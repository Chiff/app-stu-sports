import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { EventDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';

@Component({
  selector: 'sp-event-list',
  templateUrl: './event-list.component.html',
})
export class EventListComponent implements OnInit {
  activeEvents: EventDTO[];
  isLoading: boolean = false;

  constructor(private http: HttpClient, public auth: AuthService) {}

  ngOnInit(): void {
    this.isLoading = true;
    this.http.get<EventDTO[]>('api/event').subscribe((data) => {
      this.isLoading = false;
      this.activeEvents = data;
    });
  }
}
