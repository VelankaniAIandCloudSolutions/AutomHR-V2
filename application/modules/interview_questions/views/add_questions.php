<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>

                <div class="content">
                	<div class="page-header">
					<div class="row">
						<div class="col-8">
							<h4 class="page-title m-b-0"><?php echo lang('recruiting_process');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>interview_questions"><?php echo lang('interview_questions');?></a></li>
						        <li class="active breadcrumb-item"><?php echo lang('add_questions');?></li>
			</ul>
		</div>
						 <div class="col-sm-4  text-right m-b-20">     
				              <a class="btn add-btn" href="<?=base_url()?>interview_questions"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
				          </div>
					</div>
					</div>					
	<div class="row">
		<?php foreach ($jobs as $key => $value) {	//print_r($value);exit();	// foreach starts 
			?>
		<div class="col-md-6">
			<!-- <a class="job-list" href="<?=base_url()?>jobs/jobview/<?=$value->id?>"> -->
			<div class="job-list">
				<div class="job-list-det">
					<div class="job-list-desc">
						<h3 class="job-list-title"><?=ucfirst($value->title);?></h3>
						<h4 class="job-department"><?=ucfirst($jtype[$value->job_type]);?></h4>
					</div>
					<div class="job-type-info">
						<span >
						<a class='job-types' href="<?=base_url()?>jobs/apply/<?=$value->id?>/<?=$value->job_type?>"><?php echo lang('apply');?></a>
						</span>
					</div>
				</div>
				<div class="job-list-footer">
					<ul>
						<!-- <li><i class="fa fa-map-signs"></i> California</li> -->
						<li><i class="fa fa-money"></i> <?=$value->salary;?></li>
						<li><i class="fa fa-clock-o"></i> <?=Jobs::time_elapsed_string($value->created); ?></li>
					</ul>
				</div>
			</div>
			<!-- </a> -->
		</div>
		<?php } // foreach end ?>	 
	</div>
	<div class="card">
		
		<div class="card-body">
			<form action="<?php echo site_url('interview_questions/add_questions');?>" method="post" id="add_questions_form" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('department');?></label>
							<select class="form-control" name="department" id="department" required onchange="selectjobs(this.value);">
								<option value=""><?php echo lang('select_department');?></option>
								<?php foreach($departments as $dept){
									?><option value="<?php echo $dept->deptid;?>"><?php echo $dept->deptname;?></option>
									<?php 
								}?>
							</select>
							
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_name');?></label>
							<select class="form-control" name="job_id" id="job_id" required onchange="select_category()">
								<option value=""><?php echo lang('select_job');?></option>
							</select>
						</div>

					</div>
				</div>
				<div class="row">
										
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('category');?></label>
							<select class="form-control" name="category_id" id="category_id" required>
								<option value=""><?php echo lang('select_category');?></option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('question_type');?></label>
							<select class="form-control" name="question_type" id="question_type" required>
								<option value=""> <?php echo lang('select_question_type');?></option>
								<option value="1"><?php echo lang('multiple_choice_question');?></option>
								<option value="2"><?php echo lang('open_end_question');?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label><?php echo lang('add_questions');?></label>
							<textarea class="form-control" name="question" required></textarea>
						</div>
					</div>
				
				</div>
				<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('a-option');?></label>
							<input class="form-control" type="text" name="option_a" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('b-option');?></label>
							<input class="form-control" type="text" name="option_b" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('c-option');?></label>
							<input class="form-control" type="text" name="option_c" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('d-option');?></label>
							<input class="form-control" type="text" name="option_d" required>
						</div>
					</div>
					<div class="col-md-12">
					<div class="form-group">
							<label><?php echo lang('correct_answer');?></label>
							<select class="form-control" name="correct_answer" required>
								<option value=""> <?php echo lang('select_option')?></option>
								<option value="A"><?php echo lang('a-option');?></option>
								<option value="B"><?php echo lang('b-option');?></option>
								<option value="C"><?php echo lang('c-option');?></option>
								<option value="D"><?php echo lang('d-option');?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('add_image_to_question');?></label>
							<input type="file" class="form-control" name="question_image">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('answer_explanation');?></label>
							<textarea type="text" class="form-control" name="answer_explanation"></textarea>
						</div>
					</div>

				</div>
				<div class="row">
					 <div class="col-md-6">
						<div class="form-group">
							<label style="display:block;"><?php echo lang('status');?></label>
							<input type="radio" name="qst_status" value="1" checked> <?php echo lang('active'); ?>
							<input type="radio" name="qst_status" value="0"> <?php echo lang('inactive');?>
						</div>
					</div> 
					
				</div>
				
				<!--<div class="row">
					 <div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('code_snippets');?></label>
							<textarea type="text" class="form-control"></textarea>
						</div>
					</div> 
					 <div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('add_video_link');?></label>
							<input type="text" class="form-control">
						</div>
					</div>
					
				</div> -->
				<input type="hidden" name="save_question" value="1">
				<div class="submit-section">
					<a href="<?php echo base_url(); ?>jobs/manage" class="btn btn-danger submit-btn m-b-5" id="btnSave" type="submit"><?php echo lang('cancel');?></a>
					<input class="btn btn-primary submit-btn m-b-5" id="save_question" name="save_question" type="submit" value="<?php echo lang('save');?>">
				</div>
			</form>
		</div>
	</div>
</div>