<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sidebar extends MX_Controller {
	


	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Message','Project'));

	}
	public function admin_menu()
	{
		$data['languages'] = App::languages();
        $this->load->view('admin_menu',isset($data) ? $data : NULL);
	}
	public function collaborator_menu()
	{
		$data['languages'] = App::languages();
		$this->load->view('collaborator_menu',isset($data) ? $data : NULL);
	}
	public function client_menu()
	{
		$data['languages'] = App::languages();
        $this->load->view('user_menu',isset($data) ? $data : NULL);
	}
	public function top_header()
	{

                        $this->db->where(array('status'=>'1'));
                        $project_timers = $this->db->get('project_timer')->result_array();

                        $this->db->where(array('status'=>'1'));
                        $task_timers = $this->db->get('tasks_timer')->result_array();

                $data['timers'] = array_merge($project_timers,$task_timers);
                $data['updates'] = $this->applib->get_updates();

                $this->load->view('top_header',isset($data) ? $data : NULL);
	}
	public function candidate_top_header()
	{
	 $this->load->view('candidate_top_header',isset($data) ? $data : NULL);
	}
	public function scripts()
	{
		$this->load->view('scripts/uni_scripts',isset($data) ? $data : NULL);
	}
	public function flash_msg()
	{
		$this->load->view('flash_msg',isset($data) ? $data : NULL);
	}
}
/* End of file sidebar.php */