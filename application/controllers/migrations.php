<?php

require_once ("secure_area.php");
class Migrations extends Secure_area 
{
    function update()
    {
        $this->load->library('migration');
        $response = array();

        if(isset($_POST) and !empty($_POST))
        {
            if(!$this->migration->version(DATABASE_VERSION))
            {
                $response = array(
                    'success' => 0,
                    'message' => $this->migration->error_string()  //show_error( $this->migration->error_string() )
                );
            }
            else
            {
                sleep(10); 
                $batch_save_data = array("database_version" => DATABASE_VERSION );
    
                $this->Appconfig->batch_save($batch_save_data);   
                /*$this->Appconfig->update_config();
                $this->db->where('key', 'database_version');
                $result = $this->db->update( 'app_config', array('value' => DATABASE_VERSION ) );*/
                $response= array(
                    'success'=>1,
                    'message'=>"exito"
                );
            }
        } else   $response= array(
            'success'=>3,
            'message'=>"No envio dato pos" );  

        echo json_encode($response);  


    }
    function index()
    {              
        $this->load->view("migrations");
    }
    
}