import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

/**
 * Generated class for the NotificationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: 'page-notification',
  templateUrl: 'notification.html',
})
export class NotificationPage {

  public primaryColor:any;
  constructor(public navCtrl: NavController, public navParams: NavParams) {
    this.primaryColor =  localStorage.getItem('primary_color')
  }

  getHeaderStyle(){
    return { 'background': this.primaryColor}
  };
  
  ionViewDidLoad() {
   //console.log('ionViewDidLoad NotificationPage');
  }

}
