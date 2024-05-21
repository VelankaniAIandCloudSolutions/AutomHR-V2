<div class="modal-dialog">
	<div class="modal-content">
		<?php $invoice = Invoice::view_by_id($id); ?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('invoice_reminder')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'invoiceReminderForm'); echo form_open(base_url().'invoices/remind',$attributes); ?>
			<div class="modal-body">
				<input type="hidden" name="invoice_id" value="<?=$invoice->inv_id?>">
				<input type="hidden" name="client_name" value="<?=Client::view_by_id($invoice->client)->company_name?>">
				<input type="hidden" name="amount" value="<?=Applib::format_quantity(Invoice::get_invoice_due_amount($id))?>">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?php echo App::email_template('invoice_reminder','subject');?> <?=$invoice->reference_no?>" name="subject">
					</div>
				</div>
				<input type="hidden" name="message" class="hiddenmessage">
				<div class="message" contenteditable="false">
					<?php echo App::email_template('invoice_reminder','template_body');?>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button type="submit" class="submit btn btn-success" id="invoice_reminder_template"><?=lang('send_reminder')?></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$(function(){
    $('.submit').click(function () {
        var mysave = $('.message').html();
        $('.hiddenmessage').val(mysave);
    });
});
</script>