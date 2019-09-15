import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { RouterModule } from '@angular/router';

import { BsDropdownModule } from 'ngx-bootstrap';

import { AdminLayoutComponent } from './admin.component';
import { BlankLayoutComponent } from './blank.component';

import { NavigationComponent } from './../navigation/navigation.component';
import { FooterComponent } from './../footer/footer.component';
import { TopNavbarComponent } from './../topnavbar/topnavbar.component';


@NgModule({
  declarations: [
    FooterComponent,
    AdminLayoutComponent,
    BlankLayoutComponent,
    NavigationComponent,
    TopNavbarComponent
  ],
  imports: [
    BrowserModule,
    RouterModule,
    BsDropdownModule.forRoot()
  ],
  exports: [
    FooterComponent,
    AdminLayoutComponent,
    BlankLayoutComponent,
    NavigationComponent,
    TopNavbarComponent
  ],
})

export class LayoutsModule { }
