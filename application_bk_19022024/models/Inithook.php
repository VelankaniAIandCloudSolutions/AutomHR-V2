<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inithook extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_config()
    {
			// echo '<pre>';print_r($res);exit;
		// echo $this->session->userdata('branch_id');exit;
		// $wr="'branch_id',$this->session->userdata('branch_id')";
		
        // return $this->db->where($wr)->get('config');
		
		if($this->session->userdata('user_type_name') ==  'company_admin' || $this->session->userdata('branch_id') == '' || $this->session->userdata('branch_id') == 0) {
			$all_config=$this->db->get('config')->result();
		} else {
			$all_config=$this->db->where('branch_id',$this->session->userdata('branch_id'))->get('config')->result();
		}
		// echo '<pre>';print_r();exit;
		$myconf=array();
		if(isset($all_config) && !empty($all_config))
		{
			foreach($all_config as $cnf_key=>$cnf)
			{
				
				if($cnf->config_key=='company_logo')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='website_name')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='logo_or_icon')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='site_favicon')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='site_appleicon')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='system_font')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='sidebar_theme')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='theme_color')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='top_bar_color')
				{
					unset($all_config[$cnf_key]);
				}
				if($cnf->config_key=='login_title')
				{
					unset($all_config[$cnf_key]);
				}
				
				// $all_config[]=$basic_site_det
				// if (($cnf_key = array_search('company_logo', $cnf->config_key)) !== false) {
					// unset($all_config[$cnf_key]);
				// }
			}
		}
		
        $query_basic_details= $this->db->get('config')->result();
		$basic_site_det=array();
		$all_config=array();
		foreach($query_basic_details as $site_key=>$site_config)
		{
			if($site_config->config_key=='company_logo')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='website_name')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='logo_or_icon')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='site_favicon')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='site_appleicon')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='system_font')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='sidebar_theme')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='theme_color')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='top_bar_color')
			{
				$basic_site_det[]=$site_config;
			}
			if($site_config->config_key=='login_title')
			{
				$basic_site_det[]=$site_config;
			}
		}
		
		// $all_config=$basic_site_det;
		
		
		// echo '<pre>';print_r($basic_site_det);exit;
		$final_cnf = array_merge($all_config, $basic_site_det);

		
		// echo '<pre>';print_r($basic_site_det);exit;
        // return $this->db->get('config');
		// return $final_cnf;
		if($this->session->userdata('user_type_name') ==  'company_admin' || $this->session->userdata('branch_id') == '' || $this->session->userdata('branch_id') == 0) {
			$all_config_final=$this->db->get('config')->result();
		} else {
			$all_config_final=$this->db->where('branch_id',$this->session->userdata('branch_id'))->get('config')->result();
			if(isset($all_config_final) && !empty($all_config_final)) {
				$all_config_final = $all_config_final;
			} else{
				$all_config_final = $this->db->get('config')->result();
			}
		}
return $all_config_final;
		// if ($query->num_rows() > 0)
            // {
               // return  $query->result();
				// return $row;
               
            // }
    }

    public function get_lang()
    {
        if ($this->session->userdata('lang')) {
            return $this->session->userdata('lang');
        }else{
            $query = $this->db->select('language')->where('user_id',$this->session->userdata('user_id'))->get('account_details');
            if ($query->num_rows() > 0)
            {
                $row = $query->row();
                return $row->language;
            }
        }
    }
}