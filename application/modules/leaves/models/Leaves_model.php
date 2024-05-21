<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Leaves_model extends CI_Model
{
	
 	public function check_leavesById($user_id)
 	{
 		return $this->db->get_where('dgt_user_leaves',array('user_id'=>$user_id,'status'=>1))->result_array();
 	}

 	public function check_leavesBycat($user_id,$cat_id)
 	{
 		return $this->db->get_where('dgt_user_leaves',array('user_id'=>$user_id,'leave_type'=>$cat_id,'status'=>1))->result_array();
 	}
	
}

/* End of file Timesheet_model.php */