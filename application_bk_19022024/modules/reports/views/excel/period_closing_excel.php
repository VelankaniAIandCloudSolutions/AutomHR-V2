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
      <table style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%; padding: 8px;">

        <tr style="background-color:#c6e0b3" >
          <td><?php echo lang('employees');?></td>
          <td><?php echo (isset($_POST['employee_name']) && !empty($_POST['employee_name']))?ucfirst($_POST['employee_name']):"all"?></td>
          <td><?php echo lang('date_of_impresion');?></td>
          <td style="text-align: left;" colspan ="<?php echo $last_day-2;?>"><?php echo date('Y-m-d-H:i:s');?></td>
          
        </tr>
        <tr style="background-color:#c6e0b3" colspan ="<?php echo $last_day;?> ">
          <td><?php echo lang('department');?></td>
          <td><?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?ucfirst(user::GetDepartmentNameById($_POST['department_id'])):"All"?></td>
          <td><?php echo lang('year_and_month');?></td>
          <td style="text-align: left;" colspan ="<?php echo $last_day-2;?>"><?php $month_and_year = $_POST['attendance_year'].'-'.$_POST['attendance_month'].'-01'; echo (isset($attendance_month) && !empty($attendance_month))?date('Y-M',strtotime($month_and_year)) :"-"?></td>
        </tr>
        <!-- <tr style="background-color:#c6e0b3">
          <td><?php echo lang('position');?></td>
          <td><?php echo (isset($designation_name))?$designation_name:"All"?></td>
          <td><?php echo lang('to_date');?></td>
          <td style="text-align: left;"><?php echo (isset($to_date) && !empty($to_date))?$to_date.'-23:59:59':"-"?></td>
          
        </tr> -->
      </table>

  <?php $attendance_footer = '';
       $attendance_body = '';
       $attendance_head = '';

        // datas = JSON.parse(datas);
        $last_day = $last_day;
        $current_page = $current_page;
        $total_page = $total_page;
        $attendance_list = $attendance_list;
        $recordscount = count($attendance_list);
        $attendance_head = '<table id="excel_table" class="" style="vertical-align: middle !important;text-align: center; border-collapse: collapse;width: 100%;border: 1px solid #3a3a3a; padding: 8px;">
            <tbody>
              <tr class="" style="vertical-align: middle !important;background-color:#24b23c"><th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">'.lang("team_member").'</th>';
        for ($ik = 1; $ik <= $last_day; $ik++) {
           $date = $attendance_year.'-'.$attendance_month.'-'.$ik;
            $attendance_head .= '<th align="left" valign="middle" style="font-weight:700;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">' . date('M',strtotime($date)).' '.$ik  . '</th>';
        }
        $attendance_head .= '</tr></thead>';
        $attendance_body .= '<tbody>';
        $overtimes  = 0;
        $production_hour_achived  = 0;
        $later_entry_hours  =  0;
        $missing_work  =  0;
        $total_scheduled_minutes  =  0;
        $user_id = '';
        if ($recordscount > 0) {
            for ($i = 0; $i < $recordscount; $i++) {

               $record = $attendance_list[$i];

                $name = $record->fullname;
                $attendance = $record->attendance;
               $shift_details;

               $user = $this->db->get_where('users',array('id'=>$record->user_id))->row_array();
            
              if(!empty($user['designation_id'])){
                $designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
                $designation_name = $designation['designation'];
                
              }else{
                $designation_name = '-';
              }
            $imgs = '';
              if($record->avatar != 'default_avatar.jpg'){
                  $imgs = $record->avatar;
                  
              }else{
                  $imgs = "default_avatar.jpg";
              }
            $id_code = ($user['id_code'] !=0)?$user['id_code']:"-";
            $tr_color = ($i % 2 == 0)?'#fff':'#eee';

                $attendance_body .= ' <tr class="attendance_record" style="vertical-align: middle !important;">
                            <td style="vertical-align: middle !important;text-align: center;">                           
                              <b>'.ucfirst(user::displayName($record->user_id)).'</b><br>   
                              <b>'.$designation_name.' '.$id_code.'</b>
                            </td>';



               // console.log(attendance);

              $overtimes  = 0;
          $production_hour_achived  = 0;
          $later_entry_hours  =  0;
          $missing_work  =  0;
          $total_scheduled_minutes  =  0;
          $user_id = $record->user_id;
               $j=1;
                // $.each(attendance, function (key, rec) {
                  foreach ($attendance as $key => $rec) {
                    # code...
                  // }
                    // console.log(parseInt(key) + 1);
                $user_html = "";
                    //if(record.user_id == 320){
                       
                        $status = $rec['day'];
                        $punchin = $rec['punch_in'];
                        $punch_out = $rec['punch_out'];
                        // $user_id = $record->user_id;
                        $day = $key + 1;
                    //     attendance_day: parseInt(key) + 1,
                    //     attendance_month: attendance_month,
                    //     attendance_year: attendance_year,
                    //     punch_in: rec.punch_in,
                    //     punch_out: rec.punch_out

      
        $day = $key + 1;
        $month = $attendance_month;
        $year  = $attendance_year;
        $punch_in  = $rec['punch_in'];
        $punch_out  = $rec['punch_out'];
        $punch_in_manually_made  = isset($rec['punch_in_manually_made'])?$rec['punch_in_manually_made']:'';
        $punch_out_manually_made  = isset($rec['punch_out_manually_made'])?$rec['punch_out_manually_made']:'';
        $schedule_date = $year.'-'.$month.'-'.$day;
        // if($user_id == 3502 && $day == 01){
        $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
        $all_user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->result_array(); 
        //   print_r(count($all_user_schedule)); exit;
        
        if(count($all_user_schedule) == 1){
          $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
          $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
         
          if(!empty($user_schedule)){
              $total_scheduled_hour = hours_to_mins($user_schedule['work_hours']);

              $total_scheduled_minutes = $total_scheduled_hour;                                     
              
          } else{
            $total_scheduled_minutes = 0;
          }
          // production_hour
          if(!empty($punch_in) && !empty($punch_out)){
            $production_hour = time_difference(date('H:i',strtotime($punch_in)),date('H:i',strtotime($punch_out)));
          }
                
            // overtimes                    
          if($user_schedule['accept_extras'] == 1){
            $overtimes=($production_hour)-($total_scheduled_minutes);
            if($overtimes > 0)
            {
              $overtimes=$overtimes;
              if($production_hour >= $total_scheduled_minutes){
                  $production_hour_achived=  $production_hour;
              }else{
                  $production_hour_achived=  0;
              }
            }
            else
            {
              if($production_hour >= $total_scheduled_minutes){
                  $production_hour_achived=  $production_hour;
              }else{
                  $production_hour_achived=  0;
              }
              $overtimes=0;
            }
          } else{

            if($production_hour >= $total_scheduled_minutes){
              $production_hour_achived=  $production_hour;
            }else{
              $production_hour_achived=  0;
            }
            $overtimes=0;
          }

          // later_entry_hours

          if(!empty($punch_in))
          {
            if($user_schedule['free_shift'] != 1){
             $later_entry_hours = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$punch_in);
            }else{
              $later_entry_hours = 0;
            }
          } else {
            $later_entry_hours = 0;
          }

          // Missing worke

          $missing_work=($total_scheduled_minutes)-($production_hour);
                              // echo $missing_work; exit;
          if($missing_work > 0)
          {
            $missing_work=$missing_work;
           
          }
          else
          {
            $missing_work=0;
            
          }


          $overtimes  =  $overtimes;
          $production_hour_achived  =  $production_hour_achived;
          $later_entry_hours  =  $later_entry_hours;
          $missing_work  =  $missing_work;
        }else{

          foreach ($all_user_schedule as $value) {
            $work_hours = hours_to_mins($value['work_hours']);
            $total_scheduled_minutes += $work_hours;
            # code...
          }
          
          $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year);
          $this->db->select('month_days,month_days_in_out');
          $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
          $a_dayss =$key;
          $production_hour=0;
          if(!empty($record['month_days_in_out'])){

         $month_days_in_outss =  unserialize($record['month_days_in_out']);
  // echo "<pre>";print_r($a_dayss); exit;
            $j=1;                  
          foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
          {

              if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
              {
                $day = $a_dayss+1;                
                $schedule_date = date('Y-m-'.$day);
                $today_work_hour = $this->db->query('select * from dgt_shift_scheduling where employee_id ="'.$user_id.'" and schedule_date ="'.$schedule_date.'" AND ((start_time <= "'.$punch_detailss['punch_in'].'" and end_time >="'.$punch_detailss['punch_in'].'") or (start_time >= "'.$punch_detailss['punch_in'].'")) limit 1')->row_array();
                   // $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$day));
                   //  $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                      // echo $day.'' .print_r($today_work_hour); exit;
                 if(!empty($today_work_hour)){
                    if($today_work_hour['free_shift'] == 1 ){
                        
                       $later_entry_hours = 0;                      
                       
                    }else{
                      $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_in']);   

                      $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_in']);     
                       // echo $days; exit;
                      $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                      $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 
                  
                      if($punch_detailss['punch_out'] > $today_work_hour['max_end_time']){
                          $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                      }else{
                          $between_endto_max_end = 0;
                      }    
                    }
                  }
                    // }                    
                   $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out']))); 
                   // echo $production_hour; exit;                    
              }
              $j++;
            }
                  // overtimes     
                  // echo $total_scheduled_minutes.' production_hour';               
                  // echo $production_hour.'later_entry_hours';            
                  // echo $later_entry_hours;exit;               
            if($user_schedule['accept_extras'] == 1){
              $overtimes=($production_hour)-($total_scheduled_minutes);
              if($overtimes > 0)
              {
                $overtimes=$overtimes;
                if($production_hour >= $total_scheduled_minutes){
                    $production_hour_achived=  $production_hour;
                }else{
                    $production_hour_achived=  0;
                }
              }
              else
              {
                if($production_hour >= $total_scheduled_minutes){
                    $production_hour_achived=  $production_hour;
                }else{
                    $production_hour_achived=  0;
                }
                $overtimes=0;
              }
            } else{

              if($production_hour >= $total_scheduled_minutes){
                $production_hour_achived=  $production_hour;
              }else{
                $production_hour_achived=  0;
              }
              $overtimes=0;
            }
            $missing_work=($total_scheduled_minutes)-($production_hour);
                              // echo $missing_work; exit;
            if($missing_work > 0)
            {
              $missing_work=$missing_work;
             
            }
            else
            {
              $missing_work=0;
              
            }

          }
        }
                    // $.post(base_url + 'attendance/employee_shift_per_day', {
                        
                    //     user_id: user_id,
                    //     attendance_day: parseInt(key) + 1,
                    //     attendance_month: attendance_month,
                    //     attendance_year: attendance_year,
                    //     punch_in: rec.punch_in,
                    //     punch_out: rec.punch_out
                    // }, function (shift_details) {
                        // shift_details = JSON.parse(shift_details);
                        //  $('#overtimes').val(shift_details.overtimes);
                        //  $('#production_hour_achived').val(shift_details.production_hour_achived);
                        //  $('#later_entry_hours').val(shift_details.later_entry_hours);
                        //  $('#missing_work').val(shift_details.missing_work);
                        //  var overtimes = shift_details.overtimes;
                        //  var production_hour_achived = shift_details.production_hour_achived;
                        //  var later_entry_hours = shift_details.later_entry_hours;
                        //  var missing_work = shift_details.missing_work;
                   
                    // console.log(punch_out);
                    // if($punch_out != ''){
                    //     $start = moment.duration(punchin, "HH:mm");
                    //     $end = moment.duration(punch_out, "HH:mm");
                    //     $diff = end.subtract(start);
                    //     $hr =  diff.hours(); // return hours
                    //     $mins = diff.minutes(); // return minutes   
                    // }
                  if(isset($manully_made) && !empty($manully_made)){
                    $attendance_body .= '<td >';
                    if($manully_made == $punch_in_manually_made || $manully_made == $punch_out_manually_made){
                      
                    if ($status == '0') {
                        if($punchin == '' && $punch_out == ''){
                            $attendance_body .= '-';
                        }
                    } else if ($status == '1') {
                        if(($punchin != ''  && $punch_out != '')){
                            if($overtimes!= 0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" data-toggle="tooltip" title="Extra Time Worked"><i class="fa fa-check text-warning"></i></a>';
                            }
                            if($production_hour_achived !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-success" data-toggle="tooltip" title="Workday Complete"></i></a>';
                            }if($later_entry_hours !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-yellow1" data-toggle="tooltip" title="Late Arrival"></i></a>';
                            }
                            if($missing_work !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-close text-danger" data-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
                            }
                            // user_html += '<a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a>';
                            
                        }else{
                            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-close text-danger" data-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
                        }
                    } else if ($status == '2') {
                        $attendance_body .= '<i class="text-success" data-toggle="tooltip" title="Worked Hours"></i>';
                    } else if ($status == '0') {
                        $attendance_body .= '<i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" title="No Record for Check in"></i>';
                    } else if ($status == '') {
                        $attendance_body .= '-';
                    }
                    
                    }else{
                      $attendance_body .= '-';
                    }
                    $attendance_body .= '</td>';
                  } else {
                    $attendance_body .= '<td style="vertical-align: middle !important;text-align: center;">';
                    if ($status == '0') {
                        if($punchin == '' && $punch_out == ''){
                            $attendance_body .= '-';
                        }
                    } else if ($status == '1') {
                        if(($punchin != ''  && $punch_out != '')){
                            if($overtimes!= 0){
                                $attendance_body .= '<span class="text-warning" style="color:#ffbc34;font-size: 14px; font-weight: bold;">Extra Time Worked</span><br>';
                            }
                            if($production_hour_achived !=0){
                                $attendance_body .= '<span class="text-success" style="color:#55ce63;font-size: 14px; font-weight: bold;">Workday Completed</span><br>';
                            }if($later_entry_hours !=0){
                                $attendance_body .= '<span class="text-yellow1" style="color:#ffeb3b; font-size: 14px; font-weight: bold;">Late Arrival</span><br>';
                            }
                            if($missing_work !=0){
                                $attendance_body .= '<span class="text-danger" style="color:#f62d51; font-size: 14px; font-weight: bold;">Incomplete Workday Time</span><br>';
                            }
                            // user_html += '<a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a>';
                            
                        }else{
                            $attendance_body .= '<span class="text-danger" style="color:#f62d51; font-size: 14px; font-weight: bold;">Incomplete Workday Time</span><br>';
                        }
                    } else if ($status == '2') {
                        $attendance_body .= '<span class="text-success" style="color:#55ce63; font-size: 14px; font-weight: bold;">Worked Hours</span><br>';
                    } else if ($status == '0') {
                        $attendance_body .= '<span class="text-danger" style="color:#f62d51; font-size: 14px; font-weight: bold;">No Record for Check in</span><br>';
                    } else if ($status == '') {
                        $attendance_body .= '-';
                    }
                    $attendance_body .= '</td>';
                  }
                    
                    // $("#user_"+user_id + " td:last").after(user_html);
                    // });
                    
                    ++$j;
               // }
                
                }
                $attendance_body .= '</tr>';
            }
        } else {
            $attendance_body .= '<tr><td></td></tr>';
        }
        $attendance_body .= '</tbody>';

        $attendance_body .= '</table></div>';

        // $total_page = parseInt($total_page);

        // if ($total_page > 1) {

        //     $attendance_footer = '<div class="row"><div class="col-sm-12">' .
        //         '' .
        //         '<div class="dataTables_paginate paging_simple_numbers" id="table-projects_paginate">' .
        //         '<ul class="pagination m-r-15">';

        //     // $total_page = parseInt($total_page);

        //     for ($k = 1; $k <= $total_page; $k++) {
        //         if ($current_page == k) {
        //             $classpage = 'active';
        //         } else { $classpage = ''; }
        //         $attendance_footer .= '<li class="paginate_button ' . $classpage . '"><a href="javascript:void(0)" onclick="attendance_next_filter_page(' . $k . ')">' . $k . '</a></li>';
        //     }
        //     $attendance_footer .= '</ul></div></div></div>';
        // }

        // $attendance_footer .= '<div class="row"><div class="col-md-12"><div class="pagination"></div></div></div>';
        $attendance_html = $attendance_head .''. $attendance_body;
        echo $attendance_html ; ?>
  
       
</div>