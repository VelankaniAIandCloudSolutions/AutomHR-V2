<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function date_formats()
{
    return array(
        'm/d/Y' => array(
            'setting'    => 'm/d/Y',
            'datepicker' => 'mm/dd/yyyy'
        ),
        'm-d-Y' => array(
            'setting'    => 'm-d-Y',
            'datepicker' => 'mm-dd-yyyy'
        ),
        'm.d.Y' => array(
            'setting'    => 'm.d.Y',
            'datepicker' => 'mm.dd.yyyy'
        ),
        'Y/m/d' => array(
            'setting'    => 'Y/m/d',
            'datepicker' => 'yyyy/mm/dd'
        ),
        'Y-m-d' => array(
            'setting'    => 'Y-m-d',
            'datepicker' => 'yyyy-mm-dd'
        ),
        'Y.m.d' => array(
            'setting'    => 'Y.m.d',
            'datepicker' => 'yyyy.mm.dd'
        ),
        'd/m/Y' => array(
            'setting'    => 'd/m/Y',
            'datepicker' => 'dd/mm/yyyy'
        ),
        'd-m-Y' => array(
            'setting'    => 'd-m-Y',
            'datepicker' => 'dd-mm-yyyy'
        ),
        'd.m.Y' => array(
            'setting'    => 'd.m.Y',
            'datepicker' => 'dd.mm.yyyy'
        )
    );
}

function date_from_mysql($date, $ignore_post_check = FALSE)
{
    if ($date <> '0000-00-00')
    {
        if (!$_POST or $ignore_post_check)
        {
            $CI = & get_instance();

            $date = DateTime::createFromFormat('Y-m-d', $date);
            return $date->format($CI->mdl_settings->setting('date_format'));
        }
        return $date;
    }
    return '';
}

function date_from_timestamp($timestamp)
{
    $CI = & get_instance();
    
    $date = new DateTime();
    $date->setTimestamp($timestamp);
    return $date->format($CI->mdl_settings->setting('date_format'));
}

function date_to_mysql($date)
{
    $CI = & get_instance();

    $date = DateTime::createFromFormat($CI->mdl_settings->setting('date_format'), $date);
    return $date->format('Y-m-d');
}

function format_date($date){
    $CI = & get_instance();
    return strftime($CI->config->item('date_format'), strtotime($date));
}

function date_format_setting()
{
    $CI = & get_instance();

    $date_format = $CI->mdl_settings->setting('date_format');

    $date_formats = date_formats();

    return $date_formats[$date_format]['setting'];
}

function date_format_datepicker()
{
    $CI = & get_instance();

    $date_format = $CI->mdl_settings->setting('date_format');

    $date_formats = date_formats();

    return $date_formats[$date_format]['datepicker'];
}

/**
 * Adds interval to user formatted date and returns user formatted date
 * To be used when date is being output back to user
 * @param $date - user formatted date
 * @param $increment - interval (1D, 2M, 1Y, etc)
 * @return user formatted date
 */
function increment_user_date($date, $increment)
{
    $CI = & get_instance();

    $mysql_date = date_to_mysql($date);

    $new_date = new DateTime($mysql_date);
    $new_date->add(new DateInterval('P' . $increment));

    return $new_date->format($CI->mdl_settings->setting('date_format'));
}

function timelog($str_time){
    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
    return isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
}

function valid_date($string){
    return (bool)strtotime($string);
}

/**
 * Adds interval to yyyy-mm-dd date and returns in same format
 * @param $date
 * @param $increment
 * @return date
 */
function increment_date($date, $increment)
{
    $new_date = new DateTime($date);
    $new_date->add(new DateInterval('P' . $increment));
    return $new_date->format('Y-m-d');
}

/**
     * Returns time difference between two timestamps, in human readable format.
     *
     * @param int $time1 A timestamp.
     * @param int $time2 A timestamp, defaults to the current time.
     * @param string $output Formatting string specifying which parts of the date to return in the array.
     * @return string|array
     */
