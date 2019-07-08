<?php
require_once ("person_controller.php");
class Orders extends Person_controller
{
	function __construct()
	{
		parent::__construct('orders');
		$this->load->model('Order');
		$this->load->library('sale_lib');

	}
	
	
	function index($offset=0)
	{
		$params = $this->session->userdata('orders_search_data') ? $this->session->userdata('orders_search_data') : array('offset' => 0, 'order_col' => 'order_id', 'order_dir' => 'asc', 'search' => FALSE);



		if ($offset!=$params['offset'])
		{
		   redirect('orders/index/'.$params['offset']);
		}
		$this->check_action_permission('search');
		$config['base_url'] = site_url('orders/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Order->search_count_all($data['search']);
			$table_data = $this->Order->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Order->count_all();
			$table_data = $this->Order->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		
		$data['manage_table']=get_orders_manage_table($table_data,$this);
		$data['total_rows'] = $config['total_rows'];
		$this->load->view('orders/manage',$data);
	}

	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$orders_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("orders_search_data",$orders_search_data);
		
		if ($search)
		{
			$config['total_rows'] = $this->Order->search_count_all($search);
			$table_data = $this->Order->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Order->count_all();
			$table_data = $this->Order->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('orders/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_orders_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
		
	}
	
	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$orders_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("orders_search_data",$orders_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Order->search($search,$per_page,$offset, $order_col ,$order_dir);
		$config['base_url'] = site_url('orders/search');
		$config['total_rows'] = $this->Order->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $this->Order->search_count_all($search);
		$data['manage_table']=get_orders_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Order->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the customer edit form
	*/
	function view($customer_id=-1,$redirect_code=0)
	{

	}



	function view_modal($customer_id)
	{
		$data['customer_info'] = $this->Order->get_info($customer_id);;
		$this->load->view("customers/customer_modal", $data);
	}
	

	function clear_state()
	{
		$this->session->unset_userdata('orders_search_data');
		redirect('orders');
	}
	/*
	Inserts/updates a customer
	*/
	function save($customer_id=-1)
	{

	}
	
	/*
	This deletes customers from the customers table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$customers_to_delete=$this->input->post('ids');
		
		if($this->Customer->delete_list($customers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('customers_successful_deleted').' '.
			count($customers_to_delete).' '.lang('customers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('customers_cannot_be_deleted')));
		}
	}
	

		
	function pay_now($customer_id)
	{
		$this->load->library('sale_lib');
    	$this->sale_lib->clear_all();
		$this->sale_lib->set_customer($customer_id);
		$this->sale_lib->set_mode('store_account_payment');
		$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
		$this->sale_lib->add_item($store_account_payment_item_id,1);
		redirect('sales');
	}

	public function orders_invoices($orders_id)
	{	
		$data['controller_name']="items";
		$orders_invoices = $this->Order->get_invoices_orders($orders_id)->result_array();
		$data['orders_invoices'] = $orders_invoices;

		$this->load->view('orders/orders_invoice', $data);
		echo json_encode(array('success'=>true,'message'=>lang('customers_cleanup_sucessful')));
	}

	public function orders_sales($orders_id)
	{	
			$data['controller_name']="items";
			$this->db->trans_start();
			$orders_invoices = $this->Order->get_invoices_orders($orders_id)->result_array();
			$this->sale_lib->clear_all();
			$this->sale_lib->set_order($orders_id);
            foreach(unserialize($orders_invoices[0]["products"]) as $product_id => $cantidad) {
               $data=$this->Item->get_info($product_id);
               $this->sale_lib->add_item($data->item_id,$cantidad,0,null);
            }
            $this->db->trans_complete();
            redirect('sales','refresh');
    }


}
?>