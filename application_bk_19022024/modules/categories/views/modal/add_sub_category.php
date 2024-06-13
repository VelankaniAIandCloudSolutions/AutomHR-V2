<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Sub Category</h4>
		</div>
		<?php $attributes = array('class' => 'bs-example','id'=>'settingsDesignationForm');
        echo form_open_multipart('categories/sub_categories', $attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label>Category Name <span class="text-danger">*</span></label>
					<select name="cat_id" id="cat_id" required="required" class="form-control" >
						<option value="" selected disabled>Categories</option>
						<?php
						if($this->session->userdata('branch_id')==0){
							$categories = $this -> db -> get_where('budget_category',array()) -> result();
						}
						else{
							$branches = $this->db->select('*')->from('assigned_entities')->where('user_id',$this->session->userdata('user_id'))->get()->result_array();
							$branches = array_column($branches, 'branch_id');
							$categories = $this->db->where_in('branch_id',$branches)->get('budget_category')-> result();

						}
						
						if (!empty($categories)) { 
						foreach ($categories as $key => $d) { ?>
						<option value="<?php echo $d->cat_id; ?>" <?php if($category_id == $d->cat_id){ ?> selected <?php } ?>><?=$d->category_name?></option>
						<?php } }else{ ?>
						<option value="">No Results</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Sub-Category Name <span class="text-danger">*</span></label>
					<input type="text" name="sub_category" class="form-control" required>
				</div>
				<div class="submit-section">
					<button id="settings_designation_submit" class="btn btn-primary submit-btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>