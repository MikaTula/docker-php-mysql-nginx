import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { IBaseResponse } from '../inteface/base-response.interface';
import { concat, map, of, switchMap, tap } from 'rxjs';
import { IPaginationGet } from '../inteface/pagination-get.interface';
import { ISong } from '../inteface/song.interface';
import { StringUtils } from '../utils/string.utils';
import { SongsRootService } from './songs-root.service';

@Injectable({
  providedIn: 'root',
})
export class SongsApiService {
  private path = '/api/songs';
  private http = inject(HttpClient);
  private songsRootService = inject(SongsRootService);

  public list(data: IPaginationGet) {
    return concat(of(0), this.songsRootService.needUpdate$)
      .pipe(
        switchMap(() =>
          this.http.get<IBaseResponse<{ items: ISong[] }>>(this.path, {
            params: StringUtils.getStringFromPagination(data),
          }),
        ),
      )
      .pipe(map((res) => res.data.items));
  }

  public getById(id: number) {
    return this.http.get<IBaseResponse<ISong>>(this.path + '/' + id).pipe(map((res) => res.data));
  }

  public update(id: number, song: ISong) {
    return this.http.put<IBaseResponse<ISong>>(this.path + '/' + id, song).pipe(
      tap(() => this.songsRootService.needUpdate$.next()),
      map((res) => res.data),
    );
  }

  public create(song: ISong) {
    return this.http.post<IBaseResponse<ISong>>(this.path, song).pipe(
      tap(() => this.songsRootService.needUpdate$.next()),
      map((res) => res.data),
    );
  }
}
