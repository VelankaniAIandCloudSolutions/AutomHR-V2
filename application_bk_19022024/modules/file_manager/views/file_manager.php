

<style media="screen">


#categoryorder div.col-md-3 {cursor:move}

</style>

<style>
.inputDnD .form-control-file {
	 position: relative;
	 width: 100%;
	 height: 100%;
	 min-height: 6em;
	 outline: none;
	 visibility: hidden;
	 cursor: pointer;
	 

}
 .inputDnD .form-control-file:before {
	 content: attr(data-title);
	 position: absolute;
	 top: 0;
	 left: 0;
	 width: 100%;
	 min-height: 6em;
	 line-height: 2em;
	 padding-top: 1.5em;
	 opacity: 1;
	 visibility: visible;
	 text-align: center;
	 border: 2px dashed #d0cdcd;
	 transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
	 overflow: hidden;
}
 .inputDnD .form-control-file:hover:before {
	 border-style: solid;

}
</style>

<script  src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
<script>
function readUrl(input) {
  
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    reader.onload = (e) => {
      let imgData = e.target.result;
      let imgName = input.files[0].name;
      input.setAttribute("data-title", imgName);
      console.log(e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }

}
</script>




<div class="content container-fluid">
<div class="row">
<div class="col-sm-12">
<div class="file-wrap">
	<div class="file-sidebar">
		<div class="file-header justify-content-center">
			<span>Folders</span>
			<a href="javascript:void(0);" class="file-side-close"><i class="fa fa-times"></i></a>
		</div>
		<form class="file-search">
			<div class="input-group">
				<div class="input-group-prepend">
					<i class="fa fa-search"></i>
				</div>
				<input type="text" class="form-control" placeholder="Search" id="project_search">
			</div>
		</form>
		<div class=""></div>
		<div class="file-pro-list file-manager-page">
			<div class="file-scroll">
				<ul class="file-menu list-group mb-3" id="project_content">
					<li class="FolderLiClas active  align-items-center">
						<a href="#" onclick="file_fetch(0)">All Folders</a>
					</li>
					<?php
					$this->db->order_by('folder_id','DESC');
					$all_folders = $this->db->get('folders')->result_array();

					$k=1;
					foreach($all_folders as $folder)
					{
						if($k<=5)
						{?>
					<li class="FolderLiClas p-2 fav_id list-group-item d-flex justify-content-between align-items-center">
						<a href="#" onclick="file_fetch(<?php echo $folder['folder_id'];?>)"><?php echo $folder['folder_name'];?></a>
						<?php if(App::is_permit('menu_file_manager','write') || App::is_permit('menu_file_manager','delete')){?>
						<div class="dropdown dropdown-action">
							<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
							<div class="dropdown-menu dropdown-menu-right">
							<?php if(App::is_permit('menu_file_manager','write')){ ?>
								<a class="dropdown-item text-dark" href="#" id="edit" onclick="edit_folder('<?php echo $folder['folder_id']; ?>')"><i class="fa fa-pencil m-r-5"></i> Edit</a>
							<?php } ?>
							<?php if(App::is_permit('menu_file_manager','delete')){ ?>
								<a class="dropdown-item text-dark" href="<?php echo base_url()?>file_manager/delete_folder/<?php echo $folder['folder_id']; ?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
							<?php } ?>
							</div>
						</div>
					<?php } ?>
					</li>
					
						<?php
						}
					$k++;
					}?>
					<span id="dots"></span><span id="more">
					<?php
					$ik=0;
					foreach($all_folders as $folder)
					{
						if($ik>5)
						{?>
					<li>
						<a href="#" onclick="file_fetch(<?php echo $folder['folder_id'];?>)"><?php echo $folder['folder_name'];?></a>
					</li>
					
						<?php
						}
					$ik++;
					}
					?>
					
					
				</ul>
				<div class="settings-menu">
					
				<ul>
                    <?php //if(!empty($Branch)){ ?>
                    <li>
                        <!-- <a data-toggle="collapse" data-parent="#setting-nav" href="#email_menu" class="" aria-expanded="true"> -->
                        <a data-toggle="collapse" data-parent="#setting-nav" href="#email_menu" class="" aria-expanded="true">
                            <i class="fa fa-share-alt"></i> Sharing
                        </a>
                        <ul id="email_menu" class="collapse show" aria-expanded="false" style>
                                <li class="ShareFileUsers" data-share="share_to_other">
                                    <a href="#">
                                        <i class="fa fa-share-alt-square"></i>I Shared
                                    </a>
                                </li>
                                <li class="ShareFileUsers" data-share="share_to_me">
                                    <a href="#">
                                        <i class="fa fa-share-alt-square"></i>Share with me
                                    </a>
                                </li>
                        </ul>
                    </li>
                </ul>
				</div>
				
			</div>
		</div>
	</div>
	<div class="file-cont-wrap" style="width:100%">
		<div class="file-cont-inner">
			<div class="file-cont-header d-block">
				<div class="row" style="padding-top: 20px;">
					<div class="col-6">
						<div class="file-options d-inline-block">
							<a href="javascript:void(0)" id="file_sidebar_toggle" class="file-sidebar-toggle">
							<i class="fa fa-bars"></i>
							</a>
						</div>
						<span class="ml-2">File Manager</span>
					</div>
					<!-- <div class="file-options">
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add_folder"><i class="fa fa-plus"></i> Folder</a>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add_file"><i class="fa fa-upload"></i> Upload</a>
					</div> -->
					<?php if(App::is_permit('menu_file_manager','create')){ ?>
					<div class="col-6" style="display: flex;">
						<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_file"><i class="fa fa-upload"></i> Upload</a>
						<a href="#" class="btn add-btn mr-2" data-toggle="modal" data-target="#add_folder"><i class="fa fa-plus"></i> Folder</a>
					</div>
				<?php } ?>
				</div>
			</div>
			<div class="file-content">
				<form class="file-search">
					<div class="input-group">
						<div class="input-group-prepend">
							<i class="fa fa-search"></i>
						</div>
						<input type="text" class="form-control" placeholder="Search" id="file_search">
					<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
					</div>
				</form>
				<div class="file-body">
					<div class="file-scroll">
					<div class="file-scroll file-drag-upload">
							<!-- Drag & Drop Upload.... -->
						<!-- <div class="row">
						<div class="col-md-12">
							<div class="upload-section p-3">
								<form action="<?php echo base_url(); ?>file_manager/drag_file_upload" class="dropzone" method="post" id="dragFormid" enctype="multipart/form-data">
									<div class="fallback">
										<input name="files" type="file" id="drag_file_id">

									</div>
										<input name="drag_folder_id" id="drag_folder_id" type="hidden">
										 <input name="drag_file_name" id="drag_file_name" type="hidden"> 
								</form>
								 <div action="<?php echo base_url(); ?>file_manager/drag_file_upload" class="dropzone"></div> 

							</div>
						</div>
						</div> -->
						<div class="file-content-inner" id="project_files">




							<h4>Recent Files</h4>
							<div class="row row-sm">
							<?php
							foreach($today_files as $file)
							{?>
								<div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3">
									<div class="card card-file">
										<div class="dropdown-file">
											<a href="" class="dropdown-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
											<div class="dropdown-menu dropdown-menu-right">
											<?php if(App::is_permit('menu_file_manager','read')){ ?>
												<a href="#" class="dropdown-item ShareBtnAtag" data-toggle="modal" data-target="#share_file" data-id="<?php echo $file['file_id'];?>">Share</a>
												<a href="<?php echo base_url().$file['file_path'].$file['file_name'];?>" class="dropdown-item" target="_blank">View</a>
												<a href="<?php echo base_url();?>file_manager/download_file/<?php echo $file['file_id'];?>" class="dropdown-item">Download</a>
											<?php } ?>
											<?php if(App::is_permit('menu_file_manager','write')){ ?>
												<a data-toggle="modal" data-target="#edit_file<?php echo $file['file_id'];?>" href="" class="dropdown-item">Modify</a>
											<?php } ?>
											<?php if(App::is_permit('menu_file_manager','delete')){ ?>
												<a data-toggle="modal" data-target="#delete_file<?php echo $file['file_id'];?>" href="" class="dropdown-item">Delete</a>
											<?php } ?>
											
											</div>
										</div>
											<a href="<?php echo base_url().$file['file_path'].$file['file_name'];?>" target="_blank">
										<div class="card-file-thumb">
											<?php
											if($file['file_type']=='pdf')
											{?>
												<i class="fa fa-file-pdf-o" style="color:red;"></i>

											<?php
											}
											else if($file['file_type']=='docx')
											{?>
												<i class="fa fa-file-word-o" style="color:#00c5fb;"></i>

											<?php
											}
											else if($file['file_type']=='png'||$file['file_type']=='jpg'||$file['file_type']=='psd')
											{?>
												<i class="fa fa-file-image-o"></i>

											<?php
											}
											else if(($file['file_type']=='xls') || ($file['file_type']=='xlsx'))
											{?>
												<i class="fa fa-file-excel-o" style="color:#3ba92e;"></i>

											<?php
											}
											else if($file['file_type']=='ppt')
											{?>
												<i class="fa fa-file-powerpoint-o" style="#D04423;"></i>

											<?php
											}
											else if($file['file_type']=='mp3')
											{?>
												<i class="fa fa-file-audio-o"></i>

											<?php
											}
											else if($file['file_type']=='mp4')
											{?>
												<i class="fa fa-file-video-o"></i>

											<?php
											}
											else if($file['file_type']=='txt')
											{?>
												<i class="fa fa-file-text-o"></i>

											<?php
											}
											else if($file['file_type']=='html')
											{?>
												<i class="fa fa-file-code-o"></i>

											<?php
											}
											?>
										</div>
											</a>
										<div class="card-body">
											<h6><a href=""><?php echo $file['file_name'];?></a></h6>
											<span><?php echo $file['file_size'];?></span>
										</div>
										<div class="card-footer">
											<span class="d-none d-sm-inline">Last Modified: </span><?php echo date('d M Y',strtotime($file['last_modified']));?>
										</div>
									</div>
								</div>
							
								
							<?php	
							}
							?>
								
								
							</div>
							<h4>Files</h4>
							<div class="row row-sm">
							<?php 
							$i=1;
							foreach($all_files as $file)
							{
								// if($i>6)
								// {
									?>
								<div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3">
									<div class="card card-file">
										<div class="dropdown-file">
											<a href="" class="dropdown-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" class="dropdown-item ShareBtnAtag" data-toggle="modal" data-target="#share_file" data-id="<?php echo $file['file_id'];?>">Share</a>
												<a href="<?php echo base_url().$file['file_path'].$file['file_name'];?>" class="dropdown-item" target="_blank">View</a>
												<a href="<?php echo base_url();?>file_manager/download_file/<?php echo $file['file_id'];?>" class="dropdown-item">Download</a>
												<!-- <a href="<?php echo base_url();?>file_manager/download_file/<?php echo $file['file_id'];?>" class="dropdown-item">Download</a> -->
												<a data-toggle="modal" data-target="#edit_file<?php echo $file['file_id'];?>" href="" class="dropdown-item"> </a>
												<a data-toggle="modal" data-target="#delete_file<?php echo $file['file_id'];?>" href="" class="dropdown-item">Delete</a>
											
											</div>
										</div>
										<a href="<?php echo base_url().$file['file_path'].$file['file_name'];?>" target="_blank">
										<div class="card-file-thumb">
											<?php
											if($file['file_type']=='pdf')
											{?>
												<i class="fa fa-file-pdf-o" style="color:red;"></i>

											<?php
											}
											else if($file['file_type']=='docx')
											{?>
												<i class="fa fa-file-word-o" style="color:#00c5fb;"></i>

											<?php
											}
											else if($file['file_type']=='png'||$file['file_type']=='jpg'||$file['file_type']=='psd')
											{?>
												<i class="fa fa-file-image-o"></i>

											<?php
											}
											else if(($file['file_type']=='xls') || ($file['file_type']=='xlsx'))
											{?>
												<i class="fa fa-file-excel-o" style="color:#3ba92e;"></i>

											<?php
											}
											else if($file['file_type']=='ppt')
											{?>
												<i class="fa fa-file-powerpoint-o" style="#D04423;"></i>

											<?php
											}
											else if($file['file_type']=='mp3')
											{?>
												<i class="fa fa-file-audio-o"></i>

											<?php
											}
											else if($file['file_type']=='mp4')
											{?>
												<i class="fa fa-file-video-o"></i>

											<?php
											}
											else if($file['file_type']=='txt')
											{?>
												<i class="fa fa-file-text-o"></i>

											<?php
											}
											else if($file['file_type']=='html')
											{?>
												<i class="fa fa-file-code-o"></i>

											<?php
											}
											?>
											
										</div>
									</a>
										<div class="card-body">
											<h6><a href=""><?php echo $file['file_name'];?></a></h6>
											<span><?php echo $file['file_size'];?></span>
										</div>
										<div class="card-footer">
											<span class="d-none d-sm-inline">Last Modified: </span><?php echo date('d M Y',strtotime($file['last_modified']));?>
										</div>
									</div>
								</div>
							
								
							<?php
								// }
							$i++;															
							}
							?>
								
								
							</div>
						</div>
					</div>			

				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<div id="add_file" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload Files</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			<!--<form method="POST" action="<?php echo base_url();?>file_manager/add_files" enctype="multipart/form-data">-->
			<?php $attributes = array('id' => 'file_submit', 'enctype'=>'multipart/form-data'); echo form_open(base_url().'file_manager/add_files',$attributes); ?>	
			<div class="form-group">
				<label>Folders <span class="text-danger">*</span></label>
				<select class="form-control" name="project" required>
					<option value="">Select Folder</option>
					<?php
					foreach($all_folders as $folder)
					{?>
					<option value="<?php echo $folder['folder_id'];?>"><?php echo ucfirst($folder['folder_name']);?></option>
					<?php	
					}
					?>
					
					
				</select>
			</div>

			<!--	<div class="form-group">
				<label>Drag and Drop or Upload File <span class="text-danger">*</span></label>
			<input class="form-control" type="file" name="files" id="files" onchange="readUrl(this)" data-title="Drag and drop a file" required>
			</div>-->
			<div class="form-group inputDnD">
			    <input type="file" class="form-control-file" name="files" id="files" accept="image/*" onchange="readUrl(this)" data-title="Drag and drop a file">
			</div>
			<div class="submit-section">
				<button class="btn btn-primary submit-btn">Submit</button>
			</div>
		</form>
		</div>
	</div>
</div>
</div>


<div id="add_folder" class="modal custom-modal fade" role="dialog">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Create Folder</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form method="POST" action="<?php echo base_url();?>file_manager/folders" >

<div class="form-group">
	<label>Folder Name<span class="text-danger">*</span></label>
	<input class="form-control" type="text" name="folder_name" id="folder_name"  required>
	<input class="form-control" type="hidden" name="folder_id" id="folder_id" value=""  required>
</div>
<div class="submit-section">
	<button class="btn btn-primary submit-btn">Submit</button>
</div>
</form>
</div>
</div>
</div>
</div>

<div id="share_file" class="modal custom-modal fade" role="dialog">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">File Sharing</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form method="POST" action="<?php echo base_url();?>file_manager/share_to_users" >
<div class="form-group">
	<input class="form-control" type="hidden" id="user_file_id" name="file_id" value="">
	<label>Share to Users <span class="text-danger">*</span></label>
	<select class="select2-option" multiple="multiple" style="width:100%;" name="user[]" id="user">
	<option value="">Select Users</option>
	<?php foreach($users as $user) { ?>
		<option value="<?php echo $user->id; ?>"><?php echo ucfirst($user->fullname); ?></option>
	<?php } ?>
	</select>
</div>
<div class="submit-section">
	<button class="btn btn-primary submit-btn">Submit</button>
</div>
</form>
</div>
</div>
</div>
</div>



<?php
foreach($all_files as $file){
$file_id=$file['file_id'];
?>	
<div id="delete_file<?php echo $file_id;?>" class="modal custom-modal fade" role="dialog">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content modal-md">
<div class="modal-header">
<h4 class="modal-title">Delete File</h4>
</div>
<div class="modal-body card-box">
	<p>Are you sure want to delete this File?</p>
	<div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
		<a href="<?php echo base_url(); ?>file_manager/delete_file/<?php echo $file_id; ?>"  class="btn btn-primary">Delete</a>
	</div>
</div>
</div>
</div>
</div>
<div id="edit_file<?php echo $file_id;?>" class="modal custom-modal fade" role="dialog">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Modify File</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form method="POST" action="<?php echo base_url();?>file_manager/edit_files/<?php echo $file_id;?>" enctype="multipart/form-data">

<div class="form-group">
	<label>Folders <span class="text-danger">*</span></label>
	<select class="form-control" name="project<?php echo $file_id;?>" required>
		<option value="">Select Folder</option>
		<?php
		foreach($all_folders as $folders)
		{?>
		<option value="<?php echo $folders['folder_id'];?>" <?php if($folders['folder_id']==$file['project_id']){ echo 'Selected';}?>><?php echo $folders['folder_name'];?></option>
		<?php	
		}
		?>
		
	</select>
</div>

<div class="form-group">
	<label>Modify File <span class="text-danger">*</span></label>
		<a>
	<?php
		if($file['file_type']=='pdf')
		{?>
			<i class="fa fa-file-pdf-o"></i>

		<?php
		}
		else if($file['file_type']=='docx')
		{?>
			<i class="fa fa-file-word-o"></i>

		<?php
		}
		else if($file['file_type']=='png'||$file['file_type']=='jpg'||$file['file_type']=='psd')
		{?>
			<i class="fa fa-file-image-o"></i>

		<?php
		}
		else if(($file['file_type']=='xls') || ($file['file_type']=='xlsx'))
		{?>
			<i class="fa fa-file-excel-o"></i>

		<?php
		}
		else if($file['file_type']=='ppt')
		{?>
			<i class="fa fa-file-powerpoint-o"></i>

		<?php
		}
		else if($file['file_type']=='mp3')
		{?>
			<i class="fa fa-file-audio-o"></i>

		<?php
		}
		else if($file['file_type']=='mp4')
		{?>
			<i class="fa fa-file-video-o"></i>

		<?php
		}
		else if($file['file_type']=='txt')
		{?>
			<i class="fa fa-file-text-o"></i>

		<?php
		}
		else if($file['file_type']=='html')
		{?>
			<i class="fa fa-file-code-o"></i>

		<?php
		}
		echo $file['file_name'];
		?>
		
		</a>
	
								
								
								
								<!--<span class="btn-file"><input type="file" class="upload"><i class="fa fa-upload"></i></span>-->
											<input type="hidden" name="exist_file<?php echo $file_id;?>" value="<?php echo $file['file_name'];?>">
								<!-- <input class="form-control" type="file" name="files<?php echo $file_id;?>" > -->

								<div class="form-group inputDnD">
							        <input type="file" class="form-control-file" name="files<?php echo $file_id;?>" id="files" accept="image/*" onchange="readUrl(this)" data-title="Drag and drop a file">
							    </div>


							</div>
							<div class="submit-section">
								<button class="btn btn-primary submit-btn">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
			
	<?php	

	}
?>
		
		
</div>
<script type="text/javascript">
$("#categoryorder").sortable({ 
	opacity: 0.6, 
	cursor: 'move', 
	scrollSensitivity: 40, 
	update: function(){$('#message').html('Changes not saved');
	}
});

$('#button').click(function(event){
	var order = $("#categoryorder").sortable("serialize");
	$('#message').html('Saving changes..');
	alert('#message');
	//$.post("update_displayorder.php",order,function(theResponse){
		//	$('#message').html(theResponse);
		//	});
	//event.preventDefault();
});
</script>
