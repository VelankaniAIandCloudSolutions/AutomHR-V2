<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title m-b-0"><?php echo lang('recruiting_process');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				<li class="breadcrumb-item">Manage Resumes</li>
			</ul>
		</div>
	</div>
</div>
	<div class="card-box">
		<ul class="nav nav-tabs nav-tabs-solid page-tabs">
			<li><a href="<?php echo base_url(); ?>jobs/dashboard">Dashboard</a></li>
			<li><a href="<?php echo base_url(); ?>jobs/manage">Manage Jobs</a></li>
			<li class="active"><a href="<?php echo base_url(); ?>jobs/manage_resumes">Manage Resumes</a></li>
			<li><a href="<?php echo base_url(); ?>jobs/shortlist_candidates">Shortlist Candidates</a></li>
		</ul>
	</div>
	<div class="row">
		
						<a class='job-types' href="<?=base_url()?>jobs/apply/<?=$value->id?>/<?=$value->job_type?>"><?php echo lang('apply');?></a>
	</div>
	<div class="card-box">
		<div class="row">
			<div class="col-sm-12 col-12">
				<h4 class="page-title">Manage Resumes</h4>
			</div>
			<!-- <div class="col-sm-7 col-7 text-right m-b-30">
				<a href="<?php echo base_url(); ?>jobs/add" class="btn add-btn"> <?php echo lang('add_jobs');?></a>
				</div> -->						
		</div>
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 datatable">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th><?php echo lang('job_title');?></th>
						<th>Department</th>
						<th>Start Date</th>
						<th>Expire Date</th>
						<th class="text-center"><?php echo lang('job_type');?></th>
						<th class="text-center">Status</th>
						<th class="text-center">Resume</th>
						<th class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>John Doe</td>
						<td><a href="job-details.html">Web Developer</a></td>
						<td>Development</td>
						<td>3 Mar 2019</td>
						<td>31 May 2019</td>
						<td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php echo lang('full_time');?>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> <?php echo lang('full_time');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> <?php echo lang('part_time');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> <?php echo lang('internship');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> <?php echo lang('temporary');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> Other</a>
								</div>
							</div>
						</td>
						<td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> Open
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Open</a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Closed</a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Cancelled</a>
								</div>
							</div>
						</td>
						<td><a href="#" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
						<td class="text-right">
							<div class="dropdown dropdown-action">
								<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a href="<?php echo base_url(); ?>jobs/edit" class="dropdown-item"><i class="fa fa-pencil m-r-5"></i> Edit</a>
									<a href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>2</td>
						<td>Maria Sam</td>
						<td><a href="job-details.html">Web Designer</a></td>
						<td>Designing</td>
						<td>3 Mar 2019</td>
						<td>31 May 2019</td>
						<td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-success"></i> <?php echo lang('part_time');?>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> <?php echo lang('full_time');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> <?php echo lang('part_time');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> <?php echo lang('internship');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> <?php echo lang('temporary');?></a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> Other</a>
								</div>
							</div>
						</td>
						<td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-success"></i> Closed
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Open</a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Closed</a>
									<a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Cancelled</a>
								</div>
							</div>
						</td>
						<td><a href="#" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
						<td class="text-right">
							<div class="dropdown dropdown-action">
								<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<li><a href="<?php echo base_url(); ?>jobs/edit" class="dropdown-item"><i class="fa fa-pencil m-r-5"></i> Edit</a>
									<li><a href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- Delete Job Modal -->
<div class="modal custom-modal fade" id="delete_job" role="dialog">
	<div class="modal-dialog  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3>Delete Job</h3>
					<p>Are you sure want to delete?</p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-sm-6">
							<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
						</div>
						<div class="col-sm-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Job Modal -->