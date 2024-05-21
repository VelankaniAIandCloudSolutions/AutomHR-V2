<?php //echo "<pre>"; $pers = json_decode($personal_details['education_details']); print_r($pers); exit; ?>

<div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Edit Profile</h4>
						</div>
					</div>
					<form method="post" id="edit_profile_form">
						<div class="card-box">
							<h3 class="card-title">Basic Informations</h3>
							<div class="row">
								<div class="col-md-12">
									<div class="profile-img-wrap">
										<img class="inline-block mb-10" src="<?php echo base_url(); ?>assets/avatar/<?php echo $employee_details['avatar']; ?>" alt="user" style="width: 10%;" >
										<!-- <div class="fileupload btn btn-default">
											<span class="btn-text">edit</span>
											<input class="upload" type="file">
										</div> -->
									</div>
									<div class="profile-basic">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group form-focus">
													<label class="control-label">Full Name</label>
													<input type="text" class="form-control floating" name="full_name" id="full_name" value="<?php echo $employee_details['fullname']; ?>" />
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus">
													<label class="control-label">Date of Birth</label>
													<div class="cal-icon"><input class="form-control floating" id="timesheet_date_from" type="text" data-date-format="dd-mm-yyyy" name="dob" value="<?php echo $employee_details['dob']?date('d-m-Y',strtotime($employee_details['dob'])):date('d-m-Y'); ?>" size="16"></div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus select-focus">
													<label class="control-label">Gendar</label>
													<select class="select form-control floating" name="gender" id="profile_gender">
														<option value="" disabled selected>Select Gendar</option>
														<option value="male" <?php if($employee_details['gender'] =='male'){ echo 'selected'; } ?>>Male</option>
														<option value="female" <?php if($employee_details['gender'] =='female'){ echo 'selected'; } ?>>Female</option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-focus">
													<label class="control-label">Email Id</label>
													<input type="text" class="form-control floating" name="email" id="email" value="<?php echo $employee_details['email']; ?>" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-box">
							<h3 class="card-title">Contact Informations</h3>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group form-focus">
										<label class="control-label">Address</label>
										<input type="text" class="form-control floating" name="address" id="address" value="<?php echo $employee_details['address']?$employee_details['address']:''; ?>" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<label class="control-label">State</label> <!-- state -->
										<input type="text" class="form-control floating" name="state" id="state" value="<?php  echo $employee_details['state']?$employee_details['state']:''; ?>" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<label class="control-label">Country</label>
										<input type="text" class="form-control floating" name="country" id="country" value="<?php echo $employee_details['country']?$employee_details['country']:''; ?>" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<label class="control-label">Pin Code</label> <!-- pincode -->
										<input type="text" class="form-control floating" name="pincode" id="pincode" value="<?php echo $employee_details['pincode']?$employee_details['pincode']:''; ?>" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<label class="control-label">Phone Number</label>
										<input type="text" class="form-control floating"  name="phone" id="phone" value="<?php echo $employee_details['phone']?$employee_details['phone']:''; ?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class="card-box AllInstitute">
							<?php $i =1; 
							if(!empty($personal_details)){
							 $pers = json_decode($personal_details['education_details']);
							 foreach($pers as $p){ ?>
							<div class="MultipleInstitutions">								
							<h3 class="card-title">Education Informations</h3><?if($i != 1) { ?><a href="#" class="remove_div">Remove</a><?php } ?>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Institution</label>
											<input type="text" class="form-control floating" name="institute[]" id="institute" value="<?php echo $p->institute; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Subject</label>
											<input type="text" class="form-control floating" name="subject[]" id="subject" value="<?php echo $p->subject; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Year of Complete</label>
											<input type="text" class="form-control floating" name="yoc[]" id="yoc" value="<?php echo $p->yoc; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Degree</label>
											<input type="text" class="form-control floating" name="degree[]" id="degree" value="<?php echo $p->degree; ?>" />
										</div>
									</div>
								</div>
							</div>
						<?php $i++; } }else{ ?>
							<div class="MultipleInstitutions">								
							<h3 class="card-title">Education Informations</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Institution</label>
											<input type="text" class="form-control floating" name="institute[]" id="institute" value="" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Subject</label>
											<input type="text" class="form-control floating" name="subject[]" id="subject" value="" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Year of Complete</label>
											<input type="text" class="form-control floating" name="yoc[]" id="yoc" value="" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Degree</label>
											<input type="text" class="form-control floating" name="degree[]" id="degree" value="" />
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
							<div class="add-more">
								<a href="#" class="btn btn-primary" id="Add_more_institution"><i class="fa fa-plus"></i> Add More Institute</a>
							</div>
						</div>
						<div class="card-box AllExperience">
							<?php $j =1; 
							if(!empty($personal_details)){
							$experience_details = json_decode($personal_details['personal_details']); 
							 foreach($experience_details as $experience_detail){ ?>
							<div class="MultipleExperience">
							<h3 class="card-title">Experience Informations</h3><?if($j != 1) { ?><a href="#" class="remove_exp_div">Remove</a><?php } ?>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Company Name</label>
											<input type="text" class="form-control floating" name="past_company[]" id="past_company" value="<?php echo $experience_detail->company_name; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Location</label>
											<input type="text" class="form-control floating" name="past_company_loc[]" id="past_company_loc" value="<?php echo $experience_detail->company_location; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Job Position</label>
											<input type="text" class="form-control floating" name="job_position[]" id="job_position" value="<?php echo $experience_detail->job_position; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Period From</label>
											<input type="text" class="form-control floating" name="period_from[]" id="period_from" value="<?php echo $experience_detail->period_from; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Period To</label>
											<input type="text" class="form-control floating" name="period_to[]" id="period_to" value="<?php echo $experience_detail->period_to; ?>" />
										</div>
									</div>
								</div>
							</div>
							<?php $j++; } }else{ ?>
								<div class="MultipleExperience">
							<h3 class="card-title">Experience Informations</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Company Name</label>
											<input type="text" class="form-control floating" name="past_company[]" id="past_company" value="" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Location</label>
											<input type="text" class="form-control floating" name="past_company_loc[]" id="past_company_loc" value="" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Job Position</label>
											<input type="text" class="form-control floating" name="job_position[]" id="job_position" value="" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Period From</label>
											<input type="text" class="form-control floating" name="period_from[]" id="period_from" value="<?php echo $experience_detail->period_from; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-focus">
											<label class="control-label">Period To</label>
											<input type="text" class="form-control floating" name="period_to[]" id="period_to" value="" />
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="add-more">
								<a href="#" class="btn btn-primary" id="Add_experience"><i class="fa fa-plus"></i> Add More Experience</a>
							</div>
						</div>
						<div class="text-center m-t-20">
							<button class="btn btn-primary btn-lg" type="submit">Save &amp; update</button>
						</div>
					</form>
				</div>