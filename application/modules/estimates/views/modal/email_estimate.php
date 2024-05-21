<div class="modal-dialog">
	<div class="modal-content">
		<?php $estimate = Estimate::view_estimate($id); ?>
		<div class="modal-header"> 
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('email_estimate')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'estimateEmailForm'); echo form_open(base_url().'estimates/email',$attributes); ?>
			<div class="modal-body">
				<input type="hidden" name="id" value="<?=$estimate->est_id?>">	
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=App::email_template('estimate_email','subject').' '.$estimate->reference_no?>" name="subject">
					</div>
				</div>
				<input type="hidden" name="message" class="hiddenmessage">
				<div class="message" contenteditable="false">
				<?=App::email_template('estimate_email','template_body');?>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button type="submit" class="submit btn btn-success" id="estimate_email_template"><?=lang('email_estimate')?></button>
			</div>
		</form>
	</div>
<script type="text/javascript">
	$(function(){
    $('.submit').click(function () {
        var mysave = $('.message').html();
        $('.hiddenmessage').val(mysave);
    });
});
</script>
</div>