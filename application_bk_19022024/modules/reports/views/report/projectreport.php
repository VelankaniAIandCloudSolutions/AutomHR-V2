<?php 
$cur = App::currencies(config_item('default_currency')); 
$project = ($project_id > 0) ? Project::by_id($project_id) : array();
$project_id = (isset($project_id) && !empty($project_id)) ? $project_id : '0';
$status = (isset($status) && !empty($status)) ? $status : '0';
if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){
  $post_branch_id = $branch_id;
} else {
  $post_branch_id = $this->session->userdata('branch_id');
}
?>

<div class="content">
          <section class="panel panel-white">
            
            <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($projects)> 0 ){ ?>
              <a href="<?=base_url()?>reports/projectpdf/<?=$project_id;?>/<?=$status?>/<?= $post_branch_id ?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>
            <?php } ?>
            <button class="btn  btn-primary pull-right" onclick="export_report('project_report','project');" > <span ><i class="fa fa-file-excel-o m-r-5" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
            </div>

            <div class="panel-body">

            <!-- <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
            </div> -->

<div class="fill body reports-top rep-new-band">
<div class="criteria-container fill-container hidden-print">
<div class="criteria-band">
    <address class="row">
    <?php echo form_open(base_url().'reports/view/projectreport'); ?>

    <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
<div class="col-md-4">
          <label><?=lang('branches')?> </label>
          <select <?=$dis?>  class="form-control" name="branch_id" id="branchreportemployee"><!---->
              <option value="0"><?=lang('all_branches')?></option>
              <?php 
                // $all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
                // echo "<pre>"; print_r($all_branches); exit;
                if(!empty($all_branches)){
                  foreach($all_branches as $branch){ ?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch_id == $branch['branch_id']){ echo "selected"; } ?> ><?php echo $branch['branch_name']; ?></option>
              <?php 
                  }
                }
              ?>
          </select>
        </div>
        <?php }
            else{
            ?>
              <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
          <?php }?>
        <div class="col-md-4">
          <label><?=lang('project_name')?> </label>
          <select class="select2-option form-control" style="width:280px" name="project_title" id="reportprojectlist" >
                                            <optgroup label="<?=lang('projects')?>">
                                              <option value="">All</option>
                                                <?php foreach (Project::all() as $c): ?>
                                                    <option value="<?=$c->project_id?>" <?=($project_id == $c->project_id) ? 'selected="selected"' : '';?>>
                                                    <?=ucfirst($c->project_title)?></option>
                                                <?php endforeach;  ?>
                                            </optgroup>
                                        </select>
        </div>
      <div class="col-md-2">
        <label><?=lang('status')?></label>
        <select class="form-control" name="status">
        <option value="" <?=($status == ' ') ? 'selected="selected"': ''; ?>><?=lang('all')?></option>
        <option value="Active" <?=($status == 'Active') ? 'selected="selected"': ''; ?>><?=lang('active')?></option>
        <option value="On Hold" <?=($status == 'On Hold') ? 'selected="selected"': ''; ?>><?=lang('on_hold')?></option>
        <option value="Done" <?=($status == 'Done') ? 'selected="selected"': ''; ?>><?=lang('done')?></option>
</select>
      </div>
          


      <div class="col-md-2">  
  <button class="btn btn-success" type="submit">
    <?=lang('run_report')?>
  </button>
</div>



    </address>
  

  </div>
</div>


<?php  form_close(); ?>

<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><?=lang('project_report')?></h3>
  <?php if($project->project_id != NULL){ ?>
  <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$project->project_title?>&nbsp;</h5>
  <?php } ?>
</div>

  <div class="fill-container">


