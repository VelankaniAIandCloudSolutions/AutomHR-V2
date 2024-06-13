import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, Content } from 'ionic-angular';
import { empty } from 'rxjs/Observer';
import { HTTP } from '@ionic-native/http';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';

/**
 * Generated class for the NotificationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

declare var OT: any;
declare var Cordova: any;

@Component({
  selector: 'page-chat',
  templateUrl: 'chat.html',
})
export class ChatPage {
  @ViewChild(Content) content: Content;
  editorMsg = '';
  session: any;
  publisher: any;
  apiKey: any;
  sessionId: string;
  token: string;
  public mute = false;
  chatDetails: any;
  public primaryColor: any;
  tousername: any;
  message: any;
  text: string;
  noification: any = {};
  from_id: any;
  to_id: any;
  my_id: any;
  url: any;
  loading: any;
  resp: any;
  group_id: any;
  messages: any;
  chat_type: any;
  nodata = false;
  public keywords:any={};

  constructor(public navCtrl: NavController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.my_id = localStorage.getItem('user_id');
    this.chatDetails = this.navParams.get('chatdata');
    this.chat_type = this.navParams.get('type');
    console.log(this.chat_type);
   console.log(this.chatDetails);
    if (this.chatDetails) {

      if (this.chat_type == 'one') {
        this.tousername = this.chatDetails.to_user_details.user_name;
        this.apiKey = "46235992";
        this.sessionId = this.chatDetails.sessionId;
        this.token = this.chatDetails.from_token;
        this.to_id = this.navParams.get('to_id');
      }
      else if (this.chat_type == 'group') {
        this.apiKey = "46235992";
        this.group_id = this.navParams.get('group_id');
        if (this.chatDetails.sessionId) {
          this.sessionId = this.chatDetails.sessionId;
        }
        else {
          this.sessionId = this.navParams.get('session_id');
        }
        if (this.chatDetails.from_token) {
          this.token = this.chatDetails.from_token;
        }
        else {
          this.token = this.navParams.get('token');
        }
        this.tousername = this.navParams.get('group_name');
        this.to_id = this.navParams.get('to_id').join();
      }
    }

    this.noification = this.navParams.get('notification')
   //console.log(this.noification)
    if (this.noification) {
     //console.log(this.noification.group_id);
      this.tousername = this.noification.name;
      this.apiKey = "46235992";
      this.sessionId = this.noification.session_id;
      this.token = this.noification.to_token;
      this.to_id = this.noification.from_id
      if (this.noification.group_id) {
        this.group_id = this.noification.group_id;
        this.chat_type = 'group';
      }
      else {
        this.chat_type = 'one';
      }
    }
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {
    this.loadMsg();
    this.session = OT.initSession(this.apiKey, this.sessionId);
    if (this.session.isConnected(this.token) == false) {
      this.session.connect(this.token, (error) => {
        // If the connection is successful, initialize a publisher and publish to the session
        if (!error) {
          if (this.message != undefined) {
            this.sendTxt();
          }
          this.session.on('signal', (event) => {
           //console.log('came');
            this.loadMsg();
          });
         //console.log('from ' + event);
         //console.log('to ' + this.session.connection.connectionId);
        }
      });
    }
  }

  loadMsg() {
    this.url = this.apiService.getMessages();
    var token = localStorage.getItem('token')
    var token1 = { 'token': token };
    var data;
    if (this.chat_type == 'group') {
      data = {
        call_type: this.chat_type,
        group_id: this.group_id
      }
    }
    else if (this.chat_type == 'one') {
      data = {
        call_type: this.chat_type,
        user_id: this.to_id
      }
    }
   //console.log(data)
    this.http.post(this.url, data, token1)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          localStorage.clear();
        }
        if (this.resp.message == "Success") {
          var msgs = this.resp.data.chat_messages;
          if (msgs.length == 0) {
            this.nodata = true;
          }
          else {
            this.nodata = false;
          }
          this.messages = msgs;
          setTimeout(() => { this.content.scrollToBottom(300); }, 200);
         //console.log(this.messages);

        }
      })
      .catch(error => {
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  sendTxt() {
    this.url = this.apiService.sendText();
    this.token = localStorage.getItem('token')
    var token = { 'token': this.token };
    var data;
    if (this.chat_type == 'one') {
      data = {
        msg_type: 'one',
        user_msg: this.message,
        from_connectionid: this.session.connection.connectionId,
        to_user: this.to_id,
      };
    }
    else {
      data = {
        msg_type: 'group',
        user_msg: this.message,
        from_connectionid: this.session.connection.connectionId,
        to_user: this.to_id,
        group_id: this.group_id
      };
    }
   //console.log(data)
    this.http.post(this.url, data, token)
      .then(data => {
        if (data.data == "success") {
          // this.nodata = false;
          this.loadMsg();
          this.session.signal({
            type: 'msg',
            data: this.message
          }, (error) => {
            if (error) {
              console.error('Error sending signal:', error.name, error.message);
            } else {
             //console.log(data.data)
              this.message = '';
            }
          });
        }
        else {
          var data1 = JSON.parse(data.data);
          if (data1.message == "Success") {
            this.loadMsg();
            this.session.signal({
              type: 'msg',
              data: this.message
            }, (error) => {
              if (error) {
                console.error('Error sending signal:', error.name, error.message);
              } else {
                this.loadMsg();
               //console.log(data.data)
                this.message = '';
              }
            });
          }
        }
      })
      .catch(error => {
        this.referenceService.basicAlert("New HRMS", 'Cannot connect to the server at the moment try sending later');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  sendMsg() {
    this.message = this.text;
    this.text = '';
    this.sendTxt();
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
}


