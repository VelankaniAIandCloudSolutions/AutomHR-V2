<div class="content">  
	<div class="row">
		<div class="col-xs-4">
			<h4 class="page-title"><?='Pay Slip';?></h4>
		</div>
		<div class="col-xs-8 text-right m-b-30">
			<a class="btn btn-primary rounded pull-right" href="#" data-toggle="modal" data-target="#run_payroll">Run Payroll</a>
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
	<?php  
	if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { ?>
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
	   <?php 
	   if(!empty($this->session->userdata('branch_id'))){
			$users_list = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3  and ad.user_id!='' and ad.branch_id=".$this->session->userdata('branch_id')." order by u.created desc")->result_array();
	   }
	   else{
		   $users_list = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3 and ad.user_id!='' order by u.created desc")->result_array();
	   }
									
	   ?>
	   <table id="table-users" class="table table-striped custom-table m-b-0 ">
			<thead>
				<tr>
					<th> # </th> 
					<th> Fullname </th> 
					<th> Current Salary </th>
					<th> Designation </th>
					<th class="hidden-sm">Joining Date</th>
					<th class="text-right">Payroll Status</th>
					<th class="col-options no-sort text-right">Options</th>
				</tr>
			 </thead>
			 <tbody>
				<?php  /*foreach($users_list as $key => $usrs){  ?>
				<tr>
					<td><?=$key+1?></td>
					<td>
						<a class="pull-left avatar">
							<?php 
							if(config_item('use_gravatar') == 'TRUE' AND 
								Applib::get_table_field(Applib::$profile_table,
									array('user_id'=>$usrs['user_id']),'use_gravatar') == 'Y'){
												$user_email = Applib::login_info($usrs['user_id'])->email; 
							?>
							<img src="<?php $this->applib->get_gravatar($user_email)?>" class="img-circle">
							<?php }else{ 
							$img_file = return_img(base_url().'assets/avatar/'.Applib::profile_info($usrs['user_id'])->avatar);
							?>
							<img src="<?php echo $img_file;?>" class="img-circle">
							<?php } ?>
						</a>
						<h2>
							<a href="javascript:;"> 
								<?=$usrs['fullname']?>
							</a>
						</h2>
					</td> 
					
					<td>  
					<?php
						$salary = ''; 
						if(isset($usrs['salary_det'])&& $usrs['salary_det'] != ''){
							$exp = explode('[^]',$usrs['salary_det']);
							if($exp[0] != 0){ $salary = $exp[0]; }
						} 

						$user_details = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$usrs['user_id']))->row_array();
						?>
						<strong> <?php echo  $user_details['salary']?$user_details['salary']:'N/A'; ?> </strong> 
					</td>
					<td>
						<span class="label label-info"><? echo $usrs['designation'];?></span>
					</td>
					<td>
						<?=strftime(config_item('date_format'), strtotime($usrs['doj']));?>
					</td> 
					<td> 
						<span class="status-toggle pull-right">
							<input type="checkbox" value="1" class="check" onchange="user_status_change(<?php echo $usrs['user_id'];?>)" id="payroll_user_status<?php echo $usrs['user_id'];?>" <?php if($usrs['status']=='1') echo'checked' ;?>>
							<label class="checktoggle" for="payroll_user_status<?php echo $usrs['user_id'];?>">checkbox</label>
						</span>
					</td> 
					<td class="text-right">
						<!-- <a class="btn btn-success btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>payroll/edit_salary/<?=$usrs['user_id']?>" title="Edit Salary" data-original-title="Edit Salary">
							<i title="Edit Salary" class="fa fa-suitcase"></i>
						</a>
						<a class="btn btn-danger btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>payroll/create/<?=$usrs['user_id']?>" title="Create Pay Slip" data-original-title="Create Pay Slip">
							<i title="Create Pay Slip" class="fa fa-money"></i>
						</a>     -->

						<a class="btn btn-danger btn-xs"  href="<?=base_url()?>payroll/payslip/<?=$usrs['user_id']?>" title="View Pay Slip" >
							<i title="View Pay Slip" class="fa fa-money"></i>
					</td>
				</tr>
				<?php }*/ ?>  
			</tbody>
	   </table>   
		</div>
		</div> 
   </div>
   <?php } ?>
</div>

			<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
		var employee_id = $('#employee_id').val();
    	var username = $('#username').val();
		var department_id = $('#department_id').val();
    	var employee_email = $('#employee_email').val();
		$('#table-users').DataTable( {
		"columns": [
				{ data: "sl_no" },
				{ data: "fullname" },
				{ data: "current_sallary" },
				{ data: "designation" },
				{ data: "join_date" },
				{ data: "payroll_status" },
				{ data: "options" },
		],
		"bDestroy": true,
		"searching":false,
		"processing": true,
		"serverSide": true,
		"aLengthMenu": [
			//[10,25, 50, 100],
			//[10,25, 50, 100]
				[10,25, 50, 100],
				[10,25, 50, 100]
		],
		"ajax": "<?php echo base_url().'payroll/ajax_payroll';?>?employee_id="+employee_id+"&username="+username+"&department_id="+department_id+"&employee_email="+employee_email
		} );
	} );
</script>

<!-- Run Payroll Modal -->
<div class="modal custom-modal center-modal fade" id="run_payroll" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-head">
					<h3>Run Payroll</h3>
					<p>Are you sure want to run payroll?</p>

				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-xs-6">
							<a href="<?php echo base_url();?>payroll/run_payroll" class="btn btn-primary continue-btn">Yes</a>
						</div>
						<div class="col-xs-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">No</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Run Payroll Modal -->
<!-- /Run Payroll Modal -->