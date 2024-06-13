import { LoadingController, AlertController } from 'ionic-angular';
import { Injectable } from '@angular/core';

@Injectable()

export class ReferenceService {
  host = "https://dreamguys.co.in";
  // baseurl = "https://dreamguys.co.in/demo/smart-hrms/api/appuser/";
  constructor(private loadingCtrl: LoadingController, private alertCtrl: AlertController) {
    //console.log("welcome! Reference Service");
  }

  loading() {
    let loading = this.loadingCtrl.create({
      content: `
            <div class="custom-spinner-container">
            <div class="custom-spinner-box"></div>
          </div>
           `,
    });
    return loading;
  };

  basicAlert(title, message) {
    let alert = this.alertCtrl.create({
      title: title,
      subTitle: message,
      buttons: ['Ok']
    });
    alert.present();
  }
  confirmAlert(title, message) {
    let alert = this.alertCtrl.create({
      title: title,
      message: message,
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel',
          handler: () => {
            alert.dismiss(false);
            return false;
          }   
        },
        {
          text: 'Ok',
          handler: () => {
            alert.dismiss(true);
            var text = true;
            return false;
          }
        }
      ]
    });
    return alert;
  }
}

