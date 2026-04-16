import { inject, Injectable } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { SongEditComponent } from '../popups/song-edit/song-edit.component';

@Injectable({
  providedIn: 'root',
})
export class PopupSongsService {
  readonly dialog = inject(MatDialog);

  public createSong() {
    const dialogRef = this.dialog.open(SongEditComponent);

    dialogRef.afterClosed().subscribe((result) => {
      console.log(`Dialog result: ${result}`);
    });
  }

  public updateSong(id: number) {
    console.log(`updateSong`);
    const dialogRef = this.dialog.open(SongEditComponent, {
      data: { id: id },
    });

    dialogRef.afterClosed().subscribe((result) => {
      console.log(`Dialog result: ${result}`);
    });
  }


}
