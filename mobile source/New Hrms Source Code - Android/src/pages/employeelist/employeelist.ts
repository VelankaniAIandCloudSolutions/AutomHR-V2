import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { AddemployeePage } from '../addemployee/addemployee';
import { FormGroup, Validators, FormBuilder, FormControl } from '../../../node_modules/@angular/forms';
import { ProfilePage } from '../profile/profile';
import { Content } from 'ionic-angular';
import { LoginPage } from '../login/login';
import { NativePageTransitions, NativeTransitionOptions } from '@ionic-native/native-page-transitions';

/**
 * Generated class for the EmployeelistPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-employeelist',
  templateUrl: 'employeelist.html',
})
export class EmployeelistPage {
  @ViewChild('myElement') myElem;
  @ViewChild(Content) content: Content;
  bgColor = '3A57C4';
  token: any;
  url: any;
  loading: any;
  employeeList: any;
  pageNumber = 1;
  resp: any;
  page = false;
  public role: any;
  public roleId: any;
  public noData = false;
  public filterData: any = {};
  cssClass: string;
  public primaryColor: any;
  public keywords:any={};
  constructor(public navCtrl: NavController, private nativePageTransitions: NativePageTransitions, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {

    this.applyClassBySelection('rotateIn');
  }

  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getEmployeeList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.employeeList = this.resp.data.list;
         //console.log(this.resp);
          this.content.scrollToTop();
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          if (this.resp.data.length == 0) {
            this.noData = true;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
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
  doRefresh(refresher) {
    this.cssClass = '';
    this.page = false;
    this.loading = this.referenceservice.loading();
    // this.loading.present();
    this.url = this.apiService.getEmployeeList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.employeeList = this.resp.data.list;
         //console.log(this.resp);
          this.content.scrollToTop();
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          if (this.resp.data.length == 0) {
            this.noData = true;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
        // this.loading.dismiss();
      })
      .catch(error => {
        // this.loading.dismiss();
        refresher.complete();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  openModal() {
    this.cssClass = '';
    let modal = this.modalCtrl.create(FilterModalPage);
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getEmployeeList();
          this.token = localStorage.getItem('token')
         //console.log(this.token);
          var token = { 'token': this.token };
          data.page = 1;
          if (data.department == -1) {
            data.department = '';
          }
          if (data.designation == -2) {
            data.designation = '';
          }
          this.filterData = data;
         //console.log(data)
         //console.log(data.department)
          this.http.post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
              this.employeeList = this.resp.data.list;
              this.content.scrollToTop();
             //console.log(this.resp);
              if (this.resp.data.next_page == -1) {
                this.page = true;
              }
              if (this.resp.status_code == 0) {
                this.noData = true;
               //console.log(this.noData)
              }
              if (this.resp.data.length == 0) {
                this.noData = true;
              }
              this.loading.dismiss();
            })
            .catch(error => {
              this.content.scrollToTop();
              this.loading.dismiss();
             //console.log("error=" + error.status);
             //console.log("error=" + error.error); // error message as string
             //console.log("error=" + error.headers);
            });
        }
      }
    });
    modal.present();
   //console.log("modal")
  };

  ionViewWillLeave() {

    let options: NativeTransitionOptions = {
      direction: 'up',
      duration: 500,
      slowdownfactor: 3,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0,
    };

    this.nativePageTransitions.flip(options);

  }
  openProfile(myEvent, emp) {
    this.cssClass = '';
    if (myEvent.path[1].className == "more-icon") {
      this.presentPopover(myEvent, emp);
    }
    else {
      this.navCtrl.push(ProfilePage, {
        user: emp.user_id
      });
    }
  };
  presentPopover(myEvent, employee) {
    ////console.log(myEvent)
    let popover = this.popoverCtrl.create(PopoverPage);
    popover.present({
      ev: myEvent
    });
    popover.onDidDismiss(data => {
      this.cssClass = '';
      if (data == "delete") {
        let alert = this.referenceservice.confirmAlert("Confirm Delete", "Do you want to continue to delete this employee");
        alert.present();
        alert.onDidDismiss((data) => {
          if (data) {
            this.url = this.apiService.removeEmployee();
            var token = { 'token': this.token };
            var empData = { user_id: employee.user_id }
            this.http.post(this.url, empData, token)
              .then(data => {
                this.resp = JSON.parse(data.data);
                if (this.resp.message == "Invalid token or Token missing") {
                  this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
                  // this.navCtrl.popAll();
                  this.loading.dismiss();
                  localStorage.clear();
                  this.navCtrl.setRoot(LoginPage);
                }
                if (this.resp.message == "Success") {
                  if (this.resp.status_code == 1) {
                    this.loading.dismiss();
                    this.referenceservice.basicAlert(this.resp.message, 'Employee Removed successfully');
                    this.ionViewWillEnter();
                  }
                }
              })
              .catch(error => {
                this.loading.dismiss();
                this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
              });
          }
        });
      }
      if (data == "edit") {
        this.navCtrl.push(ProfilePage, {
          user: employee.user_id
        });
      }
    })
  };
  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }
  doInfinite(infiniteScroll) {
    this.cssClass = "";
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.getEmployeeList();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        this.filterData.page = this.resp.data.next_page
        this.http.post(this.url, this.filterData, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.employeeList.push(this.resp.data.list[i]);
            }
           //console.log(this.resp);
            if (this.resp.data.next_page == -1) {
              this.page = true;
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
           //console.log("error=" + error.status);
           //console.log("error=" + error.error); // error message as string
           //console.log("error=" + error.headers);
          });
      }
      else {
       //console.log('Async operation has ended');
        infiniteScroll.complete();
      }
    }, 1000);
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  addEmployee() {
    this.cssClass = '';
    this.navCtrl.push(AddemployeePage);
  }
}



/********************************************************** Popover *****************************/
@Component({
  template: `
    <ion-list class="popover-list">
      <button ion-item (click)="close('edit')">Edit</button>
      <button ion-item (click)="close('delete')">Delete</button>
    </ion-list>
  `
})
export class PopoverPage {
  public employee;
  constructor(public viewCtrl: ViewController, public navParams: NavParams, public navCtrl: NavController) {
  }

