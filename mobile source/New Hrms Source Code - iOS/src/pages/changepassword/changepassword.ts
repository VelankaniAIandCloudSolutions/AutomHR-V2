import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, AlertController } from 'ionic-angular';
import { FormGroup, Validators, FormBuilder, FormControl } from '../../../node_modules/@angular/forms';
import { ApiService } from '../../providers/apiServices';
import { ReferenceService } from '../../providers/referenceService';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { HomePage } from '../home/home';
import { LoginPage } from '../login/login';

/**
 * Generated class for the ChangepasswordPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
    selector: 'page-changepassword',
    templateUrl: 'changepassword.html',
})
export class ChangepasswordPage {
    public loading;
    public url;
    private passwordForm: FormGroup;
    public token;
    public current_password1 = false;
    public confirm_password1 = false;
    public new_password1 = false;
    public primaryColor: any;
    public keywords:any={};
    constructor(public navCtrl: NavController, public navParams: NavParams, private fb: FormBuilder, private alertCtrl: AlertController, private apiService: ApiService, private referenceService: ReferenceService, private http: HTTP) {
        this.passwordForm = fb.group({
            'current_password': ['', [Validators.required, this.currentpasswordValidator.bind(this)]],
            'new_password': ['', [Validators.required, Validators.minLength(8), Validators.maxLength(15), this.newpasswordValidator.bind(this)]],
            'confirm_password': ['', [Validators.required, Validators.minLength(8), Validators.maxLength(15), this.confirmpasswordValidator.bind(this)]],
        });
        this.primaryColor = localStorage.getItem('primary_color');
        this.keywords = JSON.parse(localStorage.getItem('keywords'));
    }

    ionViewDidLoad() {
       //console.log('ionViewDidLoad ChangepasswordPage');
    };

    getHeaderStyle() {
        return { 'background': this.primaryColor }
    };

    changePassword() {
        if (this.passwordForm.get('new_password').valid && this.passwordForm.get('confirm_password').valid) {
            if (this.passwordForm.get('new_password').value != this.passwordForm.get('current_password').value) {
                if (this.passwordForm.get('new_password').value == this.passwordForm.get('confirm_password').value) {
                    var data = this.passwordForm.value;
                    this.url = this.apiService.changepassword();
                    this.loading = this.referenceService.loading();
                    this.loading.present();
                    this.token = localStorage.getItem('token');
                    var token = { 'token': this.token };
                    this.http.post(this.url, data, token).then(data => {
                        var resp = JSON.parse(data.data);
                       //console.log(resp)
                        if (resp.message == "Invalid token or Token missing") {
                            this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
                            this.loading.dismiss();
                            // this.navCtrl.popAll();
                            localStorage.clear();
                            this.navCtrl.setRoot(LoginPage);
                        }
                        if (resp.message == "Success") {
                            if (resp.status_code == 1) {
                                this.loading.dismiss();
                                this.referenceService.basicAlert(resp.message, 'Password updated successfully');
                                this.navCtrl.setRoot(HomePage);
                            }
                            else if (resp.status_code == 0) {
                                this.loading.dismiss();
                                this.referenceService.basicAlert("New HRMS", 'Current password did not match');
                            }
                        }
                        if (resp.message == "Something went wrong, please try again later.") {
                            this.loading.dismiss();
                            this.referenceService.basicAlert("New HRMS", 'Current password did not match');
                        }
                    })
                        .catch(error => {
                            this.loading.dismiss();
                            this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
                        })
                }
                else {
                    this.referenceService.basicAlert("New HRMS", "Passwords did not match");
                }
            }
            else {
                this.referenceService.basicAlert("New HRMS", "current password and new password should not be same");
            }
        }
        else {
            if (!this.passwordForm.get('new_password').valid) {
                this.referenceService.basicAlert("New HRMS", "Password must be atleast 8 characters long. To make it stronger,use upper and lower case letters,numbers and symbols");
            }
            else if (!this.passwordForm.get('confirm_password').valid) {
                this.referenceService.basicAlert("New HRMS", "Password must be atleast 8 characters long. To make it stronger,use upper and lower case letters,numbers and symbols");
            }
        }
    };

    isValid(field: string) {
        let formField = this.passwordForm.get(field);
        return formField.valid || formField.pristine;
    };

    currentpasswordValidator(control: FormControl): { [s: string]: boolean } {
        if (!control.value.match('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$')) {
            return { invalidPassword: true };
        }
        else {
            this.current_password1 = false;
        }
    };

    newpasswordValidator(control: FormControl): { [s: string]: boolean } {
        if (!control.value.match('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$')) {
            return { invalidPassword: true };
        }
        else {
            this.new_password1 = false;
        }
    };

    confirmpasswordValidator(control: FormControl): { [s: string]: boolean } {
        if (!control.value.match('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$')) {
            return { invalidPassword: true };
        }
        else {
            this.confirm_password1 = false;
        }
    };
}
