<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<h4 class="page-title"><?=lang('templates')?></h4>
		</div>
	</div>
	<div class="row row-eq-height">
		<!-- Project Tasks -->
		<div class="col-lg-6 row-eq-height">
			<div class="panel panel-white panel-eq-dash">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="panel-title m-l-10"><?=lang('project_tasks')?></h3>
						</div>
						<div class="col-xs-6">
							<a href="<?=base_url()?>items/save_task" class="btn btn-xs btn-success pull-right m-r-10" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_task')?></a>
						</div>
					</div>
				</div>
				<div class="table-responsive panel-body">
					<table id="table-templates-1" class="table table-striped table-bordered AppendDataTables">
						<thead>
							<tr>
								<th><?=lang('task_name')?></th>
								<th><?=lang('visible')?> </th>
								<th><?=lang('estimated_hours')?> </th>
								<th class="col-options no-sort text-right"><?=lang('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($project_tasks as $key => $task) { if($task->branch_id == $this->session->userdata('branch_id')){ ?>
							<tr>
								<td>
									<a class="text-muted" href="#" data-original-title="<?=$task->task_desc?>" data-toggle="tooltip" data-placement="right"><?=$task->task_name?></a>
								</td>
								<td><?=$task->visible?></td>
								<td><strong><?=$task->estimate_hours?> <?=lang('hours')?></strong></td>
								<td class="text-right">
									<a href="<?=base_url()?>items/edit_task/<?=$task->template_id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
										<i class="fa fa-edit"></i>
									</a>
									<a href="<?=base_url()?>items/delete_task/<?=$task->template_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
										<i class="fa fa-trash-o"></i>
									</a>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- End Project Tasks -->
		<!-- Invoice Items -->
		<div class="col-lg-6 row-eq-height">
			<div class="panel panel-white panel-eq-dash">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-6">
							<h3 class="panel-title m-l-10"><?=lang('invoice_items')?> </h3>
						</div>
						<div class="col-xs-6">
							<a href="<?=base_url()?>items/add_item" class="btn btn-xs btn-success pull-right m-r-10" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('new_item')?></a>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table id="table-templates-2" class="table table-striped table-bordered AppendDataTables">
							<thead>
								<tr>
									<th><?=lang('item_name')?></th>
									<th><?=lang('unit_price')?> </th>
									<th><?=lang('qty')?> </th>
									<th class="col-options no-sort text-right"><?=lang('action')?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($invoice_items as $key => $item) { if($item->branch_id == $this->session->userdata('branch_id')){ ?>
								<tr>
									<td>
										<a class="text-muted" href="#" data-original-title="<?=$item->item_desc?>" data-toggle="tooltip" data-placement="left" title = "">
											<?=$item->item_name?>
										</a>
									</td>
									<td><?=Applib::format_currency(config_item('default_currency'), $item->unit_cost)?></td>
									<td><?=$item->quantity?></td>
									<td class="text-right">
										<a href="<?=base_url()?>items/edit_item/<?=$item->item_id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
											<i class="fa fa-edit"></i>
										</a>
										<a href="<?=base_url()?>items/delete_item/<?=$item->item_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
											<i class="fa fa-trash-o"></i>
										</a>
									</td>
								</tr>
								<?php  } } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- End Invoice Items -->
	</div>
</div>