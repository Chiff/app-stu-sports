import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TeamRoutingModule } from './team-routing.module';
import { TeamDetailComponent } from './team-detail/team-detail.component';
import { TeamNewComponent } from './team-new/team-new.component';
import { SharedModule } from '../../shared/shared/shared.module';

@NgModule({
  declarations: [TeamDetailComponent, TeamNewComponent],
  imports: [CommonModule, TeamRoutingModule, SharedModule],
})
export class TeamModule {}
