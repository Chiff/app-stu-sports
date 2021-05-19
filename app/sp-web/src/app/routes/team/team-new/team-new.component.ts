import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { zip } from 'rxjs';
import { CustomHttpError, ErrorResponse, EventDTO, TeamDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';

@Component({
  selector: 'sp-team-new',
  templateUrl: './team-new.component.html',
})
export class TeamNewComponent {
  @ViewChild('ngForm')
  private ngForm: NgForm;

  public team: TeamDTO = {} as TeamDTO;
  public error: string;

  constructor(private http: HttpClient, private router: Router, private auth: AuthService) {}

  send(): void {
    if (!this.auth.canDoAction('vytvoritTim')) {
      this.error = 'Nemáte oprávnenie na vytvorenie nového podujatia';
      return;
    }

    this.ngForm.form.markAllAsTouched();
    if (this.ngForm.invalid) {
      this.error = 'Vyplňte všetky povinné údaje a opravte chyby';
      return;
    }

    zip(
      this.http.post<TeamDTO>('api/team/create', this.team),
      this.http.post<any>(`api/system/runtask/${this.auth.getTaskId('vytvoritTim')}`, null)
    ).subscribe({
      next: ([team, _]) => {
        console.warn(team);
        this.router.navigate([`/team/detail/${team.id}`]);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        this.error = err.error.error.message;
      },
    });
  }
}
