<?php
    require_once("../../inc/includes.php");
    session_start();
    unset($_SESSION["id_customer"]);
    header("Location:".$base_url."/");
    exit;
?>