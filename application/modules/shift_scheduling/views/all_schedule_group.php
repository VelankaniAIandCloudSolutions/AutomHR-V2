<div class="content">
	<div class="page-header">
		<div class="row align-items-center">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('rotary_schedule_groups')?></h4>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
              <a class="btn back-btn" href="<?=base_url()?>shift_scheduling"><i class="fa fa-chevron-left"></i> Back</a>
          </div>
	</div>	
</div>
          <div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12 text-right m-b-20">
				<a class="btn add-btn" href="<?=base_url()?>shift_scheduling/add_schedule_group" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?php echo lang('add_schedule_group'); ?></a>
			</div>
		</div>
		<div class="row">
			<!-- Project Tasks -->
			<div class="col-lg-12">
					<div class="table-responsive">
						<table id="table-templates-1" class="table table-striped table-bordered custom-table m-b-0 AppendDataTables">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo lang('group_name'); ?></th>
									<th><?php echo lang('shift_name'); ?></th>
									<th class="col-options no-sort text-right"><?=lang('action')?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$categories = $this -> db -> get('rotary_schedule_group') -> result();
								if (!empty($categories)) {
									$j =1;
									foreach ($categories as $key => $d) { ?>
								<tr>
									<td>
										<?php echo $j; ?>
									</td>
									<td>
										<?=$d->group_name?>
									</td>
									<td>
										<?php $all_des = $this->db->get_where('shifts',array('group_id'=>$d->id))->result_array(); 
											if(count($all_des) != 0)
											{	$k = 1;
												foreach($all_des as $des){
										?>
											<div><?php echo $k.'. '.$des['shift_name']; ?></div>
										<?php $k++; } }else{ ?>
											<div>-</div>
										<?php } ?>

									</td>
									<td class="text-right">										
										<a href="<?=base_url()?>shift_scheduling/edit_add_schedule_group/<?=$d->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
											<i class="fa fa-edit"></i>
										</a>
									
									</td>
								</tr>
								<?php $j++; } } else{ ?>
									<tr><td colspan="5">No Results</td></tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
			</div>
			<!-- End Project Tasks -->
		</div>
	</div>
</div>
</div>
</div>