<div class="content">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="page-title"><?=lang('departments')?> & Designations</h4>
        </div>
    </div>
    <div class="row row-eq-height">
        <!-- Project Tasks -->
        <div class="col-lg-12 row-eq-height">
            <div class="panel panel-white panel-eq-dash">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3 class="panel-title m-l-10"><?=lang('departments')?></h3>
                        </div>
                        <div class="col-xs-6">
                            <a href="<?=base_url()?>all_departments/departments/" class="btn btn-xs btn-success pull-right m-r-10" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Department</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive panel-body">
                    <table id="table-templates-1" class="table table-striped table-bordered AppendDataTables">
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
                                $j = 1;
                                foreach ($departments as $key => $d) { ?>
                            <tr>
                                <td>
                                    <?php echo $j; ?>
                                </td>
                                <td>
                                    <a class="text-muted" href="#" data-original-title="<?=$task->task_desc?>" data-toggle="tooltip" data-placement="right"><?=$d->deptname?></a>
                                </td>
                                <td>
                                    <div>Sr. Manager</div>
                                    <div>Manager</div>
                                    <div>Executive</div>

                                </td>
                                <td class="text-right">
                                    <a href="#" class="btn btn-info btn-xs">
                                        Add Designation
                                    </a>
                                    <a href="<?=base_url()?>settings/edit_dept/<?=$d->deptid?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
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
        </div>
        <!-- End Project Tasks -->
        <!-- Invoice Items -->
        <div class="col-lg-12 row-eq-height">
            <div class="panel panel-white panel-eq-dash">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3 class="panel-title m-l-10">Designations </h3>
                        </div>
                        <div class="col-xs-6">
                            <a href="<?=base_url()?>all_departments/designations" class="btn btn-xs btn-success pull-right m-r-10" data-toggle="ajaxModal"><i class="fa fa-plus"></i>Add Designation</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="padding: 20px;">
                            <select name="department_id" id="department_id" required="required" class="form-control" >
                                <option value="" >All Departments</option>
                                  <?php
                                $departments = $this -> db -> get('departments') -> result();
                                if (!empty($departments)) { 
                                    foreach ($departments as $key => $d) { ?>
                                        <option value="<?=$d->deptname?>"><?=$d->deptname?></option>
                                    <?php } }else{ ?>
                                    <option value="">No Departments</option>
                                    <?php } ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-templates-2" class="table table-striped table-bordered AppendDataTables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Designation Name</th>
                                    <th><?=lang('department_name')?> </th>
                                    <th class="col-options no-sort text-right"><?=lang('action')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                            $designation = $this -> db -> get('designation') -> result();
                            if (!empty($designation)) {
                                $i =1;
                                foreach ($designation as $key => $des) { 

                                    $dep_det = $this->db->get_where('departments',array('deptid'=>$des->department_id))->row_array();

                                    ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <a class="text-muted" href="#" data-original-title="<?=$item->item_desc?>" data-toggle="tooltip" data-placement="left" title = "">
                                            <?=$des->designation?>
                                        </a>
                                    </td>
                                    <td><?=$dep_det['deptname']?></td>
                                    <td class="text-right">
                                        <a href="<?=base_url()?>settings/edit_designation/<?=$des->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?=base_url()?>settings/delete_designation/<?=$des->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i++; } }else{ ?>
                                    <tr>No Result found</tr>
                                <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Invoice Items -->
    </div>
</div>