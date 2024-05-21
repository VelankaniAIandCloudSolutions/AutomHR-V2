<div class="content">
	<?php if(!$this->session->userdata('candidate_id')){

		?>
	<div class="row">
		<div class="col-4">
			<h4 class="page-title">Collaboration</h4>
		</div>
	</div>
<div class="card-box">
		<ul class="nav nav-tabs nav-tabs-solid page-tabs">
			<li class="nav-item"><a class="nav-link active" href="<?php echo base_url(); ?>chats">Chat & Call</a></li>
			<li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>messages">Email</a></li>
		</ul>
	</div>
<?php  } ?>
                                            <!--<div id="profile-info-collapse">
                                                <div id="dismiss" onclick="dismiss_close();">
                                                    <i class="glyphicon glyphicon-arrow-left"></i>
                                                </div>                                                    
                                                <div class="single-profile-details">
                                                        <h3 class="m-b-20" id="fullname_chat">John Doe</h3>
                                                        <div class="user-status-blk">
                                                            <span class="user-img">
                                                                <img class="rounded-circle" id="user_img_chat" src="" width="104" alt="Admin">
                                                                <span class="status" id="user_status_chat"></span>
                                                            </span>
                                                        </div>
                                                        <hr/>
                                                        <div class="chat-profile-info">
                                                            <ul class="user-det-list text-left">
                                                                <li>
                                                                    <span class="text-left">Username:</span>
                                                                    <span class="float-right text-muted" id="chat_username">johndoe</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-left">Email:</span>
                                                                    <span class="float-right text-muted" id="user_email_chat">johndoe@example.com</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-left">Phone:</span>
                                                                    <span class="float-right text-muted" id="user_phone_chat">9876543210</span>
                                                                </li>
                                                            </ul>
                                                        </div>                                                        
                                                    </div>
                                                    <div class="grp-profile-details">
                                                    <h3 id="chatgroup_name"></h3>
                                                    <img class="img img-fluid center-block group-lg-icon" width="140px" src="images/group.svg">
                                                    <hr>
                                                    <h4>Members</h4>
                                                    <div class="project-members m-b-15" >
                                                        <ul class="team-members" id="chatGroup_members">

                                                        </ul>
                                                    </div>                                                        
                                                </div>
                                            </div>      -->      
                                                    <!--<div class="sticky-quicklinks">
                                                                <ul class="sticky list-unstyled m-b-0">
                                                                    <li>
                                                                        <a href="javascript:;" id="profileCollapse" class="" onclick="profileinfo();"> 
                                                                            <img class="stick-icon effect-fadeout hover-animate img img-fluid" src="images/man-user.svg" />
                                                                        </a>
                                                                    </li>                                                                    
                                                                    <li onclick="handle_video_panel(0)">
                                                                        <a href="#" ><img class="stick-icon effect-fadeout hover-animate img img-fluid" src="<?php echo base_url(); ?>images/call.svg" /></a>
                                                                    </li>
                                                                    <li  onclick="handle_video_panel(1)">
                                                                        <a href="#"><img class="stick-icon effect-fadeout hover-animate img img-fluid" src="<?php echo base_url(); ?>images/video-camera.svg" /></a>
                                                                    </li>
                                                                </ul>
                                                            </div> -->
<div class="call-box outgoing-box">
	<div class="call-wrapper">
		<div class="call-inner">
			<div class="call-user">
				<img class="call-avatar" src="" alt="avatar" id="outgoing_call_image">
				<h4 id="outgoing_username">Palani</h4>
				<span>Connecting...</span>
			</div>							
			<div class="call-items">
				<button class="btn call-item"><i class="material-icons">mic</i></button>
				<!-- <button class="btn call-item"><i class="material-icons">videocam</i></button> -->
				<button class="btn call-item call-end"><i class="material-icons user_vcend">call_end</i></button>
				<!-- <button class="btn call-item"><i class="material-icons">person_add</i></button> -->
				<button class="btn call-item"><i class="material-icons">volume_up</i></button>
			</div>
		</div>
	</div>
