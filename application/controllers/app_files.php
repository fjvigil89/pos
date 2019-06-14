<?php
class App_files extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function view($file_id)
	{ 
		$file = $this->Appfile->get($file_id);
		
			header("Content-type: ".get_mime_by_extension($file->file_name));
			echo $file->file_data;
	
	}
	function view_notification($notification_id)
	{ 
		$file = $this->Appfile->get_notification($notification_id);
		
		header("Content-type: ".get_mime_by_extension("image/*"));
		echo $file->img;
	
	}
	function view_transfer($file_id)
	{
		$file = $this->Appfile->get_transfer($file_id);
	
		
		if($file != "")
		{
			header("Content-type: ".get_mime_by_extension($file->name_file));
			echo $file->data_file;
		}
		else{
			header('Content-Type: '.get_mime_by_extension("./img/no-image.png"));
			readfile("./img/no-image.png");
		}
		
	}
	function view_logo_store($file_id)
	{ 
		$file = $this->Appfile->get_logo($file_id);

		header("Content-type: ".get_mime_by_extension( "logo.".$file->system_logo_type));
		echo $file->system_logo_blob;
	}

}
?>