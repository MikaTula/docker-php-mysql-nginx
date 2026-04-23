import { Component, inject, input, signal } from '@angular/core';
import { takeUntilDestroyed, toObservable } from '@angular/core/rxjs-interop';
import { distinctUntilChanged, EMPTY, finalize, map, switchMap, tap } from 'rxjs';
import { ISong } from '../../inteface/song.interface';
import { MatIconButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { MatProgressSpinner } from '@angular/material/progress-spinner';
import { PopupSongsService } from '../../service/popup-songs.service';
import { FileApiService } from '../../service/file-api.service';

@Component({
  selector: 'app-song-item',
  imports: [MatIcon, MatIconButton, MatProgressSpinner],
  templateUrl: './song-item.component.html',
  styleUrl: './song-item.component.scss',
})
export class SongItemComponent {
  public song = input.required<ISong>();

  private popupSongsService = inject(PopupSongsService);
  private fileApiService = inject(FileApiService);

  // Blob URL для <audio> — т.к. плеер работает уже после авторизации
  protected audioSrc = signal<string | null>(null);
  private blobUrl: string | null = null;

  constructor() {
    toObservable(this.song)
      .pipe(
        map((s) => s.file?.id ?? null),
        distinctUntilChanged(),
        switchMap((fileId) => {
          if (this.blobUrl) {
            URL.revokeObjectURL(this.blobUrl);
            this.blobUrl = null;
          }
          this.audioSrc.set(null);
          if (fileId === null) {
            return EMPTY;
          }
          return this.fileApiService.streamBlob(fileId).pipe(
            tap((blob) => {
              this.blobUrl = URL.createObjectURL(blob);
              this.audioSrc.set(this.blobUrl);
            }),
          );
        }),
        finalize(() => {
          if (this.blobUrl) {
            URL.revokeObjectURL(this.blobUrl);
            this.blobUrl = null;
          }
        }),
        takeUntilDestroyed(),
      )
      .subscribe();
  }

  protected openEditSong(id: number) {
    this.popupSongsService.updateSong(id);
  }
}
