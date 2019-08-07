<?php
require_once ("secure_area.php");
require __DIR__ . '/../../vendor/autoload.php';
define ('STDIN', fopen("php://stdin", "r"));

class schedules extends Secure_area 
{
    private $client_google;
    public $client;

    function __construct()
	{
        parent::__construct();        
        $this->load->model(array('Schedule','Employee', 'Item','Schedule_Items','Appfile'));        
        $this->load->library(array('sale_lib','viewer_lib','session'));

        //$client_google = $this->getClient();

        $auth = 'assets/client_secret.json';
        $this->client = new Google_Client();
        $this->client->setAuthConfig($auth);
        $this->client->setApplicationName('Google Calendar API PHP FacilPos');
        $this->client->setClientId("44046375111-gikoscmsst1j1r1n9rq5ieg9s64h5irh.apps.googleusercontent.com");
        $this->client->setClientSecret("8RRSSKByOIFx7ykPTXRPfytq");
        $this->client->setRedirectUri(base_url().'index.php/schedules/oauth');
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->addScope('profile');

        
        

    }
    public function loginUrl() {
        return $this->client->createAuthUrl();       
    }
 
    public function getAuthenticate() {
        return $this->client->authenticate();
    }
 
    public function getAccessToken() {
        return $this->client->getAccessToken();
    }
 
    public function setAccessToken($token) {
        return $this->client->setAccessToken($token);
    }
 
    public function revokeToken() {
        return $this->client->revokeToken();
    }
 
    public function client() {
        return $this->client;
    }
 
    public function getUser() {
        $google_ouath = new Google_Service_Oauth2($this->client);
        return (object)$google_ouath->userinfo->get();
    }
 
    public function isAccessTokenExpired() {
        return $this->client->isAccessTokenExpired();
    }

      // check login session
    public function isLogin() {
        $token = $this->session->userdata('google_calendar_access_token');
        if ($token) {
            $this->setAccessToken($token);
        }
        if ($this->isAccessTokenExpired()) {
            return false;
        }
        return $token;
    }
    
    // get User Info
    public function getUserInfo() {
        return $this->getUser();
    }

   // oauth method
    public function oauth() {
        $code = $this->input->get('code', true);
        $this->oauthLogin($code);
        $this->GetGoogleClient(); 
        //$this->SetGoogleClient();
        redirect(site_url().'/schedules', 'refresh');
    }
     // oauthLogin
    public function oauthLogin($code) {
        $login = $this->client->authenticate($code);
        if ($login) {
            $token = $this->getAccessToken();
            $this->session->set_userdata('google_calendar_access_token', $token);
            $this->session->set_userdata('is_authenticate_user', TRUE);
            return true;
        }
    }

    function index(){    
        if (!$this->isLogin()) {
            # code...
            $this->GetGoogleClient(); 

        }
        $data['vistas'] = 'calendar';            
        $data['loginUrl'] = $this->loginUrl();   
        return $this->load->view('calendar/index', $data);
    
        
    }    

    function listar(){           
        $data['vistas'] = 'schedule';        
        $data['schedule'] = $this->getSchedule();
       return $this->load->view('calendar/index', $data);
    }

    function addSchedules(){
        $data['vistas'] = 'save'; 
        foreach ($this->Item->get_all()->result() as $key => $item) {            
            $item_id = $item->item_id;
            $item = $item->category;
            $data['items'][$key] = $item;
            $data['items_id'][$key] = $item_id;
        }
        
       return $this->load->view('calendar/index', $data);
    }

    /***
     * cargar el schedule que este en la lista para su edicion
     */
    function editSchedule($id){        
        $data['vistas'] = 'save'; 
        $data['schedule'] = $this->Schedule->get_scheduleID($id)->result();
        foreach ($this->Item->get_all()->result() as $key => $item) {            
            $item_id = $item->item_id;
            $item = $item->category;
            $data['items'][$key] = $item;
            $data['items_id'][$key] = $item_id;
        }
        
       return $this->load->view('calendar/index', $data);
    }

