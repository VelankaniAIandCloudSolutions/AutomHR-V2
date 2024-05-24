<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employees_details extends CI_Model
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

	static function get_employees_list($params,$count_or_records,$branch_id){
		$ci =& get_instance();
		$curUserid = $ci->session->userdata('user_id');
		$entities = $ci->db->select('branch_id')->from('dgt_assigned_entities')->where('user_id',$curUserid)->get()->result_array();

		//echo '<pre>';print_r($entities);exit;
		if($count_or_records == 1){

			$page = $params['start'];
			$limit = $params['length'];
			if($page>=1){
				//$page = $page - 1 ;
			}
			//$page =  ($page * $limit);
		}

		// self::$db->select('U.*,b.branch_name,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.employee_id IS NOT NULL,AD.employee_id,"-") AS employee_id,IF(AD.emp_code IS NOT NULL,AD.emp_code,"-") AS emp_code,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id');
		self::$db->select('U.activated,U.id,U.department_id,U.email,U.user_type,
		b.branch_name,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.employee_id IS NOT NULL,AD.employee_id,"-") AS employee_id,AD.emp_code AS emp_code,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id');
		self::$db->from('users U');
		self::$db->join('account_details AD','AD.user_id=U.id','LEFT');
		self::$db->join('dgt_branches b','b.branch_id=AD.branch_id','LEFT');
		
		if(!empty($entities)){

			$entities = array_column($entities, 'branch_id');
			self::$db->where_in('AD.branch_id', $entities);
		}
		//self::$db->join('companies C','C.co_id=AD.company','LEFT');
		self::$db->join('designation D','D.id=U.designation_id','LEFT');
		self::$db->join('departments DE','DE.deptid=U.department_id','LEFT');

		//self::$db->where('U.role_id',3);
		
		if($ci->session->userdata('role_id')!=1)
		{
			//self::$db->where('AD.branch_id',$branch_id);
			if(!empty($branch_id)){
				self::$db->where_in('AD.branch_id',$branch_id);
			}
		}
		
		if(!empty($params['username'])){
			self::$db->like('AD.fullname', $params['username'], 'BOTH');
		}
		if(!empty($params['department_id'])){
			self::$db->like('D.department_id', $params['department_id'], 'BOTH');
		}
		if(!empty($params['employee_email'])){
			self::$db->like('U.email', $params['employee_email'], 'BOTH');
		}
		
		if(!empty($params['entity_id'])){
			self::$db->where_in('AD.branch_id',$branch_id);
		}

		// if(!empty($params['employee_id'])){
		// 	$employee_id = str_replace('FT-00','',$params['employee_id']);
		// 	self::$db->like('U.id', $employee_id, 'BOTH');
		// }

		if(!empty($params['employee_id'])) {
			self::$db->like('AD.emp_code', $params['employee_id'], 'BOTH');
		}
		if(isset($_GET['search']['value']) && !empty($_GET['search']['value'])){
			$cond_or_like = array();
			if($_GET['search']['value']=='active' || $_GET['search']['value']=='ACTIVE' || $_GET['search']['value']=='Active'){
				$cond_or_like['U.activated'] = 1;
			}
			if($_GET['search']['value']=='inactive' || $_GET['search']['value']=='INACTIVE' || $_GET['search']['value']=='Inactive' || $_GET['search']['value']=='InActive'){
				$cond_or_like['U.activated'] = 2;
			}
			$cond_or_like['AD.fullname'] = $_GET['search']['value'];
			$cond_or_like['D.department_id'] = $_GET['search']['value'];
			$cond_or_like['U.email'] = $_GET['search']['value'];
			$cond_or_like['b.branch_name'] = $_GET['search']['value'];
			self::$db->or_like($cond_or_like);
		}
		if($count_or_records == 1){
			
			if(isset($_GET['order'][0]['column']) && $_GET['order'][0]['column']!=1 && $_GET['order'][0]['column']!=9 && !empty($_GET['columns'][$_GET['order'][0]['column']]['data'])){
				$search = ($_GET['columns'][$_GET['order'][0]['column']]['data']!='entity_name')?$_GET['columns'][$_GET['order'][0]['column']]['data']:'branch_name';
				if($search=='user_status'){
					$search = 'activated';
				}
				self::$db->order_by($search, $_GET['order'][0]['dir']);
			}
			else{
				self::$db->order_by('U.id', 'ASC');
			}
			//self::$db->order_by('U.id', 'ASC');
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

	public function employeeIdByEmpCode($emp_code = '')
	{
		if($emp_code != "")
		{	
			$this->db->select("user_id");
			return $this->db->get_where('dgt_account_details',array('emp_code'=>$emp_code))->row_array();
		}
	}	
}

/* End of file model.php */