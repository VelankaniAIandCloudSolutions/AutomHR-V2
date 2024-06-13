<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Notice_board_model extends CI_Model
{
    private static $db;

    function __construct()
    {
		parent::__construct();
		self::$db = &get_instance()->db;
	}

    static function all()
	{	
		$role_name = User::login_role_name();
		$ci =& get_instance();
		if($role_name!='admin' && $ci->session->userdata('user_type_name') =='company_admin'){
			$branches = $ci->db->select('*')->from('assigned_entities')->where('user_id',$ci->session->userdata('user_id'))->get()->result_array();
			$branches = array_column($branches, 'branch_id');
		}
		self::$db->select('*');
		self::$db->from('notice_board'); 
		if($role_name!='admin'){
			if($ci->session->userdata('branch_id') == 0){
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
    
    static function find($notice_board_id)
	{
		return self::$db->where('id',$notice_board_id)->get('notice_board')->row();
	}
	
	static function notice_board_exists($title,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('title',$title)->where('id != ',$id)->get('notice_board')->row();            
		}
		else 
		{
			return self::$db->where('title',$title)->get('notice_board')->row();            
		}
		
	}

	static function delete($notice_board_id)
	{
		self::$db->where('id',$notice_board_id)->delete('notice_board');
	}

}