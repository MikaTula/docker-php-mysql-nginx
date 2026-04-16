import { Routes } from '@angular/router';
import { NotFoundComponent } from './common/not-found/not-found.component';
import { ViewComponent } from './layout/view/view.component';
import { LoginComponent } from './layout/login/login.component';
import { SongListComponent } from './songs/song-list/song-list.component';
import { AuthService } from './service/auth.service';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  {
    path: 'auth',
    component: ViewComponent,
    children: [
      {
        path: 'login',
        component: LoginComponent,
      },
    ],
  },
  {
    path: 'private',
    component: ViewComponent,
    canActivate: [authGuard],
    children: [
      {
        path: 'songs',
        component: SongListComponent,
      },
    ],
  },
  {
    path: '**',
    component: NotFoundComponent,
  },
];
