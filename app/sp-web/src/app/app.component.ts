import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { LocalisedTaskResourceModel } from './models/generated-swagger';
import { AuthService } from './shared/shared/services/auth.service';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  data: LocalisedTaskResourceModel;

  constructor(private http: HttpClient, public auth: AuthService) {
    this.http.get<LocalisedTaskResourceModel>('/api/event').subscribe((data) => {
      console.warn(data);
      this.data = data;
    });
  }
}
