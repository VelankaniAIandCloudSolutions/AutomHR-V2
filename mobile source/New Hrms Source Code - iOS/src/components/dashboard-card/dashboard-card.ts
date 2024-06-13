import { Component,Input } from '@angular/core';
import { NavController ,ViewController} from 'ionic-angular';
import { PopoverController } from 'ionic-angular';

/**
 * Generated class for the DashboardCardComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'dashboard-card',
  templateUrl: 'dashboard-card.html'
})
export class DashboardCardComponent {
  
  @Input() icon:string;
  @Input() amount:string;
  @Input() name:string;
  @Input() dashboard:string;
  @Input() employeeList:string;
  public colorCode:any;
  
    constructor(public navCtrl:NavController,public popoverCtrl: PopoverController) {
   //console.log('Hello DashboardCardComponent Component');
    this.colorCode = {
      'background-color': localStorage.getItem('colorCode'),
    }
  }
  
}

