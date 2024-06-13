<?php 
    $check_teamlead = $this->db->get_where('dgt_users',array('id'=>$employee_details['user_id']))->row_array();
    //echo "<pre>"; echo $personal_details['education_details']; exit; ?>
<div class="content container-fluid">
<!-- Page Title -->
<div class="row">
    <div class="col-sm-8">
        <h4 class="page-title"><?php echo lang('profile')?></h4>
    </div>
    <div class="col-sm-4 text-right m-b-20">			
        <a class="btn back-btn" href="<?php echo site_url('candidates/dashboard')?>"><i class="fa fa-chevron-left"></i> <?php echo lang('back')?></a>
    </div>
</div>
<!-- /Page Title -->
<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="#"><img class="avatar" src="<?php echo base_url(); ?>assets/avatar/default_avatar.jpg" alt=""></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 m-b-0"><?php echo $candidate_detail['first_name'].' '.$candidate_detail['last_name'];?></h3>
                                   <!--  <div class="staff-id">Canditate ID : </div>
                                    <div class="small text-muted"><?php echo lang('started');?> : </div> -->
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text"><a href=""><?php if(isset($candidate_detail['phone_number'])){  echo $candidate_detail['phone_number']; }?></a></div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text"><a href=""><?php echo $candidate_detail['email']?></a></div>
                                    </li>
                                   <!--  <li>
                                        <div class="title">Birthday:</div>
                                        <div class="text">24th July</div>
                                    </li> -->
                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text"><?php if(isset($candidate_detail['phone_number'])){  echo $candidate_detail['address']; }?>
                                        <?php if(isset($candidate_detail['address'])){  echo $candidate_detail['address'].' '.$candidate_detail['city'].' '.$candidate_detail['state'].' '.$candidate_detail['country']; }?>
                                         </div>
                                    </li>
                                    <!-- <li>
                                        <div class="title">Gender:</div>
                                        <div class="text">Male</div>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card tab-box">
    <div class="card-body">
        <div class="row user-tabs">
            <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class=" nav-item "><a class="nav-link active" href="#emp_profile" data-toggle="tab"><?php echo lang('profile');?></a></li>
                    <li class="nav-item "><a class="nav-link" href="#my_files" data-toggle="tab"><?php echo lang('my_files');?></a></li>
                    <li class="nav-item "><a class="nav-link" href="#account_settings" data-toggle="tab"><?php echo lang('account_settings');?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="tab-content pt-0">
    <!-- Profile Info Tab -->
    <div id="emp_profile" class="pro-overview tab-pane fade  active in">
        <div class="row">
            <div class="col-md-12">
                <form name="contact_info" action="<?php echo site_url('candidates/candidate_profile');?>" id="contact_info" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="save_contact" value="1">
                    <div class="card profile-box">
                        <div class="card-body">
                            <h3 class="card-title">
                                <?php echo lang('contact_informations');?>
                                <!-- <a href="#" class="edit-icon" data-toggle="modal" data-target="#personal_info_modal"><i class="fa fa-pencil"></i></a> -->
                            </h3>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('first_name');?><span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="N/A"  name="first_name" value="<?php if(isset($candidate_detail['first_name'])){  echo $candidate_detail['first_name']; }?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('last_name');?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="N/A" name="last_name" value="<?php if(isset($candidate_detail['last_name'])){  echo $candidate_detail['last_name']; }?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('address');?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="N/A" name="address" value="<?php if(isset($candidate_detail['address'])){  echo $candidate_detail['address']; }?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('country_region'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="country" value="<?php if(isset($candidate_detail['country'])){  echo $candidate_detail['country']; }?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('state_region_province');?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="state" value="<?php if(isset($candidate_detail['state'])){  echo $candidate_detail['state']; }?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('city');?></label>
                                        <input type="text" class="form-control" placeholder="N/A" name="city" value="<?php if(isset($candidate_detail['city'])){  echo $candidate_detail['city']; }?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('zip_postal_code');?></label>
                                        <input type="text" class="form-control" placeholder="N/A" name="pincode" value="<?php if(isset($candidate_detail['pincode'])){  echo $candidate_detail['pincode']; }?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('phone');?></label>
                                        <input type="text" class="form-control" placeholder="N/A"  name="phone_number" value="<?php if(isset($candidate_detail['phone_number'])){  echo $candidate_detail['phone_number']; }?>" required >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('web_address');?>?</label>
                                        <input type="text" class="form-control" placeholder="N/A" name="web_address" value="<?php  if(isset($candidate_detail['web_address'])){  echo $candidate_detail['web_address']; }?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('email');?> <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" placeholder="N/A" name="email" required value="<?php  if(isset($candidate_detail['email'])){  echo $candidate_detail['email']; }?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo lang('resume_or_cv');?>   </label>
                                        <div class="input-group input-file" name="resume">
                                            <input type="file" name="resume_file" class="form-control" placeholder='Choose a file...' />			
                                        </div>
                                        <a class="badge bg-primary text-white" href="<?php echo base_url().'/images/resume/'.$candidate_detail['file_name']; ?>" download><?php echo lang('download_file'); ?></a>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="submit-section">
                                        <button name="contact_save" id="contact_save" class="btn btn-primary submit-btn" type="submit" value="save"><?php echo lang('save');?></button>
                                        <button class="btn btn-danger submit-btn" type="submit"><?php echo lang('cancel');?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <form name="education_form" action="<?php echo site_url('candidates/candidate_profile');?>" method="post" id="education_form">
                    <input type="hidden" name="save_education" value="1">
                    <div class="card profile-box">
                        <div class="card-body">
                            <h3 class="card-title">
                                <?php echo lang('education_history');?>
                                <!-- <a href="#" class="edit-icon" data-toggle="modal" data-target="#emergency_contact_modal">
                                    <i class="fa fa-pencil"></i></a> -->
                            </h3>
                            <h5 class="section-title"><a href="#addEducation" data-toggle="collapse"><?php echo lang('add_education')?></a></h5>
                            <div id="addEducation" class="collapsed <?php if(isset($candidate_detail['school_name']) && $candidate_detail['school_name']!="") { echo 'in';} ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo lang('school_name')?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="N/A"  name="school_name" value="<?php  if(isset($candidate_detail['school_name'])){  echo $candidate_detail['school_name']; }?>" required >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo lang('graduation_year');?> </label>
                                            <input type="text" class="form-control" placeholder="N/A" name="passed_out_year" required  value="<?php  if(isset($candidate_detail['passed_out_year'])){  echo $candidate_detail['passed_out_year']; }?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo lang('major_area_of_study')?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="N/A" name="major_subject" required value="<?php  if(isset($candidate_detail['major_subject'])){  echo $candidate_detail['major_subject']; }?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo lang('degree');?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="N/A" name="degree" value="<?php  if(isset($candidate_detail['degree'])){  echo $candidate_detail['degree']; }?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo lang('gpa');?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="N/A"  value="<?php  if(isset($candidate_detail['gpa'])){  echo $candidate_detail['gpa']; }?>" name="gpa" required >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="submit-section">
                                            <button name="education_save" id="education_save" class="btn btn-primary submit-btn" type="submit" value="education_save"><?php echo lang('save');?></button>
                                            <button class="btn btn-danger submit-btn" type="submit"><?php echo lang('cancel');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <form action="<?php echo site_url('candidates/candidate_profile')?>" id="experience_form" name="experience_form" method="post">
                    <input type="hidden" name="save_experience" value="1">
                    <div class="">
                        <div class="card AllExperience_c">
                            <div class="card-body">
                                <?php $i =1; 
                                    $experience_details = json_decode($candidate_detail['experience_details']);
                                     // echo "<pre>"; print_r($pers); exit;
                                     if(!empty($experience_details)){
                                     foreach($experience_details as  $key=>$exp){ ?>
                                <div class="MultipleExperience">
                                    <h3 class="card-title"><?php echo lang('work_experience');?> </h3>
                                    <?php if($i != 1){ ?> <a href="#" class="remove_exp_div"><i class="fa fa-trash-o"></i></a><?php } ?>
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
                                    <a href="#" id="Add_experience_c"><i class="fa fa-plus-circle"></i> <?php echo lang('add_more')?></a>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn" name="save_experience" id="save_experience" value="save_experience"><?php echo lang('submit');?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <form action="<?php echo site_url('candidates/candidate_profile');?>" method="post" id="add_skill_form">
                    <input type="hidden" name="save_skill" value="1">
                    <div class="card profile-box">
                        <div class="card-body">
                            <h3 class="card-title">
                                <?php echo lang('skills');?>
                                <!-- <a href="#" class="edit-icon" data-toggle="modal" data-target="#emergency_contact_modal">
                                    <i class="fa fa-pencil"></i></a> -->
                            </h3>
                            <h5 class="section-title"><a href="#addEducation2" data-toggle="collapse"><?php echo lang('add_skills'); ?></a></h5>
                            <div id="addEducation2" class="collapsed <?php if(isset($candidate_detail['skills']) &&  $candidate_detail['skills']!=""){ echo 'in';}?>">
                                <div class="form-group">
                                    <label class="col-form-label"><?php echo lang('resposibilities');?> </label>
                                    <textarea class="form-control" rows="5" name="skills" required><?php if(isset($candidate_detail['skills'])){ echo trim($candidate_detail['skills']);} ?></textarea>
                                </div>
                                <div class="submit-section">
                                    <button name="add_skill" id="add_skill_btn" class="btn btn-primary submit-btn" type="submit" value="save"><?php echo lang('save');?></button>
                                    <button class="btn btn-danger submit-btn" type="submit"><?php echo lang('cancel');?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
            <!-- /Profile Info Tab -->
            <!-- My Files Tab -->
            <div class="tab-pane fade" id="my_files">
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
                                <!-- <div class="col-sm-4">
                                    <div class="form-group">
                                    	<label class="col-form-label">Cover Letter </label>
                                    	<div class="input-group input-file" name="letter">
                                       		<input type="file" class="form-control" placeholder='Choose a file...' />			
                                             
                                    	</div>
                                    </div>
                                    
                                    <div class="form-group">
                                    	<button type="submit" class="btn btn-primary">Save</button>
                                    	<button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                    
                                    </div>
                                    <div class="col-sm-4">
                                    <div class="form-group">
                                    <label class="col-form-label">Other Document</label>
                                    	<div class="input-group input-file" name="doc">
                                       		<input type="file" class="form-control" placeholder='Choose a file...' />
                                    	</div>
                                    </div>
                                    <div class="form-group">
                                    	<button type="submit" class="btn btn-primary">Save</button>
                                    	<button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                    </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /My files Tab -->
            <!-- Accoount settings Tab -->
            <div class="tab-pane fade" id="account_settings">
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
        </div>
    </div>
</div>
<script type="text/javascript">
    var office_address = "<?=config_item('company_address')?>";
    var office_city = "<?=config_item('company_city')?>";
    var office_state = "<?=config_item('company_state')?>";
    var office_zip_code = "<?=config_item('company_zip_code')?>";
    
    
    
</script>