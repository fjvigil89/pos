<?php
class Module extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }
	function get_all_permissions(){

		$this->db->select('permissions.*');		
		$this->db->from('permissions');
		$this->db->join('employees', 'employees.person_id = permissions.person_id and  deleted=0  ');
	
		return $this->db->get();		

	}
	function get_module_name($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return lang($row->name_lang_key);
		}
		
		return lang('error_unknown');
	}
	
	function get_module_desc($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return lang($row->desc_lang_key);
		}
	
		return lang('error_unknown');	
	}
	
	function get_all_modules()
	{
		$this->db->from('modules');
		$this->db->order_by("sort", "asc");
		return $this->db->get();		
	}
	
	function get_allowed_modules($person_id)
	{
		$this->db->from('modules');
		$this->db->join('permissions','permissions.module_id=modules.module_id');
		$this->db->where("permissions.person_id",$person_id);
		$this->db->order_by("sort", "asc");
		return $this->db->get();		
	}
    
    function apend_translated_module_names($allowed_modules){
        $result_array = array();
        foreach ($allowed_modules->result_array() as $key=>$value) {

            $result_array[$key] = $value;
            $result_array[$key]['translated_module_name'] = lang("module_".$value['module_id']);

        }
        
        return $result_array;
    }
}

