<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Note extends CI_Model
{

    private static $db;

    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function get_notes($id){
    	return self::$db->where('owner',$id)->get('notes')->result();
    }
   

}

/* End of file Project.php */