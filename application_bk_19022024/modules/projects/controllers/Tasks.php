<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tasks extends MX_Controller {

    function __construct() {
        parent::__construct();
        User::logged_in();

        $this->load->module('layouts');
        $this->load->library(array('template', 'form_validation'));
        $this->load->model(array('Project','App','Client'));
        $this->template->title(lang('projects'));

        $this->applib->set_locale();
    }


    function add() {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);

            $this->form_validation->set_rules('task_name', 'Task Name', 'required');
            $this->form_validation->set_rules('project', 'Project', 'required');

            if ($this->form_validation->run() == FALSE) {
                Applib::make_flashdata(array('form_error'=> validation_errors()));
                // Applib::go_to('projects/view/' .$project. '?group=tasks', 'error', lang('task_add_failed'));
                $this->session->set_flashdata('tokbox_error', lang('task_add_failed'));
                redirect('all_tasks/view/' .$project. '');
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

            // Applib::go_to('projects/view/'.$project.'?group=tasks', 'success', lang('task_add_success'));
            $this->session->set_flashdata('tokbox_success', lang('task_add_success'));
            redirect('all_tasks/view/'.$project.'');
            }
        } else {
            $data['project'] = $this->uri->segment(4);
            $data['action'] = 'add_task';
            $this->load->view('modal/task_action', isset($data) ? $data : NULL);
        }
    }

    function edit() {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);
            $task_id = $this->input->post('task_id', TRUE);

            $this->form_validation->set_rules('task_name', 'Task Name', 'required');
            $this->form_validation->set_rules('project', 'Project', 'required');

            if ($this->form_validation->run() == FALSE) {
                Applib::make_flashdata(array('form_error'=> validation_errors()));
                // Applib::go_to('projects/view/'.$project.'?group=tasks&view=task&id='.$task_id, 'error', lang('task_update_failed'));
                $this->session->set_flashdata('tokbox_error', lang('task_update_failed'));
                redirect('projects/view/'.$project.'?group=tasks&view=task&id='.$task_id);
            } else {
                $assign = NULL;
                $notify = TRUE; // Send email only when team changed
                $current_team = Project::view_task($task_id)->assigned_to;
                $assign = serialize($this->input->post('assigned_to'));
                if($assign == $current_team){ $notify = FALSE; }

                $start_date = Applib::date_formatter($_POST['start_date']);
                $due_date = Applib::date_formatter($_POST['due_date']);

                $form_data = array(
                    'task_name' => $this->input->post('task_name'),
                    'project' => $this->input->post('project'),
                    'start_date' => $start_date,
                    'due_date' => $due_date,
                    'description' => $this->input->post('description'),
                    'estimated_hours' => $this->input->post('estimate'),
                );
                if (!User::is_client()) {
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                    if($this->input->post('progress'))
                    $form_data['task_progress'] = $this->input->post('progress');

                    if (User::is_admin()) {
                        $assign = $this->input->post('assigned_to');
                        Project::delete_task_team($task_id);
                        if($assign){
                            foreach ($assign as $key => $value) {
                                $data = array(
                                    'assigned_user' => $value,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                                App::save_data('assign_tasks',$data);
                            }
                        }
                        $form_data['assigned_to'] = serialize($this->input->post('assigned_to'));
                    }

                }

                App::update('tasks',array('t_id' => $task_id),$form_data);

                    if (config_item('notify_task_assignments') == 'TRUE' && $notify) {
                        $this->_task_changed_notification($project, $task_id);
                    }

                // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_task_action($project,$task_id,User::get_id(),'edited');
            }


            // Log activity
            $data = array(
                'module' => 'tasks',
                'module_field_id' => $project,
                'user' => User::get_id(),
                'activity' => 'activity_edited_a_task',
                'icon' => 'fa-tasks',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $this->input->post('task_name'),
                'value2' => ''
                );
            App::Log($data);

            // $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('tokbox_success', lang('task_update_success'));
            redirect('projects/view/'.$project.'?group=tasks&view=task&id=' .$task_id);
            }

        } else {
            $task = $this->uri->segment(4);
            $data['fuelux'] = TRUE;
            $data['project'] = Project::view_task($task)->project;
            $data['id'] = $task;
            $data['action'] = 'edit_task';
            $this->load->view('modal/task_action', isset($data) ? $data : NULL);
        }
    }


    function timesheet() {
        if ($this->input->post()) {

            $task_id = $this->input->post('task_id', TRUE);

            foreach ($_POST as $key => $value) {
                $this->db->set('time_in_sec', timelog($value))->where('timer_id', $key)->update('tasks_timer');
            }


            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', lang('task_update_success'));
            redirect($_SERVER['HTTP_REFERER']);

        } else {
            $task = $this->uri->segment(4);
            $data['id'] = $task;
            $this->load->view('modal/edit_task_time', isset($data) ? $data : NULL);
        }
    }



    function add_from_template() {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);
            $template_id = $this->input->post('template_id', TRUE);

            $this->form_validation->set_rules('template_id', 'Template ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                redirect($_SERVER['HTTP_REFERER']);
            }
            $template = $this->db->where('template_id',$template_id)->get('saved_tasks')->row();

            $due_date = Applib::date_formatter($_POST['due_date']);
            $start_date = Applib::date_formatter($_POST['start_date']);

            $form_data = array(
                'task_name' => $template->task_name,
                'milestone' => $this->input->post('milestone'),
                'project' => $project,
                'assigned_to' => Project::by_id($project)->assign_to,
                'visible' => $template->visible,
                'start_date' => $start_date,
                'due_date' => $due_date,
                'task_progress' => 0,
                'description' => $template->task_desc,
                'estimated_hours' => $template->estimate_hours ? $template->estimate_hours : 0,
                'added_by' => User::get_id(),
            );
            $task_id = App::save_data('tasks',$form_data);

            foreach (unserialize(Project::by_id($project)->assign_to) as $key => $value) {
                        $data = array(
                                    'assigned_user' => $value,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                    App::save_data('assign_tasks',$data);
            }

            if (config_item('notify_task_assignments') == 'TRUE') {
                $this->_assigned_notification($project, $task_id);
            }

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
                'value1' => $template->task_name,
                'value2' => ''
                );
            App::Log($data);

            // Applib::go_to('projects/view/'.$project.'?group=tasks', 'success', lang('task_add_success'));
            $this->session->set_flashdata('tokbox_success', lang('task_add_success'));
            redirect('projects/view/'.$project.'?group=tasks');
        } else {
            $data['project'] = $this->uri->segment(4);
            $data['action'] = 'add_from_template';
            $this->load->view('modal/task_action', isset($data) ? $data : NULL);
        }
    }

    function file() {
        $action = $this->uri->segment(4);
        if ($this->input->post()) {
            Applib::is_demo();

                if ($action == 'edit') {
                    $project = $this->input->post('project', TRUE);
                    $title = $this->input->post('title', TRUE);
                    $task = $this->input->post('task', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);

                    $data = array(
                        "title" => $title,
                        "description" => $this->input->post('description'),
                    );

                    App::update('task_files',array('file_id'=>$file_id),$data);

                    // Log activity
                    $data = array(
                        'module' => 'tasks',
                        'module_field_id' => $project,
                        'user' => User::get_id(),
                        'activity' => 'activity_edited_file_task',
                        'icon' => 'fa-file',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $title,
                        'value2' => ''
                        );
                    App::Log($data);

                    // Applib::go_to('projects/view/'.$project.'?group=tasks&view=task&id='.$task, 'success', lang('file_edited_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('file_edited_successfully'));
                    redirect('projects/view/'.$project.'?group=tasks&view=task&id='.$task);

                }elseif ($action == 'delete') {
                    $project = $this->input->post('project', TRUE);
                    $task = $this->input->post('task', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);
                    $file_data = Project::view_task_file($file_id);

                    App::delete('task_files',array('file_id' => $file_id));

                    $fullpath = './assets/project-files/'.$file_data->path.$file_data->file_name;
                    if($file_data->path == NULL){
                        $fullpath = './assets/project-files/'.$file_data->file_name;
                    }
                    if(file_exists($fullpath)) unlink($fullpath);

                    // Log activity
                    $data = array(
                        'module' => 'tasks',
                        'module_field_id' => $project,
                        'user' => User::get_id(),
                        'activity' => 'activity_deleted_task_file',
                        'icon' => 'fa-file',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $file_data->title,
                        'value2' => ''
                        );
                    App::Log($data);

                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('file_deleted'));

                    redirect('projects/view/'.$project.'?group=tasks&view=task&id='.$task);
                } else {
                    //file uploading
                    $project = $this->input->post('project', TRUE);
                    $task = $this->input->post('task', TRUE);

                    $p = Project::by_id($project);
                    $path = date("Y-m-d",  strtotime($p->date_created))."_".$project."_".$p->project_code.'/';
                    $fullpath = './assets/project-files/'.$path;
                    Applib::create_dir($fullpath);
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = config_item('allowed_files');
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if (!$this->upload->do_multi_upload("taskfiles")) {
                        Applib::make_flashdata(array(
                        'form_error'=> $this->upload->display_errors('<span style="color:red">', '</span><br>')
                        ));

                        $this->session->set_flashdata('response_status', 'error');
                        $this->session->set_flashdata('message', lang('operation_failed'));
                        redirect('projects/view/'.$project.'?group=tasks&view=task&id='.$task);
                    } else {

                        $fileinfs = $this->upload->get_multi_upload_data();
                        foreach ($fileinfs as $findex => $fileinf) {
                            $data = array(
                                'task' => $task,
                                'title' => $this->input->post('title'),
                                'description' => $this->input->post('description'),
                                'path' => $path,
                                'file_name' => $fileinf['file_name'],
                                'file_ext' => $fileinf['file_ext'],
                                'size' => Applib::format_deci($fileinf['file_size']),
                                'is_image' => $fileinf['is_image'],
                                'image_width' => $fileinf['image_width'],
                                'image_height' => $fileinf['image_height'],
                                'original_name' => $fileinf['client_name'],
                                'uploaded_by' => User::get_id(),
                            );
                            $file_id = App::save_data('task_files',$data);
                        }
                        if (config_item('notify_project_files') == 'TRUE') {
                            $this->_upload_notification($project);
                        }

                        // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                Applib::slack(
                          sprintf(lang('task_file_msg'),$fileinf['file_name'],Project::view_task($task)->task_name),
                          sprintf(lang('new_task_file'), $p->project_title),
                          base_url().'projects/view/'.$project.'?group=tasks',
                          sprintf(lang('file_uploaded_by'), User::displayName(User::get_id()))
                          );
            }
                             // Log activity
                            $data = array(
                                'module' => 'tasks',
                                'module_field_id' => $project,
                                'user' => User::get_id(),
                                'activity' => 'activity_added_task_file',
                                'icon' => 'fa-file',
                                'branch_id' => $this->session->userdata('branch_id'),
                                'value1' => $fileinf['file_name'],
                                'value2' => ''
                                );
                            App::Log($data);
                            // Applib::go_to('projects/view/'.$project.'?group=tasks&view=task&id='.$task, 'success', lang('file_uploaded_successfully'));
                            $this->session->set_flashdata('tokbox_success', lang('file_uploaded_successfully'));
                            redirect('projects/view/'.$project.'?group=tasks&view=task&id='.$task);
                    }
                }

        } else {
            if ($action == 'edit') {
                $data['file_id'] = $this->uri->segment(5);

                $file = Project::view_task_file($data['file_id']);
                $data['task'] = $file->task;
                $fullpath = './assets/project-files/'.$file->path.$file->file_name;

                    if($file->path == NULL){
                        $fullpath = './assets/project-files/'.$file->file_name;
                    }
                $data['project'] = $this->uri->segment(6);
                $data['f'] = $file;
                $data['fullpath'] = $fullpath;
                $data['action'] = 'task_edit_file';
                $this->load->view('modal/task_action', isset($data) ? $data : NULL);
                return;
            }
            if ($action == 'delete') {

                $data['file_id'] = $this->uri->segment(5);
                $data['task'] = Project::view_task_file($data['file_id'])->task;
                $data['project'] = $this->uri->segment(6);
                $data['action'] = 'task_delete_file';
                $this->load->view('modal/task_action', isset($data) ? $data : NULL);
                return;


            }
            $data['project'] = $this->uri->segment(4);
            $data['task'] = $this->uri->segment(5);
            $data['action'] = 'task_add_file';
            $this->load->view('modal/task_action', isset($data) ? $data : NULL);
        }
    }

    /*function pin(){
        $id = $this->uri->segment(4);
        $data = array('pinned' => 1);
        $this->db->where('t_id',$id)->update('tasks',$data);
        Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('operation_successful'));
    }

    function unpin(){
        $id = $this->uri->segment(4);
        $data = array('pinned' => 0);
        $this->db->where('t_id',$id)->update('tasks',$data);
        Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('operation_successful'));
    }*/


    function comment() {
        if ($this->input->post()) {
            $_POST['posted_by'] = User::get_id();
            $project = $this->input->post('project',TRUE);
            $task_id = $this->input->post('task_id',TRUE);
            unset($_POST['files']);

            $comment_id = App::save_data('comments',$this->input->post());

            $comment = $this->input->post('message');

            // Send email to client and assigned user
            if (config_item('notify_task_comments') == 'TRUE') $this->_notify_task_comment($task_id,$comment);
             // Log activity
                    $data = array(
                        'module' => 'tasks',
                        'module_field_id' => $task_id,
                        'user' => User::get_id(),
                        'activity' => 'task_comment_add',
                        'icon' => 'fa-comment',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => Project::view_task($task_id)->task_name,
                        'value2' => ''
                        );
                    App::Log($data);

            // Applib::go_to('projects/view/'.$project.'/?group=tasks&view=task&id='.$task_id, 'success', lang('activity_task_comment_add'));

            $this->session->set_flashdata('tokbox_success', lang('activity_task_comment_add'));
            redirect('projects/view/'.$project.'/?group=tasks&view=task&id='.$task_id);
        }
    }


    function delete_comment(){
        if($this->input->post()){
            $id = $this->input->post('comment_id',TRUE);
            $comment_by = $this->db->where('comment_id',$id)->get('comments')->row()->posted_by;
            if(User::get_id() == $comment_by){
                App::delete('comments',array('comment_id' => $id));
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('comment_deleted'));
            }

            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['id'] = $this->uri->segment(4);
            $data['action'] = 'delete_task_comment';
            $this->load->view('modal/task_action',isset($data) ? $data : NULL);
        }
    }


    function _notify_task_comment($task,$comment) {

        $comment = strip_tags($comment);

        $message = App::email_template('task_comment','template_body');
        $subject = App::email_template('task_comment','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::view_task($task);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $comment_msg = str_replace("{COMMENT_MESSAGE}",$comment,$logo);

        $posted_by = str_replace("{POSTED_BY}",User::displayName(User::get_id()),$comment_msg);
        $task_name = str_replace("{TASK_NAME}", $info->task_name,$posted_by);
        $comment_url = str_replace("{COMMENT_URL}", base_url().'projects/view/'.$info->project . '?group=tasks&view=task&id='.$task,$task_name);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$comment_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $EmailSignature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $email = NULL;

        if (User::is_client()) { // Send mail to admins
            $recipients = array();
            foreach (Project::task_team($info->t_id) as $key => $user) {
                $recipients[] = User::login_info($user->assigned_user)->email;
            }
            $params['recipient'] = $recipients;
            $params['subject'] = '[' . config_item('company_name') . ']' . ' ' . $subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            modules::run('fomailer/send_email', $params);
        } else {
            $client = Project::by_id($info->project)->client;
            $params['recipient'] = Client::view_by_id($client)->company_email;
            $params['subject'] = '[' .config_item('company_name'). ']' . ' ' . $subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            modules::run('fomailer/send_email', $params);
        }


    }



    function _upload_notification($project) {

        $message = App::email_template('project_file','template_body');
        $subject = App::email_template('project_file','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $uploaded_by = str_replace("{UPLOADED_BY}", User::displayName(User::get_id()), $logo);
        $title = str_replace("{PROJECT_TITLE}", Project::by_id($project)->project_title, $uploaded_by);
        $project_url = str_replace("{PROJECT_URL}", base_url().'projects/view/'.$project.'/?group=tasks', $title);
        $signature = str_replace("{SIGNATURE}",$signature,$project_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        if(!User::is_client()){
                $company = Project::by_id($project)->client;
                $params['recipient'] = Client::view_by_id($company)->company_email;
                $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';

                modules::run('fomailer/send_email', $params);
        }else{
            foreach (Project::project_team($project) as $user) {
                $params['recipient'] = User::login_info($user->assigned_user)->email;
                $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';

                modules::run('fomailer/send_email', $params);
            }
        }

    }

    function tracking() {
        $action = ucfirst($this->uri->segment(4));
        $status = ($action == 'On') ? 1 : 0;
        $project = $this->uri->segment(5);
        $task = $this->uri->segment(6);
        $response = 'success';
        if ($action == 'Off') {
            $task_logged_time =  Project::task_timer($task);
            $timer = $this->db->where(array('user'=>User::get_id(),'status'=>'1','task'=>$task))->get('tasks_timer')->row();
            $time_logged = (time() - $timer->start_time) + $task_logged_time;

            $data = array('pro_id'=>$project,'task'=>$task,'status'=>$status,'user'=>User::get_id(),'end_time'=>time());
            $this->db->where(array('user'=>User::get_id(),'status'=>'1','task'=>$task,'time_in_sec'=> 0,'end_time'=>NULL))->update('tasks_timer',$data);

            $data = array('logged_time'=>Applib::format_deci($time_logged),'start_time'=>NULL);
            App::update('tasks',array('t_id'=>$task),$data);

            $message = 'timer_stopped_success';
        } else {
            $any_task_started = $this->db->where(array('pro_id'=>$project,'status'=>'1','user'=>User::get_id()))->get('tasks_timer')->num_rows();

            if($any_task_started == 0 && Project::timer_status('project',$project) != 'On'){
            $data = array(
                            'pro_id'=>$project,'task'=>$task,'status'=>$status,'user'=>User::get_id(),
                            'start_time'=>time()
                        );
            $this->db->insert('tasks_timer',$data); // Save to Daatabase
            $message = 'timer_started_success';
            }else{
                $response = 'error';
                $message = 'timer_already_started';
            }
        }
        Applib::go_to('projects/view/'.$project.'?group=tasks', $response, lang($message));
    }


    function download() {

        $file_id = $this->uri->segment(4);
        $this->load->helper('download');
        $f = Project::view_task_file($file_id);
        $fullpath = './assets/project-files/'.$f->path.$f->file_name;

        if($f->path == NULL){
            $fullpath = './assets/project-files/'.$f->file_name;
        }

        if ($f->file_name == '') {
           // Applib::go_to($_SERVER["HTTP_REFERER"], 'error', lang('operation_failed'));
           $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
           redirect($_SERVER["HTTP_REFERER"]);
        }
        if (file_exists($fullpath)) {
            $data = file_get_contents($fullpath); // Read the file's contents
            force_download($f->file_name, $data);
        }
    }

    function close_open(){
        $id = $this->uri->segment(4);
        $info = Project::view_task($id);
        if($info->task_progress < 100){
            $args = array('task_progress' => '100');
            App::update('tasks',array('t_id'=>$id),$args);
        }else{
            $args = array('task_progress' => '0');
            App::update('tasks',array('t_id'=>$id),$args);
        }
        // Applib::go_to($_SERVER["HTTP_REFERER"], 'success', lang('task_update_success'));
        $this->session->set_flashdata('tokbox_success', lang('task_update_success'));
        redirect($_SERVER["HTTP_REFERER"]);

    }



    function progress(){
        if($_POST){
            $task_id = $this->input->post('task_id',TRUE);
            $i = $this->db->where('t_id',$task_id)->get('tasks')->row();
            if($i->timer_status != 'On'){
            $task_complete = $this->input->post('task_complete');
            $progress = ($task_complete == 'true') ? '100' : '0';
            $args = array('task_progress' => $progress);
            App::update('tasks',array('t_id'=>$task_id),$args);


            if($progress=='100')
            {
                $activites='has complete the task';
            }
            else
            {
                $activites='has incompleted the task';
            }


            $form_datas = array(
                    'task_id' => $task_id,
                    'activites' => $activites,
                    'added_by' => User::get_id(),
                    'date_posted'=>date('Y-m-d H:i:s')
                );
            App::save_data('task_activites',$form_datas);

            $data['success'] = true;
            $data['message'] = 'Success!';
            $this->session->set_flashdata('tokbox_success', lang('task_update_success'));
            //$this->session->set_flashdata('message', lang('task_update_success'));
                echo json_encode($data);
                exit;
            }else{
                    $data['success'] = false;
                    $data['message'] = 'Failed!';
                    $this->session->set_flashdata('tokbox_success', lang('operation_failed'));
                    //$this->session->set_flashdata('message', lang('operation_failed'));
                    echo json_encode($data);
                    exit;
            }
        }

    }

    function preview() {
        if (!$this->input->post()) {
            $file_id = $this->uri->segment(4);
            $project_id = $this->uri->segment(5);

            $file = Project::view_task_file($file_id);
            $fullpath = './assets/project-files/'.$file->path.$file->file_name;

                if($file->path == NULL){
                    $fullpath = './assets/project-files/'.$file->file_name;
                }

            if ($file) {
                if (file_exists($fullpath) ){
                    $data['file'] = $file;
                    $data['file_path'] = $fullpath;
                    $data['project_id'] = $project_id;
                    $data['action'] = 'preview_task_file';
                    $this->load->view('modal/task_action', $data);
                } else {
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect('projects/view/'.$project_id);
                }
            } else {
                $this->session->set_flashdata('message', lang('operation_failed'));
                redirect('projects/view/'.$project_id);
            }
        }
    }

    function delete() {
        if ($this->input->post()) {
            $project = $this->input->post('project', TRUE);
            $task = $this->input->post('task_id');

            foreach (Project::task_has_files($task) as $file) {
                unlink('./assets/project-files/'.$file->file_name);
            }
            App::delete('tasks',array('t_id' => $task));
            App::delete('tasks_timer',array('task' => $task));
            App::delete('task_files',array('task' => $task));

            // Applib::go_to('projects/view/'.$project.'?group=tasks', 'success', lang('task_deleted'));
            $this->session->set_flashdata('tokbox_success', lang('task_deleted'));
            redirect('projects/view/'.$project.'?group=tasks');
        } else {
            $data['task_id'] = $this->uri->segment(5);
            $data['project'] = $this->uri->segment(4);
            $data['action'] = 'delete_task';
            $this->load->view('modal/task_action', $data);
        }
    }

        function autotasks() {
                $query = 'SELECT * FROM (
                            SELECT task_name FROM dgt_tasks
                            UNION ALL
                            SELECT task_name FROM dgt_saved_tasks
                            ) a
                            GROUP BY task_name
                            ORDER BY task_name ASC';
                $names = $this->db->query($query)->result();
                $name = array();
                foreach ($names as $n) {
                    $name[] = $n->task_name;
                }
                $data['json'] = $name;
                $this->load->view('json',isset($data) ? $data : NULL);
        }
        function autotask() {
                $name = $_POST['name'];
                $query = "SELECT * FROM (
                            SELECT task_name, description, estimated_hours as hours FROM dgt_tasks
                            UNION ALL
                            SELECT task_name, task_desc as description, estimate_hours as hours FROM dgt_saved_tasks
                            ) a
                            WHERE a.task_name = '".$name."'";
                $names = $this->db->query($query)->result();
                $name = $names[0];
                $data['json'] = $name;
                $this->load->view('json',isset($data) ? $data : NULL);
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

    function timesheet_add()
    {
        if ($this->input->post()) {

            // echo $this->session->userdata('user_id'); exit;

            $to_day = date('Y-m-d',strtotime($this->input->post('timesheet_date')));

            $check_status = $this->db->get_where('timesheet',array('user_id'=>$this->session->userdata('user_id'),'timeline_date'=>$to_day))->result_array();
            $total_hours = array();
            for($i=0;$i<count($check_status);$i++)
            {
                $time    = explode(':', $check_status[$i]['hours']);
                $minutes = ($time[0] * 60.0 + $time[1] * 1.0);
                $total_hours[] = $minutes;
            }
            $total_minitues =  array_sum($total_hours); 
            // echo 'time'.$total_minitues; exit;
            // if($total_minitues >= 480)
            // {
            //     // Applib::go_to($_SERVER["HTTP_REFERER"], 'error', "Hour Error (Less than 8 hours)");
            //     $this->session->set_flashdata('tokbox_error', "Hour Error (Less than 8 hours)");
            //     redirect($_SERVER["HTTP_REFERER"]);
            //     // echo "error_daily"; exit;
            // }else{
                // echo (480 - $total_minitues); exit;
                $balance_minitues = (480 - $total_minitues);
                $newtime    = explode(':', $this->input->post('timesheet_hours'));
                $newminutes = ($newtime[0] * 60.0 + $newtime[1] * 1.0);
                // echo $newminutes.'---'.$balance_minitues; exit;
                // if($balance_minitues >= $newminutes)
                // {
                    $result = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'project_id' => $this->input->post('project'),
                        'hours' => $this->input->post('timesheet_hours'),
                        'timeline_date' => $to_day,
                        'timeline_desc' => $this->input->post('timesheet_description'),
                        'task_id' => $this->input->post('task_id')
                    );
                    $this->db->insert('timesheet',$result);
                    // print_r($result); exit;
                    // Applib::go_to($_SERVER["HTTP_REFERER"], 'success', "Timesheet Added Successfully");
                    $this->session->set_flashdata('tokbox_success', "Timesheet Added Successfully");
                    redirect($_SERVER["HTTP_REFERER"]);
                // }else{
                //     // Applib::go_to($_SERVER["HTTP_REFERER"], 'error', "Hour Error (Less than 8 hours)");
                //     $this->session->set_flashdata('tokbox_error', "Hour Error (Less than 8 hours)");
                //     redirect($_SERVER["HTTP_REFERER"]);
                // }

            // }



            // echo "<pre>"; print_r($result); exit; 


        } else {
            $data['project'] = $this->uri->segment(4);
            $data['task_id'] = $this->uri->segment(5);
            $data['action'] = 'add_timesheet';
            $this->load->view('modal/timesheet_actions', isset($data) ? $data : NULL);
        }
    }

}

/* End of file tasks.php */
