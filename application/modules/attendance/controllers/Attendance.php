<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Attendance extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App', 'Attendance_model'));
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        $all_routes = $this->session->userdata('all_routes');
        foreach($all_routes as $key => $route){
            if($route == 'attendance'){
                $routname = "attendance";
            } 
        }
        if(empty($routname)){
             $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }
        App::module_access('menu_attendance');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
        if($this->tank_auth->is_logged_in()) {
          $role_id = $this->tank_auth->get_role_id();
          if($this->input->post()){
            $params = $this->input->post();
            if(isset($params['attendance_month'])){
              $month = $params['attendance_month'];
            }else{              
             
              $month = date('m');
              $params['attendance_month'] =  date('m');
            }

            if(isset($params['attendance_year'])){              
              $year = $params['attendance_year'];

            }else{              
              $year = date('Y');
              $params['attendance_year'] =  date('Y');
            }
            $month  = $params['attendance_month'];
            $year  = $params['attendance_year'];
            $data['attendance_month'] = $params['attendance_month'];
            $data['attendance_year'] = $params['attendance_year'];
            $last_day = $year.'-'.$month.'-1';
           
            $params['length'] = 10;
            $data['employee_name']      = $params['employee_name'];
           
            if(($role_id==4) || ($role_id==1)) {
              $attendance_list = Attendance_model::attendance_list($params); 

              $data['attendance_list']  =  $attendance_list[1];
              $data['total_page']       =  $attendance_list[0];
            }
            $data['last_day']         = date('t',strtotime($last_day));  
            
          } else{            
            $params = array();
             $data = array();
            $month = date('m');
            $year  = date('Y');
            $last_day = $year.'-'.$month.'-1';
            $params['attendance_month'] = date('m');
            $params['attendance_year'] = date('Y');
            $data['attendance_month'] = date('m');
            $data['attendance_year'] = date('Y');
            if(isset($params['page']) && $params['page'] !="")
            {
              $data['current_page']     = $params['page'];
            }
            else{
              $data['current_page']     = '';
            }
           
            if(($role_id==4) || ($role_id==1)) {
              // $attendance_list = Attendance_model::attendance_list($params); 
             // $data['attendance_list']  =  $attendance_list[1];
             $data['attendance_list']  =  '';//$attendance_list[1];
              $data['total_page']       =  0;//$attendance_list[0];
            }
            $data['last_day']         = date('t',strtotime($last_day));  
           
          }
         

            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Attendance');
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['page']       = 'Attendance';
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $this->tank_auth->get_user_id();

            $get_employee_attendance = Attendance_model::get_employee_attendance($data['user_id'],'', ''); 
            
            // echo $this->db->last_query();die;

            $data['get_employee_attendance']  = $get_employee_attendance;

            $role_id = $this->tank_auth->get_role_id();
            
            $page = (($role_id==4) || ($role_id==1))?'attendance':'create_attendance';
            if($role_id == "1")
            {
              echo $page; die;
            }
            $this->template
                  ->set_layout('users')
                  ->build($page,isset($data) ? $data : NULL);
          }else{
           redirect('');
          }
    }
    function ajax_attendance(){
      $role_id = $this->tank_auth->get_role_id();
      if(!empty($_GET['attendance_month'])){
        $params = $this->input->get();
        if(isset($params['attendance_month'])){
          $month = $params['attendance_month'];
        }else{              
          
          $month = date('m');
          $params['attendance_month'] =  date('m');
        }

        if(isset($params['attendance_year'])){              
          $year = $params['attendance_year'];

        }else{              
          $year = date('Y');
          $params['attendance_year'] =  date('Y');
        }
        $month  = $params['attendance_month'];
        $year  = $params['attendance_year'];
        $data['attendance_month'] = $params['attendance_month'];
        $data['attendance_year'] = $params['attendance_year'];
        $last_day = $year.'-'.$month.'-1';
        if(empty($params['employee_name'])){
          $params['employee_name'] = $_GET['search']['value'];
        }
        
        $data['employee_name']      = $params['employee_name'];
        
        $params['start'] = $_GET['start'];

        
        if(!empty($_GET['length']))
          $params['length'] = $_GET['length'];
        else
          $params['length'] = 10;  
        if($role_id==1) {
          $attendance_list = Attendance_model::attendance_list($params); 
          $data['attendance_list']  =  $attendance_list[1];
          $data['total_page']       =  $attendance_list[0];
          $data['total_record']     =  $attendance_list[2];
        }
        else if($role_id==3){
          if(isset($_GET['team_lead']) && $_GET['team_lead']==1 ){
            $params['teamlead_id']=$this->session->userdata('user_id');
          }

          $attendance_list = Attendance_model::attendance_list($params); 
         
          $data['attendance_list']  =  $attendance_list[1];
          $data['total_page']       =  $attendance_list[0];
          $data['total_record']     =  $attendance_list[2];
          
        }
        $data['last_day']         = date('t',strtotime($last_day));  
        
      } else{            
        //$params = array();
          $data = array();
        $month = date('m');
        $year  = date('Y');
        $last_day = $year.'-'.$month.'-1';
        $params['attendance_month'] = date('m');
        $params['attendance_year'] = date('Y');
        $data['attendance_month'] = date('m');
        $data['attendance_year'] = date('Y');
        $params['employee_name'] = $_GET['search']['value'];
        $params['start'] = $_GET['start'];
        $params['length'] = $_GET['length'];
        $data['current_page']     = $params['page'];
        
        if($role_id==1) {
          $attendance_list = Attendance_model::attendance_list($params); 
         
          $data['attendance_list']  =  $attendance_list[1];
          $data['total_page']       =  $attendance_list[0];
          $data['total_record']     =  $attendance_list[2];
          
        }
        else if($role_id==3){
          if(isset($_GET['team_lead']) && $_GET['team_lead']==1 ){
            $params['teamlead_id']=$this->session->userdata('user_id');
          }

          $attendance_list = Attendance_model::attendance_list($params); 
         
          $data['attendance_list']  =  $attendance_list[1];
          $data['total_page']       =  $attendance_list[0];
          $data['total_record']     =  $attendance_list[2];
          
        }
        $data['last_day']         = date('t',strtotime($last_day));  
        
      }
      $return_data = array();
      $return_data['data'] =  array();
      $return_data['recordsTotal'] = $data['total_record'];
		  $return_data['recordsFiltered'] = $data['total_record'];
      if(!empty($data['attendance_list'])){
        $sl_no = $params['start'];
			  $i  = 0;
        foreach($data['attendance_list'] as $attendance1){
          $j = $i+1;
          $user = $this->db->get_where('users',array('id'=>$attendance1->user_id))->row_array();

          
                
          if(!empty($user['designation_id'])){
            $designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
            $designation_name = $designation['designation'];
            
          }else{
            $designation_name = '-';
          }
          $imgs = '';
          if($attendance1->avatar != 'default_avatar.jpg'){
              $imgs = $attendance1->avatar;
              
          }else{
              $imgs = "default_avatar.jpg";
          }
          $return_data['data'][$i]['s_no'] = $sl_no +$j;
          $return_data['data'][$i]['team_member'] = '<h2 class="table-avatar d-flex align-items-center">
          <a class="avatar avatar-xs" href="'.base_url().'employees/profile_view/'.$attendance1->user_id.'">'; 
          if (file_exists(base_url().'assets/avatar/'.$imgs)) {
            $return_data['data'][$i]['team_member'] .= '<img class="img-fluid" src="'.base_url().'assets/avatar/'.$imgs.'">';
          }
          else{
            $return_data['data'][$i]['team_member'] .= '<img class="img-fluid" src="'.base_url().'assets/avatar/default_avatar.jpg">';
          }
          $return_data['data'][$i]['team_member'] .= '</a><a class="text-info" href="'.base_url().'attendance/details/'.$attendance1->user_id.'"><span class="username-info">'.user::displayName($attendance1->user_id).'</span>
          <span class="userrole-info text-muted"> '.$designation_name.'</span></a></h2>';
          $return_data['data'][$i]['lop']='<form class="min-table-width"><input type="number" class="form-control col-md-2 max-input-table" name="lop_value" id="lop_ID" value='.$attendance1->lop.'> &nbsp;<button type="button" class="btn btn-success btn-input-submit" " onclick="lop_click_event(this.form.lop_ID.value,'.$data['attendance_month'].','.$data['attendance_year'].','.$attendance1->user_id.')">Save</button></form>';
          $attendance_details = $attendance1->attendance;
          $k1 = 1;
         // echo '<pre>';print_r($attendance_details); die;




          foreach ($attendance_details as $key => $rec) {
            if(sizeof($attendance_details[$key]) > 1)
            {
              $tmp_checkin_count = 0;
              $tmp_checkin_count = sizeof($attendance_details[$key]);
              
              $tmp_checkin = $attendance_details[$key][$tmp_checkin_count-1]['punch_in'];
              $tmp_checkout = $attendance_details[$key][$tmp_checkin_count-1]['punch_out'];

              $rec = array();
              $rec[0]['day'] = '1'; 
              $rec[0]['punch_in'] = $tmp_checkin;
              $rec[0]['punch_out'] = $tmp_checkout;
            }

            $total_scheduled_minutes = 0;
            $attendance_body = '';
            $user_html = "";
            $status = $rec[0]['day'];
            $punch_in = $rec[0]['punch_in'];
            $punch_out = $rec[0]['punch_out'];
            $user_id = $attendance1->user_id;
            $day = $key + 1;
            $attendance_month = $params['attendance_month'];
            $attendance_year  = $params['attendance_year'];
            $month = $attendance_month;
            $year  = $attendance_year;
            $schedule_date = $year.'-'.$month.'-'.$day;
            $schedule_date1=date("Y-m-d",strtotime($schedule_date));
            $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date<='=>$schedule_date1,'end_date>='=>$schedule_date1);
            $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
            if(!empty($user_schedule)){
              $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);

              $total_scheduled_minutes = $total_scheduled_hour;   
            } else{
              $total_scheduled_minutes = 0;
          }
          if($total_scheduled_minutes == 0){
            $total_scheduled_minutes = 480;
          }
          $production_hour = 0;
          if(!empty($punch_in) && !empty($punch_out)){
              $current_records = $rec;
              if(!empty($current_records)){
                foreach($current_records as $current_a){
                  $req_hour = 0;
                  if(!empty($current_a['punch_in']) && !empty($current_a['punch_out'])){
                    $req_hour = time_difference(date('H:i',strtotime($current_a['punch_in'])),date('H:i',strtotime($current_a['punch_out'])));
                  }
                  $production_hour = $production_hour + $req_hour;                }
              }
           // $production_hour = time_difference(date('H:i',strtotime($punch_in)),date('H:i',strtotime($punch_out)));
          }
          // overtimes                    
          if($user_schedule['accept_extras'] == 1){
            $overtimes=($production_hour)-($total_scheduled_minutes);
            if($overtimes > 0)
            {
              $overtime=$overtimes;
              $production_hour_achived=  $production_hour;
            }
            else
            {
              $production_hour_achived=  $production_hour;
            $overtime=0;
          }
        } 
        else{
          $production_hour_achived=  $production_hour;
          
          $overtime=0;
        }
      // later_entry_hours
      if(!empty($punch_in))
      {
        $later_entry_hours = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$schedule_date.' '.$punch_in);

      } else {
        $later_entry_hours = 0;
      }
      // Missing worke
      $missing_work=($total_scheduled_minutes)-($production_hour);
      if($missing_work > 0)
      {
        $missing_work=$missing_work;
      
      }
      else
      {
        $missing_work=0;
        
      }
      $overtimes  =  $overtime;
      $production_hour_achived  =  $production_hour_achived;
      $later_entry_hours  =  $later_entry_hours;
      $missing_work  =  $missing_work;
      $holiday = $this->db->get_where('holidays',array('status'=>0,'holiday_national'=>1))->result_array(); 
      
      $holi_days=array();
      foreach($holiday as $key => $h)
      {
        $holi_days[]=$h['holiday_date'];
      }   
      $holi_dayss=  array_values($holi_days);    
     // echo $production_hour_achived.':'.$total_scheduled_minutes;exit;
      if ($status == '0') {
        if($total_scheduled_minutes ==0){ 
          $attendance_body .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" title="Week off"><i class="fa fa-close text-default"></i></a>';
        }else {
          if($punch_in == '' && $punch_out == ''){
            // echo __LINE__;die;
            // $attendance_body .= '<i class="fa fa-close text-default"></i>';
            $attendance_body .= '<a href="'.base_url() . 'attendance/update_attendance_ar/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-close text-default" data-bs-toggle="tooltip" title="Week Off"></i></a>';
          }
        }            
      } else if ($status == '1') {
        if(($punch_in != ''  && $punch_out != '')){

          /*if($overtimes!= 0){
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" data-bs-toggle="tooltip" title="Extra Time Worked"><i class="fa fa-check text-warning"></i></a>';
          }*/
          
          if($production_hour_achived !=0 && $production_hour_achived >=$total_scheduled_minutes){
           
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-success" data-bs-toggle="tooltip" title="Workday Complete"></i></a>';             
          }
          else if($production_hour_achived !=0 && $production_hour_achived >=($total_scheduled_minutes/2)){
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-success" data-bs-toggle="tooltip" title="Workday Complete"></i></a><a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-bs-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
          }
          else if($production_hour_achived !=0 && $production_hour_achived >=0){
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-bs-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
          }
          else{
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-bs-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
          }
          /*if($later_entry_hours !=0){
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-yellow" data-bs-toggle="tooltip" title="Late Arrival"></i></a>';
          }
          if($missing_work !=0){
            
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-bs-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
          }*/

        }else{
          if($later_entry_hours !=0){
            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-yellow" data-bs-toggle="tooltip" title="Late Arrival"></i></a>';
          }
          
          $attendance_body .= '<a href="'.base_url() . 'attendance/update_attendance/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-bs-toggle="tooltip" title="Incomplete Workday Time"></i></a>';             
        }
      } else if ($status == '2') {
        $attendance_body .= '<i class="text-success" data-bs-toggle="tooltip" title="Worked Hours"></i>';
      } else if ($status == '0') {
        $attendance_body .= '<i class="fa fa-exclamation-triangle text-danger" data-bs-toggle="tooltip" title="No Record for Check in"></i>';
      } else if ($status == '') {
        if($total_scheduled_minutes ==0 ){            
          if(in_array($schedule_date1,$holi_dayss)){
            $attendance_body .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" title="Holiday"><i class="fa fa-tree" aria-hidden="true"></i></a>'; 
          }else{
            if($production_hour_achived !=0 && $production_hour_achived >=$total_scheduled_minutes){
              $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-success" data-bs-toggle="tooltip" title="Workday Complete"></i></a>';             
            }
            else{
              $attendance_body .= '<a href="'.base_url() . 'attendance/update_attendance/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" >-</a>';
            }
          }
        }
        else if(($punch_in == ''  || $punch_out == '')){
          $attendance_body .= '<a href="'.base_url() . 'attendance/update_attendance/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-close text-danger" data-bs-toggle="tooltip" title="Check In not Yet"></i></a>';
        }           
      }
      ++$j; 
      $return_data['data'][$i][$k1] = $attendance_body;  
      $k1++;  
    }
          $i++;
  }
  
}
$return_data['recordsTotal'] = $data['total_record'];
$return_data['recordsFiltered'] = $data['total_record'];
$return_data['draw'] = isset ( $_GET['draw'] ) ?intval( $_GET['draw'] ) :0;
echo json_encode($return_data);exit;
}

     function details($user_id)
    {
        if($this->tank_auth->is_logged_in()) {
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Attendance');
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['page']       = 'attendance';
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $user_id;

            $role_id = $this->tank_auth->get_role_id();
            $page = 'attendance_details';
            $this->template
                  ->set_layout('users')
                  ->build($page,isset($data) ? $data : NULL);
          }else{
           redirect('');
          }
    }

     function get_list(){
        if($_POST){
           $user_id = $_POST['user_id'];
           $date = date("Y-m-d", strtotime($_POST['date']));
           $attendance_list = Attendance_model::get_list($user_id, $date);
           $records = array();
           $length = count($attendance_list);
           $total_hours = 0;
           for($i = 0; $i < $length; ++$i) {
             $row = array();
             $row['fullname'] = $attendance_list[$i]->fullname;
             $row['punch_in_date_time'] = $attendance_list[$i]->punch_in_date_time;
             $row['punch_in_note'] = $attendance_list[$i]->punch_in_note;
             $row['punch_in_address'] = $attendance_list[$i]->in_address;
             $row['punch_out_date_time'] = $attendance_list[$i]->punch_out_date_time;
             $row['punch_out_note'] = $attendance_list[$i]->punch_out_note;
             $row['punch_out_address'] = $attendance_list[$i]->out_address;
             $row['cal_hours'] = $attendance_list[$i]->cal_hours;
             $total_hours += $attendance_list[$i]->cal_hours;

               $row['total_hours'] = '--';
               $j = $i+1;
               $user_id = !empty($attendance_list[$j]->user_id)?($attendance_list[$j]->user_id):'';
               if ((($attendance_list[$i]->user_id) !== $user_id) ||  empty($user_id)) {
                 $row['total_hours'] = $total_hours;
                 $total_hours = 0;
               }
             $records[] = $row;
           }
           echo json_encode($records);
           exit;
        }
      }

      public function attendance_list()
      {
        
        if($this->input->post()){
        
          $params = $this->input->post();
          $params['branch_id']=$this->session->userdata('branch_id');

          $month = $params['attendance_month'];
          $year  = $params['attendance_year'];
          $params['length']=10;
          $last_day = $year.'-'.$month.'-1';
        
          $records = array();
          $records['current_page']     = $params['page'];
          $attendance_list = Attendance_model::attendance_list($params); 

          $records['attendance_list']  =  $attendance_list[1];
          $records['total_page']       =  $attendance_list[0];
          $records['last_day']         = date('t',strtotime($last_day));  
          echo json_encode($records);
          
       }
       exit;
      }

      public function save_punch_details(){

        if($this->input->post()){


        $params = $this->input->post();
        if(!empty($params['punch_in_date_time'])){

            $strtotime = strtotime(date('Y-m-d H:i'));
            $user_id   = $params['user_id'];

            $a_year    = date('Y',$strtotime);
            $a_month   = date('m',$strtotime);
            $a_day     = date('d',$strtotime);
            $a_cin     = date('H:i',$strtotime);

            $where     = array('user_id'=>$user_id);
            $account_record  = $this->db->get_where('dgt_account_details',$where)->row_array();

            $cur_date = date('Y-m-d');
            $where     = array('employee_id'=>$user_id,'schedule_date<='=>$cur_date,'end_date>='=>$cur_date);
            $shift_record  = $this->db->get_where('dgt_shift_scheduling',$where)->row_array();
            if(!empty($shift_record))
            {
              $new_date_time = $cur_date.' '.$shift_record['start_time'];
              $new_date_time = date('Y-m-d H:i:s',strtotime($new_date_time));
              $new_time = date("H:i", strtotime($new_date_time.'-8 hours'));
            }
             
            $current_time = date('H:i');
            $check_time = '23:59:59';
            $check_time = date('H:i',strtotime($check_time));
            $a_day = (int) $a_day;
            if(!empty($shift_record))
            {
              if (strtotime($current_time) < strtotime($shift_record['start_time']) && strtotime($check_time) > strtotime($current_time) && strtotime($new_time
              ) > strtotime($current_time) ){
                $a_day -=1;

              }
            }
            
            $check_cur_day1 = (int)$a_day - 1;
            if($check_cur_day1<=-1){
              $req_day = date('Y-m-d', strtotime(' -1 day'));
              $a_year = date('Y',strtotime($req_day));
              $a_month = date('m',strtotime($req_day));
            }
           
            $where     = array('user_id'=>$user_id,'a_month'=>(int)$a_month,'a_year'=>$a_year);
            $this->db->select('month_days,month_days_in_out');
            $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

            if(empty($record)){
              $inputs['attendance_month'] =$a_month;
              $inputs['attendance_year'] = $a_year;
              Attendance_model::attendance($user_id,$inputs);
              $this->db->select('month_days,month_days_in_out');
              $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
            }
            
            if(!empty($record['month_days'])){
              $record_day = unserialize($record['month_days']);
              $month_days_in_out_record = unserialize($record['month_days_in_out']);

              if($record['month_days_in_out'] != "b:0;")
              {
                $month_days_in_out_record = unserialize($record['month_days_in_out']);
              }
              else{
                $month_days_in_out_record = unserialize($record['month_days']);
              }
			  
 
              $a_day -=1;
              if($a_day == -1){
                $req_day = date('d', strtotime(' -1 day'));
                $a_day = (int) $req_day;
                $a_day -=1;
              }
              if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
                $current_days = $month_days_in_out_record[$a_day];
                $total_records = count($current_days);
                $current_day = end($current_days);
                
        
// echo '<pre>';print_r($a_cin);exit;

                // if($record_day[$a_day]['punch_in'] ==''){
					
                  $record_day[$a_day]['punch_in'] = $a_cin;
                  $record_day[$a_day]['punch_out'] = '';
                  $record_day[$a_day]['day'] = 1;
                // }
                
                if($total_records == 1 && empty($current_day['punch_out'])){
                  
                  $current_days = array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>'');
                  $month_days_in_out_record[$a_day][0] = $current_days;
                }else{
                  
                  // if(!empty($current_day['punch_in']) && !empty($current_day['punch_out']))
                  // {
                    $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>'');
                    $month_days_in_out_record[$a_day] = $current_days;
                  // } 
                }
				
				// $month_days_in_out_record[$a_day][0]['punch_in']='';
				// $month_days_in_out_record[$a_day][0]['punch_out']='';
                // unset($month_days_in_out_record[$a_day][0]['punch_in']);
                // unset($month_days_in_out_record[$a_day][0]['punch_out']);

              }
            }
            $this->db->where($where);
            $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
			
        }

        $tmp_attendance = array();
        $tmp_attendance = array(
          "user_id"             => $user_id,
          "punch_in_date_time"  => date("Y-m-d H:i:s"),
          "punch_in_note"       => "punch-in",
          "created"             =>  date("Y-m-d H:i:s"),
          "created_by"          => $user_id
        );

        $this->employee_attendance($user_id, "punch-in", $tmp_attendance);

        $this->session->set_flashdata('tokbox_success', 'Check in successfully saved');
        return redirect('attendance');
        }

        }

        public function save_punch_details_out(){

        if($this->input->post()){

        $params = $this->input->post();

        if(!empty($params['punch_out_date_time'])){

            $strtotime = strtotime(date('Y-m-d H:i'));
            
            $user_id   = $params['user_id'];
            
            $a_year    = date('Y',$strtotime);
            $a_month   = date('m',$strtotime);
            $a_day     = date('d',$strtotime);
            $a_cout     = date('H:i',$strtotime);

            $where     = array('user_id'=>$user_id);
            $account_record  = $this->db->get_where('dgt_account_details',$where)->row_array();

            $cur_date = date('Y-m-d');
            
            $where  = array('employee_id'=>$user_id,'schedule_date<='=>$cur_date);
            $temp_shift_record  = $this->db->get_where('dgt_shift_scheduling',$where)->row_array();
            if(!empty($temp_shift_record))
            {
              if($temp_shift_record['indefinite'] == 0)
              {
                $where = array();
                $where  = array('employee_id'=>$user_id,'schedule_date<='=>$cur_date,'end_date>='=>$cur_date);
              }
            }

            $shift_record['start_time'] = '';
            $shift_record  = $this->db->get_where('dgt_shift_scheduling',$where)->row_array();

            if(!empty($shift_record))
            {
              $new_date_time = $cur_date.' '.$shift_record['end_time'];
            }
            else{
               $new_date_time = $cur_date;
            }
           
            $new_date_time = date('Y-m-d H:i:s',strtotime($new_date_time));

            $new_time = date("H:i", strtotime($new_date_time.'+10 hours'));

            $current_time = date('H:i');
            
            if (strtotime($current_time) < strtotime($shift_record['start_time']) && strtotime($current_time) <= strtotime($new_time)){
              $a_day -=1;
            }

            $check_cur_day1 = (int)$a_day - 1;

            if($check_cur_day1<=-1){
              $req_day = date('Y-m-d', strtotime(' -1 day'));
              $a_year = date('Y',strtotime($req_day));
              $a_month = date('m',strtotime($req_day));
            }
            $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
            $this->db->select('month_days,month_days_in_out');
            $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
            
            if(empty($record)){
              $inputs['attendance_month'] =$a_month;
              $inputs['attendance_year'] = $a_year;
              Attendance_model::attendance($user_id,$inputs);
              $this->db->select('month_days,month_days_in_out');
              $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
            }
            
            if(!empty($record['month_days'])){
              
              $record_day = unserialize($record['month_days']);
              $month_days_in_out_record = unserialize($record['month_days_in_out']);

              if($record['month_days_in_out'] != "b:0;")
              {
                $month_days_in_out_record = unserialize($record['month_days_in_out']);
              }
              else{
                $month_days_in_out_record = unserialize($record['month_days']);
              }

                $a_day -=1;

                if($a_day == -1){
                  $req_day = date('d', strtotime(' -1 day'));
                  $a_day = (int) $req_day;
                  $a_day -=1;
                }
                
                $current_days = $month_days_in_out_record[$a_day];
                $total_records = count($current_days);
                $current_day = end($current_days);
              if(!empty($record_day[$a_day])){
                  $record_day[$a_day]['punch_out'] = $a_cout;
                  $record_day[$a_day]['day'] = 1;
              }

              if($total_records == 1 && empty($current_day['punch_out'])){
                
                  $month_days_in_out_record[$a_day][0]['punch_out'] = $a_cout;
                }else{
                    
                  if(!empty($current_day['punch_in']) && empty($current_day['punch_out']))
                  {
                    
                    $current_days[$total_records-1]['punch_out'] = $a_cout;
                    $month_days_in_out_record[$a_day] = $current_days;
                  } 
                }
              
            }

            $this->db->where($where);
            $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
        }

        $tmp_attendance = array();
        $tmp_attendance = array(
          "user_id"               => $user_id,
          "punch_out_date_time"   => date("Y-m-d H:i:s"),
          "punch_out_note"        => "punch-out",
          "updated_at"            =>  date("Y-m-d H:i:s"),
          "updated_by"            => $user_id
        );
       
         $this->employee_attendance($user_id, "punch-out", $tmp_attendance);

        $this->session->set_flashdata('tokbox_success', 'Check out successfully saved');
        // $this->session->set_flashdata('message', 'Check out successfully saved.');
        return redirect('attendance');
        }

   }


   public function attendance_details($user_id,$day,$month,$year)
   {
            $data['user_id'] = $user_id;
            $data['atten_day'] = $day;
            $data['atten_month'] = $month;
            $data['atten_year'] = $year;
             $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year);
             $this->db->select('month_days,month_days_in_out');
             $data['record']  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
             $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year,'a_day'=>$day);
             $this->db->select('reason');
             $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
             $data['reason']  = '';
             if(!empty($reason)){
               $data['reason']  = $reason['reason'];
             }

            $this->load->view('modal/attendance', $data);
   }
   function ro_attendance_view()
	{
		
			 
		if($_POST)
		{
			
            $params = $this->input->post();

            if(isset($params['attendance_month'])){
              $month = $params['attendance_month'];
            }else{              
             
              $month = date('m');
              $params['attendance_month'] =  date('m');
            }

            if(isset($params['attendance_year'])){              
              $year = $params['attendance_year'];

            }else{              
              $year = date('Y');
              $params['attendance_year'] =  date('Y');
            }
            $month  = $params['attendance_month'];
            $year  = $params['attendance_year'];
            $data['attendance_month'] = $params['attendance_month'];
            $data['attendance_year'] = $params['attendance_year'];
            $last_day = $year.'-'.$month.'-1';
           
            
            $data['employee_name']      = $params['employee_name'];
		}
		else
		{
			$params = array();
             $data = array();
            $month = date('m');
            $year  = date('Y');
            $last_day = $year.'-'.$month.'-1';
            $params['attendance_month'] = date('m');
            $params['attendance_year'] = date('Y');
            $data['attendance_month'] = date('m');
            $data['attendance_year'] = date('Y');
		}
            $params['teamlead_id']=$this->session->userdata('user_id');
            $data['current_page']     = $params['page'];
            /*$attendance_list = Attendance_model::attendance_list($params); 
            $data['attendance_list']  =  $attendance_list[1];
            $data['total_page']       =  $attendance_list[0];*/
            $data['last_day']         = date('t',strtotime($last_day)); 
			$this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('attendance').' - '.config_item('company_name'));  
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['datatables']       = TRUE;
            $data['page']       = lang('attendance');
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $this->tank_auth->get_user_id();
			 

            $role_id = $this->tank_auth->get_role_id();
            $page = 'ro_attendance';
            $this->template->set_layout('users')->build($page,isset($data) ? $data : NULL);
	}
	
	function regularization_request_ro()
	{
		// echo '<pre>';print_r($this->session->userdata('role_id'));exit;
		if($_POST)
		{
			
            $params = $this->input->post();

            if(isset($params['attendance_month'])){
              $month = $params['attendance_month'];
            }else{              
             
              $month = date('m');
              //$params['attendance_month'] =  date('m');
            }

            if(isset($params['attendance_year'])){              
              $year = $params['attendance_year'];

            }else{              
              $year = date('Y');
              //$params['attendance_year'] =  date('Y');
            }
            $month  = $params['attendance_month'];
            $year  = $params['attendance_year'];
            $params['attendance_month'] = $params['attendance_month'];
            $params['attendance_year'] = $params['attendance_year'];
            $last_day = $year.'-'.$month.'-1';
           
            
            $params['employee_name']      = $params['employee_name'];

            $data['attendance_month'] = $params['attendance_month'];
            $data['attendance_year'] = $params['attendance_year'];
		}
		else
		{
			$params = array();
             $data = array();
            $month = date('m');
            $year  = date('Y');
            $last_day = $year.'-'.$month.'-1';
           // $params['attendance_month'] = date('m');
            //$params['attendance_year'] = date('Y');
           // $data['attendance_month'] = date('m');
           // $data['attendance_year'] = date('Y');
		}
		
		// 
            $params['teamlead_id']=$this->session->userdata('user_id');
            $data['current_page']     = $params['page'];
			      $params['role_id']=$this->session->userdata('role_id');
            $attendance_list = Attendance_model::attendance_list_request_regul($params); 
			       // echo '<pre>';print_r($attendance_list);exit;
            $data['attendance_list']  =  $attendance_list[1];
            $data['total_page']       =  $attendance_list[0];
            $data['last_day']         = date('t',strtotime($last_day)); 
			       $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('attendance').' - '.config_item('company_name'));  
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['datatables'] = TRUE;
            $data['page']       = lang('attendance');
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $this->tank_auth->get_user_id();
			

            $role_id = $this->tank_auth->get_role_id();
            $page = 'regularization_request_ro';
            
            $this->template
                  ->set_layout('users')
                  ->build($page,isset($data) ? $data : NULL);
           
	}
	
	
  public function update_attendance($user_id,$day,$month,$year)
 {
	   if($_POST)
	   {
		   
     

       $params = $this->input->post();

		   if(!empty($params['punch_in_time'])){
		
			$punch_in_time = date('H:i',strtotime($params['punch_in_time']));
			$punch_out_time = date('H:i',strtotime($params['punch_out_time']));
			$in_date=$year.'-'.$month.'-'.$day.' '.$punch_in_time;
			$out_date=$year.'-'.$month.'-'.$day.' '.$punch_out_time;
			$strtotime = strtotime($in_date);
			$strtotime_out = strtotime($out_date);
			$user_id   = $user_id;
      $a_year    = date('Y',$strtotime);
			$a_month   = date('m',$strtotime);
			$a_day     = date('d',$strtotime);
			$a_cin     = date('H:i',$strtotime);
			$a_cout    = date('H:i',$strtotime_out);
		  $where= array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
      $this->db->select('month_days,month_days_in_out');
      $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
      if(empty($record)){
        $inputs['attendance_month'] =$a_month;
        $inputs['attendance_year'] = $a_year;
        Attendance_model::attendance($user_id,$inputs);
        $this->db->select('month_days,month_days_in_out');
        $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
      }
     
      if(!empty($record['month_days'])){
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);

        $a_day -=1;
		
        
         if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);
          
  

          if($record_day[$a_day]['punch_in'] ==''){
            $record_day[$a_day]['punch_in'] = $a_cin;
			 $record_day[$a_day]['punch_out'] = $a_cout;
            $record_day[$a_day]['day'] = 1;
          }
		  $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>$a_cout);
		$month_days_in_out_record[$a_day] = $current_days;
		 }
		  
      }
 
		
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));

      $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year,'a_day'=>$a_day+1);
      $this->db->select('reason');
      $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
      if(!empty($reason) && !empty($params['reason'])){
        $this->db->where($where);
        $this->db->update('dgt_attendance_reason', array('reason'=>$params['reason']));
      }
      else{
        if(!empty($params['reason'])){
          $where['reason'] = $params['reason'];
          $this->db->insert('dgt_attendance_reason',$where );
        }
      }

   }		

		
			$this->session->set_flashdata('tokbox_success', 'Check out successfully saved');
      if (User::is_admin()) {
			redirect('attendance');
      }
      else{
        redirect('attendance/ro_attendance_view');
      }
   
		 }  
	   
	   else
	   {
        $data['user_id'] = $user_id;
        $data['atten_day'] = $day;
        $data['atten_month'] = $month;
        $data['atten_year'] = $year;
        $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year);
        $this->db->select('month_days,month_days_in_out');
        $data['record']  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

        $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year,'a_day'=>$day);
        $this->db->select('reason');
        $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
        $data['reason']  = '';
        if(!empty($reason)){
          $data['reason']  = $reason['reason'];
        }
        $this->load->view('modal/update_attendance', $data);
	   }
            
   }


 function attendance_regularization()
	{
		if(isset($_POST['search_filter']))
		{
			
            $params = $this->input->post();
            $params['length'] = '';
            $month = date('m');
            if(!empty($params['attendance_month'])){
              $month = $params['attendance_month'];
            }
            $params['attendance_month'] =  $month;
            $year = date('Y');
            if(!empty($params['attendance_year'])){              
              $year = $params['attendance_year'];

            }
            $params['attendance_year'] =  $year;
            $data['attendance_month'] = $params['attendance_month'];
            $data['attendance_year'] = $params['attendance_year'];
            $last_day = $year.'-'.$month.'-1';
            $data['employee_name']      = $params['employee_name'];
		}
		else
		{
			$params = array();
             $data = array();
            $month = date('m');
            $year  = date('Y');
            $last_day = $year.'-'.$month.'-1';
            $params['attendance_month'] = date('m');
            $params['attendance_year'] = date('Y');
            $data['attendance_month'] = date('m');
            $data['attendance_year'] = date('Y');
		}
            // $params['teamlead_id']=$this->session->userdata('user_id');
            $params['employee_id']=$this->session->userdata('user_id');
            $data['current_page']     = $params['page'];
            $attendance_list = Attendance_model::attendance_list_regularization($params); 
            $data['attendance_list']  =  $attendance_list[1];
            
            $data['total_page']       =  $attendance_list[0];
            $data['last_day']         = date('t',strtotime($last_day)); 
			$this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('attendance').' - '.config_item('company_name'));  
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['datatables']       = TRUE;
            // $data['page']       = 'attendance_regularization';
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $this->tank_auth->get_user_id();
			

            $role_id = $this->tank_auth->get_role_id();
            $page = 'attendance_regularization';
            $this->template
                  ->set_layout('users')
                  ->build($page,isset($data) ? $data : NULL);
           
	}
	
	
	
	
	public function update_attendance_ar($user_id,$day,$month,$year)
	{
	   if($_POST)
	   {

		   $params = $this->input->post();
		   if(!empty($params['punch_in_time'])){
			
			$punch_in_time = date('H:i',strtotime($params['punch_in_time']));
			$punch_out_time = date('H:i',strtotime($params['punch_out_time']));
			$in_date=$year.'-'.$month.'-'.$day.' '.$punch_in_time;
			$out_date=$year.'-'.$month.'-'.$day.' '.$punch_out_time;
			$strtotime = strtotime($in_date);
			$strtotime_out = strtotime($out_date);
			$user_id   = $user_id;
			$a_year    = date('Y',$strtotime);
			$a_month   = date('m',$strtotime);
			$a_day     = date('d',$strtotime);
			$a_cin     = date('H:i',$strtotime);
			$a_cout    = date('H:i',$strtotime_out);
			// $a_day -=1;
		  $where= array('user_id'=>$user_id,'a_day'=>$a_day,'a_month'=>$a_month,'a_year'=>$a_year);
		  // echo '<pre>';print_r($where);exit;
      $this->db->select('*');
      $record  = $this->db->get_where('dgt_attendance_details_ar',$where)->row_array();

      
	 
      if(empty($record)){
       
        $inputs['a_day'] =$a_day;
        $inputs['attendance_month'] =$a_month;
        $inputs['attendance_year'] = $a_year;
        $inputs['reason'] = $params['reason'];
		 // echo '<pre>';print_r($inputs);exit;
		$user_details=$this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
        Attendance_model::attendance_ar($user_id,$inputs);
		// echo '<pre>';print_r($inputs);exit;
        $this->db->select('*');
        $record  = $this->db->get_where('dgt_attendance_details_ar',$where)->row_array();
      }
    // echo '<pre>';print_r($record);exit;
      if(!empty($record['month_days'])){
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);

        $a_day -=1;
		
        
         if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);
          
  

          if($record_day[$a_day]['punch_in'] ==''){
            $record_day[$a_day]['punch_in'] = $a_cin;
			 $record_day[$a_day]['punch_out'] = $a_cout;
            $record_day[$a_day]['day'] = 1;
          }
		  $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>$a_cout);
		$month_days_in_out_record[$a_day] = $current_days;
		 }
		  
      }
		
      $this->db->where($where);
      $this->db->update('dgt_attendance_details_ar', array('reason'=>$params['reason'],'month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record),'ro_id'=>$user_details['teamlead_id']));



      $message = App::email_template('attendance_regularization','template_body');
      $subject = App::email_template('attendance_regularization','subject');
      $signature = App::email_template('email_signature','template_body');

      $logo_link = create_email_logo();

      $reason  = '';
      switch($record['reason']){
        case 1:
          $reason	=	'Forgot to Check In';
          break;
        case 2:
          $reason	=	'Forgot to Check Out';
          break;
        case 3:
          $reason	=	'Network Issues';
          break;
        case 4:
          $reason	=	'On Duty';
          break;
        case 5:
          $reason	=	'Permission';
          break;
      }
      $account_details=$this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
      $user_details=$this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
     
      $message = str_replace("{REQUEST_BY}",$account_details['fullname'],$message);
      $message = str_replace("{INVOICE_LOGO}",$logo_link,$message);
      
      $message = str_replace("{ATTENDANCE_DATE}",$record['a_year'].'-'.$record['a_month'].'-'.$record['a_day'],$message);
      $message = str_replace("{CHECK_IN}",$punch_in_time,$message);
      $message = str_replace("{CHECK_OUT}",$punch_out_time,$message);
      $message = str_replace("{REASON}",$reason,$message);
      $message = str_replace("{SIGNATURE}",$signature,$message);
      $team_lead_details = $this->db->get_where('dgt_users',array('id'=>$user_details['teamlead_id']))->row_array();
      if(!empty($team_lead_details['email'])){
        $params['recipient'] = $team_lead_details['email'];
        $params['subject'] = $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';
        // echo "<pre>"; print_R($params); die;
        modules::run('fomailer/send_email',$params);

      }

      $team_lead_details = $this->db->get_where('dgt_users',array('id'=>1))->row_array();

      $params['recipient'] = $team_lead_details['email'];
      $params['subject'] = $subject;
      $params['message'] = $message;

      $params['attached_file'] = '';
      modules::run('fomailer/send_email',$params);

      // $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year,'a_day'=>$a_day+1);
      // $this->db->select('reason');
      // $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
      // if(!empty($reason) && !empty($params['reason'])){
        // $this->db->where($where);
        // $this->db->update('dgt_attendance_reason', array('reason'=>$params['reason']));
      // }
      // else{
        // if(!empty($params['reason'])){
          // $where['reason'] = $params['reason'];
          // $this->db->insert('dgt_attendance_reason',$where );
        // }
      // }

   }		

		
			$this->session->set_flashdata('tokbox_success', 'Check out successfully saved');
      if (User::is_admin()) {
			redirect('attendance');
      }
      else{
        redirect('attendance/attendance_regularization');
      }
   
		 }  
	   
	   else
	   {
        $data['user_id'] = $user_id;
        $data['atten_day'] = $day;
        $data['atten_month'] = $month;
        $data['atten_year'] = $year;
        $where     = array('user_id'=>$user_id,'a_day'=>$day,'a_month'=>$month,'a_year'=>$year);
        $this->db->select('*');
        // $data['record']  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
        $data['record']  = $this->db->get_where('dgt_attendance_details_ar',$where)->row_array();

        // $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year,'a_day'=>$day);
        // $this->db->select('reason');
        // $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
        // $data['reason']  = '';
        // if(!empty($reason)){
          // $data['reason']  = $reason['reason'];
        // }
        $this->load->view('modal/update_attendance_ar', $data);
	   }
            
   }
   
   
   
	public function update_attendance_ar_ro($user_id,$day,$month,$year)
	{
	   if($_POST)
	   {
		   $params = $this->input->post();
		   if(!empty($params['punch_in_time'])){
			
			$punch_in_time = date('H:i',strtotime($params['punch_in_time']));
			$punch_out_time = date('H:i',strtotime($params['punch_out_time']));
			$in_date=$year.'-'.$month.'-'.$day.' '.$punch_in_time;
			$out_date=$year.'-'.$month.'-'.$day.' '.$punch_out_time;
			$strtotime = strtotime($in_date);
			$strtotime_out = strtotime($out_date);
			$user_id   = $user_id;
			$a_year    = date('Y',$strtotime);
			$a_month   = date('m',$strtotime);
			$a_day     = date('d',$strtotime);
			$a_cin     = date('H:i',$strtotime);
			$a_cout    = date('H:i',$strtotime_out);
			$where= array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
		  $this->db->select('month_days,month_days_in_out');
		  $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
		  if(empty($record) && $params['ro_status']==1){
			$inputs['attendance_month'] =$a_month;
			$inputs['attendance_year'] = $a_year;
			Attendance_model::attendance($user_id,$inputs);
			$this->db->select('month_days,month_days_in_out');
			$record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
		  }
     
      if(!empty($record['month_days'])){
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);

        $a_day -=1;
		
        
         if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);
          
  

          if($record_day[$a_day]['punch_in'] ==''){
            $record_day[$a_day]['punch_in'] = $a_cin;
			 $record_day[$a_day]['punch_out'] = $a_cout;
            $record_day[$a_day]['day'] = 1;
          }
		  $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>$a_cout);
		$month_days_in_out_record[$a_day] = $current_days;
		 }
		  
      }
 
	if($params['ro_status']==1){
	
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
	}

	$where1= array('user_id'=>$user_id,'a_day'=>$a_day+1,'a_month'=>$a_month,'a_year'=>$a_year);
	  $this->db->where($where1);
      $this->db->update('dgt_attendance_details_ar', array('ro_status'=>$params['ro_status'],'reject_reason'=>$params['reject_reason'],'ro_id'=>$this->session->userdata('user_id')));
	  
	  
	  

      // $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year,'a_day'=>$a_day+1);
      // $this->db->select('reason');
      // $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
      // if(!empty($reason) && !empty($params['reason'])){
        // $this->db->where($where);
        // $this->db->update('dgt_attendance_reason', array('reason'=>$params['reason']));
      // }
      // else{
        // if(!empty($params['reason'])){
          // $where['reason'] = $params['reason'];
          // $this->db->insert('dgt_attendance_reason',$where );
        // }
      // }

   }		

		
			$this->session->set_flashdata('tokbox_success', 'Check out successfully saved');
      if (User::is_admin()) {
			redirect('attendance');
      }
      else{
        redirect('attendance/ro_attendance_view');
      }
   
		 }  
	   
	   else
	   {
        $data['user_id'] = $user_id;
        $data['atten_day'] = $day;
        $data['atten_month'] = $month;
        $data['atten_year'] = $year;
        $where = array('user_id'=>$user_id,'a_day'=>$day,'a_month'=>$month,'a_year'=>$year);
        $this->db->select('*');
        // $data['record']  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
        $data['record']  = $this->db->get_where('dgt_attendance_details_ar',$where)->row_array();
		// echo '<pre>';print_r($data);exit;
        // $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year,'a_day'=>$day);
        $this->db->select('reason');
        $reason =  $this->db->get_where('dgt_attendance_details_ar',$where)->row_array()->reason;
        $data['reason']  = '';
        if(!empty($reason)){
          $data['reason']  = $reason['reason'];
        }
        $this->load->view('modal/update_attendance_ar_ro', $data);
	   }
            
   }
	

