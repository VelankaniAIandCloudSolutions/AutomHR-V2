import { Component, Input } from '@angular/core';
import { PopoverController, NavController } from '../../../node_modules/ionic-angular/umd';
import { ReferenceService } from '../../providers/referenceService';
import { HomePopoverPage } from '../../pages/home/home';
import { ProfilePage } from '../../pages/profile/profile';
import { LoginPage } from '../../pages/login/login';

/**
 * Generated class for the PageHeaderComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'page-header',
  templateUrl: 'page-header.html'
})
export class PageHeaderComponent {

  @Input() bgColor :string;
  text: string;
  @Input() dashboard:string;
  @Input() title:string;
  @Input() addIcon:string;

  constructor() {
   //console.log('Hello PageHeaderComponent Component');
  }

  // presentPopover(myEvent) {
  //     let popover = this.popoverCtrl.create(HomePopoverPage);
  //     popover.present({
  //       ev: myEvent
  //     });
  //     popover.onDidDismiss(data => {
  //       if (data == "profile") {
  //         this.navCtrl.push(ProfilePage);
  //       }
  //       else if (data == "logout") {
  //         let alert = this.referenceservice.confirmAlert("Confirm", "Are you sure want to logout?");
  //         alert.present();
  //         alert.onDidDismiss((data) => {
  //           if (data) {
  //             localStorage.clear();
  //             this.navCtrl.push(LoginPage);
  //           }
  //         })
  //       }
  //     })
  //   };
}
