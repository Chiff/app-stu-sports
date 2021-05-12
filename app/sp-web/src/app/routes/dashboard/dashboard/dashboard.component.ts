import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AutoUnsubscribe, takeWhileAlive } from 'take-while-alive';
import { takeWhile } from 'rxjs/operators';
import { AccountModel, EventDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';

@AutoUnsubscribe()
@Component({
  selector: 'sp-dashboard',
  templateUrl: './dashboard.component.html',
  styles: [],
})
export class DashboardComponent implements OnInit {
  myEvents: EventDTO[];
  user: AccountModel;

  constructor(private http: HttpClient, private auth: AuthService) {
    this.auth.onUserChangeObservable.pipe(takeWhileAlive(this)).subscribe((data) => {
      this.user = data;
    });
  }

  ngOnInit(): void {
    this.http.get<EventDTO[]>('api/event/my').subscribe((data) => {
      this.myEvents = data;
    });
  }
}
