import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { FormBuilder, FormGroup, Validators } from '../../../node_modules/@angular/forms';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddHolidayPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-add-holiday',
  templateUrl: 'add-holiday.html',
})
export class AddHolidayPage {
  public holidayForm: FormGroup;
  public loading;
  public url;
  public token;
  public resp;
  public date;
  public title;
  public nav;
  public currentTime;
  public year;
  public month;
  public keywords: any = {};
  public day;
  primaryColor: any;

  constructor(public navCtrl: NavController, public navParams: NavParams, public fb: FormBuilder, public referenceService: ReferenceService, public apiService: ApiService, private http: HTTP) {
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate() + 1;
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;

    this.holidayForm = fb.group({
      'title': [null, Validators.compose([Validators.required])],
      'description': [null, Validators.compose([Validators.required])],
      'holiday_date': [null, Validators.compose([Validators.required])],
    });
    this.nav = this.navParams.get('holiday');
    if (this.navParams.get('holiday')) {
      this.title = this.nav.title;
      this.date = this.nav.holiday_date;
    }
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));

  }

  ionViewWillEnter() {
   //console.log('ionViewWillEnter AddHolidayPage');
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  addHoliday() {
    var data = this.holidayForm.value;
   //console.log(data)
    this.url = this.apiService.addHoliday();
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
          this.referenceService.basicAlert(resp.message, 'Holiday added successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Holiday already added');
        }
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
}


