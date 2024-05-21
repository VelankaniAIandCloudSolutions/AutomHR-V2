<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	// Count user unread messages
	static function count_unread($user)
	{
		return self::$db->where(array('user_to'=>$user,'status'=>'Unread'))->get('messages')->num_rows();
	}

	// get user inbox messages
	static function get_inbox($user)
	{
		return self::$db->where(array('user_to'=>$user,'deleted'=>'No'))
						->group_by('user_from')->get('messages')->result();
	}
	// get user sent messages
	static function get_sent($user)
	{
		return self::$db->where(array('user_from'=>$user,'deleted'=>'No'))
						->group_by('user_to')->get('messages')->result();
	}

	// get user favourited messages
	static function get_favourite($user)
	{
		return self::$db->where(array('user_to'=>$user,'deleted'=>'No','favourite'=>'1'))
						->group_by('user_from')->get('messages')->result();
	}

	// get user deleted messages
	static function get_deleted($user)
	{
		return self::$db->where(array('user_to'=>$user,'deleted'=>'Yes'))->get('messages')->result();
	}

	// Get message information
	static function view_message($id)
	{
		return self::$db->where('msg_id',$id)->get('messages')->row();
	}

	static function group_messages_by_users($user)
	{
		self::$db->join('users','users.id = messages.user_from');
		return self::$db->where('user_to',$user)->group_by("user_from")->order_by("date_received","desc")->get('messages')->result();
	}

	// Get conversation
	static function conversation($user_from){
		return self::$db->where(array('user_to' => $user_from,'user_from' => User::get_id()))
								  ->or_where(array('user_from' => $user_from))
								  ->where(array('user_to' => User::get_id(),'deleted' => 'No'))
								  ->order_by('date_received','DESC')->get('messages')->result();
	}

	

	function get_conversations($recipient)
	{
		$this->db->join('users','users.id = messages.user_from');
		$this->db->where('user_to', $recipient);
		$this->db->where('user_from', $this->tank_auth->get_user_id());
		$this->db->or_where('user_from', $recipient);
		$this->db->where('user_to', $this->tank_auth->get_user_id());
		return $this->db->where('deleted','No')->order_by("date_received","desc")->get('messages')->result();
	}
	static function search_message($keyword)
	{
		self::$db->join('users','users.id = messages.user_from');
		self::$db->where(array('user_to'=>User::get_id(),'deleted'=>'No'));
		return self::$db->like('message', $keyword)->order_by("date_received","desc")->get('messages')->result();
	}

	

}

/* End of file model.php */