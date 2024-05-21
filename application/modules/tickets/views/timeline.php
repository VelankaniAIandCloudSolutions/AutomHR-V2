<!-- Start -->
<div class="content">
						<div class="row">
							<div class="col-sm-8">
								<?php
								if (!empty($invoice_details)) {
								foreach ($invoice_details as $key => $inv) { ?>
								
								
								<div class="btn-group">
						<a href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>" data-original-title="<?=lang('view_details')?>" data-bs-toggle="tooltip" data-placement="top" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> <?=lang('invoice_details')?></a>
						</div>
								
								
							</div>
							<div class="col-sm-4 m-b-30 float-end">
								<a href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>" class="btn btn-sm btn-primary float-end">
								<i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
							</div>
						</div>
							<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

								<!-- Start Display Details -->
								

								<!-- Timeline START -->
								<div class="card card-white">
									<div class="card-body">


					<div  id="activity">
                          <ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border">
							<?php
							if (!empty($activities)) {
							foreach ($activities as $key => $a) { ?>
                            <li class="list-group-item">
                              <a href="#" class="thumb-sm float-left m-r-sm">
                                <img src="<?=base_url()?>assets/avatar/<?=Applib::profile_info($a->user)->avatar?>" class="rounded-circle">
                              </a>
                              <a href="#" class="clear">
                                <small class="float-end"><?=strftime("%b %d, %Y %H:%M:%S", strtotime($a->activity_date)) ?></small>
                                <strong class="block"><?=ucfirst(Applib::login_info($a->user)->username)?></strong>
                                <small>
                                    <?php 
                                    if (lang($a->activity) != '') {
                                        if (!empty($a->value1)) {
                                            if (!empty($a->value2)){
                                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>', '<em>'.$a->value2.'</em>');
                                            } else {
                                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>');
                                            }
                                        } else { echo lang($a->activity); }
                                    } else { echo $a->activity; } 
                                    ?> 
                                </small>
                              </a>
                            </li>
                            <?php } } ?>

                          </ul>
                        </div>
								



											
										<?php } } ?>		
									</div>
								</div>
							</div>
</div>