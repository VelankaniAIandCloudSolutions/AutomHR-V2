<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time_sheets extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('time_sheets');
		$this->load->model(array('App','timesheet_model'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');
 
		$this->load->library('template');

		$this->template->title(lang('time_sheets'));
		$this->session->unset_userdata('search_employee');
		$this->session->unset_userdata('search_from_date');
		$this->session->unset_userdata('search_to_date');
		 

		// $this->template->title(lang('users').' - '.config_item('company_name'));
		$data['page'] = 'Timesheets';
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['datepicker'] = TRUE;
		$data['users'] = $this->timesheet_model->get_all_users_detail();
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if(!$_POST){
				if(($role_id != 1) && ($role_id != 4))
				{
					// echo $user_id; exit;
					$data['all_timesheet'] = $this->timesheet_model->get_user_timesheets($user_id);
					// echo "<pre>"; print_r($data['all_timesheet']); exit;
					$data['projects'] = $this->timesheet_model->get_all_user_projects($user_id);
				}else{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets();
					// echo "<pre>"; print_r($data['all_timesheet']); exit;

				}
		}else{
			$inputs = $this->input->post();
			// print_r($inputs); exit;
				if(($role_id != 1) && ($role_id != 4))
				{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets_search($inputs,$user_id);
					$data['projects'] = $this->timesheet_model->get_all_user_projects($user_id);
				}else{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets_search($inputs);

				}

		}

		$this->template

			 ->set_layout('users')

			 ->build('timesheets_view',isset($data) ? $data : NULL);
	}
	
	function attendance_timesheet()
	{
		$this->load->module('layouts');

		$this->load->library('template');

		$this->template->title(lang('time_sheets'));
		$this->session->unset_userdata('search_employee');
		$this->session->unset_userdata('search_from_date');
		$this->session->unset_userdata('search_to_date');
		 

		// $this->template->title(lang('users').' - '.config_item('company_name'));
		$data['page'] = 'Timesheets';
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['datepicker'] = TRUE;
		$data['users'] = $this->timesheet_model->get_all_users_detail();
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if(!$_POST){
				if(($role_id != 1) && ($role_id != 4))
				{
					// echo $user_id; exit;
					$data['all_timesheet'] = $this->timesheet_model->get_user_timesheets($user_id);
					// echo "<pre>"; print_r($data['all_timesheet']); exit;
					$data['projects'] = $this->timesheet_model->get_all_user_projects($user_id);
				}else{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets();

				}
		}else{
			$inputs = $this->input->post();
			// print_r($inputs); exit;
				if(($role_id != 1) && ($role_id != 4))
				{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets_search($inputs,$user_id);
					$data['projects'] = $this->timesheet_model->get_all_user_projects($user_id);
				}else{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets_search($inputs);

				}

		}

		$this->template

			 ->set_layout('users')

			 ->build('attendance_timesheet',isset($data) ? $data : NULL);
	}

	public function add_timesheet()
	{
		$user_id = $this->session->userdata('user_id');
		$project_name = $this->input->post('project_name');
		$timeline_desc = $this->input->post('timeline_desc');
		$timeline_hours = $this->input->post('timeline_hours');
		$timeline_date = $this->input->post('timeline_date');
		$tm_date = date("Y-m-d", strtotime($timeline_date));
		$check_status = $this->timesheet_model->check_timesheetByDate($user_id,$tm_date);
		$total_hours = array();
		for($i=0;$i<count($check_status);$i++)
		{
			$time    = explode(':', $check_status[$i]['hours']);
			$minutes = ($time[0] * 60.0 + $time[1] * 1.0);
			$total_hours[] = $minutes;
		}
		$total_minitues =  array_sum($total_hours); 
		// echo $total_minitues; exit;
		// if($total_minitues > 480)
		// {
		// 	echo "error_daily"; exit;
		// }else{

			$current_hour    = explode(':', $timeline_hours);
			$current_minutes = ($current_hour[0] * 60.0 + $current_hour[1] * 1.0);
			$balance_hours =  480 - $total_minitues ;
			// if($current_minutes <= $balance_hours)
			// {
				$result = array(
					'user_id'       => $user_id,
					'project_id' 	=> $project_name,
					'hours'      	=> $timeline_hours,
					'timeline_date' => $tm_date,
					'timeline_desc' => $timeline_desc
				);
				$this->db->insert('dgt_timesheet',$result);
				echo "success"; exit;
			// }else{
			// 	echo "hoursless"; exit;
			// }


		// }
	}

	public function edit_timesheet()
	{
		$user_id = $this->session->userdata('user_id');
		$edit_id = $this->input->post('edit_id');
		$project_name = $this->input->post('project_name');
		$timeline_desc = $this->input->post('timeline_desc');
		$timeline_hours = $this->input->post('timeline_hours');
		$timeline_date = $this->input->post('timeline_date');
		$tm_date = date("Y-m-d", strtotime($timeline_date));
		$check_status = $this->timesheet_model->check_timesheetByDate($user_id,$tm_date);
		$total_hours = array();
		for($i=0;$i<count($check_status);$i++)
		{
			$time    = explode(':', $check_status[$i]['hours']);
			$minutes = ($time[0] * 60.0 + $time[1] * 1.0);
			$total_hours[] = $minutes;
		}
		$total_minitues =  array_sum($total_hours);
		// if($total_minitues > 480)
		// {
		// 	echo "error_daily"; exit;
		// }else{

			$current_hour    = explode(':', $timeline_hours);
			$current_minutes = ($current_hour[0] * 60.0 + $current_hour[1] * 1.0);
			$balance_hours =  480 - $total_minitues;
			// echo $current_minutes.'-----'.$balance_hours; exit;
			// if($current_minutes < $balance_hours)
			// {
				$result = array(
					'user_id'       => $user_id,
					'project_id' 	=> $project_name,
					'hours'      	=> $timeline_hours,
					'timeline_date' => $tm_date,
					'timeline_desc' => $timeline_desc
				);
				$this->db->where('time_id', $edit_id);
				$this->db->update('dgt_timesheet',$result);
				echo "success"; exit;
			// }else{
			// 	echo "hoursless"; exit;
			// }


		// }
	}

	public function delete_timesheet()
	{
		$time_id = $this->input->post('time_id');
		$this->db->where('time_id',$time_id);
		$this->db->delete('dgt_timesheet');
		echo "success"; exit;
	}

	
}

/* End of file projects.php */