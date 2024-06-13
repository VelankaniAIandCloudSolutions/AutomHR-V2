import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, TabHighlight } from 'ionic-angular';
import { FormGroup, Validators, FormBuilder, FormControl, FormArray } from '../../../node_modules/@angular/forms';
import { ReferenceService } from '../../providers/referenceService';
import { ApiService } from '../../providers/apiServices';
import { HTTP } from '../../../node_modules/@ionic-native/http';
import { LoginPage } from '../login/login';

/**
 * Generated class for the EditprofilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @Component({
  selector: 'page-editprofile',
  templateUrl: 'editprofile.html',
})
export class EditprofilePage {

  userForm: FormGroup;
  public loading;
  public token;
  public url;
  public basicInformation = true;
  public experienceInformation = false;
  public educationInformation = false;
  public user: any = {};
  public education_details: any = {};
  public temp: any = {};
  public experience_information: any = {};
  public editprofilePage = this;
  public currentTime;
  public day;
  public year;
  public month;
  public educationfrom:any =[];
  public experiencefrom:any =[];
  public keywords:any={};
  public primaryColor:any;
  public gender = [
  {
    name : "Male",
    value: "Male"
  },
  {
    name : "Female",
    value: "Female"
  }]

  constructor(public navCtrl: NavController, public navParams: NavParams, public fb: FormBuilder, public referenceService: ReferenceService, public apiService: ApiService, public http: HTTP) {
  
    this.user = this.navParams.get('profile');
    this.user.education_details = JSON.parse(this.user.education_details);
    this.user.experience_information = JSON.parse(this.user.experience_information);
    this.keywords = JSON.parse(localStorage.getItem('keywords'));
    this.userForm = fb.group({
      'education': fb.array([this.educationForm()]),
      'experience': fb.array([this.experienceForm()])
    });
    this.primaryColor = localStorage.getItem('primary_color');

    if (this.user.education_details != []) {
      const control = <FormArray>this.userForm.controls.education;
      control.removeAt(0)
      this.user.education_details.forEach((value, key) => {
        const control = <FormArray>this.userForm.controls.education;
        // control.value[key].patchValue(value)
        control.push(this.educationForm());
      });
      this.userForm.patchValue({
        education: this.user.education_details
      })
    }
    if (this.user.experience_information != []) {
      const control = <FormArray>this.userForm.controls.experience;
      control.removeAt(0)
      this.user.experience_information.forEach((value, key) => {
        const control = <FormArray>this.userForm.controls.experience;
        // control.value[key].patchValue(value)
        control.push(this.experienceForm());
      });
      this.userForm.patchValue({
        experience: this.user.experience_information
      })
     //console.log(this.userForm.value)
    }
    this.currentTime = new Date();
    this.year = this.currentTime.getFullYear();
    ////console.log(this.year)
    this.month =this.currentTime.getMonth()+1 ;
    this.day =this.currentTime.getDate()-1;
    if (this.month < 10) { this.month = '0' + this.month; }
    if (this.day < 10) { this.day = '0' + this.day; }
    this.currentTime = this.year+'-'+this.month+'-'+this.day;
   //console.log(this.currentTime)

  };

  getHeaderStyle(){
    return { 'background':   this.primaryColor}
  };
  
  educationForm(): FormGroup {
    return this.fb.group({
      'institution': new FormControl('',Validators.required),
      'subject': new FormControl('',Validators.required),
      'start_year': new FormControl('',Validators.required),
      'complete_year': new FormControl('',Validators.required),
      'degree': new FormControl('',Validators.required),
      'grade': new FormControl('',Validators.required),
    })
  };

  experienceForm(): FormGroup {
    return this.fb.group({
      'company': new FormControl('',Validators.required),
      'location': new FormControl('',Validators.required),
      'jop_position': new FormControl('',Validators.required),
      'period_from': new FormControl('',Validators.required),
      'period_to': new FormControl('',Validators.required),
    })
  }

  ionViewWillEnter() {
   //console.log('ionViewWillEnter EditprofilePage');
  }

  BasicInformation() {
    this.basicInformation = true;
    this.experienceInformation = false;
    this.educationInformation = false;
  }

  EducationInformation() {
    this.basicInformation = false;
    this.educationInformation = true;
    this.experienceInformation = false;
  }
  ExperienceInformation() {
    var keepGoing = true;
    var a =0;
    this.userForm.value.education.forEach((value) => {
      if(keepGoing)
      if(value.institution!='' && value.subject!='' && value.start_year!=''&&value.complete_year!=''&& value.degree!=''&&value.grade!=''){
       a++;
      }
      else{
        this.referenceService.basicAlert("New HRMS","Please fill all the empty fields");
        keepGoing = false;
      }
     //console.log(this.userForm.value.education.length)
      if(this.userForm.value.education.length==a){
        this.basicInformation = false;
        this.educationInformation = false;
        this.experienceInformation = true;
      }
   });
  //console.log(this.userForm.value.education.length)
   if(this.userForm.value.education.length==0){
    this.basicInformation = false;
    this.educationInformation = false;
    this.experienceInformation = true;
  }
  };

  selectFrom() {
    this.userForm.value.education.forEach((value,key) => {
      this.educationfrom[key] = value.start_year;
   });
  };

  selectFrom1() {
    this.userForm.value.experience.forEach((value,key) => {
      this.experiencefrom[key] = value.period_from;
   });
  };

  addEducation(): void {
    const control = <FormArray>this.userForm.controls.education;
    control.push(this.educationForm());
  };

  removeEducation(value): void {
   //console.log(value)
    const control = <FormArray>this.userForm.controls.education;
    control.removeAt(value);
  };
  removeExperience(value): void {
   //console.log(value)
    const control = <FormArray>this.userForm.controls.experience;
    control.removeAt(value);
  };

  addExperience() {
    const control = <FormArray>this.userForm.controls.experience;
    control.push(this.experienceForm());
  };
  moveFocus(nextElement) {
   //console.log(nextElement)
    nextElement.setFocus();
  };
  
  submit() {
    var keepGoing = true;
    var a =0;
    this.userForm.value.experience.forEach((value) => {
      if(keepGoing)
      if(value.company!='' && value.location!='' && value.period_from!=''&&value.period_to!=''&& value.jop_position!=''){
       a++;
      }
      else{
        this.referenceService.basicAlert("New HRMS","Please fill all the empty fields");
        keepGoing = false;
      }
     if(this.userForm.value.experience.length==a){
      this.editemployee();
    }
   });
   if(this.userForm.value.experience.length==0){
    this.editemployee();
  }
  };

  editemployee(){
    this.user.experience_information = [];
    this.user.education_details = [];
    this.userForm.value.education.forEach((value,key) => {
       this.user.education_details.push(value)
     //console.log(this.user)
    });
    this.userForm.value.experience.forEach((value) => {
      this.user.experience_information.push(value)
     //console.log(this.user)
    });
   //console.log(this.user)
    this.loading = this.referenceService.loading();
    this.loading.present();
    this.url = this.apiService.editProfile();
    this.token = localStorage.getItem('token');
    var token = { "token": this.token };
    var data = this.user;
   //console.log(data);
    this.http.post(this.url, data, token).then(data => {
      var resp = JSON.parse(data.data);
     //console.log(resp)
      if(resp.message == "Invalid token or Token missing"){
        this.referenceService.basicAlert("Session Expired", 'Oops!! your session is expired please login and try again');
        this.loading.dismiss();
        // this.navCtrl.popAll();
        localStorage.clear();
        this.navCtrl.setRoot(LoginPage);
      }
      if(resp.message == "Success"){
      if (resp.status_code == 1) {
        this.loading.dismiss();
        this.referenceService.basicAlert(resp.message, 'Profile updated successfully');
        this.navCtrl.pop();
      }
      else if (resp.status_code == 0) {
        this.loading.dismiss();
        this.referenceService.basicAlert('Something went wrong', resp.message);
      }
    }
    })
      .catch(error => {
        this.loading.dismiss();
        this.referenceService.basicAlert("New HRMS", 'Unable to reach server at the moment');
       //console.log("error=" + error);
       //console.log("error=" + error.error);
       //console.log("error=" + error.headers);
      });
  }

  emailValidator(control: FormControl): {[s: string]: boolean} {
    if (!(control.value.toLowerCase().match('[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}'))) {
      return {invalidEmail: true};
    }
  }
  phoneValidator(control: FormControl): {[s: string]: boolean} {
    if (!(control.value.match('[0-9]+'))) {
      return {invalidPhone: true};
    }
  }
}




