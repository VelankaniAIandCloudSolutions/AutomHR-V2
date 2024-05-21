<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <div class="panel panel-white">
            <div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil"></i> <?=lang('project_settings')?> - <span class="text-danger"><?php echo Project::by_id($project_id)->project_title; ?></span>
				</h3>
			</div>
            <!-- Start Settings -->
            <?php if (User::is_admin()) { ?>
			<div class="panel-body project-settings">
  
		<!-- checkbox -->
			<?php
			$attributes = array('class' => 'bs-example form-horizontal');
			echo form_open('projects/settings', $attributes);
			$current_settings = Project::by_id($project_id)->settings;
			if ($current_settings == NULL) $current_settings = '{"settings":"on"}';
			$settings = json_decode($current_settings);

                foreach (Project::permissions() as $key => $p) { ?>
				<div class="checkbox">
					<label class="checkbox-custom">
						<input name="<?=$p->setting?>" <?php
						if ( array_key_exists($p->setting, $settings) ) {
							$chk = TRUE;
							echo "checked=\"checked\"";
						}
						?>   type="checkbox">
						<i class="fa fa-fw fa-square-o <?=(isset($chk)) ? 'checked' : ''; ?>"></i>
						<?=lang($p->setting)?>
					</label>
				</div>
                <?php } ?>
                        
				<input type="hidden" name="project_id" value="<?=$project_id?>">
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
        </form>
<!-- End Settings -->
<?php } ?>
        </div>
    </div>
</div>