<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($wiki['id'])&&!empty($wiki['id'])) 
            {  
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<h4 class="modal-title"><?php echo $form_type; ?> Wiki</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example'); echo form_open_multipart('wiki/add', $attributes); 
			if(isset($wiki['id'])&&!empty($wiki['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $wiki['id']; ?>">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('title')?> <span class="text-danger">*</span></label>
					<input type="text" name="title" class="form-control" value="<?php echo isset($wiki['title'])?$wiki['title']:''; ?>" required>
				</div>
				<?php //if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4)){ ?>
				<div class="form-group">
					<label><?=lang('branch')?> <span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required>
					<option value="">Select</option>
					<?php
						if(!empty($branches))	{
						foreach ($branches as $branch1){ ?>
						<option value="<?=$branch1['branch_id']?>" <?php echo (!empty($wiki['branch_id']) && $wiki['branch_id']==$branch1['branch_id'])?'selected':'';?>><?=$branch1['branch_name']?></option>
						<?php } ?>
						<?php } ?>
				</select>
				</div>
				<?php //}?>
				<div class="form-group">
					<label><?=lang('description')?> <span class="text-danger">*</span></label>
					<textarea name ="description" class="form-control" required><?php echo isset($wiki['description'])?$wiki['description']:''; ?></textarea>
				</div>

				

				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>