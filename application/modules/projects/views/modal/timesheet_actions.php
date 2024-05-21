<?php $action = (isset($action)) ? $action : ''; ?>
<?php if($action == 'add_timesheet') { ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Add Timesheet</h4>
		</div>
	<style>
.datepicker{ z-index:1151 !important; }

</style>
<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'projectAddTask');
          echo form_open(base_url().'projects/tasks/timesheet_add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
          <input type="hidden" name="task_id" value="<?=$task_id?>">
		<div class="modal-body">
          		<div class="form-group">
				<label class="col-lg-4 control-label">Hours<span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input id="auto-task-name" type="text" class="typeahead form-control" placeholder="00:00" name="timesheet_hours" id="timesheet_hours" required>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea id="timesheet_description" name="timesheet_description" id="timesheet_description" class="form-control ta" placeholder="<?=lang('description')?>" required ></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label">Date <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<input class="form-control TimeSheetDate" id="timesheet_date" required type="text" value="<?=strftime(config_item('date_format'), time());?>" name="timesheet_date" data-date-format="dd-mm-yyyy" >
				</div>
				</div>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button class="btn btn-success" id="timesheet_add">Add Timesheet</button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
    $('.TimeSheetDate').datepicker();
    </script>



<?php } ?>
