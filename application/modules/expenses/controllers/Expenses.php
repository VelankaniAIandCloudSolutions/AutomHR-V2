<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses extends MX_Controller {



	function __construct()
	{
		parent::__construct();

		$this->load->module('layouts');	
		User::logged_in();

		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('expenses').' - '.config_item('company_name'));
		$this->load->model(array('App','Client','Expense','Project'));

		App::module_access('menu_expenses');

		if (User::is_admin() || User::perm_allowed(User::get_id(),'view_all_expenses')) 
		{
			if(isset($_GET['project']))
			{
				$this->expenses = App::get_by_where('expenses',array('id !='=>'0','project' => $_GET['project']));        	
			}
			else
			{
				// $this->expenses = App::get_by_where('expenses',array('id !='=>'0'));
				$this->expenses = $this->staff_expenses();
			}
		}
		elseif (App::is_permit('menu_expenses','read')) 
		{
			$this->expenses = $this->staff_expenses();
		}
		else
		{   
			$this->expenses = App::get_by_where('expenses',
				array('client'=>User::profile_info(User::get_id())->company,'show_client'=>'Yes','branch_id'=>$this->session->userdata('branch_id')));
		}

		$this->applib->set_locale();
	}


	function index()
	{

		$data['page'] = lang('expenses');

		$data['datatables'] = TRUE;
		$data['datepicker'] = TRUE;
		$data['form'] = TRUE;
		$data['expenses'] = $this->expenses;
		$data['attach_slip'] = TRUE;


		$user_details = $this->db->get_where('users',array('id'=>$this->session->userdata('user_id')))->row_array();
		$this->template
		->set_layout('users')
		->build('expenses',isset($data) ? $data : NULL);
	}



	function view($id = NULL)
	{	
		if(!$this->_can_view_expense($id)){ redirect(); }
		$this->load->model('Invoice');

		$data['page'] = lang('expenses');
		$data['show_links'] = TRUE;
		$data['datepicker'] = TRUE;
		$data['id'] = $id;
$data['expenses'] = $this->expenses; // GET a list of the Expenses
$this->template
->set_layout('users')
->build('view',isset($data) ? $data : NULL);
}


function _can_view_expense($expense){
	if(User::is_admin()){ return TRUE; }
	$info = Expense::view_by_id($expense);

	if($info->show_client == 'No' && User::is_client()){ return FALSE; }

	if($info->client == User::profile_info(User::get_id())->company) { return TRUE; }

	if(User::perm_allowed(User::get_id(),'view_all_expenses')) { return TRUE; }

	if(App::is_permit('menu_expenses','read')){
		return (Project::is_assigned(User::get_id(),$info->project)) ? TRUE : FALSE;
	}else{
		return FALSE;
	}
}

function create()
{

	if($this->_can_add_expense() == FALSE){ App::access_denied('expenses'); } 

	if ($this->input->post()) 
	{
		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			Applib::go_to('expenses','error',lang('operation_failed'));
		}
		else
		{
			$attached_file = NULL;				
			if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) 
			{
				$upload_response = $this->upload_slip($this->input->post());
				if($upload_response)
				{
					$attached_file = $upload_response;
				}
				else
				{
					$attached_file = NULL;
// Applib::go_to('expenses','error',lang('file_upload_failed'));
					$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
					redirect('expenses');
				}

			}
			$project_id = $this->input->post('project',TRUE);
			$p = Project::by_id($project_id);

			$billable = ($this->input->post('billable') == 'on') ? '1' : '0';
			$show_client = ($this->input->post('show_client') == 'on') ? 'Yes' : 'No';
			$invoiced = ($this->input->post('invoiced') == 'on') ? '1' : '0';
			$client = ($this->input->post('project') == 'NULL') ? $this->input->post('client') : $p->client ;
			$cur = Client::client_currency($client)->code;
			$expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));
			$exp_approvers = serialize($this->input->post('expense_approvers'));

			$data = array(
				'added_by'  	=> User::get_id(),
				'billable'		=> $billable,
				'amount'		=> $this->input->post('amount',TRUE),
				'expense_date'	=> $expense_date,
				'notes'			=> $this->input->post('notes'),
				'project'		=> $this->input->post('project',TRUE),
				'client'		=> $client,
				'receipt'		=> $attached_file,
				'invoiced'		=> $invoiced,
				'show_client'	=> $show_client,
				'branch_id'	    => $this->session->userdata('branch_id'),
				'category'		=> $this->input->post('category'),
				'reports_to'	=> $this->input->post('reports_to'),
				'type'	=> $this->input->post('type'),
				'default_expense_approval' => $this->input->post('default_expense_approval'),
				'expense_approvers' => serialize($this->input->post('expense_approvers')),
				'message_to_approvers' => $this->input->post('message_to_approvers')
			);

			$expense_id = App::save_data('expenses',$data);

			$approvers = unserialize($exp_approvers);

			if (count($approvers) > 0) {
				foreach ($approvers as $key => $value) {
					$approvers_details = array(
						'approvers' => $value,
						'expense' => $expense_id,
						'status' => 0
					);

					$result = $this->db->insert('expense_approvers',$approvers_details);
				}
			}

			$title = ($this->input->post('project') == 'NULL') ? 'N/A' : $p->project_title;
