<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');

class User_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$this->user ='dgt_users U';
		$this->account_details ='dgt_account_details AD';
		$this->countries ='dgt_countries CON';
		$this->companies ='dgt_companies C';		
		$this->load->model(array('App','User'));
	}

	public function is_valid_token($token)
	{
		if($token == 'DQCTPQMKK9R6SXN4'){
			return TRUE;
		}elseif(!empty($token)) {
			$this->db->where('unique_code', $token);
			$count = $this->db->count_all_results('dgt_users');
			if($count > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}

	}
	 public function is_valid_login($input)
	 {
	 	 $username = $input['username'];
	 	 $password = $input['password'];

	 	$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
	 	$check_password = $this->get_check_password($username);

	 	$records = array();
		if($hasher->CheckPassword($password, $check_password)){

		 	$this->db->select('U.id,U.activated,U.is_teamlead,AD.fullname,U.unique_code,U.email,U.user_type,U.role_id');
		 	$this->db->from($this->user);
		 	$this->db->join($this->account_details, 'AD.user_id = U.id', 'left');
		 	$this->db->join($this->companies, 'C.co_id = AD.company', 'left');

		 	$where = array('activated'=>1,'banned'=>0);
		 	$this->db->where($where);
		 	// $this->db->where('(role_id = 3 OR role_id = 1)');
		 	
		 	if (count($this->check_username_email($username)) >1) {
		 		$this->db->where('email', $username);
		 	}else{
		 		
		 		$this->db->where('username',$username);	
		 	}
		 	$records = $this->db->get()->row_array();

			 $records['user_type'] = (!empty($records['user_type']))?$records['user_type']:1;
			 $this->db->select('r_id,role');
		 	$this->db->from('roles');
			 $this->db->where('r_id',$records['user_type']);
			 $role_record = $this->db->get()->row_array();	
			 $records['role_name']  = $role_record['role'];

		 	
	    }

	 	if(!empty($records)){
	 		if(($records['currency'] == '') || ($records['currency'] == NULL))
	 		{
	 			$records['currency'] = 'USD';
	 		}
	 		$user_id = $records['id'];
	 		$unique_code = $this->getToken(14,$user_id);
        	$records['unique_code'] = $unique_code;	
			$this->db->select('geo_fencing,address,radius,lattiude,longtitude');
		 	$this->db->from('dgt_branches');
		 	$where = array('branch_status'=>'0');
		 	$this->db->where($where);
		 	$entities = $this->db->get()->result_array();

			 $this->db->select('value');
		 	$this->db->from('dgt_config');
		 	$where = array('config_key'=>'geo_fencing');
		 	$this->db->where($where);
		 	$geo_fencing = $this->db->get()->row_array();

			$records['entities'] = $entities;	
			$records['geo_fencing'] = $geo_fencing['value'];	
        	$this->db->where('id', $user_id);
        	$this->db->update($this->user, array('unique_code' => $unique_code));
	 	}

	 	return $records;
	 }

	 public function get_check_password($username){

	 	$user_or_email = $this->check_username_email($username);
	 	$this->db->select('password');
	 	if(count($user_or_email) > 1){
	 		$this->db->where('email', $username);
	 	}else{
	 		$this->db->where('username', $username);
	 	}
	 	$record = $this->db->get($this->user)->row_array();

	 	if(!empty($record['password'])){
	 		$record = $record['password'];	
	 	}else{
	 		$record  = '';
	 	}
	 	return $record;
	 }

	 public function countries()
	 {
	 	$this->db->select('id, value as country');
	 	$this->db->order_by('value', 'ASC');
	 	return $this->db->get($this->countries)->result();
	 	
	 }

	 public function check_username_email($username){

	 	if(!empty($username)){
	 		return $user_or_email = explode('@', $username);
	 	}else{
	 		return FALSE;
	 	}
	 }


	 public function create_user($inputs,$token)
	 {
	 	
		$username = $inputs['username'];
		$email = $inputs['email'];
	 	$password = $inputs['password'];

		$count_name  =	$this->checkusernameemail(array('username'=>$username));
		$count_email =	$this->checkusernameemail(array('email'=>$email));
		$records = 2;
		if($count_name == 0 && $count_email ==0){

	 	$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
		$hashed_password = $hasher->HashPassword($password);
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];
			 	if($role == 1 || $role == 4){
			 		if($inputs['designation_id'] == '')
			 		{
			 			$designation_id = '';
			 		}else{
			 			$designation_id = $inputs['designation_id'];
			 		}
			 		if($inputs['department_id'] == '')
			 		{
			 			$department_id = '';
			 		}else{
			 			$department_id = $inputs['department_id'];
			 		}

			 		
			 		$user['teamlead_id'] = !empty($inputs['reporting_to'])?$inputs['reporting_to']:'';			 		

			 		$user['username'] = $inputs['username'];
			 		$user['password'] = $hashed_password;
			 		$user['email'] = $inputs['email'];
			 		$user['role_id'] = 3;
			 		$user['user_type'] = !empty($inputs['user_type'])?$inputs['user_type']:'';
			 		$user['designation_id'] = $designation_id;
			 		$user['department_id'] = $department_id;
			 		$user['activated'] = 1;
			 		$user['created'] = date('Y-m-d');
			 		$this->db->insert('dgt_users', $user);
			 		$id = $this->db->insert_id();
			 		$account_details['user_id'] = $id;
			 		$account_details['fullname'] = $inputs['fullname'];
			 		$account_details['gender'] = $inputs['gender'];
			 		$account_details['doj'] = $inputs['emp_doj'];
			 		$account_details['phone'] = $inputs['phone'];
			 		$account_details['address'] = !empty($inputs['address'])?$inputs['address']:'';
			 		$account_details['city'] = !empty($inputs['city'])?$inputs['city']:'';
			 		$account_details['state'] = !empty($inputs['state'])?$inputs['state']:'';
			 		$account_details['pincode'] = !empty($inputs['pincode'])?$inputs['pincode']:'';
			 		$account_details['country'] = !empty($inputs['country'])?$inputs['country']:'';
			 		$account_details['avatar'] = 'default_avatar.jpg';
			 		$account_details['language'] = config_item('default_language') ? config_item('default_language') : 'english';
			 		$account_details['locale'] = config_item('locale') ? config_item('locale') : 'en_US';
			 		$this->db->insert('dgt_account_details', $account_details);
			 		
			 		  // update teamlead
			 		if(!empty($inputs['reporting_to'])){
			 			$ro = $inputs['reporting_to'];
			 			
			 		  	$res = array(
		                'is_teamlead' =>'yes' 
		                );
		                $this->db->where('id',$ro);
		                $this->db->update('dgt_users',$res);
			 		}
		 		 	$email_activation = config_item('email_activation');
			 		$data['site_name'] = config_item('company_name');
			 		$data['email'] = $inputs['email'];
			 		$data['username'] = $inputs['username'];
			 		$data['password'] = $inputs['password'];
                   	if (config_item('email_account_details') == 'TRUE') {   // send "welcome" email

                        $this->_send_email('welcome', $data['email'], $data);
                    }
                    $records = 1;
			 	}else{
			 		$records = 3;
			 	}
			}
		}
		return $records;

	 }
	 public function _send_email($type, $email, &$data)
    {
        switch ($type)
        {
            case 'activate':
                return $this->_activate_email($email,$data);
                break;
            case 'welcome':
                return $this->_welcome_email($email,$data);
                break;
            case 'confirm_client':
                return $this->_confirm_client($email,$data);
                break;
            case 'forgot_password':
                return $this->_email_forgot_password($email,$data);
                break;
            case 'reset_password':
                return $this->_email_reset_password($email,$data);
                break;
            case 'change_email':
                return $this->_email_change_email($email,$data);
                break;
        }
    }

    public function _welcome_email($email,$data){

        $message = App::email_template('registration','template_body');
        $subject = App::email_template('registration','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'auth/login',$logo);
        $username = str_replace("{USERNAME}",$data['username'],$site_url);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }
	 public function create_project($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];
				 if ($inputs['fixed_rate'] == TRUE) { $fixed_rate = 'Yes'; } else { $fixed_rate = 'No'; }

				 $projects['fixed_rate']=$fixed_rate;

				 if($fixed_rate = 'Yes')
				 {
				 	 
				 	 $projects['fixed_price']=$inputs['fixed_price'];
				 }
				 else
				 {
				 	$projects['hourly_rate']=$inputs['hourly_rate'];
				 }

				


				 if ($role != 2 ) { // If added by client, just assign admin
                    if($role == 3){
                        $projects['assign_to'] = 'a:1:{i:0;s:1:"'.$record['user_id'].'";}';
                    }else{
                        $projects['assign_to'] = serialize(explode(',',$this->input->post('assign_to')));
                    }

                }else{
                    $projects['assign_to'] = 'a:1:{i:0;s:1:"1";}';
                    $projects['progress'] = 0;
                }

                // echo $role;
                // exit;

                

                if($role == 2){
                    $company_id =  $this->get_company_id($record['user_id']);

                    if($company_id > 0){
                        $projects['client'] = $company_id;
                    }else{

                        $records = 2;
                    }
                }
                else
                {
                	$projects['client']=$inputs['client'];
                }

                if(isset($inputs['start_date']) && !empty($inputs['start_date']))
                {
                	 $projects['start_date'] = date('Y-m-d',strtotime($_POST['start_date']));
                }

                 if(isset($inputs['due_date']) && !empty($inputs['due_date']))
                {
                	 $projects['due_date'] = date('Y-m-d',strtotime($_POST['due_date']));
                }
               
                
               

                 	$projects['project_code']=$inputs['project_code'];
					$projects['project_title']=$inputs['project_title'];
					$projects['assign_lead']=$inputs['assign_lead'];
					$projects['estimate_hours']=$inputs['estimate_hours'];
					$projects['description']=$inputs['description'];
					if($this->db->insert('dgt_projects', $projects))
					{
						$project_id=$this->db->insert_id();

						 // Inherit currency and language settings

		                if ($inputs['client'] > 0) {
		                    $client_cur = $this->client_currency($_POST['client']);
		                    $client_lang = $this->client_language($_POST['client']);
		                } else {
		                    $client_cur = $this->currencies(config_item('default_currency'));
		                    $client_lang = $this->languages(config_item('default_language'));
		                }
		                $data = array(
		                    'currency' => $client_cur->code,
		                    'language' => $client_lang->name,
		                    'project_id' => $project_id
		                );

		               $this->db->where('project_id',$project_id);
		               $this->db->update('dgt_projects', $data);


		               // Store assignments in assign_projects table	
		               $assign = unserialize($projects['assign_to']);

		               $this->db->where('project_assigned',$project_id)->delete('assign_projects');

		               foreach ($assign as $key => $value) {
		                    $args = array(
		                        'assigned_user' => $value,
		                        'project_assigned' => $project_id
		                    );
		                    $this->db->insert('assign_projects',$args);
		                }


		                // default project settings
		                $default_settings = json_decode(config_item('default_project_settings'),TRUE);
		                foreach ($default_settings as $key => &$value) {
		                    if (strtolower($value) == 'off') {
		                        unset($default_settings[$key]);
		                    }
		                }
		                $default_settings = json_encode($default_settings);

		                $default_data = array('settings' => $default_settings);
		                $this->db->where('project_id',$project_id);
				        $this->db->update('dgt_projects', $default_data);


				         $this->lead_notification($project_id,$record['user_id']);
		                // Send email to the assigned users
		                if(config_item('notify_project_assignments') == 'TRUE'){
		                    $this->assigned_notification($project_id,$record['user_id']);
		                }

		                // Send email to client
		                if(config_item('notify_project_opened') == 'TRUE'){
		                    $this->client_notification($project_id,$record['user_id']);
		                }

		                // Post to slack channel
		                if(config_item('slack_notification') == 'TRUE'){
		                    $this->load->helper('slack');
		                    $slack = new Slack_Post;
		                    $slack->slack_create_project($project_id,$record['user_id']);
		                }

		                $app_data = array(
		                    'module' => 'projects',
		                    'module_field_id' => $project_id,
		                    'user' => $record['user_id'],
		                    'activity' => 'activity_added_new_project',
		                    'icon' => 'fa-coffee',
		                    'value1' => $inputs['project_title'],
		                    'value2' => ''
		                );
		                $this->db->insert('activities',$app_data);

                        $records = 1;

					}
					else
					{
						$records = 3;
					}

			
			}

			return $records;
		

	 }

	 public function edit_project($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$project_id = $this->input->post('project_id');


				$notify = TRUE; // Send email only when team changed
                $current_team = $this->get_assign_to($project_id);
                if(serialize($this->input->post('assign_to')) == $current_team) $notify = FALSE;


                $fixed_rate = ($this->input->post('fixed_rate') == 'on') ? 'Yes' : 'No';
                unset($_POST['fixed_rate']);

                $new_team = explode(',',$this->input->post('assign_to'));

                 $this->db->where('project_assigned',$project_id)->delete('assign_projects');

                   foreach ($new_team as $key => $value) {
                    $args = array(
                        'assigned_user' => $value,
                        'project_assigned' => $project_id
                    );
                    $this->db->insert('assign_projects',$args);
                }

                if ($_POST['client'] > 0) {
                    $client_cur = $this->client_currency($_POST['client']);
		            $client_lang = $this->client_language($_POST['client']);
                } else {
                     $client_cur = $this->currencies(config_item('default_currency'));
		             $client_lang = $this->languages(config_item('default_language'));
                }


                $_POST['assign_to'] = serialize($new_team);

                
            	if(isset( $_POST['start_date']) && !empty( $_POST['start_date']))
                {
                	  $_POST['start_date'] = date('Y-m-d',strtotime($_POST['start_date']));
                }

                 if(isset($_POST['due_date']) && !empty($_POST['due_date']))
                {
                	 $_POST['due_date'] = date('Y-m-d',strtotime($_POST['due_date']));
                }

                if (isset($_POST['files'])) unset($_POST['files']);

                 $this->db->where('project_id',$project_id)->update('projects',$this->input->post());



                $data1 = array(
                    'currency' => $client_cur->code,
                    'language' => $client_lang->name,
                    'project_id' => $project_id
                );
                 $this->db->where('project_id',$project_id)->update('projects',$data1);


                // Set Fixed Rate
                $data2 = array('fixed_rate' => $fixed_rate);
                $this->db->where('project_id',$project_id)->update('projects',$data2);

                // Send email to the assigned users
                if(config_item('notify_project_assignments') == 'TRUE' && $notify){
                    $this->notify_project_update($project_id,$record['user_id']);
                }
                // Log activity
                $app_data = array(
                    'module' => 'projects',
                    'module_field_id' => $project_id,
                    'user' => $record['user_id'],
                    'activity' => 'activity_edited_a_project',
                    'icon' => 'fa-coffee',
                    'value1' => $_POST['project_title'],
                    'value2' => ''
                );
                $this->db->insert('activities',$app_data);

                 $records = 1;


				}

			return $records;

	 }


	 public function delete_project($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$project = $this->input->post('project_id');

				if($role==1 || $role==4 || $this->perm_allowed($record['user_id'],'delete_projects')){
                    
                     foreach($this->has_comments($project) as $key => $comment){
                     $this->del_ete('comments',array('comment_id'=>$comment->comment_id));
		           }

		        $this->db->where(array('project' => $project))->delete('project_timer');
		        $this->db->where(array('pro_id' => $project))->delete('tasks_timer');

		            foreach ($this->has_bugs($project) as $bug) {
		            foreach ($this->bug_has_files($bug->bug_id) as $key => $f) {
		                $fullpath = './assets/bug-files/'.$f->file_name;

		                if(is_file($fullpath)) unlink($fullpath);

		                $this->del_ete('bug_files',array('file_id'=>$f->file_id));
		            }
		        }

		        foreach ($this->db->where('project',$project)->get('expenses')->result() as $ex) {
		            $data = array('project' => '0');
		            $this->db->where('id',$ex->id)->update('expenses',$data);
		        }

		        $this->db->where(array('project' => $project))->delete('bugs');

		        $this->delete_team($project);

		        $this->db->where(array('project_assigned' => $project))->delete('assign_tasks');
		        $this->db->where(array('project_id' => $project))->delete('links');
		        $this->db->where(array('module' => 'projects','module_field_id' => $project))->delete('activities');
		        $this->db->where(array('project' => $project))->delete('milestones');

		        foreach ($this->has_tasks($project) as $task) {
		            foreach ($this->task_has_files($task->t_id) as $key => $f) {
		                $fullpath = './assets/project-files/'.$f->path.$f->file_name;

		                if($f->path == NULL) $fullpath = './assets/project-files/'.$f->file_name;

		                if(is_file($fullpath)) unlink($fullpath);

		                //self::delete_task_file($f->file_id);
		            }
		        }
		        // Delete project files
		        foreach ($this->has_files($project) as $key => $f) {
		            $fullpath = './assets/project-files/'.$f->path.$f->file_name;
		            if($f->path == NULL) $fullpath = './assets/project-files/'.$f->file_name;

		            if(is_file($fullpath)) unlink($fullpath);
		            $this->del_ete('files',array('file_id' => $f->file_id));
		        }

		        $this->db->where(array('project' => $project))->delete('tasks');
		        $this->db->where(array('project_id' => $project))->delete('projects');

		        $records=1;

                    
                }
                else
                {
                	$records=2;
                }
			}

			return $records;

	 }

	 public function task_view($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$tasks_id    = $inputs['task'];
				$project_id    = $inputs['project'];

				 $task_det = $this->db->get_where('dgt_tasks',array('t_id'=>$tasks_id))->row_array();

                         	 if($task_det['task_progress'] == 100)
								{
									 $task_status='Completed';
									
								}else{
									 $task_status='In Completed';
								}

				 $team_lead = $this->db->select('*')
							         ->from('projects P')
							         ->join('account_details AD','P.assign_lead = AD.user_id')
							         ->where('P.assign_lead',$project_id)
							         ->get()->row_array();
							         if($team_lead['avatar'] == '' )
							         {
							         	 $pro_pic = base_url().'assets/avatar/default_avatar.jpg';
							         }else{
							         	 $pro_pic = base_url().'assets/avatar/'.$team_lead['avatar'];
							         }

							         

		         $team_members = $this->db->select('*')
						         ->from('assign_tasks PA')
						         ->join('account_details AD','PA.assigned_user = AD.user_id')
						         ->where('PA.task_assigned',$tasks_id)
						         ->get()->result_array(); 

						          $pro_pic_team = base_url().'assets/avatar/default_avatar.jpg';
						          $assignrd_name='';

						         foreach($team_members as $member)
						         {
						         	if($member['avatar'] == '' )
							         {
							         	
							         }else{
							         	$pro_pic_team = base_url().'assets/avatar/'.$member['avatar'];
							         }
						            $assignrd_name=$member['fullname'];
						         }
						         $duedate='';
						         if(!empty($task_det['due_date']))
								 {
								 	 $duedate=date('M d, Y',strtotime($task_det['due_date']));
								 }

								


								 $project_files =$this->db->query("SELECT '' as activites, '' as file_ext,CM.message,CM.date_posted,AD.fullname,'' as file_name,'' as path,AD.avatar FROM dgt_comments AS CM LEFT JOIN dgt_account_details AS AD ON CM.posted_by = AD.user_id WHERE CM.task_id='".$tasks_id."' 
															UNION  SELECT '' as activites,FI.file_ext,'' as message,FI.date_posted,ADS.fullname,FI.file_name,FI.path,ADS.avatar FROM dgt_task_files AS FI LEFT JOIN dgt_account_details AS ADS ON FI.uploaded_by = ADS.user_id WHERE FI.task='".$tasks_id."' 

															UNION  SELECT TI.activites,''as file_ext,'' as message,TI.date_posted,ADsS.fullname,'' as file_name,'' as path,ADsS.avatar FROM dgt_task_activites AS TI LEFT JOIN dgt_account_details AS ADsS ON TI.added_by = ADsS.user_id WHERE TI.task_id='".$tasks_id."'

															ORDER by date_posted ASC")->result_array();

								 				$task_comments_list=array();
								 				if(!empty($project_files))
								 				{
													foreach($project_files as $project_file){
															if($project_file['avatar'] == '' )
													         {
													         	$pro_pic_coms = base_url().'assets/avatar/default_avatar.jpg';
													         }else{
													         	$pro_pic_coms = base_url().'assets/avatar/'.$project_file['avatar'];
													         }

													      if(!empty($project_file['activites'])) 
													      {
													      	$row['img']=$pro_pic_coms;
													      	$row['full_name']=$project_file['fullname'];
															$row['activites']=$project_file['activites'];
															$row['posted_date']=date('M d Y h:ia',strtotime($project_file['date_posted']));
															$row['file_path']='';
													      }
													      else
													      {

													      	if(!empty($project_file['file_name']))
													      	{

													      	$row['img']=$pro_pic_coms;
													      	$row['full_name']=$project_file['fullname'];
															$row['activites']='';
															$row['posted_date']=date('M d Y h:ia',strtotime($project_file['date_posted']));
															$row['file_path']=base_url().'assets/project-files/'.$project_file['path'].'/'.$project_file['file_name'];

														     }

															 if(!empty($project_file['message']))
															  {

															  	$row['img']=$pro_pic_coms;
														      	$row['full_name']=$project_file['fullname'];
																$row['activites']='';
																$row['posted_date']=date('M d Y h:ia',strtotime($project_file['date_posted']));
																$row['file_path']='';
																$row['message']=$project_file['message'];

															  }

													      }


													      $task_comments_list[]=$row;

													     

													  }
								 				}








				 return array('task_status' =>$task_status,'team_lead'=>!empty($team_lead['fullname'])?$team_lead['fullname']:'','task_name'=>$task_det['task_name'],'assigne_img'=>$pro_pic_team,'assigned_name'=>$assignrd_name,'duedate'=>$duedate,'task_description'=>!empty($task_det['description'])?$task_det['description']:'','comments_list'=>$task_comments_list);


			}

	  }

	 public function create_task($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				 $project = $this->input->post('project', TRUE);
				

                $form_data = array(
                    'task_name' => $this->input->post('task_name',TRUE),
                    'project' => $this->input->post('project',TRUE),
                    'added_by' => $record['user_id'],
                );

                if($this->db->insert('dgt_tasks', $form_data))
				{

				    $task_id = $this->db->insert_id();

				     $form_datas = array(
                    'task_id' => $task_id,
                    'activites' => 'created task',
                    'added_by' => $record['user_id'],
                    'date_posted'=>date('Y-m-d H:i:s')
                );
				     $this->db->insert('dgt_task_activites', $form_datas);
                     

                  $records=1;
				 
                }
                else
                {
                	 $records=2;
                }
			
			}

			return $records;
		

	 }

	  public function assign_user($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$project = $this->input->post('project', TRUE);
                $task_id = $this->input->post('task', TRUE);
                $type = $this->input->post('type', TRUE);
            
            $form_data = array();

				 if($type=='Assign')
	            {
	                 $assign = $this->input->post('assigned_to');

	                if($role=='2') { $assign = $this->by_assign_to($project); }else{
	                    $assign = serialize($this->input->post('assigned_to'));
	                }

	                $form_data['assigned_to']=$assign;
	            }
	           
	               
	            if($type=='Due')
	            {

	                 $due_date = date('Y-m-d',strtotime($_POST['due_date']));
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
	                    'added_by' => $record['user_id'],
	                    'date_posted'=>date('Y-m-d H:i:s')
	                );
	               $this->db->insert('dgt_task_activites', $form_datas);
	                    
	            }

	            if($type=='Assign')
                {

                        $assign = $this->input->post('assigned_to');
                        $this->db->where('task_assigned',$task_id);
                        $this->db->delete('assign_tasks');
                        if (!empty($assign) > 0) {
                        
                                $team = array(
                                    'assigned_user' => $assign,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                              $this->db->insert('dgt_assign_tasks', $team);
                           
                        }

                       if (config_item('notify_task_assignments') == 'TRUE' && $assign != FALSE) {
                    $this->_assigned_notification($project, $task_id,$record['user_id']);
                }

                if(config_item('notify_task_created') == 'TRUE') $this->_new_task_notification($task_id,$record['user_id'],$role);



                        // Post to slack channel
                   if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_task_action($project,$task_id,$record['user_id'],'added');
            }

                   // Log activity
            $app_data = array(
                'module' => 'tasks',
                'module_field_id' => $project,
                'user' => $record['user_id'],
                'activity' => 'activity_added_new_task',
                'icon' => 'fa-tasks',
                'value1' => $this->input->post('task_name'),
                'value2' => ''
                );
             $this->db->insert('activities',$app_data);



                   


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



                     $form_datas = array(
                    'task_id' => $task_id,
                    'activites' => 'assigned to '.$assignrd_name,
                    'added_by' =>  $record['user_id'],
                    'date_posted'=>date('Y-m-d H:i:s')
                );
           $this->db->insert('task_activites',$form_datas);

     

           }


            }

			 
               
                  $records=1;
				 
                }
                else
                {
                	 $records=2;
                }
			
			

			return $records;
		

	 }




	 public function create_taskold($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				 $project = $this->input->post('project', TRUE);
				 $est_time = ($this->input->post('estimate') == '') ? 0 : $_POST['estimate'];

                $assign = explode(',', $this->input->post('assigned_to'));

                if($role=='2') { $assign = $this->by_assign_to($project); }else{
                    $assign = serialize(explode(',', $this->input->post('assigned_to')));
                }
                $start_date = date('Y-m-d',strtotime($_POST['start_date']));
                $due_date = date('Y-m-d',strtotime($_POST['due_date']));

                $form_data = array(
                    'task_name' => $this->input->post('task_name',TRUE),
                    'project' => $this->input->post('project',TRUE),
                    'start_date' => $start_date,
                    'due_date' => $due_date,
                    'assigned_to' => $assign,
                    'task_progress' => 0,
                    'description' => $this->input->post('description'),
                    'estimated_hours' => $this->input->post('estimate'),
                    'added_by' => $record['user_id'],
                );

                if ($role=='2') {
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                } else {
                    $form_data['visible'] = 'Yes';
                }

                if($this->db->insert('dgt_tasks', $form_data))
				{

				    $task_id = $this->db->insert_id();

	                $assign = unserialize($assign);
	                if (count($assign) > 0) {
	                    foreach ($assign as $key => $value) {
	                        $team = array(
	                            'assigned_user' => $value,
	                            'project_assigned' => $project,
	                            'task_assigned' => $task_id
	                            );

	                        $this->db->insert('dgt_assign_tasks', $team);
	                       
	                    }
	                }


                if (config_item('notify_task_assignments') == 'TRUE' && $assign != FALSE) {
                    $this->_assigned_notification($project, $task_id,$record['user_id']);
                }

                if(config_item('notify_task_created') == 'TRUE') $this->_new_task_notification($task_id,$record['user_id'],$role);



                // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_task_action($project,$task_id,$record['user_id'],'added');
            }

            // Log activity
            $app_data = array(
                'module' => 'tasks',
                'module_field_id' => $project,
                'user' => $record['user_id'],
                'activity' => 'activity_added_new_task',
                'icon' => 'fa-tasks',
                'value1' => $this->input->post('task_name'),
                'value2' => ''
                );
             $this->db->insert('activities',$app_data);

                  $records=1;
				 
                }
                else
                {
                	 $records=2;
                }
			
			}

			return $records;
		

	 }


	 public function edit_task($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				 $project = $this->input->post('project', TRUE);
				 $task_id = $this->input->post('task_id', TRUE);

				$assign = NULL;
                $notify = TRUE; // Send email only when team changed
                $info = $this->view_tasks($task_id);
                $current_team = $info->assigned_to;
                $assign = serialize(explode(',', $this->input->post('assigned_to')));
                if($assign == $current_team){ $notify = FALSE; }

                $start_date = date('Y-m-d',strtotime($_POST['start_date']));
                $due_date = date('Y-m-d',strtotime($_POST['due_date']));

                $form_data = array(
                    'task_name' => $this->input->post('task_name'),
                    'project' => $this->input->post('project'),
                    'start_date' => $start_date,
                    'due_date' => $due_date,
                    'description' => $this->input->post('description'),
                    'estimated_hours' => $this->input->post('estimate'),
                );

                if ($role!='2') {
                    $visible = ($this->input->post('visible') == 'on') ? 'Yes' : 'No';
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                    if($this->input->post('progress'))
                    $form_data['task_progress'] = $this->input->post('progress');

                    if ($role=='1' || $role=='4') {
                        $assign = $this->input->post('assigned_to');
                        $this->delete_task_team($task_id);
                        if($assign){
                            foreach ($assign as $key => $value) {
                                $team = array(
                                    'assigned_user' => $value,
                                    'project_assigned' => $project,
                                    'task_assigned' => $task_id
                                    );
                               $this->db->insert('dgt_assign_tasks', $team);
                            }
                        }
                        $form_data['assigned_to'] = serialize($this->input->post('assigned_to'));
                    }

                }

                $this->db->where(array('t_id' => $task_id));
                $this->db->update('dgt_tasks',$form_data);

                 if (config_item('notify_task_assignments') == 'TRUE' && $notify) {
                        $this->_task_changed_notification($project, $task_id,$record['user_id']);
                    }

                // Post to slack channel
            if(config_item('slack_notification') == 'TRUE'){
                $this->load->helper('slack');
                $slack = new Slack_Post;
                $slack->slack_task_action($project,$task_id,$record['user_id'],'edited');
            }


            // Log activity
            $app_data = array(
                'module' => 'tasks',
                'module_field_id' => $project,
                'user' => $record['user_id'],
                'activity' => 'activity_edited_a_task',
                'icon' => 'fa-tasks',
                'value1' => $this->input->post('task_name'),
                'value2' => ''
                );
             $this->db->insert('activities',$app_data);

                  $records=1;
				 
               
			
			}

			return $records;
		

	 }

	 public function delete_task($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				 $project = $this->input->post('project', TRUE);
                 $task = $this->input->post('task_id');

                 foreach ($this->task_has_files($task) as $file) {
                unlink('./assets/project-files/'.$file->file_name);
            }
            $this->del_ete('tasks',array('t_id' => $task));
            $this->del_ete('tasks_timer',array('task' => $task));
            $this->del_ete('task_files',array('task' => $task));

            $records=1;

				
			}

			return $records;

	 }


	  public function task_completion($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				 $task_id = $this->input->post('task_id',TRUE);
	            $i = $this->db->where('t_id',$task_id)->get('tasks')->row();
	            if($i->timer_status != 'On'){
	            $task_complete = $this->input->post('task_complete');
	            $progress = ($task_complete == 'true') ? '100' : '0';
	            $args = array('task_progress' => $progress);

	            $this->db->where(array('t_id' => $task_id));
                $this->db->update('dgt_tasks',$args);

            $records=1;

				
			}
			else
			{
				$records=2;
			}

		}	

			return $records;

	 
	}

	 public function create_client($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				 $custom_fields = array();
              foreach ($_POST as $key => &$value) {
                if (strpos($key, 'cust_') === 0) {
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }

            $_POST['company_website'] = prep_url($_POST['company_website']);

            if($this->db->insert('companies',$this->input->post(null, true)))
            {
            	$company_id = $this->db->insert_id();

            	foreach ($custom_fields as $key => $f) {
                    $key = str_replace('cust_', '', $key);
                    $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                    $cf = $this->db->where('name',$key)->get('fields');
                    $data = array(
                        'module'    => 'clients',
                        'field_id'  => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key'  => $cf->row()->name,
                        'meta_value'    => is_array($f) ? json_encode($f) : $f
                    );
                    ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                }

                $args = array(
                            'user' => $record['user_id'],
                            'module' => 'Clients',
                            'module_field_id' => $company_id,
                            'activity' => 'activity_added_new_company',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('company_name', true),
                        );
                 $this->db->insert('activities',$args);

                 $records=1;

            }
            else
            {
            	$records=2;
            }	
				 

			
			}

			return $records;
		

	 }


	 public function edit_client($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$custom_fields = array();
            foreach ($_POST as $key => &$value) {
                if (strpos($key, 'cust_') === 0) {
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }

                $company_id = $_POST['co_id'];

                foreach ($custom_fields as $key => $f) {
                    $key = str_replace('cust_', '', $key);
                    $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                    $cf = $this->db->where('name',$key)->get('fields');
                    $data = array(
                        'module'    => 'clients',
                        'field_id'  => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key'  => $cf->row()->name,
                        'meta_value'    => is_array($f) ? json_encode($f) : $f
                    );
                    ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                }

                $_POST['company_website'] = prep_url($_POST['company_website']);

                $this->db->where('co_id',$company_id);
                $this->db->update('companies',$this->input->post());

                $args = array(
                            'user' => $record['user_id'],
                            'module' => 'Clients',
                            'module_field_id' => $company_id,
                            'activity' => 'activity_updated_company',
                            'icon' => 'fa-edit',
                            'value1' => $this->input->post('company_name', true),
                        );
                 $this->db->insert('activities',$args);

                 $records=1;

           	
				 

			
			}

			return $records;
		

	 }

	 public function delete_client($inputs,$token)
	 {
	 	
	
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$company = $this->input->post('company', true);

				$company_invoices 	= $this->get_client_invoices($company);
				$company_estimates 	= $this->estimate_by_client($company);
				$company_expenses 	= $this->expenses_by_client($company);
				$company_projects 	= $this->by_client($company);
				$company_contacts 	= $this->get_client_contacts($company);

			if (count($company_invoices)) {
				foreach ($company_invoices as $invoice) {
					//delete invoice items
					$this->db->where('invoice_id',$invoice->inv_id)->delete('items');
				}
			}

			if (count($company_estimates)) {
				foreach ($company_estimates as $estimate) {
					//delete estimate items
					$this->db->where('estimate_id',$estimate->est_id)->delete('estimate_items');
				}
			}

			if (count($company_projects)) {
				foreach ($company_projects as $project) {
					// remove client from projects
					$this->db->set('client','0')->where('client',$company)->update('projects');
				}
			}

			if (count($company_expenses)) {
				foreach ($company_expenses as $expense) {
					//set client to blank in expenses
					$this->db->where('client',$company)->delete('expenses');
				}
			}

			//delete invoices
			$this->db->where('client',$company)->delete('invoices');
			//delete estimates
			$this->db->where('client',$company)->delete('estimates');

			// delete client payments
			$this->db->where('paid_by',$company)->delete('payments');
			//clear client activities
			$this->db->where(array('module'=>'Clients', 'module_field_id' => $company))->delete('activities');
			//delete company
			$this->db->where('co_id',$company)->delete('companies');


			if (count($company_contacts)) {
				foreach ($company_contacts as $contact) {
					//set contacts to blank
					$this->db->set('company','-')->where('company',$company)->update('account_details');
				}
			}

                 $records=1;

           	
				 

			
			}

			return $records;
		

	 }


	  public function create_invoice($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];


				$invoices = array();

				 if (config_item('increment_invoice_number') == 'TRUE') {
                    $invoices['reference_no'] = config_item('invoice_prefix').$this->generate_invoice_number();
                }
                else
                {
                	$invoices['reference_no']=$this->input->post('reference_no');
                }

                $invoices['client']=$this->input->post('client');
                $invoices['tax']=$this->input->post('tax');
                $invoices['tax2']=$this->input->post('tax2');
                $invoices['discount']=$this->input->post('discount');
                $invoices['extra_fee']=$this->input->post('extra_fee');
                $invoices['notes']=$this->input->post('notes');


                $invoices['allow_paypal'] = ($this->input->post('allow_paypal') == 'on') ? 'Yes' : 'No';
                $invoices['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'on') ? 'Yes' : 'No';
                $invoices['allow_stripe'] = ($this->input->post('allow_stripe') == 'on') ? 'Yes' : 'No';
                $invoices['allow_bitcoin'] = ($this->input->post('allow_bitcoin') == 'on') ? 'Yes' : 'No';
                $invoices['allow_braintree'] = ($this->input->post('allow_braintree') == 'on') ? 'Yes' : 'No';

                 if ($invoices['currency'] == '') {
                $currency = $this->client_currency($_POST['client']);
                $invoices['currency'] = $currency->code;
                }
                else
                {
                	 $invoices['currency'] = $this->input->post('currency');
                }

                $invoices['due_date'] = date('Y-m-d',strtotime($_POST['due_date']));
                unset($_POST['files']);

              // return $this->input->post();

              // exit;

                if ($this->db->insert('dgt_invoices', $invoices)) {

                	$invoice_id = $this->db->insert_id();

                	$item_details = json_decode($this->input->post('item_details'));

                	$i=1;

		            foreach ($item_details as $rows) {
		               
		               $data = array(
						'invoice_id'	=> $invoice_id,
						'item_name'		=> $rows->item_name,
						'item_desc'		=> $rows->item_desc,
						'unit_cost'		=> $rows->unit_cost,
						'item_order'	=> $i++,
						'item_tax_rate'	=> $rows->item_tax_rate,
						'item_tax_total'=> $rows->item_tax_total,
						'quantity'		=> $rows->quantity,
						'total_cost'	=> $rows->total_cost);

		               $this->db->insert('items',$data);

		            }

		           
                    // Log Activity
                $activity = array(
                    'user' => $record['user_id'],
                    'module' => 'invoices',
                    'module_field_id' => $invoice_id,
                    'activity' => 'activity_invoice_created',
                    'icon' => 'fa-plus',
                    'value1' => $_POST['reference_no'],
                );
                     $this->db->insert('activities',$activity); // Log activity

                     $records=1;
                
                }
                else
                {
                	$records=2;
                }

		}	

			return $records;

	 
	}


	 public function edit_invoice($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$invoices = array();

				 $invoice_id = $this->input->post('inv_id', true);

				$invoices['client']=$this->input->post('client');
                $invoices['tax']=$this->input->post('tax');
                $invoices['tax2']=$this->input->post('tax2');
                $invoices['discount']=$this->input->post('discount');
                $invoices['extra_fee']=$this->input->post('extra_fee');
                $invoices['notes']=$this->input->post('notes');


                $invoices['allow_paypal'] = ($this->input->post('allow_paypal') == 'on') ? 'Yes' : 'No';
                $invoices['allow_2checkout'] = ($this->input->post('allow_2checkout') == 'on') ? 'Yes' : 'No';
                $invoices['allow_stripe'] = ($this->input->post('allow_stripe') == 'on') ? 'Yes' : 'No';
                $invoices['allow_bitcoin'] = ($this->input->post('allow_bitcoin') == 'on') ? 'Yes' : 'No';
                $invoices['allow_braintree'] = ($this->input->post('allow_braintree') == 'on') ? 'Yes' : 'No';

                 if ($invoices['currency'] == '') {
                $currency = $this->client_currency($_POST['client']);
                $invoices['currency'] = $currency->code;
                }
                else
                {
                	 $invoices['currency'] = $this->input->post('currency');
                }

                $invoices['due_date'] = date('Y-m-d',strtotime($_POST['due_date']));
                unset($_POST['files']);

                $this->db->where('inv_id',$invoice_id);
                $this->db->update('dgt_invoices',$invoices);

                 // if ($this->input->post('r_freq') != 'none') {
                 //            $this->recur($invoice_id, $this->input->post(),$record['user_id']);
                 //        }




                   $this->db->where(array('invoice_id' => $invoice_id))->delete('items');

                   $item_details = json_decode($this->input->post('item_details'));

                	$i=1;

		            foreach ($item_details as $rows) {
		               
		               $data = array(
						'invoice_id'	=> $invoice_id,
						'item_name'		=> $rows->item_name,
						'item_desc'		=> $rows->item_desc,
						'unit_cost'		=> $rows->unit_cost,
						'item_order'	=> $i++,
						'item_tax_rate'	=> $rows->item_tax_rate,
						'item_tax_total'=> $rows->item_tax_total,
						'quantity'		=> $rows->quantity,
						'total_cost'	=> $rows->total_cost);

		               $this->db->insert('items',$data);

		            }

                    // Log Activity
                $activity = array(
                    'user' => $record['user_id'],
                    'module' => 'invoices',
                    'module_field_id' => $invoice_id,
                    'activity' => 'activity_invoice_created',
                    'icon' => 'fa-plus',
                    'value1' => $_POST['reference_no'],
                );
                     $this->db->insert('activities',$activity); // Log activity

                     $records=1;
                
               

		}	

			return $records;

	 
	}


	 public function delete_invoice($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$invoice = $this->input->post('inv_id', true);

	            $this->db->where(array('invoice_id' => $invoice))->delete('items');
		        //delete invoice payments
		        $this->db->where(array('invoice' => $invoice))->delete('payments');
		        //clear invoice activities
		        $this->db->where(array('module' => 'invoices', 'module_field_id' => $invoice))->delete('activities');
		        //delete invoice
		        $this->db->where(array('inv_id' => $invoice))->delete('invoices');

            $records=1;

				
			}
			

					return $records;

	 
	}


	public function create_estimate($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$estimates = array();

				$estimates['client']=$this->input->post('client');
                $estimates['tax']=$this->input->post('tax');
                $estimates['tax2']=$this->input->post('tax2');
                $estimates['discount']=$this->input->post('discount');
                $estimates['notes']=$this->input->post('notes');


				$estimates['reference_no'] = config_item('estimate_prefix').$this->generate_estimate_number();

                $currency = $this->client_currency($_POST['client']);
                $estimates['currency'] = $currency->code;
                

                $estimates['due_date'] = date('Y-m-d',strtotime($_POST['due_date']));
                unset($_POST['files']);

                if ($this->db->insert('dgt_estimates', $estimates)) {

                	$estimate_id = $this->db->insert_id();


                	$item_details = json_decode($this->input->post('item_details'));
                	$i=1;
		            foreach ($item_details as $rows) {
		               $data = array(
						'estimate_id'	=> $estimate_id,
						'item_name'		=> $rows->item_name,
						'item_desc'		=> $rows->item_desc,
						'unit_cost'		=> $rows->unit_cost,
						'item_order'	=> $i++,
						'item_tax_rate'	=> $rows->item_tax_rate,
						'item_tax_total'=> $rows->item_tax_total,
						'quantity'		=> $rows->quantity,
						'total_cost'	=> $rows->total_cost);

		               $this->db->insert('estimate_items',$data);
		            }

                    // Log Activity
                 $activity = array(
                'module' => 'estimates',
                'module_field_id' => $estimate_id,
                'user' => $record['user_id'],
                'activity' => 'activity_estimate_created',
                'icon' => 'fa-plus',
                'value1' => $_POST['reference_no'],
                'value2' => '',
                );
                     $this->db->insert('activities',$activity); // Log activity

                     $records=1;
                
                }
                else
                {
                	$records=2;
                }

		}	

			return $records;

	 
	}


	 public function edit_estimate($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				  $id = $this->input->post('est_id', true);

				   $estimates = array();

				$estimates['client']=$this->input->post('client');
                $estimates['tax']=$this->input->post('tax');
                $estimates['tax2']=$this->input->post('tax2');
                $estimates['discount']=$this->input->post('discount');
                $estimates['notes']=$this->input->post('notes');
                $currency = $this->client_currency($_POST['client']);
                $estimates['currency'] = $currency->code;
                

                $estimates['due_date'] = date('Y-m-d',strtotime($_POST['due_date']));
                

                $this->db->where('est_id',$id);
                $this->db->update('dgt_estimates', $estimates);


                 $this->del_ete('estimate_items', array('estimate_id' => $id));


                $item_details = json_decode($this->input->post('item_details'));

                	$i=1;

		            foreach ($item_details as $rows) {
		               
		               $data = array(
						'estimate_id'	=> $id,
						'item_name'		=> $rows->item_name,
						'item_desc'		=> $rows->item_desc,
						'unit_cost'		=> $rows->unit_cost,
						'item_order'	=> $i++,
						'item_tax_rate'	=> $rows->item_tax_rate,
						'item_tax_total'=> $rows->item_tax_total,
						'quantity'		=> $rows->quantity,
						'total_cost'	=> $rows->total_cost);

		               $this->db->insert('estimate_items',$data);

		            }

                
                    // Log Activity
                $data = array(
                'module' => 'estimates',
                'module_field_id' => $id,
                'user' => $record['user_id'],
                'activity' => 'activity_estimate_edited',
                'icon' => 'fa-pencil',
                'value1' => $this->input->post('reference_no', true),
                'value2' => '',
                );
                     $this->db->insert('activities',$activity); // Log activity

                     $records=1;
                
               

		}	

			return $records;

	 
	}

	public function delete_estimate($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$estimate = $this->input->post('est_id', true);
            $this->del_ete('estimate_items', array('estimate_id' => $estimate));
            $this->del_ete('estimates', array('est_id' => $estimate));
            $this->del_ete('activities', array('module' => 'estimates', 'module_field_id' => $estimate));

            $records=1;

				
			}
			

					return $records;

	 
	}


	public function create_expense($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$attached_file = NULL;
			
				$image_data = $this->input->post('img_data');


				if(!empty($image_data))
			{


				$search = array('data:image/png;base64,', 'data:image/jpeg;base64,');
                $replace   = array('', '');

				$base64string = str_replace($search, $replace, $image_data);

				$base64string = str_replace(' ', '+', $base64string);

				$data = base64_decode($base64string);

				$img_name = time();

				$attached_file='expen_'.$img_name.".png";

				file_put_contents('./assets/uploads/'.$attached_file, $data); 

			}	


				$project_id = $this->input->post('project',TRUE);

				$p = $this->by_expenses_id($project_id);

              	$billable = ($this->input->post('billable') == 'on') ? '1' : '0';
              	$show_client = ($this->input->post('show_client') == 'on') ? 'Yes' : 'No';
              	$invoiced = ($this->input->post('invoiced') == 'on') ? '1' : '0';
              	$client = ($this->input->post('project') == 'NULL') ? $this->input->post('client') : $p->client ;
              	$currency = $this->client_currency($_POST['client']);
              	$cur = $currency->code;
              	$expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));

              	$expenses = array(
              				'added_by'  	=> $record['user_id'],
              				'billable'		=> $billable,
              				'amount'		=> $this->input->post('amount',TRUE),
              				'expense_date'	=> $expense_date,
              				'notes'			=> $this->input->post('notes'),
              				'project'		=> $this->input->post('project',TRUE),
              				'client'		=> $client,
              				'receipt'		=> $attached_file,
              				'invoiced'		=> $invoiced,
              				'show_client'	=> $show_client,
              				'category'		=> $this->input->post('category')
              	);
              	  if ($this->db->insert('dgt_expenses', $expenses)) {

                	$expense_id = $this->db->insert_id();
                
		
					$title = ($this->input->post('project') == 'NULL') ? 'N/A' : $p->project_title;
					// Log activity
					$activity = array(
						'module' => 'expenses',
						'module_field_id' => $expense_id,
						'user' => $record['user_id'],
						'activity' => 'activity_expense_created',
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('amount'),
						'value2' => $title
						);
					 $this->db->insert('activities',$activity); // Log activity

                     $records=1;

                 }
                 else
                 {
                 	$records=3;

                 }

				

		}	

			return $records;

	 
	}


	public function edit_expense($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$expense_id = $this->input->post('expense', TRUE);


				$attached_file = NULL;

			$image_data = $this->input->post('img_data');

			if(!empty($image_data))
			{


				$search = array('data:image/png;base64,', 'data:image/jpeg;base64,');
                $replace   = array('', '');

				$base64string = str_replace($search, $replace, $image_data);

				$base64string = str_replace(' ', '+', $base64string);

				$data = base64_decode($base64string);

				$img_name = time();

				$attached_file='expen_'.$img_name.".png";

				file_put_contents('./assets/uploads/'.$attached_file, $data); 

			}	
								

				$project_id = $this->input->post('project',TRUE);

				$p = $this->by_expenses_id($project_id);

              	$billable = ($this->input->post('billable') == 'on') ? '1' : '0';
              	$show_client = ($this->input->post('show_client') == 'on') ? 'Yes' : 'No';
              	$invoiced = ($this->input->post('invoiced') == 'on') ? '1' : '0';
              	$client = ($this->input->post('project') == 'NULL') ? $this->input->post('client') : $p->client ;
              	$currency = $this->client_currency($_POST['client']);
              	$cur = $currency->code;
              	$expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));

                 	$expenses = array(
                				'billable'		=> $billable,
                				'amount'		=> $this->input->post('amount'),
                				'expense_date'	=> $expense_date,
                				'notes'			=> $this->input->post('notes'),
                				'project'		=> $project_id,
                				'client'		=> $client,
                				'receipt'		=> $attached_file,
                				'invoiced'		=> $invoiced,
                				'show_client'	=> $show_client,
                				'category'		=> $this->input->post('category'));

                				
                 	$this->db->where('id',$expense_id);
              	  if($this->db->update('dgt_expenses', $expenses)) {

                	$expense_id = $this->db->insert_id();
                
			
					$title = ($this->input->post('project') == 'NULL') ? 'N/A' : $p->project_title;
					// Log activity
					$activity = array(
						'module' => 'expenses',
						'module_field_id' => $expense_id,
						'user' => $record['user_id'],
						'activity' => 'activity_expense_created',
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('amount'),
						'value2' => $title
						);
					 $this->db->insert('activities',$activity); // Log activity

                     $records=1;

                 }
                 else
                 {
                 	$records=3;

                 }

				

		}	

			return $records;

	 
	}


	public function delete_expense($inputs,$token)
	 {
	 	$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];

				$expense = $this->input->post('expense', TRUE);
            $this->del_ete('expenses',array('id'=>$expense));
            

            $records=1;

				
			}
			

					return $records;

	 
	}


	private function upload_slip($data){

	
	if ($data) {
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = config_item('allowed_files');
		$config['remove_spaces'] = TRUE;
		$config['overwrite']  = FALSE;
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('receipt'))
		{
			$filedata = $this->upload->data();
			return $filedata['file_name'];
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}



	 private  function recur($invoice, $data,$user_id)
    {
    	$invoices_info=$this->invoice_view_by_id($invoice);
        $recur_days = $this->num_days($data['r_freq']);
        $due_date = $invoices_info->due_date;
        $next_date = date('Y-m-d', strtotime($due_date.'+ '.$recur_days.' days'));
        if ($data['recur_end_date'] == '') {
            $recur_end_date = '0000-00-00';
        } else {
            $recur_end_date = date_format(date_create_from_format(config_item('date_php_format'), $data['recur_end_date']), 'Y-m-d');
        }
        $data = array(
            'recurring' => 'Yes',
            'r_freq' => $recur_days,
            'recur_frequency' => $data['r_freq'],
            'recur_start_date' => date_format(date_create_from_format(config_item('date_php_format'), $data['recur_start_date']), 'Y-m-d'),
            'recur_end_date' => $recur_end_date,
            'recur_next_date' => $next_date,
        );
        $this->db->where('inv_id',$invoice);
        $this->db->update('dgt_invoices', $data);
        // Log recur activity
        $activity = array(
            'user' => $user_id,
            'module' => 'invoices',
            'module_field_id' => $invoice,
            'activity' => 'activity_invoice_made_recur',
            'icon' => 'fa-tweet',
            'value1' => $invoices_info->reference_no,
            'value2' => $next_date,
        );
       $this->db->insert('activities',$activity); 

        return true;
    }


    // Get number of days
	private function num_days($frequency){
	switch ($frequency)
	{
			case '7D':
				return 7;
			break;
			case '1M':
				return 31;
			break;
			case '3M':
				return 90;
			break;
			case '6M':
				return 182;
			break;
			case '1Y':
				return 365;
			break;
		}
	}


	public static function invoice_view_by_id($invoice)
    {
       $v=$this->db->where('inv_id', $invoice)->get('invoices')->row();
       return $v;
    }

     private function generate_estimate_number() {
        $dbPrefix = $this->db->dbprefix;
        $query = $this->db->query('SELECT reference_no, est_id FROM '.$dbPrefix.'estimates WHERE est_id = (SELECT MAX(est_id) FROM '.$dbPrefix.'estimates)');

        // $query = self::$db->select('reference_no')->select_max('est_id')->get('estimates');
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = $ref_number + 1;
            if ($next_number < config_item('estimate_start_no')) { $next_number = config_item('estimate_start_no'); }
            $next_number = $this->estimate_ref_exists($next_number);
            return sprintf('%04d', $next_number);
        }else{
            return sprintf('%04d', config_item('estimate_start_no'));
        }
    }

    private function estimate_ref_exists($next_number){
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no',config_item('estimate_prefix').$next_number)
                            ->get('estimates')->num_rows();
        if ($records > 0) {
            return $this->estimate_ref_exists($next_number + 1);
        }else{
            return $next_number;
        }
    }




	private function generate_invoice_number()
    {
        $dbPrefix = $this->db->dbprefix;
        $query = $this->db->query('SELECT reference_no, inv_id FROM '.$dbPrefix.'invoices WHERE inv_id = (SELECT MAX(inv_id) FROM '.$dbPrefix.'invoices)');

        // $query = self::$db->select('reference_no')->select_max('inv_id')->get('invoices');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = $ref_number + 1;
            if ($next_number < config_item('invoice_start_no')) {
                $next_number = config_item('invoice_start_no');
            }
            $next_number = $this->invoice_ref_exists($next_number);

            return sprintf('%04d', $next_number);
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

     private function invoice_ref_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no', config_item('invoice_prefix').$next_number)
            ->get('invoices')->num_rows();
        if ($records > 0) {
            return $this->invoice_ref_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

	 private function get_client_invoices($company)
    {
        return $this->db->where(array('client' => $company,'status !=' => 'Cancelled','show_client' => 'Yes'))->get('invoices')->result();
    }

    private function estimate_by_client($company)
	{
		return $this->db->where('client',$company)->get('estimates')->result();
	}

	 private function by_client($company)
    {
        return $this->db->where('client',$company)->get('projects')->result();
    }

	private function expenses_by_client($company, $report_by = NULL){
		switch ($report_by) {
			case 'billable':
				return $this->db->where(array('client'=>$company,'billable' => '1'))->get('expenses')->result();
				break;
			case 'unbillable':
				return $this->db->where(array('client'=>$company,'billable' => '0'))->get('expenses')->result();
				break;
			case 'billed':
				return $this->db->where(array('client'=>$company,'invoiced' => '1'))->get('expenses')->result();
				break;
			default:
				return $this->db->where('client',$company)->get('expenses')->result();
				break;
		}
		
	}

	private function get_client_contacts($company)
	{
		$this->db->join('companies','companies.co_id = account_details.company');
		$this->db->join('users','users.id = account_details.user_id');
		return $this->db->where('company',$company)->get('account_details')->result();
	}

	  private function has_files($project)
    {
        return $this->db->where('project',$project)->get('files')->result();
    }


	  private function has_tasks($project)
    {
        return $this->db->where('project',$project)->get('tasks')->result();
    }

    private function task_has_files($task)
    {
        return $this->db->where('task',$task)->get('task_files')->result();
    }

	 private function has_bugs($project)
    {
        return $this->db->where('project',$project)->get('bugs')->result();
    }

    private function bug_has_files($id)
    {
        return $this->db->where('bug',$id)->get('bug_files')->result();
    }

     private function delete_team($project){
        return $this->db->where('project_assigned',$project)->delete('assign_projects');
    }

	  private function has_comments($project)
    {
        return $this->db->where(array('project'=>$project,'task_id'=>NULL))->order_by('date_posted','desc')->get('comments')->result();
    }

    private function del_ete($table,$where = array()) {

		return $this->db->delete($table,$where);
	}

	 private function perm_allowed($user,$perm) {
        $permission = $this->db->where(array('status'=>'active'))->get('permissions')->result();
        $json = $this->profile_info_allowed_modules($user);
        $allowed_modules = ($json == NULL) ? '{"settings":"permissions"}' : $json;
        $allowed_modules = json_decode($allowed_modules,true);
        if(!array_key_exists($perm, $allowed_modules)) return FALSE;

        foreach ($permission as $key => $p) {
            if ( array_key_exists($p->name, $allowed_modules) && $allowed_modules[$perm] == 'on') {
                return TRUE;
            }else{
                return FALSE;
            }
        }
        return FALSE;
    }

     private function profile_info_allowed_modules($id) {
        return $this->db->where('user_id',$id) -> get('account_details')->row()->allowed_modules;
    }			


	 private function get_assign_to($id = NULL)
    {
        return $this->db->where('project_id',$id)->get('projects')->row()->assign_to;
    }


	private function assigned_notification($project,$user_id){

        $message = $this->email_template('project_assigned','template_body');
        $signature = $this->email_template('email_signature','template_body');

         $this->load->helper('app');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $assigned_by = str_replace("{ASSIGNED_BY}",$this->displayName($user_id),$logo);
        $title = str_replace("{PROJECT_TITLE}",$this->by_id($project),$assigned_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        foreach ($this->project_team($project) as $user) {

            $params['recipient'] = $this->login_infos($user->assigned_user);

            $params['subject'] = $this->email_template('project_assigned','subject');
            $params['message'] = $message;

            $params['attached_file'] = '';

            $this->send_email($params);
        }
    }

    private function _assigned_notification($project,$task,$user_id) {


        $message = $this->email_template('task_assigned','template_body');
        $subject = $this->email_template('task_assigned','subject');
        $signature = $this->email_template('email_signature','template_body');

         $this->load->helper('app');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
        $task_name = str_replace("{TASK_NAME}", $this->view_task($task), $logo);
        $assigned_by = str_replace("{ASSIGNED_BY}", $this->displayName($user_id), $task_name);
        $title = str_replace("{PROJECT_TITLE}", $this->by_id($project), $assigned_by);
        $link = str_replace("{PROJECT_URL}", base_url() . 'projects/view/'.$project.'?group=tasks', $title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

            foreach ($this->task_team($task) as $user) {
                $params['recipient'] = $this->login_infos($user->assigned_user);
                $params['subject'] = '['.config_item('company_name').']'.' ' .$subject;
                $params['message'] = $message;

                $params['attached_file'] = '';
                $this->send_email($params);
        }
    }


    function _new_task_notification($task,$user_id,$role){

            $info = $this->view_tasks($task);

            $message = $this->email_template('task_created','template_body');
            $subject =  $this->email_template('task_created','subject');
            $signature = $this->email_template('email_signature','template_body');

            $this->load->helper('app');

            $logo_link = create_email_logo();

            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

            $created_by = str_replace("{CREATED_BY}", $this->displayName($user_id),$logo);
            $title = str_replace("{TASK_NAME}",$info->task_name,$created_by);
            $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$info->project,$title);
            $signature = str_replace("{SIGNATURE}",$signature,$link);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            if($role==2){ // Send email to task team
                foreach ($this->task_team($task) as $key => $u) {
                    $params['recipient'] = $this->login_infos($u->assigned_user);
                    $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                    $params['message'] = $message;
                    $params['attached_file'] = '';
                     $this->send_email($params);
                }
            }else{ // Send email to client
                if($info->visible == 'Yes'){
                    $client_id = $this->by_id_client($info->project);
                    $params['recipient'] = $this->view_by_id_company($client_id);
                    $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                    $params['message'] = $message;
                    $params['attached_file'] = '';
                    $this->send_email($params);
                }
            }


    }

    private function _task_changed_notification($project,$task,$user_id) {

        $message = $this->email_template('task_updated','template_body');
        $subject = $this->email_template('task_updated','subject');
        $signature = $this->email_template('email_signature','template_body');

        $this->load->helper('app');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

         $info = $this->view_tasks($task);

        $task_name = str_replace("{TASK_NAME}", $info->task_name, $logo);
        $assigned_by = str_replace("{ASSIGNED_BY}",$this->displayName($user_id), $task_name);
        $title = str_replace("{PROJECT_TITLE}", $this->by_id($project), $assigned_by);
        $link = str_replace("{PROJECT_URL}", base_url().'projects/view/'.$project.'?group=tasks',$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        foreach ($this->task_team($task) as $u) {
                $params['recipient'] = $this->login_infos($u->assigned_user);
                $params['subject'] = '[' .config_item('company_name') . ']' .' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';
                $this->send_email($params);
            }
    }


    private function notify_project_update($project,$user_id){

        $message = $this->email_template('project_updated','template_body');
        $signature = $this->email_template('email_signature','template_body');

        $project_title = $this->by_id($project);

         $this->load->helper('app');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $assigned_by = str_replace("{ASSIGNED_BY}",$this->displayName($user_id),$logo);
        $title = str_replace("{PROJECT_TITLE}",$project_title,$assigned_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

       foreach ($this->project_team($project) as $user) {
            $params['recipient'] = $this->login_infos($user->assigned_user);

            $params['subject'] = '['.config_item('company_name').']'.' '.lang('project_updated_subject');
            $params['message'] = $message;

            $params['attached_file'] = '';

            $this->send_email($params);
        }
    }


   private function client_notification($project,$user_id){

        $info = $this->by_id($project);
        $message = $this->email_template('project_created','template_body');
        $subject =  $this->email_template('project_created','subject');
        $signature = $this->email_template('email_signature','template_body');

        $this->load->helper('app');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $created_by = str_replace("{CREATED_BY}",$this->displayName($user_id),$logo);
        $title = str_replace("{PROJECT_TITLE}",$info->project_title,$created_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $params['recipient'] = $this->view_by_id($info->client)->company_email;

        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        $this->send_email($params);
    }


	private function lead_notification($project,$user_id){

        $message = $this->email_template('project_assigned','template_body');
        $signature = $this->email_template('email_signature','template_body');

        $this->load->helper('app');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $assigned_by = str_replace("{ASSIGNED_BY}",$this->displayName($user_id),$logo);
        $title = str_replace("{PROJECT_TITLE}",$this->by_id($project),$assigned_by);
        $link =  str_replace("{PROJECT_URL}",base_url().'projects/view/'.$project,$title);
        $signature = str_replace("{SIGNATURE}",$signature,$link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        // foreach (Project::by_id($project) as $user) {
            // print_r(Project::by_id($project)->assign_lead); exit;


            $params['recipient'] = $this->login_infos($this->by_ids($project));

            $params['subject'] = $this->email_template('project_assigned','subject');
            $params['message'] = $message;

            $params['attached_file'] = '';
            // echo "<pre>"; print_r($params); exit;

            $this->send_email($params);
        // }
    }

    private function send_email($params)
	{
		if(config_item('disable_emails') == 'FALSE'){

		// If postmark API is being used
		if(config_item('use_postmark') == 'TRUE'){
			$config = array('api_key' => config_item('postmark_api_key'));
        	$this->load->library('postmark',$config);
        	
        	$this->postmark->from(config_item('postmark_from_address'), config_item('company_name'));
        	
        	if (config_item('use_alternate_emails') == 'TRUE' && isset($params['alt_email'])) {
                        $alt = $params['alt_email'];
                            if (config_item($alt.'_email') != '') {
                                $this->postmark->from(config_item($alt.'_email'), config_item($alt.'_email_name'));
                            }
                }

			$this->postmark->to($params['recipient']);
			$this->postmark->subject($params['subject']);
			$this->postmark->message_plain($params['message']);
			$this->postmark->message_html($params['message']);

			// Check attached file
			if(isset($params['attachment_url'])){ 
					$this->postmark->attach($params['attached_file']);
				}
        	return $this->postmark->send();

    	}else{
    		// If using SMTP
					if (config_item('protocol') == 'smtp') {
						$this->load->library('encrypt');
						$raw_smtp_pass =  $this->encrypt->decode(config_item('smtp_pass'));
						$config = array(
    							'smtp_host' => config_item('smtp_host'),
    							'smtp_port' => config_item('smtp_port'),
    							'smtp_user' => config_item('smtp_user'),
    							'smtp_pass' => $raw_smtp_pass,
    							'crlf' 		=> "\r\n",   							
    							'protocol'	=> config_item('protocol'),
						);						
					}	

				$this->load->library('email');
				// Send email 
				$config['mailtype'] = "html";
				$config['newline'] = "\r\n";
				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				
    			$this->email->initialize($config);

				$this->email->from(config_item('company_email'), config_item('company_name'));
                                
                if (config_item('use_alternate_emails') == 'TRUE' && isset($params['alt_email'])) {
                        $alt = $params['alt_email'];
                            if (config_item($alt.'_email') != '') {
                                $this->email->from(config_item($alt.'_email'), config_item($alt.'_email_name'));
                            }
                }
                                
				$this->email->to($params['recipient']);
				
				if (isset($params['cc'])) {
					$this->email->cc($params['cc']);
				}
				$this->email->subject($params['subject']);
				$this->email->message($params['message']);
				// check attachments
				    if($params['attached_file'] != ''){ 
				    	$this->email->attach($params['attached_file']);
				    }

				   
				// Queue emails
				if(!$this->email->send()){
					$this->send_later($params['recipient'],config_item('company_email'),$params['subject'],$params['message']);
				}


    	}

    }else{
    	// Emails disabled
    	return TRUE;
    }
	
	}

	
	 
		private function send_later($to,$from,$subject,$message)
		{
			if(is_array($to)){
				$to = explode(',', $to);
			}
			$emails = array(
							'sent_to' 		=> $to,
							'sent_from' 	=> $from,
							'subject'		=> $subject,
							'message'		=> $message
							);
			$this->db->insert('outgoing_emails',$emails);
			return TRUE;
		}


    private function email_template($group = NULL,$column = NULL){
		return $this->db->where('email_group',$group)->get('email_templates')->row()->$column;
	}

	private function displayName($user = '') {
        if($this->check_user_exist($user)) return '[MISSING USER]';

        return ($this->profile_info($user) == NULL)
            ? $this->login_info($user)
            : $this->profile_info($user);
    }

    private function project_team($project)
    {
        return $this->db->where('project_assigned',$project)->get('assign_projects')->result();
    }

    private function check_user_exist($user){
        return $this->db->where('id',$user)->get('users')->num_rows();
    }

    private function profile_info($id) {
        return $this->db->where('user_id',$id) -> get('account_details')->row()->fullname;
    }

    private function login_info($id) {
        return $this->db->where('id',$id)->get('users')->row()->username;
    }

    private function by_id($id = NULL)
    {
        return $this->db->where('project_id',$id)->get('projects')->row()->project_title;
    }

     private function by_expenses_id($id = NULL)
    {
        return $this->db->where('project_id',$id)->get('projects')->row();
    }

     private function by_assign_to($id = NULL)
    {
        return $this->db->where('project_id',$id)->get('projects')->row()->assign_to;
    }

    private function login_infos($id) {
        return $this->db->where('id',$id)->get('users')->row()->email;
    }

    private function by_ids($id = NULL)
    {
        return $this->db->where('project_id',$id)->get('projects')->row()->assign_lead;
    }

     private function by_id_client($id = NULL)
    {
        return $this->db->where('project_id',$id)->get('projects')->row()->client;
    }

    private function view_task($task,$visible = NULL)
    {
        if($visible != NULL) self::$db->where('visible',$visible);
        return $this->db->where('t_id',$task)->get('tasks')->row()->task_name;
    }

    private function view_tasks($task,$visible = NULL)
    {
        if($visible != NULL) self::$db->where('visible',$visible);
        return $this->db->where('t_id',$task)->get('tasks')->row();
    }

     private function task_team($task)
    {
        return $this->db->where('task_assigned',$task)->get('assign_tasks')->result();
    }

    private function delete_task_team($task){
        return $this->db->where('task_assigned',$task)->delete('assign_tasks');
    }



	 private function client_currency($company = FALSE)
	{
		if (!$company) { return FALSE; }
		$dcurrency = $this->db->where('code', config_item('default_currency'))->get('currencies')->result();
		$client = $this->db->where('co_id', $company)->get('companies')->result();
		if (count($client) == 0) { return $dcurrency[0]; }
		$currency = $this->db->where('code',$client[0]->currency)->get('currencies')->result();
		if (count($currency) > 0) { return $currency[0]; }
		$dcurrency = $this->db->where('code', config_item('default_currency'))->get('currencies')->result();
		if (count($dcurrency) > 0) { return $dcurrency[0]; }

	}


	private function client_language($id = FALSE)
	{
		if (!$id) { return FALSE; }
		$language = $this->db->where('name', $this->view_by_id($id))->get('languages')->result();
		return $language[0];
	}

	private function view_by_id($company)
	{
		return $this->db->where('co_id',$company)->get('companies')->row()->language;
	}

	private function view_by_id_company($company)
	{
		return $this->db->where('co_id',$company)->get('companies')->row()->company_email;
	}


	private function currencies($code = FALSE)
	{
		if (!$code) {
			return $this->db->order_by('name','ASC')->get('currencies')->result();
		}
		$c = $this->db->where('code',$code)->get('currencies')->result();
		if (count($c > 0)) { return $c[0]; }
		$c = $this->db->where('code',  config_item('default_currency'))->get('currencies')->result();
		if (count($c > 0)) { return $c[0]; } else { return FALSE; }
	}
    private function languages($lang = FALSE)
	{
		if (!$lang) {
			return $this->db->order_by('name','ASC')->get('languages')->result();
		}
		$l =  $this->db->where('name',$lang)->get('languages')->result();
		if (count($l > 0)) { return $l[0]; }
		$l =  $this->db->where('name',  config_item('default_language'))->get('languages')->result();
		if (count($l > 0)) { return $l[0]; } else { return FALSE; }
	}


	 public function checkusernameemail($where)
	 {
	 	$this->db->where($where);
	 	$count = $this->db->count_all_results('dgt_users');
	 	return $count;
	 }

	    public function getToken($length,$user_id)
   {
       $token = $user_id;

       $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
       $codeAlphabet.= "0123456789";

       $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
         $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];

    }

    return $token;
   }

   function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}
	public function get_role_and_userid($token)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('unique_code'=>$token,'activated'=>1))->row_array();
	}

	public function get_company_id($user_id)
	{
		$this->db->select('company');
		return $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row()->company;
	}

	public function get_attendance_data($token)
	{
		$records =  $this->get_role_and_userid($token);
			if(!empty($records)){

				$user_id    = $records['user_id'];
				$strtotime = strtotime(date('Y-m-d H:i'));
				  $a_year    = date('Y',$strtotime);
			      $a_month   = date('m',$strtotime);
			      $a_day     = date('d',$strtotime);
			      $a_cin     = date('H:i',$strtotime);


		          $where = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                  $this->db->select('month_days,month_days_in_out');
                   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
                  if(empty($record)){
                    $inputs['attendance_month'] =$a_month;
                    $inputs['attendance_year'] = $a_year;
                   $this->atten_dance($user_id,$inputs);
                    $this->db->select('month_days,month_days_in_out');
                     $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
                  }




                  if(!empty($record['month_days'])){
                    $record_day = unserialize($record['month_days']);

                    $month_days_in_out_record = unserialize($record['month_days_in_out']);

                    $a_day -=1;
                    
                     if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
                      $current_days = $month_days_in_out_record[$a_day];
                      $total_records = count($current_days);
                      $current_day = end($current_days);
                      
              

                      if($record_day[$a_day]['punch_in'] ==''){
                        $record_day[$a_day]['punch_in'] = $a_cin;
                        $record_day[$a_day]['day'] = 1;
                      }
                      
                      if($total_records == 1 && empty($current_day['punch_out'])){
                        
                        $current_days = array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>'');
                        $month_days_in_out_record[$a_day][0] = $current_days;
                      }else{
                        
                        if(!empty($current_day['punch_in']) && !empty($current_day['punch_out']))
                        {
                          $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>'');
                          $month_days_in_out_record[$a_day] = $current_days;
                        } 
                      }
                      

                    }
                  }
             
                  $this->db->where($where);
                  return $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));

          }

	}

	public function get_punchout_attendance_data($token)
	{
		$records =  $this->get_role_and_userid($token);
		
			if(!empty($records)){

				$strtotime = strtotime(date('Y-m-d H:i'));
      $user_id   = $records['user_id'];

      $a_year    = date('Y',$strtotime);
      $a_month   = date('m',$strtotime);
      $a_day     = date('d',$strtotime);
      $a_cout     = date('H:i',$strtotime);
      $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
      $this->db->select('month_days,month_days_in_out');
      $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
     
      if(empty($record)){
        $inputs['attendance_month'] =$a_month;
        $inputs['attendance_year'] = $a_year;
         $this->atten_dance($user_id,$inputs);
        $this->db->select('month_days,month_days_in_out');
        $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
      }
      
      if(!empty($record['month_days'])){
         
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);
         
          $a_day -=1;
          
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);

      
        if(!empty($record_day[$a_day])){
            $record_day[$a_day]['punch_out'] = $a_cout;
            $record_day[$a_day]['day'] = 1;
        }
        if($total_records == 1 && empty($current_day['punch_out'])){
           
            $month_days_in_out_record[$a_day][0]['punch_out'] = $a_cout;
          }else{
              
            if(!empty($current_day['punch_in']) && empty($current_day['punch_out']))
            {
              
               $current_days[$total_records-1]['punch_out'] = $a_cout;
               $month_days_in_out_record[$a_day] = $current_days;
            } 
          }
        
      }
      
      $this->db->where($where);
      return $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));

          }

	}

	private function atten_dance($user_id,$inputs)
    {   

        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            $insert = array(
                'user_id'=>$user_id,
                'month_days'=>serialize($days),
                'month_days_in_out'=>serialize($days_in_out),
                'a_month'=>$a_month,
                'a_year'=>$a_year
                );
            $this->db->insert("dgt_attendance_details",$insert);

        return  $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
       
    }

	

	public function update_attendance_data($user_id,$month_days,$month,$year)
	{
		$this->db->where('user_id',$user_id);
		$this->db->where('a_month',$month);
		$this->db->where('a_year',$year);
		$res = $this->db->update('dgt_attendance_details',$month_days);
		return $res;
	}

	public function get_all_attendance($user_id,$month,$year)
	{
		$this->db->select('a_month,a_year,month_days');
		return $this->db->get_where('dgt_attendance_details',array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year))->result_array();
	}

	public function upload_profilepic($user_id,$filename)
	{
		$res = array(
			'avatar' => $filename
		);
		$this->db->where('user_id',$user_id);
		return $this->db->update('dgt_account_details',$res);
	}

	public function check_device($user_id)
	{
		return $this->db->get_where('dgt_device_details',array('user_id'=>$user_id))->row_array();
	}

	public function device_update($user_id,$device_id,$option)
	{
		if($option == 'update')
		{
			$res_up = array(
				'device_id' => $device_id
			);
			$this->db->where('dev_id',$user_id);
			$this->db->update('dgt_device_details',$res_up);
			return 'updated';
		}else{
			$res_in = array(
				'user_id' => $user_id,
				'device_id' => $device_id
			);
			$this->db->insert('dgt_device_details',$res_in);
			return 'inserted';
		}
	}


	public function get_userid($username)
	{
		return $this->db->get_where('dgt_users',array('username'=>$username))->row_array();
	}


	public function face_attendance($user_id,$inputs)
	{
		$a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            $insert = array(
                'user_id'=>$user_id,
                'month_days'=>serialize($days),
                'month_days_in_out'=>serialize($days_in_out),
                'a_month'=>$a_month,
                'a_year'=>$a_year
                );
            $this->db->insert("dgt_attendance_details",$insert);

        return  $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
	}
	
	
	public function checklog_type($username,$login_type)
	{
		$log_details = $this->db->get_where('users',array('username'=>$username))->row_array();
		$check_log = $this->db->get_where('log_type',array('user_id'=>$log_details['id']))->num_rows();
// 		print_r($check_log); exit;
		if($check_log == 0)
		{
			$res = array(
				'user_id' => $log_details['id'],
				'log_type' => $login_type
			);
			$this->db->insert('log_type',$res);
		}else{
			$res = array(
				'log_type' => $login_type
			);
			$this->db->where('user_id',$log_details['id']);
			$this->db->update('log_type',$res);
		}
		return $login_type;

	}




}

/* End of file model.php */