import { BrowserModule } from "@angular/platform-browser";
import { ErrorHandler, NgModule } from "@angular/core";
import { IonicApp, IonicErrorHandler, IonicModule } from "ionic-angular";
import { AndroidPermissions } from "@ionic-native/android-permissions";

import { MyApp } from "./app.component";
import { HomePage, HomePopoverPage } from "../pages/home/home";
import { ListPage } from "../pages/list/list";

import { ProgressBarModule } from "angular-progress-bar"
import { StatusBar } from "@ionic-native/status-bar";
import { SplashScreen } from "@ionic-native/splash-screen";
import { Geolocation } from "@ionic-native/geolocation";
import { LoginPage } from "../pages/login/login";
import { Angular2FontawesomeModule } from "angular2-fontawesome/angular2-fontawesome";
import { ForgotpasswordPage } from "../pages/forgotpassword/forgotpassword";
import { ComponentsModule } from "../components/components.module";
import { HTTP } from "@ionic-native/http";
import { ApiService } from "../providers/apiServices";
import { ReferenceService } from "../providers/referenceService";
import { EmployeelistPage, PopoverPage, FilterModalPage, EditEmployeePage } from "../pages/employeelist/employeelist";
// import { TruncatePipe } from './truncate.pipe';
import { AddemployeePage } from "../pages/addemployee/addemployee";
import { DepartmentsPage } from "../pages/departments/departments";
import { AddDepartmentPage } from "../pages/add-department/add-department";
import { HolidaysPage, EditHolidayPage } from "../pages/holidays/holidays";
import { AddHolidayPage } from "../pages/add-holiday/add-holiday";
import { LeaveRequestPage, LeaveRequestMoadlPage, LeaveFilterPage } from "../pages/leave-request/leave-request";
import { AddLeaveRequestPage } from "../pages/add-leave-request/add-leave-request";
import { DesignationsPage } from "../pages/designations/designations";
import { AddDesignationsPage } from "../pages/add-designations/add-designations";
import { ProfilePage, BasicInfoPage, PersonalInfoPage, BankInfoPage, EmergencyPage } from "../pages/profile/profile";
import { EditprofilePage } from "../pages/editprofile/editprofile";
import { PayslipPage } from "../pages/payslip/payslip";
import { SettingsPage } from "../pages/settings/settings";
import { ChangepasswordPage } from "../pages/changepassword/changepassword";
import { NotificationPage } from "../pages/notification/notification";
import { AttendancePage } from "../pages/attendance/attendance";
import { AttendanceListPage } from "../pages/attendance-list/attendance-list";
import { AttendanceDetailsPage, AttendanceDetailsFilterPage } from "../pages/attendance-details/attendance-details";
import { FileTransfer, FileUploadOptions, FileTransferObject } from "@ionic-native/file-transfer";
import { Camera, CameraOptions } from "@ionic-native/camera";
import { File } from "@ionic-native/file";
import { Network } from "@ionic-native/network";
import { ActionSheet } from "../../node_modules/@ionic-native/action-sheet";
import { PipesModule } from "../pipes/pipes.module";
import { ClientsPage, EditClientPage, ClientPopoverPage, ClientFilterPage } from "../pages/clients/clients";
import { AddSalaryPage } from "../pages/add-salary/add-salary";
import { EmployeesalaryPage, EditSalaryPage, SalaryFilterPage } from "../pages/employeesalary/employeesalary";
import { LocalNotifications } from "@ionic-native/local-notifications";
import { VoiceCallPage } from "../pages/voicecall/voicecall";
import { IncomingCallPage } from "../pages/incomingcall/incomingcall";
import { VideoCallPage } from "../pages/videocall/videocall";
import { ChatPage } from "../pages/chat/chat";
import { ChatListPage } from "../pages/chat-list/chat-list";
import { ProjectlistPage, ProjectEditPage, ProjectFilterPage } from "../pages/projectlist/projectlist";
import { ProjectviewPage, CalendarModalPage } from "../pages/projectview/projectview";
import { ContactPage, GroupModalPage } from "../pages/contact/contact";
import { OneSignal } from "@ionic-native/onesignal";
import { TaskDetailsPage, TaskEditPage, TaskPopoverPage } from "../pages/task-details/task-details";
import { TasksPage } from "../pages/tasks/tasks";
import { InvoicePage } from "../pages/invoice/invoice";
import { InvoiceListPage, EditInvoicePage, InvoiceFilterPage } from "../pages/invoicelist/invoicelist";
import { EstimatesPage } from "../pages/estimates/estimates";
import { EstimatesListPage, EstimatesFilterPage, EstimatesEditPage } from "../pages/estimateslist/estimateslist";
import { AddclientPage } from "../pages/addclient/addclient";
import { SelectProjectPage } from "../pages/selectproject/selectproject";
import { ClientProfilePage } from "../pages/clientprofile/clientprofile";
import { FilterPipe, SortByPipe, OrderByPipe, ListPipe,KeysPipe } from "./filter.pipe";
import { TimeSheetListPage, TimeSheetModalPage, TimeSheetEditPage, TimeSheetFilterPage } from "../pages/timesheets/timesheetslist";
import { AddTimesheetsPage } from "../pages/add-timesheet/add-timesheet";
import { NativePageTransitions, NativeTransitionOptions } from "@ionic-native/native-page-transitions";
import { NativeAudio } from "@ionic-native/native-audio";
import { Media, MediaObject } from "@ionic-native/media";
import { CreateProjectPage } from "../pages/create-project/createproject";
import { CreateTaskPage } from "../pages/create-task/createtask";
import { CreateInvoicePage } from "../pages/create-invoice/createinvoice";
import { CreateEstimatePage } from "../pages/create-estimate/createestimate";
import { PaymentListPage, PaymentDetailsPage } from "../pages/paymentlist/paymentlist";
import { ExpensesListPage, ExpensesEditPage, ExpenseDetailsPage } from "../pages/expenseslist/expenseslist";
import { CreateExpensesPage } from "../pages/create-expenses/createexpenses";
import { FilePath } from "@ionic-native/file-path";
import { Base64 } from "@ionic-native/base64";
import { CalendarModule } from "ionic3-calendar-en";
import { AttendanceInfoPage } from "../pages/attendance_info/attendance_info";
import { TimesheetContactPage } from "../pages/timesheet-list/timesheet-list";
import { ChangeLanguagePage } from "../pages/change-language/change-language";

