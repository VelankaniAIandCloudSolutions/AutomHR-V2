import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { Content } from 'ionic-angular';
import { LoginPage } from '../login/login';
import { ProjectviewPage } from '../projectview/projectview';
import { NativePageTransitions, NativeTransitionOptions } from '@ionic-native/native-page-transitions';
import { CreateProjectPage } from '../create-project/createproject';
import { FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms';
import * as $ from 'jquery';
import { trigger } from '@angular/animations';
import 'jqueryui';



/**
 * Generated class for the projectList Page page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-projectlist',
  templateUrl: 'projectlist.html',
})
export class ProjectlistPage {
  @ViewChild(Content) content: Content;
  bgColor = '3A57C4';
  token: any;
  url: any;
  loading: any;
  projectList: any;
  pageNumber = 1;
  resp: any;
  color: any;
  public opentasks;
  public completedtasks;
  page = false;
  public role: any;
  time = 0;
  public roleId: any;
  public noData = false;
  cssClass: string;
  public primaryColor: any;
  public keywords :any={};
  secondryColor: any;
  constructor(public navCtrl: NavController, public nativePageTransitions: NativePageTransitions, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.color = localStorage.getItem('colorCode');
    this.primaryColor = localStorage.getItem('primary_color')
    this.secondryColor = localStorage.getItem('secondry_color')
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {
    this.applyClassBySelection('bounceInLeft');
  }
  doRefresh(refresher) {
    this.cssClass = '';
    this.page = false;
    this.loading = this.referenceservice.loading();
    // this.loading.present();
    this.url = this.apiService.getProjectList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.projectList = this.resp.data.list;
          refresher.complete();
          // this.projectList = this.resp.data; 
          this.projectList.forEach((value, key) => {
            value.time = this.time;
            this.time = this.time + 0.2;
           //console.log(value.tasks_open.length)
            this.projectList[key].opentasks = value.tasks_open.length;
            this.projectList[key].completedtasks = value.tasks_completed.length;
          });
         console.log(this.projectList)
          // this.content.scrollToTop();
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          if (this.projectList.length == 0) {
            this.noData = true;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
        if (this.resp.status_code == 0) {
          this.noData = true;
         //console.log(this.noData)
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

  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getProjectList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)

        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.projectList = this.resp.data.list;
          this.projectList.forEach((value, key) => {
            value.time = this.time;
            this.time = this.time + 0.2;
           //console.log(value.tasks_open.length)
            this.projectList[key].opentasks = value.tasks_open.length;
            this.projectList[key].completedtasks = value.tasks_completed.length;
          });
         //console.log(this.projectList)
          // this.content.scrollToTop();
       
          if (this.projectList.length == 0) {
            this.noData = true;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
        if (this.resp.status_code == 0) {
          this.noData = true;
         //console.log(this.noData)
        }
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + JSON.stringify(error));
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error  =" + JSON.stringify(error.headers));
      });

    $(".progress-bar").each(function () {
      var each_bar_width = $(this).attr('aria-valuenow');
      $(this).width(each_bar_width + '%');
    });
  };

  getProgresstyle() {
    return {
      background:
        "linear-gradient(to right," + this.secondryColor + "," + this.primaryColor + ")"
    };
  }
  getFontstyle() {
    return { color: this.secondryColor };
  }

  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }

  openModal() {
    this.cssClass = '';
    let modal = this.modalCtrl.create(ProjectFilterPage);
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getProjectList();
          this.token = localStorage.getItem('token')
         //console.log(this.token);
          var token = { 'token': this.token };
          data.page = 1;
         //console.log(data)
         //console.log(data.department)
          this.http.post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
              this.projectList = this.resp.data.list;
              this.content.scrollToTop();
              this.projectList.forEach((value, key) => {
                this.projectList[key].opentasks = value.tasks_open.length;
                this.projectList[key].completedtasks = value.tasks_completed.length;
              });
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

  getHeaderStyle() {
    return { 'background': this.primaryColor }
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

    this.nativePageTransitions.flip(options)
  }

  openProject(project) {
    this.cssClass = '';
    this.navCtrl.push(ProjectviewPage, {
      project: project,
    });
  };

  createProject() {
    this.cssClass = '';
   //console.log("csddc")
    this.navCtrl.push(CreateProjectPage);
  }

  edit(project) {
    this.cssClass = '';
    this.navCtrl.push(ProjectEditPage, {
      project: project
    })
  }

  doInfinite(infiniteScroll) {
    this.cssClass = "";
    setTimeout(() => {
      this.time = 0;
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.getProjectList();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = { page: this.resp.data.next_page }
        this.http.post(this.url, data, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
           //console.log(this.resp);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.resp.data.list[i].time = this.time;
              this.time = this.time + 0.2;
              this.projectList.push(this.resp.data.list[i]);
            }
            this.projectList.forEach((value, key) => {
             //console.log(value.tasks_open.length)
              this.projectList[key].opentasks = value.tasks_open.length;
              this.projectList[key].completedtasks = value.tasks_completed.length;
            });
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

  delete(project) {
    this.cssClass = '';
    let alert = this.referenceservice.confirmAlert("Confirm Delete", "Do you want to continue to delete this project");
    alert.present();
    alert.onDidDismiss((data) => {
      if (data) {
       //console.log(data);
        this.url = this.apiService.deleteProject();
        var token = { 'token': this.token };
        var empData = { project_id: project.overviews.project_id }
        this.http.post(this.url, empData, token)
          .then(data => {
           //console.log(data);
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
                var id = project.overviews.project_id;
                this.referenceservice.basicAlert(this.resp.message, 'Project Removed successfully');
                // this.ionViewWillEnter();
                var index = this.projectList.map(x => {
                  return x.overviews.project_id;
                }).indexOf(id);
                this.projectList.splice(index, 1);

              }
            }
          })
          .catch(error => {
           //console.log(error);
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
          });
      }
    });
  }
}




// ******************************************** EDIT PROJECT **********************************************




@Component({
  selector: 'page-projectlist',
  templateUrl: 'projectEdit.html',
})
export class ProjectEditPage {

  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public projectDetails: any = {};
  public projectData: any = {};
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public phone1 = false;
  projectForm: FormGroup;
  public primaryColor: any;
  public fixed = false;
  projectMember = [];

  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.projectForm = fb.group({
      'project_id': ['', [Validators.required]],
      'project_code': ['', [Validators.required]],
      'project_title': ['', [Validators.required]],
      'client': ['', [Validators.required]],
      'assign_lead': ['', Validators.compose([Validators.required])],
      'assign_to': ['', Validators.compose([Validators.required])],
      'fixed_rate': ['', [Validators.required]],
      'start_date': ['', [Validators.required]],
      'due_date': ['', [Validators.required]],
      'hourly_rate': ['', [Validators.required]],
      'fixed_price': ['', [Validators.required]],
      'estimate_hours': ['', [Validators.required]],
      'description': ['', [Validators.required]],
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.projectDetails = this.navParams.get('project');
    if (this.projectDetails.overviews.fixed_rate == 'No') {
      this.fixed = false;
    }
    else if (this.projectDetails.overviews.fixed_rate == 'Yes') {
      this.fixed = true;
    }
    this.projectDetails.overviews.project_team_members.forEach(element => {
      this.projectMember.push(element.user_id);
    });

  }

  ionViewWillEnter() {
   //console.log("bcksak");
    this.projectForm.controls.project_id.setValue(this.projectDetails.overviews.project_id)
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.projectData = this.resp.data;
       //console.log(this.projectDetails);
        this.loading.dismiss();
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
   //console.log('ionViewWillEnter DepartmentsPage');
   //console.log('ionViewWillEnter AddemployeePage');

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  addEmployee() {
    var array = this.projectForm.get('assign_to').value;
    if (array instanceof Array) {
      this.projectForm.controls.assign_to.setValue(array.join())
    }
   //console.log(this.projectForm.value)
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.editProject();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.projectForm.value;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
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
          this.referenceService.basicAlert(resp.message, 'Project created successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
      if (resp.status_code == -1) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Already requested', resp.message);
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };
}





// ************************************************** FILTER *************************************



@Component({
  selector: 'page-projectlist',
  templateUrl: 'project_filter.html',
})
export class ProjectFilterPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public clients:any={};
  public designation;
  public department_id;
  public designation_id;
  public resp;
  public primaryColor: any;
  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
  }

  ionViewWillEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp)
      this.clients = this.resp.data;
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



  dismiss() {
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

