<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('edit_rate')?></h4>
		</div>
		<?php $r = Invoice::tax_by_id($id);?>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'taxrateEditForm'); echo form_open(base_url().'invoices/tax_rates/edit',$attributes); ?>
			<input type="hidden" name="tax_rate_id" value="<?=$r->tax_rate_id?>">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('tax_rate_name')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=$r->tax_rate_name?>" name="tax_rate_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('tax_rate_percent')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=$r->tax_rate_percent?>" name="tax_rate_percent" id="edit_taxrate_percent">
						<div class="row">
						<div class="col-md-12">
						<label id="edit_taxrate_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="tax_rate">Entered Percentage is invalid</label>
						<label id="edit_taxrate_required" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="tax_rate">Tax Percentage is required</label>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button type="submit" class="btn btn-success" id="taxrate_edit_submit"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>
<script>
	$(function() {
		$('.money').maskMoney();
	})
</script>