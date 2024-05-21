<!-- Edit Performance Appraisal Modal -->
<?php $editable ='readonly'; ?>
<div class="modal-dialog modal-lg  modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title"><?php echo lang('edit_performance_appraisal');

		?></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
		<?php 
		$user_role_id = $this->session->userdata('role_id');
		$editable = $manager_editable = $hod_editable = 'disabled';

		$employee_id = $this->session->userdata('user_id');


		$this->db->select("*");
		$this->db->from("dgt_employee_appraisal");
		$this->db->where("id",$this->uri->segment(3));
		$tmp_response = $this->db->get()->row_array();

		$manager_signature = $employee_signature = $hod_signature = '';
		$manager_signature = $tmp_response['manager_signature'];
		$employee_signature = $tmp_response['employee_signature'];
		$hod_signature = $tmp_response['hod_signature'];
		$emplyee_sig_required = $manger_sig_required = $hod_sig_required = '';
		
		if($employee_signature =='')
		{
			$emplyee_sig_required = "required";
		}

		if($manager_signature =='')
		{
			$manger_sig_required = "required";
		}

		if($hod_signature =='')
		{
			$hod_sig_required = "required";
		}


		if($user_role_id == '1')
		{
			$editable = $manager_editable = $hod_editable =  '';
		}
		else{
			

			if(!empty($tmp_response))
			{
				$manager_comments = $tmp_response['Appraiser_Comments'];
				$hod_comments = $tmp_response['HOD_Remarks'];
				$tmp_employee_id = $tmp_response['employee_id'];
			}

			if($employee_id  === $tmp_employee_id)
			{
				$manager_editable = $hod_editable = 'disabled';
			}
			else{
				if($manager_comments == ''){
					$manager_editable = '';
				}

				if($hod_comments == ''){
					$hod_editable = '';
				}
			}

		}


		?>


		<form action="<?php echo site_url('appraisal/edit_appraisal/').$this->uri->segment(3);?>" method="post" id="edit_appraisal" enctype="multipart/form-data">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class=""><?php echo lang('employee');?> <span class="text-danger">*</span></label>
						<select class="select2-option form-control" style="width:100%" name="employee_id" <?php echo $editable; ?> required onchange="select_indicators(this.value)">
							<option value="<?= $appraisal_data['employee_id'] ?>" selected><?= User::displayName($appraisal_data['employee_id']) ?></option>	
<!-- <option value=""><?php echo lang('select_employee'); ?></option>
<?php foreach($employees as $emp) { ?>
<option value="<?php echo $emp->id;?>" <?php if($appraisal_data['employee_id'] == $emp->id){ echo "selected";} ?>><?php echo $emp->username;?></option>
<?php } ?> -->
</select>
</div>
<div class="form-group">
	<label><?php echo lang('select_date')?><span class="text-danger">*</span></label>
	<div class="cal-icon"><input <?php echo $editable; ?> class="form-control datetimepicker" name="appraisal_date" required value="<?php echo date('d/m/Y',strtotime($appraisal_data['appraisal_date']));?>" type="text" data-date-format="dd-mm-yyyy" ></div>
</div>

<div class="form-group">
	<label for="dateRange">Review Period</label>
	<div class="cal-icon"><input <?php echo $editable; ?> type="text" id="startDate" class="form-control datetimepicker" name="startDate" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y',strtotime($appraisal_data['startDate']));?>" required></div>
	<span> to </span>
	<div class="cal-icon"><input <?php echo $editable; ?> type="text" id="endDate" class="form-control datetimepicker" name="endDate" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y',strtotime($appraisal_data['endDate']));?>" required>
	</div></div>

	<div class="form-group">
		<label class="">Ratting Scale</label>
		<span>1 = Poor, 2 = Fair, 3 = Good, 4 = Very Good, 5 = Excellent</span>
	</div>



</div>
<div class="col-sm-12">

<!-- <div class="tab-box">
<div class="row user-tabs">
<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
<ul class="nav nav-tabs nav-tabs-solid">
	<li class="nav-item"><a href="#appr_technical1" data-toggle="tab" class="nav-link active"><?php echo lang('technical')?></a></li>
	<li class="nav-item"><a href="#appr_organizational1" data-toggle="tab" class="nav-link"><?php echo lang('organizational')?></a></li>
	
