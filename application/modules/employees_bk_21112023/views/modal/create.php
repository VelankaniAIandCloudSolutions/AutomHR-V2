<?php $company_ref = config_item('company_id_prefix').$this->applib->generate_string();
while($this->db->where('company_ref', $company_ref)->get('companies')->num_rows() == 1) {
$company_ref = config_item('company_id_prefix').$this->applib->generate_string();
} ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">
				Edit Profile
			</h4>
        </div>
		<?php $attributes = array('id' => 'createClientForm'); echo form_open(base_url().'companies/create', $attributes); ?>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-tabs-solid" id="tabsClient" role="tablist">
					<li><a class="active" data-toggle="tab" id="tab_client_general" href="#tab_basic_info"> Basic</a></li>
					<li><a data-toggle="tab" id="tab_client_contact" href="#tab_personal_info">Personal</a></li>
					<li><a data-toggle="tab" id="tab_client_web" href="#tab_emg_contact">Emergency Contact</a></li>
					<li><a data-toggle="tab" id="tab_client_bank" href="#tab_bank_info">Bank</a></li>
					<li><a data-toggle="tab" id="tab_client_hosting" href="#tab_family_info">Family </a></li>
					<li><a data-toggle="tab" href="#tab_education_info">Education</a></li>
					<li><a data-toggle="tab" href="#tab_exp_info">Experience</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="tab_basic_info">
								<form>
									<div class="row">
										<div class="col-md-12">
											<div class="profile-img-wrap edit-img">
												<img class="inline-block" src="assets/img/user.jpg" alt="user">
												<div class="fileupload btn">
													<span class="btn-text">edit</span>
													<input class="upload" type="file">
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>First Name</label>
														<input type="text" class="form-control" value="John">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Last Name</label>
														<input type="text" class="form-control" value="Doe">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Birth Date</label>
														<div class="cal-icon">
															<input class="form-control datetimepicker" type="text" value="05/06/1985">
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Gender</label>
														<select class="select form-control">
															<option value="male selected">Male</option>
															<option value="female">Female</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Address</label>
												<input type="text" class="form-control" value="4487 Snowbird Lane">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>State</label>
												<input type="text" class="form-control" value="New York">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Country</label>
												<input type="text" class="form-control" value="United States">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Pin Code</label>
												<input type="text" class="form-control" value="10523">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Phone Number</label>
												<input type="text" class="form-control" value="631-889-3206">
											</div>
										</div>
									</div>
									<div class="submit-section text-right">
										<button class="btn btn-primary submit-btn">Save & Continue</button>
									</div>
								</form>
					</div>
							<div class="tab-pane fade in" id="tab_personal_info">
								<form>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Passport No</label>
												<input type="text" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Passport Expiry Date</label>
												<div class="cal-icon">
													<input class="form-control datetimepicker" type="text">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Tel</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Nationality <span class="text-danger">*</span></label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Religion</label>
												<div class="cal-icon">
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Marital status <span class="text-danger">*</span></label>
												<select class="select form-control">
													<option>-</option>
													<option>Single</option>
													<option>Married</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Employment of spouse</label>
												<input class="form-control" type="text">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>No. of children </label>
												<input class="form-control" type="text">
											</div>
										</div>
									</div>
									<div class="submit-section wizard-btn">
										<button class="btn btn-white submit-btn">Previous</button>
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
						
					</div>
					<div class="tab-pane fade in" id="tab_emg_contact">
								<form>
									<div class="card-box">
										<h3 class="card-title">Primary Contact</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input type="text" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone 2</label>
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
									</div>
									<div class="card-box">
										<h3 class="card-title">Primary Contact</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input type="text" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone 2</label>
													<input class="form-control" type="text">
												</div>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-white submit-btn">Previous</button>
										<button class="btn btn-primary submit-btn">Save & Submit</button>
									</div>
								</form>
						
					</div>
					<div class="tab-pane fade in" id="tab_bank_info">
						<div class="form-group">
							<label><?=lang('bank')?> </label>
							<input type="text" value="" name="bank" class="form-control">
						</div>
						<div class="form-group">
							<label>SWIFT/BIC</label>
							<input type="text" value="" name="bic" class="form-control">
						</div>
						<div class="form-group">
							<label>Sort Code</label>
							<input type="text" value="" name="sortcode" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account_holder')?> </label>
							<input type="text" value="" name="account_holder" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account')?> </label>
							<input type="text" value="" name="account" class="form-control">
						</div>
						<div class="form-group">
							<label>IBAN</label>
							<input type="text" value="" name="iban" class="form-control">
						</div>
						<a class="btn btn-primary submit-btn" onclick="$('#tab_client_web').trigger('click')">Previous</a>
        				<a class="btn btn-primary submit-btn" style="float:right;" onclick="$('#tab_client_hosting').trigger('click')">Next</a>
						
					</div>
					<div class="tab-pane fade in" id="tab_family_info">
								<form>
									<div class="form-scroll">
										<div class="card-box">
											<h3 class="card-title">Family Member <a href="#" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Name <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Relationship <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Date of birth <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Phone <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
											</div>
										</div>
										<div class="card-box">
											<h3 class="card-title">Education Informations <a href="#" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Name <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Relationship <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Date of birth <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Phone <span class="text-danger">*</span></label>
														<input class="form-control" type="text">
													</div>
												</div>
											</div>
											<div class="add-more">
												<a href="#"><i class="fa fa-plus-circle"></i> Add More</a>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
						
					</div>

					<!-- START CUSTOM FIELDS -->
					<div class="tab-pane fade in" id="tab_education_info">
								<form>
									<div class="form-scroll">
										<div class="card-box">
											<h3 class="card-title">Education Informations <a href="#" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="Oxford University" class="form-control floating">
														<label class="control-label">Institution</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="Computer Science" class="form-control floating">
														<label class="control-label">Subject</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" value="01/06/2002" class="form-control floating datetimepicker">
														</div>
														<label class="control-label">Starting Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" value="31/05/2006" class="form-control floating datetimepicker">
														</div>
														<label class="control-label">Complete Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="BE Computer Science" class="form-control floating">
														<label class="control-label">Degree</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="Grade A" class="form-control floating">
														<label class="control-label">Grade</label>
													</div>
												</div>
											</div>
										</div>
										<div class="card-box">
											<h3 class="card-title">Education Informations <a href="#" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="Oxford University" class="form-control floating">
														<label class="control-label">Institution</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="Computer Science" class="form-control floating">
														<label class="control-label">Subject</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" value="01/06/2002" class="form-control floating datetimepicker">
														</div>
														<label class="control-label">Starting Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" value="31/05/2006" class="form-control floating datetimepicker">
														</div>
														<label class="control-label">Complete Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="BE Computer Science" class="form-control floating">
														<label class="control-label">Degree</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="Grade A" class="form-control floating">
														<label class="control-label">Grade</label>
													</div>
												</div>
											</div>
											<div class="add-more">
												<a href="#"><i class="fa fa-plus-circle"></i> Add More</a>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
					</div>
					<!-- End custom fields -->
					
					<!-- START CUSTOM FIELDS -->
					<div class="tab-pane fade in" id="tab_exp_info">
								<form>
									<div class="form-scroll">
										<div class="card-box">
											<h3 class="card-title">Experience Informations <a href="#" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group form-focus">
														<input type="text" class="form-control floating" value="Digital Devlopment Inc">
														<label class="control-label">Company Name</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<input type="text" class="form-control floating" value="United States">
														<label class="control-label">Location</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<input type="text" class="form-control floating" value="Web Developer">
														<label class="control-label">Job Position</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<div class="cal-icon">
															<input type="text" class="form-control floating datetimepicker" value="01/07/2007">
														</div>
														<label class="control-label">Period From</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<div class="cal-icon">
															<input type="text" class="form-control floating datetimepicker" value="08/06/2018">
														</div>
														<label class="control-label">Period To</label>
													</div>
												</div>
											</div>
										</div>
										<div class="card-box">
											<h3 class="card-title">Experience Informations <a href="#" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group form-focus">
														<input type="text" class="form-control floating" value="Digital Devlopment Inc">
														<label class="control-label">Company Name</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<input type="text" class="form-control floating" value="United States">
														<label class="control-label">Location</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<input type="text" class="form-control floating" value="Web Developer">
														<label class="control-label">Job Position</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<div class="cal-icon">
															<input type="text" class="form-control floating datetimepicker" value="01/07/2007">
														</div>
														<label class="control-label">Period From</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus">
														<div class="cal-icon">
															<input type="text" class="form-control floating datetimepicker" value="08/06/2018">
														</div>
														<label class="control-label">Period To</label>
													</div>
												</div>
											</div>
											<div class="add-more">
												<a href="#"><i class="fa fa-plus-circle"></i> Add More</a>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
					</div>
					<!-- End custom fields -->
					
				</div>
				
			</div>
			<!-- <div class="modal-footer">
				<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="createClient"><?=lang('save_changes')?></button>
			</div> -->
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.nav-tabs li a').first().tab('show');
</script>