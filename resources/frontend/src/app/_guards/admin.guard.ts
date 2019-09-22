import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { AuthenticationService } from '../services/authentication.service';

@Injectable()
export class AdminGuard implements CanActivate {

    constructor(private router: Router, private auth: AuthenticationService) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (localStorage.getItem('token') && this.auth.parseJwt(localStorage.getItem('token')).role == "admin") {
            // logged in so return true
            return true;
        }

        // not logged in so redirect to login page
        this.router.navigate(['/dashboards'], { queryParams: { returnUrl: state.url }});
        return false;
    }
}