</div>
<div class="call-box incoming-box">
	<div class="call-wrapper">
		<div class="call-inner">
			<div class="call-user">
				<img class="call-avatar" id="incoming_call_userpic" src="" alt="avatar">
				<h4 id="incoming_call_username">Soosairaj</h4>
				<span>Calling ...</span>
			</div>							
			<div class="call-items">
				
				<?php if($this->session->userdata('candidate_id')){ $id_name="user_answer"; $end_id = 'user_hangup'; }else{ echo "answer"; $end_id = 'hangup';}?>
				<button class="btn call-item call-end"><i class="material-icons" id="<?php echo $end_id;?>">call_end</i></button>
				<button class="btn call-item call-start"><i class="material-icons" id="<?php echo $id_name;?>">call</i></button>
			</div>
		</div>
	</div>
</div>
<div class="chat-main-row video-blk-right chat-call-placeholder">
<div class="msg-inner">
	<div class="no-messages">
		<i class="material-icons">forum</i>
	</div>
</div>
<div class="chat-main-wrapper" id="chat_user_window">
<div class="col-9 message-view task-view">
    <div class="chat-window">
        <div class="chat-header">
            <div class="navbar">
                <div class="user-details">
                    <div class="float-left user-img m-r-10">
                        <!-- <a href="#" title="Mike Litorus"> -->
                            <img src="" alt="" id="MsgviewUserChat" class="w-40 rounded-circle"><span class="status online" id="MsgviewStatus"></span>
                        <!-- </a> -->
                    </div>
                    <div class="user-info float-left">
                        <a href="javascript:void(0);" title=""><span class="font-bold" id="chatMenuuser"></span> 
                            <!-- <i class="typing-text">Typing...</i> -->
                        </a>
                        <span class="last-seen" id="LastseenUserChat"></span>
                    </div>
                </div>
                <ul class="nav navbar-nav float-right chat-menu">
                    <li class="dropdown">
                        <a href="javascript:;" id="user_detailss" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Group Members">
							<i class="material-icons md-30">person</i>
						</a>
                        <ul class="dropdown-menu">
                            <li>
                            <div class="group-members">
								<div class="project-members">
									<ul class="team-members" id="chatGroup_members">

									</ul>
								</div>  
							</div>  
							</li>
                        </ul>
                    </li>
					<!--<li>
						<a href="javascript:;" id="profileCollapse" class="" onclick="profileinfo();">
							<i class="material-icons md-30">person</i>
						</a>
					</li> -->                                                                 
					<!-- <li onclick="handle_video_panel_job(0)">
						<a href="#" ><i class="material-icons md-30">phone_in_talk</i></a>
					</li> -->
					<li  onclick="handle_video_panel_job(1)">
						<a href="#"><i class="material-icons md-36">videocam</i></a>
					</li>
                    <!-- <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)">Delete Conversations</a></li>
                        </ul>
                    </li> -->
                </ul>
				<!--<div id="profile-info-collapse">
					<div id="dismiss" onclick="dismiss_close();">
						<i class="glyphicon glyphicon-arrow-left"></i>
					</div>                                                    
					<div class="single-profile-details">
						<h3 class="m-b-20" id="fullname_chat">John Doe</h3>
						<div class="user-status-blk">
							<span class="user-img">
								<img class="rounded-circle" id="user_img_chat" src="" width="104" alt="Admin">
								<span class="status" id="user_status_chat"></span>
							</span>
						</div>
						<hr/>
						<div class="chat-profile-info">
							<ul class="user-det-list text-left">
								<li>
									<span class="text-left">Username:</span>
									<span class="float-right text-muted" id="chat_username">johndoe</span>
								</li>
								<li>
									<span class="text-left">Email:</span>
									<span class="float-right text-muted" id="user_email_chat">johndoe@example.com</span>
								</li>
								<li>
									<span class="text-left">Phone:</span>
									<span class="float-right text-muted" id="user_phone_chat">9876543210</span>
								</li>
							</ul>
						</div>                                                        
					</div>
					<div class="grp-profile-details">
						<h3 id="chatgroup_name"></h3>
						<img class="img img-fluid center-block group-lg-icon" width="140px" src="images/group.svg">
						<hr>
						<h4>Members</h4>
						<div class="project-members m-b-15" >
							<ul class="team-members" id="chatGroup_members">

							</ul>
						</div>                                                        
					</div>
				</div> -->
                <a href="#task_window" class="task-chat profile-rightbar float-right"><i class="fa fa-user" aria-hidden="true"></i></a>
                <div class="message-search float-right">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search" required="">
                        <span class="input-group-btn">
                            <button class="btn" type="button"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-contents">
            <div class="chat-content-wrap">
                <div class="chat-wrap-inner">
                    <div class="chat-box">
                        <div class="chats ChatHistoryCandidate">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-footer">


            <!--
	for hide message option div

            <form>
                
            <div class="message-bar">
                <div class="message-inner">
                    <!-- <a class="link attach-icon" href="#" data-toggle="modal" data-target="#drag_files"><img src="images/attachment.png" alt=""></a> --
                    <div class="message-area"><div class="input-group">
							<input type="" class="form-control" id="msgTxt" name="msgTxt" placeholder="Type message...">
                            <!--<textarea class="form-control" id="msgTxt" name="msgTxt" placeholder="Type message..."></textarea> --
                            <input type="hidden" name="hid_group_id" id="hid_group_id" >
                            <span class="input-group-btn">
                                <button class="btn btn-custom" type="submit"><i class="fa fa-send"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            </form>-->
        </div>
    </div>
