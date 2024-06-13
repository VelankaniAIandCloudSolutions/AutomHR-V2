<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/Slim/Slim.php';

class Notes extends MX_Controller
{
    public function __construct()
    {
        // Construct our parent class
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App'));
    }

    public function index($id = null)
    {
        $app = new Slim();
        $app->get('/notes', function () {
            $this->get_notes();
        });
        $app->get('/notes/:id', function ($id) {
            $this->get_note($id);
        });
        $app->post('/notes', function () {
            $this->add_note();
        });
        $app->put('/notes/:id', function ($id) {
            $this->update_note($id);
        });
        $app->delete('/notes/:id', function ($id) {
            $this->delete_note($id);
        });

        $app->run();
    }

    public function get_notes()
    {
        $owner = User::get_id();
        $notes = $this->db->where('owner', $owner)->get('notes')->result();
        echo json_encode($notes);
        exit;
    }

    public function get_note($id)
    {
        $note = $this->db->where('id', $id)->get('notes')->row();
        echo json_encode($note);
        exit;
    }

    public function add_note()
    {
        $user = User::get_id();
        $request = Slim::getInstance()->request();
        $note = json_decode($request->getBody());
        $data = array('name' => $note->name,
                      'description' => $note->description,
                      'date' => $note->date,
                      'owner' => $user,
                        );
        $this->db->insert('notes', $data);
        $note->id = $this->db->insert_id();
        echo json_encode($note);
        exit;
    }

    public function update_note($id)
    {
        $user = User::get_id();
        $request = Slim::getInstance()->request();
        $body = $request->getBody();
        $note = json_decode($body);
        $data = array('name' => $note->name,
                      'description' => $note->description,
                      'date' => $note->date,
                      'owner' => $user,
                        );
        $this->db->where('id', $id)->update('notes', $data);
        echo json_encode($note);
        exit;
    }

    public function delete_note($id)
    {
        $sql = "DELETE FROM dgt_notes WHERE id='$id'";
        $this->db->query($sql);
    }

    public function getConnection()
    {
        $dbhost = $this->db->hostname;
        $dbuser = $this->db->username;
        $dbpass = $this->db->password;
        $dbname = $this->db->database;
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    }
}
// End of notes API
