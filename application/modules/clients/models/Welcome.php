<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Model
{


	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}


	static function recent_activities($user,$limit = 6)
	{
		
		self::$db->join('users','users.id = activities.user');
		return self::$db->where('user',$user)->order_by('activity_date','DESC')->get('activities',$limit)->result();
	}

	static function recent_projects($company,$limit = 5)
	{
		return self::$db->where(array('client'=>$company,'proj_deleted'=>'No'))->order_by('date_created','DESC')->get('projects',$limit)->result();
	}
	
}

/* End of file model.php */