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
			'summary' => array(array('data'=>lang('login_store'), 'align'=>'left'), array('data'=>'V. '.lang('sales_cash'), 'align'=>'left'), array('data'=>lang('datafonos_tarjetas'), 'align'=>'left'), array('data'=>'V. '.lang('other_media'), 'align'=>'left'), array('data'=>'V. '.lang('credito'), 'align'=>'left'), array('data'=>lang('expenses'), 'align'=>'left'), array('data'=>lang('sales_total'), 'align'=>'right'))
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
                'gastos'=>$this->get_gastos_movimientos('gastos',$location["location_id"]));

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
            $this->db->where('sales_payments.payment_type','Efectivo');
        }
        else if($type=='datafono'){
            $this->db->where('(phppos_sales_payments.payment_type="Tarjeta de Crédito" OR phppos_sales_payments.payment_type="Tarjeta de Débito")');
        }else if($type=="credito"){
            $this->db->where('sales_payments.payment_type','Línea de crédito');
        }else{
            $this->db->where('sales_payments.payment_type !=','Efectivo');
            $this->db->where('sales_payments.payment_type !=','Tarjeta de Crédito');
            $this->db->where('sales_payments.payment_type !=','Tarjeta de Débito');
            $this->db->where('sales_payments.payment_type !=','Línea de crédito');
        }
        $this->db->where('sales_payments.payment_date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"');
            
        $result=$this->db->get()->result_array();
        return $result[0]['suma'];
    }
    public function get_gastos_movimientos($type='gastos',$location_id){
        if($type=='gastos'){
            $this->db->select('sum(phppos_registers_movement.mount) as suma');
        }
        else if($type=='caja_abierta'){
            $this->db->select('sum(phppos_registers_movement.mount_cash) as suma');
        }
        
        $this->db->from('registers_movement');
        $this->db->join('register_log', 'register_log.register_log_id = registers_movement.register_log_id');
        $this->db->join('registers', 'registers.register_id = register_log.register_id');
        $this->db->join('locations', 'locations.location_id = registers.location_id');
        
        if($type=='gastos'){
            $this->db->where('registers_movement.type_movement',0);
        }
        else if($type=='caja_abierta'){
            $this->db->where('registers_movement.type_movement',2);
            $this->db->where('register_log.employee_id_close',null);
        }
        $this->db->where('locations.location_id',$location_id);
        
        $this->db->where('registers_movement.register_date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"');
            
        $result=$this->db->get()->result_array();
        return $result[0]['suma'];
    }
	
	public function getTotalRows()
	{		
		$this->db->from('locations');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();

	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(total) as total', false);
		$this->db->from('receivings_items_temp');
		
		$this->db->where('receivings_items_temp.transfer_to_location_id is  NOT NULL ');
		
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}

 
}

?>