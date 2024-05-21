<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','form_validation','encrypt'));
		$this->template->title(lang('tickets').' - '.config_item('company_name'));

		$this->load->model(array('Ticket','App'));
		
		if(!App::is_access('menu_tickets'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		} 
		
		
		//App::module_access('menu_tickets');

		$archive = FALSE;
		if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }

		$this->filter_by = $this->_filter_by();
	}

	function index()
	{
		$archive = FALSE;
		if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }
		$data = array(
			'page' => lang('tickets'),
			'datatables' => TRUE,
			'datepicker' => TRUE,
			'archive' => $archive,
			'tickets' => $this->_ticket_list($archive),
			'my_tickets' => $this->my_ticket_list($archive)
		);
		
		// echo '<pre>';print_r($data);exit;
		$data['form'] =true;
		$this->template
		->set_layout('users')
		->build('tickets',isset($data) ? $data : NULL);
	}

	function _filter_by(){

		$filter = isset($_GET['view']) ? $_GET['view'] : '';

		return $filter;
	}


	function _ticket_list($archive = NULL){
				$this->session->unset_userdata('search_ticket_priority');
				$this->session->unset_userdata('search_ticket_from_date');
				$this->session->unset_userdata('search_ticket_to_date');
			if($_POST){
				$this->session->set_userdata('search_ticket_priority',$_POST['ticked_priority']);
				$this->session->set_userdata('search_ticket_from_date',$_POST['ticket_from']);
				$this->session->set_userdata('search_ticket_to_date',$_POST['ticket_to']);
			}
			if (User::is_admin()) {
				return $this->_admin_tickets($archive,$this->filter_by);
			}elseif (User::is_staff()) {
				
				return $this->_staff_tickets($archive,$this->filter_by);
			}else{
				
				return $this->_client_tickets($archive,$this->filter_by);
			}
	}

	function my_ticket_list($archive = NULL){
		$this->session->unset_userdata('search_ticket_priority');
		$this->session->unset_userdata('search_ticket_from_date');
		$this->session->unset_userdata('search_ticket_to_date');
		if($_POST){
			$this->session->set_userdata('search_ticket_priority',$_POST['ticked_priority']);
			$this->session->set_userdata('search_ticket_from_date',$_POST['ticket_from']);
			$this->session->set_userdata('search_ticket_to_date',$_POST['ticket_to']);
		}
		return $this->get_my_ticket_details($archive,$this->filter_by);
	}
	
	function GetAssignee(){
		$department_id=$_POST['department'];
		$users = $this->db->select('U.id,AD.fullname')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1)->where('U.department_id',$department_id)->order_by('AD.fullname','asc')->get()->result_array();
		// $html='<select class="select2-option form-control" style="width:100%;" name="assignee" id="assignee" >';
		$html='';
		if(!empty($users))	{
			foreach ($users as $user1){
				$html.='<option value="'.$user1["id"].'">'.$user1['fullname'].'</option>';
			}
		}
		
		echo $html;exit;

	}
	
	

	function get_my_ticket_details($archive = FALSE, $filter_by = NULL) {
		if($_POST){
			if(!empty($_POST['ticked_priority']))
			$this->db->where('priority',$_POST['ticked_priority']);
			if(!empty($_POST['ticket_from']))
			$this->db->where('created>=',date('Y-m-d',strtotime($_POST['ticket_from'])));
			if(!empty($_POST['ticket_to']))
			$this->db->where('created<=',date('Y-m-d',strtotime($_POST['ticket_to'])));
			$this->db->where('created_by',User::get_id());
			if(!empty($filter_by))
			$this->db->where('status',$filter_by);
			$this->db->from('dgt_tickets');
			return $this->db->get()->result();
		}
		if($filter_by == NULL){
			if($archive){
				return Ticket::by_where(array('created_by'=>User::get_id(),'archived_t'=>'1'));
			}else{
				return Ticket::by_where(array('created_by'=>User::get_id(),'archived_t !='=>'1'));
			}
		}else{
			switch ($filter_by) {
				case 'open':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','created_by'=>User::get_id()));
	
				break;
				case 'closed':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','created_by'=>User::get_id()));
	
				break;
				case 'pending':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','created_by'=>User::get_id()));
	
				break;
				case 'resolved':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','created_by'=>User::get_id()));
	
				break;

				case 'inprogress':
					return Ticket::by_where(array('archived_t !='=>'1','status'=>'inprogress','created_by'=>User::get_id()));
					break;
	
				default:
				return Ticket::by_where(array('archived_t !='=>'1','created_by'=>User::get_id()));
				break;
			}
		}
	}

	function view($id = NULL)
	{

		// if(!User::can_view_ticket(User::get_id(),$id)){ App::access_denied('tickets'); }

		$data['page'] = lang('tickets');
		$data['editor'] = TRUE;
		$data['id'] = $id;
		$data['tickets'] = $this->_ticket_list(); // GET a list of the Tickets

		$this->template
		->set_layout('users')
		->build('view',isset($data) ? $data : NULL);
	}



	function add()
	{
		if ($this->input->post()) {
			// echo '<pre>';print_r($_POST['subject']);exit;
			// if (isset($_POST['dept'])) {
			// 	// Applib::go_to('tickets/add/?dept='.$_POST['dept'],'success','Department selected');
			// 	$this->session->set_flashdata('tokbox_success', 'Department selected');
			// 	redirect('tickets/add/?dept='.$_POST['dept']);
			// }

			$this->form_validation->set_rules('department', 'department', 'required');
			$this->form_validation->set_rules('ticket_code', 'Ticket Code', 'required');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				Applib::make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('operation_failed'),
					'form_error'=> validation_errors()
				));

				redirect($_SERVER['HTTP_REFERER']);
			}else{
				
				
                date_default_timezone_set(config_item('timezone'));
				$attachment = '';
				if($_FILES['ticketfiles']['tmp_name'][0]){
					$attachment = $this->_upload_attachment($_POST);
				}
				
				// check additional fields
				$additional_fields = array();
				$additional_data = $this->db->where(array('deptid'=>$_POST['department']))
				->get('fields')
				->result_array();
				if (is_array($additional_data))
				foreach ($additional_data as $additional)
				{
					// We create these vales as an array
					$name = $additional['uniqid'];
					$additional_fields[$name] = $this->encrypt->encode($this->input->post($name));
				}
				$subject = $this->input->post('subject',true);
				$code = $this->input->post('ticket_code',true);

				$_POST['real_subject'] = $subject;

				$_POST['subject'] = '['.$code.'] : '.$subject;
				$time_estimation = $this->db->where('id',$_POST['priority'])->get('priorities')->row();
				$insert = array(
					'subject' => $_POST['subject'],
					'ticket_code' => $code,
					'department' => $_POST['department'],
					'priority' => $_POST['priority'],
					// 'time_estimation' => $time_estimation->hour,
					'body' => $this->input->post('body'),
					'status' => 'open',
					'created' => date("Y-m-d H:i:s",time()),
					'created_by' => User::get_id(),
					'assignee' => $_POST['assignee']
				);

				if (is_array($additional_fields)){
					$insert['additional'] = json_encode($additional_fields);
				}

				if (isset($attachment)){
					$insert['attachment'] = $attachment;
				}
				if (!User::is_admin()) {
					// $insert['reporter'] = User::get_id();
					// $_POST['reporter'] = User::get_id();
				}else{
					// $insert['reporter'] = $_POST['reporter'];
					$insert['reporter'] = User::get_id();

				}



				if($ticket_id = Ticket::save_data('tickets',$insert)){

					// Send email to Staff
					$this->_send_email_to_staff($ticket_id);
					// Send email to Client
					// $this->_send_email_to_client($ticket_id);


					// Post to slack channel
		            if(config_item('slack_notification') == 'TRUE'){
		            	$this->load->helper('slack');
		            	$slack = new Slack_Post;
		                $slack->slack_create_ticket($ticket_id,$insert['reporter']);
		            }
		            
		            $data = array(
						'module' => 'tickets/view/'.$ticket_id,
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_created',
						'icon' => 'fa-ticket',
						'value1' => $subject,
						'value2' => ''
						);
					App::Log($data);
					if($_POST['reporter'] != 0) {
						$data = array(
							'module' => 'tickets',
							'module_field_id' => $ticket_id,
							'user' => $_POST['reporter'],
							'activity' => 'activity_ticket_created',
							'icon' => 'fa-ticket',
							'value1' => $subject,
							'value2' => ''
							);
						App::Log($data);
					}
					 
					// Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_created_successfully'));
					$this->session->set_flashdata('tokbox_success', "Ticket Created Successfully");
					redirect('tickets/view/'.$ticket_id);
				}


			}
		}else{

			$data = array(
				'page' 		 => lang('tickets'),
				'datepicker' => TRUE,
				'form'		 => TRUE,
				'editor'	 => TRUE,
				'tickets'	 => $this->_ticket_list()
			);

			$this->template
			->set_layout('users')
			->build('create_ticket',isset($data) ? $data : NULL);

		}
	}





	function edit($id = NULL)
	{

		if ($this->input->post()) {
			$ticket_id = $this->input->post('id', TRUE);

			$this->form_validation->set_rules('ticket_code', 'Ticket Code', 'required');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				Applib::make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('error_in_form'),
					'form_error'=> validation_errors()
				));

				redirect($_SERVER['HTTP_REFERER']);
			}else{

				if($_FILES['ticketfiles']['tmp_name'][0]){
					$attachment = $this->_upload_attachment($this->input->post());
				}

				if (isset($attachment)){
					$_POST['attachment'] = $attachment;
				}
				$time_estimation = $this->db->where('id',$_POST['priority'])->get('priorities')->row();
				// $_POST['time_estimation'] = $time_estimation->hour;
				Ticket::update_data('tickets',array('id'=>$ticket_id),$this->input->post());

				 $data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_edited',
						'icon' => 'fa-pencil',
						'value1' => $this->input->post('subject',TRUE),
						'value2' => ''
						);
					App::Log($data);
					// Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_edited_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('ticket_edited_successfully'));
					redirect('tickets/view/'.$ticket_id);

			}
		}else{
			// if(!User::can_view_ticket(User::get_id(),$id)){ App::access_denied('tickets'); }
			$data = array(
				'page'		 	 => lang('tickets'),
				'datepicker' => TRUE,
				'form'		 => TRUE,
				'editor'	 => TRUE,
				'tickets'	 	 => $this->_ticket_list(),
				'id' 			 => $id
			);

			$this->template
			->set_layout('users')
			->build('edit_ticket',isset($data) ? $data : NULL);

		}
	}


	function quick_edit(){
		if($this->input->post()){
			$ticket_id = $this->input->post('id',TRUE);
			$data = array('reporter' 	=> $this->input->post('reporter', TRUE),
						  'department'	=> $this->input->post('department', TRUE),
						  'priority'	=> $this->input->post('priority', TRUE),
						  );
			Ticket::update_data('tickets',array('id'=>$ticket_id),$data);


			// Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_edited_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('ticket_edited_successfully'));
					redirect('tickets/view/'.$ticket_id);
		}
	}


	function reply()
	{
		if ($this->input->post()) {
			$ticket_id = $this->input->post('ticketid');

			$this->form_validation->set_rules('reply', 'Ticket Reply', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				$_POST = '';
				// Applib::go_to('tickets/view/'.$ticket_id,'error',lang('error_in_form'));
				$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
					redirect('tickets/view/'.$ticket_id);
			}else{
				date_default_timezone_set(config_item('timezone'));
				$attachment = '';
				if($_FILES['ticketfiles']['tmp_name'][0]){
					$attachment = $this->_upload_attachment($this->input->post());
				}

				if(!empty($this->session->set_userdata('ticket_cmnt_parent_id'))){
					$parent_id = $this->session->set_userdata('ticket_cmnt_parent_id');
				}else{
					$parent_id = 0;
				}
				$insert = array(
					'ticketid' 		=> $_POST['ticketid'],
					'body' 			=> $this->input->post('reply'),
					'attachment' 	=> $attachment,
					'replierid' 	=> User::get_id(),
					'time'  => date('Y-m-d H:i:s'),
					'parent_id'=> $parent_id
				);


				if($reply_id = Ticket::save_data('ticketreplies',$insert)){
					$this->session->unset_userdata('ticket_cmnt_parent_id');
					// if ticket is closed send re-opened email to staff/client
				if(Ticket::view_by_id($ticket_id)->status == 'closed'){
					if(config_item('notify_ticket_reopened') == 'TRUE'){
						$this->_notify_ticket_reopened($ticket_id);
					}

				}

					// Ticket::update_data('tickets',array('id'=> $ticket_id),array('status' => 'open'));

					(User::is_client())
								? $this->_notify_ticket_reply('admin',$ticket_id,$reply_id)
								: $this->_notify_ticket_reply('client',$ticket_id,$reply_id);
					// Send email to client/admins

					// Post to slack channel
		            if(config_item('slack_notification') == 'TRUE'){
		            	$this->load->helper('slack');
		            	$slack = new Slack_Post;
		                $slack->slack_reply_ticket($ticket_id,User::get_id(),$reply_id);
		            }

		            $data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_replied',
						'icon' => 'fa-ticket',
						'value1' => Ticket::view_by_id($ticket_id)->subject,
						'value2' => ''
						);
					App::Log($data);

					// Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_replied_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('ticket_replied_successfully'));
					redirect('tickets/view/'.$ticket_id);
				}


			}
		}else{
			$this->index();

		}
	}


	function delete($id = NULL)
	{
		if ($this->input->post()) {

			$ticket = $this->input->post('ticket', TRUE);

			App::delete('ticketreplies',array('ticketid'=>$ticket)); //delete ticket replies
			//clear ticket activities
			App::delete('activities',array('module'=>'tickets', 'module_field_id' => $ticket));
			//delete ticket
			App::delete('tickets',array('id'=>$ticket));

			// Applib::go_to('tickets','success',lang('ticket_deleted_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('ticket_deleted_successfully'));
					redirect('tickets');

		}else{
			$data['ticket'] = $id;
			$this->load->view('modal/delete_ticket',$data);
		}
	}

	function archive()
	{
		$id = $this->uri->segment(3);
		$info = Ticket::view_by_id($id);
		$archived = $this->uri->segment(4);
		$data = array("archived_t" => $archived);
		Ticket::update_data('tickets',array('id'=>$id),$data);

		$data = array(
						'module' => 'tickets',
						'module_field_id' => $id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_edited',
						'icon' => 'fa-pencil',
						'value1' => $info->subject,
						'value2' => ''
						);
		App::Log($data);
		// Applib::go_to('tickets','success',lang('ticket_edited_successfully'));
		$this->session->set_flashdata('tokbox_success', "Ticket Archived Successfully");
					redirect('tickets');
	}

	function download_file($ticket = NULL)
	{
		$this->load->helper('download');
		$file_name = Ticket::view_by_id($ticket)->attachment;
		if(file_exists('./assets/attachments/'.$file_name)){
			$data = file_get_contents('./assets/attachments/'.$file_name); // Read the file's contents
			force_download($file_name, $data);
		}else{
			// Applib::go_to('tickets/view/'.$ticket,'error',lang('operation_failed'));
			$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
					redirect('tickets/view/'.$ticket);
		}
	}


	function status($ticket = NULL){
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
			if($_GET['status']=='reopen'){
				$status = 'open';
				$data = array('reopen_comment' => $_POST['reopen_reason']);
				Ticket::update_data('tickets',array('id' => $ticket),$data);
			}
			$current_status = Ticket::view_by_id($ticket)->status;

			if($current_status == 'closed' && $status != 'closed'){
					if(config_item('notify_ticket_reopened') == 'TRUE'){
						$this->_notify_ticket_reopened($ticket);
					}

				}

			$data = array('status' => $status);
			Ticket::update_data('tickets',array('id' => $ticket),$data);

			$this->_ticket_status_update($ticket,'reporter');
			$this->_ticket_status_update($ticket,'creater');

			if ($status == 'closed' && $current_status != 'closed') {
				// Send email to ticket reporter
				$this->_ticket_closed($ticket);
			}

			// Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
            	$this->load->helper('slack');
            	$slack = new Slack_Post;
                $slack->slack_ticket_changed($ticket,$status,User::get_id());
            }

            $data = array(
						'module' => 'tickets/view/'.$ticket,
						'module_field_id' => $ticket,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_status_changed',
						'icon' => 'fa-ticket',
						'value1' => Ticket::view_by_id($ticket)->subject,
						'value2' => ''
						);
			App::Log($data);
			// Applib::go_to('tickets/view/'.$ticket,'success',lang('ticket_status_changed'));
			$this->session->set_flashdata('tokbox_success', lang('ticket_status_changed'));
					redirect('tickets/view/'.$ticket);

		}else{
			$this->index();
		}
	}
	function assignee($ticket = NULL){
		if (isset($_GET['assignee'])) {
			$assignee = $_GET['assignee'];
			$reporter = Ticket::view_by_id($ticket)->reporter;

			// if($current_status == 'closed' && $status != 'closed'){
			// 		if(config_item('notify_ticket_reopened') == 'TRUE'){
			// 			$this->_notify_ticket_reopened($ticket);
			// 		}

			// 	}

			$data = array('assignee' => $assignee,'reporter'=>User::get_id());
			Ticket::update_data('tickets',array('id' => $ticket),$data);

			// if ($status == 'closed' && $current_status != 'closed') {
			// 	// Send email to ticket reporter
			// 	$this->_ticket_closed($ticket);
			// }

			// Post to slack channel
            // if(config_item('slack_notification') == 'TRUE'){
            // 	$this->load->helper('slack');
            // 	$slack = new Slack_Post;
            //     $slack->slack_ticket_changed($ticket,$status,User::get_id());
            // }
			
			//send mail to assignee
			$this->_send_email_to_assignee($ticket);

			$assignee_name = User::profile_info($assignee)->fullname;
			$teamlead_id = User::login_info($assignee)->teamlead_id;
            $data = array(
						'module' => 'tickets/view/'.$ticket,
						'module_field_id' => $ticket,
						'user' => User::get_id(),
						'activity' => lang('ticket_assignee_updated'),
						'icon' => 'fa-ticket',
						'value1' => Ticket::view_by_id($ticket)->subject,
						'value2' => ''
						);
			App::Log($data);
			//for assignee
			$arg = array(
				'module' => 'tickets/view/'.$ticket,
				'module_field_id' => $ticket,
				'user' => $assignee,
				'activity' => Ticket::view_by_id($ticket)->subject.' ticket assigned',
				'icon' => 'fa-ticket',
				'value1' => Ticket::view_by_id($ticket)->subject,
				'value2' => ''
				);
			App::Log($arg);
			if($reporter != 0) {
				$arg = array(
					'module' => 'tickets/view/'.$ticket,
					'module_field_id' => $ticket,
					'user' => $reporter,
					'activity' => $assignee_name.' assigned to this ticket',
					'icon' => 'fa-ticket',
					'value1' => Ticket::view_by_id($ticket)->subject,
					'value2' => ''
					);
				App::Log($arg);
			}
			if($teamlead_id != 0) {
				$arg = array(
							'module' => 'tickets/view/'.$ticket,
							'module_field_id' => $ticket,
							'user' => $teamlead_id,
							'activity' => $assignee_name.' assigned to this ticket',
							'icon' => 'fa-ticket',
							'value1' => Ticket::view_by_id($ticket)->subject,
							'value2' => ''
							);
				App::Log($arg);
			}
			// Applib::go_to('tickets/view/'.$ticket,'success',lang('ticket_status_changed'));
			$this->session->set_flashdata('tokbox_success', lang('ticket_assignee_updated'));
					redirect('tickets/view/'.$ticket);

		}else{
			$this->index();
		}
	}



	function _ticket_status_update($ticket,$usertype){

		if (config_item('notify_ticket_closed') == 'TRUE') {
			$message = App::email_template('ticket_status_update_email','template_body');
			$subject = App::email_template('ticket_status_update_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($ticket);

			$no_of_replies = App::counter('ticketreplies',array('ticketid' => $ticket));

			if($usertype=='reporter')
			$reporter_email = User::login_info($info->reporter)->email;

			if($usertype=='creater')
			$reporter_email = User::login_info($info->created_by)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			if($usertype=='reporter')
			$reporter = str_replace("{REPORTER_EMAIL}",User::displayName($info->reporter),$title);
			if($usertype=='creater')
			$reporter = str_replace("{REPORTER_EMAIL}",User::displayName($info->created_by),$title);
			$staff = str_replace("{STAFF_USERNAME}",User::displayName(User::get_id()),$reporter);
			$status = str_replace("{TICKET_STATUS}",ucfirst($info->status),$staff);
			$replies = str_replace("{NO_OF_REPLIES}",$no_of_replies,$status);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket,$replies);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$link);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$EmailSignature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']', $subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);
			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';
			$params['recipient'] = $reporter_email;
			modules::run('fomailer/send_email',$params);
		}

	}

	function _ticket_closed($ticket){

		if (config_item('notify_ticket_closed') == 'TRUE') {
			$message = App::email_template('ticket_closed_email','template_body');
			$subject = App::email_template('ticket_closed_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($ticket);

			$no_of_replies = App::counter('ticketreplies',array('ticketid' => $ticket));

			$reporter_email = User::login_info($info->reporter)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			$reporter = str_replace("{REPORTER_EMAIL}",User::displayName($info->reporter),$title);
			$staff = str_replace("{STAFF_USERNAME}",User::displayName(User::get_id()),$reporter);
			$status = str_replace("{TICKET_STATUS}",'Closed',$staff);
			$replies = str_replace("{NO_OF_REPLIES}",$no_of_replies,$status);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket,$replies);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$link);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$EmailSignature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']', $subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);
			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';

			$params['recipient'] = $reporter_email;
			modules::run('fomailer/send_email',$params);
		}

	}

	function _notify_ticket_reply($group,$id,$reply_id){

		if (config_item('notify_ticket_reply') == 'TRUE') {

			$message = App::email_template('ticket_reply_email','template_body');
			$subject = App::email_template('ticket_reply_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);
			$reply = $this->db->where('id',$reply_id)->get('ticketreplies')->row();


			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			$status = str_replace("{TICKET_STATUS}",ucfirst($info->status),$title);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$status);
			$body = str_replace("{TICKET_REPLY}",$reply->body,$link);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$body);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$EmailSignature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']'.$info->subject,$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';



			switch ($group) {
				case 'admin':
				// Send to admins
				if(count(User::team())){

	        	$staff_members = User::team();
				// Send email to staff department
				foreach ($staff_members as $key => $user) {
					$dep = json_decode(User::login_info($user->id)->department_id,TRUE);
					if (is_array($dep) && in_array($info->department, $dep)) {
	            		$email = User::login_info($user->id)->email;
						$params['recipient'] = $email;
						modules::run('fomailer/send_email',$params);
	        		}
				}

				}

				return TRUE;
				break;

				default:
				$params['recipient'] = User::login_info($info->reporter)->email;
				modules::run('fomailer/send_email',$params);

				return TRUE;
				break;
				}

		}
	}


	function _notify_ticket_reopened($ticket){

			$message = App::email_template('ticket_reopened_email','template_body');
			$subject = App::email_template('ticket_reopened_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($ticket);

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$title = str_replace("{SUBJECT}",$info->subject,$logo);
			$user = str_replace("{USER}",User::displayName(User::get_id()),$title);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket,$user);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$link);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$EmailSignature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);

			$subject = str_replace("[SUBJECT]",$info->subject, $subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';
			// echo $message;exit;
	        // if(User::is_client()){
	        	// Get admins
	        $staff_members = $this->db->where(array('department_id'=>$info->department,'activated'=>1,'banned'=>0)) -> get('users')->result();
			// print_r($staff_members);exit;
			if(count($staff_members)){
	        	// if(count(User::team())){
	        	// $staff_members = User::team();
				// Send email to staff department
				foreach ($staff_members as $key => $user) {
					$dep = json_decode(User::login_info($user->id)->department_id,TRUE);
					// if (is_array($dep) && in_array($info->department, $dep)) {

	            		$email = User::login_info($user->id)->email;
						$params['message'] = str_replace("{RECIPIENT}",User::displayName($user->id),$message);
						$params['recipient'] = $email;
						// echo $params['message'];exit;
						modules::run('fomailer/send_email',$params);
	        		// }
				}
			// }

			}else{
				$email1 = User::login_info($info->reporter)->email;
				$rep_details = emp_details($info->reporter);
				$params1['message'] = str_replace("{RECIPIENT}",$rep_details['fullname'],$message);
				$params1['recipient'] = $email1;
				modules::run('fomailer/send_email',$params1s);
			}


	}

	function _send_email_to_staff($id)
	{
		if (config_item('email_staff_tickets') == 'TRUE') {

			$message = App::email_template('ticket_staff_email','template_body');
			$subject = App::email_template('ticket_staff_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);

			$reporter_email = User::login_info($info->reporter)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			$reporter = str_replace("{REPORTER_EMAIL}",$reporter_email,$title);
			// $UserEmail =
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$reporter);
			$signature = str_replace("{SIGNATURE}",$signature,$link);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$signature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$params['subject'] = $subject;

			$params['attached_file'] = '';
			$params['alt_email'] = 'support';
$staff_members = $this->db->where(array('department_id'=>$info->department,'activated'=>1,'banned'=>0)) -> get('users')->result();
			if(count($staff_members)){
			// $staff_members = User::team();

			// Send email to staff department
			foreach ($staff_members as $key => $user) {
				$dep = json_decode(User::login_info($user->id)->department_id,TRUE);
				// if (is_array($dep) && in_array($info->department, $dep)) {
            		$email = User::login_info($user->id)->email;
					$params['message'] = str_replace("{USER_EMAIL}",User::displayName($user->id),$message);
					$params['recipient'] = $email;
					modules::run('fomailer/send_email',$params);
        		// }
			}
		}

			return TRUE;

		}else{
			return TRUE;
		}

	}

	function _send_email_to_client($id)
	{

			$message = App::email_template('ticket_client_email','template_body');
			$subject = App::email_template('ticket_client_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);

			$email = User::login_info($info->reporter)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$client_email = str_replace("{CLIENT_EMAIL}",$email,$logo);
			$ticket_code = str_replace("{TICKET_CODE}",$info->ticket_code,$client_email);
			$title = str_replace("{SUBJECT}",$info->subject,$ticket_code);
			$ticket_link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$title);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$ticket_link);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$EmailSignature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);
			$data['message'] = $message;

			$message = $this->load->view('email_template', $data, TRUE);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$params['recipient'] = $email;
			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';

			modules::run('fomailer/send_email',$params);
			return TRUE;

	}

	function _send_email_to_assignee($id)
	{

			$message = App::email_template('ticket_assign_email','template_body');
			$subject = App::email_template('ticket_assign_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);

			$email = User::login_info($info->assignee)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$client_email = str_replace("{USER_EMAIL}",User::displayName($info->assignee),$logo);
			$ticket_code = str_replace("{TICKET_CODE}",$info->ticket_code,$client_email);
			$title = str_replace("{SUBJECT}",$info->subject,$ticket_code);
			$ticket_link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$title);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$ticket_link);
			$regards = str_replace("{REGARDS}",config_item('best_regards'),$EmailSignature);
        	$message = str_replace("{SITE_NAME}",config_item('company_name'),$regards);
			$data['message'] = $message;

			$message = $this->load->view('email_template', $data, TRUE);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$params['recipient'] = $email;
			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';

			modules::run('fomailer/send_email',$params);
			return TRUE;

	}

	function ticket_time_expired_email_notification(){
		$tickets = $this->db->where('(`status` != "closed" and `status` != "resolved")')->get('tickets')->result();

		if(count($tickets)){
			foreach ($tickets as $key => $value) {
				$time_estimation = strtotime('+'.$value->time_estimation.' hours', strtotime( $value->created ));
				$time_estimation_time = date("Y-m-d H:i:s",$time_estimation);
				$today = date("Y-m-d H:i:s");
				// echo $today .'>'.$time_estimation_time; exit();
				if($today > $time_estimation_time){
					$assignee_info = $this->db->where('id',$value->assignee)->get('users')->row();
					
					$assignee_det_info = $this->db->where('user_id',$value->assignee)->get('account_details')->row();
					$assignee_reporto = $this->db->where('id',$assignee_info->teamlead_id)->get('users')->row();
					$assignee_reporto_info = $this->db->where('user_id',$assignee_info->teamlead_id)->get('account_details')->row();
					$subject         = " Ticket Time Expired ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Ticket Time Expired</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.ucfirst($assignee_reporto_info->fullname).',</p>
												<p> '.$value->subject.' this ticket time was expired please check</p>
												<p> Assignee name : <br>
													'.ucfirst($assignee_det_info->fullname).'
												</p>
												<br> 
												<p> Created at : <br>
													'.date('d M Y',strtotime($value->created)).'
												</p>
												<br> 
												<p> Status : <br> <br>
													'.ucfirst($value->status).'
												</p>
												<br> 
												
												&nbsp;&nbsp; 
												 
												<a style="text-decoration:none; margin-left:15px" href="'.base_url().'tickets/"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Best Regards,<br> '.config_item('best_regards').'
											</div>
									 </div>'; 
				$params      = array(
										'recipient' => $assignee_reporto->email,
										'subject'   => $subject,
										'message'   => $message
									);   
				$succ = Modules::run('fomailer/send_email',$params);
				// echo $message ;
				$response = 'mail sent';
				
				}
			}
			
		} else {
					$response = 'No Tickets found'; 
				}
		$result=array('status'=>true,'data'=>$response);
		
		echo json_encode($result); exit;
	}
	function _upload_attachment($data){

		$config['upload_path'] = './assets/attachments/';
		$config['allowed_types'] = '*';
		$config['max_size'] = config_item('file_max_size');
		$config['overwrite'] = FALSE;
		$this->load->library('upload', $config);

		if(!$this->upload->do_multi_upload("ticketfiles")) {
			Applib::make_flashdata(array(
				'response_status' => 'error',
				'message' => lang('operation_failed'),
				'form_error'=> $this->upload->display_errors('<span style="color:red">', '</span><br>')
			));
			
			// echo '<pre>';print_r($this->upload->display_errors());exit;
			redirect($_SERVER['HTTP_REFERER']);
		} else {

			$fileinfs = $this->upload->get_multi_upload_data();
			foreach ($fileinfs as $fileinf) {
				$attachment[] = $fileinf['file_name'];
			}

			return json_encode($attachment);

		}




	}



	function _admin_tickets($archive = FALSE,$filter_by = NULL){

		if($_POST){
			if(!empty($_POST['ticked_priority']))
			$this->db->where('priority',$_POST['ticked_priority']);
			if(!empty($_POST['ticket_from']))
			$this->db->where('created>=',date('Y-m-d',strtotime($_POST['ticket_from'])));
			if(!empty($_POST['ticket_to']))
			$this->db->where('created<=',date('Y-m-d',strtotime($_POST['ticket_to'])));
			if(!empty($filter_by))
			$this->db->where('status',$filter_by);
			$this->db->from('dgt_tickets');
			return $this->db->get()->result();
		}
		if($filter_by == NULL) return Ticket::by_where(array('archived_t !=' => '1'));

		if ($archive) return Ticket::by_where(array('archived_t' => '1'));

		switch ($filter_by) {
			case 'open':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'open'));
			break;
			case 'closed':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'closed'));
			break;
			case 'pending':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'pending'));
			break;
			case 'resolved':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'resolved'));
			break;
			case 'inprogress':
				return Ticket::by_where(array('archived_t !='=>'1','status' => 'inprogress'));
				break;

			default:
			return Ticket::by_where(array('archived_t !='=>'1'));
			break;
		}

	}


	function _staff_tickets($archive = FALSE, $filter_by = NULL){

		$staff_department = User::login_info(User::get_id())->department_id;
		$dep = json_decode($staff_department,TRUE);

		if($filter_by == NULL){

			($archive) ? $this->db->where(array('archived_t' => '1'))
					: $this->db->where(array('archived_t !=' => '1'));

			if(is_array($dep)){
				$this->db->where_in('department', $dep);
			}else{
				$this->db->where('department',$staff_department);
			}
			$output = $this->db->or_where('reporter',User::get_id())->get('tickets')->result();
			return $output;

		}

		switch ($filter_by) {
			case 'open':
			$this->db->where(array('archived_t !=' => '1','status' => 'open'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;
			case 'closed':

			$this->db->where(array('archived_t !=' => '1','status' => 'closed'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;
			case 'pending':
			$this->db->where(array('archived_t !=' => '1','status' => 'pending'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;
			case 'resolved':
			$this->db->where(array('archived_t !=' => '1','status' => 'resolved'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;

			default:
			$this->db->where(array('archived_t !=' => '1'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();
			break;
		}



	}



	function _client_tickets($archive = FALSE, $filter_by = NULL){
		$user = User::view_user(User::get_id());
		
		if($user->department_id !=1 && $user->department_id !=3 && $user->department_id !=9 && $user->department_id !=10){
			if($_POST){
				if(!empty($_POST['ticked_priority']))
				$this->db->where('priority',$_POST['ticked_priority']);
				if(!empty($_POST['ticket_from']))
				$this->db->where('created>=',date('Y-m-d',strtotime($_POST['ticket_from'])));
				if(!empty($_POST['ticket_to']))
				$this->db->where('created<=',date('Y-m-d',strtotime($_POST['ticket_to'])));
				// $this->db->where('created_by',User::get_id());
				$this->db->where('assignee',User::get_id());
				if(!empty($filter_by))
				$this->db->where('status',$filter_by);
				$this->db->from('dgt_tickets');
				return $this->db->get()->result();
			}
			if($filter_by == NULL){
				// echo 1;exit;
				if($archive){
					// return Ticket::by_where(array('reporter'=>User::get_id(),'archived_t'=>'1'));
					// return Ticket::by_where(array('created_by'=>User::get_id(),'archived_t'=>'1'));
					return Ticket::by_where(array('assignee'=>User::get_id(),'archived_t'=>'1'));
				}else{
					// return Ticket::by_where(array('reporter'=>User::get_id(),'archived_t !='=>'1'));
					// return Ticket::by_where(array('created_by'=>User::get_id(),'archived_t !='=>'1'));
					return Ticket::by_where(array('assignee'=>User::get_id(),'archived_t !='=>'1'));
				}
			}else{
				switch ($filter_by) {
					case 'open':
					return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','assignee'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','created_by'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','reporter'=>User::get_id()));
		
					break;
					case 'closed':
					return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','assignee'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','created_by'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','reporter'=>User::get_id()));
		
					break;
					case 'pending':
					return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','assignee'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','created_by'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','reporter'=>User::get_id()));
		
					break;
					case 'resolved':
					return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','assignee'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','created_by'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','reporter'=>User::get_id()));
		
					break;

					case 'inprogress':
						return Ticket::by_where(array('archived_t !='=>'1','status'=>'inprogress','assignee'=>User::get_id()));
						// return Ticket::by_where(array('archived_t !='=>'1','status'=>'inprogress','created_by'=>User::get_id()));
						// return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','reporter'=>User::get_id()));
			
						break;
		
					default:
					// return Ticket::by_where(array('archived_t !='=>'1','created_by'=>User::get_id()));
					return Ticket::by_where(array('archived_t !='=>'1','assignee'=>User::get_id()));
					// return Ticket::by_where(array('archived_t !='=>'1','reporter'=>User::get_id()));
					break;
				}
			}
		}else{
			
			if($_POST){
				if(!empty($_POST['ticked_priority']))
				$this->db->where('priority',$_POST['ticked_priority']);
				if(!empty($_POST['ticket_from']))
				$this->db->where('created >=',date('Y-m-d',strtotime($_POST['ticket_from'])));
				if(!empty($_POST['ticket_to']))
				$this->db->where('created <=',date('Y-m-d',strtotime($_POST['ticket_to'])));
				$this->db->where('department',$user->department_id);
				if(!empty($filter_by))
				$this->db->where('status',$filter_by);
				$this->db->from('dgt_tickets');
				return $this->db->get()->result();
			}
			if($filter_by == NULL){
				if($archive){
					// return Ticket::by_where(array('reporter'=>User::get_id(),'archived_t'=>'1'));
					return Ticket::by_where(array('department'=>$user->department_id,'archived_t'=>'1'));
				}else{
					// return Ticket::by_where(array('reporter'=>User::get_id(),'archived_t !='=>'1'));
					return Ticket::by_where(array('department'=>$user->department_id,'archived_t !='=>'1'));
				}
	
			}
	
			switch ($filter_by) {
				case 'open':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','department'=>$user->department_id));
				// return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','reporter'=>User::get_id()));
	
				break;
				case 'closed':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','department'=>$user->department_id));
				// return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','reporter'=>User::get_id()));
	
				break;
				case 'pending':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','department'=>$user->department_id));
				// return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','reporter'=>User::get_id()));
	
				break;
				case 'resolved':
				return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','department'=>$user->department_id));
				// return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','reporter'=>User::get_id()));
	
				break;
	
				default:
				return Ticket::by_where(array('archived_t !='=>'1','department'=>$user->department_id));
				// return Ticket::by_where(array('archived_t !='=>'1','reporter'=>User::get_id()));
				break;
			}
		}

	}

	function set_parent_comment()
	{
		$parent_id = $this->input->post('parent_id');
		$this->session->set_userdata('ticket_cmnt_parent_id',$parent_id);
	}


}

/* End of file invoices.php */
