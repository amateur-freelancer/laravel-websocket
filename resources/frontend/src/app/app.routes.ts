import { Routes } from '@angular/router';

import { DashboardListComponent } from './views/dashboards/list/list.component';
import { LoginComponent } from './views/login/login.component';
import { AdminComponent } from './views/admin/admin.component';
import { PasswordComponent } from './views/password/password.component';

import { BlankLayoutComponent } from './components/common/layouts/blank.component';
import { AdminLayoutComponent } from './components/common/layouts/admin.component';

import { AuthGuard } from './_guards/auth.guard';
import { AdminGuard } from './_guards/admin.guard';

export const ROUTES: Routes = [
  // Main redirect
  { path: '', redirectTo: 'dashboards', pathMatch: 'full' },
  
  // App views
  {
    path: '', component: BlankLayoutComponent,
    children: [
      { path: 'login', component: LoginComponent },
      { path: 'dashboards', component: DashboardListComponent, canActivate: [AuthGuard] },
      { path: 'admin', component: AdminComponent, canActivate: [AdminGuard] },
      { path: 'password', component: PasswordComponent, canActivate: [AuthGuard] },
    ]
  },

  // Handle all other routes
  { path: '**', redirectTo: 'dashboards' }
];
