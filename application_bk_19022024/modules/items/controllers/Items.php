<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->module('layouts');	
		User::logged_in();

		$this->load->library(array('template','form_validation'));
		$this->load->model(array('Item','App'));

		App::module_access('menu_items');

		$this->applib->set_locale();
	}

	function index()
	{
		$this->list_items();
	}

	function list_items()
	{

	$this->template->title(lang('item_lookups').' - '.config_item('company_name'));
	$data['page'] = lang('items');
	$data['datatables'] = TRUE;
	$data['invoice_items'] = Item::list_items();
	$data['project_tasks'] = Item::list_tasks();
	$this->template
	->set_layout('users')
	->build('templates',isset($data) ? $data : NULL);
	}

	function add_item()
	{
		if ($this->input->post()) {

		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('operation_failed'));
				$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
				redirect($this->input->post('r_url'));
		}else{		


			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$_POST['item_tax_rate'] = $this->input->post('item_tax_rate');
			$_POST['item_tax_total'] = Applib::format_deci(($_POST['item_tax_rate'] / 100) *  $sub_total);
			$_POST['total_cost'] =  Applib::format_deci($sub_total + $_POST['item_tax_total']);
			$_POST['created_by'] = User::get_id();
			$_POST['branch_id']  = $this->session->userdata('branch_id');

			App::save_data('items_saved',$this->input->post());

			// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message', lang('item_added_successfully'));
				$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
				redirect($_SERVER['HTTP_REFERER']);
		}
		}else{
			$data['rates'] = App::get_by_where('tax_rates',array());
			$this->load->view('modal/add_item',$data);
		}
	}
	function save_task()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('task_name', 'Task Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');

		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('task_add_failed'));
				$this->session->set_flashdata('tokbox_error', lang('task_add_failed'));
				redirect($_SERVER['HTTP_REFERER']);
		}else{
			$visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';

			$form_data = array(
			                'task_name' => $this->input->post('task_name'),
			                'visible' => $visible,
			                'task_desc' => $this->input->post('description'),
			                'branch_id' => $this->session->userdata('branch_id'),
			                'estimate_hours' => Applib::format_deci($this->input->post('estimate')),
			                'saved_by' => User::get_id(),
			            );

			App::save_data('saved_tasks',$form_data);

			// $this->session->set_flashdata('response_status', 'success');
			// $this->session->set_flashdata('message', lang('task_add_success'));
			$this->session->set_flashdata('tokbox_success', lang('task_add_success'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		}else{
		$this->load->view('modal/save_task');
		}
	}

	function edit_item($id = NULL)
	{
		if ($this->input->post()) {

		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');


		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('operation_failed'));
				$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
				redirect($this->input->post('r_url'));
		}else{	

			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$_POST['item_tax_rate'] = $this->input->post('item_tax_rate');
			$_POST['item_tax_total'] = Applib::format_deci(($_POST['item_tax_rate'] / 100) *  $sub_total);
			$_POST['total_cost'] = Applib::format_deci($sub_total + $_POST['item_tax_total']);

			$url = $this->input->post('r_url');
			unset($_POST['r_url']);

			App::update('items_saved',array('item_id' => $this->input->post('item_id')),$this->input->post()); 

			// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message', lang('operation_successful'));
				$this->session->set_flashdata('tokbox_success', lang('operation_successful'));
				redirect($url);
		}
		}else{
				$data['rates'] = App::get_by_where('tax_rates',array());
				$data['id'] = $id;
				$this->load->view('modal/edit_item',$data);
		}
	}
	function edit_task($task = NULL)
	{		
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		
		$this->form_validation->set_rules('task_name', 'Task Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');

		$template_id = $this->input->post('template_id', TRUE);
		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('task_update_failed'));
				$this->session->set_flashdata('tokbox_error', lang('task_update_failed'));
				redirect('items');
		}else{
		$visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No'; 
			$form_data = array(
			                'task_name' => $this->input->post('task_name'),
			                'visible' => $visible,
			                'task_desc' => $this->input->post('description'),
			                'estimate_hours' => Applib::format_deci($this->input->post('estimate')),
			                'saved_by' => User::get_id(),
			            );
			App::update('saved_tasks',array('template_id' => $template_id),$form_data);

			// $this->session->set_flashdata('response_status', 'success');
			// $this->session->set_flashdata('message', lang('task_update_success'));
			$this->session->set_flashdata('tokbox_success', lang('task_update_success'));
			redirect('items');
		}
	}else{
		$data['id'] = $task;
		$this->load->view('modal/edit_task',isset($data) ? $data : NULL);
	}
	}
	function delete_task($task = NULL){
		if ($this->input->post() ){
					$template_id = $this->input->post('template_id', TRUE);
					App::delete('saved_tasks',array('template_id' => $template_id));

					// $this->session->set_flashdata('response_status', 'success');
					// $this->session->set_flashdata('message', lang('item_deleted_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_deleted_successfully'));
					redirect($this->input->post('r_url'));
		}else{
			$data['template_id'] = $task;
			$this->load->view('modal/delete_task',$data);
		}
		
	}
	function delete_item($item = NULL){
		if ($this->input->post() ){
					$item_id = $this->input->post('item', TRUE);
					App::delete('items_saved',array('item_id' => $item_id));

					// $this->session->set_flashdata('response_status', 'success');
					// $this->session->set_flashdata('message', lang('item_deleted_successfully'));
					$this->session->set_flashdata('tokbox_error', lang('item_deleted_successfully'));
					redirect($this->input->post('r_url'));
		}else{
			$data['item_id'] = $item;
			$this->load->view('modal/delete_item',$data);
		}
		
	}
}

/* End of file items.php */