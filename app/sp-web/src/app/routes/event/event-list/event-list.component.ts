import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { EventDTO } from '../../../models/sp-api';

@Component({
  selector: 'sp-event-list',
  templateUrl: './event-list.component.html',
  styles: [],
})
export class EventListComponent implements OnInit {
  activeEvents: EventDTO[];

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.http.get<EventDTO[]>('api/event').subscribe((data) => {
      this.activeEvents = data;
    });
  }
}
