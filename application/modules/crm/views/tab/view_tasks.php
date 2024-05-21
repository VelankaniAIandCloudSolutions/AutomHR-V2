<link rel="stylesheet" href="<?= base_url() ?>assets/css/tasks.css" type="text/css"/>
<!-- Lead Tasks -->
<div class="row">
    <div class="col-md-12">
        <div class="card task-panel panel-white">
			<div class="card-header">
				<div class="row">
					<div class="col-6">
						<h3 class="card-title"><?=lang('tasks')?></h3>
					</div>
					<div class="col-6">
						<a href="<?=base_url()?>leads/todo/add/<?=$l->co_id?>" class="btn btn-sm btn-success float-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_task')?></a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="task-wrapper">
					<div class="task-list-container">
						<div class="task-list-body">
							<ul id="task-list" class="task-list">
								<?php $todo = Lead::tasks($l->co_id); ?>
								<?php foreach ($todo as $key => &$list) { ?>
								<li class="task <?=($list->status == 'done') ? 'completed' : ''; ?> <?=($list->saved_by != User::get_id()) ? 'disabled' : ''; ?>">
									<div class="task-container">
										<span class="task-action-btn task-check todo_complete">
											<label class="checkbox-custom">
												<span class="action-circle large complete-btn">
													<input type="checkbox" class="list-child" data-id="<?=$list->id?>" <?php echo ($list->status == 'done') ? 'checked="checked"' : ''; ?> <?php echo ($list->saved_by != User::get_id()) ? 'disabled="disabled"' : ''; ?>>
													<i class="material-icons <?=($list->status == 'done') ? 'checked' : ''; ?> <?=($list->saved_by != User::get_id()) ? 'disabled' : ''; ?>">check</i>
												</span>
											</label>
										</span>
										<span class="task-label"><?=nl2br_except_pre($list->list_name) ?></span>
										<span class="task-action-btn task-btn-right">
											<?php if ($list->saved_by == User::get_id()) {
											?>
											<a href="<?=base_url()?>leads/todo/edit/<?= $list->id ?>" data-toggle="ajaxModal" class="action-circle large icon-edit">
												<i class="material-icons">mode_edit</i>
											</a> 
											<a href="<?=base_url()?>leads/todo/delete/<?= $list->id ?>" data-toggle="ajaxModal" class="action-circle large icon-remove">
												<i class="material-icons">delete</i>
											</a>
											<?php
											} ?>
										</span>
									</div>
								</li>
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