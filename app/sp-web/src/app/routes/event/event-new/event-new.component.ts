import { Component, Input, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { zip } from 'rxjs';
import { CiselnikDTO, CiselnikTypeEnum, CustomHttpError, ErrorResponse, EventDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';

@Component({
  selector: 'sp-event-new',
  templateUrl: './event-new.component.html',
})
export class EventNewComponent {
  @ViewChild('ngForm')
  public ngForm: NgForm;

  @Input()
  public event: EventDTO = {} as EventDTO;

  @Input()
  public isPopup: boolean = false;

  public error: string;

  public eventType: CiselnikDTO[] = [];

  constructor(private http: HttpClient, private router: Router, private auth: AuthService) {
    this.http.get(`api/ciselnik/${CiselnikTypeEnum.EVENT_TYPE}`).subscribe((data: CiselnikDTO[]) => {
      this.eventType = data;
    });
  }

  send(): void {
    if (!this.auth.canDoAction('vytvoritPodujatie')) {
      this.error = 'Nemáte oprávnenie na vytvorenie nového podujatia';
      return;
    }

    this.ngForm.form.markAllAsTouched();
    if (this.ngForm.invalid) {
      this.error = 'Vyplňte všetky povinné údaje a opravte chyby';
      return;
    }

    zip(
      this.http.post<EventDTO>('api/event/create', this.event),
      this.http.post<any>(`api/system/runtask/${this.auth.getTaskId('vytvoritPodujatie')}`, null)
    ).subscribe({
      next: ([event, _]) => {
        console.warn(event);
        this.router.navigate([`/event/detail/${event.id}`]);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        this.error = err.error.error.message;
      },
    });
  }
}
