import { Component, OnInit, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { CustomHttpError, ErrorResponse, TeamDTO } from '../../../models/sp-api';

@Component({
  selector: 'sp-team-new',
  templateUrl: './team-new.component.html',
})
export class TeamNewComponent {
  @ViewChild('ngForm')
  private ngForm: NgForm;

  public team: TeamDTO = {} as TeamDTO;
  public error: string;

  constructor(private http: HttpClient, private router: Router) {}

  send(): void {
    this.error = null;

    if (this.ngForm.invalid) {
      this.error = 'Vyplňte všetky povinné údaje';
      return;
    }

    this.http.post('api/team/create', this.team).subscribe({
      next: (team: TeamDTO) => {
        this.router.navigate([`/team/detail/${team.id}`]);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        this.error = err.error.error.message;
      },
    });
  }
}
