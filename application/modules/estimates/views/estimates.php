<div class="content">
	<div class="row">
		<div class="col-sm-4 col-xs-3">
			<h4 class="page-title"><?=lang('estimates')?></h4>
		</div>
		<div class="col-sm-8 col-xs-9 text-right m-b-0">
			<?php
			if(User::is_admin() || User::perm_allowed(User::get_id(),'add_estimates')) { ?> 
			<a href="<?=base_url()?>estimates/add" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?=lang('create_estimate')?></a>
			<?php } ?>
		</div>
	</div>
	<form id="estimate_form"  method="POST">
	<div class="row filter-row">
						<div class="col-sm-3 col-xs-6">  
							<div class="form-group form-focus focused">
								<label class="control-label">From</label>
								<div class="cal-icon"><input class="form-control floating" type="text" id="estimates_from" name="estimates_from" value="<?php if(!empty($_POST['estimates_from'])){ echo $_POST['estimates_from'];}?>"></div>
								<label id="estimates_from_error" class="error display-none" for="estimates_from">From Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-xs-6">  
							<div class="form-group form-focus">
								<label class="control-label">To</label>
								<div class="cal-icon"><input class="form-control floating" id="estimates_to" name="estimates_to" type="text" value="<?php if(!empty($_POST['estimates_to'])){ echo $_POST['estimates_to'];}?>"></div>
								<label id="estimates_to_error" class="error display-none" for="estimates_to">To Date Shouldn't be empty</label>
							</div>
						</div>
						<div class="col-sm-3 col-xs-6"> 
							<div class="form-group form-focus select-focus">
								<label class="control-label">Status</label>
								<select class="select floating form-control" id="estimates_status" name="estimates_status"> 
									<option value="" selected="selected">All Status</option>
									<option value="Pending" <?php if(!empty($_POST['estimates_status']) && $_POST['estimates_status']=='Pending'){ echo 'selected';}?>>Pending</option>
									<option value="Accepted" <?php if(!empty($_POST['estimates_status']) && $_POST['estimates_status']=='Accepted'){ echo 'selected';}?>>Accepted</option>
								</select> 
								<label id="estimates_status_error" class="error display-none" for="estimates_status">Please Select a status</label>
							</div>
						</div>
						<div class="col-sm-3 col-xs-6">  
				<a href="javascript:void(0)" class="btn btn-success btn-block" id="search_estimates_btn"> Search </a>  
						</div>     
                    </div>
			</form>

	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table id="table-estimates" class="table table-striped custom-table m-b-0">
			<thead>
				<tr>
					<?php /*<th style="width:5px; display:none;"></th>
					<th style="width:5px; display:none;"></th>*/?>
					<th class=""><?=lang('estimate')?></th>
					<th class=""><?=lang('client_name')?></th>
					<th class=""><?=lang('status')?></th>
					<th class="col-date"><?=lang('due_date')?></th>
					<th class="col-date"><?=lang('created')?></th>
					<th class="col-currency"><?=lang('amount')?></th>
					<th class="text-right">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach ($estimates as $key => $e) {
					$label = 'danger';
					if ($e->status == 'Pending'){ $label = "info"; }
					if($e->status == 'Accepted') { $label = "success";  }
				?>
				<tr>
					<td style="display:none;"><?=$e->est_id?></td>
					<td style="display:none;"><?=date('m/d/Y',strtotime($e->due_date));?></td>
					<td>
						<a class="text-info" href="<?=base_url()?>estimates/view/<?=$e->est_id?>">
							<?=$e->reference_no?>
						</a>
					</td>
					<td>
						<?php echo Client::view_by_id($e->client)->company_name; ?>
					</td>
					<td><span class="label label-<?=$label?>"><?=lang(strtolower($e->status))?> <?php if($e->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span></td>
					<td><?=strftime(config_item('date_format'), strtotime($e->due_date))?></td>
					<td><?=strftime(config_item('date_format'), strtotime($e->date_saved))?></td>
					<td class="col-currency">
						<?=Applib::format_currency($e->currency, Estimate::due($e->est_id));?>
					</td>
					<td class="text-right">
						<div class="dropdown">
							<a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
							<ul class="dropdown-menu pull-right">
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_estimates')) { ?>  
								<li><a href="<?=base_url()?>estimates/edit/<?=$e->est_id?>"><?=lang('edit_estimate')?></a></li>
								<li><a href="<?=base_url()?>estimates/timeline/<?=$e->est_id?>"><?=lang('estimate_history')?></a></li>
								<?php } ?>
								<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'view_all_estimates')) { ?>  
								<li>
								<a href="<?=base_url()?>estimates/email/<?=$e->est_id?>" data-toggle="ajaxModal" 
								title="<?=lang('email_estimate')?>"><?=lang('email_estimate')?></a>
								</li>
								<?php } ?>
								<li>
								<a href="<?=base_url()?>estimates/view/<?=$e->est_id?>"><?=lang('view_estimate')?></a>
								</li>
								<?php if (config_item('pdf_engine') == 'invoicr') : ?>
								<li><a href="<?=base_url()?>fopdf/estimate/<?=$e->est_id?>"><?=lang('pdf')?></a></li>
								<?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
								<li><a href="<?=base_url()?>estimates/pdf/<?=$e->est_id?>"><?=lang('pdf')?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					</td>
				</tr>
				<?php }*/ ?>
			</tbody>
		</table>
		</div>
		</div>
	</div>
</div>
<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
		var estimates_from	=	$('#estimates_from').val();
		var estimates_to	=	$('#estimates_to').val();
		var estimates_status=	$('#estimates_status').val();
		$('#table-estimates').DataTable( {
		"columns": [
				{ data: "ref_no" },
				{ data: "company" },
				{ data: "status" },
				{ data: "due_date" },
				{ data: "save_date" },
				{ data: "currency" },
				{ data: "action" },
		],
		"bDestroy": true,
		"processing": true,
		"serverSide": true,
		"aLengthMenu": [
			[10,25, 50, 100, 200, -1],
			[10,25, 50, 100, 200, "All"]
		],
		"ajax": "<?php echo base_url().'estimates/ajax_estimates';?>?estimates_from="+estimates_from+"&estimates_to="+estimates_to+"&estimates_status="+estimates_status
		} );
	} );
</script>