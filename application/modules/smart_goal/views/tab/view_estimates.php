<?php if ($i->individual == 0) { ?>
<!-- Client Contacts -->
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-table">
            <div class="panel-heading panel-h-p1">
				<div class="row">
					<div class="col-xs-6">
						<h3 class="panel-title m-t-6"><?=lang('estimates')?></h3>
					</div>
					<div class="col-xs-6">
						<a href="<?=base_url()?>estimates/add/<?=$i->co_id?>" class="btn btn-success pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('estimates')?></a>
					</div>
				</div>
			</div>
			<div class="panel-body">
			<div class="table-responsive">
				<table id="table-estimates" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th style="width:5px; display:none;"></th>
							<th class=""><?=lang('estimate')?></th>
							<th class=""><?=lang('status')?></th>
							<th class="col-date"><?=lang('due_date')?></th>
							<th class="col-date"><?=lang('created')?></th>
							<th class="col-currency"><?=lang('amount')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach (Estimate::estimate_by_client($company) as $key => $e) {
							$label = 'danger';
							if ($e->status == 'Pending'){ $label = "info"; }
							if($e->status == 'Accepted') { $label = "success";  }
						?>
						<tr>
							<td style="display:none;"><?=$e->est_id?></td>
							<td>
								<a class="text-info" href="<?=base_url()?>estimates/view/<?=$e->est_id?>">
									<?=$e->reference_no?>
								</a>
							</td>
							<td><span class="label label-<?=$label?>"><?=lang(strtolower($e->status))?> <?php if($e->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span></td>
							<td><?=strftime(config_item('date_format'), strtotime($e->due_date))?></td>
							<td><?=strftime(config_item('date_format'), strtotime($e->date_saved))?></td>
							<td class="col-currency">
								<strong><?=Applib::format_currency($e->currency, Estimate::due($e->est_id)); ?></strong>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				</div>
			</div>
        </section>
    </div>
</div>
<?php } ?>