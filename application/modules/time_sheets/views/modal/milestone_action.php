<?php if($action == 'add') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('add_milestone')?></h4>
		</div>
<style>
.datepicker{z-index:1151 !important;}
</style>
	<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'timesheetAddMilestone');
          echo form_open(base_url().'projects/milestones/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">

          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('milestone_name')?>" name="milestone_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>"></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<input class="form-control" type="text" id="add_milestone_date_start" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >

				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<input class="form-control" type="text" id="add_milestone_date_due" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>


		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success" id="timesheet_add_milestone"><?=lang('add_milestone')?></button>
		</form>
		</div>
	</div>

<script type="text/javascript">
        $('#add_milestone_date_start').datepicker({
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
        $('#add_milestone_date_due').datepicker('setStartDate', minDate);
        if($('#add_milestone_date_start').val() > $('#add_milestone_date_due').val())
        $('#add_milestone_date_due').val('');
    });

    $('#add_milestone_date_due').datepicker({
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



	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<?php } ?>

<?php if($action == 'edit') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">
		<?=lang('edit_milestone')?></h4>
		</div>
		<?php if(User::is_admin() || Project::is_assigned(User::get_id(),$m->project)){ ?>

		<?php $attributes = array('class' => 'bs-example form-horizontal', 'id' => 'TimesheetEditMilestone');
          echo form_open(base_url().'projects/milestones/edit',$attributes); ?>
          <input type="hidden" name="project" value="<?=$m->project?>">
          <input type="hidden" name="id" value="<?=$m->id?>">
		<div class="modal-body">

          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$m->milestone_name?>" name="milestone_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control ta"><?=$m->description?></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" id="edit_milestone_date_start" value="<?=strftime(config_item('date_format'), strtotime($m->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" id="edit_milestone_date_due" value="<?=strftime(config_item('date_format'), strtotime($m->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success" id="timesheet_edit_milestone"><?=lang('save_changes')?></button>
		</form>
		<?php } ?>
		</div>
<script type="text/javascript">
        
    $('#edit_milestone_date_start').datepicker({
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
        $('#edit_milestone_date_due').datepicker('setStartDate', minDate);
        if($('#edit_milestone_date_start').val() > $('#edit_milestone_date_due').val())
        $('#edit_milestone_date_due').val('');
    });

    $('#edit_milestone_date_due').datepicker({
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

	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<?php } ?>

<?php if($action == 'add_milestone_task') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_task')?></h4>
		</div>
	<style>
.datepicker{z-index:1151 !important;}
</style>
<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
          <input type="hidden" name="milestone" value="<?=$milestone?>">
		<div class="modal-body">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('task_name')?>" name="task_name" required>
				</div>
				</div>


				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>"></textarea>
				</div>
				</div>

<?php if(!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('progress')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
                                <div id="progress-slider"></div>
	          		<input id="progress" type="hidden" value="0" name="progress"/>
				</div>
				</div>
<?php } ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="100" name="estimate">
				</div>
				</div>

<?php if(!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control" required>
				<option value="-"><?=lang('not_assigned')?></option>
				<?php foreach (Project::project_team($project) as $user) { ?>
				<option value="<?=$user->assigned_user?>"><?=User::displayName($user->assigned_user)?></option>
				<?php } ?>
				</select>
				</div>
				</div>
<?php } ?>

<?php if(!User::is_client()){ ?>
				<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('visible_to_client')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="visible">
                          <span></span>
                        </label>
                      </div>
                    </div>



<?php } ?>






		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('add_task')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
        $('.datepicker-input').datepicker({ language: locale});
    var progress = $('#progress').val();
    $('#progress-slider').noUiSlider({
            start: [ progress ],
            step: 10,
            connect: "lower",
            range: {
                'min': 0,
                'max': 100
            },
            format: {
                to: function ( value ) {
                    return Math.floor(value);
                },
                from: function ( value ) {
                    return Math.floor(value);
                }
            }
    });
    $('#progress-slider').on('slide', function() {
        var progress = $(this).val();
        $('#progress').val(progress);
        $('.noUi-handle').attr('title', progress+'%').tooltip('fixTitle').parent().find('.tooltip-inner').text(progress+'%');
    });

    $('#progress-slider').on('change', function() {
        var progress = $(this).val();
        $('#progress').val(progress);
    });

    $('#progress-slider').on('mouseover', function() {
        var progress = $(this).val();
        $('.noUi-handle').attr('title', progress+'%').tooltip('fixTitle').tooltip('show');
    });
    </script>

<?php } ?>


<?php if($action == 'delete') { ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_milestone')?></h4>
		</div><?php
			echo form_open(base_url().'projects/milestones/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_milestone_warning')?></p>

			<input type="hidden" name="project" value="<?=$project?>">
			<input type="hidden" name="id" value="<?=$milestone?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->




<?php } ?>
