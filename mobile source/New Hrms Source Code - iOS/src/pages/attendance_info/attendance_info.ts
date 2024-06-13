import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, Content } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AttendanceListPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-attendance-info',
  templateUrl: 'attendance_info.html',
})
export class AttendanceInfoPage {

  @ViewChild(Content) content: Content;
  public contacts;
  public groupedContacts = [];
  public list;
  public loading;
  public url;
  public token;
  public resp;
  public page;
  currentTime: any;
  year: any;
  month: any;
  public noData;
  public primaryColor: any;
  type: any;
  page1: any;
  date: any;
  user_id: any;
  day: any;
  user:any;
  isData = false;
  public keywords : any ={};
  constructor(public navCtrl: NavController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.user_id = this.navParams.get('user_id');
    this.year = this.navParams.get('year');
    this.month = this.navParams.get('month');
    this.day = this.navParams.get('day');
    this.user = this.navParams.get('emp');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.date = this.year+'-'+this.month+'-'+this.day; 
   //console.log(this.resp)
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.attendanceInfo();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = {
      user_id:this.user_id,
      day:this.day,
      month:this.month,
      year:this.year,
    }
   //console.log(data)
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.list = this.resp.data;
          this.isData = true;
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
        
        }
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  openInfo() {

  }
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

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
