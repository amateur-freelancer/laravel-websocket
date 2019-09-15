import { Component } from '@angular/core';
import Echo from 'laravel-echo/dist/echo';
import * as io from 'socket.io-client';
import { environment } from 'environments/environment';

declare global {
  interface Window { io: any; }
  interface Window { Echo: any; }
}

window.io = io;
window.Echo = new Echo({
  broadcaster: 'socket.io',
  host: environment.host + ':6001'
});

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
}
