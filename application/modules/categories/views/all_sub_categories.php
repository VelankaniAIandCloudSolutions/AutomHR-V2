<div class="content">
    <div class="row">
        <div class="col-sm-8">
			<?php $depart = $this->db->get_where('budget_category',array('cat_id'=>$category_id))->row_array(); ?>
            <h4 class="page-title"><strong><?php echo $depart['category_name']; ?></strong> Sub-Category</h4>
        </div>
        <div class="col-sm-4 text-right m-b-30">
			<a href="<?=base_url()?>categories" class="btn back-btn"><i class="fa fa-chevron-left"></i>Back</a>
			<a href="<?=base_url()?>categories/sub_categories/<?php echo $category_id; ?>" class="btn btn-primary rounded" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Sub Category</a>
        </div>
    </div>
    <div class="row">
        <!-- Invoice Items -->
        <div class="col-lg-12">
				<div class="table-responsive">
					<table id="table-templates-2" class="table table-striped custom-table m-b-0 AppendDataTables">
						<thead>
							<tr>
								<th>#</th>
								<th>Category Name</th>
								<th>Sub-Category Name</th>
								<th class="col-options no-sort text-right"><?=lang('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
						if (!empty($all_subcategories)) {
							$i = 1;
							foreach ($all_subcategories as $key => $des) { 

								$dep_det = $this->db->get_where('budget_category',array('cat_id'=>$des->cat_id))->row_array();

								?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?=$dep_det['category_name']?></td>
								<td>
										<?=$des->sub_category?>
								</td>
								<td class="text-right">
									<a href="<?=base_url()?>categories/edit_sub_category/<?=$des->sub_id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
										<i class="fa fa-edit"></i>
									</a>
									<a href="<?=base_url()?>categories/delete_sub_category/<?=$des->sub_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
										<i class="fa fa-trash-o"></i>
									</a>
								</td>
							</tr>
							<?php $i++;  } }else{ ?>
								<tr>No Result found</tr>
							<?php  } ?>
						</tbody>
					</table>
				</div>
        </div>
        <!-- End Invoice Items -->
    </div>
</div>