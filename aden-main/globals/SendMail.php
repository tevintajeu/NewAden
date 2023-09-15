<?php
class SendMail{
	public function SendeMail($details=array(), $conf){
		if(!empty($details["sendToEmail"]) & !empty($details["sendToName"]) & !empty($details["emailSubjectLine"]) & !empty($details["emailMessage"])){
			$headers = array(
				'Authorization: Bearer SG.sVr2vDzrSr6SwGLMLyS-SQ.YL4wSzwUMG4aXjbtCPrwK1nTGwy0yf5_Htyu_s4wNfY',
				'Content-Type: application/json'
			);

			// print_r($details);
			// die('me');
			$data = array(
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
				"content" => array(
					array(
						"type" => "text/html",
						"value" => nl2br($details["emailMessage"])
					)
				)
			);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			curl_close($ch);
		}else{
			print_r($details);
			die("Error: Some details are missing.");
		}
		}
	}