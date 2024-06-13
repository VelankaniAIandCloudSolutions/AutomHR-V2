import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ChangepasswordPage } from '../changepassword/changepassword';
import { ChangeLanguagePage } from '../change-language/change-language';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { StatusBar } from '@ionic-native/status-bar';
import { HTTP } from '@ionic-native/http';

/**
 * Generated class for the SettingsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-settings',
  templateUrl: 'settings.html',
})
export class SettingsPage {
  public primaryColor:any;
  public keywords:any={};
  public loading;
  constructor(public navCtrl: NavController, public apiservice: ApiService, public http: HTTP, public statusBar: StatusBar, public referenceservice: ReferenceService, public navParams: NavParams) {
    this.primaryColor =  localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
    this.loading = this.referenceservice.loading();
  }

  ionViewWillEnter() {
    this.keywords = {};
    // if (localStorage.getItem('keywords')) {
      this.keywords = JSON.parse(localStorage.getItem('keywords'));
    // }
    this.getLanguages();
  }
  ionViewDidLoad() {
    this.keywords = {};
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }
  changePassword(){
    this.navCtrl.push(ChangepasswordPage);
  };
  changeLanguage() {
    this.navCtrl.push(ChangeLanguagePage);
  };

  getLanguages() {
    var url = this.apiservice.getLanguage();
    var token = { token: "DQCTPQMKK9R6SXN4" };
    var language;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    if (localStorage.getItem('language')) {
      language = localStorage.getItem('language');
    }
    else {
      language = "en";
    }
    var data = { code: language };
    console.log(data);
    this.http
      .post(url, data, token)
      .then(data => {
        //console.log(data);
        var resp = JSON.parse(data.data);
        this.keywords = resp.data[0];
        localStorage.setItem("keywords", JSON.stringify(resp.data[0]));
        console.log(resp);
        this.loading.dismiss();
        //console.log(resp.data.unique_code);
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
      });
  }

  
  getHeaderStyle(){
    // this.keywords = JSON.parse(localStorage.getItem('keywords'));
    return { 'background':this.primaryColor}
  };

}
  