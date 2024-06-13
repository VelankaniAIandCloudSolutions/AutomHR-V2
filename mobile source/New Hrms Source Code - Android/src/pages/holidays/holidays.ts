import { Component } from "@angular/core";
import { IonicPage, NavController, NavParams } from "ionic-angular";
import { AddHolidayPage } from "../add-holiday/add-holiday";
import { ReferenceService } from "../../providers/referenceService";
import { ApiService } from "../../providers/apiServices";
import { HTTP } from "../../../node_modules/@ionic-native/http";
import {
  FormGroup,
  FormBuilder,
  Validators
} from "../../../node_modules/@angular/forms";
import { LoginPage } from "../login/login";

/**
 * Generated class for the HolidaysPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-holidays",
  templateUrl: "holidays.html"
})
export class HolidaysPage {
  public loading;
  public url;
  public token;
  public resp;
  public holidayList: any;
  public role: any;
  public roleId: any;
  public noData: any;
  public primaryColor: any;
  public keywords :any={};
  currentTime: any;
  year: any;
  month: any;
  day: any;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public http: HTTP,
    private referenceservice: ReferenceService,
    private apiService: ApiService
  ) {
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id");
    this.primaryColor = localStorage.getItem("primary_color");
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate() + 1;
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getHolidays();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var currentTime = new Date();
    var year = currentTime.getFullYear();
    var token = { token: this.token };
    var data = { hyear: year };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.holidayList = this.resp.data;
          this.holidayList.sort(
            (a, b) =>
              new Date(a.holiday_date).getTime() -
              new Date(b.holiday_date).getTime()
          );
          this.holidayList.forEach(element => {
            
            if (this.currentTime <= element.holiday_date) {
             //console.log(element);
            }
          });

          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData);
          }
         //console.log(this.holidayList);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error.status);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  }
  doRefresh(refresher) {
    this.url = this.apiService.getHolidays();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var currentTime = new Date();
    var year = currentTime.getFullYear();
    var token = { token: this.token };
    var data = { hyear: year };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.holidayList = this.resp.data;
          this.holidayList.sort(
            (a, b) =>
              new Date(a.holiday_date).getTime() -
              new Date(b.holiday_date).getTime()
          );
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData);
          }
         //console.log(this.holidayList);
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error.status);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  }
  addHoliday() {
    this.navCtrl.push(AddHolidayPage);
  }

  edit(day) {
    this.navCtrl.push(EditHolidayPage, {
      holiday: day
    });
  }

  remove(day) {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.removeHoliday();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var data = { id: day.id };
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
        if (resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (resp.message == "Success") {
          if (resp.status_code == 1) {
            this.loading.dismiss();
            this.ionViewWillEnter();
            this.referenceservice.basicAlert(resp.message, "Holiday removed");
          } else if (resp.status_code == 0) {
            this.loading.dismiss();
            this.referenceservice.basicAlert("Not allowed", resp.message);
          }
        }
      })
      .catch(error => {
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
        this.loading.dismiss();
      });
  }
  getHeaderStyle() {
    return { background: this.primaryColor };
  }
}

// ********************************************EDIT HOLIDAY PAGE******************************

@Component({
  selector: "page-holidays",
  templateUrl: "edit-holiday.html"
})
export class EditHolidayPage {
  public holidayForm: FormGroup;
  public loading;
  public url;
  public token;
  public resp;
  public date;
  public desc;
  public title;
  public navData;
  public primaryColor: any;
  currentTime: any;
  year: any;
  month: any;
  day: any;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public fb: FormBuilder,
    public referenceService: ReferenceService,
    public apiService: ApiService,
    private http: HTTP
  ) {
    this.holidayForm = fb.group({
      id: [null, Validators.compose([Validators.required])],
      title: [null, Validators.compose([Validators.required])],
      holiday_date: [null, Validators.compose([Validators.required])],
      description: [null, Validators.compose([Validators.required])]
    });
    this.navData = this.navParams.get("holiday");
    this.title = this.navData.title;
    this.date = this.navData.holiday_date;
    this.desc = this.navData.description;
    this.primaryColor = localStorage.getItem("primary_color");
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    ////console.log(this.year)
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate() + 1;
    if (this.month < 10) {
      this.month = "0" + this.month;
    }
    if (this.day < 10) {
      this.day = "0" + this.day;
    }
    this.currentTime = this.year + "-" + this.month + "-" + this.day;
  }

  ionViewWillEnter() {
   //console.log("ionViewWillEnter AddHolidayPage");
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }

  editHoliday() {
    this.holidayForm.value.id = this.navData.id;
    var data = this.holidayForm.value;
    this.url = this.apiService.editHoliday();
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
       //console.log(resp);
        if (resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (resp.message == "Success") {
          if (resp.status_code == 1) {
            this.loading.dismiss();
            this.referenceService.basicAlert(
              resp.message,
              "Holiday Edited successfully"
            );
            this.navCtrl.pop();
          }
        }
        if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert(
            "New HRMS",
            "Holiday Edited successfully"
          );
          this.navCtrl.pop();
        }
      })
      .catch(error => {
        this.referenceService.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
        this.loading.dismiss();
      });
  }
}
