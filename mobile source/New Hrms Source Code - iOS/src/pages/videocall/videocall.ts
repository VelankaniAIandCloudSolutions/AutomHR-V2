import { Component, ElementRef } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { HomePage } from '../home/home';
import { ChatPage } from '../chat/chat';
import { ContactPage } from '../contact/contact';
import { Camera } from '@ionic-native/camera';
import { AndroidPermissions } from '@ionic-native/android-permissions';


declare var OT: any;
declare var Cordova: any;
/**
 * Generated class for the NotificationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-videocall',
  templateUrl: 'videocall.html',
})
export class VideoCallPage {

  session: any;
  publisher: any;
  apiKey: any;
  sessionId: string;
  token: string;
  public video = true;
  public audio = true;
  public mute = false;
  public primaryColor: any;
  public subscriber: any;
  public event: any;
  public subscribed = false;
  public subscriber_id;
  public videodata;
  callDetails: any = {};
  public notificationData: any;
  cameraSource = 0;
  subscribers = [];
  public htmlToAdd: any;
  devices: any[];
  videoInput: any = [];
  public user = 1;
  public session1;
  cam = 0;
  call=false;
  streams = [];
  fullname:any;
  pub = false;
  timeoutId;
  seconds = 0;
  minutes = 0;
  hours = 0;
  running = false;
  public keywords:any={};
  // call = true;
  // subscriber_id:any;

  constructor(public navCtrl: NavController, private androidPermissions: AndroidPermissions, private elementRef: ElementRef, public navParams: NavParams, private camera: Camera) {
    this.primaryColor =  localStorage.getItem('primary_color')
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
    this.fullname = localStorage.getItem('fullname');
    var inputDevices = [];
    var videoInputDevices;
    OT.getDevices(function (error, devices) {
     //console.log(inputDevices)
      ////console.log(devices)
      devices.forEach(element => {
        ////console.log(element);
        if (element.kind == "videoInput") {
          inputDevices.push(element);
        }

      });
    });
    this.videoInput = inputDevices;
    this.videodata = this.navParams.get('videodata');
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
    this.callDetails.apiKey = this.apiKey;
    this.callDetails.sessionId = this.sessionId;
    this.callDetails.token = this.token;
    // this.apiKey = "46210962";
    // this.sessionId = "1_MX40NjIxMDk2Mn5-MTU0MDQ5NTI0MjgxNX41cUdIQjI3K0tFSzRVQ1VJcHVnZzlTa1N-fg";
    // this.token = "T1==cGFydG5lcl9pZD00NjIxMDk2MiZzaWc9NTU0ZjI3MmM4ZmViNjE2NDM2NjNlNWNlMDc2YjNkNDg3NDdlN2MwODpzZXNzaW9uX2lkPTFfTVg0ME5qSXhNRGsyTW41LU1UVTBNRFE1TlRJME1qZ3hOWDQxY1VkSVFqSTNLMHRGU3pSVlExVkpjSFZuWnpsVGExTi1mZyZjcmVhdGVfdGltZT0xNTQwNDk1MjYwJm5vbmNlPTAuODkzNTU1MDQwMTExMTU4OSZyb2xlPXB1Ymxpc2hlciZleHBpcmVfdGltZT0xNTQzMDkwODU5JmluaXRpYWxfbGF5b3V0X2NsYXNzX2xpc3Q9";
  }

  ionViewDidLoad() {
    this.start();
   //console.log(this.videoInput)
    this.session = OT.initSession(this.apiKey, this.sessionId);
    this.session1 = OT.initSession(this.apiKey, this.sessionId);
    this.session.on({
      streamCreated: (event) => {
        this.call = true;
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

          document.getElementById('subscribe').innerHTML = '';
          
          var options = { width: '100%', height: '100%', insertMode: "append", showControls: false }
          var subscriber = this.session.subscribe(event.streams[i], 'subscribe', options);
          document.getElementById("subscribe").setAttribute('data-id', event.streams[i]);
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
        videoSource: this.videoInput[this.cam].deviceId,
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
      document.getElementById("subscribe").setAttribute('data-id', event.path[3].id);
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
  

  stop() {
    clearTimeout(this.timeoutId);
    this.running = false;
  };
  start() {
    this.timer();
    this.running = true;
  };
  clear() {
    this.seconds = 0;
    this.minutes = 0;
  };
  timer() {
    this.timeoutId = setTimeout(() => {
      this.updateTime(); // update Model
      this.timer();
    }, 1000);
  }
  updateTime() {
    this.seconds++;
    if (this.seconds === 60) {
      this.seconds = 0;
      this.minutes++;
    }
    if (this.minutes === 60) {
      this.minutes = 0;
      this.seconds = 0;
      this.hours++;
    }
  }

}
