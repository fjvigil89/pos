<?php
class Employee extends Person
{
	/*
	Determines if a given person_id is an employee
	*/
	function exists($person_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
		
	function employee_username_exists($username)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.username',$username);
		$query = $this->db->get();
		
		
		if($query->num_rows()==1)
		{
			return $query->row()->username;
		}
	}	
	
	/*
	Returns all the employees
	*/
	function get_all($limit=10000, $offset=0,$col='last_name',$order='asc')
	{	
		$employees=$this->db->dbprefix('employees');
		$people=$this->db->dbprefix('people');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$employees." ON 										                       
						".$people.".person_id = ".$employees.".person_id
						WHERE deleted =0 ORDER BY ".$col." ". $order." 
						LIMIT  ".$offset.",".$limit);		
						
		return $data;
	}
	
	function count_all()
	{
		$this->db->from('employees');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular employee
	*/
	function get_info($employee_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id',$employee_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from employee table
			$fields = $this->db->list_fields('employees');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple employees
	*/
	function get_multiple_info($employee_ids)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');		
		$this->db->where_in('employees.person_id',$employee_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	function get_multiple_info_all($limit=10000,$start=0)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');	
			$this->db->where_in('employees.deleted',0);
		//$this->db->where_in('employees.person_id',$employee_ids);
		  $this->db->limit($limit, $start);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();
	}
	function update_offline($employee_data,$employee_id=false){
		if(!$employee_id){
			$employee_id=$this->get_logged_in_employee_info()->person_id;
		}
		if(!empty($employee_data)){
			$this->db->where('person_id', $employee_id);
			$success  = $this->db->update('employees',$employee_data);
			return $success;
		}	
		return true;

	}
	
	/*
	Inserts or updates an employee
	*/
	function save(&$person_data, &$employee_data,&$permission_data, &$permission_action_data, &$location_data, $employee_id=false, $cajas=array())
	{
		$success=false;
        $login_db = $this->load->database('login',true);
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		$login_db->trans_begin();//Inniciar transaction de la base datos login
        	
		if(parent::save($person_data,$employee_id))
		{
			if (!$employee_id or !$this->exists($employee_id))
			{
				$employee_data['person_id']      = $employee_id = $person_data['person_id'];
				$success                         = $this->db->insert('employees',$employee_data);
                $employee_data['store']          = $this->db->database;
				$employee_data['employee_email'] = $person_data['email'];
				unset($employee_data["account_balance"]);
				unset($employee_data["type"]);
				unset($employee_data['id_rate']); 
				
				$_success                        = $login_db->insert('employees',$employee_data);
			}
			else
			{
				//if store is demo no change password
					$employee_info= $this->get_info($employee_id);
					$employee_data["account_balance"]=$employee_info->account_balance;
					$this->db->where('person_id', $employee_id);
			  
					$login_db->where('person_id', $employee_id);
					$login_db->where('store', $this->db->database);
					
					$success  = $this->db->update('employees',$employee_data);	
					unset($employee_data["account_balance"]);
					unset($employee_data["type"]);
					unset($employee_data['id_rate']);
					$employee_data['employee_email'] = $person_data['email'];	
					$_success = $login_db->update('employees',$employee_data);
				
			}
			
			//We have either inserted or updated a new employee, now lets set permissions. 
			if($success and $_success)
			{


				$success=$this->Cajas_empleados->eleiminar_por_id_empleado($employee_id);
				  
				 if($success)
				{
					foreach($cajas as $id_caja)
					{
						$success =$this->Cajas_empleados->guardar(
							array(
								'person_id'=>$employee_id,
								'register_id'=>$id_caja)
							);
					}
				}
				//First lets clear out any permissions the employee currently has.
				$success=$this->db->delete('permissions', array('person_id' => $employee_id));

				
				//Now insert the new permissions
				if($success)
				{
					foreach($permission_data as $allowed_module)
					{
						$success = $this->db->insert('permissions',
						array(
						'module_id'=>$allowed_module,
						'person_id'=>$employee_id));
					}
				}
				
				//First lets clear out any permissions actions the employee currently has.
				$success=$this->db->delete('permissions_actions', array('person_id' => $employee_id));
				
				//Now insert the new permissions actions
				if($success)
				{
					foreach($permission_action_data as $permission_action)
					{
						list($module, $action) = explode('|', $permission_action);
						$success = $this->db->insert('permissions_actions',
						array(
						'module_id'=>$module,
						'action_id'=>$action,
						'person_id'=>$employee_id));
					}
				}
				
				$success=$this->db->delete('employees_locations', array('employee_id' => $employee_id));
				
				//Now insert the new employee locations
				if($success)
				{
					if ($location_data !== FALSE)
					{
						foreach($location_data as $location_id)
						{
							$success = $this->db->insert('employees_locations',
							array(
							'employee_id'=>$employee_id,
							'location_id'=>$location_id
							));
						}				
					}
				}				
			}			
		}
		
		$this->db->trans_complete();		
		
		if ($success) {
		
			$login_db->trans_commit();
		
		} else {

			$login_db->trans_rollback();
		}

		return $success;
	}
	
	/*
	Deletes one employee
	*/
	function delete($employee_id)
	{
		$success=false;
		
		//Don't let employee delete their self
		if($employee_id==$this->get_logged_in_employee_info()->person_id)
			return false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$employee_info = $this->Employee->get_info($employee_id);
	
		if ($employee_info->image_id !== NULL)
		{
			$this->Person->update_image(NULL,$employee_id);
			$this->Appfile->delete($employee_info->image_id);			
		}			
		
		//Delete permissions
		if($this->db->delete('permissions', array('person_id' => $employee_id)) && $this->db->delete('permissions_actions', array('person_id' => $employee_id)))
		{	
			$this->db->where('person_id', $employee_id);
			$success = $this->db->update('employees', array('deleted' => 1));
		}
		$this->db->trans_complete();		
		return $success;
	}
	
	/*
	Deletes a list of employees
	*/
	function delete_list($employee_ids)
	{
		$success    = false;
        $login_db   = $this->load->database('login', TRUE);
        $store_name = $this->db->database;
		
		//Don't let employee delete their self
		if(in_array($this->get_logged_in_employee_info()->person_id,$employee_ids))
			return false;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		foreach($employee_ids as $employee_id)
		{
			$employee_info = $this->Employee->get_info($employee_id);
		
			if ($employee_info->image_id !== NULL)
			{
				$this->Person->update_image(NULL,$employee_id);
				$this->Appfile->delete($employee_info->image_id);			
			}			
		}
		
		$this->db->where_in('person_id',$employee_ids);
		//Delete permissions
		if ($this->db->delete('permissions'))
		{
			//delete from employee table
			$this->db->where_in('person_id',$employee_ids);
            $success = $this->db->update('employees', array('deleted' => 1));
            
			$login_db->where_in('person_id',$employee_ids);
			$login_db->where('store',$store_name);
			$success = $login_db->update('employees', array('deleted' => 1));
            
		}
        
		$this->db->trans_complete();		
		return $success;
 	}
	
		
	function check_duplicate($term)
	{
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$query = $this->db->where("CONCAT(first_name,' ',last_name) = ".$this->db->escape($term));
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
		
		
	}

	function check_duplicate_email($email, $person_id = -1)
	{
		$login_db = $this->load->database('login',true);
			
		$login_db->from('employees')
				 ->where('employee_email', $email);

		if ($person_id) {

			$query = $login_db->where("(store!='".$this->db->database."' OR person_id != '".$person_id."')", NULL, FALSE);
		}		 
		
		$query = $login_db->get();

		if ($query->num_rows() > 0) {
		
			return true;
		}

		return false;
	}

	/*
	Get search suggestions to find employees
	*/
	function get_search_suggestions($search,$limit=5)
	{
		$suggestions = array();
		
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		
		$this->db->limit($limit);	
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->last_name.', '.$row->first_name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		$this->db->where('deleted', 0);
		$this->db->like("email",$search);
		$this->db->limit($limit);
		$by_email = $this->db->get();
		$temp_suggestions = array();
		foreach($by_email->result() as $row)
		{
			$temp_suggestions[] = $row->email;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("username",$search);
		$this->db->limit($limit);
		$by_username = $this->db->get();
		foreach($by_username->result() as $row)
		{
			$suggestions[]=array('label'=> $row->username);		
		}


		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("phone_number",$search);
		$this->db->limit($limit);
		$by_phone = $this->db->get();
		$temp_suggestions = array();
		foreach($by_phone->result() as $row)
		{
			$temp_suggestions[]=$row->phone_number;		
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	
	
	/*
	Preform a search on employees
	*/
	function search($search, $limit=20,$offset=0,$column='last_name',$orderby='asc')
	{
			$this->db->from('employees');
			$this->db->join('people','employees.person_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			username LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}
	
	function search_count_all($search, $limit=10000)
	{
			$this->db->from('employees');
			$this->db->join('people','employees.person_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			username LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}
	
	/*
	Attempts to login employee and set session. Returns boolean based on outcome.
	*/
	function login($username, $password, $store)
	{
        $this->db->join('stores', 'employees.store =  stores.store_name');
		$query = $this->db->get_where('employees', array(
            'username' => $username,
            'password' => md5($password), 
            'store'    => $store, 
            'deleted'=>0
        ), 1);
        
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
            $this->session->set_userdata('person_id', $row->person_id);
    		$_SESSION['db_name'] = $row->store;
            $expire_date         = $row->expire_date;
            $license_type        = $row->license_type;
			$last_login          = date('Y-m-d H:i:s');
			$max_registers       = $row->max_registers;
			$suspended= $row->suspended;
			$profile_id=  $row->profile_id;	
			$license=  $row->license;			
			$resellers_id = $row->reseller_id;
			$expire_date_franquicia= "0000-00-00 00:00:00";
			$es_franquicia= $resellers_id==1 ? false :true;
			$query2 = $this->db->query("SELECT rl.* FROM phppos_reseller_licenses rl, 
			phppos_resellers r ,phppos_stores s WHERE rl.`reseller_id`=".$resellers_id." AND r.id=rl.reseller_id and ".
			" s.reseller_id=r.id and s.store_name='".$row->store."' and rl.license='".$row->license."'");
			if ($es_franquicia && $query2->num_rows() ==1){
				$row2 = $query2->row();
				$expire_date_franquicia=$row2->expire_date;
			}
            
			$this->update_config($expire_date, $license_type, $last_login, $max_registers,$license,
			$es_franquicia,	$expire_date_franquicia,$resellers_id,$suspended,$profile_id);
			
			return true;
		}
        
		return false;
        
	}
	
	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
    
	function update_config($expire_date, $license_type, $last_login, $max_registers,$license=0,
	$es_franquicua=false,$expire_date_franquicia=null,$resellers_id=0,$suspended=false, $profile_id = 4)
    {
        $store_db = $this->load->database('default', TRUE);
        $batch_save_data = array(
			'expire_date' => $expire_date,
			'license_type' => $license_type,
			'last_login' => $last_login,
			'max_registers' => $max_registers,
			"es_franquicia"=>$es_franquicua,
			"expire_date_franquicia"=>$expire_date_franquicia,
			"resellers_id"=>$resellers_id,
			"license"=>$license,
			"suspended"=>$suspended,
			"profile_id" => $profile_id

		);
		
        
		
		/*
        if(!empty($language))
        {
            $batch_save_data['language'] = $language;
        
            switch ($language) 
            {
                case 'spanish_argentina':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 21;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_bolivia':
                    $batch_save_data['currency_symbol'] = 'Bs.';			
                    #$batch_save_data['default_tax_1_rate'] = 13;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_brasil':
                    $batch_save_data['currency_symbol'] = 'R$';			
                    #$batch_save_data['default_tax_1_rate'] = 5;
                    $batch_save_data['default_tax_1_name'] = 'ISS';
                    break;			    
                case 'spanish_chile':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 19;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 16;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish_costarica':
                    $batch_save_data['currency_symbol'] = '₡';			
                    #$batch_save_data['default_tax_1_rate'] = 13;
                    $batch_save_data['default_tax_1_name'] = 'IV';
                    break;
                case 'spanish_ecuador':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 12;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish_elsalvador':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 13;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_spain':
                    $batch_save_data['currency_symbol'] = '€';			
                    #$batch_save_data['default_tax_1_rate'] = 21;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'english':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = '';
                    $batch_save_data['default_tax_1_name'] = '';
                    break;	
                case 'spanish_guatemala':
                    $batch_save_data['currency_symbol'] = 'Q';			
                    #$batch_save_data['default_tax_1_rate'] = 12;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish_honduras':
                    $batch_save_data['currency_symbol'] = 'L';			
                    #$batch_save_data['default_tax_1_rate'] = 15;
                    $batch_save_data['default_tax_1_name'] = 'ISV';
                    break;
                case 'spanish_mexico':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 16;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_nicaragua':
                    $batch_save_data['currency_symbol'] = 'C$';			
                    #$batch_save_data['default_tax_1_rate'] = 15;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_panama':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 7;
                    $batch_save_data['default_tax_1_name'] = 'ITBMS';
                    break;
                case 'spanish_paraguay':
                    $batch_save_data['currency_symbol'] = '₲';			
                    #$batch_save_data['default_tax_1_rate'] = 10;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_peru':
                    $batch_save_data['currency_symbol'] = 'S/.';			
                    #$batch_save_data['default_tax_1_rate'] = 18;
                    $batch_save_data['default_tax_1_name'] = 'IGV';
                    break;
                case 'spanish_puertorico':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 7;
                    $batch_save_data['default_tax_1_name'] = 'IVU';
                    break;
                case 'spanish_republicadominicana':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 18;
                    $batch_save_data['default_tax_1_name'] = 'ITBIS';
                    break;
                case 'spanish_uruguay':
                    $batch_save_data['currency_symbol'] = '$';			
                    #$batch_save_data['default_tax_1_rate'] = 22;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_venezuela':
                    $batch_save_data['currency_symbol'] = 'Bs.F.';			
                    #$batch_save_data['default_tax_1_rate'] = 12;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
            }
        }
        
		*/
		
        $success = true;
    
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $store_db->trans_start();
            
            foreach($batch_save_data as $key=>$value)
            {
                $config_data=array(
                    'key'=>$key,
                    'value'=>$value
                );
                
                $store_db->where('key', $key);
                
                if( !$store_db->update('app_config',$config_data) )
                {
                    $success=false;
                    break;
                }
            }
            
        $store_db->trans_complete();
        
        return $success;
    
    }
	
	/*
	Determins if a employee is logged in
	*/
	function is_logged_in()
	{
		return $this->session->userdata('person_id')!=false;
	}
	/**
	 * Retorna el id del empleado logueado
	 */
	function person_id_logged_in()
	{
		return $this->session->userdata('person_id');
	}
	function get_all_location_employees()
	{	
	
		$this->db->select('employees_locations.*');		
		$this->db->from('employees_locations');
		$this->db->join('employees', 'employees.person_id = employees_locations.employee_id ');
		$this->db->where($this->db->dbprefix('employees').".deleted",0);
		return $this->db->get();
		
	}
	function get_all_by_indexd($date){
		$employees=$this->db->dbprefix('employees');
		$people=$this->db->dbprefix('people');
		$sql="SELECT ". $people=$this->db->dbprefix('people').
		".*,id,language,commission_percent,deleted,password_offline,username
		FROM ".$people."
						STRAIGHT_JOIN ".$employees." ON 										                       
						".$people.".person_id = ".$employees.".person_id ";
		
		if($date=="0000-00-00 00:00:00"){
			$sql.=" WHERE deleted =0 ";
		}else{
			$sql.=" WHERE update_employee >  ".$this->db->escape($date)." or update_people > ".$this->db->escape($date);
		}		
		$data=$this->db->query($sql);				
		return $data;
	}
	
	/*
	Gets information about the currently logged in employee.
	*/
	function get_logged_in_employee_info()
	{
		if($this->is_logged_in())
		{
			return $this->get_info($this->session->userdata('person_id'));
		}
		
		return false;
	}
	
	/*
	Gets the current employee's location. If they have more than 1, then a user can change during session
	*/
	function get_logged_in_employee_current_location_id()
	{
		if($this->is_logged_in())
		{
			//If we have a location in the session
			if ($this->session->userdata('employee_current_location_id')!==FALSE)
			{
				return $this->session->userdata('employee_current_location_id');
			}
			
			//Return the first location user is authenticated for
			return current($this->get_authenticated_location_ids($this->session->userdata('person_id')));
		}
		
		return FALSE;
	}
	
	function get_current_location_info()
	{
		return $this->Location->get_info($this->get_logged_in_employee_current_location_id());
	}
		
	function set_employee_current_location_id($location_id)
	{
		if ($this->is_location_authenticated($location_id))
		{
			$this->session->set_userdata('employee_current_location_id', $location_id);
		}
	}
	
	/*
	Gets the current employee's register id (if set)
	*/
	function get_logged_in_employee_current_register_id()
	{
		if($this->is_logged_in())
		{
			//If we have a register in the session
			if ($this->session->userdata('employee_current_register_id')!==FALSE)
			{
				return $this->session->userdata('employee_current_register_id');
			}
			
			return NULL;
		}
		
		return NULL;
	}
	
	function set_employee_current_register_id($register_id)
	{
		$this->session->set_userdata('employee_current_register_id', $register_id);
	}
	
	
	/*
	Determins whether the employee specified employee has access the specific module.
	*/
	function has_module_permission($module_id,$person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		$query = $this->db->get_where('permissions', array('person_id' => $person_id,'module_id'=>$module_id), 1);
		return $query->num_rows() == 1;
	}
	
	function has_module_action_permission($module_id, $action_id, $person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		$query = $this->db->get_where('permissions_actions', array('person_id' => $person_id,'module_id'=>$module_id,'action_id'=>$action_id), 1);
		return $query->num_rows() == 1;
	}
	
	function get_employee_by_username_or_email($username_or_email)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('username',$username_or_email);
		$this->db->or_where('email',$username_or_email);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		
		return false;
	}
	
	function update_employee_password($employee_id, $password)
	{
		$employee_data = array('password' => $password);
		$this->db->where('person_id', $employee_id);
		$success = $this->db->update('employees',$employee_data);
		
		return $success;
	}
		
	function cleanup()
	{
		$employee_data = array('username' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('employees',$employee_data);
	}
		
	function get_employee_id($username)
	{
		$query = $this->db->get_where('employees', array('username' => $username, 'deleted'=>0), 1);
		if ($query->num_rows() ==1)
		{
			$row=$query->row();
			return $row->person_id;
		}
		return false;
	}
	
	function get_authenticated_location_ids($employee_id, $db_name = false)
	{
        //si el usuario esta logueado se usa esta parte
        if($db_name == false)
        {
            $this->db->select('employees_locations.location_id');
    		$this->db->from('employees_locations');
    		$this->db->join('locations', 'locations.location_id = employees_locations.location_id');
    		$this->db->where('employee_id', $employee_id);
    		$this->db->where('deleted', 0);
    		$this->db->order_by('location_id', 'asc');
    		
    		$location_ids = array();
    		
    		foreach($this->db->get()->result_array() as $location)
    		{
    			$location_ids[] = $location['location_id'];
    		}
        }
        else
        {
            $config['hostname'] = 'localhost';
            $config['username'] = 'root';
            $config['password'] = '';
            $config['database'] = $db_name;
            $config['dbdriver'] = 'mysql';
            $config['dbprefix'] = 'phppos_';
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['cache_on'] = FALSE;
            $config['cachedir'] = '';
            $config['char_set'] = 'utf8';
            $config['dbcollat'] = 'utf8_general_ci';
    
            $shop_db = $this->load->database($config,true);
            
            $shop_db->select('employees_locations.location_id');
    		$shop_db->from('employees_locations');
    		$shop_db->join('locations', 'locations.location_id = employees_locations.location_id');
    		$shop_db->where('employee_id', $employee_id);
    		$shop_db->where('deleted', 0);
    		$shop_db->order_by('location_id', 'asc');
    		
    		$location_ids = array();
    		
    		foreach($shop_db->get()->result_array() as $location)
    		{
    			$location_ids[] = $location['location_id'];
    		}
        }
		
		return $location_ids;
	}
	
	function is_location_authenticated($location_id)
	{
		if ($employee = $this->get_logged_in_employee_info())
		{
			$this->db->select('location_id');
			$this->db->from('employees_locations');
			$this->db->where('employee_id', $employee->person_id);
			$this->db->where('location_id', $location_id);
			$result = $this->db->get();

			return $result->num_rows() == 1;
		}
		
		return FALSE;
	}
	
	function is_employee_authenticated($employee_id, $location_id)
	{
		static $authed_employees;
		
		if (!$authed_employees)
		{
			$this->db->select('employee_id');
			$this->db->from('employees_locations');
			$this->db->where('location_id', $location_id);
			$result = $this->db->get();
			$authed_employees = array();
			
			foreach($result->result_array() as $employee)
			{
				$authed_employees[$employee['employee_id']] = TRUE;
			}	
		}
		return isset($authed_employees[$employee_id]) && $authed_employees[$employee_id]; 
	}
	function get_rate($id_rate){
		$this->db->from('rates');
		$this->db->where('id_rate',$id_rate);
		$this->db->where('deleted',0);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{		
			$obj=new stdClass();
			$fields = $this->db->list_fields('rates');		
			foreach ($fields as $field)
			{
				$obj->$field='1';
			}
			
			return $obj;
		}
		
	
	}

    function initial_config($data = array())
    {
        $store_db = $this->load->database('default', TRUE);
        $batch_save_data = $data;

        $success = true;
    
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $store_db->trans_start();
            
            foreach($batch_save_data as $key=>$value)
            {
                $config_data=array(
                    'key'=>$key,
                    'value'=>$value
                );
                
                $store_db->where('key', $key);
                
                if( !$store_db->update('app_config',$config_data) )
                {
                    $success=false;
                    break;
                }
            }
            
        $store_db->trans_complete();
        
        return $success;
    
    }
    

	function tiene_caja($register_id,$person_id)
	{		
		$query = $this->Cajas_empleados-> get_caja($register_id,$person_id);
		return $query->num_rows() == 1;
	}
	function tiene_caja_en_tienda($person_id,$location_id)
	{		
		$query = $this->Cajas_empleados-> get_cajas_ubicacion_por_persona($person_id,$location_id);
		return $query->num_rows() >0;
	}
	function es_demo(){
		 $login_db = $this->load->database('login',true);
		 $db_name=$this->db->database;
		 $login_db->select('is_demo');
		 $login_db->from('stores');
		 $login_db->where('store_name', $db_name);
		 $query = $login_db->get();
		 if($query->num_rows()==1)
		{
			return (int)$query->row()->is_demo;
		}else{
			return 0;
		}
	}
	function get_store(){
		$db=$this->db->database;
		$store = explode("_", $db);
		return $store[1];
	}
	function update_rate($data, $employee_id=false){
		if($employee_id==false){
			$employee_id=$this->session->userdata('person_id');			
		}
		$this->db->where('person_id', $employee_id);	
		return $this->db->update('employees',$data);	

	}

}
?>
