<?php
require_once("report.php");
class summary_movement extends Report

{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		
		$return = array();
		
	
		$columns = array();
		$columns[] = array('data'=>"Fecha", 'align'=> 'left');		
		$columns[] = array('data'=>"Entrada", 'align'=> 'left');
		$columns[] = array('data'=>"Salida", 'align'=> 'left');
		$columns[] = array('data'=>"Categoría", 'align'=> 'left');
				
		
		return $columns;
	}
	
	public function getData()
	{
		$data = array();
		$data['details'] = array();
		$this->db->select_sum("IF(`type_movement`=0,0,`mount`) ","ingreso");
		$this->db->select_sum("IF(`type_movement`=1,0,`mount`) ","egreso");
		$this->db->select(" categorias_gastos,date(register_date) as register_date ");
		$this->db->from('movement_items_temp');
		//$this->where_categoria();
		$this->db->group_by('date(register_date),categorias_gastos');
		$this->db->order_by('date(register_date) DESC ,categorias_gastos DESC');
		$ingresos=$this->db->get()->result_array();		
		$data['details']=	$ingresos;
		

		
		return $data;
	}

	public function getTotalRows()
	{
	
		$this->db->select("  date(register_date) as date  ");
		$this->db->from('movement_items_temp');
		//$this->where_categoria();
		$this->db->group_by('date(register_date), categorias_gastos');
		$cantidad = $this->db->get()->num_rows();
		return $cantidad;
		

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
		
		$data=array();
		$this->db->select(' SUM(mount) as suma, categorias_gastos');
		$this->db->from('movement_items_temp');
		$this->db->group_by('categorias_gastos');
		$this->db->where('type_movement', 0);
	//	$this->where_categoria();
		$data["egreos"]= $this->db->get()->result_array();
		
		$this->db->select(' SUM(mount) as suma, categorias_gastos');
		$this->db->from('movement_items_temp');		
		$this->db->where('type_movement', 1);
		$this->db->group_by('categorias_gastos');
		//$this->where_categoria();
		$data["ingresos"]= $this->db->get()->result_array();
		
	
		$this->db->select(' SUM(mount) as suma');
		$this->db->from('movement_items_temp');
		$this->db->where('type_movement', 1);
		//$this->where_categoria();
		$ret = $this->db->get()->row_array();
		$data["suma_ingreso"]= $ret["suma"];
	
				
		$this->db->select(' SUM(mount) as suma');
		$this->db->from('movement_items_temp');
		//$this->where_categoria();
		$this->db->where('type_movement', 0);
		$ret = $this->db->get()->row_array();
		$data["suma_egreoso"]= $ret["suma"];		
		
		
		return $data;
	}
}
?>