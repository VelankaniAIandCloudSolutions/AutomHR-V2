					
<div class="content">
	<div class="page-header">
					<div class="row">
		<div class="col-md-6">
			<h4 class="page-title m-b-0"><?php echo lang('candidate_list');?></h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li  class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard"><?php echo lang('recruiting_process');?></a></li>
				<li class="breadcrumb-item"><?php echo lang('candidate_list');?></li>
			</ul>
		</div>
		<div class="col-md-6 text-right m-b-30">
				<?php if(App::is_permit('menu_candidate_list','create')){?><a href="<?php echo base_url(); ?>jobs/add_candidates" class="btn add-btn"> <?php echo lang('add_candidates');?></a><?php } ?>
				<?php if(App::is_permit('menu_candidate_list','create')){?><a href="<?php echo base_url(); ?>jobs/parse_resumes_view" class="btn add-btn mr-2">Parse Resumes</a><?php } ?>
				
		
			</div>
	</div>
</div>
	  <?php //$this->load->view('sub_menus');?>
	<!--Canditates List-->


	
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 AppendDataTables">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo lang('name');?></th>
						<th><?php echo lang('mobile_number');?> </th>
						<th><?php echo lang('email')?></th>
						
						<th><?php echo lang('created_date')?></th>
						
						<?php if(App::is_permit('menu_candidate_list','write')==true || App::is_permit('menu_candidate_list','delete')==true)
						{
						?>
						
						<th class="text-center"><?php echo lang('action')?></th>
						<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					foreach($candidates_list as $list)
					{
						?>
						<tr>
						<td><?php echo $i++;?></td>
						<td class="text-capitalize"><?php echo $list->first_name.' '.$list->last_name;?></td>
						<td><?php echo $list->phone_number;?></td>
						<td><?php echo $list->email?></td>
						<td><?php echo date('d-m-Y',strtotime($list->created_at));?></td>
						<?php 
						if(App::is_permit('menu_candidate_list','write')==true || App::is_permit('menu_candidate_list','delete')==true)
						{
						?>
						<td class="text-center">

						<div class="dropdown dropdown-action">
						<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
						<div class="dropdown-menu dropdown-menu-right">
						<?php if(App::is_permit('menu_candidate_list','write')){?><a href="<?php echo site_url('jobs/add_candidates/'.$list->id); ?>" class="dropdown-item"><i class="fa fa-edit m-r-5"></i>Edit </a><?php } ?>
						<?php if(App::is_permit('menu_candidate_list','delete')){?><a href="<?php echo site_url('jobs/delete_candidate/'.$list->id)?>" class="dropdown-item" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a><?php } ?>
						</div>
						</div>

						</td>
						<?php
						}
						?>
					</tr>
						<?php 
					} ?>
					
				</tbody>
			</table>
		</div>
	
</div>

	<!--/Canditates List-->