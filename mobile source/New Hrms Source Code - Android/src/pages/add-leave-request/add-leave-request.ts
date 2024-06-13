import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, Thumbnail } from 'ionic-angular';
import { Validators, FormGroup, FormBuilder } from '../../../node_modules/@angular/forms';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { daysInMonth } from '../../../node_modules/ionic-angular/umd/util/datetime-util';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddLeaveRequestPage page.
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-add-leave-request',
  templateUrl: 'add-leave-request.html',
})
export class AddLeaveRequestPage {

  public leaveRequestForm: FormGroup;
  public loading;
  public url;
  public token;
  public resp;
  public date;
  public keywords: any = {};
  isData=false;
  public leave_types;
  public selectedLeave;
  public year: any;
  public month: any;
  public day: any;
  public leave_to;
  currentTime: any;
  leaves:any;
  public leave = true;
  public primaryColor: any;

  constructor(public navCtrl: NavController, public navParams: NavParams, public fb: FormBuilder, public referenceService: ReferenceService, public apiService: ApiService, private http: HTTP) {
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
   //console.log(this.currentTime)
    this.leaveRequestForm = fb.group({
      'leave_from': [null, Validators.compose([Validators.required])],
      'leave_to': [null, Validators.compose([Validators.required])],
      'leave_type': [null, Validators.compose([Validators.required])],
      'leave_reason': [null, Validators.compose([Validators.required])],
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getLeaveTypes();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.leaves = this.resp.data;
        this.isData =true;
        this.leave_types = this.leaves.leave_type;
        // this.selectedLeave = this.leave_types[0].id;
       //console.log(this.selectedLeave);
       //console.log(this.leave_types);
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
   //console.log('ionViewWillEnter AddLeaveRequestPage');
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  selectFrom() {
    this.leave_to = this.leaveRequestForm.value.leave_from;
    this.leave = false;
   //console.log(this.leave_to);
  };

  addLeaverequest() {
    var data = this.leaveRequestForm.value;
   //console.log(data)
    this.url = this.apiService.addLeaverequest();
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
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Leave requested successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };
}
