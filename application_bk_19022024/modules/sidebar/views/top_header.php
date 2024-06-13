<div class="header-<?=config_item('top_bar_color')?> header">
	<div class="header-left">
		<a class="logo" href="<?=base_url()?>">
			<?php $display = config_item('logo_or_icon'); ?>
		<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('company_logo')?>">
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
	
	<div class="page-title-box pull-left">
		<h3>
			<?php 
			if ($display == 'logo_title') {
				if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
			} ?>
		</h3>
	</div>
	<a href="#nav" class="mobile_btn pull-left" id="mobile_btn"><i aria-hidden="true" class="fa fa-bars"></i></a>
	<ul class="nav navbar-nav navbar-right nav-user pull-right">
		<?php $role = User::login_role_name(); 
		$user_id = $this->session->userdata('user_id');
		$dept_id = $this->db->get_where('dgt_users',array('id'=> $user_id))->row_array();
		$department_id = $dept_id['department_id'];
		$last_login = $dept_id['last_login'];
		$lastseen = config_item('last_seen_activities');
		$get_activities = $this->db->select('*')
						  ->from('dgt_activities')
						  ->where('activity_date >',date("Y-m-d H:i:s",$lastseen))
						  ->group_start()
						  ->where("FIND_IN_SET('".$department_id."', value2)")
						  ->or_where('value2','00')
						  ->group_end()
						  ->get()->result_array();
						  
		$cur_date = date('Y-m-d');
						  
		$history_use = $this->db->select('*')->from('transfer_history')->where('status',0)->where('effective_date',$cur_date)->get()->row_array();
		  if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
			<li>
				<a href="javascript:void(0)" class="btn add-btn red_back <?php if(empty($history_use)){ echo 'disabled';}?>" <?php if(!empty($history_use)){?> onclick="transfer_entity_employee()"<?php }?>><?=lang('trans_entity');?></a>
			</li>
		  <?php }?>
		<!--<li>
			<a id="user-activities v" href="<?=base_url()?>profile/activities">
			<?php if ($role == 'admin') {
				$lastseen = config_item('last_seen_activities');
				$activities = $this->db->where('activity_date >',date("Y-m-d H:i:s",$lastseen))->get('activities')->result();
				$act = count($activities);
				$badge = 'bg-purple';
				if ($act == 0) $badge = 'bg-purple';
			  ?>
			 <span class="badge <?=$badge;?> pull-right"><?=$act;?></span>
			<i class="fa fa-bell-o"></i><?php } elseif($role == 'staff') 
			{ 

			$act_staff = count($get_activities);
			$badge_staff = 'bg-purple';
			if ($act_staff == 0) $badge_staff = 'bg-purple';
			?><span class="badge <?=$badge_staff;?> pull-right"><?=$act_staff;?></span><i class="fa fa-bell-o"></i><?php } ?>
			</a>
		</li>-->
		<li class="hidden-xs">
			<!--<a href="javascript:;" data-toggle="sidebar-chat" onclick="show_user_sidebar()">
				<i class="fa fa-comment-o"></i>
			</a>-->
			<a  href="<?=base_url()?>chats">
				<i class="fa fa-comment-o"></i>
			</a>
	   </li>
		<?php // foreach ($timers as $timer) : if ($role == 'admin' || ($role == 'staff' && User::get_id() == $timer['user'])) : ?>
			<?php //	$type = (isset($timer['task'])) ? 'task' : 'project'; 
					//$title = (isset($timer['task'])) ? Project::view_task($timer['task'])->task_name : Project::by_id($timer['project'])->project_title;
					//$id = (isset($timer['task'])) ? $timer['pro_id'] : $timer['project']; 
			?> 
			<!-- <li class="timer hidden-xs" start="<?php //echo $timer['start_time']; ?>">
				<a title="<?php //echo lang($type).": ".$title.' by '.User::displayName($timer['user']); ?>" data-placement="bottom" data-toggle="tooltip" class="dker" href="<?php //echo site_url('projects/view/'.$id).($type == 'task' ? '?group=tasks':'');  ?>">
					<img src="<?php //echo User::avatar_url($timer['user']); ?>" class="img-rounded">
					<span></span>
				</a>
			</li> -->
		<?php // endif; endforeach; ?>
		<?php $up = count($updates); ?>
		<li class="dropdown main-drop">
			<a href="#" class="dropdown-toggle user-link" data-toggle="dropdown">
				<span class="user-img">
					<?php
					$user = User::get_id();
					$user_email = User::login_info($user)->email;

					//new
					$images=User::profile_info($user)->avatar;

					if($images=='' || $images=='default_avatar.jpg')
					{
						$img_url=base_url().'assets/avatar/default_avatar.jpg';
					}
					else
					{
						if(file_exists('assets/avatar/'.$images))
						{	
							$img_url=User::avatar_url($user);
						}
						else
						{
							$img_url=base_url().'assets/avatar/default_avatar.jpg';
						}
					}
					?>
					<img src="<?php echo $img_url?>" class="img-circle" width="40">
				</span>
				<span><?php echo User::displayName($user);?></span>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<?php if(($role != 'admin') && ($role != 'superadmin')){ ?>
					<li><a href="<?=base_url()?>employees/profile_view/<?php echo $this->session->userdata('user_id'); ?>">My Profile</a></li>
				<?php } ?>
				<li><a href="<?=base_url()?>profile/settings"><?=lang('settings')?></a></li>
				<li> <a href="<?=base_url()?>logout" ><?=lang('logout')?></a> </li>
			</ul>
		</li>
	</ul>
	
            <div class="dropdown mobile-user-menu pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu pull-right">
                	<?php if(($role != 'admin') && ($role != 'superadmin')){ ?>
						<li><a href="<?=base_url()?>employees/profile_view/<?php echo $this->session->userdata('user_id'); ?>">My Profile</a></li>
					<?php } ?>
                    <li><a href="<?=base_url()?>profile/settings"><?=lang('settings')?></a></li>
				<?php /*<li>
					<a id="user-activities" href="<?=base_url()?>profile/activities">
					<?php if ($role == 'admin') {
						$lastseen = config_item('last_seen_activities');
						$activities = $this->db->where('activity_date >',date("Y-m-d H:i:s",$lastseen))->get('activities')->result();
						$act = count($activities);
						$badge = 'bg-danger';
						if ($act == 0) $badge = 'bg-success';
					?>
					 <span class="badge <?=$badge;?> pull-right"><?=$act;?></span>
					<?php } ?><?=lang('activities')?>
					</a>
				</li>*/?>
				<li> <a href="<?=base_url()?>logout" ><?=lang('logout')?></a> </li>
                </ul>
            </div>
	
</div>

<div class="chat-window-container" id="chat-window-container">

</div>
<style>
.red_back{
	height:45px !important;
	line-height:45px !important;
	margin-top:8px !important;
	background-color:#ff0000 !important;
	border-color:#ff0000 !important;
}
.red_back:hover{
	background-color:green !important;
	border-color:green !important;
}
</style>
<script>
function transfer_entity_employee(){
	$.ajax({
		type: "GET",
		url: '<?php echo base_url('transfer_entity')?>',
		success: function (data) {
			if(data == 'Success'){
				 toastr.success('Entity Transfered Successfully');
				setTimeout(function(){ toastr.success('Entity Transfered Successfully');  
					location.reload();
				}, 1500);
			}
		}
	});
}

var uri_page_forcl = "<?php echo uri_string(); ?>";

</script>
