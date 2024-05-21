<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Model
{
	
	function overdue_projects()
	{
		$this->db->join('companies','companies.co_id = projects.client');

		return $this->db->where(array('proj_deleted'=> 'No','alert_overdue' => '0','due_date'=> date("Y-m-d"),
						  				'progress <' => '100'))->get('projects')->result();
	}

	function overdue_invoices()
	{
		$invoices = array();
		$this->db->join('companies','companies.co_id = invoices.client');
		$invoices = $this->db->where(array('inv_deleted'=> 'No','alert_overdue' => '0','due_date'=> date('Y-m-d')
						  				))->get('invoices')->result();

		foreach ($invoices as $key => &$invoice) {
    	if(Invoice::payment_status($invoice->inv_id) == 'fully_paid'){
            unset($invoices[$key]);
        	}
		}
		return $invoices;

		
	}
	
	
}

/* End of file cron_model.php */