</div>
						<div class="col-3 profile-right fixed-sidebar-right chat-profile-view" id="task_window">
							<div class="display-table profile-right-inner">
								<div class="table-row">
									<div class="table-body">
										<div class="table-content">
											<div class="chat-profile-img">
												<div class="edit-profile-img">
													<img class="avatar" src="assets/img/user.jpg" id="side_profileimg" alt="" style="height: 100%;">
												</div>
												<h3 class="user-name m-t-10 m-b-0" id="side_profile_name">John Doe</h3>
												<small class="text-muted" id="side_profile_destination">Web Designer</small>
												<!-- <a href="#" class="btn btn-primary edit-btn"><i class="fa fa-pencil"></i></a> -->
											</div>
											<div class="chat-profile-info">
												<ul class="user-det-list">
													<li>
														<span>Username:</span>
														<span class="float-right text-muted" id="side_user_name">johndoe</span>
													</li>
													<li>
														<span>DOB:</span>
														<span class="float-right text-muted" id="side_dob">24 July</span>
													</li>
													<li>
														<span>Email:</span>
														<span class="float-right text-muted" id="side_user_email">johndoe@example.com</span>
													</li>
													<li>
														<span>Phone:</span>
														<span class="float-right text-muted" id="side_user_phone">9876543210</span>
													</li>
												</ul>
											</div>
											<!-- <div class="tabbable">
												<ul class="nav nav-tabs nav-tabs-solid nav-justified m-b-0">
													<li class="active"><a href="#all_files" data-toggle="tab">All Files</a></li>
													<li><a href="#my_files" data-toggle="tab">My Files</a></li>
												</ul>
												<div class="tab-content">
													<div class="tab-pane active" id="all_files">
														<ul class="files-list">
															<li>
																<div class="files-cont">
																	<div class="file-type">
																		<span class="files-icon"><i class="fa fa-file-pdf-o"></i></span>
																	</div>
																	<div class="files-info">
																		<span class="file-name text-ellipsis">AHA Selfcare Mobile Application Test-Cases.xls</span>
																		<span class="file-author"><a href="#">Loren Gatlin</a></span> <span class="file-date">May 31st at 6:53 PM</span>
																	</div>
																	<ul class="files-action">
																		<li class="dropdown">
																			<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
																			<ul class="dropdown-menu">
																				<li><a href="javascript:void(0)">Download</a></li>
																				<li><a href="#" data-toggle="modal" data-target="#share_files">Share</a></li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</li>
														</ul>
													</div>
													<div class="tab-pane" id="my_files">
														<ul class="files-list">
															<li>
																<div class="files-cont">
																	<div class="file-type">
																		<span class="files-icon"><i class="fa fa-file-pdf-o"></i></span>
																	</div>
																	<div class="files-info">
																		<span class="file-name text-ellipsis">AHA Selfcare Mobile Application Test-Cases.xls</span>
																		<span class="file-author"><a href="#">John Doe</a></span> <span class="file-date">May 31st at 6:53 PM</span>
																	</div>
																	<ul class="files-action">
																		<li class="dropdown">
																			<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
																			<ul class="dropdown-menu">
																				<li><a href="javascript:void(0)">Download</a></li>
																				<li><a href="#" data-toggle="modal" data-target="#share_files">Share</a></li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div> -->
										</div>
									</div>
								</div>
							</div>
						</div>
