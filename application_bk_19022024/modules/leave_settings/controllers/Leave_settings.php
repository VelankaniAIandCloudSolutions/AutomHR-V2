<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Leave_settings extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App'));
        App::module_access('menu_leaves');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Leaves'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = 'leaves';
                $data['role']       = $this->tank_auth->get_role_id();
                $data['all_employees']       = App::GetAllEmployees();
                $this->template
					 ->set_layout('users')
					 ->build('leave_settings',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }



     function leave_types(){
        if ($this->input->post()) {
            
            $tbl_id               = $this->input->post('leave_type_tbl_id');
            $det['leave_type']    = $this->input->post('leave_type');
            $det['leave_days']    = $this->input->post('leave_days'); 
            if($tbl_id == ''){
                $this->db->insert('dgt_leave_types',$det);
                $this->session->set_flashdata('tokbox_success', 'Leave Type Added Successfully');
            }else{ 
                $this->db->update('dgt_leave_types',$det,array('id'=>$tbl_id));
            $this->session->set_flashdata('tokbox_success', 'Leave Type Update Successfully');
            } 
            redirect('leave_settings');
        }
    }
    function delete_leave_types(){
        if ($this->input->post()) {
            $det['status']   = 1;
            $this->db->update('dgt_leave_types',$det,array('id'=>$this->input->post('leave_type_id'))); 
            $this->session->set_flashdata('tokbox_error', 'Leave Type Deleted Successfully');
            redirect('leave_settings');
        }else{
            $data['leave_type_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_leave_type',$data);
        } 
    }
    
    function update_annual_leaves()
    {
        $annual_leaves = $this->input->post('annual_leaves');
        $res =array(
            'branch_id' => $this->session->userdata('branch_id'),
            'leave_days' => $annual_leaves
            );
        $this->db->where('leave_id',1);
        $this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		
		$exists = $this->db->where('leave_id',1)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>1,'leave_days' => $annual_leaves));
			// echo $this->db->last_query();exit;
		}

        $cr_yr = date('Y');
        $check_leaves = $this->db->get_where('yearly_leaves',array("branch_id"=>$this->session->userdata('branch_id'),'years'=>$cr_yr))->row_array();

        $anu_leaves = $this->db->get_where('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id'=>'1','status'=>'0'))->row_array();
        $cr_leaves = $this->db->get_where('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id'=>'2','status'=>'0'))->row_array();
        if(count($cr_leaves) != 0){
            $cry_leaves = $cr_leaves['leave_days'];
        }else{
            $cry_leaves = 0;
        }

        $leave = array(
            'annual_leaves' => $anu_leaves['leave_days'],
            'cr_leaves' => $cry_leaves,
        );

        if(count($check_leaves) != 0)
        {
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $this->db->where('years',$cr_yr);
            $this->db->where('branch_id',$this->session->userdata('branch_id'));
            $this->db->update('yearly_leaves',$total_leaves);
        }else{
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $total_leaves['years'] = $cr_yr;
            $total_leaves['branch_id'] = $this->session->userdata('branch_id');
            $this->db->insert('yearly_leaves',$total_leaves);
        }
        echo $annual_leaves; exit;
    }
	
	function update_annual_leaves_bybranch()
    {
        $annual_leaves = $this->input->post('annual_leaves');
        $branch_id = $this->input->post('branch_id');
        $res =array(
            // 'branch_id' => $this->session->userdata('branch_id'),
            'branch_id' => $branch_id,
            'leave_days' => $annual_leaves
            );
        // $this->db->where('leave_id',1);
        $this->db->where('leave_type_id',1);
        $this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		
		$exists = $this->db->where('leave_type_id',1)->where('branch_id', $branch_id)->get('common_leave_types');

		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id'=>1,'leave_type'=>'Annual Leaves','leave_days' => $annual_leaves));
			// echo $this->db->last_query();exit;
		}

        $cr_yr = date('Y');
        $check_leaves = $this->db->get_where('yearly_leaves',array("branch_id"=>$branch_id,'years'=>$cr_yr))->row_array();

        $anu_leaves = $this->db->get_where('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id'=>'1','status'=>'0'))->row_array();
        $cr_leaves = $this->db->get_where('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id'=>'2','status'=>'0'))->row_array();
        if(count($cr_leaves) != 0){
            $cry_leaves = $cr_leaves['leave_days'];
        }else{
            $cry_leaves = 0;
        }

        $leave = array(
            'annual_leaves' => $anu_leaves['leave_days'],
            'cr_leaves' => $cry_leaves,
        );

        if(count($check_leaves) != 0)
        {
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $this->db->where('years',$cr_yr);
            $this->db->where('branch_id',$branch_id);
            $this->db->update('yearly_leaves',$total_leaves);
        }else{
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $total_leaves['years'] = $cr_yr;
            $total_leaves['branch_id'] = $branch_id;
            $this->db->insert('yearly_leaves',$total_leaves);
        }
        echo $annual_leaves; exit;
    }
    
    function update_sick_leave()
    {
        $sick_leave = $this->input->post('sick_leave');
        $res =array(
            'leave_days' => $sick_leave,
            'branch_id' => $this->session->userdata('branch_id')
            );
        $this->db->where('leave_id',4);
		$this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		
		$exists = $this->db->where('leave_id',4)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>4,'leave_days' => $sick_leave));
			// echo $this->db->last_query();exit;
		}
		
        echo $sick_leave; exit;
    }
    
	function update_sick_leave_bybranch()
    {
        $branch_id = $this->input->post('branch_id');
        $sick_leave = $this->input->post('sick_leave');
        $res =array(
            'leave_days' => $sick_leave,
            'leave_type'=>'Sick',
            'branch_id' => $branch_id
            );
        // $this->db->where('leave_id',4);
        $this->db->where('leave_type_id',4);
		$this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		
		$exists = $this->db->where('leave_type_id',4)->where('branch_id', $branch_id)->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id' =>4,'leave_type'=>'Sick','leave_days' => $sick_leave));
			// echo $this->db->last_query();exit;
		}
		
        echo $sick_leave; exit;
    }
	
	
    function update_hospitalisation_leave()
    {
        $hospitalisation = $this->input->post('hospitalisation');
        $res =array(
            'leave_days' => $hospitalisation,
			'branch_id' => $this->session->userdata('branch_id')
            );
        $this->db->where('leave_id',5);
		$this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_id',5)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>5,'leave_days' => $hospitalisation));
			// echo $this->db->last_query();exit;
		}
        echo $hospitalisation; exit;
    }
	
	function update_hospitalisation_leave_bybranch()
    {
        $branch_id = $this->input->post('branch_id');
        $hospitalisation = $this->input->post('hospitalisation');
        $res =array(
            'leave_days' => $hospitalisation,
			'leave_type'=>'Hospitalisation',
			'branch_id' => $branch_id
            );
        // $this->db->where('leave_id',5);
        $this->db->where('leave_type_id',5);
		$this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_type_id',5)->where('branch_id', $branch_id)->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id' =>5,'leave_type'=>'Hospitalisation','leave_days' => $hospitalisation));
			// echo $this->db->last_query();exit;
		}
        echo $hospitalisation; exit;
    }
    
    function update_maternity_leave()
    {
        $maternity = $this->input->post('maternity');
        $res =array(
            'leave_days' => $maternity,
			'branch_id' => $this->session->userdata('branch_id')
            );
        $this->db->where('leave_id',6);
		$this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_id',6)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>6,'leave_days' => $maternity));
			// echo $this->db->last_query();exit;
		}
        echo $maternity; exit;
    }
	
	function update_maternity_leave_bybranch()
    {
        $branch_id = $this->input->post('branch_id');
        $maternity = $this->input->post('maternity');
        $res =array(
            'leave_days' => $maternity,
			'leave_type'=>'Maternity',
			'branch_id' => $branch_id
            );
        // $this->db->where('leave_id',6);
        $this->db->where('leave_type_id',6);
		$this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_type_id',6)->where('branch_id', $branch_id)->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type'=>'Maternity','leave_type_id' =>6,'leave_days' => $maternity));
			// echo $this->db->last_query();exit;
		}
        echo $maternity; exit;
    }
	
	
    
    function update_paternity_leave()
    {
        $paternity = $this->input->post('paternity');
        $res =array(
            'leave_days' => $paternity,
			'branch_id' => $this->session->userdata('branch_id')
            );
        $this->db->where('leave_id',7);
		$this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_id',7)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>7,'leave_days' => $paternity));
			// echo $this->db->last_query();exit;
		}
        echo $paternity; exit;
    }
	
	 function update_paternity_leave_bybranch()
    {
		$branch_id = $this->input->post('branch_id');
        $paternity = $this->input->post('paternity');
        $res =array(
            'leave_days' => $paternity,
			'leave_type'=>'Paternity',
			'branch_id' => $branch_id
            );
        // $this->db->where('leave_id',7);
        $this->db->where('leave_type_id',7);
		$this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_type_id',7)->where('branch_id', $branch_id)->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type'=>'Paternity','leave_type_id' =>7,'leave_days' => $paternity));
			// echo $this->db->last_query();exit;
		}
        echo $paternity; exit;
    }
    
	
	function update_extra_leaves_bybranch()
    {
		$branch_id = $this->input->post('branch_id');
        $paternity = $this->input->post('extra');
        $leave_type = $this->input->post('leave_type');
        $leave_type_id = $this->input->post('leave_type_id');
        $res =array(
            'leave_days' => $paternity,
			'leave_type'=>$leave_type,
			'branch_id' => $branch_id
            );
        // $this->db->where('leave_id',7);
        $this->db->where('leave_type_id',$leave_type_id);
		$this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_type_id',$leave_type_id)->where('branch_id', $branch_id)->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type'=>$leave_type,'leave_type_id' =>$leave_type_id,'leave_days' => $paternity));
			// echo $this->db->last_query();exit;
		}
        echo $paternity; exit;
    }
	
	
    function update_carry_forward_leave()
    {
        $carry_max = $this->input->post('carry_max');
        $leave_status = $this->input->post('leave_status');
        $res =array(
            'branch_id' => $this->session->userdata('branch_id'),
            'leave_days' => $carry_max,
            'leave_status' => $leave_status
            );
        $this->db->where('leave_id',2);
		$this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_id',2)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>2,'leave_days' => $carry_max));
			// echo $this->db->last_query();exit;
		}
		
        $check_leaves = $this->db->get_where('yearly_leaves',array('branch_id'=>$this->session->userdata('branch_id'),'years'=>$cr_yr))->row_array();
        $anu_leaves = $this->db->get_where('common_leave_types',array('branch_id'=>$this->session->userdata('branch_id'),'leave_id'=>'1','status'=>'0'))->row_array();
        $cr_leaves = $this->db->get_where('common_leave_types',array('branch_id'=>$this->session->userdata('branch_id'),'leave_id'=>'2','status'=>'0'))->row_array();
        if(count($cr_leaves) != 0){
            $cry_leaves = $cr_leaves['leave_days'];
        }else{
            $cry_leaves = 0;
        }

        $leave = array(
            'annual_leaves' => $anu_leaves['leave_days'],
            'cr_leaves' => $cry_leaves,
        );

        if(count($check_leaves) != 0)
        {
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $this->db->where('years',$cr_yr);
			$this->db->where('branch_id',$this->session->userdata('branch_id'));
            $this->db->update('yearly_leaves',$total_leaves);
        }else{
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $total_leaves['years'] = $cr_yr;
            $total_leaves['branch_id'] = $this->session->userdata('branch_id');
            $this->db->insert('yearly_leaves',$total_leaves);
        }
        echo $carry_max; exit;
    }
	
	function update_carry_forward_leave_bybranch()
    {
        $branch_id = $this->input->post('branch_id');
        $carry_max = $this->input->post('carry_max');
        // $leave_status = $this->input->post('leave_status');
        $res =array(
            'branch_id' => $branch_id,
			'leave_type'=>'Carry Forward',
            'leave_days' => $carry_max
            // 'leave_status' => $leave_status
            );
        // $this->db->where('leave_id',2);
        $this->db->where('leave_type_id',2);
		$this->db->where('branch_id',$branch_id);
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_type_id',2)->where('branch_id', $branch_id)->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id' =>2,'leave_type'=>'Carry Forward','leave_days' => $carry_max));
			// echo $this->db->last_query();exit;
		}
		
        $check_leaves = $this->db->get_where('yearly_leaves',array('branch_id'=>$branch_id,'years'=>$cr_yr))->row_array();
        $anu_leaves = $this->db->get_where('common_leave_types',array('branch_id'=>$branch_id,'leave_type_id'=>'1','status'=>'0'))->row_array();
        $cr_leaves = $this->db->get_where('common_leave_types',array('branch_id'=>$branch_id,'leave_type_id'=>'2','status'=>'0'))->row_array();
        if(count($cr_leaves) != 0){
            $cry_leaves = $cr_leaves['leave_days'];
        }else{
            $cry_leaves = 0;
        }

        $leave = array(
            'annual_leaves' => $anu_leaves['leave_days'],
            'cr_leaves' => $cry_leaves,
        );

        if(count($check_leaves) != 0)
        {
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $this->db->where('years',$cr_yr);
			$this->db->where('branch_id',$branch_id);
            $this->db->update('yearly_leaves',$total_leaves);
        }else{
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $total_leaves['years'] = $cr_yr;
            $total_leaves['branch_id'] = $branch_id;
            $this->db->insert('yearly_leaves',$total_leaves);
        }
        echo $carry_max; exit;
    }
    
    function update_earned_leave()
    {
        $earned_leaves = $this->input->post('earned_leaves');
        $leave_status = $this->input->post('leave_status');
        $res =array(
            'leave_days' => $earned_leaves,
			'branch_id' => $this->session->userdata('branch_id'),
            'leave_status' => $leave_status
            );
        $this->db->where('leave_id',3);
		$this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('common_leave_types',$res);
		$exists = $this->db->where('leave_id',3)->where('branch_id', $this->session->userdata('branch_id'))->get('common_leave_types');
// echo '<pre>';print_r($exists->num_rows());exit;
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$this->session->userdata('branch_id'),'leave_id' =>3,'leave_days' => $earned_leaves));
			// echo $this->db->last_query();exit;
		}
		
        echo $earned_leaves; exit;
    }
    
    function change_status()
    {
        $policy_id = $this->input->post('policy_id');
        $policy_name = $this->db->get_where('common_leave_types',array('leave_id'=>$policy_id))->row_array();
        if($policy_name['status'] == 0)
        {
            $status = "1";
        }else{
            $status = "0";
        }
        $res = array(
            'status' => $status
        );
        $this->db->where('leave_id',$policy_id);
        $this->db->update('common_leave_types',$res);
        echo "success"; exit;
    }

    function add_custom_policy()
    {
        // echo "<pre>"; print_r($_POST); exit;
        $policy_name = $this->input->post('policy_name');
        $policy_days = $this->input->post('policy_days');
        $policy_id = $this->input->post('policy_id');
        $policy = array(
            'branch_id' => $this->session->userdata('branch_id'),
            'custom_policy_name' => $policy_name,
            'policy_leave_days' => $policy_days,
            'leave_id' => $policy_id,
        );
        $this->db->insert('custom_policy',$policy);
        $insert_id = $this->db->insert_id();
        // echo $insert_id; exit;
        $users = $this->input->post('users');
        foreach($users as $user)
        {
            $u = array(
                'policy_id' => $insert_id,
                'user_id' => $user
            );
            $this->db->insert('assigned_policy_user',$u);
        }
        echo "success"; exit;
    }

    function add_new_leave_type()
    {
        $all_inputs = $this->input->post();
		// echo '<pre>';print_r($all_inputs);exit;
		if($this->session->userdata('user_type')==0)
		{
			$branch_id=$all_inputs['branch'];
		}
		else
		{
			$branch_id=$this->session->userdata('branch_id');	
		}
		
		$query = $this->db->query("SELECT * FROM dgt_common_leave_types ORDER BY leave_type_id DESC LIMIT 1");
$result = $query->row_array();
// echo '<pre>';print_r($result);exit;
        $res = array(
            'branch_id' =>  $branch_id,
            'leave_type_id' =>  $result['leave_id'] +1,
            'leave_type' => $all_inputs['leave_type_name'],
            'leave_days' => $all_inputs['leave_days']
        );
        $this->db->insert('common_leave_types',$res);
        $this->session->set_flashdata('tokbox_success', 'New LeaveType Added Successfully');
        redirect('leave_settings');
    }


    function update_policy_user()
    {
        echo "<pre>"; print_r($_POST); exit;
    }


    function delete_newleave_types()
    {
        $leave_id = $this->input->post('leave_id');
        $this->db->where('leave_id',$leave_id);
        $this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->delete('common_leave_types');
        echo 'success'; exit;
    }
	
	function delete_newleave_types_branchwise()
    {
        $leave_id = $this->input->post('leave_type_id');
        $this->db->where('leave_type_id',$leave_id);
        // $this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->delete('common_leave_types');
        echo 'success'; exit;
    }



    function policy_delete($id)
    {

        $this->db->where('assigned_id',$id);
        if($this->db->delete('assigned_policy_user'))
        {
            $this->session->set_flashdata('tokbox_success', 'Policy Deleted Successfully');
            echo "1";
        }
        else
        {
            $this->session->set_flashdata('tokbox_danger', 'Policy Deleted failed');
            echo "0";
        }
        exit;       
    }

    function update_accrual_leave()
    {
        $leave_day = $this->input->post('leave_day');
        $leave_day_month = $this->input->post('leave_day_month');
        $leave_status = $this->input->post('leave_status');
        $branch_id = $this->input->post("branch_id");
       
       
		$exists = $this->db->where('leave_type','Casual Leave')->where('leave_type_id',9)->where('branch_id', $branch_id)->get('common_leave_types');
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id' =>9,'leave_days' => $leave_day,'leave_type' => 'Casual Leave','leave_status' => 'yes','leave_day_month'=>$leave_day_month));
		}
        else{
            $res =array(
                'leave_days' => $leave_day,
                'leave_day_month' => $leave_day_month,
                'branch_id' => $branch_id,
                'leave_status' => 'yes'
            );

            $this->db->where('leave_type_id',9);
            $this->db->where('branch_id',$branch_id);
            $this->db->update('common_leave_types',$res);
        }
        echo 'success'; exit;
    }

    function update_leave()
    {
        $leave_day = $this->input->post('leave_day');
        $leave_day_month = $this->input->post('leave_day_month');
        $leave_status = $this->input->post('leave_status');
        $branch_id = $this->input->post("branch_id");
       
       
		$exists = $this->db->where('leave_type','Leave')->where('leave_type_id',24)->where('branch_id', $branch_id)->get('common_leave_types');
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id' =>24,'leave_days' => $leave_day,'leave_type' => 'Leave','leave_status' => 'no','leave_day_month'=>$leave_day_month));
		}
        else{
            $res =array(
                'leave_days' => $leave_day,
                'leave_day_month' => $leave_day_month,
                'branch_id' => $branch_id,
                'leave_status' => 'no'
            );

            $this->db->where('leave_type_id',24);
            $this->db->where('branch_id',$branch_id);
            $this->db->update('common_leave_types',$res);
        }
        echo 'success'; exit;
    }
    function update_privilege_leave()
    {
        $leave_day = $this->input->post('leave_day');
        $leave_day_month = $this->input->post('leave_day_month');
        $leave_status = $this->input->post('leave_status');
        $branch_id = $this->input->post("branch_id");
       
       
		$exists = $this->db->where('leave_type','Privilege Leave')->where('leave_type_id',28)->where('branch_id', $branch_id)->get('common_leave_types');
		if ($exists->num_rows() == 0) {
			$this->db->insert('common_leave_types',array("branch_id"=>$branch_id,'leave_type_id' =>28,'leave_days' => $leave_day,'leave_type' => 'Privilege Leave','leave_status' => 'no','leave_day_month'=>$leave_day_month));
		}
        else{
            $res =array(
                'leave_days' => $leave_day,
                'leave_day_month' => $leave_day_month,
                'branch_id' => $branch_id,
                'leave_status' => 'no'
            );

            $this->db->where('leave_type_id',28);
            $this->db->where('branch_id',$branch_id);
            $this->db->update('common_leave_types',$res);
        }
        echo 'success'; exit;
    }
}