import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, Content } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { AttendanceDetailsPage } from '../attendance-details/attendance-details';
import { LoginPage } from '../login/login';
import { TimeSheetListPage } from '../timesheets/timesheetslist';

/**
 * Generated class for the AttendanceListPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-timesheet-list',
  templateUrl: 'timesheet-list.html',
})
export class TimesheetContactPage {

  @ViewChild(Content) content: Content;
  public contacts;
  public groupedContacts = [];
  public list;
  public loading;
  public url;
  public token;
  public resp;
  public page;
  public pageNumber;
  public noData;
  public primaryColor: any;
  public keywords:any={};
  type: any;
  page1: any;

  constructor(public navCtrl: NavController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.type = this.navParams.get('timesheet');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getUserList();
    this.token = localStorage.getItem('token')
    console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.list = this.resp.data;
          this.list = this.sortByKey(this.list, 'fullname');
          this.groupContacts(this.list);
          console.log(this.resp);
          if (this.resp.status_code == 0) {
            this.noData = true;
            console.log(this.noData)
          }
          if (this.resp.data.length == 0) {
            this.noData = true;
          }
          // if (this.resp.data.next_page == -1) {
          //   this.page = true;
          // }
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
        console.log("error=" + error);
        console.log("error=" + error.error);
        console.log("error=" + error.headers);
      });
  };

  doRefresh(refresher) {
    this.page = false;
    this.url = this.apiService.getUserList();
    this.token = localStorage.getItem('token')
    console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.list = this.resp.data;
          this.list = this.sortByKey(this.list, 'fullname');
          this.groupContacts(this.list);
          console.log(this.resp);
          if (this.resp.status_code == 0) {
            this.noData = true;
            console.log(this.noData)
          }
          if (this.resp.data.length == 0) {
            this.noData = true;
          }
          // if (this.resp.data.next_page == -1) {
          //   this.page = true;
          // }
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
        console.log("error=" + error);
        console.log("error=" + error.error);
        console.log("error=" + error.headers);
      });
  }
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  openDetails(contact) {
    if (this.type) {
      this.navCtrl.push(TimeSheetListPage, {
        user: contact
      })
    }
    else {
      this.navCtrl.push(AttendanceDetailsPage, {
        user: contact
      })
    }
  };

  // doInfinite(infiniteScroll) {
  //   setTimeout(() => {
  //     if (this.resp.data.next_page != -1) {
  //       console.log('Begin async operation');
  //       this.url = this.apiService.getEmployeeList();
  //       this.token = localStorage.getItem('token')
  //       console.log(this.token);
  //       var token = { 'token': this.token };
  //       var data = { page: this.resp.data.next_page }
  //       this.http.post(this.url, data, token)
  //         .then(data => {
  //           this.resp = JSON.parse(data.data);
  //           infiniteScroll.complete();
  //           this.content.scrollToTop();
  //           for (var i = 0; i < this.resp.data.list.length; i++) {
  //             this.list.push(this.resp.data.list[i]);
  //           }
  //           this.list = this.sortByKey(this.list, 'fullname');
  //           this.groupedContacts = [];
  //           this.groupContacts(this.list);
  //           console.log(this.resp);
  //           if (this.resp.data.next_page == -1) {
  //             this.page = true;
  //           }
  //           this.loading.dismiss();
  //         })
  //         .catch(error => {
  //           this.loading.dismiss();
  //           console.log("error=" + error.status);
  //           console.log("error=" + error.error); // error message as string
  //           console.log("error=" + error.headers);
  //         });
  //     }
  //     else {
  //       console.log('Async operation has ended');
  //       infiniteScroll.complete();
  //     }
  //   }, 1000);
  // };

  sortByKey(array, key) {
    return array.sort(function (a, b) {
      var x = a[key]; var y = b[key];
      return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
  };

  groupContacts(contacts) {
    console.log(contacts)
    let sortedContacts = contacts;
    let currentLetter = false;
    let currentContacts = [];
    sortedContacts.forEach((value, index) => {
      if (value.fullname.charAt(0) != currentLetter) {
        currentLetter = value.fullname.charAt(0);
        let newGroup = {
          letter: currentLetter,
          list: []
        };
        currentContacts = newGroup.list;
        this.groupedContacts.push(newGroup);
      }
      currentContacts.push(value);
    });
    console.log(this.groupedContacts)
  };
}
