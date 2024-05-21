<?php
	$jtype=array(0=>'unassigned');
	    foreach ($offer_jobtype as $jkey => $jvalue) {
	            $jtype[$jvalue->id]=$jvalue->job_type;                        
	     }
	
	?>

		<div class="content">
			<div class="page-header">
			<div class="row">
				<div class="col-md-6">
					<h4 class="page-title m-b-0"><?php echo lang('interview_questions');?></h4>
					<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs"><?php echo lang('recruiting_process');?></a></li>
				<li class="breadcrumb-item"><?php echo lang('interview_questions');?></li>
			</ul>
				</div>
				 <div class="col-md-6  text-right m-b-20">     
				              <a class="btn add-btn" href="<?=base_url()?>jobs"><i class="fa fa-chevron-left"></i> <?php echo lang('back');?></a>

						<?php if(App::is_permit('menu_interview_questions','create')){?><a href="<?php echo base_url().'/interview_questions/category';?>" data-toggle="ajaxModal" class="btn add-btn m-r-5"> <?php echo lang('add_category');?></a><?php }?>
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
				<?php } // foreach end */ ?>	 
			</div>

			
				
				<div class="row">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table class="table table-striped custom-table mb-0 AppendDataTables">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('category_name');?></th>
										<th><?php echo lang('department');?></th>
										<th><?php echo lang('job_name');?></th>
										<?php if(App::is_permit('menu_interview_questions','write')==true || App::is_permit('menu_interview_questions','delete')==true)
										{
										?>
										<th class="text-center"><?php echo lang('action');?></th>
										<?php
										}
										?>
									</tr>
								</thead>
								<tbody>
									
										<?php 
										$i=1;
										foreach($categories as $category){
											?>
								  <tr>
											<td><?php echo $i++;?></td>
											<td><?php echo $category->category_name;?></td>
											<td><?php echo $category->deptname;?></td>
											<td><?php echo $category->job_title;?></td>
											<?php if(App::is_permit('menu_interview_questions','write')==true || App::is_permit('menu_interview_questions','delete')==true)
											{
											?>
											<td class="text-center">
											<div class="dropdown dropdown-action">
											<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

											<div class="dropdown-menu dropdown-menu-right">
												<?php if(App::is_permit('menu_interview_questions','write')){?><a class="dropdown-item" href="<?php echo base_url(); ?>interview_questions/category/<?php echo $category->id; ?>" class="dropdown-item" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i> <?php echo lang('edit');?></a><?php }?>
												<?php if(App::is_permit('menu_interview_questions','delete')){?><a class="dropdown-item" href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_category" onclick="delete_category(<?php echo $category->id;?>)"><i class="fa fa-trash-o m-r-5"></i> <?php echo lang('delete');?></a><?php }?>
											</div>

											</div>
											</td>
											<?php
											}
											?>
									</tr>
									<?php 
										}
										?>
										
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			
			<hr>
				<div class="row">
					<div class="col-sm-5 col-5">
						<h4 class="page-title"><?php echo lang('manage_interview_questions');?></h4>
					</div>
					<div class="col-sm-7 col-7 text-right m-b-30">
						<?php if(App::is_permit('menu_interview_questions','create')){?><a href="<?php echo base_url(); ?>interview_questions/add_questions" class="btn add-btn"> <?php echo lang('add_questions');?></a><?php }?>
						
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table class="table table-striped custom-table mb-0 AppendDataTables">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('question_type');?></th>
										<th><?php echo lang('questions');?></th>
										<th><?php echo lang('a-option');?></th>
										<th><?php echo lang('b-option');?></th>
										<th><?php echo lang('c-option');?></th>
										<th><?php echo lang('d-option');?></th>
										<th><?php echo lang('correct_answer');?></th>
										<th><?php echo lang('department');?></th>
										<th><?php echo lang('job_name');?></th>
										<th><?php echo lang('category_name');?></th>
										<th><?php echo lang('status');?></th>
										<?php if(App::is_permit('menu_interview_questions','write')==true || App::is_permit('menu_interview_questions','delete')==true)
										{
										?>
										<th class="text-center"><?php echo lang('action');?></th>
										<?php 
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php 

									$i=1;
									foreach($questions_list as $list){
										?>
										<tr>
										<td><?php echo $i++;?></td>
										<td><?php if($list->question_type==1)  {
											echo lang("multiple_choice_question");
										}elseif($list->question_type==2){
											echo lang('open_end_question');
										} ?></td>
										<td><?php echo htmlspecialchars($list->question);?></td>
										<td><?php echo htmlspecialchars($list->a);?></td>
										<td><?php echo htmlspecialchars($list->b);?></td>
										<td><?php echo htmlspecialchars($list->c);?></td>
										<td><?php echo htmlspecialchars($list->d);?></td>
										<td><?php echo htmlspecialchars($list->answer);?></td>
										<td><?php echo $list->deptname;?></td>
										<td><?php echo $list->job_title;?></td>
										<td><?php echo $list->category_name;?></td>

										<td class="text-center">
							<div class="dropdown action-label">
								<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-dot-circle-o text-danger"></i> <?php echo ($list->status == 0) ?  lang("inactive") :  lang("active");?>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?php echo site_url('interview_questions/question_status/1').'/'.$list->id;?>"><i class="fa fa-dot-circle-o text-info"></i> <?php echo lang('active');?></a>
									<a class="dropdown-item" href="<?php echo site_url('interview_questions/question_status/0').'/'.$list->id;?>"><i class="fa fa-dot-circle-o text-success"></i> <?php echo lang('inactive')?></a>
								</div>
							</div>
						</td>
								<?php if(App::is_permit('menu_interview_questions','write')==true || App::is_permit('menu_interview_questions','delete')==true)
										{
										?>
										<td class="text-center">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>


												<div class="dropdown-menu dropdown-menu-right">
													<?php if(App::is_permit('menu_interview_questions','write')){?><a href="<?php echo base_url(); ?>interview_questions/edit_questions/<?php echo $list->id ?>" class="dropdown-item"><i class="fa fa-pencil m-r-5"></i> <?php echo lang('edit');?></a><?php }?>
													<?php if(App::is_permit('menu_interview_questions','delete')){?><a href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_question" onclick="delete_question(<?php echo $list->id;?>)"><i class="fa fa-trash-o m-r-5"></i> <?php echo lang('delete');?></a><?php }?>
												</div>

											</div>
										</td>
										<?php 
										}
										?>
									</tr>
									<?php 
									}?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		
		</div>
	</div>
</div>
<!-- Delete Job Modal -->
<div class="modal custom-modal fade" id="delete_category" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo lang('delete');?></h3>
					<p><?php echo lang('are_you_sure_want_to_delete');?></p>
				</div>
				<div class="modal-btn delete-action">
					<form action="<?php echo site_url('interview_questions/delete_category');?>" method="post" >
						<input type="hidden" name="delete_id" id="delete_category_id" value="">
					<div class="row">
						<div class="col-sm-6">
							<button type="submit" name="delete" value="delete" class="btn btn-primary continue-btn"><?php echo lang('delete');?></button>
						</div>
						<div class="col-sm-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn"><?php echo lang('cancel');?></a>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Job Modal -->
       
       <!-- Delete Job Modal -->
<div class="modal custom-modal fade" id="delete_question" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo lang('delete');?></h3>
					<p><?php echo lang('are_you_sure_want_to_delete');?></p>
				</div>
				<div class="modal-btn delete-action">
					<form action="<?php echo site_url('interview_questions/delete_question');?>" method="post" >
						<input type="hidden" name="delete_id" id="delete_quest_id" value="">
					<div class="row">
						<div class="col-sm-6">
							<button type="submit" name="delete" value="delete" class="btn btn-primary continue-btn"><?php echo lang('delete');?></button>
						</div>
						<div class="col-sm-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn"><?php echo lang('cancel');?></a>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Job Modal -->
