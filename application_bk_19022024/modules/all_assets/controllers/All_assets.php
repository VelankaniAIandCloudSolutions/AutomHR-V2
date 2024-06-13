<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class All_assets extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Lead'));
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        $all_routes = $this->session->userdata('all_routes');
        foreach($all_routes as $key => $route){
            if($route == 'all_assets'){
                $routname = all_assets;
            } 
        }
        if(empty($routname)){
             $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_assets').' - '.config_item('company_name'));
        $data['page'] = lang('all_assets');
        $data['form'] = true;
        $data['datatables'] = true;
        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
            $branches = array_column($branches,'branch_id');

            $data['all_assets'] = $this->db->where_in('branch_id',$branches)->get('user_assets')->result_array();
       }
       else{
           $branches = $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
           if($this->tank_auth->get_role_id() == 1){
            $data['all_assets'] = $this->db->get_where('user_assets',array())->result_array();
           }
           else{
            $data['all_assets'] = $this->db->get_where('user_assets',array('branch_id'=>$this->session->userdata('branch_id')))->result_array();
           }
       }
       
       
        $this->template
                ->set_layout('users')
                ->build('all_assets', isset($data) ? $data : null);
    }

    function get_companies($branch_id){
        $companies = $this->db->select('*')->from('companies')->where('branch_id',$branch_id)->get()->result_array();
        $res_vals = array();
        $res_vals['companies']  = '<option value="">Select</option>';
        if(!empty($companies)){
            foreach($companies as $company1){
                $res_vals['companies'] .= '<option value="'.$company1['co_id'].'">'.$company1['company_name'].'</option>'; 
            }
        }
        $user_details = $this->db->select('*')->from('account_details')->where('branch_id',$branch_id)->get()->result_array();
        $res_vals['users']  = '';
        if(!empty($user_details)){
            foreach($user_details as $user_detail1){
                $res_vals['users'] .= '<option value="'.$user_detail1['user_id'].'">'.$user_detail1['fullname'].'</option>'; 
            }
        }
        echo json_encode($res_vals); exit;
    }


    public function add()
    {
        if($_POST)
        {
            $this->db->insert('user_assets',$this->input->post());
            $this->session->set_flashdata('tokbox_success', 'Asset Added Successfully');
            redirect('all_assets');
        }
    }
    public function edit($id)
    {
        if($_POST)
        {
            $this->db->where('assets_id',$id);
            $this->db->update('user_assets',$this->input->post());
            $this->session->set_flashdata('tokbox_success', 'Asset Updated Successfully');
            redirect('all_assets');
        }
    }

    public function delete($id)
    {
        $this->db->where('assets_id',$id);
        $this->db->delete('user_assets');
        $this->session->set_flashdata('tokbox_success', 'Asset deleted Successfully');
        redirect('all_assets');
    }

    public function assets_status_change($assets_id,$status){
        $result = array(
            'status' => $status
        );
        $this->db->where('assets_id',$assets_id);
        $this->db->update('user_assets',$result);
        $this->session->set_flashdata('tokbox_success', 'Asset Status changed');
        redirect('all_assets');
    }
}
/* End of file all_assets.php */
