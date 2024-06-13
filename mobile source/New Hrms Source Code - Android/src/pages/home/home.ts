import { Component, ViewChild, Inject, Injector } from "@angular/core";
import {
  NavController,
  AlertController,
  ViewController,
  NavParams,
  PopoverController,
  Nav,
  Events
} from "ionic-angular";
import { EmployeelistPage } from "../employeelist/employeelist";
import { ProfilePage } from "../profile/profile";
import { LoginPage } from "../login/login";
import { ReferenceService } from "../../providers/referenceService";
import { SettingsPage } from "../settings/settings";
import { NotificationPage } from "../notification/notification";
import { ApiService } from "../../providers/apiServices";
import { HTTP } from "@ionic-native/http";
import { StatusBar } from "@ionic-native/status-bar";
import { ProjectlistPage } from "../projectlist/projectlist";
import { OneSignal } from "@ionic-native/onesignal";
import { ClientsPage } from "../clients/clients";
import { TasksPage } from "../tasks/tasks";
import { EmployeesalaryPage } from "../employeesalary/employeesalary";
import { AttendancePage } from "../attendance/attendance";
import { SelectProjectPage } from "../selectproject/selectproject";
import { IncomingCallPage } from "../incomingcall/incomingcall";
import { VideoCallPage } from "../videocall/videocall";
import { InvoiceListPage } from "../invoicelist/invoicelist";
import { EstimatesListPage } from "../estimateslist/estimateslist";
import { VoiceCallPage } from "../voicecall/voicecall";
import {
  NativePageTransitions,
  NativeTransitionOptions
} from "@ionic-native/native-page-transitions";
import { ClientProfilePage } from "../clientprofile/clientprofile";
import * as $ from "jquery";
import "jqueryui";
import { trigger } from "@angular/animations";
import { MyApp } from "../../app/app.component";

@Component({
  selector: "page-home",
  templateUrl: "home.html"
})
export class HomePage {
  @ViewChild("myElement") myElem;
  public roleId: any;
  public role: any;
  public color: any;
  public url;
  public keywords: any = {};
  public token;
  public resp;
  count: any = {};
  one = 30;
  public loading;
  public call = false;
  cssClass: string;
  primaryColor: any;
  secondryColor: any;
  fullWhiteLogo: any;
  myApp:any;
  // sinchClient: any;
  public currentEvents: any;
  public colorCode: any;
  constructor(public navCtrl: NavController, public events: Events, injector:Injector, private nativePageTransitions: NativePageTransitions, private alertCtrl: AlertController, private oneSignal: OneSignal, public http: HTTP, public statusBar: StatusBar, public apiservice: ApiService, public referenceservice: ReferenceService, public popoverCtrl: PopoverController, ) {
    this.getLanguages();
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id");
    this.url = this.apiservice.colorCode();
    this.loading = this.referenceservice.loading();
    if (localStorage.getItem('keywords')) {
      this.keywords = JSON.parse(localStorage.getItem('keywords'));
    }
    //to get the color code
    events.subscribe('user:created', (user, time) => {
      // user and time are the same arguments passed in `events.publish(user, time)`
     //console.log('Welcome', user, 'at', time);
    });
    this.http
      .post(this.url, {}, {})
      .then(data => {
        var resp = JSON.parse(data.data);
       //console.log(resp);
        this.primaryColor = resp.data.primary_color;
        this.secondryColor = resp.data.secondry_color;
        this.fullWhiteLogo = resp.data.black_logo;
        localStorage.setItem("primary_color", this.primaryColor);
        localStorage.setItem("secondry_color", this.secondryColor);
        localStorage.setItem("black_logo", resp.data.black_logo);
        localStorage.setItem("full_white_logo", resp.data.full_white_logo);
        localStorage.setItem("white_logo", resp.data.white_logo);
        this.statusBar.overlaysWebView(false);
        this.statusBar.backgroundColorByHexString(this.primaryColor);
       //console.log(this.colorCode);
        // this.loading.dismiss();
      })
      .catch(error => {
        // this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
        ////console.log("error=" + error);
        ////console.log("error=" + error.error);
        ////console.log("error=" + error.headers);
      });
    this.primaryColor = localStorage.getItem("primary_color");
    this.secondryColor = localStorage.getItem("secondry_color");
    // one signal push notification

    // this.oneSignal.setSubscription(true);
    // this.oneSignal.startInit(
    //   "4b3604d1-e319-4b3e-8f00-d9e0997320b0",
    //   "669279692499"
    // );
    // this.oneSignal.inFocusDisplaying(
    //   this.oneSignal.OSInFocusDisplayOption.None
    // );
    // this.oneSignal.handleNotificationReceived().subscribe(data => {
    //  //console.log(data);
    //   if (data.payload.additionalData.call_type == "video") {
    //     this.navCtrl.setRoot(IncomingCallPage, {
    //       videonotification: data.payload.additionalData
    //     });
    //   } else if (data.payload.additionalData.call_type == "audio") {
    //     this.navCtrl.setRoot(IncomingCallPage, {
    //       videonotification: data.payload.additionalData
    //     });
    //   } else if (data.payload.additionalData.call_type == "text") {
    //     this.oneSignal.inFocusDisplaying(
    //       this.oneSignal.OSInFocusDisplayOption.InAppAlert
    //     );
    //   }
    // });
    // this.oneSignal.handleNotificationOpened().subscribe(data => {
    //  //console.log(data);
    //   if (data.notification.payload.additionalData.call_type == "video") {
    //     if (data.action.actionID == "answer") {
    //       this.navCtrl.setRoot(VideoCallPage, {
    //         notification: data.notification.payload.additionalData
    //       });
    //     } else if (data.action.actionID == "decline") {
    //       //  this.call = true;
    //     }
    //   } else if (
    //     data.notification.payload.additionalData.call_type == "audio"
    //   ) {
    //     if (data.action.actionID == "answer") {
    //       this.navCtrl.setRoot(VoiceCallPage, {
    //         notification: data.notification.payload.additionalData
    //       });
    //     } else if (data.action.actionID == "decline") {
    //       this.call = true;
    //     }
    //   } else if (data.notification.payload.additionalData.call_type == "text") {
    //     if (data.action.actionID == "answer") {
    //       this.navCtrl.setRoot(VoiceCallPage, {
    //         notification: data.notification.payload.additionalData
    //       });
    //     } else {
    //     }
    //   }
    // });
    // this.oneSignal.endInit();
    // this.oneSignal.getIds().then(token => {
    //  //console.log(token);
    // });

    this.color = localStorage.getItem("colorCode");
  }

