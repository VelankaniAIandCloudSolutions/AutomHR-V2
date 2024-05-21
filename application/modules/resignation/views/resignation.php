<!-- Page Content -->
<div class="content container-fluid">

	<!-- Page Title -->
	<div class="row">
		<div class="col-sm-5 col-5">
			<h4 class="page-title">Resignation</h4>
		</div>
		<div class="col-sm-7 col-7 text-right m-b-30">
			<?php //if(($this->session->userdata('role_id') != 1)){ 
			?>
			<a href="#" class="btn add-btn" onclick="add_resignation()"><i class="fa fa-plus"></i> Add Resignation</a>
			<?php //}
			?>
		</div>
	</div>
	<!-- /Page Title -->

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-striped custom-table mb-0 datatable" id="resignation_table">
					<thead>
						<tr>
							<th style="width: 30px;">#</th>
							<th>Resigning Employee </th>
							<th>Reporting Manager </th>
							<th>Department </th>
							<th>Reason </th>
							<th>Notice Date </th>
							<th>Resignation Date </th>
							<?php if (($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')) { ?>
								<th class="text-right">Action</th>
							<?php } ?>
							<th>
								Manager Action
							</th>
							<th>
								Manager Approval Status
							</th>
							<th>
								HR Action
							</th>
							<th>
								HR/Final Approval Status
							</th>
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

<!-- Add Resignation Modal -->
<div id="add_resignation" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Resignation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="#" id="add_resignations" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
					<input type="hidden" name="id">

					<div class="row">
						<div class='col-md-6'>
							<div class="form-group">
								<label><?= lang('branch') ?> <span class="text-danger">*</span></label>
								<select onchange="entity_change1(this.value);" class="form-control" style="width:100%;" name="branch_id" id="add_branch" required>
									<option value="">Select</option>
									<?php
									if (!empty($branches)) {
										foreach ($branches as $branch1) { ?>
											<option value="<?= $branch1['branch_id'] ?>"><?= $branch1['branch_name'] ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Resigning Employee <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;" name="employee" id="employee_id" onchange="get_department_id(this.value); return false;" required>
									<option value="" selected disabled> Choose Employee</option>
								</select>
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Department <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;" name="department_id" id="department_id" onchange="get_designation(this.value); return false;" required>
									<option value=""> Choose Department</option>
									<?php
									$this->load->model("department");
									$department_list = $this->department->department_list();
									foreach ($department_list as $key => $department_val) {
									?>
										<option value="<?php echo $department_val['deptid']; ?>" <?php if ($department_val['deptid'] == $user->departent_id) {
																										echo "selected";
																									} ?>><?= ucfirst($department_val['deptname']) ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Designation <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;" name="designation_id" id="designation_id" required>
									<option value=""> Choose Designation</option>
									<?php
									$designation_list = $this->department->designation_list();

									//print_r($user); die; 

									foreach ($designation_list as $key => $designation_val) {
									?>
										<option value="<?php echo $designation_val['id']; ?>"><?= ucfirst($designation_val['designation']) ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Location <span class="text-danger">*</span></label>
								<input type="text" name="location" id="location" class="form-control" readonly required />
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Date o Joining <span class="text-danger">*</span></label>
								<div class="cal-icon">
									<input type="text" name="date_of_joining" id="date_of_joining" class="form-control" required disabled>
								</div>
							</div>
						</div>

					</div>

					<div class='row'>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Exit Request Submitted Date <span class="text-danger">*</span></label>
								<div class="cal-icon">
									<input type="text" name="exit_request_submit_date" id="exit_request_submit_date" value="<?php echo date('Y-m-d');?>" class="form-control datetimepicker" required>
								</div>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Requested Last Working Date <span class="text-danger">*</span></label>
								<div class="cal-icon">
									<input type="text" name="requested_last_working_date" id="requested_last_working_date" class="form-control datetimepicker" required>
								</div>
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-md-12'>
							<div class="form-group">
								<label>Reason for Resignation <span class="text-danger">*</span></label>
								<textarea class="form-control" name="reason" id="reason" rows="4" required></textarea>
							</div>
						</div>
					</div>
					<div class='row'>

						<div class='col-md-6'>
							<div class="form-group">
								<label>Notice Period End Date <span class="text-danger">*</span></label>
								<div class="cal-icon">
									<input type="text" name="notice_period_end_date" id="notice_period_end_date" class="form-control datetimepicker" required>
								</div>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Personal Email</label>
								<input type="text" name="personal_email" id="personal_email" class="form-control" readonly />
							</div>
						</div>

					</div>

					<div class='row'>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Elaborate Reason for Resignation</label>
								<textarea class="form-control" name="elaborate_reason" id="elaborate_reason"></textarea>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								<label>Resignation Status</label>
								<select class='form-control' name="resignation_status" id='resignation_status'>
									<option value='1'>Pending</option>
									<option value='2'>Accepted</option>
									<option value='3'>Rejected</option>
									<option value='4'>Withdrawn</option>
								</select>
							</div>
						</div>
					</div>
					<div class='row'>

						<div class='col-md-6'>
							<div class="form-group">
								<label>Resignation Attachment</label>
								<input type="file" name="resignation_attachment" id="resignation_attachment" class="form-control" />
								
								<div id='attachment_files'>
									
								</div>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="btnSave">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add Resignation Modal -->



<!-- Delete Resignation Modal -->
<div class="modal custom-modal fade" id="delete_resignation" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-head">
					<h3>Delete Resignation</h3>
					<p>Are you sure want to delete?</p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-xs-6">
							<a href="javascript:void(0);" id="delete_resignations" class="btn btn-primary continue-btn">Delete</a>
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
<!-- /Delete Resignation Modal -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
	$(document).ready(function() {
		// alert('fds');

		$(".select2-option1").select2({
			// var add_branch=$("add_branch").val();alert(add_branch);
			minimumInputLength: 3,
			tags: [],
			ajax: {
				url: "<?php echo base_url('transfer_entity/getEmployees'); ?>",
				dataType: 'json',
				type: "GET",
				quietMillis: 2,
				data: function(term) {
					return {
						term: term
					};
				},
				processResults: function(data) {
					console.log(data);
					return {
						results: $.map(data, function(item) {
							var add_branch = $("#add_branch").val(); //alert(add_branch);
							// console.log(item.fullname);
							if (add_branch != '') {
								if (item.branch_id == add_branch) {
									return {
										text: item.fullname + ' (' + item.email + ')',
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

	function entity_change1(a) {
		$.ajax({
			url: '<?php echo base_url('resignation/get_companies/'); ?>' + a,
			success: function(data) {
				var reqdata = JSON.parse(data);
				$('#employee_id').empty();
				$('#employee_id').append('<option value="">Select</option>' + reqdata.users);
				$('#employee_id').refresh();
			}
		});
	}

	function get_department_id(user_id) {
		$.ajax({
			url: '<?php echo base_url('resignation/get_department/'); ?>' + user_id,
			success: function(data) {
				var reqdata = JSON.parse(data);
				let selectedValue = reqdata.department_id;
				if (selectedValue != "") {
					$('#department_id option').each(function() {
						if ($(this).val() === selectedValue) {
							$(this).prop('selected', true);
						}
					});
					get_designation(selectedValue);
					user_info(user_id);
				}

			}
		});
	}

	function get_designation(department_id='') {
		var user_id = $("#employee_id").val();
		var entity_id = $("#add_branch").val();
		$.ajax({
			method: "POST",
			type: "json",
			data: {
				department_id: department_id,
				user_id: user_id,
				entity_id: entity_id
			},
			url: '<?php echo base_url('resignation/get_designation'); ?>',
			success: function(data) {
				if (data != "") {
					var reqdata = JSON.parse(data);
					
					let selectedValue  = '';

					if(reqdata.length == '1')
					{
						selectedValue = reqdata[0].designation_id;
					}
					
					$('#designation_id option').each(function() {
						if ($(this).val() === selectedValue) {
							$(this).prop('selected', true);
						}
					});
				}
			}
		});
	}


	$("#add_branch").on("change", function() {
		let entity_id = $("#add_branch").val();
		// entity_location();
	});

	// function entity_location() {
	// 	let entity_id = $("#add_branch").val();
	// 	$.ajax({
	// 		method: "POST",
	// 		type: "json",
	// 		data: {
	// 			entity_id: entity_id
	// 		},
	// 		url: '<?php echo base_url('all_branches/branch_details'); ?>',
	// 		success: function(data) {
	// 			var result = JSON.parse(data);
	// 			if (result.status === true) {
	// 				$("#location").val(result.result);
	// 			}
	// 		}
	// 	});
	// }

	function user_info(user_id = '') {
		$.ajax({
			method: "POST",
			type: "json",
			data: {
				user_id: user_id
			},
			url: '<?php echo base_url('resignation/user_info'); ?>',
			success: function(data) {
				var result = JSON.parse(data);
				
				console.log(result);

				if (result.status === true) {
					let doj = '';
					doj = result.result.doj;
					$("#date_of_joining").val(doj);
					if (result.result.personal_email == '') {
						$("#personal_email").val(result.result.email);
					} else {
						$("#personal_email").val(result.result.personal_email);
					}

					$("#location").val(result.result.seat_location);

				}
			}
		});
	}
</script>