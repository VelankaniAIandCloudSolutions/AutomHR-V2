<div class="content">
	<?php
	$user = User::get_id();
	$cur = App::currencies(config_item('default_currency'));
	$user_company = User::profile_info($user)->company;
	$client_expenses = 0;
	$client_paid = 0;

	if ($user_company > 0) {
	  $client_expenses = Client::total_expenses($user_company);
	  $client_paid = Client::amount_paid($user_company);

		$client_outstanding = Client::due_amount($user_company);

		$client_payments = Client::amount_paid($user_company);

		$client_payable = Client::payable($user_company);

		if ($client_payable > 0 && $client_payments > 0) {
			$perc_paid = round(($client_payments/$client_payable) * 100,1);
			$perc_paid = ($perc_paid > 100) ? '100' : $perc_paid;
		}else{
			$perc_paid = 0;
		}
		$total_projects = App::counter('projects',array('client'=>$user_company));
		$complete_projects =App::counter('projects',array('client'=>$user_company,'progress >='=>'100'));
		if ($total_projects > 0) {
			$perc_complete = round(($complete_projects/$total_projects) *100,1);
			$perc_open = 100 - $perc_complete;
		}else{
			$perc_complete = 0;
			$perc_open = 0;
		}
		?>
	<?php } else {
		$client_outstanding = $perc_paid = $client_payable = $total_projects = $perc_complete = 0;
		$perc_open = 0;
	}
	?>
	<div class="m-b-md">
		<?php if($client_outstanding > 0){ ?>
		<div class="alert alert-info hidden-print">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<?=lang('amount_displayed_in_your_cur')?> &raquo; <?=$cur->code;?></strong>
		</div>
		<?php } ?>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-3">
			<div class="dash-widget card-box">
				<a class="clear" href="<?= base_url() ?>invoices">
					<span class="dash-widget-icon"><i class="fa fa-paper-plane"></i></span>
					<div class="dash-widget-info">
						<h3><?=Applib::format_currency($cur->code, $client_outstanding)?></h3>
						<span><?= lang('outstanding') ?></span>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="dash-widget card-box">
				<a class="clear" href="<?= base_url() ?>expenses">
					<span class="dash-widget-icon"><i class="fa fa-balance-scale"></i></span>
					<div class="dash-widget-info">
						<h3><?=Applib::format_currency($cur->code, $client_expenses)?></h3>
						<span><?= lang('expenses') ?></span>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="dash-widget card-box">
				<a class="clear" href="<?= base_url() ?>payments">
					<span class="dash-widget-icon"><i class="fa fa-bank"></i></span>
					<div class="dash-widget-info">
						<h3><?=Applib::format_currency($cur->code, $client_paid)?></h3>
						<span><?= lang('paid_amount') ?></span>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="dash-widget card-box">
				<a class="clear" href="<?= base_url() ?>tickets">
					<span class="dash-widget-icon"><i class="fa fa-ticket"></i></span>
					<div class="dash-widget-info">
						<h3><?=App::counter('tickets',array('reporter'=>$user,'status !='=>'closed'));?></h3>
						<span><?= lang('tickets') ?></span>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-table">
				<div class="panel-heading">
					<h3 class="panel-title"><?=lang('recent_projects')?></h3>
				</div>
				<div class="panel-body">
					<table class="table table-striped custom-table m-b-0">
						<thead>
							<tr>
								<th class=""><?=lang('project_name')?> </th>
								<th class="hidden-xs"><?=lang('progress')?></th>
								<th class="col-currency"><?=lang('project_cost')?></th>
								<th class="col-options no-sort text-right"><?=lang('options')?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach (Welcome::recent_projects($user_company) as $key => $project) { ?>
							<tr>
								<?php
								$project_cost = Project::sub_total($project->project_id);
								$progress = Project::get_progress($project->project_id);
								?>
								<td><?=$project->project_title?> </td>
								<td class="hidden-xs">
									<?php $bg = ($progress >= 100) ? 'success' : 'danger'; ?>
									<div class="progress progress-xs progress-striped m-b-0 active">
										<div class="progress-bar progress-bar-<?=$bg?>" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%">
										</div>
									</div>
								</td>
								<td class="col-currency"><?=Applib::format_currency($cur->code, $project_cost)?></td>
								<td class="text-right">
									<a class="btn btn-success btn-xs" href="<?=base_url()?>projects/view/<?=$project->project_id?>">
										<i class="fa fa-folder-open-o text"></i> <?=lang('view')?>
									</a>
								</td>
							</tr>
						<?php } ?>
						<?php if(count(Welcome::recent_projects($user_company)) <= 0){ ?>
							<tr>
								<td colspan="4" class="text-center text-muted"><?=lang('nothing_to_display')?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="panel-footer bg-white no-padder">
					<div class="row text-center no-gutter">
						<div class="col-xs-3 b-r b-light">
							<span class="h4 font-bold m-t block">
							<?php echo App::counter('bugs',array('reporter'=>$user))?>
							</span>
							<small class="text-muted m-b block"><?=lang('reported_bugs')?></small>
						</div>
						<div class="col-xs-3 b-r b-light">
							<span class="h4 font-bold m-t block">
								<?php echo App::counter('projects',array('client'=>$user_company,'progress >='=>'100')); ?>
							</span>
							<small class="text-muted m-b block"><?=lang('complete_projects')?></small>
						</div>
						<div class="col-xs-3 b-r b-light">
							<span class="h4 font-bold m-t block">
								<?php echo App::counter('messages',array('user_to'=>$user,'status'=>'Unread'))?>
							</span>
							<small class="text-muted m-b block"><?=lang('unread_messages')?></small>
						</div>
						<?php
						$ticketnumber = App::counter('tickets',array('reporter'=>$user, 'status !='=>'closed'));
						?>
						<div class="col-xs-3">
							<span class="h4 font-bold m-t block">
								<?=$ticketnumber?>
							</span>
							<small class="text-muted m-b block"><?=lang('tickets')?></small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-white">
				<div class="panel-heading">
					<h3 class="panel-title"><?=lang('payments')?></h3>
				</div>
				<div class="panel-body text-center">
					<h4>
						<small> <?=lang('paid_amount')?> : </small> <?php echo Applib::format_currency($cur->code, Client::amount_paid($user_company)); ?>
					</h4>
					<small class="text-muted block">
						<?=lang('outstanding')?> : <?=Applib::format_currency($cur->code, $client_outstanding)?>
					</small>
					<div class="inline">
						<div class="easypiechart" data-percent="<?=$perc_paid?>" data-line-width="16" data-loop="false" data-size="188">
							<span class="h2 step"><?=$perc_paid?></span>%
							<div class="easypie-text"><?=lang('paid')?>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<small><?=lang('invoice_amount')?>: <strong><?=Applib::format_currency($cur->code, $client_payable)?></strong></small>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<!-- Start Charts -->
			<div class="row">
				<div class="col-lg-6">
					<div class="panel panel-white">
						<div class="panel-heading">
							<h3 class="panel-title"><?=lang('my_projects')?></h3>
						</div>
						<div class="panel-body text-center">
							<h4><small></small><?=$total_projects?><small> <?=lang('projects')?></small></h4>
							<small class="text-muted block"><?=lang('complete_projects')?> - <strong><?=$perc_complete?>%</strong></small>
							<div class="sparkline inline" data-type="pie" data-height="150" data-slice-colors="['#99c7ce','#e1e1e1']">
								<?=$perc_complete?>,<?=$perc_open?>
							</div>
							<hr>
							<div class="text-xs">
								<i class="fa fa-circle text-info"></i> <?=lang('closed')?> - <?=$perc_complete?>%
								<i class="fa fa-circle text-muted"></i> <?=lang('open')?> - <?=$perc_open?>%
							</div>
						</div>
						<div class="panel-footer">
							<small><?=lang('projects_completion')?></small>
						</div>
					</div>
				</div>
				<!-- Start Tickets -->
				<div class="col-lg-6">
					<div class="panel panel-white">
						<div class="panel-heading">
							<h3 class="panel-title"><?=lang('recent_tickets')?></h3>
						</div>
						<div class="panel-body">
							<div class="list-group no-radius m-b-0">
								<?php
								$tickets = Ticket::by_where(array('reporter'=>$user)); // Get 7 tickets
								foreach ($tickets as $key => $ticket) {
									if($ticket->status == 'open'){ $badge = 'danger'; }elseif($ticket->status == 'closed'){ $badge = 'success'; }else{ $badge = 'dark'; }
									?>
									<a href="<?=base_url()?>tickets/view/<?=$ticket->id?>" data-original-title="<?=$ticket->subject?>" data-toggle="tooltip" data-placement="top" title = "" class="list-group-item">
										<?=$ticket->ticket_code?> - <small class="text-muted"><?=lang('priority')?>: <?=$ticket->priority?> <span class="badge bg-<?=$badge?> pull-right"><?=$ticket->status?></span></small>
									</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<!-- End Tickets -->
			</div>
		</div>
		<!-- Recent activities -->
		<div class="col-md-4">
			<div class="panel activity-panel">
				<div class="panel-heading">
					<h3 class="panel-title"><?= lang('recent_activities') ?></h3>
				</div>
				<div class="panel-body">
					<div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
						<ul class="activity-list">
							<?php foreach (Welcome::recent_activities($user) as $key => $activity) { ?>
							<li>
								<div class="activity-user">
									<a href="javascript:void(0);" class="avatar">
										<img class="img-circle" src="<?php echo User::avatar_url($activity->user); ?>">
									</a>
								</div>
								<div class="activity-content">
									<div class="timeline-content">
										<a href="javascript:void(0);" class="name"><?php echo User::displayName($activity->user); ?></a>
										<?php
											if (lang($activity->activity) != '') {
												if (!empty($activity->value1)) {
													if (!empty($activity->value2)) {
														echo sprintf(lang($activity->activity), '<a href="javascript:void(0);">' . $activity->value1 . '</a>', '<a href="javascript:void(0);">' . $activity->value2 . '</a>');
													} else {
														echo sprintf(lang($activity->activity), '<a href="javascript:void(0);">' . $activity->value1 . '</a>');
													}
												} else {
													echo lang($activity->activity);
												}
											} else {
												echo $activity->activity;
											}
										?>
										<span class="time"><?php echo Applib::time_elapsed_string(strtotime($activity->activity_date));?></span>
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
</div>