import { Injectable, Input, EventEmitter, Output } from '@angular/core';
import { Http, Headers } from '@angular/http';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map'
import { environment } from 'environments/environment';

@Injectable()
export class AuthenticationService {

    @Output() getLoggedInName: EventEmitter<any> = new EventEmitter();

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
                    localStorage.setItem('token', JSON.stringify(data['data']['token']));
                    this.getLoggedInName.emit(this.parseJwt(data['data']['token']).name);
                }
                else {
                    this.getLoggedInName.emit('');
                }
                return data;
            });
    }

    logout() {
        // remove user from local storage to log user out
        localStorage.removeItem('token');
        this.getLoggedInName.emit('');
    }

    change_password(password) {
        return this.http.post(`${this.baseURI}/password`, password)
          .map(res => {
            return res;
          });
    }

    public getToken(): string {
        return localStorage.getItem('token');
    }

    public parseJwt(token): any {
        let base64Url = token.split('.')[1];
        let base64 = base64Url.replace('-', '+').replace('_', '/');
        let data = JSON.parse(atob(base64));
        return data;
    }

    public isAuthed(): any {
        let token = this.getToken();
        if(token) {
          let params = this.parseJwt(token);
          let isAuth = Math.round(new Date().getTime() / 1000) <= params.exp;
          if(!isAuth){
            return false;
          }
          return true;
        } else {
          return false;
        }
    }

    public checkRole(): any {
        let token = this.getToken();
        if(token) {
            let data = this.parseJwt(token);
            let role = data.role;
            return role;
        }
        else{
            return false;
        }
    }
}