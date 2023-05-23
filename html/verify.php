<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
if(isset($_POST)){
require __DIR__ .'/db.php';
require "vendor/autoload.php";
//variables
$smtp_user = "kuppomacapagal1818@gmail.com";
$smtp_password = '';
$port = 465;
$subject = "Verification";
$message = "Greetings! In order to verify your email address, please click this link localhost/PetBook/emailVerify.php";


//mailing process
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $smtp_user;
$mail->Password = $smtp_password;
$mail->Port = $port;
$mail->SMTPSecure = 'ssl';
$mail->isHTML(true);
$mail->setFrom($smtp_user, $name);
$mail->addAddress($email);
$mail->Subject = $subject;
$mail->Body = $message;
$mail->send();
}