</div>

                <div class="chat-main-wrapper" id="live_video_chat" style="display: none;">
                    <div class="col-9 message-view task-view">
                        <div class="chat-window">
                            <div class="chat-header">
                                <div class="navbar">
                                    <div class="user-details">
                                        <div class="float-left user-img m-r-10">
                                            <img src="" id="call_user_pic" alt="" class="w-40 rounded-circle"><span class="status online"></span>
                                        </div>
                                        <div class="user-info float-left">
                                            <a href="#" title="Mike Litorus"><span class="font-bold" id="call_user_name">Mike Litorus</span></a>
                                            <!-- <span class="last-seen">Online</span> -->
                                        </div>
                                    </div>
                                    <ul class="nav navbar-nav float-right chat-menu">
                                        <li>
											<a href="javascript:;" class="fullscreen-fa"><i class="fa fa-arrows-alt custom-fa" aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="chat-contents">
                                <div class="chat-content-wrap">
								<div id="outgoing" class="video-background user-video"></div>
                                    <!--<div class="user-video">
                                        <img src="assets/img/video-call.jpg" alt="">
                                    </div> -->
                                        <div class="my-video" id="member_tab">
                                            <div class="chat-wrap-inner">
                                                <div class="chat-box">
                                                    <div class="chats group_members">
                                                        <div id="temp"></div>
                                                        <?php 
                                                        $group_name='';
                                                        $group_id = $this->session->userdata('session_group_id');
                                                        if(!empty($group_id)){

                                                            $group_name = $this->db
                                                            ->select('group_name')
                                                            ->get_where('dgt_chat_group_details',array('group_id'=>$group_id))
                                                            ->row_array();
                                                            // print_r($group_name);
                                                            // echo 'hi'.$this->login_id; exit;
                                                            $this->db->select('l.username,l.id,ad.avatar,ad.fullname, cg.members_id');
                                                            $this->db->from('dgt_chat_group_members cg');
                                                            $this->db->where('cg.group_id',$group_id);
                                                            $this->db->where('cg.login_id !=',$this->login_id);
                                                            $this->db->join('dgt_users l','l.id = cg.login_id');
                                                            $this->db->join('dgt_account_details ad','l.id = ad.user_id');
                                                            $group_members= $this->db->get()->result_array();
                                                            // print_r($group_members); 


                                                            if(!empty($group_members)){


                                                                foreach ($group_members as  $g) {

                                                                    if(!empty($g['avatar'])){
                                                                        $receivers_image = $g['avatar'];
                                                                        $file_to_check = FCPATH . 'assets/uploads/' . $receivers_image;
                                                                        if (file_exists($file_to_check)) {
                                                                            $receivers_image = base_url() . 'assets/uploads/'.$receivers_image;
                                                                        }
                                                                    }
                                                                    $receivers_image = (!empty($g['avatar']))?$receivers_image : base_url().'assets/img/user.jpg';

                                                                    echo '<div class="test" >
                                                                    <img src="'.$receivers_image.'" title ="'.$g['fullname'].'" class="img-fluid outgoing_image" alt="" id="image_'.$g['username'].'" >
                                                                    <video id="video_'.$g['username'].'" autoplay unmute class="hidden"></video>
                                                                    <span class="thumb-title">'.$g['fullname']?$g['fullname']:'-'.'</span>
                                                                    </div>';
                                                                }
                                                            }
                                                        }else{
                                                            echo '<div class="test" >
                                                                    <img src="'.$receiver_profile_img.'" title ="'.$name.'" class="img-fluid outgoing_image" alt="" id="image_'.$receiver_sinchusername.'" >
                                                                    <video id="video_'.$receiver_sinchusername.'" autoplay unmute class="hidden"></video>
                                                                    <span class="thumb-title">'.$name.'</span>
                                                                    </div>';
                                                        }
                                                        
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!--<div class="my-video">
                                        <ul>
                                            <li>
                                                <img src="assets/img/user-02.jpg" class="img-fluid" alt="">
                                            </li>
                                        </ul>
                                    </div> -->
