<div class="content">
	<div class="row">
		<div class="col-xs-5">
			<h4 class="page-title"><?=lang('tax_rates')?></h4>
		</div>
		<div class="col-xs-7 text-right m-b-0">
			<a href="<?=base_url()?>invoices/tax_rates/add" data-toggle="ajaxModal" class="btn btn-primary btn-rounded pull-right"><i class="fa fa-plus"></i> <?=lang('new_tax_rate')?></a>
		</div>
	</div>
	
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-rates" class="table table-striped custom-table m-b-0 AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('tax_rate_name')?></th>
					<th><?=lang('tax_rate_percent')?></th>
					<th class="col-options no-sort text-right"><?=lang('options')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rates as $key => $r) { if($r->branch_id == $this->session->userdata('branch_id')){ ?>
				<tr>
					<td><?=$r->tax_rate_name?></td>
					<td><?=$r->tax_rate_percent?> %</td>
					<td class="text-right">
						<a class="btn btn-success btn-xs" href="<?=base_url()?>invoices/tax_rates/edit/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('edit_rate')?>"><i class="fa fa-edit"></i></a>
						<a class="btn btn-danger btn-xs" href="<?=base_url()?>invoices/tax_rates/delete/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('delete_rate')?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<?php } }  ?>
			</tbody>
		</table>
		</div>
		</div>
	</div>
</div>