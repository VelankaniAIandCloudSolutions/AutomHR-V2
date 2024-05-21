<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Performance extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Client','Performance360'));
        
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        // App::module_access('menu_policies');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }
    
    function index()
    {
        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Performance'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'performance';
                $data['role']       = $this->tank_auth->get_role_id();
              
                $performance_details = $this->db->get_where('performance',array('user_id'=>$this->session->userdata('user_id')))->row_array();
                 $okr_details = $this->db->get_where('okrdetails',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                  $user = $this->db->where('lead',$this->session->userdata('user_id'))->group_by('user_id')->order_by('id','ASC')->get('okrdetails')->result_array();

                 
                  
                if($performance_details == '' &&  $data['role'] == '3' && $user == Array()) {
                $this->template
                     ->set_layout('users')
                     ->build('performance',isset($data) ? $data : NULL);
                }
                elseif($performance_details == '' &&  $data['role'] == '3' && $user != '')
                {
                     $data['datatables'] = TRUE;
                    $this->template
                     ->set_layout('users')
                     ->build('performance_lead',isset($data) ? $data : NULL);
                }
                elseif($data['role'] == '1') {
                $data['datatables'] = TRUE;
                $this->template
                     ->set_layout('users')
                     ->build('performance_manager',isset($data) ? $data : NULL);
                }
                
                elseif($okr_details == '' &&  $data['role'] == '3')
                {
                    $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
                }
                elseif($okr_details != '' &&  $data['role'] == '3')
                {
                    $this->template
                     ->set_layout('users')
                     ->build('okrdetails',isset($data) ? $data : NULL);
                }
                
        }else{
           redirect('');    
        }
     } 

     public function performance_dashboard(){

         if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Performance Dashboard'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'Dashboard';
                 $user_id = $this->session->userdata('user_id'); 
        $data['user_id'] = $user_id;
        $data["branch_id"] = $_POST['branch_id'] ? $_POST['branch_id'] : '1';
        $data['role']       = $this->tank_auth->get_role_id();
        if ($data['role'] == '1') {
            $data['branch_list'] = $this->db->select("*")->from("dgt_branches")->get()->result_array();
            $data['performances_360'] = $this->db->select('*')
                ->from('dgt_competencies')
                ->join("dgt_account_details", "dgt_account_details.user_id = dgt_competencies.user_id", "inner")
                ->where("dgt_account_details.branch_id", $data["branch_id"])
                ->group_by('dgt_competencies.user_id')
                ->get()
                ->result_array();

            // $data['completed_performance'] = $this->db->where('status', 1)->from('dgt_competencies')->get()->num_rows();
            // $data['outstanding_performance'] = $this->db->where('status', 0)->from('dgt_competencies')->get()->num_rows();
        } 
        elseif ($data['role'] == '3') {


            if(isset($_POST['branch_id']) && $_POST['branch_id'] !="")
            {
                $data["branch_id"] = $_POST['branch_id'];
            }
            else{
                $data["branch_id"] = $this->session->userdata("branch_id");
            }

            $data["branch_id"] = $this->session->userdata("branch_id");
            $data['branch_list'] = $this->db->select("*")->from("dgt_branches")->where("branch_id",$data["branch_id"])->get()->result_array();

            $data['performances_360'] = $this->db->select('*')
                    ->from('dgt_competencies')
                    ->join('dgt_account_details', 'dgt_account_details.user_id = dgt_competencies.user_id AND dgt_account_details.branch_id = ' . $data["branch_id"], 'inner')
                    ->where('(dgt_competencies.teamlead_id = ' . $user_id . ' OR dgt_competencies.user_id = ' . $user_id . ')')
                    ->group_by('dgt_competencies.user_id')
                    ->order_by('dgt_competencies.id', 'ASC')
                    ->get()
                    ->result_array();
                // echo $this->db->last_query();die;
        }

        // echo "<pre>";print_r($user_id); exit;
       
                // if($data['role'] == '1') {
                //     $data['performances_360'] = $this->db->select()
                //             ->group_by('user_id')
                //             ->from('performance_360')
                //             ->get()->result_array();                     
                //     $this->template
                //          ->set_layout('users')
                //          ->build('list',isset($data) ? $data : NULL);
                // } elseif($performances_360 != '' &&  $data['role'] == '3'){

                //     $data['performances_360'] = Performance360::get_360_performance_manager($user_id);
                //      $this->template
                //      ->set_layout('users')
                //      ->build('list',isset($data) ? $data : NULL);
                //     // $data['competencies'] = Performance360::get_competencies_manager($user_id);
                // }


            if(isset($_POST['branch_id']) && $_POST['branch_id'] !="" && ($_POST['branch_id'] =='3' || $_POST['branch_id'] =='5' || $_POST['branch_id'] == '6' || $_POST['branch_id'] =='7' || $_POST['branch_id'] =='10'))
            {
                $this->template
                         ->set_layout('users')
                         ->build('performance_dashboard_hotel',isset($data) ? $data : NULL);
            }
            else{
                $this->template
                         ->set_layout('users')
                         ->build('performance_dashboard',isset($data) ? $data : NULL);
            }
        
        }else{
           redirect('');    
        }
     }


     function show_okrdetails()
    { 
         $okr_id = $this->uri->segment(3);

        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Performance'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'performance_manager';
                $data['role']       = $this->tank_auth->get_role_id();
                $data['okr_id']     = $okr_id;
              
              
                $this->template
                     ->set_layout('users')
                     ->build('okr-manager',isset($data) ? $data : NULL);
               
               
        }else{
           redirect('');    
        }
     } 



     function add_okr()
     {
       
        $data = array(
        'user_id' => $this->input->post('user_id'),
        'okr_description' => $this->input->post('okr_description')

        );
        
        
        
        $this->db->insert('performance',$data);
        $user_id = $this->db->insert_id();
        $data['user_details'] = $this->db->get_where('users',array('id'=>$user_id))->row_array();
       
        
        $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Performance'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'performance';
                $data['role']       = $this->tank_auth->get_role_id();

        $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
     }


     function add_goals()
     {
       


        
        for($i = 0; $i< count($this->input->post('objective')); $i++)
        {

        $data_obj = array(
        'objective' => $this->input->post('objective')[$i],
        'okr_status' => $this->input->post('okr_status')[$i],
        'progress_value' => $this->input->post('progress_value')[$i],
        'grade_value' => $this->input->post('grade_value')[$i],
        'key_result' => $this->input->post('key_result')[$i],
        'key_status' => $this->input->post('key_status')[$i],
        'keyprog_value' => $this->input->post('keyres_value')[$i],
        'key_gradeval' => $this->input->post('key_gradeval')[$i],
        'feedback' => $this->input->post('feedback')[$i]


        );

        $objectives[] = $data_obj;
        }

        $data = array(
        'user_id' => $this->input->post('user_id'),
        'emp_name' => $this->input->post('fullname'),
        'position' => $this->input->post('position'),
        'lead' => $this->input->post('lead'),
        'goal_year' => $this->input->post('goal_year'),
        'goal_duration' => $this->input->post('goal_duration'),
        'objective' => json_encode($objectives)
         );


        $this->db->insert('okrdetails',$data);
        $this->session->set_flashdata('tokbox_success', 'Added Successfully');
        redirect('performance');
        
     }

     public function edit_okrdetails()
     {
       
        for($i = 0; $i< count($this->input->post('objective')); $i++)
        {

        $data_obj = array(
        'objective' => $this->input->post('objective')[$i],
        'okr_status' => $this->input->post('okr_status')[$i],
        'progress_value' => $this->input->post('progress_value')[$i],
        'grade_value' => $this->input->post('grade_value')[$i],
        'key_result' => $this->input->post('key_result')[$i],
        'key_status' => $this->input->post('key_status')[$i],
        'keyprog_value' => $this->input->post('keyres_value')[$i],
        'key_gradeval' => $this->input->post('key_gradeval')[$i],
        'obj_feedback' => $this->input->post('obj_feedback')[$i],
        'key_feedback' => $this->input->post('key_feedback')[$i]


        );

        $objectives[] = $data_obj;
             }

        $data = array(
        'id' => $this->input->post('id'),
        'emp_name' => $this->input->post('emp_name'),
        'position' => $this->input->post('position'),
        'lead' => $this->input->post('lead'),
        'goal_year' => $this->input->post('goal_year'),
        'goal_duration' => $this->input->post('goal_duration'),
        'objective' => json_encode($objectives)
         );

       

            $this->db->where('id',$this->input->post('id'));
            $this->db->update('dgt_okrdetails',$data);
        
         $this->session->set_flashdata('tokbox_success', 'Added Successfully');
         redirect('performance');
     }

    
}
