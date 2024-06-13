<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->module('layouts');	
		User::logged_in();
		
		$this->load->library(array('template','form_validation'));
		$this->load->model(array('Estimate','App'));

		$this->applib->set_locale();
	}

	function add(){
		if ($this->input->post()) {

		$id = $this->input->post('estimate_id');
		$this->form_validation->set_rules('estimate_id', 'Estimate ID', 'required');
		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{	
				$_POST = '';
				// Applib::go_to('estimates/view/'.$id,'error',lang('error_in_form'));	
				$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
				redirect('estimates/view/'.$id);
		}else{	
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$_POST['item_tax_rate'] = $this->input->post('item_tax_rate');
			$_POST['item_tax_total'] = Applib::format_deci(($_POST['item_tax_rate'] / 100) *  $sub_total);
			$_POST['total_cost'] = Applib::format_deci($sub_total + $_POST['item_tax_total']);
			$_POST['branch_id'] = $this->session->userdata('branch_id');

			unset($_POST['tax']);
			
				if(App::save_data('estimate_items',$_POST)){
					// Applib::go_to('estimates/view/'.$id,'success',lang('item_added_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
					redirect('estimates/view/'.$id);
				}
			}
		}
	}

	function insert()
	{
		if ($this->input->post()) {
			$estimate = $this->input->post('estimate',TRUE);	

			$this->form_validation->set_rules('item', 'Item Name', 'required');

			if ($this->form_validation->run() == FALSE)
			{
					// Applib::go_to('estimates/view/'.$estimate,'error',lang('operation_failed'));
					$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
					redirect('estimates/view/'.$estimate);
			}else{	

			$item = $this->input->post('item',TRUE);
			$quantity = $this->input->post('quantity',TRUE);

			$saved_item = App::get_by_where('items_saved',array('item_id'=>$item,'branch_id' => $this->session->userdata('branch_id')));
            $items = App::get_by_where('estimate_items',array('estimate_id' => $estimate,'branch_id' => $this->session->userdata('branch_id')));

			foreach ($saved_item as $key => $i) {
				$item_name = $i->item_name;
				$item_desc = $i->item_desc;
				$unit_cost = $i->unit_cost;
				$total_cost = Applib::format_deci($quantity * $i->unit_cost);
			}

			$form_data = array(
			                'estimate_id' => $estimate,
			                'item_name'  => $item_name,
			                'item_desc' => $item_desc,
			                'unit_cost' => $unit_cost,
			                'quantity'  => $quantity,
			                'branch_id' => $this->session->userdata('branch_id'),
			                'total_cost' => $total_cost,
                           	'item_order' => count($items) + 1
			            );
			if(App::save_data('estimate_items',$form_data)){
					// Applib::go_to('estimates/view/'.$estimate,'success',lang('item_added_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
					redirect('estimates/view/'.$estimate);
				}
			}
		}else{
			$data['estimate'] = $this->uri->segment(4);
			$data['saved_items'] = App::get_by_where('items_saved',array('item_id !=' => 0,'branch_id'=>$this->session->userdata('branch_id')));
			$this->load->view('modal/insert_item',$data);
		}
	}

	function edit(){

		if ($this->input->post()) {

		$estimate_id = Estimate::view_item($this->input->post('item_id',TRUE))->estimate_id;

		$this->form_validation->set_rules('estimate_id', 'Estimate ID', 'required');
		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{	
				$_POST = '';
				// Applib::go_to('estimates/view/'.$estimate_id,'error',lang('error_in_form'));	
				$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
				redirect('estimates/view/'.$estimate_id);
		}else{	

			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$_POST['item_tax_rate'] = $this->input->post('item_tax_rate');
			$_POST['item_tax_total'] = ($_POST['item_tax_rate'] / 100) *  $sub_total;
			$_POST['total_cost'] = Applib::format_deci($sub_total + $_POST['item_tax_total']);

			if(App::update('estimate_items', array('item_id' => $_POST['item_id']),$this->input->post())){
					// Applib::go_to('estimates/view/'.$estimate_id,'success',lang('item_added_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
					redirect('estimates/view/'.$estimate_id);
			}
		}

		}else{
			$item = $this->uri->segment(4);
			$data['id'] = $item;
			$this->load->view('modal/edit_item',$data);
		}
	}


	function delete(){
		if ($this->input->post() ){
					$item_id = $this->input->post('item', TRUE);
					$estimate = $this->input->post('estimate', TRUE);
					if(App::delete('estimate_items',array('item_id' => $item_id))){
						// Applib::go_to('estimates/view/'.$estimate,'success',lang('item_deleted_successfully'));
						$this->session->set_flashdata('tokbox_success', lang('item_deleted_successfully'));
						redirect('estimates/view/'.$estimate);
					}
		}else{
			$data['item_id'] = $this->uri->segment(4);
			$data['estimate'] = $this->uri->segment(5);
			$this->load->view('modal/delete_item',$data);
		}
	}

	function reorder(){
		if ($this->input->post() ){
                        $items = $this->input->post('json', TRUE);
                        $items = json_decode($items);
                        foreach ($items[0] as $ix => $item) {
                            App::update('estimate_items', array('item_id' => $item->id),array("item_order"=>$ix+1));
                        }
                }
                $data['json'] = $items;
                $this->load->view('json',isset($data) ? $data : NULL);
	}

}

/* End of file invoices.php */