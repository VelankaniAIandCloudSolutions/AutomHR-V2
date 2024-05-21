<section class="panel panel-default">
  <?php
  $milestone = isset($_GET['id']) ? $_GET['id'] : 0;
  $m = Project::view_milestone($milestone);
  ?>
    <header class="panel-heading">
      <div class="row">
        <div class="col-sm-12">

          <?php  if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)){ ?>
           <a href="<?=base_url()?>projects/milestones/add_task/<?=$m->id?>/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm"><?=lang('add_task')?></a>

            <a href="<?=base_url()?>projects/milestones/edit/<?=$m->id?>" data-toggle="ajaxModal" class="btn btn-info btn-sm"><?=lang('edit_milestone')?></a>

            <a href="<?=base_url()?>projects/milestones/delete/<?=$m->project?>/<?=$m->id?>" data-toggle="ajaxModal" title="<?=lang('delete_milestone')?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o text-white"></i> <?=lang('delete_milestone')?></a>
          <?php } ?>

        </div>
      </div>
    </header>
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-2"><?=lang('progress')?></div>
        <div class="col-lg-10">
          <div class="progress progress-xs m-t-sm">
            <div class="progress-bar progress-bar-<?=config_item('theme_color')?>" 
            data-toggle="tooltip" data-original-title="<?=Project::milestone_progress($m->id);?>%" style="width: <?=Project::milestone_progress($m->id);?>%"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?php echo $m->milestone_name; ?> </span><?=lang('milestone_name')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right"><?php echo Project::by_id($m->project)->project_title;?></span>
              <?=lang('project')?>
            </li>
          </ul>
        </div>
        <!-- End details C1-->
        <div class="col-lg-6">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=strftime(config_item('date_format'), strtotime($m->start_date))?></span><?=lang('start_date')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right">
                <?=strftime(config_item('date_format'), strtotime($m->due_date))?>
                <?php if (time() > strtotime($m->due_date)){ ?>
                  <span class="badge bg-danger"><?=lang('overdue')?></span>
                <?php } ?>
              </span><?=lang('due_date')?>
            </li>
          </ul>
        </div>
      </div>
      <p><blockquote class="small text-muted"><?=nl2br_except_pre($m->description)?></blockquote></p>
    </div>

    <!-- End details -->
    <!-- End ROW 1 -->
</section>
    <!-- Start Milestone Tasks -->
	<div class="panel">
    <header class="panel-heading">
      <div class="row">
        <div class="col-sm-12"><strong><?=lang('milestone_tasks')?></strong></div>
      </div>
    </header>
    <div class="panel-body">
    <div class="table-responsive">
      <table id="table-milestone" class="table table-striped table-bordered AppendDataTables">
        <thead>
          <tr>
            <th><?=lang('timer')?></th>
            <th><?=lang('task_name')?></th>
            <th class="col-date"><?=lang('due_date')?></th>
            <th><?=lang('progress')?></th>
            <th class="col-options no-sort"><?=lang('options')?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (Project::milestone_has_tasks($m->id) as $key => $t) {
              $timer = ($t->timer_status == 'Off') ? 'success' : 'danger'; ?>
            <tr>
              <td><span class="label label-<?=$timer?>"><?=$t->timer_status?></span></td>

              <td> <a class="text-info <?php if($t->task_progress >= 100 ) echo 'text-lt'; ?>" href="<?=base_url()?>projects/view/<?=$t->project?>?group=tasks&view=task&id=<?=$t->t_id?>"><?=$t->task_name?></a></td>

              <td><?=strftime(config_item('date_format'), strtotime($t->due_date))?>
              </td>

              <td>
                <div class="inline ">
                  <div class="easypiechart text-<?=config_item('theme_color')?>" data-percent="<?=$t->task_progress?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="<?=config_item('chart_color')?>" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                    <span class="small text-muted"><?=$t->task_progress?>%</span>
                  </div>
                </div>
              </td>
              <td>
                <?php  if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)){ ?>
                <a class="btn btn-xs btn-<?=config_item('theme_color')?>" href="<?=base_url()?>projects/tasks/edit/<?=$t->t_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
                <?php } ?>
                <?php  if(!User::is_client()){ ?>
                  <?php if ($t->timer_status == 'On') { ?>
                      <a class="btn btn-xs btn-danger" href="<?=base_url()?>projects/tasks/tracking/off/<?=$t->project?>/<?=$t->t_id?>"><?=lang('stop_timer')?> </a>
                    <?php }else{ ?>
                      <a class="btn btn-xs btn-success" href="<?=base_url()?>projects/tasks/tracking/on/<?=$t->project?>/<?=$t->t_id?>"><?=lang('start_timer')?> </a>
                  <?php } ?>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    </div>
    </div>
    <!-- End Milestone Tasks -->