// Log activity
			$data = array(
				'module' => 'expenses',
				'module_field_id' => $expense_id,
				'user' => User::get_id(),
				'activity' => 'activity_expense_created',
				'icon' => 'fa-plus',
				'branch_id' => $this->session->userdata('branch_id'),
				'value1' => $cur.' '.$this->input->post('amount'),
				'value2' => $title
			);
			App::Log($data);

			$this->session->set_flashdata('tokbox_success', lang('expense_created_successfully'));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	else
	{
		$auto_select = NULL;
		if(isset($_GET['project'])){ $auto_select = $_GET['project']; }else{ $auto_select = NULL; }

		$data['categories'] = $this->categories_list();
		$data['projects'] = $this->get_staff_projects(User::get_id()); 
		$data['auto_select_project'] = $auto_select;
		$data['form'] = TRUE;
		$this->load->view('modal/create_expense',$data);
	}
}

function _can_add_expense(){
	if (App::is_permit('menu_expenses','write')) 
	{
		return TRUE;
	}else{
		return FALSE;	
	}
}

function edit($id = NULL)
{
	if ($this->input->post()) {


		$expense_id = $this->input->post('expense', TRUE);

		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$_POST = '';
// Applib::go_to('expenses','error',lang('error_in_form'));
			$this->session->set_flashdata('tokbox_success', lang('error_in_form'));
			redirect('expenses');
		}else{	
			$receipt = NULL;
			if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) {

				$upload_response = $this->upload_slip($this->input->post());
				if($upload_response){
					$receipt = $upload_response;
					App::update('expenses',array('id'=>$expense_id),array('receipt' => $receipt));
				}else{
					$receipt = NULL;
// Applib::go_to('expenses','error',lang('file_upload_failed'));
					$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
					redirect('expenses');
				}

			}
			$project_id = $this->input->post('project',TRUE);
			$p = Project::by_id($project_id);
			$billable = ($this->input->post('billable') == 'on') ? '1' : '0';
			$show_client = ($this->input->post('show_client') == 'on') ? 'Yes' : 'No';
			$invoiced = ($this->input->post('invoiced') == 'on') ? '1' : '0';

			$client = ($this->input->post('project') == 0 || $this->input->post('project') == 'NULL') 
			? $this->input->post('client') : $p->client ;
			$cur = Client::client_currency($client)->code;
			$expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));

			$data = array(
				'billable'		=> $billable,
				'amount'		=> $this->input->post('amount'),
				'expense_date'	=> $expense_date,
				'notes'			=> $this->input->post('notes'),
				'project'		=> $project_id,
				'client'		=> $client,
				'invoiced'		=> $invoiced,
				'show_client'	=> $show_client,
				'category'		=> $this->input->post('category'),
				'type'	=> $this->input->post('type')
			);
			if(App::update('expenses',array('id'=>$expense_id),$data)){

				$title = ($this->input->post('project') == 'NULL' || $this->input->post('project') == 0) ? 'N/A' : $p->project_title;
// Log activity
				$data = array(
					'module' => 'expenses',
					'module_field_id' => $expense_id,
					'user' => User::get_id(),
					'activity' => 'activity_expense_edited',
					'icon' => 'fa-pencil',
					'branch_id' => $this->session->userdata('branch_id'),
					'value1' => $cur.' '.$this->input->post('amount'),
					'value2' => $title
				);
				App::Log($data);


// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
				$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
				redirect($_SERVER['HTTP_REFERER']);

			}
			else
			{
// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
				$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}else{


		$data['form'] = TRUE;
		$data['categories'] = App::get_by_where('categories',array('module'=>'expenses'));
		$data['projects'] = $this->get_user_projects();
		$data['id'] = $id;
		$this->load->view('modal/edit_expense',$data);

	}
}
function show($id = NULL){
	$data = array('show_client' => 'Yes');
	App::update('expenses',array('id'=>$id),$data);
// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
	$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
	redirect($_SERVER['HTTP_REFERER']);
}



