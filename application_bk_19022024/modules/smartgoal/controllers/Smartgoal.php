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
              
                $smartgoal = $this->db->get_where('smartgoal',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                 $user = $this->db->where('lead',$this->session->userdata('user_id'))->group_by('user_id')->order_by('id','ASC')->get('smartgoal')->result_array();
               

                if($smartgoal == '' && $data['role'] == '3' && $user == Array()) {
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


        $data_goal = array(
        'goal' => $this->input->post('goal')[$i],
        'created_date' => $this->input->post('created_date')[$i],
        'completed_date' => $this->input->post('completed_date')[$i],
        'goal_action' => $this->input->post('goal_action')[$i],
        'goal_progress' => $this->input->post('goal_progress')[$i]
        


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
        'goals' => json_encode($goals)
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


        $data_goal = array(
        'goal' => $this->input->post('goal')[$i],
        'created_date' => $this->input->post('created_date')[$i],
        'completed_date' => $this->input->post('completed_date')[$i],
        'goal_action' => $this->input->post('goal_action')[$i],
        'goal_progress' => $this->input->post('goal_progress')[$i],
        'rating' => $this->input->post('rating')[$i],
        'feedback' => $this->input->post('feedback')[$i],
        'status' => $this->input->post('status')[$i]

        


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
        'goals' => json_encode($goals)
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
