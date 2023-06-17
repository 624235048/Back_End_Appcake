<?php
include 'connected.php';
	header("Access-Control-Allow-Origin: *");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    
    exit;
}

if (!$link->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $link->error);
    exit();
	}

if (isset($_GET)) {
	if ($_GET['isAdd'] == 'true') {
			
		$token = $_GET['token'];
		$title = $_GET['title'];
		$body = $_GET['body'];
		

		send_notification ($token, $title, $body);	
	
	} else echo "api Notification";  
}

function send_notification($token, $title, $body)
{	
	define( 'API_ACCESS_KEY', 'AAAA1e3gV_0:APA91bG5tQl8m1ucQOVduUpxNjK9SI1am1zPDuyWMFMPXILKftJN_UfL8VWuDQdtBaVeOU1bqlmbJxoSNk9PYlbIVPJA_XnaSVS22GUe8sHH5EYxuHbsjxKcL9qBXFgq1FM6xdVW6wKy');
 
     	$msg = array
          (
		'body' 	=> $body,
		'title'	=> $title,
		'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
		'sound' => 'default',
		'content_available' => 'true',
		'priority' => 'high', 	
          );

      	$data = array
          (
		'body' 	=> $body,
		'title'	=> $title,
		'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
		'sound' => 'default',
		'content_available' => 'true',
		'priority' => 'high', 	
          );

		$fields = array
			(
				'to' => $token,
				'notification'	=> $msg,
				'data' => $data,
			);
	
	
		$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		echo $result;
		curl_close( $ch );
}
?>