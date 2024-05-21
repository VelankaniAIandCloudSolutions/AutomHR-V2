<link rel="stylesheet" href="<?= base_url() ?>assets/css/tasks.css" type="text/css"/>
<div class="row">
<div class="col-md-12">
    <div class="panel panel-body">
        <?php
        $all_tasks = App::counter('tasks', array('project' => $project_id));
        $done_tasks = App::counter('tasks', array('project' => $project_id, 'task_progress >=' => '100'));
        $in_progress = App::counter('tasks', array('project' => $project_id, 'task_progress <' => '100'));
        $perc_done = $perc_progress = 0;
        if ($all_tasks > 0) {
            $perc_done = ($done_tasks / $all_tasks) * 100;
            $perc_progress = ($in_progress / $all_tasks) * 100;
        }
        $progress = Project::get_progress($project_id);
        $project_hours = Project::total_hours($project_id);
        $project_cost = Project::sub_total($project_id);
        $info = Project::by_id($project_id);
        $currency = ($info->client > 0) ? Client::get_currency_code($info->client)->code : $info->currency;
        if ($info->client <= 0) {
            ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <i class="fa fa-info-sign"></i>
                No Client attached to this project.
            </div>
        <?php
        } ?>
        <div class="m-b-5">
            <strong><?= lang('progress') ?></strong>
            <div class="pull-right">
                <strong class="<?= ($progress < 100) ? 'text-danger' : 'text-success'; ?>"><?= $progress ?>
                    %</strong>
            </div>
        </div>
        <div class="progress-xs mb-0 <?= ($progress != '100') ? 'progress-striped active' : ''; ?> inline-block progress">
            <div class="progress-bar progress-bar-<?= config_item('theme_color') ?> " data-toggle="tooltip"
                 data-original-title="<?= $progress ?>%" style="width: <?= $progress ?>%"></div>
        </div>
    </div>
	<!-- End ROW 1 -->
	<div class="">
	<div class="row proj-summary-band">
		<?php if (User::is_admin()) : ?>
		<div class="col-lg-3 col-md-6 col-sm-6 text-center">
			<div class="card-box">
				<h3><?=lang('project_cost')?></h3>
				<h4 class="cursor-pointer text-open small"><?php echo ($info->fixed_rate == 'No') ? $info->hourly_rate.'/'.lang('hour') : lang('fixed_rate'); ?>
				</h4>
				<h4>
					<?php echo Applib::format_currency($currency, $project_cost); ?>
				</h4>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 text-center">
			<div class="card-box">
				<h3><?= lang('billable_amount') ?></h3>
				<h4 class="cursor-pointer text-open small">+ <?php echo Applib::format_currency($currency, Project::total_expense($project_id))?> <?=lang('expenses')?></h4>
				<h4><?=Applib::format_currency($currency, Project::total_expense($project_id) + $project_cost)?></h4>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 text-center">
			<div class="card-box">
				<h3><?=lang('billable')?></h3>
				<h4 class="cursor-pointer text-success small"><?=Applib::gm_sec($project_hours * 3600)?></h4>
				<h4><?=$project_hours?> <?=lang('hours')?></h4>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 text-center">
			<div class="card-box">
				<h3><?=lang('unbillable')?></h3>
				<h4 class="cursor-pointer text-danger small"><?=Applib::gm_sec(Project::unbillable($project_id) * 3600)?></h4>
				<h4><?php echo Project::unbillable($project_id); ?> <?=lang('hours')?></h4>
			</div>
		</div>
		<?php elseif (User::is_staff()): ?>
		<div class="col-md-4 text-center">
			<div class="card-box">
				<h3><?=lang('complete_tasks')?></h3>
				<h4 class="cursor-pointer text-open small">
				<?php
				$all_tasks = Project::user_tasks(User::get_id());
				$complete = $total_tasks = 0;
				foreach ($all_tasks as $key => $v) :
					if ($v->task_progress == '100' && $v->project == $project_id) :
					  $complete += 1;
					endif;

					if ($v->project == $project_id) :
					  $total_tasks += 1;
					endif;
				endforeach; ?>

				<?php echo $complete.'/'.$total_tasks; ?>
				</h4>
				<label class="label label-success">
				  <?php echo ($total_tasks > 0) ? ($complete / $total_tasks) * 100 : '0'; ?>% <?=lang('done')?>
				</label>
			</div>
		</div>
		<div class="col-md-4 text-center">
			<div class="card-box">
				<h3><?= lang('billable') ?></h3>
				<h4 class="cursor-pointer text-open small">
				  <?php echo Applib::gm_sec(Project::staff_logged_time(User::get_id(), 1)); ?>
				</h4>
				<h4 class="small">
				  <?php echo Applib::sec_to_hours(Project::staff_logged_time(User::get_id(), 1)); ?>
				</h4>
			</div>
		</div>
		<div class="col-md-4 text-center">
			<div class="card-box">
				<h3><?=lang('unbillable')?></h3>
				<h4 class="cursor-pointer text-success small">
					<?php
					$spent = Project::time_by_user(User::get_id(), 0, $project_id);
					$total = $spent->tasks_time + $spent->projects_time;
					?>
				  <?php echo Applib::gm_sec($total); ?>
				</h4>
				<h4 class="small">
				  <?php echo Applib::sec_to_hours($total); ?>
				</h4>
			</div>
		</div>
		<?php else: ?>
		<div class="col-md-3 text-center">
			<div class="card-box">
				<h3><?=lang('project_cost')?></h3>
				<h4 class="cursor-pointer text-open small"><?php echo ($info->fixed_rate == 'No') ? $info->hourly_rate.'/'.lang('hour') : lang('fixed_rate'); ?>
				</h4>
				<h4>
					<?php echo Applib::format_currency($currency, $project_cost); ?>
				</h4>
			</div>
		</div>
		<div class="col-md-3 text-center">
			<div class="card-box">
				<h3><?= lang('billable_amount') ?></h3>
				<h4 class="cursor-pointer text-open small">+ <?php echo Applib::format_currency($currency, Project::total_expense($project_id))?> <?=lang('expenses')?></h4>
				<h4><?=Applib::format_currency($currency, Project::total_expense($project_id) + $project_cost)?></h4>
			</div>
		</div>
		<div class="col-md-3 text-center">
			<div class="card-box">
				<h3><?=lang('billable')?></h3>
				<h4 class="cursor-pointer text-success small"><?=Applib::gm_sec($project_hours * 3600)?></h4>
				<h4><?=$project_hours?> <?=lang('hours')?></h4>
			</div>
		</div>
		<div class="col-md-3 text-center">
			<div class="card-box">
				<h3><?=lang('unbillable')?></h3>
				<h4 class="cursor-pointer text-danger small"><?=Applib::gm_sec(Project::unbillable($project_id) * 3600)?></h4>
				<h4><?php echo Project::unbillable($project_id); ?> <?=lang('hours')?></h4>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="row row-eq-height">
		<div class="col-sm-6 row-eq-height">
		
			<div class="card-box card-projects">
				<h3 class="card-title"><?= lang('overview') ?></h3>
				<ul class="list-group no-radius m-b-0">
					<li class="list-group-item">
						<span class="pull-right text"><?= $info->project_title ?></span>
						<span class="text-muted"><?= lang('project_name') ?></span>
					</li>
					<?php if (User::is_admin() || User::is_client() ||
						User::perm_allowed(User::get_id(), 'view_project_clients')
					) {
						?>
						<?php if ($info->client > 0) {
							?>
					<li class="list-group-item">
						<span class="pull-right">
							<a href="<?= site_url() ?>companies/view/<?= $info->client ?>">
								<?php echo Client::view_by_id($info->client)->company_name; ?>
							</a>
						</span>
						<span class="text-muted"><?= lang('client_name') ?></span>
					</li>
						<?php
						} ?>
					<?php
					} ?>
					<li class="list-group-item">
						<span class="pull-right">
							<?= (strtotime($info->start_date) == 0 ? '' : strftime(config_item('date_format'), strtotime($info->start_date))) ?>
						</span>
						<span class="text-muted"><?= lang('start_date') ?></span>
					</li>
					<li class="list-group-item">
						<span class="pull-right">
						<?php if (valid_date($info->due_date)) {
						?>
						<?= strftime(config_item('date_format'), strtotime($info->due_date)) ?>
						<?php if (time() > strtotime($info->due_date) and $progress < 100) {
						?>
						<span class="badge bg-danger"><?= lang('overdue') ?></span>
						<?php
						} ?>
						<?php
						} else {
							echo lang('ongoing');
						} ?>
						</span>
						<span class="text-muted"><?= lang('due_date') ?></span>
					</li>
					<?php if (User::is_admin() || User::is_staff() || Project::setting('show_team_members', $project_id)) {
						?>
					<li class="list-group-item">
						<span class="pull-right">
							<small class="small">
								<a class="pull-left">
								<?php foreach (Project::project_team($project_id) as $user) {
								?>
								<img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="img-circle thumb-xs"
								data-toggle="tooltip" data-title="<?= User::displayName($user->assigned_user) ?>"
								data-placement="left">
								<?php
								} ?>
								</a>
							</small>
						</span>
						<span class="text-muted"><?= lang('team_members') ?></span>
					</li>
					<li class="list-group-item">
						<span class="pull-right">
							<small class="small">
								<a class="pull-left">
								<?php $assign_lead = $this->db->get_where('projects',array('project_id'=>$project_id))->row();
								?>
								<img src="<?php echo User::avatar_url($assign_lead->assign_lead); ?>" class="img-circle thumb-xs"
								data-toggle="tooltip" data-title="<?= User::displayName($assign_lead->assign_lead) ?>"
								data-placement="left">
								<?php
								// } ?>
								</a>
							</small>
						</span>
						<span class="text-muted">Project Lead</span>
					</li>
					<?php
					} ?>
					<?php if (User::is_admin() || User::is_client() || User::perm_allowed(User::get_id(), 'view_project_cost')) {
						?>
					<li class="list-group-item">
						<span class="pull-right">
							<strong><?= $info->estimate_hours; ?> </strong><small><?= lang('hours') ?></small>
						</span>
						<span class="text-muted"><?= lang('estimated_hours') ?></span>
					</li>
					<?php
					} ?>
					<?php if (User::is_admin() || User::is_client() || User::perm_allowed(User::get_id(), 'view_project_cost')) {
						?>
					<li class="list-group-item">
						<span class="pull-right">
							<?php
							$used_budget = null;
							if ($info->estimate_hours > 0) {
							$used_budget = round(($project_hours / $info->estimate_hours) * 100, 2);
							} ?>
							<strong class="<?= ($used_budget > 100) ? 'text-danger' : 'text-success'; ?>">
							<?= ($used_budget != null) ? $used_budget.' %' : 'N/A'; ?>
							</strong>
						</span>
						<span class="text-muted"><?= lang('used_budget') ?></span>
					</li>
					<?php
					} ?>
					<?php if (User::is_admin() || User::is_client() || User::perm_allowed(User::get_id(), 'view_project_expenses')) {
						?>
					<li class="list-group-item">
						<span class="pull-right">
							<strong>
							<?php echo Applib::format_currency($currency, Project::total_expense($project_id)) ?></strong>
							<?php if (User::is_admin() || User::perm_allowed(User::get_id(), 'add_expenses')) {
							?>
							<a href="<?= site_url() ?>expenses/create/?project=<?= $project_id ?>" data-toggle="ajaxModal"
							title="<?= lang('create_expense') ?>"
							class="btn btn-xs btn-info">
							<i class="fa fa-plus"></i></a>
							<?php
							} ?>
							<a href="<?= site_url() ?>expenses/?project=<?= $project_id ?>" data-toggle="tooltip"
							title="<?= lang('view_expenses') ?>" data-placement="left"
							class="btn btn-xs btn-success"><i class="fa fa-ellipsis-h"></i></a>
						</span>
						<span class="text-muted"><?= lang('expenses') ?></span>
					</li>
					<?php
					} ?>
				</ul>
			</div>
		
		</div>
		<!-- End details C1-->
		<div class="col-sm-6 row-eq-height">
		
			<div class="card-box card-activities">
				<h3 class="card-title"><?= lang('activities') ?></h3>
				<div class="slim-scroll" data-height="340" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
					<div id="activity">
						<ul class="list-group no-radius m-b-0">
							<?php $activities = Project::activity($project_id);
							foreach ($activities as $key => $a) {
								$module = strtolower($a->module); ?>
								<li class="list-group-item">
									<a class="thumb-sm pull-left m-r-sm">
										<img src="<?php echo User::avatar_url($a->user); ?>" class="img-rounded">
									</a>
									<small class="pull-right"><?= strftime(config_item('date_format').' %H:%M:%S', strtotime($a->activity_date)) ?></small>
									<strong class="block"><?php echo User::displayName($a->user); ?></strong>
									<span
										class="label label-<?= module_color($module) ?>"><?= lang($module) ?></span>
									<small>
										<?php
										if (lang($a->activity) != '') {
											if (!empty($a->value1)) {
												if (!empty($a->value2)) {
													echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>', '<em>'.$a->value2.'</em>');
												} else {
													echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>');
												}
											} else {
												echo lang($a->activity);
											}
										} else {
											echo $a->activity;
										} ?>
									</small>
								</li>
							<?php
							} ?>
						</ul>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	<div class="row" style="padding: 0px 2px;">
	<div class="card-box">
		<h3 class="card-title">Description</h3>
		<div class="pro-desc project_content_description"><?= nl2br_except_pre($info->description) ?></div>
	</div>
    </div>
	<div class="row row-eq-height">
		<!-- start recent tasks -->
		<?php if (User::is_admin() || User::is_staff() || Project::setting('show_project_tasks', $project_id)) { ?>
                <div class="col-sm-6 row-eq-height">
                    <div class="panel task-panel panel-white panel-eq-tasks">
                        <div class="panel-heading">
							<h3 class="panel-title"><?= lang('my_tasks') ?></h3>
						</div>
                        <div class="panel-body">
                            <div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                                <?php $tasks = Project::has_tasks($project_id);
                                    if (count($tasks) > 0) {
                                        ?>
									<div class="task-wrapper">
										<div class="task-list-container">
											<div class="task-list-body">
												<ul id="task-list" class="task-list">
													<?php foreach ($tasks as $key => $t) {
													?>
														<?php $color = 'danger'; ?>
														<?php if ($t->task_progress >= '50') {
														$color = 'warning';
													} ?>
														<?php if ($t->task_progress == '100') {
														$color = 'primary';
													} ?>
													<li class="task list-<?php echo $color; ?> <?php if ($t->task_progress >= 100) {
														echo 'task-done';
													} ?>  <?=($t->task_progress == '100') ? 'completed' : ''; ?> <?=(Project::timer_status('tasks', $t->t_id) == 'On') ? 'disabled' : ''; ?>">
														<div class="task-container">
															<?php if (!User::is_client()) {
														if (Project::is_task_team($t->t_id) || $t->assigned_to == null || User::is_admin()) {
															?>
															<span class="task-action-btn task-check task_complete">
																<label class="checkbox-custom">
																	<span class="action-circle large complete-btn">
																		<input type="checkbox" class="list-child" data-id="<?=$t->t_id?>" <?php echo ($t->task_progress == '100') ? 'checked="checked"' : ''; ?> <?php echo (Project::timer_status('tasks', $t->t_id) == 'On') ? 'disabled="disabled"' : ''; ?>>
																		<i class="material-icons <?=($t->task_progress == '100') ? 'checked' : ''; ?> <?=(Project::timer_status('tasks', $t->t_id) == 'On') ? 'disabled' : ''; ?>">check</i>
																	</span>
																</label>
															</span>
															<?php
														} ?>
															<?php
													} ?>
															<span class="task-label" data-toggle="tooltip" data-original-title="<?= $t->task_progress ?>% <?= lang('done') ?>" data-placement="right"><?= $t->task_name ?></span>
															<span class="task-action-btn task-btn-right">
																<a href="<?= base_url() ?>projects/view/<?= $t->project ?>?group=tasks&view=task&id=<?= $t->t_id ?>" class="action-circle large" title="View">
																	<i class="material-icons">remove_red_eye</i>
																</a>
															</span>
														</div>
													</li>
													<?php
												} ?>
												</ul>
											</div>
										</div>
                                    </div>
                                <?php
                                    } else {
                                        ?>
                                    <div class="no-info"><?= lang('no_task_in_project') ?></div>
                                <?php
                                    } ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php
			} ?>

            <!-- End Recent Task -->

            <!-- Start Project Checklist -->

            <div class="col-sm-6 row-eq-height">
                <div class="panel panel-white task-panel panel-eq-checklist">
					<div class="panel-heading b-b">
						<div class="row">
							<div class="col-sm-6">
								<h3 class="panel-title"><?= lang('todo_list') ?></h3>
							</div>
							<div class="col-sm-6">
								<a href="<?= base_url() ?>projects/todo/add/<?= $project_id ?>" data-toggle="ajaxModal" class="btn btn-primary btn-xs pull-right"><?= lang('create_list') ?></a>
							</div>
						</div>
					</div>
                    <div class="panel-body">
                        <div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
									<div class="task-wrapper">
										<div class="task-list-container">
											<div class="task-list-body">
												<ul id="task-list" class="task-list">
													
													<?php $todo = $this->db->order_by('id','desc')->where(array('module'=> 'projects','project' => $project_id))->get('todo')->result(); ?>
													<?php foreach ($todo as $key => &$list) {
													if ($list->saved_by == User::get_id() || $list->visible == 'Yes') {
													unset($todo[$key]); ?>

                                            <li class="task list-<?= ($list->status == 'done') ? 'primary task-done' : 'danger'; ?> <?=($list->status == 'done') ? 'completed ' : ''; ?>">
												<div class="task-container">
															<span class="task-action-btn task-check todo_complete">
																
																<label class="checkbox-custom">
																<span class="action-circle large complete-btn">
															<input type="checkbox" class="list-child" data-id="<?=$list->id?>"
														  <?php echo ($list->status == 'done') ? 'checked="checked"' : ''; ?>
														  <?php echo ($list->saved_by != User::get_id()) ? 'disabled="disabled"' : ''; ?>>
														<i class="material-icons <?=($list->status == 'done') ? 'completed ' : ''; ?>
																<?=($list->saved_by != User::get_id()) ? 'disabled' : ''; ?>">check</i>
																</span>
													</label>
													
															</span>
															<span class="task-label"><?= $list->list_name ?></span>
															<span class="task-action-btn task-btn-right">
																
																
                                                        <?php if ($list->saved_by == User::get_id()) {
                                            ?>
                                                            <a href="<?=base_url()?>projects/todo/edit/<?= $list->id ?>"
                                                               data-toggle="ajaxModal"
                                                               class="action-circle large"><i class="material-icons">mode_edit</i>

                                                            </a>
                                                            <a href="<?=base_url()?>projects/todo/delete/<?= $list->id ?>" data-toggle="ajaxModal"
                                                               class="action-circle large"><i class="material-icons">delete</i>

                                                            </a>
                                                        <?php
                                        } ?>
															</span>
                                                </div>
                                            </li>
											<?php
											} ?>
											<?php
											} ?>
