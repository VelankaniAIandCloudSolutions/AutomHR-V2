<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> config_item('password_max_length', 'tank_auth'),
	'size'	=> 30,
	'class' => 'form-control input-lg'
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> config_item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
	'class' => 'form-control input-lg'
);
?>

<?php  echo modules::run('sidebar/flash_msg');?>  

<br>
<div class="content" class="m-t-lg wrapper-md">
                <div id="login-blur"></div>
                <div id="login-darken"></div>
		<div class="container aside-xxl">
		<div class="account-box">
		 <a class="navbar-brand block" href="<?=base_url()?>">
                    <?php $display = config_item('logo_or_icon'); ?>
			<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('login_logo')?>" class="img-fluid">
			<!-- <span style="color:#000;"> 
			<?php } ?>
			<?php 
                       if ($display == 'logo_title') {
                            if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
                        }
                        ?>
						</span> -->
						
                 </a>
		 <section class="card card-default bg-white m-t-lg m-t-60">
		<header class="card-header text-center"> <strong>Change Password <?=config_item('company_name')?></strong> </header>
		<?php $this->load->view('modal/flash_message'); ?> 

		<?php 
		$attributes = array('class' => 'card-body wrapper-lg','id'=>'reset_password');
		echo form_open($this->uri->uri_string(),$attributes); ?>
			<div class="form-group">
				<label class="control-label"><?=lang('new_password')?></label>
				<?php echo form_password($new_password); ?>
				<span style="color: red;">
				<?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=lang('confirm_password')?> </label>
				<?php echo form_password($confirm_new_password); ?>
				<span style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></span>
			</div>


			<button type="submit" class="btn btn-success" id="change_password">Change Password</button>
			<div class="line line-dashed">
			</div> 
			<!-- <?php if (config_item('allow_registration', 'tank_auth')){ ?>
			<p class="text-muted text-center"><small>Do not have an account?</small></p> 
			<?php } ?> -->
			<a href="#" href="#" data-toggle="modal"  data-target="#apply_job" class="btn btn-success btn-block" style="color:#fff;">Get Your Account</a>
<?php echo form_close(); ?>

 </section>
	</div> 
	</div>
	</div>

	<?php $this->load->view('modal/register');?>

	<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
</script>