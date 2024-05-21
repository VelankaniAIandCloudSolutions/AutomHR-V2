<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>
<div class="main-wrapper">
	<div class="header">
		<div class="header-left">
			<a href="<?=base_url()?>" class="logo">
			<img src="<?=base_url()?>assets/images/NewHrms_login_logo.png" width="40" height="40" alt="">
			</a>
		</div>
		<div class="page-title-box float-left">
			<h3>HRMSMAXX</h3>
		</div>
	</div>
	<div class="page-wrapper job-wrapper">
		<div class="content container">
			<div class="row">
				<div class="col-12">
					<h4 class="page-title"><?php echo lang('jobs');?></h4>
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
		</div>
	</div>
</div>