
<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-8">
			<h4 class="page-title m-b-0"><?php echo lang('apptitude_answers');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
		        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
		        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
		        <li class="active breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/apptitude_result"><?php echo lang('apptitude_results');?></a></li>
		        <li class="breadcrumb-item"><?php echo lang('apptitude_answers');?></li>
			</ul>
		</div>
		<div class="col-sm-4  text-right m-b-20">     
		<a class="btn back-btn" href="<?=base_url()?>jobs/apptitude_result"><i class="fa fa-chevron-left"></i> <?php  echo lang('back');?></a>
		</div>
	</div>
</div>
	<div class="row">
		 
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="job-info job-widget">
				<h3 class="job-title"><?php echo $user_detail['first_name'].' '.$user_detail['last_name']; ?></h3>
				<span class="job-dept"><?php echo $job_details['job_title']; ?></span>
				
			</div>


			<div class="job-content job-widget">
				<div class="job-desc-title">
					<h4><?php  echo lang('questions')?></h4>
				</div>
				
					<?php 

					foreach ($categories as $key => $category) {
						$category_name[$category->id] = $category->category_name;
					}
					$total_mark = array();
					$total_correct = array();
					$total_wrong = array();
					 foreach($question_list as $list){
					 	$aptitude_id[$list->question_id]= $list->aptitude_id;
					 	$category_questions[$list->category_id][] = $list->question_id;
					 	$question[$list->question_id]= $list->question;
					 	if($list->question_type==1){
					 		$answer[$list->question_id]= $list->answer;
					 	}else{
					 		$answer[$list->question_id]= $list->answer_explanation;
					 	}
					 	
					 	$user_answer[$list->question_id]= $list->user_answer;
					 	$answer_status[$list->question_id]= $list->answer_status;
					 	$question_type[$list->question_id]= $list->question_type;
					 	$option_a[$list->question_id]= $list->a;
					 	$option_b[$list->question_id]= $list->b;
					 	$option_c[$list->question_id]= $list->c;
					 	$option_d[$list->question_id]= $list->d;
					 			/*result summary*/
							
							
							$total_mark [] = $list->aptitude_id;
							if($list->answer_status==1){
							$total_correct[] = $list->aptitude_id;
							}else{
							$total_wrong[] = $list->aptitude_id;
							}
					 }

					 foreach ($category_questions as $category_id => $questions){
					 	?><h2><b><?php echo $category_name[$category_id]; ?></b></h2><?php 
					 	$i=1;
					 	foreach ($questions as $key => $quest_id) {
					 		?>
					 		<div class="job-description">
					 			<label><?php echo $i++.')'. htmlspecialchars($question[$quest_id]); ?></label>
					 			<?php if($question_type[$quest_id]==1){ /* multiple choice questions*/
					 				?>
					 				<ul>
							<li><?php echo htmlspecialchars($option_a[$quest_id]);?></li>
							<li><?php echo htmlspecialchars($option_b[$quest_id]);?></li>
							<li><?php echo htmlspecialchars($option_c[$quest_id]);?></li>
							<li><?php echo htmlspecialchars($option_d[$quest_id]);?></li>
							
							</ul>	
							<span><b><?php echo lang('correct_answer');?> :</b>  <?php echo $answer[$quest_id];?></span>
							<span><b><?php echo lang('user_answer');?> :</b>  <?php echo $user_answer[$quest_id];?></span>
							<span><b><?php echo lang('status')?> :</b>  <?php if($answer_status[$quest_id]==1) {
								echo '<label class="badge badge-success">'.lang('correct').'</label>';
							}else{
								echo '<label class="badge badge-danger">'.lang('wrong').'</label>';
							} ?></span>
							<?php
					 			}else{	/*open end questions*/
					 				?>
					 			</br>
					 				<span><b><?php echo lang('correct_answer');?> :</b>  <?php echo $answer[$quest_id];?></span></br>
							<span><b><?php echo lang('user_answer');?> :</b>  <?php if($user_answer[$quest_id]==NULL){
								echo '--';}else{ echo $user_answer[$quest_id];} ?></span>
							</br>
							<span><b><?php echo lang('status')?> :</b>  
								<?php
								if($answer_status[$quest_id]==NULL){
									?>
									<a class="badge badge-success" href="<?php echo site_url('jobs/change_answer_status/').$aptitude_id[$quest_id].'/1/'.$this->uri->segment(3).'/'.$this->uri->segment(4);?>"><?php echo lang('correct');?></a>
									<a  class="badge badge-danger"  href="<?php echo site_url('jobs/change_answer_status/').$aptitude_id[$quest_id].'/0/'.$this->uri->segment(3).'/'.$this->uri->segment(4);?>"><?php echo lang('wrong');?></a>
									<?php 
								}else{

								 if($answer_status[$quest_id]==1) {
								?><label class="badge badge-success"><?php echo lang('correct');?></label><?php 
							}else{
								?><label class="badge badge-danger"><?php echo lang('wrong');?></label><?php 
							} ?></span>
					 				<?php 
					 			}

					 		} ?>
							 
							</div>

					 		<?php
					 	}
					 } 
					 
		?>

			</div>
		</div>



		<div class="col-md-4">
			<div class="job-det-info job-widget">
				<h2><?php echo lang('result_summary') ?></h2>
				<div class="info-list">
					<span><i class="fa fa-bar-chart"></i></span>
					<h5><?php echo lang('total_mark');?></h5>
					<p><?php echo count($total_mark);?></p>
				</div>
				<div class="info-list">
					<span><i class="fa fa-money"></i></span>
					<h5><?php echo lang('correct_answers');?></h5>
					<p><?php echo count($total_correct)?></p>
				</div>
				<div class="info-list">
					<span><i class="fa fa-suitcase"></i></span>
					<h5><?php echo lang('wrong_answers');?></h5>
					<p><?php echo count($total_wrong)?></p>
				</div>
				
				
			</div>
		</div>
	</div>
</div>