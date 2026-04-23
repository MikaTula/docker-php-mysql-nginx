import { Component, DestroyRef, inject, signal } from '@angular/core';
import { takeUntilDestroyed, toObservable } from '@angular/core/rxjs-interop';
import { switchMap } from 'rxjs';
import { SongsApiService } from '../../service/songs-api.service';
import { IPaginationGet } from '../../inteface/pagination-get.interface';
import { ISong } from '../../inteface/song.interface';
import { SongItemComponent } from '../song-item/song-item.component';
import { PopupSongsService } from '../../service/popup-songs.service';
import { MatMiniFabButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { MatPaginator, PageEvent } from '@angular/material/paginator';

@Component({
  selector: 'app-song-list',
  imports: [SongItemComponent, MatMiniFabButton, MatIcon, MatPaginator],
  templateUrl: './song-list.component.html',
  styleUrl: './song-list.component.scss',
})
export class SongListComponent {
  private songsApiService = inject(SongsApiService);
  private popupSongsService = inject(PopupSongsService);
  private destroyRef = inject(DestroyRef);

  protected paginationData = signal<IPaginationGet>({
    page: 1,
    size: 10,
    sortOrder: 'asc',
    sortBy: 'id',
  });

  protected total = signal<number>(0);

  protected list = signal<ISong[]>([]);

  constructor() {
    toObservable(this.paginationData)
      .pipe(
        switchMap((params) => this.songsApiService.list(params)),
        takeUntilDestroyed(this.destroyRef),
      )
      .subscribe((res) => {
        this.list.set(res.items);
        this.total.set(res.total);
      });
  }

  protected openCreateSong() {
    this.popupSongsService.createSong();
  }

  protected handlePageEvent($event: PageEvent) {
    // Нужен новый объект!! Старый сравнивается по ссылке и не считается измененным
    this.paginationData.update((current) => ({
      ...current,
      page: $event.pageIndex + 1,
      size: $event.pageSize,
    }));
  }
}
