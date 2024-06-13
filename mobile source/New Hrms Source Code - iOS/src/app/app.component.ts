import { Component, ViewChild, Injectable } from "@angular/core";
import { Nav, Platform, NavController } from "ionic-angular";
import { StatusBar } from "@ionic-native/status-bar";
import { SplashScreen } from "@ionic-native/splash-screen";

import { HomePage } from "../pages/home/home";
import { ListPage } from "../pages/list/list";
import { LoginPage } from "../pages/login/login";
import {
  EmployeelistPage,
  EditEmployeePage
} from "../pages/employeelist/employeelist";
import { DepartmentsPage } from "../pages/departments/departments";
import { HolidaysPage } from "../pages/holidays/holidays";
import { LeaveRequestPage } from "../pages/leave-request/leave-request";
import { DesignationsPage } from "../pages/designations/designations";
import { ProfilePage } from "../pages/profile/profile";
import { PayslipPage } from "../pages/payslip/payslip";
import { ChangepasswordPage } from "../pages/changepassword/changepassword";
import { AttendancePage } from "../pages/attendance/attendance";
import { AddemployeePage } from "../pages/addemployee/addemployee";
import { AttendanceListPage } from "../pages/attendance-list/attendance-list";
import { AttendanceDetailsPage } from "../pages/attendance-details/attendance-details";
import { HTTP } from "@ionic-native/http";
import { ReferenceService } from "../providers/referenceService";
import { ApiService } from "../providers/apiServices";
import { Network } from "../../node_modules/@ionic-native/network";
import { EmployeesalaryPage } from "../pages/employeesalary/employeesalary";
import { AddSalaryPage } from "../pages/add-salary/add-salary";
import { LocalNotifications } from "@ionic-native/local-notifications";
import { VoiceCallPage } from "../pages/voicecall/voicecall";
import { IncomingCallPage } from "../pages/incomingcall/incomingcall";
import { VideoCallPage } from "../pages/videocall/videocall";
import { ChatPage } from "../pages/chat/chat";
import { ChatListPage } from "../pages/chat-list/chat-list";
import { ProjectlistPage } from "../pages/projectlist/projectlist";
import { ProjectviewPage } from "../pages/projectview/projectview";
import { ContactPage } from "../pages/contact/contact";
import { TaskDetailsPage } from "../pages/task-details/task-details";
import { TasksPage } from "../pages/tasks/tasks";
import { InvoicePage } from "../pages/invoice/invoice";
import { EstimatesListPage } from "../pages/estimateslist/estimateslist";
import { EstimatesPage } from "../pages/estimates/estimates";
import { InvoiceListPage } from "../pages/invoicelist/invoicelist";
import { OneSignal } from "@ionic-native/onesignal";
import { AndroidPermissions } from "@ionic-native/android-permissions";
import { TimeSheetListPage } from "../pages/timesheets/timesheetslist";
import { PaymentListPage } from "../pages/paymentlist/paymentlist";
import { ExpensesListPage } from "../pages/expenseslist/expenseslist";
import { Events } from 'ionic-angular';
import { ClientsPage } from "../pages/clients/clients";
import { SettingsPage } from "../pages/settings/settings";
import { SelectProjectPage } from "../pages/selectproject/selectproject";
import { TimesheetContactPage } from "../pages/timesheet-list/timesheet-list";
@Injectable()
@Component({
  templateUrl: "app.html"
})
export class MyApp {
  @ViewChild(Nav) nav: Nav;
  empMenu = false;
  pageMenu = false;
  rootPage: any;
  selectedPage: any;
  public role;
  public roleId;
  public url;
  public loading;
  public colorCode: any;
  public token: any;
  public resp: any;
  primaryColor:any;
  public keywords: any = {};
  // public menus:any;
  secondryColor:any;
  pages: Array<{ title: string; component: any }>;

