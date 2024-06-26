<?php if (!isset($language)) : ?>
<div class="panel panel-white">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6">
				<h3 class="panel-title m-l-0 m-t-10"><?=lang('translations')?></h3>
			</div>
			<div class="col-xs-6">
				<div class="pull-right add-translation">
                    <select id="add-language" class="select2-option form-control" name="language">
						<?php foreach ($available as $loc) : ?>
						<option value="<?=str_replace(" ", "_", $loc->language)?>"><?=ucwords($loc->language)?></option>
						<?php endforeach; ?>
                    </select>
					<button id="add-translation" class="btn btn-primary btn-md"><?=lang('add_translation')?></button>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="table-translations" class="table table-striped table-bordered custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th class="col-xs-1 no-sort"><?=lang('icon')?></th>
						<th><?=lang('language')?></th>
						<th><?=lang('progress')?></th>
						<th><?=lang('done')?></th>
						<th><?=lang('total')?></th>
						<th class="col-options no-sort text-right"><?=lang('action')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($languages as $l) : 
						$st = $translation_stats;
						$total = $st[$l->name]['total'];
						$translated = $st[$l->name]['translated'];
						$pc = round(intval(($translated/$total)*1000) / 10);
					?>
					<tr>
						<td class=""><img src="<?=base_url('assets/images/flags/'.$l->icon)?>.gif" /></td>
						<td class=""><a href="<?=base_url()?>settings/translations/view/<?=$l->name?>/?settings=translations"><?=ucwords(str_replace("_"," ", $l->name))?></a></td>
						<td>
							<div class="progress progress-sm">
							<?php $bar = 'danger'; if ($pc > 20) { $bar = 'warning'; } if ($pc > 50) { $bar = 'info'; } if ($pc > 80) { $bar = 'success'; } ?>
							<div class="progress-bar progress-bar-<?=$bar?>" role="progressbar" aria-valuenow="<?=$pc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pc?>%;">
							<?=$pc?>%
							</div>
							</div>                        
						</td>
						<td class=""><?=$translated?></td>
						<td class=""><?=$total?></td>
						<td class="text-right">
						  <a data-rel="tooltip" data-original-title="<?=lang('backup')?>" class="backup-translation btn btn-xs btn-default" href="#" data-href="<?=base_url()?>settings/translations/backup/<?=$l->name?>/?settings=translations"><i class="fa fa-download"></i></a>
						  <a data-rel="tooltip" data-original-title="<?=lang('restore')?>" class="restore-translation btn btn-xs btn-default" href="#" data-href="<?=base_url()?>settings/translations/restore/<?=$l->name?>/?settings=translations"><i class="fa fa-upload"></i></a>
						  <a data-rel="tooltip" data-original-title="<?=($l->active == 1 ? lang('deactivate') : lang('activate') )?>" class="active-translation btn btn-xs btn-<?=($l->active == 0 ? 'default' : 'success' )?>" href="#" data-href="<?=base_url()?>settings/translations/active/<?=$l->name?>/?settings=translations"><i class="fa fa-eye"></i></a>
						  <a data-rel="tooltip" data-original-title="<?=lang('edit')?>" class="btn btn-xs btn-info" href="<?=base_url()?>settings/translations/view/<?=$l->name?>/?settings=translations"><i class="fa fa-edit"></i></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php elseif (!isset($language_file)) : ?> 
