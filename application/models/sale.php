<?php

class Sale extends CI_Model
{

    public function get_info($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get();
    }
    public function get_info_by_support($support_id)
    {
        $this->db->from('sales');
        $this->db->where('support_id', $support_id);
        return $this->db->get();
    }

    public function get_info_quotes($quote_id)
    {
        $this->db->from('quotes');
        $this->db->where('quote_id', $quote_id);
        return $this->db->get();
    }

    public function is_return($sale_id)
    {
        $this->db->select('sum(quantity_purchased) as quantity');
        $this->db->from('sales_items');
        $this->db->where('sale_id', $sale_id);
        $result= $this->db->get()->row()->quantity;
        $this->db->select('sum(quantity_purchased) as quantity');
        $this->db->from('sales_item_kits');
        $this->db->where('sale_id', $sale_id);
        $result2= $this->db->get()->row()->quantity;
        $quantity=($result==null ? 0 : $result)+($result2 == null ? 0 : $result2);
        return $quantity> 0 ? false: true;
    }

    public function get_cash_sales_total_for_shift($shift_start, $shift_end)
    {
        $sales_totals = $this->get_sales_totaled_by_id($shift_start, $shift_end);
        $register_id = $this->Employee->get_logged_in_employee_current_register_id();

        $this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount, payment_id', false);
        $this->db->from('sales_payments');
        $this->db->join('sales', 'sales_payments.sale_id=sales.sale_id');
        $this->db->where('sales_payments.payment_date >=', $shift_start);
        $this->db->where('sales_payments.payment_date <=', $shift_end);
        $this->db->where('register_id', $register_id);
        $this->db->where($this->db->dbprefix('sales') . '.deleted', 0);
        $this->db->order_by('payment_date');
        


        $payments_by_sale = array();
        $sales_payments = $this->db->get()->result_array();

        foreach ($sales_payments as $row) {
            $payments_by_sale[$row['sale_id']][] = $row;
        }

        $payment_data = $this->Sale->get_payment_data($payments_by_sale, $sales_totals);

        if (isset($payment_data[lang('sales_cash')])) {
            return $payment_data[lang('sales_cash')]['payment_amount'];
        }

        return 0.00;
    }

    public function get_payment_data($payments_by_sale, $sales_totals)
    {
        $payment_data = array();

        $sale_ids = array_keys($payments_by_sale);
        $all_payments_for_sales = $this->_get_all_sale_payments($sale_ids);

        foreach ($all_payments_for_sales as $sale_id => $payment_rows) {
            if (isset($sales_totals[$sale_id])) {
                $total_sale_balance = $sales_totals[$sale_id];
                foreach ($payment_rows as $payment_row) {
                    if ($payment_row['payment_amount'] >= 0) {
                        $payment_amount = $payment_row['payment_amount'] <= $total_sale_balance ? $payment_row['payment_amount'] : $total_sale_balance;
                    } else {
                        $payment_amount = $payment_row['payment_amount'] >= $total_sale_balance ? $payment_row['payment_amount'] : $total_sale_balance;
                    }
                    if (!isset($payment_data[$payment_row['payment_type']])) {
                        $payment_data[$payment_row['payment_type']] = array('payment_type' => $payment_row['payment_type'], 'payment_amount' => 0);
                    }

                    $exists = $this->_does_payment_exist_in_array($payment_row['payment_id'], $payments_by_sale[$sale_id]);

                    if ($total_sale_balance != 0 && $exists) {
                        $payment_data[$payment_row['payment_type']]['payment_amount'] += $payment_amount;
                    }

                    $total_sale_balance -= $payment_amount;
                }
            }
        }

        return $payment_data;
    }
    
    public function _does_payment_exist_in_array($payment_id, $payments)
    {
        foreach ($payments as $payment) {
            if ($payment['payment_id'] == $payment_id) {
                return true;
            }
        }

        return false;
    }

    public function _get_all_sale_payments($sale_ids)
    {
        $return = array();

        if (count($sale_ids) > 0) {
            $this->db->select('sales_payments.*, sales.sale_time,is_invoice,invoice_number,ticket_number');
            $this->db->from('sales_payments');
            $this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
            $this->db->where_in('sales_payments.sale_id', $sale_ids);
            $this->db->order_by('payment_date');

            $result = $this->db->get()->result_array();

            foreach ($result as $row) {
                $return[$row['sale_id']][] = $row;
            }
        }
        return $return;
    }

