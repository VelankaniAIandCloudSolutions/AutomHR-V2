                <div class="header-fixed clearfix hidden-print p-0 m-l-2">
                    <div class="row">
                        <div class="col-sm-8 m-t-8">
                            <?php $estimate = Estimate::view_estimate($id);
                                $client = Client::view_by_id($estimate->client);
                                $l = $client->language; ?>

                            <a data-original-title="<?=lang('print_estimate')?>" data-toggle="tooltip" data-placement="bottom" href="#" class="btn btn-sm btn-primary" onClick="window.print();">
                                <i class="fa fa-print"></i> </a>

                            <?php if(User::can_edit_estimate()) { ?>

                                <a href="<?=base_url()?>estimates/items/insert/<?=$estimate->est_id?>" title="<?=lang('item_quick_add')?>" class="btn btn-sm btn-info" data-toggle="ajaxModal">
                                    <i class="fa fa-list-alt text-white"></i> <?=lang('items')?></a>

                                <a data-original-title="<?=lang('convert_to_invoice')?>" data-placement="bottom" class="btn btn-sm btn-warning <?php if($estimate->client == '0'){ echo "disabled"; } ?>" href="<?=base_url()?>estimates/convert/<?=$estimate->est_id?>" data-toggle="ajaxModal"
                                   title="<?=lang('convert_to_invoice')?>">
                                    <?=lang('convert_to_invoice')?></a>


                                <?php if($estimate->show_client == 'Yes'){ ?>
                                <a class="btn btn-sm btn-success" href="<?=base_url()?>estimates/hide/<?=$estimate->est_id?>" data-toggle="tooltip" data-placement="bottom" data-title="<?=lang('hide_to_client')?>"><i class="fa fa-eye-slash"></i>
                                    </a><?php }else{ ?>
                                    <a class="btn btn-sm btn-danger" href="<?=base_url()?>estimates/show/<?=$estimate->est_id?>" data-toggle="tooltip" data-placement="bottom" data-title="<?=lang('show_to_client')?>"><i class="fa fa-eye"></i>
                                    </a>
                                <?php } ?>
                            <?php } ?>


                <?php if(!User::is_client()) { ?>

                            <div class="btn-group">
                                <button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown">
                                    <?=lang('more_actions')?>
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">

                                    <?php if(User::can_edit_estimate()) { ?>
                                        <li><a href="<?=base_url()?>estimates/edit/<?=$estimate->est_id?>">
                                        <?=lang('edit_estimate')?></a></li>
                                        <li><a href="<?=base_url()?>estimates/email/<?=$estimate->est_id?>" data-toggle="ajaxModal"><?=lang('email_estimate')?></a></li>
                                        <li><a href="<?=base_url()?>estimates/timeline/<?=$estimate->est_id?>">
                                        <?=lang('estimate_history')?></a></li>
                                    <?php } ?>
                                    <?php if($estimate->status != 'Declined'){ ?>
                                    <li><a href="<?=base_url()?>estimates/status/declined/<?=$estimate->est_id?>"><?=lang('mark_as_declined')?></a></li>
                                    <?php } ?>

                                    <?php if($estimate->status != 'Accepted'){ ?>
                                    <?php $convert_string = '';
                                    if(config_item('estimate_to_project') == 'TRUE') {
                                    $convert_string = 'data-toggle="tooltip" data-title="A new project will be created"';
                                    } ?>
                                    <li><a href="<?=base_url()?>estimates/status/accepted/<?=$estimate->est_id?>" <?=$convert_string;?>><?=lang('mark_as_accepted')?></a>
                                    </li>
                                    <?php } ?>

                        <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'delete_estimates')) { ?>
                                        <li class="divider"></li>
                                        <li><a href="<?=base_url()?>estimates/delete/<?=$estimate->est_id?>" data-toggle="ajaxModal"><?=lang('delete_estimate')?></a></li>
                        <?php } ?>

                                </ul>
                            </div>
            <?php } else{ ?>
            <?php $active_button = ($estimate->status == 'Pending') ? NULL : 'disabled'; ?>
            <a class="btn btn-sm btn-success <?=$active_button?>" href="<?=base_url()?>estimates/status/accepted/<?=$estimate->est_id?>"><?=lang('mark_as_accepted')?></a>


            <a class="btn btn-sm btn-danger <?=$active_button?>" href="<?=base_url()?>estimates/status/declined/<?=$estimate->est_id?>"><?=lang('mark_as_declined')?></a>

            <?php } ?>



                        </div>
                        <div class="col-sm-4 m-t-8">
                            <?php if ($estimate->client != 0) { ?>
                                <?php if (config_item('pdf_engine') == 'invoicr') : ?>
                                <a href="<?=base_url()?>fopdf/estimate/<?=$estimate->est_id?>" class="btn btn-sm btn-primary pull-right m-r-2"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
                                <?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
                                <a href="<?=base_url()?>estimates/pdf/<?=$estimate->est_id?>" class="btn btn-sm btn-primary pull-right m-r-2"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
                                <?php endif; ?>
                            <?php } ?>

                        </div>
                    </div>
				</div>



