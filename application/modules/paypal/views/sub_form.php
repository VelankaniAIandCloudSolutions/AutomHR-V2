<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('new_payment')?></h4>
		</div>		
		<div class="modal-body">
			<p><?=lang('paypal_redirection_alert')?></p>

		<?php
			$attributes = array('name'=>'paypal_form','class' => 'bs-example form-horizontal');
                        echo form_open($paypal_url,$attributes);
                        $cur = App::currencies($info['currency']);
                ?>
					<input name="rm" value="2" type="hidden">
					<input name="cmd" value="_xclick" type="hidden">
					<input name="currency_code" value="<?=$info['currency']?>" type="hidden">
					<input name="quantity" value="1" type="hidden">
					<input name="business" value="<?=config_item('paypal_email')?>" type="hidden">
					<input name="return" value="<?=base_url()?><?=config_item('paypal_user_sub_success')?>" type="hidden">
					<input name="cancel_return" value="<?=base_url()?><?=config_item('paypal_user_sub_cancel')?>" type="hidden">
					<input name="notify_url" value="<?=base_url()?><?=config_item('paypal_sub_ipn_url')?>" type="hidden">
					<input name="custom" value="<?=$info['client'];?>" type="hidden">
					<input name="user_name" value="<?=$info['user_name']?>" type="hidden">
					<input name="user_id" value="<?=$info['user_id']?>" type="hidden">
			 <div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reference_no')?></label>
				<div class="col-lg-4">
					<input type="text" class="form-control" readonly value="<?=$info['user_name']?>">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_amount')?> (<?=$cur->symbol?>) </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" value="<?=number_format($info['amount'],2)?>" readonly>
				</div>
				</div>
          				
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('amount')?> (<?=lang('eg')?> 1200) </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" value="<?=round($info['amount'],2)?>" name="amount" >
				</div>
				</div>

				<img src="<?=base_url()?>assets/images/payment_american.png">
				<img src="<?=base_url()?>assets/images/payment_discover.png">
				<img src="<?=base_url()?>assets/images/payment_maestro.png">
				<img src="<?=base_url()?>assets/images/payment_paypal.png">

				<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-success"><?=lang('pay_invoice')?></button>
		</div>
				
			
		</div>
		
		</form>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->