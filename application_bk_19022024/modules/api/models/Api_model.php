<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');

class Api_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

		$this->user = 'dgt_users U';
		$this->users = 'dgt_users';
		$this->account_details = 'dgt_account_details AD';
		$this->account_detail = 'dgt_account_details';
		$this->companies = 'dgt_companies C';
		$this->categories = 'dgt_categories CA';
		$this->countries = 'dgt_countries COU';
		$this->designation = 'dgt_designation D';
		$this->designation_chk = 'dgt_designation';
		$this->departments = 'dgt_departments DE';
		$this->department = 'dgt_departments';
		$this->holidays = 'dgt_holidays H';
		$this->holiday = 'dgt_holidays';
		$this->user_leaves = 'dgt_user_leaves UL';
		$this->user_leave = 'dgt_user_leaves';
		$this->leave_types = 'dgt_leave_types LT';
		$this->payslips = 'dgt_payslip PS';
		$this->salary = 'dgt_salary SA';
		$this->projects = 'dgt_projects PJ';
		$this->files = 'dgt_files FL';
		$this->tasks = 'dgt_tasks TK';
		$this->tasks_timer = 'dgt_tasks_timer TT';
		$this->taskfiles = 'dgt_task_files TKF';
		$this->comments = 'dgt_comments CM ';
		$this->expenses = 'dgt_expenses EX ';
		$this->estimate = 'dgt_estimates ES ';
		$this->estimate_items = 'dgt_estimate_items ESI ';
		$this->invoice = 'dgt_invoices IN';
		$this->items = 'dgt_items IT';
		$this->payments = 'dgt_payments PY';
		$this->payment_methods = 'dgt_payment_methods PYM';

	 
	}

	public function employee_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 if($role == 1 || $role == 4){	
			$user_id = $record['user_id'];
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('U.id as user_id,username,U.email,U.role_id,U.designation_id,U.activated,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.dob !="0000-00-00",AD.dob,"-") as dob,AD.gender,AD.phone,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,IF(DE.deptid IS NOT NULL,DE.deptid,0) as department_id,IF(COU.value IS NOT NULL,COU.value,"-")as countryname');

		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id=U.id','LEFT');
		//$this->db->join($this->companies,'C.co_id=AD.company','LEFT');
		$this->db->join($this->designation,'D.id=U.designation_id','LEFT');
		$this->db->join($this->departments,'DE.deptid=U.department_id','LEFT');
		$this->db->join($this->countries,'COU.id=AD.country','LEFT');
		$this->db->where('U.role_id',3);
		$this->db->where('U.activated',1);
		$this->db->where('U.banned',0);
 		if(!empty($inputs['email'])){
			$this->db->like('U.email', $inputs['email'], 'BOTH');
		} 
		if(!empty($inputs['fullname'])){
			$this->db->like('AD.fullname', $inputs['fullname'], 'BOTH');
		} 
		if(!empty($inputs['designation'])){
			$this->db->like('U.designation_id', $inputs['designation'], 'BOTH');
		}
		if(!empty($inputs['department'])){
			$this->db->like('D.department_id', $inputs['department'], 'BOTH');
		} 
		if(!empty($inputs['employee_id'])){
			$id =  $inputs['employee_id'];
			$id = str_replace('FP-', '', $id);
			$this->db->like('U.id', $id, 'BOTH');
		} 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('U.id', 'ASC');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result();
		 }


		  }else{
		  	if($type==1){
		  		return 0;
		  	}else{
		  		return array();
		  	}
		  }
		} 
	}

	public function remove_profile($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $input['user_id'];
		 	if($role == 1 || $role == 4){
				$this->db->where('role_id',3);
				$this->db->where('id',$user_id);
				return $this->db->update($this->user, array('banned'=>1,'activated'=>0));
		 	}
		}
		return $records;
	}

	public function view_profile($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

		 	if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}
		 	// 	$this->db->select('U.id as user_id,username,U.email,U.role_id,U.designation_id,U.activated,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.dob !="0000-00-00",AD.dob,"-") as dob,AD.gender,AD.avatar,AD.phone,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,IF(D.department_id IS NOT NULL,D.department_id,0) as department_id,IF(AD.city IS NOT NULL,AD.city,"-") as city,IF(AD.country IS NOT NULL,AD.country,"-") as country,address,IF(COU.value IS NOT NULL,COU.value,"-") as countryname');

				// $this->db->from($this->user);
				// $this->db->join($this->account_details,'AD.user_id=U.id','LEFT');
				// //$this->db->join($this->companies,'C.co_id=AD.company','LEFT');
				// $this->db->join($this->designation,'D.id=U.designation_id','LEFT');
				// $this->db->join($this->departments,'DE.deptid=D.department_id','LEFT');
				// $this->db->join($this->countries,'COU.id=AD.country','LEFT');
				// $this->db->where('U.role_id',3);
				// $this->db->where('U.id',$user_id);
				// $records = $this->db->get()->row_array();
				// $records['education_details'] = json_encode($this->education_details($user_id));
				// $records['experience_information'] = json_encode($this->experience_information($user_id));

				$bank_statutories = $this->db->get_where('bank_statutory',array('user_id'=>$user_id))->row_array(); 
				$all_addtional = $this->db->get_where('bank_statutory',array('user_id'=>$user_id))->row_array();
				$overtime=$this->db->where('user_id',$user_id)->get('overtime')->result_array(); 
				
				$leave_details = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where ul.user_id='".$user_id."' and lt.branch_id = ad.branch_id  ")->result_array();

				$documents = $this->db->get_where('user_document',array('user_id'=>$user_id))->result_array();
				$all_file_attachments = $this->db->get_where('user_files',array('user_id'=>$user_id))->result_array();

				if(!empty($documents)){
					foreach($documents as $key=>$doc){
						$documents[$key]['document'] = (!empty($doc['document']))?base_url().'assets/uploads/user_document/'.$doc['document']:'';
					}
				}
				if(!empty($all_file_attachments)){
					foreach($all_file_attachments as $key=>$attach){
						$all_file_attachments[$key]['attach_file'] = (!empty($attach['attach_file']))?base_url().'assets/uploads/profile_attachments/'.$attach['attach_file']:'';
					}
				}
				$records['employee_details'] = !empty($this->get_employeedetailById($user_id))?$this->get_employeedetailById($user_id):(object)[];
				if(empty($records['employee_details']['reporting_to'])){
					$records['employee_details']['reporting_to'] = '';
				}
                $personal_details = $this->get_employeepersonalById($user_id);
                $records['personal_info'] = !empty($personal_details['personal_info'])?json_decode($personal_details['personal_info']):(object)[];
                $records['emergency_info'] = !empty($personal_details['emergency_info'])?json_decode($personal_details['emergency_info']):(object)[];
                $records['bank_info'] = !empty($personal_details['bank_info'])?json_decode($personal_details['bank_info']):(object)[];

                $records['family_members_info'] = !empty($personal_details['family_members_info'])?json_decode($personal_details['family_members_info']):[];
                $records['education_details'] = !empty($personal_details['education_details'])?json_decode($personal_details['education_details']):[];
                $records['experience_details'] = !empty($personal_details['personal_details'])?json_decode($personal_details['personal_details']):[];

                $records['in_bank_statutories'] = !empty($bank_statutories['bank_statutory'])?json_decode($bank_statutories['bank_statutory']):(object)[];
				if(!empty($bank_statutories)){
					$records['in_bank_statutories']->salary = $bank_statutories['salary'];
					$records['in_bank_statutories']->payment_type = $bank_statutories['payment_type'];
				}
                $records['addtional'] = !empty($all_addtional['pf_addtional'])?json_decode($all_addtional['pf_addtional'],TRUE):[];
                $records['deduction'] = !empty($all_addtional['pf_deduction'])?json_decode($all_addtional['pf_deduction'],TRUE):[];
				$records['leaves']	= !empty($leave_details)?($leave_details):(object)[];
				$records['documents']	= !empty($documents)?($documents):(object)[];
				$records['attachments']	= !empty($all_file_attachments)?($all_file_attachments):(object)[];
		 	}
		}
		return $records;
	}


	Public function get_employeedetailById($id)
	{
	   $user_details =  $this->db->get_where('dgt_users',array('id'=>$id))->row_array();
	   if(!empty($teamlead_id)){
	   	return $this->db->select('U.*,AD.*,d.deptname,ds.designation,r.fullname as reporting_to')
	            ->from('dgt_users U')
	            ->join('dgt_account_details AD', 'U.id = AD.user_id')
	            ->join('dgt_account_details r', 'U.teamlead_id = r.user_id')
	            ->join('dgt_designation ds', 'U.designation_id = ds.id','LEFT')
	            ->join('dgt_departments d', 'U.department_id = d.deptid','LEFT')
	            ->where('U.id',$id)
	            ->get()->row_array();
	   }
	   else{
		return $this->db->select('U.*,AD.*,d.deptname,ds.designation')
	            ->from('dgt_users U')
	            ->join('dgt_account_details AD', 'U.id = AD.user_id')
	            ->join('dgt_designation ds', 'U.designation_id = ds.id','LEFT')
	            ->join('dgt_departments d', 'U.department_id = d.deptid','LEFT')
	            ->where('U.id',$id)
	            ->get()->row_array();
	   }
	}

	public function get_employeepersonalById($id)
	{
		return $this->db->get_where('dgt_users_personal_details',array('user_id'=>$id))->row_array();
	}

	public function education_details($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('dgt_profile_education_details')->result();
	}

	public function experience_information($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('dgt_profile_experience_informations')->result();
	}

	public function profile_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}
		 		$email_check = $this->db->get_where('users',array('id !='=>$user_id,'email'=>$input['email']))->num_rows();

		        if($email_check > 0){
		           return 2;
		        }else{
			 		$account_details = array();
					
					$account_details = array('fullname'=>$input['fullname'],'phone'=>$input['phone']);
			 		if(!empty($input['city'])){
			 			$account_details['city'] = $input['city'];  
			 		}
			 		if(!empty($input['country'])){
			 			$account_details['country'] = $input['country'];  
			 		}
			 		if(!empty($input['address'])){
			 			$account_details['address'] = $input['address'];  
			 		}

					if(!empty($input['gender'])){
						$account_details['gender'] = $input['gender'];	
					}
					if(!empty($input['dob']) && $input['dob'] !='0000-00-00'){
						$account_details['dob'] = date('y-m-d',strtotime($input['dob']));	
					}
					if(!empty($input['emp_doj']) && $input['emp_doj'] !='0000-00-00'){
						$account_details['doj'] = $input['emp_doj'];	
					}
					if(!empty($input['state'])){
						$account_details['state'] = $input['state'];	
					}
					if(!empty($input['pincode'])){
						$account_details['pincode'] = $input['pincode'];	
					}

					$user = array('email'=>$input['email']);
					
					$user['designation_id'] = !empty($input['designation_id'])?$input['designation_id']:"0";	
					
					$user['department_id'] = !empty($input['department_id'])?$input['department_id']:"0";	
					
					$user['teamlead_id'] = !empty($input['reporting_to'])?$input['reporting_to']:'';

					$user['user_type'] = !empty($input['user_type'])?$input['user_type']:'';

					if(!empty($input['reporting_to'])){
			 			$ro = $input['reporting_to'];
			 			
			 		  	$res = array(
		                'is_teamlead' =>'yes' 
		                );
		                $this->db->where('id',$ro);
		                $this->db->update('dgt_users',$res);
			 		}


					$this->db->where('user_id', $user_id);
					$this->db->update($this->account_detail, $account_details);
	 				
	 				$this->db->where('id', $user_id);
					$this->db->update($this->users, $user);
						if($role == 3){
						$this->db->where('user_id', $user_id);
						$this->db->delete('dgt_profile_education_details');
						
						$education_details  = !empty($input['education_details'])?$input['education_details']:'';
						$education_new = array();
						if(!empty($education_details)){
							$education_new = json_decode($education_details,true);
							$final = array();
							foreach ($education_new as $keyvalue) {
								$keyvalue['user_id'] = $user_id;
								$final[] = $keyvalue;
							}
							$education_new = $final;
						}
						 
						
						if(count($education_new)>0){
							$this->db->insert_batch('dgt_profile_education_details', $education_new);
							 
						}

						$this->db->where('user_id', $user_id);
						$this->db->delete('dgt_profile_experience_informations');

						$experience_information  = !empty($input['experience_information'])?$input['experience_information']:'';
						$experience_information_new = array();
						if(!empty($experience_information)){
							$experience_information_new = json_decode($experience_information,true);
							$final = array();
							foreach ($experience_information_new as $keyvalue) {
								$keyvalue['user_id'] = $user_id;
								$final[] = $keyvalue;
							}
							$experience_information_new = $final;
						}
					 	 
				 
						if(count($experience_information_new) > 0){
							$this->db->insert_batch('dgt_profile_experience_informations', $experience_information_new);	
							 
						}
					}
					 
					return 1;
				}
			}
		}		
	}

	public function basic_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		
		 		$basic_info = array(
                'fullname' =>$input['fullname'],
                'dob' =>date("Y-m-d", strtotime($input['dob'])),
                'gender' =>$input['gender'],
                'address' =>$input['address'],
                'state' =>$input['state'],
                'country' =>$input['country'],
                'pincode' =>$input['pincode'],
                'phone' =>$input['phone']
            );

		 		
        $pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $basic_info['user_id'] = $user_id;
            $this->db->insert('account_details',$basic_info);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('account_details',$basic_info);
        }

        $some_details = array(
            'department_id' => $input['department_id'],
            'designation_id'=> $input['designation_id'],
            'teamlead_id'=> $input['teamlead_id']
        );
        $this->db->where('id',$user_id);
        $this->db->update('users',$some_details);
				 
				return 1;
			}
		}		
	}

	public function personal_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		$personal_info = array(
                'passport_no' =>$input['passport_no'],
                'passport_expiry' =>$input['passport_expiry'],
                'tel_number' =>$input['tel_number'],
                'nationality' =>$input['nationality'],
                'religion' =>$input['religion'],
                'marital_status' =>$input['marital_status'],
                'spouse' =>$input['spouse'],
                'no_children' =>$input['no_children']
            );
        $result = array(
                'personal_info' => json_encode($personal_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
           $this->db->where('user_id',$user_id);
           $this->db->update('users_personal_details',$result);
        }
				 
				return 1;
			}
		}		
	}

	public function emergency_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		$emergency_info = array(
                'contact_name1' =>$input['contact_name1'],
                'relationship1' =>$input['relationship1'],
                'contact1_phone1' =>$input['contact1_phone1'],
                'contact1_phone2' =>$input['contact1_phone2'],
                'contact_name2' =>$input['contact_name2'],
                'relationship2' =>$input['relationship2'],
                'contact2_phone1' =>$input['contact2_phone1'],
                'contact2_phone2' =>$input['contact2_phone2']
            );
        $result = array(
                'emergency_info' => json_encode($emergency_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }
				 
				return 1;
			}
		}		
	}

	public function bank_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		 $bank_info = array(
                'bank_name' =>$input['bank_name'],
                'bank_ac_no' =>$input['bank_ac_no'],
                'ifsc_code' =>$input['ifsc_code'],
                'pan_no' =>$input['pan_no']
            );
        $result = array(
                'bank_info' => json_encode($bank_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }
				 
				return 1;
			}
		}		
	}

	public function family_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		$member_names = $input['member_name']; 
		        $member_relationship = $input['member_relationship']; 
		        $member_dob = $input['member_dob']; 
		        $member_phone = $input['member_phone']; 
		        $family_members = array();
		        for($i = 0; $i< count($member_names); $i++)
		            {
		                $members = array(
		                    'member_name'=>$member_names[$i],
		                    'member_relationship'=>$member_relationship[$i],
		                    'member_dob'=>$member_dob[$i],
		                    'member_phone'=>$member_phone[$i]
		                );
		                $family_members[] = $members;
		            }
		        $result = array(
		                'family_members_info' => json_encode($family_members)
		            );
		        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
		        if($pers_check == 0)
		        {
		            $result['user_id'] = $user_id;
		            $this->db->insert('dgt_users_personal_details',$result);
		        }else{
		           $this->db->where('user_id',$user_id);
		           $this->db->update('users_personal_details',$result);
		        }
				 
				return 1;
			}
		}		
	}

	public function education_info_update1($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 	// 	$institute = $input['institute']; 
		  //      $subject = $input['subject']; 
		  //      $start_date = $input['start_date']; 
		  //      $end_date = $input['end_date']; 
		  //      $degree = $input['degree']; 
		  //      $grade = $input['grade']; 
		  //      $educations = array();
		  //      for($i = 0; $i< count($institute); $i++)
    //         {
    //             $education = array(
    //                 'institute'=>$institute[$i],
    //                 'subject'=>$subject[$i],
    //                 'start_date'=>$start_date[$i],
    //                 'end_date'=>$end_date[$i],
    //                 'degree'=>$degree[$i],
    //                 'grade'=>$grade[$i]
    //             );
    //             $educations[] = $education;
    //         }
    //         // echo $user_id; exit;
    //         print_r($educations); exit;
        $result = array(
                'education_details' => json_decode($input['education_info'])
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => json_encode($educations)
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}

public function education_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 	// 	$institute = $input['institute']; 
		  //      $subject = $input['subject']; 
		  //      $start_date = $input['start_date']; 
		  //      $end_date = $input['end_date']; 
		  //      $degree = $input['degree']; 
		  //      $grade = $input['grade']; 
		  //      $educations = array();
		  //      for($i = 0; $i< count($institute); $i++)
    //         {
    //             $education = array(
    //                 'institute'=>$institute[$i],
    //                 'subject'=>$subject[$i],
    //                 'start_date'=>$start_date[$i],
    //                 'end_date'=>$end_date[$i],
    //                 'degree'=>$degree[$i],
    //                 'grade'=>$grade[$i]
    //             );
    //             $educations[] = $education;
    //         }
    //         // echo $user_id; exit;
    //         print_r($educations); exit;
        $result = array(
                'education_details' => $input['education_info']
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => $input['education_info']
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}
	public function experience_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 	// 	$company_name = $input['company_name']; 
		  //      $location = $input['location']; 
		  //      $job_position = $input['job_position']; 
		  //      $period_from = $input['period_from']; 
		  //      $period_to = $input['period_to'];
		  //      $personals = array();
		  //      for($i = 0; $i< count($company_name); $i++)
		  //          {
		  //              $personal = array(
		  //                  'company_name'=>$company_name[$i],
		  //                  'location'=>$location[$i],
		  //                  'job_position'=>$job_position[$i],
		  //                  'period_from'=>$period_from[$i],
		  //                  'period_to'=>$period_to[$i]
		  //              );
		  //              $personals[] = $personal;
		  //          }
            // echo $user_id; exit;
        $result = array(
                'personal_details' => $input['experience_info']
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'personal_details' => $input['experience_info']
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}

public function experience_info_update_backup($token,$input)
	{

		$record =  $this->get_role_and_userid($token);
		// print_r($record);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		//$n=$input['experience_info'][0];
		 		// print_r($input['company_name']);

		 		 $personals = array();
				for($i = 0; $i< count($input['experience_info']); $i++)
				{
				$info=$input['experience_info'][$i];	
				$personal['company_name'] = $info['company_name']; 
				$personal['location'] = $info['location']; 
				$personal['job_position'] = $info['job_position']; 
				$personal['period_from'] = $info['period_from']; 
				$personal['period_to'] = $info['period_to'];
				$personals[] = $personal;
				}
		        
		       
				print_r($personals);

            // echo $user_id; exit;
        $result = array(
                'personal_details' => json_encode($personals)
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();

        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => json_encode($educations)
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}



	
	public function forgot_password($token,$input)
	{
		$username = $input['username'];
		// $record =  $this->get_role_and_userid($token);
		$record =  $this->get_role_and_username($username);
		if(empty($record)){
			$record =  $this->get_role_and_email($username);
		}
	    $records = array();
		if(!empty($record)){
			$role     = $record['role_id'];
			$user_id  = $record['user_id'];
		 	if($role == 3){
		 		
		 		$this->db->select('U.id as user_id,U.username,AD.fullname,U.unique_code,U.email,U.role_id');
		 		$this->db->from($this->user);
		 		$this->db->join($this->account_details, 'AD.user_id = U.id', 'left');
			 	$where = array('activated'=>1,'banned'=>0);
			 	$this->db->where($where);
			 	$this->db->where('(role_id = 3)');
		 	
		 		/*if (count($this->check_username_email($username)) >1) {
		 				$this->db->where('email', $username);
		 			}else{*/
		 		 		$this->db->where('username',$username);	
		 		 		$this->db->or_where('email',$username);	
		 			//}
			 		$records = $this->db->get('')->row_array();
			 		if(!empty($records)){
			 			$new_pass_key = md5(rand().microtime());
			 			$data = array(
							'id'		=> $records['user_id'],
							'username'		=> $records['username'],
							'email'			=> $records['email'],
							'new_password_key'	=> $new_pass_key,
							'new_password_requested'	=> date('Y-m-d h:i:s'),
						);
						$this->db->where('id', $records['user_id']);
						$result = $this->db->update($this->users, $data);
						$auth = modules::load('auth/auth/');
						
						$data['user_id'] =  $records['user_id'];
						$data['new_pass_key'] =  $new_pass_key;

						 $auth->_send_email('forgot_password', $data['email'], $data);

						 return $result;
			 		}
		 	}
		}
		return FALSE;
	}

		 public function check_username_email($username){

	 	if(!empty($username)){
	 		return $user_or_email = explode('@', $username);
	 	}else{
	 		return FALSE;
	 	}
	 }


	public function change_password($token,$input)
	{
	    $record =  $this->get_role_and_userid($token);
	    $password = !empty($input['current_password'])?$input['current_password']:'';
	    $new_password = $input['confirm_password'];
		$records = array();
		if(!empty($record)){
			$role     = $record['role_id'];
			$user_id  = $record['user_id'];

		 	if($role == 1 || $role == 4){
		 			if(empty($input['user_id'])){
		 				return FALSE;
		 			}
		 			$user_id  = $input['user_id'];
		 			$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
					$hashed_password = $hasher->HashPassword($new_password);
					$this->db->where('id', $user_id);
	 				return $this->db->update($this->users,array('password'=>$hashed_password));

		 	}elseif($role == 3){

		 		$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'),
			    $this->config->item('phpass_hash_portable', 'tank_auth'));
	 			
	 			$this->db->where('id', $user_id);
	 			$user_details = $this->db->get($this->users)->row_array();
	 			if(!empty($user_details['password'])){
			 		$check_password = $user_details['password'];	
	 			}else{
	 				$check_password  = '';
	 			}
	 			//if($hasher->CheckPassword($password, $check_password)){
	 				$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
					$hashed_password = $hasher->HashPassword($new_password);
					$this->db->where('id', $user_id);
	 				return $this->db->update($this->users,array('password'=>$hashed_password));
	 			//}

		 	}
		}
		return FALSE;	
	}


	public function departments($token)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
		 		$this->db->order_by('deptname', 'ASC');
		 		$this->db->select('deptid,deptname');
		 		$records = $this->db->get($this->departments)->result();
		 	}
		}
		return $records;
	}

	public function user_type($token)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
		 		$this->db->order_by('role','asc');
		 		$this->db->select('r_id,role');
		 		$records = $this->db->get('dgt_roles')->result();
		 	}
		}
		return $records;
	}
	public function leave_type($token)	
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
				$branches = $this->db->where('branch_status','0')->get('branches')->result();
				if(!empty($branches))
				{
					$return_data = array();
					$i = 0;
					foreach($branches as $brn)
					{
						$leaves = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',9)->get('common_leave_types')->row_array();
						$return_data[$i]['num_month']	= (isset($leaves['leave_day_month']))?$leaves['leave_day_month']:0;
						$return_data[$i]['num_days']	= (isset($leaves['leave_days']))?$leaves['leave_days']:0;
						$return_data[$i]['entity_id']	= $brn->branch_id;
						$return_data[$i]['entity_name']	= $brn->branch_name;
						$return_data[$i]['leave_type']	= 'Casual Leave';
						$return_data[$i]['leave_type_id']	= 9;
						$i++;
						
					}
					$extra_leave_types = $this->db->group_by('leave_type_id')->order_by('leave_id', 'ASC')->get_where('common_leave_types',array('leave_type_id!='=>47,'leave_type_id!='=>9,'leave_days >'=>0,'status'=>1))->result_array(); 
					if(!empty($extra_leave_types)){
						foreach($extra_leave_types as $extra_leaves){
							foreach($branches as $brn)
							{
								$leave_extra_banch_wise = $this->db->where('branch_id',$brn->branch_id)->where('leave_type_id',$extra_leaves['leave_type_id'])->where('leave_days >',0)->get('common_leave_types')->row();
								$return_data[$i]['num_days']	= (isset($leave_extra_banch_wise->leave_days))?$leave_extra_banch_wise->leave_days:0;
								$return_data[$i]['entity_id']	= $brn->branch_id;
								$return_data[$i]['entity_name']	= $brn->branch_name;
								$return_data[$i]['leave_type']	= $extra_leaves['leave_type'];
								$return_data[$i]['leave_type_id']	= $extra_leaves['leave_type_id'];
								$i++;
							}
						}
					}
					return $return_data;
				}
		 	}

		}
		return FALSE;		
	}

	public function leave_type_group($token)	
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
				
				$return_data = array();
				$i = 0;
				
				$extra_leave_types = $this->db->group_by('leave_type_id')->order_by('leave_id', 'ASC')->get_where('common_leave_types',array('status'=>1))->result_array(); 
				if(!empty($extra_leave_types)){
					foreach($extra_leave_types as $extra_leaves){
						$return_data[$i]['leave_type']	= $extra_leaves['leave_type'];
						$return_data[$i]['leave_type_id']	= $extra_leaves['leave_type_id'];
						$i++;
					}
				}
				return $return_data;
		 	}

		}
		return FALSE;		
	}

	public function check_leavesById($user_id)
 	{
 		return $this->db->get_where('dgt_user_leaves',array('user_id'=>$user_id,'status'=>1))->result_array();
 	}

 	public function check_leavesBycat($user_id,$cat_id)
 	{
 		return $this->db->get_where('dgt_user_leaves',array('user_id'=>$user_id,'leave_type'=>$cat_id,'status'=>1))->result_array();
 	}

	public function create_department($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){
				$where['deptname'] = $input['department'];

				$alreay = $this->check_input_exists($this->departments,$where);
				if($alreay==0){
					$this->db->insert($this->department, array('deptname'=>$input['department']));
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				} 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function leave_apply($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			$teamlead_details = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();

			if($role == 3){
				$where['leave_from'] = date('Y-m-d',strtotime($input['leave_from']));
				$where['leave_to'] = date('Y-m-d',strtotime($input['leave_to']));
				$where['leave_type'] = $input['leave_type'];
				$where['user_id'] = $user_id;
				$where['teamlead_id'] = $teamlead_details['teamlead_id'];
				$where['status'] = 0;

				$alreay = $this->check_input_exists($this->user_leave,$where);

				if($alreay==0){
					
	 	 		//$approvers[] = $this->input->post('teamlead_id');
	 	 		$approvers[] = $teamlead_details['teamlead_id'];
	 	 		$teamlead_id_details = $this->db->get_where('users',array('id'=>$teamlead_details['teamlead_id']))->row_array();
			  	$recipient[] = $teamlead_id_details['email'];
	 	 	
	  	// 	echo "<pre>"; print_r($approvers_id); 
	  	// 	echo "<pre>"; print_r($approvers); 
	  	// 	echo "<pre>"; print_r($recipient); 
			 // echo "<pre>"; print_r($_POST); exit;
	 	 	$approvers_array = $approvers;
	 	 

	 	 	$leave_approvers = $this->db->get('designation')->result_array();			
  			$det['user_id']       = $user_id;
			$det['leave_type']    = $this->input->post('leave_type'); 
			
			$get_customtype = $this->db->get_where('common_leave_types',array('leave_id'=>$this->input->post('leave_type')))->row_array();
			// $det['custom_leave']    = $get_customtype['custom_leave'];
			$det['teamlead_id']    = $approvers; 
 			$det['leave_from']    = date('Y-m-d',strtotime($this->input->post('leave_from')));
			$det['leave_to']      = date('Y-m-d',strtotime($this->input->post('leave_to')));
  			$qry                  =  "SELECT * FROM `dgt_user_leaves` WHERE user_id = ".$user_id."
									  and (leave_from >= '".$det['leave_from']."' or leave_to <= '".$det['leave_to']."')   and status = 0 "; 
 			$contdtn    		  = true;					  
 			$leave_list 		   = $this->db->query($qry)->result_array();
  			$d1 		 		   = strtotime($this->input->post('leave_from'));
 			$d2 		 		   = strtotime($this->input->post('leave_to'));
 			$array1     		   = array();
			for($i = $d1; $i <= $d2; $i += 86400 ){  $array1[] = $i; }  
  			if(!empty($leave_list)){ 
				foreach($leave_list as $key => $val)
				{ 
					$d11  = strtotime($val['leave_from']);
 			        $d22  = strtotime($val['leave_to']);
					for($i = $d11; $i <= $d22; $i += 86400 ){
						if(in_array($i,$array1)){
							$contdtn = false;	
							break;
						} 
					}  
					if(!$contdtn) { break; }
  				}
 			}  
 			if($contdtn){
				$det['leave_days']    = $this->input->post('leave_days');  
				$det['leave_day_type'] = $this->input->post('leave_day_type'); 
				$det['leave_reason']  = $this->input->post('leave_reason');
                 $det['teamlead_id']  = $teamlead_details['teamlead_id'];
				
				$this->db->insert('dgt_user_leaves',$det);   
				$leave_tbl_id  = $this->db->insert_id();
				// echo $this->db->last_query();
				// exit();

				$device_details = $this->db->query("SELECT * FROM `dgt_device_details` where user_id = ".$teamlead_details['teamlead_id']." ")->result_array();	

				if(!empty($device_details[0]['device_id'])){
					$subject         = " Employee Leave Request ";
					$login_user_name = user::displayName($user_id);

					$from_leave = date('d M Y',strtotime($det['leave_from']));
					$to_leave = date('d M Y',strtotime($det['leave_to']));
					$device_id = $device_details[0]['device_id'];
					$leave_day_str = $det['leave_days'].' days';
					if($det['leave_days'] < 1){
						$leave_day_str = 'Half day';
					}
					$req_message = array();
					$req_message['title']	=	$subject;
					$req_message['body']	=	'Hi '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' ) Reason: '.$det['leave_reason'];
					sendFCM($req_message,$device_id);
				
				}


				
				// echo count($approvers_array);
				// exit();
				if (count($approvers_array) > 0) {
					$i = 1;
                    foreach ($approvers_array as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'leave_id' => $leave_tbl_id,
                            'status' => 0,
                            'created_by'=>$user_id,
                            //'lt_incentive_plan' => ($this->input->post('long_term_incentive_plan')?1:0),

                            );//print_r($approvers_details);exit;

                        if($leave_approver[0]['default_leave_approval'] == "seq-approver"){
                        		if($i ==1){
	                        		$approvers_details['view_status'] = 1;
		                        } else{
		                        	$approvers_details['view_status'] = 0;
		                        }   
                        	}else{
                        		$approvers_details['view_status'] = 1;
                        	}
                       $this->db->insert('dgt_leave_approvers',$approvers_details);   
                       $login_user_name = user::displayName($user_id);  
                       if($leave_approver[0]['default_leave_approval'] == "seq-approver"){
                    		if($i ==1){   
		                        $args = array(
		                            'user' => $value,
		                            'module' => 'leaves',
		                            'module_field_id' => $leave_tbl_id,
		                            'activity' => 'Leave Requested by '.user::displayName($user_id),
		                            'icon' => 'fa-user',
		                            'value1' => $this->input->post('leave_reason', true),
		                        );
		                		App::Log($args);     
		                	} 
		                }else{
		                	  $args = array(
		                            'user' => $value,
		                            'module' => 'leaves',
		                            'module_field_id' => $leave_tbl_id,
		                            'activity' => 'Leave Requested by '.user::displayName($user_id),
		                            'icon' => 'fa-user',
		                            'value1' => $this->input->post('leave_reason', true),
		                        );
		                		App::Log($args);
		                }   
		                $i++;       

                    }
                     $subject = 'Leave Request';
                    $message = 'Leave approval Request';
                    foreach ($recipient as $key => $u) 
                    {
                        
                        $params['recipient'] = $u;
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    }

                    
                }


 				$leave_day_str = $det['leave_days'].' days';
				if($det['leave_days'] < 1){
				 	$leave_day_str = 'Half day';
 				}
				//This is admin alert Email   
				$base_url = base_url();
				
				$login_user_name = ucfirst(user::displayName($user_id)); 
				
				// $this->db->select('value');
				// $records = $this->db->get_where('dgt_config',array('config_key'=>'company_email'))->row_array();

				$log_detail = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
				// if($log_detail['teamlead_id'] != 0)
				// {
					$this->db->select('email');
					$send_mail = $this->db->get_where('dgt_users',array('id'=>$log_detail['teamlead_id']))->row_array();
					$send_mail = !empty($send_mail)?$send_mail:'';
				// }else{
				// 	$send_mail = '';
				// }
				// if($send_mail != '')
				// {
				// 	$recipient       = $send_mail['email'];
				// }
				// else{
				// 	$recipient       = array($records['value']);
				// }
					// <a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/4"> 
					// 							<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
					// 							</a>
					// 							<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/5"> 
					// 							<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
					// 							</a>  
				$from_leave = date('d M Y',strtotime($det['leave_from']));
				$to_leave = date('d M Y',strtotime($det['leave_to']));
				$lead_emails = $this->db->get('dgt_lead_reporter')->result_array(); 
				$emails = array(); 
				foreach($lead_emails as $lead_email){
					$emails[] = $lead_email['reporter_email'];
				}
				 
				$subject         = " Employee Leave Request ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' ) </p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/0/0"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>'; 			 
				$params      = array(
										'recipient' => $recipient,
										'subject'   => $subject,
										'message'   => $message
									);   
				$succ = Modules::run('fomailer/send_email',$params);


				$mgt_message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' )</p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												<a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/1"> 
												<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
												</a>
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/2"> 
												<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
												</a>  
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';

					$params1      = array(
										'recipient' => $emails,
										'subject'   => $subject,
										'message'   => $mgt_message
									);   
				// $succ = Modules::run('fomailer/send_email',$params1);




 			}
			else{
				// $this->session->set_flashdata('alert_message', 'error');
				return FALSE;
			}
					return 1;// Insert Successfully..
				}
				else{
					return 2;// Already exists
				} 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function check_input_exists($table,$where)
	{
		$this->db->where($where);
		return $this->db->count_all_results($table);
	}

	public function holidays($token,$input)
	{
		$hyear  = !empty($input['hyear'])?$input['hyear']:date('Y');
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){

		 		$this->db->select('id,title,description,holiday_date,holiday_national');
		 		$this->db->group_by('holiday_date');
		 		$this->db->order_by('holiday_date', 'ASC');
		 		$this->db->where('status',0);
		 		$this->db->where('branch_id!=',0);
		 		$this->db->like('holiday_date', $hyear, 'BOTH');
		 		$records = $this->db->get($this->holidays)->result();
		 	}
		}
		return $records;
	}

	public function remove_holiday($token,$input)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 4){
				$this->db->where('id',$input['id']);
				return $this->db->update($this->holidays,array('status'=>1));
		 	}
		}
		return $records;
	}

	public function leaves($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];
			$leave_type    = !empty($input['leave_type'])?$input['leave_type']:'';
			$leave_status    = ($input['leave_status'] != '')?$input['leave_status']:'';
			$employee_name    = ($input['employee_name'] != '')?$input['employee_name']:'';
			$leave_from    	  = ($input['leave_from'] != '')?$input['leave_from']:'';
			$leave_to    	  = ($input['leave_to'] != '')?$input['leave_to']:'';
			$entity    	  	= ($input['entity_id'] != '')?$input['entity_id']:'';
			$all_user_ids	=	array();
			if(!empty($entity)){
			
				$this->db->select('user_id');
				$this->db->from('dgt_account_details');
				$this->db->where(array('branch_id='=>$entity));
				$enitity_records = $this->db->get()->result_array();
				$all_user_ids = array_column($enitity_records, 'user_id');
			}
			if($type == 1){
				$this->db->select('UL.id');
			}
			else{
				$this->db->select('UL.id,UL.branch_id,UL.leave_from,UL.leave_to,UL.leave_type,UL.user_id,UL.teamlead_id,UL.status,UL.leave_days,UL.leave_reason,UL.reason,UL.leave_day_type,CL.leave_type as leave_type_name,AD.fullname');
			}
			$this->db->from($this->user_leaves);
			$this->db->join('common_leave_types CL', 'CL.leave_type_id = UL.leave_type', 'left');
			//if($type != 1){
				$this->db->join($this->account_details, 'AD.user_id = UL.user_id', 'left');
			//}
			if(!empty($all_user_ids)){
				$this->db->where_in('UL.user_id', $all_user_ids);
			}
			$this->db->where(array('UL.status !='=>'4'));
			if(!empty($leave_type)){
				$this->db->where('UL.leave_type',$leave_type);
			}
			if($leave_status != ''){
				$this->db->where('UL.status',$leave_status);
			}
			if(!empty($employee_name)){
				$this->db->like('AD.fullname',$employee_name);
			}
			if(!empty($leave_from)){
				$this->db->where('UL.leave_from>=',$leave_from);
			}
			if(!empty($leave_to)){
				$this->db->where('UL.leave_to<=',$leave_to);
			}
			$this->db->group_by('UL.id');
			if($role == 3){	
				$this->db->where('UL.user_id',$user_id);
			}
			$this->db->where("DATE_FORMAT(UL.leave_from,'%Y')",date('Y'));

				
				
			if($type == 1){
				$records = $this->db->get()->result();
				return count($records);exit;
			}elseif($type == 2){

				$this->db->order_by('UL.id', 'DESC');
				$limit = $input['limit'];
				$page  = !empty($input['page'])?$input['page']:1;
				$start = 0;
				if($page > 1){
					$page -=1;
					$start = ($page * $limit);
				} 

				$this->db->limit($limit,$start);
				//$records = $this->db->get($this->holidays)->result();
				$records = $this->db->get()->result();
			}
	
		}

		return $records;
	}


	public function team_leaves($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];
			$leave_type    = !empty($input['leave_type'])?$input['leave_type']:'';
			$leave_status    = ($input['leave_status'] != '')?$input['leave_status']:'';

		 		$this->db->select('UL.id,UL.branch_id,UL.leave_from,UL.leave_to,UL.leave_type,UL.user_id,UL.teamlead_id,UL.status,UL.leave_days,UL.leave_reason,UL.reason,UL.leave_day_type,lt.leave_type as leave_type_name,AD.fullname');
		 		$this->db->from($this->user_leaves);
		 		//$this->db->join('dgt_leave_approvers la', 'la.leave_id = UL.id', 'left');
		 		$this->db->join('dgt_common_leave_types lt', 'lt.leave_type_id = UL.leave_type', 'left');
		 		$this->db->join($this->account_details, 'AD.user_id = UL.user_id', 'left');		 		
		 		$this->db->join('dgt_users us', 'AD.user_id = us.id', 'left');		 		
		 		$this->db->where(array('UL.status !='=>'7'));
				$this->db->where('us.teamlead_id',$user_id);
		 		if(!empty($leave_type)){
		 			$this->db->where('UL.leave_type',$leave_type);
		 		}
		 		if($leave_status != ''){
		 			$this->db->where('UL.status',$leave_status);
		 		}
				
		 		$this->db->group_by('UL.id');
		 		if($role == 3){	
		 			//$this->db->where('la.approvers',$user_id);
		 		}
		 		$this->db->where("DATE_FORMAT(UL.leave_from,'%Y')",date('Y'));
		 			
		 			
		 		if($type == 1){
					$this->db->limit(1,0);
		 			//$records = $this->db->count_all_results($this->holidays);
					 $records = $this->db->count_all_results();
		 			
		 		}elseif($type == 2){

		 			$this->db->order_by('UL.id', 'DESC');
		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 

		 			$this->db->limit($limit,$start);
		 		//	$records = $this->db->get($this->holidays)->result();
					 $records = $this->db->get()->result();
					 

		 		}
	
		}
		return $records;
	}

	public function designations($token,$id)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
		 		
		 		$this->db->select('id,designation');
		 		$this->db->where('department_id', $id);
		 		$this->db->order_by('designation', 'ASC');
		 		$records = $this->db->get($this->designation)->result();
		 	}
		}
		return $records;
	}


	public function create_designation($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){
				$where['designation'] = $input['designation'];
				$where['department_id'] = $input['department_id'];
				$where['grade'] = $input['grade'];
				$alreay = $this->check_input_exists($this->designation_chk,$where);
				if($alreay==0){
					$this->db->insert($this->designation_chk,$where);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	
	public function create_holiday($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){

				$where['title'] = $input['title'];
				
				$where['holiday_date'] = date('Y-m-d',strtotime($input['holiday_date']));

				$alreay = $this->check_input_exists($this->holiday,$where);
				if($alreay==0){
					$where['description'] = $input['description'];
					$this->db->insert($this->holiday,$where);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function edit_holiday($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){

				$where['title'] = $input['title'];
				$where['holiday_date'] = date('Y-m-d',strtotime($input['holiday_date']));

				$alreay = $this->check_input_exists($this->holiday,$where);
				if($alreay==0){
					$where['description'] = $input['description'];
					$this->db->where('id',$input['id']);
					$this->db->update($this->holiday,$where);
					 
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}



	public function leave_cancel($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			if($role == 3){

				$where['id'] = $input['leave_id'];
				$this->db->where($where);
				return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	public function common_leave_cancel($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			if($role == 3){

				$where['id'] = $input['leave_id'];
				$this->db->where($where);
				return $this->db->update('dgt_compensatory_leave',array('status'=>$input['leave_status']));
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	// public function leave_approve_reject($token,$input)
	// {
	// 	$record =  $this->get_role_and_userid($token);
	// 	$records = array();
	// 	if(!empty($record['role_id'])){
	// 		$role    = $record['role_id'];
	// 		$user_id = $record['user_id'];

	// 		if($role == 1 || $role == 4 || $role==3){

	// 			$where['id'] = $input['leave_id'];
	// 			$this->db->where($where);
	// 			return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 
	// 		}else{
	// 			return FALSE;
	// 		}
	// 	}else{
	// 		return FALSE;
	// 	}
		
	// }
	public function leave_approve_reject($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$approver = $record['user_id'];
			$base_url = base_url();
			if($role == 1 || $role == 4 || $role==3){

				// $where['id'] = $input['leave_id'];
				// $this->db->where($where);
				// return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 				if($input['leave_status'] == 1){
 					$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$input['leave_id']." ")->result_array();
					$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
					$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();	
					
					$device_details = $this->db->query("SELECT * FROM `dgt_device_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();	
					$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();


					

					

					// if($this->input->post('approve') == 'teamlead')
					// {
					// 	$det['reason']      = $this->input->post('reason');  // Teamlead Approval
					// 	// $det['status']      = 4; 
					// 	$det['status']      = 1; 
					// }
					// if($this->input->post('approve') == 'management')
					// {
					// 	$det['reason']      = $this->input->post('reason'); 
					// 	$det['status']      = 1; 
					// }
					$det['reason']      = $input['reason']; 
					$det['status']      = 1; 
					$approvers_status['status'] = 1;
				

				     	$this->db->update('dgt_user_leaves',$det,array('id'=>$input['leave_id'])); 
			    
					// $this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
					

			         	$data = array(
								'module' => 'leaves',
								'module_field_id' => $input['leave_id'],
								'user' => $leave_det[0]['user_id'],
								'activity' => 'Leave Approved by '.$approver_det['fullname'],
								'icon' => 'fa-plus',
								'value1' => $cur.' '.$input['reason'],
								'value2' => $leave_det[0]['user_id']
								);
							App::Log($data);

		 			if(!empty($acc_det) && !empty($user_det)){
						$subject         = " Leave Request Approved ";
						if(!empty($device_details[0]['device_id'])){
							$device_id = $device_details[0]['device_id'];
							$req_message = array();
							$req_message['title']	=	$subject;
							$req_message['body']	=	'Hi '.$acc_det[0]['fullname'].'  Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by '.$approver_det['fullname'];
							sendFCM($req_message,$device_id);
						
						}
						$recipient       = array();
						if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
						
						$message         = '<div style="height: 7px; background-color: #535353;"></div>
												<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
													<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Approved</div>
													<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
														<p> Hi '.$acc_det[0]['fullname'].',</p>
														<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by '.$approver_det['fullname'].' </p> 
														<br>  
														<a style="text-decoration:none;" href="'.$base_url.'leaves"> 
															<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
														</a>
														<br>
														</big><br><br>Regards<br>The '.config_item('company_name').' Team
													</div>
											 </div>';  
						if(!empty($recipient) && count($recipient) > 0){		 
							$params      = array(
													'recipient' => $recipient,
													'subject'   => $subject,
													'message'   => $message
												);   
							$succ = Modules::run('fomailer/send_email',$params); 	
						}
					}  
					return TRUE;
 				}
 				if($input['leave_status'] == 2){
 						$det['reason']      = $input['reason']; 
						$det['status']      = 2; 
						$approver = $record['user_id'];
					// }
					$this->db->update('dgt_user_leaves',$det,array('id'=>$input['leave_id'])); 
					$approvers_status['status'] = 2;
					$this->db->update('dgt_leave_approvers',$approvers_status,array('leave_id'=>$input['leave_id'],'approvers'=>$approver)); 
		  			$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$input['leave_id']." ")->result_array();
					$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
					$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();						
					$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();

					$device_details = $this->db->query("SELECT * FROM `dgt_device_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();	

					$data = array(
								'module' => 'leaves',
								'module_field_id' => $input['leave_id'],
								'user' => $leave_det[0]['user_id'],
								'activity' => 'Leave Rejected by '.$approver_det['fullname'],
								'icon' => 'fa-plus',
								'value1' => $cur.' '.$input['reason'],
								'value2' => $leave_det[0]['user_id']
								);
							App::Log($data);
		 			if(!empty($acc_det) && !empty($user_det)){

						$subject         = " Leave Request Rejected ";
						if(!empty($device_details[0]['device_id'])){
							$device_id = $device_details[0]['device_id'];
							$req_message = array();
							$req_message['title']	=	$subject;
							$req_message['body']	=	'Hi '.$acc_det[0]['fullname'].'  Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been Rejected by '.$approver_det['fullname'];
							sendFCM($req_message,$device_id);
						
						}
						$recipient       = array();
						if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
						
						$message         = '<div style="height: 7px; background-color: #535353;"></div>
												<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
													<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Rejected</div>
													<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
														<p> Hi '.$acc_det[0]['fullname'].',</p>
														<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been Rejected by '.$approver_det['fullname'].' </p> 
														<br>  
														<a style="text-decoration:none;" href="'.$base_url.'leaves"> 
															<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
														</a>
														<br>
														</big><br><br>Regards<br>The '.config_item('company_name').' Team
													</div>
											 </div>';  
						if(!empty($recipient) && count($recipient) > 0){		 
							$params      = array(
													'recipient' => $recipient,
													'subject'   => $subject,
													'message'   => $message
												);   
							$succ = Modules::run('fomailer/send_email',$params); 	
						}
		 			}  
		 			return TRUE;  
			
 				}

			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function get_role_and_userid($token)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('unique_code'=>$token,'activated'=>1))->row_array();
	}

	public function get_role_and_username($username)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('username'=>$username,'activated'=>1))->row_array();
	}
	public function get_role_and_email($username){
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('email'=>$username,'activated'=>1))->row_array();
	}
	public function check_username($inputs)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('username'=>$inputs['username'],'activated'=>1))->row_array();
	}

	public function change_username($inputs)
	{
		
				
		$this->db->where('id',$inputs['user_id']);
		return $this->db->update($this->user, array('username'=>$inputs['username']));		 
		
	}

	public function users_list_payslip($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->join($this->designation,'D.id = U.designation_id');
		$this->db->where('U.id',$user_id);
		$this->db->where('U.role_id !=',1);
		return $this->db->get()->row_array();
	}

	public function get_salary($user_id)
	{
		
		return $this->db->get_where($this->salary,array('user_id'=>$user_id))->row_array();
	}

	public function get_payslips($user_id,$year,$month,$input,$type)
	{	
			   if(!empty($user_id))
				{
					$this->db->where('user_id',$user_id);
				}
				if(!empty($year))
				{
					$this->db->where('p_year',$year);
				}
				if(!empty($month))
				{
					$this->db->where('p_month',$month);
				}

				if($type == 1){

					$this->db->limit($limit,$start);
					 $this->db->from($this->payslips);
					 $this->db->join($this->user,'U.id = PS.user_id');
					 $this->db->join($this->account_details,'AD.user_id = U.id');
					 $this->db->join($this->designation,'D.id = U.designation_id');
		 			$records =  $this->db->get()->result_array();
		 			 
		 			$records = count($records);
		 			
		 		}elseif($type == 2){

		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 
					

		 			$this->db->limit($limit,$start);
					 $this->db->from($this->payslips);
					 $this->db->join($this->user,'U.id = PS.user_id');
					 $this->db->join($this->account_details,'AD.user_id = U.id');
					 $this->db->join($this->designation,'D.id = U.designation_id');
		 			$records =  $this->db->get()->result_array();
		 			
		 		}



		return $records;
	}

	public function check_payslip_exist($user_id,$year,$month)
	{
		return $this->db->get_where('dgt_payslip',array('user_id'=>$user_id,'p_year'=>$year,'p_month'=>$month))->num_rows();
	}

	public function check_net_exist($user_id,$amount)
	{
		return $this->db->get_where('dgt_salary',array('user_id'=>$user_id,'amount'=>$amount))->num_rows();
	}

	public function update_user_payslip($user_id,$year,$month,$result)
	{
		$where = array(
			'user_id' => $user_id,
			'p_year' =>$year,
			'p_month' => $month
		);
		$this->db->where($where);
		return $this->db->update('dgt_payslip',$result);
	}

	public function get_user_payslip($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->join($this->designation,'D.id = U.designation_id');
		$this->db->where('U.id',$user_id);
		$this->db->where('U.role_id !=',1);
		return $this->db->get()->row_array();
	}

	public function get_payslip_user($user_id,$year,$month)
	{
		return $this->db->get_where($this->payslips,array('user_id'=>$user_id,'p_year'=>$year,'p_month'=>$month))->row_array();
	}

	public function all_users($user_id,$page)
	{
		$this->db->select('U.id as user_id,AD.fullname');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->where('U.id !=',$user_id);
		if($page == 'salary')
		{
			$this->db->where('U.role_id !=',2);
		}
		$this->db->where('U.role_id !=',4);
		$this->db->where('U.role_id !=',1);
		$this->db->where('U.activated',1);
		return $this->db->get()->result_array();
	}

	public function over_all_projects()
	{
		return $this->db->get($this->projects)->result_array();
	}

	public function project_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('*');
		$this->db->from($this->projects);
		if(!empty($inputs['project_title'])){
			$this->db->like('project_title', $inputs['project_title'], 'BOTH');
		} 
		if(!empty($inputs['client'])){
			$this->db->where('client', $inputs['client']);
		}
		 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('project_id', 'desc');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result_array();
		 }
		  
		} 
	}

	public function project_listByUserId($token,$inputs,$type,$user_id)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('PJ.*');
		$this->db->from($this->projects);
		$this->db->join('dgt_assign_projects ap','ap.project_assigned=PJ.project_id','LEFT');
		$this->db->where(array('ap.assigned_user'=>$user_id));
		if(!empty($inputs['project_title'])){
			$this->db->like('PJ.project_title', $inputs['project_title'], 'BOTH');
		} 
		if(!empty($inputs['client'])){
			$this->db->where('PJ.client', $inputs['client']);
		}
		 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('PJ.project_id', 'ASC');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result_array();
		 }
		  
		} 
	}


	public function get_projectByUserId($user_id)
	{
		return $this->db->get_where($this->projects,array('client'=>$user_id))->result_array();
	}
	
	// public function task_by_project($project_id)
	// {
	// 	return $this->db->select('TK.t_id,TK.task_name,TK.estimated_hours,TK.description,TK.task_progress,TK.start_date,TK.due_date,IFNULL(AD.user_id, "0") AS user_id,IFNULL(AD.fullname, "0") AS fullname,IFNULL(AD.avatar, "0") AS avatar,IFNULL(TT.start_time, "0") AS start_time, IFNULL(TT.end_time, "0") AS end_time,IFNULL(TKF.file_id, "0") AS file_id,IFNULL(TKF.file_name, "0") AS file_name,IFNULL(TKF.path, "0") AS path,IFNULL(TKF.title, "0") AS file_title,IFNULL(TKF.size, "0") AS file_size,IFNULL(TKF.description, "0") AS file_description,IFNULL(TKF.uploaded_by, "0") AS file_uploaded_by,IFNULL(TKF.date_posted, "0") AS date_posted')
	// 			 ->from($this->tasks)
	// 			 ->join($this->account_details,'TK.added_by = AD.user_id','left')
	// 			 ->join($this->tasks_timer,'TT.task = TK.t_id','left')
	// 			 ->join($this->taskfiles,'TKF.task = TK.t_id','left')
	// 			 ->where('project',$project_id)
	// 			 ->get()->result_array();
	// 	// return $this->db->get_where($this->tasks,array('project'=>$project_id))->result_array();
	// }

	public function task_by_project($project_id)
	{
		$res = $this->db->select('TK.t_id,TK.assigned_to,TK.task_name,TK.estimated_hours,TK.description,TK.task_progress,TK.start_date,TK.due_date,IFNULL(AD.user_id, "0") AS user_id,IFNULL(AD.fullname, "0") AS fullname,IFNULL(AD.avatar, "0") AS avatar,IFNULL(TT.start_time, "0") AS start_time, IFNULL(TT.end_time, "0") AS end_time')
				 ->from($this->tasks)
				 ->join($this->account_details,'TK.added_by = AD.user_id','left')
				 ->join($this->tasks_timer,'TT.task = TK.t_id','left')
				 ->where('project',$project_id)
				 ->get()->result_array();
				 $nn = array();
		 foreach($res as $result){
		 	$task_comments['task_detail'] = $result;
		 	$task_comments['task_comment'] = $this->db->get_where($this->comments,array('project'=>$project_id,'task_id'=>$result['t_id']))->result_array();
		 	$task_files = $this->db->select('TKF.task as file_task_id,TKF.file_id as task_file_id,TKF.title as file_title,TKF.file_name,TKF.path as file_path,TKF.size as file_size,TKF.description as file_description,TKF.uploaded_by as upload_person_id,TKF.date_posted as upload_date,AD.fullname,AD.avatar')
		 			->from($this->taskfiles)
		 			->join($this->account_details,'AD.user_id = TKF.uploaded_by')
		 			->where('TKF.task',$result['t_id'])
		 			->get()->result_array();
		 	$task_comments['task_files'] = $task_files;
		 	$task_comments['assigned_to']= $this->get_user_detail('members',$result['assigned_to']);

		 	$nn[] = $task_comments;
		 }
		 return $nn;
	}

	public function get_project_files($project_id)
	{
		$this->db->select('FL.path AS project_file_path,FL.file_name AS project_file_name');
		return $this->db->get_where($this->files,array('project'=>$project_id))->result_array();
	}


	public function get_task_status($project_id,$status)
	{
		if($status == "open")
		{
			$result = $this->db->get_where($this->tasks,array('task_progress !='=>100,'project'=>$project_id))->result_array();
		}else{
			$result = $this->db->get_where($this->tasks,array('task_progress ='=>100,'project'=>$project_id))->result_array();
		}
		return $result;
	}

	public function get_task_files($tasks)
	{
		$file_count = array();
		foreach($tasks as $task)
		{
			$res = $this->db->get_where($this->taskfiles,array('task'=>$task['t_id']))->result_array();
			if(($res != 0) || ($res != ''))
			{
				$file_count[] = count($res);
			}
		}
		return array_sum($file_count);
	}

	public function get_comment_project($project_id)
	{
		return $this->db->get_where($this->comments,array('project'=>$project_id))->result_array();
	}

	public function get_user_detail($designation,$ids)
	{
		$all_users = array();
		if($designation == 'lead')
		{
			$all_users = $this->db->select('U.id as user_id,AD.fullname,AD.avatar')
			->from($this->user)
			->join($this->account_details,'U.id=AD.user_id')
			->where('U.id',$ids)
			->get()->row_array();
		}else{
			$ids = unserialize($ids); 
			foreach ($ids as $id) {
				$all_users[] = $this->db->select('U.id as user_id,AD.fullname,AD.avatar')
				->from($this->user)
				->join($this->account_details,'U.id=AD.user_id')
				->where('U.id',$id)
				->get()->row_array();
			}
		}
		return array_filter($all_users);;
	}

	public function get_all_clients()
	{
		return $this->db->select('*')
				->from($this->companies)
				->join($this->account_details,'AD.id = C.primary_contact','left')
				->where('C.is_lead','0')
				->get()
				->result_array();
	}


	public function client_list($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

		        $this->db->select('*,C.branch_id c_branch_id,C.city c_city,C.state c_state,C.country c_country,C.zip c_zip,C.skype c_skype');
				$this->db->from($this->companies);
				$this->db->join($this->account_details,'AD.id = C.primary_contact','left');
				$this->db->where('C.is_lead','0');
				if(!empty($inputs['email'])){
					$this->db->like('C.company_email', $inputs['email'], 'BOTH');
				} 
				if(!empty($inputs['client'])){
					$this->db->like('C.company_name
						', $inputs['client'], 'BOTH');
				} 
				if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->order_by('C.co_id', 'desc');
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}


	public function get_clientById($co_id)
	{
		return $this->db->select('*,C.branch_id c_branch_id,C.city c_city,C.state c_state,C.country c_country,C.zip c_zip,C.skype c_skype')
				->from($this->companies)
				->join($this->account_details,'AD.id = C.primary_contact','left')
				->where('C.is_lead','0')
				->where('C.co_id',$co_id)
				->get()
				->row_array();
	}

	// public function get_all_estimate()
	// {
	// 	return $this->db->select('*') 
	// 				->from($this->estimate)
	// 				->join($this->user,'ES.client = U.id')
	// 				->join($this->account_details,'AD.user_id = U.id')
	// 				->get()->result_array();
	// }


	public function get_all_estimate($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	                $this->db->select('ES.status as est_status, ES.*,U.*,AD.*'); 
					$this->db->from($this->estimate);
					$this->db->join($this->user,'ES.client = U.id',LEFT);
					$this->db->join($this->account_details,'AD.user_id = U.id',LEFT);
					if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }
				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$this->db->order_by('ES.est_id','DESC');
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}

	public function get_estimateByClient($token,$inputs,$type=1,$co_id)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	                $this->db->select('*'); 
					$this->db->from($this->estimate);
					$this->db->join($this->user,'ES.client = U.id');
					$this->db->join($this->account_details,'AD.user_id = U.id');
					$this->db->where('ES.client',$co_id);
					if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }
				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}


	public function get_estimateByClients($co_id)
	{
		return $this->db->select('*') 
					->from($this->estimate)
					->join($this->user,'ES.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->where('ES.client',$co_id)
					->get()->result_array();
	}

	public function get_estimate_cost($estimate_id)
	{
		return $this->db->get_where($this->estimate_items,array('estimate_id'=>$estimate_id))->result_array();
	}

	public function get_company_details($company_id)
	{
		return $this->db->get_where($this->companies,array('co_id'=>$company_id))->row_array();
	}

	// public function get_all_invoices()
	// {
	// 	return $this->db->select('*') 
	// 				->from($this->invoice)
	// 				->join($this->user,'IN.client = U.id')
	// 				->join($this->account_details,'AD.user_id = U.id')
	// 				->get()->result_array();
	// }


	public function get_all_invoices($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

		            $this->db->select('IN.status as inv_status, IN.*, U.*, AD.* '); 
					$this->db->from($this->invoice);
					$this->db->join($this->user,'IN.client = U.id',LEFT);
					$this->db->join($this->account_details,'AD.user_id = U.id',LEFT);

					 if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }

				 if($type == 1){
			   return $this->db->count_all_results();
			   // echo $this->db->last_query(); exit;	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
					$this->db->order_by('IN.inv_id','DESC');
				 	$this->db->limit($limit,$page);
					 return $this->db->get()->result_array();
			   // echo $this->db->last_query(); exit;	
				 }
	}

	

	public function get_invoicesbyClients($client_id)
	{
		return $this->db->select('*') 
					->from($this->invoice)
					->join($this->user,'IN.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->where('IN.client',$client_id)
					->get()->result_array();
	}


	public function get_invoicesbyClient($token,$inputs,$type=1,$client_id)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

		            $this->db->select('*'); 
					$this->db->from($this->invoice);
					$this->db->join($this->user,'IN.client = U.id');
					$this->db->join($this->account_details,'AD.user_id = U.id');
					$this->db->where('IN.client',$client_id);
					 if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }

				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}

	// public function get_all_expenses()
	// {
	// 	return $this->db->select('EX.*,PJ.project_title,CA.cat_name') 
	// 				->from($this->expenses)
	// 				->join($this->user,'EX.client = U.id','LEFT')
	// 				->join($this->account_details,'AD.user_id = U.id','LEFT')
	// 				->join($this->projects,'PJ.project_id = EX.project','LEFT')
	// 				->join($this->categories,'CA.id = EX.category','LEFT')
	// 				->get()->result_array();
	// }


	public function get_all_expenses($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	            $this->db->select('EX.*,PJ.project_title,CA.cat_name'); 
				$this->db->from($this->expenses);
				$this->db->join($this->user,'EX.client = U.id',LEFT);
				$this->db->join($this->account_details,'AD.user_id = U.id',LEFT);
				$this->db->join($this->projects,'PJ.project_id = EX.project',LEFT);
				$this->db->join($this->categories,'CA.id = EX.category',LEFT);
				 if($type == 1){
			  		return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$this->db->order_by('EX.id','DESC');
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}


	public function get_expensesbyClient($token,$inputs,$type=1,$client_id)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	            $this->db->select('EX.*,PJ.project_title,CA.cat_name'); 
				$this->db->from($this->expenses);
				$this->db->join($this->user,'EX.client = U.id','LEFT');
				$this->db->join($this->account_details,'AD.user_id = U.id','LEFT');
				$this->db->join($this->projects,'PJ.project_id = EX.project','LEFT');
				$this->db->join($this->categories,'CA.id = EX.category','LEFT');
				$this->db->where('EX.client',$client_id);
				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}



	// public function get_expensesbyClient($client_id)
	// {
	// 	return $this->db->select('EX.*,PJ.project_title,CA.cat_name') 
	// 				->from($this->expenses)
	// 				->join($this->user,'EX.client = U.id','LEFT')
	// 				->join($this->account_details,'AD.user_id = U.id','LEFT')
	// 				->join($this->projects,'PJ.project_id = EX.project','LEFT')
	// 				->join($this->categories,'CA.id = EX.category','LEFT')
	// 				->where('EX.client',$client_id)
	// 				->get()->result_array();
	// }

	public function get_invoice_items($invoice_id)
	{
		return $this->db->get_where($this->items,array('invoice_id'=>$invoice_id))->result_array();
	}

	public function get_invoice_payment($invoice_id)
	{
		return $this->db->get_where($this->payments,array('invoice'=>$invoice_id))->result_array();
	}

	public function get_userById($user_id)
	{
		return $this->db->get_where($this->account_detail,array('user_id'=>$user_id))->row_array();
	}

	public function get_invoiceByClientId($client_id)
	{
		// echo $client_id; exit;
		return $this->db->get_where('dgt_invoices',array('client'=>$client_id))->result_array();
	}

	public function get_allProjectsByClient($client_id)
	{
		return $this->db->get_where('dgt_projects',array('client'=>$client_id))->result_array();
	}

	public function get_allEstimateByClient($client_id)
	{
		return $this->db->select('*')
		->from('dgt_estimates E')
		->join('dgt_estimate_items EI','E.est_id = EI.estimate_id','left')
		->where('E.client',$client_id)
		->get()->result_array();
	}

	public function get_deviceIdByUser($user_id)
	{
		return $this->db->get_where('dgt_device_details',array('user_id'=>$user_id))->row_array();
	}

	public function get_taskDetails($task_id)
	{
		// return $this->db->select('*')
		// 		->from($this->tasks)
		// 		->join($this->projects,'PJ.project_id = TK.project')
		// 		->where('TK.t_id',$task_id)
		// 		->get()->row_array();
		return $this->db->get_where('dgt_tasks',array('t_id'=>$task_id))->result_array();
	}

	public function get_taskfileById($task_id)
	{
		return $this->db->get_where('dgt_task_files',array('task'=>$task_id))->result_array();
	}

	public function get_common_session()
	{
		return $this->db->get('dgt_chat_common_session',array('com_sess_id'=>1))->row_array();
	}

	public function check_connectionidByUser($user_id)
	{
		return $this->db->get_where('dgt_chat_connectionids',array('user_id'=>$user_id))->row_array();
	}

	public function connectionid_status($user_id,$connection_id,$status)
	{
		if($status == 'update'){
			$res = array('connection_id' => $connection_id);
			$this->db->where('user_id',$user_id);
			$result = $this->db->update('dgt_chat_connectionids',$res);
		}elseif($status == 'insert'){
			$res = array('user_id' => $user_id,'connection_id' => $connection_id);
			$result = $this->db->insert('dgt_chat_connectionids',$res);
		}
		return $result;
	}

	public function get_all_chat_messagesByUserId($from_id,$to_id)
	{
		$sql= "SELECT * FROM `dgt_chat_conversations` WHERE (`from_id` = '$from_id' AND `to_id` = '$to_id' OR `from_id` = '$to_id' AND `to_id` = '$from_id') AND `msg_type` = 'one'";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_group_members($group_id)
	{
		return $this->db->get_where('dgt_chat_group_members',array('group_id'=>$group_id))->result_array();
	}

	public function get_group_message($group_id)
	{
		$this->db->group_by('message');
		return $this->db->get_where('dgt_chat_conversations',array('msg_type'=>'group','group_id'=>$group_id))->result_array();
	}

	public function get_all_chat_detailsByUserId($user_id)
	{
		$sql= "SELECT * FROM `dgt_chat_conversations` WHERE (`from_id` = '$user_id' OR `to_id` = '$user_id') AND `msg_type` = 'one' ORDER BY `msg_id`  DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_all_groupmembers($user_id)
	{
		return $this->db->get_where('dgt_chat_group_members',array('login_id'=>$user_id))->result_array();
	}

	public function get_groupname($group_id)
	{
		return $this->db->get_where('dgt_chat_group_details',array('group_id'=>$group_id))->row_array();
	}

	public function get_last_msg($group_id)
	{
		$this->db->order_by('msg_id','DESC');
		$this->db->limit(1);
		return $this->db->get_where('dgt_chat_conversations',array('group_id' => $group_id,'msg_type' =>'group'))->row_array();

	}

	public function get_all_members($group_id)
	{
		$this->db->select('*');
		$this->db->group_by('login_id');
		$this->db->where('group_id',$group_id);
		return $this->db->get('dgt_chat_group_members')->result_array();
		// return $this->db->get_where('dgt_chat_group_members',array('group_id'=>$group_id))->result_array();
	}

	// public function get_all_timesheetById($user_id)
	// {
	// return $this->db->select('TS.time_id,TS.user_id,TS.project_id,TS.hours,TS.timeline_date,TS.timeline_desc,PS.project_title as project_name,ADS.fullname')
	// 				->from('dgt_timesheet TS')
	// 				->join('dgt_projects PS','TS.project_id = PS.project_id')
	// 				->join('dgt_account_details ADS','ADS.user_id = TS.user_id')
	// 				->where('TS.user_id',$user_id)
	// 				->order_by('TS.time_id','DESC')
	// 				->get()->result_array();
	// }

	public function get_all_timesheetById($token,$inputs,$type)
	{
		if($token == 0)
		{
			$user_id = $inputs['user_id'];
		}else{
			$re =  $this->get_role_and_userid($token);
			$user_id = $re['user_id'];
		}

		$this->db->select('TS.time_id,TS.user_id,TS.project_id,TS.hours,TS.timeline_date,TS.timeline_desc,PS.project_title as project_name,ADS.fullname');
		$this->db->from('dgt_timesheet TS');
		$this->db->join('dgt_projects PS','TS.project_id = PS.project_id');
		$this->db->join('dgt_account_details ADS','ADS.user_id = TS.user_id');
		$this->db->where('TS.user_id',$user_id);
		if((!empty($inputs['from_date'])) && (!empty($inputs['to_date']))){
			$this->db->where('TS.timeline_date >=', $inputs['from_date']);
			$this->db->where('TS.timeline_date <=', $inputs['to_date']);
		}else if((!empty($inputs['from_date'])) || (!empty($inputs['to_date']))){
			if($inputs['from_date'] !='')
				$this->db->where('TS.timeline_date >=', $inputs['from_date']);
			if($inputs['to_date'] !='')
				$this->db->where('TS.timeline_date <=', $inputs['to_date']);
		} 
		if($type == 1)
		{
			$rr = $this->db->get()->num_rows();
		}else{
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
			$this->db->order_by('TS.time_id','DESC');
		 	$this->db->limit($limit,$page);
			$rr = $this->db->get()->result_array();
		}
		return $rr; exit;
	}

	public function get_timesheet($timesheet_id)
	{
		$this->db->select('TS.time_id,TS.user_id,TS.project_id,TS.hours,TS.timeline_date,TS.timeline_desc,PS.project_title as project_name,ADS.fullname');
		$this->db->from('dgt_timesheet TS');
		$this->db->join('dgt_projects PS','TS.project_id = PS.project_id');
		$this->db->join('dgt_account_details ADS','ADS.user_id = TS.user_id');
		$this->db->where('TS.time_id',$timesheet_id);
		return $this->db->get()->row_array();
	}

	public function getAllPayments()
	{
		return $this->db->select('*,IN.reference_no,IN.date_saved,IN.inv_id')
				 ->from($this->payments)
				 ->join($this->companies,'PY.paid_by = C.co_id')
				 ->join($this->payment_methods,'PY.payment_method = PYM.method_id')
				 ->join($this->invoice, 'PY.invoice = IN.inv_id')
				 ->where('PY.inv_deleted','No')
				 ->get()->result_array();
		// return $this->db->get_where($this->payments,array('PY.inv_deleted'=>'No'))->result_array();
	}


	public function get_project_counts($role,$id)
	{
		if($role == 'admin')
		{
			$res = $this->db->get_where('dgt_projects',array('status'=>'Active'))->result_array();
		}else if($role == 'staff'){
			$res = $this->db->get_where('dgt_assign_projects',array('assigned_user'=>$id))->result_array();
		}else if($role == 'client'){
			$res = $this->db->get_where('dgt_projects',array('status'=>'Active','client'=>$id))->result_array();
		}
		return $res;
	}

	public function get_clients_counts($role_id)
	{
		return $this->db->get_where('dgt_users',array('role_id'=>$role_id,'activated'=>1,'banned'=>0))->result_array();
	}

	public function get_tasks_counts($role_id)
	{
		return $this->db->get_where('dgt_assign_tasks',array('assigned_user'=>$role_id))->result_array();
	}

	public function get_estimate_counts($id)
	{
		return $this->db->get_where('dgt_estimates',array('client'=>$id))->result_array();
	}

	public function get_invoice_counts($role,$id)
	{
		if($role == 'admin')
		{
			return $this->db->get('dgt_invoices')->result_array();
		}else if($role == 'client'){
			return $this->db->get_where('dgt_invoices',array('client'=>$id))->result_array();
		}
	}


	public function attendance_list($inputs,$type=1){

        $page = $inputs['page'];
        $employee_name = $inputs['employee_name'];
        $employee_id = $inputs['employee_id'];
       
       
        $query_string = "SELECT count(U.id) as total_records  FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and AD.user_id!='' ";
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		if($inputs['role_id']!=1){
			$query_string .= " AND U.teamlead_id =  '".$inputs['user_id1']."' ";    
		}
        
        $total_pages  = $this->db->query($query_string)->row_array();
        
       if(!empty($total_pages)){
            $total_pages  = $total_pages['total_records'];
            if($total_pages > 0){
                $total_pages = ceil($total_pages/10);
            }    
        }else{
             $total_pages = 0 ;
        }
         
        $query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 and AD.user_id!=''";
        
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
		if($inputs['role_id']!=1){
			$query_string .= " AND U.teamlead_id =  '".$inputs['user_id1']."' ";    
		}
        $query_string .= " ORDER BY AD.fullname ASC ";
		if($type == 2){
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
			$query_string .= "  limit ".$page.",".$limit." ";
		}
		else{
			return $total_pages;exit;
		}
        $results =  $this->db->query($query_string)->result();
        $records = array();
        if(!empty($results)){
            foreach ($results as $result) {
                $user_id   = $result->user_id;
                $attendance  = $this->attendance($user_id,$inputs);
                $result->attendance = unserialize($attendance['month_days']);
                $records[] = $result;
            }
        }
        return array($total_pages,$records);
        
    }

    public function attendance($user_id,$inputs)
    {   
        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            $insert = array(
                'user_id'=>$user_id,
                'month_days'=>serialize($days),
                'month_days_in_out'=>serialize($days_in_out),
                'a_month'=>$a_month,
                'a_year'=>$a_year
                );
            $this->db->insert("dgt_attendance_details",$insert);

        return  $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
       
    }
    Public function get_languages()
	{
	   
	   return $this->db->select('*')
	            ->from('dgt_languages')
	            ->where('active','1')
	            ->get()->result();
	}
	
	 Public function get_lang($language)
	{
	   
	   return $this->db->select('name')
	            ->from('dgt_languages')
	            ->where('code',$language)
	            ->get()->result();
	}

	public function compensatory_request_list($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($type == 1){
				$this->db->select('count(*) all_count');
			}
			else{
				$this->db->select('cl.*,AD.fullname');
			}
			$this->db->from('compensatory_leave cl');
			$this->db->join('users U','U.id = cl.user_id', 'left');
			if($type != 1){
				$this->db->join($this->account_details, 'AD.user_id = cl.user_id', 'left');
			}

			
			$this->db->where("DATE_FORMAT(cl.leave_from,'%Y')",date('Y'));
			if($role !=1){
				$this->db->where("cl.user_id",$user_id);
			}
				
				
			if($type == 1){
				$this->db->limit(1,0);
				$records = $this->db->get()->result();

				if(!empty($records[0]->all_count)){
					return $records[0]->all_count;
					exit;
				}
				else{
					return 0;exit;
				}
				
			}elseif($type == 2){

				$this->db->order_by('cl.id', 'DESC');
				$limit = $input['limit'];
				$page  = !empty($input['page'])?$input['page']:1;
				$start = 0;
				if($page > 1){
					$page -=1;
					$start = ($page * $limit);
				} 

				$this->db->limit($limit,$start);
				$records = $this->db->get()->result();
			}
	
		}

		return $records;
	}

	public function compensatory_team_request_list($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			$all_user = $this->db->select('*')->from('dgt_users')->where('teamlead_id',$user_id)->get()->result_array();
			$user_ids = array_column($all_user,'id');

			if($type == 1){
				$this->db->select('count(*) all_count');
			}
			else{
				$this->db->select('cl.*,AD.fullname');
			}
			$this->db->from('compensatory_leave cl');
			$this->db->join('users U','U.id = cl.user_id', 'left');
			if($type != 1){
				$this->db->join($this->account_details, 'AD.user_id = cl.user_id', 'left');
			}

			
			$this->db->where("DATE_FORMAT(cl.leave_from,'%Y')",date('Y'));
			if($role !=1){
				if(!empty($user_ids)){
					$this->db->where_in("cl.user_id",$user_ids);
				}else{
					$this->db->where("cl.user_id",0);
				}
				
			}
				
				
			if($type == 1){
				$this->db->limit(1,0);
				$records = $this->db->get()->result();

				if(!empty($records[0]->all_count)){
					return $records[0]->all_count;
					exit;
				}
				else{
					return 0;exit;
				}
				
			}elseif($type == 2){

				$this->db->order_by('cl.id', 'DESC');
				$limit = $input['limit'];
				$page  = !empty($input['page'])?$input['page']:1;
				$start = 0;
				if($page > 1){
					$page -=1;
					$start = ($page * $limit);
				} 

				$this->db->limit($limit,$start);
				$records = $this->db->get()->result();
			}
	
		}

		return $records;
	}

	
	public function compensatory_request($token,$inputs)
	{
		$results = array();
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$user_id = $record['user_id'];
			$pers_check = $this->db->get_where('users',array('id'=>$user_id))->result_array();
			$branch_id = $teamlead_id = '';
			if(!empty($pers_check[0]['teamlead_id'])){
				$teamlead_id = $pers_check[0]['teamlead_id'];

			}
			$pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->result_array();
			$branch_id = '';
			if(!empty($pers_check[0]['branch_id'])){
				$branch_id = $pers_check[0]['branch_id'];

			}
			$det = array();
			
			$det['leave_days']    = $inputs['leave_days'];  
			$det['leave_reason']  = $inputs['leave_reason'];
			$det['leave_from']  = $inputs['leave_date'];
			
			$det['leave_to']  = $inputs['leave_date'];
			$det['branch_id']  = $branch_id;
			$det['user_id']  = $user_id;
			$det['teamlead_id']  = $teamlead_id;
			$this->db->insert('compensatory_leave',$det); 
			return  $this->db->insert_id();
		} 
		return false;
	}


	public function compensatory_approve_request($token,$inputs)
	{
		$results = array();
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$user_id = $record['user_id'];
			$pers_check = $this->db->get_where('users',array('id'=>$user_id))->result_array();
			$branch_id = $teamlead_id = '';
			if(!empty($pers_check[0]['teamlead_id'])){
				$teamlead_id = $pers_check[0]['teamlead_id'];

			}
			$pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->result_array();
			$branch_id = '';
			if(!empty($pers_check[0]['branch_id'])){
				$branch_id = $pers_check[0]['branch_id'];

			}
			$det = array();
			
			$det['reason']  = $inputs['leave_reason'];
			$det['status']  = 1; 
			$this->db->update('compensatory_leave',$det,array('id'=>$inputs['leave_id'])); 
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$user_id." ")->result_array();
			 $check_leave = $this->db->select('*')->from('common_leave_types')->where("leave_type_id",47)->where("branch_id",$acc_det[0]['branch_id'])->get()->result_array(); 
			 if(empty($check_leave) ){
				
				$det = array();
				$det['leave_type_id']	= 47;
				$det['branch_id']		= $acc_det[0]['branch_id'];
				$det['leave_days']		= 0;
				$det['leave_type']		= 'Compensatory Off';
				$this->db->insert('common_leave_types',$det);  
			 }
			return  true;
		} 
		return false;
	}
	public function compensatory_reject_request($token,$inputs){
		$results = array();
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$user_id = $record['user_id'];
			$pers_check = $this->db->get_where('users',array('id'=>$user_id))->result_array();
			$branch_id = $teamlead_id = '';
			if(!empty($pers_check[0]['teamlead_id'])){
				$teamlead_id = $pers_check[0]['teamlead_id'];

			}
			$pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->result_array();
			$branch_id = '';
			if(!empty($pers_check[0]['branch_id'])){
				$branch_id = $pers_check[0]['branch_id'];

			}
			$det = array();
			
			$det['reason']  = $inputs['leave_reason'];
			$role    = $record['role_id'];
			 if($role != 1){	
				$det['status']      = 5; 
			}
			else
			{
				$det['status']      = 2; 
			}
			$this->db->update('compensatory_leave',$det,array('id'=>$inputs['leave_id'])); 
			
			return  true;
		} 
		return false;

	}
	public function get_entity_id($user_id){
		$pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->result_array();
		$branch_id = 0;
		if(!empty($pers_check[0]['branch_id'])){
			$branch_id = $pers_check[0]['branch_id'];
		}
		return $branch_id;
	}
	public function entity_id($token,$inputs)
	{
		$results = array();
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$user_id = $record['user_id'];
			$branch_id = $this->get_entity_id($user_id);
			$results = $this->get_cmp_offs_days($branch_id,$user_id);
		} 
		return $results;
	}

	function get_cmp_offs_days($branch_id,$user_id){

		$branches = $this->db->get_where('dgt_branches',array('branch_id'=>$branch_id))->result_array();
		if(!empty($branches[0]['weekend_workdays'])){
			$work_weekend = json_decode($branches[0]['weekend_workdays']);
		}
		$req_comp_off = [];
		$num_month = 5;
		$current_date = date('Y-m-d');
		$newDate = date('Y-m-d', strtotime($date. ' - '.$num_month.' months'));
		$req_dates = $leave_dates = $cmp_offs = array();
		for($i=0;$i<=$num_month;$i++){
			$req_dates[$i] = date('Y-m-d', strtotime($newDate. ' + '.$i.' months'));
		}
		$holidays = $this->db->get_where('dgt_holidays',array('holiday_national'=>1))->result_array();
		
		
		if(!empty($holidays)){
			foreach($holidays as $holiday1){
				$leave_dates[] = $holiday1['holiday_date'];
			}
		}
		if(!empty($req_dates)){
			foreach($req_dates as $date1){
				$colMonth = date('M',strtotime($date1));
				$a_year = date('Y',strtotime($date1));
				if (empty($work_weekend) || !in_array(1, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('first saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(2, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('second saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(3, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('third saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(4, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fourth saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(5, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fifth saturday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}

				if (empty($work_weekend) || !in_array(6, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('first Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(7, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('second Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(8, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('third Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(9, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fourth Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}
				if (empty($work_weekend) || !in_array(10, $work_weekend)){
					$req_date = date('Y-m-d', strtotime('fifth Sunday of '.$colMonth.' '.$a_year.''));
					if (!in_array($req_date, $leave_dates)){
						$leave_dates[] = $req_date;
					}
				}

			}
		}
		if(!empty($leave_dates)){
			$k = 0;
			
			foreach($leave_dates as $leave_date1){
				$a_month = (int) date('m',strtotime($leave_date1));
				$a_year = (int) date('Y',strtotime($leave_date1));
				$a_day =	(int)date('d', strtotime($leave_date1));
				$where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
				$this->db->select('month_days,month_days_in_out');
				$this->db->from('attendance_details');
				$this->db->where($where);
				$results  = $this->db->get()->result_array();

				if(!empty($results[0])){
					$month_days			 =  unserialize($results[0]['month_days']);
					$month_days_in_out	 =  unserialize($results[0]['month_days_in_out']);
					$day 				 =  $month_days[$a_day-1];
					$day_in_out 		 =	$month_days_in_out[$a_day-1];
					$latest_inout 		 =	end($day_in_out);
					
					foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
					{
						if((!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']) )||( !empty($month_days_in_out[1]['punch_in']) && !empty($month_days_in_out[1]['punch_out'])) )
						{	
							$production_hour = 0;
							foreach ($month_days_in_out[$a_day-1] as $punch_detail1) {
								$production_hour = $production_hour +time_difference(date('H:i',strtotime($punch_detail1['punch_in'])),date('H:i',strtotime($punch_detail1['punch_out'])));;
							}
							
							$hours_val = sprintf("%02d", intdiv($production_hour, 60));
							$mints_val = sprintf("%02d", ($production_hour % 60));
							$prod_hour1 += $hours_val.'h '. $mints_val.'m';
							$prod_hour = $hours_val.'h '. $mints_val.'m';
							$seconds = $hours_val * 3600 + $mints_val * 60 ;
							$total_seconds += $seconds;
							$total_production_hour += $production_hour;
							$total_secs = 8*3600;
							$progress_bar = ($seconds/$total_secs)*100;
							$actProductionHour =  intdiv($production_hour, 60);

							$check_leaves = $this->db->get_where('dgt_compensatory_leave',array('user_id'=>$user_id,'leave_from'=>$leave_date1))->result_array();

							if(empty($check_leaves[0])){
								if (!in_array($leave_date1, $req_comp_off)){
									$req_comp_off[$k] =  $leave_date1;
									if($actProductionHour>=8){
										$cmp_offs[$k]['dates'] = $leave_date1;
										$cmp_offs[$k]['worked'] = 1;
										$k++;
									}
									else if($actProductionHour>=4){
										$cmp_offs[$k]['dates'] = $leave_date1;
										$cmp_offs[$k]['worked'] = 0.5;
									}
								}
							}
							
						}
						
					}
					
				}
				
			}
		}
		return $cmp_offs;


	 }
	
	
	public function entity_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			$this->db->select('*');
			$this->db->from('branches');
			if($type == 1){
				return $this->db->count_all_results();	
			}else{
				$page = !empty($inputs['page'])?$inputs['page']:'';
				$limit = $inputs['limit'];
				if($page>=1){
					$page = $page - 1 ;
				}
				$page =  ($page * $limit);	
				$this->db->order_by('branch_id', 'ASC');
				$this->db->limit($limit,$page);
				$results = $this->db->get()->result_array();
				return $results;
			}
		  
		} 
	}
	
	 public function create_entity($token,$datas,$status)
	{
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$user_id = $record['user_id'];
			$role    = $record['role_id'];
			if($role != 1 ){
				return false;exit;
			}

			$req_work_day = array();
			if(!empty($datas['entity_weekend_work']) && $datas['weekend_workdays']!='null'){
				$weekends = explode(',',$datas['entity_weekend_work']);
				$j = 0;
				foreach($weekends as $work_day){
					$cur_work_day = '';
					switch(strtolower($work_day)){
						case '1st saturday':
							$cur_work_day = '1';
							break;
						case '2nd saturday':
							$cur_work_day = '2';
							break;
						case '3rd saturday':
							$cur_work_day = '3';
							break;
						case '4th saturday':
							$cur_work_day = '4';
							break;
						case '5th saturday':
							$cur_work_day = '5';
							break;
						case '1st sunday':
							$cur_work_day = '6';
							break;
						case '2nd sunday':
							$cur_work_day = '7';
							break;
						case '3rd sunday':
							$cur_work_day = '8';
							break;
						case '4th sunday':
							$cur_work_day = '9';
							break;
						case '5th sunday':
							$cur_work_day = '10';
							break;
						default:
							break;
					}
					$req_work_day[] = $cur_work_day;
					
				}
			}
			$ins_work_day= '';
			if(!empty($req_work_day)){
				$ins_work_day = json_encode($req_work_day);
			}
			
			$work_day = json_encode($work_day);
			$res = array('branch_name' => $datas['entity_name'],'branch_prefix' => $datas['prefix'],'weekend_workdays' => $ins_work_day,'branch_status'=>$datas['entity_status']);
			if($status == 'update'){
				$this->db->where('branch_id',$datas['entity_id']);
				$result = $this->db->update('branches',$res);
				$this->db->where('branch_id', $datas['entity_id']);
				$this->db->delete('assigned_entities');
				if(!empty($datas['entity_admin'])){
					$entity_admins = explode(',',$datas['entity_admin']);
					foreach($entity_admins as $user_data){
						$res = array();
						$res = array('user_id'=>$user_data,'branch_id'=>$datas['entity_id']);
						$result = $this->db->insert('assigned_entities',$res);
					}
				}
			}elseif($status == 'insert'){
				
				$result = $this->db->insert('branches',$res);
				$entity = $this->db->insert_id();
				if(!empty($datas['entity_admin'])){
					$entity_admins = explode(',',$datas['entity_admin']);
					foreach($entity_admins as $user_data){
						$res = array();
						$res = array('user_id'=>$user_data,'branch_id'=>$entity);
						$result = $this->db->insert('assigned_entities',$res);
					}
				}
			}
		}
		else{
			return false;
		}
		return true;
	}

	public function role_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			$this->db->select('*');
			$this->db->from('roles');
			if($type == 1){
				return $this->db->count_all_results();	
			}else{
				$page = !empty($inputs['page'])?$inputs['page']:'';
				$limit = $inputs['limit'];
				if($page>=1){
					$page = $page - 1 ;
				}
				$page =  ($page * $limit);	
				$this->db->order_by('r_id', 'ASC');
				$this->db->limit($limit,$page);
				$results = $this->db->get()->result_array();
				return $results;
			}
		  
		} 
	}


	public function menu_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			$this->db->select('name,module,icon,visible');
			$this->db->from('hooks');
			$this->db->where('hook', 'main_menu_'.$inputs['role_name']);
			$this->db->where('route !=', '#');
			$this->db->order_by('order', 'ASC');
			if($type == 1){
				return $this->db->count_all_results();	
			}else{
				if(!empty($inputs['limit'])){
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
					
					$this->db->limit($limit,$page);
				}
				$results = $this->db->get()->result_array();
				return $results;
			}
		  
		} 
	}

	public function not_added_menu_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
			$check_role = $this->db->get_where('roles',array('r_id'=>$inputs['role_id']))->result_array();
			$role_name	= '';
			$role_menus = array();
			if(!empty($check_role[0]['role'])){
				$role_name = $check_role[0]['role'];
			
				$this->db->select('name,module,icon,visible');
				$this->db->from('hooks');
				$this->db->where('hook', 'main_menu_'.$role_name);
				$this->db->where('route !=', '#');
				$role_menus = $this->db->get()->result_array();
			}
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			$this->db->select('name,module,icon,visible');
			$this->db->from('hooks');
			$this->db->where('hook', 'main_menu_admin');
			$this->db->where('route !=', '#');
			if(!empty($role_menus)){
				$req_module = array_column($role_menus, 'module');
				$this->db->where_not_in('module', $req_module);
			}
			if($type == 1){
				return $this->db->count_all_results();	
			}else{
				$page = !empty($inputs['page'])?$inputs['page']:'';
				$limit = $inputs['limit'];
				if($page>=1){
					$page = $page - 1 ;
				}
				$page =  ($page * $limit);	
				$this->db->order_by('order', 'ASC');
				$this->db->limit($limit,$page);
				$results = $this->db->get()->result_array();
				return $results;
			}
		  
		} 
	}



	public function change_menu_status($token,$inputs)
	{
		
		$record =  $this->get_role_and_userid($token);
		
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$branch_id = $this->get_entity_id($user_id);
            $check_role = $this->db->get_where('roles',array('r_id'=>$inputs['role_id']))->result_array();
			
			
			if(!empty($check_role))
            {
				$role_name = $check_role[0]['role'];
				$this->db->where('hook','main_menu_'.$role_name);
				$this->db->where('name',$inputs['menu_key']);
				$this->db->where('route !=','#');
				return $this->db->update('hooks', array('visible'=>$inputs['visible']));
				return true;

			}
		  
		} 
		return false;
	}


	public function add_menu($token,$inputs)
	{
		
		$record =  $this->get_role_and_userid($token);
		
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$branch_id = $this->get_entity_id($user_id);
            $check_role = $this->db->get_where('roles',array('r_id'=>$inputs['role_id']))->result_array();
			
			
			if(!empty($check_role))
            {
				$role_name = $check_role[0]['role'];
				$menus = $inputs['role_menu_to'];
				$all_menus	= explode(',',$menus);
				for($i=0;$i<count($all_menus);$i++)
                {
                    $get_menu_details = $this->db->get_where('hooks',array('hook'=>'main_menu_admin','name'=>$all_menus[$i],'route !='=>'#'))->row_array();
                    if($get_menu_details['parent'] != ''){

                        $get_parent_details = $this->db->get_where('hooks',array('hook'=>'main_menu_admin','module'=>$get_menu_details['parent']))->row_array();
                        $check_count = $this->db->get_where('hooks',array('hook'=>'main_menu_'.strtolower($role_name),'module'=>$get_parent_details['module']))->result_array();
                        if(count($check_count) == 0 )
                        {
                            $ress = array(
                                'branch_id' => $branch_id,
                                'module' => $get_parent_details['module'],
                                'parent' => $get_parent_details['parent'],
                                'hook' => 'main_menu_'.strtolower($role_name),
                                'icon' => $get_parent_details['icon'],
                                'name' => $all_menus[$i],
                                'route' => $get_parent_details['route'],
                                'order' => $get_menu_details['order'],
                                'access' => $inputs['role_id'],
                                'core' => $get_parent_details['core'],
                                'visible' => $get_parent_details['visible'],
                                'permission' => $get_parent_details['permission'],
                                'enabled' => $get_parent_details['enabled']
                            );
                            $this->db->insert('hooks',$ress);
                            $e = ($e + 1);
                        }
                    }
                    $res = array(
						'branch_id' => $branch_id,
                        'module' => $get_menu_details['module'],
                        'parent' => $get_menu_details['parent'],
                        'hook' => 'main_menu_'.strtolower($role_name),
                        'icon' => $get_menu_details['icon'],
                        'name' => $all_menus[$i],
                        'route' => $get_menu_details['route'],
                        'order' =>$get_menu_details['order'],
                        'access' => $inputs['role_id'],
                        'core' => $get_menu_details['core'],
                        'visible' => $get_menu_details['visible'],
                        'permission' => $get_menu_details['permission'],
                        'enabled' => $get_menu_details['enabled']
                    );
                        $this->db->insert('hooks',$res);
                        $e++;
                }
				return true;

			}
		  
		} 
		return false;
	}


	public function add_role($token,$inputs)
	{
		
		$record =  $this->get_role_and_userid($token);
		
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$branch_id = $this->get_entity_id($user_id);
            $check_role = $this->db->get_where('roles',array('role'=>strtolower($inputs['role_name'])))->result_array();
			
			
			if(empty($check_role))
            {
				$this->db->order_by('r_id',DESC);
            	$last_role = $this->db->get('roles')->row_array();
				$role_ar = array(
                    'branch_id'    => $branch_id,
                    'role'    => strtolower($inputs['role_name']),
                    'default' => ($last_role['default'] + 1),
                    'permissions' => '{"settings":"permissions","role":"'.strtolower($inputs['role_name']).'","view_all_invoices":"on","edit_invoices":"on","pay_invoice_offline":"on","view_all_payments":"on","email_invoices":"on","send_email_reminders":"on"}'
                );
                $this->db->insert('roles',$role_ar);
                $insert_id = $this->db->insert_id();
				$menus = $inputs['role_menu_to'];
				$all_menus	= explode(',',$menus);
				for($i=0;$i<count($all_menus);$i++)
                {
                    $get_menu_details = $this->db->get_where('hooks',array('hook'=>'main_menu_admin','name'=>$all_menus[$i],'route !='=>'#'))->row_array();
                    if($get_menu_details['parent'] != ''){

                        $get_parent_details = $this->db->get_where('hooks',array('hook'=>'main_menu_admin','module'=>$get_menu_details['parent']))->row_array();
                        $check_count = $this->db->get_where('hooks',array('hook'=>'main_menu_'.strtolower($inputs['role_name']),'module'=>$get_parent_details['module']))->result_array();
                        if(count($check_count) == 0 )
                        {
                            $ress = array(
                                'branch_id' => $branch_id,
                                'module' => $get_parent_details['module'],
                                'parent' => $get_parent_details['parent'],
                                'hook' => 'main_menu_'.strtolower($inputs['role_name']),
                                'icon' => $get_parent_details['icon'],
                                'name' => $all_menus[$i],
                                'route' => $get_parent_details['route'],
                                'order' => $get_menu_details['order'],
                                'access' => $insert_id,
                                'core' => $get_parent_details['core'],
                                'visible' => $get_parent_details['visible'],
                                'permission' => $get_parent_details['permission'],
                                'enabled' => $get_parent_details['enabled']
                            );
                            $this->db->insert('hooks',$ress);
                            $e = ($e + 1);
                        }
                    }
                    $res = array(
						'branch_id' => $branch_id,
                        'module' => $get_menu_details['module'],
                        'parent' => $get_menu_details['parent'],
                        'hook' => 'main_menu_'.strtolower($inputs['role_name']),
                        'icon' => $get_menu_details['icon'],
                        'name' => $all_menus[$i],
                        'route' => $get_menu_details['route'],
                        'order' =>$get_menu_details['order'],
                        'access' => $insert_id,
                        'core' => $get_menu_details['core'],
                        'visible' => $get_menu_details['visible'],
                        'permission' => $get_menu_details['permission'],
                        'enabled' => $get_menu_details['enabled']
                    );
                        $this->db->insert('hooks',$res);
                        $e++;
                }
				return true;

			}
		  
		} 
		return false;
	}
	public function attendance_regularization_dates($token,$inputs){
		$record =  $this->get_role_and_userid($token);
		
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$results = $this->db->where('user_id',$user_id)->where('a_month',$inputs['month'])->where('a_year',$inputs['year'])->get('dgt_attendance_details')->result_array();
			$all_days = unserialize($results[0]['month_days']);
			$attendance_dates = array();
			$cur_date_ch = date('d-m-Y');
			if(!empty($all_days)){
				$i = 0;
				foreach($all_days as $key=>$all_day_1){
					$production_hour = 0;
					foreach ($all_day_1[$key] as $punch_detailss) 
					{
						if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
						{
							
							$production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
						}
					}
					if(empty($all_day_1['punch_in']) || empty($all_day_1['punch_out']) || $production_hour <8){
						
						$account_details	=	$this->db->where('user_id',$user_id)->get('dgt_account_details')->row_array();
						$entity_id	=	$account_details['branch_id'];
						$req_day	=	$key + 1;
						$sel_date	=	$inputs['year'].'-'.$inputs['month'].'-'.$req_day;
						$req_date 	=	date('d-m-Y',strtotime($sel_date)); 
						$user_leave = 	$this->db->where('user_id',$user_id)->where('leave_from>=',$req_date)->where('leave_to<=',$req_date)->get('dgt_user_leaves')->result_array();
						$cur_date = (int) date('d',strtotime($sel_date));
						$cur_month = (int) date('m',strtotime($sel_date));
						$cur_year = date('Y',strtotime($sel_date));

						$ar_detail = 	$this->db->where('user_id',$user_id)->where('a_day',$cur_date)->where('a_month',$cur_month)->where('a_year',$cur_year)->get('dgt_attendance_details_ar')->result_array();
						if(empty($user_leave) && empty($ar_detail)){
							if(strtotime($cur_date_ch)>strtotime($req_date)){
								$attendance_dates[$i]	=	$req_date;
								$i++;
							}
						}
					}
				}
			}
			return $attendance_dates;
		}
		return false;
	}
	public function attendance_regularization_save($token,$inputs){
		
		$record =  $this->get_role_and_userid($token);
		//$inputs['date'] = DateTime::createFromFormat('d-m-Y', $inputs['date'])->format('Y-m-d');
		$inputs['date']	=	date('Y-m-d',strtotime($inputs['date']));
		$a_day = date('d',strtotime($inputs['date']));;
		$a_day = (int) $a_day;
		$a_month = date('m',strtotime($inputs['date']));
		$a_month = (int) $a_month;
		$a_year = date('Y',strtotime($inputs['date']));
		$a_year = (int) $a_year;
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$result = $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details_ar WHERE user_id = $user_id AND a_day = $a_day AND a_month = $a_month AND a_year = $a_year ")->row_array();
			if(!empty($result)){
				return $result;
			}else{
				$result1 = $this->db->query("SELECT * FROM dgt_users WHERE id = $user_id  ")->row_array();
				$team_lead_id = $result1['teamlead_id'];
				$days = array();
				$days_in_out = array();
				$lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'$a_day'));
				for($i=1;$i<=$lat_day;$i++){
					$day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
					$day = (strtolower($day)=='sun')?0:'';
					$day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
					$days[] = $day_details;
					$days_in_out[] = array($day_details);
				}
				//$day = date('D',strtotime($a_year.'-'.$a_month.'-'.$a_day));
				//$day = (strtolower($day)=='sun')?0:'';
				//$day_details = array('day'=>$day,'punch_in'=>$inputs['from_time'],'punch_out'=>$inputs['to_time']);
				//$day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
				
				//$days[$a_day-1] = $day_details;
				//$days_in_out[] = array($day_details);

				$reason  = '';
				switch($inputs['reason']){
					case 'Forgot to Check In':
						$reason	=	1;
						break;
					case 'Forgot to Check Out':
						$reason	=	2;
						break;
					case 'Network Issues':
						$reason	=	3;
						break;
					case 'On Duty':
						$reason	=	4;
						break;
					case 'Permission':
						$reason	=	5;
						break;
				}
				
				$insert = array(
					'user_id'=>$user_id,
					'month_days'=>serialize($days),
					'month_days_in_out'=>serialize($days_in_out),
					'a_day'=>$a_day,
					'a_month'=>$a_month,
					'a_year'=>$a_year,
					'ro_id'=>$team_lead_id,
					'reason'=>$reason
					);
				$this->db->insert("dgt_attendance_details_ar",$insert);

				return  $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details_ar WHERE user_id = $user_id AND a_day = $a_day AND a_month = $a_month AND a_year = $a_year ")->row_array();
			}
		}
		else{
			return false;
		}
	}
	public function attendance_regularization_reject_list($token,$inputs,$list_cnt){
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$page = !empty($inputs['page'])?($inputs['page']-1)*$inputs['limit']:0;
			
			if($list_cnt==1){
				if($role == 1){
					$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar where ro_status= '2'    ")->result_array();
				}
				else{
					$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar where user_id = '".$user_id."' and ro_status= '2'    ")->result_array();

				}
				return count($results);exit;

			}
			if($role == 1){
				$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar  where ro_status= '2'  order by id desc limit ".$page.",".$inputs['limit']." ")->result_array();
			}
			else{
				$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar where user_id = '".$user_id."' and ro_status= '2'   order by id desc limit ".$page.",".$inputs['limit']."  ")->result_array();
			}
			$regularize = array();
			$i = 0;
			if(!empty($results)){
				foreach($results as $result_1){
					$emp_details = $this->db->query("SELECT user_id,fullname FROM dgt_account_details WHERE user_id = '".$result_1['user_id']."' ")->row_array();
					$regularize[$i]['day']		=	$result_1['a_day'];
					$regularize[$i]['month']	=	$result_1['a_month'];
					$regularize[$i]['year']		=	$result_1['a_year'];
					$regularize[$i]['user_id']	=	$result_1['user_id'];
					$regularize[$i]['emp_name']	=	$emp_details['fullname'];
					$reason  = '';
					switch($result_1['reason']){
						case 1:
							$reason	=	'Forgot to Check In';
							break;
						case 2:
							$reason	=	'Forgot to Check Out';
							break;
						case 3:
							$reason	=	'Network Issues';
							break;
						case 4:
							$reason	=	'On Duty';
							break;
						case 5:
							$reason	=	'Permission';
							break;
					}
					$regularize[$i]['reason']	=	$reason;
					$punch_ins	=	unserialize($result_1['month_days']);
					
					$all_punch_ins = array();
					$production_hour=0;
					if(!empty($punch_ins)){
						$j = 0;
						foreach($punch_ins as $punch_in_1){
							$production_hour += time_difference(date('H:i',strtotime($punch_in_1['punch_in'])),date('H:i',strtotime($punch_in_1['punch_out'])));
							$all_punch_ins[$j]	= $punch_in_1;
						}
					}
					$regularize[$i]['hours'] = intdiv($production_hour, 60).':'. ($production_hour % 60);
					$regularize[$i]['punch_in']	=	$all_punch_ins;
					$regularize[$i]['ro_status']=	$result_1['ro_status'];
					if($result_1['ro_status']==2)
					{
						$regularize[$i]['reject_reason']=	$result_1['reject_reason'];
					}
					$i++;
				}
			}
			return $regularize;
		}
		else{
			return false;
		}

	}
	public function attendance_regularization_list($token,$inputs,$list_cnt){
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$page = !empty($inputs['page'])?($inputs['page']-1)*$inputs['limit']:0;
			$cur_cond = '';
			if(!empty($inputs['employee_name'])){
				$emp_details = $this->db->query("SELECT fullname,user_id FROM dgt_account_details  where fullname like '%".$inputs['employee_name']."%' order by id desc  ")->result_array();
				$cur_user_id = '';
				$i1 = 0;
				if(!empty($emp_details)){
					foreach($emp_details as $emp_detail_1){
						$cur_user_id = $emp_detail_1['user_id'];
						if($i1 == 0){
							$cur_cond = " and ( user_id = '".$cur_user_id."' ";
						}
						else{
							$cur_cond	.= " or user_id = '".$cur_user_id."' ";
						}
						$i1++;
					}
					$cur_cond .= " ) ";
				}
				else{
					$cur_cond = " and user_id = '' ";
				}
			}
			if($list_cnt==1){
				if($role == 1){
					$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar  where ro_status= '0' ".$cur_cond." order by id desc  ")->result_array();
				}
				else{
					$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar where ro_id = '".$user_id."' and ro_status= '0' ".$cur_cond."   ")->result_array();
				}
				return count($results);exit;

			}
			if($role == 1){
				$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar  where ro_status= '0' ".$cur_cond." order by id desc limit ".$page.",".$inputs['limit']." ")->result_array();
			}
			else{
				$results = $this->db->query("SELECT a_day,a_month,a_year,reason,ro_status,reject_reason,month_days,month_days_in_out,user_id FROM dgt_attendance_details_ar where ro_id = '".$user_id."' and ro_status= '0' ".$cur_cond."  order by id desc limit ".$page.",".$inputs['limit']."  ")->result_array();
			}
			$regularize = array();
			$i = 0;
			if(!empty($results)){
				foreach($results as $result_1){
					$emp_details = $this->db->query("SELECT user_id,fullname FROM dgt_account_details WHERE user_id = '".$result_1['user_id']."' ")->row_array();
					$regularize[$i]['day']		=	$result_1['a_day'];
					$regularize[$i]['month']	=	$result_1['a_month'];
					$regularize[$i]['year']		=	$result_1['a_year'];
					$regularize[$i]['user_id']	=	$result_1['user_id'];
					$regularize[$i]['emp_name']	=	$emp_details['fullname'];
					$reason  = '';
					switch($result_1['reason']){
						case 1:
							$reason	=	'Forgot to Check In';
							break;
						case 2:
							$reason	=	'Forgot to Check Out';
							break;
						case 3:
							$reason	=	'Network Issues';
							break;
						case 4:
							$reason	=	'On Duty';
							break;
						case 5:
							$reason	=	'Permission';
							break;
					}
					$regularize[$i]['reason']	=	$reason;
					$punch_ins	=	unserialize($result_1['month_days']);
					
					$all_punch_ins = array();
					$production_hour=0;
					if(!empty($punch_ins)){
						$j = 0;
						foreach($punch_ins as $punch_in_1){
							$production_hour += time_difference(date('H:i',strtotime($punch_in_1['punch_in'])),date('H:i',strtotime($punch_in_1['punch_out'])));
							$all_punch_ins[$j]	= $punch_in_1;
						}
					}
					$regularize[$i]['hours'] = intdiv($production_hour, 60).':'. ($production_hour % 60);
					$regularize[$i]['punch_in']	=	$all_punch_ins;
					$regularize[$i]['ro_status']=	$result_1['ro_status'];
					if($result_1['ro_status']==2)
					{
						$regularize[$i]['reject_reason']=	$result_1['reject_reason'];
					}
					$i++;
				}
			}
			return $regularize;
		}
		else{
			return false;
		}
	}
	public function attendance_regularization_approve_reject($token,$inputs){
		$record =  $this->get_role_and_userid($token);
		$a_day = date('d',strtotime($inputs['date']));
		$a_day = (int) $a_day;
		$a_month = date('m',strtotime($inputs['date']));
		$a_month = (int) $a_month;
		$a_year = date('Y',strtotime($inputs['date']));
		$a_year = (int) $a_year;
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$results = $this->db->query("SELECT * FROM dgt_attendance_details_ar where user_id = '".$inputs['user_id']."' and a_day = '".$a_day."' and a_month = '".$a_month."' and a_year = '".$a_year."' ")->row_array();
			$month_days = unserialize($results['month_days']);
			$punch_in_time = $punch_out_time = '';
			if(!empty($month_days)){
				foreach($month_days as $month_day_1){
					if(!empty($month_day_1['punch_in']) && !empty($month_day_1['punch_out'])){
						$punch_in_time = $month_day_1['punch_in'];
						$punch_out_time = $month_day_1['punch_out'];
					}
				}
			}
			$in_date=$a_year.'-'.$a_month.'-'.$a_day.' '.$punch_in_time;
			$out_date=$a_year.'-'.$a_month.'-'.$a_day.' '.$punch_out_time;
			$strtotime = strtotime($in_date);
			$strtotime_out = strtotime($out_date);
			$a_cin     = date('H:i',$strtotime);
			$a_cout    = date('H:i',$strtotime_out);
			$where= array('user_id'=>$inputs['user_id'],'a_month'=>$a_month,'a_year'=>$a_year);
			$this->db->select('month_days,month_days_in_out');
			$record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
			if(empty($record) && $inputs['status']==1){
				$inputs['attendance_month'] =$a_month;
				$inputs['attendance_year'] = $a_year;
				Attendance_model::attendance($inputs['user_id'],$inputs);
				$this->db->select('month_days,month_days_in_out');
				$record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
			}

			$this->db->select('month_days,month_days_in_out,reason');
				$record  = $this->db->get_where('dgt_attendance_details_ar',$where)->row_array();

			if(!empty($record['month_days'])){
				$record_day = unserialize($record['month_days']);
				$month_days_in_out_record = unserialize($record['month_days_in_out']);
		
				$a_day -=1;
				
				
				 if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
				  $current_days = $month_days_in_out_record[$a_day];
				  $total_records = count($current_days);
				  $current_day = end($current_days);
				  
		  
		
				  if($record_day[$a_day]['punch_in'] ==''){
					$record_day[$a_day]['punch_in'] = $a_cin;
					 $record_day[$a_day]['punch_out'] = $a_cout;
					$record_day[$a_day]['day'] = 1;
				  }
				  $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>$a_cout);
				$month_days_in_out_record[$a_day] = $current_days;
				 }
				  
			  }
			if($inputs['status']==1){
			
			  $this->db->where($where);
			  $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
			}
			$where1= array('user_id'=>$inputs['user_id'],'a_day'=>$a_day+1,'a_month'=>$a_month,'a_year'=>$a_year);
			  $this->db->where($where1);
			  $this->db->update('dgt_attendance_details_ar', array('ro_status'=>$inputs['status'],'reject_reason'=>$inputs['reason'],'ro_id'=>$user_id));
			  $params = $inputs;

			  $where1= array('user_id'=>$user_id,'a_day'=>$a_day+1,'a_month'=>$a_month,'a_year'=>$a_year);
				$this->db->where($where1);
				$this->db->update('dgt_attendance_details_ar', array('ro_status'=>$params['ro_status'],'reject_reason'=>$params['reject_reason'],'ro_id'=>$this->session->userdata('user_id')));


				$message = App::email_template('attendance_regularization_approve','template_body');
				$subject = App::email_template('attendance_regularization_approve','subject');
				$signature = App::email_template('email_signature','template_body');

				$logo_link = create_email_logo();

				$reason  = '';
				switch($record['reason']){
					case 1:
					$reason	=	'Forgot to Check In';
					break;
					case 2:
					$reason	=	'Forgot to Check Out';
					break;
					case 3:
					$reason	=	'Network Issues';
					break;
					case 4:
					$reason	=	'On Duty';
					break;
					case 5:
					$reason	=	'Permission';
					break;
				}
				$account_details=$this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
				
				$message = str_replace("{INVOICE_LOGO}",$logo_link,$message);
				$message = str_replace("{USER}",$account_details['fullname'],$message);
				if(empty($params['reject_reason'])){
					$subject = str_replace("{APPROVE_REJECT}",'Approved',$subject);
					$message = str_replace("{APPROVE_REJECT}",'Approved',$message);
				}else{
					$subject = str_replace("{APPROVE_REJECT}",'Rejected',$subject);
					$message = str_replace("{APPROVE_REJECT}",'Rejected',$message);
				}
				$message = str_replace("{ATTENDANCE_DATE}",$record['a_year'].'-'.$record['a_month'].'-'.$record['a_day'],$message);
				$message = str_replace("{CHECK_IN}",$punch_in_time,$message);
				$message = str_replace("{CHECK_OUT}",$punch_out_time,$message);
				$message = str_replace("{REASON}",$reason,$message);
				$message = str_replace("{SIGNATURE}",$signature,$message);
				if(empty($params['reject_reason'])){
					
					$message = str_replace("{REJECT_REASON}",'',$message);
				}else{
					$message = str_replace("{REJECT_REASON}",$params['reject_reason'],$message);
				}
				$team_lead_details = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
				if(!empty($team_lead_details['email'])){
					$params['recipient'] = $team_lead_details['email'];
					$params['subject'] = $subject;
					$params['message'] = $message;

					$params['attached_file'] = '';
					modules::run('fomailer/send_email',$params);
				}

			
			return true;
		}
		else{
			return false;
		}
	}
	public function geo_fencing_approve_reject($token,$inputs){
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 ){
				$this->db->where('config_key','geo_fencing');
				return $this->db->update('dgt_config', array('value'=>$inputs['status']));
				exit;
		 	}
			return false;
		}
		else{
			return false;
		}
	}


}

/* End of file Api_model.php */
/* Location: ./application/controllers/Api_model.php */
