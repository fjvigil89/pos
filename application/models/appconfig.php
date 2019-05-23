<?php
class Appconfig extends CI_Model 
{
	
	
	function exists($key)
	{
		$this->db->from('app_config');	
		$this->db->where('app_config.key',$key);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	/**
	 * cosnulta si empleado tiene el modulo offline y en la tiena estan  permitidas las ventas offline
	 */
	function is_offline_sales(){
		static $offline_sales;
		if($offline_sales===null){			
			if($this-> get("offline_sales")==1 and $this->Employee->has_module_permission('offline', $this->Employee->get_logged_in_employee_info()->person_id) and $this->Employee->has_module_permission("sales",$this->Employee->get_logged_in_employee_info()->person_id)){
				$offline_sales=true;
			}else{
				$offline_sales=false;	
			}			
		}
		return $offline_sales;
	}
	function es_franquicia(){
		static $franquicia;
		if($franquicia===null){
			$franquicia=  $this-> get("es_franquicia");
		}
		return $franquicia;
	}
	function get_video($name_video){
		static $resellers_id;
		if($resellers_id===null){
			$resellers_id=  $this-> get("resellers_id");
		}
		$login_db = $this->load->database('login',true);
		$login_db->select('turorials_reseller.*');
		$login_db->from('turorials_reseller');
		$login_db->join('tutoriales', 'turorials_reseller.id_tutorial =
		tutoriales.id ', 'left');
		$login_db->where( $login_db->dbprefix('turorials_reseller').'.id_reseller',$resellers_id);
		$login_db->where( $login_db->dbprefix('tutoriales').'.name_video',$name_video);
		$row = $login_db->get()->row_array();
		if (!empty($row))
		{
			return $row['url_video'];
		}
		return null;
		
	}
	function get_expense_category(){		
		return array(
			lang('Nomina')=>lang('Nomina'), 
			lang('Transporte')=>lang('Transporte'),
			lang('Comida')=>lang('Comida'), 
			lang('Arriendo')=>lang('Arriendo'), 
			lang('Reparaciones')=>lang('Reparaciones'), 
			lang('Publicidad')=>lang('Publicidad'),
			lang('Servicios_publicos')=>lang('Servicios_publicos'),
			lang('Suministros')=>lang('Suministros'),
			lang('Mantenimiento')=>lang('Mantenimiento'), 
			lang('Limpieza')=>lang('Limpieza'),
			lang('Combustible')=>lang('Combustible'),
			lang('otros')=>lang('otros')
		);
	}
	function get_units()
	{
		return array(
			lang('items_unity') => lang('items_unity'),
			lang('items_kg') => lang('items_kg'),
			lang('items_pounds') => lang('items_pounds'),
			lang('items_milliliters') => lang('items_milliliters'),
			lang('items_ounces') => lang('items_ounces'),
			lang("items_meters") => lang("items_meters"),
			lang("items_centimeter") => lang("items_centimeter"),
			lang("items_liter") => lang("items_liter"),
			lang("items_gram") => lang("items_gram"),
		);
	}
	function get_all_units()
	{
		$return = $this->get_units();
		$units_measurement = $this->get('units_measurement');	
		
		if ($units_measurement)		
			$units_measurement = array_map('trim', explode(',',$units_measurement));	
		else 
			$units_measurement = array();	

		foreach($units_measurement as  $unit)
			$return[ucwords($unit)]= ucwords($unit);
		
		$return = array_unique($return);

		ksort($return);

		$return = array('' => lang("common_select")) + $return;

		return $return;
	}
	function get_all()
	{
		$this->db->from('app_config');
		$this->db->order_by("key", "asc");
		return $this->db->get();		
	}
	
	function get($key)
	{
		return $this->config->item($key);
	}
	
	function save($key,$value)
	{
		$config_data=array(
		'key'=>$key,
		'value'=>$value
		);
				
		if (!$this->exists($key))
		{
			return $this->db->insert('app_config',$config_data);
		}
		
		$this->db->where('key', $key);
		return $this->db->update('app_config',$config_data);		
	}
	
	function get_raw_language_value()
	{
		$this->db->from('app_config');
		$this->db->where("key", "language");
		$row = $this->db->get()->row_array();
		if (!empty($row))
		{
			return $row['value'];
		}
		return '';	
	}
	function update_time_zone($language){
		// apatir del idioma seleccionado se consulta el time zone 
		$query = $this->db->get_where('time_zones',array('language' => $language));
		$time_zone="";		
        if($query->num_rows() > 0 )
        {
         $row= $query->row();         
         $time_zone=$row->time_zone;
        }
        else return false;// si no esta el idioma 

		$dato= array('timezone' => $time_zone);
		// se actualiza en todas la ubicaciones de la tienda 
		foreach($this->Location->get_all()->result() as $location){
			$this->db->where('location_id', $location->location_id);
			if(! $this->db->update('locations',$dato))
				return false;
				
		}
		return true;
		
	}
	function batch_save($data)
	{
		$success=true;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		foreach($data as $key=>$value)
		{
			if(!$this->save($key, $value))
			{
				$success=false;
				break;
			}
		}
		if(isset ($data["language"]) && !$this->update_time_zone($data["language"])){
			$success=false;
		}
		
		$this->db->trans_complete();		
		return $success;
		
	}
	function get_data_commpany_by_domain($domain){
		$domain = strtolower($domain);
		if($domain == URL_COMPANY OR $domain==  URL_COMPANY."/"
		|| $domain==  URL_COMPANY."/pos" || $domain==  URL_COMPANY."/pos/" ){
			return array();
		}
		$login_db = $this->load->database('login',true);
		$login_db->from('resellers');
		$login_db->where('domain', $domain);
		$login_db->or_where('domain', $domain."/");
		$login_db->or_where('domain', $domain."/pos");
		$login_db->or_where('domain', $domain."/pos/");

		$login_db->or_where('domain', substr($domain, 0, -1));
		$login_db->or_where('domain',substr($domain, 0, -1)."/");
		$login_db->or_where('domain', substr($domain, 0, -1)."/pos");
		$login_db->or_where('domain', substr($domain, 0, -1)."/pos/");
		
		$login_db->or_where('domain', substr($domain, 0, -5));
		$login_db->or_where('domain',substr($domain, 0, -5)."/");
		$query= $login_db->get();		
		if($query->num_rows()==1)
		{
			return $query->row();
		}	
						
		return array();
	}
	function get_data_commpany($id_resellers){
		static $data_company;
		
		if (!$data_company)
		{

			$login_db = $this->load->database('login',true);
			$query = $login_db->get_where('resellers', array('id' => $id_resellers), 1);
			
			if($query->num_rows()==1)
			{
				$data_company= $query->row();
			}
			else{
				$obj=new stdClass();

				//Get all the fields from items table
				$fields = $login_db->list_fields('resellers');

				foreach ($fields as $field)
				{
					$obj->$field='';
				}
				$data_company= $obj;
			}
		}
		return $data_company;
	}

	function get_theme_commpany($id_resellers){
		static $theme_company;
		
		if (!$theme_company)
		{

			$login_db = $this->load->database('login',true);
			//$query = $login_db->get_where('resellers', array('id' => $id_resellers), 1);	
			
			/*$query = $login_db->select('DISTINCT layout, name_feature_css, css_class', FALSE)
					->from('theme_reseller')
					->where('theme_reseller.reseller_id', $id_resellers)
					->join('theme_reseller_configs', 'theme_reseller_configs.theme_reseller_id = theme_reseller.id')
					->join('themes', 'themes.id = theme_reseller.theme_id')
					->join('theme_features', 'themes.id = theme_features.theme_id')
					->join('theme_feature_values', 'theme_features.id = theme_feature_values.theme_feature_id')
					->join('theme_reseller_configs', 'theme_reseller_configs.theme_feature_id = theme_features.id')
					->join('theme_reseller_configs', 'theme_reseller_configs.theme_feature_value_id = theme_feature_values.id')
					->get();*/

			$sql = "SELECT DISTINCT layout, name_feature_css, css_class 
				FROM phppos_themes, phppos_theme_reseller, phppos_theme_reseller_configs, phppos_theme_features, phppos_theme_feature_values
				WHERE  phppos_theme_reseller.reseller_id = '$id_resellers'
					AND phppos_themes.id = phppos_theme_reseller.theme_id 
					AND phppos_themes.id = phppos_theme_features.theme_id 
					AND phppos_theme_features.id = phppos_theme_feature_values.theme_feature_id 
					AND phppos_theme_reseller_configs.theme_feature_id = phppos_theme_features.id
					AND phppos_theme_reseller_configs.theme_feature_value_id = phppos_theme_feature_values.id 
					AND phppos_theme_reseller_configs.theme_reseller_id = phppos_theme_reseller.id ";

			$query = $login_db->query($sql);
						
			if ($query->num_rows() > 0)
			{
				//$theme_company = $query->row();
				$theme_company = $query->result();
			} else {
				$obj=array();
				
				$theme_company= $obj;
			}
		}
		return $theme_company;
	}
	/**
	 * Preguntamos si el tema es el prodefecto, el id del  tema es 1, es por defecto
	 */
	function is_theme_default($id_resellers){
		static $is_default;
		
		if (!$is_default)
		{

			$login_db = $this->load->database('login',true);
			$query = $login_db->get_where('theme_reseller', array('reseller_id' => $id_resellers), 1);
			
			if($query->num_rows()==1)
			{
				$data = $query->row();
				$is_default = $data->theme_id==1 ? TRUE :FALSE;
			}
		}
		return $is_default;
	}

		
	function get_logo_image($resellers_id=FALSE)
	{
		if ($this->config->item('company_logo'))
		{
			return site_url('app_files/view/'.$this->get('company_logo'));
		}
		else if($this->config->item('es_franquicia') || $resellers_id!==FALSE){
			$id=  $resellers_id!==FALSE ? $resellers_id : $this->get('resellers_id');
			$url_=  site_url('app_files/view_logo_store/'.$id );
			return  $url_;
		}else{
			return  base_url().'img/header_logo.png';
		}
	}
		
	function get_additional_payment_types()
	{
		$return = array();
		$payment_types = $this->get('additional_payment_types');
		
		if ($payment_types)
		{
			$return = array_map('trim', explode(',',$payment_types));
		}
		
		return $return;
	}
	/** las categoria que no seden contablizar en los reporte  */
	function where_categoria(){
		
		$data=array(
			"Venta",
           	"Devolución",
            lang("sales_store_account_payment"),
            "Venta eliminada",
            "Cierre de caja",
			"Apertura de caja",
			"Compra retornada",
			"Compra realizada",
			"Venta restaurada",
			lang("receivings_store_account_payment")
		);		
		
		return $data;
	}

	function get_categorias_gastos()
	{
		$return = array();
		$categoria_gastos = $this->get('categoria_gastos');	
		
		if ($categoria_gastos)
		{
			$return = array_map('trim', explode(',',$categoria_gastos));
		}
		foreach($this->get_expense_category()as $category){
			$return[$category]=$category;
		}
		$return= array_unique($return);
		//sort($return);
		return $return;
	}
    
    function update_config()
    {
        $this->config->load('config_template');
        $template = $this->config->item('config_template');
        
        $query    = $this->db->get('app_config');
        $query    = $query->result_array();
        
        $config_array    = array();
        $config_template = array();
        $data            = array();
        
        foreach ($template as $key => $value) 
        {
            $config_template[] = $key;
        }
        
        foreach($query as $row)
        {
            $config_array[] = $row['key'];
        }
        
        sort($config_array);
        sort($config_template);
        
        $array_diff =  array_diff($config_template,$config_array) ;
        
        if( !empty($array_diff) )
        {
            sort($array_diff);
            
            foreach ($array_diff as $key=>$value) 
            {
                $data[$key]['key']   = $value;
                $data[$key]['value'] = $template[$value];
            }
           
            if($this->db->insert_batch('app_config', $data))
            {
                return true;
            }
            
            return false;
        }
    }
    ////////////Modulo de Tipo de Servicios
    function add_tipo_servicios($param){
        $campo=array(
           'tservicios'     => $param['nomb']
        );
        $this->db->insert('phppos_technical_supports_tservicios', $campo);         
    }
    
    function get_tipo_servicios(){
        $reportServicios=$this->db->query("SELECT *
        FROM phppos_technical_supports_tservicios
        WHERE tservicios!='' Order By tservicios"); 
         return $reportServicios;        
    }
    function delet_tipo_servicios($param){
        $this->db->where('id', $param['elim']);       
        return  $this->db->delete('phppos_technical_supports_tservicios');   
    }
    
    /////////////Modulos de Fallas
    function add_tipo_fallas($param){
        $campo=array(
           'tfallas'     => $param['nomb']
        );
        $this->db->insert('phppos_technical_supports_tfallas', $campo);        
    }
    function get_tipo_fallas(){
        $reportServicios=$this->db->query("SELECT *
        FROM phppos_technical_supports_tfallas
        WHERE tfallas!='' Order By tfallas"); 
         return $reportServicios;        
    }
    function delet_tipo_fallas($param){
        $this->db->where('id', $param['elim']);       
        return  $this->db->delete('phppos_technical_supports_tfallas');   
    }
    
    function get_ubica_equipos(){
        $reportServicios=$this->db->query("SELECT *
        FROM phppos_technical_supports_ubi_equipos
        WHERE ubicacion!='' Order By ubicacion"); 
         return $reportServicios;        
    }
    function add_ubica_equipos($param){
        $campo=array(
           'ubicacion'     => $param['nomb']
        );
        $this->db->insert('phppos_technical_supports_ubi_equipos', $campo);         
    }
    function delet_ubica_equipos($param){
        $this->db->where('id', $param['elim']);       
        return  $this->db->delete('phppos_technical_supports_ubi_equipos');   
    }
    
}

?>