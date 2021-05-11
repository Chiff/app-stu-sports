import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EventRoutingModule } from './event-routing.module';
import { SharedModule } from '../../shared/shared/shared.module';
import { EventListComponent } from './event-list/event-list.component';
import { EventDetailComponent } from './event-detail/event-detail.component';
import { EventNewComponent } from './event-new/event-new.component';

@NgModule({
  declarations: [EventListComponent, EventDetailComponent, EventNewComponent],
  imports: [CommonModule, SharedModule, EventRoutingModule],
})
export class EventModule {}
