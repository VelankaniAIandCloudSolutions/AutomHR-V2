<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Performance_three_sixty extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Performance360'));
        $all_routes = $this->session->userdata('all_routes');
        // echo '<pre>'; print_r($all_routes); exit;
        foreach($all_routes as $key => $route){
            if($route == 'performance_three_sixty'){
                $routname = "performance_three_sixty";
            } 
            
        }
        // if (!User::is_admin())
        
        if(empty($routname)){
            // $this->session->set_flashdata('message', lang('access_denied'));
            $this->session->set_flashdata('tokbox_error', lang('access_denied'));
            redirect('');
        }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('360 performance'));
        $data['page'] = lang('performances');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $user_id = $this->session->userdata('user_id');
        $data['user_id'] = $user_id;
        
        $data['role']       = $this->tank_auth->get_role_id();

        if(isset($_POST['year']) && $_POST['year'] != "") 
        {
            $year =$_POST['year'];
        }
        else{
            $year = date("Y");
        }

        $data['year'] = $year;

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

        $performances_360 = Performance360::get_360_performance_manager($user_id, $data['year'], $data['role'] ,  $data["branch_id"]);

       $user_type =$this->session->userdata("user_type");

        if($data['role'] == '3' && $performances_360 == Array() && $user_type  != "48" && $user_type != 1) {
            $data['performances_360'] = Performance360::get_360_performance($user_id, $year,  $data["branch_id"]);
            $data['competencies'] = Performance360::get_competencies($user_id, $data['year']);
            $this->template
            ->set_layout('users')
            ->build('performance_three_sixty',isset($data) ? $data : NULL);
        } 
        elseif($data['role'] == '1') {

            if(isset($_GET['add']) && $_GET['add'] =='1')
            {
                $data['performances_360'] = Performance360::get_360_performance($user_id, $year, $data["branch_id"]);
                $data['competencies'] = Performance360::get_competencies($user_id, $data['year']);
                $this->template
                ->set_layout('users')
                ->build('performance_three_sixty',isset($data) ? $data : NULL);
            }
            else{
                $data['performances_360'] = $this->db->select()
                ->group_by('competencies.user_id')
                ->from('competencies')
                ->join("account_details", "account_details.user_id = competencies.user_id AND account_details.branch_id =".$data["branch_id"], "inner")
                ->get()->result_array();                     
                $this->template
                ->set_layout('users')
                ->build('list',isset($data) ? $data : NULL);
            }
        } elseif($performances_360 != '' &&  $data['role'] == '3'){
            if(isset($_GET['add']) && $_GET['add'] =='1')
            {
                $data['performances_360'] = Performance360::get_360_performance($user_id, $year, $data["branch_id"]);
                $data['competencies'] = Performance360::get_competencies($user_id, $data['year']);
                $this->template
                ->set_layout('users')
                ->build('performance_three_sixty',isset($data) ? $data : NULL);
            }
            else{
                 $data['performances_360'] = Performance360::get_360_performance_manager($user_id, '', $data['role']);
                 //echo $this->db->last_Query();die;
                 
                $this->template
                ->set_layout('users')
                ->build('list',isset($data) ? $data : NULL);
            }
                    // $data['competencies'] = Performance360::get_competencies_manager($user_id);
        }
                // {
                //     $this->template
                //      ->set_layout('users')
                //      ->build('okr-view',isset($data) ? $data : NULL);
                // }

                //  if($performance_details == '' &&  $data['role'] == '3') {
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance',isset($data) ? $data : NULL);
                // }
                // elseif($data['role'] == '1') {
                // $data['datatables'] = TRUE;
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance_manager',isset($data) ? $data : NULL);
                // }

                // elseif($performance_details != '' &&  $data['role'] == '3')
                // {
                //     $this->template
                //      ->set_layout('users')
                //      ->build('okr-view',isset($data) ? $data : NULL);
                // }
           // echo "<pre>";print_r($data['competencies']); exit;

    }

    public function view($company = null)
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('companies').' - '.config_item('company_name'));
        $data['page'] = lang('companies');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['editor'] = true;
        $data['tab'] = ($this->uri->segment(4) == '') ? 'dashboard' : $this->uri->segment(4);
        $data['company'] = $company;

        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

    public function create_360()
    {
        if ($this->input->post()) {

            if($this->input->post('id') !=''){
                $id= $this->input->post('id');
                $_POST['user_id'] = $this->session->userdata('user_id');

                $data = array(
                    'user_id' => $_POST['user_id'],
                    'goals'   => $_POST['goals'],
                    'goal_duration'   => $_POST['goal_duration'],
                    'action'   => serialize($_POST['action']),
                    'status'   => $_POST['status'],
                    'self_rating'   => $_POST['self_rating'],
                    'rating'        => $_POST['rating'],
                    'progress'   => $_POST['progress'],
                    'teamlead_id'   => $_POST['teamlead_id']
                );

                $performance_360_id = Performance360::update_golas($id,$data);

                $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance_three_sixty',
                    'module_field_id' => $performance_360_id,
                    'activity' => 'activity_updated_performance_360',
                    'icon' => 'fa-user',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $this->input->post('goals', true),
                );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('360_performance_created_successfully'));


            }else {
                $_POST['action'] = serialize($_POST['action']);

                $_POST['user_id'] = $this->session->userdata('user_id');

                  // $data = array(
                  //   'user_id' => $_POST['user_id'],
                  //   'goals'   => $_POST['goals'],
                  //   'goal_duration'   => $_POST['goal_duration'],
                  //   'action'   => serialize($_POST['action']),
                  //   'status'   => $_POST['status'],
                  //   'self_rating'   => $_POST['self_rating'],
                  //   'rating'   => $_POST['rating'],
                  //   'progress'   => $_POST['progress'],
                  //   'feedback'   => $_POST['feedback'],
                  //   'teamlead_id'   => $_POST['teamlead_id']
                  //   );

                  // print_r($data);exit;
                $performance_360_id = Performance360::save_golas($this->input->post(null,true));
                // print_r($this->db->last_query());exit;
                $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance_three_sixty',
                    'module_field_id' => $performance_360_id,
                    'activity' => 'activity_added_performance_360',
                    'icon' => 'fa-user',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $this->input->post('goals', true),
                );
                App::Log($args);    
                $this->session->set_flashdata('tokbox_success', lang('360_performance_created_successfully'));
            }

            redirect('performance_three_sixty');
            
        } else {

            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('360 performance'));
            $data['page'] = 'performance_three_sixty';
            $data['datatables'] = true;
            $data['form'] = true;
            $data['currencies'] = App::currencies();
            $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

            $data['countries'] = App::countries();
            $this->template
            ->set_layout('users')
            ->build('performance_three_sixty', isset($data) ? $data : null);
        }

    }

    public function manager_rating(){

        $id = $this->input->post('id');
        // $_POST['user_id'] = $this->session->userdata('user_id');
        $_POST['rating'] = $this->input->post('rating');

                   // echo "<pre>";print_r($_POST); exit;

        $performance_360_id = Performance360::update_golas($id,$this->input->post(null, true));
        // echo print_r($this->db->last_query()); exit;
        $args = array(
            'user' => User::get_id(),
            'module' => 'performance_three_sixty',
            'module_field_id' => $performance_360_id,
            'activity' => 'activity_updated_performance_360',
            'icon' => 'fa-user',
            'branch_id' => $this->session->userdata('branch_id'),
            'value1' => $this->input->post('rating', true),
        );
        App::Log($args);
        echo 'yes'; exit;

    }

    public function manager_competence_rating(){
        $_POST['user_id'] = $this->input->post('user_id');
        $_POST['competencies'] = $this->input->post('competencies');
        $_POST['rating'] = $this->input->post('rating');
        $_POST['Appraisee_Comments'] = $this->input->post('Appraisee_Comments');
        $_POST['Appraiser_Comments'] = $this->input->post('Appraiser_Comments');
        $_POST['HOD_Comments'] = $this->input->post('HOD_Comments');
        $_POST['teamlead_id'] = $this->input->post('teamlead_id');
        $_POST['kpo_score_by_manager'] = $this->input->post('kpo_score_by_manager');


        $competencies =  $this->db->where("user_id", $_POST['user_id'])
        ->where_in('competencies ', $_POST['competencies'])
        ->order_by('id','ASC')
        ->get('competencies')->result_array();

        $employee_id = $_POST['user_id'];
        $team_lead_id = array_unique($_POST['teamlead_id']);


        if(empty($competencies)){
            
            for($i =0; $i < count($_POST['competencies']);  $i++)
            {
                $tmp_data = array();
                $tmp_data = array(
                    "competencies"  =>$_POST['competencies'][$i],
                    "user_id"       =>$_POST['user_id'],
                    "rating"  =>$_POST['rating'][$i],
                    "Appraiser_Comments"  =>$_POST['Appraiser_Comments'][$i],
                    "Appraisee_Comments"  =>$_POST['Appraisee_Comments'][$i],
                    "HOD_Comments"  =>$_POST['HOD_Comments'][$i],
                    "teamlead_id"           => $_POST['teamlead_id'][$i],
                    "kpo_score_by_manager"             => $_POST['kpo_score_by_manager']
                ); 

                $competencies_id = Performance360::save_competencies($tmp_data);

                $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance_three_sixty',
                    'module_field_id' => $competencies_id,
                    'activity' => 'activity_updated_competencies',
                    'icon' => 'fa-user',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $this->input->post('rating', true),
                );
                App::Log($args);
            }

            $message ='';
            $message = User::displayName($team_lead_id[0]).' has been submitted review.';
            $subject ='';
            $subject = "Review Submmited";
            $params['recipient'] = User::login_info($team_lead_id[0])->email;
            $params['subject'] = '[' .config_item('company_name') . ']' .' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            $params['cc'] = User::login_info($employee_id)->email;
            modules::run('fomailer/send_email', $params);

             $this->session->set_flashdata('tokbox_success', lang('competencie_created_successfully'));
        } else {
            
            for($i =0; $i < count($_POST['competencies']);  $i++)
            {
                $tmp_data = array();
                $tmp_data = array(
                    "competencies"  =>$_POST['competencies'][$i],
                    "user_id"       =>$_POST['user_id'],
                    "rating"        =>trim($_POST['rating'][$i]),
                    "Appraiser_Comments"  =>$_POST['Appraiser_Comments'][$i],
                    //"Appraisee_Comments"  =>$_POST['Appraisee_Comments'][$i],
                    "HOD_Comments"  =>$_POST['HOD_Comments'][$i],
                    "teamlead_id"           => $_POST['teamlead_id'][$i],
                    "kpo_score_by_manager"             => $_POST['kpo_score_by_manager']
                ); 
                
                $this->db->where("user_id",$_POST['user_id']);
                $this->db->where("competencies",$_POST['competencies'][$i]);
                $this->db->update('competencies', $tmp_data);
                $competencies_id = $_POST['competencies'][$i];
               // $competencies_id = Performance360::update_competencies($_POST['competencies'][$i],$tmp_data,  $_POST['user_id']);

                $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance_three_sixty',
                    'module_field_id' => $competencies_id,
                    'activity' => 'activity_updated_competencies',
                    'icon' => 'fa-user',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $_POST['rating'][$i],
                );
                App::Log($args);
            }

            $message ='';
            $message = User::displayName($team_lead_id[0]).' has been submitted review.';
            $subject ='';
            $subject = "Review Submmited";
            $params['recipient'] = User::login_info($team_lead_id[0])->email;
            $params['subject'] = '[' .config_item('company_name') . ']' .' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            $params['cc'] = User::login_info($employee_id)->email;
            modules::run('fomailer/send_email', $params);


            $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
        }
        
 
        redirect(base_url('performance_three_sixty'));
    }

    public function employee_competence_rating(){

        // $competencies = $this->input->post('competencies');
        // $user_id = $this->session->userdata('user_id');
        $_POST['user_id'] = $this->session->userdata('user_id');
        $_POST['self_rating'] = $this->input->post('self_rating');
        $_POST['competencies'] = $this->input->post('competencies');
        $_POST['Appraisee_Comments'] = $this->input->post('Appraisee_Comments');
        $_POST['Appraiser_Comments'] = $this->input->post('Appraiser_Comments');
        $_POST['HOD_Comments'] = $this->input->post('HOD_Comments');

                   // echo "<pre>";print_r($_POST); exit;
        $competencies = $this->db->where(array('user_id' => $_POST['user_id'],'competencies '=> $_POST['competencies']))->order_by('id','ASC')->get('competencies')->result_array();

        if(empty($competencies)){
            $competencies_id = Performance360::save_competencies($this->input->post(null, true));
        } else {
            $competencies_id = Performance360::update_competencies($_POST['competencies'],$this->input->post(null, true));
        }

        $args = array(
            'user' => User::get_id(),
            'module' => 'performance_three_sixty',
            'module_field_id' => $competencies_id,
            'activity' => 'activity_updated_competencies',
            'icon' => 'fa-user',
            'branch_id' => $this->session->userdata('branch_id'),
            'value1' => $this->input->post('self_rating', true),
        );
        App::Log($args);
        echo 'yes'; exit;

    }

    public function delete_goal($id)
    {
        $this->db->where('id',$id)->delete('dgt_performance_360');
        $this->session->set_flashdata('tokbox_success', lang('goal_deleted_successfully'));
        redirect('performance_three_sixty');
    }

    public function delete_competence($id)
    {
        $this->db->where('id',$id)->delete('dgt_competencies');
        $this->session->set_flashdata('tokbox_success', lang('competencies_deleted_successfully'));
        redirect('performance_three_sixty');
    }



    public function show_performance_three_sixty($userid = '')
    {
      $user_id = $this->uri->segment(3);

      $this->load->module('layouts');
      $this->load->library('template');

      $this->template->title(lang('360 performance'));
      $data['page'] = lang('performances');
      $data['datatables'] = true;
      $data['form'] = true;
      $data['currencies'] = App::currencies();
      $data['languages'] = App::languages();
        // $user_id = $this->session->userdata('user_id');
      $data['user_id'] = $user_id;
        // $data['performances_360'] = Performance360::get_360_performance($user_id);


      if(isset($_POST['year']) && $_POST['year'] != "") 
      {
        $year =$_POST['year'];
    }
    else{
        $year = date("Y");
    }

    $data['performances_360'] = Performance360::get_360_performance($user_id, $year);

    $data['year'] = $year;
    $data['competencies'] = Performance360::get_competencies($user_id, $year);
    
    $kpo_score = 0;
    $self_average = 0;

    if(!empty($data['competencies']))
    {
        $tmp_kpo_scores = array_column($data['competencies'], 'kpo_score_by_manager');
        $tmp_self_scores = array_column($data['competencies'], 'self_rating');
        
        $tmp_kpo_scores = array_values(array_unique($tmp_kpo_scores));
        if(!empty($tmp_kpo_scores))
        {
           // foreach($tmp_kpo_scores as $value){
           //  $kpo_score += $value;
           //  } 

            foreach($data['competencies'] as $value){
                $kpo_score += $value['rating'];
            }  

        }
        // else{
        //    foreach($data['competencies'] as $value){
        //         $kpo_score += $value['rating'];
        //     }  
        // }
        $self_average = array_sum($tmp_self_scores);
    }
    
    $count_competencies = sizeof($data['competencies']);

    $data['kpo_score_by_manager'] = $kpo_score;
    $data['self_average'] = 0;
    $data['overall_average'] = 0;

    if($count_competencies > 0)
    {
       $data['self_average'] = round($self_average / $count_competencies, 2); 
       $data['overall_average'] = round($kpo_score / $count_competencies, 2);
    }

    $data['role']       = $this->tank_auth->get_role_id();
    $this->template
    ->set_layout('users')
    ->build('manager_view',isset($data) ? $data : NULL);
} 


