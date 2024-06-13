import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { EditprofilePage } from '../editprofile/editprofile';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { Camera, CameraOptions } from '../../../node_modules/@ionic-native/camera';
import { ActionSheet, ActionSheetOptions } from '@ionic-native/action-sheet';
import { FileUploadOptions, FileTransfer, FileTransferObject } from '../../../node_modules/@ionic-native/file-transfer';
import { File } from '@ionic-native/file';
import { LoginPage } from '../login/login';
import { EstimatesPage } from '../estimates/estimates';
import { InvoicePage } from '../invoice/invoice';
import { ProjectviewPage } from '../projectview/projectview';
/**
 * Generated class for the ProfilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-clientprofile',
  templateUrl: 'clientprofile.html',
})
export class ClientProfilePage {
  profile = "overview";
  public loading;
  public url;
  public token;
  public resp;
  public clientProfileData: any = {};
  public role: any;
  public roleId: any;
  public imagePath;
  estimatesList: any;
  public profileImage;
  projectList: any = {};
  invoicesList: any;
  invoice= false;
  estimates=false;
  projects=false;
  co_id: any;
  public keywords:any={};
  client: any;
  public primaryColor: any;
  public secondryColor: any;
  constructor(public navCtrl: NavController, public navParams: NavParams, private transfer: FileTransfer, private file: File, private actionSheet: ActionSheet, private camera: Camera, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    var id = this.navParams.get("user");
    this.primaryColor = localStorage.getItem('primary_color');
    this.secondryColor = localStorage.getItem('secondry_color');
    var id = this.navParams.get('clientId');
    if (id) {
      this.co_id = id
    }
    else {
      this.co_id = ""
    }
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  getProgresstyle() {
    return {
      background:
        "linear-gradient(to right," + this.secondryColor + "," + this.primaryColor + ")"
    };
  }
  getFontstyle() {
    return { color: this.secondryColor };
  }
  ionViewWillEnter() {
    this.loading = this.referenceService.loading();
    this.loading.present();
    var data = { co_id: this.co_id };
    this.url = this.apiService.getClientProfile();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.clientProfileData = this.resp.data;
          this.projectList = this.clientProfileData.projects;
          this.projectList.forEach((value, key) => {
           //console.log(value.tasks_open.length)
            this.projectList[key].opentasks = value.tasks_open.length;
            this.projectList[key].completedtasks = value.tasks_completed.length;
          });
          this.invoicesList = this.clientProfileData.invoices;
          this.invoicesList.forEach(element => {
            this.url = this.apiService.convertCurrency();
            this.token = localStorage.getItem("token");
            var token = { token: this.token };
            var currency = localStorage.getItem('currency')
            var data = { code: currency, amount: element.total_cost };
           //console.log(data)
            this.http
              .post(this.url, data, token)
              .then(data => {
                var resp = JSON.parse(data.data);
                if (resp.message == "Success") {
                   element.converted_total_cost = resp.data;
                }
               //console.log(resp);
              });
              this.url = this.apiService.convertCurrency();
              this.token = localStorage.getItem("token");
              var token = { token: this.token };
              var currency = localStorage.getItem('currency')
              var data = { code: currency, amount: element.payment_made };
             //console.log(data)
              this.http
                .post(this.url, data, token)
                .then(data => {
                  var resp = JSON.parse(data.data);
                  if (resp.message == "Success") {
                    element.converted_payment_made = resp.data;
                  }
                 //console.log(resp);
                });
          });
          this.estimatesList = this.clientProfileData.estimates;
          this.estimatesList.forEach(element => {
            this.url = this.apiService.convertCurrency();
            this.token = localStorage.getItem("token");
            var token = { token: this.token };
            var currency = localStorage.getItem('currency')
            var data = { code: currency, amount: element.amount };
           //console.log(data)
            this.http
              .post(this.url, data, token)
              .then(data => {
                var resp = JSON.parse(data.data);
                if (resp.message == "Success") {
                   element.converted_amount = resp.data;
                }
               //console.log(resp);
              });
          });
          if(this.invoicesList.length == 0){
            this.invoice =false;
          }
          else{
            this.invoice = true;
          }
          if(this.estimatesList.length == 0){
            this.estimates = false;
          }
          else{
            this.estimates = true;
          }
          if(this.projectList.length == 0){
            this.projects = false;
          }
          else{
            this.projects = true;
          }
         //console.log(this.projectList)
         //console.log(this.clientProfileData)
         //console.log(this.estimatesList)
          // this.projectList = this.clientProfileData.projects;
          // this.projectList.forEach((value, key) => {
          //  //console.log(value.tasks_open.length)
          //   this.projectList[key].opentasks = value.tasks_open.length;
          //   this.projectList[key].completedtasks = value.tasks_completed.length;
          // })
          this.loading.dismiss();
        }
      })
      .catch(error => {
       //console.log(error);
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
      });
  };

  editProfile() {
    this.navCtrl.push(EditprofilePage, {
      profile: this.clientProfileData
    });
  };

  openInvoice(invoice){
    this.navCtrl.push(InvoicePage,{
      invoice:invoice
    });
  };

  openProject(project){
    this.navCtrl.push(ProjectviewPage,{
      project:project,
    });
  }


  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  openEstimate(estimate){
    this.navCtrl.push(EstimatesPage,{
      estimate:estimate
    })
  };

  getstyle() {
    return { 'background': "linear-gradient(" + this.primaryColor + "," + this.secondryColor + ")" }
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
  }
  setDefaultPic() {
    this.profileImage = "assets/imgs/user.jpg";
  };
};
