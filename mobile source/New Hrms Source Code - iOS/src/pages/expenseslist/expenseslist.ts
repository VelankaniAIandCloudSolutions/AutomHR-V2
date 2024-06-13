import { Component, ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams, ModalController, Content, ViewController } from 'ionic-angular';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '@ionic-native/http';
import { LoginPage } from '../login/login';
import { AddSalaryPage } from '../add-salary/add-salary';
import { PayslipPage } from '../payslip/payslip';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { InvoicePage } from '../invoice/invoice';
import { CreateInvoicePage } from '../create-invoice/createinvoice';
import { CreateExpensesPage } from '../create-expenses/createexpenses';

/**
 * Generated class for the EmployeesalaryPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-expenseslist',
  templateUrl: 'expenseslist.html',
})
export class ExpensesListPage {
  @ViewChild(Content) content: Content;
  public role: any;
  public roleId: any;
  public primaryColor: any;
  public invoiceList: any;
  public loading: any;
  public url: any;
  public token: any;
  public expenseList: any;
  public resp: any;
  pageNumber = 1;
  time = 0;
  page = false;
  noData = false;
  public keywords : any ={};
  cssClass: string;
  constructor(public navCtrl: NavController, public referenceservice: ReferenceService, public modalCtrl: ModalController, public apiService: ApiService, public http: HTTP, public navParams: NavParams) {
    this.role = localStorage.getItem('role');
    this.roleId = localStorage.getItem('role_id');
    this.primaryColor = localStorage.getItem('primary_color');
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
  }

  ionViewDidLoad() {
    this.applyClassBySelection('bounceInRight');
  }
  ionViewWillEnter() {
    this.time = 0;
    this.page = false;
    this.loading = this.referenceservice.loading();
    this.loading.present();
    this.url = this.apiService.expenseList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "No result were found") {
          this.noData = true;
        }
        else {
          this.noData = false;
        }
        if (this.resp.message == "Success") {
          this.expenseList = this.resp.data.list;
         //console.log(this.expenseList);
          this.expenseList.forEach(element => {
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
                   element.amount = resp.data;
                }
               //console.log(resp);
              });
          });
          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          }
          else {
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
        this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error  =" + error.headers);
      });
  };

  doRefresh(refresher) {
    this.cssClass = '';
    this.time = 0;
    this.page = false;
    this.url = this.apiService.expenseList();
    this.token = localStorage.getItem('token')
   //console.log(this.token);
    var token = { 'token': this.token };
    var data = { page: this.pageNumber }
    this.http.post(this.url, data, token)
      .then(data => {
        this.resp = JSON.parse(data.data);
       //console.log(this.resp)
        // this.employeeList = this.resp.data.list;
        if (this.resp.message == "Invalid token or Token missing") {
          this.referenceservice.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
          // this.navCtrl.popAll();
          localStorage.clear();
          this.navCtrl.setRoot(LoginPage);
        }
        if (this.resp.message == "No result were found") {
          this.noData = true;
        }
        else {
          this.noData = false;
        }
        if (this.resp.message == "Success") {
          refresher.complete();
          this.expenseList = this.resp.data.list;
         //console.log(this.expenseList);
          this.expenseList.forEach(element => {
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
                   element.amount = resp.data;
                }
               //console.log(resp);
              });
          });
          this.content.scrollToTop();
          if (this.resp.data.list.length == 0) {
            this.noData = true;
          }
          else {
            this.noData = false;
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
       //console.log("error  =" + error.headers);
      });
  }
  applyClassBySelection(effect: string): void {
    this.cssClass = "animated " + effect;
  }

  editExpense(expense) {
    this.cssClass = '';
    this.time = 0;
    this.navCtrl.push(ExpensesEditPage, {
      expense: expense
    });

  }
  openexpense(expense) {
    this.cssClass = '';
    this.time = 0;
    this.navCtrl.push(ExpenseDetailsPage, {
      expense: expense
    });
  };

  addExpenses() {
    this.cssClass = '';
    this.time = 0;
    this.navCtrl.push(CreateExpensesPage);
  }


  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };
  delete(expense) {
    this.cssClass = '';
    this.time = 0;
    let alert = this.referenceservice.confirmAlert("Confirm Delete", "Do you want to continue to delete this Expense");
    alert.present();
    alert.onDidDismiss((data) => {
      if (data) {
       //console.log(data);
        this.url = this.apiService.deleteExpense();
        var token = { 'token': this.token };
        var empData = { expense: expense.expense_id }
        this.http.post(this.url, empData, token)
          .then(data => {
           //console.log(data);
            this.resp = JSON.parse(data.data);

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
                var id = expense.expense_id;
                this.referenceservice.basicAlert(this.resp.message, 'Expense Removed successfully');
                // this.ionViewWillEnter();
                var index = this.expenseList.map(x => {
                  return x.expense_id;
                }).indexOf(id);
                this.expenseList.splice(index, 1);
              }
            }
          })
          .catch(error => {
           //console.log(error);
            this.loading.dismiss();
            this.referenceservice.basicAlert("New HRMS", 'Unable to reach server at the moment');
          });
      }
    });
  }

  doInfinite(infiniteScroll) {
    this.cssClass = "";
    this.time = 0;
    setTimeout(() => {
      if (this.resp.data.next_page != -1) {
       //console.log('Begin async operation');
        this.url = this.apiService.expenseList();
        this.token = localStorage.getItem('token')
       //console.log(this.token);
        var token = { 'token': this.token };
        var data = { page: this.resp.data.next_page }
        this.http.post(this.url, data, token)
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
                  this.resp.data.list[i].amount = resp.data;
                }
               //console.log(resp);
              });
              this.expenseList.push(this.resp.data.list[i]);
              this.time = this.time + 0.2;
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

};



@Component({
  selector: 'page-expenseslist',
  templateUrl: 'expenseslistEdit.html',
})
export class ExpensesEditPage {

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
  expensesForm: FormGroup;
  category: any;
  projects: any;
  currentTime: any;
  year: any;
  day: any;
  month: any;
  public primaryColor: any;
  public client = false;
  bill = false;
  invo = false;
  expense: any = {};
  constructor(public navCtrl: NavController, public fb: FormBuilder, public navParams: NavParams, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
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
      'receipt': ['', [Validators.required]],
    });
    this.expense = this.navParams.get('expense');

    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    this.month = this.currentTime.getMonth() + 1;
    this.day = this.currentTime.getDate();
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year + '-' + this.month + '-' + this.day;
    this.primaryColor = localStorage.getItem('primary_color');
  }


  ionViewWillEnter() {
    if (this.expense.billable == 1) {
      this.expense.billable = true;
    }
    else {
      this.expense.billable = false;
    }
    if (this.expense.invoiced == 1) {
      this.expense.invoiced = true;
    }
    else {
      this.expense.invoiced = false;
    }
    if (this.expense.show_client == "Yes") {
      this.expense.show_client = true;
    }
    else {
      this.expense.show_client = false;
    }
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
       //console.log("error=" + error.error); // error message as string
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


  addEmployee() {
    if (this.expense.billable == true) {
      this.expense.billable = 1;
    }
    else {
      this.expense.billable = 0;
    }
    if (this.expense.invoiced == true) {
      this.expense.invoiced = 1;
    }
    else {
      this.expense.invoiced = 0;
    }
    if (this.expense.show_client == true) {
      this.expense.show_client = "Yes";
    }
    else {
      this.expense.show_client = "No";
    }
    if (this.expensesForm.get('amount').valid && this.expensesForm.get('category').valid) {
      this.loading = this.referenceService.loading();
      this.loading.present();
      this.url = this.apiService.editExpense();
      this.token = localStorage.getItem('token');
      var token = { "token": this.token };
      var data = this.expensesForm.value;
      data.expense = this.expense.expense_id;
     //console.log(data);
      this.http.post(this.url, data, token).then(data => {
        var resp = JSON.parse(data.data);
       //console.log(resp);
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
            this.referenceService.basicAlert(resp.message, 'Invoice created successfully');
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
      if (this.expensesForm.get('amount').value == '') {
        this.referenceService.basicAlert("New HRMS", "Please enter the amount in the input field");
      }
      else if (this.expensesForm.get('category').value == '') {
        this.referenceService.basicAlert("New HRMS", "Category must be choosed");
      }
    }
  };
}


// ************************************************** Details *************************************



@Component({
  selector: 'page-expenseslist',
  templateUrl: 'expenseDetail.html',
})
export class ExpenseDetailsPage {
  public filterData: any = {};
  public loading;
  public url;
  public token;
  public resp;
  blackLogo: any;
  public expense: any;
  public primaryColor: any;
  constructor(public viewCtrl: ViewController, public apiService: ApiService, public modalCtrl: ModalController, private referenceservice: ReferenceService, public navParams: NavParams, private http: HTTP) {
    this.primaryColor = localStorage.getItem('primary_color');
    this.expense = this.navParams.get('expense');
    this.blackLogo = localStorage.getItem('black_logo');
  }
  ionViewDidEnter() {

  };

  getHeaderStyle() {
    return { 'background': this.primaryColor }
  };

}