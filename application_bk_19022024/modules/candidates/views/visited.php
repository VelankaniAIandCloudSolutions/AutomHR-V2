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
			<h4 class="page-title">Visited Jobs</h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				 <li class="breadcrumb-item">Visited Jobs</li>
				
				</ul> 
		</div>
	</div>
</div>
	<div class="card-box">
		<form action="<?php echo site_url('candidates/visited')?>" method="post" id="visited_list_filter">
	<div class="row filter-row">
		<div class="col-sm-6 col-12 col-md-3">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('experience_level') ?></label>
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
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" ><?php echo lang('select_category'); ?></option>
					<?php foreach($job_categories as $key => $category){
						?>
						<option value="<?php echo $category->deptid;?>" <?php if($this->input->post('department_id')  == $category->deptid){ echo "selected";}?>><?php echo $category->deptname;?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label"><?php echo lang('job_country');?></label>
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
				<label class="control-label"><?php echo lang('enter_keywords');?></label>
				<input type="text" class="form-control floating" name="keywords" id="keywords" value="<?php echo $this->input->post('keywords');?>">
			</div>
		</div>

		<div class="col-sm-6 col-6 col-md-3">  
			<button type="submit" value="search" name="search" id="employee_search_btn" onclick="search_filter_submit()" class="btn btn-success btn-block btn-searchEmployee btn-circle"> <?php echo lang('search'); ?> </button>  
			<a class="badge bg-danger text-white" href="<?php current_url();?>"> clear</a>
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
						<th><?php echo lang('department');?></th>
						<th><?php echo lang('start_date');?></th>
						<th><?php echo lang('expire_date');?></th>
						
						<th class="text-center"><?php echo lang('job_type');?></th>
						<th class="text-center"><?php echo lang('status');?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					foreach($visited_jobs as $key=>$list){
						?>
					<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $list->id;?>"><?php echo $list->job_title;?></a></td>
						<td><?php echo $list->deptname;?></td>
						<td><?php echo date('d M Y',strtotime($list->start_date));?></td>
						<td><?php echo date('d M Y',strtotime($list->expired_date));?></td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php echo $list->job_type;?>
								</a>
							</div>
						</td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#" >
								<i class="fa fa-dot-circle-o text-danger"></i> <?php //if($list->job_status==0){ echo lang('open');}else{ echo lang('close');} 
								if($list->user_job_status==0){ echo lang('open');}
								elseif($list->user_job_status==1){echo lang("resume_shortlisted");}
								elseif($list->user_job_status==2){echo lang("resume_rejected");}
								elseif($list->user_job_status==3){echo lang("apptitude_selected");}
								elseif($list->user_job_status==4){echo lang("apptitude_rejected");}
								elseif($list->user_job_status==5){echo lang("video_call_selected");}
								elseif($list->user_job_status==6){echo lang('video_call_rejected');}
								elseif($list->user_job_status==7){echo lang('offered');}
								elseif($list->user_job_status==8){echo lang('offer_accepted');}
								elseif($list->user_job_status==9){echo lang('offer_rejected');}
								elseif($list->user_job_status==10){echo lang('offer_declined');}
								else{ echo lang('close');}
								
								?>
								</a>
							</div>
						</td>
					</tr>

						<?php 
					}?>
					
				</tbody>
			</table>
		</div>
	</div>
</div>