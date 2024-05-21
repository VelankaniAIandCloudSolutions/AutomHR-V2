<div class="content">
	<div class="page-header">
		<div class="row align-items-center">
		<div class="col-sm-12">
			<h4 class="page-title">Employees Management</h4>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn add-btn" href="<?=base_url()?>shift_scheduling"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
	      </div>
	</div>
</div>
	<div class="card-box">+
		<ul class="nav nav-tabs nav-tabs-solid page-tabs">
		<li><a href="<?php echo base_url(); ?>shift_scheduling">Daily Schedule</a></li>
		<li><a href="<?php echo base_url(); ?>shift_scheduling/add_schedule"><?php echo lang('add_schedule');?></a></li>
	</div>
	<div class="card-box">
		<div class="row filter-row">
			<div class="col-sm-6 col-12 col-md-2">
				<div class="form-group form-focus">
					<label class="control-label">Employee</label>
					<input type="text" class="form-control floating">
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3">
				<div class="form-group form-focus select-focus" style="width:100%;">
					<label class="control-label">Department</label>
					<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;">
						<option value="" selected="selected">All Departments</option>
						<?php if(!empty($departments)){ ?>
						<?php foreach ($departments as $department) { ?>
						<option value="<?php echo $department->deptid; ?>"><?php echo $department->deptname; ?></option>
						<?php  } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-2">
				<div class="form-group form-focus">
					<label class="control-label">Date</label>
					<input type="text" class="form-control floating" id="reportrange">
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-2">
				<div class="form-group form-focus select-focus" style="width:100%;">
					<label class="control-label">View by week or month</label>
					<select class="select floating form-control" style="padding: 14px 9px 0px;">
						<option value="" selected="selected">Week</option>
						<option value="">Month</option>
					</select>
				</div>
			</div>
			<div class="col-sm-6 col-6 col-md-3">  
				<a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee btn-circle"> Search </a>  
			</div>
		</div>
	</div>
	<div class="card-box">
		<div class="page-header">
		<div class="row align-items-center">
			<div class="col-sm-5 col-5">
				<h4 class="page-title">View Schedule</h4>
			</div>
			<div class="col-sm-7 col-7 text-right m-b-30">
				<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule" class="btn add-btn"> Add Schedule</a>
			</div>
		</div>
	</div>
		<!-- /Page Title -->
		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-bordered custom-table m-b-0" id="policies_table">
					<thead>
						<tr>
							<th><b>Scheduled Shift</b></th>
							<th><b>Mon  </b></th>
							<th><b>Tue</b></th>
							<th><b>Wed </b></th>
							<th><b>Thu </b></th>
							<th><b>Fri </b></th>
							<th><b>Sat </b></th>
							<th><b>Sun </b></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="user_det_list">
									<a href="http://localhost/ramiro_hrms/employees/profile_view/315">
									<img class="avatar" src="http://localhost/ramiro_hrms/assets/avatar/default_avatar.jpg">
									</a>
									<h2>
										<a href="http://localhost/ramiro_hrms/employees/profile_view/315">
										<span class="username-info">Guru</span>
										</a> <span class="userrole-info">8 Hrs</span>
									</h2>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
							<td>
								<div class="user-add-shedule-list">
									<a href="<?php echo base_url(); ?>shift_scheduling/add_schedule">
									<span><i class="fa fa-plus"></i></span>
									</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="add_new_user" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Add Employee</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<?php $attributes = array('id' => 'employeeAddForm'); echo form_open(base_url().'auth/register_user',$attributes); ?>
					<p class="text-danger"><?php echo $this->session->flashdata('form_errors'); ?></p>
					<input type="hidden" name="r_url" value="<?=base_url()?>employees">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('full_name')?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" value="<?=set_value('fullname')?>" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_name')?>" name="fullname" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('username')?> <span class="text-danger">*</span> <span id="already_username" style="display: none;color:red;">Already Registered Username</span></label>
								<input type="text" name="username" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_username')?>" id="check_username" value="<?=set_value('username')?>" class="form-control" autocomplete="off">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Gender</label><span class="text-danger">*</span>
								<select class="select2-option form-control" name="gender" style="width:100%;">
									<option value="" selected disabled>Gender</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('email')?> <span class="text-danger">*</span> <span id="already_email" style="display: none;color:red;">Already Registered Email</span></label>
								<input type="email" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_email')?>" name="email" id="checkuser_email" value="<?=set_value('email')?>" class="form-control" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('password')?> <span class="text-danger">*</span></label>
								<input type="password" placeholder="<?=lang('password')?>" value="<?=set_value('password')?>" name="password" id="password" class="form-control" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
								<input type="password" placeholder="<?=lang('confirm_password')?>" value="<?=set_value('confirm_password')?>" name="confirm_password"  class="form-control" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('phone')?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control telephone" value="<?=set_value('phone')?>" id="add_employee_phone" name="phone" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_phone')?>" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Address</label>  <a href="javascript:void(0)" class="office_address">Head Office</a>
								<input type="text" class="form-control" name="address" id="address" value="<?php echo $employee_details['address'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="city" id="city" value="<?php echo $employee_details['city'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>State/Province</label>
								<input type="text" class="form-control" name="state" id="state" value="<?php echo $employee_details['state'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Postal or Zip Code</label>
								<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $employee_details['pincode'];?>">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Start Date<span class="text-danger">*</span></label>
								<input class="form-control" readonly size="16" type="text" value="" name="emp_doj" id="emp_doj" data-date-format="yyyy-mm-dd" >
							</div>
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
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('department')?> <span class="text-danger">*</span></label>
								<input type="hidden" name="role" value="3">	
								<select class="select2-option" style="width:100%;" name="department_name" id="department_name">
									<option value="" selected disabled>Department</option>
									<?php
										if(!empty($departments))	{
										foreach ($departments as $department){ ?>
									<option value="<?=$department->deptid?>"><?=$department->deptname?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Position <span class="text-danger">*</span></label>
								<select class="form-control" style="width:100%;" name="designations" id="designations">
									<option value="" selected disabled>Position</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Reporting to </label>
								<select class="form-control" style="width:100%;" name="reporting_to" id="reporting_to">
									<option value="" disabled="disabled" selected="">Reporter's Name</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('user_type')?> <span class="text-danger">*</span></label>
								<select class="select2-option" style="width:100%;" name="user_type" id="user_type">
									<option value="" selected disabled>User Type</option>
									<?php
										$user_type = $this->db->order_by('role','asc')->get('roles')->result();
										if(!empty($user_type))	{
										foreach ($user_type as $type){ ?>
									<option value="<?=$type->r_id?>"><?=$type->role?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="register_btn">Submit</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	function cb(start, end) {
	  $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	}
	cb(moment().subtract(29, 'days'), moment());
	
	$('#reportrange').daterangepicker({
	  ranges: {
	    'Today': [moment(), moment()],
	    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	    'This Month': [moment().startOf('month'), moment().endOf('month')],
	    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	  }
	}, cb);
	
	//$('.ranges li').addClass('btn').css( "width", "100%" );;
	
</script>