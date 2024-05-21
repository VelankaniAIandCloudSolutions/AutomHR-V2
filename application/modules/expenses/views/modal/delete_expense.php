<?php
$e = Expense::view_by_id($expense);
$p = ($e->project > 0) ? Project::by_id($e->project) : NULL;
$cur = ($p != NULL) ? $p->currency : Client::view_by_id($e->client)->currency;
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('delete_expense')?> <?=Applib::format_currency($cur, $e->amount)?></h4>
		</div>
		<?php echo form_open(base_url().'expenses/delete'); ?>
			<div class="modal-body">
				<p><?=lang('delete_expense_warning')?></p>
				<input type="hidden" name="expense" value="<?=$expense?>">
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>
		</form>
	</div>
</div>