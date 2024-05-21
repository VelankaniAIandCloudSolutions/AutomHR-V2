
			
	<!-- Content -->
    <div class="content container">
		
		<div class="row">
			<div class="col-sm-8">
				<h4 class="page-title m-b-20">Onboarding Configuration</h4>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="card-box text-center">
					<?php /*<p class="m-b-20"><b>Departments or Individuals that need to be notified upon Candidates Acceptance</b></p>*/?>
					<form action="<?=base_url()?>offers/onboarding/<?= $offer_id;?>" method="POST" autocomplete="off" id="add_onboardding_from">
						<div class="col-sm-6 col-xs-12 col-md-6">  
								<div class="form-group form-focus">
									<label ><?=lang('full_name')?> <span class="text-danger">*</span></label>
									<input type="text" class="form-control floating" id="fullname" name="fullname" autocomplete="off" value="<?= $full_name;?>">
									<label id="employee_fullname_error" class="error display-none" for="fullname">Full Name must not empty</label>
								</div>
						</div>
						<div class="col-sm-6 col-xs-12 col-md-6">  
							<div class="form-group form-focus">
								<label><?=lang('username')?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control floating" id="username" name="username" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 col-md-6">  
							<div class="form-group form-focus">
								<label><?=lang('email')?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control floating" id="employee_email" name="employee_email" autocomplete="off">
								<label id="employee_email_error" class="error display-none" for="employee_email">Email Field must not empty</label>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 col-md-6">
							<div class="form-group">
								<label><?=lang('password')?> <span class="text-danger">*</span></label>
								<input type="password" placeholder="<?=lang('password')?>" value="<?=set_value('password')?>" name="password" id="password" class="form-control" autocomplete="off">
							</div>
						</div>
						<div style="width:100%;float:left">
							<div class="col-sm-6 col-xs-12 col-md-6">  
								<div class="form-group">
									<label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
									<input type="password" placeholder="<?=lang('confirm_password')?>" value="<?=set_value('confirm_password')?>" name="confirm_password"  class="form-control" autocomplete="off">
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 col-md-6">
								<div class="form-group">
									<label><?=lang('phone')?> <span class="text-danger">*</span></label>
									<input type="text" class="form-control telephone" value="<?=set_value('phone')?>" id="add_employee_phone" name="phone" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_phone')?>" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 col-md-6">  
							<div class="form-group">
								<label>Start Date<span class="text-danger">*</span></label>
								<input class="form-control" readonly size="16" type="text" value="<?= date('Y-m-d');?>" name="emp_doj" id="emp_doj" data-date-format="yyyy-mm-dd" >
							</div>
						</div>
							<?php 
							$departments = $this->db->order_by("deptname", "asc")->get('departments')->result();
							$mydefault = current($departments);
							$deptid   = (!empty($mydefault->deptid))?$mydefault->deptid:'-';
							$deptname = (!empty($mydefault->deptname))?$mydefault->deptname:lang('department_name');
							$records = array();
							if($deptid!='-'){
								$this->db->select('id,designation');
								$this->db->from('designation');
								$this->db->where('department_id', $deptid);
								$records = $this->db->get()->result_array();
							}
						 ?>
						<div class="col-sm-6 col-xs-12 col-md-6">
							<div class="form-group">
								<label><?=lang('department')?> <span class="text-danger">*</span>&nbsp;</label>
								<input type="hidden" name="role" value="3">	
								<select class="select2-option" style="width:100%;" name="department_name" id="department_name">
									<option value="" selected disabled>Department</option>
									<?php
									if(!empty($departments))	{
										foreach ($departments as $department){ ?>
											<option value="<?=$department->deptid?>" <?php if(!empty($jobs['department_id']) && $jobs['department_id'] == $department->deptid){ echo 'selected';}?>><?=$department->deptname?></option>
										<?php } ?>
									<?php } ?>
									<!-- </optgroup> -->
								</select>
							</div>
						</div>
						
						<div class="col-sm-6 col-xs-12 col-md-6">
							<div class="form-group">
								<label>Position <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;" name="designations" id="designations">
									<option value="" selected disabled>Position</option>
									<?php if(!empty(($designations))){
										foreach($designations as $designation_1){
									?>
											<option value="<?=$designation_1['id']?>" <?php if(!empty($jobs['position_id']) && $jobs['position_id'] == $designation_1['id']){ echo 'selected';}?>><?=$designation_1['designation']?></option>

									<?php
										}
									}
									?>
								</select>
							</div>
						</div>
							<div class="col-md-6 col-xs-12 col-md-6">
								<div class="form-group">
									<label class=""><?=lang('type_change')?> </label>
									<div class="">
										<div class="m-b">
											<select class="select2-option form-control" style="width:100%" name="type_change" id="type_change"  >
												<option value="-">None</option>
												<option value="Internal Transfer">Internal Transfer</option>
												<option value="External Transfer">External Transfer</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 col-md-6">
								<div class="form-group">
									<label>Organization Role <span class="text-danger">*</span></label>
									<select onchange="setentitymul();" class="select2-option" style="width:100%;" name="user_type" id="user_type">
										<option value="" selected disabled>Organization Role</option>
										<?php
										$user_type = $this->db->order_by('role','asc')->get('roles')->result();
										if(!empty($user_type))	{
											foreach ($user_type as $type){
												if($type->role!='admin' && $type->role!='company_admin'){
											?>
													<option value="<?=$type->r_id?>"><?=$type->role?></option>
												<?php }
											} ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 col-md-6">
								<div class="form-group">
									<label>Entity<span class="text-danger">*</span> </label>
									<?php
										$branches = $this->db->where('branch_status','0')->get('branches')->result();
									?>
									<div id="add_branch_div" >
										<select  class="select2-option" style="width:100%;" onchange="entity_change(this.value)" name="branch" id="add_branch">
											<option value="" selected disabled>Entity</option>
											<?php
											if(!empty($branches))	{
												foreach ($branches as $branch1){ ?>
													<option value="<?=$branch1->branch_id?>"><?=$branch1->branch_name?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 col-md-6">
								<div class="form-group">
									<label>Prefix </label>
									<input type="text" class="form-control" name="branch_prefix" id="branch_prefix" value="" readonly>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 col-md-6">
								<div class="form-group">
									<label>Employee Id </label>
									<input readonly type="text" class="form-control" name="employee_id" id="emp_id" value="">
								</div>
							</div>
						<?php /*<div class="form-group">
							<input type="hidden" class="form-control" value="<?php echo $offer_id;?>" name="offer_id">
							<select class="select2-option form-control" multiple="multiple" style="width:260px" name="boarders_id[]" > 
								<optgroup label="Staff">
									<?php foreach (User::team() as $user): ?>
										<option value="<?=$user->id?>">
											<?=ucfirst(User::displayName($user->id))?>
										</option>
									<?php endforeach ?>
								</optgroup> 
							</select>
							<br>
							<span class="help-block">Separated with a comma or enter</span>
						</div>*/?>
						<div class="row"></div>
						<div class="row">
							<div class="submit-section">
								<button class="btn btn-primary submit-btn" type="submit" id="onboard_save">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
	</div>
</div>
<style>
	label,.select2-selection__rendered{
		float:left !important;
	}
</style>
<script>
	function setentitymul()
	{
		$("#add_branch").val('').trigger('change')
		$("#add_branch_mul").val('').trigger('change')
		$('#branch_prefix').val('');
		$('#emp_id').val('');

		var user_type=$("#user_type").val();
		var user_type_text=$("#user_type option:selected").text();
		if(user_type_text=='company_admin')
		{
			$("#add_branch").removeAttr('required');
			$('#add_branch_mul').prop('required', true);
			$("#add_branch_div").hide();
			$("#add_branch_mul_div").show();
			// $("#add_branch").removeAttr('mulitple');
		}
		else
		{
			$("#add_branch_mul").removeAttr('required');
			$("#add_branch").prop('required', true);
			$("#add_branch_mul_div").hide();
			$("#add_branch_div").show();
			
		}
	}
	function entity_change(val) {
		if(val != '') {
			$.ajax({
			type: "POST",
			url: base_url + 'employees/get_branch_prefix',
			data:  {id:val},
				success: function (datas) {
					var response = JSON.parse(datas);
					$('#branch_prefix').val(response.branch_prefix);
					$('#emp_id').val(response.employee_id);
				}
			});
		}
}
</script>