<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('departments')?></h4>
		</div>
        <div class="col-sm-4 text-right m-b-20">
            <a class="btn add-btn" href="<?=base_url()?>all_departments/departments/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Department</a>
        </div>
	</div>
    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('department_name')?></th>
                                <th>Designations</th>
                                <th class="col-options no-sort text-right"><?=lang('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $departments = $this -> db -> get('departments') -> result();
                            if (!empty($departments)) {
                                $j =1;
                                foreach ($departments as $key => $d) { ?>
                            <tr>
								<td>
                                    <?php echo $j; ?>
                                </td>
                                <td>
                                    <?=$d->deptname?>
                                </td>
                                <td>
                                	<?php $all_des = $this->db->get_where('designation',array('department_id'=>$d->deptid))->result_array(); 
                                		if(count($all_des) != 0)
                                		{
                                			foreach($all_des as $des){
                                                $grade_details = $this->db->get_where('grades',array('grade_id'=>$des['grade']))->row_array();
                                	?>
										<div><?php echo $des['designation']; ?></div>
									<?php } }else{ ?>
										<div>-</div>
									<?php } ?>

                                </td>
                                <td class="text-right">
									<a href="<?php echo base_url(); ?>all_departments/view_designation/<?=$d->deptid?>" class="btn btn-info btn-xs">
										Designation
									</a>
                                    <a href="<?=base_url()?>all_departments/edit_dept/<?=$d->deptid?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                   <!--  <a href="<?=base_url()?>items/delete_task/<?=$task->template_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
                                        <i class="fa fa-trash-o"></i>
                                    </a> -->
                                </td>
                            </tr>
                            <?php $j++; } } else{ ?>
                                <tr>No Results</tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>
</div>