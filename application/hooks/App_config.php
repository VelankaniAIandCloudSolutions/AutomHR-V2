<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Set config variables using DB
| 
*/
  //Loads configuration from database into global CI config
  function load_config()
  {
   $CI =& get_instance();
   // echo '<pre>';print_r($CI->Inithook->get_config());exit;
   // foreach($CI->Inithook->get_config()->result() as $site_config)
   foreach($CI->Inithook->get_config() as $site_config)
   {
	   // if($site_config->config_key=='company_logo')
    $CI->config->set_item($site_config->config_key,$site_config->value);
   }
  }
?>