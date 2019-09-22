import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from '../../services/authentication.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
	
	loggedIn = false;
  	navbarOpen = false;
  	username = "";
	
	constructor(private auth: AuthenticationService) { 
		auth.getLoggedInName.subscribe(name => this.changeName(name));
	}

	ngOnInit() {
		this.checkAuth();
	}

	toggleNavbar() {
		this.navbarOpen = !this.navbarOpen;
	}

	private changeName(name: string): void {
        this.checkAuth();
    }

	checkAuth() {
		if (localStorage.getItem('token')) {
			this.loggedIn = true;
			this.username = this.auth.parseJwt(localStorage.getItem('token')).name;
		}
		else {
			this.loggedIn = false;
			this.username = "";
		}
	}

	checkRole() {
		return this.auth.checkRole();
	}
}
