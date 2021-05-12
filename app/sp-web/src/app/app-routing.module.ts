import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LogoutComponent } from './shared/shared/components/logout/logout.component';
import { AuthGuard } from './shared/shared/services/auth.guard';
import { LoginComponent } from './shared/shared/components/login/login.component';

const routes: Routes = [
  {
    path: 'dashboard',
    loadChildren: () => import('./routes/dashboard/dashboard.module').then((m) => m.DashboardModule),
  },
  {
    path: 'event',
    loadChildren: () => import('./routes/event/event.module').then((m) => m.EventModule),
  },
  {
    path: 'team',
    loadChildren: () => import('./routes/team/team.module').then((m) => m.TeamModule),
  },
  {
    path: 'logout.html',
    canActivate: [AuthGuard],
    component: LogoutComponent,
  },
  {
    path: 'login',
    component: LoginComponent,
  },
  {
    path: '**',
    redirectTo: 'event',
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
