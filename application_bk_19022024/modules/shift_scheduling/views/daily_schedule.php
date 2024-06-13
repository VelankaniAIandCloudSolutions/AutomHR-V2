<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); ?>
<div class="content">
	<!-- <div class="row">
		<div class="col-sm-12">
			<h4 class="page-title m-b-0"><?php echo lang('employee_management');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li><a href="#">Home</a></li>
				<li><a href="#">Employees</a></li>
				<li><a href="#">Shift Schedule</a></li>
				<li class="active">Daily Schedule</li>
			</ul>
		</div>
	</div> -->
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="page-title"><?php echo lang('shift_scheduling');?></h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></li>
					<li class="breadcrumb-item active"> <?php echo lang('shift_scheduling')?></li>
				</ul>
			</div>
		</div>
	</div>
	
		<?php /*	<form id="timesheet_search" method="post" action="<?php echo base_url().'shift_scheduling/'; ?>">
		<div class="row filter-row">
			
			<div class="col-sm-6 col-12 col-md-3 col-lg-2">
				<div class="form-group form-focus">
					<label class="control-label"><?php echo lang('employee');?></label>
					<input type="text" class="form-control floating" name="username" id="username" value="<?php echo(isset($username))?$username:""?>">
					<label id="username_error" class="error display-none" for="username"><?php echo lang('please_enter_the_employee_name');?></label>
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3">
				<div class="form-group form-focus select-focus" style="width:100%;">
					<label class="control-label">Select Department</label>
						<select class="select form-control floating" id="department_id" name="department_id" style="padding: 14px 9px 0px;">
						<option value="" selected="selected"><?php echo lang('all_departments');?></option>
						<?php if(!empty($departments)){ ?>
						<?php foreach ($departments as $department) { ?>
						<option value="<?php echo $department->deptid; ?>" <?php echo (isset($department_id) && $department_id == $department->deptid)?"selected":""?>><?php echo $department->deptname; ?></option>
						<?php  } ?>
						<?php } ?>
					</select>
					<label id="department_id_error" class="error display-none" for="department_id"><?php echo lang('please_select_the_department');?></label>
				
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3 col-lg-2">
				<div class="form-group form-focus">
					<label class="control-label"><?php echo lang('date');?></label>
					<input type="text" class="form-control floating date_range" id="schedule_date" name="schedule_date" value="<?php echo (isset($schedule_date))?$schedule_date:"";?>" autocomplete="off">
					<label id="schedule_date_error" class="error display-none" for="schedule_date"><?php echo lang('please_select_the_date');?></label>
				</div>
			</div>
			<div class="col-sm-6 col-12 col-md-3 col-lg-2">
				<div class="form-group form-focus select-focus" style="width:100%;">
					<label class="control-label"><?php echo lang('date_view');?></label>
					<select class="select form-control floating" style="padding: 14px 9px 0px;" name="week" id="week">
						<option value="" selected="selected"><?php echo lang('select');?></option>
						<option value="week" <?php echo (isset($week) && $week == 'week')?"selected":"";?>><?php echo lang('week');?></option>
						<option value="month" <?php echo (isset($week) && $week == 'month')?"selected":"";?>><?php echo lang('month');?></option>
					</select>
					<label id="week_error" class="error display-none" for="week"><?php echo lang('month');?></label>
				</div>
			</div>
			<div class="col-sm-6 col-6 col-md-3">  
				<!-- <a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee btn-circle"> Search </a>  -->

				<button id="shif_schedule_search_btn" class="btn btn-primary btn-block btn-searchEmployee btn-circle" ><?php echo lang('search');?></button>   
			</div>
		
		</div>
		</form>*/?>
	
	
		<div class="row d-flex align-items-center">	
			<div class="col-sm-5 mb-2">
				<h4 class="page-title text-dark"><?php echo lang('shift_scheduling');?></h4>
			</div>
			<div class="col-md-7 mb-2">
				<?php if(App::is_permit('menu_shift_scheduling','create')){?><a href="<?php echo base_url(); ?>shift_scheduling/add_schedule" class="btn add-btn mb-2"><i class="fa fa-plus"></i><?php echo lang('assign_shift');?></a><?php }?>
				<?php if(App::is_permit('menu_shift_scheduling','read')){?><a href="<?php echo base_url(); ?>shift_scheduling/shift_list" class="btn add-btn mb-2 m-r-5"><?php echo lang('shifts');?></a>		<?php }?>
				<!-- <a href="<?php echo base_url(); ?>shift_scheduling/schedule_group" class="btn add-btn m-r-5"><?php echo lang('rotary_schedule_groups');?></a>	 -->	
			</div>
		</div>
		<!-- /Page Title -->
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table m-b-0" id="policies_table">
						<thead>
							<tr>
								<th><?php echo lang('employee');?></th>
								<th>Shift</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Break Time(Mins)</th>
								<th>Action</th>

								<?php
									/*if(isset($schedule_date) && !empty($schedule_date)){
                            		$schedules_date = explode('-', $schedule_date);
                        			$from_date = date("Y-m-d", strtotime($schedules_date));       
       								$to_date = date("Y-m-d", strtotime($schedules_date));
        							$earlier = new DateTime($from_date);
									$later = new DateTime($to_date);

        							$col_count = $later->diff($earlier)->format("%a");
                            					
            //                 					$to_date = date("Y-m-d", $your_to_date);
            //                 					$this->db->where('schedule_date >=', $from_date);
												// $this->db->where('schedule_date <=', $to_date);
                            			}else if(isset($week) && !empty($week)){
                            				if($week == 'week'){
                            					$dt_min = new DateTime("last saturday"); // Edit
											$dt_min->modify('+1 day'); // Edit
											$dt_max = clone($dt_min);
											$dt_max->modify('+6 days');
											$week_start = $dt_min->format('Y/m/d');
											$week_end = $dt_max->format('Y/m/d');
											// echo 'This Week ('.$dt_min->format('m/d/Y').'-'.$dt_max->format('m/d/Y').')'; 
												$col_count = 6;
                            				} else{

                            					$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
												$last_day_this_month  = date('Y-m-t');
												$month_start = new DateTime($first_day_this_month);
												$month_end = new DateTime($last_day_this_month);

			        							$col_count = $month_end->diff($month_start)->format("%a");

                            				}
                            				
                            			}
                            			 else{
                            			 	$datetime = new DateTime(date());
					                        $start_date =  $datetime->format('d');
                            				// $col_count = date('t') - $start_date ;
                            				$col_count = 30 ;

					                        // $currentDayOfMonth=date('j');
					                        // $datetime = new DateTime($_POST['schedule_date']);
					                        // $start_date =  $datetime->format('d');
                            			}
                            			
								 for ($i=0; $i <=$col_count ; $i++) { 
								 	if(isset($schedule_date) && !empty($schedule_date)){
										echo'<th class="text-center">'.date('D d', strtotime('+'.$i.' days', strtotime($schedules_date[0]))).'</th>';
								 	}else if(isset($week) && !empty($week)){
								 		if($week == 'week'){
								 			echo'<th class="text-center">'.date('M D d', strtotime('+'.$i.' days', strtotime($week_start))).'</th>';
								 		} else{
								 			echo'<th class="text-center">'.date('M D d', strtotime('+'.$i.' days', strtotime($first_day_this_month))).'</th>';
								 		}
								 	}else{
								 		echo'<th class="text-center">'.date('M D d', strtotime('+'.$i.' days', time())).'</th>';
								 	}
									
								}*/ ?>
								
							</tr>
						</thead>
						<tbody>
						<?php 
						if (count($shift_scheduling) > 0) {
							foreach ($shift_scheduling as $shift) {	
								$list_ids = $this->db->query("SELECT * FROM `dgt_shift_scheduling` where shift_id ='".$shift['shift_id']."' ")->result_array();	
								$employee_ids = array_column($list_ids, 'employee_id');

								
								$this->db->select('*');
								$this->db->from('dgt_account_details');
								$this->db->where_in('user_id', $employee_ids);
								$query = $this->db->get();
								$employee_list = $query->result_array();;	
								$employee_name = '';
								if(!empty($employee_list)){
									foreach($employee_list as $employee_1){
										$employee_name .= $employee_1['fullname'].', ';
									}
									$employee_name = rtrim($employee_name,", ");
								}
								$shift_list = $this->db->query("SELECT * FROM `dgt_shifts` where id ='".$shift['shift_id']."' ")->row_array();	
							 ?>
							<tr>
								<td>
									<div class="user_det_list">
										
										<h2>
											<span class="username-info text-dark"><?php echo $employee_name;?></span>
										</h2>
									</div>
								</td>
								
							
								<td>
									<div class="user_det_list">
										
										<h2>
											<span class="username-info text-dark"><?php echo $shift_list['shift_name'];?></span>
										</h2>
									</div>
								</td>

								<td>
									<div class="user_det_list">
										
										<h2>
											<span class="username-info text-dark"><?php echo $shift_list['start_time'];?></span>
										</h2>
									</div>
								</td>

								<td>
									<div class="user_det_list">
										
										<h2>
											<span class="username-info text-dark"><?php echo $shift_list['end_time'];?></span>
										</h2>
									</div>
								</td>
								<td>
									<div class="user_det_list">
										
										<h2>
											<span class="username-info text-dark"><?php echo $shift_list['break_time'];?></span>
										</h2>
									</div>
								</td>
								
								<td>
								<div class="dropdown dropdown-action">
									<a data-toggle="dropdown" class="action-icon" href="#">
										<i class="material-icons">more_vert</i>
									</a>
			                          <div class="dropdown-menu float-right">
			                            
			                            <a class="dropdown-item" href="<?php echo base_url('shift_scheduling/edit_schedule/'.$shift['shift_id']);?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
			                           <a class="dropdown-item" href="<?php echo base_url('shift_scheduling/delete_schedule/'.$shift['shift_id']);?>"  title="" data-original-title="Delete"><i class="fa fa-trash-o m-r-5" onclick="return confirm('Are you want to delete?')"></i> Delete</a>
			                                
			                            
			                          </div>
			                        </div>
							</td>
								
							</tr>
							<?php 
						
						} 
						} else{ ?>
							<tr>
								<td colspan="57">No Records Found</td>
							</tr>
						<?php } ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

