import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { Validators, FormGroup, FormBuilder, FormControl, FormArray } from '@angular/forms';
import { LoginPage } from '../login/login';

/**
 * Generated class for the AddemployeePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-createestimate',
  templateUrl: 'createestimate.html',
})
export class CreateEstimatePage {

  public selected = [];
  public selected1;
  public loading;
  public url;
  public token;
  public resp;
  public projectDetails: any = {};
  public designation;
  public designate = false;
  public username1 = false;
  public password1 = false;
  public email1 = false;
  public phone1 = false;
  estimateFrom: FormGroup;
  public primaryColor: any;
  tax1:any;
  tax2:any;
  discount:any;
  subtotal=0;
  public fixed = false;
  public totalCost = 0;
  item: any = {
    item_name: '',
    item_desc: '',
    unit_cost: '',
    item_order: '',
    tax_rate: '',
    item_tax_total: '',
    quantity: '',
    total_cost: ''
  }
  public keywords : any={};
  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
    this.estimateFrom = fb.group({
      'reference_no': ['', [Validators.required]],
      'client': ['', [Validators.required]],
      'tax': [0, [Validators.required]],
      'tax2': [0, [Validators.required]],
      'due_date': ['', [Validators.required]],
      'discount': [0, [Validators.required]],
      'notes': ['', [Validators.required]],
      'item_details': fb.array([this.itemForm()]),
    });
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }



  ionViewWillEnter() {
   //console.log("bcksak");
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token }
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
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
   //console.log('ionViewWillEnter DepartmentsPage');
   //console.log('ionViewWillEnter AddemployeePage');
  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

  itemForm() {
    return this.fb.group({
      'item_name': new FormControl('', Validators.required),
      'item_desc': new FormControl('', Validators.required),
      'quantity': new FormControl('', Validators.required),
      'unit_cost': new FormControl('', Validators.required),
      'item_order': new FormControl('', Validators.required),
      'tax_rate': new FormControl('', Validators.required),
      'item_tax_total': new FormControl('', Validators.required),
      'total_cost': new FormControl('', Validators.required),
    })
  }

  moveFocus(nextElement) {
    nextElement.setFocus();
  };
  change(i) {
   //console.log(this.selected)
    this.projectDetails.saved_item.forEach(element => {
      if (element.item_name == this.selected[i]) {
        this.item.item_name = element.item_name;
        this.item.item_desc = element.item_desc;
        this.item.quantity = element.quantity;
        this.item.total_cost = element.total_cost;
        this.item.tax_rate = element.item_tax_rate;
        this.item.unit_cost = element.unit_cost;
        this.item.item_order = i;
        this.item.item_tax_total = element.item_tax_total;
        var arrayControl = <FormArray>this.estimateFrom.controls.item_details;
        var arr = arrayControl.controls[i];
        arr.patchValue(this.item);
       //console.log(arr);
      }
    });
    this.totalCostCalc();
  }
  addItem(): void {
    const control = <FormArray>this.estimateFrom.controls.item_details;
    control.push(this.itemForm());
  };

  qtyChange(i) {
    this.subtotal = 0;
    this.estimateFrom.value.item_details.forEach((element, key) => {
      if (key == i) {
        this.item.quantity = element.quantity;
        this.item.total_cost = this.item.quantity * this.item.unit_cost;
        this.item.item_tax_total = Math.ceil(this.item.total_cost * (parseInt(this.item.tax_rate) * 0.01));
        this.item.total_cost = this.item.total_cost + this.item.item_tax_total;
        var arrayControl = <FormArray>this.estimateFrom.controls.item_details;
        var arr = arrayControl.controls[i];
        arr.patchValue(this.item);
      }
    });
    this.totalCostCalc();
  };

  
  totalCostCalc() {
    this.subtotal = 0;
    this.estimateFrom.value.item_details.forEach(element => {
      this.subtotal = this.subtotal + parseInt(element.total_cost);
      this.tax1 = Math.ceil(this.subtotal * (parseInt(this.estimateFrom.value.tax) * 0.01));
      this.tax2 = Math.ceil(this.subtotal * (parseInt(this.estimateFrom.value.tax2) * 0.01));
      this.discount = Math.ceil(this.subtotal * (parseInt(this.estimateFrom.value.discount) * 0.01));
      this.totalCost = this.subtotal + this.tax1 + this.tax2;
      this.totalCost = this.totalCost - this.discount;
    });
    // this.taxCalc();
  }
 
  addEmployee() {
    if (this.estimateFrom.get('client').valid ) {
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.createEstimate();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.estimateFrom.value;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
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
          this.referenceService.basicAlert(resp.message, 'Estimate created successfully');
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
    }
      else {
        this.referenceService.basicAlert("New HRMS", "Please Choose the client to create Estimate");
      }
  };

  isValid(field: string) {
    let formField = this.estimateFrom.get(field);
    return formField.valid || formField.pristine;
  };

  nameValidator(control: FormControl): { [s: string]: boolean } {
    if (!control.value.match("^[a-zA-Z ,.'-]+$")) {
      return { invalidName: true };
    }
    else {
      this.username1 = false;
    }
  };

  passwordValidator(control: FormControl): { [s: string]: boolean } {
    if (!control.value.match('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$')) {
     //console.log(control.value);
      return { invalidPassword: true };
    }
    else {
     //console.log(control.value);
      this.password1 = false;
    }
  };

  emailValidator(control: FormControl): { [s: string]: boolean } {
    if (!(control.value.toLowerCase().match('[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}'))) {
      return { invalidEmail: true };
    }
    else {
      this.email1 = false;
    }
  };

  phoneValidator(control: FormControl): { [s: string]: boolean } {
    if (!(control.value.match('[0-9]+'))) {
      return { invalidPhone: true };
    }
    else {
      this.phone1 = false;
    }
  };
}

