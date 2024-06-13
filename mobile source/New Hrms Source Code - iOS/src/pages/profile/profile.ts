import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, Content, ModalController, ViewController } from 'ionic-angular';
import { EditprofilePage } from '../editprofile/editprofile';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { Camera, CameraOptions } from '../../../node_modules/@ionic-native/camera';
import { ActionSheet, ActionSheetOptions } from '@ionic-native/action-sheet';
import { FileUploadOptions, FileTransfer, FileTransferObject } from '../../../node_modules/@ionic-native/file-transfer';
import { File } from '@ionic-native/file';
import { LoginPage } from '../login/login';
/**
 * Generated class for the ProfilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-profile',
  templateUrl: 'profile.html',
})
export class ProfilePage {
  @ViewChild(Content) content: Content;
  profile = "overview";
  public loading;
  public url;
  public token;
  public resp;
  public profileData: any = {};
  public role: any;
  public roleId: any;
  public educationDetails: any;
  public exprienceInfo: any;
  public imagePath;
  public profileImage;
  public keywords:any={};
  noEducation = false;
  noExperience = false;
  isprofile = false;
  user_id: any;
  primaryColor:any;
  secondryColor:any;
  public colorCode: any;
  id:any;
  constructor(public navCtrl: NavController, public navParams: NavParams, public modalCtrl: ModalController, private transfer: FileTransfer, private file: File, private actionSheet: ActionSheet, private camera: Camera, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
    if (this.navParams.get("user")){
      this.id = this.navParams.get("user");
    }
    else {
      this.id = localStorage.getItem('user_id');
    }
    this.colorCode = {
      'background-color': localStorage.getItem('colorCode'),
    }
    this.primaryColor = localStorage.getItem("primary_color");
    this.secondryColor = localStorage.getItem("secondry_color");
    if (this.id) {
      this.user_id = this.id
    }
    else {
      this.user_id = "";
    }
    this.loading = this.referenceService.loading();
    this.loading.present();
   //console.log(this.resp)
    var data = { user_id: this.user_id };
    this.url = this.apiService.profile();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
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
          this.profileData = this.resp.data;
          if (this.profileData.employee_details.address == 'null') {
            this.profileData.employee_details.address = '';
          }
          if (this.profileData.employee_details.city == 'null') {
            this.profileData.employee_details.city = '';
          }
          if (this.profileData.employee_details.country == 'null') {
            this.profileData.employee_details.country = '';
          }
          this.profileImage = this.profileData.avatar;
          this.educationDetails = this.profileData.education_details;
          if (this.educationDetails.length == 0) {
            this.noEducation = true;
          }
          this.exprienceInfo = this.profileData.experience_details;
          if (this.exprienceInfo.length == 0) {
            this.noExperience = true;
          }
         //console.log(this.profileData.employee_details)
         //console.log(this.profileData.employee_details.city)
         //console.log(this.profileData.employee_details.address)
          this.isprofile = true;
         //console.log(this.profileData);
          this.loading.dismiss();
        }
      })
      .catch(error => {
       //console.log(error);
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
   //console.log(document.getElementById('scroll1'));
  }

  editBasic() {
    let modal = this.modalCtrl.create(BasicInfoPage,{
      user:this.profileData.employee_details
    });
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
        }
      }
    });
    modal.present();
   //console.log("modal")
  };

  ionViewWillEnter(){
    var data = { user_id: this.user_id };
    this.url = this.apiService.profile();
    this.token = localStorage.getItem('token')
    //console.log(this.token);
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
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
          this.profileData = this.resp.data;
          if (this.profileData.employee_details.address == 'null') {
            this.profileData.employee_details.address = '';
          }
          if (this.profileData.employee_details.city == 'null') {
            this.profileData.employee_details.city = '';
          }
          if (this.profileData.employee_details.country == 'null') {
            this.profileData.employee_details.country = '';
          }
          this.profileImage = this.profileData.avatar;
          this.educationDetails = this.profileData.education_details;
          if (this.educationDetails.length == 0) {
            this.noEducation = true;
          }
          this.exprienceInfo = this.profileData.experience_details;
          if (this.exprienceInfo.length == 0) {
            this.noExperience = true;
          }
          //console.log(this.profileData.employee_details)
          //console.log(this.profileData.employee_details.city)
          //console.log(this.profileData.employee_details.address)
          this.isprofile = true;
          //console.log(this.profileData);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        //console.log(error);
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  }
  

  ionViewDidLoad(){
    var data = { user_id: this.user_id };
    this.url = this.apiService.profile();
    this.token = localStorage.getItem('token')
    //console.log(this.token);
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
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
          this.profileData = this.resp.data;
          if (this.profileData.employee_details.address == 'null') {
            this.profileData.employee_details.address = '';
          }
          if (this.profileData.employee_details.city == 'null') {
            this.profileData.employee_details.city = '';
          }
          if (this.profileData.employee_details.country == 'null') {
            this.profileData.employee_details.country = '';
          }
          this.profileImage = this.profileData.avatar;
          this.educationDetails = this.profileData.education_details;
          if (this.educationDetails.length == 0) {
            this.noEducation = true;
          }
          this.exprienceInfo = this.profileData.experience_details;
          if (this.exprienceInfo.length == 0) {
            this.noExperience = true;
          }
          //console.log(this.profileData.employee_details)
          //console.log(this.profileData.employee_details.city)
          //console.log(this.profileData.employee_details.address)
          this.isprofile = true;
          //console.log(this.profileData);
          this.loading.dismiss();
        }
      })
      .catch(error => {
        //console.log(error);
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  }
  editpersonal() {
    let modal = this.modalCtrl.create(PersonalInfoPage, {
      user_personal: this.profileData.personal_info,
      user_id :  this.profileData.employee_details.user_id
    });
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
        }
      }
    });
    modal.present();
   //console.log("modal")
  };
  editEmergency() {
    let modal = this.modalCtrl.create(PersonalInfoPage, {
      user: this.profileData.emergency_info,
      user_id: this.profileData.employee_details.user_id
    });
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
        }
      }
    });
    modal.present();
   //console.log("modal")
  };

  editBankInfo() {
    let modal = this.modalCtrl.create(BankInfoPage, {
      user: this.profileData.personal_info,
      user_id: this.profileData.employee_details.user_id
    });
    modal.onDidDismiss((data) => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        }
        else {
        }
      }
    });
    modal.present();
   //console.log("modal")
  };

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
  
  
  editProfile() {
    this.navCtrl.push(EditprofilePage, {
      profile: this.profileData
    });
  }

  getImage() {
    let buttonLabels = ['Using Camera', 'Open Library'];
    let options: ActionSheetOptions = {
      title: 'Upload image',
      subtitle: 'Choose an action',
      buttonLabels: buttonLabels,
      addCancelButtonWithLabel: 'Cancel',
      destructiveButtonLast: true
    };
    this.actionSheet.show(options).then((buttonIndex: number) => {
     //console.log('Button pressed: ' + buttonIndex);
      this.imageUpload(buttonIndex)
    });
  };

  imageUpload(type) {
    if (type == 1) {
      var options: CameraOptions = {
        quality: 100,
        destinationType: this.camera.DestinationType.FILE_URI,
        encodingType: this.camera.EncodingType.JPEG,
        mediaType: this.camera.MediaType.PICTURE,
        sourceType: this.camera.PictureSourceType.CAMERA
      }
    }
    if (type == 2) {
      var options: CameraOptions = {
        quality: 50,
        targetWidth: 600,
        targetHeight: 600,
        destinationType: this.camera.DestinationType.FILE_URI,
        encodingType: this.camera.EncodingType.JPEG,
        mediaType: this.camera.MediaType.PICTURE,
        sourceType: this.camera.PictureSourceType.PHOTOLIBRARY
      }
    }
    this.camera.getPicture(options).then((imageData) => {
      // imageData is either a base64 encoded string or a file URI
      // If it's base64 (DATA_URL):
     //console.log(imageData)
      this.imagePath = imageData;
      this.upload();
      // let base64Image = 'data:image/jpeg;base64,' + imageData;
    }, (err) => {
      this.referenceService.basicAlert("New HRMS", 'Selected image is not supported please choose JPEG or PNG image');
      // Handle error
    });
  };

  upload() {
    const fileTransfer: FileTransferObject = this.transfer.create();
    this.token = localStorage.getItem('token')
    var token = { 'token': this.token };
    let options1: FileUploadOptions = {
      fileKey: 'file',
      fileName: '.jpg',
      httpMethod: 'post',
      mimeType: "image/jpg/png/jpeg",
      chunkedMode: false,
      headers: token
    }
    this.url = this.apiService.imageUpload();
   //console.log(options1)
    this.loading = this.referenceService.loading();
    this.loading.present();
    fileTransfer.upload(this.imagePath, this.url, options1)
      .then((data) => {
        var resp = JSON.parse(data.response)
        this.profileImage = resp.data;
        this.loading.dismiss();
        // success
      }, (err) => {
       //console.log(err)
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
        // error
      })
  };

  setDefaultPic() {
    this.profileImage = "assets/imgs/user.jpg";
  }
}



// ******************************************** EDIT basci info **********************************************




@Component({
  selector: 'page-basicinfo',
  templateUrl: 'basic_info.html',
})
export class BasicInfoPage {
  public selected;
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public user: any = {};
  public primaryColor: any; 
  public departments;
  public designation;
  public designate = false;
  public reporting = false;
  public reporting_officer: any;
  public gender = [
    {
      name: "Male",
      value: "male"
    },
    {
      name: "Female",
      value: "female"
    }]

  constructor(public navCtrl: NavController, public viewCtrl: ViewController,  public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.user = this.navParams.get('user');
    this.user.user_id = this.navParams.get('user_id')
  }

  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getDepartments();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.departments = this.resp.data;
        // this.departments.push({ 'deptid': -1, 'deptname': 'Select Department' });
        // this.selected = -1;
       //console.log(this.selected);
       //console.log(this.departments);
        this.loading.dismiss();
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
   //console.log('ionViewWillEnter DepartmentsPage');
   //console.log('ionViewWillEnter AddemployeePage');
  };

  department() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getDesignation();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = { "dept_id": this.user.department_id };
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.designation = this.resp.data.designations;
        if (this.designation.length != 0) {
          this.designate = true;
        }
        else {
          this.designate = false;
        }
        this.loading.dismiss();
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  reportingTo() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.reportingTo();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = { "dept_id": this.user.department_id, des_id: this.user.designation_id };
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      this.resp = JSON.parse(data.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.reporting_officer = this.resp.data.ro;
        if (this.designation.length != 0) {
          this.reporting = true;
        }
        else {
          this.reporting = false;
        }
        this.loading.dismiss();
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };


  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  editBasicInfo() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.basicInfo();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.user;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
     //console.log(data.data)
      var resp = JSON.parse(data.data);
     //console.log(resp)
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Required input missing") {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", resp.message);
      }

      if (resp.message == "Success") {
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Profile edited successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
      if (resp.status_code == -1) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Already requested', resp.message);
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  closeFilter() {
    this.viewCtrl.dismiss("close");
  }
}


// ******************************************** EDIT personal_info **********************************************




@Component({
  selector: 'page-basicinfo',
  templateUrl: 'personal_info.html',
})
export class PersonalInfoPage {

  public loading;
  public url;
  public token;
  public resp;
  public user: any = {};
  public primaryColor: any;
  constructor(public navCtrl: NavController, public viewCtrl: ViewController, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.user = this.navParams.get('user_personal');
    this.user.user_id = this.navParams.get('user_id')
   //console.log(this.user)
  }

  ionViewWillEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  editPersonalInfo() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.personalInfo();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.user;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     console.log(resp)
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Required input missing") {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", resp.message);
      }

      if (resp.message == "Success") {
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Profile edited successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
      if (resp.status_code == -1) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Already requested', resp.message);
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  closeFilter() {
    this.viewCtrl.dismiss("close");
  }
}


// ******************************************** EDIT bank_info **********************************************




@Component({
  selector: 'page-basicinfo',
  templateUrl: 'bank_info.html',
})
export class BankInfoPage {

  public loading;
  public url;
  public token;
  public resp;
  public user: any = {};
  public primaryColor: any;
  constructor(public navCtrl: NavController, public viewCtrl: ViewController, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.user = this.navParams.get('user');
    this.user.user_id = this.navParams.get('user_id')
  }

  ionViewWillEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  editBankInfo() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.bankInfo();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.user;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Required input missing") {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", resp.message);
      }

      if (resp.message == "Success") {
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Profile edited successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
      if (resp.status_code == -1) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Already requested', resp.message);
      }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  closeFilter() {
    this.viewCtrl.dismiss("close");
  }
}


// ******************************************** EDIT emergency_info **********************************************




@Component({
  selector: 'page-basicinfo',
  templateUrl: 'emergency.html',
})
export class EmergencyPage {

  public loading;
  public url;
  public token;
  public resp;
  public user: any = {};
  public primaryColor: any;
  constructor(public navCtrl: NavController, public viewCtrl: ViewController, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.user = this.navParams.get('user');
    this.user.user_id = this.navParams.get('user_id')
  }

  ionViewWillEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  moveFocus(nextElement) {
    nextElement.setFocus();
  };

  editEmergency() {
    console.log(this.user)
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.emergencyInfo();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.user;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
      if (resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (resp.message == "Required input missing") {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", resp.message);
      }

      if (resp.message == "Success") {
       //console.log(resp)
        if (resp.status_code == 1) {
          this.loading.dismiss();
          this.referenceService.basicAlert(resp.message, 'Profile edited successfully');
          this.navCtrl.pop();
        }
        else if (resp.status_code == 0) {
          this.loading.dismiss();
          this.referenceService.basicAlert('Already requested', resp.message);
        }
      }
      if (resp.status_code == -1) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Already requested', resp.message);
      }
      this.loading.dismiss();
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  };

  closeFilter() {
    this.viewCtrl.dismiss("close");
  }
}