public function create_competencies()
{

    if ($this->input->post()) {           

        $user_id = $this->session->userdata('user_id');
        $competencies = Performance360::get_competencies($user_id);
        $employee_id = $user_id;
        $team_lead_id = array_unique($_POST['teamlead_id']);

        if(!empty($competencies)){

            Performance360::delete_competencies($user_id);

            $_POST['user_id'] = $this->session->userdata('user_id');

                  // echo "<pre>";print_r($_POST); exit;
            for ($i=0; $i <count($_POST['competencies']) ; $i++) { 

             $data = array(

                'user_id' => $user_id,
                'competencies' => $_POST['competencies'][$i],
                'self_rating' => $_POST['self_rating'][$i],
                'rating' => $_POST['rating'][$i],
                'teamlead_id' => $_POST['teamlead_id'][$i],
                'Appraisee_Comments' => $_POST['Appraisee_Comments'][$i],
                'Appraiser_Comments' => $_POST['Appraiser_Comments'][$i],
                 "HOD_Comments"  =>$_POST['HOD_Comments'][$i],

                            // 'rating' => $_POST['rating'][$i]
            );

             Performance360::save_competencies($data);
            
         }

          $save_golas= array();
             $save_golas= array(
                "user_id"   => $user_id,
                 "action"   => json_encode($_POST),
                 "teamlead_id"   => $team_lead_id[0]
            );

             Performance360::save_golas($save_golas);

         $this->session->set_flashdata('tokbox_success', lang('competencie_created_successfully'));


     }else {
        $_POST['user_id'] = $this->session->userdata('user_id');


        for ($i=0; $i < count($_POST['competencies']) ; $i++) { 

         $data = array(

            'user_id' => $user_id,
            'competencies' => $_POST['competencies'][$i],
            'self_rating' => $_POST['self_rating'][$i],
            'teamlead_id' => $_POST['teamlead_id'][$i],
            'rating' => $_POST['rating'][$i],
            'Appraisee_Comments' => $_POST['Appraisee_Comments'][$i],
            'Appraiser_Comments' => $_POST['Appraiser_Comments'][$i],
             "HOD_Comments"  =>$_POST['HOD_Comments'][$i],
                            // 'rating' => $_POST['rating'][$i]
        );
                        // echo "<pre>";print_r(count($_POST['competencies'])); exit;
         Performance360::save_competencies($data);

     }

    $message ='';
    $message = User::displayName($employee_id).' has been submitted review.';
    $subject ='';
    $subject = "Review Submmited";
    $params['recipient'] = User::login_info($employee_id)->email;
    $params['subject'] = '[' .config_item('company_name') . ']' .' '.$subject;
    $params['message'] = $message;
    $params['attached_file'] = '';
    $params['cc'] = User::login_info($team_lead_id[0])->email;
    modules::run('fomailer/send_email', $params);

     $this->session->set_flashdata('tokbox_success', lang('competencie_created_successfully'));
 }

 redirect('performance_three_sixty');
            // }
} else {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(lang('360 performance'));
    $data['page'] = 'performance_three_sixty';
    $data['datatables'] = true;
    $data['form'] = true;
    $data['currencies'] = App::currencies();
    $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

    $data['countries'] = App::countries();
    $this->template
    ->set_layout('users')
    ->build('performance_three_sixty', isset($data) ? $data : null);
}
}
public function three_sixty_feedback(){

    $goal_created_by = $this->input->post('user_id', true);
    unset($_POST['user_id']);
    $_POST['user_id'] = $this->session->userdata('user_id');

                    // echo "<pre>";print_r($_POST); exit;

    $performance_360_feedback = Performance360::save_feedback($this->input->post(null, true));

    $args = array(
        'user' => User::get_id(),
        'module' => 'performance_three_sixty',
        'module_field_id' => $performance_360_feedback,
        'activity' => 'activity_updated_performance_360_feedback',
        'icon' => 'fa-user',
        'branch_id' => $this->session->userdata('branch_id'),
        'value1' => $this->input->post('feedback', true),
    );
    App::Log($args);
    $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
    redirect('performance_three_sixty/show_performance_three_sixty/'.$goal_created_by);

}
public function competencies_feedback(){

    $competencies_created_by = $this->input->post('user_id', true);
    unset($_POST['user_id']);
    $_POST['user_id'] = $this->session->userdata('user_id');

                    // echo "<pre>";print_r($_POST); exit;

    $performance_360_feedback = Performance360::save_competencies_feedback($this->input->post(null, true));

    $args = array(
        'user' => User::get_id(),
        'module' => 'performance_three_sixty',
        'module_field_id' => $performance_360_feedback,
        'activity' => 'activity_updated_competencies_360_feedback',
        'icon' => 'fa-user',
        'branch_id' => $this->session->userdata('branch_id'),
        'value1' => $this->input->post('feedback', true),
    );
    App::Log($args);
    $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
    redirect('performance_three_sixty/show_performance_three_sixty/'.$competencies_created_by);

}
public function three_sixty_status()
{
    $goal_id = $this->uri->segment(3);
    $status = $this->uri->segment(4);
    $goal_created_by = $this->uri->segment(5);
    $data = array('status' => $status);
    $performance_360_id = Performance360::update_golas($goal_id,$data);
    $args = array(
        'user' => User::get_id(),
        'module' => 'performance_three_sixty',
        'module_field_id' => $performance_360_id,
        'activity' => 'activity_updated_performance_360_status',
        'icon' => 'fa-user',
        'branch_id' => $this->session->userdata('branch_id'),
        'value1' => $status,
    );
    App::Log($args);
    $this->session->set_flashdata('tokbox_success', lang('360_performance_status_updated_successfully'));
    redirect('performance_three_sixty/show_performance_three_sixty/'.$goal_created_by);
}

