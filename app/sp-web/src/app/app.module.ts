import { APP_INITIALIZER, LOCALE_ID, NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { Router } from '@angular/router';
import { registerLocaleData } from '@angular/common';
import localeSk from '@angular/common/locales/sk';
import localeSkExtra from '@angular/common/locales/extra/sk';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AuthService } from './shared/shared/services/auth.service';
import { SharedModule } from './shared/shared/shared.module';
import { UserDTO } from './models/sp-api';

registerLocaleData(
  ((locale) => {
    locale[10][0] = 'dd.MM.yyyy';

    return locale;
  })(localeSk),
  localeSkExtra
);

const beforeAppInit = (authService: AuthService, router: Router) => {
  const appInitPromise = new Promise<UserDTO>((resolve) => {
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
  imports: [BrowserModule, AppRoutingModule, SharedModule],
  providers: [
    { provide: APP_INITIALIZER, useFactory: beforeAppInit, deps: [AuthService, Router], multi: true },
    { provide: LOCALE_ID, useValue: 'sk' },
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
