
			<div class="chat-main-row">

			
				<div class="chat-main-wrapper">
					<div class="col-lg-7 message-view task-view">
							<div class="chat-window">
										<div class="chat-contents">
											<div class="chat-content-wrap">
												<div class="chat-wrap-inner">
													<div class="chat-box">
														<div class="task-wrapper">
<!-- <div class="row">
						<div class="col-sm-4">
							<a href="<?php echo base_url(); ?>projects" class="btn back-btn bk-alt" ><i class="fa fa-chevron-left"></i><?php echo lang('back');?></a>			
						</div>
					</div><br> -->
														<div class="row board-view-header mb-2">
						<div class="col-lg-4">
							<h4 class="page-title"><?php echo lang('business_proposals');?></h4>
							
						</div>
						<?php  $this->db->select('sum(project_amount) as total_amount,count(id) as lead_count');
					        $this->db->from('business_proposals');
					        $total_leads = $this->db->get()->row_array();
					        // echo $this->db->last_query();?>
						<div class="col-lg-4 text-center">
							<span><b><?php echo '$'.$total_leads['total_amount'].' - '.$total_leads['lead_count'].' deals';?></b></span>
						</div>
						<div class="col-lg-4 text-right">
							<a class="btn back-btn" href="<?=base_url()?>crm"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
							<?php

							if(App::is_permit('menu_business_proposals','create'))
							{
							?>
							
							<a href="#" class="btn btn-white float-right m-r-10" data-toggle="modal" data-target="#add_task_board"><i class="fa fa-plus"></i> Create List</a>			
							<?php
							}
							?>
						</div>
					</div>

					<div class="kanban-board card-box m-b-0 loader">
						
						<div class="kanban-cont">
							<?php $task_board = $this->db->order_by('task_board_id', 'ASC')->get_where('lead_board')->result_array(); 
							if(count($task_board) != 0)
							{
								foreach($task_board as $board){

								 $this->db->select('sum(project_amount) as total_amount,count(id) as lead_count');
					        $this->db->from('business_proposals');
					        $this->db->where('lead_status',$board['task_board_id']);
					        $board_leads = $this->db->get()->row_array();

									?>
							<div class="kanban-list kanban-<?php echo $board['task_board_class']?>">
								<div class="kanban-header" >
									<div class="kanban-header-left">
									<span class="status-title"><?php echo ucfirst($board['task_board_name']);?></span>
									<br>
									<?php if(!empty($board_leads['lead_count'])){?>
										<span class="status-title-sub"><?php echo '$'.$board_leads['total_amount'].' - '.$board_leads['lead_count'].' deals';?></span>
									<?php } ?>
									</div>
									<?php
									if(App::is_permit('menu_business_proposals','write')==true || App::is_permit('menu_business_proposals','delete')==true)
									{
									?>	
									<div class="dropdown kanban-action">
										<a href="" data-toggle="dropdown">
											<i class="fa fa-ellipsis-v"></i>
										</a>
										
										<div class="dropdown-menu float-right dropdown-menu-right">
											<?php

											if(App::is_permit('menu_business_proposals','write'))
											{
											?>	
											<a class="dropdown-item" data-id="<?php echo $board['task_board_id']; ?>" href="<?php echo base_url(); ?>crm/edit_task_board/<?php echo $board['task_board_id']; ?>" data-toggle="ajaxModal">Edit </a>
														
											<!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_task_board<?php echo $board['task_board_id'];?>">Edit</a> -->
											<?php
											}
											if(App::is_permit('menu_business_proposals','delete'))
											{
											?>
											<a class="dropdown-item" data-toggle="ajaxModal" href="<?=base_url()?>crm/lead_board_delete/<?php echo $board['task_board_id']; ?>">Delete</a>
											<?php
											}
											
											?>
										</div>
										
									</div>
									<?php
									}
									?>
								</div>
								
									<?php

										$this->db->select('*');
								        $this->db->from('business_proposals b');
								        $this->db->join('lead_board l','b.lead_status = l.task_board_id','LEFT');
								        $this->db->order_by('id','ASC');
								        $all_leads = $this->db->get()->result_array();
									
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
									<div class="card panel" ondblclick="kanban_redirect(<?php echo $leads['id']?>,<?php echo $leads['lead_status']?>);">
										<div class="kanban-box">
											<div class="task-board-header">
												<a class="status-title" href="<?php echo base_url(); ?>crm/edit_lead/<?php echo $leads['id']; ?>" data-toggle="ajaxModal"><?php echo ucfirst($leads['name']);?> </a>
												<div class="avatar float-right">
													<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $leads['name'];?>" src="<?php echo $pro_pic;?>">
												</div>
											</div>
											<div class="task-board-body">
												<div class="kanban-info">
													<span><?php echo $leads['project_name'];?></span>
													
												</div>

												<div class="kanban-footer">
													<span class="task-info-cont">
													
														<span><?=Applib::format_currency(config_item('default_currency'), $leads['project_amount'])?></span>
													</span>
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
							</div>
							<?php }
						} else { echo "No Task Found";}?>
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
					<div id="add_task_board" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog  modal-dialog-centered">
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
			