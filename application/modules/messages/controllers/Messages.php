<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','tank_auth'));
		$this->group = isset($_GET['group']) ? $_GET['group'] : 'inbox';

		$this->load->model(array('Message','App'));
		App::module_access('menu_messages');

		$this->applib->set_locale();
	}

	function index()
	{

	$this->template->title(lang('messages').' - '.config_item('company_name'));
	$data['page'] = lang('messages');
	$data['group'] = $this->group;
	switch ($data['group']) {
		case 'inbox':
			$data['messages'] = Message::get_inbox(User::get_id());
			break;
		case 'sent':
			$data['messages'] = Message::get_sent(User::get_id());
				break;
		case 'favourites':
			$data['messages'] = Message::get_favourite(User::get_id());
				break;
		case 'trash':
			$data['messages'] = Message::get_deleted(User::get_id());
				break;
	}
	$this->template
	->set_layout('users')
	->build('messages',isset($data) ? $data : NULL);
	}


	function send()
	{
		if ($this->input->post()) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('user_to[]', 'User To', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if ($this->form_validation->run() == FALSE)
		{

			// Applib::go_to('messages/send/?group=sent','error',lang('error_in_form'));
			$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
			redirect('messages/send/?group=sent');
			// Applib::make_flashdata(array(
			// 	'response_status' => 'error',
			// 	'message' => lang('message_not_sent'),
			// 	'form_error' => validation_errors()
			// 	));
			// redirect($_SERVER['HTTP_REFERER']);

		}else{
			$message = $this->input->post('message');
			$user_to = $this->input->post('user_to', TRUE);

			foreach ($user_to as $key => $user) {

			if($user != User::get_id()){

				$form_data = array('user_to' => $user,'user_from' => User::get_id(),'message' => $message);
				date_default_timezone_set('UTC');
				$form_data['date_received'] = date('Y-m-d H:i:s');
				App::save_data('messages',$form_data);

				if (config_item('notify_message_received') == 'TRUE') {
					$this->_message_notification($user, $message);
				}
			}

			}

				// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message',lang('message_sent'));
				$this->session->set_flashdata('tokbox_success', lang('message_sent'));
				redirect($_SERVER['HTTP_REFERER']);
			}

		}else{
				$this->template->title(lang('messages').' - '.config_item('company_name'));
				$data['page'] = lang('messages');
				$data['form'] = TRUE;
				$data['editor'] = TRUE;
				$data['group'] = $this->group;

				$this->template
				->set_layout('users')
				->build('send_message',isset($data) ? $data : NULL);
		}
	}

	function view($user_from = NULL)
	{

		$this->template->title(lang('messages').' - '.config_item('company_name'));
		$data['page'] = lang('messages');
		$data['editor'] = TRUE;
		$data['user_from'] = $user_from;
		$data['group'] = $this->group;

		$this->_set_read($user_from);

		$this->template
		->set_layout('users')
		->build('conversations',isset($data) ? $data : NULL);
	}

	function favourite($msg = NULL){
		$status = Message::view_message($msg)->favourite;
		$status = ($status == '1') ? 0 : 1;
		$response = ($status == '1') ? 'Message favourited' : 'Removed from favourites';
		$data = array('favourite' => $status);

		App::update('messages',array('msg_id' => $msg),$data);

		// $this->session->set_flashdata('response_status', 'success');
		// $this->session->set_flashdata('message', $response);
		$this->session->set_flashdata('tokbox_success', $response);
		redirect($_SERVER['HTTP_REFERER']);
	}

	function restore($msg = NULL){
		$data = array('deleted' => 'No');
		App::update('messages',array('msg_id' => $msg),$data);

		// $this->session->set_flashdata('response_status', 'success');
		// $this->session->set_flashdata('message', 'Message restored');
		$this->session->set_flashdata('tokbox_success', 'Message restored');
		
		redirect($_SERVER['HTTP_REFERER']);
	}

	function remove($msg = NULL){
		App::delete('messages',array('msg_id' => $msg));

		// $this->session->set_flashdata('response_status', 'success');
		// $this->session->set_flashdata('message', 'Message deleted');
		$this->session->set_flashdata('tokbox_success', 'Message deleted');
		redirect($_SERVER['HTTP_REFERER']);
	}


	function _set_read($user_from){
			$this->db->set('status', 'Read');
			$this->db->where('user_to',User::get_id());
			$this->db->where('user_from',$user_from)->update('messages');
	}


	function _message_notification($user_to,$message){
			$message = strip_tags($message);
			$email_message = App::email_template('message_received','template_body');
			$subject = App::email_template('message_received','subject');
			$signature = App::email_template('email_signature','template_body');


			$recipient = User::login_info($user_to)->email;

			$logo_link = create_email_logo();

	        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$email_message);

			$email_recipient = str_replace("{RECIPIENT}",$recipient,$logo);
			$sender = str_replace("{SENDER}",User::displayName(User::get_id()),$email_recipient);
			$site_url = str_replace("{SITE_URL}",base_url().'messages',$sender);
			$msg = str_replace("{MESSAGE}",$message,$site_url);
	        $EmailSignature = str_replace("{SIGNATURE}",$signature,$msg);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$params['recipient'] = $recipient;
			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
			modules::run('fomailer/send_email',$params);
	}


	function delete($msg_id = NULL)
	{
		if ($this->input->post()) {
				$this->load->library('form_validation');
				$this->form_validation->set_rules('msg_id', 'Msg ID', 'required');

				$msg_id = $this->input->post('msg_id', TRUE);

				if ($this->form_validation->run() == FALSE)
				{
						// $this->session->set_flashdata('response_status', 'error');
						// $this->session->set_flashdata('message', lang('delete_failed'));
						$this->session->set_flashdata('tokbox_success', lang('delete_failed'));
						redirect($_SERVER['HTTP_REFERER']);
				}else{
					$data = array('deleted' => 'Yes');
					App::update('messages',array('msg_id'=>$msg_id),$data);

					// $this->session->set_flashdata('response_status', 'success');
					// $this->session->set_flashdata('message', lang('message_deleted_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('message_deleted_successfully'));
					redirect($_SERVER['HTTP_REFERER']);
				}
		}else{
			$data['msg_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete_message',$data);
		}
	}

	function search()
	{
		if ($this->input->post()) {
				$data['page'] = lang('messages');
				$data['group'] = 'inbox';
				$keyword = $this->input->post('keyword', TRUE);
				$data['messages'] = Message::search_message($keyword);
				$data['users'] = Message::group_messages_by_users(User::get_id());
				$this->template
				->set_layout('users')
				->build('messages',isset($data) ? $data : NULL);

		}else{
			redirect('messages');
		}

	}
}

/* End of file messages.php */
