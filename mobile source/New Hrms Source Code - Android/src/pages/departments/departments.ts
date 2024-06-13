import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  PopoverController
} from "ionic-angular";
import { HTTP } from "../../../node_modules/@ionic-native/http";
import { ApiService } from "../../providers/apiServices";
import { ReferenceService } from "../../providers/referenceService";
import { PopoverPage } from "../employeelist/employeelist";
import { AddDepartmentPage } from "../add-department/add-department";
import { LoginPage } from "../login/login";

/**
 * Generated class for the DepartmentsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-departments",
  templateUrl: "departments.html"
})
export class DepartmentsPage {
  url: any;
  token: any;
  resp: any;
  departments: any;
  loading: any;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public keywords:any={};
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private http: HTTP,
    private popoverCtrl: PopoverController,
    public apiServices: ApiService,
    public refService: ReferenceService
  ) {
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id");
    this.primaryColor = localStorage.getItem("primary_color");
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.refService.loading();
    this.loading.present();
    this.url = this.apiServices.getDepartments();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    this.http
      .get(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.refService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.departments = this.resp.data;
         //console.log(this.departments);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.refService.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
   //console.log("ionViewWillEnter DepartmentsPage");
  }
  doRefresh(refresher) {
    this.loading = this.refService.loading();
    this.loading.present();
    this.url = this.apiServices.getDepartments();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    this.http
      .get(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.refService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.departments = this.resp.data;
         //console.log(this.departments);
        }
        refresher.complete();
      })
      .catch(error => {
        refresher.complete();
        this.refService.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }

  presentPopover(myEvent) {
    let popover = this.popoverCtrl.create(PopoverPage);
    popover.present({
      ev: myEvent
    });
  }

  addDepartment() {
    this.navCtrl.push(AddDepartmentPage);
  }
}
