<div class="content">
	<div class="row">
		<div class="col-xs-12 message_notifcation" ></div>
		<div class="col-xs-4">
			<h4 class="page-title">Employees</h4>
		</div>
		<div class="col-sm-8 col-9 text-right m-b-20">
			<a href="javascript:void(0)" class="btn add-btn" data-toggle="modal" data-target="#add_new_user"><i class="fa fa-plus"></i> Add Employee</a>
			<?php  if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
				<a href="<?php echo  base_url('employees/transerEntity');?>" class="btn add-btn" style="margin-right:10px;"><?=lang('movement_from')?></a>
			<?php }?>
			<div class="view-icons">
				<a href="<?php echo base_url().'employees/grid_employees'?>" onclick="changeviews(this,'grid')" class="viewby grid-view btn btn-link"><i class="fa fa-th"></i></a>
				<a href="<?php echo base_url().'employees/employees'?>" onclick="changeviews(this,'list')" class="viewby list-view btn btn-link active"><i class="fa fa-bars"></i></a>
			</div>

		</div>

	</div>	
<form method="POST" id="filter_employee">
	<div class="row filter-row">

		<div class="col-sm-6 col-xs-12 col-md-2">  

			<div class="form-group form-focus">

				<label class="control-label">Employee ID</label>

				<input type="text" class="form-control floating" id="employee_id" name="employee_id" value="<?php if(!empty($_POST['employee_id'])){echo $_POST['employee_id'];}?>">
				<label id="employee_id_error" class="error display-none" for="employee_id">Employee Id must not empty</label>

			</div>

		</div>



		<div class="col-sm-6 col-xs-12 col-md-2">  

			<div class="form-group form-focus">

				<label class="control-label">Full Name</label>

				<input type="text" class="form-control floating" id="username" name="username" value="<?php if(!empty($_POST['username'])){echo $_POST['username'];}?>">
				<label id="employee_name_error" class="error display-none" for="username">Full Name must not empty</label>

			</div>

		</div>



		<div class="col-sm-6 col-xs-12 col-md-2">  

			<div class="form-group form-focus">

				<label class="control-label">Email</label>

				<input type="text" class="form-control floating" id="employee_email" name="employee_email" value="<?php if(!empty($_POST['employee_email'])){echo $_POST['employee_email'];}?>">
				<label id="employee_email_error" class="error display-none" for="employee_email">Email Field must not empty</label>
			</div>
		</div>
		<div class="col-sm-6 col-xs-12 col-md-3"> 

			<div class="form-group form-focus select-focus" style="width:100%;">

				<label class="control-label">Department</label>

				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 

					<option value="" selected="selected">All Departments</option>

					<?php if(!empty($departments)){ ?>

					<?php foreach ($departments as $department) { 
						if($department->branch_id == $this->session->userdata('branch_id')){
						?>

					<option value="<?php echo $department->deptid; ?>" <?php if(!empty($_POST['department_id']) && $_POST['department_id'] == $department->deptid){echo 'selected';}?>><?php echo $department->deptname; ?></option>

					<?php }  } ?>

					<?php } ?>

			</select>
		</div>
	</div>

	<div class="col-sm-6 col-xs-6 col-md-3">  
		<a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee form-control"> Search </a>  
	</div>  
</form>
</div>
<div class="row">
<div class="col-md-12">
<input type="hidden" value="<?=$this->session->userdata("role_id")?>" id="sess_role_id" >
<div id="employees_details" data-view="list" style="overflow-x:scroll">
<table id="table-employee" class="table table-striped custom-table m-b-0 dataTable no-footer">

	<thead>

	<tr>

		<th><?=lang('name')?></th>

		<th><?=lang('department')?></th>

		<th><?=lang('employee_id')?></th>

		<th><?=lang('email')?></th>

		<th><?=lang('mobile_phone')?> </th>

		<th><?=lang('join_date')?> </th>
		<?php if($this->session->userdata("role_id")==1){?>
		 <th><?=lang('branch')?> </th>
		<?php }?>
		<th> <?=lang('status');?></th>
		<th class="col-options no-sort text-right"><?=lang('action')?></th>

	</tr>

	</thead>

	<tbody id="admin_leave_tbl">
						</tbody>
	</table>

</div>
</div>
</div>

