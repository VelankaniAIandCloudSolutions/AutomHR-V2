<div class="content">
	<div class="row">
		<div class="col-sm-4 col-xs-3">
			<h4 class="page-title"><?=lang('invoices')?></h4>
		</div>
		<div class="col-sm-8 col-xs-9 text-right m-b-0">
			<?php
			if(User::is_admin() || User::perm_allowed(User::get_id(),'add_invoices')) { ?>
			<a href="<?=base_url()?>invoices/add" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?=lang('create_invoice')?></a>
			<?php } ?>
			<div class="btn-group pull-right m-r-10">
				<button class="btn btn-default">
				  <?php
				  $view = isset($_GET['view']) ? $_GET['view'] : NULL;
				  switch ($view) {
					case 'paid':
					  echo lang('paid');
					  break;
					case 'unpaid':
					  echo lang('not_paid');
					  break;
					case 'partially_paid':
					  echo lang('partially_paid');
					  break;
					case 'recurring':
					  echo lang('recurring');
					  break;

					default:
					  echo lang('filter');
					  break;
				  }
				  ?></button>
				 <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="<?=base_url()?>invoices?view=paid"><?=lang('paid')?></a></li>
					<li><a href="<?=base_url()?>invoices?view=unpaid"><?=lang('not_paid')?></a></li>
					<li><a href="<?=base_url()?>invoices?view=partially_paid"><?=lang('partially_paid')?></a></li>
					<li><a href="<?=base_url()?>invoices?view=recurring"><?=lang('recurring')?></a></li>
					<li><a href="<?=base_url()?>invoices"><?=lang('all_invoices')?></a></li>
				</ul>
			</div>
		</div>
	</div>

	
	<form id="invoice_form" method="POST">
	<div class="row filter-row">
						<div class="col-sm-3 col-xs-6">  
							<div class="form-group form-focus focused">
								<label class="control-label">From</label>
								<div class="cal-icon">
									<input class="form-control floating" id="invoice_date_from" name="invoice_date_from" value="<?php if(!empty($_POST['invoice_date_from'])){ echo $_POST['invoice_date_from'];}?>" type="text"></div>
									<label id="invoice_date_from_error" class="error display-none" for="invoice_date_from">From Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-xs-6">  
							<div class="form-group form-focus">
								<label class="control-label">To</label>
								<div class="cal-icon">
									<input class="form-control floating" id="invoice_date_to" name="invoice_date_to" type="text" value="<?php if(!empty($_POST['invoice_date_to'])){ echo $_POST['invoice_date_to'];}?>"></div>
									<label id="invoice_date_to_error" class="error display-none" for="invoice_date_to">To Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-xs-6"> 
							<div class="form-group form-focus select-focus">
								<label class="control-label">Status</label>
								<select class="select floating form-control" id="invoices_status" name="invoices_status"> 
									<option value="" selected="selected">All Invoices</option>
									<option value="Fully" <?php if(!empty($_POST['invoices_status']) && $_POST['invoices_status'] =='Fully' ){ echo 'selected';}?>>Paid</option>
									<option value="Not" <?php if(!empty($_POST['invoices_status']) && $_POST['invoices_status'] =='Not' ){ echo 'selected';}?>>Not Paid</option>
									<option value="Partial" <?php if(!empty($_POST['invoices_status']) && $_POST['invoices_status'] =='Partial' ){ echo 'selected';}?>>Partial</option>
									<option value="Recurring" <?php if(!empty($_POST['invoices_status']) && $_POST['invoices_status'] =='Recurring' ){ echo 'selected';}?>>Recurring</option>
								</select> 
								<label id="invoices_status_error" class="error display-none" for="invoices_status">Please Select a status</label>
							</div>
						</div>
						<div class="col-sm-3 col-xs-6">  
							<a href="javascript:void(0)" class="btn btn-success btn-block" id="tableinvoices_btn"> Search </a>  
						</div>     
                    </div>
				</form>
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-invoices" class="table table-striped custom-table m-b-0">
			<thead>
				<tr>
					<?php /*<th style="width:5px; display:none;"></th>
					<th style="width:5px; display:none;"></th>
					<th style="width:5px; display:none;"></th>*/?>
					<th class=""><?=lang('invoice')?></th>
					<th class=""><?=lang('company_name')?></th>
					<th class=""><?=lang('status')?></th>
					<?php if($this->session->userdata('role_id') == 1){ ?> 
					<th class="col-date"><?=lang('branch_name')?></th>
					<?php } ?>
					<th class="col-date"><?=lang('due_date')?></th>
					<th class="col-currency"><?=lang('amount')?></th>
					<th class="col-currency"><?=lang('due_amount')?></th>
					<th class="text-right">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				/*foreach ($invoices as $key => &$inv) {
						$status = Invoice::payment_status($inv->inv_id);
						switch ($status) {
							case 'fully_paid': $label2 = 'success';  break;
							case 'partially_paid': $label2 = 'warning'; break;
							case 'not_paid': $label2 = 'danger'; break;
							case 'cancelled': $label2 = 'primary'; break;
						}
					if($this->session->userdata('role_id') != 1){
					if($inv->branch_id == $this->session->userdata('branch_id')){
				?>
				<tr class="<?=($inv->status == 'Cancelled') ? 'text-danger' : '';?>">
					<td style="display:none;"><?=$inv->inv_id?></td>
					<td style="display:none;"><?php echo date('m/d/Y',strtotime($inv->due_date)); ?></td>
					<td style="display:none;"><?php
						$status1 = explode('_', $status);
						$status1 = current($status1);
					 echo ucfirst($status1); ?></td>
					<td><a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
							<?=$inv->reference_no?>
						</a></td>
					<td>
						<?php echo Client::view_by_id($inv->client)->company_name; ?>
					</td>
					<td>
						<span class="label label-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
						<?php if ($inv->recurring == 'Yes') { ?>
						<span class="label label-primary"><i class="fa fa-retweet"></i></span>
						<?php } ?>
					</td>
					<td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>
					<td class="col-currency">
						<?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id))?>
					</td>
					<td class="col-currency">
						<?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));?>
					</td>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<ul class="dropdown-menu pull-right">
								<li>
									<a href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
										<?=lang('preview_invoice')?>
									</a>
								</li>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
								<li>
									<a href="<?=base_url()?>invoices/edit/<?=$inv->inv_id?>">
										<?=lang('edit_invoice')?>
									</a>
								</li>
								<?php } ?>
								<li>
									<a href="<?=base_url()?>invoices/timeline/<?=$inv->inv_id?>">
										<?=lang('invoice_history')?>
									</a>
								</li>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
								<li>
									<a href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('email_invoice')?>">
										<?=lang('email_invoice')?>
									</a>
								</li>
								<?php } ?>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) { ?>
								<li>
									<a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('send_reminder')?>">
										<?=lang('send_reminder')?>
									</a>
								</li>
								<?php } ?>
								<?php if(config_item('pdf_engine') == 'invoicr') : ?>
								<li>
									<a href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>"><?=lang('pdf')?></a>
								</li>
								<?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
								<li>
									<a href="<?=base_url()?>invoices/pdf/<?=$inv->inv_id?>"><?=lang('pdf')?></a>
								</li>
								<?php endif; ?>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'delete_invoices')) { ?>
								<li>
									<a href="<?= base_url() ?>invoices/delete/<?= $inv->inv_id ?>" data-toggle="ajaxModal">
										<?=lang('delete_invoice')?>
									</a>
								</li>
								<?php } ?>
								<!-- <li>
									<a data-invid="<?= $inv->inv_id ?>" data-target="#clone_invoice<?= $inv->inv_id ?>" data-toggle="modal">
										Clone Invoice
									</a>
								</li> -->
							</ul>
						</div>
					</td>
				</tr>
				<?php } }else{ ?>
					<tr class="<?=($inv->status == 'Cancelled') ? 'text-danger' : '';?>">
					<td style="display:none;"><?=$inv->inv_id?></td>
					<td style="display:none;"><?php echo date('m/d/Y',strtotime($inv->due_date)); ?></td>
					<td style="display:none;"><?php
						$status1 = explode('_', $status);
						$status1 = current($status1);
					 echo ucfirst($status1); ?></td>
					<td><a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
							<?=$inv->reference_no?>
						</a></td>
					<td>
						<?php echo Client::view_by_id($inv->client)->company_name; ?>
					</td>
					<td>
						<span class="label label-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
						<?php if ($inv->recurring == 'Yes') { ?>
						<span class="label label-primary"><i class="fa fa-retweet"></i></span>
						<?php } ?>
					</td>
					<?php if($this->session->userdata('role_id') == 1){ 
						$branch_details = $this->db->get_where('branches',array('branch_id'=>$inv->branch_id))->row_array();

						?>
					<td><?php echo ucfirst($branch_details['branch_name']); ?></td>
					<?php } ?>
					<td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>
					<td class="col-currency">
						<?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id))?>
					</td>
					<td class="col-currency">
						<?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));?>
					</td>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<ul class="dropdown-menu pull-right">
								<li>
									<a href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
										<?=lang('preview_invoice')?>
									</a>
								</li>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
								<li>
									<a href="<?=base_url()?>invoices/edit/<?=$inv->inv_id?>">
										<?=lang('edit_invoice')?>
									</a>
								</li>
								<?php } ?>
								<li>
									<a href="<?=base_url()?>invoices/timeline/<?=$inv->inv_id?>">
										<?=lang('invoice_history')?>
									</a>
								</li>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
								<li>
									<a href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('email_invoice')?>">
										<?=lang('email_invoice')?>
									</a>
								</li>
								<?php } ?>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) { ?>
								<li>
									<a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('send_reminder')?>">
										<?=lang('send_reminder')?>
									</a>
								</li>
								<?php } ?>
								<?php if(config_item('pdf_engine') == 'invoicr') : ?>
								<li>
									<a href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>"><?=lang('pdf')?></a>
								</li>
								<?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
								<li>
									<a href="<?=base_url()?>invoices/pdf/<?=$inv->inv_id?>"><?=lang('pdf')?></a>
								</li>
								<?php endif; ?>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'delete_invoices')) { ?>
								<li>
									<a href="<?= base_url() ?>invoices/delete/<?= $inv->inv_id ?>" data-toggle="ajaxModal">
										<?=lang('delete_invoice')?>
									</a>
								</li>
								<?php } ?>
								<!-- <li>
									<a data-invid="<?= $inv->inv_id ?>" data-target="#clone_invoice<?= $inv->inv_id ?>" data-toggle="modal">
										Clone Invoice
									</a>
								</li> -->
							</ul>
						</div>
					</td>
				</tr>
				<?php } } */?>
			</tbody>
		</table>
		</div>
		</div>
	</div>
