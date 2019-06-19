<?php  if (!defined('BASEPATH')) 
exit('No direct script access allowed');
require_once ("secure_area.php");
class Category extends  Secure_area
{
    function __construct()
	{
		parent::__construct();
		
		$this->load->model('Categories');
	}
    function get_category($id)
	{
		$info = $this->Categories->get_info($id);
		$data = array("existe"=>is_numeric($info->id), "data"=>$info);

		echo json_encode($data);
	}
	function delete_category($id)
	{
		$path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
		$this->Categories->delete($id,$path_long);
	}
	
	function save_catebory($id = -1)
	{
		if (true) {   
            $response = array("success" => true, "message"=>"");
			$is_guarded = true;
			if ($id < 0 and $this->Categories->category_exists($this->input->post('name'))) {
				$response = array("success" => false, "message"=>"Categoría ya existe");
				echo json_encode($response);
				return;
			}
                    
            $path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
            $category_data = $this->Categories->get_info($id);
            $name_file = $category_data->img;
            $original_name = $category_data->name_img_original;  
            $name = $this->input->post('name') ? $this->input->post('name'): null;
            
            /*if($id <= 0 and empty($_FILES["image"]))
            {
                    echo json_encode( array("success" => false, "message"=>"Imagen es requerida"));
                    return;
            }*/

            if(!file_exists( $path_long))
            {        
                    if(!mkdir($path_long, 0777, true))
                    {               
                        $response = array("success" => false, "message"=>"Error al crear directorio");
                    }
            }
            
            if(!empty($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK)
                {
                    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                    $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));            
                    
                    if($id != -1 and !empty($category_data->img))           
                        unlink($path_long."/". $category_data->img);

                    if (in_array($extension, $allowed_extensions))
                    {
                        $original_name = $_FILES["image"]["name"];
                        $rand = rand(1, 2000);
                        $name_file = time()."-". $rand.".".$extension;
                        
                        while(file_exists( $path_long."/". $name_file))
                        {
                            $name_file = time()."-". $rand.".".$extension;
                            $rand = rand(1, 1000);
                        }                          
						$config['image_library'] = 'gd2';
						$config['source_image'] = $_FILES["image"]["tmp_name"];
						$config['create_thumb'] = false;
						$config['maintain_ratio'] = true;
						$config['width'] = 500;
						$config['height'] = 400;
						$this->load->library('image_lib', $config);
						
						$this->image_lib->resize();

                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['upload_path'] = $path_long;
                        $config['file_name'] = $name_file;
                        $config['max_size'] = "5120";
                        $config['max_width'] = "2000";
						$config['max_height'] = "2000";

                        $this->load->library('upload', $config);
                        
                        if (!$this->upload->do_upload("image")) 
                        {
                            $response = array(
                                "success" => false,
                                "message" =>"Error al subir la imagen");
                            $is_guarded = false;
                        }                                
                    }
                    else
                    {
                        $response = array(
                            "success" => false,
                            "message" =>"El tipo de imagen no es válido.");
                        $is_guarded = false;
                    }           
                }
                elseif(!empty($_FILES["image"])) 
                {
                    $response = array(
                        "success" => false,
                        "message" =>"No se pudo cargar la imagen.");
                    $is_guarded = false;
                }
                if($is_guarded)
                {
                    $data = array(  
                        "name" => $name,
                        "img" => $name_file,
                        "name_img_original" => $original_name,
                    );
                    $is_new = $id ==- 1;
					if(($id = $this->Categories->save($id,$data)) === false){
							unlink($path_long."/". $name_file);
							$response = array(
								"success" => false,
								"message" =>"No se pudo guardar los datos.");
					}
					else
					{
						$data = array("id"=>$id,
							"img"=>$name_file,
							"name"=>$name,
							"is_new" => $is_new
						);

						$response["data"]=$data;

					}					
                }            
            }
            else
                $response = array(
                    "success" => false,
                    "message" =>"No tiene permisos");

        echo json_encode($response);
	}
	function categories_modal()
	{
		
		$path_img =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
		$data["path_img"] = $path_img;
		$data["categories"] = $this->Categories->get_all();
		$this->load->view("categories_modal",$data);
	}
}
?>