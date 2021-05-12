import {Component, OnInit, ViewChild} from '@angular/core';
import { CustomHttpError, ErrorResponse, EventDTO } from '../../../models/sp-api';
import {NgForm} from "@angular/forms";
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";

@Component({
  selector: 'sp-event-new',
  templateUrl: './event-new.component.html',
  styles: [
  ]
})
export class EventNewComponent {
  @ViewChild('ngForm')
  private ngForm: NgForm;

  public event: EventDTO = {} as EventDTO;
  public error: string;

  constructor(private http: HttpClient, private router: Router) {}

  send(): void {
    this.error = null;

    if (this.ngForm.invalid) {
      this.error = 'Vyplňte všetky povinné údaje';
      return;
    }

    this.http.post('api/event/create', this.event).subscribe({
      next: (event: EventDTO) => {
        this.router.navigate([`/event/detail/${event.id}`]);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        this.error = err.error.error.message;
      },
    });
  }
}
