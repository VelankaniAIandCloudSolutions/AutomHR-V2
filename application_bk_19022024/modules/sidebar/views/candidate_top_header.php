<div class="header-<?=config_item('top_bar_color')?> header">
	<div class="header-left">
		<a class="logo" href="<?=base_url()?>/candidates/dashboard">
			<?php $display = config_item('logo_or_icon'); ?>
		<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('company_logo')?>" alt="Logo">
		<?php } ?>
		</a>
	</div>
	
	<a id="toggle_btn" href="javascript:void(0);">
		<span class="bar-icon">
			<span></span>
			<span></span>
			<span></span>
		</span>
	</a>
	
	<div class="page-title-box float-left">
		<h3><?=config_item('company_name')?>
			<?php 
			if ($display == 'logo_title') {
				if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
			} ?>
		</h3>
	</div>
	<a href="#nav" class="mobile_btn float-left" id="mobile_btn"><i aria-hidden="true" class="fa fa-bars"></i></a>
	<ul class="navbar-nav navbar-right nav-user float-right flex-row user-menu">
		<!-- search -->
		<li class="nav-item search-li" style="display:none">
			<div class="top-nav-search">
				<a href="javascript:void(0);" class="responsive-search">
					<i class="fa fa-search"></i>
			   </a>
				<form action="search.html">
					<input class="form-control" type="text" placeholder="Search here">
					<button class="btn" type="submit"><i class="fa fa-search"></i></button>
				</form>
			</div>
		</li>
		<!-- search -->
	<!-- Flag -->
	<?php if(config_item('enable_languages') == 'TRUE'){  ?>
		<?php $set_lang = ($this->session->userdata('lang'))?$this->session->userdata('lang'):'english';
		
		?>
		<li class="nav-item dropdown has-arrow flag-nav">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
				<?php foreach (App::languages() as $lang) { if ($lang->name == $set_lang) { ?>
				<img src="<?=base_url()?>assets/images/flags/<?=$lang->icon?>.png" alt="" height="20"> <span><?php echo  ucfirst($set_lang);?></span>
				<?php } } ?>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<?php foreach (App::languages() as $lang) { if ($lang->active == 1) { ?>

                    

                        <a href="<?=base_url()?>set_language?lang=<?=$lang->name?>" title="<?=ucwords(str_replace("_"," ", $lang->name))?>" class="dropdown-item">

                            <img src="<?=base_url()?>assets/images/flags/<?=$lang->icon?>.png" alt="<?=ucwords(str_replace("_"," ", $lang->name))?>"  height="16"/> <?=ucwords(str_replace("_"," ", $lang->name))?>

                        </a>

                    

                    <?php } }  ?>
				<!-- <a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/us.png" alt="" height="16"> English
				</a>
				<a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/fr.png" alt="" height="16"> French
				</a>
				<a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/es.png" alt="" height="16"> Spanish
				</a>
				<a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/de.png" alt="" height="16"> German
				</a> -->
			</div>
		</li>
	<?php } ?>
	<!-- flag -->
	<!-- <ul class="nav navbar-nav navbar-right nav-user float-right"> -->
		
		<!--  <li class="hidden-xs nav-item">
			<a class="nav-link" href="javascript:;" data-toggle="sidebar-chat" onclick="show_user_sidebar()">
				<i class="fa fa-comment-o"></i>
			</a>
	   </li>  -->
		<?php $candidate_details = $this->db->get_where('registered_candidates',array('first_name'=>$this->session->userdata('username')))->row_array();?>
		<li class="dropdown main-drop nav-item">
			<a href="#" class="nav-link user-link" data-toggle="dropdown">
				<span class="user-img">
					<img src="<?php echo base_url()?>assets/avatar/default_avatar.jpg" class="rounded-circle" width="40" alt="">
				</span>
				<span><?php echo ucfirst($candidate_details['first_name']).' '.ucfirst($candidate_details['last_name']);?></span>
				<b class="caret"></b>
			</a>
			<div class="dropdown-menu">
		
				<a class="dropdown-item" href="<?=base_url()?>candidates/candidate_profile"><?=lang('account_settings')?></a>
				 <a class="dropdown-item" href="<?=base_url()?>candidates/logout" ><?=lang('logout')?></a>
			</div>
		</li>
	</ul>
	
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu float-right dropdown-menu-right">
                    <a class="dropdown-item" href="<?=base_url()?>profile/settings"><?=lang('settings')?></a>
				
				<a class="dropdown-item" href="<?=base_url()?>logout" ><?=lang('logout')?></a> 
                </div>
            </div>
	
</div>

