<div class="content">
<div class="content">
   <div class="page-header">
      <div class="row">
         <div class="col-md-4 col-12">
            <h4 class="page-title m-b-0"><?php echo lang('add_job_request');?></h4>
         </div>
         <div class="col-md-8 col-12 text-right m-b-30">
            <!-- <a class="btn add-btn m-r-5" href="<?php echo base_url(); ?>jobs/job_post_approval" class="btn add-btn"><i class="fa fa-plus"></i> <?php echo lang('job_request_approval');?></a> -->
            <a class="btn add-btn m-r-5" href="<?php echo base_url(); ?>jobs/pending_for_job_post_list" class="btn btn-danger"><i class="fa fa-undo"></i> <?php echo lang('back');?></a>
         </div>
      </div>
   </div>
   <div class="row">
   </div>
   <div class="card">
      <div class="card-body">
         <form name="add_approval_jobs" id="add_approval_jobs" action="<?php echo site_url('jobs/job_post_approval');?>" method="post" enctype="multipart/form-data">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('job_title')?></label>
                     <input class="form-control" type="text" name="job_title" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('department'); ?></label>
                     <select required class="select2-option form-control required" name="department"  onchange="get_position(this.value)">
                        <option value=""><?php echo lang('select_department');?></option>
                        <?php 
                           foreach($departments as $key => $depart)
                           {
                           	?>
                        <option value="<?php echo $depart->deptname;?>"><?php echo $depart->deptname; ?></option>
                        <?php 
                           }
                           ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Proposed_Designation')?></label>
                     <input class="form-control" type="text" name="Proposed_Designation" required >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Number_of_years_of_experience')?></label>
                     <input class="form-control" type="text" min="0"  name="Number_of_years_of_experience" required >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('no_of_vacancy');?></label>
                     <input class="form-control" type="text" name="no_of_vacancy" min="1" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('start_date');?></label>
                     <input class="form-control" type="date" name="start_date" required>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Hiring_Manager'); ?></label>
                     <select required class="select2-option form-control required" name="Hiring_Manager">
                        <option value=""><?php echo lang('Hiring_Manager');?></option>
                        <?php 
                           foreach($hiring_manager as $key => $val)
                           {
                           	?>
                        <option value="<?php echo $val->fullname .'('.$val->emp_code.')'; ?>"><?php echo $val->fullname .'('.$val->emp_code.')'; ?></option>
                        <?php 
                           }
                           ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Assigned_Recruiter'); ?></label>
                     <select required class="select2-option form-control required" name="Assigned_Recruiter">
                        <option value=""><?php echo lang('Assigned_Recruiter');?></option>
                        <?php 
                           foreach($hr_list as $key => $val)
                           {
                           	?>
                        <option value="<?php echo $val->fullname .'('.$val->emp_code.')'; ?>"><?php echo $val->fullname .'('.$val->emp_code.')'; ?></option>
                        <?php 
                           }
                           ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Qualification_required')?></label>
                     <input class="form-control" type="text" name="Qualification_required" required >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Nature_of_work_experience')?></label>
                     <input class="form-control" type="text" name="Nature_of_work_experience" required >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Any_targeted_industry_segment')?></label>
                     <input class="form-control" type="text" name="Any_targeted_industry_segment" required >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Proposed_CTC')?></label>
                     <input class="form-control" type="number" min="0" name="Proposed_CTC" required >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Deadline_for_Hiring')?></label>
                     <input class="form-control" type="date" name="Deadline_for_Hiring" required >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('Names_of_People_who_will_conduct_the_interview')?></label>
                     <select class="select2-option form-control" style="width:100%;"  name="Names_of_People_who_will_conduct_the_interview" id="Names_of_People_who_will_conduct_the_interview" required>
                        <option value=""> Select Option </option>
                        <?php if(!empty($all_users)){
                           foreach($all_users as $all_users_val){
							$emp_account_info =array();
							$emp_account_info = User::profile_info($all_users_val->id);
							$emp_code = '';
							$emp_code = $emp_account_info->emp_code;
							?>
                        	<option value="<?=ucfirst(User::displayName($all_users_val->id)).'('.$emp_code.')';?>"><?=ucfirst(User::displayName($all_users_val->id)).'('.$emp_code.')';?></option>
                        <?php }
                           }?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('job_type');?></label>
                     <select class="select2-option form-control" style="width:100%;"  required name="job_type" >
                        <option value=""><?php echo lang('select_job_type');?></option>
                        <?php 
                           foreach ($job_types as $key => $type) {
                           	?>
                        <option value="<?php echo $type->job_type;?>"><?php echo $type->job_type;?></option>
                        <?php 	
                           }
                           ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label><?php echo lang('skills')?></label>
                     <textarea name='skills' class="form-control"  required></textarea>
                  </div>
               </div>
            </div>
            <div class="submit-section">
				
               <a href="<?php echo base_url(); ?>jobs/pending_for_job_post_list" class="btn btn-danger submit-btn m-b-5" id="btnSave" type="submit"><?php echo lang('cancel');?></a>
               <button class="btn btn-primary submit-btn m-b-5" id="job_post_approval_btn" type="submit" name="submit" value="submit"><?php echo lang('save');?></button>

            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function () {
   	$(".select2-option").select2({});
   });

</script>