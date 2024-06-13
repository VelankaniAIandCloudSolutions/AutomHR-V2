import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { Validators, FormGroup, FormBuilder, FormControl, FormArray } from '@angular/forms';
import { LoginPage } from '../login/login';
import { ActionSheetOptions, ActionSheet } from '@ionic-native/action-sheet';
import { CameraOptions, Camera } from '@ionic-native/camera';
import { FileTransferObject, FileUploadOptions, FileTransfer } from '@ionic-native/file-transfer';
import { FilePath } from '@ionic-native/file-path';
import { Base64 } from '@ionic-native/base64';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';

/**
 * Generated class for the AddemployeePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-createexpenses',
  templateUrl: 'createexpenses.html',
})
export class CreateExpensesPage {

  public selected = [];
  uploadImage = false;
  public selected1;
  public loading;
  safeUrl: SafeResourceUrl;
  public url;
  public token;
  public resp;
  public projectDetails: any = {};
  public designation;
  public designate = false;
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public totalCost = 0;
  public phone1 = false;
  expensesForm: FormGroup;
  category: any;
  projects: any;
  currentTime: any;
  year: any;
  image: any;
  imagePath: any = {};
  day: any;
  month: any;
  public primaryColor: any;
  public client = false;
  bill = false;
  invo = false;
  img: any;
  public keywords:any={};
  constructor(public navCtrl: NavController, private sanitizer: DomSanitizer, private filePath: FilePath, public base64: Base64, public actionSheet: ActionSheet, private transfer: FileTransfer, public camera: Camera, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.expensesForm = fb.group({
      'amount': ['', [Validators.required]],
      'project': ['', [Validators.required]],
      'client': ['', [Validators.required]],
      'expense_date': ['', [Validators.required]],
      'category': ['', [Validators.required]],
      'billable': ['', [Validators.required]],
      'extra_fee': ['', [Validators.required]],
      'show_client': ['', [Validators.required]],
      'invoiced': ['', [Validators.required]],
      'notes': ['', [Validators.required]],
      'receipt': ['', [Validators.required]]
    });
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }


  ionViewWillEnter() {
   //console.log("bcksak");
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    this.http.get(this.url, {}, token).then(data => {
      this.resp = JSON.parse(data.data);
     //console.log(this.resp.data);
      if (this.resp.message == "Invalid token or Token missing") {
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        // this.navCtrl.popAll();
        this.loading.dismiss();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if (this.resp.message == "Success") {
        this.projectDetails = this.resp.data;
       //console.log(this.projectDetails);

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
   //console.log('ionViewWillEnter DepartmentsPage');
   //console.log('ionViewWillEnter AddemployeePage');
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };


  moveFocus(nextElement) {
    nextElement.setFocus();
  };


  totalCostCalc() {
    this.totalCost = 0;
    this.expensesForm.value.item_details.forEach(element => {
      this.totalCost = this.totalCost + parseInt(element.total_cost);
    });
  }
  addEmployee() {

    const fileTransfer: FileTransferObject = this.transfer.create();
    this.token = localStorage.getItem('token')
    var token = { 'token': this.token };
    let options1: FileUploadOptions = {
      fileKey: 'img_data',
      fileName: '.jpg',
      httpMethod: 'post',
      mimeType: "image/jpg/png/jpeg",
      chunkedMode: false,
      headers: token,
      params: this.expensesForm.value
    }
    this.url = this.apiService.createExpense();
   //console.log(options1)
    this.loading = this.referenceService.loading();
    this.loading.present();
    fileTransfer.upload(this.imagePath, this.url, options1)
      .then((data) => {
        var resp = JSON.parse(data.response)
        // this.profileImage = resp.data;
       //console.log(resp)
        this.loading.dismiss();
        // success
      }, (err) => {
       //console.log(err)
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
        // error
      })

   //console.log(this.expensesForm.value)
    // if (this.expensesForm.get('amount').valid && this.expensesForm.get('category').valid) {
    //   this.loading = this.referenceService.loading();
    //   this.loading.present();
    //   this.url = this.apiService.createExpense();
    //   this.token = localStorage.getItem('token');
    //   var token = { "token": this.token };
    //   var data = this.expensesForm.value;
    //  //console.log(this.img);
    //   this.http.uploadFile(this.url, data, token,this.imagePath,'receipt').then(data => {
    //     var resp = JSON.parse(data.data);
    //    //console.log(resp);
    //     if (resp.message == "Invalid token or Token missing") {
    //       this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
    //       // this.navCtrl.popAll();
    //       this.loading.dismiss();
    //       localStorage.clear();
    //       this.navCtrl.setRoot(LoginPage);
    //     }
    //     if (resp.message == "Success") {
    //      //console.log(resp)
    //       if (resp.status_code == 1) {
    //         this.loading.dismiss();
    //         this.referenceService.basicAlert(resp.message, 'Expense created successfully');
    //         this.navCtrl.pop();
    //       }
    //       else if (resp.status_code == 0) {
    //         this.loading.dismiss();
    //         this.referenceService.basicAlert('Already requested', resp.message);
    //       }
    //     }
    //     if (resp.status_code == -1) {
    //       this.loading.dismiss();
    //       this.referenceService.basicAlert('Already requested', resp.message);
    //     }
    //   })
    //     .catch(error => {
    //       this.loading.dismiss();
    //       this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
    //      //console.log("error=" + error);
    //      //console.log("error=" + error.error); // error message as string
    //      //console.log("error=" + error.headers);
    //     });
    // }
    // else {
    //   if (this.expensesForm.get('amount').value == '') {
    //     this.referenceService.basicAlert("New HRMS", "Please enter the amount in the input field");
    //   }
    //   else if (this.expensesForm.get('category').value == '') {
    //     this.referenceService.basicAlert("New HRMS", "Category must be choosed");
    //   }
    // }
  };

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


      // this.expensesForm.controls.receipt.setValue(imageData);
      // this.upload();
      this.imagePath = imageData;
      let base64Image = 'data:image/jpeg;base64,' + imageData;

      this.base64.encodeFile(imageData).then((base64File: string) => {
        ////console.log(base64File)
        // this.imagePath = imageData;
        // this.imagePath = base64File;
        // this.safeUrl = this.sanitizer.bypassSecurityTrustResourceUrl(base64File);
        // this.expensesForm.controls.receipt.setValue(this.imagePath);
        this.uploadImage = true;
      }, (err) => {
       //console.log(err);
      });

    }, (err) => {
      this.referenceService.basicAlert("New HRMS", 'Selected image is not supported please choose JPEG or PNG image');
      // Handle error
    });
  };

  // upload() {
  //   const fileTransfer: FileTransferObject = this.transfer.create();
  //   this.token = localStorage.getItem('token')
  //   var token = { 'token': this.token };
  //   let options1: FileUploadOptions = {
  //     fileKey: 'file',
  //     fileName: '.jpg',
  //     httpMethod: 'post',
  //     mimeType: "image/jpg/png/jpeg",
  //     chunkedMode: false,
  //     headers: token
  //   }
  //   this.url = this.apiService.imageUpload();
  //  //console.log(options1)
  //   this.loading = this.referenceService.loading();
  //   this.loading.present();
  //   fileTransfer.upload(this.imagePath, this.url, options1)
  //     .then((data) => {
  //       var resp = JSON.parse(data.response)

  //       // success
  //     }, (err) => {
  //      //console.log(err)
  //       this.loading.dismiss();
  //       this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
  //       // error
  //     })
  // };
  imageFilePath_change($event) {
   //console.log($event)
  }
}





















