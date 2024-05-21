<?php $inv = Invoice::view_by_id($id); ?>
<header class="header-fixed clearfix hidden-print">
	<div class="row">
		<div class="col-sm-8">
			<a href="<?=site_url()?>invoices/view/<?=$inv->inv_id?>" class="btn btn-sm btn-success">
				<?=lang('view_invoice')?>
			</a>
			<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
				<?php if ($inv->show_client == 'Yes') { ?>
					<a class="btn btn-sm btn-danger" href="<?= base_url() ?>invoices/hide/<?= $inv->inv_id ?>" data-toggle="tooltip" data-title="<?= lang('hide_to_client') ?>" data-placement="bottom"><i class="fa fa-eye-slash"></i>
					</a>

				<?php } else { ?>

					<a class="btn btn-sm btn-success" href="<?= base_url() ?>invoices/show/<?= $inv->inv_id ?>" data-toggle="tooltip" data-title="<?= lang('show_to_client') ?>" data-placement="bottom"><i class="fa fa-eye"></i>
					</a>
				<?php } ?>
			<?php } ?>

			<?php if (Invoice::payment_status($inv->inv_id) != 'fully_paid') : ?>
				<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'pay_invoice_offline')) { ?>
				<?php } else { if ($inv->allow_paypal == 'Yes') { ?>
					<a class="btn btn-sm btn-<?=config_item('theme_color')?>" href="<?= base_url() ?>paypal/pay/<?= $inv->inv_id ?>" data-toggle="ajaxModal"
					   title="<?= lang('via_paypal') ?>"><?= lang('via_paypal') ?></a>

				<?php } if ($inv->allow_2checkout == 'Yes') { ?>
					<a class="btn btn-sm btn-<?=config_item('theme_color')?>" href="<?= base_url() ?>checkout/pay/<?= $inv->inv_id ?>" data-toggle="ajaxModal" title="<?= lang('via_2checkout') ?>"><?= lang('via_2checkout') ?></a>
				<?php } if ($inv->allow_stripe == 'Yes') { ?>
					<button id="customButton" class="btn btn-sm btn-<?=config_item('theme_color')?>" ><?=lang('via_stripe')?></button>
				<?php } if ($inv->allow_bitcoin == 'Yes') { ?>
					<a class="btn btn-sm btn-<?=config_item('theme_color')?>" href="<?= base_url() ?>bitcoin/pay/<?= $inv->inv_id ?>" data-toggle="ajaxModal" title="<?= lang('via_bitcoin') ?>"><?= lang('via_bitcoin') ?></a>
				<?php }
				} ?>
			<?php endif; ?>

			<div class="btn-group">
				<button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown">
					<?= lang('more_actions') ?>
					<span class="caret"></span></button>
				<ul class="dropdown-menu">
					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
						<li>
							<a href="<?= base_url() ?>invoices/send_invoice/<?= $inv->inv_id ?>" data-toggle="ajaxModal" title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
						</li>
					<?php } if (User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) { ?>
						<li>
							<a href="<?= base_url() ?>invoices/remind/<?= $inv->inv_id ?>" data-toggle="ajaxModal" title="<?= lang('send_reminder') ?>"><?= lang('send_reminder') ?></a>
						</li>
					<?php } ?>
					<li>
						<a href="<?= base_url() ?>invoices/timeline/<?= $inv->inv_id ?>">
							<?=lang('invoice_history') ?></a>
					</li>
					<!-- <li>
						<a href="<?= base_url() ?>invoices/transactions/<?= $inv->inv_id ?>">
							<?= lang('payments') ?>
						</a>
					</li> -->
				</ul>
			</div>

			<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
				<a href="<?= base_url() ?>invoices/edit/<?=$inv->inv_id?>" class="btn btn-sm btn-success" data-original-title="<?=lang('edit_invoice')?>" data-toggle="tooltip" data-placement="bottom">
					<i class="fa fa-pencil"></i>
				</a>
			<?php } ?>

			<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_invoices')) { ?>
				<a href="<?= base_url() ?>invoices/delete/<?= $inv->inv_id ?>" class="btn btn-sm btn-danger" title="<?=lang('delete_invoice')?>" data-toggle="ajaxModal">
					<i class="fa fa-trash"></i>
				</a>
			<?php } ?>
		</div>
		<div class="col-sm-3 pull-right">
			<?php if (config_item('pdf_engine') == 'invoicr') : ?>
				<a href="<?= base_url() ?>fopdf/invoice/<?=$inv->inv_id ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf') ?></a>
			<?php elseif (config_item('pdf_engine') == 'mpdf') : ?>
				<a href="<?= base_url() ?>invoices/pdf/<?=$inv->inv_id ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf') ?></a>
			<?php endif; ?>
		</div>
	</div>
</header>
<!-- Start -->
<div class="content">
				<div class="row">
                    <div class="col-xs-12">
                        <h4 class="page-title"><?=lang('invoice')?> <?=$inv->reference_no?> <?=lang('payments')?></h4>
                    </div>
                </div>
                        <!-- Payments Start -->
						<div class="row">
                   		 <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="table-payments" class="table table-striped custom-table m-b-0 AppendDataTables">
                                        <thead>
                                        <tr>
                                            <th class="col-options no-sort  col-sm-2"><?=lang('trans_id')?></th>
                                            <th class="col-sm-3"><?=lang('client')?></th>
                                            <th class="col-date col-sm-2"><?=lang('payment_date')?></th>
                                            <th class="col-currency col-sm-2"><?=lang('amount')?></th>
                                            <th class="col-sm-2"><?=lang('payment_method')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($payments as $key => $p) { ?>


                                            <tr>

                                                <td>

                                                    <a href="<?=base_url()?>payments/view/<?=$p->p_id?>" class="text-info">
                                                        <?=$p->trans_id?>
                                                    </a>
                                                </td>




                                                <td>
                                                    <?php echo Client::view_by_id($p->paid_by)->company_name; ?>
                                                </td>


                                                <td><?=strftime(config_item('date_format'), strtotime($p->payment_date));?></td>


                                                <td class="col-currency"><?=Applib::format_currency($inv->currency, $p->amount)?></td>


                                                <td><?php echo App::get_method_by_id($p->payment_method); ?>
                                                </td>


                                            </tr>


                                        <?php } ?>


                                        </tbody>
                                    </table>
								</div>
							</div>
						</div>
			</div>
<?php if (!User::is_admin() && $inv->allow_stripe == 'Yes') { ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
<?php } ?>
<!-- end -->