function delete($id = NULL)
{
	if ($this->input->post()) {

		$expense = $this->input->post('expense', TRUE);
		App::delete('expenses',array('id'=>$expense));

// Applib::go_to('expenses','success',lang('expense_deleted_successfully'));
		$this->session->set_flashdata('tokbox_success', lang('expense_deleted_successfully'));
		redirect('expenses');
	}else{
		$data['expense'] = $id;
		$this->load->view('modal/delete_expense',$data);

	}
}

/**
* get_user_projects
*
* Get user projects
*
* @access	public
* @param	type	name
* @return	array	
*/

function get_user_projects()
{
	if (!User::is_client()) {
		if (User::is_admin() || (App::is_permit('menu_expenses','read') && User::perm_allowed(User::get_id(),'view_all_projects'))) {
			return Project::all();
		}else{
			return array();
		}
	}else{
		return Project::by_client(User::profile_info(User::get_id())->company);
	}
}

function get_staff_projects($staff_id)
{
	return Project::staff_project($staff_id);
}

function upload_slip($data){

	Applib::is_demo();

	if ($data) {
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = config_item('allowed_files');
		$config['remove_spaces'] = TRUE;
		$config['overwrite']  = FALSE;
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('receipt'))
		{
			$filedata = $this->upload->data();
			return $filedata['file_name'];
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}



private function staff_expenses(){
	$this->db->select("expenses.*");
	$this->db->from("expenses");
	$this->db->join("expense_approvers", "expense_approvers.expense = expenses.id","inner");
	$this->db->where("expenses.added_by", $this->session->userdata('user_id'));
	$this->db->or_where("expense_approvers.approvers", $this->session->userdata('user_id'));
	$this->db->group_by("expenses.id");
	$expenses =  $this->db->get()->result();
	return $expenses;
}

public function update_expense_status()
{		

	if( (isset($_POST['id'])&&isset($_POST['status'])) && (!empty($_POST['id'])&&!empty($_POST['status'])) )
	{
		$status = $_POST['status'];
		$update_array = array('status'=>$status);

		if($status == 2)
		{
			$this->db->where("id", $_POST['id']);
			$this->db->update("dgt_expenses", array("admin_approved" =>'2'));
		}
		else{
			$this->db->select("*");
			$this->db->from("expense_approvers");
			$this->db->where("expense",$_POST['id']);
			$this->db->where("status",0);
			$tmp_query = $this->db->get();
			$count_approval = $tmp_query->result();
			if(sizeof($count_approval) == 1)
			{
				$this->db->where("id", $_POST['id']);
				$this->db->update("dgt_expenses", array("admin_approved" =>'1'));
			}
		}


		$this->db->where('expense',$_POST['id']);
		if($this->session->userdata('role_id') == 1)
		{

		}
		else{
			$this->db->where('approvers',$this->session->userdata('user_id'));
		}

		$this->db->update('expense_approvers',$update_array);			 
		$afftectedRows = $this->db->affected_rows();
		print_r(json_encode(array('status'=>$status,'updated'=>$afftectedRows,'expense'=>$_POST['id'])));
		exit;
	} 
}

	public function get_approvers()
	{
		$this->db->select("id");
		$this->db->where(array(
			'role_id !=' => 2,
			'role_id !=' => 1,
			'activated' => 1,
			'banned' => 0,
			'id !=' => $this->session->userdata('user_id')
		));
		// $this->db->limit(50);
		$this->db->order_by("id","desc");
		$approvers = $this->db->get('users')->result(); // Corrected method call

		$data=array();
		foreach($approvers as $r)
		{
			$data['value']=$r->id;
		// $data['label']=ucfirst($r->username);
			$data['label']=ucfirst(User::displayName($r->id));
			$json[]=$data;


		}
		echo json_encode($json);
		exit;
	}


public function categories_list()
{
	$this->db->select("*");
	$this->db->from("categories");
	$this->db->where("module","expenses");
	$query = $this->db->get();
	$resposne = $query->result();
	return $resposne;
}


}

/* End of file expenses.php */
