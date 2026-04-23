import { inject, Injectable } from '@angular/core';
import { ISignInData } from '../inteface/sign-in-data.interface';
import { ISignUpData } from '../inteface/sign-up-data.interface';
import { HttpClient } from '@angular/common/http';
import { IBaseResponse } from '../inteface/base-response.interface';
import { ILoginResult } from '../inteface/login-result.interface';
import { map } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthApiService {
  private path = '/api/';
  private http = inject(HttpClient);

  public login(data: ISignInData) {
    return this.http
      .post<IBaseResponse<ILoginResult>>(this.path + 'custom-login', data)
      .pipe(map((res) => res.data));
  }

  public register(data: ISignUpData) {
    return this.http
      .post<IBaseResponse<ILoginResult>>(this.path + 'custom-register', data)
      .pipe(map((res) => res.data));
  }

  public logout() {
    return this.http
      .get<IBaseResponse<void>>(this.path + 'custom-logout');
  }
}
