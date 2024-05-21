<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
           
			<h4 class="modal-title"><?php echo $form_type; ?> <?php echo lang('schedule_timings');?></h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example','id'=>"add_schedule_timing"); echo form_open_multipart('jobs/add_schedule_timing/'.$this->uri->segment(3).'/'.$this->uri->segment(4), $attributes); 
			?>
			<div class="modal-body">


				<div class="form-group">
					<label><?php echo lang('schedule_date1');?> <span class="text-danger">*</span></label>
					<input type="text" name="schedule_date[0]" class="form-control datetimepicker" id="schedule_date1" value="<?php if(isset($schedule_dates[0])) {echo date('d/m/Y',strtotime($schedule_dates[0]));} ?>" required data-date-format="dd/mm/yyyy">
				</div>

				<div class="form-group">
					<label value=""><?php echo lang('select_time');?></label>
					
					<select class="select2-option form-control" name="schedule_time[0][]" required multiple> 
						<option value=""><?php echo lang('select_time')?></option>
						<?php
						foreach ($time_list as $key => $time) {
						?><option value="<?php echo $key;?>" <?php if(in_array($key,$available_timings[0])){ echo "selected";}?>><?php echo $time; ?></option><?php 
						} ?>

					</select>
				</div>
				<div class="form-group">
					<label><?php echo lang('schedule_date2');?> <span class="text-danger">*</span></label>
					<input type="text" name="schedule_date[1]" class="form-control datetimepicker" id="schedule_date2" value="<?php if(isset($schedule_dates[1])) {echo date('d/m/Y',strtotime($schedule_dates[1]));} ?>" required data-date-format="dd/mm/yyyy">
				</div>

				<div class="form-group">
					<label value=""><?php echo lang('select_time');?></label>
					<select class="select2-option form-control" name="schedule_time[1][]" required multiple> 
						<option value=""><?php echo lang('select_time')?></option>
						<?php
						foreach ($time_list as $key => $time) {
						?><option value="<?php echo $key;?>" <?php if(in_array($key,$available_timings[1])){ echo "selected";}?>><?php echo $time; ?></option><?php 
						} ?>

					</select>
				</div>
				<div class="form-group">
					<label><?php echo lang('schedule_date3');?> <span class="text-danger">*</span></label>
					<input type="text" name="schedule_date[2]" class="form-control datetimepicker" id="schedule_date3" value="<?php if(isset($schedule_dates[2])) {echo date('d/m/Y',strtotime($schedule_dates[2]));} ?>" required data-date-format="dd/mm/yyyy">
				</div>

				<div class="form-group">
					<label value=""><?php echo lang('select_time');?></label>
					<select class="select2-option form-control" name="schedule_time[2][]" required multiple> 
						<option value=""><?php echo lang('select_time')?></option>
						<?php
						foreach ($time_list as $key => $time) {
						?><option value="<?php echo $key;?>" <?php if(in_array($key,$available_timings[2])){ echo "selected";}?>><?php echo $time; ?></option><?php 
						} ?>

					</select>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn" id="add_schedule_timing_btn" type="submit" name="submit" value="submit"><?php echo lang('submit'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>