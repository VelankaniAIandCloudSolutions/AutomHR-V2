import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, AlertController } from 'ionic-angular';
import { FormGroup, FormBuilder, Validators } from '../../../node_modules/@angular/forms';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddDepartmentPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-add-department',
  templateUrl: 'add-department.html',
})
export class AddDepartmentPage {

  public loading;
  public url;
  public keywords: any = {};
  private deptForm: FormGroup;
  public token;
  primaryColor: any;
  secondryColor: any;

  constructor(public navCtrl: NavController, public navParams: NavParams, private fb: FormBuilder, private alertCtrl: AlertController, private apiService: ApiService, private referenceService: ReferenceService, private http: HTTP) {
    this.deptForm = fb.group({
      'department': [null, Validators.compose([Validators.required])],
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
   //console.log('ionViewWillEnter AddDepartmentPage');
  };

  addDept() {
    var data = this.deptForm.value;
    this.url = this.apiService.addDept();
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.token = localStorage.getItem('token');
    var token = { 'token': this.token };
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        // this.navCtrl.popAll();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Success") {
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Department added successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Department already added');
        }
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
}
