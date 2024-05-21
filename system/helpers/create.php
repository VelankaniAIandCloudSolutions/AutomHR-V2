<?php $company_ref = config_item('company_id_prefix').$this->applib->generate_string();
while($this->db->where('company_ref', $company_ref)->get('companies')->num_rows() == 1) {
$company_ref = config_item('company_id_prefix').$this->applib->generate_string();
} ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('new_lead')?> -
				<span class="label label-default"><?=$company_ref?></span>
			</h4>
        </div>
		<?php echo form_open(base_url().'leads/create'); ?>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-tabs-solid" role="tablist">
					<li><a class="active" data-toggle="tab" href="#tab-client-general"><?=lang('general')?></a></li>
					<li><a data-toggle="tab" href="#tab-client-contact"><?=lang('contact')?></a></li>
					<li><a data-toggle="tab" href="#tab-client-web"><?=lang('web')?></a></li>
					<li><a data-toggle="tab" href="#tab-client-custom"><?=lang('custom_fields')?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="tab-client-general">
						<input type="hidden" name="company_ref" value="<?=$company_ref?>">
						<div class="form-group">
							<label><?=lang('company_name')?> / <?=lang('full_name')?><span class="text-danger">*</span></label>
							<input type="text" name="company_name" value="" class="form-control" required>
						</div>

						<div class="form-group">
							<label><?=lang('lead_value')?> <span class="text-danger">*</span> (e.g 500.00)</label>
							<input type="text" name="transaction_value" value="0.00" class="form-control" required>
						</div>

							<div class="form-group">
							<label><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
							<!-- Build your select: -->
							<select class="select2-option form-control"   required style="width:100%;" name="assign_to" > 
								<optgroup label="<?=lang('admin_staff')?>"> 
								<?php foreach (User::team() as $key => $user) { ?>
								<option value="<?=$user->id?>"><?=ucfirst(User::displayName($user->id))?></option>
								<?php } ?>	
								</optgroup> 
							</select>
						</div>

						<div class="form-group">
							<label><?=lang('assign_project')?> <span class="text-danger">*</span> </label>
							
							<select class="select2-option form-control"   required style="width:100%;" name="assign_project" > 
								<?php foreach (User::all_projects() as $key => $projects) { ?>
								<option value="<?=$projects->project_id?>"><?=ucfirst($projects->project_title)?></option>
								<?php } ?>	
							 
							</select>
						</div>


						<div class="form-group">
							<label><?=lang('lead_stage')?> <span class="text-danger">*</span></label>
							<select name="lead_stage" class="form-control m-b">
								<?php if($set_stage != NULL) : ?>
								<option value="<?=$set_stage?>"><?=App::get_category_by_id($set_stage)?> - Selected</option>
								<?php endif; ?>
								<?php foreach ($categories as $key => $cat) { ?>
								<option value="<?=$cat->id?>"><?=$cat->cat_name?></option>
								<?php } ?>
							</select>
							<span class="help-block">Add lead stages in Settings &raquo; System Settings &raquo; Category</span>
						</div>
						<div class="form-group">
							<label><?=lang('email')?> <span class="text-danger">*</span></label>
							<input type="email" name="company_email" value="" class="form-control" required>
						</div>
						<div class="form-group">
							<label><?=lang('language')?></label>
							<select name="language" class="form-control">
							<?php foreach (App::languages() as $lang) : ?>
							<option value="<?=$lang->name?>"<?=(config_item('default_language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label><?=lang('currency')?></label>
							<select name="currency" class="form-control">
								<?php foreach (App::currencies() as $cur) : ?>
								<option value="<?=$cur->code?>"<?=(config_item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label><?=lang('notes')?></label>
							<textarea name="notes" class="form-control ta" placeholder="<?=lang('notes')?>" ></textarea>
						</div>
					</div>
					<div class="tab-pane fade in" id="tab-client-contact">
						<div class="row">
							<div class="form-group col-md-4">
								<label><?=lang('phone')?> </label>
								<input type="text" value="" name="company_phone"  class="form-control">
							</div>
							<div class="form-group col-md-4">
								<label><?=lang('mobile_phone')?> </label>
								<input type="text" value="" name="company_mobile"  class="form-control">
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
					</div>
					<!-- START CUSTOM FIELDS -->
					<div class="tab-pane fade in" id="tab-client-custom">
						<?php $fields = $this->db->order_by('order','DESC')->where('module','leads')->get('fields')->result(); ?>
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
								<label class="">
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
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.nav-tabs li a').first().tab('show');
</script>