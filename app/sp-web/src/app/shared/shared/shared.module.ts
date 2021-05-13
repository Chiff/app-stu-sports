import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgDatepickerModule } from '@annotation/ng-datepicker';
import { NgSelectModule } from '@ng-select/ng-select';
import { LogoutComponent } from './components/logout/logout.component';
import { LoginComponent } from './components/login/login.component';

@NgModule({
  declarations: [LogoutComponent, LoginComponent],
  imports: [CommonModule, FormsModule, HttpClientModule, NgbModule, NgSelectModule, NgDatepickerModule.forRoot({})],
  exports: [FormsModule, HttpClientModule, NgbModule, NgDatepickerModule, NgSelectModule],
})
export class SharedModule {}
