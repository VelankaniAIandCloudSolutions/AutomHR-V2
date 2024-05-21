<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crm_model extends CI_Model
{
	
	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	
	public function check_useremail($email)
	{
		return $this->db->get_where('dgt_users',array('email'=>$email))->num_rows();
	}

	public function check_username($username)
	{
		return $this->db->get_where('dgt_users',array('username'=>$username))->num_rows();
	}
	

	static function add_lead($data = array()) {
		self::$db->insert('business_proposals',$data);
		return self::$db->insert_id();
	}

	 static function lead_by_id($id = NULL)
    {
        return self::$db->where('id',$id)->get('business_proposals')->row();
    }
}

/* End of file model.php */