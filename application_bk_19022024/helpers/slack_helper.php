<?php 

class Slack_Post {
 
	

 
	function __construct() {
		$ci  =&get_instance();
		$ci->lang->load('fx_slack', config_item('default_language'));
		// slack info
		$this->url = $ci->config->item('slack_webhook');
		$this->channel = $ci->config->item('slack_channel');
		$this->slack_user = $ci->config->item('slack_username');

	}


/**
 * Posts a notification to slack
 * @param $ticket id
 * @return slack response
 */

 function slack_create_ticket($ticket_id,$user) {

		$data = $this->get_ticket_by_id($ticket_id);

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('slack_created_ticket'),
					            				User::displayName($user),$data->ticket_code).'",

					            "title": "'.$data->subject.'",
					            "title_link": "'.base_url().'tickets/view/'.$ticket_id.'",
					            "fallback": "'.strip_tags(nl2br($data->body)).'",
					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($user).'",
                    							"short": true
                							},
							                {
							                    "title": "Status",
							                    "value": "'.$data->status.'",
							                    "short": true
							                },
							                {
							                    "title": "Title",
							                    "value": "'.$data->subject.'",
							                    "short": true
							                },
							                {
							                    "title": "Department",
							                    "value": "'.$this->get_dept_by_id($data->department)->deptname.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($data->body)).'",
							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    }

 /**
 * Posts a ticket reply to slack
 * @param $ticket id
 * @return slack response
 */

 function slack_reply_ticket($ticket_id,$user,$reply) {

		$data = $this->get_ticket_by_id($ticket_id);

		$reply = $this->get_ticket_reply_by_id($reply);

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('slack_reply_ticket'),
					            			User::displayName($user),$data->ticket_code).'",

					            "title": "'.$data->subject.'",
					            "title_link": "'.base_url().'tickets/view/'.$ticket_id.'",
					            "fallback": "'.strip_tags(nl2br($reply->body)).'",
					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($user).'",
                    							"short": true
                							},
							                {
							                    "title": "Status",
							                    "value": "'.ucfirst($data->status).'",
							                    "short": true
							                },
							                {
							                    "title": "Title",
							                    "value": "'.$data->subject.'",
							                    "short": true
							                },
							                {
							                    "title": "Department",
							                    "value": "'.$this->get_dept_by_id($data->department)->deptname.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($reply->body)).'",
							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    }


    // post a notification on ticket status change

    function slack_ticket_changed($ticket_id,$status,$user){

    	$data = $this->get_ticket_by_id($ticket_id);

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('ticket_changed_status_pretext'),
					            			User::displayName($user),$data->ticket_code).'",

					            "title": "'.sprintf(lang('ticket_changed_status_title'), $data->subject).'",
					            "title_link": "'.base_url().'tickets/view/'.$ticket_id.'",
					            "fallback": "'.sprintf(lang('ticket_changed_status_message'), 
							                    			$data->subject,
							                    			ucfirst($status),
							                    			User::displayName($user)).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($user).'",
                    							"short": true
                							},
							                {
							                    "title": "Status",
							                    "value": "'.ucfirst($data->status).'",
							                    "short": true
							                },
							                {
							                    "title": "Title",
							                    "value": "'.sprintf(lang('ticket_changed_status_title'), $data->subject).'",
							                    "short": true
							                },
							                {
							                    "title": "Department",
							                    "value": "'.$this->get_dept_by_id($data->department)->deptname.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.sprintf(lang('ticket_changed_status_message'), 
							                    			$data->subject,
							                    			ucfirst($status),
							                    			User::displayName($user)).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }


     // post a notification on opened projects

    function slack_create_project($id,$user){

    	$data = $this->get_project_by_id($id);

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('new_project_pretext'),
					            			User::displayName($user),ucfirst($data->project_title)).'",

					            "title": "'.sprintf(lang('new_project_title'), $data->project_title).'",
					            "title_link": "'.base_url().'projects/view/'.$id.'",

					            "fallback": "'.strip_tags(nl2br($data->description)).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($user).'",
                    							"short": true
                							},
							                {
							                    "title": "Budget Hours",
							                    "value": "'.$data->estimate_hours.'",
							                    "short": true
							                },
							                {
							                    "title": "Due Date",
							                    "value": "'.$data->due_date.'",
							                    "short": true
							                },
							                {
							                    "title": "Client",
							                    "value": "'.$this->get_client_by_id($data->client)->company_name.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($data->description)).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }

    // post a notification on milestone added/updated

    function slack_project_comment($params){

    	$data = $this->get_project_by_id($params['project']);
    	$com = $this->get_comment_by_id($params['comment']);

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('new_comment_pretext'),
					            			User::displayName($com->posted_by)).'",

					            "title": "'.sprintf(lang('new_comment_title'), $data->project_title).'",
					            "title_link": "'.base_url().'projects/view/'.$params['project'].'",

					            "fallback": "'.strip_tags(nl2br($com->message)).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($com->posted_by).'",
                    							"short": true
                							},
							                {
							                    "title": "Project Title",
							                    "value": "'.$data->project_title.'",
							                    "short": true
							                },
							                {
							                    "title": "Due Date",
							                    "value": "'.$data->due_date.'",
							                    "short": true
							                },
							                {
							                    "title": "Client",
							                    "value": "'.$this->get_client_by_id($data->client)->company_name.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($com->message)).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }

    // post a notification on comment reply
    function slack_project_comment_reply($params){

    	$data = $this->get_project_by_id($params['project']);
    	$com = $this->get_reply_by_id($params['reply']);

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('new_reply_pretext'),
					            			User::displayName($com->replied_by)).'",

					            "title": "'.sprintf(lang('new_comment_title'), $data->project_title).'",
					            "title_link": "'.base_url().'projects/view/'.$params['project'].'",

					            "fallback": "'.strip_tags(nl2br($com->reply_msg)).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($com->replied_by).'",
                    							"short": true
                							},
							                {
							                    "title": "Project Title",
							                    "value": "'.$data->project_title.'",
							                    "short": true
							                },
							                {
							                    "title": "Due Date",
							                    "value": "'.$data->due_date.'",
							                    "short": true
							                },
							                {
							                    "title": "Client",
							                    "value": "'.$this->get_client_by_id($data->client)->company_name.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($com->reply_msg)).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    }

    // post a notification on milestone added/updated

    function slack_milestone_action($project,$milestone,$user,$action){

    	$data = $this->get_project_by_id($project);
    	$m = $this->get_milestone_by_id($milestone);

    	$arg = array(
    		'new_milestone' => array(
    			'added_pretext' 	=> lang('new_milestone_pretext'),
    			'added_title'		=> lang('new_milestone_title')
    			),
    		'milestone_edit'	=> array(
    			'edited_pretext'	=> lang('milestone_updated_pretext'),
    			'edited_title'		=> lang('milestone_updated_title')
    			)
    		);
    	switch ($action) {
    		case 'added':
    				$pretext 	= $arg['new_milestone']['added_pretext'];
    				$title 		= $arg['new_milestone']['added_pretext'];
    			break;
    		
    		default:
    				$pretext 	= $arg['milestone_edit']['edited_pretext'];
    				$title 		= $arg['milestone_edit']['edited_title'];
    			break;
    	}

		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf($pretext,
					            			User::displayName($user),ucfirst($m->milestone_name)).'",

					            "title": "'.sprintf($title, $m->milestone_name).'",
					            "title_link": "'.base_url().'projects/view/'.$project.'",

					            "fallback": "'.strip_tags(nl2br($m->description)).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($user).'",
                    							"short": true
                							},
							                {
							                    "title": "Project Title",
							                    "value": "'.$data->project_title.'",
							                    "short": true
							                },
							                {
							                    "title": "Due Date",
							                    "value": "'.$m->due_date.'",
							                    "short": true
							                },
							                {
							                    "title": "Client",
							                    "value": "'.$this->get_client_by_id($data->client)->company_name.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($m->description)).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }


     function slack_project_file($params){

    	$data = $this->get_project_by_id($params['project']);
    
		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf(lang('upload_project_file'),
					            			User::displayName($params['user'])).'",

					            "title": "'.sprintf(lang('new_file'), $data->project_title).'",
					            "title_link": "'.base_url().'projects/view/'.$params['project'].'",

					            "fallback": "'.sprintf(lang('upload_project_files'),
					            				User::displayName($params['user']),
					            				$data->project_title).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Uploaded by",
                    							"value": "'.User::displayName($params['user']).'",
                    							"short": true
                							},
							                {
							                    "title": "Project Title",
							                    "value": "'.$data->project_title.'",
							                    "short": true
							                },
							                {
							                    "title": "Due Date",
							                    "value": "'.$data->due_date.'",
							                    "short": true
							                },
							                {
							                    "title": "Client",
							                    "value": "'.$this->get_client_by_id($data->client)->company_name.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.sprintf(lang('upload_project_files'),
					            				User::displayName($params['user']),
					            				$data->project_title).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }


     // post a notification on task added/updated

    function slack_task_action($project,$task,$user,$action){

    	$data = $this->get_project_by_id($project);
    	$task = $this->get_task_by_id($task);

    	$arg = array(
    		'new_task' => array(
    			'added_pretext' 	=> lang('task_added_pretext'),
    			'added_title'		=> lang('task_added_title')
    			),
    		'task_edit'	=> array(
    			'edited_pretext'	=> lang('task_edited_pretext'),
    			'edited_title'		=> lang('task_edited_title')
    			)
    		);
    	switch ($action) {
    		case 'added':
    				$pretext = $arg['new_task']['added_pretext'];
    				$title = $arg['new_task']['added_pretext'];
    			break;
    		
    		default:
    				$pretext = $arg['task_edit']['edited_pretext'];
    				$title = $arg['task_edit']['edited_title'];
    			break;
    	}
    
		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf($pretext,
					            			User::displayName($user)).'",

					            "title": "'.sprintf($title, $task->task_name).'",
					            "title_link": "'.base_url().'projects/view/'.$project.'",

					            "fallback": "'.strip_tags(nl2br($task->description)).'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Creator",
                    							"value": "'.User::displayName($user).'",
                    							"short": true
                							},
							                {
							                    "title": "Project Title",
							                    "value": "'.$data->project_title.'",
							                    "short": true
							                },
							                {
							                    "title": "Due Date",
							                    "value": "'.$task->due_date.'",
							                    "short": true
							                },
							                {
							                    "title": "Client",
							                    "value": "'.$this->get_client_by_id($data->client)->company_name.'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.strip_tags(nl2br($task->description)).'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }

     // post a notification on bug added/updated

    function slack_bug_action($params){



    	$data 	= $this->get_project_by_id($params['project']);
    	$bug 	= $this->get_bug_by_id($params['bug']);

    	$arg = array(
    		'new_bug' => array(
    			'added_pretext' 	=> lang('bug_added_pretext'),
    			'added_title'		=> lang('bug_added_title')
    			),
    		'bug_edit'	=> array(
    			'edited_pretext'	=> lang('bug_edited_pretext'),
    			'edited_title'		=> lang('bug_edited_title')
    			),
    		'file_upload' => array(
    			'upload_pretext'	=> lang('file_upload_pretext'),
    			'upload_title'		=> lang('file_upload_title'),
    			'upload_message'	=> lang('file_upload_message'),
    			)
    		);
    	switch ($params['action']) {
    		case 'added':
    				$pretext 	= $arg['new_bug']['added_pretext'];
    				$title 		= $arg['new_bug']['added_pretext'];
    				$message 	= strip_tags(nl2br($bug->bug_description));
    			break;
    		case 'file_upload':
    				$pretext 	= $arg['file_upload']['upload_pretext'];
    				$title 		= $arg['file_upload']['upload_title'];
    				$message    = $arg['file_upload']['upload_message'];
    			break;
    		
    		default:
    				$pretext 	= $arg['bug_edit']['edited_pretext'];
    				$title 		= $arg['bug_edit']['edited_title'];
    				$message 	= strip_tags(nl2br($bug->bug_description));
    			break;
    	}
    
		$data = '
		{
			"channel":"'.$this->channel.'",
			"username":"'.$this->slack_user.'",
			"unfurl_links": true,
			"attachments": [
					        {
					            "color": "good",
					            "pretext": "'.sprintf($pretext,
					            			User::displayName($params['user'])).'",

					            "title": "'.sprintf($title, $bug->issue_title).'",
					            "title_link": "'.base_url().'projects/view/'.$params['project'].'",

					            "fallback": "'.$message.'",

					            "parse" : "full",
					            "fields": [
                							{
                    							"title": "Reporter",
                    							"value": "'.User::displayName($bug->reporter).'",
                    							"short": true
                							},
							                {
							                    "title": "Project Title",
							                    "value": "'.$data->project_title.'",
							                    "short": true
							                },
							                {
							                    "title": "Priority",
							                    "value": "'.ucfirst($bug->priority).'",
							                    "short": true
							                },
							                {
							                    "title": "Status",
							                    "value": "'.ucfirst($bug->bug_status).'",
							                    "short": true
							                },
							                {
							                    "title": "Content",
							                    "value": "'.$message.'",

							                    "short": false
							                }
            							  ]
					            
					        }
    					   ]
		}';
		

		$this->slack_remote_post($data,$this->url);
    
    }

    function get_comment_by_id($comment){
    	// Get file information
    	$ci  =&get_instance();
    	return $ci->db->where('comment_id',$comment)->get('comments')->row();
    }
    function get_reply_by_id($reply){
    	// Get file information
    	$ci  =&get_instance();
    	return $ci->db->where('reply_id',$reply)->get('comment_replies')->row();
    }
    

    function get_file_by_id($file){
    	// Get file information
    	$ci  =&get_instance();
    	return $ci->db->where('file_id',$file)->get('files')->row();
    }

    function get_bug_by_id($bug){
    	// Get project information
    	$ci  =&get_instance();
    	return $ci->db->where('bug_id',$bug)->get('bugs')->row();
    }

    function get_task_by_id($task){
    	// Get project information
    	$ci  =&get_instance();
    	return $ci->db->where('t_id',$task)->get('tasks')->row();
    }

    function get_milestone_by_id($milestone){
    	// Get project information
    	$ci  =&get_instance();
    	return $ci->db->where('id',$milestone)->get('milestones')->row();
    }

    function get_project_by_id($id){
    	// Get project information
    	$ci  =&get_instance();
    	return $ci->db->where('project_id',$id)->get('projects')->row();
    }

    function get_ticket_by_id($ticket){
    	// Get ticket information
    	$ci  =&get_instance();
    	return $ci->db->where('id',$ticket)->get('tickets')->row();
    }

    function get_ticket_reply_by_id($reply_id){
    	// Get ticket reply information
    	$ci  =&get_instance();
    	return $ci->db->where('id',$reply_id)->get('ticketreplies')->row();
    }

    function get_dept_by_id($dept){
    	// Get the department name
    	$ci  =&get_instance();
    	return $ci->db->where('deptid',$dept)->get('departments')->row();
    }

    function get_client_by_id($client){
    	// Get the client name
    	$ci  =&get_instance();
    	return $ci->db->where('co_id',$client)->get('companies')->row();
    }


    function slack_remote_post($data,$url){
    	        // You can get your webhook endpoint from your Slack settings

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('payload' => $data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


}
    // End of file slack_helper.php