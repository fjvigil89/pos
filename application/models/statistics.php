<?php
class Statistics extends CI_Model
{
    public function get_best_seller_items($start_date = false, $end_date = false )
    {        
        $start_date ? $start_date : $start_date = date("Y-m-d H:i:s",strtotime("-1 month"));
        $end_date ? $end_date : $end_date = date("Y-m-d H:i:s");
        
        $this->db->select('items.name');
        $this->db->select_sum('sales_items.quantity_purchased');
        $this->db->from('sales_items');
        $this->db->join('items', 'items.item_id = sales_items.item_id', 'left');
        $this->db->join('sales', 'sales.sale_id = sales_items.sale_id', 'left');
        $this->db->where('sales.sale_time BETWEEN '.$this->db->escape($start_date).' and '.$this->db->escape($end_date));
        $this->db->group_by('phppos_items.item_id');
        $this->db->order_by('quantity_purchased','desc');
        $this->db->limit(10);
        
        $result=$this->db->get()->result_array();

        if ($result != NULL) 
        {
            return $result;
        }
    }
    
    public function get_weekly_sales($start_date=false, $end_date=false)
    {
        $this->db->select('sales.sale_time');
        $this->db->from('sales');
        $this->db->where('sales.sale_time BETWEEN '.$this->db->escape($start_date).' and '.$this->db->escape($end_date));
        //$this->db->where('sales.sale_time'.$this->db->escape($start_date).' and '.$this->db->escape($end_date));
        $sales = $this->db->get()->result_array();

        $weekly_sales = array();
        $days = array();
        $translated_days = array();
        $x = 0;
        
        foreach($sales as $sale)
        {            
            $day = 'day_'.date('N', strtotime($sale['sale_time']));
            
            if(!isset($days[$day]))
            {
                $days[$day] = 0;
            }
            
            $days[$day]++;
        }

        ksort($days);
 
        foreach ($days as $key=>$value)
        {
            $translated_days[lang($key)] = $value;
        }        

        foreach ($translated_days as $key => $value) 
        {
            $weekly_sales[$key]['days'] = $key;
            $weekly_sales[$key]['quantity_sales'] = $value;
            $weekly_sales[$key]['color'] = substr(md5(time().$x), 0, 6);
            $x++;
        }
        
        return $weekly_sales;
    }
    // productos escasos
    public function get_items_scarce($location_id){
        $final_result = array();
        $x = 5;
        $this->db->select('location_items.quantity as cantidad, items.name');
        $this->db->from('location_items');
        $this->db->join('items', 'items.item_id = location_items.item_id');
        $this->db->where('location_items.reorder_level >= phppos_location_items.quantity');
        $this->db->where('location_items.location_id',$location_id);
        $this->db->order_by('location_items.quantity','desc');
        $this->db->limit(10);
        
        $result=$this->db->get()->result_array();

        if ($result != NULL) 
        {
            foreach ($result as $key => $value) 
            {
                $final_result[$key] = $value;
                $final_result[$key]['color'] = '#'.substr(md5(time().$x), 0, 6);
                $x++;            
            }

            return $final_result;
        } 
    }


    public function profit_and_loss($data)
    {
        $profit_and_loss = array();

        foreach ($data as $key => $value) 
        {
            $profit_and_loss[lang('reports_'.$key)] = str_replace(' ','&nbsp;', to_currency($value));
        }

        return $profit_and_loss;
    }

