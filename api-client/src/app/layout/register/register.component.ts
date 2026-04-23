import { Component, inject } from '@angular/core';
import { MatButton } from '@angular/material/button';
import { MatFormField, MatInput, MatLabel } from '@angular/material/input';
import {
  AbstractControl,
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  ValidationErrors,
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
import { map, startWith } from 'rxjs';
import { AsyncPipe } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-register',
  imports: [
    MatButton,
    MatFormField,
    MatLabel,
    MatInput,
    MatCard,
    MatCardHeader,
    MatCardTitle,
    MatCardContent,
    MatCardFooter,
    ReactiveFormsModule,
    AsyncPipe,
    RouterLink,
  ],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss',
})
export class RegisterComponent {
  private authService: AuthService = inject(AuthService);

  protected registerForm: FormGroup<RegisterData> = new FormGroup(
    {
      name: new FormControl<string>('', {
        validators: [Validators.required],
        nonNullable: true,
      }),
      email: new FormControl<string>('', {
        validators: [Validators.required, Validators.email],
        nonNullable: true,
      }),
      password: new FormControl<string>('', {
        validators: [Validators.required, Validators.minLength(8), Validators.maxLength(12)],
        nonNullable: true,
      }),
      c_password: new FormControl<string>('', {
        validators: [Validators.required, Validators.minLength(8), Validators.maxLength(12)],
        nonNullable: true,
      }),
    },
    { validators: [passwordsMatchValidator] },
  );

  protected canSubmit = this.registerForm.statusChanges.pipe(
    startWith(this.registerForm.status),
    map((status) => status === 'VALID'),
  );

  protected tryRegister() {
    if (this.registerForm.valid) {
      this.authService.register({
        name: this.registerForm.controls.name.value,
        email: this.registerForm.controls.email.value,
        password: this.registerForm.controls.password.value,
        c_password: this.registerForm.controls.c_password.value,
      });
    }
  }
}

export interface RegisterData {
  name: FormControl<string>;
  email: FormControl<string>;
  password: FormControl<string>;
  c_password: FormControl<string>;
}

export function passwordsMatchValidator(group: AbstractControl): ValidationErrors | null {
  const g = group as FormGroup<RegisterData>;
  const p = g.controls.password.value;
  const c = g.controls.c_password.value;
  if (!p || !c || p === c) {
    return null;
  }
  return { passwordMismatch: true };
}
