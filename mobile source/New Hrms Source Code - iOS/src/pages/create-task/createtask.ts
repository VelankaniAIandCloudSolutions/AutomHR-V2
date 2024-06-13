import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { Validators, FormGroup, FormBuilder, FormControl } from '@angular/forms';
import { LoginPage } from '../login/login';
import { Type } from '@angular/compiler/src/output/output_ast';

/**
 * Generated class for the AddemployeePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-createtask',
  templateUrl: 'createtask.html',
})
export class CreateTaskPage {

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
  public primaryColor: any;
  public fixed = false;
  proDateFrom:any;
  currentTime:any;
  year:any;
  day:any;
  start= false;
  month:any;
  project:any;
  type:any;
  task_name:any;
  due_date:any;
  assigned_to:any;
  task:any;
  public keywords :any={};
  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.project = this.navParams.get('project')
    this.type = this.navParams.get('type')
    this.task = this.navParams.get('task')
   //console.log(this.type)
    this.primaryColor = localStorage.getItem('primary_color');
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter(){
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
  selectFrom() {
    // this.proDateFrom = this.projectForm.value.start_date;
    // this.start = true;
    ////console.log(this.proDateFrom);
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

 
  moveFocus(nextElement) {
    nextElement.setFocus();
  };
  createtask() {
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.createTask();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = {
        project:this.project,
        task_name: this.task_name
      };
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
            this.referenceService.basicAlert(resp.message, 'Task created successfully');
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
  };


  assignTo() {
      this.loading = this.referenceService.loading();
      this.loading.present();
    this.url = this.apiService.assignUser();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = {
        project: this.project,
        task: this.task,
        type:'Assign',
        assigned_to:this.assigned_to
      };
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
            this.referenceService.basicAlert(resp.message, 'Task created successfully');
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
    
  };
  addDue() {
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.assignUser();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = {
        project: this.project,
        task: this.task,
        type: 'Due',
        due_date:this.due_date
      };
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
            this.referenceService.basicAlert(resp.message, 'Task created successfully');
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
  };
}

