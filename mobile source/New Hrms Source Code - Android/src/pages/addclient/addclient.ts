import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { Validators, FormGroup, FormBuilder, FormControl } from '../../../node_modules/@angular/forms';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddemployeePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-addclient',
  templateUrl: 'addclient.html',
})
export class AddclientPage {

  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public departments;
  public designation;
  public designate = false;
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public phone1 = false;
  public client: any = {};
  public keywords: any = {};
  employeeForm: FormGroup;
  public primaryColor: any;
  basicInformation = true;
  contactInformation = false;
  webInformation = false;
  bankInformation = false;
  hostInformation = false;

  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {

    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {

  };
  contact() {
    if (!this.client.company_name || !this.client.company_email) {
      if (!this.client.company_name) {
        this.referenceService.basicAlert("New HRMS", "Please enter the company name");
      }
      else if (!this.client.company_email) {
        this.referenceService.basicAlert("New HRMS", "Please enter the company email");
      }
      // else if(contactemail.errors && contactemail.errors.pattern){

      // }
    }
    else {
      this.basicInformation = false;
      this.contactInformation = true;
    }
  }
  basic() {
    this.basicInformation = true;
    this.contactInformation = false;
  }
  web() {
    this.webInformation = true;
    this.contactInformation = false;
  }
  contactback() {
    this.webInformation = false;
    this.contactInformation = true;
  }
  bank() {
    this.webInformation = false;
    this.bankInformation = true;
  }
  webback() {
    this.webInformation = true;
    this.bankInformation = false;
  }

  host() {
    this.hostInformation = true;
    this.bankInformation = false;
  }
  bankback() {
    this.hostInformation = false;
    this.bankInformation = true;
  }

  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
  submit() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.createClient();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.client;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Success") {
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Employee Added successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
      if (resp.status_code == -1) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Already requested', resp.message);
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  }
}