  close(action) {
    this.viewCtrl.dismiss(action);
  }
}

/********************************************************** Filter *****************************/

@Component({
  selector: 'page-employeelist',
  templateUrl: 'employee_filter.html',
})
export class FilterModalPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public departments;
  public designation;
  public designate = false;
  public department_id;
  public designation_id;
  public resp;
  public primaryColor: any;
  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
  }
  ionViewDidEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getDepartments();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
      this.departments = this.resp.data;
      // this.departments.push({ 'deptid': -1, 'deptname': 'All' });
      // this.filterData.department = -1;
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  department() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getDesignation();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = { "dept_id": this.filterData.department };
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {

      this.resp = JSON.parse(data.data);
      this.designation = this.resp.data.designations;
      this.designate = true;
      // this.designation.push({ 'id': -2, 'designation': 'All' });
      // this.filterData.designation = -2;
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
   //console.log(this.filterData.department)
  }

  dismiss() {
    if (this.filterData.department == undefined) {
      this.filterData.department = '';
     //console.log(this.filterData)
    }
    if (this.filterData.designation == undefined) {
      this.filterData.designation = '';
     //console.log(this.filterData)
    }
   //console.log(this.filterData.length);
    if (JSON.stringify(this.filterData) == JSON.stringify({})) {
      this.referenceservice.basicAlert("New HRMS", 'Please fill any field to filter');
    }
    else {
      this.viewCtrl.dismiss(this.filterData);
    }
  }
  closeFilter() {
   //console.log(this.filterData)
    this.viewCtrl.dismiss("close");
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}


/********************************************************** Edit *****************************/

