<div class="p-0">
    <!-- Start Form -->
	<div class="col-lg-12 p-0">
		<div class="panel panel-table"> 
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-6">
						<h3 class="panel-title m-l-15">Categories</h3>
					</div>
					<div class="col-xs-6 text-right">
						<a href="<?=base_url()?>settings/add_category" data-toggle="ajaxModal" title="<?=lang('add_category')?>" class="btn btn-success btn-sm m-r-10"><?=lang('add_category')?></a>
					</div>
				</div>
			</div>
			<div class="panel-body"> 
				<div class="table-responsive">
					<table class="table table-striped custom-table m-b-0"> 
						<thead> 
							<tr> 
								<th class="th-sortable" data-toggle="class">ID</th> 
								<th><?=lang('cat_name')?></th> 
								<th><?=lang('module')?></th> 
								<th width="30"></th> 
							</tr> 
						</thead> 
						<tbody> 
							<?php $categories = $this->db->where('branch_id', $this->session->userdata('branch_id'))->get('categories')->result();
							foreach ($categories as $key => $cat) { ?>
							<tr> 
								<td><?=$cat->id?></td> 
								<td><?=$cat->cat_name?></td>
								<td><?=$cat->module?></td> 
								<td> 
									<a href="<?=base_url()?>settings/edit_category/<?=$cat->id?>" data-toggle="ajaxModal" data-placement="left" title="<?=lang('edit_category')?>">
										<i class="fa fa-edit text-success"></i>
									</a> 
								</td> 
							</tr> 
							<?php } ?> 
						</tbody> 
					</table> 
				</div>
			</div>
		</div>
    </div>
    <!-- End Form -->
</div>