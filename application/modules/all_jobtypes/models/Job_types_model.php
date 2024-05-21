<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Job_types_model extends CI_Model
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
		self::$db->from('jobtypes'); 		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($jobtype_id)
	{
		return self::$db->where('id',$jobtype_id)->get('jobtypes')->row();
	}
	
	static function jobcard_exists($jobtype,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('job_type',$jobtype)->where('id != ',$id)->get('jobtypes')->row();            
		}
		else 
		{
			return self::$db->where('job_type',$jobtype)->get('jobtypes')->row();            
		}
		
	}

	static function delete($jobtype)
	{
		self::$db->where('id',$jobtype)->delete('jobtypes');
	}

}