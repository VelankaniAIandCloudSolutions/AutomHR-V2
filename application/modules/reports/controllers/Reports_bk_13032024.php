<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('reports').' - '.config_item('company_name'));

		$this->load->model(array('Report','App','Invoice','Client','Expense','Project','User'));

		App::module_access('menu_reports');
		if(isset($_GET['setyear'])){ $this->session->set_userdata('chart_year', $_GET['setyear']); }
	}

	function index()
	{
		$data = array(
			'page' => lang('reports'),
		);
		$this->template
		->set_layout('users')
		->build('dashboard',isset($data) ? $data : NULL);
	}


	function view($report_view = NULL){
			switch ($report_view) {
				case 'invoicesreport':
					$this->_invoicesreport();
					break;
				case 'invoicesbyclient':
					$this->_invoicesbyclient();
					break;
				case 'paymentsreport':
					$this->_paymentsreport();
					break;
				case 'expensesreport':
					$this->_expensesreport();
					break;
				case 'expensesbyclient':
					$this->_expensesbyclient();
					break;
				case 'ticketsreport':
					$this->_ticketsreport();
					break;
				case 'projectreport':
					$this->_projectreport();
					break;
				case 'taskreport':
				$this->_taskreport();
				break;
				case 'user_report':
				$this->_user_report();
				break;
				case 'employee_report':
				$this->_employee_report();
				break;
				case 'payslip_report':
				$this->_payslip_report();
				break;
				case 'attendance_report':
				$this->_attendance_report();
				break;
				case 'leave_report':
				$this->_leave_report();
				break;
				case 'daily_report':
					$this->_daily_report();
					break;
				default:
					# code...
					break;
			}
	}

	function _daily_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}

		$branches = array_column($data['all_branches'], 'branch_id');
		if($this->input->post()){
			$data['user_id'] = $this->input->post('user_id');
			$data['branch_id'] = $this->input->post('branch_id');
			$data['role_id'] = $this->input->post('role_id');

			$this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT);

			if(!empty($data['branch_id'])){
				$this->db->where('branch_id',$data['branch_id']); 
			}
			if(!empty($data['role_id'])){
				$this->db->where('role_id',$data['role_id']); 
			}

			 $data['users'] = $this->db->get()->result_array();
		}else{
			$data['users'] = $this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT)->where_in('AD.branch_id',$branches)->get()->result_array();
			$data['role_id'] = NULL;
		}

		if($_POST['pdf']){
			 $html = $this->load->view('pdf/dailyreport',$data,true);
		  
		   $file_name = lang('daily_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('daily_report'),
			"author"    => $this->company_name,
			"creator"   => $this->company_name,
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
		}

		
		 	
		  

		$this->template
			->set_layout('users')
			->build('report/daily_report',isset($data) ? $data : NULL);
	}
	function daily_report_excel(){
		if(!empty($_POST)){

		$html = $this->load->view('excel/daily_report_excel',$data,true);
		  
		   	
			echo $html; exit;
		}else{
			echo 'error'; exit();
		}
	}
	
	function _leave_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		$data['user_id'] = $data['branch_id'] = $data['leave_month'] = $data['leave_year'] = '';
		
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		$branches = array_column($data['all_branches'], 'branch_id');
		if($this->input->post()){
			$data['user_id'] = $this->input->post('user_id');
			$data['branch_id'] = $this->input->post('branch_id');

			$data['leave_month'] = $this->input->post('leave_month');

			$data['leave_year'] = $this->input->post('leave_year');
			$this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT);

			if(!empty($data['branch_id'])){
				$this->db->where('branch_id',$data['branch_id']); 
			}

			 $data['users'] = $this->db->get()->result_array();
		}else{
			$data['users'] = $this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT)->where_in('AD.branch_id',$branches)->get()->result_array();
			$data['role_id'] = NULL;
		}
		$leave_types = $this->db->select('*')->from('common_leave_types')->where('branch_id',$data['branch_id'])->order_by('leave_type','asc')->get()->result_array();
		$data['leave_types'] = array();
		if(!empty($leave_types)){
			$data['leave_types'] = $leave_types;
			
			$this->db->select('sum(ul.leave_days) as tot_leave,cl.leave_type,cl.leave_id,cl.leave_type_id,cl.leave_day_month')->from('user_leaves ul')->join('common_leave_types cl','cl.leave_type_id=ul.leave_type',LEFT)->where('ul.status',1)->where('cl.branch_id',$data['branch_id']) ;
			if(!empty($data['leave_month'])){
				$where_moth = "(month(ul.leave_from) <= '".$data['leave_month']."' OR month(ul.leave_to) <= '".$data['leave_month']."')";
				$this->db->where($where_moth) ;
			}
			if(!empty($data['leave_year'])){
				$where_year = "(year(ul.leave_from) <= '".$data['leave_year']."' OR year(ul.leave_to) <= '".$data['leave_year']."')";
				$this->db->where($where_year) ;
			}
			$this->db->group_by('ul.leave_type');
			$user_leaves = $this->db->get()->result_array();
			$data['leaves'] = array();
			if(!empty($user_leaves)){

				foreach($user_leaves as $user_leave1){
					if($user_leave1['leave_type_id'] == 9){
					}
					$data['leaves'][$user_leave1['leave_id']] = $user_leave1['tot_leave'];
				}
			}
		}
		$this->template
			->set_layout('users')
			->build('report/leavereport',isset($data) ? $data : NULL);
	}
	function ajax_leave_report(){
		$params = $_GET;
		$return_data = array();


		$this->db->select('*')->from('users U') ->join('account_details AD','U.id = AD.user_id',LEFT);

		$where_like = $where = array();
		if(!empty($_GET['search']['value'])){
			$where_like['AD.fullname'] = $_GET['search']['value'];
			$where_like['U.email'] = $_GET['search']['value'];
			$where_like['AD.employee_id'] = $_GET['search']['value'];
		}
		
		if(!empty($_GET['user_id'])){
			$where['U.id'] = $_GET['user_id'];
		}
		if(!empty($_GET['branch_id'])){
			$branch_id = $_GET['branch_id'];
			$where['AD.branch_id'] = $_GET['branch_id'];
		}
		if(!empty($where_like)){
			$or_where = " ( AD.fullname like '%".$_GET['search']['value']."%'  or  AD.employee_id like '%".$_GET['search']['value']."%'  or  U.email like '%".$_GET['search']['value']."%' )";
			$this->db->where($or_where);
			//$this->db->or_like($where_like);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		


		$data_all = $this->db->get()->result_array();


		$this->db->select('*')->from('users U') ->join('account_details AD','U.id = AD.user_id',LEFT);

		if(!empty($where_like)){
			$or_where = " ( AD.fullname like '%".$_GET['search']['value']."%'  or  AD.emp_code like '%".$_GET['search']['value']."%'  or  U.email like '%".$_GET['search']['value']."%' )";
			$this->db->where($or_where);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		if($params['length']>0){
			$users = $this->db->limit($params['length'],$params['start'])->get()->result_array();
		}
		else{
			$users = $this->db->get()->result_array();
		}

		// echo '<pre>';print_r($users);exit;
		$return_data['recordsTotal'] = count($data_all);
		$return_data['recordsFiltered'] = count($data_all);
		$return_data['draw'] = isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;

		$req_cur_date = date('Y').'-01-01';

		if(!empty($users)){
			$sl_no = $params['start'];
			$i  = 0;
			$leave_types = $this->db->select('*')->from('common_leave_types')->where('branch_id',$branch_id)->order_by('leave_type','asc')->get()->result_array();
			foreach($users as $user1){
				$dept_det = $this->db->select('*')->from('departments')->where('deptid',$user1['department_id'])->get()->row_array();
				$desig_det = $this->db->select('*')->from('designation')->where('id',$user1['designation_id'])->get()->row_array();

				$j = $i+1;
				$return_data['data'][$i]['s_no'] = $sl_no +$j;
				$return_data['data'][$i]['employee'] = $user1['fullname'];
				$return_data['data'][$i]['email'] = $user1['email'];
				$return_data['data'][$i]['department'] = $dept_det['deptname'];
				$return_data['data'][$i]['designation'] = $desig_det['designation'];
				
				$return_data['data'][$i]['employee_code'] = $user1['emp_code'];
				$return_data['data'][$i]['absent'] = $total_booked = $total_available = $total = 0;
				if(!empty($leave_types)){
					foreach($leave_types as $leave_type1){
						$this->db->select('sum(leave_days) as tot_leave')->from('user_leaves')->where('user_id',$user1['user_id'])->where('leave_type',$leave_type1['leave_type_id'])->where('status',1) ;
						if(!empty($_GET['leave_month'])){
							$where_moth = "(month(dgt_user_leaves.leave_from) = '".$_GET['leave_month']."' OR month(dgt_user_leaves.leave_to) = '".$_GET['leave_month']."')";
							$this->db->where($where_moth) ;
						}
						if(!empty($_GET['leave_year'])){
							$where_year = "(year(dgt_user_leaves.leave_from) = '".$_GET['leave_year']."' OR year(dgt_user_leaves.leave_to) = '".$_GET['leave_year']."')";
							$this->db->where($where_year);
						}
						$ch_user_leves = $this->db->get()->result_array();
						
						$return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] =  0;
						if(!empty($ch_user_leves[0]['tot_leave'])){
							$return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] = $ch_user_leves[0]['tot_leave'];
						}else{
							$return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] = 0;
						}
						//$total_leave = $leave_type1['leave_days'] + $return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
						$total_leave = $leave_type1['leave_days'];
						$available = $leave_type1['leave_days'] - $return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
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
							$extact_mon = (int)date(m);
							//$total_leave = $leave_type1['leave_days'] - $minus_leave;
							$total_leave = ($extact_mon * $leave_type1['leave_day_month']) - $minus_leave;
							if($total_leave<=0){
								$total_leave = 0;
							}
							
							$num_month = (!empty($_GET['leave_month']))?(int) $_GET['leave_month']:(int) date('m');
							
							$tot_num_day = ($num_month * $leave_type1['leave_day_month']) - ($return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked']);
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
							if(!empty($return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'])){
								$available = $total_leave - $return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
							}
						}
						
						//$return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['available'] = $available;
						$return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['available'] = $total_leave- $return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'];
						$return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['total'] = $total_leave;
						$total_booked =  $return_data['data'][$i][str_replace(' ', '_', $leave_type1['leave_type'])]['booked'] +$total_booked;
						
						$total_available = $total_available + $available;
						$total = $total_leave +$total;
					}
				}
				$return_data['data'][$i]['total']['booked'] = $total_booked;
				$return_data['data'][$i]['total']['available'] = $total - $total_booked;
				$return_data['data'][$i]['total']['total'] = $total;
				$i++;
			}
		}			
		echo json_encode($return_data);exit;

	}
	function _invoicesreport(){
		// echo 'fds';exit;
		$data = array('page' => lang('reports'),'form' => TRUE);
		
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['report_by'] = $this->input->post('report_by');
			$data['branch_id'] = $this->input->post('branch_id');
			$data['invoices'] = Invoice::by_range_list($start_date,$end_date,$data['branch_id'],$data['report_by']);
			$data['range'] = array($start_date,$end_date);
		}else{
			$data['invoices'] = Invoice::by_range_list(date('Y-m').'-01',date('Y-m-d'),$data['branch_id']);
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/invoicesreport',isset($data) ? $data : NULL);
	}

	function _invoicesbyclient(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			$client = $this->input->post('client');
			$data['invoices'] = Invoice::get_client_invoices($client);
			$data['client'] = $client;
			$data['branch_id'] = $this->input->post('branch_id');
		}else{
			$data['invoices'] = array();
			$data['client'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/invoicesbyclient',isset($data) ? $data : NULL);
	}

	function _paymentsreport(){
		$this->load->model('Payment');
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$branch_id = $this->input->post('branch_id');
			$data['payments'] = Payment::by_range($start_date,$end_date,$branch_id);
			$data['range'] = array($start_date,$end_date);
			$data['branch_id'] = $branch_id;
		}else{
			$data['payments'] = Payment::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/paymentsreport',isset($data) ? $data : NULL);
	}

	function _expensesreport(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['report_by'] = $this->input->post('report_by');
			$branch_id = $this->input->post('branch_id');
			$data['expenses'] = Expense::by_range($start_date,$end_date,$branch_id,$data['report_by']);
			// echo $this->db->last_query(); exit;
			$data['range'] = array($start_date,$end_date);
			$data['branch_id'] = $branch_id;
		}else{
			$data['expenses'] = Expense::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/expensesreport',isset($data) ? $data : NULL);
	}


	function _expensesbyclient(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			$client = $this->input->post('client');
			$data['report_by'] = $this->input->post('report_by');
			$branch_id = $this->input->post('branch_id');
			$data['expenses'] = Expense::expenses_by_client($client,$data['report_by'],$branch_id);
			$data['client'] = $client;
			$data['branch_id'] = $branch_id;
		}else{
			$data['expenses'] = array();
			$data['client'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/expensesbyclient',isset($data) ? $data : NULL);
	}


	function _projectreport(){
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			// $project_id = $this->input->post('project_title');
			$data['project_id'] = $this->input->post('project_title');
			$data['status'] = $this->input->post('status');
			   
			if(!empty($data['project_id']) && !empty($data['status'])){
  // echo "<pre>";print_r($data); exit;
				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id'],'status' => $data['status']));
				 
			} else if (!empty($data['project_id']) && $data['status'] === '') {
   // echo "<pre>";print_r($data); exit;
				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id']));
			} else if ($data['project_id'] ==='' && !empty($data['status'])) {
				  
				$data['projects'] = Project::by_where(array('status' => $data['status']));
			} else {
				  
				$data['projects'] = Project::all();
			}
			$data['branch_id'] = $this->input->post('branch_id');
			
			// echo "<pre>";print_r($data['projects']); exit;
			
		}else{
			$data['projects'] = array();
			$data['project_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/projectreport',isset($data) ? $data : NULL);
	}

	function _taskreport(){
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			// $project_id = $this->input->post('project_title');
			$data['task_id'] = $this->input->post('task_id');
			$data['task_progress'] = $this->input->post('task_progress');
			$data['branch_id'] = $this->input->post('branch_id');
			  
			$this->db->select('*');
			$this->db->from('tasks');
			if($data['task_id']) {
				$this->db->where('t_id',$data['task_id']);
			}
			if($data['task_progress']) {
				$this->db->where('task_progress',$data['task_progress']);
			}
			if($data['branch_id']) {
				$this->db->where('branch_id',$data['branch_id']);
			}
			$data['tasks'] = $this->db->get()->result_array();


			/*if(!empty($data['task_id']) && !empty($data['task_progress'])){
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => $data['task_progress']))->result_array(); 
  

			} else if (!empty($data['task_id']) && $data['task_progress'] === '') {
   
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id']))->result_array();
				// echo "<pre>";print_r($data['tasks']); exit;
				
			} else if (!empty($data['task_progress']) && $data['task_id'] === '0') {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => $data['task_progress']))->result_array();

			} 
			else if ($data['task_progress'] === '' && $data['task_id'] === '0'){
				  
				$data['tasks'] = $this->db->get_where('tasks')->result_array();
			}

			else if ($data['task_progress'] == '0' && $data['task_id'] === '0') {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => 0))->result_array();

			} 

			else if(!empty($data['task_id']) && $data['task_progress'] == '0') {
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => 0))->result_array(); 
  

			}*/
			
			
		}else{
			$data['tasks'] = array();
			$data['task_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/taskreport',isset($data) ? $data : NULL);
	}

	function _user_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			$data['role_id'] = $this->input->post('role_id');
			$data['branch_id'] = $this->input->post('branch_id');
			
			 $this->db->select('*');
			 $this->db->from('users U');
			 $this->db->join('account_details AD','U.id = AD.user_id',LEFT);
			 if(!empty($data['role_id'])){
			 $this->db->where('U.user_type',$data['role_id']);
			 }
			 if(!empty($data['branch_id'])){
			 $this->db->where('AD.branch_id',$data['branch_id']);
			 }
			 if($this->session->userdata('user_type_name') !='company_admin' || $this->tank_auth->get_role_id()!=1){
				$this->db->where('U.id',$this->session->userdata('user_id'));
			 }
			$data['users'] =	$this->db->get()->result_array();

		}else{
			$data['users'] = array();
			$data['role_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/user_report',isset($data) ? $data : NULL);
	}

	function _employee_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			
			
			$data['user_id'] = $this->input->post('user_id');
			$data['department_id'] = $this->input->post('department_id');
			$data['designation_id'] = $this->input->post('designation_id');
			$data['branch_id'] = $this->input->post('branch_id');
		
		
			$this->db->select('U.*,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,AD.emp_code,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id');
			$this->db->from('users U');
			$this->db->join('account_details AD','AD.user_id=U.id','LEFT');
			$this->db->join('designation D','D.id=U.designation_id','LEFT');
			$this->db->join('departments DE','DE.deptid=D.department_id','LEFT');

			if(!empty($data['branch_id'])){
				$this->db->where('AD.branch_id', $data['branch_id']);
			}

			if(!empty($data['department_id'])){
				$this->db->where('U.department_id', $data['department_id']);
			}
					
			if(!empty($data['user_id'])){
				$this->db->where('U.id', $data['user_id']);
			}
			if(!empty($data['designation_id'])){
				$this->db->where('U.designation_id', $data['designation_id']);
			}
			$this->db->order_by('U.id', 'ASC');
	 	 	$data['employees'] = $this->db->get()->result_array();
				 // echo "<pre>";print_r($this->db->last_query()); exit;		
				
		}else{
			$data['employees'] = array();
			$data['user_id'] = NULL;

		}

		$this->template
			->set_layout('users')
			->build('report/employee_report',isset($data) ? $data : NULL);
	}
	function employee_report_excel(){
		
		if($this->input->post()){
				
				$data['user_id'] = $this->input->post('user_id');
				$data['department_id'] = $this->input->post('department_id');
				$data['designation_id'] = $this->input->post('designation_id');
			
			
			$this->db->select('U.*,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id,AD.dob,AD.gender,AD.address,AD.city,AD.state,R.role,PD.personal_details,PD.personal_info,PD.emergency_info,BS.salary,RES.resignationdate,TER.terminationdate');
			$this->db->from('users U');
			$this->db->join('account_details AD','AD.user_id=U.id','LEFT');
			//self::$db->join('companies C','C.co_id=AD.company','LEFT');
			$this->db->join('designation D','D.id=U.designation_id','LEFT');
			$this->db->join('departments DE','DE.deptid=D.department_id','LEFT');
			$this->db->join('roles R','R.r_id=U.user_type','LEFT');
			$this->db->join('users_personal_details PD','PD.user_id=U.id','LEFT');
			$this->db->join('bank_statutory BS','BS.user_id=U.id','LEFT');
			$this->db->join('resignation RES','RES.employee=U.id','LEFT');
			$this->db->join('termination TER','TER.employee=U.id','LEFT');
			if(!empty($data['department_id'])){
				$this->db->where('U.department_id', $data['department_id']);
			}
					
			if(!empty($data['user_id'])){
				$this->db->where('U.id', $data['user_id']);
			}
			if(!empty($data['designation_id'])){
				$this->db->where('U.designation_id', $data['designation_id']);
			}
			$this->db->where('U.role_id', 3);
			$this->db->order_by('U.id', 'ASC');
			  $data['employees'] = $this->db->get()->result_array();
			  // echo "<pre>"; print_r($data['employees']); exit;     
			$html = $this->load->view('excel/employee_report_excel',$data,true);
				  
					echo $html; exit;
				}else{
					echo 'error'; exit();
				}
			
		}

	function _payslip_report(){
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();

				}else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		if($this->input->post()){
			
			$data['user_id'] = $this->input->post('user_id');
			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');
			$data['branch_id'] = $this->input->post('branch_id');

			// print_r($data);exit;
			$this->db->select('p.*');
			$this->db->from('payslip p');
			$this->db->join('account_details AD','p.user_id = AD.user_id',LEFT);
			if(!empty($data['user_id'])){
			$this->db->where('p.user_id',$data['user_id']);
			}
			if(!empty($data['month'])){
			$this->db->where('p.p_month',$data['month']);
			}
			if(!empty($data['year'])){
			$this->db->where('p.p_year',$data['year']);
			}
			if(!empty($data['branch_id'])){
			$this->db->where('AD.branch_id',$data['branch_id']);
			}
		   $data['payslip'] =	$this->db->get()->result_array();

			/*if($data['user_id'] == '' && $data['month'] =='0' && $data['year'] == '0')
			{
				$data['payslip'] = $this->db->get('payslip')->result_array(); 
  

			} 
			elseif($data['user_id'] == '' && $data['month'] =='0' && $data['year'] == '0')
			{
				$account_details = $this->db->get_where('account_details',array('company'=>$data['branch_id']))->result_array();
				foreach ($account_details as $key => $g) {
					$user = $g['user_id'];
												
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$user))->result_array();
  				}
  			}

  		
			elseif($data['user_id'] == ''  && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month']))->result_array();
  				 				

			}
			elseif($data['user_id'] =='' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_year'=>$data['year']))->result_array();
  				 				

			}
			elseif($data['user_id'] != '' && $data['month'] =='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id']))->result_array();
			}
			elseif($data['user_id'] != '' && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month']))->result_array();
			}
			elseif($data['user_id'] != '' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] != '' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] == '' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}

			  $data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			// }
			*/
			
		}else{
			$data['payslip'] = array();
			// $data['company_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/payslip_report',isset($data) ? $data : NULL);
	}

	function _attendance_report(){
		// echo 'hi';exit;
		// echo '<pre>';print_r($this->session->userdata('branch_id'));exit;
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);
		$data['start_date'] = $data['end_date'] = '';
		$data['user_id'] = $data['branch_id'] = $data['attendance_month'] = $data['attendance_year'] =  $data['num_days'] = '';

		if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){

			$data['all_branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();

		}
		else{
			if($this->session->userdata('user_type_name') =='company_admin'){
				$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
			}
			else{
				if($this->tank_auth->get_role_id()==1){
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
				}
				else{
					$data['all_branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->where('branch_id',$this->session->userdata('branch_id'))->get()->result_array();
				}
			}
		}
		$branches = array_column($data['all_branches'], 'branch_id');

		if($this->input->post()){
			$data['user_id'] = $this->input->post('user_id');
			$data['branch_id'] = $this->input->post('branch_id');

			$data['attendance_month'] = $this->input->post('attendance_month');
			$data['attendance_year'] = $this->input->post('attendance_year');

			$gn_date = '15-'.$data['attendance_month'].'-'.$data['attendance_year'];
			$data['cur_month'] = date('M',strtotime($gn_date));
			$req_month = date('m',strtotime($gn_date));

			$data['start_date'] = '01-'.$req_month.'-'.$data['attendance_year'];
			$data['end_date'] = date("t-m-Y", strtotime($gn_date));

			$data['num_days']=cal_days_in_month(CAL_GREGORIAN,$data['attendance_month'],$data['attendance_year']);
			$this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT);

			if(!empty($data['branch_id'])){
				$this->db->where('branch_id',$data['branch_id']); 
			}
			
			$data['users'] = $this->db->get()->result_array();
		}else{

			$data['users'] = $this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT)->where_in('AD.branch_id',$branches)->get()->result_array();
		}
		if(!empty($data['branch_id'])){
			$data['leave_types'] = $this->db->select('*')->from('common_leave_types')->where('branch_id',$data['branch_id']) ->get()->result_array();
		}
		else{
			$data['leave_types'] = $this->db->select('*')->from('common_leave_types') ->get()->result_array();
		}

		

		$this->template
			->set_layout('users')
			->build('report/attendance_report',isset($data) ? $data : NULL);
	}
	function get_attendance_day($month,$year,$time,$d,$user_id=''){
		$list=array();
		$return_data = '';
		$gn_date = '15-'.$month.'-'.$year;
		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$time=mktime(12, 0, 0, $month, $d, $year);
		$colMonth = date('M',strtotime($gn_date));
		
		//weekend calculation
		$users_det = $this->db->select('*')->from('account_details')->where('user_id',$user_id) ->get()->row_array();

		$branch_details =$this->db->select('*')->from('dgt_branches')->where('branch_id',$users_det['branch_id']) ->get()->row_array();
		$weekend_workdays=json_decode($branch_details['weekend_workdays']);
				
		$weekend_cnt[1] = date('Y-m-d', strtotime('first saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[2] = date('Y-m-d', strtotime('second saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[3] = date('Y-m-d', strtotime('third saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[4] = date('Y-m-d', strtotime('fourth saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[5] = date('Y-m-d', strtotime('fifth saturday of '.$colMonth.' '.$year.''));

		$weekend_cnt[6] = date('Y-m-d', strtotime('first Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[7] = date('Y-m-d', strtotime('second Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[8] = date('Y-m-d', strtotime('third Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[9] = date('Y-m-d', strtotime('fourth Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[10] = date('Y-m-d', strtotime('fifth Sunday of '.$colMonth.' '.$year.''));
		
		$my_val=array();
		foreach($weekend_cnt as $wckey=>$wc)
		{
			foreach($weekend_workdays as $ww)
			{
				if($ww==$wckey)
				{
					// if(date('Y-m-d', $time) == $wc)
					// {
						// $my_val[]=$wc;
					// }
					$my_val[]=$wc;
				}
			}
		}
		
		
		//
		// exit;
		if(date('D', $time)=='Sat' || date('D', $time)=='Sun'){
			
			// $something = array('bla', 'omg');
			if (in_array(''.date('Y-m-d', $time).'', $my_val)) {
				// echo "|2| The 'omg' value found in the index array ||";
				$return_data = 'A';
			}
			else
			{
				$return_data = 'W';
			}
			
		}
		else{
			$chdate=date('Y-m-d', $time);
			$this->db->select('id,title');
			$this->db->from('holidays');
			$this->db->where('status',0);
			$this->db->where('holiday_date',$chdate);
			$holiday  = $this->db->get()->result_array();
			
			if(!empty($holiday)){
				$return_data = 'H';
			}
			else{
				$this->db->select('u.id,u.leave_from,u.leave_to,c.leave_type,u.leave_day_type');
				$this->db->from('user_leaves u');
				$this->db->join('common_leave_types c','c.leave_type_id = u.leave_type',LEFT);
				$this->db->where('user_id',$user_id);
				$this->db->where('u.status',1);
				$this->db->where('leave_from<=',$chdate);
				$this->db->where('leave_to>=', $chdate); 
				$leaves  = $this->db->get()->result_array();
				
				$curDate = date('Y-m-d');
				$d1 = $d;
				$month1= $month;
				if($d1<10){
					$d1 ='0'.$d1;
				}
				if($month1<10){
					$month1 ='0'.$month1;
				}
				$aDate = $year.'-'.$month1.'-'.$d1;
				
				if(!empty($leaves)){
					if(strpos($leaves[0]['leave_type'], "Leave") !== false || strpos($leaves[0]['leave_type'], "leave") !== false){
						$return_data = $leaves[0]['leave_type'];
					}
					else{
						$return_data = $leaves[0]['leave_type'];
					}
					preg_match_all('/\b\w/', $return_data, $matches);
					$return_data = implode('', $matches[0]);
					
					if($leaves[0]['leave_type'] =='Privilege Leave'){

					  $return_data = 'PL1';
					}
					if($return_data =='PLV'){

						$return_data = 'P-L';
					  }
					  if($leaves[0]['leave_day_type']!=1){
						$where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year);
						$this->db->select('month_days,month_days_in_out');
						$this->db->from('attendance_details');
						$this->db->where($where);
						$results  = $this->db->get()->result_array();
						if(!empty($results)){
							foreach ($results as $rows) {
								$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
								//for($d=1; $d<=$number; $d++)
								//{
									$a_day = date('d',strtotime($chdate));
									$a_day = $a_day+1;
									if(!empty($rows['month_days'])){
										$month_days =  unserialize($rows['month_days']);
										
										$month_days_in_out =  unserialize($rows['month_days_in_out']);
										
										$day = $month_days[$a_day-1];
										$day_in_out = $month_days_in_out[$a_day-1];
										$latest_inout = end($day_in_out);
										$actProductionHour = 0;
										foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
										{
											
											if((!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']) )||( !empty($month_days_in_out[1]['punch_in']) && !empty($month_days_in_out[1]['punch_out'])) )
											{	
												
												$production_hour = 0;
												foreach ($month_days_in_out[$a_day-1] as $punch_detail1) {
													
													$production_hour = $production_hour +time_difference(date('H:i',strtotime($punch_detail1['punch_in'])),date('H:i',strtotime($punch_detail1['punch_out'])));;

												}
												$actProductionHour =  intdiv($production_hour, 60);
											}
										}
										if($actProductionHour>=4){
											$return_data .= '/P';
										}
									}
								//}
							}
						}
					  }
				}
				else{
					if(strtotime($aDate)<strtotime($curDate)){
						$return_data = 'A';
					}
					else{
						$return_data = '-';
					}
				}
			}
		}
		return $return_data;
		
	}
	function check_holiday_orweekend($month,$year,$d,$user_id=''){
		$time=mktime(12, 0, 0, $month, $d, $year);
		//weekend calculation
		$users_det = $this->db->select('*')->from('account_details')->where('user_id',$user_id) ->get()->row_array();

		$branch_details =$this->db->select('*')->from('dgt_branches')->where('branch_id',$users_det['branch_id']) ->get()->row_array();
		$weekend_workdays=json_decode($branch_details['weekend_workdays']);
				// $colMonth = date('M',strtotime($month));
				$dateObj   = DateTime::createFromFormat('!m', $month);
$colMonth = $dateObj->format('F'); // March
		$weekend_cnt[1] = date('Y-m-d', strtotime('first saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[2] = date('Y-m-d', strtotime('second saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[3] = date('Y-m-d', strtotime('third saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[4] = date('Y-m-d', strtotime('fourth saturday of '.$colMonth.' '.$year.''));
		$weekend_cnt[5] = date('Y-m-d', strtotime('fifth saturday of '.$colMonth.' '.$year.''));

		$weekend_cnt[6] = date('Y-m-d', strtotime('first Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[7] = date('Y-m-d', strtotime('second Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[8] = date('Y-m-d', strtotime('third Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[9] = date('Y-m-d', strtotime('fourth Sunday of '.$colMonth.' '.$year.''));
		$weekend_cnt[10] = date('Y-m-d', strtotime('fifth Sunday of '.$colMonth.' '.$year.''));
		
		$my_val=array();
		foreach($weekend_cnt as $wckey=>$wc)
		{
			foreach($weekend_workdays as $ww)
			{
				if($ww==$wckey)
				{
					$my_val[]=$wc;
				}
			}
		}
		
		
		//
		// echo '<pre>';print_r($my_val);
		// exit;
		
		if(date('D', $time)=='Sat' || date('D', $time)=='Sun'){
			if (in_array(''.date('Y-m-d', $time).'', $my_val)) {
				// echo "|2| The 'omg' value found in the index array ||";exit;
				$return_data = '';
			}
			else
			{
				$return_data = 'W';
			}
			// $return_data = 'W';
		}
		else{
			$chdate=date('Y-m-d', $time);
			$this->db->select('id,title');
			$this->db->from('holidays');
			$this->db->where('status',0);
			$this->db->where('holiday_date',$chdate);
			$holiday  = $this->db->get()->result_array();
			
			if(!empty($holiday)){
				$return_data = 'H';
			}
			else{
				$return_data = '';
			}
		}
		return $return_data;
	}
	function ajax_attendance_report(){

		// print_r($_POST);exit();
		$params = $_POST;
		$return_data = array();

		$this->db->select('*')->from('users U');
		$this->db->join('account_details AD','U.id = AD.user_id',LEFT);
		$where_like = $where = array();
		if(!empty($_POST['search']['value'])){
			$where_like['AD.fullname'] = $_POST['search']['value'];
		}
		
		if(!empty($_POST['user_id'])){
			
			$where['U.id'] = $_POST['user_id'];
		}
		if(!empty($_POST['branch_id'])){
			$where['AD.branch_id'] = $_POST['branch_id'];
		}

		if(isset($params['emp_status']) && $params['emp_status'] == '0'){
			$where['U.status'] =  '0';
		}
		else if(isset($params['emp_status']) && $params['emp_status'] == '1'){
			$where['U.status'] =  '1';
		}
		else{

		}

		
		if(!empty($where_like)){
			$this->db->like($where_like);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
		$data_all = $this->db->get()->result_array();

		$this->db->select('*')->from('users U') ->join('account_details AD','U.id = AD.user_id',LEFT);

		if(!empty($where_like)){
			$this->db->like($where_like);
		}
		if(!empty($where)){
			$this->db->where($where);
		}
 
		if($params['length']>0){
			$users = $this->db->limit($params['length'],$params['start'])->get()->result_array();
		}
		else{
			$users = $this->db->get()->result_array();
		}

// echo '<pre>';print_r($users);exit;

		$return_data['recordsTotal'] = count($data_all);
		$return_data['recordsFiltered'] = count($data_all);
		$return_data['draw'] = isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
		if(!empty($users)){
			$sl_no = $params['start'];
			$i  = 0;
			$total_seconds=0;
			$prod_hour1=0;
			$total_production_hour=0;
			$leave_types = $this->db->select('*')->from('common_leave_types')->where('status',1) ->get()->result_array();
			foreach($users as $user1){
				
				$branch_details =$this->db->select('*')->from('dgt_branches')->where('branch_id',$user1['branch_id']) ->get()->row_array();
				$weekend_workdays=json_decode($branch_details['weekend_workdays']);
// echo '<pre>';print_r($weekend_workdays);exit;
				$dept_det = $this->db->select('*')->from('departments')->where('deptid',$user1['department_id'])->get()->row_array();
				$desig_det = $this->db->select('*')->from('designation')->where('id',$user1['designation_id'])->get()->row_array();

				$total_production_hour=0;
				$j = $i+1;
				$return_data['data'][$i]['s_no'] = $sl_no +$j;
				$return_data['data'][$i]['employee'] = $user1['fullname'];
				$return_data['data'][$i]['department'] = $dept_det['deptname'];
				$return_data['data'][$i]['designation'] = $desig_det['designation'];
				$where     = array('user_id'=>$user1['user_id'],'a_month'=>$params['a_month'],'a_year'=>$params['a_year']);
				$this->db->select('lop');
				$this->db->from('attendance_details');
				$this->db->where($where);
				$record  = $this->db->get()->row();
				// print_r($record->lop);exit();
				// echo $this->db->last_query();exit();
				$return_data['data'][$i]['lop'] = !empty($record->lop)?$record->lop:0;


				$month = $params['a_month'];
				$year = $params['a_year'];
				$no_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year); // days in month
				$return_data['data'][$i]['working_days'] = !empty($no_of_days) ? $no_of_days : 0;
				
				$worked_days = $no_of_days - $return_data['data'][$i]['lop'];
				$return_data['data'][$i]['worked_days'] = !empty($worked_days) ? $worked_days : 0;

				$return_data['data'][$i]['leave_taken'] = "0";

				
				// $user_id = 174;
				$where     = array('user_id'=>$user1['user_id'],'a_month'=>$params['a_month'],'a_year'=>$params['a_year']);
				$this->db->select('month_days,month_days_in_out');
				$this->db->from('attendance_details');
				$this->db->where($where);
				$results  = $this->db->get()->result_array();
				if(!empty($results)){
					foreach ($results as $rows) {
						$list=array();
						$month = $params['a_month'];
						$year = $params['a_year'];
						$gn_date = '15-'.$month.'-'.$year;
						$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

						$user_taken_leave = 0;

						for($d=1; $d<=$number; $d++)
						{
							$time=mktime(12, 0, 0, $month, $d, $year);
							$colMonth = date('M',strtotime($gn_date));
							if (date('m', $time)==$month) 
								$date=date('d M Y', $time);
							$a_day =date('d', $time);
							$to_dt=date('Y-m-d',$time);
							if(!empty($rows['month_days'])){
								$month_days =  unserialize($rows['month_days']);
								
								$month_days_in_out =  unserialize($rows['month_days_in_out']);
								
								$day = $month_days[$a_day-1];
								$day_in_out = $month_days_in_out[$a_day-1];
								$latest_inout = end($day_in_out);
								// $production_hour=0;
								// $break_hour=0;
								// $k = 1;
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
										$schedule_date1 = $year.'-'.$month.'-'.$d;

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
										$actscheduledhour =  intdiv($total_scheduled_minutes, 60);
										$actProductionHour =  intdiv($production_hour, 60);
										if($actProductionHour>=$actscheduledhour){

											$check_holiday = $this->check_holiday_orweekend($month,$year,$d,$user1['user_id']);
											// echo '<pre>';print_r($check_holiday);
											// exit;
											if($check_holiday==''){
												$return_data['data'][$i][$d.'_'.$colMonth] = 'P - ('.$prod_hour.')';
											}
											else{
												
												$weekend_cnt[1] = date('Y-m-d', strtotime('first saturday of '.$colMonth.' '.$year.''));
												$weekend_cnt[2] = date('Y-m-d', strtotime('second saturday of '.$colMonth.' '.$year.''));
												$weekend_cnt[3] = date('Y-m-d', strtotime('third saturday of '.$colMonth.' '.$year.''));
												$weekend_cnt[4] = date('Y-m-d', strtotime('fourth saturday of '.$colMonth.' '.$year.''));
												$weekend_cnt[5] = date('Y-m-d', strtotime('fifth saturday of '.$colMonth.' '.$year.''));

												$weekend_cnt[6] = date('Y-m-d', strtotime('first Sunday of '.$colMonth.' '.$year.''));
												$weekend_cnt[7] = date('Y-m-d', strtotime('second Sunday of '.$colMonth.' '.$year.''));
												$weekend_cnt[8] = date('Y-m-d', strtotime('third Sunday of '.$colMonth.' '.$year.''));
												$weekend_cnt[9] = date('Y-m-d', strtotime('fourth Sunday of '.$colMonth.' '.$year.''));
												$weekend_cnt[10] = date('Y-m-d', strtotime('fifth Sunday of '.$colMonth.' '.$year.''));
												
												$my_val=array();
												foreach($weekend_cnt as $wckey=>$wc)
												{
													foreach($weekend_workdays as $ww)
													{
														if($ww==$wckey)
														{
															// if(date('Y-m-d', $time) == $wc)
															// {
																// $my_val[]=$wc;
															// }
															$my_val[]=$wc;
														}
													}
												}
												
												
												//
												
												// if(date('D', $time)=='Sat' || date('D', $time)=='Sun'){
													
													// $something = array('bla', 'omg');
													// if (in_array(''.date('Y-m-d', $time).'', $my_val)) {
														// echo "|2| The 'omg' value found in the index array ||";
														// $return_data = 'A';
													// }
													// else
													// {
														// $return_data = 'W';
													// }
													
												// }
												// $return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">P - ('.$prod_hour.')</span>';
												
												
												if($check_holiday=='W')
												{
												// exit;
													$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'/P - ('.$prod_hour.')</span>';
												}
												else
												{
													
													if (in_array(''.date('Y-m-d', $time).'', $my_val)) {
														// $return_data = 'A';
														$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">P - ('.$prod_hour.')</span>';
													}
													else
													{
														$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'/P - ('.$prod_hour.')</span>';
													}
													
													// $return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'jj/P - ('.$prod_hour.')</span>';
												}
												// else
												// {
													// if (in_array(''.date('Y-m-d', $time).'', $my_val)) {
														// $return_data = 'A';
														// $return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">P - ('.$prod_hour.')</span>';
													// }
													// else
													// {
														// $return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'/P - ('.$prod_hour.')</span>';
													// }
												// }
											}
										}
										else if($actProductionHour>=($actscheduledhour/2)){
											$check_holiday = $this->check_holiday_orweekend($month,$year,$d,$user1['user_id']);
											 // echo '<pre>';print_r($check_holiday);
											// exit;	
											
											if($check_holiday==''){
												if(date('D', $time)=='Sat' || date('D', $time)=='Sun'){
													if($actProductionHour>=(($actscheduledhour/2))){
														$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">P - ('.$prod_hour.')</span>';
													}
												}
												else
												{
													$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:red">0.5P/0.5A - ('.$prod_hour.')</span>';
												}
											}
											else{
												if($hours_val <= ($actscheduledhour/2))
												{
													$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'/0.5P - ('.$prod_hour.')</span>';
												}
												else
												{
													
													$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'/P - ('.$prod_hour.')</span>';
													
													// $return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'P - ('.$prod_hour.')</span>';
												}
												
											}
											
										}
										else{
											$check_holiday = $this->check_holiday_orweekend($month,$year,$d,$user1['user_id']);
											if($check_holiday==''){
												$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:red">A</span>';
											}
											else{
												$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$check_holiday.'</span>';
											}
										}
										

									}
									else{
										$get_attendance = $this->get_attendance_day($month,$year,$time,$d,$user1['user_id']);

										$color_code = "orange";

										if($get_attendance == 'L' || $get_attendance == 'CL' || $get_attendance == 'FL' || $get_attendance == 'PL1' ) 
										{
											$user_taken_leave++;
										}
										else{
											$user_taken_leave += 0;	
										}


										if($get_attendance=='OD'){
										$color_code = "silver";
										}
										if($get_attendance=='A'){
										$color_code = "red";
										}
										if($get_attendance=='H'){
											$color_code = "green";
										}
										if($get_attendance=='W' || $get_attendance=='P' ){
											$color_code = "black";
										}
										$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:'.$color_code.'">'.$get_attendance."</span>";
										// $return_data['data'][$i][$d.'_'.$colMonth] ='<b>Total Hours : '.$total_working_hrs.'</b>';

									}
									// $k++;
								}
								
								
								
								// echo $prod_hour;exit;
								// $return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:green">'.$prod_hour.'</span>';
							}
							else{
								$return_data['data'][$i][$d.'_'.$colMonth] = 'Absent';
							}
						}
						// $return_data['data'][$i][$d.'_'.$colMonth] ='<b>Total Hours : '.$total_working_hrs.'</b>';
					}
				}
				else{
					$list=array();
						$month = $params['a_month'];
						$year = $params['a_year'];
						$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$gn_date = '15-'.$month.'-'.$year;
						$colMonth = date('M',strtotime($gn_date));
						
						$user_taken_leave = 0;

						for($d=1; $d<=$number; $d++)
						{
							$get_attendance = $this->get_attendance_day($month,$year,$time,$d,$user1['user_id']);

							$color_code = "orange";

							if($get_attendance == 'L' || $get_attendance == 'CL' || $get_attendance == 'FL' || $get_attendance == 'PL1' ) 
							{
								$user_taken_leave++;
							}
							else{
								$user_taken_leave +=0;	
							}

							if($get_attendance=='OD'){
							$color_code = "silver";
							}
							if($get_attendance=='A'){
							$color_code = "red";
							}
							if($get_attendance=='H'){
								$color_code = "green";
							}
							if($get_attendance=='W' || $get_attendance=='P' ){
								$color_code = "black";
							}
							$return_data['data'][$i][$d.'_'.$colMonth] = '<span style="color:'.$color_code.'">'.$get_attendance."</span>";
							//$return_data['data'][$i][$d.'_'.$colMonth] = $this->get_attendance_day($month,$year,$time,$d,$user1['user_id']);
						}
				}
				$tot_hrs = gmdate("H", $total_seconds);
										$tot_mins = gmdate("i", $total_seconds);
										$total_working_hrs = $tot_hrs.'h '.$tot_mins.'m';
										
										$hours_val1 = sprintf("%02d", intdiv($total_production_hour, 60));
										$mints_val1 = sprintf("%02d", ($total_production_hour % 60));
										$totprod_hour += $hours_val1.'h '. $mints_val1.'m';
										
				$return_data['data'][$i]['total_seconds'] = '<b>'.$hours_val1.'h '. $mints_val1.'m'.'</b>';
				$return_data['data'][$i]['leave_taken'] = !empty($user_taken_leave) ? $user_taken_leave : 0;
				$i++;
			}
		}	
		// echo $prod_hour;exit;
		echo json_encode($return_data);exit;
	}

	
	function attendance_report_excel(){
		if(!empty($_POST)){

		$html = $this->load->view('excel/attendance_report_excel',$data,true);
		  
		   	
			echo $html; exit;
		}else{
			echo 'error'; exit();
		}
	}

	function invoicespdf(){
		if($this->uri->segment(4)){

		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['report_by'] = $this->uri->segment(5);
		$data['branch_id'] = $this->uri->segment(6);
		$data['invoices'] = Invoice::by_range_list($start_date,$end_date,$data['branch_id'],$data['report_by']);
		$data['range'] = array($start_date,$end_date);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/invoices',$data,true);
		$file_name = lang('reports')."_".$start_date.'To'.$end_date.'.pdf';
	}else{
		$data['client'] = $this->uri->segment(3);
		$data['branch_id'] = $this->uri->segment(4);
		$data['invoices'] = Invoice::get_client_invoices_list($data['client'],$data['branch_id']);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/clientinvoices',$data,true);
		$file_name = lang('reports')."_".Client::view_by_id($data['client'])->company_name.'.pdf';
	}

		

		$pdf = array(
			"html"      => $html,
			"title"     => lang('invoices_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function paymentspdf(){
		$this->load->model('Payment');
		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$branch_id = $this->uri->segment(5);
		$data['branch_id'] = (isset($branch_id)) ? $branch_id : '';

		$data['payments'] = Payment::by_range($start_date,$end_date,$branch_id);
		$data['range'] = array($start_date,$end_date);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/payments',$data,true);
		$file_name = lang('payments')."_".$start_date.'To'.$end_date.'.pdf';
		
		$pdf = array(
			"html"      => $html,
			"title"     => lang('payments_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}


	function expensespdf(){
	
	if($this->uri->segment(5)){
		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['report_by'] = $this->uri->segment(5);
		$data['branch_id'] = $this->uri->segment(6);
		$data['expenses'] = Expense::by_range($start_date,$end_date,$branch_id,$data['report_by']);
		$data['range'] = array($start_date,$end_date);
		$html = $this->load->view('pdf/expenses',$data,true);
		$file_name = lang('expenses_report')."_".$start_date.'To'.$end_date.'.pdf';
	}else{
		$data['client'] = $this->uri->segment(3);
		$data['report_by'] = $this->uri->segment(4);
		$data['branch_id'] = $this->uri->segment(5);
		$data['expenses'] = Expense::expenses_by_client($data['client'],$data['report_by'],$data['branch_id']);
		$html = $this->load->view('pdf/clientexpenses',$data,true);
		$file_name = lang('expenses_report')."_".Client::view_by_id($data['client'])->company_name.'.pdf';
	}

		$pdf = array(
			"html"      => $html,
			"title"     => lang('expenses_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function projectpdf(){
		
	
	
		$data['project_id'] = $this->uri->segment(3);
		$data['status'] =$this->uri->segment(4);
		$data['branch_id'] =$this->uri->segment(5);

		$this->db->select('*');
		$this->db->from('projects');
		if($data['project_id']) {
			$this->db->where('project_id',$data['project_id']);
		}
		if($data['status']) {
			$this->db->where('status',$data['status']);
		}
		if($data['branch_id']) {
			$this->db->where('branch_id',$data['branch_id']);
		}
		$data['projects'] = $this->db->get()->result();
		/*if(!empty($data['project_id']) && !empty($data['status'])){

				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id'],'status' => $data['status']));
			
				 
			} else if (!empty($data['project_id']) && $data['status'] === '') {

				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id']));

				
			} else if ($data['project_id'] ==='' && !empty($data['status'])) {
				  
				$data['projects'] = Project::by_where(array('status' => $data['status']));
			
			} else {
				  
				$data['projects'] = Project::all();
				$file_name = lang('project_report').'.pdf';
			} */
			$html = $this->load->view('pdf/projects',$data,true);
		 	$file_name = lang('project_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('project_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function taskpdf(){
		
	
		
		$data['task_id'] = $this->uri->segment(3);
		$data['task_progress'] =$this->uri->segment(4);
		$data['branch_id'] =$this->uri->segment(5);

		$this->db->select('*');
			$this->db->from('tasks');
			if($data['task_id']) {
				$this->db->where('t_id',$data['task_id']);
			}
			if($data['task_progress']) {
				$this->db->where('task_progress',$data['task_progress']);
			}
			if($data['branch_id']) {
				$this->db->where('branch_id',$data['branch_id']);
			}
			$data['tasks'] = $this->db->get()->result_array();


		 /*if(!empty($data['task_id']) && !empty($data['task_progress']))
		 {
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => $data['task_progress']))->result_array(); 
  

			} else if (!empty($data['task_id']) && $data['task_progress'] == '') {
   
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id']))->result_array();
								
			} else if (!empty($data['task_progress']) && $data['task_id'] == '0') {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => $data['task_progress']))->result_array();

			} 
			else if ($data['task_progress'] == '' && $data['task_id'] == '0'){
				  
				$data['tasks'] = $this->db->get_where('tasks')->result_array();
				
			}
			else if(!empty($data['task_id']) && $data['task_progress'] == '0') {
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => 0))->result_array(); 
  

			}

			
						
			else {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => 0))->result_array();
				$file_name = lang('task_report').'.pdf';
			} */
		   
		   $html = $this->load->view('pdf/tasks',$data,true);
		   $file_name = lang('task_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('task_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}


	function userpdf(){
		
	
		
		$data['role_id'] = $this->uri->segment(3);
		$data['branch_id'] = $this->uri->segment(4);
		$this->db->select('*');
		$this->db->from('users U');
		$this->db->join('account_details AD','U.id = AD.user_id',LEFT);
		if(!empty($data['role_id'])){
		$this->db->where('U.user_type',$data['role_id']);
		}
		if(!empty($data['branch_id'])){
		$this->db->where('AD.branch_id',$data['branch_id']);
		}
		$data['users'] =	$this->db->get()->result_array();
		 /*	if(!empty($data['role_id']))
		 	{
				$data['users'] = $this->db->get_where('users',array('role_id'=>$data['role_id']))->result_array(); 
  

			} else if ($data['role_id'] == '0') {
   
				$data['users'] = $this->db->get_where('users')->result_array();
								
			} 
									
			else {
				  
				$data['users'] = $this->db->get_where('users')->result_array();
				$file_name = lang('user_report').'.pdf';
			} 
		   */
		   $html = $this->load->view('pdf/users',$data,true);
		   $file_name = lang('user_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('user_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function payslippdf(){
		
	
		    // $data['company_id'] = $this->uri->segment(3);
			$data['user_id'] = $this->uri->segment(3);
			$data['month'] = $this->uri->segment(4);
			$data['year'] = $this->uri->segment(5);
			$data['branch_id'] = $this->uri->segment(5);
			
			$this->db->select('p.*');
			$this->db->from('payslip p');
			$this->db->join('account_details AD','p.user_id = AD.user_id',LEFT);
			if(!empty($data['user_id'])){
			$this->db->where('p.user_id',$data['user_id']);
			}
			if(!empty($data['month'])){
			$this->db->where('p.p_month',$data['month']);
			}
			if(!empty($data['year'])){
			$this->db->where('p.p_year',$data['year']);
			}
			if(!empty($data['branch_id'])){
			$this->db->where('AD.branch_id',$data['branch_id']);
			}
		   $data['payslip'] =	$this->db->get()->result_array();

		/*	if($data['user_id'] == '0' && $data['month'] =='0' && $data['year'] == '0')
			{
				$data['payslip'] = $this->db->get('payslip')->result_array(); 
  

			} 
			// elseif($data['company_id'] !='0'  && $data['user_id'] == '0' && $data['month'] =='0' && $data['year'] == '0')
			// {
			// 	$account_details = $this->db->get_where('account_details',array('company'=>$data['company_id']))->result_array();
			// 	foreach ($account_details as $key => $g) {
			// 		$user = $g['user_id'];
												
  	// 			$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$user))->result_array();
  	// 			}
  	// 		}

  		
			elseif($data['user_id'] == '0' && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month']))->result_array();
  				 				

			}
			elseif($data['user_id'] =='0' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_year'=>$data['year']))->result_array();
  				 				

			}
			elseif($data['user_id'] != '0' && $data['month'] =='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id']))->result_array();
			}
			elseif($data['user_id'] != '0' && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month']))->result_array();
			}
			elseif($data['user_id'] != '0' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] != '0' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] == '0' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}*/
			
			
		   $html = $this->load->view('pdf/payslip',$data,true);
		   $file_name = lang('payslip_report').'.pdf';
	

		   $pdf = array(
			"html"      => $html,
			"title"     => lang('user_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}



	function employeepdf(){
		
	
		
		$data['user_id'] = $this->uri->segment(3);
		$data['branch_id'] = $this->uri->segment(4);
		$data['department_id'] = $this->uri->segment(5);
		$data['designation_id'] = $this->uri->segment(6);
		// print_r($data['company_id']);exit;
		$this->db->select('U.*,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,AD.emp_code,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id');
		$this->db->from('users U');
		$this->db->join('account_details AD','AD.user_id=U.id','LEFT');
		$this->db->join('designation D','D.id=U.designation_id','LEFT');
		$this->db->join('departments DE','DE.deptid=D.department_id','LEFT');

		if(!empty($data['branch_id'])){
			$this->db->where('AD.branch_id', $data['branch_id']);
		}

		if(!empty($data['department_id'])){
			$this->db->where('U.department_id', $data['department_id']);
		}
				
		if(!empty($data['user_id'])){
			$this->db->where('U.id', $data['user_id']);
		}
		if(!empty($data['designation_id'])){
			$this->db->where('U.designation_id', $data['designation_id']);
		}
		$this->db->order_by('U.id', 'ASC');
		$data['employees'] = $this->db->get()->result_array();
		
		/* 	if(!empty($data['company_id'])){
				$data['employees'] = $this->db->get_where('account_details',array('company'=>$data['company_id']))->result_array(); 
			} 
			elseif($data['company_id'] == '0')
			{
				$data['employees'] = $this->db->get('account_details')->result_array(); 
			}
									
			else {
				  
				$data['users'] = $this->db->get_where('users')->result_array();
				$file_name = lang('user_report').'.pdf';
			} 
		   */
		   $html = $this->load->view('pdf/employees',$data,true);
		   $file_name = lang('employee_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('user_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}




	function _filter_by(){

		$filter = isset($_GET['view']) ? $_GET['view'] : '';

		return $filter;
	}

	function employees(){
        if($this->input->post()){
            $company_id = $this->input->post('company');
            $this->db->select('company,fullname,user_id');
            $this->db->from('account_details');
            $this->db->where('company', $company_id);
            $records = $this->db->get()->result_array();
            echo json_encode($records);
            die();
        }
    }

    function choose_clients(){
    	$branch_id = $this->input->post('branch_id');
		if($branch_id) {
    		$all_companies = $this->db->get_where('companies',array('branch_id'=>$branch_id))->result_array();
		} else {
			$all_companies = $this->db->get_where('companies')->result_array();
		}
    	echo json_encode($all_companies); exit;
    }

    function choose_projects(){
    	$branch_id = $this->input->post('branch_id');
		if($branch_id) {
    		$all_projects = $this->db->get_where('projects',array('branch_id'=>$branch_id))->result_array();
		} else {
			$all_projects = $this->db->get_where('projects')->result_array();
		}
    	echo json_encode($all_projects); exit;
    }


    function choose_tasks(){
    	$branch_id = $this->input->post('branch_id');
		if($branch_id) {
			$all_tasks = $this->db->get_where('tasks',array('branch_id'=>$branch_id))->result_array();
		} else {
			$all_tasks = $this->db->get_where('tasks')->result_array();
		}
    	echo json_encode($all_tasks); exit;
    }



    function choose_emp(){
    	$branch_id = $this->input->post('branch_id');
    	if($branch_id != 0 ){
			$all_employees = $this->db->select('*')
					    			 ->from('users U')
					    			 ->join('account_details AD','U.id = AD.user_id',LEFT)
					    			 ->where('U.role_id',3)
					    			 ->where('AD.branch_id',$branch_id)
					    			 ->get()->result_array();
			// $all_departments = $this->db->get_where('departments',array('branch_id'=>$branch_id))->result_array();
    	}else{
			$all_employees = $this->db->select('*')
					    			 ->from('users U')
					    			 ->join('account_details AD','U.id = AD.user_id',LEFT)
					    			 ->where('U.role_id',3)
					    			 ->get()->result_array();
			// $all_departments = $this->db->get('departments')->result_array();
    	}
    	$result = array(
    		'employees' => $all_employees,
    		// 'departments' => $all_departments
    	);
    	// $all_emp = $this->db->get_where('tasks',array('branch_id'=>$branch_id))->result_array();
    	echo json_encode($result); exit;
    }



    function choose_dailyemp(){
    	$branch_id = $this->input->post('branch_id');
    	if($branch_id != 0 ){
			$all_employees = $this->db->select('*')
					    			 ->from('users U')
					    			 ->join('account_details AD','U.id = AD.user_id',LEFT)
					    			 ->where('U.role_id',3)
					    			 ->where('AD.branch_id',$branch_id)
					    			 ->get()->result_array();
    	}else{
			$all_employees = $this->db->select('*')
					    			 ->from('users U')
					    			 ->join('account_details AD','U.id = AD.user_id',LEFT)
					    			 ->where('U.role_id',3)
					    			 ->get()->result_array();
    	}
    	// $all_emp = $this->db->get_where('tasks',array('branch_id'=>$branch_id))->result_array();
    	echo json_encode($all_employees); exit;
    }

    function choose_depart(){
    	$department_id = $this->input->post('department_id');
    	$all_departments = $this->db->get_where('designation',array('department_id'=>$department_id))->result_array();
    	echo json_encode($all_departments); exit;
    }

    function choose_attendance(){
    	$branch_id = $this->input->post('branch_id');
    	$this->db->select('*')->from('users U')->join('account_details AD','U.id = AD.user_id',LEFT);
		if(!empty($branch_id)){
			$this->db->where('AD.branch_id',$branch_id);
		}
					    			// ->where('U.role_id',3)
					    			// ->where('AD.branch_id',$branch_id)
		$all_employees = 	$this->db->get()->result_array();
    	echo json_encode($all_employees); exit;
    }

    function choose_payslipemp(){
    	$branch_id = $this->input->post('branch_id');
		if($branch_id) {
			$all_employees = $this->db->select('*')
			->from('users U')
			->join('account_details AD','U.id = AD.user_id',LEFT)
			->where('U.role_id',3)
			->where('AD.branch_id',$branch_id)
			->get()->result_array();
		} else {
			$all_employees = $this->db->select('*')
			->from('users U')
			->join('account_details AD','U.id = AD.user_id',LEFT)
			->where('U.role_id',3)
			->get()->result_array();
		}
    	
    	echo json_encode($all_employees); exit;
    }

/*********************Excel Report***************************/
	function user_report_excel(){
			
		$data['page'] = lang('categories');
		$data['form'] = true;
		$data['datatables'] = true;

		if($this->input->post()){
			$data['role_id'] = $this->input->post('role_id');
			if(!empty($data['role_id'])){
				$data['users'] = $this->db->get_where('users',array('role_id'=>$data['role_id']))->result_array();   
				$data['role_name'] =  $this->db->get_where('roles',array('r_id'=>$data['role_id']))->row_array(); 
			} 
			else {
				$data['users'] = $this->db->get_where('users')->result_array();
			}
			$html = $this->load->view('excel/user_report_excel',$data,true);
			echo $html; exit;

		}else{
			$data['users'] = $this->db->get_where('users')->result_array();
			$data['role_id'] = NULL;
			$html = $this->load->view('excel/user_report_excel',$data,true);
			echo $html; exit;
		}
	}
}

/* End of file invoices.php */
