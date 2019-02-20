<?php
require_once ("person_controller.php");
class Campaigns extends Secure_area 
{
	function __construct()
	{
		parent::__construct('Campaigns');
		$this->load->model('person');

	}
	
	
	function index($offset=0)
	{
		
	   $params = $this->session->userdata('customers_search_data') ? $this->session->userdata('customers_search_data') : array('offset' => 0, 'order_col' => 'last_name', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('customers/index/'.$params['offset']);
		}

		$config['base_url'] = site_url('campaigns/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Customer->search_count_all($data['search']);
			$table_data = $this->Customer->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Customer->count_all();
			$table_data = $this->Customer->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		$data['manage_table']=get_people_manage_table($table_data,'campaigns');
		$data['total_rows'] = $config['total_rows'];
		$this->load->view('campaigns/manage',$data);
	}
	
		function sorting()
	{
		
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$customers_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("customers_search_data",$customers_search_data);
		
		if ($search)
		{
			$config['total_rows'] = $this->Customer->search_count_all($search);
			$table_data = $this->Customer->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Customer->count_all();
			$table_data = $this->Customer->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('campaigns/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_data_rows($table_data,'campaigns');
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
		
	}
	
	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	function search()
	{

		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$customers_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("customers_search_data",$customers_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Customer->search($search,$per_page,$offset, $order_col ,$order_dir);
		$config['base_url'] = site_url('campaigns/search');
		$config['total_rows'] = $this->Customer->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $this->Customer->search_count_all($search);
		$data['manage_table']=get_people_manage_table_data_rows($search_data,'campaigns');
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Customer->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	public function send_tts()
	{

		$this->form_validation->set_rules('data3', 'Remitente del mensaje', 'required');
		$this->form_validation->set_rules('data4', 'Contacto', 'required');
		$this->form_validation->set_rules('data5', 'Mensaje', 'required');

		$this->form_validation->set_message('required', 'El campo %s es obligatorio');

		if($this->form_validation->run() == false)
		{
			$data = array(
					"sender_error" => form_error('data3'),
					"contact_error" => form_error('data4'),
					"message_error" => form_error('data5'),
					"result" => 'error'
					);
			echo json_encode($data);

		}
		else
		{
			//echo $data['user']->sip_password; exit;
			$number=$this->Customer->get_info($this->input->post('data4'));
				
			$this->curl->create('http://198.50.103.90/ttsmsn.php');
			$this->curl->option('buffersize', 10);
			$this->curl->option('useragent', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8 (.NET CLR 3.5.30729)');
			$this->curl->option('returntransfer', 1);
			$this->curl->option('followlocation', 1);
			$this->curl->option('HEADER', false);
			$this->curl->option('connecttimeout', 600);
			$this->curl->post(array(
								'data1' => $this->config->item('sip_account'),
								'data2' => $this->config->item('sip_password'),
								'data3' => $this->input->post('data3'),
								'data4' =>$number->first_name ,
								'data5' => $this->input->post('data5'),
								'data6' => $number->phone_number,
								'app'=> 'llamame'
				
							  ));			
			   $data = array(
                	"result" => 'success',
                	"message" => $this->curl->execute(),
                	"contact_info" => $number->first_name             	
            		);

            echo json_encode($data);
            exit;
					
		}
	}
	
	
	

}
?>