<div class="call-users user-list">
                                        <ul class="chat-user-lists">
											<?php 
											$group_id = $this->session->userdata('session_group_id'); 
												if(!empty($group_id)){

												$this->db->select('CGM.group_id,AD.user_id,AD.fullname,AD.avatar')
														 ->from('dgt_chat_group_members CGM')
														 ->join('dgt_account_details AD', 'AD.user_id = CGM.login_id')
														 ->where('CGM.group_id',$group_id)
														 ->get()->result_array();

											foreach($all_group_members as $all_group_member)
											{ ?>


												<li>
													<a href="javascript:void(0)">
														<img src="<?php echo base_url(); ?>'assets/avatar/'<?php echo $all_group_member['avatar']; ?>" class="img-fluid" alt="">
													</a>
												</li>
											<?php 
											}
											}
											?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-footer">
                                <div class="call-icons">
                                    <span class="call-duration"></span>
                                    <ul class="call-items">
                                        <li class="call-item">
                                            <a href="javascript:;" class="vccam" title="Enable Video" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-video-camera camera" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li class="call-item">
                                            <a href="javascript:;" title="Mute Audio" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-microphone microphone" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li class="call-item">
                                            <a href="javascript:;" title="Add User" data-placement="top" data-toggle="tooltip">
                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="end-call">
										<a href="javascript:;" class="user_vcend" data-dismiss="modal">
											End Call
										</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-3 message-view chat-profile-view chat-sidebar" id="chat_sidebar">
                        <div class="chat-window video-window">
                            <div class="chat-header">
                                <ul class="nav nav-tabs nav-tabs-bottom">
                                    <li class="nav-item active"><a class="nav-link active" href="#calls_tab" data-toggle="tab">Calls</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#chats_tab" data-toggle="tab">Chats</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#profile_tab" data-toggle="tab">Profile</a></li>
                                </ul>
                            </div>
                            <div class="tab-content chat-contents">
                                <div class="content-full tab-pane active" id="calls_tab">
                                    <div class="chat-wrap-inner">
                                        <div class="chat-box">
                                            <div class="chats">
                                                <div class="chat chat-left">
                                                    <div class="chat-avatar">
                                                        <a href="#" class="avatar">
                                                            <img alt="John Doe" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="chat-body">
                                                        <div class="chat-bubble">
                                                            <div class="chat-content">
                                                                <span class="task-chat-user">You</span> <span class="chat-time">8:35 am</span>
                                                                <div class="call-details">
                                                                    <i class="material-icons">phone_missed</i>
                                                                    <div class="call-info">
                                                                        <div class="call-user-details">
                                                                            <span class="call-description">Jeffrey Warden missed the call</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="chat chat-left">
                                                    <div class="chat-avatar">
                                                        <a href="#" class="avatar">
                                                            <img alt="John Doe" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="chat-body">
                                                        <div class="chat-bubble">
                                                            <div class="chat-content">
                                                                <span class="task-chat-user">John Doe</span> <span class="chat-time">8:35 am</span>
                                                                <div class="call-details">
                                                                    <i class="material-icons">call_end</i>
                                                                    <div class="call-info">
                                                                        <div class="call-user-details"><span class="call-description">This call has ended</span></div>
                                                                        <div class="call-timing">Duration: <strong>5 min 57 sec</strong></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="chat-line">
                                                    <span class="chat-date">January 29th, 2015</span>
                                                </div>
                                                <div class="chat chat-left">
                                                    <div class="chat-avatar">
                                                        <a href="#" class="avatar">
                                                            <img alt="John Doe" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="chat-body">
                                                        <div class="chat-bubble">
                                                            <div class="chat-content">
                                                                <span class="task-chat-user">Richard Miles</span> <span class="chat-time">8:35 am</span>
                                                                <div class="call-details">
                                                                    <i class="material-icons">phone_missed</i>
                                                                    <div class="call-info">
                                                                        <div class="call-user-details">
                                                                            <span class="call-description">You missed the call</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="chat chat-left">
                                                    <div class="chat-avatar">
                                                        <a href="#" class="avatar">
                                                            <img alt="John Doe" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="chat-body">
                                                        <div class="chat-bubble">
                                                            <div class="chat-content">
                                                                <span class="task-chat-user">You</span> <span class="chat-time">8:35 am</span>
                                                                <div class="call-details">
                                                                    <i class="material-icons">ring_volume</i>
                                                                    <div class="call-info">
                                                                        <div class="call-user-details">
                                                                            <a href="#" class="call-description call-description--linked" data-qa="call_attachment_link">Calling John Smith ...</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-full tab-pane" id="chats_tab">
                                    <div class="chat-window">
                                        <div class="chat-contents">
                                            <div class="chat-content-wrap">
                                                <div class="chat-wrap-inner">
                                                    <div class="chat-box">
                                                        <div class="chats">
                                                            <div class="chat chat-left">
                                                                <div class="chat-avatar">
                                                                    <a href="#" class="avatar">
                                                                        <img alt="John Doe" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                                    </a>
                                                                </div>
                                                                <div class="chat-body">
                                                                    <div class="chat-bubble">
                                                                        <div class="chat-content">
                                                                            <span class="task-chat-user">John Doe</span> <span class="chat-time">8:35 am</span>
                                                                            <p>I'm just looking around.</p>
                                                                            <p>Will you tell me something about yourself? </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="chat chat-left">
                                                                <div class="chat-avatar">
                                                                    <a href="#" class="avatar">
                                                                        <img alt="John Doe" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                                    </a>
                                                                </div>
                                                                <div class="chat-body">
                                                                    <div class="chat-bubble">
                                                                        <div class="chat-content">
                                                                            <span class="task-chat-user">John Doe</span> <span class="file-attached">attached 3 files <i class="fa fa-paperclip" aria-hidden="true"></i></span> <span class="chat-time">Dec 17, 2014 at 4:32am</span>
                                                                            <ul class="attach-list">
                                                                                <li><i class="fa fa-file"></i> <a href="#">project_document.avi</a></li>
                                                                                <li><i class="fa fa-file"></i> <a href="#">video_conferencing.psd</a></li>
                                                                                <li><i class="fa fa-file"></i> <a href="#">landing_page.psd</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="chat-line">
                                                                <span class="chat-date">January 29th, 2017</span>
                                                            </div>
                                                            <div class="chat chat-left">
                                                                <div class="chat-avatar">
                                                                    <a href="#" class="avatar">
                                                                        <img alt="Jeffery Lalor" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                                    </a>
                                                                </div>
                                                                <div class="chat-body">
                                                                    <div class="chat-bubble">
                                                                        <div class="chat-content">
                                                                            <span class="task-chat-user">Jeffery Lalor</span> <span class="file-attached">attached file <i class="fa fa-paperclip" aria-hidden="true"></i></span> <span class="chat-time">Yesterday at 9:16pm</span>
                                                                            <ul class="attach-list">
                                                                                <li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <a href="#">Document_2016.pdf</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="chat chat-left">
                                                                <div class="chat-avatar">
                                                                    <a href="#" class="avatar">
                                                                        <img alt="Jeffery Lalor" src="assets/img/user.jpg" class="img-fluid rounded-circle">
                                                                    </a>
                                                                </div>
                                                                <div class="chat-body">
                                                                    <div class="chat-bubble">
                                                                        <div class="chat-content">
                                                                            <span class="task-chat-user">Jeffery Lalor</span> <span class="file-attached">attached file <i class="fa fa-paperclip" aria-hidden="true"></i></span> <span class="chat-time">Today at 12:42pm</span>
                                                                            <ul class="attach-list">
                                                                                <li class="img-file">
                                                                                    <div class="attach-img-download"><a href="#">avatar-1.jpg</a></div>
                                                                                    <div class="task-attach-img"><img src="assets/img/user.jpg" alt=""></div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-footer">
                                            <div class="message-bar">
                                                <div class="message-inner">
                                                    <a class="link attach-icon" href="#" data-toggle="modal" data-target="#drag_files"><img src="assets/img/attachment.png" alt=""></a>
                                                    <div class="message-area">
                                                        <div class="input-group">
                                                            <textarea class="form-control" placeholder="Type message..."></textarea>
                                                            <span class="input-group-btn">
																<button class="btn btn-primary" type="button"><i class="fa fa-send"></i></button>
															</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-full tab-pane" id="profile_tab">
                                    <div class="display-table">
                                        <div class="table-row">
                                            <div class="table-body">
                                                <div class="table-content">
                                                    <div class="chat-profile-img">
                                                        <div class="edit-profile-img">
                                                            <img src="assets/img/user.jpg" alt="">
                                                            <span class="change-img">Change Image</span>
                                                        </div>
                                                        <h3 class="user-name m-t-10 m-b-0">John Doe</h3>
                                                        <small class="text-muted">Web Designer</small>
                                                        <a href="edit-#" class="btn btn-primary edit-btn"><i class="fa fa-pencil"></i></a>
                                                    </div>
                                                    <div class="chat-profile-info">
                                                        <ul class="user-det-list">
                                                            <li>
                                                                <span>Username:</span>
                                                                <span class="float-right text-muted">johndoe</span>
                                                            </li>
                                                            <li>
                                                                <span>DOB:</span>
                                                                <span class="float-right text-muted">24 July</span>
                                                            </li>
                                                            <li>
                                                                <span>Email:</span>
                                                                <span class="float-right text-muted">johndoe@example.com</span>
                                                            </li>
                                                            <li>
                                                                <span>Phone:</span>
                                                                <span class="float-right text-muted">9876543210</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <ul class="nav nav-tabs nav-tabs-solid nav-justified m-b-0">
                                                            <li class="active"><a href="#all_files" data-toggle="tab">All Files</a></li>
                                                            <li><a href="#my_files" data-toggle="tab">My Files</a></li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="all_files">
                                                                <ul class="files-list">
                                                                    <li>
                                                                        <div class="files-cont">
                                                                            <div class="file-type">
                                                                                <span class="files-icon"><i class="fa fa-file-pdf-o"></i></span>
                                                                            </div>
                                                                            <div class="files-info">
                                                                                <span class="file-name text-ellipsis">AHA Selfcare Mobile Application Test-Cases.xls</span>
                                                                                <span class="file-author"><a href="#">Loren Gatlin</a></span> <span class="file-date">May 31st at 6:53 PM</span>
                                                                            </div>
                                                                            <ul class="files-action">
                                                                                <li class="dropdown">
                                                                                    <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                                                                                    <div class="dropdown-menu">
                                                                                        <a class="dropdown-item" href="javascript:void(0)">Download</a>
                                                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#share_files">Share</a>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="tab-pane" id="my_files">
                                                                <ul class="files-list">
                                                                    <li>
                                                                        <div class="files-cont">
                                                                            <div class="file-type">
                                                                                <span class="files-icon"><i class="fa fa-file-pdf-o"></i></span>
                                                                            </div>
                                                                            <div class="files-info">
                                                                                <span class="file-name text-ellipsis">AHA Selfcare Mobile Application Test-Cases.xls</span>
                                                                                <span class="file-author"><a href="#">John Doe</a></span> <span class="file-date">May 31st at 6:53 PM</span>
                                                                            </div>
                                                                            <ul class="files-action">
                                                                                <li class="dropdown">
                                                                                    <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                                                                                    <div class="dropdown-menu">
                                                                                        <a class="dropdown-item" href="javascript:void(0)">Download</a>
                                                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#share_files">Share</a>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
