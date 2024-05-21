         
<script src="<?=base_url()?>assets/js/hrms_common.js"></script>
		 <!-- <div class="page-wrapper"> -->
            	<?php  //echo $this->session->userdata('search_employee'); ?>
                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Timing Sheet</h4>
						</div>
						<?php if(($this->session->userdata('role_id') != 1) && ($this->session->userdata('role_id') != 4)){ ?>
						<div class="col-sm-4 text-right m-b-30">
							<a href="#" class="btn btn-primary rounded" data-toggle="modal" data-target="#add_todaywork"><i class="fa fa-plus"></i> Add Today Work</a>
						</div>
					<?php } ?>
					</div>
					
					<div class="row filter-row">
							<div class="col-md-12 padding-2p search_date">
						<form id="timesheet_search" method="post" action="<?php echo base_url().'time_sheets/'; ?>">
									<div class="row">
									
									<?php 
									if(($this->session->userdata('role_id') == 1))
									{
									$all_employees = $this->timesheet_model->get_all_users();
									?>
									
										<div class="col-sm-6 col-md-3 col-xs-6">  
											<div class="form-group form-focus select-focus" style="width:100%;">

												<label class="control-label">Employee Name</label>

												<select class="select floating form-control" name="employee_id"  id="employee_id" style="padding: 14px 9px 0px;"> 

														<option value="" selected="selected">All Employees</option>

														<?php  if(!empty($all_employees)){ ?>

														<?php  foreach ($all_employees as $all_employee) { ?>

														<option value="<?php echo $all_employee['user_id']; ?>" <?php if($this->session->userdata('search_employee') !=''){ if($this->session->userdata('search_employee') == $all_employee['user_id']) { echo 'selected="selected"'; } }?>><?php echo $all_employee['fullname']; ?></option>

														<?php   } ?>

														<?php  } ?>

												</select>
												<label id="employee_id_error" class="error display-none" for="employee_id">Please select an option</label>
											</div>
										</div>
										
										<?php } ?>
										<div class="col-sm-6 col-md-3 col-xs-6">
											<div class="form-group form-focus">
												<label class="control-label">Date From</label>
												<div class="cal-icon">
													<input class="form-control floating" id="timesheet_date_from" type="text" data-date-format="dd-mm-yyyy" name="search_from_date" id="search_from_date" value="<?php if($this->session->userdata('search_from_date') !=''){ echo $this->session->userdata('search_from_date');  } ?>" size="16">
													<label id="timesheet_date_from_error" class="error display-none" for="timesheet_date_from">From Date Shouldn't be empty</label>
												</div>
											</div>
										</div>
										<div class="col-sm-6 col-md-3 col-xs-6">
											<div class="form-group form-focus">
												<label class="control-label">Date To</label>
												<div class="cal-icon">
													<input class="form-control floating" id="timesheet_date_to" type="text" data-date-format="dd-mm-yyyy" name="search_to_date" id="search_to_date" value="<?php if($this->session->userdata('search_to_date') !=''){ echo $this->session->userdata('search_to_date');  } ?>" size="16">
													<label id="timesheet_date_to_error" class="error display-none" for="timesheet_date_to">To Date Shouldn't be empty</label>
												</div>
											</div>
										</div>
										<div class="col-sm-6 col-md-3 col-xs-6">  
										<div class="form-group">
												<button id="timesheet_search_btn" class="btn btn-success form-control" > Search </button>  
										</div>
										</div> 
									</div>
							 
						</form>
							</div> 
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">

								<table id="table_timesheet" class="table table-striped custom-table m-b-0 datatable">
									<thead>
										<tr>
											<th>S.No</th>
											<th>Employee Name</th>
											<th>Date</th>
											<th>Project Name</th>
											<th>Work Hours</th>
											<!-- <th class="text-center">Hours</th> -->
											<th>Work Description</th>
											<?php if(($this->session->userdata('role_id') != 1) && ($this->session->userdata('role_id') != 4)){ /*?>
												<th class="text-right">Actions</th>
											<?php */}?>
										</tr>
	
									</thead> 
									<tbody>

										<?php if(!empty($all_timesheet)){ $a = 1; foreach($all_timesheet as $timesheet){ 
										$where12 = array('user_id'=>$timesheet['user_id']);
										$user1 = $this->db->from('account_details')->where($where12)->get()->row_array();	
										?>
										<tr>
											<td><?php echo $a; ?></td>
											<td>
												<h2><a href="javascript:void(0);"><?php echo $user1['fullname']; ?> <span><?php //echo $timesheet['designation']?$timesheet['designation']:'-'; ?> </span></a></h2>
											</td>
											<?php $tm_date = date("d-M-Y", strtotime($timesheet['timeline_date']));?>
											<td><?php echo $tm_date; ?></td>
											<td>
												<h2><?php echo $timesheet['project_title']; ?></h2>
											</td>
											<td><?php echo $timesheet['hours']; ?></td>
											<td><?php echo $timesheet['timeline_desc']; ?></td>
											
										</tr>
									<?php $a++; } }else{ ?>
										<tr>
											<td colspan="6" style="text-align: center;">No Result Found</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
            <!-- </div> -->

            			<div id="add_todaywork" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Add Today Work details</h4>
						</div>
						<div class="modal-body">
							<form method="post" id="add_timeline"> 
								<div class="form-group">
									<label>Project <span class="text-danger">*</span></label>
									<?php
									$where12 = array();
									$projects = $this->db->from('projects')->where($where12)->get()->result();
									?>
									<select class="select form-control" name="project_name" id="project_name">
										<option value="" selected="selected" disabled="">Choose Project</option>
										<?php
										if (!empty($projects)) { 
										foreach ($projects as  $p) { ?>
										<option value="<?php echo $p->project_id; ?>" <?php if($project_id == $p->project_id){ ?> selected <?php } ?>><?=$p->project_title?></option>
										<?php } }else{ ?>
										<option value="">No Results</option>
										<?php } ?>
										
									</select>
								</div>
								<div class="row">
									<div class="form-group col-sm-6">
										<label>Date <span class="text-danger">*</span></label>
										<div class=""><input class="form-control TimeSheetDate" type="text" value="<?php echo date('d-m-Y'); ?>" name="timeline_date" id="timeline_date" data-date-format="dd-mm-yyyy"></div>
										<!-- <input type="hidden" name="user_id" value=""> -->
									</div>
									<div class="form-group col-sm-6">
										<label>Hours <span class="text-danger">*</span></label>
										<input class="form-control" type="text" placeholder="00:00" name="timeline_hours" id="timeline_hours">
										<span class="Error-Hours" style="display: none;color:red">Hour Error</span>
										<span class="Error-Hours-Exist" style="display: none;color:red">Total hours over </span>
									</div>
								</div>
								<div class="form-group">
									<label>Description <span class="text-danger">*</span></label>
									<textarea rows="4" cols="5" class="form-control" name="timeline_desc" id="timeline_desc"></textarea>
								</div>
								<div class="m-t-20 text-center">
									<button class="btn btn-primary" id="new_timesheet_btn">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php foreach($all_timesheet as $timesheet){ ?>
				<div id="edit_todaywork<?php echo $timesheet['time_id']; ?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Edit Work Details</h4>
						</div>
						<div class="modal-body">
							<form method="post" id="edit_timesheet">
								<div class="form-group">
									<label>Project <span class="text-danger">*</span></label>
									<?php
									$where12 = array();
									$projects = $this->db->from('projects')->where($where12)->get()->result_array();
									?>
									<select class="select form-control" name="project_name" id="project_name<?php echo $timesheet['time_id']; ?>">
										<option value="" selected="selected" disabled="">Choose Project</option>
										<?php foreach($projects as $project){ ?>
										<option value="<?php echo $project['project_id']; ?>" <?php if($timesheet['project_id'] == $project['project_id']){ ?> selected="selected" <?php } ?>><?php echo $project['project_title']; ?></option>
										<?php } ?>
									</select>
									<div class="row">
									<div class="col-md-12">
									<label id="project_name_required" class="error display-none" for="project_name"  style="top:0;font-size:15px;">Please select a project</label>
									</div>
									</div>

								</div>
								<div class="row">
									<div class="form-group col-sm-6">
										<label>Date <span class="text-danger">*</span></label>
										<div class="cal-icon"><input class="form-control" readonly value="<?php echo $timesheet['timeline_date']; ?>" name="timeline_date" id="timeline_date<?php echo $timesheet['time_id']; ?>" type="text" ></div>
										<div class="row">
										<div class="col-md-12">
										<label id="timeline_date_required" class="error display-none" for="timeline_date" style="top:0;font-size:15px;">Date is required</label>
										</div>
									    </div>

									</div>
									<div class="form-group col-sm-6">
										<label>Hours <span class="text-danger">*</span></label>
										<input class="form-control workTimelineHour" type="text" value="<?php echo $timesheet['hours']; ?>" name="timeline_hours" id="timeline_hours<?php echo $timesheet['time_id']; ?>">
										<span class="Error-Hours-edit" style="display: none;color:red">Hour Error</span>
										<span class="Error-Hours-Exist-edit" style="display: none;color:red">Total hours over </span>
										<div class="row">
										<div class="col-md-12">
										<label id="timeline_hours_error" class="error display-none" for="timeline_hours" style="top:0;font-size:15px;">Please enter a valid hour format</label>
										<label id="timeline_hours_required" class="error display-none" for="timeline_hours"  style="top:0;font-size:15px;">Hour field is required</label>
										</div>
									     </div>

									</div>
								</div>
								<div class="form-group">
									<label>Description <span class="text-danger">*</span></label>
									<textarea rows="4" cols="5" class="form-control workTimelineDesc" name="timeline_desc" id="timeline_desc<?php echo $timesheet['time_id']; ?>" ><?php echo $timesheet['timeline_desc']; ?></textarea>
									<div class="row">
									<div class="col-md-12">
									<label id="timeline_desc_error" class="error display-none" for="timeline_desc" style="top:0;font-size:15px;">Description is required</label>
									</div>
									</div>
								</div>
								<div class="m-t-20 text-center">
									<button type="button" class="btn btn-primary edit_timesheet_btn" id="timesheet_edit_submit" data-editid="<?php echo $timesheet['time_id']; ?>" >Save Changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div id="delete_workdetail<?php echo $timesheet['time_id']; ?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Delete Work Details</h4>
						</div>
						<div class="modal-body card-box">
							<p>Are you sure want to delete this?</p>
							<div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
								<button type="submit" class="btn btn-danger Delete-Timeline" data-timeid="<?php echo $timesheet['time_id']; ?>">Delete</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
