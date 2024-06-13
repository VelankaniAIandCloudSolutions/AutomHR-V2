<div class="content container-fluid">
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col">
								<h3 class="page-title">Forms</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
									<li class="breadcrumb-item active">Forms</li>
								</ul>
							</div>
							<div class="col-auto float-right ml-auto">
							<?php if($this->session->userdata('role_id') == 1){ ?>
								<?php if(App::is_permit('menu_forms','create')){?>
								<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_form"><i class="fa fa-plus"></i> Add Form</a>
							<?php } ?>
								<a href="<?php echo base_url(); ?>forms/category_list" class="btn add-btn mr-2"><?php echo lang('category'); ?></a>
							<?php }?>
							</div>
						</div>
						<?php if($this->session->userdata('role_id') == 1){ ?>
							<div class="row">
								<div class="col-auto float-right ml-auto">
									<a href="<?php echo base_url(); ?>forms/ad_list" class="list-view btn btn-link active" title="List View"><i class="fa fa-bars"></i></a>
									<a href="#" class="grid-view btn btn-link" title="Grid View"><i class="fa fa-th"></i></a>
								</div>
							</div>
						<?php } ?>
					</div>
					<!-- /Page Header -->
					<!-- Content Starts -->
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<input type="search" name="search" placeholder="search"  id="search_forms" class="form-control search_forms">
							</div>
						</div>
					</div>
					<div class="row" id="search_content">
					<?php 
					foreach($all_forms as $form)
					{
					?>
						<div class="col-lg-4">
							<div class="card">
								<div class="card-header text-center">
									<h4 class="card-title mb-0 d-inline-block">
										<a href="<?php echo base_url()?>forms/detail_view/<?php echo $form['form_id'];?>"><?php echo $form['form_name'];?></a>
									</h4>
									<?php if(App::is_permit('menu_forms','write') || App::is_permit('menu_forms','delete')){?>
									<div class="dropdown dropdown-action d-inline-block float-right">
										<a href="#" class="action-icon p-0" data-toggle="dropdown" aria-expanded="false"><i class="material-icons m-0">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
										<?php if(App::is_permit('menu_forms','write')){?>
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_form<?php echo $form['form_id'];?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
										<?php } ?>
										<?php if(App::is_permit('menu_forms','delete')){?>
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_form<?php echo $form['form_id'];?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
										<?php } ?>
										</div>
									</div>
								<?php } ?>
								</div>
								
								<div class="card-body">
									<div class="card-file-thumb">
										<?php
										$file_t=explode('.',$form['file']);
										$file_type=$file_t[1];
										if($file_type=='pdf')
										{?>
											<i class="fa fa-file-pdf-o"></i>

										<?php
										}
										else if(($file_type=='docx')|| ($file_type=='doc'))
										{?>
											<i class="fa fa-file-word-o"></i>

										<?php
										}
										else if($file_type=='PNG'||$file_type=='JPG'||$file_type=='png'||$file_type=='jpg'||$file_type=='psd')
										{?>
											<!-- <i class="fa fa-file-image-o"></i> -->
											<img src="<?php echo base_url().'uploads/files/'.$form['file']; ?>" style="height: 90%;width: 30%;">

										<?php
										}
										else if(($file_type=='xls') ||($file_type=='xlsx'))
										{?>
											<i class="fa fa-file-excel-o"></i>

										<?php
										}
										else if($file_type=='ppt')
										{?>
											<i class="fa fa-file-powerpoint-o"></i>

										<?php
										}
										else if($file_type=='mp3')
										{?>
											<i class="fa fa-file-audio-o"></i>

										<?php
										}
										else if(($file_type=='mp4') || ($file_type=='webm'))
										{?>
											<i class="fa fa-file-video-o"></i>

										<?php
										}
										else if($file_type=='txt')
										{?>
											<i class="fa fa-file-text-o"></i>

										<?php
										}
										else if($file_type=='html')
										{?>
											<i class="fa fa-file-code-o"></i>

										<?php
										}
										?>
										
									</div>
									<center><?php echo $form['file'];?><center>
								</div>
								<?php if(App::is_permit('menu_forms','read')){?>
								<div class="card-footer text-center">
									<a href="<?php echo base_url();?>forms/download_file/<?php echo $form['form_id'];?>"  class="btn btn-primary submit-btn download">Download</a>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php
					}
						?>
						
					</div>
					<!-- /Content End -->
				</div>




				<div id="add_form" class="modal custom-modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add Forms</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" action="<?php echo base_url();?>forms/add_forms" enctype="multipart/form-data">
							<div class="form-group">
								<label>Form Name <span class="text-danger">*</span></label>
								<input class="form-control" name="form_name" type="text" required>
							</div>
							<div class="form-group">
								<label>Category <span class="text-danger">*</span></label>
								<select class="form-control" name="category" required>
									<option value="" selected disabled>Select Category</option>
									<?php 
										$all_form_categories = $this->db->get_where('forms_category',array('status !='=>'2'))->result_array(); 
										if(!empty($all_form_categories)){ 
											foreach($all_form_categories as $forms_category){
											?>
												<option value="<?php echo $forms_category['id']; ?>"><?php echo ucfirst($forms_category['category_name']); ?></option>
									<?php }	}else{ ?>
										<option value="" disabled>No category</option>
									<?php	}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><?=lang('branch')?> <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
									<option value="">Select</option>
									<?php if(!empty($branches))	{
										foreach ($branches as $branch1){ ?>
											<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label>Keywords <span class="text-danger">*</span></label>
								<textarea rows="4" class="form-control" name="keywords" required></textarea>
							</div>
							<div class="form-group">
								<label>Upload File <span class="text-danger">*</span></label>
								<input class="form-control attachments" type="file" name="attachments" required>
								<span style="color:red" class="error_alert"><span>
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
	foreach($all_forms as $mail){
		$form_id=$mail['form_id'];
	?>	
		<div id="delete_form<?php echo $form_id;?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Delete Form</h4>
						</div>
							<div class="modal-body card-box">
								<p>Are you sure want to delete this Form?</p>
								<div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
									<a href="<?php echo base_url(); ?>forms/delete_form/<?php echo $form_id; ?>"  class="btn btn-primary">Delete</a>
								</div>
							</div>
					</div>
				</div>
			</div>
			<div id="edit_form<?php echo $form_id;?>" class="modal custom-modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Forms</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" action="<?php echo base_url();?>forms/edit_forms/<?php echo $form_id;?>" enctype="multipart/form-data">
							<div class="form-group">
								<label>Form Name <span class="text-danger">*</span></label>
								<input class="form-control" name="form_name<?php echo $form_id;?>" type="text" value="<?php echo $mail['form_name'];?>" required>
							</div>
							<div class="form-group">
								<label>Category <span class="text-danger">*</span></label>
								<select class="form-control" name="category<?php echo $form_id;?>" required>
									<option value="" disabled>Select Category</option>
									<?php 
										$all_form_categories = $this->db->get_where('forms_category',array('status !='=>'2'))->result_array(); 
										if(!empty($all_form_categories)){ 
											foreach($all_form_categories as $forms_category){
											?>
												<option value="<?php echo $forms_category['id']; ?>" <?php if($mail['category']==$forms_category['id']){ echo 'selected';}?>><?php echo ucfirst($forms_category['category_name']); ?></option>
									<?php }	}else{ ?>
										<option value="" disabled>No category</option>
									<?php	}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><?=lang('branch')?> <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
									<option value="">Select</option>
									<?php if(!empty($branches))	{
										foreach ($branches as $branch1){ ?>
											<option value="<?=$branch1['branch_id']?>" <?php if($branch1['branch_id']==$mail['branch_id']){ echo 'selected';}?>><?=$branch1['branch_name']?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label>Keywords <span class="text-danger">*</span></label>
								<textarea rows="4" class="form-control" name="keywords<?php echo $form_id;?>" required><?php echo $mail['keywords'];?></textarea>
							</div>
							<div class="form-group">
								<label class="d-block">Upload File <span class="text-danger " >*</span></label>
								<span class="form_image<?php echo $form_id;?>"><i class="fa fa-document"></i><?php echo $mail['file'];?> <span class="image_remove <?php echo !empty($mail['file'])?'':'hide';?>" data-id="<?php echo $form_id;?>"><i class="fa fa-close"></i></span> </span>
								<input type="hidden" value="<?php echo $mail['file'];?>" name="exist_file<?php echo $form_id;?>" id="form_file<?php echo $form_id;?>">
								<input class="form-control attachments" type="file" name="attachments<?php echo $form_id;?>">
								<span style="color:red" class="error_alert"><span>
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