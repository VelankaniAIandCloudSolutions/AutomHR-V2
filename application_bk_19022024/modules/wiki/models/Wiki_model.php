<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Wiki_model extends CI_Model
{
    private static $db;

    function __construct()
    {
		parent::__construct();
		self::$db = &get_instance()->db;
	}

    static function all()
	{	
		$ci =& get_instance();
		$role_name = User::login_role_name();
		if($role_name!='admin' && $ci->session->userdata('branch_id') == 0){
			$branches = $ci->db->select('*')->from('assigned_entities')->where('user_id',$ci->session->userdata('user_id'))->get()->result_array();
			$branches = array_column($branches, 'branch_id');
		}
		self::$db->select('*');
		self::$db->from('wiki'); 
		if($role_name!='admin'){

			if($ci->session->userdata('branch_id') == 0 || $ci->session->userdata('user_type_name') =='company_admin'){
				if(!empty($branches)){
					$ci->db->where_in('branch_id',$branches);
				}
			}
			else{
				self::$db->where('branch_id',$ci->session->userdata('branch_id'));		
			}
		}		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($wiki_id)
	{
		return self::$db->where('id',$wiki_id)->get('wiki')->row();
	}
	
	static function wiki_exists($title,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('title',$title)->where('id != ',$id)->get('wiki')->row();            
		}
		else 
		{
			return self::$db->where('title',$title)->get('wiki')->row();            
		}
		
	}

	static function delete($wiki)
	{
		self::$db->where('id',$wiki)->delete('wiki');
	}

}