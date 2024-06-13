<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('tank_auth','form_validation','recaptcha'));
        $this->load->helper('security');
        $lang = config_item('default_language');
        if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
        if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
        $this->lang->load('fx', $lang);

        foreach (config_item('tank_auth') as $key => $value) {
            $this->config->set_item($key, $value);
        }
        $this->load->model(array('App','User'));

    }
    function userzone(){
        if($this->input->post('timezone')){
            $timezone  = $this->input->post('timezone');
            $this->session->set_userdata('timezone',$timezone);
        }
    }
    function index()
    {
        if ($message = $this->session->flashdata('message')) {
            $this->load->view('auth/general_message', array('message' => $message));
        } else {
            redirect('login');
        }
    }
	function cron_promotion(){
		$cur_date = date('Y-m-d');
		$promotions = $this->db->select('*')->from('dgt_promotion')->where('accept_status',1)->where('date(effectivedate)',$cur_date)->order_by('id','desc')->get()->row_array();
		if(!empty($promotions)){
			$employee = $promotions['employee'];
			$upd_data = array(
                'department_id'=>$promotions['department'],
                'designation_id'=>$promotions['promotionto'],
            );
            $this->db->where('id',$employee);
            $this->db->update('dgt_users',$upd_data);
		}
		
		exit;
	}

    /**
     * Login user on the site
     *
     * @return void
     */
    function login()
    {
        if ($this->tank_auth->is_logged_in()) 
        {     // logged in
            redirect('');
        } else {
			// print_r($_POST);exit;
            $data['login_by_username'] = (config_item('login_by_username') AND
                config_item('use_username'));
            $data['login_by_email'] = config_item('login_by_email');

            //$this->form_validation->set_rules('branch_id', 'Branch_id', 'integer');
            $this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('remember', 'Remember me', 'integer');

            // Get login for counting attempts to login
            if (config_item('login_count_attempts') AND
                ($login = $this->input->post('login'))) {
                $login = $this->security->xss_clean($login);
            } else {
                $login = '';
            }

            // $data['use_recaptcha'] = (config_item('use_recaptcha') == 'TRUE') ? TRUE : FALSE;
            // echo "hi"; exit;
            // if ($this->tank_auth->is_max_login_attempts_exceeded($login) || config_item('captcha_login') == 'TRUE') {
            //     if ($data['use_recaptcha'])
            //         $this->form_validation->set_rules('g-recaptcha-response', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
            //     else
            //         $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
            // }
            $data['errors'] = array();
// print_r($_POST);exit;
            if ($this->form_validation->run($this)) {                               // validation ok
                if ($this->tank_auth->login(
                    // $this->form_validation->set_value('branch_id'),
                    $this->form_validation->set_value('login'),
                    $this->form_validation->set_value('password'),
                    $this->form_validation->set_value('remember'),
                    // $_POST['branch_id'],
                    $data['login_by_username'],
                    $data['login_by_email'])) {                             // success
					
					
                    $res = array(
                        'online_status' => '1'
                    );
                    $this->db->where('id',$this->session->userdata('user_id'));
                    $this->db->update('users',$res);

                    $branch_details = $this->db->get_where('account_details',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                    $user_entity = $this->db->get_where('assigned_entities',array('user_id'=>$this->session->userdata('user_id')))->row_array();
                    if(!empty($user_entity)){
                        $user_type1 = $this->db->order_by('role','asc')->where('role','company_admin')->get('roles')->row_array();
                        $this->session->set_userdata('user_type',$user_type1['r_id']);
                    }

                    $this->session->set_userdata('branch_id',$branch_details['branch_id']);
                    $user_type = $this->db->order_by('role','asc')->where('r_id',$this->session->userdata('user_type'))->get('roles')->row_array();
                    $this->session->set_userdata('user_type_name',$user_type['role']);
                    redirect('');

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    if (isset($errors['banned'])) {
                        $this->session->set_flashdata('message',$this->lang->line('auth_message_banned').' '.$errors['banned']);    // banned user
                        //$this->_show_message();
                        redirect('login');

                    } elseif (isset($errors['not_activated'])) {                // not activated user
                        redirect('/auth/send_again/');

                    }elseif (isset($errors['inactive'])) {              // not activated user
                        $this->session->set_flashdata('message',$errors['inactive']);   // Inactive user
                        redirect('login');

                    } else {                                                    // fail
                        foreach ($errors as $k => $v) { $data['errors'][$k] = $this->lang->line($v); }
                    }
                }
            }



            // $data['show_captcha'] = FALSE;
            // if(config_item('captcha_login') == 'TRUE'){
            //     $data['show_captcha'] = TRUE;
            //     if ($data['use_recaptcha']) {
            //         // $data['recaptcha_html'] = $this->_create_recaptcha();
            //     } else {
            //         $data['captcha_html'] = $this->_create_captcha();
            //     }
            // }

            // if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
            //     $data['show_captcha'] = TRUE;
            // }
            $this->load->module('layouts');
            $this->load->library('Template');
            $this->template->title(lang('welcome_to').' '.config_item('company_name'));
            $data['ref_item'] = $this->input->get('r_url', TRUE) ? $this->input->get('r_url', TRUE) : NULL;
            $this->template
                ->set_layout('login')
                ->build('auth/login_form',isset($data) ? $data : NULL);
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    function logout()
    {
        $res = array(
                        'online_status' => '0'
                    );
                    $this->db->where('id',$this->session->userdata('user_id'));
                    $this->db->update('dgt_users',$res);
        $this->tank_auth->logout();
        redirect('login');

        //$this->_show_message($this->lang->line('auth_message_logged_out'));
    }

    /**
     * Register user on the site
     *
     * @return void
     */
    function register()
    {
        $captcha_registration   = config_item('captcha_registration');
        $use_recaptcha  = (config_item('use_recaptcha') == 'TRUE') ? TRUE : FALSE;
        $use_username = config_item('use_username');

        if ($this->tank_auth->is_logged_in()) {                                 // logged in
            redirect('');

        } elseif ($this->tank_auth->is_logged_in(FALSE)) {                      // logged in, not activated
            redirect('/auth/send_again/');

        } elseif (config_item('allow_client_registration') == 'FALSE') {    // registration is off
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('auth_message_registration_disabled'));
            redirect('login');

        } else {

            if ($use_username) {
                $this->form_validation->set_rules('username', lang('username'), 'trim|required|xss_clean|min_length['.config_item('username_min_length').']|max_length['.config_item('username_max_length').']');
            }
            $this->form_validation->set_rules('fullname', lang('full_name'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', lang('email'), 'trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('password', lang('password'), 'trim|required|xss_clean|min_length['.config_item('password_min_length').']|max_length['.config_item('password_max_length').']');
            $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|xss_clean|matches[password]');


            if ($captcha_registration) {
                if ($use_recaptcha) {
                    $this->form_validation->set_rules('g-recaptcha-response', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
                } else {
                    $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
                }
            }
            $data['errors'] = array();
            $email_activation = config_item('email_activation');
            $individual = $this->input->post('company_name') == '' ? 1:0;
            $designation_id = (!empty($this->input->post('designations'))) ? $this->input->post('designations'):0;
         
            if ($this->form_validation->run($this)) {                               // validation ok
                if (!is_null($data = $this->tank_auth->create_user(
                    $use_username ? $this->form_validation->set_value('username') : '',
                    $this->form_validation->set_value('email'),
                    $this->form_validation->set_value('password'),
                    $this->input->post('fullname'),
                    '-',
                    '2',
                    '',
                    $email_activation,
                    $individual == 1 ? $this->input->post('fullname') : $this->input->post('company_name'),
                    $individual,$designation_id,'','',''
                ))) {    // success

                    $data['site_name'] = config_item('company_name');

                    if ($email_activation) {                                    // send "activate" email
                        $data['activation_period'] = config_item('email_activation_expire') / 3600;

                        $this->_send_email('activate', $data['email'], $data);

                        unset($data['password']); // Clear password (just for any case)

                        $this->session->set_flashdata('message', lang('auth_message_registration_completed_1'));
                        redirect('/auth/login');

                    } else {
                        if (config_item('email_account_details') == 'TRUE') {   // send "welcome" email

                            $this->_send_email('welcome', $data['email'], $data);
                        }
                        unset($data['password']); // Clear password (just for any case)
                        $this->session->set_flashdata('message', lang('auth_message_registration_completed_2'));
                        redirect('/auth/login');
                        //$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
                    }
                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            if ($captcha_registration) {
                if ($use_recaptcha) {
                    // $data['recaptcha_html'] = $this->_create_recaptcha();
                } else {
                    $data['captcha_html'] = $this->_create_captcha();
                }
            }
            $data['use_username'] = $use_username;
            $data['captcha_registration'] = $captcha_registration;
            $data['use_recaptcha'] = $use_recaptcha;
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Register - '.config_item('company_name'));
            $this->template
                ->set_layout('login')
                ->build('auth/register_form',isset($data) ? $data : NULL);

        }
    }

    /**
     * Register user as admin on the site
     *
     * @return void
     */
    function register_user(){
        // echo "<pre>";print_r($_POST); exit;

        $use_username = config_item('use_username');

        $data['errors'] = array();

        $email_activation = config_item('email_activation');


        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        $this->form_validation->set_rules('employee_id', 'Employee Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|xss_clean|matches[password]');
        
        

         $email_contact = ($this->input->post('email_contact')[0] == 'Yes') ? TRUE : FALSE;
         $designation_id = (!empty($this->input->post('designations'))) ? $this->input->post('designations'):0;
         $department_id = (!empty($this->input->post('department_name'))) ? $this->input->post('department_name'):0;
         // $is_teamlead = $this->input->post('is_teamlead');
         // if($is_teamlead == 'no'){  
         //    $teamlead_id = $this->input->post('team_leaders_name');
         // }else{
         //    $teamlead_id = 0;
         // }

         $is_teamlead = 'no';
         // if($is_teamlead == 'no'){  
         //    $teamlead_id = (!empty($this->input->post('team_leaders_name')))?$this->input->post('team_leaders_name'):0;
         // }else{
         //    $teamlead_id = 0;
         // }
         $ro = (!empty($this->input->post('reporting_to')))?$this->input->post('reporting_to'):0;

         $branch_id = (!empty($this->session->userdata('branch_id')))?$this->session->userdata('branch_id'):0;
         if($branch_id==0){
            $branch_id = (!empty($this->input->post('branch')))?$this->input->post('branch'):0;
        }
        $dynamic_field = (!empty($this->session->userdata('dynamic_field')))?$this->session->userdata('dynamic_field'):0;
        if ($this->form_validation->run($this)) {       // validation ok
            if (!is_null($data = $this->tank_auth->admin_create_user(
                $use_username ? $this->form_validation->set_value('username') : '',
                $this->form_validation->set_value('email'),
                $this->form_validation->set_value('password'),
                $this->input->post('fullname')?$this->input->post('fullname'):'',
                $this->input->post('company')?$this->input->post('company'):'',
                $this->input->post('city')?$this->input->post('city'):'',
                $this->input->post('state')?$this->input->post('state'):'',
                $this->input->post('address')?$this->input->post('address'):'',
                $this->input->post('pincode')?$this->input->post('pincode'):'',
                $this->input->post('role')?$this->input->post('role'):'',
                $this->input->post('user_type')?$this->input->post('user_type'):'',
                $this->input->post('phone')?$this->input->post('phone'):'',
                $this->input->post('emp_doj')?$this->input->post('emp_doj'):'',
                $email_activation,
                $designation_id,
                $department_id,
                $is_teamlead,
                $ro,
                $branch_id,
                $this->input->post('is_reporter')
            ))) {                                   // success

                $ins_data = array(
                    'user_id'	=> $data['user_id'],
                    'entity_id'	=> $branch_id,
                    'status'	=> 1
                );	
                $this->db->insert('user_entity',$ins_data);	


                $res = array(
                'is_teamlead' =>'yes' 
                );
                $this->db->where('id',$ro);
                $this->db->update('users',$res);

                $data['site_name'] = config_item('company_name');

                if ($email_activation) {                                    // send "activate" email
                    $data['activation_period'] = config_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);

                    unset($data['password']); // Clear password (just for any case)
                    // $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('su_message', lang('client_registered_successfully_activate'));
                    redirect($this->input->post('r_url'));

                } else {
                    
                    // if (config_item('email_account_details') == 'TRUE' && $email_contact) {  // send "welcome" email
                    if (config_item('email_account_details') == 'TRUE') { // send "welcome" email
                        $this->_send_email('welcome', $data['email'], $data);
                    }

                    // if($this->input->post('role') == 2){ 
                    //     $this->_send_email('confirm_client', $data['email'], $data);
                    // }

                    unset($data['password']); // Clear password (just for any case)
                    // $this->session->set_flashdata('response_status', 'success');

                    $this->session->set_flashdata('su_message', lang('user_added_successfully'));

                     //  Added Razorpay api for create employee
                    $this->load->model("Razorpay_payroll","razorpay");
                    $this->razorpay->add_employee($this->input->post(), $data['user_id']);


                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $errors = $this->tank_auth->get_error_message();

                foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                // $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('er_message', lang('client_registration_failed'));
                // $this->session->set_flashdata('form_errors', $data['errors'][$k]);
                redirect($this->input->post('r_url'));
            }
        }else{
            // $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('er_message', lang('client_registration_failed'));
            // $this->session->set_flashdata('form_errors', validation_errors('<span style="color:red">', '</span><br>'));
            redirect($this->input->post('r_url'));
        }

    }



    function register_branch_admin(){
        // echo "<pre>";print_r($_POST); exit;

        $use_username = config_item('use_username');

        $data['errors'] = array();

        $email_activation = config_item('email_activation');


        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|xss_clean|matches[password]');
        
        

         $email_contact = ($this->input->post('email_contact')[0] == 'Yes') ? TRUE : FALSE;
         $designation_id = (!empty($this->input->post('designations'))) ? $this->input->post('designations'):0;
         $department_id = (!empty($this->input->post('department_name'))) ? $this->input->post('department_name'):0;
         $is_teamlead = 'no';
         $ro = (!empty($this->input->post('reporting_to')))?$this->input->post('reporting_to'):0;
         $branch_id = (!empty($this->input->post('branch_id')))?$this->input->post('branch_id'):0;

        if ($this->form_validation->run($this)) {       // validation ok
            if (!is_null($data = $this->tank_auth->admin_create_branch_admin(
                $use_username ? $this->form_validation->set_value('username') : '',
                $this->form_validation->set_value('email'),
                $this->form_validation->set_value('password'),
                $this->input->post('fullname')?$this->input->post('fullname'):'',
                $this->input->post('company')?$this->input->post('company'):'',
                $this->input->post('city')?$this->input->post('city'):'',
                $this->input->post('state')?$this->input->post('state'):'',
                $this->input->post('address')?$this->input->post('address'):'',
                $this->input->post('pincode')?$this->input->post('pincode'):'',
                $this->input->post('role')?$this->input->post('role'):4,
                $this->input->post('user_type')?$this->input->post('user_type'):'',
                $this->input->post('phone')?$this->input->post('phone'):'',
                $this->input->post('emp_doj')?$this->input->post('emp_doj'):'',
                $email_activation,
                $designation_id,
                $department_id,
                $is_teamlead,
                $ro,
                $branch_id
            ))) {                                   // success
                $res = array(
                'is_teamlead' =>'yes' 
                );
                $this->db->where('id',$ro);
                $this->db->update('users',$res);

                $data['site_name'] = config_item('company_name');

                if ($email_activation) {                                    // send "activate" email
                    $data['activation_period'] = config_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);

                    unset($data['password']); // Clear password (just for any case)
                    // $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('su_message', lang('client_registered_successfully_activate'));
                    redirect($this->input->post('r_url'));

                } else {
                    
                    // if (config_item('email_account_details') == 'TRUE' && $email_contact) {  // send "welcome" email
                    if (config_item('email_account_details') == 'TRUE') { // send "welcome" email
                        $this->_send_email('welcome', $data['email'], $data);
                    }

                    // if($this->input->post('role') == 2){ 
                    //     $this->_send_email('confirm_client', $data['email'], $data);
                    // }

                    unset($data['password']); // Clear password (just for any case)
                    // $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('su_message', lang('user_added_successfully'));
                    redirect('branch_admins');
                }
            } else {
                $errors = $this->tank_auth->get_error_message();

                foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                // $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('er_message', lang('client_registration_failed'));
                // $this->session->set_flashdata('form_errors', $data['errors'][$k]);
                redirect($this->input->post('r_url'));
            }
        }else{
            // $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('er_message', lang('client_registration_failed'));
            // $this->session->set_flashdata('form_errors', validation_errors('<span style="color:red">', '</span><br>'));
            redirect($this->input->post('r_url'));
        }

    }



    /**
     * Send activation email again, to the same or new email address
     *
     * @return void
     */
    function send_again()
    {
        if (!$this->tank_auth->is_logged_in(FALSE)) {                           // not logged in or activated
            redirect('/auth/login/');

        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if (!is_null($data = $this->tank_auth->change_email(
                    $this->form_validation->set_value('email')))) {         // success

                    $data['site_name']  = config_item('company_name');
                    $data['activation_period'] = config_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);
                    $this->session->set_flashdata('message', sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
                    redirect('/auth/login');
                    //$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Send Password - '.config_item('company_name'));
            $this->template
                ->set_layout('login')
                ->build('auth/send_again_form',isset($data) ? $data : NULL);
        }
    }

    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function activate()
    {
        $user_id        = $this->uri->segment(3);
        $new_email_key  = $this->uri->segment(4);

        // Activate user
        if ($this->tank_auth->activate_user($user_id, $new_email_key)) {        // success
            $this->tank_auth->logout();
            //$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));
            $this->session->set_flashdata('message', $this->lang->line('auth_message_activation_completed'));
            redirect('/auth/login');

        } else {
            // fail
            $this->session->set_flashdata('message', $this->lang->line('auth_message_activation_failed'));
            redirect('/auth/login');
            //$this->_show_message($this->lang->line('auth_message_activation_failed'));
        }
    }

    /**
     * Generate reset code (to change password) and send it to user
     *
     * @return void
     */
    function forgot_password()
    {
        if ($this->tank_auth->is_logged_in()) {                                 // logged in
            redirect('');

        } elseif ($this->tank_auth->is_logged_in(FALSE)) {                      // logged in, not activated
            redirect('/auth/send_again/');

        } else {
            $this->form_validation->set_rules('login', 'Email or Username', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if (!is_null($data = $this->tank_auth->forgot_password(
                    $this->form_validation->set_value('login')))) {

                    $data['site_name'] = config_item('company_name');

                    // Send email with password activation link

                    $this->_send_email('forgot_password', $data['email'], $data);
                    $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_sent'));
                    //$this->_show_message($this->lang->line('auth_message_new_password_sent'));
                    redirect('login');

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Forgot Password - '.config_item('company_name'));
            $this->template
                ->set_layout('login')
                ->build('auth/forgot_password_form',isset($data) ? $data : NULL);
            //$this->load->view('auth/forgot_password_form', $data);
        }
    }

    /**
     * Replace user password (forgotten) with a new one (set by user).
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_password()
    {
        $user_id        = $this->uri->segment(3);
        $new_pass_key   = $this->uri->segment(4);

        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.config_item('password_min_length').']|max_length['.config_item('password_max_length').']');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

        $data['errors'] = array();

        if ($this->form_validation->run()) {                                // validation ok
            if (!is_null($data = $this->tank_auth->reset_password(
                $user_id, $new_pass_key,
                $this->form_validation->set_value('new_password')))) {  // success

                $data['site_name'] = config_item('company_name');

                // Send email with new password
                $this->_send_email('reset_password', $data['email'], $data);
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_activated'));
                redirect('login');

            } else {
                // fail
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_failed'));
                redirect('login');
            }
        } else {
            // Try to activate user by password key (if not activated yet)

            if (config_item('email_activation')) {
                $this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);

            }

            if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_failed'));
                redirect('login');
            }
        }
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Forgot Password - '.config_item('company_name'));
        $this->template
            ->set_layout('login')
            ->build('auth/reset_password_form',isset($data) ? $data : NULL);
    }

    /**
     * Change user password
     *
     * @return void
     */
    function change_password()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('/auth/login/');

        } else {

            Applib::is_demo();

            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.config_item('password_min_length').']|max_length['.config_item('password_max_length').']');
            $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->change_password(
                    $this->form_validation->set_value('old_password'),
                    $this->form_validation->set_value('new_password'))) {   // success

                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message',lang('auth_message_password_changed'));
                    redirect($this->input->post('r_url'));
                    //$this->_show_message($this->lang->line('auth_message_password_changed'));

                } else {                                                        // fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }

            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message',lang('password_or_field_error'));
            redirect('profile/settings');
        }
    }

    /**
     * Change user email
     *
     * @return void
     */
    function change_email()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('login/');

        } else {
            Applib::is_demo();

            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {    

                if (!is_null($data = $this->tank_auth->set_new_email(
                    $this->form_validation->set_value('email'),
                    $this->form_validation->set_value('password')))) {          // success

                    $data['site_name'] = config_item('company_name');
                     
                    // Send email with new email address and its activation link
                    $this->_send_email('change_email', $data['new_email'], $data);

                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message',sprintf(lang('auth_message_new_email_sent'), $data['new_email']));
                    redirect($this->input->post('r_url'));

                    //$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message',lang('password_or_email_error'));
            redirect('profile/settings');
        }
    }

    /**
     * Change username
     *
     * @return void
     */
    function change_username()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('login');

        } else {

             Applib::is_demo();

            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|is_unique[users.username]');
            
           
            $data['errors'] = array();

             if ($this->form_validation->run()!=FALSE) {                                // validation ok
            

               $this->db->set('username', $this->input->post('username', TRUE));
                $this->db->where('username',$this->tank_auth->get_username())->update('users');
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message',lang('username_changed_successfully'));
                redirect($this->input->post('r_url'));
            }
            
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message',lang('username_not_available'));
            redirect('profile/settings');
        }
    }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_email()
    {
        $user_id        = $this->uri->segment(3);
        $new_email_key  = $this->uri->segment(4);

        // Reset email
        if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {   // success
            $this->tank_auth->logout();
            $this->session->set_flashdata('message',$this->lang->line('auth_message_new_email_activated'));
            redirect('login');

        } else {                                                                // fail
            $this->session->set_flashdata('message',$this->lang->line('auth_message_new_email_failed'));
            redirect('login');
        }
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @return void
     */
    function unregister()
    {
        if (!$this->tank_auth->is_logged_in()) {                                // not logged in or not activated
            redirect('/auth/login/');

        } else {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run()) {                                // validation ok
                if ($this->tank_auth->delete_user(
                    $this->form_validation->set_value('password'))) {       // success
                    $this->_show_message($this->lang->line('auth_message_unregistered'));

                } else {                                                        // fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->load->view('auth/unregister_form', $data);
        }
    }

    /**
     * Show info message
     *
     * @param   string
     * @return  void
     */
    function _show_message($message)
    {
        $this->session->set_flashdata('message', $message);
        redirect('/auth/');
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param   string
     * @param   string
     * @param   array
     * @return  void
     */
    function _send_email($type, $email, &$data)
    {
        switch ($type)
        {
            case 'activate':
                return $this->_activate_email($email,$data);
                break;
            case 'welcome':
                return $this->_welcome_email($email,$data);
                break;
            case 'confirm_client':
                return $this->_confirm_client($email,$data);
                break;
            case 'forgot_password':
                return $this->_email_forgot_password($email,$data);
                break;
            case 'reset_password':
                return $this->_email_reset_password($email,$data);
                break;
            case 'change_email':
                return $this->_email_change_email($email,$data);
                break;
        }
    }

    function _welcome_email($email,$data){

        $message = App::email_template('registration','template_body');
        $subject = App::email_template('registration','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'auth/login',$logo);
        $username = str_replace("{USERNAME}",$data['username'],$site_url);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }


    function _confirm_client($email,$data){

        $message = App::email_template('registration','template_body');
        $subject = App::email_template('registration','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'auth/login',$logo);
        $username = str_replace("{USERNAME}",$data['username'],$site_url);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        // print_r($params); exit;

        modules::run('fomailer/send_email',$params);

    }


    function _email_change_email($email,$data){

        $message = App::email_template('change_email','template_body');
        $subject = App::email_template('change_email','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $email_key = str_replace("{NEW_EMAIL_KEY_URL}",base_url().'auth/reset_email/'.$data['user_id'].'/'.$data['new_email_key'],$logo);
        $new_email =  str_replace("{NEW_EMAIL}",$data['new_email'],$email_key);
        $site_url = str_replace("{SITE_URL}",base_url(),$new_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$site_url);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }

    function _email_reset_password($email,$data){

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

    }

    function _email_forgot_password($email,$data){

        $message = App::email_template('forgot_password','template_body');
        $subject = App::email_template('forgot_password','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'login',$logo);
        $key_url = str_replace("{PASS_KEY_URL}",base_url().'auth/reset_password/'.$data['user_id'].'/'.$data['new_pass_key'],$site_url);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$key_url);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ] '.$subject;
        $params['message'] = $message;

        //$params['attached_file'] = '';
        // echo"<pre>"; print_R($params); die;
        Modules::run('fomailer/send_email',$params);

    }

    function _activate_email($email,$data){

        $message = App::email_template('activate_account','template_body');
        $subject = App::email_template('activate_account','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $activate_url = str_replace("{ACTIVATE_URL}",site_url('/auth/activate/'.$data['user_id'].'/'.$data['new_email_key']),$logo);
        $activate_period = str_replace("{ACTIVATION_PERIOD}",$data['activation_period'],$activate_url);
        $username = str_replace("{USERNAME}",$data['username'],$activate_period);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;
        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;
        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }

    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return  string
     */
    function _create_captcha()
    {
        $this->load->helper('captcha');

        $cap = create_captcha(array(
            'img_path'      => './'.config_item('captcha_path'),
            'img_url'       => base_url().config_item('captcha_path'),
            'font_path'     => './'.config_item('captcha_fonts_path'),
            'font_size'     => config_item('captcha_font_size'),
            'img_width'     => config_item('captcha_width'),
            'img_height'    => config_item('captcha_height'),
            'show_grid'     => config_item('captcha_grid'),
            'expiration'    => config_item('captcha_expire'),
        ));

        // Save captcha params in database
        $data = array(
            'captcha_time' => time(),
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('dgt_captcha', $data);
        $this->db->query($query);

        return $cap['image'];
    }

    /**
     * Callback function. Check if CAPTCHA test is passed.
     *
     * @param   string
     * @return  bool
     */
    function _check_captcha()
    {
        // First, delete old captchas
        $expiration = time() - config_item('captcha_expire');
        $this->db->query("DELETE FROM dgt_captcha WHERE captcha_time < ".$expiration);

        // Then see if a captcha exists:
        $sql = "SELECT COUNT(*) AS count FROM dgt_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
        $binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        if ($row->count == 0){

            $this->form_validation->set_message('_check_captcha', lang('auth_incorrect_captcha'));
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Create reCAPTCHA JS and non-JS HTML to verify user as a human
     *
     * @return  string
     */
    function _create_recaptcha()
    {
        $this->load->helper('recaptcha');

        // Add custom theme so we can get only image
        $options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

        // Get reCAPTCHA JS and non-JS HTML
        $html = recaptcha_get_html(config_item('recaptcha_public_key'));

        return $options.$html;
    }

    /**
     * Callback function. Check if reCAPTCHA test is passed.
     *
     * @return  bool
     */
    function _check_recaptcha()
    {
      // Catch the user's answer
      $captcha_answer = $this->input->post('g-recaptcha-response');
      // Verify user's answer
      $response = $this->recaptcha->verifyResponse($captcha_answer);
      // Processing ...
      if ($response['success']) {
          return TRUE;
      } else {
          // Something goes wrong
          $this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
          return FALSE;
      }
    }


    function teamlead_options(){
        $dept_id = $this->input->post('dept_id');
        $teamleads = User::GetAllTeamleadsByDeptId($dept_id);
        $all_teamleads = array();
        foreach ($teamleads as $teamlead) {
            $lead_name = User::GetTeamleadNameById($teamlead->id);
            $leads_details = array(
                'user_id' => $lead_name->user_id,
                'fullname' => $lead_name->fullname
            ); 
            $all_teamleads[] = $leads_details;
        }
        echo json_encode($all_teamleads); exit;

    }
    
    function designation_option()
    {
        //print_r($_POST); exit;
        $dept_id = $this->input->post('dept_id');
        $destination = $this->db->get_where('dgt_designation',array('department_id'=>$dept_id))->result_array();
        echo json_encode($destination); exit;
    }

     public function job_apply($email=FALSE,$data=FALSE){

        $uname=$this->input->post('uname');
        $job_role=$this->input->post('job_role');
        $job_roleid=$this->input->post('job_roleid');
        $email=$this->input->post('email_id');
        $txt_msg=$this->input->post('txtar');
        $offer_id=$this->input->post('offer_id');
   
        

        if(!$uname)
        {
            echo json_encode(array('response' => 'failed','errid'=>'nameErr','msg' =>'Name is mandatory'));exit;
        }
        
        if(!$email)
        {
             echo json_encode(array('response' => 'failed','msg' =>'Email is mandatory','errid'=>'emailErr'));exit;
             
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Invalid email format";
              echo json_encode(array('response' => 'failed','msg' =>$emailErr,'errid'=>'emailErr'));exit;
    
        }
        if(!$txt_msg)
        {
            echo json_encode(array('response' => 'failed','msg' =>'Message content is mandatory','errid'=>'txtErr'));exit;
        }
        if($_FILES['cvfile']['size'] <=0 )
        {
             echo json_encode(array('response' => 'failed','msg' =>'Resume is mandatory','errid'=>'fileErr '));exit;
        }
// print_r($_FILES);exit;
        //to admin
        $tmp_name = $_FILES['cvfile']['tmp_name']; // get the temporary file name of the file on the server 



        $name    = $_FILES['cvfile']['name']; // get the name of the file 
        $size    = $_FILES['cvfile']['size']; // get size of the file for size validation 
        $type    = $_FILES['cvfile']['type']; // get type of the file 
        $error   = $_FILES['cvfile']['error']; // get the error (if any) 
        $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file 
        $content = fread($handle, $size); // reading the file 
        fclose($handle);                 // close upon completion 
        $encoded_content = chunk_split(base64_encode($content)); 

        $fullpath = './assets/resume';
        Applib::create_dir($fullpath);
        $config['upload_path'] = $fullpath;
        $config['allowed_types'] = '*';
        $config['max_size'] = config_item('file_max_size');
        $config['overwrite'] = FALSE;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if(!$this->upload->do_upload("cvfile")) {
         $this->session->set_flashdata('tokbox_success', $this->upload->display_errors('<span style="color:red">', '</span><br>'));

               redirect('jobs');

            } else {
                
                $fileinfs = $this->upload->data();
//print_r($fileinfs['full_path']);exit;
            }

        
        $admin_mail = User::view_user('1');//admin user id
        $params2['recipient'] = $admin_mail->email;
       // $params2['attached_file'] = $content; // Attaching the encoded file with email * /
        $message = App::email_template('admin_job','template_body');
        $subject2 = App::email_template('admin_job','subject');//*/
        $logo_link = create_email_logo();
        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $msg = str_replace("{APLYMSG}",$txt_msg,$logo);
        $cntnt = str_replace("{USRNM}",$uname,$msg);
        
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$cntnt);
        

        $params2['subject'] = 'Job '.$subject2.':'.$job_role;
        $params2['message'] = $message;

        $params2['attached_file'] = isset($fileinfs['full_path']) ? $fileinfs['full_path']:'';//$name;// unlink($name); 

        $db_ins=  array('offer_id' => $offer_id,'job_type'=>$job_roleid,'name'=>$uname,'email'=>$email ,'message'=>$txt_msg,'filename'=> $fileinfs['file_name'],'file_type'=>$fileinfs['file_ext'],'file_path'=>$fullpath );
        
         $appl_insert_id = $this->db->insert('candidates',$db_ins);
         $insert_id = $this->db->insert_id();
         $approver_list = App::approvers_list($offer_id);
        $batch_array =array();
        foreach ($approver_list as $key => $value) {
            $batch_array[]=array('appr_id'=>$value['approvers'],'applicant_id'=>$insert_id,'offer_id'=>$offer_id,'appr_subid'=>$value['id']);
        } 
        $this->db->insert_batch('candidate_assoc', $batch_array);
 
        modules::run('fomailer/send_email',$params2);
//  print_r($params2);exit;      
        // $this->session->set_flashdata('tokbox_success', 'Job has applied successfully ');
        //$this->session->set_flashdata('tokbox_success', 'Job has applied successfully ');

        //to candidate/applicant

       
        $message = App::email_template('job_application','template_body');
        $subject = App::email_template('job_application','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'auth/login',$logo);
        $username = str_replace("{USERNAME}",$uname,$site_url);
        //$user_email =  str_replace("{EMAIL}",$data['email'],$username);
        //$user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$username);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] =  $subject;
        $params['message'] = $message;

        $params['attached_file'] = ''; 
        modules::run('fomailer/send_email',$params);

        $return_data =array('response'=>'success','msg'=>'Resume has sent successfully','url'=>base_url()); 
        $this->session->set_flashdata('su_message', 'Job has applied successfully ');
 
        echo json_encode($return_data);exit();
         // redirect(base_url());

    }

    function loginuser_check(){
        $user_type = $this->input->post('user_type');
        if($user_type == 'admin'){
            $user_details = array(
                'username' => 'admin',
                'password' => 'Dreams@99'
            );
        }
        if($user_type == 'client'){
            $user_details = array(
                'username' => 'client',
                'password' => '123456'
            );
        }
        if($user_type == 'user'){
            $user_details = array(
                'username' => 'employee',
                'password' => '123456'
            );
        }
        echo json_encode($user_details); exit;
    }
   
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
