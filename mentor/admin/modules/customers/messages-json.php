<?php
$module_name = "customers";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

if (!isset($_GET['thread']) || empty($_GET['thread'])) {
    echo json_encode(["error" => "An error occurred while finding the thread"]);
    die();
}

$getMessages = $messages->getByThread($_GET['thread']);
$rowCount = $getMessages->rowCount();

if ($rowCount > 0) {
    // Get customer name and AI name
    $idCustomer = null;
    $idAI = null;

    $messagesArray = array();

    foreach ($getMessages as $showMessages) {
        if ($showMessages->role != 'system') {
            if (!$idCustomer) {
                $idCustomer = $showMessages->id_customer;
                $getCustomerName = $customers->get($idCustomer);
            }

            if (!$idAI) {
                $idAI = $showMessages->id_prompt;
                $getAIName = $prompts->get($idAI);
            }

            if ($showMessages->role == 'assistant') {
                $name = $getAIName->name;
                $thread_class = 'thread-ai';
                $image = $base_url . "/public_uploads/" . $getAIName->image;
            } else {
                $name = $getCustomerName->name;
                $thread_class = 'thread-user';
                $image = '';
            }

            $message = htmlentities($showMessages->content, ENT_QUOTES, 'UTF-8');
            $time = $showMessages->created_at;

            $messagesArray[] = array(
                'name' => htmlentities($name, ENT_QUOTES, 'UTF-8'),
                'thread_class' => htmlentities($thread_class, ENT_QUOTES, 'UTF-8'),
                'image' => htmlentities($image, ENT_QUOTES, 'UTF-8'),
                'message' => $message,
                'time' => htmlentities($time, ENT_QUOTES, 'UTF-8'),
            );
        }
    }

    echo json_encode($messagesArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "An error occurred while finding the thread"]);
}
?>