<?php
    
    function subscription_check()
    {
        $CI =& get_instance();
        $subscription_cancelled=false;
        if($CI->Employee->is_logged_in())
        {
            $current_date  = date("Y-m-d H:i:s");
            $suspended=$CI->config->item('suspended');
            $expire_date   = date('Y-m-d H:i:s' , strtotime ("+1 day" , strtotime ($CI->config->item('expire_date'))));;
            $expire_date_franquicia   = date('Y-m-d H:i:s' , strtotime ("+1 day" , strtotime ($CI->config->item('expire_date_franquicia'))));;

            if($CI->config->item('es_franquicia')==true){
                if( $expire_date_franquicia >$current_date && $suspended==0){
                    $start_2      = strtotime($current_date);
                    $end_2      = strtotime($expire_date_franquicia); 
                    $difference_2 = $end_2 - $start_2;
                    $_SESSION['days_left'] = round($difference_2 / 86400);

                }else{ 
                    $subscription_cancelled=true;                
                }
            }           
            if(  $expire_date > $current_date &&  $suspended==0 )
            {
                $start      = strtotime($current_date);
                $end        = strtotime($expire_date); 
                $difference = $end - $start;
                if(isset( $_SESSION['days_left']) and $CI->config->item('es_franquicia')==true){
                    if( round($difference / 86400)<= $_SESSION['days_left'] )
                     $_SESSION['days_left'] = round($difference / 86400);
                }else{
                    $_SESSION['days_left'] = round($difference / 86400);
                }
               
             }
            else 
            {   
                $subscription_cancelled=true;
            }            

            if($subscription_cancelled ){
               // $_SESSION['extra1'] = md5('ingeniando'.$CI->db->database);
               $_SESSION['extra1']= $CI->config->item('license');               
                
                if($CI->router->method !== 'subscription_cancelled' and $CI->router->method !== 'logout' and $CI->router->class !== 'payu')
                {
                    redirect('login/subscription_cancelled');
                }
            }

            $location=$CI->Employee->get_logged_in_employee_current_location_id();
            $hour= date("H",strtotime(date(get_time_format())));
            $day= date("w",strtotime(date(get_time_format())));
            if($CI->config->item('activar_control_access_employee')==1 and $CI->router->method !== 'no_access' and !$CI->Hour_access->get_has_access($location,$hour,$day,$_SESSION['person_id'])){
                $CI->Hour_access->logout_access();
                redirect('login/no_access/');
            }            
        }
    }


