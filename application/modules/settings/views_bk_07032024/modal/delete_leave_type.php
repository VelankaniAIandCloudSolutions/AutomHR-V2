<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title">Delete Leave Type</h4>
		</div><?php
			echo form_open(base_url().'leave_settings/delete_leave_types'); ?>
		<div class="modal-body">
			<p>This action will delete Leave Type from List. Proceed?</p>
 			<input type="hidden" name="leave_type_id" value="<?=$leave_type_id?>"> 
 		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->