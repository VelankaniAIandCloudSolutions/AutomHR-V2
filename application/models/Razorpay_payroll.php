<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Razorpay_payroll extends CI_Model
{
    private $api;
    private $api_key;
    private $api_id;
   
    public function __construct()
    {
        parent::__construct();
        $this->api = RAZORPAY_PAYROLL_API;

    }

    /*  
        Module Name     : RazorpayXpayroll API History 
        Description     : add history
        Author          : Ankit Agrawal
        Date            : 13/12/2023   
    */
    public function razorpay_history_insert($data)
    {
        $this->db->insert("dgt_razorpay_payroll_history", $data);
        $last_insert_id = $this->db->insert_id();
        return $last_insert_id;
    }
    
    /*  
        Module Name     : Curl Init funciton 
        Description     : Curl Init funciton 
        Author          : Ankit Agrawal
        Date            : 13/12/2023   
    */
    public function curl_operation($json_data, $api = '')
    {
        $curl = curl_init();
        if($api !="")
        {
            $this->api = $api;
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$json_data,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /*  
        Module Name     : Employee Dismiss 
        Description     : Employee Dismiss on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 13/12/2023   
    */
    public function dismiss_employee($req_data, $user_id)
    {   
    	return false;
        $this->db->select('branch_id,employee_id');
        $branch_data = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
        $module_name = 'employee';
        $action ='dismiss';
        $emp_id = (int) $branch_data['employee_id'];

        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }

        $api_keys = $query1->row_array();
        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }
        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "people",
            "sub-type"  =>  "dismiss",
        );

        $temp_data = array();
        $temp_data = array(
            "email" =>  $req_data['email'],
            "dateOfDismissal"  => date("d/m/Y")
        );

        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );
        $tmp_response = $this->curl_operation(json_encode($request_data));
        $response_data = json_decode($tmp_response, true);
        $data = array();
        $data = array(
            "module"        =>  $module_name,
            "action"        =>  $action,
            "api_url"       =>  $this->api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $data['status'] =   '0';
        }
        else{
            $data['status'] =   '1';
        }
        // $this->db->insert("dgt_razorpay_payroll_history",$data);
        // $history_id = '';
        // $history_id = $this->db->insert_id();

        $history_id = $this->razorpay_history_insert($data);

        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));     
        }
        return;
    }

    /*  
        Module Name     : Employee Added 
        Description     : Employee Add on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 14/12/2023   
    */
    public function add_employee($data = array(), $user_id= '')
    {   
        $this->db->select('branch_id,employee_id');
        $branch_data = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
        
        $module_name = 'employee';
        $action ='create';
        $emp_id = (int) $branch_data['employee_id'];

        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }

        $api_keys = $query1->row_array();
        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }
        
        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "people",
            "sub-type"  =>  "create",
        );

        $temp_data = array();
        $temp_data = array(
            "email" =>  $data['email'],
            "name"  =>  $data['fullname'],
            "type"  =>  "employee"
        );

        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );
        $tmp_response = $this->curl_operation(json_encode($request_data));
        $response_data = json_decode($tmp_response, true);

        $history_data = array();        
        $history_data = array(
            "module"        =>  $module_name,
            "action"        =>  $action,
            "api_url"       =>  $this->api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $history_data['status'] =   '0';
        }
        else{
            $history_data['status'] =   '1';
        }
        // $this->db->insert("dgt_razorpay_payroll_history",$data);
        // $history_id = '';
        // $history_id = $this->db->insert_id();

        $history_id = $this->razorpay_history_insert($history_data);

        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));
            
            $this->update_employee($user_id, $action);
        }
    }

    /*  
        Module Name     : Employee Edit/ Update 
        Description     : Employee Edit/ Update on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 14/12/2023   
    */
    public function update_employee($user_id= '', $module_action= '')
    {   
        $this->db->select('du.*, dad.*,dd.designation, dept.deptname, bank_details.*, dupd.bank_info as bank_details');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->join("dgt_designation as dd",'dd.id = du.designation_id',"left");
        $this->db->join("dgt_departments as dept",'dept.deptid = dd.department_id',"left");
        $this->db->join("dgt_bank_statutory as bank_details",'bank_details.user_id = dad.user_id',"left");
        $this->db->join("dgt_users_personal_details as dupd","dupd.user_id = dad.user_id","left");

        $this->db->where("du.id", $user_id);
        $query = $this->db->get();
        $branch_data = $query->row_array();
        
        // $response = $this->check_employee_exits($user_id);
        // if($response['error']['code'] == "8")
        // {
        //     $data = array();
        //     $data['email'] = $branch_data['email'];
        //     $data['fullname'] = $branch_data['fullname'];

        //     $this->add_employee($data, $user_id);
        // }
        
        $temp_teamLeadId = $branch_data['teamlead_id'];

        $this->db->select('du.*, dad.*');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->where("du.id", $temp_teamLeadId);
        $query2 = $this->db->get();
        $reporting_data = $query2->row_array();
       
        if(!empty($reporting_data))
        {
            $teamLead_id = (int) $reporting_data['employee_id'];
            $teamLead_type = "employee";
        }
        else{
            $teamLead_id = '';
            $teamLead_type = 'employee';
        }

        $module_name = 'employee';
        $action = $module_action;
        $emp_id = (int) $branch_data['employee_id'];
        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }

        $api_keys = $query1->row_array(); 
        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }
        
        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "people",
            "sub-type"  =>  "edit",
        );

        $state = '';
        if(strtolower($branch_data['worklocationStateCode'] ) =='ka')
        {
            $state = 'Karnataka';
        }
        else if(strtolower($branch_data['worklocationStateCode'] ) =='ga')
        {
            $state = 'Goa';
        }
        else{
            $state = 'Karnataka';
        }

        $pan_no = $bank_holder_name = $bank_name = $bank_ac_no = $ifsc_code = $account_type = '';

        if(!empty($branch_data))
        {
            if(!empty($branch_data['bank_details']))
            {
                $bank_details_array = json_decode($branch_data['bank_details'], true);
                $pan_no = $bank_details_array['pan_no'];
                $bank_holder_name = $bank_details_array['bank_holder_name'];
                $bank_name = $bank_details_array['bank_name'];
                $bank_ac_no = $bank_details_array['bank_ac_no'];
                $ifsc_code = $bank_details_array['ifsc_code'];
                $account_type = $bank_details_array['account_type'];
            }
        }

        $temp_data = array();
        $temp_data = array(
            "email"                 =>  $branch_data['email'],
            "name"                  =>  $branch_data['fullname'],
            "title"                 =>  ucwords($branch_data['designation']),
            "department"            =>  ucwords($branch_data['deptname']),
            "pan"                   =>  $pan_no,
            "phone-number"          =>  !empty($branch_data['mobile']) ? $branch_data['mobile'] : $branch_data['phone'],
            "employee-id"           =>  (int) $branch_data['employee_id'],
            "pt-enabled"            =>  true,
            "hiring-date"           =>  date("d/m/Y", strtotime($branch_data['doj'])),
            "state"                 =>  $state 
        );

        if(($bank_ac_no == "") && ($ifsc_code == ""))
        {
            $temp_data['bank-ifsc'] =  $ifsc_code;
            $temp_data['bank-account-number'] =  $bank_ac_no;
        }

        if($teamLead_id != "" && $teamLead_id > 0)
        {
            $temp_data['manager-employee-id'] = (int)$teamLead_id;
            $temp_data['manager-employee-type'] = $teamLead_type;
        }

        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );
        
        $tmp_response = $this->curl_operation(json_encode($request_data));
        $response_data = json_decode($tmp_response, true);
        $history_data = array();        
        $history_data = array(
            "module"        =>  $module_name,
            "action"        =>  $action,
            "api_url"       =>  $this->api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $history_data['status'] =   '0';
        }
        else{
            $history_data['status'] =   '1';
        }

        $history_id = $this->razorpay_history_insert($history_data);
        
        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));     
        }
    }

    /*  
        Module Name     : Employee Set Salary
        Description     : Employee  Set Salary on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 15/12/2023   
    */

    public function set_salary($user_id= '')
    {
        $this->db->select('du.*, dad.*,dd.designation, dept.deptname, bank_details.*, dupd.bank_info as bank_details');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->join("dgt_designation as dd",'dd.id = du.designation_id',"left");
        $this->db->join("dgt_departments as dept",'dept.deptid = dd.department_id',"left");
        $this->db->join("dgt_bank_statutory as bank_details",'bank_details.user_id = dad.user_id',"left");
        $this->db->join("dgt_users_personal_details as dupd","dupd.user_id = dad.user_id","left");

        $this->db->where("du.id", $user_id);
        $query = $this->db->get();
        $branch_data = $query->row_array();
        
        $pf_addtional = array();
        $pf_deduction = array();

        if(!empty($branch_data['pf_addtional']))
        {
            $pf_addtional = json_decode($branch_data['pf_addtional'], true);
        }
        if(!empty($branch_data['pf_deduction']))
        {
            $pf_deduction = json_decode($branch_data['pf_deduction'], true);
        }

        $module_name = 'employee';
        $action = "update/set-salary";
        $emp_id = (int) $branch_data['employee_id'];
        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }

        $api_keys = $query1->row_array();
        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }
        
        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "people",
            "sub-type"  => "set-salary",
        );

        if(!empty($pf_addtional))
        {
            $i=1;
            $monthly_data = array();
            foreach($pf_addtional as $key => $post_data_val)
            {
                if($post_data_val['id'] == $i)
                {
                    $monthly_data[$key] = round((($post_data_val['unit_amount']) / 12), 2);
                }
                $i++;
            }
        }

        if(!empty($pf_deduction))
        {
            $i=1;
            $deduction_monthly_data = array();
            foreach($pf_deduction as $key => $post_data_val)
            {
                if($post_data_val['id'] == $i)
                {
                    $deduction_monthly_data[$key] = round((($post_data_val['unit_amount']) / 12), 2);
                }
                $i++;
            }
        }

        $temp_data = array();
        $temp_data = array(
            "employee-id"               =>  (int) $branch_data['employee_id'],
            "custom-salary-structure"   =>  true,
        );
        $temp_data['salary-structure']= array();
        $temp_data['salary-structure']= array(
            "basic"                 =>  isset($monthly_data[1]) ? $monthly_data[1] : 0,
            "da"                    =>  isset($monthly_data[2]) ? $monthly_data[2] : 0,
            "hra"                   =>  isset($monthly_data[3]) ? $monthly_data[3] : 0,
            "special-allowance"     =>  isset($monthly_data[4]) ? $monthly_data[4] : 0,
            "lta"                   =>  isset($monthly_data[5]) ? $monthly_data[5] : 0
        );
        $temp_data['salary-structure']['custom-allowances']= array();
        $temp_data['salary-structure']['custom-allowances']= array(
            array(
                "name"      => "Conveyance Allowance",
                "amount"    =>  isset($monthly_data[6]) ? $monthly_data[6] : 0,
                "taxable"   =>  "yes"
            ),
            array(
                "name"      => "Medical Allowance",
                "amount"    =>  isset($monthly_data[7]) ? $monthly_data[7] : 0,
                "taxable"   =>  "no"
            ),
            array(
                "name"      => "Variable",
                "amount"    =>  isset($monthly_data[8]) ? $monthly_data[8] : 0,
                "taxable"   =>  "yes"
            )
            //  array(
            //     "name"      => "PF Amount Employer (Annual)",
            //     "amount"    =>  isset($monthly_data[9]) ? $monthly_data[9] : 0,
            //     "taxable"   =>  "yes"
            // )
        );

        $temp_data['salary-structure']['deductions']= array(
            // array(
            //     "name"      => "PF Amount Employee",
            //     "amount"    =>  isset($deduction_monthly_data[1]) ? $deduction_monthly_data[1] : 0,
            //     "taxable"   =>  false
            // ),
            array(
                "name"      => "Accomodation Deduction",
                "amount"    =>  isset($deduction_monthly_data[2]) ? $deduction_monthly_data[2] : 0,
                "taxable"   =>  false
            ),
            array(
                "name"      => "Other Deduction",
                "amount"    =>  isset($deduction_monthly_data[3]) ? $deduction_monthly_data[3] : 0,
                "taxable"   =>  false
            )
        );

        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );

        $tmp_response = $this->curl_operation(json_encode($request_data));
        $response_data = json_decode($tmp_response, true);
        
        $history_data = array();        
        $history_data = array(
            "module"        =>  $module_name,
            "action"        =>  $action,
            "api_url"       =>  $this->api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $history_data['status'] =   '0';
        }
        else{
            $history_data['status'] =   '1';
        }

        $history_id = $this->razorpay_history_insert($history_data);
        
        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));     
        }
    }

    /*  
        Module Name     : Employee Attendance Checkin/ Checkcout
        Description     : Employee  Attendance Checkin/ Checkcout on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 15/12/2023   
    */

    public function checkincheckout($user_id= '', $checkinout_time ='', $checkin_type= '')
    {
        $action_date = date("Y-m-d", $checkinout_time);
        $action_time = date("H:i", $checkinout_time);

        $this->db->select('du.*, dad.*');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->where("du.id", $user_id);
        $query = $this->db->get();
        $branch_data = $query->row_array();

        $module_name = 'attedance';
        if($checkin_type === 'checkin')
        {
            $action = "checkin";
        }
        else{
            $action = "checkout";
        }
        
        $emp_id = (int) $branch_data['employee_id'];
        
        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }

        $api_keys = $query1->row_array();
        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }
        
        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "attendance",
            "sub-type"  => "modify",
        );
        $temp_data = array();
        $temp_data = array(
            "employee-id"       =>  $emp_id,
            "employee-type"     =>  "employee",
            "date"              =>  $action_date,
            "status"            =>  'present',
            "leave-type"        =>  '-1'
        );
        
        if($checkin_type === 'checkin')
        {
            $temp_data['checkin'] = $action_time;
            $temp_data['remarks'] = "checked in";
        }
        else{
            $temp_data['checkout'] = $action_time;
            $temp_data['remarks'] = "checked out";
        }

        
        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );

        $api = RAZORPAY_PAYROLL_ATTENDANCE_API;
        $tmp_response = $this->curl_operation(json_encode($request_data), $api);
        $response_data = json_decode($tmp_response, true);
        
        $history_data = array();        
        $history_data = array(
            "module"        =>  $module_name,
            "action"        =>  $action,
            "api_url"       =>  $api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $history_data['status'] =   '0';
        }
        else{
            $history_data['status'] =   '1';
        }

        $history_id = $this->razorpay_history_insert($history_data);
        
        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));     
        }

    }
