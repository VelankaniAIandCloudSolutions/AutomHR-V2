<?php if ($this->session->flashdata('lead_reporter_add') == 'success'){ ?>
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> Lead Reporter Added Successfully.
    </div>
<?php } ?>
<?php if ($this->session->flashdata('lead_reporter_edit') == 'success'){ ?>
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> Lead Reporter Updated Successfully.
    </div>
<?php } ?>
<?php if ($this->session->flashdata('lead_reporter_delete') == 'success'){ ?>
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Delete!</strong> Lead Reporter Deleted.
      </div>
<?php } ?>
<div class="row">
        <div class="col-xs-5">
            <h4 class="page-title">Lead Reporters</h4>
        </div>
        <div class="col-xs-7 text-right m-b-30">
            <a href="<?=base_url()?>settings/lead_reporter_add" data-toggle="ajaxModal" class="btn btn-primary btn-rounded pull-right"><i class="fa fa-plus"></i> Reporter</a>
        </div>
        <div class="col-xs-7 text-right m-b-30">
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="table-rates" class="table table-striped custom-table m-b-0 AppendDataTables">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter's Name</th>
                    <th>Reporter's EMail</th>
                    <th class="col-options no-sort text-right"><?=lang('options')?></th>
                </tr>
            </thead>
            <tbody>
                <?php $all_leadreporter = $this->db->where('branch_id', $this->session->userdata('branch_id'))->get('dgt_lead_reporter')->result_array(); 
                $i = 1;
                if(count($all_leadreporter) )
                foreach($all_leadreporter as $leadreporter){ ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $leadreporter['reporter_name']; ?></td>
                    <td><?php echo $leadreporter['reporter_email']; ?></td>
                    <td class="text-right">
                        <!--<a class="btn btn-success btn-xs" href="<?=base_url()?>settings/lead_reporter_edit/<?php echo $leadreporter['reporter_id']; ?>" data-toggle="ajaxModal" title="<?=lang('edit_reporter')?>"><i class="fa fa-edit"></i></a>-->
                        <a class="btn btn-danger btn-xs" href="<?=base_url()?>settings/lead_reporter_delete/<?php echo $leadreporter['reporter_id']; ?>" data-toggle="ajaxModal" title="<?=lang('delete_reporter')?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php $i++; } ?>
            </tbody>
        </table>
    </div>
