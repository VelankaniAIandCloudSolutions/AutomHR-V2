<?php

// application/controllers/Backup.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->dbutil();
        $this->load->helper('file');
    }

    public function index() {
		
		echo 'fds';exit;
        // Backup the database
        $backup = $this->dbutil->backup();

        // Save the backup to a writable directory on your server
        $this->load->helper('download');
        write_file('path/to/backup/' . 'backup_' . date('Y-m-d') . '.zip', $backup);

        // Send the backup as an email attachment
        $this->send_email_with_attachment();
    }

    private function send_email_with_attachment() {
        $this->load->library('email');

        // Set up email configuration
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => '465',
            'smtp_user' => 'nithya.t@dreamguystech.com',
            'smtp_pass' => 'dreamguys',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );

        $this->email->initialize($config);

        // Set the email details
        $this->email->from('nithya.t@dreamguystech.com', 'Your Name');
        $this->email->to('parameshwaran.m@dreamguystech.com');
        $this->email->subject('Automhr DB Backup - ' . date('Y-m-d'));

        // Attach the backup file
        $this->email->attach('path/to/backup/' . 'backup_' . date('Y-m-d') . '.zip');

        // Send the email
        if ($this->email->send()) {
            // Delete the backup file after sending the email
            unlink('path/to/backup/' . 'backup_' . date('Y-m-d') . '.zip');
            echo 'Email sent with the database backup.';
        } else {
            echo 'Error sending email: ' . $this->email->print_debugger();
        }
    }
}