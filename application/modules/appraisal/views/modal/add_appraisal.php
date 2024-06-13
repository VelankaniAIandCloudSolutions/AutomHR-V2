<!--Add Performance Appraisal Modal -->
<style>
  .select2-results__options > li:first-child{
   display: none;
 }
 .select2-results__message{
  display:block !important;
}
</style>
<div class="modal-dialog modal-lg  modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><?php echo lang('give_performance_appraisal'); ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

      <form action="<?php echo site_url('appraisal/add_appraisal')?>" method="post" id="add_appraisal" enctype="multipart/form-data" name="add_appraisal">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label><?php echo lang('employee');?> <span class="text-danger">*</span></label>
              <select class="select2-option form-control" name="employee_id" style="width:100%;" required onchange="select_indicators(this.value)" >
                          <?php foreach($employees as $emp) { ?>
                          <option value="<?php echo $emp->user_id;?>"><?php echo $emp->fullname;?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label><?php echo 'Review Date';?> <span class="text-danger">*</span></label>
                        <div class="cal-icon"><input class="form-control datetimepicker" type="date" name="appraisal_date" required value="<?php echo date('Y-m-d');?>"></div>
                      </div>

                      <div class="form-group">
                       <label for="dateRange">Review Period</label>
                       <input type="date" id="startDate" name="startDate" required>
                       <span> to </span>
                       <input type="date" id="endDate" name="endDate" required>
                     </div>
                   </div>
                   <div class="col-sm-12">
                    <div class="" id="card_details">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label class="">Ratting Scale</label>
                          <span>1 = Poor, 2 = Fair, 3 = Good, 4 = Very Good, 5 = Excellent</span>
                        </div>
                      </div>

                      <div class="tab-box">
                        <div class="row user-tabs">
                          <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                            <ul class="nav nav-tabs nav-tabs-solid">
                              <?php
                              foreach($indicator_names['technical'] as $key =>$values){
                                $tmp_key1 = str_replace(' ', '_', $key); 
                                ?>
                                <li class="nav-item"><a href="#<?php echo $tmp_key1;?>" data-toggle="tab" class="nav-link active" onclick="hide_div('<?php echo $tmp_key1; ?>'); return false;" ><?php echo $key; ?></a></li>
                              <?php }
                              ?>
                            </ul>
                          </div>
                        </div>
                      </div>

                      <div id="Quality_Of_Work" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="bg-white table-responsive">
                              <table class="table">
                                <tbody>
                                  <tr>
                                    <th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
                                    <th style="font-weight: 500;"><?php echo lang('set_value');?></th>
                                  </tr>
                                  <?php
                                  foreach($indicator_names['technical'] as $key =>$values){
                                    $change_key = str_replace(" ","_",$key);
                                    if($change_key == 'Quality_Of_Work')
                                    {
                                     foreach($values  as $values_key => $tmp_val){
                                      ?> 

                                      <tr>
                                        <td scope="row" colspan="2"><?php echo $tmp_val;?></td>
                                        <td style="max-width:150px">
                                          <select name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
                                            <option value="1"> 1</option>
                                            <option value="2"> 2</option>
                                            <option value="3"> 3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                          </select>
                                        </td>
                                      </tr>
                                    <?php } } } ?>

                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div id="Work_Habits" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
                          <div class="row">
                            <div class="col-sm-12">
                              <div class="bg-white table-responsive">
                                <table class="table">

                                  <tbody>
                                    <tr>
                                      <th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
                                      <th style="font-weight: 500;"><?php echo lang('set_value');?></th>
                                    </tr>
                                    <?php
                                    foreach($indicator_names['technical'] as $key =>$values){
                                      $change_key = '';
                                      $change_key = str_replace(" ","_",$key);
                                      if($change_key == "Work_Habits")
                                      {
                                       foreach($values as $values_key => $tmp_val){ ?> 

                                        <tr>
                                          <td scope="row" colspan="2"><?php echo $tmp_val;?></td>
                                          <td style="max-width:150px">
                                            <select name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
                                              <option value="1"> 1</option>
                                              <option value="2"> 2</option>
                                              <option value="3"> 3</option>
                                              <option value="4">4</option>
                                              <option value="5">5</option>
                                            </select>
                                          </td>
                                        </tr>
                                      <?php } } } ?>


                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div id="Job_Knowledge" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="bg-white table-responsive">
                                  <table class="table">

                                    <tbody>
                                      <tr>
                                        <th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
                                        <th style="font-weight: 500;"><?php echo lang('set_value');?></th>
                                      </tr>
                                      <?php
                                      foreach($indicator_names['technical'] as $key =>$values){
                                        $change_key = '';
                                        $change_key = str_replace(" ","_",$key);
                                        if($change_key == "Job_Knowledge")
                                        {
                                         foreach($values as $values_key => $tmp_val){ ?> 

                                          <tr>
                                            <td scope="row" colspan="2"><?php echo $tmp_val;?></td>
                                            <td style="max-width:150px">
                                              <select name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
                                                <option value="1"> 1</option>
                                                <option value="2"> 2</option>
                                                <option value="3"> 3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                              </select>
                                            </td>
                                          </tr>
                                        <?php } } } ?>


                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>


                            <div id="Teamwork" class="pro-overview tab-pane fade show active div_hide_css" style="display:none;!important">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="bg-white table-responsive">
                                    <table class="table">

                                      <tbody>
                                        <tr>
                                          <th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
                                          <th style="font-weight: 500;"><?php echo lang('set_value');?></th>
                                        </tr>
                                        <?php
                                        foreach($indicator_names['technical'] as $key =>$values){

                                          if($key == "Teamwork")
                                          {
                                            $change_key = $key;
                                           foreach($values as $values_key => $tmp_val){ ?> 

                                            <tr>
                                              <td scope="row" colspan="2"><?php echo $tmp_val;?></td>
                                              <td style="max-width:150px">
                                                <select name="<?php echo $change_key;?>[<?php echo $values_key;?>]" class="form-control">
                                                  <option value="1"> 1</option>
                                                  <option value="2"> 2</option>
                                                  <option value="3"> 3</option>
                                                  <option value="4">4</option>
                                                  <option value="5">5</option>
                                                </select>
                                              </td>
                                            </tr>
                                          <?php } } } ?>


                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>


                            </div>

                          </div>

                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class=""><?php echo 'Appraisee Comments'?></label>
                              <textarea class="col-md-12" name='Appraisee_Comments' id='Appraisee_Comments'></textarea>
                            </div>
                          </div>
                          <?php 
                          $user_role = $this->session->userdata("role_id");
                          if($user_role == 1)
                            {?>
                           <div class="col-sm-12">
                            <div class="form-group">
                              <label class=""><?php echo 'Appraiser Comments'?></label>
                              <textarea class="col-md-12" name='Appraiser_Comments' id='Appraiser_Comments'></textarea>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class=""><?php echo 'HOD Remarks'?></label>
                              <textarea class="col-md-12" name='HOD_Remarks' id='HOD_Remarks'></textarea>
                            </div>
                          </div>

                            <div class="col-sm-12">
                            <div class="form-group">
                               <label class=""><?php echo 'Manager Signature';?></label>
                              <input type="file" name="manager_signature" id ="manager_signature"  required />
                            </div>
                          </div>

                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class=""><?php echo 'HOD Signature';?></label>
                              <input type="file" name="hod_signature" id ="hod_signature"  required />
                            </div>
                          </div>

                          <?php } ?>

                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class=""><?php echo lang('status')?></label>
                              <select class="select form-control" name="status" required>
                                <option value="1"><?php echo lang('active') ?></option>
                                <option value="0"><?php echo lang('inactive')?></option>
                              </select>
                            </div>
                          </div>


                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class=""><?php echo 'Employee Signature';?></label>
                              <input type="file" name="employee_signature" id ="employee_signature"  required />
                            </div>
                          </div>

                        

                          <div class="col-sm-12">
                            <div class="form-group">
                              <input type="checkbox" name="Accepted" id ="Accepted"  required /  >&nbsp;&nbsp;Accept
                            </div>
                          </div>

                        </div>

                        <div class="submit-section">
                          <button type="submit" name="submit" id="add_appraisal_btn"class="btn btn-primary submit-btn" onclick="appraisal_from();"><?php echo lang('submit')?></button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- /Add Performance Appraisal Modal-->
                <script>
                 $(".select2-option").select2({
                  minimumInputLength: 3,
                  tags: [],
                  ajax: {
                    url: "<?php echo base_url('appraisal/getEmployees');?>",
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 2,
                    data: function (term) {
                      return {
                        term: term
                      };
                    },
                    processResults: function (data) {

                      return {
                        results: $.map(data, function (item) {
                          return {
                            text: item.fullname+' ('+item.email+')',
                            slug: item.email,
                            id: item.id
                          }
                        })
                      };
                    }
                  }
                });
                 function hide_div(div_id)
                 {
                  $(".div_hide_css").hide();
                  $("#"+div_id).show();
                }
              </script>