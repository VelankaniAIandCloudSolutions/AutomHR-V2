<aside class="bg-white">
	<ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified" role="tablist">
		<li class="active">
			<a class="active" href="#projects" data-toggle="tab"><?= lang('recent_projects') ?></a>
		</li>
		<li class=""><a href="#bugs" data-toggle="tab"><?= lang('recent_bugs') ?></a></li>
		<li class=""><a href="#invoices" data-toggle="tab"><?= lang('upcoming_invoices') ?></a></li>
	</ul>
	<section class="scrollable">
		<div class="tab-content">
			<div class="tab-pane active" id="projects">
				<table class="table table-striped custom-table m-b-0 clickable">
					<thead>
						<tr>
							<th><?= lang('project_name') ?> </th>
							<th><?= lang('team_members') ?> </th>
							<th><?= lang('client_name') ?> </th>
							<th><?= lang('progress') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$projects = Project::get_uncomplete(10);
						if (count($projects) > 0) {
						foreach ($projects as $key => $project) {
						$progress = round(Project::get_progress($project->project_id),2);
						?>
						<tr>
							<td>
								<a href="<?=base_url()?>projects/view/<?=$project->project_id?>">
									<?= $project->project_title ?>
								</a>
							</td>
							<td>
								<div class="team-members">
									<?php foreach (Project::project_team($project->project_id) as $user) { ?>
									<a class="thumb-xs pull-left">
										<img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="img-circle" data-toggle="tooltip" data-title="<?=User::displayName($user->assigned_user); ?>" data-placement="top">
									</a>
									<?php } ?>
								</div>
							</td>
							<td>
								<a href="<?=base_url()?>companies/view/<?=$project->client?>">
									<?php echo Client::view_by_id($project->client)->company_name; ?>
								</a>
							</td>
							<td><?php $bg = ($progress >= 100) ? 'success' : 'danger'; ?>
								<div class="progress progress-xxs progress-striped m-b-0 active">
									<div class="progress-bar progress-bar-<?=$bg?>" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%"></div>
								</div>
							</td>
						</tr>
						<?php }
						} else { ?>
							<tr>
								<td class="text-center text-muted" colspan="4"><?=lang('nothing_to_display')?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="bugs">
				<table class="table table-striped custom-table m-b-0 text-sm">
					<thead>
						<tr>
							<th><?= lang('status') ?></th>
							<th><?= lang('issue_title') ?> </th>
							<th><?= lang('priority') ?></th>
							<th><?= lang('assigned_to') ?></th>
							<th class="col-options no-sort col-md-1"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$bugs = Project::get_bugs(5);
						if (!empty($bugs)) {
						foreach ($bugs as $key => $bug) {
						?>
						<tr>
							<?php $status = ($bug->bug_status == 'Resolved') ? 'success' : 'danger'; ?>
							<td>
								<span class="label label-<?=$status?>">
									<?=$bug->bug_status;?>
								</span>
							</td>
							<td>
								<a href="<?=base_url()?>projects/view/<?=$bug->project?>?group=bugs&view=bug&id=<?=$bug->bug_id?>">
									<?= $bug->issue_title ?>
								</a>
							</td>
							<td>
								<span class="label label-success">
									<?=lang($bug->priority)?>
								</span>
							</td>
							<td>
								<a class="pull-left thumb-xs avatar text-info" data-toggle="tooltip" data-original-title="<?=User::displayName($bug->assigned_to)?>" data-toggle="tooltip" data-placement="right" title = "">
									<img src="<?=User::avatar_url($bug->assigned_to); ?>" class="img-rounded">
								</a>
							</td>
							<td>
								<a href="<?=base_url()?>projects/view/<?=$bug->project?>?group=bugs&view=bug&id=<?=$bug->bug_id?>">
									<i class="fa fa-plus-circle text-info fa-lg"></i>
								</a>
							</td>
						</tr>
						<?php }
						} else { ?>
						<tr>
							<td class="text-center text-muted" colspan="4"><?= lang('nothing_to_display') ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="invoices">
				<table class="table table-striped custom-table m-b-0 text-sm">
					<thead>
						<tr>
							<th class="col-md-1"><?= lang('reference_no') ?></th>
							<th class="col-md-3"><?= lang('client_name') ?> </th>
							<th class="col-md-2"><?= lang('due_date') ?></th>
							<th class="col-md-2"><?= lang('due_amount') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$invoices = Invoice::get_invoices(10,'Unpaid');

						if (count($invoices) > 0) {
						foreach ($invoices as $key => $invoice) {
						if(strtotime($invoice->due_date) < time()) unset($invoices[$key]);
						?>
						<tr>
							<td>
								<a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>">
									<span class="label label-primary"><?=$invoice->reference_no;?></span>
								</a>
							</td>
							<td>
								<?php echo Client::view_by_id($invoice->client)->company_name;?>
							</td>
							<td>
								<span class="label label-danger"><?=strftime(config_item('date_format'), strtotime($invoice->due_date))?></span>
							</td>
							<td class="col-currency">
								<?php echo Applib::format_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id)); ?>
							</td>
						</tr>
						<?php }
						} else { ?>
						<tr>
							<td class="text-center text-muted" colspan="4"><?= lang('nothing_to_display') ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</aside>