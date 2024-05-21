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
			<h4 class="page-title"><?php echo lang('offered_jobs');?></h4>
			 <ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard">Recruiting Process</a></li>
				 <li class="breadcrumb-item"><?php echo lang('offered_jobs');?></li>
				
				</ul> 
		</div>
	</div>
</div>
	<!--<div class="card-box">
	<div class="row filter-row">
		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label">Experience Level</label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" selected="selected">Early Professional (<3 years)</option>
					<option value="1">Executive</option>
					<option value="2">Intern</option>
					<option value="3">Professional (>3 years)</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label">Job Category</label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" selected="selected">Software Development & Support</option>
					<option value="1">Communications</option>
					<option value="2">Design & Offering Management</option>
					<option value="3">Marketing & Communications</option>
					<option value="4">Product Services</option>
					<option value="5">Project Management</option>
					<option value="6">Technical Specialist</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-2">  
			<div class="form-group form-focus select-focus m-b-5" style="width:100%;">
				<label class="control-label">Job Country</label>
				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 
					<option value="" selected="selected">Albania</option>
					<option value="1">Canada</option>
					<option value="2">Colombia</option>
					<option value="3">Germany</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-12 col-md-3"> 
			<div class="form-group form-focus m-b-5">
				<label class="control-label">Enter Keywords</label>
				<input type="text" class="form-control floating">
			</div>
		</div>

		<div class="col-sm-6 col-6 col-md-3">  
			<a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee btn-circle"> Search </a>  
		</div>  
	</div>
</div>-->
	
	
	<div class="card-box">
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 datatable">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-capitalize"><?php echo lang('job_title');?></th>
						<th><?php echo lang('department')?></th>
						<th class="text-center"><?php echo lang('job_types');?></th>
						<th class="text-center"><?php echo lang('status');?></th> 
						<th class="text-center"><?php echo lang('action');?></th> 
					</tr>
				</thead>
				<tbody>

					<?php 
					$i=1;
					foreach($offered_jobs as $key => $jobs){?>
					<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><a href="<?php echo base_url(); ?>candidates/job_view/<?php echo $jobs->id;?>"><?php echo $jobs->job_title;?></a></td>
						<td><?php echo $jobs->deptname;?></td>
						<td class="text-center">
							<div class="action-label">
								<a class="btn btn-white btn-sm btn-rounded" href="#">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php echo $jobs->job_type;?>
								</a>
							</div>
						</td>
						 <td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php 
								if($jobs->user_job_status==7){echo 'Offered';}
								if($jobs->user_job_status==8){echo 'Offer Accepted';}
								if($jobs->user_job_status==9){echo 'Offer Declined';}
								if($jobs->user_job_status==10){echo 'Offer Rejected';}
								?>
								</a>
								<?php if($jobs->user_job_status==7 || $jobs->user_job_status==8 || $jobs->user_job_status==9){?>	
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="javascript:void(0);<?php //echo site_url('candidates/user_offer_status_change/8/'.$jobs->candidate_id.'/'.$jobs->id);?>" onclick="accept_offer('<?=$jobs->candidate_id?>','<?= $jobs->id?>')"><i class="fa fa-dot-circle-o text-info"></i> <?php echo 'Accept Offer'; ?></a>
									<a class="dropdown-item" href="<?php echo site_url('candidates/user_offer_status_change/9/'.$jobs->candidate_id.'/'.$jobs->id.'/');?>"><i class="fa fa-dot-circle-o text-danger"></i>  <?php echo 'Decline Offer'; ?></a>
									
								</div> 
							<?php } ?>
							</div>
							</td> 
						 <td class="text-center">
						 <?php if($jobs->user_job_status==7 || $jobs->user_job_status==8 ){?>
							<a href="<?php echo site_url('candidates/download_offer/'.$jobs->id);?>" class="btn btn-sm btn-info download-offer"><span style="font-size: 14px;"><i class="fa fa-download m-r-5"></i><?php echo lang('download_offer'); ?></span></a>
							<?php }else{?>
								_
							<?php }?>
						</td> 
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


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
</style>