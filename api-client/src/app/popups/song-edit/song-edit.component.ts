import { Component, DestroyRef, inject, OnInit } from '@angular/core';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { AsyncPipe } from '@angular/common';
import { MatButton } from '@angular/material/button';
import {
  MatCard,
  MatCardContent,
  MatCardFooter,
  MatCardHeader,
  MatCardTitle,
} from '@angular/material/card';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { SongsApiService } from '../../service/songs-api.service';
import { BaseLoading } from '../../common/base-loading/base-loading.class';
import { MatProgressSpinner } from '@angular/material/progress-spinner';
import { SpinnerComponent } from '../../common/spinner/spinner.component';
import { map } from 'rxjs';
import { ISong } from '../../inteface/song.interface';
import { SingerSelectComponent } from '../../common/controls/singer-select/singer-select.component';
import { parseNumericFormInput } from '../../utils/form.utils';

@Component({
  selector: 'app-song-edit',
  imports: [
    MatButton,
    MatCard,
    MatCardContent,
    MatCardFooter,
    MatCardHeader,
    MatCardTitle,
    MatFormField,
    MatInput,
    MatLabel,
    ReactiveFormsModule,
    SpinnerComponent,
    AsyncPipe,
    SingerSelectComponent,
  ],
  templateUrl: './song-edit.component.html',
  styleUrl: './song-edit.component.scss',
})
export class SongEditComponent extends BaseLoading implements OnInit {
  readonly dialogRef = inject(MatDialogRef<SongEditComponent>);
  readonly data = inject<{ id?: number }>(MAT_DIALOG_DATA);
  readonly songApiService = inject(SongsApiService);
  private readonly destroyRef = inject(DestroyRef);

  protected songEditForm = new FormGroup<SongEditForm>({
    name: new FormControl<string>('', {
      validators: [Validators.required, Validators.minLength(2), Validators.maxLength(200)],
      nonNullable: true,
    }),
    singer_id: new FormControl<number | null>(null, {
      validators: [Validators.required, Validators.min(1)],
      nonNullable: false,
    }),
    year: new FormControl<number | null>(null, {
      validators: [Validators.required, Validators.min(1900), Validators.max(2100)],
      nonNullable: false,
    }),
  });

  protected canSave = this.songEditForm.statusChanges.pipe(map((status) => status == 'VALID'));

  ngOnInit() {
    const id = this?.data?.id;
    if (id) {
      this.setLoading(true);
      this.songApiService.getById(id).subscribe((song) => {
        this.songEditForm.patchValue({
          name: song?.name,
          singer_id: song?.singer.id,
          year: song?.year,
        });
        this.setLoading(false);
      });
    }
  }

  protected save() {
    if (this.songEditForm.valid) {
      this.setLoading(true);

      const id = this?.data?.id;
      if (id) {
        this.songApiService.update(id, this.songEditForm.value as ISong).subscribe((song) => {
          this.setLoading(false);
          this.dialogRef.close(true);
        });
      } else {
        this.songApiService.create(this.songEditForm.value as ISong).subscribe((song) => {
          this.setLoading(false);
          this.dialogRef.close(true);
        });
      }
    }
  }
}

export interface SongEditForm {
  name: FormControl<string>;
  singer_id: FormControl<number | null>;
  year: FormControl<number | null>;
}
