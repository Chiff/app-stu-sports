import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { AuthService } from '../../services/auth.service';
import { AccountModel } from '../../../../models/sp-api';

@Component({
  selector: 'sp-login',
  templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit {
  user: { email: string; password: string } = {
    email: null,
    password: null,
  };

  bad: boolean = false;

  constructor(private auth: AuthService, private router: Router, private http: HttpClient) {}

  ngOnInit(): void {
    if (this.auth.isLogged()) {
      this.router.navigate(['/dashboard']);
    }
  }

  login(): void {
    this.bad = false;

    this.http.post('api/user/login', this.user).subscribe({
      next: (user: AccountModel) => {
        this.auth.onLogin(user);
        this.router.navigate(['/dashboard']);
      },
      error: () => {
        this.bad = true;
      },
    });
  }
}
