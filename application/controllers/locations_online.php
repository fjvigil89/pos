<?php
require_once ("secure_area.php");
class Locations_online extends secure_area{

	function __construct()
	{
		parent::__construct();
		$this->load->model("Location_online");
	}
	
	function index($offset=0)
	{
		$data['shop_config']=  array();
		$data['controller_name'] = strtolower(get_class());
		
		foreach ($this->Location_online->get_all() as $key )
		{
		   $shop_config = array($key->key => $key->value);
		   $data = array_merge($data, $shop_config);
		}
		
		$this->load->view('locations_online/store',$data);
	}
	
	function save()
	{	
		
		$success_message = 'Su configuracion ha sido exitosa';
		$shop= $this->input->post('shop');
		$d = array(
		   'active_shop' =>$this->input->post('active_shop'), 
           'shop' =>$this->input->post('shop'), 
		   'shop_description' => $this->input->post('shop_description'),
		   'shop_redes_facebook' =>$this->input->post('shop_redes_facebook'),
		   'shop_redes_google' =>$this->input->post('shop_redes_google'),
		   'shop_redes_twitter' =>$this->input->post('shop_redes_twitter'),
		   'shop_theme' =>$this->input->post('shop_theme')
	     );
				   
	    $t=$this->Location_online->update($d);
		echo json_encode(array('success'=>$t,'message'=>$success_message));

	}


}



