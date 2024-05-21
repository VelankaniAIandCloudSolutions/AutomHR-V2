<div class="content">
    <div class="row">
        <div class="col-sm-8">
			<?php $depart = $this->db->get_where('departments',array('deptid'=>$department_id))->row_array(); ?>
            <h4 class="page-title"><strong><?php echo $depart['deptname']; ?></strong> Designations</h4>
        </div>
        <div class="col-sm-4 text-right m-b-30">
			<a href="<?=base_url()?>all_departments" class="btn back-btn"><i class="fa fa-chevron-left"></i>Back</a>
			<a href="<?=base_url()?>all_departments/designations/<?php echo $department_id; ?>" class="btn btn-primary rounded" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Designation</a>
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
								<th><?=lang('department_name')?> </th>
								<th>Designation Name</th>
								<th>Grade</th>
								<th class="col-options no-sort text-right"><?=lang('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
						if (!empty($all_designation)) {
							$i = 1;
							foreach ($all_designation as $key => $des) { 

								$dep_det = $this->db->get_where('departments',array('deptid'=>$des->department_id))->row_array();

								?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?=$dep_det['deptname']?></td>
								<td>
										<?=$des->designation?>
								</td>
								<td>
									<?php $grade_details = $this->db->get_where('grades',array('grade_id'=>$des->grade))->row_array(); ?>
                                    <?php echo $grade_details['grade_name']; ?>
								</td>
								<td class="text-right">
									<a href="<?=base_url()?>all_departments/edit_designation/<?=$des->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
										<i class="fa fa-edit"></i>
									</a>
									<a href="<?=base_url()?>all_departments/delete_designation/<?=$des->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
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