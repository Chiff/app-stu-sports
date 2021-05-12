import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { TeamDTO } from '../../../models/sp-api';

@Component({
  selector: 'sp-team-detail',
  templateUrl: './team-detail.component.html',
})
export class TeamDetailComponent {
  public team: TeamDTO;

  constructor(private http: HttpClient, private route: ActivatedRoute) {
    this.route.params.subscribe((p) => {
      this.getTeamById(p.id);
    });
  }

  private getTeamById(id: number): void {
    this.http.get<TeamDTO>(`api/team/${id}`).subscribe((data) => {
      this.team = data;
    });
  }
}
