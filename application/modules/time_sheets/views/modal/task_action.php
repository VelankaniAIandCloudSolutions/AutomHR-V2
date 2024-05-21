<?php $action = (isset($action)) ? $action : ''; ?>
<?php if($action == 'add_task') { ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_task')?></h4>
		</div>
	<style>
.datepicker{ z-index:1151 !important; }

</style>
<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input id="hidden-task-name" type="hidden" name="task_name">
					<input id="auto-task-name" type="text" class="typeahead form-control" placeholder="Task Name" name="task_name_auto">
				</div>
				</div>
<?php if(!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone')?></label>
				<div class="col-lg-8">
				<select name="milestone" class="form-control">
				<option value="0"><?=lang('none')?></option>
				<?php foreach (Project::has_milestones($project) as $m) { ?>
						<option value="<?=$m->id?>"><?=$m->milestone_name?></option>
					<?php } ?>
				</select>
				</div>
				</div>
<?php } ?>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?></label>
				<div class="col-lg-8">
				<textarea id="auto-description" name="description" class="form-control ta" placeholder="<?=lang('description')?>"></textarea>
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
				<label class="col-lg-4 control-label"><?=lang('start_date')?> </label>
				<div class="col-lg-8">
				<input class="form-control" id="add_task_date_start" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="form-control" id="add_task_date_due" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> </label>
				<div class="col-lg-8">
					<input id="auto-estimate" type="text" class="form-control" placeholder="100" name="estimate">
				</div>
				</div>

<?php if(!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control">
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
                          <input type="checkbox" name="visible" checked="checked">
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

    var substringMatcher = function(strs) {
      return function findMatches(q, cb) {
        var substrRegex;
        var matches = [];
        substrRegex = new RegExp(q, 'i');
        $.each(strs, function(i, str) {
          if (substrRegex.test(str)) {
            matches.push(str);
          }
        });
        cb(matches);
      };
    };

    $('#auto-task-name').on('keyup',function(){ $('#hidden-task-name').val($(this).val()); });

    $.ajax({
        url: base_url + 'projects/tasks/autotasks/',
        type: "POST",
        data: {},
        success: function(response){
            $('.typeahead').typeahead({
                hint: true,
                highlight: true,
                minLength: 2
                },
                {
                name: "task_name",
                limit: 10,
                source: substringMatcher(response)
            });
            $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                $.ajax({
                    url: base_url + 'projects/tasks/autotask/',
                    type: "POST",
                    data: {name: suggestion},
                    success: function(response){
                        $('#hidden-task-name').val(response.task_name);
                        $('#auto-description').val(response.description);
                        $('#auto-estimate').val(response.hours);
                    }
                });
            });
        }
    });

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

