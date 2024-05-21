
				<!-- Page Content -->
                <div class="content container-fluid">
                	<div class="page-header">
                	<div class="row">
						<div class="col-sm-7 col-12">
							<h4 class="page-title"><?php echo lang('performance_indicator'); ?></h4>
							<ul class="breadcrumb mb-3">
								<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
								<li class="breadcrumb-item active"><?php echo lang('performance_indicator'); ?></li>
							</ul>
						</div>
				        <div class="col-sm-5 col-12 text-right m-b-20">
				           
						   <?php if(App::is_permit('menu_indicator','create'))
						   {
							  ?>
						   <a href="<?php echo site_url('appraisal/add_indicator')?>" class="btn add-btn" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?php echo lang('add_new'); ?></a>
				        <?php
						   }
						?>
						</div>
					</div>
					
				</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
									<thead>
										<tr>
											<th style="width: 30px;">#</th>
											<th><?php echo lang('designation');?></th>
											<th><?php echo lang('created_at');?></th>
											<th><?php echo lang('status');?></th>
											<?php
											if(App::is_permit('menu_indicator','write')==true|| App::is_permit('menu_indicator','delete')==true)
											{
											?>
											<th class="col-options no-sort text-right"><?php echo lang('action');?></th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php 
										$i=1;
										foreach($indicators as $indicator){
											?>
										<tr>
											<td><?php echo $i++;?></td>
											<td><?php echo ucfirst($indicator->designation);?> </td>
											<td><?php echo date('d M Y',strtotime($indicator->created_at));?></td>
											
											<td>
												<div class="dropdown action-label">
													<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
														<?php if($indicator->status==1){
															echo '<i class="fa fa-dot-circle-o text-success"></i> '.lang('active');
														}else{
															echo '<i class="fa fa-dot-circle-o text-danger"></i> '.lang('inactive');
														} ?>
													</a>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="<?php echo site_url('appraisal/indicator_status/').$indicator->id.'/1';?>"><i class="fa fa-dot-circle-o text-success"></i> <?php echo lang('active');?></a>
														<a class="dropdown-item" href="<?php echo site_url('appraisal/indicator_status/').$indicator->id.'/0';?>"><i class="fa fa-dot-circle-o text-danger"></i> <?php echo lang('inactive');?></a>
													</div>
												</div>
											</td>
											<?php
											if(App::is_permit('menu_indicator','write')==true|| App::is_permit('menu_indicator','delete')==true)
											{
											?>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

													<div class="dropdown-menu float-right">
														<?php if(App::is_permit('menu_indicator','write')){?><a class="dropdown-item" href="<?php echo site_url('appraisal/edit_indicator/').$indicator->id;?>"  data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i> <?php echo lang('edit');?></a><?php }?>
														<?php if(App::is_permit('menu_indicator','delete')){?><a class="dropdown-item" href="<?php echo site_url('appraisal/delete_indicator/').$indicator->id;?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> <?php echo lang('delete');?></a><?php }?>
													</div>

												</div>
											</td>
											<?php
											}
											?>
										</tr>
										<?php 
										}?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
              
        </div>
				<!-- /Page Content -->

				<?php //$this->load->view('modal/add_indicator');?>
				
				
			