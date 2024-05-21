<div class="panel panel-table">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6">
				<h3 class="panel-title">Links</h3>
			</div>
			<div class="col-xs-6">
				<?php  if(User::is_admin()){ ?>
				<a href="<?=base_url()?>projects/links/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm pull-right"><?=lang('add_link')?></a> 
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="table-links" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th><?=lang('link_title')?></th>
						<?php if(User::is_admin()){ ?>
						<th class="col-options no-sort text-right"><?=lang('options')?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach (Project::has_links($project_id) as $key => $link) { 
					?>
					<tr>
						<td>
							<a class="text-info" href="<?=base_url()?>projects/view/<?=$link->project_id?>?group=links&view=link&id=<?=$link->link_id?>" data-original-title="<?=$link->description?>" data-toggle="tooltip" data-placement="right" title = ""><?=$link->link_title?></a>
							<?php if (!empty($link->password)){ ?>
							<i class="fa fa-lock pull-right"></i>
							<?php } ?>
						</td>
						<?php  if(User::is_admin()){ ?>
						<td class="text-right">
							<a class="btn btn-xs btn-info" href="<?=base_url()?>projects/links/edit/<?=$link->link_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
							<a href="<?=base_url()?>projects/links/delete/<?=$link->project_id;?>/<?=$link->link_id?>" data-toggle="ajaxModal" title="<?=lang('delete_link')?>" class="btn btn-xs btn-danger"><i class="fa fa-trash-o text-white"></i></a>
							<a href="<?=$link->link_url?>" target="_blank" title="<?=$link->link_title?>" class="btn btn-xs btn-primary"><i class="fa fa-external-link text-white"></i></a>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>