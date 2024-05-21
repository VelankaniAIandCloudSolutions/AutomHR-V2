<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Model
{
	
	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function recent_projects($user, $limit = 10)
	{   
		self::$db->join('assign_projects','assign_projects.project_assigned = projects.project_id');
		self::$db->where('assigned_user', $user);
		return self::$db->order_by('date_created','desc')->group_by('project_assigned')
					->get('projects',$limit)->result();
	}
	static function recent_tasks($user, $limit = 10)
	{
		self::$db->join('assign_tasks','assign_tasks.task_assigned = tasks.t_id');
		self::$db->where('assigned_user', $user);
		return self::$db->order_by('assign_date','desc')->group_by('task_assigned')
				->get('tasks',$limit)->result();
	}
	static function recent_activities($user, $limit = 10)
	{
		return self::$db->where('user',$user)->order_by('activity_date','DESC')->get('activities',$limit)->result();
	}
	
	function _assigned_projects($user){
		$query = $this->db->select('project_assigned')->where('assigned_user',$user)->get('assign_projects');
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
	}

	function search_payment($keyword)
	{
		$this->db->where('paid_by', Applib::profile_info($this->tank_auth->get_user_id())->company);
		return $this->db->like('trans_id', $keyword)->order_by("created_date","desc")->get(Applib::$payments_table)->result();
	}
	
}

/* End of file model.php */