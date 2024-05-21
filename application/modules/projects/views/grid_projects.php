<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-xs-3">
							<h4 class="page-title"><?=($archive ? lang('project_archive') : lang('projects'));?></h4>
						</div>
						<div class="col-sm-7 col-xs-9 text-right m-b-30">
							<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects') || User::login_role_name() == 'client' && config_item('client_create_project') == 'TRUE') { ?>
							<a href="<?=base_url()?>projects/add" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?=lang('create_project')?></a>
							<?php } ?>
							<?php $user_details = $this->db->get_where('users',array('id'=>$this->session->userdata('user_id'),'role_id'=>3,'is_teamlead'=>'yes'))->row_array(); 
							if($user_details != '')
							{ ?>
							<a href="<?=base_url()?>projects/add" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?=lang('create_project')?></a>
							<?php 
							}
							?>
							<div class="view-icons">
								<a href="<?php echo base_url(); ?>projects" class="list-view btn btn-link "><i class="fa fa-bars"></i></a>
								<a href="<?php echo base_url(); ?>projects/grid_view" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
							</div>
						</div>
					</div>


					<!-- /Page Title -->
					
					<!-- Search Filter -->
					<div class="row filter-row">
						<div class="col-lg-4 col-sm-6 col-xs-12">  
							<div class="form-group form-focus">
							<label class="control-label">Project Title</label>
							<input type="text" class="form-control floating" id="project_title" name="project_title">
							<label id="project_title_error" class="error display-none" for="project_title">Project Title Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-12">  
							<div class="form-group form-focus">
								<label class="control-label">Client Name</label>
								<input type="text" class="form-control floating"  id="client_name" name="client_name">
								<label id="client_name_error" class="error display-none" for="client_name">Client Name Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-12">  
							<a href="javascript:void(0)" id="project_search_btn" class="btn btn-success btn-block form-control"> Search </a>  
						</div>     
                    </div>
					<!-- Search Filter -->
					
					<div class="row">
						<?php foreach ($projects as $key => $p) { 
							$progress = Project::get_progress($p->project_id); 
							$open_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id,'task_progress !='=>100))->result_array(); 
						    $completed_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id,'task_progress'=>100))->result_array(); 
						    $all_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id))->result_array();
						?>
						<div class="col-lg-3 col-sm-4">
							<div class="card-box project-box">
								<div class="dropdown profile-action">
									<a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
									<ul class="dropdown-menu pull-right">
										<li><a href="<?=base_url()?>projects/edit/<?=$p->project_id?>"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
										<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_projects')){ ?> 
										<li><a href="<?=base_url()?>projects/delete/<?=$p->project_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
										<?php } ?>
									</ul>
								</div>
								<h4 class="project-title"><a href="<?php echo base_url(); ?>projects/view/<?php echo $p->project_id; ?>"><?php echo $p->project_title; ?></a></h4>
								<small class="block text-ellipsis m-b-15">
									<span class="text-xs"><?php echo count($open_tasks); ?></span> <span class="text-muted">open tasks, </span>
									<span class="text-xs"><?php echo count($completed_tasks); ?></span> <span class="text-muted">tasks completed</span>
								</small>
								<p class="text-muted pro-desc"><?php $para = strip_tags($p->description); echo substr($para, 0, 80).'...'; ?></p>
								<div class="pro-deadline m-b-15">
									<div class="sub-title">
										Deadline:
									</div>
									<div class="text-muted">
										<?php echo date('d M Y',strtotime($p->due_date)); ?>
									</div>
								</div>
								<div class="project-members m-b-15">
									<div>Project Leader :</div>
									<?php $lead_details = $this->db->get_where('account_details',array('user_id'=>$p->assign_lead))->row_array(); ?>
									<ul class="team-members">
										<li>
											<a href="#" data-toggle="tooltip" title="<?php echo $lead_details['fullname']; ?>"><img src="<?php echo base_url(); ?>assets/avatar/<?php echo $lead_details['avatar']; ?>" alt="<?php echo $lead_details['fullname']; ?>"></a>
										</li>
									</ul>
								</div>
								<div class="project-members m-b-15">
									<div>Team :</div>
									<ul class="team-members">
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
											<a href="#" data-toggle="tooltip" title="<?php echo $member_details['fullname']; ?>"><img src="<?php echo base_url(); ?>assets/avatar/<?php echo $member_details['avatar']; ?>" alt="<?php echo $member_details['fullname']; ?>"></a>
										</li>
										<?php } ?>
									</ul>
								</div>
								<p class="m-b-5">Progress <span class="text-success float-right"><?php echo $p->progress; ?>%</span></p>
								<div class="progress progress-xs mb-0">
									<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $p->progress; ?>%" style="width: <?php echo $p->progress; ?>%"></div>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if(($this->session->userdata('role_id') != 1) && ($this->session->userdata('role_id') != 4)) {
							$created_check = $this->db->get_where('projects',array('created_by'=>$this->session->userdata('user_id'),'archived '=>'0'))->result(); 
							foreach ($created_check as $key => $p1) { 
							$progress = Project::get_progress($p1->project_id);
							$open_tasks1 = $this->db->get_where('tasks',array('project'=>$p1->project_id,'task_progress !='=>100))->result_array(); 
						    $completed_tasks1 = $this->db->get_where('tasks',array('project'=>$p1->project_id,'task_progress'=>100))->result_array(); 
						    $all_tasks1 = $this->db->get_where('tasks',array('project'=>$p1->project_id))->result_array();
						?>
						<div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
							<div class="card-box project-box">
								<div class="dropdown dropdown-action profile-action">
									<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right">
										<a href="<?=base_url()?>projects/edit/<?=$p1->project_id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_project')?></a>
										<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_projects')){ ?> 
										<a href="<?=base_url()?>projects/delete/<?=$p1->project_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete_project')?></a>
										<?php } ?>
									</div>
								</div>
								<h4 class="project-title"><a href="<?php echo base_url(); ?>projects/view/<?php echo $p1->project_id; ?>"><?php echo $p1->project_title; ?></a></h4>
								<small class="block text-ellipsis m-b-15">
									<span class="text-xs"><?php echo count($open_tasks1); ?></span> <span class="text-muted">open tasks, </span>
									<span class="text-xs"><?php echo count($completed_tasks1); ?></span> <span class="text-muted">tasks completed</span>
								</small>
								<p class="text-muted"><?php $para1 = strip_tags($p1->description); echo substr($para1, 0, 80).'...'; ?>
								</p>
								<div class="pro-deadline m-b-15">
									<div class="sub-title">
										Deadline:
									</div>
									<div class="text-muted">
										<?php echo date('d M Y',strtotime($p1->due_date)); ?>
									</div>
								</div>
								<div class="project-members m-b-15">
									<div>Project Leader :</div>
									<?php $lead_details = $this->db->get_where('account_details',array('user_id'=>$p1->assign_lead))->row_array(); ?>
									<ul class="team-members">
										<li>
											<a href="#" data-toggle="tooltip" title="<?php echo $lead_details['fullname']; ?>"><img src="<?php echo base_url(); ?>assets/avatar/<?php echo $lead_details['avatar']; ?>" alt="<?php echo $lead_details['fullname']; ?>"></a>
										</li>
									</ul>
								</div>
								<div class="project-members m-b-15">
									<div>Team :</div>
									<ul class="team-members">
										<?php $all_members = $this->db->get_where('assign_projects',array('project_assigned'=>$p1->project_id))->result_array(); 

									    foreach($all_members as $members){

                                            $member_details = $this->db->select('*')

                                                                        ->from('users U')

                                                                        ->join('account_details AD','U.id = AD.user_id')

                                                                        ->where('U.id',$members['assigned_user'])

                                                                        ->get()->row_array();

                                            $designation = $this->db->get_where('designation',array('id'=>$member_details['designation_id']))->row_array();



									    ?>
										<li>
											<a href="#" data-toggle="tooltip" title="<?php echo $member_details['fullname']; ?>"><img src="<?php echo base_url(); ?>assets/avatar/<?php echo $member_details['avatar']; ?>" alt="<?php echo $member_details['fullname']; ?>"></a>
										</li>
										<?php } ?>
									</ul>
								</div>
								<p class="m-b-5">Progress <span class="text-success float-right"><?php echo $p1->progress; ?>%</span></p>
								<div class="progress progress-xs mb-0">
									<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $p1->progress; ?>%" style="width: <?php echo $p1->progress; ?>%"></div>
								</div>
							</div>
						</div>

					<?php } } ?>
					</div>
                </div>