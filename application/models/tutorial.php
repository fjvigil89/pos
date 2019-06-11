<?php
class Tutorial extends CI_Model
{	
    
    function get_tutorial( $module_id = "home", $profiles_id = 4)
	{
        $login_db = $this->load->database('login',true); 
        $login_db->select($login_db->dbprefix('tutorials_videos').'.*');
        $login_db->from('tutorials_videos');
        $login_db->join('turorials', 'turorials.id = tutorials_videos.tutorial_id');	
        $login_db->join('profiles', 'profiles.tutorial_id = turorials.id');	
        $login_db->where($login_db->dbprefix('profiles').".id",$profiles_id);  
        $login_db->where($login_db->dbprefix('tutorials_videos').'.module_id',$module_id); 
        //$login_db->where($login_db->dbprefix('turorials').'.deleted',0);       
        $query =  $login_db->get();
        return $query->result();
    }

   /* function previous($module_id,$tutorial_id)
    {
        $login_db = $this->load->database('login',true);
        $login_db->select('MAX('.$login_db->dbprefix("tutorials_videos").'.id) as id,module_id',false);
        $login_db->join('turorials', 'turorials.id = tutorials_videos.tutorial_id');	
        $login_db->from('tutorials_videos');	
        $login_db->where('next_module_id',$module_id); 
        $login_db->where($login_db->dbprefix('turorials').".id",$tutorial_id); 
        //$login_db->where($login_db->dbprefix('turorials').'.deleted',0); 

        $query =  $login_db->get();        
        return $query->row();
    }   */
}
?>