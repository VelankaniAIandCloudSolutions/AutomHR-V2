
<div class="content p-t-10">
	<!-- <div class="row">
		<div class="col-8">
			<h4 class="page-title">All Jobs</h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li><a href="<?php echo base_url(); ?>">Home</a></li>
				<li><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				 <li>Manage Resumes</li>
				
				</ul>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn back-btn" href="<?=base_url()?>candidates/all_jobs_user_list"><i class="fa fa-list"></i> List View</a>
	      </div>
	</div> -->
	<div class="page-header">
	<div class="row">
		<div class="col-8">
			<h4 class="page-title"><?php echo lang('job_portals');?></h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item active"><?php echo lang('job_portals');?></li>
			</ul>
		</div>
		</div>
		<!-- <div class="col-sm-4  text-right m-b-20">     
	          <a class="btn back-btn" href="<?=base_url()?>candidates/all_jobs_user_list"><i class="fa fa-list"></i> List View</a>
	      </div> -->

	</div>

	
<form action="<?php echo site_url('candidates/all_jobs_user');?>" method="post" id="search_user_filter">
	<div class="row filter-row">
		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('experience_level');?></label>
				<select class="select floating form-control" id="experience_level" name="experience_level" style="padding: 14px 9px 0px;"> 
					<option value=""><?php echo lang('select_experience_level');?></option>
					<?php foreach($experience_level as $key=>$experience){
						?><option value="<?php echo $experience->id;?>" <?php if($this->input->post('experience_level')  == $experience->id){ echo "selected";}?>><?php echo $experience->experience_level;?></option><?php 
					}?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('job_category');?></label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" ><?php echo lang('select_category'); ?></option>
					<?php foreach($job_categories as $key => $category){
						?>
						<option value="<?php echo $category->deptid;?>" <?php if($this->input->post('department_id')  == $category->deptid){ echo "selected";}?>><?php echo $category->deptname;?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('job_country')?></label>
				<select class="select floating form-control" id="country_id" name="country_id" style="padding: 14px 9px 0px;"> 
					<option value=""><?php echo lang('select_country');?></option>
					<?php foreach($countries as $key => $country)
					{ ?>
						<option value="<?php echo $country->id;?>" <?php if($this->input->post('country_id')  == $country->id){ echo "selected";}?>><?php echo $country->value?></option>
						<?php
					} ?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3"> 
			<div class="form-group form-focus m-b-5">
				<label class="control-label"><?php echo lang('enter_keywords')?></label>
				<input type="text" class="form-control floating" name="keywords" id="keywords" value="<?php echo $this->input->post('keywords');?>">
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3">  
			<button type="submit" name="search" id="employee_search_btn"  class="btn btn-success btn-block btn-searchEmployee btn-circle m-b-10" value="search" onclick="search_filter_submit()"> <?php echo lang('search')?> </button>  
			<div class="float-right"><a href="#" data-toggle="collapse" data-target="#advance_search" class="text-white badge bg-info" style="display:inline-block; padding:5px 10px;"><?php echo lang('advanced_search');?></a>
			<a style="display:inline-block;padding:5px 10px;" class="badge bg-danger text-white" href="#"><?php echo lang('clear');?></a></div>
		</div>  
	</div>
</form>


	<div class="row filter-row m-b-20">
		<div class="col-sm-12 col-12 col-md-12"> 
			<!-- <a href="#" data-toggle="collapse" data-target="#advance_search" class="text-info">Advanced search</a> -->

				<form action="<?php echo site_url('candidates/all_jobs_user'); ?>" method="post" id="advance_search_all_user">
			<div id="advance_search" class="collapse <?php if($this->input->post('advance_search')){ echo 'in';}?> m-t-20">
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
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;" onchange="get_position_list(this.value)"> 
					<option value="" ><?php echo lang('select_category'); ?></option>
					<?php foreach($job_categories as $key => $category){
						?>
						<option value="<?php echo $category->deptid;?>" <?php if($this->input->post('department_id')  == $category->deptid){ echo "selected";}?>><?php echo $category->deptname;?></option>
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
									?><option value="<?php echo $design->id;?>" <?php if($this->input->post('position_id')  == $design->id){ echo "selected";}?>><?php echo $design->designation; ?></option><?php 
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
									<option value="<?php echo $type->id;?>" <?php if($this->input->post('job_type_id')==$type->id){ echo "selected";} ?>><?php echo $type->job_type;?></option>
								
									<?php 
								} ?>
								
							</select>
						</div>
						<!-- <div class="form-group form-focus select-focus m-b-10" style="width:100%;">
							<label class="control-label">Contract type</label>
							<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
								<option value="" selected="selected">Special Terms</option>
								<option value="1">Regular</option>
								<option value="2">Internship</option>
							</select>
						</div> -->
						<!-- <a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee btn-circle"> <?php echo lang('search'); ?> </a>  --> 
						<input type="submit" id="advanced_search_btn"  class="btn btn-success btn-block btn-searchEmployee btn-circle" value="<?php echo lang('search'); ?>" name="advance_search">   
					</div>
				</div>
			</div>
		</form>

		</div> 

</div>
	<div class="row">
		<?php foreach($jobs_list as $list){
?> 
	<div class="col-md-6">
			<a class="job-list" href="<?php echo base_url(); ?>candidates/job_view_user/<?php echo $list->id;?>">
				<div class="job-list-det">
					<div class="job-list-desc">
						<h3 class="job-list-title"><?php echo $list->job_title;?></h3>
						<h4 class="job-department"><?php echo $list->deptname;?></h4>
					</div>
					<div class="job-type-info">
						<span class="job-types"><?php echo $list->job_type;?></span>
					</div>
				</div>
				<div class="job-list-footer">
					<ul>
						<li><i class="fa fa-map-signs"></i><?php echo $list->job_location; ?></li>
						<li><i class="fa fa-money"></i> <?php echo '$'.$list->salary_from.' - $'.$list->salary_to; ?></li>
						<li><i class="fa fa-clock-o"></i>  <?php 
						$date1 = date_create($list->start_date);
						$date2 = date_create(date('Y-m-d'));
						$diff = date_diff($date1,$date2);
						echo $diff->format("%a").'  days ago';
					?> </li>
					</ul>
				</div>
			</a>
		</div>

<?php } ?>
		
		
	</div>

	<!--Job portal content-->
		<div class="row">
			<div class="col-md-6">
				<div class="card-box">
					<div class="row">
						<div class="col-sm-6 col-6">
							<h4 class="m-b-0">Google for Jobs</h4>
						</div>
						<div class="col-sm-6 col-6 float-right">
							<div class="status-toggle float-right">
								<input type="checkbox" id="company_status_1" class="check" checked>
								<label for="company_status_1" class="checktoggle">checkbox</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card-box">
					<div class="row">
						<div class="col-sm-6 col-6">
							<h4 class="m-b-0">Linkedin Jobs</h4>
						</div>
						<div class="col-sm-6 col-6 float-right">
							<div class="status-toggle float-right">
								<input type="checkbox" id="company_status_2" class="check">
								<label for="company_status_2" class="checktoggle">checkbox</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card-box">
					<div class="row">
						<div class="col-sm-6 col-6">
							<h4 class="m-b-0">Facebook Empleos</h4>
						</div>
						<div class="col-sm-6 col-6 float-right">
							<div class="status-toggle float-right">
								<input type="checkbox" id="company_status_3" class="check">
								<label for="company_status_3" class="checktoggle">checkbox</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card-box">
					<div class="row">
						<div class="col-sm-6 col-6">
							<h4 class="m-b-0">Opcion Empleo</h4>
						</div>
						<div class="col-sm-6 col-6 float-right">
							<div class="status-toggle float-right">
								<input type="checkbox" id="company_status_4" class="check">
								<label for="company_status_4" class="checktoggle">checkbox</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!--/Job portal content-->
</div>

