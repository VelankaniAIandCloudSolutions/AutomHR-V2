
				
				<!-- Delete Performance Indicator Modal -->
				
					<div class="modal-dialog  modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body texxt-center">
								<div class="form-header">
									<h4><?php echo lang('delete_performance_indicator')?></h4>
									<p><?php echo lang('are_you_sure_delete')?></p>
								</div>
								<div class="modal-btn delete-action">
									<form action="<?php echo site_url('appraisal/delete_indicator')?>" method="post">
										<input type="hidden" name="id" value="<?php echo $id;?>">
									<div class="row">
										<div class="col-6">
											<input type="submit" name="submit" class="btn btn-primary continue-btn"  value="Delete">
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn"><?php echo lang('cancel');?></a>
										</div>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				
				<!-- /Delete Performance Indicator Modal -->