import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from '../../shared/shared/services/auth.guard';
import { TeamDetailComponent } from './team-detail/team-detail.component';
import { TeamNewComponent } from './team-new/team-new.component';

const routes: Routes = [
  // {
  //   path: 'list',
  //   component: EventListComponent,
  // },
  {
    path: 'detail/:id',
    component: TeamDetailComponent,
    canActivate: [AuthGuard],
  },
  {
    path: 'new',
    component: TeamNewComponent,
    canActivate: [AuthGuard],
  },
  {
    path: '**',
    redirectTo: 'list',
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TeamRoutingModule {}
