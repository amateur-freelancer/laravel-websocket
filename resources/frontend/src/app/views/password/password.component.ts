import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '../../services/authentication.service';

@Component({
  selector: 'app-password',
  templateUrl: './password.component.html',
  styleUrls: ['./password.component.css']
})
export class PasswordComponent implements OnInit {
	model: any = {};
	loading = false;
	error = '';

	constructor(private router: Router, private authenticationService: AuthenticationService) { }

	ngOnInit() {
	}

	submit() {
		this.loading = true;
		this.authenticationService.change_password(this.model).subscribe( 
			data => {
				if(data['status'] == "success"){
					this.router.navigate(['/dashboards']);
				}
		    },
            error => {
                if(error.error.message['new_password']) {
                    this.error = error.error.message['new_password'];
                }
                else if(error.error.message['confirm_password']) {
                	this.error = error.error.message['confirm_password'];
                }
                else{
                    this.error = error.error.message;
                }
                this.loading = false;
            }
		);
	}
}
