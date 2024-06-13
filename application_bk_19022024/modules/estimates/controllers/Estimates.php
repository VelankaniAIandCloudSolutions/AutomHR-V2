<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Estimates extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->module('layouts');
        User::logged_in();

        $this->load->library(array('template', 'form_validation'));

        if (isset($_GET['login'])) {
            $this->tank_auth->remote_login($_GET['login']);
        }
        $this->template->title(lang('estimates').' - '.config_item('company_name'));
        $this->load->model(array('Client', 'Estimate','App'));
        App::module_access('menu_estimates');

        $this->applib->set_locale();
    }

    public function index()
    {
        $data['page'] = lang('estimates');
        $data['datatables'] = true;
        $data['datepicker'] = true;
        $data['estimates'] = $this->_estimate_list();
        $this->template
    ->set_layout('users')
    ->build('estimates', isset($data) ? $data : null);
    }
    public function ajax_estimates(){
        $all_estimates = $this->_estimate_list();
        $estimates = $this->_estimate_list_limit();
        $return_data['recordsTotal'] = $return_data['recordsFiltered'] = count($all_estimates);
        if(!empty($estimates)){
            $i = 0;
            foreach($estimates as $e){
                $label = 'danger';
                if ($e->status == 'Pending'){ $label = "info"; }
                if($e->status == 'Accepted') { $label = "success";  }
                $return_data['data'][$i]['ref_no']       = '<a class="text-info" href="'.base_url().'estimates/view/'.$e->est_id.'">'.$e->reference_no.'</a>';
                $return_data['data'][$i]['company']      = Client::view_by_id($e->client)->company_name;
                $return_data['data'][$i]['status']       = '<span class="label label-'.$label.'">'.lang(strtolower($e->status));
                if($e->emailed == 'Yes') {
                    $return_data['data'][$i]['status']   .= '<i class="fa fa-envelope-o"></i>';
                }
                $return_data['data'][$i]['status']       .= '</span>';
                $return_data['data'][$i]['due_date']      = strftime(config_item('date_format'), strtotime($e->due_date));
                $return_data['data'][$i]['save_date']     = strftime(config_item('date_format'), strtotime($e->date_saved));
                $return_data['data'][$i]['currency']      = Applib::format_currency($e->currency, Estimate::due($e->est_id));
                $return_data['data'][$i]['action']        = '<div class="dropdown"><a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a><ul class="dropdown-menu pull-right">';
                if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_estimates')) { 
                    $return_data['data'][$i]['action']   .= '<li><a href="'.base_url().'estimates/edit/'.$e->est_id.'">'.lang('edit_estimate').'</a></li>
                    <li><a href="'.base_url().'estimates/timeline/'.$e->est_id.'">'.lang('estimate_history').'</a></li>';
                }
                if(User::is_admin() || User::perm_allowed(User::get_id(),'view_all_estimates')) {
                    $return_data['data'][$i]['action']   .= '<li><a href="'.base_url().'estimates/email/'.$e->est_id.'" data-toggle="ajaxModal" title="'.lang('email_estimate').'">'.lang('email_estimate').'</a></li>';
                }
                $return_data['data'][$i]['action']      .= '<li><a href="'.base_url().'estimates/view/'.$e->est_id.'">'.lang('view_estimate').'</a></li>';
                if (config_item('pdf_engine') == 'invoicr'){
                    $return_data['data'][$i]['action']   .= '<li><a href="'.base_url().'fopdf/estimate/'.$e->est_id.'">'.lang('pdf').'</a></li>';
                }
                elseif(config_item('pdf_engine') == 'mpdf'){
                    $return_data['data'][$i]['action']   .= '<li><a href="'.base_url().'estimates/pdf/'.$e->est_id.'">'.lang('pdf').'</a></li>';
                }
                $return_data['data'][$i]['action']   .= '</ul></div>';
                $i++;
            }
        }
        echo json_encode($return_data);exit;

    }
    public function view($estimate_id = null)
    {
        if (!User::can_view_estimate(User::get_id(), $estimate_id)) {
            App::access_denied('estimates');
        }

        $data['page'] = lang('estimates');
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['id'] = $estimate_id;
        $data['estimates'] = $this->_estimate_list(); // GET a list of the Estimates

        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

    public function add()
    {
        if (!User::can_add_estimate()) {
            App::access_denied('estimates');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('reference_no', 'Ref No', 'required');
            $this->form_validation->set_rules('client', 'Client', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('tokbox_error', lang('operation_failed'));
                redirect('estimates');
            } else {
                $_POST['reference_no'] = config_item('estimate_prefix').Estimate::generate_estimate_number();
                // Inherit currency
                $currency = Client::client_currency($_POST['client']);
                $_POST['currency'] = $currency->code;
                $_POST['due_date'] = date_format(date_create_from_format(config_item('date_php_format'),
                        $this->input->post('due_date')), 'Y-m-d');
                unset($_POST['files']);
               // $_POST['branch_id'] = $this->session->userdata('branch_id');

                if ($estimate_id = App::save_data('estimates', $this->input->post())) {
                    // Log Activity
            $data = array(
                'module' => 'estimates',
                'module_field_id' => $estimate_id,
                'user' => User::get_id(),
                'activity' => 'activity_estimate_created',
                'icon' => 'fa-plus',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $_POST['reference_no'],
                'value2' => '',
                );
                    App::Log($data);
                    // Applib::go_to('estimates/view/'.$estimate_id, 'success', lang('estimate_created_successfully'));
                    $this->session->set_flashdata('tokbox_success', lang('estimate_created_successfully'));
                    redirect('estimates/view/'.$estimate_id);
                }
            }
        } else {
            $data['page'] = lang('estimates');
            $data['form'] = true;
            $data['editor'] = true;

            $data['datepicker'] = true;
            $data['datatables'] = true;
            $data['estimates'] = $this->_estimate_list();

            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
            }
            else{
                $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
            }

            $this->template
                ->set_layout('users')
                ->build('create_estimate', isset($data) ? $data : null);
        }
    }

    public function edit($id = null)
    {
        if (!User::can_edit_estimate()) {
            App::access_denied('estimates');
        }

        if ($this->input->post()) {
            $id = $this->input->post('est_id', true);

            $this->form_validation->set_rules('reference_no', 'Ref No', 'required');
            $this->form_validation->set_rules('client', 'Client', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');

            if ($this->form_validation->run() == false) {
                $_POST = '';
                // Applib::go_to('estimates/edit/'.$id, 'error', lang('error_in_form'));
                $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
                redirect('estimates/edit/'.$id);
            } else {
                $_POST['due_date'] = Applib::date_formatter($this->input->post('due_date'));
                $_POST['date_saved'] = Applib::date_formatter($this->input->post('date_saved'));

                if (isset($_POST['files'])) {
                    unset($_POST['files']);
                }

                App::update('estimates', array('est_id' => $id), $this->input->post());
                $data = array(
                'module' => 'estimates',
                'module_field_id' => $id,
                'user' => User::get_id(),
                'activity' => 'activity_estimate_edited',
                'icon' => 'fa-pencil',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $this->input->post('reference_no', true),
                'value2' => '',
                );
                App::Log($data);

                // Applib::go_to('estimates/view/'.$id, 'success', lang('estimate_edited_successfully'));
                $this->session->set_flashdata('tokbox_success', lang('estimate_edited_successfully'));
                redirect('estimates/view/'.$id);
            }
        } else {
            $data['page'] = lang('estimates');
            $data['datepicker'] = true;
            $data['editor'] = true;
            $data['form'] = true;
            $data['estimates'] = $this->_estimate_list(); // GET a list of the Invoices
            $data['id'] = $id;
            $this->template
            ->set_layout('users')
            ->build('edit_estimate', isset($data) ? $data : null);
        }
    }

    public function timeline($id = null)
    {
        $data['page'] = lang('estimates');
        $data['id'] = $id;
        $sort = array('column' => 'activity_id', 'order' => 'desc');
        $data['activities'] = App::get_by_where('activities',
                                    array('module_field_id' => $id, 'module' => 'estimates'), $sort);
        $data['estimates'] = $this->_estimate_list();
        $this->template
        ->set_layout('users')
        ->build('timeline', isset($data) ? $data : null);
    }

    public function pdf()
    {
        $id = $this->uri->segment(3);

        if (!User::can_view_estimate(User::get_id(), $id)) {
            App::access_denied('estimates');
        }

        $data['page'] = lang('estimates');
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['id'] = $id;

        $html = $this->load->view('estimate_pdf', $data, true);
        $pdf = array(
                    'html' => $html,
                    'title' => lang('estimate').' '.Estimate::view_estimate($id)->reference_no,
                    'author' => config_item('company_name'),
                    'creator' => config_item('company_name'),
                    'filename' => lang('estimate').' '.Estimate::view_estimate($id)->reference_no.'.pdf',
                    'badge' => config_item('display_estimate_badge'),
                );

        $this->applib->create_pdf($pdf);
    }

    public function attach_pdf($id)
    {
        if (!User::can_view_estimate(User::get_id(), $id)) {
            App::access_denied('estimates');
        }

        $data['page'] = lang('estimates');
        $data['sortable'] = true;
        $data['typeahead'] = true;
        $data['id'] = $id;

        $html = $this->load->view('estimate_pdf', $data, true);
        $pdf = array(
                    'html' => $html,
                    'title' => lang('estimate').' '.Estimate::view_estimate($id)->reference_no,
                    'author' => config_item('company_name'),
                    'creator' => config_item('company_name'),
                    'filename' => lang('estimate').' '.Estimate::view_estimate($id)->reference_no.'.pdf',
                    'attach' => true,
                    'badge' => config_item('display_estimate_badge'),
                );

        $estimate = $this->applib->create_pdf($pdf);

        return $estimate;
    }

    public function autoitems()
    {
        $query = 'SELECT * FROM (
                            SELECT item_name FROM dgt_items
                            UNION ALL
                            SELECT item_name FROM dgt_estimate_items
                            ) a
                            GROUP BY item_name
                            ORDER BY item_name ASC';
        $names = $this->db->query($query)->result();
        $name = array();
        foreach ($names as $n) {
            $name[] = $n->item_name;
        }
        $data['json'] = $name;
        $this->load->view('json', isset($data) ? $data : null);
    }

    public function autoitem()
    {
        $name = $_POST['name'];
        $query = "SELECT * FROM (
                            SELECT item_name, item_desc, quantity, unit_cost FROM dgt_items
                            UNION ALL
                            SELECT item_name, item_desc, quantity, unit_cost FROM dgt_estimate_items
                            ) a
                            WHERE a.item_name = '".$name."'";
        $names = $this->db->query($query)->result();
                //$items = $this->db->where('item_name',$name)->get(($scope == 'invoices' ? 'items':'estimate_items'))->result();
                $name = $names[0];
        $data['json'] = $name;
        $this->load->view('json', isset($data) ? $data : null);
    }

    public function show($estimate_id = null)
    {
        $data = array('show_client' => 'Yes');
        App::update('estimates', array('est_id' => $estimate_id), $data);
        // Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('estimate_visible'));
        $this->session->set_flashdata('tokbox_success', lang('estimate_visible'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function hide($estimate_id = null)
    {
        $data = array('show_client' => 'No');
        App::update('estimates', array('est_id' => $estimate_id), $data);
        // Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('estimate_not_visible'));
        $this->session->set_flashdata('tokbox_error', lang('estimate_not_visible'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function status()
    {
        $this->load->model('Project');
        $estimate = $this->uri->segment(4);
        $status = ($this->uri->segment(3) == 'accepted') ? 'Accepted' : 'Declined';
        if ($status == 'Accepted' && config_item('estimate_to_project') == 'TRUE') {
            // Convert estimate to project
                $new_project = Project::convert_estimate_to_project($estimate);

            $data = array(
                    'module' => 'projects',
                    'module_field_id' => $new_project,
                    'user' => User::get_id(),
                    'activity' => 'activity_added_new_project',
                    'icon' => 'fa-coffee',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => Project::by_id($new_project)->project_title,
                    'value2' => '',
                );
            App::Log($data);

                // Send email to the assigned users
                if (config_item('notify_project_assignments') == 'TRUE') {
                    modules::run('projects/assigned_notification', $new_project);
                }

                // Send email to client
                if (config_item('notify_project_opened') == 'TRUE') {
                    modules::run('projects/client_notification', $new_project);
                }
        }
        $this->notify_admin($estimate,$status);

        $data = array('status' => $status);
        App::update('estimates', array('est_id' => $estimate), $data);

            // Log activity
            $data = array(
                'module' => 'estimates',
                'module_field_id' => $estimate,
                'user' => User::get_id(),
                'activity' => 'activity_estimate_marked',
                'icon' => 'fa-paperclip',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => Estimate::view_estimate($estimate)->reference_no,
                'value2' => $status,
                );
        App::Log($data);

        // $this->session->set_flashdata('response_status', 'success');
        // $this->session->set_flashdata('message', lang('estimate_'.$this->uri->segment(3).'_successfully'));
        $this->session->set_flashdata('tokbox_success', lang('estimate_'.$this->uri->segment(3).'_successfully'));
        redirect('estimates/view/'.$estimate);
    }

    public function convert($id = NULL)
    {
        if($this->input->post()) :

            $id = $this->input->post('id');
            $i = Estimate::view_estimate($id);
            $this->load->model('Invoice');

            $ref = config_item('invoice_prefix').filter_var($i->reference_no, FILTER_SANITIZE_NUMBER_INT);

            if (config_item('increment_invoice_number') == 'TRUE') {
                $ref = config_item('invoice_prefix').Invoice::generate_invoice_number();
            }
            $data = array(
                                'reference_no' => $ref,
                                'client' => $i->client,
                                'currency' => $i->currency,
                                'due_date' => $i->due_date,
                                'notes' => $i->notes,
                                'tax' => $i->tax,
                                'discount' => $i->discount,
                            );
            $invoice_id = App::save_data('invoices', $data);

            $items = Estimate::has_items($id);

            foreach ($items as $key => $item) {
                $data = array(
                                'invoice_id' => $invoice_id,
                                'item_name' => $item->item_name,
                                'item_desc' => $item->item_desc,
                                'unit_cost' => $item->unit_cost,
                                'quantity' => $item->quantity,
                                'item_tax_rate' => $item->item_tax_rate,
                                'item_tax_total' => $item->item_tax_total,
                                'total_cost' => $item->total_cost,
                            );
                App::save_data('items', $data);
            }

                // Log activity
                $data = array(
                    'module' => 'invoices',
                    'module_field_id' => $invoice_id,
                    'user' => User::get_id(),
                    'activity' => 'activity_estimate_converted',
                    'icon' => 'fa-laptop',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $i->reference_no,
                    'value2' => $ref,
                    );
            App::Log($data);
            $data = array('invoiced' => 'Yes');
            App::update('estimates', array('est_id' => $id), $data);
            // Applib::go_to('invoices/view/'.$invoice_id, 'success', lang('estimate_invoiced_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('estimate_invoiced_successfully'));
            redirect('invoices/view/'.$invoice_id);




        else:
            $data['id'] = $id;
            $this->load->view('modal/convert_estimate', $data);


    endif;
    }

    public function delete($id = null)
    {
        if ($this->input->post()) {
            $estimate = $this->input->post('estimate', true);
            App::delete('estimate_items', array('estimate_id' => $estimate));
            App::delete('estimates', array('est_id' => $estimate));
            App::delete('activities', array('module' => 'estimates', 'module_field_id' => $estimate));

            // Applib::go_to('estimates', 'success', lang('estimate_deleted_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('estimate_deleted_successfully'));
            redirect('estimates');
        } else {
            $data['id'] = $id;
            $this->load->view('modal/delete_estimate', $data);
        }
    }

    public function email($id = null)
    {
        if ($this->input->post()) {
            $id = $this->input->post('id', true);
            $info = Estimate::view_estimate($id);
            $client = Client::view_by_id($info->client);
            if ($client->primary_contact > 0) {
                $login = '?login='.$this->tank_auth->create_remote_login($client->primary_contact);
            } else {
                $login = '';
            }

            $message = $this->input->post('message');
            $signature = App::email_template('email_signature', 'template_body');

            $subject = $this->input->post('subject', true);
            $due = Applib::format_quantity(Estimate::due($id));

            $logo_link = create_email_logo();

            $logo = replace_email_tags('INVOICE_LOGO', $logo_link, $message);
            $company = replace_email_tags('CLIENT', $client->company_name, $logo);
            $ref = replace_email_tags('ESTIMATE_REF', $info->reference_no, $company);
			$created = replace_email_tags('CREATED_DATE', strftime(config_item('date_format'), strtotime($info->date_saved)), $ref);
			$due_date = str_replace('{DUE_DATE}', strftime(config_item('date_format'), strtotime($info->due_date)), $created);
            $amount = str_replace('{AMOUNT}', $due, $due_date);
            $currency = str_replace('{CURRENCY}', App::currencies($info->currency)->symbol, $amount);
            $link = str_replace('{ESTIMATE_LINK}', base_url().'estimates/view/'.$id.$login, $currency);
            $EmailSignature = str_replace('{SIGNATURE}', $signature, $link);
            $message = str_replace('{SITE_NAME}', config_item('company_name'), $EmailSignature);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, true);

            $params['recipient'] = $client->company_email;
            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['alt_email'] = 'billing';
            $params['attached_file'] = './assets/tmp/'.lang('estimate').' '.$info->reference_no.'.pdf';
            $params['attachment_url'] = base_url().'assets/tmp/'.lang('estimate').' '.$info->reference_no.'.pdf';

            $estimate['est_id'] = $id;
            $estimate['ref'] = $info->reference_no;
            if (config_item('pdf_engine') == 'invoicr') {
                $estimatehtml = modules::run('fopdf/attach_estimate', $estimate);
            }
            if (config_item('pdf_engine') == 'mpdf') {
                $estimatehtml = $this->attach_pdf($id);
            }

            modules::run('fomailer/send_email', $params);
            //Delete estimate in tmp folder
            if (is_file('./assets/tmp/'.lang('estimate').' '.$info->reference_no.'.pdf')) {
                unlink('./assets/tmp/'.lang('estimate').' '.$info->reference_no.'.pdf');
            }

            // Update sent column
            $emailed = array('emailed' => 'Yes', 'date_sent' => date('Y-m-d H:i:s', time()));
            App::update('estimates', array('est_id' => $id), $emailed);

            // Log activity
            $data = array(
                'module' => 'estimates',
                'module_field_id' => $id,
                'user' => User::get_id(),
                'activity' => 'activity_estimate_sent',
                'icon' => 'fa-envelope',
                'branch_id' => $this->session->userdata('branch_id'),
                'value1' => $info->reference_no,
                'value2' => '',
                );
            App::Log($data);

            // Applib::go_to('estimates/view/'.$id, 'success', lang('estimate_sent_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('estimate_sent_successfully'));
            redirect('estimates/view/'.$id);
        } else {
            $data['id'] = $id;
            $this->load->view('modal/email_estimate', $data);
        }
    }


    public function notify_admin($id, $status)
    {
            $info = Estimate::view_estimate($id);
            $subject = 'Estimate #'.$info->reference_no.' '.$status;
            $data['estimate'] = $info;
            $data['status'] = $status;
            $data['amount'] = Estimate::due($id);

            $params['subject'] = $subject;
            $params['message'] = $this->load->view('estimate_email',$data, true);

            foreach(User::admin_list() as $key => $user) :
                $params['recipient'] = $user->email;
                modules::run('fomailer/send_email', $params);
            endforeach;
    }

    public function _estimate_list()
    {
        if (User::is_admin() || User::perm_allowed(User::get_id(), 'view_all_estimates')) {
            return Estimate::by_where('estimates', array('est_id !=' => '0'));
        } else {

            $branches = $this->db->select('*')->from('assigned_entities')->where('user_id',$this->session->userdata('user_id'))->get()->result_array();
			$branches = array_column($branches, 'branch_id');

            $estimates = $this->db->where_in('branch_id',$branches)->where(array('client' => $company, 'show_client' => 'Yes'))->get('estimates')->result();

            return $estimates;
        }
    }
    public function _estimate_list_limit()
    {
        if (User::is_admin() || User::perm_allowed(User::get_id(), 'view_all_estimates')) {
            return Estimate::by_where_limit('estimates', array('est_id !=' => '0'));
        } else {
            $page = $_GET['start'];
		    $limit = $_GET['length'];

            $branches = $this->db->select('*')->from('assigned_entities')->where('user_id',$this->session->userdata('user_id'))->get()->result_array();
			$branches = array_column($branches, 'branch_id');

            $where_arr = array();
            $where_arr['client']         =   $company;
            $where_arr['show_client']    =   'Yes';
            if(!empty($_GET['estimates_to'])){
                $dto = date('Y-m-d',strtotime($_GET['estimates_to']));
                $where_arr['date_saved >=']      =   $dto;

            }
            if(!empty($_GET['estimates_to'])){
                $dto = date('Y-m-d',strtotime($_GET['estimates_to']));
                $array['due_date <=']      =   $dto;
    
            }
            if(!empty($_GET['estimates_status'])){
                $where_arr['status']      =   $_GET['estimates_status'];
    
            }
            $estimates = $this->db->where_in('branch_id',$branches)->where($where_arr)->limit($limit,$page)->get('estimates')->result();
            return $estimates;
        }
    }
}

/* End of file estimates.php */
