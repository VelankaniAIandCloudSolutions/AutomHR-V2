                <header class="header-fixed clearfix hidden-print p-0">
                    <div class="row">
						<div class="col-md-12 m-t-8 m-l-2">
                            <?php $inv = Invoice::view_by_id($id); ?>
                            <a href="#" class="btn btn-sm btn-primary" onClick="window.print();"><i class="fa fa-print"></i></a>
                            <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) : ?>
							<a href="<?= base_url() ?>invoices/items/insert/<?=$inv->inv_id ?>" title="<?= lang('item_quick_add') ?>" class="btn btn-sm btn-info" data-toggle="ajaxModal">
								<i class="fa fa-list-alt text-white"></i> <?= lang('items') ?>
							</a>
							<?php if ($inv->show_client == 'Yes') { ?>
							<a class="btn btn-sm btn-success" href="<?= base_url() ?>invoices/hide/<?=$inv->inv_id ?>" data-toggle="tooltip" data-original-title="<?=lang('hide_to_client') ?>" data-placement="bottom">
								<i class="fa fa-eye-slash"></i>
							</a>
							<?php } else { ?>
							<a class="btn btn-sm btn-danger" href="<?= base_url() ?>invoices/show/<?= $inv->inv_id ?>" data-toggle="tooltip" data-original-title="<?= lang('show_to_client') ?>" data-placement="bottom">
								<i class="fa fa-eye"></i>
							</a>
							<?php } ?>

                            <?php endif; ?>

                            <?php if (Invoice::get_invoice_due_amount($inv->inv_id) > 0) : ?>

                                <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'pay_invoice_offline')
                                    && (Invoice::get_invoice_due_amount($inv->inv_id) > 0) ) { ?>

                                    <a class="btn btn-sm btn-<?=config_item('theme_color');?>"
                                       href="<?=base_url()?>invoices/pay/<?=$inv->inv_id?>" data-toggle="tooltip"
                                       data-original-title="<?=lang('pay_invoice')?>" data-placement="bottom">
                                        <i class="fa fa-credit-card"></i>
                                    </a>

                                <?php } else { ?>

                                    <?php if ($inv->allow_paypal == 'Yes') : ?>

                                        <a class="btn btn-sm btn-<?=config_item('theme_color')?>"
                                           href="<?=base_url() ?>paypal/pay/<?=$inv->inv_id ?>" data-toggle="ajaxModal"
                                           title="<?=lang('via_paypal')?>"><?= lang('via_paypal') ?>
                                        </a>

                                    <?php endif; ?>

                                    <?php if ($inv->allow_braintree == 'Yes') : ?>

                                        <a class="btn btn-sm btn-<?=config_item('theme_color')?>"
                                           href="<?=base_url() ?>braintree/pay/<?=$inv->inv_id ?>" data-toggle="ajaxModal"
                                           title="<?=lang('via_braintree')?>"><?= lang('via_braintree') ?>
                                        </a>

                                    <?php endif; ?>

                                    <?php if ($inv->allow_2checkout == 'Yes') : ?>

                                        <a class="btn btn-sm btn-<?=config_item('theme_color')?>"
                                           href="<?= base_url() ?>checkout/pay/<?=$inv->inv_id?>"
                                           data-toggle="ajaxModal" title="<?= lang('via_2checkout') ?>">
                                            <?= lang('via_2checkout') ?>
                                        </a>

                                    <?php endif; ?>
                                    <?php if ($inv->allow_stripe == 'Yes') : ?>

                                        <button id="customButton" class="btn btn-sm btn-<?=config_item('theme_color')?>"><?=lang('via_stripe')?>
                                        </button>

                                    <?php endif; ?>
                                    <?php if ($inv->allow_bitcoin == 'Yes') : ?>

                                        <a class="btn btn-sm btn-<?=config_item('theme_color')?>"
                                           href="<?= base_url() ?>bitcoin/pay/<?= $inv->inv_id ?>" data-toggle="ajaxModal"
                                           title="<?= lang('via_bitcoin') ?>"><?= lang('via_bitcoin') ?>
                                        </a>


                                    <?php endif; ?>
                                <?php } ?>
                            <?php endif; ?>

                            <?php if ($inv->recurring == 'Yes') { ?>
                                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')){ ?>


                                    <a class="btn btn-sm btn-warning" href="<?= base_url() ?>invoices/stop_recur/<?=$inv->inv_id?>"
                                       title="<?= lang('stop_recurring') ?>" data-toggle="ajaxModal">
                                        <i class="fa fa-retweet"></i> <?= lang('stop_recurring') ?>
                                    </a>

                                <?php }  } ?>


                            <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>

                                <a href="<?= base_url() ?>invoices/edit/<?=$inv->inv_id?>"
                                   class="btn btn-sm btn-success" data-original-title="<?=lang('edit_invoice')?>"
                                   data-toggle="tooltip" data-placement="bottom"><i class="fa fa-pencil"></i>
                                </a>


                            <?php } if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_invoices')) { ?>

                                <a href="<?= base_url() ?>invoices/delete/<?=$inv->inv_id?>" class="btn btn-sm btn-danger"
                                   title="<?=lang('delete_invoice')?>" data-toggle="ajaxModal"><i class="fa fa-trash"></i>
                                </a>

                            <?php } ?>

                            <?php if (($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4)){ ?>

                                <a data-invid="<?= $inv->inv_id ?>" data-target="#clone_invoice<?= $inv->inv_id ?>" data-toggle="modal" class="btn btn-sm btn-info" title="Invoice Clone" >Clone</a>
                            <?php } ?>

                            <div class="btn-group">

                                <button class="btn btn-sm btn-info dropdown-toggle"
                                        data-toggle="dropdown"><?= lang('more_actions') ?> <span class="caret"></span>
                                </button>

                                <ul class="dropdown-menu">

                                    <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')): ?>

                                        <li>
                                            <a href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" data-toggle="ajaxModal"
                                               title="<?= lang('email_invoice') ?>"><?=lang('email_invoice')?>
                                            </a>
                                        </li>
                                    <?php endif; ?>


                                    <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) : ?>

                                        <li>
                                            <a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-toggle="ajaxModal"
                                               title="<?=lang('send_reminder')?>"><?= lang('send_reminder') ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <a href="<?= base_url() ?>invoices/timeline/<?= $inv->inv_id ?>">
                                            <?= lang('invoice_history') ?>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="<?= base_url() ?>invoices/transactions/<?= $inv->inv_id ?>">
                                            <?= lang('payments') ?>
                                        </a>
                                    </li>

                                    <?php if(User::is_admin() && Invoice::get_invoice_due_amount($inv->inv_id) > 0) : ?>

                                        <li>
                                            <a href="<?=base_url() ?>invoices/mark_as_paid/<?=$inv->inv_id?>" data-toggle="ajaxModal">
                                                <?=lang('mark_as_paid') ?>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?=base_url() ?>invoices/cancel/<?=$inv->inv_id?>" data-toggle="ajaxModal">
                                                <?=lang('cancelled') ?>
                                            </a>
                                        </li>

                                    <?php endif; ?>
                                </ul>
                            </div>


                            <?php if (config_item('pdf_engine') == 'invoicr') : ?>
                                <a href="<?= base_url() ?>fopdf/invoice/<?= $inv->inv_id ?>" class="btn btn-sm btn-primary pull-right m-r-2">
                                    <i class="fa fa-file-pdf-o"></i> <?=lang('pdf') ?>
                                </a>


                            <?php elseif (config_item('pdf_engine') == 'mpdf') : ?>
                                <a href="<?= base_url() ?>invoices/pdf/<?= $inv->inv_id ?>" class="btn btn-sm btn-primary pull-right m-r-2">
                                    <i class="fa fa-file-pdf-o"></i> <?=lang('pdf') ?>
                                </a>

                            <?php endif; ?>


                        </div>
                    </div>
                </header>

