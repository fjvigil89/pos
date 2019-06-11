<?php
require_once ("secure_area.php");
require __DIR__ . '/../../vendor/autoload.php';
define ('STDIN', fopen("php://stdin", "r"));

class schedules extends Secure_area 
{
    private $client_google;
    function __construct()
	{
        parent::__construct();        
        $this->load->model(array('Schedule','Employee', 'Item','Schedule_Items'));

        //$client_google = $this->getClient();
    }
    function index(){           
        $data['vistas'] = 'calendar';                
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
    /**
     * Metodo para cargar todos los schedules y llenar por ajax el calendario
     * A este metodo le hace la petiocion ajax desde el script calendar.min.js
     * assets/global/plugins/fullcalendar/calendar.min.js
     */
    function getApiSchedule()
    {
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        $data['schedule'] = $this->Schedule->get_schedule($location_id)->result();
        
        // Get the API client and construct the service object.
        
        /*
        $service = new Google_Service_Calendar($this->client_google);

        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
        'maxResults' => 10,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (empty($events)) {
            print "No upcoming events found.\n";
        } else {
            print "Upcoming events:\n";
            foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                printf("%s (%s)\n", $event->getSummary(), $start);
            }
        }*/

        //header('Content-Type: application/json');        
        //json_encode($data, true);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
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
            'title' => $this->input->post('title'),
            'start' => $this->input->post('start'),
            'end' => $this->input->post('end'),
            'status'=> $status,
            'color'=> $this->input->post('color'),
            'employee_id'=> $location_id
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
            'employee_id'=> $location_id          
        );

        $save_id = $this->Schedule->save($data);

        //agreagndo en la relacion        
        foreach ($_POST['products'] as $key => $value) {
            $data = array(
                'schedule_id'=>$save_id,
                'items_id' => $value,
            );

            $this->Schedule_Items->save($data);
            
        }
        //var_dump(count($_POST['products']));    
        //$save_data = $this->Schedule->save($data);
        
        
        redirect('/schedules', 'refresh');
        
       
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
                //var_dump($value);
            };
            $this->Schedule_Items->save($data);
            
        }
                
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
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient()
    {
        $auth = 'assets/client_secret.json';

        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig($auth);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(base_url().'citas');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = "https://oauth2.googleapis.com/token";
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);            
        }

        // If there is no previous token or it's expired.
        
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            echo $client->getRefreshToken();
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }


}