<div class="col-md-12">
        
    <table id="project_report" class="table table-striped custom-table m-b-0" id="project_report">
      <thead>
        <tr>
          <th style="width:5px; display:none;"></th>
          <th class="col-title"><b><?=lang('project_title')?></b></th>          
          <th class=""><b><?=lang('client_name')?></b></th>
          <th><b><?=lang('start_date')?></b></th>
          <th><b><?=lang('end_date')?></b></th>
          <th class="col-title "><b><?=lang('status')?></b></th>
          <th><b><?=lang('team_members')?></b></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $key => $p) { 
        // $progress = Project::get_progress($p->project_id); ?>
        <tr class="<?php if (Project::timer_status('project',$p->project_id) == 'On') { echo "text-danger"; } ?>">
          <td style="display:none;"><?=$p->project_id?></td>
          <td>
            <?php  $no_of_tasks = App::counter('tasks',array('project' => $p->project_id)); ?>
            <a class="text-info" data-toggle="tooltip" data-original-title="<?=$no_of_tasks?> <?=lang('tasks')?> | <?=$progress?>% <?=lang('done')?>" href="<?=base_url()?>projects/view/<?=$p->project_id?>">
              <?=$p->project_title?>
            </a>
           <!--  <?php if (Project::timer_status('project',$p->project_id) == 'On') { ?>
            <i class="fa fa-spin fa-clock-o text-danger"></i>
            <?php } ?>
            <?php 
            if (time() > strtotime($p->due_date) AND $progress < 100){
            $color = (valid_date($p->due_date)) ? 'danger': 'default';
            echo '<span class="label label-'.$color.' pull-right">';
            echo (valid_date($p->due_date)) ? lang('overdue') : lang('ongoing'); 
            echo '</span>'; 
            } ?>
            <div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 100%; margin-right: 5px">
              <div class="progress-bar progress-bar-<?php echo ($progress >= 100 ) ? 'success' : 'danger'; ?>" role="progressbar" aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress?>%;" data-toggle="tooltip" data-original-title="<?=$progress?>%"></div>
            </div> -->
          
          </td>
          <?php if (User::is_admin()) { ?>
          <td class="">
            <?=($p->client > 0) ? Client::view_by_id($p->client)->company_name : 'N/A'; ?>
          </td>
          <?php } ?>
          <td><?php echo date('M-d-Y',strtotime($p->start_date));?></td>
          <td><?php echo date('M-d-Y',strtotime($p->due_date));?></td>

          <?php if (User::login_role_name() != 'client') { ?>
          <?php 
            switch ($p->status) {
              case 'Active': $label = 'success'; break;
              case 'On Hold': $label = 'warning'; break;
              case 'Done': $label = 'default'; break;
            }
          ?>
          <td>
            <span class="label label-<?=$label?>"><?=lang(str_replace(" ","_",strtolower($p->status)))?></span>
          </td>
          <?php } ?>

          <!-- <td class="text-muted">
            <ul class="team-members">
               
              <li>
                <a>
                  <img src="<?php echo User::avatar_url($p->assign_lead); ?>" class="img-circle" data-toggle="tooltip" data-title="<?=User::displayName($p->assign_lead); ?>" data-placement="top">
                </a>
              </li>
               
            </ul>
          </td>
 -->
          <td >
            
              <?php $i=1;
              foreach (Project::project_team($p->project_id) as $user) { ?>
              
                <?php echo $i.'. '.User::displayName($user->assigned_user); ?><br>
                <!-- <a>
                  <img src="<?php echo User::avatar_url($user->assigned_user); ?>" class="img-circle" data-toggle="tooltip" data-title="<?=User::displayName($user->assigned_user); ?>" data-placement="top">
                </a> -->
              
              <?php $i++;} ?>
            
          </td>
          <!-- <?php $hours = Project::total_hours($p->project_id);
            if($p->estimate_hours > 0){
              $used_budget = round(($hours / $p->estimate_hours) * 100,2);
            } else{ $used_budget = NULL; }
          ?>
          <td>
            <strong class="<?=($used_budget > 100) ? 'text-danger' : 'text-success'; ?>"><?=($used_budget != NULL) ? $used_budget.' %': 'N/A'?></strong>
          </td>
          <?php if (!User::is_admin()) {  
            $check_task_hours = $this->db->get_where('timesheet',array('project_id'=>$p->project_id))->result_array();    
                            $hrs = 0;
                            foreach($check_task_hours as $h)
                            {
                              $hrs += $h['hours'];
                            }

            ?>
          <td class="text-muted"><?=$hrs?> <?=lang('hours')?></td>
          <?php } ?>
          <?php if(User::login_role_name() != 'staff' || User::perm_allowed(User::get_id(),'view_project_cost')){ ?>
          <?php $cur = ($p->client > 0) ? Client::client_currency($p->client)->code : $p->currency; ?>
          <td class="col-currency">
            <strong><?=Applib::format_currency($cur, Project::sub_total($p->project_id))?></strong>
            <small class="text-muted" data-toggle="tooltip" data-title="<?=lang('expenses')?>"> (<?=Applib::format_currency($cur, Project::total_expense($p->project_id))?>) </small>
          </td>
          <?php } ?> -->

          <!-- Gannt Chart -->
          <!-- <td><a href="<?php echo base_url(); ?>projects/project_chart/<?php echo $p->project_id; ?>"><i class="fa fa-bar-chart" aria-hidden="true"></i></a></td> -->


         <!--  <td class="text-right">
            <div class="dropdown">
              <a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="fa fa-ellipsis-v"></i></a>
              <ul class="dropdown-menu pull-right">
                <li>
                  <a href="<?=base_url()?>projects/view/<?=$p->project_id?>"><?=lang('preview_project')?></a>
                </li>
                <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){ ?>   
                <li>
                  <a href="<?=base_url()?>projects/edit/<?=$p->project_id?>"><?=lang('edit_project')?></a>
                </li>
                <?php if ($archive) : ?>
                <li><a href="<?=base_url()?>projects/archive/<?=$p->project_id?>/0"><?=lang('move_to_active')?></a></li>  
                <?php else: ?>
                <li>
                  <a href="<?=base_url()?>projects/archive/<?=$p->project_id?>/1"><?=lang('archive_project')?></a>
                </li>        
                <?php endif; ?>
                <?php } ?>  
                <?php if (User::is_admin() || User::perm_allowed(User::get_id(),'delete_projects')){ ?> 
                <li>
                  <a href="<?=base_url()?>projects/delete/<?=$p->project_id?>" data-toggle="ajaxModal"><?=lang('delete_project')?></a>
                </li>
                <?php } ?>
              </ul>
            </div>
          </td> -->
        </tr>
        <?php } ?>

        
      </tbody>
    </table>
  </div>

 </div>
    

</div>


</div>




            </div>
            </section>
            </div>