<?php /* ?>

	<div class="row">

		<div class="col-lg-12">

			<div class="table-responsive">

				<table id="table-employee" class="table table-striped custom-table AppendDataTables">

					<thead>

						<tr>

							<th><?=lang('full_name')?></th>

							<th><?=lang('company')?></th>

							<th><?=lang('employee_id')?></th>

							<th><?=lang('email')?></th>

							<th><?=lang('mobile_phone')?> </th>

							<th class="hidden-sm"><?=lang('date')?> </th>

							<!-- <th><?=lang('role')?> </th> -->

							<th class="col-options no-sort text-right"><?=lang('options')?></th>

						</tr>

					</thead>

					<tbody>

						<?php foreach (User::all_staffs_users() as $key => $user) {





							?>

							<tr>

								<?php $info = User::profile_info($user->id); ?>

				<!-- <td>

				<a class="pull-left" data-toggle="tooltip" data-title="<?=User::login_info($user->id)->email?>" data-placement="right">





	<img src="<?php echo User::avatar_url($user->id); ?>" class="img-circle" width="32">



	<span class="label label-<?=($user->banned == '1') ? 'danger': 'success'?>"><?=$user->username?></span>



	<?php if($user->role_id == '3') { ?>

	 <strong class=""><?=config_item('default_currency_symbol')?><?=User::profile_info($user->id)->hourly_rate;?>/<?=lang('hour')?></strong>

	 <?php }?>

				</a>

			</td> -->



			<td class="sorting_1">

				<a href="javascript:void(0)" class="avatar"><?=strtoupper(substr($info->fullname,0,1))?></a>

				<h2><a href="javascript:void(0)"><?=$info->fullname?> <span><!-- Web Developer --></span></a></h2>

			</td>

			<td class="">

				<?php if($info->company > 0){ ?>

				<a href="<?=base_url()?>companies/view/<?=$info->company?>" class="text-info">

					<?=($info->company > 0) ? Client::view_by_id($info->company)->company_name : 'N/A'; ?></a>

					<?php }else{ ?>

					<a href="javascript:void(0)">N/A</a>

					<?php }  ?>

				</td>

				<td class=""><?='FT-00'.$info->user_id?></td>

				<td class=""><?=$user->email?></td>

				<td class=""><?=$info->phone?></td>

				<td class="hidden-sm">

					<?=strftime(config_item('date_format'), strtotime($user->created));?>

				</td>



				<!-- <td>



	<?php if (User::get_role($user->id) == 'admin') {

			  $span_badge = 'label label-danger';

		  }elseif (User::get_role($user->id) == 'staff') {

			  $span_badge = 'label label-info';

		  }elseif (User::get_role($user->id) == 'client') {

			  $span_badge = 'label label-default';

		  }else{

			  $span_badge = '';

		}

	?>

				<span class="<?=$span_badge?>">

				<?=lang(User::get_role($user->id))?></span>

			</td> -->

			<td class="text-right">

				<div class="dropdown">

					<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>

					<ul class="dropdown-menu pull-right">

		<li><a href="<?=base_url()?>employees/update/<?=$user->id?>"  data-toggle="ajaxModal"><?=lang('edit')?></li>
		<li><a href="<?=base_url()?>employees/delete/<?=$user->id?>"  data-toggle="ajaxModal"><?=lang('delete')?></a></li>

						</ul>

					</div>

				</td>





						<!-- <td class="text-right">

						<a href="<?=base_url()?>users/account/auth/<?=$user->id?>" class="btn btn-info btn-xs" data-toggle="ajaxModal" title="<?=lang('user_edit_login')?>"><i class="fa fa-lock"></i>

						</a>

						<?php if($user->role_id == '3') { ?>

						<a href="<?=base_url()?>users/account/permissions/<?=$user->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="<?=lang('staff_permissions')?>"><i class="fa fa-shield"></i>

						</a>

						<?php } ?>



						<a href="<?=base_url()?>users/account/update/<?=$user->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal" title="<?=lang('edit')?>"><i class="fa fa-edit"></i>

						</a>

						<?php if ($user->id != User::get_id()) { ?>



						<a href="<?=base_url()?>users/account/ban/<?=$user->id?>" class="btn btn-warning btn-<?=($user->banned == '1') ? 'danger': 'default'?> btn-xs" data-toggle="ajaxModal" title="<?=lang('ban_user')?>"><i class="fa fa-times-circle-o"></i>

						</a>



						<a href="<?=base_url()?>users/account/delete/<?=$user->id?>" class="btn btn-primary btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>"><i class="fa fa-trash-o"></i>

						</a>

						<?php } ?>

					</td> -->

				</tr>

				<?php } ?>

			</tbody>

		</table>

	</div>

</div>

</div>
<?php */ ?>




