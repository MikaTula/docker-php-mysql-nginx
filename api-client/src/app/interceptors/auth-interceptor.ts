import { HttpInterceptorFn } from '@angular/common/http';
import { AuthService } from '../service/auth.service';
import { inject } from '@angular/core';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const authService: AuthService = inject(AuthService);
  let r = authService.getAuthData();

  const authReq = req.clone({
    headers: req.headers.set('Authorization', authService.getAuthData()),
  });

   return next(authReq);
};
