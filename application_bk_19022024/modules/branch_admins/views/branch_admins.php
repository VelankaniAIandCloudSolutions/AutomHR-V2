<div class="content">
	<div class="row">
		<div class="col-sm-8 col-xs-4">
			<h4 class="page-title"><?php echo lang('branch_admins'); ?></h4>
		</div>
		<div class="col-sm-4 col-xs-8 text-right m-b-20">
			<a href="javascript:void(0)" class="btn add-btn" data-toggle="modal" data-target="#add_new_user"><i class="fa fa-plus"></i> <?php echo lang('add_branch_admin') ;?></a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
		   <div class="table-responsive">
				<table id="table-branches-admin" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo lang('name'); ?></th>
							<th><?php echo lang('email'); ?></th>							
							<th><?php echo lang('mobile_phone'); ?></th>							
							<th><?php echo lang('branch_name'); ?></th>							
							<!-- <th><?php echo lang('action'); ?></th>							 -->
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($all_branch_admins)){
							$row_no = 1;
							foreach($all_branch_admins as $branch_admin){

								$branch_details = $this->db->get_where('branches',array('branch_id'=>$branch_admin['branch_id']))->row_array();
						 ?>
						<tr role="row" class="odd text-center" >
							<td><?php echo $row_no; ?></td>
							<td class="div-cell user-cell sorting_1">
								<div class="user_det_list">
									<a href="<?php echo base_url(); ?>employees/profile_view/">
										<img class="avatar" src="<?php echo base_url(); ?>assets/avatar/default_avatar.jpg">
									</a>
									<h2>
										<a href="<?php echo base_url(); ?>employees/profile_view/311">
											<span class="username-info"><?php echo $branch_admin['fullname']; ?></span>
										</a> 
										<span class="userrole-info">Entity Admin</span>
									</h2>
								</div>
							</td>
							<td class="div-cell user-mail-info">
								<p><?php echo $branch_admin['email']; ?></p>
							</td>
							<td class="div-cell number-info">
								<p><?php echo $branch_admin['phone']; ?></p>
							</td>
							<td class="div-cell number-info">
								<p><?php echo $branch_details['branch_name']?ucfirst($branch_details['branch_name']):'-'; ?></p>
							</td>
							<!-- <td class="div-cell user-action-info">
								<div class="text-right">
									<div class="dropdown">
										<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="http://localhost/touya_hrms/employees/profile_view/311" title="Employee"><i class="fa fa-pencil m-r-5"></i>Edit</a>
											</li>
											<li>
												<a href="http://localhost/touya_hrms/employees/reset_password/311" data-toggle="ajaxModal"><i class="fa fa-unlock-alt m-r-5"></i>Reset Password</a>
											</li>
											<li>
												<a href="http://localhost/touya_hrms/employees/delete/311" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>
											</li>
											<li>
												<a href="http://localhost/touya_hrms/employees/change_inactive/311"><i class="fa fa-eye-slash m-r-5"></i>InActive</a>
											</li>
										</ul>
									</div>
								</div>
							</td> -->
						</tr>

					<?php $row_no++; } }else{ ?>
						<tr>
							<td colspan="4" class="text-center">No Entity Admins found</td>
						</tr>
					<?php } ?>
					</tbody>
			   </table>    
			</div>
		</div>
	</div>
</div>



<div id="add_new_user" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Entity Admin</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $attributes = array('id' => 'branchAdminAddForm'); echo form_open(base_url().'auth/register_branch_admin',$attributes); ?>

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


						<div class="col-sm-6">

							<div class="form-group">

								<label><?=lang('branches')?> <span class="text-danger">*</span></label>
									
								<select class="select2-option" style="width:100%;" required name="branch_id" id="branch_id">
											<?php

											$branch_lists = $this->db->get_where('branches',array('branch_status !='=>'1'))->result_array();
											// echo $this->db->last_query(); exit;

											 if(!empty($branch_lists))	{ ?>
													<option value="" selected disabled>Branch </option>

											 <?php foreach ($branch_lists as $branch_list){ 
											$chk_branch = $this->db->get_where('dgt_account_details',array('branch_id'=>$branch_list['branch_id']))->result_array();

											$chk_admin = $this->db->select('*')->from('users U')->join('account_details AD','U.id=AD.user_id',LEFT)->where('U.role_id',4)->where('AD.branch_id',$branch_list['branch_id'])->get()->result_array();
											
											if(count($chk_branch)==0 || count($chk_admin) == 0){
											 ?>
												<option value="<?php echo $branch_list['branch_id']; ?>"><?php echo ucfirst($branch_list['branch_name']); ?></option>

											<?php }} ?>

												<?php }else{ ?>
														<option value="" selected disabled>No Entity</option>
												<?php } ?>
								</select>
							</div>
						</div>
						</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="add_btn_branch_admin">Submit</button>
					</div>
				</form>
					</div>
			</div>
		</div>
	</div>
</div>