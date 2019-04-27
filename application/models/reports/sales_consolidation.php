<?php
require_once("report.php");
class sales_consolidation extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		$columns = array();

		$columns[] = array('data'=>lang('reports_sales_base'), 'align'=> 'right');
		$columns[] = array('data'=>lang('sales_cash'), 'align'=> 'right');
		$columns[] = array('data'=>lang('sales_check'), 'align'=> 'right');
		$columns[] = array('data'=>lang('sales_giftcard'), 'align'=> 'right');
        $columns[] = array('data'=>lang('sales_debit'), 'align'=> 'right');
		$columns[] = array('data'=>lang('sales_credit'), 'align'=> 'right');
		$columns[] = array('data'=>lang('sales_store_account'), 'align'=> 'right');
		$columns[] = array('data'=>lang('sales_store_account_payment'), 'align'=> 'right');
		$columns[] = array('data'=>lang('cash_flows_deposit_money'), 'align'=> 'right');
		$columns[] = array('data'=>lang('cash_flows_xtract_money'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_total'), 'align'=> 'right');

		return $columns;
	}

	public function getData()
	{
		$merged_data = array();

        $data_template = array(
            lang('sales_cash'),
			lang('sales_check'),
			lang('sales_giftcard'),
			lang('sales_debit'),
			lang('sales_credit'),
			lang('sales_store_account'),
            lang('cash_flows_deposit_money'),
            lang('cash_flows_xtract_money')
		);
		//$todos=$this->params['employee_id']=="Todos" ? true: false;

		$this->db->trans_start();

            $this->db->select('register_log.shift_start, register_log.shift_end, register_log.open_amount');
			$this->db->from('register_log');
			//if(!$todos){
				$this->db->where('register_log.employee_id_open = '.$this->db->escape($this->params['employee_id']));
			//}
            $this->db->where('register_log.shift_start BETWEEN'.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
            $this->db->order_by('register_log.shift_start','asc');
            $this->db->limit($this->report_limit);
            $this->db->offset($this->params['offset']);
            $cash_opening = $this->db->get()->result_array();

			$this->db->select('sales_payments.payment_type, sales_payments.payment_date,sales_payments.payment_amount');
			$this->db->from('sales_payments');
			$this->db->join('sales', 'sales_payments.sale_id = sales.sale_id', 'left');
			//if(!$todos){
				$this->db->where('sales.employee_id = '.$this->db->escape($this->params['employee_id']));
			//}
        	$this->db->where('sales_payments.payment_date BETWEEN'.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
			$sales = $this->db->get()->result_array();

			$this->db->select('registers_movement.description,registers_movement.type_movement,registers_movement.register_date,registers_movement.mount');
			$this->db->from('registers_movement');
			$this->db->join('register_log', 'registers_movement.register_log_id = register_log.register_log_id', 'left');
			//if(!$todos){
				$this->db->where('register_log.employee_id_open ='.$this->db->escape($this->params['employee_id']));
			//}
			$this->db->where('registers_movement.register_date BETWEEN'.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
			$data=$this->Appconfig->where_categoria();		
			foreach($data as $categoria){
				$this->db->where('registers_movement.categorias_gastos != '.$this->db->escape($categoria));
			}		
			
			$registers_movements = $this->db->get()->result_array();

			$this->db->select('registers_movement.description,registers_movement.type_movement,registers_movement.register_date,registers_movement.mount');
			$this->db->from('registers_movement');
			$this->db->join('register_log', 'registers_movement.register_log_id = register_log.register_log_id', 'left');
			$this->db->where('register_log.employee_id_open ='.$this->db->escape($this->params['employee_id']));
			$this->db->where('registers_movement.register_date BETWEEN'.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
			$this->db->where('registers_movement.categorias_gastos = '.$this->db->escape(lang("sales_store_account_payment")));
			$registers_movements_credit = $this->db->get()->result_array();

			$this->db->from('petty_cash');    
			$this->db->where('petty_cash.petty_cash_time BETWEEN '.$this->db->escape($this->params['start_date'])." and ".$this->db->escape($this->params['end_date']));
			//$this->db->where('petty_cash.register_id', $register_id);sold_by_employee_id
			$this->db->where('petty_cash.sold_by_employee_id',$this->params['employee_id']);
			$this->db->where($this->db->dbprefix('petty_cash') . '.deleted', 0);
			$petty_cash = $this->db->get()->result_array();
	

		$this->db->trans_complete();
        
        $last_cash_opening = end($cash_opening);

		if($this->db->trans_status() === FALSE){
            return FALSE;
        } 
        elseif($last_cash_opening['shift_end'] == "0000-00-00 00:00:00"   )
        {
            return FALSE;
        }
        else
        {
			if($last_cash_opening===FALSE){
				 $cash_opening[]=array(
					"shift_start"=>$this->params['start_date'],
					"shift_end"=>$this->params['end_date'],
					"open_amount"=>"No establecido"
				 );
			}
        	foreach($cash_opening as $key=>$value){

	            if($value['shift_end'] !== "0000-00-00 00:00:00")
                {

					$merged_data[$key][lang('reports_sales_base')] = is_numeric($value['open_amount'])? to_currency_no_money($value['open_amount']): $value['open_amount'];
					$merged_data[$key][lang('cash_flows_deposit_money')]=0;
					$merged_data[$key][lang('cash_flows_xtract_money')]=0;
					$merged_data[$key][lang("sales_store_account_payment")]=0;				
	                foreach ($data_template as $template)
                    {
	                    $merged_data[$key][$template] = 0;
	                }
		            foreach($sales as $sale )
                    {
		                if( $sale['payment_date'] >= $value['shift_start'] and $sale['payment_date'] <= $value['shift_end']){
							$sale['payment_type'] = strpos($sale['payment_type'], ":") ? substr($sale['payment_type'], 0, strpos($sale['payment_type'], ":")) : $sale['payment_type'];
		                    if(!isset($merged_data[$key][$sale['payment_type']])){
		                        $merged_data[$key][$sale['payment_type']]= 0;
		                    }
							$merged_data[$key][$sale['payment_type']] += $sale['payment_amount'] ;
						}
		            }
		            foreach ($registers_movements as $register_movement){
		                if($register_movement['register_date'] >= $value['shift_start'] and $register_movement['register_date'] <= $value['shift_end']){
							if($register_movement['type_movement']==1){
								$merged_data[$key][lang('cash_flows_deposit_money')]+=$register_movement['mount'] ;
							}else{
								$merged_data[$key][lang('cash_flows_xtract_money')] += $register_movement['mount'] ;
							}
		                }
					}
					/*foreach ($registers_movements_credit as $register_movement){
		                if($register_movement['register_date'] >= $value['shift_start'] and $register_movement['register_date'] <= $value['shift_end']){
							$merged_data[$key][lang('sales_store_account_payment')]+=$register_movement['mount'] ;
						}
					}	*/

					foreach ($petty_cash as $register_movement){
		                if($register_movement['petty_cash_time'] >= $value['shift_start'] and $register_movement['petty_cash_time'] <= $value['shift_end']){
							$merged_data[$key][lang('sales_store_account_payment')]+=$register_movement['monton_total'] ;
						}
					}
					         
		            $merged_data[$key][lang('reports_total')] =
                    (
		            	$merged_data[$key][lang('reports_sales_base')]+
		            	$merged_data[$key][lang('sales_cash')] +
		            	$merged_data[$key][lang('sales_check')] +
		            	$merged_data[$key][lang('sales_giftcard')] +
		            	$merged_data[$key][lang('sales_debit')] +
						$merged_data[$key][lang('sales_credit')] +
						$merged_data[$key][lang("sales_store_account_payment")] +
		            	$merged_data[$key][lang('cash_flows_deposit_money')]
		            ) - $merged_data[$key][lang('cash_flows_xtract_money')];
	        	}
	        }       
        	return $merged_data;
        }
	}


	function getTotalRows()
	{
        $this->db->select('register_log.shift_start, register_log.shift_end, register_log.open_amount');
        $this->db->from('register_log');
        $this->db->where('register_log.employee_id_open = '.$this->db->escape($this->params['employee_id']));
        $this->db->where('register_log.shift_start BETWEEN'.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
        $this->db->where('register_log.shift_end != "0000-00-00 00:00:00"');
        $this->db->order_by('register_log.shift_start','asc');
        return $this->db->count_all_results();
	}


	public function getSummaryData()
	{
		return array();
	}

}
