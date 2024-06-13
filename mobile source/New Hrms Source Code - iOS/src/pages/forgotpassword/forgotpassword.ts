import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, LoadingController } from 'ionic-angular';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
/**
/**
 * Generated class for the ForgotpasswordPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-forgotpassword',
  templateUrl: 'forgotpassword.html',
})
export class ForgotpasswordPage {
  public loading;
  public url;
  public token;
  public colorCode:any;
  blackLogo:any;
  primaryColor:any;
  public keywords :any={};
  forgotpasswordForm: FormGroup
  constructor(public navCtrl: NavController,private fb: FormBuilder ,private referenceservice: ReferenceService, private apiservice: ApiService, public navParams: NavParams, private http: HTTP, private loadingCtrl: LoadingController) {
    this.forgotpasswordForm = fb.group({
      'username': [null, Validators.compose([Validators.required])]   
    });
    this.blackLogo = localStorage.getItem("black_logo");
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
   //console.log('ionViewWillEnter ForgotpasswordPage');
  }
  backToLogin(){
    this.navCtrl.pop();
  };
  
  getHeaderStyle(){
    return { 'background': this.primaryColor}
  };

  forgotPassword() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.token = localStorage.getItem('token');
    var data = this.forgotpasswordForm.value;
    this.url = this.apiservice.forgotpassword();
   //console.log(data)
    this.http.post(this.url, data, {})
      .then(data => {
        var resp = JSON.parse(data.data);
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceservice.basicAlert(resp.message,'Email has been sent successfully please check your mail');
          this.navCtrl.pop();
          }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceservice.basicAlert('Something went wrong',resp.message);
        }
      })
      .catch(error => {
        this.loading.dismiss();
       //console.log(error)
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
    // this.navCtrl.setRoot(HomePage);
  }

}