<div class="panel panel-white">
	<div class="panel-heading">
		<h3 class="panel-title"><?=lang('translations')?> - <?=ucwords($language)?></h3>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="table-translations-files" class="table table-striped table-bordered custom-table m-b-0 AppendDataTables">
                <thead>
					<tr>
						<th class="col-xs-2 no-sort"><?=lang('type')?></th>
						<th class="col-xs-3"><?=lang('file')?></th>
						<th class="col-xs-4"><?=lang('progress')?></th>
						<th class="col-xs-1"><?=lang('done')?></th>
						<th class="col-xs-1"><?=lang('total')?></th>
						<th class="col-options no-sort col-xs-1 text-right"><?=lang('action')?></th>
					</tr>
                </thead>
                <tbody>
                    <?php foreach($language_files as $file => $altpath) : 
                        $shortfile = str_replace("_lang.php", "", $file);
                        $st = $translation_stats[$language]['files'][$shortfile];
                        $fn = ucwords(str_replace("_"," ", $shortfile));
                        if ($shortfile == 'fx') { $fn = 'Main Application'; }
                        if ($shortfile == 'tank_auth') { $fn = 'Authenication'; }
                        $total = $st['total'];
                        $translated = $st['translated'];
                        $pc = intval(($translated/$total)*1000) / 10;
                    ?>
                    <tr>
                        <td class=""><?=($altpath == './system/' ? 'System':'Application')?></td>
                        <td class=""><a href="<?=base_url()?>settings/translations/edit/<?=$language?>/<?=$shortfile?>/?settings=translations"><?=$fn?></a></td>
                        <td>
                            <div class="progress progress-sm">
                            <?php $bar = 'danger'; if ($pc > 20) { $bar = 'warning'; } if ($pc > 50) { $bar = 'info'; } if ($pc > 80) { $bar = 'success'; } ?>
                            <div class="progress-bar progress-bar-<?=$bar?>" role="progressbar" aria-valuenow="<?=$pc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pc?>%;">
                            <?=$pc?>%
                            </div>
                            </div>                        
                        </td>
                        <td class=""><?=$translated?></td>
                        <td class=""><?=$total?></td>
                        <td class="text-right">
                          <a class="btn btn-xs btn-default" href="<?=base_url()?>settings/translations/edit/<?=$language?>/<?=$shortfile?>/?settings=translations"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
			</table>
		</div>
	</div>
</div>
<?php else : ?>
    
<?php $attributes = array('class' => 'bs-example form-horizontal', 'id'=>'form-strings');
echo form_open_multipart('settings/translations/save/'.$language.'/'.$language_file.'/?settings=translations', $attributes); ?> 
	<input type="hidden" name="_language" value="<?=$language?>">
	<input type="hidden" name="_file" value="<?=$language_file?>">
    <div class="panel panel-white">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-6">
					<?php 
					$fn = ucwords(str_replace("_"," ", $language_file));
					if ($language_file == 'fx') { $fn = 'Main Application'; }
					if ($language_file == 'tank_auth') { $fn = 'Authenication'; }
					
					$total = count($english);
					$translated = 0;
					if ($language == 'english') { $percent = 100; } else {
						foreach ($english as $key => $value) {
							if (isset($translation[$key]) && $translation[$key] != $value) { $translated++; }
						}
						$percent = intval(($translated / $total) * 100);
					}
					?>
					<?=lang('translations')?> | <a href="<?=base_url()?>settings/translations/view/<?=$language?>/?settings=translations"><?=ucwords(str_replace("_"," ", $language))?></a> | <?=$fn?> | <?=$percent?>% <?=mb_strtolower(lang('done'))?>
				</div>
				<div class="col-xs-6 text-right">
					<button type="submit" id="save-translation" class="btn btn-xs btn-primary pull-right"><?=lang('save_translation')?></button>
				</div>
			</div>
		</div>
        <div class="panel-body">
			<div class="table-responsive">
				<table id="table-strings" class="table table-striped table-bordered custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th class="col-xs-5">English</th>
							<th class="col-xs-7"><?=ucwords(str_replace("_"," ", $language))?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($english as $key => $value) : ?>
						<tr>
							<td><?=$value?></td>
							<td><input class="form-control" width="100%" type="text" value="<?=(isset($translation[$key]) ? $translation[$key] : $value)?>" name="<?=$key?>" /></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
        </div>
    <?php endif; ?>
<!-- End details -->
	</div>
</form> 