import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, PopoverController, ViewController } from 'ionic-angular';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';
import { AddclientPage } from '../addclient/addclient';
import { ClientProfilePage } from '../clientprofile/clientprofile';
import { Content } from 'ionic-angular';
import { NativePageTransitions, NativeTransitionOptions } from '@ionic-native/native-page-transitions';
// import { Content } from 'ionic-angular/umd/navigation/nav-interfaces';

/**
 * Generated class for the ClientsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-clients',
  templateUrl: 'clients.html',
})
export class ClientsPage {
  @ViewChild(Content) content: Content;
  token: any;
  url: any;
  loading: any;
  clientList: any;
  pageNumber = 1;
  resp: any;
  page = false;
  public role: any;
  public roleId: any;
  public keywords:any;
  public noData = false;
  cssClass: string;
  public primaryColor: any;
  constructor(public navCtrl: NavController, public nativePageTransitions: NativePageTransitions, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }
  ionViewDidLoad() {

    this.applyClassBySelection('rotateIn');
  }

  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getClientList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.clientList = this.resp.data.list;
         //console.log(this.resp);
          // this.content.scrollToTop();
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          if (this.clientList == 0) {
            this.noData = true;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  };

  doRefresh(refresher) {
    this.cssClass = "";
    this.page = false;
    this.url = this.apiService.getClientList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.clientList = this.resp.data.list;
         //console.log(this.resp);
          // this.content.scrollToTop();
          if (this.resp.status_code == 0) {
            this.noData = true;
           //console.log(this.noData)
          }
          if (this.clientList == 0) {
            this.noData = true;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  ionViewWillLeave() {

    let options: NativeTransitionOptions = {
      direction: 'up',
      duration: 500,
      slowdownfactor: 3,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0,
    };

    this.nativePageTransitions.flip(options)
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }

  doInfinite(infiniteScroll) {
    this.cssClass = "";
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.getClientList();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = { page: this.resp.data.next_page }
        this.http.post(this.url, data, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.clientList.push(this.resp.data.list[i]);
            }
           //console.log(this.resp);
            if (this.resp.data.next_page == -1) {
              this.page = true;
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
           //console.log("error=" + error.status);
           //console.log("error=" + error.error); // error message as string
           //console.log("error=" + error.headers);
          });
      }
      else {
       //console.log('Async operation has ended');
        infiniteScroll.complete();
      }
    }, 1000);
  };


  openModal() {
    this.cssClass = '';
    let modal = this.modalCtrl.create(ClientFilterPage);
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getClientList();
          this.token = localStorage.getItem('token')
         //console.log(this.token);
          var token = { 'token': this.token };
          data.page = 1;
         //console.log(data)
          this.http.post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
              this.clientList = this.resp.data.list;
              this.content.scrollToTop();
             //console.log(this.resp);
              if (this.resp.data.next_page == -1) {
                this.page = true;
              }
              if (this.resp.status_code == 0) {
                this.noData = true;
               //console.log(this.noData)
              }
              if (this.resp.data.length == 0) {
                this.noData = true;
              }
              this.loading.dismiss();
            })
            .catch(error => {

              this.loading.dismiss();
             //console.log("error=" + error.status);
             //console.log("error=" + error.error); // error message as string
             //console.log("error=" + error.headers);
            });
        }
      }
    });
    modal.present();
   //console.log("modal")
  };

  // openProfile(emp) {
  //   this.navCtrl.push(ProfilePage, {
  //     user: emp.user_id
  //   });
  // };

  presentPopover(myEvent, employee) {
    this.cssClass = '';
    let popover = this.popoverCtrl.create(ClientPopoverPage);
    popover.present({
      ev: myEvent
    });
    popover.onDidDismiss(data => {
      if (data == "delete") {
        let alert = this.referenceservice.confirmAlert("Confirm Delete", "Do you want to continue to delete this Client");
        alert.present();
        alert.onDidDismiss((data) => {
          if (data) {
            this.url = this.apiService.deleteClient();
            var token = { 'token': this.token };
            var empData = { company: employee.co_id }
            this.http.post(this.url, empData, token)
              .then(data => {
                this.resp = JSON.parse(data.data);
               //console.log(this.resp)
                if (this.resp.message == "Invalid token or Token missing") {
                  this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
                  // this.navCtrl.popAll();
                  this.loading.dismiss();
                  localStorage.clear();
                  this.navCtrl.setRoot(LoginPage);
                }
                if (this.resp.message == "Success") {
                  if (this.resp.status_code == 1) {
                    this.loading.dismiss();
                    this.referenceservice.basicAlert(this.resp.message, 'Client Removed successfully');
                    this.ionViewWillEnter();
                  }
                }
              })
              .catch(error => {
                this.loading.dismiss();
                this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
              });
          }
        });
      }
      if (data == "edit") {
        this.navCtrl.push(EditClientPage, {
          client: employee,
        });
      }
    })
  };

  openClientProfile(myEvent, client) {
    this.cssClass = '';
    if (myEvent.path[1].className == "more-icon") {
      this.presentPopover(myEvent, client);
    }
    else {
      this.navCtrl.push(ClientProfilePage, {
        clientId: client.co_id,
      })
    }

  }
  addClient() {
    this.cssClass = '';
    this.navCtrl.push(AddclientPage);
  }
}


@Component({
  template: `
    <ion-list class="popover-list">
      <button ion-item (click)="close('edit')">Edit</button>
      <button ion-item (click)="close('delete')">Delete</button>
    </ion-list>
  `
})
export class ClientPopoverPage {
  public employee;
  constructor(public viewCtrl: ViewController, public navParams: NavParams, public navCtrl: NavController) {
  }

  close(action) {
    this.viewCtrl.dismiss(action);
  }
}





/********************************************************** Edit *****************************/

