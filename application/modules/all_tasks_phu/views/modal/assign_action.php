<div class="modal-dialog" id="assign_modl">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Assign Task</h4>
		</div>
		<div class="modal-body">
			<form id="assigned_task" method="post" action="#">
				<input type="hidden" name="project" id="project" value="<?=$project?>">
				<input type="hidden" name="task" id="task" value="<?=$task_id?>">
				<input type="hidden" name="type" id="type" value="<?=$action?>">
      <?php    

          if($action=='Due')
        {
            ?>
				<div class="form-group">
					<label><?=lang('due_date')?></label>
					<div class="cal-icon">
                        <input type="hidden"  id="assigned_to" value="">
						<input class="form-control" id="add_task_date_due" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
					</div>
				</div>
<?php
}
				if($action=='Assign')
				{
				?>
				<?php if(!User::is_client()){ ?>
				<div class="form-group">
					<label><?=lang('assigned_to')?></label>
                    <input type="hidden"  id="add_task_date_due" value="">
					<select name="assigned_to" id="assigned_to" class="form-control">
						<?php foreach (Project::project_team($project) as $user) { ?>
						<option value="<?=$user->assigned_user?>"><?=User::displayName($user->assigned_user)?></option>
						<?php } ?>
					</select>
				</div>
				<?php } } ?>
				<div class="submit-section">
					<button type="button" data-dismiss="modal" class="btn btn-primary submit-btn" onclick="assign_form_submit()" id="project_assign_tasks">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
    $('#add_task_date_start').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#add_task_date_due').datepicker('setStartDate', minDate);
        if($('#add_task_date_start').val() > $('#add_task_date_due').val())
        $('#add_task_date_due').val('');
    });

    $('#add_task_date_due').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });
</script>