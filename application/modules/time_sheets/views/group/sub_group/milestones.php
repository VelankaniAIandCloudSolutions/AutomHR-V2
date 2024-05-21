<div class="panel panel-table">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6">
				<h3 class="panel-title">Milestones</h3>
			</div>
			<div class="col-xs-6">
				<?php  if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)){ ?>
				<a href="<?=base_url()?>projects/milestones/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm pull-right"><?=lang('add_milestone')?></a> 
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<p class="text-danger"><?php echo $this->session->flashdata('form_error');?></p>
		<div class="table-responsive">
			<table id="table-milestones" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th><?=lang('progress')?></th>
						<th><?=lang('milestone_name')?></th>
						<th class="col-date"><?=lang('start_date')?></th>
						<th class="col-date"><?=lang('due_date')?></th>
						<?php if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)){ ?>
						<th class="col-options no-sort text-right"><?=lang('options')?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach (Project::has_milestones($project_id) as $key => $m) { 
					$progress = Project::milestone_progress($m->id);
					?>
					<tr>
						<td>
							<div class="inline ">
								<div class="easypiechart text-success" data-percent="<?=$progress?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="<?=config_item('chart_color')?>" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
								<span class="small text-muted"><?=$progress?>%</span>
								</div>
							</div>
						</td>
						<td><a class="text-info" href="<?=base_url()?>projects/view/<?=$m->project?>?group=milestones&view=milestone&id=<?=$m->id?>" data-original-title="<?=$m->description?>" data-toggle="tooltip" data-placement="right" title = ""><?=$m->milestone_name?></a></td>
						<td><?=strftime(config_item('date_format'), strtotime($m->start_date))?></td>
						<td>
						<?=strftime(config_item('date_format'), strtotime($m->due_date))?>
						<?php if (time() > strtotime($m->due_date) && $progress < 100){ ?>
						<span class="badge bg-danger"><?=lang('overdue')?></span>
						<?php } ?>
						</td>
						<?php  if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)){ ?>
						<td class="text-right">
							<a class="btn btn-xs btn-success" href="<?=base_url()?>projects/milestones/edit/<?=$m->id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
							<a href="<?=base_url()?>projects/milestones/delete/<?=$m->project?>/<?=$m->id?>" data-toggle="ajaxModal" title="<?=lang('delete_milestone')?>" class="btn btn-xs btn-danger"><i class="fa fa-trash-o text-white"></i></a>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>