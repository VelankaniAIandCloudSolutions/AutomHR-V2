<?php
$this->load->helper('app'); 
$template_group = 'user';
$attributes = array('class' => 'bs-example form-horizontal');
$branches = $this->db->where('branch_status','0')->get('branches')->result_array();
echo form_open('settings/templates?settings=termination_templates', $attributes);
?>
    <div class="p-0">
        <div class="col-lg-12 p-0">
            <div class="panel panel-white">
                <div class="panel-heading">
					<h3 class="panel-title p-5">Termination Template</h3>
				</div>
                <div class="panel-body">
                    <input type="hidden" name="return_url" value="<?=base_url()?>settings/?settings=termination_templates">
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('branch')?></label>
                        <div class="col-lg-12">
                            <select class="form-control" name="termination_entity" required onchange="termination_entity1(this.value)">
                                <option value="" disabled>Select</option>
                                <?php if(!empty($branches)){
                                    foreach($branches as $branch_1){
                                    ?>
                                        <option value="<?=$branch_1['branch_id']?>" <?= ($terminations['entity']==$branch_1['branch_id'])?'selected':'';?>><?=$branch_1['branch_name'];?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('subject')?></label>
                        <div class="col-lg-12">
                            <input class="form-control" name="termination_subject" id="termination_subject" value="<?php echo $terminations['subject'];?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('messages')?></label>
						<div class="col-lg-12">
							<textarea class="form-control foeditor-550" name="termination_message" id="termination_mes" required><?php echo $terminations['message'];?></textarea>
						</div>
                    </div>
					<div class="text-center m-t-30">
                        <button type="submit" class="btn btn-primary btn-lg"  name="termination_template_submit"><?=lang('save_changes')?></button>
					</div>
                </div>
				<div class="panel-footer">
					<strong><?=lang('template_tags')?></strong>
					<ul>
						<?php $tags = get_tags('termination_template'); 
                        foreach ($tags as $key => $value) { echo '<li>{'.$value.'}</li>'; } ?>
					</ul>
				</div>
            </div>
        </div>
    </div>
</form>