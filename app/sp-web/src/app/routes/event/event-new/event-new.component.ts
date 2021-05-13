import { Component, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { CiselnikDTO, CiselnikTypeEnum, CustomHttpError, ErrorResponse, EventDTO } from '../../../models/sp-api';

@Component({
  selector: 'sp-event-new',
  templateUrl: './event-new.component.html',
})
export class EventNewComponent {
  @ViewChild('ngForm')
  private ngForm: NgForm;

  public event: EventDTO = {} as EventDTO;
  public error: string;

  public eventType: CiselnikDTO[] = [];

  constructor(private http: HttpClient, private router: Router) {
    this.http.get(`api/ciselnik/${CiselnikTypeEnum.EVENT_TYPE}`).subscribe((data: CiselnikDTO[]) => {
      this.eventType = data;
    });
  }

  send(): void {
    this.error = null;

    this.ngForm.form.markAllAsTouched();
    if (this.ngForm.invalid) {
      this.error = 'Vyplňte všetky povinné údaje a opravte chyby';
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
