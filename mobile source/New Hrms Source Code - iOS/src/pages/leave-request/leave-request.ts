import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { AddLeaveRequestPage } from '../add-leave-request/add-leave-request';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the LeaveRequestPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-leave-request',
  templateUrl: 'leave-request.html',
})
export class LeaveRequestPage {
  public loading;
  public url;
  public token;
  public leaveList;
  public resp;
  pageNumber = 1;
  page = false;
  public role: any;
  public roleId: any;
  noData = false;
  time = 0;
  leave:any={}
  lead=false;
  leave_types = [
    'Personal','Team'
  ]
  selectedLeave = 'Personal';
  filterData: any = {};
  cssClass: string;
  public keywords:any={};
  public primaryColor: any;
  constructor(public navCtrl: NavController, public http: HTTP, public navParams: NavParams, private referenceservice: ReferenceService, private apiService: ApiService, public modalCtrl: ModalController) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
   //console.log(this.role + "=role");
   //console.log(this.roleId + "=roleid");
    this.primaryColor = localStorage.getItem('primary_color');
    this.filterData.leave_status = '';
    this.filterData.leave_type = '';
  }

  ionViewDidLoad() {

    this.applyClassBySelection('bounceInLeft');
  }

  doRefresh(refresher) {
    this.cssClass = '';
    this.url = this.apiService.getLeaves();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = {
      page: this.pageNumber,
      leave_type: this.filterData.leave_type,
      leave_status: this.filterData.leave_status
    };
   //console.log(data)
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          // this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();

          this.leaveList = this.resp.data.list;
          this.selectedLeave = 'Personal';
          this.leaveList.forEach(element => {
            this.time = this.time + 0.2;
            element.time = this.time;
          });
         //console.log(this.leaveList);
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          // this.loading.dismiss();
        }
      })
      .catch(error => {
        // this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  ionViewWillEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getLeaves();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = {
      page: this.pageNumber,
      leave_type: this.filterData.leave_type,
      leave_status: this.filterData.leave_status
    };
   //console.log(data)
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.leaveList = this.resp.data.list;
          this.leave = this.resp.data;
          this.selectedLeave = 'Personal';
          this.leaveList.forEach(element => {
            this.time = this.time + 0.2;
            element.time = this.time;
          });
         //console.log(this.leaveList);
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }
  openModal(leave) {
    this.cssClass = '';
    this.navCtrl.push(LeaveRequestMoadlPage, {
      leave: leave
    })
  };

  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  addLeave() {
    this.cssClass = '';
    this.navCtrl.push(AddLeaveRequestPage);
  };

  change(leave){
   //console.log(this.selectedLeave)
   //console.log(leave)
    if (this.selectedLeave == 'Personal'){
      this.leaveList = this.leave.list;
      this.lead =false;
    }
    if (this.selectedLeave == 'Team') {
      this.leaveList = this.leave.team_list;
      this.lead = true;
    }
  }

  openFilter() {
    this.cssClass = '';
    let modal = this.modalCtrl.create(LeaveFilterPage);
    modal.onDidDismiss((data) => {
      this.loading = this.referenceservice.loading();
      this.loading.present();
      this.url = this.apiService.getLeaves();
      this.token = localStorage.getItem('token')
     //console.log(this.token);
      var token = { 'token': this.token };
      data.page = 1;
      this.filterData.leave_type = data.leave_type;
      this.filterData.leave_status = data.leave_status;
      this.http.post(this.url, data, token)
        .then(data => {
          this.resp = JSON.parse(data.data);
          this.leaveList = this.resp.data.list;
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
         //console.log(this.resp);
          this.loading.dismiss();
        })
        .catch(error => {
          this.loading.dismiss();
          this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
         //console.log("error=" + error);
         //console.log("error=" + error.error);
         //console.log("error=" + error.headers);
        });
    });
    modal.present();
   //console.log("modal")
  };

  doInfinite(infiniteScroll) {
    ////console.log(this.resp);
    ////console.log(this.leaveList);
    this.cssClass = "";
    this.time = 0;
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.getLeaves();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = {
          page: this.resp.data.next_page,
          leave_type: this.filterData.leave_type,
          leave_status: this.filterData.leave_status
        }
       //console.log(data)
        this.http.post(this.url, data, token)
          .then(data => {
            this.resp = JSON.parse(data.data);
            infiniteScroll.complete();
           //console.log(this.resp);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.time = this.time + 0.2;
              this.resp.data.list[i].time = this.time;
              this.leaveList.push(this.resp.data.list[i]);
            }
           //console.log(this.resp);
            if (this.resp.data.next_page == -1) {
              this.page = true;
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.page = true;
            this.loading.dismiss();
          });
      }
      else {
       //console.log('Async operation has ended');
        infiniteScroll.complete();
      }
    }, 1000);
  };

  cancel(leave) {
    this.cssClass = '';
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.cancelLeaveRequest();
    this.token = localStorage.getItem('token')
    var token = { 'token': this.token };
    var data = {
      leave_id: leave.id,
      leave_status: 3
    };
   //console.log(data)
    this.http.post(this.url, data, token)
      .then(data => {
        let resp = JSON.parse(data.data);
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceservice.basicAlert(resp.message, 'Leave request Cancelledd');
          this.ionViewWillEnter();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceservice.basicAlert('Not allowed', resp.message);
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };

  acceptReject(leave, status) {
    this.cssClass = '';
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.acceptRejectLeave();
    this.token = localStorage.getItem('token')
    var token = { 'token': this.token };
    var data = {
      leave_id: leave.id,
      leave_status: status
    };
   //console.log(data)
    this.http.post(this.url, data, token)
      .then(data => {
       //console.log(data)
        let resp = JSON.parse(data.data);
        if (resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (resp.message == "Success") {
          if (resp.status_code == 1 && status == 1) {
            this.page = false;
            this.loading.dismiss();
            this.referenceservice.basicAlert(resp.message, 'Leave request accepted');
            this.ionViewWillEnter();
          }
          else if (resp.status_code == 1 && status == 2) {
            this.page = false;
            this.loading.dismiss();
            this.referenceservice.basicAlert(resp.message, 'Leave request rejected');
            this.ionViewWillEnter();
          }
        }
        if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceservice.basicAlert('Not allowed', resp.message);
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };

}


@Component({
  selector: 'page-leave-request',
  templateUrl: 'leave-request-modal.html',
})
export class LeaveRequestMoadlPage {
  public leave;
  public primaryColor: any;
  constructor(public navCtrl: NavController, public navParams: NavParams, public viewCtrl: ViewController) {
    this.leave = this.navParams.get('leave');
   //console.log(this.leave);
    this.primaryColor = localStorage.getItem('primary_color');
  }

  ionViewWillEnter() {

  };

  back() {
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}


@Component({
  selector: 'page-leave-request',
  templateUrl: 'leave-request-filter.html',
})
export class LeaveFilterPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public leave_types;
  public resp;
  public primaryColor: any;
  public leaveStatus = [
    { "id": 0, "status": "Pending" },
    { "id": 1, "status": "Accepted" },
    { "id": 2, "status": "Rejected" },
    { "id": 3, "status": "Cancelled" },
  ];

  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
  }
  ionViewDidEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getLeaveTypes();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
      this.leave_types = this.resp.data;
      // this.leave_types.push({ 'id': -1, 'leave_type': 'All' });
      // this.filterData.leave_type = -1;
      this.filterData.leave_status = -2;
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  }

  dismiss() {
    if (this.filterData.leave_type == -1) {
      this.filterData.leave_type = '';
     //console.log(this.filterData)
    }
    if (this.filterData.leave_status == -2) {
      this.filterData.leave_status = '';
     //console.log(this.filterData)
    }
    this.viewCtrl.dismiss(this.filterData);
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}



