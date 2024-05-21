<div class="btn-group">

              <button class="btn btn-default"><?=lang('reports')?></button>
              <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
              </button>
              <?php $link = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
               $page = end($link);?>
              <ul class="dropdown-menu">

             <li class="<?php if($page == 'invoicesreport') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/invoicesreport"><?=lang('invoices_report')?></a></li>
              <li class="<?php if($page == 'invoicesbyclient') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/invoicesbyclient"><?=lang('invoice_by_client')?></a></li>
              <li class="<?php if($page == 'paymentsreport') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/paymentsreport"><?=lang('payments_report')?></a></li>
              <li class="<?php if($page == 'expensesreport') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/expensesreport"><?=lang('expenses_report')?></a></li>
              <li class="<?php if($page == 'expensesbyclient') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/expensesbyclient"><?=lang('expenses_by_client')?></a></li>
              <li class="<?php if($page == 'projectreport') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/projectreport"><?=lang('project_report')?></a></li>
              <li class="<?php if($page == 'taskreport') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/taskreport"><?=lang('task_report')?></a></li>
              <!-- <li><a href="<?=base_url()?>reports/view/user_report"><?=lang('user_report')?></a></li> -->
              <li class="<?php if($page == 'employee_report') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/employee_report"><?=lang('employee_report')?></a></li>
              <li class="<?php if($page == 'payslip_report') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/payslip_report"><?=lang('payslip_report')?></a></li>
              <!-- <li><a href="<?=base_url()?>reports/view/daily_report"><?=lang('daily_report')?></a></li> -->
              <li class="<?php if($page == 'leave_report') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/leave_report">Leave Report</a></li>
              <li class="<?php if($page == 'attendance_report') { echo 'active'; } ?>"><a href="<?=base_url()?>reports/view/attendance_report"><?=lang('attendance_report')?></a></li>
              </ul>
              </div>

              <?php if(!$this->uri->segment(3)){ ?>
              <div class="btn-group">

              <button class="btn btn-default"><?=lang('year')?></button>
              <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
              </button>

              <ul class="dropdown-menu">
              <?php
                      $max = date('Y');
                      $min = $max - 3;
                      foreach (range($min, $max) as $year) { ?>
                    <li><a href="<?=base_url()?>reports?setyear=<?=$year?>"><?=$year?></a></li>
              <?php }
              ?>
                        
              </ul>

              </div>
              <?php } ?>