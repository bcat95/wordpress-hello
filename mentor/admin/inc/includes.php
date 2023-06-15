<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/Autoloader.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

$prompts = new Prompts();
$categories = new Categories();
$customers = new Customers();
$messages = new Messages();
$languages = new Languages();
$menus = new Menus();
$users = new Users();
$analytics = new Analytics();
$settings = new Settings();
$prompts_output = new PromptsOutput();
$prompts_tone = new PromptsTone();
$prompts_writing = new PromptsWriting();
$prompts_categories = new PromptsCategories();
$credits_packs = new CreditsPacks();
$pages = new Pages();
$theme = new Theme();
$seo = new Seo();
$update = new Update();
$customer_credits_packs = new CustomerCreditsPacks();
$config = $settings->get(1);
if(isset($_SESSION['admin_id'])){   
    $getUser = $users->get($_SESSION['admin_id']);
}

$saltnumber = 2545215614;
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

$base_url = url();
$base_url = $base_url;

function redirect($url, $message = '', $status = 'success') {
  $_SESSION['action'] = $status;
  $_SESSION['action_message'] = $message;
  header("location: $url");
  die();
  exit();
}


// Escape single quotes and other special characters before saving to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (is_string($value)) {
            $_POST[$key] = addslashes($value);
        }
    }


    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . "/public_uploads/";
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $uniqueID = uniqid();
        $imageName = $uniqueID . "." . $fileExtension;
        $destinationPath = $uploadDirectory . $imageName;

        // Try to move the uploaded file to the destination folder
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destinationPath)) {
            $_POST['image'] = $imageName;
        } else {
            redirect('/admin/', 'Error moving file to destination folder, check folder permission.', 'error');
        }
    }
}

function truncateText($text, $maxLength) {
  if (strlen($text) > $maxLength) {
    // Trunca o texto na última palavra antes do limite de caracteres
    $text = substr($text, 0, strrpos(substr($text, 0, $maxLength), ' '));
    // Adiciona '...' ao final do texto
    $text .= '...';
  }
  // Converte quebras de linha em tags HTML <br>
  $text = nl2br($text);
  return $text;
}


if(isset($use_stripe) && $use_stripe){
    // Define a chave secreta da API do Stripe
    if($config->stripe_test_mode){
        \Stripe\Stripe::setApiKey($config->stripe_api_key_test);
    }else{
        \Stripe\Stripe::setApiKey($config->stripe_api_key_production);
    }
}

//Find payment intent stripe
function findPaymentIntent($config, $id_order) {

    // Liste os PaymentIntents
    $paymentIntents = \Stripe\PaymentIntent::all();

    // Encontre o PaymentIntent com a id_order correspondente
    $paymentIntentFound = null;
    foreach ($paymentIntents->data as $paymentIntent) {
        if (isset($paymentIntent->metadata['id_order']) && $paymentIntent->metadata['id_order'] === $id_order) {
            $paymentIntentFound = $paymentIntent;
            break;
        }
    }
    
    return $paymentIntentFound;
}