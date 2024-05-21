<?php
$select_y = date('Y');

$s_month = date('m');
$e_year = date('Y');
?>

<?php 

$this->load->model('leaves/Leaves_model');
// echo $branch_id;exit;
// $this->db->select_sum('leave_days');
// $total_count = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id !='=>'8','leave_id !='=>'9'))->row()->leave_days;
$annual_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'1','branch_id'=>$branch_id))->row_array();
// echo "<pre>"; print_r($annual_leaves); exit; 

$carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'2','branch_id'=>$branch_id))->row_array();
$earned_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'3','branch_id'=>$branch_id))->row_array();
$sck_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'4','branch_id'=>$branch_id))->row_array();
$hospiatality_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'5','branch_id'=>$branch_id))->row_array();

// $total_hosp_leave = $this->db->get_where('user_leaves',array('user_id'=>$this->session->userdata('user_id'),'leave_type'=>'5'))->result_array();

// $total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days'] + $earned_leaves['leave_days']);
$last_yr = date("Y",strtotime("-1 year"));
// echo $last_yr; exit;
$carry_days = $this->db->select_sum('leave_days')
					   ->from('dgt_user_leaves')
					   ->where('user_id',$this->session->userdata('user_id'))
					   ->like('leave_from',$last_yr)
					   ->like('leave_to',$last_yr)
					   ->get()->row()->leave_days;
$total_hosp_leave = $this->db->select_sum('leave_days')
							   ->from('dgt_user_leaves')
							   ->where('user_id',$this->session->userdata('user_id'))
							   ->where('leave_type','5')
							   ->where('status','1')
							   ->get()->row()->leave_days;
					   // echo $this->db->last_query(); exit;

$last_yr_leaves = $this->db->get_where('yearly_leaves',array('years'=>$last_yr))->row_array();
if(count($last_yr_leaves) != 0 )
{
	$l = json_decode($last_yr_leaves['total_leaves'],TRUE);

	$lst_anu_leaves = $l['annual_leaves'];
	$lst_cr_leaves = $l['cr_leaves'];
	$last_total = $lst_anu_leaves +  $lst_cr_leaves;
	if($carry_days != '')
	{
		$bl_leaves = $carry_days - $last_total; 
	}else{
		$bl_leaves = 0; 
	}
	// echo $bl_leaves; exit;
	if($bl_leaves < 0){			
		$ext_leaves = abs($bl_leaves);
	}else{
		$ext_leaves = 0;
	}
	if($ext_leaves == $carry_leaves['leave_days'])
	{
		$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
	}
	if($ext_leaves > $carry_leaves['leave_days'])
	{
		$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
	}
	if($ext_leaves < $carry_leaves['leave_days']){
		$total_count = ($annual_leaves['leave_days'] + $ext_leaves);
	}
	if($ext_leaves == 0)
	{
		$total_count = $annual_leaves['leave_days'];
	}


}else{
	$total_count = $annual_leaves['leave_days'];
}

	// echo $total_count; exit;
