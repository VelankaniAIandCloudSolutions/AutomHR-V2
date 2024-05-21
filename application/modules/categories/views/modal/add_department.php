<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Add Department</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php $attributes = array('class' => 'bs-example','id'=> 'settingsDepartmentForm'); echo form_open_multipart('all_departments/departments', $attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('department_name')?> <span class="text-danger">*</span></label>
					<input type="text" name="deptname" class="form-control" required>
				</div>
				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>