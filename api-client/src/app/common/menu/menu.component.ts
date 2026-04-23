import { Component, inject } from '@angular/core';
import { MatButton } from '@angular/material/button';
import { AuthService } from '../../service/auth.service';
import { MatIcon } from '@angular/material/icon';
import { MatToolbar } from '@angular/material/toolbar';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-menu',
  imports: [MatButton, MatIcon, MatToolbar, RouterLink, RouterLinkActive],
  templateUrl: './menu.component.html',
  styleUrl: './menu.component.scss',
})
export class MenuComponent {
  protected authService: AuthService = inject(AuthService);
  private router = inject(Router);

  protected isAppRouteActive(): boolean {
    const path = this.router.url.split(/[?#]/)[0];
    return path === '/' || path === '' || path === '/songs';
  }
}
