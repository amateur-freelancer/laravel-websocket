import { Component, OnInit, NgZone } from '@angular/core';
import { UsersService } from 'app/services/users.service';

declare var jQuery: any;

@Component({
  moduleId: module.id,
  templateUrl: 'admin.component.html'
})
export class AdminComponent implements OnInit { 
	users = [];
	user = {};
	modalTitle = "";
	userAction = "";
	error = '';

	constructor(
		private zone: NgZone,
		private usersService: UsersService
	) {}

	ngOnInit(): void {
		this.listUsers();
	}

	public listUsers(): any {
		this.usersService.list().subscribe( 
			data => {
				this.users = data.data.users;
		    }
		);
	}

	public addUser(user) {
		this.userAction = "add";
		this.modalTitle = "Create User";
	}

	public createUser(): any {
		this.usersService.create(this.user).subscribe( 
			data => {
				this.user = {};
				let user = data.data.user;
				this.error = '';
				jQuery('#user').modal('hide');
				this.listUsers();
		    },
            error => {
            	this.error = error.error.message;
                if(error.error.message['role']){
                    this.error = error.error.message['role'];
                }
                if(error.error.message['password']) {
                	this.error = error.error.message['password'];
                }
                if(error.error.message['email']){
                    this.error = error.error.message['email'];
                }
                if(error.error.message['name']){
                    this.error = error.error.message['name'];
                }
            }
		);
	}

	public editUser(user) {
		this.userAction = "edit";
		this.modalTitle = "Update User"
		this.user = user;
		jQuery('#user').modal('show');
	}

	public updateUser(): any {
		this.usersService.update(this.user).subscribe( 
			data => {
				this.user = {};
				let user = data.data.user;
				this.error = '';
				jQuery('#user').modal('hide');
				this.listUsers();
		    },
		    error => {
		    	this.error = error.error.message;
                if(error.error.message['role']){
                    this.error = error.error.message['role'];
                }
                if(error.error.message['password']) {
                	this.error = error.error.message['password'];
                }
                if(error.error.message['email']){
                    this.error = error.error.message['email'];
                }
                if(error.error.message['name']){
                    this.error = error.error.message['name'];
                }
            }
		);
	}

	public cancel(user) {
		this.user = {};
	}

	public deleteUser(user): any {
		this.usersService.delete(user).subscribe( 
			data => {
				let user = data.data.user;
				this.listUsers();
		    }
		);
	}
}
