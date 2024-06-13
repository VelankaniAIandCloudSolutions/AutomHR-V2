<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Links extends MX_Controller {

	function __construct()
	{
		parent::__construct();
        User::logged_in();

		$this->load->module('layouts');	
		$this->load->helper('cookie');	
		$this->load->library(array('template','form_validation'));
		$this->load->model(array('Project','App'));
		$this->template->title(lang('projects'));
		
        $this->applib->set_locale();
	}

	function add()
	{
		if ($this->input->post()) { 

		$this->form_validation->set_rules('link_url', 'Link URL', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			Applib::make_flashdata(array(
				'response_status' => 'error',
				'message' => lang('operation_failed'),
				'form_error' => validation_errors()
				));
			redirect($_SERVER['HTTP_REFERER']);

		}else{
			$project_id = $this->input->post('project_id',TRUE);

			if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)) {
			
			$link_url = $_POST['link_url'];
                        if (substr($link_url, 0, 4) != 'http') {
                            $link_url = "http://".$link_url;
                        }
                        
                        if (empty($_POST['link_title'])) {
                            $meta = $this->_get_url_meta($link_url);
                            if (!empty($meta['title'])) { 
                            	$_POST['link_title'] = $meta['title']; 
                            } else { 
                            	$_POST['link_title'] = lang('link_no_title'); 
                            }
                            if (!empty($meta['description'])) { 
                            	$_POST['description'] = $meta['description']; 
                            } else { 
                            	$_POST['description'] = ''; 
                            }
                        }
                        App::save_data('links',$this->input->post());

                        // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $project_title = Project::by_id($project_id)->project_title;
                Applib::slack(
                          sprintf(lang('slack_new_link_msg'), $_POST['link_url']),
                          sprintf(lang('slack_new_link'), $project_title),
                          base_url().'projects/view/'.$project_id.'?group=links',
                          sprintf(lang('slack_new_link_by'), User::displayName(User::get_id()))
                          );
            }

            // Log activity
            $data = array(
                'module' => 'links',
                'module_field_id' => $project_id,
                'user' => User::get_id(),
                'activity' => 'activity_added_new_link',
                'icon' => 'fa-laptop',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $_POST['link_url'],
                'value2' => ''
                );
            App::Log($data);
            // Applib::go_to('projects/view/'.$project_id.'?group=links','success',lang('link_added_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('link_added_successfully'));
            redirect('projects/view/'.$project_id.'?group=links');
			}


			}
		}else{
			$data['project_id'] = $this->uri->segment(4);
			$data['action'] = 'add_link';
			$this->load->view('modal/link_action',isset($data) ? $data : NULL);
		}
	}

	function edit()
	{
		if ($this->input->post()) {

		$this->form_validation->set_rules('link_url', 'Link URL', 'required');

		if ($this->form_validation->run() == FALSE)
		{
            $_POST = '';
			// $this->session->set_flashdata('response_status', 'error');
			// $this->session->set_flashdata('message', lang('operation_failed'));
            $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
			$this->edit();
		}else{
			$project_id = $this->input->post('project_id',TRUE);
			if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)) {
			$link_id = $_POST['link_id'];

			App::update('links',array('link_id'=>$link_id),$this->input->post());

			// Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $project_title = Project::by_id($project_id)->project_title;
                Applib::slack(
                          sprintf(lang('slack_link_updated_msg'), $_POST['link_url']),
                          sprintf(lang('slack_link_updated'), $project_title),
                          base_url().'projects/view/'.$project.'?group=links',
                          sprintf(lang('slack_link_updated_by'), User::displayName(User::get_id()))
                          );
            }

            // Log activity
            $data = array(
                'module' => 'links',
                'module_field_id' => $project_id,
                'user' => User::get_id(),
                'activity' => 'activity_edited_link',
                'icon' => 'fa-laptop',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => Project::view_link($link_id)->link_title,
                'value2' => ''
                );
            App::Log($data);

			// Applib::go_to('projects/view/'.$project_id.'/?group=links&view=link&id='.$link_id,'success',lang('link_edited_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('link_edited_successfully'));
            redirect('projects/view/'.$project_id.'/?group=links&view=link&id='.$link_id);
			}
		}
		}else{
			$link_id = $this->uri->segment(4);
			$data['link'] = Project::view_link($link_id);
			$data['action'] = 'edit_link';
			$this->load->view('modal/link_action',isset($data) ? $data : NULL);
		}
	}
        
	/*function pin()
	{
            if (User::is_admin()) {
				$project_id = $this->uri->segment(4);
                $link_id = $this->uri->segment(5);
                $client = Project::by_id($project_id)->client;

                $link_client = Project::view_link($link_id)->client;

                if ($link_client > 0) {
                    App::update('links',array('link_id'=>$link_id),array('client'=>'0'));
                    $message = 'link_unpinned_successfully';
                } else {
                    App::update('links',array('link_id'=>$link_id),array('client'=>$client));
                    $message = 'link_pinned_successfully';
                }
                
                $data['project_id'] = $project_id;
                $data['link_id'] = $link_id;
                
            }
            if(isset($_SERVER['HTTP_REFERER']))
                {
                    $redirect_to = str_replace(base_url(),'',$_SERVER['HTTP_REFERER']);
                }
                else
                {
                    $redirect_to = $this->uri->uri_string();
                }            
                Applib::go_to($redirect_to,'success',lang($message));
	}*/

	function delete()
	{
		if ($this->input->post()) {

		$this->form_validation->set_rules('project_id', 'Project ID', 'required');

		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('delete_failed'));
                $this->session->set_flashdata('tokbox_error', lang('delete_failed'));
				redirect('projects');
		}else{	
			$project_id = $this->input->post('project_id');
			$link_id = $this->input->post('link_id');

			App::delete('links', array('link_id' => $link_id)); 

			// Log activity
            $data = array(
                'module' => 'links',
                'module_field_id' => $project_id,
                'user' => User::get_id(),
                'activity' => 'activity_deleted_a_link',
                'icon' => 'fa-trash-o',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => Project::view_link($link_id)->link_title,
                'value2' => ''
                );
            App::Log($data);

			// Applib::go_to('projects/view/'.$project_id.'?group=links','success',lang('link_deleted_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('link_deleted_successfully'));
            redirect('projects/view/'.$project_id.'/?group=links');
		}
		}else{
			$data['project_id'] = $this->uri->segment(4);
			$data['link_id'] = $this->uri->segment(5);
			$data['action'] = 'delete_link';
			$this->load->view('modal/link_action',$data);
		}
	}
        
        function _get_url_meta($url) 
        {
            if (intval($url) > 0) $url = Project::view_link($url)->link_url;
            $data = file_get_contents($url);
            $meta = get_meta_tags($url);
            $meta['title'] = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : null;
            return $meta;
        }
        
}

/* End of file links.php */