<div class="row">
	<div class="col-lg-6">
		<div class="card-box">
			<h3 class="card-title"><?=$l->company_name?> <?=lang('details')?></h3>
			<ul class="list-group no-radius m-b-0">
				<li class="list-group-item">
					<span class="float-right text">
						<label class="badge badge-success">
							<?=Applib::format_currency($l->currency, $l->transaction_value)?>
						</label>
					</span>
					<span class="text-muted">
						<?php echo lang('lead_value');  ?>
					</span>
				</li>
				<li class="list-group-item">
					<span class="float-right text"><?=App::get_category_by_id($l->lead_stage);?></span>
					<span class="text-muted">
						<?php echo lang('lead_stage');  ?>
					</span>
				</li>
				<li class="list-group-item">
					<span class="float-right text"><?=$l->company_name?></span>
					<span class="text-muted">
						<?php echo ($l->individual == 0) ? lang('company_name') : lang('full_name');  ?>
					</span>
				</li>
				<?php if ($l->individual == 0) { ?>
				<li class="list-group-item">
					<span class="float-right">
					<?=($l->primary_contact) ? User::displayName($l->primary_contact) : ''; ?>
					</span>
					<span class="text-muted">
						<?=lang('contact_person')?>
					</span>
				</li>
				<?php } ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="mailto:<?=$l->company_email?>"><?=$l->company_email?></a>
					</span>
					<span class="text-muted"><?=lang('email')?></span>
				</li>
				<?php if($l->company_phone != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="tel:<?=$l->company_phone?>"><?=$l->company_phone?></a>
					</span>
					<span class="text-muted"><?=lang('phone')?></span>
				</li>
				<?php endif; ?>
				<?php if($l->company_mobile != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="tel:<?=$l->company_mobile?>"><?=$l->company_mobile?></a>
					</span>
					<span class="text-muted"><?=lang('mobile_phone')?></span>

				</li>
				<?php endif; ?>
				<?php if($l->company_fax != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="tel:<?=$l->company_fax?>"><?=$l->company_fax?></a>
					</span>
					<span class="text-muted"><?=lang('fax')?></span>

				</li>
				<?php endif; ?>
				<li class="list-group-item">
					<span class="float-right">
						<?=nl2br($l->company_address)?>
					</span>
					<span class="text-muted"><?=lang('address')?></span>
				</li>
				<li class="list-group-item">
					<span class="float-right">
						<?=$l->city?>
					</span>
					<span class="text-muted"><?=lang('city')?></span>

				</li>
				<?php if($l->zip != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<?=$l->zip?>
					</span>
					<span class="text-muted"><?=lang('zip_code')?></span>

				</li>
				<?php endif; ?>
				<?php if($l->state != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<?=$l->state?>
					</span>
					<span class="text-muted"><?=lang('state_province')?></span>

				</li>
				<?php endif; ?>
				<li class="list-group-item">
					<span class="float-right">
						<?=$l->country?>
					</span>
					<span class="text-muted"><?=lang('country')?></span>
				</li>
			</ul>
		</div>
	</div>
	<!-- End details C1-->

	<!-- start extra fields-->
	<div class="col-sm-6">
		<div class="card-box">
			<h3 class="card-title"><?=lang('additional_fields')?></h3>
			<?php if($l->company_website != '') : ?>
			<ul class="list-group no-radius m-b-0">
				<li class="list-group-item">
					<span class="float-right text">
						<a href="<?=$l->company_website?>" class="text-info" target="_blank"><?=$l->company_website?></a>
					</span>
					<span class="text-muted">
						<?php echo lang('website');  ?>
					</span>
				</li>
				<?php endif; ?>
				<?php if($l->skype != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="skype:<?=$l->skype?>?call"><?=$l->skype?></a>
					</span>
					<span class="text-muted">Skype</span>
				</li>
				<?php endif; ?>
				<?php if($l->linkedin != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="<?=$l->linkedin?>" class="text-info" target="_blank"><?=$l->linkedin?></a>
					</span>
					<span class="text-muted">LinkedIn</span>
				</li>
				<?php endif; ?>

				<?php if($l->facebook != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="<?=$l->facebook?>" class="text-info" target="_blank"><?=$l->facebook?></a>
					</span>
					<span class="text-muted">Facebook</span>
				</li>
				<?php endif; ?>
				<?php if($l->twitter != '') : ?>
				<li class="list-group-item">
					<span class="float-right">
						<a href="<?=$l->twitter?>" class="text-info" target="_blank"><?=$l->twitter?></a>
					</span>
					<span class="text-muted">Twitter</span>
				</li>
				<?php endif; ?>
				<?php $custom_fields = Lead::custom_fields($l->co_id); ?>
				<?php foreach ($custom_fields as $key => $f) : ?>
				<?php if($this->db->where('name',$f->meta_key)->get('fields')->num_rows() > 0): ?>
				<li class="list-group-item">
					<span class="float-right"><?=is_json($f->meta_value) ? implode( ',',json_decode($f->meta_value)) : $f->meta_value ;?></span>
					<span class="text-muted"><?=ucfirst(humanize($f->meta_key,'-'))?></span>
				</li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<!-- end extra fields -->
</div>
<div class="card-box">
	<h3 class="card-title">Description</h3>
	<div class="leads-desc"><p><?=($l->notes == '') ? 'No Notes' : nl2br_except_pre($l->notes);?></p></div>
</div>