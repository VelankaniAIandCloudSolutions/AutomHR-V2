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

    $user_id = array();
        if(!empty($_POST['range']))
                { 
                    
                 
                  
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
                     /* $this->db->where('a_month >=', $start_month);
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
                    $where     = array('ad.subdomain_id'=>$subdomain_id,'a_month'=>$a_month,'a_year'=>$a_year);
                    if($branch_id != '') {
                        $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                    }
                    $record = $this->db->select('ad.*')
                                ->from('attendance_details ad')
                                ->join('users u', 'u.id=ad.user_id')
                                ->where($where)
                                ->get()
                                ->result_array();
                    //$record  = $this->db->get_where('dgt_attendance_details'$where)->result_array();             
                  if(!empty($record)){
                    foreach ($record as $key => $value) {
                    $user_id[] = $value['user_id'];
                  }
                }
            }
            $users_id =  array_unique($user_id);
?>          
            <table style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%; padding: 8px;">

              <tr style="background-color:#c6e0b3">                
                <td ><?php echo lang('date_of_impresion');?></td>
                <td colspan="2" style="text-align: left;"><?php echo date('Y-m-d-H:i:s');?></td>
                
              </tr>
              <tr style="background-color:#c6e0b3">
                
                <td ><?php echo lang('since_date');?></td>
                <td colspan="2" style="text-align: left;"><?php echo (isset($from_date) && !empty($from_date))?$from_date.'-00:00:00':"-"?></td>
              </tr>
              <tr style="background-color:#c6e0b3">               
                <td ><?php echo lang('to_date');?></td>
                <td colspan="2" style="text-align: left;"><?php echo (isset($to_date) && !empty($to_date))?$to_date.'-23:59:59':"-"?></td>
                
              </tr>
            </table>
            <table id="table-late_arrival_report" class="" style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%;border: 1px solid #3a3a3a; padding: 8px;">
           <!--  <thead>
              
            </thead> -->
            <tbody>
              <tr class="" style="vertical-align: middle !important;background-color:#24b23c">
                <th><?=lang('employee')?></th>
                <th ><?=lang('late_arrivals')?></th>
                <th><?=lang('accumulated_time')?></th>
              
              </tr>   
            <?php 
        

            // echo "<pre>";   print_r($user_id);             
            $later_entry_hours = 0;
            $user_absent = array();
            $user_time = array();
 
            foreach ($users_id as $key => $value) {
              if($value !=1){                    
                $user_id = $value;
                $user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
                $account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
                          
                if(isset($_POST['range']) && !empty($_POST['range'])){                            
                  $month_start = $start_month ;
                  $month_end =  $end_month;
                  $a_year = $start_year;

                }else{
                  $month_start = 1;
                  $month_end =date('m');
                  $a_year    = date('Y');
                }
                for ($m=$month_start; $m <=$month_end ; $m++) {
                  if(isset($_POST['range']) && !empty($_POST['range'])){
                    $number = $col_count;
                      
                  }else{
                    $number = cal_days_in_month(CAL_GREGORIAN, $m, $a_year);
                  }

                  /*$this->db->select('month_days,month_days_in_out');
                  $this->db->where('user_id', $user_id);
                  $this->db->where('a_month ', $m);
                  // $this->db->where('a_month <=', $end_month);
                  // $this->db->where('a_year >=', $start_year);
                  $this->db->where('a_year ', $a_year);
                  $rows =  $this->db->get('attendance_details')->row_array(); */

                  if($branch_id != '') {
                        $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
                    }
                    $rows = $this->db->select('month_days,month_days_in_out')
                                ->from('attendance_details ad')
                                ->join('users u', 'u.id=ad.user_id')
                                ->where(array('user_id'=>$user_id, 'a_month '=>$m, 'a_year '=>$a_year,'ad.subdomain_id'=> $subdomain_id))
                                ->get()
                                ->row_array();

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
                   
                    $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                    $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                    // echo $this->db->last_query();
                    
                    if(!empty($rows['month_days'])){

                      $month_days =  unserialize($rows['month_days']);
                      $month_days_in_out =  unserialize($rows['month_days_in_out']);
                      $day = $month_days[$a_day-1];
                      $day_in_out = $month_days_in_out[$a_day-1];
                      $latest_inout = end($day_in_out);                             
                       
                      if(!empty($user_schedule)){
                       
                        if(!empty($day['punch_in'])){    
                                            
                          $later_entry_hours = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$user_schedule['schedule_date'].' '.$day['punch_in']);
                          if($later_entry_hours > 0){
                            $user_absent[$user_id][] = $user_id;
                            $user_time[$user_id][] = $later_entry_hours ; 
                          }
                        
                        } 
                         
                      } 
                    }  
                  } 
                }
                if(isset($user_absent[$user_id])){
              $user_details= $this->db->get_where('users',array('id'=>$user_id))->row_array();
              $account_details= $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();                    
              if(!empty($user_details['designation_id'])){
                $designation = $this->db->get_where('designation',array('id'=>$user_details['designation_id']))->row_array();
                $designation_name = $designation['designation'];
                
              }else{
                $designation_name = '-';
              }?>
               <tr class="attendance_record" style="vertical-align: middle !important;">
                <td style="vertical-align: middle !important;text-align: center;" >                           
                  <b><?php echo ucfirst(user::displayName($user_details['id']));?></b><br>   
                  <b> <?php echo $designation_name;?> <?php echo !empty($user_details['id_code'])?' - '.$user_details['id_code']:"";?></b><br>
                </td>
                <td style="vertical-align: middle !important;text-align: center;" ><?php echo count($user_absent[$user_id]); ?></td>
                <td style="vertical-align: middle !important;text-align: center;" ><?php echo !empty(array_sum($user_time[$user_id]))?intdiv(array_sum($user_time[$user_id]), 60).'.'. (array_sum($user_time[$user_id]) % 60):''
                ?></td>
              </tr>


              <?php  
            }
              // echo'<pre>';print_r($user_absent);   exit;           
               }
            }
          // echo'<pre>';print_r($user_absent);
         ?>

                    </tbody>
      </table>