import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { UserDTO } from '../../../models/sp-api';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private userDto: UserDTO = null;
  private userPromise: Promise<UserDTO> = null;
  private inprogress: boolean = false;

  constructor(private http: HttpClient, private router: Router) {}

  public get userSnapshot(): UserDTO {
    return this.userDto;
  }

  public user(force: boolean = false): Promise<UserDTO> {
    if (this.inprogress) return this.userPromise;
    if (this.userPromise == null || force) {
      this.inprogress = true;
      this.userPromise = this.http.get<UserDTO>('api/user/detail').toPromise();
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

  public onLogin(user: UserDTO): void {
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
