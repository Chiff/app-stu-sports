import { Component } from '@angular/core';
import { ActivatedRoute, NavigationEnd, ResolveEnd, Router } from '@angular/router';
import { AuthService } from './shared/shared/services/auth.service';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  public isTick = false;
  private willTick = false;
  private tickTimeout = 4000;
  public ready = false;

  constructor(public auth: AuthService, private router: Router) {
    this.router.events.subscribe((e) => {
      if (e instanceof ResolveEnd) {
        console.warn(this.router.url);
        if (e.urlAfterRedirects === `/dashboard`) {
          setTimeout(() => {
            this.ready = true;
          }, 1000);
        } else {
          console.warn(e);
          this.ready = false;
          this.isTick = false;
          this.willTick = false;
        }
      }
    });
  }

  pageActive(event: string) {
    if (event === 'dashboard' && !this.isTick && !this.willTick) {
      // eslint-disable-next-line @typescript-eslint/no-this-alias
      this.willTick = true;
      console.warn('start');
      setTimeout(() => {
        this.isTick = true;
        setTimeout(() => {
          this.isTick = false;
          this.willTick = false;
        }, this.tickTimeout);
      }, this.tickTimeout);
    }

    return this.router.url.startsWith(`/${event}`);
  }
}
