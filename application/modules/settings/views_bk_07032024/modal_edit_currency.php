<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('edit_currency')?></h4>
        </div>

                <?php
                $i = $this->db->where('code',$code)->get('currencies')->row();
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/edit_currency',$attributes); ?>
                <input type="hidden" name="oldcode" value="<?=$i->code?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('currency_code')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->code?>" name="code">
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('currency_name')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->name?>" name="name">
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('currency_symbol')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->symbol?>" name="symbol">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('xrate')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->xrate?>" name="xrate">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
                        <button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
