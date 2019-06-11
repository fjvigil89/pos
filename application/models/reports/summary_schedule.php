<?php
require_once("report.php");
class summary_schedule extends Report

{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		
		$return = array();
		
	
		$columns = array();
		$columns[] = array('data'=>"title", 'align'=> 'left');		
		$columns[] = array('data'=>"detail", 'align'=> 'left');
		$columns[] = array('data'=>"start", 'align'=> 'left');
		$columns[] = array('data'=>"end", 'align'=> 'left');
				
		
		return $columns;
	}
	
	public function getData()
	{
		$data = array();
		$data['details'] = array();		
		$this->db->select(" title,detail, start, end");
		$this->db->from('schedule');				
		$this->db->group_by('title');
		$this->db->order_by('title DESC');
		$ingresos=$this->db->get()->result_array();		
		$data['details']=	$ingresos;
		

		
		return $data;
	}

	public function getTotalRows()
	{
		
		//$this->db->select("create_at");
		$this->db->from('schedule');
		//$this->db->where($where);
		$this->db->group_by('create_at, title');
		$cantidad = $this->db->get()->num_rows();
		
		return $cantidad;
		

	}
	

	
	public function getSummaryData()
	{
		
		$data=array();
		$this->db->select(' title');
		$this->db->from('schedule');
		$this->db->group_by('title');		
	//	$this->where_categoria();
		$data["title"]= $this->db->get()->result_array();
		
		$this->db->select(' start');
		$this->db->from('schedule');				
		$this->db->group_by('start');
		//$this->where_categoria();
		$data["start"]= $this->db->get()->result_array();
		
	
		$this->db->select(' end');
		$this->db->from('schedule');				
		$this->db->group_by('end');
		//$this->where_categoria();
		$data["end"]= $this->db->get()->result_array();
		
	
				
		$this->db->select(' detail');
		$this->db->from('schedule');		
		$data["detail"]= $this->db->get()->result_array();
		
		
		return $data;
	}
}
?>