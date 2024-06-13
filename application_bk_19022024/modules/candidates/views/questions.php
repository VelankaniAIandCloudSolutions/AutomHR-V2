<?php
	$jtype=array(0=>'unassigned');
	foreach ($offer_jobtype as $jkey => $jvalue) {
	$jtype[$jvalue->id]=$jvalue->job_type;                        
	}
	
	?>
<div class="content p-t-10">
<div class="row">
	<div class="col-8">
		<h4 class="page-title"><?php echo lang('answer_your_questions');?></h4>
	</div>
	 <div class="col-sm-4 text-right m-b-20">            
            <a class="btn back-btn" href="<?=base_url()?>candidates/aptitude/<?php echo $this->uri->segment(3).'/'.$this->uri->segment(4);?>"><i class="fa fa-chevron-left"></i> <?php echo lang('back'); ?></a>
</div>
<div class="row">
	
</div>
<div class="card-box">
	<div class="quiz-wizard">
		<div class="row">
			<div class="col-sm-12">
				<h2><?php echo lang('questions');?></h2>
			</div>
			<div class="col-sm-12">
				<div class="clearfix"></div>
				
				<div class="" id="myWizard">
					<div style="display:none;" class="navbar">
						<div class="navbar-inner">
							<ul class="nav nav-pills">
								<?php 
								$i=1;
								foreach ($questions_list as $key => $quest) { ?>
								<li class="active"><a href="#step<?php echo $i;?>" data-toggle="tab" data-step="<?php echo $i;?>?>">step <?php echo $i++;?></a></li>
							<?php } ?>
								
							</ul>
						</div>
					</div>
					<form method="post" id="quiz_answer" action="<?php echo site_url('candidates/save_user_exam');?>" >

						<input type="hidden" name="job_id" value="<?php echo $this->uri->segment(4);?>">
						<input type="hidden" name="dept_id" value="<?php echo $this->uri->segment(3);?>">
						<input type="hidden" name="category_id" value="<?php echo $this->uri->segment(5);?>">
						<input type="hidden" name="url" value="<?php echo current_url();?>">
						<div class="tab-content">
							<?php 
							$i=1;
							foreach ($questions_list as $key => $quest) {
								$next = $i+1;
							 ?>
							<div class="tab-pane   <?php if(empty($_GET['step'])){if($i==1){ echo ' show active ';}else{ echo ' fade';}}else{if($i == $_GET['step']){echo 'active ';} else {echo 'fade';}}?>" id="step<?php echo $i;?>">

							<?php if(empty($_GET['step'])){
								
								if($i==1){ 

								?>
									<input type="hidden" name="num_count" value="<?php echo $i;?>">
								<?php 
								}
							}
							else{
								if($i == $_GET['step'])
								{
									?>
									<input type="hidden" name="num_count" value="<?php echo $i;?>">
								<?php 
								}
							}
								 ?>
								<div class="row">
									<div class="col-md-12">
										<div class="well">
											<h3><span><?php echo $i++;?>.</span> <?php echo htmlspecialchars($quest->question);?></h3>
											<input type="hidden" name="question_id[]" value="<?php echo $quest->id;?>">
											<?php if($quest->question_type ==1){?>
											<div class="row">
												<div class="col-sm-12">
													<label class="question-radio">
													<input type="radio" name="answer[<?php echo $quest->id?>]" value="A">
													<span class="checkmark"></span>
													<?php echo htmlspecialchars($quest->a);?>
													</label>
												</div>
												<div class="col-sm-12">
													<label class="question-radio">
													<input type="radio" name="answer[<?php echo $quest->id?>]"  value="B">
													<span class="checkmark"></span>
													<?php echo htmlspecialchars($quest->b);?>
													</label>
												</div>
												<div class="col-sm-12">
													<label class="question-radio">
													<input type="radio" name="answer[<?php echo $quest->id?>]"  value="C">
													<span class="checkmark"></span>
													<?php echo htmlspecialchars($quest->c);?>
													</label>
												</div>
												<div class="col-sm-12">
													<label class="question-radio">
													<input type="radio" name="answer[<?php echo $quest->id?>]" value="D">
													<span class="checkmark"></span>
													<?php echo htmlspecialchars($quest->d);?>
													</label>
												</div>
											</div>
										<?php }else{
										 ?><div class="row">
												<div class="col-md-12">
													<label><?php echo lang('answer');?></label></br>
													<textarea  name="answer[<?php echo $quest->id;?>]" rows="4" cols="50"></textarea> 
													
													
												</div>
											</div><?php 
										} ?>
										</div>
										
										<?php if($i > count($questions_list)){
											?>
								<button class="btn btn-success btn-lg"  type="submit" name="finish" value="finish" id="finish_test" ><?php echo lang('finish');?></button>
											<?php
										}else{
											?>
											<button class="btn btn-success btn-lg"  type="submit" name="next" value="<?php echo lang('next');?>" id="next" ><?php echo lang('next');?></button>
											<?php /*<a class="btn btn-success btn-lg next" href="?step=<?php echo $next; ?>"><?php echo lang('next');?></a><?php */
										}?>
									</div>
									<div class="col-md-5">
										
										
									</div>
								</div>
							</div>
						<?php } ?>
							
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php echo $this->load->view('modal/apptitude_result_modal');?>

<?php if(isset($total_mark)){
?>
<script type="text/javascript">
var enable_result_model =true;
</script>
<?php 
}else{
	?>
<script type="text/javascript">
var enable_result_model =false;
</script>
	<?php
}?>