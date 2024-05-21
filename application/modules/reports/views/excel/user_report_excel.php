<style>
#excel_table {
  
  border-collapse: collapse;
  width: 100%;
}

#excel_table td, #excel_table th {
  border: 1px solid #3a3a3a;
  padding: 8px;
}

/*#excel_table tr:nth-child(even){background-color: #f2f2f2;}

#excel_table tr:hover {background-color: #ddd;}*/

#excel_table th {
  padding-top: 12px;
  padding-bottom: 12px;
 
}
</style>
<?php 
//  $system_settings = $this->db->get_where('subdomin_system_settings')->row_array();
//   $systems = unserialize(base64_decode($system_settings['system_settings']));
//   $time_zone = $systems['timezone']?$systems['timezone']:config_item('timezone');
// date_default_timezone_set($time_zone);
?>
<table style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%; padding: 8px;">

  <tr style="background-color:#c6e0b3">
    <td><?php echo lang('role_name');?></td>
    <td><?php echo (isset($role_id) && !empty($role_id))?ucfirst($role_name['role']):"All"?></td>
    <td colspan="5"></td>
    
    
  </tr>
  
  
</table>
<table id="table-absences_report" class="" style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%;border: 1px solid #3a3a3a; padding: 8px;">
           <!--  <thead>
              
            </thead> -->
<tbody>
  <tr class="" style="vertical-align: middle !important;background-color:#24b23c">  
            <th style="width:5px;"><b><?=lang('sno')?></b></th>
                    <th><b><?=lang('name')?></b></th>  
                    <th><b><?=lang('company')?></b></th>
                    <th><b><?=lang('email')?></b></th>
                    <th><b><?=lang('role')?></b></th>
                    <th><b><?=lang('designation')?></b></th>
                    <th class="col-title "><b><?=lang('status')?></b></th>     
  </tr>   
 


                    
 <?php $i=1; foreach ($users as $key => $p) { 

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
  <tr style="vertical-align: middle !important;">
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $i;?></td>    
    <td style="vertical-align: middle !important;text-align: center;"> <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['username']?>
                      </a></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo ($company['company_name'])?$company['company_name']:'-';?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['email']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $role['role']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo ($designation['designation'])?$designation['designation']:'-';?></td>
     <?php 
          switch ($p['status']) {
            case '1': $label = 'success'; break;
            case '0': $label = 'warning'; break;
          }
        ?>
    <td style="vertical-align: middle !important;text-align: center;">
      <span class="label label-<?=$label?>"><?php echo $btn_actions ?></span>
    </td>
   
  </tr>
  <?php $i++; } ?>
  </tbody>
</table>