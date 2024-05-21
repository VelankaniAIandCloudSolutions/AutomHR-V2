<?php
error_reporting(-1);

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class All_tasks extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation','Applib'));
        $this->load->model(array('Client', 'App', 'Lead','Project'));
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
         $all_routes = $this->session->userdata('all_routes');
        foreach($all_routes as $key => $route){
            if($route == 'all_tasks'){
                $routname = all_tasks;
            } 
        }
        if(empty($routname)){
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
        $this->template->title(lang('all_tasks').' - '.config_item('company_name'));
        $data['page'] = lang('all_tasks');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['types']="project";
        if (!User::is_admin()) {

            $data['all_projects'] = $this->_get_projects();
            $data['project_id'] = $this->_get_projects_id();
            
        }
        else
        {
            $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
             $this->db->select('project_id');
            $this->db->order_by('project_id',DESC);
            $this->db->limit(1);
            $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active'))->row_array();
        }
        
       
        $this->template
                ->set_layout('users')
                ->build('all_tasks', isset($data) ? $data : null);
    }

    public function view($project_id)
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_tasks').' - '.config_item('company_name'));
        $data['page'] = lang('all_tasks');
        $data['datatables'] = true;
        $data['types']="project";
        if (!User::is_admin()) {

            $data['all_projects'] = $this->_get_projects();
            $data['project_id'] = $this->_get_projectss_id($project_id);
            
        }
        else
        {
            $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
            $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active','project_id'=>$project_id))->row_array();
        }

        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

     public function task_view($project_id)
    {

        $task_id=$this->uri->segment('4');
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_tasks').' - '.config_item('company_name'));
        $data['page'] = lang('all_tasks');
        $data['datatables'] = true;
        $data['tasks_id'] = $task_id;
        $data['types']="task";
        if (!User::is_admin()) {

            $data['all_projects'] = $this->_get_projects();
            $data['project_id'] = $this->_get_projectss_id($project_id);
            
        }
        else
        {
            $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
             $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active','project_id'=>$project_id))->row_array();
        }
        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

    function _get_projects()
    {
        $projects = $this->db->get_where('projects',array('status'=>'Active','assign_lead'=>$this->session->userdata('user_id')))->result_array();

        $assign_projects = $this->db->query("SELECT p.*,ap.project_assigned FROM dgt_assign_projects AS ap LEFT JOIN dgt_projects AS p ON p.project_id=ap.project_assigned  WHERE ap.assigned_user='".$this->session->userdata('user_id')."' AND p.status='Active'")->result_array();

        if(!empty($projects))
        {
            return $projects;
        }
        else
        {
            return $assign_projects;
        }
        
       
    } 

     function _get_projects_id()
    {
        
            $this->db->select('project_id');
            $this->db->order_by('project_id',DESC);
            $this->db->limit(1);
            $projects = $this->db->get_where('projects',array('status'=>'Active','assign_lead'=>$this->session->userdata('user_id')))->row_array();

           
        $assign_projects = $this->db->query("SELECT p.project_id FROM dgt_assign_projects AS ap  LEFT JOIN dgt_projects AS p ON p.project_id=ap.project_assigned WHERE ap.assigned_user='".$this->session->userdata('user_id')."' AND p.status='Active' ORDER BY p.project_id DESC LIMIT 1")->row_array();

        if(!empty($projects))
        {
            return $projects;
        }
        else
        {
            return $assign_projects;
        }
        
       
    } 

     function _get_projectss_id($project_id)
    {
        
            $this->db->select('project_id');
            $this->db->order_by('project_id',DESC);
            $this->db->limit(1);
            $projects = $this->db->get_where('projects',array('status'=>'Active','assign_lead'=>$this->session->userdata('user_id'),'project_id'=>$project_id))->row_array();

           
        $assign_projects = $this->db->query("SELECT p.* FROM dgt_assign_projects AS ap  LEFT JOIN dgt_projects AS p ON p.project_id=ap.project_assigned WHERE ap.assigned_user='".$this->session->userdata('user_id')."' AND p.status='Active' AND p.project_id='".$project_id."'")->row_array();

        if(!empty($projects))
        {
            return $projects;
        }
        else
        {
            return $assign_projects;
        }
        
       
    } 
    
    function add($project_id = NULL) {
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
                if(User::is_client()) { $assign = Project::by_id($project)->assign_to; }else{
                    $assign = serialize($this->input->post('assigned_to'));
                }
                $start_date = Applib::date_formatter($_POST['start_date']);
                $due_date = Applib::date_formatter($_POST['due_date']);

                $form_data = array(
                    'task_name' => $this->input->post('task_name',TRUE),
                    'project' => $this->input->post('project',TRUE),
                    'start_date' => $start_date,
                    'due_date' => $due_date,
                    'assigned_to' => $assign,
                    'task_progress' => $this->input->post('progress')?$this->input->post('progress'):0,
                    'description' => $this->input->post('description'),
                    'estimated_hours' => $this->input->post('estimate'),
                    'added_by' => User::get_id(),
                );
                if (!User::is_client()) {
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                } else {
                    $form_data['visible'] = 'Yes';
                }


                $task_id = App::save_data('tasks',$form_data);
                $assign = unserialize($assign);
                if (count($assign) > 0) {
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
                'value1' => $this->input->post('task_name'),
                'value2' => ''
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', lang('task_add_success'));
            redirect('all_tasks');
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
    
    function milestone_add($project_id = NULL)
    {
        if ($this->input->post()) {

        $project = $this->input->post('project');

        $this->form_validation->set_rules('milestone_name', 'Milestone Name', 'required');
        $this->form_validation->set_rules('project', 'Project', 'required');

        if ($this->form_validation->run() == FALSE)
        {
             Applib::make_flashdata(array('form_error'=> validation_errors()));
             $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
            redirect('all_tasks');
        }else{
            $_POST['start_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['start_date']), 'Y-m-d');
            $_POST['due_date'] = date_format(date_create_from_format(config_item('date_php_format'), $_POST['due_date']), 'Y-m-d');

            $milestone_id = App::save_data('milestones',$this->input->post());

            // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_milestone_action($project,$milestone_id,User::get_id(),'added');
            }

            $data = array(
                'module' => 'milestones',
                'module_field_id' => $project,
                'user' => User::get_id(),
                'activity' => 'activity_added_new_milestone',
                'icon' => 'fa-laptop',
                'value1' => $this->input->post('milestone_name'),
                'value2' => ''
                );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', lang('milestone_added_successfully'));
            redirect('all_tasks');

        }
        }else{
            if($this->uri->segment(4) == '')
            {
                $p_dates = $this->db->get_where('projects',array('project_id'=>$project_id))->row_array();    
            }else{
                $p_dates = $this->db->get_where('projects',array('project_id'=>$this->uri->segment(4)))->row_array();   
            }

            $data['project'] = $p_dates['project_id'];
            $data['start_date'] = $p_dates['start_date'];
            $data['due_date'] = $p_dates['due_date'];
            $data['datepicker'] = TRUE;
            $data['action'] = 'add';
            $this->load->view('modal/milestone_action',isset($data) ? $data : NULL);
        }
    }


    public function post_comments()
    {
         if ($this->input->post()) {
            $project = $this->input->post('project');
            $description = $this->input->post('description');

            Applib::is_demo();

           
            if(file_exists($_FILES['projectfiles']['tmp_name']) || is_uploaded_file($_FILES['projectfiles']['tmp_name'])) {

                    $p = Project::by_id($project);
                    $path = date("Y-m-d",  strtotime($p->date_created))."_".$project."_".$p->project_code.'/';
                    $fullpath = './assets/project-files/'.$path;
                    Applib::create_dir($fullpath);
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = '*';
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if(!$this->upload->do_upload("projectfiles")) {
                    

                     $this->session->set_flashdata('tokbox_success', $this->upload->display_errors('<span style="color:red">', '</span><br>'));

                       redirect('all_tasks/view/'.$project);

                    } else {
                        
                        $fileinfs = $this->upload->data();
     
                    }


                     $data = array(
                                'project'       => $project,
                                'path'          => $path,
                                'file_name'     => $fileinfs['file_name'],
                                'ext'           => $fileinfs['file_ext'],
                                'size'          => Applib::format_deci($fileinfs['file_size']),
                                'is_image'      => $fileinfs['is_image'],
                                'image_width'   => $fileinfs['image_width'],
                                'image_height'  => $fileinfs['image_height'],
                                'uploaded_by'   => User::get_id(),
                            );
                            $data['date_posted'] = date('Y-m-d H:i:s');
                            $file_id = App::save_data('files',$data);


                }

                if(!empty($description))
                {
                    $data = array('project'=>$project,'posted_by'=>User::get_id(),'message'=>$description,'date_posted'=> date('Y-m-d H:i:s'));
                    App::save_data('comments',$data);   
                }
                     
        

             
                    // Applib::go_to('projects/view/'.$project.'/?group=files','success',lang('file_uploaded_successfully'));
                    $this->session->set_flashdata('tokbox_success', '');
                    redirect('all_tasks/view/'.$project);

        }
      
    }


    public function post_task_comments()
    {

         if ($this->input->post()) {
            $project = $this->input->post('project');
            $task = $this->input->post('task');
            $description = $this->input->post('description');

            Applib::is_demo();

           
            if(file_exists($_FILES['projectfiles']['tmp_name']) || is_uploaded_file($_FILES['projectfiles']['tmp_name'])) {

                    $p = Project::by_id($project);
                    $path = date("Y-m-d",  strtotime($p->date_created))."_".$project."_".$p->project_code.'/';
                    $fullpath = './assets/project-files/'.$path;
                    Applib::create_dir($fullpath);
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = '*';
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if(!$this->upload->do_upload("projectfiles")) {
                    

                     $this->session->set_flashdata('tokbox_success', $this->upload->display_errors('<span style="color:red">', '</span><br>'));

                       redirect('all_tasks/task_view/'.$project.'/'.$task);

                    } else {
                        
                        $fileinfs = $this->upload->data();
     
                    }


                     $data = array(
                                'task'       => $task,
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
                            $file_id = App::save_data('task_files',$data);


                }

                if(!empty($description))
                {
                    $data = array('task_id'=>$task,'posted_by'=>User::get_id(),'message'=>$description,'date_posted'=> date('Y-m-d H:i:s'));
                    App::save_data('comments',$data);   
                }
                     
        

             
                    // Applib::go_to('projects/view/'.$project.'/?group=files','success',lang('file_uploaded_successfully'));
                    $this->session->set_flashdata('tokbox_success', '');
                     redirect('all_tasks/task_view/'.$project.'/'.$task);

        }
      
    }


    public function add_tasks()
    {
        $form_data = array(
                    'task_name' => $this->input->post('task_name',TRUE),
                    'project' => $this->input->post('project',TRUE),
                    'added_by' => User::get_id(),
                );

      


        $task_id = App::save_data('tasks',$form_data);


            $form_datas = array(
                    'task_id' => $task_id,
                    'activites' => 'created task',
                    'added_by' => User::get_id(),
                    'date_posted'=>date('Y-m-d H:i:s')
                );
            App::save_data('task_activites',$form_datas);

             $data['success'] = true;
             $data['message'] = 'Success!';
             $data['task_id'] = $task_id;
             echo json_encode($data);

              exit;
    }

    public function description_update()
    {

        $this->db->where('t_id',$this->input->post('task_id'));
        if($this->db->update('tasks', array('description' =>$this->input->post('description'))))
        {
            $data['success'] = true;
             $data['message'] = 'Success!';
            echo json_encode($data);

              exit;
        }
      


    }


    function assign_user($project_id=NULL,$task_id = NULL) {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);

          
            $task_id = $this->input->post('task', TRUE);
            $type = $this->input->post('type', TRUE);
            
            $form_data = array();
            if($type=='Assign')
            {
                 $assign = $this->input->post('assigned_to');
                if(User::is_client()) { $assign = Project::by_id($project)->assign_to; }else{
                    $assign = serialize($this->input->post('assigned_to'));
                }

                $form_data['assigned_to']=$assign;
            }
           
               
            if($type=='Due')
            {

                $due_date = Applib::date_formatter($_POST['due_date']);
                $form_data['due_date'] = $due_date;
            }

            $this->db->where('t_id',$task_id);
            if($this->db->update('tasks',$form_data))
            {

            if($type=='Due')
            {
                        $form_datas = array(
                    'task_id' => $task_id,
                    'activites' => 'changed due date',
                    'added_by' => User::get_id(),
                    'date_posted'=>date('Y-m-d H:i:s')
                );
            App::save_data('task_activites',$form_datas);

                     $data['activity_username']=User::displayName(User::get_id()); 
                     $data['activity']='changed due date';  
                     $data['activity_date']=date('M d Y h:ia',strtotime(date('Y-m-d H:i:s')));

                     $data['success'] = true;
                     $data['message'] = 'Success!';
                     $data['task_id'] = $task_id;
                     $data['date'] = date('M d, Y',strtotime($due_date));
                     echo json_encode($data);

                    exit;
            }

            if($type=='Assign')
            {

                        $assign = $this->input->post('assigned_to');
                        $this->db->where('task_assigned',$task_id);
                        $this->db->delete('assign_tasks');
                        if (!empty($assign) > 0) {
                        
                                $team = array(
                                    'assigned_user' => $assign,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                                App::save_data('assign_tasks',$team);
                           
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
                        'value1' => $this->input->post('task_name'),
                        'value2' => ''
                        );
                    App::Log($data);



                     $data['success'] = true;
                     $data['message'] = 'Success!';
                     $data['task_id'] = $task_id;
                     


                      $team_members = $this->db->select('*')
                                     ->from('assign_tasks PA')
                                     ->join('account_details AD','PA.assigned_user = AD.user_id')
                                     ->where('PA.task_assigned',$task_id)
                                     ->get()->result_array(); 

                                     foreach($team_members as $member)
                                     {
                                        if($member['avatar'] == '' )
                                     {
                                        $pro_pic_team = base_url().'assets/avatar/default_avatar.jpg';
                                     }else{
                                        $pro_pic_team = base_url().'assets/avatar/'.$member['avatar'];
                                     }
                                     $assignrd_name=$member['fullname'];
                                     }

                    $data['profile_img'] = $pro_pic_team;
                    $data['profiler_name'] = $assignrd_name;

                     $data['activity_username']=User::displayName(User::get_id()); 
                     $data['activity']='assigned to '.$assignrd_name;  
                     $data['activity_date']=date('M d Y h:ia',strtotime(date('Y-m-d H:i:s')));  


                     $form_datas = array(
                    'task_id' => $task_id,
                    'activites' => 'assigned to '.$assignrd_name,
                    'added_by' => User::get_id(),
                    'date_posted'=>date('Y-m-d H:i:s')
                );
            App::save_data('task_activites',$form_datas);


                   echo json_encode($data);

                    exit;

           }
         }

   
            
        } else {
            if($this->uri->segment(3) == '')
            {
                $id = $project_id;    
            }else{
                $id = $this->uri->segment(3);   
            }

            if($this->uri->segment(4) == '')
            {
                $ids = $task_id;    
            }else{
                $ids = $this->uri->segment(4);   
            }

            $data['project'] = $id;
            $data['task_id'] = $ids;
            $data['action'] =  $this->uri->segment(5);
            $this->load->view('modal/assign_action', isset($data) ? $data : NULL);
        }
    }

    public function delete_assigne()
    {
        $task_id=$this->input->post('task_id');

         $this->db->where('task_assigned',$task_id);
         if($this->db->delete('assign_tasks'))
         {
            $form_data['assigned_to']='';
            $this->db->where('t_id',$task_id);
            $this->db->update('tasks',$form_data);

            $data['success'] = true;
            $data['message'] = 'Success!';
            echo json_encode($data);
         }

           

            exit;
    }

    public function delete_due_date()
    {
        $task_id=$this->input->post('task_id');

        $form_data['due_date']='';
        $this->db->where('t_id',$task_id);
         
         if($this->db->update('tasks',$form_data))
         {
            

            $data['success'] = true;
            $data['message'] = 'Success!';
            echo json_encode($data);
         }

           

            exit;
    }


    function _task_changed_notification($project,$task) {

        $message = App::email_template('task_updated','template_body');
        $subject = App::email_template('task_updated','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $task_name = str_replace("{TASK_NAME}", Project::view_task($task)->task_name, $logo);
        $assigned_by = str_replace("{ASSIGNED_BY}",User::displayName(User::get_id()), $task_name);
        $title = str_replace("{PROJECT_TITLE}", Project::by_id($project)->project_title, $assigned_by);
        $link = str_replace("{PROJECT_URL}", base_url().'projects/view/'.$project.'?group=tasks',$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        foreach (Project::task_team($task) as $u) {
                $params['recipient'] = User::login_info($u->assigned_user)->email;
                $params['subject'] = '[' .config_item('company_name') . ']' .' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';
                modules::run('fomailer/send_email', $params);
            }
    }

    function _assigned_notification($project,$task) {


        $message = App::email_template('task_assigned','template_body');
        $subject = App::email_template('task_assigned','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $task_name = str_replace("{TASK_NAME}", Project::view_task($task)->task_name, $logo);
        $assigned_by = str_replace("{ASSIGNED_BY}", User::displayName(User::get_id()), $task_name);
        $title = str_replace("{PROJECT_TITLE}", Project::by_id($project)->project_title, $assigned_by);
        $link = str_replace("{PROJECT_URL}", base_url() . 'projects/view/'.$project.'?group=tasks', $title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

            foreach (Project::task_team($task) as $user) {
                $params['recipient'] = User::login_info($user->assigned_user)->email;
                $params['subject'] = '['.config_item('company_name').']'.' ' .$subject;
                $params['message'] = $message;

                $params['attached_file'] = '';
                modules::run('fomailer/send_email', $params);
        }
    }


    function _new_task_notification($task){

            $info = Project::view_task($task);

            $message = App::email_template('task_created','template_body');
            $subject =  App::email_template('task_created','subject');
            $signature = App::email_template('email_signature','template_body');

            $logo_link = create_email_logo();

            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

            $created_by = str_replace("{CREATED_BY}",User::displayName(User::get_id()),$logo);
            $title = str_replace("{TASK_NAME}",$info->task_name,$created_by);
            $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$info->project,$title);
            $signature = str_replace("{SIGNATURE}",$signature,$link);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            if(User::is_client()){ // Send email to task team
                foreach (Project::task_team($task) as $key => $u) {
                    $params['recipient'] = User::login_info($u->assigned_user)->email;
                    $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                    $params['message'] = $message;
                    $params['attached_file'] = '';
                    modules::run('fomailer/send_email',$params);
                }
            }else{ // Send email to client
                if($info->visible == 'Yes'){
                    $client_id = Project::by_id($info->project)->client;
                    $params['recipient'] = Client::view_by_id($client_id)->company_email;
                    $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                    $params['message'] = $message;
                    $params['attached_file'] = '';
                    modules::run('fomailer/send_email',$params);
                }
            }


    }

    public function download($path,$file)
    {
            $this->load->helper('download');
            $file = 'assets/project-files/'.$path.'/'.$file;
            force_download($file, NULL);
            

    }

     public function delete_task()
     {
        $task_id=$this->input->post('delete_task');
        $project_id=$this->input->post('delete_project');

        $this->db->where('t_id',$task_id);
        if($this->db->delete('tasks'))
        {
            redirect(base_url().'/all_tasks/view/'.$project_id);
        }
     }


    

}
/* End of file contacts.php */
