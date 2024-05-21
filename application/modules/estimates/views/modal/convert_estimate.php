<?php $estimate = Estimate::view_estimate($id); ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('convert_to_invoice')?> <?php echo $estimate->reference_no;?> - <?=Applib::format_currency($estimate->currency,Estimate::due($estimate->est_id)); ?></h4>
		</div>
		<?php echo form_open(base_url().'estimates/convert'); ?>
			<div class="modal-body">
				<p>A new Invoice will be created for Estimate <?=$estimate->reference_no?></p>
				<input type="hidden" name="id" value="<?=$id?>">
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('convert_to_invoice')?></button>
			</div>
		</form>
	</div>
</div>