import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { LocalisedTaskResourceModel } from './models/generated-swagger';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  data: LocalisedTaskResourceModel;

  constructor(private http: HttpClient) {
    this.http.get<LocalisedTaskResourceModel>('/api/event').subscribe((data) => {
      console.warn(data);
      this.data = data;
    });
  }
}
