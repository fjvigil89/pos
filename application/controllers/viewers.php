<?php  if (!defined('BASEPATH')) 
exit('No direct script access allowed');
require_once ("secure_area.php");
class Viewers extends  Secure_area
{
    function __construct() {
        parent::__construct('viewers');

        $this->load->library('viewer_lib');
        $this->load->model('Viewer');
        $this->load->model('Viewer_file');       

    }
    function save_viewer()
    {
        $batch_save_data = array(
            "show_carrousel" => $this->input->get('show_carrousel'),
            "show_viewer" => $this->input->get('show_viewer')
        );

		$this->Appconfig->batch_save($batch_save_data) ;
    }
    function delete()
    {
        $img_to_delete = $this->input->post('ids');
        $path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img";

        if ($this->Viewer_file->delete_list($img_to_delete, $path_long)) 
        {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_deleted') ."item"));
        }
        else 
        {
            echo json_encode(array('success' => false, 'message' => lang('items_cannot_be_deleted')));
        }
    }
    function table_manage()
    {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $data_list = $this->Viewer_file->get_list_by_location($location_id);
        $manage_table =  get_img_carousel_manage_table($data_list,"viewers");
        
        echo $manage_table;
    }
    function index()
    {
        $data = array();
        $data["controller_name"] = "viewers";
        $data["employee_id"] = $this->Employee->person_id_logged_in();
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $data_list = $this->Viewer_file->get_list_by_location($location_id);
        $data["manage_table"] =  get_img_carousel_manage_table($data_list,$data["controller_name"]);

        $this->load->view("viewer/index", $data);
    }
    function view_img($id = -1)
    {
        $data = array(
            "image_data"=>$this->Viewer_file->get_info($id),
            "store" => $this->Employee->get_store(),
            "id" => is_numeric($id) ? $id :-1,
        );

        $this->load->view("viewer/img_modal", $data);
    }
    function save_img($id = -1)
    {
    
       $response = array("success" => true, "message"=>"");
       $is_guarded = true;
            
       $path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img";
       $image_data = $this->Viewer_file->get_info($id);
       $name_file = $image_data->new_name;
       $original_name = $image_data->original_name;  
       $location_id = $this->Employee->get_logged_in_employee_current_location_id();
       $title = $this->input->post('title') ? $this->input->post('title'): null;
       $description = $this->input->post('description') ? $this->input->post('description'): null;
       
       if($id <= 0 and empty($_FILES["image"]))
       {
            echo json_encode( array("success" => false, "message"=>"Imagen es requerida"));
            return;
       }
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
            
            if($id != -1)           
                unlink($path_long."/". $image_data->new_name);

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
        elseif($id <= 0) 
        {
            $response = array(
                "success" => false,
                "message" =>"No se pudo cargar la imagen.");
            $is_guarded = false;
        }
        if($is_guarded)
        {
            $data = array(                
                "type" => 1, // 1 para carousel del visor
                "location_id" => $location_id,
                "title" => $title,
                "description" => $description,
                "original_name" => $original_name ,
                "new_name" => $name_file,
            );
            
           if(!$this->Viewer_file->save($id,$data)){
                unlink($path_long."/". $name_file);
                $response = array(
                    "success" => false,
                    "message" =>"No se pudo guardar los datos.");
           }
        }  
       
       echo json_encode($response);
    }

   
    

}