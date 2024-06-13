<!--Canditates Edit Profile-->
<div class="content">
	<div class="page-header">
<div class="row">
						<div class="col-8">
							<h4 class="page-title m-b-0"><?php echo lang('recruiting_process');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/candidates"><?php echo lang('candidate_list');?></a></li>
						        <li class="active breadcrumb-item"><?php echo lang('add_candidates');?></li>
			</ul>
		</div>
						 <div class="col-sm-4  text-right m-b-20">     
				              <a class="btn back-btn" href="<?=base_url()?>jobs/candidates"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
				          </div>
					</div>

</div>
						<div class="card-box tab-box">
		<div class="row user-tabs">
			<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
				<ul class="nav nav-tabs nav-tabs-bottom">
					<li class="nav-item"><a class="active nav-link" href="#emp_profile" data-toggle="tab"><?php echo lang('profile');?></a></li>	
					<!--<li><a href="#my_files" data-toggle="tab"><?php echo lang('my_files');?></a></li>
					<li><a href="#account_settings" data-toggle="tab"><?php echo lang('account_settings');?></a></li>-->
					
				</ul> 
			</div>
		</div>
	</div>
	
					<div class="tab-content">
					 
						<!-- Profile Info Tab --> 

						<div id="emp_profile" class="pro-overview tab-pane active show ">

							<form name="contact_info" action="<?php echo site_url('jobs/add_candidates');?>" id="contact_info_add" method="post" enctype="multipart/form-data">
							<div class="row">
								<input type="hidden" name="page_check" id="page_check" value="<?php echo $this->uri->segment(3);?>">
								
									<input type="hidden" name="save_contact" value="1">
									<?php if($this->uri->segment(3)){?>
										<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $this->uri->segment(3);?>">	
									<?php  }?>
								<div class="col-md-6"> 
									<div class="card-box profile-box">
										<h3 class="card-title"><?php echo lang('contact_informations');?>
											
										</h3>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('first_name');?><span class="text-danger">*</span></label>
											<input type="text" class="form-control" placeholder="N/A"  name="first_name" value="<?php if(isset($candidate_detail['first_name'])){  echo $candidate_detail['first_name']; }?>" required>
										</div>
										
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('last_name');?> <span class="text-danger">*</span></label>
											<input type="text" class="form-control" placeholder="N/A" name="last_name" value="<?php if(isset($candidate_detail['last_name'])){  echo $candidate_detail['last_name']; }?>" >
										</div>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('address');?> <span class="text-danger">*</span></label>
											<input type="text" class="form-control" placeholder="N/A" name="address" value="<?php if(isset($candidate_detail['address'])){  echo $candidate_detail['address']; }?>">
										</div>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('country'); ?> <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="country" value="<?php if(isset($candidate_detail['country'])){  echo $candidate_detail['country']; }?>" required>
											
									   </div>
									   <div class="form-group">
											<label class="col-form-label"><?php echo lang('state');?> <span class="text-danger">*</span></label>
											<input type="text" class="form-control" name="state" value="<?php if(isset($candidate_detail['state'])){  echo $candidate_detail['state']; }?>" required>
									   </div>
									   <div class="form-group">
											<label class="col-form-label"><?php echo lang('city');?></label>
											<input type="text" class="form-control" placeholder="N/A" name="city" value="<?php if(isset($candidate_detail['city'])){  echo $candidate_detail['city']; }?>" required>
										</div>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('zip_postal_code');?></label>
											<input type="text" class="form-control" placeholder="N/A" name="pincode" value="<?php if(isset($candidate_detail['pincode'])){  echo $candidate_detail['pincode']; }?>" required>
										</div>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('phone');?></label>
											<input type="text" class="form-control" placeholder="N/A"  name="phone_number" value="<?php if(isset($candidate_detail['phone_number'])){  echo $candidate_detail['phone_number']; }?>" required >
										</div>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('web_address');?>?</label>
											<input type="text" class="form-control" placeholder="N/A" name="web_address" value="<?php  if(isset($candidate_detail['web_address'])){  echo $candidate_detail['web_address']; }?>" >
										</div>
										<div class="form-group">
											<label class="col-form-label"><?php echo lang('email');?> <span class="text-danger">*</span></label>
											<input type="email" class="form-control" placeholder="N/A" name="email" required value="<?php  if(isset($candidate_detail['email'])){  echo $candidate_detail['email']; }?>" >
										</div>

										

										<div class="form-group">
											<label class="col-form-label"><?php echo lang('job_categories');?> <span class="text-danger">*</span></label>
											<select class="select required" name="job_category[0]" id="job_category" required onchange="position_types(this.value);">
											<option value=""><?php echo lang('select_category');?></option>
											<?php 
										
											$dept_array =array();
											if(isset($candidate_detail['job_category_id'])){

													$dept_array = json_decode($candidate_detail['job_category_id'],true);
												
											}

											foreach($departments as $key => $depart)
											{
											?><option value="<?php echo $depart->deptid;?>" <?php if(in_array($depart->deptid,$dept_array)){ echo "selected";}?>><?php echo $depart->deptname; ?></option><?php 
											}
											?>
											</select>
										</div>


										 <div class="form-group">
                       <label class="col-form-label"><?php echo lang('position_type')?><span class="text-danger">*</span></label>
                        <select class="form-control" name="position_type[0]"  id="position_type" required>
                        <option value=""><?php echo lang('position')?></option>
                        <?php if(isset($designations)){
                        	$position_array =array();
											if(isset($candidate_detail['position_type_id'])){

													$position_array = json_decode($candidate_detail['position_type_id'],true);
												
											}
                        	foreach($designations as $des){
                        		?><option value="<?php echo $des['id'];?>" <?php if(in_array($des['id'],$position_array)){ echo "selected";}?>><?php echo $des['designation'];?></option><?php 
                        	}
                        }?>
                        
                        </select>
                    </div>
										<div class="form-group">
													<label class="col-form-label"><?php echo lang('resume_or_cv');?>   </label>
													<div class="input-group input-file" name="resume">
											    		<input type="file" name="resume_file" class="form-control" placeholder='Choose a file...'  <?php if(!$this->uri->segment(3)){ echo "required";} ?>/>			
													</div>
													<?php if(isset($candidate_detail['file_name'])){?>
										<a href="<?php echo base_url().'/images/resume/'.$candidate_detail['file_name']; ?>" download><?php echo lang('download_file'); ?></a>
									<?php  } ?>

										</div>
					


										<div class="form-group">
											<label class="col-form-label"><?php echo lang('password');?> <span class="text-danger">*</span></label>
											<input type="text" class="form-control" placeholder="N/A" name="password" <?php if(!$this->uri->segment(3)){ echo "required";} ?> value="" >
										</div>

										
									</div>
								</div>


								<!-- education add -->	

								<div class="col-md-6">
									
									<div class="card-box profile-box">
										<h3 class="card-title"><?php echo lang('education_history');?>
										

										</h3>
										<h5 class="section-title"><a href="#addEducation" data-toggle="collapse"><?php echo lang('add_education')?></a></h5>
										 <div id="addEducation" class="collapse <?php if(isset($candidate_detail['school_name']) && $candidate_detail['school_name']!="") { echo 'in';} ?>">
											 <div class="form-group">
												<label class="col-form-label"><?php echo lang('school_name')?> </label>
												<input type="text" class="form-control" placeholder="N/A"  name="school_name" value="<?php  if(isset($candidate_detail['school_name'])){  echo $candidate_detail['school_name']; }?>"  >
										   </div>
										   <div class="form-group">
												<label class="col-form-label"><?php echo lang('graduation_year');?> </label>
												<input type="text" class="form-control" placeholder="N/A" name="passed_out_year"   value="<?php  if(isset($candidate_detail['passed_out_year'])){  echo $candidate_detail['passed_out_year']; }?>">
											</div>
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('major_area_of_study')?> </label>
												<input type="text" class="form-control" placeholder="N/A" name="major_subject"  value="<?php  if(isset($candidate_detail['major_subject'])){  echo $candidate_detail['major_subject']; }?>">
											</div>
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('degree');?> </label>
												<input type="text" class="form-control" placeholder="N/A" name="degree" value="<?php  if(isset($candidate_detail['degree'])){  echo $candidate_detail['degree']; }?>" >
										   </div>
										   <div class="form-group">
												<label class="col-form-label"><?php echo lang('gpa');?> </label>
												<input type="text" class="form-control" placeholder="N/A"  value="<?php  if(isset($candidate_detail['gpa'])){  echo $candidate_detail['gpa']; }?>" name="gpa"  >
											</div>
											
										 </div>
										
									</div>
								</div>


							<!--experience add -->	

						<div class="col-md-6">
								<div class="form-scroll">
						<div class="card-box AllExperience_c">
						    <?php $i =1; 
												
								$experience_details = json_decode($candidate_detail['experience_details']);
								 // echo "<pre>"; print_r($pers); exit;
								 if(!empty($experience_details)){
								 foreach($experience_details as  $key=>$exp){ ?>
						    <div class="MultipleExperience">
							<h3 class="card-title"><?php echo lang('work_experience');?> </h3> <?php if($i != 1){ ?> <a href="#" class="remove_exp_div"><i class="fa fa-trash-o"></i></a><?php } ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="<?php echo $exp->company_name; ?>" name="company_name[]" required>
										<label class="control-label"><?php echo lang('company');?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="<?php echo $exp->location; ?>" name="location[]" >
										<label class="control-label"><?php echo lang('location');?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="<?php echo $exp->job_position; ?>" name="job_position[]">
										<label class="control-label"><?php echo lang('job_position'); ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="<?php echo $exp->period_from; ?>" name="period_from[]">
										</div>
										<label class="control-label"><?php echo lang('period_from'); ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="<?php echo $exp->period_to; ?>" name="period_to[]">
										</div>
										<label class="control-label"><?php echo lang('period_to');?> </label>
									</div>
								</div>
								
							</div>
							</div>
							<?php $i++; } }else{ ?>
							    <div class="MultipleExperience">
							<h3 class="card-title"><?php echo lang('work_experience');?></h3>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="" name="company_name[]">
										<label class="control-label"><?php echo lang('company_name');?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="" name="location[]">
										<label class="control-label"><?php echo lang('job_location');?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="" name="job_position[]">
										<label class="control-label"><?php echo lang('job_position')?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="" name="period_from[]">
										</div>
										<label class="control-label"><?php echo lang('period_from');?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="" name="period_to[]">
										</div>
										<label class="control-label"><?php echo lang('period_to');?></label>
									</div>
								</div>
								
							</div>
							</div>
							<?php } ?>
							<div class="add-more">
								<a href="#" id="Add_experience"><i class="fa fa-plus-circle"></i> <?php echo lang('add_more')?></a>
						</div>
						
						</div>

					</div>
				</div>

				<!--add skills -->
				<div class="col-md-6">
										
									<div class="card-box profile-box">
										<h3 class="card-title"><?php echo lang('skills');?>
										

										</h3>
										<h5 class="section-title"><a href="#addEducation2" data-toggle="collapse"><?php echo lang('add_skills'); ?></a></h5>
										 <div id="addEducation2" class="collapse <?php if(isset($candidate_detail['skills']) &&  $candidate_detail['skills']!=""){ echo 'in';}?>">
										 
										 <div class="form-group">
											<label class="col-form-label"><?php echo lang('resposibilities');?> </label>
											<textarea class="form-control" name="skills" row="5"><?php if(isset($candidate_detail['skills'])){ echo trim($candidate_detail['skills']);} ?></textarea>
										</div>
										
										 </div>
										
									</div>
								
								</div>







							</div>

							<div class="submit-section">
											<button name="contact_save" id="contact_add" class="btn btn-primary submit-btn" type="submit" value="save"><?php echo lang('save');?></button>
											<a href="<?php echo base_url('jobs/candidates');?>" class="btn btn-danger submit-btn" ><?php echo lang('cancel');?></a>
										</div>

								</form>
						
							
						</div>
						<!-- /Profile Info Tab -->

						<!-- My Files Tab -->
				<?php /* ?>	<div class="tab-pane fade" id="my_files">
							<div class="card">
								<div class="card-body">
									<form action="<?php echo site_url('candidates/candidate_files');?>" method="post" enctype="multipart/form-data" id="add_files">
										<div class="row">
											<div class="col-sm-4">
												<h3 class="card-title"><?php echo lang('my_files')?></h3>
												<div class="form-group">
													<label class="col-form-label"><?php echo lang('title');?> </label>
													<div class="input-group" name="title" required>
											    		<input type="title" class="form-control" placeholder='Enter title' name="title"  required />			
											          
													</div>
												</div>
												<div class="form-group">
													<label class="col-form-label"><?php echo lang('files');?> </label>
													<div class="input-group input-file" name="resume">
											    		<input type="file" name="userfile" class="form-control" placeholder='Choose a file...' />			
											          
													</div>
												</div>
											
												<div class="form-group">
													<button type="submit" name="save_file"  value="save" class="btn btn-primary"><?php echo lang('save');?></button>
													<button type="reset" class="btn btn-danger"><?php echo lang('reset');?></button>
												</div>
											
											</div>
											
										</div>
									   </div>
									</form>
								</div>
							</div>
							<!-- /My files Tab -->

							<!-- Accoount settings Tab -->
						<!--<div class="tab-pane fade" id="account_settings">
							<div class="card">
								<div class="card-body">
									<form action="<?php echo site_url('candidates/account_setting')?>" method="post" name="account_settings" id="account_setting" >
										<div class="row">
											<div class="col-sm-4">
												<h3 class="card-title"><?php echo lang('account_settings');?></h3>
												<div class="form-group">
													<label class="col-form-label"><?php echo lang('email'); ?></label>
													<input type="email" class="form-control" name="email" required  readonly value="<?php  if(isset($candidate_detail['email'])){ echo trim($candidate_detail['email']);}  ?>">
												</div>
												<div class="form-group">
													<label class="col-form-label"><?php echo lang('new_password');?></label>
													<input type="password" class="form-control" name="password" required >
												</div>
												<div class="form-group">
													<label class="col-form-label"><?php echo lang('confirm_password');?></label>
													<input type="password" class="form-control" name="c_password" required >
												</div>
												<div class="form-group">
													<button type="submit" name="save" value="save" id="account_save" class="btn btn-primary"><?php echo lang('save');?></button>
													
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- /Accoount settings Tab -->
						<?php */ ?>
						</div>
	

</div>
	<!--/Canditates Edit Profile-->