 
		<?php //$this->load->view('sub_menus');?>	
			
                <div class="content container-fluid">



			<div class="chat-main-row-candidate">

			
				<div class="chat-main-wrapper">
					<div class="col-7 message-view task-view">
							<div class="chat-window">
								
										<div class="chat-contents">
											<div class="chat-content-wrap">
												<div class="chat-wrap-inner">
													<div class="chat-box">
														<div class="task-wrapper">

														<div class="row board-view-header mb-2">
						<div class="col-4">
							<h4 class="page-title"><?php echo lang('candidate_status');?></h4>
							
						</div>
						
						<div class="col-4 text-center">
							
						</div>
						<div class="col-4 text-right">
						
							<a class="btn back-btn" href="<?=base_url()?>jobs/candidates"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
									
							
							
						</div>
						
					</div>

					<div class="kanban-board card-box m-b-0 loader">
						
						<div class="kanban-cont">
							<?php

							/*candidate status */


							$status_array = array(
								'0' => 'Applied',
								'1' => 'Resume Selected',
								'2' => 'Resume Rejected',
								'3' => 'Aptitude Selected',
								'4' => 'Aptitude Rejected',
								'5'=>'Video call selected', 
								'6'=>'Video call Rejected', 
								'7' => 'offered',
								'8'=>'offer accepted',
								'9' => 'offer rejected',
								'10'=>'offer decline'
							);

							$color_array = array(
								'0' => 'warning',
								'1' => 'success',
								'2' => 'danger',
								'3' => 'success',
								'4' => 'danger',
								'5' => 'success',
								'6' => 'danger',
								'7' => 'primary',
								'8' => 'purple',
								'9' => 'danger',
								'10' => 'danger',
							);

							foreach($candidates_status as $key => $list){
								$candidates[$list->user_job_status][] = array(
									'candidate_id' =>$list->candidate_id,
									'job_id' =>$list->id,
									'name' =>$list->first_name.' '.$list->last_name,
									'job_title' =>$list->job_title,
									'job_type' =>$list->job_type,
								);
							}

								foreach($status_array as $key => $status){

								?>
							<div class="kanban-list kanban-<?php echo $color_array[$key]?>">
								<div class="kanban-header" >
									<div class="kanban-header-left">
									<span class="status-title"><?php echo ucfirst($status);?></span>
									<br>
								
									</div>
									
								</div>
								
									<?php
									foreach($candidates[$key] as $key1 =>$lists){
									?>

									<div class="kanban-wrap" id="<?php echo $key1;?>" data-status="<?php echo $key;?>" data-id="<?php echo $lists['candidate_id'];?>" data-jobid="<?php echo $lists['job_id'];?>">								         		
									<div class="card panel">
										<div class="kanban-box">
											<div class="task-board-header">
												<a class="status-title" href="<?php echo '#'; ?>"><?php echo ucfirst($lists['name']);?> </a>
												<div class="avatar float-right">
													<img class="avatar-img rounded-circle border border-white" alt="User Image" title="<?php echo $lists['name'];?>" src="<?php echo base_url().'assets/avatar/default_avatar.jpg';?>">
												</div>
												
											</div>
											<div class="task-board-body">
												<div class="kanban-info">
													
													<span><?php echo $lists['job_title'];?></span>
													
												</div>

												
												<div class="kanban-footer">
													<span class="task-info-cont">
														<span><?php echo $lists['job_type'];?></span>
														
													</span>
													
												</div>
											</div>
										</div>
									</div>
									</div>
								<?php 
							}
							?>
								<div class="kanban-wrap kanban-empty ui-sortable" id="<?php echo $key;?>" >
								</div>
								
							</div>
							<?php 
						} ?>
						</div>
					</div>
					
		

	
	
			
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
		</div>
			<?php  
        //echo 123; exit; 
	 			  ?>