import { Component } from "@angular/core";
import { IonicPage, NavController, NavParams } from "ionic-angular";
import { ChatPage } from "../chat/chat";
import { ContactPage } from "../contact/contact";
import { HTTP } from "@ionic-native/http";
import { ReferenceService } from "../../providers/referenceService";
import { ApiService } from "../../providers/apiServices";
import { LoginPage } from "../login/login";

/**
 * Generated class for the NotificationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-chat-list",
  templateUrl: "chat-list.html"
})
export class ChatListPage {
  editorMsg = "";
  url: any;
  token: any;
  resp: any;
  loading: any;
  public mute = false;
  public primaryColor: any;
  last_message: any;
  user: any = {};
  group = false;
  groupnames: any;
  grouptoken: any;
  public keywords :any={};
  groupsession: any;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public http: HTTP,
    public referenceService: ReferenceService,
    public apiService: ApiService
  ) {
    this.primaryColor = localStorage.getItem("primary_color");
    this.user.users = [];
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    this.oneToOne();
  }
  removeDuplicates(myArr, prop) {
    return myArr.filter((obj, pos, arr) => {
      return arr.map(mapObj => mapObj[prop]).indexOf(obj[prop]) === pos;
    });
  }
  openChat(user) {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.initChat();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    var data = {
      to_user: user.user_id,
      chat_type: "one"
    };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          var data1 = this.resp.data;
          this.navCtrl.push(ChatPage, {
            chatdata: data1,
            to_id: user.user_id,
            type: "one"
          });
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert(
          "New HRMS",
          "Cannot make the call at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  openContactList() {
    this.navCtrl.push(ContactPage, {
      type: "chat"
    });
  }

  openGroup() {
    this.group = !this.group;
    if (this.group) {
      this.groupChat();
    }
    if (!this.group) {
      this.oneToOne();
    }
  }

  groupChat() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getChatList();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    var data = {
      call_type: "group"
    };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
       //console.log(data);
        this.resp = JSON.parse(data.data);

       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          var data1 = this.resp.data;
          // data1.all_users.forEach((element, key) => {
          //   if (element.user_id == data1.all_chats[key].user_id) {
          //     element.message = data1.all_chats[key].last_message;
          //     element.msg_time = data1.all_chats[key].msg_time;
          //   }
          // });
          this.grouptoken = data1.token;
          this.groupnames = data1.group_names;
          this.groupsession = data1.sessionId;
         //console.log(this.groupnames);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert(
          "New HRMS",
          "Cannot make the call at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  oneToOne() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getChatList();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    var data = {
      call_type: "one"
    };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          var data1 = this.resp.data;
          data1.all_users.forEach((element, key) => {
            if (element.user_id == data1.all_chats[key].user_id) {
              element.message = data1.all_chats[key].last_message;
              element.msg_time = data1.all_chats[key].msg_time;
            }
          });
          this.last_message = data1.all_users;
         //console.log(data1);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert(
          "New HRMS",
          "Cannot make the call at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  doRefresh(refresher) {
    this.url = this.apiService.getChatList();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    var data = {
      call_type: "one"
    };
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          var data1 = this.resp.data;
          data1.all_users.forEach((element, key) => {
            if (element.user_id == data1.all_chats[key].user_id) {
              element.message = data1.all_chats[key].last_message;
              element.msg_time = data1.all_chats[key].msg_time;
            }
          });
          this.last_message = data1.all_users;
         //console.log(data1);
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceService.basicAlert(
          "New HRMS",
          "Cannot make the call at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }
  getHeaderStyle() {
    return { background: this.primaryColor };
  }
  opengroupChat(message1) {
    this.navCtrl.push(ChatPage, {
      chatdata: message1,
      // to_id: this.userIds,
      type: "group",
      session_id: this.groupsession,
      group_id: message1.group_id,
      token: this.grouptoken,
      to_id: message1.user_ids,
      group_name: message1.group_name
    });
  }
  setDefaultPic(message) {
    message.profile_image = "assets/imgs/user.jpg";
  }
}
