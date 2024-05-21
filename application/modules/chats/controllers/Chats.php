<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

class Chats extends MX_Controller {

	function __construct()
	{

		parent::__construct();

		$this->load->module('layouts');

		$this->load->library(array('template','tank_auth'));

		$this->template->title('Chats - '.config_item('company_name'));

		$this->page = 'chats';

		if (!$this->tank_auth->get_username()) {

			$this->session->set_flashdata('response', 'error');

			$this->session->set_flashdata('message', lang('access_denied'));

			redirect('');

		}

		//$this->group = isset($_GET['group']) ? $_GET['group'] : 'inbox';

		$this->load->model(array('chats_model', 'App'));

		date_default_timezone_set($this->session->userdata('time_zone'));
		// $this->apiKey = '46183542';
		// $this->apiSecret = '6ce8dcd830d428d202903a9567c460bf7eebc432';
		$this->apiKey = config_item('apikey_tokbox');
		$this->apiSecret = config_item('apisecret_tokbox');
		$this->login_id = $this->session->userdata('user_id');
		$this->session->set_userdata('time_zone',date_default_timezone_set());


	}



	function index()

	{

		// $opentok = new OpenTok($this->apiKey, $this->apiSecret);
		// 		// An automatically archived session:
		// 	$sessionOptions = array(
		// 		// 'archiveMode' => ArchiveMode::ALWAYS,
		// 		'mediaMode' => MediaMode::ROUTED
		// 	);
		// 	$new_session = $opentok->createSession($sessionOptions);
		// 		// Store this sessionId in the database for later use
		// 	$sessionId = $new_session->getSessionId();

		// 	$res = array(
		// 		'common_session_id' => $sessionId
		// 	);
		// 	$this->db->insert('dgt_chat_common_session',$res);



		// $this->chatting();
		if($this->tank_auth->is_logged_in()) {

			$log_id = $this->session->userdata('user_id');
			$log_check = $this->chats_model->user_checkById($log_id);
			// if($log_check['session_id'] == '')
			// {
			// 	$opentok = new OpenTok($this->apiKey, $this->apiSecret);
			// 		// An automatically archived session:
			// 	$sessionOptions = array(
			// 		// 'archiveMode' => ArchiveMode::ALWAYS,
			// 		'mediaMode' => MediaMode::ROUTED
			// 	);
			// 	$new_session = $opentok->createSession($sessionOptions);
			// 		// Store this sessionId in the database for later use
			// 	$sessionId = $new_session->getSessionId();
			// 	$res = array(
			// 		'session_id' => $sessionId
			// 	);

			// 	$this->db->where('id',$log_id);
			// 	$this->db->update('users',$res);
			// }

			$this->load->module('layouts');

			$this->load->library('template');

			$data['datepicker']   = TRUE;
			$data['datatables']   = TRUE;

			$data['form']         = TRUE; 

			$data['page']         = 'chats';  

			$data['chat_history'] = $this->chats_model->user_chat_history($this->tank_auth->get_user_id());

			$data['group_chat_list'] = $this->chats_model->getChatList($log_id,'group');

			$data['single_chat_list'] = $this->chats_model->getChatList($log_id,'one');
			
			//echo $this->db->last_query();exit; 
			//print_r($data['single_chat_list']);exit;
			$data['all_users'] = $this->chats_model->get_all_users(array('status'=>1));
			$where['created_by'] = $this->login_id;
			$data['groups_list'] =  $this->chats_model->get_groups($where); 

			// echo $this->db->last_query();
			// echo "<pre>";

			// print_r($data['groups_list']->result());

			// exit;
			$this->session->unset_userdata('current_chat_user');
			$this->session->unset_userdata('session_group_id');

			$data['role']        = $this->tank_auth->get_role_id();

			$this->template

				 ->set_layout('users')

				 ->build('chats',isset($data) ? $data : NULL);

	    }else{

		   redirect('');	

		}

	}

public function delete_group(){
		$count = $this->db->get_where('dgt_users',array('group_id'=>$_POST['group_id'],'call_status'=>1))->num_rows();
		if($count == 0){
			$this->db->update('dgt_chat_group_details',array('status'=>0),array('group_id'=>$_POST['group_id']));	
			$result = array('success' => 'deleted');
		}else{
			$result = array('error' => 'Group call is active in this group!');
		}
		echo json_encode($result);
		
	}
	public function set_call_status(){

		$where = array('login_id' =>$this->login_id);
		$data = array('call_status' =>$_POST['call_status']);
		$this->db->update('dgt_users',$data,$where);
		if(!empty($_POST['type'])){
			echo $this->db->insert('dgt_call_type',array('login_id'=>$this->login_id,'type'=>$_POST['type']));
		}

	}


	public function insert_call_type(){
		$data = array(
			'login_id' => $this->login_id,
			'type' => $_POST['type'],
		);
		echo $this->db->insert('dgt_call_type',$data);
	}

	public function update_group_member() {
		$result = $this->db->update('dgt_chat_group_members', array('is_active'=>$_POST['is_active']),array('group_id'=>$_POST["group_id"],'login_id'=>$this->login_id));
		echo json_encode($result);
	}

	public function get_group_members_status() {
		$result = $this->db->get_where('dgt_chat_group_members', array('group_id'=>$_POST["group_id"]))->result_array();
		echo json_encode($result);
	}

	public function update_call_details(){

		if($_POST['call_to_id'] == $this->login_id){
			$call_started_at = convert_datetime($_POST['call_started_at']);
			$call_ended_at = convert_datetime($_POST['call_ended_at']);			
			$data = array(
				'call_from_id' => $_POST['call_from_id'],
				'call_to_id' => $_POST['call_to_id'],
				'group_id' => $_POST['group_id'],
				'call_type' => $_POST['call_type'],
				'call_duration' => $_POST['call_duration'],
				'call_started_at' => $call_started_at,
				'call_ended_at' => $call_ended_at,
				'end_cause' => $_POST['end_cause']
			);
			$this->db->insert('dgt_call_details',$data);


		}
		$this->set_call_status();
		$datas['call_history'] =$this->chat->get_call_history_row();
		echo json_encode($datas);
		
		
	}
	public function set_nav_bar(){
		$page = array();
		if(!empty($_POST['page'])){
			$page = array('page'=>$_POST['page']);
			$this->session->set_userdata($page);
		}
		echo json_encode($page);
	}
	public function get_group_member_details(){
		$where = array('sinch_username' => $_POST['sinch_username']);
		$data = $this->db
		->select('l.first_name,l.last_name,l.login_id as call_from_id,l.sinch_username,l.profile_img,c.type')
		->join('dgt_call_type c','l.login_id = c.login_id')
		->order_by('c.date_created','desc')
		->get_where('dgt_users l',$where)
		->row_array();
		$data['name'] = ucfirst($data['first_name']).' '.ucfirst($data['last_name']);
		$data['profile_img'] = (!empty($data['profile_img']))?base_url().'uploads/'.$data['profile_img'] : base_url().'assets/img/user.jpg';
		$data['call_to_id'] = $this->login_id;
		echo json_encode($data);
	}
	public function get_call_status(){
		$data=array();
		$result=array();
		$where = array('sinch_username'=>$_POST['sinch_username']);

		$data = $this->db
		->select('first_name,last_name,online_status,call_status')
		->get_where('dgt_users',$where)
		->row_array();

		if($data['online_status'] == 0){
			$result['name'] = ucfirst($data['first_name']).'  '.ucfirst($data['last_name']).' is Offline';
		}elseif($data['call_status'] == 1){
			$result['name'] = ucfirst($data['first_name']).'  '.ucfirst($data['last_name']).' already in Call';
		}else{
			$this->db->insert('dgt_call_type',array('login_id' => $this->login_id,'type' => $_POST['type']));
			$this->db->update('dgt_users',array('call_status'=>1),array('login_id' =>$this->login_id));		
		}			
		echo json_encode($result);
		
		
	}
	public function get_caller_details(){

		$where = array('sinch_username' => $_POST['sinch_username']);

		$data = $this->db
		->select('l.first_name,l.last_name,l.login_id as call_from_id,l.sinch_username,l.profile_img,c.type')
		->join('call_type c','l.login_id = c.login_id')
		->order_by('c.date_created','desc')
		->get_where('dgt_users l',$where)
		->row_array();
		
		$data['name'] = ucfirst($data['first_name']).' '.ucfirst($data['last_name']);
		$data['profile_img'] = (!empty($data['profile_img']))?base_url().'uploads/'.$data['profile_img'] : base_url().'assets/img/user.jpg';
		$data['call_to_id'] = $this->login_id;
		echo json_encode($data);
	}

	public function get_new_group_datas(){
		$group_id = $this->session->userdata('session_group_id');
		$data = $this->chat->get_group_datas($group_id);
		echo json_encode($data);
	}
	
