<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class All_jobtypes extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('App', 'Lead' , 'Job_types_model'));
        if (!User::is_admin()) {
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
        $this->template->title(lang('all_jobtypes').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobtypes');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['job_types'] = Job_types_model::all();          
        $this->template
                ->set_layout('users')
                ->build('all_jobtypes/index', isset($data) ? $data : null);
    }


    function add($job_type_id='')
    { 
        if ($_POST||$job_type_id!='') 
        {  
            if(sizeof(array_filter($_POST))<1&&!empty($job_type_id))
            { 
                $job_type = Job_types_model::find($job_type_id);    
                $data = [];
                $job_type_array = [];
                foreach ($job_type as $key=>$val)
                {
                    $job_type_array[$key]=$val;
                }    
                $data['job_type'] = $job_type_array;     
                $this->load->view('add',$data);
            }
            if(sizeof(array_filter($_POST))>0)
            {   
                if(isset($_POST['id'])&&!empty($_POST['id']))
                {
                    $job_type_id = $_POST['id'];
                }
                $current_date_time = date('Y-m-d H:i:s'); 
                if(isset($_POST['edit'])&&$_POST['edit']=="true"&&!empty($job_type_id))
                {
                    $job_type_exists = Job_types_model::jobcard_exists($_POST['job_type'],$_POST['id']); 
                    if(!$job_type_exists)
                    {
                        $job_type_id = $_POST['id'];
                        unset($_POST['edit']);
                        unset($_POST['id']);
                        $_POST['modified_date'] = $current_date_time;
                        App::update('jobtypes',array('id'=>$job_type_id),$this->input->post());     
                        $this->session->set_flashdata('tokbox_success', lang('jobtypes_updated_successfully'));
			            redirect('all_jobtypes');
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('job_type_exists'));
			            redirect('all_jobtypes');
                    }
                }
                else 
                {
                    $job_type_exists = Job_types_model::jobcard_exists($_POST['job_type']); 
                    if(!$job_type_exists)
                    {
                        $_POST['created_date'] = $current_date_time;
                        App::save_data('jobtypes',$this->input->post());     
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('job_type_exists'));
			            redirect('all_jobtypes');
                    }
                }                            
                $this->session->set_flashdata('tokbox_success', lang('job_types_added_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        else
        {    
            $this->load->view('add');
        }
    }

    public function delete($id='')
    {
        if ($this->input->post()) 
        {
            $jobtypes = $this->input->post('jobtypes', true);
            Job_types_model::delete($jobtypes);  
            $this->session->set_flashdata('tokbox_success', lang('jobtypes_deleted_successfully'));
            redirect('all_jobtypes');
        } 
        else 
        {
            if($id!='')
            {
                $data['id'] = $id;
                $this->load->view('delete',$data);
            }
        }
    }

    
}