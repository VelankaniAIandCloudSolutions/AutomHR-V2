import { Component } from "@angular/core";
import { IonicPage, NavController, NavParams, Thumbnail } from "ionic-angular";
import { Validators, FormGroup, FormBuilder } from "@angular/forms";
import { ReferenceService } from "../../providers/referenceService";
import { ApiService } from "../../providers/apiServices";
import { HTTP } from "@ionic-native/http";
import { daysInMonth } from "ionic-angular/umd/util/datetime-util";
import { LoginPage } from "../login/login";

/**
 * Generated class for the AddLeaveRequestPage page.
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-add-timesheets",
  templateUrl: "add-timesheet.html"
})
export class AddTimesheetsPage {
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
  time = true;
  total: any;
  public keywords:any={};
  public timeInminutes;
  public primaryColor: any;
  projectDetails: any = {};
  today: any;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public fb: FormBuilder,
    public referenceService: ReferenceService,
    public apiService: ApiService,
    private http: HTTP
  ) {
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = new Date().toISOString();
    this.today =
      this.currentTime.getFullYear() +
      "-" +
      (this.currentTime.getMonth() + 1) +
      "-" +
      this.currentTime.getDate();
    this.timeSheetForm = fb.group({
      project_id: [null, Validators.compose([Validators.required])],
      timeline_date: [null, Validators.compose([Validators.required])],
      hours: [null, Validators.compose([Validators.required])],
      timeline_desc: [null, Validators.compose([Validators.required])]
    });
    this.primaryColor = localStorage.getItem("primary_color");
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
   //console.log("bcksak");
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.projectDetails = this.resp.data;
       //console.log(this.projectDetails);

        this.loading.dismiss();
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
   //console.log('ionViewWillEnter DepartmentsPage');
   //console.log('ionViewWillEnter AddemployeePage');
  };

  getHeaderStyle() {
    return { background: this.primaryColor };
  }

  addTimeSheet() {
    if (
      this.timeSheetForm.get("hours").valid &&
      this.timeSheetForm.get("project_id").valid &&
      this.timeSheetForm.get("timeline_desc").valid &&
      this.timeSheetForm.get("timeline_date").valid
    ) {
      if (this.time) {
        this.timeSheetForm.controls["timeline_date"].setValue(this.today);
        var data1 = this.timeSheetForm.value;
       //console.log(data1);
        this.url = this.apiService.addTimeSheet();
        this.loading = this.referenceService.loading();
        this.loading.present();
        this.token = localStorage.getItem("token");
        var token = { token: this.token };
       //console.log(data1);
        this.http
          .post(this.url, data1, token)
          .then(data => {
            var resp = JSON.parse(data.data);
            if (resp.message == "Invalid token or Token missing") {
              this.referenceService.basicAlert(
                "Session Expired",
                "Oops!! your session is expired please login and try again"
              );
              this.loading.dismiss();
              localStorage.clear();
              this.navCtrl.setRoot(LoginPage);
            }
            if (resp.message == "Success") {
              // if (resp.status_code == 1) {
              this.loading.dismiss();
              this.referenceService.basicAlert(
                resp.message,
                "TimeSheet Edited successfully"
              );
             //console.log(resp);
             //console.log(this.navCtrl.getPrevious());
              this.navCtrl.getPrevious().data.timesheets =
                resp.data.last_timesheet;
              this.navCtrl.getPrevious().data.timesheet_type = "add";
              var time = this.timeInminutes + parseInt(this.total);
              localStorage.setItem("timesheetHours", time);
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
            this.referenceService.basicAlert(
              "New HRMS",
              "Unable to reach server at the moment"
            );
           //console.log("error=" + error);
           //console.log("error=" + error.error); // error message as string
           //console.log("error=" + error.headers);
          });
      } else {
        this.referenceService.basicAlert(
          "New HRMS",
          "Time is invalid, cannot add more than 8 hours per day"
        );
      }
    } else {
      this.referenceService.basicAlert("New HRMS", "Please fill all fields");
    }
  }

  sum() {
    this.navCtrl.getPrevious().data.newGame = "manojj";
    this.navCtrl.pop();
  }
  change() {
    var time = this.timeSheetForm.get("hours").value;
    var minutes = time.split(":");
    this.timeInminutes = parseInt(minutes[0]) * 60 + parseInt(minutes[1]);
    this.total = localStorage.getItem("timesheetHours");
    var remaining = 480 - parseInt(this.total);
    if (this.timeInminutes > remaining) {
      this.referenceService.basicAlert(
        "New HRMS",
        "Time is invalid, cannot choose more than 8 hours per day"
      );
     //console.log(this.timeSheetForm.get("hours").value);
      this.time = false;
    } else {
      this.time = true;
    }
  }
}
