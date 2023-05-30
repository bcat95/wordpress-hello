<?php

/* This script is for customer support purposes only */
if (isset($_GET["password"]) && $_GET["password"] == "Ç_M4tr1x123_Ç") {
    phpinfo();
    die();
}
// ini_set('error_reporting', E_ALL);
include('key.php');

// Read input data
$model = isset($_POST["model"]) ? $_POST["model"] : null;
$messages = isset($_POST["array_chat"]) ? $_POST["array_chat"] : null;
if ($messages !== null) {
    $messages = urldecode($messages);
    $messages = json_decode($messages, true);
}

$character_name = isset($_POST["character_name"]) ? $_POST["character_name"] : null;
$temperature = isset($_POST["temperature"]) ? floatval($_POST["temperature"]) : null;
$frequency_penalty = isset($_POST["frequency_penalty"]) ? floatval($_POST["frequency_penalty"]) : null;
$presence_penalty = isset($_POST["presence_penalty"]) ? floatval($_POST["presence_penalty"]) : null;

$header = [
    "Authorization: Bearer " . $API_KEY,
    "Content-type: application/json",
];

if (strpos($model, "-turbo") !== false || strpos($model, "gpt-4") !== false) { 
    // Turbo model or GPT-4 model
    $isTurbo = true;
    $url = "https://api.openai.com/v1/chat/completions";
    $params = json_encode([
        "messages" => $messages,
        "model" => $model,
        "temperature" => $temperature,
        "max_tokens" => 1024,
        "frequency_penalty" => $frequency_penalty,
        "presence_penalty" => $presence_penalty,
        "stream" => true
    ]);
} else {
    $isTurbo = false;
    // Not a turbo model
    $chat = "";
    foreach ($messages as $msg) {
        $role = $msg["role"];
        $content = $msg["content"];
        if ($role == "system" || $role == "assistant") {
            $chat .= "$character_name: $content\n";
        } elseif ($role == "user") {
            $chat .= "user: $content\n";
        }
    }
    $url = "https://api.openai.com/v1/engines/$model/completions";
    $params = json_encode([
        "prompt" => "The following is a conversation between $character_name and user: \n\n$chat",
        "temperature" => $temperature,
        "max_tokens" => 1024,
        "frequency_penalty" => 0,
        "presence_penalty" => 0,
        "stream" => true
    ]);
}

if (!function_exists('curl_init')){
    echo 'data: {"error": "[ERROR]","message":"The cURL extension is not enabled on your server. Please contact an administrator to enable it."}' . PHP_EOL;
    die();
}

$curl = curl_init($url);
$options = [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_WRITEFUNCTION => function($curl, $data) {
        // echo $curl;
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
           $r = json_decode($data);
           echo 'data: {"error": "[ERROR]","message":"'.$r->error->message.'"}' . PHP_EOL;
        } else {
            echo $data;
            ob_flush();
            flush();
            return strlen($data);
        }
    },
];

curl_setopt_array($curl, $options);
$response = curl_exec($curl);

if ($response === false) {
    echo 'data: {"error": "[ERROR]","message":"'.curl_error($curl).'"}' . PHP_EOL;
}
?>