function timespan($time1, $time2 = null, $output = 'years,months,weeks,days,hours,minutes,seconds')
    {
        // Array with the output formats
        $output = preg_split('/[^a-z]+/', strtolower((string) $output));
        // Invalid output
        if (empty($output)) {
            return false;
        }
        // Make the output values into keys
        extract(array_flip($output), EXTR_SKIP);
        // Default values
        $time1  = max(0, (int) $time1);
        $time2  = empty($time2) ? time() : max(0, (int) $time2);
        // Calculate timespan (seconds)
        $timespan = abs($time1 - $time2);
        // All values found using Google Calculator.
        // Years and months do not match the formula exactly, due to leap years.
        // Years ago, 60 * 60 * 24 * 365
        if (isset($years) ) {
            $timespan -= 31556926 * ($years = (int) floor($timespan / 31556926));
        }
        // Months ago, 60 * 60 * 24 * 30
        if (isset($months)) {
            $timespan -= 2629744 * ($months = (int) floor($timespan / 2629743.83));
        }
        // Weeks ago, 60 * 60 * 24 * 7
        if (isset($weeks)) {
            $timespan -= 604800 * ($weeks = (int) floor($timespan / 604800));
        }
        // Days ago, 60 * 60 * 24
        if (isset($days)) {
            $timespan -= 86400 * ($days = (int) floor($timespan / 86400));
        }
        // Hours ago, 60 * 60
        if (isset($hours)) {
            $timespan -= 3600 * ($hours = (int) floor($timespan / 3600));
        }
        // Minutes ago, 60
        if (isset($minutes)) {
            $timespan -= 60 * ($minutes = (int) floor($timespan / 60));
        }
        // Seconds ago, 1
        if (isset($seconds)) {
            $seconds = $timespan;
        }
        // Remove the variables that cannot be accessed
        unset($timespan, $time1, $time2);
        // Deny access to these variables
        $deny = array('deny', 'key', 'difference', 'output');
        // Return the difference
        $difference = array();
        foreach ($output as $key) {
            if (isset($$key) AND !in_array($key, $deny)) {
                // Add requested key to the output
                $difference[$key] = $$key;
            }
        }
        // Invalid output formats string
        if (empty($difference)) {
            return false;
        }
        // If only one output format was asked, don't put it in an array
        if (count($difference) === 1) {
            return current($difference);
        }
        // Return array
        return $difference;
    }
    /**
     * Expands upon the functionality provided by Date::timespan(), such that when provided
     * with two timestamps (or one and using time() as the second), it will intelligently
     * predict the human readable date format to use, based on the length of the timespan.
     * Consequently the time string returned is more of an approximation - it will in some
     * cases be less than the length of the actual period, but never more.
     *
     * timespan < 1 minute: 'X seconds'
     * timespan < 1 hour: 'X minutes'
     * timespan < 1 day: 'X hours'
     * timespan < 1 week: 'X days'
     * timespan < 1 month: 'X weeks'
     * timespan < 1 year: 'X months'
     *
     * It will also unpluralise if the unit is 1, so 1 seconds becomes 1 second.
     *
     * @param int $time1 A timestamp.
     * @param int|false $time2 A timestamp, defaults to the current time (passing null). If set to false, the
     * first parameter will be treated as the period on its own.
     * @return string Human readable format
     */

