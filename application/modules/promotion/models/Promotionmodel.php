<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class promotionmodel extends CI_Model {

var $table = 'promotion p';
    var $column_order = array(null, 'employee','designation','grade','promotionto','promotiondate'); //set column field database for datatable orderable
    var $column_search = array('employee','designation','grade','promotionto','promotiondate'); //set column field database for datatable searchable 
    //var $order = array('id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {

        $this->db->select('p.*,a.fullname,d.deptname,d1.designation as promotion_from, d11.designation as promotion_to');
        $this->db->from($this->table);
        $this->db->join('account_details a', 'a.user_id = p.employee', 'left');
        $this->db->join('users u', 'u.id = p.employee', 'left');
        $this->db->join('departments d', 'd.deptid = u.department_id', 'left');
        $this->db->join('designation d1', 'd1.id = p.designation', 'left');
        $this->db->join('designation d11', 'd11.id = p.promotionto', 'left');
        if(!empty($this->session->userdata('user_id')) && $this->session->userdata('role_id') != 1){
            $this->db->where('p.employee',$this->session->userdata('user_id'));
        }
        //add custom filter here
       // $this->db->where('p.branch_id',$this->session->userdata('branch_id'));
        $this->db->where('p.status',0);
        $i = 0;
    
        foreach ($this->column_search as $product) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open brackep. query Where with OR clause better with brackep. because maybe can combine with other WHERE with AND.
                    $this->db->like($product, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($product, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {   
        $this->db->where('p.status',0);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();

        $result= $query->row_array();

        $data['id']=$result['id'];
        $data['employee']=$result['employee'];
        $data['department']=$result['department'];
        $data['designation']=$result['designation'];
        $data['grade']=$result['grade'];
        $data['emp_name']=$this->db->select('fullname')->where('user_id',$result['employee'])->get('account_details')->row()->fullname;
        $data['grade_name']=$this->db->select('grade_name')->where('grade_id',$result['grade'])->get('grades')->row()->grade_name;
        $data['promotionto']=$result['promotionto'];
        $data['promotiondate']=date('m/d/Y',strtotime($result['promotiondate']));
        $data['currentsalary']=$result['currentsalary'];
        $data['newsalary']=$result['newsalary'];
        $data['effectivedate']=date('m/d/Y',strtotime($result['effectivedate']));


        return $data;



    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    


}