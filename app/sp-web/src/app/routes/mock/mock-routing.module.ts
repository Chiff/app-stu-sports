import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CheckoutComponent } from './checkout/checkout.component';
import { PricingComponent } from './pricing/pricing.component';

const routes: Routes = [
  {
    path: 'pricing',
    component: PricingComponent,
  },
  {
    path: 'checkout',
    component: CheckoutComponent,
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class MockRoutingModule {}
