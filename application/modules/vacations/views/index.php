<div class="content">
	<div class="row">
		<div class="col-sm-8 col-5">
			<h4 class="page-title"><?=lang('vocations')?></h4>
		</div>
        <div class="col-sm-4 col-7 text-right m-b-20">
		<?php
		if(App::is_permit('menu_vocation','create'))
		{
           ?>
            <a class="btn add-btn" href="<?=base_url()?>vacations/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Vacation</a>
        <?php
		}
		?>
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
                                <th>Vacation</th>                                 
                                <th>Status</th>
								<?php
								if(App::is_permit('menu_vocation','write')==true|| App::is_permit('menu_vocation','delete')==true)
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
                                if (!empty($vocations)) 
                                {
                                    $j =1;
                                    foreach ($vocations as $key => $vocation) 
                                    { 
                                        $status = "Active";
                                        if($vocation->status==0)
                                        {
                                            $status = "Inactive";
                                        }
                                        ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$vocation->vocation?> </td> 

                                            <td>
                                            <div class="dropdown action-label">
													<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
														<?php if($vocation->status==1){
															echo '<i class="fa fa-dot-circle-o text-success"></i> '.lang('active');
														}else{
															echo '<i class="fa fa-dot-circle-o text-danger"></i> '.lang('inactive');
														} ?>
													</a>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="<?php echo site_url('vacations/indicator_status/').$vocation->id.'/1';?>"><i class="fa fa-dot-circle-o text-success"></i> <?php echo lang('active');?></a>
														<a class="dropdown-item" href="<?php echo site_url('vacations/indicator_status/').$vocation->id.'/0';?>"><i class="fa fa-dot-circle-o text-danger"></i> <?php echo lang('inactive');?></a>
													</div>
												</div>
                                             </td>   
                                             
											<?php
											if(App::is_permit('menu_vocation','write')==true|| App::is_permit('menu_vocation','delete')==true)
											{
											?>											 
                                            <td class="text-right"> 
                                                 <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon" data-toggle="dropdown"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
													 <?php if(App::is_permit('menu_vocation','write')){?><a href="<?=base_url()?>vacations/edit/<?=$vocation->id?>" class="dropdown-item" data-toggle="ajaxModal">
                                                    <i class="fa fa-edit m-r-5"></i>Edit
													 </a><?php }?>
												<?php if(App::is_permit('menu_vocation','delete')){?>
                                                <a href="<?=base_url()?>vacations/delete/<?=$vocation->id?>" class="dropdown-item" data-toggle="ajaxModal" title="" data-original-title="Delete">
                                                    <i class="fa fa-trash-o m-r-5"></i>Delete
                                                </a>
												<?php } ?>
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