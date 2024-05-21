<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<h4 class="page-title"><?=lang('edit_profile_text')?> <strong><small>(<?=ucfirst($this->tank_auth->get_username());?>)</small></strong></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<!-- Profile Form -->
			<div class="panel">
				<div class="panel-heading">
					<h6 class="panel-title"><?=lang('profile_details')?></h6>
				</div>
				<div class="panel-body">
					<?php
					$profile = User::profile_info(User::get_id());
					$login = User::login_info(User::get_id());
					$attributes = array('class' => 'bs-example');
					echo form_open(uri_string(),$attributes); ?>
					<?php echo validation_errors(); ?>

						<div class="form-group">
							<label><?=lang('full_name')?> <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="fullname" value="<?=$profile->fullname?>" required>
						</div>
						<?php if(User::is_staff()){/* ?>
						<div class="form-group">
							<label><?=lang('hourly_rate')?> <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="hourly_rate" value="<?=$profile->hourly_rate?>" required>
						</div>
						<?php */} ?>
						<input type="hidden" value="<?=$profile->company?>" name="co_id">
						<?php
						if ($profile->company > 0) {
							$comp = Client::view_by_id($profile->company);
						?>  
						<div class="form-group">
							<label><?=lang('company')?> </label>
							<input type="text" class="form-control" name="company_data[company_name]" value="<?=(isset($comp->company_name)) ? $comp->company_name : ""?>" required>
						</div>
						<div class="form-group">
							<label><?=lang('company_email')?> </label>
							<input type="text" class="form-control" name="company_data[company_email]" value="<?=(isset($comp->company_email)) ? $comp->company_email : ""?>" required>
						</div>
						<div class="form-group">
							<label><?=lang('company_address')?> </label>
							<input type="text" class="form-control" name="company_data[company_address]" value="<?=(isset($comp->company_address)) ? $comp->company_address : ""?>" required>
						</div>
						<div class="form-group">
							<label><?=lang('company_vat')?> </label>
							<input type="text" class="form-control" name="company_data[vat]" value="<?=(isset($comp->VAT)) ? $comp->VAT : ""?>">
						</div>
						<?php } ?>
						<div class="form-group">
							<label><?=lang('phone')?></label>
							<input type="text" class="form-control" name="phone" value="<?=$profile->phone?>">
						</div>
						<!-- <div class="form-group">
							<label><?=lang('language')?></label>
							<select name="language" class="form-control">
								<?php // foreach (App::languages() as $lang) : ?>
								<option value="<?=$lang->name?>"<?=($profile->language == $lang->name ? ' selected="selected"' : '')?>>
								<?=ucfirst($lang->name); ?></option>
								<?php // endforeach; ?>
							</select>
						</div> -->
						<!-- <div class="form-group">
							<label><?=lang('locale')?></label>
							<select class="select2-option form-control" name="locale">
								<?php // foreach (App::locales() as $loc) : ?>
								<option value="<?=$loc->locale?>"<?=($profile->locale == $loc->locale ? ' selected="selected"' : '')?>>
								<?=$loc->name; ?></option>
								<?php // endforeach; ?>
							</select>
						</div> -->
						<button type="submit" class="btn btn-success"><?=lang('update_profile')?></button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<!-- Account Form -->
			<div class="panel">
				<div class="panel-heading">
					<h6 class="panel-title"><?=lang('account_details')?></h6>
				</div>
				<div class="panel-body">
					<?php
					echo form_open(base_url().'auth/change_password'); ?>
						<input type="hidden" name="r_url" value="<?=uri_string()?>">
						<div class="form-group">
							<label><?=lang('old_password')?> <span class="text-danger">*</span></label>
							<input type="password" class="form-control" name="old_password" id="old_password" placeholder="<?=lang('old_password')?>" required>
						</div>
						<div class="form-group">
							<label><?=lang('new_password')?> <span class="text-danger">*</span></label>
							<input type="password" class="form-control" name="new_password" id="profile_new_password" placeholder="<?=lang('new_password')?>" data-userid="<?php echo $this->session->userdata('user_id'); ?>" required>
						</div>
						<div class="form-group">
							<label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
							<input type="password" class="form-control" name="confirm_new_password" placeholder="<?=lang('confirm_password')?>" required>
						</div>
						<button type="submit" class="btn btn-success"><?=lang('change_password')?></button>
					</form>
				</div>
			</div>
			<div class="panel m-t-3">
				<div class="panel-heading">
					<h6 class="panel-title"><?=lang('avatar_image')?></h6>
				</div>
				<div class="panel-body">
					<?php
					$attributes = array('class' => 'bs-example form-horizontal');
					echo form_open_multipart(base_url().'profile/changeavatar',$attributes); ?>
						<input type="hidden" name="r_url" value="<?=uri_string()?>">
						<!-- <div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('use_gravatar')?></label>
							<div class="col-lg-8">
								<label class="switch">
									<input type="checkbox" <?php // if($profile->use_gravatar == 'Y'){ echo "checked=\"checked\""; } ?> name="use_gravatar">
									<span></span>
								</label>
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('avatar_image')?></label>
							<div class="col-lg-9">
								<label class="btn btn-default btn-choose">Choose File</label>
								<input type="file" class="form-control" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
							</div>
						</div>
						<button type="submit" class="btn btn-success"><?=lang('change_avatar')?></button>
					</form>
				</div>
			</div>
			<?php 
				$role_id = $this->session->userdata('role_id');
				
				 if(($role_id !=1) && ($role_id !=4)){

			 ?>
			<div class="panel m-t-3">
				<div class="panel-heading">
					<h6 class="panel-title"><?=lang('change_username')?></h6>
				</div>
				<div class="panel-body">
					<?php
					$attributes = array('class' => 'bs-example form-horizontal');
					echo form_open(base_url().'auth/change_username',$attributes); ?>
						<input type="hidden" name="r_url" value="<?=uri_string()?>">
						<div class="form-group">
							<label class="col-lg-4 control-label"><?=lang('new_username')?></label>
							<div class="col-lg-7">
								<input type="text" class="form-control" name="username" placeholder="<?=lang('new_username')?>" required>
							</div>
						</div>
						<button type="submit" class="btn btn-danger"><?=lang('change_username')?></button>
					</form>
				</div>
			</div>
			<!-- /Account form -->
			<?php }  ?>
		</div>
	</div>
</div>