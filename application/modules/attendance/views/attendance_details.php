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
  $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
  $this->db->select('month_days,month_days_in_out');
  $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

  //  Entity Weekend working day 

  $this->db->select("weekend_workdays")
  ->from("dgt_branches")
  ->join("dgt_account_details", "dgt_account_details.branch_id = dgt_branches.branch_id", "inner")
  ->where("dgt_account_details.user_id", $user_id);
  $entity_weekend_working_days = $this->db->get()->row_array();


  $saturday_weekend_working = array();
  $sunday_weekend_working = array();

  if(!empty($entity_weekend_working_days))
  {
    $tmp_array =array(); 
    $tmp_array = json_decode($entity_weekend_working_days['weekend_workdays']);
    
    foreach($tmp_array as $key => $value) 
    {

      if($value <= 5)
      {
       array_push($saturday_weekend_working, $value);
      }
      if($value >=6 )
      {
        array_push($sunday_weekend_working, $value);
      }
    }
  }

  $punchin_id = 1;
  if(!empty($record['month_days'])){


    $month_days =  unserialize($record['month_days']);
    $month_days_in_out =  unserialize($record['month_days_in_out']);

    $a_day -=1;

    if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

      $day = $month_days[$a_day];
      $day_in_out = $month_days_in_out[$a_day];


      $latest_inout = end($day_in_out);


      if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
        $punch_in = $day['punch_in'];
        $punch_in_out = $latest_inout['punch_in'];
        $punch_out_in = $latest_inout['punch_out'];
        $punchin_id = 1;
      }else{
       $punch_in = $day['punch_in'];
       $punch_in_out = $latest_inout['punch_in'];
       $punch_out_in = $latest_inout['punch_out'];
       $punchin_id = 0;
     }
   }




   $punchin_time = date("g:i a", strtotime($day['punch_in']));
   $punchout_time = date("g:i a", strtotime($day['punch_out']));
 }

 ?>


 <?php
 $a_dayss -=1;
 $production_hour=0;
 $break_hour=0;

 if(!empty($record['month_days_in_out'])){

   $month_days_in_outss =  unserialize($record['month_days_in_out']);


   foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
   {

    if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
    {

      $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
    }



  }

  for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

    if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
    {

      $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
    }


  }
}
?>

