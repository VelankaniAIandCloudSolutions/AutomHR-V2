<?php
$login = array(
	'name'	=> 'username',
	'class'	=> 'form-control',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or Username';
} else if ($login_by_username) {
	$login_label = 'Username';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'inputPassword',
	'size'	=> 30,
	'class' => 'form-control'
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'class'	=> 'form-control',
	'maxlength'	=> 10,
);
?>
<div class="account-content">
		<a href="<?php echo base_url(); ?>candidates/all_jobs_user" class="btn btn-primary apply-btn">Apply Job</a>	 
	<div id="login-form" class="container">
<!-- 
		<ul class="nav navbar-nav navbar-right user-menu float-right">
			<li>
				<a href="<?=base_url()?>jobs">Career</a>
			</li>				 
		</ul>
	
 -->
					
		
					<!-- Account Logo -->
					<div class="account-logo">
						<?php $display = config_item('logo_or_icon'); ?>
						<?php if ($display == 'logo' || $display == 'logo_title') { ?>
						<img src="<?=base_url()?>assets/images/<?=config_item('login_logo')?>" class="<?=($display == 'logo' ? "" : "login-logo")?>">
						<?php } ?>
					</div>
					<!-- /Account Logo -->
					<div class="account-box">
						<div class="account-wrapper">
			<h3 class="account-title"><?php echo lang('candidate_login');?></h3>
			<p class="account-subtitle"><?php echo lang('access_to_our_dashboard');?></p>
			<?php echo modules::run('sidebar/flash_msg');?>
		
			
			<!-- Account Form -->
			<?php $attributes = array('class' => ''); echo form_open($this->uri->uri_string(),$attributes); ?>
				<div class="form-group">
					<label class="control-label"><?=lang('email_user')?></label>
					<?php echo form_input($login); ?>
					<span class="error"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
				</div>
				<div class="form-group">
					<label class="control-label"><?=lang('password')?></label>
					<?php echo form_password($password); ?>
					<span class="error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
				</div>
				<table>
					<?php if ($show_captcha) {
						if ($use_recaptcha) { ?>
					<?php echo $this->recaptcha->render(); ?>
					<?php } else { ?>
					<tr><td colspan="2"><p><?=lang('enter_the_code_exactly')?></p></td></tr>
					<tr>
						<td colspan="3"><?php echo $captcha_html; ?></td>
						<td style="padding-left: 5px;"><?php echo form_input($captcha); ?></td>
						<span class="error"><?php echo form_error($captcha['name']); ?></span>
					</tr>
					<?php }
					} ?>
				</table>
				<div class="form-group clearfix">
					<div class="float-left">
						<label>
							<?php echo form_checkbox($remember); ?> <?=lang('this_is_my_computer')?>
						</label>
					</div>
					<a href="<?=base_url()?>candidates/forgot_password" class="float-right text-muted"><?=lang('forgot_password')?></a>
				</div>
				<div class="form-group m-b-0">
					<button type="submit" name="sign_in" value ="sign_in" class="btn account-btn"><?=lang('sign_in')?></button>
				</div>
				
			<?php echo form_close(); ?>
<!-- 
			<div class="form-group m-b-0">
					<a href="<?php echo site_url('candidates/linked_login');?>"><img src="<?php echo base_url();?>assets/images/linkedin_btn.png" width="120px;"></a>
				</div> -->
			<!-- /Account Form -->
			
		</div>
	</div>
	</div>
</div>