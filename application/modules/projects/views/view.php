

<?php



$role = User::login_info(User::get_id())->role_id;



$this->load->helper('text');



$p = Project::by_id($project);



$currency = ($p->client > 0) ? Client::get_currency_code($p->client)->code : $p->currency;



?>





<!-- Page Content -->

                <div class="content container-fluid">

				

					<!-- Page Title -->

					<div class="row">

						<div class="col-sm-7 col-5">

							<h4 class="page-title"><?=$p->project_title; ?></h4>

						</div>

						<div class="col-sm-5 col-7 text-right m-b-30">

							<a href="<?php echo base_url(); ?>projects/edit/<?php echo $p->project_id; ?>" class="btn add-btn"><i class="fa fa-plus"></i> Edit Project</a>

						</div>

					</div>

					<!-- /Page Title -->

					

					<div class="row">

						<div class="col-lg-8 col-xl-9">

							<div class="card">

								<div class="card-body">

									<div class="project-title">

										<h5 class="card-title"><?=$p->project_title; ?></h5>

										<?php 

										    $open_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id,'task_progress !='=>100))->result_array(); 

										    $completed_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id,'task_progress'=>100))->result_array(); 

										    $all_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id))->result_array(); 

										?>

										<small class="block text-ellipsis m-b-15"><span class="text-xs"><?php echo count($open_tasks); ?></span> <span class="text-muted">open tasks, </span><span class="text-xs"><?php echo count($completed_tasks); ?></span> <span class="text-muted">tasks completed</span></small>

									</div>

									<?=$p->description; ?>

								</div>

							</div>

							<div class="card">

								<div class="card-body">

				                    <h5 class="card-title m-b-20">Uploaded image files</h5>

									<div class="row">

										<?php

					$project_files =$this->db->query("SELECT tf.* FROM dgt_tasks AS t LEFT JOIN  dgt_task_files AS tf ON tf.task=t.t_id  WHERE t.project='".$p->project_id."' ORDER BY tf.date_posted ASC")->result_array();

										if(!empty($project_files))
										{

											foreach($project_files as $project_file){

												if($project_file['file_ext']=='.png' || $project_file['file_ext']=='.jpg' || $project_file['file_ext']=='.jpeg' || $project_file['file_ext']=='.PNG' || $project_file['file_ext']=='.JPG' || $project_file['file_ext']=='.JPEG')
												                        {

										?>


										<div class="col-md-3 col-sm-4 col-lg-4 col-xl-3">

											<div class="uploaded-box">

												<div class="uploaded-img">

													<img style="height: 70px; width: 100px;" class="img-responsive" src="<?php echo base_url().'assets/project-files/'.$project_file['path'].'/'.$project_file['file_name'];?>" class="img-fluid" alt="">

												</div>

												<div class="uploaded-img-name">

													 <a href="<?php echo base_url().'projects/downloads/'.$project_file['path'].'/'.$project_file['file_name'];?>"><?php echo $project_file['file_name'];?></a>

												</div>

											</div>

										</div>

									<?php } } } ?>

									

									</div>

								</div>

							</div>

							<div class="card">

								<div class="card-body">

									<h5 class="card-title m-b-20">Uploaded files</h5>

									<ul class="files-list">

										<?php 

										$projects_files =$this->db->query("SELECT tf.*,ADS.fullname,ADS.avatar FROM dgt_tasks AS t LEFT JOIN  dgt_task_files AS tf ON tf.task=t.t_id LEFT JOIN dgt_account_details AS ADS ON tf.uploaded_by = ADS.user_id   WHERE t.project='".$p->project_id."' ORDER BY tf.date_posted ASC")->result_array();

										if(!empty($projects_files))
										{

												foreach($projects_files as $projects_file){

													if(!empty($projects_file['file_name']))
												{

													if($projects_file['file_ext']=='.png' || $projects_file['file_ext']=='.jpg' || $projects_file['file_ext']=='.jpeg' || $projects_file['file_ext']=='.PNG' || $projects_file['file_ext']=='.JPG' || $projects_file['file_ext']=='.JPEG')
												     {

												     }
												     else
												     {
										?>

										<li>

											<div class="files-cont">

												<div class="file-type">

													<span class="files-icon"><i class="fa fa-file"></i></span>

												</div>

												<div class="files-info">

													<span class="file-name text-ellipsis"><a href="#"><?php echo $projects_file['file_name'];?></a></span>

													<span class="file-author"><a href="<?php echo base_url().'projects/downloads/'.$projects_file['path'].'/'.$projects_file['file_name'];?>"><?php echo $projects_file['fullname']; ?></a></span> <span class="file-date"><?php echo date('M d, Y h:i A',strtotime($projects_file['date_posted'])); ?></span>

													<!-- <div class="file-size">Size: 14.8Mb</div> -->

												</div>

												<ul class="files-action">

													<li class="dropdown dropdown-action">

														<a href="" class="dropdown-toggle btn btn-link" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>

														<div class="dropdown-menu dropdown-menu-right">

															<a class="dropdown-item" href="<?php echo base_url().'projects/downloads/'.$projects_file['path'].'/'.$projects_file['file_name'];?>">Download</a>

															<!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#share_files">Share</a>

															<a class="dropdown-item" href="javascript:void(0)">Delete</a> -->

														</div>

													</li>

												</ul>

											</div>

										</li>

									<?php } } } } ?>

										

									</ul>

								</div>

							</div>

							<div class="project-task">

								<ul class="nav nav-tabs nav-tabs-top nav-justified m-b-0">
									<li class="active"><a href="#all_tasks" data-toggle="tab" aria-expanded="true">All Tasks</a></li>
									<li><a href="#pending_tasks" data-toggle="tab" aria-expanded="false">Pending Tasks</a></li>
									<li><a href="#completed_tasks" data-toggle="tab" aria-expanded="false">Completed Tasks</a></li>

								</ul>

								<div class="tab-content">

									<div class="tab-pane in active" id="all_tasks">

										<div class="task-wrapper">

											<div class="task-list-container">

												<div class="task-list-body">

													<ul id="task-list">

													    <?php foreach($all_tasks as $task){ 

													        if($task['task_progress'] == 100)

													        {

													            $task_progrs = "completed";

													        }else{

													            $task_progrs = '';

													        }

													         $assign_member = $this->db->select('*')
																	         ->from('assign_tasks PA')
																	         ->join('account_details AD','PA.assigned_user = AD.user_id')
																	         ->where('PA.task_assigned',$task['t_id'])
																	         ->get()->row_array(); 

																	         
  																	         	if($assign_member['avatar'] == '' )
																	         {
																	         	$pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
																	         }else{
																	         	$pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
																	         }

																	         $assignrds_name=$assign_member['fullname'];

													    ?>

														<li class="<?php echo $task_progrs; ?> task">

															<div class="task-container">

																<span class="task-action-btn task-check">

																	<span class="action-circle large complete-btn task_completes" data-id="<?php echo $task['t_id']; ?>" title="Mark Complete">

																		<i class="material-icons">check</i>

																	</span>

																</span>

																<span class="task-label" contenteditable="true"><?php echo $task['task_name']; ?></span>

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
																					// echo'<span class="action-circle large" title="Assign">
																					// 		<i class="material-icons">person_add</i>
																							
																					// 	</span>';
																				}
																				?>

																				</span>

																	<!-- <span class="action-circle large delete-btn" title="Delete Task">

																		<i class="material-icons">delete</i>

																	</span> -->

																</span>

															</div>

														</li>

														<?php } ?>

											</div>

										</div>

									</div>

									</div>

									<div class="tab-pane" id="pending_tasks">

									    <div class="task-wrapper">

											<div class="task-list-container">

												<div class="task-list-body">

													<ul id="task-list">

													    <?php foreach($open_tasks as $taskss){ 

													        if($taskss['task_progress'] == 100)

													        {

													            $task_progrss = "completed";

													        }else{

													            $task_progrss = '';

													        }

													    ?>

														<li class="<?php echo $task_progrss; ?> task">

															<div class="task-container">

																<span class="task-action-btn task-check">

																	<span class="action-circle large complete-btn" title="Mark Complete">

																		<i class="material-icons">check</i>

																	</span>

																</span>

																<span class="task-label" contenteditable="true"><?php echo $taskss['task_name']; ?></span>

																<span class="task-action-btn task-btn-right">

																	<span class="action-circle large" title="Assign">

																		<i class="material-icons">person_add</i>

																	</span>

																	<span class="action-circle large delete-btn" title="Delete Task">

																		<i class="material-icons">delete</i>

																	</span>

																</span>

															</div>

														</li>

														<?php } ?>

											</div>

										</div>

									</div>

									</div>

									<div class="tab-pane" id="completed_tasks">

									    <div class="task-wrapper">

											<div class="task-list-container">

												<div class="task-list-body">

													<ul id="task-list">

													    <?php foreach($completed_tasks as $tasss){ 

													        if($tasss['task_progress'] == 100)

													        {

													            $task_progr = "completed";

													        }else{

													            $task_progr = '';

													        }

													    ?>

														<li class="<?php echo $task_progr; ?> task">

															<div class="task-container">

																<span class="task-action-btn task-check">

																	<span class="action-circle large complete-btn" title="Mark Complete">

																		<i class="material-icons">check</i>

																	</span>

																</span>

																<span class="task-label" contenteditable="true"><?php echo $tasss['task_name']; ?></span>

																<span class="task-action-btn task-btn-right">

																	<span class="action-circle large" title="Assign">

																		<i class="material-icons">person_add</i>

																	</span>

																	<span class="action-circle large delete-btn" title="Delete Task">

																		<i class="material-icons">delete</i>

																	</span>

																</span>

															</div>

														</li>

														<?php } ?>

											</div>

										</div>

									</div>

									</div>

								</div>

							</div>

						</div>

						<div class="col-lg-4 col-xl-3">

							<div class="card">

								<div class="card-body">

									<h6 class="card-title m-b-15">Project details</h6>

									<table class="table table-striped table-border">

										<tbody>

											<tr>

												<td>Cost:</td>

												<?php 

												    if($p->fixed_rate == 'Yes'){ 

												        $project_cost = $p->fixed_price;

												    }

												    if($p->fixed_rate == 'No'){ 

												        $project_cost = ($p->hourly_rate * $p->estimate_hours);

												    }

												?>

												<td class="text-right"><?php echo $currency.' : '.$project_cost; ?></td>

											</tr>

											<tr>

												<td>Total Hours:</td>

												<td class="text-right"><?=$p->estimate_hours; ?> Hours</td>

											</tr>

											<tr>

												<td>Created:</td>

												<td class="text-right"><?php echo date('d M Y',strtotime($p->date_created)); ?></td>

											</tr>

											<tr>

												<td>Deadline:</td>

												<td class="text-right"><?php echo date('d M Y',strtotime($p->due_date)); ?></td>

											</tr>

											<tr>

												<td>Created by:</td>

												<?php 

												    $created_user = $this->db->get_where('account_details',array('user_id'=>$p->created_by))->row_array();

												    if(count($created_user) == 0)

												    {

												        $cr_user = '-';

												    }else{

												        $cr_user = $created_user['fullname'];   

												    }

												?>

												<td class="text-right"><a href="#"><?php echo $cr_user; ?></a></td>

											</tr>

											<tr>

												<td>Status:</td>

												<?php 

												    if($p->progress == 100){

												        $pro_status = 'Done';

												    }

												    if($p->progress == 0){

												        $pro_status = 'Not yet Started';

												    }

												    if($p->progress > 1 ){

												        $pro_status = 'Working';

												    }

												?>

												<td class="text-right"><?php echo $pro_status; ?></td>

											</tr>

										</tbody>

									</table>

									<p class="m-b-5">Progress <span class="text-success float-right"><?php echo $p->progress; ?>%</span></p>

									<div class="progress progress-xs mb-0">

										<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $p->progress; ?>%" style="width: <?php echo $p->progress; ?>%"></div>

									</div>

								</div>

							</div>

							<div class="card project-user">

								<div class="card-body">

									<!-- <h6 class="card-title m-b-20">Assigned Leader <a class="pull-right btn btn-primary btn-xs" data-toggle="modal" data-target="#assign_leader"><i class="fa fa-plus"></i> Add</a></h6> -->

									<ul class="list-box">

										<li>

											<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $p->assign_lead; ?>">

												<div class="list-item">

													<div class="list-left">

													    <?php $lead_details = $this->db->get_where('account_details',array('user_id'=>$p->assign_lead))->row_array(); ?>

														<span class="avatar"><?php echo strtoupper($lead_details['fullname'][0]); ?></span>

													</div>

													<div class="list-body">

														<span class="message-author"><?php echo $lead_details['fullname']; ?></span>

														<div class="clearfix"></div>

														<span class="message-content">Team Leader</span>

													</div>

												</div>

											</a>

										</li>

									</ul>

								</div>

							</div>

							<div class="card project-user">

								<div class="card-body">

									<h6 class="card-title">Assigned users <?php /*<a class="pull-right btn btn-primary btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>projects/team/<?=$p->project_id?>"><i class="fa fa-plus"></i> Add</a>*/?></h6>

									<ul class="list-box">

									    <?php $all_members = $this->db->get_where('assign_projects',array('project_assigned'=>$p->project_id))->result_array(); 

									    foreach($all_members as $members){

                                            $member_details = $this->db->select('*')

                                                                        ->from('users U')

                                                                        ->join('account_details AD','U.id = AD.user_id')

                                                                        ->where('U.id',$members['assigned_user'])

                                                                        ->get()->row_array();

                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();



									    ?>

										<li>

											<a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $member_details['user_id']; ?> ">

												<div class="list-item">

													<div class="list-left">

														<span class="avatar"><?php echo strtoupper($member_details['fullname'][0]); ?></span>

													</div>

													<div class="list-body">

														<span class="message-author"><?php echo $member_details['fullname']; ?></span>

														<div class="clearfix"></div>

														<span class="message-content"><?php echo $designation['designation']; ?></span>

													</div>

												</div>

											</a>

										</li>

										<?php } ?>

									</ul>

								</div>

							</div>

						</div>

					</div>

                </div>

				<!-- /Page Content -->
				
			<div id="assign_leader" class="modal custom-modal fade center-modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Assign Leader to this project</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<div class="input-group m-b-30">
								<input placeholder="Search to add a leader" class="form-control search-input input-lg" type="text">
								<span class="input-group-btn">
									<button class="btn btn-primary btn-lg">Search</button>
								</span>
							</div>
							<div>
								<ul class="media-list media-list-linked chat-user-list">
									<li class="media">
										<a href="#" class="media-link">
											<div class="media-left"><span class="avatar">R</span></div>
											<div class="media-body media-middle text-nowrap">
												<div class="user-name">Richard Miles</div>
												<span class="designation">Web Developer</span>
											</div>
										</a>
									</li>
									<li class="media">
										<a href="#" class="media-link">
											<div class="media-left"><span class="avatar">J</span></div>
											<div class="media-body media-middle text-nowrap">
												<div class="user-name">John Smith</div>
												<span class="designation">Android Developer</span>
											</div>
										</a>
									</li>
									<li class="media">
										<a href="#" class="media-link">
											<div class="media-left">
												<span class="avatar">
													<img src="assets/img/user.jpg" alt="John Doe">
												</span>
											</div>
											<div class="media-body media-middle text-nowrap">
												<div class="user-name">Jeffery Lalor</div>
												<span class="designation">Team Leader</span>
											</div>
										</a>
									</li>
								</ul>
							</div>
							<div class="submit-section">
								<button class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="assign_user" class="modal custom-modal fade center-modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Assign the user to this project</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<div class="input-group m-b-30">
								<input placeholder="Search a user to assign" class="form-control search-input input-lg" id="btn-input" type="text">
								<span class="input-group-btn">
									<button class="btn btn-primary btn-lg">Search</button>
								</span>
							</div>
							<div>
								<ul class="media-list media-list-linked chat-user-list">
									<li class="media">
										<a href="#" class="media-link">
											<div class="media-left"><span class="avatar">R</span></div>
											<div class="media-body media-middle text-nowrap">
												<div class="user-name">Richard Miles</div>
												<span class="designation">Web Developer</span>
											</div>
										</a>
									</li>
									<li class="media">
										<a href="#" class="media-link">
											<div class="media-left"><span class="avatar">J</span></div>
											<div class="media-body media-middle text-nowrap">
												<div class="user-name">John Smith</div>
												<span class="designation">Android Developer</span>
											</div>
										</a>
									</li>
									<li class="media">
										<a href="#" class="media-link">
											<div class="media-left">
												<span class="avatar">
													<img src="assets/img/user.jpg" alt="John Doe">
												</span>
											</div>
											<div class="media-body media-middle text-nowrap">
												<div class="user-name">Jeffery Lalor</div>
												<span class="designation">Team Leader</span>
											</div>
										</a>
									</li>
								</ul>
							</div>
							<div class="submit-section">
								<button class="btn btn-primary submit-btn">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>