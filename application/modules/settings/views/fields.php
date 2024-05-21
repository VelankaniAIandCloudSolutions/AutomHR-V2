<div class="p-0">
	<div class="col-lg-12 p-0">
		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url() . 'settings/fields/module', $attributes);
		?>
			<div class="panel panel-white">
				<div class="panel-heading">
					<h3 class="panel-title p-5"><?=lang('custom_fields')?></h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-2 control-label"><?= lang('module') ?> <span class="text-danger">*</span> </label>
						<div class="col-lg-3">
							<div class="m-b">
								<select name="module" class="form-control" required id="module">
									<option value="clients">Clients</option>
									<option value="tickets">Tickets</option>
									<option value="leads">Leads</option>
								</select>
							</div>
						</div>
					</div>
					<div class="select_department" style="display:none;">
						<div class="form-group">
							<label class="col-lg-2 control-label"><?= lang('department') ?> <span class="text-danger">*</span> </label>
							<div class="col-lg-3">
								<div class="m-b">
									<select name="department" class="form-control">
										<?php $dept = $this->db->get('departments')->result(); ?>
										<?php foreach($dept as $d) : ?>
											<option value="<?=$d->deptid?>"><?=$d->deptname?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="text-center m-t-30">
						<button type="submit" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#module").change(function(){
        $(this).find("option:selected").each(function(){
            if($(this).attr("value")=="tickets"){
                $(".select_department").show();
            }
            else{
                $(".select_department").hide();
            }
        });
    }).change();
});
</script>