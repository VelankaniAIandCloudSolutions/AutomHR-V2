<?php if ($l->individual == 0) { ?>
<!-- Client Contacts -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-table">
            <div class="card-header">
				<div class="row">
					<div class="col-6">
						<h3 class="card-title"><?=lang('contacts')?></h3>
					</div>
					<div class="col-6">
						<a href="<?=base_url()?>contacts/add/<?=$l->co_id?>" class="btn btn-sm btn-success float-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_contact')?></a>
					</div>
				</div>
			</div>
			<div class="card-body">
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
						<?php foreach (Lead::contacts($l->co_id) as $key => $contact) { ?>
							<tr>
								<td>
									<a class="avatar">
										<img src="<?php echo User::avatar_url($contact->user_id);?>" class="thumb-sm  rounded-circle">
									<?=$contact->fullname?>
									</a>
									</td>
								<td class="text-info" ><?=$contact->email?> </td>
								<td><a href="tel:<?=User::profile_info($contact->user_id)->phone?>"><b><i class="fa fa-phone"></i></b> <?=User::profile_info($contact->user_id)->phone?></a></td>
								<td><a href="skype:<?=User::profile_info($contact->user_id)->skype?>?call"><?=User::profile_info($contact->user_id)->skype?></a></td>
								<?php
								$login_time = ($contact->last_login == '0000-00-00 00:00:00') ? "-" : strftime(config_item('date_format')." %H:%M:%S", strtotime($contact->last_login)); ?>
								<td><?=$login_time?> </td>
								<td class="text-right">
									<a href="<?=base_url()?>leads/make_primary/<?=$contact->user_id?>/<?=$l->co_id?>" class="btn btn-default btn-sm" title="<?=lang('primary_contact')?>" >
										<i class="fa fa-chain <?php if ($l->primary_contact == $contact->user_id) { echo "text-danger"; } ?>"></i> </a>
									<a href="<?=base_url()?>contacts/update/<?=$contact->user_id?>" class="btn btn-success btn-sm" title="<?=lang('edit')?>"  data-toggle="ajaxModal">
										<i class="fa fa-edit"></i> </a>
									<a href="<?=base_url()?>users/account/delete/<?=$contact->user_id?>" class="btn btn-danger btn-sm" title="<?=lang('delete')?>" data-toggle="ajaxModal">
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