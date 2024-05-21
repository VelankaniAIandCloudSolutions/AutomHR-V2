<script src="https://js.braintreegateway.com/js/braintree-2.21.0.min.js"></script>
    <script>
// We generated a client token for you so you can test out this code
// immediately. In a production-ready integration, you will need to
// generate a client token on your server (see section below).
var clientToken = "<?=$token?>";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});
</script>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('due_amount')?> <strong><?=App::currencies($currency)->symbol;?><?=number_format($amount,2);?></strong> for Invoice #<?=$item_name;?> via Braintree</h4>
        </div>

		<div class="modal-body">
			<p><?=lang('card_store_notice')?></p>

			<?php
			 $attributes = array('class' => 'bs-example form-horizontal','id' => 'braintree-form');
          echo form_open(base_url().'braintree/process',$attributes); ?>

			<input name="item_name" value="<?=$item_name?>" type="hidden">
			<input name="item_number" value="<?=$item_number?>" type="hidden">
			<?php $cur = App::currencies($currency); ?>

			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_amount')?> (<?=$cur->symbol?>) </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" name="due" value="<?=$amount?>" readonly>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('amount')?> (<?=lang('eg')?> 1200) </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" name="amount" value="<?=round($amount,2)?>">
				</div>
				</div>

			<div id="payment-form"></div>
          				
				
				<img src="<?=base_url()?>assets/images/payment_american.png">
				<img src="<?=base_url()?>assets/images/payment_discover.png">
				<img src="<?=base_url()?>assets/images/payment_maestro.png">
				<img src="<?=base_url()?>assets/images/payment_paypal.png">


				<div class="modal-footer"> 
				<a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
				<button type="submit" class="btn btn-success" id="submit"><?=lang('pay_invoice')?></button>
		</div>

     
     
    </form>

    

			
		</div>
		
		</form>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->