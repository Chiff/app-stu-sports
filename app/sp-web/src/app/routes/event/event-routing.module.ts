import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { EventListComponent } from './event-list/event-list.component';
import { EventNewComponent } from './event-new/event-new.component';
import { EventDetailComponent } from './event-detail/event-detail.component';
import { AuthGuard } from '../../shared/shared/services/auth.guard';

const routes: Routes = [
  {
    path: 'list',
    component: EventListComponent,
  },
  {
    path: 'detail/:id',
    component: EventDetailComponent,
  },
  {
    path: 'new',
    component: EventNewComponent,
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
export class EventRoutingModule {}
