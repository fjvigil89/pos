<?php
require_once("report.php");
class Payments_cash extends Report
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
       // $columns[] = array('data'=>"Cliente", 'align'=> 'left');
		$columns[] = array('data'=>"Forma de pogo", 'align'=> 'left');
		$columns[] = array('data'=>"Total", 'align'=> 'left');
				
		
		return $columns;
	}
	
	
public function getData()
	{
     
        
        $data = array();       
       
        $this->db->from('petty_cash');    
        $this->db->where('petty_cash.petty_cash_time BETWEEN '.$this->db->escape($this->params['start_date'])." and ".$this->db->escape($this->params['end_date']));
        if($this->params['customer_id']!=-1){
        	$this->db->where($this->db->dbprefix('petty_cash') . '.customer_id',$this->db->escape($this->params['customer_id']));
		}
		$this->db->where($this->db->dbprefix('petty_cash') . '.deleted', 0);
		$this->db->where($this->db->dbprefix('petty_cash') . '.location_id',$this->params['location_id']);
		
		$this->db->order_by('petty_cash_time');
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}	
        $data["details"] = $this->db->get()->result_array();
		return $data;
	}
	
	public function getTotalRows()
	{
        $this->db->select('COUNT(petty_cash_id) as total_row');
        $this->db->from('petty_cash');    
        $this->db->where('petty_cash.petty_cash_time BETWEEN '.$this->db->escape($this->params['start_date'])." and ".$this->db->escape($this->params['end_date']));
        
		if($this->params['customer_id']!=-1){
        	$this->db->where($this->db->dbprefix('petty_cash') . '.customer_id',$this->db->escape($this->params['customer_id']));
		}
		$this->db->where($this->db->dbprefix('petty_cash') . '.location_id',$this->params['location_id']);

		$this->db->where($this->db->dbprefix('petty_cash') . '.deleted', 0);
        $this->db->order_by('petty_cash_time');
        $data= $this->db->get()->row_array();
		return $data["total_row"];
	}
	public function getSummaryData()
	{
		$this->db->select('sum(monton_total) as total');
        $this->db->from('petty_cash');    
        $this->db->where('petty_cash.petty_cash_time BETWEEN '.$this->db->escape($this->params['start_date'])." and ".$this->db->escape($this->params['end_date']));
        
		if($this->params['customer_id']!=-1){
        	$this->db->where($this->db->dbprefix('petty_cash') . '.customer_id',$this->db->escape($this->params['customer_id']));
		}
		$this->db->where($this->db->dbprefix('petty_cash') . '.location_id',$this->params['location_id']);

		$this->db->where($this->db->dbprefix('petty_cash') . '.deleted', 0);
        $this->db->order_by('petty_cash_time');
        $data= $this->db->get()->row_array();
		
		$return = array(
			
			'total' =>  $data["total"]
		);
		
		
		return $return;
	}
}
?>