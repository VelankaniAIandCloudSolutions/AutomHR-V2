
<div class="content container-fluid">

	<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="page-title">Attendance Regularization Request</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><?=lang('attendance')?></li>
					<li class="breadcrumb-item active">Regularization Request</li>
				</ul>
			</div>
			
			
		</div>
	</div>
	<!-- /Page Header -->

	<!-- Search Filter -->
  <form method="post" action="" >
	<div class="row filter-row">
		<div class="col-sm-6 col-md-3">  
			<div class="form-group form-focus">
				<input type="text" class="form-control floating" name="employee_name" id="employee_name" value="" />
				<input type="hidden"  name="employee_id" id="employee_id" value="0" />
				<label class="control-label">Employee Name</label>
			</div>
		</div>
		<div class="col-sm-6 col-md-3"> 
			<?php 
			$s_year = '2015';
			$select_y = date('Y');

			$s_month = date('m');
			$e_year = date('Y');
		 ?>
			<div class="form-group form-focus select-focus">
				<select class="select floating form-control" id="attendance_month" name="attendance_month">  
				<option value="" selected="selected" >Select Month</option>
				<?php 
					for ($ji=1; $ji <=12 ; $ji++) {  ?>
					<option value="<?php echo $ji; ?>" <?php echo ($attendance_month==$ji)?'selected':''; ?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>		
					<?php } ?>
				
			</select>
				<label class="control-label">Select Month</label>
			</div>
		</div>
		<div class="col-sm-6 col-md-3"> 
			<div class="form-group form-focus select-focus">
				<select class="select floating form-control" id="attendance_year" name="attendance_year"> 
					<option value="" selected="selected" >Select Year</option>
					<?php for($k =$e_year;$k>=$s_year;$k--){ ?>
					<option value="<?php echo $k; ?>" <?php echo ($attendance_year==$k)?'selected':''; ?> ><?php echo $k; ?></option>
					<?php } ?>
				</select>
				<label class="control-label">Select Year</label>
			</div>
		</div> 
		<div class="col-sm-6 col-md-3">  
			<button class="btn btn-primary btn-block" type="submit"><?=lang('search')?></button> 
		</div>     
	</div>
</form>

	<!-- /Search Filter -->
	
	<div class="row">
		<div class="col-lg-12">
			<div id="attendance_table" class="text-capitalize table-responsive">
				<?php $attendance_footer = '';
        $attendance_body = '';
        $attendance_head = '';
        $last_day = $last_day;
        $current_page = $current_page;
        $total_page = $total_page;
        $attendance_list = $attendance_list;
        $recordscount = count($attendance_list);
       $attendance_head = '<table id="table-attenedance" class="table table-striped custom-table m-b-0 AppendDataTables"><thead><tr><th>Employee</th><th>Date</th><th>Check In / Checkout</th><th>Reason</th><th>Action</th>';
        // for ($ik = 1; $ik <= $last_day; $ik++) {
            // $attendance_head .= '<th>' . $ik . '</th>';
        // }
        $attendance_head .= '</tr></thead>';
        $attendance_body .= '<tbody>';

        if ($recordscount > 0) {
            for ($i = 0; $i < $recordscount; $i++) {

               $record = $attendance_list[$i];
// 
                $name = $record->fullname;
                $attendance = $record->attendance;
				$req_date=$record->a_day.'-'.$record->a_month.'-'.$record->a_year;
				$record_day = unserialize($record->month_days);
				$month_days_in_out_record = unserialize($record->month_days_in_out);
				$reason=$record->reason;
				$a_day =$record->a_day;
				$a_day -=1;
				if($reason==1)
				{
					$reas='Forgot to Check In';
				}
				if($reason==2)
				{
					$reas='Forgot to Check Out';
				}
				if($reason==3)
				{
					$reas='Network Issues';
				}
				if($reason==4)
				{
					$reas='On Duty';
				}
				if($reason==5)
				{
					$reas='Permission';
				}
				
				// echo '<pre>';print_r($record);exit;
               $shift_details;

               $user = $this->db->get_where('users',array('id'=>$record->user_id))->row_array();
            
              if(!empty($user['designation_id'])){
                $designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
                $designation_name = $designation['designation'];
                
              }else{
                $designation_name = '-';
              }
            $imgs = '';
                    if($record->avatar != 'default_avatar.jpg'){
                        $imgs = $record->avatar;
                        
                    }else{
                        $imgs = "default_avatar.jpg";
                    }
                  $id_code = ($user['id_code'] !=0)?$user['id_code']:"-";
                $attendance_body .= '<tr><td><h2 class="table-avatar d-flex align-items-center">
                        <a class="avatar avatar-xs" href="'.base_url().'employees/profile_view/'.$record->user_id.'"> <img class="img-fluid" src="'.base_url().'assets/avatar/'.$imgs.'"></a><a class="text-info" href="'.base_url().'attendance/details/'.$record->user_id.'"><span class="username-info">'.user::displayName($record->user_id).'</span>
                        <span class="userrole-info text-muted"> '.$designation_name.'</span>
                      </a>
                        </h2></td>';
						
						$attendance_body .= '<td>'.date('d-m-Y',strtotime($req_date)).'</td>';
						$attendance_body .= '<td>'.date('h:i a', strtotime($record_day[$a_day]['punch_in'])).' / '.date('h:i a', strtotime($record_day[$a_day]['punch_out'])).' </td>';
						$attendance_body .= '<td>'.$reas.' </td>';
						$attendance_body .= '<td><a class="btn btn-success" href="'.base_url() . 'attendance/update_attendance_ar_ro_regul/'.$record->user_id.'/'.$record->a_day.'/'.$record->a_month.'/'.$record->a_year.'" data-toggle="ajaxModal" >Approve / Reject</a></td>';
                $attendance_body .= '</tr>';
            }
        } else {
            $attendance_body .= '<tr><td></td></tr>';
        }
        $attendance_body .= '</tbody>';

        $attendance_body .= '</table></div>';

        $attendance_html = $attendance_head .''. $attendance_body;
        echo $attendance_html ; ?>
		
			</div>
		</div>
	</div>
