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
							<?php if(App::is_permit('menu_forms','create')){?>
								<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_form"><i class="fa fa-plus"></i> Add Form</a>
							<?php } ?>
								<a href="<?php echo base_url(); ?>forms/category_list" class="btn add-btn mr-2"><?php echo lang('category'); ?></a>
							</div>

						</div>

						<div class="row">
							<div class="col-auto float-right ml-auto">
								<a href="#" class="list-view btn btn-link active" title="List View"><i class="fa fa-bars"></i></a>
								<a href="<?php echo base_url(); ?>forms/em_form" class="grid-view btn btn-link" title="Grid View"><i class="fa fa-th"></i></a>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					<!-- Content Starts -->
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-striped custom-table mb-0 AppendDataTables">
									<thead>
										<tr>
											<th>No</th>
											<th>Form Name</th>
											<th>Category</th>
											<th>Keyword</th>
											<th>File</th>
											<?php if(App::is_permit('menu_forms','write') || App::is_permit('menu_forms','delete')){?>
												<th class="text-right">Actions</th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
									<?php
									$i=1;
									foreach($all_forms as $form)
									{

										$category_details = $this->db->get_where('forms_category',array('id'=>$form['category']))->row_array();
									?>
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $form['form_name'];?></td>
											<td><?php echo ucfirst($category_details['category_name']);?></td>
											<td><?php echo wordwrap($form['keywords'], 35, '<br />', true);?></td>
											<td><a href="<?php echo base_url()?>forms/detail_view/<?php echo $form['form_id'];?>"><?php echo $form['file'];?></a></td>
											<?php if(App::is_permit('menu_forms','write') || App::is_permit('menu_forms','delete')){?>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<?php if(App::is_permit('menu_forms','write')){?>
														<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_form<?php echo $form['form_id'];?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<?php } ?>
													<?php if(App::is_permit('menu_forms','delete')){?>
														<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_form<?php echo $form['form_id'];?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													<?php } ?>
													</div>
												</div>
											</td>
										</tr>
									<?php } ?>
									<?php
									$i++;	
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /Content End -->
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
										echo $this->db->last_query(); 
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
		