import { Component } from '@angular/core';
import { AuthService } from './shared/shared/services/auth.service';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  constructor(public auth: AuthService) {}
}
