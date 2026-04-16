import { Component, inject } from '@angular/core';
import { MatButton } from '@angular/material/button';
import { AuthService } from '../../service/auth.service';
import { MatIcon } from '@angular/material/icon';
import { Router } from '@angular/router';

@Component({
  selector: 'app-menu',
  imports: [MatButton, MatIcon],
  templateUrl: './menu.component.html',
  styleUrl: './menu.component.scss',
})
export class MenuComponent {
  protected authService: AuthService = inject(AuthService);
  protected router: Router = inject(Router);

  protected toLogin() {
    this.router.navigate(['auth', 'login']).then();
  }
}
