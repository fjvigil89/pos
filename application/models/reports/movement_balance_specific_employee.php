
<?php
require_once("report.php");
class Movement_balance_specific_employee extends Report
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
		$this->db->order_by('register_date','DESC');

        $data['details']= $this->db->get()->result_array();
		
	
		
		return $data;
	}
	
	public function getTotalRows()
	{	
		$this->db->select("COUNT(DISTINCT(id_movement)) as movement_count");
		
        $this->db->from('movement_balance_employees_temp');
		$ret = $this->db->get()->row_array();
		return $ret['movement_count'];
	}
	
	
	public function getSummaryData()
	{
       
		return array();
	}
}
?>