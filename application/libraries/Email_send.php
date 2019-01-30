<?php
 class Email_send {
    function __construct()
	{
        $this->CI =& get_instance();
    }
     function send_($receiver_email, $subject, $username, $message, $sender_email="no-reply@facilPos.com", $company=false, $sender_email_base=false,$user_password="") {
        error_reporting(0);
        require_once('correo/class.phpmailer.php');
        if( $sender_email_base==false){
            $sender_email_base="nuevainformatica.desarrollo@gmail.com";//"facilpos1@gmail.com";
            $user_password="nuevainformatica123456..";//"Colombia77$$";
        }
        $mail = new PHPMailer();
        //indico a la clase que use SMTP
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        //indico el servidor de Gmail para SMTP
        $mail->Host = "smtp.gmail.com";
        //indico el puerto que usa Gmail
        $mail->SMTPDebug = 0;
        $mail->Port = 465;  //587;
        //indico un usuario / clave de un usuario de gmail
        $mail->Username = $sender_email_base;
        $mail->Password = $user_password;
        $mail->SetFrom($sender_email, $company!=false?$company:'POS');
        $mail->Subject = utf8_decode($subject);
        $mail->MsgHTML(utf8_decode($message));
        //indico destinatario         
        $address = $receiver_email;
         
        $mail->AddAddress($address, $username);
        if(!$mail->Send()) {
            $data['message_display'] = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        } else {
            $data['message_display'] = 'Email Successfully Send !'; 
        } 
        return $data;
    }
}
    ?>