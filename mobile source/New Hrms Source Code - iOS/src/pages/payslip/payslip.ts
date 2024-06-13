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
  selector: 'page-payslip',
  templateUrl: 'payslip.html',
})
export class PayslipPage {
  public primaryColor: any;
  public keywords:any={};
  payslip: any;
  loading: any;
  url: any;
  token: any;
  resp: any;
  role: any;
  roleId: any;
  payslipData: any = {};
  totalEarnings: any;
  totalDeductions: any;
  progressStatus = false;
  perc: any;
  progress: any;
  blackLogo:any;
  netSalary: any;
  pdfUrl: any;
  constructor(public navCtrl: NavController,public plt:Platform, public navParams: NavParams, private localNotifications: LocalNotifications, public file: File, public transfer: FileTransfer, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor =  localStorage.getItem('primary_color')
    this.payslip = this.navParams.get('payslip');
    this.blackLogo = localStorage.getItem('black_logo');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
   //console.log(this.payslip);    
  }

  ionViewWillEnter() {
    console.log(this.payslip.user_id);
    this.loading = this.referenceService.loading();
    this.loading.present();
    var data;
    if (this.roleId == 1) {
      data = {
        user_id: this.payslip.user_id,
        month: this.payslip.month,
        year: this.payslip.year
      };
    }
    else {
      data = {
        month: this.payslip.month,
        year: this.payslip.year
      };
    }
   //console.log(data)
    this.url = this.apiService.generatePayslip();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
        console.log(data);
        this.resp = JSON.parse(data.data);
       console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.payslipData = this.resp.data;
          this.totalEarnings = parseInt(this.payslipData.payslip_basic) + parseInt(this.payslipData.payslip_da) + parseInt(this.payslipData.payslip_hra) + parseInt(this.payslipData.payslip_conveyance) + parseInt(this.payslipData.payslip_allowance) + parseInt(this.payslipData.payslip_medical_allowance) + parseInt(this.payslipData.payslip_others);
          this.totalDeductions = parseInt(this.payslipData.payslip_ded_tds) + parseInt(this.payslipData.payslip_ded_esi) + parseInt(this.payslipData.payslip_ded_pf) + parseInt(this.payslipData.payslip_ded_leave) + parseInt(this.payslipData.payslip_ded_prof) + parseInt(this.payslipData.payslip_ded_welfare) + parseInt(this.payslipData.payslip_ded_fund) + parseInt(this.payslipData.payslip_ded_others);
          this.netSalary = this.totalEarnings - this.totalDeductions;
          this.loading.dismiss();
         console.log(this.payslipData);
         console.log(this.totalEarnings)
         console.log( parseInt(this.payslipData.payslip_basic) , parseInt(this.payslipData.payslip_da) ,parseInt(this.payslipData.payslip_hra) ,parseInt(this.payslipData.payslip_conveyance) ,parseInt(this.payslipData.payslip_allowance) , parseInt(this.payslipData.payslip_medical_allowance) ,parseInt(this.payslipData.payslip_others))
        
        }
        if(this.resp.message == "Required input missing"){
          this.loading.dismiss();
          this.referenceService.basicAlert("New HRMS", 'Please add salary before generating payslip');
          this.navCtrl.pop();
        }
      })
      .catch(error => {
       console.log(error);
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  }

  getHeaderStyle(){
    return { 'background':  this.primaryColor}
  };
  
  download() {
    // this.loading = this.referenceService.loading();
    // this.loading.present();
    this.url = this.apiService.getPdf();
    var data = {
      user_id: this.payslip.user_id,
      month: this.payslip.month,
      year: this.payslip.year
    };
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
          this.loading.dismiss();
        }
        if (this.resp.message == "Success") {
          this.pdfUrl = this.resp.data.file_name;
          this.loading.dismiss();
          this.openPdf();
         //console.log(this.payslipData)
        }
        if(this.resp.message == "Required input missing"){
          this.loading.dismiss();
          this.referenceService.basicAlert("New HRMS", 'Please add salary before generating payslip');
        }
      })
      .catch(error => {
       //console.log(error);
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  }

  openPdf() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    const fileTransfer: FileTransferObject = this.transfer.create();
    const url = this.pdfUrl;
    this.file.createDir('file:///storage/emulated/0/','SmartHrms',false)
    fileTransfer.onProgress((progressEvent) => {
      var perc = Math.floor(progressEvent.loaded / progressEvent.total * 100);
      this.progress = perc;
      if (perc == 100) {
        this.progressStatus = false
      } else {
        this.progressStatus = true;
      }
     //console.log(this.progress);
    });
    fileTransfer.download(url, 'file:///storage/emulated/0/SmartHrms/' + this.payslip.fullname + this.payslip.month+'-'+this.payslip.year+'.pdf').then((entry) => {
     //console.log('download complete: ' + entry.toURL());
      this.loading.dismiss();
      this.localNotifications.schedule({
        id: 1,
        title:'Download Finished',
        text: 'Pdf file has been downloaded',
      });
    }, (error) => {
      this.loading.dismiss();
     //console.log(error)
    });
  }
}
