
<?php
require_once("report.php");
class movement_balance_employee extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		$columns = array();
		
		
       
        $columns[]= array('data'=>"id", 'align'=> 'left');
		$columns[]= array('data'=>"Fecha", 'align'=> 'left');
		$columns[] = array('data'=>"Empleado", 'align'=> 'left');
        $columns[] = array('data'=>"Categoria", 'align'=> 'left');
        $columns[] = array('data'=>"Descripción", 'align'=> 'left');
        $columns[] = array('data'=>"Monton", 'align'=> 'left');
		$columns[] = array('data'=>"Saldo", 'align'=> 'left');		
		
		return $columns;		
	}
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
        $data['details'] = array();
        
        $this->db->from('movement_balance_employees_temp');        
        $categorys = array('Ingreso','Retiro');
		$this->db->where_in('category', $categorys);
		$this->db->order_by('register_date','DESC');
        $data['details']= $this->db->get()->result_array();
		return $data;
	}
	
	public function getTotalRows()
	{	
		$this->db->select("COUNT(DISTINCT(id_movement)) as movement_count");
		
        $this->db->from('movement_balance_employees_temp');
        
        $categorys = array('Ingreso','Retiro');
        $this->db->where_in('category', $categorys);
		$ret = $this->db->get()->row_array();
		return $ret['movement_count'];
	}
	
	
	public function getSummaryData()
	{
        $return = array(
			'entrada' => 0,
			'salida' => 0,
			
        );
        $this->db->select(' SUM(amount ) as suma');
		$this->db->from('movement_balance_employees_temp');
        $this->db->where('category', "Ingreso"); 
		
        $ret = $this->db->get()->row_array();
        $return ['entrada']= $ret["suma"];
        
        $this->db->select(' SUM(amount) as suma');
		$this->db->from('movement_balance_employees_temp');
        $this->db->where('category', "Retiro"); 
		
        $ret = $this->db->get()->row_array();
		$return ['salida']= $ret["suma"];
        //$this->db->group_by('category');
		return $return;
	}
}
?>