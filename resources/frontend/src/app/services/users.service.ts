import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import 'rxjs/add/operator/map';

import { environment } from 'environments/environment';

@Injectable()
export class UsersService {
  private baseURI: string = environment.apiUrl;

  constructor(private http: HttpClient) { }

  public list(): any {
    return this.http.get(`${this.baseURI}/users`)
      .map(res => {
        return res;
      });
  }

  public create(user): any {
    return this.http.post(`${this.baseURI}/users`, user)
      .map(res => {
        return res;
      });
  }

  public update(user): any {
    return this.http.put(`${this.baseURI}/users/${user.id}`, user)
      .map(res => {
        return res;
      });
  }

  public delete(user): any {
    return this.http.delete(`${this.baseURI}/users/${user.id}`)
      .map(res => {
        return res;
      });
  }
  
}
