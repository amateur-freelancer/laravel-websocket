import { Injectable } from '@angular/core';
import { Http, Headers } from '@angular/http';
import 'rxjs/add/operator/map';

import { environment } from 'environments/environment';

@Injectable()
export class DashboardsService {
  private baseURI: string = environment.apiUrl;
  private headerOptions = {
    headers: new Headers({
      'Content-Type': 'application/json',
    })
  };

  constructor(private http: Http) { }

  public list(profileKey): any {
    let listUrl = `${this.baseURI}/dashboards`;
    if (profileKey) {
      listUrl += `?profile_key=${profileKey}`;
    }

    return this.http.get(listUrl, this.headerOptions)
      .map(res => {
        return res.json();
      });
  }

  public detail(id): any {
    return this.http.get(`${this.baseURI}/dashboards/${id}`, this.headerOptions)
      .map(res => {
        return res.json();
      });
  }

  public update(dashboard): any {
    return this.http.put(`${this.baseURI}/dashboards/${dashboard.id}`, dashboard, this.headerOptions)
      .map(res => {
        return res.json();
      });
  }

}
