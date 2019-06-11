<?php
class Profile extends CI_Model
{

	protected $login;
    
    function __construct() {
        parent::__Construct();
        $this->login = $this->load->database("login",TRUE);
        //$this->db = $this->load->database("shop",TRUE);
        //$res = $bd->get('noticias');
    }

	function get_profiles_admin(){
		$result=array();
		if($this->Employee->es_demo()){
			$resellers_id = $this->config->item('resellers_id');
			$this->login->select('*');
			$this->login->from('profiles');
			$this->login->where('deleted = 0 AND reseller_id =',$resellers_id);
			$this->login->order_by('name_lang_key', 'ASC');
			$result = $this->login->get()->result();
		}	

		return $result;
	}	

	function load_profile($profile = 1) {
		
		$data = array();

		$this->login->select('id, name_lang_key, reseller_id, deleted');
		$this->login->from('profiles');
		$this->login->where('id', $profile);
		$this->login->where('deleted', 0);
		$perfil = $this->login->get()->result();

		$this->login->select('profile_id, module_id, deleted');
		$this->login->from('profiles_modules');
		$this->login->where('profile_id', $profile);
		$this->login->where('deleted', 0);
		$modulos = $this->login->get()->result();

		$array_modulos = array();
		foreach ($modulos as $key => $modulo) {
			array_push($array_modulos, $modulo->module_id);
		}

		$data['modulos'] = $array_modulos;

		$this->login->select('profile_id, module_id, action_id, deleted');
		$this->login->from('profiles_actions');
		$this->login->where('profile_id', $profile);
		$this->login->where('deleted', 0);
		$acciones = $this->login->get()->result();

		$array_acciones = array();
		foreach ($acciones as $key => $accion) {
			array_push($array_acciones, $accion->module_id.$accion->action_id);
		}

		$data['acciones'] = $array_acciones;

		$this->db->select('name_lang_key, desc_lang_key, sort, icon, module_id, color');
		$this->db->from('modules');
		$modules_exist = $this->db->get()->result();
		$array_modules_exist = array();
		foreach ($modules_exist as $key => $module_exist) {
			array_push($array_modules_exist, $module_exist->module_id);
		}

		$data['modules_exist'] = $array_modules_exist;

		$this->db->select('action_id, module_id, action_name_key, sort');
		$this->db->from('modules_actions');
		$actions_exist = $this->db->get()->result();
		
		$array_actions_exist = array();
		
		foreach ($actions_exist as $action_exist) {
			array_push($array_actions_exist,$action_exist->module_id.$action_exist->action_id);
		}	

		$this->db->query("SET FOREIGN_KEY_CHECKS=0");

		$data['actions_exist'] = $array_actions_exist;

		foreach ($modulos as $modulo) {

			if(!in_array($modulo->module_id, $array_modules_exist)) {
				$this->login->select('*');
				$this->login->from('modules');
				$this->login->where('module_id',$modulo->module_id);
				$modulo_db = $this->login->get();
				$aux_modulo_db = $modulo_db->row();

				$datos_modules = array();

				$datos_modules['name_lang_key'] = $aux_modulo_db->name_lang_key;
				$datos_modules['desc_lang_key'] = $aux_modulo_db->desc_lang_key;
				$datos_modules['sort'] 			= $aux_modulo_db->sort;
				$datos_modules['icon'] 			= $aux_modulo_db->icon;
				$datos_modules['module_id'] 	= $aux_modulo_db->module_id;
				$datos_modules['color'] 		= $aux_modulo_db->color;

				$this->db->insert('modules', $datos_modules);
				if($aux_modulo_db->module_id != 'employees') {
					$datos_permissions = array();

					$datos_permissions['module_id'] = $aux_modulo_db->module_id;
					$datos_permissions['person_id'] = 1;

					$this->db->insert('permissions', $datos_permissions);
				}
			}
		}

		$i=0;
		foreach ($acciones as $accion) {
			
			if(!in_array($accion->module_id.$accion->action_id, $array_actions_exist)) {
				$this->db->select('*');
				$this->db->from('modules_actions');
				$this->db->where("module_id = '".$accion->module_id."' AND action_id = '".$accion->action_id."'");
				$accion_db = $this->db->get();
				
				if($accion_db->num_rows() > 0) {

					$aux_accion_db = $accion_db->row();

					$datos_actions = array();

					$datos_actions['action_id'] = $aux_accion_db->action_id;
					$datos_actions['module_id'] = $aux_accion_db->module_id;
					$datos_actions['action_name_key'] = $aux_accion_db->action_name_key;
					$datos_actions['sort'] = $aux_accion_db->sort;

					$data['demo2017'][$i] = $datos_actions;

					$this->db->insert('modules_actions', $datos_actions);
					
					if($aux_accion_db->module_id != 'employees') {
						$datos_permissions_actions = array();

						$datos_permissions_actions['module_id'] = $aux_accion_db->module_id;
						$datos_permissions_actions['action_id'] = $aux_accion_db->action_id;
						$datos_permissions_actions['person_id'] = 1;

						$this->db->insert('permissions_actions', $datos_permissions_actions);
					}
					$i++;	
				}
			}
		}

		foreach ($actions_exist as $action_exist) {
			if(!in_array($action_exist->module_id.$action_exist->action_id, $array_acciones)) {
				$this->db->where("module_id = '".$action_exist->module_id."' AND action_id = '".$action_exist->action_id."'");
				$this->db->delete('modules_actions');

				$this->db->where("module_id <> 'employees' AND module_id = '".$action_exist->module_id."' AND action_id = '".$action_exist->action_id."'");
				$this->db->delete('permissions_actions');
			}
		}

		foreach ($modules_exist as $module_exist) {
			if(!in_array($module_exist->module_id, $array_modulos)) {
				$this->db->where("module_id = '".$module_exist->module_id."'");
				$this->db->delete('modules');

				$this->db->where("module_id <> 'employees' AND module_id = '".$module_exist->module_id."'");
				$this->db->delete('permissions');
			}
		}
		$this->db->query("SET FOREIGN_KEY_CHECKS=1");

		$this->login->select("`key` as clave, value");
		$this->login->from("profiles_configs");
		$this->login->where("profile_id", $profile);

		$profile_configs = $this->login->get()->result();

		foreach ($profile_configs as $profile_config) {
			$this->db->set('value', "'".$profile_config->value."'", FALSE);
			$this->db->where('`key`', $profile_config->clave);
			$this->db->update('app_config'); 
		}

		$this->login->select("id_rate, name, sale_rate, rate_buy");
		$this->login->from("profiles_rates");
		$this->login->where("profile_id", $profile);

		$profile_rates = $this->login->get()->result();

		foreach ($profile_rates as $profile_rate) {
			$this->db->set('name', "'".$profile_rate->name."'", FALSE);
			$this->db->set('sale_rate', "'".$profile_rate->sale_rate."'", FALSE);
			$this->db->set('rate_buy', "'".$profile_rate->rate_buy."'", FALSE);
			$this->db->where('id_rate', $profile_rate->id_rate);
			$this->db->update('rates'); 
		}

		$this->db->set('value', '1', FALSE);
		$this->db->where('`key`', 'hide_modal');
		$this->db->update('app_config');

		$this->db->set('value', '0', FALSE);
		$this->db->where('`key`', 'initial_config');
		$this->db->update('app_config');

		$data['profile_configs'] = $profile_configs;

		return $data;

	}
}