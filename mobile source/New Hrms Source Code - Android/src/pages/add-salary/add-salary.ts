import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddSalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-add-salary',
  templateUrl: 'add-salary.html',
})
export class AddSalaryPage {

  public role: any;
  public roleId: any;
  public primaryColor: any;
  public salaryForm: FormGroup;
  public employeenames: any;
  public url: any;
  public loading: any;
  public resp: any;
  public token: any;
  public empList: any;
  public selected;
  public basicPay: any;
  public da: any;
  public hra: any;
  public dateyear: any;
  public keywords:any;
  constructor(public navCtrl: NavController, public fb: FormBuilder, public referenceservice: ReferenceService, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.salaryForm = fb.group({
      'user_id': ['', Validators.compose([Validators.required])],
      'year': ['', Validators.compose([Validators.required])],
      'month': ['', Validators.compose([Validators.required])],
      'net_salary': ['', Validators.compose([Validators.required])],
      'basic_pay': ['', Validators.compose([Validators.required])],
      'da': ['', Validators.compose([Validators.required])],
      'hra': ['', Validators.compose([Validators.required])],
      'conveyance': [0, Validators.compose([Validators.required])],
      'allowance': [0, Validators.compose([Validators.required])],
      'medical_allowance': [0, Validators.compose([Validators.required])],
      'earning_others': [0, Validators.compose([Validators.required])],
      'tds': [0, Validators.compose([Validators.required])],
      'esi': [0, Validators.compose([Validators.required])],
      'pf': [0, Validators.compose([Validators.required])],
      'leaves': [0, Validators.compose([Validators.required])],
      'prof_tax': [0, Validators.compose([Validators.required])],
      'labour_welfare': [0, Validators.compose([Validators.required])],
      'fund': [0, Validators.compose([Validators.required])],
      'ded_others': [0, Validators.compose([Validators.required])],
    });
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getUserList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var data = {
      page : "salary"
    }
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.empList = this.resp.data;
          // this.empList.push({ "user_id": -1, "fullname": "Select Employee" });
          // this.selected = -1;
         //console.log(this.resp);
        }
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  addSalary() {
   //console.log(this.salaryForm.value)
    if (this.salaryForm.get('user_id').valid && this.salaryForm.get('net_salary').valid && this.salaryForm.get('month').valid) {
      if (this.salaryForm.get('net_salary').value != 0) {
        var date = this.salaryForm.value.month.split("-");
        this.salaryForm.value.month = date[1];
        this.salaryForm.value.year = date[0];
        this.loading = this.referenceservice.loading();
        this.loading.present();
        this.url = this.apiService.addSalary();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = this.salaryForm.value;
       //console.log(data)
        this.http.post(this.url, data, token)
          .then(data => {
            this.resp = JSON.parse(data.data);
           //console.log(this.resp)
            if (this.resp.message == "Invalid token or Token missing") {
              this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
              // this.navCtrl.popAll();
              this.loading.dismiss();
              localStorage.clear();
              this.navCtrl.setRoot(LoginPage);
            }
            if (this.resp.message == "Success") {
             //console.log(this.resp);
              this.referenceservice.basicAlert("New HRMS", 'Salary has been added successfully');
              this.navCtrl.pop();
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
           //console.log("error=" + error);
           //console.log("error=" + error.error);
           //console.log("error=" + error.headers);
          });
      }
      else {
        this.referenceservice.basicAlert("New HRMS", "Net salary must be greater than 0");
      }
    }
    else {
      if (this.salaryForm.get('user_id').value == undefined && this.salaryForm.get('net_salary').value == '' && this.salaryForm.get('month').value == '') {
        this.referenceservice.basicAlert("New HRMS", "Please enter the details in the input fields");
      }
      else if (this.salaryForm.get('user_id').value == undefined) {
        this.referenceservice.basicAlert("New HRMS", "Please select the employee to continue");
      }
      else if (this.salaryForm.get('month').value == '') {
        this.referenceservice.basicAlert("New HRMS", "Please select the year and month to add salary");
      }
      else if (this.salaryForm.get('net_salary').value == '') {
        this.referenceservice.basicAlert("New HRMS", "Net Salary must be filled");
      }
    }
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  caculate() {
    this.basicPay = Math.ceil(this.salaryForm.value.net_salary * .50);
    this.da = Math.ceil(this.salaryForm.value.net_salary * .40);
    this.hra = Math.ceil(this.salaryForm.value.net_salary * .10);
  };

  moveFocus(nextElement) {
    nextElement.setFocus();
  };
}




