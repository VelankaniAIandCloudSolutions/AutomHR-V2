import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Geolocation } from '@ionic-native/geolocation';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';
import { CameraOptions, Camera } from '@ionic-native/camera';
import { FileTransferObject, FileUploadOptions, FileTransfer } from '@ionic-native/file-transfer';
// import { CameraOptions, Camera } from '@ionic-native/camera';

/**
 * Generated class for the AttendancePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-attendance',
  templateUrl: 'attendance.html',
})

export class AttendancePage {

  public date;
  public localdate;
  public localtime;
  public day;
  public token;
  public url;
  public loading;
  public resp;
  public lat;
  public lng;
  public punchin = false;
  public punchout = false;
  public punchinData: any;
  public punchOutData: any;
  public primaryColor: any;
  public list;
  public page;
  currentTime: any;
  year: any;
  month: any;
  public noData;
  type: any;
  page1: any;
  user_id: any;
  user: any;
  isData = false;
  public keywords: any = {};
  selectedPictureUri: any;
  location: any;
  constructor(public navCtrl: NavController, public navParams: NavParams, public http: HTTP,
    private transfer: FileTransfer, private geolocation: Geolocation, public referenceService: ReferenceService,
    public apiService: ApiService, public camera: Camera) {
    this.date = new Date();
    var month = new Date().getMonth() + 1
    this.localdate = new Date().getDate() + '/' + month + '/' + new Date().getFullYear();
    if (this.localdate == localStorage.getItem('attendanceDate')) {
      if (localStorage.getItem('punchStatus') == 'punch_in') {
        this.punchin = true;
      }
      else {
        this.punchin = false;
      }
    }
    else {
      this.punchin = false;
    }
    this.localtime = new Date().toLocaleTimeString();

    this.geolocation.getCurrentPosition().then((resp) => {
      this.lat = resp.coords.latitude;
      this.lng = resp.coords.longitude;
    }).catch((error) => {
      //console.log('Error getting location', error);
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewWillEnter() {
    if (this.localdate == localStorage.getItem('attendanceDate')) {
      if (localStorage.getItem('punchStatus') == 'punch_in') {
        this.punchin = true;
      }
      else {
        this.punchin = false;
      }
    }
    else {
      this.punchin = false;
    }
    let networkStatus = localStorage.getItem("NETWORK_STATUS");
    if (networkStatus === 'ONLINE') {
      this.selectedPictureUri = '';
      this.date = this.year + '-' + this.month + '-' + this.day;
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.createAttendance();
      this.token = localStorage.getItem('token')
      //console.log(this.token);
      var token = { 'token': this.token };
      var data = {}
      //console.log(data)
      this.http.post(this.url, data, token)
        .then(data => {
          this.resp = JSON.parse(data.data);
          //console.log(this.resp)
          if (this.resp.message == "Invalid token or Token missing") {
            this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
            this.loading.dismiss();
            localStorage.clear();
            this.navCtrl.setRoot(LoginPage);
          }
          if (this.resp.message == "Success") {
            this.list = this.resp.data;
            this.isData = true;
            //console.log(this.resp);
            if (this.resp.status_code == 0) {
              this.noData = true;
              //console.log(this.noData)
            }
            if (this.resp.data.length == 0) {
              this.noData = true;
            }
          }
          this.loading.dismiss();
        })
        .catch(error => {
          this.loading.dismiss();
          this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
          //console.log("error=" + error);
          //console.log("error=" + error.error);
          //console.log("error=" + error.headers);
        });
    }

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  // punchIn() {
  //   // this.loading = this.referenceService.loading();
  //   // this.loading.present();

  //   this.geolocation.getCurrentPosition().then((resp) => {
  //     this.lat = resp.coords.latitude;
  //     this.lng = resp.coords.longitude;
  //     // this.loading.dismiss();
  //     this.setpunch();
  //   }).catch((error) => {
  //     // this.loading.dismiss();
  //     this.referenceService.basicAlert("New HRMS", 'Error getting location Please turn on your location and try again');
  //    //console.log('Error getting location', error);
  //   });

  // };


  takePicture() {
    const options: CameraOptions = {
      quality: 100,
      destinationType: this.camera.DestinationType.FILE_URI,
      encodingType: this.camera.EncodingType.JPEG,
      mediaType: this.camera.MediaType.PICTURE,
      sourceType: this.camera.PictureSourceType.CAMERA,
      cameraDirection: 1
    };
    this.camera.getPicture(options).then(imageUri => {
      this.selectedPictureUri = imageUri;
      console.log(this.selectedPictureUri);
    }).catch(console.error);
  }

  saveImage() {
    this.loading.showLoading();
    this.geolocation.getCurrentPosition().then((resp) => {
      this.loading.dismissLoading();
      console.log(resp.coords, "On save");
      this.location = resp.coords;
      let networkStatus = localStorage.getItem("NETWORK_STATUS");
      let data = {
        imageUri: this.selectedPictureUri,
        latitude: this.location.latitude,
        longitude: this.location.longitude
      }
      if (networkStatus === "ONLINE") {
        console.log(this.location, this.selectedPictureUri);
        this.referenceService.basicAlert("Success", "Uploaded");
      }
      else if (networkStatus === "OFFLINE") {
        console.log(this.location, this.selectedPictureUri);
        let savedData: any = localStorage.getItem("OFFLINE_DATA");
        console.log(savedData);
        savedData = JSON.parse(savedData)
        savedData = (savedData) ? savedData : [];
        savedData.push(data)
        localStorage.setItem("OFFLINE_DATA", JSON.stringify(savedData));
        this.referenceService.basicAlert("Success", "Saved in local");
      }
    });
  }

  punchIn() {
    if (this.selectedPictureUri) {
      this.loading = this.referenceService.loading();
      this.loading.present();
      let data: any = {};
      data.latitude = this.lat,
        data.longitude = this.lng;
      let networkStatus = localStorage.getItem("NETWORK_STATUS");
      if (networkStatus === "ONLINE") {
        this.token = localStorage.getItem('token');
        var token = { "token": this.token };
        this.url = this.apiService.punchIn();
        const fileTransfer: FileTransferObject = this.transfer.create();
        let options1: FileUploadOptions = {
          fileKey: 'user_profile_pic',
          fileName: 'image.jpg',
          params: data,
          httpMethod: 'post',
          mimeType: "image/jpg/png/jpeg",
          chunkedMode: false,
          headers: token
        }
        fileTransfer.upload(this.selectedPictureUri, this.url, options1)
          .then((data) => {

            var resp = JSON.parse(data.response);
            if (resp.message == "SUCCESS") {
              if (resp.status_code == 1) {
                this.loading.dismiss();
                localStorage.setItem('punchStatus', 'punch_in');
                localStorage.setItem('attendanceDate', this.localdate);
                this.referenceService.basicAlert(resp.message, "Punched in Successfully");
                this.ionViewWillEnter();
              }
            }
            this.loading.dismiss();
            console.log(resp);
          }, (err) => {
            console.log(err);
            this.loading.dismiss();
            this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
          })
      }
      else if (networkStatus === "OFFLINE") {
        this.loading.dismiss();
        let date = new Date();
        data.pictureUri = this.selectedPictureUri;
        data.punch = "punch_in";
        data.punch_date = Math.round((new Date()).getTime() / 1000);
        let savedData: any = localStorage.getItem("OFFLINE_DATA");
        savedData = JSON.parse(savedData)
        savedData = (savedData) ? savedData : [];
        savedData.push(data)
        localStorage.setItem("OFFLINE_DATA", JSON.stringify(savedData));
        localStorage.setItem('punchStatus', 'punch_in');
        localStorage.setItem('attendanceDate', this.localdate);
        if (this.localdate == localStorage.getItem('attendanceDate')) {
          if (localStorage.getItem('punchStatus') == 'punch_in') {
            this.punchin = true;
          }
          else {
            this.punchin = false;
          }
        }
        else {
          this.punchin = false;
        }
        this.selectedPictureUri = '';
        this.referenceService.basicAlert("Success", "Punched in Successfully");
      }
    }
    else {
      this.referenceService.basicAlert("New HRMS", 'Take pickture and then continue');
    }

  };

  // punchOut() {
  //   // this.loading = this.referenceService.loading();
  //   // this.loading.present();
  //   this.geolocation.getCurrentPosition().then((resp) => {
  //     this.lat = resp.coords.latitude;
  //     this.lng = resp.coords.longitude;
  //     // this.loading.dismiss();
  //     this.Outpunch();
  //   }).catch((error) => {
  //     // this.loading.dismiss();
  //     this.referenceService.basicAlert("New HRMS", 'Error getting location Please turn on your location and try again');
  //    //console.log('Error getting location', error);
  //   });

  // };

  punchOut() {
    if (this.selectedPictureUri) {
      this.loading = this.referenceService.loading();
      this.loading.present();
      let data: any = {};
      data.latitude = this.lat,
        data.longitude = this.lng;
      let networkStatus = localStorage.getItem("NETWORK_STATUS");
      if (networkStatus === "ONLINE") {
        this.token = localStorage.getItem('token');
        var token = { "token": this.token };
        this.url = this.apiService.punchOut();
        const fileTransfer: FileTransferObject = this.transfer.create();
        let options1: FileUploadOptions = {
          fileKey: 'user_profile_pic',
          fileName: 'image.jpg',
          params: data,
          httpMethod: 'post',
          mimeType: "image/jpg/png/jpeg",
          chunkedMode: false,
          headers: token
        }
        fileTransfer.upload(this.selectedPictureUri, this.url, options1)
          .then((data) => {
            console.log(data.response);
            var resp = JSON.parse(data.response);
            if (resp.message == "SUCCESS") {
              if (this.resp.status_code == 1) {
                this.loading.dismiss();
                localStorage.setItem('punchStatus', 'punch_out');
                localStorage.setItem('attendanceDate', this.localdate);
                this.referenceService.basicAlert(resp.message, "Punched out Successfully")
                this.ionViewWillEnter();
              }
            }
            this.loading.dismiss();
          }, (err) => {
            console.log(err);
            this.loading.dismiss();
            this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
          })
      }
      else if (networkStatus === "OFFLINE") {
        this.loading.dismiss();
        let date = new Date();
        data.pictureUri = this.selectedPictureUri;
        data.punch = "punch_out";
        data.punch_date = Math.round((new Date()).getTime() / 1000);
        let savedData: any = localStorage.getItem("OFFLINE_DATA");
        savedData = JSON.parse(savedData)
        savedData = (savedData) ? savedData : [];
        savedData.push(data)
        localStorage.setItem('punchStatus', 'punch_out');
        localStorage.setItem('attendanceDate', this.localdate);
        localStorage.setItem("OFFLINE_DATA", JSON.stringify(savedData));
        if (this.localdate == localStorage.getItem('attendanceDate')) {
          if (localStorage.getItem('punchStatus') == 'punch_in') {
            this.punchin = true;
          }
          else {
            this.punchin = false;
          }
        }
        else {
          this.punchin = false;
        }
        this.selectedPictureUri = '';
        this.referenceService.basicAlert("Success", "Punched out Successfully");
      }
    }
    else {
      this.referenceService.basicAlert("New HRMS", 'Take pickture and then continue');
    }
  };
}
