import { Component, OnDestroy, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { parseDate } from '@annotation/ng-datepicker';
import { fromPromise } from 'rxjs/internal-compatibility';
import { zip } from 'rxjs';
import { NgForm } from '@angular/forms';
import { CustomHttpError, ErrorResponse, EventDTO, MyNotificationsDTO, TeamDTO, UserDTO } from '../../../models/sp-api';
import { AuthService } from '../../../shared/shared/services/auth.service';
import { EventNewComponent } from '../event-new/event-new.component';

@Component({
  selector: 'sp-event-detail',
  templateUrl: './event-detail.component.html',
})
export class EventDetailComponent implements OnDestroy {
  public EVT_ACTIONS = {
    addTeam: '1',
    removeTeam: '66',
    cancelEvent: '5',
    startEvent: '6',
    finishEvent: '7',
    editEvent: '96',
    addPoint: '999',
  };

  @ViewChild('ngForm')
  private ngForm: NgForm;

  @ViewChild('eventNewComponent')
  private eventNewComponent: EventNewComponent;

  public user: UserDTO;
  public availibleTeams: TeamDTO[] = [];

  public event: EventDTO;
  public editingEvent: EventDTO;

  public addTeam: boolean = false;
  public teamId: string = null;
  public addTeamError: string = null;

  private refreshInterval: NodeJS.Timeout;
  public isEditing: boolean = false;
  public notifications: MyNotificationsDTO;

  constructor(private http: HttpClient, private route: ActivatedRoute, public auth: AuthService) {
    this.route.params.subscribe((p) => {
      this.getEventById(p.id);
    });
  }

  ngOnDestroy(): void {
    clearInterval(this.refreshInterval);
  }

  public getEventById(id: string): void {
    clearInterval(this.refreshInterval);
    this.refreshInterval = setInterval(() => {
      this.getEventById(id);
    }, 10000);

    this.http.get<MyNotificationsDTO>(`api/notification/Event/${id}`).subscribe((notif) => {
      this.notifications = notif;
    });

    if (!this.auth?.isLogged()) {
      this.http.get<EventDTO>(`api/event/byid/${id}/guest`).subscribe((data) => {
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

          return event.min_team_members <= members && members <= event.max_team_members;
        }) || [];

      if (
        event.min_team_members === 1 &&
        event.max_team_members === 1 &&
        !this.availibleTeams.find((t) => t.users.length === 1 && !t.disabled)
      ) {
        this.availibleTeams.push({
          team_name: `${user.firstname} ${user.surname}`,
          id: '-1',
        } as TeamDTO);
      }

      const teamsOnEvent = event.teams_on_event.map((t) => t.id);
      this.availibleTeams = this.availibleTeams.filter((t) => !teamsOnEvent.includes(t.id)).filter((t) => !t.disabled);

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

  shoudStartSoon(event: EventDTO): boolean {
    if (!event) return false;

    const now = new Date();
    const start = parseDate(event.registration_end, 'yyyy-MM-ddTHH:mm:ss', 'sk');
    const end = parseDate(event.event_start, 'yyyy-MM-ddTHH:mm:ss', 'sk');

    return +now >= +start && +now < +end;
  }

  fresh(event: EventDTO): boolean {
    if (!event) return false;

    const now = new Date();
    const cmp = parseDate(event.registration_start, 'yyyy-MM-ddTHH:mm:ss', 'sk');

    return +now < +cmp;
  }

  public isOnAnyTeam(): boolean {
    if (!this?.event?.teams_on_event?.length) {
      return false;
    }

    return this.event.teams_on_event.some((t) => t.users.some((u) => this.user?.id === u.id));
  }

  public hasAction(action: Actions): boolean {
    const a = this.EVT_ACTIONS[action];

    if (!this.event?.available_transitions?.taskReference?.length || !a) return false;
    return !!this.event.available_transitions.taskReference.find((e) => e.transitionId === a);
  }

  add(): void {
    this.addTeamError = null;

    if (this.ngForm.invalid) {
      this.addTeamError = 'Vyplňte všetky povinné údaje';
      return;
    }

    this.http
      .post('api/event/addTeamById', {
        event_id: this.event.id,
        team_id: this.teamId,
        task_id: this.getTransitionString('addTeam'),
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

  remove(t: TeamDTO): void {
    if (!window.confirm('Naozaj si prajete odhlásiť tento tím z podujatie?')) {
      return;
    }

    this.http
      .delete(`api/event/${this.event.id}/teams/delete/${t.id}`, {
        params: { task_id: this.getTransitionString('removeTeam') },
      })
      .subscribe({
        next: () => {
          this.getEventById(this.event.id);
        },
        error: (err: CustomHttpError<ErrorResponse>) => {
          window.alert(err.error.error.message);
        },
      });
  }

  private getTransitionString(action: Actions) {
    return this.event.available_transitions.taskReference.find((t) => t.transitionId === this.EVT_ACTIONS[action])?.stringId;
  }

  startEvent(): void {
    if (!window.confirm('Naozaj si prajete zahájiť podujatie?')) {
      return;
    }

    this.http.post(`api/event/runtask/${this.getTransitionString('startEvent')}`, null).subscribe({
      next: () => {
        this.getEventById(this.event.id);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        window.alert(err.error.error.message);
      },
    });
  }

  addPoint(t: TeamDTO): void {
    if (!window.confirm('Naozaj si prajete pridať bod pre tento tím?')) {
      return;
    }

    this.http
      .post(`api/event/${this.event.id}/points`, {
        team_id: t.id,
        points: 1,
      })
      .subscribe({
        next: () => {
          this.getEventById(this.event.id);
        },
        error: (err: CustomHttpError<ErrorResponse>) => {
          window.alert(err.error.error.message);
        },
      });
  }

  finishEvent(t: TeamDTO): void {
    if (!window.confirm('Naozaj si prajete vybrať víťaza?')) {
      return;
    }

    this.http
      .post(`api/event/${this.event.id}/finish`, {
        winner_id: t.id,
        task_id: this.getTransitionString('finishEvent'),
      })
      .subscribe({
        next: () => {
          this.getEventById(this.event.id);
        },
        error: (err: CustomHttpError<ErrorResponse>) => {
          window.alert(err.error.error.message);
        },
      });
  }

  isWinner(t: TeamDTO): boolean {
    return !!this.event?.event_team_info.find((x) => x.team_id === t.id)?.is_winner;
  }

  canBeStarted(event: EventDTO): boolean {
    const teamCount = event?.teams_on_event?.length || 0;
    return event?.min_teams <= teamCount && teamCount <= event?.max_teams;
  }

  cancelEvent(): void {
    if (!window.confirm('Naozaj si prajete zrušiť toto podujatie?')) {
      return;
    }

    this.http
      .put(`api/event/disable/${this.event.id}`, {
        task_id: this.getTransitionString('cancelEvent'),
      })
      .subscribe({
        next: () => {
          this.getEventById(this.event.id);
        },
        error: (err: CustomHttpError<ErrorResponse>) => {
          window.alert(err.error.error.message);
        },
      });
  }

  editEvent() {
    clearInterval(this.refreshInterval);
    this.isEditing = true;
    this.editingEvent = JSON.parse(JSON.stringify(this.event)) as EventDTO;
  }

  sendEditedEvent(): void {
    if (!window.confirm('Naozaj si prajete upraviť toto podujatie?')) {
      return;
    }

    this.eventNewComponent.error = null;
    this.eventNewComponent.ngForm.form.markAllAsTouched();
    if (this.eventNewComponent.ngForm.invalid) {
      this.eventNewComponent.error = 'Vyplňte všetky povinné údaje a opravte chyby';
      return;
    }

    // this.eventNewComponent.error = 'update not implemented!!!';
    this.http.put(`api/event/${this.event.id}`, this.editingEvent).subscribe({
      next: (event: EventDTO) => {
        this.isEditing = false;
        this.editingEvent = null;
        this.getEventById(event.id);
      },
      error: (err: CustomHttpError<ErrorResponse>) => {
        this.eventNewComponent.error = err.error.error.message;
      },
    });
  }

  cancelEdit() {
    this.isEditing = false;
    this.getEventById(this.event.id);
  }
}

export type Actions = 'addTeam' | 'removeTeam' | 'cancelEvent' | 'finishEvent' | 'startEvent' | 'editEvent' | 'addPoint';
