import { Component, inject } from '@angular/core';
import { MatButton } from '@angular/material/button';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import {
  FormControl,
  FormControlStatus,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {
  MatCard,
  MatCardContent,
  MatCardFooter,
  MatCardHeader,
  MatCardTitle,
} from '@angular/material/card';
import { AuthService } from '../../service/auth.service';
import { map } from 'rxjs';
import { toSignal } from '@angular/core/rxjs-interop';
import { AsyncPipe } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-login',
  imports: [
    MatButton,
    MatFormField,
    MatLabel,
    MatInput,
    FormsModule,
    MatCard,
    MatCardHeader,
    MatCardTitle,
    MatCardContent,
    MatCardFooter,
    ReactiveFormsModule,
    AsyncPipe,
    RouterLink,
  ],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss',
})
export class LoginComponent {
  private authService: AuthService = inject(AuthService);

  protected loginForm: FormGroup<LoginData> = new FormGroup({
    login: new FormControl<string>('', {
      validators: [Validators.required, Validators.email],
      nonNullable: true,
    }),
    password: new FormControl<string>('', {
      validators: [Validators.required, Validators.minLength(8), Validators.maxLength(12)],
      nonNullable: true,
    }),
  });

  protected canLogin = this.loginForm.statusChanges.pipe(map((status) => status === 'VALID'));

  protected tryLogin() {
    if (this.loginForm.valid) {
      this.authService.login({
        email: this.loginForm.controls.login.value,
        password: this.loginForm.controls.password.value,
      });
    }
  }
}

export interface LoginData {
  login: FormControl<string>;
  password: FormControl<string>;
}
