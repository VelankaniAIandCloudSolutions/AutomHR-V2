<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App','Project'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
    }

    function index()
    {
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('calendar'));
                $data['fullcalendar'] = TRUE;
                $data['datepicker'] = TRUE;
                $data['form'] = TRUE;
                $data['page'] = lang('calendar');
                $this->template
                ->set_layout('users')
                ->build('calendar',isset($data) ? $data : NULL);

    }
    function settings()
    {
                if ($_POST) {
                    $this->db->where('config_key','gcal_api_key')->update('config',array('value' => $_POST['gcal_api_key']));
                    $this->db->where('config_key','gcal_id')->update('config',array('value' => $_POST['gcal_id']));
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->load->view('modal/calendar-settings',isset($data) ? $data : NULL);
                }
    }

    function add_event()
    {
        // if(User::is_admin() || User::is_staff()){

        if ($_POST) {

            $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
            $this->form_validation->set_rules('event_name', lang('event_name'), 'required');
            $this->form_validation->set_rules('start_date', lang('start_date'), 'required');
            $this->form_validation->set_rules('end_date', lang('end_date'), 'required');
            if ($this->form_validation->run() == FALSE)
            {
                Applib::go_to($_SERVER['HTTP_REFERER'],'error',lang('error_in_form'));
            }else{
                $start_date = Applib::date_formatter($this->input->post('start_date', TRUE));
                $end_date = Applib::date_formatter($this->input->post('end_date', TRUE));
                $data = array(
                    'event_name'    => $this->input->post('event_name', TRUE),
                    'description'   => $this->input->post('description', TRUE),
                    'branch_id'    => $this->input->post('branch_id', TRUE),
                    'start_date'    => $start_date,
                    'end_date'      => $end_date,
                    'project'       => $this->input->post('project', TRUE),
                    'color'         => $this->input->post('color', TRUE),
                    'added_by'      => User::get_id(),
                    'created'       => date('Y-m-d H:i:s')
                    );
                $this->db->insert('events', $data);
                // Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('event_added_success'));
                $this->session->set_flashdata('tokbox_success', lang('event_added_success'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                $branches = $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
             }
             else{
                 $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
             }
            $this->load->view('modal/add_event',isset($data) ? $data : NULL);
        }
    }


function edit_event($id = NULL)
    {
        // if(User::is_admin() || User::is_staff()){

        if ($_POST) {

        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
        $this->form_validation->set_rules('event_name', lang('event_name'), 'required');
        $this->form_validation->set_rules('start_date', lang('start_date'), 'required');
        $this->form_validation->set_rules('end_date', lang('end_date'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            Applib::go_to($_SERVER['HTTP_REFERER'],'error',lang('error_in_form'));
        }else{
            $id = $this->input->post('id', TRUE);
            $added_by = $this->db->where('id',$id)->get('events')->row()->added_by;
            $start_date = Applib::date_formatter($this->input->post('start_date', TRUE));
            $end_date = Applib::date_formatter($this->input->post('end_date', TRUE));
                    $data = array(
                        'event_name'    => $this->input->post('event_name', TRUE),
                        'description'   => $this->input->post('description', TRUE),
                        'start_date'    => $start_date,
                        'end_date'      => $end_date,
                        'project'       => $this->input->post('project', TRUE),
                        'color'         => $this->input->post('color', TRUE)
                        );
                    // if(User::is_admin() || (User::is_staff() && $added_by == User::get_id())){
                    //     $this->db->where('id',$id)->update('events', $data);
                    // }
                     if($added_by == User::get_id()){
                        $this->db->where('id',$id)->update('events', $data);
                    }

                    // Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('event_edited_success'));
                    $this->session->set_flashdata('tokbox_success', lang('event_edited_success'));
                    redirect($_SERVER['HTTP_REFERER']);
            }
                } else {
                    $data['event'] = $this->db->where('id',$id)->get('events')->row();
                    $this->load->view('modal/edit_event',isset($data) ? $data : NULL);
                }
    // }
}


        function event($type, $id) {

            switch ($type) {
                case "tasks" :
                    $tasks = $this->db
                                ->select('*, dgt_tasks.description as task_description', TRUE)
                                ->join('projects','project_id = project')
                                ->join('users','id = added_by')
                                ->where('t_id',$id)
                                ->get('tasks')
                                ->result();
                    $data['task'] = $tasks[0];
                    break;
                case "payments" :
                    $payments = $this->db
                                ->join('payment_methods','method_id = payment_method')
                                ->join('companies','paid_by = co_id')
                                ->join('invoices','inv_id = invoice')
                                ->where('p_id',$id)
                                ->get('payments')
                                ->result();
                    $data['payment'] = $payments[0];
                    break;
                case "projects" :
                    $projects = $this->db
                                ->join('companies','client = co_id')
                                ->where('project_id',$id)
                                ->get('projects')
                                ->result();
                    $data['project'] = $projects[0];
                    break;
                case "invoices" :
                    $invoices = $this->db
                                ->join('companies','client = co_id')
                                ->where('inv_id',$id)
                                ->get('invoices')
                                ->result();
                    $data['invoice'] = $invoices[0];
                    break;
                case "estimates" :
                    $estimates = $this->db
                                ->join('companies','client = co_id')
                                ->where('est_id',$id)
                                ->get('estimates')
                                ->result();
                    $data['estimate'] = $estimates[0];
                    break;
                case 'events':
                    $events = $this->db
                                ->where('id',$id)
                                ->get('events')
                                ->result();
                    $data['event'] = $events[0];
                    break;
            }

                $this->load->view('modal/event-details',isset($data) ? $data : NULL);
		}
         function get_events()
         {
            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                $branches = $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                $branches = array_column($branches,'branch_id');
                $data = $this->db->where_in('branch_id',$branches)->get('events',array())->result_array(); 
             }else{
                    $data = $this->db->get_where('events',array())->result_array(); 
             }
        $return_array = [];
        if(sizeof(array_filter($data))>0)
        {
            foreach ($data as $key => $value) 
            {
                $return_array[] = array('title'=>$value['event_name'],'start'=>$value['start_date'],'end'=>$value['end_date'],'backgroundColor' =>$value['color']); 
            }
        } 
        echo json_encode($return_array);       
        exit;
        }
}
