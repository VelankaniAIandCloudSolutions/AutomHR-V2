<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function recent_paid()
	{
		return self::$db->order_by('created_date', 'desc')->where('inv_deleted','No')->get('payments',15)->result();
	}
	// Get payment method name using ID
	static function method_name_by_id($id = NULL)
	{
		return self::$db->where('method_id',$id)->get('payment_methods')->row()->method_name;
	}

	// Get all payments
	static function all(){
		return self::$db->order_by('created_date','desc')->where('inv_deleted','No')->get('payments')->result();
	}
	static function all_limit(){
		$page = $_GET['start'];
		$limit = $_GET['length'];
		return self::$db->order_by('created_date','desc')->where('inv_deleted','No')->limit($limit,$page)->get('payments')->result();
	}
	// Get payments by client ID
	static function by_client($client = NULL){
		if($client > 0){
			return self::$db->order_by('created_date','desc')->where(array('paid_by'=>$client,'inv_deleted'=>'No'))->get('payments')->result();
		}else{
			return array();
		}
	}
	static function by_client_limit($client = NULL){
		$page = $_GET['start'];
		$limit = $_GET['length'];
		if($client > 0){
			if(isset($_GET['search']['value']) && !empty($_GET['search']['value'])){
				$cond_or_like = array();
				
				$cond_or_like['amount'] = $_GET['search']['value'];
				self::$db->or_like($cond_or_like);
			}
			return self::$db->order_by('created_date','desc')->where(array('paid_by'=>$client,'inv_deleted'=>'No'))->limit($limit,$page)->get('payments')->result();
		}else{
			return array();
		}
	}
	// Get payment information 
	static function view_by_id($id = NULL){
		return self::$db->where(array('p_id'=>$id))->get('payments')->row();
	}

	// Get client payments
	static function client_payments($company = NULL)
	{
		return self::$db->where('paid_by',$company)->get('payments')->result();
	}


	// Save payment to database
	static function save_pay($data){
		self::$db->insert('payments',$data);
		return self::$db->insert_id();
	}

	// Update payments table
	static function update_pay($id,$data){
		return self::$db->where('p_id',$id)->update('payments',$data);
	}

	// Delete payment from payments table
	static function delete($id){
		return self::$db->where('p_id',$id)->delete('payments');
	}

	// Get Invoice payments
	static function by_invoice($id){
		return self::$db->where('invoice',$id)->get('payments')->result();
	}

	static function by_range($start,$end,$branch_id = NULL){
		if($branch_id == 0){
        	$sql = "SELECT * FROM dgt_payments WHERE payment_date BETWEEN '$start' AND '$end' AND refunded = 'No'";
		}else{
        	$sql = "SELECT * FROM dgt_payments WHERE branch_id = '$branch_id' AND payment_date BETWEEN '$start' AND '$end' AND refunded = 'No'";
		}
        return self::$db->query($sql)->result();
    }
	

}

/* End of file model.php */// Get recently paid invoices