<?php

date_default_timezone_set('Asia/Kolkata');
$punch_in_date = date('Y-m-d');
$punch_in_time = date('H:i');
$punch_in_date_time = date('Y-m-d H:i');


$strtotime = strtotime($punch_in_date_time);
$a_year    = date('Y', $strtotime);
$a_month   = date('m', $strtotime);
$a_day     = date('d', $strtotime);
$a_days     = date('d', $strtotime);
$a_dayss     = date('d', $strtotime);
$a_cin     = date('H:i', $strtotime);
$current_time = date('H:i');
$cur_date = date('Y-m-d');
$new_date_time = $cur_date . ' ' . $shift_record['end_time'];
$new_date_time = date('Y-m-d H:i:s', strtotime($new_date_time));
$new_time = date("H:i", strtotime($new_date_time . '+8 hours'));


$where     = array('user_id' => $user_id);
$account_record  = $this->db->get_where('dgt_account_details', $where)->row_array();


$where     = array('employee_id' => $user_id, 'schedule_date<=' => $cur_date, 'end_date>=' => $cur_date);
$shift_record  = $this->db->get_where('dgt_shift_scheduling', $where)->row_array();
$check_cur_day1 = $a_day;
if (strtotime($current_time) < strtotime($shift_record['start_time']) && strtotime($current_time) <= strtotime($new_time)) {
  $check_cur_day1 = (int)$a_day - 2;
}
if ($check_cur_day1 == -1) {
  $req_day = date('Y-m-d', strtotime(' -1 day'));
  $a_year = date('Y', strtotime($req_day));
  $a_month = date('m', strtotime($req_day));
}
$where     = array('user_id' => $user_id, 'a_month' => $a_month, 'a_year' => $a_year);
$this->db->select('month_days,month_days_in_out');
$record  = $this->db->get_where('dgt_attendance_details', $where)->row_array();

$punchin_id = 1;
if (!empty($record['month_days'])) {


  $month_days =  unserialize($record['month_days']);
  $month_days_in_out =  unserialize($record['month_days_in_out']);

  $a_day -= 1;
  $where     = array('user_id' => $user_id);
  $account_record  = $this->db->get_where('dgt_account_details', $where)->row_array();

  $cur_date = date('Y-m-d');
  $where     = array('employee_id' => $user_id, 'schedule_date<=' => $cur_date, 'end_date>=' => $cur_date);
  $shift_record  = $this->db->get_where('dgt_shift_scheduling', $where)->row_array();

  if (!empty($shift_record)) {
    $total_scheduled_hour = work_hours($shift_record['schedule_date'] . ' ' . $shift_record['start_time'], $shift_record['schedule_date'] . ' ' . $shift_record['end_time'], $shift_record['break_time']);

    $total_scheduled_minutes = $total_scheduled_hour;
  } else {
    $total_scheduled_minutes = 480;
  }
  if ($total_scheduled_minutes == 0) {
    $total_scheduled_minutes = 480;
  }

  $new_date_time = $cur_date . ' ' . $shift_record['end_time'];
  $new_date_time = date('Y-m-d H:i:s', strtotime($new_date_time));
  $new_time = date("H:i", strtotime($new_date_time . '+8 hours'));

  $current_time = date('H:i');
  if (strtotime($current_time) < strtotime($shift_record['start_time']) && strtotime($current_time) <= strtotime($new_time)) {
    $a_day -= 1;
  }
  if ($a_day == -1) {
    $req_day = date('d', strtotime(' -1 day'));
    $a_day = (int) $req_day;
    $a_day -= 1;
  }
  if (!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])) {

    $day = $month_days[$a_day];
    $day_in_out = $month_days_in_out[$a_day];
    $latest_inout = end($day_in_out);


    if ($day['day'] == '' || !empty($latest_inout['punch_out']) || empty($latest_inout['punch_in'])) {
      $punch_in = $day['punch_in'];
      $punch_in_out = $latest_inout['punch_in'];
      $punch_out_in = $latest_inout['punch_out'];
      $punchin_id = 1;
    } else {
      $punch_in = $day['punch_in'];
      $punch_in_out = $latest_inout['punch_in'];
      $punch_out_in = $latest_inout['punch_out'];
      $punchin_id = 0;
    }
  }

  if (isset($get_employee_attendance) && !empty($get_employee_attendance)){
    if ($get_employee_attendance['punch_out_date_time'] == '' && $get_employee_attendance['punch_in_date_time'] != "") {
      $punchin_id = 0;
    }

  } else {
    $punchin_id = 1;
  }

  $punchin_time = date("g:i a", strtotime($day['punch_in']));
  $punchout_time = date("g:i a", strtotime($day['punch_out']));
}

