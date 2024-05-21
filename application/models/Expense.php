<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Expense extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	// Calculate client expenses
 	static function total_by_client($client = NULL)
		{
			return self::$db->select_sum('amount')
							->where(array('billable' => '1','invoiced' => '0','client' => $client))
							->get('expenses')
							->row()->amount;
		}

	// Get client expenses
	static function billable_by_client($company)
	{
		self::$db->where(array('invoiced' => '0','billable' => '1','client' => $company,'show_client' => 'Yes'));
		return self::$db->get('expenses')->result();
	}

	// Get expense information
	static function view_by_id($id){
		return self::$db->where('id',$id)->get('expenses')->row();
	}
	// Update Expense
	static function update($id,$data){
		return self::$db->where('id',$id)->update('expenses',$data);
	}
	// Get all client expenses
	static function expenses_by_client($company, $report_by = NULL,$branch_id = NULL){
		if(($branch_id == '0') || ($branch_id == NULL)){
			switch ($report_by) {
				case 'billable':
					return self::$db->where(array('client'=>$company,'billable' => '1'))->get('expenses')->result();
					break;
				case 'unbillable':
					return self::$db->where(array('client'=>$company,'billable' => '0'))->get('expenses')->result();
					break;
				case 'billed':
					return self::$db->where(array('client'=>$company,'invoiced' => '1'))->get('expenses')->result();
					break;
				default:
					return self::$db->where('client',$company)->get('expenses')->result();
					break;
			}
		}else{

			switch ($report_by) {
				case 'billable':
					return self::$db->where(array('client'=>$company,'billable' => '1','branch_id'=>$branch_id))->get('expenses')->result();
					break;
				case 'unbillable':
					return self::$db->where(array('client'=>$company,'billable' => '0','branch_id'=>$branch_id))->get('expenses')->result();
					break;
				case 'billed':
					return self::$db->where(array('client'=>$company,'invoiced' => '1','branch_id'=>$branch_id))->get('expenses')->result();
					break;
				default:
					return self::$db->where(array('client'=>$company,'branch_id'=>$branch_id))->get('expenses')->result();
					break;
			}
		}
		
	}

	 static function by_range($start,$end,$branch_id = NULL,$report_by = NULL){
	 	if(($branch_id == '0') || ($branch_id == NULL)){
		 	switch ($report_by) {
		 		case 'billable':
		 			$sql = "SELECT * FROM dgt_expenses WHERE billable = '1' AND expense_date BETWEEN '$start' AND '$end'";
		 			break;
		 		case 'unbillable':
		 			$sql = "SELECT * FROM dgt_expenses WHERE billable = '0' AND expense_date BETWEEN '$start' AND '$end'";
		 			break;
		 		case 'billed':
		 			$sql = "SELECT * FROM dgt_expenses WHERE invoiced = '1' AND expense_date BETWEEN '$start' AND '$end'";
		 			break;
		 		
		 		default:
		 			$sql = "SELECT * FROM dgt_expenses WHERE expense_date BETWEEN '$start' AND '$end'";
		 			break;
		 	}
	 	}else{

	 	switch ($report_by) {
	 		case 'billable':
	 			$sql = "SELECT * FROM dgt_expenses WHERE branch_id = '$branch_id' AND billable = '1' AND expense_date BETWEEN '$start' AND '$end'";
	 			break;
	 		case 'unbillable':
	 			$sql = "SELECT * FROM dgt_expenses WHERE branch_id = '$branch_id' AND billable = '0' AND expense_date BETWEEN '$start' AND '$end'";
	 			break;
	 		case 'billed':
	 			$sql = "SELECT * FROM dgt_expenses WHERE  branch_id = '$branch_id' AND invoiced = '1' AND expense_date BETWEEN '$start' AND '$end'";
	 			break;
	 		
	 		default:
	 			$sql = "SELECT * FROM dgt_expenses WHERE branch_id = '$branch_id' AND expense_date BETWEEN '$start' AND '$end'";
	 			break;
	 	}
	 	}
        
        return self::$db->query($sql)->result();
    }

    static function total_expenses(){
    	$total = 0;
    	$expenses = self::$db->where('billable',1)->get('expenses')->result();
    	foreach ($expenses as $key => $e) {
    		if(Client::view_by_id($e->client)->currency != config_item('default_currency')){
    			$total += Applib::convert_currency(Client::view_by_id($e->client)->currency,$e->amount);
    		}else{
    			$total += $e->amount;
    		}
    	}
    	return $total;
    }

}

/* End of file model.php */