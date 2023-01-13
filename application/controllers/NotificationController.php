<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NotificationController extends CI_Controller
{
	public function send()
	{
		$device_token = $this->request->getVar("device_token");

		return $this->sendNotification($device_token, array(
			"title" => "Sample Message",
			"body" => "This is Test message body"
		));
	}

	public function sendNotification($device_token, $message)
    {
        $SERVER_API_KEY = 'AAAA_71zdpg:APA91bEfXbJ55wI9H3rrsOxwENFzbrv-JFiHzfucvlhtK3llcecTVk2s_cAGvSQErHge0K_xld8FmYrTDE_5bVhofcuxmqq8w8qMWU0Cy1Xnr-T3DrGH-OkNQn7YzgJC7pAmEbGBS9y8';
  
        // payload data, it will vary according to requirement
        $data = [
            "to" => $device_token, // for single device id
            "data" => $message
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
      
        curl_close($ch);
      
        return $response;
    }
}