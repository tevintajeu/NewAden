<?php
class SendMail {
    public function sendWelcomeEmail($email, $name, $subscribed) {//the subscribed parameter indicates whether  the user has subscribed to receiving notifications
        // Replace with your SendGrid API key
        $sendgrid_api_key = 'YOUR_SENDGRID_API_KEY';

        // Validate the email address
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Email details
            $emailDetails = array(
                "sendToEmail" => $email,
                "sendToName" => $name,
                "emailSubjectLine" => "Welcome to My Application, $name" //adds user's name to the subjectline and emailMessage
                //"emailMessage" => "Welcome $name to My Application! Thank you for joining us. <br><br>Click here to view your profile: <a href='https://example.com/profile/{$details["sendToEmail"]}'>https://example.com/profile/{$details["sendToEmail"]}</a>"
                 //above, allows the user to view their profile by clicking on the link
            
                );
            //Customize email message based on subscription status
            if($subscribed){
                $emailSubjectLine .="(Subscribed)";
                $emailMessage .= "Thank you for subscribing to our notifications and updates.";
            }else{
                $emailSubjectLine .="(Unsubscribed)";
                $emailMessage .= "You have opted out of receiving notifications and updates.";
            }

            // Configuration
            $config = array(
                "au_email_address" => "your_email@example.com",
                "site_name" => "Your Application"
            );

            // Call the sendEmail function
            $this->sendEmail($emailDetails, $config, $sendgrid_api_key);
        } else {
            echo "Invalid email address. Please provide a valid email.";
        }
    }

    public function sendEmail($details = array(), $conf, $api_key) {
        if (!empty($details["sendToEmail"]) && !empty($details["sendToName"]) && !empty($details["emailSubjectLine"]) && !empty($details["emailMessage"])) {
            $headers = array(
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json'
            );

            $data = [
                "personalizations" => array(
                    array(
                        "to" => array(
                            array(
                                "email" => $details["sendToEmail"],
                                "name" => $details["sendToName"]
                            )
                        )
                    )
                ),
                "from" => array(
                    "email" => $conf["au_email_address"],
                    "name" => $conf["site_name"]
                ),
                "subject" => $details["emailSubjectLine"],
                "content" => [
                    array(
                        "type" => "text/html", 
                        "value" => nl2br($details["emailMessage"])
                    )
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                echo "Failed to send the email. Please try again later.";
            } else {
                echo "Email sent successfully. Welcome email sent to {$details['sendToEmail']}.";
            }
        } else {
            echo "Error: Some details are missing.";
        }
    }



}

// // Example usage:
// $mailer = new SendMail();
// $email = "user@example.com"; // Replace with the user's email address
// $name = "User Name"; // Replace with the user's name
// $subscribed= true;
// $mailer->sendWelcomeEmail($email, $name,$subscribed);


/*if ($mailer->sendWelcomeEmail($email, $name, $subscribed)) {
    // Redirect to signin.php after sending the email
    header("Location: ./signin.php");
    exit;
} else {
    echo "Failed to send the email. Please try again later.";
}*/
?>
