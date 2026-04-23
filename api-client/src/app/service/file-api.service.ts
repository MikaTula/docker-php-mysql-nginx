import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs';
import { IBaseResponse } from '../inteface/base-response.interface';
import { IFile } from '../inteface/file.interface';

@Injectable({
  providedIn: 'root',
})
export class FileApiService {
  private path = '/api/files';
  private http = inject(HttpClient);

  public upload(file: File) {
    const formData = new FormData();
    formData.append('file', file);

    return this.http.post<IBaseResponse<IFile>>(this.path, formData).pipe(map((res) => res.data));
  }

  public getById(id: number) {
    return this.http.get<IBaseResponse<IFile>>(this.path + '/' + id).pipe(map((res) => res.data));
  }
  public streamBlob(id: number) {
    return this.http.get(`${this.path}/${id}/stream`, { responseType: 'blob' });
  }
}
