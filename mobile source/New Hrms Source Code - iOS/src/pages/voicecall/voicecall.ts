import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ContactPage } from '../contact/contact';
import { HomePage } from '../home/home';
import { ChatPage } from '../chat/chat';

declare var OT: any;
declare var Cordova: any;
/**
 * Generated class for the NotificationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-voicecall',
  templateUrl: 'voicecall.html',
})
export class VoiceCallPage {
  session: any;
  publisher: any;
  apiKey: any;
  sessionId: string;
  token: string;
  public video = false;
  public audio = true;
  public mute = false;
  public primaryColor: any;
  public subscriber: any;
  public event: any;
  public subscribed = false;
  public subscriber_id;
  public videodata;
  public notificationData: any;
  callDetails: any = {};
  devices: any[];
  videoInput: any = [];
  public user = 1;
  public session1;
  cam = 0;
  streams = [];
  fullname:any;
  pub = false;
  public keywords:any={};
  constructor(public navCtrl: NavController, public navParams: NavParams) {
    this.primaryColor =  localStorage.getItem('primary_color')
    this.videodata = this.navParams.get('voicedata');
    if (this.videodata) {
      this.apiKey = '46235992';
      this.sessionId = this.videodata.session_id;
      this.token = this.videodata.from_token;
    }
    this.notificationData = this.navParams.get('notification');
    if (this.notificationData) {
      this.apiKey = '46235992';
      this.sessionId = this.notificationData.session_id;
      this.token = this.notificationData.to_token;
    }
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
    // this.apiKey = "46210962";
    // this.sessionId = "1_MX40NjIxMDk2Mn5-MTU0MDQ5NTI0MjgxNX41cUdIQjI3K0tFSzRVQ1VJcHVnZzlTa1N-fg";
    // this.token = "T1==cGFydG5lcl9pZD00NjIxMDk2MiZzaWc9NTU0ZjI3MmM4ZmViNjE2NDM2NjNlNWNlMDc2YjNkNDg3NDdlN2MwODpzZXNzaW9uX2lkPTFfTVg0ME5qSXhNRGsyTW41LU1UVTBNRFE1TlRJME1qZ3hOWDQxY1VkSVFqSTNLMHRGU3pSVlExVkpjSFZuWnpsVGExTi1mZyZjcmVhdGVfdGltZT0xNTQwNDk1MjYwJm5vbmNlPTAuODkzNTU1MDQwMTExMTU4OSZyb2xlPXB1Ymxpc2hlciZleHBpcmVfdGltZT0xNTQzMDkwODU5JmluaXRpYWxfbGF5b3V0X2NsYXNzX2xpc3Q9";  
  }
  ionViewDidLoad() {
    
   //console.log(this.videoInput)
    this.session = OT.initSession(this.apiKey, this.sessionId);
    this.session1 = OT.initSession(this.apiKey, this.sessionId);
    this.session.on({
      streamCreated: (event) => {
        this.event = event;
        this.streams.push(event.stream.streamId);
        for (var i = 0; i < event.streams.length; i++) {
          // Make sure we don’t subscribe to ourself
         //console.log(event.streams[i]);
          this.streams.push(event.streams[i]);
          if (event.streams[i].connection.connectionId == this.session.connection.connectionId) {
           //console.log(event.streams[i]);
            this.streams.push(event.stream.streamId);
            return;
          }
          var options = { width: '100%', height: '100%', insertMode: "append", showControls: false }
          var subs = 'stream_' + event.streams[i].streamId;
          var div = document.createElement('div');
          div.setAttribute('id', subs);
          document.getElementById("userimage").appendChild(div);
          this.session.subscribe(event.streams[i], subs, options);
        }

       //console.log(`Stream ${event.stream.name} ended because ${event.reason}`);
      },
      streamDestroyed: (event) => {
       //console.log(`Stream ${event.stream.name} ended because ${event.reason}`);
        // OT.updateViews();
      }
    });
    this.session.connect(this.token, () => {
      var publisherOptions = {
        width: 100,
        insertMode: "append",
        height: 100,
        publishAudio:true,
        publishVideo:false,
        showControls: false
      };
      this.publisher = OT.initPublisher('publisher', publisherOptions);
      this.session.publish(this.publisher);
      this.pub=true;
    });
  };


  on(event) {
   //console.log(this.streams);
    var stream;
    var id = event.path[3].id.split('_');
    var connectionId = id[1];
   //console.log(connectionId);
    for (var i = 0; i < this.streams.length; i++) {
      // Make sure we don’t subscribe to ourself
     //console.log(this.streams[i].streamId)
     //console.log(connectionId)
      if (this.streams[i].streamId == connectionId) {
        stream = this.streams[i];
       //console.log(stream)
      }
    }
    if (stream) {
      document.getElementById('subscribe').innerHTML = '';
      var options = { width: '100%', height: '100%', insertMode: "append", showControls: false }
      var subscriber = this.session.subscribe(stream, 'subscribe', options);
      document.getElementById("subscribe").setAttribute('data-id', event.path[3].id)
    }
  }
  userMute() {
    this.mute = !this.mute;
   //console.log(this.mute);
  }
  addPeople() {
    this.navCtrl.push(ContactPage, {
      call: this.callDetails
    })
  }
  unMuteAudio() {
    this.audio = true;
    this.publisher.publishAudio(true);
  }
  unMuteVideo() {
    this.video = true;
    this.publisher.publishVideo(true);
  }
  swapCam() {
   //console.log(this.videoInput)
   //console.log(this.videoInput[this.cam])
    this.session.unpublish(this.publisher);
    this.pub=false;
    setTimeout(() => {
      if (this.cam == 0) {
        this.cam = 1;
        var publisherOptions = {
          width: 100,
          insertMode: "append",
          height: 100,
          videoSource: this.videoInput[this.cam].deviceId,
          showControls: false
        };
        this.publisher = OT.initPublisher('publisher', publisherOptions);
        this.session.publish(this.publisher);
      }
      else {
        this.cam = 0;
        var publisherOptions = {
          width: 100,
          insertMode: "append",
          height: 100,
          videoSource: this.videoInput[this.cam].deviceId,
          showControls: false
        };
        this.publisher = OT.initPublisher('publisher', publisherOptions);
        this.session.publish(this.publisher);
        this.pub=true;
      }
    }, 3000);

    ////console.log(input)

    // this.swap();
  };
  muteVideo() {
    this.video = false;
    this.publisher.publishVideo(false);
  }
  muteAudio() {
    this.audio = false;
    this.publisher.publishAudio(false);
  }
  endCall() {
    this.session.disconnect();
    // (this.publisher);
    if (this.subscribed) {
      this.session.unsubscribe(this.subscriber)
    }
    else {
      this.session.publish(this.publisher);
    }
    this.navCtrl.setRoot(HomePage);
  };

  videoback() {
    // this.publisher.unpu();
    this.navCtrl.push(ChatPage, {
      session: this.session,
      details: this.callDetails
    });
  }


}
