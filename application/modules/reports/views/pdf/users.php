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
   $role_id = (isset($role_id)) ? $role_id : '';
   $role = $this->db->get_where('roles',array('r_id'=>$role_id))->row_array();
   ?> 
<div class="rep-container">
   <div class="page-header text-center">
      <h3 class="reports-headerspacing"><b><?=lang('user_report')?></b></h3>
      <?php if($role_id != NULL){ ?>
      <h5><b><span><?=lang('role_name')?>:</span>&nbsp;<?=$role['role']?>&nbsp;</b></h5>
      <?php } ?>
   </div>
   <table class="table pure-table">
      <thead>
         <tr>
            <th><b>S.No</b></th>
            <th><b><?=lang('name')?></b></th>
            <th><b><?=lang('company')?></b></th>
            <th><b><?=lang('email')?></b></th>
            <th><b><?=lang('role')?></b></th>
            <th><b><?=lang('designation')?></b></th>
            <th class="col-title "><b><?=lang('status')?></b></th>
         </tr>
      </thead>
      <tbody>
         <?php $i =1 ; 
            foreach ($users as $key => $p) { 
            
                  
                    if($p['status'] == 1)
                               {
                                 $cls = 'active';
                                 $btn_actions='Active';
                               }else{
                                 $cls = 'inactive';
                                 $btn_actions='Inactive';
                               }
            
                               $account_details = $this->db->get_where('account_details',array('user_id'=>$p['id']))->row_array(); 
                             $company = $this->db->get_where('companies',array('co_id'=>$account_details['company']))->row_array();
            
                             $role =  $this->db->get_where('roles',array('r_id'=>$p['role_id']))->row_array(); 
                             $designation =  $this->db->get_where('designation',array('id'=>$p['designation_id']))->row_array(); 
                                 ?>
         <tr >
            <td><?=$i?></td>
            <td>
               <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
               <?=$p['username']?>
               </a>
            </td>
            <td><?php echo $company['company_name']?></td>
            <td><?php echo $p['email']?></td>
            <td><?php echo $role['role']?></td>
            <td><?php echo $designation['designation']?></td>
            <?php 
               switch ($p['status']) {
                 case '1': $label = 'success'; break;
                 case '0': $label = 'warning'; break;
               }
               ?>
            <td>
               <span class="label label-<?=$label?>"><?php echo $btn_actions ?></span>
            </td>
         </tr>
         <?php $i++; } ?>
      </tbody>
   </table>
</div>