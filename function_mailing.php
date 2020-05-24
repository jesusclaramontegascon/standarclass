<?php
require_once('function_db.php');

// Mandar un correo sencillo
function send_mail($desde, $nombre, $asunto, $cuerpo )
{
    require_once '../util/swift/lib/swift_required.php';
    
    $entidad = current(get_data_entidad($_SESSION['usuario']['cod_ent'], 0));
    
    $mail_smtp_server = $entidad['mail_smtp_server'];
    $mail_smtp_port   = $entidad['mail_smtp_port'];
    $mail_user        = $entidad['mail_user'];
    $mail_password    = $entidad['mail_password'];
    $mail_email       = $entidad['mail_email'];
    $mail_name_show   = $entidad['mail_name_show'];
    
    //Create the Transport
    $transport = Swift_SmtpTransport::newInstance($mail_smtp_server, $mail_smtp_port)
         ->setUsername($mail_user)
         ->setPassword($mail_password);
    
    //Create the Mailer using your created Transport
    $mailer = Swift_Mailer::newInstance($transport);
    
    //Create a message
    $message = Swift_Message::newInstance($asunto)
        ->setFrom(array($mail_email => $mail_name_show))
        ->setTo(array('artem@antonov.es', $mail_email  => $mail_name_show))
        ->setReplyTo(array($desde => $nombre))
        ->setBody($cuerpo)
    ;
      
    //Send the message
    $num_sent = $mailer->batchSend($message, $failures);
    
    if(!$num_sent)
    {
        foreach($failures as $failure)
        {
            $txt .= sprintf('Fallo al mandar [%s] para dirección [%s] \r\n', date('d/m/Y h:i:s'),  $failure);
        }
        file_put_contents('mailing.txt', $txt, FILE_TEXT | FILE_APPEND);
    } 
    
    
    return $num_sent;
}

// Mandar un correo sencillo
function send_mail_to($destinatario, $nombre, $asunto, $cuerpo )
{
    require_once '../util/swift/lib/swift_required.php';
    
    $entidad = current(get_data_entidad($_SESSION['usuario']['cod_ent'], 0));
    
    $mail_smtp_server = $entidad['mail_smtp_server'];
    $mail_smtp_port   = $entidad['mail_smtp_port'];
    $mail_user        = $entidad['mail_user'];
    $mail_password    = $entidad['mail_password'];
    $mail_email       = $entidad['mail_email'];
    $mail_name_show   = $entidad['mail_name_show'];
    
    //Create the Transport
    $transport = Swift_SmtpTransport::newInstance($mail_smtp_server, $mail_smtp_port)
         ->setUsername($mail_user)
         ->setPassword($mail_password);
    
    //Create the Mailer using your created Transport
    $mailer = Swift_Mailer::newInstance($transport);
    
    //Create a message
    $message = Swift_Message::newInstance($asunto)
        ->setFrom(array($mail_email => $mail_name_show))
        ->setTo(array( $destinatario => $nombre))
        ->setReplyTo(array($mail_email => $mail_name_show))
        ->setBody($cuerpo)
    ;

    //Send the message
    $num_sent = $mailer->batchSend($message, $failures);

    /*
    if(!$num_sent)
    {
        foreach($failures as $failure)
        {
            $txt .= sprintf('Fallo al mandar [%s] para dirección [%s] \r\n', date('d/m/Y h:i:s'),  $failure);
        }
        file_put_contents('../log/mailing.txt', $txt, FILE_TEXT | FILE_APPEND);
    } 
    */
    
    return $num_sent;
}

?>