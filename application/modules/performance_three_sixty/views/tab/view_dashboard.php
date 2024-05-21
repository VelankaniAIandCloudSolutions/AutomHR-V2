<div class="row proj-summary-band">
	<div class="col-md-3 col-sm-6 text-center">
		<div class="card-box">
			<h3><?=lang('this_month')?></h3>
			<h4 class="cursor-pointer text-open small"><?=lang('payments')?></h4>
			<h4><?=Applib::format_currency($cur->code, Client::month_amount(date('Y'),date('m'),$i->co_id));?></h4>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 text-center">
		<div class="card-box">
			<h3><?=lang('balance_due')?></h3>
			<h4 class="cursor-pointer text-open small">- <?=lang('expenses')?></h4>
			<h4><?=Applib::format_currency($cur->code, $due);?></h4>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 text-center">
		<div class="card-box">
			<h3><?=lang('expense_cost')?></h3>
			<h4 class="cursor-pointer text-danger small"><?=lang('pending')?></h4>
			<h4><?=Applib::format_currency($cur->code, Expense::total_by_client($i->co_id))?></h4>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 text-center">
		<div class="card-box">
			<h3><?=lang('received_amount')?></h3>
			<h4 class="cursor-pointer text-success small"><?=lang('total_receipts')?></h4>
			<h4><?=Applib::format_currency($cur->code, Client::amount_paid($i->co_id))?></h4>
		</div>
	</div>
</div>
<div class="row row-eq-height">
	<!-- Start client details -->
	<div class="col-sm-6 row-eq-height">
		<div class="card-box card-eq-cdetails">
			<h3 class="card-title"><?=$i->company_name?> - <?=lang('details')?></h3>
			<ul class="list-group no-radius m-b-0">
				<li class="list-group-item">
					<span class="pull-right text"><?=$i->company_name?></span>
					<span class="text-muted">
						<?php echo ($i->individual == 0) ? lang('company_name') : lang('full_name');  ?>
					</span>
				</li>
				<?php if ($i->individual == 0) { ?>
				<li class="list-group-item">
					<span class="pull-right">
					<?=($i->primary_contact) ? User::displayName($i->primary_contact) : ''; ?>
					</span>
					<span class="text-muted">
						<?=lang('contact_person')?>
					</span>
				</li>
				<?php } ?>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="mailto:<?=$i->company_email?>"><?=$i->company_email?></a>
					</span>
					<span class="text-muted"><?=lang('email')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="tel:<?=$i->company_phone?>"><?=$i->company_phone?></a>
					</span>
					<span class="text-muted"><?=lang('phone')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="tel:<?=$i->company_mobile?>"><?=$i->company_mobile?></a>
					</span>
					<span class="text-muted"><?=lang('mobile_phone')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="tel:<?=$i->company_fax?>"><?=$i->company_fax?></a>
					</span>
					<span class="text-muted"><?=lang('fax')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<?=$i->VAT?>
					</span>
					<span class="text-muted"><?=lang('tax')?> <sup>No</sup></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<?=nl2br($i->company_address)?>
					</span>
					<span class="text-muted"><?=lang('address')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<?=$i->city?>
					</span>
					<span class="text-muted"><?=lang('city')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<?=$i->zip?>
					</span>
					<span class="text-muted"><?=lang('zip_code')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<?=$i->state?>
					</span>
					<span class="text-muted"><?=lang('state_province')?></span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<?=$i->country?>
					</span>
					<span class="text-muted"><?=lang('country')?></span>
				</li>
			</ul>
		</div>
	</div>
	<!-- End client details -->

	<!-- start extra fields-->
	<div class="col-sm-6 row-eq-height">
		<div class="card-eq-cfields">
		<div class="card-box">
			<h3 class="card-title"><?=lang('additional_fields')?></h3>
			<ul class="list-group no-radius m-b-0">
				<li class="list-group-item">
					<span class="pull-right text">
						<a href="<?=$i->company_website?>" class="text-info" target="_blank"><?=$i->company_website?></a>
					</span>
					<span class="text-muted">
						<?php echo lang('website');  ?>
					</span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="skype:<?=$i->skype?>?call"><?=$i->skype?></a>
					</span>
					<span class="text-muted">Skype</span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="<?=$i->linkedin?>" class="text-info" target="_blank"><?=$i->linkedin?></a>
					</span>
					<span class="text-muted">LinkedIn</span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="<?=$i->facebook?>" class="text-info" target="_blank"><?=$i->facebook?></a>
					</span>
					<span class="text-muted">Facebook</span>
				</li>
				<li class="list-group-item">
					<span class="pull-right">
						<a href="<?=$i->twitter?>" class="text-info" target="_blank"><?=$i->twitter?></a>
					</span>
					<span class="text-muted">Twitter</span>
				</li>
				<?php $custom_fields = Client::custom_fields($i->co_id); ?>
				<?php foreach ($custom_fields as $key => $f) : ?>
				<?php if($this->db->where('name',$f->meta_key)->get('fields')->num_rows() > 0): ?>
				<li class="list-group-item">
					<span class="pull-right"><?=is_json($f->meta_value) ? implode( ',',json_decode($f->meta_value)) : $f->meta_value ;?></span>
					<span class="text-muted"><?=ucfirst(humanize($f->meta_key,'-'))?></span>
				</li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="card-box m-t-3">
			<h3 class="card-title">Notes</h3>
			<?=($i->notes == '') ? 'No Notes' : nl2br_except_pre($i->notes);?>
		</div>
		</div>
	</div>
	<!-- end extra fields -->
</div>