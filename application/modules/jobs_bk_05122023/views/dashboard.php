
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-12">
							<h4 class="page-title m-b-0"><?php echo lang('recruiting_process');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
						        <li class="breadcrumb-item"><?php echo lang('recruiting_dashboard');?></li>
			</ul>
		</div>
	</div>
</div>
				<?php //$this->load->view('sub_menus');?>
	
	<div class="row">
		
	</div>
	<div class="row admin-dash">
		<div class="col-md-6 col-sm-6 col-lg-3">
			<div class="dash-widget clearfix card-box">
				<span class="dash-widget-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('jobs');?></span>
					<h3><?php echo $total_jobs;?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-lg-3">
			<div class="dash-widget clearfix card-box">
				<span class="dash-widget-icon"><i class="fa fa-users"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('job_seekers');?></span>
					<h3><?php echo $total_candidates;?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-lg-3">
			<div class="dash-widget clearfix card-box">
				<span class="dash-widget-icon"><i class="fa fa-user"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('offered')?></span>
					<h3><?php echo array_sum($offered_users);?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-lg-3">
			<div class="dash-widget clearfix card-box">
				<span class="dash-widget-icon"><i class="fa fa-clipboard icon"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('job_applications');?></span>
					<h3><?php echo count($applicants_list); ?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="card-box chart-div">
				<h3 class="card-title"><?php echo lang('overview')?></h3>
				<div class="row">
					<div class="col-md-12 m-b-10">
						<canvas id="canvas_rec"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card-box">
				<h3 class="card-title"><?php echo lang('latest_jobs');?></h3>
				<div class="list-group">
					<?php foreach($latest_jobs as $key => $latest){?>
				<a href="<?php if(App::is_permit('recruiting_dashboard','read')){?><?php echo base_url(); ?>jobs/view_jobs/<?php echo $latest['id'];?><?php }else{ echo '#';}?>" class="list-group-item"><?php echo $latest['job_title'];?><span class="float-right" style="font-size:12px; color: #a1a1a1;"><?php if($latest['days']>0){ echo $latest['days'].' days ago';}else{ echo $latest['hour'].' hours ago';}?></span></a>
				<?php
					} ?>
					
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card-box">
				<h3 class="card-title"><?php echo lang('applicants_list');?></h3>
				<div class="table-responsive">
					<table class="table table-striped custom-table mb-0 AppendDataTables">
						<thead>
							<tr>
								<th>#</th>
								<th><?php echo lang('name');?></th>
								<th><?php echo lang('job_title');?></th>
								<th><?php echo lang('department');?></th>
								<th><?php echo lang('start_date');?></th>
								<th><?php echo lang('expire_date');?></th>
								<th class="text-center"><?php echo lang('job_types');?></th>
								<th class="text-center"><?php echo lang('status');?></th>
								<th class="text-center"><?php echo lang('resume');?></th>
								<!-- <th class="text-right"><?php echo lang('action')?></th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
							$i =1;
							foreach ($applicants_list as $key => $list) {
							 ?>
							<tr>
								<td><?php echo $i++;?></td>
								<td class="text-capitalize"><?php echo $list->first_name.''.$list->last_name;?></td>
								<td><a href="<?php if(App::is_permit('recruiting_dashboard','read')){?><?php echo site_url('jobs/view_jobs/').$list->id;?><?php }else{ echo '#';}?>"><?php echo $list->job_title;?></a></td>
								<td><?php echo $list->deptname;?></td>
								<td><?php echo date('d M Y',strtotime($list->start_date));?></td>
								<td><?php echo date('d M Y',strtotime($list->expired_date));?></td>
								<td class="text-center">
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-dot-circle-o text-danger"></i> <?php echo $list->job_type;?>
										</a>
										<!-- <ul class="dropdown-menu dropdown-menu-right">
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Full Time</a></li>
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Part Time</a></li>
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Internship</a></li>
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> Temporary</a></li>
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-warning"></i> Other</a></li>
										</ul> -->
									</div>
								</td>
								<td class="text-center">
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-dot-circle-o text-danger"></i> <?php 
										if($list->job_status==0){ echo lang('open');}else{
											echo lang('close');
										}
										?>
										</a>
										<!-- <ul class="dropdown-menu dropdown-menu-right">
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Open</a></li>
											<li><a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Closed</a></li>
										</ul> -->
									</div>
								</td>
								<td><a href="<?php echo base_url().'images/resume/'.$list->file_name;?>" class="btn btn-sm btn-primary" download><i class="fa fa-download"></i> <?php echo lang('download'); ?></a></td>
								<!-- <td class="text-right">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											<li><a href="<?php echo base_url(); ?>jobs/edit" class="dropdown-item"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
											<li><a href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
										</ul>
									</div>
								</td> -->
							</tr>
						<?php } ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>