<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Annual_incentive_plans extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('App', 'Lead' , 'Annual_incentive_plans_model'));
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
		if(!App::is_access('menu_annual_incentive_plans'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
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
        $this->template->title(lang('annual_incentive_plans').' - '.config_item('company_name'));
        $data['page'] = lang('annual_incentive_plans');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['plans'] = Annual_incentive_plans_model::all();          
        $this->template
                ->set_layout('users')
                ->build('annual_incentive_plans/index', isset($data) ? $data : null);
    }


    function add($plan_id='')
    { 
        if ($_POST||$plan_id!='') 
        {  
            if(sizeof(array_filter($_POST))<1&&!empty($plan_id))
            { 
                $plan = Annual_incentive_plans_model::find($plan_id);    
                $data = [];
                $plan_array = [];
                foreach ($plan as $key=>$val)
                {
                    $plan_array[$key]=$val;
                }    
                $data['plan'] = $plan_array;     
                $this->load->view('add',$data);
            }
            if(sizeof(array_filter($_POST))>0)
            {   
                if(isset($_POST['id'])&&!empty($_POST['id']))
                {
                    $plan_id = $_POST['id'];
                }
                $current_date_time = date('Y-m-d H:i:s'); 
                if(isset($_POST['edit'])&&$_POST['edit']=="true"&&!empty($plan_id))
                {
                    $plan_exists = Annual_incentive_plans_model::jobcard_exists($_POST['plan'],$_POST['id']); 
                    if(!$plan_exists)
                    {
                        $plan_id = $_POST['id'];
                        unset($_POST['edit']);
                        unset($_POST['id']);
                        $_POST['modified_date'] = $current_date_time;
                        App::update('annual_incentive_plans',array('id'=>$plan_id),$this->input->post());     
                        $this->session->set_flashdata('tokbox_success', lang('plan_updated_successfully'));
			            redirect('annual_incentive_plans');
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('plan_exists'));
			            redirect('annual_incentive_plans');
                    }
                }
                else 
                {
                    $plan_exists = Annual_incentive_plans_model::jobcard_exists($_POST['plan']); 
                    if(!$plan_exists)
                    {
                        $_POST['created_date'] = $current_date_time;
                        App::save_data('annual_incentive_plans',$this->input->post());     
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('plan_exists'));
			            redirect('annual_incentive_plans');
                    }
                }                            
                $this->session->set_flashdata('tokbox_success', lang('plan_added_successfully'));
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
            $plan = $this->input->post('plan', true);
            Annual_incentive_plans_model::delete($plan);  
            $this->session->set_flashdata('tokbox_success', lang('plan_deleted_successfully'));
            redirect('annual_incentive_plans');
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