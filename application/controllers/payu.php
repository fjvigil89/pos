<?php
class Payu extends CI_Controller 
{
    function __construct()
	{
		parent::__construct();
		$this->load->config('payu');
        $this->load->model('Subscriptions');
	}
    
    function index()
	{
        //pseudo POST de payu
        #$_POST['extra1']            = 'b1d466312b47900523fb0acf9cfe6886';
        #$_POST['value']             = '143';
        #$_POST['transaction_id']   = '99000';
        #$_POST['state_pol']         = 4;
        #$_POST['description']      = 'Pago por 1 mes';
        #$_POST['currency']         = 'USD';
        #$_POST['payment_method']   = 'payu';
        #$_POST['transaction_date'] = '2017-05-11 10:16:10';
        #$_POST['phone']            = '02413445766';
        
        if( isset($_POST)  and !empty($_POST) )
        {
            if( $_POST['state_pol'] == 4 and $_POST['currency'] == $this->config->item('payu_currency') )
            {
                $license      = $_POST['extra1'];
                $value        = $_POST['value'];
                $license_type = $this->Subscriptions->get_license_type($_POST['extra1']);
                
                $data = array(
                    'transaction_id'   => $_POST['transaction_id'] ,
                    'state_pol'        => $_POST['state_pol'] ,
                    'description'      => $_POST['description'] ,
                    'value'            => $_POST['value'] ,
                    'currency'         => $_POST['currency'] ,
                    'payment_method'   => $_POST['payment_method'] ,
                    'transaction_date' => $_POST['transaction_date'] ,
                    'extra1'           => $_POST['extra1'] ,
                    'phone'            => $_POST['phone'] 
                );
                
                $this->Subscriptions->save_transaction_data($data);
                
                //si el pago es una renovación el cliente debe tener una subscripcion de por vida
                if( !($this->config->item('renovation') == $value and $license_type !== 'lifetime') )
                {
                    if($this->config->item('one_year') == $_POST['value'])
                    {
                        $updated_license_type = 'lifetime';
                    }
                    else 
                    {
                        $updated_license_type = 'monthly';
                    }
                    
                    $this->Subscriptions->renew_subscription($license,$value); 
                    $this->Subscriptions->set_license_type($license, $license_type, $updated_license_type); 
                }
            }
        }
        else 
        {
            redirect('home');
        }
    }
    
    function update_register_quantity()
    {
        /*
        $_POST['value']  = 15;
        $_POST['extra1'] = 'b1d466312b47900523fb0acf9cfe6886';
        */
        
        if( isset($_POST)  and !empty($_POST) )
        {
            if( isset($_POST)  and !empty($_POST) )
            {
                $value          = $_POST['value'];
                $license        = $_POST['extra1'];
                $register_value = $this->config->item('register_value');
                
                $this->Subscriptions->update_register_quantity($value, $license, $register_value);
            }
        }
    }
    
}