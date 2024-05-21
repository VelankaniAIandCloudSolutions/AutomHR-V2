<div class="header-fixed clearfix hidden-print p-0">
	<div class="row m-t-8">
		<div class="col-sm-7">
			<?php $e = Estimate::view_estimate($id); ?>
			<div class="btn-group">
				<a href="<?=base_url()?>estimates/view/<?=$e->est_id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-sm m-l-2">
					<i class="fa fa-info-circle"></i> <?=lang('estimate_details')?>
				</a>
			</div>
		</div>
		<div class="col-sm-4 pull-right">
			<?php if ($e->client != 0) { ?>
			<?php if (config_item('pdf_engine') == 'invoicr') : ?>
			<a href="<?=base_url()?>fopdf/estimate/<?=$e->est_id?>" class="btn btn-sm btn-primary pull-right m-r-5"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
			<?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
			<a href="<?=base_url()?>estimates/pdf/<?=$e->est_id?>" class="btn btn-sm btn-primary pull-right m-r-5"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
			<?php endif; ?>
			<?php } ?>
		</div>
	</div>
</div>

<div class="padding-2p">
	<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
		<!-- Start Display Details -->
		<div class="panel">
			<div class="panel-body">
				<div  id="activity">
					<ul class="list-group no-radius m-b-0">
						<?php foreach ($activities as $key => $a) { ?>
						<li class="list-group-item">
							<a class="pull-left thumb-sm avatar">
								<img src="<?php echo User::avatar_url($a->user); ?>" class="img-rounded">
							</a>
							<a href="#" class="clear">
                                <small class="pull-right"><?=strftime("%b %d, %Y %H:%M:%S", strtotime($a->activity_date)) ?></small>
                                <strong class="block m-l-xs"><?=ucfirst(User::displayName($a->user)); ?></strong>
                                <small class="m-l-xs">
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
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>