@Component({
  selector: 'page-clients',
  templateUrl: 'clientEdit.html',
})
export class EditClientPage {
  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public departments;
  public designation;
  public username1 = false;
  public email1 = false;
  public phone1 = false;
  public designate = false;
  public clientid;
  public client: any = {};
  public primaryColor: any;
  public dept: any;
  public clientdata: any = {};
  basicInformation = true;
  contactInformation = false;
  webInformation = false;
  bankInformation = false;
  hostInformation = false;
  constructor(public navCtrl: NavController, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {

    var id = this.navParams.get('client');
   //console.log(id)
    this.clientid = id.co_id;
    this.primaryColor = localStorage.getItem('primary_color');
  }

  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getClientProfile();
    var data = { co_id: this.clientid };
   //console.log(data)
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp)
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.clientdata = this.resp.data;
       //console.log(this.clientdata)
        this.loading.dismiss();
      }
    })
      .catch(error => {
       //console.log(error)
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };



  contact() {
    this.basicInformation = false;
    this.contactInformation = true;
  }
  basic() {
    this.basicInformation = true;
    this.contactInformation = false;
  }
  web() {
    this.webInformation = true;
    this.contactInformation = false;
  }
  contactback() {
    this.webInformation = false;
    this.contactInformation = true;
  }
  bank() {
    this.webInformation = false;
    this.bankInformation = true;
  }
  webback() {
    this.webInformation = true;
    this.bankInformation = false;
  }

  host() {
    this.hostInformation = true;
    this.bankInformation = false;
  }
  bankback() {
    this.hostInformation = false;
    this.bankInformation = true;
  }


  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  submit() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.editClient();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.client;
    data.co_id = this.clientid;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
     //console.log(data)
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Success") {
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Client Edited successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };

}


// ************************************************** FILTER *************************************



@Component({
  selector: 'page-clients',
  templateUrl: 'client_filter.html',
})
export class ClientFilterPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public clients;
  public designation;
  public department_id;
  public designation_id;
  public resp;
  public primaryColor: any;
  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, public popoverCtrl: PopoverController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
  }
  ionViewDidEnter() {
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getClientList();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.post(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
      this.clients = this.resp.data.list;
      // this.departments.push({ 'deptid': -1, 'deptname': 'All' });
      // this.filterData.department = -1;
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  moveFocus(nextElement) {
    nextElement.setFocus();
  };



  dismiss() {
   //console.log(this.filterData.length);
    if (JSON.stringify(this.filterData) == JSON.stringify({})) {
      this.referenceservice.basicAlert("New HRMS", 'Please fill any field to filter');
    }
    else {
      this.viewCtrl.dismiss(this.filterData);
    }
  }
  closeFilter() {
   //console.log(this.filterData)
    this.viewCtrl.dismiss("close");
  }

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}
