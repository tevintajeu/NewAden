<?php
session_start();
date_default_timezone_set("Africa/Nairobi");
include('dbconn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require '../path/to/PHPMailer/src/Exception.php';
// require '../path/to/PHPMailer/src/PHPMailer.php';
// require '../path/to/PHPMailer/src/SMTP.php';
require '../vendor/autoload.php';




$email_address = $_POST["email_address"];
$user_email =$_POST["email_address"];
$token = md5(time());
$token_expire = date("Y-m-d H:i:s", strtotime("+ 2hours"));


function bind_to_template($replacements, $template){
    return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements){
        return $replacements[$matches[1]];
    }, $template);
}

function sendemail_verify($email_address,$token,$user_email){
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'tevot191030@gmail.com';                     
    $mail->Password   = 'fnluafgqycrspxec';                               
    $mail->SMTPSecure = 'tls';            
    $mail->Port       = 587 ;  

    $mail->setFrom('info@icd.rochella.org');
    $mail->addAddress($email_address); 

    $mail->isHTML(true);
    $mail->Subject = "ics 2.2 Email account verifiaction ";

    $email_template = "
    <h2> hi $user_email, you have registered with ics 2.2</h2>
    <p> We see you're from $location. Thank you for joining our community!<br>
    <p> We value diversity! </p>
    <h5>Verify your email address to login with the below given link </h5>
    <br>
    <a href='http://localhost/aden-main/processes/verify_email.php?token=$token''>click here</a>";

    $replacements = [
        'user_email' => $user_email,
        'location' => $location,
    ];

    $mail->Body = bind_to_template($email_template, $replacements);
    $mail-> send();

   


};




$check_email_query = "SELECT email_address FROM users WHERE email_address ='$email_address' LIMIT 1";
$check_email_query_RUN = mysqli_query($conn, $check_email_query);

if (mysqli_num_rows($check_email_query_RUN) > 0) {
   // $_SESSION['status'] = "email already exists";
   echo "email already exists";
   // header("location:register.php");
} else {
    $query = "INSERT INTO users (email_address,token,token_expire) value ('$email_address','$token','$token_expire')";
    $query_run = mysqli_query($conn, $query);


    if ($query_run) {
        sendemail_verify("$email_address","$token","$user_email"); //Passing user email to the function
        $_SESSION['status'] = "registration successfull. now verify your email";
        header("location:../signup.php");

    } else {
        
        $_SESSION['status'] = "Registration Failed";
              header("location:register.php");

    }
}


?>