  constructor(
    public platform: Platform,
    public apiservice: ApiService,
    public statusBar: StatusBar,
    private androidPermissions: AndroidPermissions,
    private oneSignal: OneSignal,
    public localNotifications: LocalNotifications,
    private network: Network,
    public splashScreen: SplashScreen,
    private apiService: ApiService,
    private referenceService: ReferenceService,
    private http: HTTP,
    public events: Events
  ) {
    this.initializeApp();
    // used for an example of ngFor and navigation
    this.pages = [
      { title: "Home", component: HomePage },
      { title: "employeeList", component: EmployeelistPage },
      { title: "department", component: DepartmentsPage },
      { title: "holidays", component: HolidaysPage },
      { title: "leave", component: LeaveRequestPage },
      { title: "designations", component: DesignationsPage },
      { title: "profile", component: ProfilePage },
      { title: "login", component: LoginPage },
      { title: "payslip", component: PayslipPage },
      { title: "attendance", component: AttendancePage },
      { title: "attendancelist", component: AttendanceListPage },
      { title: "attendancedetail", component: AttendanceDetailsPage },
      { title: "empSalary", component: EmployeesalaryPage },
      { title: "contactlist", component: ContactPage },
      { title: "chatlist", component: ChatListPage },
      { title: "estimateslist", component: EstimatesListPage },
      { title: "invoicelist", component: InvoiceListPage },
      { title: "timesheet", component: TimeSheetListPage },
      { title: "paymentlist", component: PaymentListPage },
      { title: "expenseslist", component: ExpensesListPage },
      {title:"clientsList",component:ClientsPage},
      {title:"projectlist",component:ProjectlistPage},
      {title:"settings",component:SettingsPage},
      { title: "task", component: SelectProjectPage },
    ];
    if (localStorage.getItem('keywords')){
      this.keywords = JSON.parse(localStorage.getItem('keywords'));
    }
   
  }

