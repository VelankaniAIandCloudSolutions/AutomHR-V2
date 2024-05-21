<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Interview_questions extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer','Jobs_model'));
        
        $this->load->library(array('form_validation')); 
		if(!App::is_access('menu_interview_questions'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		

    }

    public function index()
    {   
        
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('interview_questions').' - '.config_item('company_name'));
        $data['page'] = lang('interview_questions');
        $data['sub_page'] = lang('interview_questions');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
       
       // echo $this->db->last_query();exit; 
        $where['question_category.deleted_at'] =NULL;
        $data['categories'] = $this->Jobs_model->select_question_category($where);
        $data['questions_list'] = $this->Jobs_model->select_question_list(array('interview_questions.deleted_at'=>NULL));
        //echo $this->db->last_query();exit;      
        $this->template
                ->set_layout('users')
                ->build('questions_list', isset($data) ? $data : null);
    }

    function category(){

        $data['departments'] = $this->Jobs_model->select('departments'); /* to fetch job department*/
         if($this->uri->segment(3)!=""){
        $data['category'] = $category = $this->Jobs_model->select_row_array('question_category',array('id'=>$this->uri->segment(3)));
        $data['jobs_list'] = $this->Jobs_model->select('jobs',array('department_id'=>$category['dept_id']));
        $data['form_type'] = lang('edit_category');
        }else{
        $data['category'] = [];
        $data['form_type'] = lang('add_category');
        }
        $this->load->view('modal/category_form',$data);
    }
	

    function save_category(){

        if($this->input->post('submit')){
            $this->form_validation->set_rules('department','','trim|required');
            $this->form_validation->set_rules('job_id','','trim|required');
            $this->form_validation->set_rules('category_name','','trim|required');
          if($this->form_validation->run()){

            $data['dept_id'] = $this->input->post('department');
            $data['job_id'] =  $this->input->post('job_id');
            $data['category_name'] =  $this->input->post('category_name');
            $data['status'] = 1;
            if($this->input->post('category_id')!=""){
                $where['id'] = $this->input->post('category_id');
                $result = $this->Jobs_model->update('question_category',$data,$where);
            }else{
                $result = $this->Jobs_model->insert('question_category',$data);
            }
            if($result){
                $this->session->set_flashdata('tokbox_success', lang('category_saved_successfully'));
                redirect('interview_questions');
            }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
                redirect('interview_questions');
            }

          }else{
             $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
             redirect('interview_questions');
          }
        }
    }

    function delete_category(){
       
        if($this->input->post('delete')){
            $where['id'] = $this->input->post('delete_id');
            $upd['deleted_at'] = date('Y-m-d');
            $this->Jobs_model->update('question_category',$upd,$where);
             $this->session->set_flashdata('tokbox_success', lang('category_deleted_successfully'));
                redirect('interview_questions');
        }
    }

    function jobs_list(){
        $jobs_list = $this->Jobs_model->select_row('jobs',array('department_id'=>$this->input->post('department')));
        echo json_encode($jobs_list,true);
        die(); 
    }
   
	public function add_questions()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('add_questions').' - '.config_item('company_name'));
        $data['page'] = lang('interview_questions');
        $data['sub_page'] = lang('interview_questions');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
         
        if($this->input->post('save_question')){
         
            $this->form_validation->set_rules('department','','trim|required');
            $this->form_validation->set_rules('job_id','','trim|required');
            $this->form_validation->set_rules('category_id','','trim|required');
            $this->form_validation->set_rules('question','','trim|required');
            $this->form_validation->set_rules('question_type','','trim|required');
            if($this->input->post('question_type') ==1){
            $this->form_validation->set_rules('option_a','','trim|required');
            $this->form_validation->set_rules('option_b','','trim|required');
            $this->form_validation->set_rules('option_c','','trim|required');
            $this->form_validation->set_rules('option_d','','trim|required');
            $this->form_validation->set_rules('correct_answer','','trim|required');
            }else{
            $this->form_validation->set_rules('answer_explanation','','trim|required');
            }
          if($this->form_validation->run()){

            $add_data['question'] = $this->input->post('question');
            $add_data['dept_id'] = $this->input->post('department');
            $add_data['job_id'] = $this->input->post('job_id');
            $add_data['category_id'] = $this->input->post('category_id');
            $add_data['question_type'] = $this->input->post('question_type');
            if($this->input->post('question_type')==1){
            $add_data['a'] = $this->input->post('option_a');
            $add_data['b'] = $this->input->post('option_b');
            $add_data['c'] = $this->input->post('option_c');
            $add_data['d'] = $this->input->post('option_d');
            $add_data['answer'] = $this->input->post('correct_answer');
            }

            $add_data['status'] = $this->input->post('qst_status');
            $add_data['created_by'] = $this->session->userdata('user_id');

            
            if(!empty($_FILES['question_image']['name'])){

                if($this->Jobs_model->image_upload('question_image')){
                    $files_detail = $this->Jobs_model->image_upload('question_image');
                    $add_data['question_image']  = $files_detail['file_name'];
                }
             }
             if($this->input->post('answer_explanation')!=""){
                 $add_data['answer_explanation'] = $this->input->post('answer_explanation');
             }
             $insert = $this->Jobs_model->insert('interview_questions',$add_data);
             if($insert){
                $this->session->set_flashdata('tokbox_success', lang('question_added_successfully'));
                redirect('interview_questions/add_questions');
             }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
                redirect('interview_questions/add_questions');
             }

          }else{
                $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
                redirect('interview_questions/add_questions');
          }
        }

       $data['departments'] = $this->Jobs_model->select('departments'); /* to fetch job department*/
        $this->template
                ->set_layout('users')
                ->build('add_questions', isset($data) ? $data : null);
    }	
    function delete_question(){
       
        if($this->input->post('delete')){
            $where['id'] = $this->input->post('delete_id');
            $upd['deleted_at'] = date('Y-m-d H:i:s');
            $this->Jobs_model->update('interview_questions',$upd,$where);
             $this->session->set_flashdata('tokbox_success', lang('question_deleted_successfully'));
                redirect('interview_questions');
        }
    }
    function category_list(){
        $where['dept_id'] =  $this->input->post('department');
        $where['job_id'] =  $this->input->post('job_id');
        $category_list = $this->Jobs_model->select_row('question_category',$where); /* question category based on department and jobs*/
        echo json_encode($category_list,true);
        die(); 
    }

    function question_status(){
        $where['id'] = $this->uri->segment(4);
        $upd['status'] = $this->uri->segment(3);
        $status_update = $this->Jobs_model->update('interview_questions',$upd,$where); 
       // echo $this->db->last_query();exit; 
        $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
        redirect('interview_questions');
    }
	public function edit_questions()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('recruiting_process').' - '.config_item('company_name'));
        $data['page'] = lang('recruiting');
        $data['sub_page'] = lang('interview_questions');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();


        if($this->input->post('edit_question')){



            $this->form_validation->set_rules('department','','trim|required');
            $this->form_validation->set_rules('job_id','','trim|required');
            $this->form_validation->set_rules('category_id','','trim|required');
            $this->form_validation->set_rules('question','','trim|required');
            $this->form_validation->set_rules('question_type','','trim|required');
            if($this->input->post('question_type') ==1){
            $this->form_validation->set_rules('option_a','','trim|required');
            $this->form_validation->set_rules('option_b','','trim|required');
            $this->form_validation->set_rules('option_c','','trim|required');
            $this->form_validation->set_rules('option_d','','trim|required');
            $this->form_validation->set_rules('correct_answer','','trim|required');
            }else{
                $this->form_validation->set_rules('answer_explanation','','trim|required');
            }
          if($this->form_validation->run()){

            $add_data['question'] = $this->input->post('question');
            $add_data['dept_id'] = $this->input->post('department');
            $add_data['job_id'] = $this->input->post('job_id');
            $add_data['category_id'] = $this->input->post('category_id');
            $add_data['question_type'] = $this->input->post('question_type');
            if($this->input->post('question_type')==1){
            $add_data['a'] = $this->input->post('option_a');
            $add_data['b'] = $this->input->post('option_b');
            $add_data['c'] = $this->input->post('option_c');
            $add_data['d'] = $this->input->post('option_d');
            $add_data['answer'] = $this->input->post('correct_answer');
            }
            $add_data['status'] = $this->input->post('qst_status');

            if(!empty($_FILES['question_image']['name'])){

                if($this->Jobs_model->image_upload('question_image')){
                    $files_detail = $this->Jobs_model->image_upload('question_image');
                    $add_data['question_image']  = $files_detail['file_name'];
                }
             }
             if($this->input->post('answer_explanation')!=""){
                 $add_data['answer_explanation'] = $this->input->post('answer_explanation');
             }
             $where['id'] = $this->input->post('question_id');
             $update = $this->Jobs_model->update('interview_questions',$add_data,$where);
             if($update){
                $this->session->set_flashdata('tokbox_success', lang('question_updated_successfully'));
                redirect('interview_questions/edit_questions/'.$this->uri->segment(3));
             }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
                redirect('interview_questions/edit_questions.'.$this->uri->segment(3));
             }

          }else{
                $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
                redirect('interview_questions/edit_questions/'.$this->uri->segment(3));
          }
            
        }

        $data['question'] = $question = $this->Jobs_model->select_row_array('interview_questions',array('id'=>$this->uri->segment(3)));
        $data['departments'] = $this->Jobs_model->select('departments'); /* to fetch  department*/
        $data['jobs_list'] = $this->Jobs_model->select('jobs',array('id'=>$question['job_id'])); /* to fetch job */
        $data['categories'] = $this->Jobs_model->select('question_category',array('job_id'=>$question['job_id'],'dept_id'=>$question['dept_id'])); /* to fetch job */

      
        $this->template
                ->set_layout('users')
                ->build('edit_questions', isset($data) ? $data : null);
    }
	

}
/* End of file interview questions.php */
