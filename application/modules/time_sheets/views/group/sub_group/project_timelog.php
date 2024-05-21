<div class="table-responsive">
	<table id="table-project-timelog" class="table table-striped custom-table m-b-0 AppendDataTables">
		<thead>
			<tr>   
				<th style="width:5px; display:none;"></th>
				<th class=""><?=lang('user')?></th>
				<th class="col-time"><?=lang('time_spent')?></th>
				<th class="col-time"><?=lang('date_saved')?></th>
				<?php  if(!User::is_client()){ ?>
				<th class="col-options no-sort text-right"></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$timesheet = (User::is_staff()) ? Project::timesheet($project_id,'project',User::get_id())
			: Project::timesheet($project_id,'project');
			foreach ($timesheet as $key => $t) {
			?>
			<tr>
				<td style="display:none;"><?=$t->timer_id?></td>
				<td>
					<a class="thumb-sm avatar" href="javascript:void(0);"><img src="<?php echo User::avatar_url($t->user); ?>" class="img-rounded"></a>
					<h2><a href="javascript:void(0);"><?=ucfirst(User::displayName($t->user))?></a></h2>
				</td>
				<td>
					<small class="text-muted">
						<?php if($t->status == '1'){ ?>
						<label class="label label-primary"><?=lang('on')?></label>
						<?php }else{ ?>
						<?=Applib::sec_to_hours(Project::logged_time('project',$t->timer_id));?>
						<?php } ?>
					</small>
				</td>
				<td>
					<small class="text-muted">
					<?=strftime(config_item('date_format'), strtotime($t->date_timed))?>
					</small>
				</td>
				<?php  if(!User::is_client()){ ?>
				<td class="text-right">
					<?php if($t->billable == '1') { ?>
					<a class="btn btn-xs btn-success" href="<?=base_url()?>projects/timesheet/billable/<?=$t->project?>?group=timesheets&cat=projects&id=<?=$t->timer_id?>" title="<?=lang('billable')?>" data-toggle="tooltip" data-placement="left">
					<i class="fa fa-check"></i>
					</a>
					<?php }else{ ?>
					<a class="btn btn-xs btn-danger" href="<?=base_url()?>projects/timesheet/billable/<?=$t->project?>?group=timesheets&cat=projects&id=<?=$t->timer_id?>" title="<?=lang('not_billable')?>" data-toggle="tooltip" data-placement="left"><i class="fa fa-square-o"></i></a>
					<?php } ?>
					<a class="btn btn-xs btn-info" href="<?=base_url()?>projects/timesheet/edit/<?=$t->project?>?group=timesheets&cat=projects&id=<?=$t->timer_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
					<a class="btn btn-xs btn-danger" href="<?=base_url()?>projects/timesheet/delete/<?=$t->project?>?group=timesheets&cat=projects&id=<?=$t->timer_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>