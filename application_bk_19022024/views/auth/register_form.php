<?php

if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'class'	=> 'form-control',
		'value' => set_value('username'),
		'maxlength'	=> config_item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'class'	=> 'form-control',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$fullname = array(
	'name'	=> 'fullname',
	'class'	=> 'form-control',
	'value'	=> set_value('fullname'),
);
$company_name = array(
	'name'	=> 'company_name',
	'class'	=> 'form-control',
	'value'	=> set_value('company_name'),
);
$password = array(
	'name'	=> 'password',
	'class'	=> 'form-control',
	'value' => set_value('password'),
	'maxlength'	=> config_item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'class'	=> 'form-control',
	'value' => set_value('confirm_password'),
	'maxlength'	=> config_item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'class'	=> 'form-control',
	'maxlength'	=> 8,
);
?>
<div class="account-content">
	<div class="container">
	
		<!-- Account Logo -->
		<div class="account-logo">
			<?php $display = config_item('logo_or_icon'); ?>
			<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('login_logo')?>" class="<?=($display == 'logo' ? "" : "login-logo")?>">
			<?php } ?>
		</div>
		<!-- /Account Logo -->
		
		<div class="account-box">
			<h3 class="account-title">Register</h3>
			<p class="account-subtitle">Access to our dashboard</p>

			<?php $attributes = array('class' => ''); echo form_open($this->uri->uri_string(),$attributes); ?>
				<div class="form-group">
					<label class="control-label"><?=lang('company_name')?></label>
					<?php echo form_input($company_name); ?>
					<span class="error"><?php echo form_error($company_name['name']); ?><?php echo isset($errors[$company_name['name']])?$errors[$company_name['name']]:''; ?></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
					<?php echo form_input($fullname); ?>
					<span class="error"><?php echo form_error($fullname['name']); ?><?php echo isset($errors[$fullname['name']])?$errors[$fullname['name']]:''; ?></span>
				</div>
				<?php if ($use_username) { ?>
				<div class="form-group">
					<label class="control-label"><?=lang('username')?> <span class="text-danger">*</span></label>
					<?php echo form_input($username); ?>
					<span class="error"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></span>
				</div>
				<?php } ?>
				<div class="form-group">
					<label class="control-label"><?=lang('email')?> <span class="text-danger">*</span></label>
					<?php echo form_input($email); ?>
					<span class="error"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=lang('password')?> <span class="text-danger">*</span></label>
					<?php echo form_password($password); ?>
					<span class="error"><?php echo form_error($password['name']); ?></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
					<?php echo form_password($confirm_password); ?>
					<span class="error"><?php echo form_error($confirm_password['name']); ?></span>
				</div>
				<div class="form-group">
					<table>
						<?php if ($captcha_registration == 'TRUE') {
							if ($use_recaptcha) { ?>
						<?php echo $this->recaptcha->render(); ?>
						<?php } else { ?>
						<tr><td colspan="4"><p><?=lang('enter_the_code_exactly')?></p></td></tr>
						<tr>
							<td colspan="3"><?php echo $captcha_html; ?></td>
							<td style="padding-left: 5px;"><?php echo form_input($captcha); ?></td>
						</tr>
						<?php }
						} ?>
					</table>
							<span class="error"><?php echo form_error($captcha['name']); ?></span>
				</div>
				<div class="form-group">
					<button type="submit" class="btn account-btn"><?=lang('sign_up')?></button>
				</div>
				<div class="account-footer">
					<p><?=lang('already_have_an_account')?> <a href="<?=base_url()?>login"><?=lang('sign_in')?></a></p>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>