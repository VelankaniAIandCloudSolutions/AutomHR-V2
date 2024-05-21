 

			
        <?php  
	 			 $this->db->where('project',$project_id['project_id']);
	 			 $this->db->where('branch_id',$this->session->userdata('branch_id'));
                $this->db->order_by('t_id','DESC');
                $this->db->limit('1');
                $last_tasks = $this->db->get('tasks')->row_array(); ?>
			<div class="chat-main-row">

			
				<div class="chat-main-wrapper">
					<div class="col-xs-7 message-view task-view">
							<div class="chat-window">
								<!--<div class="chat-header">
									<div class="navbar">
										<div class="pull-left">
											
											<! -- <div class="add-task-btn-wrapper">
											    <a href="<?php echo base_url(); ?>all_tasks/add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
    													Add Task
												</a>
											</div>	
 - ->
											<div class="add-task-btn-wrapper">
												<span class="add-task-btn btn btn-white">
													Add Task
												</span>
											</div>
										</div>
										<div class="pull-left m-l-10">
											
											<div class="add-task-btn-wrapper">
												<a href="<?php echo base_url(); ?>all_tasks/milestone_add/<?php echo $project_id['project_id']; ?>" data-toggle="ajaxModal" class="add-task-btn btn btn-white">
													Add Milestone
												</a>
											</div>
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
										<div class="pull-right board-view-header">
											
											<div class="view-icons">
												 <a href="javascript:void(0);" class="grid-view btn btn-link " style=" pointer-events: none;cursor: default;"   title="kanban View"><i class="fa fa-th"></i></a>
												<a href="<?php echo base_url().'all_tasks/view/'.$project_id['project_id']; ?>"  class="list-view btn btn-link active" title="List View"><i class="fa fa-bars"></i></a>
											</div>
										</div>
									</div>
								</div> -->
										<div class="chat-contents">
											<div class="chat-content-wrap">
												<div class="chat-wrap-inner">
													<div class="chat-box">
														<div class="task-wrapper">
