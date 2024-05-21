<!-- Apply Job Modal -->
<div class="modal custom-modal fade" id="apply_job" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header p-0">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <h3 class="account-title"><?php echo lang('register');?></h3>
                
            <div class="account-footer mb-3">
                  <p class="account-subtitle"> <?php echo lang('already_have_an_account');?>.<a href="<?php echo site_url('candidates/login'); ?>"><?php echo lang('login'); ?></a></p>

              </div>
               
               
                <form name="user_register" id="user_register" method="post" action="<?php echo site_url('candidates/register');?>" enctype="multipart/form-data" >

                    <input type="hidden" value="" name="register_for" id="register_for">
                    <input type="hidden"  name="url" value="<?php echo current_url();?>">
                	<input type="hidden" name="job_id" value="<?php echo $this->uri->segment(3);?>">
                    <div class="form-group">
                        <label><?php echo lang('first_name');?></label>
                        <input class="form-control" type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('last_name');?></label>
                        <input class="form-control" type="text" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('primary_email');?></label>
                        <input class="form-control" type="email" name="email" required>
                    </div>
                    <div class="form-group">
                       <label class="col-form-label"><?php echo lang('job_categories')?><span class="text-danger">*</span></label>
                        <select class="form-control" name="job_category[]" <?php /*multiple*/?> id="job_category"  required>
                        	<option value=""><?php echo lang('select_category');?></option>
                        	<?php foreach ($job_categories as $key => $category) {
                        	?><option value="<?php echo $category->deptid?>"><?php echo $category->deptname;?></option><?php 
                        	} ?>
                         </select>
                    </div>
                    <div class="form-group">
                       <label class="col-form-label"><?php echo lang('position_type')?><span class="text-danger">*</span></label>
                        <select class="form-control" name="position_type[]" <?php /*multiple*/?> id="position_type" required>
                        <option value=""><?php echo lang('position'); ?></option>
                        
                        </select>
                    </div>
                    <div class="form-group">
                       <label class="col-form-label"><?php echo lang('country');?><span class="text-danger">*</span></label>
                        <select class="form-control" name="country[]" multiple required >
                        	<option value=""><?php echo lang('select_country');?></option>
                        	<?php 
                        	foreach($countries as $key => $country){
                        		?><option value="<?php echo $country->id;?>"><?php echo $country->value;?></option><?php 
                        	}
                        	?>
                        </select>
                    </div>
                      <div class="form-group">
                       <label class="col-form-label"><?php echo lang('resume_or_cv');?><span class="text-danger">*</span></label>
                        <div class="input-group input-file" name="resume">
                            <input type="file" class="form-control" placeholder='Choose a file...' name="resume" id="resume"  />           
                        </div>
                        <span id="file_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('password');?></label>
                        <input class="form-control" type="password" name="password" required>
                    </div>
                     <div class="form-group mb-0">
                        <label><?php echo lang('confirm_password');?></label>
                        <input class="form-control" type="password" name="c_password" required>
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="submit" id="register_submit"  value="submit"><?php echo lang('submit');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Apply Job Modal -->
