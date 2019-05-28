<?php
class Notification extends CI_Model{
  
   
  function exists($notification_id)
  {
      $employee_id =  $this->session->userdata('person_id');
      $this->db->select('notification_id');
      $this->db->from('notifications_see');
      $this->db->where('employee_id',  $employee_id);
      $this->db->where('notification_id', $notification_id);
      $query = $this->db->get();

      return $query->num_rows() == 1;
  
  }
  function see_all($type = 1, $for_whom = 1)
  {
      $db2 = $this->load->database('login',true);
      $db2->select("id");
      $db2->from('notifications');
      $db2->where('deleted', 0);
      $db2->where('type', $type);
      $db2->where('for_whom', $for_whom);
      $result = $db2->get()->result();

      foreach ( $result as $_data) {
         if(!$this->exists($_data->id))
         {
            $data = array(
               "state"=> 1,
               "notification_id"=>$_data->id,
               "employee_id"=> $this->session->userdata('person_id'),
               "created" => date('Y-m-d H:i:s')
            );

            $this->db->insert('notifications_see',$data);
         }
      }
  }

  function save($notification_id,$data)
  {
      $employee_id =  $this->session->userdata('person_id');
      $data["employee_id"] = $employee_id;

      if($this->exists($notification_id))
      {
         $this->db->where('employee_id',$employee_id);
         $this->db->where('notification_id',$notification_id);
         return $this->db->update('notifications_see',$data);
      }    
     
      return $this->db->insert('notifications_see',$data);
  }
   function ids_see($state = 2,$all = false)
   {
      $employee_id =  $this->session->userdata('person_id');
      $ids = array();

      $this->db->select('notification_id');
      $this->db->from('notifications_see');
      $this->db->where('employee_id',  $employee_id);
      if(!$all)
         $this->db->where('state', $state);

      $result = $this->db->get()->result();
      
      foreach ($result as $data) {
         $ids[] =  $data->notification_id;
      }

      return $ids;

   }
  
   /**
    * permite contar  las notificaciones que estan pendiente o que no ha visto
    */   
   function count_pending($type = 1 ,  $for_whom = 1)
   {
       
      $db2 = $this->load->database('login',true);
      $ids_see = $this->ids_see(0,true);  
      $ids_see = count( $ids_see) == 0 ? array(0) : $ids_see;
      $db2->from('notifications');
      $db2->where('deleted', 0);
      $db2->where('type', $type);
      $db2->where('for_whom', $for_whom);      
      $db2->where_not_in('id',$ids_see);
      $total = $db2->count_all_results();

      return $total;
      
   }
   function get_info($id)
   {
      $db2 = $this->load->database('login',true);       
      $db2->from('notifications');
      $db2->where('id', $id);
      $db2->where('deleted', 0);      
      $result = $db2->get()->row();
      return $result;
   }
   function get_all($limit = 12, $offset = 0, $type = 1, $for_whom = 1)
   {
      
      $db2 = $this->load->database('login',true);      
      $ids_see = $this->ids_see();
      $inSql = implode(",",$ids_see);
      $inSql = $inSql == "" ? "0" : $inSql;
            
      $db2->select("notifications.*, IF(id IN(". $inSql."), 1,0 )  as is_saw",false);
      $db2->from('notifications');
      $db2->where('deleted', 0);
      $db2->where('type', $type);
      $db2->where('for_whom', $for_whom);
      $db2->order_by("id", "desc");
      $db2->limit($limit);
      $db2->offset($offset);      
      $result = $db2->get()->result();

      return $result;
   }
}
?>