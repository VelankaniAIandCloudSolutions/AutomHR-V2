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

<?php if(!empty($_POST['id_code']) || !empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
  {                     
                 
      if(isset($_POST['id_code']) && !empty($_POST['id_code'])){

        if($branch_id != '') {
            $this->db->where("branch_id IN (".$branch_id.")",NULL, false);
        }
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
  } ?>
    <table style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%; padding: 8px;">

    <tr style="background-color:#c6e0b3">
      <td><?php echo lang('employees_boss');?></td>
      <td><?php echo (isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id']))?ucfirst(user::displayName($_POST['teamlead_id'])):"all"?></td>
      <td><?php echo lang('date_of_impresion');?></td>
      <td style="text-align: left;" colspan="6"><?php echo date('Y-m-d-H:i:s');?></td>
      
    </tr>
    <tr style="background-color:#c6e0b3">
      <td><?php echo lang('department');?></td>
      <td><?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?ucfirst(user::GetDepartmentNameById($_POST['department_id'])):"All"?></td>
      <td><?php echo lang('since_date');?></td>
      <td style="text-align: left;" colspan="6"><?php echo (isset($from_date) && !empty($from_date))?$from_date.'-00:00:00':"-"?></td>
    </tr>
    <tr style="background-color:#c6e0b3">
      <td><?php echo lang('position');?></td>
      <td><?php echo (isset($designation_name))?$designation_name:"All"?></td>
      <td><?php echo lang('to_date');?></td>
      <td style="text-align: left;" colspan="6"><?php echo (isset($to_date) && !empty($to_date))?$to_date.'-23:59:59':"-"?></td>
      
    </tr>
  </table>
  <table id="excel_table" class="" style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%;border: 1px solid #3a3a3a; padding: 8px;">
 <!--  <thead>
    
  </thead> -->
    <tbody>
      <tr class="" style="vertical-align: middle !important;background-color:#24b23c">
        <th><?=lang('employee')?></th>
        <th><?=lang('office')?></th>
        <th><?=lang('department')?></th>
        <th><?=lang('month')?></th>
        <th><?=lang('days')?></th>
        <th><?=lang('missing_time')?></th>
        <th><?=lang('work_code')?></th>
        <th><?=lang('work_code_time')?></th>
        <th><?=lang('comments')?></th>              
      </tr>   
    
      <?php  $users_id =  array_unique($user_id);
              foreach ($users_id as $key => $value) {
                  if($value !=1){                    
                    $user_id = $value;                                   
                    if(isset($_POST['range']) && !empty($_POST['range'])){                            
                      $month_start = $start_month ;
                      $month_end =  $end_month;
                      $a_year = $start_year;
                    }else{
                      $month_start = date('m');
                      $month_end =date('m');
                      $a_year    = date('Y');
                    }
                    $day_lists = array();
                    $missing_works = array();
                    $missing_works_check =0;
                    $workcode = array();
                    $work_code_times = array();
                    $all_comments = array();
                    $punch_workcode = 0;
                    $employee_incident_count =0;
                    $employee_incident ='';
                    $employee_incidents = array();
                    for ($m=$month_start; $m <=$month_end ; $m++) {
                      if(isset($_POST['range']) && !empty($_POST['range'])){
                          $number = $col_count; 

                        }else{
                          $numbers = cal_days_in_month(CAL_GREGORIAN, $m, $a_year);
                          $number = $numbers -1;
                        }
                        $month_name = date("F", mktime(0, 0, 0, $m, 10)); 
                      /*$this->db->select('month_days,month_days_in_out');
                      $this->db->where('user_id', $user_id);
                      $this->db->where('a_month ', $m);
                      $this->db->where('a_year ', $a_year);
                      $rows =  $this->db->get('attendance_details')->row_array(); */

                        if($branch_id != '') {
                            $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                        }
                        $rows = $this->db->select('month_days,month_days_in_out')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id', 'left')
                                    ->where(array('user_id'=>$user_id, 'a_month '=>$m, 'a_year '=>$a_year,'ad.subdomain_id'=>$subdomain_id))
                                    ->get()
                                    ->row_array();
                      // echo "<pre>"; print_r($rows); exit;
                        if(!empty($rows['month_days'])){
                          $user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
                        $account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();         
                        if(!empty($user_details['designation_id'])){
                          $designation = $this->db->get_where('designation',array('id'=>$user_details['designation_id']))->row_array();
                          $designation_name = $designation['designation'];
                          
                        }else{
                          $designation_name = '-';
                        }?>


                        <?php 
                          
                        for($d=0; $d<=$number; $d++)
                        {
                          if(isset($_POST['range']) && !empty($_POST['range'])){
                            $time =   date(strtotime('+'.$d.' days', strtotime($date_range[0])));
                          } else{
                             $time= $time=mktime(12, 0, 0, $m, $d+1, $a_year);       
                          }
                          
                        // if (date('m', $time)==$month)       
                          $date=date('d M Y', $time);
                          $new_date=date('d/m/Y', $time);
                          $schedule_date=date('Y-m-d', $time);
                          $a_day =date('d', $time);
                          $a_month =date('m', $time);
                          $a_year =date('Y', $time);
                           // echo $schedule_date. '<br>'; 
                         
                          
                          // echo $this->db->last_query();
                          
                          if(!empty($rows['month_days'])){

                            $month_days =  unserialize($rows['month_days']);
                            $month_days_in_out =  unserialize($rows['month_days_in_out']);
                            $day = $month_days[$a_day-1];
                            $day_in_out = $month_days_in_out[$a_day-1];
                            $latest_inout = end($day_in_out);                             
                            
                              $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                            $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                              if(!empty($user_schedule)){
                                $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);
                                // echo $total_scheduled_hour;
                               $total_scheduled_minutes = $total_scheduled_hour;
                                    } else{
                                      $total_scheduled_minutes = 0;
                                    }
                                  $production_hour=0;
                                  $k = 1;
                                  foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                  {

                                      if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                      {
                                         $days = $a_day;
                                          $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                          $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                          if($k == 1){                                       
                                           // print_r($today_work_hour); exit();
                                            $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);   
                                           $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);     
                                           // echo $days; exit;
                                           $first_punch_in = $punch_detail['punch_in'];
                                          $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                           
                                        }
                                        $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                          $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                        if($punch_detail['punch_out'] > $today_work_hour['max_end_time']){
                                            $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                        }else{
                                            $between_endto_max_end = 0;
                                        }                                       

                                          $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                      }
                                                
                                    $k++;    
                                    if(!empty($punch_detail['punchin_workcode']) || !empty($punch_detail['punchout_workcode']))
                                      {
                                        $punch_workcode = 1;
                                      }                          
                                       
                                  }
                                 // echo 'between_minstartto_start'.$between_minstartto_start; exit;
                                  if($production_hour > 0 && $later_entry_hours>0){
                                    $production_hour = $production_hour-$end_between;
                                  } else{
                                    $production_hour = $production_hour-$start_between-$end_between;
                                  }
                                  if($production_hour<0){
                                    $production_hour = 0;
                                  }

                                  $missing_work=($total_scheduled_minutes)-($production_hour);
                                  // echo $missing_work; exit;
                                  if($missing_work > 0)
                                  {
                                      $missing_works_check +=$missing_work;
                                      
                                  } 
                                  // if(!empty($_POST['work_code_time'])){
                                  //  if(!empty($day['punchout_workcode']) || !empty($day['punchin_workcode'])){
                                    
                                   //  } else{

                                   //  }  
                                  // }
                                                                
                          }  
                            /*$this->db->where('emp_id', $user_id);
                            $this->db->where('start_date', $schedule_date);
                            $this->db->where('subdomain_id', $subdomain_id);
                            // $this->db->or_where('end_date', $schedule_date);
                            $employee_incidents =  $this->db->get('calendar_incident')->row_array();*/

                            if($branch_id != '') {
                                    $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                                }

                                $employee_incidents = $this->db->select('*')
                                        ->from('calendar_incident ci')
                                        ->from('users u')
                                        ->where(array('emp_id'=>$user_id,'start_date'=>$schedule_date,'ci.subdomain_id'=>$subdomain_id))
                                        ->get()
                                        ->row_array();
                            // print_r($employee_incidents);
                                if(!empty($employee_incidents)){
                                  // $incident_name =  $this->db->get_where('incidents',array('id' => $employee_incidents['incident']))->row_array();
                                  // if(!empty($incident_name)){                                    
                                  //  $employee_incident = $incident_name['incident_name'];
                                  // }
                                  $employee_incident_count = 1;
                                }
                          
                        }

                        if($_POST['work_code_time'] == 'yes'){

                        if($punch_workcode > 0 || $employee_incident_count > 0){

                          
                          ?>
                        <tr class="attendance_record" style="vertical-align: middle !important;">
                         
                          <td style="vertical-align: middle !important;text-align: center;" >
                           
                            <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                            <b> <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?' - '.$user_details['id_code']:"";?></b><br>
                          </td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo ''; ?></td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo ucfirst(user::GetDepartmentNameById($user_details['department_id']));?></td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo $month_name;?></td>
                          <!-- <td> -->

                        <?php 
                          $comma_count = 0;
                          $day_list = 0;
                        for($d=0; $d<=$number; $d++)
                        {
                          if(isset($_POST['range']) && !empty($_POST['range'])){
                            $time =   date(strtotime('+'.$d.' days', strtotime($date_range[0])));
                          } else{
                             $time= $time=mktime(12, 0, 0, $m, $d+1, $a_year);       
                          }
                          
                        // if (date('m', $time)==$month)       
                          $date=date('d M Y', $time);
                          $new_date=date('d/m/Y', $time);
                          $schedule_date=date('Y-m-d', $time);
                          $a_day =date('d', $time);
                          $a_month =date('m', $time);
                          $a_year =date('Y', $time);
                           // echo $schedule_date. '<br>'; 
                         
                          
                          // echo $this->db->last_query();
                          
                          if(!empty($rows['month_days'])){

                            $month_days =  unserialize($rows['month_days']);
                            $month_days_in_out =  unserialize($rows['month_days_in_out']);
                            $day = $month_days[$a_day-1];
                            $day_in_out = $month_days_in_out[$a_day-1];
                            $latest_inout = end($day_in_out);                             
                            
                              $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                            $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                              if(!empty($user_schedule)){
                                $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);
                                // echo $total_scheduled_hour;
                               $total_scheduled_minutes = $total_scheduled_hour;
                                    } else{
                                      $total_scheduled_minutes = 0;
                                    }

                              
                                  $production_hour=0;
                                  $k = 1;
                                  foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                  {

                                      if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                      {
                                         $days = $a_day;
                                          $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                          $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                          if($k == 1){                                       
                                           // print_r($today_work_hour); exit();
                                            $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);   
                                           $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);     
                                           // echo $days; exit;
                                           $first_punch_in = $punch_detail['punch_in'];
                                          $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                           
                                        }
                                        $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                          $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                        if($punch_detail['punch_out'] > $today_work_hour['max_end_time']){
                                            $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                        }else{
                                            $between_endto_max_end = 0;
                                        }                                       

                                          $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                      }
                                                
                                    $k++;                              
                                       
                                       if(!empty($punch_detail['punchin_workcode']) || !empty($punch_detail['punchout_workcode']))
                                      {
                                        $punch_workcode = 1;
                                      }else{
                                        $punch_workcode = 0;
                                      }
                                  }
                                 // echo 'between_minstartto_start'.$between_minstartto_start; exit;
                                  if($production_hour > 0 && $later_entry_hours>0){
                                    $production_hour = $production_hour-$end_between;
                                  } else{
                                    $production_hour = $production_hour-$start_between-$end_between;
                                  }
                                  if($production_hour<0){
                                    $production_hour = 0;
                                  }

                                  if($production_hour< $total_scheduled_minutes){
                                    $missing_work=($total_scheduled_minutes)-($production_hour);
                                }else{
                                  $missing_work =0;
                                }
                                  // echo $missing_work; exit;
                                /*$this->db->where('emp_id', $user_id);
                                $this->db->where('start_date', $schedule_date);
                                $this->db->where('subdomain_id', $subdomain_id);
                                // $this->db->or_where('end_date', $schedule_date);
                                $employee_incidents =  $this->db->get('calendar_incident')->row_array();*/

                                if($branch_id != '') {
                                    $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                                }

                                $employee_incidents = $this->db->select('*')
                                        ->from('calendar_incident ci')
                                        ->from('users u')
                                        ->where(array('emp_id'=>$user_id,'start_date'=>$schedule_date,'ci.subdomain_id'=>$subdomain_id))
                                        ->get()
                                        ->row_array();

                                  if(!empty($employee_incidents)){
                                    $employee_incident_count = 1;                                    
                                  }else{
                                    $employee_incident_count = 0;
                                  }
                                  
                                   
                                  if($punch_workcode >0 || $employee_incident_count>0)
                                  {
                                      $missing_works[] =$missing_work;
                                      $day_lists[] = $a_day;
                                      
                                      if(!empty($employee_incidents)){
                                      $incident_name =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $employee_incidents['incident']))->row_array();
                                      $employee_incident = $incident_name['incident_name'];
                                      $workcode[] = $employee_incident;
                                      
                                    }

                                  if(!empty($day['punchout_workcode']) || !empty($day['punchin_workcode'])){
                                    if(!empty($day['punchin_workcode'])){
                                      $work_code = $day['punchin_workcode'];
                                      $work_code_time = $day['punch_in'];
                                      $comments = $day['punch_in_description'];
                                    }else{
                                      $work_code = $day['punchout_workcode'];
                                      $work_code_time = $day['punch_out'];
                                      $comments = $day['punch_out_description'];
                                    }
                                       $punch_workcode =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $work_code))->row_array();                                       
                                       $workcode[] = $punch_workcode['incident_name'];
                                       $work_code_times[] = $work_code_time;
                                       $all_comments[] = $comments;
                                    }
                                    
                                  }

                                                                
                          }  
                          
                        } ?>
                        <!-- </td>                         -->
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $day_lists);?></td>
                        <td style="vertical-align: middle !important;text-align: center;">><?php echo !empty(array_sum($missing_works))?intdiv(array_sum($missing_works), 60).'.'. (array_sum($missing_works) % 60).' hrs':'0';?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $workcode);?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $work_code_times);;?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $all_comments);;?></td>
              </tr>
                      <?php }
                      } else if($_POST['work_code_time'] == 'no'){
                          if($missing_works_check > 0 ){?>
                        <tr class="attendance_record" style="vertical-align: middle !important;">
                         <td style="vertical-align: middle !important;text-align: center;" >
                           
                            <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                            <b> <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?' - '.$user_details['id_code']:"";?></b><br>
                          </td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo ''; ?></td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo ucfirst(user::GetDepartmentNameById($user_details['department_id']));?></td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo $month_name;?></td>
                          <!-- <td> -->

                        <?php 
                          $comma_count = 0;
                          $day_list = 0;
                        for($d=0; $d<=$number; $d++)
                        {
                          if(isset($_POST['range']) && !empty($_POST['range'])){
                            $time =   date(strtotime('+'.$d.' days', strtotime($date_range[0])));
                          } else{
                             $time= $time=mktime(12, 0, 0, $m, $d+1, $a_year);       
                          }
                          
                        // if (date('m', $time)==$month)       
                          $date=date('d M Y', $time);
                          $new_date=date('d/m/Y', $time);
                          $schedule_date=date('Y-m-d', $time);
                          $a_day =date('d', $time);
                          $a_month =date('m', $time);
                          $a_year =date('Y', $time);
                           // echo $schedule_date. '<br>'; 
                         
                          
                          // echo $this->db->last_query();
                          
                          if(!empty($rows['month_days'])){

                            $month_days =  unserialize($rows['month_days']);
                            $month_days_in_out =  unserialize($rows['month_days_in_out']);
                            $day = $month_days[$a_day-1];
                            $day_in_out = $month_days_in_out[$a_day-1];
                            $latest_inout = end($day_in_out);                             
                            
                              $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                            $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                              if(!empty($user_schedule)){
                                $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);
                                // echo $total_scheduled_hour;
                               $total_scheduled_minutes = $total_scheduled_hour;
                                    } else{
                                      $total_scheduled_minutes = 0;
                                    }

                              
                                  $production_hour=0;
                                  $k = 1;
                                  foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                  {

                                      if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                      {
                                         $days = $a_day;
                                          $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                          $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                          if($k == 1){                                       
                                           // print_r($today_work_hour); exit();
                                            $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);   
                                           $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);     
                                           // echo $days; exit;
                                           $first_punch_in = $punch_detail['punch_in'];
                                          $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                           
                                        }
                                        $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                          $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                        if($punch_detail['punch_out'] > $today_work_hour['max_end_time']){
                                            $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                        }else{
                                            $between_endto_max_end = 0;
                                        }                                       

                                          $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                      }
                                                
                                    $k++;                              
                                       
                                       if(!empty($punch_detail['punchin_workcode']) || !empty($punch_detail['punchout_workcode']))
                                      {
                                        $punch_workcode = 1;
                                      }else{
                                        $punch_workcode = 0;
                                      }
                                  }
                                 // echo 'between_minstartto_start'.$between_minstartto_start; exit;
                                  if($production_hour > 0 && $later_entry_hours>0){
                                    $production_hour = $production_hour-$end_between;
                                  } else{
                                    $production_hour = $production_hour-$start_between-$end_between;
                                  }
                                  if($production_hour<0){
                                    $production_hour = 0;
                                  }

                                  if($production_hour< $total_scheduled_minutes){
                                    $missing_work=($total_scheduled_minutes)-($production_hour);
                                }else{
                                  $missing_work =0;
                                }
                                  // echo $missing_work; exit;
                                /*$this->db->where('emp_id', $user_id);
                                $this->db->where('start_date', $schedule_date);
                                $this->db->where('subdomain_id', $subdomain_id);
                                // $this->db->or_where('end_date', $schedule_date);
                                $employee_incidents =  $this->db->get('calendar_incident')->row_array();*/

                                if($branch_id != '') {
                                    $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                                }

                                $employee_incidents = $this->db->select('*')
                                        ->from('calendar_incident ci')
                                        ->from('users u')
                                        ->where(array('emp_id'=>$user_id,'start_date'=>$schedule_date,'ci.subdomain_id'=>$subdomain_id))
                                        ->get()
                                        ->row_array();

                                  if(!empty($employee_incidents)){
                                    $employee_incident_count = 1;                                    
                                  }else{
                                    $employee_incident_count = 0;
                                  }

                                   
                                  if($missing_work > 0 )
                                  {
                                      $missing_works[] =$missing_work;
                                      $day_lists[] = $a_day;
                                      
                                      if(!empty($employee_incidents)){
                                      $incident_name =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $employee_incidents['incident']))->row_array();
                                      $employee_incident = $incident_name['incident_name'];
                                      $workcode[] = $employee_incident;
                                      
                                    }

                                  if(!empty($day['punchout_workcode']) || !empty($day['punchin_workcode'])){
                                    if(!empty($day['punchin_workcode'])){
                                      $work_code = $day['punchin_workcode'];
                                      $work_code_time = $day['punch_in'];
                                      $comments = $day['punch_in_description'];
                                    }else{
                                      $work_code = $day['punchout_workcode'];
                                      $work_code_time = $day['punch_out'];
                                      $comments = $day['punch_out_description'];
                                    }
                                       $punch_workcode =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $work_code))->row_array();                                       
                                       $workcode[] = $punch_workcode['incident_name'];
                                       $work_code_times[] = $work_code_time;
                                       $all_comments[] = $comments;
                                    }
                                    
                                  }                                 
                          }  
                          
                        } ?>
                        <!-- </td>                         -->
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $day_lists);?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo !empty(array_sum($missing_works))?intdiv(array_sum($missing_works), 60).'.'. (array_sum($missing_works) % 60).' hrs':'0';?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $workcode);?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $work_code_times);;?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $all_comments);;?></td>
              </tr>
                      <?php }
                      }else{
                         if($missing_works_check > 0 || $punch_workcode > 0 || $employee_incident_count > 0){?>
                        <tr class="attendance_record" style="vertical-align: middle !important;">
                          <td style="vertical-align: middle !important;text-align: center;" >
                           
                            <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                            <b> <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?' - '.$user_details['id_code']:"";?></b><br>
                          </td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo ''; ?></td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo ucfirst(user::GetDepartmentNameById($user_details['department_id']));?></td>
                          <td style="vertical-align: middle !important;text-align: center;"><?php echo $month_name;?></td>
                          <!-- <td> -->

                        <?php 
                          $comma_count = 0;
                          $day_list = 0;
                        for($d=0; $d<=$number; $d++)
                        {
                          if(isset($_POST['range']) && !empty($_POST['range'])){
                            $time =   date(strtotime('+'.$d.' days', strtotime($date_range[0])));
                          } else{
                             $time= $time=mktime(12, 0, 0, $m, $d+1, $a_year);       
                          }
                          
                        // if (date('m', $time)==$month)       
                          $date=date('d M Y', $time);
                          $new_date=date('d/m/Y', $time);
                          $schedule_date=date('Y-m-d', $time);
                          $a_day =date('d', $time);
                          $a_month =date('m', $time);
                          $a_year =date('Y', $time);
                           // echo $schedule_date. '<br>'; 
                         
                          
                          // echo $this->db->last_query();
                          
                          if(!empty($rows['month_days'])){

                            $month_days =  unserialize($rows['month_days']);
                            $month_days_in_out =  unserialize($rows['month_days_in_out']);
                            $day = $month_days[$a_day-1];
                            $day_in_out = $month_days_in_out[$a_day-1];
                            $latest_inout = end($day_in_out);                             
                            
                              $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                            $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                              if(!empty($user_schedule)){
                                $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);
                                // echo $total_scheduled_hour;
                               $total_scheduled_minutes = $total_scheduled_hour;
                                    } else{
                                      $total_scheduled_minutes = 0;
                                    }

                              
                                  $production_hour=0;
                                  $k = 1;
                                  foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                  {

                                      if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                      {
                                         $days = $a_day;
                                          $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                          $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                          if($k == 1){                                       
                                           // print_r($today_work_hour); exit();
                                            $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);   
                                           $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);     
                                           // echo $days; exit;
                                           $first_punch_in = $punch_detail['punch_in'];
                                          $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                           
                                        }
                                        $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                          $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                        if($punch_detail['punch_out'] > $today_work_hour['max_end_time']){
                                            $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                        }else{
                                            $between_endto_max_end = 0;
                                        }                                       

                                          $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                      }
                                                
                                    $k++;                              
                                       
                                       if(!empty($punch_detail['punchin_workcode']) || !empty($punch_detail['punchout_workcode']))
                                      {
                                        $punch_workcode = 1;
                                      }else{
                                        $punch_workcode = 0;
                                      }
                                  }
                                 // echo 'between_minstartto_start'.$between_minstartto_start; exit;
                                  if($production_hour > 0 && $later_entry_hours>0){
                                    $production_hour = $production_hour-$end_between;
                                  } else{
                                    $production_hour = $production_hour-$start_between-$end_between;
                                  }
                                  if($production_hour<0){
                                    $production_hour = 0;
                                  }

                                  if($production_hour< $total_scheduled_minutes){
                                    $missing_work=($total_scheduled_minutes)-($production_hour);
                                }else{
                                  $missing_work =0;
                                }
                                  // echo $missing_work; exit;
                                /*$this->db->where('emp_id', $user_id);
                                $this->db->where('start_date', $schedule_date);
                                $this->db->where('subdomain_id', $subdomain_id);
                                // $this->db->or_where('end_date', $schedule_date);
                                $employee_incidents =  $this->db->get('calendar_incident')->row_array();*/

                                if($branch_id != '') {
                                    $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                                }

                                $employee_incidents = $this->db->select('*')
                                        ->from('calendar_incident ci')
                                        ->from('users u')
                                        ->where(array('emp_id'=>$user_id,'start_date'=>$schedule_date,'ci.subdomain_id'=>$subdomain_id))
                                        ->get()
                                        ->row_array();
                                        
                                  if(!empty($employee_incidents)){
                                    $employee_incident_count = 1;                                    
                                  }else{
                                    $employee_incident_count = 0;
                                  }

                                   
                                  if($missing_work > 0 || $punch_workcode >0 || $employee_incident_count>0)
                                  {
                                      $missing_works[] =$missing_work;
                                      $day_lists[] = $a_day;
                                      
                                      if(!empty($employee_incidents)){
                                      $incident_name =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $employee_incidents['incident']))->row_array();
                                      $employee_incident = $incident_name['incident_name'];
                                      $workcode[] = $employee_incident;
                                      
                                    }

                                  if(!empty($day['punchout_workcode']) || !empty($day['punchin_workcode'])){
                                    if(!empty($day['punchin_workcode'])){
                                      $work_code = $day['punchin_workcode'];
                                      $work_code_time = $day['punch_in'];
                                      $comments = $day['punch_in_description'];
                                    }else{
                                      $work_code = $day['punchout_workcode'];
                                      $work_code_time = $day['punch_out'];
                                      $comments = $day['punch_out_description'];
                                    }
                                       $punch_workcode =  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $work_code))->row_array();                                       
                                       $workcode[] = $punch_workcode['incident_name'];
                                       $work_code_times[] = $work_code_time;
                                       $all_comments[] = $comments;
                                    }
                                    
                                  }                                 
                          }  
                          
                        } ?>
                        <!-- </td>                         -->
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $day_lists);?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo !empty(array_sum($missing_works))?intdiv(array_sum($missing_works), 60).'.'. (array_sum($missing_works) % 60).' hrs':'0';?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $workcode);?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $work_code_times);;?></td>
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo implode(',', $all_comments);;?></td>
              </tr>
                      <?php }
                      }
                    }?>
                      
                    <?php }
                   // echo'<pre>';print_r($user_absent);   exit;           
                  }
              }?>

    </tbody>
  </table>