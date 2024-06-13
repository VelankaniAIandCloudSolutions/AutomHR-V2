<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Set the default App Language
| 
*/


  //Loads configuration from database into global CI config
  function set_lang()
  {
   $CI =& get_instance();
   $system_lang = $CI->Inithook->get_lang();

   $CI->config->set_item('language', $system_lang);
   
   $CI->lang->load('fx', $system_lang ? $system_lang : 'english');
   $CI->db->select('value');
   $where = array('config_key'=>'timezone');
   $configs  = $CI->db->get_where('config',$where)->row_array();
   
   if(!empty($configs['value'])){
	   date_default_timezone_set($configs['value']);
   }
   else{
	date_default_timezone_set($CI->config->item('timezone'));
   }
    
   // Load plugin translations
   $plugins = $CI->db->get('plugins')->result();
   foreach($plugins as $plugin) {
       $CI->lang->load($plugin->route, $system_lang ? $system_lang : 'english', FALSE, TRUE, '', $plugin->route);
   }
   
  }