  ionViewDidLoad() {
    this.applyClassBySelection("zoomIn");
  }


  ionViewWillEnter() {
    if (localStorage.getItem('keywords')) {
      this.keywords = JSON.parse(localStorage.getItem('keywords'));
    }
    this.getLanguages();
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiservice.dashboardCount();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    this.http
      .post(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.count = this.resp.data;
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error.status);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
    if (this.call) {
      this.decline();
    }
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
        localStorage.setItem("keywords",JSON.stringify(resp.data[0]));
        console.log(resp);

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

  getstyle() {
    return {
      background:
        "linear-gradient(" + this.primaryColor + "," + this.secondryColor + ")"
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
  getFontstyle() {
    return { color: this.secondryColor };
  }

  openInvoices() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0
      // fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(InvoiceListPage);
  }
  openEstimates() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0
      // fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(EstimatesListPage);
  }

  openClient() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0
      // fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(ClientsPage);
  }
  openEmployee() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      // slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0
      // fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(EmployeelistPage);
  }

  openTask() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0,
      fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(SelectProjectPage);
  }
  openProject() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0,
      fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(ProjectlistPage);
  }

  decline() {
    this.url = this.apiservice.declineCall();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    this.http
      .post(this.url, {}, token)
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
  openPayroll() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0
      // fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(EmployeesalaryPage);
  }
  openAttedance() {
    let options: NativeTransitionOptions = {
      direction: "right",
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0
      // fixedPixelsBottom: 60
    };
    this.nativePageTransitions.flip(options);
    this.navCtrl.push(AttendancePage);
  }

  presentPopover(myEvent) {
    let popover = this.popoverCtrl.create(HomePopoverPage);
    popover.present({
      ev: myEvent
    });
    popover.onDidDismiss(data => {
      if (data == "profile") {
        var data1 = localStorage.getItem("role_id");
        if (data1 == "2") {
          this.navCtrl.push(ClientProfilePage);
        } else if (data1 == "3") {
          this.navCtrl.push(ProfilePage);
        }
      } else if (data == "setting") {
        this.navCtrl.push(SettingsPage);
      } else if (data == "logout") {
        let alert = this.referenceservice.confirmAlert(
          "Confirm",
          "Are you sure want to logout?"
        );
        alert.present();
        alert.onDidDismiss(data => {
          if (data) {
            localStorage.clear();
           //console.log(this.color);
            localStorage.setItem("colorCode", this.color);
            this.navCtrl.setRoot(LoginPage);
          }
        });
      }
    });
  }

  logout() {
    let alert = this.referenceservice.confirmAlert(
      "Confirm",
      "Are you sure want to logout?"
    );
    alert.present();
    alert.onDidDismiss(data => {
      if (data) {
        localStorage.clear();
       //console.log(this.color);
        localStorage.setItem("colorCode", this.color);
        this.navCtrl.setRoot(LoginPage);
      }
    });
  }
  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }

  openNotification() {
   //console.log("notification page");
    this.navCtrl.push(NotificationPage);
  }
}

@Component({
  template: `
    <ion-list class="popover-list">
      <button ion-item (click)="openMyprofile()">My Profile</button>
      <button ion-item (click)="openSettings()">Settings</button>
      <button ion-item (click)="logout()">Logout</button>
    </ion-list>
  `
})
export class HomePopoverPage {
  public employee;
  public roleId: any;
  public role: any;

  constructor(
    public viewCtrl: ViewController,
    public navParams: NavParams,
    public navCtrl: NavController
  ) {
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id");
  }

  openMyprofile() {
    var action = "profile";
    this.viewCtrl.dismiss(action);
  }

  openSettings() {
    var action = "setting";
    this.viewCtrl.dismiss(action);
  }

  logout() {
    var action = "logout";
    this.viewCtrl.dismiss(action);
  }
}