?>


<?php
$a_dayss -= 1;
$production_hour = 0;
$break_hour = 0;

$where     = array('user_id' => $user_id);
$account_record  = $this->db->get_where('dgt_account_details', $where)->row_array();
$cur_date = date('Y-m-d');
$where     = array('employee_id' => $user_id, 'schedule_date<=' => $cur_date, 'end_date>=' => $cur_date);
$shift_record  = $this->db->get_where('dgt_shift_scheduling', $where)->row_array();


$new_date_time = $cur_date . ' ' . $shift_record['end_time'];
$new_date_time = date('Y-m-d H:i:s', strtotime($new_date_time));
$new_time = date("H:i", strtotime($new_date_time . '+8 hours'));
$req_new_time = date("Y-m-d H:i", strtotime($new_date_time . '+8 hours'));
$current_time = date('H:i');
if (strtotime($current_time) < strtotime($shift_record['start_time']) && strtotime($current_time) <= strtotime($new_time)) {
  $req_new_time = date("Y-m-d H:i", strtotime($new_date_time . '-24 hours'));
  $a_dayss -= 1;
}
if ($a_dayss == -1) {
  $req_day = date('d', strtotime(' -1 day'));
  $a_dayss = (int) $req_day;
  $a_dayss -= 1;
}

if (!empty($record['month_days_in_out'])) {
  $month_days_in_outss =  unserialize($record['month_days_in_out']);

  foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) {
    if (!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out'])) {
      $production_hour += time_difference(date('H:i', strtotime($punch_detailss['punch_in'])), date('H:i', strtotime($punch_detailss['punch_out'])));
    }
  }
  for ($i = 0; $i < count($month_days_in_outss[$a_dayss]); $i++) {
    if (!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][$i + 1]['punch_in']) {
      $break_hour += time_difference(date('H:i', strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])), date('H:i', strtotime($month_days_in_outss[$a_dayss][$i + 1]['punch_in'])));
    }
  }
}
?>
<div class="content container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <h4 class="page-title">Attendance</h4>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="card punch-status">
        <div class="card-body">
          <h5 class="card-title">Timesheet <small class="text-muted"><?php echo date('d M Y', strtotime($req_new_time)); ?></small></h5>
          <?php
          if ($punchin_id == 1) { ?>
            <form action="<?php echo base_url(); ?>attendance/save_punch_details" method="POST" class="form-horizontal">
              <div class="punch-det">
                <h6>Check In </h6>
                <p><?php echo date("D M j h:i:s A"); ?></p>
              </div>
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
              <input type="hidden" name="punch_in_date_time" id="punch_in_date_time" value="<?php echo $punch_in_date_time; ?>">
              <div class="punch-info">
                <div class="punch-hours">
                  <span><?php echo intdiv($production_hour, 60) . '.' . ($production_hour % 60); ?> hrs</span>
                </div>
              </div>
              <div class="punch-btn-section">
                <button type="submit" class="btn btn-primary punch-btn">Check in</button>
              </div>
              <div class="punch-btn-section">
                <a href="<?php echo base_url(); ?>attendance/attendance_regularization" class="btn btn-success punch-btn">Attendance Regularization</a>
              </div>
            </form>
          <?php } else { ?>
            <form action="<?php echo base_url(); ?>attendance/save_punch_details_out" method="POST" class="form-horizontal">
              <div class="punch-det">
                <h6>Check In at</h6>
                <p><?php echo date('l'); ?>, <?php echo date('d M Y', strtotime($punch_in_date)); ?> <?php echo (!empty($punch_in)) ? date("g:i a", strtotime($punch_in)) : ''; ?></p>
              </div>
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
              <input type="hidden" name="punch_out_date_time" id="punch_out_date_time" value="<?php echo $punch_in_date_time; ?>">
              <div class="punch-info">
                <div class="punch-hours">
                  <span><?php echo intdiv($production_hour, 60) . '.' . ($production_hour % 60); ?> hrs</span>
                </div>
              </div>
              <div class="punch-btn-section">
                <button type="submit" class="btn btn-primary punch-btn">Check out</button>
              </div>

              <div class="punch-btn-section">
                <a href="<?php echo base_url(); ?>attendance/attendance_regularization" class="btn btn-success punch-btn">Attendance Regularization</a>
              </div>


            </form>
          <?php } ?>
          <div class="statistics">



            <div class="row">
              <div class="col-md-6 text-center">
                <div class="stats-box">
                  <p>Worked Hours</p>
                  <h6><?php echo intdiv($production_hour, 60) . '.' . ($production_hour % 60); ?> hrs</h6>
                </div>
              </div>
              <div class="col-md-6 text-center">
                <div class="stats-box">
                  <p>Break</p>
                  <h6><?php echo intdiv($break_hour, 60) . '.' . ($break_hour % 60); ?> hrs</h6>
                </div>
              </div>
              <?php /*<div class="col-md-4 text-center">
<div class="stats-box">
<p>Overtime</p>
<?php
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
<h6><?php echo intdiv($overtime, 60).'.'. ($overtime % 60);?> hrs</h6>
</div>
</div>*/ ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card att-statistics">
        <div class="card-body">
          <h5 class="card-title">Statistics</h5>
          <div class="stats-list">
            <div class="stats-info">

              <?php
              $total_scheduled_hrs = intdiv($total_scheduled_minutes, 60);
              $maxTime = ($total_scheduled_hrs * 3600);

              $today_percentage = (($production_hour * 60) / $maxTime) * 100;

              ?>

              <p>Today <strong><?php echo intdiv($production_hour, 60) . '.' . ($production_hour % 60); ?> <small>/ <?php echo $total_scheduled_hrs; ?> hrs</small></strong></p>
              <div class="progress">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $today_percentage; ?>%" aria-valuenow="<?php echo $today_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>



            <?php
            $week_production_hour = 0;
            $month_production_hour = 0;

            if (!empty($record['month_days_in_out'])) {

              $month_days_in_out_week =  unserialize($record['month_days_in_out']);

              $week_start_date = date("d", strtotime('monday this week'));
              $week_end_date = date("d", strtotime("friday this week"));

              for ($week = $week_start_date - 1; $week <= $week_end_date - 1; $week++) {

                foreach ($month_days_in_out_week[$week] as $punch_detail_week) {

                  if (!empty($punch_detail_week['punch_in']) && !empty($punch_detail_week['punch_out'])) {

                    $week_production_hour += time_difference(date('H:i', strtotime($punch_detail_week['punch_in'])), date('H:i', strtotime($punch_detail_week['punch_out'])));
                  }
                }
              }
            }


            $week_maxTime = ($total_scheduled_hrs * 5 * 3600);
            $week_percentage = (($week_production_hour * 60) / $week_maxTime) * 100;

            $working_hours = working_days(date('n'), date('Y')) * $total_scheduled_hrs;



            if (!empty($record['month_days_in_out'])) {

              $month_days_in_out_month =  unserialize($record['month_days_in_out']);

              $month_start_date = date('01', strtotime(date('Y-m-d')));
              $month_end_date = date('t', strtotime(date('Y-m-d')));

              for ($month = $month_start_date - 1; $month <= $month_end_date - 1; $month++) {

                foreach ($month_days_in_out_month[$month] as $punch_detail_month) {

                  if (!empty($punch_detail_month['punch_in']) && !empty($punch_detail_month['punch_out'])) {

                    $month_production_hour += time_difference(date('H:i', strtotime($punch_detail_month['punch_in'])), date('H:i', strtotime($punch_detail_month['punch_out'])));
                  }
                }
              }
            }


            $month_maxTime = ($working_hours * 3600);
            $month_percentage = (($month_production_hour * 60) / $month_maxTime) * 100;


            $remaining_hour = ($working_hours * 60) - $month_production_hour;



            $month_overtimes = ($month_production_hour) - ($working_hours * 60);
            if ($month_overtimes > 0) {
              $month_overtime = $month_overtimes;
            } else {
              $month_overtime = 0;
            }

            $overtime_percentage = (($month_overtime * 60) / $month_maxTime) * 100;




            ?>
            <div class="stats-info">
              <p>This Week <strong><?php echo intdiv($week_production_hour, 60) . '.' . ($week_production_hour % 60); ?> <small>/ 40 hrs</small></strong></p>
              <div class="progress">
                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $week_percentage; ?>%" aria-valuenow="<?php echo $week_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="stats-info">
              <p>This Month <strong><?php echo intdiv($month_production_hour, 60) . '.' . ($month_production_hour % 60); ?> <small>/ <?php echo $working_hours; ?> hrs</small></strong></p>
              <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $month_percentage; ?>%" aria-valuenow="<?php echo $month_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
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
</div>*/ ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card recent-activity">
        <div class="card-body">
          <h5 class="card-title">Today Activity</h5>
          <ul class="res-activity-list">

            <?php
            $a_days -= 1;
            $where     = array('user_id' => $user_id);
            $account_record  = $this->db->get_where('dgt_account_details', $where)->row_array();

            $cur_date = date('Y-m-d');
            $where     = array('employee_id' => $user_id, 'schedule_date<=' => $cur_date, 'end_date>=' => $cur_date);
            $shift_record  = $this->db->get_where('dgt_shift_scheduling', $where)->row_array();

            $new_date_time = $cur_date . ' ' . $shift_record['end_time'];
            $new_date_time = date('Y-m-d H:i:s', strtotime($new_date_time));
            $new_time = date("H:i", strtotime($new_date_time . '+8 hours'));

            $current_time = date('H:i');

            if (strtotime($current_time) < strtotime($shift_record['start_time']) && strtotime($current_time) <= strtotime($new_time)) {
              $a_days -= 1;
              if ($a_days == -1) {
                $req_day = date('d', strtotime(' -1 day'));
                $a_days = (int) $req_day;
                $a_days -= 1;
              }
            }
            if (!empty($record['month_days_in_out'])) {

              $month_days_in_outs =  unserialize($record['month_days_in_out']);
              foreach ($month_days_in_outs[$a_days] as $punch_details) {
                if (!empty($punch_details['punch_in'])) {
                  echo '<li>
                        <p class="mb-0">Check In at</p>
                        <p class="res-activity-time">
                        <i class="fa fa-clock-o"></i>
                        ' . date("g:i a", strtotime($punch_details['punch_in'])) . '
                        </p>
                        </li>';
                                        }
                                        if (!empty($punch_details['punch_out'])) {
                                          echo '<li>
                        <p class="mb-0">Check Out at</p>
                        <p class="res-activity-time">
                        <i class="fa fa-clock-o"></i>
                        ' . date("g:i a", strtotime($punch_details['punch_out'])) . '
                        </p>
                        </li>';
                }
              }
            }
            ?>
          </ul>
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
    <form method="post" action="<?php echo base_url(); ?>attendance">
      <div class="col-sm-3 col-xs-6">
        <div class="form-group form-focus">
          <select class="select floating form-control" id="attendance_month" name="attendance_month">
            <option value="" selected="selected" disabled>Select Month</option>
            <?php
            for ($ji = 1; $ji <= 12; $ji++) {
              $sele1 = '';
              if (isset($_POST['attendance_month']) && !empty($_POST['attendance_month'])) {
                if ($_POST['attendance_month'] == $ji) {
                  $sele1 = 'selected';
                }
              }
            ?>
              <option value="<?php echo $ji; ?>" <?php echo $sele1; ?>><?php echo date('F', strtotime($select_y . '-' . $ji)); ?></option>
            <?php } ?>

          </select>
        </div>
      </div>

      <div class="col-sm-3 col-xs-6">
        <div class="form-group form-focus">
          <select class="select floating form-control" id="attendance_year" name="attendance_year">
            <option value="" selected="selected" disabled>Select Year</option>
            <?php for ($k = $e_year; $k >= $s_year; $k--) {
              $sele2 = '';
              if (isset($_POST['attendance_year']) && !empty($_POST['attendance_year'])) {
                if ($_POST['attendance_year'] == $k) {
                  $sele2 = 'selected';
                }
              }

            ?>
              <option value="<?php echo $k; ?>" <?php echo $sele2; ?>><?php echo $k; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>


      <div class="col-sm-3 col-xs-6">
        <button type="submit" class="btn btn-success btn-block">Search</button>
      </div>

      <?php
      $is_teamlead = $this->db->get_where('users', array('id' => $this->session->userdata('user_id')))->row()->is_teamlead;
      if ($is_teamlead == 'yes') {
      ?>
        <div class="col-md-3 col-sm-6">
          <a href="<?php echo base_url(); ?>attendance/ro_attendance_view" class="btn btn-primary btn-block">Team Attendance</a>
        </div>
      <?php
      }
      ?>
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
              <th>Worked Hours</th>
              <th>Break</th>
              <?php /* <th>Overtime</th>*/ ?>
            </tr>
          </thead>
          <tbody>

            <?php

            if (isset($_POST['attendance_month']) && !empty($_POST['attendance_month'])) {
              $a_month = $_POST['attendance_month'];
            }

            if (isset($_POST['attendance_year']) && !empty($_POST['attendance_year'])) {
              $a_year = $_POST['attendance_year'];
            }

            $where     = array('user_id' => $user_id, 'a_month' => $a_month, 'a_year' => $a_year);
            $this->db->select('month_days,month_days_in_out');
            $results  = $this->db->get_where('dgt_attendance_details', $where)->result_array();

            $sno = 1;
            foreach ($results as $rows) {
              $list = array();
              $month = $a_month;
              $year = $a_year;

              $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);


              for ($d = 1; $d <= $number; $d++) {
                $time = mktime(12, 0, 0, $month, $d, $year);
                if (date('m', $time) == $month)
                  $date = date('d M Y', $time);

                $a_day = date('d', $time);

                if (!empty($rows['month_days'])) {
                  $month_days =  unserialize($rows['month_days']);
                  $month_days_in_out =  unserialize($rows['month_days_in_out']);
                  $day = $month_days[$a_day - 1];
                  $day_in_out = $month_days_in_out[$a_day - 1];
                  $latest_inout = end($day_in_out);

                  $production_hour = 0;
                  $break_hour = 0;

                  $in_date = $year . '-' . $month . '-' . ($a_day);
                  $punch_out_date = '';

                  $list_attendance_employee = array();
                  $list_attendance_employee =  Attendance_model::get_employee_attendance($user_id, $in_date, 'all');
                  if(!empty($list_attendance_employee)) {
                    $day['punch_in'] = $latest_inout['punch_out'] = '';
                    // $production_hour = 0;
                    foreach ($list_attendance_employee as $punch_detail) {
                      
                      if (!empty($punch_detail['punch_in_date_time']) && !empty($punch_detail['punch_out_date_time'])) {
                       
                        $day['punch_in'] = date("h:i a", strtotime($punch_detail['punch_in_date_time']));
                        $latest_inout['punch_out'] = date("h:i a", strtotime($punch_detail['punch_out_date_time']));
                        
                        $punch_in_Date =date("Y-m-d", strtotime($punch_detail['punch_in_date_time']));
                        $tmp_punch_in_Date =date("Y-m-d", strtotime($punch_detail['punch_out_date_time']));
                        if($punch_in_Date != $tmp_punch_in_Date)
                        {
                          $punch_out_date = ' ('.date("d M Y ", strtotime($punch_detail['punch_out_date_time'])).')';
                        }
                        
                        $production_hour += time_difference(date('H:i', strtotime($punch_detail['punch_in_date_time'])), date('H:i', strtotime($punch_detail['punch_out_date_time'])));
                      }
                      else{
                        if($punch_detail['punch_in_date_time'] != "")
                        {
                          $day['punch_in'] = date("h:i a", strtotime($punch_detail['punch_in_date_time']));
                        }

                        if($punch_detail['punch_out_date_time'] != "")
                        {
                          $latest_inout['punch_out'] = date("h:i a", strtotime($punch_detail['punch_out_date_time']));
                        }
                      }
                      $break_hour = '';
                    }
                  } 
                  else {
                    // $production_hour = 0;
                    foreach ($month_days_in_out[$a_day - 1] as $punch_detail) {

                      if (!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out'])) {

                        $production_hour += time_difference(date('H:i', strtotime($punch_detail['punch_in'])), date('H:i', strtotime($punch_detail['punch_out'])));
                      }
                    }

                    for ($i = 0; $i < count($month_days_in_out[$a_day - 1]); $i++) {

                      if (!empty($month_days_in_out[$a_day - 1][$i]['punch_out']) && $month_days_in_out[$a_day - 1][$i + 1]['punch_in']) {
  
                        $break_hour += time_difference(date('H:i', strtotime($month_days_in_out[$a_day - 1][$i]['punch_out'])), date('H:i', strtotime($month_days_in_out[$a_day - 1][$i + 1]['punch_in'])));
                      }
                    }
                    
                  }

                  

                  $overtimes = ($production_hour + $break_hour) - (9 * 60);
                  if ($overtimes > 0) {
                    $overtime = $overtimes;
                  } else {
                    $overtime = 0;
                  }
            ?>
                  <tr>
                    <td><?php echo $sno++; ?></td>
                    <td><?php echo $date; ?></td>
                    <?php

                    if (date('D', $time) == 'Sat' || date('D', $time) == 'Sun') {
                      if (!empty($day['punch_in'])) {
                    ?>

                        <td><?php echo !empty($day['punch_in']) ? date("g:i a", strtotime($day['punch_in'])) : '-'; ?></td>
                        <td class="<?php echo $punch_out_date; ?>"><?php echo !empty($latest_inout['punch_out']) ? date("g:i a", strtotime($latest_inout['punch_out'])) . $punch_out_date: '-'; ?></td>
                        <td><?php echo !empty($production_hour) ? intdiv($production_hour, 60) . '.' . ($production_hour % 60) . ' hrs' : '-'; ?> </td>
                        <td><?php echo !empty($break_hour) ? intdiv($break_hour, 60) . '.' . ($break_hour % 60) . ' hrs' : '-'; ?></td>
                        <?php /* <td><?php echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';?></td>*/ ?>

                      <?php
                      } else {
                        echo '<td></td><td></td><td style="color:red;"> Week Off  </td>';
                      }
                    } else {
                      ?>

                      <td><?php echo !empty($day['punch_in']) ? date("g:i a", strtotime($day['punch_in'])) : '-'; ?></td>
                      <td><?php echo !empty($latest_inout['punch_out']) ? date("g:i a", strtotime($latest_inout['punch_out']))  . $punch_out_date : '-'; ?></td>
                      <td><?php echo !empty($production_hour) ? intdiv($production_hour, 60) . '.' . ($production_hour % 60) . ' hrs' : '-'; ?> </td>
                      <td><?php echo !empty($break_hour) ? intdiv($break_hour, 60) . '.' . ($break_hour % 60) . ' hrs' : '-'; ?></td>
                      <?php /*<td><?php echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';?></td>*/ ?>

                    <?php
                    }
                    ?>
                  </tr>
            <?php }
              }
            } ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- /Page Content -->