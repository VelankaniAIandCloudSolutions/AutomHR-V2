<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appraisal extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App','Project','Appraisal_model'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
		if(!App::is_access('menu_appraisal'))
		{
			$this->session->set_flashdata('tokbox_error', lang('access_denied'));
			redirect('');
		}
    }

    function index(){
    	  		$this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('appraisal').' - '.config_item('company_name'));
                 $data['form'] = TRUE;
                 $data['datatables'] = TRUE;
                $data['page'] = lang('appraisal');

                $role_type = $this->session->userdata('user_type');
                $data['visiable'] = '';

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




                if($role_type == '58')
                {
                    $data['visiable'] ='1';
                    $where['users.id']= $this->session->userdata('user_id');
                     $data['appraisals'] = $this->Appraisal_model->select_appraisals($where, $data["branch_id"]);
                }
                elseif($role_type == '48')
                {
                    $tmp_employees_id = $this->db->select("id")->from("dgt_users")->where("teamlead_id",$this->session->userdata('user_id'))->get()->result_array();
                    $employees_id= array();
                    foreach($tmp_employees_id as $tmp_employees_id_val)
                    {
                        array_push($employees_id, $tmp_employees_id_val['id']);
                    }

                    $this->db->select('employee_appraisal.*,users.username,designation.designation,departments.deptname');
                    $this->db->from('employee_appraisal');
                    $this->db->join('users','users.id=employee_appraisal.employee_id');
                    $this->db->join('designation','designation.id=users.designation_id');
                    $this->db->join('departments','departments.deptid=users.department_id');
                    if(!empty($employees_id)){
                        $this->db->where_in("users.id", $employees_id);
                    }
                    if($data["branch_id"] != "")
                    {
                        $this->db->join("account_details","account_details.user_id = users.id", "inner");
                        $this->db->where("account_details.branch_id",$data["branch_id"]);
                    }

                    $this->db->order_by("id", "DESC");
                    $query = $this->db->get();
                    $data['appraisals'] = $query->result();
                    $data['visiable'] ='1'; 
                }
                else{
                    $data['visiable'] ='1';
                     $data['appraisals'] = $this->Appraisal_model->select_appraisals('', $data["branch_id"]);
                }
               
             // echo $this->db->last_query();exit; 
                $this->template
                ->set_layout('users')
                ->build('appraisal',isset($data) ? $data : NULL);
    }
    function indicator(){
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('indicator').' - '.config_item('company_name'));
                 $data['form'] = TRUE;
                 $data['datatables'] = TRUE;
                $data['page'] = lang('indicator');
                $data['indicators'] = $this->Appraisal_model->select_indicators();

				if(!App::is_access('menu_indicator'))
				{
				$this->session->set_flashdata('tokbox_error', lang('access_denied'));
				redirect('');
				} 	



                $this->template
                ->set_layout('users')
                ->build('indicator',isset($data) ? $data : NULL);
    }

    function add_indicator(){
        if($_POST){

            //print_r($_POST);exit; 

          // $this->form_validation->set_rules('designation','','trim|required');
          $this->form_validation->set_rules('indicators_level[]','','trim|required');
          
          if($this->form_validation->run()){
           $already = $this->Appraisal_model->select('indicators',array('designation_id'=>$this->input->post('designation')));
           if(count($already)>0){
            $this->session->set_flashdata('tokbox_error', lang('given_designation_already_have_indicators'));
            redirect($_SERVER['HTTP_REFERER']);
           }

            $ins['designation_id']= $this->input->post('designation');
            $ins['created_by'] =  $this->session->userdata('user_id');
            $ins['status'] = $this->input->post('status');
            $ins['created_at'] = date('Y-m-d');
            $indicators_level = $this->input->post('indicators_level');
            $indicators = [];
            $levels = [];
             foreach ($indicators_level as $key => $value) {
                 $indicators[$key] = $key;
                 $levels[$key] = $value; 
             }
             $ins['level'] = json_encode($levels,true);
             $ins['indicator'] = json_encode($indicators,true);
            $this->Appraisal_model->insert('indicators',$ins);
            $this->session->set_flashdata('tokbox_success', "Indicator details added successfully");
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);
          }

        }else{
             $data['designations'] = $this->Appraisal_model->select('dgt_designation');

             $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
            $this->load->view('modal/add_indicator',$data);
        }
    }
    function edit_indicator(){
        if($_POST){

            // $this->form_validation->set_rules('designation','','trim|required');
          $this->form_validation->set_rules('indicators_level[]','','trim|required');
          
          if($this->form_validation->run()){

            $already = $this->Appraisal_model->select('indicators',array('designation_id'=>$this->input->post('designation'),'id !=' =>$this->uri->segment(3)));
           if(count($already)>0){
            $this->session->set_flashdata('tokbox_error', lang('given_designation_already_have_indicators'));
            redirect($_SERVER['HTTP_REFERER']);
           }
            $upd['designation_id']= $this->input->post('designation');
            $upd['updated_at'] = date('Y-m-d');
            $upd['status'] = $this->input->post('status');
           
            $indicators_level = $this->input->post('indicators_level');
            $indicators = [];
            $levels = [];
             foreach ($indicators_level as $key => $value) {
                 $indicators[$key] = $key;
                 $levels[$key] = $value; 
             }
            $upd['level'] = json_encode($levels,true);
            $upd['indicator'] = json_encode($indicators,true);
            $where['id'] = $this->uri->segment(3);
            $this->Appraisal_model->update('indicators',$upd,$where);
           // echo $this->db->last_query();exit;
            $this->session->set_flashdata('tokbox_success', lang('indicator_details_updated_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);

          }

        }else{
            $where['id'] = $this->uri->segment(3);
            $data['inidicator_data'] = $this->Appraisal_model->select_row('indicators',$where);         
            $data['designations'] = $this->Appraisal_model->select('dgt_designation');
            $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
            $this->load->view('modal/edit_indicator',$data);
        }
    }
    function delete_indicator(){
        if($_POST){

          $this->db->where('id',$_POST['id']);
          $this->db->delete('indicators'); 
          $this->session->set_flashdata('tokbox_success', lang('indicator_deleted_successfully'));
          redirect($_SERVER['HTTP_REFERER']);

        }else{
          $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_indicator',$data);
        }
    }


    function indicator_status(){
      $upd['status'] = $this->uri->segment(4); /*status*/
      $where['id'] = $this->uri->segment(3); /*id*/
      $this->Appraisal_model->update('indicators',$upd,$where);
      $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
      redirect($_SERVER['HTTP_REFERER']);
    }

    function appraisal_status(){
      $upd['status'] = $this->uri->segment(4); /*status*/
      $where['id'] = $this->uri->segment(3); /*id*/
      $this->Appraisal_model->update('employee_appraisal',$upd,$where);
      $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
      redirect($_SERVER['HTTP_REFERER']);
    }
    function add_appraisal(){

        $user_type_role = $this->session->userdata("user_type_name");
        if($_POST){
          $this->form_validation->set_rules('employee_id','','trim|required');
          $this->form_validation->set_rules('appraisal_date','','trim|required');
          // $this->form_validation->set_rules('levels[]','','trim|required');
          
          if($this->form_validation->run()){
            if($user_type_role =='employee')
            {
                 $ins['employee_id']= $this->session->userdata("user_id");
            }
            else{
                 $ins['employee_id']= $this->input->post('employee_id');
            }
            $data['employee_id'] = $ins['employee_id'];
            $appraisal_date = str_replace('/', '-', $this->input->post('appraisal_date'));
            $ins['appraisal_date']= date('Y-m-d',strtotime($appraisal_date));
            $ins['created_at'] = date('Y-m-d');
            $ins['created_by'] =$this->session->userdata('user_id');
            $ins['status'] = $this->input->post('status');

            $ins['Appraiser_Comments'] = $this->input->post('Appraiser_Comments');
            $ins['HOD_Remarks'] = $this->input->post('HOD_Remarks');
            $ins['Appraisee_Comments'] = $this->input->post('Appraisee_Comments');
            $ins['startDate'] = date("Y-m-d", strtotime($this->input->post('startDate')));
            $ins['endDate'] = date("Y-m-d", strtotime($this->input->post('endDate')));
            $ins['Accepted'] = $this->input->post('Accepted');
            

            $levels = $this->input->post('levels');
            $ins['levels'] = json_encode($levels,true);

            $Teamwork = $this->input->post('Teamwork');
            $ins['Teamwork'] = json_encode($Teamwork,true);
            $Job_Knowledge = $this->input->post('Job_Knowledge');
            $ins['Job_Knowledge'] = json_encode($Job_Knowledge,true);
            $Work_Habits = $this->input->post('Work_Habits');
            $ins['Work_Habits'] = json_encode($Work_Habits,true);
            $Quality_Of_Work = $this->input->post('Quality_Of_Work');
            $ins['Quality_Of_Work'] = json_encode($Quality_Of_Work,true);
                        
            $this->Appraisal_model->insert('employee_appraisal',$ins);

            $last_insert_id = $this->db->insert_id();
            if($last_insert_id != "" && isset($_FILES['employee_signature']) && $_FILES['employee_signature'] != '')
            {
                $employee_signature_name = $this->image_upload('employee_signature',$last_insert_id, 'employee');

                if(empty($employee_signature_name['error'])) {
                    $file_name = '';
                    $file_name = $employee_signature_name['file_name'];
                    $this->db->where("id", $last_insert_id);
                    $this->db->update("dgt_employee_appraisal",  array("employee_signature" => $file_name));
                }
            }

            if($last_insert_id != "" && isset($_FILES['manager_signature']) && $_FILES['manager_signature'] != '')
            {
                $manager_signature_name = $this->image_upload('manager_signature',$last_insert_id,'manager');
                if(empty($manager_signature_name['error'])) {
                    $file_name = '';
                    $file_name = $manager_signature_name['file_name'];
                    $this->db->where("id", $last_insert_id);
                    $this->db->update("dgt_employee_appraisal",  array("manager_signature" => $file_name));
                }
            }

            if($last_insert_id != "" && isset($_FILES['hod_signature']) && $_FILES['hod_signature'] != '')
            {
                $hod_signature_name = $this->image_upload('hod_signature',$last_insert_id,'hod');
                if(empty($hod_signature_name['error'])) {
                    $file_name = '';
                    $file_name = $hod_signature_name['file_name'];
                    $this->db->where("id", $last_insert_id);
                    $this->db->update("dgt_employee_appraisal",  array("hod_signature" => $file_name));
                }
            }

            // if (empty($logo_response['error'])) {
            //     $file_name = '';
            //     $file_name = $logo_response['file_name'];
            //     $this->db->where("branch_id", $branch_id);
            //     $this->db->update("dgt_branches",  array("entity_logo" => $file_name));
            // }
            // else{
            //     $this->session->set_flashdata('tokbox_danger', $logo_response['error']);
            //     redirect('all_branches');
            // }



           // echo $this->db->last_query();exit;
            $this->session->set_flashdata('tokbox_success', lang('appraisal_added_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);
          }

        }else{
          $where['role_id'] = 3;
          if($user_type_role =='employee')
          {
            $where= array();
            $where['user_id'] = $this->session->userdata("user_id");
            $data['employees'] = $this->Appraisal_model->select('account_details',$where);
          }
          else{
            $data['employees'] = $this->Appraisal_model->select('users',$where);
          }
          

          $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
          $this->load->view('modal/add_appraisal',$data);
        }
    }

    function indicators_list(){
      
    $where['id'] = $this->input->post('designation');
       $employee_designation = $this->Appraisal_model->select_row('users',$where);
      $where1['designation_id'] = $employee_designation['designation_id'];
      $indicators_list =  $this->Appraisal_model->select_row('indicators',$where1);

      $indicators = json_decode($indicators_list['indicator'],true);
      $levels = json_decode($indicators_list['level'],true);
      //print_r($levels);exit;
      foreach ($levels as $key => $value) {
        if($value==1){ $level_name = "Beginner";}
        if($value==2){ $level_name = "Intermediate";}
        if($value==3){ $level_name = "Advanced";}
        if($value==4){ $level_name = "Expert / Leader";}
        $level_list[$key] = $level_name;
      }

      echo json_encode($level_list,true);exit;

    }
    function edit_appraisal(){
        if($_POST){
          //print_r($_POST);exit; 

          $this->form_validation->set_rules('employee_id','','trim|required');
          $this->form_validation->set_rules('appraisal_date','','trim|required');
          // $this->form_validation->set_rules('levels[]','','trim|required');
          
          if($this->form_validation->run()){
            $upd['employee_id']= $this->input->post('employee_id');
            $appraisal_date = str_replace('/', '-', $this->input->post('appraisal_date'));
            $upd['appraisal_date']= date('Y-m-d',strtotime($appraisal_date));
            $upd['updated_at'] = date('Y-m-d');
           
            $upd['status'] = $this->input->post('status');
            $upd['Appraiser_Comments'] = $this->input->post('Appraiser_Comments');
            $upd['HOD_Remarks'] = $this->input->post('HOD_Remarks');
            $upd['Appraisee_Comments'] = $this->input->post('Appraisee_Comments');
            $upd['startDate'] = date("Y-m-d", strtotime($this->input->post('startDate')));
            $upd['endDate'] = date("Y-m-d", strtotime($this->input->post('endDate')));
            $upd['Accepted'] = $this->input->post('Accepted');
            

            $levels = $this->input->post('levels');
            $upd['levels'] = json_encode($levels,true);

            $Teamwork = $this->input->post('Teamwork');
            $upd['Teamwork'] = json_encode($Teamwork,true);
            $Job_Knowledge = $this->input->post('Job_Knowledge');
            $upd['Job_Knowledge'] = json_encode($Job_Knowledge,true);
            $Work_Habits = $this->input->post('Work_Habits');
            $upd['Work_Habits'] = json_encode($Work_Habits,true);
            $Quality_Of_Work = $this->input->post('Quality_Of_Work');
            $upd['Quality_Of_Work'] = json_encode($Quality_Of_Work,true);
            $where['id'] = $last_insert_id =  $this->uri->segment(3);   

            $upd['kpo_score'] = $this->input->post('kpo_score');

            $this->Appraisal_model->update('employee_appraisal',$upd,$where);

            if($last_insert_id != "" && isset($_FILES['employee_signature']) && $_FILES['employee_signature'] != '')
            {
                $employee_signature_name = $this->image_upload('employee_signature',$last_insert_id,'employee');

                if(empty($employee_signature_name['error'])) {
                    $file_name = '';
                    $file_name = $employee_signature_name['file_name'];
                    $this->db->where("id", $last_insert_id);
                    $this->db->update("dgt_employee_appraisal",  array("employee_signature" => $file_name));
                }
            }

            if($last_insert_id != "" && isset($_FILES['manager_signature']) && $_FILES['manager_signature'] != '')
            {
                $manager_signature_name = $this->image_upload('manager_signature',$last_insert_id, 'manager');
                if(empty($manager_signature_name['error'])) {
                    $file_name = '';
                    $file_name = $manager_signature_name['file_name'];
                    $this->db->where("id", $last_insert_id);
                    $this->db->update("dgt_employee_appraisal",  array("manager_signature" => $file_name));
                }
            }

            if($last_insert_id != "" && isset($_FILES['hod_signature']) && $_FILES['hod_signature'] != '')
            {
                $hod_signature_name = $this->image_upload('hod_signature',$last_insert_id, 'hod');
                if(empty($hod_signature_name['error'])) {
                    $file_name = '';
                    $file_name = $hod_signature_name['file_name'];
                    $this->db->where("id", $last_insert_id);
                    $this->db->update("dgt_employee_appraisal",  array("hod_signature" => $file_name));
                }
            }


           //echo $this->db->last_query();exit;
            $this->session->set_flashdata('tokbox_success', lang('appraisal_updated_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_feilds_missing'));
            redirect($_SERVER['HTTP_REFERER']);
          }

        }else{

          $where['role_id'] = 3;
          $data['employees'] = $this->Appraisal_model->select('users',$where);
          $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();

          $cond['id'] =  $this->uri->segment(3);
          $data['appraisal_data'] = $this->Appraisal_model->select_row('employee_appraisal',$cond);

          
          $cond1['users.id'] =$data['appraisal_data']['employee_id'];
          $data['des_indicator'] = $this->Appraisal_model->get_indicators_row($cond1);
            $this->load->view('modal/edit_appraisal',$data);
        }
    }
    function delete_appraisal(){
         if($_POST){

          $this->db->where('id',$_POST['id']);
          $this->db->delete('employee_appraisal'); 
          $this->session->set_flashdata('tokbox_success', lang('indicator_deleted_successfully'));
          redirect($_SERVER['HTTP_REFERER']);

        }else{
          $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_appraisal',$data);
        }
    }

    function view_appraisal(){
       $cond['employee_appraisal.id'] =  $this->uri->segment(3);
        $data['appraisal_data'] = $this->Appraisal_model->select_appraisals_view($cond);
        
        $data['indicator_names'] = $this->Appraisal_model->get_indicatorname();
        
        
        $this->load->view('modal/view_appraisal',$data);
    }

    function getEmployees(){
      extract($_GET);
        $employee_id = $this->session->userdata("user_id");
        $user_role = $this->session->userdata("role_id");

      $this->db->select('U.id,AD.fullname,U.email')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1);
      if($employee_id !='' && $user_role == '3')
      {
        $this->db->where("U.id", $employee_id);
      }
      $this->db->where("(AD.fullname like '%".$term['term']."%' OR U.email like '%".$term['term']."%' )", NULL, FALSE);
  
      $users = $this->db->order_by('AD.fullname','asc')->get()->result_array();
      
      echo json_encode($users);
      exit;
  
     }



    function image_upload($file_name = '', $appriasal_id = '', $signature_type = '')
    {
        $config['allowed_types'] = 'jpeg|jpg|png|gif';
        $upload_dir = 'uploads/';
        $logo_dir = $upload_dir . '/appriasal_signature';

        if (!is_dir($logo_dir)) {
            mkdir($logo_dir, 0777, true);
        }

        $signature_type_dir = $logo_dir.'/'.$signature_type;
        if (!is_dir($signature_type_dir)) {
            mkdir($signature_type_dir, 0777, true);
        }

        $entity_logo_dir = $signature_type_dir . '/' . $appriasal_id;
        
        if (!is_dir($entity_logo_dir)) {
            mkdir($entity_logo_dir, 0777, true);
        }

        $config['upload_path'] = $entity_logo_dir;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        } else {
            $data = array('image_metadata' => $this->upload->data());
            return $this->upload->data();
        }
    }

    public function import_csv()
  {
    // $csvFilePath = APPPATH . 'HotelReview.csv'; 
    $csvFilePath = APPPATH . 'MissingAppraisalreview.csv'; 
    $startRow = 3; // Start reading from row 3
    $query = array();
    if (file_exists($csvFilePath)) {
      $fileHandle = fopen($csvFilePath, 'r');

      if ($fileHandle !== false) {
        $csvData = array();
        $rowCount = 0; // Counter to track current row

        while (($row = fgetcsv($fileHandle, 10000, ',')) !== FALSE) {
          $rowCount++;
          if ($rowCount >= $startRow) {
            // Add data to $csvData array starting from row 3
            $csvData[] = $row;
          }
        }

        fclose($fileHandle);
        $inserted_row = 2;
        $not_inserted= 2;
        $array_insert = $array_not_inserted = array();
        foreach ($csvData as $csvData_val) {
          if($csvData_val[1] == '')
          {
            continue;
          };

          $employee_id_data = $this->db->select("user_id")->from("account_details")->where("emp_code",$csvData_val[1])->get()->row();
          $emp_id = $employee_id_data->user_id;
          
            $date = DateTime::createFromFormat('d-m-Y', $csvData_val[2]);
          $appraisal_date = $date->format('Y-m-d H:i:s');
          $appraisee_comments = $csvData_val[3];
          $appraiser_comments = $csvData_val[4];
          $hod_comments = $csvData_val[5];

          $tmp_start_date = DateTime::createFromFormat('d-m-Y', $csvData_val[6]);
          $start_date = $tmp_start_date->format('Y-m-d');
         
          $tmp_end_date = DateTime::createFromFormat('d-m-Y', $csvData_val[7]);
          $end_date = $tmp_end_date->format('Y-m-d');
          $accepted = 'on';
          
          $teamwork = str_replace("(","[",$csvData_val[8]);
          $teamwork = str_replace(")","]",$teamwork);
          
          $array = array();
          $array = json_decode($teamwork, true);
          $teamwork_associativeArray = array();
          foreach ($array as $key => $value) {
              $teamwork_associativeArray[strval($key + 1)] = strval($value);
          }

          $job_knowledge = str_replace("(","[",$csvData_val[9]);
          $job_knowledge = str_replace(")","]",$job_knowledge);

          $array = array();
          $array = json_decode($job_knowledge, true);
          $job_knowledge_associativeArray = array();
          foreach ($array as $key => $value) {
              $job_knowledge_associativeArray[strval($key + 1)] = strval($value);
          }

          $work_habit = str_replace("(","[",$csvData_val[10]);
          $work_habit = str_replace(")","]",$work_habit);

          $array = array();
          $array = json_decode($work_habit, true);
          $work_habit_associativeArray = array();
          foreach ($array as $key => $value) {
              $work_habit_associativeArray[strval($key + 1)] = strval($value);
          }

          $quality_work = str_replace("(","[",$csvData_val[11]);
          $quality_work = str_replace(")","]",$quality_work);

          $array = array();
          $array = json_decode($quality_work, true);
          $quality_work_associativeArray = array();
          foreach ($array as $key => $value) {
              $quality_work_associativeArray[strval($key + 1)] = strval($value);
          }

         

          $string = $csvData_val[12];
          $pattern = '/\((.*?)\)/'; // Regular expression pattern to match text within parentheses
          $teamlead_id = '';

          if (preg_match($pattern, $string, $matches)) {
              //$teamlead_emp_code = $matches[1]; // Get the matched value inside the brackets
              $teamlead_id_data = $this->db->select("teamlead_id")->from("dgt_users")->where("id",$emp_id)->get()->row();
              if(!empty($teamlead_id_data))
              {
                $teamlead_id = $teamlead_id_data->user_id;
              }
          }

          $tmp_created_at = DateTime::createFromFormat('d-m-Y', $csvData_val[13]);
        
          $created_at = $tmp_created_at->format('Y-m-d H:i:s');

          $temp_appriasal_data= array();
          $temp_appriasal_data= array(
            "employee_id"         =>  $emp_id,
            "appraisal_date"      =>  $appraisal_date,
            "Appraisee_Comments"  =>  $appraisee_comments,
            "Appraiser_Comments"  =>  $appraiser_comments,
            "HOD_Remarks"         =>  $hod_comments,
            "startDate"           =>  $start_date,
            "endDate"             =>  $end_date,
            "Accepted"            =>  $accepted,
            "Teamwork"            =>  json_encode($teamwork_associativeArray),
            "Work_Habits"         =>  json_encode($work_habit_associativeArray),
            "Quality_Of_Work"     =>  json_encode($quality_work_associativeArray),
            "Job_Knowledge"       =>  json_encode($job_knowledge_associativeArray),
            "status"              => '1',
            "created_by"          => $teamlead_id,
            "created_at"          => $created_at,
            "updated_at"          => $created_at,
          );
          if($emp_id != "")
          {
            $this->db->insert("employee_appraisal", $temp_appriasal_data);
              $last_insert_id = $this->db->insert_id();
              if($last_insert_id)
              {
                array_push($array_insert, $inserted_row++);
              }
              else{
                  $not_inserted = $not_inserted + 1;
                  array_push($array_not_inserted, $not_inserted);
              } 
          }
        }
        $this->session->set_flashdata('tokbox_error', implode(',', $array_not_inserted));
         $this->session->set_flashdata('tokbox_success', implode(',', $array_insert));

        redirect(base_url("appraisal"));


      } 
      else 
      {
        echo 'Error opening file.';
        die;
      }
    } 
    else 
    {
      echo 'CSV file not found.';
      die;
    }


  }
}