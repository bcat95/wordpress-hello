<?php
session_start();
require_once("inc/includes.php");
$email = addslashes($_POST['email']);
$password = md5($_POST['password'].addslashes($saltnumber));

// Validate email
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

if (!$email) {
    header("location:/admin/login?error=invalid_email");
    die();
}

$get = $users->getData($email,$password);
if($get->id){
    $_SESSION['admin_id'] = $get->id;
    $_SESSION['admin_name'] = $get->name;
    $_SESSION['admin_email'] = $get->email;
    header("location:/admin/");
    die();
}else{
    header("location:/admin/login?error=invalid_data&email=" . urlencode($_POST['email']));
    die();
}

