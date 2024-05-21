<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class income_statement extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('income_statement');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index($year = NULL)
	{
		//print_r($year); exit;
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('income_statement').' - '.config_item('company_name'));
		$data['page'] = lang('income_statement');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE; 	
		$start_years = Date('Y', strtotime("-8 Year"));
		$current_year = date("Y");
		if(isset($year) && !empty($year)){
			$data['year'] = $year;
			$y = $year;
		} else {
			$data['year'] = date("Y");
			$y =  date("Y");
		}

// 		$this->db->select('DISTINCT(C.category_name)');
// 		$this->db->join('budget_category C','C.cat_id = dgt_budget_revenues.category_id','LEFT');
// 		$this->db->where("DATE_FORMAT(dgt_budget_revenues.revenue_date,'%Y')",$y);
// 		$data['revenue_category'] = $this->db->get('budget_revenues')->result_array();

// 		$this->db->select('DISTINCT(C.category_name)');
// 		$this->db->join('budget_category C','C.cat_id = dgt_budget_expenses.category_id','LEFT');
// 		$this->db->where("DATE_FORMAT(dgt_budget_expenses.expense_date,'%Y')",$y);
// 		$data['expense_category'] = $this->db->get('budget_expenses')->result_array();

		$data['months'] = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'June','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		//echo '<pre>';print_r($data); exit;
		//print_r($this->db->last_query());
			
		$this->template

			 ->set_layout('users')

			 ->build('income_statement',isset($data) ? $data : NULL);
	}

	
}

/* End of file Social_impact.php */