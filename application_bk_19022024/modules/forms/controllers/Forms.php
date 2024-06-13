<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Forms extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Lead'));
        if(!App::is_access('menu_forms'))
        {
            $this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        }
        
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Forms - '.config_item('company_name'));
        $data['page'] = 'Forms';
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['countries'] = App::countries();
        $data['employee'] = $this->db->select('*')->from('users')->join('account_details', 'users.id = account_details.user_id AND users.role_id!=2')->get()->result_array();
        $data['user_details'] =  $this->db->get_where('account_details',array())->result_array();
        
        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
            $branches = array_column($data['branches'],'branch_id');
            $data['all_forms'] = $this->db->where_in('branch_id',$branches)->order_by('form_id','DESC')->get('forms')->result_array();
        }
        else{
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
            if($this->tank_auth->get_role_id() != 1){
                
                $data['all_forms'] = $this->db->where('branch_id',$this->session->userdata('branch_id'))->order_by('form_id','DESC')->get('forms')->result_array();
            }
            else{
                 $data['all_forms'] = $this->db->order_by('form_id','DESC')->get('forms')->result_array();
            }
        }
        if (User::is_admin()) {
            $this->template
                ->set_layout('users')   
                ->build('em_form', isset($data) ? $data : null);
        }
        else
        {
            $this->em_form();
        }
        
        
    }
    public function em_form()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Forms - '.config_item('company_name'));
        $data['page'] = 'Forms';
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['countries'] = App::countries();
        $data['employee'] = $this->db->select('*')->from('users')->join('account_details', 'users.id = account_details.user_id AND users.role_id!=2')->get()->result_array();
        $data['user_details'] =  $this->db->get_where('account_details',array())->result_array();
        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();

            $branches = array_column($data['branches'],'branch_id');
            $data['all_forms'] = $this->db->where_in('branch_id',$branches)->order_by('form_id','DESC')->get('forms')->result_array();
        }
        else{
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
            if($this->tank_auth->get_role_id() != 1){
                
                $data['all_forms'] = $this->db->where('branch_id',$this->session->userdata('branch_id'))->order_by('form_id','DESC')->get('forms')->result_array();
            }
            else{
                 $data['all_forms'] = $this->db->order_by('form_id','DESC')->get('forms')->result_array();
            }
        }
        $this->template
                ->set_layout('users')   
                ->build('em_form', isset($data) ? $data : null);
    }
    public function ad_list()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Forms - '.config_item('company_name'));
        $data['page'] = 'Forms';
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['countries'] = App::countries();
        $data['employee'] = $this->db->select('*')->from('users')->join('account_details', 'users.id = account_details.user_id AND users.role_id!=2')->get()->result_array();
        $data['user_details'] =  $this->db->get_where('account_details',array())->result_array();
        $data['all_forms'] = $this->db->order_by('form_id','DESC')->get('forms')->result_array();
        $this->template
                ->set_layout('users')   
                ->build('ad_form', isset($data) ? $data : null);
    }
public function add_forms()
    {
        $form_name=$this->input->post('form_name');
        $category=$this->input->post('category');
        $keywords=$this->input->post('keywords');

        $dataInfo = array();
        $files = $_FILES;
        $_FILES['attachments']['name']= $files['attachments']['name'];
        $_FILES['attachments']['type']= $files['attachments']['type'];
        $_FILES['attachments']['tmp_name']= $files['attachments']['tmp_name'];
        $_FILES['attachments']['error']= $files['attachments']['error'];
        $_FILES['attachments']['size']= $files['attachments']['size'];    
        
        $config['upload_path'] = './uploads/files';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|docs|PNG|txt|doc|xls';
        $config['overwrite'] = true;
        $this->load->library('upload',$config);
        $r = $this->upload->do_upload('attachments');
        $dataInfo[] = $this->upload->data();
        // echo "<pre>"; print_r($r); exit;
        $data=array(
        'form_name'=>$form_name,
        'category'=>$category,
        'keywords'=>$keywords,
        'file'=>preg_replace('/\s+/', '_',$_FILES['attachments']['name'])
        );

        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
            $branch_id =  $data['branch_id'] = $this->session->userdata('branch_id');
         }
         else{
             $branch_id =  $data['branch_id'] = $this->input->post('branch_id');
         }


        $insert=$this->db->insert('forms',$data);
        $this->session->set_flashdata('tokbox_success', 'Form added Successfully');
        redirect('forms');
    }
