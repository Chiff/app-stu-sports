import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AutoUnsubscribe } from 'take-while-alive';
import { MyEventsDTO, MyNotificationsDTO, UserDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';

@AutoUnsubscribe()
@Component({
  selector: 'sp-dashboard',
  templateUrl: './dashboard.component.html',
})
export class DashboardComponent implements OnInit {
  myEvents: MyEventsDTO;
  user: UserDTO;
  notifications: MyNotificationsDTO;

  constructor(private http: HttpClient, public auth: AuthService) {}

  ngOnInit(): void {
    this.http.get<MyEventsDTO>('api/event/my').subscribe((data) => {
      this.myEvents = data;
    });

    this.auth.user(true).then((data) => {
      this.user = data;

      // notifications: MyNotificationsDTO;
      this.http.get<MyNotificationsDTO>(`api/notification/User/${this.user.id}`).subscribe((notif) => {
        this.notifications = notif;
      });
    });
  }
}
