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
  selector: 'page-createproject',
  templateUrl: 'createproject.html',
})
export class CreateProjectPage {

  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public projectDetails:any = {};
  public designation;
  public designate = false;
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public phone1 = false;
  projectForm: FormGroup;
  start = false;
  public primaryColor: any;
  proDateFrom:any;
  currentTime:any;
  year:any;
  day:any;
  month:any;
  public keywords:any={};
  public fixed = false;
  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.projectForm = fb.group({
      'project_code': ['', [Validators.required]],
      'project_title': ['', [Validators.required]],
      'client': ['', [Validators.required]],
      'assign_lead': ['', Validators.compose([Validators.required])],
      'assign_to': [, Validators.compose([Validators.required])],
      'fixed_rate': ['', [Validators.required]],
      'start_date': ['', [Validators.required]],
      'due_date': ['', [Validators.required]],
      'hourly_rate': ['', [Validators.required]],
      'fixed_price': ['', [Validators.required]],
      'estimate_hours': ['', [Validators.required]],
      'description': ['', [Validators.required]],
    });
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter(){
   //console.log("bcksak");
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
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
    return { 'background': this.primaryColor }
  };

  selectFrom() {
    this.proDateFrom = this.projectForm.value.start_date;
    this.start = true;
   //console.log(this.proDateFrom);
  };

  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  addEmployee() {
   console.log(this.projectForm.value)
    console.log(this.projectForm.get('assign_to').valid)

    
    if (this.projectForm.get('project_code').valid && this.projectForm.get('project_title').valid && this.projectForm.get('client').valid && this.projectForm.get('assign_lead').valid && this.projectForm.get('assign_to').valid && this.projectForm.get('start_date').valid) {
    //  //console.log(this.phone1, this.email1, this.password1, this.username1)
    //   if (this.projectForm.value.department_id == -1) {
    //     this.projectForm.value.department_id == '';
    //   }
      var array = this.projectForm.get('assign_to').value;
      if (array instanceof Array) {
      this.projectForm.controls.assign_to.setValue(array.join())
      }
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.createProject();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = this.projectForm.value;
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
            this.referenceService.basicAlert(resp.message, 'Project created successfully');
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
    else {
      if (this.projectForm.get('project_code').valid && this.projectForm.get('project_title').valid && this.projectForm.get('client').valid && this.projectForm.get('assign_lead').valid && this.projectForm.get('assign_to').valid && this.projectForm.get('start_date').valid) {
        this.referenceService.basicAlert("New HRMS", "Please enter the details in the input fields");
      }
      else if (!this.projectForm.get('project_title').valid) {
        if (this.projectForm.get('project_title').value == '') {
          this.referenceService.basicAlert("New HRMS", "Project title must be filled");
        }
      }
      else if (!this.projectForm.get('client').valid) {
        if (this.projectForm.get('client').value == '') {
          this.referenceService.basicAlert("New HRMS", "client must be selected");
        }
      }
      else if (!this.projectForm.get('assign_lead').valid) {
        if (this.projectForm.get('assign_lead').value == '') {
          this.referenceService.basicAlert("New HRMS", "Assign lead must be selected.");
        }
      }
      else if (!this.projectForm.get('assign_lead').valid) {
        if (this.projectForm.get('assign_lead').value == '') {
          this.referenceService.basicAlert("New HRMS", "Assign to must be selected.");
        }
      }
      else if (!this.projectForm.get('start_date').valid) {
        if (this.projectForm.get('start_date').value == '') {
          this.referenceService.basicAlert("New HRMS", "Start date  must be filled.");
        }
      }
    }
  };

}

