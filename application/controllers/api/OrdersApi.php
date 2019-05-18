<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OrdersApi extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->model('Inventory');
		$this->load->model('Additional_item_numbers');
		$this->lang->load('items');
		$this->lang->load('module');
		$this->load->model('Item');
		$this->load->model('api/Order_api');
		$this->load->model('api/Product');
		$this->load->library('encrypt');
		$this->load->library('session');

    }

	public function index($id=null)
	{

        $encrypted_string=!empty($this->input->get('key'))?$this->input->get('key'):null;
		$secreto=base64_decode($encrypted_string);

		//var_dump($secreto); die();

		$this->session->set_userdata('db_name_api', $secreto);


		/*//renovarmos los config de la tienda------------------------------------------*/

		foreach($this->Product->get_all_confi()->result() as $app_config)
		{
			$this->config->set_item($app_config->key,$app_config->value);
		}

		/*//----------------------------------------------------------------------//*/

		if ($encrypted_string != $this->config->item('token_api')) {
			$data['error']="Tiene problema con su token de autenticacion";
			echo json_encode($data); die();
		}


		$productos = $this->Order_api->get_all($id, $limit=10000, $offset=0,$col='item_id',$order='desc')->result_array();

		
		echo json_encode($productos); 
	}




    public function save()
	{

        $encrypted_string=!empty($this->input->get('key'))?$this->input->get('key'):null;
		$secreto=base64_decode($encrypted_string);

		//var_dump($secreto); die();

		$this->session->set_userdata('db_name_api', $secreto);

		/*//renovarmos los config de la tienda------------------------------------------*/

		foreach($this->Product->get_all_confi()->result() as $app_config)
		{
			$this->config->set_item($app_config->key,$app_config->value);
		}

		/*//----------------------------------------------------------------------//*/
$save=false;

if (!empty($this->input->post('order_id'))) {

		$datos=array(
                    'order_id' => $this->input->post('order_id'),
                    'products' => $this->input->post('products'),
                    'date' => $this->input->post('date'),
                    'referrer' => $this->input->post('referrer'),
                    'clean_referrer' => $this->input->post('clean_referrer'),
                    'payment_type' =>  $this->input->post('payment_type'),
                    'paypal_status' => $this->input->post('paypal_status'),
                    'discount_code' => $this->input->post('discountCode'),
                    'user_id' => $this->input->post('user_id'),
                    'for_id' => $this->input->post('for_id'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'post_code' => $this->input->post('post_code'),
                    'notes' => $this->input->post('notes')
                 );



		$save = $this->Order_api->set_orden($datos);



}
	
		echo json_encode($save); 

	}


    public function orderstatus()
	{

        $encrypted_string=!empty($this->input->get('key'))?$this->input->get('key'):null;
		$secreto=base64_decode($encrypted_string);

		//var_dump($secreto); die();

		$this->session->set_userdata('db_name_api', $secreto);

		/*//renovarmos los config de la tienda------------------------------------------*/

		foreach($this->Product->get_all_confi()->result() as $app_config)
		{
			$this->config->set_item($app_config->key,$app_config->value);
		}

		/*//----------------------------------------------------------------------//*/


		$save=false;

		if (!empty($this->input->post('order_id'))) 
		{

				$datos=array(
		                    'order_id' => $this->input->post('order_id'),
		                    'processed' => $this->input->post('processed'),
		                    'viewed' => $this->input->post('viewed'),
		                 );


				$save = $this->Order_api->set_orden_update_status($datos);

		}
	
		echo json_encode($save); 

	}



	
}