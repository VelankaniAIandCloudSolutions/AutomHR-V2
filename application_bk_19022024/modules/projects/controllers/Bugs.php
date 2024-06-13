<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bugs extends MX_Controller {

    function __construct() {
        parent::__construct();
        User::logged_in();

        $this->load->module('layouts');
        $this->load->library(array('template', 'form_validation'));
        $this->load->model(array('Project','App'));
        $this->template->title(lang('projects'));

        $this->applib->set_locale();
    }

    function add() {
        if ($this->input->post()) {

            $project = $this->input->post('project');
            if (!User::is_admin() || !Project::is_assigned(User::get_id(),$project)) {
                $_POST['reporter'] = User::get_id();
                $_POST['assigned_to'] = $this->db->select_min('assigned_user')
                                                 ->where('project_assigned',$project)
                                                 ->get('assign_projects')->row()->assigned_user;
            }

            date_default_timezone_set(config_item('timezone'));
            $_POST['last_modified'] = date("Y-m-d H:i:s");

            $bug_id = App::save_data('bugs',$this->input->post());
            // Log activity
            $data = array(
                'module' => 'bugs',
                'module_field_id' => $bug_id,
                'user' => User::get_id(),
                'activity' => 'activity_issue_added',
                'icon' => 'fa-bug',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $this->input->post('issue_title'),
                'value2' => ''
                );
            App::Log($data);

            $assigned = $_POST['assigned_to'];

            if (config_item('notify_bug_assignment') == 'TRUE' && $assigned != '-') {
                $this->_notify_assigned_bug($bug_id);
            }

            if (config_item('notify_bug_reporter') == 'TRUE' && $_POST['reporter'] != User::get_id()) $this->_reported_notification($bug_id);

             // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $params = array('project'      => $project,
                                'bug'          => $bug_id,
                                'user'         => User::get_id(),
                                'action'       => 'added'
                                );
                $slack->slack_bug_action($params);
            }

            // Applib::go_to('projects/view/'.$_POST['project'].'/?group=bugs', 'success', lang('bug_assigned_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('bug_assigned_successfully'));
            redirect('projects/view/'.$_POST['project'].'/?group=bugs');

        } else {
            $data['project'] = $this->uri->segment(4);
            $data['action'] = 'add_bug';
            $this->load->view('modal/bug_action', isset($data) ? $data : NULL);
        }
    }



    function edit() {
        if ($this->input->post()) {
            date_default_timezone_set(config_item('timezone'));
            $_POST['last_modified'] = date("Y-m-d H:i:s");
            $bug_id = $this->input->post('bug_id',TRUE);
            $project = $this->input->post('project',TRUE);
            $assigned = $this->input->post('assigned_to',TRUE);
            $old_assigned = Project::view_bug($bug_id)->assigned_to;

            App::update('bugs',array('bug_id'=>$bug_id),$this->input->post());

            if (config_item('notify_bug_assignment') == 'TRUE' && $assigned != '-' && $assigned != $old_assigned) {
                $this->_notify_assigned_bug($bug_id);
            }

            // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $params = array('project'      => $project,
                                'bug'          => $bug_id,
                                'user'         => User::get_id(),
                                'action'       => 'edited'
                                );
                $slack->slack_bug_action($params);
            }

             // Log activity
            $data = array(
                'module' => 'bugs',
                'module_field_id' => $bug_id,
                'user' => User::get_id(),
                'activity' => 'activity_issue_edited',
                'icon' => 'fa-edit',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $this->input->post('issue_title'),
                'value2' => ''
                );
            App::Log($data);

            // Applib::go_to('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug_id, 'success', lang('bug_assigned_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('bug_assigned_successfully'));
            redirect('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug_id);
        } else {
            $bug_id = isset($_GET['id']) ? $_GET['id'] : '';
            $data['project'] = $this->uri->segment(4);
            $data['id'] = $bug_id;
            $data['action'] = 'edit_bug';
            $this->load->view('modal/bug_action', isset($data) ? $data : NULL);
        }
    }

    function file() {
        $action = $this->uri->segment(4);
        if ($this->input->post()) {
            Applib::is_demo();

                if ($action == 'edit') {
                    $project = $this->input->post('project', TRUE);
                    $title = $this->input->post('title', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);
                    $bug = $this->input->post('bug', TRUE);

                    $data = array(
                        "title" => $title,
                        "description" => $this->input->post('description'),
                    );
                    App::update('bug_files',array('file_id'=>$file_id),$data);

                    // Log activity
                    $data = array(
                        'module' => 'bugs',
                        'module_field_id' => $bug,
                        'user' => User::get_id(),
                        'activity' => 'activity_edited_file_bug',
                        'icon' => 'fa-file',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $title,
                        'value2' => ''
                        );
                    App::Log($data);
                    // Applib::go_to('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug,'success',lang('file_edited_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('file_edited_successfully'));
                    redirect('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug);

                } elseif ($action == 'delete') {
                    $this->load->helper("file");
                    $project = $this->input->post('project', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);
                    $bug = $this->input->post('bug', TRUE);

                    $f = Project::view_bug_file($file_id);

                    App::delete('bug_files', array('file_id' => $file_id));

                    if(file_exists('./assets/bug-files/'.$f->file_name)){
                        unlink('./assets/bug-files/'.$f->file_name);
                    }

                    // Log activity
                    $data = array(
                        'module' => 'bugs',
                        'module_field_id' => $bug,
                        'user' => User::get_id(),
                        'activity' => 'activity_deleted_file_bug',
                        'icon' => 'fa-file',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $f->file_name,
                        'value2' => ''
                        );
                    App::Log($data);
                    // Applib::go_to('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug,'success', lang('file_deleted'));
                    $this->session->set_flashdata('tokbox_success', lang('file_deleted'));
                    redirect('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug);
                } else {
                    $project = $this->input->post('project', TRUE);
                    $bug = $this->input->post('bug', TRUE);
                    $title = $this->input->post('title', TRUE);
                    $description = $this->input->post('description', TRUE);

                    $config['upload_path'] = './assets/bug-files/';
                    $config['allowed_types'] = config_item('allowed_files');
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if (!$this->upload->do_multi_upload("bugfiles")) {
                        // Applib::go_to('projects/view/'.$project.'?group=bugs', 'error', lang('operation_failed'));
                        $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                        redirect('projects/view/'.$project.'?group=bugs');

                    } else {

                        $fileinfs = $this->upload->get_multi_upload_data();
                        foreach ($fileinfs as $findex => $fileinf) {
                            $data = array(
                                'bug' => $bug,
                                'title' => $title,
                                'description' => $description,
                                'file_name' => $fileinf['file_name'],
                                'file_ext' => $fileinf['file_ext'],
                                'size' => Applib::format_deci($fileinf['file_size']),
                                'is_image' => $fileinf['is_image'],
                                'image_width' => $fileinf['image_width'],
                                'image_height' => $fileinf['image_height'],
                                'original_name' => $fileinf['client_name'],
                                'uploaded_by' => User::get_id(),
                            );
                            $file_id = App::save_data('bug_files',$data);
                        }
                    if(Project::view_bug($bug) != '-') $this->_upload_notification($bug);

                    // Post to slack channel
                    if(config_item('slack_notification') == 'TRUE'){
                        $this->load->helper('slack');
                        $slack = new Slack_Post;
                        $params = array('project'   => $project,
                                        'bug'       => $bug,
                                        'user'      => User::get_id(),
                                        'action'    => 'file_upload'
                                        );
                        $slack->slack_bug_action($params);
                    }

                    // Log activity
                    $data = array(
                        'module' => 'bugs',
                        'module_field_id' => $bug,
                        'user' => User::get_id(),
                        'activity' => 'activity_uploaded_file_bug',
                        'icon' => 'fa-file',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $title,
                        'value2' => ''
                        );
                    App::Log($data);

                    // Applib::go_to('projects/view/' . $project . '/?group=bugs&view=bug&id=' . $bug, 'success', lang('file_uploaded_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('file_uploaded_successfully'));
                        redirect('projects/view/'.$project.'/?group=bugs&view=bug&id='.$bug);
                    }
                }

        } else {
            if ($action == 'edit') {
                $data['action'] = 'bug_edit_file';
                $data['file_id'] = $this->uri->segment(5);
                $data['project'] = $this->uri->segment(6);
                $data['f'] = Project::view_bug_file($data['file_id']);
                $this->load->view('modal/bug_action', isset($data) ? $data : NULL);
                return;
            }
            if ($action == 'delete') {

                $data['action'] = 'bug_delete_file';
                $data['file_id'] = $this->uri->segment(5);
                $data['project'] = $this->uri->segment(6);
                $this->load->view('modal/bug_action', isset($data) ? $data : NULL);
                return;
            }
            $data['project'] = $this->uri->segment(4);
            $data['bug'] = $this->uri->segment(5);
            $data['action'] = 'add_bug_file';
            $this->load->view('modal/bug_action', isset($data) ? $data : NULL);
        }
    }

    function _upload_notification($bug) {
        $message = App::email_template('bug_file','template_body');
        $subject = App::email_template('bug_file','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::view_bug($bug);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $uploaded_by = str_replace("{UPLOADED_BY}",User::displayName(User::get_id()), $logo);
        $title = str_replace("{ISSUE_TITLE}",$info->issue_title, $uploaded_by);
        $project_url = str_replace("{BUG_URL}",base_url().'projects/view/'.$project.'?group=bugs&view=bug&id='.$bug,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$project_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $subject = str_replace("{TITLE}",$info->issue_title,$subject);

        $params['recipient'] = User::login_info($info->assigned_to)->email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function status() {
        $status = isset($_GET['s']) ? $_GET['s'] : '';
        $bug = isset($_GET['id']) ? $_GET['id'] : '';
        $project = $this->uri->segment(4);
        $info = Project::view_bug($bug);

        switch ($status) {
            case 'confirmed':
            App::update('bugs',array('bug_id'=>$bug),array('bug_status'=>'Confirmed'));
                break;
            case 'pending':
            App::update('bugs',array('bug_id'=>$bug),array('bug_status'=>'Pending'));
                break;
            case 'resolved':
            App::update('bugs',array('bug_id'=>$bug),array('bug_status'=>'Resolved'));
                break;
            case 'verified':
            App::update('bugs',array('bug_id'=>$bug),array('bug_status'=>'Verified'));
                break;
            default:
            App::update('bugs',array('bug_id'=>$bug),array('bug_status'=>'Unconfirmed'));
                break;
        }
        if (config_item('notify_bug_status') == 'TRUE' && $info->assigned_to != '-') {

            $this->_notify_bug_status_change($bug,$status);

        }

        // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $project_title = Project::by_id($info->project)->project_title;
                Applib::slack(
                          sprintf(lang('slack_bug_status_msg'), $info->issue_title, strtoupper(lang($status))),
                          sprintf(lang('slack_bug_status'), $project_title),
                          base_url().'projects/view/'.$project.'?group=bugs',
                          sprintf(lang('slack_bug_status_by'), User::displayName(User::get_id()))
                          );
            }

        // Applib::go_to('projects/view/'.$project.'/?group=bugs', 'success', lang('bug_status_changed'));
        $this->session->set_flashdata('tokbox_success', lang('bug_status_changed'));
        redirect('projects/view/'.$project.'/?group=bugs');

    }

    function _notify_bug_status_change($bug,$status) {

        $message = App::email_template('bug_status','template_body');
        $subject = App::email_template('bug_status','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::view_bug($bug);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $email_issue_title = str_replace("{ISSUE_TITLE}", $info->issue_title, $logo);
        $status = str_replace("{STATUS}", lang(strtolower($status)), $email_issue_title);
        $marker_by = str_replace("{MARKED_BY}",User::displayName(User::get_id()), $status);
        $bug_url = str_replace("{BUG_URL}",base_url().'projects/view/'.$info->project.'?group=bugs&view=bug&id='.$bug,$marker_by);
        $signature = str_replace("{SIGNATURE}",$signature,$bug_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        if(User::get_id() == $info->reporter){
            $params['recipient'] = User::login_info($info->assigned_to)->email;
        }else{
            $params['recipient'] = User::login_info($info->reporter)->email;
        }


        $params['subject'] = '[ ' .config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function preview(){
        if (!$this->input->post()) {
            $file_id = $this->uri->segment(4);
            $project_id = $this->uri->segment(5);
            $file = Project::view_bug_file($file_id);
            if ($file)
            {
                if(file_exists('./assets/bug-files/'.$file->file_name)){
                    $data['file'] = $file;
                    $data['action'] = 'preview_bug_file';
                    $this->load->view('modal/bug_action', $data);
                }else{
                    // $this->session->set_flashdata('message',$this->lang->line('operation_failed'));
                    $this->session->set_flashdata('tokbox_error', $this->lang->line('operation_failed'));
                    redirect('projects/view/'.$project_id);
                }
            }
            else
            {
                // $this->session->set_flashdata('message',$this->lang->line('operation_failed'));
                $this->session->set_flashdata('tokbox_error', $this->lang->line('operation_failed'));
                redirect('projects/view/'.$project_id);
            }
        }
    }

    function comment() {
        if ($this->input->post()) {
            $_POST['comment_by'] = User::get_id();
            $project = $this->input->post('project',TRUE);
            unset($_POST['project']);
            unset($_POST['files']);

            $comment_id = App::save_data('bug_comments',$this->input->post());

            $bug = $this->input->post('bug_id',TRUE);
            $comment = $this->input->post('comment');

            // Send email to client and assigned user
            if (config_item('notify_bug_comments') == 'TRUE') $this->_notify_bug_comment($bug,$comment);
             // Log activity
                    $data = array(
                        'module' => 'bugs',
                        'module_field_id' => $bug,
                        'user' => User::get_id(),
                        'activity' => 'bug_comment_add',
                        'icon' => 'fa-comment',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => Project::view_bug($bug)->issue_title,
                        'value2' => ''
                        );
                    App::Log($data);

            // Applib::go_to('projects/view/'.$project.'/?group=bugs&view=bug&id='.$_POST['bug_id'], 'success', lang('activity_bug_comment_add'));
            $this->session->set_flashdata('tokbox_success', lang('activity_bug_comment_add'));
            redirect('projects/view/'.$project.'/?group=bugs&view=bug&id='.$_POST['bug_id']);
        }
    }


    function delete_comment(){
        if($this->input->post()){
            $id = $this->input->post('comment_id',TRUE);
            $comment_by = $this->db->where('c_id',$id)->get('bug_comments')->row()->comment_by;
            if(User::get_id() == $comment_by){
                App::delete('bug_comments',array('c_id' => $id));
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('comment_deleted'));
            }

            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['id'] = $this->uri->segment(4);
            $data['action'] = 'delete_bug_comment';
            $this->load->view('modal/bug_action',isset($data) ? $data : NULL);
        }
    }

    function _notify_bug_comment($bug,$comment) {

        $message = App::email_template('bug_comment','template_body');
        $subject = App::email_template('bug_comment','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::view_bug($bug);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $comment_msg = str_replace("{COMMENT_MESSAGE}",$comment,$logo);

        $posted_by = str_replace("{POSTED_BY}",User::displayName(User::get_id()),$comment_msg);
        $email_issue_title = str_replace("{ISSUE_TITLE}", $info->issue_title,$posted_by);
        $comment_url = str_replace("{COMMENT_URL}", base_url() . 'projects/view/'.$info->project . '?group=bugs&view=bug&id='.$bug,$email_issue_title);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$comment_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $EmailSignature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $email = NULL;

        if ($info->reporter == User::get_id()) {
            if($info->assigned_to > 0){
                $email = User::login_info($info->assigned_to)->email;
            }
        } else {
            $email = User::login_info($info->reporter)->email;
        }
        if($email){

            $params['recipient'] = $email;

            $params['subject'] = '[' . config_item('company_name') . ']' . ' ' . $subject;
            $params['message'] = $message;

            $params['attached_file'] = '';

            modules::run('fomailer/send_email', $params);
        }
    }

    function _notify_assigned_bug($bug) {
        $message = App::email_template('bug_assigned','template_body');
        $subject = App::email_template('bug_assigned','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::view_bug($bug);

        $project_title = Project::by_id($info->project)->project_title;
        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $issue_title = str_replace("{ISSUE_TITLE}", $info->issue_title, $logo);
        $assigned_by = str_replace("{ASSIGNED_BY}", User::displayName(User::get_id()), $issue_title);
        $issue_project_title = str_replace("{PROJECT_TITLE}", $project_title, $assigned_by);
        $site_url = str_replace("{SITE_URL}", base_url(), $issue_project_title);
        $signature = str_replace("{SIGNATURE}",$signature,$site_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $subject = str_replace("{TITLE}",$info->issue_title,$subject);

        $params['recipient'] = User::login_info($info->assigned_to)->email;

        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function _reported_notification($bug) {
        $message = App::email_template('bug_reported','template_body');
        $subject = App::email_template('bug_reported','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::view_bug($bug);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $title = str_replace("{ISSUE_TITLE}",$info->issue_title,$logo);
        $added_by = str_replace("{ADDED_BY}",User::displayName(User::get_id()),$title);
        $project_url = str_replace("{BUG_URL}", base_url().'projects/view/'.$info->project.'?group=bugs&view=bug&id='.$bug, $added_by);
        $signature = str_replace("{SIGNATURE}",$signature,$project_url);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $subject = str_replace("{TITLE}",$info->issue_title,$subject);

        if (User::is_client()) {
              $params['recipient'] = User::login_info($info->assigned_to)->email;
              $params['subject'] = '['.config_item('company_name').']'.' ' .$subject;
              $params['message'] = $message;
              $params['attached_file'] = '';
              modules::run('fomailer/send_email', $params);
        }else{
              $params['recipient'] = User::login_info($info->reporter)->email;
              $params['subject'] = '['.config_item('company_name').']'.' ' .$subject;
              $params['message'] = $message;
              $params['attached_file'] = '';
              modules::run('fomailer/send_email', $params);
        }


    }

    function download() {
        $this->load->helper('download');
        $project = $this->uri->segment(4);
        $file_id = $this->uri->segment(5);
        $f = Project::view_bug_file($file_id);
        if (file_exists('./assets/bug-files/' . $f->file_name)) {
            $data = file_get_contents('./assets/bug-files/' . $f->file_name); // Read the file's contents
            force_download($f->file_name, $data);
        } else {
            // Applib::go_to('projects/view/'.$project.'/?group=bugs', 'error', lang('operation_failed'));
            $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
            redirect('projects/view/'.$project.'/?group=bugs');
        }
    }

    function delete() {
        if ($this->input->post()) {
            $project = $this->input->post('project',TRUE);
            $bug_id = $this->input->post('bug_id',TRUE);
            $info = Project::view_bug($bug_id);

            foreach (Project::bug_has_files($bug_id) as $file) {
                if(file_exists('./assets/bug-files/'.$file->file_name)){
                     unlink('./assets/bug-files/'.$file->file_name);
                 }
            }

            App::delete('bug_comments',array('bug_id' => $bug_id));
            App::delete('bug_files',array('bug' => $bug_id));
            App::delete('bugs',array('bug_id' => $bug_id));

            // Log activity
                    $data = array(
                        'module' => 'bugs',
                        'module_field_id' => $bug_id,
                        'user' => User::get_id(),
                        'activity' => 'activity_bug_delete',
                        'icon' => 'fa-bug',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $info->issue_title,
                        'value2' => ''
                        );
                    App::Log($data);

                    // Applib::go_to('projects/view/'.$project.'/?group=bugs', 'success', lang('issue_deleted_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('issue_deleted_successfully'));
                    redirect('projects/view/'.$project.'/?group=bugs');

        } else {
            $bug_id = isset($_GET['id']) ? $_GET['id'] : '';

            $data['project'] = $this->uri->segment(4);
            $data['bug_id'] = $bug_id;
            $data['action'] = 'delete_bug';
            $this->load->view('modal/bug_action', $data);
        }
    }

}

/* End of file projects.php */
