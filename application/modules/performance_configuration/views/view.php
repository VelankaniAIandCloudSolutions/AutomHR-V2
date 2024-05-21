
<?php
$i = Client::view_by_id($company);
$cur = Client::client_currency($i->co_id);
$due = Client::due_amount($i->co_id);
($i->is_lead == 1) ? redirect('leads/view/'.$i->co_id) : '';
?>
<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-12">
							<h4 class="page-title">My Profile</h4>
						</div>
					</div>
					<!-- /Page Title -->
					
					<div class="card-box mb-0">
						<div class="row">
							<div class="col-md-12">
								<div class="profile-view">
									<div class="profile-img-wrap">
										<div class="profile-img">
											<a href=""><img class="avatar" src="<?php echo base_url(); ?>assets/img/user.jpg" alt=""></a>
										</div>
									</div>
									<div class="profile-basic">
										<div class="row">
											<div class="col-md-5">
												<div class="profile-info-left">
													<h3 class="user-name m-t-0"><?=$i->company_name?></h3>
													<h5 class="company-role m-t-0 mb-0"></h5>
													<small class="text-muted">CEO</small>
													<div class="staff-id">Company ID : <?=$i->company_ref?></div>
													<div class="staff-msg"><a href="chat.html" class="btn btn-custom">Send Message</a></div>
												</div>
											</div>
											<div class="col-md-7">
												<ul class="personal-info">
													<li>
														<span class="title">Phone:</span>
														<span class="text"><a href=""><?=$i->company_mobile?></a></span>
													</li>
													<li>
														<span class="title">Email:</span>
														<span class="text"><a href=""><?=$i->company_email?></a></span>
													</li>
													<li>
														<span class="title">Address:</span>
														<span class="text"><?=$i->company_address?$i->company_address:''?> <?=$i->city?$i->city:''?> <?=$i->state?$i->state:''?> <?=$i->country?$i->country:''?>  <?=$i->zip?$i->zip:''?></span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-box tab-box">
						<div class="row user-tabs">
							<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="active"><a class="nav-link active" data-toggle="tab" href="#myprojects">My Projects</a></li>
									<li class="<?=($tab == 'contacts') ? 'active' : '';?>">
										<a data-toggle="tab" href="#mycontacts"><?=lang('contacts')?></a>
									</li>
									<li class="<?=($tab == 'invoices') ? 'active' : '';?>">
										<a data-toggle="tab" href="#myinvoices"><?=lang('invoices')?></a>
									</li>
									<li class="<?=($tab == 'estimates') ? 'active' : '';?>">
										<a data-toggle="tab" href="#myestimates"><?=lang('estimates')?></a>
									</li>
									<li class="<?=($tab == 'files') ? 'active' : '';?>">
										<a data-toggle="tab" href="#myfiles"><?=lang('files')?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>

                    <div class="row">
                        <div class="col-lg-12"> 
							<div class="tab-content profile-tab-content">
								
								<!-- Projects Tab -->
								<div id="myprojects" class="tab-pane fade in active">
									<div class="row">
										<?php foreach (Project::by_client($i->co_id) as $key => $p) { 
												$progress = Project::get_progress($p->project_id); 
												$open_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id,'task_progress !='=>100))->result_array(); 
											    $completed_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id,'task_progress'=>100))->result_array(); 
											    $all_tasks = $this->db->get_where('tasks',array('project'=>$p->project_id))->result_array();
										?>
										<div class="col-lg-3 col-sm-4">
											<div class="card-box project-box">
												<div class="dropdown profile-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
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
												<p class="text-muted pro-desc"><?php $para = strip_tags($p->description); echo substr($para, 0, 80).'...'; ?>
												</p>
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
												<p class="m-b-5">Progress <span class="text-success pull-right"><?php echo $p->progress; ?>%</span></p>
												<div class="progress progress-xs mb-0">
													<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $p->progress; ?>%" style="width: <?php echo $p->progress; ?>%"></div>
												</div>
											</div>
										</div>
									<?php } ?>
									</div>
								</div>
								<!-- /Projects Tab -->
								
								<!-- Contacts Tab -->
								<div id="mycontacts" class="tab-pane fade">
									<?php if ($i->individual == 0) { ?>
									<!-- Client Contacts -->
									<div class="row">
									    <div class="col-md-12">
									        <div class="panel panel-table">
									            <div class="panel-heading panel-h-p1">
													<div class="row">
														<div class="col-xs-6">
															<h3 class="panel-title m-t-6"><?=lang('contacts')?></h3>
														</div>
														<div class="col-xs-6">
															<a href="<?=base_url()?>contacts/add/<?=$i->co_id?>" class="btn btn-success pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_contact')?></a>
														</div>
													</div>
												</div>
												<div class="panel-body">
												<div class="table-responsive">
													<table id="table-client-details-1" class="table table-striped custom-table m-b-0 AppendDataTables">
														<thead>
															<tr>
																<th><?=lang('full_name')?></th>
																<th><?=lang('email')?></th>
																<th><?=lang('mobile_phone')?> </th>
																<th>Skype</th>
																<th class="col-date"><?=lang('last_login')?> </th>
																<th class="col-options no-sort text-right"><?=lang('options')?></th>
															</tr>
														</thead>
														<tbody>
														<?php foreach (Client::get_client_contacts($i->co_id) as $key => $contact) { ?>
															<tr>
																<td><a class="avatar">
																		<img src="<?php echo User::avatar_url($contact->user_id);?>" class="thumb-sm img-circle" width='30'height='30'>
																	<?=$contact->fullname?>
																	</a>
																	</td>
																<td class="text-info" ><?=$contact->email?> </td>
																<td><a href="tel:<?=User::profile_info($contact->user_id)->phone?>"><b><i class="fa fa-phone"></i></b> <?=User::profile_info($contact->user_id)->phone?></a></td>
																<td><a href="skype:<?=User::profile_info($contact->user_id)->skype?>?call"><?=User::profile_info($contact->user_id)->skype?></a></td>
																<?php
																if ($contact->last_login == '0000-00-00 00:00:00') {
																	$login_time = "-";
																}else{ $login_time = strftime(config_item('date_format')." %H:%M:%S", strtotime($contact->last_login)); } ?>
																<td><?=$login_time?> </td>
																<td class="text-right">
																	<a href="<?=base_url()?>companies/send_invoice/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-primary btn-xs" title="<?=lang('email_invoice')?>" data-toggle="ajaxModal">
																		<i class="fa fa-envelope"></i> </a>
																	<a href="<?=base_url()?>companies/make_primary/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-default btn-xs" title="<?=lang('primary_contact')?>" >
																		<i class="fa fa-chain <?php if ($i->primary_contact == $contact->user_id) { echo "text-danger"; } ?>"></i> </a>
																	<a href="<?=base_url()?>contacts/update/<?=$contact->user_id?>" class="btn btn-success btn-xs" title="<?=lang('edit')?>"  data-toggle="ajaxModal">
																		<i class="fa fa-edit"></i> </a>
																	<a href="<?=base_url()?>users/account/delete/<?=$contact->user_id?>" class="btn btn-danger btn-xs" title="<?=lang('delete')?>" data-toggle="ajaxModal">
																		<i class="fa fa-trash-o"></i> </a>
																</td>
															</tr>
														<?php  } ?>
														</tbody>
													</table>
													</div>
												</div>
									        </div>
									    </div>
									</div>
									<?php } ?>
								</div>
								<!-- /Contacts Tab -->
								
								<!-- Invoices Tab -->
								<div id="myinvoices" class="tab-pane fade">
									<h1>Invoices</h1>
									<div class="row">
									<div class="col-md-12">
										<section class="panel panel-table">
											<div class="panel-heading panel-h-p1">
												<h3 class="panel-title m-t-5 m-b-5"><?=lang('invoices')?></h3>
											</div>
											<div class="panel-body">
											<div class="table-responsive">
												<table id="table-invoices" class="table table-striped custom-table m-b-0 AppendDataTables">
													<thead>
														<tr>
															<th style="width:5px; display:none;"></th>
															<th><?=lang('invoice')?></th>
															<th class=""><?=lang('status')?></th>
															<th class="col-date"><?=lang('due_date')?></th>
															<th class="col-currency"><?=lang('amount')?></th>
															<th class="col-currency"><?=lang('due_amount')?></th>
														</tr>
													</thead>
													<tbody>
													<?php foreach (Invoice::get_client_invoices($i->co_id) as $key => $inv) { ?>
													<?php
													$status = Invoice::payment_status($inv->inv_id);
													switch ($status) {
														case 'fully_paid': $label2 = 'success';  break;
														case 'partially_paid': $label2 = 'warning'; break;
														case 'not_paid': $label2 = 'danger'; break;
													} ?>
														<tr>
															<td style="display:none;"><?=$inv->inv_id?></td>
															<td>
																<a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
																	<?=$inv->reference_no?>
																</a>
															</td>
															<td class="">
																<span class="label label-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
															  <?php if ($inv->recurring == 'Yes') { ?>
															   <span class="label label-primary m-l-5"><i class="fa fa-retweet"></i></span>
															  <?php }  ?>
															</td>
															<td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>
															<td class="col-currency">
															<?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id))?>
															</td>
															<td class="col-currency">
																<strong><?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));?></strong>
															</td>
														</tr>
													<?php } ?>
													</tbody>
												</table>
												</div>
											</div>
										</section>
									</div>
								</div>

								</div>
								<!-- /Invoices Tab -->
								
								<!-- Estimates Tab -->
								<div id="myestimates" class="tab-pane fade">
									<h1>Estimates</h1>
									<?php if ($i->individual == 0) { ?>
									<!-- Client Contacts -->
									<div class="row">
									    <div class="col-md-12">
									        <section class="panel panel-table">
									            <div class="panel-heading panel-h-p1">
													<div class="row">
														<div class="col-xs-6">
															<h3 class="panel-title m-t-6"><?=lang('estimates')?></h3>
														</div>
														<div class="col-xs-6">
															<a href="<?=base_url()?>estimates/add/<?=$i->co_id?>" class="btn btn-success pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('estimates')?></a>
														</div>
													</div>
												</div>
												<div class="panel-body">
												<div class="table-responsive">
													<table id="table-estimates" class="table table-striped custom-table m-b-0 AppendDataTables">
														<thead>
															<tr>
																<th style="width:5px; display:none;"></th>
																<th class=""><?=lang('estimate')?></th>
																<th class=""><?=lang('status')?></th>
																<th class="col-date"><?=lang('due_date')?></th>
																<th class="col-date"><?=lang('created')?></th>
																<th class="col-currency"><?=lang('amount')?></th>
															</tr>
														</thead>
														<tbody>
															<?php foreach (Estimate::estimate_by_client($i->co_id) as $key => $e) {
																$label = 'danger';
																if ($e->status == 'Pending'){ $label = "info"; }
																if($e->status == 'Accepted') { $label = "success";  }
															?>
															<tr>
																<td style="display:none;"><?=$e->est_id?></td>
																<td>
																	<a class="text-info" href="<?=base_url()?>estimates/view/<?=$e->est_id?>">
																		<?=$e->reference_no?>
																	</a>
																</td>
																<td><span class="label label-<?=$label?>"><?=lang(strtolower($e->status))?> <?php if($e->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span></td>
																<td><?=strftime(config_item('date_format'), strtotime($e->due_date))?></td>
																<td><?=strftime(config_item('date_format'), strtotime($e->date_saved))?></td>
																<td class="col-currency">
																	<strong><?=Applib::format_currency($e->currency, Estimate::due($e->est_id)); ?></strong>
																</td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
													</div>
												</div>
									        </section>
									    </div>
									</div>
									<?php } ?>
								</div>
								<!-- /Estimates Tab -->
								
								<!-- Files Tab -->
								<div id="myfiles" class="tab-pane fade">
									<div class="row">
										<div class="col-md-12">
											<div class="panel files-panel">
												<div class="panel-heading panel-h-p1">
													<div class="row">
														<div class="col-xs-6">
															<h3 class="panel-title m-t-6"><?=lang('files')?></h3>
														</div>
														<div class="col-xs-6">
															<a href="<?=base_url()?>companies/file/add/<?=$i->co_id?>" class="btn btn-success btn-sm pull-right" data-toggle="ajaxModal" data-placement="left" title="<?=lang('upload_file')?>"><i class="fa fa-plus-circle"></i> <?=lang('upload_file')?></a>	
														</div>
													</div>
												</div>
												<div class="panel-body">
													<ul class="files-list list-group no-radius">
														<?php $this->load->helper('file');
														// echo count(Client::has_files($i->co_id)); exit;
														foreach (Client::has_files($i->co_id) as $key => $f) {
														$icon = $this->applib->file_icon($f->ext);
														$real_url = base_url().'assets/uploads/'.$f->file_name;
														?>
														<li class="list-group-item">
															<div class="files-cont">
																<?php if ($f->is_image == '1') { ?>
																<?php if ($f->image_width > $f->image_height) {
																	$ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
																	$style = 'height:100%; margin-left: -'.$ratio.'%';
																} else {
																	$ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
																	$style = 'width:100%; margin-top: -'.$ratio.'%';
																}  ?>
																<div class="file-type">
																	<a href="<?=base_url()?>companies/file/<?=$f->file_id?>"><img style="<?=$style?>" src="<?=$real_url?>" /></a>
																</div>
																<?php }else{ ?>
																<div class="file-type">
																	<span class="files-icon"><i class="fa <?=$icon?> fa-lg"></i></span>
																</div>
																<?php } ?>
																<span class="file-name">
																	<a data-toggle="tooltip" data-placement="right" data-original-title="<?=$f->description?>" href="<?=base_url()?>companies/file/<?=$f->file_id?>">
																		<?=(empty($f->title) ? $f->file_name : $f->title)?>
																	</a>
																</span>
																<div class="files-action">
																	<a href="<?=base_url()?>companies/file/delete/<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i></a>
																</div>
															</div>
														</li>
														<?php } ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /Files Tab -->
								
							</div>
						</div>
					</div>
				</div>
				<!-- /Page Content -->