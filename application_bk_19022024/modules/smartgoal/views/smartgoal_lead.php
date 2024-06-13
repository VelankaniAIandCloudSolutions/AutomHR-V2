<div class="content">
   <div class="row">
      <div class="col-sm-8 col-xs-3">
         <?php 
         $user_id = $this->uri->segment(3);
         $username = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
         
         ?>
         <h4 class="page-title"><?php echo $username['fullname']?> Smartgoal Manager</h4>
      </div>
      
   </div>

   
   
   

      <div class="row">
      <div class="col-md-12">
      <div class="table-responsive">
         <?php 
         $user_id = $this->uri->segment(3);
         

         
         $smartgoal_details = $this->db->select('*')
                     ->from('smartgoal')
                     ->get()->result_array();
         
            ?>
          <table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
            <thead>
               <tr class="table_heading">
                  <th> No </th>
                  <th> Name </th>
                  <th> Designation</th>
                  <th> Team lead </th>
                  <th> Goal Duaration </th> 
                   
                  
               </tr>
            </thead>
            <tbody id="admin_leave_tbl">
               <?php 
               if(!empty($smartgoal_details)){
                foreach($smartgoal_details as $key => $details){  
                   $teamlead = $this->db->get_where('account_details',array('user_id'=>$details['lead']))->row_array();
               ?>
               
               <tr>
                  <td><?=$key+1?></td>
                  
                  <td><a class="text-info" href="<?php echo base_url()?>smartgoal/show_smartgoal/<?=$details['id']?>"><?=$details['emp_name']?></a></td>
                  <td><?=$details['position']?></td>
                  <td><?=$teamlead['fullname']?></td>
                  <td><?=$details['goal_duration']?></td>
                                 
               </tr>
             <?php  } ?>  
             <?php  }else{ ?>
                   <tr><td class="text-center" colspan="9">No details were found</td></tr>
                   <?php } ?>  
            </tbody>
         </table>    
       </div>
      </div>
      </div>
      <!-- user leave end -->
     
      

         

      


