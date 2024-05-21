
<!-- <div class="content">
	<div class="row"> -->

   
     
        	  
          
                <div class="content">
                	<div class="page-header">
					<div class="row">
						<div class="col-8">
							<h4 class="page-title m-b-0"><?php echo lang('edit_jobs');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/manage"><?php echo lang('manage_jobs');?></a></li>
						        <li class="active  breadcrumb-item"><?php echo lang('edit_jobs');?></li>
			</ul>
		</div>
						 <div class="col-sm-4  text-right m-b-20">     
				              <a class="btn back-btn" href="<?=base_url()?>jobs/manage"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
				          </div>
					</div>	
					</div>
	<div class="row">
		<?php /* foreach ($jobs as $key => $value) {	//print_r($value);exit();	// foreach starts 
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
						<a class='job-types' href="<?=base_url()?>jobs/apply/<?=$value->id?>/<?=$value->job_type?>">Apply</a>
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
		<?php } // foreach end */?>	 
	</div>
	<div class="card">
		
		<div class="card-body">
			<form id="edit_jobs" name="edit_job" action="<?php echo site_url('jobs/edit').'/'.$this->uri->segment(3)?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_title');?></label>
							<input class="form-control" type="text" value="<?php echo $job[0]['job_title'];?>" name="job_title" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('department');?></label>
							<select class="select" name="department"  onchange="get_position(this.value)">
								<option value=""><?php echo lang('select_department');?></option>
								<?php 
									foreach($departments as $key => $depart)
									{
										?><option value="<?php echo $depart->deptid;?>" <?php if($depart->deptid==$job[0]['department_id']){ echo "selected";}?>><?php echo $depart->deptname; ?></option><?php 
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
							<select class="select required" name="position"  id="positions">
								<option value=""><?php echo lang('select_position');?></option>
								<?php 
									foreach($positions as $key => $postion)
									{
										?><option value="<?php echo $postion->id;?>" <?php if($postion->id==$job[0]['position_id']){ echo "selected";}?>><?php echo $postion->designation; ?></option><?php 
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('country');?></label>
							<select class="select" name="country_id" >
								<option value=""><?php echo lang('select_country');?></option>
								<?php 
									foreach($countries as $key => $country)
									{
										?><option value="<?php echo $country->id;?>" <?php if($job[0]['country_id']==$country->id){ echo "selected";}?>><?php echo $country->value; ?></option><?php 
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
							<input class="form-control" type="text" value="<?php echo $job[0]['job_location'];?>" name="job_location" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('experience_level');?></label>
							<select class="select" name="experience_level" >
								<option value=""><?php echo lang('select_experience_level');?> </option>
								<?php 
									foreach($experience_levels as $key => $level)
									{
										?><option value="<?php echo $level->id;?>" <?php if($job[0]['experience_level_id'] == $level->id ){echo "selected";}?>><?php echo $level->experience_level; ?></option><?php 
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
							<input class="form-control" type="text" value="<?php echo $job[0]['experience'];?>" name="experience" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('no_of_vacancy');?></label>
							<input class="form-control" type="text" value="<?php echo $job[0]['no_of_vacancy'];?>"  name="no_of_vacancy" >
						</div>
					</div>
					
				
				</div>
				<div class="row">

						<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('age_limit');?></label>
							<input class="form-control" type="text" value="<?php echo $job[0]['age'];?>"  name="age">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('salary_from');?></label>
							<input type="text" class="form-control" value="<?php echo $job[0]['salary_from'];?>" name="salary_from">
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('salary_to');?></label>
							<input type="text" class="form-control" value="<?php echo $job[0]['salary_to'];?>" name="salary_to">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_type');?></label>
							
								<select class="select" name="job_type" >
								<option value=""><?php echo lang('select_job_type');?></option>
								<?php 
									foreach ($job_types as $key => $type) {
										?><option value="<?php echo $type->id;?>" <?php if($job[0]['job_type_id']==$type->id){ echo "selected";}?>><?php echo $type->job_type;?></option><?php 	
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
							<select class="select" name="status" >
								<option value="0" <?php if($job[0]['job_status']==0) { echo "selected";}?>><?php echo lang('open');?></option>
								<option value="1"  <?php if($job[0]['job_status']==1) { echo "selected";}?>><?php echo lang('close');?></option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('start_date');?></label>
							<input type="text" class="form-control datetimepicker" value="<?php echo date('m/d/Y',strtotime($job[0]['start_date']));?>" name="start_date" >
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('expire_date');?></label>
							<input type="text" class="form-control datetimepicker"value="<?php echo date('m/d/Y',strtotime($job[0]['expired_date']));?>" name="expired_date" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('description');?></label>
							<textarea class="form-control" name="description"><?php echo $job[0]['description']?></textarea>
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
				<?php if(!empty($job[0]['job_image'])){?>
				<img src="<?php echo base_url('images/jobs').'/'.$job[0]['job_image'];?>" width="100px" height="100px">
				<?php } ?>
				<div class="submit-section">
					<a href="<?php echo base_url(); ?>jobs/manage" class="btn btn-danger submit-btn m-b-5" id="btnSave" type="submit"><?php echo lang('cancel');?></a>
					<button class="btn btn-primary submit-btn m-b-5" id="savejobs" type="submit" value="submit" name="submit"><?php echo lang('save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>