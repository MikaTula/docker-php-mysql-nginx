import { Routes } from '@angular/router';
import { NotFoundComponent } from './common/not-found/not-found.component';
import { ViewComponent } from './layout/view/view.component';
import { LoginComponent } from './layout/login/login.component';
import { RegisterComponent } from './layout/register/register.component';
import { SongListComponent } from './songs/song-list/song-list.component';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  {
    path: '',
    component: ViewComponent,
    canActivate: [authGuard],
    children: [
      {
        path: '',
        pathMatch: 'full',
        redirectTo: 'songs',
      },
      {
        path: 'songs',
        component: SongListComponent,
      },
    ],
  },
  {
    path: 'auth',
    component: ViewComponent,
    children: [
      {
        path: 'sign-in',
        component: LoginComponent,
      },
      {
        path: 'sign-up',
        component: RegisterComponent,
      },
    ],
  },
  {
    path: '**',
    component: ViewComponent,
    children: [
      {
        path: '',
        component: NotFoundComponent,
      },
    ],
  },
];
