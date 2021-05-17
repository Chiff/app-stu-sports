import { Component, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { parseDate } from '@annotation/ng-datepicker';
import { fromPromise } from 'rxjs/internal-compatibility';
import { zip } from 'rxjs';
import { NgForm } from '@angular/forms';
import { AccountModel, CustomHttpError, ErrorResponse, EventDTO, TeamDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';

@Component({
  selector: 'sp-event-detail',
  templateUrl: './event-detail.component.html',
})
export class EventDetailComponent {
  public EVT_ACTIONS = {
    addTeam: '1',
  };

  @ViewChild('ngForm')
  private ngForm: NgForm;

  public user: AccountModel;
  public availibleTeams: TeamDTO[] = [];

  public event: EventDTO;

  public addTeam: boolean = false;
  public teamId: string = null;
  public addTeamError: string = null;

  constructor(private http: HttpClient, private route: ActivatedRoute, public auth: AuthService) {
    this.route.params.subscribe((p) => {
      this.getEventById(p.id);
    });
  }

  private getEventById(id: string) {
    if (!this.auth?.isLogged()) {
      this.http.get<EventDTO>(`api/event/byid/${id}`).subscribe((data) => {
        this.event = data;
        this.event.available_transitions = null;
      });

      return;
    }

    zip(this.http.get<EventDTO>(`api/event/byid/${id}`), fromPromise(this.auth.user(true))).subscribe(([event, user]) => {
      this.event = event;
      this.user = user;

      this.availibleTeams =
        this.user.teams?.filter((team) => {
          const members = team.users?.length || 0;

          return event.min_team_members <= members && members >= event.max_team_members;
        }) || [];

      if (
        event.min_team_members === 1 &&
        event.max_team_members === 1 &&
        !this.availibleTeams.find((t) => t.users.length === 1)
      ) {
        this.availibleTeams.push({
          team_name: `${user.firstname} ${user.surname}`,
          id: '-1',
        } as TeamDTO);
      }

      const teamsOnEvent = event.teams_on_event.map((t) => t.id);
      this.availibleTeams = this.availibleTeams.filter((t) => !teamsOnEvent.includes(t.id));

      if (this.availibleTeams?.length === 1) {
        this.teamId = this.availibleTeams[0].id;
      }
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

  public hasAction(action: Actions): boolean {
    const a = this.EVT_ACTIONS[action];

    if (!this.event?.available_transitions || !a) return false;
    return !!this.event.available_transitions.taskReference.find((e) => e.transitionId === a);
  }

  add() {
    this.addTeamError = null;

    if (this.ngForm.invalid) {
      this.addTeamError = 'Vyplňte všetky povinné údaje';
      return;
    }

    this.http
      .post('api/event/addTeamById', {
        event_id: this.event.id,
        team_id: this.teamId,
      })
      .subscribe({
        next: () => {
          this.getEventById(this.event.id);
          this.addTeam = false;
        },
        error: (err: CustomHttpError<ErrorResponse>) => {
          this.addTeamError = err.error.error.message;
        },
      });
  }
}

export type Actions = 'addTeam';
