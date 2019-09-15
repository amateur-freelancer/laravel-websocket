import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';

import { RouterModule } from '@angular/router';
import { LocationStrategy, HashLocationStrategy } from '@angular/common';

import { ROUTES } from './app.routes';
import { AppComponent } from './app.component';

// App views
import { DashboardsModule } from './views/dashboards/dashboards.module';

import { AuthGuard } from './_guards/auth.guard';
import { AdminGuard } from './_guards/admin.guard';
import { JwtInterceptor } from './_helpers/jwt.interceptor';
import { AuthenticationService } from './services/authentication.service';
import { LoginComponent } from './views/login/login.component';
import { AdminComponent } from './views/admin/admin.component';

// App modules/components
import { LayoutsModule } from './components/common/layouts/layouts.module';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    AdminComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    DashboardsModule,
    LayoutsModule,
    HttpClientModule,
    RouterModule.forRoot(ROUTES)
  ],
  providers: [
                {   
                    provide: LocationStrategy, 
                    useClass: HashLocationStrategy 
                },
                AuthGuard,
                AdminGuard,
                AuthenticationService,
                {
                    provide: HTTP_INTERCEPTORS,
                    useClass: JwtInterceptor,
                    multi: true
                },
              ],
  bootstrap: [AppComponent]
})
export class AppModule { }
