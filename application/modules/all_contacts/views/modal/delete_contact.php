

<div class="modal-dialog modal-dialog-centered" id="delete_contact">
	<div class="modal-content">
		<div class="modal-body">
			<form method="POST">
				<div class="form-head">
					<h3>Delete Contact</h3>
                    <p>Are you sure want to delete?</p>
				</div>
				
				<input type="hidden" name="id" value="<?php echo $id;?>" id="contact_id">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" class="btn continue-btn" id="submit_delete_contact">Delete</button>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn continue-btn">Cancel</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>