<?php
$branches = $this->db->where('branch_status','0')->get('branches')->result();
?>
<div class="content">
	<div class="row">
		<div class="col-sm-4 col-xs-3">
			<h4 class="page-title"><?=lang('leave_settings')?></h4>
		</div>
		<div class="col-sm-8 col-xs-9 text-right m-b-20">
			<a href="#" class="btn add-btn" data-toggle="modal" data-target="#new_leave_type" >
				<i class="fa fa-plus"></i> <?='Add New Type';?>
            </a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<!-- Custom Policy -->
			<div class="custom-policy">
				<div class="leave-header">
					<div class="title">Casual Leave</div>
				</div>
				<table class="table table-hover table-nowrap leave-table">
					<thead>
						<tr>
							<th class="l-name">Entity</th>
							<th class="l-name" style="white-space:nowrap">Number of Days Per Month</th>
							<th class="l-days">Total Annual Days</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(isset($branches) && !empty($branches))
						{
							foreach($branches as $brn)
							{
								$leaves = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',9)->get('common_leave_types')->row_array();
							?>
								<tr>
									<td><?php echo $brn->branch_name;?></td>
									<td><input type="number" class="form-control" id="accrual_leaves_day_month_<?=$brn->branch_id;?>" value="<?php echo (isset($leaves['leave_day_month']))?$leaves['leave_day_month']:'';?>" ></td>
									<td><input type="number" class="form-control" id="accrual_leaves_day_<?=$brn->branch_id;?>" value="<?php echo (isset($leaves['leave_days']))?$leaves['leave_days']:'';?>" ></td>
									<td>
									<button class="btn btn-primary leave-save-btn" onclick="accrual_leaves('<?=$brn->branch_id;?>');"  type="submit" >Save</button>
									</td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
				<!-- /Custom Policy -->

			<!-- Custom Policy -->
			<div class="custom-policy">
				<div class="leave-header">
					<div class="title">Leave</div>
				</div>
				<table class="table table-hover table-nowrap leave-table">
					<thead>
						<tr>
							<th class="l-name">Entity</th>
							<th class="l-name" style="white-space:nowrap">Number of Days Per Month</th>
							<th class="l-days">Total Annual Days</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$branches = $this->db->where('branch_status','0')->get('branches')->result();
						if(isset($branches) && !empty($branches))
						{
							foreach($branches as $brn)
							{
								$leaves = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',24)->get('common_leave_types')->row_array();
							?>
								<tr>
									<td><?php echo $brn->branch_name;?></td>
									<td><input type="number" class="form-control" id="leaves_day_month_<?=$brn->branch_id;?>" value="<?php echo (isset($leaves['leave_day_month']))?$leaves['leave_day_month']:'';?>" ></td>
									<td><input type="number" class="form-control" id="leaves_day_<?=$brn->branch_id;?>" value="<?php echo (isset($leaves['leave_days']))?$leaves['leave_days']:'';?>" ></td>
									<td>
									<button class="btn btn-primary leave-save-btn" onclick="leaves('<?=$brn->branch_id;?>');"  type="submit" >Save</button>
									</td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
				<!-- /Custom Policy -->
				<!-- Custom Policy -->
			<div class="custom-policy">
				<div class="leave-header">
					<div class="title">Privilege Leave</div>
				</div>
				<table class="table table-hover table-nowrap leave-table">
					<thead>
						<tr>
							<th class="l-name">Entity</th>
							<th class="l-name" style="white-space:nowrap">Number of Days Per Month</th>
							<th class="l-days">Total Annual Days</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$branches = $this->db->where('branch_status','0')->get('branches')->result();
						if(isset($branches) && !empty($branches))
						{
							foreach($branches as $brn)
							{
								$leaves = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',28)->get('common_leave_types')->row_array();
							?>
								<tr>
									<td><?php echo $brn->branch_name;?></td>
									<td><input type="number" class="form-control" id="privilege_leaves_day_month_<?=$brn->branch_id;?>" value="<?php echo (isset($leaves['leave_day_month']))?$leaves['leave_day_month']:'';?>" ></td>
									<td><input type="number" class="form-control" id="privilege_leaves_day_<?=$brn->branch_id;?>" value="<?php echo (isset($leaves['leave_days']))?$leaves['leave_days']:'';?>" ></td>
									<td>
									<button class="btn btn-primary leave-save-btn" onclick="privilege_leaves('<?=$brn->branch_id;?>');"  type="submit" >Save</button>
									</td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
				<!-- /Custom Policy -->
		<?php /*
			<!-- Annual Leave -->
			<div class="card-box leave-box" id="leave_annual">
				<?php $leave_annual = $this->db->get_where('common_leave_types',array('leave_id'=>1))->row_array(); ?>
				<h3 class="card-title with-switch">
					Annual 											
					
				</h3>
				<div class="form-group form-check">
					<!-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> -->
					<label class="form-check-label" for="exampleCheck1">Adjust employee's leave balance to an updated Days immediately</label>
					<span class="form-text mt-0">(Any updates made to Days, carry forward and custom policy will be effective from this cycle)</span>
				</div>
				<div class="leave-item">
					<!-- Annual Days Leave -->
					<div class="leave-row">
						<div class="leave-left">
							<div class="input-box">
							<?php
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leaves_branch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',1)->get('dgt_common_leave_types')->row();
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control Daystext" id="annual_leaves_<?=$brn->branch_id;?>"  value="<?php echo $leaves_branch_wise->leave_days; ?>" >
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="update_leaves(<?=$brn->branch_id;?>);" type="submit" id="annual_leave_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
						</div>
						<!--<div class="leave-right">
							<div class="UpdateBtn" style="display: none;">
								<button class="btn btn-white CancelBtn" >Cancel</button>
								<button class="btn btn-primary">Save</button>
							</div>
							<div class="EditBtn">
								<button class="leave-edit-btn BtnEdit">Edit</button>
							</div>
						</div>
						<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
						</div> -->
					</div>
					<!-- /Annual Days Leave -->
					
					
					
					<!-- Earned Leave -->
					<div class="leave-row">

					</div>
					<!-- /Earned Leave -->
					
				</div>
				
				<!-- Custom Policy -->
				<!--<div class="custom-policy">
					<div class="leave-header">
						<div class="title">Custom policy</div>
						<div class="leave-action">
							<button class="btn btn-sm btn-primary PolicyID" type="button" data-id="8" data-toggle="modal" data-target="#add_custom_policy"><i class="fa fa-plus"></i> Add custom policy</button>
						</div>
					</div>
					<table class="table table-hover table-nowrap leave-table">
						<thead>
							<tr>
								<th class="l-name">Name</th>
								<th class="l-days">Days</th>
								<th class="l-assignee">Assignee</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $all_users = $this->db->get_where('custom_policy',array('leave_id'=>8))->result_array(); 
								foreach($all_users as $user){
									$users_all = $this->db->select('*')
													         ->from('assigned_policy_user AU')
													         ->join('account_details AD','AU.user_id = AD.user_id')
													         ->where('AU.policy_id',$user['policy_id'])
													         ->get()->result_array();
									// echo "<pre>"; print_r($users_all); exit;
									foreach($users_all as $al_user)
									{
							?>
							<tr>
								<td><?php echo $user['custom_policy_name']; ?></td>
								<td><?php echo $user['policy_leave_days']; ?></td>
								<td>
									<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $al_user['user_id']; ?>" class="avatar"><?php echo strtoupper($al_user['fullname'][0]); ?></a>
									<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $al_user['user_id']; ?>"><?php echo $al_user['fullname']; ?></a>
								</td>
								<td class="text-right">
									<div class="dropdown dropdown-action">
										<a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item EditCustomUser" data-toggle="modal" data-id="<?php echo $al_user['assigned_id']; ?>" data-target="#edit_custom_policy<?php echo $user['policy_id'].$al_user['user_id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
											<a href="#" class="dropdown-item" onclick="delete_custom_policy(<?php echo $al_user['assigned_id']; ?>)"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
										</div>
									</div>
								</td>
							</tr>
						<?php } } ?>
						</tbody>
					</table>
				</div>-->
				<!-- /Custom Policy -->
				
			</div>
			<!-- /Annual Leave -->
			
			*/?>
			<?php /*$leave_sick = $this->db->get_where('common_leave_types',array('leave_id'=>4))->row_array(); ?>
			<!-- Sick Leave -->
			<div class="card-box leave-box" id="leave_sick">
				<h3 class="card-title with-switch">
					Carry forward 											
				</h3>
				
				<div class="leave-item">
					<!-- Carry Forward -->
					<div class="leave-row">
						<div class="leave-left">
						
						<div class="input-box">
							<?php
							// $leave_carry = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leave_carry_branch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',2)->get('dgt_common_leave_types')->row();
							// $leave_carry_branch_wise = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="cm_branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control Daystext" id="carry_max_<?=$brn->branch_id;?>" value="<?php echo $leave_carry_branch_wise->leave_days; ?>"  >
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="cf_update_leaves(<?=$brn->branch_id;?>);" type="submit" id="carry_forward_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
							
							<!--<div class="input-box">
								<div class="leave-inline-form">
									<!--<label class="radio-inline">
									<?php $leave_carry = $this->db->get_where('common_leave_types',array('leave_id'=>2))->row_array(); ?>
										<input type="radio" name="carryfwd" class="CarryFwd" value="no"  disabled="disabled" <?php if($leave_carry['leave_status']=='no'){ ?> checked <?php } ?>>No
									</label>
									<label class="radio-inline">
										<input type="radio" name="carryfwd" class="CarryFwd" value="yes" disabled="disabled" <?php if($leave_carry['leave_status']=='yes'){ ?> checked <?php } ?>>Yes
									</label>-->
									<!--<div class="input-group" id="MaxDays" >
										<!--<span class="input-group-addon">
											Max
										</span>-->
										<!--<label>Days</label>
										<input type="text" class="form-control" id="carry_max" value="<?php echo $leave_carry['leave_days']; ?>"  >
									</div>
								</div>
							</div>-->
						</div>
						<!--<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="carry_forward">Edit</button>
						</div>-->
						<!-- <div class="leave-right">
							<div class="UpdateMaxBtn" style="display: none;">
								<button class="btn btn-white CancelMaxBtn" >Cancel</button>
								<button class="btn btn-primary">Save</button>
							</div>
							<button class="leave-edit-btn EditMax">
								Edit
							</button>
						</div> -->
					</div>
					<!-- /Carry Forward -->
				</div>
			</div>
			<!-- /Sick Leave -->
			
			*/?>
				<?php $leave_sick = $this->db->get_where('common_leave_types',array('leave_id'=>4))->row_array(); /*?>
			<!-- Sick Leave -->
			<div class="card-box leave-box" id="leave_sick">
				<h3 class="card-title with-switch">
					Sick 											
					<!--<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" data-id="4" id="switch_sick" <?php if($leave_sick['status'] == 0 ){ ?>checked <?php } ?>>
						<label class="onoffswitch-label" for="switch_sick">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>-->
				</h3>
				<div class="form-group form-check">
					<!-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> -->
					<label class="form-check-label" for="exampleCheck1">Adjust employee's leave balance to an updated Days immediately</label>
					<span class="form-text mt-0">(Any updates made to Days, carry forward and custom policy will be effective from this cycle)</span>
				</div>
				<div class="leave-item">
					<div class="leave-row">
						<div class="leave-left">
						
						<div class="input-box">
							<?php
							// $leave_carry = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leave_sick_banch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',4)->get('dgt_common_leave_types')->row();

							// $leave_carry_branch_wise = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="cm_branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control" id="sick_leave_<?=$brn->branch_id;?>" value="<?php echo $leave_sick_banch_wise->leave_days; ?>" >
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="sick_update_leaves(<?=$brn->branch_id;?>);" type="submit" id="sick_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
							
							<!--<div class="input-box">
								<div class="form-group">
									<label>Days</label>
									<input type="text" class="form-control" id="sick_leave" value="<?php echo $leave_sick['leave_days']; ?>" disabled>
								</div>
							</div>-->
							
							
						</div>
						<!--<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="sick">Edit</button>
						</div>-->
					</div>
				</div>
			</div>
			<!-- /Sick Leave -->
			
				<?php */$leave_hosp = $this->db->get_where('common_leave_types',array('leave_id'=>5))->row_array(); /*?>
			<!-- Hospitalisation Leave -->
			<div class="card-box leave-box" id="leave_hospitalisation">
				<h3 class="card-title with-switch">
					Hospitalisation 											
					<!--<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_hospitalisation" data-id="5"  <?php if($leave_hosp['status'] == 0 ){ ?>checked <?php } ?>>
						<label class="onoffswitch-label" for="switch_hospitalisation">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>-->
				</h3>
				<div class="form-group form-check">
					<!-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> -->
					<label class="form-check-label" for="exampleCheck1">Adjust employee's leave balance to an updated Days immediately</label>
					<span class="form-text mt-0">(Any updates made to Days, carry forward and custom policy will be effective from this cycle)</span>
				</div>
				<div class="leave-item">
					<!-- Annual Days Leave -->
					<div class="leave-row">
						<div class="leave-left">
							<div class="input-box">
							<?php
							// $leave_carry = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leave_hosp_banch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',5)->get('dgt_common_leave_types')->row();

							// $leave_carry_branch_wise = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="cm_branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control" id="hospitalisation_<?=$brn->branch_id;?>" value="<?php echo $leave_hosp_banch_wise->leave_days; ?>" >
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="hospitalisation_update_leaves(<?=$brn->branch_id;?>);" type="submit" id="hospitalisation_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
							
							<!--<div class="input-box">
								<div class="form-group">
									<label>Days</label>
									<input type="text" class="form-control" id="hospitalisation" value="<?php echo $leave_hosp['leave_days']; ?>" >
								</div>
							</div>-->
							
						</div>
						<!--<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="hospitalisation">Edit</button>
						</div>-->
					</div>
					<!-- /Annual Days Leave -->
					
				</div>
				
				<!-- Custom Policy -->
				<!--<div class="custom-policy">
					<div class="leave-header">
						<div class="title">Custom policy</div>
						<div class="leave-action">
							<button class="btn btn-sm btn-primary PolicyID" type="button" data-id="9" data-toggle="modal" data-target="#add_custom_policy"><i class="fa fa-plus"></i> Add custom policy</button>
						</div>
					</div>
					<table class="table table-hover table-nowrap leave-table">
						<thead>
							<tr>
								<th class="l-name">Name</th>
								<th class="l-days">Days</th>
								<th class="l-assignee">Assignee</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $all_user = $this->db->get_where('custom_policy',array('leave_id'=>9))->result_array(); 
								foreach($all_user as $use){
									$users_al = $this->db->select('*')
													         ->from('assigned_policy_user AU')
													         ->join('account_details AD','AU.user_id = AD.user_id')
													         ->where('AU.policy_id',$use['policy_id'])
													         ->get()->result_array();
									// echo "<pre>"; print_r($users_all); exit;
									foreach($users_al as $a_user)
									{
							?>
							<tr>
								<td><?php echo $use['custom_policy_name']; ?></td>
								<td><?php echo $use['policy_leave_days']; ?></td>
								<td>
									<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $a_user['user_id']; ?>" class="avatar"><?php echo strtoupper($a_user['fullname'][0]); ?></a>
									<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $a_user['user_id']; ?>"><?php echo $a_user['fullname']; ?></a>
								</td>
								<td class="text-right">
									<div class="dropdown dropdown-action">
										<a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item" data-toggle="modal" data-target="#edit_custom_policy"><i class="fa fa-pencil m-r-5"></i> Edit</a>
											<a href="#" class="dropdown-item" onclick="delete_custom_policy(<?php echo $a_user['assigned_id']; ?>)"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
										</div>
									</div>
								</td>
							</tr>
						<?php } } ?>
						</tbody>
					</table>
				</div>-->
				<!-- /Custom Policy -->
				
			</div>
			<!-- /Hospitalisation Leave -->
			
				<?php */$leave_maternity = $this->db->get_where('common_leave_types',array('leave_id'=>6))->row_array(); /*?>
			<!-- Maternity Leave -->
			<div class="card-box leave-box" id="leave_maternity">
				<h3 class="card-title with-switch">
					Maternity  <span class="subtitle">Assigned to female only</span>
					<!--<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_maternity" data-id="6"  <?php if($leave_maternity['status'] == 0 ){ ?>checked <?php } ?>>
						<label class="onoffswitch-label" for="switch_maternity">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>-->
				</h3>
				<div class="form-group form-check">
					<!-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> -->
					<label class="form-check-label" for="exampleCheck1">Adjust employee's leave balance to an updated Days immediately</label>
					<span class="form-text mt-0">(Any updates made to Days, carry forward and custom policy will be effective from this cycle)</span>
				</div>
				<div class="leave-item">
					<div class="leave-row">
						<div class="leave-left">
							<div class="input-box">
							<?php
							// $leave_carry = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leave_maternity_banch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',6)->get('dgt_common_leave_types')->row();

							// $leave_carry_branch_wise = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="cm_branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control" id="maternity_leaves_<?=$brn->branch_id;?>" value="<?php echo $leave_maternity_banch_wise->leave_days; ?>" >
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="maternity_update_leaves(<?=$brn->branch_id;?>);" type="submit" id="maternity_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
							
							
							<!--<div class="input-box">
								<div class="form-group">
									<label>Days</label>
									<input type="text" class="form-control" id="maternity_leaves" value="<?php echo $leave_maternity['leave_days']; ?>" disabled>
								</div>
							</div>-->
							
						</div>
						<!--<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="maternity">Edit</button>
						</div>-->
					</div>
				</div>
			</div>
			<!-- /Maternity Leave -->
			
				<?php */$leave_paternity = $this->db->get_where('common_leave_types',array('leave_id'=>7))->row_array(); /*?>
			<!-- Paternity Leave -->
			<div class="card-box leave-box" id="leave_paternity">
				<h3 class="card-title with-switch">
					Paternity  <span class="subtitle">Assigned to male only</span>
					<!--<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_paternity" data-id="7"  <?php if($leave_paternity['status'] == 0 ){ ?>checked <?php } ?>>
						<label class="onoffswitch-label" for="switch_paternity">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>-->
				</h3>
				<div class="form-group form-check">
					<!-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> -->
					<label class="form-check-label" for="exampleCheck1">Adjust employee's leave balance to an updated Days immediately</label>
					<span class="form-text mt-0">(Any updates made to Days, carry forward and custom policy will be effective from this cycle)</span>
				</div>
				<div class="leave-item">
					<div class="leave-row">
						<div class="leave-left">
						
						<div class="input-box">
							<?php
							// $leave_carry = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leave_paternity_banch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',7)->get('dgt_common_leave_types')->row();

							// $leave_carry_branch_wise = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="cm_branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control" id="paternity_leaves_<?=$brn->branch_id;?>" value="<?php echo $leave_paternity_banch_wise->leave_days; ?>" >
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="paternity_update_leaves(<?=$brn->branch_id;?>);" type="submit" id="paternity_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
							
							<!--<div class="input-box">
								<div class="form-group">
									<label>Days</label>
									<input type="text" class="form-control" id="paternity_leaves" value="<?php echo $leave_paternity['leave_days']; ?>" disabled>
								</div>
							</div>-->
							
							
						</div>
						<!--<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="paternity">Edit</button>
						</div>-->
					</div>
				</div>
			</div>
			<!-- /Paternity Leave -->

			<?php */
			// $this->db->select('*');
			 //$this->db->from('dgt_common_leave_types');
			// $this->db->where('leave_type_id >=8');
			// $this->db->group_by('leave_type_id');
			// $extra_leave_types=$this->db->result_array();
			
			//$extra_leave_types = $this->db->group_by('leave_type_id')->order_by('leave_id', 'ASC')->get_where('dgt_common_leave_types',array('leave_type_id >='=>8))->result_array(); 
			
			$extra_leave_types = $this->db->group_by('leave_type_id')->order_by('leave_id', 'ASC')->get_where('dgt_common_leave_types',array('leave_type_id!='=>47,'leave_type_id!='=>9,'leave_type_id!='=>24,'leave_type_id!='=>28,'status'=>1))->result_array(); 
			if(count($extra_leave_types) != 0){
					foreach($extra_leave_types as $extra_leaves){

						if($extra_leaves['leave_type_id'] != "3" && $extra_leaves['leave_type_id'] != "24" && $extra_leaves['leave_type_id'] != "9")
						{
			?>
			<!-- Dynamic Leave Types -->


			<div class="card-box leave-box" <?php if($extra_leaves['leave_type_id']==47){?> style="display:none"<?php }?>>
				<h3 class="card-title with-switch">
					<?php echo $extra_leaves['leave_type']; ?>  
					<!--<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox ALLExtraSwitch" id="switch_<?php echo $extra_leaves['leave_type']; ?>" data-id="<?php echo $extra_leaves['leave_id']; ?>" <?php if($extra_leaves['status'] == 0 ){ echo "checked"; } ?> >
						<label class="onoffswitch-label" for="switch_<?php echo $extra_leaves['leave_type']; ?>">
							<span class="onoffswitch-inner"></span>
							<span class="onoffswitch-switch"></span>
						</label>
					</div>-->
					<?php /*<a class="btn btn-danger leave-delete-btn" onclick="deletotherleaves(<?=$extra_leaves['leave_type_id']?>);" data-id="<?php echo $extra_leaves['leave_id']; ?>">Delete</a>*/?>
				</h3>
				<div class="form-group form-check">
					<label class="form-check-label" for="exampleCheck1">Adjust employee's leave balance to an updated Days immediately <?php //echo $extra_leaves['leave_type_id']?></label>
					<span class="form-text mt-0">(Any updates made to Days, carry forward and custom policy will be effective from this cycle)</span>
				</div>
				<div class="leave-item">
					<div class="leave-row">
						<div class="leave-left">
						
						<div class="input-box">
							<?php
							// $leave_carry = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
								if(isset($branches) && !empty($branches))
								{
									foreach($branches as $brn)
									{
										$leave_extra_banch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',$extra_leaves['leave_type_id'])->get('dgt_common_leave_types')->row();

							// $leave_carry_branch_wise = $this->db->get_where('common_leave_types',array('leave_type_id'=>2))->row_array(); 
										
										// 	echo '<pre>';print_r($leaves_branch_wise->leave_days);exit;

										?>
										
										<div class="row">
										<div class="col-md-6">
										<div class="form-group">
											<label>Entity</label>
											<input type="text" class="form-control Daystext" id="cm_branch_id_<?=$brn->branch_id;?>"  value="<?php echo $brn->branch_name; ?>" disabled>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-group">
											<label>Days</label>
											<input type="text" class="form-control" id="extra_leaves_<?=$brn->branch_id;?>" value="<?php echo $leave_extra_banch_wise->leave_days; ?>" onkeyup="update_leave(this)">
										</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-group">
											<div class="leave-right">
												<!--<button class="leave-edit-btn1" onclick="enable_leavdays(<?=$brn->branch_id;?>);" type="button" data-typ-<?=$brn->branch_id;?>="annual_<?=$brn->branch_id;?>">Edit</button>-->
												<button class="btn btn-primary leave-save-btn" onclick="extra_update_leaves(<?=$brn->branch_id;?>,'<?=$extra_leaves['leave_type'];?>','<?=$extra_leaves['leave_type_id'];?>');" type="submit" id="extra_<?=$brn->branch_id;?>">Save</button>
											</div>
										</div>
										</div>
										
										</div>
										
										
											<!--<div class="leave-right">
												<button class="leave-edit-btn" type="button" data-typ="annual">Edit</button>
											</div>-->
										<?php
									}
								}
								?>
								
							</div>
							
							<!--<div class="input-box">
								<div class="form-group">
									<label>Days</label>
									<input type="text" class="form-control" id="paternity_leaves" value="<?php echo $extra_leaves['leave_days']; ?>" disabled>
								</div>
							</div>-->
							
							
							
						</div>
						<!--<div class="leave-right">
							<button class="leave-edit-btn" type="button" data-typ="paternity">Edit</button>
						</div>-->
					</div>
				</div>
			</div>


			<!-- Dynamic Leave TYpes -->
		<?php } } } ?>
			
		</div>
	</div>


<!-- Add Custom Policy Modal -->
<div id="add_custom_policy" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Custom Policy</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="policy_custom_form" method="post" action="<?php echo base_url(); ?>leave_settings/add_custom_policy">
					<div class="form-group">
						<label>Policy Name <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="policy_name" id="policy_name">
					</div>
					<div class="form-group">
						<label>Days <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="policy_days" id="policy_days">
					</div>
					<input type="hidden" name="policy_id" id="policy_id" value="">
					<div class="form-group leave-duallist">
						<label>Add employee</label>
						<div class="row">
							<div class="col-lg-5 col-sm-5 col-xs-12">
								<select name="customleave_from" id="customleave_select" class="form-control" size="5" multiple="multiple">
									<?php foreach($all_employees as $employee){ ?>
										<option value="<?php echo $employee['user_id']; ?>"><?php echo $employee['fullname']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="multiselect-controls col-lg-2 col-sm-2 col-xs-12">
								<button type="button" id="customleave_select_rightAll" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
								<button type="button" id="customleave_select_rightSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
								<button type="button" id="customleave_select_leftSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
								<button type="button" id="customleave_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>
							</div>
							<div class="col-lg-5 col-sm-5 col-xs-12">
								<select name="customleave_to" id="customleave_select_to" class="form-control" size="8" multiple="multiple"></select>
							</div>
						</div>
					</div>

					<div class="submit-section">
						<a class="btn btn-primary submit-btn PolicyBtn">Submit</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Add Custom Policy Modal -->
<?php $all_users = $this->db->get_where('custom_policy',array('leave_id'=>8))->result_array(); 
								foreach($all_users as $user){
									$users_all = $this->db->select('*')
													         ->from('assigned_policy_user AU')
													         ->join('account_details AD','AU.user_id = AD.user_id')
													         ->where('AU.policy_id',$user['policy_id'])
													         ->get()->result_array();
									foreach($users_all as $al_user)
									{
							?>
		<!-- Edit Custom Policy Modal -->
		<div id="edit_custom_policy<?php echo $user['policy_id'].$al_user['user_id']; ?>" class="modal custom-modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Custom Policy</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="post" action="<?php echo base_url(); ?>leave_settings/update_policy_user/<?php echo $al_user['assigned_id']; ?>">
							<div class="form-group">
								<label>Policy Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="policy_name" value="<?php echo $user['custom_policy_name']; ?>" readonly>
							</div>
							<div class="form-group">
								<label>Days <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="policy_days" value="<?php echo $user['policy_leave_days']; ?>">
							</div>
							<div class="form-group leave-duallist">
								<label>Add employee</label>
								<div class="row">
									<div class="col-lg-5 col-sm-5 col-xs-12">
										<select name="edit_customleave_from" id="edit_customleave_select<?php echo $al_user['assigned_id']; ?>" class="form-control EditSelectEmployee" size="5" multiple="multiple">
											<?php foreach($all_employees as $employee){ ?>
												<option value="<?php echo $employee['user_id']; ?>"><?php echo $employee['fullname']; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="multiselect-controls col-lg-2 col-sm-2 col-xs-12">
										<button type="button" id="edit_customleave_select<?php echo $al_user['assigned_id']; ?>_rightAll" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
										<button type="button" id="edit_customleave_select<?php echo $al_user['assigned_id']; ?>_rightSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
										<button type="button" id="edit_customleave_select<?php echo $al_user['assigned_id']; ?>_leftSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
										<button type="button" id="edit_customleave_select<?php echo $al_user['assigned_id']; ?>_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>
									</div>
									<div class="col-lg-5 col-sm-5 col-xs-12">
										<select name="customleave_to" id="edit_customleave_select<?php echo $al_user['assigned_id']; ?>_to" class="form-control EditSelectUsers" size="8" multiple="multiple">
											<option value="<?php echo $al_user['user_id']; ?>"><?php echo $al_user['fullname']; ?></option>
										</select>
									</div>
								</div>
							</div>

							<div class="submit-section">
								<a class="btn btn-primary submit-btn EditAssignee" data-id="<?php echo $al_user['assigned_id']; ?>">Submit</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="<?=base_url()?>assets/js/multiselect.min.js"></script>
		<script type="text/javascript">
			if($('#edit_customleave_select<?php echo $al_user['assigned_id']; ?>').length > 0) {
				$('#edit_customleave_select<?php echo $al_user['assigned_id']; ?>').multiselect();
			}
		</script>
		<!-- /Edit Custom Policy Modal -->

<!-- Delete Custom Policy Modal -->
<div class="modal custom-modal center-modal fade" id="delete_custom_policy" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3>Delete Custom Policy</h3>
					<p>Are you sure want to delete?</p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-primary continue-btn DeleteBtn"  id="delete_custom_policys">Delete</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
		<?php } } ?>
<!-- /Delete Custom Policy Modal -->

<!-- New Leave Type Model -->

<div id="new_leave_type" class="modal custom-modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">New Leave Type</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="newtype_leave_form" method="post" action="<?php echo base_url(); ?>leave_settings/add_new_leave_type">
				
					<div class="form-group">
						<label>Entity<span class="text-danger">*</span> </label>
						<select  class="select2-option" style="width:100%;" name="branch" id="add_branch" required>
							<option value="" selected disabled>Entity</option>
								<?php
									if(!empty($branches))	{
									foreach ($branches as $branch1){ ?>
									<option value="<?=$branch1->branch_id?>"><?=$branch1->branch_name?></option>
									<?php } ?>
									<?php } ?>
						</select>
						
					</div>
					<div class="form-group">
						<label>Leave Type Name <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="" name="leave_type_name" id="leave_type_name">
					</div>
					<div class="form-group">
						<label>Days <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="" name="leave_days" id="leave_days">
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<div id="delete_newpolicy" class="modal custom-modal center-modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Delete New leave Types</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="newtype_leave_form" method="post" action="<?php echo base_url(); ?>leave_settings/add_new_leave_type">
					<div class="form-group">
						<label>Leave Type Name <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="" name="leave_type_name" id="leave_type_name">
					</div>
					<div class="form-group">
						<label>Days <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="" name="leave_days" id="leave_days">
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script>
function enable_leavdays(branch_id)
{
	// alert(branch_id);
	// var type_form = $(this).data('typ-'+branch_id);//alert(type_form);
	// $(this).removeClass('leave-edit-btn1').addClass('btn btn-white leave-cancel-btn').text('Cancel');
	$(this).closest("#leave-right").append('<button class="btn btn-primary leave-save-btn" type="submit" id="">Save</button>');
	// $(this).parent().parent().find("input").prop('disabled', false);
	// return false;
}



function update_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#annual_leaves_'+branch_id).val();//alert(annual_leaves);return false;
	if(annual_leaves == '')
	{
		toastr.error('Annual Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_annual_leaves_bybranch/', {branch_id:branch_id,annual_leaves: annual_leaves}, function (datas) {
			// console.log(datas); return false;
		   $('#annual_leaves_'+branch_id).val(datas);
		   toastr.success('Annual Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}


function cf_update_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#carry_max_'+branch_id).val();//alert(annual_leaves);return false;
	if(annual_leaves == '')
	{
		toastr.error('Carry Forward Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_carry_forward_leave_bybranch/', {branch_id:branch_id,carry_max: annual_leaves}, function (datas) {
			// console.log(datas); return false;
		   $('#carry_max_'+branch_id).val(datas);
		   toastr.success('Carry Forward Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}

function sick_update_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#sick_leave_'+branch_id).val();//alert(annual_leaves);return false;
	if(annual_leaves == '')
	{
		toastr.error('Sick Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_sick_leave_bybranch/', {branch_id:branch_id,sick_leave: annual_leaves}, function (datas) {
			// console.log(datas); return false;
		   $('#sick_leave_'+branch_id).val(datas);
		   toastr.success('Sick Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}
function hospitalisation_update_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#hospitalisation_'+branch_id).val();//alert(annual_leaves);return false;
	if(annual_leaves == '')
	{
		toastr.error('Hospitalisation Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_hospitalisation_leave_bybranch/', {branch_id:branch_id,hospitalisation: annual_leaves}, function (datas) {
			// console.log(datas); return false;
		   $('#hospitalisation_'+branch_id).val(datas);
		   toastr.success('Hospitalisation Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}

function maternity_update_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#maternity_leaves_'+branch_id).val();//alert(annual_leaves);return false;
	if(annual_leaves == '')
	{
		toastr.error('Maternity Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_maternity_leave_bybranch/', {branch_id:branch_id,maternity: annual_leaves}, function (datas) {
			// console.log(datas); return false;
		   $('#maternity_leaves_'+branch_id).val(datas);
		   toastr.success('Maternity Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}


function paternity_update_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#paternity_leaves_'+branch_id).val();//alert(annual_leaves);return false;
	if(annual_leaves == '')
	{
		toastr.error('Paternity Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_paternity_leave_bybranch/', {branch_id:branch_id,paternity: annual_leaves}, function (datas) {
			// console.log(datas); return false;
		   $('#paternity_leaves_'+branch_id).val(datas);
		   toastr.success('Paternity Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}
function update_leave(a){
	$('#'+a.id).val(a.value)
}

function extra_update_leaves(branch_id,leave_type,leave_type_id)
{
	// alert(leave_type);return false;
	var base_url ='<?php echo base_url(); ?>';
	var annual_leaves = $('#extra_leaves_'+branch_id).val();//alert(branch_id);return false;
	if(annual_leaves == '')
	{
		toastr.error('Extra Leaves Field is Required');
		return false;
	}else{
		$.post(base_url + 'leave_settings/update_extra_leaves_bybranch/', {branch_id:branch_id,extra: annual_leaves,leave_type:leave_type,leave_type_id:leave_type_id}, function (datas) {
			// console.log(datas); return false;
		   $('#extra_leaves_'+branch_id).val(datas);
		   toastr.success('Extra Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}


function deletotherleaves(leave_type_id)
{
	 // var leave_id = $(this).data('id');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete LeaveType?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    console.log(leave_type_id);
                    $.post(base_url + 'leave_settings/delete_newleave_types_branchwise/', {leave_type_id:leave_type_id}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
}
function accrual_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var leave_day = $('#accrual_leaves_day_'+branch_id).val();
	var leave_day_month = $('#accrual_leaves_day_month_'+branch_id).val();
	if(leave_day == '')
	{
		toastr.error('Total Annual Days Field is Required');
		return false;
	}
	else if(leave_day_month == '')
	{
		toastr.error('Number of Days Per Month Field is Required');
		return false;
	}
	else if(leave_day_month >2.5)
	{
		toastr.error("Can't Add More than 2.5 days on Number of Days Per Month Field");
		return false;
	}
	else{
		$.post(base_url + 'leave_settings/update_accrual_leave/', {branch_id:branch_id,leave_day: leave_day,leave_day_month:leave_day_month}, function (datas) {
			// console.log(datas); return false;
		   toastr.success('Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}

function leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var leave_day = $('#leaves_day_'+branch_id).val();
	var leave_day_month = $('#leaves_day_month_'+branch_id).val();
	if(leave_day == '')
	{
		toastr.error('Total Annual Days Field is Required');
		return false;
	}
	else if(leave_day_month == '')
	{
		toastr.error('Number of Days Per Month Field is Required');
		return false;
	}
	else if(leave_day_month >2.5)
	{
		toastr.error("Can't Add More than 2.5 days on Number of Days Per Month Field");
		return false;
	}
	else{
		$.post(base_url + 'leave_settings/update_leave/', {branch_id:branch_id,leave_day: leave_day,leave_day_month:leave_day_month}, function (datas) {
			// console.log(datas); return false;
		   toastr.success('Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}
function privilege_leaves(branch_id)
{
	var base_url ='<?php echo base_url(); ?>';
	var leave_day = $('#privilege_leaves_day_'+branch_id).val();
	var leave_day_month = $('#privilege_leaves_day_month_'+branch_id).val();
	if(leave_day == '')
	{
		toastr.error('Total Annual Days Field is Required');
		return false;
	}
	else if(leave_day_month == '')
	{
		toastr.error('Number of Days Per Month Field is Required');
		return false;
	}
	else if(leave_day_month >2.5)
	{
		toastr.error("Can't Add More than 2.5 days on Number of Days Per Month Field");
		return false;
	}
	else{
		$.post(base_url + 'leave_settings/update_privilege_leave/', {branch_id:branch_id,leave_day: leave_day,leave_day_month:leave_day_month}, function (datas) {
			// console.log(datas); return false;
		   toastr.success('Leaves Updated');
		   setTimeout(function () {
				location.reload();
			}, 1500);
		});
	}
}

</script>
<style>
	.l-days{
		white-space: nowrap;
	}
</style>