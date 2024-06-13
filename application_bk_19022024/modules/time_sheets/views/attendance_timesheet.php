            <!-- <div class="page-wrapper"> -->
            	<?php  //echo $this->session->userdata('search_employee'); ?>
                <div class="content container-fluid">
                	<div class="page-header">
					<div class="row">
						<div class="col-sm-8 col-5">
							<h4 class="page-title">Clockfie Timesheets</h4>
							<ul class="breadcrumb">
				                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				                <li class="breadcrumb-item active">Clockfie Timesheets</li>
				            </ul>
						</div>
					
						<div class="col-sm-4 text-right col-7 m-b-30">
							<a href="https://drive.google.com/drive/folders/1Vfv1g8yLVesPQhT2HJWLCGYcG9Zevxa0?usp=share_link" target="_blank" class="btn btn-primary add-btn" > Clockfie Exe</a>
						</div>
					
					</div>
				</div>
				<input type="hidden" value="<?php echo App::is_permit('menu_timesheets','write'); ?>" id="edit_timesheet_access">
					<?php 
					$all_employees = $this->timesheet_model->get_all_users();
					?>
					
					<?php if(User::is_admin()){ ?>

						<div class="row filter-row">
								<div class="col-md-12 padding-2p search_date">
							<form id="timesheet_search" method="post" action="<?php echo base_url().'time_sheets/clockfie_timesheet'; ?>">
										<div class="row">
											<div class="col-sm-6 col-md-3 ">  
												<div class="form-group form-focus select-focus" style="width:100%;">

													<label class="control-label">Employee Name</label>

													<select class="select2-option floating form-control" name="employee_id"  id="employee_id" style="padding: 14px 9px 0px;"> 

															<option value="" selected="selected">Select</option>

															<?php  if(!empty($all_employees)){ ?>

															<?php  foreach ($all_employees as $all_employee) { ?>

															<option value="<?php echo $all_employee['user_id']; ?>" <?php if($this->session->userdata('search_employee') !=''){ if($this->session->userdata('search_employee') == $all_employee['user_id']) { echo 'selected="selected"'; } }?>><?php echo $all_employee['fullname']; ?></option>

															<?php   } ?>

															<?php  } ?>

													</select>
													<label id="employee_id_error" class="error display-none" for="employee_id">Please select an option</label>
												</div>
											</div>
											<div class="col-sm-6 col-md-3 ">
												<div class="form-group form-focus">
													<label class="control-label">Date From</label>
													<div class="cal-icon">
														<input class="form-control floating" id="timesheet_date_from" type="text" data-date-format="yyyy-mm-dd" name="search_from_date" id="search_from_date" value="<?php if($this->session->userdata('search_from_date') !=''){ echo $this->session->userdata('search_from_date');  } ?>" size="16" readonly='true'>
														<label id="timesheet_date_from_error" class="error display-none" for="timesheet_date_from">From Date Shouldn't be empty</label>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-3">
												<div class="form-group form-focus">
													<label class="control-label">Date To</label>
													<div class="cal-icon">
														<input class="form-control floating" id="timesheet_date_to" type="text" data-date-format="yyyy-mm-dd" name="search_to_date" id="search_to_date" value="<?php if($this->session->userdata('search_to_date') !=''){ echo $this->session->userdata('search_to_date');  } ?>" size="16" readonly='true'>
														<label id="timesheet_date_to_error" class="error display-none" for="timesheet_date_to">To Date Shouldn't be empty</label>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-3 ">  
											<div class="form-group">
											<button id="timesheet_search_btn" class="btn btn-primary form-control" > Search </button>  
											</div>
											</div> 
										</div>
								
							</form>
								</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="timesheet" class="table table-striped custom-table m-b-0 ">
									<thead>
										<tr>
											<th>#</th>
											<th>Employee Name</th>
											<!--<th>Project Name</th>
											<th>Work Hours</th>
											<!-- <th class="text-center">Hours</th> -->
											<!--<th>Work Description</th>-->
											<?php foreach($time_sheet_data['date_range'] as $key=>$data){ ?>
												<th><b><?=$data['day']?> </b><br><span><?=$data['dateInFormat']?></span></th>
												<?php } ?>
											<th>Time Worked</th>
											
										</tr>
									</thead>
									<tbody>
										<?php if(count($time_sheet_data['all_report']) != 0){ $a = 1; foreach($time_sheet_data['all_report'] as $timesheet){ 
											$user = User::login_info_by_email($timesheet['user']['email']);
											$user_id = $user->id;

											if($timesheet['user']['email']==$user->email && $user->is_exit==0){

											
											?>
										<tr>
											<td><?php echo $a; ?></td>
											<td>
												<?php
												$user_dept=App::get_user_dept_by_id($user->id);
												$dept_name=App::get_dept_by_id($user_dept);
												$user_id=$user->id;
												if($timesheet['avatar']!='' && file_exists(base_url().'assets/avatar/'.$timesheet['avatar'])){
													$avatar = $timesheet['avatar'];
												}else{
													$avatar = 'default_avatar.jpg';
												}
												?>
												<?php if(User::is_admin()){ ?>
												<h2 class="table-avatar"><a  href="<?php echo base_url(); ?>employees/profile_view/<?php echo $user_id; ?>" class="avatar"><img class="avatar" src="<?php echo User::avatar_url($user_id); ?>" alt=""></a><a href="<?=base_url().'time_sheets/clockfie_timesheet/'.$user_id; ?>"><?php echo User::DisplayName($user_id); ?>  <span class="dept_name">( <?php echo $dept_name?$dept_name:'-'; ?> )</span></a></h2> <?php }else{ ?>
												<h2 class="table-avatar"><a  href="<?php echo base_url(); ?>employees/profile_view/<?php echo $user_id; ?>" class="avatar"><img class="avatar" src="<?php echo User::avatar_url($user_id); ?>" alt=""></a><a href="<?=base_url().'time_sheets/clockfie_timesheet/'.$user_id; ?>"><?php echo User::DisplayName($user_id); ?>  <span class="dept_name"> (<?php echo $dept_name?$dept_name:'-'; ?> ) </span></a></h2> <?php } ?>

											</td>
											<?php 
											$total_seconds = 0;
											foreach($timesheet['report']['date_range_data'] as $wrk_time){ 
												if($user->prod_hours_from==2){
													date_default_timezone_set('Asia/Kolkata');
													$punch_in_date = date('Y-m-d');
													$punch_in_time = date('H:i');
													// $punch_in_date_time = date('Y-m-d H:i');
													$punch_in_date_time = $wrk_time['total_time_dur_new'];
		
		
													$strtotime = strtotime($punch_in_date_time);
													$a_year    = date('Y',$strtotime);
													$a_month   = date('m',$strtotime);
													$a_day     = date('d',$strtotime);
													$a_days     = date('d',$strtotime);
													$a_dayss     = date('d',$strtotime);
													$a_cin     = date('H:i',$strtotime);
													$where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
													$this->db->select('last_punchin,month_days,month_days_in_out');
													$rows  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
													$a_dayss -=1;
													$production_hour=0;
													$break_hour=0;
													
													$month_days =  unserialize($rows['month_days']);
													$month_days_in_out =  unserialize($rows['month_days_in_out']);
													$day = $month_days[$a_day-1];
													$day_in_out = $month_days_in_out[$a_day-1];
													$latest_inout = end($day_in_out);
													$production_hour=0;
													$break_hour=0;
													$k = 1;
													foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
													{

														if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
														{
															$days = $a_day;
															$today_work_where     = array('id'=>1);
															$today_work_hour = $this->db->get_where('dgt_shifts',$today_work_where)->row_array();
															if($k == 1){                              
															$later_entry_hours = later_entry_minutes(date('Y-m-'.$days).' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);   
															$extra_hours = extra_minutes(date('Y-m-'.$days).' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);     
															
														}
															$production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
														}		
														$k++;                              
															
													}
													// $num = $production_hour;
													// $num_padded = sprintf("%02d", $num);
													$hours_val = sprintf("%02d", intdiv($production_hour, 60));
													$mints_val = sprintf("%02d", ($production_hour % 60));
													$prod_hour = $hours_val.'h '. $mints_val.'m';
													$seconds = $hours_val * 3600 + $mints_val * 60 ;
													$total_seconds += $seconds;
													$total_secs = 8*3600;
													$progress_bar = ($seconds/$total_secs)*100;


												}else{
													$prod_hour = $wrk_time['total_time_dur'];
													$progress_bar = $wrk_time['compute_time_per'];
												}
											?>
											<td>
											<div class="progress mb-2" style="height: 22px">
											<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $progress_bar?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
												<?=$prod_hour?></td> 
											<?php } 
											if($user->prod_hours_from==2){
												$tot_hrs = gmdate("H", $total_seconds);
												$tot_mins = gmdate("i", $total_seconds);
												$total_working_hrs = $tot_hrs.'h '.$tot_mins.'m';
											}else{
												$total_working_hrs = $timesheet['report']['total_work_hrs_new'];
											} ?>
											<td><?=$total_working_hrs?></td>
											<?php $tm_date = date("d-M-Y", strtotime($timesheet['timeline_date']));?>
											
										</tr>
									<?php $a++; } } }else{ ?>
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
            </div>


			<div id="add_todaywork" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog  modal-dialog-centered">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Add Today Work details</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form method="post" id="add_timeline"> 
								<div class="form-group">
									<label>Project <span class="text-danger">*</span></label>
									<!-- <select class="select form-control" name="project_name" id="project_name">
										<option value="" selected="selected" disabled="">Choose Project</option>
										<?php foreach($projects as $project){ ?>
										<option value="<?php echo $project['project_id']; ?>"><?php echo $project['project_title']; ?></option>
										<?php } ?>
									</select> -->
									<input class="form-control" type="text"  name="project_name" id="project_name">

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
			<?php 
		// 	echo "<pre>"; print_r($all_timesheet);exit;
			
			foreach($all_timesheet as $timesheet){ ?>
				<div id="edit_todaywork<?php echo $timesheet['time_id']; ?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog  modal-dialog-centered">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Edit Work Details</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form method="post" >
								<div class="form-group">
									<?php
                                    $user_id=$timesheet['user_id'];
									?>
									<label>Project <span class="text-danger">*</span></label>
									<!-- <select class="select form-control" name="project_name" id="project_name<?php echo $timesheet['time_id']; ?>">
										<option value="" selected="selected" disabled="">Choose Project</option>
										<?php foreach($projects as $project){ ?>
										<option value="<?php echo $project['project_id']; ?>" <?php if($timesheet['project_id'] == $project['project_id']){ ?> selected="selected" <?php } ?>><?php echo $project['project_title']; ?></option>
										<?php } ?>
									</select> -->
									<input class="form-control" type="text"  name="project_name" id="project_name<?php echo $timesheet['time_id']; ?>" value="<?php echo $timesheet['project_name']; ?>">
									<div class="row">
										<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
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
									<button type="button" class="btn btn-primary edit_timesheet_btn"  data-editid="<?php echo $timesheet['time_id']; ?>" >Save Changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div id="delete_workdetail<?php echo $timesheet['time_id']; ?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Delete Work Details</h4>
						</div>
						<div class="modal-body card-box">
							<p>Are you sure want to delete this?</p>
							<div class="modal-btn delete-action">
					<div class="row">
					<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn continue-btn">Cancel</a>
						</div>
						<div class="col-6">
							<button type="submit" class="btn continue-btn Delete-Timeline" data-timeid="<?php echo $timesheet['time_id']; ?>">Delete</button>
						</div>
						
					</div>
				</div>
							<!--<div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
								<button type="submit" class="btn btn-danger Delete-Timeline" data-timeid="<?php echo $timesheet['time_id']; ?>">Delete</button>
							</div>-->
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		


		
           
