<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Annual_incentive_plans_model extends CI_Model
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
		self::$db->from('annual_incentive_plans'); 		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($plan_id)
	{
		return self::$db->where('id',$plan_id)->get('annual_incentive_plans')->row();
	}
	
	static function jobcard_exists($plan,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('plan',$plan)->where('id != ',$id)->get('annual_incentive_plans')->row();            
		}
		else 
		{
			return self::$db->where('plan',$plan)->get('annual_incentive_plans')->row();            
		}
		
	}

	static function delete($plan_id)
	{
		self::$db->where('id',$plan_id)->delete('annual_incentive_plans');
	}

	public function get_vocations()
    {
        return self::$db->select('*')->where(array('status'=>1))->get('annual_incentive_plans')->result_array();
    }

}