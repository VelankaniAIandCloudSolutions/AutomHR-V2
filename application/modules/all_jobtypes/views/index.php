<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('job_types')?></h4>
		</div>
        <div class="col-sm-4 text-right m-b-20">
            <a class="btn add-btn" href="<?=base_url()?>all_jobtypes/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Job Type</a>
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
                                <th><?=lang('job_type_name')?></th>                                 
                                <th>Status</th>
                                <th class="col-options no-sort text-right"><?=lang('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                                      
                                if (!empty($job_types)) 
                                {
                                    $j =1;
                                    foreach ($job_types as $key => $job_type) 
                                    { 
                                        $status = "Active";
                                        if($job_type->status==0)
                                        {
                                            $status = "Inactive";
                                        }
                                        ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$job_type->job_type?> </td> 
                                            <td> <?php echo $status; ?> </td>               
                                            <td class="text-right"> 
                                                <a href="<?=base_url()?>all_jobtypes/edit/<?=$job_type->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?=base_url()?>all_jobtypes/delete/<?=$job_type->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="" data-original-title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>                         
                                            </td>
                                        </tr>
                                        <?php $j++; 
                                    } 
                                } 
                                else
                                { ?>
                                    <tr>No Results</tr>
                                <?php 
                                } ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>
</div>