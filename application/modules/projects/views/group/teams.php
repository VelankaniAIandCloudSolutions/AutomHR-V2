<div class="panel panel-table">
	<div class="panel-heading panel-h-p1">
		<div class="row">
			<div class="col-xs-6">
				<h3 class="panel-title m-t-6">Team</h3>
			</div>
			<div class="col-xs-6">
				<?php if(User::is_admin()){ ?>
				<a href="<?=base_url()?>projects/team/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm pull-right"><?=lang('update_team')?></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<?php if (!User::is_client() || Project::setting('show_team_members',$project_id)) { ?>
			<table id="table-teams" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th style="width:5px; display:none;"></th>
						<th class=""><?=lang('user')?></th>
						<th class=""><?=lang('last_login')?></th>
						<th class=""><?=lang('email')?></th>
						<?php if(User::is_admin()){ ?>
						<th class=""><?=lang('hours')?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach(Project::project_team($project_id) as $key => $u) { ?>
					<tr>
						<td style="display:none;"><?=$u->assigned_user?></td>
						<td>
							<a class="thumb-sm avatar" href="javascript:void(0);"><img src="<?php echo User::avatar_url($u->assigned_user); ?>" class="img-rounded"></a>
							<h2><a href="javascript:void(0);"><?php echo User::displayName($u->assigned_user); ?></a></h2>
						</td>
						<td>
							<span class="text-muted">
							<?php if(User::login_info($u->assigned_user)->last_login != '0000-00-00 00:00:00'){
							echo Applib::time_elapsed_string(strtotime(User::login_info($u->assigned_user)->last_login));
							}else{ echo User::login_info($u->assigned_user)->last_login; } ?>
							</span>
							<br>
							<i class="fa fa-phone"></i>
							<small><?=User::profile_info($u->assigned_user)->phone;?></small>
						</td>
						<?php $email = User::login_info($u->assigned_user)->email?>
						<td class=""><a href="mailto:<?=$email?>"><?=$email?></a></td>
						<?php if(User::is_admin()){
						$p_hours = Project::time_by_user($u->assigned_user,'1',$project_id)->projects_time;
						$t_hours = Project::time_by_user($u->assigned_user,'1',$project_id)->tasks_time;
						$total_hours = $p_hours + $t_hours;
						$format = sprintf('%02d:%02d:%02d', ($total_hours / 3600), ($total_hours / 60 % 60), $total_hours % 60);
						?>
						<td>
							<span class=""><?=lang('time_spent')?></span> &raquo; <span class="label label-success"><?=$format?></span><br>
							<span class=""><?=lang('hourly_rate')?></span> &raquo; <span class="label label-default">
							<?=User::profile_info($u->assigned_user)->hourly_rate?>/<?=lang('hour')?></span>
						</td>
						<?php } ?>
					</tr>
					<?php }  ?>
				</tbody>
			</table>
			<?php } ?>
			<!-- End view team members -->
		</div>
	</div>
</div>