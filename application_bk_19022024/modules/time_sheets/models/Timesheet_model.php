<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Timesheet_model extends CI_Model
{
	
 	public function get_all_users_detail()
    {
		$role_name = User::login_role_name();
		
        $where = array('US.activated'=>1,'US.role_id'=>3,'AD.branch_id'=>$this->session->userdata('branch_id'));
        return $this->db->select('*')
                 ->from('dgt_users US')
                 ->join('dgt_account_details AD','US.id = AD.user_id')
                 ->join('dgt_designation DS','DS.department_id = US.department_id','left')
                 ->where($where)
                 ->get()->result_array();
    }

    public function get_all_users()
    {

        $where = array('US.activated'=>1,'US.role_id'=>3 /*,'branch_id'=>$this->session->userdata('branch_id')*/);
        return $this->db->select('*')
                 ->from('dgt_users US')
                 ->join('dgt_account_details AD','US.id = AD.user_id','left')
                 ->where($where)
                 ->get()->result_array();
    }


    public function get_all_user_projects($user_id)
    {
    	// $res = $this->db->get_where('projects',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
    	$res = $this->db->get_where('projects')->result_array();
		$r = array();
    	foreach ($res as $rr) {
    		$pro_arr = unserialize($rr['assign_to']);
    		// print_r($pro_arr);
    		if (in_array($user_id,$pro_arr)){
    			$r[] = $rr;
    		}
    	}
    	return $r;
    }
	 


	public function check_timesheetByDate($user_id,$timesheet_date)
	{
		return $this->db->get_where('dgt_timesheet',array('user_id'=>$user_id,'timeline_date' =>$timesheet_date))->result_array();
	}

	public function get_all_timesheets()
	{
		return $this->db->select('*')
				 ->from('dgt_timesheet TS')
				// ->join('dgt_account_details AD','TS.user_id = AD.user_id','left')
				// ->join('dgt_projects PS','TS.project_id = PS.project_id','left')
				 // ->where('AD.branch_id',$this->session->userdata('branch_id'))
				 ->get()->result_array();
	}


	public function get_all_timesheets_search($inputs,$user_id=null,$role_id=null)
	{
		$this->session->unset_userdata('search_employee');
		$this->session->unset_userdata('search_from_date');
		$this->session->unset_userdata('search_to_date');
		$this->db->select('*');
		$this->db->from('dgt_timesheet TS');
		//$this->db->join('dgt_account_details AD','TS.user_id = AD.user_id','left');
		//$this->db->join('dgt_projects PS','TS.project_id = PS.project_id','left');
		//$this->db->where('AD.branch_id',$this->session->userdata('branch_id'));
		if($user_id != null){
			//$this->db->where('TS.user_id',$user_id);
		}
		if($inputs['employee_id']!= ''){
			$this->session->unset_userdata('search_employee');
			$this->session->set_userdata('search_employee',$inputs['employee_id']);
			//$this->db->where('TS.user_id',$inputs['employee_id']);
		}
		if($inputs['search_from_date']!= ''){
			$this->session->unset_userdata('search_from_date');
			$this->session->set_userdata('search_from_date',$inputs['search_from_date']);
			$start_date = date("Y-m-d", strtotime($inputs['search_from_date']));
			$this->db->where('TS.timeline_date >=', $start_date);
		}
		if($inputs['search_to_date']!= ''){
			$this->session->unset_userdata('search_to_date');
			$this->session->set_userdata('search_to_date',$inputs['search_to_date']);
			$to_date = date("Y-m-d", strtotime($inputs['search_to_date']));
			$this->db->where('TS.timeline_date <=', $to_date);
		}
		return $this->db->get()->result_array();
	}

 
	public function get_user_timesheets($user_id)
	{
		return $this->db->select('*')
				 ->from('dgt_timesheet TS')
				// ->join('dgt_account_details AD','TS.user_id = AD.user_id','left')
				 //->join('dgt_projects PS','TS.project_id = PS.project_id','left')
				 //->where('TS.user_id',$user_id)
				 //->where('AD.branch_id',$this->session->userdata('branch_id'))
				 ->get()->result_array();
	}
	 
	
}

/* End of file Timesheet_model.php */