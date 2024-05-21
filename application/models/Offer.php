<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offer extends CI_Model
{

	private static $db;


	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	/**
	* Insert records to companies table and return INSERT ID
	*/
	static function save($data = array()) {
		self::$db->insert('offers',$data);
		return self::$db -> insert_id();
	}

	/**
	* Update client information
	*/
	static function update($id, $data = array()) {
		self::$db->where('id',$id)->update('offers',$data);
		return self::$db->affected_rows();
	}

	//Inser record to offer approvers table and return insert ID
	static function save_offer_approvers($data = array()) {
		self::$db->insert('offer_approvers',$data);
		return self::$db -> insert_id();
	}
	static function save_offer_approver_settings($data = array()) {
		self::$db->insert('offer_approver_settings',$data);
		return self::$db -> insert_id();
	}
	static function onboarding_save($data = array()) {
		self::$db->insert('onboarding',$data);
		return self::$db -> insert_id();
	}

	// Get all clients
	static function get_all_clients()
	{
		return self::$db->where(array('is_lead' => 0,'co_id >'=> 0))->order_by('company_name','ASC')->get('companies')->result();
	}
	// Get all departments
	static function get_all_departments()
	{
		return self::$db->order_by('depthidden','ASC')->get('departments')->result();
	}


	static function due_amount($company)
	{
		$due = 0;
		$cur = self::view_by_id($company)->currency;
		$invoices = self::$db->where(array('client'=>$company,'status !='=>'Cancelled'))->get('invoices')->result();
		foreach ($invoices as $key => $invoice) {
			if($invoice->currency != $cur){
					$due += Applib::convert_currency($cur,Invoice::get_invoice_due_amount($invoice->inv_id));
			}else{
				$due += Invoice::get_invoice_due_amount($invoice->inv_id);
			}
		}
			return $due;
	}

	// Get all client files
    static function has_files($id)
    {
        return self::$db->where('client_id',$id)->get('files')->result();
    }

	static function get_client_contacts($company)
	{
		self::$db->join('companies','companies.co_id = account_details.company');
		self::$db->join('users','users.id = account_details.user_id');
		return self::$db->where('company',$company)->get('account_details')->result();
	}

	static function payable($company){
		$total = 0;
		$invoices = Invoice::get_client_invoices($company);
		foreach ($invoices as $key => $inv) {
			if($inv->currency != config_item('default_currency')){
				$total += Applib::convert_currency($inv->currency, Invoice::payable($inv->inv_id));
			}else{
				$total += Invoice::payable($inv->inv_id);
			}
		}
		return $total;
	}

	static function view_by_id($company)
	{
		return self::$db->where('co_id',$company)->get('companies')->row();
	}

	static function custom_fields($client){
		return self::$db->where(array('module'=>'clients','client_id'=>$client))->get('formmeta')->result();
	}

	// Get client currency
	static function client_currency($company = FALSE)
	{
		if (!$company) { return FALSE; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		$client = self::$db->where('co_id', $company)->get('companies')->result();
		if (count($client) == 0) { return $dcurrency[0]; }
		$currency = self::$db->where('code',$client[0]->currency)->get('currencies')->result();
		if (count($currency) > 0) { return $currency[0]; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		if (count($dcurrency) > 0) { return $dcurrency[0]; }

	}
	// Get client language
	static function client_language($id = FALSE)
	{
		if (!$id) { return FALSE; }
		$language = self::$db->where('name',self::view_by_id($id)->language)->get('languages')->result();
		return $language[0];
	}

	// Amount paid by client
	static function amount_paid($company)
	{
		$total = 0;
		if($company > 0){
		$payments = self::$db->where(array('paid_by'=>$company,'refunded'=>'No'))->get('payments')->result();
		foreach ($payments as $key => $pay) {
			if($pay->currency != config_item('default_currency')){
				$total += Applib::convert_currency($pay->currency,$pay->amount);
			}else{
				$total += $pay->amount;
			}
		}
	}
		return $total;
	}

	// Get Client Currency
	static function get_currency_code($company = FALSE)
	{
		if (!$company) { return FALSE; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		$client = self::$db->where('co_id', $company)->get('companies')->result();
		if (count($client) == 0) { return $dcurrency[0]; }
		$currency = self::$db->where('code',$client[0]->currency)->get('currencies')->result();
		if (count($currency) > 0) { return $currency[0]; }
		$dcurrency = self::$db->where('code', config_item('default_currency'))->get('currencies')->result();
		if (count($dcurrency) > 0) { return $dcurrency[0]; }

	}
	// Get client expenses
	static function total_expenses($company = NULL)
		{
			return self::$db->select_sum('amount')
							->where(array('billable' => '1','invoiced' => '0','client' => $company))
							->get('expenses')
							->row()->amount;
		}

		static function month_amount($year, $month, $client){
	        $total = 0;
	        $query = "SELECT * FROM dgt_payments WHERE paid_by = '$client' AND MONTH(payment_date) = '$month' AND refunded = 'No' AND YEAR(payment_date) = '$year'";
	        $payments = self::$db->query($query)->result();
	        foreach($payments as $p) {
	            $amount = $p->amount;
	            if ($p->currency != config_item('default_currency')) {
	                $amount = Applib::convert_currency($p->currency, $amount);
	            }
	            $total += $amount;
	        }
	        return round($total, config_item('currency_decimals'));
	    }

		// Get all client comments
	    static function has_comments($id)
	    {
	        return self::$db->where(array('client_id'=>$id))->order_by('date_posted','desc')->get('comments')->result();
	    }
	    // Get all comment replies
	    static function has_replies($comment)
	    {
	        return self::$db->where('parent_comment',$comment)->get('comment_replies')->result();
	    }

	// Deletes Client from the database
	static function delete($company)
	{

	$company_invoices 	= Invoice::get_client_invoices($company);
	$company_estimates 	= Estimate::estimate_by_client($company);
	$company_expenses 	= Expense::expenses_by_client($company);
	$company_projects 	= Project::by_client($company);
	$company_contacts 	= self::get_client_contacts($company);

			if (count($company_invoices)) {
				foreach ($company_invoices as $invoice) {
					//delete invoice items
					self::$db->where('invoice_id',$invoice->inv_id)->delete('items');
				}
			}

			if (count($company_estimates)) {
				foreach ($company_estimates as $estimate) {
					//delete estimate items
					self::$db->where('estimate_id',$estimate->est_id)->delete('estimate_items');
				}
			}

			if (count($company_projects)) {
				foreach ($company_projects as $project) {
					// remove client from projects
					self::$db->set('client','0')->where('client',$company)->update('projects');
				}
			}

			if (count($company_expenses)) {
				foreach ($company_expenses as $expense) {
					//set client to blank in expenses
					self::$db->where('client',$company)->delete('expenses');
				}
			}

			//delete invoices
			self::$db->where('client',$company)->delete('invoices');
			//delete estimates
			self::$db->where('client',$company)->delete('estimates');

			// delete client payments
			self::$db->where('paid_by',$company)->delete('payments');
			//clear client activities
			self::$db->where(array('module'=>'Clients', 'module_field_id' => $company))->delete('activities');
			//delete company
			self::$db->where('co_id',$company)->delete('companies');


			if (count($company_contacts)) {
				foreach ($company_contacts as $contact) {
					//set contacts to blank
					self::$db->set('company','-')->where('company',$company)->update('account_details');
				}
			}

	}
	static function to_where($array = NULL){
		 return self::$db->where('user_id',$array['user_id'])->get('offers')->result();		 
	}
	static function job_where($array = NULL){
		 return self::$db->get('jobtypes')->result();		 
	}
	static function job_where_limit($array = NULL){
		$page   = $_GET['start'];
		$limit  = $_GET['length'];
		return self::$db->from('jobtypes')->limit($limit,$page)->get()->result();		 
   }
 

	static function approve_candidate($user_id = NULL){
		if($user_id && $user_id!=1){ 
		// $query = "SELECT ca.name,ca.email,cas.id as casid,ofs.id as offer_id,ofs.title,ofs.job_type,cas.app_status,ca.filename,ca.file_path FROM dgt_candidates as ca LEFT JOIN dgt_candidate_assoc as cas on cas.applicant_id=ca.id LEFT JOIN dgt_offers as ofs on ofs.id=cas.offer_id WHERE cas.appr_id=$user_id AND cas.app_status in (1,2)";


		$query = "SELECT ofs.*,ofa.status as approver_status,ofa.id as app_row_id from dgt_offers as ofs LEFT JOIN dgt_offer_approvers as ofa on ofs.id = ofa.offer  WHERE ofa.approvers=$user_id AND ofa.status in (0,1,2) AND view_status= 1  ORDER BY ofs.id DESC";

			return self::$db->query($query)->result();
	    }
	    elseif ($user_id==1) {	    
			// $query = "SELECT ca.name,ca.email,cas.id as casid,ofs.id as offer_id,ofs.title,ofs.job_type,cas.app_status,ca.filename,ca.file_path FROM dgt_candidates as ca LEFT JOIN dgt_candidate_assoc as cas on cas.applicant_id=ca.id LEFT JOIN dgt_offers as ofs on ofs.id=cas.offer_id GROUP by ca.id "; 
			$query = "SELECT ofs.*,ca.name,ca.email,cas.id as casid,ofa.status as approver_status,ofa.id as app_row_id from dgt_offers as ofs 
			LEFT JOIN dgt_offer_approvers as ofa on ofs.id = ofa.offer
			LEFT JOIN dgt_candidate_assoc as cas on cas.offer_id = ofs.id
			LEFT JOIN dgt_candidates as ca on ca.id = cas.applicant_id
			GROUP by ofs.id  ORDER BY ofs.id DESC";
			
		
			return self::$db->query($query)->result();
	        
	    }

	}

	static function approve_candidate_limit($user_id = NULL){
		$page = 0;
		$limit = 10;
		if(isset($_GET['start']) && $_GET['start'] != "")
		{
			$page   = $_GET['start'];
		}
		if(isset($_GET['length']) && $_GET['length'] != "")
		{
			$limit  = $_GET['length'];
		}
		
		
		if($user_id && $user_id!=1){ 
			$query = "SELECT ofs.*,ofa.status as approver_status,ofa.id as app_row_id from dgt_offers as ofs LEFT JOIN dgt_offer_approvers as ofa on ofs.id = ofa.offer  WHERE ofa.approvers=$user_id AND ofa.status in (0,1,2) AND view_status= 1  ORDER BY ofs.id DESC limit ".$page." ,".$limit;
			return self::$db->query($query)->result();
	    }
	    elseif ($user_id==1) {	 
			$query = "SELECT ofs.*,ofa.status as approver_status,ofa.id as app_row_id from dgt_offers as ofs LEFT JOIN dgt_offer_approvers as ofa on ofs.id = ofa.offer  GROUP by ofs.id  ORDER BY ofs.id DESC limit ".$page." ,".$limit;
			return self::$db->query($query)->result();
	    }
	}

	static function candidate_status($offer_id=FALSE,$new_status=FALSE)
	{
		if ($offer_id && $new_status) 
		{	 
			self::$db->set(array('status'=>$new_status))->where('id',$offer_id)->update('offer_approvers');
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	static function get_CandStatus($status_data=FALSE)
	{
			 

		//	$query = "SELECT distinct dgt_offers.title,dgt_offers.job_type,dgt_candidates.email,dgt_candidates.name FROM `dgt_offer_approvers` JOIN dgt_offers on dgt_offers.id = dgt_offer_approvers.offer JOIN dgt_candidates on dgt_candidates.offer_id=dgt_offer_approvers.offer WHERE dgt_offer_approvers.status = $incl[0]";
			// $query = "SELECT ca.name,ca.email, ofs.title,ofs.job_type,cas.id,cas.applicant_id,cas.offer_id,ofs.salary,ofs.annual_incentive_plan,ofs.long_term_incentive_plan,ca.id as caid, MIN(cas.app_status) as app_status from dgt_candidate_assoc as cas LEFT JOIN dgt_offers as ofs on ofs.id = cas.offer_id LEFT JOIN dgt_candidates as ca on ca.id=cas.applicant_id GROUP BY cas.applicant_id";
			//return $query;

				$query = "SELECT ofs.* from dgt_offers as ofs LEFT JOIN dgt_offer_approvers as ofa on ofs.id = ofa.offer GROUP BY ofs.id ORDER BY ofs.id DESC";




	        $status = self::$db->query($query)->result_array();
	        return $status ;
	       // return self::$db->last_query();
	}
	static function get_CandStatus_limit($status_data=FALSE)
	{
		$page   = $_GET['start'];
		$limit  = $_GET['length'];
		$query = "SELECT ofs.* from dgt_offers as ofs LEFT JOIN dgt_offer_approvers as ofa on ofs.id = ofa.offer GROUP BY ofs.id ORDER BY ofs.id DESC limit ".$page.",".$limit."";
	    $status = self::$db->query($query)->result_array();
	    return $status ;
	}

	static function usersMailid($users=FALSE)
	{
		if ($users) 
		{	$imps =implode(',',$users);
			$query ="SELECT id,username,email FROM `dgt_users` WHERE `id` IN ($imps)";
			$status = self::$db->query($query)->result_array();
			return $status ;

		}
	}

	static function applicantStatus($app_id=FALSE,$status=FALSE,$old_state=FALSE)
	{
		if ($status && $app_id) {

			if($old_state)
			{
				return self::$db->set(array('previous_state'=>$old_state,'app_status'=>$status))->where('applicant_id',$app_id)->update('candidate_assoc');
			}
			else
			{
				return self::$db->set(array('app_status'=>$status,'previous_state'=>$status))->where('applicant_id',$app_id)->update('candidate_assoc');
			}
		
			
		}
		else return FALSE;

	}
	static function applicantMail($appid=FALSE)
	{
		if($appid)
		{
			return self::$db->where('id',$appid)->get('candidates')->result_array();
		}
	}
	static function applicantStatus_old($offer_id=FALSE)
	{
		if($offer_id)
		{
			return self::$db->where('id',$offer_id)->get('offers')->result_array();
		}
	}

	static function getjobbyid($offer_id){
		 return self::$db->where('id',$offer_id)->get('offers')->row_array();		 
	}

	static function getCandidateOfferById($offer_id,$candidate_id){
		$res = array(
			'offer_id' => $offer_id,
			'applicant_id' => $candidate_id
		);
		 return self::$db->where($res)->get('candidate_assoc')->row_array();	
		 // return self::$db->last_query();	 
	}
	static function check_candidate($candidate)
	{
		return self::$db->get_where('offers',array('candidate'=>$candidate))->num_rows();
	}
	static function get_candidate_by_id($candidate)
	{
		return self::$db->get_where('dgt_registered_candidates',array('id'=>$candidate))->row_array();
	}

	static function add_user($data = array()) {
		self::$db->insert('dgt_users',$data);
		return self::$db -> insert_id();
	}
	static function add_account_user($data = array()) {
		self::$db->insert('dgt_account_details',$data);
		return self::$db -> insert_id();
	}


	function offer_letter_foramt( $job_id = '', $candidate_id ='')
	{
		$this->applib->set_locale();
       
        $job_data = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id)); 
        $user_data = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$candidate_id)); 
        $where_cond = array('candidate'=>$candidate_id,'title'=>$job_id);
        $offer = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $salary_break_details = $this->db->select('*')->from('dgt_candidate_ctc')->where("candidate_id",$candidate_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
        $department_id = $job_data['department_id'];
        $department_data = $this->db->select('*')->from('dgt_departments')->where("deptid",$department_id)->order_by('deptid', 'DESC')->limit(1)->get()->row_array();
        
		$branch_id = $department_data['branch_id'];
        if($branch_id == 0)
		{
			$branch_id = 4;
		}

        $branch_data = $this->db->select('*')->from('dgt_branches')->where("branch_id",$branch_id)->order_by('branch_id', 'DESC')->limit(1)->get()->row_array();
        $candidate_additional_info =  $this->db->select('*')->from('dgt_candidate_additional_information')->where("candidate_id",$candidate_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
        $offer_letter_attachment_template = $this->db->where(array('email_group'=>'offer_send', "branch_id" =>  $branch_id))->get('email_templates')->row_array();
        $offet_letter_format = $offer_letter_attachment_template['offer_letter_attachment'];
        $temp_entity_logo =  $branch_data['entity_logo'] ?  base_url('uploads/entity_logo/').$branch_id.'/'.$branch_data['entity_logo'] : base_url('uploads/default-logo.png');
        
        // GM/MD details
        $gm_md_name = '';
        $gm_md_result = array();
       
        $this->db->select("dad.fullname as gm_md_name, dd.designation as designation");
        $this->db->from("dgt_account_details as dad");
        $this->db->join("dgt_users as du","du.id = dad.user_id","inner");
        $this->db->join("dgt_designation as dd","dd.id = du.designation_id","inner");
        $this->db->where("du.id",$branch_data['entity_md_gm']);
        $this->db->where("du.status","1");
        
        $gm_md_result = $this->db->get()->row_array();

        if(!empty($gm_md_result))
        {
            if(!empty($gm_md_result['gm_md_name']))
            {
                $gm_md_name = $gm_md_result['gm_md_name'];
            }
            if(!empty($gm_md_result['designation']))
            {
                $gm_md_name .= '<br>('.$gm_md_result['designation'].')';
            }
        }

        //  HR MANAGER DETAILS
        $hr_name = '';
        $hr_result = array();
       
        $this->db->select("dad.fullname as hr_name, dd.designation as designation");
        $this->db->from("dgt_account_details as dad");
        $this->db->join("dgt_users as du","du.id = dad.user_id","inner");
        $this->db->join("dgt_designation as dd","dd.id = du.designation_id","inner");
        $this->db->where("du.id",$offer['hr_manager_id']);
        $this->db->where("du.status","1");
        
        $hr_result = $this->db->get()->row_array();

        if(!empty($hr_result))
        {
            if(!empty($hr_result['hr_name']))
            {
                $hr_name = $hr_result['hr_name'];
            }
            if(!empty($hr_result['designation']))
            {
                $hr_name .= '<br>('.$hr_result['designation'].')';
            }
        }

        $entity_logo = '<img style="width:'.config_item('invoice_logo_width').'px" src="'.$temp_entity_logo.'"/>';
        $addtion_ctc = $deduction_ctc= array();
        $addtion_ctc = json_decode($salary_break_details['addtional'], true);
        $deduction_ctc = json_decode($salary_break_details['deduction'], true);
        $basic_monthly = $basic_annual = $monthly_da = $annual_da = $monthly_hra = $annual_hra =
        $monthly_lta = $annual_lta = $monthly_medical = $annual_medical = $monthly_conveyance = $annual_conveyance = 
        $monthly_variable = $annual_varaible = $monthly_employer_pf = $annual_employer_pf= $monthly_employee_pf = 
        $annual_employee_pf = $monthly_accomodation = $annual_accomodation = $gross_salary_monthly = $gross_salary_annual = 
        $ctc_monthly = $ctc_annual = 0;
        
        $doj = '';
        $doj = $offer['date_of_joining'];
        if($doj !="" && $doj != "0000-00-00")
        {
            $doj = date("d-m-Y", strtotime($doj));
        }

        if(!empty($addtion_ctc))
        {
            
            $ctc_monthly = round($addtion_ctc[0]['unit_amount'] / 12, 2);
            $ctc_annual = round($addtion_ctc[0]['unit_amount'], 2);

            
            $basic_monthly = round($addtion_ctc[1]['unit_amount'] / 12, 2);
            $basic_annual = round($addtion_ctc[1]['unit_amount'], 2);
            $monthly_da =  round($addtion_ctc[2]['unit_amount'] / 12, 2);
            $annual_da =  round($addtion_ctc[2]['unit_amount'] , 2);
            $monthly_hra =  round($addtion_ctc[3]['unit_amount'] / 12, 2);
            $annual_hra =  round($addtion_ctc[3]['unit_amount'] , 2);
            $monthly_lta =  round($addtion_ctc[4]['unit_amount'] / 12, 2);
            $annual_lta =  round($addtion_ctc[4]['unit_amount'] , 2);
            $monthly_medical =  round($addtion_ctc[5]['unit_amount'] / 12, 2);
            $annual_medical =  round($addtion_ctc[5]['unit_amount'] , 2);
            $monthly_conveyance =  round($addtion_ctc[6]['unit_amount'] / 12, 2);
            $annual_conveyance =  round($addtion_ctc[6]['unit_amount'] , 2);
            $monthly_variable =  round($addtion_ctc[7]['unit_amount'] / 12, 2);
            $annual_varaible =  round($addtion_ctc[7]['unit_amount'] , 2);
            $monthly_employer_pf =  round($addtion_ctc[8]['unit_amount'] / 12, 2);
            $annual_employer_pf =  round($addtion_ctc[8]['unit_amount'] , 2);

            //  DEDUCITON FIELDS
            $monthly_employee_pf =  round($deduction_ctc[0]['unit_amount'] / 12, 2);
            $annual_employee_pf =  round($deduction_ctc[0]['unit_amount'] , 2);
            $monthly_accomodation =  round($deduction_ctc[1]['unit_amount'] / 12, 2);
            $annual_accomodation =  round($deduction_ctc[1]['unit_amount'] , 2);

            $gross_salary_monthly = $basic_monthly + $monthly_da + $monthly_hra + $monthly_lta + $monthly_medical + 
            $monthly_conveyance + $monthly_variable + $monthly_employer_pf; 

            $gross_salary_annual = $basic_annual + $annual_da + $annual_hra + $annual_lta + $annual_medical + 
            $annual_conveyance + $annual_varaible + $annual_employer_pf; 
        }

        $candidate_login_url = "<a href='".base_url('candidates')."' target='_blank'>Login</a>";

        $offet_letter_format  = str_replace("{ENTITY_LOGO}",$entity_logo,$offet_letter_format);
        $offet_letter_format  = str_replace("{OFFER_LETTER_DATE}",date("d/m/Y", strtotime($offer['updated'])),$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_TITLE}",'',$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_NAME}",ucwords(rtrim($user_data['first_name'].' '.$user_data['last_name'],' ')),$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_EMAIL}",$user_data['email'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_ADDRESS}",$candidate_additional_info['address'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_CITY}",$candidate_additional_info['city'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_STATE}",$candidate_additional_info['state'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_COUNTRY}",$candidate_additional_info['countrys'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_PINCODE}",$candidate_additional_info['pincode'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_MOBILE}",$candidate_additional_info['phone_number'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{JOB_ROLE}",$job_data['job_title'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{COMPANY_NAME}",$branch_data['branch_name'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CTC(IN_DIGIT)}",number_format($offer['salary'], 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CTC(IN_WORDS)}",ucwords(getIndianCurrency($offer['salary'])) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{BASIC_MONTHLY}",number_format($basic_monthly, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{BASIC_ANNUAL}",number_format($basic_annual, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{MONTHLY_DA}",number_format($monthly_da, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ANNUAL_DA}",number_format($annual_da, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{MONTHLY_HRA}", number_format($monthly_hra, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ANNUAL_HRA}", number_format($annual_hra, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{MONTHLY_LTA}",number_format($monthly_lta, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ANNUAL_LTA}",number_format($annual_lta, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{MONTHLY_MEDICAL_ALLOWANCE}",number_format($monthly_medical, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ANNUAL_MEDICAL_ALLOWANCE}",number_format($annual_medical, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{MONTHLY_CONVEYANCE_ALLOWANCE}",number_format($monthly_conveyance, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ANNUAL_CONVEYANCE_ALLOWANCE}",number_format($annual_conveyance, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{MONTHLY_VARIABLE}",number_format($monthly_variable, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ANNUAL_VARIABLE}",number_format($annual_varaible, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{EMPLOYER_PF_CONTRIBUTION_MONTHLY}",number_format($monthly_employer_pf, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{EMPLOYER_PF_CONTRIBUTION_ANNUAL}",number_format($annual_employer_pf , 2),$offet_letter_format);
        $offet_letter_format  = str_replace("{EMPLOYEE_PF_CONTRIBUTION_MONTHLY}",number_format($monthly_employee_pf, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{EMPLOYEE_PF_CONTRIBUTION_ANNUAL}",number_format($annual_employee_pf, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ACCOMODATION_MONTHLY}",number_format($monthly_accomodation, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ACCOMODATION_ANNUAL}",number_format($annual_accomodation, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_LOGIN_URL}",$candidate_login_url ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ENTITY_MD_GM}",$gm_md_name ,$offet_letter_format);
        $offet_letter_format  = str_replace("{ENTITY_NAME}",$branch_data['branch_name'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_DOB}",$branch_data['branch_name'] ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_GROSS_SALARY_MONTHLY}",$gross_salary_monthly ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_GROSS_SALARY_ANNUAL}",$gross_salary_annual ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_CTC_MONTHLY}",number_format($ctc_monthly, 2) ,$offet_letter_format);
        $offet_letter_format  = str_replace("{CANDIDATE_CTC_ANNUAL}",number_format($ctc_annual, 2) ,$offet_letter_format);

        $offet_letter_format  = str_replace("{DATE_OF_JOINING}",$doj ,$offet_letter_format);
        $offet_letter_format  = str_replace("{HR_MANAGER}",$hr_name ,$offet_letter_format);
        $html = $offet_letter_format;
		return $html;
	}

	function offer_letter_email_body( $job_id = '', $candidate_id ='')
	{
		$this->applib->set_locale();
       
        $job_data = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id)); 
        $user_data = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$candidate_id)); 
        $where_cond = array('candidate'=>$candidate_id,'title'=>$job_id);
        $offer = $this->db->select('*')->from('dgt_offers')->where($where_cond)->get()->row_array();
        $salary_break_details = $this->db->select('*')->from('dgt_candidate_ctc')->where("candidate_id",$candidate_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
        $department_id = $job_data['department_id'];
        $department_data = $this->db->select('*')->from('dgt_departments')->where("deptid",$department_id)->order_by('deptid', 'DESC')->limit(1)->get()->row_array();
        
		$branch_id = $department_data['branch_id'];
        if($branch_id == 0)
		{
			$branch_id = 4;
		}

        $branch_data = $this->db->select('*')->from('dgt_branches')->where("branch_id",$branch_id)->order_by('branch_id', 'DESC')->limit(1)->get()->row_array();
        $candidate_additional_info =  $this->db->select('*')->from('dgt_candidate_additional_information')->where("candidate_id",$candidate_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
        $offer_letter_attachment_template = $this->db->where(array('email_group'=>'offer_send', "branch_id" =>  $branch_id))->get('email_templates')->row_array();
        // $offet_letter_format = $offer_letter_attachment_template['offer_letter_attachment'];

		$offer_letter_email_subject = $offer_letter_attachment_template['subject'];
		$offer_letter_email_body = $offer_letter_attachment_template['template_body'];

        $temp_entity_logo =  $branch_data['entity_logo'] ?  base_url('uploads/entity_logo/').$branch_id.'/'.$branch_data['entity_logo'] : base_url('uploads/default-logo.png');
        
        // GM/MD details
        $gm_md_name = '';
        $gm_md_result = array();
       
        $this->db->select("dad.fullname as gm_md_name, dd.designation as designation");
        $this->db->from("dgt_account_details as dad");
        $this->db->join("dgt_users as du","du.id = dad.user_id","inner");
        $this->db->join("dgt_designation as dd","dd.id = du.designation_id","inner");
        $this->db->where("du.id",$branch_data['entity_md_gm']);
        $this->db->where("du.status","1");
        
        $gm_md_result = $this->db->get()->row_array();

        if(!empty($gm_md_result))
        {
            if(!empty($gm_md_result['gm_md_name']))
            {
                $gm_md_name = $gm_md_result['gm_md_name'];
            }
            if(!empty($gm_md_result['designation']))
            {
                $gm_md_name .= '<br>('.$gm_md_result['designation'].')';
            }
        }

        //  HR MANAGER DETAILS
        $hr_name = '';
        $hr_result = array();
       
        $this->db->select("dad.fullname as hr_name, dd.designation as designation");
        $this->db->from("dgt_account_details as dad");
        $this->db->join("dgt_users as du","du.id = dad.user_id","inner");
        $this->db->join("dgt_designation as dd","dd.id = du.designation_id","inner");
        $this->db->where("du.id",$offer['hr_manager_id']);
        $this->db->where("du.status","1");
        
        $hr_result = $this->db->get()->row_array();

        if(!empty($hr_result))
        {
            if(!empty($hr_result['hr_name']))
            {
                $hr_name = $hr_result['hr_name'];
            }
            if(!empty($hr_result['designation']))
            {
                $hr_name .= '<br>('.$hr_result['designation'].')';
            }
        }

        $entity_logo = '<img style="width:'.config_item('invoice_logo_width').'px" src="'.$temp_entity_logo.'"/>';
        $addtion_ctc = $deduction_ctc= array();
        $addtion_ctc = json_decode($salary_break_details['addtional'], true);
        $deduction_ctc = json_decode($salary_break_details['deduction'], true);
        $basic_monthly = $basic_annual = $monthly_da = $annual_da = $monthly_hra = $annual_hra =
        $monthly_lta = $annual_lta = $monthly_medical = $annual_medical = $monthly_conveyance = $annual_conveyance = 
        $monthly_variable = $annual_varaible = $monthly_employer_pf = $annual_employer_pf= $monthly_employee_pf = 
        $annual_employee_pf = $monthly_accomodation = $annual_accomodation = $gross_salary_monthly = $gross_salary_annual = 
        $ctc_monthly = $ctc_annual = 0;
        
        $doj = '';
        $doj = $offer['date_of_joining'];
        if($doj !="" && $doj != "0000-00-00")
        {
            $doj = date("d-m-Y", strtotime($doj));
        }

        if(!empty($addtion_ctc))
        {
            $ctc_monthly = round($addtion_ctc[0]['unit_amount'] / 12, 2);
            $ctc_annual = round($addtion_ctc[0]['unit_amount'], 2);

            
            $basic_monthly = round($addtion_ctc[1]['unit_amount'] / 12, 2);
            $basic_annual = round($addtion_ctc[1]['unit_amount'], 2);
            $monthly_da =  round($addtion_ctc[2]['unit_amount'] / 12, 2);
            $annual_da =  round($addtion_ctc[2]['unit_amount'] , 2);
            $monthly_hra =  round($addtion_ctc[3]['unit_amount'] / 12, 2);
            $annual_hra =  round($addtion_ctc[3]['unit_amount'] , 2);
            $monthly_lta =  round($addtion_ctc[4]['unit_amount'] / 12, 2);
            $annual_lta =  round($addtion_ctc[4]['unit_amount'] , 2);
            $monthly_medical =  round($addtion_ctc[5]['unit_amount'] / 12, 2);
            $annual_medical =  round($addtion_ctc[5]['unit_amount'] , 2);
            $monthly_conveyance =  round($addtion_ctc[6]['unit_amount'] / 12, 2);
            $annual_conveyance =  round($addtion_ctc[6]['unit_amount'] , 2);
            $monthly_variable =  round($addtion_ctc[7]['unit_amount'] / 12, 2);
            $annual_varaible =  round($addtion_ctc[7]['unit_amount'] , 2);
            $monthly_employer_pf =  round($addtion_ctc[8]['unit_amount'] / 12, 2);
            $annual_employer_pf =  round($addtion_ctc[8]['unit_amount'] , 2);

            //  DEDUCITON FIELDS
            $monthly_employee_pf =  round($deduction_ctc[0]['unit_amount'] / 12, 2);
            $annual_employee_pf =  round($deduction_ctc[0]['unit_amount'] , 2);
            $monthly_accomodation =  round($deduction_ctc[1]['unit_amount'] / 12, 2);
            $annual_accomodation =  round($deduction_ctc[1]['unit_amount'] , 2);

            $gross_salary_monthly = $basic_monthly + $monthly_da + $monthly_hra + $monthly_lta + $monthly_medical + 
            $monthly_conveyance + $monthly_variable + $monthly_employer_pf; 

            $gross_salary_annual = $basic_annual + $annual_da + $annual_hra + $annual_lta + $annual_medical + 
            $annual_conveyance + $annual_varaible + $annual_employer_pf; 
        }

        $candidate_login_url = "<a href='".base_url('candidates')."' target='_blank'>Login</a>";
		

		//  Email subject 
		$offer_letter_email_subject  = str_replace("{ENTITY_LOGO}",$entity_logo,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{OFFER_LETTER_DATE}",date("d/m/Y", strtotime($offer['updated'])),$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_TITLE}",'',$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_NAME}",ucwords(rtrim($user_data['first_name'].' '.$user_data['last_name'],' ')),$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_EMAIL}",$user_data['email'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_ADDRESS}",$candidate_additional_info['address'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_CITY}",$candidate_additional_info['city'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_STATE}",$candidate_additional_info['state'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_COUNTRY}",$candidate_additional_info['countrys'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_PINCODE}",$candidate_additional_info['pincode'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_MOBILE}",$candidate_additional_info['phone_number'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{JOB_ROLE}",$job_data['job_title'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{COMPANY_NAME}",$branch_data['branch_name'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CTC(IN_DIGIT)}",number_format($offer['salary'], 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CTC(IN_WORDS)}",ucwords(getIndianCurrency($offer['salary'])) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{BASIC_MONTHLY}",number_format($basic_monthly, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{BASIC_ANNUAL}",number_format($basic_annual, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{MONTHLY_DA}",number_format($monthly_da, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ANNUAL_DA}",number_format($annual_da, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{MONTHLY_HRA}", number_format($monthly_hra, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ANNUAL_HRA}", number_format($annual_hra, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{MONTHLY_LTA}",number_format($monthly_lta, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ANNUAL_LTA}",number_format($annual_lta, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{MONTHLY_MEDICAL_ALLOWANCE}",number_format($monthly_medical, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ANNUAL_MEDICAL_ALLOWANCE}",number_format($annual_medical, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{MONTHLY_CONVEYANCE_ALLOWANCE}",number_format($monthly_conveyance, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ANNUAL_CONVEYANCE_ALLOWANCE}",number_format($annual_conveyance, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{MONTHLY_VARIABLE}",number_format($monthly_variable, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ANNUAL_VARIABLE}",number_format($annual_varaible, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{EMPLOYER_PF_CONTRIBUTION_MONTHLY}",number_format($monthly_employer_pf, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{EMPLOYER_PF_CONTRIBUTION_ANNUAL}",number_format($annual_employer_pf , 2),$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{EMPLOYEE_PF_CONTRIBUTION_MONTHLY}",number_format($monthly_employee_pf, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{EMPLOYEE_PF_CONTRIBUTION_ANNUAL}",number_format($annual_employee_pf, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ACCOMODATION_MONTHLY}",number_format($monthly_accomodation, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ACCOMODATION_ANNUAL}",number_format($annual_accomodation, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_LOGIN_URL}",$candidate_login_url ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ENTITY_MD_GM}",$gm_md_name ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{ENTITY_NAME}",$branch_data['branch_name'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_DOB}",$branch_data['branch_name'] ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_GROSS_SALARY_MONTHLY}",$gross_salary_monthly ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_GROSS_SALARY_ANNUAL}",$gross_salary_annual ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_CTC_MONTHLY}",number_format($ctc_monthly, 2) ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{CANDIDATE_CTC_ANNUAL}",number_format($ctc_annual, 2) ,$offer_letter_email_subject);

        $offer_letter_email_subject  = str_replace("{DATE_OF_JOINING}",$doj ,$offer_letter_email_subject);
        $offer_letter_email_subject  = str_replace("{HR_MANAGER}",$hr_name ,$offer_letter_email_subject);
		// email subject End  
        
		//	Email Body Start
		$offer_letter_email_body  = str_replace("{ENTITY_LOGO}",$entity_logo,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{OFFER_LETTER_DATE}",date("d/m/Y", strtotime($offer['updated'])),$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_TITLE}",'',$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_NAME}",ucwords(rtrim($user_data['first_name'].' '.$user_data['last_name'],' ')),$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_EMAIL}",$user_data['email'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_ADDRESS}",$candidate_additional_info['address'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_CITY}",$candidate_additional_info['city'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_STATE}",$candidate_additional_info['state'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_COUNTRY}",$candidate_additional_info['countrys'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_PINCODE}",$candidate_additional_info['pincode'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_MOBILE}",$candidate_additional_info['phone_number'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{JOB_ROLE}",$job_data['job_title'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{COMPANY_NAME}",$branch_data['branch_name'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CTC(IN_DIGIT)}",number_format($offer['salary'], 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CTC(IN_WORDS)}",ucwords(getIndianCurrency($offer['salary'])) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{BASIC_MONTHLY}",number_format($basic_monthly, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{BASIC_ANNUAL}",number_format($basic_annual, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{MONTHLY_DA}",number_format($monthly_da, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ANNUAL_DA}",number_format($annual_da, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{MONTHLY_HRA}", number_format($monthly_hra, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ANNUAL_HRA}", number_format($annual_hra, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{MONTHLY_LTA}",number_format($monthly_lta, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ANNUAL_LTA}",number_format($annual_lta, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{MONTHLY_MEDICAL_ALLOWANCE}",number_format($monthly_medical, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ANNUAL_MEDICAL_ALLOWANCE}",number_format($annual_medical, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{MONTHLY_CONVEYANCE_ALLOWANCE}",number_format($monthly_conveyance, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ANNUAL_CONVEYANCE_ALLOWANCE}",number_format($annual_conveyance, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{MONTHLY_VARIABLE}",number_format($monthly_variable, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ANNUAL_VARIABLE}",number_format($annual_varaible, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{EMPLOYER_PF_CONTRIBUTION_MONTHLY}",number_format($monthly_employer_pf, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{EMPLOYER_PF_CONTRIBUTION_ANNUAL}",number_format($annual_employer_pf , 2),$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{EMPLOYEE_PF_CONTRIBUTION_MONTHLY}",number_format($monthly_employee_pf, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{EMPLOYEE_PF_CONTRIBUTION_ANNUAL}",number_format($annual_employee_pf, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ACCOMODATION_MONTHLY}",number_format($monthly_accomodation, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ACCOMODATION_ANNUAL}",number_format($annual_accomodation, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_LOGIN_URL}",$candidate_login_url ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ENTITY_MD_GM}",$gm_md_name ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{ENTITY_NAME}",$branch_data['branch_name'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_DOB}",$branch_data['branch_name'] ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_GROSS_SALARY_MONTHLY}",$gross_salary_monthly ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_GROSS_SALARY_ANNUAL}",$gross_salary_annual ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_CTC_MONTHLY}",number_format($ctc_monthly, 2) ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{CANDIDATE_CTC_ANNUAL}",number_format($ctc_annual, 2) ,$offer_letter_email_body);

        $offer_letter_email_body  = str_replace("{DATE_OF_JOINING}",$doj ,$offer_letter_email_body);
        $offer_letter_email_body  = str_replace("{HR_MANAGER}",$hr_name ,$offer_letter_email_body);
		// Email Body End 

		$html = array();
		$html = array(
			"email_subject" => $offer_letter_email_subject,
			"email_body" => $offer_letter_email_body,
			"entity_name" => $branch_data['branch_name']
		);
		return $html;
	}

}

/* End of file model.php */
