<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fomailer extends MX_Controller {

		function __construct()
		{
			parent::__construct();
		}

	function send_email($params)
	{
		if(config_item('disable_emails') == 'FALSE'){

		// If postmark API is being used
		if(config_item('use_postmark') == 'TRUE'){
			$config = array('api_key' => config_item('postmark_api_key'));
        	$this->load->library('postmark',$config);
        	
        	$this->postmark->from(config_item('postmark_from_address'), config_item('company_name'));
        	
        	if (config_item('use_alternate_emails') == 'TRUE' && isset($params['alt_email'])) {
                        $alt = $params['alt_email'];
                            if (config_item($alt.'_email') != '') {
                                $this->postmark->from(config_item($alt.'_email'), config_item($alt.'_email_name'));
                            }
                }

			$this->postmark->to($params['recipient']);
			$this->postmark->subject($params['subject']);
			$this->postmark->message_plain($params['message']);
			$this->postmark->message_html($params['message']);

			// Check attached file
			if(isset($params['attachment_url'])){ 
					$this->postmark->attach($params['attached_file']);
				}
        	return $this->postmark->send();

    	}else{

    		// If using SMTP

			if (config_item('protocol') == 'smtp') {

				// $this->load->library('encrypt');
				// $raw_smtp_pass =  $this->encrypt->decode(config_item('smtp_pass'));
				
				$raw_smtp_pass = config_item('smtp_pass');
				
				$config = array(
                        'smtp_host' => config_item('smtp_host'),
                        'smtp_port' => config_item('smtp_port'),
                        'smtp_user' => config_item('smtp_user'),
                        'smtp_pass' => $raw_smtp_pass,
                        'crlf'      => "\r\n",
                        'protocol'  => config_item('protocol'),
                        'mailtype'  => 'html',
                        'charset'   => 'utf-8',
                        'wordwrap'  => TRUE,
                        'newline'   => "\r\n",
                        'smtp_crypto' => 'ssl',
                    );						
			}	

			$this->load->library('email');
			// Send email 
			$config['mailtype'] = "html";
			$config['newline'] = "\r\n";
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$this->email->initialize($config);
			$this->email->from(config_item('company_email'), config_item('company_name'));	
			if (config_item('use_alternate_emails') == 'TRUE' && isset($params['alt_email'])) {
					$alt = $params['alt_email'];
						if (config_item($alt.'_email') != '') {
							$this->email->from(config_item($alt.'_email'), config_item($alt.'_email_name'));
						}
			}	
			$this->email->to($params['recipient']);
			
			if (isset($params['cc'])) {
				$this->email->cc($params['cc']);
			}
			$this->email->subject($params['subject']);
			$this->email->message($params['message']);
			// check attachments
				if($params['attached_file'] != ''){ 
					$this->email->attach($params['attached_file']);
				}
			// Queue emails

			$email_error = array();
			$delivery_status = '1';
			$email_sent = $this->email->send();
			
			if ($email_sent != '1'){	
				$email_error = $this->email->print_debugger();
				$delivery_status = 0;
				$email_log = array();
				$email_log = array(
					"sent_to"	=>	$params['recipient'],
					"sent_from"	=>	config_item('company_email'),
					"subject"	=>	$params['subject'],
					"message"	=>	$params['message'],
					"delivered"	=>	$delivery_status,
					"email_response"=> $email_error
				);
				$this->db->insert("dgt_outgoing_emails", $email_log);
			}
			// if(!$this->email->send()){
			// 	$this->send_later($params['recipient'],config_item('company_email'),$params['subject'],$params['message']);
			// }

    	}

    }else{
    	// Emails disabled
    	return TRUE;
    }
	
	}

	/**
	 * send_later
	 *
	 * Log unsent emails to be completed via CRON
	 *
	 * @access	private
	 * @param	email params
	 * @return	boolean	
	 */
	 
		private function send_later($to,$from,$subject,$message)
		{
			if(is_array($to)){
				$to = explode(',', $to);
			}
			$emails = array(
							'sent_to' 		=> $to,
							'sent_from' 	=> $from,
							'subject'		=> $subject,
							'message'		=> $message
							);
			$this->db->insert('outgoing_emails',$emails);
			return TRUE;
		}
}

/* End of file fomailer.php */