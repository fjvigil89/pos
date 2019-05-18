<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_api extends CI_Model {

    private $api;

    function __construct() {
        parent::__construct();
        $this->api = $this->load->database('shop_api', TRUE);              
    }


    function get_all($id=null, $limit=10000, $offset=0,$col='item_id',$order='desc')
    {
     
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
                $this->api->from('orders');
                $this->api->where('order_id',$order_id);
                $query = $this->api->get();
                return ($query->num_rows()>=1); 
        }


        function set_orden($post)
        {

        $api = $this->load->database('shop_api', TRUE); 
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

                    if (!$api->insert('orders', $data1)) {
                        log_message('error', print_r($api->error(), true));
                    }

                    $lastId = $api->insert_id();
               
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
                   
                    if (!$api->insert('orders_clients', $data2)) {
                        log_message('error', print_r($api->error(), true));
                    }


            return $post['order_id'];



        }

        function set_orden_update_status($post)
        {

            $api = $this->load->database('shop_api', TRUE); 
                    $data=array('processed' => $post['processed'],
                                'viewed' => $post['viewed']);




            $this->manageQuantitiesAndProcurement($post['order_id'], $post['processed']);

                    
                   $api->where('order_id',$post['order_id']);
            return $api->update('orders', $data);
        }

            function inventory_insert($inventory_data)
            {
                $api = $this->load->database('shop_api', TRUE);     
                return $api->insert('inventory',$inventory_data);
            }


            private function manageQuantitiesAndProcurement($id, $to_status)
            {

        $api = $this->load->database('shop_api', TRUE); 

                if ($to_status == 0 || $to_status == 2) {
                    $operator = '+';
                }

                if ($to_status == 1) {
                    $operator = '-';
                }
                $api->select('products');
                $api->where('order_id', $id);
                $result = $api->get('orders');
                $arr = $result->row_array();
                $products = unserialize($arr['products']);

        $cantidad=0;

                foreach ($products as $product_id => $quantity) {

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


                                /*$qty_buy =$operator.$quantity;
                                $sale_remarks ="PEDIDO ".$id;
                                $inv_data = array
                                (
                                    'trans_date'=>date('Y-m-d H:i:s'),
                                    'trans_items'=>$product_id,
                                    'trans_user'=>1,
                                    'trans_comment'=>$sale_remarks,
                                    'trans_inventory'=>$qty_buy,
                                    'location_id' => 1
                                );

                                $this->inventory_insert($inv_data);*/



                }



            }



            function Item_location_get_info($item_id,$location=false)
            {


                $api = $this->load->database('shop_api', TRUE); 

                if(!$location)
                {
                    $location= 1;
                }
                
                $api->from('location_items');
                $api->where('item_id',$item_id);
                $api->where('location_id',$location);
                $query = $api->get();

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
                    $fields = $api->list_fields('location_items');

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

                $api = $this->load->database('shop_api', TRUE); 
                if(!$location_id)
                {
                    $location_id= 1;
                }
                
                $sql = 'INSERT INTO '.$api->dbprefix('location_items'). ' (quantity, item_id, location_id)'
                    . ' VALUES (?, ?, ?)'
                    . ' ON DUPLICATE KEY UPDATE quantity = ?'; 
                
                return $api->query($sql, array($quantity, $item_id, $location_id,$quantity));      
            }


            function is_tier_overwritten($item_id, $location_id)
            {
                $api = $this->load->database('shop_api', TRUE); 
                $api->from('location_items_tier_prices');
                $api->where('item_id',$item_id);
                $api->where('location_id',$location_id);
                $query = $api->get();

                return ($query->num_rows()>=1);
            }

        }