  initializeApp() {
    this.platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need
      if (this.splashScreen) {
       //console.log("splash");
        this.splashScreen.hide();
      }
      let disconnectSubscription = this.network.onDisconnect().subscribe(() => {
       //console.log("menu")
        this.role = localStorage.getItem("role");
        this.roleId = localStorage.getItem("role_id");
        this.menuOpened();
        this.referenceService.basicAlert(
          "New HRMS",
          "Oops!! It seems like you are not connected to internet"
        );
      });
      this.getLanguages();
      this.menuOpened();
      this.role = localStorage.getItem("role");
      this.roleId = localStorage.getItem("role_id");
      this.androidPermissions
        .requestPermissions([
          this.androidPermissions.PERMISSION.CAMERA,
          this.androidPermissions.PERMISSION.MODIFY_AUDIO_SETTINGS,
          this.androidPermissions.PERMISSION.RECORD_AUDIO,
          this.androidPermissions.PERMISSION.READ_EXTERNAL_STORAGE
        ])
        .then(() => {
          //getDevices uses Enumerate Devices and initWebRTC starts the chat connections, etc...
        });
      
      this.oneSignal.setSubscription(true);
      this.oneSignal.startInit(
        "4b3604d1-e319-4b3e-8f00-d9e0997320b0",
        "669279692499"
      );
      this.oneSignal.inFocusDisplaying(
        this.oneSignal.OSInFocusDisplayOption.None
      );
      this.oneSignal.handleNotificationReceived().subscribe(data => {
       //console.log(data);
        if (data.payload.additionalData.call_type == "video") {
          this.nav.push(IncomingCallPage, {
            videonotification: data.payload.additionalData
          });
        } else if (data.payload.additionalData.call_type == "audio") {
          this.nav.push(IncomingCallPage, {
            videonotification: data.payload.additionalData
          });
        } else if (data.payload.additionalData.call_type == "text") {
          // this.nav.push(ChatPage, {
          //   notification: data.payload.additionalData
          // });
        }
      });
      this.oneSignal.handleNotificationOpened().subscribe(data => {
       //console.log(data);
        this.splashScreen.hide();
        if (data.notification.payload.additionalData.call_type == "video") {
          if (data.action.actionID == "answer") {
            this.nav.push(VideoCallPage, {
              notification: data.notification.payload.additionalData
            });
          } else if (data.action.actionID == "decline") {
            this.url = this.apiservice.declineCall();
            this.token = localStorage.getItem("token");
           //console.log(this.token);
            var id = {
              user_id: data.notification.payload.additionalData.from_id
            };
            var token = { token: this.token };
            this.http
              .post(this.url, id, token)
              .then(data => {
                this.resp = JSON.parse(data.data);
               //console.log(this.resp);
                if (this.resp.message == "Success") {
                  // this.navCtrl.setRoot(HomePage);
                  // this.loading.dismiss();
                }
              })
              .catch(error => {
                // this.navCtrl.setRoot(HomePage);
              });
          }
        } else if (
          data.notification.payload.additionalData.call_type == "audio"
        ) {
          if (data.action.actionID == "answer") {
            this.nav.push(VoiceCallPage, {
              notification: data.notification.payload.additionalData
            });
          } else if (data.action.actionID == "decline") {
            this.url = this.apiservice.declineCall();
            this.token = localStorage.getItem("token");
           //console.log(this.token);
            var id = {
              user_id: data.notification.payload.additionalData.from_id
            };
            var token = { token: this.token };
            this.http
              .post(this.url, id, token)
              .then(data => {
                this.resp = JSON.parse(data.data);
               //console.log(this.resp);
                if (this.resp.message == "Success") {
                  // this.navCtrl.setRoot(HomePage);
                  // this.loading.dismiss();
                }
              })
              .catch(error => {
                // this.navCtrl.setRoot(HomePage);
              });
          }
        } else if (
          data.notification.payload.additionalData.call_type == "text"
        ) {
          this.nav.setRoot(ChatPage, {
            notification: data.notification.payload.additionalData
          });
        }
      });
      this.oneSignal.endInit();
      this.oneSignal.getIds().then(token => {
       //console.log(token);
      });
      this.events.publish('user:created', this.menuOpened(),this.menuClosed);

     
      if (localStorage.getItem("loginStatus")) {
        this.rootPage = HomePage; // user can user this.nav.setRoot(TutorialPage);
      } else {
        this.rootPage = LoginPage; // user can user this.nav.setRoot(LoginPage);
      }
      // this.getLanguages();
    });
  }

  ionViewWillEnter() {
    this.getLanguages();
  }

  getstyle() {

    this.menuOpened();
    this.primaryColor = localStorage.getItem("primary_color");
    this.secondryColor = localStorage.getItem("secondry_color");
    return {
      background:
        "linear-gradient(to right," + this.secondryColor + "," + this.primaryColor + ")"
    };
  }
  getProgresstyle() {
    return {
      background:
        "linear-gradient(to right," + this.secondryColor + "," + this.primaryColor + ")"
    };
  }
  getHeaderStyle() {
    return { background: this.primaryColor };
  }
  menuOpened() {
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id")   ;
    // this.menus = JSON.parse(localStorage.getItem('menus'));
    // // console.log(this.menus);
    // // if (localStorage.getItem('menus')){
    //   for (const [key, value] of this.menus) {
    //     console.log(key, value);
    //   // }
    // }

  }
  getLanguages() {
    var url = this.apiservice.getLanguage();
    var token = { token: "DQCTPQMKK9R6SXN4" };
    var language;
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

        //console.log(resp.data.unique_code);
      })
      .catch(error => {
        console.log(error)
        this.loading.dismiss();
        this.referenceService.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
      });
  }
  menuClosed() {
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id");
    // this.menus = JSON.parse(localStorage.getItem('menus'));
    // // console.log(this.menus);
  }

  openPage(page) {
    this.selectedPage = "";
    this.pages.forEach(element => {
      if (element.title == page) {
        if (page != "Home") {
          this.nav.push(element.component);
        } else {
          this.nav.setRoot(element.component);
        }
      }
    });
  }

  
  openTimeSheets() {
    this.nav.push(TimesheetContactPage, {
      timesheet: true
    });
  }
  

  openSub(page) {
    if (this.selectedPage == page) {
      this.selectedPage = "";
    } else {
      this.selectedPage = page;
    }
  }
}
