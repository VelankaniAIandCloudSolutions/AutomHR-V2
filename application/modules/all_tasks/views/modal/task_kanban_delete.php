<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?='Delete Task Board'?></h4>
		</div>
		<?php echo form_open(base_url().'all_tasks/task_kanban_delete'); ?>
			<div class="modal-body">
				<p><?='This action will delete Task from List. Proceed?'?></p>
				<input type="hidden" name="task_id" value="<?=$task_id?>"> 
				<input type="hidden" name="project_id" value="<?=$project_id?>"> 
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>
		</form>
	</div>
</div>