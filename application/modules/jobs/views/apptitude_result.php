<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title m-b-30 m-b-0"><?php echo lang('apptitude_results');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard"><?php echo lang('recruiting_process');?></a></li>
				<li class="breadcrumb-item"><?php echo lang('apptitude_results');?></li>
			</ul>
		</div>
	</div>
</div>
	<?php //$this->load->view('sub_menus');?>
	<div class="row">
		 
	</div>

		
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 AppendDataTables">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo lang('name');?></th>
						<th><?php echo lang('job_title');?></th>
						<th><?php echo lang('department');?></th>
						<th><?php echo lang('category_wise_mark');?></th>
						<th><?php echo lang('total_mark');?></th>
						<th><?php echo lang('status');?></th>
						<?php if(App::is_permit('menu_apptitude_results','read')==true)
						{
						?>
						<th class="text-right"><?php echo lang('action')?></th>
						<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach($completed_users as $users){

						$users_category[$users->user_id][$users->category_id] = $users->total_mark;
						$category_name[$users->category_id] =  $users->category_name;
						$total_marks[$users->user_id][] = $users->total_mark;
					}
					//print_r($total_marks);
					$i=1;
					$jobs_array = array();
					foreach($completed_users as $users){
						if(!in_array($users->job_id.'-'.$users->user_id,$jobs_array))
						{
							$jobs_array[] = $users->job_id.'-'.$users->user_id;
						?>
						<tr>
						<td><?php echo $i++; ?></td>
						<td class="text-capitalize"><a href="<?php echo '#';?>"><?php echo $users->first_name.' '.$users->last_name;?></a></td>
						<td><?php echo $users->job_title;?></td>
						<td><?php echo $users->deptname;?></td>
						<td><?php

						 if(isset($users_category[$users->user_id])){
						 	foreach($users_category[$users->user_id] as $cat_id=>$mark){
						 		echo $category_name[$cat_id].' - <b>'.$mark.'</b></br>';
						 	}
						 }



						?></td>
						<td class="text-center"><?php if(isset($total_marks[$users->user_id])) { echo array_sum($total_marks[$users->user_id]);}else{ echo 0;}?></td>

						<td >

							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php 
								if($users->user_job_status==0){ echo lang('no_action'); }
								elseif($users->user_job_status==1){echo lang("resume_shortlisted");}
								elseif($users->user_job_status==2){echo lang("resume_rejected");}
								elseif($users->user_job_status==3){echo lang("apptitude_selected");}
								elseif($users->user_job_status==4){echo lang("apptitude_rejected");}
								elseif($users->user_job_status==5){echo lang("video_call_selected");}
								elseif($users->user_job_status==6){echo lang('video_call_rejected');}
								elseif($users->user_job_status==7){echo lang('offered');}
								elseif($users->user_job_status==8){echo lang('offer_accepted');}
								elseif($users->user_job_status==9){echo lang('offer_rejected');}
								elseif($users->user_job_status==10){echo lang('offer_declined');}
								?>
								</a>	
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/1/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-info"></i> <?php echo lang("resume_shortlisted"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/2/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("resume_rejected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/3/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-success"></i>  <?php echo lang("apptitude_selected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/4/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("apptitude_rejected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/5/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-success"></i>  <?php echo lang("video_call_selected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/6/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("video_call_rejected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/7/'.$users->user_id.'/'.$users->job_id.'/apptitude_result');?>"><i class="fa fa-dot-circle-o text-success"></i>  <?php echo lang("offered"); ?></a>
								</div> 
							</div>
						</td>
						<?php if(App::is_permit('menu_apptitude_results','read')==true)
						{
						?>
						<td><a href="<?php echo site_url('jobs/question_answer/'.$users->user_id.'/'.$users->job_id);?>" class="btn border-rounded btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>
</a></td><?php
						}
?>
						
						
						
					</tr>
						<?php 
					}
					} 
					?>
					
				
					
				</tbody>
			</table>
		
	</div>
</div>


