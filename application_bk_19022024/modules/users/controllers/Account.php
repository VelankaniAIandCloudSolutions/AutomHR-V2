<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->helper('security');
		$this->load->model(array('Client','App','Lead'));
		$this->load->model('Users_details','usersdetails');
	}
	function index(){
		$this->active();
	}

	function active()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('users').' - '.config_item('company_name'));
	$data['page'] = lang('users');
	$data['datatables'] = TRUE;
	$data['form'] = TRUE;
	$data['companies'] = Client::get_all_clients();
	
	$this->template
	->set_layout('users')
	->build('users',isset($data) ? $data : NULL);
	}

	function account_list(){
	    $list = $this->usersdetails->admin_client_users();
        $data = array();
    
        $no = $_POST['start'];
        foreach ($list as $key => $user) {
        	$info = User::profile_info($user['id']);
        	$role_details = $this->db->get_where('roles',array('default'=>$user['role_id']))->row_array();
            $no++;
            $row = array();
            $row[] = '<td class="sorting_1">
            		  <span class="avatar">'.ucfirst($user['fullname'][0]).'</span>
            		  <h2>'.ucfirst($user['fullname']).' <span>'.ucfirst($user['company_name']).'</span></h2>
            		  </td>';
			$row[] = '<td>'.$user['email'].'</td>';
			$row[] = '<td>'.ucfirst($user['company_name']).'</td>';
			$row[] = '<td>'.date('d M Y',strtotime($user['created'])).'</td>';
						$roleby = ($user['role_id'] == 1)?'Admin':'Client';
						$borderby = ($user['role_id'] == 1)?'danger':'info';
			$row[] = '<td><span class="label label-'.$borderby.'-border">'.ucfirst($role_details['role']).'</span></td>';
			$html =  '<td class="text-right">
						<div class="dropdown text-right">
						<a href="javascript:void(0)" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
							<ul class="dropdown-menu pull-right">';
														
			// $html .='<li><a href="'.base_url().'users/account/auth/'.$user['user_id'].'" data-toggle="ajaxModal" title="'.lang("user_edit_login").'"><i class="fa fa-lock"></i> '.lang("user_edit_login").'</a></li>';

			 if($user['role_id'] == '3') { 

				$html .=  '<li><a href="'.base_url().'users/account/permissions/'.$user['user_id'].'"  data-toggle="ajaxModal" title="'.lang("staff_permissions").'"><i class="fa fa-shield"></i> '.lang("staff_permissions").'</a></li>';
			} 
				$html .='<li><a href="'.base_url().'users/account/update/'.$user['user_id'].'" data-toggle="ajaxModal" title="'.lang("edit").'">
									<i class="fa fa-edit"></i> '.lang("edit").'</a></li>';

			if ($user['id'] != User::get_id()) { 
			
				$class = ($user['banned'] == "1") ? "danger": "default";
			
			$html .='<li><a href="'.base_url().'users/account/ban/'.$user['user_id'].'" class="btn-'.$class.'" data-toggle="ajaxModal" title="'.lang("ban_user").'"><i class="fa fa-times-circle-o"></i> '.lang("ban_user").'</a></li>';

			$html .='<li><a href="'.base_url().'users/account/delete/'.$user['user_id'].'" data-toggle="ajaxModal" title="'.lang("delete").'"><i class="fa fa-trash-o"></i> '.lang("delete").'</a></li>';
			
			} 
			$html .='</ul></div></td>';

			$row[] = $html;
            //add html for action
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->usersdetails->admin_client_count_all(),
            "recordsFiltered" => $this->usersdetails->admin_client_count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        die();
	}

	function permissions()
	{

		if ($_POST) {
			 $permissions = json_encode($_POST);
			 $data = array('allowed_modules' => $permissions);
			 App::update('account_details',array('user_id' => $_POST['user_id']),$data);

			 // $this->session->set_flashdata('response_status', 'success');
			 // $this->session->set_flashdata('message', lang('settings_updated_successfully'));
			 $this->session->set_flashdata('tokbox_success', lang('settings_updated_successfully'));
			redirect(base_url().'users/account');

		}else{
			$staff_id = $this->uri->segment(4);

			if (User::login_info($staff_id)->role_id != '3') {
				// $this->session->set_flashdata('response_status', 'error');
			 	// $this->session->set_flashdata('message', lang('operation_failed'));
			 	$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
			 	redirect($_SERVER['HTTP_REFERRER']);
			}
			$data['user_id'] = $staff_id;
			$this->load->view('modal/edit_permissions',isset($data) ? $data : NULL);
		}


	}


	function update()
	{
		if ($this->input->post()) {
			if (config_item('demo_mode') == 'TRUE') {
			// $this->session->set_flashdata('response_status', 'error');
			// $this->session->set_flashdata('message', lang('demo_warning'));
			$this->session->set_flashdata('tokbox_error', lang('demo_warning'));
			redirect('users/account');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');

		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('operation_failed'));
				$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
				redirect('users/account');
		}else{
			$user_id =  $this->input->post('user_id');
			$profile_data = array(
			                'fullname' => $this->input->post('fullname'),
                            'company' => $this->input->post('company'),
			                'phone' => $this->input->post('phone'),
			                'mobile' => $this->input->post('mobile'),
			                'skype' => $this->input->post('skype'),
			                'language' => $this->input->post('language'),
			                'locale' => $this->input->post('locale'),
			                'hourly_rate' => $this->input->post('hourly_rate')
			            );
			if (isset($_POST['department'])) {
				$profile_data['department'] = json_encode($_POST['department']);
			}
			App::update('account_details',array('user_id'=>$user_id),$profile_data);

			$data = array(
				'module' => 'users',
				'module_field_id' => $user_id,
				'user' => User::get_id(),
				'activity' => 'activity_updated_system_user',
				'icon' => 'fa-edit',
				'branch_id' => $this->session->userdata('branch_id'),
				'value1' => User::displayName($user_id),
				'value2' => ''
				);
			App::Log($data);

			// $this->session->set_flashdata('response_status', 'success');
			// $this->session->set_flashdata('message', lang('user_edited_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('user_edited_successfully'));
			redirect('users/account');
		}
		}else{
		$data['id'] = $this->uri->segment(4);
		$this->load->view('modal/edit_user',$data);
		}
	}


	function ban()
	{

		if ($_POST) {
			$user_id = $this->input->post('user_id');
			$ban_reason = $this->input->post('ban_reason');
			$action = (User::login_info($user_id)->banned == '1') ? '0' : '1';

			 $data = array('banned' => $action,'ban_reason' => $ban_reason);
			 App::update('users',array('id' => $user_id),$data);

			 // $this->session->set_flashdata('response_status', 'success');
			 // $this->session->set_flashdata('message', lang('settings_updated_successfully'));
			 $this->session->set_flashdata('tokbox_success', lang('settings_updated_successfully'));

			redirect(base_url().'users/account');

		}else{
			$user_id = $this->uri->segment(4);
			$data['user_id'] = User::login_info($user_id)->id;
			$data['username'] = User::login_info($user_id)->username;
			$this->load->view('modal/ban_user',isset($data) ? $data : NULL);
		}


	}



	function auth()
	{
		if ($this->input->post()) {
			Applib::is_demo();

		$user_password = $this->input->post('password');
		$username = $this->input->post('username');
		$this->config->load('tank_auth',TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('username', 'User Name', 'required|trim|xss_clean');

		if(!empty($user_password)) {
                $this->form_validation->set_rules('password', 'Password', "trim|required|xss_clean|min_length[4]|max_length[32]");
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
        }

		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('operation_failed'));
				$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
				redirect('users/account');
		}else{
                        date_default_timezone_set(config_item('timezone'));
			$user_id =  $this->input->post('user_id');
			$args = array(
			                'email' 	=> $this->input->post('email'),
			                'role_id' 	=> $this->input->post('role_id'),
			                'modified' 	=> date("Y-m-d H:i:s")
			            );

			$db_debug = $this->db->db_debug; //save setting
			$this->db->db_debug = FALSE; //disable debugging for queries
			$result = $this->db->set('username',$username)
							   ->where('id',$user_id)
							   ->update('users'); //run query
			$this->db->db_debug = $db_debug; //restore setting

			if(!$result){
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('username_not_available'));
				$this->session->set_flashdata('tokbox_error', lang('username_not_available'));
				redirect('users/account');
			}

			App::update('users',array('id' => $user_id), $args);

			if(!empty($user_password)) {
                $this->tank_auth->set_new_password($user_id,$user_password);
            }

            $data = array(
				'module' => 'users',
				'module_field_id' => $user_id,
				'user' => User::get_id(),
				'activity' => 'activity_updated_system_user',
				'icon' => 'fa-edit',
				'branch_id' => $this->session->userdata('branch_id'),
				'value1' => User::displayName($user_id),
				'value2' => ''
				);
			App::Log($data);

			// $this->session->set_flashdata('response_status', 'success');
			// $this->session->set_flashdata('message', lang('user_edited_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('user_edited_successfully'));
			redirect('users/account');
		}
		}else{
		$data['id'] = $this->uri->segment(4);
		$this->load->view('modal/edit_login',$data);
		}
	}




	function delete()
	{
		if ($this->input->post()) {

		Applib::is_demo();

		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id', 'User ID', 'required');
		if ($this->form_validation->run() == FALSE)
		{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('delete_failed'));
				$this->session->set_flashdata('tokbox_error', lang('delete_failed'));
				$this->input->post('r_url');
		}else{
			$user = $this->input->post('user_id',TRUE);
			$deleted_user = User::displayName($user);

			if (User::profile_info($user)->avatar != 'default_avatar.jpg') {
				if(is_file('./assets/avatar/'.User::profile_info($user)->avatar))
				unlink('./assets/avatar/'.User::profile_info($user)->avatar);
			}
			$user_companies = App::get_by_where('companies',array('primary_contact' => $user));
			foreach ($user_companies as $co) {
				$ar = array('primary_contact' => '');
				App::update('companies',array('primary_contact' => $user),$ar);
			}
			$user_tickets = App::get_by_where('tickets',array('reporter' => $user));
			foreach ($user_tickets as $ticket) {
				App::delete('tickets',array('reporter' => $user));
			}
			$user_bugs = App::get_by_where('bugs',array('reporter' => $user));
			foreach ($user_bugs as $bug) {
				App::delete('bugs',array('reporter' => $user));
			}
			$user_comments = App::get_by_where('comments',array('posted_by' => $user));

			foreach ($user_comments as $comment) {
				$replies = App::get_by_where('comment_replies',array('parent_comment' => $comment->comment_id));
				foreach ($replies as $key => $r) {
					App::delete('comment_replies',array('parent_comment' => $comment->comment_id));
				}

			}

			App::delete('comments', array('posted_by' => $user));
			App::delete('messages', array('user_to' => $user));
			App::delete('assign_tasks', array('assigned_user' => $user));
			App::delete('assign_projects', array('assigned_user' => $user));
			App::delete('activities', array('user' => $user));

			App::delete('account_details', array('user_id' => $user));
			App::delete('users', array('id' => $user));

			// Log activity
			$data = array(
				'module' => 'users',
				'module_field_id' => $user,
				'user' => User::get_id(),
				'activity' => 'activity_deleted_system_user',
				'icon' => 'fa-trash-o',
				'branch_id' => $this->session->userdata('branch_id'),
				'value1' => $deleted_user,
				'value2' => ''
				);
			App::Log($data);

			// Applib::make_flashdata(array(
			// 		'response_status' => 'success',
			// 		'message' => lang('user_deleted_successfully')
			// 		));
			$this->session->set_flashdata('tokbox_success', lang('user_deleted_successfully'));
			redirect($_SERVER['HTTP_REFERER']);
		}
		}else{
			$data['user_id'] = $this->uri->segment(4);
			$this->load->view('modal/delete_user',$data);
		}
	}
}

/* End of file account.php */
