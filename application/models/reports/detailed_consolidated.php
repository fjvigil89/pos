<?php
require_once("report.php");
class detailed_consolidated extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		$columns = array();
		
		$columns[] = array('data'=>lang('reports_sales_base'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_sales_cash'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_sales_credit'), 'align'=> 'right');
        $columns[] = array('data'=>lang('reports_sales_debit'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_sales_cash_withdraw'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_sales_cash_withdraw'), 'align'=> 'right');
		
		return $columns;		
	}
	
	public function getData()
	{	
        return array();
	}
	
	
	function getTotalRows()
	{		
        return array();
	}
	
	
	public function getSummaryData()
	{
		return array();
	}

}
?>