public function update($company = null)
{
    if ($this->input->post()) {

        $custom_fields = array();
        foreach ($_POST as $key => &$value) {
            if (strpos($key, 'cust_') === 0) {
                $custom_fields[$key] = $value;
                unset($_POST[$key]);
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
        $this->form_validation->set_rules('company_ref', 'Company ID', 'required');
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('company_email', 'Company Email', 'required|valid_email');

        if ($this->form_validation->run() == false) {
                // $this->session->set_flashdata('response_status', 'error');
                // $this->session->set_flashdata('message', lang('error_in_form'));
            $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
            $company_id = $_POST['co_id'];
            $_POST = '';
            redirect('companies/view/'.$company_id);
        } else {
            $company_id = $_POST['co_id'];

            foreach ($custom_fields as $key => $f) {
                $key = str_replace('cust_', '', $key);
                $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                $cf = $this->db->where('name',$key)->get('fields');
                $data = array(
                    'module'    => 'clients',
                    'field_id'  => $cf->row()->id,
                    'client_id' => $company_id,
                    'meta_key'  => $cf->row()->name,
                    'meta_value'    => is_array($f) ? json_encode($f) : $f
                );
                ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
            }

            $_POST['company_website'] = prep_url($_POST['company_website']);
            Client::update($company_id, $this->input->post());

            $args = array(
                'user' => User::get_id(),
                'module' => 'Clients',
                'module_field_id' => $company_id,
                'activity' => 'activity_updated_company',
                'icon' => 'fa-edit',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $this->input->post('company_name', true),
            );
            App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('client_updated'));
            $this->session->set_flashdata('tokbox_success', lang('client_updated'));
            redirect('companies/view/'.$company_id);
        }
    } else {
        $data['company'] = $company;
        $this->load->view('modal/edit', $data);
    }
}



            // Delete Company
public function delete()
{
    if ($this->input->post()) {
        $company = $this->input->post('company', true);

        Client::delete($company);

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('company_deleted_successfully'));
        $this->session->set_flashdata('tokbox_success', lang('company_deleted_successfully'));
        redirect('companies');
    } else {
        $data['company_id'] = $this->uri->segment(3);
        $this->load->view('modal/delete', $data);
    }
}

public function get_approvers()
{
    $approvers = User::team();



    $data=array();
    foreach($approvers as $r)
    {
        $data['value']=$r->id;
        $data['label']=ucfirst($r->username);
        $json[]=$data;


    }
    echo json_encode($json);
    exit;
}





}
/* End of file contacts.php */