<div class="content container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h4 class="page-title">Attendance - <i><?=ucfirst(User::displayName($user_id))?></i></h4>
    </div>
  </div>
  <div class="row">

    <div class="col-md-6">
      <div class="card att-statistics">
        <div class="card-body">
          <h5 class="card-title">Statistics</h5>
          <div class="stats-list" style="height:auto"> 
            <div class="stats-info">

              <?php
              $maxTime = (8*3600);

              $today_percentage = (($production_hour*60) / $maxTime) * 100;

              ?>

              <p>Today <strong><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> <small>/ 8 hrs</small></strong></p>
              <div class="progress">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $today_percentage;?>%" aria-valuenow="<?php echo $today_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>



            <?php
            $week_production_hour=0;
            $month_production_hour=0;

            if(!empty($record['month_days_in_out'])){

             $month_days_in_out_week =  unserialize($record['month_days_in_out']);

             $week_start_date = date("d",strtotime('monday this week'));
             $week_end_date=date("d",strtotime("friday this week"));

             for ($week=$week_start_date-1; $week <= $week_end_date-1 ; $week++) { 

              foreach ($month_days_in_out_week[$week] as $punch_detail_week) 
              {

                if(!empty($punch_detail_week['punch_in']) && !empty($punch_detail_week['punch_out']))
                {

                  $week_production_hour += time_difference(date('H:i',strtotime($punch_detail_week['punch_in'])),date('H:i',strtotime($punch_detail_week['punch_out'])));


                }
              }

            }

          }


          $week_maxTime = (40*3600);
          $week_percentage = (($week_production_hour*60) / $week_maxTime) * 100;

          $working_hours=working_days(date('n'), date('Y'))*8;



          if(!empty($record['month_days_in_out'])){

           $month_days_in_out_month =  unserialize($record['month_days_in_out']);

           $month_start_date = date('01', strtotime(date('Y-m-d')));
           $month_end_date=date('t', strtotime(date('Y-m-d')));

           for ($month=$month_start_date-1; $month <= $month_end_date-1 ; $month++) { 

            foreach ($month_days_in_out_month[$month] as $punch_detail_month) 
            {

              if(!empty($punch_detail_month['punch_in']) && !empty($punch_detail_month['punch_out']))
              {

                $month_production_hour += time_difference(date('H:i',strtotime($punch_detail_month['punch_in'])),date('H:i',strtotime($punch_detail_month['punch_out'])));


              }
            }

          }

        }


        $month_maxTime = ($working_hours*3600);
        $month_percentage = (($month_production_hour*60) / $month_maxTime) * 100;


        $remaining_hour=($working_hours*60)-$month_production_hour;



        $month_overtimes=($month_production_hour)-($working_hours*60);
        if($month_overtimes > 0)
        {
          $month_overtime=$month_overtimes;
        }
        else
        {
          $month_overtime=0;
        }

        $overtime_percentage = (($month_overtime*60) / $month_maxTime) * 100;




        ?>
        <div class="stats-info">
          <p>This Week <strong><?php echo intdiv($week_production_hour, 60).'.'. ($week_production_hour % 60);?> <small>/ 40 hrs</small></strong></p>
          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $week_percentage;?>%" aria-valuenow="<?php echo $week_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div class="stats-info">
          <p>This Month <strong><?php echo intdiv($month_production_hour, 60).'.'. ($month_production_hour % 60);?> <small>/ <?php echo $working_hours;?> hrs</small></strong></p>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $month_percentage;?>%" aria-valuenow="<?php echo $month_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
                    <?php /*<div class="stats-info">
                      <p>Remaining Hours <strong><?php echo intdiv($remaining_hour, 60).'.'. ($remaining_hour % 60);?> <small>/ <?php echo $working_hours;?> hrs</small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $month_percentage;?>%" aria-valuenow="<?php echo $month_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>Overtime <strong><?php echo intdiv($month_overtime, 60).'.'. ($month_overtime % 60);?> hrs</strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $overtime_percentage;?>%" aria-valuenow="<?php echo $overtime_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      </div>*/?>

                    </div>
                  </div>
                </div>
              </div>

            </div>

            <?php 
            $s_year = '2015';
            $select_y = date('Y');

            $s_month = date('m');
            $e_year = date('Y');



            ?>

            <!-- Search Filter -->
            <div class="row filter-row">
              <form method="post" action="">
                <div class="col-sm-3 col-xs-6">  
                  <div class="form-group form-focus">
                    <select class="select floating form-control" id="attendance_month" name="attendance_month">  
                      <option value="" selected="selected" disabled>Select Month</option>
                      <?php 
                      for ($ji=1; $ji <=12 ; $ji++) {  
                        $sele1='';


                        if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                        {
                          if($_POST['attendance_month']==$ji)
                          {
                            $sele1='selected';
                          }

                        }



                        ?>
                        <option value="<?php echo $ji; ?>" <?php echo $sele1;?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>    
                      <?php } ?>

                    </select>
                  </div>
                </div>

                <div class="col-sm-3 col-xs-6">  
                  <div class="form-group form-focus">
                    <select class="select floating form-control" id="attendance_year" name="attendance_year"> 
                      <option value="" selected="selected" disabled>Select Year</option>
                      <?php for($k =$e_year;$k>=$s_year;$k--){ 
                        $sele2='';
                        if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                        {
                          if($_POST['attendance_year']==$k)
                          {
                            $sele2='selected';
                          }
                        }

                        ?>
                        <option value="<?php echo $k; ?>" <?php echo $sele2;?> ><?php echo $k; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>


                <div class="col-sm-3 col-xs-6">  
                 <button type="submit" class="btn btn-success btn-block">Search</button> 
               </div>     
             </div>
             <!-- /Search Filter -->

             <div class="row">
              <div class="col-lg-12">
                <div class="table-responsive">
                  <table class="table table-striped custom-table mb-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Date </th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Production</th>
                        <th>Break</th>
                        <th>Overtime</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php

                      if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                      {
                        $a_month=$_POST['attendance_month'];
                      }

                      if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                      {
                        $a_year=$_POST['attendance_year'];
                      }





                      $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                      $this->db->select('month_days,month_days_in_out');
                      $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();

                      $sno=1;
                      foreach ($results as $rows) {

                        $list=array();


                        $month = $a_month;
                        $year = $a_year;

                        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);


                        for($d=1; $d<=$number; $d++)
                        {
                          $time=mktime(12, 0, 0, $month, $d, $year);  

                          if (date('m', $time)==$month)       
                            $date=date('d M Y', $time);

                          $a_day =date('d', $time);

                          if(!empty($rows['month_days'])){
                            $month_days =  unserialize($rows['month_days']);
                            $month_days_in_out =  unserialize($rows['month_days_in_out']);
                            $day = $month_days[$a_day-1];
                            $day_in_out = $month_days_in_out[$a_day-1];
                            $latest_inout = end($day_in_out);

                            $production_hour=0;
                            $break_hour=0;

                            foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                            {

                              if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                              {

                                $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                              }
                            }
                            

                            for ($i=0; $i <count($month_days_in_out[$a_day-1]) ; $i++) { 

                             


                              if(!empty($month_days_in_out[$a_day-1][$i]['punch_out']) && $month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])
                              {

                                $break_hour += time_difference(date('H:i',strtotime($month_days_in_out[$a_day-1][$i]['punch_out'])),date('H:i',strtotime($month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])));
                              }


                            }

                            $overtimes=($production_hour+$break_hour)-(9*60);
                            if($overtimes > 0)
                            {
                              $overtime=$overtimes;
                            }
                            else
                            {
                              $overtime=0;
                            }
                            ?>

                            <tr>
                              <td><?php echo $sno++;?></td>
                              <td><?php echo $date;?></td>
                              <?php
                              if (date('D', $time) == 'Sat' || date('D', $time) == 'Sun') {

                              $week_number = 0;
                              $dayOfMonth = date('j', $time);
                              $week_number = ceil($dayOfMonth / 7);

                              if (!empty($day['punch_in'])) {
                                  ?>
                                  <td><?php echo !empty($day['punch_in']) ? date("g:i a", strtotime($day['punch_in'])) : '-'; ?></td>
                                  <td><?php echo !empty($latest_inout['punch_out']) ? date("g:i a", strtotime($latest_inout['punch_out'])) : '-'; ?></td>
                                  <td><?php echo !empty($production_hour) ? intdiv($production_hour, 60) . '.' . ($production_hour % 60) . ' hrs' : '-'; ?></td>
                                  <td><?php echo !empty($break_hour) ? intdiv($break_hour, 60) . '.' . ($break_hour % 60) . ' hrs' : '-'; ?></td>
                                  <td><?php echo !empty($overtime) ? intdiv($overtime, 60) . '.' . ($overtime % 60) . ' hrs' : '-'; ?></td>
                                  <?php   
                              } else {

                                  $weekend_combinations = [];
                                  for ($i = 1; $i <= 5; $i++) {
                                      $weekend_combinations[$i] = ['Sat' => $saturday_weekend_working[$i-1], 'Sun' => $sunday_weekend_working[$i-1]];
                                  }

                                  if (in_array($week_number, array_keys($weekend_combinations))) {
                                      $weekend_work = $weekend_combinations[$week_number];

                                      if (isset($weekend_work[date('D', $time)]) && in_array($weekend_work[date('D', $time)], $saturday_weekend_working)) {
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                      } elseif (isset($weekend_work[date('D', $time)]) && in_array($weekend_work[date('D', $time)], $sunday_weekend_working)) {
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                          echo '<td> - </td>';
                                      } else {
                                          echo '<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off </td>';
                                      }
                                  }
                              }
                          }
                             else
                             {
                              ?>

                              <td><?php echo !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';?></td>
                              <td><?php echo !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';?></td>
                              <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?> </td>
                              <td><?php echo !empty($break_hour)?intdiv($break_hour, 60).'.'. ($break_hour % 60).' hrs':'-';?></td>
                              <td><?php echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';?></td>

                              <?php  
                            }
                            ?>
                          </tr>
                        <?php } } } ?>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        <!-- /Page Content -->