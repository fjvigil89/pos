<?php
require_once("report.php");
class specific_movement extends Report

{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns($type='')
	{
		
		$return = array();
		
	
		$columns = array();
		$columns[] = array('data'=>"", 'align'=> 'left');
		$columns[] = array('data'=>"ID", 'align'=> 'left');
		$columns[] = array('data'=>"Fecha", 'align'=> 'left');

		
		$columns[] = array('data'=>"Descripción", 'align'=> 'left');
	    $columns[] = array('data'=>"Tipo de documento", 'align'=> 'left');
		$columns[] = array('data'=>"Entrada", 'align'=> 'left');
		$columns[] = array('data'=>"Salida", 'align'=> 'left');
		if(!$type){
		$columns[] = array('data'=>"Categoría", 'align'=> 'left');	
		}
		$columns[] = array('data'=>"Cajero", 'align'=> 'left');
		
		if(!$type){	
		$columns[] = array('data'=>"En caja", 'align'=> 'right');
		}
				
		
		return $columns;
	}
	
	public function getData()
	{ 
		$data = array();
		$data['details'] = array();
		
		$this->db->from('movement_items_temp');
		//$this->where_categoria();

		$this->db->order_by('register_movement_id','DESC');
		$data['details']=$this->db->get()->result_array();
		       
        
		return $data;
	}


	public function getData_sale_especific($params){
		$data=array();
		$data['detailes']=array();
		set_time_limit(0);
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();
        
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.
			' and sales.location_id='.$this->db->escape($location_id);
		}

		if (isset($params['register_id']) && $params['register_id']!="all")
		{
			$where .= ' and sales.register_id='.$this->db->escape($params["register_id"]);
		}

		if(isset($params['empleado_id']) && $params['empleado_id']!="all"){
			$where .= ' and sales.employee_id='.$this->db->escape($params["empleado_id"]);
		}
		//, sales.is_invoice 
              
		$query=$this->db->query("SELECT sales.sale_id , sales.sale_time, sales.payment_type ,sales.is_invoice , sales.payment_type , sales.register_id  , e.username FROM ".$this->db->dbprefix('sales')." sales INNER JOIN ".$this->db->dbprefix('employees')." e  on sales.employee_id = e.id  
		      $where  ");

		$result=$query->result_array();
		
       	$ventas=array();
        $dinero_cantidad=array();
        $i=0;
        
       	foreach ($result as $key => $consulta) {
       		
       		foreach ($consulta as $indice_consulta => $value2) {
       	
       			if($indice_consulta=='payment_type'){
       			

       				$dinero_cantidad = str_replace(array('<br />'), '%%', $value2);
       				$dinero_cantidad = str_replace(array('<br/>'), '%%', $dinero_cantidad);
       				$dinero_cantidad= explode("%%",$dinero_cantidad);
                    

       				foreach ($dinero_cantidad as $indice => $valor) {
       					if($valor !=''){

                            $assign = explode(":",$valor);

                            foreach ($consulta as $key => $value) {
                            	$ventas[$i][$key]=$value;
                            }

                            $ventas[$i]['item_unit_price']=$assign[1];
                            $ventas[$i]['payment_type']=$assign[0];
                            $ventas[$i]['is_invoice']='Factura';
                          

       					}
       					$i++;

       				}
       			
       			}
            
       		
       		}
       		
       	}

        return $ventas;
      	
		   
	}
	public function getData_reicivings_especific($params){
		$data=array();
		$data['detailes']=array();
		set_time_limit(0);
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();
        
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE receiving_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.
			' and receiving.location_id='.$this->db->escape($location_id);
		}

		if(isset($params['empleado_id']) && $params['empleado_id']!="all"){
			$where .= ' and receiving.employee_id='.$this->db->escape($params["empleado_id"]);
		}


	    $query=$this->db->query("SELECT receiving.receiving_id , receiving.receiving_time , receiving.payment_type, e.username , sum(items.item_unit_price)  FROM ".$this->db->dbprefix('receivings')." receiving INNER JOIN ".$this->db->dbprefix('employees')." e  on receiving.employee_id = e.id  INNER JOIN ".$this->db->dbprefix('receivings_items')." items on receiving.receiving_id = items.receiving_id
		       $where group by receiving.receiving_id ");
        
	    $compras = $query->result_array();
	    
	    foreach ($compras as $indice_consulta => $value) {
	    	foreach ($value as $indice => $value2) {
	    		$compras[$indice_consulta]['is_invoice']='Recibo';
	    		$compras[$indice_consulta]['item_unit_price']=$compras[$indice_consulta]['sum(items.item_unit_price)'];
	    		$compras[$indice_consulta]['register_id']='No';
	    	}
	    }

        return $compras;
	}


	

	public function getTotalRows()
	{
		
		$this->db->select(' COUNT(DISTINCT('.$this->db->dbprefix('movement_items_temp').'.register_movement_id)) as movement_count ');
		$this->db->from('movement_items_temp');
		//$this->where_categoria();

		$ret = $this->db->get()->row_array();
		return $ret['movement_count'];	

	}
	/*function where_categoria(){
		$data=array(
			"Venta",
           	"Devolución",
            lang("sales_store_account_payment"),
            "Venta eliminada",
            "Cierre de caja",
            "Apertura de caja"
		);		
		foreach($data as $categoria){
			$this->db->where('categorias_gastos !=', $categoria);
		}
	}*/
	
	public function getSummaryData()
	{
		$this->db->select(' SUM(mount) as suma');
		$this->db->from('movement_items_temp');
		//$this->where_categoria();
		$this->db->where('type_movement', 0);
		
	 
		$return = array(
			'entrada' => 0,
			'salida' => 0,
			
		);
		$ret = $this->db->get()->row_array();
		$return["salida"]= $ret["suma"];
		
		$this->db->select(' SUM(mount) as suma');
		$this->db->from('movement_items_temp');
		//$this->where_categoria();

		$this->db->where('type_movement', 1);

		$ret = $this->db->get()->row_array();
		$return["entrada"]= $ret["suma"];		
		
		return $return;
	}
}
?>