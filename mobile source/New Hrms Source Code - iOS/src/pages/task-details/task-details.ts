import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { Content } from 'ionic-angular';
import { LoginPage } from '../login/login';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { CreateTaskPage } from '../create-task/createtask';



/**
 * Generated class for the EmployeelistPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-task-details',
  templateUrl: 'task-details.html',
})
export class TaskDetailsPage {
  @ViewChild(Content) content: Content;

  token: any;
  url: any;
  loading: any;
  resp: any;
  color: any;
  public role: any;
  public roleId: any;
  public noData = false;
  public primaryColor: any;
  public task: any;
  public project: any;
  taskDetails:any={};
  files = false;
  isData=false;
  secondryColor: any;
  public keywords:any={};
  constructor(public navCtrl: NavController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.color = localStorage.getItem('colorCode');
    this.primaryColor = localStorage.getItem('primary_color');
    this.task = this.navParams.get('task');
    this.project = this.navParams.get('project');
    this.secondryColor = localStorage.getItem('secondry_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));

  }

  ionViewWillEnter() {
    this.task = this.navParams.get('task');
    this.project = this.navParams.get('project');
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.token = localStorage.getItem('token');
    this.url = this.apiService.taskView();
    var token = { 'token': this.token };
    var empData = {
      project: this.project.overviews.project_id,
      task: this.task.task_detail.t_id
    }
    this.http.post(this.url, empData, token)
      .then(data => {
       //console.log(data);
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
          this.taskDetails = this.resp.data;
          this.taskDetails.comments_list.forEach(element => {
            if (element.file_path!=''){
              element.filetype = element.file_path.substr((element.file_path.lastIndexOf('.') + 1));
              element.filename = element.file_path.substr((element.file_path.lastIndexOf('/') + 1));
            }
          });
         //console.log(this.taskDetails)
          this.isData=true;
        }
        this.loading.dismiss();
      })
      .catch(error => {
       //console.log(error);
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
   //console.log(this.task);
   //console.log(this.project)
  }
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  setDefaultUserPic(img) {
    img.avatar = "assets/imgs/user.jpg";
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
  delete() {
    let alert = this.referenceservice.confirmAlert("Confirm Delete", "Do you want to continue to delete this task");
    alert.present();
    alert.onDidDismiss((data) => {
      if (data) {
       //console.log(data);
        this.loading = this.referenceservice.loading();
        this.loading.present();
        this.token = localStorage.getItem('token');
        this.url = this.apiService.deleteTask();
        var token = { 'token': this.token };
        var empData = {
          project: this.project.project_id,
          task_id: this.task.task_detail.t_id
        }
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
                this.referenceservice.basicAlert(this.resp.message, 'task Removed successfully');
                this.navCtrl.getPrevious().data.type = "delete";
                this.navCtrl.getPrevious().data.task_id = this.task.task_detail.t_id;
                this.navCtrl.pop();
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
  };

  edit() {
    this.navCtrl.push(TaskEditPage, {
      project: this.project,
      task: this.task
    })
  };

  complete() {
    let alert = this.referenceservice.confirmAlert("Confirm to complete", "Do you want to continue to add this task to complete list?");
    alert.present();
    alert.onDidDismiss((data) => {
      if (data) {
       //console.log(data);
        this.loading = this.referenceservice.loading();
        this.loading.present();
        this.token = localStorage.getItem('token');
        this.url = this.apiService.completeTask();
        var token = { 'token': this.token };
        var empData = {
          task_id: this.task.task_detail.t_id,
          task_complete: true
        }
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
                this.referenceservice.basicAlert(this.resp.message, 'Task has been added to complete list successfully');
                this.navCtrl.getPrevious().data.type = "complete";
                this.navCtrl.getPrevious().data.task_id = this.task.task_detail.t_id;
                this.navCtrl.pop();
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

  assignTo(){
   //console.log("cgyhas")
    this.navCtrl.push(CreateTaskPage,{
      task: this.task.task_detail.t_id,
      project: this.project.project_id,
      type:'assign'
    })
  }

  dueDate() {
   //console.log("cgyhas")
    this.navCtrl.push(CreateTaskPage, {
      task: this.task.task_detail.t_id,
      project: this.project.project_id,
      type: 'due'
    })
  }

  presentPopover(myEvent) {
    let popover = this.popoverCtrl.create(TaskPopoverPage);
    popover.present({
      ev: myEvent
    });
    popover.onDidDismiss(data => {
      if (data == "edit") {
        this.edit();
      }
      else if (data == "delete") {
        this.delete();
      }
    })
  };
}

// ********************************************** Edit Task *********************************



@Component({
  selector: 'page-task-details',
  templateUrl: 'taskEdit.html',
})
export class TaskEditPage {

  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public task: any = {};
  public projectData: any = {};
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public phone1 = false;
  projectForm: FormGroup;
  public primaryColor: any;
  public fixed = false;
  taskMember = [];
  project: any;

  constructor(public navCtrl: NavController, public fb: FormBuilder, public popoverCtrl: PopoverController, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.projectForm = fb.group({
      'project': ['', [Validators.required]],
      'task_name': ['', [Validators.required]],
      'description': ['', [Validators.required]],
      'assigned_to': [, Validators.compose([Validators.required])],
      'start_date': ['', [Validators.required]],
      'due_date': ['', [Validators.required]],
      'estimate': ['', [Validators.required]],
      'task_id': ['', [Validators.required]],
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.task = this.navParams.get('task');
    this.project = this.navParams.get('project');

    this.task.assigned_to.forEach(element => {
      this.taskMember.push(element.user_id);
    });
  }

  ionViewWillEnter() {
    this.projectForm.controls.project.setValue(this.project.overviews.project_id);
    this.projectForm.controls.task_id.setValue(this.task.task_detail.t_id);
   //console.log(this.projectForm);
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
       //console.log(this.projectData);
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
    var array = this.projectForm.get('assigned_to').value;
    this.projectForm.controls.assigned_to.setValue(array.join())
   //console.log(this.projectForm.value)
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.editTask();
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
          this.referenceService.basicAlert(resp.message, 'task edited successfully');
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


@Component({
  template: `
  <ion-list class="popover-list">
  <button ion-item  (click)="edit()">Edit</button>
  <button ion-item  (click)="delete()">Delete</button>
  </ion-list>
`
})
export class TaskPopoverPage {
  public employee;
  public roleId: any;
  public role: any;

  constructor(public viewCtrl: ViewController, public navParams: NavParams, public navCtrl: NavController) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');

  }

  edit() {
    var action = "edit";
    this.viewCtrl.dismiss(action);
  }

  delete() {
    var action = "delete";
    this.viewCtrl.dismiss(action);
  }
}
