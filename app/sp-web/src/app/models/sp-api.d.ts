export interface AccountModel {
  id: number;
  email: string;
  firstname: string;
  surname: string;
  created_at: string; // yyyy-MM-ddTHH:mm:ss
  updated_at: string; // yyyy-MM-ddTHH:mm:ss
}

// TODO - 08/03/2021 - zatial nemame
export enum AccountPermissionEnum {
  APP_EDIT,
  TEAM_MANAGER,
  PARTICIPANT,
}
