<div class="modal fade in question-modal" id="free_question_modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog  modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" id="close_btn" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h3><?php echo lang('result_for_your_test');?>
			</br>
					
				</h3>
			</div>
			<div class="modal-body">
				<!-- <div class="alert alert-success success"></div> -->
				<!-- <div class="alert alert-danger error"></div> -->
				<div class="form-horzontal">
					<?php 

					$result_status =0;
					if($result_display->num_rows()==0){
						$result_status =1;
					}
					if($result_status == 1){?>
					<div class="col-md-12">
						<div class="card-box">
							<h3 class="m-b-0"><?php echo lang('correct_answers');?> : <span class="text-success"><b><?php if(isset($total_correct)){ echo $total_correct;}?></b></span></h3>
						</div>
						<div class="card-box">
							<h3 class="m-b-0"><?php echo lang('wrong_answer');?> : <span class="text-danger"><b><?php if(isset($total_wrong)){ echo $total_wrong;}?></b></span></h3>
						</div>
					</div>
				<?php }else{
					?>
					<div class="col-md-12">
						<div class="card-box">
							<h3 class="m-b-0"><?php echo lang('result_will_be_updated_soon');?> </h3>
						</div>
						
					</div><?php 
				} ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="modal-footer">
				<p class="m-b-20"><?php  echo lang('please_click_next_to_move_main_menu');?></p>
				<a type="button" href="<?=base_url()?>candidates/interviewing" class="btn btn-primary btn-lg submit-btn"><?php echo lang('next');?></a>
			</div>
		</div>
	</div>
</div>