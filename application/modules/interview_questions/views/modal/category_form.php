<!-- Add lead Modal -->
            <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo $form_type;?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" id="category_form_id" action="<?php echo site_url('interview_questions/save_category');?>">
                                    <input type="hidden"  name="category_id" value="<?php if(isset($category['id'])){ echo $category['id']; }?>">
                                	 <div class="form-group ">
                                        <label><?php echo lang('department');?> <span class="text-danger">*</span></label>
									<select class="select  form-control" name="department" id="department" onchange="selectjobs(this.value)">

									<option value=""><?php echo lang('select_department');?></option>
									<?php foreach($departments as $dept){
										?><option value="<?php echo $dept->deptid;?>" <?php if(isset($category['dept_id']) && ($category['dept_id'] ==$dept->deptid)){ echo 'selected';}?>><?php echo $dept->deptname;?></option><?php
									}?>
									
									</select>
                                    </div>
                                     <div class="form-group">
                                        <label><?php echo lang('job_name');?> <span class="text-danger">*</span></label>
									<select class="select  form-control" name="job_id" id="job_id" required>
									<option value=""><?php echo lang('select_job');?></option>
                                    <?php if(isset($jobs_list)){
                                        foreach($jobs_list as $jobs){
                                            ?><option value="<?php echo $jobs->id;?>" <?php if($category['job_id'] == $jobs->id){ echo "selected";}?>><?php echo $jobs->job_title;;?></option><?php 
                                        }
                                    }?>
									
									</select>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('category_name');?> <span class="text-danger">*</span></label>
                                         <input class="form-control" type="text" name="category_name" value="<?php echo $category['category_name'];?>" required>
                                    </div>
                                   
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" value="submit" id="save_category_btn" name="submit"><?php echo lang('submit');?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                   
                <!-- /Add Lead Modal -->