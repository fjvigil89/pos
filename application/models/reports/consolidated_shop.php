<?php
require_once("report.php");
class Consolidated_shop extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
			'summary' => array(array('data'=>lang('login_store'), 'align'=>'left'), array('data'=>'V. '.lang('sales_cash'), 'align'=>'left'), array('data'=>lang('datafonos_tarjetas'), 'align'=>'left'), array('data'=>'V. '.lang('other_media'), 'align'=>'left'), array('data'=>'V. '.lang('credito'), 'align'=>'left'), array('data'=>lang('expenses'), 'align'=>'left'),array('data'=>lang('move_money_category'), 'align'=>'left'), array('data'=>lang('sales_total'), 'align'=>'right'))
		);		
	}
	
	public function getData()
	{
        $locations=array();
        if($this->params['store_id']!='all'){
            $locations[]=get_object_vars($this->Location->get_info($this->params['store_id']));
        }else{
            $locations= $this->Location->get_all_and_deleted()->result_array();
        }
		$data = array();
		$data['summary'] = array();
		
        $location_name=array();
        $data_shop=array();
        $key=0;
        foreach($locations as $location){
            $location_name[$location["location_id"]]= $location["name"];
            $data_shop[$key]=array('name'=>$location["name"],
                'efectivo'=>$this->get_type_chash('Efectivo',$location["location_id"]),
                'datafono'=>$this->get_type_chash('datafono',$location["location_id"]),
                'credito'=>$this->get_type_chash('credito',$location["location_id"]),
                'otros'=>$this->get_type_chash('otros',$location["location_id"]),
                'gastos'=>$this->get_movimiento_caja(0,$location["location_id"]),
                'traslado'=>$this->get_movimiento_caja(2,$location["location_id"]));

            $key++;

        }
        $data['summary']=$data_shop;
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}		
		
		
		return $data;
    }
    
    public function get_type_chash($type='Efectivo',$location_id){
        
        $this->db->select('sum(phppos_sales_payments.payment_amount) as suma');
        $this->db->from('locations');
        $this->db->join('sales', 'sales.location_id = locations.location_id');
        $this->db->join('sales_payments', 'sales_payments.sale_id = sales.sale_id');
        $this->db->where('locations.location_id',$location_id);
        if($type=='Efectivo'){
            $this->db->where('sales_payments.payment_type',lang('sales_cash'));
        }
        else if($type=='datafono'){
            $this->db->where('(phppos_sales_payments.payment_type="'.lang('sales_credit').'" OR phppos_sales_payments.payment_type="'.lang('sales_debit').'")');
        }else if($type=="credito"){
            $this->db->where('sales_payments.payment_type',lang('sales_store_account'));
        }else{
            $this->db->where('sales_payments.payment_type !=',lang('sales_cash'));
            $this->db->where('sales_payments.payment_type !=',lang('sales_credit'));
            $this->db->where('sales_payments.payment_type !=', lang('sales_debit'));
            $this->db->where('sales_payments.payment_type !=',lang('sales_store_account'));
        }
        $this->db->where('sales_payments.payment_date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"');
        $result=$this->db->get()->row_array();
        return $result['suma'];
    }
    

    public function get_movimiento_caja($type=0,$id_location=1){
        $data = array();
		$this->db->select(' sum(phppos_movement_items_temp.mount) as suma');
		$this->db->from('movement_items_temp');
		//$this->where_categoria();
        $this->db->where('movement_items_temp.type_movement',$type);
        $this->db->where('location_id_tienda',$id_location);
        
		$this->db->order_by('register_movement_id','DESC');
		$data=$this->db->get()->row_array();
        
		return $data['suma'];
    }
	
	public function getTotalRows()
	{		
		$this->db->from('locations');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();

	}
	
	public function getSummaryData($report_data=array())
	{
        $efectivo=0;
        $datafonos=0;
        $otros=0;
        $credito=0;
        $gastos=0;
        $traslado=0;
        $total=0;
		foreach ($report_data['summary'] as $key => $row){
            $efectivo+=$row['efectivo'];
            $datafonos+=$row['datafono'];
            $otros+=$row['otros'];
            $credito+=$row['credito'];
            $gastos+=$row['gastos'];
            $traslado+=$row['traslado'];
            $total+=$row['efectivo']+$row['datafono']+$row['credito']+$row['otros']-$row['gastos'];
            
        }
        $total_data = array(array('data' => lang('sales_total'), 'align' => 'left'),
                    array('data' => to_currency($efectivo, 10), 'align' => 'left'),
                    array('data' => to_currency($datafonos, 10), 'align' => 'left'),
                    array('data' => to_currency($otros, 10), 'align' => 'left'),
                    array('data' => to_currency($credito, 10), 'align' => 'left'),
                    array('data' => to_currency($gastos, 10), 'align' => 'left'),
                    array('data' => to_currency($traslado, 10), 'align' => 'left'),
                    array('data' => to_currency($total), 'align' => 'right'));
                     
		return $total_data;
	}

 
}

?>