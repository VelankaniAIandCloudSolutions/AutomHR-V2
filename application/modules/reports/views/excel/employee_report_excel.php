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
    <td><?php echo lang('s_no')?></td>
    <td><?php echo lang('employee');?></td>
    <td><?php echo (isset($user_id) && !empty($user_id))?ucfirst(user::displayName($user_id)):"All"?></td>
    <td><?php echo lang('department');?></td>
    <td style="text-align: left;"><?php echo (isset($department_id) && !empty($department_id))?ucfirst(user::GetDepartmentNameById($department_id)):"All"?></td>
    <td colspan="14"></td>
    
  </tr>
  <tr style="background-color:#c6e0b3">
    
    <td><?php echo lang('designation');?></td>
    <td style="text-align: left;"><?php echo (isset($designation_id) && !empty($designation_id))?ucfirst(user::GetDesignationNameById($designation_id)):"All"?></td>
    <td><?php echo lang('export_date');?></td>
    <td style="text-align: left;"><?php echo date('Y-m-d H:i');?></td>
    <td colspan="14"></td>
  </tr>
  
</table>
<table id="table-absences_report" class="" style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%;border: 1px solid #3a3a3a; padding: 8px;">
           <!--  <thead>
              
            </thead> -->
<tbody>
  <tr class="" style="vertical-align: middle !important;background-color:#24b23c">  
            <th><b><?php echo lang('s_no')?></b></th>  
            <th><b><?=lang('name')?></b></th>  
            <th><b><?=lang('employee_type')?></b></th>  
            <th><b><?=lang('email')?></b></th>
            <th><b><?=lang('department')?></b></th>
            <th><b><?=lang('designation')?></b></th>
            <th><b><?=lang('joining_date')?></b></th>  
            <th><b><?=lang('dob')?></b></th>  
            <th><b><?=lang('marital_status')?></b></th>  
            <th><b><?=lang('gender')?></b></th>   
            <th><b><?=lang('terminated_date')?></b></th>  
            <th><b><?=lang('relieving_date')?></b></th>  
            <th><b><?=lang('salary')?></b></th>  
            <th><b><?=lang('address')?></b></th>  
            <th><b><?=lang('contact_number')?></b></th>  
            <th><b><?=lang('emergency_contact_details')?></b></th>  
            <th><b><?=lang('total_years_months_experience')?></b></th>                      
            <th class="col-title "><b><?=lang('status')?></b></th>       
  </tr>   
 


                    
 <?php $i =1 ; 
  foreach ($employees as $key => $p) { 

     // $company_name = $this->db->get_where('companies',array('co_id'=>$p['company']))->row_array(); 
     // $users = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array();
     // $designation = $this->db->get_where('designation',array('id'=>$p['designation_id']))->row_array();
     // $department = $this->db->get_where('departments',array('deptid'=>$p['department_id']))->row_array();

   if($p['status'] == 1)
    {
      $cls = 'active';
      $btn_actions=lang('active');
    }else{
      $cls = 'inactive';
      $btn_actions=lang('inactive');
    }
    if(!empty($p['personal_info'])){
      $personal_info = json_decode($p['personal_info']); 
    }else{
      $personal_info = array();
    }
    if(!empty($p['personal_details'])){
      $personal_details = json_decode($p['personal_details']); 
    }else{
      $personal_details = array();
    }
    if(!empty($p['emergency_info'])){
      $emergency_info = json_decode($p['emergency_info']); 
    }else{
      $emergency_info = array();
    }

    if(($p['doj'] != 0000-00-00)){
      if(!empty($p['terminationdate']) || !empty($p['resignationdate'])){
        if(!empty($p['terminationdate'])){
          $datetime1 = new DateTime($p['doj']);
          $datetime2 = new DateTime($p['terminationdate']);
          $interval = $datetime1->diff($datetime2);
          $experience = $interval->format('%y years %m months and %d days');
        }else{
          $datetime1 = new DateTime($p['doj']);
          $datetime2 = new DateTime($p['resignationdate']);
          $interval = $datetime1->diff($datetime2);
          $experience = $interval->format('%y years %m months and %d days');
        }
      }else{
        $datetime1 = new DateTime($p['doj']);
        $datetime2 = new DateTime(date('Y-m-d'));
        $interval = $datetime1->diff($datetime2);
        $experience = $interval->format('%y years %m months and %d days');
      }
      
    }else{
      $experience = '-';
    }

                           
  ?> 
  <tr style="vertical-align: middle !important;">
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $i;?></td>    
    <td style="vertical-align: middle !important;text-align: center;">     
      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
        <?=$p['fullname']?>
      </a>    
    </td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['role']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['email']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['department']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['designation']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['doj']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['dob']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo isset($personal_info->marital_status)?$personal_info->marital_status:'';?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['gender']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['terminationdate']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['resignationdate']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['salary']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['address'];
                              echo isset($p['city'])?$p['city'].'<br>':'';
                              echo isset($p['state'])?$p['state'].'<br>':'';
                    ?></td>

    <td style="vertical-align: middle !important;text-align: center;"><?php echo $p['phone']?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo isset($emergency_info->contact_name1)?$emergency_info->contact_name1.'<br>':'';        
      echo isset($emergency_info->relationship1)?$emergency_info->relationship1.'<br>':'';
      echo isset($emergency_info->contact1_phone1)?$emergency_info->contact1_phone1:'';
                    ?></td>
    <td style="vertical-align: middle !important;text-align: center;"><?php echo $experience?></td>
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