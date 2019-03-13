<?php
require_once ("person_controller.php");
class Payments extends Secure_area 
{
	function __construct()
	{
		parent::__construct();
		
	}
	public function receipt($payments_id=false){
		$data=array();
		$petty_cash= $this->Sale->get_petty_cash_by_id($payments_id);
		$customer = $this->Customer->get_info($petty_cash->customer_id);
		$employee = $this->Employee->get_info($petty_cash->sold_by_employee_id);
		$data["petty_cash"]=$petty_cash;
		$data["customer"]=$customer;
		$data["employee"]=$employee;
		// se carga la vista receipt		
		 $this->load->view("payments/receipt",$data); 
	}

}
?>