<?php
class Subscriptions extends CI_Model 
{
    protected $login_db;
    
    function __construct()
	{
		parent::__construct();
        $this->config->load('payu');
        $this->login_db = $this->load->database('login',true);
	}
    
    function time_to_add($value)
    {
        switch ($value) 
        {
            case $this->config->item('one_month'):
                $added_time = "+1 month";
                break;
                
            case $this->config->item('two_months'):
                $added_time = "+2 months";
                break;
                
            case $this->config->item('three_months'):
                $added_time = "+3 months";
                break;
                
            case $this->config->item('six_months'):
                $added_time = "+6 months";
                break;
                
            case $this->config->item('one_year'):
                $added_time = "+1 year";
                break;
                
            case $this->config->item('renovation'):
                $added_time = "+1 year";
                break;
            
            default:
                $added_time = false;
                break;
        }
        
        return $added_time;
    }
    
    function license_is_expired($license)
    {
        $expire_date        = $this->login_db->get_where('stores', array('license' => $license), 1 )->row()->expire_date;
        $current_date       = date("Y-m-d H:i:s");
        return ($expire_date < $current_date) ? true : false;
    }
    
    function renew_subscription($license,$value)
    {
        $expire_date        = $this->login_db->get_where('stores', array('license' => $license), 1 )->row()->expire_date;
        $current_date       = date("Y-m-d H:i:s");
        $added_time         = $this->time_to_add($value);
        
        if($added_time !== false)
        {
            if( $this->license_is_expired($license) )
            {
                $updated_expire_date = date( "Y-m-d H:i:s" ,strtotime($added_time));
            }
            else 
            {
                $updated_expire_date = date ( 'Y-m-d H:i:s' , strtotime ( $added_time , strtotime ( $expire_date ) ) );
            }
            
            $data = array('expire_date' => $updated_expire_date);
            $this->login_db->where('license', $license);
            $this->login_db->update('stores', $data);
        }
    }
    
    function save_transaction_data($data)
    {
        if($this->login_db->insert('payments', $data))
        {
            return true;
        }
    	 
        return false;
    }
    
    function get_payments()
    {
        $login_db = $this->load->database('login', TRUE);
        
        $login_db->select('description,value,payment_method,transaction_date');
        $login_db->from('payments');
        $login_db->where('extra1',md5('ingeniando'.$this->db->database));
        
        return $login_db->get()->result_array();
    }
    
    function get_license_type($license)
    {
        $this->login_db->select('license_type');
        $this->login_db->from('stores');
        $this->login_db->where('license',$license);
        
        return $this->login_db->get()->row()->license_type;
        
    }
    
    function set_license_type($license, $current_license_type, $updated_license_type)
    {
        if( $current_license_type !== 'lifetime' )
        {
            $this->login_db->where('license', $license);
            $this->login_db->update('stores', array('license_type' => $updated_license_type));
        }
    }
    
    function get_register_quantity($license)
    {
        $this->login_db->select('max_registers');
        $this->login_db->from('stores');
        $this->login_db->where('license',$license);
        
        return $this->login_db->get()->row()->max_registers;
    }
    
    function update_register_quantity($value, $license, $register_value)
    {
        $registers_to_add  = $value  / $register_value;
        $current_registers = $this->get_register_quantity($license);
        $total             = $registers_to_add + $current_registers;
        
        $data              = array('max_registers' => $total);
        $this->login_db->where('license', $license);
        $this->login_db->update('stores', $data);
    }
    
}