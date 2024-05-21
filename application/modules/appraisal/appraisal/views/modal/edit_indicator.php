<!-- Edit Performance Indicator Modal -->
				
					<div class="modal-dialog  modal-dialog-centered" >
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?php echo lang('edit_performance_indicator');?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="<?php echo site_url('appraisal/edit_indicator/').$this->uri->segment(3); ?>" id="edit_indicator" method="post">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<?php echo lang('designation');?> <span class="text-danger">*</span>
												<select class="select form-control" name="designation" required>
													<option value=""><?php echo lang('select_designation')?></option>
													<?php foreach($designations as $res){ ?>
													<option value="<?php echo $res->id;?>" <?php if(isset($inidicator_data['designation_id']) && $inidicator_data['designation_id'] ==$res->id ){ echo "selected";}?>><?php echo $res->designation;
									 ?></option>
												<?php } ?>
													
												</select>
											</div>
										</div>
										<div class="col-sm-6">
											<h4 class="modal-sub-title"><?php echo lang('technical');?></h4>
											<?php 
											if(isset($inidicator_data['indicator'])){
												$indicatorlist = json_decode($inidicator_data['indicator'],true);
												$indicatorlevel = json_decode($inidicator_data['level'],true);
												foreach ($indicatorlist as $key => $ind) {
													$levels[$ind] = $indicatorlevel[$key];
												}

											}
											foreach($indicator_names['technical'] as $key =>$values){?>										<div class="form-group">
												<label class="col-form-label"><?php echo $values;?></label>
												<select class="select form-control" name="indicators_level[<?php echo $key; ?>]">
													
													<option value="1" <?php if(isset($levels[$key]) && $levels[$key] == 1){ echo "selected";}?>><?php echo lang('beginner')?></option>
													<option value="2" <?php if(isset($levels[$key]) && $levels[$key] == 2){ echo "selected";}?>><?php echo lang('intermediate')?></option>
													<option value="3" <?php if(isset($levels[$key]) && $levels[$key] == 3){ echo "selected";}?>><?php echo lang('advanced')?></option>
													<option value="4" <?php if(isset($levels[$key]) && $levels[$key] == 4){ echo "selected";}?>><?php echo lang('expert_leader')?></option>
												</select>
											</div>
										<?php }  ?>
											
										</div>
												<div class="col-sm-6">
											<h4 class="modal-sub-title"><?php echo lang('organizational');?></h4>
											<?php foreach($indicator_names['organization'] as $key1 =>$values1){?>										<div class="form-group">
												<label class="col-form-label"><?php echo $values1;?></label>
												<select class="select form-control" name="indicators_level[<?php echo $key1; ?>]">
													
													<option value="1" <?php if(isset($levels[$key1]) && $levels[$key1] == 1){ echo "selected";}?>><?php echo lang('beginner')?></option>
													<option value="2" <?php if(isset($levels[$key1]) && $levels[$key1] == 2){ echo "selected";}?>><?php echo lang('intermediate')?></option>
													<option value="3" <?php if(isset($levels[$key1]) && $levels[$key1] == 3){ echo "selected";}?>><?php echo lang('advanced')?></option>
													<option value="4" <?php if(isset($levels[$key1]) && $levels[$key1] == 4){ echo "selected";}?>><?php echo lang('expert_leader')?></option>
												</select>
											</div>
										<?php }  ?>
											
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('status');?></label>
												<select class="select form-control" name="status">
													<option value="1" <?php if($inidicator_data['status'] ==1){ echo "selected";}?>><?php echo lang('active')?></option>
													<option value="0" <?php if($inidicator_data['status']==0){ echo "selected";}?>><?php echo lang('inactive')?></option>
												</select>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<input type="submit" name="submit" value="<?php echo lang('submit')?>" id="edit_indicator_btn" onclick="indicator_form()"  class="btn btn-primary submit-btn">
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Performance Indicator Modal -->