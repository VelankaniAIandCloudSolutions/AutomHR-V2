import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { VoiceCallPage } from '../voicecall/voicecall';
import { VideoCallPage } from '../videocall/videocall';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';
import { HomePage } from '../home/home';
import { NativeAudio } from '@ionic-native/native-audio';
import { Media, MediaObject } from '@ionic-native/media';

/**
 * Generated class for the NotificationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-incomingcall',
  templateUrl: 'incomingcall.html',
})
export class IncomingCallPage {

  public primaryColor: any;
  public loading;
  public url;
  public token;
  public resp;
  public page;
  public contact;
  call = false;
  groupVideo: any;
  file: any;
  type: any;
  fullname: any;
  notificationData: any;
  constructor(public navCtrl: NavController, private media: Media, private nativeAudio: NativeAudio, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.fullname = localStorage.getItem('fullname');
    this.contact = this.navParams.get('contact');
    this.groupVideo = this.navParams.get('group');
    this.type = this.navParams.get('type');
    if (this.contact) {
      this.call = true;
      this.makeCall();
    }
    if (this.groupVideo) {
      this.call = true;
      this.makeGroupVideoCall();
    }
    this.notificationData = this.navParams.get('videonotification');
   //console.log(this.notificationData);
    if (this.notificationData) {
      this.call = false;
    }
    // this.file = this.media.create('assets/music/progress_tone.wav');
    // this.file.onSuccess.subscribe(() =>//console.log('Action is successful'));
    // this.file.onError.subscribe(error =>//console.log('Error!', error));
    // this.file.play();

  }

  ionViewDidLoad() {
    if(!this.call){
      this.nativeAudio.preloadComplex('uniqueId2', 'assets/music/phonering.mp3', 1, 1, 0).then(onSuccess => {
        this.nativeAudio.loop('uniqueId2').then(onSuccess => {
         //console.log(onSuccess);
        });
       //console.log(this.nativeAudio);
      });
    }
    // this.makeCall();
  }

  makeCall() {
    // this.loading = this.referenceService.loading();
    // this.loading.present();
    this.url = this.apiService.makecall();
    this.token = localStorage.getItem('token')
    
    var token = { 'token': this.token };
   //console.log(this.token);
    var data = {
      user_id: this.contact.user_id,
      call_type: this.type
    }
    this.http.post(this.url, data, token)
      .then(data => {
       //console.log(JSON.parse(data.data));
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          var data1 = this.resp.data;
          console.log(data1)
          if (this.type == 'video') {
            // this.nativeAudio.stop('uniqueId2');
            // this.nativeAudio.unload('uniqueId2');
            this.navCtrl.push(VideoCallPage, {
              videodata: data1
            })
          }
          if (this.type == 'voice') {
            // this.nativeAudio.stop('uniqueId2');
            // this.nativeAudio.unload('uniqueId2');
            this.navCtrl.push(VoiceCallPage, {
              voicedata: data1
            })
          }
          // this.loading.dismiss();
        }
      })
      .catch(error => {
        // this.nativeAudio.stop('uniqueId2');
        // this.nativeAudio.unload('uniqueId2');
        // this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Cannot make the call at the moment');
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error=" + JSON.stringify(error));
       //console.log("error=" + error.headers);
      });
  };

  makeGroupVideoCall() {
    this.url = this.apiService.makeGroupCall();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = {
      user_ids: this.groupVideo.join(),
      call_type: 'video',
      group_name: "test_group"
    }
   //console.log(data);
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          var data1 = this.resp.data;
          console.log(data1)
          this.nativeAudio.stop('uniqueId2');
          this.nativeAudio.unload('uniqueId2');
          this.navCtrl.push(VideoCallPage, {
            videodata: data1
          })
          // this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Cannot make the call at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  answerCall() {
    this.nativeAudio.stop('uniqueId2');
    this.nativeAudio.unload('uniqueId2');
    this.navCtrl.push(VideoCallPage, {
      notification: this.notificationData
    });
  }
  declineCall() {
    this.url = this.apiService.declineCall();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    this.http.post(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Success") {
         //console.log(this.resp);
          this.nativeAudio.stop('uniqueId2');
          this.nativeAudio.unload('uniqueId2');
          this.navCtrl.setRoot(HomePage);
        }
      })
      .catch(error => {
        // this.navCtrl.setRoot(HomePage);
      });

  }
}
