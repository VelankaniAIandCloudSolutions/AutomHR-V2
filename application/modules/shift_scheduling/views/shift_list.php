<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); ?>
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title text-dark"><?php echo lang('shift_list');?></h4>
				<ul class="breadcrumb">
					<li class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></li>
					<li class="breadcrumb-item active"> <?php echo lang('shift_list');?></li>
				</ul>
			<!-- <ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li><a href="#">Home</a></li>
				<li><a href="#">Employees</a></li>

				<li><a href="#">Shift Schedule</a></li>
				<li class="active">Daily Schedule</li>
			</ul> -->
		</div>
		<div class="col-sm-4  text-right m-b-20">     
	          <a class="btn add-btn" href="<?=base_url()?>shift_scheduling"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
      	</div>
	</div>
	</div>
	
	
		<div class="row align-items-center">
			<div class="col-sm-5">
				<!-- <h4 class="page-title text-dark"><?php echo lang('shift_list');?></h4>
				<ul class="breadcrumb">
					<li class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></li>
					<li class="breadcrumb-item active"> <?php echo lang('shift_list');?></li>
				</ul> -->
			</div>
			<div class="col-sm-7 text-right">
				<?php if(App::is_permit('menu_shift_scheduling','create')){?><a href="<?php echo base_url(); ?>shift_scheduling/add_schedule" class="btn add-btn mb-3"><?php echo lang('assign_shifts');?></a><?php }?>
				<?php if(App::is_permit('menu_shift_scheduling','create')){?><a href="<?php echo base_url(); ?>shift_scheduling/add_shift" class="btn add-btn m-r-5 mb-3"><?php echo lang('add_shift');?></a><?php }?>				
			</div>
		</div>
		<!-- /Page Title -->
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped datatable custom-table m-b-0 text-capitalize" id="shifts">
						<thead>
							<tr>
								<th>#</th>							
								<th><?php echo lang('shift_name');?></th>						
								<!-- <th><?php echo lang('rotary_group');?></th>	 -->				
								<!-- <th><?php echo lang('min_start_time');?></th> -->	
								<th><?php echo lang('start_time');?></th>				
							<!-- 	<th><?php echo lang('max_start_time');?></th>	 -->				
								<!-- <th><?php echo lang('min_end_time');?></th>	 -->					
								<th><?php echo lang('end_time');?></th>						
								<!-- <th><?php echo lang('max_end_time');?></th>	 -->					
								<th><?php echo lang('break_time');?></th>						
								<th><?php echo lang('note');?></th>						
								<!-- <th><?php echo lang('status');?></th>						 -->
								<th><?php echo lang('actions');?></th>						
								
							</tr>
						</thead>
						<tbody>
						<?php 
						if (count($shifts) > 0) {
							$i=1;
							foreach ($shifts as $shift) { 
								// $rotary_schedule_group = $this->db->get_where('rotary_schedule_group',array('id'=>$shift['group_id']))->row_array();  
								?>
								<tr style="background-color: <?php echo $shift['color'];?>">
								<td><?php echo $i ;?></td>
								<td><?php echo ucfirst($shift['shift_name']);?></td>
								<!-- <td><?php echo !empty($rotary_schedule_group)?$rotary_schedule_group['group_name']:"-";?></td> -->
								<!-- <td><?php echo date('h:i:s a', strtotime($shift['min_start_time']));?></td> -->
								<td><?php echo date('h:i:s a', strtotime($shift['start_time']));?></td>
								<!-- <td><?php echo date('h:i:s a', strtotime($shift['max_start_time']));?></td> -->
								<!-- <td><?php echo date('h:i:s a', strtotime($shift['min_end_time']));?></td> -->
								<td><?php echo date('h:i:s a', strtotime($shift['end_time']));?></td>
								<!-- <td><?php echo date('h:i:s a', strtotime($shift['max_end_time']));?></td> -->
								<td><?php echo $shift['break_time'].' mins';?></td>
								<td><?php echo $shift['tag'];?></td>
								<!-- <td><div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
											<i class="fa fa-dot-circle-o text-<?php echo ($shift['published'] == 1)?'success':'danger';?>"></i> <?php echo ($shift['published'] == 1)?'Active':'In-active';?>
										</a>
									</div>
								</td> -->
								<!-- <td><?php echo ($shift['published'] == 1)?'Active':'In-active';?></td> -->
								<td class="text-right">									
			                       	<div class="dropdown dropdown-action">
								<a data-toggle="dropdown" class="action-icon" href="#">
									<i class="material-icons">more_vert</i>
								</a>
			                          <div class="dropdown-menu float-right">
			                            <?php if (User::is_admin()) { ?>

			                            <a class="dropdown-item" href="<?=base_url()?>shift_scheduling/edit_shift/<?=$shift['id']?>"><i class="fa fa-pencil m-r-5"></i> <?=lang('edit_shift')?></a>
			                           <a class="dropdown-item" href="<?=base_url()?>shift_scheduling/delete_shift/<?=$shift['id']?>" data-toggle="ajaxModal" title="<?=lang('delete_shift')?>"><i class="fa fa-trash-o m-r-5"></i> <?=lang('delete_shift')?></a>
			                                
			                            <?php } ?>

			                          </div>
			                        </div>
								</td>
							</tr>
							<?php $i++; } 
						} else{ ?>
							<tr>
								<td colspan="11"><?php echo lang('no_records_found');?></td>
							</tr>
						<?php } ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	
</div>