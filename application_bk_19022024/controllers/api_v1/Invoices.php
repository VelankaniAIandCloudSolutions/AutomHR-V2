<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Invoices extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model(array('Invoice','Client'));
    }

    public function index_get()
    {
        $invoices = array();
        $items = array();
        $list_invoices = Invoice::get_invoices();
        foreach ($list_invoices as $key => $inv) {
            $invoices[] = array(
                            'id'                => $inv->inv_id,
                            'ref'               => $inv->reference_no,
                            'client'            => Client::view_by_id($inv->client)->company_name,
                            'due_date'          => $inv->due_date,
                            'payment_methods'   => array(
                                                        'allow_paypal'          => $inv->allow_paypal,
                                                        'allow_stripe'          => $inv->allow_stripe,
                                                        'allow_2checkout'       => $inv->allow_2checkout,
                                                        'allow_braintree'       => array(  
                                                                    'active'    => $inv->allow_braintree,
                                                                    'account'   => $inv->braintree_merchant_ac
                                                                    ),
                                                        'allow_bitcon'          => $inv->allow_bitcoin
                                                        ),
                            'items'             =>  Invoice::has_items($inv->inv_id),
                            'tax'               => array(
                                                        'percent' => $inv->tax,
                                                        'amount'  => Invoice::get_invoice_tax($inv->inv_id)
                                                        ),
                            'extra_fee'         => array( 
                                                        'percent' => $inv->extra_fee,
                                                        'amount'  => Invoice::get_invoice_fee($inv->inv_id)
                                                        ),
                            'discount'          => array( 
                                                        'percent' => $inv->discount,
                                                        'amount'  => Invoice::get_invoice_discount($inv->inv_id)
                                                        ),
                            'sub_total'         => Invoice::get_invoice_subtotal($inv->inv_id),
                            'paid_amount'       => Invoice::get_invoice_paid($inv->inv_id),
                            'due_amount'        => Invoice::get_invoice_due_amount($inv->inv_id),
                            'recurring'         => array(
                                                        'status'            => $inv->recurring,
                                                        'recur_days'        => $inv->r_freq,
                                                        'start_date'        => $inv->recur_start_date,
                                                        'end_date'          => $inv->recur_end_date,
                                                        'recur_frequency'   => $inv->recur_frequency,
                                                        'next_recur'        => $inv->recur_next_date
                                                        ),
                            'currency'          => $inv->currency,
                            'status'            => Invoice::payment_status($inv->inv_id),
                            'archived'          => $inv->archived,
                            'emailed'           => array('status' => $inv->emailed,'date_sent' => $inv->date_sent),
                            'visible'           => $inv->show_client,
                            'created'           => $inv->date_saved,
                            'notes'             => $inv->notes

                    );
        }

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($invoices)
            {
                // Set the response and exit
                $this->response($invoices, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No invoices were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retreival.
        // Usually a model is to be used for this.

        $invoice = NULL;

        if (!empty($invoices))
        {
            foreach ($invoices as $key => $value)
            {
                $value['id'] = (int) $value['id'];
                if (isset($value['id']) && $value['id'] === $id)
                {
                    $invoice = $value;
                }
            }
        }

        if (!empty($invoice))
        {
            $this->set_response($invoice, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Invoice could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
