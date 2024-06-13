<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Offers extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation','tank_auth'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer','Jobs_model'));
        $all_routes = $this->session->userdata('all_routes');
        //echo '<pre>'; print_r($all_routes); exit;
        foreach($all_routes as $key => $route){
            if($route == 'offers'){
                $routname = "offers";
            } 
            
        }
        // if (!User::is_admin())
        
        if(empty($routname)){
            // $this->session->set_flashdata('message', lang('access_denied'));
            // $this->session->set_flashdata('tokbox_error', lang('access_denied'));
            // redirect('');
        }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    public function index()
    {
       
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('offer_page_title').' - '.config_item('company_name'));
        $data['page'] = lang('offer_dashboard');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
		if(!App::is_access('menu_offer_dashboard'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
			redirect('');
		}

	
        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();

        $applicant_status= Offer::get_CandStatus($inprogress); 
       // echo '<pre>';print_r($applicant_status);exit;

        foreach ($applicant_status as $apk => $apv) 
        {
                // $offer_approvers_status   = $this->db->get_where('offer_approvers',array('status'=>1,'offer'=>$apv['id']))->num_rows();
                //             if($offer_approvers_status != 0){
                //               $status = 1;
                //             }else {

                //                 $offer_status   = $this->db->get_where('offers',array('id'=>$apv['id']))->row_array();
                //                 $status = $offer_status['offer_status'];
                                
                //             }


            if($apv['offer_status']==2){
                 $data['ready'][]= array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                    // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']

                );

            }
            elseif($apv['offer_status']==3){
                $data['send'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                    // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            elseif($apv['offer_status']==4){
               $data['accept'][]= array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            } 
            elseif($apv['offer_status']==5){
               $data['declined'][]= array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            elseif($apv['offer_status']==6){
                $data['archived'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            elseif($apv['offer_status']==7){
                $data['rejected'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            } 
            else{
                 $data['inprogress'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                    // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            
        }
        $this->template
                ->set_layout('users')
                ->build('offers', isset($data) ? $data : null);
    }

    public function view($company = null)
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('companies').' - '.config_item('company_name'));
        $data['page'] = lang('companies');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['editor'] = true;
        $data['tab'] = ($this->uri->segment(4) == '') ? 'dashboard' : $this->uri->segment(4);
        $data['company'] = $company;

        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

    public function create()
    {
		if(!App::is_access('menu_offer_creation'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
			redirect('');
		} 
		
         $activity_user = User::displayName($this->session->userdata('user_id'));
        if ($this->input->post()) {
             //print_r($this->input->post());exit;
            // print_r($this->session->all_userdata()); exit;
            // $custom_fields = array();
            // foreach ($_POST as $key => &$value) {
            //     if (strpos($key, 'cust_') === 0) {
            //         $custom_fields[$key] = $value;
            //         unset($_POST[$key]);
            //     }
            // }
            // $this->form_validation->set_rules('title', 'title', 'required');
            // $this->form_validation->set_rules('job_type', 'Job Type', 'required');
            // $this->form_validation->set_rules('Salary   ', 'Salary', 'required');

            // if ($this->form_validation->run() == false) {
            //     $_POST = '';
            //     // echo "<pre>";print_r("df"); exit;
            //             // $errors = validation_errors();
            //             // Applib::go_to('companies', 'error', lang('error_in_form'));
            //             $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
            //             redirect('offers');
            // } else {
                $_POST['user_id'] = $this->session->userdata('user_id');
                $offer_approvers = serialize($this->input->post('offer_approvers'));
                $_POST['offer_approvers'] = $offer_approvers;
				$_POST['offer_status']	= 2;
                  

                $offer_id = Offer::save($this->input->post(null, true));

                $req_where['candidate_id']  =   $_POST['candidate'];
                $req_where['job_id']  =   $_POST['title'];

                //$this->db->set(array('user_job_status'=>7))->where($req_where)->update('dgt_candidate_job_status');
 
                $approvers = unserialize($offer_approvers);
                $user_mail= Offer::usersMailid($approvers);
                if (count($approvers) > 0) {
                    $i = 1;
                    foreach ($approvers as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'offer' => $offer_id,
                            //'status' => 0,
                            'created_by'=>$this->session->userdata('user_id'),
                            //'lt_incentive_plan' => ($this->input->post('long_term_incentive_plan')?1:0),

                            );//print_r($approvers_details);exit;
                         if($this->input->post('default_offer_approval') == "seq-approver"){
                                /*if($i ==1){
                                    $approvers_details['view_status'] = 1;
                                } else{
                                    $approvers_details['view_status'] = 0;
                                }   */
                                $approvers_details['view_status'] = 1;
                                $approvers_details['status'] = 0;
                            }else{
                                $approvers_details['status'] = 1;
                                $approvers_details['view_status'] = 1;
                            }
                      // $this->db->insert('dgt_leave_approvers',$approvers_details);
                        Offer::save_offer_approvers($approvers_details);

                        if($this->input->post('default_offer_approval') == "seq-approver"){
                            if($i ==1){ 
                        $args = array(
                            'user' => $value,
                            'module' => 'offers/offer_approvals',
                            'module_field_id' => $offer_id,
                            'activity' => 'Offer Approval Request by '.ucfirst($activity_user),
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('title', true),
                        );
                        App::Log($args);
                        }
                          
                     }
                     if($this->input->post('default_offer_approval') != "seq-approver"){
                            
                        $args = array(
                            'user' => $value,
                            'module' => 'offers/offer_approvals',
                            'module_field_id' => $offer_id,
                            'activity' => 'Offer Approval Request by '.ucfirst($activity_user),
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('title', true),
                        );
                          App::Log($args);
                        
                     }
                    $i++;
                    }
                        $subject = 'Offer approval';
                        $message = 'Offer approval Request';
                        foreach ($user_mail as $key => $u) 
                        {
                            
                            $params['recipient'] = $u['email'];
                            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                            $params['message'] = $message;
                            $params['attached_file'] = '';
                            Modules::run('fomailer/send_email',$params);
                        }
                }
                

                // foreach ($custom_fields as $key => $f) {
                //     $key = str_replace('cust_', '', $key);
                //     $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                //     $cf = $this->db->where('name',$key)->get('fields');
                //     $data = array(
                //         'module'    => 'clients',
                //         'field_id'  => $cf->row()->id,
                //         'client_id' => $company_id,
                //         'meta_key'  => $cf->row()->name,
                //         'meta_value'    => is_array($f) ? json_encode($f) : $f
                //     );
                //     ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                // }

               
                // $args = array(
                //             'user' => User::get_id(),
                //             'module' => 'Offers',
                //             'module_field_id' => $offer_id,
                //             'activity' => 'Offer Approval Request by '.ucfirst($activity_user),
                //             'icon' => 'fa-user',
                //             'value1' => $this->input->post('title', true),
                //         );
                // App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('client_registered_successfully'));
                $this->session->set_flashdata('tokbox_success', lang('offer_created_successfully'));
                redirect('offers');
            // }
        } else {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('create_offer').' - '.config_item('company_name'));
        $data['page'] = lang('create_offer');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('create', isset($data) ? $data : null);
        }
    }

    public function create_offer($candidate_id,$job_id)
    {
    
        $query = "SELECT * from dgt_candidate_job_status where job_id = '".$job_id."'  and candidate_id = '".$candidate_id."'";
	    $check_job = $this->db->query($query)->row_array();

        if(empty(($check_job))){
            redirect('');
            exit;
        }

		$data['candidate_id'] = $candidate_id;
		$data['job_id'] = $job_id;

        $query = "SELECT * from dgt_registered_candidates where id = '".$candidate_id."' ";
	    $data['req_candidate'] = $this->db->query($query)->row_array();
        $query = "SELECT * from dgt_jobs where id = '".$job_id."' ";
	    $data['req_job'] = $this->db->query($query)->row_array();
		if(!App::is_access('menu_offer_creation'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
			redirect('');
		} 
		
		
         $activity_user = User::displayName($this->session->userdata('user_id'));
        if ($this->input->post()) {
                $_POST['user_id'] = $this->session->userdata('user_id');
                $offer_approvers = serialize($this->input->post('offer_approvers'));
                $_POST['offer_approvers'] = $offer_approvers;
				$_POST['offer_status']	= 2;
                  

                $offer_id = Offer::save($this->input->post(null, true));

                $req_where['candidate_id']  =   $_POST['candidate'];
                $req_where['job_id']  =   $_POST['title'];

               // $this->db->set(array('user_job_status'=>7))->where($req_where)->update('dgt_candidate_job_status');
 
                $approvers = unserialize($offer_approvers);
                $user_mail= Offer::usersMailid($approvers);
                if (count($approvers) > 0) {
                    $i = 1;
                    foreach ($approvers as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'offer' => $offer_id,
                            'status' => 0,
                            'created_by'=>$this->session->userdata('user_id'),
                            //'lt_incentive_plan' => ($this->input->post('long_term_incentive_plan')?1:0),

                            );//print_r($approvers_details);exit;
                         if($this->input->post('default_offer_approval') == "seq-approver"){
                               /* if($i ==1){
                                    $approvers_details['view_status'] = 1;
                                } else{
                                    $approvers_details['view_status'] = 0;
                                }   */
                                $approvers_details['view_status'] = 1;
                            }else{
                                $approvers_details['view_status'] = 1;
                            }
                      // $this->db->insert('dgt_leave_approvers',$approvers_details);
                        Offer::save_offer_approvers($approvers_details);

                        if($this->input->post('default_offer_approval') == "seq-approver"){
                            if($i ==1){ 
                        $args = array(
                            'user' => $value,
                            'module' => 'offers/offer_approvals',
                            'module_field_id' => $offer_id,
                            'activity' => 'Offer Approval Request by '.ucfirst($activity_user),
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('title', true),
                        );
                        App::Log($args);
                        }
                          
                     }
                     if($this->input->post('default_offer_approval') != "seq-approver"){
                            
                        $args = array(
                            'user' => $value,
                            'module' => 'offers/offer_approvals',
                            'module_field_id' => $offer_id,
                            'activity' => 'Offer Approval Request by '.ucfirst($activity_user),
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('title', true),
                        );
                          App::Log($args);
                        
                     }
                    $i++;
                    }
                        $subject = 'Offer approval';
                        $message = 'Offer approval Request';
                        foreach ($user_mail as $key => $u) 
                        {
                            
                            $params['recipient'] = $u['email'];
                            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                            $params['message'] = $message;
                            $params['attached_file'] = '';
                            Modules::run('fomailer/send_email',$params);
                        }
                }
                
                $this->session->set_flashdata('tokbox_success', lang('offer_created_successfully'));
                redirect('offers');
            // }
        } else {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('create_offer').' - '.config_item('company_name'));
        $data['page'] = lang('create_offer');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('create_offer', isset($data) ? $data : null);
        }
    }

    public function check_candidate()
    {
        $candidate = $this->input->post('candidate');
        $check_candidate = Offer::check_candidate($candidate);
        if($check_candidate > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    

    public function update($company = null)
    {
        if ($this->input->post()) {

            $custom_fields = array();
            foreach ($_POST as $key => &$value) {
                if (strpos($key, 'cust_') === 0) {
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
            $this->form_validation->set_rules('company_ref', 'Company ID', 'required');
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('company_email', 'Company Email', 'required|valid_email');

            if ($this->form_validation->run() == false) {
                // $this->session->set_flashdata('response_status', 'error');
                // $this->session->set_flashdata('message', lang('error_in_form'));
                $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
                $company_id = $_POST['co_id'];
                $_POST = '';
                redirect('companies/view/'.$company_id);
            } else {
                $company_id = $_POST['co_id'];

                foreach ($custom_fields as $key => $f) {
                    $key = str_replace('cust_', '', $key);
                    $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                    $cf = $this->db->where('name',$key)->get('fields');
                    $data = array(
                        'module'    => 'clients',
                        'field_id'  => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key'  => $cf->row()->name,
                        'meta_value'    => is_array($f) ? json_encode($f) : $f
                    );
                    ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                }

                $_POST['company_website'] = prep_url($_POST['company_website']);
                Client::update($company_id, $this->input->post());

                $args = array(
                            'user' => User::get_id(),
                            'module' => 'Clients',
                            'module_field_id' => $company_id,
                            'activity' => 'activity_updated_company',
                            'icon' => 'fa-edit',
                            'value1' => $this->input->post('company_name', true),
                        );
                App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('client_updated'));
                $this->session->set_flashdata('tokbox_success', lang('client_updated'));
                redirect('companies/view/'.$company_id);
            }
        } else {
            $data['company'] = $company;
            $this->load->view('modal/edit', $data);
        }
    }


    
            // Delete Company
    public function delete()
    {
        if ($this->input->post()) {
            $company = $this->input->post('company', true);

            Client::delete($company);

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('company_deleted_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('company_deleted_successfully'));
            redirect('companies');
        } else {
            $data['company_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete', $data);
        }
    }

    public function get_approvers()
    {
        $approvers = User::team();

        

         $data=array();
            foreach($approvers as $r)
            {
                $data['value']=$r->id;
                $data['label']=ucfirst(User::displayName($r->id));
                $json[]=$data;
                
                
            }
        echo json_encode($json);
        exit;
    }


    public function offers_list()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        // $this->template->title(lang('offer_approval_process'));
        $this->template->title(lang('offer_list').' - '.config_item('company_name'));
        $data['page'] = lang('offer_list');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
       // $data['offer_list'] = $this->_getOfferlist();
        $data['offer_list'] = Offer::get_CandStatus();
        // echo " <pre>"; print_r($data['offer_list']); exit;
         $data['offer_jobtype'] = $this->_getOfferjob();
		
		
		if(!App::is_access('menu_offerlist'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
			redirect('');
		} 
		

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('lists', isset($data) ? $data : null);
    }
    public function ajax_offer_list(){
        $page   = $_GET['start'];
		$limit  = $_GET['length'];
        $offer_jobtype = $this->_getOfferjob();
        $jtype=array(0=>'unassigned');
        foreach ($offer_jobtype as $jkey => $jvalue) {
           $jtype[$jvalue->id]=$jvalue->job_type;                        
         } 

        $this->load->helper('text');
        $all_offer_jobs = Offer::get_CandStatus();
        $offer_jobs = Offer::get_CandStatus_limit();

        $return_data['recordsTotal'] = $return_data['recordsFiltered'] = count($all_offer_jobs);
        if(!empty($offer_jobs)){
            $i = 0; 
            foreach ($offer_jobs as $key => $t) {
                $s_label = 'In-progress';
                if($t['app_status'] == 2) $s_label = 'Approved';
                if($t['app_status'] == 3) $s_label = 'Send offer';
                if($t['app_status'] == 4) $s_label = 'Offer accepted';
                if($t['app_status'] == 5) $s_label = 'Declined'; 
                if($t['app_status'] == 6) $s_label = 'Onboard'; 
                $query = "SELECT * from dgt_jobs where id = '".$t['title']."' ";
	            $req_job = $this->db->query($query)->row_array();
                $return_data['data'][$i]['title']		    = ucfirst($req_job['job_title']);
                $return_data['data'][$i]['job_type']	    = ucfirst($jtype[$t['job_type']]);
                $return_data['data'][$i]['salary']	    	= $t['salary'];

                $return_data['data'][$i]['label']	    	= $s_label; 
                $i++;

            }
        }
        echo json_encode($return_data);exit;

    }
    public function onboarding($offer_id = '')
    {
        if ($this->input->post()) {
            $email_activation = config_item('email_activation');
             $hasher = new PasswordHash(
                $this->config->item('phpass_hash_strength', 'tank_auth'),
                $this->config->item('phpass_hash_portable', 'tank_auth'));
            $req_password = $hasher->HashPassword($this->input->post('password'));

            $user_data = array(
                            'username' =>$this->input->post('username'),
                            'password' =>$req_password,
                            'email' =>$this->input->post('employee_email'),
                            'user_type' =>$this->input->post('user_type'),
                            'role_id' =>3,
                            'designation_id' =>$this->input->post('designations'),
                            'department_id' =>$this->input->post('department_name'),
                            );

            $user_id = offer::add_user($user_data);
            if($this->input->post('branch_prefix') != '') {
                $emp_code	= $this->input->post('branch_prefix').$this->input->post('employee_id');
            } else {
                $emp_code	= $this->input->post('employee_id');
            }

            $account_data = array(
                'user_id' =>$user_id,
                'fullname' =>$this->input->post('fullname'),
                'doj' =>$this->input->post('emp_doj'),
                'branch_id' =>$this->input->post('branch'),
                'department' =>$this->input->post('department'),
                'type_change' =>$this->input->post('type_change'),
                'employee_id' => $this->input->post('employee_id'),
                'emp_code' => $emp_code,
                );
            offer::add_account_user($account_data);
            $_POST['user_id'] = $this->session->userdata('user_id');
            $boardsid = $this->input->post('boarders_id');
           // $offer_id = $this->input->post('offer_id');
            $boarders_id = serialize($boardsid);

            $_POST['boarders_id'] = $boarders_id;
            $user_mail= Offer::usersMailid($boardsid);
            $offer_details = Offer::getjobbyid($offer_id);               $this->session->set_flashdata('tokbox_success', lang('mail_sent_successfully'));
            
            $del_cond = array('id'=>$offer_id);
            $this->db->where($del_cond);
		    $this->db->delete('dgt_offers');

            $del_cond = array('candidate_id'=>$offer_details['candidate'],'job_id'=>$offer_details['title']);
            $this->db->where($del_cond);
		    $this->db->delete('dgt_candidate_job_status');

            $subject = 'Onboarding';
            $message  = '<div style="height: 7px; background-color: #535353;"></div>
                            <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Employee Onboarding</div>
                                <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                    <p> Hello All,</p>
                                    <p>Here we have two new Team Members!</p>
                                    <p>I am happy to introduce our new employee who have joined '.config_item('company_name').'. The employee details are provided below for your reference.  </p>  
                                    <p><b>Name : '.$offer_details["candidate"].'</b></p>
                                    <p><b>Designation : '.$offer_details["title"].'</b></p>
                                    <p>Kindly provide your professional support to the new employees. </>            
                                    <br> 
                                    
                                    &nbsp;&nbsp;                                     
                                    <br>
                                    </big><br><br>Regards<br>The '.config_item('company_name').' Team
                                </div>
                         </div>'; 
            foreach ($user_mail as $key => $u) 
            {
                
                $params['recipient'] = $u['email'];
                $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';
                Modules::run('fomailer/send_email',$params);
            }
            redirect('offers');
        }else {
        $this->load->module('layouts');
        $this->load->library('template');
        // $this->template->title(lang('offer_approval_process'));
        $this->template->title('Onboarding'.' - '.config_item('company_name'));
        $data['page'] = lang('offer_dashboard');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['offer_id'] =  $offer_id = $this->uri->segment(3);
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['offer_list'] = $this->_getOfferlist();
        $data['offer_jobtype'] = $this->_getOfferjob();
        // echo '<pre>'; print_r($data['offer_list']);exit();
        $data['jobs']  = $this->db->query("SELECT * FROM dgt_jobs where id = ".$data['offer_list'][0]->id." ")->row_array();
        $data['designations']  = $this->db->query("SELECT * FROM dgt_designation where department_id = ".$data['jobs']['department_id']." ")->result_array();
        $cur_offer = Offer::getjobbyid($offer_id);
        $candidate_details = Offer::get_candidate_by_id($cur_offer['candidate']);

        $data['full_name'] = (!empty($candidate_details) && ((!empty($candidate_details['first_name'])) || !empty($candidate_details['last_name'])) )?ltrim($candidate_details['first_name'].' '.$candidate_details['last_name'],' '):'';
       
        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('onboarding', isset($data) ? $data : null);
            }
    }

     function _getOfferlist()
     {
         return Offer::to_where(array('user_id'=>'1'));
     }

     function _getOfferjob()
     {
         return Offer::job_where(array('user_id'=>'1'));
     }
     function _getOfferjob_limit()
     {
         return Offer::job_where_limit(array('user_id'=>'1'));
     }

    public function joblist()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        // $this->template->title(lang('offer_approval_process'));
        $this->template->title('Offers List');
        $data['page'] = lang('offers');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['offer_list'] = $this->_getOfferlist();
        $data['offer_jobtype'] = $this->_getOfferjob();


        $data['countries'] = App::countries();
        $this->template
             ->set_layout('users')
             ->build('joblists', isset($data) ? $data : null);
        // $this->load->view('joblists');
    }

  /*   public function offers_status()
    {
        $data['offer_list'] = $this->_getOfferlist();
        $data['offer_jobtype'] = $this->_getOfferjob();


        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('lists', isset($data) ? $data : null);
    }*/

    public function offer_approvals()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('offer_approval').' - '.config_item('company_name'));
        $user_id = $this->session->userdata['user_id'];
        $data['candi_list'] = Offer::approve_candidate($user_id);
        $data['offer_jobtype'] = $this->_getOfferjob();
        $data['currencies'] = App::currencies();
        $data['datatables'] = true;
        $data['page'] = lang('offer_approval');
//$data['countries'] = App::countries();
      // echo "<pre>"; print_r($data['candi_list']);exit();
        if(!App::is_access('menu_offer_approval'))
        {
        $this->session->set_flashdata('tokbox_error', lang('access_denied'));
        redirect('');
        } 
		$this->template->set_layout('users')->build('offer_approval', isset($data) ? $data : null);


    }
    public function ajax_offer_approvals(){
        $page   = $_GET['start'];
		$limit  = $_GET['length'];
        $curren_type = array();
        foreach ($currencies as $curren){ 
        $curren_type[$curren->code] = $curren->symbol;
        }
        $plan_percent = array();
        foreach (User::get_annual_incentive_plans() as $plans =>$plan){
        $plan_percent[$plan['id']] = ucfirst(trim($plan['plan']));
        } 
        $vocation_name = array();
        foreach (User::get_vocations() as $vocations =>$vocation){
        $vocation_name[$vocation['id']] = ucfirst(trim($vocation['vocation']));
        }
        $i=0;
        $user_id = $this->session->userdata['user_id'];
        $all_candi_lists = Offer::approve_candidate($user_id);
        $return_data['recordsTotal'] = $return_data['recordsFiltered'] = count($all_candi_lists);
        $candi_lists = Offer::approve_candidate_limit($user_id);
        if(!empty($candi_lists)){
            $i = 0;
            foreach($candi_lists as $ck => $cv){
                $j = $page + $i + 1;
                $s_label = 'Requested';$s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='success'; $color='#b31109';
                $clr_class='warning'; $title="Click to Approve";
                if($cv->approver_status == 1) { 
				    $s_label = 'Rejected'; $s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='danger'; $color='#b31109';
                    $clr_class='danger'; $title="Click to Approve";
                }
                if($cv->approver_status == 2) { 
                    $s_label = 'Approved'; $s_label2 = '<i class="fa fa-thumbs-o-down" aria-hidden="true"></i>'; $clr_class='success';
                  $title="Click to Not Approve"; $class='warning';$color='#056928';
                }
                if($this->session->userdata('role_id')==1)
                {
                    $rejected = $this->db->where(array('status'=>1,'offer'=>$cv->id))->get('dgt_offer_approvers')->num_rows();
                    if($rejected >0)
                    {
                    $s_label = 'Rejected'; $s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='danger'; $color='#b31109';
                    $clr_class='danger'; $title="Click to Approve";

                    }
                }
                $req_cand = $this->db->query("SELECT * FROM dgt_registered_candidates where id = ".$cv->candidate." ")->row_array();
                $req_job = $this->db->query("SELECT * FROM dgt_jobs where id = ".$cv->title." ")->row_array();
                $req_job_type = $this->db->query("SELECT * FROM dgt_jobtypes where id = ".$cv->job_type." ")->row_array();
                $return_data['data'][$i]['s_no']		    = $j;
                $return_data['data'][$i]['title']   		= $req_job['job_title'];
                $return_data['data'][$i]['name']            = '<a href="';
                if(App::is_permit('menu_offer_approval','read')){
                    $return_data['data'][$i]['name']      .=  base_url('offers/offer_view/').$cv->id;
                }
                else{ 
                    $return_data['data'][$i]['name']       .= '#';
                }
                $return_data['data'][$i]['name']           .= '" class ="">'.ucfirst($req_cand['first_name'].' '.$req_cand['last_name']).'</a>';
                $return_data['data'][$i]['job_type']   		= ucfirst($req_job_type['job_type']);
                $return_data['data'][$i]['salary']   		= $cv->salary.' '.$curren_type[$cv->currency_type];
                if(isset($plan_percent[$cv->annual_incentive_plan])){
                  //  $return_data['data'][$i]['annual_plan'] = $plan_percent[$cv->annual_incentive_plan];
                }else{ 
                    ///$return_data['data'][$i]['annual_plan'] = "No";
                }
                $req_offer_id = "'".$cv->id."'";
                $req_offer_row_id = "'".$cv->app_row_id."'";
                $return_data['data'][$i]['long_plan']       = ucfirst(($cv->long_term_incentive_plan=='on')?'Yes':'No');
                $return_data['data'][$i]['status']         = '<label class="badge bg-inverse-'.$clr_class.'" style="display: inline-block;min-width: 90px;">'.ucfirst($s_label).'</label>';
                $return_data['data'][$i]['action']   		= '<div class="dropdown"><a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu float-right"><a href="javascript:void(0)" data-status="1" data-offerid ="'.$cv->id.'" data-offid="'.$cv->app_row_id.'" class="status_changebuttons dropdown-item"  title="Approve offer" onclick="offer_approve('.$req_offer_id.','.$req_offer_row_id.',1)"><i class="fa fa-thumbs-o-up m-r-5" aria-hidden="true"></i>'.lang('approve').'</a><a href="javascript:void(0)" data-status="2" data-offerid ="'.$cv->id.'" data-offid="'.$cv->app_row_id.'" class ="status_changebuttons dropdown-item"  onclick="offer_approve('.$req_offer_id.','.$req_offer_row_id.',2)"><i class="fa fa-ban m-r-5" aria-hidden="true"></i> '.lang('reject').'</a><a href="'.base_url('offers/offer_view/').$cv->id.'" class =" dropdown-item"><i class="fa fa-eye m-r-5" aria-hidden="true"></i>View</a></div></div>';
                $i++;
            }
         }
         echo json_encode($return_data);exit;
    }
    
    public function candidate_approve()
    {
        

        $status = $this->input->post('status');
        $offer_tab_id = $this->input->post('offer_tab_id');
        $offer_id = $this->input->post('offer_id');

        if($status==1)
        {
            $new_status = 2;
        }
        else
        {
            $new_status = 1;
        }
		
		// echo '<pre>';print_r($this->input->post());exit;


        $status_change = Offer::candidate_status($offer_tab_id,$new_status);

        $offer_approvers_status   = $this->db->get_where('offer_approvers',array('status'=>1,'offer'=>$offer_id))->num_rows();
       
        if($offer_approvers_status != 0){
         $this->db->set(array('offer_status'=>1))->where('id',$offer_id)->update('offers');
        } else {
             $this->db->set(array('offer_status'=>2))->where('id',$offer_id)->update('offers');
        }
        $approver = $this->session->userdata('user_id');

        $offer_det = $this->db->query("SELECT * FROM dgt_offers where id = ".$offer_id." ")->result_array();
        $acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$offer_det[0]['user_id']." ")->result_array();

        $offer_approvers = $this->db->get('offer_approver_settings')->result_array();
            if($offer_approvers[0]['default_offer_approval'] == "seq-approver"){
            // next approvers view
            if($status == 1){              
                $get_approver_record = $this->db->get_where('dgt_offer_approvers',array('offer'=>$offer_id,'approvers'=>$approver))->row_array();            
                
                $view_next = $this->db->query('select * from dgt_offer_approvers where id = (select min(id) from dgt_offer_approvers where id > '.$get_approver_record['id'].')')->row_array();
                $view_status['view_status'] = 1;
                if(!empty($view_next)){
                    $this->db->update('dgt_offer_approvers',$view_status,array('offer'=>$offer_id,'id'=>$view_next['id'])); 

                    $data = array(
                                'module' => 'offers/offer_approvals',
                                'module_field_id' => $offer_id,
                                'user' => $view_next['approvers'],
                                'activity' => 'Offer Submitted by '.$acc_det[0]['fullname'],
                                'icon' => 'fa-plus'
                                // 'value1' => $cur.' '.$this->input->post('amount'),
                              //  'value2' => $offer_det[0]['added_by']
                                );
                    // print_r($data);
                App::Log($data);    

                }   


            }
        }
        $approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();
        if($status==1){
            $status_msg='approved';
          
        }else{
            $status_msg='rejected';

        }

        $data = array(
                    'module' => 'offers',
                    'module_field_id' => $offer_id,
                    'user' => $offer_det[0]['user_id'],
                    'activity' => 'Offer '.$status_msg.' by '.$approver_det['fullname'],
                    'icon' => 'fa-plus',
                    // 'value1' => $cur.' '.$this->input->post('amount'),
                    // 'value2' => $expense_det[0]['added_by']
                    );
                App::Log($data);


        print_r($status_change);exit();
                  

        exit();
    }

    public function send_offer_candidate()
    {
        $new_status = 3;
        $offer_id = $this->input->post('assoc_id');
        $ch_asso_id_img = $this->input->post('ch_asso_id_img');
        $where_cond = array('id'=>$offer_id);
        $offer_data = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $status_change = false;
        if(!empty($offer_data)){
            $status_change = $this->db->set(array('offer_status'=>3,'signature'=>$ch_asso_id_img))->where('id',$offer_id)->update('offers');
            $job_id =   $offer_data['title'];
            $can_id =   $offer_data['candidate'];
            $this->db->set(array('user_job_status'=>7))->where('candidate_id',$can_id)->where('job_id',$job_id)->update('dgt_candidate_job_status');
        }
        
        if($status_change)
        {   
            // $message = App::email_template('offer_send','template_body');
            // $subject = App::email_template('offer_send','subject');
            $signature = App::email_template('email_signature','template_body');
            $user_mail= Offer::applicantMail($offer_id);

            $logo_link = create_email_logo();
            
            $job_data = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id)); 
            $user_data = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$can_id)); 

            // $logo_link = create_email_logo();
            // $message        =   str_replace("{INVOICE_LOGO}",$logo_link,$message);
            // $message        =   str_replace("{SALARY}",$offer_data['salary'],$message);
            // $message        =   str_replace("{JOB}",$job_data['job_title'],$message);
            // $message        =   str_replace("{LINK}",base_url('candidates/dashboard'),$message);
            // $message        =   str_replace("{USER}",rtrim($user_data['first_name'].' '.$user_data['last_name'],' '),$message);

            $attach_file   =    FCPATH.'assets/uploads/offer_letter.pdf';

            $file_url = base_url().$ch_asso_id_img;

            $sign_img = "<img src='".$file_url."' style='width:200px;'>";

            // $message        =   str_replace("{ADMIN_SIGNATURE}",$sign_img,$message);

            $this->load->model("offer");
            $candidate_id = $can_id;
            $email_text = '';
            
            $email_text = $this->offer->offer_letter_email_body($job_id, $candidate_id);
            if(!empty($email_text['email_subject']))
            {
                $subject = $email_text['email_subject'];
            }
            else{
                $subject = "Offer Letter";
            }

            $message = '';
            
            if(!empty($email_text['email_body']))
            {
                $message = $email_text['email_body'];
            }
            else{
                $message = "Contragulation you have selected.";
            }
            $entity_name = '';
            if(!empty($email_text['entity_name']))
            {
                $entity_name = $email_text['entity_name'];
            }
            else{
                $entity_name = "Autom HR";
            }
            

            $html = $this->offer->offer_letter_foramt($job_id, $candidate_id);

            $pdf = array(
                'html' => $html,
                'title' => lang('invoice'),
                'author' => config_item('company_name'),
                'creator' => config_item('company_name'),
                'filename' => 'offer_letter.pdf',
                'badge' => config_item('display_invoice_badge'),
            );

            $content        =   $this->applib->create_pdf_mail($pdf);

            file_put_contents($attach_file, $content);
    
            $params['recipient'] = $user_data['email'];
            $params['subject'] = '['.$entity_name.']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = $attach_file;
            
            Modules::run('fomailer/send_email',$params);
            $this->session->set_flashdata('tokbox_success', lang('mail_sent_successfully'));
            unlink($attach_file);
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }

    }

    public function send_applicantmail_preview()
    {
        $new_status = 3;
        $offer_id = $this->input->post('assoc_id'); 
        $where_cond = array('id'=>$offer_id);
        $offer_data = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $status_change = false;
        if(!empty($offer_data)){
            $status_change = true;
            $job_id =   $offer_data['title'];
            $can_id =   $offer_data['candidate'];
        }
        if($status_change)
        {   
            // $message = App::email_template('offer_send','template_body');

            $this->load->model("offer");
            $candidate_id = $can_id;
            $message = $this->offer->offer_letter_foramt($job_id, $candidate_id);

            $subject = App::email_template('offer_send','subject');
            $signature = App::email_template('email_signature','template_body');
            $user_mail= Offer::applicantMail($offer_id);

            $logo_link = create_email_logo();
            
            $job_data = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id)); 
            $user_data = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$can_id)); 

            // $logo_link = create_email_logo();
            // $message        =   str_replace("{INVOICE_LOGO}",$logo_link,$message);
            // $message        =   str_replace("{SALARY}",$offer_data['salary'],$message);
            // $message        =   str_replace("{JOB}",$job_data['job_title'],$message);
            // $message        =   str_replace("{LINK}",base_url('candidates/dashboard'),$message);
            // $message        =   str_replace("{USER}",rtrim($user_data['first_name'].' '.$user_data['last_name'],' '),$message);

            if(isset($_POST['sign']) && (empty($_POST['signature_type']) || $_POST['signature_type']!='upload_image')){
                $image_parts = explode(";base64,", $_POST['sign']);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
            
                $image_base64 = base64_decode($image_parts[1]);
                
            
                $file = 'assets/uploads/sign/'  .  uniqid() . '.' . $image_type;
                $req_file = FCPATH.$file;
                file_put_contents($req_file, $image_base64);
            }
            else{
                $config['upload_path']= "assets/uploads/sign/";
                $config['allowed_types']='gif|jpg|png';
                $this->load->library('upload',$config);
                if($this->upload->do_upload("file")){
                    $data = array('upload_data' => $this->upload->data());
                   $file = 'assets/uploads/sign/'.$data['upload_data']['file_name'];
                }
                
                else{
                    print_r($config);
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);exit;
                }
            }
            $file_url = base_url().$file;

            $sign_img = "<img src='".$file_url."' style='width:200px;'>";

            $message        =   str_replace("{ADMIN_SIGNATURE}",$sign_img,$message);
            $message        =   str_replace("{CANDIDATE_SIGNATURE}",'',$message);

            echo json_encode( array('msg' =>'success' ,'response'=>'ok','content'=>$message,'img'=>$file,'offer_id'=>$offer_id) );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }

    }
    

    public function offer_decline()
    {
        $new_status = 5;
        $offer_id = $this->input->post('assoc_id');
        $where_cond = array('id'=>$offer_id);
        $offer_data = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $status_change = false;
        if(!empty($offer_data)){
            $status_change =  $this->db->set(array('offer_status'=>7))->where('id',$offer_id)->update('offers');
            $job_id =   $offer_data['title'];
            $can_id =   $offer_data['candidate'];
            $this->db->set(array('user_job_status'=>10))->where('candidate_id',$can_id)->where('job_id',$job_id)->update('dgt_candidate_job_status');
        }
        // $status_change =Offer::applicantStatus($app_id,$new_status);
        if($status_change)
        {   

            $message = App::email_template('offer_mail_cancel','template_body');
            $subject = App::email_template('offer_mail_cancel','subject');
            $signature = App::email_template('email_signature','template_body');
            $user_mail= Offer::applicantMail($offer_id);
            $logo_link = create_email_logo();
            $username_repl = str_replace("{USERNAME}",strtoupper($user_mail[0]['name']),$message);            
            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$username_repl);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$logo);
            
            $params['recipient'] = $user_mail[0]['email'];
            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            Modules::run('fomailer/send_email',$params);
            $this->session->set_flashdata('tokbox_success', 'Offer rejected successfully');
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }

    }

    public function to_archive()
    {
		// echo '<pre>';print_r($this->input->post());exit;
        $new_status = 6;
        $offer_id = $this->input->post('assoc_id');
        $old_state = $this->input->post('current');
        $status_change = $this->db->set(array('offer_status'=>6,'old_status'=>$old_state))->where('id',$offer_id)->update('offers');
        // $status_change =Offer::applicantStatus($app_id,$new_status,$old_state);
        if($status_change)
        {
            $this->session->set_flashdata('tokbox_success', 'Application has archived successfully');
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();    
        }
        else
        {
            $this->session->set_flashdata('tokbox_success', 'Oops! Job application archive failed');
            
            echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
        }
        
    }
    public function app_retrieve()
    {
        
        $offer_id = $this->input->post('assoc_id');
        $prev_state=Offer::applicantStatus_old($offer_id);

        if($prev_state[0]['old_status'] )
        {
         $status_change = $this->db->set(array('offer_status'=>$prev_state[0]['old_status']))->where('id',$offer_id)->update('offers');
            if($status_change)
            {
                $this->session->set_flashdata('tokbox_success', 'Application has retrieved successfully');
                
                echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();    
            }
            else
            {
                $this->session->set_flashdata('tokbox_success', 'Oops! process failed');
                
                echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
            }
        }
           else
            {
                $this->session->set_flashdata('tokbox_success', 'Oops! process failed');
                
                echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
            }
        
    }
    public function app_accepts()
    {
        $new_status = 2;
        $offer_id = $this->input->post('assoc_id');
        
        $status_change = $status_change = $this->db->set(array('offer_status'=>2))->where('id',$offer_id)->update('offers');

        $offer_details = Offer::getjobbyid($offer_id);

        $where_cond = array('candidate_id'=>$offer_details['candidate'],'job_id'=>$offer_details['title']);


        $status_change = $status_change = $this->db->set(array('user_job_status'=>7))->where($where_cond)->update('dgt_candidate_job_status');

        $this->session->set_flashdata('tokbox_success', 'Application has accept successfully');
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();  

        if($status_change && !empty($offer_data))
        {
            $this->session->set_flashdata('tokbox_success', 'Application has accept successfully');
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();    
        }
        else
        {
            $this->session->set_flashdata('tokbox_success', 'Oops! Job application accept has failed');
            
            echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
        }
    }

    public function offer_view($offer_id){

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('offer_dashboard').' - '.config_item('company_name'));
        $data['page'] = lang('offer_dashboard');
        // $data['datatables'] = true;
        // $data['form'] = true;
        // $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['offer_details'] = Offer::getjobbyid($offer_id);
       
        $offer_status   = $this->db->get_where('offers',array('id'=>$offer_id))->row_array();
        $data['candiate_offer_status'] = $offer_status['offer_status'];
        // echo $data['candiate_offer_status']; exit
        // $data['candiate_offer_status'] = Offer::getCandidateOfferById($offer_id,$candidate_id);

        // $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('offer_view', isset($data) ? $data : null);
    }

    function getEmployees(){
		extract($_GET);

		$this->db->select('U.id,AD.fullname,U.email')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1);
		$this->db->where("(AD.fullname like '%".$term['term']."%' OR U.email like '%".$term['term']."%' )", NULL, FALSE);

		$users = $this->db->order_by('AD.fullname','asc')->get()->result_array();
		
		echo json_encode($users);
		exit;

	 }
    function getCandidates(){
		extract($_GET);
		$this->db->select('r.id,r.first_name,r.email')->from('dgt_registered_candidates r')->join('dgt_candidate_job_status c','c.candidate_id = r.id')->where('r.status',1)->where('c.user_job_status!=',2)->where('c.user_job_status!=',6);
		$this->db->where("(r.first_name like '%".$term['term']."%' OR r.email like '%".$term['term']."%' )", NULL, FALSE);
		$users = $this->db->group_by('r.email')->order_by('first_name','asc')->get()->result_array();
		echo json_encode($users);
		exit;
	}
    function getJobs(){
		extract($_GET);
		$this->db->select('id,job_title')->from('dgt_jobs')->where('job_status',0);
		$jobs = $this->db->order_by('job_title','asc')->get()->result_array();
		echo json_encode($jobs);
		exit;
	 }

     function getJobs_by_candidate(){
		extract($_POST);
		$this->db->select('j.id,j.job_title')->from('dgt_jobs j')->join('dgt_candidate_job_status c','c.job_id = j.id');
        $this->db->where('j.job_status',0);
        $this->db->where('c.candidate_id',$candidate_id);
        $this->db->where('c.user_job_status!=',2);
        $this->db->where('c.user_job_status!=',6);
        $this->db->where('c.user_job_status!=',7);
        $this->db->where('c.user_job_status!=',8);
        $this->db->where('c.user_job_status!=',9);
		$jobs = $this->db->order_by('j.job_title','asc')->get()->result_array();
        $cur_result = "<option value=''>Select</option>";
        if(!empty($jobs)){
            foreach($jobs as $job1){
                $cur_result .= "<option value='".$job1['id']."'>".$job1['job_title']."</option>";
            }
        }
        echo $cur_result;
		exit;
	 }
     function get_job_detail(){
        extract($_POST);
        $this->db->select('*')->from('dgt_jobs')->where('job_status',0)->where('id',$job_id);
		$jobs = $this->db->order_by('job_title','asc')->get()->row_array();
		echo json_encode($jobs);
		exit;
     }

}
/* End of file contacts.php */
