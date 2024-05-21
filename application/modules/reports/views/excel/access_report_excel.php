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
$branch_id = $this->session->userdata('branch_id');

date_default_timezone_set('Asia/Kolkata');
  $punch_in_date = date('Y-m-d');
  $punch_in_time = date('H:i');
  $punch_in_date_time = date('Y-m-d H:i');


   $strtotime = strtotime($punch_in_date_time);
   $a_year    = date('Y',$strtotime);
   $a_month   = date('m',$strtotime);
   $a_day     = date('d',$strtotime);
   $a_days     = date('d',$strtotime);
   $a_dayss     = date('d',$strtotime);
   $a_cin     = date('H:i',$strtotime);
   $subdomain_id     = $this->session->userdata('subdomain_id');

   if($branch_id != '') {
        $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
    }
   $record = $this->db->select('ad.*')
                ->from('attendance_details ad')
                ->join('users u', 'u.id=ad.user_id')
                ->where(array('a_month'=>$a_month,'a_year'=>$a_year, 'ad.subdomain_id'=> $subdomain_id))
                ->get()
                ->result_array();
   /*$where     = array('subdomain_id'=>$subdomain_id,'a_month'=>$a_month,'a_year'=>$a_year);
   // $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
?>
 
              <?php 
          $user_id = array();
        if(!empty($_POST['id_code']) || !empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                    
                 
                  if(isset($_POST['id_code']) && !empty($_POST['id_code'])){

                    $users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'id_code'=>$_POST['id_code']))->row_array();
 
                    if(!empty($users)){
                      $user_id[] = $users['id'];
                    }else{
                      $user_id ='';
                    }

                  } 

                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                    $user_id[] = $_POST['user_id'];
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                    $dept_users= $this->db->select('*')
                                ->from('users')
                                ->where('subdomain_id',$subdomain_id)
                                ->where("FIND_IN_SET('$dept_id',department_id) !=", 0)
                                //->where_in('department_id',$_POST['department_id'])
                                ->get()
                                ->result_array();
                    if(!empty($dept_users)){
                      foreach ($dept_users as $key => $value) {
                        $user_id[] = $value['id'];
                      }
                    }
                  }
                  if(isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id'])){
                    $team_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'teamlead_id'=>$_POST['teamlead_id']))->result_array();
                    if(!empty($team_users)){
                      foreach ($team_users as $key => $value) {
                        $user_id[] = $value['id'];
                      }
                    }
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
                       
                    if(empty($user_id)){
                       // echo "<pre>";   print_r($_POST); exit;
                      // $all_users = $this->db->get_where('users',array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0))->result_array();
                      /*$this->db->where('a_month >=', $start_month);
                      $this->db->where('a_month <=', $end_month);
                      $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year <=', $end_year);
                      $this->db->where('subdomain_id', $subdomain_id);
                      $all_users =  $this->db->get('attendance_details')->result_array();*/

                      if($branch_id != '') {
                            $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                        }
                        $all_users = $this->db->select('ad.*')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('a_month >='=>$start_month, 'a_month <='=>$end_month, 'a_year >='=>$start_year,'a_year <='=>$end_year, 'ad.subdomain_id'=> $subdomain_id))
                                    ->get()
                                    ->result_array();
                      // echo "<pre>";   print_r($all_users); exit;
                       if(!empty($all_users)){
                        foreach ($all_users as $key => $value) {

                          $user_ids[] = $value['user_id'];
                          
                        }
                        $user_id = $user_ids;
                      }
                    }
                     
                  } 
              } else{                
                 if(!empty($record)){
                  foreach ($record as $key => $value) {
                    $user_id[] = $value['user_id'];
                  }
                }
            }?>

            <table style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%; padding: 8px;">

              <tr style="background-color:#c6e0b3">
                <td><?php echo lang('employees_boss');?></td>
                <td><?php echo (isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id']))?ucfirst(user::displayName($_POST['teamlead_id'])):"All"?></td>
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
                <th><?=lang('date')?></th>
                <th colspan="2"><?=lang('employee')?></th>
                <th><?=lang('time')?></th>
              
              </tr>   
            <?php 
        
            $users_id =  array_unique($user_id); 

            foreach ($users_id as $key => $value) {

              if($value !=1){                    
                $user_id = $value;                       
               // print_r($_POST['range']); exit;
                  if(isset($_POST['range']) && !empty($_POST['range'])){
                    /*$this->db->select('month_days,month_days_in_out');
                    $this->db->where('user_id', $user_id);
                    $this->db->where('a_month ', $start_month);
                    // $this->db->where('a_month <=', $end_month);
                    // $this->db->where('a_year >=', $start_year);
                    $this->db->where('a_year ', $start_year);
                    $this->db->where('subdomain_id', $subdomain_id);
                    $results =  $this->db->get('attendance_details')->result_array();*/

                    if($branch_id != '') {
                        $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                    }
                    $results = $this->db->select('month_days,month_days_in_out')
                                ->from('attendance_details ad')
                                ->join('users u', 'u.id=ad.user_id')
                                ->where(array('user_id'=>$user_id, 'a_month '=>$start_month, 'a_year '=>$start_year,'ad.subdomain_id'=> $subdomain_id))
                                ->get()
                                ->result_array();

                  } else{
                    $a_year    = date('Y');
                    $a_month   = date('m');

                    if($branch_id != '') {
                        $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                    }
                    $results = $this->db->select('month_days,month_days_in_out')
                                ->from('attendance_details ad')
                                ->join('users u', 'u.id=ad.user_id')
                                ->where(array('user_id'=>$user_id, 'a_month '=>$a_month, 'a_year '=>$a_year,'ad.subdomain_id'=> $subdomain_id,'u.id!='=>$this->session->userdata('user_id')))
                                ->get()
                                ->result_array();
                   /*$where     = array('subdomain_id'=>$subdomain_id,'user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                   $this->db->select('month_days,month_days_in_out');
                   $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
                   
                  }                     
                  $sno=1;
                  foreach ($results as $rows) {
                    $list=array();
                    if(isset($_POST['range']) && !empty($_POST['range'])){
                      $number = $col_count;
                      $start_val = 0;
                    }else{
                      $month = $a_month;
                      $year = $a_year;

                      $number = $a_day;
                      // $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                      $start_val = $a_day;

                    }                   
                    for($d=$start_val; $d<=$number; $d++)
                    {
                      if(isset($_POST['range']) && !empty($_POST['range'])){
                            $time =   date(strtotime('+'.$d.' days', strtotime($date_range[0])));
                      } else{
                         $time=mktime(12, 0, 0, $month, $d, $year);     
                    

                      }
                          // if (date('m', $time)==$month)       
                      $date=date('d M Y', $time);
                      $new_date=date('d/m/Y', $time);
                      $schedule_date=date('Y-m-d', $time);
                      $a_day =date('d', $time);
                      $a_month =date('m', $time);
                      $a_year =date('Y', $time);
                       // echo print_r($schedule_date) ; exit;   
                      /*$this->db->select('month_days,month_days_in_out');
                      $this->db->where('user_id', $user_id);
                      $this->db->where('a_month ', $a_month);
                      // $this->db->where('a_month <=', $end_month);
                      // $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year ', $a_year);
                      $this->db->where('subdomain_id', $subdomain_id);
                      $rows =  $this->db->get('attendance_details')->row_array(); */

                        if($branch_id != '') {
                            $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                        }
                        $rows = $this->db->select('month_days,month_days_in_out')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('user_id'=>$user_id, 'a_month '=>$a_month, 'a_year '=>$a_year,'ad.subdomain_id'=> $subdomain_id,'u.id!='=>$this->session->userdata('user_id')))
                                    ->get()
                                    ->row_array();

                      if(!empty($rows['month_days'])){
                        $month_days =  unserialize($rows['month_days']);
                        $month_days_in_out =  unserialize($rows['month_days_in_out']);
                        $day = $month_days[$a_day-1];
                        $day_in_out = $month_days_in_out[$a_day-1];
                        $latest_inout = end($day_in_out);
                      
                        $user_details= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'id'=>$user_id))->row_array();
                        $account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();                    
                        if(!empty($user_details['designation_id'])){
                          $designation = $this->db->get_where('designation',array('subdomain_id'=>$subdomain_id,'id'=>$user_details['designation_id']))->row_array();
                          $designation_name = $designation['designation'];
                          
                        }else{
                          $designation_name = '-';
                        }
                  

                        $production_hour=0;                          
                        foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                        {
                          if(!empty($punch_detail['punch_in']) )
                          { ?>
                            <tr class="attendance_record" style="vertical-align: middle !important;">
                              <td style="vertical-align: middle !important;text-align: center;"><?php echo $new_date;?> <br>
                                <?php echo date('l', $time)?>
                              </td>
                              <td style="vertical-align: middle !important;text-align: center;" colspan="2">
                                 
                                  <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                                  <b> <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?$user_details['id_code']:"";?></b>
                                </td>
                              <td style="vertical-align: middle !important;text-align: center;"><?php echo '-> '.$punch_detail['punch_in'];?></td>
                            </tr>

                       <?php}
                          if(!empty($punch_detail['punch_out'])){?>
                            <tr class="attendance_record" style="vertical-align: middle !important;">
                              <td style="vertical-align: middle !important;text-align: center;"><?php echo $new_date;?> <br>
                                <?php echo date('l', $time)?>
                              </td>
                              <td style="vertical-align: middle !important;text-align: center;" colspan="2">
                                 
                                  <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                                  <b> <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?$user_details['id_code']:"";?></b>
                                </td>
                              <td style="vertical-align: middle !important;text-align: center;"><?php echo '<- '.$punch_detail['punch_out'];?></td>
                            </tr>
                            <tr class="attendance_record" style="vertical-align: middle !important;">
                              <td style="vertical-align: middle !important;text-align: center;"><?php echo lang('attendance_time');  ?></td>
                              <td  style="vertical-align: middle !important;" colspan="2"><?php  $production_hour = time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));?>
                              <?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?>
                              </td>
                              <td class="attendance_record" style="vertical-align: middle !important;"><?php echo '';?></td>
                            </tr>
                          <?php $production_hour = 0;
                          }                                     
                        }
                      } 
                    } 
                  } 
                } 
              } ?>
        </tbody>
      </table>