</div>  
</div>  

            <input type="hidden" name="to_id" id="to_id" value="">
            <input type="hidden" id="call_type" value="audio">
            <input type="hidden" id="group_id" value="">
            <input type="hidden" id="groupId" value="">
            <input type="hidden" name="receiver_id" id="receiver_id" value="<?php echo $receiver_id; ?>">
            <input type="hidden"  id="new_call_type">
            <!--  receiver id  -->

            <input type="hidden" name="time" id="time" > 
            <!-- Current Time  --> 
            <input type="hidden" name="img" id="sender_img" value="<?php echo $profile_img; ?>">
            <!-- Sender Image  -->
            <input type="hidden" name="img" id="receiver_image" value="<?php echo $receiver_profile_img; ?>">
            <!-- Receiver Image  -->

            <input type="hidden" name="message_type" id="type" value="<?php echo $mesage_type ?>" >
            <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id; ?>">


            <audio id="ringback" src="<?php echo base_url().'assets/audio/ringback.wav'; ?>" loop></audio>
            <audio id="ringtone" src="<?php echo base_url().'assets/audio/phone_ring.wav'; ?>" loop></audio>
            <input type="hidden"  id="call_to_id" value="<?php echo $receiver_id ?>" >  
            <input type="hidden"  id="call_from_id" >   
            <input type="hidden" id="call_type" value="audio">
            <input type="hidden" id="call_duration" value="call_duration" >
            <input type="hidden" id="call_started_at" value="call_started_at" >
            <input type="hidden" id="call_ended_at" value="call_ended_at">
            <input type="hidden" id="end_cause" value="end_cause" >

            <input type="hidden" name="" id="receiver_ids">
            <input type="hidden" name="" id="avatar_url" value="<?php echo base_url().'assets/avatar/'; ?>">


            




            <div class="container-fluid vccontainer group_vccontainer hidden">
                    <div class="vcrow">
                        <div class="vccol vccollarge">
                            <div class="vcvideo">
                                <div class="videoinner">
                                    <div class="to_group_video hidden"><?php echo $group_name; ?></div>
                                    <img src="<?php echo base_url().'assets/img/user.jpg'; ?>" class="rounded-circle img-fluid center-block" id="inner_image">

                                    <div id="sample" style="width: 500px;height: 500px;margin: 0 0 0 0px;"></div>
                                    
                                </div>
                                <div class="vcopponentvideo">
                                    <img src="<?php echo  $profile_img ?>" class="img-fluid" id="group_outgoing_caller_image">
                                    <!-- <div  id="outgoing"  style="width: 282px;height: 209px;"></div> -->
                                    <!-- /*<div  id="outgoing"  style="width: 282px;height: 209px;"></div>*/ -->
                                </div>
                                <!-- <div class="vcactions">
                                    <a class="vccam hidden" href="javascript:void(0)" id="group_video_mute"><i class="fa fa-video-camera Vid-Icn-InActive" aria-hidden="true" style="display:none;color:red;"></i><i class="fa fa-video-camera Vid-Icn-Active" aria-hidden="true"></i></a>  
                                    <a href="javascript:void(0)" class="vcend hidden" ">Call End</a>
                                </div> -->  


                            </div>
                        </div>
                    </div>
                </div>


            <div id="drag_files" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Drag and drop files upload</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body p-t-0">
                            <form id="js-upload-form">
                                <div class="upload-drop-zone" id="drop-zone">
                                    <i class="fa fa-cloud-upload fa-2x"></i> <span class="upload-text">Just drag and drop files here</span>
                                </div>
                                <h4>Uploading</h4>
                                <ul class="upload-list">
                                    <li class="file-list">
                                        <div class="upload-wrap">
                                            <div class="file-name">
                                                <i class="fa fa-photo"></i>
                                                photo.png
                                            </div>
                                            <div class="file-size">1.07 gb</div>
                                            <button type="button" class="file-close">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                        <div class="progress progress-xs progress-striped">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                        </div>
                                        <div class="upload-process">37% done</div>
                                    </li>
                                    <li class="file-list">
                                        <div class="upload-wrap">
                                            <div class="file-name">
                                                <i class="fa fa-file"></i>
                                                task.doc
                                            </div>
                                            <div class="file-size">5.8 kb</div>
                                            <button type="button" class="file-close">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                        <div class="progress progress-xs progress-striped">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                        </div>
                                        <div class="upload-process">37% done</div>
                                    </li>
                                    <li class="file-list">
                                        <div class="upload-wrap">
                                            <div class="file-name">
                                                <i class="fa fa-photo"></i>
                                                dashboard.png
                                            </div>
                                            <div class="file-size">2.1 mb</div>
                                            <button type="button" class="file-close">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                        <div class="progress progress-xs progress-striped">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                        </div>
                                        <div class="upload-process">Completed</div>
                                    </li>
                                </ul>
                            </form>
                            <div class="m-t-30 text-center">
                                <button class="btn btn-primary btn-lg">Add to upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="add_group" class="modal custom-modal fade center-modal" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Create a group</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for example.</p>
                            <form id="group_form" method="post">
                                <div class="form-group">
                                    <label>Group Name <span class="text-danger">*</span></label>
                                    <input class="form-control" id="group_name" name="group_name" required="" type="text">
                                </div>
                                <div class="form-group">
                                    <label>Send invites to: </label>
                                    <input class="form-control" required="" id="members" name="members" value="" type="text" data-role="tagsinput" />
                                </div>
                                <div class="m-t-50 text-center">
                                    <button class="btn btn-primary btn-lg">Create Group</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="add_chat_user" class="modal custom-modal fade center-modal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Direct Chat</h3>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group m-b-30">
                                    <input placeholder="Search to start a chat" class="form-control search-input input-lg" name="search_user" id="search_user" type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-lg" onclick="search_user()">Search</button>
                                    </span>
                                </div>
                                <div>
                                    <h5>Conversations with</h5>
                                    <div class="new_user_list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div id="share_files" class="modal custom-modal fade center-modal" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                  
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Share File</h3>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="files-share-list">
                                <div class="files-cont">
                                    <div class="file-type">
                                        <span class="files-icon"><i class="fa fa-file-pdf-o"></i></span>
                                    </div>
                                    <div class="files-info">
                                        <span class="file-name text-ellipsis">AHA Selfcare Mobile Application Test-Cases.xls</span>
                                        <span class="file-author"><a href="#">Bernardo Galaviz</a></span> <span class="file-date">May 31st at 6:53 PM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Share With</label>
                                <input class="form-control" type="text">
                            </div>
                            <div class="m-t-50 text-center">
                                <button class="btn btn-primary btn-lg">Share</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- popup-->
            


    <?php $this->load->view('popups'); ?>