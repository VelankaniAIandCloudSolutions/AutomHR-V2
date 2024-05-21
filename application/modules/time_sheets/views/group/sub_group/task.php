<style type="text/css">
  .note-editor.note-frame {
    border: none; 
}
</style>
    <?php
    $task = isset($_GET['id']) ? $_GET['id'] : 0;
    $t = (!User::is_client()) ? Project::view_task($task) : Project::view_task($task,'Yes');
    if($t->project == $project_id){ ?>
	<div class="row m-b-20">
		<div class="col-sm-12">
			<?php if($t->task_progress < 100) {
			if(!User::is_client()){
			if(Project::timer_status('tasks',$t->t_id) == 'On') { ?>
			<a class="btn btn-danger" href="<?=base_url()?>projects/tasks/tracking/off/<?=$t->project?>/<?=$t->t_id?>"><?=lang('stop_timer')?></a>
			<?php }else{ ?>
			<a class="btn btn-success" href="<?=base_url()?>projects/tasks/tracking/on/<?=$t->project?>/<?=$t->t_id?>"><?=lang('start_timer')?></a>
			<?php }  }
			}
			?>
			<?php if($t->task_progress < 100 && !User::is_client()) : ?>
			<a class="btn btn-primary" href="<?=base_url()?>projects/tasks/close_open/<?=$t->t_id?>" data-toggle="tooltip" data-title="<?=lang('mark_as_complete');?>" data-placement="bottom">
				<i class="fa fa-lg fa-check-square-o text-white"></i>
			</a>
			<?php endif; ?>
			<a href="<?=base_url()?>projects/tasks/file/<?=$t->project?>/<?=$t->t_id?>" data-toggle="ajaxModal" class="btn btn-info"><?=lang('attach_file')?></a>
			<?php if(!User::is_client() || $t->added_by == User::get_id()){ ?>
			<a href="<?=base_url()?>projects/tasks/edit/<?=$t->t_id?>" data-toggle="ajaxModal" class="btn btn-success"><?=lang('edit_task')?></a>
			<?php } if(User::is_admin()){ ?>
			<a href="<?=base_url()?>projects/tasks/delete/<?=$t->project?>/<?=$t->t_id?>" data-toggle="ajaxModal" title="<?=lang('delete_task')?>" class="btn btn-danger">
				<i class="fa fa-trash-o text-white"></i> <?=lang('delete_task')?>
			</a>
			<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4">
			<div class="panel">
				<div class="panel-body">
					<h6 class="panel-title m-b-15">Task Details</h6>
					<table class="table table-striped table-border">
						<tbody>
							<tr>
								<td><?=lang('total_time')?>:</td>
								<td class="text-right"><?=Applib::sec_to_hours(Project::task_timer($t->t_id))?></td>
							</tr>
							<tr>
								<td><?=lang('estimate')?>:</td>
								<td class="text-right"><?=$t->estimated_hours?> <?=lang('hours')?></td>
							</tr>
							<tr>
								<td><?=lang('milestone')?>:</td>
								<td class="text-right">
									<?php $text_status = ($t->milestone) ? Project::view_milestone($t->milestone)->milestone_name : ''; 
									if(!empty($text_status)){
									?>
									<a href="<?=base_url()?>projects/view/<?=$t->project?>/?group=milestones&view=milestone&id=<?=$t->milestone?>" class="text-primary">
										<?php echo ($t->milestone) ? Project::view_milestone($t->milestone)->milestone_name : 'N/A'; ?>
									</a>
									<?php }else{echo '-';} ?>
								</td>
							</tr>
							<tr>
								<td><?=lang('start_date')?>:</td>
								<td class="text-right">
									<?php $start_date = ($t->start_date == NULL) ? $t->date_added : $t->start_date; ?> <?=strftime(config_item('date_format'), strtotime($start_date))?>
								</td>
							</tr>
							<tr>
								<td><?=lang('end_date')?>:</td>
								<td class="text-right">
									<?=strftime(config_item('date_format'), strtotime($t->due_date))?>
								</td>
							</tr>
							<tr>
								<td>Created by:</td>
								<td class="text-right">
									<a href="#">
										<img src="<?php echo User::avatar_url($t->added_by); ?>" class="img-circle thumb-sm" data-toggle="tooltip" data-title="<?=User::displayName($t->added_by); ?>" data-placement="bottom">
										<?=User::displayName($t->added_by)?>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="m-b-5">Progress <span class="text-<?=config_item('theme_color')?> pull-right"><?=$t->task_progress?>%</span></p>
					<div class="progress progress-xs <?=($t->task_progress != '100') ? 'active' : ''; ?> m-b-0">
						<div class="progress-bar progress-bar-<?=config_item('theme_color')?>" data-toggle="tooltip" data-original-title="<?=$t->task_progress?>%" style="width: <?=$t->task_progress?>%">
						</div>
					</div>
				</div>
			</div>
			<div class="panel uploaded-files">
				<div class="panel-body">
					<h5 class="panel-title m-b-20">Uploaded files</h5>
					<ul class="files-list">
					<?php $this->load->helper('file');
					foreach (Project::task_has_files($t->t_id) as $key => $f) {
						$icon = $this->applib->file_icon($f->file_ext);
						$real_url = ($f->path != NULL)
							? base_url().'assets/project-files/'.$f->path.$f->file_name
							: base_url().'assets/project-files/'.$f->file_name;
						?>
					<?php if(count(Project::task_has_files($t->t_id) > 0)): ?>
						<li>
							<div class="files-cont">
								<div class="file-type">
								<?php if ($f->is_image == 1) : ?>
									<?php if ($f->image_width > $f->image_height) {
										$ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
										$style = 'height:100%; margin-left: -'.$ratio.'%';
									} else {
										$ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
										$style = 'width:100%; margin-top: -'.$ratio.'%';
									}  ?>
									<a href="<?=base_url()?>projects/tasks/preview/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" /></a>
								<?php else : ?>
									
									<span class="files-icon"><i class="fa <?=$icon?>"></i></span>
								<?php endif; ?>
								</div>
								<div class="files-info">
									<span class="file-name text-ellipsis"><a data-toggle="tooltip" data-placement="top" data-original-title="<?=$f->description?>" href="<?=base_url()?>projects/tasks/download/<?=$f->file_id?>">
							<?=(empty($f->title) ? $f->file_name : $f->title)?>
						</a></span>
									<span class="file-author"><a href="javascript:void(0);"><?php echo User::displayName($f->uploaded_by)?></a></span> <span class="file-date"><?=strftime(config_item('date_format')." %H:%M", strtotime($f->date_posted));?></span>
									<div class="file-size">Size: <?=number_format($f->size,0,config_item('decimal_separator'),  config_item('thousand_separator'))?> KB</div>
								</div>
								<?php  if($f->uploaded_by == User::get_id() || User::is_admin()){ ?>

								<ul class="files-action">
									<li class="dropdown">
										<a aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-default btn-xs" href=""><i class="fa fa-ellipsis-h"></i></a>
										<ul class="dropdown-menu">
											<li><a href="<?=base_url()?>projects/tasks/file/edit/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i> Edit</a></li>
											<li><a href="<?=base_url()?>projects/tasks/file/delete/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i> Delete</a></li>
										</ul>
									</li>
								</ul>
								
								<?php } ?>
							</div>
						</li>
					  <?php endif; ?>
					  <?php } ?>
					</ul>
				</div>
			</div>
			<div class="card-box">
				<h6 class="panel-title m-b-15"><?=lang('team_members')?></h6>
				<ul class="team-members">
					<?php if(!User::is_client()){ ?>
					<?php foreach (Project::task_team($t->t_id) as $u) { ?>
					<li>
						<a href="javascript:void(0);">
							<img src="<?php echo User::avatar_url($u->assigned_user); ?>" class="img-circle" data-toggle="tooltip" data-title="<?=User::displayName($u->assigned_user); ?>" data-placement="right">
						</a>
					</li>
					<?php } ?>
					<?php } ?>
				</ul>
			</div>
		</div>

                
					<div class="col-lg-8">
						<div class="panel">
                            <div class="panel-body">
                                <div class="project-title">
                                    <h5 class="panel-title m-b-20"><?=$t->task_name?></h5>
                                </div>
                                <div class="task-desc">
									<?=nl2br_except_pre($t->description)?>
								</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
							<?php $attributes = 'class="m-b-0"'; echo form_open(base_url().'projects/tasks/comment',$attributes); ?>
								<div class="panel-body">
									<input type="hidden" name="task_id" value="<?=$t->t_id?>">
									<input type="hidden" name="project" value="<?=$t->project?>">
									<textarea class="form-control foeditor-100" name="message" placeholder="<?=$t->task_name?> <?=lang('comment')?>"></textarea>
								</div>
								<div class="panel-footer bg-white">
                                    <button class="btn btn-success pull-right" type="submit"><?=lang('comment')?></button>
                                    <ul class="nav nav-pills nav-sm"></ul>
                                </div>
							</form>
					   </div>
                        <div class="panel panel-white">
							<div class="panel-heading">
								<h3 class="panel-title"><?=lang('latest_comments')?></h3>
							</div>
							<ul class="list-group">
								<?php foreach (Project::task_has_comments($t->t_id) as $key => $c) {
								$role_label = (User::login_info($c->posted_by)->role_id == '1') ? 'danger' : 'info';
								?>
								<li class="list-group-item">
									<a class="thumb-sm pull-left m-r-sm">
									<img src="<?php echo User::avatar_url($c->posted_by); ?>" class="img-rounded" data-toggle="tooltip" data-title="<?=User::displayName($c->posted_by); ?>"
									data-placement="right">
									</a>
									<div class="activate_links">
									<?=nl2br_except_pre($c->message)?>
									</div>
									<small class="block text-muted">
									<strong><?=User::displayName($c->posted_by);?></strong>
									<i class="fa fa-clock-o"></i> 
									<?php
									if(config_item('show_time_ago') == 'TRUE'){
									echo strtolower(humanFormat(strtotime($c->date_posted)).' '.lang('ago'));
									} ?></small>
								</li>
								<?php } ?>
								<?php if(count(Project::task_has_comments($t->t_id)) <= 0) : ?>
								<li class="list-group-item">
									<div class="no-info"><?=lang('no_comment_found')?></div>
								</li>
								<?php endif; ?>
							</ul>
						</div>
                    </div>         
                </div>
				<?php } ?>