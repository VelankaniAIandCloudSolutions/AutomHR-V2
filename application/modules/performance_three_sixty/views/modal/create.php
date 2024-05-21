<?php $company_ref = config_item('company_id_prefix').$this->applib->generate_string();
while($this->db->where('company_ref', $company_ref)->get('companies')->num_rows() == 1) {
$company_ref = config_item('company_id_prefix').$this->applib->generate_string();
} ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<!-- <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> -->
			<h4 class="modal-title">
				<?=lang('new_company')?> - <span class="label label-default"><?=$company_ref?></span>
			</h4>
        </div>
		<?php $attributes = array('id' => 'createClientForm'); echo form_open(base_url().'companies/create', $attributes); ?>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-tabs-solid" id="tabsClient" role="tablist">
					<li><a class="active" data-toggle="tab" id="tab_client_general" href="#tab-client-general"><?=lang('general')?></a></li>
					<li><a data-toggle="tab" id="tab_client_contact" href="#tab-client-contact"><?=lang('contact')?></a></li>
					<li><a data-toggle="tab" id="tab_client_web" href="#tab-client-web"><?=lang('web')?></a></li>
					<li><a data-toggle="tab" id="tab_client_bank" href="#tab-client-bank"><?=lang('bank')?></a></li>
					<li><a data-toggle="tab" id="tab_client_hosting" href="#tab-client-hosting"><?=lang('hosting')?></a></li>
					<!-- <li><a data-toggle="tab" href="#tab-client-custom"><?=lang('custom_fields')?></a></li> -->
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="tab-client-general">
						<input type="hidden" name="company_ref" value="<?=$company_ref?>">
						<div class="form-group">
							<label><?=lang('company_name')?> / <?=lang('full_name')?> <span class="text-danger">*</span></label>
							<input type="text" name="company_name" id="create_company_name" value="" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('email')?> <span class="text-danger">*</span></label>
							<input type="email" name="company_email" id="create_company_email" value="" class="form-control" >
						</div>
						<div class="form-group">
							<label><?=lang('vat')?> </label>
							<input type="text" value="" name="VAT" class="form-control">
						</div>
						<!-- <div class="form-group">
							<label><?=lang('language')?></label>
							<select name="language" class="form-control">
								<?php //foreach (App::languages() as $lang) : ?>
								<option value="<?=$lang->name?>"<?=(config_item('default_language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
								<?php //endforeach; ?>
							</select>
						</div> -->
						<div class="form-group">
							<label><?=lang('currency')?></label>
							<select name="currency" class="form-control">
								<?php foreach (App::currencies() as $cur) : ?>
								<option value="<?=$cur->code?>"<?=(config_item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label><?=lang('notes')?> <span class="text-danger">*</span></label>
							<textarea name="notes" id="create_company_notes" class="form-control ta" placeholder="<?=lang('notes')?>" ></textarea>
						</div>
						<div class="text-right">
						<a class="btn btn-primary submit-btn" id="nextCreateGeneral">Next</a>
						</div>
        				
					</div>
					<div class="tab-pane fade in" id="tab-client-contact">
						<div class="row">
							<div class="form-group col-md-4">
								<label><?=lang('phone')?> </label>
								<input type="text" value="" id="create_client_phone" name="company_phone"  class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label><?=lang('mobile_phone')?> <span class="text-danger">*</span></label>
								<input type="text" name="company_mobile" id="create_company_mobile" class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label><?=lang('fax')?> </label>
								<input type="text" value="" name="company_fax"  class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label><?=lang('address')?></label>
							<textarea name="company_address" class="form-control"></textarea>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label><?=lang('city')?> </label>
								<input type="text" value="" name="city" class="form-control">
							</div>
							<div class="form-group col-md-6">
								<label><?=lang('zip_code')?> </label>
								<input type="text" value="" name="zip" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label><?=lang('state_province')?> </label>
								<input type="text" value="" name="state" class="form-control">
							</div>
							<div class="form-group col-md-6">
								<label><?=lang('country')?> </label>
								<select class="form-control" name="country" >
									<optgroup label="<?=lang('selected_country')?>">
										<option value="<?=config_item('company_country')?>"><?=config_item('company_country')?></option>
									</optgroup>
									<optgroup label="<?=lang('other_countries')?>">
										<?php foreach (App::countries() as $country): ?>
										<option value="<?=$country->value?>"><?=$country->value?></option>
										<?php endforeach; ?>
									</optgroup>
								</select>
							</div>
						</div>
						<a class="btn btn-primary submit-btn" onclick="$('#tab_client_general').trigger('click')">Previous</a>
        				<a class="btn btn-primary submit-btn" style="float:right;" id="nextCreateContact" type="submit">Next</a>
						
					</div>
					<div class="tab-pane fade in" id="tab-client-web">
						<div class="form-group">
							<label><?=lang('website')?> </label>
							<input type="text" value="" name="company_website" class="form-control">
						</div>
						<div class="form-group">
							<label>Skype</label>
							<input type="text" value="" name="skype" class="form-control">
						</div>
						<div class="form-group">
							<label>LinkedIn</label>
							<input type="text" value="" name="linkedin" class="form-control">
						</div>
						<div class="form-group">
							<label>Facebook</label>
							<input type="text" value="" name="facebook" class="form-control">
						</div>
						<div class="form-group">
							<label>Twitter</label>
							<input type="text" value="" name="twitter" class="form-control">
						</div>
						<a class="btn btn-primary submit-btn" onclick="$('#tab_client_contact').trigger('click')">Previous</a>
        				<a class="btn btn-primary submit-btn" style="float:right;"  onclick="$('#tab_client_bank').trigger('click')">Next</a>
						
					</div>
					<div class="tab-pane fade in" id="tab-client-bank">
						<div class="form-group">
							<label><?=lang('bank')?> </label>
							<input type="text" value="" name="bank" class="form-control">
						</div>
						<div class="form-group">
							<label>SWIFT/BIC</label>
							<input type="text" value="" name="bic" class="form-control">
						</div>
						<div class="form-group">
							<label>Sort Code</label>
							<input type="text" value="" name="sortcode" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account_holder')?> </label>
							<input type="text" value="" name="account_holder" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account')?> </label>
							<input type="text" value="" name="account" class="form-control">
						</div>
						<div class="form-group">
							<label>IBAN</label>
							<input type="text" value="" name="iban" class="form-control">
						</div>
						<a class="btn btn-primary submit-btn" onclick="$('#tab_client_web').trigger('click')">Previous</a>
        				<a class="btn btn-primary submit-btn" style="float:right;" onclick="$('#tab_client_hosting').trigger('click')">Next</a>
						
					</div>
					<div class="tab-pane fade in" id="tab-client-hosting">
						<div class="form-group">
							<label><?=lang('hosting_company')?> </label>
							<input type="text" value="" name="hosting_company" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('hostname')?> </label>
							<input type="text" value="" name="hostname" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account_username')?> </label>
							<input type="text" value="" name="account_username" autocomplete="off" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account_password')?> </label>
							<input type="password" value="" autocomplete="off" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('port')?> </label>
							<input type="text" value="" name="port" class="form-control">
						</div>
						<a class="btn btn-primary submit-btn" onclick="$('#tab_client_bank').trigger('click')">Previous</a>
						<button type="submit" class="btn btn-success submit-btn" style="float:right;" id="createClient"><?=lang('save_changes')?></button>
						
					</div>

					<!-- START CUSTOM FIELDS -->
					<div class="tab-pane fade in" id="tab-client-custom">
						<?php $fields = $this->db->order_by('order','DESC')->where('module','clients')->get('fields')->result(); ?>
						<?php foreach($fields as $f): ?>
							<?php $options = json_decode($f->field_options,true); ?>
							<!-- check if dropdown -->
							<?php if($f->type == 'dropdown'): ?>

							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<select class="form-control" name="<?='cust_'.$f->name?>" <?=($f->required) ? 'required': '';?> >
									<?php foreach($options['options'] as $opt) : ?>
									<option value="<?=$opt['label']?>" <?=($opt['checked']) ? 'selected="selected"' : '';?>><?=$opt['label']?></option>
									<?php endforeach; ?>
								</select>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Text field -->
							<?php elseif($f->type == 'text'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<input type="text" name="<?='cust_'.$f->name?>" class="form-control" <?=($f->required) ? 'required': '';?>>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Textarea field -->
							<?php elseif($f->type == 'paragraph'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<textarea name="<?='cust_'.$f->name?>" class="form-control ta" <?=($f->required) ? 'required': '';?>></textarea>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Radio buttons -->
							<?php elseif($f->type == 'radio'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<?php foreach($options['options'] as $opt) : ?>
								<label class="radio-custom">
									<input type="radio" name="<?='cust_'.$f->name?>[]" <?=($opt['checked']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>" <?=($f->required) ? 'required': '';?>> <?=$opt['label']?>
								</label>
								<?php endforeach; ?>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Checkbox field -->
							<?php elseif($f->type == 'checkboxes'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<?php foreach($options['options'] as $opt) : ?>
								<div class="checkbox">
									<label class="checkbox-custom">
										<input type="checkbox" name="<?='cust_'.$f->name?>[]" <?=($opt['checked']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>">
										<?=$opt['label']?>
									</label>
								</div>
								<?php endforeach; ?>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>
							<!-- Email Field -->
							<?php elseif($f->type == 'email'): ?>

							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<input type="email" name="<?='cust_'.$f->name?>" class="form-control" <?=($f->required) ? 'required': '';?>>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>
							<?php elseif($f->type == 'section_break'): ?>
								<hr />
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- End custom fields -->
				</div>
				
			</div>
			<!-- <div class="modal-footer">
				<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="createClient"><?=lang('save_changes')?></button>
			</div> -->
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.nav-tabs li a').first().tab('show');
</script>