</ul>
											</div>
										</div>
                                    </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- END TODO -->


        </div>


        <!-- END ROW -->


        <div class="row  row-eq-height">


            <!-- start recent files -->

            <?php if (User::is_admin() || User::is_staff() || Project::setting('show_project_files', $project_id)) {
                                    ?>
                <div class="col-sm-6 row-eq-height">
                    <div class="panel panel-table panel-eq-files">
                        <div class="panel-heading">
							<h3 class="panel-title"><?= lang('recent_files') ?></h3>
						</div>
                        <div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                            <table class="table table-striped custom-table m-b-0">
                                <thead>
									<tr>
										<th><?= lang('file_name') ?></th>
										<th></th>
									</tr>
                                </thead>
                                <tbody>
                                <?php $files = Project::has_files($project_id);
                                    if (count($files) > 0) {
                                        foreach ($files as $key => $f) {
                                            $icon = $this->applib->file_icon($f->ext);
                                            $path = $f->path;
                                            $file_path = ($path != null)
                                            ? base_url().'assets/project-files/'.$path.$f->file_name
                                            : base_url().'assets/project-files/'.$f->file_name;
                                            $real_url = $file_path; ?>
                                        <tr>
                                            <td>
                                                <?php if ($f->is_image == 1) : ?>
                                                    <?php if ($f->image_width > $f->image_height) {
                                                $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                                                $style = 'height:100%; margin-left: -'.$ratio.'%';
                                            } else {
                                                $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                                                $style = 'width:100%; margin-top: -'.$ratio.'%';
                                            } ?>
                                                    <div class="file-icon icon-small"><a
                                                            href="<?= base_url() ?>projects/files/preview/<?= $f->file_id ?>/<?= $project_id ?>"
                                                            data-toggle="ajaxModal"><img style="<?= $style ?>"
                                                                                         src="<?= $real_url ?>"/></a>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="file-icon icon-small"><i
                                                            class="fa <?= $icon ?> fa-lg"></i></div>
                                                <?php endif; ?>
                                                <a href="<?= base_url() ?>projects/files/download/<?= $f->file_id ?>"
                                                   data-original-title="<?= $f->description ?>"
                                                   data-toggle="tooltip" data-placement="top" title="">
                                                    <?php
                                                    if (empty($f->title)) {
                                                        echo $this->applib->short_string($f->file_name, 10, 8, 22);
                                                    } else {
                                                        echo $this->applib->short_string($f->title, 20, 0, 22);
                                                    } ?>

                                                </a></td>
                                            <td>
                                                <?php echo User::displayName($f->uploaded_by); ?>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                        ?>
										<tr>
											<td colspan="2">
												<div class="no-info"><?= lang('no_file_in_project') ?></div>
											</td>
										</tr>
                                <?php
                                    } ?>
                                </tbody>
                            </table>

                        </div>


                    </div>
                </div>
            <?php
                                } ?>

            <!-- END FILES -->


            <?php if (User::is_admin() || User::is_staff() || Project::setting('show_project_bugs', $project_id)) {
				?>
			<div class="col-sm-6 row-eq-height">
				<div class="panel panel-table panel-eq-bugs">
					<div class="panel-heading">
						<h3 class="panel-title"><?= lang('recent_bugs') ?></h3>
					</div>
					<div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
						<table class="table table-striped custom-table m-b-0">
							<thead>
								<tr>
									<th><?= lang('action') ?></th>
									<th><?= lang('reporter') ?></th>
								</tr>
							</thead>
								<tbody>
								<?php
								$bugs = Project::has_bugs($project_id);
								if (count($bugs) > 0) {
								foreach ($bugs as $key => $b) {
								?>
								<tr>
									<td>
										<a class="btn btn-xs btn-<?= config_item('theme_color') ?>" href="<?= base_url() ?>projects/view/<?= $project_id ?>/?group=bugs&view=bug&id=<?= $b->bug_id ?>"><?= lang('preview') ?></a>
									</td>
									<td><?php echo User::displayName($b->reporter); ?></td>
								</tr>
								<?php
								}
								} else {
								?>
								<tr>
									<td colspan="2">
										<div class="no-info"><?= lang('no_bug_in_project') ?></div>
									</td>
								</tr>
								<?php
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			</div>
            <?php } ?>
            <!-- END FILES -->
		</div>
	</div>