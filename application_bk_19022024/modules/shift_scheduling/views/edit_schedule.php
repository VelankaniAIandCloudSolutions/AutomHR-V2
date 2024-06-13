<?php 	$entities = $this->db->where("branch_status", "0")->get('dgt_branches')->result_array(); 
		$shifts = $this->db->where('published',1)->order_by("id", "asc")->get('shifts')->result();
?>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title m-b-0"><?php echo lang('shift_scheduling');?></h4>
			<ul class="breadcrumb m-b-20 p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('employees');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>shift_scheduling"><?php echo lang('shift_scheduling');?></a></li>
				<li class="breadcrumb-item active"><?php echo lang('edit_schedule');?></li>
			</ul>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn add-btn" href="<?=base_url()?>shift_scheduling"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
	      </div>
	</div>
</div>
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<!-- Add Schedule -->
			<div class="card">
				
				<div class="card-body">
					<h6 class="card-title"><?php echo lang('edit_schedule');?></h6>
					<form method="POST" id="employeeScheduleAddForm" action="<?php echo base_url().'shift_scheduling/edit_schedule'?>">
						<div class="row">
							<div class="col-md-12">
								
								<div class="form-group">
									<label><?php echo lang('branch');?> <span class="text-danger">*</span></label>
									<select class="select event-from-time form-control" name="entity_id" id="entity_id" >
										<option value="" selected disabled><?php echo lang('branch');?></option>
										<?php if(!empty($entities)){
											foreach($entities as $entity_1){
										?>
												<option value="<?= $entity_1['branch_id'];?>"  <?php if(!empty($shift_details['entity_id']) && ($shift_details['entity_id'] == $entity_1['branch_id'] )){ echo 'selected'; }?>><?= $entity_1['branch_name'];?></option>
										<?php
											}
										}
										?>
									</select>	
									<input type="hidden" name="shift_id" value="<?= $shift_details['shift_id'];?>" id="cur_shift_id">										
								</div>
								<div class="form-group">

									<label><?php echo lang('department');?> <span class="text-danger">*</span></label>
									<select class="select event-from-time form-control" name="department" id="department" >
										<option value="" selected ><?php echo lang('department');?></option>
										<?php if(!empty($departments1)){
											foreach($departments1 as $department_1){
										?>
												<option value="<?= $department_1['deptid'];?>" <?php if(!empty($shift_details['department_id']) && ($shift_details['department_id'] == $department_1['deptid'] )){ echo 'selected'; }?>><?= $department_1['deptname'];?></option>
										<?php
											}
										}
										?>
									</select>
								</div>
								<div class="form-group">

									<label><?php echo lang('employee');?> <span class="text-danger">*</span></label>
									<select class="select event-from-time form-control" name="employee[]" id="employee" multiple >
										<?php if(!empty($all_shift_details)){
											foreach($all_shift_details as $all_shift_1){
											?>
												<option value="<?= $all_shift_1['employee_id'];?>" selected><?= $all_shift_1['fullname'];?></option>
											<?php
											}
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label><?php echo lang('start_date');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
										<input class="datepicker-schedule form-control" name="schedule_date" id="schedule_date" data-date-format="dd-mm-yyyy" value="<?php echo (isset($schedule_date) && !empty($schedule_date))?$schedule_date:date('d-m-Y');?>" <?php echo (isset($schedule_date) && !empty($schedule_date))?"disabled":"";?>>
									</div>
								</div>
								<?php

										if($employee_details['schedule_date']<= date('Y-m-d')){
											$disabled='disabled';
										}else{
											$disabled='';
										}
										?>
								<div class="form-group">
                                    <label><?php echo lang('shifts');?> <span class="text-danger">*</span></label>
									<select class="select event-from-time form-control" name="shift_id" id="shift_id" <?php echo $disabled;?>>
										<option value="" selected disabled><?php echo lang('shifts');?></option>
										<?php
										if(!empty($shifts))	{
											$j =1;
										foreach ($shifts as $shift){ ?>
										<option value="<?=$shift->id?>" <?php echo(!empty($shift_details['shift_id'] && $shift_details['shift_id'] == $shift->id))?"selected":"";?>><?php echo $shift->shift_name;?></option>
										<?php $j++; } ?>
										<?php } ?>
									</select>
                                </div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('start_time');?> <span class="text-danger">*</span></label>
											<div class='input-group'>
												<input type="text" class="form-control" name="start_time" id="start_time" value="<?php  if(isset($shift_details) && ($shift_details['start_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($shift_details) && !empty($shift_details['start_time']))?date('h:i a', strtotime($shift_details['start_time'])):"";}?>" readonly>
												
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('end_time');?> <span class="text-danger">*</span></label>
											<div class='input-group'>
												<input type="text" class="form-control" name="end_time" id="end_time" value="<?php if(isset($shift_details) && ($shift_details['end_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($shift_details) && !empty($shift_details['end_time']))?date('h:i a', strtotime($shift_details['end_time'])):"";}?>" readonly>
												
											</div>												
										</div>
									</div>								
								</div> 
								<div class="form-group shift_details">
									<label><?php echo lang('break_time');?> (<?php echo lang('in_minutes');?>) </label>
									<div class='input-group'>
										<input type="text" class="form-control" name="break_time" id="break_time" value="<?php echo (isset($shift_details) && !empty($shift_details['break_time']))?$shift_details['break_time']:"";?>" readonly>
										
									</div>											
								</div>	
								<div class="form-group">
									<div class="checkbox  <?php echo (isset($shift_details['cyclic_shift']) && ($shift_details['cyclic_shift'] ==1))?"":"hide";?>">
									  <label><input type="checkbox"  name="cyclic_shift" id="" value="1" class="recurring mr-2" <?php echo (isset($shift_details['cyclic_shift']) && ($shift_details['cyclic_shift'] ==1))?"checked":"";?> onclick="return false;">Cyclic Shift</label>
									</div>
								</div>
								<div class="exist_data <?php echo ($shift_details['recurring_shift'] == 1)?"":"hide";?>">
									
								<div class="form-group">
									<div class="checkbox <?php echo (isset($shift_details['recurring_shift']) && ($shift_details['recurring_shift'] ==1))?"":"hide";?>">
									  <label><input type="checkbox"  name="recurring_shift" id="" value="1" class="recurring mr-2" <?php echo (isset($shift_details['recurring_shift']) && ($shift_details['recurring_shift'] ==1))?"checked":"";?> onclick="return false;">Recurring Shift</label>
									</div>
								</div>
								
								<!--  -->	
								
								<div class="form-group repeat_week <?php echo (isset($shift_details['cyclic_shift']))?"":"hide";?>">
									<label><?php echo lang('repeat_every');?>y</label>
									<select class="select form-control recurring" name="repeat_week" id="repeat_week" onchange="return false;">
										
										<option value="1" <?php echo ($shift_details['repeat_week'] ==1)?"selected":"";?>>1</option>
										<option value="2" <?php echo ($shift_details['repeat_week'] ==2)?"selected":"";?>>2</option>
										<option value="3" <?php echo ($shift_details['repeat_week'] ==3)?"selected":"";?>>3</option>
										<option value="4" <?php echo ($shift_details['repeat_week'] ==4)?"selected":"";?>>4</option>
									</select>
									<label><?php echo lang('week');?>(s)</label>
								</div>		
								<?php $weekdays = explode(',',$shift_details['week_days']);?>
								<div class="form-group wday-box">
									<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="monday" class="days recurring " <?php echo in_array('monday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">M</span></label>
    
   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="tuesday" class="days recurring" <?php echo in_array('tuesday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">T</span></label>
								   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="wednesday" class="days recurring" <?php echo in_array('wednesday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">W</span></label>
								   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="thursday" class="days recurring" <?php echo in_array('thursday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">T</span></label>
								    
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="friday" class="days recurring" <?php echo in_array('friday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">F</span></label>
								   
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="saturday" class="days recurring" <?php echo in_array('saturday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">S</span></label>
								  
							      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="sunday" class="days recurring" <?php echo in_array('sunday', $weekdays)?"checked":"" ;?> onclick="return false;"><span class="checkmark">S</span></label>
								</div>	
								<div class="form-group end_date1">
									<label><?php echo lang('end_on');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
									<input class="datepicker-schedule form-control end_date recurring " class="form-control" data-date-format="dd-mm-yyyy"  name="end_date"   value="<?php echo (isset($shift_details['end_date']) && ($shift_details['end_date'] =='0000-00-00'))?date('d-m-Y',strtotime($shift_details['schedule_date'])):date('d-m-Y',strtotime($shift_details['end_date']));?>"  <?php echo (isset($shift_details['indefinite']) && ($shift_details['indefinite'] ==1))?"disabled":"";?>>	
									</div>								
								</div>	
								<div class="form-group shift_details hide">
									<div class="checkbox indefinite_checkbox_edit ">
								 	 	<label><input type="checkbox"  name="indefinite" id="indefinite_edit" value="1" class="recurring mr-2" <?php echo (isset($shift_details['indefinite']) && ($shift_details['indefinite'] ==1))?"checked":"";?> onclick="return false;">Idefinite</label>
									</div>
								</div>
								</div>

								<!-- <div class="form-group shift_details ">
										<div class="checkbox">
										  
										</div>
								</div> -->
								<div class="form-group shift_details repeat_week hide">
									<label><?php echo lang('repeat_every');?></label>
									<div class='input-group'>
										<input type="text" class="form-control" name="repeat_week" id="repeat_week" value="" readonly>
									</div>	
									
								</div>	
								<div class="form-group wday-box">
									
								</div>	
								<div class="form-group shift_details edit_end_date <?php echo ($shift_details['recurring_shift'] == 1 || $employee_details['cyclic_shift'] ==1)?"hide":"";?>">
									<label>End on <span class="text-danger">*</span></label>
									<input class="datepicker-schedule form-control end_date" class="form-control" data-date-format="dd-mm-yyyy"  name="end_date"   value="<?php echo (isset($shift_details['end_date']) && ($shift_details['end_date'] =='0000-00-00'))?date('d-m-Y',strtotime($employee_details['schedule_date'])):date('d-m-Y',strtotime($shift_details['end_date']));?>"  >								
								</div>	
								<div class="form-group shift_details hide">
									<div class="checkbox indefinite_checkbox">
									 
									</div>
								</div>
								<!-- <div class="form-group">
									<label>Repeat</label>
									<select class="select event-from-time form-control" name="repeat_time" id="repeat_time" <?php echo (isset($employee_details))?"disabled":"";?>>
										<option value="0">Never</option>
										<option value="1">This week</option>
										<option value="2">Every 2 week</option>
										<option value="3">Every 3 week</option>
										<option value="4">Every 4 week</option>
										<option value="5">Every 5 week</option>
										<option value="6">Every 6 week</option>
										<option value="7">Every 7 week</option>
										<option value="8">Every 8 week</option>
									</select>
								</div>								
								<div class="form-group">
									<label>Add a tag </label>
									<input class="form-control" type="text" data-role="tagsinput" name="tag" id="tag" value="<?php echo (isset($employee_details) && !empty($employee_details['tag']))?$employee_details['tag']:"";?>" />
								</div>
								<div class="form-group">
									<label>Add a note</label>
									<textarea class="form-control" rows="4" name="note" id="note"><?php echo (isset($employee_details) && !empty($employee_details['note']))?$employee_details['note']:"";?></textarea>
								</div> -->
								<!-- <div class="form-group">
									<label>Publish</label>
									<div class="material-switch">
										<input id="someSwitch" class="form-control" name="publish" type="checkbox"/ checked value="1">
										<label for="someSwitch" class="label-warning"></label>
									</div>
								</div> -->
								<div class="form-group total_cyclic_days <?php echo ($employee_details['cyclic_shift'] ==1)?"":"hide"?>">
									<label><?php echo lang('no_of_days_in_cycle');?> </label>
									<input class="form-control" type="text" name="no_of_days_in_cycle" id="total_cyclic_days" value="<?php echo $shift_details['no_of_days_in_cycle']?>" readonly / keyup="return false;">
								</div>
								<div class="form-group wday-box cyclic_days">
									<?php
									if($shift_details['cyclic_shift'] ==1){
									
										for ($i=1; $i < $shift_details['no_of_days_in_cycle']+1; $i++) { ?>
										
											<label class="checkbox-inline "><input type="checkbox" name="workdays[]" value="<?php echo $i;?>" class="days recurring" <?php echo ($shift_details['workday'] >= $i)?"checked":""?> onclick="return false;"><span class="checkmark"><?php echo $i;?></span></label>	
										
										
									<?php }
									} ?>	
								</div>
								<div class="submit-section">
									<a href="<?php echo base_url(); ?>shift_scheduling" class="btn btn-danger bg-orange text-white submit-btn m-b-5" type="submit"><?php echo lang('cancel');?></a>
									<button class="btn btn-primary submit-btn m-b-5" id="submit_shift_scheduling_add" type="submit"><?php echo lang('save');?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.checkbox,.repeat_week,.wday-box{
		display:none !important;
	}
	.wday-box{

		display: none !important;
	}
</style>