<?php
$check_teamlead = $this->db->get_where('dgt_users', array('id' => $employee_details['user_id']))->row_array();
$branch_det = $this->db->where('branch_status', '0')->where('branch_id', $employee_details['branch_id'])->get('branches')->row_array();



$disabled = 'disabled';
$editable ='readonly';
if ($this->session->userdata("role_id") == 1) {
	$disabled = '';
	$editable = '';
}



//echo "<pre>"; echo $personal_details['education_details']; exit; 
?>
<div class="content container-fluid">
	<!-- Page Title -->
	<div class="row">
		<div class="col-sm-12">
			<h4 class="page-title">Profile</h4>
		</div>
	</div>
	<!-- /Page Title -->

	<div class="card-box m-b-0">
		<div class="row">
			<div class="col-md-12">
				<div class="profile-view">
					<div class="profile-img-wrap">
						<div class="profile-img">
							<a href="#"><img class="avatar" src="<?php echo base_url(); ?>assets/avatar/<?php echo $employee_details['avatar']; ?>" alt=""></a>
						</div>
					</div>
					<div class="profile-basic">
						<div class="row">
							<div class="col-md-5">
								<div class="profile-info-left">
									<h3 class="user-name m-t-0 m-b-0"><?php echo $employee_details['fullname']; ?></h3>
									<?php $des = $this->db->get_where('designation', array('id' => $employee_details['designation_id']))->row_array(); ?>
									<?php $dept = $this->db->get_where('departments', array('deptid' => $employee_details['department_id']))->row_array(); ?>
									<small class="text-muted"><?php echo $des['designation']; ?></small>
									<?php /*<div class="staff-id">Employee ID : <?php echo 'FT-00'.$employee_details['user_id']; ?></div>*/ ?>
									<div class="staff-id">Employee ID : <?php echo $employee_details['emp_code'];  ?></div>
									<div class="small text-muted">Date of Join : <?php echo $employee_details['doj'] ? date('d-M-Y', strtotime($employee_details['doj'])) : '-';; ?></div>
									<?php if (!empty($employee_details['exit_date']) && $employee_details['exit_date'] != "0000-00-00") { ?>
										<div class="small text-muted">Date of Exit : <?php echo date('d-M-Y', strtotime($employee_details['exit_date'])); ?></div>
									<?php } ?>
									<div class="staff-id">Personal Email : <?php echo $employee_details['personal_email']; ?></div>
									<?php /*if($this->session->userdata('role_id') == 1){ ?>
<div class="staff-msg"><a href="<?php echo base_url(); ?>chats" class="btn btn-custom">Send Message</a></div>
<?php }*/  ?>
								</div>
							</div>
							<div class="col-md-7">
								<ul class="personal-info">
									<li>
										<span class="title">Phone:</span>
										<span class="text"><a href=""><?php echo $employee_details['phone']; ?></a></span>
									</li>
									<li>
										<span class="title">Email:</span>
										<span class="text"><a href=""><?php echo $employee_details['email']; ?></a></span>
									</li>
									<li>
										<span class="title">Birthday:</span>
										<span class="text"><?php echo (!empty($employee_details['dob']) && $employee_details['dob'] != "0000-00-00") ? date('d-M-Y', strtotime($employee_details['dob'])) : '-'; ?></span>
									</li>
									<?php /*<li>
<span class="title">Address:</span>
<span class="text"><?php echo $employee_details['address']?$employee_details['address'].' '.$employee_details['city'].' '.$employee_details['state'].' '.$employee_details['country']:''; ?></span>
</li>*/ ?>
									<li>
										<span class="title">Seat Location:</span>
										<span class="text"><?php echo $employee_details['seat_location']; ?></span>
									</li>
									<li>
										<?php
										$location = '';
										if (!empty($employee_details['location1'])) {
											$location .= $employee_details['location1'];
										}
										if (!empty($employee_details['location2'])) {
											if (!empty($employee_details['location1'])) {
												$location .= ',';
											}
											$location .= $employee_details['location2'];
										}
										?>
										<span class="title">Address:</span>
										<span class="text"><?php echo  $employee_details['address']; ?></span>
									</li>
									<?php /*<li>
<span class="title">Gender:</span>
<span class="text"><?php echo $employee_details['gender']; ?></span>
</li>*/ ?>
									<li>
										<span class="title">Reports to:</span>
										<span class="text">
											<div class="avatar-box">
												<?php $reporting_to = $this->db->get_where('account_details', array('user_id' => $employee_details['teamlead_id']))->row_array();
												if (count($reporting_to) != 0) {
													if ($reporting_to['avatar'] == '') {
														$pro_pic = base_url() . 'assets/avatar/default_avatar.jpg';
													} else {
														$pro_pic = base_url() . 'assets/avatar/' . $reporting_to['avatar'];
													}
												?>
													<div class="avatar avatar-xs">
														<img src="<?php echo $pro_pic; ?>" alt="">
													</div>
											</div>
											<a href="<?php echo base_url() . 'employees/profile_view/' . $employee_details['teamlead_id'] ?>">
												<?php echo $reporting_to['fullname']; ?>
											</a>
										<?php } else { ?>
											<div class="avatar avatar-xs">
												<img src="<?php echo base_url() . 'assets/avatar/default_avatar.jpg'; ?>" alt="">
											</div>
							</div>
							<a href="">
								-
							</a>
						<?php } ?>
						</span>
						</li>
						</ul>
						</div>
					</div>
				</div>
				<div class="pro-edit"><a href="#" class="edit-icon" data-toggle="modal" data-target="#profile_info"><i class="fa fa-pencil"></i></a></div>
			</div>
		</div>
	</div>
</div>

<div class="card-box tab-box">
	<div class="row user-tabs">
		<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
			<ul class="nav nav-tabs nav-tabs-bottom">
				<li class="active"><a href="#emp_profile" data-toggle="tab">Profile</a></li>
				<?php //if(($this->session->userdata('role_id') != 2) && ($this->session->userdata('role_id') != 3)){ 
				?>
				<li><a href="#bank_statutory" data-toggle="tab">Bank & Statutory</a></li>
				<li><a href="#tab_additions" data-toggle="tab">Payroll Addition</a></li>
				<li><a href="#tab_deductions" data-toggle="tab">Payroll Deduction</a></li>
				<?php //} 
				?>
				<?php /*if($this->session->userdata('role_id') == 3){ ?>
<li><a href="#tab_overtime" data-toggle="tab">Overtime</a></li>
<?php } ?>
<?php if($check_teamlead['is_teamlead'] == 'yes'){ ?>
<li><a href="#tab_teamovertime" data-toggle="tab">Team Overtime</a></li>
<?php }*/ ?>
				<li><a href="#tab_leaves" data-toggle="tab">Leaves</a></li>
				<li><a href="#file_attach" data-toggle="tab">File Attachments</a></li>
				<?php $document_permission = $this->db->get_where('hooks', array('access' => $this->session->userdata('role_id'), 'module' => 'all_employees_document'))->row_array();
				//if(!empty($document_permission)){
				?>
				<li><a href="#file_document" data-toggle="tab">Documents</a></li>
				<?php //}
				?>
			</ul>
		</div>
	</div>
</div>

