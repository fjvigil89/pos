<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends CI_Controller {
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
		$this->load->model('api/Product');
		$this->load->library('encrypt');
		$this->load->library('session');

    }

	public function index($id=null)
	{

        $encrypted_string=!empty($this->input->get('key'))?$this->input->get('key'):null;

		$secreto=base64_decode($encrypted_string);
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


		$productos = $this->Product->get_all($id, $limit=10000, $offset=0,$col='item_id',$order='desc');

		$get_info_tax['tax'] = $this->Product->get_info_tax();
		
		echo json_encode(array_merge($productos,$get_info_tax)); 
	}
	

	function imgview($file_id)
	{ 

        $encrypted_string=!empty($this->input->get('key'))?$this->input->get('key'):null;
		$secreto=base64_decode($encrypted_string);
		$this->session->set_userdata('db_name_api', $secreto);

		$file = $this->Product->get($file_id);
		//header("Content-type: ".get_mime_by_extension($file->file_name));
		$data["file_id"]=$file->file_id;
		$data["file_name"]=$file->file_name;
		$data["file_data"]=base64_encode($file->file_data);
		echo json_encode($data);
	}

	function imagen($file_id)
	{ 

        $encrypted_string=!empty($this->input->get('key'))?$this->input->get('key'):null;

		$secreto=base64_decode($encrypted_string);
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

		$file = $this->Product->getfile($file_id);
		header("Content-type: ".get_mime_by_extension($file->file_name));
		echo $file->file_data;
	}



}