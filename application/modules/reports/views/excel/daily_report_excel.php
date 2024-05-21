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

   
    $record = $this->db->select('ad.*')
                ->from('attendance_details ad')
                ->join('users u', 'u.id=ad.user_id')
                ->where(array('a_month'=>$a_month,'a_year'=>$a_year))
                ->get()
                ->result_array();
   /*$where     = array('subdomain_id'=>$subdomain_id,'a_month'=>$a_month,'a_year'=>$a_year);
   // $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
?>
 
              <?php 
         $user_id = array();
        if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                    
                 
                 

                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                    $user_id[] = $_POST['user_id'];
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                    $dept_users= $this->db->select('*')
                                ->from('users')
                                ->where_in('department_id',$_POST['department_id'])
                                ->get()
                                ->result_array();
                    if(!empty($dept_users)){
                      foreach ($dept_users as $key => $value) {
                        $user_id[] = $value['id'];
                      }
                    }
                  }
                  if(isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id'])){
                    $team_users= $this->db->get_where('users',array('teamlead_id'=>$_POST['teamlead_id']))->result_array();
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
                      $this->db->where('subdomain_id ', $subdomain_id);
                      $all_users =  $this->db->get('attendance_details')->result_array();*/

                       
                        $all_users = $this->db->select('ad.*')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('a_month >='=>$start_month, 'a_month <='=>$end_month, 'a_year >='=>$start_year,'a_year <='=>$end_year,'u.id!='=>$this->session->userdata('user_id')))
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
                <th><?=lang('date')?></th>
                <th colspan="2"><?=lang('employee')?></th>
                <th><?=lang('status')?></th>
              
              </tr>   
             <?php $user_id =  array_unique($user_id);

                     // echo "<pre>";   print_r($user_id); exit;

                   foreach ($user_id as $key => $value) {

                    if($value !=1){
                    
                  $user_id = $value;

$user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
$account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
                      ?>
             
              
             <?php

                    if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                    {
                      $a_month=$_POST['attendance_month'];
                    }

                     if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                    {
                      $a_year=$_POST['attendance_year'];
                    }


                    
                     
                     // print_r($_POST['range']); exit;
                    if(isset($_POST['range']) && !empty($_POST['range'])){
                      /*$this->db->select('month_days,month_days_in_out');
                      $this->db->where('user_id', $user_id);
                      $this->db->where('a_month ', $start_month);
                      // $this->db->where('a_month <=', $end_month);
                      // $this->db->where('a_year >=', $start_year);
                      $this->db->where('a_year ', $start_year);
                      $this->db->where('subdomain_id ', $subdomain_id);
                      $results =  $this->db->get('attendance_details')->result_array();*/

                        
                        $results = $this->db->select('month_days,month_days_in_out')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('user_id'=>$user_id, 'a_month '=>$start_month, 'a_year '=>$start_year,'u.id!='=>$this->session->userdata('user_id')))
                                    ->get()
                                    ->result_array();

                    } else{
                      $a_year    = date('Y');
                      $a_month   = date('m');

                       
                        $results = $this->db->select('month_days,month_days_in_out')
                                    ->from('attendance_details ad')
                                    ->join('users u', 'u.id=ad.user_id')
                                    ->where(array('user_id'=>$user_id, 'a_month '=>$a_month, 'a_year '=>$a_year,'u.id!='=>$this->session->userdata('user_id')))
                                    ->get()
                                    ->result_array();

                     /*$where     = array('subdomain_id'=>$subdomain_id,'user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                     $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
                     
                    }
                   
                     
                     $sno=1;
                     // echo "<pre>";print_r($results); 
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
                          $week_off = 0;
                          $actually_worked = 0;
                          $absent = 0;
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
                                  $this->db->where('subdomain_id ', $subdomain_id);
                                  $rows =  $this->db->get('attendance_details')->row_array(); */

                               
                                $rows = $this->db->select('month_days,month_days_in_out')
                                            ->from('attendance_details ad')
                                            ->join('users u', 'u.id=ad.user_id')
                                            ->where(array('user_id'=>$user_id, 'a_month '=>$a_month, 'a_year '=>$a_year,'u.id!='=>$this->session->userdata('user_id')))
                                            ->get()
                                            ->row_array();

                                  $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                                  $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                                  $shift =  $this->db->get_where('shifts',array('id' => $user_schedule['shift_id']))->row_array(); 
                                 if(!empty($user_schedule)){
                                    $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);

                                    $total_scheduled_minutes = $total_scheduled_hour;                                     
                                    
                                  } else{
                                    $total_scheduled_minutes = 0;
                                  }


                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);
                               
                                 
                                 $k = 1;
                               
                        
                             $user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
              $account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();                    
              if(!empty($user_details['designation_id'])){
                          $designation = $this->db->get_where('designation',array('id'=>$user_details['designation_id']))->row_array();
                          $designation_name = $designation['designation'];
                          
                        }else{
                          $designation_name = '-';
                        }
                    ?>


                    <tr class="attendance_record" style="vertical-align: middle !important;">
                        <td style="vertical-align: middle !important;text-align: center;"><?php echo $new_date;?> <br>
                        <?php echo date('l', $time)?>
                      </td>
                     <td style="vertical-align: middle !important;text-align: center;" colspan="2">
                           
                            <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                            <b> <?php echo $designation_name;?> </b><br>                             
                          
                           <?php
                           if(!empty($shift['shift_name'])){

                            echo !empty($shift['shift_name'])?ucfirst($shift['shift_name']):''?>&nbsp;<?php echo !empty($total_scheduled_minutes)?'('.intdiv($total_scheduled_minutes, 60).'.'. ($total_scheduled_minutes % 60).' hrs)':'';?><br> 
                           <?php  }

                          $punchin_workcode = '';
                          $punchout_workcode = '';
                            foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                          {
                            //  if(isset($punch_detail['punchin_workcode']) && !empty($punch_detail['punchin_workcode'])){
                            //   $punchin_workcodes = $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $punch_detail['punchin_workcode']))->row_array();
                            //  $punchin_workcode= '('.$punchin_workcodes['incident_name'].')';
                            // }else{
                            //   $punchin_workcode = '';
                            // }
                            
                            //  if(isset($punch_detail['punchout_workcode']) && !empty($punch_detail['punchout_workcode'])){
                            //     $punchout_workcodes=  $this->db->get_where('incidents',array('subdomain_id'=>$subdomain_id,'id' => $punch_detail['punchout_workcode']))->row_array(); 
                            //      $punchout_workcode= '('.$punchout_workcodes['incident_name'].')';
                            //    }else{
                            //     $punchout_workcode ='';
                            //    }
                             if(!empty($punch_detail['punch_in'])){                 

                           echo !empty($punch_detail['punch_in'])?'<i class="fa fa-arrow-right text-success"></i> &nbsp; '.date("g:i a", strtotime($punch_detail['punch_in'])).' &nbsp;|&nbsp ':''; ?><?php echo !empty($punch_detail['punch_out'])?'<i class="fa fa-arrow-left text-danger"></i> &nbsp;  '.date("g:i a", strtotime($punch_detail['punch_out'])):''; ?>  <br>
                         <?php
                            }
                        }?>            
            </td>
                      
                     
                      <?php

                      // if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      if(empty($user_schedule))
                      {
                        if(empty($day['punch_in']))
                        {
                           ?>
                         <td style="vertical-align: middle !important;text-align: center;">
                          <span class="label " style="color:  #ff1a75;font-size: 14px; font-weight: bold;"><?php echo lang('week_off');?></span>
                      </td>    
                      
                          
                       <?php }?>
                        
                     <?php  }
                      else
                      {
                        
                        if(!empty($day['punch_in']))
                        {
                          
                           $later_entry_minutes = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$day['punch_in']);


                           if($later_entry_minutes > 0){?>
                             <td style="vertical-align: middle !important;text-align: center;">
                              <span class="label" style="color: rgb(241, 180, 76);font-size: 14px; font-weight: bold;"><?php echo lang('delay');?></span>
                          </td>
                          <?php  } else{?>
                            <td style="vertical-align: middle !important;text-align: center;">
                              <span class="label" style="color: #1eb53a; font-size: 14px; font-weight: bold;"><?php echo lang('present');?></span>
                            </td>
                          <?php }
                           ?>
                           
                       <?php } else {?>
                         <td style="vertical-align: middle !important;text-align: center;">
                          <span class="label " style="color: rgb(61, 142, 248); font-size: 14px; font-weight: bold;"><?php echo lang('absent');?></span>
                      </td>
                       <?php }?>
                      
                      
                    </tr>
                    <?php } }  } } } } ?>


                    </tbody>
      </table>