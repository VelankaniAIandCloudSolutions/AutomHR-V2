<div class="content">
	<div class="row">
	   <div class="col-md-6 col-md-offset-3">
			<h4 class="page-title"><?='Edit Notice';?></h4>
			<?php  
			if(!isset($rules_det) || empty($rules_det)){ redirect(base_url().'rules_regulation');} 
			$attributes = array('class' => 'bs-example');
			echo form_open(base_url().'rules_regulation/edit',$attributes); ?>
				<div class="form-group">
					<label> Description </label>
					<textarea class="form-control" name="rule_description"> <?=$rules_det[0]['content']?> </textarea>
				</div> 
				<div class="m-t-20 text-center">
					<input type="hidden" name="rule_tbl_id" value="<?=$rules_det[0]['id']?>">
					<button class="btn btn-primary" type="submit"> Update Rule </button>
					<a href="<?php echo base_url().'rules_regulation';?>" >
						<button class="btn btn-danger" type="button"> Cancel </button>
					</a>
				</div>
			</form>
	   </div> 
	</div>
</div>