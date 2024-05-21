<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class All_contacts extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Lead'));
        $this->load->model('Contacts_model','contacts');
		// if(!App::is_access('menu_contacts'))
		// {
		// $this->session->set_flashdata('tokbox_error', lang('access_denied'));
		// redirect('');
		// }		
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('all_contacts').' - '.config_item('company_name'));
        $data['page'] = lang('all_contacts');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['leads'] = Lead::all();
        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('all_contacts', isset($data) ? $data : null);
    }


    public function view($company = null)
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('leads').' - '.config_item('company_name'));
        $data['page'] = lang('leads');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['editor'] = true;
        $data['todo_list'] = TRUE;
        $data['task_checkbox'] = true;
        $data['fuelux'] = true;
        $data['tab'] = ($this->uri->segment(4) == '') ? 'dashboard' : $this->uri->segment(4);
        $data['lead'] = $company;

        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

    function move_list(){
        if($this->input->post()){
            $lead_id = $this->input->post('lead_id', true);
            $target_stage = humanize($this->input->post('target', true));
            $target_id = $this->db->where('cat_name',$target_stage)->get('categories')->row()->id;
            $data = array('lead_stage' => $target_id);
            App::update('companies',array('co_id' => $lead_id),$data);
            $args = array(
                        'user' => User::get_id(),
                        'module' => 'leads',
                        'module_field_id' => $lead_id,
                        'activity' => 'lead_stage_changed',
                        'icon' => 'fa-exchange',
                        'value1' => Lead::find($lead_id)->company_name,
                        'value2' => $target_stage
                    );
            App::Log($args);
            echo sprintf(lang('lead_stage_changed'),Lead::find($lead_id)->company_name,$target_stage);
            exit;
        }
		else
		{
            echo 'No POST data received'; exit;
        }
    }

    public function create()
    {
        if ($this->input->post()) {
            $custom_fields = array();
            foreach ($_POST as $key => &$value) 
			{
                if (strpos($key, 'cust_') === 0) 
				{
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }
            $this->form_validation->set_rules('company_ref', 'Client Ref', 'required|is_unique[companies.company_ref]');
            $this->form_validation->set_rules('company_name', 'Client Name', 'required');
            $this->form_validation->set_rules('company_email', 'Client Email', 'required|valid_email');
            if ($this->form_validation->run() == false) {
                $_POST = '';
				Applib::go_to('leads', 'error', lang('error_in_form'));
            } 
			else 
			{
                $_POST['company_website'] = prep_url($_POST['company_website']);
                $_POST['is_lead'] = 1;

                $company_id = Lead::save($this->input->post(null, true));

                foreach ($custom_fields as $key => $f){
                    $key = str_replace('cust_', '', $key);
                    $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                    $cf = $this->db->where('name',$key)->get('fields');
                    $data = array(
                        'module'    => 'leads',
                        'field_id'  => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key'  => $cf->row()->name,
                        'meta_value'    => is_array($f) ? json_encode($f) : $f
                    );
                    ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                }

                $args = array(
                            'user' => User::get_id(),
                            'module' => 'leads',
                            'module_field_id' => $company_id,
                            'activity' => 'activity_added_new_lead',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('company_name', true),
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('client_registered_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            }
			
        } 
		else {
            $data['set_stage'] = isset($_GET['stage']) ? $_GET['stage'] : NULL;
            $data['categories'] = App::get_by_where('categories',array('module' => 'leads'));
            $this->load->view('modal/create',$data);
        }
    }

    public function update($company = null)
    {
        if ($this->input->post()) {

            $custom_fields = array();
            foreach ($_POST as $key => &$value){
                if (strpos($key, 'cust_') === 0){
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
                $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
                $company_id = $_POST['co_id'];
                $_POST = '';
                redirect('leads/view/'.$company_id);
            } else {
                $company_id = $_POST['co_id'];

                foreach ($custom_fields as $key => $f) {
                    $key = str_replace('cust_', '', $key);
                    $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                    $cf = $this->db->where('name',$key)->get('fields');
                    $data = array(
                        'module'    => 'leads',
                        'field_id'  => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key'  => $cf->row()->name,
                        'meta_value'    => is_array($f) ? json_encode($f) : $f
                    );
                    ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                }

                $_POST['company_website'] = prep_url($_POST['company_website']);
                Lead::update($company_id, $this->input->post());

                $args = array(
                            'user' => User::get_id(),
                            'module' => 'leads',
                            'module_field_id' => $company_id,
                            'activity' => 'activity_updated_lead',
                            'icon' => 'fa-edit',
                            'value1' => $this->input->post('company_name', true),
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('client_updated'));
                redirect('leads/view/'.$company_id);
            }
        } else {
            $data['company'] = $company;
            $data['categories'] = App::get_by_where('categories',array('module' => 'leads'));
            $this->load->view('modal/edit', $data);
        }
    }


    function comment()
    {
        if($this->uri->segment(4) == 'delete') :
            $comment_id = $this->uri->segment(3);
            if($this->input->post()){
                $id = $this->input->post('id',TRUE);
                $info = $this->db->where('comment_id',$id)->get('comments')->row();

                if(User::get_id() == $info->posted_by || User::is_admin()){
                    App::delete('comments',array('comment_id'=>$id));
                }
                Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('comment_deleted'));
            }else{
                $data['info'] = $this->db->where('comment_id',$comment_id)->get('comments')->row();
                $this->load->view('modal/delete_comment',isset($data) ? $data : NULL);
            }

        else:

        if ($this->input->post()) {
            $id = $this->input->post('client_id');
            $comment = $this->input->post('comment');
            $data = array('client_id'=>$id,'posted_by'=>User::get_id(),'message'=>$comment);
            $comment_id = App::save_data('comments',$data);
            $this->session->set_flashdata('tokbox_success', lang('comment_successful'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            redirect('leads');
        }

    endif;
    }

    public function file($id = null)
    {
        if ($this->uri->segment(3) == 'delete'): // Delete file code

                    if ($this->input->post()) {
                        $file_id = $this->input->post('file', true);

                        $file = $this->db->where('file_id', $file_id)->get('files')->row();

                        $fullpath = './assets/uploads/'.$file->file_name;
                        if (file_exists($fullpath)) {
                            unlink($fullpath);
                        }

                        App::delete('files', array('file_id' => $file_id));

                    // Log activity
                                $data = array(
                                    'module' => 'leads',
                                    'module_field_id' => $file->client_id,
                                    'user' => User::get_id(),
                                    'activity' => 'activity_deleted_a_file',
                                    'icon' => 'fa-times',
                                    'value1' => $file->file_name,
                                    'value2' => '',
                                    );
                        App::Log($data);
                        Applib::go_to('leads/view/'.$file->client_id, 'success', lang('file_deleted'));
                    } else {
                        $data['file_id'] = $this->uri->segment(4);
                        $data['action'] = 'delete_file';
                        $this->load->view('modal/file_action', $data);
                    } elseif ($this->uri->segment(3) == 'add'): // Adding a file

                    if ($this->input->post()) {
                        Applib::is_demo();
                        $company = $this->input->post('company', true);
                        $description = $this->input->post('description', true);
                        $config['upload_path'] = './assets/uploads';
                        $config['allowed_types'] = config_item('allowed_files');
                        $config['max_size'] = config_item('file_max_size');
                        $config['overwrite'] = false;

                        $this->load->library('upload');

                        $this->upload->initialize($config);

                        if (!$this->upload->do_multi_upload('clientfiles')) {
                            Applib::make_flashdata(array(
                            'response_status' => 'error',
                            'message' => lang('operation_failed'),
                            'form_error' => $this->upload->display_errors('<span style="color:red">', '</span><br>'),
                            ));
                            redirect('leads/view/'.$company);
                        } else {
                            $fileinfs = $this->upload->get_multi_upload_data();
                            foreach ($fileinfs as $findex => $fileinf) {
                                $data = array(
                                    'project' => 0,
                                    'client_id' => $company,
                                    'path' => null,
                                    'file_name' => $fileinf['file_name'],
                                    'title' => $_POST['title'],
                                    'ext' => $fileinf['file_ext'],
                                    'size' => Applib::format_deci($fileinf['file_size']),
                                    'is_image' => $fileinf['is_image'],
                                    'image_width' => $fileinf['image_width'],
                                    'image_height' => $fileinf['image_height'],
                                    'description' => $description,
                                    'uploaded_by' => User::get_id(),
                                );
                                $file_id = App::save_data('files', $data);
                            }

                 // Log activity
                        $data = array(
                            'module' => 'leads',
                            'module_field_id' => $company,
                            'user' => User::get_id(),
                            'activity' => 'activity_uploaded_file',
                            'icon' => 'fa-file',
                            'value1' => $this->input->post('title', true),
                            'value2' => '',
                            );
                            App::Log($data);
                            $this->session->set_flashdata('tokbox_success', lang('file_uploaded_successfully'));
                            redirect($_SERVER['HTTP_REFERER']);
                        }
                    } else {
                        $data['company'] = $this->uri->segment(4);
                        $data['action'] = 'add_file';
                        $this->load->view('modal/file_action', $data);
                    }
                    // End file add
                else: // Download file
        $this->load->helper('download');
        $file = $this->db->where('file_id', $id)->get('files')->row();

        $fullpath = './assets/uploads/'.$file->file_name;
        if ($fullpath) {
            $data = file_get_contents($fullpath); // Read the file contents
                        force_download($file->file_name, $data);
        } else {
            $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
            redirect('leads/view/'.$file->client_id);

        }
        endif;
    }

    public function make_primary()
    {
        $contact = $this->uri->segment(3);
        $company = $this->uri->segment(4);
        $this->db->set('primary_contact', $contact);
        $this->db->where('co_id', $company)->update('companies');
        $this->session->set_flashdata('tokbox_success', lang('primary_contact_set'));
        redirect('leads/view/'.$company.'/contacts');
    }

    public function account()
    {
        $client = $this->db->where('co_id', $this->uri->segment(4))->get('companies')->result();
        $data['client'] = $client[0];
        $data['type'] = $this->uri->segment(3);
        $this->load->view('modal/account', isset($data) ? $data : null);
    }

    public function convert($id = NULL)
    {
        if ($this->input->post()) {
            $company = $this->input->post('id', true);

            $data = array('is_lead' => 0,'lead_stage' => NULL, 'transaction_value' => 0.00);
            App::update('companies',array('co_id' => $company),$data);

            $this->session->set_flashdata('tokbox_success', lang('client_registered_successfully'));
            redirect('companies/view/'.$company);
        } else {
            $data['id'] = $id;
            $this->load->view('modal/convert', $data);
        }
    }




    function todo($action = NULL){

        $action = $this->uri->segment(3);

        if($action == 'add'){

            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('todo_item', 'Item', 'required');

                if ($this->form_validation->run() == FALSE){
                    $this->session->set_flashdata('response_status', 'error');
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $lead_id = $this->input->post('lead_id',TRUE);
                    $data = array('list_name'   => $this->input->post('todo_item'),
                        'saved_by'  => User::get_id(),
                        'module'    => 'leads',
                        'visible'       => 'Yes',
                        'lead_id'       => $lead_id
                    );
                    App::save_data('todo',$data);
                    $this->session->set_flashdata('tokbox_success', lang('operation_successful'));
                    redirect('leads/view/'.$lead_id.'/tasks');
                }

            }else{
                $data['lead_id'] = $this->uri->segment(4);
                $data['action'] = 'add_todo';
                $this->load->view('modal/lead_action',$data);
            }

        }

        if ($action == 'edit') {
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('todo_item', 'Item', 'required');

                if ($this->form_validation->run() == FALSE){
                    $this->session->set_flashdata('response_status', 'error');
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $id = $this->input->post('id',TRUE);
                    $data = array(
                        'list_name'     => $this->input->post('todo_item'),
                        'saved_by'  => User::get_id(),
                        'module'    => 'leads'
                    );
                    App::update('todo',array('id'=>$id),$data);
                    $this->session->set_flashdata('tokbox_success', lang('operation_successful'));
                    redirect($_SERVER['HTTP_REFERER']);
                    
                }

            }else{
                $data['id'] = $this->uri->segment(4);
                $data['action'] = 'edit_todo';
                $this->load->view('modal/lead_action',$data);
            }
        }

        if($action == 'delete'){
            if($_POST){
                $id = $this->input->post('id');
                App::delete('todo',array('id'=>$id));
                $this->session->set_flashdata('tokbox_success', lang('operation_successful'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                $data['id'] = $this->uri->segment(4);
                $data['action'] = 'delete_todo';
                $this->load->view('modal/lead_action',$data);
            }
        }


    }

            // Delete Lead
    public function delete()
    {
        if ($this->input->post()) {
            $lead = $this->input->post('id', true);

            Lead::delete($lead);
            $this->session->set_flashdata('tokbox_success', lang('company_deleted_successfully'));
            redirect('leads');
        } else {
            $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete', $data);
        }
    }
     public function get_contacts()
    {
        $branch_id = $this->session->userdata('branch_id');
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('status'=>1,'branch_id'=>$branch_id))->result_array();

                $html ='';

                $html .='<ul class="contact-list">';

                            foreach ($all_contacts as $contact) {
                                 if(!empty($contact['avatar']) && @getimagesize(base_url().'assets/uploads/'.$contact['avatar'])){
                                        $avatar = $contact['avatar'];
                                    } else{
                                        $avatar = 'default_avatar.jpg';
                                    }
                               
                             $html .='<li>
                                <div class="contact-cont">
                                    <div class="float-left user-img">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img class="rounded-circle" alt="" src="'.base_url().'assets/uploads/'.$avatar.'">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis"><a href="'.base_url().'all_contacts/view_contact/'.$contact['id'].'" data-toggle="ajaxModal">'.$contact['contact_name'].'</a></span>
                                        <span class="contact-date">'.$contact['email'].'</span>
                                    </div>';
                                    if(App::is_permit('menu_contacts','write')==true||App::is_permit('menu_contacts','delete')==true)
									{
									$html.='<ul class="contact-action">
                                        <li class="dropdown">
                                            <a href="" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu">';
												
												if(App::is_permit('menu_contacts','delete'))
												{
												$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/delete_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>';

												}
												if(App::is_permit('menu_contacts','write'))
												{
												$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/edit_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i>Edit</a>';
                                                
												}
												
                                           $html.= '</div>
                                        </li>
                                    </ul>';
									}
                                $html.= '</div>
                            </li>';                       
                            }
                            
                $html .='</ul>';    

                echo $html; exit();    
    }
    public function create_contacts()
    {
        if ($this->input->post()) {


                if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {

                            $config['upload_path'] = './assets/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['overwrite'] = true;

                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('file')){
                                        echo $this->upload->display_errors(); exit;
                            }else{
                                $data = $this->upload->data();
                                $_POST['avatar'] = $data['file_name'];
                               
                            }
                }else{
                    $_POST['avatar'] = 'default_avatar.JPG';
                }
                $contact_id = $this->contacts->create_contacts($this->input->post(null, true));      
                if($this->db->affected_rows()>0){
                    $args = array(
                            'user' => User::get_id(),
                            'module' => 'all_contacts',
                            'module_field_id' => $contact_id,
                            'activity' => 'Create contacts',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('contact_name', true),
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('contact_created_successfully'));
            }else{
                $this->session->set_flashdata('tokbox_success', lang('contact_add_faild'));
            }
                
                redirect('all_contacts');
        } 
		else 
		{
            $this->load->view('modal/create_contact');
        }
    }
    public function edit_contact()
    {
        if ($this->input->post()) {
			if(file_exists($_FILES['avatar']['tmp_name']) || is_uploaded_file($_FILES['avatar']['tmp_name'])) {

                            $config['upload_path'] = './assets/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['overwrite'] = true;

                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('avatar')){
                                        echo $this->upload->display_errors(); exit;
                            }else{
                                $data = $this->upload->data();
                                $_POST['avatar'] = $data['file_name'];
                               
                            }
                } else {

                    $_POST['avatar'] = $_POST['image'];
                }
                $data =array('roles'=>$_POST['roles'],
                            'contact_name'=>$_POST['contact_name'],
                            'email'=>$_POST['email'],
                            'contact_number'=>$_POST['contact_number'],
                            'avatar'=>$_POST['avatar'],
                            'status'=>$_POST['status'],

                            );

                $this->db->where('id',$_POST['id']);
                $contact_id = $this->db->update('dgt_contacts',$data);      
                $args = array(
                            'user' => User::get_id(),
                            'module' => 'all_contacts',
                            'module_field_id' => $_POST['id'],
                            'activity' => 'Update contacts',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('contact_name', true),
                        );
                App::Log($args);
                $branch_id = $this->session->userdata('branch_id');
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('status'=>1,'branch_id'=>$branch_id))->result_array();

                $html ='';

                $html .='<ul class="contact-list">';

                            foreach ($all_contacts as $contact) {
                                 if(!empty($contact['avatar'])){
                                        $avatar = $contact['avatar'];
                                    } else{
                                        $avatar = 'default_avatar.JPG';
                                    }
                               
                             $html .='<li>
                                <div class="contact-cont">
                                   <div class="float-left user-img">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img class="rounded-circle" alt="" src="'.base_url().'assets/uploads/'.$avatar.'">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis"><a href="'.base_url().'all_contacts/view_contact/'.$contact['id'].'" data-toggle="ajaxModal">'.$contact['contact_name'].'</a></span>
                                        <span class="contact-date">'.$contact['email'].'</span>
                                    </div>';
                                   if(App::is_permit('menu_contacts','write')==true||App::is_permit('menu_contacts','delete')==true)
									{
									$html.='<ul class="contact-action">
                                        <li class="dropdown">
                                            <a href="" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu">';
												
												if(App::is_permit('menu_contacts','write'))                                               
                                                {
			                                    $html.='<a class="dropdown-item" href="'.base_url().'all_contacts/edit_contact/'.$contact['id'].'"  data-toggle="ajaxModal" ><i class="fa fa-pencil m-r-5"></i>Edit</a>';
                                                }
												if(App::is_permit('menu_contacts','delete'))
												{
												$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/delete_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>';
												}
												
                                           $html.= '</div>
                                        </li>
                                    </ul>';
									}
                                $html.= '</div>
                            </li>';   
                            }
                            
                $html .='</ul>';    

                echo $html; exit();    
                        
        }
		else 
		{
            $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/edit_contact',$data);
        }
    }
    public function view_contact()
    {
		$data['id'] = $this->uri->segment(3);
		$this->load->view('modal/view',$data);
    }
     public function delete_contact()
    {
         if ($this->input->post()) {
            $branch_id = $this->session->userdata('branch_id');
                $this->db->where('id',$_POST['id']);
                $contact_id = $this->db->delete('dgt_contacts');      
                $args = array(
                            'user' => User::get_id(),
                            'module' => 'all_contacts',
                            'module_field_id' => $_POST['id'],
                            'activity' => 'Delete contacts',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('id', true),
                        );
                App::Log($args);
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('status'=>1,'branch_id'=>$branch_id))->result_array();

                $html ='';

                $html .='<ul class="contact-list">';

                            foreach ($all_contacts as $contact) {
                                 if(!empty($contact['avatar'])){
                                        $avatar = $contact['avatar'];
                                    } else{
                                        $avatar = 'default_avatar.JPG';
                                    }
                               
                             $html .='<li>
                                <div class="contact-cont">
                                   <div class="float-left user-img">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img class="rounded-circle" alt="" src="'.base_url().'assets/uploads/'.$avatar.'">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis"><a href="'.base_url().'all_contacts/view_contact/'.$contact['id'].'" data-toggle="ajaxModal">'.$contact['contact_name'].'</a></span>
                                        <span class="contact-date">'.$contact['email'].'</span>
                                    </div>';
                                    if(App::is_permit('menu_contacts','write')==true||App::is_permit('menu_contacts','delete')==true)
									{
									$html.='<ul class="contact-action">
                                        <li class="dropdown">
                                            <a href="" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu">';
												
												if(App::is_permit('menu_contacts','write'))                                             
                                               
												{
			                                       	$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/edit_contact/'.$contact['id'].'"  data-toggle="ajaxModal" data-target="#edit_contact"><i class="fa fa-pencil m-r-5"></i>Edit</a>';
                                                }
												if(App::is_permit('menu_contacts','delete'))
												{
													$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/delete_contact/'.$contact['id'].'" data-toggle="ajaxModal" "><i class="fa fa-trash-o m-r-5"></i>Delete</a>';
												}
												
                                           $html.= '</div>
                                        </li>
                                    </ul>';
									}
                                $html.= '</div>
                            </li>';                        
                            }
                            
                $html .='</ul>';    

                echo $html; exit();    
                        
        }
		else 
		{
            $data['id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_contact',$data);
        }
    }

    public function search_contact_based_role()
    {
        if ($this->input->post()) {
            $branch_id = $this->session->userdata('branch_id');
            if(!empty($_POST['id'])){
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('roles'=>$_POST['id'],'branch_id'=>$branch_id))->result_array();
            }else{
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('status'=>1,'branch_id'=>$branch_id))->result_array();
            }
                

                $html ='';

                $html .='<ul class="contact-list">';

                            foreach ($all_contacts as $contact) {
                                 if(!empty($contact['avatar'])){
                                        $avatar = $contact['avatar'];
                                    } else{
                                        $avatar = 'default_avatar.JPG';
                                    }
                               
                             $html .='<li>
                                <div class="contact-cont">
                                    <div class="float-left user-img">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img class="rounded-circle" alt="" src="'.base_url().'assets/uploads/'.$avatar.'">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis"><a href="'.base_url().'all_contacts/view_contact/'.$contact['id'].'" data-toggle="ajaxModal">'.$contact['contact_name'].'</a></span>
                                        <span class="contact-date">'.$contact['email'].'</span>
                                    </div>';
                                    if(App::is_permit('menu_contacts','write')==true||App::is_permit('menu_contacts','delete')==true)
									{
									$html.='<ul class="contact-action">
                                        <li class="dropdown">
                                            <a href="" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu">';
												
												if(App::is_permit('menu_contacts','write'))
												{
													$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/edit_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i>Edit</a>';
												}
												if(App::is_permit('menu_contacts','delete'))
												{
													$html.='<a class="dropdown-item"  href="'.base_url().'all_contacts/delete_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>';
												}
												
                                           $html.= '</div>
                                        </li>
                                    </ul>';
									}
                                $html.= '</div>
                            </li>';                        
                            }
                            
                $html .='</ul>';    

                echo $html; exit();    
            }
    }
    public function search_contact_based_alpha()
    {
        if ($this->input->post()) {
            $branch_id = $this->session->userdata('branch_id');
            if(!empty($_POST['name'])){
               $all_contacts = $this->db->select('*')->from('contacts')->where("contact_name LIKE '".$_POST['name']."%'")->where("branch_id",$branch_id)->order_by('contact_name','ASC')->get()->result_array();
                
            }else{
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('status'=>1,'branch_id'=>$branch_id))->result_array();
            }
                

                $html ='';

                $html .='<ul class="contact-list">';

                            foreach ($all_contacts as $contact) {
                                 if(!empty($contact['avatar'])){
                                        $avatar = $contact['avatar'];
                                    } else{
                                        $avatar = 'default_avatar.JPG';
                                    }
                               
                             $html .='<li>
                                <div class="contact-cont">
                                    <div class="float-left user-img">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img class="rounded-circle" alt="" src="'.base_url().'assets/uploads/'.$avatar.'">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis"><a href="'.base_url().'all_contacts/view_contact/'.$contact['id'].'" data-toggle="ajaxModal">'.$contact['contact_name'].'</a></span>
                                        <span class="contact-date">'.$contact['email'].'</span>
                                    </div>';
                                    if(App::is_permit('menu_contacts','write')==true||App::is_permit('menu_contacts','delete')==true)
									{
									$html.='<ul class="contact-action">
                                        <li class="dropdown">
                                            <a href="" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu">';
												
												if(App::is_permit('menu_contacts','write'))
												{
												$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/edit_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i>Edit</a>';
												}
												if(App::is_permit('menu_contacts','delete'))
												{
													$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/delete_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>';
												}
												
                                           $html.= '</div>
                                        </li>
                                    </ul>';
									}
                                $html.= '</div>
                            </li>';                        
                            }
                            
                $html .='</ul>';    

                echo $html; exit();    
            }
    }
    public function contact_search()
    {
        if ($this->input->post()) {
            $branch_id = $this->session->userdata('branch_id');
            if(!empty($_POST['name'])){
               $all_contacts = $this->db->select('*')->from('contacts')->where("contact_name LIKE '%".$_POST['name']."%'")->where('branch_id',$branch_id)->order_by('contact_name','ASC')->get()->result_array();
                
            }else{
                $all_contacts = $this->db->order_by('contact_name','ASC')->get_where('contacts',array('status'=>1,'branch_id'=>$branch_id))->result_array();
            }
                

                $html ='';

                $html .='<ul class="contact-list">';

                            foreach ($all_contacts as $contact) {
                                 if(!empty($contact['avatar'])){
                                        $avatar = $contact['avatar'];
                                    } else{
                                        $avatar = 'default_avatar.JPG';
                                    }
                               
                             $html .='<li>
                                <div class="contact-cont">
                                    <div class="float-left user-img">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img class="rounded-circle" alt="" src="'.base_url().'assets/uploads/'.$avatar.'">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name text-ellipsis"><a href="'.base_url().'all_contacts/view_contact/'.$contact['id'].'" data-toggle="ajaxModal">'.$contact['contact_name'].'</a></span>
                                        <span class="contact-date">'.$contact['email'].'</span>
                                    </div>';
                                    if(App::is_permit('menu_contacts','write')==true||App::is_permit('menu_contacts','delete')==true)
									{
									$html.='<ul class="contact-action">
                                        <li class="dropdown">
                                            <a href="" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu">';
												
												if(App::is_permit('menu_contacts','write'))
                                                						{
			                                       						$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/edit_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i>Edit</a>';
                                                }
												if(App::is_permit('menu_contacts','delete'))
												{
													$html.='<a class="dropdown-item" href="'.base_url().'all_contacts/delete_contact/'.$contact['id'].'" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>';
												}
												
                                           $html.= '</div>
                                        </li>
                                    </ul>';
									}
                                $html.= '</div>
                            </li>';                        
                            }
                            
                $html .='</ul>';    

                echo $html; exit();    
            }
    }
    function check_contact_email()
    {
        $check_email = $this->input->post('check_email');
        $check_username = $this->contacts->check_useremail($check_email);
        
        if($check_username > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    function check_contact_number()
    {
        $contact_number = $this->input->post('contact_number');
        $check_contact_number = $this->contacts->check_contact_number($contact_number);
        if($check_contact_number > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    
}

/* End of file contacts.php */
