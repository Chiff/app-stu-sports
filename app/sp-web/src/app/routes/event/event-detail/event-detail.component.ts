import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { parseDate } from '@annotation/ng-datepicker';
import { EventDTO } from '../../../models/sp-api';

@Component({
  selector: 'sp-event-detail',
  templateUrl: './event-detail.component.html',
})
export class EventDetailComponent implements OnInit {
  public event: EventDTO;

  constructor(private http: HttpClient, private route: ActivatedRoute) {
    this.route.params.subscribe((p) => {
      this.getEventById(p.id);
    });
  }

  ngOnInit(): void {
    // noop
  }

  private getEventById(id: string) {
    this.http.get<EventDTO>(`api/event/byid/${id}`).subscribe((data) => {
      this.event = data;
    });
  }

  isFinished(event: EventDTO): boolean {
    if (!event) return false;

    const now = new Date();
    const end = parseDate(event.event_end, 'yyyy-MM-ddTHH:mm:ss', 'sk');

    return +now > +end;
  }

  isActive(event: EventDTO): boolean {
    if (!event) return false;

    const now = new Date();
    const start = parseDate(event.event_start, 'yyyy-MM-ddTHH:mm:ss', 'sk');
    const end = parseDate(event.event_end, 'yyyy-MM-ddTHH:mm:ss', 'sk');

    return +now >= +start && +now < +end;
  }

  inReg(event: EventDTO): boolean {
    if (!event) return false;

    const now = new Date();
    const start = parseDate(event.registration_start, 'yyyy-MM-ddTHH:mm:ss', 'sk');
    const end = parseDate(event.registration_end, 'yyyy-MM-ddTHH:mm:ss', 'sk');

    return +now >= +start && +now < +end;
  }

  fresh(event: EventDTO): boolean {
    if (!event) return false;

    const now = new Date();
    const cmp = parseDate(event.registration_start, 'yyyy-MM-ddTHH:mm:ss', 'sk');

    return +now < +cmp;
  }
}