<div id="add_new_user" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Employee</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $attributes = array('id' => 'employeeAddForm','autocomplete'=>"off"); echo form_open(base_url().'auth/register_user',$attributes); ?>

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
					</div>

					<div class="row">
					<div class="col-sm-6">

						<div class="form-group">

							<label><?=lang('email')?> <span class="text-danger">*</span> <span id="already_email" style="display: none;color:red;">Already Registered Email</span></label>

							<input type="email" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_email')?>" name="email" id="checkuser_email" value="<?=set_value('email')?>" class="form-control" autocomplete="off">
							

						</div>
						<!-- <span id="error_emailid" style="display: none;color:red;">Invalid Email-Id</span> -->
						

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
								<label>Address</label>
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

								<label><?=lang('department')?> <span class="text-danger">*</span>&nbsp;<a href="#" data-toggle="modal" data-target="#department_add" title="Add Departments" class="btn btn-info btn-xs pull-right" style="margin-left: 245px;"><i class="fa fa-plus"></i> Add</a></label>
								<input type="hidden" name="role" value="3">	
								<select class="select2-option" style="width:100%;" name="department_name" id="department_name">
										<option value="" selected disabled>Department</option>
											<?php

											

											 if(!empty($departments))	{

											 foreach ($departments as $department){ ?>

												<option value="<?=$department->deptid?>"><?=$department->deptname?></option>

												<?php } ?>

												<?php } ?>

											<!-- </optgroup> -->

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
					
					<div class="col-sm-6">

						<div class="form-group">

							<label>Is reporter <span class="text-danger">*</span></label>

						<select class="form-control" style="width:100%;" name="is_reporter" id="is_reporter">
							<option value="yes">Yes</option>
							<option value="no" selected>No</option>
							</select>

						</div>

					</div>
					<div class="col-sm-6">

								<div class="form-group">
									<label>Entity<span class="text-danger">*</span> </label>
									<?php
									//if($this->session->userdata('user_id') ==  1) { 
										$branches = $this->db->where('branch_status','0')->get('branches')->result();
									/*} else {
										$branches = $this->db->select('dgt_branches.*')
													->from('dgt_branches')
													->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')
													->where('dgt_branches.branch_status','0')
													->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))
													->get()->result();
									}*/
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
							<div class="col-sm-2">
								<div class="form-group">
									<label>Prefix </label>
									<input type="text" class="form-control" name="branch_prefix" id="branch_prefix" value="" readonly>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>Employee Id </label>
									<input readonly type="text" class="form-control" name="employee_id" id="emp_id" value="">
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


						
						
						<!--<div class="col-sm-6">
							<div class="form-group">
								<label>Dynamic Field </label>
								<?php
								$fields = $this->db->where('status',0)->get('dynamicfield')->result();
								?>
								<select multiple class="select2-option" style="width:100%;" name="dynamic_field" id="dynamic_field" required>
									<option value=""  disabled>Dyamic Field</option>
										<?php
										if(!empty($fields))	{
											foreach ($fields as $field1){ ?>
												<option value="<?=$field1->id?>"><?=$field1->field?></option>
											<?php } 
										} ?>
								</select><br><br>
								
								<button type="button" onclick="enabledyanmicfield();" class="btn btn-primary"> Enable</button>
							</div>
						</div>-->
						
						<div id="project_div" style="display:none;" class="col-sm-6">
							<div class="form-group">
								<label>Project </label>
								<?php
								$project = $this->db->where('branch_id',$this->session->userdata('branch_id'))->get('dgt_projects')->result();
								?>
								<select class="select2-option" style="width:100%;" name="project_id" id="project_id" >
									<option value=""  disabled>Project</option>
										<?php
										if(!empty($project))	{
											foreach ($project as $project1){ ?>
												<option value="<?=$project1->id?>"><?=$project1->project_code?> - <?=$project1->project_title?> </option>
											<?php } 
										} ?>
								</select>
								</div>
						</div>
						
						
						<div id="wiki_div" style="display:none;" class="col-sm-6">
							<div class="form-group">
								<label>Wiki </label>
								<?php
								$project = $this->db->where('branch_id',$this->session->userdata('branch_id'))->get('dgt_projects')->result();
								?>
								<select class="select2-option" style="width:100%;" name="project_id" id="project_id" >
									<option value=""  disabled>Project</option>
										<?php
										if(!empty($project))	{
											foreach ($project as $project1){ ?>
												<option value="<?=$project1->id?>"><?=$project1->project_code?> - <?=$project1->project_title?> </option>
											<?php } 
										} ?>
								</select>
								</div>
						</div>
						
						
						

						<div class="col-md-6">
							<div class="form-group">
								<label class=""><?=lang('type_change')?> <span class="text-danger">*</span></label>
								<div class="">
									<div class="m-b">
										<select class="select2-option form-control" style="width:100%" name="type_change" id="type_change" required >
											<option value="-">None</option>
											<option value="Internal Transfer">Internal Transfer</option>
											<option value="External Transfer">External Transfer</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class=""><?=lang('location')?> </label>
									<div class="">

									<select class="form-control" style="width:100%;" name="location" id="location">
										<option value="46 Ounces">46 Ounces</option>
										<option value="46 Ounces - Cafe Capriccio">46 Ounces - Cafe Capriccio</option>
										<option value="Oterra">Oterra</option>
										<option value="Otium Bangalore">Otium Bangalore</option>
										<option value="Otium Goa">Otium Goa</option>
										<option value="Velankani AI & Cloud Solutions LLP">Velankani AI & Cloud Solutions LLP</option>
										<option value="VEPL">VEPL</option>
										<option value="VISL">VISL</option>
									</select>									
								</div>
							</div>   
						</div>
