<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payroll extends MX_Controller {
    function __construct()
    {
        parent::__construct();
		$this->load->library(array('tank_auth'));	
        $this->load->model(array( 'App','Client'));     
        $salary_setting = App::salary_setting();
			  $settingsalray = array();
                        if(!empty($salary_setting)){
                            foreach ($salary_setting as  $value) {
                                $settingsalray[$value->config_key] = $value->value;
                            }
                        }
			 $this->da_percentage = (!empty($settingsalray['salary_da']))?$settingsalray['salary_da']:'1';
			 $this->hra_percentage = (!empty($settingsalray['salary_hra']))?$settingsalray['salary_hra']:'1';  
    }
    function index()
    {
		
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title('Payroll');
		$data['departments'] = Client::get_all_departments();
		 
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('payrun');
		$data['role']       = $this->tank_auth->get_role_id();
		// print_r($data);exit;
		$this->template
			 ->set_layout('users')
			 ->build('create_payroll',isset($data) ? $data : NULL);
    }
	function create()
	{
		$data['da_percentage'] = $this->da_percentage;
		$data['hra_percentage'] = $this->hra_percentage;
		$data['user_id'] = $this->uri->segment(3);
		$this->load->view('modal/pay_slip',$data);
	}
	function edit_salary()
	{
		$data['user_id'] = $this->uri->segment(3);
		$this->load->view('modal/edit_salary',$data);
	}

	public function delete($id)
    {
        $this->db->where('assets_id',$id);
        $this->db->delete('user_assets');
        $this->session->set_flashdata('tokbox_success', 'Payroll Items deleted Successfully');
        redirect('all_assets');
    }
	function save_salary()
	{
		if ($this->input->post()) {
  			$det['user_id']       = $this->input->post('salary_user_id');  
 			$det['amount']        = $this->input->post('user_salary_amount');
			$det['date_created']  = date('Y-m-d H:i:s');
			$this->db->insert('dgt_salary',$det);  
			//$this->session->set_flashdata('alert_message', 'error');
			redirect('payroll');
 		}else{
			$data['user_id'] = $this->uri->segment(3);
		    $this->load->view('modal/edit_salary',$data);
		}
		
	}
	function update_salary()
	{ 
  			$user_id         = $this->input->post('user_id');  
 			$det['amount']   = $this->input->post('amount');
 			$det['date_created'] = date('Y-m-d H:i:s');
			$id              = $this->input->post('type');
 			$this->db->update('dgt_salary',$det,array('id'=>$id));  
			echo 1;
			//$this->session->set_flashdata('alert_message', 'error');
			//redirect('payroll');
 		    exit;
	}


	function view_salary_slip(){

		if($this->input->post()){
			
			
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Payroll');
			$data['datepicker'] = TRUE;
			$data['form']       = TRUE; 
			$data['page']       = 'payroll';
			$data['role']       = $this->tank_auth->get_role_id();
			$data['pay_slip_details'] = $this->input->post();
			
			$this->template->set_layout('users')
			 ->build('salary_detail',isset($data) ? $data : NULL);
		}
	}

	function payslip($employeeid)
	{
		    $this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Payroll');
			$data['datepicker'] = TRUE;
			$data['form']       = TRUE; 
			$data['page']       = 'payroll';
			$data['role']       = $this->tank_auth->get_role_id();
			$data['user_id']  = $employeeid;
			
			
			$this->template->set_layout('users')
			 ->build('employee_salary_detail',isset($data) ? $data : NULL);
	}
	function ajax_payroll(){ 
		 $params  = $this->input->get();
		 $return_data = array();
		 $cur_where = '';
		 if(!empty($params['username'])){
			$cur_where .= " and ad.fullname like '%".$params['username']."%' ";
		}
		if(!empty($params['department_id'])){
			$cur_where .= " and d.department_id like '%".$params['department_id']."%' ";
		}
		if(!empty($params['employee_email'])){
			$cur_where .= " and u.email like '%".$params['employee_email']."%' ";
		}
		// if(!empty($params['employee_id'])){
		// 	$employee_id = str_replace('FT-00','',$params['employee_id']);
		// 	self::$db->like('U.id', $employee_id, 'BOTH');
		// }
		if(!empty($params['employee_id'])) {
			$cur_where .= " and ad.emp_code like '%".$params['employee_id']."%' ";
		}
		 
		 if(!empty($this->session->userdata('branch_id'))){
			$all_users = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3  and ad.user_id!='' and ad.branch_id=".$this->session->userdata('branch_id')." ".$cur_where." order by u.created desc")->result_array();
			$users_list = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3  and ad.user_id!='' and ad.branch_id=".$this->session->userdata('branch_id')." ".$cur_where." order by u.created desc limit ".$params['start'].",".$params['length'])->result_array();
	   }
	   else{
		   $all_users = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3 and ad.user_id!='' ".$cur_where." order by u.created desc")->result_array();
			$users_list = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3 and ad.user_id!='' ".$cur_where." order by u.created desc limit ".$params['start'].",".$params['length'])->result_array();
	   }
		$return_data['recordsTotal'] = count($all_users);
		$return_data['recordsFiltered'] = count($all_users);
		$return_data['draw'] = isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
		$return_data['data'] = [];
		if(!empty($users_list)){
			$sl_no = $params['start'];
			$i  = 0;
			foreach($users_list as $key => $usrs){
				$j = $i +1;
				$show_leave = base_url().'leaves/show_leave/'.$levs['user_id'];
				$return_data['data'][$i]['sl_no'] = $sl_no +$j;
				$return_data['data'][$i]['fullname'] = '<a class="pull-left avatar">';
				if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$usrs['user_id']),'use_gravatar') == 'Y'){
					$user_email = Applib::login_info($usrs['user_id'])->email;
					$return_data['data'][$i]['fullname'] .= '<img src="'.$this->applib->get_gravatar($user_email).'" class="img-circle">';
				}
				else{ 
					$img_file = return_img(base_url().'assets/avatar/'.Applib::profile_info($usrs['user_id'])->avatar);
					$return_data['data'][$i]['fullname'] .= '<img src="'.$img_file.'" class="img-circle">';
				}
				$return_data['data'][$i]['fullname'] .= '</a><h2><a href="javascript:void(0);">'.$usrs['fullname'].'</a></h2>';
				$salary = ''; 
				if(isset($usrs['salary_det'])&& $usrs['salary_det'] != ''){
					$exp = explode('[^]',$usrs['salary_det']);
					if($exp[0] != 0){ $salary = $exp[0]; }
				} 
				$current_sallary1 = $user_details['salary']?$user_details['salary']:'N/A';
				$user_details = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$usrs['user_id']))->row_array();
				
				$return_data['data'][$i]['current_sallary'] = '<strong>'.$current_sallary1.'</strong> ';
				$return_data['data'][$i]['designation'] = '<span class="label label-info">'.$usrs['designation'].'</span>';
				$return_data['data'][$i]['join_date'] = strftime(config_item('date_format'), strtotime($usrs['doj']));
				$cur_status = '';;
				if($usrs['status']=='1'){
					$cur_status = 'checked'; 
				}
				$return_data['data'][$i]['payroll_status'] = '<span class="status-toggle pull-right"><input type="checkbox" value="1" class="check" onchange="user_status_change('.$usrs['user_id'].')" id="payroll_user_status'.$usrs['user_id'].'" '.$cur_status.'><label class="checktoggle" for="payroll_user_status'.$usrs['user_id'].'">checkbox</label></span>';
				$return_data['data'][$i]['options'] = '<a class="btn btn-danger btn-xs"  href="'.base_url().'payroll/payslip/'.$usrs['user_id'].'" title="View Pay Slip" ><i title="View Pay Slip" class="fa fa-money"></i></a>';
				$i++;
			}
		}
		echo json_encode($return_data);exit;
	}
	
	function employee(){

			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Payroll');
			$data['datepicker'] = TRUE;
			$data['form']       = TRUE; 
			$data['page']       = 'payroll';
			$data['role']       = $this->tank_auth->get_role_id();
			$data['user_id']  = $this->session->userdata('user_id');
			$this->template->set_layout('users')
			 ->build('employee_salary_detail',isset($data) ? $data : NULL);
	}

	function salary_detail()
	{ 
  			$user_id   = $this->input->post('user_id');  
 			$year      = $this->input->post('year');
			$month     = $this->input->post('month');
			$this->db->where('user_id', $user_id);
			$this->db->where('p_year', $year);
			$this->db->where('p_month', $month);
			$details = $this->db->get('payslip')->row();

			if(!empty($details)){

				$details = json_decode($details->payslip_details,TRUE);
				print_r($details); exit;
				$bs = $details['payslip_basic'];
				$da = $details['payslip_da'];
				$hra = $details['payslip_hra'];
			echo json_encode(array('basic'=>$bs,'da'=>$da,'hra'=>$hra,'payment_details'=>$details));
			exit;	
			}else{

			$date      = $year."-".$month."-31";
  			$qry       = "select * from dgt_salary where user_id = ".$user_id."";
			$s_qry     = '';
			if($year != ''){
				$s_qry = " and date_created <= '".$date." 23:59:59' order by date_created desc";
			}
			if($year == date('Y') && $month > date('m')){
 				$s_qry = " order by date_created desc ";
			} 
			$qry .= $s_qry. " limit 1";
			  // echo $qry; exit;
 			$res      = $this->db->query($qry)->result_array();
			$bs       = $da = $hra = '';
			if(!empty($res)){
			    $bass  = $res[0]['amount'];
			    $da  = ($this->da_percentage*$res[0]['amount']/100);
				$hra = ($this->hra_percentage*$res[0]['amount']/100);
				// $bs  = ($bs-($da+$hra));
				$bs  = $da;
				$other = ($bass - ($da + $hra));

 			echo json_encode(array('basic'=>$bs,'hra'=>$hra,'other'=>$other, 'payment_details'=>array()));
 			exit;
			}
			}
 	} 


 	function payroll_items()
 	{
 		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title('Payroll');
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('payroll_items');
		$data['role']       = $this->tank_auth->get_role_id();
			
			
			$this->template->set_layout('users')
			 ->build('payroll_items',isset($data) ? $data : NULL);
 	} 


 	function overtime()
 	{
 		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title('Payroll');
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('overtime');
		$data['role']       = $this->tank_auth->get_role_id();
			
			
			$this->template->set_layout('users')
			 ->build('overtime',isset($data) ? $data : NULL);
 	}


 	function status_change()
 	{
 		
        $this->db->where('id',$this->input->post('employeeid'));
        if($this->db->update('users',array('status' =>$this->input->post('status'))))
        {
        	echo 1;
        }
        else
        {
        	echo 0;
        }
        exit;
 	}


 	function run_payroll()
 	{
		
 			$users_list = $this->db->query("SELECT u.*
									FROM `dgt_users` u  
									inner join dgt_bank_statutory bs on bs.user_id = u.id 
									where u.activated = 1  and u.status=1 order by u.created desc")->result_array();

 			

 			foreach ($users_list as $user_rows) 
 			{

 				$payroll_user_id[]=$user_rows['id'];

 			}

 			
 			for ($p=0; $p <count($payroll_user_id) ; $p++) { 
 				$pay_slip_details = array();
 				
 				

 				$payslip_year=date('Y');
                $payslip_month=date('m');

                

                $pay_slip_details['payslip_year']=$payslip_year;
                $pay_slip_details['payslip_month']=$payslip_month;


                 $all_statutorys = $this->db->get_where('bank_statutory',array('user_id'=>$payroll_user_id[$p]))->result_array(); 

                 foreach ($all_statutorys as $all_statutory) {

                 	 $addtional_ar = json_decode($all_statutory['pf_addtional'],TRUE);
					 $deduction_ar = json_decode($all_statutory['pf_deduction'],TRUE);

					 $pay_slip_details['addtion||basic_pay']=$all_statutory['salary'];

					 if(is_array($addtional_ar))
					 {
						foreach ($addtional_ar as $key => $value) 
						{
							$pay_slip_details['addtion||'.$value['addtion_name']]=$value['unit_amount'];
			            }
					 }

					 if(is_array($deduction_ar))
					 {
						foreach ($deduction_ar as $key => $values) 
						{
							$pay_slip_details['deduction||'.$values['model_name']]=$values['unit_amount'];
			            }
					 }
                 	
                //  }
				


				 $bank_details = $this->db->get_where('bank_statutory',array('user_id'=>$payroll_user_id[$p]))->row_array(); 

						$pf_details = json_decode($bank_details['bank_statutory'],TRUE);
						if($pf_details['pf_contribution'] == 'yes')
						{
							$pf_amount = $pf_details['pf_total_rate'];
						}else{
							$pf_amount = '';
						}

						if($pf_details['esi_contribution'] == 'yes')
						{
							$esi_amount = $pf_details['esi_total_rate'];
						}else{
							$esi_amount = '';
						} 

				$total_leaves =  $this->db->get_where('user_leaves',array('user_id'=>$payroll_user_id[$p],'status'=>1))->row_array();
						$lop = ($total_leaves['leave_days'] - 12);
						if($lop > 0)
						{
							$lop_leaves = $lop;
						}else{
							$lop_leaves = 0;
						}

						$total_salary = $all_statutory['salary'];
						$one_day = (round($total_salary) / 22);
						$total_lop = ($one_day * $lop_leaves);
				// 		echo $total_lop; exit;


			 $over_time = $this->db->query('SELECT * FROM  dgt_overtime WHERE user_id ='.$payroll_user_id[$p].' AND status =1 AND  Month(ot_date)='.$payslip_month.' && YEAR(ot_date)='.$payslip_year.'')->result_array(); 

			 if(!empty( $over_time))
			 {
			 	$overtime=array();
				 foreach ($over_time as $o_row)
				  {
				 	 $overtime[]=$o_row['ot_hours'];
				  }

				  $time = 0;
					//$time_arr =  array("00:30","01:15");
					 foreach ($overtime as $time_val) {
					    $time +=explode_time($time_val); // this fucntion will convert all hh:mm to seconds
					}

					 
					 $pay_slip_details['addtion||over_time']=second_to_hhmm($time)*round($one_day/8);
					  

			  }


			 // $tds=$this->db->query('SELECT `salary_percentage` FROM dgt_tds_settings WHERE `salary_from` <= '.$all_statutory['salary'].' AND `salary_to` >= '.$all_statutory['salary'].'')->row_array();
			  $tds = $this->db->select('salary_percentage')
			         //  ->from('')
			           ->where('salary_from <=', $all_statutory['salary'])
			           ->where('salary_to >=',$all_statutory['salary'])
			           ->get('tds_settings')->row_array();


				$pay_slip_details['deduction||TDS']=(($all_statutory['salary']*$tds['salary_percentage'])/100);
				$pay_slip_details['deduction||ESI']=$esi_amount;
				$pay_slip_details['deduction||PF']=$pf_amount;
				$pay_slip_details['deduction||leave']=round($total_lop);




				$pay_slip_details['payslip_user_id']=$payroll_user_id[$p];



				$array = array();
				$array['user_id'] = $pay_slip_details['payslip_user_id'];

	            $array['p_year'] = $pay_slip_details['payslip_year'];

	            $array['p_month'] = $pay_slip_details['payslip_month'];



				$this->db->where($array);

				$payslip_count = $this->db->count_all_results('payslip');

				echo '<pre>';print_r($array);
				echo $payslip_count;
				print_r($array1);
				

				if($payslip_count == 0){

					$array['payslip_details'] = json_encode($pay_slip_details);

					$this->db->insert('payslip', $array);

					  $result=($this->db->affected_rows()!= 1)? false:true;


				}else{

					$array1['payslip_details'] = json_encode($pay_slip_details);

					$this->db->where($array);

					$this->db->update('payslip', $array1);

					  $result=($this->db->affected_rows()!= 1)? false:true;

				}
 			}
				



 			}




 			 if($result==true) 
            {
                $this->session->set_flashdata('tokbox_success', 'Payslip updated successfully');
                redirect(base_url().'payroll');
            }   
            else
            {
                $this->session->set_flashdata('tokbox_error', 'No changes');
                redirect(base_url().'payroll');
            }


 			
 	}


 	function settings()
	{
		if ($this->input->post()) {
  			$salary_from=$this->input->post('salary_from');
        $salary_to=$this->input->post('salary_to');
        $salary_percentage=$this->input->post('salary_percentage');



        $this->db->empty_table('tds_settings');

        for ($i=0; $i <count($salary_from) ; $i++) 
        { 
            $data=array('salary_from' =>$salary_from[$i],
                        'salary_to' =>$salary_to[$i],
                        'salary_percentage' =>$salary_percentage[$i],
                        );
            $this->db->insert('tds_settings',$data);

        }

        $this->session->set_flashdata('tokbox_success', 'Settings Update Successfully');
        redirect('payroll/settings');
 		}else{
			$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title('Payroll');
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('payrollsettings');
		$data['role']       = $this->tank_auth->get_role_id();
		$data['salary_setting'] = App::salary_setting();
			
			
			$this->template->set_layout('users')
			 ->build('payroll_settings',isset($data) ? $data : NULL);
		}
		
	}


	
	
	
}
