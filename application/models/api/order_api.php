<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_api extends CI_Model {

    private $api;

    function __construct() {
        parent::__construct();
        $this->api = $this->load->database('shop_api', TRUE);              
    }


    function get_all($id=null, $limit=10000, $offset=0,$col='item_id',$order='desc')
    {
            $this->api = $this->load->database('shop_api', TRUE); 
            $this->api->select('*'); 
            $this->api->from('orders');  
            $this->api->join('orders_clients', 'orders_clients.for_id = orders.id');
            $this->api->where('orders.deleted',0);

            if ($id!='all') {
                $this->api->where('orders.id',$id);
            }
            
            $this->api->limit($limit);
            $this->api->offset($offset);
            return $this->api->get();
    }


    function exists($order_id)
    {
                $this->api = $this->load->database('shop_api', TRUE); 
                $this->api->from('orders');
                $this->api->where('order_id',$order_id);
                $query = $this->api->get();
                return ($query->num_rows()>=1); 
    }


    function set_orden($post)
    {

                    $this->api = $this->load->database('shop_api', TRUE); 
                    $this->api->trans_begin();

                    $lastId=false;

                    $data1=array(
                                'order_id' => $post['order_id'],
                                'products' => $post['products'],
                                'date' => $post['date'],
                                'referrer' => $post['referrer'],
                                'clean_referrer' => $post['clean_referrer'],
                                'payment_type' => $post['payment_type'],
                                'paypal_status' => @$post['paypal_status'],
                                'discount_code' => @$post['discountCode'],
                                'user_id' => $post['user_id']
                                );

                    if (!$this->api->insert('orders', $data1)) {
                        log_message('error', print_r($this->api->_error_message(), true));
                    }

                    $lastId = $this->api->insert_id();
               
                    $data2=array(
                                'for_id' =>  $lastId,
                                'first_name' => $post['first_name'],
                                'last_name' => $post['last_name'],
                                'email' => $post['email'],
                                'phone' => $post['phone'],
                                'address' => $post['address'],
                                'city' => $post['city'],
                                'post_code' => $post['post_code'],
                                'notes' => $post['notes']
                                );  
                   
                    if (!$this->api->insert('orders_clients', $data2)) {
                        log_message('error', print_r($this->api->_error_message(), true));
                    }

                    if ($this->api->trans_status() === FALSE) {
                        $this->api->trans_rollback();
                        return false;
                    } else {
                        $this->api->trans_commit();
                        return array_merge($data1,$data2);
                    }


    }

    function set_orden_update_status($post)
    {
                   $this->api = $this->load->database('shop_api', TRUE); 
                   $data=array('processed' => $post['processed'],'viewed' => $post['viewed']);
                   $this->manageQuantitiesAndProcurement($post['order_id'], $post['processed']);
                   $this->api->where('order_id',$post['order_id']);
            return $this->api->update('orders', $data);
    }

    function inventory_insert($inventory_data)
    {
            $this->api = $this->load->database('shop_api', TRUE); 
            return $this->api->insert('inventory',$inventory_data);
    }


    private function manageQuantitiesAndProcurement($id, $to_status)
    {
                $this->api = $this->load->database('shop_api', TRUE); 

                if ($to_status == 0 || $to_status == 2) {
                    $operator = '+';
                }

                if ($to_status == 1) {
                    $operator = '-';
                }
                $this->api->select('products');
                $this->api->where('order_id', $id);
                $result = $this->api->get('orders');
                $arr = $result->row_array();
                $products = unserialize($arr['products']);

                $cantidad=0;

                foreach ($products as $product_id => $quantity) 
                {
                    $cur_item_location_info = $this->Item_location_get_info($product_id);

                               if ($operator=='+') {
                                            $cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;
                                            $cantidad=$cur_item_location_info->quantity + $quantity;
                                            $this->save_quantity($cantidad, $product_id);

                                }else if($operator=='-'){
                                            $cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;
                                            $cantidad=$cur_item_location_info->quantity - $quantity;
                                            $this->save_quantity($cantidad, $product_id);
                                }

                }



    }



    function Item_location_get_info($item_id,$location=false)
    {

                $this->api = $this->load->database('shop_api', TRUE); 

                if(!$location)
                {
                    $location= 1;
                }
                
                $this->api->from('location_items');
                $this->api->where('item_id',$item_id);
                $this->api->where('location_id',$location);
                $query = $this->api->get();

                if($query->num_rows()==1)
                {
                    $row = $query->row();
                    
                    //Store a boolean indicating if the price has been overwritten
                    $row->is_overwritten = ($row->cost_price !== NULL ||
                    $row->unit_price !== NULL ||
                    $row->promo_price !== NULL || 
                    $this->is_tier_overwritten($item_id, $location));
                    return $row;
                
                }
                else
                {
                    //Get empty base parent object, as $item_id is NOT an item_location
                    $item_location_obj=new stdClass();

                    //Get all the fields from item_locations table
                    $fields = $this->api->list_fields('location_items');

                    foreach ($fields as $field)
                    {
                        $item_location_obj->$field='';
                    }
                    
                    $item_location_obj->is_overwritten = FALSE;

                    return $item_location_obj;
                }
                

    }

    function save_quantity($quantity, $item_id, $location_id=false)
    {
                $this->api = $this->load->database('shop_api', TRUE); 
                if(!$location_id)
                {
                    $location_id= 1;
                }
                
                $sql = 'INSERT INTO '.$this->api->dbprefix('location_items'). ' (quantity, item_id, location_id)'
                    . ' VALUES (?, ?, ?)'
                    . ' ON DUPLICATE KEY UPDATE quantity = ?'; 
                
                return $this->api->query($sql, array($quantity, $item_id, $location_id,$quantity));      
    }


    function is_tier_overwritten($item_id, $location_id)
    {
                $this->api = $this->load->database('shop_api', TRUE); 
                $this->api->from('location_items_tier_prices');
                $this->api->where('item_id',$item_id);
                $this->api->where('location_id',$location_id);
                $query = $this->api->get();
                return ($query->num_rows()>=1);
    }


}