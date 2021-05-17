import { Component, OnDestroy } from '@angular/core';
import { ActivatedRoute, NavigationEnd, ResolveEnd, Router } from '@angular/router';
import { AuthService } from './shared/shared/services/auth.service';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent implements OnDestroy {
  public isTick = false;
  private willTick = false;
  private tickTimeout = 4000;
  public ready = false;

  private t1: number;
  private t2: number;

  constructor(public auth: AuthService, private router: Router) {
    this.router.events.subscribe((e) => {
      if (e instanceof ResolveEnd) {
        if (e.urlAfterRedirects === `/dashboard`) {
          setTimeout(() => {
            this.ready = true;
          }, 1000);
        } else {
          this.ready = false;
          this.isTick = false;
          this.willTick = false;

          clearTimeout(this.t1);
          clearTimeout(this.t2);
        }
      }
    });
  }

  ngOnDestroy(): void {
    clearTimeout(this.t1);
    clearTimeout(this.t2);
  }

  pageActive(event: string) {
    if (event === 'dashboard' && !this.isTick && !this.willTick) {
      // eslint-disable-next-line @typescript-eslint/no-this-alias
      this.willTick = true;

      this.t1 = setTimeout(() => {
        this.isTick = true;
        this.t2 = setTimeout(() => {
          this.isTick = false;
          this.willTick = false;
        }, this.tickTimeout);
      }, this.tickTimeout);
    }

    return this.router.url.startsWith(`/${event}`);
  }
}
