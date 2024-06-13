<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Department extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function department_list($entity_id = '', $department_id = '', $start = '', $limit = '')
    {
        $this->db->select("*");
        $this->db->from("dgt_departments");
        if ($entity_id != "") {
            $this->db->where("branch_id", $entity_id);
        }
        if ($department_id != "") {
            $this->db->where("deptid", $department_id);
        }
        $response = $this->db->get()->result_array();
        return $response;
    }

    public function designation_list($entity_id = '', $department_id = '', $user_id = '', $start = '', $limit = '')
    {
        $this->db->select("dd.id as designation_id, dd.*");
        $this->db->from("dgt_designation as dd");

         if ($user_id != '') {
                $this->db->join("dgt_users as du", "du.designation_id = dd.id", "left");
                $this->db->where("du.id", $user_id);
        }

        if ($department_id != "") {
            $this->db->where("dd.department_id", $department_id);
        }

        $response = $this->db->get()->result_array();
        return $response;
    }
}
