<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function module_color($param)
{
    switch ($param) {
        case 'tasks':
            return 'warning';
            break;
        case 'milestones':
            return 'primary';
            break;
        case 'files':
            return 'info';
            break;
        case 'bugs':
            return 'danger';
            break;
        case 'projects':
            return 'success';
            break;
        default:
            return 'default';
            break;
    }
}

function replace_email_tags($tag, $value, $string)
{
    return str_replace('{'.$tag.'}', $value, $string);
}

function create_email_logo()
{
    return '<img style="width:'.config_item('invoice_logo_width').'px" src="'.base_url().'/assets/images/logos/'.config_item('invoice_logo').'"/>';
}

function get_tags($template)
{
    switch ($template) {
        case 'activate_account':
            return array('INVOICE_LOGO', 'USERNAME', 'ACTIVATE_URL', 'ACTIVATION_PERIOD', 'EMAIL', 'PASSWORD', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'change_email':
            return array('INVOICE_LOGO', 'NEW_EMAIL', 'NEW_EMAIL_KEY_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'forgot_password':
            return array('INVOICE_LOGO', 'PASS_KEY_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'registration':
            return array('INVOICE_LOGO', 'USERNAME', 'SITE_URL', 'EMAIL', 'PASSWORD', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'reset_password':
            return array('INVOICE_LOGO', 'USERNAME', 'EMAIL', 'NEW_PASSWORD', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'bug_assigned':
           return array('INVOICE_LOGO', 'ISSUE_TITLE', 'ASSIGNED_BY', 'PROJECT_TITLE', 'SITE_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'bug_status':
            return array('INVOICE_LOGO', 'ISSUE_TITLE', 'STATUS', 'MARKED_BY', 'BUG_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'bug_comment':
            return array('INVOICE_LOGO', 'POSTED_BY', 'ISSUE_TITLE', 'COMMENT_URL', 'COMMENT_MESSAGE', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'bug_file':
            return array('INVOICE_LOGO', 'UPLOADED_BY', 'ISSUE_TITLE', 'BUG_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'bug_reported':
            return array('INVOICE_LOGO', 'ISSUE_TITLE', 'ADDED_BY', 'BUG_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'project_created':
            return array('INVOICE_LOGO', 'PROJECT_TITLE', 'CREATED_BY', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'project_assigned':
            return array('INVOICE_LOGO', 'PROJECT_TITLE', 'ASSIGNED_BY', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'project_comment':
            return array('INVOICE_LOGO', 'POSTED_BY', 'PROJECT_TITLE', 'COMMENT_URL', 'COMMENT_MESSAGE', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'project_complete':
            return array('INVOICE_LOGO', 'CLIENT_NAME', 'PROJECT_TITLE', 'PROJECT_CODE', 'PROJECT_URL', 'PROJECT_HOURS', 'PROJECT_COST', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'project_file':
            return array('INVOICE_LOGO', 'UPLOADED_BY', 'PROJECT_TITLE', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'project_updated':
            return array('INVOICE_LOGO', 'PROJECT_TITLE', 'ASSIGNED_BY', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'task_created':
            return array('INVOICE_LOGO', 'TASK_NAME', 'CREATED_BY', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'task_assigned':
            return array('INVOICE_LOGO', 'TASK_NAME', 'ASSIGNED_BY', 'PROJECT_TITLE', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'task_updated':
           return array('INVOICE_LOGO', 'TASK_NAME', 'PROJECT_TITLE', 'ASSIGNED_BY', 'PROJECT_URL', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'task_comment':
            return array('INVOICE_LOGO', 'POSTED_BY', 'TASK_NAME', 'COMMENT_URL', 'COMMENT_MESSAGE', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'invoice_message':
            return array('INVOICE_LOGO', 'REF', 'CLIENT', 'CURRENCY', 'AMOUNT', 'INVOICE_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'invoice_reminder':
            return array('INVOICE_LOGO', 'REF', 'CLIENT', 'CURRENCY', 'AMOUNT', 'INVOICE_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'payment_email':
           return array('INVOICE_LOGO', 'REF', 'INVOICE_CURRENCY', 'PAID_AMOUNT', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_client_email':
            return array('INVOICE_LOGO', 'CLIENT_EMAIL', 'SUBJECT', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_closed_email':
            return array('INVOICE_LOGO', 'REPORTER_EMAIL', 'SUBJECT', 'STAFF_USERNAME', 'TICKET_CODE', 'TICKET_STATUS', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_reply_email':
           return array('INVOICE_LOGO', 'SUBJECT', 'TICKET_CODE', 'TICKET_STATUS', 'TICKET_REPLY', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_staff_email':
            return array('INVOICE_LOGO', 'USER_EMAIL', 'SUBJECT', 'REPORTER_EMAIL', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'auto_close_ticket':
            return array('INVOICE_LOGO', 'REPORTER_EMAIL', 'SUBJECT', 'TICKET_CODE', 'TICKET_STATUS', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'ticket_reopened_email':
            return array('INVOICE_LOGO', 'RECIPIENT', 'SUBJECT', 'USER', 'TICKET_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'estimate_email':
            return array('INVOICE_LOGO', 'ESTIMATE_REF', 'CREATED_DATE', 'DUE_DATE', 'CLIENT', 'CURRENCY', 'AMOUNT', 'ESTIMATE_LINK', 'SITE_NAME', 'SIGNATURE');
            break;
        case 'message_received':
            return array('INVOICE_LOGO', 'RECIPIENT', 'SENDER', 'MESSAGE', 'SITE_URL', 'SITE_NAME', 'SIGNATURE');
            break;
		case 'attendance_regularization':
            return array('INVOICE_LOGO', 'REQUEST_BY', 'ATTENDANCE_DATE', 'CHECK_IN', 'CHECK_OUT', 'REASON', 'SIGNATURE');
            break;
		case 'attendance_regularization_approve':
            return array('INVOICE_LOGO', 'USER','APPROVE_REJECT', 'ATTENDANCE_DATE', 'CHECK_IN', 'CHECK_OUT', 'REASON', 'SIGNATURE','REJECT_REASON');
            break;
		case 'offer_send':
            // return array('INVOICE_LOGO','Job', 'USER','CANDIDATE_SIGNATURE','ADMIN_SIGNATURE','Link','SALARY');
            return array('ENTITY_LOGO', 
            'OFFER_LETTER_DATE',
            // 'CANDIDATE_TITLE',
            'CANDIDATE_NAME',
            'CANDIDATE_EMAIL',
            'CANDIDATE_CITY',
            'CANDIDATE_STATE',
            'CANDIDATE_COUNTRY',
            'CANDIDATE_PINCODE',
            'CANDIDATE_ADDRESS',
            'CANDIDATE_MOBILE',
            'JOB_ROLE',
            'COMPANY_NAME',
            'REPORTING_TO',
            'DATE_OF_JOINING',
            'CTC(IN_DIGIT)',
            'CTC(IN_WORDS)',
            'HR_MANAGER',
            'ENTITY_MD_GM',
            'BASIC_MONTHLY',
            'BASIC_ANNUAL',
            'MONTHLY_DA',
            'ANNUAL_DA',
            'MONTHLY_HRA',
            'ANNUAL_HRA',
            'MONTHLY_LTA',
            'ANNUAL_LTA',
            'MONTHLY_MEDICAL_ALLOWANCE',
            'ANNUAL_MEDICAL_ALLOWANCE',
            'MONTHLY_CONVEYANCE_ALLOWANCE',
            'ANNUAL_CONVEYANCE_ALLOWANCE',
            'MONTHLY_VARIABLE',
            'ANNUAL_VARIABLE',
            'EMPLOYER_PF_CONTRIBUTION_MONTHLY',
            'EMPLOYER_PF_CONTRIBUTION_ANNUAL',
            'EMPLOYEE_PF_CONTRIBUTION_MONTHLY',
            'EMPLOYEE_PF_CONTRIBUTION_ANNUAL',
            'ACCOMODATION_MONTHLY',
            'ACCOMODATION_ANNUAL',
            'CANDIDATE_LOGIN_URL',
            'ENTITY_NAME',
            'CANDIDATE_DOB',
            'CANDIDATE_GROSS_SALARY_MONTHLY',
            'CANDIDATE_GROSS_SALARY_ANNUAL',
            'CANDIDATE_CTC_MONTHLY',
            'CANDIDATE_CTC_ANNUAL'
        );
            break;
		case 'promotion_template':
            return array('INVOICE_LOGO','CURRENT_SALARY','USER', 'NEW_SALARY','EFFECTIVE_DATE');
            break;
		case 'termination_template':
            return array('INVOICE_LOGO','USER','TERMINATE_DATE','LAST_DAY','TERMINATE_REASON','TERMINATE_TYPE');
            break;
        case 'birthday':
            return array('INVOICE_LOGO','USER', 'SITE_NAME','BIRTHDAY_IMAGE');
            break;
        case 'anniversary':
            return array('INVOICE_LOGO','USER', 'SITE_NAME','ANNIVERSARY_IMAGE','TOTAL_YEARS');
            break;
        default:
            return array();
            break;
    }
}
function extract_tags($string, $start = '{', $end = '}')
{
    $matches = array();
    $regex = "/$start([a-zA-Z0-9_]*)$end/";
    preg_match_all($regex, $string, $matches);

    return $matches[1];
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function is_json($str) {
 return is_string($str) && is_array(json_decode($str, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
function datatable_script()
{
    return '<script src="https://code.jquery.com/jquery-3.5.1.js"></script><script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script><script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script><script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>';
}
function return_img($url)
{
    if (!empty($url) && file_exists($url)) {
        $return_url = $url;
   }
   else{
       $return_url = base_url().'assets/avatar/default_avatar.jpg';
   }
    return $return_url;
}
function sendFCM($message, $id) {

    $ci=& get_instance();
   
    $API_ACCESS_KEY = $ci->config->item('fcm_access_key');

    $url = $ci->config->item('fcm_url');

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
            'data' => array (
                    "message" => $message,
            ),                
            'priority' => 'high',
            'notification' => array(
                        'title' => $message['title'],
                        'body' => $message['body'],                            
            ),
    );
    $fields = json_encode ( $fields );
    
    $headers = array (
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
    ); 
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    $result = curl_exec ( $ch );
    curl_close ( $ch );
    return $result;
}
function config_get_item($item){
	$ci=& get_instance();
	$config = $ci->db->where('config_key',$item)->get('config')->row_array();
	return (!empty($config['value']))?$config['value']:'';
}


function getIndianCurrency($number = 0)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    
    if(config_item('currency_position') === 'before')
    {
        $Rupees = '<strong>'.config_item('default_currency').'</strong>'.' '.implode('', array_reverse($str));
    }
    else{
        $Rupees = implode('', array_reverse($str)). ' <strong>'.config_item('default_currency').'</strong>';
    }
    
    $paise ='';
    // $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees : '') . $paise;
}