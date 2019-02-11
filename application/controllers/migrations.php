<?php

require_once ("secure_area.php");
class Migrations extends Secure_area 
{
    
    function index()
    {
        $this->load->library('migration');
        
        if(isset($_POST) and !empty($_POST))
        {
            if(!$this->migration->current())
            {
                echo json_encode(array(
                    'success' => false,
                    'message' => show_error( $this->migration->error_string() )
                ));
            }
            else
            {
                sleep(60); 
                $this->Appconfig->update_config();
                $this->db->where('key', 'database_version');
                $this->db->update( 'app_config', array('value' => DATABASE_VERSION ) );
                echo json_encode(array(
                    'success'=>true,
                    'message'=>"exito"
                ));
            }
        }
        else
        {
            $this->load->view("migrations");
        }
    }
    
}