public function update_attendance_ar_ro_regul($user_id,$day,$month,$year)
	{
	   if($_POST)
	   {
		   $params = $this->input->post();
		   if(!empty($params['punch_in_time'])){
			
			$punch_in_time = date('H:i',strtotime($params['punch_in_time']));
			$punch_out_time = date('H:i',strtotime($params['punch_out_time']));
			$in_date=$year.'-'.$month.'-'.$day.' '.$punch_in_time;
			$out_date=$year.'-'.$month.'-'.$day.' '.$punch_out_time;
			$strtotime = strtotime($in_date);
			$strtotime_out = strtotime($out_date);
			$user_id   = $user_id;
			$a_year    = date('Y',$strtotime);
			$a_month   = date('m',$strtotime);
			$a_day     = date('d',$strtotime);
			$a_cin     = date('H:i',$strtotime);
			$a_cout    = date('H:i',$strtotime_out);
			$where= array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
		  $this->db->select('month_days,month_days_in_out');
		  $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
		  if(empty($record) && $params['ro_status']==1){
			$inputs['attendance_month'] =$a_month;
			$inputs['attendance_year'] = $a_year;
			Attendance_model::attendance($user_id,$inputs);
			$this->db->select('month_days,month_days_in_out');
			$record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
		  }
     
      if(!empty($record['month_days'])){
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);

        $a_day -=1;
		
        
         if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);
          
  

          if($record_day[$a_day]['punch_in'] ==''){
            $record_day[$a_day]['punch_in'] = $a_cin;
			 $record_day[$a_day]['punch_out'] = $a_cout;
            $record_day[$a_day]['day'] = 1;
          }
		  $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>$a_cout);
		$month_days_in_out_record[$a_day] = $current_days;
		 }
		  
      }
	if($params['ro_status']==1){
	
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
    
      $req_approve_data= Attendance_model::attendance_regulization_update($_POST, $user_id, $in_date, $out_date); 
  }

	$where1= array('user_id'=>$user_id,'a_day'=>$a_day+1,'a_month'=>$a_month,'a_year'=>$a_year);
	  $this->db->where($where1);
      $this->db->update('dgt_attendance_details_ar', array('ro_status'=>$params['ro_status'],'reject_reason'=>$params['reject_reason'],'ro_id'=>$this->session->userdata('user_id')));

      $message = App::email_template('attendance_regularization_approve','template_body');
      $subject = App::email_template('attendance_regularization_approve','subject');
      $signature = App::email_template('email_signature','template_body');

      $logo_link = create_email_logo();

      $reason  = '';
      switch($record['reason']){
        case 1:
          $reason	=	'Forgot to Check In';
          break;
        case 2:
          $reason	=	'Forgot to Check Out';
          break;
        case 3:
          $reason	=	'Network Issues';
          break;
        case 4:
          $reason	=	'On Duty';
          break;
        case 5:
          $reason	=	'Permission';
          break;
      }
      

      $account_details=$this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
      $message = str_replace("{INVOICE_LOGO}",$logo_link,$message);
      $message = str_replace("{USER}",$account_details['fullname'],$message);
      if(empty($params['reject_reason'])){
        $message = str_replace("{APPROVE_REJECT}",'Approved',$message);
      }else{
        $message = str_replace("{APPROVE_REJECT}",'Rejected',$message);
      }
      $message = str_replace("{ATTENDANCE_DATE}",$record['a_year'].'-'.$record['a_month'].'-'.$record['a_day'],$message);
      $message = str_replace("{CHECK_IN}",$punch_in_time,$message);
      $message = str_replace("{CHECK_OUT}",$punch_out_time,$message);
      $message = str_replace("{REASON}",$reason,$message);
      $message = str_replace("{SIGNATURE}",$signature,$message);
      if(empty($params['reject_reason'])){
        $message = str_replace("{REJECT_REASON}",'',$message);
      }else{
        $message = str_replace("{REJECT_REASON}",$params['reject_reason'],$message);
      }
      $team_lead_details = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
      if(!empty($team_lead_details['email'])){
        // $params['recipient'] = $team_lead_details['email'];

        $params['recipient'] ='ankitagrawal@velankanigroup.com';
        
        if($subject != "")
        {
          $params['subject'] = $subject;
        }
        else{
          $params['subject'] = "Regulization Request Response";
        }

        if($message != "")
        {
          $params['message'] = $message;
        }
        else{
          $params['message'] = "We have taken action on your request.";
        }

        $params['attached_file'] = '';

        // modules::run('fomailer/send_email',$params);
        
      }
	  
      // $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year,'a_day'=>$a_day+1);
      // $this->db->select('reason');
      // $reason =  $this->db->get_where('dgt_attendance_reason',$where)->row_array();
      // if(!empty($reason) && !empty($params['reason'])){
        // $this->db->where($where);
        // $this->db->update('dgt_attendance_reason', array('reason'=>$params['reason']));
      // }
      // else{
        // if(!empty($params['reason'])){
          // $where['reason'] = $params['reason'];
          // $this->db->insert('dgt_attendance_reason',$where );
        // }
      // }

   }		
	if($params['ro_status']==1)
	{
		$this->session->set_flashdata('tokbox_success', 'Approved successfully..!');
	}
	else
	{
		$this->session->set_flashdata('tokbox_success', 'Rejected successfully..!');
	}
		
      if (User::is_admin()) {
			redirect('attendance/regularization_request_ro');
      }
      else{
        redirect('attendance/regularization_request_ro');
      }
   
		 }  
	   
	   else
	   {
        $data['user_id'] = $user_id;
        $data['atten_day'] = $day;
        $data['atten_month'] = $month;
        $data['atten_year'] = $year;
        $where = array('user_id'=>$user_id,'a_day'=>$day,'a_month'=>$month,'a_year'=>$year);
        $this->db->select('*');
        // $data['record']  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
        $data['record']  = $this->db->get_where('dgt_attendance_details_ar',$where)->row_array();
		// echo '<pre>';print_r($data);exit;
        // $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year,'a_day'=>$day);
        $this->db->select('reason');
        $reason =  $this->db->get_where('dgt_attendance_details_ar',$where)->row_array()->reason;
        $data['reason']  = '';
        if(!empty($reason)){
          $data['reason']  = $reason['reason'];
        }
        $this->load->view('modal/update_attendance_ar_ro_regul', $data);
	   }
            
   }


   public function save_lop_month_wise()
   {
      extract($_POST);
      $where = array('a_month'=>$_POST['atte_month'],'a_year'=>$_POST['atte_year'],'user_id'=>$_POST['user_id']);
      // if(!empty($reason) && !empty($params['reason'])){
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('lop'=>$_POST['lop_value']));
      // }

       // // Employee Leave Calcualtion
      $dedcution_cal = array();
      $dedcution_cal = $this->leave_calculate($_POST['user_id']); 
       //  Added Razorpay api for deduction of salary as lop
       $this->load->model("Razorpay_payroll","razorpay");
       $this->razorpay->employee_deducition_lop($_POST, $dedcution_cal);

   }
   

    // Employee Leave Calcualtion
  public function leave_calculate($user_id = '')
   {
    $return_data = array();
    $this->db->select('du.*, dad.*');
    $this->db->from("dgt_users as du");
    $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
    $this->db->where("du.id", $user_id);
    $query = $this->db->get();
    $user1 = $query->row_array();

    $leave_types = $this->db->select('*')->from('common_leave_types')->where('branch_id',$user1['branch_id'])->order_by('leave_type','asc')->get()->result_array();
    $req_cur_date = date('Y').'-01-01';
    $i=0;
    $total_booked = $total_available = $total = 0;
    if(!empty($leave_types)){
      foreach($leave_types as $leave_type1){
        $this->db->select('sum(leave_days) as tot_leave')->from('user_leaves')->where('user_id',$user1['user_id'])->where('leave_type',$leave_type1['leave_type_id'])->where('status',1) ;
        if(!empty($_GET['leave_month'])){
          $where_moth = "(month(dgt_user_leaves.leave_from) <= '".$_GET['leave_month']."' OR month(dgt_user_leaves.leave_to) <= '".$_GET['leave_month']."')";
          $this->db->where($where_moth) ;
        }
        if(!empty($_GET['leave_year'])){
          $where_year = "(year(dgt_user_leaves.leave_from) <= '".$_GET['leave_year']."' OR year(dgt_user_leaves.leave_to) <= '".$_GET['leave_year']."')";
          $this->db->where($where_year);
        }
        $ch_user_leves = $this->db->get()->result_array();
        $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] =  0;
        if(!empty($ch_user_leves[0]['tot_leave'])){
          $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] = $ch_user_leves[0]['tot_leave'];
        }else{
          $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] = 0;
        }
        
        $total_leave = $leave_type1['leave_days'];
        $available = $leave_type1['leave_days'] - $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
        if($leave_type1['leave_type_id'] == 9 || $leave_type1['leave_type_id'] == 24 || $leave_type1['leave_type_id'] == 28){
          $minus_leave = 0;
          if(strtotime($req_cur_date)<strtotime($user1['doj'])){
            $cur_mon = (int)date('m',strtotime($user1['doj']));
            $cur_day1 = (int)date('d',strtotime($user1['doj']));
            if($cur_day1<=15){
              $minus_leave = ($cur_mon-1)*$leave_type1['leave_day_month'];
            }
            else{
              $minus_leave = ($cur_mon)*$leave_type1['leave_day_month'];
            }
          }
          $extact_mon = (int)date('m');
          $total_leave = ($extact_mon * $leave_type1['leave_day_month']) - $minus_leave;
          if($total_leave<=0){
            $total_leave = 0;
          }
          
          $num_month = (!empty($_GET['leave_month']))?(int) $_GET['leave_month']:(int) date('m');
          
          $tot_num_day = ($num_month * $leave_type1['leave_day_month']) - ($return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked']);
          $leave_type1['leave_days'] = $tot_num_day;
          $available = $leave_type1['leave_days'] -$minus_leave;
          
        }
        
        if($leave_type1['leave_type_id'] == 47){
          $leave_list = $this->db->select("sum(leave_days) as tot_leave")->from('compensatory_leave')->where("user_id",$user1['user_id'])->where('status',1)->get()->result_array();
          $total_leave = 0;
          if(!empty($leave_list[0]['tot_leave'])){
            $total_leave = $leave_list[0]['tot_leave'];
          }
          
          $available = $total_leave;
          if(!empty($return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'])){
            $available = $total_leave - $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
          }
        }
        
        $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['available'] = $total_leave- $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
        $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['total'] = $total_leave;
        $total_booked =  $return_data[str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] +$total_booked;
        $total_available = $total_available + $available;
        $total = $total_leave +$total;
      }
    }
    return $return_data;
   }
   

   public function employee_attendance($emp_id = '', $attendance_type='', $tmp_attendance = array())
   {

    if($emp_id < 1 || $emp_id == '')
    {
      return false;
    }

    $employee_id = $emp_id;
    
    $response = Attendance_model::employee_attendance($employee_id, $attendance_type, $tmp_attendance); 


   }
}