<?php /*
						<div class="col-sm-6">
							<div class="form-group">
								<label>Reporting To2 </label>
								<input type="text" class="form-control" name="reporting_to2" id="reporting_to2" >
							</div>
						</div>
						*/ ?><input type="hidden" class="form-control" name="reporting_to2" id="reporting_to2" >
						<div class="col-sm-6">

							<div class="form-group">
								<label>Personal Email </label>
								<input type="email" class="form-control" name="personal_email" id="personal_email" value="">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Group Joining Date </label>
								<input class="form-control datepicker1" readonly size="16" type="text" value="" id="emp_gjd" data-date-format="yyyy-mm-dd"  >
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Date Of Birth </label>
								<input class="form-control datepicker1" readonly size="16" type="date"  name="dob" id="dob" data-date-format="yyyy-mm-dd" value="" >
							</div>

						</div>
						<div class="col-sm-6">
										
							<div class="form-group">
								<?php
								$this->db->select('grade_id,grade_name');
								$this->db->from('dgt_grades');
								if(!empty($this->session->userdata('branch_id'))){
									$this->db->where('branch_id', $this->session->userdata('branch_id'));
								}
								$this->db->order_by("grade_name", "asc");
								$records = $this->db->get()->result_array();
								?>
								<label>Employee Level </label>
								<select class="form-control" style="width:100%;" name="employee_level" id="employee_level" >
									<option value="">Select</option>
									<option value="JB 1">JB 1</option>
									<option value="JB 2">JB 2</option>
									<option value="JB 3">JB 3</option>
									<option value="JB 4">JB 4</option>
									<option value="JB 5">JB 5</option>
									<option value="JB 6">JB 6</option>
									<option value="JB 7">JB 7</option>
									<option value="JB 8">JB 8</option>
									<option value="JB 9">JB 9</option>

									<option value="L1">L1</option>
									<option value="L2">L2</option>
									<option value="L3">L3</option>
									<option value="L4">L4</option>
									<option value="L5">L5</option>
									<option value="L6">L6</option>
									<option value="L7">L7</option>
									<option value="L8">L8</option>
									<?php 
									/*if(!empty($records)){
										foreach($records as $record1){
									?>
											<option value="<?php echo $record1['grade_id'];?>" ><?php echo $record1['grade_name'];?></option>
									<?php
										}
									}*/
									?>
								</select>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Seat Location </label>
								<input type="text" class="form-control" name="seat_location" id="seat_location">
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Division </label>
								<select class="form-control" style="width:100%;" name="division" id="division">
									<option value="Tower Building">Tower Building</option>
								</select>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Business Unit </label>
								<select class="form-control" style="width:100%;" name="business_unit" id="business_unit">
									<option value="Manufacturing">Manufacturing</option>
									<option value="Precast">Precast</option>
									<option value="Tower Building">Tower Building</option>
								</select>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label>Cost Center </label>
								<input type="text" class="form-control" name="cost_center" id="cost_center" value="">
							</div>

						</div>
						<?php 

						$user_type = $this->db->order_by('role','asc')->where('role','employee')->get('roles')->result();
						?>
						<input type="hidden" value="<?php echo $user_type[0]->r_id;?>" name="user_type">
						<?php 
						
						//if($this->session->userdata('branch_id')==0){?>
						
						<div class="col-sm-6">

							<div class="form-group">

								<label>Organization Role <span class="text-danger">*</span></label>
									
								<select onchange="setentitymul();" class="select2-option" style="width:100%;" name="user_type" id="user_type">
									<option value="" selected disabled>Organization Role</option>
									<?php
									/*if($this->session->userdata('user_type_name') ==  'company_admin') { 
										$user_type = $this->db->order_by('role','asc')->where('role','employee')->get('roles')->result();
									} else {*/
										$user_type = $this->db->order_by('role','asc')->where('branch_id',$this->session->userdata('branch_id'))->get('roles')->result();
								//	}
									

									 if(!empty($user_type))	{

									 foreach ($user_type as $type){
										 if($type->role!='admin' && $type->role!='company_admin'){
										 ?>

										<option value="<?=$type->r_id?>"><?=$type->role?></option>

										 <?php }} ?>

										<?php } ?>
								</select>
							</div>

						</div>
						
							<!-- <div class="col-sm-6">

								<div class="form-group">
									<label>Entity<span class="text-danger">*</span> </label>
									<?php
									//if($this->session->userdata('user_id') ==  1) { 
										$branches = $this->db->where('branch_status','0')->get('branches')->result();
									/*} else {
										$branches = $this->db->select('dgt_branches.*')
													->from('dgt_branches')
													->join('dgt_assigned_entities','dgt_branches.branch_id = dgt_assigned_entities.branch_id')
													->where('dgt_branches.branch_status','0')
													->where('dgt_assigned_entities.user_id',$this->session->userdata('user_id'))
													->get()->result();
									}*/
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
								

							</div> -->
							<!-- <div class="col-sm-2">
								<div class="form-group">
									<label>Prefix </label>
									<input type="text" class="form-control" name="branch_prefix" id="branch_prefix" value="" readonly>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>Employee Id </label>
									<input readonly type="text" class="form-control" name="employee_id" id="emp_id" value="">
								</div>
							</div> -->
						<?php// }?>

						<!--<div class="col-sm-6">

								<div class="form-group">
									<label>Dynamic Field </label>
									<?php
									$fields = $this->db->get('dynamicfield')->result();
									?>
									<select class="select2-option" style="width:100%;" name="dynamic_field" id="dynamic_field" required>
										<option value="" selected disabled>Dyamic Field</option>
											<?php
											if(!empty($fields))	{
												foreach ($fields as $field1){ ?>
													<option value="<?=$field1->id?>"><?=$field1->field?></option>
												<?php } 
											} ?>
									</select>
								</div>

							</div>-->




						</div>


					<div class="submit-section">
						<?php 
							$check_all_employees = $this->db->get_where('users',array('role_id'=>3))->num_rows();
							if($check_all_employees == 0){
								$res = "register_btn_first";
							}else{
								$res = "register_btn";
							}
						 ?>
						<button class="btn btn-primary submit-btn" id="<?php echo $res; ?>">Submit</button>

					</div>
				</form>
					</div>



			</div>

		</div>

	</div>

