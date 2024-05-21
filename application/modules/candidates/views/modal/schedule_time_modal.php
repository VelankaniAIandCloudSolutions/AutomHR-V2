<!-- Modal -->

  <div class="modal-dialog  modal-dialog-centered">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo lang('select_your_time');?></h4>
      </div>
      	<form action="<?php echo site_url('candidates/schedule_interview_time/'.$this->uri->segment(3));?>" method="post" id="user_schedule_form">
      <div class="modal-body">
       <div class="row">
       <?php 
       $time_display = array();
       foreach ($time_list as $key => $time) {
       	$time_display[$key] = $time;
       }
       ?>
			<div class="col-sm-12">
				<div class="form-group">
					<label>Day1 <span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;" name="user_date[0]" id="userdate1">
						<option value=""><?php echo lang('select_time')?></option>
						<?php 
						foreach($schedule_timings[0] as $key => $times){?>
						<option value="<?php echo $times;?>" <?php if(isset($user_selected_timing[0]) && $user_selected_timing[0] == $times) { echo "selected";}?>><?php echo $time_display[$times];?> (<?php echo date('d M Y',strtotime($schedule_dates[0]));?>) </option>
					<?php } ?>
						
					</select>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label>Day 2 <span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;" name="user_date[1]" id="userdate2">
					
						<option value=""><?php echo lang('select_time')?></option>
						<?php foreach($schedule_timings[1] as $key => $times) { ?>
						<option value="<?php echo $times;?>" <?php if(isset($user_selected_timing[1]) && $user_selected_timing[1] == $times) { echo "selected";}?>><?php echo $time_display[$times];;?> (<?php echo date('d M Y',strtotime($schedule_dates[1]));?>) </option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label>Day 3 <span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;" name="user_date[2]" id="userdate3">
						<option value=""><?php echo lang('select_time')?></option>
						<?php foreach($schedule_timings[2] as $key => $times) { ?>
						<option value="<?php echo $times;?>" <?php if(isset($user_selected_timing[2]) && $user_selected_timing[2] == $times) { echo "selected";}?>><?php  echo $time_display[$times];;?> (<?php echo date('d M Y',strtotime($schedule_dates[2]));?>) </option>
					<?php } ?>
					</select>
				</div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close');?></button>
         <input type="submit" class="btn btn-success" name="submit" value="<?php echo lang('submit');?>" id="user_schedule_btn">
      </div>

    </div>
      </form>

  </div>

