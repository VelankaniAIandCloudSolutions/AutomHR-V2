<!-- Add Performance Indicator Modal -->
				
					<div class="modal-dialog  modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?php echo lang('set_new_indicator'); ?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form name="add_indicator" method="post" action="<?php echo site_url('appraisal/add_indicator'); ?>" id="add_indicator">
									<div class="row">
										<!-- <div class="col-sm-12">
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('designation');?> <span class="text-danger">*</span></label>
												<select class="select form-control" name="designation" required>
													<option value=""><?php echo lang('select_designation')?></option>
													<?php foreach($designations as $res){ ?>
													<option value="<?php echo $res->id;?>"><?php echo $res->designation;
									 ?></option>
												<?php } ?>
													
												</select>
											</div>
										</div> -->
										<div class="col-sm-12">
											<!-- <h4 class="modal-sub-title"><?php echo lang('technical');?></h4> -->
											<?php
											 foreach($indicator_names['technical'] as $key =>$values){
											 	echo '<h4 class="modal-sub-title">'.$key.'</h4>';
											 	foreach($values as $tmp_val){ ?>								
											 		<div class="form-group">
												<label class="col-form-label"><?php echo $tmp_val;?></label>
												<!-- <select class="select form-control" name="indicators_level[<?php echo $values; ?>]" required>
													<option value=""></option>
													<option value="1"><?php echo lang('beginner')?></option>
													<option value="2"><?php echo lang('intermediate')?></option>
													<option value="3"><?php echo lang('advanced')?></option>
													<option value="4"><?php echo lang('expert_leader')?></option>
												</select> -->
											</div>
										<?php } }  ?>
											
										</div>
										<!-- <div class="col-sm-6">
											<h4 class="modal-sub-title"><?php echo lang('organizational');?></h4>
											<?php foreach($indicator_names['organization'] as $key1 =>$values1){?>										<div class="form-group">
												<label class="col-form-label"><?php echo $values1;?></label>
												<select class="select form-control" name="indicators_level[<?php echo $key1; ?>]" required>
													<option value=""></option>
													<option value="1"><?php echo lang('beginner')?></option>
													<option value="2"><?php echo lang('intermediate')?></option>
													<option value="3"><?php echo lang('advanced')?></option>
													<option value="4"><?php echo lang('expert_leader')?></option>
												</select>
											</div>
										<?php }  ?>
											
										</div> -->
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('status')?></label>
												<select class="select form-control" name="status" required>
													<option value="1"><?php echo lang('active')?></option>
													<option value="0"><?php echo lang('inactive')?></option>
												</select>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<input type="submit" name="submit" id="add_indicator_btn" value="<?php echo lang('submit');?>" onclick="indicator_form()" class="btn btn-primary submit-btn">
									</div>
								</form>
							</div>
						</div>
					</div>
				
				<!-- /Add Performance Indicator Modal -->