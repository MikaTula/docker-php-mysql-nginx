import {
  ChangeDetectorRef,
  Component,
  DestroyRef,
  inject,
  OnInit,
  Optional,
  Self,
  signal,
} from '@angular/core';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import { NgControl, FormControl, ReactiveFormsModule } from '@angular/forms';
import { BaseLoading } from '../../base-loading/base-loading.class';
import { FileApiService } from '../../../service/file-api.service';
import { MatIcon } from '@angular/material/icon';
import { MatProgressSpinner } from '@angular/material/progress-spinner';
import { IFile } from '../../../inteface/file.interface';

@Component({
  selector: 'app-file-upload',
  imports: [ReactiveFormsModule, MatIcon, MatProgressSpinner],
  templateUrl: './file-upload.component.html',
  styleUrl: './file-upload.component.scss',
})
export class FileUploadComponent extends BaseLoading implements OnInit {
  private cdr = inject(ChangeDetectorRef);
  private destroyRef = inject(DestroyRef);
  private fileApiService = inject(FileApiService);
  // После успешной загрузки имени с API — чтобы не дёргать API повторно для того же id
  private loadedNameForId: number | null = null;
  protected readonly dragOver = signal(false);
  protected readonly fileName = signal<string>('');
  protected readonly uploadError = signal<string>('');

  constructor(@Self() @Optional() private ngControl: NgControl) {
    super();
    if (this.ngControl) {
      this.ngControl.valueAccessor = this;
    }
  }

  protected get control(): FormControl<number | null> {
    return this.ngControl.control as FormControl<number | null>;
  }

  ngOnInit(): void {
    // Начальное значение приходит через writeValue от FormControlName.
    // Дальше — только смены значения (например patchValue после загрузки песни).
    this.control?.valueChanges
      .pipe(takeUntilDestroyed(this.destroyRef))
      .subscribe((value) => this.syncIdToDisplayName(value));
  }

  writeValue(value: number | null): void {
    this.syncIdToDisplayName(value);
  }

  registerOnChange(): void {}
  registerOnTouched(): void {}
  setDisabledState(): void {}

  protected openFileDialog(fileInput: HTMLInputElement): void {
    if (this.control.disabled || this.loading()) {
      return;
    }

    fileInput.click();
  }

  protected onFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (!file) {
      return;
    }

    this.uploadFile(file);
    input.value = '';
  }

  protected clearFile(event: MouseEvent, fileInput: HTMLInputElement): void {
    event.stopPropagation();
    if (this.control.disabled || this.loading()) {
      return;
    }

    fileInput.value = '';
    this.fileName.set('');
    this.loadedNameForId = null;
    this.uploadError.set('');
    this.control.setValue(null);
    this.control.markAsDirty();
    this.control.markAsTouched();
  }

  protected onDrop(event: DragEvent): void {
    event.preventDefault();
    this.dragOver.set(false);

    if (this.control.disabled || this.loading()) {
      return;
    }

    const file = event.dataTransfer?.files?.[0];
    if (file) {
      this.uploadFile(file);
    }
  }

  protected onDragOver(event: DragEvent): void {
    event.preventDefault();
    if (!this.control.disabled && !this.loading()) {
      this.dragOver.set(true);
    }
  }

  protected onDragLeave(event: DragEvent): void {
    event.preventDefault();
    this.dragOver.set(false);
  }

  private uploadFile(file: File): void {
    this.uploadError.set('');
    this.setLoading(true);
    this.fileApiService.upload(file).subscribe({
      next: (uploaded) => this.applyUploadedFile(uploaded),
      error: () => {
        this.uploadError.set('File upload failed');
        this.setLoading(false);
      },
    });
  }

  private applyUploadedFile(uploaded: IFile): void {
    this.fileName.set(uploaded.originalName);
    this.loadedNameForId = uploaded.id;
    this.control.setValue(uploaded.id);
    this.control.markAsDirty();
    this.control.markAsTouched();
    this.setLoading(false);
    this.cdr.detectChanges();
  }

  private loadFileInfo(id: number): void {
    this.setLoading(true);
    this.fileApiService.getById(id).subscribe({
      next: (file) => {
        this.fileName.set(file.originalName);
        this.loadedNameForId = id;
        this.setLoading(false);
      },
      error: () => {
        this.fileName.set('');
        this.loadedNameForId = null;
        this.setLoading(false);
      },
    });
  }

  /**
   * Есть id в модели, но имя ещё не показано (или id сменился) — запрашиваем метаданные файла.
   */
  private syncIdToDisplayName(value: number | null): void {
    if (value == null || value < 1) {
      this.fileName.set('');
      this.loadedNameForId = null;
      return;
    }

    if (this.loadedNameForId === value && this.fileName()) {
      return;
    }

    this.loadFileInfo(value);
  }
}
