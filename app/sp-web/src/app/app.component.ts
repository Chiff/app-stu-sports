import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from './shared/shared/services/auth.service';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  public isTick = false;
  private willTick = false;
  private tickTimeout = 4000;

  constructor(public auth: AuthService, private router: Router) {}

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
