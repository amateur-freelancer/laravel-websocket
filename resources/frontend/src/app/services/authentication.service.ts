import { Injectable } from '@angular/core';
import { Http, Headers } from '@angular/http';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map'
import { environment } from 'environments/environment';

@Injectable()
export class AuthenticationService {
    private baseURI: string = environment.apiUrl;
    private headerOptions = {
        headers: new Headers({
          'Content-Type': 'application/json',
        })
    };
    constructor(private http: HttpClient) { }

    login(email: string, password: string) {
        return this.http.post(`${this.baseURI}/login/`, { email: email, password: password })
            .map(data => {
                // login successful if there's a jwt token in the response
                if (data['status'] == "success") {
                    // store user details and jwt token in local storage to keep user logged in between page refreshes
                    localStorage.setItem('useremail', data['data']['email']);
                    localStorage.setItem('userrole', data['data']['role']);
                }
                return data;
            });
    }

    logout() {
        // remove user from local storage to log user out
        localStorage.removeItem('useremail');
        localStorage.removeItem('userrole');
    }
}