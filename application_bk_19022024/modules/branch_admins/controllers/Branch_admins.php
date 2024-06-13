<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch_admins extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('App');
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) {
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title(lang('branch_admins'));
			$data['datepicker']     = TRUE;
			$data['page']           = lang('branch_admins');
			$data['form'] = true;
        	$data['datatables'] = true;
            $all_branch_admins = $this->db->select('*')
                                          ->from('users U')
                                          ->join('account_details AD','U.id=AD.user_id',LEFT)
                                          ->where('U.role_id',4)
                                          ->get()->result_array();
        	$data['all_branch_admins'] = $all_branch_admins;
			$this->template
				 ->set_layout('users')
				 ->build('branch_admins',isset($data) ? $data : NULL);
	    }else{
		   redirect('');	
		}			 
     }

     function add_branch(){
     	if($_POST){
     		$branch_details = array(
     			'branch_name' => $this->input->post('branch_name'),
     			'branch_status' => $this->input->post('branch_status')
     		);
     		// echo "<pre>"; print_r($branch_details); exit;
     		$this->db->insert('branches',$branch_details);
     		$this->session->set_flashdata('tokbox_success', 'New Branch Added!');
     		redirect('all_branches');
     	}else{
			$this->load->view('modal/add_branch');
     	}
     }

     function edit_branch(){
     	if($_POST){
     		$branch_details = array(
     			'branch_name' => $this->input->post('branch_name'),
     			'branch_status' => $this->input->post('branch_status')
     		);
     		// echo "<pre>"; print_r($branch_details); exit;
     		$this->db->where('branch_id',$this->input->post('branch_id'));
     		$this->db->update('branches',$branch_details);
     		$this->session->set_flashdata('tokbox_success', 'Branch Updated!');
     		redirect('all_branches');
     	}else{
     		$branch_id = $this->uri->segment(3);
     		$data['branch_id'] = $branch_id;
     		$data['branch_details'] = $this->db->get_where('branches',array('branch_id' => $branch_id))->row_array();
			$this->load->view('modal/edit_branch',$data);
     	}
     }



     function delete_branch(){
     	if($_POST){
     		$this->db->where('branch_id',$this->input->post('branch_id'));
     		$this->db->delete('branches');
     		$this->session->set_flashdata('tokbox_success', 'Branch Deleted!');
     		redirect('all_branches');
     	}else{
     		$branch_id = $this->uri->segment(3);
     		$data['branch_id'] = $branch_id;
			$this->load->view('modal/delete_branch',$data);
     	}
     }
	
}
