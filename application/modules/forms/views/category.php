<div class="content container-fluid">
					<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col">
				<h3 class="page-title">Forms</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Dashboard</a></li>
					<li class="breadcrumb-item active"><a href="<?php echo base_url();?>forms">Forms</a></li>
					<li class="breadcrumb-item active">Category</li>
				</ul>
			</div>
			<?php if(($this->session->userdata('role_id') == 1) || ( ($this->session->userdata('user_type_name') == 'company_admin'))){ ?>
			<div class="col-auto float-right ml-auto">
				<a href="#"  data-toggle="modal" data-target="#add_category"  class="btn add-btn"><?php echo lang('add_category'); ?>
				</a>
			</div>
			<?php }?>
		</div>
	</div>
	<!-- /Page Header -->
	<div class="row">
		<!-- <div class="col-12"> -->
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-hover custom-table m-b-0 AppendDataTables">
						<thead>
							<tr>
								<th>#</th>
								<th>Category Name</th>
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 											
							$i = 1;
							if(isset($categories) && !empty($categories)){
								foreach($categories as $category){		
							?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $category->category_name; ?></td>
								<td>
									<?php if($category->status == 1) { ?>
										<a href="<?=base_url()?>forms/changeCategoryStatus/<?=$category->id?>" data-toggle="ajaxModal"><span class="badge bg-success text-white">Active</span></a>
									<?php } else { ?>
										<a href="<?=base_url()?>forms/changeCategoryStatus/<?=$category->id?>" data-toggle="ajaxModal"><span class="badge bg-danger text-white">Inactive</span></a>
										
									<?php } ?>
								</td>
								<td class="text-center">
									<div class="dropdown">
										<a data-toggle="dropdown" class="action-icon" href="#" aria-expanded="true"><i class="fa fa-ellipsis-v"></i></a>
										<div class="dropdown-menu float-right">									
											<a class="dropdown-item" href="<?php echo base_url()?>forms/edit_category/<?=$category->id?>" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i>Edit</a>
											
											<a class="dropdown-item" href="<?php echo base_url()?>forms/delete_category/<?=$category->id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>
										
										</div>
									</div>
								</td>
							</tr>
						<?php $i++; } } else{ ?>
							<th><td colspan="8">No Records Found</td></th>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		<!-- </div> -->
	</div>
</div>

<div id="add_category" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo lang('add_category'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php $attributes = array('id' => 'category_submit'); echo form_open(base_url().'forms/addCategory',$attributes); ?>
					<div class="form-group">
						<label><?php echo lang('category_name'); ?> <span class="text-danger">*</span><span id="already_username" style="display: none;color:red;">Category Name Already Exists</span></label>
						<input class="form-control" type="text" name="category_name" id="category_name">
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
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="submit_category"><?php echo lang('submit'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>	

					