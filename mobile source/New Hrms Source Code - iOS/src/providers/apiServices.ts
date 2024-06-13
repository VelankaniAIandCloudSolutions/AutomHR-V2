import { Injectable } from "@angular/core";

@Injectable()
export class ApiService {
  host = "http://www.newhrms.com";
  baseurl = "http://hr.laoapps.com/api/";
  constructor() {
   //console.log("welcome! Reference Service");
  }

  login() {
    return this.baseurl + "appuser/login";
  }
  getEmployeeList() {
    return this.baseurl + "api/employee_list";
  }
  getDepartments() {
    return this.baseurl + "api/departments";
  }
  getDesignation() {
    return this.baseurl + "api/designations";
  }
  getHolidays() {
    return this.baseurl + "api/holidays";
  }
  getLeaves() {
    return this.baseurl + "api/leaves";
  }
  addDept() {
    return this.baseurl + "api/create_department";
  }
  addDesignation() {
    return this.baseurl + "api/create_designations";
  }
  addHoliday() {
    return this.baseurl + "api/create_holiday";
  }
  addLeaverequest() {
    return this.baseurl + "api/leave_apply";
  }
  getLeaveTypes() {
    return this.baseurl + "api/leave_type";
  }
  cancelLeaveRequest() {
    return this.baseurl + "api/leave_cancel";
  }
  acceptRejectLeave() {
    return this.baseurl + "api/leave_approve_reject";
  }
  profile() {
    return this.baseurl + "api/profile";
  }
  removeHoliday() {
    return this.baseurl + "api/remove_holiday";
  }
  editHoliday() {
    return this.baseurl + "api/edit_holiday";
  }
  addEmployee() {
    return this.baseurl + "appuser/create_employee";
  }
  editProfile() {
    return this.baseurl + "api/view_profile";
  }
  removeEmployee() {
    return this.baseurl + "api/remove_profile";
  }
  forgotpassword() {
    return this.baseurl + "api/forgot_password";
  }
  changepassword() {
    return this.baseurl + "api/change_password";
  }
  punchIn() {
    return this.baseurl + "appuser/punch_in";
  }
  punchOut() {
    return this.baseurl + "appuser/punch_out";
  }
  attendanceDetails() {
    return this.baseurl + "api/attendance_details";
  }
  imageUpload() {
    return this.baseurl + "appuser/user_profilepic_upload";
  }
  colorCode() {
    return this.baseurl + "appuser/app_colorcode";
  }
  getSalaryList() {
    return this.baseurl + "api/payslip_users_list";
  }
  getUserList() {
    return this.baseurl + "api/all_users";
  }
  addSalary() {
    return this.baseurl + "api/add_salary";
  }
  generatePayslip() {
    return this.baseurl + "api/run_payroll";
  }
  getPdf() {
    return this.baseurl + "api/payslip_pdf";
  }
  punchInStatus() {
    return this.baseurl + "appuser/user_punch_in_details";
  }
  getProjectList() {
    return this.baseurl + "api/all_projects";
  }
  getClientList() {
    return this.baseurl + "api/client_list";
  }
  getClientProfile() {
    return this.baseurl + "api/client_profile";
  }
  getEstimatesList() {
    return this.baseurl + "api/estimate_list";
  }
  getInvoicesList() {
    return this.baseurl + "api/invoice_list";
  }
  makecall() {
    return this.baseurl + "api/one_to_one_call";
  }
  makeGroupCall() {
    return this.baseurl + "api/group_call";
  }
  initChat() {
    return this.baseurl + "api/user_chat";
  }
  declineCall() {
    return this.baseurl + "api/call_decline";
  }
  sendText() {
    return this.baseurl + "api/chat_message";
  }
  getMessages() {
    return this.baseurl + "api/all_chat_message";
  }
  getChatList() {
    return this.baseurl + "api/one_to_one_chat_messages";
  }
  getTimeSheetList() {
    return this.baseurl + "api/timesheet_list";
  }
  editTimeSheet() {
    return this.baseurl + "api/edit_timesheet";
  }
  addTimeSheet() {
    return this.baseurl + "api/add_timesheet";
  }
  deleteTimeSheet() {
    return this.baseurl + "api/delete_timesheet";
  }
  getProjectDetails() {
    return this.baseurl + "api/projects_details";
  }
  createProject() {
    return this.baseurl + "appuser/create_project";
  }
  editProject() {
    return this.baseurl + "appuser/edit_project";
  }
  editTask() {
    return this.baseurl + "appuser/edit_task";
  }
  deleteProject() {
    return this.baseurl + "appuser/delete_project";
  }
  createTask() {
    return this.baseurl + "appuser/create_task";
  }
  deleteTask() {
    return this.baseurl + "appuser/delete_task";
  }
  completeTask() {
    return this.baseurl + "appuser/task_completion";
  }
  createClient() {
    return this.baseurl + "appuser/create_client";
  }
  deleteClient() {
    return this.baseurl + "appuser/delete_client";
  }
  editClient() {
    return this.baseurl + "appuser/edit_client";
  }

  createInvoice() {
    return this.baseurl + "appuser/create_invoice";
  }
  createEstimate() {
    return this.baseurl + "appuser/create_estimate";
  }
  getPayments() {
    return this.baseurl + "api/all_payments";
  }
  createExpense() {
    return this.baseurl + "appuser/create_expense";
  }
  expenseList() {
    return this.baseurl + "api/expense_list";
  }
  editExpense() {
    return this.baseurl + "appuser/edit_expense";
  }
  deleteExpense() {
    return this.baseurl + "appuser/delete_expense";
  }
  editInvoice() {
    return this.baseurl + "appuser/edit_invoice";
  }
  editEstimate() {
    return this.baseurl + "appuser/edit_estimate";
  }
  deleteInvoice() {
    return this.baseurl + "appuser/delete_invoice";
  }
  deleteEstimate() {
    return this.baseurl + "appuser/delete_estimate";
  }
  dashboardCount(){
    return this.baseurl + "api/dashboard_count";
  }
  convertCurrency(){
    return this.baseurl + "api/currency_convert";
  }
  reportingTo() {
    return this.baseurl + "api/reporting_officer";
  }
  attendanceList(){
    return this.baseurl + "api/attendance_list";
  }
  basicInfo(){
    return this.baseurl + "api/basic_info";
  }
  personalInfo() {
    return this.baseurl + "api/personal_info";
  }
  emergencyInfo() {
    return this.baseurl + "api/emergency_info";
  }
  bankInfo() {
    return this.baseurl + "api/bank_info";
  }
  familyInfo() {
    return this.baseurl + "api/family_info";
  }
  educationInfo() {
    return this.baseurl + "api/education_info";
  }
  experienceInfo() {
    return this.baseurl + "api/experience_info";
  }
  attendanceInfo(){
    return this.baseurl + "api/attendance_info"
  }
  createAttendance() {
    return this.baseurl + "api/create_attendance"
  } 
  assignUser(){
    return this.baseurl + "appuser/assign_user"
  }
  runPayroll() {
    return this.baseurl + "api/run_payroll"
  } 
  taskView(){
    return this.baseurl + "appuser/task_view"
  }
  getLanguageList() {
    return this.baseurl + "api/language_list"
  }
  getLanguage() {
    return this.baseurl + "api/translate_list"
  }
}


