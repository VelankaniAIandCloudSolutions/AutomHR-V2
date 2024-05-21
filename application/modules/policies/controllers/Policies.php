<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Policies extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Client'));
        $this->load->model('policiesmodel','policies');
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        App::module_access('menu_policies');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }
    
    function index()
    {
        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Policies'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['datatables'] = true;
                $data['page']       = lang('policies');
                $data['role']       = $this->tank_auth->get_role_id();
                if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
                    $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
                }
                else{
                    $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
                }
                $this->template
                     ->set_layout('users')
                     ->build('policies',isset($data) ? $data : NULL);
        }else{
           redirect('');    
        }
     } 


     public function policies_list()
    {
        $list = $this->policies->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
         foreach ($list as $policy) {

            $departments=explode(',', $policy->department);

            $department=array();
            for ($i=0; $i <count($departments) ; $i++) { 

                if($departments[$i]==00)
                {
                    $department[]='All Departments';
                }
                else
                {
                    $department[]=$this->db->select('deptname')->where('deptid',$departments[$i])->get('departments')->row()->deptname;
                }

                
               
            }

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $policy->policyname;
            $row[] = App::get_entity_name($policy->branch_id);
            $row[] = implode(', ', $department);
            
            $des_length = strlen($policy->description);
            if($des_length > 200)
            {
                $row[] = substr($policy->description, 0, 200).'<a title="full view" href="#" onclick="full_view_policy('.$policy->id.')"> [Read More]</a>';
                
            }
            else{
                $row[] = $policy->description; 
            }
            $row[] = date('d M Y',strtotime($policy->posted_date));

            $html='<div class="dropdown dropdown-action text-right">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="'.base_url().'policies/download/'.$policy->id.'"><i class="fa fa-download m-r-5"></i> Download</a></li>';
                                if($this->tank_auth->get_role_id()!=3)
                                {
                                      $html.='<li><a href="#" onclick="edit_policies('.$policy->id.')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                                    <li><a href="#" onclick="delete_policies('.$policy->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>';
                                }

                               
                             $html.='</ul>
                        </div>';

            $row[]=$html;
           

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->policies->count_all(),
            "recordsFiltered" => $this->policies->count_filtered(),
            "data" => $data,
            ); 
//output to json format
        echo json_encode($output);
        exit;
    }

    public function download($policy_id)
    {
        $policyfile=$this->db->select('policy_file')->where('id',$policy_id)->get('policies')->row()->policy_file;

           $this->load->helper('download');
            force_download($policyfile, NULL);
    }


    public function policies_edit($id)
    {
        $data = $this->policies->get_by_id($id);

        echo json_encode($data);
        exit;
    }

     public function policies_details()
    {
      
      $id=$this->input->post('id');

        $policy_file=$this->db->select('policy_file')->where('id',$id)->get('policies')->row()->policy_file;

       
        $html='';
        
            if(!empty($policy_file))
            {
               $ext = pathinfo($policy_file, PATHINFO_EXTENSION);

               if($ext=='jpg' || $ext=='png' || $ext=='jpeg' || $ext=='JPG' || $ext=='PNG' || $ext=='JPEG')
               {
                  $html='<img src="'.base_url().$policy_file.'" class="img-thumbnail" alt="Cinque Terre" width="304" height="236">';
               }
               else
               {
                 $html='<a href="'.base_url().$policy_file.'"><i class="fa fa-download"></i> Download</a>';
               }
               
               
            }

            
                    echo $html;
                    exit;

    }




    public function add_policies()
    {
        $policyname=$this->input->post('policyname');
        $description=$this->input->post('description');
        $department=$this->input->post('department');

        $config['upload_path'] = './assets/uploads/policy/';
        $config['allowed_types'] = '*';
        $config['max_filename'] = '*';
        $config['overwrite'] =TRUE;



        if (isset($_FILES['policy_upload']['name']) && !empty($_FILES['policy_upload']['name']))
         {
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('policy_upload')) {
                 $datas['uplo']='no';
                 $datas['status']=$this->upload->display_errors();
                 echo json_encode($datas);
                 exit; 
            } else {
                $data = $this->upload->data();
                $policy_file='assets/uploads/policy/'.$data['file_name'];      
            }
        } 
        else
         {
            $policy_file='';
         }

         if (empty($_POST['branch_id'])) {
            $_POST['branch_id'] = $this->session->userdata('branch_id');
        }

            $data = array(
                'policyname'=>$policyname,
                'description'=>$description,
                'branch_id'=> $_POST['branch_id'],
                'department'=>implode(',', $department),
                'policy_file'=>$policy_file,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('policies',$data);
            $policy_id = $this->db->insert_id();
            $dept_id = $this->db->get_where('dgt_policies',array('id'=> $policy_id))->row_array();
            $department_id = $dept_id['department'];


            $log_data = array(
                    'module' => 'policies',
                    'module_field_id' => $policy_id,
                    'user' => User::get_id(),
                    'activity' => 'Created new policy',
                    'icon' => 'fa-coffee',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $_POST['policyname'],
                    'value2' => $department_id
                );
            App::Log($log_data);

            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Policies added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Policies added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_policies()
    {

        $id=$this->input->post('id');
        $policyname=$this->input->post('policyname');
        $description=$this->input->post('description');
        $department=$this->input->post('department');
        $policy_files=$this->input->post('policy_files');

        $config['upload_path'] = './assets/uploads/policy/';
        $config['allowed_types'] = '*';
        $config['max_filename'] = '*';
        $config['overwrite'] =TRUE;



        if (isset($_FILES['policy_upload']['name']) && !empty($_FILES['policy_upload']['name']))
         {


            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('policy_upload')) {
                 $datas['uplo']='no';
                 $datas['status']=$this->upload->display_errors();
                 echo json_encode($datas);
                 
                 exit; 
            } else {

                $data = $this->upload->data();
                $policy_file='assets/uploads/policy/'.$data['file_name'];

                          
            }
               
        } 
        else
         {
            $policy_file=$policy_files;
         }

        

            $data = array(
                'policyname'=>$policyname,
                'description'=>$description,
                'department'=>implode(',', $department),
                'policy_file'=>$policy_file,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            if (User::is_admin()) {
                $data['branch_id'] = $_POST['branch_id'];
            }
            $this->db->where('id',$id);
            $this->db->update('policies',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            $dept_id = $this->db->get_where('dgt_policies',array('id'=> $id))->row_array();
            $department_id = $dept_id['department'];


            $log_data = array(
                    'module' => 'policies',
                    'module_field_id' => $id,
                    'user' => User::get_id(),
                    'activity' => 'Updated policy',
                    'icon' => 'fa-coffee',
                    'branch_id' => $this->session->userdata('branch_id'),
                    'value1' => $_POST['policyname'],
                    'value2' => $department_id
                );
            App::Log($log_data);


            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Policies update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Policies update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function policies_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->policies->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    }




     
     
     
    




}
