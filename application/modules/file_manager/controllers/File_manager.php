<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class File_manager extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->model(array('App'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
        $this->load->helper('security');
     	if(!App::is_access('menu_file_manager'))
        {
            $this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        }
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('file_manager').' - '.config_item('company_name'));
        $data['page'] = lang('file_manager');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['dropzone'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        
		$data['users'] = $this->db->select('U.id,AD.fullname')
                                  ->from('users U')
                                  ->join('account_details AD','U.id = AD.user_id')
                                  ->where('role_id',3)
                                  ->get()->result();
		$data['all_projects']=$this->db->get('projects')->result_array();
		$data['today_files']=$this->db->order_by('file_id','desc')->limit('4','file_id')->get('file_manager')->result_array();
		$data['all_files']=$this->db->order_by('file_id','desc')->get('file_manager')->result_array();
        $this->template
                ->set_layout('users')
                ->build('file_manager', isset($data) ? $data : null);
    }
	
	public function add_files()
	 {
		$post = $this->input->post();
		$project_id=$this->input->post('project');
        if($_FILES['files']['tmp_name'] != '') {
            $config['upload_path'] = './uploads/files';
            $config['allowed_types'] ='gif|jpg|png|jpeg|xlsx|zip|pdf|doc|docx|ppt|pptx|xls|mp3|mp4|txt';
            $this->load->library('upload', $config);
			
            if(!$this->upload->do_upload('files')) {
                $this->session->set_flashdata('tokbox_error', $this->upload->display_errors());
				redirect('file_manager');
            } 
			else 
			{ 
                $data = $this->upload->data();
                $upload_data = array('avatar' => $data['file_name']);
                $size=$this->formatSizeUnits($s1);
                $uploadImg = base_url().'uploads/files/'.$upload_data['avatar'];
				
				date_default_timezone_set("Asia/kolkata");
				$date=date('d-m-Y');
				
				$s1=$_FILES['files']['size'];
				$size=$this->formatSizeUnits($s1);
				$f=explode('.',$upload_data['avatar']);
				$file_type=$f[1];
				$data=array(
				'project_id'=>$project_id,
				'file_name'=>$upload_data['avatar'],
				'file_type'=>$file_type,
				'file_path'=>'uploads/files/',
				'file_size'=>$size,
				'created_at'=>$date,
				);
				
			$insert=$this->db->insert('file_manager',$data);
			// Log activity
            $data = array(
                'module' => 'file manager',
                'module_field_id' =>  $this->db->insert_id(),
                'user' => User::get_id(),
                'activity' => 'New Files Added',
                'icon' => 'fa-file',
                'value1' => $upload_data['avatar'],
                'value2' => $upload_data['avatar'],
                );
            App::Log($data);	
				
            }
        }
		$this->session->set_flashdata('tokbox_success', 'Files Added Successfully');
		redirect('file_manager');
	}
	
	
	public function edit_files()
	 {
		$file_id=$this->uri->segment(3);
		$project_id=$this->input->post('project'.$file_id);
		$exist_file=$this->input->post('exist_file'.$file_id);
		
		//edit_file
			
		 if($_FILES['files'.$file_id]['tmp_name'] != '') {
            $config['upload_path'] = './uploads/files';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|xlsx|xls|zip|pdf|doc|docx|ppt|pptx|mp4|mp3|txt';
            $this->load->library('upload', $config);
			
			$config['upload_path'];
            if(!$this->upload->do_upload('files'.$file_id)) {
                echo $this->upload->display_errors(); exit;
            } 
			else 
			{ 
                $data = $this->upload->data();
                $upload_data = array('avatar' => $data['file_name']);
                $size=$this->formatSizeUnits($s1);
                $uploadImg = base_url().'uploads/files/'.$upload_data['avatar'];
				
				date_default_timezone_set("Asia/kolkata");
				$date=date('d-m-Y');
				
				$s1=$_FILES['files'.$file_id]['size'];
				$size=$this->formatSizeUnits($s1);
				$f=explode('.',$upload_data['avatar']);
				$file_type=$f[1];
				$data=array(
				'project_id'=>$project_id,
				'file_name'=>$upload_data['avatar'],
				'file_type'=>$file_type,
				'file_path'=>'uploads/files/',
				'file_size'=>$size,
				'created_at'=>$date,
				);
				
			//$insert=$this->db->insert('file_manager',$data);	
			$update=$this->db->where('file_id',$file_id)->update('file_manager',$data);
			// Log activity
            $data = array(
                'module' => 'file manager',
                'module_field_id' =>  $this->db->insert_id(),
                'user' => User::get_id(),
                'activity' => $upload_data['avatar']. 'File Updated',
                'icon' => 'fa-file',
                'value1' => $upload_data['avatar'],
                'value2' => $upload_data['avatar']
                );
            App::Log($data);
            }
        }
		else
		{
		$data=array(
        'project_id'=>$project_id,
       
        );
        $update=$this->db->where('file_id',$file_id)->update('file_manager',$data);
        
			
		}
		
		$this->session->set_flashdata('tokbox_success', 'Updated Successfully');
		redirect('file_manager');	
	
	}
	
	
	 function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes/1073741824,2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes/1048576,2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes/1024,2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

function project_files()
{
	$project_id=$this->input->post('project_id');
	if($project_id=='0')
	{
		$all_files=$this->db->order_by('file_id','desc')->get('file_manager')->result_array();
	
	}
	else
	{
		$all_files=$this->db->order_by('file_id','desc')->get_where('file_manager',array('project_id'=>$project_id))->result_array();

		$folder_details = $this->db->get_where('folders',array('folder_id'=>$project_id))->row_array();
	}
	$folder_name = $folder_details['folder_name']?$folder_details['folder_name']:'';
	
	$html.='<h4>'.ucfirst($folder_name).' Files</h4><div class="row row-sm">';
		
		$i=1;
		if(!empty($all_files)){
		foreach($all_files as $file)
		{
			
				
			$html.='<div class="col-md-3">
				<div class="card card-file">
					<div class="dropdown-file">
						<a href="" class="dropdown-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
						<a href="#" class="dropdown-item" onclick="ShareBtnAtag('.$file['file_id'].');" data-toggle="modal" data-target="#share_file" data-id="">Share</a>
							<a href="'.base_url().$file['file_path'].$file['file_name'].'" class="dropdown-item" target="_blank">View</a>
							<a href="'.base_url().'file_manager/download_file/'.$file['file_name'].'" class="dropdown-item">Download</a>
							<a data-toggle="modal" data-target="#edit_file'.$file['file_id'].'" href="" class="dropdown-item">Modify</a>
								<a data-toggle="modal" data-target="#delete_file'.$file['file_id'].'" href="" class="dropdown-item">Delete</a>
						</div>
					</div>
					<a href="'.base_url().$file['file_path'].$file['file_name'].'" target="_blank">
					<div class="card-file-thumb">';
						
						if($file['file_type']=='pdf')
						{
							$html.='<i class="fa fa-file-pdf-o" style="color:red;"></i>';

					
						}
						else if(($file['file_type']=='docx') ||  ($file['file_type']=='doc'))
						{
							$html.='<i class="fa fa-file-word-o" style="color:#00c5fb;"></i>';

						}
						else if($file['file_type']=='png'||$file['file_type']=='jpg'||$file['file_type']=='psd')
						{
							$html.='<i class="fa fa-file-image-o"></i>';

						
						}
						else if(($file['file_type']=='xls') || ($file['file_type']=='xlsx'))
						{
							$html.='<i class="fa fa-file-excel-o" style="color:#3ba92e;"></i>';

						}												
						else if($file['file_type']=='ppt')
						{
							$html.='<i class="fa fa-file-powerpoint-o" style="#D04423;"></i>';

						
						}
						else if($file['file_type']=='mp3')
						{
							$html.='<i class="fa fa-file-audio-o"></i>';

						}
						else if($file['file_type']=='mp4')
						{
							$html.='<i class="fa fa-file-video-o"></i>';
						}
						else if($file['file_type']=='txt')
						{
							$html.='<i class="fa fa-file-text-o"></i>';
						}
						else if($file['file_type']=='html')
						{
							$html.='<i class="fa fa-file-code-o"></i>';
						}
						
						
					$html.='</div></a>
					<div class="card-body">
						<h6><a href="">'.$file['file_name'].'</a></h6>
						<span>'.$file['file_size'].'</span>
					</div>
					<div class="card-footer">
						<span class="d-none d-sm-inline">Last Modified: </span>'.date('d M Y',strtotime($file['last_modified'])).'
					</div>
				</div>
			</div>';
		
			
		
			
		$i++;	
	}
   }else{
   		$html.='<div class="card-body">
					<center><h6>No Records Found</h6></center>
				</div>';
   }
	$html.='</div>';	
		
		
		echo $html; exit();														
	}														
														
function file_search()
{	
	
	$search_data = $this->input->post('search_data');

    $all= $this->db->select('*')->from('file_manager')->like('file_name',$search_data)->get()->result_array();   
	$html.='<h4>Files</h4><div class="row row-sm">';
													
	$i=1;
	if(!empty($all)){
	foreach($all as $file)
	{
		$html.='<div class="col-md-3">
			<div class="card card-file">
				<div class="dropdown-file">
					<a href="" class="dropdown-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
					<a href="#" class="dropdown-item ShareBtnAtag" data-toggle="modal" data-target="#share_file" data-id="'.$file['file_id'].'">Share</a>
						<a href="'.base_url().$file['file_path'].$file['file_name'].'" class="dropdown-item" target="_blank">View</a>
						<a href="'.base_url().'file_manager/download_file/'.$file['file_name'].'" class="dropdown-item">Download</a>
						<a data-toggle="modal" data-target="#edit_file'.$file['file_id'].'" href="" class="dropdown-item">Modify</a>
							<a data-toggle="modal" data-target="#delete_file'.$file['file_id'].'" href="" class="dropdown-item">Delete</a>
					
						
					</div>
			</div>
			<a href="'.base_url().$file['file_path'].$file['file_name'].'" target="_blank">
			<div class="card-file-thumb">';
				
				if($file['file_type']=='pdf')
				{
					$html.='<i class="fa fa-file-pdf-o" style="color:red;"></i>';

			
				}
				else if(($file['file_type']=='docx') ||  ($file['file_type']=='doc'))
				{
					$html.='<i class="fa fa-file-word-o" style="color:#00c5fb;"></i>';

				}
				else if($file['file_type']=='png'||$file['file_type']=='jpg'||$file['file_type']=='psd')
				{
					$html.='<i class="fa fa-file-image-o"></i>';

				
				}
				else if(($file['file_type']=='xls') || ($file['file_type']=='xlsx'))
				{
					$html.='<i class="fa fa-file-excel-o" style="color:#3ba92e;"></i>';

				}												
				else if($file['file_type']=='ppt')
				{
					$html.='<i class="fa fa-file-powerpoint-o" style="#D04423;"></i>';

				
				}
				else if($file['file_type']=='mp3')
				{
					$html.='<i class="fa fa-file-audio-o"></i>';

				}
				else if($file['file_type']=='mp4')
				{
					$html.='<i class="fa fa-file-video-o"></i>';
				}
				else if($file['file_type']=='txt')
				{
					$html.='<i class="fa fa-file-text-o"></i>';
				}
				else if($file['file_type']=='html')
				{
					$html.='<i class="fa fa-file-code-o"></i>';
				}
																
																
				$html.='</div></a>
				<div class="card-body">
					<h6><a href="">'.$file['file_name'].'</a></h6>
					<span>'.$file['file_size'].'</span>
				</div>
				<div class="card-footer">
					<span class="d-none d-sm-inline">Last Modified: </span>'.date('d M Y',strtotime($file['last_modified'])).'
				</div>
			</div>
		</div>';				
		$i++;	
	}
   }else{
   		$html.='<div class="card-body">
					<center><h6>No Records Found</h6></center>
				</div>';
   }
	$html.='</div>';	
	
	echo $html; exit();	
}
	function project_search()
	{
		$search_data = $this->input->post('search_data');
		//$project= $this->db->like('project_title', $search_data)->get('projects')->result_array();
		if($search_data=="")
		{
			$project = $this->db->get('folders')->result_array();
		}
		else
		{
			$project= $this->db->select('*')->from('folders')->like('folder_name',$search_data)->get()->result_array(); 
		}
		if(!empty($project)){
		foreach ($project as $p){
			
			$html.='<li class="p-2 fav_id list-group-item d-flex justify-content-between align-items-center">
					<a href="#" onclick="file_fetch('.$p['folder_id'].')">'.$p['folder_name'].'</a>
					<div class="dropdown dropdown-action">
						<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="#" id="edit" onclick="edit_folder('.$p['folder_id'].')"><i class="fa fa-pencil m-r-5"></i> Edit</a>
							<a class="dropdown-item" href="<?php echo base_url()?>file_manager/delete_folder/'.$p['folder_id'].'" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
						</div>
					</div>
				</li>';	
		}
	   }else{
	   	$html.='<div class="card-body">
					<center><h6>No Records Found</h6></center>
				</div>';
	   }
		echo $html;
	exit();
	}

function download_file()
{
	$this->load->helper('download');
	$id=$this->uri->segment(3);
	$file_name=$this->db->get_where('file_manager',array('file_id'=>$id))->row()->file_name;
	$path = base_url('uploads/files/'.$file_name);
	$data   = file_get_contents($path);
	$this->session->set_flashdata('tokbox_success', 'Download Successfully');
	force_download($file_name, $data);
	
}

public function delete_file()
{
	$file_id=$this->uri->segment(3);
	$file = $this->db->get_where('file_manager', array('file_id'=>$file_id))->row();
	$delete=$this->db->where('file_id',$file_id)->delete('file_manager');
	// Log activity
    $data = array(
        'module' => 'file manager',
        'module_field_id' =>  $this->db->insert_id(),
        'user' => User::get_id(),
        'activity' => 'Upload Files Deleted',
        'icon' => 'fa-file',
        'value1' => $file->file_name,
        'value2' => $file->file_name
        );
    App::Log($data);
	$this->session->set_flashdata('tokbox_success', 'File Deleted Successfully');
	redirect('file_manager');
}

public function rename_file()
{
	$old_file = $this->input->post('rename');
	$new_file = $this->uri->segment(3);
	$oldDir = FCPATH . 'assets/uploads/'.$old_file;
	$newDir = FCPATH . 'assets/uploads/'.$new_file;
	rename($oldDir, $newDir);
	redirect('file_manager');
}	


// Create folder...

public function create_folder()
{
	$foldername  = $this->input->post('folder_name');
	$res = array(
		'folder_name' => $foldername
	);
	if($this->input->post('folder_id') == ''){
		$this->db->insert('folders',$res);
		// Log activity
    $data = array(
        'module' => 'file manager',
        'module_field_id' =>  $this->db->insert_id(),
        'user' => User::get_id(),
        'activity' => 'File Folder Created',
        'icon' => 'fa-file',
        'value1' => $foldername,
        'value2' => $foldername
        );
	$this->session->set_flashdata('tokbox_success', 'Folder Added Successfully');
	}else{
		$this->db->where('folder_id',$this->input->post('folder_id'));
		$this->db->update('folders',$res);

		// Log activity
    $data = array(
        'module' => 'file manager',
        'module_field_id' =>  $this->db->insert_id(),
        'user' => User::get_id(),
        'activity' => 'File Folder Updated',
        'icon' => 'fa-file',
        'value1' => $foldername,
        'value2' => $foldername
        );
	$this->session->set_flashdata('tokbox_success', 'Folder Updated Successfully');
	}
	
    App::Log($data);
    redirect($_SERVER['HTTP_REFERER']);

}
function folders($folder_id =NULL){
	if ($_POST) {
		
		$settings = $_POST['settings'];
		unset($_POST['settings']);

		$this->db->where('folder_name',$this->input->post('folder_name'));
		$folders_exits = $this->db->get('folders')->num_rows();
		if($folders_exits>0){
			$this->session->set_flashdata('tokbox_error', "Folders already exists");
			redirect($_SERVER['HTTP_REFERER']);
		}

		App::save_data('folders',$this->input->post());
		$this->session->set_flashdata('tokbox_success', 'Folders Added successfully');
		redirect($_SERVER['HTTP_REFERER']);
	}else{
		// $this->index();
		$data['folder_id'] = $folder_id;
		$this->load->view('modal/add_folders',$data);
	}
}

public function share_to_users()
{
	$shared_users = $this->input->post('user');
	for($i=0;$i<count($shared_users);$i++) {
		$share_file_details = array(
			'file_id' => $this->input->post('file_id'),
			'user_id' => $this->session->userdata('user_id'),
			'share_to_ids' => $shared_users[$i]
		);
		
		$this->db->insert('fileshare_details',$share_file_details);
		// echo "<pre>"; print_r($share_file_details);
	} 
		// exit;
	$this->session->set_flashdata('tokbox_success', 'Files shared Successfully!');
    redirect($_SERVER['HTTP_REFERER']);
}


	public function share_files()
	{
		$share_type = $this->input->post('share_type');

		if($share_type == 'share_to_other'){
				$all_files = 	$this->db->select('FM.*,FD.share_to_ids,FD.user_id')
										 ->from('fileshare_details FD')
										 ->join('file_manager FM','FD.file_id = FM.file_id')
										 ->order_by('FD.share_id')
										 ->where('FD.user_id',$this->session->userdata('user_id'))
										 ->group_by('FD.file_id')
										 ->get()->result_array();
		$html.='<h4>I Shared Files</h4><div class="row row-sm">';
		}

		if($share_type == 'share_to_me'){
				$all_files = 	$this->db->select('FM.*')
										 ->from('fileshare_details FD')
										 ->join('file_manager FM','FD.file_id = FM.file_id')
										 ->where('FD.share_to_ids',$this->session->userdata('user_id'))
										 ->get()->result_array();
		$html.='<h4>Shared me Files</h4><div class="row row-sm">';
		}
			
			$i=1;
			if(!empty($all_files)){
			foreach($all_files as $file)
			{
				$html.='<div class="col-md-3">
					<div class="card card-file">
						<div class="dropdown-file">
							<a href="" class="dropdown-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="'.base_url().$file['file_path'].$file['file_name'].'" class="dropdown-item" target="_blank">View</a>
								<a href="'.base_url().'file_manager/download_file/'.$file['file_name'].'" class="dropdown-item">Download</a>';

				// $html.=				'<a data-toggle="modal" data-target="#edit_file'.$file['file_id'].'" href="" class="dropdown-item">Modify</a>
				// 					<a data-toggle="modal" data-target="#delete_file'.$file['file_id'].'" href="" class="dropdown-item">Delete</a>';

				$html.=			'</div>
						</div>
						<a href="'.base_url().$file['file_path'].$file['file_name'].'" target="_blank">
						<div class="card-file-thumb">';
							
							if($file['file_type']=='pdf')
						{
							$html.='<i class="fa fa-file-pdf-o" style="color:red;"></i>';

					
						}
						else if(($file['file_type']=='docx') ||  ($file['file_type']=='doc'))
						{
							$html.='<i class="fa fa-file-word-o" style="color:#00c5fb;"></i>';

						}
						else if($file['file_type']=='png'||$file['file_type']=='jpg'||$file['file_type']=='psd')
						{
							$html.='<i class="fa fa-file-image-o"></i>';

						
						}
						else if(($file['file_type']=='xls') || ($file['file_type']=='xlsx'))
						{
							$html.='<i class="fa fa-file-excel-o" style="color:#3ba92e;"></i>';

						}												
						else if($file['file_type']=='ppt')
						{
							$html.='<i class="fa fa-file-powerpoint-o" style="#D04423;"></i>';

						
						}
						else if($file['file_type']=='mp3')
						{
							$html.='<i class="fa fa-file-audio-o"></i>';

						}
						else if($file['file_type']=='mp4')
						{
							$html.='<i class="fa fa-file-video-o"></i>';
						}
						else if($file['file_type']=='txt')
						{
							$html.='<i class="fa fa-file-text-o"></i>';
						}
						else if($file['file_type']=='html')
						{
							$html.='<i class="fa fa-file-code-o"></i>';
						}
							
							
						$html.='</div></a>
						<div class="card-body">
							<h6><a href="">'.$file['file_name'].'</a></h6>
							<span>'.$file['file_size'].'</span>
						</div>
						<div class="card-footer">
							<span class="d-none d-sm-inline">Last Modified: </span>'.date('d M Y',strtotime($file['last_modified'])).'
						</div>';
						if($share_type == 'share_to_other'){

							$share_others = $this->db->get_where('fileshare_details',array('file_id'=>$file['file_id'],'user_id'=>$this->session->userdata('user_id')))->result_array();
							foreach($share_others as $other){
								$user_details = $this->db->get_where('account_details',array('user_id'=>$other['share_to_ids']))->row_array();
								$user_names[$i][] = $user_details['fullname'];
							}
							$html.='<div class="card-footer">
								<span class="d-none d-sm-inline">Shared to </span>'.implode(',', $user_names[$i]).'</div>';
						}
						if($share_type == 'share_to_me'){

							$share_to = $this->db->get_where('fileshare_details',array('file_id'=>$file['file_id'],'share_to_ids'=>$this->session->userdata('user_id')))->result_array();
							foreach($share_to as $too){
								$user_details_too = $this->db->get_where('account_details',array('user_id'=>$too['user_id']))->row_array();
								$user_names_too[$i][] = $user_details_too['fullname'];
							}


							$html.='<div class="card-footer">
								<span class="d-none d-sm-inline">Shared by </span>'.implode(',', $user_names_too[$i]).'
							</div>';
						}
					$html.= '</div>
				</div>';
			
				
			
				
			$i++;	
	}
  }else{
  	$html.='<div class="card-body">
					<center><h6>No Records Found</h6></center>
				</div>';

  }
	$html.='</div>';	
		
		
		echo $html; exit;														
	}

	public function edit_folder($id)
	{
		$folder_details = $this->db->get_where('folders',array('folder_id'=>$id))->row_array();
		echo json_encode($folder_details); exit;
	}

	public function delete_folder($id= NULL) {
        if($this->input->post()) {
            $folder_id = $this->input->post('folder_id');

            $all_files  = $this->db->get_where('file_manager',array('project_id'=>$folder_id))->result_array();
            foreach($all_files as $fil)
            {
            	$this->db->where('file_id',$fil['file_id']);
            	$this->db->delete('file_manager');
            }
            App::delete('folders',array('folder_id'=>$folder_id));
            $this->session->set_flashdata('tokbox_success', 'Folder Deleted Successfully');
            redirect('file_manager');
        }else{
            $data['folder_id'] = $id;
            $this->load->view('delete_folder',$data);
        } 
    }

    public function drag_file_upload()
    {
    	$post = $this->input->post();
		$project_id=$this->input->post('drag_folder_id');
		if($project_id != ''){
        if($_FILES['file']['tmp_name'] != '') {
            $config['upload_path'] = './uploads/files';
            $config['allowed_types'] ='gif|jpg|png|jpeg|xlsx|zip|pdf|doc|docx|ppt|pptx|xls|mp3|mp4|txt';
            $this->load->library('upload', $config);
			
            if(!$this->upload->do_upload('file')) {
                $this->session->set_flashdata('tokbox_error', $this->upload->display_errors());
				redirect('file_manager');
            } 
			else 
			{ 
                $data = $this->upload->data();
                $upload_data = array('avatar' => $data['file_name']);
                $size=$this->formatSizeUnits($s1);
                $uploadImg = base_url().'uploads/files/'.$upload_data['avatar'];
				
				date_default_timezone_set("Asia/kolkata");
				$date=date('d-m-Y');
				
				$s1=$_FILES['file']['size'];
				$size=$this->formatSizeUnits($s1);
				$f=explode('.',$upload_data['avatar']);
				$file_type=$f[1];
				$data=array(
				'project_id'=>$project_id,
				'file_name'=>$upload_data['avatar'],
				'file_type'=>$file_type,
				'file_path'=>'uploads/files/',
				'file_size'=>$size,
				'created_at'=>$date,
				);
				
			$insert=$this->db->insert('file_manager',$data);
			// Log activity
            $data = array(
                'module' => 'file manager',
                'module_field_id' =>  $this->db->insert_id(),
                'user' => User::get_id(),
                'activity' => 'New Files Added',
                'icon' => 'fa-file',
                'value1' => $upload_data['avatar'],
                'value2' => $upload_data['avatar']
                );
            App::Log($data);	
				
            }
        }
		$this->session->set_flashdata('tokbox_success', 'Files Added Successfully');
		redirect('file_manager');
	}else{
		$this->session->set_flashdata('tokbox_error', 'Folder not selected,');
		redirect('file_manager');
	}
    }

															
}														
														

	

	


  

    
