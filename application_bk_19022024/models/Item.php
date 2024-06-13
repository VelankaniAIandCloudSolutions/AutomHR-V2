<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function list_items()
	{
	    $user_id = User::get_id();
		return self::$db->where('deleted','No')->where('created_by',$user_id)->order_by('item_id','desc')->get('items_saved')->result();
	}

	static function list_tasks()
	{
	    $user_id = User::get_id();
		return self::$db->where('saved_by',$user_id)->order_by('added','desc')->get('saved_tasks')->result();
	}
	
	static function view_item($id)
	{
		return self::$db->where('item_id',$id)->get('items_saved')->row();
	}
	static function view_task($id)
	{
		return self::$db->where('template_id',$id)->get('saved_tasks')->row();
	}
	
	

}

/* End of file model.php */