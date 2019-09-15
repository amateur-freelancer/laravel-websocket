import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';

@Injectable()
export class AdminGuard implements CanActivate {

    constructor(private router: Router) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (localStorage.getItem('useremail') && localStorage.getItem('userrole') == "admin") {
            // logged in so return true
            return true;
        }

        // not logged in so redirect to login page
        this.router.navigate(['/dashboards'], { queryParams: { returnUrl: state.url }});
        return false;
    }
}