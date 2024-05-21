<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_client')?></h4>
        </div>
		<?php $i = Client::view_by_id($company); ?>
		<?php $attributes = array('id' => 'editClientForm'); echo form_open(base_url().'companies/update',$attributes); ?>
			<input style="display:none">
			<input type="password" style="display:none">
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tabsUptClient" role="tablist">
					<li class="active"><a class="active" data-toggle="tab" id="tab_upt_client_general" href="#tab-client-general"><?=lang('general')?></a></li>
					<li><a data-toggle="tab" id="tab_upt_client_contact" href="#tab-client-contact"><?=lang('contact')?></a></li>
					<li><a data-toggle="tab" id="tab_upt_client_web" href="#tab-client-web"><?=lang('web')?></a></li>
					<li><a data-toggle="tab" id="tab_upt_client_bank" href="#tab-client-bank"><?=lang('bank')?></a></li>
					<li><a data-toggle="tab" id="tab_upt_client_hosting" href="#tab-client-hosting"><?=lang('hosting')?></a></li>
					<!-- <li><a data-toggle="tab" id="#tab_upt_client_custom" href="#tab-client-custom"><?=lang('custom_fields')?></a></li> -->
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="tab-client-general">
						<input type="hidden" name="company_ref" value="<?=$i->company_ref?>">
						<input type="hidden" name="co_id" value="<?=$i->co_id?>">
						<div class="form-group">
							<label><?php if ($i->individual == 0) { echo lang('company_name'); } else { echo lang('full_name'); } ?> <span class="text-danger">*</span></label>
							<input type="text" name="company_name" id="edit_company_name" value="<?=$i->company_name?>" class="form-control" required>
						</div>
						<div class="form-group">
							<label><?=lang('email')?> <span class="text-danger">*</span></label>
							<input type="email" name="company_email" id="edit_company_email" value="<?=$i->company_email?>" class="form-control" required>
						</div>
						<div class="form-group">
							<label><?=lang('vat')?> </label>
							<input type="text" value="<?=$i->VAT?>" name="VAT" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('language')?></label>
							<select name="language" class="form-control">
								<?php foreach (App::languages() as $lang) : ?>
								<option value="<?=$lang->name?>"<?=($i->language == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<?php $currency = App::currencies($i->currency); ?>
						<div class="form-group">
							<label><?=lang('currency')?></label>
							<select name="currency" class="form-control">
								<?php foreach (App::currencies() as $cur) : ?>
								<option value="<?=$cur->code?>"<?=($currency->code == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label><?=lang('notes')?> <span class="text-danger">*</span></label>
							<textarea name="notes" id="edit_company_notes" class="form-control ta"><?=$i->notes;?></textarea>
						</div>
						<div class="text-right">
						<a class="btn btn-primary" id="nextEditGeneral">Next</a>
						</div>
        				
					</div>
					<div class="tab-pane fade in" id="tab-client-contact">
						<div class="row">
							<div class="form-group col-md-4">
								<label><?=lang('phone')?> </label>
								<input type="text" value="<?=$i->company_phone?>" id="edit_client_phone" name="company_phone"  class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label><?=lang('mobile_phone')?> <span class="text-danger">*</span></label>
								<input type="text" value="<?=$i->company_mobile?>" name="company_mobile" id="edit_company_mobile" class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label><?=lang('fax')?> </label>
								<input type="text" value="<?=$i->company_fax?>" name="company_fax"  class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label><?=lang('address')?></label>
							<textarea name="company_address" class="form-control ta"><?=$i->company_address?></textarea>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label><?=lang('city')?> </label>
								<input type="text" value="<?=$i->city?>" name="city" class="form-control">
							</div>
							<div class="form-group col-md-6">
								<label><?=lang('zip_code')?> </label>
								<input type="text" value="<?=$i->zip?>" name="zip" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label><?=lang('state_province')?> </label>
								<input type="text" value="<?=$i->state?>" name="state" class="form-control">
							</div>
							<div class="form-group col-md-6">
								<label><?=lang('country')?> </label>
								<select class="form-control" name="country" >
									<optgroup label="<?=lang('selected_country')?>">
										<option value="<?=$i->country?>"><?=$i->country?></option>
									</optgroup>
									<optgroup label="<?=lang('other_countries')?>">
										<?php foreach (App::countries() as $country): ?>
										<option value="<?=$country->value?>"><?=$country->value?></option>
										<?php endforeach; ?>
									</optgroup>
								</select>
							</div>
						</div>
						<a class="btn btn-primary" onclick="$('#tab_upt_client_general').trigger('click')">Previous</a>
        				<a class="btn btn-primary" style="float:right;" id="nextEditContact" type="submit">Next</a>
						
					</div>
					<div class="tab-pane fade in" id="tab-client-web">
						<div class="form-group">
							<label><?=lang('website')?> </label>
							<input type="text" value="<?=$i->company_website?>" name="company_website"  class="form-control">
						</div>
						<div class="form-group">
							<label>Skype</label>
							<input type="text" value="<?=$i->skype?>" name="skype"  class="form-control">
						</div>
						<div class="form-group">
							<label>LinkedIn</label>
							<input type="text" value="<?=$i->linkedin?>" name="linkedin"  class="form-control">
						</div>
						<div class="form-group">
							<label>Facebook</label>
							<input type="text" value="<?=$i->facebook?>" name="facebook"  class="form-control">
						</div>
						<div class="form-group">
							<label>Twitter</label>
							<input type="text" value="<?=$i->twitter?>" name="twitter"  class="form-control">
						</div>
						<a class="btn btn-primary" onclick="$('#tab_upt_client_contact').trigger('click')">Previous</a>
        				<a class="btn btn-primary" style="float:right;"  onclick="$('#tab_upt_client_bank').trigger('click')">Next</a>
						
					</div>
					<div class="tab-pane fade in" id="tab-client-bank">
						<div class="form-group">
							<label><?=lang('bank')?> </label>
							<input type="text" value="<?=$i->bank?>" name="bank"  class="form-control">
						</div>
						<div class="form-group">
							<label>SWIFT/BIC</label>
							<input type="text" value="<?=$i->bic?>" name="bic"  class="form-control">
						</div>
						<div class="form-group">
							<label>Sort Code</label>
							<input type="text" value="<?=$i->sortcode?>" name="sortcode"  class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account_holder')?> </label>
							<input type="text" value="<?=$i->account_holder?>" name="account_holder"  class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account')?> </label>
							<input type="text" value="<?=$i->account?>" name="account"  class="form-control">
						</div>
						<div class="form-group">
							<label>IBAN</label>
							<input type="text" value="<?=$i->iban?>" name="iban" class="form-control">
						</div>
						<a class="btn btn-primary" onclick="$('#tab_upt_client_web').trigger('click')">Previous</a>
        				<a class="btn btn-primary" style="float:right;" onclick="$('#tab_upt_client_hosting').trigger('click')">Next</a>
						
					</div>

					<div class="tab-pane fade in" id="tab-client-hosting">
						<div class="form-group">
							<label><?=lang('hosting_company')?> </label>
							<input type="text" value="<?=$i->hosting_company?>" name="hosting_company" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('hostname')?> </label>
							<input type="text" value="<?=$i->hostname?>" name="hostname" class="form-control">
						</div>
						<div class="form-group">
							<label><?=lang('account_username')?> </label>
							<input type="text" value="<?=$i->account_username?>" name="account_username" class="form-control" >
						</div>
						<div class="form-group">
							<label><?=lang('account_password')?> </label>
							<input type="password" value="<?=$i->account_password?>"  name="account_password" class="form-control" >
						</div>
						<div class="form-group">
							<label><?=lang('port')?> </label>
							<input type="text" value="<?=$i->port?>" name="port" class="form-control">
						</div>
						<a class="btn btn-primary" onclick="$('#tab_upt_client_bank').trigger('click')">Previous</a>
						<button type="submit" style="float:right;" class="btn btn-success" id="updateClient"><?=lang('save_changes')?></button>
						
					</div>

					<!-- START CUSTOM FIELDS -->
					<div class="tab-pane fade in" id="tab-client-custom">
						<?php $fields = $this->db->order_by('order','DESC')->where('module','clients')->get('fields')->result(); ?>
						<?php foreach($fields as $f): ?>
							<?php $val = App::field_meta_value($f->name, $company); ?>
							<?php $options = json_decode($f->field_options,true); ?>
							<!-- check if dropdown -->
							<?php if($f->type == 'dropdown'): ?>

							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<select class="form-control" name="<?='cust_'.$f->name?>" <?=($f->required) ? 'required': '';?> >
									<option value="<?=$val?>"><?=$val?></option>
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
								<input type="text" name="<?='cust_'.$f->name?>" class="form-control" value="<?=$val?>" <?=($f->required) ? 'required': '';?>>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Textarea field -->
							<?php elseif($f->type == 'paragraph'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<textarea name="<?='cust_'.$f->name?>" class="form-control ta" <?=($f->required) ? 'required': '';?>><?=$val?></textarea>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Radio buttons -->
							<?php elseif($f->type == 'radio'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<?php foreach($options['options'] as $opt) : ?>
								<?php $sel_val = json_decode($val); ?>
								<label class="radio-custom">
								<input type="radio" name="<?='cust_'.$f->name?>[]" <?=($opt['checked'] || $sel_val[0] == $opt['label']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>" <?=($f->required) ? 'required': '';?>> <?=$opt['label']?> </label>
								<?php endforeach; ?>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>

							<!-- Checkbox field -->
							<?php elseif($f->type == 'checkboxes'): ?>
							<div class="form-group">
								<label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
								<?php foreach($options['options'] as $opt) : ?>
								<?php $sel_val = json_decode($val); ?>
								<div class="checkbox">
									<label class="checkbox-custom">
										<?php if(is_array($sel_val)) : ?>
										<input type="checkbox" name="<?='cust_'.$f->name?>[]" <?=($opt['checked'] || in_array($opt['label'], $sel_val)) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>">
										<?php else: ?>
										<input type="checkbox" name="<?='cust_'.$f->name?>[]" <?=($opt['checked']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>">
										<?php endif; ?>
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
								<input type="email" name="<?='cust_'.$f->name?>" value="<?=$val?>" class="form-control" <?=($f->required) ? 'required': '';?>>
								<span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
							</div>
							<?php elseif($f->type == 'section_break'): ?>
							<hr />
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div> -->
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.nav-tabs li a').first().tab('show');
</script>
