<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_knowledge extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
        $this->load->helper('security');
        if(!App::is_access('menu_knowledgebase'))
        {
            $this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        }
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('ad_knowledge').' - '.config_item('company_name'));
        $data['form'] = TRUE;
        $data['page'] = lang('ad_knowledge');
        // $data['categories'] = $this->db->select('*')->order_by('id', 'DESC')->get_where('category', array('status ='=>'1','subdomain_id'=>$this->session->userdata('subdomain_id')))->result();
        $this->db->order_by('id','DESC');
        $this->db->where('status','1');
        $data['categories'] = $this->db->get('category')->result();
        // echo $this->db->last_query(); exit;
        $data['knowledge_details'] = $this->db->select('*')->get_where('ad_knowledge', array('status!='=>'2'))->result();
        // date_default_timezone_set('Asia/tokyo'); 
        // echo date("Y-m-d H:i:s"); exit;
        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches'] = $branches  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();
       }
       else{
           $branches = $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
       }
        $this->template
            ->set_layout('users')
            ->build('ad_knowledge',isset($data) ? $data : NULL);
    }

   public function ad_knowledge_view($id)
   {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('ad_knowledge').' - '.config_item('company_name'));
        $data['form'] = TRUE;
        $data['page'] = lang('ad_knowledge');
        $data['id'] = $id;
        
        if($this->uri->segment(2) == 'ad_knowledge_view') {
            $views = $this->db->get_where('ad_knowledge_comments', array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id'),'views'=>'0'))->row();
           if($views) {
                $data = array(
                    'views'=>'1'
                );
                App::update('ad_knowledge_comments',array('id'=>$views->id), $data);
            } 

            $new_views = $this->db->get_where('ad_knowledge_comments', array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id')))->row();

            if(empty($new_views)) {
                $views_data = array(
                    'knowledge_id' => $id,
                    'user_id'   => $this->session->userdata('user_id'),
                    'views' =>'1',
                    'created_datetime' => date('Y-m-d H:i:s')
                );
                $this->db->insert('ad_knowledge_comments',$views_data);
            }
        }
        
        $data['knwldge_data'] = $this->db->select('*')->get_where('ad_knowledge', array('id'=>$id,'status!='=>'2'))->row();
        
        $data['topics'] = $this->db->select('*')->get_where('ad_knowledge_topic', array('knowledge_id'=>$id))->result();
        
        $data['knowledge_details'] = $this->db->select('ad_k.*,c.category_name,count(comments.comments) as comment_count,comments.comments,comments.created_datetime as comments_date,u.username,count(comments.views) as view_count')
            ->from('ad_knowledge as ad_k')
            ->join('category as c', 'c.id=ad_k.category', 'LEFT')
            ->join('ad_knowledge_comments as comments', 'comments.knowledge_id=ad_k.id', 'LEFT')
            ->join('users as u', 'u.id=comments.user_id', 'LEFT')
            ->where('ad_k.id', $id)
            //->where('comments!=', '')
            ->get()
            ->row();
    
        $data['comments'] = $this->db->select('comments.id,comments.knowledge_id,comments.user_id,comments.comments,comments.created_datetime,u.username')
            ->from('ad_knowledge_comments as comments')
            ->join('ad_knowledge as ad_k', 'ad_k.id=comments.knowledge_id', 'LEFT')
            ->join('users as u', 'u.id=comments.user_id', 'LEFT')
            ->order_by('comments.id','DESC')
            ->where(array('ad_k.id'=>$id, 'comments!='=>'')) //
            ->get()
            ->result();
        $data['no_of_comments']=  $this->db->select('*')
            ->from('ad_knowledge_comments')
            ->where(array('knowledge_id'=>$id, 'comments !='=>''))
            ->get()
            ->num_rows();
            
        $data['likes'] = $this->db->select('count(comments.likes) as likes_count')
            ->from('ad_knowledge_comments as comments')
            ->where(array('comments.knowledge_id'=>$id, 'likes'=>'1'))
            ->get()
            ->row();

        $data['categories'] = $this->db->select('*')
            ->from('category')
            ->where(array('status!='=>'2'))
            ->get()
            ->result();

         $data['popular_articles'] = $this->db->select('*')
            ->from('ad_knowledge')
            //->where('views>','1')
            ->where(array('status!='=>'2'))
            ->order_by('views', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        $data['latest_articles'] = $this->db->select('*')
            ->from('ad_knowledge')
            ->where('created_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 5 DAY) AND NOW()')
            ->where(array('status!='=>'2'))
            ->limit(5)
            ->get()
            ->result();
        //echo 'det<pre>'; print_r($data['popular_articles']); exit;
        $this->template
            ->set_layout('users')
            ->build('ad_knowledge_view',isset($data) ? $data : NULL);
   }

   public function addKnowledge()
   {
        $post = $this->input->post();
        $topic_count = count($post['topic']);
        //echo 'post<pre>'; print_r($topic_count); exit;
        if($post!='') {
            $post_data_1 = array(
                'category'  =>$this->input->post('category'),
                'title' => 'Default Title',
                'branch_id' => $this->input->post('branch_id'),
                //'topic' => $post['topic'],
                'description' => $this->input->post('description')
            );

            if($this->input->post('knowledge_id') == '') {
                $post_data_2 = array('created_datetime' => date('Y-m-d H:i:s'));
                $post_data = array_merge($post_data_1, $post_data_2);
                $this->db->insert('ad_knowledge',$post_data);
                $last_id = $this->db->insert_id();
                if($this->db->affected_rows() > 0) {
                	for($i=0; $i < $topic_count; $i++) {
	                	$topic = array(
		                	'knowledge_id' => $last_id,
		                	'topic'	=> $post['topic'][$i]
	                	);
	                	$this->db->insert('ad_knowledge_topic',$topic);
	                }
                }
            } else {
                $post_data_2 = array('modified_datetime' => date('Y-m-d H:i:s'));
                $post_data = array_merge($post_data_1, $post_data_2);
                App::update('ad_knowledge',array('id'=>$this->input->post('knowledge_id')),$post_data);
                if($this->db->affected_rows() > 0) {
                    $this->db->where('knowledge_id', $this->input->post('knowledge_id'));
                    $this ->db->delete('ad_knowledge_topic');
                    for($i=0; $i < $topic_count; $i++) {
                        $topic = array(
                            'knowledge_id' => $this->input->post('knowledge_id'),
                            'topic' => $post['topic'][$i]
                        );
                        $this->db->insert('ad_knowledge_topic',$topic);
                    }
                }
            }
            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('tokbox_success', 'Knowledge Details Added Successfully');
                redirect($_SERVER['HTTP_REFERER']);
            } 
        } else {
            $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect($_SERVER['HTTP_REFERER']);
        }
   }

   public function addCategory()
   {
        $post = $this->input->post();
        if($post!='') {
            $post_data = array(
                'category_name'  =>$this->input->post('category_name'),
                'created_datetime'  => date('Y-m-d H:i:s'),
                'branch_id'  =>$this->input->post('branch_id'),
                // 'subdomain_id' =>$this->session->userdata('subdomain_id')
            );
        
            if($this->input->post('category_id') == '') {
                $this->db->insert('category',$post_data);
                $data = array(
                'module' => 'knowledgebase',
                'module_field_id' => $this->db->insert_id(),
                'user' => User::get_id(),
                'activity' => 'Knowledgebase category added',
                'icon' => 'fa-star',
                'value1' => User::displayName($this->session->userdata('user_id')),
                'value2' => $this->input->post('category_name'),
                // 'subdomain_id' => $this->session->userdata('subdomain_id')
            );
            App::Log($data);
            } else {
                App::update('category',array('id'=>$this->input->post('category_id')),$post_data);
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
        $this->template->title(lang('category').' - '.config_item('company_name'));
        $data['form'] = TRUE;
        $data['page'] = lang('ad_knowledge');

        if($this->tank_auth->get_role_id() != 1 && $this->session->userdata('user_type_name') =='company_admin'){
            $data['branches']  = $this->db->select('dgt_branches.*')->from('dgt_branches')->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')->where('dgt_branches.branch_status','0')->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))->get()->result_array();

            $branches = array_column($data['branches'],'branch_id');

            $data['categories'] = $this->db->where_in('branch_id',$branches)->where('status!=','2')->get('category')->result();
        }
        else{
            $data['branches'] = $this->db->select('branch_id,branch_status,branch_name')->from('branches') ->where('branch_status','0')->get()->result_array();
            if($this->tank_auth->get_role_id() != 1){
                $data['categories'] = $this->db->select('*')->get_where('category', array('status!='=>'2','branch_id'=>$this->session->userdata('branch_id')))->result();
            }
            else{
                $data['categories'] = $this->db->select('*')->get_where('category', array('status!='=>'2'))->result();
            }
        }
       
        
        $this->template
            ->set_layout('users')
            ->build('category',isset($data) ? $data : NULL);
   }

   public function add_like_details($id,$like) 
   {
        if($like == '1') {
           /* $this->db->set('likes', 'likes+'. $like.'',false);
            $this->db->where('id', $id);
            $this->db->update('ad_knowledge');*/
            //
            $knowledge_data = $this->db->select('*')->get_where('ad_knowledge_comments', array('knowledge_id'=>$id, 'user_id'=>$this->session->userdata('user_id'),'likes'=>'0'))->row();
            
            if($knowledge_data == '') {
                $likes_data = array(
                    'knowledge_id' => $id,
                    'user_id'   =>$this->session->userdata('user_id'),
                    'likes' => '1',
                    'created_datetime' => date('Y-m-d H:i:s')
                );
                $this->db->insert('ad_knowledge_comments', $likes_data);
                 $data = array(
                    'module' => 'knowledgebase',
                    'module_field_id' => $this->db->insert_id(),
                    'user' => User::get_id(),
                    'activity' => 'Knowledgebase category added',
                    'icon' => 'fa-star',
                    'value1' => User::displayName($this->session->userdata('user_id')),
                    'value2' => ''
                );
                App::Log($data);
            } else {
                $this->db->set('likes', '1',false);
                $this->db->where(array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id')));
                $this->db->update('ad_knowledge_comments');
            }
            
        } 
        if($like == '0') {
            $count = '1';
            $this->db->set('likes', '0',false);
                $this->db->where(array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id')));
                $this->db->update('ad_knowledge_comments');
        } 
       //exit;
        if($this->db->affected_rows()>0) {
            $this->session->set_flashdata('tokbox_success', 'Like Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function delete_knowledge() {
        if($this->input->post()) {
            $det['status']= '2'; 
            App::update('ad_knowledge',array('id'=>$this->input->post('knowledge_id')),$det);
             $data = array(
                'module' => 'knowledgebase',
                'module_field_id' => $this->db->insert_id(),
                'user' => User::get_id(),
                'activity' => 'Knowledgebase deleted',
                'icon' => 'fa-star',
                'value1' => User::displayName($this->session->userdata('user_id')),
                'value2' => $this->input->post('knowledge_id')
            );
            App::Log($data);
            $this->session->set_flashdata('tokbox_success', 'Knowledge Details Deleted Successfully');
            redirect('ad_knowledge');
        } else {
            $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect('ad_knowledge');
        }
    }

    public function add_comments() {
    	//$comments_data = $this->db->select('*')->get_where('ad_knowledge_comments', array('knowledge_id'=>$this->input->post('knowledge_id'),'knowledge_id'=>$this->session->userdata('user_id'),'comments!='=>''))->result();
        $post = $this->input->post();
        // date_default_timezone_set('Asia/Calcutta'); 
        //echo 'post<pre>'; print_r($post); exit;
        if($post['user_reply_comments'] == '1') {
            $comments_data = $this->db->get_where('ad_knowledge_comments', array('id'=>$post['comment_id_1'],'knowledge_id'=>$post['knowledge_id_1'],'user_id'=>$post['user_id_1']))->row();
            //if(!empty($comments_data)) {
                $comments = array(
                    'knowledge_id'  => $post['knowledge_id_1'],
                    'user_id'   => $post['user_id_1'],
                    'reply_user_id' => $this->session->userdata('user_id'),
                    'reply_user_comments' => $post['comment'],
                    'modified_datetime' =>date('Y-m-d H:i:s')
                ); 
                //$this->db->insert('ad_knowledge_comments',$comments);
                App::update('ad_knowledge_comments',array('id'=>$post['comment_id_1']),$comments);
                $data = array(
                    'module' => 'knowledgebase',
                    'module_field_id' => $this->db->insert_id(),
                    'user' => User::get_id(),
                    'activity' => 'Knowledgebase comments added',
                    'icon' => 'fa-star',
                    'value1' => User::displayName($this->session->userdata('user_id')),
                    'value2' => $post['comment']
                );
                App::Log($data);
            /*} else {
                echo 'else'; exit;
            }*/
           // echo 'post<pre>'; print_r($comments_data); exit;
        }
        $post_data = array(
            'knowledge_id'  =>$this->input->post('knowledge_id'),
            'user_id'  => $this->session->userdata('user_id'),
            'comments'  => $this->input->post('comment'),
            'created_datetime' => date('Y-m-d H:i:s')
        );
        //echo '<pre>'; print_r($post_data); exit;
        $this->db->insert('ad_knowledge_comments',$post_data);
        $data = array(
                    'module' => 'knowledgebase',
                    'module_field_id' => $this->db->insert_id(),
                    'user' => User::get_id(),
                    'activity' => 'Knowledgebase comments added',
                    'icon' => 'fa-star',
                    'value1' => User::displayName($this->session->userdata('user_id')),
                    'value2' => $post['comment']
                );
                App::Log($data);

        if($this->db->affected_rows()>0) {
        	$this->session->set_flashdata('tokbox_success', 'Comments Added Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
        	$this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect($_SERVER['HTTP_REFERER']);
        }

    }
    
    public function edit_category($id) {
        $data['form'] = TRUE;
        $data['category_details'] = $this->db->get_where('category',array('id'=>$id,'status!='=>'2'))->row();
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
            App::update('category',array('id'=>$this->input->post('category_id')),$det);

            $data = array(
                    'module' => 'knowledgebase',
                    'module_field_id' => $this->db->insert_id(),
                    'user' => User::get_id(),
                    'activity' => 'Knowledgebase category deleted',
                    'icon' => 'fa-star',
                    'value1' => User::displayName($this->session->userdata('user_id')),
                    'value2' => $this->input->post('category_id')
                );
                App::Log($data);
            $this->session->set_flashdata('tokbox_success', 'Category Deleted Successfully');
            redirect('ad_knowledge/category_list');
        }else{
            $data['category_id'] = $this->uri->segment(3);
            $this->load->view('delete_category',$data);
        } 
    }

    public function changeCategoryStatus() {
        if($this->input->post()) {
            $minute_data = $this->db->select('status')->get_where('category', array('id'=>$this->input->post('category_id')))->row();
            if($minute_data->status == '1') {
                $det['status']= '0'; 
            } else if($minute_data->status == '0') {
                $det['status']= '1'; 
            } 
            App::update('category',array('id'=>$this->input->post('category_id')),$det);
            $data = array(
                    'module' => 'knowledgebase',
                    'module_field_id' => $this->db->insert_id(),
                    'user' => User::get_id(),
                    'activity' => 'Knowledgebase category status changes',
                    'icon' => 'fa-star',
                    'value1' => User::displayName($this->session->userdata('user_id')),
                    'value2' => $this->input->post('category_id')
                );
                App::Log($data);
            $this->session->set_flashdata('tokbox_success', 'Category Status Changed Successfully');
            redirect('ad_knowledge/category_list');
        }else{
            $data['category_id'] = $this->uri->segment(3);
            $this->load->view('change_category_status',$data);
        } 
    }

    public function check_categoryname($category_name)
	{
		$category_name = $this->input->post('categoryname');
		$category = $this->db->get_where('category',array('category_name'=>$category_name))->num_rows();
		
		if($category > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
	}

    public function newCategory() {
        $post = $this->input->post('category_name');
        $get_category = $this->db->get_where('category', array('category_name'=>$post))->num_rows();
        if($get_category > 0) {
            $data['success'] = 'exists';
        } else {
            $post_data = array(
                'category_name'  =>$this->input->post('category_name'),
                'created_datetime'  => date('Y-m-d H:i:s'),
               
            );
            if($post != '') {
                $new_category = $this->db->insert('category', $post_data);
                $insert_id = $this->db->insert_id();

                $data['category'] = $this->db->get_where('category', array('id'=>$insert_id))->row();
                $data['category_id'] = $data['category']->id;
                $data['category_name'] = $data['category']->category_name;
                if($data['category'] != '') {
                    $data['success'] = TRUE;
                } else {
                    $data['success'] = FALSE;
                }
            }
        } 
        echo json_encode($data);
        exit;
    }
}