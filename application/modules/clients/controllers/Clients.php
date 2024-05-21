<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();
		
		$this->load->model(array('Project','App','Welcome','Client','Ticket','Invoice'));

		if (User::is_admin()) {
			redirect('welcome');
		}
		if (User::is_staff()) {
			redirect('collaborator');
		}

		$this->applib->set_locale();
	}

	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('welcome').' - '.config_item('company_name'));
	$data['page'] = lang('home');
	$this->template
	->set_layout('users')
	->build('welcome',isset($data) ? $data : NULL);
	}
}

/* End of file clients.php */