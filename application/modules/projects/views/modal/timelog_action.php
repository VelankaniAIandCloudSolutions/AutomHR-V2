<?php $action = (isset($action)) ? $action : ''; ?>



<?php if($action == 'add_time') { ?>




<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('time_entry')?></h4>
		</div>

		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url().'projects/timesheet/add_time',$attributes); ?>
		<input type="hidden" name="project" value="<?=$project?>">
		<input type="hidden" name="cat" value="<?=$cat?>">
		<div class="modal-body">
			<?php
			if ($cat == 'tasks') { ?>
			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<select name="task" class="form-control">
						<?php foreach (Project::has_tasks($project) as $key => $t) {  ?>
						<option value="<?=$t->t_id?>"><?=$t->task_name?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php } ?>

			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('time_spent')?></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" placeholder="5:30:00" name="spent_time">
				</div>
				<span class="help-block text-dark small"><strong>HH:MM:SS</strong> e.g 4:00:00 (4hrs)</span>
				</div>




			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?></label>
				<div class="col-lg-8">
				<textarea id="auto-description" name="description" class="form-control ta" placeholder="<?=lang('description')?>"></textarea>
				</div>
				</div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('time_entry')?></button>
	</form>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>


<?php if($action == 'edit_time') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('time_entry')?></h4>
		</div>

					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/timesheet/edit',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
          <input type="hidden" name="cat" value="<?=$cat?>">
          <input type="hidden" name="timer_id" value="<?=$timer_id?>">
		<div class="modal-body">
		<?php
		if ($cat == 'tasks') { ?>
			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="task" class="form-control">
				<option value="<?=$i->task?>"><?=Project::view_task($i->task)->task_name; ?></option>
				<?php foreach (Project::has_tasks($project) as $key => $t) {  ?>
					<option value="<?=$t->t_id?>"><?=$t->task_name?></option>
				<?php } ?>
				</select>
				</div>
				</div>
		<?php } ?>

		<?php $cat = ($cat == 'projects') ? 'project' : 'tasks'; ?>

		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('time_spent')?></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=Applib::gm_sec(Project::logged_time($cat,$i->timer_id));?>" name="spent_time">
				</div>
				<span class="help-block text-dark small"><strong>HH:MM:SS</strong> e.g 4:00:00 (4hrs)</span>
		</div>


                    <div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?></label>
				<div class="col-lg-8">
				<textarea id="auto-description" name="description" class="form-control ta"><?=$i->description?></textarea>
				</div>
				</div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('time_entry')?></button>

		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<!-- /.modal-dialog -->


<?php } ?>



<?php if($action == 'timer_description') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header ">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('description')?></h4>
		</div>
		<div class="modal-body">
		<?php if($description == ''){ ?>
			<p><?=lang('nothing_to_display')?></p>
		<?php }else{ ?>
			<p><?=nl2br_except_pre($description)?></p>
		<?php } ?>


		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>


<?php if($action == 'delete_time') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_time')?></h4>
		</div><?php
			echo form_open(base_url().'projects/timesheet/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_time_warning')?></p>

			<input type="hidden" name="project" value="<?=$project?>">
			<input type="hidden" name="timer_id" value="<?=$timer_id?>">
			<input type="hidden" name="cat" value="<?=$cat?>">

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
