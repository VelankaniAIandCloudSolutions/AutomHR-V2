<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stripepay extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->model(array('Invoice','Client','App'));
	}


	function authenticate(){

	// Check for a form submission:
	if ($_POST) {

	// Stores errors:
	$errors = array();

	// Need a payment token:
	if (isset($_POST['stripeToken'])) {

		$token = $this->input->post('stripeToken',true);

		// Check for a duplicate submission, just in case:
		// Uses sessions, you could use a cookie instead.
		if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
			$errors['token'] = 'You have apparently resubmitted the form. Please do not do that.';
		} else { // New submission.
			$_SESSION['token'] = $token;
		}

	} else {
		$errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
	}


	// If no errors, process the order:
	if (empty($errors)) {

		// create the charge on Stripe's servers - this will charge the user's card
		try {

			// Include the Stripe library:
			require_once APPPATH.'/libraries/stripe/init.php';

			// set your secret key: remember to change this to your live secret key in production
			// see your keys here https://manage.stripe.com/account
			\Stripe\Stripe::setApiKey(config_item('stripe_private_key'));

			$invoice_id = $this->input->post('invoice',TRUE);
			$info = Invoice::view_by_id($invoice_id);
			$company = Client::view_by_id($info->client);

			$amount = intval($this->input->post('amount',TRUE) * 100);

			$metadata = array(
			                     'invoice_id' 	=> $invoice_id,
			                     'payer' 		=> User::displayName(User::get_id()),
			                     'payer_email' 	=> $company->company_email,
			                     'invoice_ref' 	=> $info->reference_no
			                     );

			// Charge the order:
			$charge = \Stripe\Charge::create(array(
				"amount"   		=> $amount, // amount in cents
				"currency" 		=> $info->currency,
				"card" 			=> $token,
				"metadata" 		=> $metadata,
				"description" 	=> "Payment for Invoice ".$info->reference_no
				)
			);

			// Check that it was paid:
			if ($charge->paid == true) {
				$metadata = $charge->metadata;
				$data = array(
				            'invoice' => $metadata->invoice_id,
				            'paid_by' => $info->client,
				            'currency' => strtoupper($charge->currency),
				            'payer_email' => $metadata->payer_email,
				            'payment_method' => '1',
				            'notes' => $charge->description.' by '.User::displayName(User::get_id()).' via Stripe',
				            'amount' => round($charge->amount/100,2),
				            'trans_id' => $charge->balance_transaction,
				            'month_paid' => date('m'),
							'year_paid' => date('Y'),
							'payment_date' => date('Y-m-d')
				        );
				// Store the order in the database.
				if ($payment_id = App::save_data('payments', $data)) {
                $cur_i = App::currencies(strtoupper($charge->currency));

                $received_amount = number_format($amount/100,2);

                $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice_id,
                'user' => $company->primary_contact,
                'activity' => 'activity_payment_of',
                'icon' => 'fa-usd',
                'value1' => $cur_i->symbol.''.$received_amount,
                'value2' => $info->reference_no
                );

            	App::Log($data);

            	$this->_send_payment_email($invoice_id,$received_amount); // Send email to client

            	if(config_item('notify_payment_received') == 'TRUE'){
            		$this->_notify_admin($invoice_id,$received_amount,$cur_i->code); // Send email to admin
            	}

   			$due = Invoice::get_invoice_due_amount($invoice_id);
			if($due <= 0){
				Invoice::update($invoice_id,array('status'=>'Paid'));
			}

            	// $this->session->set_flashdata('response_status', 'success'); 
				// $this->session->set_flashdata('message', 'Payment received and applied to Invoice '.$info->reference_no);
				$this->session->set_flashdata('tokbox_success', 'Payment received and applied to Invoice '.$info->reference_no);
				redirect('invoices/view/'.$invoice_id);

				}else{
				// $this->session->set_flashdata('response_status', 'success');
				// $this->session->set_flashdata('message', 'Payment not recorded in the database. Please contact the system Admin.');
				$this->session->set_flashdata('tokbox_success', 'Payment not recorded in the database. Please contact the system Admin.');
				redirect('invoices/view/'.$invoice_id);
				}


			} else { // Charge was not paid!
				// $this->session->set_flashdata('response_status', 'error');
				// $this->session->set_flashdata('message', 'Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.');
				$this->session->set_flashdata('tokbox_error', 'Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.');
				redirect('invoices/view/'.$invoice_id);
			}

		} catch (Stripe_CardError $e) {
		    // Card was declined.
			$e_json = $e->getJsonBody();
			$err = $e_json['error'];
			$errors['stripe'] = $err['message'];
		} catch (Stripe_ApiConnectionError $e) {
		    // Network problem, perhaps try again.
		} catch (Stripe_InvalidRequestError $e) {
		    // You screwed up in your programming. Shouldn't happen!
		} catch (Stripe_ApiError $e) {
		    // Stripe's servers are down!
		} catch (Stripe_CardError $e) {
		    // Something else that's not the customer's fault.
		}

		} // A user form submission error occurred, handled below.


	} // Form submission.

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

			$params['recipient'] = Client::view_by_id($info->client)->company_email;

			$params['subject'] = '['.config_item('company_name').'] '.$subject;
			$params['message'] = $message;
			$params['attached_file'] = '';

			modules::run('fomailer/send_email',$params);
	}


	function _notify_admin($invoice,$amount,$cur)
    {
    		$info = Invoice::view_by_id($invoice);

   			foreach (User::admin_list() as $key => $user) {
   				$data = array(
   								'email'		    => $user->email,
   								'invoice_ref'   => $info->reference_no,
   								'amount'		=> $amount,
   								'currency'		=> $cur,
   								'invoice_id'	=> $invoice,
   								'client'        => Client::view_by_id($info->client)->company_name
   							);

   				$email_msg = $this->load->view('new_payment',$data,TRUE);

   				$params = array(
   								'subject' 		=> '['.config_item('company_name').'] Payment Confirmation',
   								'recipient' 	=> $user->email,
   								'message'		=> $email_msg,
   								'attached_file'	=> ''
   								);

				modules::run('fomailer/send_email',$params);
   			}

    }

    function sub_authenticate()
    {
    		// Check for a form submission:
		if ($_POST) {

			$email = $this->input->post('stripeEmailsub');
			$user_name = $this->session->userdata('username');


		// Stores errors:
		$errors = array();

		// Need a payment token:
		if (isset($_POST['stripeTokensub'])) {

			$token = $this->input->post('stripeTokensub',true);

			// Check for a duplicate submission, just in case:
			// Uses sessions, you could use a cookie instead.
			if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
				$errors['token'] = 'You have apparently resubmitted the form. Please do not do that.';
			} else { // New submission.
				$_SESSION['token'] = $token;
			}

		} else {
			$errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
		}


		// If no errors, process the order:
		if (empty($errors)) {

			// create the charge on Stripe's servers - this will charge the user's card
			try {

				// Include the Stripe library:
				require_once APPPATH.'/libraries/stripe/init.php';

				// set your secret key: remember to change this to your live secret key in production
				// see your keys here https://manage.stripe.com/account
				\Stripe\Stripe::setApiKey(config_item('stripe_private_key'));

				// $invoice_id = $this->input->post('invoice',TRUE);
				// $info = Invoice::view_by_id($invoice_id);
				// $company = Client::view_by_id($info->client);

				// $amount = intval($this->input->post('amount',TRUE) * 100);
				$users_amount = intval($this->input->post('stripeamountsub',TRUE) * 100);
				// $currency = $this->input->post('currency',TRUE);

				$metadata = array(
				                     'user_name' 	=> $user_name,
				                     // 'payer' 		=> User::displayName(User::get_id()),
				                     'payer' 		=> $user_name,
				                     'payer_email' 	=> $email,
				                     'user_id' 	=> $this->session->userdata('user_id')
				                     );

				// Charge the order:
				$charge = \Stripe\Charge::create(array(
					"amount"   		=> $users_amount, // amount in cents
					"currency" 		=> 'usd',
					"card" 			=> $token,
					"metadata" 		=> $metadata,
					"description" 	=> "Payment for Users "
					)
				);
				// Check that it was paid:
				if ($charge->paid == true) {
					$metadata = $charge->metadata;
					$data = array(
					            'user_id' => $this->session->userdata('user_id'),
					            'paid_by' => $user_name,
					            'currency' => strtoupper($currency),
					            'allowed_users_count' => round($charge->amount/100,2),
					            'payer_email' => $metadata->payer_email,
					            'payment_method' => '1',
					            'notes' => $charge->description.' by '.$user_name.' via Stripe',
					            'amount' => round($charge->amount/100,2),
					            'trans_id' => $charge->balance_transaction,
					            'month_paid' => date('m'),
								'year_paid' => date('Y'),
								'payment_date' => date('Y-m-d')
					        );
					$this->db->insert('dgt_user_subscription',$data);
					$this->session->set_userdata('subscription','success');
					redirect('users/account');

				} else { // Charge was not paid!
					$this->session->set_userdata('subscription','error');
					redirect('users/account');
				}

			} catch (Stripe_CardError $e) {
			    // Card was declined.
				$e_json = $e->getJsonBody();
				$err = $e_json['error'];
				$errors['stripe'] = $err['message'];
				echo($e->getMessage());
			} catch (Stripe_ApiConnectionError $e) {
			    // Network problem, perhaps try again.
			    echo($e->getMessage());
			} catch (Stripe_InvalidRequestError $e) {
			    // You screwed up in your programming. Shouldn't happen!
			    echo($e->getMessage());
			} catch (Stripe_ApiError $e) {
			    // Stripe's servers are down!
			    echo($e->getMessage());
			} catch (Stripe_CardError $e) {
			    // Something else that's not the customer's fault.
			    echo($e->getMessage());
			}

			} // A user form submission error occurred, handled below.


		} // Form submission.
    }
}


// End of file
