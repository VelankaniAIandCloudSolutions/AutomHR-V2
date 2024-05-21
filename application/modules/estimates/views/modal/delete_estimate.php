<?php $estimate = Estimate::view_estimate($id); ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('delete_estimate')?> <?php echo $estimate->reference_no;?> - <?=Applib::format_currency($estimate->currency,Estimate::due($estimate->est_id)); ?></h4>
		</div>
		<?php echo form_open(base_url().'estimates/delete'); ?>
			<div class="modal-body">
				<p><?=lang('delete_estimate_warning')?></p>
				<input type="hidden" name="estimate" value="<?=$id?>">
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>
		</form>
	</div>
</div>