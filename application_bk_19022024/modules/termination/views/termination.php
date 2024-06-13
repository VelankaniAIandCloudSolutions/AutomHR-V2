				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-5">
							<h4 class="page-title">Termination</h4>
						</div>
						<div class="col-sm-7 col-7 text-right m-b-30">
							<?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){ ?>
								<a href="#" class="btn add-btn" onclick="add_termination()"><i class="fa fa-plus"></i> Add Termination</a>
							<?php }?>
						</div>
					</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped custom-table mb-0 datatable" id="termination_table">
									<thead>
										<tr>
											<th style="width: 30px;">#</th>
											<th>Terminated Employee </th>
											<th>Department</th>
											<th>Termination Type </th>
											<th>Termination Date </th>
											<th>Reason</th>
											<th>Last Date </th>
											<?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){ ?>
												<th class="text-right">Action</th>
											<?php }?>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
				<!-- /Page Content -->
				
				<!-- Add Termination Modal -->
				<div id="add_termination" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Termination</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="#"  id="add_terminations" method="post" enctype="multipart/form-data" data-parsley-validate novalidate > 
									<input type="hidden" name="id">
									<div class="form-group">
										<label><?=lang('branch')?> <span class="text-danger">*</span></label>
										<select onchange="entity_change1(this.value);" class="form-control" style="width:100%;"  name="branch_id" id="add_branch" >
											<option value="">Select</option>
											<?php
												if(!empty($branches))	{
													foreach ($branches as $branch1){ ?>
														<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
													<?php } ?>
												<?php } ?>
										</select>
									</div>
									
									<div class="form-group">
										<label>Terminated Employee <span class="text-danger">*</span></label>
										<select class="form-control" style="width:100%;" name="employee" id="employee_id" > 
											<option value="" selected disabled> Choose Employee</option>
										<!-- <?php 
										foreach (User::employee() as $key => $user) { 
											$emp_details = User::profile_info($user->id); 
											// if($emp_details->branch_id == $this->session->userdata('branch_id')){
											?>
										<option value="<?php echo $user->id;?>"><?=ucfirst(User::displayName($user->id))?></option>
										<?php //}
										} ?> -->
									   </select>
									</div>
									<div class="form-group">
										<label>Termination Type <span class="text-danger">*</span></label>
										<div class="add-group-btn">
											<select class="select termination_type" name="termination_type" id="termination_type">
												<option value="">Select</option>
											</select>
											<a class="btn btn-primary" href="" data-toggle="modal" data-target="#add_termination_type_modal"><i class="fa fa-plus"></i> Add</a>
										</div>
									</div>
									<div class="form-group">
										<label>Termination Date <span class="text-danger">*</span></label>
										<div class="cal-icon">
											<input type="text" id="terminationdate" name="terminationdate"  class="form-control datetimepicker">
										</div>
									</div>
									<div class="form-group">
										<label>Reason <span class="text-danger">*</span></label>
										<textarea name="reason" id="reason" class="form-control" rows="4"></textarea>
									</div>
									<div class="form-group">
										<label>Last Date <span class="text-danger">*</span></label>
										<div class="cal-icon">
											<input type="text" id="lastdate" name="lastdate" class="form-control datetimepicker">
										</div>
									</div>
									<?php /*<div class="form-group">
										<label>Termination Letter <span class="text-danger">*</span></label>
										<input type="file" id="terminationletter" name="terminationletter"  class="form-control">
									</div>*/?>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="btnSave">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Termination Modal -->
				
				<!-- Edit Termination Modal -->
				<div id="add_termination_type_modal" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Termination Type</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="add_termination_type">
									<div class="form-group">
										<label>Termination Type <span class="text-danger">*</span></label>
										<input class="form-control" id="term_ination_type1" name="termination_type" type="text" >
									</div>
									
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="btnSave_t">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Termination Modal -->
				
				<!-- Delete Termination Modal -->
				<div class="modal custom-modal fade" id="delete_termination" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Delete termination</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" id="delete_terminations" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Termination Modal -->
				
				
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

				<script>
				
				$(document).ready(function () {
					// alert('fds');
					 
		$(".select2-option1").select2({
			// var add_branch=$("add_branch").val();alert(add_branch);
			minimumInputLength: 3,
			tags: [],
			ajax: {
				url: "<?php echo base_url('transfer_entity/getEmployees');?>",
				dataType: 'json',
				type: "GET",
				quietMillis: 2,
				data: function (term) {
					return {
						term: term
					};
				},
				processResults: function (data) {
					console.log(data);
					return {
						results: $.map(data, function (item) {
							var add_branch= $("#add_branch").val();//alert(add_branch);
							// console.log(item.fullname);
							if(add_branch !='')
							{
								if(item.branch_id==add_branch)
								{
									return {
										text: item.fullname+' ('+item.email+')',
										slug: item.email,
										id: item.id
									}
								}
							}
						
						})
					};
				}
			}
		});
	});


				function entity_change1(a){
					$.ajax( {
						url:'<?php echo base_url('all_assets/get_companies/');?>'+a,
						success:function(data) {
							var reqdata = JSON.parse(data);
							$('#employee_id').empty();
							$('#employee_id').append('<option value="">Select</option>'+reqdata.users);
							$('#employee_id').refresh();
						}
					});
				}
	
			
				</script>
				
				