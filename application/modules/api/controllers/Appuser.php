<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Appuser extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model','user');
        $header =  getallheaders(); // Get Header Data

        $token  = '';
        if(!empty($header['token'])){ $token = $header['token'];        }
        
        if(empty($token)){ 
            if(!empty($header['Token'])){ $token = $header['Token']; }
        }
        if (empty($token)) {
            $this->is_valid = FALSE;    
        }if(!empty($token)){
            $this->token = $token;
            $valid = $this->user->is_valid_token($token);

            if($valid){
                $this->is_valid = TRUE;    
            }else{
                $this->is_valid = FALSE;
            }
            
        }

    }
  
    public function login_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['username']) && !empty($inputs['password']) && !empty($inputs['device_id'])){

                $result = $this->user->is_valid_login($inputs); 

                  if(!empty($result)){
                    $check_device = $this->user->check_device($result['id']);
                        if($check_device != '')
                        {
                            $dev_res = $this->user->device_update($check_device['dev_id'],$inputs['device_id'],'update');
                        }else{
                            $dev_res = $this->user->device_update($result['id'],$inputs['device_id'],'insert');
                        }
                        $response['status_code'] = 1;
                        $response['message'] = 'SUCCESS';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid login credential';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }

     public function create_employee_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['username']) && !empty($inputs['password'])&& !empty($inputs['email'])&& !empty($inputs['fullname'])  && !empty($inputs['phone']) && !empty($inputs['emp_doj'])&& !empty($inputs['reporting_to'])){

                 $result = $this->user->create_user($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'Employee username or email already exists...';
                   }else{
                        $response['message'] = 'Invalid employee details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function create_project_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();
            
            if(!empty($inputs['project_code']) && !empty($inputs['project_title'])&& !empty($inputs['client'])&& !empty($inputs['assign_lead'])  && !empty($inputs['assign_to'])  && !empty($inputs['start_date'])  && !empty($inputs['due_date']) && !empty($inputs['estimate_hours'])  && !empty($inputs['description'])){

                 
                

                 $result = $this->user->create_project($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'company not set';
                   }else{
                        $response['message'] = 'Invalid project details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


     public function edit_project_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];


            if(!empty($inputs['project_code']) && !empty($inputs['project_title'])&& !empty($inputs['client'])&& !empty($inputs['assign_lead'])  && !empty($inputs['assign_to'])  && !empty($inputs['start_date'])  && !empty($inputs['due_date']) && !empty($inputs['estimate_hours'])  && !empty($inputs['description'])){

                 
                

                 $result = $this->user->edit_project($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'company not set';
                   }else{
                        $response['message'] = 'Invalid project details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }

   public function delete_project_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_project($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'Permission Denied';
                   }else{
                        $response['message'] = 'Invalid project details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }

    public function task_view_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['task']) && !empty($inputs['project'])){

                 
                

                 $result = $this->user->task_view($inputs,$this->token);    

                  if($result){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_task_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['task_name']) && !empty($inputs['project'])){

                 
                

                 $result = $this->user->create_task($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }






//     public function delete_project_post()
//     {
//         if($this->is_valid == TRUE)   {

//             $data = array();
//             $response = array();
//             $response['status_code'] = -1;
//             $response['message'] = 'Required input missing';
//             $response['data'] = $data;

//             $inputs = $this->post();

// //             echo $inputs['assign_lead'];

// // exit;

//             $result = $this->user->delete_project($inputs,$this->token);  

//                if($result==1){
//                         $response['status_code'] = 1;
//                         $response['message'] = 'Success';
//                         $response['data'] = $result;
//                    }elseif($result==2){
//                         $response['message'] = 'Permission Denied';
//                    }else{
//                         $response['message'] = 'Invalid project details';
//                    }
//             $this->response($response, REST_Controller::HTTP_OK);
//         }else{

//             $this->token_error();
//         }
//     }


    public function assign_user_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['project']) && !empty($inputs['task']) && !empty($inputs['type'])){

                 
                

                 $result = $this->user->assign_user($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function edit_task_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['task_name']) && !empty($inputs['assigned_to'])){

                 
                

                 $result = $this->user->edit_task($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function delete_task_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_task($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }

    public function task_completion_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->task_completion($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_client_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['company_name']) && !empty($inputs['company_email'])){

                 
                

                 $result = $this->user->create_client($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid client details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_client_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['company_name']) && !empty($inputs['company_email'])){

                 
                

                 $result = $this->user->edit_client($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid client details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_client_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_client($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_invoice_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;
            


            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->create_invoice($inputs,$this->token); 

                    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid invoice details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_invoice_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->edit_invoice($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid invoice details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_invoice_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_invoice($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid invoice details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_estimate_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->create_estimate($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid estimate details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_estimate_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();
//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->edit_estimate($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid estimate details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_estimate_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_estimate($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid estimate details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }



    public function create_expense_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['amount']) && !empty($inputs['category'])){

                 
                

                 $result = $this->user->create_expense($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['status_code'] = -1;
                        $response['message'] = 'File Upload failed';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid expense details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_expense_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['amount']) && !empty($inputs['category'])){

                 
                

                 $result = $this->user->edit_expense($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid expense details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_expense_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_expense($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid expense details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }





    

     public function countries_get()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

             $result = $this->user->countries();    
              if(!empty($result)){
                        $response['status_code'] = 1;
                        $response['message'] = 'SUCCESS';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'No details found';
                   }
            
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }

    public function forget_password_post()
    {
    		
    }

    public function token_error(){
        $this->response([
                'code' => 498,
                'status' => FALSE,
                'message' => 'Invalid token or Token missing'
            ], REST_Controller::HTTP_OK);
    }

    public function punch_in_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

                 $strtotime = strtotime(date('Y-m-d H:i'));
                  $a_year    = date('Y',$strtotime);
                  $a_month   = date('m',$strtotime);
                  $a_day     = date('d',$strtotime);
                  $a_cin     = date('H:i',$strtotime); 

                   $record = $this->user->get_attendance_data($this->token);
                                  
                
                if($record){

                $result = array(
                    'punch_in' => $a_cin
                );
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
                }else{
                    $response['status_code'] = 1;
                    $response['message'] = 'Something went wrong, please try again later.';
                }
           
        $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function punch_out_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

                  $strtotime = strtotime(date('Y-m-d H:i'));
                  $a_year    = date('Y',$strtotime);
                  $a_month   = date('m',$strtotime);
                  $a_day     = date('d',$strtotime);
                  $a_cout     = date('H:i',$strtotime); 

                   $record = $this->user->get_punchout_attendance_data($this->token);

                                  
                
                if($record){

                $result = array(
                    'punch_out' => $a_cout
                );
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
                }else{
                    $response['status_code'] = 1;
                    $response['message'] = 'Something went wrong, please try again later.';
                }
           
        $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function user_punch_in_details_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['date']) && !empty($inputs['time'])){
                $date = str_replace('/', '-', $inputs['date']);
                $time=strtotime($date);
                $day=date("d",$time) - 1;
                $month=date("m",$time);
                $month = ltrim($month, '0');
                $year=date("Y",$time);

                $client_datas = $this->user->get_attendance_data($this->token,$inputs,$month,$year);

                $client_data = unserialize($client_datas['month_days']);
               


                $result['punch_in']=(!empty($client_data[$day]['punch_in'])?$client_data[$day]['punch_in']:"0");
                $result['punch_out']=(!empty($client_data[$day]['punch_out'])?$client_data[$day]['punch_out']:"0");
                     
                     
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;


            }else{
                $response['status_code'] = 0;
                $response['message'] = 'Required inputs missing';
            }
        $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function user_profilepic_upload_post()
    {
        if($this->is_valid == TRUE)   {
            $uploaddir = 'assets/uploads/';
            $file_name = time().underscore($_FILES['file']['name']);
            $uploadfile = $uploaddir.$file_name;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $result = base_url().$uploadfile;
                $user_details = $this->user->get_role_and_userid($this->token);
                // print_r($user_details); exit;
                $this->user->upload_profilepic($user_details['user_id'],$file_name);
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
             } else {
                $response['status_code'] = 0;
                $response['message'] = 'Image Upload Error.';       
             }
              $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function app_colorcode_post()
   {
       $result = array(
           'primary_color'     =>  '#0a15d5',
           'secondry_color'    =>  '#fe9c45',
           'black_logo'        =>  base_url().'assets/images/black_logo.png',
           'full_white_logo'   =>  base_url().'assets/images/full_white_logo.png',
           'white_logo'        =>  base_url().'assets/images/white_logo.png'
       );
       $response['status_code'] = 1;
       $response['message'] = 'SUCCESS';
       $response['data'] = $result;
       $this->response($response, REST_Controller::HTTP_OK);
   }
   
   
   public function username_check_post()
   {
        $inputs = $this->post();
        $check_username = $this->user->checkusernameemail(array('username'=>$inputs['username']));
       
       // echo print($check_username) ; exit;
        if($check_username == 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'REGISTER IN THE BACKEND FIRST.';
        }else{
            $login_type = $inputs['login_type'];
            $check_log_type = $this->user->checklog_type($inputs['username'],$inputs['login_type']);
            $response['status_code'] = 1;
            $response['message'] = 'SUCCESS';
        }
        $this->response($response, REST_Controller::HTTP_OK);
   }


   public function face_punch_post()
   {
        $data = array();
        $response = array();
        $res = array();
        
        $inputs = $this->post();
        $users = $this->user->get_userid($inputs['username']);
        $user_name = ucfirst($inputs['username']);
        $user_id = $users['id']; 
        $strtotime = strtotime(date('Y-m-d H:i'));
        $a_year    = date('Y',$strtotime);
        $a_month   = date('m',$strtotime);
        $a_day     = date('d',$strtotime);
        $a_cin     = date('H:i',$strtotime);
        $where = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
        $this->db->select('month_days,month_days_in_out');
        $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
        // echo $this->db->last_query();
        // exit();
        if(empty($record))
        {
            $details['attendance_month'] =$a_month;
            $details['attendance_year'] = $a_year;
            $this->user->face_attendance($user_id,$details);  
            $where = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
            $this->db->select('month_days,month_days_in_out');
            $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
        }

         if(!empty($inputs['latitude']) && !empty($inputs['longitude'])){
            $punch_address= punch_address($inputs['latitude'],$inputs['longitude']);
        }else{
            $punch_address = '';
        }

        // if(!empty($inputs['latitude']) && !empty($inputs['longitude'])){


        //     $url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAkA_CZ6XSZiMDeVjc1iVVTHAk4wmwQ6p8&latlng='.trim($inputs['latitude']).','.trim($inputs['longitude']).'&sensor=true'; 
        //     $json = @file_get_contents($url); 
            
        //     $data = json_decode($json); 

        //     $status = $data->status; 

        //     $status1 = "OK";
        //     if(strtolower($status) == strtolower($status1)){        //Get address from json data 

        //         $location['address'] = $data->results[0]->formatted_address;    

        //     }else{ 

        //         $location['address'] =  array(); 

        //     } 

        //     // $punch_address= punch_address($inputs['latitude'],$inputs['longitude']);
        //     $punch_address= $location['address'];






        // }else{
        //     $punch_address = '';
        // }

        $punch_location = array(
            'latitude' => $inputs['latitude'],
            'longitude' =>$inputs['longitude'],
            'location' =>$punch_address,
        );

        $all_days_attendance = unserialize($record['month_days_in_out']);

        $current_day = ($a_day - 1);

        $record_day = unserialize($record['month_days']);

             if($record_day[$current_day]['punch_in'] ==''){
                    $record_day[$current_day]['punch_in'] = $a_cin;
                    $record_day[$current_day]['punchin_location'] = $punch_location;
                    $record_day[$current_day]['day'] = 1;
                }

             if(!empty($record_day[$current_day])){
                $record_day[$current_day]['punch_out'] = $a_cin;
                $record_day[$current_day]['punchout_location'] = $punch_location;
                $record_day[$current_day]['day'] = 1;
            }




        $count_current_day = count($all_days_attendance[$current_day]);

        

        if($count_current_day == 1  && empty($all_days_attendance[$current_day][0]['punch_in']) ){

            $all_days_attendance[$current_day][0] = array(
                'day' => 1,
                'punch_in' => $a_cin,
                'punchin_location' => $punch_location,
                'punch_out'=>''
            );
        }elseif($count_current_day == 1  && empty($all_days_attendance[$current_day][0]['punch_out']) ){

            $all_days_attendance[$current_day][0]['punch_out'] = $a_cin;
            $all_days_attendance[$current_day][0]['punchout_location'] = $punch_location;
        }elseif($count_current_day >= 1) {

            $end_day = end($all_days_attendance[$current_day]);

            if(!empty($end_day['punch_out']))
            {
                $all_days_attendance[$current_day][$count_current_day] = array(
                    'day' => 1,
                    'punch_in' => $a_cin,
                    'punchin_location' => $punch_location,
                    'punch_out'=>''
                );
            }elseif(empty($all_days_attendance[$current_day][$count_current_day]['punch_out'])){
                $all_days_attendance[$current_day][$count_current_day - 1]['punch_out'] = $a_cin;   
                $all_days_attendance[$current_day][$count_current_day - 1]['punchout_location'] = $punch_location;             
            }
        }

        // print_r(serialize($all_days_attendance));
        // exit();

        $this->db->where($where);
        $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($all_days_attendance)));
       
           // print_r(end($all_days_attendance[$current_day])); exit;
        $last_punch = end($all_days_attendance[$current_day]);
        if($last_punch['punch_out'] == '')
        {
            $res = array(
                'punch_in' => $last_punch['punch_in'],
                'location' =>$punch_address,
            );
            $response['message'] = $user_name.' Punch-In Success at '.$last_punch['punch_in'];
            $response['status_code'] = 11;
        }else{
            $res = array(
                'punch_out' => $last_punch['punch_out'],
                'location' =>$punch_address,
            );
            $response['message'] = $user_name.' Punch-Out Success at '.$last_punch['punch_out'];
            $response['status_code'] = 12;
        }
        $response['data'] = $res;

        $this->response($response, REST_Controller::HTTP_OK);



   }
   
   
    public function androidlogin_post()
    {

       if($this->is_valid == TRUE)   {
          

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['username']) && !empty($inputs['password']) && !empty($inputs['device_id'])){
                
                 

                $result = $this->user->is_valid_login($inputs); 

                  if(!empty($result)){
                      
                    $check_device = $this->user->check_device($result['id']);
                        if($check_device != '')
                        {
                            $dev_res = $this->user->device_update($check_device['dev_id'],$inputs['device_id'],'update');
                        }else{
                            $dev_res = $this->user->device_update($result['id'],$inputs['device_id'],'insert');
                        }
                        
                        
                        $log_details = $this->db->get_where('log_type',array('user_id'=>$result['id']))->row_array();
                        //echo 123;exit;
                        if(!empty($log_details)){
                         $result['login_type'] = $log_details['log_type'];   
                        }else{
                            $result['login_type'] = '';
                        }
                        
                        $response['status_code'] = 1;
                        $response['message'] = 'SUCCESS';
                        
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid login credential';
                        $response['data'] = (object) $data;
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            //print_r($response);exit;
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }



}
?>
