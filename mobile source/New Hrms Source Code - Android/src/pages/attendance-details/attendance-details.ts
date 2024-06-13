import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController } from 'ionic-angular';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AttendanceDetailsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-attendance-details',
  templateUrl: 'attendance-details.html',
})
export class AttendanceDetailsPage {

  public user;
  public loading;
  public url;
  public token;
  public resp;
  public attendanceList :any ={};
  isData = false;
  public data;
  public primaryColor: any;
  public keywords:any = {};
  constructor(public navCtrl: NavController, public modalCtrl: ModalController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    if (this.navParams.get('user')) {
      this.user = this.navParams.get('user');
      var month = new Date().getMonth() + 1;
      var year = new Date().getFullYear();
      this.data = {
        user_id: this.user.user_id,
        attendance_month: month,
        attendance_year: year
      }
    }
    else {
      var month = new Date().getMonth() + 1;
      var year = new Date().getFullYear();
      this.data = {
        attendance_month: month,
        attendance_year: year
      }
    }
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.attendanceDetails();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
   //console.log(this.data)
    this.http.post(this.url, this.data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "No result were found") {
          this.referenceService.basicAlert("New HRMS", 'No result found');
          this.loading.dismiss();
        }
        if (this.resp.message == "Success") {
          this.attendanceList = this.resp.data;
          this.isData = true;
         //console.log(this.resp)
         //console.log(this.attendanceList)
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

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  openModal() {
    let modal = this.modalCtrl.create(AttendanceDetailsFilterPage);
    modal.onDidDismiss((data) => {
      if(data.month){
        this.data.attendance_month =  data.month;
        this.data.attendance_year= data.year
        this.ionViewDidLoad();
      }
    });
    modal.present();
   //console.log("modal");
  };

}


@Component({
  selector: 'page-attendance-details',
  templateUrl: 'attendance-filtermodal.html',
})
export class AttendanceDetailsFilterPage {

  public user;
  public loading;
  public url;
  public token;
  public resp;
  public attendanceList;
  public data;
  public primaryColor: any;
  public filterData: any = {};
  constructor(public viewCtrl: ViewController, public navCtrl: NavController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
  }

  dismiss() {
    var date = this.data.split("-");
    this.filterData.month = date[1];
    this.filterData.year = date[0];
    this.viewCtrl.dismiss(this.filterData);
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
}
