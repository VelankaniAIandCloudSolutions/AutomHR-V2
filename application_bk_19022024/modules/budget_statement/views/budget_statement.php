
      <div class="content container-fluid">

          <div class="row">
            <div class="col-xs-12">
              <h4 class="page-title">Budget Statement</h4>
            </div>
          </div>
          <div class="budget-tabs">           
            <ul class="nav nav-tabs tabs nav-tabs-bottom nav-justified">
              <?php   $start_years = Date('Y', strtotime("-3 Year"));
                 $current_year = date("Y");
                    for ($i=$start_years; $i <=$current_year ; $i++) { ?>
                     <li class="<?php echo ($i == $current_year )?'active':'';?>"><a data-toggle="tab" href="#statement_<?php echo $i;?>"><?php echo $i;?></a></li>
              <?php }?>
            <!--   <li><a data-toggle="tab" href="#statement_2016">2016</a></li>
              <li><a data-toggle="tab" href="#statement_2017">2017</a></li>
              <li><a data-toggle="tab" href="#statement_2018">2018</a></li>
              <li class="active"><a data-toggle="tab" href="#statement_2019">2019</a></li> -->
            </ul>
          </div>
        <div class="tab-content">           
            
           <?php  for ($i=$start_years; $i <=$current_year ; $i++) { ?>
            <div id="statement_<?php echo $i;?>" class="tab-pane <?php echo ($i == $current_year )?'active':'';?>">
              <div class="card-box">
                <h4 class="card-title">Budget Statement <?php echo $i;?></h4>

                <input type="hidden" name="new_hidden_rows" id="new_hidden_rows" value="0">

                <table class="table table-hover accounts-table budget-table m-b-0 table-nowrap">
                  <thead>
                    <tr>

                      <th style="width: 250px;"></th>
                       <?php foreach($months as $month) {?>
                          <th><?php echo $month; ?></th>
                         <?php } ?>
                      <th>Overall</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="revenue_cont_<?php echo $i;?>">
                    <tr class="title-row">
                      <td colspan="10"><strong>Revenue</strong></td>
                      <td class="text-right" colspan="5"><button type="button" class="btn btn-info btn-xs add-new" id="revenue_add" data-year ="<?php echo $i;?>" onclick="revenue_add(<?php echo $i;?>)"><i class="fa fa-plus"></i> Add Revenue</button></td>
                    </tr>
                    <?php $this->db->select('*');
                          $this->db->where("year",$i);
                          $this->db->where("type","revenue");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $revenues = $this->db->get('budget_statement')->result_array();
                          $count = 0;
                          if(!empty($revenues)){
                            $count = 0;
                            foreach ($revenues as $revenue) {?>
                              <tr>
                                  <td data-name="budget_name" data-id="budget_name<?php echo $count.$i; ?>"><?php echo $revenue['budget_name'];?></td>
                                  <td data-name="jan" data-id="jan<?php echo $count.$i; ?>"><?php echo $revenue['jan'];?></td>
                                  <td data-name="feb" data-id="feb<?php echo $count.$i; ?>"><?php echo $revenue['feb'];?></td>
                                  <td data-name="mar" data-id="mar<?php echo $count.$i; ?>"><?php echo $revenue['mar'];?></td>
                                  <td data-name="apr" data-id="apr<?php echo $count.$i; ?>"><?php echo $revenue['apr'];?></td>
                                  <td data-name="may" data-id="may<?php echo $count.$i; ?>"><?php echo $revenue['may'];?></td>
                                  <td data-name="jun" data-id="jun<?php echo $count.$i; ?>"><?php echo $revenue['jun'];?></td>
                                  <td data-name="jul" data-id="jul<?php echo $count.$i; ?>"><?php echo $revenue['jul'];?></td>
                                  <td data-name="aug" data-id="aug<?php echo $count.$i; ?>"><?php echo $revenue['aug'];?></td>
                                  <td data-name="sep" data-id="sep<?php echo $count.$i; ?>"><?php echo $revenue['sep'];?></td>
                                  <td data-name="oct" data-id="oct_?php echo $count.$i; ?>"><?php echo $revenue['oct'];?></td>
                                  <td data-name="nov" data-id="nov<?php echo $count.$i; ?>"><?php echo $revenue['nov'];?></td>
                                  <td data-name="dece" data-id="dece<?php echo $count.$i; ?>"><?php echo $revenue['dece'];?></td>
                                  <td><strong><?php echo ($revenue['jan']+$revenue['feb']+$revenue['mar']+$revenue['apr']+$revenue['may']+$revenue['jun']+$revenue['jul']+$revenue['aug']+$revenue['sep']+$revenue['oct']+$revenue['nov']+$revenue['dece']);?></strong></td>
                                  <td class="text-right actionss">
                                    <a class="btn btn-info btn-xs add"  onclick="save_revenue(<?php echo $count?>,<?php echo $i?>,this)" data-type ="revenue" data-id ="<?php echo $revenue['id'];?>" id="revenue_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>
                                    <a class="btn btn-success btn-xs edit" id="revenue_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#revenue_delete<?php echo $revenue['id']?>" id="" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                                  </td>
                                <tr>
                                
                                <div id="revenue_delete<?php echo $revenue['id']?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content modal-md">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Delete Budeget Revenue( <?php echo $revenue['budget_name']?> )</h4>
                                    </div>
                                    <form method="post" action="<?php echo base_url(); ?>budget_statement/delete_budget_statement/<?php echo $revenue['id']; ?>">
                                      <div class="modal-body">
                                        <input type="hidden" class="form-control" value="<?php echo $revenue['id'];?>" name="id">
                                        <input type="hidden" class="form-control" value="<?php echo $revenue['budget_name'];?>" name="budget_name">
                                        <p>Are you sure want to delete this?</p>
                                        <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      <?php $counts = $count;
                         $count++;
                            }
                     }?>
                    <input type="hidden" name="new_hidden_rows" id="new_hidden_rows" value="<?php echo $counts;?>">
                    
                  </tbody>
                  <tbody>
                    <tr class="total-row">
                      <td><strong>Total Revenues</strong></td>
                      <?php $this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
                          $this->db->where("year",$i);
                          $this->db->where("type","revenue");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $total_revenue = $this->db->get('budget_statement')->row_array();?>
                      <td><strong><?php echo $total_revenue['jan']?></strong></td>
                      <td><strong><?php echo $total_revenue['feb']?></strong></td>
                      <td><strong><?php echo $total_revenue['mar']?></strong></td>
                      <td><strong><?php echo $total_revenue['apr']?></strong></td>
                      <td><strong><?php echo $total_revenue['may']?></strong></td>
                      <td><strong><?php echo $total_revenue['jun']?></strong></td>
                      <td><strong><?php echo $total_revenue['jul']?></strong></td>
                      <td><strong><?php echo $total_revenue['aug']?></strong></td>
                      <td><strong><?php echo $total_revenue['sep']?></strong></td>
                      <td><strong><?php echo $total_revenue['oct']?></strong></td>
                      <td><strong><?php echo $total_revenue['nov']?></strong></td>
                      <td><strong><?php echo $total_revenue['dece']?></strong></td>
                      <?php $total_revenues = ($total_revenue['jan']+$total_revenue['feb']+$total_revenue['mar']+$total_revenue['apr']+$total_revenue['may']+$total_revenue['jun']+$total_revenue['jul']+$total_revenue['aug']+$total_revenue['sep']+$total_revenue['oct']+$total_revenue['nov']+$total_revenue['dece']) ?>
                      <td><strong><?php echo $total_revenues; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>
                  <tbody id="expense_cont<?php echo $i;?>">
                    <tr class="title-row">
                      <td colspan="10"><strong>Expenses</strong></td>
                      <td class="text-right" colspan="5"><button type="button" class="btn btn-info btn-xs add-new" id="expense_add" data-year ="<?php echo $i;?>" onclick="expense_add(<?php echo $i;?>)"><i class="fa fa-plus"></i> Add Expense</button></td>
                    </tr>
                    <?php $this->db->select('*');
                          $this->db->where("year",$i);
                          $this->db->where("type","expense");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $expenses = $this->db->get('budget_statement')->result_array();
                          $expense_count = 0;
                          if(!empty($expenses)){
                           // $count = 0;
                            foreach ($expenses as $expense) {?>
                              <tr>
                                  <td data-name="budget_name" data-id="expense_budget_name<?php echo $expense_count.$i; ?>"><?php echo $expense['budget_name'];?></td>
                                  <td data-name="jan" data-id="expense_jan<?php echo $expense_count.$i; ?>"><?php echo $expense['jan'];?></td>
                                  <td data-name="feb" data-id="expense_feb<?php echo $expense_count.$i; ?>"><?php echo $expense['feb'];?></td>
                                  <td data-name="mar" data-id="expense_mar<?php echo $expense_count.$i; ?>"><?php echo $expense['mar'];?></td>
                                  <td data-name="apr" data-id="expense_apr<?php echo $expense_count.$i; ?>"><?php echo $expense['apr'];?></td>
                                  <td data-name="may" data-id="expense_may<?php echo $expense_count.$i; ?>"><?php echo $expense['may'];?></td>
                                  <td data-name="jun" data-id="expense_jun<?php echo $expense_count.$i; ?>"><?php echo $expense['jun'];?></td>
                                  <td data-name="jul" data-id="expense_jul<?php echo $expense_count.$i; ?>"><?php echo $expense['jul'];?></td>
                                  <td data-name="aug" data-id="expense_aug<?php echo $expense_count.$i; ?>"><?php echo $expense['aug'];?></td>
                                  <td data-name="sep" data-id="expense_sep<?php echo $expense_count.$i; ?>"><?php echo $expense['sep'];?></td>
                                  <td data-name="oct" data-id="expense_oct<?php echo $expense_count.$i; ?>"><?php echo $expense['oct'];?></td>
                                  <td data-name="nov" data-id="expense_nov<?php echo $expense_count.$i; ?>"><?php echo $expense['nov'];?></td>
                                  <td data-name="dece" data-id="expense_dece<?php echo $expense_count.$i; ?>"><?php echo $expense['dece'];?></td>
                                  <td><strong><?php echo ($expense['jan']+$expense['feb']+$expense['mar']+$expense['apr']+$expense['may']+$expense['jun']+$expense['jul']+$expense['aug']+$expense['sep']+$expense['oct']+$expense['nov']+$expense['dece']);?></strong></td>
                                  <td class="text-right actionss">
                                    <a class="btn btn-info btn-xs add"  onclick="save_expense(<?php echo $expense_count?>,<?php echo $i?>,this)" data-type ="expense" data-id ="<?php echo $expense['id'];?>" id="expense_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>
                                    <a class="btn btn-success btn-xs edit" id="expense_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#expense_delete<?php echo $expense['id']?>" id="" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                                  </td>
                                <tr>
                                
                                <div id="expense_delete<?php echo $expense['id']?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content modal-md">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Delete Budeget Expense( <?php echo $expense['budget_name']?> )</h4>
                                    </div>
                                    <form method="post" action="<?php echo base_url(); ?>budget_statement/delete_budget_statement/<?php echo $expense['id']; ?>">
                                      <div class="modal-body">
                                        <input type="hidden" class="form-control" value="<?php echo $expense['id'];?>" name="id">
                                        <input type="hidden" class="form-control" value="<?php echo $expense['budget_name'];?>" name="budget_name">
                                        <p>Are you sure want to delete this?</p>
                                        <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      <?php $expense_counts = $expense_count;
                         $expense_count++;
                            }
                     }?>
                    <input type="hidden" name="expense_new_hidden_rows" id="expense_new_hidden_rows" value="<?php echo $expense_counts;?>">
                  </tbody>
                  <tbody>
                    <tr class="total-row">
                      <td><strong>Total Expenses</strong></td>
                      <?php $this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
                          $this->db->where("year",$i);
                          $this->db->where("type","expense");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $total_expense = $this->db->get('budget_statement')->row_array();?>
                      <td><strong><?php echo $total_expense['jan']?></strong></td>
                      <td><strong><?php echo $total_expense['feb']?></strong></td>
                      <td><strong><?php echo $total_expense['mar']?></strong></td>
                      <td><strong><?php echo $total_expense['apr']?></strong></td>
                      <td><strong><?php echo $total_expense['may']?></strong></td>
                      <td><strong><?php echo $total_expense['jun']?></strong></td>
                      <td><strong><?php echo $total_expense['jul']?></strong></td>
                      <td><strong><?php echo $total_expense['aug']?></strong></td>
                      <td><strong><?php echo $total_expense['sep']?></strong></td>
                      <td><strong><?php echo $total_expense['oct']?></strong></td>
                      <td><strong><?php echo $total_expense['nov']?></strong></td>
                      <td><strong><?php echo $total_expense['dece']?></strong></td>
                      <?php $total_expenses = ($total_expense['jan']+$total_expense['feb']+$total_expense['mar']+$total_expense['apr']+$total_expense['may']+$total_expense['jun']+$total_expense['jul']+$total_expense['aug']+$total_expense['sep']+$total_expense['oct']+$total_expense['nov']+$total_expense['dece']) ?>
                      <td><strong><?php echo $total_expenses; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td><strong>Earnings Before Tax</strong></td>
                      <?php 
                        $profit = array();
                        foreach ($total_revenue as $key => $value) {
                          $profit[$key] = $total_revenue[$key] - $total_expense[$key];

                        }

                      ?>
                      <td><strong><?php echo $profit['jan']?></strong></td>
                      <td><strong><?php echo $profit['feb']?></strong></td>
                      <td><strong><?php echo $profit['mar']?></strong></td>
                      <td><strong><?php echo $profit['apr']?></strong></td>
                      <td><strong><?php echo $profit['may']?></strong></td>
                      <td><strong><?php echo $profit['jun']?></strong></td>
                      <td><strong><?php echo $profit['jul']?></strong></td>
                      <td><strong><?php echo $profit['aug']?></strong></td>
                      <td><strong><?php echo $profit['sep']?></strong></td>
                      <td><strong><?php echo $profit['oct']?></strong></td>
                      <td><strong><?php echo $profit['nov']?></strong></td>
                      <td><strong><?php echo $profit['dece']?></strong></td>
                      <?php $total_profit = ($profit['jan']+$profit['feb']+$profit['mar']+$profit['apr']+$profit['may']+$profit['jun']+$profit['jul']+$profit['aug']+$profit['sep']+$profit['oct']+$profit['nov']+$profit['dece']) ?>
                      <td><strong><?php echo $total_profit; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>
                  <tbody id="tax_cont<?php echo $i;?>">
                    <tr class="title-row">
                      <td colspan="10"><strong>Taxes</strong></td>
                      <td class="text-right" colspan="5"><button type="button" class="btn btn-info btn-xs add-new" id="" data-year ="<?php echo $i;?>" onclick="tax_add(<?php echo $i;?>)"><i class="fa fa-plus"></i> Add Tax</button></td>
                    </tr>
                   <?php $this->db->select('*');
                          $this->db->where("year",$i);
                          $this->db->where("type","tax");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $taxes = $this->db->get('budget_statement')->result_array();
                          $tax_count = 0;
                          if(!empty($taxes)){ ?>
                            <input type="hidden" name="no_of_tax" id="no_of_tax" value="<?php echo count($taxes);?>">
                           
                          <?php   foreach ($taxes as $tax) {?>
                              <tr>
                                  <td> <div class="tax-input">
                                    <input type="text" class="form-control" placeholder="Tax Name" data-name="budget_name" id="tax_budget_name<?php echo $tax_count.$i; ?>" name="budget_name[]" value="<?php echo $tax['budget_name'].'';?>" readonly>
                                    <input type="text"   data-tax="tax_percentage" id="tax_percentage<?php echo $tax_count.$i; ?>" name="tax_percentage" class="form-control tax-value-input num_val" placeholder="%" value="<?php echo $tax['tax_percentage'];?>" readonly></div> </td>
                                    <td><?php $jan = $profit['jan']/$tax['tax_percentage']; echo number_format((float)$jan, 2, '.', '');?></td>
                                  <td><?php $feb = $profit['feb']/$tax['tax_percentage']; echo number_format((float)$feb, 2, '.', '');?></td>
                                  <td><?php $mar = $profit['mar']/$tax['tax_percentage']; echo number_format((float)$mar, 2, '.', '');?></td>
                                  <td><?php $apr = $profit['apr']/$tax['tax_percentage']; echo number_format((float)$apr, 2, '.', '');?></td>
                                  <td><?php $may = $profit['may']/$tax['tax_percentage']; echo number_format((float)$may, 2, '.', '');?></td>
                                  <td><?php $jun = $profit['jun']/$tax['tax_percentage']; echo number_format((float)$jun, 2, '.', '');?></td>
                                  <td><?php $jul = $profit['jul']/$tax['tax_percentage']; echo number_format((float)$jul, 2, '.', '');?></td>
                                  <td><?php $aug = $profit['aug']/$tax['tax_percentage']; echo number_format((float)$aug, 2, '.', '');?></td>
                                  <td><?php $sep = $profit['sep']/$tax['tax_percentage']; echo number_format((float)$sep, 2, '.', '');?></td>
                                  <td><?php $oct = $profit['oct']/$tax['tax_percentage']; echo number_format((float)$oct, 2, '.', '');?></td>
                                  <td><?php $nov = $profit['nov']/$tax['tax_percentage']; echo number_format((float)$nov, 2, '.', '');?></td>
                                  <td><?php $dece = $profit['dece']/$tax['tax_percentage']; echo number_format((float)$dece, 2, '.', '');?></td>
                                  <?php $after_tax[] = array('jan' => $jan,
                                                              'feb' => $feb,
                                                              'mar' => $mar,
                                                              'apr' => $apr,
                                                              'may' => $may,
                                                              'jun' => $jun,
                                                              'jul' => $jul,
                                                              'aug' => $aug,
                                                              'sep' => $sep,
                                                              'oct' => $oct,
                                                              'nov' => $nov,
                                                              'dece' => $dece
                                                            );?>
                                  
                                 <!--  <td data-name="feb" data-id="tax_feb<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="mar" data-id="tax_mar<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="apr" data-id="tax_apr<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="may" data-id="tax_may<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="jun" data-id="tax_jun<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="jul" data-id="tax_jul<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="aug" data-id="tax_aug<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="sep" data-id="tax_sep<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="oct" data-id="tax_oct<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="nov" data-id="tax_nov<?php echo $tax_count.$i; ?>"></td>
                                  <td data-name="dece" data-id="tax_dece<?php echo $tax_count.$i; ?>"></td> -->
                                  <td><strong><?php $total_tax = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dece; echo  number_format((float)$total_tax, 2, '.', '');?> </strong></td>
                                  <td class="text-right actionss">
                                    <a class="btn btn-info btn-xs add"  onclick="save_tax(<?php echo $tax_count?>,<?php echo $i?>,this)" data-type ="tax" data-id ="<?php echo $tax['id'];?>" id="tax_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>
                                    <a class="btn btn-success btn-xs edit" id="tax_edit" title="Edit" data-budget_name ="tax_budget_name<?php echo $tax_count.$i; ?>" data-tax="tax_percentage<?php echo $tax_count.$i; ?>" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#tax_delete<?php echo $tax['id']?>" id="" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                                  </td>
                                <tr>
                                
                                <div id="tax_delete<?php echo $tax['id']?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content modal-md">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Delete Budeget Tax( <?php echo $tax['budget_name']?> )</h4>
                                    </div>
                                    <form method="post" action="<?php echo base_url(); ?>budget_statement/delete_budget_statement/<?php echo $tax['id']; ?>">
                                      <div class="modal-body">
                                        <input type="hidden" class="form-control" value="<?php echo $tax['id'];?>" name="id">
                                        <input type="hidden" class="form-control" value="<?php echo $tax['budget_name'];?>" name="budget_name">
                                        <p>Are you sure want to delete this?</p>
                                        <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      <?php $tax_counts = $tax_count;
                         $tax_count++;
                            }
                     }?>
                    <input type="hidden" name="tax_new_hidden_rows" id="tax_new_hidden_rows" value="<?php echo $tax_counts;?>">
                  </tbody>
                  <tbody>
                    <tr class="total-row">
                      <td><strong>Total Taxes</strong></td>
                      <?php $total_taxes = array();
                      //echo '<pre>';print_r($after_tax);
                          foreach ($after_tax as $k=>$subArray) {
                            foreach ($subArray as $id=>$value) {
                              $total_taxes[$id]+=$value;
                            }
                          }

                         //print_r($sumArray);?>
                      <td><strong><?php echo number_format((float)$total_taxes['jan'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['feb'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['mar'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['apr'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['may'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['jun'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['jul'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['aug'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['sep'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['oct'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['nov'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$total_taxes['dece'], 2, '.', '');?></strong></td>
                      <td><strong><?php $month_tax = $total_taxes['jan'] + $total_taxes['feb'] +$total_taxes['mar'] + $total_taxes['apr'] + $total_taxes['may'] + $total_taxes['jun'] + $total_taxes['jul'] + $total_taxes['aug'] + $total_taxes['sep'] + $total_taxes['oct'] + $total_taxes['nov'] + $total_taxes['dece']; echo  number_format((float)$month_tax, 2, '.', '');?></strong></td>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr class="total-row">
                      <td><strong>Net Earnings</strong></td>
                      <?php 
                        $net_earning = array();
                        foreach ($profit as $key => $value) {
                          $net_earning[$key] = $profit[$key] - $total_taxes[$key];

                        }

                      ?>
                      <td><strong><?php echo number_format((float)$net_earning['jan'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun']+$net_earning['jul'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun']+$net_earning['jul']+$net_earning['aug'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun']+$net_earning['jul']+$net_earning['aug']+$net_earning['sep'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun']+$net_earning['jul']+$net_earning['aug']+$net_earning['sep']+$net_earning['oct'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun']+$net_earning['jul']+$net_earning['aug']+$net_earning['sep']+$net_earning['oct']+$net_earning['nov'], 2, '.', '');?></strong></td>
                      <td><strong><?php echo number_format((float)$net_earning['jan']+$net_earning['feb']+$net_earning['mar']+$net_earning['apr']+$net_earning['may']+$net_earning['jun']+$net_earning['jul']+$net_earning['aug']+$net_earning['sep']+$net_earning['oct']+$net_earning['nov']+$net_earning['dece'], 2, '.', '');?></strong></td>
                      <td><strong><?php $total_net_earning = $net_earning['jan'] + $net_earning['feb'] +$net_earning['mar'] + $net_earning['apr'] + $net_earning['may'] + $net_earning['jun'] + $net_earning['jul'] + $net_earning['aug'] + $net_earning['sep'] + $net_earning['oct'] + $net_earning['nov'] + $net_earning['dece']; echo  number_format((float)$total_net_earning, 2, '.', '');?></strong></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          <?php }?>
          </div>
        </div>