</div>

</div>


<div id="department_add" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Departments</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
							
						<div class="modal-body">												
							<form id="dept_add" method="post">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Department Name<span class="text-danger">*</span><span id="Already_departs" style="display: none; color:red;float: right;">Already Exists</span></label>
												<input type="text" class="form-control" name="department_name" id="department_addname" placeholder="Department Name" >
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Designation Name<span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="role_name" id="role_addname" placeholder="Role Name">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Grade <span class="text-danger">*</span></label>
												<select name="grade" id="addgrade" required="required" class="form-control" >
													<option value="" selected disabled>Grade</option>
													<?php
													$all_designations = $this -> db -> get('designation') -> num_rows();
													$grades = $this -> db -> get('grades') -> result();
													if (!empty($grades)) { 
														$e=1;
													foreach ($grades as $key => $d) { ?>
													<option value="<?php echo $d->grade_id; ?>" <?php if($all_designations == 0){ if($e == 1){ echo "selected"; } }  ?> ><?=$d->grade_name?></option>
													<?php $e++; } }else{ ?>
													<option value="">No Results</option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<a id="departAdds" class="btn btn-primary submit-btn">Submit</a>
									</div>
								</form>
						</div>
					
					</div>
				</div>
			</div>
			<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
		var employee_id = $('#employee_id').val();
    	var username = $('#username').val();
		var department_id = $('#department_id').val();
    	var employee_email = $('#employee_email').val();
		$('#table-employee').DataTable( {
		"columns": [
				{ data: "fullname" },
				{ data: "department" },
				{ data: "emp_code" },
				{ data: "email" },
				{ data: "phone" },
				{ data: "doj" },
				{ data: "entity_name" },
				{ data: "user_status" },
				{ data: "action" },
		],
		"bDestroy": true,
		"processing": true,
		"serverSide": true,
		"aLengthMenu": [
			//[10,25, 50, 100],
			//[10,25, 50, 100]
				[10,25, 50, 100, 200, -1],
				[10,25, 50, 100, 200, "All"]
		],
		"ajax": "<?php echo base_url().'employees/employees_list';?>?employee_id="+employee_id+"&username="+username+"&department_id="+department_id+"&employee_email="+employee_email
		} );
	} );
