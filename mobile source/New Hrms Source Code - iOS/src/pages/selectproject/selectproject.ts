import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, PopoverController } from 'ionic-angular';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { PopoverPage } from '../employeelist/employeelist';
import { AddDepartmentPage } from '../add-department/add-department';
import { LoginPage } from '../login/login';
import { TasksPage } from '../tasks/tasks';
import { NativeTransitionOptions, NativePageTransitions } from '@ionic-native/native-page-transitions';

/**
 * Generated class for the DepartmentsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-selectproject',
  templateUrl: 'selectproject.html',
})
export class SelectProjectPage {
  url: any;
  token: any;
  resp: any;
  projects: any;
  loading: any;
  public role: any;
  public roleId: any;
  public keywords:any={};
  public primaryColor: any;
  constructor(public navCtrl: NavController,public nativePageTransitions:NativePageTransitions, public navParams: NavParams, private http: HTTP, private popoverCtrl: PopoverController, public apiServices: ApiService, public refService: ReferenceService) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor =  localStorage.getItem('primary_color')
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.loading = this.refService.loading();
    this.loading.present();
    this.url = this.apiServices.getProjectList();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.post(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp);
      if(this.resp.message == "Invalid token or Token missing"){
        this.refService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        // this.navCtrl.popAll();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if(this.resp.message == "Success"){ 
      this.projects = this.resp.data.list;
     //console.log(this.resp);
      this.loading.dismiss();
      }
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.refService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
   //console.log('ionViewWillEnter DepartmentsPage');
  };

  getHeaderStyle(){
    return { 'background': this.primaryColor}
  };
  
  openTaks(project) {
    this.navCtrl.push(TasksPage,{
      project: project,
    });
  }

  ionViewWillLeave() {

    let options: NativeTransitionOptions = {
       direction: 'up',
       duration: 500,
       slowdownfactor: 3,
       iosdelay: 100,
       androiddelay: 150,
       fixedPixelsTop: 0,
      };
    this.nativePageTransitions.flip(options)
   }
  
}
