<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_model extends CI_Model
{

    private static $db;

    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    // Get all projects
    static function get_list($user_id = NULL, $date = NULL)
    {
        $condition = "";
        if($user_id != NULL){
          $condition .= " U.id = '$user_id' AND";
        }
    /*    $attenlogs = "SELECT AE.user_id, AE.punch_in_date_time, AE.punch_in_note, AE.in_address, AE.punch_out_note, AE.out_address, IF(AE.punch_out_date_time != '0000-00-00 00:00:00', AE.punch_out_date_time, '--') AS punch_out_date_time, AD.fullname, IF((time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) IS NULL,0,(time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) ) AS cal_hours FROM dgt_account_details AS AD 
            LEFT JOIN  dgt_attendance_employee AS AE ON AD.user_id = AE.user_id 
            LEFT JOIN  dgt_users AS U ON U.id = AD.user_id 
            WHERE ".$condition." role_id = 3 AND (date(punch_in_date_time) = '$date' OR date(punch_out_date_time) = '$date') ORDER BY AE.user_id ASC"; */
             $attenlogs = "SELECT U.id as user_id,AD.fullname, AE.punch_in_date_time, AE.punch_in_note, AE.in_address, AE.punch_out_note, AE.out_address, IF(AE.punch_out_date_time != '0000-00-00 00:00:00', AE.punch_out_date_time, '-') AS punch_out_date_time,  IF((time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) IS NULL,0,(time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) ) AS cal_hours
                    FROM dgt_users U
                    INNER JOIN dgt_account_details AD ON AD.user_id = U.id 
                    INNER JOIN dgt_attendance_employee AE ON AE.user_id = AD.user_id
                    WHERE $condition  U.role_id = 3  AND (date(punch_in_date_time) = '$date' OR date(punch_out_date_time) = '$date') ORDER BY AE.user_id ASC";
       
        return self::$db->query($attenlogs)->result();
    }

    static function attendance_list_regularization($inputs){
        // echo $this->session->userdata('branch_id');exit;
        // print_r($inputs);exit;
                $page = $inputs['start'];
                $employee_name = $inputs['employee_name'];
                $employee_id = $inputs['employee_id'];
                $teamlead_id = $inputs['teamlead_id'];
                $limit = '';
                $query_string = "SELECT count(U.id) as total_records  FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and U.activated=1 ";
                
                if(!empty($employee_name)){
                    $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
                }
                if($employee_id !=0){
                    $query_string .= " AND U.id =  $employee_id";    
                }
                
                if($teamlead_id !=0){
                    $query_string .= " AND U.teamlead_id =  $teamlead_id";    
                }
                
                $total_pages  = self::$db->query($query_string)->row_array();
                $total_record = 0;
                if(!empty($total_pages)){
                    $total_record = $total_pages['total_records'];;
                    $total_pages  = $total_pages['total_records'];
                    if($total_pages > 0){
                        $total_pages = ceil($total_pages/10);
                    }    
                }else{
                     $total_pages = 0 ;
                }
                //$query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and AD.branch_id='".$inputs['branch_id']."' ";
        
                $query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3";
                
                if(!empty($employee_name)){
                    $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
                }
                if($employee_id !=0){
                    $query_string .= " AND U.id =  $employee_id";    
                }
                if($teamlead_id !=0){
                    $query_string .= " AND U.teamlead_id =  $teamlead_id";    
                }
                $query_string .= " ".$limit;
                $results =  self::$db->query($query_string)->result();
                $records = array();
                if(!empty($results)){
                    foreach ($results as $result) {
                        $user_id   = $result->user_id;
                        $attendance  = self::attendance($user_id,$inputs);
                        $result->attendance = unserialize($attendance['month_days']);
                        $records[] = $result;
                    }
                }
                return array($total_pages,$records,$total_record);
                
            }

    static function attendance_list($inputs){
// echo $this->session->userdata('branch_id');exit;
// print_r($inputs);exit;
        $page = $inputs['start'];
        $employee_name = $inputs['employee_name'];
        $employee_id = $inputs['employee_id'];
        $status = $inputs['status'];

        if(!isset($inputs['teamlead_id']))
        {
            $inputs['teamlead_id']=0;
        }

		$teamlead_id = $inputs['teamlead_id'];
        $limit = 'LIMIT 0,'.$inputs['length'];
        if($page > 1){
            if($page<10){
                $start = ($page - 1) * 10;
            }
            else{
                $start = $page;
            }

            if($inputs['length'] > 0)
            {
              $limit = " LIMIT $start,".$inputs['length'];  
            } 
            else{
                $limit = " LIMIT $start";
            }
            
        }
        $query_string = "SELECT count(U.id) as total_records  FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and U.activated=1 ";
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        
        if($status != ""){
            $query_string .= " AND U.status = '".$status."'";
        }

        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		
		if($teamlead_id !=0){
            $query_string .= " AND U.teamlead_id =  $teamlead_id";    
        }

        $total_pages  = self::$db->query($query_string)->row_array();


        $total_record = 0;
        if(!empty($total_pages)){
            $total_record = $total_pages['total_records'];;
            $total_pages  = $total_pages['total_records'];
            if($total_pages > 0){
                $total_pages = ceil($total_pages/10);
            }    
        }else{
             $total_pages = 0 ;
        }
        //$query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and AD.branch_id='".$inputs['branch_id']."' ";

        $query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3";
        
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		if($teamlead_id !=0){
            $query_string .= " AND U.teamlead_id =  $teamlead_id";    
        }

        if($status != ""){
            $query_string .= " AND U.status = '".$status."'";
        }


        $query_string .= " ".$limit;
        $results =  self::$db->query($query_string)->result();

        
        $records = array();
        if(!empty($results)){
            
            foreach ($results as $result) {
                $user_id   = $result->user_id;
                $lop_value = self::$db->query("SELECT lop FROM dgt_attendance_details WHERE user_id = '".$user_id."' AND a_month = '".$inputs['attendance_month']."' AND a_year = '".$inputs['attendance_year']."' ")->row_array();
                // print_r($lop_value['lop']);exit();
                $result->lop=$lop_value['lop'];
                $attendance  = self::attendance($user_id,$inputs);
               
                $result->attendance = unserialize($attendance['month_days_in_out']);
                $records[] = $result;
            }
        }
        
        return array($total_pages,$records,$total_record);
        
    }
	
	
	static function attendance_list_request_regul($inputs){
		
// print_r($this->session->userdata('user_id'));exit;
// print_r($inputs);exit;
        $page = $inputs['page'];
        $employee_name = $inputs['employee_name'];
        $employee_id = $inputs['employee_id'];
		$teamlead_id = $inputs['teamlead_id'];
        $limit = 'LIMIT 0,10';
        if($page > 1){
            $start = ($page - 1) * 10;
            $limit = " LIMIT $start,10";
        }
        $query_string = "SELECT count(U.id) as total_records  FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and U.activated=1 ";
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		
		if($teamlead_id !=0){
            $query_string .= " AND U.teamlead_id =  $teamlead_id";    
        }

        
        $total_pages  = self::$db->query($query_string)->row_array();
        
        if(!empty($total_pages)){
            $total_pages  = $total_pages['total_records'];
            if($total_pages > 0){
                $total_pages = ceil($total_pages/10);
            }    
        }else{
             $total_pages = 0 ;
        }
         
        //$query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and AD.branch_id='".$inputs['branch_id']."' ";

        $query_string = "SELECT U.id as user_id,U.teamlead_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3";
        
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		if($teamlead_id !=0){
            $query_string .= " AND U.teamlead_id =  $teamlead_id";    
        }
        $results =  self::$db->query($query_string)->result();
		// echo '<pre>';print_r($results);exit;
        $records = array();
		$attendance  = self::attendance_reg($inputs);
		// echo '<pre>';print_r($attendance);exit;
		
        // if(!empty($results)){
			// $i=0;
            // foreach ($results as $result) {
				
                // $user_id   = $result->user_id;
                // $attendance  = self::attendance_reg($user_id,$result->teamlead_id,$inputs);
				// echo '<pre>';print_r($attendance);exit;
                // $result->attendance_reg['month_days'][] = unserialize($attendance[$user_id]['month_days']);
                // $result->attendance_reg['a_day'] = $attendance[$i]['a_day'];
                // $result->attendance_reg['a_month'] = $attendance[$i]['a_month'];
                // $result->attendance_reg['a_year'] = $attendance[$i]['a_year'];
				
                // $attendance[$user_id][] = $result;
                // $records[] = $attendance;
				// echo '<pre>';print_r($records);exit;
				// $i++;
            // }
        // }
		
		$records = $attendance;
        return array($total_pages,$records);
        
    }
	
	
	
	static function attendance_reg($inputs)
    {   
        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $teamlead_id =  $inputs['teamlead_id'];
        // $result = self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
		$employee_name = $inputs['employee_name'];
		$query_string = "SELECT U.teamlead_id,AD.fullname,a.user_id,a.* FROM dgt_attendance_details_ar as a 
		left join dgt_account_details as AD on AD.user_id = a.user_id
		left join dgt_users as U on U.id = a.user_id
		WHERE  a.ro_status =0 ";
		if($inputs['role_id']!=1)
		{
			// $query_string .= " AND a.ro_id = $teamlead_id";
            $query_string .= " AND U.teamlead_id = $teamlead_id";
		}
        
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
		if(!empty($a_month)){
            $query_string .= " AND a.a_month = $a_month";
        }
		if(!empty($a_year)){
            $query_string .= " AND a.a_year = $a_year";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		// if($teamlead_id !=0){
            // $query_string .= " AND U.teamlead_id =  $teamlead_id";    
        // }
		
		// echo $query_string;exit;
        $result =  self::$db->query($query_string)->result();
		
		
		// if($inputs['role_id']==1)
		// {
			// $result = self::$db->query("SELECT AD.fullname,a.user_id,a.* FROM dgt_attendance_details_ar as a left join dgt_account_details as AD on AD.user_id = a.user_id
		// WHERE  a.ro_status =0 ")->result();
		// }
		// else
		// {
			// $result = self::$db->query("SELECT AD.fullname,a.user_id,a.* FROM dgt_attendance_details_ar as a left join dgt_account_details as AD on AD.user_id = a.user_id
		// WHERE a.ro_id = $teamlead_id AND a.ro_status =0 ")->result();
		// }
        
		
		// echo '<pre>';print_r($inputs);exit;
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            // $insert = array(
                // 'user_id'=>$user_id,
                // 'month_days'=>serialize($days),
                // 'month_days_in_out'=>serialize($days_in_out),
                // 'a_month'=>$a_month,
                // 'a_year'=>$a_year
                // );
            // self::$db->insert("dgt_attendance_details",$insert);

        // return  self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
       
    }

    static function attendance($user_id,$inputs)
    {   
        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
       /*if(!empty($result)){
           
            return $result;
        }else{*/
            $days = array();
            $days_in_out = array();
            $attendance_results = self::$db->query("SELECT branch_id FROM dgt_account_details WHERE user_id = $user_id ")->row_array();
            $req_branch_id = 0;
            if(!empty($attendance_results['branch_id'])){
                $req_branch_id = $attendance_results['branch_id'];
                $branch_details = self::$db->query("SELECT branch_id,weekend_workdays FROM dgt_branches WHERE branch_id = '".$req_branch_id."' ")->row_array();  
                $weekend_workdays=json_decode($branch_details['weekend_workdays']);
                $gn_date = '15-'.$a_month.'-'.$a_year;
		        $colMonth = date('M',strtotime($gn_date));
                $weekend_cnt[1] = date('Y-m-d', strtotime('first saturday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[2] = date('Y-m-d', strtotime('second saturday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[3] = date('Y-m-d', strtotime('third saturday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[4] = date('Y-m-d', strtotime('fourth saturday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[5] = date('Y-m-d', strtotime('fifth saturday of '.$colMonth.' '.$a_year.''));

                $weekend_cnt[6] = date('Y-m-d', strtotime('first Sunday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[7] = date('Y-m-d', strtotime('second Sunday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[8] = date('Y-m-d', strtotime('third Sunday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[9] = date('Y-m-d', strtotime('fourth Sunday of '.$colMonth.' '.$a_year.''));
                $weekend_cnt[10] = date('Y-m-d', strtotime('fifth Sunday of '.$colMonth.' '.$a_year.''));
                
                $my_val=array();
                foreach($weekend_cnt as $wckey=>$wc)
                {
                    foreach($weekend_workdays as $ww)
                    {
                        if($ww==$wckey)
                        {
                            $my_val[]=$wc;
                        }
                    }
                }
            }


            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $check_current_date = date('Y-m-d',strtotime($a_year.'-'.$a_month.'-'.$i));
               //$day = (strtolower($day)=='sun')?0:'';
            
                if(strtolower($day)=='sat' || strtolower($day) =='sun'){
                    $day = 0;
                    if (in_array($check_current_date, $my_val)) {
                        $day = '';
                    }
                 }
                 else{
                    $current_date = date('Y-m-d');
                    if(strtotime($check_current_date)>strtotime($current_date)){
                        $day = '';
                    }
                    else{
                        $day = 1;
                    }
                 }
                $j = $i -1;
                if(!empty($result['month_days'])){
                    $resul_arrs = unserialize($result['month_days']);
                  
                    if(!empty($resul_arrs[$j])){
                        $day_details = array('day'=>$day,'punch_in'=>$resul_arrs[$j]['punch_in'],'punch_out'=>$resul_arrs[$j]['punch_out']);
                    }
                    else{
                        $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                    }
                }
                else{
                    $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                }
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            if(empty($result)){
                $insert = array(
                    'user_id'=>$user_id,
                    'month_days'=>serialize($days),
                    'month_days_in_out'=>serialize($days_in_out),
                    'a_month'=>$a_month,
                    'a_year'=>$a_year
                    );
                self::$db->insert("dgt_attendance_details",$insert);
            }
            else{
                $upd_data = array(
                    'month_days'=>serialize($days),
                  //  'month_days_in_out'=>serialize($days_in_out),
                    );
                self::$db->where('user_id', $user_id);
                self::$db->where('a_month', $a_month);
                self::$db->where('a_year', $a_year);
		        self::$db->update("dgt_attendance_details",$upd_data );
            }
         
            
        return  self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        //}
       
    }
	
	static function attendance_ar($user_id,$inputs)
    {   
        $a_day = $inputs['a_day'];
        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details_ar WHERE user_id = $user_id AND a_day = $a_day AND a_month = $a_month AND a_year = $a_year ")->row_array();
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
			
			
            $insert = array(
                'user_id'=>$user_id,
                'month_days'=>serialize($days),
                'month_days_in_out'=>serialize($days_in_out),
                'a_day'=>$a_day,
                'a_month'=>$a_month,
                'a_year'=>$a_year,
                'reason'=>$inputs['reason']
                );
            self::$db->insert("dgt_attendance_details_ar",$insert);

        return  self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details_ar WHERE user_id = $user_id AND a_day = $a_day AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
       
    }
	

    public function employee_attendance($emp_id = '', $attendance_type='', $tmp_attendance= array())
    {
        if($attendance_type === 'punch-out')
        {
            $attendance_response = self::get_employee_attendance($emp_id, $tmp_attendance['punch_out_date_time'], '',$attendance_type);
        }
        else{
            $attendance_response = self::get_employee_attendance($emp_id, $tmp_attendance['punch_in_date_time'], '', $attendance_type);
        }
        
       
        if(empty($attendance_response))
        {
            self::$db->insert("attendance_employee", $tmp_attendance);
        }
        else{
           if($attendance_response['punch_in_date_time'] != "" && $attendance_response['punch_out_date_time'] == '')
           {
            $checkin_time = $attendance_response['punch_in_date_time'];
            $checkout_time = $tmp_attendance['punch_out_date_time'];
            $punch_in_timestamp = strtotime($checkin_time);
            $punch_out_timestamp = strtotime($checkout_time);
            $total_minutes = 0;
            $total_minutes = round(($punch_out_timestamp - $punch_in_timestamp) / 60);
            $tmp_attendance['total_time_in_minutes'] = $total_minutes;
            self::$db->where("id", $attendance_response['id']);
            self::$db->update("attendance_employee", $tmp_attendance);
           }
           else{
            self::$db->insert("attendance_employee", $tmp_attendance);
           }
        }
    }
    public function get_employee_attendance($emp_id = '', $in_date='', $record_limit = '', $attendance_type ='')
    {
        self::$db->select("*");
        self::$db->from("dgt_attendance_employee");
        self::$db->where("user_id", $emp_id);
        if($in_date != "" && $attendance_type == '')
        {
            self::$db->group_start();
            self::$db->where("DATE(punch_in_date_time)", date("Y-m-d", strtotime($in_date))); 
            // self::$db->or_where("DATE(punch_out_date_time)", date("Y-m-d", strtotime($in_date))); 
            self::$db->group_end();
        }

        if($attendance_type === 'punch-out')
        {
            self::$db->group_start();
            self::$db->where("punch_out_note",NULL);  
            self::$db->group_end();
        }
        if($attendance_type === 'punch-in')
        {
            self::$db->group_start();
            self::$db->where("punch_in_note",NULL); 
            self::$db->group_end();
        }

        self::$db->order_by("id","desc");
        // self::$db->group_by("DATE(dgt_attendance_employee.punch_in_date_time)");

        if($record_limit == "")
        {
            self::$db->limit(1);
            $response = self::$db->get()->row_array();
        }
        else{

            $response = self::$db->get()->result_array();
        }

        // if($in_date =='2024-05-07' && $emp_id == 1074)
        // {
           
        //    if(!empty($response))
        //    {
        //         $tmp_check_in_time = $tmp_check_out_time = '';

        //         $tmp_break_time = 0;


        //         $tmp_records_count = sizeof($response);

        //         $tmp_check_in_time = date("Y-m-d H:i:s", strtotime($response[0]['punch_in_date_time']));
        //         $tmp_check_out_time = date("Y-m-d H:i:s", strtotime($response[$tmp_records_count - 1]['punch_out_date_time']));

        //         foreach($response as $key => $response_val)
        //         {
        //             if($key < $tmp_records_count )
        //             {
        //                 $break_hour += time_difference(date('H:i', strtotime($response_val[$key]['punch_out_date_time'])), date('H:i', strtotime($response_val[$key + 1]['punch_in_date_time'])));    
        //             }


        //         }
        //     }
        // }   
        
        return $response;
    }

    public function attendance_regulization_update($data, $user_id, $in_date, $out_date)
    {
        $tmp_attendance = array();
        $punch_in_timestamp = strtotime($in_date);
        $punch_out_timestamp = strtotime($out_date);
        $total_minutes = 0;
        $total_minutes = round(($punch_out_timestamp - $punch_in_timestamp) / 60);
       
        $tmp_attendance = array(
            "user_id"             => $user_id,
            "punch_in_date_time"  => date("Y-m-d H:i:s", strtotime($in_date)),
            "punch_in_note"       => "punch-in",
            "punch_out_date_time" => date("Y-m-d H:i:s", strtotime($out_date)),
            "punch_out_note"      => "punch-out",
            "updated_at"          =>  date("Y-m-d H:i:s"),
            "updated_by"          => $user_id,
            "total_time_in_minutes" => $total_minutes
        );

        $last_record_id = self::get_employee_attendance($user_id, $in_date);
        if($last_record_id != "")
        {
            self::$db->where("id", $last_record_id['id']);
            self::$db->update("attendance_employee", $tmp_attendance);
        }
        else{
            self::$db->insert("attendance_employee", $tmp_attendance);
           }
    }
	

}

/* End of file Project.php */
