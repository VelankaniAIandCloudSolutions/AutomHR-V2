				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-5">
							<h4 class="page-title">Promotion</h4>
						</div>
						<div class="col-sm-7 col-7 text-right m-b-30">
							<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_promotion" ><i class="fa fa-plus"></i> Add Daily Task</a>
						</div>
					</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped custom-table mb-0 datatable" id="dailytasks_table">
									<thead>
										<tr>
											<th style="width: 30px;">#</th>
											<th>Employee Name</th>
											<th>Entity Name</th>
											<th>Task Name</th>
											<th>Task status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($daily_tasks)){
											$i = 1;
											foreach($daily_tasks as $task){ 
												$branch_details = $this->db->get_where('branches',array('branch_id'=>$task['branch_id']))->row_array();
												$user_details = $this->db->get_where('account_details',array('user_id'=>$task['user_id']))->row_array();
												?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><?php echo ucfirst($user_details['fullname']); ?></td>
													<td><?php if($branch_details['branch_id'] != '0'){ echo ucfirst($branch_details['branch_name']); }else{ echo '-'; } ?></td>
													<td><?php echo ucfirst($task['dailytask_name']); ?></td>
													<td><?php if($task['task_status'] == 0){ ?> <span class="label label-success">Active</span> <?php  }else{ ?> <span class="label label-danger">InActive</span><?php } ?></td>
													<!-- <td>Edit</td> -->
													<td>
														<a  href="<?=base_url()?>daily_task/edit_dailytasks/<?php echo $task['dailytask_id']; ?>" data-toggle="ajaxModal" class="btn btn-success" data-toggle="tooltip" title="<?=lang('edit_dailytask')?>"><i class="fa fa-pencil"></i></a>
														<?php if($this->session->userdata('role_id') != 3){ ?>
															<a  href="<?=base_url()?>daily_task/delete_dailytask/<?php echo $task['dailytask_id']; ?>" data-toggle="ajaxModal" class="btn btn-danger" data-toggle="tooltip" title="<?=lang('delete_dailytask')?>" ><i class="fa fa-times"></i></a>	
														<?php } ?>
													</td>
												</tr>
											<?php $i++; }
										}else{ ?>
											<tr>
												<td>No Result found</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
				<!-- /Page Content -->
				
				<!-- Add Promotion Modal -->
				<div id="add_promotion" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Daily Task</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="<?php echo base_url(); ?>daily_task/add_dailytasks"  id="add_dailytasks" method="post">
									<?php if($this->session->userdata('role_id') == 1){ ?>
									<div class="form-group">
										<label>1Branch name<span class="text-danger">*</span></label>
										<select class="select2-option" style="width:100%;" name="branch_id" id="dailybranch_id" > 
											<option value="" selected disabled>Choose Entity</option>
											<option value="0">All Entities</option>
											<?php 
												$all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
												if(!empty($all_branches)){
													foreach($all_branches as $branch){

											?>
												<option value="<?php echo $branch['branch_id'];?>"><?php echo $branch['branch_name']; ?></option>
											<?php } }else{ ?>
												<option value="" disabled>No Branches</option>
											<?php } ?>
									   </select>
									</div>
								<?php }else{ ?>
									<input type="hidden" name="branch_id" value="<?php echo $this->session->userdata('branch_id'); ?>">
								<?php } ?>
								<?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4)){ ?>
									<div class="form-group">
										<label>Employee Name<span class="text-danger">*</span></label>
										<select class="select2-option" style="width:100%;" name="user_id" id="dailyemployee_id" > 
											<option value="" selected disabled >Choose Employee</option>
										<?php foreach (User::employee() as $key => $user) { 
											$emp_details = User::profile_info($user->id); 
											if($emp_details->branch_id == $this->session->userdata('branch_id')){
											?>
										<option value="<?php echo $user->id;?>"><?=ucfirst(User::displayName($user->id))?></option>
										<?php } }  ?>
									   </select>
									</div>
								<?php }else{ ?>
									<div class="form-group">
										<label>Employee Name<span class="text-danger">*</span></label>
										<select class="select2-option" style="width:100%;" name="user_id" id="dailyemployee_id" > 
											<option value="" disabled >Choose Employee</option>
											<option value="<?php echo $this->session->userdata('user_id'); ?>" selected ><?=ucfirst(User::displayName($this->session->userdata('user_id')))?></option>
									   </select>
									</div>
								<?php } ?>
									<div class="form-group">
										<label>Task Name<span class="text-danger">*</span></label>
											<input type="text" id="dailytask_name" name="dailytask_name" class="form-control">
									</div>
									<div class="form-group">
										<label>Task Status<span class="text-danger">*</span></label>
										<select class="select2-option" id="task_status" style="width:100%;" name="task_status" >
											<option value="" disabled selected>--Status--</option>
											<option value="0">Active</option>
											<option value="1">InActive</option>
										</select>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="dailytask_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Promotion Modal -->
				
				
				
				<!-- Delete Promotion Modal -->
				<div class="modal custom-modal fade" id="delete_promotion" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Delete Promotion</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" id="delete_promotions" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Promotion Modal -->