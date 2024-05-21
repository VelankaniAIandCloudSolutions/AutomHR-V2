<?php if ($i->individual == 0) { ?>
<!-- Client Contacts -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-table">
            <div class="panel-heading panel-h-p1">
				<div class="row">
					<div class="col-xs-6">
						<h3 class="panel-title m-t-6"><?=lang('contacts')?></h3>
					</div>
					<div class="col-xs-6">
						<a href="<?=base_url()?>contacts/add/<?=$i->co_id?>" class="btn btn-success pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_contact')?></a>
					</div>
				</div>
			</div>
			<div class="panel-body">
			<div class="table-responsive">
				<table id="table-client-details-1" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th><?=lang('full_name')?></th>
							<th><?=lang('email')?></th>
							<th><?=lang('mobile_phone')?> </th>
							<th>Skype</th>
							<th class="col-date"><?=lang('last_login')?> </th>
							<th class="col-options no-sort text-right"><?=lang('options')?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach (Client::get_client_contacts($company) as $key => $contact) { ?>
						<tr>
							<td><a class="avatar">
									<img src="<?php echo User::avatar_url($contact->user_id);?>" class="thumb-sm img-circle" width='30'height='30'>
								<?=$contact->fullname?>
								</a>
								</td>
							<td class="text-info" ><?=$contact->email?> </td>
							<td><a href="tel:<?=User::profile_info($contact->user_id)->phone?>"><b><i class="fa fa-phone"></i></b> <?=User::profile_info($contact->user_id)->phone?></a></td>
							<td><a href="skype:<?=User::profile_info($contact->user_id)->skype?>?call"><?=User::profile_info($contact->user_id)->skype?></a></td>
							<?php
							if ($contact->last_login == '0000-00-00 00:00:00') {
								$login_time = "-";
							}else{ $login_time = strftime(config_item('date_format')." %H:%M:%S", strtotime($contact->last_login)); } ?>
							<td><?=$login_time?> </td>
							<td class="text-right">
								<a href="<?=base_url()?>companies/send_invoice/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-primary btn-xs" title="<?=lang('email_invoice')?>" data-toggle="ajaxModal">
									<i class="fa fa-envelope"></i> </a>
								<a href="<?=base_url()?>companies/make_primary/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-default btn-xs" title="<?=lang('primary_contact')?>" >
									<i class="fa fa-chain <?php if ($i->primary_contact == $contact->user_id) { echo "text-danger"; } ?>"></i> </a>
								<a href="<?=base_url()?>contacts/update/<?=$contact->user_id?>" class="btn btn-success btn-xs" title="<?=lang('edit')?>"  data-toggle="ajaxModal">
									<i class="fa fa-edit"></i> </a>
								<a href="<?=base_url()?>users/account/delete/<?=$contact->user_id?>" class="btn btn-danger btn-xs" title="<?=lang('delete')?>" data-toggle="ajaxModal">
									<i class="fa fa-trash-o"></i> </a>
							</td>
						</tr>
					<?php  } ?>
					</tbody>
				</table>
				</div>
			</div>
        </div>
    </div>
</div>
<?php } ?>