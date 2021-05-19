import { HttpErrorResponse } from '@angular/common/http';
import { TaskReferenceModel } from './generated-swagger';

export interface EventDTO {
  id: string;
  user_id: string;
  name: string;
  min_teams: number;
  max_teams: number;
  min_team_members: number;
  max_team_members: number;
  created_at: DateTimeAsString;
  updated_at: DateTimeAsString;
  registration_start: DateTimeAsString;
  registration_end: DateTimeAsString;
  event_start: DateTimeAsString;
  event_end: DateTimeAsString;
  owner: UserDTO;
  type: CiselnikDTO;
  description: string;
  teams_on_event: TeamDTO[];
  available_transitions: { taskReference: TaskReferenceModel[] };
  event_team_info: EventTeamDTO[];
  disabled: boolean;
}

export interface EventTeamDTO {
  team_id: string;
  team_name: string;
  event_id: string;
  points: number;
  is_winner: boolean;
}

export interface MyEventsDTO {
  owned: EventDTO[];
  ended: EventDTO[];
  upcoming: EventDTO[];
}

export interface TeamDTO {
  id: string;
  team_name: string;
  disabled: string;
  created_at: DateTimeAsString;
  updated_at: DateTimeAsString;
  registration_start: DateTimeAsString;
  owner: UserDTO;
  users: UserDTO[];
  active_events: EventDTO[];
  ended_events: EventDTO[];
  future_events: EventDTO[];
  points: number;
  wins: number;
  events_total: number;
}

export interface UserDTO {
  id: string;
  firstname: string;
  surname: string;
  email: string;
  created_at: DateTimeAsString;
  teams: TeamDTO[];
}

export type DateTimeAsString = string;

export interface CustomHttpError<T> extends HttpErrorResponse {
  error: T;
}

export interface ErrorResponse {
  error: {
    code: number;
    message: string;
  };
}

export interface CiselnikDTO {
  id: number;
  label: string;
  group: string;
  type: CiselnikTypeEnum;
}

// TODO - 13/05/2021 - tu treba doplnat vsetky existujuce typy
export enum CiselnikTypeEnum {
  EVENT_TYPE = 'EVENT_TYPE',
}