	public function get_group_datas(){
		
		


		$group_id = $_POST['group_id'];
		$count = $this->db->get_where('dgt_chat_group_details',array('status'=>1,'group_id'=>$group_id))->num_rows();
		if($count == 0){
			echo json_encode(array('error'=>'No group'));
			exit;
		}
		// echo '<pre>'; print_r($_POST);
		//  exit;


		$this->session->set_userdata(array('session_chat_id'=>''));		
		$data = $this->chat->get_group_datas($group_id);
		$total_chat= $this->chat->get_total_chat_group_count($group_id); 

		$this->session->set_userdata(array('session_group_id'=>$group_id,'group_type'=>$data['group']['type']));
		$latest_chats = $this->chat->get_group_messages($total=null,$group_id);  		
		$page=0;
		if($total_chat>5){
			$total_chat = $total_chat - 5;
			$page = $total_chat / 5;
			$page = ceil($page);
			$page--;
		}

		if(count($latest_chats)>4){

			$html ='<div class="load-more-btn text-center" total="'.$page.'">
			<button class="btn btn-default" data-page="2"><i class="fa fa-refresh"></i> Load More</button>
			</div><div class="ajax_old"></div>';      
		}else{
			$html ='';
		}


		if(!empty($latest_chats)){

			foreach($latest_chats as $l){
				$sender_name = $l['sender_name'];
				$sender_profile_img = (!empty($l['sender_profile_image']))?base_url().'uploads/'.$l['sender_profile_image'] : base_url().'assets/img/user.jpg';
				$msg = $l['message'];
				$type = $l['type'];
				$file_name = base_url().$l['file_path'].'/'.$l['file_name'];
				$time = date('h:i A',strtotime($l['chatdate']));
				$up_file_name =$l['file_name'];

				if($l['sender_id'] == $this->login_id){
					$class_name = 'chat-right';
					$img_avatar='';
				}else{
					$img_avatar = '<div class="chat-avatar">
					<a href="#" class="avatar">
					<img alt="'.$sender_name.'" title="'.$sender_name.'" src="'.$sender_profile_img.'" class="img-fluid rounded-circle">
					</a>
					</div>';
					$class_name = 'chat-left';
				}
				if($msg == 'file' && $type == 'image'){

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content img-content">
					<div class="chat-img-group clearfix">
					<a class="chat-img-attach" href="'.$file_name.'" target="_blank">
					<img width="182" height="137" alt="" src="'.$file_name.'">
					<div class="chat-placeholder">
					<div class="chat-img-name">'.$up_file_name.'</div>
					</div>
					</a>
					</div>
					<span class="chat-time">'.$time.'</span>
					</div>               
					</div>
					</div>
					</div>';

				}else if($msg == 'file' && $type == 'others'){

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content "><ul class="attach-list">
					<li><i class="fa fa-file"></i><a href="'.$file_name.'">'.$up_file_name.'</a></li>
					</ul>
					<span class="chat-time">'.$time.'</span>       
					</div>
					</div>
					</div>
					</div>';

				}else{

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content">
					<p>'.$msg.'</p>         
					<span class="chat-time">'.$time.'</span>
					</div>
					</div>
					</div>
					</div>';
				}														
			}

		}

		$html .='<div class="ajax"></div><input type="hidden"  id="hidden_id">';

		if($total_chat == 0){
			$html .='<div class="no_message"></div>';
		}
		$data['messages'] = $html;


		echo json_encode($data);
	}
	Public function get_username(){
		$data = array();
		$data = $this->db->select('first_name,last_name,profile_img')
		->get_where('dgt_users',array('sinch_username' => $_POST['sinch_username']))
		->row_array();
		if(!empty($data)){
			$data['first_name'] = ucfirst($data['first_name']);
			$data['last_name'] = ucfirst($data['last_name']);
			$data['profile_img'] = (!empty($data['profile_img']))?base_url().'uploads/'.$data['profile_img'] : base_url().'assets/img/user.jpg';
		}
		echo json_encode($data);
	}

	public function add_group_user(){
		// echo '<pre>';
		// 	print_r($_POST);
		// 	print_r($this->session->userdata('session_group_id'));
		// exit;
		$group_name='';

		if(!empty($_POST['group_id'])){
			$group_id = $_POST['group_id'];			
		}elseif(!empty($this->session->userdata('session_group_id'))){ 
			/* Checking for session Group id */		
			$group_id = $this->session->userdata('session_group_id');

		}elseif(!empty($this->session->userdata('dummy_group_id'))){ 
			/* Checking for Dummy group id */
			$group_id = $this->session->userdata('dummy_group_id');
		}else{
			/* No session & dummy group id & post group id */
			$opentok = new OpenTok($this->apiKey, $this->apiSecret);
				// An automatically archived session:
			$sessionOptions = array(
				// 'archiveMode' => ArchiveMode::ALWAYS,
				'mediaMode' => MediaMode::ROUTED
			);
			$new_session = $opentok->createSession($sessionOptions);
				// Store this sessionId in the database for later use
			$sessionId = $new_session->getSessionId();
			$data = array(					
				'session_id' => $sessionId,
				'type' => 'text',
				'created_by' => $this->login_id
			);
			$this->db->insert('dgt_chat_group_details',$data);
			$group_id =$this->db->insert_id();				

			$where = array('group_id' => $group_id,'login_id'=>$this->login_id);
			$check  = $this->db->get_where('dgt_chat_group_members',$where)->num_rows();

			/* Check already users presence in group */
			
			if($check == 0){				
				$det = array(
					
					'group_id' => $group_id,
					'login_id' => $this->login_id,
					'new_user' => 1
				);

				$this->db->insert('dgt_chat_group_members',$det);
				$this->db->insert('dgt_chat_seen_details',$det);							
			}
		}

		/* Insert Current User in group */
			$group_name = 'Group'.$group_id;
			$this->db->update('dgt_chat_group_details',array('group_name'=>$group_name,'type' => 'text' ),array('group_id'=>$group_id));



		$member = explode(',',$_POST['members']);
		$new_users = array();

		$this->db->update('dgt_chat_group_members',array('new_user'=>0),array('group_id'=>$group_id));

		for ($i=0; $i <count($member) ; $i++) { 
			$user = $this->db->get_where('dgt_users',array('username'=>$member[$i],'activated'=>1))->row_array();
			// print_r($users); exit;
			if(!empty($user)){
				$sinch_usernames[]=$user['username'];
				$datas = array(
					'group_id' => $group_id,
					'login_id' => $user['id'],
					'new_user' => 1
				);

				$where = array('group_id' => $group_id,'login_id'=>$user['login_id']);
				$check  = $this->db->get_where('dgt_chat_group_members',$where)->num_rows();
				/* Check already users presence in group */
				if($check == 0){
					$new_users[]=$datas['login_id'];
					$this->db->insert('dgt_chat_group_members',$datas);
					$this->db->insert('dgt_chat_seen_details',$datas);							
				}
			}
		}

		/* Session chat user id */
		if(!empty($this->session->userdata('session_chat_id'))){
			$where = array('group_id' => $group_id,'login_id'=>$this->session->userdata('session_chat_id'));
			$check  = $this->db->get_where('dgt_chat_group_members',$where)->num_rows();
			/* Check already users presence in group */
			if($check == 0){
				$new_users +=array($this->session->userdata('session_chat_id'));
				$dat = array(
					'group_id' => $group_id,
					'login_id' => $this->session->userdata('session_chat_id'),
					'new_user' => 1
				);
				$this->db->insert('dgt_chat_group_members',$dat);
				$this->db->insert('dgt_chat_seen_details',$dat);							
			}
			
		}



		$sinch_users=array();
		if(!empty($new_users)){		
			/* Check Current user in Call */
			 $count = $this->db
			->get_where('dgt_users',array('id' => $this->login_id, 'call_status' =>1 ))
			->num_rows();



			if($count!=0){
				/*Make Call ringing for new user */

				for ($i=0; $i <count($new_users) ; $i++) { 
					/* Check Calling user already  in Call */
					$where = array('id' => $new_users[$i], 'call_status' =>1 );
					$count = $this->db
					->get_where('dgt_users',$where)
					->num_rows();

					if($count==0){
						$new = array(
							'type' => 'many',
							'group_id' => $group_id ,
							'from_id' => $this->login_id,
							'to_id'=>$new_users[$i]
						);
						$this->db->insert('dgt_call_notification',$new);									
					}

				}
			}




			$sinch_users=$this->db
			->select('U.username,U.id,AD.avatar as profile_img')
			->from('dgt_users U')
			->join('dgt_account_details AD','U.id = AD.user_id')
			->where_in('U.id',$new_users)
			->get()
			->result_array();
		}				
		$session_values = array('session_chat_id' =>'','session_group_id' => $group_id);
		$this->session->set_userdata($session_values);
		echo json_encode(array('group_name'=>$group_name,'group_id'=>$group_id,'users'=>$sinch_users));
		exit;
	}
	public function update_group(){
		 $this->db->update('dgt_chat_group_details',array('group_name'=>$_POST['group_name']),array('group_id' => $_POST['group_id']));
		 $hidden_group_name = str_replace(' ','_',$_POST['hidden_group_name']);
		 $new_group_name = str_replace(' ','_',$_POST['group_name']);
echo json_encode(array('hidden_group_name'=>ucfirst($hidden_group_name),'group_name'=>$_POST['group_name'],'new_group_name'=>ucfirst($new_group_name)));
	}
	public function create_group(){

		$this->session->set_userdata(array('session_chat_id'=>''));
		$group_type = $_POST['group_type'];

		/* No session & dummy group id & post group id */
			$opentok = new OpenTok($this->apiKey, $this->apiSecret);
				// An automatically archived session:
			$sessionOptions = array(
				// 'archiveMode' => ArchiveMode::ALWAYS,
				'mediaMode' => MediaMode::ROUTED
			);
			$new_session = $opentok->createSession($sessionOptions);
				// Store this sessionId in the database for later use
			$sessionId = $new_session->getSessionId();



		$data = array('group_name' => $_POST['group_name'],'type' => $group_type,'session_id' => $sessionId);
		$count = $this->db->get_where('dgt_chat_group_details',$data)->num_rows();
		if($count!=0){
			$result = array('error'=>'Group name already taken!');		
			
		}else{

			$this->db->insert('dgt_chat_group_details',$data);
			$group_id = $this->db->insert_id();
			$this->session->set_userdata(array('session_group_id'=>$group_id,'group_type' => $group_type));

			$member = explode(',',$_POST['members']);
			// print_r($member); exit;
			for ($i=0; $i <count($member) ; $i++) { 

				$user = $this->db
				->select('username,id')
				->get_where('dgt_users',array('username'=>$member[$i],'activated'=>1))
				->row_array();
				$group_members[]=$user;
				if(!empty($user)){
					$sinch_usernames[]=$user['username'];
					$datas = array(
						'group_id' => $group_id,
						'login_id' => $user['id']
					);
					$this->db->insert('dgt_chat_group_members',$datas);
					$this->db->insert('dgt_chat_seen_details',$datas);	
				}
				
			}
			// print_r($group_members); exit;
			$sinch_usernames = implode(',',$sinch_usernames);
			$datas = array(
				'group_id' => $group_id,
				'login_id' => $this->login_id
			);
			$this->db->insert('dgt_chat_group_members',$datas);

			$datas = array(
				'group_id' => $group_id,
				'login_id' => $this->login_id
			);

			$datta['receiver_id'] = 0;
			$datta['sender_id'] = $this->login_id;
			$datta['time_zone'] = $this->session->userdata('time_zone');
			$datta['chatdate'] = date('Y-m-d H:i:s');
			$datta['message'] = 'Hi there!';
			$datta['message_type'] = 'group';
			$datta['group_id'] = $group_id;
			$this->db->insert('dgt_chat_details',$datta);

			$note_data = array(
				'created_by' => $this->login_id,
				'group_id' => $group_id,
				'group_type' =>$group_type,				
			);
			$this->db->insert('dgt_notification_details',$note_data);



			$result = array(
				'success'=>'Group created successfully!',
				'group_name' => ucfirst($_POST['group_name']),
				'group_id'=>$group_id,
				'group_type'=>$group_type,
				'sinch_username' => $sinch_usernames,
				'group_members' => $group_members
			);
			
		}	
		echo json_encode($result); exit;

	}

