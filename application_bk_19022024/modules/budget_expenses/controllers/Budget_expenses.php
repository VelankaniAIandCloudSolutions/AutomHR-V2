<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_expenses extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('budget_expenses');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('budget_expenses').' - '.config_item('company_name'));
		$data['page'] = lang('budget_expenses');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE; 
		//$this->session->unset_userdata('budget_title');
		 //$this->session->unset_userdata('budget_start_date');
		// $this->session->unset_userdata('budget_end_date');

		//print_r($_POST); exit;
		if($this->session->userdata('role_id')!=1){
			
		$data['categories'] = $this->db->get_where('budget_category',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
		}
		else{
			$data['categories'] = $this->db->get('budget_category')->result_array();
	
		}

		
		if($_POST){

				
			
				$this->session->unset_userdata('cat_id');
				$this->session->unset_userdata('budget_start_date');
				$this->session->unset_userdata('budget_end_date');
				
				
				if($_POST['cat_id']!= ''){
					$this->session->unset_userdata('cat_id');
					$this->session->set_userdata('cat_id',$_POST['cat_id']);
					$this->db->where('category_id',$_POST['cat_id']);
				}
				if($_POST['budget_start_date']!= ''){
					$this->session->unset_userdata('budget_start_date');
					$this->session->set_userdata('budget_start_date',$_POST['budget_start_date']);
					$start_date = date("Y-m-d", strtotime($_POST['budget_start_date']));
					$this->db->where('expense_date >=', $start_date);
				}
				if($_POST['budget_end_date']!= ''){
					$this->session->unset_userdata('budget_end_date');
					$this->session->set_userdata('budget_end_date',$_POST['budget_end_date']);
					$to_date = date("Y-m-d", strtotime($_POST['budget_end_date']));
					$this->db->where('expense_date <=', $to_date);
				}
			//	return $this->db->get()->result_array();
				$this->db->where('branch_id',$this->session->userdata('branch_id'));

				$data['budget_expenses'] = $this->db->get('budget_expenses')->result_array();
					
		} else {

				
				$this->db->where('branch_id',$this->session->userdata('branch_id'));
				$data['budget_expenses'] = $this->db->get('budget_expenses')->result_array();
		}
		
		
		//$data['budget_category'] = $this->db->get('budget_category')->result_array();
		//$data['budget_subcategory'] = $this->db->get('budget_subcategory')->result_array();
		//echo '<pre>';print_r($data); exit;
		$this->template

			 ->set_layout('users')

			 ->build('budget_expenses',isset($data) ? $data : NULL);
	}

	function ajax_budget_expense(){
		$page = $_GET['start'];
		$limit = $_GET['length'];

		$this->db->where('branch_id',$this->session->userdata('branch_id'));
		$all_budget_expenses = $this->db->get('budget_expenses')->result_array();
		$return_data['recordsTotal'] = $return_data['recordsFiltered'] = count($all_budget_expenses);


		if($_GET['cat_id']!= ''){
			$this->session->unset_userdata('cat_id');
			$this->session->set_userdata('cat_id',$_GET['cat_id']);
			$this->db->where('category_id',$_GET['cat_id']);
		}
		if($_GET['budget_start_date']!= ''){
			$this->session->unset_userdata('budget_start_date');
			$this->session->set_userdata('budget_start_date',$_POST['budget_start_date']);
			$start_date = date("Y-m-d", strtotime($_GET['budget_start_date']));
			$this->db->where('expense_date >=', $start_date);
		}
		if($_GET['budget_end_date']!= ''){
			$this->session->unset_userdata('budget_end_date');
			$this->session->set_userdata('budget_end_date',$_GET['budget_end_date']);
			$to_date = date("Y-m-d", strtotime($_GET['budget_end_date']));
			$this->db->where('expense_date <=', $to_date);
		}

		$this->db->where('branch_id',$this->session->userdata('branch_id'));
		$this->db->limit($limit,$page);
		$budget_expenses = $this->db->get('budget_expenses')->result_array();
		if(!empty($budget_expenses)){
			$i = 0;
			foreach($budget_expenses as $budget){ 
				$j = $page + $i + 1;
				if($budget['project_id'] != 0){
					$project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
					$project_name = $project['project_title'];
				}else{
					$project_name = '-';
				}

				if(($budget['category_id'] != 0) || ($budget['s_category_id'] != 0)){
					$category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
					$subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['s_category_id']))->row_array();
					$category_name = $category['category_name'];
					$subcategory_name = $subcategory['sub_category'];
				}else{
					$category_name = '-';
					$subcategory_name = '-';
				}

				$budget_expenses_titles = json_decode($budget['expenses_title']);
				$budget_expenses_title = implode(' , ', $budget_expenses_titles);
				$budget_expenses_amounts = json_decode($budget['expenses_amount']);
				$budget_expenses_amount = implode(' , ', $budget_expenses_amounts);

				$return_data['data'][$i]['budget_no']		= $j;
				$return_data['data'][$i]['notes']			= $budget['notes'];
				$return_data['data'][$i]['category']		= $category_name;
				$return_data['data'][$i]['subcategory']		= $subcategory_name;
				$return_data['data'][$i]['amount']			= $budget['amount'];
				$return_data['data'][$i]['expense_date']	= date('d M Y',strtotime($budget['expense_date']));
				$return_data['data'][$i]['action']			= '<div class="dropdown"><a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a><ul class="dropdown-menu pull-right"><li><a href="'.base_url().'budget_expenses/edit/'.$budget['id'].'" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i> Edit</a></li><li><a href="#" data-toggle="modal" data-target="#delete_budget'.$budget['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li></ul></div>';

				$i++;
			}
		}
		echo json_encode($return_data);exit;
	}

	function create()
	{

		//if($this->_can_add_expense() == FALSE){ App::access_denied('expenses'); }

		if ($this->input->post()) {

		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			Applib::go_to('budget_expenses','error',lang('operation_failed'));
		}else{

			$attached_file = NULL;
			
				if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) {
					$upload_response = $this->upload_slip($this->input->post());
					if($upload_response){
						$attached_file = $upload_response;
					}else{
						$attached_file = NULL;
						// Applib::go_to('expenses','error',lang('file_upload_failed'));
						$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
						redirect('budget_expenses');
					}

				}
				
              	$expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));

              	$data = array(
              				'added_by'  	=> User::get_id(),
              				'amount'		=> $this->input->post('amount',TRUE),
              				'expense_date'	=> $expense_date,
              				'branch_id'	=> $this->session->userdata('branch_id'),
              				'notes'			=> $this->input->post('notes'),
              				'receipt'		=> $attached_file,
              				'category_id'		=> $this->input->post('category'),
              				's_category_id'		=> $this->input->post('sub_category'),
              	);
                    
		if($expense_id = App::save_data('budget_expenses',$data)){
			//$title = ($this->input->post('project') == 'NULL') ? 'N/A' : $p->project_title;
			// Log activity
			// $data = array(
			// 	'module' => 'expenses',
			// 	'module_field_id' => $expense_id,
			// 	'user' => User::get_id(),
			// 	'activity' => 'activity_expense_created',
			// 	'icon' => 'fa-plus',
			// 	'value1' => $cur.' '.$this->input->post('amount'),
			// 	'value2' => $title
			// 	);
			// App::Log($data);

			// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_created_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('expense_created_successfully'));
			redirect($_SERVER['HTTP_REFERER']);
				}
			}

		}else{
			$auto_select = NULL;
			if(!empty($this->session->userdata('branch_id'))){
				$data['categories'] = $this->db->get_where('budget_category',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
			}
			else{
				$data['categories'] = $this->db->get_where('budget_category',array())->result_array();
			}
			$data['sub_categories'] = $this->db->get('budget_subcategory')->result_array();
			$data['form'] = TRUE;
			$this->load->view('modal/create_expense',$data);

		}
	}

