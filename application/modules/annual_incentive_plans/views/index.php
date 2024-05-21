<div class="content">
    <div class="page-header">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('annual_incentive_plans')?></h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('annual_incentive_plans')?></li>
            </ul>
		</div>
        <div class="col-sm-4 text-right m-b-10">
			<?php
			if(App::is_permit('menu_annual_incentive_plans','create'))
			{
			?>
			<a class="btn add-btn m-b-10" href="<?=base_url()?>annual_incentive_plans/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Annual Incentive Plans</a>
			<?php
			}
			?>
		</div>
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
                                <th>Plans</th>                                 
                                <th>Status</th>
								<?php
								if(App::is_permit('menu_annual_incentive_plans','write')==true|| App::is_permit('menu_annual_incentive_plans','delete')==true)
								{
								?>
								<th class="col-options no-sort text-right"><?=lang('action')?></th>
								<?php
								}
								?>
							</tr>
                        </thead>
                        <tbody>
                            <?php                                      
                                if (!empty($plans)) 
                                {
                                    $j =1;
                                    foreach ($plans as $key => $plan) 
                                    { 
                                        $status = "Active";
                                        if($plan->status==0)
                                        {
                                            $status = "Inactive";
                                        }
                                        ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$plan->plan?> </td>
                                          
											<td>
											<div class="action-label">
											<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
											<i class="fa fa-dot-circle-o text-success"></i><?php echo $status; ?>
											</a>
											</div>
											</td> 
											<?php
											if(App::is_permit('menu_annual_incentive_plans','write')==true|| App::is_permit('menu_annual_incentive_plans','delete')==true)
											{
											?>
                                            <td class="text-right"> 
                                                 <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon" data-toggle="dropdown"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
												 <?php if(App::is_permit('menu_annual_incentive_plans','write'))
												 {?>
                                                <a href="<?=base_url()?>annual_incentive_plans/edit/<?=$plan->id?>" class="dropdown-item" data-toggle="ajaxModal">
                                                    <i class="fa fa-edit m-r-5"></i>Edit
                                                </a>
												<?}
												if(App::is_permit('menu_annual_incentive_plans','delete')){
												?>
                                                <a href="<?=base_url()?>annual_incentive_plans/delete/<?=$plan->id?>" class="dropdown-item" data-toggle="ajaxModal" title="" data-original-title="Delete">
                                                    <i class="fa fa-trash-o m-r-5"></i>Delete
                                                </a> 
												<?php
												}
												?>
                                                </div>
                                                </div>                        

                                            </td>
											<?php
											}
											?>
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