import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { LogoutComponent } from './components/logout/logout.component';
import { LoginComponent } from './components/login/login.component';

@NgModule({
  declarations: [LogoutComponent, LoginComponent],
  imports: [CommonModule, FormsModule, HttpClientModule],
})
export class SharedModule {}