function humanFormat($time1, $time2 = null)
    {

        date_default_timezone_set('Asia/Kolkata');

        // Default values
        $time1  = max(0, (int) $time1);
        if($time2 !== false) {
            $time2  = empty($time2) ? time() : max(0, (int) $time2);
            // Calculate timespan (seconds)
            $period = abs($time1 - $time2);
        } else {
            $period = $time1;
        }
        $format = 'seconds';
        if ($period > 31556926) {
            // More than one year
            $format = 'years';
        }
        elseif ($period > 2629744) {
            // More than one month
            $format = 'months';
        }
        elseif ($period > 604800) {
            // More than one week
            $format = 'weeks';
        }
        elseif ($period > 86400) {
            // More than one day
            $format = 'days';
        }
        elseif ($period > 3600) {
            // More than one hour
            $format = 'hours';
        }
        elseif ($period > 60) {
            // More than one minute
            $format = 'minutes';
        }
        if($time2 !== false) {
            // Get timespan output
            $timespan = timespan($time1, $time2, $format);
        } else {
            $timespan = $time1;
        }
        // Remove the s
        if($timespan == 1) {
            $format = substr($format, 0, -1);
        }
        return $timespan . ' ' . $format;
    }

    function time_difference($dtime,$atime)
    {
       $nextDay=$dtime>$atime?1:0;
       $dep=EXPLODE(':',$dtime);
       $arr=EXPLODE(':',$atime);
       $diff=ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
       $hours=FLOOR($diff/(60*60));
       $mins=FLOOR(($diff-($hours*60*60))/(60));
       $secs=FLOOR(($diff-(($hours*60*60)+($mins*60))));
       IF(STRLEN($hours)<2){$hours="0".$hours;}
       IF(STRLEN($mins)<2){$mins="0".$mins;}
       IF(STRLEN($secs)<2){$secs="0".$secs;}
       return (($hours*60)+$mins);
    }

    function working_days($month, $year) {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

                $date = $year.'/'.$month.'/'.$i; //format date
                $get_name = date('l', strtotime($date)); //get week day
                $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                //if not a weekend add day to array
                if($day_name != 'Sun' && $day_name != 'Sat'){
                    $workdays[] = $i;
                }

        }

        return count($workdays);
   }

   function explode_time($time) { //explode time and convert into seconds
        $time = explode(':', $time);
        $time = $time[0] * 3600 + $time[1] * 60;
        return $time;
}

function second_to_hhmm($time) { //convert seconds to hh:mm
        $hour = floor($time / 3600);
        $minute = strval(floor(($time % 3600) / 60));
        if ($minute == 0) {
            $minute = "00";
        } else {
            $minute = $minute;
        }
        $time = $hour . "." . $minute;
        return $time;
}
function differnceTime($startTime, $endTime,$break_time){
    if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
    $interval = $datetime1->diff($datetime2);  

     $total_hours = $interval->format("%H:%I:%S");

    $time = $total_hours;
    $timesplit=explode(':',$time);
    $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);
    $realtime = $min-$break_time;    

    return $hoursAndMinutes =  floor($realtime / 60).' hrs '.($realtime -   floor($realtime / 60) * 60).' mins';   

}
function work_time($startTime, $endTime,$break_time){

    if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){

        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));

        

    }

    $datetime1 = new DateTime($startTime);

    $datetime2 = new DateTime($endTime);

    $interval = $datetime1->diff($datetime2);  



     $total_hours = $interval->format("%H:%I:%S");



    $time = $total_hours;

    $timesplit=explode(':',$time);

    $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);

    $realtime = $min-$break_time;    



    return $hoursAndMinutes =  floor($realtime / 60).'.'.($realtime -   floor($realtime / 60) * 60);   



}
function total_work_hours($startTime, $endTime){
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
    $interval = $datetime1->diff($datetime2);   
    
    $hours_to_min = $interval->h * 60;
    $minutes = $hours_to_min + $interval->i;
    return $Minutes = $minutes;
}
function work_hours($startTime, $endTime,$break_minutes){
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
    $interval = $datetime1->diff($datetime2);  

     $total_hours = $interval->format("%H:%I:%S");

    $time = $total_hours;
    $timesplit=explode(':',$time);
    $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);
    return $realtime = $min-$break_minutes;  
     
    // $work_hours->format($hoursAndMinutes); 
}

function later_entry_hours($startTime, $endTime){
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
    // echo $datetime1. ' '.$datetime2 ; exit;
    if($datetime2 > $datetime1){       
        $interval = $datetime1->diff($datetime2);          
        $hoursAndMinutes = ($interval->h > 0) ? "%H hrs %I mins" : "%I mins";
        return $interval->format($hoursAndMinutes); 
    } else{
        return '-';
    }
    
}
function later_entry_minutes($startTime, $endTime){
   
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
     }
    // echo $startTime .'>'.$endTime;
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
    if($datetime2 > $datetime1){       
        $interval = $datetime1->diff($datetime2);   
        $hours_to_min = ($interval->h) * 60;
       return $minutes = $hours_to_min + ($interval->i);
   }else{
        return 0;
   }
}

