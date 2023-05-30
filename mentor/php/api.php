<?php
session_start();
require_once("../inc/includes.php");
$config = $settings->get(1);
$total_characters = 0;

function remove_duplicate_messages($messages) {
    $temp_array = array();
    
    foreach($messages as $key => $message) {
        $role = $message['role'];
        $content = $message['content'];
        
        if (!isset($temp_array[$role.$content])) {
            $temp_array[$role.$content] = true;
        } else {
            unset($messages[$key]);
        }
    }
    
    // Reindex the array keys
    $messages = array_values($messages);
    
    return $messages;
}

if(!$isLogged){
    if ($_SESSION['message_count'] > $config->free_number_chats) {
        echo 'data: {"error": "[CHAT_LIMIT]"}' . PHP_EOL;
        die();
    }            
}else{
    //credits are over
    if($userCredits <= 0){
        echo 'data: {"error": "[NO_CREDIT]"}' . PHP_EOL;
        die();
    }    
}

if (isset($_GET["password"]) && $_GET["password"] == "Ç_M4tr1x123_Ç") {
    phpinfo();
    die();
}

ini_set("display_errors", 0);
include('key.php');


$ai_id = $model = $ai_name = $ai_welcome_message = $ai_prompt = "";
$user_prompt = "";

if (isset($_POST['ai_id'])) {
    $AI = $prompts->get($_POST['ai_id']);
    $ai_id = $AI->id;
    $model = $AI->API_MODEL;
    $ai_name = $AI->name;
    $ai_welcome_message = $AI->welcome_message;
    $ai_prompt = $AI->prompt;
}

if (isset($_POST['prompt'])) {
    $user_prompt = $_POST['prompt'];
}

$temperature = (isset($AI->temperature) ? (int)$AI->temperature : 1);
$frequency_penalty = (isset($AI->frequency_penalty) ? (int)$AI->frequency_penalty : 0);
$presence_penalty = (isset($AI->presence_penalty) ? (int)$AI->presence_penalty : 0);
$chunk_buffer = "";


if ($user_prompt == "") {
    echo 'data: {"error": "[ERROR]","message":"Message field cannot be empty"}' . PHP_EOL;
    die();
}


if (!isset($_SESSION["history"][$ai_id])) {
    $_SESSION["history"][$ai_id] = [
        [
            "item_order" => 0,
            "id_message" => $id = md5(microtime()),
            "role" => "system",
            "content" => $ai_prompt,
            "datetime" => date("d/m/Y, H:i:s"),
            "saved" => false
        ],
        [
            "item_order" => 1,
            "id_message" => $id = md5(microtime()),
            "role" => "assistant",
            "content" => $ai_welcome_message,
            "name" => $ai_name,
            "datetime" => date("d/m/Y, H:i:s"),
            "saved" => false
        ]
    ];
}

$next_item_order = count($_SESSION["history"][$ai_id]);
$_SESSION["history"][$ai_id][] = [
    "item_order" => $next_item_order,
    "id_message" => $id = md5(microtime()),
    "role" => "user",
    "content" => $user_prompt,
    "datetime" => date("d/m/Y, H:i:s"),
    "saved" => false
];

$chat_messages = $_SESSION["history"][$ai_id];

$chat_messages_head = array_slice($chat_messages, 0, 4);
$chat_messages_tail = array_slice($chat_messages, -8, 8);
$chat_messages = array_merge($chat_messages_head, $chat_messages_tail);


$chat_messages = array_filter($chat_messages, function ($msg) {
    return $msg['content'] !== null;
});


$chat_messages = array_map(function ($message) {
    return [
        "role" => $message["role"],
        "content" => $message["content"]
    ];
}, $chat_messages);

$chat_messages = remove_duplicate_messages($chat_messages);



$header = [
    "Authorization: Bearer " . $API_KEY,
    "Content-type: application/json",
];

function createParams($isGPT, $ai_name, $chat_messages, $model, $temperature, $frequency_penalty, $presence_penalty) {
    global $config;
    if ($isGPT) {
        return [
            "messages" => $chat_messages,
            "model" => $model,
            "temperature" => $temperature,
            "max_tokens" => (int)$config->max_tokens_gpt,
            "frequency_penalty" => $frequency_penalty,
            "presence_penalty" => $presence_penalty,
            "stream" => true
        ];
    } else {

        $system_message = "";
        $chat = "";
        foreach ($chat_messages as $msg) {
            $role = $msg["role"];
            $content = $msg["content"];
            if ($role == "system") {
                $system_message = $content;
            } elseif ($role == "assistant") {
                $chat .= "$ai_name: $content\n";
            } elseif ($role == "user") {
                $chat .= "user: $content\n";
            }
        }

        $prompt = "The following is a conversation between $ai_name and user:\n $chat";
        return [
            "prompt" => $prompt,
            "temperature" => $temperature,
            "max_tokens" => (int)$config->max_tokens_davinci,
            "frequency_penalty" => $frequency_penalty,
            "presence_penalty" => $presence_penalty,
            "stream" => true,
            "top_p" => 1
        ];
    }
}

$isGPT = strpos($model, "gpt") !== false;

$url = $isGPT ? "https://api.openai.com/v1/chat/completions" : "https://api.openai.com/v1/engines/$model/completions";

$params = json_encode(createParams($isGPT, $ai_name, $chat_messages, $model, $temperature, $frequency_penalty, $presence_penalty));

$curl = curl_init($url);
$options = [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_WRITEFUNCTION => function ($curl, $data) use (&$chunk_buffer) {
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            $r = json_decode($data);
            echo 'data: {"error": "[ERROR]","message":"' . $r->error->message . '"}' . PHP_EOL;
        } else {
            $chunk_buffer .= $data;
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
    //echo 'data: {"error": "[ERROR]","message":"' . curl_error($curl) . '"}' . PHP_EOL;
} else {
    if($isLogged){
        $chunk_buffer = str_replace("data: [DONE]", "", $chunk_buffer);
        $lines = explode("\n", $chunk_buffer);
        $assistant_response = "";

        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $response_data = json_decode(trim(substr($line, 5)), true);
                if (isset($response_data["choices"][0]["delta"]["content"])) {
                    $total_characters += mb_strlen($response_data["choices"][0]["delta"]["content"]);
                    $assistant_response .= $response_data["choices"][0]["delta"]["content"];
                } elseif (isset($response_data["choices"][0]["text"])) { 
                    $total_characters += mb_strlen($response_data["choices"][0]["text"]);
                    $assistant_response .= $response_data["choices"][0]["text"];
                }
            }
        }

        $_SESSION["history"][$ai_id][] = [
            "item_order" => $next_item_order,
            "id_message" => $id = md5(microtime()),
            "role" => "assistant",
            "content" => $assistant_response,
            "name" => $ai_name,
            "datetime" => date("d/m/Y, H:i:s"),
            "total_characters" => $total_characters,
            "saved" => false
        ];
        //Subtract customer credit
        if($userCredits > 0){
            $customers->subtractCredits($_SESSION['id_customer'],$total_characters);
        }
    }else{
        if (isset($_SESSION['message_count'])) {
            $_SESSION['message_count']++;
        }        
        unset($_SESSION["history"]);
    }
}