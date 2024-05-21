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
			<h4 class="page-title m-b-0"><?php echo lang('shortlist_candidates');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
		        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
		        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
		        <li class="breadcrumb-item"><?php echo lang('shortlist_candidates');?></li>
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
						<th class="text-center"><?php echo lang('status');?></th>
						
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					foreach($shortlisted as $list){
						?>
					<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><?php echo $list->first_name.' '.$list->last_name;?></td>
						<td><a href="<?php if(App::is_permit('menu_shortlist_candidates','read')){?><?php echo base_url(); ?>jobs/view_jobs/<?php echo $list->id;?><?php }else{ echo '#';}?>"><?php echo $list->job_title;?></a></td>
						<td><?php echo $list->deptname;?></td>
						<td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php 
								if($list->user_job_status==0){ echo lang('no_action'); }
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
								?>
								</a>	
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/1/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-info"></i> <?php echo lang("resume_shortlisted"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/2/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("resume_rejected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/3/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-success"></i>  <?php echo lang("apptitude_selected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/4/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("apptitude_rejected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/5/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-success"></i>  <?php echo lang("video_call_selected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/6/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo lang("video_call_rejected"); ?></a>
									<a class="dropdown-item" href="<?php echo site_url('jobs/user_status_change/7/'.$list->candidate_id.'/'.$list->id.'/shortlist_candidates');?>"><i class="fa fa-dot-circle-o text-success"></i>  <?php echo lang("offered"); ?></a>
								</div> 
							</div>
						</td>
					</tr>
					<?php 
					}?>
				</tbody>
			</table>
		</div>
	
</div>
<!-- Delete Job Modal -->
<div class="modal custom-modal fade" id="delete_job" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3>Delete</h3>
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