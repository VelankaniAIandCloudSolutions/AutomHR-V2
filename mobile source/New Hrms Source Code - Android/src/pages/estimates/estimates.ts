import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, Platform } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';
import { FileTransferObject, FileTransfer } from '@ionic-native/file-transfer';
import { File } from '@ionic-native/file';
import { LocalNotifications } from '@ionic-native/local-notifications';
/**
 * Generated class for the PayslipPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-estimates',
  templateUrl: 'estimates.html',
})
export class EstimatesPage {
  public primaryColor: any;
  estimate: any;
  loading: any;
  url: any;
  token: any;
  resp: any;
  role: any;
  roleId: any;
  items = [];
  subtotal =0;
  tax1=0;
  tax2=0;
  total=0;
  blackLogo:any;
  public keywords:any ={};
  // payslipData: any = {};
  // totalEarnings: any;
  // totalDeductions: any;
  // progressStatus = false;
  // perc: any;
  // progress: any;
  // netSalary: any;
  // pdfUrl: any;
  constructor(public navCtrl: NavController,public plt:Platform, public navParams: NavParams, private localNotifications: LocalNotifications, public file: File, public transfer: FileTransfer, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.blackLogo = localStorage.getItem('black_logo');
    this.estimate = this.navParams.get('estimate');
    this.items =  this.estimate.items;
   //console.log(this.estimate);    
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.items.forEach((value,key) => {
    this.subtotal = parseInt(value.total_cost)+ this.subtotal;
    this.url = this.apiService.convertCurrency();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var currency = localStorage.getItem('currency')
    var data = { code: currency, amount: value.total_cost };
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
        if (resp.message == "Success") {
          value.total_cost = resp.data;
        }
      });
    this.url = this.apiService.convertCurrency();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var currency = localStorage.getItem('currency')
    var data = { code: currency, amount: value.unit_cost };
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
        if (resp.message == "Success") {
          value.unit_cost = resp.data;
        }
      });
    });
    ////console.log(parseInt(this.estimate.tax1) * 0.1)
    this.tax1 =  Math.ceil(this.subtotal* (parseInt(this.estimate.tax1) * 0.01));
    this.tax2 =  Math.ceil(this.subtotal* (parseInt(this.estimate.tax2) * 0.01));
    this.total=this.subtotal+this.tax1+this.tax2;
   //console.log(this.total)
    this.url = this.apiService.convertCurrency();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var currency = localStorage.getItem('currency')
    var data = { code: currency, amount: this.tax1 };
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
        if (resp.message == "Success") {
          this.tax1 = resp.data;
        }
      });
      this.url = this.apiService.convertCurrency();
      this.token = localStorage.getItem("token");
      var token = { token: this.token };
      var currency = localStorage.getItem('currency')
      var data = { code: currency, amount: this.total };
      this.http
        .post(this.url, data, token)
        .then(data => {
          var resp = JSON.parse(data.data);
          if (resp.message == "Success") {
            this.total = resp.data;
          }
        });
    this.url = this.apiService.convertCurrency();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var currency = localStorage.getItem('currency')
    var data = { code: currency, amount:this.tax2 };
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
        if (resp.message == "Success") {
          this.tax2 = resp.data;
        }
      });
      this.url = this.apiService.convertCurrency();
      this.token = localStorage.getItem("token");
      var token = { token: this.token };
      var currency = localStorage.getItem('currency')
      var data = { code: currency, amount:this.subtotal };
      this.http
        .post(this.url, data, token)
        .then(data => {
          var resp = JSON.parse(data.data);
          if (resp.message == "Success") {
            this.subtotal = resp.data;
          }
        });
  };

  getHeaderStyle(){
    return { 'background': this.primaryColor}
  };
}


