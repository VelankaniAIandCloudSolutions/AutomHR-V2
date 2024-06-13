<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appraisal extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App','Project','Appraisal_model'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
		if(!App::is_access('menu_appraisal'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
			redirect('');
		}

    }

    function index(){
    	  		$this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('appraisal').' - '.config_item('company_name'));
                 $data['form'] = TRUE;
                 $data['datatables'] = TRUE;
                $data['page'] = lang('appraisal');
              $data['appraisals'] = $this->Appraisal_model->select_appraisals();
             // echo $this->db->last_query();exit; 
                $this->template
                ->set_layout('users')
                ->build('appraisal',isset($data) ? $data : NULL);
    }
    function indicator(){
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('indicator').' - '.config_item('company_name'));
                 $data['form'] = TRUE;
                 $data['datatables'] = TRUE;
                $data['page'] = lang('indicator');
                $data['indicators'] = $this->Appraisal_model->select_indicators();

				if(!App::is_access('menu_indicator'))
				{
				$this->session->set_flashdata('tokbox_error', lang('access_denied'));
				redirect('');
				} 	



                $this->template
                ->set_layout('users')
                ->build('indicator',isset($data) ? $data : NULL);
    }

    function add_indicator(){
        if($_POST){

            //print_r($_POST);exit; 

          $this->form_validation->set_rules('designation','','trim|required');
          $this->form_validation->set_rules('indicators_level[]','','trim|required');
          
          if($this->form_validation->run()){
           $already = $this->Appraisal_model->select('indicators',array('designation_id'=>$this->input->post('designation')));
           if(count($already)>0){
            $this->session->set_flashdata('tokbox_error', lang('given_designation_already_have_indicators'));
            redirect($_SERVER['HTTP_REFERER']);
           }

            $ins['designation_id']= $this->input->post('designation');
            $ins['created_by'] =  $this->session->userdata('user_id');
            $ins['status'] = $this->input->post('status');
            $ins['created_at'] = date('Y-m-d');
            $indicators_level = $this->input->post('indicators_level');
            $indicators = [];
            $levels = [];
             foreach ($indicators_level as $key => $value) {
                 $indicators[$key] = $key;
                 $levels[$key] = $value; 
             }
             $ins['level'] = json_encode($levels,true);
             $ins['indicator'] = json_encode($indicators,true);
            $this->Appraisal_model->insert('indicators',$ins);
            $this->session->set_flashdata('tokbox_success', "Indicator details added successfully");
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);
          }

        }else{
             $data['designations'] = $this->Appraisal_model->select('dgt_designation');

             $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
            $this->load->view('modal/add_indicator',$data);
        }
    }
    function edit_indicator(){
        if($_POST){

            $this->form_validation->set_rules('designation','','trim|required');
          $this->form_validation->set_rules('indicators_level[]','','trim|required');
          
          if($this->form_validation->run()){

            $already = $this->Appraisal_model->select('indicators',array('designation_id'=>$this->input->post('designation'),'id !=' =>$this->uri->segment(3)));
           if(count($already)>0){
            $this->session->set_flashdata('tokbox_error', lang('given_designation_already_have_indicators'));
            redirect($_SERVER['HTTP_REFERER']);
           }
            $upd['designation_id']= $this->input->post('designation');
            $upd['updated_at'] = date('Y-m-d');
            $upd['status'] = $this->input->post('status');
           
            $indicators_level = $this->input->post('indicators_level');
            $indicators = [];
            $levels = [];
             foreach ($indicators_level as $key => $value) {
                 $indicators[$key] = $key;
                 $levels[$key] = $value; 
             }
            $upd['level'] = json_encode($levels,true);
            $upd['indicator'] = json_encode($indicators,true);
            $where['id'] = $this->uri->segment(3);
            $this->Appraisal_model->update('indicators',$upd,$where);
           // echo $this->db->last_query();exit;
            $this->session->set_flashdata('tokbox_success', lang('indicator_details_updated_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);

          }

        }else{
            $where['id'] = $this->uri->segment(3);
            $data['inidicator_data'] = $this->Appraisal_model->select_row('indicators',$where);         
            $data['designations'] = $this->Appraisal_model->select('dgt_designation');
            $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
            $this->load->view('modal/edit_indicator',$data);
        }
    }
    function delete_indicator(){
        if($_POST){

          $this->db->where('id',$_POST['id']);
          $this->db->delete('indicators'); 
          $this->session->set_flashdata('tokbox_success', lang('indicator_deleted_successfully'));
          redirect($_SERVER['HTTP_REFERER']);

        }else{
          $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_indicator',$data);
        }
    }


    function indicator_status(){
      $upd['status'] = $this->uri->segment(4); /*status*/
      $where['id'] = $this->uri->segment(3); /*id*/
      $this->Appraisal_model->update('indicators',$upd,$where);
      $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
      redirect($_SERVER['HTTP_REFERER']);
    }

    function appraisal_status(){
      $upd['status'] = $this->uri->segment(4); /*status*/
      $where['id'] = $this->uri->segment(3); /*id*/
      $this->Appraisal_model->update('employee_appraisal',$upd,$where);
      $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
      redirect($_SERVER['HTTP_REFERER']);
    }
    function add_appraisal(){
        if($_POST){

          //print_r($_POST);exit; 

          $this->form_validation->set_rules('employee_id','','trim|required');
          $this->form_validation->set_rules('appraisal_date','','trim|required');
          $this->form_validation->set_rules('levels[]','','trim|required');
          
          if($this->form_validation->run()){
            $ins['employee_id']= $this->input->post('employee_id');
            $appraisal_date = str_replace('/', '-', $this->input->post('appraisal_date'));
            $ins['appraisal_date']= date('Y-m-d',strtotime($appraisal_date));
            $ins['created_at'] = date('Y-m-d');
            $ins['created_by'] =$this->session->userdata('user_id');
            $ins['status'] = $this->input->post('status');
            $levels = $this->input->post('levels');
            $ins['levels'] = json_encode($levels,true);
                        
            $this->Appraisal_model->insert('employee_appraisal',$ins);
           // echo $this->db->last_query();exit;
            $this->session->set_flashdata('tokbox_success', lang('appraisal_added_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);

          }

        }else{
          $where['role_id'] = 3;
          $data['employees'] = $this->Appraisal_model->select('users',$where);
          $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
          $this->load->view('modal/add_appraisal',$data);
        }
    }

    function indicators_list(){
      
    $where['id'] = $this->input->post('designation');
       $employee_designation = $this->Appraisal_model->select_row('users',$where);
      $where1['designation_id'] = $employee_designation['designation_id'];
      $indicators_list =  $this->Appraisal_model->select_row('indicators',$where1);

      $indicators = json_decode($indicators_list['indicator'],true);
      $levels = json_decode($indicators_list['level'],true);
      //print_r($levels);exit;
      foreach ($levels as $key => $value) {
        if($value==1){ $level_name = "Beginner";}
        if($value==2){ $level_name = "Intermediate";}
        if($value==3){ $level_name = "Advanced";}
        if($value==4){ $level_name = "Expert / Leader";}
        $level_list[$key] = $level_name;
      }

      echo json_encode($level_list,true);exit;

    }
    function edit_appraisal(){
        if($_POST){
          //print_r($_POST);exit; 

          $this->form_validation->set_rules('employee_id','','trim|required');
          $this->form_validation->set_rules('appraisal_date','','trim|required');
          $this->form_validation->set_rules('levels[]','','trim|required');
          
          if($this->form_validation->run()){
            $upd['employee_id']= $this->input->post('employee_id');
            $appraisal_date = str_replace('/', '-', $this->input->post('appraisal_date'));
            $upd['appraisal_date']= date('Y-m-d',strtotime($appraisal_date));
            $upd['updated_at'] = date('Y-m-d');
           
            $upd['status'] = $this->input->post('status');
            $levels = $this->input->post('levels');
            $upd['levels'] = json_encode($levels,true);
            $where['id'] =  $this->uri->segment(3);          
            $this->Appraisal_model->update('employee_appraisal',$upd,$where);
           //echo $this->db->last_query();exit;
            $this->session->set_flashdata('tokbox_success', lang('appraisal_updated_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);

          }

        }else{

          $where['role_id'] = 3;
          $data['employees'] = $this->Appraisal_model->select('users',$where);
          $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();

          $cond['id'] =  $this->uri->segment(3);
          $data['appraisal_data'] = $this->Appraisal_model->select_row('employee_appraisal',$cond);

          
          $cond1['users.id'] =$data['appraisal_data']['employee_id'];
          $data['des_indicator'] = $this->Appraisal_model->get_indicators_row($cond1);
            $this->load->view('modal/edit_appraisal',$data);
        }
    }
    function delete_appraisal(){
         if($_POST){

          $this->db->where('id',$_POST['id']);
          $this->db->delete('employee_appraisal'); 
          $this->session->set_flashdata('tokbox_success', lang('indicator_deleted_successfully'));
          redirect($_SERVER['HTTP_REFERER']);

        }else{
          $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_appraisal',$data);
        }
    }

    function view_appraisal(){
       $cond['employee_appraisal.id'] =  $this->uri->segment(3);
        $data['appraisal_data'] = $this->Appraisal_model->select_appraisals_view($cond);

        
        $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();

       
        
        
        $this->load->view('modal/view_appraisal',$data);
    }

    function getEmployees(){
      extract($_GET);
  
      $this->db->select('U.id,AD.fullname,U.email')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1);
      $this->db->where("(AD.fullname like '%".$term['term']."%' OR U.email like '%".$term['term']."%' )", NULL, FALSE);
  
      $users = $this->db->order_by('AD.fullname','asc')->get()->result_array();
      
      echo json_encode($users);
      exit;
  
     }
}