<div class="padding-2p">
       <!-- Start Display Details -->

                <section class="ie-details">
                    <!-- Start Display Details -->

                    <?php
                    if(!$this->session->flashdata('message')){
                        if(strtotime($estimate->due_date) < time() && $estimate->status == 'Pending'){ ?>
                            <div class="alert alert-warning hidden-print">
                                <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
                                <?=lang('estimate_overdue')?>
                            </div>
                        <?php } } ?>

                    <div class="panel m-b-0">
						<div class="panel-body">
					
                        <div class="row">
                            <div class="col-xs-4">
                                <div style="height: <?=config_item('invoice_logo_height')?>px">
                                <img class="ie-logo" src="<?=base_url()?>assets/images/logos/<?=config_item('invoice_logo')?>" >
                                </div>
                            </div>
                            <div class="col-xs-8 text-right">
                                <p class="h4"><?=$estimate->reference_no?></p>
                                <div><?=lang('estimate_date')?><span class="col-xs-5 no-gutter-right pull-right"><strong><?=strftime(config_item('date_format'), strtotime($estimate->date_saved));?></strong></span></div>
                                <div><?=lang('valid_until')?><span class="col-xs-5 no-gutter-right pull-right"><strong><?=strftime(config_item('date_format'), strtotime($estimate->due_date));?></strong></span></div>
                                <div><?=lang('estimate_status')?><span class="col-xs-5 no-gutter-right pull-right"><span class="label bg-success"><?=$estimate->status?></span></span></div>
                            </div>
                        </div>


                        <div class="m-t">
                            <div class="row">
                        <?php if (config_item('swap_to_from') == 'FALSE') { ?>
                        <div class="col-sm-6 col-xs-12">
                            <strong><?= lang('received_from') ?>:</strong>
                            <h4><?= (config_item('company_legal_name_' . $l) ? config_item('company_legal_name_' . $l) : config_item('company_legal_name')) ?></h4>
                            <p>
                                <span class="col-xs-5 no-gutter"><?= lang('address') ?>:</span>
                                <span class="col-xs-7 no-gutter">
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
                                <span class="col-xs-5 no-gutter"><?= lang('phone') ?>:</span>
                                <span class="col-xs-7 no-gutter">
                                    <a href="tel:<?= (config_item('company_phone_' . $l) ? config_item('company_phone_' . $l) : config_item('company_phone')) ?>">
                                    <?= (config_item('company_phone_' . $l) ? config_item('company_phone_' . $l) : config_item('company_phone')) ?></a>

                                    <?php if (config_item('company_phone_2_'.$l) != '' || config_item('company_phone_2') != '') : ?>
                                    , <a href="tel:<?= (config_item('company_phone_2_' . $l) ? config_item('company_phone_2_' . $l) : config_item('company_phone_2')) ?>">
                                    <?= (config_item('company_phone_2_' . $l) ? config_item('company_phone_2_' . $l) : config_item('company_phone_2')) ?></a>
                                    <?php endif; ?>
                                </span>
                                <?php if (config_item('company_fax_'.$l) != '' || config_item('company_fax') != '') : ?>
                                <span class="col-xs-5 no-gutter"><?= lang('fax') ?>:</span>
                                <span class="col-xs-7 no-gutter">
                                    <a href="tel:<?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?>">
                                    <?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?></a>
                                </span>
                                <?php endif; ?>
                                <?php if (config_item('company_registration_'.$l) != '' || config_item('company_registration') != '') : ?>
                                <span class="col-xs-5 no-gutter"><?= lang('company_registration') ?>:</span>
                                <span class="col-xs-7 no-gutter">
                                    <a href="tel:<?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?>">
                                    <?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?></a>
                                </span>
                                <?php endif; ?>
                                <?php if (config_item('company_vat_'.$l) != '' || config_item('company_vat') != '') : ?>
                                <span class="col-xs-5 no-gutter"><?=lang('company_vat')?>:</span>
                                <span class="col-xs-7 no-gutter">
                                <?= (config_item('company_vat_' . $l) ? config_item('company_vat_' . $l) : config_item('company_vat')) ?><br>
                                <span>
                                <?php endif; ?>
                                    </p>
                                </div>
                                <?php } ?>
                                <div class="col-lg-9 col-sm-6 col-xs-12">
                                <div class="col-lg-5">
                                    <strong><?=lang('bill_to')?>:</strong>
                                    <h4><?=ucfirst($client->company_name)?> <br></h4>
                                    <p>
                                    <span class="col-xs-4 no-gutter"><?= lang('address') ?>:</span>
                                    <span class="col-xs-8 no-gutter">
                                        <?php echo $client->company_address; ?><br>
                                        <?php echo $client->city; ?>
                                        <?php if ($client->zip != '') { echo ", ".$client->zip;  } ?><br>
                                        <?php if ($client->state != '') { echo $client->state.", ";  } ?>
                                        <?php echo $client->country; ?>
                                                                </span>
                                        <span class="col-xs-4 no-gutter"><?=lang('phone')?>:</span>
                                        <span class="col-xs-8 no-gutter">
                                        <a href="tel:<?php echo $client->company_phone; ?>">
                                        <?php echo $client->company_phone; ?></a></span>
                                        <?php if ($client->company_fax != '') : ?>
                                        <span class="col-xs-4 no-gutter"><?= lang('fax') ?>:</span>
                                        <span class="col-xs-8 no-gutter">
                                            <a href="tel:<?php echo $client->company_fax; ?>">
                                            <?php echo ucfirst($client->company_fax); ?></a>&nbsp;
                                        </span>
                                        <?php endif; ?>

                                    <?php if($client->VAT != ''){ ?>
                                    <span class="col-xs-4 no-gutter"><?=lang('company_vat') ?>:</span>
                                    <span class="col-xs-8 no-gutter"><?php echo $client->VAT; ?>
                                    </span>
                                    <?php } ?>
                                    </p>
                                    </div>
                                </div>
                    <?php if (config_item('swap_to_from') == 'TRUE') { ?>
                    <div class="col-xs-12 col-sm-6 col-lg-3">
                    <div class="col-lg-12">
                        <strong><?= lang('received_from') ?>:</strong>
                        <h4><?= (config_item('company_legal_name_' . $l) ? config_item('company_legal_name_' . $l) : config_item('company_legal_name')) ?></h4>
                        <p>
                            <span class="col-xs-4 no-gutter"><?= lang('address') ?>:</span>
                            <span class="col-xs-8 no-gutter">
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
                            <span class="col-xs-4 no-gutter"><?= lang('phone') ?>:</span>
                            <span class="col-xs-8 no-gutter">
                                <a href="tel:<?= (config_item('company_phone_' . $l) ? config_item('company_phone_' . $l) : config_item('company_phone')) ?>">
                                <?= (config_item('company_phone_' . $l) ? config_item('company_phone_' . $l) : config_item('company_phone')) ?></a>

                                <?php if (config_item('company_phone_2_'.$l) != '' || config_item('company_phone_2') != '') : ?>
                                , <a href="tel:<?= (config_item('company_phone_2_' . $l) ? config_item('company_phone_2_' . $l) : config_item('company_phone_2')) ?>">
                                <?= (config_item('company_phone_2_' . $l) ? config_item('company_phone_2_' . $l) : config_item('company_phone_2')) ?></a>
                                <?php endif; ?>
                            </span>
                            <?php if (config_item('company_fax_'.$l) != '' || config_item('company_fax') != '') : ?>
                            <span class="col-xs-4 no-gutter"><?= lang('fax') ?>:</span>
                            <span class="col-xs-8 no-gutter">
                                <a href="tel:<?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?>">
                                <?= (config_item('company_fax_' . $l) ? config_item('company_fax_' . $l) : config_item('company_fax')) ?></a>
                            </span>
                            <?php endif; ?>
                            <?php if (config_item('company_registration_'.$l) != '' || config_item('company_registration') != '') : ?>
                            <span class="col-xs-4 no-gutter"><?= lang('company_registration') ?>:</span>
                            <span class="col-xs-8 no-gutter">
                                <a href="tel:<?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?>">
                                <?= (config_item('company_registration_' . $l) ? config_item('company_registration_' . $l) : config_item('company_registration')) ?></a>
                            </span>
                            <?php endif; ?>
                            <?php if (config_item('company_vat_'.$l) != '' || config_item('company_vat') != '') : ?>
                            <span class="col-xs-4 no-gutter"><?=lang('company_vat')?>:</span>
                            <span class="col-xs-8 no-gutter">
                            <?= (config_item('company_vat_' . $l) ? config_item('company_vat_' . $l) : config_item('company_vat')) ?><br>
                            <span>
                            <?php endif; ?>
                                </p>
                            </div>
                            <?php } ?>
                            </div>
                    </div>
                        </div>
                        <?php $showtax = config_item('show_estimate_tax') == 'TRUE' ? TRUE : FALSE; ?>
                        <div class="line"></div>
                        <div class="table-responsive">
                        <table id="est-details" class="table sorted_table small" type="estimates"><thead>
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
                            <?php foreach (Estimate::has_items($estimate->est_id) as $key => $item) { ?>
                            <tr class="sortable" data-name="<?=$item->item_name?>" data-id="<?=$item->item_id?>">
                                        <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                                        <td>
                                <?php if(User::can_edit_estimate()) { ?>
                                        <a class="text-info" href="<?=base_url()?>estimates/items/edit/<?=$item->item_id?>" data-toggle="ajaxModal"><?=$item->item_name?></a>
                                <?php }else{ echo $item->item_name; } ?>
                                        </td>

                                        <td><?=nl2br($item->item_desc); ?></td>
                                        <td class="text-right">
                                        <?=Applib::format_quantity($item->quantity); ?>
                                        </td>
                                        <?php if ($showtax) : ?>
                                        <td class="text-right">
                                        <?=Applib::format_tax($item->item_tax_rate); ?>%
                                        </td>
                                        <?php endif; ?>
                                        <td class="text-right">
                                        <?=Applib::format_currency($estimate->currency, $item->unit_cost);?>
                                        </td>
                                        <?php if ($showtax) : ?>
                                        <td class="text-right">
                                        <?=Applib::format_currency($estimate->currency, $item->item_tax_total);?>
                                        </td>
                                        <?php endif; ?>
                                        <td class="text-right">
                                        <?=Applib::format_currency($estimate->currency, $item->total_cost);?>
                                        </td>
                                        <td>
                                            <?php if(User::can_edit_estimate()) { ?>
                                                <a class="hidden-print" href="<?=base_url()?>estimates/items/delete/<?=$item->item_id?>/<?=$item->estimate_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            
                            </tbody>
                        </table>
                        </div>
                        <table class="table">
                            <tbody>
                        <?php if(User::can_edit_estimate()) { ?>
                                <tr class="hidden-print">
                                    <?php
                                    $attributes = array('class' => 'bs-example form-horizontal');
                                    echo form_open(base_url().'estimates/items/add', $attributes); ?>
                                    <input type="hidden" name="estimate_id" value="<?=$estimate->est_id?>">
                                    <input type="hidden" name="item_order" value="<?=count(Estimate::has_items($estimate->est_id))+1?>">
                                    <input id="hidden-item-name" type="hidden" name="item_name" value="<?=count(Estimate::has_items($estimate->est_id))+1?>">
                                    <td></td>
                                    <td><input id="auto-item-name" data-scope="estimates" type="text" placeholder="<?=lang('item_name')?>" class="typeahead form-control"></td>
                                    <td><textarea id="auto-item-desc" rows="1" name="item_desc" placeholder="<?=lang('item_description')?>" class="form-control js-auto-size"></textarea></td>
                                    <td><input id="auto-quantity" type="text" name="quantity" placeholder="1" class="form-control money"></td>
                                    <?php if ($showtax) : ?>
                                    <td>
                                        <select name="item_tax_rate" class="form-control m-b">
                                            <option value="0.00"><?=lang('none')?></option>
                            <?php foreach (App::get_by_where('tax_rates',array()) as $key => $tax) { ?>
                                                    <option value="<?=$tax->tax_rate_percent?>" <?=config_item('default_tax') == $tax->tax_rate_percent ? ' selected="selected"' : '' ?>><?=$tax->tax_rate_name?></option>
                            <?php } ?>
                                        </select>
                                    </td>
                                    <?php endif; ?>
                                    <td><input id="auto-unit-cost" type="text" name="unit_cost" required placeholder="50.56" class="form-control"></td>
                                    <?php if ($showtax) : ?>
                                    <td><input type="text" name="tax" placeholder="0.00" readonly="" class="form-control money"></td>
                                    <?php endif; ?>
                                    <td></td>
                                    <td><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> <?=lang('save')?></button></td>
                                    </form>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                <strong><?=lang('sub_total')?></strong>
                                </td>

                                <td class="text-right">
                    <?=Applib::format_currency($estimate->currency,Estimate::sub_total($estimate->est_id)); ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                    <strong><?=lang('tax')?> 1 (<?=Applib::format_tax($estimate->tax);?>%)</strong></td>
                                <td class="text-right">
                    <?=Applib::format_currency($estimate->currency, Estimate::total_tax($estimate->est_id)); ?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                    <strong><?=lang('tax')?> 2 (<?=Applib::format_tax($estimate->tax2);?>%)</strong></td>
                                <td class="text-right">
                    <?=Applib::format_currency($estimate->currency, Estimate::total_tax($estimate->est_id,'tax2')); ?></td>
                                <td></td>
                            </tr>

                            <?php if($estimate->discount > 0){ ?>
                                <tr>
                                    <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border">
                                        <strong><?=lang('discount')?> - <?=Applib::format_tax($estimate->discount)?>%</strong></td>
                                    <td class="text-right">
                    <?=Applib::format_currency($estimate->currency,Estimate::total_discount($estimate->est_id)); ?>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="<?= $showtax ? '7' : '5' ?>" class="text-right no-border"><strong><?=lang('total')?></strong></td>
                                <td class="text-right">
                    <?=Applib::format_currency($estimate->currency,Estimate::due($estimate->est_id)); ?></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                       <div class="estimate-info">
							<h5>Other information</h5>
							<p class="text-muted"><?=$estimate->notes?></p>
						</div>
                    </div>
                    </div>
                </section>
                <!-- End display details -->
	</div> 
