<div class="content">
	<div class="row">
		<div class="col-sm-6 col-xs-6">
			<h4 class="page-title">Payment View</h4>
		</div>
		<div class="col-sm-6 col-xs-6 text-right m-b-0">
			<?php $i = Payment::view_by_id($id); ?>
			<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_payments')){ ?>
			<a href="<?=base_url()?>payments/edit/<?=$i->p_id?>" title="<?=lang('edit_payment')?>" class="btn btn-sm btn-success">
				<i class="fa fa-pencil text-white"></i> <?=lang('edit_payment')?>
			</a>
			<?php if($i->refunded == 'No'){ ?>
			<a href="<?=base_url()?>payments/refund/<?=$i->p_id?>" title="<?=lang('refunded')?>" class="btn btn-sm btn-info" data-toggle="ajaxModal">
				<i class="fa fa-warning text-white"></i> <?=lang('refunded')?>
			</a>
			<?php } ?>
			<?php } ?>
			<a href="<?=base_url()?>payments/pdf/<?=$i->p_id?>" title="<?=lang('pdf')?>" class="btn btn-sm btn-primary">
				<i class="fa fa-file-pdf-o text-white"></i> <?=lang('pdf')?> <?=lang('receipt')?>
			</a>
		</div>
	</div>
	<!-- Start Payment -->
	<?php if($i->refunded == 'Yes') { ?>
	<div class="alert alert-danger hidden-print">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<i class="fa fa-warning"></i> <?=lang('transaction_refunded')?>
	</div>
	<?php } ?>
	<div class="row">
		<div class="col-sm-9">
			<div class="card-box">
				<h3 class="card-title"><?=lang('payments_received')?></h3>
				<ul class="list-group no-radius m-b-0">
					<li class="list-group-item">
						<span class="text-muted"><?=lang('payment_date')?></span>
						<span class="pull-right"><?=strftime(config_item('date_format')." %H:%M:%S", strtotime($i->created_date));?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('transaction_id')?></span>
						<span class="pull-right"><?=$i->trans_id?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('received_from')?></span>
						<span class="pull-right">
							<strong>
								<a href="<?=base_url()?>companies/view/<?=$i->paid_by?>">
									<?=ucfirst(Client::view_by_id($i->paid_by)->company_name);?>
								</a>
							</strong>
						</span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('payment_mode')?></span>
						<span class="pull-right"><?=App::get_method_by_id($i->payment_method)?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('currency')?></span>
						<span class="pull-right"><?=$i->currency?></span>
					</li>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('notes')?></span>
						<span class="pull-right"><?=($i->notes) ? $i->notes : 'NULL'; ?></span>
					</li>
					<?php if($i->attached_file) : ?>
					<li class="list-group-item">
						<span class="text-muted"><?=lang('attachment')?></span>
						<span class="pull-right">
						<a href="<?=base_url()?>assets/uploads/<?=$i->attached_file?>" target="_blank">
							<?=$i->attached_file?>
						</a>
						</span>
					</li>
					<?php endif; ?>
					
				</ul>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="payment-info-box">
				<h5><?=lang('amount_received')?></h5>
				<?php $cur = Invoice::view_by_id($i->invoice)->currency; ?>
				<h3><?=Applib::format_currency($cur, $i->amount)?></h3>
			</div>
		</div>
	</div>
<div class="row">
<div class="col-md-12">
<div class="panel panel-table">
	<div class="panel-heading">
		<h3 class="panel-title"><?=lang('payment_for')?></h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped custom-table m-b-0">
			<thead>
				<tr>
					<th>
						<?=lang('invoice_code')?>
					</th>
					<th>
						<?=lang('invoice_date')?>
					</th>
					<th>
						<?=lang('due_amount')?>
					</th>
					<th>
						<?=lang('paid_amount')?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<a href="<?=base_url()?>invoices/view/<?=$i->invoice?>"><?=Invoice::view_by_id($i->invoice)->reference_no;?></a>
					</td>
					<td>
						<?=strftime(config_item('date_format'), strtotime(Invoice::view_by_id($i->invoice)->date_saved));?>
					</td>
					<td>
						<span><?=Applib::format_currency($cur, Invoice::get_invoice_due_amount($i->invoice))?> </span>
					</td>
					<td>
						<span><?=Applib::format_currency($cur, $i->amount)?></span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</div>
</div>
              <!-- End Payment -->
			
			</div>
            <!-- end -->
