export interface AccountModel extends UserDTO {
  id: number;
  updated_at: DateTimeAsString; // yyyy-MM-ddTHH:mm:ss
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
  max_team_member: number;
  created_at: DateTimeAsString;
  updated_at: DateTimeAsString;
  registration_start: DateTimeAsString;
  registration_end: DateTimeAsString;
  event_start: DateTimeAsString;
  event_end: DateTimeAsString;
  owner: UserDTO;
}

export interface UserDTO {
  firstname: string;
  surname: string;
  email: string;
  created_at: DateTimeAsString;
}

export type DateTimeAsString = string;
