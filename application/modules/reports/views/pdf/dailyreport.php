<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />
<style type="text/css">
  .pure-table td, .pure-table th {
    border-bottom: 1px solid #cbcbcb;
    border-width: 0 0 0 1px;
    margin: 0;
    overflow: visible;
    padding: .5em 1em;
}
.pure-table .table td {
    vertical-align: middle !important;
}
</style>
<?php 
ini_set('memory_limit', '-1');
$cur = App::currencies(config_item('default_currency')); 
$customer = ($client > 0) ? Client::view_by_id($client) : array();
$report_by = (isset($report_by)) ? $report_by : 'all';
?>


<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><b><?=lang('daily_report')?></b></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>

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
   if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){
    $post_branch_id = $branch_id;
    $emp_id =$_POST['user_id'];
    } else {
    $post_branch_id = $this->session->userdata('branch_id');
    $emp_id =$this->session->userdata('user_id');
    }
   $this->db->select('ad.*');
   $this->db->from('attendance_details ad');
   $this->db->join('users u', 'u.id=ad.user_id');
   $this->db->join('account_details acct','u.id = acct.user_id',LEFT);   
   if($emp_id) {
     $this->db->where('ad.user_id',$emp_id);
   }
   if($emp_id) {
     $this->db->where('acct.branch_id',$post_branch_id);
   }
   $this->db->where(array('a_month'=>$a_month,'a_year'=>$a_year));
   $record = $this->db->get()->result_array();
   /*$where     = array('subdomain_id'=>$subdomain_id,'a_month'=>$a_month,'a_year'=>$a_year);
   // $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->result_array();*/
   ?>

         <table border="0"cellpadding="0" cellspacing="0" height="100%" width="1200px" class="inside-report">
            <thead>
              <tr class="attendance_record">                
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('date')?></th>
                  <th align="left" valign="middle" style="font-weight:700;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('employee')?></th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('status')?></th>
                 
              </tr>
            </thead>
            <tbody>
				<?php 
				 $user_id = array();
				if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']) || !empty($_POST['branch_id']))
                { 
                  $this->db->select('attend.*');
                  $this->db->from('attendance_details attend');
                  $this->db->join('users U', 'U.id=attend.user_id');
                  $this->db->join('account_details AD','U.id = AD.user_id',LEFT);   
                  if(!empty($_POST['branch_id'])) {
                    $this->db->where('AD.branch_id',$_POST['branch_id']);
                  }   
                  if(!empty($_POST['user_id'])) {
                    $this->db->where('attend.user_id',$_POST['user_id']);
                  }        			
                  if(!empty($_POST['department_id'])) {
                    $this->db->where('U.department_id',$_POST['branch_id']);
                  }  
                  if(!empty($_POST['teamlead_id'])) {
                    $this->db->where('U.teamlead_id',$_POST['teamlead_id']);
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
                    $this->db->where(array('attend.a_month >='=>$start_month, 'attend.a_month <='=>$end_month, 'attend.a_year >='=>$start_year,'attend.a_year <='=>$end_year));
                  } else {
                    $this->db->where(array('a_month'=>$a_month,'a_year'=>$a_year));
                  }
                  $daily_attendance_report = $this->db->get()->result_array();
              } else{              	 
	              //  if(!empty($record)){
	              //   foreach ($record as $key => $value) {
	              //     $user_id[] = $value['user_id'];
	              //   }
              	// }
                $daily_attendance_report = $record;
          	}
              // $user_id =  array_unique($user_id);

                     // echo "<pre>";   print_r($user_id); exit;
                    $sno=1;
                   foreach ($daily_attendance_report as $key => $value) {

                    if($value['user_id'] !=1){
                    
                      $user_id = $value['user_id'];

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
                      $this->db->where('subdomain_id',$subdomain_id);
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


                    <tr class="<?php echo $sno;?>" style="background-color:<?php echo ($sno % 2 == 0)?'#fff':'#cbcbcb';?>;font-family:'Open Sans',arial,sans-serif!important;">
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                              <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                              <?php echo $new_date ;?>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                               <?php echo date('l', $time)?>
                              </td>
                            </tr>
                          </table>
                            
                        </td>
               				
                      <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                         <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                          <tr>
                            <td width="40px" style="min-width: 40px">
                               <img class="avatar" style="border-radius:50px;background-color: transparent;border-radius: 30px;display: inline-block;font-weight: 500;height: 38px;line-height: 38px;overflow: hidden;text-align: center;text-decoration: none;text-transform: uppercase;vertical-align: middle;width: 38px;position: relative;" src="<?php echo base_url();?>assets/avatar/<?php echo $account_details['avatar']?>">
                            </td>
                            <td>
                              <h2 style="display: inline-block;font-size: inherit;font-weight: 400;margin: 0;padding: 0;vertical-align: middle;"><span class="username-info"><?php echo ucfirst(user::displayName($user_details['id']));?></span></h2>
                                <span class="userrole-info"> <?php echo $designation_name;?></span>
                                
                            </td>
                          </tr>
                           <tr>
                            <td width="40px" style="min-width: 40px">
                            </td>
                            <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                              <?php echo !empty($shift['shift_name'])?ucfirst($shift['shift_name']):''?>&nbsp;<?php echo !empty($total_scheduled_minutes)?'('.intdiv($total_scheduled_minutes, 60).'.'. ($total_scheduled_minutes % 60).' hrs)':'';?><br>  
                           <?php

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
                                              

                           echo !empty($punch_detail['punch_in'])?'<i class="fa fa-arrow-right text-success"></i> &nbsp; '.date("g:i a", strtotime($punch_detail['punch_in'])).' &nbsp;|&nbsp ':''; ?><?php echo !empty($punch_detail['punch_out'])?'<i class="fa fa-arrow-left text-danger"></i> &nbsp;  '.date("g:i a", strtotime($punch_detail['punch_out'])):''; ?>  <br>
                         <?php }?>  
                          </td>
                        </tr>
                        </table>
                            </td>
                          </tr>

                      </table>  
                    </td>
                     
                      <?php

                      // if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      if(empty($user_schedule))
                      {
                        if(empty($day['punch_in']))
                        {
                           ?>
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                 <span class="label " style="color:  #ff1a75;padding: 5px 20px ;
    font-size: 18px;min-width: 110px;display: inline-block; font-weight: bold;"><?php echo lang('week_off');?></span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td>    
                         
                      
                          
                       <?php }?>
                        
                     <?php  }
                      else
                      {
                        
                        if(!empty($day['punch_in']))
                        {
                        	
                           $later_entry_minutes = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$day['punch_in']);


                           if($later_entry_minutes > 0){?>
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                 <span class="label " style="color: rgb(241, 180, 76);padding: 5px 20px;
    font-size: 18px;min-width: 110px;display: inline-block;font-weight: bold;">Delay</span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                           	
                          <?php  } else{?>
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                 <span class="label " style="color: #1eb53a;padding: 5px 20px;
    font-size: 18px;min-width: 110px;display: inline-block;font-weight: bold;">Present</span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                          	
                          <?php }
                           ?>
                           
                       <?php } else {?>
                        <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                 <span class="label " style="color: rgb(61, 142, 248);padding: 5px 20px;
    font-size: 18px;min-width: 110px;display: inline-block;font-weight: bold;"><?php echo lang('absent');?></span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td>
                       	
                       <?php }?>
                      
                      
                    </tr>
                    <?php } }  }  

                  } } $sno++;} ?>
					
				</tbody>
			</table>
    </div>
