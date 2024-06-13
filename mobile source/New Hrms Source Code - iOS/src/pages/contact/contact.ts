import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, Content, ModalController, ViewController, PopoverController } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { AttendanceDetailsPage } from '../attendance-details/attendance-details';
import { LoginPage } from '../login/login';
import { VoiceCallPage } from '../voicecall/voicecall';
import { VideoCallPage } from '../videocall/videocall';
import { IncomingCallPage } from '../incomingcall/incomingcall';
import { ChatPage } from '../chat/chat';

/**
 * Generated class for the AttendanceListPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-contact',
  templateUrl: 'contact.html',
})
export class ContactPage {
  @ViewChild(Content) content: Content;
  public contacts;
  public groupedContacts = [];
  public list;
  public loading;
  public url;
  public token;
  public resp;
  public page;
  public searchText;
  group = false;
  public pageNumber;
  public noData;
  public calldetails;
  public selected = {};
  public searching = false;
  userIds = [];
  type: any;
  chat = false;
  public primaryColor: any;
  group_id: any;
  groupnames: any;
  groupName: any;
  my_id: any;
  public keywords :any ={};
  constructor(public navCtrl: NavController, public modalCtrl: ModalController, public navParams: NavParams, public http: HTTP, public referenceService: ReferenceService, public apiService: ApiService) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.my_id = localStorage.getItem('user_id');
    this.type = this.navParams.get('type')
    // this.userIds.push(this.my_id);
    if (this.type == 'chat') {
      this.chat = true;
     //console.log(this.type)
    }
    else {
      this.chat = false;
     //console.log(this.type)
    }
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {
    // this.page = false;

    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getUserList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.list = this.resp.data;
          this.list = this.sortByKey(this.list, 'fullname');
          this.list.forEach(element => {
            element.checked = false;
          });
          // this.groupContacts(this.list);
         //console.log(this.list);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };
  doRefresh(refresher) {
    this.url = this.apiService.getUserList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.list = this.resp.data;
          this.list = this.sortByKey(this.list, 'fullname');
          this.list.forEach(element => {
            element.checked = false;
          });
          // this.groupContacts(this.list);
         //console.log(this.list);
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });

  }
  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  openChat(user) {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.initChat();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = {
      to_user: user.user_id,
      chat_type: 'one'
    }
   //console.log(data);
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
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
            type: 'one'
          })
          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Cannot make the call at the moment');
       //console.log("error=" + JSON.stringify(error));
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error=" + error.headers);
      });
  }
  makecall() {
    this.list.forEach(element => {
      if (element.checked) {
        this.userIds.push(element.user_id)
       //console.log(this.userIds.join())
      }
    })
    this.navCtrl.push(IncomingCallPage, {
      group: this.userIds
    })
  };

  makeGroupChat() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.list.forEach(element => {
      if (element.checked) {
        this.userIds.push(element.user_id)
       //console.log(this.userIds.join())
      }
    });

    this.url = this.apiService.makeGroupCall();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    // this.userIds.push()
    var token = { 'token': this.token };
    var data = {
      user_ids: this.userIds.join(),
      call_type: 'chat',
      group_name: this.groupName
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
          this.group_id = data1.group_id;
          this.groupnames = data1.user_names;
         //console.log(data1);
          this.loading.dismiss();
          this.groupChat();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Cannot make the call at the moment');
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  groupChat() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.initChat();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = {
      to_user: this.userIds.join(),
      chat_type: 'group'
    }
   //console.log(data);
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          this.loading.dismiss();
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          var data1 = this.resp.data;
          this.loading.dismiss();
          this.navCtrl.push(ChatPage, {
            chatdata: data1,
            to_id: this.userIds,
            type: 'group',
            group_id: this.group_id,
            usernames: this.groupnames,
            group_name: this.groupName
          })
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Cannot make the call at the moment');
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  makegroupcall() {

  };
  cancelGroupCall() {
    this.group = false;
  };
  search() {
    this.searching = true;
  };
  clearSearch() {
    this.searching = false;
  };

  openDetails(contact) {
    this.navCtrl.push(AttendanceDetailsPage, {
      user: contact
    })
  };

  voiceCall(contact) {
    this.navCtrl.push(IncomingCallPage, {
      contact: contact,
      type: 'voice'
    })
  };
  videoCall(contact) {
    this.navCtrl.push(IncomingCallPage, {
      contact: contact,
      type: 'video'
    })
  };
  // doInfinite(infiniteScroll) {
  //   setTimeout(() => {
  //     if (this.resp.data.next_page != -1) {
  //      //console.log('Begin async operation');
  //       this.url = this.apiService.getEmployeeList();
  //       this.token = localStorage.getItem('token')
  //      //console.log(this.token);
  //       var token = { 'token': this.token };
  //       var data = { page: this.resp.data.next_page }
  //       this.http.post(this.url, data, token)
  //         .then(data => {
  //           this.resp = JSON.parse(data.data);
  //           infiniteScroll.complete();
  //           this.content.scrollToTop();
  //           for (var i = 0; i < this.resp.data.list.length; i++) {
  //             this.list.push(this.resp.data.list[i]);
  //           }
  //           this.list = this.sortByKey(this.list, 'fullname');
  //           this.groupedContacts = [];
  //           this.groupContacts(this.list);
  //          //console.log(this.resp);
  //           if (this.resp.data.next_page == -1) {
  //             this.page = true;
  //           }
  //           this.loading.dismiss();
  //         })
  //         .catch(error => {
  //           this.loading.dismiss();
  //          //console.log("error=" + error.status);
  //          //console.log("error=" + error.error); // error message as string
  //          //console.log("error=" + error.headers);
  //         });
  //     }
  //     else {
  //      //console.log('Async operation has ended');
  //       infiniteScroll.complete();
  //     }
  //   }, 1000);
  // };

  sortByKey(array, key) {
    return array.sort(function (a, b) {
      var x = a[key]; var y = b[key];
      return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
  };

  groupContacts(contacts) {
   //console.log(contacts)
    let sortedContacts = contacts;
    let currentLetter = false;
    let currentContacts = [];
    sortedContacts.forEach((value, index) => {
      if (value.fullname.charAt(0) != currentLetter) {
        currentLetter = value.fullname.charAt(0);
        let newGroup = {
          letter: currentLetter,
          list: []
        };
        currentContacts = newGroup.list;
        this.groupedContacts.push(newGroup);
      }
      currentContacts.push(value);
    });
   //console.log(this.groupedContacts)
  };

  openModal() {
   //console.log("data");
    let modal = this.modalCtrl.create(GroupModalPage);
    modal.onDidDismiss((data) => {
     //console.log(data);
      this.groupName = data;
      if (this.groupName == "close") {
        this.group = false;
      }
      else {
        this.group = true;
      }
    });
    modal.present();
  }
}


@Component({
  selector: 'page-contact',
  templateUrl: 'creategroup.html',
})
export class GroupModalPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public departments;
  public designation;
  public department_id;
  public designation_id;
  public resp;
  groupname: any;
  public primaryColor: any;
  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
  }
  ionViewDidEnter() {

  };

  moveFocus(nextElement) {
  };

  dismiss() {
    if (this.groupname) {
      this.viewCtrl.dismiss(this.groupname);
    }
    else {
      this.referenceservice.basicAlert("New HRMS", 'Please add groupname');
    }
  }
  closeFilter() {
    this.viewCtrl.dismiss("close");
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}
