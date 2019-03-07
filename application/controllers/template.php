<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends CI_Controller{

    public function __construct()
    {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("Template_model");
    }

    function index()
    {    
        $url=base_url();
        $data['data'] = array('base_url' =>$url );
        $data['productos']=$this->Template_model->get_all();
        $data['category']=$this->Template_model->category();
        $data['logos']=['logotipo'=>$this->get_logo_image()];
        foreach ($this->Template_model->config_shop() as $key )
        {
           $shop_config = array($key->key => $key->value);
           $data = array_merge($data, $shop_config);
        }
        //como hemos creado el grupo registro podemos utilizarlo
        $this->template->set_template('template1');
        //añadimos los archivos css que necesitemoa
        $this->template->add_css('assets/template/css/externo/bootstrap.css');
        $this->template->add_css('assets/template/css/externo/style.css');
        $this->template->add_css('assets/template/css/externo/dark.css');
        $this->template->add_css('assets/template/css/externo/font-icons.css');
        $this->template->add_css('assets/template/css/externo/animate.css');
        $this->template->add_css('assets/template/css/externo/magnific-popup.css');
        $this->template->add_css('assets/template/css/externo/responsive.css');
        $this->template->add_css('assets/template/css/externo/bs-rating.css');
        $this->template->add_css('assets/template/css/main.css');

        //añadimos los archivos js que necesitemoa
        $this->template->add_js('assets/template/js/vendor/jquery.js');
        $this->template->add_js('assets/template/js/vendor/plugins.js');
        //Footer Scripts
        $this->template->add_js('assets/template/js/vendor/functions.js');
        $this->template->add_js('assets/template/js/main.js');
        //Star Rating Plugin
        $this->template->add_js('assets/template/js/vendor/star-rating.js');
        
        //la sección header será el archivo views/registro/header_template
        $this->template->write_view('topbar', 'template/topbar', $data, TRUE);
        $this->template->write_view('header', 'template/header', $data, TRUE);
        $this->template->write_view('slider', 'template/slider', $data, TRUE);
        $this->template->write_view('product', 'template/product', $data, TRUE);
        $this->template->write_view('sidebar', 'template/sidebar', $data, TRUE);
        $this->template->write_view('footer', 'template/footer', $data, TRUE);
        //desde aquí también podemos setear el título
        $this->template->write('title',$data['shop'], TRUE);
        $this->template->write('description',$data['shop_description'], TRUE);
        //obtenemos los usuarios 
        
        
        //el contenido de nuestro formulario estará en views/registro/formulario_registro,
        //de esta forma también podemos pasar el array data a registro/formulario_registro
        $this->template->write_view('content', 'tienda/index', $data, TRUE); 
        //la sección footer será el archivo views/registro/footer_template
        //$this->template->write_view('footer', 'registro/footer_template');   
        //con el método render podemos renderizar y hacer que se visualice la template
        $this->template->render();

    }
    
    
    public function contact()
    {
        $url=base_url();
        $data['data'] = array('base_url' =>$url );
        $data['productos']=$this->Template_model->get_all();
        $data['category']=$this->Template_model->category();
        $data['logos']=['logotipo'=>$this->get_logo_image()];
        foreach ($this->Template_model->config_shop() as $key )
        {
           $shop_config = array($key->key => $key->value);
           $data = array_merge($data, $shop_config);
        }
        //como hemos creado el grupo registro podemos utilizarlo
        $this->template->set_template('template1');
        //añadimos los archivos css que necesitemoa
        $this->template->add_css('assets/template/css/externo/bootstrap.css');
        $this->template->add_css('assets/template/css/externo/style.css');
        $this->template->add_css('assets/template/css/externo/dark.css');
        $this->template->add_css('assets/template/css/externo/font-icons.css');
        $this->template->add_css('assets/template/css/externo/animate.css');
        $this->template->add_css('assets/template/css/externo/magnific-popup.css');
        $this->template->add_css('assets/template/css/externo/responsive.css');
        $this->template->add_css('assets/template/css/externo/bs-rating.css');
        $this->template->add_css('assets/template/css/main.css');

        //añadimos los archivos js que necesitemoa
        $this->template->add_js('assets/template/js/vendor/jquery.js');
        $this->template->add_js('assets/template/js/vendor/plugins.js');
        //Footer Scripts
        $this->template->add_js('assets/template/js/vendor/functions.js');
        $this->template->add_js('assets/template/js/main.js');
        //Star Rating Plugin
        $this->template->add_js('assets/template/js/vendor/star-rating.js');
        
        //la sección header será el archivo views/registro/header_template
        $this->template->write_view('topbar', 'template/topbar', $data, TRUE);
        $this->template->write_view('header', 'template/header', $data, TRUE);
        //$this->template->write_view('slider', 'template/slider', $data, TRUE);
        //$this->template->write_view('product', 'template/product', $data, TRUE);
        //$this->template->write_view('sidebar', 'template/sidebar', $data, TRUE);
        $this->template->write_view('footer', 'template/footer', $data, TRUE);
        //desde aquí también podemos setear el título
        $this->template->write('title',$data['shop'], TRUE);
        $this->template->write('description',$data['shop_description'], TRUE);
        //obtenemos los usuarios
        
        
        //el contenido de nuestro formulario estará en views/registro/formulario_registro,
        //de esta forma también podemos pasar el array data a registro/formulario_registro
        $this->template->write_view('content', 'tienda/contact', $data, TRUE); 
        //la sección footer será el archivo views/registro/footer_template
        //$this->template->write_view('footer', 'registro/footer_template');   
        //con el método render podemos renderizar y hacer que se visualice la template
        $this->template->render();
    }
    
    
    public function category()
    {
        $url=base_url();
        $data['data'] = array('base_url' =>$url );
        $data['productos']=$this->Template_model->get_all();
        $data['category']=$this->Template_model->category();
        $data['logos']=['logotipo'=>$this->get_logo_image()];
        
        foreach ($this->Template_model->config_shop() as $key )
        {
           $shop_config = array($key->key => $key->value);
           $data = array_merge($data, $shop_config);
        }
        //como hemos creado el grupo registro podemos utilizarlo
        $this->template->set_template('template1');
        //añadimos los archivos css que necesitemoa
        $this->template->add_css('assets/template/css/externo/bootstrap.css');
        $this->template->add_css('assets/template/css/externo/style.css');
        $this->template->add_css('assets/template/css/externo/dark.css');
        $this->template->add_css('assets/template/css/externo/font-icons.css');
        $this->template->add_css('assets/template/css/externo/animate.css');
        $this->template->add_css('assets/template/css/externo/magnific-popup.css');
        $this->template->add_css('assets/template/css/externo/responsive.css');
        $this->template->add_css('assets/template/css/externo/bs-rating.css');
        $this->template->add_css('assets/template/css/main.css');

        //añadimos los archivos js que necesitemoa
        $this->template->add_js('assets/template/js/vendor/jquery.js');
        $this->template->add_js('assets/template/js/vendor/plugins.js');
        //Footer Scripts
        $this->template->add_js('assets/template/js/vendor/functions.js');
        $this->template->add_js('assets/template/js/main.js');
        //Star Rating Plugin
        $this->template->add_js('assets/template/js/vendor/star-rating.js');
        
        //la sección header será el archivo views/registro/header_template
        $this->template->write_view('topbar', 'template/topbar', $data, TRUE);
        $this->template->write_view('header', 'template/header', $data, TRUE);
        
        //$this->template->write_view('slider', 'template/slider', $data, TRUE);
        //$this->template->write_view('product', 'template/product', $data, FALSE);
        $this->template->write_view('content', 'tienda/category', $data, TRUE ); 
        //$this->template->write_view('sidebar', 'template/sidebar', $data, true);
        $this->template->write_view('footer', 'template/footer', $data, TRUE);
        //desde aquí también podemos setear el título
        $this->template->write('title',$data['shop'], TRUE);
        $this->template->write('description',$data['shop_description'], TRUE);
        //obtenemos los usuarios
        
        
        //el contenido de nuestro formulario estará en views/registro/formulario_registro,
        //de esta forma también podemos pasar el array data a registro/formulario_registro
        
        //la sección footer será el archivo views/registro/footer_template
        //$this->template->write_view('footer', 'registro/footer_template');   
        //con el método render podemos renderizar y hacer que se visualice la template
        $this->template->render();
    
    }
    
    public function shop_item($id=false)
    {   
        $id=$this->input->get('id');        
        $data['shop_item']=$this->Template_model->shop_item($id);
        $this->load->view('tienda/shop-item',$data);
    }
    
    function get_logo_image()
    {

        $data=$this->Template_model->get_logo_image();

        if (!empty($data->value))
        {
            return site_url('img/view/'.$data->value);
        }
        return  base_url().'assets/template/images/logo-dark.png';
    }
    
    

}