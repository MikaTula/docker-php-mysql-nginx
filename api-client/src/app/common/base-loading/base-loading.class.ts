import { signal } from '@angular/core';

export abstract class BaseLoading {
  public readonly loading = signal<boolean>(false);
  public setLoading(value: boolean) {
    this.loading.set(value);
  }
}
