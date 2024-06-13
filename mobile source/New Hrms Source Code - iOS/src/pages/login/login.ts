import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  LoadingController,
  AlertController,
  MenuController,
  Events
} from "ionic-angular";
import { HomePage } from "../home/home";
import {
  FormGroup,
  FormBuilder,
  Validators,
  FormControl
} from "@angular/forms";
import { ForgotpasswordPage } from "../forgotpassword/forgotpassword";
import { HTTP } from "@ionic-native/http";
import { ApiService } from "../../providers/apiServices";
import { ReferenceService } from "../../providers/referenceService";
import { StatusBar } from "@ionic-native/status-bar";
import { OneSignal } from "@ionic-native/onesignal";
/**
 * Generated class for the LoginPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-login",
  templateUrl: "login.html"
})
export class LoginPage {
  url: any;
  host: any;
  loading: any;
  public username1 = false;
  public password1 = false;
  private loginForm: FormGroup;
  public primaryColor: any;
  public secondryColor: any;
  
  public keywords:any ={};
  blackLogo: any;
  device_id: any;
  constructor(
    public navCtrl: NavController,
    public menuCtrl: MenuController,
    private oneSignal: OneSignal,
    public events: Events,
    private fb: FormBuilder,
    public statusBar: StatusBar,
    private referenceservice: ReferenceService,
    private apiservice: ApiService,
    public navParams: NavParams,
    private http: HTTP,
    private loadingCtrl: LoadingController
  ) {
    this.getLanguages();
    if (localStorage.getItem('keywords')) {
      this.keywords = JSON.parse(localStorage.getItem('keywords'));
    }
    this.loginForm = fb.group({
      username: ["", [Validators.required, this.nameValidator.bind(this)]],
      password: ["", [Validators.required, this.passwordValidator.bind(this)]],
      device_id: ["", []]
    });
    this.url = this.apiservice.colorCode();
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.http
      .post(this.url, {}, {})
      .then(data => {
        var resp = JSON.parse(data.data);
        console.log(resp);
        this.primaryColor = resp.data.primary_color;
        this.secondryColor = resp.data.secondry_color;
        this.blackLogo = resp.data.black_logo;
        localStorage.setItem("primary_color", this.primaryColor);
        localStorage.setItem("secondry_color", this.secondryColor);
        localStorage.setItem("black_logo", resp.data.black_logo);
        localStorage.setItem("full_white_logo", resp.data.full_white_logo);
        localStorage.setItem("white_logo", resp.data.white_logo);
        this.statusBar.overlaysWebView(false);
        this.statusBar.backgroundColorByHexString(this.primaryColor);
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Please Check your internet and try again later"
        );
      });
    this.primaryColor = localStorage.getItem("primary_color");
    this.blackLogo = localStorage.getItem("black_logo");
    this.menuCtrl.swipeEnable(false);
  }
  ionViewWillEnter() {
    this.url = this.apiservice.login();
    this.host = this.apiservice.host;
    this.oneSignal.setSubscription(true);
    this.oneSignal.startInit(
      "4b3604d1-e319-4b3e-8f00-d9e0997320b0",
      "669279692499"
    );
    this.oneSignal.getIds().then(token => {
      this.device_id = token.userId;
      this.loginForm.controls["device_id"].setValue(this.device_id);
      //console.log(token);
    });
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }
  getLanguages() {
    var url = this.apiservice.getLanguage();
    var token = { token: "DQCTPQMKK9R6SXN4" };
    var language ;
    if (localStorage.getItem('language')){
      language = localStorage.getItem('language');
    }
    else{
      language = "en";
    }
    var data = {code:language};
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
        this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
      });
  }
  login() {
    if (
      this.loginForm.get("username").valid &&
      this.loginForm.get("password").valid
    ) {
      this.loading = this.referenceservice.loading();
      this.loading.present();
      var token = { token: "DQCTPQMKK9R6SXN4" };
      var data = this.loginForm.value;
      console.log(this.url,data);
      this.http
        .post(this.url, data, token)
        .then(data => {
          //console.log(data);
          var resp = JSON.parse(data.data);
          localStorage.setItem("currency", resp.data.currency);
          console.log(resp);
          if (resp.status_code == 1) {
            if (resp.data.role_id == "4") {
              localStorage.setItem("loginStatus", "true");
              localStorage.setItem("menus", JSON.stringify(resp.data.menus));
              localStorage.setItem("role", "admin");
              localStorage.setItem("role_id", "1");
              localStorage.setItem("fullname", resp.data.fullname);
              localStorage.setItem("user_id", resp.data.id);
              localStorage.setItem("currency", resp.data.currency);
            }
            if (resp.data.role_id == "1") {
              localStorage.setItem("loginStatus", "true");
              localStorage.setItem("role", "admin");
              localStorage.setItem("role_id", resp.data.role_id);
              localStorage.setItem("fullname", resp.data.fullname);
              localStorage.setItem("user_id", resp.data.id);
              localStorage.setItem("currency", resp.data.currency);

            } else if (resp.data.role_id == "3") {
              localStorage.setItem("loginStatus", "true");
              localStorage.setItem("role", "user");
              localStorage.setItem("role_id", resp.data.role_id);
              localStorage.setItem("fullname", resp.data.fullname);
              localStorage.setItem("user_id", resp.data.id);
              localStorage.setItem("currency", resp.data.currency);

            } else if (resp.data.role_id == "2") {
              localStorage.setItem("loginStatus", "true");
              localStorage.setItem("role", "client");
              localStorage.setItem("role_id", resp.data.role_id);
              localStorage.setItem("fullname", resp.data.fullname);
              localStorage.setItem("user_id", resp.data.id);
              localStorage.setItem("currency", resp.data.currency)
            }
            localStorage.setItem("token", resp.data.unique_code);
            this.loading.dismiss();
            this.events.subscribe('user:created', (user, time) => {
              // user and time are the same arguments passed in `events.publish(user, time)`
              //console.log('Welcome', user, 'at', time);
            });
            this.navCtrl.setRoot(HomePage);
          }
          //console.log(resp.data.unique_code);
          if (resp.status_code == -1) {
            this.loading.dismiss();
            this.referenceservice.basicAlert("Login Failed", resp.message);
          }
        })
        .catch(error => {
          this.loading.dismiss();
          this.referenceservice.basicAlert(
            "New HRMS",
            "Unable to reach server at the moment"
          );
        });
    } else {
      if (!this.loginForm.get("password").valid) {
        this.password1 = true;
      }
      if (!this.loginForm.get("username").valid) {
        this.username1 = true;
      }
    }
    // this.navCtrl.setRoot(HomePage);
  }

  isValid(field: string) {
    let formField = this.loginForm.get(field);
    return formField.valid || formField.pristine;
  }

  nameValidator(control: FormControl): { [s: string]: boolean } {
    if (control.value == "") {
      return { invalidName: true };
    } else {
      this.username1 = false;
    }
  }

  passwordValidator(control: FormControl): { [s: string]: boolean } {
    if (control.value == "") {
      // if (!control.value.match('^[a-zA-Z0-9!@#$%^&*_.+-]+$')) {
      return { invalidPhone: true };
    } else {
      this.password1 = false;
    }
    // }
  }

  openForgotPassword() {
    this.navCtrl.push(ForgotpasswordPage);
  }
}
