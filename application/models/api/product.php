<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends CI_Model {


     function __construct() 
     {
     parent::__construct();

     } 

    function get_all_confi()
    {
        $db_api = $this->load->database('shop_api', TRUE);
        $db_api->from('app_config');
        $db_api->order_by("key", "asc");
        return $db_api->get();        
    }



    function get_all($id=null, $limit=10000, $offset=0,$col='item_id',$order='desc')
        {
            $db_api = $this->load->database('shop_api', TRUE);
            $current_location=1;        
            $db_api->select('items.item_id as id, suppliers.company_name as supplier_company_name, location_items.location as location, items.*, 
            location_items.quantity as quantity, 
            location_items.reorder_level as location_reorder_level,
            location_items.cost_price as location_cost_price,
            location_items.unit_price as location_unit_price,
            app_files.file_name as file_name_img,

            ');
            
            $db_api->from('items');
            $db_api->join('suppliers', 'items.supplier_id = suppliers.person_id', 'left');
            $db_api->join('app_files', 'items.image_id = app_files.file_id', 'left');
            $db_api->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');


            $db_api->group_by('items.item_id');
            $db_api->where('items.deleted',0);
            $db_api->where('items.shop_online',1);


            if ($id!='all') {
            $db_api->where('items.item_id',$id);
            }
            
            
            $db_api->limit($limit);
            $db_api->offset($offset);


            $query = $db_api->get();

            $data = array();
            if($query !== FALSE && $query->num_rows() > 0){
                $data = $query->result_array();
            }



            return $data;
        }


        function get($file_id)
        {
                     $db_api = $this->load->database('shop_api', TRUE);
            $query = $db_api->get_where('app_files', array('file_id' => $file_id), 1);
            
            $db_api->where('file_id',$file_id,1);

            if($query->num_rows()==1)
            {
                return $query->row();
            }
            
            return "";
            
        }

    function getfile($file_id)
    {
        $db_api = $this->load->database('shop_api', TRUE);
        $query = $db_api->get_where('app_files', array('file_id' => $file_id), 1);
        
        if($query->num_rows()==1)
        {
            return $query->row();
        }
        
        return "";  
    }


    function get_info_tax()
    {

        
        //Global Store Config
        $default_tax_1_rate = $this->config->item('default_tax_1_rate');
        $default_tax_1_name = $this->config->item('default_tax_1_name');
                
        $default_tax_2_rate = $this->config->item('default_tax_2_rate');
        $default_tax_2_name = $this->config->item('default_tax_2_name');
        $default_tax_2_cumulative = $this->config->item('default_tax_2_cumulative') ? $this->config->item('default_tax_2_cumulative') : 0;
        
        $default_tax_3_rate = $this->config->item('default_tax_3_rate');
        $default_tax_3_name = $this->config->item('default_tax_3_name');
        
        $default_tax_4_rate = $this->config->item('default_tax_4_rate');
        $default_tax_4_name = $this->config->item('default_tax_4_name');
        
        $default_tax_5_rate = $this->config->item('default_tax_5_rate');
        $default_tax_5_name = $this->config->item('default_tax_5_name');
        
        $return = array();
        
        if ($default_tax_1_rate)
        {
            $return[] = array(
                'id' => 1,
                'name' => $default_tax_1_name,
                'percent' => $default_tax_1_rate,
                'cumulative' => 0
            );
        }
        
        if ($default_tax_2_rate)
        {
            $return[] = array(
                'id' => 2,
                'name' => $default_tax_2_name,
                'percent' => $default_tax_2_rate,
                'cumulative' => $default_tax_2_cumulative
            );
        }

        if ($default_tax_3_rate)
        {
            $return[] = array(
                'id' => 3,
                'name' => $default_tax_3_name,
                'percent' => $default_tax_3_rate,
                'cumulative' => 0
            );
        }

        if ($default_tax_4_rate)
        {
            $return[] = array(
                'id' => 4,
                'name' => $default_tax_4_name,
                'percent' => $default_tax_4_rate,
                'cumulative' => 0
            );
        }

        if ($default_tax_5_rate)
        {
            $return[] = array(
                'id' => 5,
                'name' => $default_tax_5_name,
                'percent' => $default_tax_5_rate,
                'cumulative' => 0
            );
        }
        
                
        return $return;
    }




}