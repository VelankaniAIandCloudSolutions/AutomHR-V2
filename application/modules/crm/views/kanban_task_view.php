				
			<div class="chat-main-row kanban-height">
					<div class="chat-main-wrapper">
						<div class="col-7 message-view task-view">
							<div class="chat-window">
								<div class="chat-header">
									<div class="navbar">
										<div class="float-left">
											
											<!-- <div class="add-task-btn-wrapper">
											    <a href="<?php echo base_url(); ?>all_tasks/add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
    													Add Task
												</a>
											</div>
 -->
											<div class="add-task-btn-wrapper" style="padding-top: 12px;">
												
													<strong><?php echo ucfirst($project_id['project_title']);?></strong>
												
											</div>
										</div>
										<div class="float-right board-view-header">
											
											<div class="view-icons">
												 <a href="<?php echo base_url().'crm/business_proposals'; ?>" class="grid-view btn btn-link active"  title="kanban View"><i class="fa fa-th"></i></a>
												<a href="javascript:void(0);" style=" pointer-events: none;cursor: default;"  class="list-view btn btn-link " title="List View"><i class="fa fa-bars"></i></a>
											</div>
										</div>
										<!-- <div class="float-left m-l-10">
											
											<div class="add-task-btn-wrapper">
												<a href="<?php echo base_url(); ?>all_tasks/milestone_add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
													Add Milestone
												</a>
											</div>
										</div>
										<div class="float-left m-l-10">
											
											<div class="add-task-btn-wrapper">
												<a href="#" data-toggle="modal" data-target="#add_tasks" class="btn btn-white">
													Add Task
												</a>
											</div>
										</div> -->
										<!--<ul class="nav navbar-nav float-right chat-menu">-->
										<!--	<li class="dropdown">-->
										<!--		<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>-->
										<!--		<ul class="dropdown-menu">-->
										<!--			<li><a href="javascript:void(0)">Pending Tasks</a></li>-->
										<!--			<li><a href="javascript:void(0)">Completed Tasks</a></li>-->
										<!--			<li><a href="javascript:void(0)">All Tasks</a></li>-->
										<!--		</ul>-->
										<!--	</li>-->
										<!--</ul>-->
										<a class="task-chat profile-rightbar float-right" href="#task_window"><i class="fa fa fa-comment"></i></a>
									</div>
								</div>
								<div class="chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="task-wrapper">
													<div class="task-list-container">
														<div class="task-list-body">
															<?php $lead_board = $this->db->get_where('task_board',array('task_board_id'=>$status))->result_array(); 
																	if(count($lead_board) != 0)
																	{
																		foreach($lead_board as $board){?>
							<div class="kanban-list kanban-<?php echo $board['task_board_class']?>">
								<div class="kanban-header">
									<span class="status-title"><?php echo ucfirst($board['task_board_name']);?></span>
									<!-- <div class="dropdown kanban-action">
										<a href="" data-toggle="dropdown">
											<i class="fa fa-ellipsis-v"></i>
										</a>
										<ul class="dropdown-menu float-right">
											<li><a href="#" data-toggle="modal" data-target="#edit_task_board<?php echo $board['task_board_id'];?>">Edit</a></li>
											<li><a data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/task_board_delete/<?php echo $board['task_board_id']; ?>/<?php echo $board['project_id']?>">Delete</a></li>
										</ul>
									</div> -->
								</div>
								
									<?php 
									
								        $all_leads = $this->db->get_where('business_proposals',array('id'=>$lead_id))->result_array(); 
									
																	if(count($all_leads) != 0)
																	{
																		foreach($all_leads as $leads){
																			if($leads['avatar'] == '')
														{
															$pro_pic = base_url().'assets/avatar/default_avatar.JPG';
														}else{
															$pro_pic = base_url().'assets/uploads/'.$leads['avatar'];
														}



																	         if($board['task_board_id'] == $leads['lead_status']){?>

									<div class="kanban-wrap" id="<?php echo $board['task_board_id'];?>"  data-id="<?php echo $leads['id']?>">								         		
									<div class="card panel">
										<div class="kanban-box">
											<div class="task-board-header">
												<a class="status-title" href="<?php echo base_url(); ?>crm/edit_lead/<?php echo $leads['id']; ?>" data-toggle="ajaxModal"><?php echo ucfirst($leads['name']);?> </a>
												<div class="avatar float-right">
													<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $leads['name'];?>" src="<?php echo $pro_pic;?>">
												</div>
												<!-- <div class="dropdown kanban-task-action">
													<a href="" data-toggle="dropdown">
														<i class="fa fa-angle-down"></i>
													</a>
													<ul class="dropdown-menu float-right">

														<li><a class="edit_task_modal" data-toggle="modal" data-target="#edit_task_modal" href="javascript:void(0);" onclick="edit_model(<?php echo $tasks['t_id']?>);">Edit</a></li>
														<li><a data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/task_kanban_delete/<?php echo $tasks['t_id']; ?>/<?php echo $tasks['project']?>">Delete</a></li>
													</ul>
												</div> -->
											</div>
											<div class="task-board-body">
												<div class="kanban-info">
													<!-- <div class="progress progress-xs"> -->
														<!-- <div class="progress-bar"  role="progressbar" style="width: <?php echo $leads['task_progress'];?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" value="<?php echo $tasks['task_progress'];?>"></div> -->
													<!-- </div> -->
													<span><?php echo $leads['project_name'];?></span>
													
												</div>

												<?php
												// 	$class ="";
												//  if($tasks['priority'] == "High"){
												// 	$class ="danger";
												// } else if($tasks['priority'] == "Normal") {
												// 		$class ="warning";
												// } else {
												// 	$class ="success";
												// }
												?>
												<div class="kanban-footer">
													<span class="task-info-cont">
														<span><?php echo '$'.$leads['project_amount'];?></span>
														<!-- <span class="task-date"> <?php echo ($leads['created'] !='')?'<i class="fa fa-clock-o"></i>'.date('M d',strtotime($leads['created'])):'';?></span> -->
														<!-- <span class="task-priority label label-<?php echo $class;?>" ><?php echo $tasks['priority']?></span> -->
													</span>
													<!-- <div class="pro-teams">	

																<div class="pro-team-members">
																	
																	<div class="avatar-group">
																		  <?php $all_members = $this->db->get_where('assign_tasks',array('task_assigned'=>$tasks['t_id']))->result_array(); 
																		  
																		  $loop_count=0;

																	    foreach($all_members as $members){
																	    	$first_user++; 
																	    	if($loop_count<5){ $loop_count++;

				                                            $member_details = $this->db->select('*')

                                                            ->from('users U')

                                                            ->join('account_details AD','U.id = AD.user_id')

                                                            ->where_in('U.id',$members['assigned_user'])

                                                            ->get()->row_array();
													        
												         	if($member_details['avatar'] == '' )
													         {
													         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
													         }else{
													         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
													         }
													          $assignrds_name=$member_details['fullname'];

								                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();

 																	if(count($all_members)>5 && $first_user==1) {?><div class="avatar">
																				<span title='Additional users' class="badge bg-purple"><?php echo '+';echo count($all_members)-5; ?></span>
																			</div><?php }	

																	    ?>

																		<div class="avatar">
																			<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $assignrds_name;?>" src="<?php echo $pro_pic_teams;?>">
																		</div>
																	<?php }
																	else { $loop_count++; }

																}?> -->
																		<?php /*if($loop_count>5) { ?><div class="avatar">
																				<span class="badge bg-purple"><?php echo $loop_count -5; ?></span>
																			</div><?php } */?><!-- <div class="avatar"> -->
																			<!-- <a class="float-right btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$p->project_id?>"> -->
																			<!-- <a class="avatar-title rounded-circle border border-white" data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/assign_user/<?=$project_id['project_id']?>/<?=$tasks_id?>/Assign"><i class="fa fa-plus"></i></a>
																		</div> -->
																<!-- 	</div>
																</div>
															</div> -->
													<!-- <span class="task-users"> -->
														<!-- <?php if($assign_member['assigned_user'] ==''){?>
																<img src="<?php echo $pro_pic_teams;?>" class="task-avatar" title="<?php echo $assignrds_name;?>" width="24" height="24">
														<?php } else {?>
															<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $assign_member['assigned_user']; ?>">
														<img src="<?php echo $pro_pic_teams;?>" class="task-avatar" title="<?php echo $assignrds_name;?>" width="24" height="24">
													</a>
													<?php } ?> -->
													<!-- <img src="<?php echo $pro_pic_teams;?>" class="task-avatar" title="<?php echo $assignrds_name;?>" width="24" height="24">	
													</span> -->
												</div>
											</div>
										</div>
									</div>
									</div>
								<?php }
							}
							} ?>
								<div class="kanban-wrap kanban-empty ui-sortable" id="<?php echo $board['task_board_id'];?>" >
								</div>
								<!-- <div class="add-new-task">
									<a href="javascript:void(0);" data-toggle="modal" data-target="#add_task_modal" class="add_task_modal" data-status="<?php echo $board['task_board_id'];?>">Add New Task</a>
								</div> -->
							</div>
							<?php }
						}?>
														</div>
														<div class="task-list-footer">
															<div class="new-task-wrapper" style="display: none;">
																<input type="hidden" id="project_id" value="<?php echo $project_id['project_id']; ?>" placeholder="Enter new task here. . .">
																<textarea id="new_task" placeholder="Enter new task here. . ."></textarea>
																<!-- <span class="error-message hidden">You need to enter a task first</span>
																<span class="add-new-task-btn btn" id="add-task">Add Task</span>
																<span class="cancel-btn btn" id="close-task-panel">Close</span> -->
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

             <?php

             if($types=='project')
             {
             	/*
             	?>

						<div class="col-5 message-view task-chat-view" id="task_window">
							<div class="chat-window">
								<div class="chat-header">
									<div class="navbar">
										<div class="task-assign">
											<span class="assign-title">Project Lead </span> 
											<?php $team_lead = $this->db->select('*')
											         ->from('projects P')
											         ->join('account_details AD','P.assign_lead = AD.user_id')
											         ->where('P.assign_lead',$project_id['project_id'])
											         ->get()->row_array();
											         if($team_lead['avatar'] == '' )
											         {
											         	$pro_pic = base_url().'assets/avatar/default_avatar.jpg';
											         }else{
											         	$pro_pic = base_url().'assets/avatar/'.$team_lead['avatar'];
											         }

											?>
											<a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php echo $team_lead['fullname']; ?>">
												<img src="<?php echo $pro_pic; ?>" class="avatar" alt="" height="20" width="20">
											</a>
										</div>
									</div>
								</div>
								<div class="chat-contents task-chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="chats">
													<?php $project_det = $this->db->get_where('projects',array('project_id'=>$project_id['project_id']))->row_array(); ?>
													<h4><?php echo $project_det['project_title']; ?></h4>
													<hr class="task-line" />
													
													
													<?php 
														// $project_files = $this->db->select('*')
														// 			         ->from('files FI')
														// 			         ->join('account_details AD','FI.uploaded_by = AD.user_id')
														// 			         ->where('FI.project',$project_id['project_id'])
														// 			         ->get()->result_array();

													$project_files =$this->db->query("SELECT '' as ext,CM.message,CM.date_posted,AD.fullname,'' as file_name,'' as path,AD.avatar FROM dgt_comments AS CM LEFT JOIN dgt_account_details AS AD ON CM.posted_by = AD.user_id WHERE CM.project='".$project_id['project_id']."' 
													UNION  SELECT FI.ext,'' as message,FI.date_posted,ADS.fullname,FI.file_name,FI.path,ADS.avatar FROM dgt_files AS FI LEFT JOIN dgt_account_details AS ADS ON FI.uploaded_by = ADS.user_id WHERE FI.project='".$project_id['project_id']."' ORDER by date_posted ASC")->result_array();

														foreach($project_files as $project_file){
															if($project_file['avatar'] == '' )
													         {
													         	$pro_pic_coms = base_url().'assets/avatar/default_avatar.jpg';
													         }else{
													         	$pro_pic_coms = base_url().'assets/avatar/'.$project_file['avatar'];
													         }
													?>
														<div class="chat chat-left">
															<div class="chat-avatar">
																<a  title="<?php echo $project_file['fullname']; ?>" data-placement="right" data-toggle="tooltip" class="avatar">
																	<img alt="<?php echo $project_file['fullname']; ?>" src="<?php echo $pro_pic_coms; ?>" class="img-fluid rounded-circle">
																</a>
															</div>
															<div class="chat-body">
																<div class="chat-bubble">
																	<div class="chat-content">
																	<span class="task-chat-user"><?php echo $project_file['fullname']; ?></span> 

																	<?php

																			if(!empty($project_file['file_name']))
																			{

																		?>

																	<span class="file-attached">attached file <i class="fa fa-paperclip"></i></span> <span class="chat-time"><?php if(config_item('show_time_ago') == 'TRUE'){ echo strtolower(humanFormat(strtotime($project_file['date_posted'])).' '.lang('ago')); } ?></span>
																	<ul class="attach-list">
																		<?php
																		
																		if($project_file['ext']=='.png' || $project_file['ext']=='.jpg' || $project_file['ext']=='.jpeg' || $project_file['ext']=='.PNG' || $project_file['ext']=='.JPG' || $project_file['ext']=='.JPEG')
												                        {
												                        	
																		echo'<li class="img-file">
																			<div class="attach-img-download"><a href="#">'.$project_file['file_name'].'</a></div>
																			<div class="task-attach-img"><img src="'.base_url().'assets/project-files/'.$project_file['path'].'/'.$project_file['file_name'].'" alt=""></div>
																		</li>';
																		
																	    }
																	    else if($project_file['ext']=='.pdf')
																	    {
																	    	echo'<ul class="attach-list">
																				<li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <a href="'.base_url().'assets/project-files/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></li>
																			</ul>';
																	    }
																	    else
																	    {
																	    	echo'<ul class="attach-list">
																				<li><i class="fa fa-file"></i> <a href="'.base_url().'assets/project-files/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></li>
																			</ul>';
																	    }
																	    ?>
																	</ul>
																<?php }

																if(!empty($project_file['message']))
																{
																	?>

																	 <span class="chat-time"><?php if(config_item('show_time_ago') == 'TRUE'){ echo strtolower(humanFormat(strtotime($project_file['date_posted'])).' '.lang('ago')); } ?></span>
																		<p><?php echo $project_file['message']?></p>

																	<?php

																}
																?>
																</div>


																	
																</div>
															</div>
														</div>
													<?php } ?>
													</div>
													<!-- <div class="task-information"><span class="task-info-line"><a class="task-user" href="#">John Doe</a> <span class="task-info-subject">marked task as incomplete</span></span><div class="task-time">1:16pm</div></div> -->
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="chat-footer">
									<div class="message-bar">
											<form method="post" id="post_comments" enctype="multipart/form-data" action="<?php echo base_url();?>all_tasks/post_comments" class="message-inner">
											<input type="file" id="file_upload" name="projectfiles" style="display:none"/>
											<a class="link attach-icon" href="#" id="OpenImgUpload" data-target="#drag_files"><img src="<?php echo base_url();?>images/attachment.png" alt=""></a>
											<div class="message-area"><div class="input-group">
												<input type="hidden" name="project" value="<?php echo $project_id['project_id'];?>">
												<textarea class="form-control" id="comments" name="description" placeholder="Description"></textarea>
												<span class="input-group-btn">
													<button class="btn btn-custom" type="submit"><i class="fa fa-send"></i></button>
												</span>
												</div>
											</div>
										</form>
									</div>
									<div class="project-members task-followers">
										<span class="followers-title">Team Members</span>
										<?php $team_members = $this->db->select('*')
											         ->from('assign_projects PA')
											         ->join('account_details AD','PA.assigned_user = AD.user_id')
											         ->where('PA.project_assigned',$project_id['project_id'])
											         ->get()->result_array(); 

											         foreach($team_members as $member)
											         {
											         	if($member['avatar'] == '' )
											         {
											         	$pro_pic_team = base_url().'assets/avatar/default_avatar.jpg';
											         }else{
											         	$pro_pic_team = base_url().'assets/avatar/'.$member['avatar'];
											         }
											         ?>
										<a href="#" data-toggle="tooltip" title="<?php echo $member['fullname']; ?>">
											<img src="<?php echo $pro_pic_team; ?>" class="avatar" alt="<?php echo $member['fullname']; ?>" height="20" width="20">
										</a>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php */ ?>

						<?php } 

                         if($types=='task')
                         {

                         	 $lead_det = $this->db->get_where('dgt_business_proposals',array('id'=>$lead_id))->row_array();

        //                  	 if($task_det['task_progress'] == 100)
								// {
								// 	$tasl_cls = '<i class="material-icons">check</i> Completed';
								// 	$btn_task='task-completed';
								// 	$btn_action='task_uncompletes';
									
								// }else{
								// 	$tasl_cls = '<i class="material-icons">check</i> Mark Complete';
								// 	$btn_task='';
								// 	$btn_action='task_completes';
								// }

						?>

						<div class="col-5 message-view task-chat-view" id="task_window">
							<div class="chat-window">
								<div class="chat-header">
									<div class="navbar">
										<!-- <div class="task-complete">
											<a class="task-complete-btn <?php echo $btn_task;?> <?php echo $btn_action;?>" data-id="<?php echo $tasks_id; ?>" id="task_complete" href="javascript:void(0);">
												<?php echo $tasl_cls;?>
											</a>
										</div>
										<div class="task-assign">
											<span class="assign-title">Project Lead </span> 
											<?php $team_lead = $this->db->select('*')
											         ->from('projects P')
											         ->join('account_details AD','P.assign_lead = AD.user_id')
											         ->where('P.assign_lead',$project_id['project_id'])
											         ->get()->row_array();
											         if($team_lead['avatar'] == '' )
											         {
											         	$pro_pic = base_url().'assets/avatar/default_avatar.jpg';
											         }else{
											         	$pro_pic = base_url().'assets/avatar/'.$team_lead['avatar'];
											         }

											?>
											<a href="#" data-toggle="tooltip" data-placement="bottom" title="<?php echo $team_lead['fullname']; ?>">
												<img src="<?php echo $pro_pic; ?>" class="avatar" alt="" height="20" width="20">
											</a>

										</div> -->
										<div class="float-right board-view-header">											
											<div class="view-icons">
												 <a href="<?php echo base_url().'crm/business_proposals'; ?>" class="grid-view btn btn-link active"  title="kanban View"><i class="fa fa-window-close"></i></a>
											</div>
										</div>
									</div>
								</div>
								<div class="chat-contents task-chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="chats">
													<div class="task-desc">														
														<div class="task-textarea">
															<b><input type="text"  placeholder="Lead name" id="lead_name<?php echo $lead_id;?>" onkeyup="lead_name(<?php echo $lead_id;?>)" class="form-control" value="<?php echo ucfirst($lead_det['name'])?>"> </b>
														</div>
													</div>
													<!-- <h4><?php echo $lead_det['name']; ?></h4> -->
													<?php $team_members = $this->db->select('*')
											         ->from('assign_tasks PA')
											         ->join('account_details AD','PA.assigned_user = AD.user_id')
											         ->where('PA.task_assigned',$tasks_id)
											         ->get()->result_array(); 

											         $pro_pic_team = base_url().'assets/avatar/default_avatar.jpg';

											         foreach($team_members as $member)
											         {
											         	if($member['avatar'] == '' )
											         {
											         	
											         }else{
											         	$pro_pic_team = base_url().'assets/avatar/'.$member['avatar'];
											         }
											         $assignrd_name=$member['fullname'];
											         }
											         ?>
													<div class="task-header">
														<div class="assignee-info">
															
																<div class="pro-teams">	

																<div class="pro-team-members">
																	<div class="task-head-title"><?php echo $lead_det['project_name']; ?> </div>
																	
																</div>
															</div>
															
														</div>
														<div class="task-due-date">
																														
																<?php if(!empty($lead_det['created']))
																{
																	echo'<div class="due-icon">
																		<span>
																			<i class="material-icons">date_range</i>
																		</span>
																		</div>
																		<div class="due-info">
																			<div class="task-head-title">Created Date </div>
																			<div class="due-date">'.date('M d, Y',strtotime($lead_det['created'])).'</div>
																		</div>
																	  ';
																} 
																// else 
																// {
																// 	echo'<a data-toggle="ajaxModal" href="'.base_url().'all_tasks/assign_user/'.$project_id['project_id'].'/'.$tasks_id.'/Due">
																// 		<div class="due-icon">
																// 			<span>
																// 				<i class="material-icons">date_range</i>
																// 			</span>
																// 		</div>
																// 		<div class="due-info">
																// 			<div class="task-head-title">Due Date </div>
																// 			<div class="due-date"></div>
																// 		</div>
																// 	</a>';
																// }
																?>
															
														</div>
													</div>

													<hr class="task-line">
													<div class="task-desc">
														<div class="task-desc-icon">
															<i class="material-icons">subject</i>
														</div>
														<div class="task-textarea">
															<textarea placeholder="Description" id="task_description<?php  echo $lead_det['id']?>" onkeyup="task_description(<?php echo $lead_id;?>)" class="form-control"><?php echo $lead_det['description'];?></textarea>
														</div>
													</div>


													
													<?php 
														// $project_files = $this->db->select('*')
														// 			         ->from('files FI')
														// 			         ->join('account_details AD','FI.uploaded_by = AD.user_id')
														// 			         ->where('FI.project',$project_id['project_id'])
														// 			         ->get()->result_array();

													$project_files =$this->db->query("SELECT '' as activites, '' as file_ext,CM.message,CM.date_posted,AD.fullname,'' as file_name,'' as path,AD.avatar FROM dgt_lead_comments AS CM LEFT JOIN dgt_account_details AS AD ON CM.posted_by = AD.user_id WHERE CM.lead_id='".$lead_id."' 
															UNION  SELECT '' as activites,FI.file_ext,'' as message,FI.date_posted,ADS.fullname,FI.file_name,FI.path,ADS.avatar FROM dgt_lead_files AS FI LEFT JOIN dgt_account_details AS ADS ON FI.uploaded_by = ADS.user_id WHERE FI.lead='".$lead_id."' 

															

															ORDER by date_posted ASC")->result_array();


													foreach($project_files as $project_file){
															if($project_file['avatar'] == '' )
													         {
													         	$pro_pic_coms = base_url().'assets/avatar/default_avatar.jpg';
													         }else{
													         	$pro_pic_coms = base_url().'assets/avatar/'.$project_file['avatar'];
													         }

													      if(!empty($project_file['activites'])) 
													      {  
													?>


														<div class="task-information">
														<span class="task-info-line"><a class="task-user" href="#"><?php echo $project_file['fullname']; ?></a> <span class="task-info-subject"><?php echo $project_file['activites']; ?></span></span>
														<div class="task-time"><?php echo date('M d Y h:ia',strtotime($project_file['date_posted'])); ?></div>
													   </div>
													<?php }

													else { 
													?>


														<div class="chat chat-left">
															<div class="chat-avatar">
																<a  title="<?php echo $project_file['fullname']; ?>" data-placement="right" data-toggle="tooltip" class="avatar">
																	<img alt="<?php echo $project_file['fullname']; ?>" src="<?php echo $pro_pic_coms; ?>" class="img-fluid rounded-circle">
																</a>
															</div>
															<div class="chat-body"> 
																<div class="chat-bubble">
																	<div class="chat-content">
																	<span class="task-chat-user"><?php echo $project_file['fullname']; ?></span> 

																	<?php

																			if(!empty($project_file['file_name']))
																			{

																		?>

																	<span class="file-attached">attached file <i class="fa fa-paperclip"></i></span> <span class="chat-time"><?php if(config_item('show_time_ago') == 'TRUE'){ echo strtolower(humanFormat(strtotime($project_file['date_posted'])).' '.lang('ago')); } ?></span>
																	<ul class="attach-list">
																		<?php
																		
																		if($project_file['file_ext']=='.png' || $project_file['file_ext']=='.jpg' || $project_file['file_ext']=='.jpeg' || $project_file['file_ext']=='.PNG' || $project_file['file_ext']=='.JPG' || $project_file['file_ext']=='.JPEG')
												                        {
												                        	
																		echo'<li class="img-file">
																			<div class="attach-img-download"><a href="'.base_url().'crm/download/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></div>
																			<div class="task-attach-img"><img src="'.base_url().'assets/lead-files/'.$project_file['path'].'/'.$project_file['file_name'].'" alt=""></div>
																		</li>';
																		
																	    }
																	    else if($project_file['ext']=='.pdf')
																	    {
																	    	echo'<ul class="attach-list">
																				<li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <a href="'.base_url().'crm/download/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></li>
																			</ul>';
																	    }
																	    else
																	    {
																	    	echo'<ul class="attach-list">
																				<li><i class="fa fa-file"></i> <a href="'.base_url().'crm/download/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></li>
																			</ul>';
																	    }
																	    ?>
																	</ul>
																<?php }

																if(!empty($project_file['message']))
																{
																	?>

																	 <span class="chat-time"><?php if(config_item('show_time_ago') == 'TRUE'){ echo strtolower(humanFormat(strtotime($project_file['date_posted'])).' '.lang('ago')); } ?></span>
																		<p><?php echo $project_file['message']?></p>

																	<?php

																}
																?>
																</div>


																	
																</div>
															</div>
														</div>
													<?php } } ?>
													</div>
													<!-- <div class="task-information"><span class="task-info-line"><a class="task-user" href="#">John Doe</a> <span class="task-info-subject">marked task as incomplete</span></span><div class="task-time">1:16pm</div></div> -->
												</div>
											</div>
										</div>
									</div>
								<div class="chat-footer">
									<div class="message-bar">
										<form method="post" id="post_comments_<?php echo $lead_id;?>" enctype="multipart/form-data" action="#" class="message-inner">
											<input type="file" data-fid='<?php echo $lead_id;?>' class='_file_updata' id="file_upload_<?php echo $lead_id;?>"  name="projectfiles" style="display:none"/>
											<a class="link attach-icon open_upso_load" data-zid='<?php echo $lead_id;?>' href="#" id="OpenImgUpload" data-target="#drag_files"><img src="<?php echo base_url();?>images/attachment.png" alt=""></a>
											<div class="message-area"><div class="input-group">
												<input type="hidden" id='project_upl_<?php echo $lead_id;?>' name="project" value="<?php echo $project_id['project_id'];?>">
												<input type="hidden" name="task" id='task_k_<?php echo $lead_id;?>' value="<?php echo $lead_id;?>">
												<textarea class="form-control" id="comments_<?php echo $lead_id;?>"  name="description" placeholder="Description"></textarea>
												<span class="input-group-btn">
													<button class="btn btn-custom _comment_upload" data-taskid='<?php echo $lead_id;?>' id='_comment_upload' type="button"><i class="fa fa-send"></i></button>
												</span>
												</div>
											</div>
										</form>
									</div>
									<!-- <div class="project-members task-followers">
										<span class="followers-title">Team members</span>
										<?php $team_members = $this->db->select('*')
											         ->from('assign_projects PA')
											         ->join('account_details AD','PA.assigned_user = AD.user_id')
											         ->where('PA.project_assigned',$project_id['project_id'])
											         ->get()->result_array(); 

											         foreach($team_members as $member)
											         {
											         	if($member['avatar'] == '' )
											         {
											         	$pro_pic_team = base_url().'assets/avatar/default_avatar.jpg';
											         }else{
											         	$pro_pic_team = base_url().'assets/avatar/'.$member['avatar'];
											         }
											         ?>
										<a href="#" data-toggle="tooltip" title="<?php echo $member['fullname']; ?>">
											<img src="<?php echo $pro_pic_team; ?>" class="avatar" alt="<?php echo $member['fullname']; ?>" height="20" width="20">
										</a>
										<?php } ?>
									</div> -->
								</div>
							</div>
						</div>



					<?php } ?>
						</div>
					</div>
				<!--</div> -->
<div  class="modal custom-modal fade" role="dialog" id="delete_modal">
	<div class="modal-dialog  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<form id="assigned_task" method="post" action="<?php echo base_url();?>all_tasks/delete_task">
					<div class="form-head">
						<h3><?=lang('delete_lead')?></h3>
						<p>Are you sure want to delete?</p>
					</div>
					<input type="hidden" name="delete_project" id="project" value="<?=$project_id['project_id']?>">
					<input type="hidden" name="delete_task" id="delete_task_id" >
					<div class="modal-btn delete-action">
						<div class="row">
							<div class="col-6">
								<button type="submit" class="btn continue-btn">Delete</button>
							</div>
							<div class="col-6">
								<a href="javascript:void(0);" data-dismiss="modal" class="btn cancel-btn">Cancel</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="add_tasks" class="modal fade">

	<div class="modal-dialog  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Add Task</h4>
			</div>
			<div class="modal-body">
				<form class="bs-example form-horizontal">
					<div class="form-group">
						<label class="col-lg-4 control-label">Task Name <span class="text-danger">*</span></label>
						<div class="col-lg-8">
							<input type="text" name="milestone_name" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-4 control-label">Description <span class="text-danger">*</span></label>
						<div class="col-lg-8">
							<textarea class="form-control"></textarea>
						</div>
					</div>
					
						<div class="form-group">
							<label class="col-lg-4 control-label">Select Milestone <span class="text-danger">*</span></label>
							<div class="col-lg-8">
							<select class="form-control">
								<option value="1">Milestone 1</option>
								<option value="2">Milestone 2</option>
								<option value="3">Milestone 3</option>
							</select>
						</div>
						</div>

					<div class="form-group">
						<label class="col-lg-4 control-label">Start Date <span class="text-danger">*</span></label>
						<div class="col-lg-8">
							<input type="text" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-4 control-label">Deadline <span class="text-danger">*</span></label>
						<div class="col-lg-8">
							<input type="text" class="form-control">
						</div>
					</div>

					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>