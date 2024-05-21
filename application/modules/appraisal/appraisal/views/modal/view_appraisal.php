<!-- Edit Performance Appraisal Modal -->
			
					<div class="modal-dialog  modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?php echo lang('view_performance_appraisal');

								?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('employee');?> : </label>
												<?php echo $appraisal_data['username']?>
											</div>
											<div class="form-group">
												<label><?php echo lang('appraisal_date')?> : </label>
												<?php echo date('d/m/Y',strtotime($appraisal_data['appraisal_date']));?>
											</div>
											<div class="form-group">
												<label><?php echo lang('designation')?> : </label>
												<?php echo $appraisal_data['designation'];?>
											</div>
											<div class="form-group">
												<label><?php echo lang('department')?> : </label>
												<?php echo $appraisal_data['deptname'];?>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="card">
												<div class="card-body">
													<div class="tab-box">
														<div class="row user-tabs">
															<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
																<ul class="nav nav-tabs nav-tabs-solid">
																	<li class="nav-item"><a href="#appr_technical1" data-toggle="tab" class="nav-link active"><?php echo lang('technical')?></a></li>
																	<li class="nav-item"><a href="#appr_organizational1" data-toggle="tab" class="nav-link"><?php echo lang('organizational')?></a></li>
																	
																</ul>
															</div>
														</div>
													</div>
<div class="tab-content text-center">
<div id="appr_technical1" class="pro-overview tab-pane fade show active">
<div class="row">
<div class="col-sm-12">
<div class="bg-white table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th colspan="5"><?php echo lang('technical_competencies');?></th>
</tr>
</thead>
<tbody>
<tr>
<th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
<th colspan="2" style="font-weight: 500;"><?php echo lang('expected_value');?></th>
<th style="font-weight: 500;"><?php echo lang('set_value');?></th>
</tr>
<?php 
$des_levels = json_decode($appraisal_data['level'],true);
$appr_levels = json_decode($appraisal_data['levels'],true);

foreach($indicator_names['technical'] as $key => $value){

	$j = $key;
                                        ?>
                                        	<tr>
                                        <td scope="row" colspan="2"><?php echo $value;?></td>
                                        <td colspan="2" id="level_<?php echo $key;?>"> <?php
                                     if($des_levels[$j]==1){ echo lang('beginner'); }
                                     if($des_levels[$j]==2){ echo lang('intermediate'); }
                                     if($des_levels[$j]==3){ echo lang('advanced'); }
                                     if($des_levels[$j]==4){ echo lang('expert_leader'); }
                                     ?></td>
                                        <td>
                                        	<select name="levels[<?php echo $key;?>]" class="form-control" disabled>
	                                          
	                                          <option value="1" <?php if(isset($appr_levels[$j]) && $appr_levels[$j] == 1){ echo "selected";}?>> <?php echo lang('beginner')?></option>
	                                          <option value="2" <?php if(isset($appr_levels[$j]) && $appr_levels[$j] == 2){ echo "selected";}?>> <?php echo lang('intermediate')?></option>
	                                          <option value="3" <?php if(isset($appr_levels[$j]) && $appr_levels[$j] == 3){ echo "selected";}?>> <?php echo lang('advanced')?></option>
	                                          <option value="4" <?php if(isset($appr_levels[$j]) && $appr_levels[$j] == 4){ echo "selected";}?>><?php echo lang('expert_leader')?> </option>
                                          </select>
                                      </td>
                                        </tr>
                                        <?php	
                                        }?>

</tbody>
</table>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="appr_organizational1">
<div class="row">
<div class="col-sm-12">
<div class="bg-white table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th colspan="5"><?php echo lang('organizational_competencies')?></th>
</tr>
</thead>
<tbody>
<tr>
<th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
<th colspan="2" style="font-weight: 500;"><?php echo lang('expected_value');?></th>
<th style="font-weight: 500;"><?php echo lang('set_value');?></th>
</tr>
<?php foreach($indicator_names['organization'] as $key1 => $value1){
                                   $k = $key1;
                                        ?>
                                        	<tr>
                                        <td scope="row" colspan="2"><?php echo $value1;?></td>
                                        <td colspan="2" id="level_<?php echo $key1;?>">
                                     <?php
                                     if($des_levels[$k]==1){ echo lang('beginner'); }
                                     if($des_levels[$k]==2){ echo lang('intermediate'); }
                                     if($des_levels[$k]==3){ echo lang('advanced'); }
                                     if($des_levels[$k]==4){ echo lang('expert_leader'); }
                                     ?></td>
                                        <td>
                                        	<select name="levels[<?php echo $key1;?>]" class="form-control" disabled>
	                                        
	                                          <option value="1" <?php if(isset($appr_levels[$k]) && $appr_levels[$k] == 1){ echo "selected";}?>> <?php echo lang('beginner')?></option>
	                                          <option value="2"  <?php if(isset($appr_levels[$k]) && $appr_levels[$k] == 2){ echo "selected";}?>> <?php echo lang('intermediate')?></option>
	                                          <option value="3"  <?php if(isset($appr_levels[$k]) && $appr_levels[$k] == 3){ echo "selected";}?>> <?php echo lang('advanced')?></option>
	                                          <option value="4"  <?php if(isset($appr_levels[$k]) && $appr_levels[$k] == 4){ echo "selected";}?>><?php echo lang('expert_leader')?> </option>
                                          </select>
                                      </td>
                                        </tr>
                                        <?php	
                                        }?>

</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-form-label"><?php echo lang('status')?> : </label>
												<?php if($appraisal_data['status'] ==1){ echo lang('active');}else{
													echo lang('inactive');
												}
												?>
												
											</div>
										</div>
									</div>
									
								
							</div>
						</div>
					</div>
				
				<!-- /Edit Performance Appraisal Modal -->