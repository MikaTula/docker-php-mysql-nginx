import { Component, inject, input } from '@angular/core';
import { ISong } from '../../inteface/song.interface';
import { MatButton, MatIconButton } from '@angular/material/button';
import { MatIcon } from '@angular/material/icon';
import { PopupSongsService } from '../../service/popup-songs.service';

@Component({
  selector: 'app-song-item',
  imports: [MatIcon, MatIconButton],
  templateUrl: './song-item.component.html',
  styleUrl: './song-item.component.scss',
})
export class SongItemComponent {
  public song = input.required<ISong>();

  private popupSongsService = inject(PopupSongsService);

  protected openEditSong(id: number) {
    this.popupSongsService.updateSong(id);
  }


}
