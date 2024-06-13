<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Candidates extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer','Jobs_model'));
        
        $this->load->library(array('form_validation')); 
    }

    public function index()
    {   
        
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(lang('job_dashboard').' - '.config_item('company_name'));
    $data['page'] = lang('jobs');
    $data['datatables'] = TRUE;
    $data['form'] = TRUE;
    $data['country_code'] = TRUE;
    $data['companies'] = Client::get_all_clients();
    $data['departments'] = Client::get_all_departments();



 // print_r($data);exit;
       
        $this->template
                ->set_layout('login')
                ->build('dashboard',isset($data)?$data:NULL);
    }
	function login_valid(){
        if(!$this->session->userdata('user_id')){
            redirect('candidates/login');
        }
    }
    public function dashboard()
    {
        $this->login_valid(); 
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('candidates_dashboard');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $jobs_counts = $this->Jobs_model->select_counts(array('candidate_id'=>$this->session->userdata('user_id'),'jobs.expired_date>='=>date('Y-m-d')));
       // echo $this->db->last_query();exit; 
        $job_count= [];
         foreach ($jobs_counts as $key => $val) {
             $job_count[$val['status']] = $val['count'];
         }
        $data['job_counts'] = $job_count;

        $data['applied_jobs'] = $this->Jobs_model->select_jobs_status_list(array('candidate_job_status.status'=>1,'candidate_id'=>$this->session->userdata('user_id')));

       $data['latest_jobs'] = $this->Jobs_model->get_latest_jobs(10);
       $where1['candidate_id'] = $this->session->userdata('user_id');
       $where1['jobs.expired_date>='] = date('Y-m-d'); 
        
        
        $join_table = 'jobs';
        $join_cond = 'jobs.id = candidate_visited_jobs.job_id';
       $data['total_visited'] = $this->Jobs_model->get_counts('candidate_visited_jobs',$join_table,$join_cond,$where1);
    $where['candidate_id'] = $this->session->userdata('user_id');
    $where['user_job_status >='] = 7; /*offered job*/
    $data['offered_jobs'] = $this->Jobs_model->select_offered_jobs($where);
    //echo $this->db->last_query();exit; 
       //print_r( $data['job_counts']);exit; 

    $data['month_wise'] = $month_wise= $this->Jobs_model->select_month_wise_details(array('status'=>1,'candidate_id'=>$this->session->userdata('user_id')));

     foreach($month_wise as $key => $list){
        $data['month_wise_total_jobs'][$list->month][] = $list->job_id;
        if($list->user_job_status==7 || $list->user_job_status==8){
          $data['month_wise_offer_jobs'][$list->month][] = $list->job_id;
        }
        
     }
      
        $this->template
                ->set_layout('users')
                ->build('dashboard', isset($data) ? $data : null);
    }
	 public function all_jobs()
    {
        $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();


        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));

        $data['job_types'] =  $this->Jobs_model->select('jobtypes',array('status'=>1));/* job types */
        $data['designation'] =  $this->Jobs_model->select('designation');/* get position list*/

        $where['jobs.deleted_at'] =NULL; 
        $where['jobs.expired_date>='] = date('Y-m-d'); 
        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
            $where['department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }

            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
            
        }elseif($this->input->post('advance_search')){
             $like = NULL; $where = NULL;
            if($this->input->post('department_id')!=""){
            $where['jobs.department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('position_id')!=""){
            $where['position_id'] = $this->input->post('position_id');    
            }
             if($this->input->post('job_type_id')!=""){
            $where['job_type_id'] = $this->input->post('job_type_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
        }else{
        $data['jobs_list'] = $this->Jobs_model->select_jobs_list($where);
        }
        $this->template
                ->set_layout('users')
                ->build('all_jobs', isset($data) ? $data : null);
    } 
     public function all_jobs_list()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));

         $data['job_types'] =  $this->Jobs_model->select('jobtypes',array('status'=>1));/* job types */
        $data['designation'] =  $this->Jobs_model->select('designation');/* get position list*/
    
        $where['jobs.deleted_at'] =NULL;  
        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
            $where['department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
            
        }elseif($this->input->post('advance_search')){


             $like = NULL; $where = NULL;
            if($this->input->post('department_id')!=""){ 
            $where['department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('position_id')!=""){
            $where['position_id'] = $this->input->post('position_id');    
            }
             if($this->input->post('job_type_id')!=""){
            $where['job_type_id'] = $this->input->post('job_type_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
        }else{
        $data['jobs_list'] = $this->Jobs_model->select_jobs_list($where);
        }
      
        $this->template
                ->set_layout('users')
                ->build('all_job_list', isset($data) ? $data : null);
    } 
    public function all_jobs_user()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));
        $data['job_types'] =  $this->Jobs_model->select('jobtypes',array('status'=>1));
        $data['designation'] =  $this->Jobs_model->select('designation');
        $where['jobs.deleted_at'] =NULL; 
        $where['jobs.expired_date>='] = date('Y-m-d'); 


        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            $where['jobs.deleted_at'] =NULL; 
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
            $where['jobs.department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
            
        }elseif($this->input->post('advance_search')){  /* for advanced search */
                $like = NULL; $where = NULL;
            if($this->input->post('department_id')!=""){
            $where['jobs.department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('position_id')!=""){
            $where['position_id'] = $this->input->post('position_id');    
            }
             if($this->input->post('job_type_id')!=""){
            $where['job_type_id'] = $this->input->post('job_type_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
        }
        else{
             $data['jobs_list'] = $this->Jobs_model->select_jobs_list($where);
        }
      

        $this->template
                ->set_layout('login')
                ->build('all_jobs_user', isset($data) ? $data : null);
    } 
     public function all_jobs_user_list()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
       $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));

        $data['job_types'] =  $this->Jobs_model->select('jobtypes',array('status'=>1));/* job types */
        $data['designation'] =  $this->Jobs_model->select('designation');/* get position list*/
        $where['jobs.deleted_at'] =NULL; 
        $where['jobs.expired_date>='] = date('Y-m-d'); 
        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
            $where['jobs.department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
            
        }elseif($this->input->post('advance_search')){
            $like= NULL; $where= NULL;
            if($this->input->post('department_id')!=""){
            $where['jobs.department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('position_id')!=""){
            $where['position_id'] = $this->input->post('position_id');    
            }
            if($this->input->post('job_type_id')!=""){
            $where['job_type_id'] = $this->input->post('job_type_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $data['jobs_list'] = $this->Jobs_model->select_jobs_list_filter($where,$like);
        }else{
             $data['jobs_list'] = $this->Jobs_model->select_jobs_list($where);
        }
      
        $this->template
                ->set_layout('login')
                ->build('all_job_user_list', isset($data) ? $data : null);
    } 
    public function position_types(){
       
        $designations = $this->Jobs_model->select_where_in('designation',$this->input->post('categories'),'department_id');
        
       echo json_encode($designations,true);
       die(); 
    }
    public function job_details(){
        extract($_POST);
        $jobs_detail = $this->Jobs_model->select_jobs_detail(array('jobs.id'=>$job_id));
        echo json_encode($jobs_detail,true);
        die(); 
    }
    public function job_view_user()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('jobview').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();


        $data['jobs_detail'] = $this->Jobs_model->select_jobs_detail(array('jobs.id'=>$this->uri->segment(3))); /* get jobs details*/
        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */

       $cond['job_id'] = $this->uri->segment(3);
       $data['total_views'] = $this->Jobs_model->get_counts('candidate_visited_jobs',$cond); /* get total views*/
       $cond['status'] =1; /* for applied jobs*/ 
       $data['applications'] = $this->Jobs_model->get_counts('candidate_job_status',$cond); /* total applicants */
        $this->template
                ->set_layout('login')
                ->build('job_view_user', isset($data) ? $data : null);
    }


    public function job_view()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('jobview').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        if(!$this->uri->segment(3)){
            redirect('candidates/dashboard');
        }

         if($this->session->userdata('user_id')  && $this->uri->segment(3)!=0){
                $where['job_id'] = $this->uri->segment(3);          
                $where['candidate_id'] = $this->session->userdata('user_id');
                $visited = $this->Jobs_model->select_check('candidate_visited_jobs',$where);

                if($visited->num_rows()>0){
                    $upd['visited_on'] = date('Y-m-d H:i:s');
                     $upd['candidate_id'] = $this->session->userdata('user_id');
                      $upd['job_id'] =  $this->uri->segment(3); 
                    $visited_data = $visited->row_array();
                    $upd_visit = $this->Jobs_model->update('candidate_visited_jobs',$upd,array('id'=>$visited_data['id']));
                     
                }else{
                   
                    $ins['job_id'] = $this->uri->segment(3);          
                    $ins['candidate_id'] = $this->session->userdata('user_id');
                    $ins['visited_on'] = date('Y-m-d H:i:s');
                    $add_visit = $this->Jobs_model->insert('candidate_visited_jobs',$ins);
                    
                }
              

        }

       $cond['job_id'] = $this->uri->segment(3);
       $data['total_views'] = $this->Jobs_model->get_counts('candidate_visited_jobs',$cond); /* get total views*/
       $cond['status'] =1; /* for applied jobs*/ 
       $data['applications'] = $this->Jobs_model->get_counts('candidate_job_status',$cond); /* total applicants */
       $data['jobs_detail'] = $this->Jobs_model->select_jobs_detail(array('jobs.id'=>$this->uri->segment(3))); /* get jobs details*/
       $cond['candidate_id'] =  $this->session->userdata('user_id');
        $applied_status = $this->Jobs_model->select_check('candidate_job_status',$cond);
        $data['applied_status']= true;
        if($applied_status->num_rows()>0){
            $data['applied_status']= false;
        }
        $this->template
                ->set_layout('users')
                ->build('jobview', isset($data) ? $data : null);
    }

    

    public function job_apply(){
       

       if($this->session->userdata('user_id')){
        if($this->uri->segment(4)=="apply"){
            $status = 1;
            $emessage=lang('already_applied_for_this_job');
            $message = lang('job_applied_successfully');
        }
         if($this->uri->segment(4)=="save"){
            $status = 2;
            $emessage=lang('already_saved_this_job');
             $message = lang('job_saved_successfully');
        }
         if($this->uri->segment(4)=="archive"){
            $status = 3;
            $emessage=lang('already_archived_this_job');
            $message = lang('job_archived_successfully');
        }
        $already = $this->Jobs_model->select_check('candidate_job_status',array('job_id'=>$this->uri->segment(3),'candidate_id'=>$this->session->userdata('user_id'),'status'=>$status));
        if($already->num_rows()>0){
             $this->session->set_flashdata('tokbox_error',$emessage);
             redirect('candidates/job_view/'.$this->uri->segment(3)); 
        }

       $check_other_status = $this->Jobs_model->select_check('candidate_job_status',array('job_id'=>$this->uri->segment(3),'candidate_id'=>$this->session->userdata('user_id')));
       if($check_other_status->num_rows()>0){

        $apply_job = $this->Jobs_model->update('candidate_job_status',array('status'=>$status),array('job_id'=>$this->uri->segment(3),'candidate_id'=>$this->session->userdata('user_id')));
       }else{
         $apply_job = $this->Jobs_model->insert('candidate_job_status',array('job_id'=>$this->uri->segment(3),'candidate_id'=>$this->session->userdata('user_id'),'status'=>$status));
       }

        

        if($apply_job){
             $this->session->set_flashdata('tokbox_success', $message);
             redirect('candidates/job_view/'.$this->uri->segment(3)); 
        }else{
            $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
             redirect('candidates/job_view/'.$this->uri->segment(3)); 
        }

       }else{
        redirect('candidates/login');
       }
        
    }
     

    public function login(){
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('login').' - '.config_item('company_name'));
        $data['page'] = lang('all_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        if($this->session->userdata('user_id')){
             redirect('candidates/dashboard');
        }
        if($this->input->post('sign_in')){

                $this->form_validation->set_rules('username','','trim|required');
                $this->form_validation->set_rules('password','','trim|required');
                if($this->form_validation->run()){
                    $where['email'] = $this->input->post('username');
                    $where['password'] = md5($this->input->post('password'));
                    $user = $this->Jobs_model->select_check('registered_candidates',$where);
                    if($user->num_rows()>0){
                        $row = $user->row_array();
                        $upd['online_status'] = 1;
                         $upd['last_login'] = date('Y-m-d H:i:s');
                        $update = $this->Jobs_model->update('registered_candidates',$upd,array('id'=>$row['id']));

                    $this->session->set_userdata('user_id', $row['id']);
                    $this->session->set_userdata('candidate_id', $row['id']);
                    $this->session->set_userdata('username', $row['first_name']);
                    $this->session->set_userdata('status', $row['status']);
                    //$this->session->set_flashdata('message', 'login successfully');
                    //$this->session->set_flashdata('response_status', 'success');
                   
                    redirect('candidates/dashboard');

                    }else{
                          $this->session->set_flashdata('message', lang('invalid_username_password'));
                           $this->session->set_flashdata('response_status', 'error');
                          redirect('candidates/login');
                    }


                }else{
                     $this->session->set_flashdata('message', lang('username_password_is_missing'));
                     $this->session->set_flashdata('response_status', 'error');
                     redirect('candidates/login');
                }

        }
        
        $this->template
                ->set_layout('login')
                ->build('candidate_login', isset($data) ? $data : null);
       
    }

    function linked_login(){

  //       $ch = curl_init('https://www.howsmyssl.com/a/check'); 
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  // $data = curl_exec($ch); 
  // curl_close($ch); 
  // $json = json_decode($data); 
  // echo "<h1>Your TLS version is: " . $json->tls_version . "</h1>\n";
  // exit;
        $this->load->library('linkedin', array(
            'access' => "86m9lg89l5go7a",//"<your Consumer Key / API Key goes here>",
            'secret' => "iUMdLTEa7XruuzPU",//"<your Consumer Secret / Secret Key goes here>",
            'callback' => base_url()."candidates/receiver"//"<write here your site name>/receiver" 
        ));
        $this->linkedin->getRequestToken();



        
        $requestToken = serialize($this->linkedin->request_token);
        // echo $requestToken; exit;
        $this->session->set_userdata(array(
            'requestToken' => $requestToken
        ));
       // echo $this->linkedin->generateAuthorizeUrl();exit; 
        header("Location: " . $this->linkedin->generateAuthorizeUrl());

  


    }

    public function receiver(){ 

        if (isset($_GET['oauth_problem'])) {
            session_unset();
            $this->session->set_flashdata(
                'linkedinError', 
                array(
                    'type' => 'error',
                    'msg' => 'Sorry! Something went wrong this time. Please try again later.'
                )
            );
            redirect('.');
            exit;
        }

        $this->load->library('linkedin', array(
            'access' => "86m9lg89l5go7a",//"<your Consumer Key / API Key goes here>",
            'secret' => "iUMdLTEa7XruuzPU",//"<your Consumer Secret / Secret Key goes here>" 
        ));

        // get from session
        $requestToken = $this->session->userdata('requestToken');
        
        if (isset($_REQUEST['oauth_verifier'])) {
            $oauthVerifier = $_REQUEST['oauth_verifier'];
            $this->linkedin->request_token = unserialize($requestToken);
            $this->linkedin->oauth_verifier = $oauthVerifier;
            $this->linkedin->getAccessToken($oauthVerifier);
            // set in session
            $this->session->set_userdata(array(
                'oauth_verifier' => $oauthVerifier,
                'oauth_access_token' => serialize($this->linkedin->access_token)
            ));
        } else {
            $oauthVerifier = $this->session->userdata('oauth_verifier');
            $oauthAccessToken = $this->session->userdata('oauth_verifier');
            $this->linkedin->request_token = unserialize($requestToken);
            $this->linkedin->oauth_verifier = $oauthVerifier;
            $this->linkedin->access_token = unserialize($oauthAccessToken);
        }


        if (isset($_REQUEST['oauth_verifier'])) {
            $userData = $this->linkedin->getUserInfo(
                serialize($this->linkedin->request_token), 
                $this->session->userdata('oauth_verifier'), 
                $this->session->userdata('oauth_access_token')
            );
             
        } else {
            $userData['status'] = 404;
        }
    }
    public function linkedauth(){       //optional


        $get    =   $this->input->get();
        print_r($get);
    }

    function forgot_password(){

         $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('login').' - '.config_item('company_name'));
        $data['page'] = lang('forgot_password');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $this->form_validation->set_rules('login', 'Email or Username', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run()) { 
             if (!is_null($data = $this->tank_auth->forgot_password_candidate(
                    $this->form_validation->set_value('login')))) {

                    $data['site_name'] = config_item('company_name');

                    // Send email with password activation link
                    // $this->_send_email('forgot_password', $data['email'], $data);
                    // $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_sent'));
                    // //$this->_show_message($this->lang->line('auth_message_new_password_sent'));
                    // redirect('login');

                                     // validation ok
                    // $data['site_name'] = config_item('company_name');
                    // Send email with password activation link
                    $message = App::email_template('forgot_password','template_body');
                    $subject = App::email_template('forgot_password','subject');
                    $signature = App::email_template('email_signature','template_body');

                    $logo_link = create_email_logo();

                    $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

                    $site_url = str_replace("{SITE_URL}",base_url().'login',$logo);
                    $key_url = str_replace("{PASS_KEY_URL}",base_url().'candidates/reset_password/'.$data['user_id'],$site_url);
                    $EmailSignature = str_replace("{SIGNATURE}",$signature,$key_url);
                    $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

                    $params['recipient'] = $data['email'];

                    $params['subject'] = '[ '.config_item('company_name').' ] '.$subject;
                    $params['message'] = $message;

                    $params['attached_file'] = '';

                    Modules::run('fomailer/send_email',$params);

                    $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_sent'));
                    //$this->_show_message($this->lang->line('auth_message_new_password_sent'));
                    redirect('candidates');
                     } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }         
            }
            $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */

        $this->template
                ->set_layout('login')
                ->build('auth/forgot_password_form', isset($data) ? $data : null);
    }

    function reset_password(){
        $user_id        = $this->uri->segment(3);
        $check_user = $this->Jobs_model->select_row_array('registered_candidates',array('id' => $user_id));
           if(!$check_user){
            $this->session->set_flashdata('message',lang('invalid_user'));
            redirect('candidates/login');
           }
        if($_POST){
           // print_r($_POST);
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

        $data['errors'] = array();

        if ($this->form_validation->run()) {      

           
           $user = $check_user;
           $data['email'] = $check_user['email'];
           $data['username'] = $check_user['email'];
           $data['password'] = $this->input->post('confirm_new_password');

           $this->Jobs_model->update('registered_candidates',array('password'=>md5($this->input->post('confirm_new_password'))),array('id'=>$user_id));
           // echo $this->db->last_query();exit; 
            $data['site_name'] = config_item('company_name');

                // Send email with new password
                    $message = App::email_template('reset_password','template_body');
                    $subject = App::email_template('reset_password','subject');
                    $signature = App::email_template('email_signature','template_body');

                    $logo_link = create_email_logo();

                    $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

                    $username = str_replace("{USERNAME}",$data['username'],$logo);
                    $user_email =  str_replace("{EMAIL}",$data['email'],$username);
                    $user_password =  str_replace("{NEW_PASSWORD}",$data['new_password'],$user_email);
                    $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
                    $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

                    $params['recipient'] = $email;

                    $params['subject'] = '[ '.config_item('company_name').' ]'.$subject;
                    $params['message'] = $message;

                    $params['attached_file'] = '';

                    modules::run('fomailer/send_email',$params);
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_activated'));
                redirect('candidates/login');

           
        } else {
            $error = validation_errors(); 
            $this->session->set_flashdata('message',$error);
            redirect('candidates/reset_password/'.$user_id);
        }
    }

     $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Forgot Password - '.config_item('company_name'));
        $this->template
            ->set_layout('login')
            ->build('auth/reset_password_form',isset($data) ? $data : NULL);
    }

    function logout(){

         $upd['online_status'] = 0;
         $upd['call_status'] = 0;
         $update = $this->Jobs_model->update('registered_candidates',$upd,array('id'=>$this->session->userdata('user_id')));
        session_destroy();


        $this->session->set_flashdata('message', 'logedout successfully');
        $this->session->set_flashdata('response_status', 'error');
        redirect('candidates/login');
       
    }

    public function register(){

        if($this->input->post('submit')){
          $this->form_validation->set_rules('first_name','','trim|required');
          $this->form_validation->set_rules('email','','trim|required');
          $this->form_validation->set_rules('password','','trim|required');
          $this->form_validation->set_rules('c_password','','trim|required');
            
          if($this->input->post('password') != $this->input->post('c_password'))
          {
               $this->session->set_flashdata('error', lang('password_and_confirm_password_mismatch'));
              // redirect('candidates/job_view_user/'.$this->input->post('job_id')); 
               redirect($this->input->post('url')); 
          }
         
          if($this->form_validation->run()){
            if(isset($_FILES['resume']['name']) && empty($_FILES['resume']['name'])){

                $this->session->set_flashdata('error', lang('please_upload_your_resume'));
                 redirect($this->input->post('url')); 
            }
            

            $already = $this->Jobs_model->select_check('registered_candidates',array('email'=>$this->input->post('email')));

            if($already->num_rows()>0){
                
                $this->session->set_flashdata('error', lang('email_id_already_register'));
                 redirect($this->input->post('url')); 
            } 


            $ins['first_name'] = $this->input->post('first_name');
            $ins['last_name'] = $this->input->post('last_name');
            $ins['email'] = $this->input->post('email');
            $ins['job_category_id'] = json_encode($this->input->post('job_category'),true);
            $ins['position_type_id'] = json_encode($this->input->post('position_type'),true);
            $ins['country_id'] = json_encode($this->input->post('country'),true);
            $ins['password'] = md5($this->input->post('c_password'));
            $ins['status'] = 1;
           
          $add = $this->Jobs_model->insert('registered_candidates',$ins);
          
           if($add){   

            /*resume upload */
            if(!empty($_FILES['resume']['name'])){
                if($this->upload('resume')){
                    $files_detail = $this->upload('resume');
                     $resume_name = $files_detail['file_name'];
                     $file_data['candidate_id'] = $add;
                     $file_data['title'] = 'Resume';
                     $file_data['file_name'] = $resume_name;
                     $insert_file = $this->Jobs_model->insert('candidate_files',$file_data);  
                    // echo $this->db->last_query();exit; 

                }
             }


            $userdata = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$add));
           // echo $this->db->last_query();
            $this->session->set_userdata('user_id', $userdata['id']);
            $this->session->set_userdata('candidate_id', $userdata['id']);
            $this->session->set_userdata('username', $userdata['first_name']);
            $this->session->set_userdata('status', $userdata['status']);
            if($this->input->post('register_for')!=""){
            
            if($this->input->post('register_for')=="apply"){
                $status = 1; 
            }
            if($this->input->post('register_for')=="saved"){
                 $status = 2;
            }
            if($this->input->post('register_for')=="archive"){
                 $status = 3;
            }

            $archive = $this->Jobs_model->insert('candidate_job_status',array('job_id'=>$this->input->post('job_id'),'candidate_id'=>$userdata['id'],'status'=>$status));
            //exit; 
            }
            $this->session->set_flashdata('success', lang('register_login_successfully'));
            redirect('candidates/candidate_profile');
           }else{
             $this->session->set_flashdata('error', lang('something_went_to_wrong'));
              redirect($this->input->post('url'));  
           }

          }else{

          //  print_r(validation_errors());exit; 
             $this->session->set_flashdata('error', validation_errors());
             redirect($this->input->post('url')); 
          }
        }else{
            $this->load->view('modal/register');
        }
        
    } 

    function upload($file_name){


         $config['upload_path'] = './images/resume/';
         $config['allowed_types'] = 'pdf|docx|doc';
         $config['max_size']      = 0;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);

        
        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            $data = array('image_metadata' => $this->upload->data());
           return $this->upload->data();
        }
    }

	public function saved()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('saved');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));
        $where['jobs.deleted_at']= NULL;
        $where['jobs.expired_date>='] = date('Y-m-d'); 
        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
            $where['department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
            $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
            $like= $this->input->post('keywords');    
            }
            $where['candidate_job_status.status'] = 2;
            $where['candidate_id'] = $this->session->userdata('user_id');
            $data['saved_jobs'] = $this->Jobs_model->select_jobs_status_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
            
        }else{
            $where['candidate_job_status.status'] = 2;
            $where['candidate_id'] = $this->session->userdata('user_id');
        $data['saved_jobs'] = $this->Jobs_model->select_jobs_status_list($where);
        }
        $this->template
                ->set_layout('users')
                ->build('saved', isset($data) ? $data : null);
    }
	public function applied()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('applied');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

         $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));
        $where['jobs.deleted_at']= NULL;
        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
                $where['department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
                $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
                $like= $this->input->post('keywords');    
            }
            $where['candidate_job_status.status'] = 1;
            $where['candidate_id'] = $this->session->userdata('user_id');
            $data['applied_jobs'] = $this->Jobs_model->select_jobs_status_list_filter($where,$like);
            //echo $this->db->last_query();exit; 
            
        }else{
            $where['candidate_job_status.status'] = 1;
            $where['candidate_id'] = $this->session->userdata('user_id');
            $data['applied_jobs'] = $this->Jobs_model->select_jobs_status_list($where);
      }
        $this->template
                ->set_layout('users')
                ->build('applied', isset($data) ? $data : null);
    }
	public function interviewing()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('interviewing');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $where['jobs.deleted_at'] = NULL;
        $where['candidate_job_status.status'] =1;
        $where['candidate_job_status.candidate_id'] =$this->session->userdata('user_id');
        $where_not =array(0,2); /* resume selected*/
        $data['interviewing_jobs'] = $this->Jobs_model->select_interview_jobs($where,$where_not);
        $where_not =array(0,2,4); /* apptitude selected*/
        $data['scheduled_interview'] = $this->Jobs_model->select_users_interview($where,$where_not);
        $data['time_list'] =$this->Jobs_model->time_diffence();

        //echo $this->db->last_query();exit; 

        $this->template
                ->set_layout('users')
                ->build('interviewing', isset($data) ? $data : null);
    }

    function schedule_interview_time(){
        $data['job_id'] = $job_id = $this->uri->segment('3');
        $scheduled_timings = $this->Jobs_model->select_row_array('schedule_timings',array('user_id'=>$this->session->userdata('user_id'),'job_id'=>$job_id));
        $data['schedule_dates'] = json_decode($scheduled_timings['schedule_date'],true);
        $data['schedule_timings'] = json_decode($scheduled_timings['available_timing']);
        $data['user_selected_timing'] = json_decode($scheduled_timings['user_selected_timing']);

        if(isset($_POST['submit'])){
            $upd['user_selected_timing'] = json_encode($this->input->post('user_date'),true);
            $upd['updated_at'] = date('Y-m-d H:i:s');
            $update = $this->Jobs_model->update('schedule_timings',$upd,array('user_id'=>$this->session->userdata('user_id'),'job_id'=>$job_id));
           // echo $this->db->last_query();exit; 
            if($update){
                $this->session->set_flashdata('tokbox_success', lang('interview_scheduled_successfully'));
             redirect('candidates/interviewing'); 
            }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
               redirect('candidates/interviewing'); 

            }

        }

         $data['time_list'] = $this->Jobs_model->time_diffence();
        


        $this->load->view('modal/schedule_time_modal',$data);
    }
    public function interviewing_jobs()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('interviewing_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

      
        $this->template
                ->set_layout('users')
                ->build('interviewing_job_list', isset($data) ? $data : null);
    }
    public function candidate_profile()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('interviewing_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        //print_r($_POST);exit;
        if($this->input->post('save_contact')){
           
            $this->form_validation->set_rules('first_name','','trim|required');
            $this->form_validation->set_rules('last_name','','trim|required');
            $this->form_validation->set_rules('email','','trim|required');
            $this->form_validation->set_rules('address','','trim|required');
            $this->form_validation->set_rules('country','','trim|required');
            $this->form_validation->set_rules('state','','trim|required');
            $this->form_validation->set_rules('city','','trim|required');
            $this->form_validation->set_rules('pincode','','trim|required');
            $this->form_validation->set_rules('phone_number','','trim|required');
          
          if($this->form_validation->run()){
            $where['id'] = $this->session->userdata('user_id');
            $basic_upd['first_name'] = $this->input->post('first_name');
            $basic_upd['last_name'] = $this->input->post('last_name');
            $basic_upd['email'] = $this->input->post('email');
            $update_basic = $this->Jobs_model->update('registered_candidates',$basic_upd,$where); 

            $already = $this->Jobs_model->select_check('candidate_additional_information',array('candidate_id'=>$this->session->userdata('user_id')));
            $upd_data['address'] = $this->input->post('address');
            $upd_data['country'] = $this->input->post('country');
            $upd_data['state'] = $this->input->post('state');
            $upd_data['city'] = $this->input->post('city');
            $upd_data['pincode'] = $this->input->post('pincode');
            $upd_data['phone_number'] = $this->input->post('phone_number');
            if($this->input->post('web_address')!=""){
             $upd_data['web_address'] = $this->input->post('web_address');   
            }
            if(!empty($_FILES['resume_file']['name'])){
            $files_detail = $this->upload('resume_file');
             $file_name = $files_detail['file_name'];
             $cond['candidate_id'] = $this->session->userdata('user_id');
             $cond['file_type'] = 1; /* for resume files*/
             $file_data['file_name'] = $file_name;           
             $add_file = $this->Jobs_model->update('candidate_files',$file_data,$cond);

            }

            if($already->num_rows()>0){
              $result  =$this->Jobs_model->update('candidate_additional_information',$upd_data,array('candidate_id'=>$this->session->userdata('user_id')));

            }else{
                $upd_data['candidate_id'] =  $this->session->userdata('user_id');
                $result  =  $this->Jobs_model->insert('candidate_additional_information',$upd_data);
            }
            if($result){
                $this->session->set_flashdata('tokbox_success',lang('details_saved_successfully'));
                redirect('candidates/candidate_profile'); 
            }else{
                $this->session->set_flashdata('tokbox_error',lang('something_went_to_wrong'));
                redirect('candidates/candidate_profile'); 
            }

          }else{
             $this->session->set_flashdata('tokbox_error',lang('some_feilds_missing'));
             redirect('candidates/candidate_profile'); 
          }
        }

        if($this->input->post('save_education')){
 
            $this->form_validation->set_rules('school_name','','trim|required');
            $this->form_validation->set_rules('passed_out_year','','trim|required');
            $this->form_validation->set_rules('major_subject','','trim|required');
            $this->form_validation->set_rules('degree','','trim|required');
            $this->form_validation->set_rules('gpa','','trim|required');

            if($this->form_validation->run()){

                $already = $this->Jobs_model->select_check('candidate_additional_information',array('candidate_id'=>$this->session->userdata('user_id')));
                $upd_data['school_name'] = $this->input->post('school_name');
                $upd_data['passed_out_year'] = $this->input->post('passed_out_year');
                $upd_data['major_subject'] = $this->input->post('major_subject');
                $upd_data['degree'] = $this->input->post('degree');
                $upd_data['gpa'] = $this->input->post('gpa');
                if($already->num_rows()>0){

                    $result  =$this->Jobs_model->update('candidate_additional_information',$upd_data,array('candidate_id'=>$this->session->userdata('user_id')));
                }else{
                    $upd_data['candidate_id'] =  $this->session->userdata('user_id');
                    $result  =  $this->Jobs_model->insert('candidate_additional_information',$upd_data);
                }
                if($result){
                    $this->session->set_flashdata('tokbox_success',lang('details_saved_successfully'));
                    redirect('candidates/candidate_profile'); 
                }else{
                    $this->session->set_flashdata('tokbox_error',lang('something_went_to_wrong'));
                    redirect('candidates/candidate_profile'); 
                }

            }else{
                $this->session->set_flashdata('tokbox_error',lang('some_feilds_missing'));
                redirect('candidates/candidate_profile'); 
            }

        }

        if($this->input->post('save_skill')){
            if($this->input->post('skills')==""){
                //echo 1233;exit;
                $this->session->set_flashdata('tokbox_error',lang('add_skills_rersponsibility'));
                redirect('candidates/candidate_profile'); 
            }
            $already = $this->Jobs_model->select_check('candidate_additional_information',array('candidate_id'=>$this->session->userdata('user_id')));
              $upd_data['skills'] = $this->input->post('skills');
             if($already->num_rows()>0){
                 $result  =$this->Jobs_model->update('candidate_additional_information',$upd_data,array('candidate_id'=>$this->session->userdata('user_id')));
             }else{
                $upd_data['candidate_id'] =  $this->session->userdata('user_id');
                $result  =  $this->Jobs_model->insert('candidate_additional_information',$upd_data);

             }
             if($result){
                $this->session->set_flashdata('tokbox_success',lang('skills_saved_successfully'));
                redirect('candidates/candidate_profile'); 

             }else{
                $this->session->set_flashdata('tokbox_error',lang('something_went_to_wrong'));
                redirect('candidates/candidate_profile'); 
             }

        }
      //  print_r($_POST);exit; 

        if($this->input->post('save_experience')){


        $company_name = $this->input->post('company_name'); 
        $location = $this->input->post('location'); 
        $job_position = $this->input->post('job_position'); 
        $period_from = $this->input->post('period_from'); 
        $period_to = $this->input->post('period_to');
        $experineces = array();
        for($i = 0; $i< count($company_name); $i++)
            {
                $experinece = array(
                    'company_name'=>$company_name[$i],
                    'location'=>$location[$i],
                    'job_position'=>$job_position[$i],
                    'period_from'=>$period_from[$i],
                    'period_to'=>$period_to[$i]
                );
                $experineces[] = $experinece;
            }
            // echo $user_id; exit;
        $result = array(
                'experience_details' => json_encode($experineces)
            );
   // print_r($result);exit;
        $user_id = $this->session->userdata('user_id');
        $check_user = $this->db->get_where('candidate_additional_information',array('candidate_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('candidate_id',$user_id);
            $this->db->update('candidate_additional_information',$result);
         //   echo $this->db->last_query();exit; 
            $this->session->set_flashdata('tokbox_success', lang('experience_info_updated'));
            redirect('candidates/candidate_profile');
        }else{
            $this->session->set_flashdata('tokbox_error', lang('please_add_basic_details'));
            redirect('candidates/candidate_profile');
        }
    
        
        }

        $where['a.id'] = $this->session->userdata('user_id');
        // $where['c.file_type'] = 1;
        $data['candidate_detail'] =  $this->Jobs_model->select_candidate_details($where);

        
        // echo $this->db->last_query();exit; 
      
        $this->template
                ->set_layout('users')
                ->build('candidate_profile', isset($data) ? $data : null);
    }

    public function candidate_files(){

         if(empty($_FILES['userfile']['name'])){
              $this->session->set_flashdata('tokbox_error',lang('please_select_upload_file'));
                        redirect('candidates/candidate_profile');
         }
         if($this->input->post('title') == ""){
            $this->session->set_flashdata('tokbox_error',lang('please_enter_file_title'));
            redirect('candidates/candidate_profile');
         }

        if($this->upload('userfile')){
             $files_detail = $this->upload('userfile');
             $file_name = $files_detail['file_name'];
             $file_data['candidate_id'] = $this->session->userdata('user_id');
             $file_data['title'] = $this->input->post('title');
             $file_data['file_name'] = $file_name;
             $file_data['file_type'] = 2; /* for other files*/
             $add_file = $this->Jobs_model->insert('candidate_files',$file_data);
             //echo $this->db->last_query();exit; 
            if($add_file){
                 $this->session->set_flashdata('tokbox_success',lang('files_saved_successfully'));
                 redirect('candidates/candidate_profile');
            }else{
                $this->session->set_flashdata('tokbox_error',lang('something_went_to_wrong'));
                redirect('candidates/candidate_profile');
            }
        }
             
       
    }

    public function account_setting(){
        if($this->input->post('save')){
            $this->form_validation->set_rules('email','','trim|required');
            $this->form_validation->set_rules('password','','trim|required');
            $this->form_validation->set_rules('c_password','','trim|required');
             if($this->form_validation->run()){
                if($this->input->post('c_password')!=$this->input->post('c_password')){
                    $this->session->set_flashdata('tokbox_error',lang('password_and_confirm_password_mismatch'));
                    redirect('candidates/candidate_profile'); 
                }
                $where['id'] =$this->session->userdata('user_id');
                $upd["password"] = md5($this->input->post('c_password'));
                $update = $this->Jobs_model->update('registered_candidates',$upd,$where);
              //  echo $this->db->last_query();exit; 
                if($update){
                    $this->session->set_flashdata('tokbox_success',lang('password_updated_successfully'));
                    redirect('candidates/candidate_profile'); 
                }else{
                    $this->session->set_flashdata('tokbox_error',lang('something_went_to_wrong'));
                    redirect('candidates/candidate_profile');
                }
             }else{
                $this->session->set_flashdata('tokbox_error',lang('some_feilds_missing'));
                redirect('candidates/candidate_profile');
             }
        }
    }

    public function send_applicantmail_preview()
    {
        $new_status = 3;
        $job_id = $this->input->post('job_id');
        $can_id = $this->input->post('can_id');
        $where_cond = array('title'=>$job_id,'candidate'=>$can_id);
        $offer_data = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $status_change = false;
        if(!empty($offer_data)){
            $status_change = true;
            $job_id =   $offer_data['title'];
            $can_id =   $offer_data['candidate'];
        }
        if($status_change)
        {   
            $message = App::email_template('offer_send','template_body');
            $subject = App::email_template('offer_send','subject');
            $signature = App::email_template('email_signature','template_body');

            $logo_link = create_email_logo();
            
            $job_data = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id)); 
            $user_data = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$can_id)); 

            $logo_link = create_email_logo();
            $message        =   str_replace("{INVOICE_LOGO}",$logo_link,$message);
            $message        =   str_replace("{SALARY}",$offer_data['salary'],$message);
            $message        =   str_replace("{JOB}",$job_data['job_title'],$message);
            $message        =   str_replace("{LINK}",base_url('candidates/dashboard'),$message);
            $message        =   str_replace("{USER}",rtrim($user_data['first_name'].' '.$user_data['last_name'],' '),$message);

            if(isset($_POST['sign']) && (empty($_POST['signature_type']) || $_POST['signature_type']!='upload_image')){
                $image_parts = explode(";base64,", $_POST['sign']);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
            
                $image_base64 = base64_decode($image_parts[1]);
                
            
                $file = 'assets/uploads/candidate_sign/'  .  uniqid() . '.' . $image_type;
                $req_file = FCPATH.$file;
                file_put_contents($req_file, $image_base64);
            }
            else{
                $config['upload_path']= "assets/uploads/candidate_sign/";
                $config['allowed_types']='gif|jpg|png';
                $this->load->library('upload',$config);
                if($this->upload->do_upload("file")){
                    $data = array('upload_data' => $this->upload->data());
                   $file = 'assets/uploads/candidate_sign/'.$data['upload_data']['file_name'];
                }
            }
            $file_url = base_url().$file;

            $sign_img = "<img src='".$file_url."' style='width:200px;'>";

            $message        =   str_replace("{CANDIDATE_SIGNATURE}",$sign_img,$message);

            
            $file_url       =   base_url().$offer_data['signature'];
            $sign_img       =   "<img src='".$file_url."' style='width:200px;'>";
            $message        =   str_replace("{ADMIN_SIGNATURE}",$sign_img,$message);

            echo json_encode( array('msg' =>'success' ,'response'=>'ok','content'=>$message,'img'=>$file,'offer_id'=>$offer_id) );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }

    }
    public function send_offer_candidate()
    {
        $new_status = 3;
        $job_id = $this->input->post('job_id');
        $can_id = $this->input->post('can_id');
        $ch_asso_id_img = $this->input->post('ch_asso_id_img');
        $where_cond = array('title'=>$job_id,'candidate'=>$can_id);
        $offer_data = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $status_change = false;
        if(!empty($offer_data)){
            $status_change = $this->db->set(array('candidate_signature'=>$ch_asso_id_img))->where($where_cond)->update('offers');
        }
        
        
        if($status_change)
        {   
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }
    }

	public function offered()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('view_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $where['candidate_id'] = $this->session->userdata('user_id');
        $where['user_job_status >='] = 7; /*offered job*/
        //$where['user_job_status <'] = 9; /*offered job*/
        $data['offered_jobs'] = $this->Jobs_model->select_offered_jobs($where);

        $this->template
                ->set_layout('users')
                ->build('offered', isset($data) ? $data : null);
    }


    function download_offer(){
       
        $this->applib->set_locale();
        $data['name'] = 'test';
        $job_data = $this->Jobs_model->select_row_array('jobs',array('id'=>$this->uri->segment(3))); 
        $user_data = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$this->session->userdata('user_id'))); 
        $where_cond = array('candidate'=>$this->session->userdata('user_id'),'title'=>$this->uri->segment(3));
        $offer = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $message        =   App::email_template('offer_send','template_body');
        $logo_link      =   create_email_logo();
        
        $message        =   str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $message        =   str_replace("{SALARY}",$offer['salary'],$message);
        $message        =   str_replace("{JOB}",$job_data['job_title'],$message);
        $message        =   str_replace("{LINK}",base_url('candidates/dashboard'),$message);
        $message        =   str_replace("{USER}",rtrim($user_data['first_name'].' '.$user_data['last_name'],' '),$message);

        $file_url = base_url().$offer['signature'];
        $sign_img = "<img src='".$file_url."' style='width:200px;'>";
        $message        =   str_replace("{ADMIN_SIGNATURE}",$sign_img,$message);

        $file_url = base_url().$offer['candidate_signature'];
        $sign_img = "<img src='".$file_url."' style='width:200px;'>";
        $message        =   str_replace("{CANDIDATE_SIGNATURE}",$sign_img,$message);

        //$html = $this->load->view('candidates/offer_letter', $data, true);
        $html = $message;

        $pdf = array(
        'html' => $html,
        'title' => lang('invoice'),
        'author' => config_item('company_name'),
        'creator' => config_item('company_name'),
        'filename' => 'offer_letter.pdf',
        'badge' => config_item('display_invoice_badge'),
    );

        $this->applib->create_pdf($pdf);
        // $html = $this->output->get_output();
        
        // // Load pdf library
        // $this->load->library('pdf');
        
        // // Load HTML content
        // $this->pdf->loadHtml('hellow world');
        
        // // (Optional) Setup the paper size and orientation
        // $this->pdf->setPaper('A4', 'landscape');
        
        // // Render the HTML as PDF
        // $this->pdf->render();
        
        // // Output the generated PDF (1 = download and 0 = preview)
        // $this->pdf->stream("welcome.pdf", array("Attachment"=>1));
    }

     function user_offer_status_change()
    {
      $status = $this->uri->segment(3); /* current status based on admin approval*/
      $user_id =  $this->uri->segment(4); /* candidate id*/
      $where['job_id'] =  $this->uri->segment(5); /* job id*/
      $where['candidate_id'] = $user_id;
      $result =  $this->Jobs_model->update('candidate_job_status',array('user_job_status'=>$status),$where);
      if($status == 8){//accept
        $this->db->set(array('offer_status'=>4))->where(array('candidate'=>$where['candidate_id'],'title'=>$where['job_id']))->update('dgt_offers');
      }
      else if($status == 9){
        $this->db->set(array('offer_status'=>5))->where(array('candidate'=>$where['candidate_id'],'title'=>$where['job_id']))->update('dgt_offers');
      }
     if($result){
        $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
      
           redirect('candidates/offered');
        
     }else{
        $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
       
           redirect('candidates/offered');
        
     }
      

    }
	public function visited()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('visited');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['job_categories'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['countries'] = $this->Jobs_model->select('countries'); /* to fetch countries */
        $data['experience_level'] = $this->Jobs_model->select('experience_level',array('status'=>1));
        $where['jobs.deleted_at'] = NULL;
        $where['jobs.expired_date>='] = date('Y-m-d'); 
        if($this->input->post('search')){
            $like= NULL; $where= NULL;
            if($this->input->post('experience_level')!=""){
                 $where['experience_level_id'] = $this->input->post('experience_level');   
            }
            if($this->input->post('department_id')!=""){
                $where['department_id'] = $this->input->post('department_id');    
            }
            if($this->input->post('country_id')!=""){
                $where['country_id'] = $this->input->post('country_id');    
            }
            if($this->input->post('keywords')!=""){
                $like= $this->input->post('keywords');    
            }
        
            $where['candidate_visited_jobs.candidate_id'] = $this->session->userdata('user_id');
            // $where['cjob.status'] =1;
          
            $data['visited_jobs'] = $this->Jobs_model->select_visited_jobs_filter($where,$like);
            // echo $this->db->last_query();exit; 
            
        }else{
             $where['candidate_visited_jobs.candidate_id'] = $this->session->userdata('user_id');
             // $where['cjob.status'] =1;
             $data['visited_jobs'] = $this->Jobs_model->select_visited_jobs($where);
             // echo $this->db->last_query();exit; 
        }
        $this->template
                ->set_layout('users')
                ->build('visited', isset($data) ? $data : null);
    }
    public function archived()
    {
         $this->login_valid();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('archived');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['archive_jobs'] = $this->Jobs_model->select_jobs_status_list(array('candidate_job_status.status'=>3,'candidate_id'=>$this->session->userdata('user_id'),'jobs.deleted_at'=>NULL,'jobs.expired_date>='=>date('Y-m-d')));      
        $this->template
                ->set_layout('users')
                ->build('archived', isset($data) ? $data : null);
    }
    function delete_job(){
        

        if($this->input->post('submit')){
            $where['id'] = $this->input->post('delete_id');
            $this->Jobs_model->delete('candidate_job_status',$where);
            $this->session->set_flashdata('tokbox_success', lang('job_deleted_successfully'));
            redirect('candidates/'.$this->input->post('page_name'));
         }
    }
    public function aptitude()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('aptitude');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages']  = App::languages();

        $where['dept_id']   = $dept_id=  $this->uri->segment(3); /*department id*/
        $where['job_id']   =  $job_id = $this->uri->segment(4);  /* job id */
        $data['categories'] = $this->Jobs_model->select('question_category',$where);
        if(count($data['categories'])==0){
              $this->session->set_flashdata('tokbox_error', lang('test_not_allocated'));
            redirect('candidates/interviewing');
        }

        //print_r($_SESSION);exit; 
        $data['job_detail'] = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id));


        $this->template
                ->set_layout('users')
                ->build('aptitude', isset($data) ? $data : null);
    }
    public function questions()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('aptitude');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        /* to fetch result */
        $cond['user_id']  =$this->session->userdata('user_id'); 
        $cond['job_id'] = $this->uri->segment(4);
        $cond['category_id'] = $this->uri->segment(5);
        
        $results =  $this->Jobs_model->select('apptitude_interview_results',$cond);

        $cond1['job_id'] = $this->uri->segment(4);
        $cond1['category_id'] = $this->uri->segment(5);
        $results_ques =  $this->Jobs_model->select('interview_questions',$cond1);
        
        $data['result_display']  =  $this->Jobs_model->select_numrows('apptitude_interview_results',$cond);
        

        if(count($results) && count($results_ques) && count($results) == count($results_ques)){
        $total_mark = array();
        $total_correct = array();
        $total_wrong = array();
        foreach ($results as $key => $value) {
            $total_mark [] = $value->id;
            if($value->answer_status==1){
                $total_correct[] = $value->id;
            }else{
                $total_wrong[] = $value->id;
            }
        }
        $data['total_mark'] =  count($total_mark);
        $data['total_correct'] =  count($total_correct);
        $data['total_wrong'] =  count($total_wrong);
        }
        /**/

        $where['dept_id']       =  $dept_id = $this->uri->segment(3); /*department id */
        $where['job_id']        =  $job_id = $this->uri->segment(4); /*job id*/
        $where['category_id']   =  $category_id = $this->uri->segment(5); /*question category*/
        $where['deleted_at']    = NULL;
        $where['status']    = 1;
        $data['questions_list']  = $this->Jobs_model->select('interview_questions',$where);
        if(count($data['questions_list'])==0){
            $this->session->set_flashdata('tokbox_error', lang('questions_not_enable'));
            redirect('candidates/aptitude/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
        }
        $this->template
                ->set_layout('users')
                ->build('questions', isset($data) ? $data : null);
    }

    function save_user_exam(){
 
        if($this->input->post('finish')){

            $where['user_id']  =$this->session->userdata('user_id'); 
            $where['job_id'] = $this->input->post('job_id');
            $where['category_id'] = $this->input->post('category_id');
            $already =  $this->Jobs_model->select('apptitude_interview_results',$where);

            $cond1['job_id'] =  $where['job_id'];
            $cond1['category_id'] =  $where['category_id'];
            $results_ques =  $this->Jobs_model->select('interview_questions',$cond1);
            //echo count($already);exit; 
            if(count($already)>= count($results_ques)){
            $this->session->set_flashdata('tokbox_error', lang('already_completed_test'));
            redirect($this->input->post('url'));
            }

           $questions =  $this->input->post('question_id');
           $user_answer =  $this->input->post('answer');
           $get_correct_answers =  $this->Jobs_model->select_where_in('interview_questions',$questions,'id');/*get question answers*/

           foreach ($get_correct_answers as $key => $quest) {
               $correct_ans[$quest['id']] = $quest['answer'];
               $question_type[$quest['id']] = $quest['question_type'];
           }

           //print_r($correct_ans);echo "<pre>";
           
            $ins['user_id']  =$this->session->userdata('user_id'); 
            $ins['job_id'] = $this->input->post('job_id');
            $ins['category_id'] = $this->input->post('category_id');
           foreach($questions as $key => $qst_id){
                $ins['question_id']  = $qst_id;
                $ins['question_type']  = $question_type[$qst_id];
                if(isset($user_answer[$qst_id])){  /* user attended questions */
                     $ins['user_answer']  = $user_answer[$qst_id];
                    if($correct_ans[$qst_id] == $user_answer[$qst_id]){
                        $status = 1; /*correct answer*/
                    }else{
                        $status = 0;
                    } 
              /*  }else{  
                    $ins['user_answer']  = "";
                    $status = 0;
                }
                if($question_type[$qst_id] !=1){
                    $ins['answer_status']  = NULL;
                }else{
                    $ins['answer_status']  = $status;

                }*/
               
                $ins['answer_status']  = $status;

                $where = array();
                $where['user_id']  =$this->session->userdata('user_id'); 
                $where['job_id'] = $this->input->post('job_id');
                $where['category_id'] = $this->input->post('category_id');
                $where['question_id'] = $qst_id;
                $already =  $this->Jobs_model->select('apptitude_interview_results',$where);
                if(count($already) > 0){
                    $saved = $this->Jobs_model->update('apptitude_interview_results',$ins,$where);
                }
                else{
                    $saved = $this->Jobs_model->insert('apptitude_interview_results',$ins);
                }
                }
            }
        }
            else if(isset($_POST['next']))
            {

                $where['user_id']  =$this->session->userdata('user_id'); 
                $dept_id  =$this->input->post('dept_id'); 
                $where['job_id'] = $this->input->post('job_id');
                $where['category_id'] = $this->input->post('category_id');
                $already =  $this->Jobs_model->select('apptitude_interview_results',$where);
                $cond1['job_id'] =  $where['job_id'];
                $cond1['category_id'] =  $where['category_id'];
                $results_ques =  $this->Jobs_model->select('interview_questions',$cond1);
                //echo count($already);exit; 
                if(count($already)>= count($results_ques)){
                $this->session->set_flashdata('tokbox_error', lang('already_completed_test'));
                redirect($this->input->post('url'));
                }
    
               $questions =  $this->input->post('question_id');
               $user_answer =  $this->input->post('answer');
             //print_r($_POST);echo "<pre>";exit; 
               $get_correct_answers =  $this->Jobs_model->select_where_in('interview_questions',$questions,'id');/*get question answers*/
    
               foreach ($get_correct_answers as $key => $quest) {
                   $correct_ans[$quest['id']] = $quest['answer'];
                   $question_type[$quest['id']] = $quest['question_type'];
               }
    
               //print_r($correct_ans);echo "<pre>";
               
                $ins['user_id']  =$this->session->userdata('user_id'); 
                $ins['job_id'] = $this->input->post('job_id');
                $ins['category_id'] = $this->input->post('category_id');
                foreach($questions as $key => $qst_id){
                    $ins['question_id']  = $qst_id;
                    $ins['question_type']  = $question_type[$qst_id];
                    if(isset($user_answer[$qst_id])){  /* user attended questions */
                         $ins['user_answer']  = $user_answer[$qst_id];
                        if($correct_ans[$qst_id] == $user_answer[$qst_id]){
                            $status = 1; /*correct answer*/
                        }else{
                            $status = 0;
                        } 
                  /*  }else{  
                        $ins['user_answer']  = "";
                        $status = 0;
                    }
                    if($question_type[$qst_id] !=1){
                        $ins['answer_status']  = NULL;
                    }else{
                        $ins['answer_status']  = $status;
    
                    }*/
                    $ins['answer_status']  = $status;
                    $where = array();
                    $where['user_id']  =$this->session->userdata('user_id'); 
                    $where['job_id'] = $this->input->post('job_id');
                    $where['category_id'] = $this->input->post('category_id');
                    $where['question_id'] = $qst_id;
                    $already =  $this->Jobs_model->select('apptitude_interview_results',$where);
                    if(count($already) > 0){
                        $saved = $this->Jobs_model->update('apptitude_interview_results',$ins,$where);
                    }   
                    else{
                        $saved = $this->Jobs_model->insert('apptitude_interview_results',$ins);
                    }
                    }
                }
            }
            
           if($saved){
                if($this->input->post('finish')){
                    $this->session->set_flashdata('tokbox_success', lang('test_completed_successfully'));
                
                    redirect($this->input->post('url'));
                }
                else{
                    $num_count = $this->input->post('num_count') + 1;
                    redirect('candidates/questions/'.$dept_id.'/'.$where['job_id'].'/'.$ins['category_id'].'?step='.$num_count);
                    exit;
                }
            }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
                redirect($this->input->post('url'));
            }
        }   
    

    public function video_test()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('my_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('video_test');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

      
        $this->template
                ->set_layout('users')
                ->build('video_test', isset($data) ? $data : null);
    }

    function offerlistload()
    {
        return Offer::to_where(array('user_id'=>'1'));
    }
    function _jobtypename()
    {
        return Offer::job_where(array('user_id'=>'1'));
    }


    function time_elapsed_string($datetime, $full = false) {
        $current =    date("Y-m-d H:i:s");
    $now = new DateTime($current);
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function apply($jid=false,$jtype=false)
    {
        if($jid==false || $jtype==false)
        {
            header("Location: ".base_url('jobs'));
        }
         $this->load->module('layouts');
            $this->load->library('Template');

            $data['jobs'] = $this->offerlistload();
            $data['offer_jobtype'] = $this->_jobtypename();


 
       
        $this->template
                ->set_layout('login')
                ->build('jobview',isset($data)?$data:NULL);
    }

}
/* End of file contacts.php */