// 	function _can_add_expense(){
// 		if (User::is_admin() || User::perm_allowed(User::get_id(),'add_expenses')) {
// 			return TRUE;
// 		}else{
// 			return FALSE;	
// 		}
// 	}

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
				redirect('budget_expenses');
		}else{	
			$receipt = NULL;
			if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) {

					$upload_response = $this->upload_slip($this->input->post());
					if($upload_response){
						$receipt = $upload_response;
						App::update('budget_expenses',array('id'=>$expense_id),array('receipt' => $receipt));
					}else{
						$receipt = NULL;
						// Applib::go_to('expenses','error',lang('file_upload_failed'));
						$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
						redirect('budget_expenses');
					}

				}
			 
              $expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));

              $data = array(
                				'added_by'  	=> User::get_id(),
                				'amount'		=> $this->input->post('amount'),
                				'expense_date'	=> $expense_date,
                				'notes'			=> $this->input->post('notes'),                				
                				'category_id'		=> $this->input->post('category'),
                				's_category_id'		=> $this->input->post('sub_category')

                				);

             	 $this->db->where('id',$expense_id);
             	 $result= $this->db->update('budget_expenses',$data);
             	 //print_r($result); exit;
                if($result){

    //             $title = ($this->input->post('project') == 'NULL' || $this->input->post('project') == 0) ? 'N/A' : $p->project_title;
    //             	// Log activity
				// $data = array(
				// 	'module' => 'expenses',
				// 	'module_field_id' => $expense_id,
				// 	'user' => User::get_id(),
				// 	'activity' => 'activity_expense_edited',
				// 	'icon' => 'fa-pencil',
				// 	'value1' => $cur.' '.$this->input->post('amount'),
				// 	'value2' => $title
				// 	);
				// App::Log($data);


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
			$data['categories'] = $this->db->get_where('budget_category',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
			$data['sub_categories'] = $this->db->get('budget_subcategory')->result_array();
			$data['inf'] = $this->db->get_where('budget_expenses',array('id'=>$id))->row_array();
			$data['id'] = $id;
			//echo '<pre>';print_r($data); exit;
			$this->load->view('modal/edit_expense',$data);

		}
	}
