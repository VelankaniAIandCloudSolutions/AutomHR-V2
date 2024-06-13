<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Add Department</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php $attributes = array('class' => 'bs-example','id'=> 'settingsDepartmentForm'); echo form_open_multipart('all_departments/departments', $attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('department_name')?> <span class="text-danger">*</span> <span id="Already_depart" style="display: none; color:red;float: right;">Already Exists</span></label>
					<input type="text" name="deptname" class="form-control" id="add_department" placeholder="Department Name">
				</div>
				<div class="submit-section">
					<button class="btn btn-primary submit-btn" id="AddDepart">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>