<?php 
 
class Migration_Customers_colum extends CI_Migration
{
    
    public function up()
	{

		// nueva columna others customers
		$this->db->query("ALTER TABLE `phppos_customers` ADD `other` VARCHAR(250) NULL AFTER `deleted`");

    }
	public function down()
	{		
		$this->db->query("ALTER TABLE phppos_customers DROP other");

	}
    
}