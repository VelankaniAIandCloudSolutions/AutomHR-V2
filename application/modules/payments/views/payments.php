<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="page-title"><?=lang('payments')?></h4>
		</div>
	</div>
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-payments" class="table table-striped custom-table m-b-0 ">
			<thead>
				<tr>
					<?php /*<th style="width:5px; display:none;"></th>*/?>
					<th class=""><?=lang('invoice')?></th>
					<th class=""><?=lang('client')?></th>
					<th class="col-date"><?=lang('payment_date')?></th>
					<th class="col-date"><?=lang('invoice_date')?></th>
					<th class="col-currency"><?=lang('amount')?></th>
					<th class=""><?=lang('payment_method')?></th>
					<th class="">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach ($payments as $key => $p) { if($p->branch_id == $this->session->userdata('branch_id')){   ?>
				<tr>
				<?php
				$currency = Invoice::view_by_id($p->invoice)->currency;
				$invoice_date = Invoice::view_by_id($p->invoice)->date_saved;
				$invoice_date = strftime(config_item('date_format'), strtotime($invoice_date));
				?>
					<td style="display:none;"><?=$p->p_id?></td>
					<td>
						<a class="text-info" href="<?=base_url()?>payments/view/<?=$p->p_id?>">
							<?php echo Invoice::view_by_id($p->invoice)->reference_no; ?>
						</a>
					</td>
					<td>
						<?php echo Client::view_by_id($p->paid_by)->company_name; ?>
					</td>
					<td><?=strftime(config_item('date_format'), strtotime($p->payment_date));?></td>
					<td><?=$invoice_date?></td>
					<td class="col-currency <?=($p->refunded == 'Yes') ? 'text-lt text-danger' : '' ; ?>">
						<strong><?=Applib::format_currency($currency, $p->amount)?></strong>
					</td>
					<td><?php echo App::get_method_by_id($p->payment_method); ?></td>
					<td class="text-right">
						<div class="dropdown">
							<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
							<ul class="dropdown-menu pull-right">                      
								<li>
									<a href="<?=base_url()?>payments/view/<?=$p->p_id?>"><?=lang('view_payment')?></a>
								</li>
								<li>
									<a href="<?=base_url()?>payments/pdf/<?=$p->p_id?>">
										<?=lang('pdf')?> <?=lang('receipt')?>
									</a>
								</li>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_payments')){ ?>
								<li><a href="<?=base_url()?>payments/edit/<?=$p->p_id?>"><?=lang('edit_payment')?></a></li>
								<?php if($p->refunded == 'No'){ ?>
								<li>
									<a href="<?=base_url()?>payments/refund/<?=$p->p_id?>" data-toggle="ajaxModal">
										<?=lang('refunded')?>
									</a>
								</li>
								<?php } ?>
								<li>
									<a href="<?=base_url()?>payments/delete/<?=$p->p_id?>" data-toggle="ajaxModal"><?=lang('delete_payment')?></a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</td>
				</tr>
				<?php } }*/ ?>
			</tbody>
		</table>
		</div>
		</div>
	</div>
</div>
<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
		$('#table-payments').DataTable( {
		"columns": [
				{ data: "ref_no" },
				{ data: "company" },
				{ data: "payment_date" },
				{ data: "invoice_date" },
				{ data: "amount" },
				{ data: "pay_method" },
				{ data: "action" }
		],
		"paging": true,
		"searching":false,
		"bDestroy": true,
		"processing": true,
		"serverSide": true,
		"aLengthMenu": [
				[10,25, 50, 100, 200, -1],
				[10,25, 50, 100, 200, "All"]
		],
		"ajax": "<?php echo base_url().'payments/ajax_payments';?>"
		} );
	} );
</script>