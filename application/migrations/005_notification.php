<?php 
 
class Migration_Notification extends CI_Migration
{
    
    public function up()
	{
		
		$this->db->query("CREATE TABLE `phppos_notifications_see` (
			`notification_id` int(11) NOT NULL,
			`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`employee_id` int(11) NOT NULL,
			`state` tinyint(4) NOT NULL DEFAULT '1' ) 
			");      
		
		$this->db->query("ALTER TABLE `phppos_notifications_see`
					ADD PRIMARY KEY (`notification_id`,`employee_id`)");
	}
	public function down()
	{		
		$this->db->query("DROP TABLE phppos_notifications_see");
	}
    
}