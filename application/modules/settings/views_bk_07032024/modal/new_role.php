<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title">Add Reporter</h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'leadReporterAdd'); echo form_open(base_url().'settings/lead_reporter_add',$attributes); ?>
		<?php echo validation_errors(); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label">Role Name<span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="Role Name" name="role_name" id="role_name">
						<?php echo form_error('reporter_name'); ?>
						<span style="color:red;display: none;" id="reporter_name_error">Role Name Fields is Required</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Role Position <span class="text-danger">*</span></label>
					<div class="col-lg-8">
					<select class="form-control" style="width:100%;" name="role_position" id="role_position">
							<option value="" disabled selected>Choose</option>
						<?php $this->db->order_by('role_order',ASC); $all_roles = $this->db->get_where('role_management',array('role_order !=' => 1))->result_array(); 
						foreach($all_roles as $role){  for($i=0;$i<2;$i++){
							if($i == 0 ){ $s = "(Before)"; } 
							if($i == 1 ){ $s = "(After)"; }  ?>
							<option value="<?php echo $role['role_order']; ?>"><?php echo $role['role_name'].' '.$s; ?></option>
						<?php } } ?>
					</select>
				</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button class="btn btn-success" id="lead_reporter_add">Add Reporter</button>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	// $(document).ready(function(){
	// 	$('#add_reporter_btn').click(function(){
	// 		var reporter_name = $('#reporter_name').val();
	// 		var reporter_email = $('#reporter_email').val();
	// 		if(reporter_name == '')
	// 		{
	// 			$('#reporter_name_error').css('display','');
	// 			$('#reporter_email_error').css('display','none');
	// 			return false;
	// 		}
	// 		if(reporter_email == '')
	// 		{
	// 			$('#reporter_name_error').css('display','none');
	// 			$('#reporter_email_error').css('display','');
	// 			return false;
	// 		}else{
	// 			var result = isEmail(reporter_email);
	// 			if(result == false)
	// 			{
	// 				$('#reporter_name_error').css('display','none');
	// 				$('#reporter_email_error').css('display','none');
	// 				$('#reporter_email_error_invalid').css('display','');
	// 				return false;
	// 			}
	// 		}

	// 	});

	// 	function isEmail(email) {
	// 		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	// 		return regex.test(email);
	// 	}
	// });

</script>