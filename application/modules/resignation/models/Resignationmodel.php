<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resignationmodel extends CI_Model {

var $table = 'resignation r';
	var $column_order = array(null, 'employee','noticedate','resignationdate','reason'); //set column field database for datatable orderable
	var $column_search = array('employee','noticedate','resignationdate','reason'); //set column field database for datatable searchable 
	//var $order = array('id' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->select('r.*, a.fullname, d.deptname');
		$this->db->from($this->table);
		$this->db->join('account_details a', 'a.user_id = r.employee', 'left');
		$this->db->join('users u', 'u.id = r.employee', 'left');
		$this->db->join('departments d', 'd.deptid = u.department_id', 'left');
		
		// Custom filter for branch_id
		if ($this->tank_auth->get_role_id() != 1) {
			// $this->db->where('r.branch_id', $this->session->userdata('branch_id'));
			// Custom filter for user submitted own resignation
			$this->db->where('(r.employee = '.$this->session->userdata('user_id').' OR u.teamlead_id = '.$this->session->userdata('user_id').')');
		}

		// Custom filter for status
		$this->db->where('r.status', '1');
		$i = 0;
	
		foreach ($this->column_search as $product) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
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
		// $this->db->where('r.status',"1");
		// $this->db->from($this->table);
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('r.*,ad.fullname');
		$this->db->from($this->table);
		$this->db->join('dgt_account_details ad','ad.user_id=r.employee','join');
		$this->db->where('r.id',$id);
		$query = $this->db->get();

		$result= $query->row_array();
		
		
		$data['id']=$result['id'];
		$data['branch_id']=$result['branch_id'];
		$data['employee']=$result['employee'];
		$data['fullname'] = $result['fullname'];
		$data['noticedate']=date('m/d/Y',strtotime($result['noticedate']));
		$data['resignationdate']=date('m/d/Y',strtotime($result['resignationdate']));
		$data['reason']=$result['reason'];


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

	public function get_by_id_v2($id = ''){
		$data = array();

		$this->db->select("dr.*");
		$this->db->from("dgt_resignation as dr");
		if($id != '')
		{
			$this->db->where("id", $id);
		}
		$this->db->order_by("id","desc");
		$this->db->limit(1);

		$tmp_output = $this->db->get()->row_array();
		
		if(!empty($tmp_output))
		{
			$tmp_output['attachments'] = array();
			 
			$this->db->select("*");
			$this->db->from("dgt_resignation_attachment");
			$this->db->where("resignation_id", $tmp_output['id']);
			$this->db->where("status", '1');
			$tmp_output['attachments'] = $this->db->get()->result_array();


			$this->db->select('r.*,ad.fullname,ad.emp_code, ad.doj as date_of_joining, ad.personal_email, dgt_users.email as email');
			$this->db->from($this->table);
			$this->db->join('dgt_account_details ad','ad.user_id=r.employee','join');
			$this->db->join('dgt_users','dgt_users.id =r.employee','innerjoin');
			$this->db->where('r.id',$id);
			$query = $this->db->get();

			$result= $query->row_array();

			$data['id']=$result['id'];
			$data['branch_id']=$result['branch_id'];
			$data['employee']=$result['employee'];
			$data['emp_code']=$result['emp_code'];
			$data['fullname'] = $result['fullname'];
			$data['noticedate']=date('Y-m-d',strtotime($result['noticedate']));
			$data['resignationdate']=date('Y-m-d',strtotime($result['resignationdate']));
			$data['reason']=$result['reason'];
			$data['department_id']=$tmp_output['department_id'];
			$data['designation_id']=$tmp_output['designation_id'];
			$data['department_id']=$tmp_output['department_id'];
			$data['location']=$tmp_output['location'];
			$data['date_of_joining']=date('Y-m-d',strtotime($result['date_of_joining']));
			$data['exit_request_submit_date']=$result['exit_request_submit_date'];
			$data['requested_last_working_date']=$result['requested_last_working_date'];
			$data['notice_period_end_date']=$result['notice_period_end_date'];
			if($result['personal_email'] != "")
			{
				$data['personal_email'] = $result['personal_email'];
			}
			else{
				$data['personal_email'] = $result['email'];
			}

			$data['elaborate_reason']=$tmp_output['elaborate_reason'];
			$data['attachments'] =$tmp_output['attachments'];
			$data['resignation_status'] =$tmp_output['status'];

			$data['resignation_manger_data'] = array();

			$this->db->select("*");
			$this->db->from("resignation_manger_response");
			$this->db->where("resignation_id", $tmp_output['id']);
			$data['resignation_manger_data'] = $this->db->get()->result_array();

			$data['resignation_hr_data'] = array();

			$this->db->select("*");
			$this->db->from("resignation_hr_response");
			$this->db->where("resignation_id", $tmp_output['id']);
			$data['resignation_hr_data'] = $this->db->get()->result_array();
		}
		return $data;
	}


}