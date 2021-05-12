import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root',
})
export class AuthGuard implements CanActivate {
  constructor(private auth: AuthService) {}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    return this.canAccessRoute(state.url);
  }

  canAccessRoute(path: string): boolean {
    if (path === '/logout.html') {
      return !this.auth.isLogged();
    }

    if (!this.auth.isLogged()) {
      window.location.replace('/login');
      return false;
    }

    if (path === '/') return true;

    if (!this.auth.isLogged()) return false;

    if (new RegExp('^/dashboard.*').test(path)) return this.auth.isLogged();
    if (new RegExp('^/team.*').test(path)) return this.auth.isLogged();
    if (new RegExp('^/event/new').test(path)) return this.auth.isLogged();

    // if (new RegExp('^/app-settings').test(path))
    //   return this.auth.hasPermission([
    //     AccountPermissionEnum.APP_EDIT
    //   ]);

    console.warn(`denied [${path}]`, this.auth.userSnapshot);

    // TODO: docasne vsetko povolit
    if (new RegExp('^/').test(path)) return true;
    return false;
  }
}
