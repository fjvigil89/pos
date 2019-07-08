<?php
function generate_expiration_lerta_subcategory()
{
    $CI =& get_instance();
    $custom2 = $CI->config->item('custom_subcategory2_name');
    $now = date('Y-m-d');
    $day =$CI->config->item("subcategory_alerts") ? $CI->config->item("subcategory_alerts"): 15 ;
    $date_delete =  $CI->config->item("date_alert_subcategory_send");
    if($date_delete != $now and $CI->Location->get_info_for_key('receive_stock_alert')) 
    {
      try {
        $items =$CI->Item->expired_products_subcategory();
      
        $html = '<html>
            <head>
            <style>
            table, th, td {
              border: 1px solid black;
              border-collapse: collapse;
            }
            </style>
            </head>
            <body>
            <h2>Productos que vencen a  menos de '.$day.'</h2>

            <table style="width:100%">
            <tr>
              <th>'.lang("items_name").'</th>
              <th>'.lang("items_expiration_date").'</th>
              <th>'.$custom2.'</th>
              <th>'.lang("locations_shop_name").'</th>
              <th>'.lang("items_quantity").'</th>
            </tr>
            ';

        foreach ($items as  $item) 
        {
          // cuerpo
          $html .='<tr>'.
                    '<td>'.$item['name'].'</td>'.
                    '<td>'. date(get_date_format(), strtotime($item['expiration_date'])).'</td>'.
                    '<td>'.$item['custom2'].'</td>'.
                    '<td>'.$item['name_location'].'</td>'.
                    '<td>'.to_quantity($item['quantity']).'</td>'.
                  '</tr>'; 
        }
        $html .= '</table></body>
        </html>';

        if (count($items)> 0 ) 
        {          
          $CI->load->library('Email_send');
          $para = $CI->Location->get_info_for_key('stock_alert_email') ? $CI->Location->get_info_for_key('stock_alert_email') : $CI->Location->get_info_for_key('email');
          $subject="Alerta de vencimiento";
          $name="";
          $company = $CI->config->item('company');
          $from=$CI->Location->get_info_for_key('email') ? $CI->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
          $CI->email_send->send_($para, $subject, $name,$html,$from,$company) ;                  
        }
        $CI->Appconfig->save("date_alert_subcategory_send",  $now); 
       
      } catch (Exception $e) {
        $CI->Appconfig->save("date_alert_subcategory_send",  $now);
      }
    }
}
?>