<div class="tab-content">

	<!-- Profile Info Tab -->
	<?php $personal_info = json_decode($personal_details['personal_info']); ?>
	<div id="emp_profile" class="pro-overview tab-pane fade in active">
		<div class="row">
			<div class="col-md-6">
				<div class="card-box profile-box">
					<h3 class="card-title">Personal Informations <a href="#" class="edit-icon" data-toggle="modal" data-target="#personal_info_modal"><i class="fa fa-pencil"></i></a></h3>
					<ul class="personal-info">
						<li>
							<span class="title">Aadhar Card No.</span>
							<span class="text"><?php echo (!empty($personal_info->aadhar)) ? $personal_info->aadhar : ''; ?></span>
						</li>
						<li>
							<span class="title">Pan Card No.</span>
							<span class="text"><?php echo (!empty($personal_info->pan_card)) ? $personal_info->pan_card : ''; ?></span>
						</li>
						<li>
							<span class="title">Tel</span>
							<span class="text"><a href=""><?php echo $personal_info->tel_number; ?></a></span>
						</li>
						<li>
							<span class="title">Nationality</span>
							<span class="text"><?php echo $personal_info->nationality; ?></span>
						</li>
						<li>
							<span class="title">Religion</span>
							<span class="text"><?php echo $personal_info->religion; ?></span>
						</li>
						<li>
							<span class="title">Marital status</span>
							<span class="text"><?php echo $personal_info->marital_status; ?></span>
						</li>
						<li>
							<span class="title">Employment of spouse</span>
							<span class="text"><?php echo $personal_info->spouse; ?></span>
						</li>
						<li>
							<span class="title">No. of children</span>
							<span class="text"><?php echo $personal_info->no_children; ?></span>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-6">
				<?php $emergency_info = json_decode($personal_details['emergency_info']); ?>
				<div class="card-box profile-box">
					<h3 class="card-title">Emergency Contact <a href="#" class="edit-icon" data-toggle="modal" data-target="#emergency_contact_modal"><i class="fa fa-pencil"></i></a></h3>
					<h5 class="section-title">Primary</h5>
					<ul class="personal-info">
						<li>
							<span class="title">Name</span>
							<span class="text"><?php echo $emergency_info->contact_name1; ?></span>
						</li>
						<li>
							<span class="title">Relationship</span>
							<span class="text"><?php echo $emergency_info->relationship1; ?></span>
						</li>
						<li>
							<span class="title">Phone </span>
							<span class="text"><?php echo $emergency_info->contact1_phone1; ?> <?php echo $emergency_info->contact1_phone2; ?></span>
						</li>
					</ul>
					<hr>
					<h5 class="section-title">Secondary</h5>
					<ul class="personal-info">
						<li>
							<span class="title">Name</span>
							<span class="text"><?php echo $emergency_info->contact_name2; ?></span>
						</li>
						<li>
							<span class="title">Relationship</span>
							<span class="text"><?php echo $emergency_info->relationship2; ?></span>
						</li>
						<li>
							<span class="title">Phone </span>
							<span class="text"><?php echo $emergency_info->contact2_phone1; ?> <?php echo $emergency_info->contact2_phone2; ?></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="card-box profile-box">
					<?php $bank_info = json_decode($personal_details['bank_info']); ?>
					<h3 class="card-title">Bank information <?php if ($this->session->userdata('role_id') != 3) { ?><a href="#" class="edit-icon" data-toggle="modal" data-target="#bank_iformation_modal"><i class="fa fa-pencil"></i></a> <?php } ?></h3>
					<ul class="personal-info">
						<li>
							<span class="title">Bank name</span>
							<span class="text"><?php echo $bank_info->bank_name; ?></span>
						</li>
						<li>
							<span class="title">Bank account No.</span>
							<span class="text"><?php echo $bank_info->bank_ac_no; ?></span>
						</li>
						<li>
							<span class="title">IFSC Code</span>
							<span class="text"><?php echo $bank_info->ifsc_code; ?></span>
						</li>
						<li>
							<span class="title">PAN No</span>
							<span class="text"><?php echo $bank_info->pan_no; ?></span>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card-box profile-box">
					<h3 class="card-title">Family Information <a href="#" class="edit-icon" data-toggle="modal" data-target="#family_info_modal"><i class="fa fa-pencil"></i></a></h3>
					<table class="table table-nowrap">
						<thead>
							<tr>
								<th>Name</th>
								<th>Relationship</th>
								<th>Date of Birth</th>
								<th>Phone</th>
							</tr>
						</thead>
						<tbody>
							<?php $personal_info = json_decode($personal_details['family_members_info']);
							// echo "<pre>"; print_r($personal_info); exit;
							if (count($personal_info) != 0) {
								foreach ($personal_info as $per) {
							?>
									<tr>
										<td><?php echo $per->member_name; ?></td>
										<td><?php echo $per->member_relationship; ?></td>
										<td><?php echo $per->member_dob; ?></td>
										<td><?php echo $per->member_phone; ?></td>
									</tr>
								<?php }
							} else { ?>
								<tr>
									<td colspan="4">
										<div class="no-results">No Data Found</div>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="card-box user-box">
					<h3 class="card-title">Education Information <a href="#" class="edit-icon" data-toggle="modal" data-target="#education_info"><i class="fa fa-pencil"></i></a></h3>
					<?php $education_details = json_decode($personal_details['education_details']);
					// echo "<pre>"; print_r($education_details); exit;
					?>
					<div class="experience-box">
						<ul class="experience-list">
							<?php if ($education_details != '') {
								foreach ($education_details as $education_detail) {
							?>
									<li>
										<div class="experience-user">
											<div class="before-circle"></div>
										</div>
										<div class="experience-content">
											<div class="timeline-content">
												<a href="#/" class="name"><?php echo $education_detail->institute; ?></a>
												<div><?php echo $education_detail->degree; ?></div>
												<span class="time"><?php echo $education_detail->start_date; ?> - <?php echo $education_detail->end_date; ?></span>
											</div>
										</div>
									</li>
								<?php }
							} else { ?>
								<li>
									<div class="no-results">Not Updated</div>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card-box user-box">
					<h3 class="card-title">Experience <a href="#" class="edit-icon" data-toggle="modal" data-target="#experience_info"><i class="fa fa-pencil"></i></a></h3>
					<div class="experience-box">
						<ul class="experience-list">
							<?php $personal_detailss = json_decode($personal_details['personal_details']);
							foreach ($personal_detailss as $personal_detail) {
							?>
								<li>
									<div class="experience-user">
										<div class="before-circle"></div>
									</div>
									<div class="experience-content">
										<div class="timeline-content">
											<a href="#/" class="name"><?php echo $personal_detail->job_position; ?> at <?php echo $personal_detail->company_name; ?></a>
											<span class="time"><?php echo date('d M Y', strtotime($personal_detail->period_from)); ?> - <?php echo date('d M Y', strtotime($personal_detail->period_from)); ?></span>
										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Profile Info Tab -->


	<?php
	$bank_statutories = $this->db->get_where('bank_statutory', array('user_id' => $employee_details['user_id']))->row_array();
	$in_bank_statutories = json_decode($bank_statutories['bank_statutory']);
	// echo $in_bank_statutories->pf_contribution; exit;
	// print_r($in_bank_statutories); exit;
	?>

	<!-- Bank Statutory Tab -->
	<div class="tab-pane fade" id="bank_statutory">
		<div class="card">
			<div class="card-body">
				<h3 class="card-title"> Basic Salary Information </h3>
				<?php if ($this->session->userdata('role_id') == 1) { ?>
					<form method="post" id="bank_statutory_form" action="<?php echo base_url(); ?>employees/bank_statutory">
					<?php } ?>
					<div class="row">
						<!-- <div class="col-sm-4">
<div class="form-group">
<label class="col-form-label">Salary basis <span class="text-danger">*</span></label>
<select class="form-control" >
<option value="" disabled>Select salary basis type</option>
<option value="HOURLY">Hourly</option>
<option value="DAILY">Daily</option>
<option value="WEEKLY">Weekly</option>
<option value="MONTHLY">Monthly</option>
</select>
</div>
</div> -->
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Salary amount <small class="text-muted">per year</small></label>
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">$</span>
									</div>
									<input type="text" class="form-control" name="user_salary" id="user_salary" placeholder="Type your salary amount" value="<?php echo $bank_statutories['salary']; ?>">
									<input type="hidden" name="bankuser_id" id="bankuser_id" value="<?php echo $employee_details['user_id']; ?>">
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Payment type</label>
								<select class="form-control" name="payment_type" id="payment_type">
									<option value="" disabled>Select payment type</option>
									<option value="bank" <?php if ($bank_statutories['payment_type'] == 'bank') {
																echo "selected";
															} ?>>Bank transfer</option>
									<option value="cheque" <?php if ($bank_statutories['payment_type'] == 'cheque') {
																echo "selected";
															} ?>>Cheque</option>
									<option value="cash" <?php if ($bank_statutories['payment_type'] == 'cash') {
																echo "selected";
															} ?>>Cash</option>
									<option value="manual bank transfer" <?php if ($bank_statutories['payment_type'] == 'manual bank transfer') {
																				echo "selected";
																			} ?>><?php echo 'Manual Bank Transfer' ?></option>
								</select>
							</div>
						</div>
					</div>
					<hr>
					<h3 class="card-title"> PF Information</h3>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">PF contribution</label>
								<select class="form-control " name="pf_contribution" id="pf_contribution">
									<option value="" disabled>Select PF contribution</option>
									<option value="yes" <?php if ($in_bank_statutories->pf_contribution == 'yes') {
															echo "selected";
														} ?>>YES</option>
									<option value="no" <?php if ($in_bank_statutories->pf_contribution == 'no') {
															echo "selected";
														} ?>>NO</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">PF No. </label>
								<input type="text" class="form-control PFrecords" name="pf_no" id="pf_no" placeholder="Type your PF No" value="<?php echo $in_bank_statutories->pf_no; ?>" <?php if ($in_bank_statutories->pf_contribution == 'no') {
																																																echo "disabled";
																																															} ?>>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Employee PF rate</label>
								<select class="form-control PFrecords" name="pf_rates" id="pf_rates" <?php if ($in_bank_statutories->pf_contribution == 'no') {
																											echo "disabled";
																										} ?>>
									<option value="" disabled>Select PF contribution</option>
									<option value="yes" <?php if ($in_bank_statutories->pf_rates == 'yes') {
															echo "selected";
														} ?>>YES</option>
									<option value="no" <?php if ($in_bank_statutories->pf_rates == 'no') {
															echo "selected";
														} ?>>NO</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Additional rate(%) </label>
								<input type="text" class="form-control PFrecords EMprate" name="pf_add_rates" id="pf_add_rates" placeholder="Rates(%)" value="<?php echo $in_bank_statutories->pf_add_rates; ?>" <?php if ($in_bank_statutories->pf_contribution == 'no') {
																																																						echo "disabled";
																																																					} ?>>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Total rate</label>
								<input type="text" class="form-control PFrecords EMprate" name="pf_total_rate" id="pf_total_rate" placeholder="Amount" value="<?php echo $in_bank_statutories->pf_total_rate; ?>" readonly <?php if ($in_bank_statutories->pf_contribution == 'no') {
																																																								echo "disabled";
																																																							} ?>>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Employer PF rate</label>
								<select class="form-control PFrecords" name="pf_employer_contribution" id="pf_employer_contribution" <?php if ($in_bank_statutories->pf_contribution == 'no') {
																																			echo "disabled";
																																		} ?>>
									<option value="" disabled>Select PF contribution</option>
									<option value="yes" <?php if ($in_bank_statutories->pf_employer_contribution == 'yes') {
															echo "selected";
														} ?>>YES</option>
									<option value="no" <?php if ($in_bank_statutories->pf_employer_contribution == 'no') {
															echo "selected";
														} ?>>NO</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Additional rate(%) <span class="text-danger">*</span></label>
								<input type="text" class="form-control PFrecords EmprRate" name="employer_add_rates" id="employer_add_rates" placeholder="N/A" value="<?php echo $in_bank_statutories->employer_add_rates; ?>" <?php if ($in_bank_statutories->pf_contribution == 'no') {
																																																									echo "disabled";
																																																								} ?>>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Total rate</label>
								<input type="text" class="form-control PFrecords EmprRate" name="employer_total_rates" id="employer_total_rates" placeholder="Amount" value="<?php echo $in_bank_statutories->employer_total_rates; ?>" readonly <?php if ($in_bank_statutories->pf_contribution == 'no') {
																																																														echo "disabled";
																																																													} ?>>
							</div>
						</div>
					</div>

					<hr>
					<h3 class="card-title"> ESI Information</h3>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">ESI contribution</label>
								<select class="form-control" name="esi_contribution" id="esi_contribution">
									<option value="" disabled>Select ESI contribution</option>
									<option value="yes" <?php if ($in_bank_statutories->esi_contribution == 'yes') {
															echo "selected";
														} ?>>YES</option>
									<option value="no" <?php if ($in_bank_statutories->esi_contribution == 'no') {
															echo "selected";
														} ?>>NO</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">ESI No.</label>
								<input type="text" class="form-control ESIrecords" name="esi_no" id="esi_no" placeholder="Type your ESI No" value="<?php echo $in_bank_statutories->esi_no; ?>" <?php if ($in_bank_statutories->esi_contribution == 'no') {
																																																	echo "disabled";
																																																} ?>>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Employee ESI rate</label>
								<select class="form-control ESIrecords" name="esi_rate" id="esi_rate" <?php if ($in_bank_statutories->esi_contribution == 'no') {
																											echo "disabled";
																										} ?>>
									<option value="" disabled>Select ESI Rate</option>
									<option value="yes" <?php if ($in_bank_statutories->esi_rate == 'yes') {
															echo "selected";
														} ?>>YES</option>
									<option value="no" <?php if ($in_bank_statutories->esi_rate == 'no') {
															echo "selected";
														} ?>>NO</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Additional rate(%) </label>
								<input type="text" class="form-control ESIrecords ESIRates" name="esi_add_rate" id="esi_add_rate" placeholder="Rates(%)" value="<?php echo $in_bank_statutories->esi_add_rate; ?>" <?php if ($in_bank_statutories->esi_contribution == 'no') {
																																																						echo "disabled";
																																																					} ?>>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="col-form-label">Total rate</label>
								<input type="text" class="form-control ESIrecords ESIRates" name="esi_total_rate" id="esi_total_rate" placeholder="Total Rate" value="<?php echo $in_bank_statutories->esi_total_rate; ?>" readonly <?php if ($in_bank_statutories->esi_contribution == 'no') {
																																																										echo "disabled";
																																																									} ?>>
							</div>
						</div>
					</div>
					<?php if ($this->session->userdata('role_id') == 1 || (!empty($branches) && $view_id != $this->session->userdata('user_id'))) { ?>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn" type="submit">Save</button>
						</div>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- /Bank Statutory Tab -->

	<!-- Additions Tab -->
	<div class="tab-pane" id="tab_additions">

		<!-- Add Addition Button -->
		<!-- <?php //if($this->session->userdata('role_id')==1 || ( !empty($branches) && $view_id!= $this->session->userdata('user_id') ) ){
				?>
<div class="text-right m-b-30 clearfix">
<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_addition"><i class="fa fa-plus"></i> Add Addition</button>
</div>
<?php //}
?> -->
		<!-- /Add Addition Button -->

		<div class="payroll-table card">
			<form method="post" id="addtional_form" action="<?php echo base_url(); ?>employees/addtional_pf_details">
				<input type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
				<table class="table table-hover table-radius">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Default/Unit Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$all_addtional = $this->db->get_where('bank_statutory', array('user_id' => $employee_details['user_id']))->row_array();

						$addtional_ar = json_decode($all_addtional['pf_addtional'], TRUE);
						$i = 1;

						$annual_gross_salary = $annual_baisc_salary = $annual_da = $annual_hra = $annual_sa =
							$annual_lta = $annual_conveyance_allowance = $annual_medical_allowance = $annual_variable = 0;
						$epf_annual_employer = 0;
						if (is_array($addtional_ar) && !empty($addtional_ar)) {
							foreach ($addtional_ar as $addtional_ar_val) {
								if ($addtional_ar_val['id'] == 1) {
									$annual_gross_salary = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 2) {
									$annual_baisc_salary = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 3) {
									$annual_da = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 4) {
									$annual_hra = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 5) {
									$annual_sa = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 6) {
									$annual_lta = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 7) {
									$annual_conveyance_allowance = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 8) {
									$annual_medical_allowance = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
								if ($addtional_ar_val['id'] == 9) {
									$annual_variable = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}

								if ($addtional_ar_val['id'] == 10) {
									$epf_annual_employer = $addtional_ar_val['unit_amount'] ? $addtional_ar_val['unit_amount'] : 0;
								}
							}
						}
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "Gross CTC (annual)",
							"unit_amount"           => $annual_gross_salary,
							"id"                    => "1"
						);

						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "Basic Salary",
							"unit_amount"           => $annual_baisc_salary,
							"id"                    => "2"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "DA",
							"unit_amount"           => $annual_da,
							"id"                    => "3"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "HRA",
							"unit_amount"           => $annual_hra,
							"id"                    => "4"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "SA",
							"unit_amount"           => $annual_sa,
							"id"                    => "5"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "LTA",
							"unit_amount"           => $annual_lta,
							"id"                    => "6"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "Conveyance Allowance",
							"unit_amount"           => $annual_conveyance_allowance,
							"id"                    => "7"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "Medical Allowance",
							"unit_amount"           => $annual_medical_allowance,
							"id"                    => "8"
						);
						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "Variable",
							"unit_amount"           => $annual_variable,
							"id"                    => "9"
						);

						$payroll_addition[] = array(
							"category_name"         => "addtional",
							"addtion_name"          => "PF Amount Employer (annual)",
							"unit_amount"           => $epf_annual_employer,
							"id"                    => "10"
						);


						// foreach ($addtional_ar as $add_ar) {
						if (is_array($payroll_addition)) {
							$i = 1;
							foreach ($payroll_addition as $key => $value) { ?>
								<tr>
									<th><?php echo $i; ?></th>
									<th><?php echo $value['addtion_name']; ?></th>
									<th>
										<input type="number" class="form-control" name="<?php echo $value['addtion_name']; ?>" value="<?php echo $value['unit_amount']; ?>" pattern="[0-9]*">
									</th>
								</tr>
						<?php $i++;
							}
						} ?>
					</tbody>
				</table>
				<?php if ($this->session->userdata('role_id') == 1 || (!empty($branches) && $view_id != $this->session->userdata('user_id'))) { ?>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				<?php } ?>

			</form>
		</div>
	</div>
	<!-- Additions Tab -->

	<!-- Overtime Tab -->
	<div class="tab-pane" id="tab_overtime">

		<!-- Add Overtime Button -->
		<div class="text-right m-b-30 clearfix">
			<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
		</div>
		<!-- /Add Overtime Button -->

		<div class="payroll-table card">
			<table class="table table-hover table-radius">
				<thead>
					<tr>
						<th>#</th>
						<th>Description</th>
						<th>Date</th>
						<th>Hours</th>
						<th>Status</th>
						<th>Options</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$overtime = $this->db->where('user_id', $employee_details['user_id'])->get('overtime')->result_array();

					if (!empty($overtime)) {
						$o = 1;
						foreach ($overtime as $o_row) {


					?>
							<tr>
								<th><?php echo $o++; ?></th>
								<th><?php echo $o_row['ot_description']; ?></th>
								<td><?php echo date('d M Y', strtotime($o_row['ot_date'])); ?></td>
								<td><?php echo $o_row['ot_hours']; ?></td>
								<td>
									<?php
									if ($o_row['status'] == 0) {
										if ($check_teamlead['is_teamlead'] == 'no')
											echo '<span class="label" style="background:#D2691E"> Pending </span>';
										if ($check_teamlead['is_teamlead'] == 'yes')
											echo '<span class="label" style="background:#D2691E"> Requested </span>';
									} else if ($o_row['status'] == 1) {
										echo '<span class="label label-success"> Approved </span> ';
									} else if ($o_row['status'] == 2) {
										echo '<span class="label label-danger"> Rejected</span>';
									} else if ($o_row['status'] == 3) {
										echo '<span class="label label-danger"> Cancelled</span>';
									}
									?>
								</td>
								<td>
									<?php if ($check_teamlead['is_teamlead'] == 'no') { ?>
										<?php if ($o_row['status'] == 0) { ?>
											<a class="btn btn-danger btn-xs" href="<?= base_url() ?>employees/overtime_cancel/<?= $o_row['id'] ?>/<?php echo $o_row['user_id']; ?>" title="Cancel" data-original-title="Cancel">
												<i class="fa fa-times"></i>
											</a>
										<?php }
									}
									if ($check_teamlead['is_teamlead'] == 'yes') {
										if (($o_row['status'] != 3) &&  ($o_row['status'] != 1)) {
										?>
											<a class="btn btn-success btn-xs" href="<?= base_url() ?>employees/overtime_approve/<?= $o_row['id'] ?>/<?php echo $o_row['user_id']; ?>" title="Approve" data-original-title="Approve">
												<i class="fa fa-thumbs-o-up"></i>
											</a>
											<a class="btn btn-danger btn-xs" href="<?= base_url() ?>employees/overtime_reject/<?= $o_row['id'] ?>/<?php echo $o_row['user_id']; ?>" title="Reject" data-original-title="Reject">
												<i class="fa fa-thumbs-o-down"></i>
											</a>
									<?php }
									} ?>
									<!--<a class="btn btn-danger btn-xs"  
data-toggle="ajaxModal" href="<?= base_url() ?>leaves/delete/<?= $levs['id'] ?>" title="Delete" data-original-title="Delete">
<i class="fa fa-trash-o"></i> 
</a>-->
								</td>
							</tr>

					<?php }
					} ?>
				</tbody>
			</table>
		</div>

	</div>
	<!-- /Overtime Tab -->


	<div class="tab-pane" id="tab_teamovertime">

		<!-- Add Overtime Button -->
		<div class="text-right m-b-30 clearfix">
			<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
		</div>
		<!-- /Add Overtime Button -->

		<div class="payroll-table card">
			<table class="table table-hover table-radius">
				<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Description</th>
						<th>Date</th>
						<th>Hours</th>
						<th>Status</th>
						<th>Options</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$overtime = $this->db->where('teamlead_id', $check_teamlead['id'])->get('overtime')->result_array();



					if (!empty($overtime)) {
						$o = 1;
						foreach ($overtime as $o_row) {


					?>
							<tr>
								<th><?php echo $o++; ?></th>
								<?php if ($check_teamlead['is_teamlead'] == 'yes') {
									$user_details = $this->db->get_where('account_details', array('user_id' => $o_row['user_id']))->row_array(); ?>
									<td><?= $user_details['fullname'] ?></td> <?php } ?>
								<th><?php echo $o_row['ot_description']; ?></th>
								<td><?php echo date('d M Y', strtotime($o_row['ot_date'])); ?></td>
								<td><?php echo $o_row['ot_hours']; ?></td>
								<td>
									<?php
									if ($o_row['status'] == 0) {
										if ($check_teamlead['is_teamlead'] == 'no')
											echo '<span class="label" style="background:#D2691E"> Pending </span>';
										if ($check_teamlead['is_teamlead'] == 'yes')
											echo '<span class="label" style="background:#D2691E"> Requested </span>';
									} else if ($o_row['status'] == 1) {
										echo '<span class="label label-success"> Approved </span> ';
									} else if ($o_row['status'] == 2) {
										echo '<span class="label label-danger"> Rejected</span>';
									} else if ($o_row['status'] == 3) {
										echo '<span class="label label-danger"> Cancelled</span>';
									}
									?>
								</td>
								<td>
									<?php if ($check_teamlead['is_teamlead'] == 'no') { ?>
										<?php if ($o_row['status'] == 0) { ?>
											<a class="btn btn-danger btn-xs" href="<?= base_url() ?>employees/overtime_cancel/<?= $o_row['id'] ?>/<?php echo $o_row['user_id']; ?>" title="Cancel" data-original-title="Cancel">
												<i class="fa fa-times"></i>
											</a>
										<?php }
									}
									if ($check_teamlead['is_teamlead'] == 'yes') {
										if (($o_row['status'] != 3) &&  ($o_row['status'] != 1)) {
										?>
											<a class="btn btn-success btn-xs" href="<?= base_url() ?>employees/overtime_approve/<?= $o_row['id'] ?>/<?php echo $o_row['teamlead_id']; ?>" title="Approve" data-original-title="Approve">
												<i class="fa fa-thumbs-o-up"></i>
											</a>
											<a class="btn btn-danger btn-xs" href="<?= base_url() ?>employees/overtime_reject/<?= $o_row['id'] ?>/<?php echo $o_row['teamlead_id']; ?>" title="Reject" data-original-title="Reject">
												<i class="fa fa-thumbs-o-down"></i>
											</a>
									<?php }
									} ?>
									<!--<a class="btn btn-danger btn-xs"  
data-toggle="ajaxModal" href="<?= base_url() ?>leaves/delete/<?= $levs['id'] ?>" title="Delete" data-original-title="Delete">
<i class="fa fa-trash-o"></i> 
</a>-->
								</td>
							</tr>

					<?php }
					} ?>
				</tbody>
			</table>
		</div>

	</div>

	<!-- Deductions Tab -->
	<div class="tab-pane" id="tab_deductions">

		<?php //if($this->session->userdata('role_id')==1 || ( !empty($branches) && $view_id!= $this->session->userdata('user_id') ) ){
		?>
		<!-- Add Deductions Button -->
		<!-- <div class="text-right m-b-30 clearfix">
<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_deduction"><i class="fa fa-plus"></i> Add Deduction</button>
</div> -->
		<!-- /Add Deductions Button -->
		<?php //}
		?>

		<div class="payroll-table card">
			<form method="post" action="<?php echo base_url(); ?>employees/add_deduction">
				<input type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
				<table class="table table-hover table-radius">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Default/Unit Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php $all_addtional = $this->db->get_where('bank_statutory', array('user_id' => $employee_details['user_id']))->row_array();

						$deduction_ar = json_decode($all_addtional['pf_deduction'], TRUE);
						$i = 1;
						$pf_amount_employer = $pf_amount_employee = $other_deduction = $accomodation_deduction = 0;

						if (is_array($deduction_ar) && !empty($deduction_ar)) {
							foreach ($deduction_ar as $deduction_ar_val) {
								if ($deduction_ar_val['id'] == 1) {
									$pf_amount_employee = $deduction_ar_val['unit_amount'] ? $deduction_ar_val['unit_amount'] : 0;
								}
								if ($deduction_ar_val['id'] == 2) {
									$other_deduction = $deduction_ar_val['unit_amount'] ? $deduction_ar_val['unit_amount'] : 0;
								}
								if ($deduction_ar_val['id'] == 3) {
									$accomodation_deduction = $deduction_ar_val['unit_amount'] ? $deduction_ar_val['unit_amount'] : 0;
								}
							}
						}

						$payroll_deduction[] = array(
							"category_name"         => "deduction",
							"model_name"            => "PF Amount Employee (annual)",
							"unit_amount"           => $pf_amount_employee,
							"id"                    => "1"
						);

						$payroll_deduction[] = array(
							"category_name"         => "deduction",
							"model_name"            => "Accomodation Deduction",
							"unit_amount"           => $other_deduction,
							"id"                    => "2"
						);

						$payroll_deduction[] = array(
							"category_name"         => "deduction",
							"model_name"            => "Other Deduction",
							"unit_amount"           => $accomodation_deduction,
							"id"                    => "3"
						);
						if (is_array($payroll_deduction)) {
							$i = 1;
							foreach ($payroll_deduction as $key => $value) { ?>
								<tr>
									<th><?php echo $i; ?></th>
									<th><?php echo $value['model_name']; ?></th>
									<th>
										<input type="number" class="form-control" name="<?php echo $value['model_name']; ?>" value="<?php echo $value['unit_amount']; ?>" pattern="[0-9]*">
									</th>
								</tr>
						<?php $i++;
							}
						} ?>
					</tbody>
				</table>
				<?php if ($this->session->userdata('role_id') == 1 || (!empty($branches) && $view_id != $this->session->userdata('user_id'))) { ?>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				<?php } ?>
			</form>
		</div>
	</div>
	<!-- /Deductions Tab -->
	<!-- Leaves Tab -->
	<div class="tab-pane" id="tab_leaves">


		<?php
		$user_id = $this->uri->segment(3);



		$leave_details = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
FROM `dgt_user_leaves` ul
left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
left join dgt_account_details ad on ad.user_id = ul.user_id 
where ul.user_id='" . $user_id . "' and lt.branch_id = ad.branch_id ")->result_array();
		// print_r($leave_details); exit;
		?>
		<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
			<thead>
				<tr>
					<th> No </th>
					<th> Leave Type </th>
					<th> Date From </th>
					<th> Date To </th>
					<th> Reason </th>
					<th> No.of Days </th>
					<th> Status </th>

				</tr>
			</thead>
			<tbody id="admin_leave_tbl">
				<?php
				if (!empty($leave_details)) {
					foreach ($leave_details as $key => $details) {  ?>

						<tr>
							<td><?= $key + 1 ?></td>
							<?php if (!empty($details['l_type'])) {
							?>
								<td><?= $details['l_type'] ?></td>
							<?php } else { ?>
								<td>Casual Leave</td>
							<?php } ?>
							<td><?= (!empty($details['leave_from'])) ? date('d-m-Y', strtotime($details['leave_from'])) : '' ?></td>
							<td><?= (!empty($details['leave_to'])) ? date('d-m-Y', strtotime($details['leave_to'])) : '' ?></td>
							<td width="30%"><?= $details['leave_reason'] ?></td>
							<td>
								<?php
								echo $details['leave_days'];
								if ($details['leave_day_type'] == 1) {
									echo ' ( Full Day )';
								} else if ($details['leave_day_type'] == 2) {
									echo ' ( First Half )';
								} else if ($details['leave_day_type'] == 3) {
									echo ' ( Second Half )';
								} ?>
							</td>
							<td>
								<?php
								if ($details['status'] == 4) {
									echo '<span class="label label-info"> TL - Approved</span><br>';
									echo '<span class="label label-danger"> Management - Pending</span>';
								} else if ($details['status'] == 7) {
									echo '<span class="label label-danger"> Deleted </span>';
								}
								if ($details['status'] == 0) {
									echo ' <span class="label" style="background:#D2691E"> Pending </span>';
								} else if ($details['status'] == 1) {
									echo '<span class="label label-success"> Approved </span> ';
								} else if ($details['status'] == 2) {
									echo '<span class="label label-danger"> Rejected</span>';
								} else if ($details['status'] == 3) {
									echo '<span class="label label-danger"> Cancelled</span>';
								}
								?>
							</td>

						</tr>
					<?php  } ?>
				<?php  } else { ?>
					<tr>
						<td class="text-center" colspan="9">No details were found</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>
	<!-- /Leaves Tab -->
	<!-- Document Tab -->
	<div class="tab-pane" id="file_document">
		<h3 class="card-title"> Documents</h3>
		<div class="text-right m-b-30 clearfix">
			<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_document"><i class="fa fa-plus"></i>Upload Document</button>
		</div>
		<div class="table-responsive">
			<table id="table-filess" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Document</th>
						<th>Uploaded Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<a href="/images/myw3schoolsimage.jpg" download></a>
					<?php
					$all_file_attachments = $this->db->get_where('user_document', array('user_id' => $employee_details['user_id']))->result_array();
					$re = 1;
					if (!empty($all_file_attachments)) {
						foreach ($all_file_attachments as $attachment) {
							$ext = pathinfo($attachment['document'], PATHINFO_EXTENSION);
							if (($ext == 'jpg') || ($ext == 'JPG') || ($ext == 'JPEG') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'PNG')) {
								$res = '<a href="' . base_url() . 'assets/uploads/user_document/' . $attachment['document'] . '" download><img src="' . base_url() . 'assets/uploads/user_document/' . $attachment['document'] . '" style="height:50px;width:50px;"></a>';
							} else if ($ext == 'pdf') {
								$res = '<a href="' . base_url() . 'assets/uploads/user_document/' . $attachment['document'] . '" download><span><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size: 50px;"></i></span></a>';
							} else {
								$res = '<a href="' . base_url() . 'assets/uploads/user_document/' . $attachment['document'] . '" download><span><i class="fa fa-file-text-o" aria-hidden="true" style="font-size: 50px;"></i></span></a>';
							}
					?>
							<tr>
								<td><?php echo $re; ?></td>
								<td><?php echo $attachment['document_name']; ?></td>
								<td><?php echo $res; ?></td>
								<td><?php echo date('d M Y', strtotime($attachment['created_at'])); ?></td>
								<td>
									<a href="#" data-toggle="modal" data-target="#edit_document" onclick="edit_document('<?php echo $attachment['id']; ?>')"><i class="fa fa-pencil m-r-5"></i> Edit</a>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="<?php echo base_url() . 'employees/delete_document/' . $attachment['id']; ?>" onclick="return confirm('Are you want to delete?')">Delete</a>
								</td>
							</tr>
						<?php
							$re++;
						}
					} else {
						?>
						<tr>
							<td colspan="4" style="text-align: center;">No Attachment</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	</div>
	<!-- File Attachment Tab -->
	<div class="tab-pane" id="file_attach">
		<h3 class="card-title"> File Attachments </h3>
		<div class="text-right m-b-30 clearfix">
			<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_fileattachment"><i class="fa fa-plus"></i> Files Upload</button>
		</div>
		<div class="table-responsive">
			<table id="table-filess" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th>#</th>
						<th>Attachment File</th>
						<th>Description</th>
						<th>Uploaded Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<a href="/images/myw3schoolsimage.jpg" download></a>
					<?php
					$all_file_attachments = $this->db->get_where('user_files', array('user_id' => $employee_details['user_id']))->result_array();
					$re = 1;
					if (!empty($all_file_attachments)) {
						foreach ($all_file_attachments as $attachment) {
							$ext = pathinfo($attachment['attach_file'], PATHINFO_EXTENSION);
							if (($ext == 'jpg') || ($ext == 'JPG') || ($ext == 'JPEG') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'PNG')) {
								$res = '<a href="' . base_url() . 'assets/uploads/profile_attachments/' . $attachment['attach_file'] . '" download><img src="' . base_url() . 'assets/uploads/profile_attachments/' . $attachment['attach_file'] . '" style="height:50px;width:50px;"></a>';
							} else if ($ext == 'pdf') {
								$res = '<a href="' . base_url() . 'assets/uploads/profile_attachments/' . $attachment['attach_file'] . '" download><span><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size: 50px;"></i></span></a>';
							} else {
								$res = '<a href="' . base_url() . 'assets/uploads/profile_attachments/' . $attachment['attach_file'] . '" download><span><i class="fa fa-file-text-o" aria-hidden="true" style="font-size: 50px;"></i></span></a>';
							}
					?>
							<tr>
								<td><?php echo $re; ?></td>
								<td><?php echo $attachment['description']; ?></td>
								<td><?php echo $res; ?></td>
								<td><?php echo date('d M Y', strtotime($attachment['created_at'])); ?></td>
								<td>
									<a href="#" data-toggle="modal" data-target="#edit_file_attachment" onclick="edit_file_attachment('<?php echo $attachment['user_file_id']; ?>')"><i class="fa fa-pencil m-r-5"></i> Edit</a>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="<?php echo base_url() . 'employees/delete_file_attachment/' . $attachment['user_file_id']; ?>" onclick="return confirm('Are you want to delete?')">Delete</a>
								</td>
							</tr>
						<?php
							$re++;
						}
					} else {
						?>
						<tr>
							<td colspan="4" style="text-align: center;">No Attachment</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	</div>
	<!-- /Leaves Tab -->


</div>
</div>
<!-- Add Document Attachment Modal -->
<div id="add_document" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload Document</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>employees/add_document/<?php echo $employee_details['user_id']; ?>" enctype="multipart/form-data">
					<div class="form-group namediv">
						<label>Name <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="document_name[]" id="document_name" required>
					</div>
					<div class="form-group">
						<label>Upload Document <span class="text-danger">*</span></label>
						<input class="form-control" type="file" name="document_file[]" id="document_file" required>
					</div>
					<div id="add_another">
					</div>
					<div class="form-group">
						<input type="hidden" value="1" id="ipdoc">
						<a class="add-doc" id="add_doc" href="javascript:void(0)" onclick="add_document()" title="Add Document">Add Document</a>
					</div>

					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add Deduction Modal -->

<!-- Edit Document Attachment Modal -->
<div id="edit_document" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload Document</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>employees/edit_document/<?php echo $employee_details['user_id']; ?>" enctype="multipart/form-data">
					<input type="hidden" name="document_id" id="document_id">
					<div class="form-group namediv">
						<label>Name <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="edit_document_name" id="edit_document_name" required>
					</div>
					<div class="form-group">
						<label>Upload Document</label>
						<span class="form_image"><i class="fa fa-document"></i></span>

						<input class="form-control" type="file" name="attachments">
						<input type="hidden" name="exist_file" id="exist_file">

					</div>

					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /edit Deduction Modal -->
<!-- Profile Modal -->
<div id="profile_info" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Profile Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="basic_info_form1" method="post" action="<?php echo base_url(); ?>employees/basic_info_add/<?php echo $employee_details['user_id']; ?>" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div class="profile-img-wrap edit-img">
								<?php if ($employee_details['avatar'] != '') {
									$pro_pict = $employee_details['avatar'];
								} else {
									$pro_pict = 'default_avatar.jpg';
								} ?>
								<img class="inline-block" src="<?php echo base_url(); ?>assets/avatar/<?php echo $pro_pict; ?>" alt="user">
								<div class="fileupload btn">
									<span class="btn-text">Edit</span>
									<input class="upload" type="file" id="employee_pro_pics" name="userfile">
									<input type="hidden" id="employee_user_id" name="users_id" value="<?php echo $employee_details['user_id']; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Full Name</label>
										<input type="text" class="form-control" name="full_name" value="<?php echo $employee_details['fullname']; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Official Email</label>
										<input type="text" name="email" style="cursor: not-allowed;" class="form-control" value="<?php echo $employee_details['email']; ?>" <?php echo $editable; ?> >
									</div>
								</div>


							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Address </label><span><a href="javascript:void(0)" class="office_address"> Head Office</a></span>
								<input type="text" class="form-control" name="address" id="address" value="<?php echo $employee_details['address']; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="city" id="city" value="<?php echo $employee_details['city']; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>State/Province</label>
								<input type="text" class="form-control" name="state" id="state" value="<?php echo $employee_details['state']; ?>">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Postal or Zip Code</label>
								<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $employee_details['pincode']; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label style="display:block;">Phone Number</label>
								<input type="text" class="form-control telephone" name="phone" id="phone" value="<?php echo $employee_details['phone']; ?>">
							</div>
						</div>
						<div class="col-sm-6">

							<div class="form-group">

								<label>Start Date<span class="text-danger">*</span></label>

								<input class="form-control" readonly size="16" type="text" value="<?= $employee_details['doj'] ?>" name="emp_doj" id="emp_doj" data-date-format="yyyy-mm-dd">

							</div>

						</div>
						<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result();
						$get_department = $this->db->get_where('departments', array('deptid' => $employee_details['department_id']))->row_array();
						?>
						<div class="col-md-6">
							<div class="form-group">
								<label><?= lang('department') ?> <span class="text-danger">*</span></label>
								<input type="hidden" name="role" value="3">
								<?php if ($this->session->userdata('user_id') == $employee_details['user_id']) {
									$dept_name = '';
									if (!empty($departments)) {
										foreach ($departments as $department) {
											if ($employee_details['department_id'] == $department->deptid) {
												$dept_name = $department->deptname;
											}
										}
									}
								?>
									<input class="form-control" style="width:100%;" name="department_name" id="department_name" disabled value="<?php echo $dept_name; ?>">
								<?php
								} else {
								?>

									<select class="<?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "form-control" : "select2-option"; ?>" style="width:100%;" name="department_name" id="department_name" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
										<!--<option value="<?php echo $get_department['deptid'] ? $get_department['deptid'] : ''; ?>" selected ><?php echo $get_department['deptname'] ? $get_department['deptname'] : 'Select'; ?></option>-->
										<?php
										if (!empty($departments)) {
											foreach ($departments as $department) { ?>
												<option value="<?= $department->deptid ?>" <?php echo ($employee_details['department_id'] == $department->deptid) ? 'selected' : ''; ?>><?= $department->deptname ?></option>
											<?php } ?>
										<?php } ?>

									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<?php //$get_designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array(); 
								?>
								<?php $get_designation = $this->db->select('id,designation')->from('designation')->where('department_id', $employee_details['department_id'])->order_by("designation", "asc")->get()->result_array();
								// print_r($get_designation);exit;

								?>

								<label>Position <span class="text-danger">*</span></label>
								<?php if ($this->session->userdata('user_id') == $employee_details['user_id']) {
									$get_designation = $this->db->get_where('designation', array('id' => $employee_details['designation_id']))->row_array();
								?>
									<input type="text" class="form-control" style="width:100%;" name="designations" id="designations" disabled value="<?php echo $get_designation['designation']; ?>">
								<?php
								} else { ?>
									<select class="form-control" style="width:100%;" name="designations" id="designations" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
										<?php
										if (!empty($get_designation)) {
											foreach ($get_designation as $designation_1) {
										?>
												<option value="<?php echo $designation_1['id'] ? $designation_1['id'] : ''; ?>" <?php if ($employee_details['designation_id'] == $designation_1['id']) {
																																	echo 'selected';
																																} ?>><?php echo $designation_1['designation'] ? $designation_1['designation'] : 'Select'; ?></option>
										<?php }
										} ?>
									</select>
								<?php } ?>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">

								<label>Is reporter <span class="text-danger">*</span></label>

								<select class="form-control" style="width:100%;" name="is_reporter" id="is_reporter">
									<option selected disabled value="">Select</option>
									<option value="yes" <?php echo ($employee_details['is_reporter'] == 'yes') ? 'selected' : ''; ?>>Yes</option>
									<option value="no" <?php echo (($employee_details['is_reporter'] == 'no' || $employee_details['is_reporter'] == 'no')) ? 'selected' : ''; ?>>No</option>
								</select>

							</div>

						</div>
						<?php if ($this->session->userdata('branch_id') == 0) { ?>
							<div class="col-sm-6">

								<div class="form-group">
									<label>Entity<span class="text-danger">*</span> </label>
									<input type="hidden" name="branch" value="<?php echo $employee_details['branch_id']; ?>">
									<?php
									$branches = $this->db->where('branch_status', '0')->get('branches')->result();
									?>
									<select class="select2-option" style="width:100%;" name="branch" id="add_branch" required <?php echo $disabled; ?>>
										<option value="" selected disabled>Branch</option>
										<?php
										if (!empty($branches)) {
											foreach ($branches as $branch1) { ?>
												<option value="<?= $branch1->branch_id ?>" <?php echo ($branch1->branch_id == $employee_details['branch_id']) ? 'selected' : ''; ?>><?= $branch1->branch_name ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>

							</div>
						<?php } ?>
						<?php
						$branche_det = $this->db->where('branch_id', $employee_details['branch_id'])->where('branch_status', '0')->get('branches')->row_array();
						if ($employee_details['employee_id'] == '') {
							$users = $this->db->select('*')
								->from('dgt_users U')
								->join('account_details AD', 'U.id = AD.user_id')
								->where('AD.branch_id', $employee_details['branch_id'])
								->get()->result_array();
							if (empty($users) && isset($users)) {
								$emp_id = 0;
							} else {
								$emp_id = count($users);
							}
							$emp_id++;
							//	$employee_no = '00'.$emp_id;
							$employee_no = '';
						} else {
							$employee_no = $employee_details['employee_id'];
						}
						?>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Prefix </label>
								<input type="text" class="form-control" name="branch_prefix" id="branch_prefix" value="<?= $branche_det['branch_prefix'] ?>" readonly>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Employee Id </label>
								<input type="text" class="form-control" name="employee_id" id="employee_id" value="<?php echo $employee_no; ?>" <?php echo ($this->session->userdata('role_id') != 1) ? "disabled" : ""; ?>>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label> Full EmployeeID <span class="text-danger">*</span></label>


								<input type="text" class="form-control" id="employee_id" value="<?php echo $employee_details['emp_code']; ?>" readonly>
							</div>
						</div>
						<div class="col-sm-6 TeamDiv">

							<?php //$get_users = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array(); 

							// $get_users = $this->db->select('AD.*')->from('users U')->join('account_details AD','U.id = AD.user_id')->where('U.department_id',$employee_details['department_id'])->where('U.activated',1)->where('U.banned',0)->get()->result_array();
							// $get_users = $this->db->select('AD.*')->from('users U')->join('account_details AD','U.id = AD.user_id')->where('AD.branch_id',$employee_details['branch_id'])->where('U.is_reporter','yes')->where('U.activated',1)->where('U.banned',0)->get()->result_array();
							$get_users = $this->db->select('AD.*')->from('users U')->join('account_details AD', 'U.id = AD.user_id')->where('U.is_reporter', 'yes')->where('U.activated', 1)->where('U.banned', 0)->get()->result_array();
							// echo $this->db->last_query();
							// print_r($get_users);exit;
							?>
							<div class="form-group">
								<label>Reporting to </label>
								<?php if ($this->session->userdata('user_id') != $employee_details['user_id']) { ?>
									<select class="form-control" style="width:100%;" name="reporting_to" id="reporting_to" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
										<option value="">Select</option>
										<?php
										if (!empty($get_users)) {
											foreach ($get_users as $user_1) {
										?>
												<option value="<?php echo $user_1['user_id'] ? $user_1['user_id'] : ''; ?>" <?php if ($employee_details['teamlead_id'] == $user_1['user_id']) {
																																echo 'selected';
																															} ?>><?php echo $user_1['fullname'] ? $user_1['fullname'] : 'Select'; ?></option>
										<?php }
										} ?>
									</select>
								<?php } else {
									$get_users = $this->db->get_where('account_details', array('user_id' => $employee_details['teamlead_id']))->row_array();
								?>
									<input type="text" class="form-control" style="width:100%;" name="reporting_to" id="reporting_to" value="<?php echo $get_users['fullname'] ? $get_users['fullname'] : ''; ?>" disabled>

								<?php } ?>

							</div>
						</div>
						<?php

						$user_type = $this->db->order_by('role', 'asc')->where('r_id', $employee_details['user_type'])->get('roles')->result();
						?>
						<input type="hidden" value="<?php echo $user_type[0]->r_id; ?>" name="user_type">

						<div class="col-sm-6">

							<div class="form-group">

								<label><?= lang('user_type') ?> <span class="text-danger">*</span></label>
								<?php if ($this->session->userdata('user_id') == $employee_details['user_id']) {
									$type_name = '';
									if (!empty($user_type)) {
										foreach ($user_type as $type) {
											if ($type->role != 'admin' && $type->role != 'company_admin') {
												////if($employee_details['user_type'] == $type->r_id){
												$type_name = $type->role;
												//}
											}
										}
									}
								?>
									<input type="text" class="form-control" style="width:100%;" name="user_type" id="user_type" required disabled value="<?php echo $type_name; ?>">
								<?php
								} else { ?>
									<select class="select2-option" style="width:100%;" name="user_type" id="user_type" required <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
										<option value="" selected disabled>User Type</option>
										<?php

										$user_type = $this->db->get('roles')->result();


										if (!empty($user_type)) {

											foreach ($user_type as $type) {
												if ($type->role != 'admin' && $type->role != 'company_admin') {

										?>

													<option value="<?= $type->r_id ?>" <?php echo ($employee_details['user_type'] == $type->r_id) ? 'selected' : ''; ?>><?= $type->role ?></option>

											<?php
												}
											} ?>

										<?php } ?>



									</select>
								<?php } ?>
							</div>

						</div>


						<?php /*<div class="col-sm-6">

<div class="form-group">
<?php 
$user_type = $this->db->order_by("role", "asc")->where('branch_id',$this->session->userdata('branch_id'))->get('roles')->result(); 
$get_usertype = $this->db->get_where('roles',array('r_id'=>$employee_details['user_type']))->row_array(); ?>

<label>User Type <span class="text-danger">*</span></label>

<select class="form-control" style="width:100%;" name="user_type" id="user_type" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"disabled":"";?>>
<!--<option value="<?php echo $get_usertype['r_id']?$get_usertype['r_id']:'';?>" selected ><?php echo $get_usertype['r_id']?$get_usertype['role']:'Select';?></option>-->
<?php
if(!empty($user_type))	{
foreach ($user_type as $usertype){ ?>
<option <?php if($get_usertype['r_id'] == $usertype->r_id){echo 'selected';}
?> value="<?=$usertype->r_id?>"><?=$usertype->role?></option>
<?php } ?>
<?php } ?>
</select>

</div>

</div>*/ ?>
						<?php if ($this->session->userdata('role_id') == 1) { ?>
							<div class="col-md-6">
								<div class="form-group">
									<label class=""><?= lang('type_change') ?> <span class="text-danger">*</span></label>
									<div class="">
										<div class="m-b">
											<select class="select2-option form-control" style="width:100%" name="type_change" id="type_change" required <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
												<option value="-" <?php echo ($employee_details['type_change'] == '-') ? 'selected' : ''; ?>>None</option>
												<option value="Internal Transfer" <?php echo ($employee_details['type_change'] == 'Internal Transfer') ? 'selected' : ''; ?>>Internal Transfer</option>
												<option value="External Transfer" <?php echo ($employee_details['type_change'] == 'External Transfer') ? 'selected' : ''; ?>>External Transfer</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						<?php if ($this->session->userdata('user_id') == $employee_details['user_id']) { ?>
							<div class="col-md-6">
								<div class="form-group">
									<label class=""><?= lang('location') ?> </label>
									<div class="">

										<input type="text" class="form-control" style="width:100%;" name="location" id="location" value="<?php echo $employee_details['location1']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<div class="col-md-6">
								<div class="form-group">
									<label class=""><?= lang('location') ?> </label>
									<div class="">

										<select class="form-control" style="width:100%;" name="location" id="location">
											<option value="46 Ounces" <?php echo ($employee_details['location1'] == '46 Ounces') ? 'selected' : ''; ?>>46 Ounces</option>
											<option value="46 Ounces - Cafe Capriccio" <?php echo ($employee_details['location1'] == '46 Ounces - Cafe Capriccio') ? 'selected' : ''; ?>>46 Ounces - Cafe Capriccio</option>
											<option value="Oterra" <?php echo ($employee_details['location1'] == 'Oterra') ? 'selected' : ''; ?>>Oterra</option>
											<option value="Otium Bangalore" <?php echo ($employee_details['location1'] == 'Otium Bangalore') ? 'selected' : ''; ?>>Otium Bangalore</option>
											<option value="Otium Goa" <?php echo ($employee_details['location1'] == 'Otium Goa') ? 'selected' : ''; ?>>Otium Goa</option>
											<option value="Velankani AI & Cloud Solutions LLP" <?php echo ($employee_details['location1'] == 'Velankani AI & Cloud Solutions LLP') ? 'selected' : ''; ?>>Velankani AI & Cloud Solutions LLP</option>
											<option value="VEPL" <?php echo ($employee_details['location1'] == 'VEPL') ? 'selected' : ''; ?>>VEPL-Unit2</option>
											<option value="VISL" <?php echo ($employee_details['location1'] == 'VISL') ? 'selected' : ''; ?>>VISL</option>

											<option value="46 Ounces" <?php echo ($employee_details['location1'] == '46 Ounces') ? 'selected' : ''; ?>>46 Ounces</option>

											<option value="VEPL-B1" <?php echo ($employee_details['location1'] == 'VEPL-B1') ? 'selected' : ''; ?>>VEPL-B1</option>
											<option value="VEPL-JIGANI" <?php echo ($employee_details['location1'] == 'VEPL-JIGANI') ? 'selected' : ''; ?>>VEPL-JIGANI</option>


										</select>
									</div>
								</div>
							</div>
						<?php }/*?>

<div class="col-sm-6">

<div class="form-group">
<label>Reporting To2 </label>
<input type="text" class="form-control" name="reporting_to2" id="reporting_to2" value="<?php echo $employee_details['reporting_to2']; ?>" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"disabled":"";?>>
</div>

</div>*/ ?>
						<input type="hidden" class="form-control" name="reporting_to2" id="reporting_to2" value="<?php echo $employee_details['reporting_to2']; ?>" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Personal Email </label>
								<input type="email" class="form-control" name="personal_email" id="personal_email" value="<?php echo $employee_details['personal_email']; ?>">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Group Joining Date </label>
								<input class="form-control datepicker1" readonly size="16" type="text" value="<?php echo (!empty($employee_details['emp_gjd']) && ($employee_details['emp_gjd'] != "0000-00-00")) ? date('Y-m-d', strtotime($employee_details['emp_gjd'])) : ''; ?>" name="emp_gjd" id="emp_gjd" data-date-format="yyyy-mm-dd">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Date Of Birth </label>
								<input class="form-control datepicker1" readonly size="16" type="date" name="dob" id="dob" data-date-format="yyyy-mm-dd" value="<?php echo (!empty($employee_details['dob'])) ? date('Y-m-d', strtotime($employee_details['dob'])) : ''; ?>">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Exit Date </label>
								<input class="form-control datepicker1" readonly size="16" type="date" name="exit_date" id="exit_date" data-date-format="yyyy-mm-dd" value="<?php echo (!empty($employee_details['exit_date'])) ? date('Y-m-d', strtotime($employee_details['exit_date'])) : ''; ?>">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<?php
								$this->db->select('id,grade');
								$this->db->from('designation');
								$this->db->where('id', $employee_details['designation_id']);
								$this->db->order_by("designation", "asc");
								$records = $this->db->get()->row_array();
								$grade_id = $records['grade'];

								$this->db->select('grade_id,grade_name');
								$this->db->from('dgt_grades');
								if (!empty($this->session->userdata('branch_id'))) {
									$this->db->where('branch_id', $this->session->userdata('branch_id'));
								}
								$this->db->order_by("grade_name", "asc");
								$records = $this->db->get()->result_array();
								?>
								<label>Employee Level </label>
								<?php if ($this->session->userdata('user_id') == $employee_details['user_id']) {
									$grade_name = '';
									if (!empty($records)) {
										foreach ($records as $record1) {
											if ($grade_id == $record1['grade_id']) {
												$grade_name = $record1['grade_name'];
											}
										}
									}
								?>
									<input type="text" class="form-control" style="width:100%;" name="employee_level" id="employee_level" disabled value="<?php echo $employee_details['employee_level']; ?>">
								<?php } else { ?>
									<select class="form-control" style="width:100%;" name="employee_level" id="employee_level" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
										<option value="">Select</option>
										<option value="JB 1" <?php echo ($employee_details['employee_level'] == 'JB 1') ? 'selected' : ''; ?>>JB 1</option>
										<option value="JB 2" <?php echo ($employee_details['employee_level'] == 'JB 2') ? 'selected' : ''; ?>>JB 2</option>
										<option value="JB 3" <?php echo ($employee_details['employee_level'] == 'JB 3') ? 'selected' : ''; ?>>JB 3</option>
										<option value="JB 4" <?php echo ($employee_details['employee_level'] == 'JB 4') ? 'selected' : ''; ?>>JB 4</option>
										<option value="JB 5" <?php echo ($employee_details['employee_level'] == 'JB 5') ? 'selected' : ''; ?>>JB 5</option>
										<option value="JB 6" <?php echo ($employee_details['employee_level'] == 'JB 6') ? 'selected' : ''; ?>>JB 6</option>
										<option value="JB 7" <?php echo ($employee_details['employee_level'] == 'JB 7') ? 'selected' : ''; ?>>JB 7</option>
										<option value="JB 8" <?php echo ($employee_details['employee_level'] == 'JB 8') ? 'selected' : ''; ?>>JB 8</option>
										<option value="JB 9" <?php echo ($employee_details['employee_level'] == 'JB 9') ? 'selected' : ''; ?>>JB 9</option>

										<option value="L1" <?php echo ($employee_details['employee_level'] == 'L1') ? 'selected' : ''; ?>>L1</option>
										<option value="L2" <?php echo ($employee_details['employee_level'] == 'L2') ? 'selected' : ''; ?>>L2</option>
										<option value="L3" <?php echo ($employee_details['employee_level'] == 'L3') ? 'selected' : ''; ?>>L3</option>
										<option value="L4" <?php echo ($employee_details['employee_level'] == 'L4') ? 'selected' : ''; ?>>L4</option>
										<option value="L5" <?php echo ($employee_details['employee_level'] == 'L5') ? 'selected' : ''; ?>>L5</option>
										<option value="L6" <?php echo ($employee_details['employee_level'] == 'L6') ? 'selected' : ''; ?>>L6</option>
										<option value="L7" <?php echo ($employee_details['employee_level'] == 'L7') ? 'selected' : ''; ?>>L7</option>
										<option value="L8" <?php echo ($employee_details['employee_level'] == 'L8') ? 'selected' : ''; ?>>L8</option>
										<?php
										/*if(!empty($records)){
foreach($records as $record1){
?>
<option value="<?php echo $record1['grade_id'];?>" <?php echo ($grade_id == $record1['grade_id'])?'selected':'';?>><?php echo $record1['grade_name'];?></option>
<?php
}
}*/
										?>
									</select>
								<?php } ?>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Seat Location </label>
								<input type="text" class="form-control" name="seat_location" id="seat_location" value="<?php echo $employee_details['seat_location']; ?>">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Division </label>
								<input type="text" class="form-control" name="division" id="division" value="<?php echo $employee_details['division']; ?>" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Business Unit </label>
								<input type="text" class="form-control" name="business_unit" id="business_unit" value="<?php echo $employee_details['business_unit']; ?>" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Cost Center </label>
								<input type="text" class="form-control" name="cost_center" id="cost_center" value="<?php echo $employee_details['cost_center']; ?>" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id']) ? "disabled" : ""; ?>>
							</div>

						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label>Father Name </label>
								<input type="text" class="form-control" name="fatherName" id="fatherName" value="<?php echo $employee_details['fatherName']; ?>">
							</div>
						</div>


						<!-- <?php if ($this->session->userdata('branch_id') == 0) { ?>
<div class="col-sm-6">

<div class="form-group">
<label>Entity<span class="text-danger">*</span> </label>
<input type="hidden" name="branch" value="<?php echo $employee_details['branch_id']; ?>">
<?php
									$branches = $this->db->where('branch_status', '0')->get('branches')->result();
?>
<select class="select2-option" style="width:100%;" name="branch" id="add_branch" required disabled>
<option value="" selected disabled>Branch</option>
<?php
									if (!empty($branches)) {
										foreach ($branches as $branch1) { ?>
<option value="<?= $branch1->branch_id ?>" <?php echo ($branch1->branch_id == $employee_details['branch_id']) ? 'selected' : ''; ?>><?= $branch1->branch_name ?></option>
<?php } ?>
<?php } ?>
</select>
</div>

</div>
<?php } ?>
<?php
$branche_det = $this->db->where('branch_id', $employee_details['branch_id'])->where('branch_status', '0')->get('branches')->row_array();
if ($employee_details['employee_id'] == '') {
	$users = $this->db->select('*')
		->from('dgt_users U')
		->join('account_details AD', 'U.id = AD.user_id')
		->where('AD.branch_id', $employee_details['branch_id'])
		->get()->result_array();
	if (empty($users) && isset($users)) {
		$emp_id = 0;
	} else {
		$emp_id = count($users);
	}
	$emp_id++;
	//	$employee_no = '00'.$emp_id;
	$employee_no = '';
} else {
	$employee_no = $employee_details['employee_id'];
}
?>
<div class="col-sm-2">
<div class="form-group">
<label>Prefix </label>
<input type="text" class="form-control" name="branch_prefix" id="branch_prefix" value="<?= $branche_det['branch_prefix'] ?>" readonly>
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
<label>Employee Id </label>
<input type="text" class="form-control" name="employee_id" id="employee_id" value="<?php echo $employee_no; ?>" <?php echo ($this->session->userdata('role_id') != 1) ? "disabled" : ""; ?>> 
</div>
</div>
<div class="col-sm-4">
<div class="form-group">
<label> <span class="text-danger">*</span></label>


<input type="text" class="form-control" id="employee_id" value="<?php echo $employee_details['emp_code']; ?>" readonly> 
</div>
</div> -->
						<!--<div class="col-sm-6">

<div class="form-group">
<label>Dynamic Field </label>
<?php
$fields = $this->db->get('dynamicfield')->result();
?>
<select class="select2-option" style="width:100%;" name="dynamic_field" id="dynamic_field" required>
<option value="" selected disabled>Dyamic Field</option>
<?php
if (!empty($fields)) {
	foreach ($fields as $field1) { ?>
<option value="<?= $field1->id ?>" <?php echo ($field1->id == $employee_details['dynamic_field']) ? 'selected' : ''; ?>><?= $field1->field ?></option>
<?php }
} ?>
</select>
</div>

</div>-->


					</div>


					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="basic_info_btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Profile Modal -->

<!-- Bank Modal -->
<div id="bank_iformation_modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Bank Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="bank_info_form" method="post" action="<?php echo base_url(); ?>employees/bank_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Bank Name</label>
										<input type="text" class="form-control" name="bank_name" value="<?php echo $bank_info->bank_name; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Bank Account No</label>
										<input type="text" name="bank_ac_no" id="bank_ac_no" class="form-control" value="<?php echo $bank_info->bank_ac_no; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>IFSC Code</label>
										<input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="<?php echo $bank_info->ifsc_code; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>PAN No</label>
										<input type="text" name="pan_no" id="pan_no" class="form-control" value="<?php echo $bank_info->pan_no; ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="basic_info_btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Bank Modal -->

<!-- Personal Info Modal -->
<div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Personal Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="personal_info_form" method="post" action="<?php echo base_url(); ?>employees/personal_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php
								$personal_info = json_decode($personal_details['personal_info']);
								?>
								<label>Aadhar Card No. <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?php echo $personal_info->aadhar; ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pan Card No. <span class="text-danger">*</span></label>
								<?php /*<div class="cal-icon">*/ ?>
								<input class="form-control <?php /*datetimepicker*/ ?>" type="text" name="pan_card_no" id="pan_card_no" <?php /*data-date-format="dd-mm-yyyy"*/ ?> value="<?php echo $personal_info->pan_card; ?>" required>
								<?php /*</div>*/ ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Tel </label>
								<input class="form-control" type="text" name="tel_number" id="tel_number" value="<?php echo $personal_info->tel_number; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Nationality </label>
								<input class="form-control" type="text" name="nationality" id="nationality" value="<?php echo $personal_info->nationality; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Religion </label>
								<input class="form-control" type="text" name="religion" id="religion" value="<?php echo $personal_info->religion; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Marital status </label>
								<select class="select form-control" name="marital_status" id="marital_status">
									<option value="" disabled>-</option>
									<option value="single" <?php if ($personal_info->religion == 'single') {
																echo "selected";
															} ?>>Single</option>
									<option value="married" <?php if ($personal_info->religion == 'married') {
																echo "selected";
															} ?>>Married</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Employment of spouse</label>
								<input class="form-control" type="text" name="spouse" id="spouse" value="<?php echo $personal_info->spouse; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No. of children</label>
								<input class="form-control" type="text" name="no_children" id="no_children" value="<?php echo $personal_info->no_children; ?>">
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Personal Info Modal -->

<!-- Family Info Modal -->
<div id="family_info_modal" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Family Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" id="family_info_form" action="<?php echo base_url(); ?>employees/family_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="form-scroll">
						<div class="card-box AllFamilyMembers">
							<?php $personal_info = json_decode($personal_details['family_members_info']);
							// echo "<pre>"; print_r($personal_info); exit;
							if (count($personal_info) != 0) {
								$i = 1;
								foreach ($personal_info as $per) {
							?>
									<div class="FamilyMembers">
										<h3 class="card-title">Family Member </h3><?php if ($i != 1) { ?> <a href="#" class="remove_family_div"><i class="fa fa-trash-o"></i></a> <?php } ?>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="member_name[]" value="<?php echo $per->member_name; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="member_relationship[]" value="<?php echo $per->member_relationship; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Date of birth <span class="text-danger">*</span></label>
													<input class="form-control ALlmembers" type="text" name="member_dob[]" value="<?php echo $per->member_dob; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="member_phone[]" value="<?php echo $per->member_phone; ?>">
												</div>
											</div>
										</div>
									</div>
								<?php $i++;
								}
							} else { ?>
								<div class="FamilyMembers">
									<h3 class="card-title">Family Member </h3>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Name <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="member_name[]">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Relationship <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="member_relationship[]">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Date of birth <span class="text-danger">*</span></label>
												<input class="form-control ALlmembers" type="text" name="member_dob[]">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Phone <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="member_phone[]">
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="add-more">
								<a href="#" id="add_more_family"><i class="fa fa-plus-circle"></i> Add More</a>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Family Info Modal -->

<!-- Emergency Contact Modal -->
<div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Emergency Contact Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="emergency_form" method="post" action="<?php echo base_url(); ?>employees/emergency_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="card-box">
						<h3 class="card-title">Primary Contact</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Name <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="contact_name1" id="contact_name1" value="<?php echo $emergency_info->contact_name1; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Relationship <span class="text-danger">*</span></label>
									<select class="select form-control" name="relationship1" id="relationship1">
										<option value="" disabled>-</option>
										<option value="father" <?php if ($emergency_info->relationship1 == 'father') {
																	echo "selected";
																}  ?>>Father</option>
										<option value="mother" <?php if ($emergency_info->relationship1 == 'mother') {
																	echo "selected";
																}  ?>>Mother</option>
										<option value="sister" <?php if ($emergency_info->relationship1 == 'sister') {
																	echo "selected";
																}  ?>>Sister</option>
										<option value="brother" <?php if ($emergency_info->relationship1 == 'brother') {
																	echo "selected";
																}  ?>>Brother</option>
										<option value="others" <?php if ($emergency_info->relationship1 == 'others') {
																	echo "selected";
																}  ?>>Others</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="contact1_phone1" id="contact1_phone1" value="<?php echo $emergency_info->contact1_phone1;  ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone 2</label>
									<input class="form-control" type="text" name="contact1_phone2" id="contact1_phone2" value="<?php echo $emergency_info->contact1_phone2;  ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="card-box">
						<h3 class="card-title">Secondary Contact</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Name </label>
									<input type="text" class="form-control" name="contact_name2" id="contact_name2" value="<?php echo $emergency_info->contact_name2;  ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Relationship </label>
									<select class="select form-control" name="relationship2" id="relationship2">
										<option value="" disabled>-</option>
										<option value="father" <?php if ($emergency_info->relationship2 == 'father') {
																	echo "selected";
																}  ?>>Father</option>
										<option value="mother" <?php if ($emergency_info->relationship2 == 'mother') {
																	echo "selected";
																}  ?>>Mother</option>
										<option value="sister" <?php if ($emergency_info->relationship2 == 'sister') {
																	echo "selected";
																}  ?>>Sister</option>
										<option value="brother" <?php if ($emergency_info->relationship2 == 'brother') {
																	echo "selected";
																}  ?>>Brother</option>
										<option value="others" <?php if ($emergency_info->relationship2 == 'others') {
																	echo "selected";
																}  ?>>Others</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone </label>
									<input class="form-control" type="text" name="contact2_phone1" id="contact2_phone1" value="<?php echo $emergency_info->contact2_phone1;  ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone 2</label>
									<input class="form-control" type="text" name="contact2_phone2" id="contact2_phone2" value="<?php echo $emergency_info->contact2_phone2;  ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Emergency Contact Modal -->

<!-- Education Modal -->
<div id="education_info" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Education Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" id="education_info_form" action="<?php echo base_url(); ?>employees/education_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="form-scroll AllInstitute">
						<?php $i = 1;
						// print_r($personal_details); exit;
						if (!empty($education_details)) {
							// $pers = json_decode($personal_details['education_details']);
							foreach ($education_details as $p) { ?>
								<div class="card-box MultipleInstitutions">
									<h3 class="card-title">Education Information</h3> <?php if ($i != 1) { ?> <a href="#" class="remove_div"><i class="fa fa-trash-o"></i></a> <?php } ?>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-focus focused">
												<input type="text" class="form-control floating" name="institute[]" value="<?php echo $p->institute; ?>">
												<label class="control-label">Institution</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus focused">
												<input type="text" class="form-control floating" name="subject[]" value="<?php echo $p->subject; ?>">
												<label class="control-label">Subject</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus focused">
												<div class="cal-icon">
													<input type="text" name="start_date[]" class="form-control floating datetimepicker" value="<?php echo $p->start_date; ?>">
												</div>
												<label class="control-label">Starting Date</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus focused">
												<div class="cal-icon">
													<input type="text" name="end_date[]" class="form-control floating datetimepicker" value="<?php echo $p->end_date; ?>">
												</div>
												<label class="control-label">Complete Date</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus focused">
												<input type="text" name="degree[]" class="form-control floating" value="<?php echo $p->degree; ?>">
												<label class="control-label">Degree</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus focused">
												<input type="text" name="grade[]" class="form-control floating" value="<?php echo $p->grade; ?>">
												<label class="control-label">Grade</label>
											</div>
										</div>
									</div>
								</div>
							<?php $i++;
							}
						} else { ?>
							<div class="card-box MultipleInstitutions">
								<h3 class="card-title">Education Information </h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" value="" class="form-control floating" name="institute[]">
											<label class="control-label">Institution</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" class="form-control floating" name="subject[]">
											<label class="control-label">Subject</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" name="start_date[]" class="form-control floating datetimepicker">
											</div>
											<label class="control-label">Starting Date</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<div class="cal-icon">
												<input type="text" name="end_date[]" class="form-control floating datetimepicker">
											</div>
											<label class="control-label">Complete Date</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" name="degree[]" class="form-control floating">
											<label class="control-label">Degree</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus focused">
											<input type="text" name="grade[]" class="form-control floating">
											<label class="control-label">Grade</label>
										</div>
									</div>
								</div>
							</div>
						<?php }  ?>
						<div class="add-more">
							<a href="#" id="Add_more_institution"><i class="fa fa-plus-circle"></i> Add More Institute</a>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Education Modal -->

<!-- Experience Modal -->

<div id="experience_info" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Experience Informations</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" id="experience_info_form" action="<?php echo base_url(); ?>employees/experience_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="form-scroll">
						<div class="card-box AllExperience">
							<?php $i = 1;

							$pers = json_decode($personal_details['personal_details']);
							// echo "<pre>"; print_r($pers); exit;
							if (!empty($pers)) {
								foreach ($pers as $p) { ?>
									<div class="MultipleExperience">
										<h3 class="card-title">Experience Informations </h3> <?php if ($i != 1) { ?> <a href="#" class="remove_exp_div"><i class="fa fa-trash-o"></i></a><?php } ?>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group form-focus">
													<input type="text" class="form-control floating" value="<?php echo $p->company_name; ?>" name="company_name[]">
													<label class="control-label">Company Name</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus">
													<input type="text" class="form-control floating" value="<?php echo $p->location; ?>" name="location[]">
													<label class="control-label">Location</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus">
													<input type="text" class="form-control floating" value="<?php echo $p->job_position; ?>" name="job_position[]">
													<label class="control-label">Job Position</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus">
													<div class="cal-icon">
														<input type="text" class="form-control floating datetimepicker" value="<?php echo $p->period_from; ?>" name="period_from[]">
													</div>
													<label class="control-label">Period From</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus">
													<div class="cal-icon">
														<input type="text" class="form-control floating datetimepicker" value="<?php echo $p->period_to; ?>" name="period_to[]">
													</div>
													<label class="control-label">Period To</label>
												</div>
											</div>
										</div>
									</div>
								<?php $i++;
								}
							} else { ?>
								<div class="MultipleExperience">
									<h3 class="card-title">Experience Informations </h3>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-focus">
												<input type="text" class="form-control floating" value="" name="company_name[]">
												<label class="control-label">Company Name</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<input type="text" class="form-control floating" value="" name="location[]">
												<label class="control-label">Location</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<input type="text" class="form-control floating" value="" name="job_position[]">
												<label class="control-label">Job Position</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<div class="cal-icon">
													<input type="text" class="form-control floating datetimepicker" value="" name="period_from[]">
												</div>
												<label class="control-label">Period From</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<div class="cal-icon">
													<input type="text" class="form-control floating datetimepicker" value="" name="period_to[]">
												</div>
												<label class="control-label">Period To</label>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="add-more">
							<a href="#" id="Add_experience"><i class="fa fa-plus-circle"></i> Add More</a>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Experience Modal -->

<!-- Add Addition Modal -->
<!-- <div id="add_addition" class="modal center-modal fade" role="dialog">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Add Addition</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form method="post" id="addtional_form" action="<?php echo base_url(); ?>employees/addtional_pf_details">
<div class="form-group">
<label>Name <span class="text-danger">*</span></label>
<input class="form-control" type="text" name="addtion_name" id="addtion_name" value="" placeholder="Name"> 
<input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_details['user_id']; ?>">
</div>
<div class="form-group">
<label>Category <span class="text-danger">*</span></label>
<select class="select" name="category_name" id="category_name">
<option value="" selected disabled>Select a category</option>
<option value="monthly" >Monthly remuneration</option>
<option value="addtional">Additional remuneration</option>
</select>
</div>
<div class="form-group">
<label>Unit Amount</label>
<div class="input-group">
<span class="input-group-addon">
$
</span>
<input type="text" class="form-control" name="unit_amount" id="unit_amount" value="" placeholder="Unit Amount">
<span class="input-group-addon">
.00
</span>
</div>
</div>
<div class="submit-section">
<button class="btn btn-primary submit-btn">Submit</button>
</div>
</form>
</div>
</div>
</div>
</div> -->
<!-- /Add Addition Modal -->

<?php foreach ($addtional_ar as $add_ar) { ?>

	<!-- Edit Addition Modal -->
	<!-- <div id="edit_addition<?php echo $add_ar['id']; ?>" class="modal center-modal fade" role="dialog">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Edit Addition</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form method="post" id="edit_addtional_form" action="<?php echo base_url(); ?>employees/edit_additional/<?php echo $add_ar['id']; ?>">
<div class="form-group">
<label>Name <span class="text-danger">*</span></label>
<input class="form-control" type="text" name="addtion_name" value="<?php echo $add_ar['addtion_name']; ?>" >
<input type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
</div>
<div class="form-group">
<label>Category <span class="text-danger">*</span></label>
<select class="select" name="category_name">
<option value="" disabled>Select a category</option>
<option value="monthly" <?php if ($add_ar['category_name'] == 'monthly') {
							echo "selected";
						} ?>>Monthly remuneration</option>
<option value="addtional" <?php if ($add_ar['category_name'] == 'addtional') {
								echo "selected";
							} ?>>Additional remuneration</option>
</select>
</div>
<div class="form-group">
<label>Unit Amount</label>
<div class="input-group">
<span class="input-group-addon">
$
</span>
<input type="text" class="form-control" name="unit_amount" value="<?php echo $add_ar['unit_amount']; ?>">
<span class="input-group-addon">
.00
</span>
</div>
</div>
<div class="submit-section">
<button class="btn btn-primary submit-btn">Save</button>
</div>
</form>
</div>
</div>
</div>
</div> -->
	<!-- /Edit Addition Modal -->

	<!-- Delete Addition Modal -->
	<div class="modal center-modal fade" id="delete_addition<?php echo $add_ar['id']; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-header">
						<h3>Delete Addition</h3>
						<p>Are you sure want to delete?</p>
					</div>
					<div class="modal-btn delete-action">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
							</div>
							<div class="col-6">
								<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Delete Addition Modal -->
<?php } ?>

<!-- Add Overtime Modal -->
<div id="add_overtime" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Overtime</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?php echo base_url(); ?>employees/add_overtime" id="add_overtimes" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
					<div class="form-group">
						<label>Description <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="ot_description" required value="">
						<input class="form-control" type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
					</div>
					<div class="form-group">
						<label>Date<span class="text-danger">*</span></label>
						<input class="form-control datetimepicker" type="text" name="ot_date" required id="ot_date">
						<?php $teamlead_details = $this->db->get_where('dgt_users', array('id' => $this->session->userdata('user_id')))->row_array(); ?>
						<input type="hidden" name="teamlead_id" id="teamlead_id" value="<?php echo $teamlead_details['teamlead_id']; ?>">
					</div>
					<div class="form-group">
						<label>Hours<span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="ot_hours" id="ot_hours" required="" placeholder="HH:MM">
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add Overtime Modal -->

<!-- Edit Overtime Modal -->
<div id="edit_overtime" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Overtime</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label>Name <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="ot_description" value="" placeholder="OT Description">
					</div>
					<div class="form-group">
						<label>Date<span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="ot_date" id="ot_date">
					</div>
					<div class="form-group">
						<label>Hours<span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="ot_hours" id="ot_hours" placeholder="HH:MM">
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Edit Overtime Modal -->

<!-- Delete Overtime Modal -->
<div class="modal center-modal fade" id="delete_overtime" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3>Delete Overtime</h3>
					<p>Are you sure want to delete?</p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Overtime Modal -->

<!-- Add Deduction Modal -->
<div id="add_deduction" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Deduction</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>employees/add_deduction/">
					<div class="form-group">
						<label>Name <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="model_name" id="model_name" value="">
						<input class="form-control" type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
					</div>
					<div class="form-group">
						<label>Unit Amount</label>
						<div class="input-group">
							<span class="input-group-addon">
								$
							</span>
							<input type="text" class="form-control" name="unit_amount" value="">
							<span class="input-group-addon">
								.00
							</span>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add Deduction Modal -->

<!-- Add File Attachment Modal -->
<div id="add_fileattachment" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Files Upload</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>employees/add_attachment/<?php echo $employee_details['user_id']; ?>" enctype="multipart/form-data">
					<div class="form-group">
						<label>Upload Files <span class="text-danger">*</span></label>
						<input class="form-control" type="file" name="attach_file[]" id="attach_file" multiple>
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea name="fileDesc" id="fileDesc" class="form-control"></textarea>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add Deduction Modal -->

<!-- Edit file attachment -->

<div id="edit_file_attachment" class="modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Files upload</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>employees/edit_files_upload/<?php echo $employee_details['user_id']; ?>" enctype="multipart/form-data">
					<input type="hidden" name="file_uploads_id" id="file_uploads_id">
					<div class="form-group">
						<label>Description</label>
						<textarea name="fileDesc_edit" id="fileDesc_edit" class="form-control"></textarea>
					</div>

					<div class="form-group">
						<label>Upload Document</label>
						<span class="form_image_upload"><i class="fa fa-document"></i></span>

						<input class="form-control" type="file" name="attachments_upload">
						<input type="hidden" name="exist_file_upload" id="exist_file_upload">

					</div>

					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Edit file attachment -->

<?php foreach ($deduction_ar as $dec_ar) { ?>

	<!-- Edit Deduction Modal -->
	<div id="edit_deduction<?php echo $dec_ar['id']; ?>" class="modal center-modal fade" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Deduction</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" id="edit_addition_form" action="<?php echo base_url(); ?>employees/edit_pfdeduction/<?php echo $dec_ar['id']; ?>">
						<div class="form-group">
							<label>Name <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="model_name" value="<?php echo $dec_ar['model_name']; ?>">
							<input class="form-control" type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
						</div>
						<div class="form-group">
							<label>Unit Amount</label>
							<div class="input-group">
								<span class="input-group-addon">
									$
								</span>
								<input type="text" class="form-control" name="unit_amount" value="<?php echo $dec_ar['unit_amount']; ?>">
								<span class="input-group-addon">
									.00
								</span>
							</div>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Edit Addition Modal -->
<?php } ?>

<!-- Delete Deduction Modal -->
<div class="modal center-modal fade" id="delete_deduction" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3>Delete Deduction</h3>
					<p>Are you sure want to delete?</p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Deduction Modal -->
<script type="text/javascript">
	var office_address = "<?= config_item('company_address') ?>";
	var office_city = "<?= config_item('company_city') ?>";
	var office_state = "<?= config_item('company_state') ?>";
	var office_zip_code = "<?= config_item('company_zip_code') ?>";
	$(document).ready(function() {
		$("#dob").datepicker({});
		$("#exit_date").datepicker({});
	});

	function add_document() {
		var ipdoc = $('#ipdoc').val();
		var deldoc = "'" + ipdoc + "'";
		let document_div = '<div id="cur' + ipdoc + '"><div class="form-group namediv"><label>Name <span class="text-danger">*</span></label><input class="form-control" type="text" name = "document_name[]"  required></div><div class="form-group"><label>Upload Document <span class="text-danger">*</span></label><input class="form-control" type="file" name = "document_file[]" required ></div><div class="form-group del_div"><a class="del-doc"  href="javascript:void(0)" onclick="delete_document(' + deldoc + ')" title="Delete Document">Delete Document</a></div></div>';
		$('#add_another').append(document_div);
		var reqdoc = ipdoc + 1;
		$('#ipdoc').val(reqdoc);
	}

	function delete_document(a) {
		$('#cur' + a).html('');

	}

	function edit_document(a) {
		$('#document_id').val(a);
		$.ajax({
			url: "<?php echo base_url(); ?>employees/get_document/" + a,
			success: function(result) {
				var obj = JSON.parse(result);
				$('#edit_document_name').val(obj.document_name);
				$('#exist_file ').val(obj.document);
				$('.form_image').text(obj.document);
			}
		});
	}

	function edit_file_attachment(a) {
		$('#file_uploads_id').val(a);
		$.ajax({
			url: "<?php echo base_url(); ?>employees/get_file_attachment/" + a,
			success: function(result) {
				var obj = JSON.parse(result);
				$('#fileDesc_edit').val(obj.description);
				$('#exist_file_upload').val(obj.attach_file);
				$('.form_image_upload').text(obj.attach_file);
			}
		});
	}
</script>
<style>
	.add-doc,
	.del-doc {
		float: left;
		text-decoration: underline;
		font-weight: 600
	}

	.doc_icon {
		font-size: 25px;
	}

	.add-doc:active,
	.add-doc:focus {
		color: #007bff;
		text-decoration: underline !important;
	}

	.add-doc:hover,
	.del-doc:hover {
		color: red !important
	}

	.del-doc {
		margin-right: 10%;
	}

	.submit-section {
		/*	float:left;
width:100%;
padding:20px*/
	}

	.namediv {
		clear: both;
	}
</style>