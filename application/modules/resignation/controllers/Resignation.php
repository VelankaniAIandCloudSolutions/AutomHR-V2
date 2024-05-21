<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Resignation extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array('App'));
        $this->load->model('resignationmodel', 'resignations');
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        //App::module_access('menu_leaves');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
        if ($this->tank_auth->is_logged_in()) {
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Resignation');
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['page']       = lang('resignation');
            $data['role']       = $this->tank_auth->get_role_id();
            $branch_id = $this->session->userdata("branch_id");
            if ($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') == 'company_admin') {
                $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities', 'dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status', '0')->where('dgt_assigned_entities.user_id', $this->session->userdata('user_id'))->get()->result_array();
            } else {
                if($this->tank_auth->get_role_id() == '1')
                {
                    $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches')->where('branch_status', '0')->get()->result_array();

                }
                else{
                     $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches')->where('branch_status', '0')->where("branch_id", $branch_id)->get()->result_array();
                 }

            }
            $this->template
                ->set_layout('users')
                ->build('resignation', isset($data) ? $data : NULL);
        } else {
            redirect('');
        }
    }


    public function resignation_list()
    {
        $list = $this->resignations->get_datatables();
        $user_session_id = $this->session->userdata("user_id");
        $user_role_id = $this->session->userdata("role_id");

        // echo '<pre>';print_r($this->db->last_query());exit;
        $data = array();
        $no = $_POST['start'];
        $a = 1;
        foreach ($list as $resignation) {
            $employee_details = $this->db->get_where("users", array("id" => $resignation->employee))->row();
            $mngr_whr = array();
            $mngr_whr = array(
                "resignation_id"    => $resignation->id
            );
            $manager_resignation_data = $this->db->get_where("resignation_manger_response", $mngr_whr)->row();
            
            $hr_whr = array();
            $hr_whr = array(
                "resignation_id"    => $resignation->id
            );

            $hr_resignation_data = $this->db->get_where("resignation_hr_response", $hr_whr)->row();

            $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $resignation->fullname;
            $row[] = User::displayName($employee_details->teamlead_id);
            $row[] = $resignation->deptname;
            $row[] = $resignation->reason;
            $row[] = date('d M Y', strtotime($resignation->noticedate));
            $row[] = date('d M Y', strtotime($resignation->resignationdate));
            if (($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')) {
                $row[] = '<div class="dropdown dropdown-action">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu pull-right">
                    <li><a href="#" onclick="edit_resignation(' . $resignation->id . ')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                    <li><a href="#" onclick="delete_resignations(' . $resignation->id . ')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                </ul>
            </div>';
            }

            $manager_status = $hr_status = '';
            $class = "label label-warning";
            $manager_status = "Pending";
            
            if (!empty($manager_resignation_data)) {
                $tmp_manager_status = $manager_resignation_data->status;
                if ($tmp_manager_status == "2") {
                    $class = "label label-success";
                    $manager_status = "Approve";
                }
                if ($tmp_manager_status == "3") {
                    $class = "label label-danger";
                    $manager_status = "Reject";
                }
                if ($tmp_manager_status == "4") {
                    $class = "label label-danger";
                    $manager_status = "Withdraw";
                }
            }

            if ($user_session_id != $resignation->employee && ($manager_resignation_data->status == "1" || $manager_resignation_data->status == "0" || $manager_resignation_data->status =='')) 
            {
                $row[] = '
                    <a href="' . base_url("resignation/manager_approval/{$resignation->id}") . '">
                        <i class="fa fa-check" title="Approve" style="color: green; font-size: 22px;" aria-hidden="true"></i>
                    </a>
                    <a href="' . base_url("resignation/manager_approval/{$resignation->id}") . '">
                        <i class="fa fa-ban" title="Reject" style="color: red; font-size: 22px;" aria-hidden="true"></i>
                    </a>
                ';
            } 
            else
            {
                $row[] = '<span class="' . $class . '">' . $manager_status . ' </span>';
            }

            $row[] = '<span class="' . $class . '">' . $manager_status . '</span>';


            $class = "label label-warning";
            $hr_status = "Pending";

            if (!empty($hr_resignation_data)) {
                $tmp_hr_status = $hr_resignation_data->status;
                if ($tmp_hr_status == "2") {
                    $class = "label label-success";
                    $hr_status = "Approve";
                }
                if ($tmp_hr_status == "3") {
                    $class = "label label-danger";
                    $hr_status = "Reject";
                }
                if ($tmp_hr_status == "4") {
                    $class = "label label-danger";
                    $hr_status = "Withdraw";
                }
            }

            if($manager_status =='Approve' && ($employee_details->teamlead_id != $this->session->userdata("user_id")))
            {
                $row[] = '
                    <a href="' . base_url("resignation/hr_approval/{$resignation->id}") . '">
                        <i class="fa fa-check" title="Approve" style="color: green; font-size: 22px;" aria-hidden="true"></i>
                    </a>
                    <a href="' . base_url("resignation/hr_approval/{$resignation->id}") . '">
                        <i class="fa fa-ban" title="Reject" style="color: red; font-size: 22px;" aria-hidden="true"></i>
                    </a>
                ';
            }

            else if($manager_status =='Reject')
            {
                $class = "label label-danger";
                $row[] = '<span class="' . $class . '">' . $manager_status . ' </span>';
            }
            else{
                $row[] = '<span class="' . $class . '">' . $hr_status . ' </span>';
            }

            if($manager_status =='Approve')
            {
                $row[] = '<span class="' . $class . '">' . $hr_status . ' </span>';
            }
            else if($manager_status =='Reject')
            {
                $class = "label label-danger";
                $row[] = '<span class="' . $class . '">' . $manager_status . ' </span>';
            }
            else{
                $row[] = '<span class="' . $class . '">' . $hr_status . ' </span>';
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->resignations->count_all(),
            "recordsFiltered" => $this->resignations->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        exit;
    }


    public function resignation_edit($id)
    {
        // $data = $this->resignations->get_by_id($id);
        $data = $this->resignations->get_by_id_v2($id);
        // print_r($data); die;
        echo json_encode($data);
        exit;
    }






    /* public function add_resignation()
    {
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
        $reason=$this->input->post('reason');
       
	  
       
            $data = array(
                'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
                'reason'=>$reason,
                // 'branch_id'=>$this->session->userdata('branch_id'),
                'branch_id'=>$this->input->post('branch_id'),
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('resignation',$data);
			 // echo '<pre>';print_r($data);exit;
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Resignation added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Resignation added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }*/


    public function add_resignation()
    {
        $employee = $this->input->post('employee');
        $noticedate = date('Y-m-d', strtotime($this->input->post('noticedate')));
        $resignationdate = date('Y-m-d', strtotime($this->input->post('resignationdate')));
        $reason = $this->input->post('reason');

        $login_user_details = $this->db->get_where('users', array('id' => $employee))->row_array();
        // if($this->session->userdata('role_id') == 3){
        $resignation_notice = $this->db->get('resignation_notice')->row_array();
        if (!empty($resignation_notice)) {
            if (!empty($resignation_notice['notice_days'])) {
                $noticedate =   date('Y-m-d', strtotime('+' . $resignation_notice['notice_days'] . ' days', strtotime($this->input->post('resignationdate'))));
            }
            if (!empty($resignation_notice['email_notification'])) {
                $user_id = explode(',', $resignation_notice['email_notification']);
                foreach ($user_id as $key => $id) {
                    $user_details = $this->db->get_where('users', array('id' => $id))->row_array();
                    if (!empty($user_details)) {
                        $data = array(
                            'module' => 'resignation',
                            'module_field_id' => $user_details['id'],
                            'user' => $user_details['id'],
                            'activity' => 'Resignation requested by ' . User::displayName($employee),
                            'icon' => 'fa-plus',
                        );
                        App::Log($data);
                        $recipient[] = $user_details['email'];
                    }
                    # code...
                }
            }
        } else {
            $noticedate =  $resignationdate;
            $repoting_detils = $this->db->get_where('users', array('id' => $login_user_details['teamlead_id']))->row_array();
            $recipient[] = $repoting_detils['email'];
        }

        $resignationdate = date("Y-m-d", strtotime($this->input->post('exit_request_submit_date')));
        $noticedate = date("Y-m-d", strtotime($this->input->post('notice_period_end_date')));
        // 
        // }else{
        // $data = array(
        // 'module' => 'resignation',
        // 'module_field_id' => $employee,
        // 'user' => $employee,
        // 'activity' => 'Resignation craeted by '.User::displayName($this->session->userdata("user_id")),
        // 'icon' => 'fa-plus',
        // );
        // App::Log($data);
        // }

        $teamlead_id = '';
        $this->db->select("teamlead_id");
        $this->db->from("dgt_users");
        $this->db->where("id", $employee);
        $team_response = $this->db->get()->row_array();
        $teamlead_id = $teamlead_id['teamlead_id'];


        $data = array(
            'employee' => $employee,
            'noticedate' => $noticedate,
            'resignationdate' => $resignationdate,
            'branch_id' => $this->input->post('branch_id'),
            'reason' => $reason,
            'posted_date' => date('Y-m-d H:i:s'),
            "department_id" => $this->input->post('department_id'),
            "designation_id" => $this->input->post('designation_id'),
            "location" => $this->input->post('location'), // Seat Location
            "notice_period_end_date" => date("Y-m-d", strtotime($this->input->post('notice_period_end_date'))),
            "requested_last_working_date" => date("Y-m-d", strtotime($this->input->post('requested_last_working_date'))),
            "exit_request_submit_date" =>  date("Y-m-d", strtotime($this->input->post('exit_request_submit_date'))),
            "elaborate_reason"      => $this->input->post("elaborate_reason"),
            "status"                => '1',
            "added_by"            => $this->session->userdata('user_id'),
            "added_at"            => date("Y-m-d H:i:s"),
            "manager_id"           => $teamlead_id
        );

        $this->db->insert('resignation', $data);


        $last_id = $this->db->insert_id();
        $result = ($this->db->affected_rows() != 1) ? false : true;
        if ($result == true) {

            $manager_data = array();
            $manager_data = array(
                "manager_id"        => $teamlead_id,
                "employee_id"       => $employee,
                "resignation_id"    => $last_id,
                "status"            => '0',
                "created_at"        => date("Y-m-d H:i:s"),
                "updated_at"        => date("Y-m-d H:i:s")
            );

            $this->db->insert("dgt_resignation_manger_response", $manager_data);

            if (isset($_FILES['resignation_attachment']['name']) && $_FILES['resignation_attachment']['name'] != "") {
                $attachment['error'] = array();
                $attachment = $this->resignation_attachment_upload('resignation_attachment', $employee);

                if (empty($attachment['error'])) {
                    $file_name = '';
                    $file_name = $attachment['file_name'];
                    $attachment_data = array();
                    $attachment_data = array(
                        "resignation_id"    =>  $last_id,
                        "status"            =>  '1',
                        "added_at"          =>  date("Y-m-d H:i:s"),
                        "added_by"          =>  $this->session->userdata("user_id"),
                        "updated_at"        =>  date("Y-m-d H:i:s"),
                        "updated_by"        =>  $this->session->userdata("user_id"),
                        "name"              => $file_name
                    );
                    $this->db->insert("resignation_attachment", $attachment_data);
                } else {
                    $this->session->set_flashdata('tokbox_danger', $attachment['error']);
                }
            }

            // if($this->session->userdata('role_id') == 3){
            $subject         = "Resignation Letter";
            $message         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Resignation</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi,</p>
                                                <p><b>Name : ' . User::displayName($employee) . '</b></p>  
                                                <p><b>Resignation Date </b> : ' . $resignationdate . '</p>
                                                <p><b>Notice Date </b> : ' . $noticedate . '</b></p>     
                                                <p><b>Reason </b> : ' . $reason . '</p>                                      
                                                <br> 
                                                &nbsp;&nbsp;  
                                                <br>
                                                </big><br><br>Regards<br>The ' . User::displayName($employee) . ' 
                                            </div>
                                     </div>';
            foreach ($recipient as $key => $u) {
                $params['recipient'] = $u;
                $params['subject'] = '[' . config_item('company_name') . ']' . ' ' . $subject;
                $params['message'] = $message;
                $params['attached_file'] = '';

                modules::run('fomailer/send_email', $params);
            }
            // }else{
            // $subject_admin         = "Resignation Letter";
            // $message_admin         = '<div style="height: 7px; background-color: #535353;"></div>
            // <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
            // <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Resignation</div>
            // <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
            // <p> Hi '.User::displayName($employee).',</p>
            // <p><b>Reason </b> : '.$reason.'</p>  
            // <p><b>Resignation Date </b> : '.$resignationdate.'</p>
            // <p><b>Notice Date </b> : '.$noticedate.'</b></p>     

            // <br> 

            // &nbsp;&nbsp;  

            // <br>
            // </big><br><br>Regards<br>The '.User::displayName($this->session->userdata("user_id")).' 
            // </div>
            // </div>';       


            // $params['recipient'] = $login_user_details['email'];
            // $params['subject'] = '['.config_item('company_name').']'.' '.$subject_admin;
            // $params['message'] = $message_admin;
            // $params['attached_file'] = '';
            // modules::run('fomailer/send_email',$params);

            // }

            $datas['result'] = 'yes';
            $datas['status'] = 'Resignation added successfully';
        } else {
            $datas['result'] = 'no';
            $datas['status'] = 'Resignation added failed!';
        }

        echo json_encode($datas);

        exit;
    }


    public function update_resignation()
    {
        $id = $this->input->post('id');
        $employee = $this->input->post('employee');

        $resignation_Data = $this->db->get_where("resignation", array("id" => $id))->row();
        if (!empty($resignation_Data)) {
            $employee = $resignation_Data->employee;
        }
        // $noticedate = date('Y-m-d', strtotime($this->input->post('noticedate')));
        // $resignationdate = date('Y-m-d', strtotime($this->input->post('resignationdate')));

        $resignationdate = date("Y-m-d", strtotime($this->input->post('exit_request_submit_date')));
        $noticedate = date("Y-m-d", strtotime($this->input->post('notice_period_end_date')));

        $reason = $this->input->post('reason');

        // $data = array(
        //     // 'employee'=>$employee,
        //     'noticedate' => $noticedate,
        //     'resignationdate' => $resignationdate,
        //     'reason' => $reason,
        //     'posted_date' => date('Y-m-d H:i:s')
        // );

        $data = array(
            'noticedate' => $noticedate,
            'resignationdate' => $resignationdate,
            'reason' => $reason,
            'posted_date' => date('Y-m-d H:i:s'),
            "department_id" => $this->input->post('department_id'),
            "designation_id" => $this->input->post('designation_id'),
            "notice_period_end_date" => date("Y-m-d", strtotime($this->input->post('notice_period_end_date'))),
            "requested_last_working_date" => date("Y-m-d", strtotime($this->input->post('requested_last_working_date'))),
            "exit_request_submit_date" =>  date("Y-m-d", strtotime($this->input->post('exit_request_submit_date'))),
            "elaborate_reason"      => $this->input->post("elaborate_reason"),
            "status"                => $this->input->post("resignation_status"),
            "updated_by"            => $this->session->userdata('user_id'),
            "updated_at"            => date("Y-m-d H:i:s")
        );

        $this->db->where('id', $id);
        $this->db->update('resignation', $data);
        $result = ($this->db->affected_rows() != 1) ? false : true;

        if ($result == true) {
            if (isset($_FILES['resignation_attachment']['name']) && $_FILES['resignation_attachment']['name'] != "") {
                $attachment['error'] = array();
                $attachment = $this->resignation_attachment_upload('resignation_attachment', $employee);

                if (empty($attachment['error'])) {
                    $file_name = '';
                    $file_name = $attachment['file_name'];
                    $attachment_data = array();
                    $attachment_data = array(
                        "resignation_id"    =>  $id,
                        "status"            =>  '1',
                        "added_at"          =>  date("Y-m-d H:i:s"),
                        "added_by"          =>  $this->session->userdata("user_id"),
                        "updated_at"        =>  date("Y-m-d H:i:s"),
                        "updated_by"        =>  $this->session->userdata("user_id"),
                        "name"              =>  $file_name
                    );
                    $this->db->insert("resignation_attachment", $attachment_data);
                } else {
                    $this->session->set_flashdata('tokbox_danger', $attachment['error']);
                }
            }

            $datas['result'] = 'yes';
            $datas['status'] = 'Resignation update successfully';
        } else {
            $datas['result'] = 'no';
            $datas['status'] = 'Resignation update failed!';
        }

        echo json_encode($datas);

        exit;
    }




    public function resignation_delete($id)
    {
        $data = array(
            'status' => 1,
        );
        $this->resignations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    }

    public function get_companies($branch_id)
    {
       
       $role_id = $this->session->userdata("role_id");

        $this->db->select('*')->from('companies')->where('branch_id', $branch_id);
        $companies = $this->db->get()->result_array();

        $res_vals = array();
        $res_vals['companies']  = '<option value="">Select</option>';
        if (!empty($companies)) {
            foreach ($companies as $company1) {
                $res_vals['companies'] .= '<option value="' . $company1['co_id'] . '">' . $company1['company_name'] . '</option>';
            }
        }
         $this->db->select('*')->from('account_details')->where('branch_id', $branch_id);

        if($role_id != '1')
        {
            $this->db->where("user_id", $this->session->userdata("user_id"));
        }

        $user_details = $this->db->get()->result_array();

        $res_vals['users']  = '';
        if (!empty($user_details)) {
            foreach ($user_details as $user_detail1) {
                $res_vals['users'] .= '<option value="' . $user_detail1['user_id'] . '">' . $user_detail1['fullname'] . '</option>';
            }
        }
        echo json_encode($res_vals);
        exit;
    }

    function get_department($user_id)
    {
        if ($user_id != "") {
            $this->db->select("department_id");
            $this->db->from("dgt_users");
            $this->db->where("id", $user_id);
            $result = $this->db->get()->row_array();
            if (!empty($result)) {
                echo json_encode($result);
                exit;
            } else {
                echo "";
                exit;
            }
        }
    }

    function get_designation()
    {

        if (isset($_POST) && $_POST['department_id'] != "") {
            $this->load->model("department");
            $designation_list = $this->department->designation_list($_POST['entity_id'], $_POST['department_id'], $_POST['user_id']);
            // echo $this->db->last_query();die;

            if (!empty($designation_list)) {
                echo json_encode($designation_list);exit;
               
            } else {
                echo "";
                exit;
            }
        }
    }

    function user_info()
    {
        $output = array();
        $output = array(
            "status"    =>    false
        );
        if (isset($_POST) && $_POST['user_id'] != "") {
            $user_info = $this->db->select('*, AD.seat_location')
                ->from('dgt_users US')
                ->join('dgt_account_details AD', 'US.id = AD.user_id')
                ->where("US.id", $_POST['user_id'])
                ->get()->row_array();

            if (!empty($user_info)) {
                $output['status'] = true;
                $output['result'] = $user_info;
            }
        } else {
            $output['message'] = 'No Records Found';
        }
        echo json_encode($output);
        exit;
    }

    function resignation_attachment_upload($file_name = '', $employee_id = '')
    {
        $resignation_details = $this->db->select("id")->order_by("id", "desc")
            ->limit(1)
            ->get_where("resignation", array("employee" => $employee_id))
            ->row();

        $resignation_id = '';

        if (!empty($resignation_details)) {
            $resignation_id = $resignation_details->id;
            $this->db->where("resignation_id", $resignation_id);
            $this->db->update("resignation_attachment", array("status" => "0"));
        }

        $upload_dir = 'uploads/';
        $config['allowed_types'] = '*';
        $tmp_attachment_dir = $upload_dir . 'resignation_attachment';
        $attachment_dir = $tmp_attachment_dir . '/' . $employee_id;

        if (!file_exists($attachment_dir)) {
            mkdir($attachment_dir, 0777, true);
        }

        $config['upload_path'] = $attachment_dir;
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

    public function manager_approval()
    {
        $record_id = $this->uri->segment(3);
        $data["resignation_data"] = $this->resignations->get_by_id_v2($record_id);

        if(isset($_POST['btnmanagerSave']) && $_POST['btnmanagerSave'] != "")
        {
            $resignation_Data = $this->db->get_where("resignation", array("id" => $record_id))->row();
            if (!empty($resignation_Data)) {
                $employee = $resignation_Data->employee;
            }

            $manager_feedback = $this->input->post("feedback");
            $resignation_status = $this->input->post("resignation_status");
            $last_woking_date = $this->input->post("last_woking_date");
            $change_last_working_date = $this->input->post("change_last_working_date");

            $manager_Data = $this->db->get_where("resignation_manger_response", array("resignation_id" => $record_id))->row();
            if(!empty($manager_Data))
            {
                $manager_update_temp_data = array();
                $manager_update_temp_data = array(
                    "feedback"          =>  $manager_feedback,
                    "status"  =>  $resignation_status,
                    "last_woking_date"  =>  date("Y-m-d", strtotime($last_woking_date)),
                    "updated_at"        =>  date("Y-m-d H:i:s"),
                    "is_notice_period_change" => $change_last_working_date
                );
                $this->db->where("resignation_id",$record_id);
                $this->db->update("resignation_manger_response", $manager_update_temp_data);
            }
            else{
                $manager_update_temp_data = array();
                $manager_update_temp_data = array(
                    "feedback"          =>  $manager_feedback,
                    "status"            =>  $resignation_status,
                    "last_woking_date"  =>  date("Y-m-d", strtotime($last_woking_date)),
                    "created_at"        =>  date("Y-m-d H:i:s"),
                    "updated_at"        =>  date("Y-m-d H:i:s"),
                    "manager_id"        => $this->session->userdata("user_id"),
                    "employee_id"       => $employee,
                    "resignation_id"    => $record_id,
                    "is_notice_period_change" => $change_last_working_date,
                );
                $this->db->insert("resignation_manger_response", $manager_update_temp_data);
            }
            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('tokbox_success', 'Record has been updated.');
            } 
            if (isset($_FILES['resignation_attachment']['name']) && $_FILES['resignation_attachment']['name'] != "") {
                $attachment['error'] = array();
                $attachment = $this->resignation_attachment_upload('resignation_attachment', $employee);

                if (empty($attachment['error'])) {
                    $file_name = '';
                    $file_name = $attachment['file_name'];
                    $attachment_data = array();
                    $attachment_data = array(
                        "resignation_id"    =>  $record_id,
                        "status"            =>  '1',
                        "added_at"          =>  date("Y-m-d H:i:s"),
                        "added_by"          =>  $this->session->userdata("user_id"),
                        "updated_at"        =>  date("Y-m-d H:i:s"),
                        "updated_by"        =>  $this->session->userdata("user_id"),
                        "name"              => $file_name
                    );
                    $this->db->insert("resignation_attachment", $attachment_data);
                } else {
                    $this->session->set_flashdata('tokbox_danger', $attachment['error']);
                }
            }
            redirect(base_url("resignation"));            
        }

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Resignation Manager View');
        $data['datepicker'] = TRUE;
        $data['form']       = TRUE;
        $data['page']       = lang('resignation');
        if ($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') == 'company_admin') {
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities', 'dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status', '0')->where('dgt_assigned_entities.user_id', $this->session->userdata('user_id'))->get()->result_array();
        } else {
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches')->where('branch_status', '0')->get()->result_array();
        }
        $this->template
            ->set_layout('users')
            ->build('resignation_manager_view', isset($data) ? $data : NULL);

    }

    public function hr_approval()
    {
        $record_id = $this->uri->segment(3);
        $data["resignation_data"] = $this->resignations->get_by_id_v2($record_id);
        
        if(isset($_POST['btnHrSave']) && $_POST['btnHrSave'] != "")
        { 
            $resignation_Data = $this->db->get_where("resignation", array("id" => $record_id))->row();
            if (!empty($resignation_Data)) {
                $employee = $resignation_Data->employee;
            }
            
            $manager_data = $this->db->select("id")->get_where("resignation_manger_response", array("resignation_id" => $record_id))->row();
            $manager_response_id = '';
            if(!empty($manager_data ))
            {
                $manager_response_id = $manager_data->id;
            }
            $hr_feedback = $this->input->post("hr_feedback");
            $resignation_status_hr = $this->input->post("resignation_status_hr");
            $change_last_working_date_by_hr = $this->input->post("change_last_working_date_by_hr");
            $last_woking_date_by_hr = $this->input->post("last_woking_date_by_hr");
            $all_clearance = $this->input->post("all_clearance");
            $assets = array();
            $assets = $this->input->post("assets");

            $hr_data = $this->db->get_where("resignation_hr_response", array("resignation_id" => $record_id))->row();
            if(!empty($hr_data))
            {
                $hr_update_temp_data = array();
                $hr_update_temp_data = array(
                    "feedback"                  =>  $hr_feedback,
                    "status"                    =>  $resignation_status_hr,
                    "last_woking_date_by_hr"    =>  date("Y-m-d", strtotime($last_woking_date_by_hr)),
                    "updated_at"                =>  date("Y-m-d H:i:s"),
                    "is_agreed_with_manger_last_working_date" => $change_last_working_date_by_hr,
                    "is_clearence_completed"    => $all_clearance,
                    "assets"                    => json_encode($assets),
                    "hr_id"                     =>$this->session->userdata("user_id"),
                    "employee_id"               => $employee,
                    "resignation_id"            => $record_id,
                    "resignation_manager_response_id"       => $manager_response_id
                );

                $this->db->where("resignation_id",$record_id);
                $this->db->update("resignation_hr_response", $hr_update_temp_data);
            }
            else{
                $hr_update_temp_data = array();
                $hr_update_temp_data = array(
                    "feedback"                  =>  $hr_feedback,
                    "status"                    =>  $resignation_status_hr,
                    "last_woking_date_by_hr"    =>  date("Y-m-d", strtotime($last_woking_date_by_hr)),
                    "updated_at"                =>  date("Y-m-d H:i:s"),
                    "created_at"                =>  date("Y-m-d H:i:s"),
                    "is_agreed_with_manger_last_working_date" => $change_last_working_date_by_hr,
                    "is_clearence_completed"    => $all_clearance,
                    "assets"                    => json_encode($assets),
                    "hr_id"                     =>$this->session->userdata("user_id"),
                    "employee_id"               => $employee,
                    "resignation_id"            => $record_id,
                    "resignation_manager_response_id"       => $manager_response_id
                );
                $this->db->insert("resignation_hr_response", $hr_update_temp_data);
            }

            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('tokbox_success', 'Record has been updated.');
            } 
            if (isset($_FILES['resignation_attachment']['name']) && $_FILES['resignation_attachment']['name'] != "") {
                $attachment['error'] = array();
                $attachment = $this->resignation_attachment_upload('resignation_attachment', $employee);

                if (empty($attachment['error'])) {
                    $file_name = '';
                    $file_name = $attachment['file_name'];
                    $attachment_data = array();
                    $attachment_data = array(
                        "resignation_id"    =>  $record_id,
                        "status"            =>  '1',
                        "added_at"          =>  date("Y-m-d H:i:s"),
                        "added_by"          =>  $this->session->userdata("user_id"),
                        "updated_at"        =>  date("Y-m-d H:i:s"),
                        "updated_by"        =>  $this->session->userdata("user_id"),
                        "name"              => $file_name
                    );
                    $this->db->insert("resignation_attachment", $attachment_data);
                } else {
                    $this->session->set_flashdata('tokbox_danger', $attachment['error']);
                }
            }
            redirect(base_url("resignation"));            
        }

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Resignation Manager View');
        $data['datepicker'] = TRUE;
        $data['form']       = TRUE;
        $data['page']       = lang('resignation');
        if ($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') == 'company_admin') {
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities', 'dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status', '0')->where('dgt_assigned_entities.user_id', $this->session->userdata('user_id'))->get()->result_array();
        } else {
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches')->where('branch_status', '0')->get()->result_array();
        }
        $this->template
            ->set_layout('users')
            ->build('resignation_hr_view', isset($data) ? $data : NULL);

    }
}