<?php if($action == 'edit_task') { ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_task')?></h4>
		</div>
		<style>
.datepicker{z-index:1151 !important;}
</style>
		<?php $task = Project::view_task($id); ?>
		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/edit',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="task_id" value="<?=$task->t_id?>">
			 <input type="hidden" name="project" value="<?=$task->project?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$task->task_name?>" name="task_name">
				</div>
				</div>

<?php if(!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone')?></label>
				<div class="col-lg-8">
				<select name="milestone" class="form-control">
				<option value="0">Select One</option>
				<?php foreach (Project::has_milestones($project) as $m) { ?>
						<option value="<?=$m->id?>"<?=($task->milestone == $m->id ? ' selected="selected"' : '')?>><?=$m->milestone_name?></option>
					<?php } ?>
				</select>
				</div>
				</div>
<?php } ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control ta"><?=$task->description?></textarea>
				</div>
				</div>
<?php if(!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('progress')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
                   <div id="progress-slider"></div>
	          		<input id="progress" type="hidden" value="<?=$task->task_progress?>" name="progress"/>
				</div>
				</div>
<?php } ?>


				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?> </label>
				<div class="col-lg-8">
				<input class="form-control" id="edit_task_date_start" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($task->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>



				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="form-control" id="edit_task_date_due" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($task->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$task->estimated_hours?>" name="estimate">
				</div>
				</div>

	<?php if(User::is_admin()){ ?>

	<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control">
				<?php foreach (Project::project_team($project) as $u) { ?>
				 	<option value="<?=$u->assigned_user?>" <?php foreach (Project::task_team($id) as $user) {
				 		if ($user->assigned_user == $u->assigned_user) { ?> selected = "selected" <?php } } ?>>
				 		<?=ucfirst(User::displayName($u->assigned_user))?></option>
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
                          <input type="checkbox" name="visible" <?php if($task->visible == 'Yes'){ echo "checked=\"checked\""; }?>>
                          <span></span>
                        </label>
                      </div>
                    </div>



<?php } ?>






		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
	  
	  $('#edit_task_date_start').datepicker({
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
        $('#edit_task_date_due').datepicker('setStartDate', minDate);
        if($('#edit_task_date_start').val() > $('#edit_task_date_due').val())
        $('#edit_task_date_due').val('');
    });

    $('#edit_task_date_due').datepicker({
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




<?php if($action == 'add_from_template') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_task')?></h4>
		</div>
		<style>
.datepicker{ z-index:1151 !important; }
</style>

<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/add_from_template',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
			<p><?=lang('email_sending_warning')?></p>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('templates')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="template_id" class="form-control" required="required">
				<option value=""><?=lang('choose_template')?></option>
					<?php foreach (App::get_by_where('saved_tasks',array()) as $key => $task) { ?>
						<option value="<?=$task->template_id?>"><?=ucfirst($task->task_name)?></option>
					<?php } ?>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone')?></label>
				<div class="col-lg-8">
				<select name="milestone" class="form-control">
				<option value="0">None</option>
					<?php
					foreach (Project::has_milestones($project) as $key => $m) { ?>
						<option value="<?=$m->id?>"><?=ucfirst($m->milestone_name)?></option>
					<?php } ?>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?> </label>
				<div class="col-lg-8">
				<input class="form-control" id="temp_task_date_start" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>


				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="form-control" id="temp_task_date_due" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

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
      $('#temp_task_date_start').datepicker({
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
        $('#temp_task_date_due').datepicker('setStartDate', minDate);
        if($('#temp_task_date_start').val() > $('#temp_task_date_due').val())
        $('#temp_task_date_due').val('');
    });

    $('#temp_task_date_due').datepicker({
   // autoclose: true
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



<?php } ?>


<?php if($action == 'task_add_file') {  ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('upload_file')?></h4>
		</div>

					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'projects/tasks/file',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
           <input type="hidden" name="task" value="<?=$task?>">
		<div class="modal-body">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" placeholder="<?=lang('file_title')?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>" ></textarea>
                    </div>
                </div>

                <div id="file_container">
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                        <div class="col-lg-8">
							<label class="btn btn-default btn-choose">Choose File</label>                  
                            <input type="file" class="form-control" name="taskfiles[]">
                        </div>
                    </div>
                </div>

		</div>
		<div class="modal-footer">
                    <a href="#" class="btn btn-success pull-left" id="add-new-file"><?=lang('upload_another_file')?></a>
                    <a href="#" class="btn btn-danger pull-left" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('upload_file')?></button>
		</form>
		</div>
	</div>

    <script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<div class='col-lg-offset-3 col-lg-9'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='taskfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                "<div class='col-lg-offset-3 col-lg-9'>" +
                "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='taskfiles[]'>" +
                "</div></div>"
            );
        });
    </script>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'task_edit_file') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_file')?></h4>
		</div>

		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'projects/tasks/file/edit',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
          <input type="hidden" name="task" value="<?=$task?>">
          <input type="hidden" name="file_id" value="<?=$file_id?>">
		<div class="modal-body">
                    <?php   $icon = $this->applib->file_icon($f->file_ext);
                            $real_url = base_url().$fullpath; ?>

                <div class="form-group">
                    <div class="col-lg-3">
                    <?php if ($f->is_image == 1) : ?>
                    <?php if ($f->image_width > $f->image_height) {
                        $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                        $style = 'height:100%; margin-left: -'.$ratio.'%';
                    } else {
                        $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                        $style = 'width:100%; margin-top: -'.$ratio.'%';
                    }  ?>
                        <div class="file-icon icon-large pull-right">
                            <a href="<?=base_url()?>projects/files/preview/<?=$f->file_id?>/<?=$project?>" data-toggle="ajaxModal">
                            <img style="<?=$style?>" src="<?=$real_url?>" /></a>
                        </div>
                    <?php else : ?>
                        <div class="file-icon icon-large pull-right"><i class="fa <?=$icon?> fa-5x"></i></div>
                    <?php endif; ?>
                    </div>
                    <div class="col-lg-9">
                    <table class="table table-striped table-small">
                        <tbody>
                            <tr><td class="col-lg-3"><?=lang('file_name');?></td><td><?=$f->file_name;?></td></tr>
                            <tr><td><?=lang('size');?></td><td><?=$f->size;?></td></tr>
                            <?php if($f->is_image == 1) : ?>
                            <tr><td><?=lang('dimensions');?></td><td><?=$f->image_width;?>x<?=$f->image_height;?></td></tr>
                            <?php endif; ?>
                            <tr><td><?=lang('date');?></td><td><?=strftime(config_item('date_format')." %H:%M", strtotime($f->date_posted));?></td></tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" value="<?=$f->title;?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control ta" ><?=$f->description;?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
                    <button type="submit" class="btn btn-success"><?=lang('save_file')?></button>
		</form>
		</div>
	        </div>
        </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>

<?php if($action == 'task_delete_file') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_file')?></h4>
		</div><?php
			echo form_open(base_url().'projects/tasks/file/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_file_warning')?></p>

			<input type="hidden" name="file_id" value="<?=$file_id?>">
                        <input type="hidden" name="task" value="<?=$task?>">
			<input type="hidden" name="project" value="<?=$project?>">

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


<?php if($action == 'preview_task_file') { ?>


<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=$file->title?></h4>
        </div>
        <div class="modal-body">
            <img class="img-responsive" width="<?=$file->image_width?>" src="<?=base_url()?><?=$file_path?>"
                 alt="<?=$file->original_name?>"/>
        </div>
    </div>
</div>



<?php } ?>



<?php if($action == 'delete_task') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_task')?></h4>
		</div><?php
			echo form_open(base_url().'projects/tasks/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_task_warning')?></p>

			<input type="hidden" name="task_id" value="<?=$task_id?>">
			<input type="hidden" name="project" value="<?=$project?>">

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

<?php if($action == 'delete_task_comment') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_comment')?></h4>
		</div><?php
			echo form_open(base_url().'projects/tasks/delete_comment'); ?>
		<div class="modal-body">
			<p><?=lang('delete_message_warning')?></p>

			<input type="hidden" name="comment_id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-primary"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>
