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
  FormArray,
  FormControl
} from "@angular/forms";
import { InvoicePage } from "../invoice/invoice";
import { CreateInvoicePage } from "../create-invoice/createinvoice";

/**
 * Generated class for the EmployeesalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-invoicelist",
  templateUrl: "invoicelist.html"
})
export class InvoiceListPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public invoiceList: any;
  public loading: any;
  public url: any;
  public token: any;
  public invoicesList: any;
  public resp: any;
  public keywords :any={};
  pageNumber = 1;
  time = 0;
  page = false;
  noData = false;
  cssClass: string;
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
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {
    this.applyClassBySelection("bounceInRight");
  }
  ionViewWillEnter() {
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.getInvoicesList();
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
          this.invoicesList = this.resp.data.list;
         console.log(this.invoicesList);
          this.invoicesList.sort((a, b) => a.invoice_id - b.invoice_id);
          this.invoicesList.forEach(element => {
            this.time = this.time + 0.2;
            element.time = this.time;
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
       //console.log("error=" + JSON.stringify(error));
       //console.log("error=" + JSON.stringify(error.error));
       //console.log("error  =" + JSON.stringify(error.headers));
      });
  };

  doRefresh(refresher) {
    this.cssClass = '';
    this.page = false;
    this.url = this.apiService.getInvoicesList();
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
          this.invoicesList = this.resp.data.list;
         //console.log(this.invoicesList);
          this.invoicesList.sort((a, b) => a.invoice_id - b.invoice_id);
          this.invoicesList.forEach(element => {
            this.time = this.time + 0.2;
            element.time = this.time;
            this.url = this.apiService.convertCurrency();
            this.token = localStorage.getItem("token");
            var token = { token: this.token };
            var currency = localStorage.getItem('currency')
            var data = { code: currency, amount: element.total_cost };
            this.http
              .post(this.url, data, token)
              .then(data => {
                var resp = JSON.parse(data.data);
                if (resp.message == "Success") {
                   element.converted_total_cost = resp.data;
                }
               //console.log(this.resp);
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

  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }
  // editSalary(payslip) {
  //   this.navCtrl.push(EditSalaryPage, {
  //     payslip: payslip
  //   })
  // };

  openInvoice(invoice) {
    this.cssClass = "";
    this.navCtrl.push(InvoicePage, {
      invoice: invoice
    });
  }

  editInvoice(invoice) {
    this.cssClass = "";
    this.navCtrl.push(EditInvoicePage, {
      invoice: invoice
    });
  }

  addInvoice() {
    this.cssClass = "";
    this.navCtrl.push(CreateInvoicePage);
  }
  generatePayslip(payslip) {
    this.cssClass = "";
    this.navCtrl.push(PayslipPage, {
      payslip: payslip
    });
  }

  getHeaderStyle() {
    return { background: this.primaryColor };
  }
  deleteInvoice(invoice) {
    this.cssClass = "";
    let alert = this.referenceservice.confirmAlert(
      "Confirm Delete",
      "Do you want to continue to delete this invoice"
    );
    alert.present();
    alert.onDidDismiss(data => {
      if (data) {
       //console.log(data);
        this.url = this.apiService.deleteInvoice();
        var token = { token: this.token };
        var empData = { inv_id: invoice.invoice_id };
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
                var id = invoice.invoice_id;
                this.referenceservice.basicAlert(
                  this.resp.message,
                  "Invoice Removed successfully"
                );
                // this.ionViewWillEnter();
                var index = this.invoicesList
                  .map(x => {
                    return x.invoice_id;
                  })
                  .indexOf(id);
                this.invoicesList.splice(index, 1);
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

  openModal() {
    this.cssClass = "";
    let modal = this.modalCtrl.create(InvoiceFilterPage);
    modal.onDidDismiss(data => {
      if (data != undefined) {
        if (data == "close") {
          this.ionViewWillEnter();
          this.content.scrollToTop();
        } else {
          this.loading = this.referenceservice.loading();
          this.loading.present();
          this.url = this.apiService.getInvoicesList();
          this.token = localStorage.getItem("token");
         //console.log(this.token);
          var token = { token: this.token };
          data.page = 1;
         //console.log(data);
          this.http
            .post(this.url, data, token)
            .then(data => {
              this.resp = JSON.parse(data.data);
             //console.log(this.resp);
             //console.log(data.data)
              this.invoicesList = this.resp.data.list;
             //console.log(this.resp);
              this.invoicesList.sort((a, b) => a.invoice_id - b.invoice_id);
              this.content.scrollToTop();
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

  doInfinite(infiniteScroll) {
    this.cssClass = "";
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log("Begin async operation");
        this.url = this.apiService.getInvoicesList();
        this.token = localStorage.getItem("token");
       //console.log(this.token);
        var token = { token: this.token };
        var data = { page: this.resp.data.next_page };
       //console.log(data);
        this.http
          .post(this.url, data, token)
          .then(data => {
            infiniteScroll.complete();
            this.resp = JSON.parse(data.data);
           //console.log(this.resp);
            this.resp.data.list.forEach(element => {
              element.time = this.time;
              this.invoicesList.push(element);
              this.time = this.time + 0.2;
             //console.log(element);
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
            this.invoicesList.sort((a, b) => a.invoice_id - b.invoice_id);
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

// **************************************************Edit *************************************

@Component({
  selector: "page-invoicelist",
  templateUrl: "invoicelistEdit.html"
})
export class EditInvoicePage {
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
  invoiceFrom: FormGroup;
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
  invoice: any = {};
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
    this.invoiceFrom = fb.group({
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
    this.invoice = this.navParams.get("invoice");
   //console.log(this.invoice);
    this.invoiceFrom.patchValue;
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
    if (this.invoice.items != []) {
      const control = <FormArray>this.invoiceFrom.controls.item_details;
      control.removeAt(0);
      this.invoice.items.forEach((value, key) => {
        const control = <FormArray>this.invoiceFrom.controls.item_details;
        // control.value[key].patchValue(value)
       //console.log(this.invoiceFrom);
        control.push(this.itemForm());
      });
      this.invoiceFrom.patchValue({
        item_details: this.invoice.items
      });
     //console.log(this.invoiceFrom);
    }
    this.invoiceFrom.value.item_details.forEach((element, key) => {
     //console.log(element);
      this.selected[key] = element.item_name;
      ////console.log(element.item_name);
     //console.log(this.selected);
    });
    if (this.invoice.allow_stripe == "No") {
      this.invoice.allow_stripe = false;
    } else {
      this.invoice.allow_stripe = true;
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
    const control = <FormArray>this.invoiceFrom.controls.item_details;
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
        var arrayControl = <FormArray>this.invoiceFrom.controls.item_details;
        var arr = arrayControl.controls[i];
        arr.patchValue(this.item);
       //console.log(arr);
      }
    });
    this.totalCostCalc();
  }

  qtyChange(i) {
    this.subtotal = 0;
    this.invoiceFrom.value.item_details.forEach((element, key) => {
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
        var arrayControl = <FormArray>this.invoiceFrom.controls.item_details;
        var arr = arrayControl.controls[i];
        arr.patchValue(this.item);
       //console.log(this.item);
      }
    });
    this.totalCostCalc();
  }

  totalCostCalc() {
    this.subtotal = 0;
    this.invoiceFrom.value.item_details.forEach(element => {
      this.subtotal = this.subtotal + parseInt(element.total_cost);
      this.tax1 = Math.ceil(
        this.subtotal * (parseInt(this.invoiceFrom.value.tax) * 0.01)
      );
      this.tax2 = Math.ceil(
        this.subtotal * (parseInt(this.invoiceFrom.value.tax2) * 0.01)
      );
      this.discount = Math.ceil(
        this.subtotal * (parseInt(this.invoiceFrom.value.discount) * 0.01)
      );
      this.totalCost = this.subtotal + this.tax1 + this.tax2;
      this.totalCost = this.totalCost - this.discount;
    });
    // this.taxCalc();
  }
  addEmployee() {
    if (this.invoice.allow_stripe == false) {
      this.invoiceFrom.controls.allow_stripe.setValue("No");
    } else {
      this.invoiceFrom.controls.allow_stripe.setValue("Yes");
    }
   //console.log(JSON.stringify(this.invoiceFrom.value));
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.editInvoice();
    this.token = localStorage.getItem("token");
    var token = { token: this.token };
    var data = this.invoiceFrom.value;
    data.inv_id = this.invoice.invoice_id;
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

// ************************************************** FILTER *************************************

@Component({
  selector: "page-invoicelist",
  templateUrl: "invoicelistfilter.html"
})
export class InvoiceFilterPage {
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
      status: "Paid"
    },
    {
      status: "Unpaid"
    },
    {
      status: "Partial"
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
