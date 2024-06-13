<div class="content container-fluid">
          <div class="row">
            <div class="col-xs-8">
              <h4 class="page-title">Income Statement</h4>
            </div>
          </div>
          <div class="budget-tabs">           
            <ul class="nav nav-tabs tabs nav-tabs-bottom nav-justified">
            <?php   $start_years = Date('Y', strtotime("-3 Year"));
                 $current_year = date("Y");
                    for ($i=$start_years; $i <=$current_year ; $i++) { ?>
                    <!-- <li class="<?php echo ($i == $current_year )?'active':'';?>" id="tab_<?php echo $i;?>"><a class="statement" data-year="<?php echo $i;?>" href="<?php echo base_url().'income_statement/index/'.$i;?>"><?php echo $i;?></a></li> -->
                    <li class="<?php echo ($i == $current_year )?'active':'';?>" id="tab_<?php echo $i;?>"><a data-toggle="tab" class="statement" data-year="<?php echo $i;?>" href="#statement_<?php echo $i;?>"><?php echo $i;?></a></li>
                  <?php }?>
            </ul>
          </div>
          <div class="tab-content">           
            
           <?php  for ($y=$start_years; $y <=$current_year ; $y++) { 

            $this->db->select('DISTINCT(C.category_name)');
            $this->db->join('budget_category C','C.cat_id = dgt_budget_revenues.category_id','LEFT');
            $this->db->where("DATE_FORMAT(dgt_budget_revenues.revenue_date,'%Y')",$y);
            $this->db->where('budget_revenues.branch_id',$this->session->userdata('branch_id'));
            $revenue_category = $this->db->get('budget_revenues')->result_array();

            $this->db->select('DISTINCT(C.category_name)');
            $this->db->join('budget_category C','C.cat_id = dgt_budget_expenses.category_id','LEFT');
            $this->db->where("DATE_FORMAT(dgt_budget_expenses.expense_date,'%Y')",$y);
            $this->db->where('budget_expenses.branch_id',$this->session->userdata('branch_id'));
            $expense_category = $this->db->get('budget_expenses')->result_array();

            ?>
            <div id="statement_<?php echo $y;?>" class="tab-pane <?php echo ($y == $current_year )?'active':'';?>">
              <div class="card-box">
                  <h4 class="card-title">Income Statement <?php echo $y;?></h4>
                  <div class="table-responsive border">
                    <table class="table table-hover accounts-table mb-0">
                    <thead>
                      <th></th>
                      <?php foreach($months as $month) {?>
                      <th><?php echo $month; ?></th>
                     <?php } ?>
                     <th>Annual Profit</th>
                    </thead>
                    <tbody>

                       <!-- Budget_revenue Start -->

                      <tr>
                        <th><strong>Revenue</strong></th>
                      </tr>
                         

                     <?php 
                     echo '<tr>';


                      /* Getting Post Year */
                        // if(isset($year) && !empty($year)){
                        // $y = $year;
                        // } else {
                        // $y = date("Y");
                        // }
                       
                          

                        foreach($revenue_category as $cat){  // Looping category name here 
                              
                            echo '<td>'.ucfirst($cat['category_name']).'</td>';
                              $i= 1;
                      // Generating Month  here 
                              $category_revenue = 0;
                        foreach($months as $month) {

                          if(strlen($i) == 1){
                            $financial_start_date = $y.'-0'.$i;
                          } else {
                            $financial_start_date = $y.'-'.$i;
                          }

                          $this->db->select('SUM(dgt_budget_revenues.amount)  as total_amount ,dgt_budget_revenues.notes,C.category_name');
                          $this->db->where("DATE_FORMAT(dgt_budget_revenues.revenue_date,'%Y-%m')",$financial_start_date);
                          $this->db->where("C.category_name",$cat['category_name']);                              
                          $this->db->join('budget_category C','C.cat_id = dgt_budget_revenues.category_id','LEFT');
                          $this->db->where('budget_revenues.branch_id',$this->session->userdata('branch_id'));
                          $this->db->group_by('dgt_budget_revenues.category_id');
                          $revenues = $this->db->get('budget_revenues')->row_array();

                         // echo $this->db->last_query();
                          
                          if(!empty($revenues)){
                              $category_revenue +=$revenues['total_amount'];
                              echo '<td>'.$revenues['total_amount'].'</td>';          // Sum of Revenue in Project & Month wise                      
                         }else{
                          echo '<td>-</td>';                          
                        }
                        $i++;

                      } // month loop ends here 
                      echo '<td style = "text-align:right">'.$category_revenue.'</td>';      
                      echo '</tr>';

                    } // Category loop ends here 

                    ?>  
                    <?php 
                     echo '<tr>';
                     
                  
                      /* Getting Categories from the array  ends */


                          /* Getting Post Year */
                            // if(isset($year) && !empty($year)){
                            // $y = $year;
                            // } else {
                            // $y = date("Y");
                            // }
                           
                          

                           
                              
                            echo '<td><strong>Total Revenue</strong></td>';
                              $i= 1;
                      // Generating Month  here 
                              $c_total_revenues = 0;
                        foreach($months as $month) {

                          if(strlen($i) == 1){
                            $financial_start_date = $y.'-0'.$i;
                          } else {
                            $financial_start_date = $y.'-'.$i;
                          }

                          $this->db->select('SUM(dgt_budget_revenues.amount)  as total_amount');
                          $this->db->where("DATE_FORMAT(dgt_budget_revenues.revenue_date,'%Y-%m')",$financial_start_date);
                          $this->db->where('budget_revenues.branch_id',$this->session->userdata('branch_id'));
                          $total_revenues = $this->db->get('budget_revenues')->row_array();

                          $sum_revenues[$i] = $total_revenues;
                         // echo $this->db->last_query();

                          if(!empty($total_revenues['total_amount'])){
                            $c_total_revenues += $total_revenues['total_amount'];
                              echo '<th>'.$total_revenues['total_amount'].'</th>';          // Sum of Revenue in Project & Month wise                      
                         }else{
                          echo '<td>-</td>';                          
                        }
                        $i++;

                      } // month loop ends here 
                      echo '<th style = "text-align:right">'.$c_total_revenues.'</th>'; 
                      echo '</tr>'; ?>

                <!-- Budget_revenue end -->

                <!-- Budget_expenses start -->

                     <tr>
                        <th><strong>Expenses</strong></th>
                      </tr>
                         

                     <?php 
                     echo '<tr>';


                     /* Getting Post Year */
                        // if(isset($year) && !empty($year)){
                        // $y = $year;
                        // } else {
                        // $y = date("Y");
                        // }
                           
                          

                          foreach($expense_category as $cat){  // Looping category name here 
                              
                            echo '<td>'.ucfirst($cat['category_name']).'</td>';
                              $i= 1;
                               $category_expense = 0;
                      // Generating Month  here 
                        foreach($months as $month) {

                          if(strlen($i) == 1){
                            $financial_start_date = $y.'-0'.$i;
                          } else {
                            $financial_start_date = $y.'-'.$i;
                          }

                          $this->db->select('SUM(dgt_budget_expenses.amount)  as total_amount ,dgt_budget_expenses.notes,C.category_name');
                          $this->db->where("DATE_FORMAT(dgt_budget_expenses.expense_date,'%Y-%m')",$financial_start_date);
                          $this->db->where("C.category_name",$cat['category_name']);                              
                          $this->db->join('budget_category C','C.cat_id = dgt_budget_expenses.category_id','LEFT');
                          $this->db->where('budget_expenses.branch_id',$this->session->userdata('branch_id'));
                          $this->db->group_by('dgt_budget_expenses.category_id');
                          $expenses = $this->db->get('budget_expenses')->row_array();

                         // echo $this->db->last_query();

                          if(!empty($expenses)){
                            $category_expense += $expenses['total_amount'];
                              echo '<td>'.$expenses['total_amount'].'</td>';          // Sum of Revenue in Project & Month wise                      
                         }else{
                          echo '<td>-</td>';                          
                        }
                        $i++;

                      } // month loop ends here 
                      echo '<td style = "text-align:right">'.$category_expense.'</td>';   
                      echo '</tr>';

                    } // Category loop ends here 

                      ?>  
                      <?php 
                     echo '<tr>';

                       // sum of expenses for each month

                          /* Getting Post Year */
                      // if(isset($year) && !empty($year)){
                      // $y = $year;
                      // } else {
                      // $y = date("Y");
                      // }   
                      
                      echo '<td><strong>Total Expense</strong></td>';
                        $i= 1;
                        $c_total_expense = 0;
                      // Generating Month  here 
                        foreach($months as $month) {

                          if(strlen($i) == 1){
                            $financial_start_date = $y.'-0'.$i;
                          } else {
                            $financial_start_date = $y.'-'.$i;
                          }

                          $this->db->select('SUM(dgt_budget_expenses.amount)  as total_amount');
                          $this->db->where("DATE_FORMAT(dgt_budget_expenses.expense_date,'%Y-%m')",$financial_start_date);
                          $this->db->where('budget_expenses.branch_id',$this->session->userdata('branch_id'));
                          $total_expenses = $this->db->get('budget_expenses')->row_array();

                          $sum_expenses[$i] = $total_expenses;

                         // echo $this->db->last_query();

                          if(!empty($total_expenses['total_amount'])){
                            $c_total_expense += $total_expenses['total_amount'];
                              echo '<th>'.$total_expenses['total_amount'].'</th>';          // Sum of Revenue in Project & Month wise                      
                         }else{
                          echo '<td>-</td>';                          
                        }
                        $i++;

                      } // month loop ends here 
                      echo '<th style = "text-align:right">'.$c_total_expense.'</th>';  
                      echo '</tr>';

                   

                      ?>    
                    <!-- Sum of profit for each month -->
                     <?php 
                     //print_r($sum_revenues); exit;
                      echo '<tr>';

                       // sum of Profit for each month
                                                                 
                           $total_profits = 0;   
                      echo '<td><strong>Total Profit</strong></td>';
                        $i= 1;

                        $ret = array();
                        
                        foreach ($sum_revenues as $key => $value) {
                            $ret[$key] = $sum_revenues[$key]['total_amount'] - $sum_expenses[$key]['total_amount'];

                        }
                        foreach ($ret as  $value) {
                           if(!empty($value)){
                             $total_profits += $value;
                                echo '<th>'.$value.'</th>';          // Sum of Revenue in Project & Month wise                      
                           }else{
                            echo '<td>-</td>';                          
                          }
                        }
                      echo '<th style = "text-align:right">'.$total_profits.'</th>';  
                      echo '</tr>';

                   

                      ?>    

                     
                    </tbody>
                  </table>
                </div>
              </div>     
           </div>
          <?php }?>
        </div>
</div>

                