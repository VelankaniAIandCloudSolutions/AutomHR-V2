<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Model
{

    private static $db;

    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    // Get all projects
    static function all()
    {
        $ci =& get_instance();
        if($ci->session->userdata('user_id') ==1){
            return self::$db->order_by('date_created','desc')->get('projects')->result();
        } else {
            return self::$db->where('branch_id',$ci->session->userdata('branch_id'))->order_by('date_created','desc')->get('projects')->result();
        }
        
        
    }

    // Get all pinned projects
   /* static function pinned($type = 'projects')
    {
        if($type == 'tasks'){
            return self::$db->where('pinned',1)->order_by('t_id','desc')->get('tasks')->result();
        }else{
            return self::$db->where('pinned',1)->order_by('project_id','desc')->get('projects')->result();
        }
    }*/

    // Get project information
    static function by_id($id = NULL)
    {
        return self::$db->where('project_id',$id)->get('projects')->row();
    }


    // Save Project
    static function save($data){
        self::$db->insert('projects',$data);
        return self::$db->insert_id();
    }

    // Update Project
    static function update($project,$data){
        return self::$db->where('project_id',$project)->update('projects',$data);
    }


    // Delete project team
    static function delete_team($project){
        return self::$db->where('project_assigned',$project)->delete('assign_projects');
    }

    // Delete task team
    static function delete_task_team($task){
        return self::$db->where('task_assigned',$task)->delete('assign_tasks');
    }


    // Get projects WHERE array
    static function by_where($array = NULL,$join = NULL){
        if($join != NULL) self::$db->join($join,'assign_projects.project_assigned = projects.project_id');
        return self::$db->where($array)->get('projects')->result();
    }
	static function by_where_limit($array = NULL,$join = NULL){
        $client_ids = array();
        if(!empty($_GET['client_name'])){
            $cond_like2['company_name'] = $_GET['client_name'];
            $companines = self::$db->like($cond_like2)->get('companies')->result_array();
            if(!empty($companines)){
                $client_ids = array_column($companines, 'co_id');
            }

           // $cond_like['project_title']  = $_GET['project_title'];
            //self::$db->like($cond_like);
        }
        if($join != NULL) self::$db->join($join,'assign_projects.project_assigned = projects.project_id');

        
        if(!empty($_GET['project_title'])){
            $cond_like['project_title']  = $_GET['project_title'];
            self::$db->like($cond_like);
        }
        if(!empty($client_ids)){
            foreach($client_ids as $client1){
                $cond_like1['client']  = $client1;
                self::$db->like($cond_like1);
            }
        }
        else if(!empty($_GET['client_name'])){
            $cond_like1['client'] = 0;
            self::$db->like($cond_like1);
        }
        
        return self::$db->where($array)->limit($_GET['length'],$_GET['start'])->get('projects')->result();
    }

    // Get uncompleted projects
    static function get_uncomplete($limit = NULL)
    {
        return self::$db->where('status !=','Done')->order_by('project_id','DESC')->get('projects',$limit)->result();
    }

    // Get project assigned users
    static function project_team($project)
    {
        return self::$db->where('project_assigned',$project)->get('assign_projects')->result();
    }

    static function by_client($company)
    {
        return self::$db->where('client',$company)->get('projects')->result();
    }


    static function get_client_links($company)
    {
        return self::$db->where('client',$company)->get('links')->result();
    }

    // Get all project tasks
    static function has_tasks($project)
    {
        return self::$db->where('project',$project)->get('tasks')->result();
    }

    // Get all tasks
    static function get_tasks()
    {
        return self::$db->order_by('t_id','desc')->get('tasks')->result();
    }

    static function user_tasks($user, $project = NULL){
        if($project != NULL){
            self::$db->join('assign_tasks','assign_tasks.task_assigned = tasks.t_id');
            self::$db->where(array('assigned_user' => $user,'project_assigned' => $project));
            return self::$db->order_by('assign_date','desc')->get('tasks')->result();
        }else{
            self::$db->join('tasks','assign_tasks.task_assigned = tasks.t_id');
             return self::$db->where(array('assigned_user' => $user))->order_by('a_id','desc')->get('assign_tasks')->result();
        }

    }

    // Get task assigned users
    static function task_team($task)
    {
        return self::$db->where('task_assigned',$task)->get('assign_tasks')->result();
    }


    // Get time data
    static function view_time_entry($type = 'project',$id)
    {
        $table = ($type == 'project') ? 'project_timer' : 'tasks_timer';
        return self::$db->where('timer_id',$id)->get($table)->row();
    }

    // Get task information
    static function view_task($task,$visible = NULL)
    {
        if($visible != NULL) self::$db->where('visible',$visible);
        return self::$db->where('t_id',$task)->get('tasks')->row();
    }

    // Get comment information
    static function view_comment($comment)
    {
        return self::$db->where('comment_id',$comment)->get('comments')->row();
    }

    // Get project file information
    static function view_file($file)
    {
        return self::$db->where('file_id',$file)->get('files')->row();
    }

    // Get comment reply information
    static function view_reply($reply)
    {
        return self::$db->where('reply_id',$reply)->get('comment_replies')->row();
    }

    // Get task file information
    static function view_task_file($file)
    {
        return self::$db->where('file_id',$file)->get('task_files')->row();
    }

    // Get milestone information
    static function view_milestone($id)
    {
        return self::$db->where('id',$id)->get('milestones')->row();
    }

    // Get bug information
    static function view_bug($id)
    {
        return self::$db->where('bug_id',$id)->get('bugs')->row();
    }

    // Get link information
    static function view_link($id)
    {
        return self::$db->where('link_id',$id)->get('links')->row();
    }

    // Get bug file information
    static function view_bug_file($id)
    {
        return self::$db->where('file_id',$id)->get('bug_files')->row();
    }

    static function setting($setting,$project)
    {
        $json = self::by_id($project)->settings;
        if($json == NULL) $json = '{"settings":"on"}';
        $settings = json_decode($json);
        return (array_key_exists($setting, $settings)) ? TRUE : FALSE;
    }

    static function permissions(){
        return self::$db->where(array('id >'=>'0'))->get('project_settings')->result();
    }

    // Get all project files
    static function has_files($project)
    {
        return self::$db->where('project',$project)->get('files')->result();
    }

    // Get all project bugs
    static function has_bugs($project)
    {
        return self::$db->where('project',$project)->get('bugs')->result();
    }

    // Get all project bugs
    static function has_milestones($project)
    {
        return self::$db->where('project',$project)->get('milestones')->result();
    }

    // Get all tasks in milestone
    static function milestone_has_tasks($id)
    {
        return self::$db->where('milestone',$id)->get('tasks')->result();
    }


    // Get all project links
    static function has_links($project)
    {
        return self::$db->where('project_id',$project)->get('links')->result();
    }
    // Get all project comments
    static function has_comments($project)
    {
        return self::$db->where(array('project'=>$project,'task_id'=>NULL))->order_by('date_posted','desc')->get('comments')->result();
    }
    // Get all comment replies
    static function has_replies($comment)
    {
        return self::$db->where('parent_comment',$comment)->get('comment_replies')->result();
    }

    // Get task files
    static function task_has_files($task)
    {
        return self::$db->where('task',$task)->get('task_files')->result();
    }

    // Get bug files
    static function bug_has_files($id)
    {
        return self::$db->where('bug',$id)->get('bug_files')->result();
    }

    // Get task comments
    static function task_has_comments($id)
    {
        return self::$db->order_by('date_posted','desc')->where('task_id',$id)->get('comments')->result();
    }

    // Get bug comments
    static function bug_has_comments($id)
    {
        return self::$db->order_by('date_commented','desc')->where('bug_id',$id)->get('bug_comments')->result();
    }

    // Get billable expense
    static function has_billable_expenses($project)
    {
        return self::$db->where(array('project'=>$project,'invoiced' => '0', 'billable' => '1'))
            ->get('expenses')->result();
    }

    // Get project timesheet
    static function timesheet($project,$type = 'project',$user = NULL)
    {
        if($user != NULL) self::$db->where('user',$user);
        if($type == 'project') self::$db->where('project',$project);
        if($type == 'tasks') self::$db->where('pro_id',$project);
        return self::$db->get($type.'_timer')->result();
    }

    // Calculate logged time
    static function logged_time($type = 'project',$id)
    {
        $timer = self::$db->where('timer_id',$id)->get($type.'_timer')->row();
        if($timer->time_in_sec > 0){
            return $timer->time_in_sec;
        }else{
            return ($timer->end_time > 0) ? $timer->end_time - $timer->start_time : 0;
        }
    }

    static function timer_status($type = 'project',$id){
        switch ($type) {
            case 'project':
                $started = self::$db->where(array('user'=>User::get_id(),'project'=>$id,'status'=>'1'))->get('project_timer')->num_rows();
                return ($started > 0) ? 'On' : 'Off';
                break;

            default:
                $started = self::$db->where(array('user'=>User::get_id(),'task'=>$id,'status'=>'1'))
                                    ->get('tasks_timer')->num_rows();
                return ($started > 0) ? 'On' : 'Off';
                break;
        }

    }

    static function staff_work($duration,$user){
        $task_total = $project_total = 0;
        $projectlogs = $tasklogs = array();
        switch ($duration) {
            case 'week':
                $tasklogs = "SELECT * FROM dgt_tasks_timer WHERE user = '$user' AND YEARWEEK(`date_timed`,1) = YEARWEEK(CURDATE(),1)";
                $tasks = self::$db->query($tasklogs)->result();
                $projectlogs = "SELECT * FROM dgt_project_timer WHERE user = $user AND YEARWEEK(`date_timed`,1) = YEARWEEK(CURDATE(),1)";
                $projects = self::$db->query($projectlogs)->result();
                break;
            case 'today':
                $tasklogs = "SELECT * FROM dgt_tasks_timer WHERE user='$user' AND DATE(`date_timed`)=DATE(NOW())";
                $tasks = self::$db->query($tasklogs)->result();
                $projectlogs = "SELECT * FROM dgt_project_timer WHERE user=$user AND DATE(`date_timed`)=DATE(NOW())";
                $projects = self::$db->query($projectlogs)->result();
                break;
            case 'month':
                $month = date('m');
                $year = date('Y');
                $tasklogs = "SELECT * FROM dgt_tasks_timer WHERE user = '$user' AND MONTH(date_timed) = '$month' AND YEAR(date_timed) = '$year'";
                $tasks = self::$db->query($tasklogs)->result();
                $projectlogs = "SELECT * FROM dgt_project_timer WHERE user = '$user' AND MONTH(date_timed) = '$month' AND YEAR(date_timed) = '$year'";
                $projects = self::$db->query($projectlogs)->result();
                break;
            case 'year':
                $year = date('Y');
                $tasklogs = "SELECT * FROM dgt_tasks_timer WHERE user = '$user' AND YEAR(date_timed) = '$year'";
                $tasks = self::$db->query($tasklogs)->result();
                $projectlogs = "SELECT * FROM dgt_project_timer WHERE user = '$user' AND YEAR(date_timed) = '$year'";
                $projects = self::$db->query($projectlogs)->result();
                break;

            default:
                # code...
                break;
        }

        if(count($tasks) > 0){
            foreach ($tasks as $key => $t) {
             $task_total += self::logged_time('tasks',$t->timer_id);
            }
        }
        if(count($projects) > 0){
            foreach ($projects as $key => $p) {
                $project_total += self::logged_time('project',$p->timer_id);
            }
        }
        return Applib::sec_to_hours($task_total + $project_total);
    }

    // Calculate logged time
    static function staff_logged_time($staff,$billable = 1)
    {
        $projects = self::$db->where(array('user'=>$staff,'billable'=>$billable))
                            ->get('project_timer')->result();
        $tasks = self::$db->where(array('user'=>$staff,'billable'=>$billable))->get('tasks_timer')->result();
        $pr_time = 0;
        $t_time = 0;
        foreach ($projects as $key => $pr) {
                if($pr->time_in_sec > 0){
                $pr_time += $pr->time_in_sec;
            }else{
              $pr_time += ($pr->end_time > 0) ? $pr->end_time - $pr->start_time : 0;
            }
        }
        foreach ($tasks as $key => $t) {
                if($t->time_in_sec > 0){
                $t_time += $t->time_in_sec;
            }else{
              $t_time += ($t->end_time > 0) ? $t->end_time - $t->start_time : 0;
            }
        }
        return $pr_time + $t_time;

    }

    // Get project progress
    static function get_progress($project = NULL)
    {
        $auto_progress = self::by_id($project)->auto_progress;
        if($auto_progress == 'TRUE'){
            // get tasks progress and find the project progress
            return Applib::format_deci(self::$db->select_avg('task_progress')->where('project',$project)->get('tasks')->row()->task_progress);
        }else{
            return self::by_id($project)->progress;
        }

    }


    // Get project hours
    static function total_hours($project){
        return round((self::get_hours($project,'','tasks') + self::get_hours($project))/3600,2);
    }

    // Get project sub total cost
    static function sub_total($project){
        $total_hours = self::total_hours($project);
        $hourly_rate = self::by_id($project)->hourly_rate;
        $cost = self::by_id($project)->fixed_price;
        if(self::by_id($project)->fixed_rate == 'No') { $cost = $total_hours * $hourly_rate; }
        return round($cost,2);
    }


    // Calculate total tasks/project hours
    static function get_hours($project,$billable = NULL,$type = 'project'){
        $billable = ($billable == NULL) ? '1' : '0';
        $res = self::$db->where(array('project'=>$project,'billable'=>$billable))->get('project_timer')->result();
        if($type == 'tasks'){
            $res = self::$db->where(array('pro_id'=>$project,'billable'=>$billable))->get('tasks_timer')->result();
        }
        $a = array();
        foreach ($res as $key => $t) {
            $a[] = self::logged_time($type,$t->timer_id);
        }
        return (is_array($a)) ? array_sum($a) : 0;

    }

    static function unbillable($project){
        $for_tasks = self::get_hours($project,$billable = '0','tasks');
        $for_projects = self::get_hours($project,$billable = '0');
        return round(($for_tasks + $for_projects)/3600,2);
    }

    // Total expense amount in project
    static function total_expense($project = NULL)
    {
        return self::$db->select_sum('amount')
            ->where(array('project'=> $project,'billable' => '1', 'invoiced' => '0'))
            ->get('expenses')
            ->row()->amount;

    }

    // Calculate user time spent on tasks/projects

    static function time_by_user($user,$billable = '1',$project){
        $data = array();
        self::$db->where(array('user' => $user,'billable' => $billable,'project' => $project));
        $p_time = self::$db->get('project_timer')->result();
        $pt = 0;
        foreach ($p_time as $key => $p) :
            $pt += self::logged_time('project',$p->timer_id);
        endforeach;
        $data['projects_time'] = $pt;

        self::$db->where(array('user' => $user,'billable' => $billable,'pro_id' => $project));
        $t_time = self::$db->get('tasks_timer')->result();
        $tt = 0;
        foreach ($t_time as $key => $t) :
            $tt += self::logged_time('tasks',$t->timer_id);
        endforeach;
        $data['tasks_time'] = $tt;
        return (object)$data;
    }


    static function task_timer($task){
        $res = self::$db->where(array('task'=>$task,'billable'=>'1','status'=>'0'))->get('tasks_timer')->result();
        $a = array();
        foreach ($res as $key => $t) {
            $a[] = self::logged_time('tasks',$t->timer_id);
        }
        return (is_array($a)) ? array_sum($a) : 0;
    }


    static function milestone_progress($id){
        $tasks = self::$db->where('milestone',$id) -> get('tasks') -> num_rows();
        $row = self::$db->select_sum('task_progress')->where('milestone',$id)->get('tasks')->row();
        return ($tasks > 0) ? Applib::format_deci($row->task_progress/$tasks) : 0;

    }

    // Get all bugs
    static function get_bugs($limit = NULL)
    {
        return self::$db->order_by('reported_on','DESC')->get('bugs',$limit)->result();
    }


    static function is_assigned($user, $project){
        $assigned = self::$db->where(array('assigned_user'=>$user,'project_assigned' => $project))->get('assign_projects')->num_rows();
        return ($assigned > 0) ? TRUE : FALSE;
    }

    static function is_task_team($task){
        $is_true = self::$db->where(array('assigned_user'=>User::get_id(),'task_assigned'=>$task))
            ->get('assign_tasks')->num_rows();
        return ($is_true > 0) ? TRUE : FALSE;
    }


    static function activity($id){
        $modules = array('milestones', 'tasks', 'files','bugs','projects','links');
        if(!User::is_admin()) self::$db->where('user',User::get_id());
        self::$db->where_in('module', $modules);
        self::$db->order_by('activity_id','desc');

        return self::$db->where(array('module_field_id'=>$id))->get('activities')->result();
    }


    static function convert_estimate_to_project($estimate){
        $ci = &get_instance();
        $ci->load->helper('string');
        $ci->load->model(array('Estimate','Client'));
        $info = Estimate::view_estimate($estimate);
        $due_date = date('Y-m-d', strtotime("+30 days"));

        $admin = self::$db->select('id')->where(array('role_id'=>'1','activated'=>'1','banned'=>'0'))->order_by('id','RANDOM')->get('users')->row()->id;

        $assign_to = 'a:1:{i:0;s:1:"'.$admin.'";}';

        $data = array(
                        'project_code' => random_string('nozero', 5),
                        'project_title' => 'Project '.$info->reference_no,
                        'description'   => $info->notes,
                        'client'        => $info->client,
                        'currency'      => $info->currency,
                        'start_date'    => Applib::date_formatter(date('d-m-Y')),
                        'due_date'      => $due_date,
                        'fixed_rate'    => 'Yes',
                        'hourly_rate'   => config_item('hourly_rate'),
                        'fixed_price'   => Applib::format_deci(Estimate::due($estimate)),
                        'progress'      => 0,
                        'auto_progress' => 'TRUE',
                        'notes'         => 'Created from Estimate '.$info->reference_no,
                        'assign_to'     => $assign_to,
                        'language'      => Client::view_by_id($info->client)->language,
                        );
        $project_id = self::save($data);

        $data = array(
                        'assigned_user' => $admin,
                        'project_assigned' => $project_id
                        );
        self::$db->insert('assign_projects',$data);

        // default project settings
        $default_settings = json_decode(config_item('default_project_settings'),TRUE);
        foreach ($default_settings as $key => &$value) {
                    if (strtolower($value) == 'off') {
                        unset($default_settings[$key]);
                    }
                }
        $default_settings = json_encode($default_settings);

        $data = array('settings'=>$default_settings);
        self::update($project_id,$data);

        $items = Estimate::has_items($estimate);
        foreach ($items as $key => $item) {
            $data = array(
                        'task_name'         => $item->item_name,
                        'project'           => $project_id,
                        'assigned_to'       => $assign_to,
                        'description'       => $item->item_desc,
                        'start_date'        => date('Y-m-d'),
                        'due_date'          => $due_date,
                        'estimated_hours'   => 0.00,
                        'added_by'          => User::get_id()
                        );
             $task_id = App::save_data('tasks',$data);
             $data = array(
                        'assigned_user' => $admin,
                        'project_assigned' => $project_id,
                        'task_assigned' => $task_id
                        );
             self::$db->insert('assign_tasks',$data);
        }
        return $project_id;
    }

    static function stop_timer($id,$type = NULL){
        if($type == 'project'){
            $info = Project::by_id($id);
            if(!self::timer_started_by($id,'project')){
                 log_message('error', lang('timer_not_allowed'));
            }
            $project_logged_time =  Project::total_hours($id);
            $time_logged = (time() - $info->timer_start) + $project_logged_time;

            $data = array('timer'=>'Off','time_logged'=>$time_logged,'timer_start'=>'');
            Project::update($id,$data);
            $data = array('project'=>$id,'start_time'=>$info->timer_start,
                'user'=>User::get_id(),'end_time'=>time());

            App::save_data('project_timer',$data);
            return TRUE;
        }else{
            if (!self::timer_started_by($id,'task')) {
                log_message('error', lang('timer_not_allowed'));
            }

            $task_start = Project::view_task($id)->start_time;
            $task_logged_time = Project::task_timer($id);
            $time_logged = (time() - $task_start) + $task_logged_time;
            $data = array(
                'timer_status' => 'Off',
                'logged_time' => $time_logged,
                'start_time' => NULL
                );
            App::update('tasks',array('t_id'=>$id),$data);
            $data = array(
            'pro_id' => Project::view_task($id)->project,
            'task' => $id,
            'user' => User::get_id(),
            'start_time' => $task_start,
            'end_time' => time()
            );
            App::save_data('tasks_timer',$data);
            return TRUE;
        }
    }

    static function timer_started_by($id,$type = NULL){
        if($type == 'project'){
            $started_by = Project::by_id($id)->timer_started_by;
            return ($started_by == User::get_id() || User::is_admin()) ? TRUE : FALSE;
        }else{
            $started_by = Project::view_task($id)->timer_started_by;
            return ($started_by == User::get_id() || User::is_admin()) ? TRUE : FALSE;
        }
    }

    // Delete Project
    static function delete($project){

        foreach(self::has_comments($project) as $key => $comment){
            App::delete('comments',array('comment_id'=>$comment->comment_id));
        }

        self::$db->where(array('project' => $project))->delete('project_timer');
        self::$db->where(array('pro_id' => $project))->delete('tasks_timer');

        foreach (self::has_bugs($project) as $bug) {
            foreach (self::bug_has_files($bug->bug_id) as $key => $f) {
                $fullpath = './assets/bug-files/'.$f->file_name;

                if(is_file($fullpath)) unlink($fullpath);

                App::delete('bug_files',array('file_id'=>$f->file_id));
            }
        }

        foreach (self::$db->where('project',$project)->get('expenses')->result() as $ex) {
            $data = array('project' => '0');
            self::$db->where('id',$ex->id)->update('expenses',$data);
        }

        self::$db->where(array('project' => $project))->delete('bugs');

        self::delete_team($project);

        self::$db->where(array('project_assigned' => $project))->delete('assign_tasks');
        self::$db->where(array('project_id' => $project))->delete('links');
        self::$db->where(array('module' => 'projects','module_field_id' => $project))->delete('activities');
        self::$db->where(array('project' => $project))->delete('milestones');

        foreach (self::has_tasks($project) as $task) {
            foreach (self::task_has_files($task->t_id) as $key => $f) {
                $fullpath = './assets/project-files/'.$f->path.$f->file_name;

                if($f->path == NULL) $fullpath = './assets/project-files/'.$f->file_name;

                if(is_file($fullpath)) unlink($fullpath);

                // self::delete_task_file($f->file_id);
            }
        }
        // Delete project files
        foreach (self::has_files($project) as $key => $f) {
            $fullpath = './assets/project-files/'.$f->path.$f->file_name;
            if($f->path == NULL) $fullpath = './assets/project-files/'.$f->file_name;

            if(is_file($fullpath)) unlink($fullpath);
            App::delete('files',array('file_id' => $f->file_id));
        }

        self::$db->where(array('project' => $project))->delete('tasks');
        self::$db->where(array('project_id' => $project))->delete('projects');
    }

    // Get staff based project
    static function staff_project($staff_id)
    {
        return self::$db->like('assign_to',$staff_id)->get('projects')->result();
    }

}

/* End of file Project.php */
