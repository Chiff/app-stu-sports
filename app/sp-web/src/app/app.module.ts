import { APP_INITIALIZER, NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { HttpClientModule } from '@angular/common/http';
import { Router } from '@angular/router';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AuthService } from './shared/shared/services/auth.service';
import { AccountModel } from './models/sp-api';
import { SharedModule } from './shared/shared/shared.module';

const beforeAppInit = (authService: AuthService, router: Router) => {
  const appInitPromise = new Promise<AccountModel>((resolve) => {
    authService.user(true).then(
      (user) => {
        resolve(user);
        router.navigate(['/dashboard']);
      },
      () => {
        resolve(null);
      }
    );
  });

  return () => appInitPromise;
};

@NgModule({
  declarations: [AppComponent],
  imports: [BrowserModule, HttpClientModule, AppRoutingModule, SharedModule],
  providers: [{ provide: APP_INITIALIZER, useFactory: beforeAppInit, deps: [AuthService, Router], multi: true }],
  bootstrap: [AppComponent],
})
export class AppModule {}
