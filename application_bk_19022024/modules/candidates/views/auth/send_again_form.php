<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'class' => 'form-control input-lg'
);
?>

<div class="content" class="m-t-lg wrapper-md">

		<div class="container aside-xxl">
		 <a class="navbar-brand block" href="<?=base_url()?>">
                    <?php $display = config_item('logo_or_icon'); ?>
			<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('company_logo')?>" class="m-r-sm">
			<?php } ?>
			<?php
                        if ($display == 'logo_title') {
                            if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
                        }
                        ?>
                 </a>
		 <section class="card panel-default bg-white m-t-lg">
		<header class="card-header text-center"> <strong>Send Again <?=config_item('company_name')?></strong> </header>

		<?php
		$attributes = array('class' => 'card-body wrapper-lg');
		echo form_open($this->uri->uri_string(),$attributes); ?>
			<div class="form-group">
				<label class="control-label"><?=lang('email_address')?></label>
				<?php echo form_input($email); ?>
				<span style="color: red;">
				<?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?>
				</span>
			</div>


			<button type="submit" class="btn btn-primary">Send</button>
			<div class="line line-dashed">
			</div>
			<?php if (config_item('allow_registration', 'tank_auth')){ ?>
			<p class="text-muted text-center"><small>Do not have an account?</small></p>
			<?php } ?>
			<a href="<?=base_url()?>auth/register" class="btn btn-success btn-block">Get Your Account</a>
<?php echo form_close(); ?>

 </section>
	</div>
	</div>