    /***
     * enviar email con los datos del schedule creado
     */
    function email_schedule($data)
    {
        $this->load->library('Email_send');
        $para=$this->Employee->get_info($this->Employee->get_logged_in_employee_current_location_id());
        $subject="Calendario";        
        $company="FacilPos";
        $name=$company.' | '.$subject;
        $from='no-reply@FacilPos.com';        
        $data['receipt_title']= "Information about schedule";
        $data['total_price'] = array_sum($data['total_price']);
        $email=$this->email_send->send_($para->email, $subject, $name,
        $this->load->view('calendar/schedule_email', $data),$from,$company) ;
        
    }
    /**
     * Metodo para cargar todos los schedules y llenar por ajax el calendario
     * A este metodo le hace la petiocion ajax desde el script calendar.min.js
     * assets/global/plugins/fullcalendar/calendar.min.js
     */
    function getApiSchedule()
    {
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        $data['schedule'] = $this->Schedule->get_schedule($location_id)->result();               

        //header('Content-Type: application/json');        
        //json_encode($data, true);
        
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));

        
    }

    // add google calendar event
    function SetGoogleClient() {        
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        $post = $this->Schedule->get_schedule_FacilPos($location_id)->result(); 
        $post = $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($post));
        
        /*

        $json = array();
        $calendarId = 'primary';        
        // start date time validation
        if(empty(trim($post['startDate'])) && empty($post['startTime'])){
            $json['error']['startdate'] = 'Please enter start date time';
        }
        if(empty(trim($post['endDate'])) && empty($post['endTime'])){
            $json['error']['enddate'] = 'Please enter end date time';
        }
        if(empty(trim($post['description']))){
            $json['error']['description'] = 'Please enter description';
        }

        if(empty($json['error'])){
            $event = array(
                'summary'     => $post['summary'],
                'start'       => $post['startDate'].'T'.$post['startTime'].':00+03:00',
                'end'         => $post['endDate'].'T'.$post['endTime'].':00+03:00',
                'description' => $post['description'],

            );
            $data = $this->actionEvent($calendarId, $event);
            if ($data->status == 'confirmed') {
                $json['message'] = 1;
            } else {
                $json['message'] = 0;
            }
        }
        $this->output->set_header('Content-Type: application/json');
        echo json_encode($json);
    
        */
    }

    function GetGoogleClient(){
        // Get the API client and construct the service object.
        
        
        $service = new Google_Service_Calendar($this->client);

        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
        'maxResults'    => 100000,
        'orderBy'       => 'startTime',
        'singleEvents'  => true,
        'timeMin'       => date("c", strtotime(date('Y-m-d ').' -1 year')),
        'timeMax'       => date("c", strtotime(date('Y-m-d ').' +1 year')),
        );

        $results = $service->events->listEvents($calendarId, $optParams);        
        //$events = $results->getItems();
        //var_dump($events);

        
        $creator = new Google_Service_Calendar_EventCreator();

        foreach ($results->getItems() as $item) {
 
            if(!empty($item->getStart()->date) && !empty($item->getEnd()->date)) {
                $startDate = date('Y-m-d H:i', strtotime($item->getStart()->date));
                $endDate = date('Y-m-d H:i', strtotime($item->getEnd()->date));
            } else {
                $startDate = date('Y-m-d H:i', strtotime($item->getEnd()->dateTime));
                $endDate = date('Y-m-d H:i', strtotime($item->getEnd()->dateTime));
            }
            
            $created = date('Y-m-d H:i', strtotime($item->getCreated()));
  
            $location_id=$this->Employee->get_logged_in_employee_current_location_id();
            
            $data= array(
                    'id_google'     => $item->getId(),
                    'title'         => trim($item->getSummary()),
                    'detail'        => trim($item->getDescription()),                    
                    'start'         => $startDate,
                    'end'           => $endDate,
                    'status'        => '1',
                    'employee_id'   => $location_id,
                    'create_at'     => $created,
                    'parent'        => 'Google Calendar'
                  
            );            
            if (!$this->Schedule->exist_Google_FacilPos($item->getId()))
            {
                $this->Schedule->save($data);    
            }

            
        }
        



    }

    /**
     * Metodo para cargar todos los schedules y llenar por ajax el calendario
     * A este metodo le hace la petiocion ajax desde el script calendar.min.js
     * assets/global/plugins/fullcalendar/calendar.min.js
     */
    function getSchedule()
    {
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        $data = $this->Schedule->get_schedule($location_id)->result_array();
        //var_dump($data);
        return $data;
        
         
    }
    
   

    /**
     * Este es para uso de ajax o com API
     * 
     */
    function setApiSchedule()
    {
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        
        $status = '0';
        if($this->input->post('status')=='on')
        {
            $status = '1';
        }

        $data = array(
            'title'         => $this->input->post('title'),
            'start'         => $this->input->post('start'),
            'end'           => $this->input->post('end'),
            'status'        => $status,
            'color'         => $this->input->post('color'),
            'parent'        => 'FacilPos',
            'employee_id'   => $location_id,
        );
        
        if ($this->input->post('update')== "false") {
            
            $save_data = $this->Schedule->save($data);
            
        }     
       
    }

    /**
     * Agregar un nuevo Schedule
     */
    function setSchedule()
    {
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        
        $status = '0';
        if($_POST['status']=='on')
        {
            $status = '1';
        }
        $data = array(
            'title' => $_POST['title'],
            'detail'=>$_POST['detail'],
            'start' => $_POST['start_date'],
            'end' => $_POST['end_date'],
            'status'=> $status,
            'color'=> $_POST['color'],
            'parent'        => 'FacilPos',
            'employee_id'=> $location_id,        
        );

        $save_id = $this->Schedule->save($data);

        //agreagndo en la relacion        
        if (isset($_POST['products'])) {
            foreach ($_POST['products'] as $key => $value) {
                $data_item = array(
                    'schedule_id'=>$save_id,
                    'items_id' => $value,
                );

                $items =$this->Item->get_info($value);
                $data['items'][$key] = $items;                
                $data['total_price'][$key] = (float)$items->unit_price;
                $this->Schedule_Items->save($data_item);
            }
        }
        
        
        $this->email_schedule($data);
        redirect('/schedules', 'refresh');
        
       
    }

    /***
     * Mostrar imagen del producto
     */
    function getImagen($item_id)
    {
        $file = $this->Appfile->get($item_id);		
        header("Content-type: ".get_mime_by_extension($file->file_name));			                
        echo $file->file_data;
    }

    /**
     * Actualizar un schedule
     */
    function updateSchedule()
    {
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        $id = $_POST['id'];
        $status = '0';
        if($_POST['status']=='on')
        {
            $status = '1';
        }
        $data = array(
            'title' => $_POST['title'],
            'detail'=>$_POST['detail'],
            'start' => $_POST['start_date'],
            'end' => $_POST['end_date'],
            'status'=> $status,
            'color'=> $_POST['color'],
            'employee_id'=> $location_id          
        );

        $save_id = $this->Schedule->save($data, $id);


        
        if(isset($_POST['products']))
        {
            $producto_update = $_POST['products'];            
            $producto_existente = $this->Schedule_Items->get_schedule($id)->result();
            foreach ($producto_existente as $key => $item) {            
                $this->Schedule_Items->delete($id);                
            }

            foreach ($producto_update as $key => $value) {           
                $data = array(
                    'schedule_id'=>$id,
                    'items_id' => $value,
                );
                $items =$this->Item->get_info($value);
                $data['items'][$key] = $items;                
                $data['total_price'][$key] = (float)$items->unit_price;
            };
            $this->Schedule_Items->save($data);
            
        }
               
        $this->email_schedule($data);
        redirect('/schedules', 'refresh');
        
       
    }

    function setEnable()
    {
        
        if ($this->input->post('update')== "true") {
            $item = $this->Schedule->get_info($this->input->post('id'));
            $data = array();
            foreach ($item as $key => $value) {
                # code...
                $data[$key]= $value;
            }
            
            if($data['status'] == '1')
            {
                $data['status']= '0';
            }
            else
            {
                $data['status']= '1';
            }
            //var_dump($data);
            $save_data = $this->Schedule->save($data, $this->input->post('id'));
            
        }
    }

    function setDelete($id){
        
        
        $item = $this->Schedule->delete($id);        
        redirect('schedules/listar');
        
    }
  

    /***
     * funcion para facturar los schedule
     */
    function facturar($id)
    {
        $data['schedule'] = $this->Schedule->get_scheduleID($id)->result();
        $producto_facturar = $this->Schedule_Items->get_schedule($id)->result();
        foreach ($producto_facturar as $key => $value) {                       
            $items =$this->Item->get_info($value->id);
            $price = $this->viewer_lib->get_price_item_expe($items);
            $this->sale_lib->add_item($items->item_id,1,0,$price);    
        };

        redirect('/sales', 'refresh');
        
    }
    
    


}