import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';

import { DashboardsService } from 'app/services/dashboards.service';

import { DashboardListComponent } from './list/list.component';

@NgModule({
  declarations: [DashboardListComponent],
  imports: [
    BrowserModule,
    FormsModule
  ],
  exports: [DashboardListComponent],
  providers: [DashboardsService]
})
export class DashboardsModule { }
