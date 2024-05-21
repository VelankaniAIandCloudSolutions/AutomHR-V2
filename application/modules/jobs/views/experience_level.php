<div class="content">
    <div class="page-header">
	<div class="row">
		<div class="col-md-6">
			<h4 class="page-title"><?=lang('experience_level')?></h4>
            <ul class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('experience_level')?></li>
            </ul>
		</div>
        <div class="col-md-6 text-right m-b-20">
			<?php if(App::is_permit('menu_experience_level','create')){?><a class="btn add-btn" href="<?=base_url()?>jobs/add_experience" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=
			lang('add_experience_level')?></a>
			<?php } ?>

        </div>
	</div>
</div>
    <?php //$this->load->view('sub_menus');?>
    
	
    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('experience')?></th>                                 
                                <th><?php echo lang('status');?></th>
								<?php if(App::is_permit('menu_experience_level','write')==true || App::is_permit('menu_experience_level','delete')==true)
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
                               
                                    $j =1;
                                    foreach ($experience_levels as $key => $exp_level) 
                                    { 
                                        $status = lang('active');
                                        if($exp_level->status==0)
                                        {
                                            $status = lang('inactive');
                                        }
                                        ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$exp_level->experience_level?> </td> 

											<td>
												<div class="action-label">
												<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
												<i class="fa fa-dot-circle-o text-success"></i> <?php echo $status; ?>
												</a>
												</div>  
											</td>              
											<?php if(App::is_permit('menu_experience_level','write')==true || App::is_permit('menu_experience_level','delete')==true)
											{
											?>
                                            <td class="text-right"> 
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
													<?php if(App::is_permit('menu_experience_level','write')){?> <a href="<?=base_url()?>jobs/add_experience/<?=$exp_level->id?>" class="dropdown-item" data-toggle="ajaxModal">
													<i class="fa fa-edit m-r-5"></i>Edit
													</a><?php }?>
													<?php if(App::is_permit('menu_experience_level','delete')){?> <a href="<?=base_url()?>jobs/delete_experience/<?=$exp_level->id?>" class="dropdown-item" data-toggle="ajaxModal" title="" data-original-title="Delete">
													<i class="fa fa-trash-o m-r-5"></i> Delete
													</a><?php } ?>
													</div>
												</div>                         

                                            </td>
											
											<?php
											}
											?>
                                        </tr>
                                        <?php $j++; 
                                    } 
                            
                                ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>
    
</div>