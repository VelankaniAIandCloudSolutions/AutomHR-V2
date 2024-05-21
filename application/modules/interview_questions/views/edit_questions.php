<?php
$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content">
	<div class="row">

   <!-- $jtype=array(0=>'unassigned');
       foreach ($offer_jobtype as $jkey => $jvalue) {
               $jtype[$jvalue->id]=$jvalue->job_type;                        
        }

?> -->
     
        	  
          
                <div class="content">
                	<div class="page-header">
					<div class="row">
						<div class="col-8">
							<h4 class="page-title m-b-0"><?php echo lang('recruiting_process');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
						        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/manage"><?php echo lang('manage_questions');?></a></li>
						        <li class="active breadcrumb-item"><?php echo lang('edit_question');?></li>
			</ul>
		</div>
						 <div class="col-sm-4  text-right m-b-20">     
				              <a class="btn add-btn" href="<?=base_url()?>interview_questions"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>
				          </div>
					</div>
					</div>	
					
	<div class="row">
		<?php /* foreach ($jobs as $key => $value) {	//print_r($value);exit();	// foreach starts 
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
		<?php } // foreach end */?>	 
	</div>
	<div class="card">
		
		<div class="card-body">
			<form action="<?php echo site_url('interview_questions/edit_questions/'.$this->uri->segment(3));?>" id="edit_question_form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="question_id" value="<?php echo $this->uri->segment(3);?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('department');?></label>
							<select class="select" name="department" id="department" required onchange="selectjobs(this.value);">
								<option value=""><?php echo lang('select_department');?></option>
								<?php foreach($departments as $dept){
									?><option value="<?php echo $dept->deptid;?>" <?php if(isset($question['dept_id']) && $question['dept_id'] ==$dept->deptid){ echo "selected";}?>><?php echo $dept->deptname;?></option>
									<?php 
								}?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('job_name');?></label>
							<select class="select" name="job_id" id="job_id" required onchange="select_category()">
								<option value=""><?php echo lang('select_job');?></option>
								<?php foreach($jobs_list as $job){
									?><option value="<?php echo $job->id;?>" <?php if(isset($question['job_id']) && $question['job_id'] ==$job->id){ echo "selected";}?>><?php echo $job->job_title;?></option>
									<?php 
								}?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
										
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('category');?></label>
							<select class="select" name="category_id" id="category_id" required>
								<option value=""><?php echo lang('select_category');?></option>
								<?php foreach($categories as $category){
									?><option value="<?php echo $category->id;?>" <?php if(isset($question['category_id']) && $question['category_id'] ==$category->id){ echo "selected";}?>><?php echo $category->category_name;?></option>
									<?php 
								}?>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('question_type');?></label>
							<select class="select" name="question_type" id="question_type" required>
								<option value=""> <?php echo lang('select_question_type');?></option>
								<option value="1" <?php if(isset($question['question_type']) && $question['question_type'] ==1){ echo "selected";}?>><?php echo lang('multiple_choice_question');?></option>
								<option value="2" <?php if(isset($question['question_type']) && $question['question_type'] ==2){ echo "selected";}?>><?php echo lang('open_end_question');?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label><?php echo lang('add_questions');?></label>
							<textarea class="form-control" name="question" required><?php echo $question['question']; ?></textarea>
						</div>
					</div>
				
				</div>
				<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('a-option');?></label>
							<input class="form-control" type="text" name="option_a" required value="<?php echo $question['a'];?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('b-option');?></label>
							<input class="form-control" type="text" name="option_b" value="<?php echo $question['b'];?>" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('c-option');?></label>
							<input class="form-control" type="text" name="option_c" value="<?php echo $question['c'];?>" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('d-option');?></label>
							<input class="form-control" type="text" name="option_d" value="<?php echo $question['d'];?>" required>
						</div>
					</div>
					<div class="col-md-12">
					<div class="form-group">
							<label><?php echo lang('correct_answer');?></label>
							<select class="select" name="correct_answer" required>
								<option value=""> <?php echo lang('select_option')?></option>
								<option value="A" <?php if($question['answer']=='A'){ echo "selected";} ?>><?php echo lang('a-option');?></option>
								<option value="B" <?php if($question['answer']=='B'){ echo "selected";} ?>><?php echo lang('b-option');?></option>
								<option value="C" <?php if($question['answer']=='C'){ echo "selected";} ?>><?php echo lang('c-option');?></option>
								<option value="D" <?php if($question['answer']=='D'){ echo "selected";} ?>><?php echo lang('d-option');?></option>
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
							<textarea type="text" class="form-control" name="answer_explanation"><?php echo $question['answer_explanation'];?></textarea>
						</div>
					</div>

				</div>
				<div class="row">
					 <div class="col-md-6">
						<div class="form-group">
							<label><?php echo lang('status');?></label>
							<input type="radio" name="qst_status" value="1" <?php if($question['status']==1){ 
								echo "checked";}?>><?php echo lang('active'); ?>
							<input type="radio"  name="qst_status" value="0" <?php if($question['status']==0){ 
								echo "checked";}?>> <?php echo lang('inactive')?>
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
				 <input type="hidden" value="1" name="edit_question"> 
				
				<div class="submit-section">
					<a href="<?php echo base_url(); ?>interview_questions" class="btn btn-danger submit-btn m-b-5" id="btnSave" type="submit"><?php echo lang('cancel');?></a>
					<button name="edit_question" value="edit_question"class="btn btn-primary submit-btn m-b-5" id="edit_question" type="submit"><?php echo lang('save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>