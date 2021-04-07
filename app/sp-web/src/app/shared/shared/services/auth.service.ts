import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { AccountModel, AccountPermissionEnum } from '../../../models/sp-api';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private userDto: AccountModel = null;
  private userPromise: Promise<AccountModel> = null;
  private inprogress: boolean = false;

  constructor(private http: HttpClient, private router: Router) {}

  public get userSnapshot(): AccountModel {
    return this.userDto;
  }

  public user(force: boolean = false): Promise<AccountModel> {
    if (this.inprogress) return this.userPromise;
    if (this.userPromise == null || force) {
      this.inprogress = true;
      this.userPromise = this.http.get<AccountModel>('api/user/detail').toPromise();
      this.userPromise.then(
        (user) => {
          this.inprogress = false;
          this.userDto = user;
        },
        () => {
          this.inprogress = false;
          this.userDto = null;
        }
      );
    }

    return this.userPromise;
  }

  public isLogged(): boolean {
    return !!this.userDto?.id;
  }

  public hasPermission(permissions: AccountPermissionEnum[] | AccountPermissionEnum): boolean {
    if (!this.userDto?.permissions) return false;

    if (!Array.isArray(permissions)) {
      permissions = [permissions];
    }

    if (!permissions) {
      return false;
    }

    return !!permissions.find((r) => {
      return this.userDto.permissions.includes(r);
    });
  }

  public onLogin(user: AccountModel): void {
    this.userDto = user;
    this.inprogress = false;
    this.userPromise = null;
  }

  public logout(): void {
    this.userDto = null;
    this.router.navigate(['/logout.html']);
    this.http.get('api/user/logout').subscribe();
  }
}