@Component({
  selector: 'page-employeelist',
  templateUrl: 'employee_edit.html',
})
export class EditEmployeePage {
  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public departments;
  public designation;
  public username1 = false;
  public email1 = false;
  public phone1 = false;
  public designate = false;
  employeeForm: FormGroup;
  public employee;
  public primaryColor: any;
  public dept: any;
  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.employeeForm = fb.group({
      // 'username': ['', [Validators.required, Validators.minLength(3), this.nameValidator.bind(this)]],
      'email': ['', [Validators.required, this.emailValidator.bind(this)]],
      'fullname': [null, Validators.compose([Validators.required])],
      'designation_id': [null, Validators.compose([Validators.required])],
      'department_id': [null, Validators.compose([Validators.required])],
      'phone': ['', [Validators.required, Validators.minLength(10), Validators.maxLength(15), this.phoneValidator.bind(this)]],
    });
    this.employee = this.navParams.get('employee');
    this.dept = this.employee.department_id;
    // this.department();

   //console.log(this.employee)
    this.primaryColor = localStorage.getItem('primary_color');
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
        this.loading.dismiss();
        if (this.employee.department_id == 0) {
          this.loading.dismiss();
          // this.departments.push({ 'deptid': -1, 'deptname': 'Select Department' });
          // this.employee.department_id = -1;
        }
        else {
          this.loading.dismiss();
          this.department();
        }
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };


  department() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getDesignation();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = { "dept_id": this.employee.department_id };
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
      if (this.resp.message == "Success")
        this.loading.dismiss(); {
        this.designation = this.resp.data.designations;
        if (this.employee.designation_id == 0 || this.employee.department_id != this.dept) {
          if (this.designation.length != 0) {
            this.designate = true;
            // this.designation.push({ 'id': -1, 'designation': 'Select Designation' });
            // this.employee.designation_id = -1;
          }
          else {
            this.designate = false;
          }
        }
        else {
          this.loading.dismiss();
          this.designate = true;
        }
       //console.log(this.resp)
        this.loading.dismiss();
      }
    })
      .catch(error => {
       //console.log(error)
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  addEmployee() {
   //console.log(this.employeeForm.value);
    if (this.employeeForm.get('phone').valid && this.employeeForm.get('email').valid) {
      if (this.employeeForm.value.designation_id == -1) {
        this.employeeForm.value.designation_id = 0;
      }
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.editProfile();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = this.employeeForm.value;
      data.user_id = this.employee.user_id;
     //console.log(data);
      this.http.post(this.url, data, token).then(data => {
        var resp = JSON.parse(data.data);
       //console.log(resp)
       //console.log(data)
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
            this.referenceService.basicAlert(resp.message, 'Employee Edited successfully');
            this.navCtrl.pop();
          }
          else if (resp.status_code == 0) {
            this.loading.dismiss();
            this.referenceService.basicAlert('Already requested', resp.message);
          }
        }
      })
        .catch(error => {
          this.loading.dismiss();
          this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
        });
    }
    else {
      // if (!this.employeeForm.get('username').valid) {
      //   if (this.employeeForm.get('username').value == '') {
      //     this.referenceService.basicAlert("New HRMS", "User name must be filled");
      //   }
      //   else {
      //     this.referenceService.basicAlert("New HRMS", "User name should not contain any special characters and numbers.");
      //   }
      // }

      if (!this.employeeForm.get('phone').valid) {
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
    }
  };

  isValid(field: string) {
    let formField = this.employeeForm.get(field);
    return formField.valid || formField.pristine;
  }

  nameValidator(control: FormControl): { [s: string]: boolean } {
    if (control.value != "") {
     //console.log(control.value)
      if (!control.value.match("^[a-zA-Z ,.'-]+$")) {
        return { invalidName: true };
      }
    }
    else {
      this.username1 = false;
    }
  }
  emailValidator(control: FormControl): { [s: string]: boolean } {
    if (control.value != "") {
      if (!(control.value.toLowerCase().match('[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}'))) {
        return { invalidEmail: true };
      }
      else {
        this.email1 = false;
      }
    }
  }
  phoneValidator(control: FormControl): { [s: string]: boolean } {
    if (!(control.value.match('[0-9]+'))) {
      return { invalidPhone: true };
    }
    else {
      this.phone1 = false;
    }
  }
}
