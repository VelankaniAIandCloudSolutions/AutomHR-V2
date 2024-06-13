import { Component, ViewChild } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  ModalController,
  Content,
  ViewController,
  PopoverController
} from "ionic-angular";
import { ReferenceService } from "../../providers/referenceService";
import { ApiService } from "../../providers/apiServices";
import { HTTP } from "@ionic-native/http";
import { LoginPage } from "../login/login";
import { AddSalaryPage } from "../add-salary/add-salary";
import { PayslipPage } from "../payslip/payslip";
import {
  FormGroup,
  FormBuilder,
  Validators,
  FormControl,
  FormArray
} from "@angular/forms";
import { EstimatesPage } from "../estimates/estimates";
import { CreateEstimatePage } from "../create-estimate/createestimate";

/**
 * Generated class for the EmployeesalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-estimateslist",
  templateUrl: "estimateslist.html"
})
export class EstimatesListPage {
  @ViewChild(Content) content: Content;
  public role: any;
  cssClass: any;
  public roleId: any;
  public primaryColor: any;
  public estimatesList: any;
  public loading: any;
  public url: any;
  public token: any;
  public resp: any;
  pageNumber = 1;
  page = false;
  noData = false;
  time = 0;

  constructor(
    public navCtrl: NavController,
    public referenceservice: ReferenceService,
    public modalCtrl: ModalController,
    public apiService: ApiService,
    public http: HTTP,
    public navParams: NavParams
  ) {
    this.role = localStorage.getItem("role");
    this.roleId = localStorage.getItem("role_id");
    this.primaryColor = localStorage.getItem("primary_color");
  }
  ionViewDidLoad() {
    this.applyClassBySelection("bounceInRight");
  }
  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getEstimatesList();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    var data = { page: this.pageNumber };
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       console.log(this.resp);
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "No result were found") {
          this.noData = true;
        } else {
          this.noData = false;
        }
        if (this.resp.message == "Success") {
          this.estimatesList = this.resp.data.list;
         console.log(this.estimatesList);
          this.estimatesList.sort((a, b) => a.estimate_id - b.estimate_id)
          this.estimatesList.forEach(element => {
            this.time = this.time + 0.2;
            element.time = this.time;
            this.url = this.apiService.convertCurrency();
            this.token = localStorage.getItem("token");
            var token = { token: this.token };
            var currency = localStorage.getItem('currency')
            var data = { code: currency, amount: element.amount };
           console.log(data)
            this.http
              .post(this.url, data, token)
              .then(data => {
                var resp = JSON.parse(data.data);
                if (resp.message == "Success") {
                   element.converted_amount = resp.data;
                }
               console.log(resp);
              });
          });

          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          } else {
            this.noData = false;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
        this.loading.dismiss();
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error  =" + error.headers);
      });
  }
  doRefresh(refresher) {
    this.cssClass = '';
    this.page = false;
    this.url = this.apiService.getEstimatesList();
    this.token = localStorage.getItem("token");
   //console.log(this.token);
    var token = { token: this.token };
    var data = { page: this.pageNumber };
    this.http
      .post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp);
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "No result were found") {
          this.noData = true;
        } else {
          this.noData = false;
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.estimatesList = this.resp.data.list;
         //console.log(this.estimatesList);
          this.estimatesList.sort((a, b) => a.estimate_id - b.estimate_id)
          this.estimatesList.forEach(element => {
            this.time = this.time + 0.2;
            element.time = this.time;
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

          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          } else {
            this.noData = false;
          }
          if (this.resp.data.next_page == -1) {
            this.page = true;
          }
        }
      })
      .catch(error => {
        refresher.complete();
        this.referenceservice.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error  =" + error.headers);
      });
  }

  openEstimate(estimate) {
    this.cssClass = "";
    this.navCtrl.push(EstimatesPage, {
      estimate: estimate
    });
  }
  // editSalary(payslip) {
  //   this.navCtrl.push(EditSalaryPage, {
  //     payslip: payslip
  //   })
  // };

  addEstimate() {
    this.cssClass = "";
    this.navCtrl.push(CreateEstimatePage);
  }

  generatePayslip(payslip) {
    this.navCtrl.push(PayslipPage, {
      payslip: payslip
    });
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }

  openModal() {
    this.cssClass = "";
    let modal = this.modalCtrl.create(EstimatesFilterPage);
    modal.onDidDismiss(data => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        } else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getEstimatesList();
          this.token = localStorage.getItem("token");
         //console.log(this.token);
          var token = { token: this.token };
          data.page = 1;
         //console.log(data);
          this.http
            .post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
              this.estimatesList = this.resp.data.list;
              this.estimatesList.sort((a, b) => a.estimate_id - b.estimate_id)
              this.content.scrollToTop();
             //console.log(this.resp);
              if (this.resp.data.next_page == -1) {
                this.page = true;
              }
              if (this.resp.data.list.length == 0) {
                this.noData = true;
              }
              this.loading.dismiss();
            })
            .catch(error => {
              this.content.scrollToTop();
              this.loading.dismiss();
             //console.log("error=" + error.status);
             //console.log("error=" + error.error); // error message as string
             //console.log("error=" + error.headers);
            });
        }
      }
    });
    modal.present();
   //console.log("modal");
  }

  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }
  editEstimate(estimate) {
    this.cssClass = "";
    this.navCtrl.push(EstimatesEditPage, {
      estimate: estimate
    });
  }

  deleteEstimate(estimate) {
    this.cssClass = "";
    let alert = this.referenceservice.confirmAlert(
      "Confirm Delete",
      "Do you want to continue to delete this estimate"
    );
    alert.present();
    alert.onDidDismiss(data => {
      if (data) {
       //console.log(data);
        this.url = this.apiService.deleteEstimate();
        var token = { token: this.token };
        var empData = { est_id: estimate.estimate_id };
        this.http
          .post(this.url, empData, token)
          .then(data => {
           //console.log(data);
            this.resp = JSON.parse(data.data);
            if (this.resp.message == "Invalid token or Token missing") {
              this.referenceservice.basicAlert(
                "Session Expired",
                "Oops!! your session is expired please login and try again"
              );
              // this.navCtrl.popAll();
              this.loading.dismiss();
              localStorage.clear();
              this.navCtrl.setRoot(LoginPage);
            }
            if (this.resp.message == "Success") {
              if (this.resp.status_code == 1) {
                this.loading.dismiss();
                var id = estimate.estimate_id;
                this.referenceservice.basicAlert(
                  this.resp.message,
                  "Estimate Removed successfully"
                );
                // this.ionViewWillEnter();
                var index = this.estimatesList
                  .map(x => {
                    return x.estimate_id;
                  })
                  .indexOf(id);
                this.estimatesList.splice(index, 1);
              }
            }
          })
          .catch(error => {
           //console.log(error);
            this.loading.dismiss();
            this.referenceservice.basicAlert(
              "New HRMS",
              "Unable to reach server at the moment"
            );
          });
      }
    });
  }
  doInfinite(infiniteScroll) {
    this.cssClass = "";
    this.time = 0;
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log("Begin async operation");
        this.url = this.apiService.getEstimatesList();
        this.token = localStorage.getItem("token");
       //console.log(this.token);
        var token = { token: this.token };
        var data = { page: this.resp.data.next_page };
        this.http
          .post(this.url, data, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
            for (var i = 0; i < this.resp.data.list.length; i++) {
              this.resp.data.list[i].time = this.time;
              this.url = this.apiService.convertCurrency();
              this.token = localStorage.getItem("token");
              var token = { token: this.token };
              var currency = localStorage.getItem('currency')
              var data1 = { code: currency, amount: this.resp.data.list[i].amount };
             //console.log(data1)
              this.http
                .post(this.url, data1, token)
                .then(data => {
                  var resp = JSON.parse(data.data);
                  if (resp.message == "Success") {
                    this.resp.data.list[i].converted_amount = resp.data;
                  }
                 //console.log(resp);
                });
              this.estimatesList.push(this.resp.data.list[i]);
              this.time = this.time + 0.2;
            }
            this.estimatesList.sort((a, b) => a.estimate_id - b.estimate_id)
           //console.log(this.resp);
            if (this.resp.data.next_page == -1) {
              this.page = true;
            }
            this.loading.dismiss();
          })
          .catch(error => {
            this.loading.dismiss();
            this.referenceservice.basicAlert(
              "New HRMS",
              "Unable to reach server at the moment"
            );
           //console.log("error=" + error.status);
           //console.log("error=" + error.error); // error message as string
           //console.log("error=" + error.headers);
          });
      } else {
       //console.log("Async operation has ended");
        infiniteScroll.complete();
      }
    }, 1000);
  }
}

// ************************************************** FILTER *************************************

@Component({
  selector: "page-estimateslist",
  templateUrl: "estimateslistfilter.html"
})
export class EstimatesFilterPage {
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
  status: any = [
    {
      status: "Pending"
    },
    {
      status: "Accepted"
    }
  ];
  constructor(
    public viewCtrl: ViewController,
    public apiService: ApiService,
    public modalCtrl: ModalController,
    public popoverCtrl: PopoverController,
    private referenceservice: ReferenceService,
    public navParams: NavParams,
    private http: HTTP
  ) {
    this.primaryColor = localStorage.getItem("primary_color");
  }
  ionViewDidEnter() { }

  moveFocus(nextElement) {
    nextElement.setFocus();
  }

  filter() {
   //console.log(this.filterData.length);
    if (JSON.stringify(this.filterData) == JSON.stringify({})) {
      this.referenceservice.basicAlert(
        "New HRMS",
        "Please fill any field to filter"
      );
    } else {
      this.viewCtrl.dismiss(this.filterData);
    }
  }
  closeFilter() {
   //console.log(this.filterData);
    this.viewCtrl.dismiss("close");
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }
}

// ************************************************** Edit *************************************

@Component({
  selector: "page-estimateslist",
  templateUrl: "estimateslistEdit.html"
})
export class EstimatesEditPage {
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
  public totalCost = 0;
  public phone1 = false;
  estimateForm: FormGroup;
  item: any = {
    item_name: "",
    item_desc: "",
    unit_cost: "",
    item_order: "",
    tax_rate: "",
    item_tax_total: "",
    quantity: "",
    total_cost: "",
    invoice_id: "",
    date_saved: "",
    item_id: ""
  };
  estimate: any = {};
  currentTime: any;
  year: any;
  day: any;
  month: any;
  tax1 = 0;
  tax2 = 0;
  discount = 0;
  taxTotal: any;
  subtotal = 0;
  public primaryColor: any;
  public fixed = false;
  constructor(
    public navCtrl: NavController,
    public fb: FormBuilder,
    public navParams: NavParams,
    public referenceService: ReferenceService,
    public apiService: ApiService,
    public http: HTTP
  ) {
    this.estimateForm = fb.group({
      reference_no: ["", [Validators.required]],
      client: ["", [Validators.required]],
      tax: ["", [Validators.required]],
      tax2: ["", [Validators.required]],
      due_date: ["", [Validators.required]],
      discount: ["", [Validators.required]],
      extra_fee: ["", [Validators.required]],
      currency: ["", [Validators.required]],
      allow_stripe: ["", [Validators.required]],
      notes: ["", [Validators.required]],
      item_details: fb.array([this.itemForm()])
    });
    this.estimate = this.navParams.get("estimate");
   //console.log(this.estimate);
    this.estimateForm.patchValue;
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) {
      this.month = "0" + this.month;
    }
    if (this.day < 10) {
      this.day = "0" + this.day;
    }
    this.currentTime = this.year + "-" + this.month + "-" + this.day;
    this.primaryColor = localStorage.getItem("primary_color");
    if (this.estimate.items != []) {
      const control = <FormArray>this.estimateForm.controls.item_details;
      control.removeAt(0);
      this.estimate.items.forEach((value, key) => {
        const control = <FormArray>this.estimateForm.controls.item_details;
        // control.value[key].patchValue(value)
       //console.log(this.estimateForm);
        control.push(this.itemForm());
      });
      this.estimateForm.patchValue({
        item_details: this.estimate.items
      });
     //console.log(this.estimateForm);
    }
    this.estimateForm.value.item_details.forEach((element, key) => {
     //console.log(element);
      this.selected[key] = element.item_name;
      ////console.log(element.item_name);
     //console.log(this.selected);
    });
    if (this.estimate.allow_stripe == "No") {
      this.estimate.allow_stripe = false;
    } else {
      this.estimate.allow_stripe = true;
    }
  }

  ionViewWillEnter() {
    this.totalCostCalc();
    ////console.log(this.invoice);
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.getProjectDetails();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    this.http
      .get(this.url, {}, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp.data);
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "Success") {
          this.projectDetails = this.resp.data;
          ////console.log(this.projectDetails);

          this.loading.dismiss();
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }

  itemForm() {
    return this.fb.group({
      item_name: new FormControl("", Validators.required),
      item_id: new FormControl("", Validators.required),
      invoice_id: new FormControl("", Validators.required),
      date_saved: new FormControl("", Validators.required),
      item_desc: new FormControl("", Validators.required),
      quantity: new FormControl("", Validators.required),
      unit_cost: new FormControl("", Validators.required),
      item_order: new FormControl("", Validators.required),
      item_tax_rate: new FormControl("", Validators.required),
      item_tax_total: new FormControl("", Validators.required),
      total_cost: new FormControl("", Validators.required)
    });
  }

  addItem(): void {
    const control = <FormArray>this.estimateForm.controls.item_details;
    control.push(this.itemForm());
  }

  moveFocus(nextElement) {
    nextElement.setFocus();
  }
  change(i) {
   //console.log(this.selected);
    this.projectDetails.saved_item.forEach(element => {
      if (element.item_name == this.selected[i]) {
        this.item = element;
       //console.log(this.item);
        var arrayControl = <FormArray>this.estimateForm.controls.item_details;
        var arr = arrayControl.controls[i];
        arr.patchValue(this.item);
       //console.log(arr);
      }
    });
    this.totalCostCalc();
  }

  qtyChange(i) {
    this.subtotal = 0;
    this.estimateForm.value.item_details.forEach((element, key) => {
      if (key == i) {
        this.item = element;
        this.item.total_cost =
          parseInt(this.item.quantity) * parseInt(this.item.unit_cost);
       //console.log(this.item.total_cost);
       //console.log(this.item.quantity);
       //console.log(this.item.unit_cost);
       //console.log(this.item.total_cost);
       //console.log(this.item.item_tax_rate);

        this.item.item_tax_total = Math.ceil(
          parseInt(this.item.total_cost) *
          (parseInt(this.item.item_tax_rate) * 0.01)
        );
        this.item.total_cost =
          parseInt(this.item.total_cost) + parseInt(this.item.item_tax_total);
       //console.log(this.item.total_cost);
        var arrayControl = <FormArray>this.estimateForm.controls.item_details;
        var arr = arrayControl.controls[i];
        arr.patchValue(this.item);
       //console.log(this.item);
      }
    });
    this.totalCostCalc();
  }

  totalCostCalc() {
    this.subtotal = 0;
    this.estimateForm.value.item_details.forEach(element => {
      this.subtotal = this.subtotal + parseInt(element.total_cost);
      this.tax1 = Math.ceil(
        this.subtotal * (parseInt(this.estimateForm.value.tax) * 0.01)
      );
      this.tax2 = Math.ceil(
        this.subtotal * (parseInt(this.estimateForm.value.tax2) * 0.01)
      );
      this.discount = Math.ceil(
        this.subtotal * (parseInt(this.estimateForm.value.discount) * 0.01)
      );
      this.totalCost = this.subtotal + this.tax1 + this.tax2;
      this.totalCost = this.totalCost - this.discount;
    });
    // this.taxCalc();
  }
  addEmployee() {
    if (this.estimate.allow_stripe == false) {
      this.estimateForm.controls.allow_stripe.setValue("No");
    } else {
      this.estimateForm.controls.allow_stripe.setValue("Yes");
    }
   //console.log(JSON.stringify(this.estimateForm.value));
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.editEstimate();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var data = this.estimateForm.value;
    data.inv_id = this.estimate.invoice_id;
   //console.log(data);
    this.http
      .post(this.url, data, token)
      .then(data => {
        var resp = JSON.parse(data.data);
       //console.log(resp);
        if (resp.message == "Invalid token or Token missing") {
          this.referenceService.basicAlert(
            "Session Expired",
            "Oops!! your session is expired please login and try again"
          );
          // this.navCtrl.popAll();
          this.loading.dismiss();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (resp.message == "Success") {
         //console.log(resp);
          if (resp.status_code == 1) {
            this.loading.dismiss();
            this.referenceService.basicAlert(
              resp.message,
              "Invoice created successfully"
            );
            this.navCtrl.pop();
          } else if (resp.status_code == 0) {
            this.loading.dismiss();
            this.referenceService.basicAlert("Already requested", resp.message);
          }
        }
        if (resp.status_code == -1) {
          this.loading.dismiss();
          this.referenceService.basicAlert("Already requested", resp.message);
        }
      })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert(
          "New HRMS",
          "Unable to reach server at the moment"
        );
       //console.log("error=" + error);
       //console.log("error=" + error.error); // error message as string
       //console.log("error=" + error.headers);
      });
  }
}
