<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Performance_configuration extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer','Performance'));
        $all_routes = $this->session->userdata('all_routes');
        // echo '<pre>'; print_r($all_routes); exit;
        foreach($all_routes as $key => $route){
            if($route == 'performance_configuration'){
                $routname = performance_configuration;
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
        $this->template->title(lang('performance_configuration'));
        $data['page'] = lang('performance_configuration');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['okr_description'] = Performance::okr_description();
         // echo "<pre>"; print_r( $data['okr_description']); exit;

        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('performance_configuration', isset($data) ? $data : null);
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

    public function create_smart_goal()
    {
        if ($this->input->post()) {
             echo "<pre>"; print_r($_POST); exit;
            
            if($_POST['rating_scale'] == "rating_1_5"){
                $rating_value = implode('|', $this->input->post('rating_value'));
                $definition = implode('|', $this->input->post('definition')); 
            }elseif ($_POST['rating_scale'] == "rating_1_10") {
                $rating_value = implode('|', $this->input->post('rating_value_ten'));
                $definition = implode('|', $this->input->post('definition_ten'));
                unset($_POST['rating_value_ten']);
                unset($_POST['definition_ten']);
               
            } else {
                $rating_value = implode('|', $this->input->post('rating_value_custom'));
                $definition = implode('|', $this->input->post('definition_custom'));
                unset($_POST['rating_value_custom']);
                unset($_POST['definition_custom']);
            }

            $_POST['created_by'] = $this->session->userdata('user_id');
            $_POST['rating_value'] = $rating_value;
            $_POST['definition'] = $definition;

            $this->db->where('id !=', 0);
            $this->db->delete('dgt_smart_goal_configuration');
           
            $smart_goal_id = Performance::save($this->input->post(null, true));

            $args = array(
                        'user' => User::get_id(),
                        'module' => 'Performance Configuration',
                        'module_field_id' => $smart_goal_id,
                        'activity' => 'activity_added_smart_goal',
                        'icon' => 'fa-user',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $this->input->post('rating_scale', true),
                    );
            App::Log($args);

            $this->session->set_flashdata('tokbox_success', lang('smart_goal_created_successfully'));
            redirect('settings/?settings=performance');
            
        } 
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

    public function okr_description()
    {
       if($this->input->post()){

            $this->db->where('id !=', 0);
            $this->db->delete('dgt_okr_description');
            echo "<pre>"; print_r($_POST); 

            $_POST['user_id'] = $this->session->userdata('user_id');
            $okr_description_id = Performance::okr_description_save($this->input->post(null, true));
           
            // echo "<pre>"; print_r($_POST); 

            
           

            $args = array(
                        'user' => User::get_id(),
                        'module' => 'Performance Configuration',
                        'module_field_id' => $okr_description_id,
                        'activity' => 'activity_added_okr_description',
                        'icon' => 'fa-user',
                        'branch_id' => $this->session->userdata('branch_id'),
                        'value1' => $this->input->post('description', true),
                    );
            App::Log($args);

            $this->session->set_flashdata('tokbox_success', lang('description_added_successfully'));
            redirect('settings/?settings=performance');
       } 
    }

    public function insert_kpi()
    {

        $this->db->where('id !=', 0);
        $this->db->delete('dgt_kpi');
       $data = array(
       'description' => $this->input->post('kpi_desc'),
       'status'   => 'active'
       );

       $result = $this->db->insert('dgt_kpi',$data);
       $this->session->set_flashdata('tokbox_success', 'Description added Successfully');
       redirect('settings/?settings=performance');
    }

    public function insert_competencies()
    {

        $this->db->where('id !=', 0);
        $this->db->delete('dgt_performance_competencies');
        $data = array(
        'description' => $this->input->post('competencies_desc'),
        'status'      => 'active'

        );

        $result = $this->db->insert('performance_competencies',$data);
        $this->session->set_flashdata('tokbox_success', 'Description added Successfully');
        redirect('settings/?settings=performance');
    }


   


}
/* End of file contacts.php */
