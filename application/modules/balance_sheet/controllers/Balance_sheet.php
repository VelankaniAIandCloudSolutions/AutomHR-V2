<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_sheet extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('balance_sheet');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('balance_sheet'));
		$data['page'] = lang('balance_sheet');
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

		$data['months'] = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'June','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');;
		for ($i=$start_years; $i <=$current_year ; $i++) { 
			$this->db->select('(sum(jan)+sum(feb)+sum(mar)+sum(apr)+sum(may)+sum(jun)+sum(jul)+sum(aug)+sum(sep)+sum(oct)+sum(nov)+sum(dece)) as total_asset');
	      	$this->db->where("year",$i);
	      	$this->db->where("type","asset");
	      	$this->db->where("branch_id",$this->session->userdata('branch_id'));
	      	$total_asset = $this->db->get('balance_sheet')->row_array();

	      	$this->db->select('(sum(jan)+sum(feb)+sum(mar)+sum(apr)+sum(may)+sum(jun)+sum(jul)+sum(aug)+sum(sep)+sum(oct)+sum(nov)+sum(dece)) as total_liabilitie_shareholder');
	      	$this->db->where("year",$i);
	      	$this->db->where("type","liabilitie");
	      	$this->db->where("branch_id",$this->session->userdata('branch_id'));
	      	$this->db->or_where("type","shareholder");
	      	$total_liabilitie_shareholder = $this->db->get('balance_sheet')->row_array();
     	}
      	$data['total_asset_color'] = $total_asset;
      	$data['total_liabilitie_shareholder_color'] = $total_liabilitie_shareholder;
      	
		$this->template

			 ->set_layout('users')

			 ->build('balance_sheet',isset($data) ? $data : NULL);
	}

	public function add_balance_sheet()
	{
		if($_POST){
			// echo "<pre>"; print_r($_POST); exit;
			//type = $this->input->post('type');
			
			$result = array(
				'title' => $this->input->post('title'),
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
			
			
			if(!empty($this->input->post('id'))){
				$this->db->where("id",$this->input->post('id'));
				$id = $this->db->update('balance_sheet',$result);					
				$this->session->set_flashdata('tokbox_success', ucfirst($this->input->post('title')).' '. ucfirst($this->input->post('type')).' Updated Successfully');
				echo json_encode($id); exit;
			}else{
				$result['branch_id'] = $this->session->userdata('branch_id');
				$this->db->insert('balance_sheet',$result);
				$id = $this->db->insert_id();
				$this->session->set_flashdata('tokbox_success', ucfirst($this->input->post('title')).' '. ucfirst($this->input->post('type')).' Added Successfully');
				echo json_encode($id); exit;
			}
	            //redirect('budgets');
        }
    }

    public function delete_balance_sheet($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('balance_sheet');
		$this->session->set_flashdata('tokbox_success', ucfirst($this->input->post('title')).' Deleted Successfully');
        redirect('balance_sheet/index/'.$this->input->post('year'));
	}
	
}

/* End of file Social_impact.php */