    public function get_payment_data_grouped_by_sale($payments_by_sale, $sales_totals)
    {
        $payment_data = array();

        $sale_ids = array_keys($payments_by_sale);
        $all_payments_for_sales = $this->_get_all_sale_payments($sale_ids);

        foreach ($all_payments_for_sales as $sale_id => $payment_rows) {
            if (isset($sales_totals[$sale_id])) {
                $total_sale_balance = $sales_totals[$sale_id];

                foreach ($payment_rows as $payment_row) {
                    if ($payment_row['payment_amount'] >= 0) {
                        $payment_amount = $payment_row['payment_amount'] <= $total_sale_balance ? $payment_row['payment_amount'] : $total_sale_balance;
                    } else {
                        $payment_amount = $payment_row['payment_amount'] >= $total_sale_balance ? $payment_row['payment_amount'] : $total_sale_balance;
                    }

                    if (!isset($payment_data[$sale_id][$payment_row['payment_type']])) {
                        $payment_data[$sale_id][$payment_row['payment_type']] = array('sale_id' => $sale_id, 'payment_type' => $payment_row['payment_type'], 'payment_amount' => 0, 'payment_date' => $payment_row['payment_date'], 'sale_time' => $payment_row['sale_time'], 'is_invoice' => $payment_row['is_invoice'], 'ticket_number' => $payment_row['ticket_number'], 'invoice_number' => $payment_row['invoice_number']);
                    }

                    $exists = $this->_does_payment_exist_in_array($payment_row['payment_id'], $payments_by_sale[$sale_id]);

                    if ($total_sale_balance != 0 && $exists) {
                        $payment_data[$sale_id][$payment_row['payment_type']]['payment_amount'] += $payment_amount;
                    }

                    $total_sale_balance -= $payment_amount;
                }
            }
        }

        return $payment_data;
    }
    public function get_petty_cash_totaled_payments($shift_start, $shift_end, $register_id = null)
    {
        if ($register_id == null) {

            $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        }
        //el total tambien  se pude calcular ta petty_cash_payments
        $this->db->select('SUM(monton_total) as total');  
        $this->db->from('petty_cash');    
        $this->db->where('petty_cash.petty_cash_time BETWEEN '.$this->db->escape($shift_start)." and ".$this->db->escape($shift_end));
        $this->db->where('petty_cash.register_id', $register_id);
        $this->db->where($this->db->dbprefix('petty_cash') . '.deleted', 0);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $total = $query->row()->total;
            if ($total != null) {
                return $total;
            }

        }
        return 0;
    }
    public function get_sales_totaled_by_id($shift_start, $shift_end, $register_id = null)
    {
        if ($register_id == null) {

            $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        }

        $this->db->select('sales.sale_id', false);
        $this->db->from('sales');
        $this->db->join('sales_payments', 'sales_payments.sale_id=sales.sale_id');
        $this->db->where('sales_payments.payment_date >=', $shift_start);
        $this->db->where('sales_payments.payment_date <=', $shift_end);
        $this->db->where('register_id', $register_id);
        $this->db->where($this->db->dbprefix('sales') . '.deleted', 0);

        $sale_ids = array();
        $result = $this->db->get()->result();
        foreach ($result as $row) {
            $sale_ids[] = $row->sale_id;
        }

        $sales_totals = array();

        if (count($sale_ids) > 0) {
            $where = 'WHERE ' . $this->db->dbprefix('sales') . '.sale_id IN(' . implode(',', $sale_ids) . ')';
            $this->_create_sales_items_temp_table_query($where);
            $this->db->select('sale_id, SUM(total) as total', false);
            $this->db->from('sales_items_temp');
            $this->db->group_by('sale_id');

            foreach ($this->db->get()->result_array() as $sale_total_row) {
                $sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
            }
        }

        return $sales_totals;
    }

    public function get_sales_totaled_payments($shift_start, $shift_end, $register_id = null)
    {
        if ($register_id == null) {

            $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        }

        $this->db->select('sales.sale_id,sales_payments.payment_type, sales_payments.payment_amount', false);
        $this->db->from('sales');
        $this->db->join('sales_payments', 'sales_payments.sale_id=sales.sale_id');
        $this->db->where('sales_payments.payment_date >=', $shift_start);
        $this->db->where('sales_payments.payment_date <=', $shift_end);
        $this->db->where('register_id', $register_id);
        $this->db->where($this->db->dbprefix('sales') . '.deleted', 0);

        $result = $this->db->get()->result();
        $payments = array();

        foreach ($result as $row) {

            $payments[$row->payment_type][] = $row->payment_amount;

            //echo "tpo de pago: ".$row->payment_type." --- ".$row->payment_amount." <br>";
        }

        return $payments;
    }

    public function get_last_sale_numbers(){
        $locations=$this->Location->get_all();
           
        $data=array();
        foreach ($locations->result() as $location) {
           
            $this->db->select('MAX(invoice_number) as invoice_number,MAX(ticket_number) as ticket_number,location_id',false);
            $this->db->from('sales');          
            $this->db->where('serie_number_invoice', $location->serie_number);
            $this->db->where('location_id',$location->location_id);                
            $result = $this->db->get()->row_array();  
            if($result["invoice_number"]==null){
                $result["invoice_number"]="".($location->start_range-1);
            } 
            if($result["ticket_number"]==null){
                $result["ticket_number"]="".($location->start_range-1);
            }  
            if($result["location_id"]==null){
                $result["location_id"]=$location->location_id;
            } 
            $data[]= $result; 
        }
        
       
        return $data;
    }
    public function get_next_sale_number($sale_type = 0, $location_id)
    {
        
        $invoice_number_info=$this->Location->get_info($location_id);       
            $serie_number=$invoice_number_info->serie_number;
            $increment=(int)$invoice_number_info->increment;
            $start_range=(int) $invoice_number_info->start_range;

        if ($sale_type == 1) {
            $this->db->select_max('invoice_number');
            $this->db->from('sales');
            $this->db->where('location_id', $location_id);
            $this->db->where('serie_number_invoice', $serie_number);
            $row = $this->db->get()->row()->invoice_number;
            if($row == null){
                $row = $start_range;
            }else{
             $row = $row + $increment;
            }
        } else {
            $this->db->select_max('ticket_number');
            $this->db->from('sales');
            $this->db->where('location_id', $location_id);
            $this->db->where('serie_number_invoice', $serie_number);
            $row = $this->db->get()->row()->ticket_number;
            if($row == null){
                $row = $start_range;
            }else{
                $row = $row + $increment;
            }
        }
        return $row;
    }

    public function get_sale_numeration($sale_id)
    {
        $this->db->select('invoice_number,ticket_number,is_invoice');
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return false;
    }

    /**
     * added for cash register
     * insert a log for track_cash_log
     * @param array $data
     */
    public function update_register_log($data)
    {
        $register_id = $this->Employee->get_logged_in_employee_current_register_id();

        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->where('register_id', $register_id);
        return $this->db->update('register_log', $data) ? true : false;
    }
    public function update_register_log_by_id($data,$register_log_id)
    {      
        $this->db->where('register_log_id', $register_log_id);
        return $this->db->update('register_log', $data) ? true : false;
    }

    public function insert_register($data)
    {

        return $this->db->insert('register_log', $data) ? $this->db->insert_id() : false;
    }

    public function is_register_log_open($register_id=false)
    {
        if(!$register_id){
            $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        }
        $this->db->from('register_log');
        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->where('register_id', $register_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
        }

    }
    

    public function get_current_register_log($register_id = false)
    {
        if (!$register_id) {
            $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        }
        $this->db->from('register_log');
        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->where('register_id', $register_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } 
        return false;
    }
    public function get_registers_log_open()
    {
        $this->db->from('register_log');
        $this->db->where('shift_end', '0000-00-00 00:00:00');       
        $query = $this->db->get();
        return $query ;
      }
      public function get_registers_log_by_id($register_log_id)
      {
          $this->db->from('register_log');
          $this->db->where('register_log_id', $register_log_id);       
          $query = $this->db->get();
          return $query->row() ;
        }
        public function get_registers_log_by_id_synchronized($synchronizations_offline_id,$register_log_id_offline)
      {
          $this->db->from('register_log');
          $this->db->where('synchronizations_offline_id',$synchronizations_offline_id);
          $this->db->where('register_log_id_offline', $register_log_id_offline );       
          $query = $this->db->get();
          if ($query->num_rows()>0) {
            return $query->row();
        } 
        return false;
         
        }


    public function get_register_log($register_log_id = false)
    {
        $this->db->from('register_log');
        $this->db->where('register_log_id', $register_log_id);
        $query = $this->db->get();

        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }

    }

    public function get_current_register_log_person($session_id)
    {
        $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        $this->db->from('register_log');
        $this->db->where('register_id', $register_id);
        $this->db->where('employee_id_open', $session_id);
        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->order_by('register_log_id', 'Desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } 
        return false; 

    }

    public function get_current_register_log_date($date)
    {
        $register_id = $this->Employee->get_logged_in_employee_current_register_id();
        $this->db->from('register_log');
        $this->db->where('shift_start', $date);
        $this->db->where('register_id', $register_id);
        $register_log = $this->db->get()->row();

        return $register_log;
    }

    public function exists($sale_id, $location_id=false)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        if($location_id){
            $this->db->where('location_id', $location_id);
        }
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }
    public function exists_offline($sale_id_offline, $synchronizations_offline_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id_offline', $sale_id_offline);
        $this->db->where('synchronizations_offline_id', $synchronizations_offline_id);
        $query = $this->db->get();
        return ($query->num_rows() == 1);
    }

    public function exists_register_log($register_log_id){
        $this->db->from('register_log');
        $this->db->where('register_log_id', $register_log_id);
        $result = $this->db->get();
        if ($result->num_rows()>0){
            return true;
        }
        return false;

    }
    public function update($sale_data, $sale_id)
    {
        $this->db->where('sale_id', $sale_id);
        $success = $this->db->update('sales', $sale_data);

        return $success;
    }
    public function save_offline($data){
    //    $this->load->model('Synchronization_offline');
        $this->db->query("SET autocommit=0");
        //Lock tables invovled in sale transaction so we don't have deadlock 
        $this->db->query('LOCK TABLES ' . $this->db->dbprefix('customers') . ' WRITE, ' . $this->db->dbprefix('sales') . ' WRITE,
		' . $this->db->dbprefix('store_accounts') . ' WRITE, ' . $this->db->dbprefix('sales_payments') . ' WRITE, ' . $this->db->dbprefix('sales_items') . ' WRITE,
		' . $this->db->dbprefix('giftcards') . ' WRITE, ' . $this->db->dbprefix('location_items') . ' WRITE,
		' . $this->db->dbprefix('inventory') . ' WRITE, ' . $this->db->dbprefix('sales_items_taxes') . ' WRITE,
		' . $this->db->dbprefix('sales_item_kits') . ' WRITE, ' . $this->db->dbprefix('sales_item_kits_taxes') . ' WRITE,' . $this->db->dbprefix('people') . ' READ,' . $this->db->dbprefix('items') . ' READ
		, ' . $this->db->dbprefix('employees_locations') . ' READ,' . $this->db->dbprefix('locations') . ' READ, ' . $this->db->dbprefix('items_tier_prices') . ' READ
		, ' . $this->db->dbprefix('location_items_tier_prices') . ' READ, ' . $this->db->dbprefix('items_taxes') . ' READ, ' . $this->db->dbprefix('item_kits') . ' READ
		, ' . $this->db->dbprefix('location_item_kits') . ' READ, ' . $this->db->dbprefix('item_kit_items') . ' READ, ' . $this->db->dbprefix('employees') . ' READ , ' . $this->db->dbprefix('item_kits_tier_prices') . ' READ
		, ' . $this->db->dbprefix('location_item_kits_tier_prices') . ' READ, ' . $this->db->dbprefix('location_items_taxes') . ' READ
        , ' . $this->db->dbprefix('location_item_kits_taxes') . ' READ, ' . $this->db->dbprefix('item_kits_taxes') . ' READ
        , '. $this->db->dbprefix('items_subcategory') . ' WRITE ,'.$this->db->dbprefix('employees') . ' WRITE 
        , '. $this->db->dbprefix('registers_movement') . ' WRITE ,'.$this->db->dbprefix('register_log') . ' WRITE ');

        $sale_id=false;
        $respuesta=array();
        $error=false;
       
        foreach($data as $sale){
            $mode = $sale["mode"];
+           $total_sale = $sale["total"];
            $error=false;
            $mensaje= array('mensaje' => "Venta sincronizada con éxito.","error"=>false,"sale_id_offline"=>$sale["sale_id"],"extra"=>"");
           $guardar_venta=true;
            if($this->exists_offline($sale["sale_id"], $sale["id_synchronization"])){
                $mensaje['mensaje'] = "La venta ya estaba sincronizada.";
                $mensaje["error"]=false;
                $guardar_venta=false;
               // $respuesta[]=$mensaje;
               // break;
            }
            if(!$this->Synchronization_offline->exists($sale["token"], $sale["id_synchronization"])){
                $mensaje['mensaje'] = "Esta venta no se realizó con ningun sincronización válida.";
                $mensaje["error"]=true;
                $guardar_venta=false;
                //$respuesta[]=$mensaje;
                //break;
            }
           
            $location = $this->Location->get_info($sale["location_id"]);
            $serie_number = $location->serie_number==""?1:$location->serie_number;
            if($sale["is_invoice"] == 1)
            {               
                $sale['ticket_number']  = $this->Sale->get_next_sale_number(0,$sale["location_id"]);
                $sale['serie_number_invoice']= $serie_number;
            }
            else
            {
                $sale['invoice_number'] = $this->Sale->get_next_sale_number(1,$sale["location_id"]);               
                $sale['serie_number_invoice']= $serie_number;
            }
            $sales_data = array(
                'customer_id' =>  $sale["customer_id"] > 0 ? $sale["customer_id"] : null, 
                'employee_id' => $sale["employee_id"],
                'sold_by_employee_id' => $sale["sold_by_employee_id"],
                'payment_type' => $sale["payment_type"],
                'comment' => $sale["comment"],
                'show_comment_on_receipt' => $sale["show_comment_on_receipt"],
                'suspended' => $sale["suspended"],
                'deleted' => $sale["deleted"],
                'deleted_by' => ($sale["deleted_by"]!="" and $sale["deleted_by"]!=null) ? $sale["deleted_by"]:null ,
                'register_id' =>  $sale["register_id"],
                'cc_ref_no' =>  $sale["cc_ref_no"], 
                'auth_code' =>  $sale["auth_code"],
                'store_account_payment' =>  $sale["store_account_payment"],
                'tier_id' =>  $sale["tier_id"] > 0? $sale["tier_id"]  : null,
                'invoice_number' =>  $sale["invoice_number"],
                'ticket_number' =>  $sale["ticket_number"],
                'is_invoice' => $sale["is_invoice"],
                'ntable' => $sale["ntable"]!="" ?$sale["ntable"]:null,            
                "divisa"=>$sale["divisa"]!="" ?$sale["divisa"]:null,
                "opcion_sale"=>$sale["opcion_sale"]!="" ?$sale["opcion_sale"]:null,
                "transaction_rate"=>$sale["transaction_rate"]!=""?$sale["transaction_rate"]:null,
                "synchronizations_offline_id"=>$sale["id_synchronization"],
                "sale_id_offline"=>$sale["sale_id"]                
            );
            $sales_data['location_id'] =$sale["location_id"];  

            if ($guardar_venta ==true and !$this->db->insert('sales', $sales_data)) {
                $mensaje['mensaje'] = "No fué posible guardar los datos de la venta.";
                $mensaje["error"]=true;    
            }else if ($guardar_venta ==true){
                $sale_id = $this->db->insert_id(); 
                // se garegan los movimientos por venta
                if ($this->config->item('track_cash') ==1){ 
                    //if($this->is_register_log_open($sale["register_id"])){
                    $sale["movement_register"] = isset($sale["movement_register"]) ? $sale["movement_register"]: array();
                    foreach($sale["movement_register"] as $movement){ 
                        $register_log_id=null;
                        if($movement["register_log_created_online"]==1){// si la caja se abrió en linea 
                            if(!$this->exists_register_log($movement["register_log_id"])){
                                $mensaje['mensaje'] = "No hay caja relacionada a esta venta";
                                $mensaje["error"]=true;                                     
                                break;
                            }else{
                                $register_log_id= $movement["register_log_id"] ;
                            }
                        }else {// cuando se abre  offline
                           $_register=$this->get_registers_log_by_id_synchronized($movement["id_synchronization"],$movement["register_log_id"]);
                           if($_register){
                            $register_log_id= $_register->register_log_id;
                           }else{
                            $mensaje['mensaje'] = "No hay caja offline relacionada a esta venta";
                            $mensaje["error"]=true;                                     
                            break; 
                           }
                        
                        }
                        $mount=  $movement["mount"];
                        $this->Register_movement->save(
                                $mount, $movement["description"],$movement["register_id"],true,
                                $movement["categorias_gastos"], $movement["id_employee"],false,
                                $movement["register_date"],$register_log_id); //Registrar movimiento
                        
                    }                  
                }
                // items
                $sale["sales_items"] = isset($sale["sales_items"])?$sale["sales_items"]: array();
               
                foreach($sale["sales_items"] as $item){
                    $item["sale_id"]=$sale_id;
                    $is_serialized=  $item["is_serialized"];
                    $is_promo=$item["is_promo"];
                    unset($item["is_promo"]);
                    unset($item["is_serialized"]);
                    if ($mensaje["error"]==false and !$this->db->insert('sales_items', $item)) {                       
                        $mensaje['mensaje'] = "No fue posible de guardar los datos productos.";
                        $mensaje["error"]=true;                        
                        break;                        
                    }
                    $cur_item_info = $this->Item->get_info($item['item_id']);
                    $cur_item_location_info = $this->Item_location->get_info($item['item_id']);
    
                    if (!$cur_item_info->is_service and $mensaje["error"]==false) {
                        $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                        if (!$this->Item_location->save_quantity($cur_item_location_info->quantity - $item['quantity_purchased'], $item['item_id'])) {
                            $mensaje['mensaje'] = "No fue posible de guardar la cantida productos.";
                            $mensaje["error"]=true;                        
                            break;                
                        }
                        if ($mensaje["error"]==false and $this->config->item('subcategory_of_items') and $cur_item_info->subcategory==1) {
                            $subcategory = $this->items_subcategory->get_info($item['item_id'], false, $item['custom1_subcategory'], $item['custom2_subcategory']);
                            $quantity_subcategory = $subcategory->quantity;
                            if(!$this->items_subcategory->save_quantity(($quantity_subcategory-$item['quantity_purchased']), 
                                $item['item_id'], false, $item['custom1_subcategory'],$item['custom2_subcategory'])){                                
                            }
                        }
                        if ($is_serialized==1 and $mensaje["error"]==false) {                            
                            $this->Additional_item_seriales-> delete_serial($item['item_id'],$item["serialnumber"]);
                        }
                    }
                    if($is_promo==1){
                        $this->drecrease_promo_quantity($item["quantity_purchased"],$item["item_id"]);
                    }
                    
                }
                if($mensaje["error"]==false and $sales_data["customer_id"]!=null and $sale['balance']!= 0  and $sales_data["customer_id"]>0)
		        {
                    $this->db->set('balance', 'balance+' . $sale['balance'], false);
                    $this->db->where('person_id',$sales_data["customer_id"]);                    
                    if ($this->db->update('customers')) {
                        $store_account_transaction =isset($sale["store_account_transaction"])? $sale["store_account_transaction"]:array();
                        if (!$this->db->insert('store_accounts',$store_account_transaction))
                        {
                            $mensaje['mensaje'] = "No fue posible agregar los movimientos a la cuanta del cliente.";
                            $mensaje["error"]=true;                    
                        }	
                    }else{
                        $mensaje['mensaje'] = "No fue posible agregar el saldo a la cuanta del cliente.";
                        $mensaje["error"]=true;
                    }          	

		        }
                // items impuestos
                $sale["sale_items_taxes"] = isset($sale["sale_items_taxes"])?$sale["sale_items_taxes"]: array();
                foreach($sale["sale_items_taxes"] as $items_taxes){
                    $items_taxes["sale_id"]=$sale_id;
                                      
                    if ($mensaje["error"]==false and  !$this->db->insert('sales_items_taxes', $items_taxes)) {
                        $mensaje['mensaje'] = "No fue posible de guardar los impuestos de los productos.";
                        $mensaje["error"]=true;
                        break;                        
                    }
                }
                // items kit
                $sale["sales_item_kits"] = isset($sale["sales_item_kits"])?$sale["sales_item_kits"]: array();

                foreach($sale["sales_item_kits"] as $item_kit){
                    $item_kit["sale_id"]=$sale_id;
                    if ( $mensaje["error"]==false and !$this->db->insert('sales_item_kits', $item_kit)) {
                        $mensaje['mensaje'] = "No fue posible de guardar lo(s) kit(s).";
                        $mensaje["error"]=true; 
                       
                        break;                        
                    }
                    foreach ($this->Item_kit_items->get_info($item_kit['item_kit_id']) as $item_kit_item) {
                        $cur_item_info = $this->Item->get_info($item_kit_item->item_id);
                        $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);

                        if (!$cur_item_info->is_service) {
                            $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                            if (!$this->Item_location->save_quantity($cur_item_location_info->quantity - ($item_kit['quantity_purchased'] * $item_kit_item->quantity), $item_kit_item->item_id)) {
                                $mensaje['mensaje'] = "No fue posible la cantida kit(s).";
                                $mensaje["error"]=true; 
                                break;
                            }
                            
                        }

                    }
                }
                // items kit impuestos
                $sale["sales_item_kits_taxes"] = isset($sale["sales_item_kits_taxes"])?$sale["sales_item_kits_taxes"]: array();

                foreach($sale["sales_item_kits_taxes"] as $items_kit_taxes){
                    $items_kit_taxes["sale_id"]=$sale_id;
                    if ($mensaje["error"]==false and !$this->db->insert('sales_item_kits_taxes',$items_kit_taxes)) {
                        $mensaje['mensaje'] = "No fue posible de guardar los impuestos de los  Kit(s).";
                        $mensaje["error"]=true;
                        
                        break;                                               
                    }
                }
                // metodos de pagos
                $sale["sales_payments"] = isset($sale["sales_payments"])?$sale["sales_payments"]: array();

                foreach($sale["sales_payments"] as $payments){
                    $payments["sale_id"]=$sale_id;            
                    if ( $mensaje["error"]==false and !$this->db->insert('sales_payments', $payments)) {
                        $mensaje['mensaje'] = "No fue posible de guardar las formas de pagos.";
                        $mensaje["error"]=true; 
                        break;                        
                    }
                }
                $sale["inv_data"] = isset($sale["inv_data"])?$sale["inv_data"]: array();

                foreach($sale["inv_data"] as $inv_data){
                    $inv_data["trans_comment"]=($inv_data["trans_comment"]." ".$sale_id);
                    if ($mensaje["error"]==false and !$this->Inventory->insert($inv_data)) {
                        $mensaje['mensaje'] = "No fue posible de guardar los movimientos en el inventario.";
                        $mensaje["error"]=true; 
                        break;                
                    }
                }
            }
            $system_point= $this->config->item('system_point');
           if($system_point and  $mode=="sale")
		    {
                if($sales_data["customer_id"]>0){

                    $this->load->library('sale_lib');                
                    $this->load->model('points');
                    $total=$total_sale;               
                    $point_pucharse = $this->sale_lib->get_point($this->config->item('value_point'),$total);
                    $detail = 'Id venta #';
                    $this->points->save_point($point_pucharse,$sales_data["customer_id"],$detail);
                    $data['points']=$this->Customer->get_info_points($sales_data["customer_id"]);
                }
		    }
            if($mensaje["error"]==true){
                $this->db->query("ROLLBACK");
            }
            $respuesta[]=$mensaje;

        }
        $this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');
        return $respuesta;

    }

    public function save($items, $customer_id, $employee_id, $sold_by_employee_id, $comment,
     $show_comment_on_receipt, $payments, $sale_id = false, $suspended = 0, $cc_ref_no = '', 
     $auth_code = '', $change_sale_date = false, $balance = 0,$mode="sale" ,$tier_id=false, 
     $deleted_taxes=array(),$store_account_payment = 0,
      $total = 0, $amount_change, $invoice_type, $ntbale = null,$divisa=null, 
      $opcion_sale=null, $transaction_rate=null, $transaction_cost=null, $another_currency=0,
      $currency=null, $total_other_currency=null , $overwrite_tax=false,$new_tax=null,$value_other_currency=null,
      $is_servicio_tecnico=false,$support_id=null)
    {
        //we need to check the sale library for deleted taxes during sale
        //$this->load->library('sale_lib');

        if (count($items) == 0) {
            return -1;
        }

        $payment_types = '';
        foreach ($payments as $payment_id => $payment) {
            $payment_types = $payment_types . $payment['payment_type'] . ': ' . to_currency($payment['payment_amount']) . '<br />';
    
        }
        if (!$tier_id) {
            $tier_id = null;
        }

        $sales_data = array(
            'customer_id' => $customer_id > 0 ? $customer_id : null,
            'employee_id' => $employee_id,
            'sold_by_employee_id' => $sold_by_employee_id,
            'payment_type' => $payment_types,
            'comment' => $comment,
            'show_comment_on_receipt' => $show_comment_on_receipt ? $show_comment_on_receipt : 0,
            'suspended' => $suspended,
            'deleted' => 0,
            'deleted_by' => null,
            'register_id' => $this->Employee->get_logged_in_employee_current_register_id(),
            'cc_ref_no' => $cc_ref_no,
            'auth_code' => $auth_code,
            'store_account_payment' => $store_account_payment,
            'tier_id' => $tier_id ? $tier_id : null,
            'invoice_number' => $invoice_type['invoice_number'],
            'ticket_number' => $invoice_type['ticket_number'],
            'is_invoice' => $invoice_type['is_invoice'],
            'serie_number_invoice'=>$invoice_type['serie_number_invoice'],
            'ntable' => $ntbale,            
            "divisa"=>$divisa,
            "opcion_sale"=>$opcion_sale,
            "transaction_rate"=>$transaction_rate, // tasa puesta por la casa de cambio 
            "another_currency"=>$another_currency,
            "currency"=>$currency,
            "total_other_currency"=>$total_other_currency,
            "overwrite_tax"=>$overwrite_tax,
            "value_other_currency"=>$value_other_currency,
            "support_id"=>$support_id
            
        );

        $sale_info = $this->get_info($sale_id)->row();

        if ($this->config->item('track_cash') == 1 && $sales_data['suspended'] != 1) {

            $cash = $this->get_payment_cash($payments); //Obtener pagos en efectivo de la venta/devolucion actual

            if ($mode== 'sale') {
                $description = "Venta";
                $categorias_gastos="Venta";
            } elseif ($mode == 'return') {
                $description = "Devolución";
                $categorias_gastos="Devolución";
                $cash = $cash * (-1);
            }

            if ($sale_id) { //Si la operacion es editar o una devolucion
                $cash = $this->get_diff_sale_cash($sale_id, $cash);
            }

            if ($amount_change > 0) {
                $cash = $cash - $amount_change;
            }
// se registran los movimiento de la caja
            
            $this->Register_movement->save($cash, $description,false,true,$categorias_gastos,false); 
                     //Registrar movimiento
        }

        if ($sale_id) {

            $sales_data['sale_time'] = $sale_info->sale_time;
            if ($change_sale_date) {
                $sale_time = strtotime($change_sale_date);
                if ($sale_time !== false) {
                    $sales_data['sale_time'] = date('Y-m-d H:i:s', strtotime($change_sale_date));
                }
            }
        } else {

            $sales_data['sale_time'] = date('Y-m-d H:i:s');
            $sales_data['location_id'] = $this->Employee->get_logged_in_employee_current_location_id();
            //$sales_data['register_id'] = $this->Employee->get_logged_in_employee_current_register_id();
        }
            
        $this->db->query("SET autocommit=0");
        //Lock tables invovled in sale transaction so we don't have deadlock 
        $this->db->query('LOCK TABLES ' . $this->db->dbprefix('customers') . ' WRITE, ' . $this->db->dbprefix('sales') . ' WRITE,
		' . $this->db->dbprefix('store_accounts') . ' WRITE, ' . $this->db->dbprefix('sales_payments') . ' WRITE, ' . $this->db->dbprefix('sales_items') . ' WRITE,
		' . $this->db->dbprefix('giftcards') . ' WRITE, ' . $this->db->dbprefix('location_items') . ' WRITE,
		' . $this->db->dbprefix('inventory') . ' WRITE, ' . $this->db->dbprefix('sales_items_taxes') . ' WRITE,
		' . $this->db->dbprefix('sales_item_kits') . ' WRITE, ' . $this->db->dbprefix('sales_item_kits_taxes') . ' WRITE,' . $this->db->dbprefix('people') . ' READ,' . $this->db->dbprefix('items') . ' READ
		,' . $this->db->dbprefix('employees_locations') . ' READ,' . $this->db->dbprefix('locations') . ' READ, ' . $this->db->dbprefix('items_tier_prices') . ' READ
		, ' . $this->db->dbprefix('location_items_tier_prices') . ' READ, ' . $this->db->dbprefix('items_taxes') . ' READ, ' . $this->db->dbprefix('item_kits') . ' READ
		, ' . $this->db->dbprefix('location_item_kits') . ' READ, ' . $this->db->dbprefix('item_kit_items') . ' READ, ' . $this->db->dbprefix('employees') . ' READ , ' . $this->db->dbprefix('item_kits_tier_prices') . ' READ
		, ' . $this->db->dbprefix('location_item_kits_tier_prices') . ' READ, ' . $this->db->dbprefix('location_items_taxes') . ' READ
        , ' . $this->db->dbprefix('location_item_kits_taxes') . ' READ, ' . $this->db->dbprefix('item_kits_taxes') . ' READ
        ,'. $this->db->dbprefix('items_subcategory') . ' WRITE ,'.$this->db->dbprefix('employees') . ' WRITE ');

        $previous_store_account_amount = 0;

        if ($sale_id) 
        {         
            //Delete previoulsy sale so we can overwrite data
            if (!$this->delete($sale_id, true)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
            
            $this->db->where('sale_id', $sale_id);
            if (!$this->db->update('sales', $sales_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
        } else {
            $result = $this->db->insert('sales', $sales_data);
            if (! $result) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
            $sale_id = $this->db->insert_id();
        }
        if( $this->config->item('activar_casa_cambio')==true  && $mode != 'Devolución'){
            // se edita el saldo del empleado
            $emp_info = $this->Employee->get_info($sold_by_employee_id);
           
            if($emp_info->type=="credito"){
                if($emp_info->account_balance<$total){
                    return -1;
                }
                $status = $this->movement_balance->save_movement(-abs($transaction_cost),null , $sold_by_employee_id ,1,$opcion_sale );
                $status =( $status && $this->movement_balance->update_balance_employee(-abs($transaction_cost),  $sold_by_employee_id));
                if (!$status ) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }
            }           

        }
        
      
        $total_giftcard_payments = 0; 
        $amount_change_aux = $amount_change;
        foreach ($payments as $payment_id => $payment) {
            //Only update giftcard payments if we are NOT an estimate (suspended = 2)
            if ($suspended != 2) {
                if (substr($payment['payment_type'], 0, strlen(lang('sales_giftcard'))) == lang('sales_giftcard')) {
                    /* We have a gift card and we have to deduct the used value from the total value of the card. */
                    $splitpayment = explode(':', $payment['payment_type']);
                    $cur_giftcard_value = $this->Giftcard->get_giftcard_value($splitpayment[1]);

                    $this->Giftcard->update_giftcard_value($splitpayment[1], $cur_giftcard_value - $payment['payment_amount']);
                    $total_giftcard_payments += $payment['payment_amount'];
                }
            }
            $sales_payments_data = array
                (
                'sale_id' => $sale_id,
                'payment_type' => $payment['payment_type'],
                'payment_amount' => $payment['payment_amount'],
                'payment_date' => $payment['payment_date'],
                'truncated_card' => $payment['truncated_card'],
                'card_issuer' => $payment['card_issuer'],
            );
            // si se permite solo $amount_change en efectivo se coloca el if, de lo contrario no
            if ($payment['payment_type'] == lang('sales_cash')) {

                if ($payment['payment_amount'] <= $amount_change_aux) {

                    $amount_change_aux = $amount_change_aux - $payment['payment_amount'];
                    $sales_payments_data['payment_amount'] = 0;

                } else if ($payment['payment_amount'] > $amount_change_aux) {

                    $sales_payments_data["payment_amount"] = $sales_payments_data["payment_amount"] - $amount_change_aux;
                    $amount_change_aux = 0;
                }
            }

            if (!$this->db->insert('sales_payments', $sales_payments_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }

        }
         

        $has_added_giftcard_value_to_cost_price = $total_giftcard_payments > 0 ? false : true;
        $store_account_item_id = $this->Item->get_store_account_item_id();

        foreach ($items as $line => $item) {
            if (isset($item['item_id'])) {
                $cur_item_info = $this->Item->get_info($item['item_id']);
                $cur_item_location_info = $this->Item_location->get_info($item['item_id']);

                $item['quantity'] = $mode == 'return' ? -$item['quantity'] : $item['quantity'];

                if ($item['item_id'] != $store_account_item_id) {
                    $cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;
                } else { // Set cost price = price so we have no profit
                    $cost_price = $item['price'];
                }

                if (!$this->config->item('disable_subtraction_of_giftcard_amount_from_sales')) {
                    //Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
                    if (!$has_added_giftcard_value_to_cost_price) {
                        $cost_price += $total_giftcard_payments / $item['quantity'];
                        $has_added_giftcard_value_to_cost_price = true;
                    }
                }
                $reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;

                if ($cur_item_info->tax_included) {
                    $item['price'] = get_price_for_item_excluding_taxes($item['item_id'], $item['price']);
                }
                $sales_items_data = array
                    (
                    'sale_id' => $sale_id,
                    'item_id' => $item['item_id'],
                    'line' => $item['line'],
                    'description' => $item['description'],
                    'serialnumber' => $item['serialnumber'],
                    'quantity_purchased' => $item['quantity'],
                    'discount_percent' => $item['discount'],
                    'item_cost_price' => to_currency_no_money($cost_price, 10),
                    'item_unit_price' => $item['price'],
                    'commission' => get_commission_for_item($item['item_id'], $item['price'], $item['quantity'], $item['discount']),
                    'custom1_subcategory' => $item['custom1_subcategory'],
                    'custom2_subcategory' => $item['custom2_subcategory'],
                    'numero_cuenta' => $item['numero_cuenta'],
                    'numero_documento' => $item['numero_documento'],
                    'titular_cuenta' => $item['titular_cuenta'],
                    "tasa"=>$item["tasa"],
                    "tipo_documento"=>$item["tipo_documento"],
                    "id_tier"=>$item["id_tier"],                    
                    "transaction_status"=>$item["transaction_status"],
                    "fecha_estado"=>$item["fecha_estado"],
                    "comentarios"=>$item["comentarios"],
                    "tipo_cuenta"=>$item["tipo_cuenta"],
                    "observaciones"=>$item["observaciones"],
                    "celular"=>$item["celular"],
                    "has_sales_units" => $item["has_sales_units"],
				    "name_unit"=> $item["name_unit"],
				    "has_selected_unit" => $item["has_selected_unit"],
				    "unit_quantity_presentation" => $item["unit_quantity_presentation"],
				    "unit_quantity_item" => $item["unit_quantity_item"],
				    "unit_quantity"	=> $item["unit_quantity"],	
                    "price_presentation" => $item["price_presentation"],
                    "unit_measurement" => $item["unit_measurement"]

                );

                if (!$this->db->insert('sales_items', $sales_items_data)) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }
                

                //Only update giftcard payments if we are NOT an estimate (suspended = 2)
                if ($suspended != 2) {
                    //create giftcard from sales
                    if ($item['name'] == lang('sales_giftcard') && !$this->Giftcard->get_giftcard_id($item['description'])) {
                        $giftcard_data = array(
                            'giftcard_number' => $item['description'],
                            'value' => $item['price'],
                            'customer_id' => $customer_id > 0 ? $customer_id : null,
                        );

                        if (!$this->Giftcard->save($giftcard_data)) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }
                    }
                }

                //Only do stock check + inventory update if we are NOT an estimate
                if ($suspended != 2) {
                    $stock_recorder_check = false;
                    $out_of_stock_check = false;
                    $email = false;
                    $message = '';

                    //checks if the quantity is greater than reorder level
                    if (!$cur_item_info->is_service && $cur_item_location_info->quantity > $reorder_level) {
                        $stock_recorder_check = true;
                    }

                    //checks if the quantity is greater than 0
                    if (!$cur_item_info->is_service && $cur_item_location_info->quantity > 0) {
                        $out_of_stock_check = true;
                    }

                    //Update stock quantity IF not a service
                    if (!$cur_item_info->is_service) {
                        $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                        if (!$this->Item_location->save_quantity($cur_item_location_info->quantity - $item['quantity'], $item['item_id'])) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }
                        if ($this->config->item('subcategory_of_items') && $item['has_subcategory']==1) {
                            $subcategory = $this->items_subcategory->get_info($item['item_id'], false, $item['custom1_subcategory'], $item['custom2_subcategory']);
                            $quantity_subcategory = $subcategory->quantity;
                            if(!$this->items_subcategory->save_quantity(($quantity_subcategory-$item['quantity']), 
                            $item['item_id'], false, $item['custom1_subcategory'],$item['custom2_subcategory'])){
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }

                        }
                        
                    }
                    if ( $item['is_serialized']==1) {
                            
                        if(!$this->Additional_item_seriales-> delete_serial($item['item_id'],$item["serialnumber"])){
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }

                    }

                    //Re-init $cur_item_location_info after updating quantity
                    $cur_item_location_info = $this->Item_location->get_info($item['item_id']);

                    //checks if the quantity is out of stock
                    if ($out_of_stock_check && $cur_item_location_info->quantity <= 0) {
                        $message = $cur_item_info->name . ' ' . lang('sales_is_out_stock') . ' ' . to_quantity($cur_item_location_info->quantity);
                        $email = true;
                    }
                    //checks if the quantity hits reorder level
                    else if ($stock_recorder_check && ($cur_item_location_info->quantity <= $reorder_level)) {
                        $message = $cur_item_info->name . ' ' . lang('sales_hits_reorder_level') . ' ' . to_quantity($cur_item_location_info->quantity);
                        $email = true;
                    }

                    //send email
                    if ($this->Location->get_info_for_key('receive_stock_alert') && $email) {
                        /*
                        $this->load->library('email');
                        $config = array();
                        $config['mailtype'] = 'text';
                        $this->email->initialize($config);
                        $this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
                        $this->email->to($this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email'));

                        $this->email->subject(lang('sales_stock_alert_item_name') . $this->Item->get_info($item['item_id'])->name);
                        $this->email->message($message);
                        $this->email->send();
                        */
                        $this->load->library('Email_send');
                        $para=$this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email');
                        $subject=lang('sales_stock_alert_item_name') . $this->Item->get_info($item['item_id'])->name;
                        $name="";
                        $company=$this->config->item('company');
                        $from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
                        $this->email_send->send_($para, $subject, $name,$message,$from,$company) ;
                    }

                    if (!$cur_item_info->is_service) {
                        $qty_buy = -$item['quantity'];

                        //pedido decuento en tienda online---------------------------------------- 
                        if (!empty($this->sale_lib->get_order())) {
                            $sale_remarks = 'PEDIDO ' . $this->sale_lib->get_order();  
                        }else{
                           $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id; 
                        }
                        //------------------------------------------------------------------------
                        $inv_data = array
                            (
                            'trans_date' => date('Y-m-d H:i:s'),
                            'trans_items' => $item['item_id'],
                            'trans_user' => $employee_id,
                            'trans_comment' => $sale_remarks,
                            'trans_inventory' => $qty_buy,
                            'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
                        );
                        if (!$this->Inventory->insert($inv_data)) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }

                    }
                }
            } else {//  para los kits
                $item['quantity'] = $mode == 'return' ? -$item['quantity'] : $item['quantity'];

                $cur_item_kit_info = $this->Item_kit->get_info($item['item_kit_id']);
                $cur_item_kit_location_info = $this->Item_kit_location->get_info($item['item_kit_id']);

                $cost_price = ($cur_item_kit_location_info && $cur_item_kit_location_info->cost_price) ? $cur_item_kit_location_info->cost_price : $cur_item_kit_info->cost_price;

                if (!$this->config->item('disable_subtraction_of_giftcard_amount_from_sales')) {
                    //Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
                    if (!$has_added_giftcard_value_to_cost_price) {
                        $cost_price += $total_giftcard_payments / $item['quantity'];
                        $has_added_giftcard_value_to_cost_price = true;
                    }
                }

                if ($cur_item_kit_info->tax_included) {
                    $item['price'] = get_price_for_item_kit_excluding_taxes($item['item_kit_id'], $item['price']);
                }

                $sales_item_kits_data = array
                    (
                    'sale_id' => $sale_id,
                    'item_kit_id' => $item['item_kit_id'],
                    'line' => $item['line'],
                    'description' => $item['description'],
                    'quantity_purchased' => $item['quantity'],
                    'discount_percent' => $item['discount'],
                    'item_kit_cost_price' => $cost_price === null ? 0.00 : to_currency_no_money($cost_price, 10),
                    'item_kit_unit_price' => $item['price'],
                    'commission' => get_commission_for_item_kit($item['item_kit_id'], $item['price'], $item['quantity'], $item['discount']),
                    "id_tier"=> $item['id_tier']
                );

                if (!$this->db->insert('sales_item_kits', $sales_item_kits_data)) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }

                foreach ($this->Item_kit_items->get_info($item['item_kit_id']) as $item_kit_item) {
                    $cur_item_info = $this->Item->get_info($item_kit_item->item_id);
                    $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);

                    $reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level !== null) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;

                    //Only do stock check + inventory update if we are NOT an estimate
                    if ($suspended != 2) {
                        $stock_recorder_check = false;
                        $out_of_stock_check = false;
                        $email = false;
                        $message = '';

                        //checks if the quantity is greater than reorder level
                        if (!$cur_item_info->is_service && $cur_item_location_info->quantity > $reorder_level) {
                            $stock_recorder_check = true;
                        }

                        //checks if the quantity is greater than 0
                        if (!$cur_item_info->is_service && $cur_item_location_info->quantity > 0) {
                            $out_of_stock_check = true;
                        }

                        //Update stock quantity IF not a service item and the quantity for item is NOT NULL
                        if (!$cur_item_info->is_service) {
                            $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                            if (!$this->Item_location->save_quantity($cur_item_location_info->quantity - ($item['quantity'] * $item_kit_item->quantity), $item_kit_item->item_id)) {
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }
                            
                        }

                        //Re-init $cur_item_location_info after updating quantity
                        $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);

                        //checks if the quantity is out of stock
                        if ($out_of_stock_check && !$cur_item_info->is_service && $cur_item_location_info->quantity <= 0) {
                            $message = $cur_item_info->name . ' ' . lang('sales_is_out_stock') . ' ' . to_quantity($cur_item_location_info->quantity);
                            $email = true;
                        }
                        //checks if the quantity hits reorder level
                        else if ($stock_recorder_check && ($cur_item_location_info->quantity <= $reorder_level)) {
                            $message = $cur_item_info->name . ' ' . lang('sales_hits_reorder_level') . ' ' . to_quantity($cur_item_location_info->quantity);
                            $email = true;
                        }

                        //send email
                        if ($this->Location->get_info_for_key('receive_stock_alert') && $email) {
                           /* $this->load->library('email');
                            $config = array();
                            $config['mailtype'] = 'text';
                            $this->email->initialize($config);
                            $this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
                            $this->email->to($this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email'));

                            $this->email->subject(lang('sales_stock_alert_item_name') . $cur_item_info->name);
                            $this->email->message($message);
                            $this->email->send();*/

                            $this->load->library('Email_send');
                            $para=$this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email');
                            $subject=lang('sales_stock_alert_item_name') . $cur_item_info->name;
                            $name="";
                            $company=$this->config->item('company');
                            $from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
                            $this->email_send->send_($para, $subject, $name,$message,$from,$company) ;
                        }

                        if (!$cur_item_info->is_service) {
                            $qty_buy = -$item['quantity'] * $item_kit_item->quantity;
                            $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                            $inv_data = array
                                (
                                'trans_date' => date('Y-m-d H:i:s'),
                                'trans_items' => $item_kit_item->item_id,
                                'trans_user' => $employee_id,
                                'trans_comment' => $sale_remarks,
                                'trans_inventory' => $qty_buy,
                                'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
                            );
                            if (!$this->Inventory->insert($inv_data)) {
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }
                        }
                    }
                }
            }

            $customer = $this->Customer->get_info($customer_id);
            
            
            if (($customer_id == -1 or $customer->taxable) ) {
               
                if (isset($item['item_id'])) {
                    if($overwrite_tax==1){
                        $query_result = $this->db->insert('sales_items_taxes', array(
                            'sale_id' => $sale_id,
                            'item_id' => $item['item_id'],
                            'line' => $item['line'],
                            'name' => $new_tax['name'],
                            'percent' => $new_tax['percent'],
                            'cumulative' => $new_tax['cumulative'],
                        ));
                        if (!$query_result) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }                        
    
                    }else if($is_servicio_tecnico){
                        foreach ($item["ivas"] as $iva) {
                            $query_result = $this->db->insert('sales_items_taxes', array(
                                'sale_id' => $sale_id,
                                'item_id' => $item['item_id'],
                                'line' => $item['line'],
                                'name' => $iva['name'],
                                'percent' => $iva['percent'],
                                'cumulative' => $iva['cumulative'],
                            ));
                            if (!$query_result) {
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }   
                        }                        
                    }
                    else{

                        foreach ($this->Item_taxes_finder->get_info($item['item_id']) as $row) {

                            $tax_name = $row['percent'] . '% ' . $row['name'];
                            //Only save sale if the tax has NOT been deleted
                            if (!in_array($tax_name, $deleted_taxes)) {

                                $query_result = $this->db->insert('sales_items_taxes', array(
                                    'sale_id' => $sale_id,
                                    'item_id' => $item['item_id'],
                                    'line' => $item['line'],
                                    'name' => $row['name'],
                                    'percent' => $row['percent'],
                                    'cumulative' => $row['cumulative'],
                                ));

                                if (!$query_result) {

                                    $this->db->query("ROLLBACK");
                                    $this->db->query('UNLOCK TABLES');
                                    return -1;
                                }
                            }
                        }
                    }
                } else {
                    if($overwrite_tax==true){
                        $query_result = $this->db->insert('sales_item_kits_taxes', array(
                            'sale_id' => $sale_id,
                            'item_kit_id' => $item['item_kit_id'],
                            'line' => $item['line'],
                            'name' => $new_tax['name'],
                            'percent' => $new_tax['percent'],
                            'cumulative' => $new_tax['cumulative'],
                        ));
                        if (!$query_result) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                           return -1;
                        }

                    }else{
                        foreach ($this->Item_kit_taxes_finder->get_info($item['item_kit_id']) as $row) {
                            $tax_name = $row['percent'] . '% ' . $row['name'];

                            //Only save sale if the tax has NOT been deleted
                            if (!in_array($tax_name, $deleted_taxes)) {
                                $query_result = $this->db->insert('sales_item_kits_taxes', array(
                                    'sale_id' => $sale_id,
                                    'item_kit_id' => $item['item_kit_id'],
                                    'line' => $item['line'],
                                    'name' => $row['name'],
                                    'percent' => $row['percent'],
                                    'cumulative' => $row['cumulative'],
                                ));

                                if (!$query_result) {
                                    $this->db->query("ROLLBACK");
                                    $this->db->query('UNLOCK TABLES');
                                    return -1;
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');

        return $sale_id;
    }

    public function save_ticket($items, $customer_id, $employee_id, $sold_by_employee_id, $comment, $show_comment_on_receipt, $payments, $sale_id = false, $suspended = 0, $cc_ref_no = '', $auth_code = '', $change_sale_date = false, $balance = 0, $store_account_payment = 0, $total = 0, $amount_change)
    {
        //we need to check the sale library for deleted taxes during sale
        $this->load->library('sale_lib');

        if (count($items) == 0) {
            return -1;
        }

        $payment_types = '';
        foreach ($payments as $payment_id => $payment) {
            $payment_types = $payment_types . $payment['payment_type'] . ': ' . to_currency($payment['payment_amount']) . '<br />';
        }

        $tier_id = $this->sale_lib->get_selected_tier_id();

        if (!$tier_id) {
            $tier_id = null;
        }

        $sales_data = array(
            'customer_id' => $customer_id > 0 ? $customer_id : null,
            'employee_id' => $employee_id,
            'sold_by_employee_id' => $sold_by_employee_id,
            'payment_type' => $payment_types,
            'comment' => $comment,
            'show_comment_on_receipt' => $show_comment_on_receipt ? $show_comment_on_receipt : 0,
            'suspended' => $suspended,
            'deleted' => 0,
            'deleted_by' => null,
            'cc_ref_no' => $cc_ref_no,
            'auth_code' => $auth_code,
            'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
            'register_id' => $this->Employee->get_logged_in_employee_current_register_id(),
            'store_account_payment' => $store_account_payment,
            'tier_id' => $tier_id ? $tier_id : null,
        );
        $value = 0;
        $register_payment = false;
        foreach ($payments as $value) {
            $cash = $this->session->userdata("cash");
            $sum_mount_total = 0;
            if ($value['payment_type'] == 'Efectivo') {
                $sum_mount[] = $value['payment_amount'];
                $register_payment = true;
            }
        }

        if ($sale_id) {
            $old_date = $this->get_info($sale_id)->row_array();
            $sales_data['sale_time'] = $old_date['sale_time'];

            if ($change_sale_date) {
                $sale_time = strtotime($change_sale_date);
                if ($sale_time !== false) {
                    $sales_data['sale_time'] = date('Y-m-d H:i:s', strtotime($change_sale_date));
                }
            }
        } else {
            $sales_data['sale_time'] = date('Y-m-d H:i:s');
        }

        $this->db->query("SET autocommit=0");
        //Lock tables invovled in sale transaction so we don't have deadlock
        $this->db->query('LOCK TABLES ' . $this->db->dbprefix('customers') . ' WRITE, ' . $this->db->dbprefix('ticket') . ' WRITE,
		' . $this->db->dbprefix('store_accounts') . ' WRITE, ' . $this->db->dbprefix('ticket_payments') . ' WRITE, ' . $this->db->dbprefix('ticket_items') . ' WRITE,
		' . $this->db->dbprefix('giftcards') . ' WRITE, ' . $this->db->dbprefix('location_items') . ' WRITE,
		' . $this->db->dbprefix('inventory') . ' WRITE, ' . $this->db->dbprefix('ticket_items_taxes') . ' WRITE,
		' . $this->db->dbprefix('ticket_item_kits') . ' WRITE, ' . $this->db->dbprefix('ticket_item_kits_taxes') . ' WRITE,' . $this->db->dbprefix('people') . ' READ,' . $this->db->dbprefix('items') . ' READ
		,' . $this->db->dbprefix('employees_locations') . ' READ,' . $this->db->dbprefix('locations') . ' READ, ' . $this->db->dbprefix('items_tier_prices') . ' READ
		, ' . $this->db->dbprefix('location_items_tier_prices') . ' READ, ' . $this->db->dbprefix('items_taxes') . ' READ, ' . $this->db->dbprefix('item_kits') . ' READ
		, ' . $this->db->dbprefix('location_item_kits') . ' READ, ' . $this->db->dbprefix('item_kit_items') . ' READ, ' . $this->db->dbprefix('employees') . ' READ , ' . $this->db->dbprefix('item_kits_tier_prices') . ' READ
		, ' . $this->db->dbprefix('location_item_kits_tier_prices') . ' READ, ' . $this->db->dbprefix('location_items_taxes') . ' READ
		, ' . $this->db->dbprefix('location_item_kits_taxes') . ' READ, ' . $this->db->dbprefix('item_kits_taxes') . ' READ');

        $previous_store_account_amount = 0;

        if ($sale_id) {
            //Delete previoulsy sale so we can overwrite data
            if (!$this->delete($sale_id, true)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }

            $this->db->where('ticket_id', $sale_id);
            if (!$this->db->update('ticket', $sales_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
        } else {
            if (!$this->db->insert('ticket', $sales_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
            $sale_id = $this->db->insert_id();
        }

        $total_giftcard_payments = 0;

        foreach ($payments as $payment_id => $payment) {
            //Only update giftcard payments if we are NOT an estimate (suspended = 2)
            if ($suspended != 2) {
                if (substr($payment['payment_type'], 0, strlen(lang('sales_giftcard'))) == lang('sales_giftcard')) {
                    /* We have a gift card and we have to deduct the used value from the total value of the card. */
                    $splitpayment = explode(':', $payment['payment_type']);
                    $cur_giftcard_value = $this->Giftcard->get_giftcard_value($splitpayment[1]);

                    $this->Giftcard->update_giftcard_value($splitpayment[1], $cur_giftcard_value - $payment['payment_amount']);
                    $total_giftcard_payments += $payment['payment_amount'];
                }
            }

            $sales_payments_data = array
                (
                'ticket_id' => $sale_id,
                'payment_type' => $payment['payment_type'],
                'payment_amount' => $payment['payment_amount'],
                'payment_date' => $payment['payment_date'],
                'truncated_card' => $payment['truncated_card'],
                'card_issuer' => $payment['card_issuer'],
            );
            if (!$this->db->insert('ticket_payments', $sales_payments_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
        }

        $has_added_giftcard_value_to_cost_price = $total_giftcard_payments > 0 ? false : true;
        $store_account_item_id = $this->Item->get_store_account_item_id();

        foreach ($items as $line => $item) {
            if (isset($item['item_id'])) {
                $cur_item_info = $this->Item->get_info($item['item_id']);
                $cur_item_location_info = $this->Item_location->get_info($item['item_id']);

                if ($item['item_id'] != $store_account_item_id) {
                    $cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;
                } else { // Set cost price = price so we have no profit
                    $cost_price = $item['price'];
                }

                if (!$this->config->item('disable_subtraction_of_giftcard_amount_from_sales')) {
                    //Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
                    if (!$has_added_giftcard_value_to_cost_price) {
                        $cost_price += $total_giftcard_payments / $item['quantity'];
                        $has_added_giftcard_value_to_cost_price = true;
                    }
                }
                $reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;

                if ($cur_item_info->tax_included) {
                    $item['price'] = get_price_for_item_excluding_taxes($item['item_id'], $item['price']);
                }

                $sales_items_data = array
                    (
                    'ticket_id' => $sale_id,
                    'item_id' => $item['item_id'],
                    'line' => $item['line'],
                    'description' => $item['description'],
                    'serialnumber' => $item['serialnumber'],
                    'quantity_purchased' => $item['quantity'],
                    'discount_percent' => $item['discount'],
                    'item_cost_price' => to_currency_no_money($cost_price, 10),
                    'item_unit_price' => $item['price'],
                    'commission' => get_commission_for_item($item['item_id'], $item['price'], $item['quantity'], $item['discount']),
                );

                if (!$this->db->insert('ticket_items', $sales_items_data)) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }

                //Only update giftcard payments if we are NOT an estimate (suspended = 2)
                if ($suspended != 2) {
                    //create giftcard from sales
                    if ($item['name'] == lang('sales_giftcard') && !$this->Giftcard->get_giftcard_id($item['description'])) {
                        $giftcard_data = array(
                            'giftcard_number' => $item['description'],
                            'value' => $item['price'],
                            'customer_id' => $customer_id > 0 ? $customer_id : null,
                        );

                        if (!$this->Giftcard->save($giftcard_data)) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }
                    }
                }

                //Only do stock check + inventory update if we are NOT an estimate
                if ($suspended != 2) {
                    $stock_recorder_check = false;
                    $out_of_stock_check = false;
                    $email = false;
                    $message = '';

                    //checks if the quantity is greater than reorder level
                    if (!$cur_item_info->is_service && $cur_item_location_info->quantity > $reorder_level) {
                        $stock_recorder_check = true;
                    }

                    //checks if the quantity is greater than 0
                    if (!$cur_item_info->is_service && $cur_item_location_info->quantity > 0) {
                        $out_of_stock_check = true;
                    }

                    //Update stock quantity IF not a service
                    if (!$cur_item_info->is_service) {
                        $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                        if (!$this->Item_location->save_quantity($cur_item_location_info->quantity - $item['quantity'], $item['item_id'])) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }
                    }

                    //Re-init $cur_item_location_info after updating quantity
                    $cur_item_location_info = $this->Item_location->get_info($item['item_id']);

                    //checks if the quantity is out of stock
                    if ($out_of_stock_check && $cur_item_location_info->quantity <= 0) {
                        $message = $cur_item_info->name . ' ' . lang('sales_is_out_stock') . ' ' . to_quantity($cur_item_location_info->quantity);
                        $email = true;
                    }
                    //checks if the quantity hits reorder level
                    else if ($stock_recorder_check && ($cur_item_location_info->quantity <= $reorder_level)) {
                        $message = $cur_item_info->name . ' ' . lang('sales_hits_reorder_level') . ' ' . to_quantity($cur_item_location_info->quantity);
                        $email = true;
                    }

                    //send email
                    if ($this->Location->get_info_for_key('receive_stock_alert') && $email) {
                        /*$this->load->library('email');
                        $config = array();
                        $config['mailtype'] = 'text';
                        $this->email->initialize($config);
                        $this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
                        $this->email->to($this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email'));

                        $this->email->subject(lang('sales_stock_alert_item_name') . $this->Item->get_info($item['item_id'])->name);
                        $this->email->message($message);
                        $this->email->send();*/
                        $this->load->library('Email_send');
                        $para=$this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email');
                        $subject=lang('sales_stock_alert_item_name') . $this->Item->get_info($item['item_id'])->name;
                        $name="";
                        $company=$this->config->item('company');
                        $from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
                        $this->email_send->send_($para, $subject, $name,$message,$from,$company) ;
                    }

                    if (!$cur_item_info->is_service) {
                        $qty_buy = -$item['quantity'];
                        $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                        $inv_data = array
                            (
                            'trans_date' => date('Y-m-d H:i:s'),
                            'trans_items' => $item['item_id'],
                            'trans_user' => $employee_id,
                            'trans_comment' => $sale_remarks,
                            'trans_inventory' => $qty_buy,
                            'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
                        );
                        if (!$this->Inventory->insert($inv_data)) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }
                    }
                }
            } else {
                $cur_item_kit_info = $this->Item_kit->get_info($item['item_kit_id']);
                $cur_item_kit_location_info = $this->Item_kit_location->get_info($item['item_kit_id']);

                $cost_price = ($cur_item_kit_location_info && $cur_item_kit_location_info->cost_price) ? $cur_item_kit_location_info->cost_price : $cur_item_kit_info->cost_price;

                if (!$this->config->item('disable_subtraction_of_giftcard_amount_from_sales')) {
                    //Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
                    if (!$has_added_giftcard_value_to_cost_price) {
                        $cost_price += $total_giftcard_payments / $item['quantity'];
                        $has_added_giftcard_value_to_cost_price = true;
                    }
                }

                if ($cur_item_kit_info->tax_included) {
                    $item['price'] = get_price_for_item_kit_excluding_taxes($item['item_kit_id'], $item['price']);
                }

                $sales_item_kits_data = array
                    (
                    'ticket_id' => $sale_id,
                    'item_kit_id' => $item['item_kit_id'],
                    'line' => $item['line'],
                    'description' => $item['description'],
                    'quantity_purchased' => $item['quantity'],
                    'discount_percent' => $item['discount'],
                    'item_kit_cost_price' => $cost_price === null ? 0.00 : to_currency_no_money($cost_price, 10),
                    'item_kit_unit_price' => $item['price'],
                    'commission' => get_commission_for_item_kit($item['item_kit_id'], $item['price'], $item['quantity'], $item['discount']),
                );

                if (!$this->db->insert('ticket_item_kits', $sales_item_kits_data)) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }

                foreach ($this->Item_kit_items->get_info($item['item_kit_id']) as $item_kit_item) {
                    $cur_item_info = $this->Item->get_info($item_kit_item->item_id);
                    $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);

                    $reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level !== null) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;

                    //Only do stock check + inventory update if we are NOT an estimate
                    if ($suspended != 2) {
                        $stock_recorder_check = false;
                        $out_of_stock_check = false;
                        $email = false;
                        $message = '';

                        //checks if the quantity is greater than reorder level
                        if (!$cur_item_info->is_service && $cur_item_location_info->quantity > $reorder_level) {
                            $stock_recorder_check = true;
                        }

                        //checks if the quantity is greater than 0
                        if (!$cur_item_info->is_service && $cur_item_location_info->quantity > 0) {
                            $out_of_stock_check = true;
                        }

                        //Update stock quantity IF not a service item and the quantity for item is NOT NULL
                        if (!$cur_item_info->is_service) {
                            $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                            if (!$this->Item_location->save_quantity($cur_item_location_info->quantity - ($item['quantity'] * $item_kit_item->quantity), $item_kit_item->item_id)) {
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }
                        }

                        //Re-init $cur_item_location_info after updating quantity
                        $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id);

                        //checks if the quantity is out of stock
                        if ($out_of_stock_check && !$cur_item_info->is_service && $cur_item_location_info->quantity <= 0) {
                            $message = $cur_item_info->name . ' ' . lang('sales_is_out_stock') . ' ' . to_quantity($cur_item_location_info->quantity);
                            $email = true;
                        }
                        //checks if the quantity hits reorder level
                        else if ($stock_recorder_check && ($cur_item_location_info->quantity <= $reorder_level)) {
                            $message = $cur_item_info->name . ' ' . lang('sales_hits_reorder_level') . ' ' . to_quantity($cur_item_location_info->quantity);
                            $email = true;
                        }

                        //send email
                        if ($this->Location->get_info_for_key('receive_stock_alert') && $email) {
                            /*
                            $this->load->library('email');
                            $config = array();
                            $config['mailtype'] = 'text';
                            $this->email->initialize($config);
                            $this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
                            $this->email->to($this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email'));

                            $this->email->subject(lang('sales_stock_alert_item_name') . $cur_item_info->name);
                            $this->email->message($message);
                            $this->email->send();
                            */
                            $this->load->library('Email_send');
                            $para=$this->Location->get_info_for_key('stock_alert_email') ? $this->Location->get_info_for_key('stock_alert_email') : $this->Location->get_info_for_key('email');
                            $subject=lang('sales_stock_alert_item_name') . $cur_item_info->name;
                            $name="";
                            $company=$this->config->item('company');
                            $from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
                            $this->email_send->send_($para, $subject, $name,$message,$from,$company) ;
                        }

                        if (!$cur_item_info->is_service) {
                            $qty_buy = -$item['quantity'] * $item_kit_item->quantity;
                            $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                            $inv_data = array
                                (
                                'trans_date' => date('Y-m-d H:i:s'),
                                'trans_items' => $item_kit_item->item_id,
                                'trans_user' => $employee_id,
                                'trans_comment' => $sale_remarks,
                                'trans_inventory' => $qty_buy,
                                'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
                            );
                            if (!$this->Inventory->insert($inv_data)) {
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }
                        }
                    }
                }
            }

            $customer = $this->Customer->get_info($customer_id);

            if ($customer_id == -1 or $customer->taxable) {
                if (isset($item['item_id'])) {

                    foreach ($this->Item_taxes_finder->get_info($item['item_id']) as $row) {

                        $tax_name = $row['percent'] . '% ' . $row['name'];

                        //Only save sale if the tax has NOT been deleted

                        if (!in_array($tax_name, $this->sale_lib->get_deleted_taxes())) {

                            $query_result = $this->db->insert('ticket_items_taxes', array(
                                'ticket_id' => $sale_id,
                                'item_id' => $item['item_id'],
                                'line' => $item['line'],
                                'name' => $row['name'],
                                'percent' => $row['percent'],
                                'cumulative' => $row['cumulative'],
                            ));

                            if (!$query_result) {

                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }
                        }
                    }
                } else {
                    foreach ($this->Item_kit_taxes_finder->get_info($item['item_kit_id']) as $row) {
                        $tax_name = $row['percent'] . '% ' . $row['name'];

                        //Only save sale if the tax has NOT been deleted
                        if (!in_array($tax_name, $this->sale_lib->get_deleted_taxes())) {
                            $query_result = $this->db->insert('ticket_item_kits_taxes', array(
                                'ticket_id' => $sale_id,
                                'item_kit_id' => $item['item_kit_id'],
                                'line' => $item['line'],
                                'name' => $row['name'],
                                'percent' => $row['percent'],
                                'cumulative' => $row['cumulative'],
                            ));

                            if (!$query_result) {
                                $this->db->query("ROLLBACK");
                                $this->db->query('UNLOCK TABLES');
                                return -1;
                            }
                        }
                    }
                }
            }
        }

        $this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');

        return $sale_id;
    }

/*   
     * sale_id_abono id de la sale que se le desea a gregar un abaono
     */
    public function save_petty_cash($items, $customer_id, $employee_id, $sold_by_employee_id, $comment, $show_comment_on_receipt, $payments, $sale_id = false, $suspended = 0, $cc_ref_no = '', $auth_code = '', $change_sale_date = false, $balance = 0, $store_account_payment = 0, $total = 0, $amount_change, $id_sale = -1)
    {
      
      
        $this->load->library('sale_lib');

        $payment_types = '';
        // cuanto pago
        $monton = $this->sale_lib->get_total();
        foreach ($payments as $payment_id => $payment) {
            $payment_types = $payment_types . $payment['payment_type'] . ': ' . to_currency($payment['payment_amount']) . '<br />';
            //$monton=$monton+$payment['payment_amount'];
        }

        $sales_data = array(
            "sale_id" => null,
            'customer_id' => $customer_id > 0 ? $customer_id : null,
            'employee_id' => $employee_id,
            'sold_by_employee_id' => $sold_by_employee_id,
            'payment_type' => $payment_types,
            'comment' => $comment,
            'show_comment_on_receipt' => $show_comment_on_receipt ? $show_comment_on_receipt : 0,
            'suspended' => $suspended,
            'deleted' => 0,
            'deleted_by' => null,
            'cc_ref_no' => $cc_ref_no,
            'auth_code' => $auth_code,
            'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
            'register_id' => $this->Employee->get_logged_in_employee_current_register_id(),
            'store_account_payment' => $store_account_payment,
            "monton_total" => $monton,
        );
        $sales_data['petty_cash_time'] = date('Y-m-d H:i:s');
        $customer_info=$this->Customer->get_info($customer_id);
        $balence_previos=$customer_info->balance;
		$value=0;
		$this->db->query("SET autocommit=0");
		//Lock tables invovled in sale transaction so we don't have deadlock
        $this->db->query('LOCK TABLES '.$this->db->dbprefix('customers').' WRITE, '.$this->db->dbprefix('store_accounts').
        ' WRITE, '.$this->db->dbprefix('petty_cash_payments').' WRITE, '.$this->db->dbprefix('petty_cash'));
			
		$store_account_payment_amount  = $this->sale_lib->get_total();

	
		//Only update balance + store account payments if we are NOT an estimate (suspended = 2)
		
		     //Update customer store account if payment made
			if($customer_id > 0 && $store_account_payment_amount)
			{
				$this->db->set('balance','balance-'.$store_account_payment_amount,false);
				$this->db->where('person_id', $customer_id);
				if (!$this->db->update('customers'))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
			 }
			if (!$this->db->insert('petty_cash',$sales_data))
			{

				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			$sale_id = $this->db->insert_id();
		
			 //insert store account transaction 

			if($customer_id > 0 && $store_account_payment_amount)
			{
			 	

				//if($store_account_payment_amount>$balence_previos)
				//{
				//	$store_account_payment_amount=-$store_account_payment_amount;
				//}
			 	$store_account_transaction = array( 
			      'customer_id'=>$customer_id,
			      'petty_cash_id'=>$sale_id,
				  'comment'=>$comment,
			      'transaction_amount'=>$store_account_payment_amount,
				  'balance'=>$this->Customer->get_info($customer_id)->balance,
                  'date' => date('Y-m-d H:i:s'),
                  "movement_type"=>1,// restal del saldo
                  "category"=>lang("sales_store_account_payment")
				);
				if (!$this->db->insert('store_accounts',$store_account_transaction))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}			
			 }	 		 
         
          // se actualiza la caja y los registros
          foreach ($payments as $payment_id => $payment) { 
            if (substr($payment['payment_type'], 0, strlen(lang('sales_giftcard'))) == lang('sales_giftcard')) {
                /* We have a gift card and we have to deduct the used value from the total value of the card. */
                $splitpayment = explode(':', $payment['payment_type']);
                $cur_giftcard_value = $this->Giftcard->get_giftcard_value($splitpayment[1]);

                $this->Giftcard->update_giftcard_value($splitpayment[1], $cur_giftcard_value - $payment['payment_amount']);
                //$total_giftcard_payments += $payment['payment_amount'];
            } 
            $petty_cash_payments = array
                (
                'petty_cash_id' => $sale_id,
                'payment_type' => $payment['payment_type'],
                'payment_amount' =>$payment['payment_amount'],
                'payment_date' => $payment['payment_date']                
                );
                if (!$this->db->insert('petty_cash_payments', $petty_cash_payments)) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }
         }
          $description =lang("sales_store_account_payment");
          $categorias_gastos=lang("sales_store_account_payment");
          $cash = $this->get_payment_cash($payments);
          if ($amount_change > 0) {
              $cash = $cash - $amount_change;
          }
          if ($cash > 0 && !$this->Register_movement->save($cash, $description,false,true,$categorias_gastos,false)) {
              $this->db->query("ROLLBACK");
              $this->db->query('UNLOCK TABLES');
              return -1;
          }	
		 	  	
		
		$this->db->query("COMMIT");			
		$this->db->query('UNLOCK TABLES');	
	
		return $sale_id;
    }

    

    

    public function add_petty_cash_customer($customer_id, $balance, $suspended = 0)
    {
        //we need to check the sale library for deleted taxes during sale
        $this->load->library('sale_lib');
        $this->db->query("SET autocommit=0");
        //Lock tables invovled in sale transaction so we don't have deadlock
        $this->db->query('LOCK TABLES ' . $this->db->dbprefix('customers') . ' WRITE, ' . $this->db->dbprefix('customers') . ' READ ');
        //Update customer store account balance
        if ($customer_id > 0 && $balance) {

            $this->db->set('balance', 'balance+' . $balance, false);
            $this->db->where('person_id', $customer_id);
            if (!$this->db->update('customers')) {

                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
            $store_account_transaction = array( 
                'customer_id'=>$customer_id,            
                'comment'=>"",
                'transaction_amount'=> $balance,
                'balance'=>$this->Customer->get_info($customer_id)->balance,
                'date' => date('Y-m-d H:i:s'),
                "movement_type"=>0, //0 add al saldo 
                "category"=>"Venta"
              );
              if (!$this->db->insert('store_accounts',$store_account_transaction))
              {
                  $this->db->query("ROLLBACK");
                  $this->db->query('UNLOCK TABLES');
                  return -1;
              }	
        }
        
        $this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');
        return true;
    }
    function get_sales_store_account_payments($sale_id){
        $this->db->from('sales_payments');
        $this->db->where('payment_type', lang('sales_store_account'));
        $this->db->where('sale_id', $sale_id);
        $to_be_paid_result = $this->db->get();
        return  $to_be_paid_result ;
    }
    public function update_store_account($sale_id, $undelete = 0)
    {
        //update if Store account payment exists       
        $to_be_paid_result = $this->get_sales_store_account_payments($sale_id);
        $customer_id = $this->get_customer($sale_id)->person_id;
        if ($to_be_paid_result->num_rows >= 1) {
            foreach ($to_be_paid_result->result() as $to_be_paid) {
                if ($to_be_paid->payment_amount) {
                    //update customer balance
                    if ($undelete == 0) {
                      
                        $this->db->set('balance', 'balance-' . $to_be_paid->payment_amount, false);
                    } else {
                        $this->db->set('balance', 'balance+' . $to_be_paid->payment_amount, false);
                    }
                    $this->db->where('person_id', $customer_id);
                    $this->db->update('customers');
                }
            }
        }
        
    }
    
  
    public function update_store_account_empoyee($sale_id, $undelete = 0, $delete=false)
    {
        $this->load->library('sale_lib');  
             
        $employee= $this->get_employee($sale_id);
        if( $this->config->item('activar_casa_cambio')==true &&  $employee->type=="credito"){ 
            $costo_total=$this->sale_lib->get_total_price_transaction_previous();          
            // este dato es difrente de FALSE cuando se edita una venta
            if($costo_total===FALSE){
                $costo_total=$this->sale_lib-> get_total_price_transaction();
            }      
            if ($costo_total) {
                if($undelete == 0){
                    $this->movement_balance->update_balance_employee(-$costo_total, $employee->person_id);
                }else{
                    $this->movement_balance->update_balance_employee($costo_total, $employee->person_id);
                } 
            }          
        }
    }
   

    public function update_giftcard_balance($sale_id, $undelete = 0)
    {
        //if gift card payment exists add the amount to giftcard balance
        $this->db->from('sales_payments');
        $this->db->like('payment_type', lang('sales_giftcard'));
        $this->db->where('sale_id', $sale_id);
        $sales_payment = $this->db->get();

        if ($sales_payment->num_rows >= 1) {
            foreach ($sales_payment->result() as $row) {
                $giftcard_number = str_ireplace(lang('sales_giftcard') . ':', '', $row->payment_type);
                $value = $row->payment_amount;
                if ($undelete == 0) {
                    $this->db->set('value', 'value+' . $value, false);
                } else {
                    $this->db->set('value', 'value-' . $value, false);
                }
                $this->db->where('giftcard_number', $giftcard_number);
                $this->db->update('giftcards');
            }
        }
    }

    public function delete($sale_id, $all_data = false)
    {
        $sale_info = $this->get_info($sale_id)->row_array();
        $suspended = $sale_info['suspended'];
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        //Only update stock quantity if we are NOT an estimate ($suspendd = 2)
        if ($suspended != 2) {
            $this->db->select('sales.location_id, item_id, quantity_purchased, custom1_subcategory,custom2_subcategory,serialnumber');
            $this->db->from('sales_items');
            $this->db->join('sales', 'sales.sale_id = sales_items.sale_id');
            $this->db->where('sales_items.sale_id', $sale_id);

            foreach ($this->db->get()->result_array() as $sale_item_row) {
                $sale_location_id = $sale_item_row['location_id'];
                $cur_item_info = $this->Item->get_info($sale_item_row['item_id']);
                $cur_item_location_info = $this->Item_location->get_info($sale_item_row['item_id'], $sale_location_id);

                $cur_item_quantity = $this->Item_location->get_location_quantity($sale_item_row['item_id'], $sale_location_id);

                if (!$cur_item_info->is_service) {
   
                    //Update stock quantity
                    $this->Item_location->save_quantity($cur_item_quantity + $sale_item_row['quantity_purchased'], $sale_item_row['item_id'], $sale_location_id);
                    if ($this->config->item('subcategory_of_items') && $cur_item_info->subcategory) {
                        $subcategory = $this->items_subcategory->get_info($sale_item_row['item_id'], false, $sale_item_row['custom1_subcategory'], $sale_item_row['custom2_subcategory']);
                        $quantity_subcategory = $subcategory->quantity;
                        $this->items_subcategory->save_quantity(($quantity_subcategory+ $sale_item_row['quantity_purchased']), 
                        $sale_item_row['item_id'], false,  $sale_item_row['custom1_subcategory'], $sale_item_row['custom2_subcategory']);
                    }
                    
                    $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                    $inv_data = array
                        (
                        'location_id' => $sale_location_id,
                        'trans_date' => date('Y-m-d H:i:s'),
                        'trans_items' => $sale_item_row['item_id'],
                        'trans_user' => $employee_id,
                        'trans_comment' => $sale_remarks,
                        'trans_inventory' => $sale_item_row['quantity_purchased'],
                    );
                    $this->Inventory->insert($inv_data);
                }
                if ( $cur_item_info->is_serialized==1) {                            
                    $this->Additional_item_seriales-> save_one($sale_item_row['item_id'],$sale_item_row["serialnumber"]);                       
                }
            }
        }

        //Only update stock quantity + store accounts + giftcard balance if we are NOT an estimate ($suspended = 2)
        if ($suspended != 2) {
            $this->db->select('sales.location_id, item_kit_id, quantity_purchased');
            $this->db->from('sales_item_kits');
            $this->db->join('sales', 'sales.sale_id = sales_item_kits.sale_id');
            $this->db->where('sales_item_kits.sale_id', $sale_id);

            foreach ($this->db->get()->result_array() as $sale_item_kit_row) {
                foreach ($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item) {
                    $sale_location_id = $sale_item_kit_row['location_id'];
                    $cur_item_info = $this->Item->get_info($item_kit_item->item_id);
                    $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id, $sale_location_id);

                    if (!$cur_item_info->is_service) {
                        $cur_item_location_info->quantity = $cur_item_location_info->quantity !== null ? $cur_item_location_info->quantity : 0;

                        $this->Item_location->save_quantity($cur_item_location_info->quantity + ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity), $item_kit_item->item_id, $sale_location_id);

                        $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                        $inv_data = array
                            (
                            'location_id' => $sale_location_id,
                            'trans_date' => date('Y-m-d H:i:s'),
                            'trans_items' => $item_kit_item->item_id,
                            'trans_user' => $employee_id,
                            'trans_comment' => $sale_remarks,
                            'trans_inventory' => $sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity,
                        );
                        $this->Inventory->insert($inv_data);
                    }
                }
            }
            // para no restar de la cuanta del clinte cuando se suspenda por segunda vez
           if( $suspended !=1 ){
                $this->update_store_account($sale_id);                           
           }
           $this->update_giftcard_balance($sale_id);   
           $this->update_store_account_empoyee($sale_id,1); 
           // para la casa de cambio no hay ventas suspendidad 
           $sales_store_account_total=0;
           $payments= $this->get_sales_store_account_payments($sale_id);
           foreach ($payments->result() as $to_be_paid) {           
                $sales_store_account_total =$sales_store_account_total+ $to_be_paid->payment_amount;             
            }
            

            //Only insert store account transaction if we aren't deleting the whole sale.
            //When deleting the whole sale save() takes care of this
            if (!$all_data ) {
                $previous_store_account_amount =$sales_store_account_total;// $this->get_store_account_payment_total($sale_id);
                if ($previous_store_account_amount) {
                    $store_account_transaction = array(
                        'customer_id' => $sale_info['customer_id'],
                      
                        'comment' => $sale_info['comment'],
                        'transaction_amount' => $previous_store_account_amount,
                        'balance' => $this->Customer->get_info($sale_info['customer_id'])->balance,
                        'date' => date('Y-m-d H:i:s'),
                        "movement_type"=> 1, // se resta saldo
                        "category"=>"Venta eliminada"
                    );
                   $this->db->insert('store_accounts', $store_account_transaction);
                }
            }
        }

        if ($all_data) {
            $this->db->delete('sales_payments', array('sale_id' => $sale_id));
            $this->db->delete('sales_items_taxes', array('sale_id' => $sale_id));
            $this->db->delete('sales_items', array('sale_id' => $sale_id));
            $this->db->delete('sales_item_kits_taxes', array('sale_id' => $sale_id));
            $this->db->delete('sales_item_kits', array('sale_id' => $sale_id));
        }

        $this->db->where('sale_id', $sale_id);
        return $this->db->update('sales', array('deleted' => 1, 'deleted_by' => $employee_id));
    }

    public function delete_quotes($quote_id, $all_data = false)
    {
        $sale_info = $this->get_info($quote_id)->row_array();
        $suspended = $sale_info['suspended'];
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        if ($all_data) {
            $this->db->delete('quotes_payments', array('quote_id' => $quote_id));
            $this->db->delete('quotes_items_taxes', array('quote_id' => $quote_id));
            $this->db->delete('quotes_items', array('quote_id' => $quote_id));
        }

        $this->db->where('quote_id', $quote_id);
        return $this->db->update('quotes', array('deleted' => 1, 'deleted_by' => $employee_id));
    }

    public function undelete($sale_id)
    {
        $sale_info = $this->get_info($sale_id)->row_array();
        $suspended = $sale_info['suspended'];
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        //Only update stock quantity + store accounts + giftcard balance if we are NOT an estimate ($suspended = 2)
        if ($suspended != 2) {
            $this->db->select('sales.location_id, item_id, quantity_purchased,custom1_subcategory,custom2_subcategory,serialnumber');
            $this->db->from('sales_items');
            $this->db->join('sales', 'sales.sale_id = sales_items.sale_id');
            $this->db->where('sales_items.sale_id', $sale_id);

            foreach ($this->db->get()->result_array() as $sale_item_row) {
                $sale_location_id = $sale_item_row['location_id'];
                $cur_item_info = $this->Item->get_info($sale_item_row['item_id']);
                $cur_item_location_info = $this->Item_location->get_info($sale_item_row['item_id'], $sale_location_id);

                if (!$cur_item_info->is_service && $cur_item_location_info->quantity !== null) {
                    //Update stock quantity
                    $this->Item_location->save_quantity($cur_item_location_info->quantity - $sale_item_row['quantity_purchased'], $sale_item_row['item_id']);
                    if ($this->config->item('subcategory_of_items')==1 && $cur_item_info->subcategory) {
                        $subcategory = $this->items_subcategory->get_info($sale_item_row['item_id'], false, $sale_item_row['custom1_subcategory'], $sale_item_row['custom2_subcategory']);
                        $quantity_subcategory = $subcategory->quantity;
                        $this->items_subcategory->save_quantity(($quantity_subcategory-$sale_item_row['quantity_purchased']), 
                        $sale_item_row['item_id'], false, $sale_item_row['custom1_subcategory'],$sale_item_row['custom2_subcategory']);
                    }
                      
                    $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                    $inv_data = array
                        (
                        'location_id' => $sale_location_id,
                        'trans_date' => date('Y-m-d H:i:s'),
                        'trans_items' => $sale_item_row['item_id'],
                        'trans_user' => $employee_id,
                        'trans_comment' => $sale_remarks,
                        'trans_inventory' => -$sale_item_row['quantity_purchased'],
                    );
                    $this->Inventory->insert($inv_data);
                }
                if ($cur_item_info->is_serialized==1) {                        
                    $this->Additional_item_seriales->delete_serial($sale_item_row['item_id'],$sale_item_row["serialnumber"]);   
                } 
            }
           if($suspended != 1){
             $this->update_store_account($sale_id, 1);
           }
            $this->update_giftcard_balance($sale_id, 1);
            $sales_store_account_total=0;
            $payments= $this->get_sales_store_account_payments($sale_id);
            foreach ($payments->result() as $to_be_paid) {           
                 $sales_store_account_total =$sales_store_account_total+ $to_be_paid->payment_amount;             
             }

            $previous_store_account_amount = $sales_store_account_total;// = $this->get_store_account_payment_total($sale_id);

            if ($previous_store_account_amount) {
                $store_account_transaction = array(
                    'customer_id' => $sale_info['customer_id'],                    
                    'comment' => $sale_info['comment'],
                    'transaction_amount' => $previous_store_account_amount,
                    'balance' => $this->Customer->get_info($sale_info['customer_id'])->balance,
                    'date' => date('Y-m-d H:i:s'),
                    "movement_type"=> 0 ,// se suma saldo
                    "category"=>"Venta restaurada"
                );
                $rr=$this->db->insert('store_accounts', $store_account_transaction);
            }

            $this->db->select('sales.location_id, item_kit_id, quantity_purchased');
            $this->db->from('sales_item_kits');
            $this->db->join('sales', 'sales.sale_id = sales_item_kits.sale_id');
            $this->db->where('sales_item_kits.sale_id', $sale_id);

            foreach ($this->db->get()->result_array() as $sale_item_kit_row) {
                foreach ($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item) {
                    $sale_location_id = $sale_item_kit_row['location_id'];
                    $cur_item_info = $this->Item->get_info($item_kit_item->item_id);
                    $cur_item_location_info = $this->Item_location->get_info($item_kit_item->item_id, $sale_location_id);
                    if (!$cur_item_info->is_service && $cur_item_location_info->quantity !== null) {
                        $this->Item_location->save_quantity($cur_item_location_info->quantity - ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity), $item_kit_item->item_id, $sale_location_id);

                        $sale_remarks = $this->config->item('sale_prefix') . ' ' . $sale_id;
                        $inv_data = array
                            (
                            'location_id' => $sale_location_id,
                            'trans_date' => date('Y-m-d H:i:s'),
                            'trans_items' => $item_kit_item->item_id,
                            'trans_user' => $employee_id,
                            'trans_comment' => $sale_remarks,
                            'trans_inventory' => -$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity,
                        );
                        $this->Inventory->insert($inv_data);
                    }
                }
            }
        }

        $this->db->where('sale_id', $sale_id);
        return $this->db->update('sales', array('deleted' => 0, 'deleted_by' => null));
    }

    public function get_sale_items($sale_id)
    {
        $this->db->from('sales_items');
        $this->db->where('sale_id', $sale_id);
        $this->db->order_by('line');
        return $this->db->get();
    }

    public function get_sale_items_quotes($quote_id)
    {
        $this->db->from('quotes_items');
        $this->db->where('quote_id', $quote_id);
        $this->db->order_by('line');
        return $this->db->get();
    }

    public function get_sale_items_ordered_by_category($sale_id)
    {
        $this->db->select('*, sales_items.description as sales_items_description');
        $this->db->from('sales_items');
        $this->db->join('items', 'items.item_id = sales_items.item_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->order_by('category, name');
        return $this->db->get();
    }

    public function get_sale_item_kits($sale_id)
    {
        $this->db->from('sales_item_kits');
        $this->db->where('sale_id', $sale_id);
        $this->db->order_by('line');
        return $this->db->get();
    }

    public function get_sale_item_kits_ordered_by_category($sale_id)
    {
        $this->db->from('sales_item_kits');
        $this->db->join('item_kits', 'item_kits.item_kit_id = sales_item_kits.item_kit_id');
        $this->db->where('sale_id', $sale_id);
        $this->db->order_by('category, name');
        return $this->db->get();
    }

    public function get_sale_items_taxes($sale_id, $line = false)
    {
        $item_where = '';

        if ($line) {
            $item_where = 'and ' . $this->db->dbprefix('sales_items') . '.line = ' . $line;
        }

        $query = $this->db->query('SELECT name, percent, cumulative, item_unit_price as price, quantity_purchased as quantity, discount_percent as discount ' .
            'FROM ' . $this->db->dbprefix('sales_items_taxes') . ' JOIN ' .
            $this->db->dbprefix('sales_items') . ' USING (sale_id, item_id, line) ' .
            'WHERE ' . $this->db->dbprefix('sales_items_taxes') . ".sale_id = $sale_id" . ' ' . $item_where . ' ' .
            'ORDER BY ' . $this->db->dbprefix('sales_items') . '.line,' . $this->db->dbprefix('sales_items') . '.item_id,cumulative,name,percent');
        return $query->result_array();
    }

    public function get_sale_items_taxes_quotes($quote_id, $line = false)
    {
        $item_where = '';

        if ($line) {
            $item_where = 'and ' . $this->db->dbprefix('quotes_items') . '.line = ' . $line;
        }

        $query = $this->db->query('SELECT name,'.$this->db->dbprefix('quotes_items_taxes').'.item_id, percent, cumulative, item_unit_price as price, quantity_purchased as quantity, discount_percent as discount ' .
            'FROM ' . $this->db->dbprefix('quotes_items_taxes') . ' JOIN ' .
            $this->db->dbprefix('quotes_items') . ' USING (quote_id, item_id, line) ' .
            'WHERE ' . $this->db->dbprefix('quotes_items_taxes') . ".quote_id = $quote_id" . ' ' . $item_where . ' ' .
            'ORDER BY ' . $this->db->dbprefix('quotes_items') . '.line,' . $this->db->dbprefix('quotes_items') . '.item_id,cumulative,name,percent');
        return $query->result_array();
    }

    public function get_sale_item_kits_taxes($sale_id, $line = false)
    {
        $item_kit_where = '';

        if ($line) {
            $item_kit_where = 'and ' . $this->db->dbprefix('sales_item_kits') . '.line = ' . $line;
        }

        $query = $this->db->query('SELECT name, percent, cumulative, item_kit_unit_price as price, quantity_purchased as quantity, discount_percent as discount ' .
            'FROM ' . $this->db->dbprefix('sales_item_kits_taxes') . ' JOIN ' .
            $this->db->dbprefix('sales_item_kits') . ' USING (sale_id, item_kit_id, line) ' .
            'WHERE ' . $this->db->dbprefix('sales_item_kits_taxes') . ".sale_id = $sale_id" . ' ' . $item_kit_where . ' ' .
            'ORDER BY ' . $this->db->dbprefix('sales_item_kits') . '.line,' . $this->db->dbprefix('sales_item_kits') . '.item_kit_id,cumulative,name,percent');
        return $query->result_array();
    }

    public function get_sale_payments($sale_id)
    {
        $this->db->from('sales_payments');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get();
    }

    public function get_sale_payments_quotes($quote_id)
    {
        $this->db->from('quotes_payments');
        $this->db->where('quote_id', $quote_id);
        return $this->db->get();
    }

    public function get_customer($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->Customer->get_info($this->db->get()->row()->customer_id);
    }
    public function get_customer_by_petty_cash($petty_cash_id)
    {
        $this->db->from('petty_cash');
        $this->db->where('petty_cash_id', $petty_cash_id);
        return $this->Customer->get_info($this->db->get()->row()->customer_id);
    }
    public function get_petty_cash_by_id($petty_cash_id)
    {
        $this->db->from('petty_cash');
        $this->db->where('petty_cash_id', $petty_cash_id);
        return $this->db->get()->row();
    }
    public function get_employee($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->Employee->get_info($this->db->get()->row()->sold_by_employee_id);
    }

    public function get_customer_quotes($quote_id)
    {
        $this->db->from('quotes');
        $this->db->where('quote_id', $quote_id);
        return $this->Customer->get_info($this->db->get()->row()->customer_id);
    }

    public function get_comment($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->comment;
    }

    public function get_table($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->ntable;
    }
    public function get_divisa($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->divisa;
    }
    public function get_opcion_sale($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->opcion_sale;
    }
    public function get_transaction_rate($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->transaction_rate;
    }

    public function get_comment_quotes($quote_id)
    {
        $this->db->from('quotes');
        $this->db->where('quote_id', $quote_id);
        return $this->db->get()->row()->comment;
    }

    public function get_comment_on_receipt($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->show_comment_on_receipt;
    }
    public function get_otra_moneda($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->another_currency;
    }
    public function get_serie_number($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->serie_number_invoice;
    }
    
    public function get_comment_on_receipt_quotes($quote_id)
    {
        $this->db->from('quotes');
        $this->db->where('quote_id', $quote_id);
        return $this->db->get()->row()->show_comment_on_receipt;
    }

    public function get_sold_by_employee_id($sale_id)
    {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->sold_by_employee_id;
    }

    public function get_sold_by_employee_id_quotes($quote_id)
    {
        $this->db->from('quotes');
        $this->db->where('quote_id', $quote_id);
        return $this->db->get()->row()->sold_by_employee_id;
    }

    //We create a temp table that allows us to do easy report/sales queries
    public function create_sales_items_temp_table($params)
    {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $where = '';

        if (isset($params['sale_ids'])) {
            if (!empty($params['sale_ids'])) {
                for ($k = 0; $k < count($params['sale_ids']); $k++) {
                    $params['sale_ids'][$k] = $this->db->escape($params['sale_ids'][$k]);
                }

                $where .= 'WHERE ' . $this->db->dbprefix('sales') . ".sale_id IN(" . implode(',', $params['sale_ids']) . ")";
            } else {
                $where .= 'WHERE ' . $this->db->dbprefix('sales') . ".sale_id IN(0)";
            }
        } elseif (isset($params['start_date']) && isset($params['end_date'])) {
            $where = 'WHERE sale_time BETWEEN ' . $this->db->escape($params['start_date']) . ' and ' . $this->db->escape($params['end_date']) . ' and ' . $this->db->dbprefix('sales') . '.location_id=' . $this->db->escape($location_id) . (($this->config->item('hide_store_account_payments_in_reports')) ? ' and ' . $this->db->dbprefix('sales') . '.store_account_payment=0' : '');

            $where = 'WHERE sale_time BETWEEN ' . $this->db->escape($params['start_date']) . ' and '
            . $this->db->escape($params['end_date']) . ' and ' . $this->db->dbprefix('sales') . '.location_id=' .
            $this->db->escape($location_id) . (($this->config->item('hide_store_account_payments_in_reports')) ? ' and ' .
                $this->db->dbprefix('sales') . '.store_account_payment=0' : '');

                if (isset($params['id_employee_login']) && $params['id_employee_login']!==FALSE) {
                    $where .= ' and ' . $this->db->dbprefix('sales') . '.sold_by_employee_id='.$this->db->escape($params['id_employee_login']);
        
                }
               
            /*if (isset($params['employee_id'])) {
            $where .= ' and ' . $this->db->dbprefix('sales') . '.employee_id='.$this->db->escape($params['employee_id']);

            }
            else if (isset($params['sold_by_employee_id'])) {
            $where .= ' and ' . $this->db->dbprefix('sales') . '.sold_by_employee_id='.$this->db->escape($params['sold_by_employee_id']);

            }*/
            //Added for detailed_suspended_report, we don't need this for other reports as we are always going to have start + end date
            if (isset($params['force_suspended']) && $params['force_suspended']) {
                $where .= ' and suspended != 0';
            } elseif ($this->config->item('hide_layaways_sales_in_reports')) {
                $where .= ' and suspended = 0';
            } else {
                $where .= ' and suspended != 2';
            }
        } elseif ($this->config->item('hide_layaways_sales_in_reports')) {
            $where .= 'WHERE suspended = 0' . ' and ' . $this->db->dbprefix('sales') . '.location_id=' . $this->db->escape($location_id) . (($this->config->item('hide_store_account_payments_in_reports')) ? ' and ' . $this->db->dbprefix('sales') . '.store_account_payment=0' : '');
        } else {
            $where .= 'WHERE suspended != 2' . ' and ' . $this->db->dbprefix('sales') . '.location_id=' . $this->db->escape($location_id) . (($this->config->item('hide_store_account_payments_in_reports')) ? ' and ' . $this->db->dbprefix('sales') . '.store_account_payment=0' : '');
        }

        if ($where == '') {
            $where = 'WHERE suspended != 2 and ' . $this->db->dbprefix('sales') . '.location_id=' . $this->db->escape($location_id) . (($this->config->item('hide_store_account_payments_in_reports')) ? ' and ' . $this->db->dbprefix('sales') . '.store_account_payment=0' : '');
        }

        $return = $this->_create_sales_items_temp_table_query($where);
        return $return;
    }

    public function _create_sales_items_temp_table_query($where)
    {
        set_time_limit(0);

        return $this->db->query("CREATE TEMPORARY TABLE " . $this->db->dbprefix('sales_items_temp') . "
		(SELECT " . $this->db->dbprefix('sales') .".divisa as divisa,". $this->db->dbprefix('sales') .".transaction_rate as transaction_rate,". $this->db->dbprefix('sales') . ".deleted as deleted," . $this->db->dbprefix('sales') . ".deleted_by as deleted_by, sale_time, date(sale_time) as sale_date, " . $this->db->dbprefix('registers') . '.name as register_name,' . $this->db->dbprefix('sales_items') . ".sale_id, comment,payment_type,is_invoice,ticket_number,invoice_number, customer_id, employee_id,suspended, sold_by_employee_id,
		" . $this->db->dbprefix('items') . ".item_id, NULL as item_kit_id, supplier_id, quantity_purchased, item_cost_price, item_unit_price, category,
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		" . $this->db->dbprefix('sales_items') . ".line as line, serialnumber, " . $this->db->dbprefix('sales_items') . ".description as description,tasa as tasa,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)+(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100)
		+(((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as total,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100)
		+(((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as tax,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit, commission, store_account_payment
		FROM " . $this->db->dbprefix('sales_items') . "
		INNER JOIN " . $this->db->dbprefix('sales') . " ON  " . $this->db->dbprefix('sales_items') . '.sale_id=' . $this->db->dbprefix('sales') . '.sale_id' . "
		INNER JOIN " . $this->db->dbprefix('items') . " ON  " . $this->db->dbprefix('sales_items') . '.item_id=' . $this->db->dbprefix('items') . '.item_id' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('suppliers') . " ON  " . $this->db->dbprefix('items') . '.supplier_id=' . $this->db->dbprefix('suppliers') . '.person_id' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('sales_items_taxes') . " ON  "
            . $this->db->dbprefix('sales_items') . '.sale_id=' . $this->db->dbprefix('sales_items_taxes') . '.sale_id' . " and "
            . $this->db->dbprefix('sales_items') . '.item_id=' . $this->db->dbprefix('sales_items_taxes') . '.item_id' . " and "
            . $this->db->dbprefix('sales_items') . '.line=' . $this->db->dbprefix('sales_items_taxes') . '.line' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('registers') . " ON  " . $this->db->dbprefix('registers') . '.register_id=' . $this->db->dbprefix('sales') . '.register_id' . "
		$where
		GROUP BY sale_id, item_id, line)
		UNION ALL
		(SELECT " . $this->db->dbprefix('sales') .".divisa as divisa," . $this->db->dbprefix('sales') .".transaction_rate as transaction_rate,". $this->db->dbprefix('sales') . ".deleted as deleted," . $this->db->dbprefix('sales') . ".deleted_by as deleted_by, sale_time, date(sale_time) as sale_date, " . $this->db->dbprefix('registers') . '.name as register_name,' . $this->db->dbprefix('sales_item_kits') . ".sale_id, comment,payment_type,is_invoice,ticket_number,invoice_number,customer_id, employee_id,suspended, sold_by_employee_id,
		NULL as item_id, " . $this->db->dbprefix('item_kits') . ".item_kit_id, '' as supplier_id, quantity_purchased, item_kit_cost_price, item_kit_unit_price, category,
		discount_percent, (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		" . $this->db->dbprefix('sales_item_kits') . ".line as line, '' as serialnumber, " . $this->db->dbprefix('sales_item_kits') . ".description as description,'' as tasa,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)+(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100)
		+(((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as total,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100)
		+(((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100) + (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as tax,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100) - (item_kit_cost_price*quantity_purchased) as profit, commission, store_account_payment
		FROM " . $this->db->dbprefix('sales_item_kits') . "
		INNER JOIN " . $this->db->dbprefix('sales') . " ON  " . $this->db->dbprefix('sales_item_kits') . '.sale_id=' . $this->db->dbprefix('sales') . '.sale_id' . "
		INNER JOIN " . $this->db->dbprefix('item_kits') . " ON  " . $this->db->dbprefix('sales_item_kits') . '.item_kit_id=' . $this->db->dbprefix('item_kits') . '.item_kit_id' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('sales_item_kits_taxes') . " ON  "
            . $this->db->dbprefix('sales_item_kits') . '.sale_id=' . $this->db->dbprefix('sales_item_kits_taxes') . '.sale_id' . " and "
            . $this->db->dbprefix('sales_item_kits') . '.item_kit_id=' . $this->db->dbprefix('sales_item_kits_taxes') . '.item_kit_id' . " and "
            . $this->db->dbprefix('sales_item_kits') . '.line=' . $this->db->dbprefix('sales_item_kits_taxes') . '.line' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('registers') . " ON  " . $this->db->dbprefix('registers') . '.register_id=' . $this->db->dbprefix('sales') . '.register_id' . "
		$where
		GROUP BY sale_id, item_kit_id, line) ORDER BY sale_id, line");
    }

    public function get_giftcard_value($giftcardNumber)
    {
        if (!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($giftcardNumber))) {
            return 0;
        }

        $this->db->from('giftcards');
        $this->db->where('giftcard_number', $giftcardNumber);
        return $this->db->get()->row()->value;
    }

    public function get_table_by_id($table)
    {
        $suspended_types = array(1, 2);

        $location_id = $this->Employee->get_logged_in_employee_current_location_id();

        $this->db->select('sale_id');
        $this->db->from('sales');
        $this->db->join('customers', 'sales.customer_id = customers.person_id', 'left');
        $this->db->join('people', 'customers.person_id = people.person_id', 'left');
        $this->db->where('sales.deleted', 0);
        $this->db->where('ntable', $table);
        $this->db->where_in('suspended', $suspended_types);
        $this->db->where_in('location_id', $location_id);
        $this->db->order_by('sale_id');
        $sales = $this->db->get();
        $row = $sales->num_rows();
        if ($row > 0) {
            $data = $sales->row();
            if (isset($data)) {
                $id = $data->sale_id;
            } else {
                $id = false;
            }
        } else {
            $id = false;
        }

        return $id;
    }

    public function get_all_suspended($suspended_types = array(1, 2))
    {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();

        $this->db->from('sales');
        $this->db->join('customers', 'sales.customer_id = customers.person_id', 'left');
        $this->db->join('people', 'customers.person_id = people.person_id', 'left');
        $this->db->where('sales.deleted', 0);
        $this->db->where_in('suspended', $suspended_types);
        $this->db->where_in('location_id', $location_id);
        $this->db->order_by('sale_id');
        $sales = $this->db->get()->result_array();

        for ($k = 0; $k < count($sales); $k++) {
            $item_names = array();
            $this->db->select('name');
            $this->db->from('items');
            $this->db->join('sales_items', 'sales_items.item_id = items.item_id');
            $this->db->where('sale_id', $sales[$k]['sale_id']);

            foreach ($this->db->get()->result_array() as $row) {
                $item_names[] = $row['name'];
            }

            $this->db->select('name');
            $this->db->from('item_kits');
            $this->db->join('sales_item_kits', 'sales_item_kits.item_kit_id = item_kits.item_kit_id');
            $this->db->where('sale_id', $sales[$k]['sale_id']);

            foreach ($this->db->get()->result_array() as $row) {
                $item_names[] = $row['name'];
            }

            $sales[$k]['items'] = implode(', ', $item_names);
        }

        return $sales;
    }

    public function get_all_quotoses($suspended_types = array(1, 2))
    {
        $this->db->from('quotes');
        $this->db->join('customers', 'quotes.customer_id = customers.person_id', 'left');
        $this->db->join('people', 'customers.person_id = people.person_id', 'left');
        $this->db->where('quotes.deleted', 0);
        $this->db->order_by('quote_id',"desc");
        $sales = $this->db->get()->result_array();

        for ($k = 0; $k < count($sales); $k++) {
            $item_names = array();
            $this->db->select('name');
            $this->db->from('items');
            $this->db->join('quotes_items', 'quotes_items.quote_id = items.item_id');
            $this->db->where('quote_id', $sales[$k]['quote_id']);

            foreach ($this->db->get()->result_array() as $row) {
                $item_names[] = $row['name'];
            }

            $sales[$k]['items'] = implode(', ', $item_names);
        }

        return $sales;
    }

    public function count_all()
    {
        $this->db->from('sales');
        $this->db->where('deleted', 0);

        if ($this->config->item('hide_store_account_payments_in_reports')) {
            $this->db->where('store_account_payment', 0);
        }

        return $this->db->count_all_results();
    }

    public function get_recent_sales_for_customer($customer_id)
    {
        $return = array();

        $this->db->select('sales.*, SUM(quantity_purchased) as items_purchased');
        $this->db->from('sales');
        $this->db->join('sales_items', 'sales.sale_id = sales_items.sale_id');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('deleted', 0);
        $this->db->order_by('sale_time DESC');
        $this->db->group_by('sales.sale_id');
        $this->db->limit(10);

        foreach ($this->db->get()->result_array() as $row) {
            $return[] = $row;
        }

        return $return;
    }

    public function get_store_account_payment_total($petty_cash_id)
    {
        $this->db->select('SUM(payment_type) as store_account_payment_total', false);
        $this->db->from('petty_cash');
        $this->db->where('petty_cash_id', $petty_cash_id);
        $patty_payments = $this->db->get()->result_array();

        return isset($patty_payments['store_account_payment_total']) ? $petty_payments['store_account_payment_total'] : 0;
    }

    //Regresa la cantidad de productos con descuento en existencia de un producto especifico
    //@param item_id  tipo: int
    public function get_promo_quantity($item_id)
    {
        $this->db->select('promo_quantity')->from('items')->where('item_id', $item_id);
        $query = $this->db->get();

        if (isset($query) && $query->row()->promo_quantity > 0) {
            return $query->row()->promo_quantity;
        }

        return false;
    }

    //Resta la cantidad de productos en promocion al hacer una compra
    //@param item_id  tipo: int
    //@param quantity  tipo: int
    public function drecrease_promo_quantity($quantity, $item_id)
    {
        $quantity = (int) $quantity;
        $query = 'UPDATE ' . $this->db->dbprefix("items") . ' SET `promo_quantity` = `promo_quantity` - ? WHERE `item_id` = ?';
        return $this->db->query($query, array($quantity, $item_id)) ? true : false;
    }

    //Verifica si un item cumple los requerimientos para aplicar un descuento por promocion
    //@param itemId  tipo: int
    public function is_item_promo($itemId)
    {
        $today = strtotime(date('Y-m-d'));
        $item_info = $this->Item->get_info($itemId);
        $is_item_promo = (
            $this->Sale->get_promo_quantity($itemId) > 0 && ($item_info->start_date !== null && $item_info->end_date !== null) && (strtotime($item_info->start_date) <= $today && strtotime($item_info->end_date) >= $today)
        );

        if ($is_item_promo) {
            return true;
        }

        return false;
    }

    public function get_payment_cash($payments)
    {
        $total_cash = 0;

        foreach ($payments as $value) {

            if ($value['payment_type'] == lang('sales_cash')) {

                $total_cash += (float) $value['payment_amount'];
            }
        }

        return $total_cash;
    }

    public function get_previous_payments_cash($sale_id)
    {
        $total_cash = 0;
        $previous_payments = $this->get_sale_payments($sale_id)->result_array();

        $total_cash = $this->get_payment_cash($previous_payments);

        return $total_cash;
    }
    function paymet_total($sale_id){
       $payments= $this->get_sale_payments($sale_id)->result_array();
       $total= 0;

        foreach ($payments as $value) {           
                $total += (float) $value['payment_amount'];           
        }

        return $total;
    }
    public function delete_sale($sale_id)
    {
        $error = "";
        $success = false;
        $sale_info = $this->get_info($sale_id)->row(); //obtener informacion de la venta

        if ($this->config->item('track_cash') == 1 && $sale_info->suspended != 2) { //Si el registro de movimiento de caja esta activo
            $register_log_id = $this->get_current_register_log(); //Obtener la session de la caja

            if (!$register_log_id) {

                $error = "Aperture una caja";
            } elseif ($sale_info->location_id != $this->Employee->get_logged_in_employee_current_location_id()) {

                $error = "¡Esta no es la tienda donde se realizo la venta!";
            } elseif ($this->get_cash_available($sale_id) < 0) { //Obtener la diferencia del monto de la caja
                $error = "No tienes suficiente efectivo en caja para eliminar la venta";
            } elseif ($this->delete($sale_id)) { //Eliminar venta
                $cash = $this->get_previous_payments_cash($sale_id); //Obtener monto efectivo de la venta
                $this->Register_movement->save($cash * (-1), "Venta eliminada",false,true,"Venta eliminada"); //Registar movimiento
                if( $this->config->item('activar_casa_cambio')==true &&  $this->Employee->get_info($sale_info->sold_by_employee_id)->type=="credito"){               
                    $this->load->library('sale_lib');
                   $costo_total=$this->sale_lib-> get_total_price_transaction();
                    $this->movement_balance->save_movement($costo_total,null, $sale_info->sold_by_employee_id ,0,"Venta eliminada",false );
                }
                $success = true;
            }
        } else {

            if ($this->delete($sale_id)) { //Eliminar venta
                if( $this->config->item('activar_casa_cambio')==true &&  $this->Employee->get_info($sale_info->sold_by_employee_id)->type=="credito"){               
                    $this->load->library('sale_lib');
                    $costo_total=$this->sale_lib-> get_total_price_transaction();
                    $this->movement_balance->save_movement($costo_total,null, $sale_info->sold_by_employee_id ,0 ,"Venta eliminada",false);
                }
                $success = true;
            }
        }

        return compact('success', 'error');
    }

    public function undelete_sale($sale_id)
    {
        $error = "";
        $success = false;
        $sale_info = $this->get_info($sale_id)->row(); //obtener informacion de la venta

        if ($this->config->item('track_cash') == 1 && $sale_info->suspended != 2) { //Si el registro de movimiento de caja esta activo
            $register_log_id = $this->get_current_register_log(); //Obtener la session de la caja donde se facturo la venta

            if (!$register_log_id) {

                $error = "Aperture una caja";
            } elseif ($sale_info->location_id != $this->Employee->get_logged_in_employee_current_location_id()) {

                $error = "¡Esta no es la tienda donde se realizo la venta!";
            } elseif ($this->undelete($sale_id)) { //Eliminar venta
                $cash = $this->get_previous_payments_cash($sale_id); //Obtener monto efectivo de la venta
                $this->Register_movement->save($cash, "Venta restaurada",false,true, "Venta restaurada"); //Registar movimiento
                $success = true;
            }
        } else {

            if ($this->undelete($sale_id)) { //Restaurar venta
                $success = true;
            }
        }

        return compact('success', 'error');
    }

    /*     * * Obsoletas ** */

    public function subtract_payments_to_register($sale_id, $register_id = false)
    {
        /*
        $register_log_id         = $this->get_current_register_log($register_id);//Obtener caja donde se facturo la venta

        $cash                    = $this->get_previous_payments_cash($sale_id); //Obtener monto efectivo de la venta

        $amount_current_register = $register_log_id->cash_sales_amount; //Monto caja dnde se proceso la venta

        //if ($sale_info->register_id == $register_log_id->register_id) {
        $diff_cash = 0;//($amount_current_register + $this->get_diff_sale_cash($sale_id, $cash));

        //}

        return $diff_cash;
         */
        return 0;
    }

    /*     * * Obsoletas ** */

    public function get_diff_cash_sale_register($sale_id, $register_id = false, $cash_payment)
    {
        /*
    $current_cash_amount = $this->get_current_register_log($register_id)->cash_sales_amount;//Monto actual en caja
    $amount_register     = $this->subtract_payments_to_register($sale_id, $register_id);// Monto sin los pagos previos
    $total               =  ($amount_register + $cash_payment);
    $diff_cash           = ($total - $current_cash_amount); // Diferencia del efectivo con respecto al pago anterior

    return $diff_cash;
     */
    }

    public function get_diff_sale_cash($sale_id, $cash_payment)
    {
        $amount_register = $this->get_previous_payments_cash($sale_id); // Monto en efectivos pagos previos
        $diff_cash = ($cash_payment - $amount_register); // Diferencia del efectivo con respecto al pago anterior

        return $diff_cash;
    }
  /*  public function get_diff_sale_cash_credit($sale_id, $cash_payment=0)
    {
        $previous_payments = $this->get_sale_payments($sale_id)->result_array();
        $amount_register=0;
        $line_credit=lang('sales_store_account');
        foreach( $previous_payments as $payment ){
            if($line_credit==$payment["payment_type"]){
                $amount_register+=(float) $payment["payment_amount"];
            }
        }
        //$amount_register = $this->get_previous_payments_cash($sale_id); // Monto en efectivos pagos previos
        $diff_cash = ( abs($cash_payment)-$amount_register); // Diferencia del efectivo con respecto al pago anterior

        return $diff_cash;
    }*/

    public function get_petty_cash($cutomer_id,$location_id=false,$limit=20)
    {
       if($location_id==false){
            $location_id= $this->Employee->get_logged_in_employee_current_location_id();
       }
        $this->db->from('petty_cash');
        $this->db->where('customer_id', $cutomer_id);
        $this->db->where('location_id', $location_id);        
        $this->db->where('deleted', 0);
        $this->db->order_by('petty_cash_id DESC');
        $this->db->limit($limit);
        $query = $this->db->get();

        return $query->result();
    }
    function delete_petty_cash($petty_cash_id,$delte_all=false){
        $data=array(
            "deleted"=>1,
            "deleted_by"=>$this->Employee->get_logged_in_employee_info()->person_id
         );
         $this->db->where('petty_cash_id', $petty_cash_id);
         $result = $this->db->update('petty_cash', $data);
         $this->update_store_account_by_petty_cash($petty_cash_id); 
         $this->update_giftcard_balance_by_petty_cash($petty_cash_id); 
         return $result;
    }
    public function update_giftcard_balance_by_petty_cash($petty_cash_id, $undelete = 0)
    {
        $petty_cash_info=$this->get_petty_cash_by_id($petty_cash_id);
       
            //update if Store account payment exists
            $this->db->from('petty_cash_payments');     
            $this->db->like('payment_type', lang('sales_giftcard'));  
            $this->db->where('petty_cash_id', $petty_cash_id);
        
            $to_be_paid_result = $this->db->get();

            //$customer_id = $this-> get_customer_by_petty_cash($petty_cash_id)->person_id;

            if ($to_be_paid_result->num_rows >= 1) {
                foreach ($to_be_paid_result->result() as $to_be_paid) {
                    
                    if ($to_be_paid->payment_amount) {
                        $giftcard_number = str_ireplace(lang('sales_giftcard') . ':', '', $to_be_paid->payment_type);
                        $value = $to_be_paid->payment_amount;
                        if ($undelete == 0) {
                            $this->db->set('value', 'value+' . $value, false);
                        } else {
                            $this->db->set('value', 'value-' . $value, false);
                        }
                        $this->db->where('giftcard_number', $giftcard_number);
                        $this->db->update('giftcards');                       
                        
                    }   
                }
            }
        
    }
    public function update_store_account_by_petty_cash($petty_cash_id, $undelete = 0)
    {
        $petty_cash_info=$this->get_petty_cash_by_id($petty_cash_id);
        if($petty_cash_info->monton_total){
            //update if Store account payment exists
            $this->db->from('petty_cash_payments');       
            $this->db->where('petty_cash_id', $petty_cash_id);
        
            $to_be_paid_result = $this->db->get();

            $customer_id = $this-> get_customer_by_petty_cash($petty_cash_id)->person_id;

            if ($to_be_paid_result->num_rows >= 1) {
                foreach ($to_be_paid_result->result() as $to_be_paid) {
                    if($to_be_paid->payment_type=="Efectivo"){
                        if ($to_be_paid->payment_amount) {
                    
                            if ($undelete == 0) {
                                $description ="Abono  de crédito eliminado";
                                $categorias_gastos="Abono  de crédito eliminado";
                                $result=  $this->Register_movement->save(-$to_be_paid->payment_amount, $description,false,true,$categorias_gastos,false);
                                
                        
                            } else {
                                $description ="Abono  de crédito restaurado";
                                $categorias_gastos="Abono  de crédito restaurado";
                                $result=  $this->Register_movement->save($to_be_paid->payment_amount, $description,false,true,$categorias_gastos,false);
                                
                            }
                        
                        }
                    }   
                }
            }
            $store_account_payment_amount=$petty_cash_info->monton_total;
            $movement_type=0;
            $category="";
            if ($undelete == 0) {
                $this->db->set('balance', 'balance+' . $store_account_payment_amount, false);
                //$store_account_payment_amount=-$store_account_payment_amount;
                    $movement_type=0;
                    $category="Abono  de crédito eliminado";
                    
            }else{
                $this->db->set('balance', 'balance-' . $store_account_payment_amount, false);
                $movement_type=1;
                $category="Abono  de crédito restaurado";
            }
            $this->db->where('person_id', $customer_id);
            $result=  $this->db->update('customers');

            $store_account_transaction = array(
                'customer_id'=>$customer_id,
                'petty_cash_id'=>$petty_cash_id,
                'comment'=>"",
                'transaction_amount'=>$store_account_payment_amount,
                'balance'=>$this->Customer->get_info($customer_id)->balance,
                'date' => date('Y-m-d H:i:s'),
                "movement_type"=>$movement_type,// 0 agregar al la cuenta 1 restar de la cuenta
                "category"=>$category
            );            
           $result= $this->db->insert('store_accounts',$store_account_transaction);            
        }        
    }  

    public function get_cash_available($sale_id, $payment_cash = null)
    {

        $register_log_id = $this->get_current_register_log(); //Obtener caja actual

        if ($payment_cash == null) {

            $amount_cash = $register_log_id->cash_sales_amount - $this->get_previous_payments_cash($sale_id);
        } else {

            $amount_cash = ($register_log_id->cash_sales_amount + $this->Sale->get_diff_sale_cash($sale_id, $payment_cash));
        }

        return $amount_cash;
    }

    function is_deleted_sale($sale_id){
        $sale_info = $this->get_info($sale_id)->row_array();
        if(empty($sale_info) ||  $sale_info['deleted']==1){
            return true;
        }
    }
    /**
     * consula las beses que se han ocupadPo las besas en un rango de fecha
     */
    public function get_tables_n_occupied($data_inicio, $data_fin)
    {
        $this->db->from('sales');
        $this->db->select('ntable, COUNT(ntable) AS count');
        $this->db->where("date_format(sale_time, '%Y-%m-%d')>='" . $data_inicio . "'");
        $this->db->where("date_format(sale_time, '%Y-%m-%d')<='" . $data_fin . "'");

        $this->db->where('ntable is NOT NULL', null, false);
        $this->db->group_by('ntable');
        $query = $this->db->get();
        return $query->result();}

}

/*End of file sale.php */
