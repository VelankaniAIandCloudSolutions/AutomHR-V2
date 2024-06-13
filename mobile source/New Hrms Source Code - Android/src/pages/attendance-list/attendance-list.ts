import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, Content, ModalController } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { AttendanceDetailsPage, AttendanceDetailsFilterPage } from '../attendance-details/attendance-details';
import { LoginPage } from '../login/login';
import { TimeSheetListPage } from '../timesheets/timesheetslist';
import { AttendanceInfoPage } from '../attendance_info/attendance_info';

/**
 * Generated class for the AttendanceListPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-attendance-list',
  templateUrl: 'attendance-list.html',
})
export class AttendanceListPage {

  @ViewChild(Content) content: Content;
  public contacts;
  public groupedContacts = [];
  public list;
  public loading;
  public url;
  public token;
  public resp;
  public page;
  currentTime:any;
  year:any;
  month:any;
  public noData;
  public primaryColor: any;
  type: any;
  data:any = {};
  page1: any;
  days:any=[];  
  public keywords:any={};
  constructor(public navCtrl: NavController, public modalCtrl: ModalController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.type = this.navParams.get('timesheet')
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
   //console.log(this.resp)
    var month = this.month-1
    var date = new Date(this.year, month);
    this.days=[];
    while (date.getMonth() === month) {
      this.days.push(new Date(date).getDate());
      date.setDate(date.getDate() + 1);
    }
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.attendanceList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    this.data = { attendance_month: this.month,attendance_year:this.year }
   //console.log(this.data)
    this.http.post(this.url, this.data, token)
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
         //console.log(this.resp);
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
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
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  openInfo(emp,i){
   //console.log(emp,i)
    this.navCtrl.push(AttendanceInfoPage,{
      user_id: emp.user_id,
      day:i+1,
      month:this.month,
      year:this.year,
      emp:emp
    });
  };

  openModal() {
    let modal = this.modalCtrl.create(AttendanceDetailsFilterPage);
    modal.onDidDismiss((data) => {
      if (data.month) {
        this.month = data.month;
        this.year = data.year
       //console.log(this.month,this.year);
        this.ionViewWillEnter();
      }
    });
    modal.present();
   //console.log("modal");
  };


  openDetails(emp){
    this.navCtrl.push(AttendanceDetailsPage, {
      user: emp
    })
  }
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  
  // doInfinite(infiniteScroll) {
  //   setTimeout(() => {
  //     if (this.resp.data.next_page != -1) {
  //      //console.log('Begin async operation');
  //       this.url = this.apiService.getEmployeeList();
  //       this.token = localStorage.getItem('token')
  //      //console.log(this.token);
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
  //          //console.log(this.resp);
  //           if (this.resp.data.next_page == -1) {
  //             this.page = true;
  //           }
  //           this.loading.dismiss();
  //         })
  //         .catch(error => {
  //           this.loading.dismiss();
  //          //console.log("error=" + error.status);
  //          //console.log("error=" + error.error); // error message as string
  //          //console.log("error=" + error.headers);
  //         });
  //     }
  //     else {
  //      //console.log('Async operation has ended');
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
   //console.log(contacts)
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
   //console.log(this.groupedContacts)
  };
}
