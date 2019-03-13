<?php
require_once ("secure_area.php");
class Offline extends Secure_area 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$data=array();
		/*$array_time= array(
			"0"=>"No actulizar",
			'30' =>"30 ".lang('minute'),
			'60' => "1 ".lang('hours'),
			'120' => "2 ".lang('hours'),
			'1440' => "24 ".lang('hours'),
		);			
		$data["array_time"]=$array_time;*/
		$this->load->view("offline/index",$data);
    
	}
	function save(){
		$batch_save_data=array();
		$password =$this->input->post('password');
		$employee_data=array();
		if($password!=""){
			$employee_data["password_offline"]	=md5($password);
		}
		if(/*$this->Appconfig->batch_save($batch_save_data) and */$this->Employee->update_offline($employee_data) )
		{
			echo json_encode(array('success'=>true,'message'=>lang('config_saved_successfully')));
		}else
		{
			echo json_encode(array('success'=>false,'message'=>lang('config_saved_unsuccessfully')));
		}

	}
}
?>