<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Promotion extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        
       $this->load->model('promotionmodel','promotions');
        $this->load->library(array('form_validation','tank_auth'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment','promotionmodel'));
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
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Promotion'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('promotion');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('promotion',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


     public function get_designation()
     {
     	$id=$this->input->post('employeeid');

     	$result=$this->db->query("SELECT u.designation_id,d.designation FROM dgt_users AS u LEFT JOIN dgt_designation AS d ON u.designation_id=d.id  WHERE u.id='".$id."'")->row_array();
        echo json_encode($result);
        exit;	

     }

     public function get_designation_by_dept()
     {
     	$id=$this->input->post('deptid');

     	$result=$this->db->query("SELECT id,designation FROM dgt_designation  WHERE department_id='".$id."'")->result_array();
        echo json_encode($result);
        exit;	

     }

     public function get_department()
     {
     	$emp_id=$this->input->post('employeeid');
     	$edit_id=$this->input->post('edit_id');
        if(!empty($edit_id)){
            $result=$this->db->query("SELECT department as department_id FROM dgt_promotion  WHERE id='".$edit_id."'")->row_array();
        }
     	else{
            $result=$this->db->query("SELECT department_id FROM dgt_users  WHERE id='".$emp_id."'")->row_array();
        }
     	$results=$this->db->query("SELECT deptid,deptname FROM dgt_departments")->result_array();
        if(!empty($results)){
            foreach($results as $key=> $result_1){
                $results[$key]['department_id'] = $result['department_id'];
            }
        }
        echo json_encode($results);
        exit;	

     }

     public function getemployeeAnnualctc()
     {
     	$emp_id=$this->input->post('employeeid');
     	$edit_id=$this->input->post('edit_id');
        $all_addtional = $this->db->get_where('bank_statutory',array('user_id'=>$emp_id))->row_array(); 
        $addtional_ar = json_decode($all_addtional['pf_addtional'],TRUE);


       
        $key = array_search('Annual CTC', array_column($addtional_ar, 'addtion_name'));
        // echo '<pre>';print_r($addtional_ar[$key]['unit_amount']);exit;
        echo $addtional_ar[$key]['unit_amount'];
        exit;	
        

        if(!empty($edit_id)){
            $result=$this->db->query("SELECT department as department_id FROM dgt_promotion  WHERE id='".$edit_id."'")->row_array();
        }
     	else{
            $result=$this->db->query("SELECT department_id FROM dgt_users  WHERE id='".$emp_id."'")->row_array();
        }
     	$results=$this->db->query("SELECT deptid,deptname FROM dgt_departments")->result_array();
        if(!empty($results)){
            foreach($results as $key=> $result_1){
                $results[$key]['department_id'] = $result['department_id'];
            }
        }
        echo json_encode($results);
        exit;	

     }


     

     public function get_grades()
     {
     	$id=$this->input->post('employeeid');

     	$results=$this->db->query("SELECT u.department_id FROM dgt_users AS u LEFT JOIN dgt_departments AS d ON u.department_id=d.deptid  WHERE u.id='".$id."'")->row_array();



     		$this->db->where('department_id',$results['department_id']);
     		$query=$this->db->get('dgt_designation');
     		$result= $query->result();
	        $data=array();
			foreach($result as $r)
			{
				$data['id']=$r->id;
				$data['designation']=$r->designation;
				$json[]=$data;
				
				
			}
		echo json_encode($json);
		exit;
     	
     }


        public function promotion_list()
    {
        $list = $this->promotions->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
         foreach ($list as $promotion) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $promotion->fullname;
            $row[] = $promotion->deptname;
            $row[] = $promotion->promotion_from;
            $row[] = $promotion->promotion_to;
            $row[] = date('d M Y',strtotime($promotion->promotiondate));
            if($this->session->userdata('role_id') != 1){
                if($promotion->accept_status=='0'){
                    $row[] = 'Pending';
                }
                else if($promotion->accept_status=='1'){
                    $row[] = 'Accepted';
                }
                else if($promotion->accept_status=='2'){
                    $row[] = 'Rejected';
                }
                $row[]='<div class="dropdown dropdown-action">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu pull-right">
                    <li><a href="#" onclick="accept_promotion_new('.$promotion->id.')">Accept</a></li>
                    <li><a href="#" onclick="reject_promotion('.$promotion->id.')">Reject</a></li>
                </ul>
            </div>';
            }
            else{
                
            $row[]='<div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#" onclick="edit_promotion('.$promotion->id.')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                                <li><a href="#" onclick="delete_promotions('.$promotion->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                            </ul>
                        </div>';
            }


           

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->promotions->count_all(),
            "recordsFiltered" => $this->promotions->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }
    public function promotion_reject($id){

        $data = array(
            'accept_status'=>2
        );
        $this->db->where('id',$id);
        $this->db->update('promotion',$data);
        echo '1';exit;
    }
    public function promotion_accept($id){

        $data = array(
            'accept_status'=>1
        );
        $this->db->where('id',$id);
        $this->db->update('promotion',$data);
        echo '1';exit;
    }

    public function promotion_edit($id)
    {
        $data = $this->promotions->get_by_id($id);

        echo json_encode($data);
        exit;
    }

    public function add_promotion()
    {    	
        $employee=$this->input->post('employee');
        $department=$this->input->post('department');
        $designation=$this->input->post('designation');
        $grade=$this->input->post('grade');
        $promotionto=$this->input->post('promotionto');
        $promotiondate=date('Y-m-d',strtotime($this->input->post('promotiondate')));

        $currentsalary=$this->input->post('currentsalary');
        $newsalary=$this->input->post('newsalary');
        $effectivedate=date('Y-m-d',strtotime($this->input->post('effectivedate')));
       
        $data = array(
            'employee'=>$employee,
            'designation'=>$designation,
            'department'=>$department,
            'grade'=>$grade,
            'promotionto'=>$promotionto,
            'promotiondate'=>$promotiondate,
            'currentsalary'=>$currentsalary,
            'newsalary'=>$newsalary,
            'effectivedate'=>$effectivedate,
            'posted_date'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('promotion',$data);
        // echo $this->db->last_query();exit;
        $result=($this->db->affected_rows()!= 1)? false:true;

        if($result==true) 
        {
            $where_cond = array('user_id'=>$employee);
            $user_data = $this->db->select('branch_id,fullname')->from('dgt_account_details')->where($where_cond)->get()->row_array();
            $where_cond = array('id'=>$employee);
            $user_data1 = $this->db->select('email')->from('dgt_users')->where($where_cond)->get()->row_array();
            $branch_id = $user_data['branch_id'];
            $where_cond = array('entity'=>$branch_id);
            $promotion_temp_data = $this->db->select('subject,message')->from('dgt_promotion_template')->where($where_cond)->get()->row_array();

            $subject        =   $promotion_temp_data['subject'];
            $req_effectivedate = date('d-m-Y',strtotime($effectivedate));
            $message        =   $promotion_temp_data['message'];

            $logo_link = create_email_logo();
            $message        =   str_replace("{INVOICE_LOGO}",$logo_link,$message);
            $message        =   str_replace("{NEW_SALARY}",$newsalary,$message);
            $message        =   str_replace("{CURRENT_SALARY}",$currentsalary,$message);
            $message        =   str_replace("{EFFECTIVE_DATE}",$req_effectivedate,$message);
            $message        =   str_replace("{USER}",rtrim($user_data['fullname'],' '),$message);

            $pdf = array(
                'html' => $message,
                'title' => lang('invoice'),
                'author' => config_item('company_name'),
                'creator' => config_item('company_name'),
                'filename' => 'promotion_letter.pdf',
                'badge' => config_item('display_invoice_badge'),
            );

            $content        =   $this->applib->create_pdf_mail($pdf);
            $attach_file   =    FCPATH.'assets/uploads/promotion_letter.pdf';


            file_put_contents($attach_file, $content);
    
            $params['recipient'] = $user_data1['email'];
            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = $attach_file;
            Modules::run('fomailer/send_email',$params);
            unlink($attach_file);


            /*$config['upload_path']          = './uploads/promotion';
            $config['allowed_types']        = 'doc|DOC|docx|DOCX|p0df|PDF';
            $config['max_size']             = 1024;

            $this->load->library('upload', $config);
            $this->db->select('email');
            $send_mail = $this->db->get_where('dgt_users',array('id'=>$employee))->row_array();
            $send_mail = !empty($send_mail)?$send_mail:'';
            if($send_mail != '')
            {
                $recipient       = $send_mail['email'];
            }
            if ( $this->upload->do_upload('promotionletter')){
                $req_data   =   $this->upload->data();
                $params      = array(
                    'recipient' => $recipient,
                    'subject'   => 'Promotion Mail',
                    'message'   => 'Please check the attachement'
                );  
                $params['attached_file'] = $req_data['full_path'];
                $succ = Modules::run('fomailer/send_email',$params);
                unlink($req_data['full_path']);
            }*/
            $datas['result']='yes';
            $datas['status']='Promotion added successfully';
        }   
        else
        {
            $datas['result']='no';
            $datas['status']='Promotion added failed!';
        }
        
        echo json_encode($datas);

        exit;

    }



    public function update_promotion()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $designation=$this->input->post('designation');
        $grade=$this->input->post('grade');
        $promotionto=$this->input->post('promotionto');
        $promotiondate=date('Y-m-d',strtotime($this->input->post('promotiondate')));
        $department=$this->input->post('department');


        $currentsalary=$this->input->post('currentsalary');
        $newsalary=$this->input->post('newsalary');
        $effectivedate=date('Y-m-d',strtotime($this->input->post('effectivedate')));
        
        $data = array(
            'employee'=>$employee,
            'designation'=>$designation,
            'grade'=>$grade,
            'department'=>$department,
            'promotionto'=>$promotionto,
            'currentsalary'=>$currentsalary,
            'newsalary'=>$newsalary,
            'effectivedate'=>$effectivedate,
            'promotiondate'=>$promotiondate,
        );
        $this->db->where('id',$id);
        $this->db->update('promotion',$data);
        $result=($this->db->affected_rows()!= 1)? false:true;

        if($result==true) 
        {
            $datas['result']='yes';
            $datas['status']='Promotion update successfully';
        }   
        else
        {
            $datas['result']='no';
            $datas['status']='Promotion update failed!';
        }
        
        echo json_encode($datas);

        exit;

    }


   

    public function promotion_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->promotions->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    }

    function getEmployees(){
		extract($_GET);

		$this->db->select('U.id,AD.fullname,U.email')->from('dgt_users U')->join('account_details AD','U.id = AD.user_id')->where('U.status',1)->where('U.id!=',1);
		$this->db->where("(AD.fullname like '%".$term['term']."%' OR U.email like '%".$term['term']."%' )", NULL, FALSE);

		$users = $this->db->order_by('AD.fullname','asc')->get()->result_array();
		
		echo json_encode($users);
		exit;

	 }


}
