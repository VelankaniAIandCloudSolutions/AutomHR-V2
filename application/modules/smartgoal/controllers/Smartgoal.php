<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Smartgoal extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Client'));
        
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
                $this->template->title('Smartgoal'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'smartgoal';
                $data['role']       = $this->tank_auth->get_role_id();
                

                $data["branch_id"] = $this->session->userdata("branch_id");
                
                if(isset($_POST["branch_id"]) && $_POST["branch_id"] != "") {
                    $data["branch_id"] = $_POST["branch_id"];

                } 

                if($this->session->userdata("branch_id") == 0 || $this->session->userdata("role_id") == 1) {
                    $data['branch_list'] = $this->db->select("*")
                                                    ->from("branches")
                                                    ->get()
                                                    ->result_array();
                } else {
                    $data['branch_list'] = $this->db->select("*")
                                                    ->from("branches")
                                                    ->where("branch_id",  $data["branch_id"])
                                                    ->get()
                                                    ->result_array();
                }


                $this->db->where('user_id',$this->session->userdata('user_id'));
                
                $smartgoal = $this->db->get('smartgoal')->row_array();

                 $this->db->select("smartgoal.*");
                    $this->db->from("smartgoal");
                    $this->db->join("users as u","u.id=smartgoal.user_id","left");
                    $this->db->where("u.id",$this->session->userdata('user_id'));
                    $this->db->or_where("u.teamlead_id",$this->session->userdata('user_id'));
                    $user =  $this->db->order_by('smartgoal.id','ASC')->get()->result_array();
               
               
               if(isset($_GET['add']) && $_GET['add'] =='1')
               {
                    $this->template
                         ->set_layout('users')
                         ->build('smartgoal_emp',isset($data) ? $data : NULL);
               }
               elseif($smartgoal == '' && $data['role'] == '3' && $user == Array()) {
              
                $this->template
                     ->set_layout('users')
                     ->build('smartgoal_emp',isset($data) ? $data : NULL);
                }
                elseif($smartgoal == '' &&  $data['role'] == '3' && $user != '')
                {
                     $data['datatables'] = TRUE;
                    $this->template
                     ->set_layout('users')
                     ->build('smartgoal_lead',isset($data) ? $data : NULL);
                }
                elseif($data['role'] == '1') {
                $data['datatables'] = TRUE;
                $this->template
                     ->set_layout('users')  
                     ->build('smartgoal_list',isset($data) ? $data : NULL);
                }
                 elseif($data['role'] == '3' && $smartgoal != '')  {
                $data['datatables'] = TRUE;
                $this->template
                     ->set_layout('users')  
                     ->build('smartgoal_list_emp',isset($data) ? $data : NULL);
                }
                
                
        }else{
           redirect('');    
        }
     } 

     function add_smartgoal()
     {
        for($i = 0; $i< count($this->input->post('goal')); $i++)
        {
            if(isset($this->input->post('manager_rating')[$i]))
            {
                $this->input->post('manager_rating')[$i] = $this->input->post('manager_rating')[$i];
            }
            else{
                $this->input->post('manager_rating')[$i] ='';
            }
        $data_goal = array(
        'goal' => $this->input->post('goal')[$i],
        'created_date' => $this->input->post('created_date')[$i],
        'completed_date' => $this->input->post('completed_date')[$i],
        'goal_action' => $this->input->post('goal_action')[$i],
        'goal_progress' => $this->input->post('goal_progress')[$i],
        'manager_rating' => $this->input->post('manager_rating')[$i],
        'Weightage' => $this->input->post('Weightage')[$i],
        'score' => $this->input->post('score')[$i],
        'rating'    => $this->input->post('rating')[$i],
        );

        $goals[] = $data_goal; 
        }

        $data = array(
        'user_id' => $this->input->post('user_id'),
        'emp_name' => $this->input->post('fullname'),
        'position' => $this->input->post('position'),
        'lead' => $this->input->post('lead'),
        'goal_year' => $this->input->post('goal_year'),
        'goal_duration' => $this->input->post('goal_duration'),
        'goals' => json_encode($goals),
        "training_need" => $this->input->post('training_need')
         );

        $this->db->insert('smartgoal',$data);
        $this->session->set_flashdata('tokbox_success', 'Added Successfully');
        redirect('smartgoal');
        
     }


      function show_smartgoal()
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
                     ->build('smartgoal_manager',isset($data) ? $data : NULL);
               
               
        }else{
           redirect('');    
        }
     } 

     public function edit_smartgoal()
     {
       
       
        for($i = 0; $i< count($this->input->post('goal')); $i++)
        {

            if(isset($this->input->post('manager_rating')[$i]))
            {
                $this->input->post('manager_rating')[$i] = $this->input->post('manager_rating')[$i];
            }
            else{
                $this->input->post('manager_rating')[$i] ='';
            }
            
        $data_goal = array(
        'goal' => $this->input->post('goal')[$i],
        'created_date' => $this->input->post('created_date')[$i],
        'completed_date' => $this->input->post('completed_date')[$i],
        'goal_action' => $this->input->post('goal_action')[$i],
        'goal_progress' => $this->input->post('goal_progress')[$i],
        'rating' => $this->input->post('rating')[$i],
        'feedback' => $this->input->post('feedback')[$i],
        'status' => $this->input->post('status')[$i],
        'manager_rating' => $this->input->post('manager_rating')[$i],
        'Weightage' => $this->input->post('Weightage')[$i],
        'score' => $this->input->post('score')[$i]
        );

        $goals[] = $data_goal; 
        }

        $data = array(
        'user_id' => $this->input->post('user_id'),
        'emp_name' => $this->input->post('emp_name'),
        'position' => $this->input->post('position'),
        'lead' => $this->input->post('lead'),
        'goal_year' => $this->input->post('goal_year'),
        'goal_duration' => $this->input->post('goal_duration'),
        'goals' => json_encode($goals),
        "training_need" => $this->input->post('training_need')
         );

            $this->db->where('id',$this->input->post('id'));
            $this->db->update('smartgoal',$data);
        
         $this->session->set_flashdata('tokbox_success', 'Added Successfully');
         redirect('smartgoal');
     }

      function show_smartgoal_emp()
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
                     ->build('show_smartgoal',isset($data) ? $data : NULL);
               
               
        }else{
           redirect('');    
        }
     }

}
