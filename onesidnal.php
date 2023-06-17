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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['isAdd']) && $data['isAdd'] === true) {
        $token = $data['token'];
        $title = $data['title'];
        $body = $data['body'];

        send_notification($token, $title, $body);
    } else {
        echo "API Notification";
    }
}

function send_notification($token, $title, $body)
{
    $apiKey = 'MTRkNDc0NjUtOGFjNy00NDczLWJlZGYtZjk3NmZmOGNmZWQ2';
    $appId = '777efcf3-1984-4cbd-bc48-e63b4db352c3';

    $fields = array(
        'app_id' => $appId,
        'include_player_ids' => array($token),
        'contents' => array('en' => $body),
        'headings' => array('en' => $title)
    );

    $headers = array(
        'Content-Type: application/json',
        'Authorization: Basic ' . $apiKey
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}

?>