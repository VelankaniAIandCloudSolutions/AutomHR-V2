import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { Content } from 'ionic-angular';
import { TaskDetailsPage } from '../task-details/task-details';
import { CreateTaskPage } from '../create-task/createtask';
import 'jqueryui';

/**
 * Generated class for the EmployeelistPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-projectview',
  templateUrl: 'projectview.html',
})
export class ProjectviewPage {
  @ViewChild(Content) content: Content;
  bgColor = '3A57C4';
  token: any;
  url: any;
  loading: any;
  employeeList: any;
  pageNumber = 1;
  resp: any;
  color: any;
  page = false;
  public pro: any;
  public opentasks;
  public completedtasks;
  project = 'overview';
  public currentEvents: any;
  public role: any;
  public task = false;
  public roleId: any;
  public noData = false;
  public primaryColor: any;
  public keywords:any={};
  secondryColor: any;
  events = [];
  tooltip: any;
  taskEvent: any = {};
  constructor(public navCtrl: NavController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.color = localStorage.getItem('primary_color');
    this.primaryColor = localStorage.getItem('primary_color')
    this.secondryColor = localStorage.getItem('secondry_color')
    this.pro = this.navParams.get('project');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
   //console.log(this.pro)
    this.opentasks = this.pro.tasks_open.length;
    this.completedtasks = this.pro.tasks_completed.length;
    if (this.pro.tasks.length == 0) {
      this.task = true;
    }
  }

  ionViewWillEnter() {
 
  }

  ionViewWillLoad() {
    this.pro.overviews.project_team_members.forEach(element => {
      var img = this.pro.overviews.user_profile_path + element.avatar;
      element.avatar = img;
    });
   //console.log(this.pro);
    this.pro.tasks.forEach(element => {
     //console.log(element.task_detail)
      if (element.task_detail.due_date!=null){
        var date = element.task_detail.due_date.split('-')
        var data: any = {};
        data.year = date[0];
        data.month = parseInt(date[1]) - 1;
        data.date = date[2];
        data.detail = element.task_detail;
        this.events.push(data);
      }
    });
  };

  getProgresstyle() {
    return {
      background:
        "linear-gradient(to right," + this.secondryColor + "," + this.primaryColor + ")"
    };
  }
  getHeaderStyle() {
    return { background: this.primaryColor };
  }
  getFontstyle() {
    return { color: this.secondryColor };
  }


  setDefaultPic() {
    this.pro.overviews.project_lead_photo = "assets/imgs/user.jpg";
  };

  setDefaultUserPic(img) {
    img.avatar = "assets/imgs/user.jpg";
  };

  openTask(task) {
    this.navCtrl.push(TaskDetailsPage, {
      task: task,
      project:this.pro,
    });
  };

  onDaySelect(event) {
   //console.log(event);
    var month = event.month + 1;
    var day = event.date;
   //console.log(day)
    if (day < 10) {
     //console.log(day)
      day = '0' + day;
    }
    var eventData: any = {};
    var date = event.year + '-' + month + '-' + day;
    this.pro.tasks.forEach(element => {
      if (element.task_detail.due_date == date) {
        eventData = element;
      }
    });
   //console.log(eventData)
    if (JSON.stringify(eventData) != '{}') {
      this.navCtrl.push(CalendarModalPage, { event: eventData })
    }
    else {
      this.referenceservice.basicAlert("New HRMS", 'No Events on this day');
    }
  }
  onMonthSelect(event) {
   //console.log(event)
  }


  createTask() {
    this.navCtrl.push(CreateTaskPage, {
      project: this.pro.overviews.project_id,
        type: 'task'
    })
  }
}


// **********************************TIMESHEETS DETAILS PAGE****************************************

@Component({
  selector: 'page-projectview',
  templateUrl: 'calanderModal.html',
})
export class CalendarModalPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public event: any = {};
  public loading: any;
  public url: any;
  public token: any;
  public resp: any;
  pageNumber = 1;
  page = false;
  noData = false;
  constructor(public navCtrl: NavController, public referenceservice: ReferenceService, public modalCtrl: ModalController, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color')
    this.event = this.navParams.get('event');
   //console.log(this.event)
  }

  ionViewWillEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
}




