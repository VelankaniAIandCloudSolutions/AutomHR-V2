<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Rules_regulation extends MX_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->library(array('tank_auth'));
		$this->load->library(array('form_validation'));
		$this->load->model(array( 'App'));
    }

    function index()
    {
                $this->load->module('layouts');
                $this->load->library('template');
				$this->template->title('Rules & Regulation - '.$this->config->item('company_name')); 
				
				//$data['datatables']     = TRUE;
 				$data['datepicker']     = TRUE;
                $data['page']           = 'rules';
                $data['role']           = $this->tank_auth->get_role_id();
				$data['rules']          = $this->db->query("select * from dgt_rules_regulation where status = 0 order by date_created  asc ")->result_array();
                $this->template
					 ->set_layout('users')
					 ->build('rules_tbl',isset($data) ? $data : NULL);
     }
	function add()
	{
		if ($this->input->post()) { 
			$det['content']       = $this->input->post('rule_description');
			$det['date_created']  = date('Y-m-d H:i:s');
			$this->db->insert('dgt_rules_regulation',$det);
			//$this->session->set_userdate('holyday_success','successs');
    		redirect('rules_regulation');
		}else{
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Rules & Regulation - '.$this->config->item('company_name')); 
 			//$data['datepicker']     = TRUE;
			$data['page']           = 'rules';
			$data['role']           = $this->tank_auth->get_role_id();
  			$this->template
			->set_layout('users')
			->build('create_rules',isset($data) ? $data : NULL);
 		}
		
	} 
	function edit()
	{
		if ($this->input->post()) { 
			$det['content']   = $this->input->post('rule_description');
			$this->db->update('dgt_rules_regulation',$det,array('id'=>$this->input->post('rule_tbl_id'))); 
    		redirect('rules_regulation');
		}else{
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Rules & Regulation - '.$this->config->item('company_name')); 
 			//$data['datepicker']     = TRUE;
			$data['page']           = 'rules';
			$data['role']           = $this->tank_auth->get_role_id();
			$data['rules_det']     = $this->db->query("select * from dgt_rules_regulation where id = ".$this->uri->segment(3)."")->result_array();
 			//print_r($data['holydays_det']);exit;
   			$this->template
			->set_layout('users')
			->build('edit_rules',isset($data) ? $data : NULL);
 		}
 	} 
	function delete()
	{
		if ($this->input->post()) {
			$det['status']        = 1; 
			$this->db->update('dgt_rules_regulation',$det,array('id'=>$this->input->post('rule_tbl_id'))); 
			redirect('rules_regulation');
 		}else{
			$data['rule_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete_rules',$data);
		}
	
	} 
}