/*  
        Module Name     : Employee Deduction as LOP
        Description     : Employee Deduction as LOP on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 18/12/2023   
    */

    public function employee_deducition_lop($post = array(), $dedcution_cal = array())
    {
        $user_id = $post['user_id'];
        $this->db->select('du.*, dad.*,bank_details.*');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->join("dgt_bank_statutory as bank_details",'bank_details.user_id = dad.user_id',"left");
        $this->db->where("du.id", $user_id);
        $query = $this->db->get();
        $branch_data = $query->row_array();

        $module_name = 'payroll';
        $action = "lop added";
       
        $emp_id = (int) $branch_data['employee_id'];
        
        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }
        $api_keys = $query1->row_array();

        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }

        $this->db->select("id,user_id,a_month,a_year, lop");
        $this->db->from('dgt_attendance_details');
        $this->db->where("user_id",$user_id);
        $this->db->where("a_month", $post['atte_month']);
        $this->db->where("a_year", $post['atte_year']);
        $this->db->order_by("id","desc");
        $this->db->limit(1);
        $query2 = $this->db->get();
        $lop_data = $query2->row_array();

        $lop = 0;
        $month = date("m");
        $year = date("Y");
        $deduction_amount = 0;
        if(!empty($lop_data))
        {
            $lop = $lop_data['lop'];
            $month = $lop_data['a_month'];
            $year = $lop_data['a_year'];
        }

        $pf_addtional = array();
        $basic_anual_salary = $monthly_salary =  $one_day_amount = 0;
        $days_count_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        if(!empty($branch_data['pf_addtional']))
        {
            $pf_addtional = json_decode($branch_data['pf_addtional'], true);
            $basic_anual_salary = $pf_addtional[0]['unit_amount'];
            $monthly_salary = round(($basic_anual_salary/12), 2);
            $one_day_amount = round(($monthly_salary / $days_count_month ), 2);
        }
        $deduction_amount = ($one_day_amount * $lop);

        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "payroll",
            "sub-type"  => "add-deduction",
        );
        $final_deduciton_month = $year.'-'.$month;
        $temp_data = array();
        $temp_data = array(
            "employee-id"       =>  $emp_id,
            "payroll-month"     =>  $final_deduciton_month,
            "deduction-amount"  =>  round($deduction_amount, 2),
            "remarks"           =>  "Salary Deduction for LOP"    
        );
        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );

        $api = RAZORPAY_PAYROLL_ADD_DEDUCTION_API;
        $tmp_response = $this->curl_operation(json_encode($request_data), $api);
        $response_data = json_decode($tmp_response, true);
        
        $history_data = array();        
        $history_data = array(
            "module"        =>  $module_name,
            "action"        =>  $action ,
            "api_url"       =>  $api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $history_data['status'] =   '0';
        }
        else{
            $history_data['status'] =   '1';
        }

        $history_id = $this->razorpay_history_insert($history_data);
        
        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));     
        }
    }

    /*  
        Module Name     : Employee Attendance Checkin/ Checkcout
        Description     : Employee  Attendance Checkin/ Checkcout on Razorpay Payroll system
        Author          : Ankit Agrawal
        Date            : 15/12/2023   
    */

    public function check_employee_exits($user_id= '')
    {
        $this->db->select('du.*, dad.*');
        $this->db->from("dgt_users as du");
        $this->db->join("dgt_account_details as dad","dad.user_id = du.id","inner");
        $this->db->where("du.id", $user_id);
        $query = $this->db->get();
        $branch_data = $query->row_array();

        $module_name = 'employee';
        $action = 'view';
        $emp_id = (int) $branch_data['employee_id'];
        
        $this->db->select("razorpay_xpayroll_id, razorpay_xpayroll_key");
        $this->db->from("dgt_branches");
        $this->db->where("branch_id", $branch_data['branch_id']);
        $query1 =  $this->db->get();        
        if(!$query1)
        {
            return false;
        }

        $api_keys = $query1->row_array();
        $this->api_key = $this->api_id = null;
        if(!empty($api_keys))
        {
            $this->api_key = $api_keys['razorpay_xpayroll_key'];
            $this->api_id = $api_keys['razorpay_xpayroll_id'];
        }
        
        $auth = array();
        $auth = array(
            "id"    =>  $this->api_id,
            "key"   =>  $this->api_key
        );

        $request = array();
        $request = array(
            "type"  =>  "people",
            "sub-type"  => "view",
        );
        $temp_data = array();
        $temp_data = array(
            "employee-id"       =>  $emp_id,
            "employee-type"     =>  "employee"
        );
        
        $request_data = array();
        $request_data = array(
            "auth"      =>  $auth,
            "request"   =>  $request,
            "data"      =>  $temp_data
        );

        $api = RAZORPAY_PAYROLL_API;
        $tmp_response = $this->curl_operation(json_encode($request_data), $api);
        $response_data = json_decode($tmp_response, true);
        
        $history_data = array();        
        $history_data = array(
            "module"        =>  $module_name,
            "action"        =>  $action,
            "api_url"       =>  $api,
            "request_data"  =>  json_encode($request_data),
            "response_data" =>  $tmp_response,
            "user_id"       =>  $user_id,
            "date_added"    =>  date("Y-m-d H:i:s")
        );

        if(isset($response_data['error']) && !empty($response_data['error']))
        {
            $history_data['status'] =   '0';
        }
        else{
            $history_data['status'] =   '1';
        }

        $history_id = $this->razorpay_history_insert($history_data);
        
        if($history_id > 0)
        {
            $this->db->where("id",$user_id);
            $this->db->update("dgt_users", array("dgt_razorpay_payroll_history_id" => $history_id));     
        }

        return $response_data; 
    }
}