	public function get_notification(){
		$data = array();
		$where = array('l.sinch_username' => $_POST['created_by']);
		$data = $this->db
		->select('l.first_name,l.last_name,l.profile_img,g.group_name,g.type as group_type')				
		->order_by('n.note_id','desc')
		->join('dgt_notification_details n','l.login_id = n.created_by')
		->join('dgt_chat_group_details g','g.group_id = n.group_id')					
		->get_where('dgt_users l',$where)
		->row_array();

		if(!empty($data)){
			$data['group_name'] = ucfirst($data['group_name']);
			$data['first_name'] = ucfirst($data['first_name']);
			$data['last_name'] = ucfirst($data['last_name']);
			$data['profile_img'] = (!empty($data['profile_img']))?base_url().'uploads/'.$data['profile_img'] : base_url().'assets/img/user.jpg';						
		}

		echo json_encode($data);
	}

	public function create_share(){
		$data = array('group_name' => $_POST['group_name'], 'from_id' => $this->login_id);
		$count = $this->db->get_where('screen_share_details',$data)->num_rows();
		if($count!=0){
			$result = array('error'=>'Group name already taken!');		
			
		}else{
			$da = array(
				'group_name' => $_POST['group_name'],
				'channel' =>'',
				'type' => 'screenshare',
				'created_by' => $this->login_id				
			);
			$this->db->insert('dgt_chat_group_details',$da);
			$group_id = $this->db->insert_id();

			$member = explode(',',$_POST['members']);
			for ($i=0; $i <count($member) ; $i++) { 
				$user = $this->db->get_where('dgt_users',array('username'=>$member[$i],'status'=>1))->row_array();
				if($user){
					$sinch_usernames[]=$user['sinch_username'];
					$datas = array(
						'group_name' => $_POST['group_name'],
						'from_id' => $this->login_id,
						'to_id' => $user['login_id'],
							//'status' => 'invited',
						'created_date' => date('Y-m-d H:i:s')
					);
					$this->db->insert('dgt_screen_share_details',$datas);	

					/*Add group member details */
					$datat = array(
						'group_id' => $group_id,
						'login_id' => $user['login_id'],
						'created_by' => $this->login_id
					);
					$this->db->insert('dgt_chat_group_members',$datat);	

				}
			}


			$result = array(
				'success'=>'Group created successfully!',
				'group_name' => ucfirst($_POST['group_name']),
				'url' => '',
				'fromId' => $this->login_id
			);
		}
		echo json_encode($result);

	}

	public function request_share() {

		/* Url to stop : POST https://api.screenleap.com/v2/screen-shares/{screenShareCode}/stop */
		
		$url = 'https://api.screenleap.com/v2/screen-shares';
		$ch = curl_init($url); curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('authtoken:LkoSIriJbW'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'accountid=dreamguys_technologies');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Whether you need the following line depends on your curl configuration.
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$screenShareData = curl_exec($ch);
		curl_close($ch);
		echo json_encode($screenShareData);
	}

	public function update_share(){


		$data['sender_id'] = $this->login_id;
		$data['time_zone'] = $this->session->userdata('time_zone');
		$data['chatdate'] = date('Y-m-d H:i:s');
		$data['message'] = 'Click the link to view the  Sharing screen <br><a data-src="'.$_POST["url"].'" href="'.$_POST["url"].'" target="blank">'.$_POST["url"].'</a>';


		if(!empty($_POST['group_id'])){

		// Saving the URL 
			$dt = array('screen_share_url'=> $_POST["url"]);
			$where = array('group_id'=> $_POST["group_id"]);
			$result = $this->db->update('dgt_chat_group_details',$dt,$where);

			$data['receiver_id'] = 0;		
		// $data['message_type'] = (!empty($_POST['group_id']))?'group':'text';
			$data['message_type'] = 'group';
			$data['group_id'] = (!empty($_POST['group_id']))?$_POST['group_id']:'';
			$result = $this->db->insert('chat_details',$data);
			$chat_id = $this->db->insert_id();

			$users = array($data['receiver_id'],$data['sender_id']);
			$wher = array('login_id !='=>$this->login_id ,'group_id'=>$data['group_id']);
			$userid_list = $this->db->select('login_id')->get_where('dgt_chat_group_members',$wher)->result_array();
			if(!empty($userid_list)){
				foreach($userid_list as $u){
					$insert_data = array('group_id'=>$data['group_id'],'login_id'=>$u['login_id']);
					$this->db->insert('dgt_chat_seen_details',$insert_data);
				}
			}
		}else{


			$data['receiver_id'] =$_POST['receiver_id'];		
			$data['message_type'] = 'text';
			$data['group_id'] = '';
			$result = $this->db->insert('chat_details',$data);
			$chat_id = $this->db->insert_id();
			$users = array($data['receiver_id'],$data['sender_id']);
			for ($i=0; $i <2 ; $i++) { 
				$datas = array('chat_id' =>$chat_id ,'can_view'=>$users[$i]);
				$this->db->insert('chat_deleted_details',$datas);
			}



		}

		
		
		
		echo json_encode($result);
	}
	public function get_users_by_name(){
		$users_record = array();
		$username = $this->input->post('user_name');
		$data = $this->chats_model->get_users_by_name($username);
		if(!empty($data)){
				if(count($data) > 1 )
				{
					$msg_type = 'group';
				}else{
					$msg_type = 'one';
				}

			foreach($data as $u){
				// $res = array(
				// 	'from_id' => $this->login_id,
				// 	'to_id'	  => $u['user_id'],
				// 	'message' => '',
				// 	'group_id'=> '0',
				// 	'msg_type'=> $msg_type,
				// 	'read_status'=> 1,
				// 	'msg_date'=>date('Y-m-d H:i:s')
				// );
				// $this->db->insert('dgt_chat_conversations',$res);

				$destination = $this->db->get_where('designation',array('id'=>$u['designation_id']))->row_array();
				$datas['first_letter'] = ucfirst(substr($u['fullname'], 0, 1));
				$datas['fullname'] = ucfirst($u['fullname']);
				$datas['username'] = $u['username'];
				$datas['email'] = $u['email'];
				$datas['phone'] = $u['phone']?$u['phone']:'-';
				$datas['login_id'] = $u['user_id'];
				$datas['base_url'] = base_url();
				$datas['last_login'] = $u['last_login'];
				$datas['pro_pic'] = $u['avatar'];
				$datas['online_status'] = $u['online_status'];
				$datas['destination'] = $destination['designation'];
				$users_record[] =$datas;
			}

		}		
		echo json_encode($users_record,true); exit;
	}
	public function get_old_messages(){

		if($_POST['total']<0){
			return false;
		}else{
			$total = $_POST['total'];
			$total = $total * 5;
		}

		$latest_chats= $this->chat->get_latest_chat($total);  


		$html='';
		if(!empty($latest_chats)){

			foreach($latest_chats as $l){
				$sender_name = $l['sender_name'];
				$sender_profile_img = (!empty($l['sender_profile_image']))?base_url().'uploads/'.$l['sender_profile_image'] : base_url().'assets/img/user.jpg';
				$msg = $l['message'];
				$type = $l['type'];
				$file_name = base_url().$l['file_path'].'/'.$l['file_name'];
				$time = date('h:i A',strtotime($l['created_at']));
				$up_file_name =$l['file_name'];

				if($l['sender_id'] == $this->login_id){
					$class_name = 'chat-right';
					$img_avatar='';
				}else{
					$img_avatar = '<div class="chat-avatar">
					<a href="#" class="avatar">
					<img alt="'.$sender_name.'" src="'.$sender_profile_img.'" class="img-fluid rounded-circle">
					</a>
					</div>';
					$class_name = 'chat-left';
				}
				if($msg == 'file' && $type == 'image'){

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content img-content">
					<div class="chat-img-group clearfix">
					<a class="chat-img-attach" href="'.$file_name.'" target="_blank">
					<img width="182" height="137" alt="" src="'.$file_name.'">
					<div class="chat-placeholder">
					<div class="chat-img-name">'.$up_file_name.'</div>
					</div>
					</a>
					</div>
					<span class="chat-time">'.$time.'</span>
					</div>               
					</div>
					</div>
					</div>';

				}else if($msg == 'file' && $type == 'others'){

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content "><ul class="attach-list">
					<li><i class="fa fa-file"></i><a href="'.$file_name.'">'.$up_file_name.'</a></li>
					</ul>
					<span class="chat-time">'.$time.'</span>       
					</div>
					</div>
					</div>
					</div>';

				}else{

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content">
					<p>'.$msg.'</p>         
					<span class="chat-time">'.$time.'</span>
					</div>
					</div>
					</div>
					</div>';
				}														
			}
			$html .='<div class="ajax"></div><input type="hidden"  id="hidden_id">';
		}

		echo $html;


	}




