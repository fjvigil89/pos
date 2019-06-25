<?php
class Tutorial extends CI_Model
{	
    
    function get_tutorial( $module_id = "home", $profiles_id = 4)
	{
        $login_db = $this->load->database('login',true); 
        $login_db->select($login_db->dbprefix('tutorials_videos').'.*');
        $login_db->from('tutorials_videos');
        $login_db->join('tutorials', 'tutorials.id = tutorials_videos.tutorial_id');	
        $login_db->join('profiles', 'profiles.tutorial_id = tutorials.id');	
        $login_db->where($login_db->dbprefix('profiles').".id",$profiles_id);  
        $login_db->where($login_db->dbprefix('tutorials_videos').'.module_id',$module_id); 
        //$login_db->where($login_db->dbprefix('tutorials').'.deleted',0);       
        $query =  $login_db->get();
        return $query->result();
    }
    function is_hide_video($module_id, $employee_id)
    {
        // $this->Employee->person_id_logged_in()
        $this->db->from('hide_video');	
        $this->db->where('employee_id',$employee_id);
        $this->db->where('module_id',$module_id);
        $query = $this->db->get();        		
        return ($query->num_rows() == 1);
    }
    function hide_video($module_id, $employee_id)
    {
        // $this->Employee->person_id_logged_in()
        $this->db->from('hide_video');	
        $this->db->insert('hide_video', 
        array(
            "module_id" => $module_id,
            "employee_id"=>$employee_id
        ));       
    }
    function show_video($module_id, $employee_id)
    {
        $this->db->delete('hide_video', array('module_id' => $module_id,"employee_id"=>$employee_id));
       
    }
   /* function previous($module_id,$tutorial_id)
    {
        $login_db = $this->load->database('login',true);
        $login_db->select('MAX('.$login_db->dbprefix("tutorials_videos").'.id) as id,module_id',false);
        $login_db->join('tutorials', 'tutorials.id = tutorials_videos.tutorial_id');	
        $login_db->from('tutorials_videos');	
        $login_db->where('next_module_id',$module_id); 
        $login_db->where($login_db->dbprefix('tutorials').".id",$tutorial_id); 
        //$login_db->where($login_db->dbprefix('tutorials').'.deleted',0); 

        $query =  $login_db->get();        
        return $query->row();
    }   */
}
?>