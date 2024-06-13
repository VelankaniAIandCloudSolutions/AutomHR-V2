<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vacations extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('App', 'Lead' , 'Vocations_model'));
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
		if(!App::is_access('menu_vocation'))
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
        $this->template->title(lang('vocations').' - '.config_item('company_name'));
        $data['page'] = lang('vocations');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['vocations'] = Vocations_model::all();          
        $this->template
                ->set_layout('users')
                ->build('vacations/index', isset($data) ? $data : null);
    }


    function add($vocation_id='')
    { 
        if ($_POST||$vocation_id!='') 
        {  
            if(sizeof(array_filter($_POST))<1&&!empty($vocation_id))
            { 
                $vocation = Vocations_model::find($vocation_id);    
                $data = [];
                $vocation_array = [];
                foreach ($vocation as $key=>$val)
                {
                    $vocation_array[$key]=$val;
                }    
                $data['vocation'] = $vocation_array;     
                $this->load->view('add',$data);
            }
            if(sizeof(array_filter($_POST))>0)
            {   
                if(isset($_POST['id'])&&!empty($_POST['id']))
                {
                    $vocation_id = $_POST['id'];
                }
                $current_date_time = date('Y-m-d H:i:s'); 
                if(isset($_POST['edit'])&&$_POST['edit']=="true"&&!empty($vocation_id))
                {
                    $vocation_exists = Vocations_model::jobcard_exists($_POST['vocation'],$_POST['id']); 
                    if(!$vocation_exists)
                    {
                        $vocation_id = $_POST['id'];
                        unset($_POST['edit']);
                        unset($_POST['id']);
                        $_POST['modified_date'] = $current_date_time;
                        App::update('vocations',array('id'=>$vocation_id),$this->input->post());     
                        $this->session->set_flashdata('tokbox_success', lang('vocation_updated_successfully'));
			            redirect('vacations');
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('vocation_exists'));
			            redirect('vacations');
                    }
                }
                else 
                {
                    $vocation_exists = Vocations_model::jobcard_exists($_POST['vocation']); 
                    if(!$vocation_exists)
                    {
                        $_POST['created_date'] = $current_date_time;
                        App::save_data('vocations',$this->input->post());     
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('vocation_exists'));
			            redirect('vacations');
                    }
                }                            
                $this->session->set_flashdata('tokbox_success', lang('vocation_added_successfully'));
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
            $vocation = $this->input->post('vocation', true);
            Vocations_model::delete($vocation);  
            $this->session->set_flashdata('tokbox_success', lang('vocation_deleted_successfully'));
            redirect('vacations');
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

    function indicator_status(){
        $upd['status'] = $this->uri->segment(4); /*status*/
        $where['id'] = $this->uri->segment(3); /*id*/
        $this->Vocations_model->update('vocations',$upd,$where);
        // print_r($this);
        // die();
        $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
        redirect($_SERVER['HTTP_REFERER']);
      }

    
}