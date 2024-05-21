<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Braintree extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->model(array('Invoice','App','Client'));
	}

	function index()
	{
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', lang('paypal_canceled'));
		$this->session->set_flashdata('tokbox_error', lang('paypal_canceled'));
				redirect('clients');
	}

	function pay($invoice = NULL)
	{
		$info = Invoice::view_by_id($invoice);

		$invoice_due = Invoice::get_invoice_due_amount($invoice);
		if ($invoice_due <= 0) {  $invoice_due = 0.00;	}

		$data = array( 'item_name' 		=> $info->reference_no,
						'item_number' 	=> $invoice,
						'currency' 		=> $info->currency,
						'client'		=> $info->client,
						'amount' 		=> $invoice_due,
						'token'			=> $this->_gen_token()
						);

		$this->load->view('form',$data);
	}

	function process(){
		$invoice = $this->input->post('item_number',TRUE);
		$info = Invoice::view_by_id($invoice);
		$company = Client::view_by_id($info->client);

		require_once(APPPATH.'libraries/braintree/lib/Braintree.php');
		$braintree_env = (config_item('braintee_live') == 'TRUE') ? 'production' : 'sandbox';
		Braintree_Configuration::environment($braintree_env);
		Braintree_Configuration::merchantId(config_item('braintree_merchant_id'));
		Braintree_Configuration::publicKey(config_item('braintree_public_key'));
		Braintree_Configuration::privateKey(config_item('braintree_private_key'));

		$nonce = $this->input->post('payment_method_nonce');

		$result = Braintree_Transaction::sale([
					  'amount' => $this->input->post('amount'),
					  'orderId' => $info->reference_no,
					  'paymentMethodNonce' => $nonce,
					  'merchantAccountId' => $info->braintree_merchant_ac,
					  'customer' => [
							    'firstName' => '',
							    'lastName' => '',
							    'company' => $company->company_name,
							    'phone' => $company->company_phone,
							    'fax' => $company->company_fax,
							    'website' => $company->company_website,
							    'email' => $company->company_email
							  ],
					  'options' => [
					    'submitForSettlement' => True
  						]
					]);

		if($result->success){
		$tr = $result->transaction;
		$data = array(
						'invoice' => $invoice,
                        'paid_by' => $info->client,
                        'payer_email' => User::login_info(User::get_id())->email,
                        'payment_method' => '1',
                        'currency' => $tr->currencyIsoCode,
                        'amount' => $tr->amount,
                        'trans_id' => $tr->id,
                        'notes' => 'Paid by '.User::displayName(User::get_id()).' via Braintree | Invoice Currency:'.$info->currency,
                        'payment_date' => date('d-m-Y'),
                        'month_paid' => date('m'),
                        'year_paid' => date('Y'),
                       );

		// Store the order in the database.
				if ($payment_id = App::save_data('payments', $data)) {
                    $cur_i = App::currencies($tr->currencyIsoCode);

                // Log activity
				$data = array(
					'module' => 'invoices',
					'module_field_id' => $invoice,
					'user' => User::get_id(),
					'activity' => 'activity_payment_of',
					'icon' => 'fa-usd',
					'branch_id' => $this->session->userdata('branch_id'),
					'value1' => $cur_i->symbol.''.$tr->amount,
					'value2' => $info->reference_no
					);
				App::Log($data);

            	$this->_send_payment_email($invoice,$tr->amount); // Send email to client

            	if(config_item('notify_payment_received') == 'TRUE'){
            		// Send email to admin
            		$this->_notify_admin($invoice,$tr->amount,$cur_i->code);
            	}

            	$due = Invoice::get_invoice_due_amount($invoice);
				if($due <= 0){
					Invoice::update($invoice,array('status'=>'Paid'));
				}


            	// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message', 'Payment received and applied to Invoice '.$info->reference_no);
				$this->session->set_flashdata('tokbox_success', 'Payment received and applied to Invoice '.$info->reference_no);
				redirect('invoices/view/'.$info->inv_id);

				}else{
				// $this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('tokbox_success', 'Payment not recorded in the database. Please contact the system Admin.');
				// $this->session->set_flashdata('message', 'Payment not recorded in the database. Please contact the system Admin.');
				redirect('invoices/view/'.$info->inv_id);
					}

        }else if ($result->transaction) {
    		print_r("Error processing transaction:");
    		print_r("\n  code: " . $result->transaction->processorResponseCode);
    		print_r("\n  text: " . $result->transaction->processorResponseText);
		} else {
    		print_r("Validation errors: \n");
    		print_r($result->errors->deepAll());
		}

	}


function _send_payment_email($invoice_id,$paid_amount){

			$message = App::email_template('payment_email','template_body');
            $subject = App::email_template('payment_email','subject');
            $signature = App::email_template('email_signature','template_body');


            $info = Invoice::view_by_id($invoice_id);

            $cur = App::currencies($info->currency);

            $logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$invoice_ref = str_replace("{REF}",$info->reference_no,$logo);

			$invoice_currency = str_replace("{INVOICE_CURRENCY}",$cur->symbol,$invoice_ref);
			$amount = str_replace("{PAID_AMOUNT}",$paid_amount,$invoice_currency);
            $EmailSignature = str_replace("{SIGNATURE}",$signature,$amount);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

			$params = array(
				'recipient' => Client::view_by_id($info->client)->company_email,
				'subject'	=> '['.config_item('company_name').']'.$subject,
				'message'	=> $message,
				'attached_file' => ''
				);

			modules::run('fomailer/send_email',$params);
	}

function _notify_admin($invoice,$amount,$cur)
    {
    		$info = Invoice::view_by_id($invoice);

            foreach (User::admin_list() as $key => $user) {
                $data = array(
                                'email'         => $user->email,
                                'invoice_ref'   => $info->reference_no,
                                'amount'        => $amount,
                                'currency'      => $cur,
                                'invoice_id'    => $invoice,
                                'client'        => Client::view_by_id($info->client)->company_name
                            );

                $email_msg = $this->load->view('new_payment',$data,TRUE);

                $params = array(
                                'subject'       => '['.config_item('company_name').'] Payment Confirmation',
                                'recipient'     => $user->email,
                                'message'       => $email_msg,
                                'attached_file' => ''
                                );

                modules::run('fomailer/send_email',$params);
            }



    }

	function _gen_token(){
		$braintree_env = (config_item('braintee_live') == 'TRUE') ? 'production' : 'sandbox';
		require_once(APPPATH.'libraries/braintree/lib/Braintree.php');
		Braintree_Configuration::environment($braintree_env);
		Braintree_Configuration::merchantId(config_item('braintree_merchant_id'));
		Braintree_Configuration::publicKey(config_item('braintree_public_key'));
		Braintree_Configuration::privateKey(config_item('braintree_private_key'));
		return Braintree_ClientToken::generate();
	}
}


////end
