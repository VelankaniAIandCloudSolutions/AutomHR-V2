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
		<h4 class="page-title"><?php echo lang('my_jobs');?></h4>
		 <ul class="breadcrumb p-l-0" style="background:none; border:none;">
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
			<li class="breadcrumb-item">Dashboard</li>
			</ul> 
	</div>
</div>
</div>
<div class="row admin-dash">
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
		<div class="card-body">
			<span class="dash-widget-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
			<div class="dash-widget-info">
				<span><?php echo lang('offered');?></span>
				<h3><?php echo count($offered_jobs);?></h3>
			</div>
		</div>
	</div>
	</div>
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
			<div class="card-body">
				<span class="dash-widget-icon"><i class="fa fa-clipboard icon" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('applied');?></span>
					<h3><?php if(isset($job_counts[1])){ echo $job_counts[1]; }else{ echo 0;}?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
			<div class="card-body">
				<span class="dash-widget-icon"><i class="fa fa-retweet" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('visited');?></span>
					<h3><?php if(isset($total_visited)){ echo $total_visited['count']; }else{ echo 0;}?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-lg-3 d-flex">
		<div class="dash-widget card flex-fill">
			<div class="card-body">
				<span class="dash-widget-icon"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>
				<div class="dash-widget-info">
					<span><?php echo lang('saved');?></span>
					<h3><?php if(isset($job_counts[2])){ echo $job_counts[2]; }else{ echo 0;}?></h3>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 d-flex">
		<div class="card chart-div flex-fill">
			<div class="card-body">
				<h3 class="card-title"><?php echo lang('overview');?></h3>
				<div class="row">
					<div class="col-md-12 m-b-10">
						<canvas id="canvas_rec_user"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 d-flex">
		<div class="card flex-fill">
		<div class="card-body">
			<h3 class="card-title"><?php echo lang('latest_jobs'); ?></h3>
			<div class="list-group" style="min-height:245px;height:245px;overflow:auto;">
				<?php foreach($latest_jobs as $key => $latest){?>
<a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $latest['id'];?>" class="text-capitalize list-group-item"><?php echo $latest['job_title'];?><span class="float-right" style="font-size:12px; color: #a1a1a1;"><?php if($latest['days']>0){ echo $latest['days'].' days ago';}else{ echo $latest['hour'].' hours ago';}?></span></a>
<?php
					} ?>
			</div>
		</div>
	</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h3 class="card-title"><?php echo lang('offered_jobs');?></h3>
				<div class="table-responsive">
					<div class="table-responsive">
						<table class="table table-striped custom-table mb-0 datatable">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo lang('job_title');?></th>
									<th><?php echo lang('department');?></th>
									<th class="text-center"><?php echo lang('job_types');?></th>
									<th class="text-center"><?php echo lang('status');?></th>
									<th class="text-center"><?php echo lang('action')?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i=1;
								foreach ($offered_jobs as $key=>$offer) {
								?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $offer->id;?>"><?php echo $offer->job_title;?></a></td>
									<td><?php echo $offer->deptname ?></td>
									<td class="text-center">
										<div class="action-label">
											<a class="btn btn-white btn-sm btn-rounded" href="#">
											<i class="fa fa-dot-circle-o text-danger"></i> <?php echo $offer->job_type;?>
											</a>
										</div>
									</td>
									<td class="text-center">
										<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
											<?php 
											if($offer->user_job_status==7){ echo lang('offered'); }
											if($offer->user_job_status==8){ echo lang('offer_accepted'); }
											if($offer->user_job_status==9){ echo lang('offer_declined'); }
											if($offer->user_job_status==10){ echo lang('offer_rejected'); }
											?>
											</a>
											<?php if($offer->user_job_status==7 || $offer->user_job_status==8 || $offer->user_job_status==9){?>	
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="javascript:void(0);<?php //echo site_url('candidates/user_offer_status_change/8/'.$jobs->candidate_id.'/'.$jobs->id);?>" onclick="accept_offer('<?=$offer->candidate_id?>','<?= $offer->id?>')"><i class="fa fa-dot-circle-o text-info"></i> <?php echo 'Accept Offer'; ?></a>
									<a class="dropdown-item" href="<?php echo site_url('candidates/user_offer_status_change/9/'.$offer->candidate_id.'/'.$offer->id.'/');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo 'Decline Offer'; ?></a>
									
								</div> 
							<?php } ?>
										</div>
									</td>
									<td class="text-center">
										<?php if($offer->user_job_status==7 || $offer->user_job_status==8 ){?>
										<a href="<?php echo site_url('candidates/download_offer/').$offer->id;?>" class="btn btn-sm btn-info download-offer"><span style="font-size:14px;"><i class="fa fa-download m-r-5"></i><?php echo lang('download_offer')?></span></a>
										<? }else{ ?>
											-
										<?php  }?>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
						<div id="digital_signature_div" class="modal custom-modal fade center-modal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Offer Send</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<form enctype="multipart/form-data" id="prv_form">				
						<input id="ch_candidate_id" type="hidden" name="can_id">				
						<input id="ch_job_id" type="hidden" name="job_id">	
						<div class="form-group">
							<label>Signature Type</label>
							<input type="radio" name="signature_type" value="digital_signature" checked onchange="signature_div('signature_div')">Add Digital Signature
							<input type="radio" name="signature_type" value="upload_image" onchange="signature_div('sign_image')">Upload Signature
						</div>
						<div class="form-group sign_div" id="sign_image" style="display:none">
							<label>Upload Signature</label>
							<input type="file" id="sign_image_file" name="sign_image">
							<p style="color:red;display:none;" id="sign_img_error" class="eror_sign">Please Upload Signature</p>
							<p style="color:red;display:none;" id="sign_img_val_error" class="eror_sign">Please Upload Only Image</p>
						</div>			
						<div class="form-group sign_div" id="signature_div" >
							<label>Digital Signature: <span class="text-muted-light"></span></label>
							<br>
							<div id="sig"></div>
							<textarea id="signature64" name="signature" style="display: none"></textarea>
							<p style="color:red;display:none;" id="sign_error" class="eror_sign">Please Sign Here</p>
						</div>
						<div class="col-md-12 sign_div" id="clear_sign">
							<button class="btn btn-sm btn-warning" id="clear">&#x232B;Clear Signature</button>
						</div>
						<div class="m-t-50 text-center">
							<button class="btn btn-primary btn-lg" type="button"  onclick="appcandmails_preview()">Next</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


<div id="digital_signature_preview" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Offer Preview</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">				
				<input id="ch_asso_id_preview" type="hidden">				
				<input id="ch_asso_id_img" type="hidden">
				<input id="prev_candidate_id" type="hidden">				
				<input id="prev_job_id" type="hidden">				
				<div id="preview_div"></div>
				<div class="m-t-50 text-center">
					<button class="btn btn-primary btn-lg" type="button"  onclick="can_accept_offer_letter()">Accept</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.signature.css" type="text/css" />
<script src="<?=base_url()?>assets/js/jquery.signature.min.js"></script> 
<script type="text/javascript">
	function signature_div(cur_div){
		$('.eror_sign').hide();
		$('.sign_div').hide();
		$('#'+cur_div).show();
		if(cur_div == 'signature_div'){
			$('#clear_sign').show();
		}
	}
	var sig = $('#sig').signature({
		syncField: '#signature64',
		syncFormat: 'PNG'
	});
	$('#clear').click(function(e) {
		e.preventDefault();
		sig.signature('clear');
		$("#signature64").val('');
	});
</script>
<style>
	
	.kbw-signature {
			width:auto;
			height: auto;
		}

		
	</style>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<h3 class="card-title"><?php echo lang('applied_jobs')?></h3>
					<div class="table-responsive">
						<table class="table table-striped custom-table mb-0 datatable">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo lang('job_title');?></th>
									<th><?php echo lang('department');?></th>
									<th><?php echo lang('start_date');?></th>
									<th><?php echo lang('expire_date');?></th>
									<th class="text-center"><?php echo lang('job_types');?></th>
									<th class="text-center"><?php echo lang('status'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(count($applied_jobs)>0){
								$i=1;
								foreach ($applied_jobs as $key => $list) {
								 	?>
								 	<tr>
									<td><?php echo $i++;?></td>
									<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $list->id;?>"><?php echo $list->job_title;?></a></td>
									<td><?php echo $list->deptname;?></td>
									<td><?php echo date('d M Y',strtotime($list->start_date)); ?></td>
									<td><?php echo date('d M Y',strtotime($list->expired_date)); ?></td>
									
									<td class="text-center">
										<div class="action-label">
											<a class="btn btn-white btn-sm btn-rounded" href="#">
											<i class="fa fa-dot-circle-o text-danger"></i><?php  echo $list->job_type;?>
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
								 }
								 } ?>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>