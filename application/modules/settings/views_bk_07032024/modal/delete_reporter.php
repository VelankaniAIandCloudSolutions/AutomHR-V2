<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title">Delete Reporter</h4>
		</div><?php $attributes = array('class' => 'bs-example form-horizontal','method' => 'post');
			echo form_open(base_url().'settings/lead_reporter_delete/'.$r_id); ?>
		<div class="modal-body">
			<p>This action will delete Reporter from List. Proceed?</p>
 			<input type="hidden" name="reporter_id" value="<?=$r_id?>"> 
 		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->