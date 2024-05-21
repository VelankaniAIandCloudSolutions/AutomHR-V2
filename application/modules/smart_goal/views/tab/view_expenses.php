<!-- Client Expenses -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-table">
			<div class="panel-heading panel-h-p1">
				<h3 class="panel-title m-t-5 m-b-5"><?=lang('expenses')?></h3>
			</div>
			<div class="panel-body">
			<div class="table-responsive">
				<table id="table-expenses" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th style="width:5px; display:none;"></th>
							<th class=""><?=lang('project')?></th>
							<th class="col-currency"><?=lang('amount')?></th>
							<th class=""><?=lang('invoiced')?></th>
							<th class=""><?=lang('category')?></th>
							<th class=""><?=lang('expense_date')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach (Expense::expenses_by_client($company) as $key => $e) {
						$p = ($e->project != '' || $e->project != 'NULL')? Project::by_id($e->project) : NULL; ?>
						<tr>
							<td style="display:none;"><?=$e->id?></td>
							<td>
								<?=($p != NULL) ? $p->project_title : 'N/A'; ?>
							</td>
							<td class="col-currency">
								<strong>
								<?php
								$cur = ($p != NULL) ? $p->currency : 'USD';
								$cur = ($e->client > 0) ? Client::client_currency($e->client)->code : $cur;
								?>
								<?=Applib::format_currency($cur, $e->amount)?>
								</strong>
							</td>
							<td>
							<span class="small label label-<?=($e->invoiced == '0') ? 'danger' : 'success';?>">
								<?=($e->invoiced == '0') ? 'No' :'Yes'; ?>
							</span>
							</td>
							<td>
								<?php echo App::get_category_by_id($e->category); ?>
							</td>
							<td>
								<?=strftime(config_item('date_format'), strtotime($e->expense_date))?>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>