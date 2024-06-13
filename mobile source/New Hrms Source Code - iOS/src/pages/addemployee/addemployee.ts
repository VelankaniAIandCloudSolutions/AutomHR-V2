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
  selector: 'page-addemployee',
  templateUrl: 'addemployee.html',
})
export class AddemployeePage { 

  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public departments;
  public designation;
  public designate = false;
  public reporting = false;
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public reporting_officer:any;
  public phone1 = false;
  employeeForm: FormGroup;
  public primaryColor: any;
  public keywords :any ={};
  leads: any;
  public fixed = false;
  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.employeeForm = fb.group({
      'username': ['', [Validators.required, this.nameValidator.bind(this)]],
      'password': ['', [Validators.required, Validators.minLength(8), Validators.maxLength(15), this.passwordValidator.bind(this)]],
      'email': ['', [Validators.required, this.emailValidator.bind(this)]],
      'fullname': ['', Validators.compose([Validators.required])],
      'designation_id': ['', Validators.compose([Validators.required])],
      'department_id': ['', Validators.compose([Validators.required])],
      'emp_doj': ['', Validators.compose([Validators.required])],
      'phone': ['', [Validators.required, Validators.minLength(10), Validators.maxLength(15), this.phoneValidator.bind(this)]],
      'reporting_to': ['', Validators.compose([Validators.required])],
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
        // this.selected = -1;
       //console.log(this.selected);
       //console.log(this.departments);
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

  department() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getDesignation();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = { "dept_id": this.selected };
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.designation = this.resp.data.designations;
        if (this.designation.length != 0) {
          this.designate = true;
        }
        else {
          this.designate = false;
        }
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
  };

  reportingTo() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.reportingTo();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = { "dept_id": this.selected, des_id: this.selected1 };
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.reporting_officer = this.resp.data.ro;
        if (this.designation.length != 0) {
          this.reporting = true;
        }
        else {
          this.reporting = false;
        }
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
  };

  moveFocus(nextElement) {
    nextElement.setFocus();
  };
  team() {
   //console.log(this.employeeForm.value.teamlead_id)
  }
  addEmployee() {

    if (this.employeeForm.get('password').valid && this.employeeForm.get('username').valid && this.employeeForm.get('phone').valid && this.employeeForm.get('email').valid) {
     //console.log(this.phone1, this.email1, this.password1, this.username1)
      if (this.employeeForm.value.department_id == -1) {
        this.employeeForm.value.department_id == '';
      }
      if (this.employeeForm.value.is_teamlead == true) {
        this.employeeForm.controls.is_teamlead.setValue('yes');
        this.employeeForm.controls.teamlead_id.setValue(0);
      }
      else if (this.employeeForm.value.is_teamlead == false) {
        this.employeeForm.controls.is_teamlead.setValue('no');
       //console.log(this.employeeForm.value.teamlead_id)
        if (this.employeeForm.value.teamlead_id == '') {
         //console.log(this.employeeForm.value)
          this.referenceService.basicAlert("New HRMS", "Team lead must be filled");
        }
      }
     //console.log(this.employeeForm.value)
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.addEmployee();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = this.employeeForm.value;
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
    else {
      if (this.employeeForm.get('fullname').value == '' && this.employeeForm.get('username').value == '' && this.employeeForm.get('email').value == '' && this.employeeForm.get('phone').value == '' && this.employeeForm.get('password').value == '') {
        this.referenceService.basicAlert("New HRMS", "Please enter the details in the input fields");
      }
      else if (!this.employeeForm.get('fullname').valid) {
        if (this.employeeForm.get('fullname').value == '') {
          this.referenceService.basicAlert("New HRMS", "Full name must be filled");
        }
        else {
          this.referenceService.basicAlert("New HRMS", "User name should not contain any special characters and numbers.");
        }
      }
      else if (!this.employeeForm.get('username').valid) {
        if (this.employeeForm.get('username').value == '') {
          this.referenceService.basicAlert("New HRMS", "User name must be filled");
        }
        else {
          this.referenceService.basicAlert("New HRMS", "User name should not contain any special characters and numbers.");
        }
      }
      else if (!this.employeeForm.get('phone').valid) {
        if (this.employeeForm.get('phone').value == '') {
          this.referenceService.basicAlert("New HRMS", "Phone must be filled.");
        }
        else {
          this.referenceService.basicAlert("New HRMS", "Phone number must be in 10-15 characters long.");
        }
      }
      else if (!this.employeeForm.get('email').valid) {
        if (this.employeeForm.get('email').value == '') {
          this.referenceService.basicAlert("New HRMS", "Email must be filled.");
        }
        else {
          this.referenceService.basicAlert("New HRMS", "Email should be in email format.");
        }
      }
      else if (!this.employeeForm.get('password').valid) {
        if (this.employeeForm.get('password').value == '') {
          this.referenceService.basicAlert("New HRMS", "Password must be filled.");
        }
        else {
          this.referenceService.basicAlert("New HRMS", "Password must be atleast 8 characters long. To make it stronger,use upper and lower case letters,numbers and symbols");
        }
      }
    }
  };

  isValid(field: string) {
    let formField = this.employeeForm.get(field);
    return formField.valid || formField.pristine;
  };

  nameValidator(control: FormControl): { [s: string]: boolean } {
    if (!control.value.match("^[a-zA-Z ,.'-]+$")) {
      return { invalidName: true };
    }
    else {
      this.username1 = false;
    }
  };

  passwordValidator(control: FormControl): { [s: string]: boolean } {
    if (!control.value.match('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$')) {
     //console.log(control.value);
      return { invalidPassword: true };
    }
    else {
     //console.log(control.value);
      this.password1 = false;
    }
  };

  emailValidator(control: FormControl): { [s: string]: boolean } {
    if (!(control.value.toLowerCase().match('[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}'))) {
      return { invalidEmail: true };
    }
    else {
      this.email1 = false;
    }
  };

  phoneValidator(control: FormControl): { [s: string]: boolean } {
    if (!(control.value.match('[0-9]+'))) {
      return { invalidPhone: true };
    }
    else {
      this.phone1 = false;
    }
  };
}

