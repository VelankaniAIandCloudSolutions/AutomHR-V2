<!-- Page Content  -->
<div class="content container-fluid pb-0">
	<div class="page-header">
		<div class="row">
			<div class="col">
				<h4 class="page-title"><?=lang('employees')?></h4>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li class="breadcrumb-item active">Employee</li>
				</ul>
			</div>
			<div class="col-auto float-right ml-auto">
				<?php if(App::is_permit('all_employees','create')){?><a href="javascript:void(0)" class="btn add-btn" data-toggle="modal" data-target="#add_new_user"><i class="fa fa-plus"></i> Add Employee</a><?php }?>
				<div class="view-icons">
					<a href="<?php echo base_url().'employees/grid_employees'?>" onclick="changeviews(this,'grid')" class="viewby grid-view btn btn-link active"><i class="fa fa-th"></i></a>
					<a href="<?php echo base_url().'employees/employees'?>" onclick="changeviews(this,'list')" class="viewby list-view btn btn-link"><i class="fa fa-bars"></i></a>

				</div>
			</div>
		</div>
	</div>
	
		<form method="post" action="<?php echo base_url(); ?>employees/grid_employees" enctype="multipart/form-data">
			<div class="row filter-row">
				<div class="col-sm-6 col-md-2">  
					<div class="form-group form-focus">

						<label class="control-label">Employee ID</label>

						<input type="text" class="form-control floating" id="employee_id" name="employee_id" value="<?php echo(isset($_POST['employee_id']))?$_POST['employee_id']:'';?>">
						<label id="employee_id_error" class="error display-none" for="employee_id">Employee Id must not be empty</label>

					</div>

				</div>
				<div class="col-sm-6 col-md-2">  
					<div class="form-group form-focus">

						<label class="control-label">Full Name</label>

						<input type="text" class="form-control floating" id="username" name="username" value="<?php echo(isset($_POST['username']))?$_POST['username']:'';?>">
						<label id="employee_name_error" class="error display-none" for="username">Full Name must not be empty</label>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">  
					<div class="form-group form-focus">

						<label class="control-label">Email</label>

						<input type="text" class="form-control floating" id="employee_email" name="employee_email" value="<?php echo(isset($_POST['employee_email']))?$_POST['employee_email']:'';?>">
						<label id="employee_email_error" class="error display-none" for="employee_email">Email Field must not be empty</label>

					</div>

				</div>

				<div class="col-sm-6 col-md-3"> 
					<div class="form-group form-focus select-focus" style="width:100%;">

						<label class="control-label">Department</label>

						<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 

							<option value="" selected="selected">All Departments</option>

							<?php if(!empty($departments)){ ?>

							<?php foreach ($departments as $department) { ?>

							<option value="<?php echo $department->deptid; ?>" <?php echo(isset($_POST['department_id']) && $_POST['department_id'] == $department->deptid)?"selected":'';?>><?php echo $department->deptname; ?></option>

							<?php  } ?>

							<?php } ?>
						</select>
					</div>
				</div>
				
				<div class="col-sm-6 col-md-3">  
					<!-- <a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-primary btn-block btn-searchEmployee form-control"> Search </a>   -->
					<button type="submit" id="grid_employee_search_btn" class="btn btn-primary btn-block btn-searchEmployee btn-circle">Search</button>
				</div>  
			</div>
		</form>

					
					<div class="row staff-grid-row">
						<?php
						if (!empty($employees)) {
						foreach ($employees as $employee) { 
						// $client_due = Client::due_amount($client->co_id);
						?>
							<div class="col-md-4 col-sm-4 col-xs-6 col-lg-3 AllGridCompanies">
								<div class="profile-widget">
									<div class="profile-img">
										<a  href="<?php if(App::is_permit('all_employees','read')){?><?php echo base_url(); ?>employees/profile_view/<?php echo $employee->id; ?><?php }else{ echo '#';}?>" class="avatar"><img class="avatar" src="<?php echo base_url(); ?>assets/avatar/<?php echo $employee->avatar; ?>" alt=""></a>
										<span name="email_company" style="display: none;"><?php echo $employee->email; ?></span>
									</div>
									
										<?php if(App::is_permit('all_employees','write')==true || App::is_permit('all_employees','delete')==true)
										{
										?>
									<div class="dropdown profile-action">
										<a aria-expanded="false" data-toggle="dropdown" class="action-icon " href="#"><i class="fa fa-ellipsis-v"></i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<?php if(App::is_permit('all_employees','write')){?><a class="dropdown-item" href="<?php echo base_url(); ?>employees/profile_view/<?php echo $employee->id; ?>" title="Employee"><i class="fa fa-pencil m-r-5"></i> <?php echo lang('edit');?></a><?php }?>
											<?php if(App::is_permit('all_employees','delete')){?><a class="dropdown-item" href="<?php echo base_url(); ?>employees/delete/<?php echo $employee->id; ?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> <?php echo lang('delete');?></a><?php }?>
											
										</div>
									</div>
									<?php
										}
									?>
									<h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $employee->id; ?>"><?=($employee->fullname != NULL) ? $employee->fullname : ucfirst($employee->username); ?></a></h4>
									<h5 class="user-name mb-0 text-ellipsis"><a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $employee->id; ?>"><?php echo $employee->id_code; ?></a></h5>
									<!-- <h5 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $employee->id; ?>"><?php echo $employee->designation; ?></a></h5> -->
									 <div class="small text-secondary text-ellipsis"><a class="text-muted" href="<?php echo base_url(); ?>employees/profile_view/<?php echo $employee->id; ?>"><?php echo $employee->designation; ?></a></div> 
									<!-- <a href="<?=base_url()?>employees/profile_view/<?=$employee->id?>" class="btn btn-white btn-sm m-t-10">View Profile</a> -->
								</div>
							</div>
						<?php } } ?>
					</div>
                </div>
				<!-- /Page Content-->
				<div id="add_new_user" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog  modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo lang('add_employee');?></h4>
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
								<label><?=lang('gender')?></label><span class="text-danger">*</span>
								<select class="select2-option form-control" name="gender" style="width:100%;">
									<option value="" selected disabled><?=lang('gender')?></option>
									<option value="male"><?=lang('male')?></option>
									<option value="female"><?=lang('female')?></option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label><?=lang('id_code')?> <span class="text-danger">*</span> <span id="already_id_code" style="display: none;color:red;">Already Registered Id Code</span></label>
								<input type="text" name="id_code" placeholder="<?=lang('eg')?> 543219876" id="check_id_code" value="" class="form-control only-numeric" autocomplete="off">
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
								<label><?=lang('address')?></label>  <a href="javascript:void(0)" class="office_address"><?=lang('head_office')?></a>
								<input type="text" class="form-control" name="address" id="address" value="<?php echo $employee_details['address'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?=lang('city')?></label>
								<input type="text" class="form-control" name="city" id="city" value="<?php echo $employee_details['city'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?=lang('state_province')?></label>
								<input type="text" class="form-control" name="state" id="state" value="<?php echo $employee_details['state'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?=lang('postal_or_zip_code')?></label>
								<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $employee_details['pincode'];?>">
							</div>
						</div>					
						<div class="col-sm-6">  
							<div class="form-group">
								<label><?=lang('start_date')?><span class="text-danger">*</span></label>
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
										<option value="" selected disabled><?=lang('department')?></option>
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
									<label><?=lang('position')?> <span class="text-danger">*</span></label>
									<select class="form-control" style="width:100%;" name="designations" id="designations">
										<option value="" selected disabled><?=lang('position')?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label><?=lang('reporting_to')?> </label>
									<select class="form-control" style="width:100%;" name="reporting_to" id="reporting_to">
										<option value="" disabled="disabled" selected=""><?=lang('reporters_name')?></option>
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label><?=lang('user_type')?> <span class="text-danger">*</span></label>
									<select class="select2-option" style="width:100%;" name="user_type" id="user_type">
										<option value="" selected disabled><?=lang('user_type')?></option>
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
							<button class="btn btn-primary submit-btn" id="register_btn"><?=lang('submit')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
<script type="text/javascript">
	var office_address = "<?=config_item('company_address')?>";
	var office_city = "<?=config_item('company_city')?>";
	var office_state = "<?=config_item('company_state')?>";
	var office_zip_code = "<?=config_item('company_zip_code')?>";

</script>
