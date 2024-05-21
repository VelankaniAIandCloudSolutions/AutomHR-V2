<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title">Edit Reporter</h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'leadReporterEdit'); echo form_open(base_url().'settings/lead_reporter_edit/'.$reporter_details['reporter_id'],$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label">Reporter Name<span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="Reporter Name" name="reporter_name" id="reporter_name" value="<?php echo $reporter_details['reporter_name']; ?>">
						<?php echo form_error('reporter_name', '<div class="error">', '</div>'); ?>
						<span style="color:red;display: none;" id="reporter_name_error-edit">Reporter Name Fields is Required</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Reporter Email <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control money" placeholder="Reporter Email" name="reporter_email" id="reporter_email" value="<?php echo $reporter_details['reporter_email']; ?>">
						<?php echo form_error('reporter_email', '<div class="error">', '</div>'); ?>
						<span style="color:red;display: none;" id="reporter_email_error_edit">Reporter Email Fields is Required</span>
						<span style="color:red;display: none;" id="reporter_email_error_invalid_edit">Invalid Email ID</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button id="lead_reporter_edit" class="btn btn-success">Update Reporter</button>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	// // $(document).ready(function(){
	// 	$('#edit_reporter_btn').click(function(){
	// 		var reporter_name = $('#reporter_name').val();
	// 		var reporter_email = $('#reporter_email').val();
	// 		if(reporter_name == '')
	// 		{
	// 			$('#reporter_name_error_edit').css('display','');
	// 			$('#reporter_email_error_edit').css('display','none');
	// 			return false;
	// 		}
	// 		if(reporter_email == '')
	// 		{
	// 			$('#reporter_name_error_edit').css('display','none');
	// 			$('#reporter_email_error_edit').css('display','');
	// 			return false;
	// 		}else{
	// 			var result = isEmail(reporter_email);
	// 			if(result == false)
	// 			{
	// 				$('#reporter_name_error_edit').css('display','none');
	// 				$('#reporter_email_error_edit').css('display','none');
	// 				$('#reporter_email_error_invalid_edit').css('display','');
	// 				return false;
	// 			}
	// 		}

	// 	});

	// 	function isEmail(email) {
	// 		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	// 		return regex.test(email);
	// 	}
	// // });

</script>