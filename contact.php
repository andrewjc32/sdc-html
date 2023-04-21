<?php
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require 'vendor/autoload.php';
 
$mail = new PHPMailer(true);
 
try {
    if (!empty($_POST)) {
        if (!isset($_POST['g-recaptcha-response'])) {
            throw new \Exception('ReCaptcha is not set.');
        }

        $recaptchaSecret = $_SERVER['CAPTCHASECRET'];
        
        $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret, new \ReCaptcha\RequestMethod\CurlPost());

        $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    
        if (!$response->isSuccess()) {
            throw new \Exception('ReCaptcha was not validated.');
        }
    
        $mail->SMTPDebug = 2;                                      
        $mail->isSMTP();                                           
        $mail->Host       = $_SERVER["HOST"];                   
        $mail->SMTPAuth   = true;                            
        $mail->Username   = $_SERVER["USER"];                
        $mail->Password   = $_SERVER["PASSWORD"];                       
        $mail->SMTPSecure = 'tls';                             
        $mail->Port       = 587; 
     
        $mail->setFrom($_SERVER["USER"], 'PQRS serdraga.com.co');          
        $mail->addAddress('andrew_w21@hotmail.com');
          
        $mail->isHTML(true);                                 
        $mail->Subject = 'Subject';
    
        $emailTextHtml = "<style type='text/css'>body {font-family: Roboto, sans-serif; font-size: 16px; }</style>";
        $emailTextHtml .= "<body>";
        $emailTextHtml .= "<h1>Tienes un nuevo mensaje desde el buzon de PQRS en serdraga.com.co</h1><br><br>";
        $emailTextHtml .= "<p>";
        $emailTextHtml .= "<strong>Remitente: </strong>$_POST[firstName] $_POST[lastName]<br>";
        $emailTextHtml .= "<strong>Correo: </strong>$_POST[email]<br>";
        $emailTextHtml .= "<strong>Celular : </strong>$_POST[number]<br>";
        $emailTextHtml .= "<strong>Tipo de solicitud: </strong>$_POST[tipo]<br>";
        $emailTextHtml .= "<strong>Asunto: <strong> $_POST[asunto]<br>";
        $emailTextHtml .= "<strong>Comentarios: <strong> $_POST[comentarios]<br>";
        $emailTextHtml .= "</p>";
        $emailTextHtml .= "<p>Tenga un buen dia,<br>Saludos,<br>Andrew</p>";
        $emailTextHtml .= "</body>";
    
        $mail->Body    = $emailTextHtml;
    
        $mail->AltBody = 'Body in plain text for non-HTML mail clients';
        $mail->send();
        header('Location: gracias.html');
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
 
?>