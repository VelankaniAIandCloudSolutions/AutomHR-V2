<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timesheet extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('projects'));
		$this->load->model(array('Project','App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function add_time()
	{
		if ($this->input->post()) {	

		$project = $this->input->post('project');
		$time_in_sec = timelog($this->input->post('spent_time'));

			if($_POST['cat'] == 'tasks'){

			$this->form_validation->set_rules('task', 'Task', 'required');

				if ($this->form_validation->run() == FALSE)
					{
						// $this->session->set_flashdata('response_status', 'error');
						// $this->session->set_flashdata('message', lang('error_in_form'));
						$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
						redirect($_SERVER['HTTP_REFERER']);

					}else{

						$data = array(
					                'task' => $this->input->post('task'),
					                'pro_id' => $project,
					                'time_in_sec' => $time_in_sec,
					                'description' => $this->input->post('description'),
					                'user' => User::get_id()
					                );		
						App::save_data('tasks_timer',$data);

					}

				}else{
					
				$data = array(
			                'project' => $project,
			                'user' => User::get_id(),
			                'time_in_sec' => $time_in_sec,
			                'description' => $this->input->post('description')
			                );
				App::save_data('project_timer',$data);
			}

			// Applib::go_to('projects/view/'.$project.'/?group=timesheets&cat='.$_POST['cat'],'success',lang('time_logged_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('time_logged_successfully'));
			redirect('projects/view/'.$project.'/?group=timesheets&cat='.$_POST['cat']);
		 // Passed validation

		}else{
			$data['project'] = $this->uri->segment(4);
			$data['cat'] = isset($_GET['cat']) ? $_GET['cat'] : 'projects';
			$data['action'] = 'add_time';
			$this->load->view('modal/timelog_action', isset($data) ? $data : NULL);
		}
	}
	function edit()
	{
		if ($this->input->post()) {	
		$time_in_sec = timelog($this->input->post('spent_time'));

		$project = $this->input->post('project',TRUE);

		if($_POST['cat'] == 'tasks'){
			$data = array(
			                'task' => $this->input->post('task'),
			                'pro_id' => $project,
			                'time_in_sec' => $time_in_sec,
			                'description' => $this->input->post('description'),
			                'user' => User::get_id()
			                );	

			App::update('tasks_timer',array('timer_id'=>$_POST['timer_id']),$data);

				}else{

			$data = array(
			                'project' => $project,
			                'time_in_sec' => $time_in_sec,
			                'user' => User::get_id(),
			                'description' => $this->input->post('description')
			                );
			App::update('project_timer',array('timer_id'=>$_POST['timer_id']),$data);

			}

			// Applib::go_to('projects/view/'.$project.'/?group=timesheets&cat='.$_POST['cat'],'success',lang('time_logged_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('time_logged_successfully'));
			redirect('projects/view/'.$project.'/?group=timesheets&cat='.$_POST['cat']);

		}else{
			$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
			$timer_id = isset($_GET['id']) ? $_GET['id'] : '';
			$data['project'] = $this->uri->segment(4);
			$data['timer_id'] = $timer_id;
			$data['cat'] = $cat;
			if($cat == 'tasks'){ 
				$data['i'] = Project::view_time_entry('task',$timer_id); 
			}else{ 
				$data['i'] = Project::view_time_entry('project',$timer_id);
			}
			$data['action'] = 'edit_time';
			$this->load->view('modal/timelog_action', isset($data) ? $data : NULL);
		}
	}

	function billable(){
		if (isset($_GET['id'])) {
			$tbl = '';
			$id = $_GET['id'];

			if(!isset($_GET['cat'])){
				redirect($_SERVER['HTTP_REFERER']);
			}
			$tbl = ($_GET['cat'] == 'tasks') ? 'tasks_timer' : 'project_timer';
			
			$billable = $this->db->select('timer_id,billable')->where('timer_id',$id)->get($tbl)
								 ->row()->billable;
			if ($billable == '1') {
				$args = array('billable' => 0);
				App::update($tbl,array('timer_id' => $id),$args);
				// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('operation_successful'));
				$this->session->set_flashdata('tokbox_success', lang('operation_successful'));
			redirect($_SERVER['HTTP_REFERER']);
			}else{
				$args = array('billable' => 1);
				App::update($tbl,array('timer_id' => $id),$args);
				// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('operation_successful'));
				$this->session->set_flashdata('tokbox_success', lang('operation_successful'));
				redirect($_SERVER['HTTP_REFERER']);
			}

		}else{
			// Applib::go_to($_SERVER['HTTP_REFERER'],'error',lang('operation_failed'));
			$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	function description(){
		if(!isset($_GET['cat'])){
				redirect($_SERVER['HTTP_REFERER']);
			}

		$tbl = ($_GET['cat'] == 'tasks') ? 'task' : 'project'; 

		$timer_id = $this->uri->segment(4);
		$data['description'] = Project::view_time_entry($tbl,$timer_id)->description;
		$data['action'] = 'timer_description';
		$this->load->view('modal/timelog_action',$data);
	}

	function delete()
	{
		if ($this->input->post()) {
		$project = $this->input->post('project',TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('project', 'Project ID', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			// Applib::go_to('projects/view/'.$_POST['project'].'/?group=timesheets&cat='.$_POST['cat'],'error',lang('delete_failed'));
			$this->session->set_flashdata('tokbox_error', lang('delete_failed'));
			redirect('projects/view/'.$_POST['project'].'/?group=timesheets&cat='.$_POST['cat']);
		}else{	
			if ($this->input->post('cat') == 'tasks') {
				App::delete('tasks_timer',array('timer_id' => $this->input->post('timer_id'))); 
				}else{
				App::delete('project_timer', array('timer_id' => $this->input->post('timer_id'))); 
			}
			// Applib::go_to('projects/view/'.$project.'/?group=timesheets&cat='.$_POST['cat'],'success',lang('time_deleted_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('time_deleted_successfully'));
			redirect('projects/view/'.$_POST['project'].'/?group=timesheets&cat='.$_POST['cat']);
			}
		}else{
			$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
			$timer_id = isset($_GET['id']) ? $_GET['id'] : '';

			$data['project'] = $this->uri->segment(4);
			$data['timer_id'] = $timer_id;
			$data['cat'] = $cat;
			$data['action'] = 'delete_time';
			$this->load->view('modal/timelog_action',$data);
		}
	}

	
}

/* End of file projects.php */