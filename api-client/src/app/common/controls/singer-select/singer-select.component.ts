import { Component, inject, OnInit, Optional, Self, signal } from '@angular/core';
import { ControlValueAccessor, FormControl, ReactiveFormsModule, NgControl } from '@angular/forms';
import { SingerApiService } from '../../../service/singer-api.service';
import { ISinger } from '../../../inteface/singer.interface';
import { BaseLoading } from '../../base-loading/base-loading.class';
import { IPaginationGet } from '../../../inteface/pagination-get.interface';
import { MatFormField, MatLabel } from '@angular/material/input';
import { MatOption, MatSelect } from '@angular/material/select';

@Component({
  selector: 'app-singer-select',
  imports: [MatFormField, MatLabel, MatSelect, MatOption, ReactiveFormsModule],
  templateUrl: './singer-select.component.html',
  styleUrl: './singer-select.component.scss',
})
export class SingerSelectComponent extends BaseLoading implements ControlValueAccessor, OnInit {
  private singerApiService = inject(SingerApiService);

  protected singerList = signal<ISinger[]>([]);

  protected paginationData: IPaginationGet = {
    page: 1,
    size: 10,
    sortOrder: 'asc',
    sortBy: 'id',
  };

  constructor(@Self() @Optional() private ngControl: NgControl) {
    super();
    if (this.ngControl) {
      this.ngControl.valueAccessor = this;
    }
  }

  protected get control(): FormControl<number | null> {
    return this.ngControl.control as FormControl<number | null>;
  }
  ngOnInit() {
    this.setLoading(true);
    this.singerApiService.list(this.paginationData).subscribe((singers) => {
      this.singerList.set(singers);
      this.setLoading(false);
    });

    this.control.valueChanges.subscribe((value) => {
      this.control.setValue(value, { emitEvent: false });
    });
  }

  writeValue(): void {}
  registerOnChange(): void {}
  registerOnTouched(): void {}
  setDisabledState(): void {}

  protected get getSinger(): ISinger | undefined {
    return this.singerList().find((singer) => singer.id === this.control.value);
  }
}
