import { NgModule,CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import {BrowserModule}  from '@angular/platform-browser';
import { DashboardCardComponent } from './dashboard-card/dashboard-card';
import { IonicPageModule } from 'ionic-angular';
import { PageHeaderComponent } from './page-header/page-header';
@NgModule({
	declarations: [DashboardCardComponent,
	PageHeaderComponent],
	schemas: [ CUSTOM_ELEMENTS_SCHEMA],
	imports: [BrowserModule,IonicPageModule],
	exports: [
		DashboardCardComponent,
    PageHeaderComponent]
})
export class ComponentsModule {}
