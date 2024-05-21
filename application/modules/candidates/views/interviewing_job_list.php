<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content p-t-10">
	<div class="page-header">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title">Interviewing Jobs List</h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				 <li class="breadcrumb-item">Manage Resumes</li>
				
				</ul> 
		</div>
	</div>
</div>
	<!-- <div class="card-box">
		<ul class="nav nav-tabs nav-tabs-solid page-tabs">
			
			<li><a href="<?php echo base_url(); ?>jobs/dashboard">Dashboard</a></li>
			<li><a href="<?php echo base_url(); ?>jobs/manage">Manage Jobs</a></li>
			<li class="active"><a href="<?php echo base_url(); ?>jobs/manage_resumes">Manage Resumes</a></li>
			<li><a href="<?php echo base_url(); ?>jobs/shortlist_candidates">Shortlist Candidates</a></li>
		</ul>
		</div> -->
	<div class="row">
		<?php foreach ($jobs as $key => $value) {	//print_r($value);exit();	// foreach starts 
			?>
		<div class="col-md-6">
			<!-- <a class="job-list" href="<?=base_url()?>jobs/jobview/<?=$value->id?>"> -->
			<div class="job-list">
				<div class="job-list-det">
					<div class="job-list-desc">
						<h3 class="job-list-title"><?=ucfirst($value->title);?></h3>
						<h4 class="job-department"><?=ucfirst($jtype[$value->job_type]);?></h4>
					</div>
					<div class="job-type-info">
						<span >
						<a class='job-types' href="<?=base_url()?>jobs/apply/<?=$value->id?>/<?=$value->job_type?>"><?php echo lang('apply');?></a>
						</span>
					</div>
				</div>
				<div class="job-list-footer">
					<ul>
						<!-- <li><i class="fa fa-map-signs"></i> California</li> -->
						<li><i class="fa fa-money"></i> <?=$value->salary;?></li>
						<li><i class="fa fa-clock-o"></i> <?=Jobs::time_elapsed_string($value->created); ?></li>
					</ul>
				</div>
			</div>
			<!-- </a> -->
		</div>
		<?php } // foreach end ?>	 
	</div>
	<div class="card-box">
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 datatable">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo lang('job_title');?></th>
						<th><?php echo lang('department')?></th>
						<th class="text-center"><?php echo lang('job_type')?></th>
						<th class="text-center"><?php echo lang('aptitude_test')?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td><a href="<?php echo base_url(); ?>candidates/job_view">Web Developer</a></td>
						<td>Development</td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php echo lang('full_time');?>
								</a>
							</div>
						</td>
						<td class="text-center">
							<a href="<?=base_url()?>candidates/aptitude" class="btn btn-primary aptitude-btn"><span>
								<?php echo lang('click_here');?></span></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>