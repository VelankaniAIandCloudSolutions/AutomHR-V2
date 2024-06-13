import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Geolocation } from '@ionic-native/geolocation';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AttendancePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-attendance',
  templateUrl: 'attendance.html',
})

export class AttendancePage {

  public date;
  public localdate;
  public localtime;
  public day;
  public token;
  public url;
  public loading;
  public resp;
  public lat;
  public lng;
  public punchin = false;
  public punchout = false;
  public punchinData: any;
  public punchOutData: any;
  public primaryColor: any;
  public list;
  public page;
  currentTime: any;
  year: any;
  month: any;
  public noData;
  type: any;
  page1: any;
  user_id: any;
  user: any;
  isData = false;
  public keywords :any={};
  constructor(public navCtrl: NavController, public navParams: NavParams, public http: HTTP, private geolocation: Geolocation, public referenceService: ReferenceService, public apiService: ApiService) {
    this.date = new Date();
    var month = new Date().getMonth() + 1
    this.localdate = new Date().getDate() + '/' + month + '/' + new Date().getFullYear();
    this.localtime = new Date().toLocaleTimeString();
    this.geolocation.getCurrentPosition().then((resp) => {
      this.lat = resp.coords.latitude;
      this.lng = resp.coords.longitude;
    }).catch((error) => {
     //console.log('Error getting location', error);
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.date = this.year + '-' + this.month + '-' + this.day;
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.createAttendance();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { }
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

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  // punchIn() {
  //   // this.loading = this.referenceService.loading();
  //   // this.loading.present();
    
  //   this.geolocation.getCurrentPosition().then((resp) => {
  //     this.lat = resp.coords.latitude;
  //     this.lng = resp.coords.longitude;
  //     // this.loading.dismiss();
  //     this.setpunch();
  //   }).catch((error) => {
  //     // this.loading.dismiss();
  //     this.referenceService.basicAlert("New HRMS", 'Error getting location Please turn on your location and try again');
  //    //console.log('Error getting location', error);
  //   });

  // };

  punchIn() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.punchIn();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = {
      latitude: this.lat,
      longitude: this.lng
    }
   //console.log(data)
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Required inputs missing") {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Please turn on your location and try again');
      }
      if (this.resp.message == "SUCCESS") {
        if (this.resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(this.resp.message, "Punched in Successfully")
          this.ionViewWillEnter();
        }
      }
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  // punchOut() {
  //   // this.loading = this.referenceService.loading();
  //   // this.loading.present();
  //   this.geolocation.getCurrentPosition().then((resp) => {
  //     this.lat = resp.coords.latitude;
  //     this.lng = resp.coords.longitude;
  //     // this.loading.dismiss();
  //     this.Outpunch();
  //   }).catch((error) => {
  //     // this.loading.dismiss();
  //     this.referenceService.basicAlert("New HRMS", 'Error getting location Please turn on your location and try again');
  //    //console.log('Error getting location', error);
  //   });

  // };

  punchOut() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.localtime = new Date().toLocaleTimeString();
    this.url = this.apiService.punchOut();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = {
      latitude: this.lat,
      longitude: this.lng
    }
   //console.log(data)
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp)
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Required input missing") {
        this.referenceService.basicAlert("New HRMS", 'Please turn on your location and try again');
        this.loading.dismiss();
      }
      if (this.resp.message == "SUCCESS") {
        if (this.resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(this.resp.message, "Punched out Successfully")
          this.ionViewWillEnter();
        }
      }
      this.loading.dismiss();
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
