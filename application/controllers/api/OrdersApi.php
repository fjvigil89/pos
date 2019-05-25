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




 function save()
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

        $errors = [];
        $id_order=false;

        if (!isset($_POST['order_id']) || empty($_POST['order_id'])) {
            $errors[] = 'No order_id array or empty';
        }
        if (!isset($_POST['products']) || empty($_POST['products'])) {
            $errors[] = 'No products array or empty';
        }
        if (!isset($_POST['date']) || empty($_POST['date'])) {
            $errors[] = 'No date array or empty';
        }
        if (!isset($_POST['referrer']) || empty($_POST['referrer'])) {
            $errors[] = 'No referrer array or empty';
        }
        if (!isset($_POST['clean_referrer']) || empty($_POST['clean_referrer'])) {
            $errors[] = 'No clean_referrer array or empty';
        }
        if (!isset($_POST['payment_type']) || empty($_POST['payment_type'])) {
            $errors[] = 'No payment_type array or empty';
        }
        if (!isset($_POST['for_id']) || empty($_POST['for_id'])) {
            $errors[] = 'No for_id array or empty';
        }
        if (!isset($_POST['first_name']) || empty($_POST['first_name'])) {
            $errors[] = 'No first_name array or empty';
        } 
        if (!isset($_POST['last_name']) || empty($_POST['last_name'])) {
            $errors[] = 'No last_name array or empty';
        } 
        if (!isset($_POST['phone']) || empty($_POST['phone'])) {
            $errors[] = 'No phone array or empty';
        }
        if (!isset($_POST['address']) || empty($_POST['address'])) {
            $errors[] = 'No address array or empty';
        } 
        if (!isset($_POST['city']) || empty($_POST['city'])) {
            $errors[] = 'No city array or empty';
		} 
		
		var_dump($_POST);
                           
        if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'message' => $error
            ];
        } else {

          

            $id_order=$this->Order_api->set_orden($_POST);
            $message = [
                'message' => 'Registro exitoso',
                'id_order' => $id_order
            ];
        }

		echo json_encode($message); 

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