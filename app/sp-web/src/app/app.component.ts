import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'sp-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  data: any;

  constructor(private http: HttpClient) {
    this.http.get('/api/event').subscribe((data) => {
      this.data = data;
    });
  }
}
