<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budgets extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('time_sheets');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('budgets').' - '.config_item('company_name'));
		$data['page'] = lang('budgets');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['budgets'] = $this->db->get_where('budgets',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
		$this->template

			 ->set_layout('users')

			 ->build('budgets',isset($data) ? $data : NULL);
	}
	public function ajax_budget(){
		$all_budgets = $this->db->get_where('budgets',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
		$page = $_GET['start'];
		$limit = $_GET['length'];
		$this->db->from('budgets');
		$condition = array('branch_id'=>$this->session->userdata('branch_id'));
		$this->db->where($condition); 
		if(!empty($cond_or_like)){
			$this->db->or_like($cond_or_like);
		}
		$this->db->limit($limit,$page);
		$budgets = $this->db->get()->result_array();
		$return_data['recordsTotal'] = $return_data['recordsFiltered'] = count($all_budgets);
		$return_data['data'] = [];
		if(!empty($budgets)){
			$i = 0;
			foreach($budgets as $budget){
				$j = $page + $i +1;
				if($budget['project_id'] != 0){
					$project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
					$project_name = $project['project_title'];
				  }else{
					$project_name = '-';
				  }

				  if(($budget['category_id'] != 0) && ($budget['sub_cat_id'] != 0)){
					$category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
					$subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['sub_cat_id']))->row_array();
					$category_name = $category['category_name'];
					$subcategory_name = $subcategory['sub_category'];
				  }else{
					$category_name = '-';
					$subcategory_name = '-';
				  }
				  $return_data['data'][$i]['budget_no']   	     = $j;
				  $return_data['data'][$i]['budget_title']       = $budget['budget_title'];
				  $return_data['data'][$i]['budget_type']        = $budget['budget_type'];
				  $return_data['data'][$i]['start_date']         = date('d M Y',strtotime($budget['budget_start_date']));
				  $return_data['data'][$i]['end_date'] 		     = date('d M Y',strtotime($budget['budget_end_date']));
				  $return_data['data'][$i]['overall_revenues']   = $budget['overall_revenues'];
				  $return_data['data'][$i]['overall_expenses']   = $budget['overall_expenses'];
				  $return_data['data'][$i]['tax_amount']  		 = $budget['tax_amount'];
				  $return_data['data'][$i]['budget_amount']		 = $budget['budget_amount'];
				  $return_data['data'][$i]['action']			 = '<div class="dropdown"><a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a><ul class="dropdown-menu pull-right"><li><a href="'.base_url().'budgets/edit_budgets/'.$budget['budget_id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a></li><li><a href="#" data-toggle="modal" data-target="#delete_budget'.$budget['budget_id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li></ul></div>';
				$i++;
			}
		}
		echo json_encode($return_data);exit;

	}
	public function add_budgets()
	{
		if($_POST){
			// echo "<pre>"; print_r($_POST); exit;
			$budget_type = $this->input->post('budget_type');

			$result = array(
				'budget_title' => $this->input->post('budget_title'),
				'budget_type' => $this->input->post('budget_type'),
				'budget_start_date' => date('Y-m-d',strtotime($this->input->post('budget_start_date'))),
				'budget_end_date' => date('Y-m-d',strtotime($this->input->post('budget_end_date'))),
				'revenue_title' => json_encode($this->input->post('revenue_title')),
				'revenue_amount' => json_encode($this->input->post('revenue_amount')),
				'overall_revenues' => $this->input->post('overall_revenues'),
				'expenses_title' => json_encode($this->input->post('expenses_title')),
				'expenses_amount' => json_encode($this->input->post('expenses_amount')),
				'overall_expenses' => $this->input->post('overall_expenses'),
				'expected_profit' => $this->input->post('expected_profit'),
				'tax_amount' => $this->input->post('tax_amount'),
				'budget_amount' => $this->input->post('budget_amount')
			);
			if($budget_type == 'project')
			{
				$result['project_id'] = $this->input->post('projects');
			}

			if($budget_type == 'category')
			{
				$result['category_id'] = $this->input->post('category');
				$result['sub_cat_id'] = $this->input->post('sub_category');
			}

			$result['branch_id'] = $this->session->userdata('branch_id');
			// echo "<pre>";
			// print_r($result); exit;

			$this->db->insert('budgets',$result);
			$this->session->set_flashdata('tokbox_success', 'Budget Added Successfully');
            redirect('budgets');


		}else{
			$this->load->module('layouts');
			$this->load->library('template');		 
			$this->template->title(lang('budgets').' - '.config_item('company_name'));
			$data['page'] = lang('budgets');
			$data['datatables'] = TRUE;
			$data['form'] = TRUE;
			if(!empty($this->session->userdata('branch_id'))){
				$data['categories'] =  $this->db->get_where('budget_category',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
				// $this->db->get('budget_category')->result_array();
				$data['projects'] = $this->db->get_where('projects',array('branch_id'=>$this->session->userdata('branch_id'),'status'=>'Active'))->result_array();
				// $this->db->get('projects')->result_array();\
			}
			else{
				$data['categories'] =  $this->db->get_where('budget_category',array())->result_array();
				// $this->db->get('budget_category')->result_array();
				$data['projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
			}
			$this->template
				 ->set_layout('users')
				 ->build('add_budgets',isset($data) ? $data : NULL);
		}
	}


	public function edit_budgets($budget_id)
	{
		if($_POST)
		{
			$budget_type = $this->input->post('budget_type');

			$result = array(
				'budget_title' => $this->input->post('budget_title'),
				'budget_type' => $this->input->post('budget_type'),
				'budget_start_date' => date('Y-m-d',strtotime($this->input->post('budget_start_date'))),
				'budget_end_date' => date('Y-m-d',strtotime($this->input->post('budget_end_date'))),
				'revenue_title' => json_encode($this->input->post('revenue_title')),
				'revenue_amount' => json_encode($this->input->post('revenue_amount')),
				'overall_revenues' => $this->input->post('overall_revenues'),
				'expenses_title' => json_encode($this->input->post('expenses_title')),
				'expenses_amount' => json_encode($this->input->post('expenses_amount')),
				'overall_expenses' => $this->input->post('overall_expenses'),
				'expected_profit' => $this->input->post('expected_profit'),
				'tax_amount' => $this->input->post('tax_amount'),
				'budget_amount' => $this->input->post('budget_amount')
			);
			if($budget_type == 'project')
			{
				$result['project_id'] = $this->input->post('projects');
			}

			if($budget_type == 'category')
			{
				$result['category_id'] = $this->input->post('category');
				$result['sub_cat_id'] = $this->input->post('sub_category');
			}

			$this->db->where('budget_id',$budget_id);
			$this->db->update('budgets',$result);
			$this->session->set_flashdata('tokbox_success', 'Budget Updated Successfully');
            redirect('budgets');
		}else{			
			$this->load->module('layouts');
			$this->load->library('template');		 
			$this->template->title(lang('budgets').' - '.config_item('company_name'));
			$data['page'] = lang('budgets');
			$data['datatables'] = TRUE;
			$data['form'] = TRUE;
			$data['budgets'] = $this->db->get_where('budgets',array('budget_id'=>$budget_id))->row_array();
			$data['categories'] = $this->db->get('budget_category')->result_array();
			$data['projects'] = $this->db->get('projects')->result_array();
			$this->template
				 ->set_layout('users')
				 ->build('edit_budgets',isset($data) ? $data : NULL);
		}
	}

	public function check_subcategories()
	{
		$category_id = $this->input->post('category_id');
		$sub_category = $this->db->get_where('budget_subcategory',array('cat_id'=>$category_id))->result_array();
		echo json_encode($sub_category); exit;
	}

	public function delete_budget($budget_id)
	{
		$this->db->where('budget_id',$budget_id);
		$this->db->delete('budgets');
		$this->session->set_flashdata('tokbox_success', 'Budget Deleted Successfully');
        redirect('budgets');
	}

	

	
}

/* End of file Social_impact.php */