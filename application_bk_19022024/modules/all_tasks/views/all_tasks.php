				<div class="chat-main-row">
					<div class="chat-main-wrapper">
						<div class="col-xs-7 message-view task-view">
							<div class="chat-window">
								<div class="chat-header">
									<div class="navbar">
										<div class="pull-left">
											
											<!-- <div class="add-task-btn-wrapper">
											    <a href="<?php echo base_url(); ?>all_tasks/add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
    													Add Task
												</a>
											</div>
 -->
											<!-- <div class="add-task-btn-wrapper">
												<span class="add-task-btn btn btn-white">
													Add Task
												</span>
											</div> -->
										</div>
										<div class="pull-left m-l-10">
											<div class="row">
						 
							<a href="<?php echo base_url(); ?>projects" class="btn back-btn bk-alt" ><i class="fa fa-chevron-left"></i>Back</a>			
						</div>
					 
											<!-- <div class="add-task-btn-wrapper">
												<a href="<?php echo base_url(); ?>all_tasks/milestone_add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
													Add Milestone
												</a>
											</div> -->
										</div>
										<ul class="nav navbar-nav pull-right chat-menu">
											<li class="dropdown">
												<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>
												<ul class="dropdown-menu">
													<li><a href="javascript:void(0)">Pending Tasks</a></li>
													<li><a href="javascript:void(0)">Completed Tasks</a></li>
													<li><a href="javascript:void(0)">All Tasks</a></li>
												</ul>
											</li>
										</ul>
										<a class="task-chat profile-rightbar pull-right" href="#task_window"><i class="fa fa fa-comment"></i></a>
									</div>
								</div>

								<div class="chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="task-wrapper">
													<div class="task-list-container">
														<div class="task-list-body">
															<ul id="task-list">

															

																			<?php $all_tasks = $this->db->get_where('tasks',array('project'=>$project_id['project_id']))->result_array(); 
																	if(count($all_tasks) != 0)
																	{
																		foreach($all_tasks as $tasks){
																			if($tasks['task_progress'] == 100)
																			{
																				$cls = 'completed';
																				$btn_actions='task_uncompletes';
																			}else{
																				$cls = '';
																				$btn_actions='task_completes';
																			}

																			 $assign_member = $this->db->select('*')
																	         ->from('assign_tasks PA')
																	         ->join('account_details AD','PA.assigned_user = AD.user_id')
																	         ->where('PA.task_assigned',$tasks['t_id'])
																	         ->get()->row_array(); 

																	         
  																	         	if($assign_member['avatar'] == '' )
																	         {
																	         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																	         }else{
																	         	$pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
																	         }

																	         $assignrds_name=$assign_member['fullname'];
																	         
																	?>
																	<li class="task <?php echo $cls; ?>">
																		<div class="task-container">
																			<span class="task-action-btn task-check">
																				<span class="action-circle large complete-btn <?php echo $btn_actions;?>"  data-id="<?php echo $tasks['t_id']; ?>" title="Mark  Complete">
																					<i class="material-icons">check</i>
																				</span>
																			</span>
																			<a href="<?php echo base_url();?>all_tasks/task_view/<?php echo $project_id['project_id']; ?>/<?php echo $tasks['t_id']; ?>">
																			<span class="task-label" contenteditable="false"><?php echo $tasks['task_name']; ?> </span>
																			</a>
																		<span class="task-action-btn task-btn-right">
																			<span id="assign_tasks<?php echo $tasks['t_id']; ?>">
																				<?php
																				if(!empty($assignrds_name))
																				{
																					echo'<span class="action-circle large" title="'.$assignrds_name.'">
																							<img src="'.$pro_pic_teams.'" alt="">
																						</span>';
																				}
																				else
																				{
																					echo'<span class="action-circle large" title="Assign">
																							<a data-toggle="ajaxModal" href="'.base_url().'all_tasks/assign_user/'.$project_id['project_id'].'/'.$tasks['t_id'].'/Assign">
																								<i class="material-icons">person_add</i>
																							</a>
																						</span>';
																				}
																				?>

																				</span>
																			
																			<span class="action-circle large" data-toggle="modal" onclick="delete_task(<?php echo $tasks['t_id'];?>)"  title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span> 
																		</span>
																		</div>
																	</li>

																<?php } }else{ ?>
																	<li class="task">
																		<div class="task-container">
																			<span class="task-label" contenteditable="false"><h5>No Tasks</h5></span>
																		</div>
																	</li>
																<?php } ?>
															</ul>
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

						<div class="col-xs-5 message-view task-chat-view" id="task_window">
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
																	<img alt="<?php echo $project_file['fullname']; ?>" src="<?php echo $pro_pic_coms; ?>" class="img-responsive img-circle">
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

                         	 $task_det = $this->db->get_where('dgt_tasks',array('t_id'=>$tasks_id))->row_array();

                         	 if($task_det['task_progress'] == 100)
								{
									$tasl_cls = '<i class="material-icons">check</i> Completed';
									$btn_task='task-completed';
									$btn_action='task_uncompletes';
									
								}else{
									$tasl_cls = '<i class="material-icons">check</i> Mark Complete';
									$btn_task='';
									$btn_action='task_completes';
								}

						?>

						<div class="col-xs-5 message-view task-chat-view" id="task_window">
							<div class="chat-window">
								<div class="chat-header">
									<div class="navbar">
										<div class="task-complete">
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
										</div>
									</div>
								</div>
								<div class="chat-contents task-chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="chats">
													
													<h4><?php echo $task_det['task_name']; ?></h4>
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
															
																<?php if(!empty($assignrd_name))
																{
																	echo'
																	<div class="avatar">
																		<img src="'.$pro_pic_team.'" alt="">
																	</div>
																	<div class="assigned-info">
																		<div class="task-head-title">Assigned To</div>
																		<div class="task-assignee">'.$assignrd_name.'</div>
																	</div>
																
																		
																		<span onclick="delete_assigne('.$project_id['project_id'].','.$tasks_id.')" class="remove-icon">
																	    <i class="fa fa-close"></i>
																	    </span>
																	    ';
																} 
																else 
																{
																	echo'<a data-toggle="ajaxModal" href="'.base_url().'all_tasks/assign_user/'.$project_id['project_id'].'/'.$tasks_id.'/Assign">
																		<div class="avatar">
																			<img src="'.$pro_pic_team.'" alt="">
																		</div>
																		<div class="assigned-info">
																			<div class="task-head-title">Unassigned</div>
																			<div class="task-assignee"></div>
																		</div>
																	   </a>';
																}
																?>
															
														</div>
														<div class="task-due-date">
																														
																<?php if(!empty($task_det['due_date']))
																{
																	echo'<div class="due-icon">
																		<span>
																			<i class="material-icons">date_range</i>
																		</span>
																		</div>
																		<div class="due-info">
																			<div class="task-head-title">Due Date </div>
																			<div class="due-date">'.date('M d, Y',strtotime($task_det['due_date'])).'</div>
																		</div>
																	  <span onclick="delete_due_date('.$project_id['project_id'].','.$tasks_id.')" class="remove-icon"><i class="fa fa-close"></i></span>';
																} 
																else 
																{
																	echo'<a data-toggle="ajaxModal" href="'.base_url().'all_tasks/assign_user/'.$project_id['project_id'].'/'.$tasks_id.'/Due">
																		<div class="due-icon">
																			<span>
																				<i class="material-icons">date_range</i>
																			</span>
																		</div>
																		<div class="due-info">
																			<div class="task-head-title">Due Date </div>
																			<div class="due-date"></div>
																		</div>
																	</a>';
																}
																?>
															
														</div>
													</div>
													<hr class="task-line">
													<div class="task-desc">
														<div class="task-desc-icon">
															<i class="material-icons">subject</i>
														</div>
														<div class="task-textarea">
															<textarea placeholder="Description" id="task_description" onkeyup="task_description(<?php echo $tasks_id;?>)" class="form-control"><?php echo $task_det['description'];?></textarea>
														</div>
													</div>


													
													<?php 
														// $project_files = $this->db->select('*')
														// 			         ->from('files FI')
														// 			         ->join('account_details AD','FI.uploaded_by = AD.user_id')
														// 			         ->where('FI.project',$project_id['project_id'])
														// 			         ->get()->result_array();

													$project_files =$this->db->query("SELECT '' as activites, '' as file_ext,CM.message,CM.date_posted,AD.fullname,'' as file_name,'' as path,AD.avatar FROM dgt_comments AS CM LEFT JOIN dgt_account_details AS AD ON CM.posted_by = AD.user_id WHERE CM.task_id='".$tasks_id."' 
															UNION  SELECT '' as activites,FI.file_ext,'' as message,FI.date_posted,ADS.fullname,FI.file_name,FI.path,ADS.avatar FROM dgt_task_files AS FI LEFT JOIN dgt_account_details AS ADS ON FI.uploaded_by = ADS.user_id WHERE FI.task='".$tasks_id."' 

															UNION  SELECT TI.activites,''as file_ext,'' as message,TI.date_posted,ADsS.fullname,'' as file_name,'' as path,ADsS.avatar FROM dgt_task_activites AS TI LEFT JOIN dgt_account_details AS ADsS ON TI.added_by = ADsS.user_id WHERE TI.task_id='".$tasks_id."'

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
																	<img alt="<?php echo $project_file['fullname']; ?>" src="<?php echo $pro_pic_coms; ?>" class="img-responsive img-circle">
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
																			<div class="attach-img-download"><a href="'.base_url().'all_tasks/download/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></div>
																			<div class="task-attach-img"><img src="'.base_url().'assets/project-files/'.$project_file['path'].'/'.$project_file['file_name'].'" alt=""></div>
																		</li>';
																		
																	    }
																	    else if($project_file['ext']=='.pdf')
																	    {
																	    	echo'<ul class="attach-list">
																				<li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <a href="'.base_url().'all_tasks/download/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></li>
																			</ul>';
																	    }
																	    else
																	    {
																	    	echo'<ul class="attach-list">
																				<li><i class="fa fa-file"></i> <a href="'.base_url().'all_tasks/download/'.$project_file['path'].'/'.$project_file['file_name'].'">'.$project_file['file_name'].'</a></li>
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
										<form method="post" id="post_comments" enctype="multipart/form-data" action="<?php echo base_url();?>all_tasks/post_task_comments" class="message-inner">
											<input type="file" id="file_upload" onchange="this.form.submit();" name="projectfiles" style="display:none"/>
											<a class="link attach-icon" href="#" id="OpenImgUpload" data-target="#drag_files"><img src="<?php echo base_url();?>images/attachment.png" alt=""></a>
											<div class="message-area"><div class="input-group">
												<input type="hidden" name="project" value="<?php echo $project_id['project_id'];?>">
												<input type="hidden" name="task" value="<?php echo $tasks_id;?>">
												<textarea class="form-control" id="comments" name="description" placeholder="Description"></textarea>
												<span class="input-group-btn">
													<button class="btn btn-custom" type="submit"><i class="fa fa-send"></i></button>
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
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<form id="assigned_task" method="post" action="<?php echo base_url();?>all_tasks/delete_task">
					<div class="form-head">
						<h3><?=lang('delete_lead')?></h3>
						<p>Are you sure want to delete?</p>
					</div>
					<input type="text" name="delete_project" id="project" value="<?=$project_id['project_id']?>">
					<input type="text" name="delete_task" id="delete_task_id" >
					<div class="modal-btn delete-action">
						<div class="row">
							<div class="col-xs-6">
								<button type="submit" class="btn continue-btn">Delete</button>
							</div>
							<div class="col-xs-6">
								<a href="javascript:void(0);" data-dismiss="modal" class="btn cancel-btn">Cancel</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>