public function edit_forms()
{
        $form_id=$this->uri->segment(3);
        $form_name=$this->input->post('form_name'.$form_id);
        $category=$this->input->post('category'.$form_id);
        $keywords=$this->input->post('keywords'.$form_id);

        $dataInfo = array();
        $files = $_FILES;
        if($_FILES['attachments'.$form_id]['name']=="")
        {
            $file=$this->input->post('exist_file'.$form_id);
        }
        else
        {
            $file=preg_replace('/\s+/', '_',$_FILES['attachments'.$form_id]['name']);
            $_FILES['attachments']['name']= $files['attachments'.$form_id]['name'];
            $_FILES['attachments']['type']= $files['attachments'.$form_id]['type'];
            $_FILES['attachments']['tmp_name']= $files['attachments'.$form_id]['tmp_name'];
            $_FILES['attachments']['error']= $files['attachments'.$form_id]['error'];
            $_FILES['attachments']['size']= $files['attachments'.$form_id]['size'];    

            $config['upload_path'] = './uploads/files';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|docs|PNG|txt|doc|xls';
            $config['overwrite'] = true;
            $this->load->library('upload',$config);
            $this->upload->do_upload('attachments');
            $dataInfo[] = $this->upload->data();
        }           
        

        $data=array(
        'form_name'=>$form_name,
        'category'=>$category,
        'keywords'=>$keywords,
        'file'=>$file
        );
        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
            $branch_id =  $data['branch_id'] = $this->session->userdata('branch_id');
        }
        else{
             $branch_id =  $data['branch_id'] = $this->input->post('branch_id');
         }
        $insert=$this->db->where('form_id',$form_id)->update('forms',$data);
        redirect('forms');
}
public function delete_form()
{
    $form_id=$this->uri->segment(3);
    
    $delete=$this->db->where('form_id',$form_id)->delete('forms');
    redirect('forms');
}


public function auto_search() {
    
$search_data = $this->input->post('search_data');

    $query= $this->db->select('*')->from('forms')->like(array("form_name"=>$search_data))->get()->result_array();   
    $num_rows= $this->db->select('*')->from('forms')->like(array("form_name"=>$search_data))->get()->num_rows(); 
if($num_rows=='0')
{
    $html.="<span style='text-align:centre;color:#6c757d'><h3>No Result Found !!!</h3></span>";
}   
foreach ($query as $form){
$html.='<div class="col-lg-4">
            <div class="card">
                <div class="card-header text-center">
                    <a href="'.base_url().'forms/detail_view/'.$form['form_id'].'"><h4 class="card-title mb-0">
                        '.$form['form_name'].'
                    </h4></a>
                </div>
                <div class="card-body">
                    <div class="card-file-thumb">';
                    $file_t=explode('.',$form['file']);
                    $file_type=$file_t[1];
                    if($file_type=='pdf')
                    {
                        $html.='<i class="fa fa-file-pdf-o"></i>';

                    
                    }
                    else if($file_type=='docx')
                    {
                        $html.='<i class="fa fa-file-word-o"></i>';

                    
                    }
                    else if($file_type=='png'||$file_type=='jpg'||$file_type=='psd')
                    {
                        $html.='<img src="'.base_url().'uploads/files/'.$form['file'].'" style="height: 90%;width: 30%;">';

                    
                    }
                    else if($file_type=='xls')
                    {
                        $html.='<i class="fa fa-file-excel-o"></i>';

                    
                    }
                    else if($file_type=='ppt')
                    {
                        $html.='<i class="fa fa-file-powerpoint-o"></i>';

                    
                    }
                    else if($file_type=='mp3')
                    {
                        $html.='<i class="fa fa-file-audio-o"></i>';

                    
                    }
                    else if($file_type=='mp4')
                    {
                        $html.='<i class="fa fa-file-video-o"></i>';

                    
                    }
                    else if($file_type=='txt')
                    {
                        $html.='<i class="fa fa-file-text-o"></i>';
                    
                    }
                    else if($file_type=='html')
                    {
                        $html.='<i class="fa fa-file-code-o"></i>';

                    }
                    
                $html.='</div>
                <center>'.$form['file'].'</center>
                </div>
                <div class="card-footer text-center">
                    <a href="'.base_url().'forms/download_file/'.$form['file'].'" target="_blank" class="btn btn-primary submit-btn">Dowload</a>
                </div>
            </div>
        </div>';
            
}
echo $html;
exit();
}
function detail_view()
{
     $form_id= $this->uri->segment(3);
    $this->load->helper('download');
    $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title('Forms - '.config_item('company_name'));
        $data['page'] = 'Forms';
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['countries'] = App::countries();
        $data['employee'] = $this->db->select('*')->from('users')->join('account_details', 'users.id = account_details.user_id AND users.role_id!=2')->get()->result_array();
        $data['user_details'] =  $this->db->get_where('account_details',array())->result_array();
        $data['all_forms'] = $this->db->order_by('form_id','DESC')->get_where('forms',array("form_id"=>$form_id))->result_array();
        $this->template
                ->set_layout('users')   
                ->build('em_detail_view', isset($data) ? $data : null);
}

