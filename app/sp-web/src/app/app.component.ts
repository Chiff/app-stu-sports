import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from './shared/shared/services/auth.service';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  constructor(public auth: AuthService, private router: Router) {}

  pageActive(event: string) {
    return this.router.url.startsWith(`/${event}`);
  }
}