	public function get_call_notification(){
		$result = array('status'=>false);

		/* Checking any call for login user  */
		$wh = array('id'=>$this->login_id);
		$data = $this->db->get_where('dgt_users',$wh)->row_array();
		if($data['call_status'] == 1){
			echo json_encode($result);
			exit;
		}

		$where = array('to_id'=>$this->login_id);
		$data = $this->db->get_where('dgt_call_notification',$where)->row_array();
		// print_r($data); exit;
		if(!empty($data)){			

			/*  one to one call */
			if($data['type'] == 'one'){

				$wheres = array('id'=>$data['from_id']);
				$response = $this->db->select('id,username,online_status')
				->get_where('dgt_users',$wheres)->row_array();
				$profile_pic = $this->db->get_where('account_details',array('user_id',$response['id']))->row_array();
				// print_r($profile_pic); exit;
				$response['fullname'] = $profile_pic['fullname'];
				$response['profile_pic'] = $profile_pic['avatar'];
				$response['group_id'] = $data['group_id'];	
				$response['type'] = 'one';	
				$response['call_type'] = $data['call_type'];

			}else{
				/* Group Details */
				$wheres = array('group_id'=>$data['group_id']);
				$response = $this->db->get_where('chat_group_details',$wheres)->row_array();			
				/* Group Members Details */

					// $this->db->select('l.sinch_username,l.login_id,l.profile_img,l.first_name,l.last_name, cg.members_id,l.online_status');
					// $this->db->where('cg.group_id',$data['group_id']);
					// $this->db->where('cg.login_id !=',$this->login_id);
					// $this->db->join('login_details l','l.login_id = cg.login_id');
					// $response['group_members']=  $this->db->get('chat_group_members cg')->result_array();
					$response['type'] = 'many';	
					$response['call_type'] = $data['call_type'];

			}
			
			$result=array('status'=>true,'data'=>$response);
		}
		// print_r($result); exit;
		echo json_encode($result); exit;
	}

	public function get_dummy_datas(){

		// print_r($_POST); exit;
		$result = array();
		$data = array();	
		// print_r($this->session->userdata()); exit;

		if(!empty($this->session->userdata('dummy_session_id'))){
			$my_token = $this->session->userdata('dummy_token');
			$sessionId = $this->session->userdata('dummy_session_id');
			$group_id = $this->session->userdata('dummy_group_id');				

			/* Update the user is in call */				

			$this->db->update('dgt_users',array('call_status'=>1,'group_id'=>$group_id),array('id'=>$this->login_id));



		}else{

			$opentok = new OpenTok($this->apiKey, $this->apiSecret);

			$group_id = $_POST['group_id'];

			/* Update the user is in call */				
$this->db->update('dgt_users',array('call_status'=>1,'group_id'=>$group_id),array('id'=>$this->login_id));

	// echo $_POST['group_id']; exit;
			$where = array('dgt_chat_group_details.group_id' => $_POST['group_id'],'dgt_chat_group_members.login_id'=>$this->login_id);
			$data = $this->db->select('dgt_chat_group_details.session_id,dgt_chat_group_members.token,dgt_chat_group_details.group_id')
			->join('dgt_chat_group_members dgt_chat_group_members','dgt_chat_group_members.group_id = dgt_chat_group_details.group_id')
			->get_where('dgt_chat_group_details',$where)
			->row_array();
		// echo $this->db->last_query(); exit;

			if(!empty($data)){

				$sessionId = $data['session_id'];
				$name = $this->session->userdata('username');

				$responses['first_name'] = $this->session->userdata('username');
				$responses['last_name'] = $this->session->userdata('username');
				$responses['sinch_username'] = $this->session->userdata('username');
				$responses['login_id'] = $this->session->userdata('login_id');
				
									// Replace with meaningful metadata for the connection:
				$connectionMetaData = json_encode($responses);

					// Replace with the correct session ID:
				$my_token = $opentok->generateToken($sessionId,array('expireTime' => time()+(7 * 24 * 60 * 60), 'data' =>  $connectionMetaData));

				$where = array('group_id' => $data['group_id'],'login_id'=>$this->login_id);
				$this->db->update('dgt_chat_group_members',array('token'=>$my_token),$where);	



				$session_values = array('dummy_session_id' => $sessionId,'dummy_token'=>$my_token ,'dummy_group_id' =>$_POST['group_id']);
				
				$this->session->set_userdata($session_values);				
			}
		}

		$this->db->delete('dgt_call_notification',array('group_id' => $_POST['group_id']));

		$result = array('apiKey' => $this->apiKey,'sessionId' =>$sessionId , 'token' => $my_token,'dummy_group_id' =>$_POST['group_id']);
		echo json_encode($result); exit;

		
	}
	public function discard_notify(){
		echo $this->db->delete('dgt_call_notification',array('group_id' => $_POST['group_id']));
		exit;
	}


	public function get_chat_token(){

		// print_r($this->session->userdata()); exit;




		/*Check  Login User Already in a call */
		$user = $this->db
		->get_where('dgt_users',array('id'=>$this->login_id))
		->row_array();
// print_r($user); exit;
		if($user['call_status']==1){
			echo json_encode(array('error' => 'You are already in a call!'));
			exit;
		}

		/* Check it is a One to One call Or Group Call */

		// if(!empty($this->session->userdata('user_id'))){ /* One to One */		
		if(!empty($_POST['to_id'])){ /* One to One */		

			
			// echo "check"; exit;

		/* Check calling user is online or in a call   */
		$user =array();
		$session_chat_id = $_POST['to_id'];
		// $session_chat_id = $this->session->userdata('session_chat_id');
		// $session_chat_id = $this->session->userdata('user_id');
		// echo $session_chat_id; exit;

		$user = $this->db
		->get_where('dgt_users',array('id'=>$session_chat_id))
		->row_array();

		// print_r($user); exit;

		
		if($user['online_status']==0){ 
			echo json_encode(array('error' => $user['username'].' is offline'));
			exit;
		}elseif($user['call_status']==1){ 
			echo json_encode(array('error' => $user['username'].' already in a call'));
			exit;
		}	








		/* Call again in a same group  */

		if(!empty($this->session->userdata('dummy_session_id'))){
			// echo "test"; exit;
			$my_token = $this->session->userdata('dummy_token');
			$sessionId = $this->session->userdata('dummy_session_id');
			// if($my_token != '')
			// {
			// 	$my_token = $my_token;
			// }else{
			// 	$opentok = new OpenTok($this->apiKey, $this->apiSecret);
			//  	// Replace with the correct session ID:
			// 	$my_token = $opentok->generateToken($sessionId);
			// }
			// echo $my_token; exit;

			$group_id =  $this->session->userdata('dummy_group_id');
		

		/* Check group available or not */
		$count = $this->db->get_where('dgt_chat_group_details',array('status'=>1,'group_id'=>$group_id))->num_rows();
		if($count == 0){
			echo json_encode(array('error'=>'No group'));
			exit;
		}





			/* Update in login details */
			$this->db->update('dgt_users',array('call_status'=>1,'group_id'=>$group_id),array('id'=>$this->login_id));








		}else{
			// echo "hi"; exit;

			/* Make One to One new Call */ 			

			$result = array();
			$data = array();	

		

			/* Generate a Session Id  */

			$opentok = new OpenTok($this->apiKey, $this->apiSecret);
				// An automatically archived session:
			$sessionOptions = array(
				// 'archiveMode' => ArchiveMode::ALWAYS,
				'mediaMode' => MediaMode::ROUTED
			);
			$new_session = $opentok->createSession($sessionOptions);


				// Store this sessionId in the database for later use
			$sessionId = $new_session->getSessionId();
			// echo $sessionId; exit;





			/* Get count of group table */
			$data = $this->db
			->order_by('group_id','desc')
			->get('dgt_chat_group_details')->row_array();

			$group_id  = (!empty($data)? $data['group_id'] : 0 ); 
			$group_id++;

			$group_name = 'dummy'.$group_id;


			/* Insert Dummy entry in Group Table */
			$datas = array(
				'group_name' => $group_name,
				'type' => 'video',
				'session_id' => $sessionId,
				'created_by' => $this->session->userdata('user_id')
			);
			$this->db->insert('dgt_chat_group_details',$datas);
			$group_id = $this->db->insert_id();


				/* Update Login User as in a Call */				

			$this->db->update('dgt_users',array('call_status'=>1,'group_id'=>$group_id),array('id'=>$this->login_id));




			$login_details = 	$this->db
			->select('username,id')
			->get_where('dgt_users',array('id' => $this->login_id))
			->row_array();
			$login = json_encode($login_details);

			
			// Set some options in a token
			$my_token = $new_session->generateToken(array(
				'role'       => Role::MODERATOR
			// 'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
			// 'data'       => $login
		));
			// echo $my_token; exit;


			/* Insert Group members */
			$dat = array('group_id' => $group_id,'login_id' => $this->login_id,'token' => $my_token);
			$this->db->insert('dgt_chat_group_members',$dat);

			$session_values = array(
				'dummy_group_id' => $group_id,
				'dummy_group_name' => $group_name,
				'dummy_session_id' => $sessionId				
			);

			$this->session->set_userdata($session_values);

			$dats = array('group_id' => $group_id,'login_id' => $session_chat_id);
			$this->db->insert('dgt_chat_group_members',$dats);

			/* RINGING one to one call notitification to single user */
			$notify = array(
			'group_id' => $group_id,
			'from_id' => $this->login_id,
			'to_id' => $session_chat_id,						
			'call_type' => $_POST['type_of_call']
		);
			$this->db->insert('dgt_call_notification',$notify);


		}    		

			

		}elseif(!empty($this->session->userdata('session_group_id'))){ /* Group Call */

			$group_id = $this->session->userdata('session_group_id');




			$count = $this->db->get_where('dgt_chat_group_details',array('status'=>1,'group_id'=>$group_id))->num_rows();

		if($count == 0){
			echo json_encode(array('error'=>'No group'));
			exit;
		}


		// echo $this->session->userdata('session_group_id'); exit;


			/* Get the session id  from group  table */

			$sessionId = $this->db->get_where('dgt_chat_group_details',array('group_id'=>$group_id))->row()->session_id;


				$responses['username'] = $this->session->userdata('username');
				// $responses['last_name'] = $this->session->userdata('last_name');
				$responses['sinch_username'] = $this->session->userdata('username');
				$responses['login_id'] = $this->session->userdata('user_id');
				
									// Replace with meaningful metadata for the connection:
				$connectionMetaData = json_encode($responses);

// echo $connectionMetaData; exit;

			// /* Generate as token */
			$opentok = new OpenTok($this->apiKey, $this->apiSecret);
		 	// Replace with the correct session ID:
			$my_token = $opentok->generateToken($sessionId,array('expireTime' => time()+(7 * 24 * 60 * 60), 'data' =>  $connectionMetaData));


			 // Update in login details 
			$this->db->update('dgt_users',array('call_status'=>1,'group_id'=>$group_id),array('id'=>$this->login_id));


			$members = $this->db							
							->join('dgt_users','dgt_users.id = dgt_chat_group_members.login_id')
							->get_where('dgt_chat_group_members',array('dgt_chat_group_members.group_id'=>$group_id,'dgt_chat_group_members.login_id !=' =>$this->login_id))
							->result_array();

			if(!empty($members)){

				foreach ($members as $m) {

						/* Make call notification to group members */


						/* Check calling user is online or in a call   */
						$user =array();
						$user_login_id = $m['login_id'];

						$user = $this->db
						->get_where('dgt_users',array('id'=>$user_login_id))
						->row_array();


						if($user['online_status'] !=0 && $user['call_status']!=1){

							/* RINGING Group call notitification to  user */
							$notify = array(
							'group_id' => $group_id,
							'from_id' => $this->login_id,
							'to_id' => $user_login_id,
							'type' => 'group',
							'call_type' => $_POST['type_of_call']
							);
							$this->db->insert('dgt_call_notification',$notify);												
						}
				}
			}
		}

		$result = array('apiKey' => $this->apiKey,'sessionId' =>$sessionId , 'token' => $my_token ,'dummy_group_id' => $group_id);
		// print_r($result); exit;
		echo json_encode($result);	exit;

	}

