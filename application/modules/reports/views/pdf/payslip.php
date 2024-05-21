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
      <h3 class="reports-headerspacing"><b><?=lang('payslip_report')?></b></h3>
      <?php if($role_id != NULL){ ?>
      <h5><b><span><?=lang('role_name')?>:</span>&nbsp;<?=$role['role']?>&nbsp;</b></h5>
      <?php } ?>
   </div>
   <table class="table pure-table">
                <thead>
                  <tr>
                    <th><b>S.No</b></th>
                    <th><b><?=lang('employee_name')?></b></th>  
                    <th><b><?=lang('paid_amount')?></b></th>
                    <th><b><?=lang('payment_month')?></b></th>
                    <th><b><?=lang('payment_year')?></b></th> 
                                      
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $i =1 ; 
                  foreach ($payslip as $key => $p) { 

                     $name = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array(); 
                     $users = $this->db->get_where('users',array('id'=>$p['user_id']))->row_array();
                     $payment_amount = $this->db->get_where('bank_statutory',array('user_id'=>$p['user_id']))->row_array();

                     if($p['p_month'] == 1)
                    {
                      $month = 'January';
                      
                    }elseif($p['p_month'] == 2){
                     $month = 'Febrary';
                      
                    }
                    elseif($p['p_month'] == 3){
                     $month = 'March';
                      
                    }
                    elseif($p['p_month'] == 4){
                     $month = 'April';
                      
                    }
                    elseif($p['p_month'] == 5){
                     $month = 'May';
                      
                    }
                    elseif($p['p_month'] == 6){
                     $month = 'June';
                      
                    }
                    elseif($p['p_month'] == 7){
                     $month = 'July';
                      
                    }
                    elseif($p['p_month'] == 8){
                     $month = 'August';
                      
                    }
                    elseif($p['p_month'] == 9){
                     $month = 'September';
                      
                    }
                    elseif($p['p_month'] == 10){
                     $month = 'October';
                      
                    }
                    elseif($p['p_month'] == 11){
                     $month = 'November';
                      
                    }
                    elseif($p['p_month'] == 12){
                     $month = 'December';
                      
                    }

                                                            
                  ?> 
                  <tr >
                     <td><?=$i?></td>
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['user_id']?>">
                        <?=$name['fullname']?>
                      </a>
                    
                    </td>
                    <td><?php echo $payment_amount['salary']?></td>
                    <td><?php echo $month?></td>
                    <td><?php echo $p['p_year']?></td>
                    

                  </tr>
                  <?php $i++; } ?>
                </tbody>
              </table>
</div>