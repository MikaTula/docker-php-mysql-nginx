import { computed, effect, inject, Injectable, signal } from '@angular/core';
import { AuthApiService } from './auth-api.service';
import { ISignInData } from '../inteface/sign-in-data.interface';
import { ISignUpData } from '../inteface/sign-up-data.interface';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private authApiService = inject(AuthApiService);
  private router = inject(Router);

  private varName = 'name';
  private varToken = 'token';

  public name = signal<string | null>(localStorage.getItem(this.varName) ?? null);
  private token = signal<string | null>(localStorage.getItem(this.varToken) ?? null);
  public isAuth = computed(() => this.token() !== null && this.token() !== '');
  constructor() {
    effect(() => {
      localStorage.setItem(this.varToken, this.token() ?? '');
    });
    effect(() => {
      localStorage.setItem(this.varName, this.name() ?? '');
    });
  }

  public login(loginData: ISignInData) {
    this.authApiService.login(loginData).subscribe((loginResult) => {
      this.token.set(loginResult.token);
      this.name.set(loginResult.name);
      this.router.navigate(['/songs']).then();
    });
  }

  public register(signUpData: ISignUpData) {
    this.authApiService.register(signUpData).subscribe((loginResult) => {
      this.token.set(loginResult.token);
      this.name.set(loginResult.name);
      this.router.navigate(['/songs']).then();
    });
  }

  public logout() {
    this.authApiService.logout().subscribe( () => this.logoutInner());
  }

  public logoutInner() {
    this.token.set(null);
    this.name.set(null);
    this.router.navigate(['auth', 'sign-in']).then();
  }

  public getAuthData(): string {
    return 'Bearer ' + this.token();
  }
}