	public function set_chat_user(){

	

		$this->session->set_userdata(array('session_group_id'=>''));
		$this->session->set_userdata(array('session_chat_id'=>$_POST['user_id']));
		// $this->session->set_userdata(array('session_chat_id'=>$_POST['login_id']));
		$latest_chats= $this->chat->get_latest_chat($total=null); 

		$total_chat= $this->chat->get_total_chat_count(); 

		$page=0;
		if($total_chat>5){
			$total_chat = $total_chat - 5;
			$page = $total_chat / 5;
			$page = ceil($page);
			$page--;
		}


		if(count($latest_chats)>4){

			$html ='<div class="load-more-btn text-center" total="'.$page.'">
			<button class="btn btn-default" data-page="2"><i class="fa fa-refresh"></i> Load More</button>
			</div><div class="ajax_old"></div>';      
		}else{
			$html ='';
		}


		if(!empty($latest_chats)){

			foreach($latest_chats as $l){
				$sender_name = $l['sender_name'];
				$sender_profile_img = (!empty($l['sender_profile_image']))?base_url().'uploads/'.$l['sender_profile_image'] : base_url().'assets/img/user.jpg';
				$msg = $l['message'];
				$type = $l['type'];
				$file_name = base_url().$l['file_path'].'/'.$l['file_name'];
				$time = date('h:i A',strtotime($l['chatdate']));
				$up_file_name =$l['file_name'];

				if($l['sender_id'] == $this->login_id){
					$class_name = 'chat-right';
					$img_avatar='';
				}else{
					$img_avatar = '<div class="chat-avatar">
					<a href="#" class="avatar">
					<img alt="'.$sender_name.'" title="'.ucfirst($sender_name).'" src="'.$sender_profile_img.'" class="img-fluid rounded-circle">
					</a>
					</div>';
					$class_name = 'chat-left';
				}
				if($msg == 'file' && $type == 'image'){

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content img-content">
					<div class="chat-img-group clearfix">
					<a class="chat-img-attach" href="'.$file_name.'" target="_blank">
					<img width="182" height="137" alt="" src="'.$file_name.'">
					<div class="chat-placeholder">
					<div class="chat-img-name">'.$up_file_name.'</div>
					</div>
					</a>
					</div>
					<span class="chat-time">'.$time.'</span>
					</div>               
					</div>
					</div>
					</div>';

				}else if($msg == 'file' && $type == 'others'){

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content "><ul class="attach-list">
					<li><i class="fa fa-file"></i><a href="'.$file_name.'">'.$up_file_name.'</a></li>
					</ul>
					<span class="chat-time">'.$time.'</span>       
					</div>
					</div>
					</div>
					</div>';

				}else{

					$html .= '<div class="chat '.$class_name.'">'.$img_avatar.'
					<div class="chat-body">
					<div class="chat-bubble">
					<div class="chat-content">
					<p>'.$msg.'</p>         
					<span class="chat-time">'.$time.'</span>
					</div>
					</div>
					</div>
					</div>';
				}														
			}

		}

		$html .='<div class="ajax"></div><input type="hidden"  id="hidden_id">';

		if($total_chat == 0){
			$html .='<div class="no_message"></div>';
		}






		$this->db->update('dgt_chat_details',array('read_status'=>1),array('receiver_id' => $this->login_id,'sender_id' =>$_POST['login_id']));

		$data = $this->db
		->select('l.phone_number,l.email,l.dob,l.first_name,l.last_name,l.login_id,l.online_status,l.sinch_username,l.profile_img,d.department_name')
		->join('dgt_department_details d','d.department_id = l.department_id','left')
		->get_where('dgt_users l',array('l.login_id'=>$_POST['login_id']))
		->row_array();

		$data['first_name'] = ucfirst($data['first_name']);
		$data['last_name'] =ucfirst($data['last_name']);
		$data['dob'] =(!empty($data['dob']) && $data['dob']!='0000-00-00')?date('d-m-Y',strtotime($data['dob'])):'N/A';

		if(empty($data['profile_img'])){
			$data['profile_img'] = base_url().'assets/img/user.jpg';
		}else{
			$data['profile_img'] = base_url().'uploads/'.$data['profile_img'];
		}
		$data['messages'] = $html;
		$data['call_history'] =$this->chat->get_call_history();
		echo json_encode($data);
	}

	public function insert_chat()
	{	

		  // echo '<pre>'; print_r($_POST); exit;
		if($_POST['message_type'] == 'group'){
				
			$data['receiver_id'] = 0;
			$data['sender_id'] = $this->login_id;
			$data['time_zone'] = $this->session->userdata('time_zone');
			$data['chatdate'] = date('Y-m-d H:i:s');
			$data['message'] = $_POST['message'];
			$data['message_type'] = 'group';
			$data['group_id'] = (!empty($_POST['group_id']))?$_POST['group_id']:'';
			$result = $this->db->insert('chat_details',$data);
			$chat_id = $this->db->insert_id();
			$users = array($data['receiver_id'],$data['sender_id']);
			$wher = array('login_id !='=>$this->login_id ,'group_id'=>$data['group_id']);
			$userid_list = $this->db->select('login_id')->get_where('dgt_chat_group_members',$wher)->result_array();
			if(!empty($userid_list)){
				foreach($userid_list as $u){
					$insert_data = array('group_id'=>$data['group_id'],'login_id'=>$u['login_id']);
					$this->db->insert('dgt_chat_seen_details',$insert_data);

					$datas = array('chat_id' =>$chat_id ,'can_view'=>$u['login_id'],'group_id'=>$_POST['group_id']);
					$this->db->insert('dgt_chat_deleted_details',$datas);


				}
			}	
			exit;	

		}elseif($_POST['message_type'] == 'text'){

			$data['receiver_id'] =$_POST['receiver_id'];
			$data['sender_id'] = $this->login_id;
			$data['time_zone'] = $this->session->userdata('time_zone');
			$data['chatdate'] = date('Y-m-d H:i:s');
			$data['message'] = $_POST['message'];
			$data['message_type'] = 'text';
			$data['group_id'] = 0;

			$result = $this->db->insert('chat_details',$data);
			$chat_id = $this->db->insert_id();
			$users = array($data['receiver_id'],$data['sender_id']);
			for ($i=0; $i <2 ; $i++) { 
				$datas = array('chat_id' =>$chat_id ,'can_view'=>$users[$i]);
				$this->db->insert('dgt_chat_deleted_details',$datas);
			}
		}
		echo json_encode($users);

	}
	public function delete_conversation()
	{

	// echo '<pre>'; print_r($_POST);

		$selected_user = $_POST['sender_id'];

		if(!empty($_POST['group_id'])){

			$this->db->delete('dgt_chat_seen_details',array('login_id'=>$this->login_id,'group_id' => $_POST['group_id'])); 
		}else{

			$data = $this->chat->deletable_chats();
			if(!empty($data)){
				foreach ($data as $d) {
					$this->db->delete('dgt_chat_deleted_details',array('chat_id'=>$d['chat_id'],'can_view'=>$this->login_id)); 
				}  
			}

		}

    // echo $this->db->last_query();
		echo '1';
	}


	Public function get_message_details()
	{

		// echo '<pre>'; print_r($_POST);exit;
		/*Receiver data */
		$data= $this->db
		->select('first_name,last_name,login_id,sinch_username')
		->get_where('dgt_users',array('sinch_username'=>$_POST['receiver_sinchusername']))
		->row_array();

		/*Message*/
		$msg['msg_data'] = $this->db
		->select('c.chat_id,c.message_type,c.message,c.receiver_id,c.sender_id,c.chatdate,c.file_path,c.file_name,
			c.read_status,
			c.time_zone,
			c.type,
			c.status'

		)
		->order_by('c.chat_id','desc')		
		->get_where('dgt_chat_details c',array('c.sender_id'=>$data['login_id']))
		->row_array();



		if($msg['msg_data']['message_type'] == 'group'){
			$msg['msg_data'] = '';
			$msg['msg_data'] = $this->db
			->select('c.chat_id,c.group_id,c.message_type,c.message,c.receiver_id,c.sender_id,c.chatdate,c.file_path,c.file_name,
				c.read_status,
				c.time_zone,
				c.type,
				c.status,
				g.group_name'
			)
			->order_by('c.chat_id','desc')
			->join('dgt_chat_group_details g','g.group_id = c.group_id')
			->get_where('dgt_chat_details c',array('c.sender_id'=>$data['login_id']))
			->row_array();
			$msg['msg_data']['group_name'] = ucfirst($msg['msg_data']['group_name']);
			$msg['msg_data']['new_group_name'] = str_replace(' ','_',$msg['msg_data']['group_name']);




			$where = array('login_id'=>$this->login_id ,'read_status'=>'0','group_id' =>$msg['msg_data']['group_id']);
			$msg['count'] = $this->db
			->get_where('dgt_chat_seen_details',$where)
			->num_rows();		


		}else{


			$where = array('sender_id'=>$data['login_id'] ,'receiver_id' =>$this->login_id,'read_status'=>0,'message_type' =>'group');
			$msg['count'] = $this->db
			->get_where('dgt_chat_details',$where)
			->num_rows();

			$where = array('c.sender_id'=>$data['login_id'] ,'c.receiver_id' =>$this->login_id,'c.read_status'=>0,'c.message_type' =>'group');


			$this->db->update('dgt_chat_details',array('read_status'=>1,'message_type'=>'text'),array('chat_id'=>$msg['msg_data']['chat_id']));		

		}




		$msg['reciever_data'] = $data;
		echo json_encode($msg);
	}