</div>
<?php foreach($invoices as $invoice){ ?>

<div class="modal fade" id="clone_invoice<?= $invoice->inv_id ?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Clone Invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Do you want clone this Invoice(<?= $invoice->reference_no ?>)?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button class="btn btn-primary clone_invoice_btn" data-invoice="<?= $invoice->inv_id ?>">Yes</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
		var inv_from	=	$('#invoice_date_from').val();
		var inv_to		=	$('#invoice_date_to').val();
		var inv_status	=	$('#invoices_status').val();
		$('#table-invoices').DataTable( {
		"columns": [
				{ data: "ref_no" },
				{ data: "company" },
				{ data: "status" },
				{ data: "entity" },
				{ data: "due_date" },
				{ data: "subtotal" },
				{ data: "due_amount" },
				{ data: "action" },
		],
		"bDestroy": true,
		"processing": true,
		"serverSide": true,
		"aLengthMenu": [
			//[10,25, 50, 100],
			//[10,25, 50, 100]
				[10,25, 50, 100, 200, -1],
				[10,25, 50, 100, 200, "All"]
		],
		"ajax": "<?php echo base_url().'invoices/ajax_invoice_list';?>?inv_from="+inv_from+"&inv_to="+inv_to+"&inv_status="+inv_status,
		"drawCallback": function (settings) { 
        // Here the response
        var response = settings.json;
        console.log(response);
    },
		} );
	} );
</script>