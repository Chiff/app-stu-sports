import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MockRoutingModule } from './mock-routing.module';
import { PricingComponent } from './pricing/pricing.component';
import { CheckoutComponent } from './checkout/checkout.component';
import { SharedModule } from '../../shared/shared/shared.module';

@NgModule({
  declarations: [PricingComponent, CheckoutComponent],
  imports: [CommonModule, SharedModule, MockRoutingModule],
})
export class MockModule {}
