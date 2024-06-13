<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class budget_statement extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('budget_statement');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('budget_statement').' - '.config_item('company_name'));
		$data['page'] = lang('budget_statement');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE; 	
		$start_years = Date('Y', strtotime("-3 Year"));
		$current_year = date("Y");
		if(isset($year) && !empty($year)){
			$data['year'] = $year;
			$y = $year;
		} else {
			$data['year'] = date("Y");
			$y =  date("Y");
		}

		$this->db->select('DISTINCT(C.category_name)');
		$this->db->join('budget_category C','C.cat_id = dgt_budget_revenues.category_id','LEFT');
		$this->db->where("budget_revenues.branch_id",$this->session->userdata('branch_id'));
		$this->db->where("DATE_FORMAT(dgt_budget_revenues.revenue_date,'%Y')",$y);
		$data['revenue_category'] = $this->db->get('budget_revenues')->result_array();

		$this->db->select('DISTINCT(C.category_name)');
		$this->db->join('budget_category C','C.cat_id = dgt_budget_expenses.category_id','LEFT');
		$this->db->where("DATE_FORMAT(dgt_budget_expenses.expense_date,'%Y')",$y);
		$this->db->where("budget_expenses.branch_id",$this->session->userdata('branch_id'));
		$data['expense_category'] = $this->db->get('budget_expenses')->result_array();

		$data['months'] = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'June','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
