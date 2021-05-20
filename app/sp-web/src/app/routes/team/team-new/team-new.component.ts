import { Component, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { CustomHttpError, ErrorResponse, TeamDTO } from '../../../models/sp-api';
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

    this.team['task_id'] = this.auth.getTaskId('vytvoritTim');
    this.http.post<TeamDTO>('api/team/create', this.team).subscribe({
      next: (team) => {
        console.warn(team);
        this.router.navigate([`/team/detail/${team.id}`]);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        this.error = err.error.error.message;
      },
    });
  }
}
