<?php
require_once(__DIR__ . '/../ClassAutoLoad.php');
require (__DIR__ . '/../vendor/autoload.php');
require (__DIR__ . '/../lang/en.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class methods{
    function sendemail_verify($email_address, $token, $message, $lang)
    {

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $lang["username"]; //load the lang file here                  
        $mail->Password = $lang["password"];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($lang["username"]);
        $mail->addAddress($email_address);

        $mail->isHTML(true);
        $mail->Subject = $lang["subject_sign_up_verify"];
        $mail->Body = $message;

        $mail->send();

    }

    function bind_to_template($replacements, $template)
    {
        return preg_replace_callback('/{{(.+?)}}/', function ($matches) use ($replacements) {
            return $replacements[$matches[1]];
        }, $template);
    }
}