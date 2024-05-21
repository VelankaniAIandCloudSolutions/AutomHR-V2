<div class="panel panel-table">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-12">
				<?php if (!User::is_client() || Project::setting('show_timesheets',$project_id)) { 
				$cat = isset($_GET['cat']) ? $_GET['cat'] : 'projects';
				?>
				<div class="">
					<?php  if(!User::is_client()){ ?>
					<a href="<?=base_url()?>projects/timesheet/add_time/<?=$project_id?>?group=timesheets&cat=<?=$cat?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm"><i class="fa fa-clock-o"></i> <?=lang('time_entry')?></a>
					<?php } ?>
					<div class="pull-right">
						<?php if ($cat == 'projects') { ?>
						<a class="btn btn-success btn-sm" href="<?=base_url()?><?=uri_string()?>?group=timesheets&cat=tasks"><i class="fa fa-arrow-right"></i> <?=lang('switch_to_tasks_timesheet')?></a>
						<?php } elseif ($cat == 'tasks') { ?>
						<a class="btn btn-success btn-sm" href="<?=base_url()?><?=uri_string()?>?group=timesheets&cat=projects"><i class="fa fa-arrow-right"></i> <?=lang('switch_to_project_timesheet')?></a>
						<?php } ?>
					</div>           
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<?php
		if($cat == 'projects'){
		$data['project_id'] = $project_id;
		$this->load->view('group/sub_group/project_timelog',$data);
		} else {
		$data['project_id'] = $project_id;
		$this->load->view('group/sub_group/tasks_timelog',$data);
		}
		} ?>
		<!-- End details -->
	</div>
</div>