function download_file()
{
    $this->load->helper('download');
    $id=$this->uri->segment(3);
    $file_name=$this->db->get_where('forms',array('form_id'=>$id))->row()->file;
    $path = base_url('uploads/files/'.$file_name);
    $data   = file_get_contents($path);
    $this->session->set_flashdata('tokbox_success', 'Download Successfully');
    force_download($file_name, $data);
    
}





        
  function view_file(){
        $fname = $this->uri->segment(3);
        $tofile= realpath("assets/uploads/".$fname);
       // header('Content-Type:application/msword');
        readfile($tofile);
    }



    public function addCategory()
   {
        $post = $this->input->post();
        if($post!='') {
            $post_data = array(
                'category_name'  =>$this->input->post('category_name'),
                'created_datetime'  => date('Y-m-d H:i:s')
            );
            
            if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') !='company_admin'){
                $branch_id =  $post_data['branch_id'] = $this->session->userdata('branch_id');
             }
             else{
                 $branch_id =  $post_data['branch_id'] = $this->input->post('branch_id');
             }
            if($this->input->post('category_id') == '') {
                $this->db->insert('forms_category',$post_data);
            } else {
                App::update('forms_category',array('id'=>$this->input->post('category_id')),$post_data);
            }
            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('tokbox_success', 'Category Name Added Successfully');
                redirect($_SERVER['HTTP_REFERER']);
            } 
        } else {
            $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect($_SERVER['HTTP_REFERER']);
        }
   }

   public function category_list()
   {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('category'));
        $data['form'] = TRUE;
        $data['page'] = lang('forms');        
        $data['datatables'] = true;
        // $data['categories'] = $this->db->select('*')->get_where('category', array('status!='=>'2'))->result();
        $data['categories'] = $this->db->select('*')->get_where('forms_category', array('status!='=>'2'))->result();

        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
        }
        else{
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
        }
        $this->template
            ->set_layout('users')
            ->build('category',isset($data) ? $data : NULL);
   }


   public function edit_category($id) {
        $data['form'] = TRUE;
        $data['category_details'] = $this->db->get_where('forms_category',array('id'=>$id,'status!='=>'2'))->row();
        $data['id'] = $id;

        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
        }
        else{
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
        }
        $this->load->view('edit_category',$data);
    }

    public function delete_category() {
        if($this->input->post()) {
            $det['status']= '2'; 
            App::update('forms_category',array('id'=>$this->input->post('category_id')),$det);
            $this->session->set_flashdata('tokbox_success', 'Category Deleted Successfully');
            redirect('forms/category_list');
        }else{
            $data['category_id'] = $this->uri->segment(3);
            $this->load->view('delete_category',$data);
        } 
    }

    public function changeCategoryStatus() {
        if($this->input->post()) {
            $minute_data = $this->db->select('status')->get_where('forms_category', array('id'=>$this->input->post('category_id')))->row();
            if($minute_data->status == '1') {
                $det['status']= '0'; 
            } else if($minute_data->status == '0') {
                $det['status']= '1'; 
            } 
            App::update('forms_category',array('id'=>$this->input->post('category_id')),$det);
            $this->session->set_flashdata('tokbox_success', 'Category Status Changed Successfully');
            redirect('forms/category_list');
        }else{
            $data['category_id'] = $this->uri->segment(3);
            $this->load->view('change_category_status',$data);
        } 
    }

    public function check_categoryname($category_name)
    {
        $category_name = $this->input->post('categoryname');
        $category = $this->db->get_where('forms_category',array('category_name'=>$category_name))->num_rows();
        
        if($category > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    
    
}
/* End of file all_departments.php */
