<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="content p-t-10">
	<div class="row">
		<div class="col-8">
			<h4 class="page-title mb-3"><?php echo lang('aptitude_test_screen');?></h4>
		</div>
		<div class="col-4">
			<a href="<?php echo site_url('candidates/interviewing') ?>" class=" btn back-btn text-right"><?php echo lang('back');?></a>
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
	<div class="row">
		<div class="col-lg-12 ">
			<div class="card-box">
				<p><?php echo lang('name');?> : <b><?php echo $this->session->userdata('username'); ?></b></p>
				<!-- <p>Code : <b>#1245</b></p> -->
				<p><?php echo lang('job_title')?> : <b><?php echo $job_detail['job_title'];?></b></p>
			</div>
			<div class="card-box">
				<p class="mb-3"><?php echo lang('click_button_to_answer_your_question'); ?></p>
				<div class="row">
					<?php foreach($categories as $category){
						?>
						<div class="col-md-6 text-center mb-3">
						<a href="<?=base_url()?>candidates/questions/<?php echo $job_detail['department_id'].'/'.$job_detail['id'].'/'.$category->id; ?>" class="btn btn-success btn-block submit-btn"><?php echo  $category->category_name; ?></a>
					</div>
						<?php
					}?>
					
					
				</div>
			</div>
		</div>
	</div>
</div>