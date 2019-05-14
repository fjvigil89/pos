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
    
}