// $user_id = $this->session->userdata('user_id');
$user_id =$_POST['user_id'];
		  		$total_leaves = array();
		  		$normal_leaves = array();
		  		$medical_leaves = array();
		  		$sick_leaves = array();
		  		$leaves1 = $this->Leaves_model->check_leavesById($user_id);
		  		$nor_leaves = $this->Leaves_model->check_leavesBycat($user_id,'1');
		  		$med_leaves = $this->Leaves_model->check_leavesBycat($user_id,'2');
		  		$sick_leav = $this->Leaves_model->check_leavesBycat($user_id,'4');
		  		$sk_leaves = $this->Leaves_model->check_leavesBycat($user_id,'3');
		  		for($i=0;$i<count($leaves1);$i++)
		  		{
		  			$total_leaves[] = $leaves1[$i]['leave_days'];
		  		}
		  		foreach($nor_leaves as $n_leave)
		  		{
		  			$normal_leaves[] = $n_leave['leave_days'];
		  		}
		  		foreach($med_leaves as $md_leave)
		  		{
		  			$medical_leaves[] = $md_leave['leave_days'];
		  		}
		  		foreach($sk_leaves as $sk_leave)
		  		{
		  			$sick_leaves[] = $sk_leave['leave_days'];
		  		}
		  		foreach($sick_leav as $sick_lea)
		  		{
		  			$all_sick_leaves[] = $sick_lea['leave_days'];
		  		}

		  		$t_leaves = array_sum($total_leaves);
		  		$total_normal_leaves = $this->db->get_where('leave_types',array('id'=>1))->row_array();
		  		$lop = ($t_leaves - $total_normal_leaves['leave_days']);
		  		if($lop > 0 )
		  		{
		  			$lop_days = $lop;
		  		}else{
		  			$lop_days = 0;
		  		}

		  		$re_leaves = (12 - $t_leaves);

		  		$an_leaves        = array();
		  		$crfd_leaves      = array();
		  		$ernd_leaves      = array();
		  		$anu_leaves       = $this->Leaves_model->check_leavesBycat($user_id,'1');
		  		$cr_leaves 		  = $this->Leaves_model->check_leavesBycat($user_id,'2');
		  		$er_leaves 		  = $this->Leaves_model->check_leavesBycat($user_id,'3');
		  		foreach($anu_leaves as $anu_leave)
		  		{
		  			$an_leaves[] = $anu_leave['leave_days'];
		  		}
		  		foreach($cr_leaves as $cr_leave)
		  		{
		  			$crfd_leaves[] = $cr_leave['leave_days'];
		  		}
		  		foreach($er_leaves as $er_leave)
		  		{
		  			$ernd_leaves[] = $er_leave['leave_days'];
		  		}

		  		// $tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves) + array_sum($ernd_leaves));
		  		$tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves));

		  		$tot_sk_leaves = array_sum($all_sick_leaves)?array_sum($all_sick_leaves):'0';


		  		$extra_leaves = $this->db->get_where('assigned_policy_user',array('user_id'=>$user_id));

		  		$extra_policy_leaves = array();
		  		$all_extra_policy_leaves = array();

		  		foreach ($extra_leaves->result_array() as $extra) {
		  			$extra_days = $this->db->get_where('custom_policy',array('policy_id'=>$extra['policy_id']))->row_array();
		  			$extra_policy_leaves[] = $extra_days['policy_leave_days'];
		  		}

		  		$user_detail = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();

		  		$maternity_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'6'))->row_array();
		  		$paternity_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'7'))->row_array();



		  		$total_maternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$_POST['user_id'])
												  ->where('leave_type','6')
												  ->where('status','1')
												  ->get()->row()->leave_days;


		  		$total_paternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$_POST['user_id'])
												  ->where('leave_type','7')
												  ->where('status','1')
												  ->get()->row()->leave_days;



		  		$cr_yr = date('Y');
		  		$total_user_leaves = $this->db->select_sum('leave_days')
									   ->from('dgt_user_leaves')
									   ->where('user_id',$_POST['user_id'])
									   ->where('status','1')
									   ->where('leave_type','1')
									   ->like('leave_from',$cr_yr)
									   ->like('leave_to',$cr_yr)
									   ->get()->row()->leave_days;

				if($extra_leaves->num_rows() != 0){
					$total_count = ($total_count + array_sum($extra_policy_leaves));
				}


				$sk_lops = ($sck_leaves['leave_days'] - $tot_sk_leaves);
				if($sk_lops < 0 )
				{
					$sick_lop = abs($sk_lops);
				}else{
					$sick_lop = 0;
				}
				$tot_anu_count = ($total_count - $total_user_leaves);
				if($tot_anu_count < 0 )
				{
					$anu_lop = abs($tot_anu_count);
				}else{
					$anu_lop = 0;
				}
				$tot_hosp_count = ($hospiatality_leaves['leave_days'] - $total_hosp_leave);
				if($tot_hosp_count < 0 )
				{
					$hosp_lop = abs($tot_hosp_count);
				}else{
					$hosp_lop = 0;
				}

				$total_lop = ($anu_lop + $sick_lop + $hosp_lop);


				// Maternity Leave Conditions..

			$doj = $user_detail['doj'];
			$cr_date = date('Y-m-d');

			$ts1 = strtotime($doj);
			$ts2 = strtotime($cr_date);
			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);
			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);
			$job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);
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
            <?php  $report_name = lang('leave_report');?>
            <button class="btn  btn-primary pull-right" onclick="attendance_report_excel('<?php echo $report_name;?>','attendance_report_excel');"> <span ><i class="fa fa-file-excel-o" aria-hidden="true"></i></span> <span><?=lang('excel')?></span> </button>
             
    </div>

    <div class="panel-body">
      <div class="fill body reports-top rep-new-band">
        <div class="criteria-container fill-container hidden-print">
          <div class="criteria-band">
            <address class="row">
              <form method="post" action="">
              <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
                <div class="col-md-3">
                  <label><?=lang('branches')?> </label>
	
                  <select required  class="select2-option form-control" style="width:100%" name="branch_id" id="branchreportattendance" >
                    <option value="" disabled selected>Choose Entity</option>
                    <optgroup label="<?=lang('all_branches')?>">
                      <?php 
                      
                       if(!empty($all_branches)){
                      foreach ($all_branches as $c){ ?>
                      <option value="<?=$c['branch_id']?>" <?php if($branch_id == $c['branch_id']){ echo "selected"; } ?> ><?=ucfirst($c['branch_name'])?></option>
                      <?php } }  ?>
                    </optgroup>
                  </select>
                </div>
               <div class="col-md-3">
                <label>Employee</label>
                <select class="select2-option form-control" name="user_id" id="reportattendanceusers">
                <option value="" <?php //if(empty($_POST['user_id'])){ echo 'selected';}?>>All Employees</option>
                  <?php if(($user_id != '') && ($users == '')){ 
                    $user_details = $this->db->select('*')
                                             ->from('users U')
                                             ->join('account_details AD','U.id = AD.user_id')
                                             ->where('AD.user_id',$user_id)
                                             ->get()->row_array();
                  ?>
                    <option value="<?php echo $user_details['user_id']; ?>" <?php if(!empty($_POST['user_id'])){ echo 'selected';}?>><?php echo $user_details['fullname']; ?></option>
                  <?php }elseif(!empty($users)){ 
                    foreach($users as $user){
                    ?>
                    <option value="<?php echo $user['user_id']; ?>" <?php if($_POST['user_id']==$user['user_id']){ echo 'selected';}?>><?php echo $user['fullname']; ?></option>
                  <?php } } ?>
                </select>
              </div>
              <?php }
              else{
              ?>
                <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
                <input type="hidden" value="<?php  echo $this->session->userdata('user_id');?>" name="user_id">
            <?php }?>
             <div class="<?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>"">
                <label><?=lang('month')?> </label>
               <select class="select floating form-control" id="leave_month" name="leave_month">  
               <option value="" <?php if(empty($_POST['leave_month'])){ echo 'selected';}?>>All Months</option>
                <?php 
                  for ($ji=1; $ji <=12 ; $ji++) {  
                    $sele1='';
                    if(isset($_POST['leave_month']) && !empty($_POST['leave_month']))
                    {
                      if($_POST['leave_month']==$ji)
                      {
                        $sele1='selected';
                      }
                    }
                    ?>
                  <option value="<?php echo $ji; ?>" <?php echo $sele1;?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>    
                  <?php } ?>                
              </select>
              </div>

              <div class="<?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>"">
                <label><?=lang('year')?> </label>
                 <select class="select floating form-control" id="leave_year" name="leave_year"> 
                  <?php for($k =$e_year;$k>=2015;$k--){ 
                    $sele2='';
                     if(isset($_POST['leave_year']) && !empty($_POST['leave_year']))
                    {
                      if($_POST['leave_year']==$k)
                      {
                        $sele2='selected';
                      }
                    }
                    ?>
                  <option value="<?php echo $k; ?>" <?php echo $sele2;?> ><?php echo $k; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="<?php echo ($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1)? 'col-md-2':'col-md-4';?>"">  
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
            <h3 class="reports-headerspacing"><?=lang('leave_bal_report')?></h3>
            <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?>
        </div>
        <?php if($this->input->post()){/*?>
          <table class="report_table table table-striped custom-table m-b-0" >
            <thead >
              <tr>
                <th class="report_col"><b><?=lang('leave_type')?></b></th>
                <th class="report_col"><b><?=lang('num_days')?></b></th>
              </tr>
            </thead>  
            <tbody>
            <?php if(!empty($leave_types)){
                foreach($leave_types as $leave_type1){
					
					$get_leaves_count = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>$leave_type1['leave_type_id'],'branch_id'=>$branch_id))->row_array();
                ?>
                  <tr>
                    <td class="report_col"><b><?php echo  $leave_type1['leave_type'];?></b></td>
                    <td class="report_col"><b><?php echo (!empty($leaves[$leave_type1['leave_id']]))?$leaves[$leave_type1['leave_id']]:'0';?> / <?=$get_leaves_count['leave_days']?> <?php //$total_count?></b></td>
                  </tr>
                <?php }
            }?>
            </tbody>
          </table> */ ?>
          <div class="fill-container">
            <div class="col-md-12" style="overflow-x:scroll">                  
                <table id="task_report" class="table table-striped custom-table m-b-0">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Emp Id</th>
                        <th>Employee</th>
						<th>Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        
                        <?php /*<th>Absent</th>*/?>
                        <?php if(!empty($leave_types)){
                          foreach($leave_types as $leave_type1){
                          ?>
                            <th style="text-align:center"><?php echo ucfirst($leave_type1['leave_type']);?>
                            <table border="1" style="border:none">
                              <tr>
                                <th style="padding:20px">Eligible</th>
                                <th style="padding:20px">Booked</th>
                                <th style="padding:20px">Available</th>
                                  
                              </tr>
                            </table>
                          </th>
                          <?php 
                          }
                        }
                        ?>
                        <th style="text-align:center">
                            <table border="1" style="border:none">
                              <tr>
                                  <th style="padding:20px">Eligible</th>
                                  <th style="padding:20px">Booked</th>
                                  <th style="padding:20px">Available</th>
                                  
                                  
                              </tr>
                            </table>
                          </th>
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
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
    $('#task_report').DataTable( {
		 "columns": [
             { data: "s_no" },
             { data: "employee_code" },
             { data: "employee" },
			 { data: "email" },
             { data: "department" },
             { data: "designation" },
             
            <?php /* { data: "absent" },*/?>
             <?php if(!empty($leave_types)){
                foreach($leave_types as $leave_type1){
                ?>
                  { data: "<?php echo str_replace(' ', '_', $leave_type1['leave_type']);?>",
                    "render": function (data, type, row, meta) {
                        return '<div class="col-md-12 text-center"><div class="col-md-4 text-center">'+data.total + '</div><div class="col-md-4 text-center">'+data.booked+'</div><div class="col-md-4 text-center">'+data.available+'</div></div>';
                    }
                  
                  },
                  
                  
                <?php 
                }
              }
              ?>
              { data: "total",
                  "render": function (data, type, row, meta) {
                      return '<div class="col-md-12 text-center"><div class="col-md-4 text-center">'+data.total + '</div><div class="col-md-4 text-center">'+data.booked+'</div><div class="col-md-4 text-center">'+data.available+'</div></div>';
                  }
                
                },
          ],
        "processing": true,
        "serverSide": true,
       "aLengthMenu": [
            [10,25, 50, 100, 200, -1],
            [10,25, 50, 100, 200, "All"]
        ],
        "ajax": "<?php echo base_url().'reports/ajax_leave_report?branch_id='.$branch_id.'&user_id='.$user_id.'&leave_month='.$leave_month.'&leave_year='.$leave_year;?>"
    } );
} );
	</script>
<style>
.report_table{
  width:100%
}
.report_col {
    padding-top: 10px;
}
td{
  text-align:center;
}
td:nth-child(2) { text-align: left; }
td:nth-child(3) { text-align: left; }
td:nth-child(4) { text-align: left; }
th.sorting {
    white-space: nowrap;
}
</style>