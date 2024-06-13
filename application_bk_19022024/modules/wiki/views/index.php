<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('wiki')?></h4>
		</div>
        <?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){ ?>
            <div class="col-sm-4 text-right m-b-20">
                <a class="btn add-btn" href="<?=base_url()?>wiki/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Wiki</a>
            </div>
        <?php } ?>
	</div>
    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="wiki" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('title')?></th>      
                                <th>  <?=lang('branch')?></th>                         
                                <th><?=lang('description')?></th>
                                <?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){ ?>
                                    <th class="col-options no-sort text-right"><?=lang('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                                      
                                if (!empty($wikis)) 
                                {
                                    $role_name = User::login_role_name();
                                    $j =1;
                                    foreach ($wikis as $key => $wiki) 
                                    { //if($role_name == 'admin' || $wiki->branch_id == $this->session->userdata('branch_id') ){ ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$wiki->title?> </td> 
                                            <td><?=App::get_entity_name($wiki->branch_id);?>
                                            <td> <?=wordwrap($wiki->description,100,"<br>\n")?> </td>   
                                            <?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){ ?>            
                                                <td class="text-right"> 
                                                    <a href="<?=base_url()?>wiki/edit/<?=$wiki->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="<?=base_url()?>wiki/delete/<?=$wiki->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="" data-original-title="Delete">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>                         
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php $j++; 
                                  //  } 
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