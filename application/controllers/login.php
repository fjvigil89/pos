<?php
class Login extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('encrypt');
	}

	function login2()
    {
		$this->load->view("partial/cache_control");
         $this->load->view("login2/login");
	}
	function no_access()
	{
		$data['hour']=date(get_time_format(),time());
		$this->load->view("login/no_access",$data);
			
	}
	
	function index($demo=0)
	{
		$data = array();
        
        if( isset($_GET['user'],$_GET['store']) and !empty($_GET['store']) and !empty($_GET['user']) )
        {
            $data['user']  = $_GET['user'] ;
            $data['store'] = $_GET['store'] ;
        }
        elseif (isset($_COOKIE['user'],$_COOKIE['store']) and !empty($_COOKIE['user']) and !empty($_COOKIE['store']) ) 
        {
            $data['user']  = $_COOKIE['user'] ;
            $data['store'] = $_COOKIE['store'] ;
        }
        else
        {
            $data['user']  = "" ;
            $data['store'] = "" ;
        }		
		
        if( isset($_SESSION['captcha']) and !empty($_SESSION['captcha']) )
        {
            ($_SESSION['captcha'] > 4 ) ? $data['show_captcha'] = true  : $data['show_captcha'] = false ;
        }
        else 
        {
            $data['show_captcha'] = false ;
		}
		$data_company= $this->Appconfig->get_data_commpany_by_domain(base_url()) ;
		$data["data_company"]=$data_company;
		$data["es_franquicia"]=count($data_company)==1? true : false;
		
		if($demo==1 and !$data["es_franquicia"])
		{
			$data['user'] = 'demo2017' ;
			$data['store'] = 'demo2017';
			$data["pass_"]="demo2017";
		}
		elseif($demo==1)
		{
			$data['user'] = $data_company->user_demo ;
			$data['store'] = $data_company->store_demo ;;
			$data["pass_"]=$data_company->password_demo ;
		}
		$data["demo"]=$demo;
		//$data['username'] = is_on_demo_host() ? 'admin' : '';
		//$data['password'] = is_on_demo_host() ? 'pointofsale' : '';
		if ($this->agent->browser() == 'Internet Explorer' && $this->agent->version() < 9)
		{
			$data['ie_browser_warning'] = TRUE;
		}
		else
		{
			$data['ie_browser_warning'] = FALSE;
		}
		if(APPLICATION_VERSION==$this->config->item('version'))
		{
			$data['application_mismatch']=false;
		}
		else
		{
			$data['application_mismatch']=lang('login_application_mismatch');
		}
		
		if($this->Employee->is_logged_in())
		{
			redirect('home/index');
		}
		else
		{
            if( isset($_POST['g-recaptcha-response']) )
            {
                $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_captcha_check');
				
				if( $this->form_validation->run() == TRUE  )
				{
	                $this->form_validation->set_rules('username', 'lang:login_username', 'callback_login_check');
	            }
            }
			else
			{
				$this->form_validation->set_rules('username', 'lang:login_username', 'callback_login_check');
			}
					
    	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == FALSE)
			{
				//Only set the username when we have a non false value (not '' or FALSE)
				if ($this->input->post('username'))
				{					
					$data['username'] = $this->input->post('username');
				}
				include APPPATH.'config/database.php';
				
				//If we have a site configuration check to make sure the user has not cancelled
				if (isset($db['site']))
				{
					$site_db = $this->load->database('site', TRUE);
					
					if ($this->_is_subscription_cancelled($site_db))
					{
						if ($this->_is_subscription_cancelled_within_7_days($site_db))
						{
							$data['subscription_cancelled_within_14_days']  = TRUE;
							$this->load->view('login/login',$data);
						}
						else
						{
							$this->load->view('login/subscription_cancelled');
						}
					}
					else
					{
						$this->load->view('login/login', $data);
					}
				}
				else
				{
					$this->load->view('login/login',$data);
				}
			}
			else
			{
                //remember username and store in the client browser
                if(isset($_POST['remember_user']) and $_POST['remember_user'] == 1 )
                {
                    setcookie('user',$this->input->post('username') ,time() + 2592000 );
                    setcookie('store' , $this->input->post('store') , time() + 2592000 );
                }
                else
                {
                    setcookie('user'  , null , 0 );
                    setcookie('store' , null , 0 );
                }
                
				$batch_save_data=array(
					'config_remember_saved' =>  1,
				);

				$this->Appconfig->batch_save($batch_save_data);
				redirect('home');
			}
		}
	} 
	
	function _is_subscription_cancelled($site_db)
	{
		$phppos_client_name = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
		$site_db->select('subscr_status');	
		$site_db->from('subscriptions');	
		$site_db->where('username',$phppos_client_name);
		$site_db->where('subscr_status','cancelled');
		$query = $site_db->get();
		return ($query->num_rows() >= 1);
	}
	
	function _is_subscription_cancelled_within_7_days($site_db)
	{
		$phppos_client_name = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
		$thirty_days_ago = date('Y-m-d H:i:s', strtotime("now -7 days"));
		$site_db->select('subscr_status');	
		$site_db->from('subscriptions');	
		$site_db->where('username',$phppos_client_name);
		$site_db->where('subscr_status','cancelled');
		$site_db->where('cancel_date >', $thirty_days_ago);
		$query = $site_db->get();
		return ($query->num_rows() >= 1);
	}
	
	function login_check($username)
	{
        $password  = $this->input->post("password");
        //for production enviroment
        
        if( substr($this->input->post("store"), 0, 6) !==  DB_NAME_PREFIX )
        {
            $store = DB_NAME_PREFIX.$this->input->post("store");	
        }
        else 
        {
            $store = $this->input->post("store");
        }
        
		
		if(!$this->Employee->login($username,$password,$store))
		{
			$this->form_validation->set_message('login_check', lang('login_invalid_username_and_password'));
            (isset($_SESSION['captcha'])) ? $_SESSION['captcha'] += 1 : $_SESSION['captcha'] = 1;
			return false;
		}
		return true;		
	}
	
	function employee_location_check($username)
	{
        
        $db_name = $this->db->get_where('employees', array(
            'username' => $username,
            'password' => md5($this->input->post("password")), 
            'store'    => $this->input->post("store"), 
            'deleted'=>0
        ), 1)->row()->store;
        		
		$employee_id = $this->Employee->get_employee_id($username);
		
		if ($employee_id)
		{
			$employee_location_count = count($this->Employee->get_authenticated_location_ids($employee_id,$db_name));

			if ($employee_location_count < 1)
			{
				$this->form_validation->set_message('employee_location_check', lang('login_employee_is_not_assigned_to_any_locations'));
				return false;
			}
		}
		
		//Didn't find an employee, we can pass validation
		return true;
	}
	
	function switch_user()
	{
		if($this->input->post('password'))
		{
			if(!$this->Employee->login($this->input->post('username'),$this->input->post('password')))
			{
				echo json_encode(array('success'=>false,'message'=>lang('login_invalid_username_and_password')));
			}
			else
			{
				//Unset location in case the user doesn't have access to currently set location
				$this->session->unset_userdata('employee_current_location_id');
				echo json_encode(array('success'=>true));
			}
		}
		else
		{
			foreach($this->Employee->get_all()->result_array() as $row)
			{
				$employees[$row['username']] = $row['first_name'] .' '. $row['last_name'];
			}
			$data['employees']=$employees;

			$this->load->view('login/switch_user',$data);
			
		}
	}
	
	function reset_password()
	{
		$this->load->view('login/reset_password');
	}
	
	function do_reset_password_notify()
	{	
		if($this->input->post('username_or_email'))
		{
			$employee = $this->Employee->get_employee_by_username_or_email($this->input->post('username_or_email'));
			if ($employee)
			{
				$data = array();
				$data['employee'] = $employee;
			    $data['reset_key'] = base64url_encode($this->encrypt->encode($employee->person_id.'|'.(time() + (2 * 24 * 60 * 60))));
			
				$this->load->library('email');

				$config['charset']  = 'utf-8';
		        $config['newline']  = "\r\n";
		        $config['mailtype'] = 'html';
		        $config['protocol'] = 'smtp';
		        $config['smtp_host'] = 'server.hostingoptimo.com';
		        $config['smtp_port'] = '25';
		        $config['smtp_user'] = 'no-reply@ingeniandoweb.com';
		        $config['smtp_pass'] = 'o&=c53#m4gf~';
		        $config['validation'] = true;
		        $this->email->initialize($config);
		        $this->email->clear();
				$this->email->from('no-reply@ingeniandoweb.com', $this->config->item('company'));
				$this->email->to($employee->email); 

				$this->email->subject(lang('login_reset_password'));
				$this->email->message($this->load->view("login/reset_password_email",$data, true));	
				$this->email->send();
			
				$data['success']=lang('login_password_reset_has_been_sent');
				$this->load->view('login/reset_password',$data);
			}
			else 
			{
				$data['error']=lang('login_username_or_email_does_not_exist');
				$this->load->view('login/reset_password',$data);
			}
		}
		else
		{
			$data['error']= lang('common_field_cannot_be_empty');
			$this->load->view('login/reset_password',$data);
		}
	}
	
	function reset_password_enter_password($key=false)
	{
		if ($key)
		{
			$data = array();
		    list($employee_id, $expire) = explode('|', $this->encrypt->decode(base64url_decode($key)));			
			if ($employee_id && $expire && $expire > time())
			{
				$employee = $this->Employee->get_info($employee_id);
				$data['username'] = $employee->username;
				$data['key'] = $key;
				$this->load->view('login/reset_password_enter_password', $data);			
			}
		}
	}
	
	function do_reset_password($key=false)
	{
		if ($key)
		{
	    	list($employee_id, $expire) = explode('|', $this->encrypt->decode(base64url_decode($key)));
			
			if ($employee_id && $expire && $expire > time())
			{
				$password = $this->input->post('password');
				$confirm_password = $this->input->post('confirm_password');
				
				if (($password == $confirm_password) && strlen($password) >=8)
				{
					if ($this->Employee->update_employee_password($employee_id, md5($password)))
					{
						$this->load->view('login/do_reset_password');	
					}
				}
				else
				{
					$data = array();
					$employee = $this->Employee->get_info($employee_id);
					$data['username'] = $employee->username;
					$data['key'] = $key;
					$data['error_message'] = lang('login_passwords_must_match_and_be_at_least_8_characters');
					$this->load->view('login/reset_password_enter_password', $data);
				}
			}
		}
	}

	//Funcion de prueba para llamar la vista de subscription_cancelled
	function subscription_cancelled()
	{
        if( isset($_SESSION['extra1']) and !empty($_SESSION['extra1']) )
        {
            $this->config->load('payu');
            
            $data['gateway_url']     = $this->config->item('payu_gateway_url');
            $data['merchantId']      = $this->config->item('payu_merchantId');
            $data['accountId']       = $this->config->item('payu_accountId');
            $data['currency']        = $this->config->item('payu_currency');
            $data['test']            = $this->config->item('payu_test');
            $data['responseUrl']     = site_url('payu');
            $data['confirmationUrl'] = site_url('payu');
            $data['referenceCode']   = md5(time().rand(1, 15));
            $data['extra1']          = $_SESSION['extra1'];
            $data['api_key']         = $this->config->item('payu_api_key');
            $data['currency']        = $this->config->item('payu_currency');

            $data['one_month']       = $this->config->item('one_month');
            $data['two_months']      = $this->config->item('two_months');
            $data['three_months']    = $this->config->item('three_months');
            $data['six_months']      = $this->config->item('six_months');
            $data['one_year']        = $this->config->item('one_year');
            $data['renovation']      = $this->config->item('renovation');
            
            $this->load->view('login/subscription_cancelled',$data);
        }
        else 
        {
            redirect('login');
        }
        
	}
    
    function captcha_check($response)
    {
        $secret_key  = "6LdGqCcTAAAAAM8vq1TD5p4q-yp4WlmtdJ7afurq";
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        $captcha     = json_decode(
            file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$response.'&remoteip='.$remote_addr),TRUE
        );
        
        if ($captcha['success'] == 1)
		{
            return TRUE;
		}
		else
		{
            $this->form_validation->set_message('captcha_check', 'Error: captcha invalido');
			return FALSE;
		}
    }
}