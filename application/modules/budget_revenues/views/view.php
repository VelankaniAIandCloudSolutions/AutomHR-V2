<?php $inf = Expense::view_by_id($id);?>
<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('view_expense')?></h4>
		</div>
		<div class="col-sm-4 text-right m-b-0">
			<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_expenses')) { ?>
			<a href="<?=base_url()?>expenses/edit/<?=$inf->id?>" title="<?=lang('edit_expense')?>" class="btn btn-primary rounded pull-right" data-toggle="ajaxModal">
				<i class="fa fa-pencil text-white"></i> <?=lang('edit_expense')?>
			</a>
			<?php } ?>
		</div>
	</div>

	<!-- Start details -->

	<?php if($inf->project != '' || $inf->project != NULL){
	  $p = Project::by_id($inf->project);
	}else{
	  $p = NULL;
	}

	?>
	<div class="row">
		<div class="col-sm-9">
			<div class="card-box">
				<h3 class="card-title">
					<?php if($p != NULL){ ?> 
					<?=lang('project')?> : <a class="text-primary" href="<?=site_url()?>projects/view/<?=$p->project_id?>">
					<?=strtoupper($p->project_title)?></a>
					<?php } ?>
				</h3>
				<ul class="list-group no-radius m-b-0">
					<li class="list-group-item">
						<span class="text-muted"><?=lang('expense_date')?></span>
						<span class="pull-right"><?=strftime(config_item('date_format')." %H:%M:%S", strtotime($inf->expense_date));?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('category')?></span>
						<span class="pull-right"><?php echo App::get_category_by_id($inf->category); ?></span>
					</li>
					<?php if(User::is_admin() || (User::is_staff() && User::perm_allowed(User::get_id(),'view_project_clients'))) { ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('client')?></span>
						<span class="pull-right"><strong><a href="<?=base_url()?>companies/view/<?=$inf->client?>"><?=ucfirst(Client::view_by_id($inf->client)->company_name)?></a></strong></span>
					</li>
					<?php } ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('added_by')?></span>
						<span class="pull-right"><?php echo User::displayName($inf->added_by); ?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('billable')?></span>
						<span class="pull-right"><?=($inf->billable == '1') ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('invoiced')?></span>
						<span class="pull-right"><?=($inf->invoiced == '1') ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('date_saved')?></span>
						<span class="pull-right"><?=strftime(config_item('date_format')." %H:%M:%S", strtotime($inf->saved));?></span>
					</li>
					<?php if($inf->invoiced_id > 0) { ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('invoiced_in')?></span>
						<span class="pull-right">
							<a href="<?=site_url()?>invoices/view/<?=$inf->invoiced_id?>">
								#<?php echo Invoice::view_by_id($inf->invoiced_id)->reference_no; ?>
							</a>
						</span>
					</li>
					<?php } ?>
					<?php if($inf->receipt) { ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('attachment')?></span>
						<span class="pull-right">
							<a href="<?=base_url()?>assets/uploads/<?=$inf->receipt?>" target="_blank">
								<?=$inf->receipt?>
							</a>
						</span>
					</li>
					<?php } ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('notes')?></span><br>
						<span><?=($inf->notes) ? $inf->notes : 'NULL'; ?></span>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="payment-info-box">
				<h5><?=lang('expense_cost')?></h5>
				<h3>
					<?php 
					$cur = ($p != NULL) ? $p->currency : 'USD';
					$cur = ($inf->client > 0) ? Client::client_currency($inf->client)->code : $cur;
					?>
					<?php echo Applib::format_currency($cur, $inf->amount); ?>
				</h3>
			</div>
		</div>
	</div>
	<!-- End expense details -->
</div>