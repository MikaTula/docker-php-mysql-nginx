import { ChangeDetectorRef, Component, inject, OnInit, signal } from '@angular/core';
import { SongsApiService } from '../../service/songs-api.service';
import { IPaginationGet } from '../../inteface/pagination-get.interface';
import { ISong } from '../../inteface/song.interface';
import { SongItemComponent } from '../song-item/song-item.component';
import { PopupSongsService } from '../../service/popup-songs.service';
import { MatMiniFabButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';

@Component({
  selector: 'app-song-list',
  imports: [SongItemComponent, MatMiniFabButton, MatIcon],
  templateUrl: './song-list.component.html',
  styleUrl: './song-list.component.scss',
})
export class SongListComponent implements OnInit {
  private songsApiService = inject(SongsApiService);
  private popupSongsService = inject(PopupSongsService);

  protected paginationData: IPaginationGet = {
    page: 1,
    size: 10,
    sortOrder: 'asc',
    sortBy: 'id',
  };

  protected list = signal<ISong[]>([]);
  ngOnInit() {
    this.songsApiService.list(this.paginationData).subscribe((res) => {
      this.list.set(res);
    });
  }

  protected openCreateSong() {
    this.popupSongsService.createSong();
  }
}
