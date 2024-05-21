<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />
<style type="text/css">
  .pure-table td, .pure-table th {
    border-bottom: 1px solid #cbcbcb;
    border-width: 0 0 0 1px;
    margin: 0;
    overflow: visible;
    padding: .5em 1em;
}
.pure-table .table td {
    vertical-align: middle !important;
}
</style>
<?php 
ini_set('memory_limit', '-1');
$cur = App::currencies(config_item('default_currency')); 
$project = ($project_id > 0) ? Project::by_id($project_id) : array();
$project_id = (isset($project_id)) ? $project_id : '';
$status = (isset($status)) ? $status : '';
?>


<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><b><?=lang('project_report')?></b></h3>
  <?php if($project_id != NULL){ ?>
  <h5><b><span><?=lang('project_name')?>:</span>&nbsp;<?=$project->project_title?>&nbsp;</b></h5>
  <?php } ?>
</div>

<table class="table pure-table"><thead>
<tr>
  <th><b>S.No</b></th>
  <th class="col-title"><b><?=lang('project_title')?></b></th>    
  <th class=""><b><?=lang('client_name')?></b></th>
  <th><b><?=lang('start_date')?></b></th>
  <th><b><?=lang('end_date')?></b></th>    
  <th class="col-title "><b><?=lang('status')?></b></th>
  <th><b><?=lang('team_members')?></b></th>
</tr>
</thead>

<tbody>

 <?php $i =1 ; 
 foreach ($projects as $key => $p) { 
        // $progress = Project::get_progress($p->project_id); ?>
        <tr class="<?php if (Project::timer_status('project',$p->project_id) == 'On') { echo "text-danger"; } ?>">
          <td style="display:none;"><?=$i?></td>
          <td>
            <?php  $no_of_tasks = App::counter('tasks',array('project' => $p->project_id)); ?>
            <a class="text-info" data-toggle="tooltip" data-original-title="<?=$no_of_tasks?> <?=lang('tasks')?> | <?=$progress?>% <?=lang('done')?>" href="<?=base_url()?>projects/view/<?=$p->project_id?>">
              <?=$p->project_title?>
            </a>
          
          </td>
          <?php if (User::is_admin()) { ?>
          <td class="">
            <?=($p->client > 0) ? Client::view_by_id($p->client)->company_name : 'N/A'; ?>
          </td>
          <?php } ?>
          <td><?php echo date('M-d-Y',strtotime($p->start_date));?></td>
          <td><?php echo date('M-d-Y',strtotime($p->due_date));?></td>

       
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
         
         
          <td >
            
              <?php $i=1;
              foreach (Project::project_team($p->project_id) as $user) { ?>
              
                <?php echo $i.'. '.User::displayName($user->assigned_user); ?><br>
               
              <?php $i++;} ?>
            
          </td>
         
        </tr>
        <?php $i++; } ?>


</tbody>
</table> </div>