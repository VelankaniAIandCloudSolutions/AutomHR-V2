<?php 

/*$cur = App::currencies(config_item('default_currency')); 
$task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
$project_id = (isset($task_id)) ? $task_id : '';
$task_progress = (isset($task_progress)) ? $task_progress : '';
$task_id = (isset($company_id)) ? $company_id : '';



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
          }*/

          $s_year = '2015';
          $select_y = date('Y');

          $s_month = date('m');
          $e_year = date('Y');



          ?>
          <div class="content">
            <section class="panel panel-white">

              <div class="panel-heading">

                <?=$this->load->view('report_header');?>

                <?php if($this->uri->segment(3) && count($employees)> 0 ){ ?>
                  <a href="<?=base_url()?>reports/employeepdf/<?=$company_id;?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
                </a>              
              <?php } ?>
              <input type="hidden" class="form-control department_id_excel" name = "department_id" value="<?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?$_POST['department_id']:'';?>">
              <input type="hidden" class="form-control teamlead_id_excel" name = "teamlead_id" value="<?php echo (isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id']))?$_POST['teamlead_id']:'';?>">
              <input type="hidden" class="form-control range_excel" name = "range" value="<?php echo (isset($_POST['range']) && !empty($_POST['range']))?$_POST['range']:'';?>">
              <input type="hidden" class="form-control user_id_excel" name = "user_id" value="<?php echo (isset($_POST['user_id']) && !empty($_POST['user_id']))?$_POST['user_id']:'';?>">
              <?php  $report_name = lang('attendance_report');?>
              <button class="btn  btn-primary pull-right" onclick="attendance_report_excel('<?php echo $report_name;?>','attendance_report_excel');"> <span ><i class="fa fa-file-excel-o" aria-hidden="true"></i></span> <span><?=lang('excel')?></span> </button>

            </div>

            <div class="panel-body">

            <!-- <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
                  </div> -->

                  <div class="fill body reports-top rep-new-band">
                    <div class="criteria-container fill-container hidden-print">
                      <div class="criteria-band">
                        <address class="row">
                          <form method="post" action="">
                            <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
                              <div class="col-md-3">
                                <label><?=lang('branches')?> </label>

                                <select class="select2-option form-control" style="width:100%" name="branch_id" id="branchreportattendance" required>
                                  <option value="" disabled selected>Choose Entity</option>
                                  <optgroup label="<?=lang('all_branches')?>">
                                   <?php /*   <option value="" <?php if($branch_id == ''){ echo "selected"; } ?> >All Entities</option>*/?>
                                   <?php 

                                   if(!empty($all_branches)){
                                    foreach ($all_branches as $c){ ?>
                                      <option value="<?=$c['branch_id']?>" <?php if($_POST['branch_id'] == $c['branch_id']){ echo "selected"; } ?> ><?=ucfirst($c['branch_name'])?></option>
                                    <?php } }  ?>
                                  </optgroup>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label>Employee</label>
                                <select class="select2-option form-control" name="user_id" id="reportattendanceusers">
                                  <option value="" <?php if(empty($_POST['user_id']) || $_POST['user_id'] == ''){ echo "selected"; } ?> >All Employees</option>
                                  <?php if(!empty($users)){ 
                                    foreach($users as $user){
                                      ?>
                                      <option value="<?php echo $user['user_id']; ?>" <?php if($_POST['user_id'] == $user['user_id']){ echo "selected"; } ?>><?php echo $user['fullname']; ?></option>
                                    <?php } 
                                  } ?>
                                </select>
                              </div>
                            <?php }
                            else{
                              ?>
                              <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
                              <input type="hidden" value="<?php  echo $this->session->userdata('user_id');?>" name="user_id">
                            <?php }?>
                            <div class=" <?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>">
                              <label><?=lang('month');?> </label>
                              <select class="select floating form-control" id="attendance_month" name="attendance_month" required>  

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



                            <div class="<?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>">
                              <label><?=lang('year');?> </label>
                              <select class="select floating form-control" id="attendance_year" name="attendance_year" required> 

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

                            <div class="<?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>">
                              <label><?=lang('status');?> </label>
                              <select class="select floating form-control" id="emp_status" name="emp_status" > 
                                <option value ="2"  <?php if($_POST['emp_status'] == '2'){echo "selected";} ?> ><?php echo lang("all"); ?></option>
                                <option value ="1" <?php if($_POST['emp_status'] == '1') {echo "selected";} ?> > <?php echo lang("active"); ?></option>
                                <option value ="0" <?php if($_POST['emp_status'] == '0'){echo "selected";} ?> ><?php echo lang("inactive"); ?></option>

                              </select>
                            </div>


                            <div class="<?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>">  
                              <button class="btn btn-success" type="submit">
                                <?=lang('run_report')?>
                              </button>
                            </div>



                          </address>
                        </div>
                      </div>


                      <!--   <?php  form_close(); ?> -->

                      <div class="rep-container">
                        <div class="page-header text-center1">
                          <h3 class="reports-headerspacing"><?=lang('attendance_report')?></h3>
                          <?php if($task->t_id != NULL){ ?>
                            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
                          <?php } ?>
                        </div>
                        <?php if($this->input->post()){?>
                          <div class="fill-container">

                            <div class="row">
                              <?php if(!empty($start_date)){?>
                                <div class="col-md-12">
                                  <div class="col-md-3">Start Date</div>
                                  <div class="col-md-4"><?php echo $start_date;?></div>
                                </div>

                                <div class="col-md-12">
                                  <div class="col-md-3">End Date</div>
                                  <div class="col-md-4"><?php echo $end_date;?></div>
                                </div>
                              <?php }?>

                            </div>
                            <?php if(!empty($leave_types)){?>
                              <div class="row" style="margin-top:10px;margin-bottom:10px;">
                                <div class="col-md-12">
                                  <div class="col-md-3"><span >P</span> - Present</div>
                                  <div class="col-md-3"><span >W</span> - Weekend</div>
                                  <div class="col-md-3"><span style="color:green">H</span> - Holidays</div>
                                  <?php foreach($leave_types as $leave_type1){
                                   preg_match_all('/\b\w/', $leave_type1['leave_type'], $matches);
                                   $firstLetters = implode('', $matches[0]);
                                   if($leave_type1['leave_type'] =='Privilege Leave'){

                                    $firstLetters = 'PL1';
                                  }
                                  if($firstLetters =='PLV'){

                                    $firstLetters = 'P-L';
                                  }
                                  $color_code = "orange";
                                  if($firstLetters=='OD'){
                                    $color_code = "silver";
                                  }
                                  if($firstLetters=='A'){
                                    $color_code = "red";
                                  }
                                  echo '<div class="col-md-3"><span style="color:'.$color_code.'">'.$firstLetters.'</span> - '.$leave_type1['leave_type'].'</div>';
                                }
                                ?>
                              </div>
                            </div>
                          <?php }?>

                          <div class="col-md-12" style="overflow-x:scroll">

                            <table id="task_report" class="table table-striped custom-table m-b-0">
                              <thead>
                               <tr>
                                <th>#</th>
                                <th>Employee </th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>LOP(in Days)</th>
                                <th>Total Working Days </th>
                                <th>Total Days Worked(Total Working Days- LOP)</th>
                                <th>Total Leaves</th>
                                <th>Total Hours </th>
                                <?php
                                for($i=1;$i<=$num_days;$i++){
                                  ?>
                                  <th class="singleline"><?php echo $i.'-'.$cur_month;?></th>
                                  <?php
                                }
                                ?>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    <?php }?>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <style>
            .singleline,td{
              white-space: nowrap;
            }
          </style>

          <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
          <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
          <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
          <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>



          <script>
            $(document).ready(function() {
              $('#task_report').DataTable( {

                "processing": true,
                "serverSide": true,
                dom: 'Blfrtip',
                buttons: [
                 'excel', 'pdf'
                 ],
                "aLengthMenu": [
                  [10,25, 50, 100, 200, -1],
                  [10,25, 50, 100, 200, "All"]
                  ],
                "ajax": {
                  url:"<?php echo base_url().'reports/ajax_attendance_report'?>",
                  type:"POST",
                  data:{
                    "branch_id":"<?=$branch_id?>",
                    "user_id":"<?=$user_id?>",
                    "a_month":"<?=$attendance_month?>",
                    "a_year":"<?=$attendance_year?>",
                    "emp_status":$('#emp_status').val(),
                  },
                },
                columns: [
                 { data: "s_no", "defaultContent": "--" },
                 { data: "employee" , "defaultContent": "--" },
                 { data: "department" , "defaultContent": "--"},
                 { data: "designation" , "defaultContent": "--"},
                 { data: "lop" , "defaultContent": "--"},
                 { data: "working_days" , "defaultContent": "--"},
                 { data: "worked_days" , "defaultContent": "--"},
                 { data: "leave_taken" , "defaultContent": "--"},
                 { data: "total_seconds" , "defaultContent": "--"},
                 <?php for($i=1;$i<=$num_days;$i++){
                  ?>
                  { data: "<?php echo $i.'_'.$cur_month;?>" },
                  <?php 
                }
                ?>

                ],
              } );
            });
          </script>
