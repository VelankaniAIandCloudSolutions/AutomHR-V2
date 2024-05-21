<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends MX_Controller {

	function __construct()
	{
		parent::__construct();
        User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','form_validation'));
		$this->load->model(array('Project','App'));
		$this->template->title(lang('projects'));
        $this->applib->set_locale();
	}
	function add()
	{
		if ($this->input->post()) {
			$project = $this->input->post('project', TRUE);
			$description = $this->input->post('description', TRUE);


			Applib::is_demo();

					$p = Project::by_id($project);
                    $path = date("Y-m-d",  strtotime($p->date_created))."_".$project."_".$p->project_code.'/';
					$fullpath = './assets/project-files/'.$path;
					Applib::create_dir($fullpath);
					$config['upload_path'] = $fullpath;
                    $config['allowed_types'] = config_item('allowed_files');
                    $config['max_size']	= config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if(!$this->upload->do_multi_upload("projectfiles")) {
                    Applib::make_flashdata(array(
                    	'response_status' => 'error',
                    	'message' => lang('operation_failed'),
                        'form_error'=> $this->upload->display_errors('<span style="color:red">', '</span><br>')
                        ));
                    	redirect('projects/view/'.$project.'?group=files');
                    } else {

                        $fileinfs = $this->upload->get_multi_upload_data();
                        foreach($fileinfs as $findex=>$fileinf) {
                            $data = array(
                                'project'       => $project,
                                'path'          => $path,
                                'file_name'	    => $fileinf['file_name'],
                                'title'         => $_POST['title'],
                                'ext'           => $fileinf['file_ext'],
                                'size'  	    => Applib::format_deci($fileinf['file_size']),
                                'is_image'      => $fileinf['is_image'],
                                'image_width'   => $fileinf['image_width'],
                                'image_height'  => $fileinf['image_height'],
                                'description'	=> $description,
                                'uploaded_by'	=> User::get_id(),
                            );
                            $data['date_posted'] = date('Y-m-d H:i:s');
                            $file_id = App::save_data('files',$data);
                        }

                if (config_item('notify_project_files') == 'TRUE') $this->_upload_notification($project);

            	// Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $params = array('project'   => $project,
                                'user'      => User::get_id()
                                );
                $slack->slack_project_file($params);
            }

             // Log activity
                    $data = array(
                        'module' => 'files',
                        'module_field_id' => $project,
                        'user' => User::get_id(),
                        'activity' => 'activity_uploaded_file',
                        'icon' => 'fa-file',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $this->input->post('title',TRUE),
                        'value2' => ''
                        );
                    App::Log($data);
                    // Applib::go_to('projects/view/'.$project.'/?group=files','success',lang('file_uploaded_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('file_uploaded_successfully'));
                    redirect('projects/view/'.$project.'/?group=files');
                    }

		}else{
			$data['project'] = $this->uri->segment(4);
			$data['action'] = 'add_file';
			$this->load->view('modal/file_action',isset($data) ? $data : NULL);
		}
	}
	function edit()
	{
		if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);
            $title = $this->input->post('title', TRUE);
            $file_id = $this->input->post('file_id', TRUE);
            $array = array();
            date_default_timezone_set('UTC');
            $array = $this->input->post();
            $array['date_posted'] = date('Y-m-d H:i:s');
			Applib::is_demo();

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

                    if(!$this->upload->do_multi_upload("projectfiles")) {
                    Applib::make_flashdata(array(
                        'response_status' => 'error',
                        'message' => lang('operation_failed'),
                        'form_error'=> $this->upload->display_errors('<span style="color:red">', '</span><br>')
                        ));
                        redirect('projects/view/'.$project.'?group=files');
                    } else {

                            $fileinfs = $this->upload->get_multi_upload_data();
                        foreach($fileinfs as $findex=>$fileinf) {
                            $data = array(
                                'project'       => $project,
                                'path'          => $path,
                                'file_name'     => $fileinf['file_name'],
                                'title'         => $_POST['title'],
                                'ext'           => $fileinf['file_ext'],
                                'size'          => Applib::format_deci($fileinf['file_size']),
                                'is_image'      => $fileinf['is_image'],
                                'image_width'   => $fileinf['image_width'],
                                'image_height'  => $fileinf['image_height'],
                                'description'   => $description,
                                'uploaded_by'   => User::get_id(),
                            );
                            $data['date_posted'] = date('Y-m-d H:i:s');
                            App::update('files',array('file_id'=>$file_id),$data);
                        }

                             if (config_item('notify_project_files') == 'TRUE') $this->_upload_notification($project);

                                    // Post to slack channel
                                if(config_item('slack_notification') == 'TRUE'){
                                    $this->load->helper('slack');
                                    $slack = new Slack_Post;
                                    $params = array('project'   => $project,
                                                    'user'      => User::get_id()
                                                    );
                                    $slack->slack_project_file($params);
                                }


                                 App::update('files',array('file_id'=>$file_id),$array);
                             // Log activity
                                    $data = array(
                                        'module' => 'files',
                                        'module_field_id' => $project,
                                        'user' => User::get_id(),
                                        'activity' => 'activity_edited_file',
                                        'icon' => 'fa-file',
                                        'branch_id' => $this->session->userdata('branch_id'),
                                        'value1' => $title,
                                        'value2' => ''
                                        );
                                    App::Log($data);

                                    // Applib::go_to('projects/view/'.$project.'/?group=files','success',lang('file_edited_successfully'));
                                    $this->session->set_flashdata('tokbox_success', lang('file_edited_successfully'));
                                    redirect('projects/view/'.$project.'/?group=files');


                    }




			

			

		}else{
			if(isset($_GET['id'])){
				$data['file_id'] = $_GET['id'];
				$file = Project::view_file($data['file_id']);
                $fullpath = './assets/project-files/'.$file->path.$file->file_name;
                    if($file->path == NULL)
                        $fullpath = './assets/project-files/'.$file->file_name;

                $data['file_path'] = $fullpath;
                $data['f'] = $file;
				$data['project_id'] = $this->uri->segment(4);
				$data['action'] = 'edit_file';
				$this->load->view('modal/file_action',$data);
			}
		}
	}

	function download()
	{
		$this->load->helper('download');
		$file_id = $this->uri->segment(4);
		$file = Project::view_file($file_id);
		$project = $file->project;

	    $fullpath = './assets/project-files/'.$file->path.$file->file_name;
	    if($file->path == NULL)
	            $fullpath = './assets/project-files/'.$file->file_name;

		if($fullpath){
				$data = file_get_contents($fullpath); // Read the file contents
				force_download($file->file_name, $data);
			}else{
				// Applib::go_to('projects/view/'.$project,'error',lang('operation_failed'));
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect('projects/view/'.$project);
			}
	}

	function preview(){
        if (!$this->input->post()) {
            $file_id = $this->uri->segment(4);
            $project_id = $this->uri->segment(5);
            $file =  Project::view_file($file_id);

            $fullpath = 'assets/project-files/'.$file->path.$file->file_name;
            if($file->path == NULL)
                $fullpath = 'assets/project-files/'.$file->file_name;

            if ($file)
            {
                if(file_exists($fullpath)){
                    $data['file'] = $file;
                    $data['file_path'] = $fullpath;
                    $data['project_id'] = $project_id;
                    $data['action'] = 'preview_file';
                    $this->load->view('modal/file_action', $data);
                }else{
                	// $this->session->set_flashdata('response_status','error');
                    // $this->session->set_flashdata('message',lang('operation_failed'));
                    $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                    redirect('projects/view/'.$project_id);
                }
            }
            else
            {
                // $this->session->set_flashdata('response_status','error');
                // $this->session->set_flashdata('message',lang('operation_failed'));
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect('projects/view/'.$project_id);
            }
        }
    }

	function delete()
	{
		if ($this->input->post()) {

		$project_id = $this->input->post('project', TRUE);
		$file_id = $this->input->post('file', TRUE);

		$file =  Project::view_file($file_id);

        $fullpath = './assets/project-files/'.$file->path.$file->file_name;
    	if($file->path == NULL)
        $fullpath = './assets/project-files/'.$file->file_name;

        if(file_exists($fullpath)) unlink($fullpath);

		App::delete('files',array('file_id' => $file_id));

		// Log activity
                    $data = array(
                        'module' => 'files',
                        'module_field_id' => $project_id,
                        'user' => User::get_id(),
                        'activity' => 'activity_deleted_a_file',
                        'icon' => 'fa-times',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $file->file_name,
                        'value2' => ''
                        );
                    App::Log($data);
                    // Applib::go_to('projects/view/'.$project_id.'/?group=files','success',lang('file_deleted'));
                    $this->session->set_flashdata('tokbox_success', lang('file_deleted'));
                    redirect('projects/view/'.$project_id.'/?group=files');

		}else{
			if(isset($_GET['id'])){
				$data['file_id'] = $_GET['id'];
				$data['project_id'] = $this->uri->segment(4);
                $data['action'] = 'delete_file';
				$this->load->view('modal/file_action',$data);
			}

		}
	}



	function _upload_notification($project){
		$this->load->model('Client');

		$message = App::email_template('project_file','template_body');
        $subject = App::email_template('project_file','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Project::by_id($project);

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

		$uploaded_by = str_replace("{UPLOADED_BY}",User::displayName(User::get_id()),$logo);
		$title = str_replace("{PROJECT_TITLE}",$info->project_title,$uploaded_by);
		$project_url = str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project.'/?group=files',$title);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$project_url);
		$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

		$data['message'] = $message;
		$message = $this->load->view('email_template', $data, TRUE);

		if(User::is_client()){ // Send email to Team
			foreach (Project::project_team($project) as $key => $u) {
					$params['recipient'] = User::login_info($u->assigned_user)->email;
					$params['subject'] = '['.config_item('company_name').']'.' '.$subject;
					$params['message'] = $message;
					$params['attached_file'] = '';
					modules::run('fomailer/send_email',$params);
			}
		}else{ // Send email to client primary contact
			$contact = Client::view_by_id($info->client)->primary_contact;
			if($contact > 0){
					$params['recipient'] = User::login_info($contact)->email;
					$params['subject'] = '['.config_item('company_name').']'.' '.$subject;
					$params['message'] = $message;
					$params['attached_file'] = '';
					modules::run('fomailer/send_email',$params);
			}

		}

	}

}

/* End of file files.php */
