<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Leaves extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Leaves_model'));
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        App::module_access('menu_leaves');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Leaves'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
				$data['datatables'] = TRUE;
                $data['page']       = 'Leaves';
                $data['role']       = $this->tank_auth->get_role_id();
				$branch_id			= $this->session->userdata('branch_id');
				$all_leave_types = $this->db->get_where('common_leave_types',array('status'=>'0','branch_id'=>$branch_id))->result_array();
				$user_id      = $this->tank_auth->get_user_id(); 
				$data['cmp_offs'] = $this->get_cmp_offs_days($branch_id,$user_id);
				
                $this->template
					 ->set_layout('users')
					 ->build('leaves',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     } 
	 
	 function ajax_leaves(){
		$params = $_GET;
		$return_data = array();
		
		
		if(empty($_GET['search']['value'])){
			if(isset($_GET['leave_type']) && isset($_GET['leave_status'])  && isset($_GET['employee_name'])  && isset($_GET['leave_from'])  && isset($_GET['leave_to']) && ($_GET['leave_type']!='' || $_GET['leave_status']!='' || $_GET['employee_name']!='' || $_GET['leave_from']!='' || $_GET['leave_to']!=''  )){

				$leave_from    = date('Y-m-d',strtotime($this->input->get('leave_from')));
				$leave_to      = date('Y-m-d',strtotime($this->input->get('leave_to')));

				$req_where	   = "";

				if($_GET['leave_type']!=''){
					$req_where	.=	" and ul.leave_type = '".$_GET['leave_type']."' ";
				}
				if($_GET['leave_status']!=''){
					$req_where	.=	" and ul.status = '".$_GET['leave_status']."' ";
				}
				if($_GET['employee_name']!=''){
					$req_where	.=	" and  ad.fullname like '%".$_GET['employee_name']."%' ";
				}
				if($_GET['leave_from']!=''){
					$req_where	.=	" and ul.leave_from >= '".$leave_from."' ";
				}
				if($_GET['leave_to']!=''){
					$req_where	.=	" and ul.leave_to <= '".$leave_to."' ";
				}

				$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname FROM `dgt_user_leaves` ul left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id left join dgt_account_details ad on ad.user_id = ul.user_id  where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." ".$req_where."  order by ul.id  DESC limit ".$params['start'].",".$params['length'])->result_array();
			}
			else{
				$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname FROM `dgt_user_leaves` ul left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id left join dgt_account_details ad on ad.user_id = ul.user_id  where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  order by ul.id  DESC limit ".$params['start'].",".$params['length'])->result_array();
			}
			$data_all = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname FROM `dgt_user_leaves` ul left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id left join dgt_account_details ad on ad.user_id = ul.user_id  where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." ".$req_where."  order by ul.id  DESC ")->result_array();

		} 
		else{
			if(isset($_GET['leave_type']) && isset($_GET['leave_status'])  && isset($_GET['employee_name'])  && isset($_GET['leave_from'])  && isset($_GET['leave_to']) && ($_GET['leave_type']!='' || $_GET['leave_status']!='' || $_GET['employee_name']!='' || $_GET['leave_from']!='' || $_GET['leave_to']!=''  )){

				$leave_from    = date('Y-m-d',strtotime($this->input->get('leave_from')));
				$leave_to      = date('Y-m-d',strtotime($this->input->get('leave_to')));
				$req_where	   = "";

				if($_GET['leave_type']!=''){
					$req_where	.=	" and ul.leave_type = '".$_GET['leave_type']."' ";
				}
				if($_GET['leave_status']!=''){
					$req_where	.=	" and ul.status = '".$_GET['leave_status']."' ";
				}
				if($_GET['employee_name']!=''){
					$req_where	.=	" and  ad.fullname like '%".$_GET['employee_name']."%' ";
				}
				if($_GET['leave_from']!=''){
					$req_where	.=	" and ul.leave_from >= '".$leave_from."' ";
				}
				if($_GET['leave_to']!=''){
					$req_where	.=	" and ul.leave_to <= '".$leave_to."' ";
				}


				$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname FROM `dgt_user_leaves` ul left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id left join dgt_account_details ad on ad.user_id = ul.user_id  where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  ".$req_where." and (ad.fullname like '%".$_GET['search']['value']."%' or ul.leave_reason like '%".$_GET['search']['value']."%'  or ul.leave_type like '%".$_GET['search']['value']."%' or lt.leave_days like '%".$_GET['search']['value']."%' or DATE_FORMAT(ul.leave_from,'%d-%m-%Y') like '%".$_GET['search']['value']."%' or DATE_FORMAT(ul.leave_to,'%d-%m-%Y') like '%".$_GET['search']['value']."%') order by ul.id  DESC limit ".$params['start'].",".$params['length'])->result_array();
				
			}
			else{
				$req_where	=	'';
				$pending = (string) strpos('Pending', $_GET['search']['value']);
				$rejected = (string) strpos('Rejected', $_GET['search']['value']);
				$approved = (string) strpos('Approved', $_GET['search']['value']);
				
				if($approved !='' ){
					 $req_where .= " or ul.status = '1' ";
				}
				if($pending !=''){
					$req_where .= " or ul.status = '0' ";
			   }
				if($rejected !='' ){
					$req_where .= " or ul.status = '2' ";
		   		} 
				$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname FROM `dgt_user_leaves` ul left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id left join dgt_account_details ad on ad.user_id = ul.user_id  where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and (ad.fullname like '%".$_GET['search']['value']."%' or ul.leave_reason like '%".$_GET['search']['value']."%'  or lt.leave_type like '%".$_GET['search']['value']."%' or lt.leave_days like '%".$_GET['search']['value']."%' or DATE_FORMAT(ul.leave_from,'%d-%m-%Y') like '%".$_GET['search']['value']."%' or DATE_FORMAT(ul.leave_to,'%d-%m-%Y') like '%".$_GET['search']['value']."%' ".$req_where.")  order by ul.id  DESC limit ".$params['start'].",".$params['length'])->result_array();
			}
			$data_all = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname FROM `dgt_user_leaves` ul left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id left join dgt_account_details ad on ad.user_id = ul.user_id  where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." ".$req_where."  and (ad.fullname like '%".$_GET['search']['value']."%' or ul.leave_reason like '%".$_GET['search']['value']."%'  or lt.leave_type like '%".$_GET['search']['value']."%' or lt.leave_days like '%".$_GET['search']['value']."%' or DATE_FORMAT(ul.leave_from,'%d-%m-%Y') like '%".$_GET['search']['value']."%' or DATE_FORMAT(ul.leave_to,'%d-%m-%Y') like '%".$_GET['search']['value']."%' ".$req_where.")  order by ul.id  DESC")->result_array();
			
		}
		$return_data['recordsTotal'] = count($data_all);
		$return_data['recordsFiltered'] = count($data_all);
		$return_data['draw'] = isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
		$return_data['data'] = [];
		if(!empty($leave_list)){
			$sl_no = $params['start'];
			$i  = 0;
			foreach($leave_list as $key => $levs){ 
				$j = $i+1;
				$show_leave = base_url().'leaves/show_leave/'.$levs['user_id'];
				$return_data['data'][$i]['s_no'] = $sl_no +$j;
				$return_data['data'][$i]['fullname'] = '<a class="text-info" href="'.$show_leave.'">'.$levs['fullname'].'</a>';
				$return_data['data'][$i]['leave_type'] = $levs['l_type'];
				$return_data['data'][$i]['leave_from'] = (!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):'';
				$return_data['data'][$i]['leave_to'] = (!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):'';
				$return_data['data'][$i]['leave_reason'] = $levs['leave_reason'];
				$return_data['data'][$i]['leave_days'] = $levs['leave_days'];

				if($levs['leave_day_type'] == 1){
					$return_data['data'][$i]['leave_days'] .=  ' ( Full Day )';
				}else if($levs['leave_day_type'] == 2){
					$return_data['data'][$i]['leave_days'] .=  ' ( First Half )';
				}else if($levs['leave_day_type'] == 3){
					$return_data['data'][$i]['leave_days'] .=  ' ( Second Half )';
				}
				
				if($levs['status'] == 4){
					$return_data['data'][$i]['status']	=   '<span class="label label-info"> TL - Approved</span><br><span class="label label-danger"> Management - Pending</span>';
				}
				else if($levs['status'] == 7){
					$return_data['data'][$i]['status']	=  '<span class="label label-danger"> Deleted </span>';
				}
				else if($levs['status'] == 0){
					$return_data['data'][$i]['status']	=  ' <span class="label" style="background:#D2691E"> Pending </span>';
				}else if($levs['status'] == 1){
					$return_data['data'][$i]['status']	=  '<span class="label label-success"> Approved </span> ';
				}else if($levs['status'] == 2){
					$return_data['data'][$i]['status']	=  '<span class="label label-danger"> Rejected</span>';
				}else if($levs['status'] == 3){
					$return_data['data'][$i]['status']	=  '<span class="label label-danger"> Cancelled</span>';
				}
				else if($levs['status'] == 5){
					$return_data['data'][$i]['status']	=  '<span class="label label-danger"> TL - Rejected</span>';
				}
				$approve_url = base_url().'leaves/approve/management/'.$levs['id'];
				$reject_url  = base_url().'leaves/reject/management/'.$levs['id'];
				$return_data['data'][$i]['action']	= '<a  class="btn btn-success btn-xs" data-toggle="ajaxModal" href="'.$approve_url.'" title="Approve" data-original-title="Approve"><i class="fa fa-thumbs-o-up"></i> </a><a class="btn btn-danger btn-xs" data-toggle="ajaxModal" href="'.$reject_url.'" title="Reject" data-original-title="Reject"><i class="fa fa-thumbs-o-down"></i> </a>';
				$i++;
			}
		}
		echo json_encode($return_data);exit;
	 }
	 function get_cmp_offs_days($branch_id,$user_id){

		$branches = $this->db->get_where('dgt_branches',array('branch_id'=>$branch_id))->result_array();
		if(!empty($branches[0]['weekend_workdays'])){
			$work_weekend = json_decode($branches[0]['weekend_workdays']);
		}
		$num_month = 5;
		$current_date = date('Y-m-d');
		$newDate = date('Y-m-d', strtotime($date. ' - '.$num_month.' months'));
		$req_dates = $leave_dates = $cmp_offs = array();
		for($i=0;$i<=$num_month;$i++){
			$req_dates[$i] = date('Y-m-d', strtotime($newDate. ' + '.$i.' months'));
		}
		$holidays = $this->db->from('dgt_holidays')->where(array('holiday_national'=>1))->group_by('holiday_date')->get()->result_array();
		
		if(!empty($holidays)){
			foreach($holidays as $holiday1){
				$leave_dates[] = $holiday1['holiday_date'];
			}
		}
		if(!empty($req_dates)){
			foreach($req_dates as $date1){
				$colMonth = date('M',strtotime($date1));
				$a_year = date('Y',strtotime($date1));
				if (empty($work_weekend) || !in_array(1, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('first saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(2, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('second saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(3, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('third saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(4, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fourth saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(5, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fifth saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}

				if (empty($work_weekend) || !in_array(6, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('first Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(7, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('second Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(8, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('third Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(9, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fourth Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(10, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fifth Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}

			}
		}
		if(!empty($leave_dates)){
			$k = 0;
			$req_comp_off1 = [];
			foreach($leave_dates as $leave_date1){
				$a_month = (int) date('m',strtotime($leave_date1));
				$a_year = (int) date('Y',strtotime($leave_date1));
				$a_day =	(int)date('d', strtotime($leave_date1));
				$where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
				$this->db->select('month_days,month_days_in_out');
				$this->db->from('attendance_details');
				$this->db->where($where);
				$results  = $this->db->get()->result_array();

				if(!empty($results[0])){
					$month_days			 =  unserialize($results[0]['month_days']);
					$month_days_in_out	 =  unserialize($results[0]['month_days_in_out']);
					$day 				 =  $month_days[$a_day-1];
					$day_in_out 		 =	$month_days_in_out[$a_day-1];
					$latest_inout 		 =	end($day_in_out);
					
					foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
					{
						if((!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']) )||( !empty($month_days_in_out[1]['punch_in']) && !empty($month_days_in_out[1]['punch_out'])) )
						{	
							$production_hour = 0;
							foreach ($month_days_in_out[$a_day-1] as $punch_detail1) {
								$production_hour = $production_hour +time_difference(date('H:i',strtotime($punch_detail1['punch_in'])),date('H:i',strtotime($punch_detail1['punch_out'])));;
							}
							
							$hours_val = sprintf("%02d", intdiv($production_hour, 60));
							$mints_val = sprintf("%02d", ($production_hour % 60));
							$prod_hour1 += $hours_val.'h '. $mints_val.'m';
							$prod_hour = $hours_val.'h '. $mints_val.'m';
							$seconds = $hours_val * 3600 + $mints_val * 60 ;
							$total_seconds += $seconds;
							$total_production_hour += $production_hour;
							$total_secs = 8*3600;
							$progress_bar = ($seconds/$total_secs)*100;
							$actProductionHour =  intdiv($production_hour, 60);

							$check_leaves = $this->db->get_where('dgt_compensatory_leave',array('user_id'=>$user_id,'leave_from'=>$leave_date1))->result_array();

							if(empty($check_leaves[0])){
								if (!in_array($leave_date1, $req_comp_off1)){
									$req_comp_off1[$k] = $leave_date1;
									if($actProductionHour>=8){
										$cmp_offs[$k]['dates'] = $leave_date1;
										$cmp_offs[$k]['worked'] = 1;
										$k++;
									}
									else if($actProductionHour>=4){
										$cmp_offs[$k]['dates'] = $leave_date1;
										$cmp_offs[$k]['worked'] = 0.5;
									}
								}
							}
							
						}
						
					}
					
				}
				
			}
		}
		return $cmp_offs;


	 }
	 function admin_login() //this is ADMIN LOGIN WITHOUT PASSWORD FUNCTION
	 { 
	     $user = $this->db->query("SELECT * FROM `dgt_users` where id = 1")->result_array();  
		 $this->session->set_userdata(array(
												'user_id'   => $user[0]['id'],
												'username'  => $user[0]['username'],
												'role_id'   => $user[0]['role_id'],
												'status'	=> ($user[0]['activated'] == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
										    ));
 		 redirect('/leaves');								
 	 } 
	 
	 function sts_update($leave_tbl_id = 0 ,$sts_type = 0) 
	 {  
	    $log_in_sts = false;
 		if(!$this->tank_auth->is_logged_in()) {	  
			$user = $this->db->query("SELECT * FROM `dgt_users` where id = 1")->result_array();  
			if(!empty($user)){
 				$this->session->set_userdata(array(
														'user_id'   => $user[0]['id'],
														'username'  => $user[0]['username'],
														'role_id'   => $user[0]['role_id'],
														'status'	=> ($user[0]['activated'] == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
												  ));
				$log_in_sts = true;	 
			}else{ 
			    $log_in_sts = false;
			}
		}else{
			 $log_in_sts = true;
		}   
		if($log_in_sts){ 
			$chk = $this->db->query("select * from dgt_user_leaves where id = ".$leave_tbl_id."")->result_array();
			if(isset($chk[0]['status']) && $chk[0]['status'] == 0){
				$det['status']  = $sts_type; 
				$this->db->update('dgt_user_leaves',$det,array('id'=>$leave_tbl_id));  
				$head_str = "  ";
				if($sts_type == 1){
					$head_str = " Approved ";
				}else if($sts_type == 2){
					$head_str = " Rejected ";
				}  
				$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$chk[0]['user_id']." ")->result_array();
				$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$chk[0]['user_id']." ")->result_array();
				if(!empty($acc_det) && !empty($user_det)){
					$recipient       = array();
					if($user_det[0]['email'] != '') { $recipient[] = $user_det[0]['email']; }
					$subject         = " Leave Request ".$head_str;
					$message         = '<div style="height: 7px; background-color: #535353;"></div>
											<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
												<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request '.$head_str.'</div>
												<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
													<p> Hi '.$acc_det[0]['fullname'].',</p>
													<p> Your Leave Request for '.date('d-m-Y',strtotime($chk[0]['leave_from'])).' to '.date('d-m-Y',strtotime($chk[0]['leave_to'])).' has been '.$head_str.' by Admin </p> 
													<br>  
													<a style="text-decoration:none;" href="http://dreamguystech.com/hrm/"> 
														<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
													</a>
													<br>
													</big><br><br>Regards<br>The '.config_item('company_name').' Team
												</div>
										 </div>';  
					if(!empty($recipient) && count($recipient) > 0){		 
						$params      = array(
												'recipient' => $recipient,
												'subject'   => $subject,
												'message'   => $message
											);   
						$succ = Modules::run('fomailer/send_email',$params); 	
					}
				}   
				
			}else{
			    //here alert message	
			}
			redirect('/leaves');
		}else{
			redirect('');
		}
	} 

	function addCompenstory()
	{
 		if($this->tank_auth->is_logged_in()) { 
		 if ($this->input->post()) {
			$user_id              = $this->tank_auth->get_user_id(); //echo $user_id;exit;
			$branch_id			  = $this->session->userdata('branch_id');
			$cmp_offs			  = $this->get_cmp_offs_days($branch_id,$user_id);
  			$det['user_id']       = $user_id;
			$det['teamlead_id']   = $this->input->post('teamlead_id'); 
 			$det['leave_from']    = date('Y-m-d',strtotime($this->input->post('req_compen_leave_date_from')));
			$det['leave_to']      = date('Y-m-d',strtotime($this->input->post('req_compen_leave_date_to')));

			$cmp_offs_days		  = array_column($cmp_offs, 'dates');

			if(!in_array($det['leave_from'],$cmp_offs_days) || !in_array($det['leave_to'],$cmp_offs_days)){
				redirect('leaves');exit;
			}
			if(strtotime($det['leave_from']) != strtotime($det['leave_to'])){
				redirect('leaves');exit;
			}
			$key = array_search ($det['leave_from'], $cmp_offs_days);
			if(empty($cmp_offs[$key]['worked'])){
				redirect('leaves');exit;
			}
			else{
				if($cmp_offs[$key]['worked'] != $this->input->post('req_compen_leave_count')){
					redirect('leaves');exit;
				}
			}
			
  			$qry                    =  "SELECT * FROM `dgt_compensatory_leave` WHERE user_id = ".$user_id."
									  and (leave_from >= '".$det['leave_from']."' or leave_to <= '".$det['leave_to']."')   and status = 0 "; 
 			$contdtn    		   = true;					  
 			$leave_list 		   = $this->db->query($qry)->result_array();
  			$d1 		 		   = strtotime($this->input->post('req_compen_leave_date_from'));
 			$d2 		 		   = strtotime($this->input->post('req_compen_leave_date_to'));
 			$array1     		   = array();
			for($i = $d1; $i <= $d2; $i += 86400 ){  $array1[] = $i; }  
  			if(!empty($leave_list)){ 
				foreach($leave_list as $key => $val)
				{ 
					$d11  = strtotime($val['leave_from']);
 			        $d22  = strtotime($val['leave_to']);
					for($i = $d11; $i <= $d22; $i += 86400 ){
						if(in_array($i,$array1)){
							$contdtn = false;	
							break;
						} 
					}  
					if(!$contdtn) { break; }
  				}
 			}  
 			if($contdtn){
				$det['leave_days']    = $this->input->post('req_compen_leave_count');  
				if($det['leave_days'] <= 1){
				 //  $det['leave_day_type'] = $this->input->post('req_compen_leave_day_type'); 
				}
				$det['leave_reason']  = $this->input->post('req_leave_reason');
				$det['branch_id']  = $this->session->userdata('branch_id');
				$this->db->insert('compensatory_leave',$det);  
				
				$leave_tbl_id  = $this->db->insert_id();
 				$leave_day_str = $det['leave_days'].' days';
				if($det['leave_days'] < 1){
				 	$leave_day_str = 'Half day';
 				}
				//This is admin alert Email   
				$base_url = base_url();
				
				$login_user_name = $this->tank_auth->get_username();  
				
				// $this->db->select('value');
				// $records = $this->db->get_where('dgt_config',array('config_key'=>'company_email'))->row_array();

				$log_detail = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
				// if($log_detail['teamlead_id'] != 0)
				// {
					$this->db->select('email');
					$send_mail = $this->db->get_where('dgt_users',array('id'=>$log_detail['teamlead_id']))->row_array();
					$send_mail = !empty($send_mail)?$send_mail:'';
				// }else{
				// 	$send_mail = '';
				// }
				if($send_mail != '')
				{
					$recipient       = $send_mail['email'];
				}
				// else{
				// 	$recipient       = array($records['value']);
				// }
				$from_leave = date('d M Y',strtotime($det['leave_from']));
				$to_leave = date('d M Y',strtotime($det['leave_to']));
				$lead_emails = $this->db->get('dgt_lead_reporter')->result_array(); 
				$emails = array(); 
				foreach($lead_emails as $lead_email){
					$emails[] = $lead_email['reporter_email'];
				}
				 
				$subject         = " Employee Compensatory Leave Request ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Applied for Compensatory Leave Request ( from :'.$from_leave.' to '.$to_leave.' ) </p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>'; 			 
				$params      = array(
										'recipient' => $recipient,
										'subject'   => $subject,
										'message'   => $message
									);   
				$succ = Modules::run('fomailer/send_email',$params);


				$mgt_message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Applied for Compensatory Leave Request ( from :'.$from_leave.' to '.$to_leave.' )</p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';

					$params1      = array(
										'recipient' => $emails,
										'subject'   => $subject,
										'message'   => $mgt_message
									);   
				$succ = Modules::run('fomailer/send_email',$params1);

				$this->session->set_flashdata('tokbox_success', lang('compen_request'));


 			}else{
				// $this->session->set_flashdata('alert_message', 'error');
				$this->session->set_flashdata('tokbox_error', lang('Error'));
			}
     		redirect('leaves');
		} 
		}else{
		   redirect('');	
		}
 	}

	function add()
	{
 		if($this->tank_auth->is_logged_in()) { 
		 if ($this->input->post()) {
			$user_id              = $this->tank_auth->get_user_id(); //echo $user_id;exit;
  			$det['user_id']       = $user_id;
			$det['leave_type']    = $this->input->post('req_leave_type'); 
			$det['teamlead_id']    = $this->input->post('teamlead_id'); 
 			$det['leave_from']    = date('Y-m-d',strtotime($this->input->post('req_leave_date_from')));
			$det['leave_to']      = date('Y-m-d',strtotime($this->input->post('req_leave_date_to')));
			if($det['leave_type']==47){
				$qry              =  "SELECT * FROM `dgt_compensatory_leave` WHERE user_id = ".$user_id."
									  and (leave_from >= '".$det['leave_from']."' or leave_to <= '".$det['leave_to']."')   and status = 0 "; 
			}else{
  				$qry              =  "SELECT * FROM `dgt_user_leaves` WHERE user_id = ".$user_id."
									  and (leave_from >= '".$det['leave_from']."' or leave_to <= '".$det['leave_to']."')   and status = 0 "; 
			}
 			$contdtn    		  = true;					  
 			$leave_list 		   = $this->db->query($qry)->result_array();
  			$d1 		 		   = strtotime($this->input->post('req_leave_date_from'));
 			$d2 		 		   = strtotime($this->input->post('req_leave_date_to'));
 			$array1     		   = array();
			for($i = $d1; $i <= $d2; $i += 86400 ){  $array1[] = $i; }  
  			if(!empty($leave_list)){ 
				foreach($leave_list as $key => $val)
				{ 
					$d11  = strtotime($val['leave_from']);
 			        $d22  = strtotime($val['leave_to']);
					for($i = $d11; $i <= $d22; $i += 86400 ){
						if(in_array($i,$array1)){
							$contdtn = false;	
							break;
						} 
					}  
					if(!$contdtn) { break; }
  				}
 			}  
 			if($contdtn){
				$det['leave_days']    = $this->input->post('req_leave_count');  
				if($det['leave_days'] <= 1){
				   $det['leave_day_type'] = $this->input->post('req_leave_day_type'); 
				}
				$det['leave_reason']  = $this->input->post('req_leave_reason');
				$det['branch_id']  = $this->session->userdata('branch_id');
				$this->db->insert('dgt_user_leaves',$det);   
				$leave_tbl_id  = $this->db->insert_id();
 				$leave_day_str = $det['leave_days'].' days';
				if($det['leave_days'] < 1){
				 	$leave_day_str = 'Half day';
 				}
				//This is admin alert Email   
				$base_url = base_url();
				
				$login_user_name = $this->tank_auth->get_username();  
				
				// $this->db->select('value');
				// $records = $this->db->get_where('dgt_config',array('config_key'=>'company_email'))->row_array();

				$log_detail = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
				// if($log_detail['teamlead_id'] != 0)
				// {
					$this->db->select('email');
					$send_mail = $this->db->get_where('dgt_users',array('id'=>$log_detail['teamlead_id']))->row_array();
					$send_mail = !empty($send_mail)?$send_mail:'';
				// }else{
				// 	$send_mail = '';
				// }
				if($send_mail != '')
				{
					$recipient       = $send_mail['email'];
				}
				// else{
				// 	$recipient       = array($records['value']);
				// }
				$from_leave = date('d M Y',strtotime($det['leave_from']));
				$to_leave = date('d M Y',strtotime($det['leave_to']));
				$lead_emails = $this->db->get('dgt_lead_reporter')->result_array(); 
				$emails = array(); 
				foreach($lead_emails as $lead_email){
					$emails[] = $lead_email['reporter_email'];
				}
				 
				$subject         = " Employee Leave Request ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' ) </p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												<a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/4"> 
												<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
												</a>
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/5"> 
												<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
												</a>  
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/0/0"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>'; 			 
				$params      = array(
										'recipient' => $recipient,
										'subject'   => $subject,
										'message'   => $message
									);   
				$succ = Modules::run('fomailer/send_email',$params);


				$mgt_message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' )</p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												<a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/1"> 
												<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
												</a>
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/2"> 
												<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
												</a>  
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/0/0"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';

					$params1      = array(
										'recipient' => $emails,
										'subject'   => $subject,
										'message'   => $mgt_message
									);   
				$succ = Modules::run('fomailer/send_email',$params1);




 			}else{
				// $this->session->set_flashdata('alert_message', 'error');
				$this->session->set_flashdata('tokbox_error', lang('Error'));
			}
     		redirect('leaves');
		} 
		}else{
		   redirect('');	
		}
 	} 
	function approve()
	{
		if ($this->input->post()) {
			if($this->input->post('approve') == 'teamlead')
			{
				$det['reason']      = $this->input->post('reason');  // Teamlead Approval
				// $det['status']      = 4; 
				$det['status']      = 1; 
			}
			if($this->input->post('approve') == 'management')
			{
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 1; 
			}
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$this->input->post('req_leave_tbl_id')." ")->result_array();
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
			$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();
 			if(!empty($acc_det) && !empty($user_det)){
				$recipient       = array();
				if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				$subject         = " Leave Request Approved ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Approved</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.$acc_det[0]['fullname'].',</p>
												<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by Admin </p> 
												<br>  
												<a style="text-decoration:none;" href="'.base_url().'login"> 
													<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';  
				if(!empty($recipient) && count($recipient) > 0){		 
					$params      = array(
											'recipient' => $recipient,
											'subject'   => $subject,
											'message'   => $message
										);   
					$succ = Modules::run('fomailer/send_email',$params); 	
				}
 			}   
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(4);
			$data['teamlead'] = $this->uri->segment(3);
			$this->load->view('modal/approve',$data);
		}
 	}
	 function approve_request()
	 {
		 if ($this->input->post()) {
			 if($this->input->post('approve') == 'teamlead')
			 {
				 $det['reason']      = $this->input->post('reason');  // Teamlead Approval
				 $det['status']      = 1; 
			 }
			 if($this->input->post('approve') == 'management')
			 {
				 $det['reason']      = $this->input->post('reason'); 
				 $det['status']      = 1; 
			 }
			 $this->db->update('compensatory_leave',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			 $leave_det = $this->db->query("SELECT * FROM dgt_compensatory_leave where id = ".$this->input->post('req_leave_tbl_id')." ")->result_array();
			 $acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
			 $check_leave = $this->db->select('*')->from('common_leave_types')->where("leave_type_id",47)->where("branch_id",$acc_det[0]['branch_id'])->get()->result_array(); 
			 if(empty($check_leave) ){
				$det = array();
				$det['leave_type_id']	= 47;
				$det['branch_id']		= $acc_det[0]['branch_id'];
				$det['leave_days']		= 0;
				$det['leave_type']		= 'Compensatory Off';
				$this->db->insert('common_leave_types',$det);  
			 }

			 $user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();
			  if(!empty($acc_det) && !empty($user_det)){
				 $recipient       = array();
				 if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				 $subject         = " Compensatory Leave Request Approved ";
				 $message         = '<div style="height: 7px; background-color: #535353;"></div>
										 <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											 <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Approved</div>
											 <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												 <p> Hi '.$acc_det[0]['fullname'].',</p>
												 <p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by Admin </p> 
												 <br>  
												 <a style="text-decoration:none;" href="'.base_url().'login"> 
													 <button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												 </a>
												 <br>
												 </big><br><br>Regards<br>The '.config_item('company_name').' Team
											 </div>
									  </div>';  
				 if(!empty($recipient) && count($recipient) > 0){		 
					 $params      = array(
											 'recipient' => $recipient,
											 'subject'   => $subject,
											 'message'   => $message
										 );   
					 $succ = Modules::run('fomailer/send_email',$params); 	
				 }
			  }   
			 redirect('leaves');
		  }else{
			 $data['req_leave_tbl_id'] = $this->uri->segment(4);
			 $data['teamlead'] = $this->uri->segment(3);
			 $this->load->view('modal/approve_request',$data);
		 }
	  }
	function reject()
	{
		if ($this->input->post()) {
			if($this->input->post('approve') == 'teamlead')
			{
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 5; 
			}
			if($this->input->post('approve') == 'management')
			{
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 2; 
			}
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
  			$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$this->input->post('req_leave_tbl_id')." ")->result_array();
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
			$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();
 			if(!empty($acc_det) && !empty($user_det)){
				$recipient       = array();
				if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				$subject         = " Leave Request Rejected ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Rejected</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.$acc_det[0]['fullname'].',</p>
												<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been Rejected by Admin </p> 
												<br>  
												<a style="text-decoration:none;" href="'.base_url().'login"> 
													<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';  
				if(!empty($recipient) && count($recipient) > 0){		 
					$params      = array(
											'recipient' => $recipient,
											'subject'   => $subject,
											'message'   => $message
										);   
					$succ = Modules::run('fomailer/send_email',$params); 	
				}
 			}    
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(4);
			$data['teamlead'] = $this->uri->segment(3);
			$this->load->view('modal/reject',$data);
		} 
	}
	function reject_request()
	{
		if ($this->input->post()) {
			if($this->input->post('approve') == 'teamlead')
			{
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 5; 
			}
			if($this->input->post('approve') == 'management')
			{
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 2; 
			}
			$this->db->update('compensatory_leave',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
  			$leave_det = $this->db->query("SELECT * FROM dgt_compensatory_leave where id = ".$this->input->post('req_leave_tbl_id')." ")->result_array();
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
			$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();
 			if(!empty($acc_det) && !empty($user_det)){
				$recipient       = array();
				if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				$subject         = " Leave Request Rejected ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Rejected</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.$acc_det[0]['fullname'].',</p>
												<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been Rejected by Admin </p> 
												<br>  
												<a style="text-decoration:none;" href="'.base_url().'login"> 
													<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';  
				if(!empty($recipient) && count($recipient) > 0){		 
					$params      = array(
											'recipient' => $recipient,
											'subject'   => $subject,
											'message'   => $message
										);   
					$succ = Modules::run('fomailer/send_email',$params); 	
				}
 			}    
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(4);
			$data['teamlead'] = $this->uri->segment(3);
			$this->load->view('modal/reject_request',$data);
		} 
	}
	function cancel()
	{
		if ($this->input->post()) {
			$det['reason']      = $this->input->post('reason'); 
			$det['status']      = 3; 
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(3);
			$this->load->view('modal/cancel',$data);
		}
	
	}
	function compen_cancel()
	{
		if ($this->input->post()) {
			$det['reason']      = $this->input->post('reason'); 
			$det['status']      = 3; 
			$this->db->update('compensatory_leave',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(3);
			$this->load->view('modal/cancel_request',$data);
		}
	
	}
	function delete()
	{
		if ($this->input->post()) {
			$det['status']      = 7; 
			$det['reason']      = $this->input->post('reason'); 
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete',$data);
		}
 	}
	function search_leaves()
	{ 
		$l_entity = $_POST['l_entity'];
 		$l_type =  $_POST['l_type'];
		$l_sts  =  $_POST['l_sts']; 
		$uname  =  $_POST['uname']; 
		$dfrom  =  $_POST['dfrom']; 
		$dto    =  $_POST['dto']; 
		
		if($dfrom != '') $dfrom = date('Y-m-d',strtotime($_POST['dfrom']));
		if($dto != '') $dto = date('Y-m-d',strtotime($_POST['dto']));
 		 
		$qry    =  "SELECT ul.*,lt.leave_type as l_type,ad.fullname
					FROM `dgt_user_leaves` ul
					left join dgt_leave_types lt on lt.id = ul.leave_type
					left join dgt_account_details ad on ad.user_id = ul.user_id 
					where ";
		if($l_entity != ''){ $qry   .=  " ad.branch_id = '".$l_entity."' and "; }
		if($l_type != ''){ $qry   .=  " ul.leave_type = '".$l_type."' and "; } 			
		if($l_sts != ''){ $qry    .=  " ul.status = '".$l_sts."' and "; } 
 		if($uname != ''){ $qry    .=  " ul.user_id = (SELECT user_id FROM `dgt_account_details` WHERE fullname like '%".$uname."%') and "; } 
 		if($dfrom != ''){ $qry    .=  " ul.leave_from >= '".$dfrom."' and "; } 
		if($dto != ''){ $qry      .=  " ul.leave_to <= '".$dto."' and "; } 
   		$qry    .=  " ul.status != 4 and DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." order by ul.id DESC";
 		//echo $qry; exit;
 		$html       = '';	
 		$leave_list = $this->db->query($qry)->result_array();
  	    foreach($leave_list as $key => $levs){   
				 $html    .= '<tr>
								<td>'.($key+1).'</td>
								<td>'.$levs['fullname'].'</td>
								<td>'.$levs['l_type'].'</td>
								<td>'.date('d-m-Y',strtotime($levs['leave_from'])).'</td>
								<td>'.date('d-m-Y',strtotime($levs['leave_to'])).'</td>
								<td>'.$levs['leave_reason'].'</td>
								<td>'; 
								    $html    .=  $levs['leave_days'];
									if($levs['leave_day_type'] == 1){ $html    .= ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){ $html    .= ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){ $html    .= ' ( Second Half )'; } 
				   $html    .= '</td>
								<td>';
									if($levs['status'] == 0){ $html    .= '<span class="label" style="background:#D2691E"> Pending </span>';
									}else if($levs['status'] == 1){ $html    .= '<span class="label label-success"> Approved </span>';
									}else if($levs['status'] == 2){ $html    .= '<span class="label label-danger"> Rejected</span>';
									}else if($levs['status'] == 3){ $html    .= '<span class="label label-danger"> Cancelled</span>'; } 
				   $html    .= '</td>
								<td>';
				   if($levs['status'] == 0){
						  $html    .= '<a  class="btn btn-success btn-xs"  
									 data-toggle="ajaxModal" href="'.base_url().'leaves/approve/'.$levs['id'].'" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a>';
				   } 
				   if($levs['status'] == 0 || $levs['status'] == 1){   
						  $html    .= '&nbsp;<a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="'.base_url().'leaves/reject/'.$levs['id'].'" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a>';
				   }  
				   /*$html    .= '&nbsp;<a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="'.base_url().'leaves/delete/'.$levs['id'].'" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
								 </a>
								</td>
							</tr>';*/
        } 
		if($html == ''){
 			$html = '<tr>
			           <td colspan="9" class="text-center"> No Data Available </td>
 			         </tr>';
 		}  
  		echo $html;  exit;
  	}
	
	function Getholidaydates()
	{ 
 		$holiday_id =  $_POST['holiday_id'];
		$qry    =  "SELECT * FROM `dgt_holidays` where id='".$holiday_id."'";
 		//echo $qry; exit;
 		$html       = '';	
 		$holiday_list = $this->db->query($qry)->row_array();
  		echo date('d-m-Y',strtotime($holiday_list['holiday_date']));  exit;
  	}
	function get_attendance_details()
	{ 
 		$holiday_id =  $_POST['holiday_id'];
 		$user_id =  $_POST['user_id'];
 		$req_leave_date_from =  $_POST['req_leave_date_from'];
 		$req_leave_date_to =  $_POST['req_leave_date_to'];
		
		// $strtotime = strtotime(date('Y-m-d H:i'));
			$strtotime = strtotime($req_leave_date_from);
            $a_year    = date('Y',$strtotime);
            $a_month   = date('m',$strtotime);
            $a_day     = date('d',$strtotime);
			$where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
            $this->db->select('month_days,month_days_in_out');
            $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
			// echo '<pre>';print_r($record);exit;
			$punchin_id = 1;
			$punchin_time = '';
			$production_hour = 0;
			if(!empty($record['month_days'])){
				$month_days =  unserialize($record['month_days']);
				$month_days_in_out =  unserialize($record['month_days_in_out']);
								

				$a_day -=1;
				
				if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  
					$day = $month_days[$a_day];
					$day_in_out = $month_days_in_out[$a_day];
					$latest_inout = end($day_in_out);


					if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
					  $punch_in = $day['punch_in'];
					  $punch_in_out = $latest_inout['punch_in'];
					  $punch_out_in = $latest_inout['punch_out'];
					  $punchin_id = 1;
					}else{
					   $punch_in = $day['punch_in'];
					  $punch_in_out = $latest_inout['punch_in'];
					  $punch_out_in = $latest_inout['punch_out'];
					  $punchin_id = 0;
					}
				 }
				
				foreach ($month_days_in_out[$a_day] as $punch_detail1) {
					$production_hour = $production_hour +time_difference(date('H:i',strtotime($punch_detail1['punch_in'])),date('H:i',strtotime($punch_detail1['punch_out'])));;
				}
			 $punchin_time = date("g:i a", strtotime($day['punch_in']));
			 $punchout_time = date("g:i a", strtotime($day['punch_out']));
		   }

		    $hours_val = sprintf("%02d", intdiv($production_hour, 60));
		    $mints_val = sprintf("%02d", ($production_hour % 60));
		    $prod_hour1 += $hours_val.'h '. $mints_val.'m';
			$prod_hour = $hours_val.'h '. $mints_val.'m';
			$seconds = $hours_val * 3600 + $mints_val * 60 ;
			$total_seconds += $seconds;
			$total_production_hour += $production_hour;
			$total_secs = 8*3600;
			$progress_bar = ($seconds/$total_secs)*100;
			$actProductionHour =  intdiv($production_hour, 60);
		   
			// echo '<pre>';print_r($punchin_time);exit;
			/*if($punchin_time !='')
			{
				$res=1;
			}
			else
			{
				$res=0;
			}*/
			$res = 0;
			if($actProductionHour>=8){
				$res = 1;
			}
			$res = 1;
			echo $res;exit;
			// echo '<pre>';print_r($res);exit;
  	}
	

  	public function leave_check()
  	{
  		$user_id = $_POST['login_id'];
  		// echo $user_id; exit;
  		$total_leaves = array();
  		$normal_leaves = array();
  		$medical_leaves = array();
  		$sick_leaves = array();
  		$leaves = $this->Leaves_model->check_leavesById($user_id);
  		$nor_leaves = $this->Leaves_model->check_leavesBycat($user_id,'1');
  		$med_leaves = $this->Leaves_model->check_leavesBycat($user_id,'2');
  		$sk_leaves = $this->Leaves_model->check_leavesBycat($user_id,'3');
  		for($i=0;$i<count($leaves);$i++)
  		{
  			$total_leaves[] = $leaves[$i]['leave_days'];
  		}
  		foreach($nor_leaves as $n_leave)
  		{
  			$normal_leaves[] = $n_leave['leave_days'];
  		}
  		foreach($med_leaves as $md_leave)
  		{
  			$medical_leaves[] = $md_leave['leave_days'];
  		}
  		foreach($sk_leaves as $sk_leave)
  		{
  			$sick_leaves[] = $sk_leave['leave_days'];
  		}

  		$t_leaves = array_sum($total_leaves);
  		$total_normal_leaves = $this->db->get_where('leave_types',array('id'=>1))->row_array();
  		$lop = ($t_leaves - $total_normal_leaves['leave_days']);
  		if($lop > 0 )
  		{
  			$lop_days = $lop;
  		}else{
  			$lop_days = 0;
  		}

  		$all_leaves = array(
  			'total_leaves' => $t_leaves,
  			'normal_leaves' => array_sum($normal_leaves),
  			'medical_leaves' => array_sum($medical_leaves),
  			'sick_leaves' => array_sum($sick_leaves),
  			'loss_pay' => $lop_days
  		);
  		echo json_encode($all_leaves); exit; 

  	}


  	public function show_leave()
  	{
  		 $user_id = $this->uri->segment(3);
              
  		$this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Leaves'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
				$data['datatables'] = TRUE;
                $data['page']       = 'leaves';
                $data['user_id']    = $user_id;
               
                $this->template
					 ->set_layout('users')
					 ->build('show_leaves',isset($data) ? $data : NULL);
  	}



	  function get_compensatory_attendance_details()
	  { 
		   $user_id =  $_POST['user_id'];
		   $req_leave_date_from =  $_POST['req_leave_date_from'];
		   $req_leave_date_to =  $_POST['req_leave_date_to'];
		  
		  // $strtotime = strtotime(date('Y-m-d H:i'));
			  $strtotime = strtotime($req_leave_date_from);
			  $a_year    = date('Y',$strtotime);
			  $a_month   = date('m',$strtotime);
			  $a_day     = date('d',$strtotime);
			  $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
			  $this->db->select('month_days,month_days_in_out');
			  $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
			  // echo '<pre>';print_r($record);exit;
			  $punchin_id = 1;
			  $punchin_time = '';
			  if(!empty($record['month_days'])){
				  $month_days =  unserialize($record['month_days']);
				  $month_days_in_out =  unserialize($record['month_days_in_out']);
								  
  
				  $a_day -=1;
				  
				  if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  
					  $day = $month_days[$a_day];
					  $day_in_out = $month_days_in_out[$a_day];
					  $latest_inout = end($day_in_out);
					  if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
						$punch_in = $day['punch_in'];
						$punch_in_out = $latest_inout['punch_in'];
						$punch_out_in = $latest_inout['punch_out'];
						$punchin_id = 1;
					  }else{
						 $punch_in = $day['punch_in'];
						$punch_in_out = $latest_inout['punch_in'];
						$punch_out_in = $latest_inout['punch_out'];
						$punchin_id = 0;
					  }
				   }
			   $punchin_time = date("g:i a", strtotime($day['punch_in']));
			   $punchout_time = date("g:i a", strtotime($day['punch_out']));
			 }
			 
			 
			  // echo '<pre>';print_r($punchin_time);exit;
			  if($punchin_time !='')
			  {
				  $res=1;
			  }
			  else
			  {
				  $res=0;
			  }
			  echo $res;exit;
			  // echo '<pre>';print_r($res);exit;
		}

	public function bulk_leave_approve()
	{
		$post_data = $this->input->post();
		
		if(is_array($post_data) && !empty($post_data))
		{

			// $tmp_data = $post_data['team_leave_approve'];
			$keys = array_keys($post_data['team_leave_approve']);

			foreach($keys as $value)
			{
				$det = array();
				$det['reason'] = 'Approved';
				$det['status'] = 1; 
				
				$this->db->where('id', $value);
				$this->db->where('status', '0');
				$query = $this->db->get('dgt_user_leaves');
				
				if($query->num_rows() > 0)
				{
					$this->db->where("id", $value);
					$this->db->update('dgt_user_leaves', $det);
					
					$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$value)->result_array();
					$acc_det   = $this->db->query("SELECT * FROM dgt_account_details where user_id = ".$leave_det[0]['user_id'])->result_array();
					$user_det  = $this->db->query("SELECT * FROM dgt_users where id = ".$leave_det[0]['user_id'])->result_array();
					
					if(!empty($acc_det) && !empty($user_det))
					{
						$recipient       = array();
						if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
						$subject         = " Leave Request Approved ";
						$message         = '<div style="height: 7px; background-color: #535353;"></div>
												<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
													<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Approved</div>
													<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
														<p> Hi '.$acc_det[0]['fullname'].',</p>
														<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by Admin </p> 
														<br>  
														<a style="text-decoration:none;" href="'.base_url().'login"> 
															<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
														</a>
														<br>
														</big><br><br>Regards<br>The '.config_item('company_name').' Team
													</div>
											</div>';  
						if(!empty($recipient) && count($recipient) > 0)
						{		 
							$recipient = "ankitvel@mailinator.com";
							$params = array(
								'recipient' => $recipient,
								'subject'   => $subject,
								'message'   => $message
							);   
							Modules::run('fomailer/send_email',$params); 	
						}
					}
				}
			}
		}
		$this->session->set_flashdata('tokbox_success', 'All Leave has been approved.');
		redirect('leaves');
	}
}
