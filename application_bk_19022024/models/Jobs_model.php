<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobs_model extends CI_Model
{


    function __construct(){
        parent::__construct();
      
    }

    public function insert($table,$data){
    	$this->db->insert($table,$data);		 
 		return $this->db->insert_id();
    }
    public function update($table,$data,$where){
    	$this->db->update($table,$data,$where);		 
 		return true;
    }
    public function delete($table,$where){
    	$this->db->delete($table,$where);
    	return true;
    }

   public function select($table,$where=NULL){
    	$this->db->select('*');
    	$this->db->from($table);
    	if($where!=NULL){
    	$this->db->where($where);	
    	} 
    	$query = $this->db->get();
    	return $query->result();
    }
     
    public function select_check($table,$where=NULL){
        $this->db->select('*');
        $this->db->from($table);
        if($where!=NULL){
        $this->db->where($where);   
        }
        $query = $this->db->get();
        return $query;
    }

    public function select_numrows($table,$where=NULL){
        $this->db->select('id');
        $this->db->from($table);
        if($where!=NULL){
        $this->db->where($where);   
        }
        $query = $this->db->get();
        return $query;
    }
       public function select_row($table,$where=NULL){
    	$this->db->select('*');
    	$this->db->from($table);
    	if($where!=NULL){
    	$this->db->where($where);	
    	}
    	$query = $this->db->get();
    	return $query->result_array();
    }

    public function select_row_array($table,$where=NULL){
        $this->db->select('*');
        $this->db->from($table);
        if($where!=NULL){
        $this->db->where($where);   
        }
        $query = $this->db->get();
        return $query->row_array();
    }
    public function select_jobs_list($where=NULL){
    	$this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description');
    	$this->db->from('jobs');
    	$this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('designation','designation.id = jobs.position_id');
    	$this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
    	if($where!=NULL){
    		$this->db->where($where);
    	}
		$query = $this->db->get();
    	return $query->result();	
    }
    public function select_visited_jobs($where=NULL){
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description,cjob.user_job_status');
        $this->db->from('candidate_visited_jobs');
        $this->db->join('jobs','jobs.id=candidate_visited_jobs.job_id');
        $this->db->join('candidate_job_status cjob','cjob.candidate_id=candidate_visited_jobs.candidate_id and dgt_candidate_visited_jobs.job_id=cjob.job_id');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
        // $this->db->where('cjob.job_id', "dgt_candidate_visited_jobs.job_id");
        // $this->db->group_by('candidate_visited_jobs.job_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();

    }
     public function select_visited_jobs_filter($where=NULL,$like=NULL){ //New Code
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description,cjob.user_job_status');
        $this->db->from('candidate_visited_jobs');
        $this->db->join('jobs','jobs.id=candidate_visited_jobs.job_id');
        $this->db->join('candidate_job_status cjob','cjob.candidate_id=candidate_visited_jobs.candidate_id and dgt_candidate_visited_jobs.job_id=cjob.job_id');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
         $this->db->join('experience_level','experience_level.id=jobs.experience_level_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        if($like!=NULL){
            $this->db->like('job_title',$like);
        }
        $this->db->group_by('jobs.id');
           // $this->db->where($where);        }
        $query = $this->db->get();
        return $query->result();

    }
    function select_jobs_status_list($where=NULL){ //New Code
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description,candidate_job_status.id as job_status_id,candidate_job_status.user_job_status');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id=candidate_job_status.job_id');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        $this->db->group_by('jobs.id');
        $query = $this->db->get();
        return $query->result();
    }

     

       function select_jobs_status_list_filter($where=NULL,$like=NULL){
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description,candidate_job_status.id as job_status_id,candidate_job_status.user_job_status');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id=candidate_job_status.job_id');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
        $this->db->join('experience_level','experience_level.id=jobs.experience_level_id');
        if($where!=NULL){
            $this->db->where($where);
        }
         if($like!=NULL){
            $this->db->like('job_title',$like);
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function select_counts($where = NULL){
        $this->db->select('count(job_id) as count,status');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id = candidate_job_status.job_id');
        if($where!=NULL){
            $this->db->where($where);    
        }        
        $this->db->group_by('candidate_job_status.status');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_counts($table,$join_table=NULL,$join_cond=NULL,$where=NULL){
        $this->db->select('count(*) as count');
        $this->db->from($table);
        if(!empty($join_table) && !empty($join_cond)){
            $this->db->join($join_table,$join_cond);
        }
        if($where!=NULL){
            $this->db->where($where);    
        }        
        $query = $this->db->get();
        return $query->row_array();

    }

     function select_jobs_list_filter($where=NULL,$like=NULL){
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description');
        $this->db->from('jobs');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
        $this->db->join('experience_level','experience_level.id=jobs.experience_level_id','left');
        if($where!=NULL){
            $this->db->where($where);
        }
        if($like!=NULL){
            $this->db->or_like('job_title',$like);
        }
        $query = $this->db->get();
        return $query->result();
    }
     function get_latest_jobs($limit=NULL){
    	$this->db->select("id,job_title,timestampdiff(DAY, created_at, now()) as days,timestampdiff(HOUR, created_at, now()) as hour");
    	$this->db->from('jobs');
         $this->db->where('deleted_at',NULL);
        if($limit!=NULL){
            $this->db->limit($limit);
        }
        $this->db->order_by('start_date','desc');
    	$query = $this->db->get();
    	return $query->result_array();
    }
      function select_jobs_detail($where=NULL){
    	$this->db->select('jobs.id,jobs.job_title,jobs.position_id,departments.deptid,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description,jobs.job_image');
    	$this->db->from('jobs');
    	$this->db->join('departments','departments.deptid = jobs.department_id');
    	$this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
    	if($where!=NULL){
    		$this->db->where($where);
    		
    	}
    	$query = $this->db->get();
    	return $query->row_array();
    }

     function select_where_in($table,$array,$field){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where_in($field,$array);
        $query = $this->db->get();
        return $query->result_array();
    }

     function select_candidate_details($where){
        $this->db->select('a.id,a.first_name,a.last_name,a.email,b.address,b.country,b.state,b.city,b.pincode,b.phone_number,b.web_address,b.school_name,b.passed_out_year,b.major_subject,b.degree,b.gpa,b.skills,c.file_name,b.experience_details,a.job_category_id,a.position_type_id');
        $this->db->from('registered_candidates as a');
        $this->db->join('candidate_additional_information as b','b.candidate_id=a.id','left');
        $this->db->join('candidate_files as c','c.candidate_id=a.id','left');
        if($where!=NULL){
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->row_array();

    }

    function select_resumes_list($where=NULL){
           $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobs.job_location,jobs.no_of_vacancy,jobs.experience,jobs.age,jobs.salary_from,jobs.salary_to,jobtypes.job_type,jobs.job_status,jobs.start_date,jobs.expired_date,jobs.description,candidate_job_status.id as job_status_id,candidate_job_status.status,candidate_files.file_name,registered_candidates.first_name,registered_candidates.last_name,candidate_job_status.user_job_status,registered_candidates.id as candidate_id');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id=candidate_job_status.job_id');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('jobtypes','jobtypes.id=jobs.job_type_id');
        $this->db->join('candidate_files','candidate_files.candidate_id = candidate_job_status.candidate_id');
        $this->db->join('registered_candidates','registered_candidates.id = candidate_job_status.candidate_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        // $this->db->group_by('candidate_files.candidate_id');  //Code removed to mari
        $query = $this->db->get();
        return $query->result();
    }


    function select_shortlist_candidates($where = NULL,$where_not=NULL){
      
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,candidate_job_status.id as job_status_id,registered_candidates.first_name,registered_candidates.last_name,candidate_job_status.user_job_status,registered_candidates.id as candidate_id');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id=candidate_job_status.job_id');
        $this->db->join('departments','departments.deptid = jobs.department_id');
        $this->db->join('registered_candidates','registered_candidates.id = candidate_job_status.candidate_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        if($where_not!=NULL){
            $this->db->where_not_in('candidate_job_status.user_job_status',$where_not);
        }
        $query = $this->db->get();
        return $query->result();
    }

   
    function select_applications($where=NULL){
        $this->db->select('count(candidate_id) as count,job_id');
        $this->db->from('candidate_job_status');
        if($where!=NULL){
            $this->db->where($where);
        }
        $this->db->group_by('job_id');
        $query = $this->db->get();
        return $query->result();
    }

    function select_question_category($where = NULL){
        $this->db->select('question_category.id,question_category.category_name,jobs.job_title,departments.deptname');
        $this->db->from('question_category');
        $this->db->join('jobs','jobs.id=question_category.job_id');
        $this->db->join('departments','departments.deptid = question_category.dept_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();

    }

    function image_upload($file_name,$path = NULL){

            if($path != NULL){
                $config['upload_path'] = './images/'.$path.'/';
            }else{
                $config['upload_path'] = './images/question/';
            }
         
         $config['allowed_types'] = '*';
         $config['max_size']      = 0;

         $this->load->library('upload', $config);
         $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            $data = array('image_metadata' => $this->upload->data());
           return $this->upload->data();
        }
    }


    function upload($file_name){


         $config['upload_path'] = './images/resume/';
         $config['allowed_types'] = 'pdf|docx|doc';
         $config['max_size']      = 0;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);

        
        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
           
            return false;
        } else {
            $data = array('image_metadata' => $this->upload->data());
           return $this->upload->data();
        }
    }

    function select_question_list($where=NULL){
        $this->db->select('interview_questions.*,departments.deptname,jobs.job_title,question_category.category_name');
        $this->db->from('interview_questions');
        $this->db->join('departments','departments.deptid=interview_questions.dept_id');
        $this->db->join('jobs','jobs.id=interview_questions.job_id');
        $this->db->join('question_category','question_category.id=interview_questions.category_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function select_interview_jobs($where=NULL,$where_not=NULL){
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,departments.deptid,candidate_job_status.candidate_id');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id=candidate_job_status.job_id');
        $this->db->join('departments','departments.deptid=jobs.department_id');
        if($where!=NULL){
            $this->db->where($where);
        }
         if($where_not!=NULL){
            $this->db->where_not_in('candidate_job_status.user_job_status',$where_not);
        }
        $this->db->group_by('jobs.id');
        $query = $this->db->get();
        return $query->result();

    }

    function get_apps_completed_user(){
        $this->db->select('sum(answer_status) as total_mark,jobs.job_title,departments.deptname,jobs.id as job_id,user_id,registered_candidates.first_name,registered_candidates.last_name,user_job_status,question_category.category_name,question_category.id as category_id');
        $this->db->from('apptitude_interview_results');
        $this->db->join('jobs','jobs.id=apptitude_interview_results.job_id');
        $this->db->join('departments','departments.deptid=jobs.department_id');
        $this->db->join('question_category','question_category.id=apptitude_interview_results.category_id');
        $this->db->join('registered_candidates','registered_candidates.id=apptitude_interview_results.user_id');
        $this->db->join('candidate_job_status','candidate_job_status.job_id=apptitude_interview_results.job_id and candidate_job_status.candidate_id = apptitude_interview_results.user_id','left');
        $this->db->group_by(array('job_id','user_id','category_id'));
        $query = $this->db->get();
        return $query->result();
    }

    function select_users_interview($where=NULL,$where_not=NULL){
         $this->db->select('jobs.id,jobs.job_title,departments.deptname,departments.deptid,candidate_job_status.candidate_id,registered_candidates.first_name,registered_candidates.last_name,schedule_timings.schedule_date,schedule_timings.available_timing,user_selected_timing');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id=candidate_job_status.job_id');
        $this->db->join('departments','departments.deptid=jobs.department_id');
        $this->db->join('registered_candidates','registered_candidates.id=candidate_job_status.candidate_id');
        $this->db->join('schedule_timings','schedule_timings.user_id=candidate_job_status.candidate_id and schedule_timings.job_id=jobs.id','left');
        if($where!=NULL){
            $this->db->where($where);
        }
         if($where_not!=NULL){
            $this->db->where_not_in('candidate_job_status.user_job_status',$where_not);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function time_diffence(){
        $h = 0; $hour=0;
        while ($h < 24 && $hour < 24) {
        $add_time = $hour+1;


        $key = date('H:i', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
        $key_plus = date('H:i', strtotime(date('Y-m-d') . ' + ' . $add_time . ' hours'));

        $value = date('h:i A', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
        $value_plus = date('h:i A', strtotime(date('Y-m-d') . ' + ' . $add_time. ' hours'));
        $formatter[$key.'-'.$key_plus] = $value.'-'.$value_plus;
        $h++; $hour++;
        }
        return $formatter;  
    }

    function select_candidates(){
        $this->db->select('registered_candidates.id,registered_candidates.first_name,registered_candidates.last_name,registered_candidates.email,registered_candidates.created_at,candidate_additional_information.phone_number');
        $this->db->from('registered_candidates');
        $this->db->join('candidate_additional_information','candidate_additional_information.candidate_id=registered_candidates.id','left');
        $query = $this->db->get();
        return $query->result();
    }

    function select_questions_answers($where = NULL){
        $this->db->select('a.id as aptitude_id,a.user_id,a.job_id,a.category_id,a.question_id,a.user_answer,a.question_type,a.answer_status,b.dept_id,b.question,b.a,b.b,b.c,b.d,b.answer,b.answer_explanation,b.question_image');
        $this->db->from('apptitude_interview_results as a');
        $this->db->join('interview_questions as b','b.id=a.question_id');
        if($where!=NULL){
        $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function select_offered_jobs($where = NULL)
    {
        $this->db->select('jobs.id,jobs.job_title,departments.deptname,jobtypes.job_type,candidate_job_status.user_job_status,candidate_job_status.candidate_id');
        $this->db->from('candidate_job_status');
        $this->db->join('jobs','jobs.id = candidate_job_status.job_id');
        $this->db->join('jobtypes','jobtypes.id = jobs.job_type_id');
        $this->db->join('departments','departments.deptid=jobs.department_id');
        if($where!=NULL){
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }
    

    function select_offered_count($where =NULL)
    {
        $this->db->select('count(id) as offered_count,user_job_status');
        $this->db->from('candidate_job_status');
        if($where!=NULL){
        $this->db->where($where);    
        }
        $this->db->where_not_in('user_job_status',array(9,10));
        $this->db->group_by(array('user_job_status'));
        $query = $this->db->get();
        return $query->result();
        
    }

    function select_month_wise_details($where=NULL){
        $this->db->select('MONTH(created_at) as month,job_id,candidate_id,user_job_status,id');
        $this->db->from('candidate_job_status');
        if($where!=NULL){
        $this->db->where($where);    
        }
         $query = $this->db->get();
        return $query->result();
        
    }

    function upload_using_url($file_url) {
        $config['upload_path'] = './images/resume/';
        $config['allowed_types'] = 'pdf|docx|doc';
        $config['max_size'] = 0;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $file_name= basename($file_url);
        $file_content = file_get_contents($file_url);
        if ($file_content) {
            if (!$this->upload->do_upload($file_name)) {
                $error = array('error' => $this->upload->display_errors());
                return false;
            } else {
                $data = array('image_metadata' => $this->upload->data());
                return $this->upload->data();
            }
        } else {
            return false;
        }
    }

    public function manager_list()
    {
        $this->db->select("users.id as user_id, dad.fullname, dad.emp_code");
        $this->db->from("users");
        $this->db->join("dgt_account_details as dad","dad.user_id = users.id","inner");
        $this->db->where("users.user_type","48");
        $this->db->where("users.status","1");
        $query = $this->db->get();
        return $query->result();
    }
    public function hr_list()
    {
        $this->db->select("users.id as user_id, dad.fullname, dad.emp_code");
        $this->db->from("users");
        $this->db->join("dgt_account_details as dad","dad.user_id = users.id","inner");
        $this->db->where("users.user_type","56");
        $this->db->where("users.status","1");
        $query = $this->db->get();
        return $query->result();
    }

    function jobTitleDisplay($id){
        $this->db->select("job_title");
		$result = $this->db->where('id',$id)->get('dgt_jobs')->row();
        if(!empty($result))
        {
            return $result->job_title;
        }
    }
}

/* End of Jobs model.php */
