import { HttpErrorResponse } from '@angular/common/http';

// TODO - 12/05/2021 - permissions
export interface AccountModel extends UserDTO {
  permissions: AccountPermissionEnum[];
}

// TODO - 08/03/2021 - zatial nemame
export enum AccountPermissionEnum {
  APP_EDIT,
  TEAM_MANAGER,
  PARTICIPANT,
}

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
}

export interface TeamDTO {
  id: string;
  team_name: string;
  created_at: DateTimeAsString;
  updated_at: DateTimeAsString;
  registration_start: DateTimeAsString;
  owner: UserDTO;
  users: UserDTO[];
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
