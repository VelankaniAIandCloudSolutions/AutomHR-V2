<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_entity extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->model(array('Client','App','Lead','Users'));
        $this->load->model('employees_details','employees');
        $this->load->library(array('tank_auth','form_validation'));
    }
    
    public function index(){
        $this->load->module('layouts');
        $this->load->library('template');

        $cur_date = date('Y-m-d');

        $employees = $this->db->select('*')->from('transfer_history')->where('status',0)->where('date(effective_date)',$cur_date)->get()->result_array();
        if(!empty($employees)){
           
            foreach($employees as $employee1){
                $chk_employee_branch=$this->db->select('*')->from('account_details AD')->join('users U','U.id = AD.user_id')->where('U.id',$employee1['user_id'])->where('AD.branch_id',$employee1['branch_id'])->get()->result_array();
                if(count($chk_employee_branch)==0)
                {
                   $entity_prefix = APP::get_entity_prefix($employee1['branch_id']);
                    $this->db->select('*')->from('account_details')->where('user_id',$employee1['user_id']);
                    if($this->session->userdata('user_type_name') =='company_admin'){
                        $this->db->where('branch_id',$this->session->userdata('branch_id'));
                    }
                    $chk_account = $this->db->get()->row_array();
                    if(!empty($chk_account)){
                        $emp_code = $entity_prefix.'-'.$employee1['employee_id'];
                    
                        $res = array('branch_id'=> $employee1['branch_id']);
                        $res['emp_code'] = $emp_code;
                        $res['employee_id'] = $employee1['employee_id'];
                        $res['type_change'] = $employee1['type_change'];
                        $res['doj']         = $employee1['effective_date'];
                        $res['location1']   = $employee1['location'];
                        $res['business_unit']   = $employee1['business_unit'];
                        $res['division']        = $employee1['division'];
                        $res['employee_id']   = $employee1['employee_id'];
                        $this->db->where('user_id',$employee1['user_id']);
                        $this->db->update('account_details',$res);

                        $upd_data = array('designation_id'=> $employee1['designation_id'],'department_id'=> $employee1['department_id']);
                        if(!empty($employee1['report_to'])){
                          $upd_data['teamlead_id'] = $employee1['report_to'];
                        }
                        $this->db->where('id',$employee1['user_id']);
                        $this->db->update('users',$upd_data);
                        $chk_entity=$this->db->select('*')->from('user_entity')->where('user_id',$employee1['user_id'])->where('status',1)->get()->row_array();
                        if(!empty($chk_entity)){
                            
                            $upd_data =  array();
                            $upd_data['status'] = 0;
                            $upd_data['transfer_date'] = date('Y-m-d');
                            $this->db->where('user_id',$employee1['user_id']);
                            $this->db->update('user_entity',$upd_data);
                        }
                        else{
                            $ins_data = array(
                                'user_id'	=> $employee1['user_id'],
                                'entity_id'	=> $employee1['branch_id'],
                                'status'	=> 1
                            );	
                            $this->db->insert('user_entity',$ins_data);			
                        }
                        

                        $upd_data =  array();
                        $upd_data['status'] = 1;
                        $this->db->where('user_id',$employee1['user_id']);
                        $this->db->update('transfer_history',$upd_data);
                        echo 'Success';
                        exit;
                    }
                }
            }
        }
    }
    public function getEmployees(){
		extract($_GET);
        $branches = array();
        if($this->session->userdata('user_type_name') =='company_admin'){
            $all_branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
            $branches = array_column($all_branches, 'branch_id');
        }
		$this->db->select('U.id,AD.fullname,AD.branch_id,U.email')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1);
        if($this->session->userdata('user_type_name') =='company_admin'){
            $this->db->where('U.id!=',$this->session->userdata('user_id'));
            $this->db->where_in('AD.branch_id',$branches);
            
        }
        $this->db->where("(AD.fullname like '%".$term['term']."%' OR U.email like '%".$term['term']."%' )", NULL, FALSE);

		$users = $this->db->order_by('AD.fullname','asc')->get()->result_array();
		echo json_encode($users);
		exit;

	 }

}

/* End of file employees.php */
