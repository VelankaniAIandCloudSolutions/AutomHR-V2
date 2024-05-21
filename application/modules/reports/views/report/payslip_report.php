<?php 
$cur = App::currencies(config_item('default_currency')); 
$task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
$p_month = (isset($month)) ? $month : '0';
$p_year = (isset($year)) ? $year : '0';
if((isset($user_id)) ? $user_id : '' != '')
{
$user_id = (isset($user_id)) ? $user_id : '0';
}
else
{
  $user_id = '0';
}
$company_id = (isset($company_id)) ? $company_id : '0';
// print_r($payslip);exit;
if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){
  $post_branch_id = $branch_id;
} else {
  $post_branch_id = $this->session->userdata('branch_id');
}
?>

<div class="content">
  <section class="panel panel-white">
            
    <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($payslip)> 0 ){ ?>
              <a href="<?=base_url()?>reports/payslippdf/<?=$user_id;?>/<?=$p_month;?>/<?=$p_year;?>/<?= $post_branch_id ?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>              
            <?php } ?>
            <button class="btn  btn-primary pull-right" onclick="export_report('task_report','payslip');" > <span ><i class="fa fa-file-excel-o m-r-5" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
             
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
            <?php echo form_open(base_url().'reports/view/payslip_report'); ?>

            <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
              <div class="col-md-3">
                <label><?=lang('branches')?> </label>
                <select  <?=$dis?>  class="form-control" name="branch_id" id="branchreportpayslip"><!---->
                  <option value="0"><?=lang('all_branches')?></option>
                    <?php 
                      // $all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
                      // echo "<pre>"; print_r($all_branches); exit;
                      if(!empty($all_branches)){
                        foreach($all_branches as $branch){ ?>
                          <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch_id == $branch['branch_id']){ echo "selected"; } ?> ><?php echo $branch['branch_name']; ?></option>
                    <?php 
                        }
                      }
                    ?>
                </select>
              </div>
              <div class="col-md-3">
                <label><?=lang('employees')?> </label>
                <?php
                if($this->session->userdata('user_id') !=1){
                  $all_employees = $this->db->select('U.*,AD.fullname')
                  ->from('users U')
                  ->join('account_details AD','U.id = AD.user_id',LEFT)
                  ->where('U.role_id',3)
                  ->where('AD.branch_id',$this->session->userdata('branch_id'))
                  ->get()->result_array();
                }
                ?>
                <select class="form-control" style="width:100%;" name="user_id" id="all_payroleemployees">
                <option value="" selected disabled>Employee</option>
                <?php if(!empty($all_employees)){ 
                    foreach($all_employees as $emp) { ?>
                      <option value="<?php echo $emp['id']; ?>" <?php if($user_id == $emp['id']){ echo "selected"; } ?>><?php echo $emp['fullname']; ?></option>
                    <?php } } ?>
                </select>
              </div>
              <?php }
            else{
            ?>
              <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
              <input type="hidden" value="<?php  echo $this->session->userdata('user_id');?>" name="user_id">
          <?php }?>
              

              <div class="col-md-2">
                <label><?=lang('month')?> </label>
                <select class="select2-option form-control" name="month" >
                    <optgroup label="<?=lang('month')?>">
                      <option value="0" <?php if($month == 0) { echo 'selected=selected'; }?>>All</option>
                      <option value="1" <?php if($month == 1) { echo 'selected=selected'; }?>>January</option>
                      <option value="2" <?php if($month == 2) { echo 'selected=selected'; }?>>Febrary</option>
                      <option value="3" <?php if($month == 3) { echo 'selected=selected'; }?>>March</option>
                      <option value="4" <?php if($month == 4) { echo 'selected=selected'; }?>>April</option>
                      <option value="5" <?php if($month == 5) { echo 'selected=selected'; }?>>May</option>
                      <option value="6" <?php if($month == 6) { echo 'selected=selected'; }?>>June</option>
                      <option value="7" <?php if($month == 7) { echo 'selected=selected'; }?>>July</option>
                      <option value="8" <?php if($month == 8) { echo 'selected=selected'; }?>>August</option>
                      <option value="9" <?php if($month == 9) { echo 'selected=selected'; }?>>September</option>
                      <option value="10" <?php if($month == 10) { echo 'selected=selected'; }?>>October</option>
                      <option value="11" <?php if($month == 11) { echo 'selected=selected'; }?>>November</option>
                      <option value="12" <?php if($month == 12) { echo 'selected=selected'; }?>>December</option>
                    </optgroup>
                </select>
              </div>

               <div class="col-md-2">
                <label><?=lang('year')?> </label>
                <select class="select2-option form-control"  name="year" >
                    <optgroup label="<?=lang('year')?>">
                       <option value="0" <?php if($month == 0) { echo 'selected=selected'; }?>>All</option>
                       <?php for ($i = date('Y'); $i >= 2015; $i--) { ?>
                         <option value="<?php echo $i; ?>"  <?php if($year == $i) { echo 'selected=selected'; }?>><?php echo $i; ?></option>
                        <?php } ?>
                      <!-- <option value="2015" <?php if($year == 2015) { echo 'selected=selected'; }?>>2015</option>
                      <option value="2016" <?php if($year == 2016) { echo 'selected=selected'; }?>>2016</option>
                      <option value="2017" <?php if($year == 2017) { echo 'selected=selected'; }?>>2017</option>
                      <option value="2018" <?php if($year == 2018) { echo 'selected=selected'; }?>>2018</option>
                      <option value="2019" <?php if($year == 2019) { echo 'selected=selected'; }?>>2019</option> -->
                      
                      
                       
                      </optgroup>
                </select>
              </div>
 
                


              <div class="col-md-2">  
                <button class="btn btn-success" type="submit">
                  <?=lang('run_report')?>
                </button>
              </div>



            </address>
          </div>
        </div>


        <?php  form_close(); ?>

        <div class="rep-container">
          <div class="page-header text-center">
            <h3 class="reports-headerspacing"><?=lang('payslip_report')?></h3>
            <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?>
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <th style="width:5px; display:none;"></th>
                    <th><b><?=lang('employee_name')?></b></th>  
                    <th><b><?=lang('paid_amount')?></b></th>
                    <th><b><?=lang('payment_month')?></b></th>
                    <th><b><?=lang('payment_year')?></b></th> 
                   
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($payslip as $key => $p) { 


                     $name = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array(); 
                     $users = $this->db->get_where('users',array('id'=>$p['user_id']))->row_array();
                     $payment_amount = $this->db->get_where('bank_statutory',array('user_id'=>$p['user_id']))->row_array();

                     if($p['p_month'] == 1)
                    {
                      $month = 'January';
                      
                    }elseif($p['p_month'] == 2){
                     $month = 'Febrary';
                      
                    }
                    elseif($p['p_month'] == 3){
                     $month = 'March';
                      
                    }
                    elseif($p['p_month'] == 4){
                     $month = 'April';
                      
                    }
                    elseif($p['p_month'] == 5){
                     $month = 'May';
                      
                    }
                    elseif($p['p_month'] == 6){
                     $month = 'June';
                      
                    }
                    elseif($p['p_month'] == 7){
                     $month = 'July';
                      
                    }
                    elseif($p['p_month'] == 8){
                     $month = 'August';
                      
                    }
                    elseif($p['p_month'] == 9){
                     $month = 'September';
                      
                    }
                    elseif($p['p_month'] == 10){
                     $month = 'October';
                      
                    }
                    elseif($p['p_month'] == 11){
                     $month = 'November';
                      
                    }
                    elseif($p['p_month'] == 12){
                     $month = 'December';
                      
                    }

                                                            
                  ?> 
                  <tr >
                    
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['user_id']?>">
                        <?=$name['fullname']?>
                      </a>
                    
                    </td>
                    <td><?php echo $payment_amount['salary']?></td>
                    <td><?php echo $month?></td>
                    <td><?php echo $p['p_year']?></td>
                    

                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>    