// 	function show($id = NULL){
// 		$data = array('show_client' => 'Yes');
// 		App::update('expenses',array('id'=>$id),$data);
// 		// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
// 		$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
// 				redirect($_SERVER['HTTP_REFERER']);
// 	}

	

	public function delete_budget($expense_id)
	{
		$this->db->where('id',$expense_id);
		$this->db->delete('budget_expenses');
		$this->session->set_flashdata('tokbox_success', 'Expense Deleted Successfully');
        redirect('budget_expenses');
	}

// 	/**
// 	 * get_user_projects
// 	 *
// 	 * Get user projects
// 	 *
// 	 * @access	public
// 	 * @param	type	name
// 	 * @return	array	
// 	 */
	 
// 	function get_user_projects()
// 	{
// 		if (!User::is_client()) {
// 			if (User::is_admin() || (User::is_staff() && User::perm_allowed(User::get_id(),'view_all_projects'))) {
// 				return Project::all();
// 			}else{
// 				return array();
// 			}
// 		}else{
// 			return Project::by_client(User::profile_info(User::get_id())->company);
// 		}
// 	}


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



// 	private function staff_expenses(){

// 		$projects = $this->db->select('project_assigned')
// 							 ->where('assigned_user',User::get_id())
// 							 ->get('assign_projects')->result_array();
// 		$pro = array();
// 		foreach ($projects as $key => $p) {
// 			$pro[] = $p['project_assigned'];
			
// 		}

// 		$expenses = array();
// 		if(count($pro) > 0){
// 			$expenses = $this->db->where_in('project', $pro)->get('expenses')->result();
// 		}
		
// 		return $expenses;
// 	}

// }
		
}

/* End of file Social_impact.php */