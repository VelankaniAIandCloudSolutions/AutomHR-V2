<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content p-t-10">
	<div class="page-header">
	<div class="row">
		<div class="col-8">
			<h4 class="page-title"><?php echo lang('jobs_list')?></h4>
			 <ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				 <li class="breadcrumb-item">Manage Resumes</li>
				
				</ul> 
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn back-btn" href="<?=base_url()?>candidates/all_jobs"><i class="fa fa-list"></i> <?php echo lang('job_grid_view');?></a>
	      </div>
	</div>
</div>
<form action="<?php echo site_url('candidates/all_jobs_list');?>" method="post" id="search_alljobs_filter_list">
	<div class="card-box">
	<div class="row filter-row">
		<div class="col-sm-6 col-12 col-md-3">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('experience_level'); ?></label>
				<select class="select floating form-control" id="experience_level" name="experience_level" style="padding: 14px 9px 0px;"> 
					<option value=""><?php echo lang('select_experience_level');?></option>
					<?php foreach($experience_level as $key=>$experience){
						?><option value="<?php echo $experience->id;?>" <?php if($this->input->post('experience_level')  == $experience->id){ echo "selected";}?>><?php echo $experience->experience_level;?></option><?php 
					}?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('job_category'); ?></label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"
				required> 
					<option value="" ><?php echo lang('select_category')?></option>
					<?php foreach($job_categories as $key => $category){
						?>
						<option value="<?php echo $category->deptid;?>" <?php if($this->input->post('department_id') == $category->deptid){ echo 'selected';}?>><?php echo $category->deptname;?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('job_country');?></label>
				<select class="select floating form-control" id="country_id" name="country_id" style="padding: 14px 9px 0px;"> <option value=""><?php echo lang('select_country'); ?></option>
					<?php foreach($countries as $key => $country)
					{ ?>
						<option value="<?php echo $country->id;?>" <?php if($this->input->post('country_id') == $country->id){ echo 'selected';}?>><?php echo $country->value?></option>
						<?php
					} ?>
				</select>
			</div>
		</div> 

		<div class="col-sm-6 col-12 col-md-3">  
			<div class="form-group form-focus m-b-5">
				<label class="control-label"><?php echo lang('enter_keywords');?></label>
				<input type="text" class="form-control floating" id="keywords"  name="keywords" value="<?php echo $this->input->post('keywords');?>">
			</div>
		</div>

		<div class="col-sm-6 col-6 col-md-3">  
			<button  type="submit" name="search" value="search" id="employee_search_btn" onclick="search_filter_submit()" class="btn btn-success btn-block btn-searchEmployee btn-circle m-b-10"> <?php echo lang('search');?> </button>  
			<a href="#" data-toggle="collapse" data-target="#advance_search" class="badge bg-primary text-white m-b-10" style="display:inline-block;"><?php echo lang('advanced_search');?></a>
			<a href="<?php echo site_url('candidates/all_jobs_list');?>"  class="badge bg-danger text-white m-b-10" style="display:inline-block;"><?php echo lang('clear') ?></a>
		</div>  
	</div>
</div>
</form>

	<div class="row filter-row">
		<div class="col-sm-12 col-12 col-md-12">  
			<!-- <a href="#" data-toggle="collapse" data-target="#advance_search" class="text-info">Advanced search</a> -->
			<form action="<?php echo site_url('candidates/all_jobs_list'); ?>" method="post" id="advance_search_user_list">

			<div id="advance_search" class="collapse <?php if($this->input->post('advance_search')){ echo 'in';}?>">
				<div class="card-box">
				<div class="row">
					<div class="col-lg-4">
						<div class="form-group form-focus m-b-10">
							<label class="control-label"><?php echo lang('enter_keywords')?></label>
							<input type="text" class="form-control floating" value="<?php echo $this->input->post('keywords');?>" name="keywords">
						</div>
						<!-- <div class="form-group form-focus m-b-10">
							<label class="control-label">Country/Region</label>
							<input type="text" class="form-control floating">
						</div> -->
						<div class="form-group form-focus m-b-10">
							<label class="control-label"><?php echo lang('job_category');?></label>
		<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;" onchange="get_position_list(this.value)" > 
			<option value="" ><?php echo lang('select_category'); ?></option>
			<?php foreach($job_categories as $key => $category){
				?>
				<option value="<?php echo $category->deptid;?>" <?php if($this->input->post('department_id')  == $category->deptid){ echo "selected";}?> ><?php echo $category->deptname;?></option>
			<?php } ?>
		</select>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group form-focus select-focus m-b-10" style="width:100%;">
							<label class="control-label"><?php echo lang('location');?></label>
							<select class="select floating form-control" id="country_id" name="country_id" style="padding: 14px 9px 0px;"> 
								<option value=""><?php echo lang('select_country');?></option>
					<?php foreach($countries as $key => $country)
					{ ?>
						<option value="<?php echo $country->id;?>" <?php if($this->input->post('country_id')  == $country->id){ echo "selected";}?>><?php echo $country->value?></option>
						<?php
					} ?>
							</select>
						</div>
						<div class="form-group form-focus select-focus m-b-10" style="width:100%;">
							<label class="control-label"><?php echo lang('position_type') ?></label>
							<select class="select floating form-control" id="positions" name="position_id" style="padding: 14px 9px 0px;"> 
								<option value="" selected="selected"><?php echo lang('select_position');?></option>
								<?php  foreach($designation as $key=>$design){
									?><option value="<?php echo $design->id;?>" <?php if($this->input->post('position_id')==$design->id){ echo "selected";}?>><?php echo $design->designation; ?></option><?php 
								} ?>
							</select>
						</div>
						<!-- <div class="form-group form-focus select-focus m-b-10" style="width:100%;">
							<label class="control-label">Language</label>
							<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
								<option value="" selected="selected">All</option>
								<option value="1">English (United States)</option>
								<option value="2">Korean</option>
								<option value="3">Japanese</option>
							</select>
						</div> -->
					</div>
					<div class="col-lg-4">
						<div class="form-group form-focus select-focus m-b-10" style="width:100%;">
							<label class="control-label"><?php echo lang('employment_type');?></label>
							<select class="select floating form-control" id="job_type_id" name="job_type_id" style="padding: 14px 9px 0px;">
							<option value=""><?php echo lang('select_employment_type');?></option> 
								<?php foreach($job_types as $type){
									?>
									<option value="<?php echo $type->id;?>" <?php if($this->input->post('job_type_id')==$type->id){ echo "selected";}?>><?php echo $type->job_type;?></option>
								
									<?php 
								} ?>
								
							</select>
						</div>
						<!-- <div class="form-group form-focus select-focus m-b-10" style="width:100%;">
							<label class="control-label">Contract type</label>
							<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
								<option value="" selected="selected">Special Terms</option>
								<option value="1">Regular</option>
								<option value="2"><?php echo lang('internship');?></option>
							</select>
						</div> -->
						<input type="submit" id="advanced_search_btn"  class="btn btn-success btn-block btn-searchEmployee btn-circle" value="<?php echo lang('search'); ?>" name="advance_search"> 
					</div>
				</div>
			</div>
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
						<th><?php  echo lang('job_title');?></th>
						<th><?php echo lang('department');?></th>
						<th><?php echo lang('start_date')?></th>
						<th><?php echo lang('expire_date')?></th>
						<th class="text-center"><?php  echo lang('job_types');?></th>
						<th class="text-center"><?php echo lang('status');?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					foreach($jobs_list as $job){
						?>
					<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $job->id;?>"><?php echo $job->job_title;?></a></td>
						<td><?php echo $job->deptname;?></td>
						<td><?php echo date('d M Y',strtotime($job->start_date));?></td>
						<td><?php echo date('d M Y',strtotime($job->expired_date));?></td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#">
								<i class="fa fa-dot-circle-o text-danger"></i><?php echo $job->job_type;?>
								</a>
							</div>
						</td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#" >
								<i class="fa fa-dot-circle-o text-danger"></i><?php if($job->status==0){ echo 'Open';}else{ echo "Close";}?> 
								</a>
							</div>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>