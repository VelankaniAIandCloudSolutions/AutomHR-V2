<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appraisal_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

		
	}


	function insert($table,$data){
		$this->db->insert($table,$data);
		return true;
	}
	function update($table,$data,$where){
		$this->db->update($table,$data,$where);
		//$this->db->where($where);
		return true;
	}

	function select($table,$where = NULL){
		$this->db->select('*');
		$this->db->from($table);
		if($where!=NULL){
		$this->db->where($where);
	    }
		$query = $this->db->get();
		return $query->result();
	}

	function get_indicatorname(){
		// $indicators = array(
		//     'technical' => array(
		//         1 => "Customer Experience",
		//         2 => "Marketing",
		//         3 => "Management",
		//         4 => "Administration",
		//         5 => "Presentation Skill",
		//         6 => "Quality Of Work",
		//         7 => "Efficiency"
		//     ),
		//     'organization' => array(
		//         8  => "Integrity",
		//         9  => "Professionalism",
		//         10 => "Team Work",
		//         11 => "Critical Thinking",
		//         12 => "Conflict Management",
		//         13 => "Attendance",
		//         14 => "Ability To Meet Deadline"
		//     )
		// );

		$indicators = array(
			'technical' => array('Quality Of Work' => array(
	        1 => "Accuracy, neatness and timeliness of work",
	        2 => "Adherence to duties and procedures in Job Description and Work Instructions",
	        3 => "Synchronization with organizations/functional goals"
	    ),
	    'Work Habits' => array(
	        1 => "Reliable, have a great attendance record, on time and provides additional cover when needed",
	        2 => "Attendance",
	        3 => "Team member stays busy, looks for things to do, takes initiatives at workplace",
	        4 => "All deadlines are met and work is accurate and reliable",
	        5 => "Supportive of change"
	    ),
	    'Job Knowledge' => array(
	        1 => "Skill and ability to perform job up to expectation",
	        2 => "Shows interest in learning and improving",
	        3 => "Has Problem solving ability",
	        4 => "Understands all elements of job role",
	        5 => "Expresses self clearly and professionally. Has open and honest conversations with manager and team."
	    ),
	    'Teamwork' => array(
	        1 => "Ability to work with peers and superiors",
	        2 => "Ability to work with other teams",
	        3 => "Able to support in crisis in own team or other teams."
	    ))

	    	
);

		return $indicators;
	}

	function select_indicators($where = NULL){
		$this->db->select('indicators.*,designation.designation');
		$this->db->from('indicators');
		$this->db->join('designation','designation.id=indicators.designation_id');
		if($where!=NULL){
		$this->db->where($where);
	    }
	    $this->db->order_by("id", "DESC");
		$query = $this->db->get();
		return $query->result();
	}

	function select_row($table,$where=NULL){
		$this->db->select('*');
		$this->db->from($table);
		if($where!=NULL){
		$this->db->where($where);
	    }
		$query = $this->db->get();
		return $query->row_array();
	}
	function select_appraisals($where=NULL, $branch_id = ''){

		$this->db->select('employee_appraisal.*,users.username,designation.designation,departments.deptname');
		$this->db->from('employee_appraisal');
		$this->db->join('users','users.id=employee_appraisal.employee_id');
		$this->db->join('designation','designation.id=users.designation_id');
		$this->db->join('departments','departments.deptid=users.department_id');
		if($where!=NULL){
			$this->db->where($where);
		}
		if($branch_id != "")
		{
			$this->db->join("account_details","account_details.user_id = users.id", "inner");
			$this->db->where("account_details.branch_id",$branch_id);
		}
		$this->db->order_by("id", "DESC");
		$query = $this->db->get();
		return $query->result();
	}

	function get_indicators_row($where){
		$this->db->select('indicators.*');
		$this->db->from('users');
		$this->db->join('indicators','indicators.designation_id = users.designation_id');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

	function select_appraisals_view($where=NULL){
		$this->db->select('employee_appraisal.*,users.username,designation.designation,departments.deptname,indicators.level');
		$this->db->from('employee_appraisal');
		$this->db->join('users','users.id=employee_appraisal.employee_id');
		$this->db->join('designation','designation.id=users.designation_id');
		$this->db->join('indicators','indicators.designation_id=users.designation_id','left');
		$this->db->join('departments','departments.deptid=users.department_id');
		if($where!=NULL){
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->row_array();
	}




}

/* End of file appraisal_model.php */