</script>
<script type="text/javascript">
	var office_address = "<?=config_item('company_address')?>";
	var office_city = "<?=config_item('company_city')?>";
	var office_state = "<?=config_item('company_state')?>";
	var office_zip_code = "<?=config_item('company_zip_code')?>";

function enabledyanmicfield()
{
	var dynamic_field = $('#dynamic_field').val();
	var dy=dynamic_field.toString().split(',');
// alert(dy);

	// return false;
	$.each(dynamic_field,function(i){
	   
		if(dynamic_field[i]==1)
		{
			//alert(dynamic_field[i]);
			$("#project_div").css('display','block');
		}
		// else
		// {
			// $("#project_div").css('display','none');
		// }
	});
	// return false;
	// var dynamic_field=$("#dynamic_field").val();alert(dynamic_field);
	
}


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

	// alert(user_type_text);
	// return false;
}

function entity_change(val) {
// $('#branch_prefix').val('');
					// $('#emp_id').val('');
	<?php /*var user_type=$("#user_type option:selected").text();
	if(user_type=='employee')
	{*/?>
		if(val != '') {
			$.ajax({
			type: "POST",
			url: base_url + 'employees/get_branch_prefix',
			data:  {id:val},
				success: function (datas) {
// alert(datas);					
					var response = JSON.parse(datas);
					
					$('#branch_prefix').val(response.branch_prefix);
					$('#emp_id').val(response.employee_id);
				}
			});
		}
		<?php /*}
	else
	{?>
		var add_branch_mul=$("#add_branch_mul").val();//alert(add_branch_mul);return false;
		$.ajax({
			type: "POST",
			url: base_url + 'employees/get_branch_prefix_companyadmin',
			data:  {id:add_branch_mul},
				success: function (datas) { 
					var response = JSON.parse(datas);
					$('#branch_prefix').val(response.branch_prefix);
					$('#emp_id').val(response.employee_id);
				}
			});
	<?php }*/?>
	$.post(base_url+'employees/teamlead_options/',{branch_id:val},function(res){
            // console.log(res); return false;
            var leads_name = JSON.parse(res);
            $('#reporting_to').empty();
            $('#reporting_to').append("<option value='' selected disabled='disabled'>Reporter's Name</option>");
            for(i=0; i<leads_name.length; i++) {
                $('#reporting_to').append("<option value="+leads_name[i].id+">"+leads_name[i].username+"</option>");                      
             }
            });
}
</script>
<style>
	th {
    	white-space: nowrap;
	}
	td {
	    white-space: nowrap;
	}
	tr {
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}
</style>