<!-- Start -->
<div class="content">

					
	<!-- Page Header -->
	<div class="page-header mt-0">
		<div class="row align-items-center">
			<div class="col-sm-5">
				<h4 class="page-title"><?=lang('tickets')?></h4>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><?=lang('tickets')?></li>
				</ul>
			</div>
					
					
			<div class="col-sm-7 text-right m-b-20">
				<?php
				// if(App::is_permit('menu_tickets','create'))
				// {
					?>
				<a href="<?=base_url()?>tickets/add" class="btn add-btn mb-1"><i class="fa fa-plus"></i> <?=lang('create_ticket')?></a>
				<?php
				// }
				?>
				<?php //if(!User::is_client()) { ?>
				<?php //if ($archive) : ?>
				<!-- <a href="<?=base_url()?>tickets" class="btn float-end add-btn m-r-10 mb-1"><?=lang('view_active')?></a> -->
				<?php //else: ?>
				<!-- <a href="<?=base_url()?>tickets?view=archive" class="btn btn-info float-end add-btn m-r-10 mb-1"><?=lang('view_archive')?></a> -->
				<?php //endif; ?>
				<?php //} ?>
				
				<div class="btn-group float-end m-r-10 mb-1">
					<button class="btn bg-white dropdown-toggle" data-toggle="dropdown">
			  
					  <?php
					  $view = isset($_GET['view']) ? $_GET['view'] : NULL;
					  switch ($view) {
						case 'pending':
						  echo lang('pending');
						  break;
						case 'closed':
						  echo lang('closed');
						  break;
						case 'open':
						  echo lang('open');
						  break;
						case 'resolved':
						  echo lang('resolved');
						  break;

						case 'inprogress':
							echo lang('inprogress');
							break;

						default:
						  echo lang('filter');
						  break;
					  }
					  ?>
					</button>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=pending"><?=lang('pending')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=closed"><?=lang('closed')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=open"><?=lang('open')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=resolved"><?=lang('resolved')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=inprogress"><?=lang('inprogress')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets"><?=lang('all_tickets')?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
		
	<form method="post">	
		<div class="row filter-row">
			<!-- <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
				<div class="form-group form-focus ">
					<label class="control-label">Reporter</label>
					<input type="text" class="form-control floating ticket_search_submit" id="employee_name" name="employee_name" />
					<label id="employee_name_error" class="error display-none" for="employee_name">Reporter Shouldn't be empty</label>
				</div>
			</div> -->

			<!-- <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
				<div class="form-group form-focus select-focus ">
					<label class="control-label">Status</label>
					<select class="select floating form-control" id="ticket_status" name="ticket_status"> 
						<option value=""> All Tickets</option>
						<option value="Pending"> Pending </option>
						<option value="Closed"> Closed </option>
						<option value="Open"> Open </option>
						<option value="Resolved"> Resolved </option>
						<option value="Inprogress"> Inprogress </option>
					</select>
					<label id="ticket_status_error" class="error display-none" for="ticket_status">Please Select a status</label>
				</div>
			</div> -->
			
			<?php $priorities = $this->db->order_by('hour','DESC')->get('priorities')->result_array();?>
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
				<div class="form-group form-focus select-focus">
					<label class="control-label">Priority</label>
					<select class="select floating form-control" id="ticked_priority" name="ticked_priority"> 
						<option value="" selected="selected"> Select </option>
						<?php foreach ($priorities as $key => $value) {?>
						<option <?php if($this->session->userdata('search_ticket_priority') !=''){ if($this->session->userdata('search_ticket_priority') == $value['id']) { echo 'selected="selected"'; } }?> value="<?php echo $value['id'];?>" > <?php echo ucfirst($value['priority']) ?> </option> 
						<?php } ?>
					</select>
					<label id="ticked_priority_error" class="error display-none" for="ticked_priority">Please Select a priority</label>
				</div>
			</div>
			
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
				<div class="form-group form-focus">
					<label class="control-label">From</label>
					<div class="cal-icon"><input class="form-control floating" id="ticket_from" data-date-format="dd-mm-yyyy" value="<?php if($this->session->userdata('search_ticket_from_date') !=''){ echo $this->session->userdata('search_ticket_from_date');  } ?>" name="ticket_from" type="text"></div>
					<label id="ticket_from_error" class="error display-none" for="ticket_from">From Date Shouldn't be empty</label>
				</div>
			</div>
				
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
					<div class="form-group form-focus">
					<label class="control-label">To</label>
					<div class="cal-icon">
						<input class="form-control floating" id="ticket_to" data-date-format="dd-mm-yyyy" value="<?php if($this->session->userdata('search_ticket_to_date') !=''){ echo $this->session->userdata('search_ticket_to_date');  } ?>" name="ticket_to" type="text">
					</div>
					<label id="ticket_to_error" class="error display-none" for="ticket_to">To Date Shouldn't be empty</label>
				</div>
			</div>
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
				<input href="javascript:void(0)"  type="submit" class="btn btn-primary text-white btn-block" value="Search">  
				<!-- <a href="javascript:void(0)" id="ticket_search_btn" class="btn btn-primary text-white btn-block"> Search </a>   -->
			</div> 
		</div>
	</form>
	

	
	<div class="row">
		<?php
				$priorities1 = $this->db->order_by('hour','DESC')->get('priorities')->result();
					foreach ($priorities1 as $p){
						$p_color[$p->id] = $p->color;
						$p_name[$p->id] = $p->priority;
					} 

					?>
		<div class="col-md-12"> 
			<div class="card-box tab-box">
				<div class="row user-tabs">
					<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
						<ul class="nav nav-tabs nav-tabs-solid" role="tablist">
							<?php 
							 $user_details = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); 

							// if($user_details['department_id'] ==1 || $user_details['department_id'] ==3 || $user_details['department_id']==9 || $user_details['department_id'] ==10 || $user_details['id'] == 1){ ?>
								<li class="nav-item active"><a class=" nav-link" href="#raised_tickets" data-toggle="tab">Raised Tickets</a></li>
								<li class="nav-item"><a class="nav-link" href="#my_tickets" data-toggle="tab">My Tickets</a></li>
							<?php //} else { ?>
								<!--<li class="nav-item"><a class="active nav-link" href="#my_tickets" data-bs-toggle="tab">My Tickets</a></li>-->
							<?php //} ?>
						</ul>
						<div class="tab-content" style="padding: 25px;">
							<div id="raised_tickets" class="pro-overview tab-pane fade active in">
								<h4>Raised Tickets</h4>
								
								<div class="wrapper1">
									<div class="table-responsive1 table-responsive">
									
									</div>
								</div>
								
								<div class="wrapper2">
									<div class="table-responsive1 table-responsive">
									<table id="table-tickets<?=($archive) ? '-archive':''?>" class="table table-striped custom-table m-b-0 AppendDataTables">
											<thead>
												<tr>
													<th>#</th>
													<th>Ticket Number</th>
													<th><?=lang('subject')?></th>
													<?php if (User::is_admin() || User::is_staff()) { ?>
													<!-- <th><?=lang('reporter')?></th> -->
													<?php } ?>
													<?php //if (User::is_admin() || User::is_staff()) { ?>
													<th>Assignee</th>
													<?php //} ?>
													<th class="col-date"><?=lang('date')?></th>
													<th class="col-options no-sort"><?=lang('priority')?></th>
													<!-- <th class=""><?=lang('department')?></th> -->
													<th class="text-center"><?=lang('status')?></th>
													<th class="text-center">Created By</th>
													<!-- <th style="width:5px;">Created Date</th> -->
													<th class=" text-center"><?=lang('action')?></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$this->load->helper('text');
												$i=1;
												foreach ($tickets as $key => $t) {
												$s_label = 'default';
												if($t->status == 'open') $s_label = 'danger';
												if($t->status == 'closed') $s_label = 'primary';
												if($t->status == 'resolved') $s_label = 'success';
												if($t->status == 'pending') $s_label = 'warning';
												if($t->status == 'inprogress') $s_label = 'info';
											?>
											<tr>
												<td><?=$i;?></td>
												<td><?php echo $t->ticket_code;?></td>
												<td>
													<?php $rep = $this->db->where('ticketid',$t->id)->get('ticketreplies')->num_rows();
													if($rep == 0){ ?>
													<h2>
													<a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>" data-toggle="tooltip" data-title="<?=lang('ticket_not_replied')?>"></h2>
													<?php }else{ ?>
													<h2><a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>">
													<?php } ?>

													<?=word_limiter(ucfirst($t->subject), 8);?>
													</a></h2><br>
													<?php if($rep == 0 && $t->status != 'closed'){ ?>
													<span class="text-danger f-12">Pending for <?=Applib::time_elapsed_string(strtotime($t->created));?></span>
													<?php } ?>
												</td>
												
												<?php if (User::is_admin() || User::is_staff()) { ?>
												<!-- <td>
													<?php
													if($t->reporter != NULL){ ?>
													<h2 class="table-avatar">
														<a class="avatar avatar-xs" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?php echo User::login_info($t->reporter)->email; ?>" data-placement="right">
															<img alt="" src="<?php echo User::avatar_url($t->reporter); ?>">
														</a>
														<a href="javascript:void(0);"><?php echo ucfirst(User::displayName($t->reporter)); ?></a>
													</h2>
													<?php } else { echo "NULL"; } ?>
												</td> -->
												<?php } ?>
												
												<?php //if (User::is_admin() || User::is_staff()) { ?>
												<td>
												<?php
													if($t->assignee != 0){ ?>
													<h2 class="table-avatar d-flex align-items-center">
														<a class="avatar avatar-xs" href="javascript:void(0);" data-toggle="tooltip" title="<?php echo User::login_info($t->assignee)->email; ?>" data-placement="right">
															<img alt="" src="<?php echo User::avatar_url($t->assignee); ?>">
														</a>
														<a href="javascript:void(0);"><?php echo(!empty($t->assignee))?ucfirst(User::displayName($t->assignee)):""; ?></a>
													</h2>
													<?php } else { echo "-"; } ?>
												</td>
												<?php //} ?>

												<td><?=date("D, d M g:i:A",strtotime($t->created));?><br/>
													<span class="text-primary f-12">(<?=Applib::time_elapsed_string(strtotime($t->created));?>)</span>
												</td>

												<td>
													<span class="badge badge-primary f-14 text-white" style="background: <?php echo $p_color[$t->priority]; ?> !important;"> <?php echo ucfirst($p_name[$t->priority]);?></span>
												</td>

												<!-- <td>
													<?php 
													// $department = App::get_dept_by_id($t->department);
													// if(!empty($department)){echo ucfirst($department);}else{echo '-';} ?>
												</td> -->

												<td class="text-center">
												<?php
													switch ($t->status) {
														case 'open':
															$status_lang = 'open';
															break;
														case 'closed':
															$status_lang = 'closed';
															break;
														case 'pending':
															$status_lang = 'pending';
															break;
														case 'resolved':
															$status_lang = 'resolved';
															break;
														case 'inprogress':
															$status_lang = 'inprogress';
															break;

														default:
															# code...
															break;
													}
													?>
													<span class="badge f-14 badge-<?=$s_label?>"><?=ucfirst(lang($status_lang))?></span>
												</td>
												<td style=""><?=ucfirst(User::displayName($t->created_by));?></td>                  
												<!-- <td style=""><?=date("d/m/Y",strtotime($t->created));?></td>                   -->
												<td class="text-center">
												
												<div class="dropdown">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<ul class="dropdown-menu pull-right">
												
												<?php //if(App::is_permit('menu_tickets','read')){?>
												
												<li><a class="dropdown-item" class="" href="<?=base_url()?>tickets/view/<?=$t->id?>"><i class="fa fa-crosshairs m-r-5"></i><?=lang('preview_ticket')?></a></li>
												
												<?php //} ?>
												</ul>
											</div>
											
													<!--<div class="dropdown dropdown-action">
														<a data-toggle="dropdown" class="action-icon" href="#">
															<i class="material-icons">more_vert</i>
														</a>
														<div class="dropdown-menu float-end dropdown-menu-right">

															<?php if(App::is_permit('menu_tickets','read')){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/view/<?=$t->id?>"><i class="fa fa-crosshairs m-r-5"></i><?=lang('preview_ticket')?></a><?php }?>
															
															<!-- <?php if(App::is_permit('menu_tickets','write') || $t->created_by==User::get_id()){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/edit/<?=$t->id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_ticket')?></a><?php }?> -->
															<?php if(User::is_admin()){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/edit/<?=$t->id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_ticket')?></a><?php }?>
															<?php if(App::is_permit('menu_tickets','delete') && $t->status!='resolved'){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete_ticket')?></a><?php }?>

															<?php //if ($archive) : ?>
															<!-- <a class="dropdown-item" class="" href="<?=base_url()?>tickets/archive/<?=$t->id?>/0"><i class="fa fa-archive m-r-5" aria-hidden="true"></i><?=lang('move_to_active')?></a> -->
															<?php //else: ?>
															<!-- <a class="dropdown-item" class="" href="<?=base_url()?>tickets/archive/<?=$t->id?>/1"><i class="fa fa-archive m-r-5" aria-hidden="true"></i> <?=lang('archive_ticket')?></a> -->
															<?php //endif; ?>
															
														<!--</div>
													</div>-->
												</td>
											</tr>
											<?php $i++; } ?>
										</tbody>
									</table>
								</div>
								</div>
								
							</div>
							<div id="my_tickets" class="pro-overview tab-pane fade ">
								<h4>My Tickets</h4>
								
								<div class="wrapper1">
									<div class="table-responsive1 table-responsive">
									
									</div>
								</div>
								
								<div class="wrapper2">
									<div class="table-responsive2 table-responsive">
									<table class="table table-striped custom-table m-b-0 AppendDataTables">
										<thead>
											<tr>
											<th>#</th>
											<th>Ticket Number</th>
											<th><?=lang('subject')?></th>
											<?php if (User::is_admin() || User::is_staff()) { ?>
											<!-- <th><?=lang('reporter')?></th> -->
											<?php } ?>
											<th><?=lang('assignee')?></th>
											<?php //} ?>
											<th class="col-date"><?=lang('date')?></th>
											<th class="col-options no-sort"><?=lang('priority')?></th>
											<th class="text-center"><?=lang('status')?></th>
											<th class="text-center">Created By</th>
											<th class=" text-center"><?=lang('action')?></th>
											</tr>
										</thead>
										<tbody>
										<?php
											$this->load->helper('text');
											$j=1;
											foreach ($my_tickets as $key => $t) {
											$s_label = 'default';
											if($t->status == 'open') $s_label = 'danger';
											if($t->status == 'closed') $s_label = 'primary';
											if($t->status == 'resolved') $s_label = 'success';
											if($t->status == 'pending') $s_label = 'warning';
											if($t->status == 'inprogress') $s_label = 'info';
										?>
										<tr>
											<td><?=$j;?></td>
											<td><?php echo $t->ticket_code;?></td>
											<td>
											<?php $rep = $this->db->where('ticketid',$t->id)->get('ticketreplies')->num_rows();
											if($rep == 0){ ?>
											<h2>
											<a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>" data-toggle="tooltip" data-title="<?=lang('ticket_not_replied')?>"></h2>
											<?php }else{ ?>
											<h2><a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>">
											<?php } ?>

											<?=word_limiter(ucfirst($t->subject), 8);?>
											</a></h2><br>
											<?php if($rep == 0 && $t->status != 'closed'){ ?>
											<span class="text-danger f-12">Pending for <?=Applib::time_elapsed_string(strtotime($t->created));?></span>
											<?php } ?>
											</td>
											
											<td>
											<?php
											if($t->assignee != 0){ ?>
											<h2 class="table-avatar d-flex align-items-center">
												<a class="avatar avatar-xs" href="javascript:void(0);" data-toggle="tooltip" title="<?php echo User::login_info($t->assignee)->email; ?>" data-placement="right">
												<img alt="" src="<?php echo User::avatar_url($t->assignee); ?>">
												</a>
												<a href="javascript:void(0);"><?php echo(!empty($t->assignee))?ucfirst(User::displayName($t->assignee)):""; ?></a>
											</h2>
											<?php } else { echo "-"; } ?>
											</td>
											<?php //} ?>

											<td><?=date("D, d M g:i:A",strtotime($t->created));?><br/>
											<span class="text-primary f-12">(<?=Applib::time_elapsed_string(strtotime($t->created));?>)</span>
											</td>

											<td>
											<span class="badge badge-primary f-14 text-white" style="background: <?php echo $p_color[$t->priority]; ?> !important;"> <?php echo ucfirst($p_name[$t->priority]);?></span>
											</td>
											<td class="text-center">
											<?php
											switch ($t->status) {
												case 'open':
												$status_lang = 'open';
												break;
												case 'closed':
												$status_lang = 'closed';
												break;
												case 'pending':
												$status_lang = 'pending';
												break;
												case 'resolved':
												$status_lang = 'resolved';
												break;
												case 'inprogress':
												$status_lang = 'inprogress';
												break;

												default:
												# code...
												break;
											}
											?>
											<span class="badge f-14 badge-<?=$s_label?>"><?=ucfirst(lang($status_lang))?></span>
											</td>
											<td style=""><?=ucfirst(User::displayName($t->created_by));?></td>                  
											<td class="text-center">
											
											<div class="dropdown">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<ul class="dropdown-menu pull-right">
												
												<?php //if(App::is_permit('menu_tickets','read')){ ?>
												<li><a class="dropdown-item" class="" href="<?=base_url()?>tickets/view/<?=$t->id?>"><i class="fa fa-crosshairs m-r-5"></i><?=lang('preview_ticket')?></a></li>
												
												<?php //} ?>
												
												<?php //if(User::is_admin()){?>
												<li><a class="dropdown-item" class="" href="<?=base_url()?>tickets/edit/<?=$t->id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_ticket')?></a></li>
												
												<?php //} ?>
												
												<?php //if(App::is_permit('menu_tickets','delete') && $t->status!='resolved'){?>
												<li><a class="dropdown-item" class="" href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete_ticket')?></a></li>
												
												
												<?php //} ?>


												
													
													
												</ul>
											</div>
											
											<!--<div class="dropdown dropdown-action">
												<a data-toggle="dropdown" class="action-icon" href="#">
												<i class="material-icons">more_vert</i>
												</a>
												<div class="dropdown-menu float-end dropdown-menu-right">

												<?php if(App::is_permit('menu_tickets','read')){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/view/<?=$t->id?>"><i class="fa fa-crosshairs m-r-5"></i><?=lang('preview_ticket')?></a><?php }?>
												
												<?php if(User::is_admin()){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/edit/<?=$t->id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_ticket')?></a><?php }?>
												<?php if(App::is_permit('menu_tickets','delete') && $t->status!='resolved'){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete_ticket')?></a><?php }?>

												
												</div>
											</div>-->
											</td>
										</tr>
										<?php $j++; } ?>
										</tbody>
									</table>
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

