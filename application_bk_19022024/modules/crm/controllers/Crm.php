<?php
error_reporting(-1);

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Crm extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation','Applib'));
        $this->load->model(array('Client', 'App', 'Lead','Project'));        
        $this->load->model('crm_model','crm');
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        // $all_routes = $this->session->userdata('all_routes');
        // foreach($all_routes as $key => $route){
        //     if($route == 'crm'){
        //         $routname = crm;
        //     } 
        // }
        // if(empty($routname)){
        //      $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('leads').' - '.config_item('company_name'));
        $data['page'] = lang('leads');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
           $branches = $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
        }
        else{
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
        }
        $this->db->select('*');
        $this->db->from('business_proposals b');
        $this->db->join('lead_board l','b.lead_status = l.task_board_id','LEFT');
        if(!empty($branches)){
            $this->db->where_in('branch_id',$branches);
        }
        else if($this->tank_auth->get_role_id() != 1){
            $this->db->where('branch_id',$this->session->userdata('branch_id'));		
        }
        $this->db->order_by('id','DESC');
        $all_leads = $this->db->get()->result_array();
         // print_r($all_leads); exit;
        $data['all_leads'] = $all_leads;


		if(!App::is_access('menu_business_leads'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        } 

        


        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('all_leads', isset($data) ? $data : null);
    }

    public function add_lead()
    {
         // print_r($_FILES);
         // print_r($_POST);
         //  exit();
        if ($this->input->post()) {


                if(file_exists($_FILES['avatar']['tmp_name']) || is_uploaded_file($_FILES['avatar']['tmp_name'])) {

                            $config['upload_path'] = './assets/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['overwrite'] = true;

                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('avatar')){
                                        echo $this->upload->display_errors(); exit;
                            }else{
                                $data = $this->upload->data();
                                $_POST['avatar'] = $data['file_name'];
                               
                            }
                }
                            $this->db->order_by('task_board_id',ASC);
                $lead_board = $this->db->get('lead_board')->row_array();
                if(!empty($lead_board)){
                    $lead_status = $lead_board['task_board_id'];
                }else{
                    $lead_status = 1;
                }
                $data = array(
                        'name'    => $_POST['name'],
                        'email'  => $_POST['email'],
                        'phone_no' => $_POST['phone_no'],
                        'project_name'  => $_POST['project_name'],
                        'project_amount'    => $_POST['project_amount'],
                        'avatar'    => $_POST['avatar'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'lead_status'    => $lead_status,
                        'status'    => ($_POST['status'] !='')?$_POST['status']:'0'
                    );
                if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                     $branch_id =  $data['branch_id'] = $this->session->userdata('branch_id');
                }
                else{
                    $branch_id =  $data['branch_id'] = $this->input->post('branch_id');
                }
                $this->db->insert('business_proposals',$data);
                $lead_id = $this->db->insert_id();  
                // print_r($this->db->last_query()); exit();
                $args = array(
                            'user' => User::get_id(),
                            'module' => 'crm',
                            'module_field_id' => $lead_id,
                            'activity' => 'Add Lead',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('name', true),
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('lead_created_successfully'));
                redirect('crm');

                

                 
                        
        } 
    }

    public function edit_lead()
    {
          // print_r($_FILES);
         // print_r($_POST);
           // die();
        if ($this->input->post()) {
 

                if(file_exists($_FILES['avatar']['tmp_name']) || is_uploaded_file($_FILES['avatar']['tmp_name'])) {

                            $config['upload_path'] = './assets/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['overwrite'] = true;

                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('avatar')){
                                        echo $this->upload->display_errors(); exit;
                            }else{
                                $data = $this->upload->data();
                                $_POST['avatar'] = $data['file_name'];
                               
                            }
                } else {

                    $_POST['avatar'] = $_POST['image'];
                }
                 unset($_POST['image']);
                // echo "<pre>"; print_r( $_POST); exit;
                $data = array(
                        'name'    => $_POST['name'],
                        'email'  => $_POST['email'],
                        'phone_no' => $_POST['phone_no'],
                        'project_name'  => $_POST['project_name'],
                        'project_amount'    => $_POST['project_amount'],
                        'avatar'    => $_POST['avatar'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'lead_status'    => $_POST['lead_status'],
                        'status'    => $_POST['status']
                    );
                    if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                        $branch_id =  $data['branch_id'] = $this->session->userdata('branch_id');
                   }
                   else{
                       $branch_id =  $data['branch_id'] = $this->input->post('branch_id');
                   }
               
                $this->db->where('id',$_POST['id']);
                $lead_id = $this->db->update('dgt_business_proposals',$data);      
                // print_r($this->db->last_query()); exit();
                $args = array(
                            'user' => User::get_id(),
                            'module' => 'all_leads',
                            'module_field_id' => $_POST['id'],
                            'activity' => 'Update contacts',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('name', true),
                        );
                App::Log($args);
               $this->session->set_flashdata('tokbox_success', lang('lead_edited_successfully'));
               redirect($_SERVER['HTTP_REFERER']);
                

                 
                        
        }else {
             // print_r($this->uri->segment(3)); exit;
            $data['id'] = $this->uri->segment(3);

            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                $branches = $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
             }
             else{
                 $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
             }
            $this->load->view('modal/edit_lead',$data);

        }
    }

    public function edit_task_board() {
        $data['id'] = $task_board_id = $this->uri->segment(3);
        $data['board'] = $this->db->get_where('lead_board',array('task_board_id'=> $task_board_id))->row_array();
        $this->load->view('modal/edit_task_board',$data);
    }

    public function lead_view($lead_id)
    {

        
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_leads').' - '.config_item('company_name'));
         $data['page'] = lang('crm');
        $data['sub_page'] = lang('leads');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['lead_id'] = $lead_id;
        $data['types']="task";
        $data['status']= $this->uri->segment('4');
        // echo $data['status']; exit;
       if (!User::is_admin()) {

            $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
            // $data['project_id'] = $this->_get_projectss_id($project_id);
            $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active','project_id'=>$project_id))->row_array();
            
         }
        else
       {
           $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
            $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active','project_id'=>$project_id))->row_array();
       }
        $this->template
        ->set_layout('users')
        ->build('kanban_task_view', isset($data) ? $data : null);
    }

     public function delete_lead()
    {
         if ($this->input->post()) {
               
                // echo "<pre>"; print_r( $_POST); exit;
               
               
                $this->db->where('id',$_POST['id']);
                $this->db->delete('business_proposals');      
                 // print_r($this->db->last_query()); die();
                $args = array(
                            'user' => User::get_id(),
                            'module' => 'all_leads',
                            'module_field_id' => $_POST['id'],
                            'activity' => 'Delete Lead',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('id', true),
                        );
                App::Log($args);
               $this->session->set_flashdata('tokbox_success', lang('lead_deleted_successfully'));
                redirect('crm');
                        
        }else {
             // print_r($this->uri->segment(3)); exit;
            $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_lead',$data);

        }
    }   
    
    public function business_proposals()
    {
           
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('business_proposals').' - '.config_item('company_name'));
        $data['page'] = lang('business_proposals');
        $data['form'] = TRUE;
        $data['datatables'] = true;
        $data['datepicker'] = true;
        $data['nouislider'] = true;
        $data['editor'] = true;
        $data['set_fixed_rate'] = true;
        $data['types']="business_proposals";
      $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active','project_id'=>1))->row_array();
        // echo $data['status']; exit;
		if(!App::is_access('menu_business_proposals'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        } 
        $this->template
        ->set_layout('users')
        ->build('business_proposals', isset($data) ? $data : null);
   
    }    
       function task_kanban_delete()
    {
        if ($this->input->post()) {    
        $task_id = $this->input->post('task_id');
        $project_id = $this->input->post('project_id');
        
        $this->db->where('t_id',$task_id);
        $this->db->delete('tasks');
         $this->session->set_flashdata('tokbox_success','Task Deleted Successfully');
        redirect('all_tasks/kanban/'.$project_id);
         }else{
            $data['task_id'] = $this->uri->segment(3);
            $data['project_id'] = $this->uri->segment(4);
            $this->load->view('modal/task_kanban_delete',$data);
        } 
    }
    public function lead_board_add()
    {
        $data['task_board_name']        = $this->input->post('task_board_name');
        $data['task_board_color']       = $this->input->post('task_board_color');
        $data['task_board_bc']          = $this->input->post('task_board_bc');
        $data['task_board_class']          = $this->input->post('task_board_class');
        $data['project_id']             = $this->input->post('project_id');
        $data['user_id']                = $this->session->userdata('user_id');
     //   $data['subdomain_id']           = $this->session->userdata('subdomain_id');
        $this->db->insert('lead_board',$data);
        // print_r($this->db->last_query());
        echo "success";
       }

     public function lead_board_edit()
    {
       
        if ($this->input->post()) {
            // echo "<pre>"; print_r($_POST); exit;          
            $data['task_board_name']        = $this->input->post('task_board_name');
            $data['task_board_color']       = $this->input->post('task_board_color');
            $data['task_board_bc']          = $this->input->post('task_board_bc');
            $data['task_board_class']          = $this->input->post('task_board_class');
            $data['project_id']             = $this->input->post('project_id');
            $data['user_id']                = $this->session->userdata('user_id');
            $this->db->where('task_board_id', $this->input->post('task_board_id'));
            $this->db->update('lead_board',$data);
            //$this->session->set_userdate('holyday_success','successs');
             echo $this->db->last_query();exit;
            echo "update"; exit;        
             
        }

    }

     public function lead_status_edit()
    {
       
        if ($this->input->post()) {
             // echo "<pre>"; print_r($_POST); exit;          
          
            $data['lead_status']                = $this->input->post('status');
            // $task_board = $this->db->get_where('task_board',array('task_board_id'=>$this->input->post('status')))->row_array();
            // if(ucfirst($task_board['task_board_name'])  == "Completed"){
            //     $data['task_progress'] = 100;
            // }
           
            $this->db->where('id', $this->input->post('user_id'));
            $this->db->update('business_proposals',$data);
            //$this->session->set_userdate('holyday_success','successs');
             
            echo "update"; exit;        
             
        }

    }

      function lead_board_delete()
    {
        if ($this->input->post()) {    
        $task_board_id = $this->input->post('task_board_id');
        // $project_id = $this->input->post('project_id');
        
        $this->db->where('task_board_id',$task_board_id);
        $this->db->delete('lead_board');
        $this->session->set_flashdata('tokbox_success','Lead Board Deleted Successfully');
        redirect('crm/business_proposals');
         }else{
            $data['task_board_id'] = $this->uri->segment(3);
            // $data['project_id'] = $this->uri->segment(4);
            $this->load->view('modal/lead_board_delete',$data);
        } 
    }
     function add_kanban_task($project_id = NULL) {

        if ($this->input->post()) {
        
            $project = $this->input->post('project', TRUE);

            $this->form_validation->set_rules('task_name', 'Task Name', 'required');
            $this->form_validation->set_rules('project', 'Project', 'required');

            if ($this->form_validation->run() == FALSE) {
                Applib::make_flashdata(array('form_error'=> validation_errors()));
                $this->session->set_flashdata('tokbox_error', lang('task_add_failed'));
                redirect('all_tasks');
            } else {

                $est_time = ($this->input->post('estimate') == '') ? 0 : $_POST['estimate'];

                $assign = $this->input->post('assigned_to');
                // echo"<pre>"; print_r($assign); exit;
                if(User::is_client()) { $assign = Project::by_id($project)->assign_to; }else{
                    $assign = serialize($this->input->post('assigned_to'));
                }
                if($_POST['due_date'] !=''){
                    $due_date = date('Y-m-d',strtotime($_POST['due_date']));
                } else {
                    $due_date = '';
                }
                $form_data = array(
                    'task_name' => $this->input->post('task_name',TRUE),
                    'project' => $this->input->post('project',TRUE),
                    // 'start_date' => $start_date,
                    'due_date' => $due_date,
                    'assigned_to' => $assign,
                    'priority' => $this->input->post('priority',TRUE),
                    'task_progress' => $this->input->post('progress')?$this->input->post('progress'):0,
                    // 'description' => $this->input->post('description'),
                    // 'estimated_hours' => $this->input->post('estimate'),
                    'status' => $this->input->post('status'),
                    'added_by' => User::get_id(),
                );
                if (!User::is_client()) {
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                } else {
                    $form_data['visible'] = 'Yes';
                }

                $assign = unserialize($assign);  
                // echo '<pre>'; print_r($form_data); exit;

                $task_id = App::save_data('tasks',$form_data);



                
                if ($assign > 0) {
                     foreach ($assign as $key => $value) {
                             $team = array(
                                    'assigned_user' => $value,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                         App::save_data('assign_tasks',$team);
                        }                   
                       
                }

                if (config_item('notify_task_assignments') == 'TRUE' && $assign != FALSE) {
                    $this->_assigned_notification($project, $task_id);
                }

                if(config_item('notify_task_created') == 'TRUE') $this->_new_task_notification($task_id);



                // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_task_action($project,$task_id,User::get_id(),'added');
            }

            // Log activity
            $data = array(
                'module' => 'tasks',
                'module_field_id' => $project,
                'user' => User::get_id(),
                'activity' => 'activity_added_new_task',
                'icon' => 'fa-tasks',
                'value1' => $this->input->post('task_name',TRUE),
                'value2' => '',
                'subdomain_id' => $this->session->userdata('subdomain_id')
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', lang('task_add_success'));
            redirect('all_tasks/kanban/'.$project);
            }
        } else {
            if($this->uri->segment(4) == '')
            {
                $id = $project_id;    
            }else{
                $id = $this->uri->segment(4);   
            }

            $data['project'] = $id;
            $data['action'] = 'add_task';
            $this->load->view('modal/task_action', isset($data) ? $data : NULL);
        }
    }

    function edit_kanban_task($task_id = NULL) {

        if ($this->input->post()) {
        // echo '<pre>'; print_r($_POST); exit;
            $project = $this->input->post('project', TRUE);
            $task_id = $this->input->post('t_id', TRUE);

            $this->form_validation->set_rules('task_name', 'Task Name', 'required');
            $this->form_validation->set_rules('project', 'Project', 'required');

            if ($this->form_validation->run() == FALSE) {
                Applib::make_flashdata(array('form_error'=> validation_errors()));
                $this->session->set_flashdata('tokbox_error', lang('task_add_failed'));
                redirect('all_tasks');
            } else {

                $est_time = ($this->input->post('estimate') == '') ? 0 : $_POST['estimate'];

                $assign = $this->input->post('assigned_to');
                if(User::is_client()) { $assign = Project::by_id($project)->assign_to; }else{
                    $assign = serialize($this->input->post('assigned_to'));
                }

                if($_POST['due_date'] !=''){
                    $due_date = date('Y-m-d',strtotime($_POST['due_date']));
                } else {
                    $due_date = '';
                }

                $form_data = array(
                    'task_name' => $this->input->post('task_name',TRUE),
                    'project' => $this->input->post('project',TRUE),
                    // 'start_date' => $start_date,
                    'due_date' => $due_date,
                    'assigned_to' => $assign,
                    'priority' => $this->input->post('priority',TRUE),
                    'task_progress' => $this->input->post('task_progress')?$this->input->post('task_progress'):0,
                    // 'description' => $this->input->post('description'),
                    // 'estimated_hours' => $this->input->post('estimate'),
                    'status' => $this->input->post('status'),
                    'added_by' => User::get_id(),
                );
                if (!User::is_client()) {
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                } else {
                    $form_data['visible'] = 'Yes';
                }

                $this->db->where('t_id',$task_id);
                $this->db->update('tasks',$form_data);
                $assign = unserialize($assign);
                if ($assign > 0) {
                   
                        $team = array(
                            'assigned_user' => $assign,
                            'project_assigned' => $project,
                            'task_assigned' => $task_id
                            );
                         $this->db->where('task_assigned',$task_id);
                         $this->db->update('assign_tasks',$team);
                   
                }

                if (config_item('notify_task_assignments') == 'TRUE' && $assign != FALSE) {
                    $this->_assigned_notification($project, $task_id);
                }

                if(config_item('notify_task_created') == 'TRUE') $this->_new_task_notification($task_id);



                // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_task_action($project,$task_id,User::get_id(),'added');
            }

            // Log activity
            $data = array(
                'module' => 'tasks',
                'module_field_id' => $project,
                'user' => User::get_id(),
                'activity' => 'activity_edited_task',
                'icon' => 'fa-tasks',
                'value1' => $this->input->post('task_name',TRUE),
                'value2' => '',
                'subdomain_id' => $this->session->userdata('subdomain_id')
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', "Task Edited Successfully");
            redirect('all_tasks/kanban/'.$project);
            }
        } else {
            if($this->uri->segment(3) == '')
            {
                $id = $task_id;    
            }else{
                $id = $this->uri->segment(3);   
            }
         $data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$id))->row_array(); 
            // $data['project'] = $id;
            $data['action'] = 'add_task';
            $this->load->view('modal/edit_kanban_task', isset($data) ? $data : NULL);
        }
    }
 
     function check_task_board_name()
    {
        $task_board_name = $this->input->post('check_task_board_name');
        $project_id = $this->input->post('project_id');
        $check_task_board_name = $this->db->get_where('task_board',array('task_board_name'=>$task_board_name,'project_id'=>$project_id))->num_rows();
        if($check_task_board_name > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
        function check_task_name()
    {
        $task_name = $this->input->post('check_task_name');
        $project_id = $this->input->post('project_id');
        $check_task_name = $this->db->get_where('tasks',array('task_name'=>$task_name,'project'=>$project_id))->num_rows();
        if($check_task_name > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
         public function progress_update()
    {
// print_r($this->input->post());exit;
        $this->db->where('t_id',$this->input->post('task_id'));
        if($this->db->update('tasks', array('task_progress' =>$this->input->post('progress'))))
        {
            $data['success'] = true;
             $data['message'] = 'Success!';
            echo json_encode($data);

              exit;
        }
      


    }
    
    public function lead_name_update()
    {

        $lead_name = $this->db->get_where('business_proposals',array('name' =>$this->input->post('name'),'id' =>$this->input->post('lead_id')))->row_array();

        if(count($lead_name) > 0){

            $data['success'] = false;
            $data['message'] = 'Name already exist';
            echo 'false';
            exit;
        } else {

            $this->db->where('id',$this->input->post('lead_id'));
            if($this->db->update('business_proposals', array('name' =>$this->input->post('name'))))
            {
                $data['success'] = true;
                 $data['message'] = 'Success!';
                echo json_encode($data);

                  exit;
            }

            
        }         

    }     
     public function description_update()
    {

        $this->db->where('id',$this->input->post('lead_id'));
        // print_r($this->input->post());exit;
        if($this->db->update('business_proposals', array('description' =>$this->input->post('description'))))
        {
            $data['success'] = true;
             $data['message'] = 'Success!';
            echo json_encode($data);

              exit;
        }
      


    }   

    public function post_lead_comments($lead=false)
    {

            //echo json_encode($this->input->post());exit;
    
       // print_r($_FILES);
       // print_r($this->input->post());

         if ($this->input->post()) {
            
            $lead = $this->input->post('lead');
            $description = $this->input->post('description');
        }

            Applib::is_demo();

            if($lead && $_FILES)     {
            if(file_exists($_FILES['projectfiles']['tmp_name']) || is_uploaded_file($_FILES['projectfiles']['tmp_name'])) {
   
                    $p = $this->db->where('id',$lead)->get('business_proposals')->row();
                     // echo json_encode($p);exit;
                    $path = date("Y-m-d",  strtotime($p->created))."_".$lead;
                    $fullpath = './assets/lead-files/'.$path;
                    Applib::create_dir($fullpath);
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = '*';
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if(!$this->upload->do_upload("projectfiles")) {
                    
                   
                     $this->session->set_flashdata('tokbox_success', $this->upload->display_errors('<span style="color:red">', '</span><br>'));

                   //  echo json_encode(array('return'=>'ok','msg'=>'success'));

                     //  redirect('all_tasks/task_view/'.$project.'/'.$task);

                    } else {
                        
                        $fileinfs = $this->upload->data();
                     //   echo json_encode(array('return'=>'ok1','msg'=>'success'));
     
                    }


                     $data = array(
                                'lead'       => $lead,
                                'path'          => $path,
                                'file_name'     => $fileinfs['file_name'],
                                'file_ext'           => $fileinfs['file_ext'],
                                'size'          => Applib::format_deci($fileinfs['file_size']),
                                'is_image'      => $fileinfs['is_image'],
                                'image_width'   => $fileinfs['image_width'],
                                'image_height'  => $fileinfs['image_height'],
                                'uploaded_by'   => User::get_id(),
                            );
                            $data['date_posted'] = date('Y-m-d H:i:s');
                            $file_id = App::save_data('lead_files',$data);


                }
            }

                if(!empty($description))
                {
                    $data = array('lead_id'=>$lead,'posted_by'=>User::get_id(),'message'=>$description,'date_posted'=> date('Y-m-d H:i:s'));
                    App::save_data('lead_comments',$data);  
                }
                     
        

             
                    // Applib::go_to('projects/view/'.$project.'/?group=files','success',lang('file_uploaded_successfully'));
                    $this->session->set_flashdata('tokbox_success', '');
                    echo json_encode(array('return'=>'ok','msg'=>'success'));exit;
                     //redirect('all_tasks/task_view/'.$project.'/'.$task);
                     // redirect('all_tasks/kanban/'.$project.'/'.$task);

        
      
    }

    public function download($path,$file)
    {
            $this->load->helper('download');
            $file = 'assets/lead-files/'.$path.'/'.$file;
            force_download($file, NULL);
            

    }


    public function team_datajax_load($project_id=NULL)
    {  
        $members = $this->input->post('assigned_to');
        if($members){
        $result =$this->db->select('*')
            ->from('users U')
            ->join('account_details AD','U.id = AD.user_id')
            ->where_in('U.id',$members)
            ->get()->result_array();
        }
        else $result ='';

        echo json_encode($result);exit;
    }

    public function ajax_comments_update($lead_id=FALSE)
    {
        $lead_id = $this->input->post('id');
        if($lead_id){
      $comments_data =   $this->db->query("SELECT '' as activites, '' as file_ext,CM.message,CM.date_posted,AD.fullname,'' as file_name,'' as path,AD.avatar FROM dgt_lead_comments AS CM LEFT JOIN dgt_account_details AS AD ON CM.posted_by = AD.user_id WHERE CM.lead_id='".$lead_id."' 
                                                            UNION  SELECT '' as activites,FI.file_ext,'' as message,FI.date_posted,ADS.fullname,FI.file_name,FI.path,ADS.avatar FROM dgt_lead_files AS FI LEFT JOIN dgt_account_details AS ADS ON FI.uploaded_by = ADS.user_id WHERE FI.lead='".$lead_id."'                                                           

                                                            ORDER by date_posted ASC")->result_array();

      echo json_encode($comments_data);exit;
        }
    }

}
/* End of file contacts.php */
