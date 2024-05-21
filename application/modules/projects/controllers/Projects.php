<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->module('layouts');
        $this->load->library(array('template','form_validation'));
        $this->load->model(array('Client','Project','App','Expense'));
        $this->template->title(lang('projects'));

        $archive = FALSE;
        if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }

        // App::module_access('menu_projects');
        $all_routes = $this->session->userdata('all_routes');
        foreach($all_routes as $key => $route){
            if($route == 'projects'){
                $routname = projects;
            } 
        }
        if(empty($routname)){
             $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }
        // echo "hi123"; exit;
        $this->filter_by = $this->_filter_by();

        $this->applib->set_locale();

    }


    function index()
    {
        $archive = FALSE;
        if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }
        $data = array(
            'page' => lang('projects'),
            'projects' => $this->_project_list($archive),
            'datatables' => TRUE,
            'archive' => $archive
        );
        $this->template
            ->set_layout('users')
            ->build('projects',isset($data) ? $data : NULL);
    }

    function ajax_project(){
        $archive = FALSE;
        $all_projects = $this->_project_list($archive);
        $projects = $this->_project_list($archive,'page');
        $data['recordsTotal']       =  $data['recordsFiltered']     =   count($all_projects);
        if(!empty($projects)){
            $i = 0;
            foreach($projects as $p){
                $view_url = "'".base_url().'projects/view/'.$p->project_id."'";
                $no_of_tasks = App::counter('tasks',array('project' => $p->project_id));
                $data['data'][$i]['title'] = '<a class="text-info" data-toggle="tooltip" data-original-title="'.$no_of_tasks.' '.lang('tasks').' '.$progress.'%'.lang('done').'" href="'.$view_url.'">'.$p->project_title.'</a>';

                if (User::is_admin()) {
					$data['data'][$i]['client_name'] = ($p->client > 0) ? Client::view_by_id($p->client)->company_name : 'N/A';
			    }
                if (User::login_role_name() != 'client') {
					switch ($p->status) {
						case 'Active': $label = 'success'; break;
						case 'On Hold': $label = 'warning'; break;
						case 'Done': $label = 'default'; break;
					}
					$data['data'][$i]['status'] = '<span class="label label-'.$label.'">'.lang(str_replace(" ","_",strtolower($p->status))).'</span>';
			    }
                $img_file = return_img(User::avatar_url($p->assign_lead));
                $data['data'][$i]['lead'] = '<ul class="team-members"><li><a><img src="'.$img_file.'" class="img-circle" data-toggle="tooltip" data-title="'.User::displayName($p->assign_lead).'" data-placement="top"></a></li></ul>';

                $data['data'][$i]['team'] = '<ul class="team-members">';
                $members = Project::project_team($p->project_id);
                if(!empty($members)){
				    foreach ($members as $user) {
                        $img_file = return_img(User::avatar_url($user->assigned_user));
                        $data['data'][$i]['team'] .= '<li><a><img src="'.$img_file.'" class="img-circle" data-toggle="tooltip" data-title="'.User::displayName($user->assigned_user).'" data-placement="top"></a></li>';
				     }
                }
				$data['data'][$i]['team'] .= '</ul>';
                $hours = Project::total_hours($p->project_id);
                if($p->estimate_hours > 0){
                    $used_budget = round(($hours / $p->estimate_hours) * 100,2);
                } else{ $used_budget = NULL; }
                $budget_class = ($used_budget > 100) ? 'text-danger' : 'text-success';
                $data['data'][$i]['budget'] = '<strong class="'.$budget_class.'">'.($used_budget != NULL) ? $used_budget.' %': 'N/A'.'</strong>';
                if (!User::is_admin()) {  
                    $check_task_hours = $this->db->get_where('timesheet',array('project_id'=>$p->project_id))->result_array();    
                    $hrs = 0;
                    foreach($check_task_hours as $h)
                    {
                        $hrs += $h['hours'];
                    }
                    $data['data'][$i]['amount'] = $hrs.'  '.lang('hours');
                }
                if(User::login_role_name() != 'staff' || User::perm_allowed(User::get_id(),'view_project_cost')){
					$cur = ($p->client > 0) ? Client::client_currency($p->client)->code : $p->currency;
                    $data['data'][$i]['amount'] = '<strong>'.Applib::format_currency($cur, Project::sub_total($p->project_id)).'</strong><small class="text-muted" data-toggle="tooltip" data-title="'.lang('expenses').'"> ('.Applib::format_currency($cur, Project::total_expense($p->project_id)).') </small>';
						
			    }
                $data['data'][$i]['chart'] = '<a href="'.base_url().'projects/project_chart/'.$p->project_id.'"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>';
               
                $data['data'][$i]['action'] = '<div class="dropdown"><a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a><ul class="dropdown-menu pull-right"><li><a href="'.base_url().'projects/view/'.$p->project_id.'">'.lang('preview_project').'</a></li>';
                if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){  
                    $data['data'][$i]['action'] .= '<li><a href="'.base_url().'projects/edit/'.$p->project_id.'">'.lang('edit_project').'</a></li>';
                    if ($archive){
                        $data['data'][$i]['action'] .= '<li><a href="'.base_url().'projects/archive/'.$p->project_id.'/0">'.lang('move_to_active').'</a></li> '; 
                    }else{
                        $data['data'][$i]['action'] .= '<li><a href="'.base_url().'projects/archive/'.$p->project_id.'/1">'.lang('archive_project').'</a></li>';        
                    }
                }
                if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_projects')){
                    $data['data'][$i]['action'] .= '<li><a href="'.base_url().'projects/delete/'.$p->project_id.'" data-toggle="ajaxModal">'.lang('delete_project').'</a></li>';
                }
                $data['data'][$i]['action'] .= '</ul></div>';
                $i++;
            }
        }
        $data['draw'] = isset ( $_GET['draw'] ) ?intval( $_GET['draw'] ) :0;
        echo json_encode($data);
        exit;
    }
    function view($project = NULL)
    {
        // $this->_check_owner($project);

        $data = array(
            'page'          => lang('projects'),
            'datatables'    => TRUE,
            'fuelux'        => TRUE,
            'editor'        => TRUE,
            'nouislider'    => TRUE,
            'project'       => $project,
            'todo_list'     => TRUE,
            'datepicker'    => TRUE,
            'form'          => TRUE,
            'task_checkbox' => TRUE
        );
        $this->load->helper('app');

        $data['group'] = $this->input->get('group', TRUE) ? $this->input->get('group', TRUE) : 'dashboard';


        if(isset($_GET['action']) ? $_GET['action'] : '' == 'edit'){
            $data['form'] = TRUE;
            $data['set_fixed_rate'] = TRUE;
        }

        if($data['group'] == 'calendar'){ $data['calendar'] = TRUE; }

        if($data['group'] == 'gantt'){
            $data['project'] = $project;
            $data['gantt'] = TRUE;
        }

        $this->template
            ->set_layout('users')
            ->build('view',isset($data) ? $data : NULL);
    }



    function add()
    {
        // echo User::login_role_name(); exit;
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
        $user_details = $this->db->get_where('users',array('id'=>$user_id,'role_id'=>3,'is_teamlead'=>'yes'))->row_array();
        if(empty($user_details)){
            if(!User::can_add_project()) App::access_denied('projects');
        }

        if ($this->input->post()) {
            // check form validation
            $this->form_validation->set_rules('project_code', 'Project Code', 'required');
            $this->form_validation->set_rules('project_title', 'Project Title', 'required');
            // $this->form_validation->set_rules('branch_id', 'Entity', 'required');

            if ($this->form_validation->run() == FALSE) // Validation ok
            {
                Applib::make_flashdata(array(
                    'response_status' => 'error',
                    'message' => lang('operation_failed'),
                    'form_error'=> validation_errors()
                ));
                redirect('projects/add');
            }else{

                // echo "<pre>"; print_r($_POST); exit;
                if ($this->input->post('fixed_rate') == 'on') { $fixed_rate = 'Yes'; } else { $fixed_rate = 'No'; }

                unset($_POST['fixed_rate']);

                if (User::login_role_name() != 'client') { // If added by client, just assign admin
                    if(User::login_role_name() == 'staff'){
                        if(!empty($user_details)){
                            $_POST['assign_to'] = serialize($this->input->post('assign_to'));
                        }
                        $_POST['assign_to'] = 'a:1:{i:0;s:1:"'.User::get_id().'";}';
                    }else{
                        $_POST['assign_to'] = serialize($this->input->post('assign_to'));
                    }

                }else{
                    $_POST['assign_to'] = 'a:1:{i:0;s:1:"1";}';
                    $_POST['progress'] = 0;
                }

                if(User::login_role_name() == 'client'){
                    $company_id = User::profile_info(User::get_id())->company;
                    if($company_id > 0){
                        $_POST['client'] = $company_id;
                    }else{
                        $this->session->set_flashdata('tokbox_error', lang('company_not_set'));
                        redirect('projects');
                    }
                }
                if(valid_date($_POST['start_date']))
                $_POST['start_date'] = Applib::date_formatter($_POST['start_date']);
                if(valid_date($_POST['due_date']))
                $_POST['due_date'] = Applib::date_formatter($_POST['due_date']);

                if (isset($_POST['files'])) { unset($_POST['files']); }
                if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                    // $_POST['branch_id'] = $this->session->userdata('branch_id');
                }

                $project_id = Project::save($this->input->post());

                // Inherit currency and language settings

                if ($_POST['client'] > 0) {
                    $client_cur = Client::client_currency($_POST['client']);
                    $client_lang = Client::client_language($_POST['client']);
                } else {
                    $client_cur = App::currencies(config_item('default_currency'));
                    $client_lang = App::languages(config_item('default_language'));
                }
                $data = array(
                    'currency' => $client_cur->code,
                    'language' => $client_lang->name,
                    'project_id' => $project_id
                );
                Project::update($project_id,$data);


                // Store assignments in assign_projects table

                $assign = unserialize($_POST['assign_to']);

                // echo $this->input->post('assign_lead'); exit;

                Project::delete_team($project_id);

                foreach ($assign as $key => $value) {
                    $args = array(
                        'assigned_user' => $value,
                        'project_assigned' => $project_id
                    );
                    App::save_data('assign_projects',$args);
                }

                // Set Fixed Rate
                $data = array('fixed_rate' => $fixed_rate);
                Project::update($project_id,$data);

                // default project settings
                $default_settings = json_decode(config_item('default_project_settings'),TRUE);
                foreach ($default_settings as $key => &$value) {
                    if (strtolower($value) == 'off') {
                        unset($default_settings[$key]);
                    }
                }
                $default_settings = json_encode($default_settings);

                $data = array('settings' => $default_settings);
                Project::update($project_id,$data);
                // echo $project_id; exit;

                // echo config_item('notify_project_assignments'); exit;


                $this->lead_notification($project_id);
                // Send email to the assigned users
                if(config_item('notify_project_assignments') == 'TRUE'){
                    $this->assigned_notification($project_id);
                }

                // Send email to client
                if(config_item('notify_project_opened') == 'TRUE'){
                    $this->client_notification($project_id);
                }

                // Post to slack channel
                if(config_item('slack_notification') == 'TRUE'){
                    $this->load->helper('slack');
                    $slack = new Slack_Post;
                    $slack->slack_create_project($project_id,User::get_id());
                }

                $data = array(
                    'module' => 'projects',
                    'module_field_id' => $project_id,
                    'user' => User::get_id(),
                    'activity' => 'activity_added_new_project',
                    'icon' => 'fa-coffee',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $_POST['project_title'],
                    'value2' => ''
                );
                App::Log($data);

                // Applib::go_to('projects/view/'.$project_id.'?group=dashboard','success',lang('project_added_successfully'));
                $this->session->set_flashdata('tokbox_success', lang('project_added_successfully'));
                redirect('projects/view/'.$project_id.'?group=dashboard');
            }


        }else{
            $data = array(
                'page' => lang('projects'),
                'form' => TRUE,
                'datepicker' => TRUE,
                'nouislider' => TRUE,
                'editor'    => TRUE,
                'set_fixed_rate' => TRUE,
                'projects' => $this->_project_list()
            );

            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                $branches = $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
             }
             else{
                 $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
             }
			 
			 $data['companies'] = $this->db->select('*')->from('companies')->get()->result_array();
			 $data['users'] = $this->db->select('*')->from('account_details')->get()->result_array();
            $this->template
                ->set_layout('users')
                ->build('create_project',isset($data) ? $data : NULL);
        }
    }

    function getEmployees(){
		extract($_GET);

		$this->db->select('U.id,AD.fullname,U.email')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1);
		$this->db->where("(AD.fullname like '%".$term['term']."%' OR U.email like '%".$term['term']."%' )", NULL, FALSE);

		$users = $this->db->order_by('AD.fullname','asc')->get()->result_array();
		
		echo json_encode($users);
		exit;

	 }

    function get_companies($branch_id){
        $companies = $this->db->select('*')->from('companies')->where('branch_id',$branch_id)->get()->result_array();
        $res_vals = array();
        $res_vals['companies']  = '<option value="">Select</option>';
        if(!empty($companies)){
            foreach($companies as $company1){
                $res_vals['companies'] .= '<option value="'.$company1['co_id'].'">'.$company1['company_name'].'</option>'; 
            }
        }
        $user_details = $this->db->select('*')->from('account_details')->where('branch_id',$branch_id)->get()->result_array();
        $res_vals['users']  = '';
        if(!empty($user_details)){
            foreach($user_details as $user_detail1){
                $res_vals['users'] .= '<option value="'.$user_detail1['user_id'].'">'.$user_detail1['fullname'].'</option>'; 
            }
        }
        echo json_encode($res_vals); exit;
    }



    function edit($project_id = NULL)
    {


        if ($this->input->post()) {
            $project_id = $this->input->post('project_id');

            // if(!User::can_edit_project(Project::by_id($project_id)->client,$project_id)) App::access_denied('projects');
            // check form validation
            $this->form_validation->set_rules('project_code', 'Project Code', 'required');
            $this->form_validation->set_rules('project_title', 'Project Title', 'required');
            $this->form_validation->set_rules('client', 'Client', 'required');
            $this->form_validation->set_rules('assign_to[]', 'Assign To', 'required');
            // $this->form_validation->set_rules('branch_id', 'Entity', 'required');
            if ($this->form_validation->run() == FALSE)
            {
                var_dump(validation_errors());
                die();
                Applib::make_flashdata(array(
                    'response_status' => 'error',
                    'message' => lang('error_in_form'),
                    'form_error'=> validation_errors()
                ));
                redirect('projects/view/'.$project_id.'/?group=dashboard&action=edit');
            }else{
                $notify = TRUE; // Send email only when team changed
                $current_team = Project::by_id($project_id)->assign_to;
                if(serialize($this->input->post('assign_to')) == $current_team) $notify = FALSE;


                $fixed_rate = ($this->input->post('fixed_rate') == 'on') ? 'Yes' : 'No';
                unset($_POST['fixed_rate']);

                $new_team = $this->input->post('assign_to');

                Project::delete_team($project_id);

                foreach ($new_team as $key => $value) {
                    $data = array(
                        'assigned_user' => $value,
                        'project_assigned' => $project_id
                    );
                    App::save_data('assign_projects',$data);
                }

                if ($_POST['client'] > 0) {
                    $client_cur = Client::client_currency($_POST['client']);
                    $client_lang = Client::client_language($_POST['client']);
                } else {
                    $client_cur = App::currencies(config_item('default_currency'));
                    $client_lang = App::languages(config_item('default_language'));
                }


                $_POST['assign_to'] = serialize($new_team);

                if(valid_date($_POST['start_date']))
                $_POST['start_date'] = Applib::date_formatter($_POST['start_date']);

                if(valid_date($_POST['due_date']))
                $_POST['due_date'] = Applib::date_formatter($_POST['due_date']);

                if (isset($_POST['files'])) unset($_POST['files']);

                if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                    // $_POST['branch_id'] = $this->session->userdata('branch_id');
                }

                Project::update($project_id,$this->input->post());

                $data = array(
                    'currency' => $client_cur->code,
                    'language' => $client_lang->name,
                    'project_id' => $project_id
                );

                if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                    // $data['branch_id'] = $this->session->userdata('branch_id');
                }
                else{
                    // $data['branch_id'] = $this->input->post('branch_id');
                }
                
                Project::update($project_id,$data);


                // Set Fixed Rate
                $data = array('fixed_rate' => $fixed_rate);
                Project::update($project_id,$data);

                // Send email to the assigned users
                if(config_item('notify_project_assignments') == 'TRUE' && $notify){
                    $this->_notify_project_update($project_id);
                }
                // Log activity
                $data = array(
                    'module' => 'projects',
                    'module_field_id' => $project_id,
                    'user' => User::get_id(),
                    'activity' => 'activity_edited_a_project',
                    'icon' => 'fa-coffee',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $_POST['project_title'],
                    'value2' => ''
                );
                App::Log($data);

                if ($this->input->post('progress') == '100') {
                    $this->_project_complete($project_id);
                }

                // Applib::go_to('projects/view/'.$project_id.'?group=dashboard&action=edit','success',lang('project_edited_successfully'));
                $this->session->set_flashdata('tokbox_success', lang('project_edited_successfully'));
                redirect('projects/view/'.$project_id);
            }
        }else{
            $data = array(
                'page' => lang('projects'),
                'form' => TRUE,
                'datepicker' => TRUE,
                'nouislider' => TRUE,
                'editor'    => TRUE,
                'set_fixed_rate' => TRUE,
                'projects' => $this->_project_list()
            );
            $data['project_id'] = $project_id;
            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                 $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
            }
            else{
                $branches = $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
            }
             $branches = array_column($branches,'branch_id');
            $data['users'] = $this->db->select('*')->from('account_details')->where_in('branch_id',$branches)->get()->result_array();

            $data['companies'] = $this->db->select('*')->from('companies')->where_in('branch_id',$branches)->get()->result_array();
            
            $this->template
                ->set_layout('users')
                ->build('edit_projects',isset($data) ? $data : NULL);
        }
    }



    function todo($action = NULL){

        $action = $this->uri->segment(3);

        if($action == 'add'){

            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('todo_item', 'Item', 'required');

                if ($this->form_validation->run() == FALSE){
                    $this->session->set_flashdata('response_status', 'error');
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $project = $this->input->post('project',TRUE);
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $data = array('list_name'   => $this->input->post('todo_item'),
                        'saved_by'  => User::get_id(),
                        'module'    => 'projects',
                        'visible'       => $visible,
                        'project'       => $project
                    );
                    App::save_data('todo',$data);
                    // Applib::go_to('projects/view/'.$project,'success',lang('operation_successful'));
                    $this->session->set_flashdata('tokbox_success', lang('operation_successful'));
                    redirect('projects/view/'.$project);
                }

            }else{
                $data['project_id'] = $this->uri->segment(4);
                $data['action'] = 'add_todo';
                $this->load->view('modal/project_action',$data);
            }

        }

        if ($action == 'edit') {
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('todo_item', 'Item', 'required');

                if ($this->form_validation->run() == FALSE){
                    $this->session->set_flashdata('response_status', 'error');
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $id = $this->input->post('id',TRUE);
                    $project = $this->input->post('project',TRUE);
                    if(!isset($_POST['visible'])) $visible = 'No';
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $data = array('list_name'   => $this->input->post('todo_item'),
                        'saved_by'  => User::get_id(),
                        'module'    => 'projects',
                        'visible'       => $visible
                    );
                    App::update('todo',array('id'=>$id),$data);
                    // Applib::go_to('projects/view/'.$project,'success',lang('operation_successful'));
                    $this->session->set_flashdata('tokbox_success', lang('operation_successful'));
                    redirect('projects/view/'.$project);
                }

            }else{
                $data['id'] = $this->uri->segment(4);
                $data['action'] = 'edit_todo';
                $this->load->view('modal/project_action',$data);
            }
        }

        if($action == 'delete'){
            if($_POST){
                $id = $this->input->post('id');
                App::delete('todo',array('id'=>$id));
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('operation_successful'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                $data['id'] = $this->uri->segment(4);
                $data['action'] = 'delete_todo';
                $this->load->view('modal/project_action',$data);
            }
        }

        if($action == 'status'){
            if($_POST){
                $id = $this->input->post('id');
                $i = $this->db->where('id',$id)->get('todo')->row();
                if($i->saved_by != User::get_id()){
                    $data['success'] = false;
                    $data['message'] = 'Failed!';
                    $this->session->set_flashdata('response_status', 'error');
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    echo json_encode($data);
                    exit;
                }

                $todo_complete = $this->input->post('todo_complete');
                $progress = ($todo_complete == 'true') ? 'done' : 'pending';
                $args = array('status' => $progress);
                App::update('todo',array('id'=>$id),$args);

                $data['success'] = true;
                $data['message'] = 'Success!';
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('operation_successful'));
                echo json_encode($data);
                exit;
            }
        }


    }


    function delete($id = NULL)
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('project_id', 'Project ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', lang('delete_failed'));
                redirect('projects');
            }else{

                $project = $this->input->post('project_id',TRUE);
                if(User::is_admin() || User::perm_allowed(User::get_id(),'delete_projects')){
                    Project::delete($project);
                    // Applib::go_to('projects','success',lang('project_deleted_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('project_deleted_successfully'));
                    redirect('projects');
                }

            }
        }else{
            $data['project_id'] = $id;
            $data['action'] = 'delete_project';
            $this->load->view('modal/project_action',$data);
        }
    }



    function gantt($project = NULL)
    {
        $data = array(
            'page' => lang('projects'),
            'project' => $project,
            'gantt' => TRUE,
        );
        $this->template
            ->set_layout('users')
            ->build('gantt',isset($data) ? $data : NULL);
    }




    function archive($id)
    {
        $archived = $this->uri->segment(4);
        $data = array("archived" => $archived);
        Project::update($id,$data);

        // Post to slack channel
        if(config_item('slack_notification') == 'TRUE'){
            $project_title = Project::by_id($id)->project_title;
            Applib::slack(
                sprintf(lang('project_archived_msg'), $project_title,date('d-M-Y H:i:s')),
                sprintf(lang('project_archived'), $project_title),
                base_url().'projects/view/'.$id,
                sprintf(lang('project_archived_by'), User::displayName(User::get_id()))
            );
        }

        // Log activity
        $data = array(
            'module'            => 'projects',
            'module_field_id'   => $id,
            'user'              => User::get_id(),
            'activity'          => 'activity_edited_a_project',
            'icon'              => 'fa-coffee',
            'branch_id' => $this->session->userdata('branch_id'),
            'value1'            => $project_title,
            'value2'            => ''
        );
        App::Log($data);
        if($archived == 0){
            // Applib::go_to('projects','success',lang('project_active_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('project_active_successfully'));
                    redirect('projects');
        }
        if($archived == 1){
            // Applib::go_to('projects','success',lang('project_archive_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('project_archive_successfully'));
                    redirect('projects');
        }
    }

    function copy_project($project = NULL)
    {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);

            $this->form_validation->set_rules('project', 'Project', 'required');
            if ($this->form_validation->run() == FALSE)
            {
                // Applib::go_to('projects/view/'.$project.'?group=dashboard','error',lang('project_copy_failed'));
                $this->session->set_flashdata('tokbox_error', lang('project_copy_failed'));
                    redirect('projects/view/'.$project.'?group=dashboard');
            }else{

                $inf = Project::by_id($project);

                $new_code = filter_var($inf->project_code,FILTER_SANITIZE_NUMBER_INT)+1 ;

                $new_project = array(
                    'project_code'  => config_item('project_prefix').$new_code,
                    'project_title' => $inf->project_title.' clone',
                    'description'   => $inf->description,
                    'client'        => $inf->client,
                    'currency'      => $inf->currency,
                    'start_date'    => $inf->start_date,
                    'due_date'      => $inf->due_date,
                    'fixed_rate'    => $inf->fixed_rate,
                    'hourly_rate'   => $inf->hourly_rate,
                    'fixed_price'   => $inf->fixed_price,
                    'progress'      => $inf->progress,
                    'notes'         => $inf->notes,
                    'assign_to'     => $inf->assign_to,
                    'status'        => $inf->status,
                    'settings'      => $inf->settings,
                    'estimate_hours'=> $inf->estimate_hours,
                    'language'      => $inf->language,
                    'archived'      => 0,
                    'date_created'  => $inf->date_created
                );

                $new_project_id = Project::save($new_project);

                $team = unserialize($inf->assign_to);
                foreach ($team as $key => $user) {
                    $args = array(
                        'assigned_user' => $user,
                        'project_assigned' => $new_project_id
                    );
                    App::save_data('assign_projects',$args);
                }

                foreach (Project::has_milestones($project) as $key => $milestone) {
                    $params = array(
                        'milestone_name'    => $milestone->milestone_name,
                        'description'       => $milestone->description,
                        'project'           => $new_project_id,
                        'start_date'        => $milestone->start_date,
                        'due_date'          => $milestone->due_date
                    );
                    App::save_data('milestones',$params);
                }

                foreach (Project::has_tasks($project) as $key => $task) {
                    $args = array(
                        'task_name'         => $task->task_name,
                        'project'           => $new_project_id,
                        'milestone'         => $task->milestone,
                        'assigned_to'       => $task->assigned_to,
                        'description'       => $task->description,
                        'visible'           => $task->visible,
                        'task_progress'     => $task->task_progress,
                        'timer_status'      => $task->timer_status,
                        'timer_started_by'  => $task->timer_started_by,
                        'start_time'        => $task->start_time,
                        'estimated_hours'   => $task->estimated_hours,
                        'logged_time'       => $task->logged_time,
                        'due_date'          => $task->due_date,
                        'added_by'          => $task->added_by
                    );
                    $task_id = App::save_data('tasks',$args);

                    $team = unserialize($task->assigned_to);

                    foreach ($team as $key => $user) {
                        $args = array(
                            'assigned_user'     => $user,
                            'project_assigned'  => $new_project_id,
                            'task_assigned'     => $task_id
                            );
                        App::save_data('assign_tasks',$args);
                    }
                }
                foreach (Project::has_links($project) as $key => $link) {
                    $args = array(
                                'project_id'    => $new_project_id,
                                'client'        => $inf->client,
                                'icon'          => $link->icon,
                                'link_title'    => $link->link_title,
                                'link_url'      => $link->link_url,
                                'description'   => $link->description,
                                'username'      => $link->username,
                                'password'      => $link->password,
                                );
                    App::save_data('links',$args);
                }

                // Log activity
                $data = array(
                    'module' => 'projects',
                    'module_field_id' => $new_project_id,
                    'user' => User::get_id(),
                    'activity' => 'activity_copied_project',
                    'icon' => 'fa-copy',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $inf->project_title,
                    'value2' => ''
                );
                App::Log($data);

                // Applib::go_to('projects/view/'.$new_project_id.'?group=dashboard','success',lang('project_copied'));
                $this->session->set_flashdata('tokbox_success', lang('project_copied'));
                redirect('projects/view/'.$new_project_id.'?group=dashboard');
            }
        }else{
            $data['project'] = $project;
            $data['action'] = 'clone_project';
            $this->load->view('modal/project_action',isset($data) ? $data : NULL);
        }
    }

    function settings(){

        if ($_POST) {
            $project_id = $this->input->post('project_id');

            $settings = json_encode($_POST);
            $data = array('settings' => $settings);

            Project::update($project_id,$data);

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('settings_updated_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('settings_updated_successfully'));
            redirect(base_url().'projects/view/'.$project_id.'?group=settings');
        }else{
            $this->index();
        }

    }

    function team($project = NULL)
    {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);

            $this->form_validation->set_rules('project', 'Project', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                // Applib::go_to('projects/view/'.$project.'?group=teams','error',lang('error_in_form'));
                $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
                redirect('projects/view/'.$project);
            }else{
                $assigned = serialize($this->input->post('assigned_to'));

                Project::delete_team($project);

                $assign = $this->input->post('assigned_to');

                foreach ($assign as $key => $value) {
                    $data = array('assigned_user' => $value,'project_assigned'=>$project);
                    App::save_data('assign_projects',$data);
                }

                Project::update($project,array('assign_to' => $assigned));

                // Send email to assigned members
                if(config_item('notify_project_assignments') == 'TRUE') $this->assigned_notification($project);

                // Post to slack channel
                if(config_item('slack_notification') == 'TRUE'){
                    $project_title = Project::by_id($project)->project_title;
                    Applib::slack(
                        sprintf(lang('team_updated_msg'), $project_title),
                        sprintf(lang('team_updated'), $project_title),
                        base_url().'projects/view/'.$project,
                        sprintf(lang('team_updated_by'), User::displayName(User::get_id()))
                    );
                }

                // Log activity
                $data = array(
                    'module' => 'projects',
                    'module_field_id' => $project,
                    'user' => User::get_id(),
                    'activity' => 'activity_edited_team',
                    'icon' => 'fa-group',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => Project::by_id($project)->project_title,
                    'value2' => ''
                );
                App::Log($data);
                // Applib::go_to('projects/view/'.$project.'/?group=teams','success',lang('project_team_updated'));
                $this->session->set_flashdata('tokbox_success', lang('project_team_updated'));
                redirect('projects/view/'.$project);

            }
        }else{
            $data['project'] = $project;
            $data['action'] = 'edit_team';
            $this->load->view('modal/project_action',isset($data) ? $data : NULL);
        }
    }

    function invoice($project = NULL)
    {
        if ($this->input->post()) {

            $this->load->model('Invoice');

            $project = $this->input->post('project', TRUE);

            $this->form_validation->set_rules('project', 'Project', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                // Applib::go_to('projects/view/'.$project.'?group=dashboard','error',lang('invoice_not_created'));
                $this->session->set_flashdata('tokbox_error', lang('invoice_not_created'));
                redirect('projects/view/'.$project.'?group=dashboard');
            }else{

                $this->load->helper('string');
                $reference_no = config_item('invoice_prefix').random_string('nozero', 6);

                if(config_item('increment_invoice_number') == 'TRUE') $reference_no = config_item('invoice_prefix').Invoice::generate_invoice_number();

                $info = Project::by_id($project);
                $project_rate = ($info->fixed_rate == 'Yes') ? $info->fixed_price : $info->hourly_rate;
                $due_date = strftime(config_item('date_format'), strtotime("+".config_item('invoices_due_after')." days"));

                $data = array(
                    'reference_no'  => $reference_no,
                    'tax'           => 0,
                    'client'        => $info->client,
                    'currency'      => $info->currency,
                    'due_date'      => $due_date,
                    'notes'         => config_item('default_terms')
                );
                $invoice_id = Invoice::save($data);

                $activity = array(
                    'user'              => User::get_id(),
                    'module'            => 'invoices',
                    'module_field_id'   => $invoice_id,
                    'activity'          => 'activity_invoice_created',
                    'icon'              => 'fa-plus',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1'            => $reference_no
                );
                App::Log($activity); // Log activity

                $task_list = array();
                foreach (Project::has_tasks($project) as $task) {
                    $task_list[] = $task->task_name.' - '.Applib::sec_to_hours(Project::task_timer($task->t_id));
                }

                $items = array(
                    'invoice_id' => $invoice_id,
                    'item_name'  => $info->project_title,
                    'item_desc' => lang('time_spent').' : '.Project::total_hours($project).' '.lang('hours').PHP_EOL.implode(PHP_EOL, $task_list),
                    'unit_cost' => $project_rate,
                    'quantity'  => Applib::format_deci(Project::total_hours($project)),
                    'total_cost' => Applib::format_deci(Project::sub_total($project))
                );
                Invoice::save_items($items);

                if($this->input->post('unbill_invoiced_time', TRUE) == 'on'){
                        $this->db->where('project',$project)->update('project_timer',array('billable' => 0));
                        $this->db->where('pro_id',$project)->update('tasks_timer',array('billable' => 0));
                    }

                // Add selected expenses

                if(isset($_POST['expense'])){

                    foreach ($_POST['expense'] as $key => $ar) {
                        $e = Expense::view_by_id($key);
                        $cat = App::get_category_by_id($e->category);

                        $project_title = Project::by_id($e->project)->project_title;
                        $items = array(
                            'item_name' => lang('expenses').' [ '.$e->expense_date.' ]',
                            'item_desc' => '['.$project_title.']'.PHP_EOL.'&raquo; '.$cat.PHP_EOL.'&raquo; '.$e->notes,
                            'unit_cost' => $e->amount,
                            'invoice_id'    => $invoice_id,
                            'total_cost'    => $e->amount,
                            'quantity'      => '1.00'

                        );
                        Invoice::save_items($items);
                        Expense::update($e->id,array('invoiced' => '1','invoiced_id' => $invoice_id));
                    }
                }



                // Post to slack channel
                if(config_item('slack_notification') == 'TRUE'){
                    Applib::slack(
                        sprintf(lang('project_invoiced_msg'), $info->project_title, $reference_no),
                        sprintf(lang('project_invoiced'), $info->project_title),
                        base_url().'projects/view/'.$project,
                        sprintf(lang('project_invoiced_by'), User::displayName(User::get_id()))
                    );
                }

                // Log activity
                $data = array(
                    'module' => 'projects',
                    'module_field_id' => $project,
                    'user' => User::get_id(),
                    'activity' => 'invoiced_project',
                    'icon' => 'fa-money',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $info->project_title,
                    'value2' => ''
                );
                App::Log($data);
                // Applib::go_to('invoices/view/'.$invoice_id,'success',lang('invoice_created_successfully'));
                $this->session->set_flashdata('tokbox_success', lang('invoice_created_successfully'));
                redirect('invoices/view/'.$invoice_id);
            }
        }else{
            $data['project'] = $project;
            $data['action'] = 'invoice_project';
            $this->load->view('modal/project_action',isset($data) ? $data : NULL);
        }
    }



    function comment()
    {
        if ($this->input->post()) {

             $id = $this->input->post('project');

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
            $this->form_validation->set_rules('comment', 'Comment', 'required');
        if ($this->form_validation->run() == FALSE)
        {

            
            // Applib::go_to('projects/view/'.$id.'/?group=comments','error',lang('error_in_form'));
            $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
            redirect('projects/view/'.$id.'/?group=comments');
            // Applib::make_flashdata(array(
            //  'response_status' => 'error',
            //  'message' => lang('message_not_sent'),
            //  'form_error' => validation_errors()
            //  ));
            // redirect($_SERVER['HTTP_REFERER']);
        }
       else{
           $id = $this->input->post('project');
            $comment = $this->input->post('comment');
            $data = array('project'=>$id,'posted_by'=>User::get_id(),'message'=>$comment);
            $comment_id = App::save_data('comments',$data);

            // Log activity
            $data = array(
                'module' => 'projects',
                'module_field_id' => $id,
                'user' => User::get_id(),
                'activity' => 'activity_project_comment_added',
                'icon' => 'fa-comment',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => Project::by_id($id)->project_title,
                'value2' => ''
            );
            App::Log($data);

            if (config_item('notify_project_comments') == 'TRUE') {
                $this->_comment_notification($id,$comment);
                //send notification to the administrator
            }

            // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $params = array('project'       => $id,
                    'comment'       => $comment_id,
                    'user'          => User::get_id()
                );
                $slack->slack_project_comment($params);
            }

            // Applib::go_to('projects/view/'.$id.'/?group=comments','success',lang('comment_successful'));
            $this->session->set_flashdata('tokbox_success', lang('comment_successful'));
            redirect('projects/view/'.$id.'/?group=comments');
        }
        }else{
            redirect('projects');
        }
    }



    function replies()
    {
        if ($this->input->post()) {
            $project_id = $this->input->post('project',TRUE);

            $this->load->library('form_validation');
            $this->form_validation->set_rules('message', 'Message', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', lang('comment_failed'));
                redirect('projects/view/'.$project_id.'?group=comments');
            }else{
                $message = $this->input->post('message');
                $comment_id = $this->input->post('comment', TRUE);
                $data = array('parent_comment' => $comment_id,'reply_msg' => $message,'replied_by' => User::get_id());
                $reply_id = App::save_data('comment_replies',$data);

                if (config_item('notify_project_comments') == 'TRUE') {
                    //send notifications
                    $this->_comment_notification($project_id,$message);
                }
                // Post to slack channel
                if(config_item('slack_notification') == 'TRUE'){
                    $this->load->helper('slack');
                    $slack = new Slack_Post;
                    $params = array('project'   => $project_id,
                        'reply'     => $reply_id,
                        'user'      => User::get_id()
                    );
                    $slack->slack_project_comment_reply($params);
                }

                // Applib::go_to('projects/view/'.$project_id.'/?group=comments','success',lang('comment_replied_successful'));
                $this->session->set_flashdata('tokbox_success', lang('comment_replied_successful'));
                redirect('projects/view/'.$id.'/?group=comments');

            }

        }else{
            $data['comment'] = $this->input->get('c', TRUE);
            $data['project'] = $this->input->get('p', TRUE);
            $data['action'] = 'comment_reply';
            $this->load->view('modal/project_action',isset($data) ? $data : NULL);
        }
    }

    function delete_comment($id = NULL){

        if($this->input->post()){
            $id = $this->input->post('id',TRUE);
            $info = Project::view_comment($id);

            if(User::get_id() == $info->posted_by || User::is_admin()){
                App::delete('comments',array('comment_id'=>$id));

                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('comment_deleted'));
            }
            redirect('projects/view/'.$info->project.'?group=comments');
        }else{
            $data['info'] = Project::view_comment($id);
            $data['action'] = 'delete_comment';
            $this->load->view('modal/project_action',isset($data) ? $data : NULL);
        }
    }

    function delete_reply($id = NULL){
        if($this->input->post()){
            $id = $this->input->post('reply_id');
            $replied_by = Project::view_reply($id)->replied_by;
            if(User::get_id() == $replied_by || User::is_admin()){
                App::delete('comment_replies',array('reply_id'=>$id));
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('comment_deleted'));
            }
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['info'] = Project::view_reply($id);
            $data['action'] = 'delete_reply';
            $this->load->view('modal/project_action',isset($data) ? $data : NULL);
        }
    }

    function download($project = NULL)
    {
        if (isset($_GET['id'])) {
            $file_id = $this->input->get('id', TRUE);
            $info = Project::view_file($file_id);
            $this->load->helper('download');

            if($info->file_name == ''){
                // Applib::go_to($_SERVER["HTTP_REFERER"],'error',lang('operation_failed'));
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            if(file_exists('./assets/project-files/'.$info->path.$info->file_name)){
                $data = file_get_contents('./assets/project-files/'.$info->path.$info->file_name);
                // Read the file's contents
                force_download($info->file_name, $data);
            }else{
                // Applib::go_to($_SERVER["HTTP_REFERER"],'error',lang('operation_failed'));
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

    }

    function tracking()
    {
        $action = ucfirst($this->uri->segment(3));
        $status = ($action == 'On') ? 1 : 0;
        $project = $this->uri->segment(4);
        $info = Project::by_id($project);
        $timer_msg = '';
        $response = 'success';
        if ($action == 'Off') {

            $project_logged_time =  Project::total_hours($project);
            $timer = $this->db->where(array('user'=>User::get_id(),'status'=>'1','project'=>$project))->get('project_timer')->row();
            $time_logged = (time() - $timer->start_time) + $project_logged_time;

            $data = array('project'=>$project,'status'=>$status,'user'=>User::get_id(),'end_time'=>time());
            $this->db->where(array('user'=>User::get_id(),'status'=>'1','project'=>$project,'time_in_sec'=> 0,'end_time'=>NULL))->update('project_timer',$data);

            $data = array('time_logged'=>Applib::format_deci($time_logged),'timer_start'=>'');
            Project::update($project,$data);

            $timer_msg = 'timer_stopped_success';

        }else{

            $any_task_started = $this->db->where(array('pro_id'=>$project,'status'=>'1','user'=>User::get_id()))->get('tasks_timer')->num_rows();

            if($any_task_started == 0){

            $data = array('project'=>$project,'status'=>$status,'user'=>User::get_id(),'start_time'=>time());
            $this->db->insert('project_timer',$data); // Save to Daatabase
            $timer_msg = 'timer_started_success';

            }else{
                $response = 'error';
                $timer_msg = 'timer_already_started';
            }


        }
        Applib::go_to($_SERVER["HTTP_REFERER"],$response,lang($timer_msg));
        if($response == 'error')
        {
            $this->session->set_flashdata('tokbox_error', lang($timer_msg));
        }else{
            $this->session->set_flashdata('tokbox_success', lang($timer_msg));
        }
                redirect($_SERVER["HTTP_REFERER"]);
    }

    function mark_as_complete($id = NULL){

        if ($this->input->post()) {
            if(!User::is_admin()) App::access_denied('projects');
            $id = $this->input->post('id');
            $data = array('progress' => 100,'auto_progress' => 'FALSE');
            Project::update($id,$data);

            $data = array('task_progress' => 100);

            App::update('tasks',array('project' => $id),$data);

            $this->_project_complete($id);

            // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $project_title = Project::by_id($id)->project_title;
                Applib::slack(
                    sprintf(lang('project_marked_complete'), $project_title),
                    sprintf(lang('project_closed'), $project_title),
                    base_url().'projects/view/'.$id,
                    sprintf(lang('project_closed_by'), User::displayName(User::get_id()))
                );
            }

            // Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('project_edited_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('project_edited_successfully'));
                redirect($_SERVER["HTTP_REFERER"]);

        }else{
            $data['id'] = $id;
            $data['action'] = 'mark_as_complete';
            $this->load->view('modal/project_action',$data);

        }

    }

    /*function pin($id = NULL){
        $data = array('pinned' => 1);
        $this->db->where('project_id',$id)->update('projects',$data);
        Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('operation_successful'));
    }

    function unpin($id = NULL){
        $data = array('pinned' => 0);
        $this->db->where('project_id',$id)->update('projects',$data);
        Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('operation_successful'));
    }*/

    function _project_complete($project) {

        $message = App::email_template('project_complete','template_body');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::by_id($project);

        $cur = Client::client_currency($info->client);
        Project::update($project,array('status' => 'Done'));

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $client = str_replace("{CLIENT_NAME}",Client::view_by_id($info->client)->company_name,$logo);
        $title = str_replace("{PROJECT_TITLE}",$info->project_title,$client);
        $code = str_replace("{PROJECT_CODE}",$info->project_code,$title);
        $link = str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$code);
        $hours = str_replace("{PROJECT_HOURS}",Project::total_hours($project),$link);
        $cost = str_replace("{PROJECT_COST}",$cur->symbol.''.Applib::format_deci(Project::sub_total($project)),$hours);
        $signature = str_replace("{SIGNATURE}",$signature,$cost);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['recipient'] = Client::view_by_id($info->client)->company_email;
        $params['subject'] = App::email_template('project_complete','subject');
        $params['message'] = $message;


        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }

    function client_notification($project){

        $info = Project::by_id($project);
        $message = App::email_template('project_created','template_body');
        $subject =  App::email_template('project_created','subject');
        $signature = App::email_template('email_signature','template_body');



        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $created_by = str_replace("{CREATED_BY}",User::displayName(User::get_id()),$logo);
        $title = str_replace("{PROJECT_TITLE}",$info->project_title,$created_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['recipient'] = Client::view_by_id($info->client)->company_email;

        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);
    }



    function _comment_notification($project,$comment){
        $comment = strip_tags($comment);

        $message = App::email_template('project_comment','template_body');
        $subject = App::email_template('project_comment','subject');
        $signature = App::email_template('email_signature','template_body');


        $info = Project::by_id($project);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $comment_msg = str_replace("{COMMENT_MESSAGE}",$comment,$logo);
        $posted_by = str_replace("{POSTED_BY}",User::displayName(User::get_id()),$comment_msg);
        $title = str_replace("{PROJECT_TITLE}",$info->project_title,$posted_by);
        $link =  str_replace("{COMMENT_URL}",base_url().'projects/view/'.$project.'?group=comments',$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        // Send email to team

        if(User::is_client()){
            foreach (Project::project_team($project) as $key => $user) {
                $params['recipient'] = User::login_info($user->assigned_user)->email;
                $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';
                modules::run('fomailer/send_email',$params);

            }
        }else{
            // Send email to client
            $params['recipient'] = Client::view_by_id($info->client)->company_email;
            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            modules::run('fomailer/send_email',$params);
        }
    }

    function assigned_notification($project){

        $message = App::email_template('project_assigned','template_body');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $assigned_by = str_replace("{ASSIGNED_BY}",User::displayName(User::get_id()),$logo);
        $title = str_replace("{PROJECT_TITLE}",Project::by_id($project)->project_title,$assigned_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        foreach (Project::project_team($project) as $user) {

            $params['recipient'] = User::login_info($user->assigned_user)->email;

            $params['subject'] = App::email_template('project_assigned','subject');
            $params['message'] = $message;

            $params['attached_file'] = '';

            modules::run('fomailer/send_email',$params);
        }
    }


    function lead_notification($project){

        $message = App::email_template('project_assigned','template_body');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $assigned_by = str_replace("{ASSIGNED_BY}",User::displayName(User::get_id()),$logo);
        $title = str_replace("{PROJECT_TITLE}",Project::by_id($project)->project_title,$assigned_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        // foreach (Project::by_id($project) as $user) {
            // print_r(Project::by_id($project)->assign_lead); exit;


            $params['recipient'] = User::login_info(Project::by_id($project)->assign_lead)->email;

            $params['subject'] = App::email_template('project_assigned','subject');
            $params['message'] = $message;

            $params['attached_file'] = '';
            // echo "<pre>"; print_r($params); exit;

            modules::run('fomailer/send_email',$params);
        // }
    }

    function _notify_project_update($project){

        $message = App::email_template('project_updated','template_body');
        $signature = App::email_template('email_signature','template_body');

        $project_title = Project::by_id($project)->project_title;

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $assigned_by = str_replace("{ASSIGNED_BY}",User::displayName(User::get_id()),$logo);
        $title = str_replace("{PROJECT_TITLE}",$project_title,$assigned_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        foreach (Project::project_team($project) as $user) {
            $params['recipient'] = User::login_info($user->assigned_user)->email;

            $params['subject'] = '['.config_item('company_name').']'.' '.lang('project_updated_subject');
            $params['message'] = $message;

            $params['attached_file'] = '';

            modules::run('fomailer/send_email',$params);
        }
    }



    function auto_progress($project = NULL){
        if ($project) {
            $current_status = Project::by_id($project)->auto_progress;
            $set_to = ($current_status == 'TRUE') ? 'FALSE' : 'TRUE';
            $data = array('auto_progress'=>$set_to);
            Project::update($project,$data);

            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', lang('progress_auto_calculated'));
            redirect($_SERVER['HTTP_REFERER']);
        }

    }






    function _project_list($archive = NULL,$filter_page= Null){

        if (User::is_admin() || User::perm_allowed(User::get_id(),'view_all_projects')){
            if(!empty($filter_page)){
                return $this->_admin_projects($archive,$this->filter_by,$filter_page);
            }else{
                return $this->_admin_projects($archive,$this->filter_by);
            }
        }
        elseif (User::login_role_name() == 'staff') { 
            if(!empty($filter_page)){
                return $this->_staff_projects($archive,$this->filter_by,$filter_page);
            }else{
                return $this->_staff_projects($archive,$this->filter_by);
            }
            
        }else{
            if(!empty($filter_page)){
                return $this->_client_projects($archive,$this->filter_by,$filter_page);
            }else{
                return $this->_client_projects($archive,$this->filter_by);
            }

        }
    }


    function _admin_projects($archive = FALSE,$filter_by = NULL,$filter_page= NULL){

        if ($archive) return Project::by_where(array('archived'=>'1','branch_id'=>$this->session->userdata('branch_id')));

        if($filter_by != NULL){
            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                if(!empty($filter_page)){
                    return Project::by_where_limit(array('archived !='=>'1','status' => $filter_by,'branch_id'=>$this->session->userdata('branch_id')));
                }
                else{
                    return Project::by_where(array('archived !='=>'1','status' => $filter_by,'branch_id'=>$this->session->userdata('branch_id')));
                }
            }
            else if( $this->session->userdata('user_type_name') =='company_admin'){
                if(!empty($filter_page)){
                    $branches = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                    $branches = array_column($branches,'branch_id');

                    if(!empty($_GET['project_title'])){
                        $cond_like['project_title']  = $_GET['project_title'];
                        return $this->db->like($cond_like)->where(array('archived'=>'1','status' => $filter_by))->where_in('branch_id',$branches)->limit($_GET['length'],$_GET['start'])->get('projects')->result();
                    }
                    else{
                        return $this->db->where(array('archived'=>'1','status' => $filter_by))->where_in('branch_id',$branches)->limit($_GET['length'],$_GET['start'])->get('projects')->result();
                    }
                }
                else{
                    $branches = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                    $branches = array_column($branches,'branch_id');
                    if(!empty($_GET['project_title'])){
                        $cond_like['project_title']  = $_GET['project_title'];
                        return $this->db->like($cond_like)->where(array('archived'=>'1','status' => $filter_by))->where_in('branch_id',$branches)->get('projects')->result();
                    }
                    else{
                        return $this->db->where(array('archived'=>'1','status' => $filter_by))->where_in('branch_id',$branches)->get('projects')->result();
                    }

                   
                }
            }
            else{
                if(!empty($filter_page)){
                    return Project::by_where_limit(array('archived !='=>'1','status' => $filter_by));
                }
                else{
                    return Project::by_where(array('archived !='=>'1','status' => $filter_by));
                }
            }
        }else{
            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                if(!empty($filter_page)){
                    return Project::by_where_limit(array('archived !='=>'1','branch_id'=>$this->session->userdata('branch_id')));
                }else{
                    return Project::by_where(array('archived !='=>'1','branch_id'=>$this->session->userdata('branch_id')));
                }
            }
            else if( $this->session->userdata('user_type_name') =='company_admin'){
                if(!empty($filter_page)){
                    $branches = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                    $branches = array_column($branches,'branch_id');
                    if(!empty($_GET['project_title'])){
                        $cond_like['project_title']  = $_GET['project_title'];
                        return $this->db->like($cond_like)->where(array('archived'=>'1'))->where_in('branch_id',$branches)->limit($_GET['length'],$_GET['start'])->get('projects')->result();
                    }else{
                      return $this->db->where(array('archived'=>'1'))->where_in('branch_id',$branches)->limit($_GET['length'],$_GET['start'])->get('projects')->result();
                    }
                }else{
                    $branches = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                    $branches = array_column($branches,'branch_id');

                    if(!empty($_GET['project_title'])){
                        $cond_like['project_title']  = $_GET['project_title'];
                        return $this->db->like($cond_like)->where(array('archived'=>'1'))->where_in('branch_id',$branches)->get('projects')->result();
                    }
                    else{
                        return $this->db->where(array('archived'=>'1'))->where_in('branch_id',$branches)->get('projects')->result();
                    }
                }
            }
            else{
                if(!empty($filter_page)){
                    return Project::by_where_limit(array('archived !='=>'1'));
                }
                else{
                    return Project::by_where(array('archived !='=>'1'));
                }
            }
        }
    }


    function _staff_projects($archive = FALSE, $filter_by = NULL,$filter_page= NULL){

        if ($archive) {
            $pr['projects'] =  Project::by_where(array('archived'=>'1','assigned_user'=>User::get_id()),'assign_projects');
            $lead_check = $this->db->get_where('projects',array('assign_lead'=>$this->session->userdata('user_id'),'archived '=>'1'))->result();
            // $created_check = $this->db->get_where('projects',array('created_by'=>$this->session->userdata('user_id'),'archived '=>'1'))->result();
            if(count($lead_check) != 0){
                $pr['projects'] = $lead_check;
            }
            return $pr['projects'];
        }

        if($filter_by != NULL){
            if(!empty($filter_page)){
                return Project::by_where_limit(array('assigned_user'=>User::get_id(),'archived !='=>'1','status'=>$filter_by),'assign_projects');
            }
            else{
                return Project::by_where(array('assigned_user'=>User::get_id(),'archived !='=>'1','status'=>$filter_by),'assign_projects');
            }

        }else{
            if(!empty($filter_page)){
                $pr['projects'] = Project::by_where_limit(array('assigned_user'=>User::get_id(),'archived !='=>'1'),'assign_projects');
            }
            else{
                $pr['projects'] = Project::by_where(array('assigned_user'=>User::get_id(),'archived !='=>'1'),'assign_projects');
            }
            
            $lead_check = $this->db->get_where('projects',array('assign_lead'=>$this->session->userdata('user_id'),'archived '=>'0'))->result();
            // $created_check = $this->db->get_where('projects',array('created_by'=>$this->session->userdata('user_id'),'archived '=>'0'))->result();
            if(count($lead_check) != 0){
                $pr['projects'] = $lead_check;
            }
            return $pr['projects'];
        }
    }



    function _client_projects($archive = FALSE, $filter_by = NULL,$filter_page= NULL){
        $client = User::profile_info(User::get_id())->company;
        if ($archive) {
            return Project::by_where(array('archived'=>'1','client'=>$client));

        }
        if($filter_by != NULL){
            if(!empty($filter_page)){
                return Project::by_where_limit(array('client'=>$client, 'archived !=' => '1', 'status' => $filter_by));
            }else{
              return Project::by_where(array('client'=>$client, 'archived !=' => '1', 'status' => $filter_by));
            }
        }else{
            if(!empty($filter_page)){
                return Project::by_where_limit(array('client'=>$client,'archived !=' => '1'));
            }else{
                return Project::by_where(array('client'=>$client,'archived !=' => '1'));
            }
        }
    }

    // Check filter by

    function _filter_by(){

        $filter = isset($_GET['view']) ? $_GET['view'] : '';

        switch ($filter) {

            case 'active':
                return 'Active';
                break;

            case 'on_hold':
                return 'On Hold';
                break;

            case 'done':
                return 'Done';

                break;

            default:
                return NULL;
                break;
        }
    }




    // Check project owner
    function _check_owner($project){

        if(User::is_admin()) return TRUE;
        if(User::profile_info(User::get_id())->company == Project::by_id($project)->client){ return TRUE; }
        if(User::perm_allowed(User::get_id(),'view_all_projects')) { return TRUE; }
        if(Project::is_assigned(User::get_id(),$project)){ return TRUE;
        } else{
            App::access_denied('projects');
        }
    }


    function checkmilestone_date()
    {
        $res = $this->db->get_where('dgt_milestones',array('id'=>$this->input->post('id')))->row_array();
        $str_date = new DateTime($res['start_date']);
        $end_date = new DateTime($res['due_date']);
        $resss = array(
            'start_date' => $str_date->format('d-m-Y'),
            'due_date' => $end_date->format('d-m-Y')
        );
        echo json_encode($resss); exit;
    }


    function grid_view()
    {
        $archive = FALSE;
        if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }
        $data = array(
            'page' => lang('projects'),
            'projects' => $this->_project_list($archive),
            'datatables' => TRUE,
            'archive' => $archive
        );
        $this->template
            ->set_layout('users')
            ->build('grid_projects',isset($data) ? $data : NULL);
    }


    public function downloads($path,$file)
    {
            $this->load->helper('download');
            $file = 'assets/project-files/'.$path.'/'.$file;
            force_download($file, NULL);
            

    }


    // Gantt Chart..

    public function project_chart($project_id)
    {
        $data = array(
                'page' => lang('projects'),
                'form' => TRUE,
                'datepicker' => TRUE,
                'nouislider' => TRUE,
                'editor'    => TRUE,
                'set_fixed_rate' => TRUE,
                'project_id' => $project_id
            );
        $data['task_list'] = $this->db->get_where('tasks',array('project'=>$project_id))->result_array();
        $this->session->set_userdata('project_id',$project_id);
        $this->template
            ->set_layout('users')
            ->build('project_chart',isset($data) ? $data : NULL);
    }

    public function chart_tasks()
    {
        $project_id = $this->input->post('project_id');
        $task_list = $this->db->get_where('tasks',array('project'=>$project_id))->result_array();
        $taskss = array();
        $tasks_val = array();
        $i = 0;
        if(count($task_list) != 0)
        {
            foreach($task_list as $tasks){
                $s_date_y = date('Y',strtotime($tasks['date_added']));
                $s_date_m = date('m',strtotime($tasks['date_added']));
                $s_date_d = date('j',strtotime($tasks['date_added']));
                $e_date_y = date('Y',strtotime($tasks['due_date']));
                $e_date_m = date('m',strtotime($tasks['due_date']));
                $e_date_d = date('j',strtotime($tasks['due_date']));
                $r[$i][]= $tasks['t_id']; 
                $r[$i][]= $tasks['task_name']; 
                $r[$i][]=$s_date_y."/".($s_date_m - 1)."/".$s_date_d; 
                $r[$i][]=$e_date_y."/".($e_date_m - 1)."/".$e_date_d;
                $r[$i][]= null; 
                $r[$i][]= 100; 
                $r[$i][]= null;

                $taskss[]=$r[$i];
                $i++;
            }
            $e = json_encode($taskss); 
            echo $e; exit;
        }else{
            echo 'empty'; exit;
        }
    }

}

/* End of file projects.php */