@NgModule({
  declarations: [
    MyApp,
    HomePage,
    ListPage,
    ListPipe,
    KeysPipe,
    HomePopoverPage,
    LoginPage,
    AddemployeePage,
    PopoverPage,
    AddDepartmentPage,
    DepartmentsPage,
    FilterModalPage,
    EditSalaryPage,
    HolidaysPage,
    AddHolidayPage,
    EmployeelistPage,
    LeaveRequestMoadlPage,
    AddLeaveRequestPage,
    LeaveRequestPage,
    DesignationsPage,
    ClientsPage,
    AddDesignationsPage,
    ForgotpasswordPage,
    LeaveFilterPage,
    SalaryFilterPage,
    PayslipPage,
    ProfilePage,
    EditEmployeePage,
    EditprofilePage,
    SettingsPage,
    ChangepasswordPage,
    HomePopoverPage,
    NotificationPage,
    AttendancePage,
    AttendanceListPage,
    AttendanceDetailsPage,
    EditHolidayPage,
    AddSalaryPage,
    EmployeesalaryPage,
    VoiceCallPage,
    VideoCallPage,
    ChatListPage,
    ChatPage,
    IncomingCallPage,
    ContactPage,
    ProjectviewPage,
    ProjectlistPage,
    TaskDetailsPage,
    AttendanceDetailsFilterPage,
    InvoicePage,
    InvoiceListPage,
    EstimatesPage,
    AddclientPage,
    EstimatesListPage,
    TasksPage,
    ClientProfilePage,
    SelectProjectPage,
    FilterPipe,
    OrderByPipe,
    GroupModalPage,
    SortByPipe,
    TimeSheetListPage,
    TimeSheetModalPage,
    TimeSheetEditPage,
    TimeSheetFilterPage,
    AddTimesheetsPage,
    CreateProjectPage,
    ProjectEditPage,
    CreateTaskPage,
    TaskEditPage,
    TaskPopoverPage,
    EditClientPage,
    ClientPopoverPage,
    CreateInvoicePage,
    CreateEstimatePage,
    CalendarModalPage,
    ClientFilterPage,
    ProjectFilterPage,
    PaymentListPage,
    PaymentDetailsPage,
    ExpensesListPage,
    CreateExpensesPage,
    ExpensesEditPage,
    EditInvoicePage,
    InvoiceFilterPage,
    EstimatesFilterPage,
    ExpenseDetailsPage,
    EstimatesEditPage,
    BasicInfoPage,
    PersonalInfoPage,
    BankInfoPage,
    EmergencyPage,
    AttendanceInfoPage,
    TimesheetContactPage,
    ChangeLanguagePage,
  ],
  imports: [
    BrowserModule,
    ComponentsModule,
    PipesModule,
    ProgressBarModule,
    Angular2FontawesomeModule,
    IonicModule.forRoot(MyApp),
    CalendarModule
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    HomePage,
    HomePopoverPage,
    ListPage,
    PopoverPage,
    AddemployeePage,
    DepartmentsPage,
    HolidaysPage,
    ClientsPage,
    AddHolidayPage,
    AddDepartmentPage,
    AddLeaveRequestPage,
    FilterModalPage,
    EmployeelistPage,
    PayslipPage,
    LeaveFilterPage,
    LoginPage,
    LeaveRequestMoadlPage,
    LeaveRequestPage,
    DesignationsPage,
    SalaryFilterPage,
    AddDesignationsPage,
    ForgotpasswordPage,
    EditSalaryPage,
    ProfilePage,
    EditEmployeePage,
    EditprofilePage,
    SettingsPage,
    ChangepasswordPage,
    HomePopoverPage,
    NotificationPage,
    AttendancePage,
    AttendanceListPage,
    AttendanceDetailsPage,
    EditHolidayPage,
    AddSalaryPage,
    EmployeesalaryPage,
    IncomingCallPage,
    VoiceCallPage,
    VideoCallPage,
    ChatListPage,
    ProjectviewPage,
    ChatPage,
    ContactPage,
    ProjectlistPage,
    AttendanceDetailsFilterPage,
    TaskDetailsPage,
    InvoicePage,
    InvoiceListPage,
    EstimatesPage,
    EstimatesListPage,
    AddclientPage,
    TasksPage,
    ClientProfilePage,
    SelectProjectPage,
    GroupModalPage,
    TimeSheetListPage,
    TimeSheetModalPage,
    AddTimesheetsPage,
    CreateProjectPage,
    TimeSheetFilterPage,
    TimeSheetEditPage,
    ProjectEditPage,
    CreateTaskPage,
    TaskEditPage,
    TaskPopoverPage,
    EditClientPage,
    ClientPopoverPage,
    CreateInvoicePage,
    CreateEstimatePage,
    CalendarModalPage,
    ClientFilterPage,
    ProjectFilterPage,
    PaymentListPage,
    PaymentDetailsPage,
    ExpensesListPage,
    CreateExpensesPage,
    ExpensesEditPage,
    EditInvoicePage,
    InvoiceFilterPage,
    EstimatesFilterPage,
    ExpenseDetailsPage,
    EstimatesEditPage,
    BasicInfoPage,
    PersonalInfoPage,
    BankInfoPage,
    EmergencyPage,
    AttendanceInfoPage,
    TimesheetContactPage,
    ChangeLanguagePage,
  ],
  providers: [
    StatusBar,
    HTTP,
    Geolocation,
    ApiService,
    Network,
    OneSignal,
    ReferenceService,
    File,
    LocalNotifications,
    FileTransfer,
    AndroidPermissions,
    ActionSheet,
    Camera,
    SplashScreen,
    FilePath,
    NativePageTransitions,
    NativeAudio,
    Base64,
    Media,
    { provide: ErrorHandler, useClass: IonicErrorHandler }
  ]
})
export class AppModule { }
