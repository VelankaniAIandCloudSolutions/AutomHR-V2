<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Chats_model extends CI_Model
{
  function user_chat_history($user_id)
	{

		/*$usr_qry = "SELECT c.chat_id,
					 CASE
						WHEN c.chat_from = ".$user_id." THEN (chat_to)
						WHEN c.chat_from != ".$user_id." THEN (chat_from)
						ELSE 0
					 END AS user_id,
					 CASE
						WHEN c.chat_from = ".$user_id." THEN (SELECT  fullname FROM dgt_account_details WHERE user_id = c.chat_to)
						WHEN c.chat_from != ".$user_id." THEN (SELECT fullname FROM dgt_account_details WHERE user_id = c.chat_from)
						ELSE ''
					 END AS fullname,
					 CASE
						WHEN c.chat_from = ".$user_id." THEN (SELECT  count(id) FROM dgt_chats_text WHERE text_from = c.chat_to and text_to = ".$user_id." and text_status = 0)
						WHEN c.chat_from != ".$user_id." THEN (SELECT  count(id) FROM dgt_chats_text WHERE text_from = c.chat_from and text_to = ".$user_id." and text_status = 0)
						ELSE ''
						END AS new_chats,
					 (SELECT concat(text_from,'[^]',text_to,'[^]',text_content,'[^]',text_date_time) as det FROM dgt_chats_text WHERE text_to = user_id order by text_date_time desc limit 1) as last_chat
					 FROM `dgt_chats` c 
					 WHERE (c.chat_from = ".$user_id." or c.chat_to = ".$user_id.") order by chat_date_time desc";  
 		$query   = $this->db->query($usr_qry); 
 		//chat_id = c.chat_id
 		//echo $this->db->last_query();
 		//exit;
		if ($query->num_rows() > 0){
			return $query->result();
		} */

	 $id =$user_id;
      
      $this->db->select('id,text_from,text_to');
      $this->db->from('dgt_chats_text');
      $this->db->where("text_from = $user_id OR text_to = $user_id");
      $this->db->order_by('id', 'desc');
      $chat_history = $this->db->get()->result_array();
      
      $chat_user_ids = array();
      if(!empty($chat_history)){
        foreach ($chat_history as $history) {
       	  $from = $history['text_from'];
          if(!in_array($from, $chat_user_ids) && $from!=$id){
            $chat_user_ids[] = $from; 
          }
          $to = $history['text_to'];
          if(!in_array($to, $chat_user_ids) && $to!=$id){
            $chat_user_ids[] = $to; 
            //$chat_user_ids[] = $to; 
          }
        }
      }
      $chat_user_ids = array_filter($chat_user_ids);

      $chathistory = array();

      if(!empty($chat_user_ids)){
      	foreach ($chat_user_ids as $chat_id) {

      	$result = $this->db->query("SELECT CT.id as chat_id, if(text_from = $user_id, text_to,text_from) as user_id,CONCAT(text_from,'[^]',text_to,'[^]',text_content,'[^]',text_date_time) as last_chat,text_status as new_chats FROM `dgt_chats_text` CT  WHERE (text_from = $user_id and text_to =$chat_id) OR (text_from = $chat_id and text_to =$user_id) order by text_date_time DESC limit 1")->row_array();
		$this->db->select('fullname');
		 
      	if(!empty($result)){
      			$data = $this->db->get_where('dgt_account_details', array('user_id' => $chat_id))->row_array();	
      			$result['fullname'] = $data['fullname'];
				// $chathistory[] = (object)$result; 
				$this->db->select('online_status');
				$d = $this->db->get_where('dgt_users', array('id' => $chat_id))->row_array();	
      			$result['online_status'] = $d['online_status'];
				$chathistory[] = (object)$result;      		
      		}
      	}
      }
      /*echo "<pre>";
    print_r($chathistory);exit;*/
	return $chathistory;
    
	}
 function chat_text_details($user1,$user2,$order_by)
	{
		$qry      = "SELECT ct.*,ad1.fullname as from_user_name,ad2.fullname as to_user_fullname
					 FROM `dgt_chats_text` ct
					 left join dgt_account_details as ad1 on ad1.user_id = ct.text_from
					 left join dgt_account_details as ad2 on ad2.user_id = ct.text_to
					 where (ct.text_from = ".$user1." and ct.text_to = ".$user2.") OR 
					 (ct.text_from = ".$user2." and ct.text_to = ".$user1." )"; 
		if($order_by == 1){ $qry .= "order by ct.id asc";}			 
		if($order_by == 2){ $qry .= "order by ct.id asc";}	 
  		$query   = $this->db->query($qry); 
		if ($query->num_rows() > 0){
			return $query->result_array();
		} 
	}	
	
 function check_already_chat($user1,$user2)
	{
		$usr_qry = "SELECT  chat_id from dgt_chats WHERE (chat_from = ".$user1." and chat_to = ".$user2.") OR (chat_from = ".$user2." and chat_to = ".$user1.")";  
 		$query   = $this->db->query($usr_qry); 
		if ($query->num_rows() > 0){
			$chat = $query->result_array();
			return $chat[0]['chat_id'];
		} 
	}	
	 
function inser_chat($data,$sts)
	{ 
	    if($sts == 1){
	    	$this->db->insert('dgt_chats',$data);
			return $this->db->insert_id();
		}else if($sts == 2){
			$this->db->update('dgt_chats',$data,array('chat_id'=>$data['chat_id']));
		}
	}		 
	 
	 function new_chat_user($name='')
	 {
	 	$user_id = $this->session->userdata('user_id');
	 	$query = $this->db->query("SELECT chat_from,chat_to FROM dgt_chats WHERE chat_from = $user_id OR chat_to = $user_id");
	 	$ids = array();
	 	$ids[] = $user_id;
	 	if($query->num_rows() > 0){
	 		$datas = $query->result_array();
	 		foreach ($datas as  $value) {
	 			$ids[] = $value['chat_from'];
	 			$ids[] = $value['chat_to'];
	 		}
	 		$ids = array_unique($ids);
	 	}

	 	$query_data = $this->db->query("SELECT fullname,user_id FROM dgt_account_details WHERE user_id NOT IN (".implode(',',$ids).") AND fullname like '%".$name."%'");
		$results = array();
		 
		if($query_data->num_rows() > 0){
			$results = $query_data->result_array();
		}
		return $results;
	 }

	 public function new_chat_userdetails($id='')
	 {
	 	$query_data = $this->db->query("SELECT fullname,user_id,avatar FROM dgt_account_details WHERE user_id = $id")->row_array();
	 	
	 	$from_id = $this->session->userdata('user_id');
		$to_id = $query_data['user_id'];	
		$array['chat_from'] = $from_id;
		$array['chat_to']= $to_id;
		$array['chat_date_time']= date('Y-m-d h:i:s'); 
		$id = $this->inser_chat($array,1);

	   $data2['text_from']         = $from_id;
	   $data2['text_to']           = $to_id;
	   $data2['text_content']      = ''; 
 	   $data2['text_date_time']    = date('Y-m-d h:i:s'); 
	   $this->db->insert('dgt_chats_text',$data2);
 	   $last_id                    = $this->db->insert_id();
	    $query_data['last_id']	= $last_id;
	    $query_data['lastdate']	= date('d M',strtotime($data2['text_date_time']));
	    return $query_data;
	 }


	 public function user_checkById($user_id)
	 {
	 	return $this->db->get_where('users',array('id'=>$user_id))->row_array();
	 }

	 public function check_session_exists($session_id)
	 {
	 	return $this->db->get_where('dgt_chat_connection',array('session_id' =>$session_id))->row_array();
	 }

	 public function get_session_connection($user_id)
	 {
	 	return $this->db->get_where('dgt_chat_connection',array('from_id' =>$user_id))->row_array();
	 }

	 public function get_common_session()
	 {
	 	return $this->db->get('dgt_chat_common_session',array('com_sess_id'=>1))->row_array();
	 }

	 public function check_connections($user_id)
	 {
	 	return $this->db->get_where('dgt_chat_connectionids',array('user_id'=>$user_id))->row_array();
	 }
	 public function get_chat_messagesbyID($user_id,$chat_type)
	 {
	 	$this->db->like('from_id',$user_id);
	 	$this->db->or_like('to_id',$user_id);
	 	$this->db->where('msg_type',$chat_type);
	 	return $this->db->get('dgt_chat_conversations')->result_array();
	 }

	 public function get_groupsByUserId($user_id)
	 {
	 	return $this->db->select('*')
	 					->from('dgt_chat_group_members GM')
	 					->join('dgt_chat_group_details GD', 'GD.group_id = GM.group_id')
	 					->where('GM.login_id',$user_id)
	 					->get()->result_array();
	 	// return $this->db->get_where('dgt_chat_group_members',array('login_id'=>$user_id))->result_array();
	 }

	 public function getChatList($log_id,$chat_type)
	 {
	 	if($chat_type == 'group')
	 	{
	 		return $this->db->select('*')
					 				   ->from('dgt_chat_conversations CC')
					 				   ->join('dgt_chat_group_details GD','CC.group_id = GD.group_id')
					 				   ->where('CC.from_id',$log_id)
					 				   ->where('CC.msg_type',$chat_type)
					 				   ->group_by('CC.group_id')
					 				   ->order_by('CC.msg_date','DESC')
					 				   ->get()->result_array();
	 	}else if($chat_type == 'one')
	 	{
	 		$query1 = $this->db->select('*')
	 				 ->from('dgt_chat_conversations CC')
	 				 ->join('dgt_account_details AD','AD.user_id = CC.to_id')
	 				 ->join('dgt_users U','U.id = AD.user_id')
	 				 ->where('CC.from_id',$log_id)
	 				 ->where('CC.msg_type',$chat_type)
	 				 ->where('CC.group_id',0)
	 				 // ->or_where('CC.to_id',$log_id)
	 				 // ->where('CC.msg_type',$chat_type)
	 				 // ->where('CC.group_id',0)
	 				 ->group_by('CC.to_id')
	 				 ->order_by('CC.msg_date','DESC')
	 				 ->get()->result_array();
	 		$query2 = $this->db->select('*')
	 				 ->from('dgt_chat_conversations CC')
	 				 ->join('dgt_account_details AD','AD.user_id = CC.from_id')
	 				 ->join('dgt_users U','U.id = AD.user_id')
	 				 ->where('CC.to_id',$log_id)
	 				 ->where('CC.msg_type',$chat_type)
	 				 ->where('CC.group_id',0)
	 				 ->group_by('CC.from_id')
	 				 ->order_by('CC.msg_date','DESC')
	 				 ->get()->result_array();
	 				// echo $this->db->last_query();exit; 
	 				$query3= array_merge($query1,$query2);
	 				//print_r($query3);exit; 

			
return $query3;
	 				
	 		// 	$sql = "select CC.msg_id,CC.from_id,CC.to_id,AD.user_id,AD.fullname,AD.phone,AD.avatar,U.username,U.email,U.designation_id,U.last_login,U.online_status from dgt_chat_conversations as CC join dgt_account_details as AD on AD.user_id = CC.to_id join dgt_users as U on U.id = AD.user_id where CC.from_id = 347 and CC.msg_type = 'one' and CC.group_id = 0 group by CC.to_id order by CC.msg_date DESC UNION select CCC.msg_id,CCC.from_id,CCC.to_id,ADD.user_id,ADD.phone,ADD.avatar,UU.username,UU.email,UU.designation_id,UU.last_login,UU.online_status from dgt_chat_conversations as CCC join dgt_account_details as ADD on ADD.user_id = CCC.from_id join dgt_users as UU on UU.id = ADD.user_id where CCC.to_id = 347 and CCC.msg_type = 'one' and CCC.group_id = 0 group by CCC.to_id order by CCC.msg_date DESC";
	 		// 	//echo $sql1;  
	 		// 	$query = $this->db->query($sql);
			 // return $query->result_array();	 	
				// return $this->db->get_where('dgt_chat_conversations',array('from_id'=>$log_id,'msg_type'=>$chat_type,'group_id'=>0))->result_array();
	 	}
	 }

	 public function chat_usersignle_history($user_id,$log_id)
	 {
	 	$sql= "SELECT * FROM `dgt_chat_conversations` WHERE `from_id` = '$user_id' AND `to_id` = '$log_id'  OR `from_id` = '$log_id' AND `to_id` = '$user_id' AND `msg_type` = 'one' ";
			$query = $this->db->query($sql);
			return $query->result_array();
	 }
	 
	 public function chat_usergroup_history($group_id)
	 {
	 	$sql= "SELECT * FROM `dgt_chat_conversations` WHERE `group_id` = '$group_id' AND `msg_type` = 'group' ";
			$query = $this->db->query($sql);
			return $query->result_array();
	 }

	 public function chatgroup_members_list($group_id)
	 {
	 	return $this->db->select('CGM.group_id,AD.user_id,AD.fullname,AD.avatar')
	 			 ->from('dgt_chat_group_members CGM')
	 			 ->join('dgt_account_details AD', 'AD.user_id = CGM.login_id')
	 			 ->where('CGM.group_id',$group_id)
	 			 ->get()->result_array();
	 }


	 public function chatgroup_members($group_id) /*new function*/
	 {
	 	return $this->db->select('CGM.group_id,AD.user_id,AD.fullname,AD.avatar')
	 			 ->from('dgt_chat_group_users CGM')
	 			 ->join('dgt_account_details AD', 'AD.user_id = CGM.user_id')
	 			 ->where('CGM.group_id',$group_id)
	 			 ->get()->result_array();
	 }


	 public function get_users_by_name($username){		
		$query = "SELECT *
		FROM `dgt_account_details` `l`
		JOIN `dgt_users` `d` ON `d`.`id` = `l`.`user_id`
		WHERE (`l`.`fullname` LIKE '%$username%')
		AND `l`.`user_id` != '$this->login_id'
		AND `d`.`activated` = 1
		LIMIT 10";
		return $this->db->query($query)->result_array();
	}

	 function image_upload($file_name,$path = NULL){

            if($path != NULL){
                $config['upload_path'] = './images/'.$path.'/';
            }else{
                $config['upload_path'] = './images/question/';
            }
         
         $config['allowed_types'] = '*';
         $config['max_size']      = 0;

         $this->load->library('upload', $config);
         $this->upload->initialize($config);

        
        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
           
            return false;
        } else {
            $data = array('image_metadata' => $this->upload->data());
           return $this->upload->data();
        }
    }


    function get_msg_count($where =NULL){
    	$this->db->select('*');
    	$this->db->from('dgt_chat_conversations');
    	if($where!=NULL){
    	$this->db->where($where);
    	}
    	//$this->db->group_by('to_id');
    	$query = $this->db->get();
    	return $query->result();
    }
    function get_group_msg_count($where =NULL){
    	$this->db->select('dgt_chat_read_status.*');
    	$this->db->from('dgt_chat_read_status');
    	$this->db->join('dgt_chat_group_users','dgt_chat_group_users.user_id=dgt_chat_read_status.to_user_id and dgt_chat_group_users.group_id=dgt_chat_read_status.group_id');
    	if($where!=NULL){
    	$this->db->where($where);
    	}
    	//$this->db->group_by('to_user_id');
    	$query = $this->db->get();
    	return $query->result();
    }

    function get_all_users($where = NULL){
    	$this->db->select('id,username');
    	$this->db->from('dgt_users');
    	if($where!=NULL){
    	$this->db->where($where);
    	}
    	$query = $this->db->get()->result_array();
    	return $query;
    }
    function get_groups($where = NULL){
    	$this->db->select('dgt_chat_groups.id,dgt_chat_groups.group_name,dgt_chat_group_users.user_id');
    	$this->db->from('dgt_chat_groups');
    	$this->db->join('dgt_chat_group_users','dgt_chat_group_users.group_id=dgt_chat_groups.id','left');
    	$this->db->group_by('dgt_chat_groups.id');
    	
    	$this->db->where('dgt_chat_group_users.user_id',$this->session->userdata('user_id'));
    	if($where!=NULL){
    		$this->db->or_where($where);
    	}
    	$query = $this->db->get()->result_array();
    	return $query;
    	
    }
     function get_groups_users($where = NULL){
    	$this->db->select('dgt_chat_groups.id,dgt_chat_groups.group_name,dgt_chat_group_users.user_id');
    	$this->db->from('dgt_chat_groups');
    	$this->db->join('dgt_chat_group_users','dgt_chat_group_users.group_id=dgt_chat_groups.id','left');
    	// $this->db->group_by('dgt_chat_groups.id');
    	if($where!=NULL){
    		$this->db->where($where);
    	}
    	$query = $this->db->get()->result_array();
    	return $query;
    	
    }

	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	
}

/* End of file chats_model.php */