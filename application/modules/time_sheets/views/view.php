		<?php
        $role = User::login_info(User::get_id())->role_id;
        $this->load->helper('text');
        $p = Project::by_id($project);
        $currency = ($p->client > 0) ? Client::get_currency_code($p->client)->code : $p->currency;
        ?>
<div class="header-fixed clearfix">
	<div class="row">
		<div class="col-sm-12">
			<div class="col-xs-3 col-sm-4">
				<h3 class="page-title m-b-0"><?=$p->project_title; ?></h3>
			</div>
			<div class="col-xs-9 col-sm-8">
				<div class="btn-group pull-right">
					<?php if ($role != '2'):
					$timer_status = Project::timer_status('project', $p->project_id);
					$label = ($timer_status == 'On') ? 'danger' : 'success';
					if ($timer_status == 'On') : ?>
					<a data-toggle="tooltip" data-original-title="<?=lang('stop_timer')?>" data-placement="bottom" href="<?=base_url()?>projects/tracking/off/<?=$p->project_id?>" class="btn btn-sm btn-default">
						<i class="fa fa-clock-o text-<?=$label?>"></i>
					</a>
					<?php else: ?>
					<a data-toggle="tooltip" data-original-title="<?=lang('start_timer')?>" data-placement="bottom" href="<?=base_url()?>projects/tracking/on/<?=$p->project_id?>" class="btn btn-sm btn-default">
						<i class="ion-ios-timer text-<?=$label?>"></i>
					</a>
					<?php endif; ?>
					<?php endif; ?>

					<?php if (User::is_admin() || Project::is_assigned(User::get_id(), $p->project_id)) : ?>
					<?php $txt = ($p->auto_progress == 'TRUE') ? 'auto_progress_off' : 'auto_progress_on'; ?>
					<a data-toggle="tooltip" data-original-title="<?=lang($txt)?>" data-placement="bottom" href="<?=base_url()?>projects/auto_progress/<?=$p->project_id?>" class="btn btn-sm btn-default">
						<i class="ion-ios-pulse-strong"></i>
					</a>
					<?php if ($p->auto_progress == 'TRUE') : ?>
					<a data-toggle="ajaxModal" title="<?=lang('mark_as_complete')?>" href="<?=base_url()?>projects/mark_as_complete/<?=$p->project_id?>" class="btn btn-sm btn-default">
						<i class="ion-ios-checkmark-outline"></i> <?=lang('done')?>
					</a>
					<?php endif; ?>
					<?php if ($p->client > 0) {
					?>
					<?php if (User::is_admin() || User::perm_allowed(User::get_id(), 'add_invoices')) : ?>
					<a href="<?=base_url()?>projects/invoice/<?=$p->project_id?>" class="btn btn-default btn-sm" data-toggle="ajaxModal">
						<i class="ion-ios-paper"></i> <?=lang('invoice')?>
					</a>
					<?php endif; ?>
					<?php
					} ?>
					<?php if (User::is_admin()) : ?>
					<a href="<?=base_url()?>projects/copy_project/<?=$p->project_id?>" data-toggle="ajaxModal" class="btn btn-default btn-sm" title="<?=lang('clone_project')?>" data-placement="bottom">
						<i class="fa fa-clone"></i> <?=lang('clone_project')?>
					</a>
					<?php endif; ?>
					<?php if (User::is_admin() || User::perm_allowed(User::get_id(), 'edit_all_projects')) {
					?>
					<a data-toggle="tooltip" data-original-title="<?=lang('edit_project')?>" data-placement="bottom" href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=<?=$group?>&action=edit" class="btn btn-default btn-sm"><i class="ion-compose"></i> </a>
					<?php
					} ?>
					<?php if (User::is_admin() || User::perm_allowed(User::get_id(), 'delete_projects')) {
					?>
					<a href="<?=base_url()?>projects/delete/<?=$p->project_id?>?group=<?=$group?>" data-toggle="ajaxModal" title="<?=lang('delete_project')?>" data-placement="left" class="btn btn-default btn-sm">
						<i class="ion-ios-trash"></i>
					</a>
					<?php
					} ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<?php switch ($role) {
		case 1: $ext = 'admin'; break;
		case 2: $ext = 'client'; break;
		case 3: $ext = 'staff'; break;
		}
	$menus = $this->db->where('access', $role)->where('visible', 1)->where('hook', 'projects_menu_'.$ext)->order_by('order', 'ASC')->get('hooks')->result();
	?>

<div class="sub-tab m-b-20">
	<ul class="nav nav-tabs nav-tabs-bottom bg-white">
		<?php
		foreach ($menus as $menu) :
		$perm = true;
		$label = $this->db->where('parent', $menu->module)->where('hook', 'project_menu_label_'.$ext)->get('hooks')->result(); ?>
		<?php if ($menu->permission != '') {
		$perm = Project::setting($menu->permission, $p->project_id);
		} 

		
		?>

		<?php if ($perm) {
		$timer_on = 0;
		if ($menu->module == 'project_tasks') {
		$timer_on = App::counter('tasks_timer', array('pro_id' => $project, 'status' => '1'));
		} ?>

		<li class="<?php echo ($group == $menu->route) ? 'active' : ''; ?>">
			<a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=<?=$menu->route?>">
				<?=lang($menu->name)?>
				<?php if (count($label) > 0) {
				$lab = modules::run($label[0]->route, $p->project_id); ?>
				<span class="label label-default pull-right"><?php if ($lab > 0) {
				echo $lab;
				} ?></span><?php
				} ?>

				<?php if($menu->module == 'project_comments') : ?>
				<?php $count = $this->db->where(array('project'=>$p->project_id,'posted_by !='=>User::get_id(),'task_id'=>NULL,'unread'=>1))->get('comments')->num_rows();?>
				<?=($count > 0) ? '<label class="label bg-success">'.$count.'</label>' : '';?>
				<?php endif; ?>

				<?php if ($timer_on > 0) {
				?><span class="m-r-xs"><i class="fa fa-refresh fa-spin text-danger"></i></span><?php
				} ?>
			</a>
		</li>

		<?php
		} ?>

		<?php endforeach; ?>
	</ul>
</div>
<?php if (strtotime($p->due_date) < time() && strtotime($p->due_date) > 0 && $p->status != 'Done'): ?>
	<div class="overdue-cn-banner fill-container alert alert-success">
		<strong> Overdue: </strong>
		This project is overdue by <?=humanFormat(time(), strtotime($p->due_date))?>.
	</div>
<?php elseif (Project::total_expense($p->project_id) > 0): ?>
<div class="overdue-cn-banner fill-container alert alert-success">
	<strong> Expenses Available: </strong>
      An expense of <?=Applib::format_currency($currency, Project::total_expense($p->project_id)); ?> available for this project. <a href="<?=site_url()?>expenses/?project=<?=$p->project_id?>">Click here</a> to view expense.
</div>
<?php endif; ?>

<!-- Load the settings form in views -->
<?php
if (isset($_GET['action']) == 'edit') {
	$data['project_id'] = $p->project_id;
	$this->load->view('group/edit_project', $data);
} else {
	$data['project_id'] = $p->project_id;
	$this->load->view('group/'.$group, $data);
}
?>
<!-- End of settings Form -->
</div>
