import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, Content, ViewController } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';
import { AddSalaryPage } from '../add-salary/add-salary';
import { PayslipPage } from '../payslip/payslip';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

/**
 * Generated class for the EmployeesalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-employeesalary',
  templateUrl: 'employeesalary.html',
})
export class EmployeesalaryPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public payslipLists: any;
  public loading: any;
  public url: any;
  public token: any;
  public resp: any;
  pageNumber = 1;
  page = false;
  public keywords:any={};
  noData = false;
  constructor(public navCtrl: NavController, public referenceservice: ReferenceService, public modalCtrl: ModalController, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getSalaryList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.payslipLists = this.resp.data.list;
         //console.log(this.resp);
          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          }
          else {
            this.noData = false;
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
       //console.log("error  =" + error.headers);
      });
  };
  doRefresh(refresher) {
    this.page = false;
    this.url = this.apiService.getSalaryList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.payslipLists = this.resp.data.list;
         //console.log(this.resp);
          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          }
          else {
            this.noData = false;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error  =" + error.headers);
      });
  };
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  editSalary(payslip) {
    this.navCtrl.push(EditSalaryPage, {
      payslip: payslip
    })
  };


  addSalary() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.runPayroll();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    
    this.http.get(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        this.referenceservice.basicAlert('Success', 'Payslips added successfully');
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
  


  generatePayslip(payslip) {
    this.navCtrl.push(PayslipPage, {
      payslip: payslip
    })
  };

  openModal() {
    let modal = this.modalCtrl.create(SalaryFilterPage);
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getSalaryList();
          this.token = localStorage.getItem('token')
         //console.log(this.token);
          var token = { 'token': this.token };
          data.page = 1;
         //console.log(data)
          this.http.post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
              this.payslipLists = this.resp.data.list;
              this.content.scrollToTop();
             //console.log(this.resp);
              if (this.resp.data.next_page == -1) {
                this.page = true;
              }
              if (this.resp.data.list.length == 0) {
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


  doInfinite(infiniteScroll) {
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.getSalaryList();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = { page: this.resp.data.next_page }
        this.http.post(this.url, data, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.payslipLists.push(this.resp.data.list[i]);
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
}



@Component({
  selector: 'page-add-salary',
  templateUrl: 'empsalaryEdit.html',
})
export class EditSalaryPage {

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
  public payslip: any = {};
  public dateyear: any;
  public month: any;
  constructor(public navCtrl: NavController, public fb: FormBuilder, public referenceservice: ReferenceService, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    var data = this.navParams.get('payslip');
    this.payslip.user_id = data.user_id;
    this.payslip.year = data.year;
    this.payslip.month = data.month;
    this.payslip.net_salary = data.salary,
      this.payslip.basic_pay = data.payslip_details.payslip_basic;
    this.payslip.da = data.payslip_details.payslip_da;
    this.payslip.hra = data.payslip_details.payslip_hra;
    this.payslip.conveyance = data.payslip_details.payslip_conveyance;
    this.payslip.allowance = data.payslip_details.payslip_allowance;
    this.payslip.medical_allowance = data.payslip_details.payslip_medical_allowance;
    this.payslip.earning_others = data.payslip_details.payslip_others;
    this.payslip.tds = data.payslip_details.payslip_ded_tds;
    this.payslip.esi = data.payslip_details.payslip_ded_esi;
    this.payslip.pf = data.payslip_details.payslip_ded_pf;
    this.payslip.leaves = data.payslip_details.payslip_ded_leave;
    this.payslip.prof_tax = data.payslip_details.payslip_ded_prof;
    this.payslip.labour_welfare = data.payslip_details.payslip_ded_welfare;
    this.payslip.fund = data.payslip_details.payslip_ded_fund;
    this.payslip.ded_others = data.payslip_details.payslip_ded_others;
  }
  ionViewWillEnter() {
    // this.loading = this.referenceservice.loading();
    // this.loading.present();
    // this.url = this.apiService.getUserList();
    // this.token = localStorage.getItem('token')
    ////console.log(this.token);
    // var token = { 'token': this.token };
    // this.http.post(this.url, {}, token)
    //   .then(data => {
    //     this.resp = JSON.parse(data.data);
    //    //console.log(this.resp)
    //     if (this.resp.message == "Invalid token or Token missing") {
    //       this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
    //       // this.navCtrl.popAll();
    //       this.loading.dismiss();
    //       localStorage.clear();
    //       this.navCtrl.setRoot(LoginPage);
    //     }
    //     if (this.resp.message == "Success") {
    //       this.empList = this.resp.data;
    //       this.empList.push({ "user_id": -1, "fullname": "Select Employee" });
    //       this.selected = -1;
    //      //console.log(this.resp);
    //     }
    //     this.loading.dismiss();
    //   })
    //   .catch(error => {
    //     this.loading.dismiss();
    //     this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
    //    //console.log("error=" + error);
    //    //console.log("error=" + error.error);
    //    //console.log("error=" + error.headers);
    //   });
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  addSalary() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.addSalary();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = this.payslip;
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
          this.referenceservice.basicAlert("New HRMS", 'Payslip Edited Successfully');
         //console.log(this.resp);
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
  caculate() {
   //console.log(this.payslip.salary)
    this.payslip.basic_pay = Math.ceil(parseInt(this.payslip.net_salary) * .50);
    this.payslip.da = Math.ceil(parseInt(this.payslip.net_salary) * .40);
    this.payslip.hra = Math.ceil(parseInt(this.payslip.net_salary) * .10);
  }
}




@Component({
  selector: 'page-add-salary',
  templateUrl: 'employeesalaryfilter.html',
})
export class SalaryFilterPage {

  public role: any;
  public roleId: any;
  public primaryColor: any;
  public employeenames: any;
  public url: any;
  public loading: any;
  public resp: any;
  public token: any;
  public empList: any;
  public selected;
  public dateyear: any;
  public month: any;
  public filterData: any = {};
  constructor(public navCtrl: NavController, public viewCtrl: ViewController, public fb: FormBuilder, public referenceservice: ReferenceService, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
  }
  ionViewWillEnter() {
    if (this.roleId == 1) {
      this.loading = this.referenceservice.loading();
      this.loading.present();
      this.url = this.apiService.getUserList();
      this.token = localStorage.getItem('token')
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
    }
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  filter() {
    if (this.dateyear != undefined || this.filterData.user_id != -1) {
      if (this.dateyear != undefined) {
        var date = this.dateyear.split("-");
        this.filterData.month = date[1];
        this.filterData.year = date[0];
        if (this.filterData.user_id != -1) {
          this.viewCtrl.dismiss(this.filterData);
        }
        else {
          this.filterData.user_id = '';
          this.viewCtrl.dismiss(this.filterData);
        }

      }
      else if (this.filterData.user_id != -1) {
        this.filterData.month = '';
        this.filterData.year = '';
        this.viewCtrl.dismiss(this.filterData);
      }
    }
    else {
      if (JSON.stringify(this.filterData) == JSON.stringify({})) {
        this.referenceservice.basicAlert("New HRMS", 'Please fill any field to filter');
      }
    }
  };

  closeFilter() {
    this.viewCtrl.dismiss("close");
  };
}
