            <div class="content container-fluid">
            	 <div class="page-header">
					<div class="row">
						<div class="col-lg-4">
							<h4 class="page-title"><?=lang('all_leads')?></h4>
							<ul class="breadcrumb">
				                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				                <li class="breadcrumb-item">CRM</li>
				                <li class="breadcrumb-item active"><?=lang('all_leads')?></li>
				            </ul>
						</div>
						<div class="col-sm-8 col-lg-8 text-right m-b-20">
							<?php
							if(App::is_permit('menu_business_leads','create'))
							{
								if(($this->session->userdata('role_id') == 1) || ( ($this->session->userdata('user_type_name') == 'company_admin'))){ 
							?>
							<a class="btn add-btn text-white" data-toggle="modal" data-target="#add_lead" title="" data-placement="bottom" data-original-title="Add Lead">
										<i class="fa fa-plus"></i> Add Lead			</a>
							<?php
								}
							}
							?>	
						</div>
					</div>
				</div>
					<?php //$this->load->view('sub_menus');?>				
					
			
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">

								<table class="table table-hover table-striped custom-table m-b-0 AppendDataTables">

									<thead>
										<tr>
											<th>#</th>
											<th>Lead Name</th>
											<th>Project</th>
											<th>Amount</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Status</th>
											<th>Created</th>
											<?php
											if(App::is_permit('menu_business_leads','write')==true||App::is_permit('menu_business_leads','delete'))
											{
											?>
											<th class="text-center no-sort">Action</th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php 											
											// $all_leads = $this->db->get_where('users',array('role_id'=>3,'is_teamlead'=>'yes','activated'=>1,'banned'=>0))->result_array(); 
										$i = 1;
										if(isset($all_leads) && !empty($all_leads)){
											foreach($all_leads as $leads){		
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo ucfirst($leads['name']); ?></td>
											<td><?php echo $leads['project_name']; ?></td>
											<td><?php echo $leads['project_amount']; ?></td>
											<td><?php echo $leads['email']; ?></td>
											<td><?php echo $leads['phone_no']; ?></td>
											
											
											<td><span class="badge bg-inverse-warning"><?php echo $leads['task_board_name']; ?></span></td>
											<td><?php echo date('d-M-Y',strtotime($leads['created'])); ?></td>
											<?php
											if(App::is_permit('menu_business_leads','write')==true||App::is_permit('menu_business_leads','delete'))
											{
											?>
											<td class="text-center">
												<div class="dropdown">
													<a data-toggle="dropdown" class="action-icon" href="#" aria-expanded="true"><i class="fa fa-ellipsis-v"></i></a>
													<div class="dropdown-menu float-right">											
														   
															<?php
															if(App::is_permit('menu_business_leads','write'))
															{
															?>
															<a class="dropdown-item" href="<?php echo base_url(); ?>crm/edit_lead/<?php echo $leads['id']; ?>" data-toggle="ajaxModal"><i class="fa fa-edit m-r-5"></i>Edit Lead</a>
														<?php
															}
															if(App::is_permit('menu_business_leads','delete'))
															{
															?>
															<a class="dropdown-item" href="<?php echo base_url(); ?>crm/delete_lead/<?php echo $leads['id']; ?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete Lead</a>
														<?php
															}
															?>
												</div>
												</div>
											</td>
											<?php
											}
											?>
										</tr>
										<?php $i++; }  }else{ ?>
										<th><td colspan="8">No Records Found</td></th>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
                </div>
</div>
                 <!-- Add lead Modal -->
            <div class="modal custom-modal fade" id="add_lead" role="dialog" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Lead</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="<?php echo base_url(); ?>crm/add_lead" id="AddLeadForm" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                         <input class="form-control" type="text" name="name" id="lead_name">
                                    </div>
									<div class="form-group">
										<label><?=lang('branch')?> <span class="text-danger">*</span></label>
										<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
											<option value="">Select</option>
											<?php if(!empty($branches))	{
												foreach ($branches as $branch1){ ?>
													<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
                                    <div class="form-group">
                                        <label>Project Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="project_name" id="project_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="project_amount" id="project_amount">
                                    </div>
   		                          	<div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" id="email">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Number <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone_no" id="phone_no">
                                    </div>
                                     <div class="form-group">
                                        <label>Image </label>
                                        <input class="form-control" type="file" name="avatar" id="file">
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block">Status</label>
                                        <div class="status-toggle">
                                            <input type="checkbox" id="lead_status" name="status" class="check" value="1">
                                            <label for="lead_status" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" id="submit_lead_form" >Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Add Lead Modal -->
				