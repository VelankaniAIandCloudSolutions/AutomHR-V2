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

      if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                 
                

                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                     $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = U.id
                    where ul.user_id = '".$_POST['user_id']."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                   $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where U.department_id = '".$dept_id."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }
                  if(isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id'])){
                     $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where U.teamlead_id = '".$_POST['teamlead_id']."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }

                  if(isset($_POST['range']) && !empty($_POST['range'])){
                   
                    $date_range = explode('-', $_POST['range']);
                    $start_date = $date_range[0];
                    $end_date = $date_range[1];
                    $start_time=strtotime($start_date);
                    $start_day=date("d",$start_time);
                    $start_month=date("m",$start_time);
                    $start_year=date("Y",$start_time);
                    $end_date=strtotime($end_date);
                    $end_day=date("d",$end_date);
                    $end_month=date("m",$end_date);
                    $end_year=date("Y",$end_date);
                   
                    $from_date = date("Y-m-d", $start_time);       
                      $to_date = date("Y-m-d", $end_date);
                      $earlier = new DateTime($from_date);
                      $later = new DateTime($to_date);

                      $col_count = $later->diff($earlier)->format("%a");

                      $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where ((ul.leave_from >= '".$from_date."' AND ul.leave_from >= '".$to_date."') || (ul.leave_to <= '".$from_date."' AND ul.leave_to <= '".$to_date."'))  and ul.status =1 order by ul.id  DESC ")->result_array();
                  } 
                } else{              
                 $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and ul.status =1 order by ul.id  DESC ")->result_array();
            }?>

            <table style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%; padding: 8px;">

              <tr style="background-color:#c6e0b3">
                <td><?php echo lang('employees_boss');?></td>
                <td><?php echo (isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id']))?ucfirst(user::displayName($_POST['teamlead_id'])):"all"?></td>
                <td><?php echo lang('date_of_impresion');?></td>
                <td style="text-align: left;"><?php echo date('Y-m-d-H:i:s');?></td>
                
              </tr>
              <tr style="background-color:#c6e0b3">
                <td><?php echo lang('department');?></td>
                <td><?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?ucfirst(user::GetDepartmentNameById($_POST['department_id'])):"All"?></td>
                <td><?php echo lang('since_date');?></td>
                <td style="text-align: left;"><?php echo (isset($from_date) && !empty($from_date))?$from_date.'-00:00:00':"-"?></td>
              </tr>
              <tr style="background-color:#c6e0b3">
                <td><?php echo lang('position');?></td>
                <td><?php echo (isset($designation_name))?$designation_name:"All"?></td>
                <td><?php echo lang('to_date');?></td>
                <td style="text-align: left;"><?php echo (isset($to_date) && !empty($to_date))?$to_date.'-23:59:59':"-"?></td>
                
              </tr>
            </table>
            <table id="excel_table" class="" style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%;border: 1px solid #3a3a3a; padding: 8px;">
           <!--  <thead>
              
            </thead> -->
            <tbody>
              <tr class="" style="vertical-align: middle !important;background-color:#24b23c">
                <th>Employee</th>
                <th>Date</th>
                <th>Department</th>
                <th class="text-center">Leave Type</th>
                <th class="text-center">No of Days</th>
                <th class="text-center">Remaining Leave</th>
                <th class="text-center">Total Leaves</th>
                <th class="text-center">Total Leave Taken</th>
                <th class="text-center">Leave Carry Forward</th>
              
              </tr>   
             <?php if(!empty($leave_list)){
                        $leave_count = array();
                        $job_experience = 0;
                        $doj = '';
                        foreach($leave_list as $key => $levs){ 

                          $doj = $levs['doj'];
                          $cr_date = date('Y-m-d');

                          $ts1 = strtotime($doj);
                          $ts2 = strtotime($cr_date);
                          $year1 = date('Y', $ts1);
                          $year2 = date('Y', $ts2);
                          $month1 = date('m', $ts1);
                          $month2 = date('m', $ts2);
                          $job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);
                          $this->db->select_sum('leave_days');
                          if($job_experience < 3){
                            $this->db->where('leave_id !=','6');
                            $this->db->where('leave_id !=','7');
                            $this->db->where('leave_id !=','8');
                            $this->db->where('leave_id !=','9');
                          } else {
                            if($levs['gender'] =='male'){
                              $this->db->where('leave_id !=','6');
                            }
                            if($levs['gender'] =='female'){
                              $this->db->where('leave_id !=','7');
                            }
                          }
                          $this->db->where('status','0');
                          $total_leave = $this->db->get('dgt_common_leave_types')->row_array();

                          $this->db->select_sum('leave_days');
                          $leave_count = $this->db->get_where('user_leaves',array('user_id'=> $levs['user_id'],'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();  
                          $carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'2'))->row_array();

                          $last_yr = date("Y",strtotime("-1 year"));
// echo $last_yr; exit;
                          $carry_days = $this->db->select_sum('leave_days')
                                       ->from('dgt_user_leaves')
                                       ->where('user_id',$levs['user_id'])
                                       ->like('leave_from',$last_yr)
                                       ->like('leave_to',$last_yr)
                                       ->get()->row()->leave_days;

                            if($carry_days != '')
                          {
                            $bl_leaves = $carry_days - $total_leave['leave_days']; 
                          }else{
                            $bl_leaves = 0; 
                          }
                          if($bl_leaves < 0){     
                            $ext_leaves = abs($bl_leaves);
                            $ext_leaves = $carry_leaves;
                          }else{
                            $ext_leaves = 0;
                          }
                          $user = $this->db->get_where('users',array('id'=>$levs['user_id']))->row_array();
            
                          if(!empty($user['designation_id'])){
                            $designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
                            $designation_name = $designation['designation'];
                            
                          }else{
                            $designation_name = '-';
                          }
                          if(!empty($user['department_id'])){
                            $department = $this->db->get_where('departments',array('deptid'=>$user['department_id']))->row_array();
                            $department_name = $department['deptname'];
                            
                          }else{
                            $department_name = '-';
                          }
                          $imgs = '';
                                  if($levs['avatar'] != 'default_avatar.jpg'){
                                      $imgs = $levs['avatar'];
                                      
                                  }else{
                                      $imgs = "default_avatar.jpg";
                                  }
                        ?>
                        <tr class="attendance_record" style="vertical-align: middle !important;">
                          <td style="vertical-align: middle !important;text-align: center;">
                            <h2 class="table-avatar">
                              <a href="<?php echo base_url().'employees/profile_view/'.$levs['user_id'];?>" class="avatar avatar-sm mr-2"> <img class="avatar" class="avatar-img rounded-circle" src="<?php echo base_url();?>assets/avatar/<?php echo  $imgs;?>" alt="User Image"></a>
                              <a class="text-info" href="<?php echo base_url()?>leaves/show_leave/<?=$levs['user_id']?>"><?php echo user::displayName($levs['user_id']);?>
                              </a>
                            </h2>
                          </td>
                           <td style="vertical-align: middle !important;text-align: center;"><?=date('d-m-Y',strtotime($levs['leave_from']))?></td>
                           <td style="vertical-align: middle !important;text-align: center;"><?php echo ucfirst($department_name);?></td>
                           <td style="vertical-align: middle !important;text-align: center;">
                            <button class="btn btn-outline-info btn-sm"><?php echo (!empty($levs['l_type']))?$levs['l_type']:''?></button>
                          </td>
                           <td style="vertical-align: middle !important;text-align: center;"> <span class="btn btn-danger btn-sm"><?php 
              echo $levs['leave_days'];
              if($levs['leave_day_type'] == 1){
                echo ' ( Full Day )';
              }else if($levs['leave_day_type'] == 2){
                echo ' ( First Half )';
              }else if($levs['leave_day_type'] == 3){
                echo ' ( Second Half )';
              }?></span></td>
                           <td style="vertical-align: middle !important;text-align: center;"><span class="btn btn-warning btn-sm"><b><?php echo $total_leave['leave_days'] - $leave_count['leave_days'];?></b></span></td>
                           <td style="vertical-align: middle !important;text-align: center;"><span class="btn btn-success btn-sm"><b><?php echo($total_leave['leave_days'] != 0)?$total_leave['leave_days']:0;?></b></span></td>
                           <td style="vertical-align: middle !important;text-align: center;"><?php echo ($leave_count['leave_days'] !='')?$leave_count['leave_days']:0;?></td>
                           <td style="vertical-align: middle !important;text-align: center;"><?php echo $ext_leaves;?></td>
                        </tr>
                      
                      <?php }
                    }?>


                    </tbody>
      </table>