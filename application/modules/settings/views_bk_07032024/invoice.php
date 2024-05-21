<div class="p-0">
	<!-- Start Form -->
	<div class="col-lg-12 p-0">
		<?php
		$attributes = array('class' => 'bs-example form-horizontal','id'=>'settingsInvoiceForm');
		echo form_open_multipart('settings/update', $attributes); ?>
			<div class="panel panel-white">
				<div class="panel-heading">
					<h3 class="panel-title p-5"><?=lang('invoice_settings')?></h3>
				</div>
				<div class="panel-body">
					<input type="hidden" name="settings" value="<?=$load_setting?>">
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('invoice_color')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" id="invoice_color" readonly name="invoice_color" class="form-control" value="<?=config_item('invoice_color')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('invoice_prefix')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="invoice_prefix" class="form-control" value="<?=config_item('invoice_prefix')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('invoices_due_after')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="invoices_due_after" class="form-control" data-toggle="tooltip" data-placement="top" data-original-title="<?=lang('days')?>" value="<?=config_item('invoices_due_after')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('invoice_start_number')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="invoice_start_no" class="form-control" value="<?=config_item('invoice_start_no')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">PDF Engine</label>
						<div class="col-lg-5">
							<select name="pdf_engine" class="form-control">
								<option value="invoicr"<?=(config_item('pdf_engine') == 'invoicr'? ' selected="selected"': '')?>>Invoicr</option>
								<option value="mpdf"<?=(config_item('pdf_engine') == 'mpdf'? ' selected="selected"': '')?>>mPDF</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('swap_to_from_side')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="swap_to_from" />
								<input type="checkbox" <?php if(config_item('swap_to_from') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="swap_to_from">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('display_invoice_badge')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="display_invoice_badge" />
								<input type="checkbox" <?php if(config_item('display_invoice_badge') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="display_invoice_badge">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('automatic_email_on_recur')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="automatic_email_on_recur" />
								<input type="checkbox" <?php if(config_item('automatic_email_on_recur') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="automatic_email_on_recur">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('show_item_tax')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="show_invoice_tax" />
								<input type="checkbox" <?php if(config_item('show_invoice_tax') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="show_invoice_tax">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Tax1<span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="tax1" id="tax1" class="form-control" value="<?=config_item('tax1')?>" placeholder="Tax1">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Tax2<span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="tax2" id="tax2" class="form-control" value="<?=config_item('tax2')?>" placeholder="Tax2">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('invoice_logo')?></label>
						<div class="col-lg-9">
							<div class="row">
								<div class="col-lg-12">
									<label class="btn btn-default btn-choosef">Choose File</label>
									<input type="file" class="form-control" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="invoicelogo">
								</div>
							</div>
							<?php if (config_item('invoice_logo') != '') : ?>
								<div class="row">
									<div class="col-lg-6">
										<div id="invoice-logo-slider"></div>
									</div>
									<div class="col-lg-6">
										<div id="invoice-logo-dimensions"><?=config_item('invoice_logo_height')?>px x <?=config_item('invoice_logo_width')?>px</div>
									</div>
								</div>
								<input id="invoice-logo-height" type="hidden" value="<?=config_item('invoice_logo_height')?>" name="invoice_logo_height"/>
								<input id="invoice-logo-width" type="hidden" value="<?=config_item('invoice_logo_width')?>" name="invoice_logo_width"/>
								<div class="row" style="height: 150px; margin-bottom:15px;">
									<div class="col-lg-12">
										<div class="invoice_image" style="height: <?=config_item('invoice_logo_height')?>px"><img src="<?=base_url()?>assets/images/logos/<?=config_item('invoice_logo')?>" /></div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group terms">
						<label class="col-lg-3 control-label"><?=lang('invoice_footer')?></label>
						<div class="col-lg-9">
							<textarea class="form-control foeditor" name="invoice_footer"><?=config_item('invoice_footer')?></textarea>
						</div>
					</div>
					<div class="form-group terms">
						<label class="col-lg-3 control-label"><?=lang('default_terms')?></label>
						<div class="col-lg-9">
							<textarea class="form-control foeditor" name="default_terms"><?=config_item('default_terms')?></textarea>
						</div>
					</div>
					<div class="text-center m-t-30">
                        <button id="settings_invoice_submit" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- End Form -->
</div>