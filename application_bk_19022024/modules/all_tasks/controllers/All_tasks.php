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
            $data['all_projects'] = $this->db->get_where('projects',array('branch_id'=>$this->session->userdata('branch_id'),'status'=>'Active'))->result_array();
             $this->db->select('project_id');
            $this->db->order_by('project_id',DESC);
            $this->db->limit(1);
            $data['project_id'] = $this->db->get_where('projects',array('branch_id'=>$this->session->userdata('branch_id'),'status'=>'Active'))->row_array();
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
     public function kanban_task_view($project_id)
    {

        $task_id=$this->uri->segment('4');
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_tasks').' - '.config_item('company_name'));
        $data['page'] = lang('all_kanban_tasks');
        $data['datatables'] = true;
        $data['datepicker'] = true;
        $data['tasks_id'] = $task_id;
        $data['types']="task";
        $data['status']= $this->uri->segment('5');
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
            $projects = $this->db->get_where('projects',array('status'=>'Active','assign_lead'=>$this->session->userdata('user_id'),'project_id'=>$project_id,'branch_id'=>$this->session->userdata('branch_id')))->row_array();

           
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
                'branch_id' => $this->session->userdata('branch_id'),
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
    function get_task_details() {

        
         $tasks = $this->db->get_where('tasks',array('t_id'=>$this->input->post('task_id')))->row_array(); 
         $tasks['assigned_to'] = unserialize($tasks['assigned_to']); 
        echo json_encode($tasks);

              exit; 
        
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
                'branch_id' => $this->session->userdata('branch_id'),
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


    public function post_task_comments($task=false,$project=false)
    {

            //echo json_encode($this->input->post());exit;
    
       // print_r($_FILES);
       // print_r($this->input->post());

         if ($this->input->post()) {
            $project = $this->input->post('project');
            $task = $this->input->post('task');
            $description = $this->input->post('description');
        }

            Applib::is_demo();

            if($task && $project && $_FILES)     {
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

                   //  echo json_encode(array('return'=>'ok','msg'=>'success'));

                     //  redirect('all_tasks/task_view/'.$project.'/'.$task);

                    } else {
                        
                        $fileinfs = $this->upload->data();
                     //   echo json_encode(array('return'=>'ok1','msg'=>'success'));
     
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
            }

                if(!empty($description))
                {
                    $data = array('task_id'=>$task,'posted_by'=>User::get_id(),'message'=>$description,'date_posted'=> date('Y-m-d H:i:s'));
                    App::save_data('comments',$data);  
                }
                     
        

             
                    // Applib::go_to('projects/view/'.$project.'/?group=files','success',lang('file_uploaded_successfully'));
                    $this->session->set_flashdata('tokbox_success', '');
                    echo json_encode(array('return'=>'ok','msg'=>'success'));exit;
                     //redirect('all_tasks/task_view/'.$project.'/'.$task);
                     // redirect('all_tasks/kanban/'.$project.'/'.$task);

        
      
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
        // print_r($this->input->post());exit;
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
                     //   $assign = serialize($this->input->post('assigned_to'));
                        $this->db->where('task_assigned',$task_id);
                        $this->db->delete('assign_tasks');
                        if (!empty($assign) > 0) {
                            foreach ($assign as $key => $value) {
                                $team = array(
                                    'assigned_user' => $value,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                            App::save_data('assign_tasks',$team);//print_r($this->db->last_query());exit;
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
                        'branch_id' => $this->session->userdata('branch_id'),
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


    public function kanban($project_id)
    {
            // if ($this->session->userdata('subdomain_id') != 6) {
            //     redirect();
            //     # code...
            // }
                   
                // echo "<pre>";print_r($this->session->userdata('subdomain_id')); exit;
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_tasks').' - '.config_item('company_name'));
        $data['page'] = "kanban";
        $data['form'] = TRUE;
        $data['datatables'] = true;
        $data['datepicker'] = true;
        $data['nouislider'] = true;
        $data['editor'] = true;
        $data['set_fixed_rate'] = true;
        $data['types']="task";
        if (!User::is_admin()) {

            $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
            $data['project_id'] = $this->_get_projectss_id($project_id);
        }
        else
        {
            $data['all_projects'] = $this->db->get_where('projects',array('status'=>'Active'))->result_array();
            $data['project_id'] = $this->db->get_where('projects',array('status'=>'Active','project_id'=>$project_id,'branch_id'=>$this->session->userdata('branch_id')))->row_array();
        }
   //print_r($data); exit;
        
        $this->template
        ->set_layout('users')
        ->build('kanban', isset($data) ? $data : null);
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
    public function task_board_add()
    {
        // print_r($_POST);exit();
        $data['task_board_name']        = $this->input->post('task_board_name');
        $data['task_board_color']       = $this->input->post('task_board_color');
        $data['task_board_bc']          = $this->input->post('task_board_bc');
        $data['task_board_class']          = $this->input->post('task_board_class');
        $data['project_id']             = $this->input->post('project_id');
        $data['user_id']                = $this->session->userdata('user_id');
        $data['branch_id']                = $this->session->userdata('branch_id');
     //   $data['subdomain_id']           = $this->session->userdata('subdomain_id');
        $this->db->insert('task_board',$data);
        // print_r($this->db->last_query());
        echo "success"; exit;
    }

     public function task_board_edit()
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
            $this->db->update('task_board',$data);
            //$this->session->set_userdate('holyday_success','successs');
             
            echo "update"; exit;        
             
        }

    }

     public function task_status_edit()
    {
       
        if ($this->input->post()) {
             // echo "<pre>"; print_r($_POST); exit;          
          
            $data['status']                = $this->input->post('status');
            $task_board = $this->db->get_where('task_board',array('task_board_id'=>$this->input->post('status')))->row_array();
            if(ucfirst($task_board['task_board_name'])  == "Completed"){
                $data['task_progress'] = 100;
            }
           
            $this->db->where('t_id', $this->input->post('t_id'));
            $this->db->update('tasks',$data);
            //$this->session->set_userdate('holyday_success','successs');
             
            echo "update"; exit;        
             
        }

    }

      function task_board_delete()
    {
        if ($this->input->post()) {    
        $task_board_id = $this->input->post('task_board_id');
        $project_id = $this->input->post('project_id');
        
        $this->db->where('task_board_id',$task_board_id);
        $this->db->delete('task_board');
        $this->session->set_flashdata('tokbox_success','Task Board Deleted Successfully');
        redirect('all_tasks/kanban/'.$project_id);
         }else{
            $data['task_board_id'] = $this->uri->segment(3);
            $data['project_id'] = $this->uri->segment(4);
            $this->load->view('modal/task_board_delete',$data);
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
                    'branch_id' => $this->session->userdata('branch_id'),
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
                'branch_id' => $this->session->userdata('branch_id'),
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
                'branch_id' => $this->session->userdata('branch_id'),
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
    
        public function task_name_update()
    {

        $task_name = $this->db->get_where('tasks',array('task_name' =>$this->input->post('task_name'),'project' =>$this->input->post('project_id')))->row_array();

        if(count($task_name) > 0){

            $data['success'] = false;
            $data['message'] = 'Name already exist';
            echo 'false';
            exit;
        } else {

            $this->db->where('t_id',$this->input->post('task_id'));
            if($this->db->update('tasks', array('task_name' =>$this->input->post('task_name'))))
            {
                $data['success'] = true;
                 $data['message'] = 'Success!';
                echo json_encode($data);

                  exit;
            }

            
        }

        
      


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

    public function ajax_comments_update($tasks_id=FALSE)
    {
        $tasks_id = $this->input->post('id');
        if($tasks_id){
      $comments_data =   $this->db->query("SELECT '' as activites, '' as file_ext,CM.message,CM.date_posted,AD.fullname,'' as file_name,'' as path,AD.avatar FROM dgt_comments AS CM LEFT JOIN dgt_account_details AS AD ON CM.posted_by = AD.user_id WHERE CM.task_id='".$tasks_id."' 
                                                            UNION  SELECT '' as activites,FI.file_ext,'' as message,FI.date_posted,ADS.fullname,FI.file_name,FI.path,ADS.avatar FROM dgt_task_files AS FI LEFT JOIN dgt_account_details AS ADS ON FI.uploaded_by = ADS.user_id WHERE FI.task='".$tasks_id."' 

                                                            UNION  SELECT TI.activites,''as file_ext,'' as message,TI.date_posted,ADsS.fullname,'' as file_name,'' as path,ADsS.avatar FROM dgt_task_activites AS TI LEFT JOIN dgt_account_details AS ADsS ON TI.added_by = ADsS.user_id WHERE TI.task_id='".$tasks_id."'

                                                            ORDER by date_posted ASC")->result_array();

      echo json_encode($comments_data);exit;
        }
    }

}
/* End of file contacts.php */
