<?php 
 
class Migration_Add_pendiente extends CI_Migration
{
    
    public function up()
	{		
		$this->db->query("INSERT INTO `phppos_app_config` (`key`, `value`) VALUES ('profile_id', '0')");
		$this->db->query("CREATE TABLE `phppos_hide_video` (
			`employee_id` int(11) NOT NULL,
			`module_id` varchar(100) NOT NULL) ");

		$this->db->query("ALTER TABLE `phppos_hide_video` ADD UNIQUE KEY `employee_id` (`employee_id`,`module_id`)");

		$this->db->query("ALTER TABLE `phppos_hide_video` ADD CONSTRAINT `phppos_hide_video_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`)");
	}


	public function down()
	{		
		$this->db->query("DROP TABLE phppos_hide_video");			

	}
    
}