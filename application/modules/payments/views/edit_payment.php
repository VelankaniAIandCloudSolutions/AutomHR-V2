<div class="content">
	<div class="row">
		<div class="col-sm-4">
			<h4 class="page-title"><?=lang('edit_payment')?></h4>
		</div>
		<div class="col-sm-8 text-right m-b-0">
			<?php $i = Payment::view_by_id($id); ?>
			<div class="btn-group">
				<a href="<?=base_url()?>payments/view/<?=$i->p_id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="top" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> <?=lang('payment_details')?></a>
			</div>
		</div>
	</div>
	<!-- Start create invoice -->
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-white">
				<div class="panel-heading">
					<h3 class="panel-title"><?=lang('payment_details')?> - TRANS <?=$i->trans_id?></h3>
				</div>
				<div class="panel-body">
					<?php $attributes = array('class' => 'bs-example form-horizontal','id'=> 'paymentEditForm'); echo form_open(base_url().'payments/edit',$attributes); ?>
						<input type="hidden" name="p_id" value="<?=$i->p_id?>">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('amount')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
								<input type="text" class="form-control" value="<?=$i->amount?>" name="amount">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('payment_method')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
								<select name="payment_method" class="form-control">
									<?php foreach (App::list_payment_methods() as $key => $p_method) { ?>
										<option value="<?=$p_method->method_id?>"<?=($i->payment_method == $p_method->method_id ? ' selected="selected"' : '')?>><?=$p_method->method_name?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php $currency = App::currencies($i->currency); ?>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('currency')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
								<select name="currency" class="form-control">
									<?php foreach (App::currencies() as $cur) : ?>
									<option value="<?=$cur->code?>"<?=($currency->code == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('payment_date')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
								<input class="datepicker-input form-control" type="text" readonly value="<?=strftime(config_item('date_format'), strtotime($i->payment_date));?>" name="payment_date" data-date-format="<?=config_item('date_picker_format');?>" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('notes')?> <span class="text-danger">*</span></label>
							<div class="col-lg-8">
								<textarea name="notes" class="form-control ta"><?=$i->notes?></textarea>
							</div>
						</div>
						<div class="text-center m-t-30">
							<button type="submit" class="btn btn-lg btn-primary" id="payment_edit_submit"> <?=lang('save_changes')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- End create invoice -->
</div>