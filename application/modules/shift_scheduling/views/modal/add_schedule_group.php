<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"><?php echo lang('add_schedule_group'); ?></h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php $attributes = array('class' => 'bs-example','id'=> 'settingsDepartmentForm'); echo form_open_multipart('shift_scheduling/add_schedule_group', $attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?php echo lang('group_name'); ?><span class="text-danger">*</span></label>
					<input type="text" name="group_name" class="form-control" required>
					<input type="hidden" class="form-control" value="<?php echo $this->session->userdata('user_id');?>" name="created_by">
				</div>
				<div class="submit-section">
					<button class="btn btn-primary submit-btn"><?php echo lang('submit'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>