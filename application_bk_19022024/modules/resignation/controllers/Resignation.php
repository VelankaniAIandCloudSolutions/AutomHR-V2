<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Resignation extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App'));
        $this->load->model('resignationmodel','resignations');
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
                $this->template->title('Resignation'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('resignation');
                $data['role']       = $this->tank_auth->get_role_id();
				if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                    $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                }
                else{
                    $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
                }
                $this->template
					 ->set_layout('users')
					 ->build('resignation',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


      public function resignation_list()
    {
        $list = $this->resignations->get_datatables();
		// echo '<pre>';print_r($this->db->last_query());exit;
        $data = array();
        $no = $_POST['start'];
        $a=1;
         foreach ($list as $resignation) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $resignation->fullname;
            $row[] = $resignation->deptname;
            $row[] = $resignation->reason;
            $row[] = date('d M Y',strtotime($resignation->noticedate));
            $row[] = date('d M Y',strtotime($resignation->resignationdate));

            $row[]='<div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#" onclick="edit_resignation('.$resignation->id.')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                                <li><a href="#" onclick="delete_resignations('.$resignation->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                            </ul>
                        </div>';


           

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
        $data = $this->resignations->get_by_id($id);

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
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
        $reason=$this->input->post('reason');
		
        $login_user_details = $this->db->get_where('users',array('id'=>$employee))->row_array();
        // if($this->session->userdata('role_id') == 3){
            $resignation_notice = $this->db->get('resignation_notice')->row_array();
            if(!empty($resignation_notice)){
                if(!empty($resignation_notice['notice_days'])){
                 $noticedate=   date('Y-m-d',strtotime('+'.$resignation_notice['notice_days'].' days',strtotime($this->input->post('resignationdate'))));
                }
                if(!empty($resignation_notice['email_notification'])){
                    $user_id = explode(',', $resignation_notice['email_notification']);
                    foreach ($user_id as $key => $id) {
                         $user_details = $this->db->get_where('users',array('id'=>$id))->row_array();
                        if(!empty($user_details)){
                            $data = array(
                                'module' => 'resignation',
                                'module_field_id' => $user_details['id'],
                                'user' => $user_details['id'],
                                'activity' => 'Resignation requested by '.User::displayName($employee),  
                                'icon' => 'fa-plus',
                            );
                            App::Log($data);
                            $recipient[] =$user_details['email']; 
                        }
                        # code...
                    }
                }
            }else{
               $noticedate =  $resignationdate;              
               $repoting_detils = $this->db->get_where('users',array('id'=>$login_user_details['teamlead_id']))->row_array();
               $recipient[] = $repoting_detils['email'];
            }
			
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
            $data = array(
                'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
				'branch_id'=>$this->input->post('branch_id'),
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('resignation',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;
			
			if($result==true) 
            {   
                // if($this->session->userdata('role_id') == 3){
                $subject         = "Resignation Letter";
                $message         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Resignation</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi,</p>
                                                <p><b>Name : '.User::displayName($employee).'</b></p>  
                                                <p><b>Resignation Date </b> : '.$resignationdate.'</p>
                                                <p><b>Notice Date </b> : '.$noticedate.'</b></p>     
                                                <p><b>Reason </b> : '.$reason.'</p>                                      
                                                <br> 
                                                
                                                &nbsp;&nbsp;  
                                                
                                                <br>
                                                </big><br><br>Regards<br>The '.User::displayName($employee).' 
                                            </div>
                                     </div>';       
                    foreach ($recipient as $key => $u) 
                    {
                        
                        $params['recipient'] = $u;
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
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

    }


    public function update_resignation()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
         $reason=$this->input->post('reason');

        
            $data = array(
                // 'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->where('id',$id);
            $this->db->update('resignation',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Resignation update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Resignation update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function resignation_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->resignations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    } 
	 
    public function get_companies($branch_id){
        $companies = $this->db->select('*')->from('companies')->where('branch_id',$branch_id)->get()->result_array();
        $res_vals = array();
        $res_vals['companies']  = '<option value="">Select</option>';
        if(!empty($companies)){
            foreach($companies as $company1){
                $res_vals['companies'] .= '<option value="'.$company1['co_id'].'">'.$company1['company_name'].'</option>'; 
            }
        }
        $user_details = $this->db->select('*')->from('account_details')->where('branch_id',$branch_id)->get()->result_array();
        $res_vals['users']  = '';
        if(!empty($user_details)){
            foreach($user_details as $user_detail1){
                $res_vals['users'] .= '<option value="'.$user_detail1['user_id'].'">'.$user_detail1['fullname'].'</option>'; 
            }
        }
        echo json_encode($res_vals); exit;
    }



}
