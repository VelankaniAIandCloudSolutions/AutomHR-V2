<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class All_departments extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Lead'));
        
       /* if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {

        // echo "<pre>"; print_r($this->session->userdata()); exit;
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_departments').' - '.config_item('company_name'));
        $data['page'] = lang('all_departments');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['leads'] = Lead::all();
        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('all_departments', isset($data) ? $data : null);
    }


    function departments(){
        if ($_POST) {
            $settings = $_POST['settings'];
            unset($_POST['settings']);
            $_POST['branch_id'] = $this->session->userdata('branch_id');
            App::save_data('departments',$this->input->post());

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('department_added_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('department_added_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            // $this->index();
            $this->load->view('modal/add_department');
        }
    }

        function designations($dept_id =NULL){
        if ($_POST) {
            $settings = $_POST['settings'];
            unset($_POST['settings']);

            $this->db->where('department_id',$this->input->post('department_id'));
            $this->db->where('designation',$this->input->post('designation'));
            $design_exits = $this->db->get('designation')->num_rows();
            if($design_exits>0){
                $this->session->set_flashdata('tokbox_error', "Designation already exists");
                redirect($_SERVER['HTTP_REFERER']);
            }

            App::save_data('designation',$this->input->post());
            $this->session->set_flashdata('tokbox_success', 'Designation Added successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            // $this->index();
            $data['department_id'] = $dept_id;
            $this->load->view('modal/add_designation',$data);
        }
    }

    function edit_designation($des_id)
    {
        if($_POST){
            $this->db->where('id',$des_id) -> update('designation',$this->input->post());
            $this->session->set_flashdata('tokbox_success', 'Designation Update Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['des_details'] = $this->db->get_where('designation',array('id'=>$des_id))->row_array();
            $this->load->view('modal/edit_designation',$data);
        }
    }

    function delete_designation($des_id)
    {
        if($_POST){
            $this->db->where('id',$des_id) -> delete('designation');
            $this->session->set_flashdata('tokbox_success', 'Designation Deleted Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['des_details'] = $this->db->get_where('designation',array('id'=>$des_id))->row_array();
            $this->load->view('modal/delete_designation',$data);
        }
    }

    public function delete_designation_new($id,$table_name)
    {
        $this->db->where('id',$id)->delete($table_name);
        echo 'success';exit;
    }


    function edit_dept($deptid = NULL){
        if ($_POST) {
            if(isset($_POST['delete_dept']) AND $_POST['delete_dept'] == 'on'){
                $this->db->where('deptid',$_POST['deptid']) -> delete('departments');
                $this->session->set_flashdata('tokbox_error', lang('department_deleted'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                $this->db->where('deptid',$_POST['deptid']) -> update('departments',$this->input->post());
                $this->session->set_flashdata('tokbox_success', lang('department_updated'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            $data['deptid'] = $deptid;
            $data['dept_info'] = $this->db ->where(array('deptid'=>$deptid)) -> get('departments') -> result();
            $this->load->view('modal_edit_department',isset($data) ? $data : NULL);
        }
    }

    function view_designation($dept_id){
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_departments').' - '.config_item('company_name'));
        $data['page'] = lang('all_departments');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['all_designation'] = $this->db->get_where('designation',array('department_id'=>$dept_id))->result();
        $data['department_id'] = $dept_id;
        $this->template
                ->set_layout('users')
                ->build('all_designations', isset($data) ? $data : null);
    }

    public function check_depart_name()
    {
        $depart_name = $this->input->post('department_name');
        $depart_check = $this->db->get_where('departments',array('deptname'=>$depart_name))->num_rows();
        if($depart_check > 0){
            echo "false"; exit;
        }else{
            echo "true"; exit;
        }
    }

    
}
/* End of file all_departments.php */
