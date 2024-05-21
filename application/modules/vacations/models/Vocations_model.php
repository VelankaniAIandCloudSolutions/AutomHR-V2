<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Vocations_model extends CI_Model
{
    private static $db;

    function __construct()
    {
		parent::__construct();
		self::$db = &get_instance()->db;
	}

    static function all()
	{	
		self::$db->select('*');
		self::$db->from('vocations'); 		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($vocation_id)
	{
		return self::$db->where('id',$vocation_id)->get('vocations')->row();
	}
	
	static function jobcard_exists($vocation,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('vocation',$vocation)->where('id != ',$id)->get('vocations')->row();            
		}
		else 
		{
			return self::$db->where('vocation',$vocation)->get('vocations')->row();            
		}
		
	}

	static function delete($vocation_id)
	{
		self::$db->where('id',$vocation_id)->delete('vocations');
	}

	public function get_vocations()
    {
        return self::$db->select('*')->where(array('status'=>1))->get('vocations')->result_array();
    }
	function update($table,$data,$where){
		$this->db->update($table,$data,$where);
		//$this->db->where($where);
		return true;
	}
}