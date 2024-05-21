<!-- Client Projects -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-table">
			<div class="panel-heading panel-h-p1">
				<h3 class="panel-title m-t-5 m-b-5"><?=lang('projects')?></h3>
			</div>
			<div class="panel-body">
			<div class="table-responsive">
				<table id="table-projects" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th style="width:5px; display:none;"></th>
							<th><?=lang('project_title')?></th>
							<th><?=lang('status')?></th>
							<th><?=lang('team_members')?></th>
							<th><?=lang('amount')?></th>
							<th><?=lang('progress')?> </th>
						</tr>
					</thead>
					<tbody>
						<?php foreach (Project::by_client($company) as $key => $project) { ?>
						<?php $progress = Project::get_progress($project->project_id); ?>
						<tr>
							<td style="display:none;"><?=$project->project_id?></td>
							<td>
								<a class="text-info" href="<?=base_url()?>projects/view/<?=$project->project_id?>">
								<?=$project->project_title?>
								</a>
							</td>
							<?php
							  switch ($project->status) {
								  case 'Active': $label = 'success'; break;
								  case 'On Hold': $label = 'warning'; break;
								  case 'Done': $label = 'default'; break;
							  }
							?>
							<td>
								<span class="label label-<?=$label?>"><?=lang(str_replace(" ","_",strtolower($project->status)))?></span>
							</td>
							<td class="text-muted">
								<ul class="team-members">
									<?php foreach (Project::project_team($project->project_id) as $user) { ?>
									<li>
										<a href="javascript:void(0);">
											<img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="img-circle" data-toggle="tooltip" data-title="<?=User::displayName($user->assigned_user); ?>" data-placement="top">
										</a>
									</li>
									<?php } ?>
								</ul>
							</td>
							<td class="col-currency">
								<?php $pcur = ($project->client > 0) ? $cur->code : $project->currency; ?>
								<strong><?=Applib::format_currency($pcur, Project::sub_total($project->project_id))?></strong>
								<small class="text-muted" data-toggle="tooltip" data-title="<?=lang('expenses')?>">
									(<?=Applib::format_currency($pcur, Project::total_expense($project->project_id))?>)
								</small>
							</td>
							<td>
								<div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 100%; margin-right: 5px">
								  <div class="progress-bar progress-bar-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress?>%;" data-toggle="tooltip" data-original-title="<?=$progress?>%"></div>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			</div>
		</div>
	</div>
</div>