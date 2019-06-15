<?php 
 
class Migration_Categories extends CI_Migration
{
    
    public function up()
	{		
		$this->load->model('Categories');

		foreach ($this->Item->get_all_categories()->result() as $category) {
            $category = $category->category;
            $this->Categories->save(-1, array("name"=>$category,"img"=>"","name_img_original"=>""));
        }
	}


	public function down()
	{		
		
	}
    
}