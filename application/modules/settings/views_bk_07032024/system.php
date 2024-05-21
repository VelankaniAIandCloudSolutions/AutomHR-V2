<div class="p-0">
    <!-- Start Form -->
    <div class="col-lg-12 p-0">
    <?php
    $view = isset($_GET['view']) ? $_GET['view'] : '';
    $data['load_setting'] = $load_setting;
    switch ($view) {
        case 'slack':
            $this->load->view('slack',$data);
            break;
        case 'categories':
            $this->load->view('expense_categories',$data);
            break;
        case 'project':
            $this->load->view('project_settings',$data);
            break;
        
        default: ?>

        <?php
        $attributes = array('class' => 'bs-example form-horizontal','id'=>'settingsSystemForm');
        echo form_open_multipart('settings/update', $attributes); ?>
            <div class="panel panel-white">
                <div class="panel-heading font-bold">
					<h3 class="panel-title p-5"><?=lang('system_settings')?></h3>
				</div>
                <div class="panel-body">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <!-- <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_language')?></label>
                        <div class="col-lg-3">
                            <select name="default_language" class="form-control">
                                <?php //foreach ($languages as $lang) : ?>
                                    <option lang="<?=$lang->code?>" value="<?=$lang->name?>"<?=(config_item('default_language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                                <?php //endforeach; ?>
                            </select>
                        </div>
                    </div> -->
                    <!-- <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('locale')?></label>
                        <div class="col-lg-3">
                            <select class="select2-option form-control" name="locale" required>
                                <?php //foreach ($locales as $loc) : ?>
                                    <option lang="<?=$loc->code?>" value="<?=$loc->locale?>"<?=(config_item('locale') == $loc->locale ? ' selected="selected"' : '')?>><?=$loc->name?></option>
                                <?php //endforeach; ?>
                            </select>
                        </div>
                        <span class="help-block m-b-0">Affects Decimals</span>
                    </div> -->
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('timezone')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select class="select2-option form-control" name="timezone" class="form-control" required>
                                <?php foreach ($timezones as $timezone => $description) : ?>
                                    <option value="<?=$timezone?>"<?=(config_item('timezone') == $timezone ? ' selected="selected"' : '')?>><?=$description?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_currency')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="default_currency" class="form-control">
                                <?php foreach ($currencies as $cur) : ?>
                                    <option value="<?=$cur->code?>"<?=(config_item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- <a class="btn btn-success btn-sm" data-toggle="ajaxModal" href="<?=base_url()?>settings/add_currency"><?=lang('add_currency')?></a> -->
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_currency_symbol')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="default_currency_symbol" class="form-control">
                                <?php $cur = App::currencies(config_item('default_currency')); ?>
                                <?php foreach ($currencies as $cur) : ?>
								<option value="<?=$cur->symbol?>" <?=(config_item('default_currency_symbol') == $cur->symbol ? ' selected="selected"' : '')?>><?=$cur->symbol?> - <?=$cur->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <span class="help-block m-b-0">Overwritten by Client's Currency</span>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('currency_position')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="currency_position" class="form-control">
								<option value="before"<?=(config_item('currency_position') == 'before' ? ' selected="selected"' : '')?>><?=lang('before_amount')?></option>
								<option value="after"<?=(config_item('currency_position') == 'after' ? ' selected="selected"' : '')?>><?=lang('after_amount')?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('currency_decimals')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="currency_decimals" class="form-control">
								<option value="0"<?=(config_item('currency_decimals') == 0 ? ' selected="selected"' : '')?>>0</option>
								<option value="1"<?=(config_item('currency_decimals') == 1 ? ' selected="selected"' : '')?>>1</option>
								<option value="2"<?=(config_item('currency_decimals') == 2 ? ' selected="selected"' : '')?>>2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('decimal_separator')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" value="<?=config_item('decimal_separator')?>" name="decimal_separator">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('thousand_separator')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" value="<?=config_item('thousand_separator')?>" name="thousand_separator">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('tax')?> 1</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control money" value="<?=config_item('default_tax')?>" id="system_settings_tax1" name="default_tax">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('tax')?> 2</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control money" value="<?=config_item('default_tax2')?>" id="system_settings_tax2" name="default_tax2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('tax_decimals')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="tax_decimals" class="form-control">
								<option value="0"<?=(config_item('tax_decimals') == 0 ? ' selected="selected"' : '')?>>0</option>
								<option value="1"<?=(config_item('tax_decimals') == 1 ? ' selected="selected"' : '')?>>1</option>
								<option value="2"<?=(config_item('tax_decimals') == 2 ? ' selected="selected"' : '')?>>2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('quantity_decimals')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="quantity_decimals" class="form-control">
								<option value="0"<?=(config_item('quantity_decimals') == 0 ? ' selected="selected"' : '')?>>0</option>
								<option value="1"<?=(config_item('quantity_decimals') == 1 ? ' selected="selected"' : '')?>>1</option>
								<option value="2"<?=(config_item('quantity_decimals') == 2 ? ' selected="selected"' : '')?>>2</option>
                            </select>
                        </div>
                    </div>
                    <?php $date_format = config_item('date_format'); ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('date_format')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="date_format" class="form-control">
                                <option value="%d-%m-%Y"<?=($date_format == "%d-%m-%Y" ? ' selected="selected"' : '')?>><?=strftime("%d-%m-%Y", time())?></option>
                                <option value="%m-%d-%Y"<?=($date_format == "%m-%d-%Y" ? ' selected="selected"' : '')?>><?=strftime("%m-%d-%Y", time())?></option>
                                <option value="%Y-%m-%d"<?=($date_format == "%Y-%m-%d" ? ' selected="selected"' : '')?>><?=strftime("%Y-%m-%d", time())?></option>
                                <option value="%Y.%m.%d"<?=($date_format == "%Y.%m.%d" ? ' selected="selected"' : '')?>><?=strftime("%Y.%m.%d", time())?></option>
                                <option value="%d.%m.%Y"<?=($date_format == "%d.%m.%Y" ? ' selected="selected"' : '')?>><?=strftime("%d.%m.%Y", time())?></option>
                                <option value="%m.%d.%Y"<?=($date_format == "%m.%d.%Y" ? ' selected="selected"' : '')?>><?=strftime("%m.%d.%Y", time())?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('enable_languages')?> </label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="enable_languages" />
                                <input type="checkbox" <?php if(config_item('enable_languages') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="enable_languages">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('use_gravatar')?></label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="use_gravatar" />
                                <input type="checkbox" <?php if(config_item('use_gravatar') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="use_gravatar">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('allow_client_registration')?></label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="allow_client_registration" />
                                <input type="checkbox" <?php if(config_item('allow_client_registration') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="allow_client_registration">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('use_recaptcha')?></label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="use_recaptcha" />
                                <input type="checkbox" <?php //if(config_item('use_recaptcha') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="use_recaptcha">
                                <span></span>
                            </label>
                        </div>
                        <span class="help-block m-b-0">Set your Keys in application/config/recaptcha.php</span>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('captcha_login')?></label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="captcha_login" />
                                <input type="checkbox" <?php //if(config_item('captcha_login') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="captcha_login">
                                <span></span>
                            </label>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('file_max_size')?> <span class="text-danger">*</span> </label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" value="<?=config_item('file_max_size')?>" name="file_max_size" data-type="digits" data-required="true">
                        </div>
                        <span class="help-block m-b-0">KB</span>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('allowed_files')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" value="<?=config_item('allowed_files')?>" name="allowed_files">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('client_create_project')?></label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="client_create_project" />
                                <input type="checkbox" <?php if(config_item('client_create_project') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="client_create_project">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('auto_close_ticket')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" value="<?=config_item('auto_close_ticket')?>" name="auto_close_ticket">
                        </div>
                        <span class="help-block m-b-0"><?=lang('auto_close_ticket_after')?></span>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('ticket_start_number')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <input type="text" name="ticket_start_no" class="form-control" value="<?=config_item('ticket_start_no')?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_department')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select name="ticket_default_department" class="form-control">
								<?php foreach (App::get_by_where('departments',array()) as $key => $d) { ?>
								<option value="<?=$d->deptid?>"<?=(config_item('ticket_default_department') == $d->deptid ? ' selected="selected"' : '')?>><?=$d->deptname?></option>
								<?php } ?>
                            </select>
                        </div>
						<span class="help-block m-b-0"><?=lang('default_ticket_department')?></span>
                    </div>
					<div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('stop_timer_logout')?></label>
                        <div class="col-lg-3">
                            <label class="switch">
                                <input type="hidden" value="off" name="stop_timer_logout" />
                                <input type="checkbox" <?php if(config_item('stop_timer_logout') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="stop_timer_logout">
                                <span></span>
                            </label>
                        </div>
                    </div>
					<div class="text-center m-t-30">
                        <button id="system_settings_save" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
                </div>
            </div>
        </form>
            <?php
            break;
    }
    ?>
    </div>
    <!-- End Form -->
</div>