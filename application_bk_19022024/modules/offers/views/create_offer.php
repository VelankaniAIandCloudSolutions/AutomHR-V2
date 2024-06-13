<style>
    .select2-results__options > li:first-child{
   display: none;
}
.select2-results__message{
    display:block !important;
}
    </style>
<?php 
    $offer_approvers = $this->db->get('offer_approver_settings')->result_array();
    $currency = $this->db->get('currency')->result_array();
    if(!empty($offer_approvers)){
        $default_offer_approvals = $this->db->get_where('offer_approver_settings',array('default_offer_approval'=>'seq-approver'))->result_array();
        // echo "<pre>";print_r($default_offer_approvals);
        if(!empty($default_offer_approvals)){
            $default_offer_approval = 'seq-approver';
            $seq_approve = 'seq-approver';
            
        }else {
            $default_offer_approval = 'sim-approver';
            $sim_approve = 'sim-approver';
        }
    }else {
        $default_offer_approval = '';
    }
     // echo "<pre>";print_r($currency);
 ?>


<!-- Content -->
                <div class="content container-fluid">
					
					<!-- Title -->
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Offer Approval Process</h4>
						</div>
					</div>
					<!-- / Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="offer-create">
							
								<!-- Offer Create Wizard -->
								<div class="tabbable">
									<ul class="nav navbar-nav wizard">
										<li class="">
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
										</li>
										<li>
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
										</li>
										<li>
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
										</li>
										
										<li>
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
										</li>
									</ul>
								</div>
								<!-- /Offer Create Wizard -->
								
								<div class="row">
									<div class="col-md-12">
										<h3 class="page-sub-title">Create Offer</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<form class="form-horizontal" id="create_offers" action="<?=base_url()?>offers/create_offer/<?php echo $candidate_id."/".$job_id ;?>" method="POST">
											
											<div class="form-group form-row">
												<label class="control-label col-sm-3">Create offer for  <span class="text-danger">*</span></label>
												<div class="col-sm-9">
													<input class="form-control" type="hidden" name="candidate" id="check_candidate" value="<?= $candidate_id;?>">
													<input type="text" class="form-control" value="<?= $req_candidate['first_name'].' '.$req_candidate['last_name']?>" readonly>
												</div>

											</div>
											
											<div class="form-group">
												<label class="control-label col-sm-3">Title</label>
												<div class="col-sm-9">
													<input type="hidden" value="<?= $job_id;?>" name="title">
													<input type="text" class="form-control" value="<?= $req_job['job_title']?>" readonly>
													<input class="form-control" type="hidden" name="status" value="1">
												</div>
											</div>
											
											<div class="form-group">
												<label class="control-label col-sm-3">Job Type</label>
												<div class="col-sm-9">
													<select class="form-control select1"  id="job_type" disabled>
														<?php foreach (User::GetJobType() as $jtype =>$jvalue): ?>
															<option value="<?=$jvalue['id']?>"  >
																<?= ucfirst(trim($jvalue['job_type']));?>
															</option>
														<?php endforeach ?>	
													</select>
													<input type="hidden" name="job_type" id="job_type1">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Base Salary</label>
												<div class="col-sm-9">
													<input class="form-control" type="text" name="salary" id="salary" value="<?= $req_job['salary_from'];?>">
												</div>
											</div>
											<?php /*<div class="form-group">
												<label class="control-label col-sm-3">Annual Incentive Plan</label>
												<div class="col-sm-9">
													<select class="select form-control" name="annual_incentive_plan" id="annual_incentive_plan">
														<option value="">Selection</option>
														<?php foreach (User::get_annual_incentive_plans() as $jtype =>$jvalue): ?>
															<option value="<?=$jvalue['id']?>"  >
																<?= ucfirst(trim($jvalue['plan']));?>
															</option>
														<?php endforeach ?>	
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label class="control-label col-sm-3">Long Term Incentive Plan</label>
												<div class=" col-sm-9">
													<div class="onoffswitch">
														<input type="checkbox" class="onoffswitch-checkbox" id="onoffswitch" name="long_term_incentive_plan" >
														<label class="onoffswitch-label" for="onoffswitch">
															<span class="onoffswitch-inner"></span>
															<span class="onoffswitch-switch"></span>
														</label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Vacation</label>
												<div class="col-sm-9">
													<select class="select form-control" name="vacation" id="Vacation">
														<option value="">Selection</option>
														<?php foreach (User::get_vocations() as $jtype =>$jvalue): ?>
															<option value="<?=$jvalue['id']?>"  >
																<?= ucfirst(trim($jvalue['vocation']));?>
															</option>
														<?php endforeach ?>	
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Reports to</label>
												<div class="col-sm-9">													
													<select class="select2-option1 form-control"   style="width:100%" name="reports_to" id="reports_to"> 
														
													</select>
												</div>
											</div>*/?>
											<div class="form-group">
												<label class="control-label col-sm-3">Default Offer Approval</label>
												<div class="col-md-9 approval-option">
													<label class="radio-inline">
														<input id="radio-single" value="seq-approver" name="default_offer_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
													</label>
													<label class="radio-inline">
														<input id="radio-multiple" value="sim-approver" name="default_offer_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
													</label>
												</div>
											</div>
											<div class="form-group row approver seq-approver">
												<label class="control-label col-sm-3">Offer Approvers</label>
												
												<div class="col-sm-9 " >
													<div class="row" style="display:none">
														<div class="col-md-10">
															<!-- <select class="select form-control" name="offer_approvers" >
																<option>Recruiter</option>
																<option>Hiring Manager</option>
																<option>Manager</option>
																<option>Manager</option>
																<option>Manager</option>
															</select> -->
															<select class="select2-option1 form-control"   style="width:100%" name="offer_approvers[]" id="offer_approvers"> 
														
													</select>
														</div>
														<div class="col-md-2">
															<span class="m-t-10 badge btn-success">Approver</span>
														</div>
													</div>
													<div id="items">
													</div>
												</div>
												<div class="row">
													<input type="hidden" id="count" value="1">
											<div class="col-sm-9 col-md-offset-3 m-t-10">
												<a id="add1" href="javascript:void(0)" class="add-more">+ Add Approver</a>
											</div>
											</div>
											</div>
											
											<div class="form-group row approver sim-approver">
												<label class="control-label col-sm-3">Offer Approvers</label>
												<div class="col-sm-9 ">
													<label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
													<div class="row">
														<div class="col-md-10">
															<select class="select2-option1 form-control" multiple="multiple" style="width:100%" name="offer_approvers[]" > 
																
															</select>

														</div>
														<div class="col-md-2">
															<span class="m-t-10 badge btn-success">Approved</span>
														</div>
													</div>
												</div>
											</div>
											
											
											<div class="row m-t-20">
												<div class="col-md-9 col-sm-offset-3">
													<label class="control-label" style="margin-bottom:10px;padding-left:0">Message to Approvers</label>
													<div class="row">
														<div class="col-md-12">
															 <textarea class="form-control" rows="5" name="message_to_approvers" id="message_to_approvers"></textarea>
														</div>
													</div>
												</div>
											</div>
											<div class="m-t-30 text-center">
												<button class="btn btn-primary" type="submit" id="create_offers_submit">Create Offer & Send for Approval</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
				<!-- / Content -->
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script>
				$(document).ready(function () {
					$("#check_candidate").select2({
						minimumInputLength: 3,
						tags: [],
						ajax: {
							url: "<?php echo base_url('offers/getCandidates');?>",
							dataType: 'json',
							type: "GET",
							quietMillis: 2,
							data: function (term) {
								return {
									term: term
								};
							},
							processResults: function (data) {
								return {
									results: $.map(data, function (item) {
									return {
											text: item.first_name+' ('+item.email+')',
											slug: item.email,
											id: item.id
										}
									})
								};
							}
						}
					});
					$("#reports_to").select2({
						minimumInputLength: 3,
						tags: [],
						ajax: {
							url: "<?php echo base_url('offers/getEmployees');?>",
							dataType: 'json',
							type: "GET",
							quietMillis: 2,
							data: function (term) {
								return {
									term: term
								};
							},
							processResults: function (data) {
								return {
									results: $.map(data, function (item) {
									return {
											text: item.fullname+' ('+item.email+')',
											slug: item.email,
											id: item.id
										}
									})
								};
							}
						}
					});
					/*$("#job_title").select2({
						minimumInputLength: 3,
						tags: [],
						ajax: {
							url: "<?php echo base_url('offers/getJobs');?>",
							dataType: 'json',
							type: "GET",
							quietMillis: 2,
							data: function (term) {
								return {
									term: term
								};
							},
							processResults: function (data) {
								return {
									results: $.map(data, function (item) {
									return {
											text: item.job_title,
											slug: item.job_title,
											id: item.id
										}
									})
								};
							}
						}
					});*/
				});
				get_job('<?php echo $candidate_id;?>');
				job_detail('<?php echo $job_id;?>');
				function get_job(a){
					$.ajax({
						type: "POST",
						url: "<?php echo base_url();?>offers/getJobs_by_candidate",
						data:{candidate_id:a}, 
						beforeSend :function(){
						
						},                         
						success: function (data) {   
						    $("#job_title").empty().append(data);
						}
					});
				}
				function job_detail(a){
					$.ajax({
						type: "POST",
						url: "<?php echo base_url();?>offers/get_job_detail",
						data:{job_id:a}, 
						beforeSend :function(){
						
						},                         
						success: function (data) {   
							var obj = JSON.parse(data);
							$('#job_type').val(obj.job_type_id);
							$('#job_type1').val(obj.job_type_id);
							$('#salary').val(obj.salary_from);

						}
					});

				}
				</script>