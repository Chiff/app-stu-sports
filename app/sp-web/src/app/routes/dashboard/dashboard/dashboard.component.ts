import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AutoUnsubscribe } from 'take-while-alive';
import { EventDTO, UserDTO } from '../../../models/sp-api';

@AutoUnsubscribe()
@Component({
  selector: 'sp-dashboard',
  templateUrl: './dashboard.component.html',
})
export class DashboardComponent implements OnInit {
  myEvents: EventDTO[];
  user: UserDTO;

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.http.get<EventDTO[]>('api/event/my').subscribe((data) => {
      this.myEvents = data;
    });

    this.http.get('api/user/detail').subscribe((data: UserDTO) => {
      this.user = data;
    });
  }
}
