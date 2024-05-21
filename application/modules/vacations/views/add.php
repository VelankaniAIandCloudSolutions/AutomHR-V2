<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($vocation['id'])&&!empty($vocation['id'])) 
            {  
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<h4 class="modal-title"><?php echo $vocation['vocation']?$vocation['vocation']:''; ?> Vacation</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example','id'=> 'addVacation'); echo form_open_multipart('vacations/add', $attributes); 
			if(isset($vocation['id'])&&!empty($vocation['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $vocation['id']; ?>">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label>Vacation <span class="text-danger">*</span></label>
					<input type="text" name="vocation" class="form-control" value="<?php echo isset($vocation['vocation'])?$vocation['vocation']:''; ?>" required>
				</div>

				<div class="form-group">
					<?php 
						$active_selected = "selected";
						$inactive_selected = "";
							if(isset($vocation['status'])&&$vocation['status']==0)
							{
								$active_selected = "";
								$inactive_selected = "selected";
							}
					 ?>
					<select class="select2-option form-control" name="status" required> 								 
						<option value="1" <?php echo $active_selected;  ?> >Active</option>
						<option value="0" <?php echo $inactive_selected;  ?> >Inactive</option>
					</select>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn submit_vacation">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>