function extra_minutes($startTime, $endTime){
   
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
    // return $startTime .'>'.$endTime;
    if($datetime1 > $datetime2){
        $interval = $datetime1->diff($datetime2);   
        $hours_to_min = ($interval->h) * 60;
        return $minutes = $hours_to_min + ($interval->i);
    }else{
        return 0;
    }
    
}

function between_minstartto_start($startTime, $endTime){
   
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
 
    $interval = $datetime1->diff($datetime2);   
    $hours_to_min = ($interval->h) * 60;
    return $minutes = $hours_to_min + ($interval->i);
    
}

function between_endto_max_end($startTime, $endTime){
   
   if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){
        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));
        
    }
    $datetime1 = new DateTime($startTime);
    $datetime2 = new DateTime($endTime);
 
    $interval = $datetime1->diff($datetime2);   
    $hours_to_min = ($interval->h) * 60;
    return $minutes = $hours_to_min + ($interval->i);
    
}

function start_between($minstartTime, $punchIn, $startTime){
   
   
    $minstartTime = new DateTime($minstartTime);
    $punchIn = new DateTime($punchIn);
    $startTime = new DateTime($startTime);
    // return $startTime .'>'.$endTime;
    if(($punchIn > $minstartTime) && ($punchIn < $startTime)){
        $interval = $punchIn->diff($startTime);   
        $hours_to_min = ($interval->h) * 60;
        return $minutes = $hours_to_min + ($interval->i);
    }else{
        return 0;
    }
    
}

function end_between($endTime, $punchOut, $maxendTime){
   
   
    $endTime = new DateTime($endTime);
    $punchOut = new DateTime($punchOut);
    $maxendTime = new DateTime($maxendTime);
    // return $startTime .'>'.$endTime;
    if(($punchOut > $endTime) && ($punchOut < $maxendTime)){
        $interval = $punchOut->diff($maxendTime);   
        $hours_to_min = ($interval->h) * 60;
        return $minutes = $hours_to_min + ($interval->i);
    }else{
        return 0;
    }
    
}

function exceltime_to_normal($time){

   $the_value =  $time;
    $total = $the_value * 24; //multiply by the 24 hours
    $hours = floor($total); //Gets the natural number part
    $minute_fraction = $total - $hours; //Now has only the decimal part
    $minutes = $minute_fraction * 60; //Get the number of minutes
    return $display = $hours . ":" . $minutes;
    

}

function hours_to_mins($hours){

    $timesplit=explode('.',$hours);

    $min=($timesplit[0]*60)+($timesplit[1]);

    return  $min;  
    

}
function night_hours_punch_in($startTime, $endTime, $input) {
    if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($input)) == "AM"){

        $input = date('Y-m-d H:i', strtotime($input . ' +1 day'));

        

    }
    if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){

        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));

        

    }
    $from = new DateTime($startTime);

    $input = new DateTime($input);

    $till = new DateTime($endTime);

    // return $startTime .'>'.$endTime;

    if(($input > $from) && ($input < $till)){

       
        return 'yes';

    }else{

        return 'no';

    }
}
function night_hours_punch_out($startTime, $endTime, $input) {
    if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($input)) == "AM"){

        $input = date('Y-m-d H:i', strtotime($input . ' +1 day'));

        

    }
    if(date("A", strtotime($startTime)) == "PM" && date("A", strtotime($endTime)) == "AM"){

        $endTime = date('Y-m-d H:i', strtotime($endTime . ' +1 day'));

        

    }
    $from = new DateTime($startTime);

    $input = new DateTime($input);

    $till = new DateTime($endTime);

    // return $startTime .'>'.$endTime;

    if(($input > $from) && ($input < $till)){

       
        return 'yes';

    }else{

        return 'no';

    }
}

function punch_address($latitude,$longitude){

    $url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAkA_CZ6XSZiMDeVjc1iVVTHAk4wmwQ6p8&latlng='.trim($latitude).','.trim($longitude).'&sensor=true'; 
    $json = @file_get_contents($url); 
    
    $data = json_decode($json); 

    $status = $data->status; 

    $status1 = "OK";
    if(strtolower($status) == strtolower($status1)){        //Get address from json data 

        $location['address'] = $data->results[0]->formatted_address;    

    }else{ 

        $location['address'] =  array(); 

    } 

            //Print address 

   return $location['address'];  
}



?>