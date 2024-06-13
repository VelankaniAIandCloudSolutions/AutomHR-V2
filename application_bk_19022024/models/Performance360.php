<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Performance360 extends CI_Model
{

	private static $db;


	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	/**
	* Insert records to performance_360 table and return INSERT ID
	*/
	static function save_golas($data = array()) {
		self::$db->insert('performance_360',$data);
		return self::$db -> insert_id();
	}

	/**
	* Update performance_360 information
	*/
	static function update_golas($id, $data = array()) {
		self::$db->where('id',$id)->update('performance_360',$data);
		return self::$db->affected_rows();
	}

	static function update_competencies($competencies, $data = array()) {
		self::$db->where('competencies',$competencies)->update('competencies',$data);
		return self::$db->affected_rows();
	}

	// Get 360_performance
	static function get_360_performance($user_id)
	{
		return self::$db->where('user_id',$user_id)->order_by('id','ASC')->get('performance_360')->result_array();
	}
	static function get_competencies($user_id)
	{
		return self::$db->where('user_id',$user_id)->order_by('id','ASC')->get('competencies')->result_array();
	}


	static function get_360_performance_manager($user_id)
	{
		return self::$db->where('teamlead_id',$user_id)->group_by('user_id')->order_by('id','ASC')->get('performance_360')->result_array();
	}
	// static function get_competencies_manager($user_id)
	// {
	// 	return self::$db->where('teamlead_id',$user_id)->order_by('id','ASC')->get('competencies')->result_array();
	// }


	static function delete_competencies($user_id)
	{
		self::$db->where('user_id',$user_id)->delete('competencies');
	}

	static function save_competencies($data = array()) {
		self::$db->insert('competencies',$data);
		return self::$db -> insert_id();
	}
	static function save_feedback($data = array()) {
		self::$db->insert('three_sixty_feedback',$data);
		return self::$db -> insert_id();
	}
	static function save_competencies_feedback($data = array()) {
		self::$db->insert('competencies_feedback',$data);
		return self::$db -> insert_id();
	}
	// Get all departments
	static function get_all_departments()
	{
		return self::$db->order_by('depthidden','ASC')->get('departments')->result();
	}


	static function due_amount($company)
	{
		$due = 0;
		$cur = self::view_by_id($company)->currency;
		$invoices = self::$db->where(array('client'=>$company,'status !='=>'Cancelled'))->get('invoices')->result();
		foreach ($invoices as $key => $invoice) {
			if($invoice->currency != $cur){
					$due += Applib::convert_currency($cur,Invoice::get_invoice_due_amount($invoice->inv_id));
			}else{
				$due += Invoice::get_invoice_due_amount($invoice->inv_id);
			}
		}
			return $due;
	}

	// Get all client files
    static function has_files($id)
    {
        return self::$db->where('client_id',$id)->get('files')->result();
    }

	static function get_client_contacts($company)
	{
		self::$db->join('companies','companies.co_id = account_details.company');
		self::$db->join('users','users.id = account_details.user_id');
		return self::$db->where('company',$company)->get('account_details')->result();
	}


	static function payable($company){
		$total = 0;
		$invoices = Invoice::get_client_invoices($company);
		foreach ($invoices as $key => $inv) {
			if($inv->currency != config_item('default_currency')){
				$total += Applib::convert_currency($inv->currency, Invoice::payable($inv->inv_id));
			}else{
				$total += Invoice::payable($inv->inv_id);
			}
		}
		return $total;
	}

	static function view_by_id($company)
	{
		return self::$db->where('co_id',$company)->get('companies')->row();
	}

	static function custom_fields($client){
		return self::$db->where(array('module'=>'clients','client_id'=>$client))->get('formmeta')->result();
	}

	// Get client currency
	static function client_currency($company = FALSE)
	{
		if (!$company) { return FALSE; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		$client = self::$db->where('co_id', $company)->get('companies')->result();
		if (count($client) == 0) { return $dcurrency[0]; }
		$currency = self::$db->where('code',$client[0]->currency)->get('currencies')->result();
		if (count($currency) > 0) { return $currency[0]; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		if (count($dcurrency) > 0) { return $dcurrency[0]; }

	}
	// Get client language
	static function client_language($id = FALSE)
	{
		if (!$id) { return FALSE; }
		$language = self::$db->where('name',self::view_by_id($id)->language)->get('languages')->result();
		return $language[0];
	}

	// Amount paid by client
	static function amount_paid($company)
	{
		$total = 0;
		if($company > 0){
		$payments = self::$db->where(array('paid_by'=>$company,'refunded'=>'No'))->get('payments')->result();
		foreach ($payments as $key => $pay) {
			if($pay->currency != config_item('default_currency')){
				$total += Applib::convert_currency($pay->currency,$pay->amount);
			}else{
				$total += $pay->amount;
			}
		}
	}
		return $total;
	}

	// Get Client Currency
	static function get_currency_code($company = FALSE)
	{
		if (!$company) { return FALSE; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		$client = self::$db->where('co_id', $company)->get('companies')->result();
		if (count($client) == 0) { return $dcurrency[0]; }
		$currency = self::$db->where('code',$client[0]->currency)->get('currencies')->result();
		if (count($currency) > 0) { return $currency[0]; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		if (count($dcurrency) > 0) { return $dcurrency[0]; }

	}
	// Get client expenses
	static function total_expenses($company = NULL)
		{
			return self::$db->select_sum('amount')
							->where(array('billable' => '1','invoiced' => '0','client' => $company))
							->get('expenses')
							->row()->amount;
		}

		static function month_amount($year, $month, $client){
	        $total = 0;
	        $query = "SELECT * FROM dgt_payments WHERE paid_by = '$client' AND MONTH(payment_date) = '$month' AND refunded = 'No' AND YEAR(payment_date) = '$year'";
	        $payments = self::$db->query($query)->result();
	        foreach($payments as $p) {
	            $amount = $p->amount;
	            if ($p->currency != config_item('default_currency')) {
	                $amount = Applib::convert_currency($p->currency, $amount);
	            }
	            $total += $amount;
	        }
	        return round($total, config_item('currency_decimals'));
	    }

		// Get all client comments
	    static function has_comments($id)
	    {
	        return self::$db->where(array('client_id'=>$id))->order_by('date_posted','desc')->get('comments')->result();
	    }
	    // Get all comment replies
	    static function has_replies($comment)
	    {
	        return self::$db->where('parent_comment',$comment)->get('comment_replies')->result();
	    }

	// Deletes Client from the database
	static function delete($company)
	{

	$company_invoices 	= Invoice::get_client_invoices($company);
	$company_estimates 	= Estimate::estimate_by_client($company);
	$company_expenses 	= Expense::expenses_by_client($company);
	$company_projects 	= Project::by_client($company);
	$company_contacts 	= self::get_client_contacts($company);

			if (count($company_invoices)) {
				foreach ($company_invoices as $invoice) {
					//delete invoice items
					self::$db->where('invoice_id',$invoice->inv_id)->delete('items');
				}
			}

			if (count($company_estimates)) {
				foreach ($company_estimates as $estimate) {
					//delete estimate items
					self::$db->where('estimate_id',$estimate->est_id)->delete('estimate_items');
				}
			}

			if (count($company_projects)) {
				foreach ($company_projects as $project) {
					// remove client from projects
					self::$db->set('client','0')->where('client',$company)->update('projects');
				}
			}

			if (count($company_expenses)) {
				foreach ($company_expenses as $expense) {
					//set client to blank in expenses
					self::$db->where('client',$company)->delete('expenses');
				}
			}

			//delete invoices
			self::$db->where('client',$company)->delete('invoices');
			//delete estimates
			self::$db->where('client',$company)->delete('estimates');

			// delete client payments
			self::$db->where('paid_by',$company)->delete('payments');
			//clear client activities
			self::$db->where(array('module'=>'Clients', 'module_field_id' => $company))->delete('activities');
			//delete company
			self::$db->where('co_id',$company)->delete('companies');


			if (count($company_contacts)) {
				foreach ($company_contacts as $contact) {
					//set contacts to blank
					self::$db->set('company','-')->where('company',$company)->update('account_details');
				}
			}

	}

}

/* End of file model.php */
