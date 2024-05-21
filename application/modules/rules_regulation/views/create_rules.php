<div class="content">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h4 class="page-title"><?='Create Rule';?></h4>
			<?php $attributes = array('class' => 'bs-example');
			echo form_open(base_url().'rules_regulation/add',$attributes); ?> 
				<div class="form-group">
					<label> Description </label>
					<textarea class="form-control" name="rule_description" required> </textarea>
				</div> 
				<div class="m-t-20 text-center">
					<button class="btn btn-primary" type="submit"> Create </button>
					<a href="<?php echo base_url().'rules_regulation';?>" >
						<button class="btn btn-danger" type="button"> Cancel </button>
					</a>
				</div>
			</form>
		</div> 
	</div>
</div>