</ul>
</div>
</div>
</div> -->

<div class="tab-box">
	<div class="row user-tabs">
		<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
			<ul class="nav nav-tabs nav-tabs-solid">
				<?php
				foreach($indicator_names['technical'] as $key =>$values){
					$tmp_key1 = str_replace(' ', '_', $key); 
					?>
					<li class="nav-item"><a href="#<?php echo $tmp_key1;?>" data-toggle="tab" class="nav-link active" onclick="hide_div('<?php echo $tmp_key1; ?>'); return false;" ><?php echo $key; ?></a></li>
				<?php }
				?>
			</ul>
		</div>
	</div>
</div>

<!-- Start Tab -->
<div class="tab-content">
	<div id="Quality_Of_Work" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
		<div class="row">
			<div class="col-sm-12">
				<div class="bg-white table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
								<th style="font-weight: 500;"><?php echo lang('set_value');?></th>
							</tr>
							<?php
							foreach($indicator_names['technical'] as $key =>$values){
								$change_key = str_replace(" ","_",$key);
								if($change_key == 'Quality_Of_Work')
								{
						// $des_levels = json_decode($des_indicator['level'],true);
									$appr_levels = json_decode($appraisal_data[$change_key],true);

									foreach($values as $values_key => $tmp_val){ ?>
										<tr>
											<td scope="row" colspan="2"><?php echo $tmp_val;?></td>
											<td style="max-width:150px">
												<select <?php echo $editable; ?> name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
													<option value="1" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 1){ echo "selected";}?>> 1</option>
													<option value="2" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 2){ echo "selected";}?>> 2</option>
													<option value="3" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 3){ echo "selected";}?>> 3</option>
													<option value="4" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 4){ echo "selected";}?>>4</option>
													<option value="5" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 5){ echo "selected";}?>>5</option>
												</select>
											</td>
										</tr>
									<?php } } } ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>


			<div id="Work_Habits" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
				<div class="row">
					<div class="col-sm-12">
						<div class="bg-white table-responsive">
							<table class="table">
								<tbody>
									<tr>
										<th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
										<th style="font-weight: 500;"><?php echo lang('set_value');?></th>
									</tr>
									<?php
									foreach($indicator_names['technical'] as $key =>$values){
										$change_key = str_replace(" ","_",$key);
										if($change_key == 'Work_Habits')
										{

											$des_levels = json_decode($des_indicator['level'],true);
											$appr_levels = json_decode($appraisal_data[$change_key],true);
											foreach($values as $values_key => $tmp_val){ ?>
												<tr>
													<td scope="row" colspan="2"><?php echo $tmp_val;?></td>
													<td style="max-width:150px">
														<select <?php echo $editable; ?> name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
															<option value="1" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 1){ echo "selected";}?>> 1</option>
															<option value="2" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 2){ echo "selected";}?>> 2</option>
															<option value="3" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 3){ echo "selected";}?>> 3</option>
															<option value="4" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 4){ echo "selected";}?>>4</option>
															<option value="5" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 5){ echo "selected";}?>>5</option>
														</select>
													</td>
												</tr>
											<?php } } } ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>


					<div id="Job_Knowledge" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
						<div class="row">
							<div class="col-sm-12">
								<div class="bg-white table-responsive">
									<table class="table">
										<tbody>
											<tr>
												<th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
												<th style="font-weight: 500;"><?php echo lang('set_value');?></th>
											</tr>
											<?php
											foreach($indicator_names['technical'] as $key =>$values){
												$change_key = str_replace(" ","_",$key);
												if($change_key == 'Job_Knowledge')
												{

													$des_levels = json_decode($des_indicator['level'],true);
													$appr_levels = json_decode($appraisal_data[$change_key],true);
													foreach($values as $values_key => $tmp_val){ ?>
														<tr>
															<td scope="row" colspan="2"><?php echo $tmp_val;?></td>
															<td style="max-width:150px">
																<select <?php echo $editable; ?> name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
																	<option value="1" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 1){ echo "selected";}?>> 1</option>
																	<option value="2" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 2){ echo "selected";}?>> 2</option>
																	<option value="3" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 3){ echo "selected";}?>> 3</option>
																	<option value="4" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 4){ echo "selected";}?>>4</option>
																	<option value="5" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 5){ echo "selected";}?>>5</option>
																</select>
															</td>
														</tr>
													<?php } } } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div id="Teamwork" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
								<div class="row">
									<div class="col-sm-12">
										<div class="bg-white table-responsive">
											<table class="table">
												<tbody>
													<tr>
														<th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
														<th style="font-weight: 500;"><?php echo lang('set_value');?></th>
													</tr>
													<?php
													foreach($indicator_names['technical'] as $key =>$values){
// $change_key = str_replace(" ","_",$key);
														if($key == 'Teamwork')
														{
															$change_key = $key;
															$des_levels = json_decode($des_indicator['level'],true);
															$appr_levels = json_decode($appraisal_data[$change_key],true);
															foreach($values as $values_key => $tmp_val){ ?>
																<tr>
																	<td scope="row" colspan="2"><?php echo $tmp_val;?></td>
																	<td style="max-width:150px">
																		<select <?php echo $editable; ?> name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
																			<option value="1" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 1){ echo "selected";}?>> 1</option>
																			<option value="2" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 2){ echo "selected";}?>> 2</option>
																			<option value="3" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 3){ echo "selected";}?>> 3</option>
																			<option value="4" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 4){ echo "selected";}?>>4</option>
																			<option value="5" <?php if(isset($appr_levels[$values_key]) && $appr_levels[$values_key] == 5){ echo "selected";}?>>5</option>
																		</select>
																	</td>
																</tr>
															<?php } } } ?>

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>

								</div>
								<!-- End Tab -->

							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label class=""><?php echo 'Appraisee Comments'?></label>
									<textarea <?php echo $editable; ?> class="col-md-12" name='Appraisee_Comments' id='Appraisee_Comments'><?php echo $appraisal_data['Appraisee_Comments']; ?></textarea>
								</div>
							</div>

							<!-- <div class="col-sm-12">
								<div class="form-group">
									<label class=""><?php echo 'Appraiser Comments'?></label>
									<textarea <?php echo $manager_editable; ?> class="col-md-12" name='Appraiser_Comments' id='Appraiser_Comments'><?php echo $appraisal_data['Appraiser_Comments']; ?></textarea>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label class=""><?php echo 'HOD Remarks'?></label>
									<textarea <?php echo $hod_editable; ?> class="col-md-12" name='HOD_Remarks' id='HOD_Remarks'> <?php echo $appraisal_data['HOD_Remarks']; ?></textarea>
								</div>
							</div> -->

							<div class="col-sm-12">
								<div class="form-group">
									<label class=""><?php echo lang('status')?></label>
									<select class="select form-control" name="status" required>
										<option value="1" <?php if($appraisal_data['status'] ==1){ echo "selected";}?>><?php echo lang('active'); ?></option>
										<option value="0"<?php if($appraisal_data['status'] ==0){ echo "selected";}?>><?php echo lang('inactive'); ?></option>
									</select>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label class=""><?php echo 'Employee Signature';?></label>
									<input type="file" name="employee_signature" id ="employee_signature" <?php echo $emplyee_sig_required;?> />
								</div>
								<div class="form-group">
									<img src="<?php echo base_url("uploads/appriasal_signature/employee/").$this->uri->segment(3).'/'.$employee_signature;?>" height ='100px' width='100px'>
								</div>
							</div>
							<?php if($manager_editable == ''){?>
								<!-- <div class="col-sm-12">
									<div class="form-group">
										<label class=""><?php echo 'Manager Signature';?></label>
										<input type="file" name="manager_signature" id ="manager_signature"  <?php echo $manger_sig_required;?> />
									</div>
									<?php if($manager_signature !=""){?>
									<div class="form-group">
									<img src="<?php echo base_url("uploads/appriasal_signature/manager/").$this->uri->segment(3).'/'.$manager_signature;?>" height ='100px' width='100px'>
									</div>
									<?php } ?>
								</div> -->
							<?php }?>

							<?php if($hod_editable == ''){?>
								<!-- <div class="col-sm-12">
									<div class="form-group">
										 <label class=""><?php echo 'HOD Signature';?></label>
										<input type="file" name="hod_signature" id ="hod_signature"  <?php echo $hod_sig_required;?> />
									</div>
									<?php if($hod_signature !=""){?>
									<div class="form-group">
									<img src="<?php echo base_url("uploads/appriasal_signature/hod/").$this->uri->segment(3).'/'.$hod_signature;?>" height ='100px' width='100px'>
									</div>
								<?php } ?>
								</div> -->

							<?php }?>


							<div class="col-sm-12">
								<div class="form-group">
									<input type="checkbox" name="Accepted" id ="Accepted" <?php if($appraisal_data['Accepted'] =='on'){ echo "checked";}?>  required /  >&nbsp;&nbsp;Accept
								</div>
							</div>

							<?php 
							if($this->session->userdata("user_type_role") != 'employee'){
							?>
								<div class="row">
									<!-- <div class="col-sm-3">
										<div class="form-group">
											<label> KPO Score </label>
											<input type="text" name="kpo_score" min="0" class="form-control" id ="kpo_score" value='<?php echo $appraisal_data['kpo_score'] ? $appraisal_data['kpo_score'] : "0"; ?>'  required />
										</div>
									</div> -->

									<div class="col-sm-3">
										<div class="form-group">
											<label> Score </label>
											<?php 

											$kpo_total = 0;

											if(!empty($tmp_response))
											{
												$Teamwork_array = json_decode($tmp_response['Teamwork'], true);
												$Job_Knowledge_array = json_decode($tmp_response['Job_Knowledge'], true);
												$Work_Habits_array = json_decode($tmp_response['Work_Habits'], true);
												$Quality_Of_Work_array = json_decode($tmp_response['Quality_Of_Work'], true);
												$kpo_total = array_sum($Teamwork_array) +  array_sum($Job_Knowledge_array) + array_sum($Work_Habits_array) + array_sum($Quality_Of_Work_array);
											}
											
											// if(isset($appraisal_data['kpo_score']))
											// {
											// 	$kpo_total += $appraisal_data['kpo_score'];
											// }

											?>
											<input type="text" class="form-control" value='<?php echo $kpo_total; ?>'  readonly />
										</div>
									</div>

									<div class="col-sm-3">
										<div class="form-group">
											<label> Average of Score </label>
											<?php 
											$total_size_of_kpo = sizeof($Teamwork_array) + sizeof($Job_Knowledge_array) + sizeof($Work_Habits_array) + sizeof($Quality_Of_Work_array);

											$avgg = round(($kpo_total / $total_size_of_kpo) , 2) ;
											?>
											<input type="text" class="form-control"  value='<?php echo $avgg; ?>'  readonly />
										</div>
									</div>

									<div class="col-sm-3">
										<div class="form-group">
											<label> Total Fields</label>
											<input type="text" class="form-control"  value='<?php echo $total_size_of_kpo; ?>'  readonly />
										</div>
									</div>
								</div>
							<?php 	
							}
							?>


						</div>
						<!-- <div class="submit-section">
							<button type="submit" name="submit" id="edit_appraisal_btn" class="btn btn-primary submit-btn" onclick="appraisal_from();"><?php echo lang('save');?></button>
						</div> -->
					</form>
				</div>
			</div>
		</div>

		<!-- /Edit Performance Appraisal Modal -->
		<script>
			$(".select2-option").select2({
				minimumInputLength: 3,
				tags: [],
				ajax: {
					url: "<?php echo base_url('appraisal/getEmployees');?>",
					dataType: 'json',
					type: "GET",
					quietMillis: 2,
					data: function (term) {
						return {
							term: term
						};
					},
					processResults: function (data) {
						return {
							results: $.map(data, function (item) {
								return {
									text: item.fullname+' ('+item.email+')',
									slug: item.email,
									id: item.id
								}
							})
						};
					}
				}
			});


			function hide_div(div_id)
			{
				$(".div_hide_css").hide();
				$("#"+div_id).show();
			}

		</script>