    public function get_all_sale_payments_by_employeer()
    {
        $final_result = array();
        $x = 0;

        $this->db->select('people.first_name, count(sale_id) as total, people.image_id');
        $this->db->from('sales');
        $this->db->join('employees', 'sales.sold_by_employee_id=employees.person_id');
        $this->db->join('people', 'people.person_id=employees.person_id');
        $this->db->Group_by('employees.person_id');
        $this->db->order_by('total');

        $result = $this->db->get()->result_array();
        
        if ($result != NULL) 
        {
            foreach ($result as $key => $value) 
            {
                $final_result[$key] = $value;
                $final_result[$key]['color'] = substr(md5(time().$x), 0, 6);
                $x++;            
            }

            return $final_result;
        }                
    }
    public function get_all_sales_by_store($start_date = false, $end_date = false){
        $final_result = array();
        $start_date ? $start_date : $start_date = date("Y-m-d");
        $end_date ? date($end_date) : $end_date = date("Y-m-d",strtotime("1 day"));
        
        $this->db->select('locations.name, COUNT(sale_id) as total_sales');
        $this->db->from('sales');
        $this->db->join('locations', 'locations.location_id=sales.location_id');
        $this->db->where('sales.sale_time BETWEEN '.$this->db->escape($start_date).' and '.$this->db->escape($end_date));
        $this->db->group_by('sales.location_id');
        $this->db->limit(10);
        
        $result=$this->db->get()->result_array();

        if ($result != NULL) 
        {
            return $result;
        }
    }
    // ventas totales por tienda en dienero
    public function get_sales_store_money($start_date = false, $end_date = false){
        $final_result = array();
        $x = 0;
        $start_date ? $start_date : $start_date = date("Y-m-d");
        $end_date ? date($end_date) : $end_date = date("Y-m-d",strtotime("1 day"));
        
        $this->db->select('locations.name');
        $this->db->select_sum('sales_payments.payment_amount','Total');
        $this->db->from('sales');
        $this->db->join('locations', 'locations.location_id=sales.location_id');
        $this->db->join('sales_payments', 'sales_payments.sale_id=sales.sale_id');
        $this->db->where('sales.sale_time BETWEEN '.$this->db->escape($start_date).' and '.$this->db->escape($end_date));
        $this->db->group_by('sales.location_id');
        $this->db->limit(10);
        
        $result=$this->db->get()->result_array();

        if ($result != NULL) 
        {
            foreach ($result as $key => $value) 
            {
                $final_result[$key] = $value;
                $final_result[$key]['color'] = '#'.substr(md5(time().$x), 0, 6);
                $x++;            
            }

            return $final_result;
        } 
    }

    // ganancias por dias
    public function get_sales_earnings_monsth_day($date='2019'){
        $final_result = array();
        $x = 0;
        $start_date=$date.'-01-01';
        $end_date =$date.'-12-31';

        $this->db->select('DATE_FORMAT(sale_time, ("%m"))  AS fecha ');
        $this->db->select('ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased),(0)) as profit');
        $this->db->from('sales');
        $this->db->join('sales_items', 'sales_items.sale_id=sales.sale_id');
        $this->db->where('sales.sale_time >=',$start_date);
        $this->db->where('sales.sale_time <',$end_date);
        $this->db->order_by('DATE_FORMAT(sale_time, ("%m"))');
        
        $result=$this->db->get()->result_array();

        if ($result != NULL) 
        {
            $total=0;
            $fecha_dia=$result[0]['fecha'];
            foreach ($result as $key => $value) 
            {
                $fecha_dia_new=$value['fecha'];
                if($fecha_dia==$fecha_dia_new){
                    $total+=$value['profit'];
                }else{
                    $final_result[$x]['profit'] = $total;
                    $final_result[$x]['fecha'] = $this->get_mes_year($fecha_dia);
                    $total=$value['profit'];
                    $fecha_dia=$fecha_dia_new;
                    $x++;
                   
                }           
            }
            $final_result[$x]['profit'] = $total;
            $final_result[$x]['fecha'] = $this->get_mes_year($fecha_dia);
            return $final_result;
        } 
    }
    public function get_mes_year($mes){
        switch($mes){
            case 1: $mes="Ene"; break;
            case 2: $mes="Feb"; break;
            case 3: $mes="Mar"; break;
            case 4: $mes="Abr"; break;
            case 5: $mes="May"; break;
            case 6: $mes="Jun"; break;
            case 7: $mes="Jul"; break;
            case 8: $mes="Ago"; break;
            case 9: $mes="Sep"; break;
            case 10: $mes="Oct"; break;
            case 11: $mes="Nov"; break;
            case 12: $mes="Dic"; break;
         }
         return $mes;
    }
    
}