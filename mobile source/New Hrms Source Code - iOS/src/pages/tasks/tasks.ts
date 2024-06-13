import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { Content } from 'ionic-angular';
import { LoginPage } from '../login/login';
import { TaskDetailsPage } from '../task-details/task-details';
import { CreateTaskPage } from '../create-task/createtask';



/**
 * Generated class for the EmployeelistPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-tasks',
  templateUrl: 'tasks.html',
})
export class TasksPage {
  @ViewChild(Content) content: Content;

  token: any;
  url: any;
  loading: any;
  employeeList: any;
  pageNumber = 1;
  resp: any;
  project: any;
  color: any;
  page = false;
  compeltedTask = [];
  taskcompleted = false;
  notask = false;
  taskopen = false;
  openTasks = [];
  projectList:any={};
  tasks = 'pending';
  public role: any;
  public roleId: any;
  public noData = false;
  public primaryColor: any;
  public keywords:any={};
  constructor(public navCtrl: NavController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.color = localStorage.getItem('colorCode');
    this.primaryColor = localStorage.getItem('primary_color')
    this.project = this.navParams.get('project');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
    console.log(this.project)
  }

  ionViewWillEnter(){
    console.log(this.project)
    this.projectList = {};
    this.compeltedTask = [];
    this.openTasks = [];
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getProjectList();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.post(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        // this.navCtrl.popAll();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.projectList = this.resp.data.list;
        console.log(this.projectList);
        this.compeltedTask = [];
        this.openTasks = [];
        this.projectList.forEach(element => {
          if (element.overviews.project_id == this.project.overviews.project_id){
            this.project = element;
            this.project.tasks.forEach(element => {
              var img = this.project.user_profile_path + element.task_detail.avatar;
              element.task_detail.avatar = img;
              element.task_files.forEach(element => {
                var img = this.project.task_file_path + element.avatar;
                element.avatar = img;
              });
            });

            if (this.project.tasks.length == 0) {
              this.notask = true;
            }
            this.project.tasks.forEach((value, key) => {
              if (value.task_detail.task_progress == 100) {
                this.compeltedTask.push(value);
                this.taskcompleted = true;
              }
              else {
                this.openTasks.push(value)
                this.taskopen = true;
              }
            });
            if (this.navParams.get('task_id')) {
              this.compeltedTask = [];
              this.openTasks = [];
              var id = this.navParams.get('task_id');
              if (this.navParams.get('type') == 'delete') {
                var index = this.project.tasks.map(x => {
                  return x.task_detail.t_id;
                }).indexOf(id);
                this.project.tasks.splice(index, 1);
              }
              else if (this.navParams.get('type') == 'complete') {
                this.project.tasks.forEach((value, key) => {
                  if (value.task_detail.task_progress == 100) {
                    this.compeltedTask.push(value);
                    this.taskcompleted = true;
                  }
                  else {
                    this.openTasks.push(value)
                    this.taskopen = true;
                  }
                });
              }
            }
          }
        });
       //console.log(this.resp);
        this.loading.dismiss();
      }
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  task(){
    
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  openTask(task) {
    this.navCtrl.push(TaskDetailsPage, {
      task: task,
      project:this.project
    });
  }


  createTask() {
    this.navCtrl.push(CreateTaskPage,{
      project:this.project.overviews.project_id,
      type:'task'
    })
  }

  setDefaultUserPic(img) {
    img.task_detail.avatar = "assets/imgs/user.jpg";
  }
}