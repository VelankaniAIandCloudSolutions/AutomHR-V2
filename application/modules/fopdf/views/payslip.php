<?php 

$this->db->where('id',$form_data['payslipid']);
$details = $this->db->get('payslip')->row_array();
$pay_slip_details = json_decode($details['payslip_details'],TRUE);

$pdf = new tFPDF();

$pdf->AddPage(); 

$user_det = $this->db->query("select fullname FROM `dgt_account_details` where user_id = ".$form_data['user_id']."")->result_array();

$this->db->select('U.id,AD.fullname as username,U.email,U.created,C.company_name, CONCAT(C.company_address,", ",C.city,", ",C.state,", ",C.country) as address');
$this->db->from('users U');
$this->db->join('account_details AD', 'AD.user_id = U.id', 'left');
$this->db->join('companies C', 'C.co_id = AD.company', 'left');
$this->db->where('U.id', $form_data['user_id']);
$employee_info = $this->db->get()->row();

if(!empty($user_det)){

  	$earnings = 0;
	$deductions = 0;


	$pdf->Cell(20,20);			 

	$pdf->Image('assets/images/'.config_item('company_logo'),80,4,0,10); 

	$pdf->Ln(5);				 

	 
 	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Company Name : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,$employee_info->company_name);

	
	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 


	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'PAYSLIP');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,' #'.$form_data['payslipid']);

	$pdf->Ln(5);
	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Address : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,$employee_info->address);

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 
	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Salary Month:');

	$salary_moth = $form_data['year'].'-'.$form_data['month'].'-1';

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5, date('F Y',strtotime($salary_moth)));

	$pdf->Ln(5);

 


	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Employee Name : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,$user_det[0]['fullname']);

	$pdf->Ln(5);

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Month & Year : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,date('F Y',strtotime($salary_moth)));

	$pdf->Ln(10);

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(50,5,'Earnings',1);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'Deductions',1);

	$pdf->Cell(40,5,'',1);

	

	$pdf->Ln();


	$pdf->SetFont('Arial','',7);

	$addition=array();
		$addition_value=array();
		$deduction=array();
		$deduction_value=array();

	 foreach ($pay_slip_details as $key => $value) {

	 	

	 	if(strpos($key, 'addtion||')!== false || strpos($key, 'deduction||')!== false) { 

	 	if(strpos($key, 'addtion||')!== false) { 
	 		$addition[]=ucwords(str_replace(array('addtion||','_'), ' ', $key));
	 		$addition_value[]=$value;
	 		$earnings +=$value;

	 	}

	 	if(strpos($key, 'deduction||')!== false){
	 		$deduction[]=ucwords(str_replace(array('deduction||','_'), ' ', $key));
	 		$deduction_value[]=$value; 
	 		$deductions +=$value;
	 	}

	 	
	  }
     }

     for ($i=0; $i < count($addition + $deduction) ; $i++) { 
     	
	
	
	$pdf->Cell(50,5,$addition[$i] ,1);

	$pdf->Cell(50,5,$addition_value[$i],1);

	$pdf->Cell(50,5,$deduction[$i]  ,1);

	$pdf->Cell(40,5,$deduction_value[$i] ,1); 

    $pdf->Ln();
    
       
   }

	

	
	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(50,5,'Total Earnings',1);

	$pdf->Cell(50,5,$earnings,1);

	$pdf->Cell(50,5,'Total Deductions',1);

	$pdf->Cell(40,5,$deductions,1);

	

	$pdf->Ln();

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'NET Salary',1);

	$pdf->Cell(40,5,($earnings-$deductions),1);

	

	$pdf->Ln(20);

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Signature of Employee');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,' _______________________________'); 

	

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,' ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,'                                     '); 

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Signature of Director');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,' _______________________________');

 	

	$pdf->Ln(10);

}

	 

$pdf->Output('Employee_Payslip.pdf','D'); 



  

exit; //don't remove this