	Public function get_user_details(){

		//echo '<pre>';print_r($_POST);exit;
		$data = array();
		$where =array('sinch_username'=>$_POST['receiver_sinchusername']);
		$data= $this->db
		->select('first_name,last_name,login_id,sinch_username,online_status,profile_img')
		->get_where('dgt_users',$where)
		->row_array();

		$data['profile_img'] = (!empty($data['profile_img']))?base_url().'uploads/'.$data['profile_img']:base_url().'assets/img/user.jpg';

		$data['first_name'] = ucfirst($data['first_name']); 
		$data['last_name'] = ucfirst($data['last_name']); 	


		if($_POST['message_type'] == 'group'){




			$where = array(
				'c.sender_id'=>$data['login_id'],
				'receiver_id' =>0,
				'c.read_status'=>0,
				'c.message_type' => $_POST['message_type']
			);
			$data['message'] = $this->db
			->order_by('chat_id','desc')
			->join('dgt_chat_group_details cg','c.group_id = cg.group_id ')
			->get_where('dgt_chat_details c',$where)
			->row_array();


			$data['message']['group_name'] = ucfirst($data['message']['group_name']);
			$data['message']['new_group_name'] = str_replace(' ','_',$data['message']['group_name']);
			$group_id = $_POST['group_id'];

		// echo '<pre>';
		// print_r($data['message']);


			$sinch_users=array();

			$wheres = array('g.new_user' => 1 ,'g.group_id' => $group_id);
			$sinch_users=$this->db
			->select('l.sinch_username,l.first_name,l.last_name,l.login_id,l.profile_img')
			->join('dgt_users l','l.login_id = g.login_id')
			->get_where('dgt_chat_group_members g',$wheres)
			->result_array();
			$data['message']['new_group_members']=$sinch_users;

		// echo '<pre>';
		// print_r($data);
		// exit;

			$where = array(
				'login_id'=>$this->login_id ,
				'group_id' =>$group_id,
				'read_status'=>'0'				
			);
			$data['count'] = $this->db
			->get_where('dgt_chat_seen_details',$where)
			->num_rows();


		}else{


			$where = array('sender_id'=>$data['login_id'] ,'receiver_id' =>$this->login_id,'read_status'=>0,'message_type' => $_POST['message_type']);
			$data['count'] = $this->db
			->get_where('dgt_chat_details',$where)
			->num_rows();



			$where = array('sender_id'=>$data['login_id'] ,'receiver_id' =>$this->login_id,'read_status'=>0,'message_type' => $_POST['message_type']);
			$data['message'] = $this->db
			->order_by('chat_id','desc')
			->get_where('dgt_chat_details',$where)
			->row_array();


		}




		// $data['msg'] = $this->db->get_where('chat_details',array('chat_id',$_POST['']))->row_array();

		echo json_encode($data);
	}

	public function upload_files()
	{

		$path = "uploads/".$this->login_id;
		if(!is_dir($path)){
			mkdir($path);
		}

		$target_file =$path . basename($_FILES["userfile"]["name"]);
		$file_type = pathinfo($target_file,PATHINFO_EXTENSION);

		if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" ){
			$type = 'others';
		}else{
			$type = 'image';
		}


		$config['upload_path']   = './'.$path;
		$config['allowed_types'] = '*';		
		$this->load->library('upload',$config);

