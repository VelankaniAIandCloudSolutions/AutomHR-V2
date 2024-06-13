import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, AlertController } from 'ionic-angular';
import { FormGroup, Validators, FormBuilder } from '../../../node_modules/@angular/forms';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddDesignationsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-add-designations',
  templateUrl: 'add-designations.html',
})
export class AddDesignationsPage {

  public loading;
  public url;
  private designationForm: FormGroup;
  public token;
  public departments;
  public keywords: any = {};
  public resp;
  public selectedLeave;
  primaryColor: any;
  constructor(public navCtrl: NavController, public navParams: NavParams, private fb: FormBuilder, private alertCtrl: AlertController, private apiService: ApiService, private referenceService: ReferenceService, private http: HTTP) {
    this.designationForm = fb.group({
      'designation': [null, Validators.compose([Validators.required])],
      'department_id': [null, Validators.compose([Validators.required])],
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getDepartments();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.departments = this.resp.data;
        // this.departments.push({ 'deptid': -1, 'deptname': 'Select Department' });
        // this.selectedLeave = -1;  
       //console.log(this.selectedLeave);
       //console.log(this.departments);
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
   //console.log('ionViewWillEnter DepartmentsPage');
  }



  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  addDesignation() {
    var data = this.designationForm.value;
    this.url = this.apiService.addDesignation();
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.token = localStorage.getItem('token');
    var token = { 'token': this.token };
   //console.log(data)
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Success") {
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Designation added successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Designation already added');
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
  }
}






