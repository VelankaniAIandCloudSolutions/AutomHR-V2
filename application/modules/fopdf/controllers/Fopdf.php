<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fopdf extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();
		
		$this->load->model(array('Client','Estimate','Invoice','App'));
		$this->load->helper('invoicer');		
	
		$this->applib->set_locale();
		
	}

	function invoice($invoice_id = NULL){			
			$data['id'] = $invoice_id;
			$this->load->view('invoice_pdf',isset($data) ? $data : NULL);				
	}
	function estimate($estimate = NULL){
			$data['id'] = $estimate;
			$this->load->view('estimate_pdf',isset($data) ? $data : NULL);	
	}

	function attach_invoice($invoice){			
			$data['id'] = $invoice['inv_id'];
			$data['attach'] = TRUE;
			$invoice = $this->load->view('invoice_pdf',isset($data) ? $data : NULL,TRUE);	
			return $invoice;			
	}
	function attach_estimate($estimate){
			$data['attach'] = TRUE;			
			$data['id'] = $estimate['est_id'];
			$est = $this->load->view('estimate_pdf',isset($data) ? $data : NULL,TRUE);	
			return $est;			
	}

	function payslip_pdf(){

		$data['attach'] = TRUE;	



		$form_data = array();

		if ($this->input->post('payslip_user_id')) {

			$form_data['user_id']           = $this->input->post('payslip_user_id');

			$form_data['year']              = $this->input->post('payslip_year');

			$form_data['month']             = $this->input->post('payslip_month');

			$form_data['payslipid']  = $this->input->post('payslipid');

			

		 } 

		$data['form_data'] = $form_data;	  



	 $est = $this->load->view('payslip',isset($data) ? $data : NULL,TRUE);	

		return $est;			

	}

}

/* End of file fopdf.php */