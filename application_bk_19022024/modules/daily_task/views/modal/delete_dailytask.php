<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('delete_dailytask')?></h4>
		</div>
		<?php echo form_open(base_url().'daily_task/delete_dailytask'); ?>
			<div class="modal-body">
				<p><?=lang('delete_branch_warning')?></p>
				<input type="hidden" name="dailytask_id" value="<?php echo $dailytask_id; ?>">
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>
		</form>
	</div>
</div>