;
		//echo '<pre>';print_r($data['profit']);exit;
		//print_r($this->db->last_query());
   		for ($i=$start_years; $i <=$current_year ; $i++) { 
			$this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
			$this->db->where("year",2019);
			$this->db->where("type","revenue");
			$this->db->where("branch_id",$this->session->userdata('branch_id'));
			$total_revenue = $this->db->get('budget_statement')->row_array();
						
			
			$this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
			$this->db->where("year",2019);
			$this->db->where("type","expense");
			$this->db->where("branch_id",$this->session->userdata('branch_id'));
			$total_expense = $this->db->get('budget_statement')->row_array();

			$profit = array();
	   		foreach ($total_revenue as $key => $value) {
	          $profit[$key] = $total_revenue[$key] - $total_expense[$key];

	        }

	     
	       //
	        $this->db->select('*');
			$this->db->where("year",2019);
			$this->db->where("type","tax");
			$taxes = $this->db->get('budget_statement')->result_array();
			
			$after_tax = array();
			if(!empty($taxes)){ 
				foreach ($taxes as $tax) {
					
				 	$jan = $profit['jan']/$tax['tax_percentage']; 
	              	$feb = $profit['feb']/$tax['tax_percentage']; 
	               	$mar = $profit['mar']/$tax['tax_percentage'];
	               	$apr = $profit['apr']/$tax['tax_percentage']; 
	               	$may = $profit['may']/$tax['tax_percentage'];
	               	$jun = $profit['jun']/$tax['tax_percentage']; 
	               	$jul = $profit['jul']/$tax['tax_percentage']; 
	              	$aug = $profit['aug']/$tax['tax_percentage']; 
	              	$sep = $profit['sep']/$tax['tax_percentage'];
	              	$oct = $profit['oct']/$tax['tax_percentage'];
	              	$nov = $profit['nov']/$tax['tax_percentage']; 
	             	$dece = $profit['dece']/$tax['tax_percentage']; 

	            	$after_tax[] = array('jan' => $jan,
	                                      'feb' => $feb,
	                                      'mar' => $mar,
	                                      'apr' => $apr,
	                                      'may' => $may,
	                                      'jun' => $jun,
	                                      'jul' => $jul,
	                                      'aug' => $aug,
	                                      'sep' => $sep,
	                                      'oct' => $oct,
	                                      'nov' => $nov,
	                                      'dece' => $dece
	                                    );
	        	}
	    	}
	    	 //echo '<pre>'; print_r($after_tax); exit; 
		 	$total_taxes = array('jan' => 0,
	                                      'feb' => 0,
	                                      'mar' => 0,
	                                      'apr' => 0,
	                                      'may' => 0,
	                                      'jun' => 0,
	                                      'jul' => 0,
	                                      'aug' => 0,
	                                      'sep' => 0,
	                                      'oct' => 0,
	                                      'nov' => 0,
	                                      'dece' => 0
	                                    );
	      	foreach ($after_tax as $k=>$subArray) {
	      		
	            foreach ($subArray as $id=>$value) {
	            	//echo $id; exit;
	              $total_taxes[$id] +=$value;
	              
	            }
	      	}
	      	
	  	 	$net_earning = array();
	        foreach ($profit as $key => $value) {
	          $net_earning[$key] = $profit[$key] - $total_taxes[$key];

	        }
	        //echo print_r($net_earning); exit;
	        $data['net_earning_month_wise']=$net_earning;

        }
   		 	// $data['jan_b'] =   $net_earning['jan'];
   		 	// $data['feb_b'] = $data['jan_b'] + $net_earning['feb']; 
   		 	// $data['mar_b'] = $data['feb_b'] + $net_earning['mar']; 

   		 	// echo '<pre>'; print_r($data);
   		 	// exit;
        




		$this->template

			 ->set_layout('users')

			 ->build('budget_statement',isset($data) ? $data : NULL);
	}

	public function add_budget_statement()
	{
		if($_POST){
				// echo "<pre>"; print_r($_POST); exit;
			$budget_type = $this->input->post('type');
			if($budget_type =='tax'){
				$result = array(
					'budget_name' => $this->input->post('budget_name'),
					'tax_percentage' => $this->input->post('tax_percentage'),
					'type' => $this->input->post('type'),
					'year' => $this->input->post('year'),
				//	'tax_percentage' => $this->input->post('tax_percentage'),
					'created_by' => User::get_id()
				);
			} else {
				$result = array(
					'budget_name' => $this->input->post('budget_name'),
					'type' => $this->input->post('type'),
					'jan' => $this->input->post('jan'),
					'feb' => $this->input->post('feb'),
					'mar' => $this->input->post('mar'),
					'apr' => $this->input->post('apr'),
					'may' => $this->input->post('may'),
					'jun' => $this->input->post('jun'),
					'jul' => $this->input->post('jul'),
					'aug' => $this->input->post('aug'),
					'sep' => $this->input->post('sep'),
					'oct' => $this->input->post('oct'),
					'nov' => $this->input->post('nov'),
					'dece' => $this->input->post('dece'),
					'year' => $this->input->post('year'),
				//	'tax_percentage' => $this->input->post('tax_percentage'),
					'created_by' => User::get_id()
				);
			}
			if(!empty($this->input->post('tax_percentage'))){
				$result['tax_percentage'] = $this->input->post('tax_percentage');
			}	
			// echo "<pre>";
			// print_r($result); exit;
			if(!empty($this->input->post('id'))){
				$this->db->where("id",$this->input->post('id'));
				$id = $this->db->update('budget_statement',$result);					
				$this->session->set_flashdata('tokbox_success', $this->input->post('budget_name').' '. $this->input->post('type').' Updated Successfully');
				echo json_encode($id); exit;
			}else{
				$result['branch_id'] = $this->session->userdata('branch_id');
				$this->db->insert('budget_statement',$result);
				$id = $this->db->insert_id();
				$this->session->set_flashdata('tokbox_success', $this->input->post('budget_name').' '. $this->input->post('type').' Added Successfully');
				echo json_encode($id); exit;
			}
	            //redirect('budgets');
        }
    }

    public function delete_budget_statement($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('budget_statement');
		$this->session->set_flashdata('tokbox_success', $this->input->post('budget_name').' Deleted Successfully');
        redirect('budget_statement');
	}
	
}

/* End of file Social_impact.php */