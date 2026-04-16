import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ISignInData } from '../inteface/sign-in-data.interface';
import { IBaseResponse } from '../inteface/base-response.interface';
import { ILoginResult } from '../inteface/login-result.interface';
import { map, tap } from 'rxjs';
import { IPaginationGet } from '../inteface/pagination-get.interface';
import { ISong } from '../inteface/song.interface';
import { StringUtils } from '../utils/string.utils';
import { ISinger } from '../inteface/singer.interface';

@Injectable({
  providedIn: 'root',
})
export class SingerApiService {
  private path = '/api/singers';
  private http = inject(HttpClient);

  public list(data: IPaginationGet) {
    const r = StringUtils.getStringFromPagination(data);

    return (
      this.http
        // .get<IBaseResponse<ISong[]>>(this.path, { params: StringUtils.getStringFromPagination(data) })
        .get<IBaseResponse<{ items: ISinger[] }>>(
          this.path + '?page=1&size=100&sortBy=id&sortOrder=asc',
        )
        .pipe(map((res) => res.data?.items))
    );
  }

}
