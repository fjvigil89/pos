<?php
require_once("report.php");
class specific_movement extends Report

{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
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
		$columns[] = array('data'=>"Categoría", 'align'=> 'left');
		$columns[] = array('data'=>"Cajero", 'align'=> 'left');
		$columns[] = array('data'=>"En caja", 'align'=> 'right');
				
		
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