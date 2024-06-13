import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, Content, ViewController } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';
import { AddSalaryPage } from '../add-salary/add-salary';
import { PayslipPage } from '../payslip/payslip';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { InvoicePage } from '../invoice/invoice';
import { CreateInvoicePage } from '../create-invoice/createinvoice';

/**
 * Generated class for the EmployeesalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-paymentlist',
  templateUrl: 'paymentlist.html',
})
export class PaymentListPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public invoiceList: any;
  public loading: any;
  public url: any;
  public token: any;
  public paymentList: any;
  public resp: any;
  public keywords:any={};
  pageNumber = 1;
  page = false;
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
    this.url = this.apiService.getPayments();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       console.log(this.resp)
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "No result were found" || this.resp.data.length != 0) {
          this.noData = true;
        }
        else {
          this.noData = false;
        }
        if (this.resp.message == "Success" && this.resp.data.length != 0) {
          this.paymentList = this.resp.data;
         console.log(this.paymentList);
          this.paymentList.forEach(element => {
            this.url = this.apiService.convertCurrency();
            this.token = localStorage.getItem("token");
            var token = { token: this.token };
            var currency = localStorage.getItem('currency')
            var data = { code: currency, amount: element.amount };
           console.log(data)
            this.http
              .post(this.url, data, token)
              .then(data => {
                var resp = JSON.parse(data.data);
                if (resp.message == "Success") {
                   element.amount = resp.data;
                }
               console.log(resp);
              });
          });
          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          }
          else{
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
    this.url = this.apiService.getPayments();
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
        if (this.resp.message == "No result were found") {
          this.noData = true;
        }
        else {
          this.noData = false;
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.paymentList = this.resp.data;
          this.paymentList.forEach(element => {
            this.url = this.apiService.convertCurrency();
            this.token = localStorage.getItem("token");
            var token = { token: this.token };
            var currency = localStorage.getItem('currency')
            var data = { code: currency, amount: element.amount };
           //console.log(data)
            this.http
              .post(this.url, data, token)
              .then(data => {
                var resp = JSON.parse(data.data);
                if (resp.message == "Success") {
                   element.amount = resp.data;
                }
               //console.log(resp);
              });
          });
         //console.log(this.paymentList);
          this.content.scrollToTop();
          // if (this.resp.data.list.length == 0) {
          //   this.noData = true;
          // }
          // else{
          //   this.noData = false;
          // }
          // if (this.resp.data.next_page == -1) {
          //   this.page = true;
          // }
        }

      })
      .catch(error => {
        refresher.complete();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error  =" + error.headers);
      });
  }

  // editSalary(payslip) {
  //   this.navCtrl.push(EditSalaryPage, {
  //     payslip: payslip
  //   })
  // };

  openPayment(payment) {
    this.navCtrl.push(PaymentDetailsPage, {
      payment: payment
    });
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


};




// ************************************************** Details *************************************



@Component({
  selector: 'page-paymentlist',
  templateUrl: 'paymentDetail.html',
})
export class PaymentDetailsPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public resp;
  blackLogo: any;
  public payment: any;
  public primaryColor: any;
  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.payment = this.navParams.get('payment');
    this.blackLogo = localStorage.getItem('black_logo');
  }
  ionViewDidEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}