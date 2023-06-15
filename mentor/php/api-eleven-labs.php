<?php
$API_KEY = "76478d2f1d3d9d2c67646d18e4258bc0";

$url = 'https://api.elevenlabs.io/v1/voices';

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\nxi-api-key: $API_KEY",
        'method'  => 'GET'
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

header('Content-Type: application/json');
echo $result;
?>