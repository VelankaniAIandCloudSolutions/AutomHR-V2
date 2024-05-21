<?php foreach ($messages as $key => $msg) { ?>
<li class="<?php if($msg->status == 'Unread'){echo  "unread"; }?>">
	<div class="trash-link">
		<div class="thumb-xs pull-left m-r-sm">
			<img src="<?php echo User::avatar_url($msg->user_from); ?>" class="img-circle">
		</div>
		<span class="mail-date">
			<?php echo Applib::time_elapsed_string(strtotime($msg->date_received)); ?>
		</span>
		<span class="name"><?=ucfirst(User::displayName($msg->user_from));?></span>
		<span class="subject">
			<?=strip_tags($msg->message);?>
		</span> 
		<span class="perm-delete"> 
			<a href="<?=base_url()?>messages/restore/<?=$msg->msg_id?>" title="Restore"><i class="fa fa-retweet text-primary"></i></a>
			<a href="<?=base_url()?>messages/remove/<?=$msg->msg_id?>" title="Permanent Delete"><i class="fa fa-times text-danger"></i></a>
		</span>
	</div>
</li>
<?php } ?>
<?php if(count($messages) == 0 ){ ?>
<li class="no-messages">
	<i class="fa fa-envelope fa-5x"></i>
	<h4 class="no-msg-title"><?=lang('nothing_to_display')?></h4>
</li>
<?php } ?>