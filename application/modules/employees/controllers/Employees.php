<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class employees extends MX_Controller { 

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        // if($this->session->userdata('role_id') != 1){
        //     redirect();
        // }
        $this->load->helper('security');
        $this->load->model(array('Client','App','Lead','Users'));
        $this->load->model('employees_details','employees');
        $this->load->library(array('tank_auth','form_validation'));
    }
    function index(){
        $this->active();
    }

    function active()
    {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(lang('users').' - '.config_item('company_name'));
    $data['page'] = lang('all employees');
    $data['datatables'] = TRUE;
    $data['form'] = TRUE;
    $data['country_code'] = TRUE;
    $data['companies'] = Client::get_all_clients();

    if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
        $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
    }
    else{
        $branches = $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
    }

    $data['branches'] = $branches;

    $data['departments'] = Client::get_all_departments();
    $this->template
    ->set_layout('users')
    ->build('employees',isset($data) ? $data : NULL);
    }

    function permissions()
    {
        if ($_POST) {
             $permissions = json_encode($_POST);
             $data = array('allowed_modules' => $permissions);
             App::update('account_details',array('user_id' => $_POST['user_id']),$data);
             $this->session->set_flashdata('tokbox_success', lang('settings_updated_successfully'));
            redirect(base_url().'employees');

        }else{
            $staff_id = $this->uri->segment(4);

            if (User::login_info($staff_id)->role_id != '3') {
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect($_SERVER['HTTP_REFERRER']);
            }
            $data['user_id'] = $staff_id;
            $this->load->view('modal/edit_permissions',isset($data) ? $data : NULL);
        }
    }


    function update()
    {
        
        if ($this->input->post()) {
            if (config_item('demo_mode') == 'TRUE') {
            $this->session->set_flashdata('tokbox_error', lang('demo_warning'));
            redirect('employees');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
        $this->form_validation->set_rules('fullname', 'Full Name', 'required');

        if ($this->form_validation->run() == FALSE)
        {
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect('employees');
        }else{
            $user_id =  $this->input->post('user_id');
            $profile_data = array(
                            'fullname' => $this->input->post('fullname'),
                            'phone' => $this->input->post('phone'),
                            'mobile' => $this->input->post('mobile'),
                            'skype' => $this->input->post('skype'),
                            'doj' => $this->input->post('emp_doj'),
                            'language' => $this->input->post('language'),
                            'locale' => $this->input->post('locale'),
                            'hourly_rate' => $this->input->post('hourly_rate'),
                            'fatherName' => $this->input->post('fatherName')
                        );
            if (isset($_POST['department'])) {
                $profile_data['department'] = json_encode($_POST['department']);
            }
            App::update('account_details',array('user_id'=>$user_id),$profile_data);
            
            $designation_id = (!empty($this->input->post('designations')))?$this->input->post('designations'):'';
            App::update('users',array('id'=>$user_id),array('designation_id'=>$designation_id));

            $data = array(
                'module' => 'users',
                'module_field_id' => $user_id,
                'user' => User::get_id(),
                'activity' => 'activity_updated_system_user',
                'icon' => 'fa-edit',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => User::displayName($user_id),
                'value2' => ''
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', lang('user_edited_successfully'));
            redirect('employees');
        }
        }else{
        
        $data['id'] = $this->uri->segment(3);
        $this->load->view('modal/edit_user',$data);
        }
    }


    function ban()
    {

        if ($_POST) {
            $user_id = $this->input->post('user_id');
            $ban_reason = $this->input->post('ban_reason');
            $action = (User::login_info($user_id)->banned == '1') ? '0' : '1';

             $data = array('banned' => $action,'ban_reason' => $ban_reason);
             App::update('users',array('id' => $user_id),$data);
            $this->session->set_flashdata('tokbox_success', lang('settings_updated_successfully'));
            redirect(base_url().'employees');
        }else{
            $user_id = $this->uri->segment(4);
            $data['user_id'] = $user_id;
            $data['username'] = User::login_info($user_id)->username;
            $this->load->view('modal/ban_user',isset($data) ? $data : NULL);
        }
    }



    function auth()
    {
        if ($this->input->post()) {
            Applib::is_demo();

        $user_password = $this->input->post('password');
        $username = $this->input->post('username');
        $this->config->load('tank_auth',TRUE);

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('username', 'User Name', 'required|trim|xss_clean');

        if(!empty($user_password)) {
                $this->form_validation->set_rules('password', 'Password', "trim|required|xss_clean|min_length[4]|max_length[32]");
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
        }

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
            redirect('employees');
        }else{
                        date_default_timezone_set(config_item('timezone'));
            $user_id =  $this->input->post('user_id');
            $args = array(
                            'email'     => $this->input->post('email'),
                            'role_id'   => $this->input->post('role_id'),
                            'modified'  => date("Y-m-d H:i:s")
                        );

            $db_debug = $this->db->db_debug; //save setting
            $this->db->db_debug = FALSE; //disable debugging for queries
            $result = $this->db->set('username',$username)
                               ->where('id',$user_id)
                               ->update('users'); //run query
            $this->db->db_debug = $db_debug; //restore setting

            if(!$result){
                $this->session->set_flashdata('tokbox_error', lang('username_not_available'));
                redirect('employees');
            }

            App::update('users',array('id' => $user_id), $args);

            if(!empty($user_password)) {
                $this->tank_auth->set_new_password($user_id,$user_password);
            }

            $data = array(
                'module' => 'users',
                'module_field_id' => $user_id,
                'user' => User::get_id(),
                'activity' => 'activity_updated_system_user',
                'icon' => 'fa-edit',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => User::displayName($user_id),
                'value2' => ''
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', lang('user_edited_successfully'));
            redirect('employees');
        }
        }else{
        $data['id'] = $this->uri->segment(4);
        $this->load->view('modal/edit_login',$data);
        }
    }

    function designations(){
        if($this->input->post()){
            $depart_id = $this->input->post('department');
            $this->db->select('id,designation');
            $this->db->from('designation');
            $this->db->where('department_id', $depart_id);
            $this->db->order_by("designation", "asc");
            $records = $this->db->get()->result_array();
            echo json_encode($records);
            die();
        }
    }



    function delete()
    {
        if ($this->input->post()) {

        Applib::is_demo();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User ID', 'required');
        if ($this->form_validation->run() == FALSE)
        {
                $this->session->set_flashdata('tokbox_error', lang('delete_failed'));
                $this->input->post('r_url');
        }else{
            $user = $this->input->post('user_id',TRUE);
            $deleted_user = User::displayName($user);

            if (User::profile_info($user)->avatar != 'default_avatar.jpg') {
                if(is_file('./assets/avatar/'.User::profile_info($user)->avatar))
                unlink('./assets/avatar/'.User::profile_info($user)->avatar);
            }
            $user_companies = App::get_by_where('companies',array('primary_contact' => $user));
            foreach ($user_companies as $co) {
                $ar = array('primary_contact' => '');
                App::update('companies',array('primary_contact' => $user),$ar);
            }
            $user_tickets = App::get_by_where('tickets',array('reporter' => $user));
            foreach ($user_tickets as $ticket) {
                App::delete('tickets',array('reporter' => $user));
            }
            $user_bugs = App::get_by_where('bugs',array('reporter' => $user));
            foreach ($user_bugs as $bug) {
                App::delete('bugs',array('reporter' => $user));
            }
            $user_comments = App::get_by_where('comments',array('posted_by' => $user));

            foreach ($user_comments as $comment) {
                $replies = App::get_by_where('comment_replies',array('parent_comment' => $comment->comment_id));
                foreach ($replies as $key => $r) {
                    App::delete('comment_replies',array('parent_comment' => $comment->comment_id));
                }

            }
    
            App::delete('comments', array('posted_by' => $user));
            App::delete('messages', array('user_to' => $user));
            App::delete('assign_tasks', array('assigned_user' => $user));
            App::delete('assign_projects', array('assigned_user' => $user));
            App::delete('activities', array('user' => $user));

            App::delete('account_details', array('user_id' => $user));
            App::delete('users', array('id' => $user));

            $this->delete_profile($user_id);

            // Log activity
            $data = array(
                'module' => 'users',
                'module_field_id' => $user,
                'user' => User::get_id(),
                'activity' => 'activity_deleted_system_user',
                'icon' => 'fa-trash-o',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $deleted_user,
                'value2' => ''
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', lang('user_deleted_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        }else{
            $user_id = $this->uri->segment(4);
            $user_id = (is_numeric($user_id))?$user_id:'';
            if($user_id==''){
                $user_id = $this->uri->segment(3);
                $user_id = (is_numeric($user_id))?$user_id:'';    
            }
            $data['user_id'] = (!empty($user_id))?$user_id:0;

            $this->load->view('modal/delete_user',$data);
        }
    }
    /* Dreamguys 27/06/2018 Start PHP Developer */

    function employees_list()
    {
        //if($this->input->post()){
            
            $inputs  = $this->input->get();
            $branch_id[0] = $this->session->userdata('branch_id');
            $usertype_name = $this->session->userdata('user_type_name');
            if($usertype_name == 'company_admin') {
                $branch_details = $this->db->get_where('dgt_assigned_entities',array('user_id'=>$this->session->userdata('user_id')))->result_array(); 
                $branch_id = array_column($branch_details, 'branch_id');
            }
          /*  $limit   = 12;
            $inputs['limit']  = $limit;*/

            if(isset($inputs['entity_id']) && $inputs['entity_id'] != "")
            {
                $branch_id = $inputs['entity_id'];
            }

            $lists = $this->employees->get_employees_list($inputs,1,$branch_id);

            $records = array();

            $all_count = $this->employees->get_employees_list($inputs,2,$branch_id);
            // echo $this->db->last_query();exit;
            $records = $return_data = array();
            $return_data['recordsTotal'] = $all_count;
            $return_data['recordsFiltered'] = $all_count;
            $return_data['data']  = array();

            if(count($lists) >0){
                $i  = 0;
                foreach ($lists as $list) {
                   $list = (array) $list;

                    $this->db->select('dgt_razorpay_payroll_history.response_data');
                    $this->db->from("dgt_razorpay_payroll_history");
                    $this->db->where("user_id", $list['id']);
                    $this->db->order_by("id","desc");
                    $this->db->limit(1);
                    $query = $this->db->get();
                    $razorpay_response = $query->row_array();
                    $razorpay_response_status = '';
                    if(!empty($razorpay_response))
                    {
                        $responseData = json_decode($razorpay_response['response_data'], true);
                        if(!empty($responseData) && !empty($responseData['error']))
                        {
                            $razorpay_response_status = '<span style="color:red">'.$responseData['error']["message"].'</span>';
                        }
                        else{
                            $razorpay_response_status = '<span style="color:green">'.$responseData['status'].'</span>';
                        }
                    }


                    $return_data['data'][$i] = (array)$list;
                    $imgs = '';
                    if(empty($list['avatar'])){
                        $imgs = $list['avatar'];
                        
                    }else{
                        $imgs = "default_avatar.jpg";
                    }
                    $title = str_replace( array( '-' ), '', $list['emp_code']);

                    $return_data['data'][$i]['emp_code']=$title;

                    $return_data['data'][$i]['razorpay_response_status']=ucwords($razorpay_response_status);


                    $return_data['data'][$i]['fullname']    =   '<div class="user_det_list"><a href="'.base_url().'employees/profile_view/'.$list['id'].'">';
                    $img_file = return_img(base_url().'assets/avatar/'.$imgs);
                    $return_data['data'][$i]['fullname'] .= '<img class="avatar" src="'.$img_file.'">';
                    
                    $depart_id = $list['department_id'];
                    $return_data['data'][$i]['designations'] = (!empty($depart_id))?$this->employees->get_designations($depart_id):array();

                    $return_data['data'][$i]['fullname'] .= '<h2><span class="username-info">'.$list['fullname'].'</span></a><span class="userrole-info">'.$list['designation'].'</span></h2></div>';


                    $entities = $this->db->select('dgt_branches.branch_name')
                            ->from('dgt_branches')
                            ->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')
                            ->where('dgt_branches.branch_status','0')
                            ->where('dgt_assigned_entities.user_id',$list['id'])
                            ->get()->result();
                    $arr_entity = array_column($entities, 'branch_name');
                    $entity_name= implode(",", $arr_entity);
                  /*  if($list['user_type'] != '44') {
                        $return_data['data'][$i]['entity_name'] = $list['branch_name'];
                    } else {*/
                        $return_data['data'][$i]['entity_name'] = $list['branch_name'];
                   // }
                    if($list['activated'] == 1)
                    {
                        $return_data['data'][$i]['user_status'] = 'Active';
                    }else{
                        $return_data['data'][$i]['user_status'] = 'InActive';
                    }
                    $return_data['data'][$i]['action']  =   '<div class="text-right"><div class="dropdown"><a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a><ul class="dropdown-menu pull-right"><li><a href="'. base_url(). 'employees/profile_view/'.$list['id'].'"   title="Employee"><i class="fa fa-pencil m-r-5"></i>Edit</a></li><li><a href="'.base_url().'employees/reset_password/'.$list['id'].'"  data-toggle="ajaxModal"><i class="fa fa-unlock-alt m-r-5"></i>Reset Password</a></li><li><a href="'.base_url(). 'employees/delete/'.$list['id'].'"  data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a></li>';
                    if($list['activated'] == 2 ){
                        $return_data['data'][$i]['action'] .= '<li><a href="'.base_url().'employees/change_inactive/'.$list['id'].'" ><i class="fa fa-eye m-r-5"></i>Active</a></li>';
                    }else{
                        $return_data['data'][$i]['action'] .= '<li><a href="'.base_url().'employees/change_inactive/'.$list['id'].'" ><i class="fa fa-eye-slash m-r-5"></i>InActive</a></li>';
                    }
                    $return_data['data'][$i]['action'] .= '</ul></div></div>';
                    $i++;
                }
            }
            $count   = $this->employees->get_employees_list($inputs,2,$branch_id);
            $total_page = 1;
            if($count > $limit){
                $total_page = ceil($count /$limit);
            }
            $array = array();
           /* $array['current_page'] = $inputs['page'];
            $array['total_page']   = $total_page;
            $array['list']         = $records;*/
            // echo "<pre>";print_r($return_data);exit();
            echo json_encode($return_data);

        //}
        die();
    }

    function changedesignation(){
        if($this->input->post()){
            $params = $this->input->post();
            echo $this->employees->changedesignation($params);

        }
        die();
    }

    function change_inactive($user_id){
        $user_det = $this->db->get_where('users',array('id'=>$user_id))->row_array();
        if($user_det['activated'] == 2)
        {
            $stat = 1;
        }else{
            $stat = 2;
        }
        $res = array(
            'activated' =>$stat,
            'status'=>0,
        );

        $this->db->where('id',$user_id);
        $this->db->update('dgt_users',$res);


     
        $this->activate_stat($user_id, $stat);

        $this->load->model("Razorpay_payroll","razorpay");
        $this->razorpay->dismiss_employee($user_det , $user_id);


        $this->session->set_flashdata('tokbox_success', 'Status Updated Success');
        redirect('employees'); exit;
    }

    function check_user_email()
    {
        $user_email = $this->input->post('user_email');
        $check_email = $this->employees->check_useremail($user_email);
        if($check_email > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }

    function check_username()
    {
        $user_name = $this->input->post('check_username');
        $check_username = $this->employees->check_username($user_name);
        if($check_username > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    
    
    public function profile_view($id)
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('View Profile - '.config_item('company_name'));
        $data['page'] = lang('all employees');
        // $data['datatables'] = TRUE;
        $data['form'] = TRUE;
        $data['country_code'] = TRUE;
        $data['employee_details'] = $this->employees->get_employeedetailById($id);
        // echo "<pre>";print_r($data['employee_details'] );exit;
        $data['personal_details'] = $this->employees->get_employeepersonalById($id);
        $data['branches']   = array();
       if( $this->session->userdata('role_id') != 1 && $this->session->userdata('user_type_name') !='company_admin') {
            if($this->session->userdata('user_id') != $id){
                redirect('attendance'); exit;
            }
        }
        if($this->session->userdata('user_type_name') =='company_admin'){
            $all_branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
            $data['branches'] = array_column($all_branches, 'branch_id');
            
            if (!in_array($data['employee_details']['branch_id'], $data['branches']))
            {
                redirect('employees'); exit;
            }
        }
        $data['view_id'] = $id;
            $this->template
        ->set_layout('users')
        ->build('employees/profile_view',isset($data) ? $data : NULL);
    }
    
    public function get_profile_view($user_id){
        if ($this->input->post()) {
            
            
            $chk_employee_branch=$this->db->select('*')
                                             ->from('account_details AD')
                                             ->join('users U','U.id = AD.user_id')
                                             // ->where('AD.user_id',$user_id)
                                             ->where('U.email',$this->input->post('emailid'))
                                             ->where('AD.branch_id',$this->input->post('branch_id'))
                                             ->get()->result_array();
                                     
            if(count($chk_employee_branch)==0)
            {
                //inactive users
                 $res = array(
                        'activated' => 2
                    );
                $this->db->where('email',$this->input->post('emailid'));
                $this->db->update('dgt_users',$res);
                //
                
                $result = $this->DuplicateRecord($table='dgt_users', $primary_key_field='id', $primary_key_val = $user_id);
                $get_employee_profile=$this->db->select('*')
                                             ->from('account_details AD')
                                             // ->join('account_details AD','U.id = AD.user_id')
                                             ->where('AD.user_id',$user_id)
                                             ->get()->row_array();
                // echo '<pre>';print_r($get_employee_profile);exit;    
                $profile = array(
                    'user_id'   => $result,
                    'company'   => $get_employee_profile['company'],
                    'fullname'  => $get_employee_profile['fullname'],
                    'city'      => $get_employee_profile['city'],
                    'state'     => $get_employee_profile['state'],
                    'address'   => $get_employee_profile['address'],
                    'pincode'   => $get_employee_profile['pincode'],
                    'phone'     => $get_employee_profile['phone'],
                    'doj'       => $get_employee_profile['doj'],
                    'branch_id'     => $this->input->post('branch_id'),
                    'avatar'    => 'default_avatar.jpg',
                    'language'  => config_item('default_language') ? config_item('default_language') : 'english',
                    'locale'    => config_item('locale') ? config_item('locale') : 'en_US'
                );  
                $this->db->insert('dgt_account_details',$profile);
                                
                // print_r($result);exit;
            }
            else
            {
                // echo '<pre>';print_r($chk_employee_branch);exit;
                $get_employee_det_by_branch=$this->db->select('*')
                                             ->from('dgt_users U')
                                             // ->join('account_details AD','U.id = AD.user_id')
                                             ->where('U.email',$this->input->post('emailid'))
                                             ->get()->result_array();
                 $res = array(
                        'activated' => 2
                    );
                    $this->db->where('email',$this->input->post('emailid'));
                    $this->db->update('dgt_users',$res);
                    
                    //branch update
                    $this->db->where('id',$chk_employee_branch[0]['id']);
                    // $this->db->where('branch_id',$this->input->post('branch_id'));
                    $this->db->update('dgt_users',array(
                        'activated' => 1
                    ));
                    // 
            }
            // exit;
           
            $this->session->set_flashdata('tokbox_success', 'Entity Changed');
            redirect('employees');
        }else{
            $user_id = $this->uri->segment(4);
            $user_id = (is_numeric($user_id))?$user_id:'';
            if($user_id==''){
                $user_id = $this->uri->segment(3);
                $user_id = (is_numeric($user_id))?$user_id:'';    
            }
            $data['user_id'] = (!empty($user_id))?$user_id:0;

            $this->load->view('modal/changeuser_branch',$data); 
        }
    }
    
    
    function DuplicateRecord($table, $primary_key_field, $primary_key_val) { 
    // echo 'fdgfd';exit;
    /* CREATE SELECT QUERY */ 
    $this->db->where($primary_key_field, $primary_key_val); 
    $query = $this->db->get($table); 
    
    foreach ($query->result() as $row){ 
        foreach($row as $key=>$val) { 
            if($key != $primary_key_field) {                 
                //Below code can be used instead of passing a data array directly to the insert or update functions  
                $this->db->set('activated',1); 
                $this->db->set($key, $val); 
            } //endif 
        } //endforeach 
    } //endforeach 
    
    //insert the new record into table 
     $this->db->insert($table); 
     return $this->db->insert_id();
     // echo '<pre>';print_r($this->db->last_query());exit;
}

public function edit_profile($id)
{
    if (!$_POST) {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Edit Profile - ' . config_item('company_name'));
        $data['page'] = lang('all employees');
        $data['form'] = TRUE;
        $data['employee_details'] = $this->employees->get_employeedetailById($id);
        $data['personal_details'] = $this->employees->get_employeepersonalById($id);
        $this->template
             ->set_layout('users')
             ->build('employees/edit_profile', isset($data) ? $data : NULL);
    } else {
        $profile = array(
            'fullname' => $this->input->post('full_name'),
            'dob' => date("Y-m-d", strtotime($this->input->post('dob'))),
            'gender' => $this->input->post('gender'),
            'address' => $this->input->post('address'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country'),
            'pincode' => $this->input->post('pincode'),
            'phone' => $this->input->post('phone')
        );

        $education = array();
        $personal = array();
        
        $institute = $this->input->post('institute');
        $subject = $this->input->post('subject');
        $yoc = $this->input->post('yoc');
        $degree = $this->input->post('degree');
        for ($i = 0; $i < count($institute); $i++) {
            $education[] = array(
                'institute' => $institute[$i],
                'subject' => $subject[$i],
                'yoc' => $yoc[$i],
                'degree' => $degree[$i]
            );
        }

        $past_company = $this->input->post('past_company');
        $past_company_loc = $this->input->post('past_company_loc');
        $job_position = $this->input->post('job_position');
        $period_from = $this->input->post('period_from');
        $period_to = $this->input->post('period_to');
        for ($i = 0; $i < count($past_company); $i++) {
            $personal[] = array(
                'company_name' => $past_company[$i],
                'company_location' => $past_company_loc[$i],
                'job_position' => $job_position[$i],
                'period_from' => $period_from[$i],
                'period_to' => $period_to[$i]
            );
        }

        $department = $data['employee_details']['department'];
        $avatar = $data['employee_details']['avatar'];
        $branch_id = $data['employee_details']['branch_id'];

        if ($branch_id) {
            $this->load->model("Employees_details");
            $branch = $this->Employees_details->get_branch_by_id($branch_id);
            if (!empty($branch)) {
                $branch_name = $branch['branch_name'];
            } else {
                $branch_name = "Branch not found.";
            }
        } else {
            $branch_name = "No branch ID provided.";
        }

        $full_name = $this->input->post('full_name');
        $name_parts = explode(' ', $full_name);
        $first_name = array_shift($name_parts);
        $last_name = implode(' ', $name_parts);

        $full_name = $inputs['fullname'];
        $name_parts = explode(' ', $full_name, 2);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
        $profile_data = array(
            'email' => $inputs['email'],
            'password' => $inputs['password'],
            'entity_name' => $branch_name,
            'category_name' => $category_name,
            'role_name' =>$role_name,
            'avatar' => $avatar,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phonenumber' => $inputs['phone'],
            'birth_date' => date("Y-m-d", strtotime($this->input->post('dob'))),
            'is_employee' => !empty($inputs['is_employee']) ? $inputs['is_employee'] : false,
            'is_instructor' => !empty($inputs['is_instructor']) ? $inputs['is_instructor'] : false,
            'is_manager' => !empty($inputs['is_manager']) ? $inputs['is_manager'] : false,
            'is_admin' => !empty($inputs['is_admin']) ? $inputs['is_admin'] : false,
            'is_hotel' => !empty($inputs['is_hotel']) ? $inputs['is_hotel'] : false
        );
       

        if (isset($response['message']) && $response['message'] == "Profile updated successfully") {
            // Update the local database
            $check_exist = $this->db->get_where('dgt_account_details', array('user_id' => $id))->num_rows();
            if ($check_exist == 0) {
                $profile['user_id'] = $id;
                $this->db->insert('dgt_account_details', $profile);
            } else {
                $this->db->where('user_id', $id);
                $this->db->update('dgt_account_details', $profile);
            }

            $result = array(
                'education_details' => json_encode($education),
                'personal_details' => json_encode($personal)
            );
            $pers_check = $this->db->get_where('dgt_users_personal_details', array('user_id' => $id))->num_rows();
            if ($pers_check == 0) {
                $result['user_id'] = $id;
                $this->db->insert('dgt_users_personal_details', $result);
            } else {
                $this->db->where('user_id', $id);
                $this->db->update('dgt_users_personal_details', $result);
            }

            $this->session->set_flashdata('tokbox_success', 'Profile Updated');
        } else {
            $this->session->set_flashdata('tokbox_error', 'Failed to update profile: ' . $response['error']);
        }

        redirect(base_url() . 'employees/profile_view/' . $id);
    }
}


    function basic_info_add($user_id)
    {
        // echo "<pre>";print_r($_POST);exit;

        $branch_id = (!empty($this->session->userdata('branch_id')))?$this->session->userdata('branch_id'):0;
        if($branch_id==0){
            $branch_id = (!empty($this->input->post('branch')))?$this->input->post('branch'):0;
        }
        $dynamic_field = (!empty($this->session->userdata('dynamic_field')))?$this->session->userdata('dynamic_field'):0;
        if($this->input->post('branch_prefix') != '') {
            $emp_code   = $this->input->post('branch_prefix').$this->input->post('employee_id');
        } else {
            $emp_code   = $this->input->post('employee_id');
        }


        $basic_info = array(
                'fullname' =>$this->input->post('full_name'),
                'dob' =>date("Y-m-d", strtotime($this->input->post('dob'))),
                'gender' =>$this->input->post('gender'),
                'address' =>$this->input->post('address'),
                'state' =>$this->input->post('state'),
                'city' =>$this->input->post('city'),
                'pincode' =>$this->input->post('pincode'),
                'phone' =>$this->input->post('phone'),
                'reporting_to2'=>$this->input->post('reporting_to2'),
                'personal_email'=>$this->input->post('personal_email'),
                //'emp_gjd'=>(!empty($this->input->post('emp_gjd')))?date('Y-m-d',strtotime($this->input->post('emp_gjd'))):'',
                'dob'=>(!empty($this->input->post('dob')))?date('Y-m-d',strtotime($this->input->post('dob'))):'',
                'seat_location'=>$this->input->post('seat_location'),
                'division'=>$this->input->post('division'),
                'business_unit'=>$this->input->post('business_unit'),
                'cost_center'=>$this->input->post('cost_center'),
                'branch_id'=>$branch_id,
                'dynamic_field'=>$dynamic_field,
                'employee_id' => $this->input->post('employee_id'),
                'emp_code' => $emp_code,
                'doj' => $this->input->post('emp_doj'),
                'fatherName' => $this->input->post('fatherName'),
                'employee_level'  => $this->input->post('employee_level')
            );

        if(!empty($this->input->post('exit_date'))){
            $basic_info['exit_date']    = $this->input->post('exit_date');
        }
        if(!empty($this->input->post('location'))){
            $basic_info['location1']    = $this->input->post('location');
        }
        if(!empty($_POST['type_change'])){
            $basic_info['type_change']  = $this->input->post('type_change');
        }

        $pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->num_rows();
        $this->form_validation->set_rules('employee_id', 'Employee Id', 'trim|required|xss_clean');
        // if ($this->form_validation->run($this)) {
        if($pers_check == 0)
        {
            $basic_info['user_id'] = $user_id;
            $this->db->insert('account_details',$basic_info);
        }
        else{
            // unset($basic_info['emp_code']);
            $this->db->where('user_id',$user_id);
            $this->db->update('account_details',$basic_info);
        }

        $some_details = array(
            'department_id' => $this->input->post('department_name'),
            'designation_id'=> $this->input->post('designations'),
            'teamlead_id'=> $this->input->post('reporting_to'),
            'user_type'=> $this->input->post('user_type'),
            'is_reporter' => $this->input->post('is_reporter'),
             'email' =>$this->input->post('email')
        );
        // echo "<pre>";print_r($some_details);exit;
        $this->db->where('id',$user_id);
        $this->db->update('users',$some_details);
        // echo $this->db->last_query();exit;
        
        $this->db->where('id',$user_id);
        if($this->db->update('users',$some_details))
        {
            $res = array(
                'is_teamlead' =>'yes' 
                );
                $this->db->where('id',$this->input->post('reporting_to'));
                $this->db->update('users',$res);
        }
        
        $this->callUpdateProfileAPI($user_id);
        

        $chk_entity=$this->db->select('*')->from('user_entity')->where('user_id',$user_id)->where('status',1)->get()->result_array();
        if(empty($chk_entity)){
            $ins_data = array(
                'user_id'   => $user_id,
                'entity_id' => $branch_id,
                'status'    => 1
            );  
            $this->db->insert('user_entity',$ins_data); 
        }
        if(!empty($this->input->post('designations'))){

            $some_details = array(
                'grade' => $this->input->post('employee_level'),
            );

            $this->db->where('grade',$this->input->post('designations'));
            $this->db->update('dgt_designation',$some_details);
        }

        // $inputs = $this->input->post();
       
       
        

        $this->load->model("Razorpay_payroll","razorpay");
        
         $response = $this->razorpay->check_employee_exits($user_id);


        if($response['error']['code'] == "8")
        {
            $this->db->select('du.email,dad.fullname');
            $this->db->from("dgt_users as du");
            $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
            $this->db->where("du.id", $user_id);
            $query = $this->db->get();
            $employee_data = $query->row_array();

            $data = array();
            $data['email'] = $employee_data['email'];
            $data['fullname'] = $employee_data['fullname'];
            
            $this->razorpay->add_employee($data, $user_id);
        }
        else{
            $this->razorpay->update_employee($user_id, 'update');
        }

        
        
     

        $this->session->set_flashdata('tokbox_success', 'Profile Basic Information Updated');
    // }
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }

    function personal_info_add($user_id)
    {
        $personal_info = array(
                'aadhar' =>$this->input->post('aadhar_no'),
                'pan_card' =>$this->input->post('pan_card_no'),
                'tel_number' =>$this->input->post('tel_number'),
                'nationality' =>$this->input->post('nationality'),
                'religion' =>$this->input->post('religion'),
                'marital_status' =>$this->input->post('marital_status'),
                'spouse' =>$this->input->post('spouse'),
                'no_children' =>$this->input->post('no_children')
            );
        $result = array(
                'personal_info' => json_encode($personal_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
           $this->db->where('user_id',$user_id);
           $this->db->update('users_personal_details',$result);
        }
        
        $this->session->set_flashdata('tokbox_success', 'Personal Information Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }


    function emergency_info_add($user_id)
    {
        $emergency_info = array(
                'contact_name1' =>$this->input->post('contact_name1'),
                'relationship1' =>$this->input->post('relationship1'),
                'contact1_phone1' =>$this->input->post('contact1_phone1'),
                'contact1_phone2' =>$this->input->post('contact1_phone2'),
                'contact_name2' =>$this->input->post('contact_name2'),
                'relationship2' =>$this->input->post('relationship2'),
                'contact2_phone1' =>$this->input->post('contact2_phone1'),
                'contact2_phone2' =>$this->input->post('contact2_phone2')
            );
        $result = array(
                'emergency_info' => json_encode($emergency_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }
        
        $this->session->set_flashdata('tokbox_success', 'Emergency Information Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }


    function bank_info_add($user_id)
    {
        $bank_info = array(
                'bank_name' =>$this->input->post('bank_name'),
                'bank_ac_no' =>$this->input->post('bank_ac_no'),
                'ifsc_code' =>$this->input->post('ifsc_code'),
                'pan_no' =>$this->input->post('pan_no')
            );
        $result = array(
                'bank_info' => json_encode($bank_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }

        //  Added Razorpay api for update bank employee
        $this->load->model("Razorpay_payroll","razorpay");
        $this->razorpay->update_employee($user_id, 'update');

        $this->session->set_flashdata('tokbox_success', 'Bank Information Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }


    function family_info_add($user_id)
    {
        $member_names = $this->input->post('member_name'); 
        $member_relationship = $this->input->post('member_relationship'); 
        $member_dob = $this->input->post('member_dob'); 
        $member_phone = $this->input->post('member_phone'); 
        $family_members = array();
        for($i = 0; $i< count($member_names); $i++)
            {
                $members = array(
                    'member_name'=>$member_names[$i],
                    'member_relationship'=>$member_relationship[$i],
                    'member_dob'=>$member_dob[$i],
                    'member_phone'=>$member_phone[$i]
                );
                $family_members[] = $members;
            }
        $result = array(
                'family_members_info' => json_encode($family_members)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
           $this->db->where('user_id',$user_id);
           $this->db->update('users_personal_details',$result);
        }
        
        $this->session->set_flashdata('tokbox_success', 'Family Members Information Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }


    function education_info_add($user_id)
    {
        // echo "<pre>"; print_r($_POST); exit;
        $institute = $this->input->post('institute'); 
        $subject = $this->input->post('subject'); 
        $start_date = $this->input->post('start_date'); 
        $end_date = $this->input->post('end_date'); 
        $degree = $this->input->post('degree'); 
        $grade = $this->input->post('grade'); 
        $educations = array();
        for($i = 0; $i< count($institute); $i++)
            {
                $education = array(
                    'institute'=>$institute[$i],
                    'subject'=>$subject[$i],
                    'start_date'=>$start_date[$i],
                    'end_date'=>$end_date[$i],
                    'degree'=>$degree[$i],
                    'grade'=>$grade[$i]
                );
                $educations[] = $education;
            }
            // echo $user_id; exit;
        $result = array(
                'education_details' => json_encode($educations)
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => json_encode($educations)
            );
            $this->db->insert('users_personal_details',$res);
        }
        // print_r($r); exit;
        $this->session->set_flashdata('tokbox_success', 'Education Information Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }


    function experience_info_add($user_id)
    {
        // echo "<pre>"; print_r($_POST); exit;
        $company_name = $this->input->post('company_name'); 
        $location = $this->input->post('location'); 
        $job_position = $this->input->post('job_position'); 
        $period_from = $this->input->post('period_from'); 
        $period_to = $this->input->post('period_to');
        $personals = array();
        for($i = 0; $i< count($company_name); $i++)
            {
                $personal = array(
                    'company_name'=>$company_name[$i],
                    'location'=>$location[$i],
                    'job_position'=>$job_position[$i],
                    'period_from'=>$period_from[$i],
                    'period_to'=>$period_to[$i]
                );
                $personals[] = $personal;
            }
            // echo $user_id; exit;
        $result = array(
                'personal_details' => json_encode($personals)
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => json_encode($educations)
            );
            $this->db->insert('users_personal_details',$res);
        }
        // print_r($r); exit;
        $this->session->set_flashdata('tokbox_success', 'Education Information Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
              
    }


    function teamlead_options(){
        $des_id = $this->input->post('des_id');
        $dept_id = $this->input->post('dept_id');
        $branch_id = $this->input->post('branch_id');
        $r = $this->db->get_where('designation',array('id'=>$des_id))->row_array();
        // $grade = $r['grade'];
        // $grade_details = $this->db->get_where('grades',array('grade_id'=>$grade))->row_array();
        // $all_grades = $this->db->get_where('designation',array('grade <='=>$grade))->result_array();
        $all_users = array();
        // foreach ($all_grades as $grades) {
            // $this->db->group_by('designation_id');
            // $user_details = $this->db->select('*')
            //                          ->from('users U')
            //                          ->join('account_details AD','U.id = AD.user_id')
            //                          // ->where('U.designation_id',$grades['id'])
            //                          ->where('U.designation_id',$grades['id'])
            //                        //  ->where('U.role_id',3)
            //                          ->where('U.activated',1)
            //                          ->where('U.banned',0)
            //                          ->where('U.is_teamlead','yes')
            //                          ->get()->result_array();

            // New Code
            $user_details = $this->db->select('AD.*')
                                ->from('users U')
                                ->join('account_details AD','U.id = AD.user_id')
                                // ->where('U.designation_id',$des_id)
                                // ->where('U.department_id',$dept_id)
                                // ->where('AD.branch_id',$branch_id)
                                ->where('U.is_reporter','yes')
                                ->where('U.activated',1)
                                // ->where('U.is_teamlead','yes')
                                ->where('U.banned',0)
                                ->get()->result_array();
            // New Code

            // $user_details = $this->db->get_where('users',array('designation_id'=>$grades['id'],'role_id'=>3,'activated'=>1,'banned'=>0))->result_array();
            foreach($user_details as $users)
            {
                if(count($user_details) != 0)
                {

                $user = array(
                    'id' => $users['user_id'],
                    'username' => $users['fullname']
                );
                $all_users[] = $user;
                }
            }
        // }

    foreach ($all_users as $key => $row) {
        $id[$key]  = $row['id'];
        $username[$key] = $row['username'];
    }

    $username = array_map('strtolower', $username);

    array_multisort($username, SORT_ASC, SORT_STRING, $all_users);

        echo json_encode($all_users); exit;

    }



    function employee_edit($user_id)
    {
        $this->load->view('modal/create');
    }



    function employee_profile_upload(){
        Applib::is_demo();

        if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
            $current_avatar = User::profile_info($this->input->post('user_id'))->avatar;

                            $config['upload_path'] = './assets/avatar/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['overwrite'] = FALSE;

                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('file'))
                                    {
                                        echo $this->upload->display_errors(); exit;
                            }else{
                                        $data = $this->upload->data();
                                        $ar = array('avatar' => $data['file_name']);
                                        App::update('account_details',array('user_id'=>$this->input->post('user_id')),$ar);
                                        
                                if(file_exists('./assets/avatar/'.$current_avatar) 
                                    && $current_avatar != 'default_avatar.jpg'){
                                    unlink('./assets/avatar/'.$current_avatar);
                                }
                            }
                }

                if(isset($_POST['use_gravatar']) && $_POST['use_gravatar'] == 'on'){
                    $ar = array('use_gravatar' => 'Y');
                    App::update('account_details',array('user_id'=>$this->input->post('user_id')),$ar);

                }else{ 
                    $ar = array('use_gravatar' => 'N');
                    App::update('account_details',array('user_id'=>$this->input->post('user_id')),$ar);
                }
                echo 'success'; exit;


    }

    function bank_statutory()
    {
        $user_id = $this->input->post('bankuser_id');
        $salary = $this->input->post('user_salary');
        $payment_type = $this->input->post('payment_type');
        $pf_contribution = $this->input->post('pf_contribution')?$this->input->post('pf_contribution'):'';
        $pf_no = $this->input->post('pf_no')?$this->input->post('pf_no'):'';
        $pf_rates = $this->input->post('pf_rates')?$this->input->post('pf_rates'):'';
        $pf_add_rates = $this->input->post('pf_add_rates')?$this->input->post('pf_add_rates'):'';
        $pf_total_rate = $this->input->post('pf_total_rate')?$this->input->post('pf_total_rate'):'';
        $pf_employer_contribution = $this->input->post('pf_employer_contribution')?$this->input->post('pf_employer_contribution'):'';
        $employer_add_rates = $this->input->post('employer_add_rates')?$this->input->post('employer_add_rates'):'';
        $employer_total_rates = $this->input->post('employer_total_rates')?$this->input->post('employer_total_rates'):'';
        $esi_contribution = $this->input->post('esi_contribution')?$this->input->post('esi_contribution'):'';
        $esi_no = $this->input->post('esi_no')?$this->input->post('esi_no'):'';
        $esi_rate = $this->input->post('esi_rate')?$this->input->post('esi_rate'):'';
        $esi_add_rate = $this->input->post('esi_add_rate')?$this->input->post('esi_add_rate'):'';
        $esi_total_rate = $this->input->post('esi_total_rate')?$this->input->post('esi_total_rate'):'';

        $all_details = array(
            'pf_contribution' =>$pf_contribution,
            'pf_no' =>$pf_no,
            'pf_rates' =>$pf_rates,
            'pf_add_rates' =>$pf_add_rates,
            'pf_total_rate' =>$pf_total_rate,
            'pf_employer_contribution' =>$pf_employer_contribution,
            'employer_add_rates' =>$employer_add_rates,
            'employer_total_rates' =>$employer_total_rates,
            'esi_contribution' =>$esi_contribution,
            'esi_no' =>$esi_no,
            'esi_rate' =>$esi_rate,
            'esi_add_rate' =>$esi_add_rate,
            'esi_total_rate' =>$esi_total_rate
        );

        $result = array(
            'salary'  => $salary,
            'payment_type' => $payment_type,
            'bank_statutory' => json_encode($all_details) 
        );
        $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        // echo count($check_status); exit;
        if(count($check_status) == 0 )
        {
            $result['user_id'] = $user_id;
            // echo "<pre>"; print_r($result); exit;
            $this->db->insert('dgt_bank_statutory',$result);
        }else{
            // echo "<pre>"; print_r($result); exit;
            $this->db->where('user_id',$user_id);
            $this->db->update('dgt_bank_statutory',$result);
        }
        $this->session->set_flashdata('tokbox_success', 'Bank Statutory Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
    }


    // function addtional_pf_details()
    // {
    //     $user_id = $this->input->post('user_id');
    //     $res = array(
    //         'addtion_name' => $this->input->post('addtion_name'),
    //         'category_name' => $this->input->post('category_name'),
    //         'unit_amount' => $this->input->post('unit_amount')
    //     );
    //     $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
    //     if(count($check_status) == 0 )
    //     {
    //         $res['id'] = 1;
    //         $result =array(
    //             'pf_addtional' => json_encode(array($res))
    //         );
    //         $result['user_id'] = $user_id;
    //         $this->db->insert('dgt_bank_statutory',$result);
    //     }else{
    //         $addtional_details = json_decode($check_status['pf_addtional'],TRUE);
    //         if(is_array($addtional_details))
    //         {
    //             $res['id'] = count($addtional_details) + 1;
    //             array_push($addtional_details,$res);
    //             $pf_add = array( 'pf_addtional' => json_encode($addtional_details));
    //         }else{
    //             $res['id'] = 1;
    //             $pf_add = array( 'pf_addtional' => json_encode(array($res)));
    //         }
    //         $this->db->where('user_id',$user_id);
    //         $this->db->update('dgt_bank_statutory',$pf_add);
    //     }
    //     $this->session->set_flashdata('tokbox_success', 'PF Addtional Updated');
    //     redirect(base_url().'employees/profile_view/'.$user_id);

    // }

    function addtional_pf_details()
    {
        $user_id = $this->input->post('user_id');
        $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        $post_data = $this->input->post();
        unset($post_data['user_id']);
        unset($post_data['0']);

        if(count($check_status) == 0 )
        {
            $array_temp_data = array();
            $anual_basic_salary = array();

            $i= 1;
            foreach($post_data as $key => $post_data_val)
            {
                $payroll_addition = array();
                $payroll_addition = array(
                    "category_name"         => "addtional",
                    "addtion_name"          => str_replace("_"," ", $key),
                    "unit_amount"           => $post_data_val,
                    "id"                    => $i
                );

                if($i == 1)
                {
                    array_push($anual_basic_salary,$post_data_val);
                } 
                array_push($array_temp_data, $payroll_addition);
                $i++;
            }
            $result =array(
                "salary"        =>  array_sum($anual_basic_salary),
                'pf_addtional'  =>  json_encode($array_temp_data)
            );
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_bank_statutory',$result);
        }
        else
        {
            $array_temp_data = array();
            $anual_basic_salary = array();
            $i= 1;
            foreach($post_data as $key => $post_data_val)
            {
                $payroll_addition = array();
                $payroll_addition = array(
                    "category_name"         => "addtional",
                    "addtion_name"          => str_replace("_"," ", $key),
                    "unit_amount"           => $post_data_val,
                    "id"                    => $i
                );

                if($i== 1)
                {
                    array_push($anual_basic_salary,$post_data_val);
                } 
                array_push($array_temp_data, $payroll_addition);
                $i++;
            }

            $result =array(
                "salary"        =>  array_sum($anual_basic_salary),
                'pf_addtional' => json_encode($array_temp_data)
            );
            $this->db->where('user_id',$user_id);
            $this->db->update('dgt_bank_statutory',$result);
        }

        //  Added Razorpay api for set salary
        $this->load->model("Razorpay_payroll","razorpay");
        $this->razorpay->set_salary($user_id);

        $this->session->set_flashdata('tokbox_success', 'PF Addtional Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
    }

    function edit_additional($ar_id)
    {
        $user_id = $this->input->post('user_id');
        $get_data = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        $addtional = json_decode($get_data['pf_addtional'],TRUE);
        foreach($addtional as $key => $value)
        {
            if($value['id'] == $ar_id)
            {
              $addtional[$key] =array(
                'addtion_name' => $this->input->post('addtion_name'),
                'category_name' => $this->input->post('category_name'),
                'unit_amount' => $this->input->post('unit_amount'),
                'id' => $ar_id
              );
            }
        }   
        $updated_addtional = array('pf_addtional' => json_encode($addtional));
        $this->db->where('user_id',$user_id);
        $this->db->update('dgt_bank_statutory',$updated_addtional);
        $this->session->set_flashdata('tokbox_success', 'PF Addtional Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);

    }


    function delete_pfaddtional()
    {
        $ar_id = $this->input->post('arid');
        $user_id = $this->input->post('user_id');
        $keyid = $this->input->post('keyid');

        $get_data = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        $addtional = json_decode($get_data['pf_addtional'],TRUE);
        unset($addtional[$keyid]);
        if(!empty($addtional)){
            $updated_addtional = array('pf_addtional' => json_encode($addtional));
        }else{
            $updated_addtional = array('pf_addtional' =>'');
        }
        $this->db->where('user_id',$user_id);
        $this->db->update('dgt_bank_statutory',$updated_addtional);
        echo "success"; exit;
    }

    // function add_deduction()
    // {
    //     $user_id = $this->input->post('user_id');
    //     $res = array(
    //         'model_name' => $this->input->post('model_name'),
    //         'unit_amount' => $this->input->post('unit_amount')
    //     );
    //     $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
    //     if(count($check_status) == 0 )
    //     {
    //         $res['id'] = 1;
    //         $result =array(
    //             'pf_deduction' => json_encode(array($res))
    //         );
    //         $result['user_id'] = $user_id;
    //         $this->db->insert('dgt_bank_statutory',$result);
    //     }else{
    //         $addtional_details = json_decode($check_status['pf_deduction'],TRUE);
    //         if(is_array($addtional_details))
    //         {
    //             $res['id'] = count($addtional_details) + 1;
    //             array_push($addtional_details,$res);
    //             $pf_add = array( 'pf_deduction' => json_encode($addtional_details));
    //         }else{
    //             $res['id'] = 1;
    //             $pf_add = array( 'pf_deduction' => json_encode(array($res)));
    //         }
    //         $this->db->where('user_id',$user_id);
    //         $this->db->update('dgt_bank_statutory',$pf_add);
    //     }
    //     $this->session->set_flashdata('tokbox_success', 'PF Addtional Updated');
    //     redirect(base_url().'employees/profile_view/'.$user_id);
    // }

    function add_deduction()
    {
        $user_id = $this->input->post('user_id');
        $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        $post_data = $this->input->post();
        unset($post_data['user_id']);
        unset($post_data['0']);

        if(count($check_status) == 0 )
        {
            $array_temp_data = array();
            $i= 1;
            foreach($post_data as $key => $post_data_val)
            {
                $payroll_deduction = array();
                $payroll_deduction = array(
                    "category_name"         => "deduction",
                    "model_name"            => str_replace("_"," ", $key),
                    "unit_amount"           => $post_data_val,
                    "id"                    => $i
                );
                array_push($array_temp_data, $payroll_deduction);
                $i++;
            }
            $result =array(
                'pf_deduction' => json_encode($array_temp_data)
            );
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_bank_statutory',$result);
        }
        else{
            $array_temp_data = array();
            $i= 1;
            foreach($post_data as $key => $post_data_val)
            {
                $payroll_deduction = array();
                $payroll_deduction = array(
                    "category_name"         => "deduction",
                    "model_name"            => str_replace("_"," ", $key),
                    "unit_amount"           => $post_data_val,
                    "id"                    => $i
                );
                array_push($array_temp_data, $payroll_deduction);
                $i++;
            }
            $result =array(
                'pf_deduction' => json_encode($array_temp_data)
            );
            $this->db->where('user_id',$user_id);
            $this->db->update('dgt_bank_statutory',$result);
        }
        
        //  Added Razorpay api for PF Deduction
        $this->load->model("Razorpay_payroll","razorpay");
        $this->razorpay->set_salary($user_id);

        $this->session->set_flashdata('tokbox_success', 'PF Addtional Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
    }
    
    function edit_pfdeduction($ar_id){
        $user_id = $this->input->post('user_id');
        $get_data = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        $deduction = json_decode($get_data['pf_deduction'],TRUE);
        foreach($deduction as $key => $value)
        {
            if($value['id'] == $ar_id)
            {
              $deduction[$key] =array(
                'model_name' => $this->input->post('model_name'),
                'unit_amount' => $this->input->post('unit_amount'),
                'id' => $ar_id
              );
            }
        }   
        $updated_deduction = array('pf_deduction' => json_encode($deduction));
        $this->db->where('user_id',$user_id);
        $this->db->update('dgt_bank_statutory',$updated_deduction);
        $this->session->set_flashdata('tokbox_success', 'PF Addtional Updated');
        redirect(base_url().'employees/profile_view/'.$user_id);
    }


    function delete_pfdeduction()
    {
        $ar_id = $this->input->post('arid');
        $user_id = $this->input->post('user_id');
        $keyid = $this->input->post('keyid');

        $get_data = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
        $addtional = json_decode($get_data['pf_deduction'],TRUE);
        unset($addtional[$keyid]);
        if(!empty($addtional)){
            $updated_addtional = array('pf_deduction' => json_encode($addtional));
        }else{
            $updated_addtional = array('pf_deduction' =>'');
        }
        $this->db->where('user_id',$user_id);
        $this->db->update('dgt_bank_statutory',$updated_addtional);
        echo "success"; exit;
    }


    // function add_overtime()
    // {
    //     $user_id = $this->input->post('user_id');
    //     $res = array(
    //         'model_name' => $this->input->post('ot_description'),
    //         'unit_amount' => date('Y-m-d',strtotime($this->input->post('ot_date'))),
    //         'hours' => $this->input->post('ot_hours')
    //     );
    //     $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$user_id))->row_array();
    //     if(count($check_status) == 0 )
    //     {
    //         $res['id'] = 1;
    //         $result =array(
    //             'overtime' => json_encode(array($res))
    //         );
    //         $result['user_id'] = $user_id;
    //         $this->db->insert('dgt_bank_statutory',$result);
    //     }else{
    //         $addtional_details = json_decode($check_status['overtime'],TRUE);
    //         if(is_array($addtional_details))
    //         {
    //             $res['id'] = count($addtional_details) + 1;
    //             array_push($addtional_details,$res);
    //             $pf_add = array( 'overtime' => json_encode($addtional_details));
    //         }else{
    //             $res['id'] = 1;
    //             $pf_add = array( 'overtime' => json_encode(array($res)));
    //         }
    //         $this->db->where('user_id',$user_id);
    //         $this->db->update('dgt_bank_statutory',$pf_add);
    //     }
    //     $this->session->set_flashdata('tokbox_success', 'Over Time updated');
    //     redirect(base_url().'employees/profile_view/'.$user_id);
    // }


      function add_overtime()
    {
        $user_id = $this->input->post('user_id');
        $res = array(
            'user_id'=>$user_id,
            'teamlead_id'=>$this->input->post('teamlead_id'),
            'ot_description' => $this->input->post('ot_description'),
            'ot_date' => date('Y-m-d',strtotime($this->input->post('ot_date'))),
            'ot_hours' => $this->input->post('ot_hours'),
            'status'=>0,
            'date_posted'=>date('Y-m-d H:i:s')

        );
        
        $this->db->insert('overtime',$res);
         $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $this->session->set_flashdata('tokbox_success', 'Over Time updated');
                redirect(base_url().'employees/profile_view/'.$user_id);
            }   
            else
            {
                 $this->session->set_flashdata('tokbox_error', 'Over Time update failed');
                 redirect(base_url().'employees/profile_view/'.$user_id);
            }
       
    }

    function overtime_cancel($id,$user_id)
    {
        
           
            $det['status']      = 3; 
            $this->db->update('overtime',$det,array('id'=>$id)); 
             $this->session->set_flashdata('tokbox_success', 'Over Time canceled');
                redirect(base_url().'employees/profile_view/'.$user_id);
      
    
    }

     function overtime_approve($id,$user_id)
    {
        
           
            $det['status']      = 1; 
            $this->db->update('overtime',$det,array('id'=>$id)); 
             $this->session->set_flashdata('tokbox_success', 'Over Time approved');
                redirect(base_url().'employees/profile_view/'.$user_id);
      
    
    }

     function overtime_reject($id,$user_id)
    {
        
           
            $det['status']      = 2; 
            $this->db->update('overtime',$det,array('id'=>$id)); 
             $this->session->set_flashdata('tokbox_success', 'Over Time rejected');
                redirect(base_url().'employees/profile_view/'.$user_id);
      
    
    }


    function add_depart_role()
    {
        $department = array(
            'deptname' => $this->input->post('department_name'),
            'branch_id' => $this->session->userdata('branch_id')
        );
        $this->db->insert('departments',$department);
        $depart_id = $this->db->insert_id();
        $roles = array(
            'department_id' => $depart_id,
            'grade' => $this->input->post('grade'),
            'designation' => $this->input->post('role_name')
        );
        $this->db->insert('designation',$roles);
        // echo $r; exit;
        $res = $this->db->where('branch_id',$this->session->userdata('branch_id'))->get('departments')->result_array();
        echo json_encode($res); exit;

    }


    function add_attachment($user_id)
    {

        $count = count($_FILES['attach_file']['name']);
        if($count != 0){
        $config['upload_path']          = './assets/uploads/profile_attachments/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|txt|doc|docx';
        $this->load->library('upload', $config);
        $image = array(); 
        $image =$_FILES;

        for ($i=0; $i<$count;$i++) { 

            $_FILES['attach_file']['name'] = $image['attach_file']['name'][$i];
            $_FILES['attach_file']['type'] = $image['attach_file']['type'][$i];
            $_FILES['attach_file']['tmp_name'] = $image['attach_file']['tmp_name'][$i];
            $_FILES['attach_file']['error'] = $image['attach_file']['error'][$i];
            $_FILES['attach_file']['size'] = $image['attach_file']['size'][$i];
             
            if ( ! $this->upload->do_upload('attach_file'))
            {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('tokbox_error', 'Attachments Not Uploaded');
            }
            else
            {
                $data = $this->upload->data();
                // print_r($data); exit;
                $res = array(
                            'user_id' => $user_id,
                            'attach_file' => $data['file_name'],
                        );
                if(!empty($_POST['fileDesc'])){
                    $res['description'] = $_POST['fileDesc'];
                }
                $this->db->insert('user_files',$res);
            }

        }
            $this->session->set_flashdata('tokbox_success', 'Attachments are Uploaded');
            redirect(base_url().'employees/profile_view/'.$user_id);
        }else{
            $this->session->set_flashdata('tokbox_error', 'Choose Attachment files');
            redirect(base_url().'employees/profile_view/'.$user_id);
        }

    }

    public function reset_password($user_id){
        if ($this->input->post()) {
            $password = $this->input->post('new_password');
            $new_password = $this->tank_auth->user_reset_password($password);

            $res = array(
                'password' => $new_password
            );
            $this->db->where('id',$user_id);
            $this->db->update('users',$res);
            $this->session->set_flashdata('tokbox_success', 'Password Changed');
            redirect('employees');
        }else{
            $user_id = $this->uri->segment(4);
            $user_id = (is_numeric($user_id))?$user_id:'';
            if($user_id==''){
                $user_id = $this->uri->segment(3);
                $user_id = (is_numeric($user_id))?$user_id:'';    
            }
            $data['user_id'] = (!empty($user_id))?$user_id:0;

            $this->load->view('modal/resetpassword_user',$data); 
        }
    }






    /* Dreamguys 25/02/2019 End */

    function add_document($user_id)
    {
        $count = count($_FILES['document_file']['name']);
        if($count != 0){
        $config['upload_path']          = './assets/uploads/user_document/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|txt|doc|docx|csv';
        $this->load->library('upload', $config);
        $image = array(); 
        $image =$_FILES;
        for ($i=0; $i<$count;$i++) { 

            $_FILES['attach_file']['name'] = $image['document_file']['name'][$i];
            $_FILES['attach_file']['type'] = $image['document_file']['type'][$i];
            $_FILES['attach_file']['tmp_name'] = $image['document_file']['tmp_name'][$i];
            $_FILES['attach_file']['error'] = $image['document_file']['error'][$i];
            $_FILES['attach_file']['size'] = $image['document_file']['size'][$i];
             
            if ( ! $this->upload->do_upload('attach_file'))
            {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('tokbox_error', 'Documents Not Uploaded');
            }
            else
            {
                $data = $this->upload->data();
                // print_r($data); exit;
                $res = array(
                            'user_id' => $user_id,
                            'document_name' => $_POST['document_name'][$i],
                            'document' => $data['file_name']
                        );
                
                $this->db->insert('user_document',$res);
            }

        }
            $this->session->set_flashdata('tokbox_success', 'Documents are Uploaded');
            redirect(base_url().'employees/profile_view/'.$user_id);
        }else{
            $this->session->set_flashdata('tokbox_error', 'Choose Attachment files');
            redirect(base_url().'employees/profile_view/'.$user_id);
        }

    }
    function edit_document($user_id)
    {
        extract($_POST);
        // print_r($_POST);exit();
        $dataInfo = array();
        $files = $_FILES;
        if($_FILES['attachments']['name']=="")
        {
            $file=$this->input->post('exist_file');
        }
        else
        {
            $file=$_FILES['attachments']['name'];
            $file = preg_replace('/\s+/', '_', $file);
            $_FILES['attachments']['name']= $files['attachments']['name'];
            $_FILES['attachments']['type']= $files['attachments']['type'];
            $_FILES['attachments']['tmp_name']= $files['attachments']['tmp_name'];
            $_FILES['attachments']['error']= $files['attachments']['error'];
            $_FILES['attachments']['size']= $files['attachments']['size'];    

            $config['upload_path'] = './assets/uploads/user_document/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|txt|doc|docx|csv';
            // $config['overwrite'] = true;
            $this->load->library('upload',$config);
            $this->upload->do_upload('attachments');
            $dataInfo[] = $this->upload->data();
        }           
        

            $data=array(
            'document_name'=>$edit_document_name,
            'document'=>$file
            );
            $this->db->where('id',$_POST['document_id']);
            $this->db->update('user_document',$data);  
            $this->session->set_flashdata('tokbox_success', 'Documents are Uploaded');
            redirect(base_url().'employees/profile_view/'.$user_id);
    }



    function edit_files_upload($user_id)
    {
        extract($_POST);
        // print_r($_POST);exit();
        $dataInfo = array();
        $files = $_FILES;
        if($_FILES['attachments_upload']['name']=="")
        {
            $file=$this->input->post('exist_file_upload');
        }
        else
        {
            $file=$_FILES['attachments_upload']['name'];
            $file = preg_replace('/\s+/', '_', $file);
            $_FILES['attachments_upload']['name']= $files['attachments_upload']['name'];
            $_FILES['attachments_upload']['type']= $files['attachments_upload']['type'];
            $_FILES['attachments_upload']['tmp_name']= $files['attachments_upload']['tmp_name'];
            $_FILES['attachments_upload']['error']= $files['attachments_upload']['error'];
            $_FILES['attachments_upload']['size']= $files['attachments_upload']['size'];    

            $config['upload_path'] = './assets/uploads/profile_attachments/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|txt|doc|docx|csv';
            // $config['overwrite'] = true;
            $this->load->library('upload',$config);
            $this->upload->do_upload('attachments_upload');
            $dataInfo[] = $this->upload->data();
        }           
        

            $data=array(
            'description'=>$fileDesc_edit,
            'attach_file'=>$file
            );
            $this->db->where('user_file_id',$_POST['file_uploads_id']);
            $this->db->update('user_files',$data);  
            $this->session->set_flashdata('tokbox_success', 'File Attachment are updated');
            redirect(base_url().'employees/profile_view/'.$user_id);
    }


    function delete_document($id){
        if (empty($id))
        {
            //$this->session->set_flashdata('tokbox_error', 'Invalid document');
            redirect(base_url().'employees/');
            exit;
        }
        $this->db->select('*');
        $this->db->from('user_document');
        $this->db->where('id', $id);
        $users = $this->db->get()->result_array();
        if (empty($users))
        {
           // $this->session->set_flashdata('tokbox_error', 'Invalid document');
            redirect(base_url().'employees');
            exit;
        }
        unlink(FCPATH.'assets/uploads/user_document/'.$users[0]['document']);
        $this->db->where('id', $id);
        $this->db->delete('user_document');
        $this->session->set_flashdata('tokbox_success', 'Document Deleted');
        redirect(base_url().'employees/profile_view/'.$users[0]['user_id']);
    }


    function delete_file_attachment($id){
        if (empty($id))
        {
            //$this->session->set_flashdata('tokbox_error', 'Invalid document');
            redirect(base_url().'employees/');
            exit;
        }
        $this->db->select('*');
        $this->db->from('user_files');
        $this->db->where('user_file_id', $id);
        $users = $this->db->get()->result_array();
        if (empty($users))
        {
           // $this->session->set_flashdata('tokbox_error', 'Invalid document');
            redirect(base_url().'employees');
            exit;
        }
        unlink(FCPATH.'assets/uploads/profile_attachments/'.$users[0]['attach_file']);
        $this->db->where('user_file_id', $id);
        $this->db->delete('user_files');
        $this->session->set_flashdata('tokbox_success', 'File Attachment Deleted');
        redirect(base_url().'employees/profile_view/'.$users[0]['user_id']);
    }

    function get_document($id){
        if(!empty($id)){
            $this->db->select('*');
            $this->db->from('user_document');
            $this->db->where('id', $id);
            $users = $this->db->get()->result_array();
            if (!empty($users))
            {
                echo json_encode($users[0]);
            }
            exit;
        }

    }

    function get_file_attachment($id){
        if(!empty($id)){
            $this->db->select('*');
            $this->db->from('user_files');
            $this->db->where('user_file_id', $id);
            $users = $this->db->get()->result_array();
            if (!empty($users))
            {
                echo json_encode($users[0]);
            }
            exit;
        }

    }

    public function get_branch_prefix() {
        
        $id = $this->input->post('id');
        $data = array();
        if(!empty($id)){ 

            $this->db->select('*');
            $this->db->from('dgt_branches');
            $this->db->where('branch_id',$id);
            $query = $this->db->get();
            $cur_branch = $query->row_array();




            $this->db->select('*');
            $this->db->from('dgt_branches');
            $this->db->where('branch_prefix',$cur_branch['branch_prefix']);
            $query = $this->db->get();
            $all_branches = $query->result_array();

            $branches = array_column($all_branches,'branch_id');
            


            $this->db->select('*');
            $this->db->from('dgt_users U');
            $this->db->join('account_details AD','U.id = AD.user_id');
            $this->db->where_in('AD.branch_id',$branches);
            $this->db->order_by("cast(AD.employee_id as SIGNED) ", "desc");
               $query = $this->db->get();
            if($query !== FALSE && $query->num_rows()>0){

                $users = $query->row_array();

                
                //$emp_id = count($users);    
                $emp_id = (int) $users['employee_id']; 
            }else{
                $emp_id = 0;   
            }
            $emp_id++;
            //$employee_no = '00'.$emp_id;
            $employee_no = str_pad($emp_id, 5, 0, STR_PAD_LEFT);
            
            $this->db->select('branch_prefix');
            $this->db->from('dgt_branches');
            $this->db->where('branch_id', $id);
            $branch = $this->db->get()->row_array();
            if (!empty($branch))
            {
                $data['branch_prefix'] = $branch['branch_prefix'];
            }
            $data['employee_id'] = $employee_no;
            echo json_encode($data);
            exit;
        }
    }
    
    public function get_branch_prefix_companyadmin() {
        $ids = $this->input->post('id');
        $impid = implode(',', $ids);
        
        $empl_id_gen=$this->db->select('*')
                        ->from('dgt_assigned_entities')
                        ->where_in('branch_id', $impid)
                        ->group_by('user_id')
                        ->get()->result_array();
                        if(empty($empl_id_gen) && isset($empl_id_gen)){
                            $emp_id = 0;   
                        }else{
                            $emp_id = count($empl_id_gen);    
                        }
                        $emp_id++;
                        $employee_no = '00'.$emp_id;
                        
                        // echo '<pre>';print_r($empl_id_gen);exit;
        $data = array();
        if(isset($ids) && !empty($ids))
        {
            foreach($ids as $id)
            {
                if(!empty($id)){
                    $users = $this->db->select('*')
                        ->from('dgt_users U')
                        ->join('account_details AD','U.id = AD.user_id')
                        ->where('AD.branch_id',$id)
                        ->get()->result_array();
                                // echo '<pre>';print_r($this->db->last_query());exit;

                    // if(empty($users) && isset($users)){
                        // $emp_id = 0;   
                    // }else{
                        // $emp_id = count($users);    
                    // }
                    // $emp_id++;
                    // $employee_no = '00'.$emp_id;
                    
                    $this->db->select('branch_prefix');
                    $this->db->from('dgt_branches');
                    $this->db->where('branch_id', $id);
                    $branch = $this->db->get()->row_array();
                    if (!empty($branch))
                    {
                        $data['branch_prefix'][] = substr($branch['branch_prefix'],0,5);
                    }
                    
                    
                }
            }
        }
$finalarr = implode("", $data['branch_prefix']);
        // echo '<pre>';print_r($finalarr);exit;
        $data['branch_prefix']=$finalarr;
        $data['employee_id'] = $employee_no;

        echo json_encode($data);
                    exit;
    }

    public function get_details($emp_id){

        $chk_employee_branch=$this->db->select('*')->from('account_details AD')->join('users U','U.id = AD.user_id')->where('U.id',$emp_id)->get()->result_array();
        $data = array();
        if(!empty($chk_employee_branch)){
            $data = $chk_employee_branch[0];
            $this->db->select('*')->from('branches')->where('branch_status','0')->where('branch_id',$data['branch_id']);
            $entities = $this->db->get()->result_array();
            $data['prefix'] = $entities[0]['branch_prefix'];

            $depart_id = $data['department_id'];
            $this->db->select('id,designation');
            $this->db->from('designation');
            $this->db->where('department_id', $depart_id);
            $this->db->order_by("designation", "asc");
            $designations = $this->db->get()->result_array();
            $reqDesignation = '';
            if(!empty($designations)){
                foreach($designations as $designation1){
                   $reqDesignation .=' <option value="'.$designation1['id'].'">'.$designation1['designation'].'</option>';
                }
            }
            $data['designation'] = $reqDesignation;

            $this->db->select('*');
            $this->db->from('transfer_history');
            $this->db->where('user_id', $emp_id);
            $this->db->where('status', 1);
            $history_data['historys'] = $this->db->order_by('id','desc')->get()->result_array();
            if(!empty($history_data['historys'])){
             $data['historys'] = $this->load->view('employee_history', $history_data, TRUE);
            }
            else{
                $data['historys'] = '';
            }

        }
        echo json_encode($data);
        exit;
    }
    public function transerEntity(){
        $this->load->module('layouts');
        $this->load->library('template');

        if ($this->input->post()) {
        
        $id = $this->input->post('employee');
        $chk_employee_branch=$this->db->select('*')->from('account_details AD')->join('users U','U.id = AD.user_id')->where('U.id',$id)->where('AD.branch_id',$this->input->post('branch_id'))->get()->result_array();
        if(count($chk_employee_branch)==0)
        {
            $res = array('branch_id'=> $this->input->post('branch_id'));
            $res['employee_id'] = $this->input->post('employee_id');
            
            $chk_account=$this->db->select('*')->from('account_details')->where('user_id',$id)->get()->row_array();

            $chk_user=$this->db->select('*')->from('users')->where('id',$id)->get()->row_array();
            $ins_data = array(
                'user_id'   => $id,
                'type_change'   => $this->input->post('type_change'),
                'department_id' =>$this->input->post('department_id'),
                'designation_id'    => $this->input->post('designations'),
                'report_to' => $this->input->post('report_to'),
                'location'  => $this->input->post('location'),
                'division'  => $this->input->post('division'),
                'business_unit' => $this->input->post('business_unit'),
                'branch_id' => $this->input->post('branch_id'),
                'prev_branch_id'    => $chk_account['branch_id'],
                'employee_id'   => $this->input->post('employee_id'),
                'prev_employee_id'  => $chk_account['employee_id'],
            );  
            if(!empty($this->input->post('emp_doj'))){
                $emp_doj = $this->input->post('emp_doj');
                $ins_data['effective_date'] = date('y-m-d',strtotime($emp_doj));
            }
            $this->db->insert('transfer_history',$ins_data);
            $this->session->set_flashdata('tokbox_success', 'Entity Changed on Effective Date After Update by Admin or Superadmin');
            redirect(base_url().'employees');
        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Please Change the Entity');
            redirect(base_url('employees/transerEntity'));
        }
                   
        
    }
    $data = array(
        'page'       => lang('all employees'),
        'employee_id'=>$id,
        'datepicker' => TRUE,
        'form'       => TRUE,
        'editor'     => TRUE,
        
    );
   
    $data['employee_details'] =  array();
    
    $this->db->select('id,grade');
    $this->db->from('designation');
    $this->db->where('id', $data['employee_details']['designation_id']);
    $designation = $this->db->get()->row_array();

    $this->db->select('grade_id');
    $this->db->from('grades');
    $this->db->where('grade_id', $designation['grade']);
    $grades = $this->db->get()->row_array();
    $data['grade_id'] = '';
    if(!empty($grades)){
        $data['grade_id'] = $grades['grade_id'];
    }
    
    $data['personal_details'] =   $data['leads'] = array();

    $this->db->select('*')->from('users U') ->join('account_details AD','U.id = AD.user_id',LEFT);
    $this->db->where('U.status',1);
    $data['users'] = $this->db->limit($params['length'],$params['start'])->get()->result_array();


    $branches = array();
    if($this->session->userdata('user_type_name') =='company_admin'){
        $all_branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
        $branches = array_column($all_branches, 'branch_id');
    }
    $this->db->select('*')->from('branches')->where('branch_status','0');
    if($this->session->userdata('user_type_name') =='company_admin'){
        $this->db->where_in('branch_id',$branches);
    }
    $data['entities'] = $this->db->get()->result_array();
  

    $this->db->select('*');
    $this->db->from('transfer_history');
    $this->db->where('user_id', $id);
    $this->db->where('status', 1);
    $data['historys'] = array();
    $this->template->set_layout('users')->build('employee_transfer',isset($data) ? $data : NULL);
}

public function get_reportemployee($des_id){
    $r = $this->db->get_where('designation',array('id'=>$des_id))->row_array();
    $grade = $r['grade'];
    $grade_details = $this->db->get_where('grades',array('grade_id'=>$grade))->row_array();
    $all_grades = $this->db->get_where('designation',array('grade <='=>$grade))->result_array();
    $all_users = array();
    foreach ($all_grades as $grades) {
        $user_details = $this->db->select('*')
                                 ->from('users U')
                                 ->join('account_details AD','U.id = AD.user_id')
                                 ->where('U.designation_id',$grades['id'])
                                 ->where('U.activated',1)
                                 ->where('U.banned',0)
                                 ->get()->result_array();

        foreach($user_details as $users)
        {
            if(count($user_details) != 0)
            {

            $user = array(
                'id' => $users['user_id'],
                'username' => $users['fullname']
            );
            $all_users[] = $user;
            }
        }
    }
    foreach ($all_users as $key => $row) {
        $id[$key]  = $row['id'];
        $username[$key] = $row['username'];
    }

    $username = array_map('strtolower', $username);

    array_multisort($username, SORT_ASC, SORT_STRING, $all_users);
    return $all_users;
}


function grid_employees()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('employees').' - '.config_item('company_name'));
        $data['page'] = lang('employees');        
        $data['sub_page'] = lang('all employees');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        if($this->input->post()){
            $inputs  = $this->input->post();
            $limit   = 10000000;
            $inputs['limit']  = $limit;
            
            $data['employees'] = $this->employees->get_employees_list($inputs,1,$this->session->userdata('branch_id'));
            $data['departments'] = Client::get_all_departments();
           
        }else{
            $limit   = 1000000;
            $inputs['limit']  = $limit;
           
            $data['employees'] = $this->employees->get_employees_list($inputs,1,$this->session->userdata('branch_id'));
            $data['departments'] = Client::get_all_departments();
        }
        
        // echo "<pre>";print_r($data['employees']); exit;

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('grid_employees', isset($data) ? $data : null);
    }

    function check_user_email_1()
    {
        $email = $this->input->post('employee_email');
        $check_usermail = $this->employees->check_useremail($email);
        $response = empty($check_usermail>0) ? TRUE : FALSE;
        echo json_encode($response); die();
    }
    function check_user_name_1()
    {
        $username = $this->input->post('username');
        $check_username = $this->employees->check_username($username);
        $response = empty($check_username>0) ? TRUE : FALSE;
        echo json_encode($response); die();
    }

    function employee_bulk_import()
    {
        $post = $this->input->post();
        $files = $_FILES;
        
        if(!empty($post) && !empty($files))
        {   
            if(isset($post['action_type']) && $post['action_type'] === 'update')
            {
                if(isset($post['info_update_for']) && $post['info_update_for'] == '1')
                {
                    $this->employees_bulk_update($post, $files);
                }
                if(isset($post['info_update_for']) && $post['info_update_for'] == '2')
                {
                    $this->employees_bank_statutory_update($post, $files);
                }
                if(isset($post['info_update_for']) && $post['info_update_for'] == '3')
                {
                    $this->zip_file_upload($post, $files);
                }
            }
        }
        else{
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('employee_bulk_import'));
            $data['page'] = lang('employee_bulk_import');   
            $data['title'] = lang('employee_bulk_import');        
            $data['form'] = true;

            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin')
            {
                $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result();
            }
            else
            {
                $branches = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result();
            }

            $data['entities'] = $branches;
             $this->template
            ->set_layout('users')
            ->build('employee_bulk_import',!empty($data)? $data : NULL);
        }
    }

    
    function employees_bulk_update($postData, $files)
    {   

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('file');
        $this->load->library('upload');
        
        $branch_id = $postData['branch_id'];

        // Set upload configuration
        $config['upload_path']   = './assets/uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size']      = 51200; // 50 MB

        $this->upload->initialize($config); 
        if ($this->upload->do_upload('import_csv')) {

            $fileData = $this->upload->data();
            $filePath = './assets/uploads/bulkImport/' . $fileData['file_name'];

            header('Content-Type: text/html; charset=utf-8');

            $file = fopen($filePath, 'rb');

            if($file !== false) 
            {
                $i = 2;
                //$delimiter = "\t";
                fgetcsv($file);
                $record_line_error = array();

                $action_by = $postData['action_by'];
                echo"<pre>";
                while (($row = fgetcsv($file)) !== false)
                {
                    $row = array_map('utf8_encode', $row);

                    $this->db->select("dgt_users.id as user_id");
                    $this->db->from("dgt_account_details");
                    $this->db->join("dgt_users","dgt_users.id = dgt_account_details.user_id","inner");        

                    if(isset($row[0]) && $row[0] != "" && $action_by == '1')
                    {
                        $this->db->where("dgt_account_details.emp_code", trim($row[0]));
                    }
                    else{

                        $this->db->where("dgt_users.email", trim($row[7]));
                    }

                    $this->db->group_by("dgt_users.id");
                    $query = $this->db->get();
                    
                    if(!$query)
                    {
                       return false;
                    }
                    
                    $emp_code = $row[0];

                    $emp_user_id = array();
                    $emp_user_id = $query->result_array();

                    if(!empty($emp_user_id))
                    {
                        $emp_full_name = '';

                        if($row[1] != "" )
                        {
                            $emp_full_name .= ucfirst(trim($row[1]));
                        }
                        if($row[2] != "" )
                        {
                            $emp_full_name .=' '.ucfirst(trim($row[2]));   
                        }
                        if($row[3] != "" )
                        {
                            $emp_full_name .=' '.ucfirst(trim($row[3]));
                        }

                        $emp_gender = '';

                        if($row[4] != "" )
                        {
                            $emp_gender .=ucfirst(trim($row[4]));   
                        }

                        $doj = '';

                        if($row[5] != "")
                        {
                            $emp_doj = date("Y-m-d", strtotime($row[5]));
                        }

                        $emp_designation = $emp_department = '';

                        if(isset($row[8]) && $row[8] != "")
                        {
                            $this->db->select("*");
                            $this->db->from("dgt_departments");
                            $this->db->where("deptname", trim($row[8]));
                            $department_query = $this->db->get();
                            if(!$department_query)
                            {
                                return false;
                            }
                            $department_result = $department_query->result_array();
                            if(!empty($department_result))
                            {
                                foreach($department_result as $department_result_val)
                                {
                                    $emp_department = $department_result_val['deptid'];
                                }
                            }
                            else{
                                $department_insert = array();
                                $department_insert = array(
                                    "deptname"  => trim($row[8]),
                                    "branch_id"  => $branch_id
                                );
                                $this->db->insert("dgt_departments", $department_insert);
                                $emp_department = $this->db->insert_id();
                            }
                        }

                        if(isset($row[6]) && $row[6] != "")
                        {
                            $this->db->select("*");
                            $this->db->from("dgt_designation");
                            $this->db->where("dgt_designation.designation", $row[6]);
                            $this->db->group_by("id");
                            $designation_query = $this->db->get();
                            if(!$designation_query)
                            {
                                return false;
                            }
                            $designation_result = array();
                            $designation_result = $designation_query->result_array();
                            if(!empty($designation_result))
                            {
                                foreach($designation_result as $designation_query_val)
                                {
                                    $emp_designation = $designation_query_val['id'];
                                }
                            }
                            else{
                                $designation_insert = array();
                                $designation_insert = array(
                                    "department_id"  => $emp_department,
                                    "designation"  => trim($row[6]),
                                    "branch_id"  => $branch_id,
                                    "created"    => date("Y-m-d H:i:s")
                                );
                                $this->db->insert("dgt_designation", $designation_insert);
                                $emp_designation = $this->db->insert_id();
                            }
                        }

                        $emp_email = '';
                        if($row[7] != "" )
                        {
                            $emp_email .= $row[7];
                        }

                        $worklocationName = '';
                        $seat_location = '';
                        if($row[9] != "" )
                        {
                            $worklocationName .= $row[9];
                            $seat_location .= $row[9];
                        }

                        $worklocationAddress1 = '';
                        if($row[10] != "" )
                        {
                            $worklocationAddress1 .= $row[10];
                            $seat_location .= ', '.$row[10];
                        }
                        $worklocationAddress2 = '';
                        if($row[11] != "" )
                        {
                            $worklocationAddress2 .= $row[11];
                            $seat_location .= ', '.$row[11];
                        }

                        $worklocationCity = '';
                        if($row[12] != "" )
                        {
                            $worklocationCity .= $row[12];
                            $seat_location .= ', '.$row[12];
                        }

                        $worklocationStateCode = '';
                        if($row[13] != "" )
                        {
                            $worklocationStateCode .= $row[13];
                            $seat_location .= ', '.$row[13];
                        }

                        $worklocationCountry = '';
                        if($row[14] != "" )
                        {
                            $worklocationCountry .= $row[14];
                            $seat_location .= ', '.$row[14];
                        }

                        $worklocationPostalCode = '';
                        if($row[15] != "" )
                        {
                            $worklocationPostalCode .= $row[15];
                            $seat_location .= ', '.$row[15];
                        }

                        $emp_status = '';

                        if($row[17] != "" && $row[17] === 'Exited')
                        {
                            $emp_status .= '0';
                        }
                        else{
                            $emp_status .= '1';
                        }

                        $emp_personal_email = '';
                        if($row[18] != "")
                        {
                            $emp_personal_email .= $row[18];
                        }

                        $empfatherName = '';
                        if($row[19] != "")
                        {
                            $empfatherName .= $row[19];
                        }

                        $empmobile = '';
                        if($row[20] != "")
                        {
                            $empmobile .= $row[20];
                        }

                        $emp_dob = '';
                        if($row[21] != "")
                        {
                            $emp_dob = date("Y-m-d", strtotime($row[21]));
                        }

                        $emp_address = '';
                        if($row[22] != "")
                        {
                             $emp_address .= $row[22];
                        }
                        if($row[23] != "")
                        {
                             $emp_address .= ', '.$row[23];
                        }

                        $emp_city = '';
                        if($row[24] != "")
                        {
                             $emp_city .= $row[24];
                        }

                        $emp_state = '';
                        if($row[25] != "")
                        {
                             $emp_state .= $row[25];
                        }

                        $emp_postal_code = '';
                        if($row[26] != "")
                        {
                             $emp_postal_code .= $row[26];
                        }

                        $emp_country = '';
                        if($row[27] != "")
                        {
                             $emp_country .= $row[27];
                        }

                        $emp_personal_info = array();

                        if($row[28] != "")
                        {
                             $emp_personal_info['pan_card'] = trim($row[28]);
                        }

                        $emp_payment_mode = '';

                        if($row[29] != "")
                        {
                             $emp_payment_mode .= strtolower(trim($row[29]));
                        }

                        $emp_bank_info = array();

                        if($row[28] != "")
                        {
                             $emp_bank_info['pan_no'] = trim($row[28]);
                        }

                        if($row[30] != "")
                        {
                             $emp_bank_info['bank_holder_name'] = trim($row[30]);
                        }
                        if($row[31] != "")
                        {
                             $emp_bank_info['bank_name'] = trim($row[31]);
                        }
                        if($row[32] != "")
                        {
                             $emp_bank_info['bank_ac_no'] = number_format($row[32]);
                        }
                        
                        if($row[33] != "")
                        {
                             $emp_bank_info['ifsc_code'] = trim($row[33]);
                        }

                        if($row[34] != "")
                        {
                             $emp_bank_info['account_type'] = trim($row[34]);
                        }

                        $emp_exit_date = '';
                        if($row[35] != "")
                        {
                             $emp_exit_date = date("Y-m-d", strtotime($row[35]));
                        }

                        $emp_cost_center = '';
                        if($row[36] != "")
                        {
                             $emp_cost_center .= trim($row[36]);
                        }
                        $emp_level = '';
                        if($row[37] != "")
                        {
                             $emp_level .= trim($row[37]);
                        }

                        $emp_group_doj = '';
                        if($row[38] != "" && $row[38] != "0000-00-00")
                        {
                             $emp_group_doj = date("Y-m-d", strtotime($row[38]));
                        }
                        else{
                            $emp_group_doj = $emp_doj;
                        }

                        $branch_result = array();
                        $this->db->select("branch_prefix");
                        $this->db->from("dgt_branches");
                        $this->db->where("branch_id", $branch_id);
                        $branch_query = $this->db->get();
                        $branch_result = $branch_query->result_array();
                        $temp_employee_id = '';
                         if(!empty($branch_result))
                         {
                            $branch_prefex = '';
                            $branch_prefex = $branch_result[0]['branch_prefix'];

                            $temp_employee_id_exp = explode($branch_prefex, $row[0]);
                            $temp_employee_id = '';
                            $temp_employee_id = $temp_employee_id_exp[1];

                         }
                        
                        // Start - Employee Personal Info Update.
                        $user_account_data = array();
                        $user_account_data = array(
                            "emp_code"      => $emp_code,
                            "fullname"      => $emp_full_name,
                            "gender"        => $emp_gender,
                            "doj"           => $emp_doj,
                            "department"    => $emp_department,
                            "updated_date"  => date("Y-m-d H:i:s"),
                            "employee_id"   => $temp_employee_id,
                            "branch_id"     => $branch_id,
                            "emp_gjd"       => $emp_group_doj,
                            "employee_level"=> $emp_level,
                            "cost_center"   => $emp_cost_center,
                            "worklocationName"=>$worklocationName,
                            "worklocationAddress1"=>$worklocationAddress1,
                            "worklocationAddress2"=>$worklocationAddress2,
                            "worklocationCity"=> $worklocationCity,
                            "worklocationStateCode"=> $worklocationStateCode,
                            "worklocationCountry"=> $worklocationCountry,
                            "worklocationPostalCode"=> $worklocationPostalCode,
                            "seat_location" =>$seat_location,
                            "personal_email"    =>  $emp_personal_email,
                            "fatherName"        =>  $empfatherName,
                            "phone"            => $empmobile,
                            "mobile"            => $empmobile,
                            "dob"               => $emp_dob,
                            "address"           => $emp_address,
                            "city"              => $emp_city,
                            "state"             => $emp_state,
                            "country"           => $emp_country,
                            "pincode"           => $emp_postal_code
                            // "employee_level"    => $emp_level,
                            // "cost_center"       => $emp_cost_center,

                         );

                        foreach($emp_user_id as $emp_data)
                        {
                            $emp_id = $emp_data['user_id'];
                        }
                                           
                        $this->db->where("user_id",$emp_id);
                        $this->db->update("dgt_account_details", $user_account_data);
                       
                        // End - Employee Personal Info Update.

                        // Start - Employee Details Add / Edit
                        $family_member = array();
                        $family_member[] = array(
                            'member_name'=>$empfatherName,
                            'member_relationship'=>'father'
                        );

                        $result = array();
                        $result = array(
                               // 'bank_info' => json_encode($emp_bank_info),
                                'personal_info'=> json_encode($emp_personal_info),
                                'family_members_info'=> json_encode($family_member)
                            );


                        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$emp_id))->num_rows();
                        if($pers_check == 0)
                        {
                            $result['user_id'] = $emp_id;
                            $this->db->insert('dgt_users_personal_details',$result);
                        }
                        else
                        {
                            $this->db->where('user_id',$emp_id);
                            $this->db->update('users_personal_details',$result);
                        }

                        // End - Employee Bank Details Add/Edit

                        /* Start - User Account Info */

                        $user_info = array();
                        $user_info = array(
                            "exit_date"         =>  $emp_exit_date,
                            "status"            =>  $emp_status,
                            "email"             =>  $emp_email,
                            "designation_id"    =>  $emp_designation,
                            "department_id"    =>  $emp_department,
                            "modified"          =>  date("Y-m-d H:i:s")
                        );

                        $this->db->where("id",$emp_id);
                        $this->db->update("dgt_users", $user_info);  
                        // echo $this->db->last_query();die;

                        /* End - User Account Info */

                        //  Added Razorpay api for update employee
                            $this->load->model("Razorpay_payroll","razorpay");
                            
                            $response = $this->razorpay->check_employee_exits($emp_id);

                            if($response['error']['code'] == "8")
                            {
                                $this->db->select('du.email,dad.fullname');
                                $this->db->from("dgt_users as du");
                                $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
                                $this->db->where("du.id", $emp_id);
                                $query = $this->db->get();
                                $employee_data = $query->row_array();

                                $data = array();
                                $data['email'] = $employee_data['email'];
                                $data['fullname'] = $employee_data['fullname'];

                                $this->razorpay->add_employee($data, $emp_id);
                            }
                            else{
                                $this->razorpay->update_employee($emp_id, 'update');
                            }
                    }
                    else{
                         array_push($record_line_error,  $i); 
                    }
                    $i++;
                }
            } 
            if(!empty($record_line_error) && sizeof($record_line_error) > 0)
            {
                $implode_rows_num = implode(",", $record_line_error);
                $this->session->set_flashdata('tokbox_success', lang('csv_file_import_success').' '.lang('employee_no_exist').' : '. $implode_rows_num);
            }
            else{
                 $this->session->set_flashdata('tokbox_success', lang('csv_file_import_success'));
            }
           
            // unlink($filePath);

            redirect(base_url('employees/employee_bulk_import'));
        } 
        else {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('tokbox_error', $error);
            redirect(base_url('employees/employee_bulk_import'));
        }
    }

    /* Downlaod CSV file for employee update records */
    function employeeSampleCsv()
    {
        if(isset($_GET['file']) && $_GET['file'] =='basicsample')
        {
            $file_path = FCPATH . 'assets/uploads/employeeImportTemplate.csv';
        }

        if(isset($_GET['file']) && $_GET['file'] =='banksample')
        {
            $file_path = FCPATH . 'assets/uploads/employeeImportBankTemplate.csv';
        }
        

        if (file_exists($file_path)) {
            $this->load->helper('download');

            // Set the headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . basename($file_path));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));

            // Read the file and send it to the browser
            readfile($file_path);

            // Exit the script to prevent any additional output
            exit(); 
            $this->session->set_flashdata('tokbox_success', lang('employee_csv_file_exported'));
            redirect(base_url('employees/employee_bulk_import'));
        }
        else
        {
            $this->session->set_flashdata('tokbox_error', lang('employee_csv_file_exported_error'));
            redirect(base_url('employees/employee_bulk_import'));
        } 
    } 

    public function employees_bank_statutory_update($postData, $files)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('file');
        $this->load->library('upload');
        
        $branch_id = $postData['branch_id'];
        // Set upload configuration
        $config['upload_path']   = './assets/uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size']      = 51200; // 50 MB

        $this->upload->initialize($config); 
        if ($this->upload->do_upload('import_csv')) 
        {
            $fileData = $this->upload->data();
            $filePath = './assets/uploads/' . $fileData['file_name'];

            header('Content-Type: text/html; charset=utf-8');

            $file = fopen($filePath, 'rb');
            if($file !== false) 
            {
                $i = 2;
                //$delimiter = "\t";
                fgetcsv($file);
                $record_line_error = array();

                $action_by = $postData['action_by'];
                echo"<pre>";
                while (($row = fgetcsv($file)) !== false)
                {
                    $row = array_map('utf8_encode', $row);

                    $this->db->select("dgt_users.id as user_id, dgt_bank_statutory.payment_type as payment_mode");
                    $this->db->from("dgt_account_details");
                    $this->db->join("dgt_users","dgt_users.id = dgt_account_details.user_id","inner");   
                    $this->db->join("dgt_bank_statutory","dgt_bank_statutory.user_id = dgt_account_details.user_id","left");     

                    if(isset($row[0]) && $row[0] != "" && $action_by == '1')
                    {
                        $this->db->where("dgt_account_details.emp_code", trim($row[0]));
                    }
                    else{

                        $this->db->where("dgt_users.email", trim($row[2]));
                    }

                    $this->db->group_by("dgt_users.id");
                    $query = $this->db->get();
                    
                    if(!$query)
                    {
                       return false;
                    }

                    $emp_user_id = array();
                    $emp_user_id = $query->result_array();

                    if(!empty($emp_user_id))
                    {
                        $emp_id = $emp_payment_mode = '';
                        foreach($emp_user_id as $emp_data)
                        {
                            $emp_id = $emp_data['user_id'];
                            $emp_payment_mode = $emp_data['payment_mode'];
                        }

                        //  gross ctc (annual)
                        $annual_gross_salary = 0;
                        if($row[9] != "" && $row[9] != "")
                        {
                            $annual_gross_salary = $row[9];
                        }

                        //  basic salary (annual)
                        $annual_baisc_salary = 0;
                        if($row[10] != "" && $row[10] != "")
                        {
                            $annual_baisc_salary = $row[10];
                        }

                        // DA (annual)
                        $annual_da = 0;
                        if($row[11] != "" && $row[11] != "")
                        {
                            $annual_da = $row[11];
                        }

                        // HRA (annual)
                        $annual_hra = 0;
                        if($row[12] != "" && $row[12] != "")
                        {
                            $annual_hra = $row[12];
                        }

                        // SA (annual)
                        $annual_sa = 0;
                        if($row[13] != "" && $row[13] != "")
                        {
                            $annual_sa = $row[13];
                        }

                        // LTA (annual)
                        $annual_lta = 0;
                        if($row[14] != "" && $row[14] != "")
                        {
                            $annual_lta = $row[14];
                        }

                        // Conveyance Allowance (annual)
                        $annual_conveyance_allowance = 0;
                        if($row[15] != "" && $row[15] != "")
                        {
                            $annual_conveyance_allowance = $row[15];
                        }

                        // Conveyance Allowance (annual)
                        $annual_medical_allowance = 0;
                        if($row[16] != "" && $row[16] != "")
                        {
                            $annual_medical_allowance = $row[16];
                        }

                        // Variable (annual)
                        $annual_variable = 0;
                        if($row[17] != "" && $row[17] != "")
                        {
                            $annual_variable = $row[17];
                        }

                        //Start -  PF Details 
                        $pf_amount_employer = '';
                        if($row[18] != "" && $row[18] != "")
                        {
                            $pf_amount_employer = $row[18];
                        }
                        $pf_amount_employee = '';
                        if($row[19] != "" && $row[19] != "")
                        {
                            $pf_amount_employee = $row[19];
                        }

                        $accomodation_deduction = '';
                        if($row[20] != "" && $row[20] != "")
                        {
                            $accomodation_deduction = $row[20];
                        }
                        $other_deduction = '';
                        if($row[21] != "" && $row[21] != "")
                        {
                            $other_deduction = $row[21];
                        }

                        //End -  PF Details 

                        /* Start - Bank & Statutory Add/ Edit */
                        $bank_statutory = array();
                        $bank_statutory = array(
                            "pf_total_rate"         => $pf_amount_employee,
                            "employer_total_rates"  => $pf_amount_employer
                        );

                        $payroll_addition = $payroll_deduction = array();

                        // Start - Payroll Addition Fields

                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "Gross CTC (annual)",
                            "unit_amount"           => $annual_gross_salary,
                            "id"                    => "1"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "Basic Salary",
                            "unit_amount"           => $annual_baisc_salary,
                            "id"                    => "2"
                        );

                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "DA",
                            "unit_amount"           => $annual_da,
                            "id"                    => "3"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "HRA",
                            "unit_amount"           => $annual_hra,
                            "id"                    => "4"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "SA",
                            "unit_amount"           => $annual_sa,
                            "id"                    => "5"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "LTA",
                            "unit_amount"           => $annual_lta,
                            "id"                    => "6"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "Conveyance Allowance",
                            "unit_amount"           => $annual_conveyance_allowance,
                            "id"                    => "7"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "Medical Allowance",
                            "unit_amount"           => $annual_medical_allowance,
                            "id"                    => "8"
                        );
                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "Variable",
                            "unit_amount"           => $annual_variable,
                            "id"                    => "9"
                        );

                        $payroll_addition[] = array(
                            "category_name"         => "addtional",
                            "addtion_name"          => "PF Amount Employer (annual)",
                            "unit_amount"           => $pf_amount_employer,
                            "id"                    => "10"
                        );

                        // End - Payroll Addition Fields



                        // Start - Payroll Deduction Fields

                        $payroll_deduction[] = array(
                            "category_name"         => "deduction",
                            "model_name"            => "PF Amount Employee (annual)",
                            "unit_amount"           => $pf_amount_employee,
                            "id"                    => "1"
                        );

                        $payroll_deduction[] = array(
                            "category_name"         => "deduction",
                            "model_name"            => "Accomodation Deduction",
                            "unit_amount"           => $other_deduction,
                            "id"                    => "2"
                        );

                        $payroll_deduction[] = array(
                            "category_name"         => "deduction",
                            "model_name"            => "Other Deduction",
                            "unit_amount"           => $accomodation_deduction,
                            "id"                    => "3"
                        );
                        // End - Payroll Deduction Fields

                        $result = array();
                        $result = array(
                                'salary'            =>  $annual_gross_salary,
                                'payment_type'      =>  $emp_payment_mode,
                                'bank_statutory'    =>  json_encode($bank_statutory),
                                'pf_addtional'      =>  json_encode($payroll_addition),
                                'pf_deduction'      =>  json_encode($payroll_deduction)
                        );
                        
                        $check_status = array();
                        $check_status = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$emp_id))->row_array();
                        if(count($check_status) == 0 )
                        {
                            $result['user_id'] = $emp_id;
                            $this->db->insert('dgt_bank_statutory',$result);
                        }else{
                            $this->db->where('user_id',$emp_id);
                            $this->db->update('dgt_bank_statutory',$result);
                        }

                        /* Edit - Bank & Statutory Add/ Edit */
                    }
                    else
                    {
                        array_push($record_line_error,  $i); 
                    }
                    $i++;
                }
            } 
            if(!empty($record_line_error) && sizeof($record_line_error) > 0)
            {
                $implode_rows_num = implode(",", $record_line_error);
                $this->session->set_flashdata('tokbox_success', lang('csv_file_import_success').' '.lang('employee_no_exist').' : '. $implode_rows_num);
            }
            else{
                    $this->session->set_flashdata('tokbox_success', lang('csv_file_import_success'));
            }
            
            // unlink($filePath);

            redirect(base_url('employees/employee_bulk_import'));
        } 
        else 
        {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('tokbox_error', $error);
            redirect(base_url('employees/employee_bulk_import'));
        }
    }



    public function export_employee_data()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $branch_id = $_GET['branch_id'];
        $this->db->select('du.*, dad.*,dd.designation, dept.deptname, bank_details.*, dupd.bank_info as bank_details');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->join("dgt_designation as dd",'dd.id = du.designation_id',"left");
        $this->db->join("dgt_departments as dept",'dept.deptid = dd.department_id',"left");
        $this->db->join("dgt_bank_statutory as bank_details",'bank_details.user_id = dad.user_id',"left");
        $this->db->join("dgt_users_personal_details as dupd","dupd.user_id = dad.user_id","left");

        $this->db->where("dad.branch_id", $branch_id);
        $this->db->order_by("du.id","desc");
        $query = $this->db->get();
        $employees_data = $query->result_array();

        if(!empty($employees_data))
        {
            // $this->load->library('csv');
            $file_name = 'EmployeeExportData_'.date("dmyhis").'.csv';
            header("Content-type: text/csv");   
            header("Content-Disposition: attachment; filename=".$file_name);
            header("Pragma: no-cache");
            header("Expires: 0");
            $output = fopen("php://output", "w");
            
            // Write header row
            $columns = array(
                'Employee Number', 'First Name', 'Middle Name', 'Last Name', 'Gender',
                'Date of Joining', 'Designation', 'Work Email', 'Department', 'Worklocation Name',
                'Worklocation Address1', 'Worklocation Address2', 'Worklocation City',
                'Worklocation StateCode', 'Worklocation Country', 'Worklocation PostalCode',
                'Enable Portal', 'Employee Status', 'Personal Email', 'Father Name',
                'Mobile Number', 'Date of Birth', 'Personal AddressLine1', 'Personal AddressLine2',
                'Personal City', 'Personal StateCode', 'Personal PostalCode', 'Personal Country',
                'PAN Number', 'Payment Mode', 'Bank Holder Name', 'Bank Name', 'Account Number',
                'IFSC Code', 'Account Type', 'Last Working Day', 'CF.Cost Center', 'Emp Level', 'CF.Group D-O-J', 'Reporting Manager'
            );

            fputcsv($output, $columns);

            

            foreach ($employees_data as $row) {

                $temp_teamLeadId = $row['teamlead_id'];
                
                $reporting_data = array();
                $manager_name = '';

                if($temp_teamLeadId > 0)
                {
                    $this->db->select('du.*, dad.*');
                    $this->db->from("dgt_users as du");
                    $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
                    $this->db->where("du.id", $temp_teamLeadId);
                    $query2 = $this->db->get();
                    $reporting_data = $query2->row_array();
                    $manager_name .= $reporting_data["fullname"];
                    if($reporting_data["emp_code"] != "")
                    {
                        $manager_name .= ' ('.$reporting_data["emp_code"].')';
                    }
                }
                $tmp_output = array();
                $tmp_output[] = $row['emp_code'];
                $full_name = $row['fullname'];
                $exp_name = explode(" ", $full_name);
                $tmp_output[] = $exp_name[0]? $exp_name[0] :'';
                if($exp_name[2] != "")
                {
                    $tmp_output[] = $exp_name[1]? $exp_name[1] :'';
                    $tmp_output[] = $exp_name[2]? $exp_name[2] :'';
                }
                else{
                    $tmp_output[] = '';
                    $tmp_output[] = $exp_name[1]? $exp_name[1] :'';
                }
                
                $gender = ucwords($row['gender']);
                $tmp_output[] = $gender;

                $tmp_output[] = $row['doj'] ? date("d-m-Y", strtotime($row['doj'])): "";
                $tmp_output[] = $row['designation'];
                $tmp_output[] = $row['email'];
                $tmp_output[] = $row['deptname'];
                $tmp_output[] = $row['worklocationName'];
                $tmp_output[] = $row['worklocationAddress1'];
                $tmp_output[] = $row['worklocationAddress2'];
                $tmp_output[] = $row['worklocationCity'];
                $tmp_output[] = $row['worklocationStateCode'];
                $tmp_output[] = $row['worklocationCountry'];

                $tmp_output[] = $row['worklocationPostalCode'];
                if($row['status'] =='1')
                {
                    $tmp_output[] = "Yes";
                    $tmp_output[] = "Active";
                }
                else
                {
                    $tmp_output[] = "No";
                    $tmp_output[] = "Exited";
                }
                $tmp_output[] = $row['personal_email'];
                $tmp_output[] = $row['fatherName'];
                $tmp_output[] = $row['phone'];
                $tmp_output[] = $row['dob'] ? date("d-m-Y", strtotime($row['dob'])): "";
                $tmp_output[] = $row['address'];
                $tmp_output[] = '';
                $tmp_output[] = $row['city'];
                $tmp_output[] = $row['state'];
                $tmp_output[] = $row['pincode'];
                $tmp_output[] = $row['country'];

                $bank_info = json_decode($row['bank_details'], true);
                $tmp_output[] = $bank_info['pan_no'];
                $tmp_output[] = ucwords($bank_info['payment_type']);
                $tmp_output[] = $bank_info['bank_holder_name'];
                $tmp_output[] = $bank_info['bank_name'];
                // $tmp_output[] = (string)$bank_info['bank_ac_no'];
                  $tmp_output[] =   sprintf("%.0f", $bank_info['bank_ac_no']);
                $tmp_output[] = $bank_info['ifsc_code'];
                $tmp_output[] = ucwords($bank_info['account_type']);
                $exit_date = $row['exit_date'];
                if($row['exit_date'] != "" || $row['exit_date'] !="0000-00-00")
                {
                    $exit_date = $row['exit_date'];
                }
                else{
                    $exit_date = '';
                }

                $tmp_output[] = $exit_date;
                $tmp_output[] = $row['cost_center'] ? $row['cost_center'] : "";
                $tmp_output[] = $row['employee_level'] ? ucwords($row['employee_level']) : "";
                $tmp_output[] = $row['emp_gjd'] ? date("d-m-Y", strtotime($row['emp_gjd'])): "";
                $tmp_output[] = $manager_name;
                fputcsv($output, $tmp_output);
            }

            // Close the temporary file handle (file will be deleted)
            fclose($output); exit;
        }
    }

    public function update_emp_exit_date()
    {
        $emp_number = array();
        
        $filePath = './assets/uploads/terminal_emp.csv';

        header('Content-Type: text/html; charset=utf-8');

        $file = fopen($filePath, 'rb');
        if($file !== false) 
        {
            $i = 2;
            //$delimiter = "\t";
            fgetcsv($file);
            $record_line_error = array();
            while (($row = fgetcsv($file)) !== false)
            {
                $row = array_map('utf8_encode', $row);

                $this->db->select("dgt_users.id as user_id, dgt_bank_statutory.payment_type as payment_mode");
                $this->db->from("dgt_account_details");
                $this->db->join("dgt_users","dgt_users.id = dgt_account_details.user_id","inner");   
                $this->db->join("dgt_bank_statutory","dgt_bank_statutory.user_id = dgt_account_details.user_id","left");     
                $this->db->where("dgt_account_details.emp_code", trim($row[0]));
                $this->db->group_by("dgt_users.id");
                $query = $this->db->get();
                if(!$query)
                {
                    return false;
                }

                $emp_user_id = array();
                $emp_user_id = $query->result_array();

                if(!empty($emp_user_id))
                {
                    $emp_id = '';
                    foreach($emp_user_id as $emp_data)
                    {
                        $emp_id = $emp_data['user_id'];
                    }

                    $exit_date = '';
                    if($row[2] != "" && $row[2] != "")
                    {
                        $exit_date = date("Y-m-d", strtotime($row[2]));
                    }
                    $this->db->where("id", $emp_id);
                    $this->db->update("dgt_users", array("exit_date" => $exit_date));
                }
            }
        }
    }

    // Bulk Bank Info Update

    public function update_emp_bank_info()
    {
        $emp_number = array();
        
        $filePath = './assets/uploads/terminal_emp.csv';

        header('Content-Type: text/html; charset=utf-8');

        $file = fopen($filePath, 'rb');
        if($file !== false) 
        {
            $i = 2;
            //$delimiter = "\t";
            fgetcsv($file);
            $record_line_error = array();
            while (($row = fgetcsv($file)) !== false)
            {
                $row = array_map('utf8_encode', $row);

                $this->db->select("dgt_users.id as user_id, dgt_bank_statutory.payment_type as payment_mode");
                $this->db->from("dgt_account_details");
                $this->db->join("dgt_users","dgt_users.id = dgt_account_details.user_id","inner");   
                $this->db->join("dgt_bank_statutory","dgt_bank_statutory.user_id = dgt_account_details.user_id","left");     
                $this->db->where("dgt_account_details.emp_code", trim($row[0]));
                $this->db->group_by("dgt_users.id");
                $query = $this->db->get();
                if(!$query)
                {
                    return false;
                }

                $emp_user_id = array();
                $emp_user_id = $query->result_array();

                if(!empty($emp_user_id))
                {
                    $emp_id = '';
                    foreach($emp_user_id as $emp_data)
                    {
                        $emp_id = $emp_data['user_id'];
                    }

                    $exit_date = '';
                    if($row[1] != "" && $row[1] != "")
                    {
                        $bank_name = $row[1];
                    }
                    if($row[2] != "" && $row[2] != "")
                    {
                        $bank_account_num = $row[2];
                    }

                    if($row[3] != "" && $row[3] != "")
                    {
                        $bank_ifsc_code = $row[3];
                    }
                    if($row[4] != "" && $row[4] != "")
                    {
                        $bank_acocunt_holder_name = $row[4];
                    }

                    $this->db->where("id", $emp_id);
                    $this->db->update("dgt_users", array("exit_date" => $exit_date));
                }
            }
        }
    }

    public function zip_file_upload($postData, $files)
    {   
        $config['upload_path'] = './assets/zip_file_upload/';
        $config['allowed_types'] = 'zip|rar';
      
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('import_csv')) 
        {
            $filename = $this->upload->data();
            $zip_file_name = base_url("/assets/zip_file_upload/").$filename['file_name'];

            $zip = new ZipArchive;

            if ($zip) {
                $tmp_extract_zip_dir = './assets/uploads/user_document/';
                $extractToDirectory = '';
                if ($zip->open($config['upload_path'].$filename['file_name']) === TRUE) 
                {
                    $extractResult = $zip->extractTo($tmp_extract_zip_dir);
                    
                    $numFiles = $zip->numFiles;
                    
                    for ($i = 0; $i < $numFiles; $i++) 
                    {
                        $filename = $zip->getNameIndex($i);
                        $fileNameWithExtension = basename($filename);
                        $fileNameWithoutExtension = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                        $fileExtension = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                        $tmp_file_name = explode("_", $fileNameWithoutExtension);
                        
                        $employee_id = $this->employees->employeeIdByEmpCode($tmp_file_name[0]); // employee id as user id in database
                        
                        if($employee_id['user_id'])
                        {
                            $check_data_where = array();
                            $check_data_where = array(
                                "user_id"           =>  $employee_id['user_id'],
                                "document_name"     =>  $postData['document_type'],
                                "document"          =>  $filename,
                            );

                            $document_exist = $this->db->get_where("dgt_user_document", $check_data_where)->num_rows();
                            
                            $tmp_file_data = array();
                            $tmp_file_data = array(
                                "user_id"           =>  $employee_id['user_id'],
                                "document_name"     =>  $postData['document_type'],
                                "document"          =>  $filename,
                                "created_at"        =>  date("Y-m-d H:i:s")
                            );

                            if($document_exist > 0 )
                            {
                                $this->db->where($check_data_where);
                                $this->db->where("dgt_user_document", $tmp_file_data);
                            }
                            else{
                                $this->db->insert("dgt_user_document", $tmp_file_data);
                            }
                            
                            $tmp_file_data = $check_data_where = array();

                            $params = array();


                            $subject ='Confirmation: '.ucwords($postData['document_type']).' Uploaded Successfully';
                            $message = "Dear User,

                            We're delighted to confirm that your recent ". strtolower($postData['document_type'])."has been successfully uploaded to your profile. This ensures that your health coverage is current and accessible whenever needed.
                            
                            Our team has reviewed the document you provided, and it meets our requirements. Thank you for promptly completing this step.
                            
                            Should you need any further assistance or have questions regarding your coverage, don't hesitate to reach out. We're here to support you.
                            
                            Best regards,";
                         
                            $staff_email = '';
                            $staff_query = $this->db->query("SELECT email FROM dgt_users WHERE id = ?", array($employee_user_id));
                            $staff_row = $staff_query->row_array();
                            $staff_email = $staff_row['email'];
                            if($staff_email)
                            {
                                $params['recipient'] = $staff_email;

                                $params['subject'] = '[' .config_item('company_name') . ']' .' '.$subject;
                                $params['message'] = $message;
                                // $params['attached_file'] = '';
                               
                                   modules::run('fomailer/send_email', $params);
                                //    echo "<pre>";
                                //    print_R(error_get_last());
                                //    print_r($r); die;  
                            }
                        }

                    }

                    $zip->close();

                    $this->session->set_flashdata('tokbox_success', "File uploaded successfully.");
                    redirect(base_url().'employees');
                } 
                else 
                {
                    $this->session->set_flashdata('tokbox_danger', $zip->status);
                    redirect(base_url().'employees');
                }
            } 
            else 
            {
                $this->session->set_flashdata('tokbox_danger','Failed to create ZipArchive object.');
                redirect(base_url().'employees');
            }

        }
        else 
        {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('tokbox_danger',$error);
            redirect(base_url().'employees');
        }
    }
    public function testCiCd()
    {
        echo 'cicd execute succesfuly'; exit;   
    }

    public function callUpdateProfileAPI($user_id = '')
    {
        $employee_details = $this->employees->get_employeedetailById($user_id);
       
        $url = 'http://127.0.0.1:8000/api/v1/automhr_api/update_profile/';
        
        
        $employee_details['role'] = '';
        if (!empty($employee_details['role_id'])) {
            $employee_details['role'] = $employee_details['role_id'];
        }
        
        $employee_details['department_name'] = '';
        if (!empty($employee_details['department_id'])) {
            $employee_details['department_name'] = $employee_details['department_id'];
        }
        
        $employee_details['designations'] = '';
        if (!empty($employee_details['designation_id'])) {
            $employee_details['designations'] = $employee_details['designation_id'];
        }
        
        $tmp_response = modules::run("Auth/callCreateProfileAPI", $employee_details, $url, 'update');  

        return $tmp_response;
        // // Uncomment if you want to debug the employee details
        // echo "<pre>";
        // print_r($tmp_response); 
        // die;
    }

    
    function activate_stat($user_id,$stat)
    {
        $url = "http://127.0.0.1:8000/api/v1/automhr_api/active_state/";

        $this->load->model("Employees_details");
        $user = $this->Employees_details-> get_employeedetailById($user_id);
  
        $email = $user['email'];
  

    $state_data = array(
        'email' => $email,    
        'is_active' => $stat

    );


    $jsonData = json_encode($state_data);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ));

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_status === 204) {
        echo "Profile Active state updated successfully.";
    } else {
        echo "Failed to update profile . Error: " . curl_error($ch);
    }

    curl_close($ch);
    }


    function delete_profile($user_id = '') {

        $this->load->model("Employees_details");

        $user = $this->Employees_details->get_employeedetailById($user_id);
    
        $url = "http://127.0.0.1:8000/api/v1/automhr_api/delete_profile/";
    
        $email = $user['email'];
        $jsonData = json_encode(['email' => $email]);

    
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
    
        $response = curl_exec($ch);
    
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
    
        curl_close($ch);
    
        if (isset($error_msg)) {
            $this->session->set_flashdata('error', 'Error: ' . $error_msg);
        } elseif ($http_status === 204) {
            $this->session->set_flashdata('success', 'User profile deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user profile. HTTP Status Code: ' . $http_status);
        }
    
        redirect($_SERVER['HTTP_REFERER']);
    }
    

}




/* End of file employees.php */
