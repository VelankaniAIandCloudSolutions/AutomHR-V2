<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();
		
		$this->load->model(array('App','Client'));

		$this->applib->set_locale();
	}

	
	function index(){
		redirect('profile/settings');
	}

	function settings()
	{
		if($_POST){
			Applib::is_demo();
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		$this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

		if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		                {
		                	// $this->session->set_flashdata('response_status', 'error');
							// $this->session->set_flashdata('message',lang('error_in_form'));
							$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
							$_POST = '';
							$this->settings();
		                    //redirect('profile/settings');
		                }else{ 

		                $id = $this->input->post('co_id',TRUE);

                        if (isset($_POST['company_data'])) {
                            $company_data = $_POST['company_data'];
                            Client::update($id,$company_data);
                            unset($_POST['company_data']);
                        }
                            unset($_POST['co_id']);
                        App::update('account_details',array('user_id'=>User::get_id()),$this->input->post());

                        // $this->session->set_flashdata('response_status', 'success');
                        // $this->session->set_flashdata('message',lang('profile_updated_successfully'));
                        $this->session->set_flashdata('tokbox_success', lang('profile_updated_successfully'));
                        redirect('profile/settings');
		        }

		}else{
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title(lang('profile').' - '.config_item('company_name'));
			$data['page'] = lang('home');
			$data['form'] = TRUE;
			$this->template
			->set_layout('users')
			->build('edit_profile',isset($data) ? $data : NULL);
		}
	}

	function changeavatar()
	{		


		if ($this->input->post()) {
						
		Applib::is_demo();

		if(file_exists($_FILES['userfile']['tmp_name']) || is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$current_avatar = User::profile_info(User::get_id())->avatar;

							$config['upload_path'] = './assets/avatar/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							// $config['file_name'] = strtoupper('USER-'.$this->tank_auth->get_username()).'-AVATAR';
							$config['overwrite'] = FALSE;

							$this->load->library('upload', $config);

							if ( ! $this->upload->do_upload())
									{
										// $this->session->set_flashdata('response_status', 'error');
										// $this->session->set_flashdata('message',lang('avatar_upload_error'));
										$this->session->set_flashdata('tokbox_error', lang('avatar_upload_error'));
										redirect($this->input->post('r_url', TRUE));
							}else{
										$data = $this->upload->data();
										$ar = array('avatar' => $data['file_name']);
										App::update('account_details',array('user_id'=>User::get_id()),$ar);
										
								if(file_exists('./assets/avatar/'.$current_avatar) 
									&& $current_avatar != 'default_avatar.jpg'){
									unlink('./assets/avatar/'.$current_avatar);
								}
							}
				}

				if(isset($_POST['use_gravatar']) && $_POST['use_gravatar'] == 'on'){
					$ar = array('use_gravatar' => 'Y');
					App::update('account_details',array('user_id'=>User::get_id()),$ar);

				}else{ 
					$ar = array('use_gravatar' => 'N');
					App::update('account_details',array('user_id'=>User::get_id()),$ar);
					}

				// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message',lang('avatar_uploaded_successfully'));
				$this->session->set_flashdata('tokbox_success', lang('avatar_uploaded_successfully'));
				redirect($this->input->post('r_url', TRUE));

					
			}else{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('no_avatar_selected'));
				$this->session->set_flashdata('tokbox_error', lang('no_avatar_selected'));
				redirect('profile/settings');
		}
	}

	function activities()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('activities').' - '.config_item('company_name'));
	$data['page'] = lang('activities');
	$data['datatables'] = TRUE;
    $data['lastseen'] = config_item('last_seen_activities');
    $this->db->where('config_key','last_seen_activities')->update('config',array('value'=>time()));
	if($_POST){
    	$data['dfrom']  =  $_POST['dfrom']; 
		$data['dto']    =  $_POST['dto']; 
		if($data['dfrom'] != '') $data['dfrom'] = date('Y-m-d',strtotime($_POST['dfrom']));
		if($data['dto'] != '') $data['dto'] = date('Y-m-d',strtotime($_POST['dto']));
    	
		$user_id = $this->session->userdata('user_id');
		$dept_id = $this->db->get_where('dgt_users',array('id'=> $this->session->userdata('user_id')))->row_array();
		$department_id = $dept_id['department_id'];
		if($this->session->userdata('user_id') != 1){
			// $activity1 = $this->db->where('user',$this->session->userdata('user_id'))->order_by('activity_date','DESC')->get('activities')->result();

			// $activity2 = $this->db->where("FIND_IN_SET('".$department_id."', value2)")->or_where('value2','00')->order_by('activity_date','DESC')->get('activities')->result();
			// $activity = 	array_unique(array_merge($activity2,$activity1), SORT_REGULAR);
			$this->db->select('*');
		  	$this->db->from('dgt_activities');
		 	if($data['dfrom'] != ''){
			 $this->db->where("DATE_FORMAT(activity_date,'%Y-%m-%d') >=",$data['dfrom']);
			}
				// }
		 	if($data['dto'] != ''){
				$this->db->where("DATE_FORMAT(activity_date,'%Y-%m-%d') <=",$data['dto']);
			}
			  // ->where('user',$user_id)
		  	$this->db->group_start();
		  	$this->db->or_where("FIND_IN_SET('".$department_id."', value2)");
		  	$this->db->or_where('value2','00');
		  	$this->db->or_where('user',$user_id);
		  	$this->db->group_end();
		  	$this->db->order_by('activity_date','DESC');
		  	$activity = $this->db->get()->result();
			// print_r($this->db->last_query()); exit;
		} else {
		 	if($data['dfrom'] != ''){
			 $this->db->where("DATE_FORMAT(activity_date,'%Y-%m-%d') >=",$data['dfrom']);
			}
				// }
		 	if($data['dto'] != ''){
				$this->db->where("DATE_FORMAT(activity_date,'%Y-%m-%d') <=",$data['dto']);
			}
			$activity =  $this->db->order_by('activity_date','DESC')->get('activities')->result();
			// print_r($this->db->last_query()); exit;
			 
		}
		$data['dfrom']  =  $_POST['dfrom']; 
		$data['dto']    =  $_POST['dto']; 
    }else {

		$user_id = $this->session->userdata('user_id');
		$dept_id = $this->db->get_where('dgt_users',array('id'=> $this->session->userdata('user_id')))->row_array();
		$department_id = $dept_id['department_id'];
		if($this->session->userdata('user_id') != 1){
			// $activity1 = $this->db->where('user',$this->session->userdata('user_id'))->order_by('activity_date','DESC')->get('activities')->result();

			// $activity2 = $this->db->where("FIND_IN_SET('".$department_id."', value2)")->or_where('value2','00')->order_by('activity_date','DESC')->get('activities')->result();
			// $activity = 	array_unique(array_merge($activity2,$activity1), SORT_REGULAR);


			$activity = $this->db->select('*')
			  ->from('dgt_activities')
			  // ->where('activity_date >',date("Y-m-d H:i:s",$lastseen))
			  // ->where('user',$user_id)
			  ->group_start()
			  ->or_where("FIND_IN_SET('".$department_id."', value2)")
			  ->or_where('value2','00')
			  ->or_where('user',$user_id)
			  ->group_end()
			  ->order_by('activity_date','DESC')
			  ->get()->result();
					// print_r($this->db->last_query()); exit;
			} else {
					$activity =  $this->db->order_by('activity_date','DESC')->get('activities')->result();
			}
		}
		
		// echo '<pre>';print_r($data);exit;
		$data['activity'] = $activity;
	$this->template
	->set_layout('users')
	->build('activities',isset($data) ? $data : NULL);
	}

	function help()
	{
	$this->load->model('profile_model');
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('profile').' - '.config_item('company_name'));
	$data['page'] = lang('home');
	$this->template
	->set_layout('users')
	->build('intro',isset($data) ? $data : NULL);
	}
}

/* End of file profile.php */