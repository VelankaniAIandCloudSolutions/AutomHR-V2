<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collaborator extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in(); 
		// $this->load->model(array( 'App', 'Attendance_model'));
		$this->load->model(array('Project','App','Welcome','Attendance_model'));

		if (User::is_admin()) {
			redirect('welcome');
		}
		if (User::is_client()) {
			redirect('clients');
		}

		$this->applib->set_locale();
	}

	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('welcome').' - '.config_item('company_name'));
	$data['page'] = lang('home');
	$data['task_checkbox'] = TRUE;

	// print_r($this->config->item("lms_username"));exit;	
	//to collect my goals API
	$cdata = array('username'=>$this->config->item("lms_username"),'password' =>$this->config->item("lms_password"));
	$url ='http://dev.trainotel.com/api/v1/token/login';
	$handle = curl_init($url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_POST, true);
	// curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $cdata);
	// curl_exec($handle);
	$result=curl_exec($handle);
	curl_close($handle);
	$token_res = json_decode($result, true);
	
	if(!empty($token_res['auth_token']))
	{
		$user_id = $this->session->userdata('user_id');   
		$user_details = $this->db->select('email')
						 ->from('dgt_users')
						 ->where('id', $user_id)
						 ->get()->row_array();
		$token=$token_res['auth_token'];
		
		$cdata1 = array('email'=>base64_encode($user_details['email']));
		// $cdata1 = array('email'=>'bWFuYWdlckBnbWFpbC5jb20=');
		$urls ='http://dev.trainotel.com/api/v1/automhr_api/get_my_goals/';
		$handles = curl_init($urls);
		curl_setopt($handles, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handles, CURLOPT_POST, true);
		$headers1 = array(
		"Authorization: Token ".$token."",
		"Content-Type: application/json"
		);
		curl_setopt($handles, CURLOPT_HTTPHEADER, $headers1);
		curl_setopt($handles, CURLOPT_POSTFIELDS, json_encode($cdata1));
		// curl_exec($handles);
		$results=curl_exec($handles);
		if (curl_errno($handles)) {
			$error_msg = curl_error($handles);
		}
		curl_close($handles);
		$data_res = json_decode($results, true);
		// echo '<pre>';print_r($data_res);exit;
		if(isset($data_res['goals']))
		{
			$data['goals']=$data_res['goals'];
		}
		else
		{
			$data['goals']='';
		}
		
	}

	//


	$this->template
	->set_layout('users')
	->build('welcome',isset($data) ? $data : NULL);
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
        
         if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);
          
  

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
          

        }
      }
      
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));

      // echo $this->db->last_query();die;

      //  Added Razorpay api for attandence checkin 
      // $this->load->model("Razorpay_payroll","razorpay");
      // $this->razorpay->checkincheckout($user_id, $strtotime, 'checkin');

   }

   $this->session->set_flashdata('tokbox_success', 'Check in successfully saved');
   return redirect('collaborator');
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
   $this->session->set_flashdata('tokbox_success', 'Check out successfully saved');

   //  Added Razorpay api for attandence checkout 
    // $this->load->model("Razorpay_payroll","razorpay");
    // $this->razorpay->checkincheckout($user_id, $strtotime, 'checkout');

      
   // $this->session->set_flashdata('message', 'Check out successfully saved.');
   return redirect('collaborator');
   }

   }
	
}

/* End of file collaborator.php */