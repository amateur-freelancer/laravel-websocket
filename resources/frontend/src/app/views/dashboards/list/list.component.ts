import { Component, OnInit, NgZone } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { DashboardsService } from 'app/services/dashboards.service';
import * as _ from 'lodash';

declare var jQuery: any;

@Component({
  selector: 'dashboard-list',
  templateUrl: 'list.template.html'
})

export class DashboardListComponent implements OnInit {
  dashboards = [];
  groupedDashboards = {};
  groupedDashboardsKeys = [];
  selectedBox = {};

  constructor(
    private zone: NgZone,
    private dashboardsService: DashboardsService,
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    const profilekey = this.route.snapshot.queryParams['profile_key'];

    this.dashboardsService.list(profilekey)
      .subscribe(
        data => {
          this.dashboards = data;
          this.groupedDashboards = _.groupBy(this.dashboards, 'sort_order');
          this.groupedDashboardsKeys = Object.keys(this.groupedDashboards);
        }
      );

    const channelName = profilekey ? `dashboard-${profilekey}` : 'dashboard-all';

    window.Echo.channel(channelName)
      .listen('DashboardUpdated', (data) => {
        this.zone.run(() => {
          console.log('Received dashboard updated event', data);
          this.dashboards = data.dashboards;
          this.groupedDashboards = _.groupBy(this.dashboards, 'sort_order');
          this.groupedDashboardsKeys = Object.keys(this.groupedDashboards);
        });
      });
  }

  refreshBox(box) {
    this.dashboardsService.detail(box.id)
      .subscribe(
        data => {
          const index = this.dashboards.indexOf(box);
          if (index !== -1) {
            this.dashboards[index] = data;
            this.groupedDashboards = _.groupBy(this.dashboards, 'sort_order');
            this.groupedDashboardsKeys = Object.keys(this.groupedDashboards);
          }
        }
      );
  }

  editBox(box) {
    this.selectedBox = box;
    jQuery('#box-settings').modal('show');
  }

  updateBox(box) {
    this.dashboardsService.update(box)
      .subscribe(
        data => {
          const index = this.dashboards.indexOf(box);
          if (index !== -1) {
            this.dashboards[index] = data;
            this.groupedDashboards = _.groupBy(this.dashboards, 'sort_order');
            this.groupedDashboardsKeys = Object.keys(this.groupedDashboards);
          }
          jQuery('#box-settings').modal('hide');
        }
      );
  }
}
