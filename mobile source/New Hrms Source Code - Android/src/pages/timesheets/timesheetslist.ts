import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, Content, ViewController } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { AddTimesheetsPage } from '../add-timesheet/add-timesheet';
import { LoginPage } from '../login/login';

/**
 * Generated class for the EmployeesalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-timesheetslist',
  templateUrl: 'timesheetslist.html',
})
export class TimeSheetListPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public estimatesList: any;
  public loading: any;
  public url: any;
  public token: any;
  public keywords:any={};
  public resp: any;
  pageNumber = 1;
  page = false;
  noData = false;
  newGame: any;
  timesheets: any;
  day: any;
  currentTime: any;
  today: any;
  user: any;
  constructor(public navCtrl: NavController, public referenceservice: ReferenceService, public modalCtrl: ModalController, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.currentTime = new Date();
    this.today = this.currentTime.getFullYear() + '-' + (this.currentTime.getMonth() + 1) + '-' + this.currentTime.getDate();
    this.user = this.navParams.get('user');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.newGame = this.navParams.get('timesheets') || null;
    if (this.navParams.get('timesheet_type') == "edit") {
      if (this.navParams.get('timesheets')) {
        var data = this.navParams.get('timesheets');
        var id = data.time_id;
        var index = this.timesheets.map(x => {
          return x.time_id;
        }).indexOf(id);
       //console.log(this.timesheets[index])
        this.timesheets[index] = data;
        if (this.timesheets[index].timeline_date == this.today) {
          this.timesheets[index].edit = true;
        }
       //console.log(this.timesheets[index])
      }
    }
    else if (this.navParams.get('timesheet_type') == "add") {
      if (this.navParams.get('timesheets')) {
        var data = this.navParams.get('timesheets');
        data.edit = true;
        this.timesheets.splice(0, 0, data)
       //console.log(this.timesheets);
      }
    }
  };

  doRefresh(refresher) {
    this.page = false;
    this.url = this.apiService.getTimeSheetList();
    this.token = localStorage.getItem('token');
    var data;
    if (this.user) {
      data = {
        user_id: this.user.user_id,
        page: this.pageNumber
      };
    }
    else {
      data = {
        user_id: localStorage.getItem('user_id'),
        page: this.pageNumber
      }
    }
   //console.log(this.token);
    var token = { 'token': this.token };
    // var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        // this.projectList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.timesheets = this.resp.data.list;
          var hours = this.resp.data.overall_hours;
          refresher.complete();
          this.timesheets.forEach(element => {
            if (element.timeline_date == this.today) {
              element.edit = true;
            }
            else {
              element.edit = false;
            }
          });
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
          hours.forEach(element => {
            if (element.date == this.today) {
              localStorage.setItem('timesheetHours', element.minutes);
            }
          });
          this.timesheets
         //console.log(this.resp.data)

          if (this.timesheets.length == 0) {
            this.noData = true;
          }
          // if (this.resp.data.next_page == -1) {
          //   this.page = true;
          // }
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  ionViewDidLoad() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getTimeSheetList();
    this.token = localStorage.getItem('token');
    var data;
    if (this.user) {
      data = {
        user_id: this.user.user_id,
        page: this.pageNumber
      };
    }
    else {
      data = {
        user_id: localStorage.getItem('user_id'),
        page: this.pageNumber
      }
    }
   //console.log(this.token);
    var token = { 'token': this.token };
    // var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        // this.projectList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.timesheets = this.resp.data.list;
          var hours = this.resp.data.overall_hours;
          this.timesheets.forEach(element => {
            if (element.timeline_date == this.today) {
              element.edit = true;
            }
            else {
              element.edit = false;
            }
          });
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
          hours.forEach(element => {
            if (element.date == this.today) {
              localStorage.setItem('timesheetHours', element.minutes);
            }
          });
          this.timesheets
         //console.log(this.resp.data)

          if (this.timesheets.length == 0) {
            this.noData = true;
          }
          else{
            this.noData=false;
          }
          // if (this.resp.data.next_page == -1) {
          //   this.page = true;
          // }
        }
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  addTimesheet() {
    this.navCtrl.push(AddTimesheetsPage);
  }

  editTimeSheet(timesheet) {
    this.navCtrl.push(TimeSheetEditPage, { timesheet: timesheet })
  };
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  openModal(timesheet) {
    this.navCtrl.push(TimeSheetModalPage, { timesheet: timesheet })
  };
  openFilter() {
    let modal = this.modalCtrl.create(TimeSheetFilterPage);
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.content.scrollToTop();
        }
        else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getTimeSheetList();
          this.token = localStorage.getItem('token')
         //console.log(this.token);
          var token = { 'token': this.token };
          data.page = 1;
          if (this.user) {
            data.user_id = this.user.user_id
          }
          else {
            data.user_id = localStorage.getItem('user_id');
          }
         //console.log(data)
          this.http.post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
              this.timesheets = this.resp.data.list;
              this.content.scrollToTop();
             //console.log(this.resp);
              if (this.resp.data.next_page == -1) {
                this.page = true;
              }
              if (this.resp.status_code == 0) {
                this.noData = true;
               //console.log(this.noData)
              }
              if (this.timesheets.length == 0) {
                this.noData = true;
              }
              this.loading.dismiss();
            })
            .catch(error => {
              this.content.scrollToTop();
              this.loading.dismiss();
             //console.log("error=" + error.status);
             //console.log("error=" + error.error); // error message as string
             //console.log("error=" + error.headers);
            });
        }
      }
    });
    modal.present();
   //console.log("modal")
  }

  delete(timesheet) {
    let alert = this.referenceservice.confirmAlert("Confirm Delete", "Do you want to continue to delete this timesheet");
    alert.present();
    alert.onDidDismiss((data1) => {
      if (data1) {
        this.loading = this.referenceservice.loading();
        this.loading.present();
        this.url = this.apiService.deleteTimeSheet();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = { time_id: timesheet.time_id }
        this.http.post(this.url, data, token)
          .then(data => {
            this.resp = JSON.parse(data.data);
           //console.log(this.resp)
            // this.projectList = this.resp.data.list;
            if (this.resp.message == "Invalid token or Token missing") {
              this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
              // this.navCtrl.popAll();
              localStorage.clear();
              this.navCtrl.setRoot(LoginPage);
            }
            if (this.resp.message == "Success") {
              var id = timesheet.time_id;
              var time1 = timesheet.hours;
              var minutes1 = time1.split(':');
              var totalminutes = (parseInt(minutes1[0]) * 60) + parseInt(minutes1[1]);
              var total = localStorage.getItem('timesheetHours');
              var t = parseInt(total) - totalminutes;
              localStorage.setItem('timesheetHours', t.toString());
              var index = this.timesheets.map(x => {
                return x.time_id;
              }).indexOf(id);
              this.timesheets.splice(index, 1);
              // this.timesheets = this.resp.data.all_timesheets;
             //console.log(this.timesheets)
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
           //console.log("error=" + error);
           //console.log("error=" + error.error);
           //console.log("error=" + error.headers);
          });
      }
    })
  };

  doInfinite(infiniteScroll) {
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.getTimeSheetList();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = { page: this.resp.data.next_page }
        this.http.post(this.url, data, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.timesheets.push(this.resp.data.list[i]);
            }
           //console.log(this.resp);
            if (this.resp.data.next_page == -1) {
              this.page = true;
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
           //console.log("error=" + error.status);
           //console.log("error=" + error.error); // error message as string
           //console.log("error=" + error.headers);
          });
      }
      else {
       //console.log('Async operation has ended');
        infiniteScroll.complete();
      }
    }, 1000);
  };
}



// **********************************TIMESHEETS DETAILS PAGE****************************************

@Component({
  selector: 'page-timesheetslist',
  templateUrl: 'timesheetsmodal.html',
})
export class TimeSheetModalPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public timesheet: any;
  public loading: any;
  public url: any;
  public token: any;
  public resp: any;
  pageNumber = 1;
  page = false;
  noData = false;
  constructor(public navCtrl: NavController, public referenceservice: ReferenceService, public modalCtrl: ModalController, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color')
    this.timesheet = this.navParams.get('timesheet');
  }

  ionViewWillEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
}





// **********************************TIMESHEETS Edit PAGE****************************************

@Component({
  selector: 'page-timesheetslist',
  templateUrl: 'timesheetslistEdit.html',
})
export class TimeSheetEditPage {
  @ViewChild(Content) content: Content;
  public timeSheetForm: FormGroup;
  public loading;
  public url;
  public token;
  public resp: any;
  public selectedProject;
  public year: any;
  public month: any;
  public day: any;
  public leave_to;
  currentTime: any;
  public leave = true;
  public primaryColor: any;
  timesheet: any;
  time = true;
  total: any;
  public timeInminutes;
  today: any;
  projects: any;
  timeInminutes1: any;
  timesheettime: any;
  changed = false;
  changed1 = false;
  constructor(public navCtrl: NavController, public navParams: NavParams, public fb: FormBuilder, public referenceService: ReferenceService, public apiService: ApiService, private http: HTTP) {
    this.currentTime = new Date();
    this.timesheet = this.navParams.get('timesheet');
    this.timesheettime = this.timesheet.hours;
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = new Date().toISOString();
    this.today = this.currentTime.getFullYear() + '-' + (this.currentTime.getMonth() + 1) + '-' + this.currentTime.getDate();
    // if (this.month < 10) { this.month = '0' + this.month; }
    // if (this.day < 10) { this.day = '0' + this.day; }
    // this.currentTime = this.year + '-' + this.month + '-' + this.day;
    ////console.log(this.currentTime)
    this.timeSheetForm = fb.group({
      'project_id': [null, Validators.compose([Validators.required])],
      'timeline_date': [null, Validators.compose([Validators.required])],
      'hours': [null, Validators.compose([Validators.required])],
      'timeline_desc': [null, Validators.compose([Validators.required])],
      'time_id': [null, Validators.compose([Validators.required])],
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.timeSheetForm.controls['time_id'].setValue(this.timesheet.time_id)
  }

  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectList();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.post(this.url, {}, token).then(data => {
     //console.log(data);
      this.resp = JSON.parse(data.data);
     //console.log(this.resp);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        // this.navCtrl.popAll();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.projects = this.resp.data;
       //console.log(this.resp);
        this.loading.dismiss();
      }
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + JSON.stringify(error));
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });

   //console.log('ionViewWillEnter AddLeaveRequestPage');
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  change() {
    if (!this.changed) {
      var time1 = this.timesheettime;
      var minutes1 = time1.split(':');
      this.timeInminutes1 = (parseInt(minutes1[0]) * 60) + parseInt(minutes1[1]);
      this.total = localStorage.getItem('timesheetHours');
      var t = this.total - this.timeInminutes1;
      localStorage.setItem('timesheetHours', t.toString());
      this.changed = true;
    }
    var time = this.timeSheetForm.get('hours').value;
    var minutes = time.split(':');
    this.timeInminutes = (parseInt(minutes[0]) * 60) + parseInt(minutes[1]);
    this.total = localStorage.getItem('timesheetHours');
    var remaining = 480 - parseInt(this.total);
    if (this.timeInminutes > remaining) {
      this.referenceService.basicAlert("New HRMS", 'Time is invalid, cannot choose more than 8 hours per day');
     //console.log(this.timeSheetForm.get('hours').value);
      this.time = false;
    }
    else {
      this.time = true;
    }
  };

  editTimeSheets() {
    if (this.time) {
      var data = this.timeSheetForm.value;
     //console.log(data)
      this.url = this.apiService.editTimeSheet();
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.token = localStorage.getItem('token');
      var token = { 'token': this.token };
     //console.log(data)
      this.http.post(this.url, data, token).then(data => {
        var resp = JSON.parse(data.data);
        if (resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (resp.message == "Success") {
          // if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'TimeSheet Edited successfully');
         //console.log(resp)
         //console.log(this.navCtrl.getPrevious())
          this.navCtrl.getPrevious().data.timesheets = resp.data.last_timesheet;
          this.navCtrl.getPrevious().data.timesheet_type = "edit";
          var time = this.timeInminutes + parseInt(this.total)
          localStorage.setItem('timesheetHours', time);
          this.changed1 = true;
          this.navCtrl.pop();
          // }

          // else if (resp.status_code == 0) {
          // this.loading.dismiss();
          // this.referenceService.basicAlert('Already requested', resp.message);
          // }
        }
      })
        .catch(error => {
          this.loading.dismiss();
          this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
         //console.log("error=" + error);
         //console.log("error=" + error.error); // error message as string
         //console.log("error=" + error.headers);
        });
    }
    else {
      this.referenceService.basicAlert("New HRMS", 'Time is invalid, cannot add more than 8 hours per day');
    }
  };

  ionViewWillLeave() {
    if (!this.changed1) {
      var time1 = this.timesheettime;
      var minutes1 = time1.split(':');
      this.timeInminutes1 = (parseInt(minutes1[0]) * 60) + parseInt(minutes1[1]);
      this.total = localStorage.getItem('timesheetHours');
      var t = parseInt(this.total) + this.timeInminutes1;
      localStorage.setItem('timesheetHours', t);
    }
  }

}







// **********************************TIMESHEETS Filter PAGE****************************************


@Component({
  selector: 'page-timesheetslist',
  templateUrl: 'timesheetslistfilter.html',
})
export class TimeSheetFilterPage {
  @ViewChild(Content) content: Content;
  public filterData: any = {};
  public resp: any;
  public year: any;
  public month: any;
  public day: any;
  public leave_to;
  currentTime: any;
  public leave = true;
  public primaryColor: any;
  timesheet: any;
  today: any;
  projects: any;
  min: any;
  constructor(public navCtrl: NavController, public viewCtrl: ViewController, public navParams: NavParams, public fb: FormBuilder, public referenceService: ReferenceService, public apiService: ApiService, private http: HTTP) {
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
    this.timesheet = this.navParams.get('timesheet');
    this.min = this.year - 5;
    // if (this.month < 10) { this.month = '0' + this.month; }
    // if (this.day < 10) { this.day = '0' + this.day; }
    // this.currentTime = this.year + '-' + this.month + '-' + this.day;
    ////console.log(this.currentTime)

    this.primaryColor = localStorage.getItem('primary_color');

  }

  ionViewWillEnter() {
    // this.year = this.currentTime.getFullYear();
    // this.min = this.year - 5;
  };

  selectFrom() {
    this.min = this.filterData.from_date;
   //console.log(this.min);
  };

  filter() {
    this.viewCtrl.dismiss(this.filterData);
   //console.log(this.filterData)
  }
  closeFilter() {
   //console.log(this.filterData)
    this.viewCtrl.dismiss("close");
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}



