<!-- INCOMING  CALL POPUP -->
<div id="incoming_call" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="profile-widget">
					<div class="profile-img">
						<a href="javascript:void(0)" class="avatar"><img src="<?php echo base_url(); ?>assets/img/user-03.jpg" alt="" class="caller_image"></a>
					</div>
					<h4 class="user-name m-t-10 m-b-0 text-ellipsis"><a href="client-profile.html" class="caller_name"></a></h4>
					<div class="small text-muted"></div>
				<div class="incoming-btns">
								<input type="hidden" name="caller_login_id" class="caller_login_id">
								<input type="hidden" name="caller_sinchusername" class="caller_sinchusername">
								<input type="hidden" name="caller_full_name" class="caller_full_name">
								<input type="hidden" name="caller_profile_img" class="caller_profile_img">
						<a href="javascript:void(0)" class="btn btn-success m-r-10" id="answer">Answer</a>
						<a href="javascript:void(0)" class="btn btn-danger" data-dismiss="modal" id="hangup">Decline</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- Edit Group Name -->

<div id="edit_group" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Edit group name</h3>
			</div>
			<div class="modal-body">
				<p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for example.</p>
				<form id="edit_group_form" method="post">
					<div class="form-group">
						<label>Group Name <span class="text-danger">*</span></label>
						<input class="form-control"  type="text" name="group_name" id="edit_group_name">					
					</div>					
					<div class="m-t-50 text-center">
						<button class="btn btn-primary btn-lg" type="submit" >Update Group</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>





<!-- ADD NEW USER For GROUP POPUP  -->

<div id="add_group_user" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Add New user</h3>
			</div>
			<div class="modal-body">				
				<form id="group_user_form" method="post">					
					<div class="form-group">
						<label>Send invites to: <span class="text-muted-light"></span></label>
						<input class="form-control"  type="text" name="members" id="members1" placeholder="e.g: username1,username2" data-role="tagsinput">
					</div>
					<div class="m-t-50 text-center">
						<button class="btn btn-primary btn-lg" type="submit" >Add User</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- ADD NEW GROUP POPUP  -->

<div id="add_group" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Create a group</h3>
			</div>
			<div class="modal-body">
				<p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for example.</p>
				<form id="group_form" method="post">
					<div class="form-group">
						<label>Group Name <span class="text-danger">*</span></label>
						<input class="form-control"  type="text" name="group_name" id="group_name">
						<input class="form-control"  type="hidden" name="group_type" id="group_type">
					</div>
					<div class="form-group">
						<label>Send invites to: <span class="text-muted-light"></span></label>
						<input class="form-control"  type="text" name="members" id="members" placeholder="e.g: username1,username2" data-role="tagsinput">
					</div>
					<div class="m-t-50 text-center">
						<button class="btn btn-primary btn-lg" type="submit" >Create Group</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- ADD NEW USER POPUP  -->
<div id="add_chat_user" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Direct Chat</h3>
			</div>
			<div class="modal-body">
				<div class="input-group m-b-30">
					<input placeholder="Search to start a chat" class="form-control search-input input-lg" id="search_user" type="text">
					<span class="input-group-btn">
						<button class="btn btn-primary btn-lg" onclick="search_user()">Search</button>
					</span>
				</div>
				<div>
					<h5>Recent Conversations</h5>
					<div id="user_list" data-type=""></div>									
				</div>
			</div>
		</div>
	</div>
</div>



<div id="screen_share" class="modal custom-modal fade center-modal" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Screen Share</h3>
			</div>
			<div class="modal-body">
				<p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for example.</p>
				<form id="screen_share_form" method="post">
					<div class="form-group">
						<label>Group Name <span class="text-danger">*</span></label>
						<input class="form-control"  type="text" name="share_group_name" id="share_group_name">
					</div>
					<div class="form-group">
						<label>Send invites to: <span class="text-muted-light"></span></label>
						<input class="form-control"  type="text" name="share_members" id="share_members" data-role="tagsinput">
					</div>
					<div class="m-t-50 text-center">
						<button class="btn btn-primary btn-lg" type="submit" >Send Invite</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>