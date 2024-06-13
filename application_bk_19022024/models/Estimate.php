<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Estimate extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function estimate_by_client($company)
	{
		return self::$db->where('client',$company)->get('estimates')->result();
	}
	static function view_estimate($id)
	{
		return self::$db->where('est_id',$id)->get('estimates')->row();
	}
	static function view_item($id)
	{
		return self::$db->where('item_id',$id)->get('estimate_items')->row();
	}

	static function by_where($table,$array = NULL){
    	return self::$db->where($array)->get($table)->result();
	}
	static function by_where_limit($table,$array = NULL){
		$page = $_GET['start'];
		$limit = $_GET['length'];
		if(!empty($_GET['estimates_from'])){
			$dfrom = date('Y-m-d',strtotime($_GET['estimates_from']));
			$array['date_saved >=']      =   $dfrom;

		}
		if(!empty($_GET['estimates_to'])){
			$dto = date('Y-m-d',strtotime($_GET['estimates_to']));
			$array['due_date <=']      =   $dto;

		}
		if(!empty($_GET['estimates_status'])){
			$array['status']      =   $_GET['estimates_status'];

		}
    	$result = self::$db->where($array)->limit($limit,$page)->get($table)->result();
		return $result;
	}

	static function rates()
	{
		return self::$db->get('tax_rates')->result();
	}


	static function sub_total($estimate){
		$row = self::$db->select_sum('total_cost')->where('estimate_id',$estimate)->get('estimate_items')->row();
		return $row->total_cost;
	}

	static function total_tax($estimate,$type = 'tax1',$sum_tax = FALSE){
		if($sum_tax) return self::sum_tax($estimate);
        $tax = ($type == 'tax2') ? self::view_estimate($estimate)->tax2 : self::view_estimate($estimate)->tax;
        if($type == 'tax2') return ($tax/100) * self::sub_total($estimate);
		return ($tax / 100) * self::sub_total($estimate);
	}

	static function sum_tax($estimate){
        $tax1 = self::view_estimate($estimate)->tax;
        $tax2 = self::view_estimate($estimate)->tax2;
        return ($tax1/100) * self::sub_total($estimate) + ($tax2/100) * self::sub_total($estimate);
    }

	static function total_discount($estimate){
		$discount = self::view_estimate($estimate)->discount;
		return ($discount / 100) * self::sub_total($estimate);
	}

	static function due($estimate){
		$discount = self::total_discount($estimate);
		return round((self::sub_total($estimate) - $discount) + self::total_tax($estimate,NULL,TRUE),2);
	}

	// List items ordered
	static function has_items($id){
		return self::$db->where('estimate_id',$id)->order_by('item_order','asc')->get('estimate_items')->result();
	}

		// Generate new Invoice Number

	static function generate_estimate_number() {
		$dbPrefix = self::$db->dbprefix;
		$query = self::$db->query('SELECT reference_no, est_id FROM '.$dbPrefix.'estimates WHERE est_id = (SELECT MAX(est_id) FROM '.$dbPrefix.'estimates)');

		// $query = self::$db->select('reference_no')->select_max('est_id')->get('estimates');
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$ref_number = intval(substr($row->reference_no, -4));
			$next_number = $ref_number + 1;
			if ($next_number < config_item('estimate_start_no')) { $next_number = config_item('estimate_start_no'); }
			$next_number = self::ref_exists($next_number);
			return sprintf('%04d', $next_number);
		}else{
			return sprintf('%04d', config_item('estimate_start_no'));
		}
	}

	// Verify if REF Exists

	static function ref_exists($next_number){
		$next_number = sprintf('%04d', $next_number);

		$records = self::$db->where('reference_no',config_item('estimate_prefix').$next_number)
							->get('estimates')->num_rows();
		if ($records > 0) {
			return self::ref_exists($next_number + 1);
		}else{
			return $next_number;
		}
	}



}

/* End of file model.php */
