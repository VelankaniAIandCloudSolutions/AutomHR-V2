<div class="row">
	<!-- Start Form -->
	<div class="col-lg-12">
		<div class="panel panel-white">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-6">
						<h3 class="panel-title"><?=lang('currencies')?></h3>
					</div>
					<div class="col-xs-6 text-right">
						<a href="<?=base_url()?>settings/add_currency" data-toggle="ajaxModal" title="<?=lang('add_currency')?>" class="btn btn-primary btn-sm"><?=lang('add_currency')?></a>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive"> 
					<div class="alert alert-info">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Notice</strong> Rates based on United States Dollar (USD)
					</div>
					<table class="table table-striped custom-table m-b-0"> 
						<thead> 
							<tr> 
								<th class="th-sortable" data-toggle="class">Code</th> 
								<th>Code Name</th> 
								<th>Symbol</th> 
								<th>xChange Rate</th> 
								<th width="30"></th> 
							</tr> 
						</thead> 
						<tbody> 
							<?php $currencies = $this->db->get('currencies')->result();
							foreach ($currencies as $key => $cur) { ?>
							<tr> 
								<td><?=$cur->code?></td> 
								<td><?=$cur->name?></td> 
								<td><?=$cur->symbol?></td> 
								<td><?=$cur->xrate?></td> 
								<td> 
									<a href="<?=base_url()?>settings/edit_currency/<?=$cur->code?>" data-toggle="ajaxModal" data-placement="left" title="<?=lang('edit_currency')?>">
										<i class="fa fa-edit text-success"></i>
									</a> 
								</td> 
							</tr> 
							<?php } ?> 
						</tbody> 
					</table> 
				</div>
			</div>
		</div>
	</div>
	<!-- End Form -->
</div>