<?php
class movement_balance extends CI_Model
{


	function __construct()
	{
		
	}
	function save_movement($amount, $description, $id_persona ,$type_movement=0, $category="",$suma_amount=true){
		$new_balance= $this->Employee->get_info($id_persona)->account_balance;
		if($suma_amount){
			$new_balance= $new_balance+$amount;
		}
		$data=array(
			"register_date"=>date('Y-m-d H:i:s'),
			"id_person"=>$id_persona,// empleado a quien se realiza el movimiento
			"amount"=>abs($amount),
			"new_balance"=>$new_balance,
			"type_movement"=>$type_movement,
			"description"=>$description,
			"registered_by"=>$this->session->userdata('person_id'),
			"category"=>$category,
			"location_id"=>$this->Employee->get_logged_in_employee_current_location_id()
		);
		 return $this->db->insert('movement_balance_employees', $data);
		

	}

	function save($amount, $description, $id_persona ,$type_movement=0 ,$category="")
	{         
		if (!$this->save_movement($amount, $description, $id_persona ,$type_movement,$category)) {		
			return false;
		}		
		if (!$this->update_balance_employee($amount, $id_persona)) {		
			return false;
		}
		return true;
	}
	function update_balance_employee($amount, $id_persona){
		$this->db->set('account_balance', 'account_balance+' . $amount, false);
		$this->db->where('person_id', $id_persona);
		$res=$this->db->update('employees');
		return $res ;
		
	}
	public function create_movement_balance_employees_temp_table($params)
	{
		set_time_limit(0);
		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = ' WHERE register_date BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.
			' and '.$this->db->dbprefix('movement_balance_employees').'.location_id='.$this->db->escape($location_id);
		}
		if (isset($params['id_employee']) && $params['id_employee']!="all")
		{
			$where .= ' and '.$this->db->dbprefix('movement_balance_employees').'.id_person='.$this->db->escape($params["id_employee"]);
		}	
		$sql=
			"CREATE TEMPORARY TABLE ".$this->db->dbprefix('movement_balance_employees_temp')."

		(SELECT ".$this->db->dbprefix('movement_balance_employees').".id_movement as id_movement ,".
		$this->db->dbprefix('movement_balance_employees').".id_person as id_person ,". 
		$this->db->dbprefix('movement_balance_employees').".amount as amount ,".
		$this->db->dbprefix('movement_balance_employees').".type_movement as type_movement, ".
		$this->db->dbprefix('movement_balance_employees').".registered_by as registered_by, ".
		$this->db->dbprefix('movement_balance_employees').".register_date as register_date, ".
		$this->db->dbprefix('movement_balance_employees').".description as description, ".
		$this->db->dbprefix('movement_balance_employees').".category as category, ".
		$this->db->dbprefix('movement_balance_employees').".location_id as location_id, ".
		$this->db->dbprefix('movement_balance_employees').".new_balance as new_balance, ".
		$this->db->dbprefix('people').".first_name as  first_name,".
		$this->db->dbprefix('people').".last_name as last_name ".
		
		"FROM ".$this->db->dbprefix('movement_balance_employees').
		" JOIN ".$this->db->dbprefix('employees')." ON  ".$this->db->dbprefix('employees').'.person_id='.$this->db->dbprefix('movement_balance_employees').'.id_person'.
		" JOIN ".$this->db->dbprefix('people')." ON  ".$this->db->dbprefix('people').'.person_id='.$this->db->dbprefix('employees').'.person_id'.

		$where." )";
		$this->db->query($sql);
	}

	
	
}

