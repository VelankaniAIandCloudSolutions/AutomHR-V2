import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, PopoverController } from 'ionic-angular';
import { PopoverPage } from '../employeelist/employeelist';
import { AddDesignationsPage } from '../add-designations/add-designations';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the DesignationsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */


@Component({
  selector: 'page-change-language',
  templateUrl: 'change-language.html',
})
export class ChangeLanguagePage {

  public role : any;
  public roleId : any;
  public primaryColor:any;
  public loading:any;
  public url:any;
  public token :any;
  public resp:any;
  public languages:any;
  public keywords: any;
  public selected:any;
  constructor(public navCtrl: NavController, public navParams: NavParams, private popoverCtrl: PopoverController,public referenceService:ReferenceService,public apiService:ApiService,public http:HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getLanguageList();
    this.token = localStorage.getItem('token');
    var token =  {"token": this.token}
    this.http.get(this.url,{},token).then(data => {
      this.resp = JSON.parse(data.data);
      if(this.resp.message == "Invalid token or Token missing"){
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if(this.resp.message == "Success"){        
      this.languages = this.resp.data;
      this.loading.dismiss();
      }
    })
    .catch(error => {
      this.loading.dismiss();       
       this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
     //console.log("error=" + error);
     //console.log("error=" + error.error);
     //console.log("error=" + error.headers);
    });
   //console.log('ionViewWillEnter DepartmentsPage'); 
  };

  getHeaderStyle(){
    return { 'background': this.primaryColor}
  };
  
  change(){
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getLanguage();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    var data = { "code": this.selected };
   //console.log(data)
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp)
      if(this.resp.message == "Invalid token or Token missing"){
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        // this.navCtrl.popAll();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if(this.resp.message == "Success"){ 
      this.keywords = this.resp.data[0];
        localStorage.setItem("keywords", JSON.stringify(this.keywords));
      localStorage.setItem("language", this.selected);
        this.referenceService.basicAlert("New HRMS", 'Language updated');
     //console.log(this.resp);
      this.loading.dismiss();
      }
      if (this.resp.message == "File has not been updated.Please update"){
        this.keywords = [];
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'File has not been updated.Please update');
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
   //console.log('ionViewWillEnter DepartmentsPage');  
  }

  presentPopover(myEvent) {
    let popover = this.popoverCtrl.create(PopoverPage);
    popover.present({
      ev: myEvent
    });
  };

  addDesignation(){
    this.navCtrl.push(AddDesignationsPage);
  }

}
