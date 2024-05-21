
<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); ?>
<div class="content">
	<div class="page-header mb-0">
	<div class="row">
		<div class="col-md-8 col-6">
			<h4 class="page-title m-b-0"><?php echo lang('edit_shift');?></h4>
			<ul class="breadcrumb m-b-20 p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('employees');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('shift_scheduling');?></a></li>
				<li class="breadcrumb-item active"><?php echo lang('edit_shift');?></li>
			</ul>
		</div>
		<div class="col-md-4 col-6 text-right m-b-20">     
	          <a class="btn add-btn" href="<?=base_url()?>shift_scheduling/shift_list"><i class="fa fa-chevron-left"></i><?php echo lang('back');?></a>
	      </div>
	</div>
</div>
	<!-- <div class="card-box">
		<ul class="nav nav-tabs nav-tabs-solid page-tabs">
			<li ><a href="<?php echo base_url(); ?>shift_scheduling">Daily Schedule</a></li>
			<li class="active"><a href="<?php echo base_url(); ?>shift_scheduling/add_schedule"><?php echo lang('add_schedule');?></a></li></ul>
		</div> -->
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<!-- Add Schedule -->
			<div class="card">
				
				<div class="card-body">
					<!-- <h6 class="card-title"><?php echo lang('edit_shift');?></h6> -->
					<form method="POST" id="scheduleAddForm" action="<?php echo base_url().'shift_scheduling/edit_shift'?>">
						<div class="row">
							<div class="col-md-12">
								<?php if(isset($shift_details) && !empty($shift_details)){?>
																
										<input class="form-control" type="hidden" id="shift_id" value="<?=$shift_details['id']?>" name="id" />								

									<?php } ?>
								<div class="form-group">
									<label><?php echo lang('shift_name');?> <span class="text-danger">*</span><span id="check_shiftname" style="display: none;color:red;">Shift Already Exist!</span></label>
									<input type="text" class="form-control edit_shift_name" name="shift_name" id="shift_name" value="<?php echo (isset($shift_details['shift_name']) && !empty($shift_details['shift_name']))?$shift_details['shift_name']:'';?>" >
								</div>
									<?php
									 // $schedule_group = $this->db->order_by("group_name", "asc")->get('rotary_schedule_group')->result(); ?>
								<!-- <div class="form-group">
									<label><?=lang('rotary_schedule_groups')?> </label>
									
									<select class="select2-option form-control"  name="group_id" id="">
										<option value="" selected disabled><?=lang('select_group')?></option>
										<?php
										if(!empty($schedule_group))	{
										foreach ($schedule_group as $rotary_group){ ?>
										<option value="<?=$rotary_group->id?>" <?php echo ($rotary_group->id == $shift_details['group_id'])?"selected":"";?>><?php echo ucfirst($rotary_group->group_name)?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div> -->
								<div class="form-group">
									<label><?php echo lang('start_date');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
										<input class="datepicker-schedule form-control" name="start_date" id="start_date" data-date-format="dd-mm-yyyy" value="<?php echo (isset($shift_details['start_date']) && ($shift_details['start_date'] =='0000-00-00'))?'':date('d-m-Y',strtotime($shift_details['start_date']));?>" placeholder="DD/MM/YYYY">
									</div>
								</div>	
								<div class="row">
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('min_start_time');?><span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="min_start_time" id="min_start_time" value="<?php if(isset($shift_details) && ($shift_details['min_start_time'] == '00:00:00')){
													echo '';
												} else { echo (isset($shift_details) && !empty($shift_details['min_start_time']))?$shift_details['min_start_time']:"";}?>">
												
											</div>											
										</div>
									</div> -->
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('start_time');?> <span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="start_time" id="start_time" value="<?php  if(isset($shift_details) && ($shift_details['start_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($shift_details) && !empty($shift_details['start_time']))?$shift_details['start_time']:"";}?>">
												
											</div>
										</div>
									</div>
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('max_start_time');?> <span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="max_start_time" id="max_start_time" value="<?php  if(isset($shift_details) && ($shift_details['max_start_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($shift_details) && !empty($shift_details['max_start_time']))?$shift_details['max_start_time']:"";}?>">
												
											</div>
										</div>
									</div> -->
																	
								<!-- </div>
								<div class="row"> -->
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('min_end_time');?><span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="min_end_time" id="min_end_time" value="<?php if(isset($shift_details) && ($shift_details['min_end_time'] == '00:00:00')){
													echo '';
												} else { echo (isset($shift_details) && !empty($shift_details['min_end_time']))?$shift_details['min_end_time']:"";}?>">
												 
											</div>											
										</div>
									</div> -->
									<div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('end_time');?><span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="end_time" id="end_time" value="<?php if(isset($shift_details) && ($shift_details['end_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($shift_details) && !empty($shift_details['end_time']))?$shift_details['end_time']:"";}?>">
												
											</div>												
										</div>
									</div>
									<!-- <div class="col-md-4">
										<div class="form-group">
											<label><?php echo lang('max_end_time');?> <span class="text-danger">*</span></label>
											<div class='time-icon date time_picker'>
												<input type="text" class="form-control" name="max_end_time" id="max_end_time" value="<?php if(isset($shift_details) && ($shift_details['max_end_time'] == '00:00:00')){
													echo '';
												} else {  echo (isset($shift_details) && !empty($shift_details['max_end_time']))?$shift_details['max_end_time']:"";}?>">
												
											</div>											
										</div>
									</div>-->
									
								</div> 
								<div class="form-group">
									<label><?php echo lang('break_time');?></label>
									<div class='input-group'>
										<input type="text" class="form-control" name="break_time" id="break_time" value="<?php if(isset($shift_details) && ($shift_details['break_time'] == '00:00:00')){
											echo '';
										} else {  echo (isset($shift_details) && !empty($shift_details['break_time']))?$shift_details['break_time']:"";}?>">
										
									</div>											
								</div>	
								<div class="form-group">
									<label><?php echo lang('color');?> </label>

									 <input type="color" class="form-control" name="color" value="<?php echo (isset($shift_details) && !empty($shift_details['color']))?$shift_details['color']:"#235ca5";?>">

									<!-- <select name="color" class="form-control">
										<option value=""><?php echo lang('select_color')?></option>
										<option value="#f0092c">Red</option>
										<option value="#083ef0" selected>Blue</option>
										<option value="#f0cd05">Yellow</option>
										<option value="#05f024">Green</option>
										<option value="#f0f5f1">White</option>
									</select> -->
								</div>
								<div class="form-group hide">
									<div class="checkbox">
									  <label><input class="mr-2" type="checkbox"  name="recurring_shift" id="recurring_shift" value="1" <?php echo (isset($shift_details['recurring_shift']) && ($shift_details['recurring_shift'] ==1))?"checked":"";?>><?php echo lang('recurring_shift');?></label>
									</div>
									<div class="checkbox ">
									  <label><input class="mr-2" type="checkbox"  name="cyclic_shift" id="cyclic_shift" value="1" <?php echo (isset($shift_details['cyclic_shift']) && ($shift_details['cyclic_shift'] ==1))?"checked":"";?>><?php echo lang('cyclic_shift');?></label>
									</div>
								</div>
								
								<!--  -->	
								<div id="recurring" class="<?php echo (isset($shift_details['recurring_shift']) && ($shift_details['recurring_shift'] ==1 ))?"":"hide";?> hide">
									<!-- <div class="form-group">
										<label><?php echo lang('repeat_every');?></label>
										<select class="select form-control recurring" name="repeat_week" id="repeat_week">
											<option value="1" <?php echo ($shift_details['repeat_week'] ==1)?"selected":"";?>>1</option>
											<option value="2" <?php echo ($shift_details['repeat_week'] ==2)?"selected":"";?>>2</option>
											<option value="3" <?php echo ($shift_details['repeat_week'] ==3)?"selected":"";?>>3</option>
											<option value="4" <?php echo ($shift_details['repeat_week'] ==4)?"selected":"";?>>4</option>
										</select>
										<label><?php echo lang('week');?>(s)</label>
									</div>	 -->	
									<?php $weekdays = explode(',',$shift_details['week_days']);?>
									<div class="form-group wday-box">
										<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="monday" class="days recurring" <?php echo in_array('monday', $weekdays)?"checked":"" ;?>><span class="checkmark">M</span></label>
	    
	   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="tuesday" class="days recurring" <?php echo in_array('tuesday', $weekdays)?"checked":"" ;?>><span class="checkmark">T</span></label>
									   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="wednesday" class="days recurring" <?php echo in_array('wednesday', $weekdays)?"checked":"" ;?>><span class="checkmark">W</span></label>
									   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="thursday" class="days recurring" <?php echo in_array('thursday', $weekdays)?"checked":"" ;?>><span class="checkmark">T</span></label>
									    
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="friday" class="days recurring" <?php echo in_array('friday', $weekdays)?"checked":"" ;?>><span class="checkmark">F</span></label>
									   
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="saturday" class="days recurring" <?php echo in_array('saturday', $weekdays)?"checked":"" ;?>><span class="checkmark">S</span></label>
									  
								      	<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="sunday" class="days recurring" <?php echo in_array('sunday', $weekdays)?"checked":"" ;?>><span class="checkmark">S</span></label>
									</div>	
									
									
									<div class="form-group week_days">
										
										
									</div>
								</div>
								<div class="form-group indefinite">
										<div class="checkbox">
										  <label><input class="mr-2" type="checkbox"  name="indefinite" id="indefinite1" value="1" <?php echo ($shift_details['indefinite'] =='1')?"checked":"";?>><?php echo lang('indefinite');?></label>
										</div>
									</div>
								<div class="form-group recurring_end_date <?php echo ($shift_details['cyclic_shift'] ==1)?"":""?>">
									<label><?php echo lang('end_on');?> <span class="text-danger">*</span></label>
									<div class="cal-icon">
										<input class="datepicker-schedule form-control" name="end_date" id="end_date1" data-date-format="dd-mm-yyyy" value="<?php echo (isset($shift_details['end_date']) && ($shift_details['end_date'] =='0000-00-00'))?'':date('d-m-Y',strtotime($shift_details['end_date']));?>" placeholder="DD/MM/YYYY"  <?php echo ($shift_details['indefinite'] =='1')?"disabled":"";?>>
									</div>
								</div>	
								<div class="form-group total_cyclic_days <?php echo ($shift_details['cyclic_shift'] ==1)?"":"hide"?>">
									<label><?php echo lang('no_of_days_in_cycle');?> </label>
									<input class="form-control" type="text" name="no_of_days_in_cycle" id="total_cyclic_days" value="<?php echo $shift_details['no_of_days_in_cycle']?>" />
								</div>	
								<div class="form-group wday-box cyclic_days">
									<?php
									  if($shift_details['cyclic_shift'] ==1){
									for ($i=1; $i < $shift_details['no_of_days_in_cycle']+1; $i++) { ?>
										
											<label class="checkbox-inline "><input type="checkbox" name="workdays[]" value="<?php echo $i;?>" class="days recurring" <?php echo ($shift_details['workday'] >= $i)?"checked":""?> ><span class="checkmark"><?php echo $i;?></span></label>	
										
										
									<?php } } ?>
								</div>

															
								<div class="form-group">
									<label><?php echo lang('add_a_tag');?> </label>
									<input class="form-control" type="text" data-role="tagsinput" name="tag" id="tag" value="<?php echo (isset($shift_details) && !empty($shift_details['tag']))?$shift_details['tag']:"";?>" />
								</div>
								<div class="form-group">
									<label><?php echo lang('add_a_note');?></label>
									<textarea class="form-control" rows="4" name="note" id="note"><?php echo (isset($shift_details) && !empty($shift_details['note']))?$shift_details['note']:"";?></textarea>
								</div>
								<!-- <div class="form-group">
									<label>Publish</label>
									<div class="material-switch">
										<input id="someSwitch" class="form-control" name="publish" type="checkbox"/ checked value="1">
										<label for="someSwitch" class="label-warning"></label>
									</div>
								</div> -->

								<!-- <div class="form-group">
                                        <label class="d-block"><?php echo lang('publish');?></label>
                                        <div class="status-toggle">
                                            <input type="checkbox" id="contact_status" name="publish" class="check" <?php echo ($shift_details['published'] == 1)?"checked":"";?> value="1">
                                            <label for="contact_status" class="checktoggle"><?php echo lang('checkbox');?></label>
                                        </div>
                                    </div> -->
								<div class="submit-section">
									<a href="<?php echo base_url(); ?>shift_scheduling/shift_list" class="btn btn-danger bg-orange text-white submit-btn m-b-5" type="submit"><?php echo lang('cancel');?></a>
									<button class="btn btn-primary submit-btn m-b-5" id="submit_scheduling_add" type="submit"><?php echo lang('save');?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>