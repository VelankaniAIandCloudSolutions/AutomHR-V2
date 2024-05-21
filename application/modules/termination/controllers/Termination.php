<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Termination extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array('App'));
        $this->load->model('terminationmodel','terminations');
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
                $this->template->title('Termination'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('termination');
                $data['role']       = $this->tank_auth->get_role_id();
				if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                    $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                }
                else{
                    $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
                }
                $this->template
					 ->set_layout('users')
					 ->build('termination',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


     public function add_termination_type()
    {
        $termination_type=$this->input->post('termination_type');
               
            $data = array(
                'termination_type'=>$termination_type,
                 );
            $this->db->insert('termination_type',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination type added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination type added failed!';
            }
        
        echo json_encode($datas);

        exit;

    } 

    public function get_termination()
    {
    	$query=$this->db->get('termination_type');
        $result= $query->result();
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->id;
			$data['label']=$r->termination_type;
			$json[]=$data;
			
			
		}
		echo json_encode($json);
		exit;
    }

       public function termination_list()
    {
        $list = $this->terminations->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
         foreach ($list as $termination) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $termination->fullname;
            $row[] = $termination->deptname;
            $row[] = $termination->terminationtypes;
            $row[] = date('d M Y',strtotime($termination->terminationdate));
            $row[] = $termination->reason;
            $row[] = date('d M Y',strtotime($termination->lastdate));

            

            /*$row[]='<div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#" onclick="edit_termination('.$termination->id.')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                                <li><a href="#" onclick="delete_terminations('.$termination->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                            </ul>
                        </div>';*/

            $row[]='<div class="dropdown dropdown-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <ul class="dropdown-menu pull-right">
                <li><a href="#" onclick="delete_terminations('.$termination->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
            </ul>
        </div>';


           

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->terminations->count_all(),
            "recordsFiltered" => $this->terminations->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }


    public function termination_edit($id)
    {
        $data = $this->terminations->get_by_id($id);

        echo json_encode($data);
        exit;
    }

   




    public function add_termination()
    {

    	
        $employee=$this->input->post('employee');
        $termination_type=$this->input->post('termination_type');
        $lastdate=date('Y-m-d',strtotime($this->input->post('lastdate')));
        $terminationdate=date('Y-m-d',strtotime($this->input->post('terminationdate')));
        $reason=$this->input->post('reason');
       
       
            $data = array(
                'employee'=>$employee,
                'lastdate'=>$lastdate,
                'termination_type'=>$termination_type,
                'terminationdate'=>$terminationdate,
                // 'branch_id'=>$this->session->userdata('branch_id'),
                'branch_id'=>$this->input->post('branch_id'),
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('termination',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {

                

                $where_cond = array('user_id'=>$employee);
            $user_data = $this->db->select('branch_id,fullname')->from('dgt_account_details')->where($where_cond)->get()->row_array();
            $where_cond = array('id'=>$employee);
            $user_data1 = $this->db->select('email')->from('dgt_users')->where($where_cond)->get()->row_array();
            $branch_id = $user_data['branch_id'];
            $where_cond = array('entity'=>$branch_id);
            $termination_temp_data = $this->db->select('subject,message')->from('dgt_termination_template')->where($where_cond)->get()->row_array();
            

            $subject        =   $termination_temp_data['subject'];
            $message        =   $termination_temp_data['message'];

           /* $upd_data = array(
                'status'=>0,
            );
            $this->db->where('id',$employee);
            $this->db->update('dgt_users',$upd_data);*/
            
            $logo_link = create_email_logo();
            $get_termination_type = $this->db->select('termination_type')->from('dgt_termination_type')->where(array('id'=>$termination_type))->get()->row_array();
            $message        =   str_replace("{INVOICE_LOGO}",$logo_link,$message);
            $message        =   str_replace("{TERMINATE_TYPE}",$get_termination_type['termination_type'],$message);
            $message        =   str_replace("{TERMINATE_REASON}",$reason,$message);
            $message        =   str_replace("{LAST_DAY}",date('d-m-Y',strtotime($lastdate)),$message);
            $message        =   str_replace("{TERMINATE_DATE}",date('d-m-Y',strtotime($terminationdate)),$message);
            $message        =   str_replace("{USER}",rtrim($user_data['fullname'],' '),$message);

            $pdf = array(
                'html' => $message,
                'title' => lang('invoice'),
                'author' => config_item('company_name'),
                'creator' => config_item('company_name'),
                'filename' => 'termination_letter.pdf',
                'badge' => config_item('display_invoice_badge'),
            );

            $content        =   $this->applib->create_pdf_mail($pdf);
            $attach_file   =    FCPATH.'assets/uploads/termination_letter.pdf';


            file_put_contents($attach_file, $content);
    
            $params['recipient'] = $user_data1['email'];
            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = $attach_file;
            Modules::run('fomailer/send_email',$params);
            unlink($attach_file);
               /* $config['upload_path']          = './uploads/termination';
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
                if ( $this->upload->do_upload('terminationletter')){
                    $req_data   =   $this->upload->data();
                    $params      = array(
                        'recipient' => $recipient,
                        'subject'   => 'Termination Mail',
                        'message'   => 'Please check the attachement'
                    );  
                    $params['attached_file'] = $req_data['full_path'];
                    $succ = Modules::run('fomailer/send_email',$params);
                    unlink($req_data['full_path']);
                }*/

                $datas['result']='yes';
                $datas['status']='Termination added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_termination()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $termination_type=$this->input->post('termination_type');
        $lastdate=date('Y-m-d',strtotime($this->input->post('lastdate')));
        $terminationdate=date('Y-m-d',strtotime($this->input->post('terminationdate')));
        $reason=$this->input->post('reason');

        
            $data = array(
                // 'employee'=>$employee,
                'lastdate'=>$lastdate,
                'termination_type'=>$termination_type,
                'terminationdate'=>$terminationdate,
                'reason'=>$reason,
                );
            $this->db->where('id',$id);
            $this->db->update('termination',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function termination_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->terminations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    } 




	 



}
