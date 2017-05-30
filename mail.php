<?php
require 'mailsystem/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 5;                               // Enable verbose debug output

//$mail->isHTML(true);                                      // Set mailer to use SMTP
//$mail->Host = 'tls://smtp.gmail.com';  // Specify main and backup SMTP servers
//$mail->SMTPAuth = false;                               // Enable SMTP authentication
//$mail->Username = 'veenucan24@gmail.com';                 // SMTP username
//$mail->Password = 'willbeon1';                           // SMTP password
//$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 587;                                    // TCP port to connect to
$mail->isSendmail();
$mail->setFrom('localhost', 'Mailer');
$mail->addAddress('veenucan24@gmail.com', 'Joe User');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
