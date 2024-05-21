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
$task_progress = (isset($task_progress)) ? $task_progress : '';
$task_id = (isset($task_id)) ? $task_id : '';
$task = $this->db->get_where('tasks',array('t_id'=>$task_id))->row_array();
?> 


<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><b><?=lang('task_report')?></b></h3>
  <?php if($task_id != NULL){ ?>
  <h5><b><span><?=lang('task_name')?>:</span>&nbsp;<?=$task['task_name']?>&nbsp;</b></h5>
  <?php } ?>
</div>

<table class="table pure-table"><thead>
<tr>
  <th><b>S.No</b></th>
  <th class="col-title"><b><?=lang('task_name')?></b></th>  
  <th><b><?=lang('start_date')?></b></th>
  <th><b><?=lang('end_date')?></b></th>
  <th class="col-title "><b><?=lang('status')?></b></th>
  <th><b><?=lang('assigned_to')?></b></th>
</tr>
</thead>

<tbody>

 <?php $i =1 ; 
 foreach ($tasks as $key => $p) { 

       
         if($p['task_progress'] == 100)
                    {
                      $cls = 'completed';
                      $btn_actions='Completed';
                    }else{
                      $cls = 'pending';
                      $btn_actions='Pending';
                    }

                   $assign_member = $this->db->select('*')
                       ->from('assign_tasks PA')
                       ->join('account_details AD','PA.assigned_user = AD.user_id')
                       ->where('PA.task_assigned',$p['t_id'])
                       ->get()->row_array(); 

                       
                          if($assign_member['avatar'] == '' )
                       {
                        $pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
                       }else{
                        $pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
                       }

                       $assignrds_name=$assign_member['fullname'];
                  // $progress = Project::get_progress($p->project_id); ?>
                  <tr >
                   
                    <td><?=$i?></td>
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>all_tasks/task_view/<?php echo $p['task_name']?>/<?=$p['t_id']?>">
                        <?=$p['task_name']?>
                      </a>
                    
                    </td>
                    <td><?php if(!empty($p['date_added'])) { echo date('M-d-Y',strtotime($p['date_added'])); } ?></td>
                    <td><?php if(!empty($p['due_date'])) { echo date('M-d-Y',strtotime($p['due_date'])); }?></td>

                    
                    <?php 
                      switch ($p['task_progress']) {
                        case '100': $label = 'success'; break;
                        case '0': $label = 'warning'; break;
                      }
                    ?>
                    <td>
                      <span class="label label-<?=$label?>"><?php echo $btn_actions ?></span>
                    </td>
                    <td><?php echo $assignrds_name;?></td>
                  </tr>
        <?php $i++; } ?>


</tbody>
</table> </div>