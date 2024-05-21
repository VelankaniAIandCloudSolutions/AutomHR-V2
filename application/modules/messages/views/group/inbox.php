<?php foreach ($messages as $key => $msg) { ?>
<li class="<?php if($msg->status == 'Unread'){echo  "unread"; }?>">
	<a href="<?=base_url()?>messages/view/<?=$msg->user_from?>">
		<div class="thumb-xs pull-left m-r-sm">
			<img src="<?php echo User::avatar_url($msg->user_from); ?>" class="img-circle">
		</div>
		<span class="mail-date">
			<?php
			
			   echo Applib::time_elapsed_string(strtotime($msg->date_received),'UTC'); ?>
		</span>
		<span class="name"><?=ucfirst(User::displayName($msg->user_from));?></span>
		<span class="subject">
			<?php $longmsg = strip_tags($msg->message); $message = word_limiter($longmsg, 6); echo $message; ?>
		</span> 
	</a> 
</li>
<?php } ?>
<?php if(count($messages) == 0 ){ ?>
<li class="no-messages">
	<i class="fa fa-envelope fa-5x"></i>
	<h4 class="no-msg-title"><?=lang('nothing_to_display')?></h4>
</li>
<?php } ?>