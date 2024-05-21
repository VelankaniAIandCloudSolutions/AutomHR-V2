<!-- Client Payments -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-table">
			<div class="panel-heading panel-h-p1">
				<h3 class="panel-title m-t-5 m-b-5"><?=lang('payments')?></h3>
			</div>
			<div class="panel-body">
			<div class="table-responsive">
				<table id="table-payments" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th style="width:5px; display:none;"></th>
							<th><?=lang('date')?></th>
							<th><?=lang('invoice')?></th>
							<th class=""><?=lang('payment_method')?></th>
							<th><?=lang('amount')?> </th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach (Payment::client_payments($company) as $key => $p) {
						$cur = Client::client_currency($p->paid_by); ?>
						<tr>
							<td style="display:none;"><?=$p->p_id?></td>
							<td>
								<a class="text-info" href="<?=base_url()?>payments/view/<?=$p->p_id?>">
									<?=strftime(config_item('date_format'), strtotime($p->created_date));?>
								</a>
							</td>
							<td>
								<a class="text-info" href="<?=base_url()?>invoices/view/<?=$p->invoice?>">
									<?php echo Invoice::view_by_id($p->invoice)->reference_no;?>
								</a>
							</td>
							<td>
								<label class="label label-default">
									<?php echo App::get_method_by_id($p->payment_method); ?>
								</label>
							</td>
							<td>
								<strong><?php echo Applib::format_currency($cur->code, $p->amount); ?></strong>
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