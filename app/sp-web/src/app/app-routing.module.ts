import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LogoutComponent } from './shared/shared/components/logout/logout.component';
import { AuthGuard } from './shared/shared/services/auth.guard';
import { LoginComponent } from './shared/shared/components/login/login.component';

const routes: Routes = [
  {
    path: 'mock',
    loadChildren: () => import('./routes/mock/mock.module').then((m) => m.MockModule),
  },
  {
    path: 'dashboard',
    loadChildren: () => import('./routes/dashboard/dashboard.module').then((m) => m.DashboardModule),
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
    redirectTo: 'dashboard',
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
