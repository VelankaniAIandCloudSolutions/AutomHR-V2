<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shift_scheduling_model extends CI_Model
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

	static function get_employees_list($params,$count_or_records){

		if($count_or_records == 1){

			$page = $params['page'];
			$limit = $params['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);
		}
		self::$db->select('U.*,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id');
		self::$db->from('users U');
		self::$db->join('account_details AD','AD.user_id=U.id','LEFT');
		//self::$db->join('companies C','C.co_id=AD.company','LEFT');
		self::$db->join('designation D','D.id=U.designation_id','LEFT');
		self::$db->join('departments DE','DE.deptid=D.department_id','LEFT');

		self::$db->where('U.role_id',3);

		if(!empty($params['username'])){
			self::$db->like('AD.fullname', $params['username'], 'BOTH');
		}
		if(!empty($params['department_id'])){
			self::$db->like('D.department_id', $params['department_id'], 'BOTH');
		}
		if(!empty($params['employee_email'])){
			self::$db->like('U.email', $params['employee_email'], 'BOTH');
		}
		if(!empty($params['employee_id'])){
			$employee_id = str_replace('FT-00','',$params['employee_id']);
			self::$db->like('U.id', $employee_id, 'BOTH');
		}
		
		if($count_or_records == 1){
			self::$db->order_by('U.id', 'ASC');
			self::$db->limit($limit,$page);
		 	 return self::$db->get()->result();

		}if($count_or_records == 2){

			return self::$db->count_all_results();	
		}
		 
	}

	public function get_designations($id)
	{
		self::$db->select('id,designation');
		self::$db->where('department_id',$id);
		return self::$db->get('designation')->result();
	}

	public function get_department_and_designation($id)
	{
		self::$db->select('department_id');
		self::$db->where('id',$id);
		$record = self::$db->get('designation')->row();
		$list = array();
		$list['departmentid'] = $department_id =0;
		if(!empty($record)){
			$department_id = $record->department_id;
		}
		$list['designations'] = array();
		if($department_id !=0){
		$list['departmentid'] = $department_id;	
		self::$db->select('id,designation');
		self::$db->where('department_id',$department_id);
		$list['designations'] = self::$db->get('designation')->result();
		}
		return $list;
	}
	public function changedesignation($params)
	{
		$designation = $params['designation'];
		$userid = $params['userid'];
		self::$db->where('id',$userid);
		return self::$db->update('users',array('designation_id'=>$designation));
	}

	public function check_useremail($email)
	{
		return $this->db->get_where('dgt_users',array('email'=>$email))->num_rows();
	}

	public function check_username($username)
	{
		return $this->db->get_where('dgt_users',array('username'=>$username))->num_rows();
	}
	
	Public function get_employeedetailById($id)
	{
	   // return $this->db->get_where('dgt_users',array('id'=>$id))->row_array();
	   return $this->db->select('*')
	            ->from('dgt_users U')
	            ->join('dgt_account_details AD', 'U.id = AD.user_id')
	            ->where('U.id',$id)
	            ->get()->row_array();
	}

	public function get_employeepersonalById($id)
	{
		return $this->db->get_where('dgt_users_personal_details',array('user_id'=>$id))->row_array();
	}
	 
	public function check_shift_name($shift_name)
	{
		return $this->db->get_where('dgt_shifts',array('shift_name'=>$shift_name))->num_rows();
	}
	public function check_edit_shift_name($shift_name,$id)
	{
		return $this->db->get_where('dgt_shifts',array('shift_name'=>$shift_name,'id !='=>$id))->num_rows();
	}
}

/* End of file model.php */