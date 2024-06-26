
<div class="content">
	
<div class="content">
	<div class="page-header">
					<div class="row">
						<div class="col-8">
							<h4 class="page-title m-b-0"><?php echo lang('add_jobs');?></h4>
							<ul class="breadcrumb p-l-0" style="background:none; border:none;">
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/manage"><?php echo lang('manage_jobs');?></a></li>
						        <li class="active breadcrumb-item"><?php echo lang('add_jobs');?></li>
							</ul>
						</div>
						 <div class="col-sm-4  text-right m-b-20">     
				              <a class="btn back-btn" href="<?=base_url()?>jobs/manage"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
				          </div>
					</div>
					</div>					
	<div class="row">
		
	</div>
	<div class="card">
		<div class="card-body">
			<form name="add_jobs" id="add_jobs" action="<?php echo site_url('add_jobs');?>" method="post" enctype="multipart/form-data">
				<input type='hidden' name ='approved_job_id' value='<?php echo $record_id ? $record_id :""?>'>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_title')?></label>
							<input class="form-control" type="text" name="job_title" value="<?php echo $job_request_data['job_title'] ? $job_request_data['job_title'] : ''?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('department'); ?></label>
							<select class="form-control required select2-option" style="width:100%;"   name="department"  onchange="get_position(this.value)">
								<option value=""><?php echo lang('select_department');?></option>
								<?php 
									foreach($departments as $key => $depart)
									{
										?><option value="<?php echo $depart->deptid;?>" <?php if($job_request_data['department'] == $depart->deptname) echo"selected";?> ><?php echo $depart->deptname; ?></option><?php 
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('position'); ?></label>
							<select class="select required select2-option" style="width:100%;"   name="position"  id="positions">
								<option value=""><?php echo lang('select_position');?></option>
								<?php 
									foreach($positions as $key => $postion)
									{
										?><option value="<?php echo $postion->id;?>"  <?php if($job_request_data['Proposed_Designation'] == $postion->designation) echo"selected";?> ><?php echo $postion->designation; ?></option><?php 
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('country')?></label>
							<select class="form-control select2-option" style="width:100%;"  name="country_id" >
								<option value=""><?php echo lang('select_country');?></option>
								<?php 
									foreach($countries as $key => $country)
									{
										?><option value="<?php echo $country->id;?>"><?php echo $country->value; ?></option><?php 
									}
								?>
							</select>
						</div>
					</div>
					
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_location');?></label>
							<input class="form-control" type="text" name="job_location" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('experience_level')?></label>
							<select class="form-control select2-option required" style="width:100%;"  name="experience_level" >
								<option value=""><?php echo lang('select_experience_level');?> </option>
								<?php 
									foreach($experience_levels as $key => $level)
									{
										?><option value="<?php echo $level->id;?>"><?php echo $level->experience_level; ?></option><?php 
									}
								?>
							</select>
						</div>
					</div>
					
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('experiences');?></label>
							<input class="form-control" type="text" name="experience" value="<?php echo $job_request_data['Number_of_years_of_experience'] ? $job_request_data['Number_of_years_of_experience'] : ''?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('no_of_vacancy');?></label>
							<input class="form-control" type="text" name="no_of_vacancy" value="<?php echo $job_request_data['no_of_vacancy'] ? $job_request_data['no_of_vacancy'] : ''?>">
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('age_limit');?></label>
							<input class="form-control" type="text" name="age" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('salary_from');?></label>
							<input type="text" class="form-control" name="salary_from" value="<?php echo $job_request_data['Proposed_CTC'] ? $job_request_data['Proposed_CTC'] : ''?>">
						</div>
					</div>
				
				</div>
				<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('salary_to');?></label>
							<input type="text" class="form-control" name="salary_to" >
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_type');?></label>
							<select class="form-control select2-option" style="width:100%;"  name="job_type" >
								<option value=""><?php echo lang('select_job_type');?></option>
								<?php 
									foreach ($job_types as $key => $type) {
										?><option value="<?php echo $type->id;?>" <?php if($job_request_data['job_type'] == $type->job_type) echo"selected";?>  ><?php echo $type->job_type;?></option><?php 	
									}
								?>
							</select>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('status');?></label>
							<select class="select select2-option" style="width:100%;"   name="status" >
								<option value="0" selected><?php echo lang('open') ?></option>
								<option value="1"><?php echo lang('close');?></option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('start_date');?></label>
							<input type="text" class="form-control datetimepicker" data-date-start-date="0d" name="start_date" value="<?php echo $job_request_data['start_date'] ? $job_request_data['start_date'] : ''?>">
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('expire_date');?></label>
							<input type="text" class="form-control datetimepicker" data-date-start-date="0d" name="expired_date" value="<?php echo $job_request_data['Deadline_for_Hiring'] ? $job_request_data['Deadline_for_Hiring'] : ''?>" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('description');?></label>
							<textarea class="form-control" name="description"><?php echo $job_request_data['skills'] ? $job_request_data['skills'] : ''?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_image');?></label>
							<input type="file" class="form-control" name="image_file" id="image_file">
						</div>
					</div>
					
				</div>
				<div class="submit-section">
					<a href="<?php echo base_url(); ?>jobs/manage" class="btn btn-danger submit-btn m-b-5" id="btnSave" type="submit"><?php echo lang('cancel');?></a>
					<button class="btn btn-primary submit-btn m-b-5" id="savejobs" type="submit" name="submit" value="submit"><?php echo lang('save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>