<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content p-t-10">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title"><?php echo lang('interviewing');?></h4>
		</div>
		
		<!-- <div class="col-sm-4  text-right m-b-20">     
	          <a class="btn back-btn" href="<?=base_url()?>candidates/interviewing_jobs"><i class="fa fa-list"></i> List View</a>
	      </div> -->

	</div>
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
		<?php } // foreach end ?>	 
	</div>
	<!-- <div class="row">
		<div class="col-lg-4 col-md-6 col-lg-offset-2">
			<div class="aptitude-test">
				<div class="panel" style="position: relative;"> -->
					<!-- <div class="aptitude-overlay"></div>
						<div class="aptitude-modal">Selected!!</div> -->
			<!-- 		<div class="panel-heading text-center bg-success text-white">
						<h4 class="m-b-0">Apptitude</h4>
					</div>
					<div class="panel-body">
						<img src="<?php echo base_url(); ?>images/aptitude.png" alt="" width="100%">
						<div class="m-t-0">
							<h3 class="text-center">First Round</h3>
						</div>
						<div class="text-center m-t-20 m-b-10">
							<a href="<?=base_url()?>candidates/interviewing_jobs" class="btn btn-primary aptitude-btn"><span>Click Here</span></a>
						</div>
					</div>
				</div>
			</div>
		</div> -->
		<!-- <div class="col-lg-4 col-md-6">
			<div class="aptitude-test">
				<div class="panel" style="position: relative;">
					<div class="aptitude-overlay1"></div>
					<div class="aptitude-modal1"><i class="fa fa-lock" aria-hidden="true"></i></div>
					<div class="panel-heading text-center bg-success text-white">
						<h4 class="m-b-0">Video call</h4>
					</div>
					<div class="panel-body">
						<img src="<?php echo base_url(); ?>images/aptitude-1.png" alt="" width="100%">
						<div class="m-t-0">
							<h3 class="text-center">Second Round</h3>
						</div>
						<div class="text-center m-t-20 m-b-10">
							<a href="<?=base_url()?>chats" class="btn btn-primary aptitude-btn"><span>Click Here</span></a>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	<!-- </div> -->
	<div class="row">
	<div class="col-md-6 col-md-offset-2">
	<div class="card-box">
	<ul class="nav nav-pills nav-justified">
    <li class=" nav-item active"><a class="nav-link active" data-toggle="pill" href="#home" style="margin-right:10px;"><?php echo lang('apptitude');?></a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#menu1"><?php echo lang('schedule_interview');?></a></li>
	</ul>
	</div>
	</div>
	</div>
	  <div class="tab-content pt-0">
	    <div id="home" class="tab-pane fade  active in">
	       <div class="card-box">
			<div class="table-responsive">
				<table class="table table-striped custom-table mb-0 datatable">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo lang('job_title');?></th>
						<th><?php echo lang('department');?></th>
						<!-- <th class="text-center"><?php echo lang('job_types');?></th> -->
						<th class="text-center"><?php echo lang('aptitude_test');?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$i=1;
					 foreach($interviewing_jobs as $list)
					{
						?>
						<tr>
						<td><?php echo $i++; ?></td>
						<td class="text-capitalize"><a href="<?php echo base_url('candidates/job_view/'.$list->id); ?>"><?php echo $list->job_title;?></a></td>
						<td><?php echo $list->deptname;?></td>
						<!-- <td class="text-center">
								<div class="action-label">
									<a class="btn btn-white btn-sm btn-rounded" href="#">
								<i class="fa fa-dot-circle-o text-danger"></i> Full Time
									</a>
								</div>
						</td> -->
							<td class="text-center">
							<a href="<?=base_url()?>candidates/aptitude/<?php echo $list->deptid.'/'.$list->id;?>" class="btn btn-primary aptitude-btn btn-sm"><span><?php echo lang('click_here'); ?></span></a>
							</td>
						</tr>

						<?php

					}?>
					
					</tbody>
				</table>
			</div>
		</div>
	    </div>
	    <div id="menu1" class="tab-pane fade">
	       <div class="card-box">
			<div class="table-responsive">
				<table class="table table-striped custom-table mb-0 datatable">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo lang('job_title');?></th>
						<th><?php echo lang('department');?></th>
						
						<th class="text-center"><?php echo lang('aptitude_test');?></th>
						<th class="text-center"><?php echo lang('interview_scheduled_timings');?></th>
						<th class="text-center"><?php echo lang('schedule_interview');?></th>
						</tr>
					</thead>
					<tbody>
					<?php 

					
					$i =1;
					foreach($scheduled_interview as $list){
					?>
						<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><a href="<?php echo '#';?>"><?php echo $list->job_title;?></a></td>
						<td><?php echo $list->deptname;?></td>
						
							<td class="text-center">
							<a href="javascript:void(0);" class="btn-sm btn btn-primary disabled"><span>

								<?php  echo lang('selected');?></span></a>
							</td>
						<td><?php 
						$user_available_times = json_decode($list->user_selected_timing,true);
						foreach (json_decode($list->schedule_date,true) as $key => $sche_date) {
							echo '<b>'.date('d-m-Y',strtotime($sche_date)).'</b> / '.$time_list[$user_available_times[$key]].'</br>';
						} ?></td>
							<td class="text-center">
							<?php
							$video_call_enable =false;
							 if($list->user_selected_timing ==NULL){?>
							<a href="<?php echo site_url('candidates/schedule_interview_time/'.$list->id);?>" class="btn-sm btn btn-primary aptitude-btn" data-toggle="ajaxModal"><span><?php echo lang('select_your_time'); ?></span></a>
						<?php }else{
						$user_dates = json_decode($list->user_selected_timing,true);

						$current_date = date('d-m-Y');
						$current_time = date('H:i');
							foreach (json_decode($list->schedule_date,true) as $key => $sche_date) {
								
								if(strtotime($sche_date) == strtotime($current_date)){

									
									if(isset($user_dates[$key])){
										$time_array = explode('-', $user_dates[$key]);
										
									if($current_time >= $time_array[0] && $current_time <= $time_array[1]){

										$video_call_enable= true;
									}

									}
								}
							}
							if($video_call_enable){
						 ?> 
						 <!-- <a href="<?php echo site_url('candidate_chats'); ?>"><?php echo lang('video_call');?></a> -->
						 <a href="javascript:void(0)">-</a>
						 <?php 
							}else{
								?><a href="<?php echo site_url('candidate_chats'); ?>">-</a><?php 
							}
							
							
							//echo $list->schedule_date;
						

						}?>
							</td>
						</tr>
				<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	    </div>
	  </div>
</div>
