<?php
class Secure_area extends CI_Controller 
{
	var $module_id;
	
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		parent::__construct();
        $c = 0;
         
        if( $c === 1 ){
            $a = md5( '02af3d9'.preg_replace( '/[^0-9.]+/', '', shell_exec('wmic DISKDRIVE GET SerialNumber 2>&1') ) );
            $b =  md5('02af3d9'.'325336573945038202020202020202020202020');
            if($a !== $b){die();}
        }
        
		$this->module_id = $module_id;	
		$this->load->model('Employee');
		$this->load->model('Location');
        $this->load->helper('array_helper');
		if(!$this->Employee->is_logged_in())
		{
			redirect('login');
		}
		
		if(!$this->Employee->has_module_permission($this->module_id,$this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
		
		//load up global data
		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$data['allowed_modules']=$this->Module->get_allowed_modules($logged_in_employee_info->person_id);
		$data['user_info']=$logged_in_employee_info;
		
		$locations_list=$this->Location->get_all();
		$authenticated_locations = $this->Employee->get_authenticated_location_ids($logged_in_employee_info->person_id);
		$locations = array();
		foreach($locations_list->result() as $row)
		{
			if(in_array($row->location_id, $authenticated_locations))
			{
				$locations[$row->location_id] =$row->name;
			}
		}
		
		$data['authenticated_locations'] = $locations;
            
		$this->load->vars($data);
	}
	
	function check_action_permission($action_id)
	{
		if (!$this->Employee->has_module_action_permission($this->module_id, $action_id, $this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
	}	
}
?>