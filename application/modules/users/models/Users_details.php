<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_details extends CI_Model
{
	
	function __construct(){
		parent::__construct();
	}
  var $column_order = array('U.username','U.role_id','U.created','AD.fullname','AD.company');
  var $column_search = array('U.username','U.role_id','U.created','AD.fullname','AD.company','LEFT');
    var $order = array('U.role_id ASC'); // default order
    var $account_details  = 'account_details AD';
    var $users  = 'users U';
    var $companies  = 'companies C';
    
    
    public function user_get_datatables_query()
    {

     $this->username = $_POST['username'];
     $this->company = $_POST['company'];
     $this->user_role = $_POST['user_role'];

     $this->db->select('U.*,AD.*');
     $this->db->from($this->users);
     $this->db->join($this->account_details,'AD.user_id=U.id',"LEFT");
        // $this->db->join($this->companies,'C.co_id=AD.company',"LEFT");
     $i = 0;

         foreach ($this->column_search as $item) // loop column
         {
                    if($_POST['search']['value']) // if datatable send POST for search
                    {

                            if($i===0) // first loop
                            {
                                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                                    $this->db->like($item, $_POST['search']['value']);
                                }
                                else
                                {
                                   $search_val = $_POST['search']['value'];
                                   $this->db->or_like($item, $search_val);
                               }
                            if(count($this->column_search) - 1 == $i) //last loop
                                    $this->db->group_end(); //close bracket
                                }

                                $i++;
                            }

                            if(!empty($this->username)){
                                $this->db->group_start();
                                // $this->db->like('AD.emp_code', $_POST['search']['value']);
                                $this->db->like('U.username', $this->username);
                                $this->db->group_end(); 
                            }
                            if(!empty($this->user_role)){
                                $this->db->where('U.user_type', $this->user_role);
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
        public function admin_client_users(){

            $this->user_get_datatables_query();	
            if($_POST['length'] != -1)
        // $this->db->where('U.role_id !=',3);
        // $this->db->where('AD.branch_id',$this->session->userdata('branch_id'));
                $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query->result_array();

        }
        public function admin_client_count_all(){

            // $this->db->where('U.role_id !=',4);
            // $this->db->where('U.role_id !=',3);
            $this->db->from($this->users);
            return $this->db->count_all_results();
        }
        public function admin_client_count_filtered(){
           $this->user_get_datatables_query();  
         //    $this->db->where('U.role_id !=',4);
         // $this->db->where('U.role_id !=',3);
           $query = $this->db->get();
           return $query->num_rows();;
       }

   }

/* End of file model.php */