		if($this->upload->do_upload('userfile')){	
			$file_name=$this->upload->data('file_name');	

			if($_POST['message_type'] == 'text'){

				$data = array(
					'receiver_id' =>$_POST['receiver_id'],
					'sender_id' => $this->login_id,
					'message' =>'file',
					'file_name'=>$file_name,		
					'chatdate' => date('Y-m-d H:i:s'),
					'type' =>$type,
					'read_status' =>0,
					'time_zone' =>$this->session->userdata('time_zone'),
					'file_path' => $path,
					'message_type' =>'text'				
				);			

				$result = $this->db->insert('chat_details',$data);
				$chat_id = $this->db->insert_id();
				$users = array($data['receiver_id'],$data['sender_id']);
				for ($i=0; $i <2 ; $i++) { 
					$datas = array('chat_id' =>$chat_id ,'can_view'=>$users[$i]);
					$this->db->insert('dgt_chat_deleted_details',$datas);
				}

			}else{

				$data = array(
					'group_id' =>$_POST['group_id'],
					'receiver_id' =>0,
					'sender_id' => $this->login_id,
					'message' =>'file',
					'file_name'=>$file_name,		
					'chatdate' => date('Y-m-d H:i:s'),
					'type' =>$type,
					'message_type'=>'group',
					'read_status' =>0,
					'time_zone' =>$this->session->userdata('time_zone'),
					'file_path' => $path				
				);			

				$result = $this->db->insert('dgt_chat_details',$data);



			}


			echo  json_encode(array('img'=>$path.'/'.$file_name,'type'=>$type,'file_name' => $file_name));
		}else{
			echo  json_encode(array('error'=>$this->upload->display_errors()));
		}
	}


	public function get_messages()
	{

		$selected_user = $_POST['selected_user_id'];
		$latest_chat= $this->chat->get_latest_chat($selected_user,$session_id);  
		$total_chat= $this->chat->get_total_chat_count($selected_user,$session_id);  

		// echo '<pre>'; 
		// print_r($latest_chat); 
		// exit;


		if($total_chat>5){
			$total_chat = $total_chat - 5;
			$page = $total_chat / 5;
			$page = ceil($page);
			$page--;
		}



  // echo $this->db->last_query();
  // exit;

		if(count($latest_chat)>4){

			$html ='<div class="load-more-btn text-center" total="'.$page.'">
			<button class="btn btn-default" data-page="2"><i class="fa fa-refresh"></i> Load More</button>
			</div><div class="ajax_old"></div>';      
		}else{
			$html ='';
		}



		if(!empty($latest_chat)){
			foreach($latest_chat as $key => $currentuser) : 


				$class_name =($currentuser['sender_id'] != $session_id) ? 'chat-left' : '';
				$user_image = ($currentuser['senderImage']!= '') ? base_url().'assets/images/'.$currentuser['senderImage']: base_url().'assets/images/default-avatar.png';


				if($currentuser['senderImage']!=''){
					$img =  base_url().'assets/images/'.$currentuser['senderImage'];
				}elseif($currentuser['socialImage']!=''){
					$img = $currentuser['socialImage'];
				}else{
					$img = base_url().'assets/images/default-avatar.png';
				}


				$time_zone = $this->session->userdata('time_zone');
				$from_timezone = $currentuser['time_zone'];
				$date_time = $currentuser['chatdate'];
				$date_time  = $this->converToTz($date_time,$time_zone,$from_timezone);
				$time = date('h:i a',strtotime($date_time));


				if($currentuser['type'] == 'image'){

					$html .='<div class="chat '.$class_name.'">
					<div class="chat-avatar">
					<a title="" data-placement="right" href="#" data-toggle="tooltip" class="avatar" data-original-title="June Lane">
					<img  src="'.$img.'" class="img-fluid rounded-circle">
					</a>
					</div>
					<div class="chat-body">
					<div class="chat-content">
					<p><img alt="" src="'.base_url().$currentuser['file_path'].'/'.$currentuser['file_name'].'" class="img-fluid"></p>
					<p>'.$currentuser['file_name'].'</p>
					<a href="'.base_url().$currentuser['file_path'].'/'.$currentuser['file_name'].'" target="_blank" download>Download</a>
					<span class="chat-time">'.$time.'</span>
					</div>
					</div>
					</div>';

				}else if($currentuser['type'] == 'others'){

					$html .='<div class="chat '.$class_name.'">
					<div class="chat-avatar">
					<a title="" data-placement="right" href="#" data-toggle="tooltip" class="avatar" data-original-title="June Lane">
					<img  src="'.$img.'" class="img-fluid rounded-circle">
					</a>
					</div>
					<div class="chat-body">
					<div class="chat-content">
					<p><img alt="" src="'.base_url().'assets/images/download.png" class="img-fluid"></p>
					<p>'.$currentuser['file_name'].'</p>
					<a href="'.base_url().$currentuser['file_path'].'/'.$currentuser['file_name'].'" target="_blank" download class="chat-time">Download</a>
					<span class="chat-time">'.$time.'</span>
					</div>
					</div>
					</div>';


				}
				else if($currentuser['msg']=='ENABLE_STREAM' || $currentuser['msg']=='DISABLE_STREAM'){


				}else{
					$html .='<div class="chat '.$class_name.'">
					<div class="chat-avatar">
					<a title="" data-placement="right" href="#" data-toggle="tooltip" class="avatar" data-original-title="June Lane">
					<img  src="'.$img.'" class="img-fluid rounded-circle">
					</a>
					</div>
					<div class="chat-body">
					<div class="chat-content">
					<p>
					'.$currentuser['msg'].'
					</p>
					<span class="chat-time">'.$time.'</span>
					</div>
					</div>
					</div>';

				}




			endforeach;


		}
		$html .='<div class="ajax"></div><input type="hidden"  id="hidden_id">';

		if($total_chat == 0){
			$html .='<div class="no_message"></div>';
		}


		echo $html;

	}
	public function get_all_users(){
		$users = array();
		$data = $this->db->select('username,first_name,last_name')
		->get_where('dgt_users',array('type'=>'user','login_id !='=>$this->login_id))
		->result_array();
		if(!empty($data)){
			foreach($data as $d){
				$users[] = $d['username'];
				//$users['text'] = ucfirst($d['first_name']).' '.ucfirst($d['last_name']);
				//$datas[]=$users;
			}

		}
		echo json_encode($users);
		exit;
	}

	public function online_offline()
	{
		$to_id = $_POST['to_id'];
		$this->session->set_userdata('current_chat_user',$to_id);
		$res = $this->db->get_where('dgt_users',array('id'=>$to_id))->row_array();
		$chats = $this->chats_model->get_chat_messagesbyID($to_id,'one');
		// print_r($chats); exit;
		if($res['online_status'] == 0)
		{
			echo "offline";
			exit;
		}else{
			echo "online";
			exit;
		}
	}

	public function chatting()
	{
		if($this->tank_auth->is_logged_in()) {

			$log_id = $this->session->userdata('user_id');
			$log_check = $this->chats_model->user_checkById($log_id);
			if($log_check['session_id'] == '')
			{
				$opentok = new OpenTok($this->apiKey, $this->apiSecret);
					// An automatically archived session:
				$sessionOptions = array(
					// 'archiveMode' => ArchiveMode::ALWAYS,
					'mediaMode' => MediaMode::ROUTED
				);
				$new_session = $opentok->createSession($sessionOptions);
					// Store this sessionId in the database for later use
				$sessionId = $new_session->getSessionId();
				$res = array(
					'session_id' => $sessionId
				);

				$this->db->where('id',$log_id);
				$this->db->update('users',$res);
			}

			$this->load->module('layouts');

			$this->load->library('template');

			$this->template->title('Chats'); 

			$data['datepicker']   = TRUE;

			$data['form']         = TRUE; 

			$data['page']         = 'chating';  

			$data['chat_history'] = $this->chats_model->user_chat_history($this->tank_auth->get_user_id());

			/*echo $this->db->last_query();

			echo "<pre>";

			print_r($data['chat_history']);

			exit;*/

			$data['role']         = $this->tank_auth->get_role_id();

			$this->template

				 ->set_layout('users')

				 ->build('chating',isset($data) ? $data : NULL);

	    }else{

		   redirect('');	

		}
	}


	public function get_message_token()
	{
		$to_id = $this->input->post('to_id');
		// echo $to_id; exit;
		if($to_id != ""){
		$login_details = 	$this->db
		->select('username,id')
		->get_where('dgt_users',array('id' => $to_id))
		->row_array();
		// $login_details['from_id'] = $this->login_id;
		// $login_details['to_id'] = $this->input->post('to_id');
		$login = json_encode($login_details);
		}

		$from_details = $this->db->select('username,id')
						->get_where('dgt_users',array('id' => $this->login_id))
						->row_array();
		$froms = json_encode($from_details);

		$to_connection_details = $this->chats_model->check_connections($to_id);
		$from_connection_details = $this->chats_model->check_connections($this->login_id);

		// $common_session = $this->chats_model->get_common_session();

		// $sessionId = $common_session['common_session_id'];

		// $opentok = new OpenTok($this->apiKey, $this->apiSecret);

		// $login_token = $opentok->generateToken($sessionId,array('expireTime' => time()+(7 * 24 * 60 * 60), 'data' =>  $login));

		// $from_token = $opentok->generateToken($sessionId,array('expireTime' => time()+(7 * 24 * 60 * 60), 'data' =>  $froms));

		$result = array('login_id' => $this->login_id,'to_id' => $to_id,'username' =>$from_details['username']);

		echo json_encode($result); exit;
	}

	public function message_saving()
	{
		$inputs = $this->input->post();
		$from_id = $inputs['from_id'];
		$to_id = $inputs['to_id'];
		$connection_id = $inputs['connection_id'];
		$msg = $inputs['msge'];
		$msg_typ = $inputs['msg_typ'];
		$group_id = $inputs['group_id'];
		date_default_timezone_set('Asia/Calcutta'); 
		$msg_date = date('Y-m-d H:i:s');
		//print_r($_POST); exit;
		$count = count($_FILES['files']['name']);

		$images = array(); 
        if($count != 0){
        $config['upload_path']          = './assets/uploads/chats/';
        $config['allowed_types']        = 'gif|png|jpeg|jpg|pdf|doc|txt|docx|xls|zip|rar|xlsx|mp4|m4p|m4v|avi|wmv|flv|swf';
        $this->load->library('upload', $config);
        $image = array(); 
        $image =$_FILES;

        //print_r($image); exit;

        for ($i=0; $i<$count;$i++) {
//echo $image['files']['name'][$i];
            $_FILES['files']['name'] = $image['files']['name'][$i];
            $_FILES['files']['type'] = $image['files']['type'][$i];
            $_FILES['files']['tmp_name'] = $image['files']['tmp_name'][$i];
            $_FILES['files']['error'] = $image['files']['error'][$i];
            $_FILES['files']['size'] = $image['files']['size'][$i];
             
            if ( ! $this->upload->do_upload('files'))
            {
            	//echo $this->upload->display_errors();exit;
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('tokbox_error', $this->upload->display_errors());
               // redirect('chats');
                echo  $this->upload->display_errors();exit; 
            }
            else
            {
                $data = $this->upload->data();
                $images[] = $data['file_name'];
                
                
            }

        }
    }

    //print_r($images); exit;


            
		if(($from_id != 0) && ($to_id != 0)){
			
			
			// $from_check_connection = $this->chats_model->check_connections($from_id);
			// // print_r($from_check_connection); exit;
			// if($from_check_connection == ''){
			// 	$from_connections = array(
			// 		'user_id' => $from_id,
			// 		'connection_id' => $connection_id
			// 	);
			// 	$this->db->insert('dgt_chat_connectionids',$from_connections);
			// }

			$from_user_details = $this->db->get_where('account_details',array('user_id'=>$from_id))->row_array();
			$to_user_details = $this->db->get_where('account_details',array('user_id'=>$to_id))->row_array();
			/*update message status*/
			$upd = array('read_status' => 1);

			$this->db->where('to_id',$from_id);
			$this->db->where('from_id',$to_id);
			$this->db->update('dgt_chat_conversations',$upd);


			if($group_id != '')
			{
				$group_id = $group_id;
			}else{
				$group_id = 0;
			}
			$conversations = array(
				'from_id' => $from_id,
				'to_id'   => $to_id,
				'message' => $msg,
				'group_id' => $group_id,
				'msg_type' => $msg_typ,
				'msg_date' => $msg_date,
			);
			if(count($images)>0){
				$conversations['images'] = implode(',',$images);
			}
			$this->db->insert('dgt_chat_conversations',$conversations);
			//echo $this->db->last_query();exit;
			$insert_id = $this->db->insert_id();

			$res = array(
				'insert_id' => $insert_id,
				'from_user_pic' => $from_user_details['avatar'],
				'to_user_pic' => $to_user_details['avatar']
			);
			echo $insert_id; exit;
			// echo json_encode($res); exit;
		}else{

			//print_r($_POST);exit; 

			$from_user_details = $this->db->get_where('account_details',array('user_id'=>$from_id))->row_array();
			
			/*update message status*/
			// $upd = array('read_status' => 1);

			// $this->db->where('to_id',$from_id);
			// $this->db->where('from_id',$to_id);
			// $this->db->update('dgt_chat_conversations',$upd);

			if($group_id != '')
			{
				$upd = array('read_status' => 1,'updated_at'=>date('Y-m-d H:i:s'));
				
				$this->db->where('to_user_id',$from_id);
				$this->db->where('group_id',$group_id);
				$this->db->where('read_status',0);
				$this->db->update('dgt_chat_read_status',$upd);


			$group_id = $group_id;
			
			$conversations = array(
				'from_id' => $from_id,
				'to_id'   => '',
				'message' => $msg,
				'group_id' => $group_id,
				'msg_type' => $msg_typ,
				'msg_date' => $msg_date,
			);
			if(count($images)>0){
				$conversations['images'] = implode(',',$images);
			}
			$this->db->insert('dgt_chat_conversations',$conversations);
			//echo $this->db->last_query();exit;
			$insert_id = $this->db->insert_id();
			$members = explode(',',$_POST['group_members_ids']);
			$add =array();
			foreach($members as $mem_id){
				$add['message_id'] = $insert_id;
				$add['group_id'] = $group_id;
				$add['to_user_id'] = $mem_id;
				$add['read_status'] = 0;
				$add['created_at'] = date('Y-m-d H:i:s');
				$this->db->insert('dgt_chat_read_status',$add);
			}

			/*$res = array(
				'insert_id' => $insert_id,
				'from_user_pic' => $from_user_details['avatar'],
				'to_user_pic' => $to_user_details['avatar']
			);*/
			echo $insert_id; exit;
			}
			

		}
	}



	public function get_chat_msg_notification()
	{
		$result = array('status'=>false);

		/* Checking any call for login user  */
		$wh = array('id'=>$this->login_id);
		$login_details = $this->db->get_where('dgt_users',$wh)->row_array();

		$group_details = $this->chats_model->get_groupsByUserId($this->login_id);
		echo json_encode($group_details); exit;
	}


	public function chat_single_history()
	{

		$log_id = $this->login_id;
		if($this->session->userdata('current_chat_user') && (!$this->input->post('user_id') || $this->input->post('user_id') == "")){
			$user_id = $this->session->userdata('current_chat_user');
		}else{
			$user_id = $this->input->post('user_id');
			//echo $user_id;
		}
		if($user_id == ""){
			return false;
		}
		//$this->session->unset_userdata('session_group_id');
		$msg_details = $this->chats_model->chat_usersignle_history($user_id,$log_id);
		//echo $this->db->last_query();exit; 
		$upd = array('read_status' => 1);
		if($this->input->post('user_id')){
			$this->db->where('to_id',$log_id);
			$this->db->where('from_id',$user_id);
			$this->db->update('dgt_chat_conversations',$upd);
		}
		$message_content = "";
		foreach($msg_details as $msg_detail){
			if($msg_detail['message'] !='' || !empty($msg_detail['images']) ){
				if($msg_detail['message'] !=''){
				$message_content = $msg_detail['message'];
				}
				if(!empty($msg_detail['images'])){
					$images = explode(',', $msg_detail['images']);
					$message_content = "";

					$image_types = array('gif','png','jpeg','jpg');

					foreach ($images as $value) {
						$filename = base_url('assets/uploads/chats/').$value;
						$extension = pathinfo($filename)['extension']; 

						if(in_array($extension,$image_types)){
						$message_content .="<img src='".base_url('assets/uploads/chats/').$value."' width='100' height='100'>";
						}else{
							$message_content .="<a href='".base_url('assets/uploads/chats/').$value."' download>".$value."</a><br>";
						}
					}
					
				}
			if($msg_detail['from_id'] == $log_id)
			{
				$chat_class = 'chat-right';
			}else{
				$chat_class = 'chat-left';
			}
			$html .= "<div class='chat ".$chat_class."'>";
			if($chat_class == 'chat-left')
			{
				$html .= "<div class='chat-avatar'>
                                    <a title='John Doe' data-placement='right' data-toggle='tooltip' class='avatar'>
                                        <img alt='John Doe' src='images/user.jpg' class='img-fluid rounded-circle'>
                                    </a>
                                </div>";
			}
	            $html .= "<div class='chat-body'>
	                <div class='chat-bubble'>
	                    <div class='chat-content content_".$msg_detail['msg_id']."'>
	                        <p>".$message_content."</p>
	                        <span class='chat-time'>".date("H:i:s",strtotime($msg_detail['msg_date']))."</span>
	                    </div>";
	                    if($this->login_id == $msg_detail['from_id']){
	                  $html .=  "<div class='chat-action-btns'>
	                        <ul>
	                            <li><a href='#' class='del-msg DeleteChatMsgg' data-msgid='".$msg_detail['msg_id']."' data-userid='".$user_id."' title='Delete'><i class='fa fa-trash-o'></i></a>
	                           </li>
	                        </ul>
	                    </div>";
	                }
	                /*if($this->login_id == $msg_detail['from_id'] && !empty($msg_detail['images'])){
	                	$html .=  "<div class='chat-action-btns'>
	                        <ul>
	                            <li><a href='#' class='del-msg DeleteChatMsgg' data-msgid='".$msg_detail['msg_id']."' data-userid='".$user_id."' title='Delete'><i class='fa fa-trash-o'></i></a>
	                            <a href='#' class='edit-msg EditChatmsg' data-msgid='".$msg_detail['msg_id']."' data-message ='".$message_content."' data-userid='".$user_id."' title='Edit'><i class='fa fa-pencil'></i></a>
	                      		</li>
	                        </ul>
	                    </div>";

	                }*/
	              $html .=  "</div>
	            </div>
	        </div>";
		}else{
			$html ='';
		}
		}
        	print_r($html); exit;
	}

	public function chat_single_delete()
	{
		$msg_id = $this->input->post('msg_id');
		$this->db->where('msg_id',$msg_id);
		$res = $this->db->delete('dgt_chat_conversations');
		if($this->input->post('group_id')){
			$this->db->where('message_id',$msg_id);
			$this->db->where('group_id',$this->input->post('group_id'));
			$res1 = $this->db->delete('dgt_chat_read_status');

		}
		echo $this->db->last_query();
		echo "success"; exit;
	}

	public function group_chat_userlist()
	{
		
		$log_id = $this->login_id;
		if($this->session->userdata('session_group_id') && (!$this->input->post('group_id') || $this->input->post('group_id')=="")){
			$group_id = $this->session->userdata('session_group_id');
		}else{
			$group_id = $this->input->post('group_id');
		}
		if($group_id==""){
			return false;
		}
		//$this->session->unset_userdata('current_chat_user');
		
		$this->session->set_userdata('session_group_id',$group_id);
		$msg_details = $this->chats_model->chat_usergroup_history($group_id);
		//$all_group_members = $this->chats_model->chatgroup_members_list($group_id);
		$all_group_members = $this->chats_model->chatgroup_members($group_id);

		// print_r($all_group_members); exit; 
		foreach ($all_group_members as $key => $members) {
			$member_name[$members['user_id']] = $members['fullname'];
		}

		//print_r($member_name);exit;
		$where['group_id'] = $group_id;
		$where['to_user_id'] = $log_id;
		$where['read_status'] = 0;
		$upd['read_status'] = 1;
		$upd['updated_at'] = date('Y-m-d H:i:s');
		$this->db->update('dgt_chat_read_status',$upd,$where);	


		$message_content = "";
		foreach($msg_details as $msg_detail){

			if($msg_detail['message'] !=''){
				$message_content = $msg_detail['message'];
				}
				if(!empty($msg_detail['images'])){
					$images = explode(',', $msg_detail['images']);
					$message_content = "";
					$image_types = array('gif','png','jpeg','jpg');

					foreach ($images as $value) {
						$filename = base_url('assets/uploads/chats/').$value;
						$extension = pathinfo($filename)['extension']; 

						if(in_array($extension,$image_types)){
						$message_content .="<img src='".base_url('assets/uploads/chats/').$value."' width='100' height='100'>";
						}else{
							$message_content .="<a href='".base_url('assets/uploads/chats/').$value."' download>".$value."</a><br>";
						}
					}
					
				}
				if(isset($member_name[$msg_detail['from_id']])){
					$msg_username = "<span class='chat-time text-info'>".$member_name[$msg_detail['from_id']]."</span>";
				}else{
					$msg_username = "<span class='chat-time text-warning'>removed user</span>";

				}
			if(($msg_detail['from_id'] == $log_id))
			{
				$chat_class = 'chat-right';
			}else{
				$chat_class = 'chat-left';
			}
			$html .= "<div class='chat ".$chat_class."'>";
			if($chat_class == 'chat-left')
			{
				$html .= "<div class='chat-avatar'>
                                    <a href='profile.html' title='John Doe' data-placement='right' data-toggle='tooltip' class='avatar'>
                                        <img alt='John Doe' src='images/user.jpg' class='img-fluid rounded-circle'>
                                    </a>
                                </div>";
			}
	            $html .= "<div class='chat-body'>
	                <div class='chat-bubble'>
	                    <div class='chat-content'>
	                        <p>".$message_content."</p>
	                        ".$msg_username."
	                        <span class='chat-time'>".date("H:i:s",strtotime($msg_detail['msg_date']))."</span>
	                    </div>";
	                   if(($msg_detail['from_id'] == $log_id)){
	                   $html .= " <div class='chat-action-btns'>
	                        <ul>
	                            <li><a href='#' class='del-msg DeleteChatMsgggroup' data-msgid='".$msg_detail['msg_id']."' data-grpid='".$msg_detail['group_id']."'  title='Delete'><i class='fa fa-trash-o'></i></a></li>
	                        </ul>
	                    </div>";
	                	}
	                $html .= "</div>
	            </div>
	        </div>";
		}
            print_r($html); exit;
	}

	public function group_user_grouplist()
	{
		$group_id = $this->input->post('group_id');
		//$all_group_members = $this->chats_model->chatgroup_members_list($group_id);
		$all_group_members = $this->chats_model->chatgroup_members($group_id);

		$i=1;
		foreach($all_group_members as $all_group_member)
		{
			$html .= "<li>
                         <a href='#' data-toggle='tooltip' title='' data-original-title='".$all_group_member['fullname']."'><!--<img src='".base_url().'assets/avatar/'.$all_group_member['avatar']."' alt='".$all_group_member['fullname']."'>-->".$all_group_member['fullname']."</a>
                    </li>";
                    $i++;
		}
		print_r($html); exit;
	}

	public function grouplist_receiver()
	{
		$group_id = $this->input->post('group_id');
		$user_id = $this->session->userdata('user_id');
		//$all_groups = $this->chats_model->chatgroup_members_list($group_id);
		$all_groups = $this->chats_model->chatgroup_members($group_id);
		$receivers = array();
		foreach($all_groups as $all_group){
			if($all_group['user_id'] != $user_id)
			{
				$receivers[] = $all_group['user_id'];
			}
		}
		// print_r($receivers); exit;
		$r = implode(',',$receivers);
		print_r($r); exit;

		
	}

	function create_groups(){
		//print_r($_POST); exit; 
		$this->load->library(array('form_validation'));
	if($_POST){
		$this->form_validation->set_rules('group_name', '', 'required');
		$this->form_validation->set_rules('members[]', '', 'required');

		if ($this->form_validation->run())
		{
			$ins['group_name'] = $this->input->post('group_name');
			$ins['status'] = 1;
			$ins['type'] = 'text';
			$ins['created_by'] = $this->session->userdata('user_id');
			$this->db->insert('dgt_chat_groups',$ins);
			$group_id = $this->db->insert_id();

			foreach ($this->input->post('members') as $member) {
				$ins1['group_id'] = $group_id;
				$ins1['user_id'] = $member;
				$this->db->insert('dgt_chat_group_users',$ins1);
			}
			 $this->session->set_flashdata('tokbox_success', lang('group_created_successfully'));
			redirect('chats');
		}else{
			 $this->session->set_flashdata('tokbox_error', lang('group_name_or_groupmember_empty'));
			redirect('chats');
		}
	}
	}

	function edit_group(){
		//echo $this->uri->segment(3);exit; 
		$this->load->library(array('form_validation'));
		if($_POST){

		$this->form_validation->set_rules('group_name', '', 'required');
		$this->form_validation->set_rules('members[]', '', 'required');

		if ($this->form_validation->run())
		{
			$upd['group_name'] = $this->input->post('group_name');
			$upd['updated_by'] = $this->session->userdata('user_id');
			$upd['updated_at'] = date('Y-m-d');
			$where['id'] = $this->input->post('group_id');
			$this->db->update('dgt_chat_groups',$upd,$where);
			$group_id = $this->input->post('group_id');
			
			$this->db->where('group_id',$this->input->post('group_id'));
			$this->db->delete('dgt_chat_group_users');

			foreach ($this->input->post('members') as $member) {
				$ins1['group_id'] = $group_id;
				$ins1['user_id'] = $member;
				$this->db->insert('dgt_chat_group_users',$ins1);
			}
			 $this->session->set_flashdata('tokbox_success', lang('group_updated_successfully'));
			redirect('chats');
		}else{
			 $this->session->set_flashdata('tokbox_error', lang('group_name_or_groupmember_empty'));
			redirect('chats');
		}


		}else{

		$data['group_id'] = $this->uri->segment(3);
		$where['group_id'] =$this->uri->segment(3); 
		$group_members = $this->chats_model->get_groups_users($where);
		$group_members_ids = array();
		foreach ($group_members->result() as  $member) {
			$group_name = $member->group_name;
			$group_id = $member->id;
			$group_members_ids[] = $member->user_id;
		}

		$data['group_data'] =array('group_id'=>$group_id,'group_name'=>$group_name,'members'=>$group_members_ids);
		// print_r($data['group_data']);exit; 
		$data['all_users'] = $this->chats_model->get_all_users(array('status'=>1));
		$this->load->view('modal/edit_group',$data);
		}
	}





	

 

} 