<div class="row">
						<div class="col-sm-4" style="width: 8%;">
							<a href="<?php echo base_url(); ?>projects" class="btn back-btn bk-alt" ><i class="fa fa-chevron-left"></i>Back</a>			
						</div>
					</div><br>
														<div class="row board-view-header">
						<div class="col-xs-4">
							<h4 class="page-title">Task List</h4>
						</div>
						<div class="col-xs-8 text-right">
							<div class="view-icons">
								 <a href="javascript:void(0);" class="grid-view btn btn-link" style=" pointer-events: none;cursor: default;" title="kanban View"><i class="fa fa-th"></i></a>
								<a href="<?php echo base_url().'all_tasks/view/'.$project_id['project_id']; ?>" class="list-view btn btn-link active" title="List View"><i class="fa fa-bars"></i></a>
							</div>
							<a href="#" class="btn btn-white pull-right m-r-10" data-toggle="modal" data-target="#add_task_board"><i class="fa fa-plus"></i> Create List</a>
							<a href="<?php echo base_url().'projects/view/'.$project_id['project_id']; ?>" class="btn btn-white pull-right m-r-10" title="View Board"><i class="fa fa-link"></i></a>
							<a href="#" class="btn btn-white pull-right m-r-10" title="Edit" data-toggle="modal" data-target="#edit_project"><i class="fa fa-pencil"></i><a>
							<div class="pro-teams">
								<div class="pro-team-lead">
									<h4>Lead</h4>
									<div class="avatar-group">
										<?php 

                                            $member_details = $this->db->select('*')

                                                                        ->from('users U')

                                                                        ->join('account_details AD','U.id = AD.user_id')

                                                                        ->where('U.id',$project_id['assign_lead'])
                                                                        ->where('AD.branch_id',$this->session->userdata('branch_id'))
                                                                        ->get()->row_array();
																	         
															         	if($member_details['avatar'] == '' )
																         {
																         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																         }else{
																         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
																         }
																          $assignrds_name=$member_details['fullname'];

                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();

                                          
									    ?>
									   
										<div class="avatar">
											 <?php  if(empty($member_details)){ ?>
											 	
												<a class="avatar-title rounded-circle border border-white" href="" data-toggle="modal" data-target="#assign_leader">
													<i class="fa fa-plus"></i>
												</a>
                                            <?php } else { ?>
                                            	
                                            	<img class="avatar-img rounded-circle border border-white" data-toggle="modal" data-target="#assign_leader" alt="User Image" title="<?php echo $assignrds_name;?>" src="<?php echo $pro_pic_teams;?>">
                                          <?php  } ?>
											
										</div>										
										<!-- <div class="avatar">
											<a href="" class="avatar-title rounded-circle border border-white" data-toggle="modal" data-target="#assign_leader"><i class="fa fa-plus"></i></a>
										</div> -->
									</div>
								</div>
								<div class="pro-team-members">
									<h4>Team</h4>
									<div class="avatar-group">
										  <?php $all_members = $this->db->get_where('assign_projects',array('project_assigned'=>$project_id['project_id']))->result_array(); 

									    foreach($all_members as $members){

                                            $member_details = $this->db->select('*')

                                                                        ->from('users U')

                                                                        ->join('account_details AD','U.id = AD.user_id')

                                                                        ->where('U.id',$members['assigned_user'])
                                                                        ->where('AD.branch_id',$this->session->userdata('branch_id'))

                                                                        ->get()->row_array();
																	         
															         	if($member_details['avatar'] == '' )
																         {
																         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																         }else{
																         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
																         }
																          $assignrds_name=$member_details['fullname'];

                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();



									    ?>

										<div class="avatar">
											<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $assignrds_name;?>" src="<?php echo $pro_pic_teams;?>">
										</div>
									<?php }?>
										<div class="avatar">
											<!-- <a class="pull-right btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$p->project_id?>"> -->
											<a class="avatar-title rounded-circle border border-white" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$project_id['project_id']?>/kanban"><i class="fa fa-plus"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php $progress = Project::get_progress($project_id['project_id']); //print_r($progress);exit; ?>
					<!-- 	<div class="col-xs-12">
							<div class="pro-progress">
								<div class="pro-progress-bar">
									<h4>Progress</h4>
									<div class="progress">
										<div class="progress-bar <?php echo ($progress >= 100 ) ? 'progress-bar-success' : ''; ?>" role="progressbar" style="width: <?php echo $progress;?>%"></div>
									</div>
									<span><?php echo $progress;?>%</span>
								</div>
							</div>
						</div> -->
					</div>

					<div class="kanban-board card-box m-b-0">
						<div class="kanban-cont">
							<?php $task_board = $this->db->get_where('task_board',array('project_id'=>$project_id['project_id'],'branch_id'=>$this->session->userdata('branch_id')))->result_array(); 
																	if(count($task_board) != 0)
																	{
																		foreach($task_board as $board){?>
							<div class="kanban-list kanban-<?php echo $board['task_board_class']?>" >
								<div class="kanban-header">
									<span class="status-title"><?php echo ucfirst($board['task_board_name']);?></span>
									<div class="dropdown kanban-action">
										<a href="" data-toggle="dropdown">
											<i class="fa fa-ellipsis-v"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<li><a href="#" data-toggle="modal" data-target="#edit_task_board<?php echo $board['task_board_id'];?>">Edit</a></li>
											<li><a data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/task_board_delete/<?php echo $board['task_board_id']; ?>/<?php echo $board['project_id']?>">Delete</a></li>
										</ul>
									</div>
								</div>
								
									<?php $all_tasks = $this->db->get_where('tasks',array('project'=>$project_id['project_id'],'branch_id'=>$this->session->userdata('branch_id')))->result_array(); 
									
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

																			 // $assign_member = $this->db->select('*')
																	   //       ->from('assign_tasks PA')
																	   //       ->join('account_details AD','PA.assigned_user = AD.user_id')
																	   //       ->where('PA.task_assigned',$tasks['t_id'])
																	   //       ->get()->resul_array(); 

																	         
  																	 //         	if($assign_member['avatar'] == '' )
																	   //       {
																	   //       	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																	   //       }else{
																	   //       	$pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
																	   //       }

																	   //       $assignrds_name=$assign_member['fullname'];

																	         if($board['task_board_id'] == $tasks['status']){?>

									<div class="kanban-wrap" id="<?php echo $board['task_board_id'];?>"  data-id="<?php echo $tasks['t_id']?>">								         		
									<div class="card panel" ondblclick="kanban_redirect(<?php echo $tasks['t_id']?>,<?php echo $project_id['project_id']?>,<?php echo $tasks['status']?>);">
										<div class="kanban-box">
											<div class="task-board-header">
												<span class="status-title" data-toggle="modal" data-target="#edit_task_modal<?php echo($tasks['t_id']);?>" onclick="edit_model(<?php echo $tasks['t_id']?>);" > <?php echo $tasks['task_name']?> </span>
												<div class="dropdown kanban-task-action">
													<a href="" data-toggle="dropdown">
														<i class="fa fa-angle-down"></i>
													</a>
													<ul class="dropdown-menu pull-right">

														<!-- <li><a class="edit_task_modal" data-toggle="modal" data-target="#edit_task_modal" href="javascript:void(0);" onclick="edit_model(<?php echo $tasks['t_id']?>);">Edit</a></li> -->
														<!-- <li><a class="edit_task_modal" data-toggle="modal" data-target="#edit_task_modal<?php echo $tasks['t_id']; ?>" href="javascript:void(0);" onclick="edit_model(<?php echo $tasks['t_id']?>);">Edit</a></li> -->
														<li><a data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/task_kanban_delete/<?php echo $tasks['t_id']; ?>/<?php echo $tasks['project']?>">Delete</a></li>
													</ul>
												</div>
											</div>
											<div class="task-board-body">
												<div class="kanban-info">
													<div class="progress progress-xs">
														<div class="progress-bar"  role="progressbar" style="width: <?php echo $tasks['task_progress'];?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" value="<?php echo $tasks['task_progress'];?>"></div>
													</div>
													<span><?php echo $tasks['task_progress'];?></span>
												</div>

												<?php
													$class ="";
												 if($tasks['priority'] == "High"){
													$class ="danger";
												} else if($tasks['priority'] == "Normal") {
														$class ="warning";
												} else {
													$class ="success";
												}?>
												<div class="kanban-footer">
													<span class="task-info-cont">
														<span class="task-date"> <?php echo ($tasks['due_date'] !='')?'<i class="fa fa-clock-o"></i>'.date('M d',strtotime($tasks['due_date'])):'';?></span>
														<span class="task-priority label label-<?php echo $class;?>" ><?php echo $tasks['priority']?></span>
													</span>
													<div class="pro-teams">	

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

																}?>
																		<?php /*if($loop_count>5) { ?><div class="avatar">
																				<span class="badge bg-purple"><?php echo $loop_count -5; ?></span>
																			</div><?php } */?><!-- <div class="avatar"> -->
																			<!-- <a class="pull-right btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$p->project_id?>"> -->
																			<!-- <a class="avatar-title rounded-circle border border-white" data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/assign_user/<?=$project_id['project_id']?>/<?=$tasks_id?>/Assign"><i class="fa fa-plus"></i></a>
																		</div> -->
																	</div>
																</div>
															</div>
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
								<div class="add-new-task">
									<a href="javascript:void(0);" data-toggle="modal" data-target="#add_task_modal" class="add_task_modal" data-status="<?php echo $board['task_board_id'];?>">Add New Task</a>
								</div>
							</div>
							<?php }
						} else { echo "No Task Found";}?>
						</div>
					</div>
					<div id="add_task_board" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Add Task Board</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form>
								<div class="form-group">
									<label>Task Board Name <span id="already_task_board_name" style="display: none;color:red;">Already Registered Task board name</span></label>
									<input type="text" class="form-control" id="task_board_name" name="task_board_name" data-id="<?php echo $project_id['project_id']?>">
									<input type="hidden" class="form-control" id="project_id" name="project_id" value="<?php echo $project_id['project_id'];?>">
								</div>
								<div class="form-group task-board-color">
									<label>Task Board Color</label>
									<div class="board-color-list">
										<label class="board-control board-primary">
											<input name="color" type="radio" class="board-control-input" data-class="primary" data-bc ="#fff5ec" value="#ff9b44" checked="">
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-success">
											<input name="color" type="radio" class="board-control-input" data-class="success" data-bc="#edf7ee" value="#4caf50">
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-info">
											<input name="color" type="radio" class="board-control-input" data-class="info" data-bc ="#e7f3fe" value="#42a5f5">
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-purple">
											<input name="color" type="radio" class="board-control-input" data-class="purple" data-bc="#f1effd" value="#7460ee">
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-warning">
											<input name="color" type="radio" class="board-control-input" data-class="warning" data-bc ="##fdfcf3" value="#ffb300">
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-danger">
											<input name="color" type="radio" class="board-control-input" data-class="danger" data-bc ="#fef7f6" value="#ef5350">
											<span class="board-indicator"></span>
										</label>
									</div>
								</div>
								<div class="m-t-20 text-center">
									<button type="button" class="btn btn-primary btn-lg" id="task_board_save" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ">Save</button> 
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php $task_board = $this->db->get_where('task_board',array('project_id'=>$project_id['project_id']))->result_array(); 
																	if(count($task_board) != 0)
																	{
																		foreach($task_board as $board){?>
			<div id="edit_task_board<?php echo $board['task_board_id']?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Edit <?php echo ucfirst($board['task_board_name']);?> Board</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form>
								<div class="form-group">
									<label>Task Board Name</label>
									<input type="hidden" class="form-control" id="task_board_id" name="task_board_id" value="<?php echo $board['task_board_id'];?>">
									<input type="text" class="form-control" id="task_board_name<?php echo $board['task_board_id']?>" name="task_board_name" value="<?php echo $board['task_board_name'];?>">
									<input type="hidden" class="form-control" id="project_id<?php echo $board['task_board_id']?>" name="project_id" value="<?php echo $board['project_id'];?>">
								</div>
								<div class="form-group task-board-color">
									<label>Task Board Color</label>
									<div class="board-color-list">
										<label class="board-control board-primary">
											<input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="primary" data-bc ="#fff5ec" value="#ff9b44" <?php echo($board['task_board_color']=="#ff9b44")?"checked":"";?>>
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-success">
											<input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="success" data-bc="#edf7ee" value="#4caf50" <?php echo($board['task_board_color']=="#4caf50")?"checked":"";?>>
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-info">
											<input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input"  data-class="info" data-bc ="#e7f3fe" value="#42a5f5" <?php echo($board['task_board_color']=="#42a5f5")?"checked":"";?>>
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-purple">
											<input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="purple" data-bc="#f1effd" value="#7460ee" <?php echo($board['task_board_color']=="#7460ee")?"checked":"";?>>
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-warning">
											<input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="warning" data-bc ="##fdfcf3" value="#ffb300" <?php echo($board['task_board_color']=="#ffb300")?"checked":"";?>>
											<span class="board-indicator"></span>
										</label>
										<label class="board-control board-danger">
											<input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="danger" data-bc ="#fef7f6" value="#ef5350" <?php echo($board['task_board_color']=="#ef5350")?"checked":"";?>>
											<span class="board-indicator"></span>
										</label>
									</div>
								</div>
								<div class="m-t-20 text-center">
									<button type="button" class="btn btn-primary btn-lg" id="task_board_edit" data-id="<?php echo $board['task_board_id'];?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ">Save</button> 
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php } }?>

	<!-- Edit Board Modal -->
			<div id="edit_project" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Edit Board</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<?php //if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){

				// $project = Project::by_id($project_id['project_id']);
						$attributes = array('class' => '','id' => 'projectEditForm');
						echo form_open(base_url().'projects/edit',$attributes); ?>
						<?php echo validation_errors('<span style="color:red">', '</span><br>'); ?>
						<input type="hidden" name="project_id" value="<?=$project_id['project_id']?>">
						<input type="hidden" class="form-control" value="<?=$project_id['project_code']?>" name="project_code" readonly>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Board Name</label>
											<input type="text" class="form-control" value="<?php echo $project_id['project_title'];?>" name="project_title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Client</label>
											<select  style="width:100%;" class="form-control" name="client" >
									<!-- <?php if($project_id['client'] > 0) { ?>
						<option value="" disabled>Choose Companies</option>
									<?php } ?> -->
									<option value="" >Choose Clients</option>
										<?php foreach (Client::get_all_clients() as $key => $c) { 
												if($c->branch_id == $this->session->userdata('branch_id')){
											?>
											<option value="<?=$c->co_id?>" <?php if($c->co_id == $project_id['client']){ echo "selected"; } ?>><?=ucfirst($c->company_name)?></option>
											<?php } } ?>
										</select> 
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Start Date</label>
											<div class="cal-icon">
												<input class="datepicker-input form-control" readonly type="text" value="<?=strftime(config_item('date_format'), strtotime($project_id['start_date']));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>End Date</label>
											<div class="cal-icon">
												<input class="datepicker-input form-control" readonly type="text" value="<?php if(valid_date($project_id['due_date'])){ echo strftime(config_item('date_format'), strtotime($project_id['due_date'])); } ?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
											</div>
										</div>
									</div>
								</div>
								<div class="row">							
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-lg-3 control-label"><?=lang('fixed_rate')?></label>
											<div class="col-lg-5">
												<label class="switch">
													<input type="checkbox" <?php if($project_id['fixed_rate'] == 'Yes'){ echo "checked=\"checked\""; } ?> name="fixed_rate" id="fixed_rate" >
													<span></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div id="hourly_rate" <?php if($project_id['fixed_rate'] == 'Yes'){ echo "style=\"display:none\""; }?>>
											<div class="form-group">
												<label class="control-label"><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50 )</label>
												
													<input type="text" class="form-control money" value="<?=$project_id['hourly_rate'];?>" name="hourly_rate">
												
											</div>
										</div>
										<div id="fixed_price" <?php if($project_id['fixed_rate'] == 'No'){ echo "style=\"display:none\""; }?>>
											<div class="form-group">
												<label class="control-label"><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )<span class="text-danger">*</span></label>
												
													<input type="text" class="form-control" value="<?=$project_id['fixed_price']?>" name="fixed_price">
												
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Project Leader <span class="text-danger">*</span></label>
											<select class="select2-option form-control"   style="width:260px" name="assign_lead" > 
										<optgroup label="Staff">
											<?php 
											$whr = array('role_id !='=>2,'activated'=>1,'banned'=>0);
								$all_userss = $this->db->get_where('users',$whr)->result();
								
								foreach ($all_userss as $key => $user) { ?>
												<option value="<?=$user->id?>"  <?php 
													if ($user->id == $project_id['assign_lead']) { ?> selected = "selected" <?php }   ?>>
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php } ?>
										</optgroup> 
									</select>
										</div>
									</div>

									 <?php $member_details = $this->db->select('*')

                                                                        ->from('users U')

                                                                        ->join('account_details AD','U.id = AD.user_id')

                                                                        ->where('U.id',$project_id['assign_lead'])

                                                                        ->get()->row_array();
																	         
															         	if($member_details['avatar'] == '' )
																         {
																         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																         }else{
																         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
																         }
																          $assignrds_name=$member_details['fullname'];?>
									<div class="col-md-6">
										<div class="form-group">
											<label class=" control-label">Team Leader</label>
											<div class="project-members">
												<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $project_id['assign_lead']; ?>" data-toggle="tooltip" title="<?php echo $assignrds_name;?>">
													<img src="<?php echo $pro_pic_teams;?>" class="avatar" alt="<?php echo $assignrds_name;?>" title="<?php echo $assignrds_name;?>" height="20" width="20">
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Add Team</label>
											<select class="select2-option form-control" multiple="multiple" style="width:260px" name="assign_to[]" > 
										<optgroup label="Staff">
											<?php 
											    $whr = array('role_id !='=>2,'activated'=>1,'banned'=>0);
                								$all_userss = $this->db->get_where('users',$whr)->result();
                								
                								foreach ($all_userss as $key => $user) { ?>
												<option value="<?=$user->id?>" <?php foreach (Project::project_team($project_id['project_id']) as $value) {
													if ($user->id == $value->assigned_user) { ?> selected = "selected" <?php } } ?>>
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php } ?>
										</optgroup> 
									</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Team Members</label>
											<div class="project-members">
											 	<?php $all_members = $this->db->get_where('assign_projects',array('project_assigned'=>$project_id['project_id']))->result_array(); 
											 	if(!empty($all_members)){
											 		$i=0;
						   							foreach($all_members as $members){

	                                            		$member_details = $this->db->select('*')

	                                                                        ->from('users U')

	                                                                        ->join('account_details AD','U.id = AD.user_id')

	                                                                        ->where('U.id',$members['assigned_user'])

	                                                                        ->get()->row_array();
																		         
																         	if($member_details['avatar'] == '' )
																	         {
																	         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																	         }else{
																	         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
																	         }
																	          $assignrds_name=$member_details['fullname'];

	                                            		$designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();



										    ?>
												<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $members['assigned_user']; ?>" data-toggle="tooltip" title="<?php echo $assignrds_name;?>">
													<img src="<?php echo $pro_pic_teams;?>" class="avatar" alt="<?php echo $assignrds_name;?>" height="20" width="20">
												</a>
												<?php
													$i++;
													if($i==3) break;
												 }  
												 if(count($all_members) >= 5 ){ ?>
														<span class="all-team">+<?php echo 4 - (count($all_members));?></span>
													<?php } ?>
											<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Description</label>
									<textarea name="description" class="form-control foeditor-project-edit" placeholder="<?=lang('about_the_project')?>" required><?=$project_id['description'];?></textarea>
									<div class="row">
									<div class="col-md-6">
									<label id="project_description_error" class="error display-none" style="position:inherit;top:0">Description must not empty</label>
									</div>
									</div>
								</div>
								<!-- <div class="form-group">
									<label>Upload Files</label>
									<input class="form-control" type="file">
								</div> -->
								<div class="m-t-20 text-center">
									<button id="project_edit_dashboard" class="btn btn-primary"><?=lang('save_changes')?></button>
									<!-- <button class="btn btn-primary">Save Changes</button> -->
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- /Edit Board Modal -->
		<!-- Add Task Modal -->
			<div id="add_task_modal" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Add Task</h4>
						</div>
						<div class="modal-body">
							<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'AddTask');

          echo form_open(base_url().'all_tasks/add_kanban_task/'.$project_id['project_id'],$attributes); ?>

								<div class="form-group">
									<label>Task Name <span id="already_task_name" style="display: none;color:red;">Already Registered Task name</span></label>
									<input type="hidden" class="form-control" name="project" value="<?php echo $project_id['project_id']?>">
									<input type="text" class="form-control" name="task_name" id="task_names" data-id="<?php echo $project_id['project_id']?>">
									<input type="hidden" class="form-control status" name="status" value="">
								</div>
								<div class="form-group">
									<label>Task Priority</label>
									<select class="form-control select" name="priority">
										<option value="">Select</option>
										<option value="High">High</option>
										<option value="Normal">Normal</option>
										<option value="Low">Low</option>
									</select>
								</div>
								<div class="form-group">
									<label>Due Date</label>
									<div class="cal-icon">
										<input class="datepicker-input form-control" readonly type="text" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
									</div>
								</div>
								<div class="form-group">
									<label>Task Followers</label>
									<select class="select2-option" multiple="multiple"  style="width:100%;" name="assigned_to[]" > 
								<optgroup label="<?=lang('admin_staff')?>"> 
								<?php 
								 $whr = array('U.role_id !='=>2,'U.activated'=>1,'U.banned'=>0
								 // ,'AD.branch_id'=> $this->session->userdata('branch_id')
								 );
								 $all_userss = $this->db->select('*')
								 		  ->from('users U')
								 		  ->join('account_details AD','AD.user_id = U.id')
								 		  ->where($whr)
								 		  ->get()->result();
								 // $all_userss = $this->db->get_where('users',$whr)->result();
								
								foreach ($all_userss as $key => $user) { ?>
								<option value="<?=$user->user_id?>"><?=ucfirst(User::displayName($user->user_id))?></option>
								<?php } ?>	
								</optgroup> 
							</select>
									
									
								 
								</div>
								<div class="submit-section text-center">
									<button class="btn btn-primary submit-btn" id="add_task">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- /Add Task Modal -->
			<!-- edit Task Modal -->
		<?php $all_tasks = $this->db->get_where('tasks',array('project'=>$project_id['project_id']))->result_array(); 
																	if(count($all_tasks) != 0)
																	{
																		foreach($all_tasks as $task_det){
																			if($task_det['task_progress'] == 100)
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
																	         ->where('PA.task_assigned',$task_det['t_id'])
																	         ->get()->row_array(); 

																	         
  																	         	if($assign_member['avatar'] == '' )
																	         {
																	         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																	         }else{
																	         	$pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
																	         }

																	         $assignrds_name=$assign_member['fullname'];
																	         $tasks_id = $task_det['t_id'];
																	          ?>
					<div id="edit_task_modal<?php echo $tasks_id;?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<button type="button" class="close" id="close" data-dismiss="modal">&times;</button>
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Edit Task</h4>
						</div>												         
						<div class="modal-body">
						<div class="col-xs-5 message-view task-chat-view" id="task_window">
							<div class="chat-window">
								<!-- <div class="chat-header">
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
										<div class="pull-right board-view-header">											
											<div class="view-icons">
												 <a href="<?php echo base_url().'all_tasks/kanban/'.$project_id['project_id']; ?>" class="grid-view btn btn-link active"  title="kanban View"><i class="fa fa-window-close"></i></a>
											</div>
										</div>
									</div>
								</div> -->
								<div class="chat-contents task-chat-contents">
									<div class="chat-content-wrap">
										<div class="">
											<div class="chat-box">
												<div class="chats">
													<div class="task-desc">														
														<div class="task-textarea">
															<b><input type="text"  placeholder="Task name" id="task_name<?php echo $tasks_id;?>" onkeyup="task_name(<?php echo $tasks_id;?>,<?php echo $project_id['project_id'];?>)" class="form-control" value="<?php echo ucfirst($task_det['task_name'])?>"> </b>
														</div>
													</div>
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
																	<div class="task-head-title">Assign to </div>
																	<div class="avatar-group">
																		  <?php $all_members = $this->db->get_where('assign_tasks',array('task_assigned'=>$tasks_id))->result_array();
																		  $loop_count2=0;  $extra_users=0;
																		   $first_user=0;

																	    foreach($all_members as $members){
																	    	$extra_users++; $first_user++; 

																	    	if($loop_count2<5){ $loop_count2++;

								                                            $member_details = $this->db->select('*')

								                                                                        ->from('users U')

								                                                                        ->join('account_details AD','U.id = AD.user_id')

								                                                                        ->where('U.id',$members['assigned_user'])

								                                                                        ->get()->row_array();
																									         
																							         	if($member_details['avatar'] == '' )
																								         {
																								         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																								         }else{
																								         	$pro_pic_teams= base_url().'assets/avatar/'.$member_details['avatar'];
																								         }
																								          $assignrds_name=$member_details['fullname'];

								                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();

								                                             if(count($all_members)>5 && $first_user==1) {?>
								                                             	<div class="avatar">
																				<span title='Additional users' class="badge bg-purple"><?php echo '+';echo count($all_members)-5; ?></span>
																				</div>
																			<?php }																	    
																	    ?>

																		<div class="avatar">
																			<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $assignrds_name;?>" src="<?php echo $pro_pic_teams;?>">
																		</div>
																	<?php } 			

																}  $extra_users = $extra_users - 5;				
																 ?>
																		<div class="avatar">
																			<!-- <a class="pull-right btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$p->project_id?>"> -->
																			<a class="avatar-title rounded-circle border border-white" data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/assign_user/<?=$project_id['project_id']?>/<?=$tasks_id?>/Assign"><i class="fa fa-plus"></i></a>
																			
																		</div>
																	<?php /*if($extra_users > 0) {?><div class="avatar">
																				<span class="badge bg-purple"><?php echo $extra_users; ?></span>
																			</div><?php }*/ ?>
																		</div>				
																</div>
															</div>
															
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
													<div class="task-desc">
														<div class="form-group col-md-12"> 
															<label class=" control-label"><?=lang('progress')?></label>
															<div class=""> 
																<div id="progress-slider_task<?php echo $tasks_id;?>" class="edit_progress" onchange="task_progress(<?php echo $tasks_id;?>)" data-taskid="<?php echo $tasks_id;?>"></div>
																<input id="progress_task<?php echo $tasks_id;?>" type="hidden" value="" name="task_progress" onchange="task_progress(<?php echo $tasks_id;?>)" data-taskid="<?php echo $tasks_id;?>" class=""/>
															</div>
														</div> 
													</div> 
													<hr class="task-line">													
													<div class="task-desc">
														<div class="task-desc-icon">
															<i class="material-icons">subject</i>
														</div>
														<div class="task-textarea">

															<textarea placeholder="Description" id="task_description<?php echo $tasks_id;?>" onkeyup="task_description(<?php echo $tasks_id;?>)" class="form-control"><?php echo $task_det['description'];?></textarea>
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
										<form method="post" id="post_comments_<?php echo $tasks_id;?>" enctype="multipart/form-data" action="#" class="message-inner">
											<input type="file" data-fid='<?php echo $tasks_id;?>' class='_file_updata' id="file_upload_<?php echo $tasks_id;?>"  name="projectfiles" style="display:none"/>
											<a class="link attach-icon open_upso_load" data-zid='<?php echo $tasks_id;?>' href="#" id="OpenImgUploads" data-target="#drag_files"><img src="<?php echo base_url();?>images/attachment.png" alt=""></a>
											<div class="message-area"><div class="input-group">
												<input type="hidden" id='project_upl_<?php echo $tasks_id;?>' name="project" value="<?php echo $project_id['project_id'];?>">
												<input type="hidden" name="task" id='task_k_<?php echo $tasks_id;?>' value="<?php echo $tasks_id;?>">
												<textarea class="form-control" id="comments_<?php echo $tasks_id;?>" name="description" placeholder="Description"></textarea>
												<span class="input-group-btn">
													<button class="btn btn-custom _comment_upload" data-taskid='<?php echo $tasks_id;?>' id='_comment_upload' type="button"><i class="fa fa-send"></i></button>
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
						</div>
					</div>
				</div>
			</div>										         






 
			<?php }
		}
?>
					<!--Kanban content end-->



														</div>
													</div>
												</div>
											</div>
										</div>




							</div>
				
					</div>
						
				</div>
						
			</div>