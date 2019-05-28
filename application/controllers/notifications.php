<?php  if (!defined('BASEPATH')) 
exit('No direct script access allowed');
require_once ("secure_area.php");
class Notifications extends  Secure_area
{
    function __construct() {
        parent::__construct();
    }

    function  view($id = -1)
    {

        $data["notification"] = $this->Notification->get_info($id);
        $data["exists"] = count($data["notification"]) == 0;

        if(!$data["exists"] )
        {
            $data_new = array(
                "state"=> 2,
                "notification_id"=>$id,
                "employee_id"=> $this->session->userdata('person_id'),
                "created" => date('Y-m-d H:i:s')
            );
            $this->Notification->save($id,$data_new);
        }
        
        $this->load->view("notificaciones/view", $data);
    }

    function show()
    {
        $data["notifications"] = $this->Notification->get_all(10000);
        $this->load->view("notificaciones/list" ,$data);
    }
    function seen()
    {
        $this->Notification->see_all();
    }
}