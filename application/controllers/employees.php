<?php
require_once ("person_controller.php");
class Employees extends Person_controller
{
	function __construct()
	{
		parent::__construct('employees');
		$this->load->model('Cajas_empleados');
		$this->load->model('Change_house');
		$this->load->model('Hour_access');
	}
	
	function index($offset=0)
	{
		$params = $this->session->userdata('employee_search_data') ? $this->session->userdata('employee_search_data') : array('offset' => 0, 'order_col' => 'last_name', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('employees/index/'.$params['offset']);
		}
		$this->check_action_permission('search');
		$config['base_url'] = site_url('employees/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Employee->search_count_all($data['search']);
			$table_data = $this->Employee->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Employee->count_all();
			$table_data = $this->Employee->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		
		$data['total_rows'] = $config['total_rows'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		$data['manage_table']=get_people_manage_table($table_data,$this);
		$this->load->view('people/manage',$data);
	}
	
	
	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$employee_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("employee_search_data",$employee_search_data);
		if ($search)
		{
			$config['total_rows'] = $this->Employee->search_count_all($search);
			$table_data = $this->Employee->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Employee->count_all();
			$table_data = $this->Employee->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('employees/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	function clear_state()
	{
		$this->session->unset_userdata('employee_search_data');
		redirect('employees');
	}

	function check_duplicate()
	{	
		$email     = $this->input->post('email');
		$person_id = $this->input->post('person_id');
		echo json_encode(array(

			'duplicate'       => $this->Employee->check_duplicate($this->input->post('term')),
			'duplicate_email' => $this->Employee->check_duplicate_email($email, $person_id)

			));
	}
	/* added for excel expert */
	function excel_export() {
		$data = $this->Employee->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang('employees_username'),lang('common_first_name'),lang('common_last_name'),lang('common_email'),lang('common_phone_number'),lang('common_address_1'),lang('common_address_2'),lang('common_city'),	lang('common_state'),lang('common_zip'),lang('common_country'),lang('common_comments'));
		$rows[] = $row;
		foreach ($data as $r) {
			$row = array(
				$r->username,
				$r->first_name,
				$r->last_name,
				$r->email,
				$r->phone_number,
				$r->address_1,
				$r->address_2,
				$r->city,
				$r->state,
				$r->zip,
				$r->country,
				$r->comments
			);
			$rows[] = $row;
		}
		
		$content = array_to_spreadsheet($rows);
		force_download('employees_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}
	
	
	
	
	
	
	/*
	Returns employee table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$employee_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("employee_search_data",$employee_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Employee->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('employees/search');
		$config['total_rows'] = $this->Employee->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
		
	}
	
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Employee->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the employee edit form
	*/
	function view($employee_id=-1,$redirect_code=0)
	{
		$this->check_action_permission('add_update');
		$data['person_info']=$this->Employee->get_info($employee_id);
		$data['logged_in_employee_id'] = $this->Employee->get_logged_in_employee_info()->person_id;
		$data['all_modules']=$this->Module->get_all_modules();
		$data['controller_name']=strtolower(get_class());
		

		$locations_list=$this->Location->get_all()->result();
		$authenticated_locations = $this->Employee->get_authenticated_location_ids($employee_id);
		$logged_in_employee_authenticated_locations = $this->Employee->get_authenticated_location_ids($data['logged_in_employee_id']);
		$can_assign_all_locations = $this->Employee->has_module_action_permission('employees', 'assign_all_locations', $this->Employee->get_logged_in_employee_info()->person_id);		

		$locations = array();
		foreach($locations_list as $row)
		{
			$has_access = in_array($row->location_id, $authenticated_locations);
			$can_assign_access = $can_assign_all_locations || (in_array($row->location_id, $logged_in_employee_authenticated_locations));

			$locations[$row->location_id] = array('name' => $row->name, 'has_access' => $has_access, 'can_assign_access' => $can_assign_access);
		}
		$data["locations_list"]=$locations_list;
		$data["cajas"]=$this->Register-> get_all_store();

		//	$data['rates']=$this->Change_house->get_rate_all()->result_array();
		
		$rates=array();
		foreach ($this->Change_house->get_rate_all()->result() as $rate) {
			$rates[$rate->id_rate]=$rate->name;
		}
		$data['rates']=$rates;
		
		$data['locations']=$locations;
		$data['redirect_code']=$redirect_code;
		if($this->config->item('activar_control_access_employee')==1){
			$data['hour_access_employee']=$this->Hour_access->get_acceso_employee($employee_id);
			$data['hour_access_employee_table']=get_access_employee_table($data["locations_list"],$employee_id);

		}
				
		$this->load->view("employees/form",$data);
	}
	
	
	function exmployee_exists()
	{
		if($this->Employee->employee_username_exists($this->input->post('username')))
		echo 'false';
		else
		echo 'true';
		
	}
	/*
	Inserts/updates an employee
	*/
	function save($employee_id=-1)
	{
		$this->check_action_permission('add_update');
		$person_data = array(
			'first_name'=>$this->input->post('first_name'),
			'last_name'=>$this->input->post('last_name'),
			'email'=>$this->input->post('email'),
			'phone_number'=>$this->input->post('phone_number'),
			'address_1'=>$this->input->post('address_1'),
			'address_2'=>$this->input->post('address_2'),
			'city'=>$this->input->post('city'),
			'state'=>$this->input->post('state'),
			'zip'=>$this->input->post('zip'),
			'country'=>$this->input->post('country'),
			'comments'=>$this->input->post('comments')
		);
		
		$permission_data = $this->input->post("permissions")!=false ? $this->input->post("permissions"):array();
		$cajas = $this->input->post("permiso_cajas")!=false ? $this->input->post("permiso_cajas"):array();
		$permission_action_data = $this->input->post("permissions_actions")!=false ? $this->input->post("permissions_actions"):array();

		if($employee_id==1){
			// primero se elimina para evitar que se duplique la accion, ya que el usuario puede marcar o no.

			$permission_data = array_diff($permission_data, array('employees'));
			array_push($permission_data, 'employees');
			$permission_action_data = array_diff($permission_action_data, array('employees|add_update','employees|search'));
			array_push($permission_action_data, 'employees|search');
			array_push($permission_action_data, 'employees|add_update');
			
		}

		$location_data = $this->input->post('locations');
		$redirect_code=$this->input->post('redirect_code');
		//Password has been changed OR first time password set
		if($this->input->post('password')!='' )
		{			
			$employee_data=array(
				'username'=>$this->input->post('username'),
				'password'=>md5($this->input->post('password'))
			);		
		}

		foreach ($location_data as $key) {
			$hora_acceso[$key]=array(
				'lunes'=>$this->input->post($key.'_day_0'),
				'martes'=>$this->input->post($key.'_day_1'),
				'miercoles'=>$this->input->post($key.'_day_2'),
				'jueves'=>$this->input->post($key.'_day_3'),
				'viernes'=>$this->input->post($key.'_day_4'),
				'sabados'=>$this->input->post($key.'_day_5'),
				'domingos'=>$this->input->post($key.'_day_6')
			);
		}		

		//Password has been changed OR first time password set
		if($this->input->post('password')!='' && !$this->Employee->es_demo())
		{			
				$employee_data=array(
				'username'=>$this->input->post('username'),
				'password'=>md5($this->input->post('password'))
			);		
		}
		else //Password not changed
		{	
			$username      = trim($this->input->post('username')) == '' ? NULL : trim($this->input->post('username'));
			$employee_data = array('username' => $username);
		}

		if( $employee_id == 1 and $this->Employee->es_demo()){
			$employee_data=array(
				'username'=>$this->Employee->get_store(),
				'password'=>md5($this->Employee->get_store())
			);
		}
		
		//Commission
		$employee_data['commission_percent'] = (float)$this->input->post('commission_percent');
		$employee_data['account_balance'] = (float)$this->input->post('account_balance')? $this->input->post('account_balance'):0;
		$employee_data['type'] = $this->input->post('type')?$this->input->post('type'):null;
		$employee_data['id_rate'] = $this->input->post('id_rate')?  $this->input->post('id_rate'):1;
		
		
		$this->load->helper('directory');
		
		$valid_languages = directory_map(APPPATH.'language/', 1);
		$employee_data=array_merge($employee_data,array('language'=>in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english'));
		

		if((is_array($location_data) && count($location_data) > 0) && $this->Employee->save($person_data,$employee_data,$permission_data, $permission_action_data, $location_data, $employee_id,$cajas))
		{	
			// save control acceso por tienda
			if($employee_id !=1 && $this->config->item('activar_control_access_employee')==1)
			{
				$_employee_id_acce = $employee_id == -1 ? $person_data['person_id']: $employee_id;
				
				$result_access = $this->Hour_access->save($hora_acceso,$_employee_id_acce);
				
			}
			
			if ($this->Location->get_info_for_key('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
			}
			
			$success_message = '';
			
			//New employee
			if($employee_id==-1)
			{
				$success_message = lang('employees_successful_adding').' '.$person_data['first_name'].' '.$person_data['last_name'];
				echo json_encode(array('success'=>true,'message'=>$success_message,'person_id'=>$employee_data['person_id'],'redirect_code'=>$redirect_code));
			}
			else //previous employee
			{
				$success_message = lang('employees_successful_updating').' '.$person_data['first_name'].' '.$person_data['last_name'];
				$this->session->set_flashdata('manage_success_message', $success_message);
				echo json_encode(array('success'=>true,'message'=>$success_message,'person_id'=>$employee_id,'redirect_code'=>$redirect_code));
			}			
			
			//Delete Image
			if($this->input->post('del_image') && $employee_id != -1 && !is_on_demo_host())
			{
				$employee_info = $this->Employee->get_info($employee_id);
			    if($employee_info->image_id != null)
			    {
					$this->Person->update_image(NULL,$employee_id);
					$this->Appfile->delete($employee_info->image_id);
			    }
			}

			//Save Image File
			if(!empty($_FILES["image_id"]) && $_FILES["image_id"]["error"] == UPLOAD_ERR_OK && !$this->Employee->es_demo())
			{			    

			    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
				$extension = strtolower(pathinfo($_FILES["image_id"]["name"], PATHINFO_EXTENSION));
			    if (in_array($extension, $allowed_extensions))
			    {
				    $config['image_library'] = 'gd2';
				    $config['source_image']	= $_FILES["image_id"]["tmp_name"];
				    $config['create_thumb'] = FALSE;
				    $config['maintain_ratio'] = TRUE;
				    $config['width']	 = 400;
				    $config['height']	= 300;
				    $this->load->library('image_lib', $config); 
				    $this->image_lib->resize();
				    $image_file_id = $this->Appfile->save($_FILES["image_id"]["name"], file_get_contents($_FILES["image_id"]["tmp_name"]));
			    }
						if($employee_id==-1)
						{
			    			$this->Person->update_image($image_file_id,$employee_data['person_id']);
						}
						else
						{
							$this->Person->update_image($image_file_id,$employee_id);
		    			
						}
			}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('employees_error_adding_updating').' '.
			$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
		}
	}
	
	/*
	This deletes employees from the employees table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$employees_to_delete=$this->input->post('ids');
		
		if (in_array(1,$employees_to_delete))
		{
			//failure
			echo json_encode(array('success'=>false,'message'=>lang('employees_cannot_delete_default_user')));
		}
		elseif($this->Employee->delete_list($employees_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('employees_successful_deleted').' '.
			count($employees_to_delete).' '.lang('employees_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('employees_cannot_be_deleted')));
		}
	}
		
	function cleanup()
	{
		$this->Employee->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('employees_cleanup_sucessful')));
	}
}
?>