<div class="padding-2p">
                    <!-- Start Display Details -->
                    <?php if($inv->status != 'Cancelled') : ?>
                    <?php
                    if (!$this->session->flashdata('message')) :
                        if (strtotime($inv->due_date) < time() && Invoice::get_invoice_due_amount($inv->inv_id) > 0) :
                            ?>
                            <div class="alert alert-warning hidden-print">
                                <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
                                <?= lang('invoice_overdue') ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-danger hidden-print">
                        <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
                        This Invoice is Cancelled!
                    </div>

                <?php endif; ?>

                    <div class="panel inv-panel">
					                <?php if(Invoice::payment_status($inv->inv_id) == 'fully_paid'){ ?>
<div id="ember2686" disabled="false" class="ribbon ember-view popovercontainer" data-original-title="" title="">  <div class="ribbon-inner ribbon-success">
    <?=lang('paid')?>
  </div>
</div>
<?php } ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-3 col-sm-6">
                                <div style="height: <?=config_item('invoice_logo_height')?>px">
                                    <img class="ie-logo" src="<?= base_url() ?>assets/images/logos/<?= config_item('invoice_logo') ?>" style="height: <?=config_item('invoice_logo_height')?>px">
                                </div>
                            </div>
                            <div class="col-xs-9 col-sm-6 text-right" style="color: #817d7d;">
								<h3 class="text-uppercase"><?=$inv->reference_no?> <?php if ($inv->recurring == 'Yes') { ?>
                                        <span class="label bg-danger"><i class="fa fa-retweet"></i>
                                            <?=$inv->recur_frequency?> </span>
                                    <?php } ?></h3>


                                <div>
                                    <?=lang('invoice_date')?>
                                    <span class="col-xs-5 no-gutter-right pull-right">
                                    <strong>
                                        <?=strftime(config_item('date_format'), strtotime($inv->date_saved)); ?>
                                    </strong>
                                    </span>
                                </div>

                                <?php if ($inv->recurring == 'Yes') { ?>
                                    <div>
                                        <?= lang('recur_next_date') ?>
                                        <span class="col-xs-5 no-gutter-right pull-right">
                                    <strong>
                                        <?=strftime(config_item('date_format'), strtotime($inv->recur_next_date)); ?>
                                    </strong>
                                    </span>
                                    </div>
                                <?php } ?>

                                <div>
                                    <?= lang('payment_due') ?>
                                    <span class="col-xs-5 no-gutter-right pull-right">
                                        <strong>
                                            <?=strftime(config_item('date_format'), strtotime($inv->due_date)); ?>
                                        </strong>
                                        </span>
                                </div>


                                <div>
                                    <?= lang('payment_status') ?>
                                    <span class="col-xs-5 no-gutter-right pull-right">
                                        <span class="label bg-success">
                                        <?=lang(Invoice::payment_status($inv->inv_id))?>
                                        </span></span>
                                </div>
                            </div>
                        </div>
                        <div class="m-t">
                            <div class="row">

                                <?php if (config_item('swap_to_from') == 'FALSE') { ?>

                                    <div class="col-sm-6 col-md-6 col-lg-3 m-b-20">
                                        <strong><?= lang('received_from') ?>:</strong>
                                        <?php $l = Client::view_by_id($inv->client)->language; ?>

                                        <h4>
                                            <?=(config_item('company_legal_name_' . $l)
                                                ? config_item('company_legal_name_' . $l)
                                                : config_item('company_legal_name'))
                                            ?>
                                        </h4>
                                        <p>

                                            <span class="col-xs-3 no-gutter"><?= lang('address') ?>:</span>

                                <span class="col-xs-9 no-gutter">
                                <?=(config_item('company_address_' . $l)
                                    ? config_item('company_address_' . $l)
                                    : config_item('company_address'))
                                ?><br>

                                    <?=(config_item('company_city_' . $l)
                                        ? config_item('company_city_' . $l)
                                        : config_item('company_city'))
                                    ?>

                                    <?php if (config_item('company_zip_code_' . $l) != '' || config_item('company_zip_code') != '') : ?>
                                        , <?=(config_item('company_zip_code_' . $l)
                                            ? config_item('company_zip_code_' . $l)
                                            : config_item('company_zip_code'))
                                        ?>
                                    <?php endif; ?><br>

                                    <?php if (config_item('company_state_' . $l) != '' || config_item('company_state') != '') : ?>

                                        <?=(config_item('company_state_' . $l)
                                            ? config_item('company_state_' . $l)
                                            : config_item('company_state')) ?>,
                                    <?php endif; ?>

                                    <?=(config_item('company_country_' . $l)
                                        ? config_item('company_country_' . $l)
                                        : config_item('company_country')) ?>
                            </span>

                                            <span class="col-xs-3 no-gutter"><?= lang('phone') ?>:</span>
                                <span class="col-xs-9 no-gutter">
                                <a href="tel:<?= (config_item('company_phone_' . $l)
                                    ? config_item('company_phone_' . $l)
                                    : config_item('company_phone')) ?>">

                                    <?=(config_item('company_phone_' . $l)
                                        ? config_item('company_phone_' . $l)
                                        : config_item('company_phone')) ?>
                                </a>

                                    <?php if (config_item('company_phone_2_'.$l) != '' || config_item('company_phone_2') != '') : ?>
                                        , <a href="tel:<?= (config_item('company_phone_2_' . $l)
                                            ? config_item('company_phone_2_' . $l)
                                            : config_item('company_phone_2')) ?>">

                                            <?=(config_item('company_phone_2_' . $l)
                                                ? config_item('company_phone_2_' . $l)
                                                : config_item('company_phone_2')) ?>
                                        </a>
                                    <?php endif; ?>
                            </span>

                                            <?php if (config_item('company_fax_'.$l) != '' || config_item('company_fax') != '') : ?>
                                                <span class="col-xs-3 no-gutter"><?= lang('fax') ?>:</span>
                                                <span class="col-xs-9 no-gutter">
                                <a href="tel:<?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?>">
                                    <?=(config_item('company_fax_' . $l)
                                        ? config_item('company_fax_' . $l)
                                        : config_item('company_fax')) ?>
                                </a>
                            </span>
                                            <?php endif; ?>
                                            <?php if (config_item('company_registration_'.$l) != '' || config_item('company_registration') != '') : ?>
                                                <span class="col-xs-3 no-gutter"><?= lang('company_registration') ?>:</span>
                                                <span class="col-xs-9 no-gutter">

                                <a href="tel:<?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?>">
                                    <?=(config_item('company_registration_' . $l)
                                        ? config_item('company_registration_' . $l)
                                        : config_item('company_registration')) ?>
                                </a>
                            </span>
                                            <?php endif; ?>
                                            <?php if (config_item('company_vat_'.$l) != '' || config_item('company_vat') != '') : ?>

                                            <span class="col-xs-3 no-gutter"><?=lang('company_vat')?>:</span>

                            <span class="col-xs-9 no-gutter">
                            <?=(config_item('company_vat_' . $l)
                                ? config_item('company_vat_' . $l)
                                : config_item('company_vat')) ?><br>
                            <span>

                            <?php endif; ?>
                                        </p>
                                    </div>
                                <?php } ?>

                                <div class="col-sm-6 col-md-6 col-lg-9 m-b-20">
                                    <strong><?= lang('bill_to') ?>:</strong>
                                    <h4><?=Client::view_by_id($inv->client)->company_name;?> <br></h4>
                                    <p>
                                        <span class="col-xs-3 no-gutter"><?=lang('address')?>:</span>
                                            <span class="col-xs-9 no-gutter">
                                        <?=Client::view_by_id($inv->client)->company_address?><br>
                                                <?=Client::view_by_id($inv->client)->city;?>
                                                <?php if (Client::view_by_id($inv->client)->zip != '') {
                                                    echo ", ".Client::view_by_id($inv->client)->zip;  } ?><br>

                                                <?php if (Client::view_by_id($inv->client)->state != '') {
                                                    echo Client::view_by_id($inv->client)->state.", ";  } ?>
                                                <?=Client::view_by_id($inv->client)->country; ?>
                                    </span>
                                        <span class="col-xs-3 no-gutter"><?=lang('phone')?>:</span>
                                            <span class="col-xs-9 no-gutter">
                                        <a href="tel:<?=Client::view_by_id($inv->client)->company_phone;?>">
                                            <?=ucfirst(Client::view_by_id($inv->client)->company_phone) ?>
                                        </a>&nbsp;
                                    </span>

                                        <?php if (Client::view_by_id($inv->client)->company_fax != '') : ?>
                                            <span class="col-xs-3 no-gutter"><?=lang('fax')?>:</span>
                                            <span class="col-xs-9 no-gutter">
                                        <a href="tel:<?=Client::view_by_id($inv->client)->company_fax;?>">
                                            <?=ucfirst(Client::view_by_id($inv->client)->company_fax) ?>
                                        </a>&nbsp;
                                    </span>
                                        <?php endif; ?>

                                        <?php if(Client::view_by_id($inv->client)->VAT != ''){ ?>
                                            <span class="col-xs-3 no-gutter"><?=lang('company_vat')?>:</span>
                                            <span class="col-xs-9 no-gutter">
                                            <?=Client::view_by_id($inv->client)->VAT;?>
                                    </span>
                                        <?php } ?>

                                    </p>

                                    <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                                        <?php if(Expense::total_by_client($inv->client) > 0) { ?>
                                            <span class="text-info hidden-print">
    <a href="<?=site_url()?>invoices/add_expenses/<?=$inv->client?>/<?=$inv->inv_id?>" class="btn btn-xs btn-danger" data-toggle="ajaxModal"><?=lang('expenses_available')?></a>
    </span>

                                        <?php } ?>
                                    <?php } ?>
                                </div>

                                <?php if (config_item('swap_to_from') == 'TRUE') { ?>

                                    <div class="col-sm-6 col-md-6 col-lg-3 m-b-20">
                                        <strong><?= lang('received_from') ?>:</strong>
                                        <?php $l = Client::view_by_id($inv->client)->language; ?>
                                        <h4><?= (config_item('company_legal_name_' . $l)
                                                ? config_item('company_legal_name_' . $l)
                                                : config_item('company_legal_name')) ?>
                                        </h4>
                                        <p>
                                            <span class="col-xs-3 no-gutter"><?= lang('address') ?>:</span>
                            <span class="col-xs-9 no-gutter">
                                <?= (config_item('company_address_' . $l) ? config_item('company_address_' . $l) : config_item('company_address')) ?><br>

                                <?= (config_item('company_city_' . $l) ? config_item('company_city_' . $l) : config_item('company_city')) ?>

                                <?php if (config_item('company_zip_code_' . $l) != '' || config_item('company_zip_code') != '') : ?>
                                    , <?= (config_item('company_zip_code_' . $l) ? config_item('company_zip_code_' . $l) : config_item('company_zip_code')) ?>
                                <?php endif; ?><br>

                                <?php if (config_item('company_state_' . $l) != '' || config_item('company_state') != '') : ?>
                                    <?= (config_item('company_state_' . $l) ? config_item('company_state_' . $l) : config_item('company_state')) ?>,
                                <?php endif; ?>

                                <?= (config_item('company_country_' . $l) ? config_item('company_country_' . $l) : config_item('company_country')) ?>
                            </span>

                                            <span class="col-xs-3 no-gutter"><?= lang('phone') ?>:</span>
                            <span class="col-xs-9 no-gutter">
                                <a href="tel:<?= (config_item('company_phone_' . $l) ? config_item('company_phone_' . $l) : config_item('company_phone')) ?>">
                                    <?= (config_item('company_phone_' . $l) ? config_item('company_phone_' . $l) : config_item('company_phone')) ?></a>

                                <?php if (config_item('company_phone_2_'.$l) != '' || config_item('company_phone_2') != '') : ?>
                                    , <a href="tel:<?= (config_item('company_phone_2_' . $l) ? config_item('company_phone_2_' . $l) : config_item('company_phone_2')) ?>">
                                        <?= (config_item('company_phone_2_' . $l) ? config_item('company_phone_2_' . $l) : config_item('company_phone_2')) ?></a>
                                <?php endif; ?>
                            </span>

                                            <?php if (config_item('company_fax_'.$l) != '' || config_item('company_fax') != '') : ?>

                                                <span class="col-xs-3 no-gutter"><?= lang('fax') ?>:</span>
                                                <span class="col-xs-9 no-gutter">
                                <a href="tel:<?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?>">
                                    <?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?></a>
                            </span>
                                            <?php endif; ?>

                                            <?php if (config_item('company_registration_'.$l) != '' || config_item('company_registration') != '') : ?>
                                                <span class="col-xs-3 no-gutter"><?= lang('company_registration') ?>:</span>
                                                <span class="col-xs-9 no-gutter">
                                <a href="tel:<?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?>">
                                    <?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?></a>
                            </span>
                                            <?php endif; ?>
                                            <?php if (config_item('company_vat_'.$l) != '' || config_item('company_vat') != '') : ?>
                                            <span class="col-xs-3 no-gutter"><?=lang('company_vat')?>:</span>
                                            <span class="col-xs-9 no-gutter">
                            <?= (config_item('company_vat_' . $l) ? config_item('company_vat_' . $l) : config_item('company_vat')) ?><br>
                            <span>
                        <?php endif; ?>
                                        </p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php $showtax = config_item('show_invoice_tax') == 'TRUE' ? TRUE : FALSE; ?>
                        <div class="line"></div>
                        <div class="table-responsive">
                        <table id="inv-details" class="table sorted_table" type="invoices"><thead>
                            <tr>
                                <th></th>
                                <?php if ($showtax) : ?>
                                    <th width="20%"><?= lang('item_name') ?> </th>
                                    <th width="25%"><?= lang('description') ?> </th>
                                    <th width="7%" class="text-right"><?= lang('qty') ?> </th>
                                    <th width="10%" class="text-right"><?= lang('tax_rate') ?> </th>
                                    <th width="12%" class="text-right"><?= lang('unit_price') ?> </th>
                                    <th width="12%" class="text-right"><?= lang('tax') ?> </th>
                                    <th width="12%" class="text-right"><?= lang('total') ?> </th>
                                <?php else : ?>
                                    <th width="25%"><?= lang('item_name') ?> </th>
                                    <th width="35%"><?= lang('description') ?> </th>
                                    <th width="7%" class="text-right"><?= lang('qty') ?> </th>
                                    <th width="12%" class="text-right"><?= lang('unit_price') ?> </th>
                                    <th width="12%" class="text-right"><?= lang('total') ?> </th>
                                <?php endif; ?>
                                <th class="text-right inv-actions"></th>
                            </tr> </thead> <tbody>
                            <?php foreach (Invoice::has_items($inv->inv_id) as $key => $item) { ?>
                                <tr class="sortable" data-name="<?=$item->item_name?>" data-id="<?=$item->item_id?>">
                                    <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                                    <td>

                                        <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                                            <a class="text-info" href="<?=base_url()?>invoices/items/edit/<?=$item->item_id?>" data-toggle="ajaxModal"><?=$item->item_name?>
                                            </a>
                                        <?php } else { ?>
                                            <?=$item->item_name?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-muted"><?=nl2br($item->item_desc)?></td>

                                    <td class="text-right"><?=Applib::format_quantity($item->quantity);?></td>
                                    <?php if ($showtax) : ?>
                                        <td class="text-right"><?=Applib::format_tax($item->item_tax_rate).'%';?></td>
                                    <?php endif; ?>
                                    <td class="text-right"><?=Applib::format_currency($inv->currency, $item->unit_cost);?></td>
                                    <?php if ($showtax) : ?>
                                        <td class="text-right"><?=Applib::format_currency($inv->currency, $item->item_tax_total);?></td>
                                    <?php endif; ?>
                                    <td class="text-right"><?=Applib::format_currency($inv->currency, $item->total_cost);?></td>

                                    <td>
                                        <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                                            <a class="hidden-print"
                                               href="<?= base_url() ?>invoices/items/delete/<?=$item->item_id?>/<?=$item->invoice_id ?>" data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            
                            </tbody>
                        </table>
                        </div>
                        <table class="table">
                            <tbody>
                            <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>

                                <?php if ($inv->status != 'Paid') { ?>
                                    <tr class="hidden-print">
                                        <?php $attributes = array('class' => 'bs-example form-horizontal');
                                        echo form_open(base_url() . 'invoices/items/add', $attributes);
                                        ?>
                                        <input type="hidden" name="invoice_id" value="<?=$inv->inv_id ?>">
                                        <input type="hidden" name="item_order" value="<?=count(Invoice::has_items($inv->inv_id)) + 1?>">
                                        <input id="hidden-item-name" type="hidden" name="item_name">
                                        <td></td>
                                        <td><input id="auto-item-name" data-scope="invoices" type="text" placeholder="<?=lang('item_name') ?>" class="typeahead form-control" style="width: 150px;"></td>

                                        <td><textarea id="auto-item-desc" rows="1" name="item_desc" placeholder="<?= lang('item_description') ?>" class="form-control js-auto-size" style="width: 350px;"></textarea></td>

                                        <td><input id="auto-quantity" type="text" name="quantity" value="1" class="form-control" style="width: 70px;margin-left: 65px;" ></td>
                                        <?php if ($showtax) : ?>
                                            <td>
                                                <select name="item_tax_rate" class="form-control m-b" style="width: 115px;" >
                                                    <option value="0.00"><?= lang('none') ?></option>
                                                    <?php
                                                    foreach (Invoice::get_tax_rates() as $key => $tax) {
                                                        ?>
                                                        <option value="<?=$tax->tax_rate_percent?>" <?=config_item('default_tax') == $tax->tax_rate_percent ? ' selected="selected"' : '' ?>><?=$tax->tax_rate_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        <?php endif; ?>
                                        <td><input id="auto-unit-cost" type="text" name="unit_cost" required placeholder="50.56" class="form-control" style="  width: 95px;margin-left: 15%;" ></td>
                                        <?php if ($showtax) : ?>
                                            <td><input type="text" name="tax" placeholder="0.00" readonly="" class="form-control" style="width: 55%;margin-left: 65%;"></td>
                                        <?php endif; ?>
                                        <td></td>
                                        <td><button type="submit" class="btn btn-<?=config_item('theme_color')?>"><i class="fa fa-check"></i> <?= lang('save') ?></button></td>
                                        </form>
                                    </tr>
                                <?php } ?>
                                <?php } ?>
                                <tr>
                                <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border"><strong><?= lang('sub_total') ?></strong></td>
                                <td class="text-right">
                                    <?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id)) ?>
                                </td>

                                <td></td>
                                </tr>
                                <?php if ($inv->tax > 0.00): ?>
                                <tr>
                                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                        <strong><?=config_item('tax1')?> (<?=Applib::format_tax($inv->tax)?>%)</strong></td>
                                    <td class="text-right">
                                        <?=Applib::format_currency($inv->currency, Invoice::get_invoice_tax($inv->inv_id))?>
                                    </td>

                                    <td></td>

                                </tr>
                                <?php endif ?>

                                <?php if ($inv->tax2 > 0.00): ?>
                                <tr>
                                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                        <strong><?=config_item('tax2')?> (<?=Applib::format_tax($inv->tax2)?>%)</strong></td>
                                    <td class="text-right">
                                        <?=Applib::format_currency($inv->currency, Invoice::get_invoice_tax($inv->inv_id,'tax2'))?>
                                    </td>

                                    <td></td>

                                </tr>
                                <?php endif ?>

                                <?php if ($inv->discount > 0) { ?>
                                <tr>
                                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                        <strong><?= lang('discount') ?> - <?php echo Applib::format_tax($inv->discount); ?>%</strong></td>
                                    <td class="text-right">
                                        <?=Applib::format_currency($inv->currency, Invoice::get_invoice_discount($inv->inv_id)) ?>
                                    </td>

                                    <td></td>

                                </tr>
                                <?php } ?>

                                <?php if ($inv->extra_fee > 0) { ?>
                                <tr>
                                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                        <strong><?= lang('extra_fee') ?> - <?php echo $inv->extra_fee; ?>%</strong></td>
                                    <td class="text-right">
                                        <?=Applib::format_currency($inv->currency, Invoice::get_invoice_fee($inv->inv_id)) ?>
                                    </td>

                                    <td></td>

                                </tr>
                                <?php } ?>

                                <?php if (Invoice::get_invoice_paid($inv->inv_id) > 0) {
                                ?>
                                <tr>
                                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border"><strong><?= lang('payment_made') ?></strong></td>
                                    <td class="text-right text-danger">
                                        (-) <?=Applib::format_currency($inv->currency, Invoice::get_invoice_paid($inv->inv_id))?>
                                    </td>
                                    <td></td>
                                </tr>
                                <?php } ?>


                                <tr>
                                <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border"><strong>
                                        <?=lang('due_amount')?></strong></td>
                                <td class="text-right">
                                    <?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id))?>
                                </td>
                                <td></td>
                                </tr>
                                </tbody>
                                </table>
						<div class="invoice-info">
							<h5>Other information</h5>
							<p class="text-muted"><?= $inv->notes ?></p>
						</div>
						</div>
                    </div>

                    <?php if(!User::is_admin()) { ?>
                        <!-- START STRIPE PAYMENT -->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

                    <?php if($inv->allow_braintree == 'Yes') { ?>
                        <script src="https://js.braintreegateway.com/js/braintree-2.21.0.min.js"></script>
                    <?php } ?>

                    <?php if($inv->allow_stripe == 'Yes') { ?>

                        <script src="https://checkout.stripe.com/checkout.js"></script>

                        <script>
                            var handler = StripeCheckout.configure({
                                key: '<?=config_item('stripe_public_key')?>',
                                image: '<?=base_url()?>assets/images/<?=config_item('company_logo')?>',
                                token: function(token) {
                                    // Use the token to create the charge with a server-side script.
                                    // You can access the token ID with `token.id`
                                    $("#stripeToken").val(token.id);
                                    $("#stripeEmail").val(token.email);
                                    $("#stripeForm").submit();



                                }
                            });
                            $('#customButton').on('click', function(e) {
                                // Open Checkout with further options
                                handler.open({
                                    name: '<?=config_item('company_name')?>',
                                    description: 'INV REF: #<?=$inv->reference_no?> (<?=App::currencies($inv->currency)->symbol;?><?=Invoice::get_invoice_due_amount($inv->inv_id); ?>)',
                                    amount: '<?=Invoice::get_invoice_due_amount($inv->inv_id)*100?>',
                                    currency: '<?=$inv->currency?>'
                                });
                                e.preventDefault();
                            });
                            // Close Checkout on page navigation
                            $(window).on('popstate', function() {
                                handler.close();
                            });
                        </script>
                        <?php
    $attributes = array('id' => 'stripeForm');
          echo form_open(base_url().'stripepay/authenticate',$attributes); ?>
                            <input type="hidden" id="stripeToken" name="stripeToken"/>
                            <input type="hidden" id="stripeEmail" name="stripeEmail"/>
                            <input type="hidden" name="invoice" value="<?=$inv->inv_id?>"/>
                            <input type="hidden" name="ref" value="<?=$inv->reference_no?>"/>
                            <input type="hidden" name="amount" value="<?=Invoice::get_invoice_due_amount($inv->inv_id);?>"/>
                        </form>


                        <!-- END STRIPE CHECKOUT -->

                    <?php } ?>
                    <?php } ?>
</div>
<!-- end -->


<div class="modal fade" id="clone_invoice<?= $inv->inv_id ?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Clone Invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Do you want clone this Invoice(<?= $inv->reference_no ?>)?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button class="btn btn-primary clone_invoice_btn" data-invoice="<?= $inv->inv_id ?>">Yes</button>
      </div>
    </div>
  </div>
</div>
