<?php

$server		 = 'localhost';
$username    = 'ment_chatgpt';
$password    = 'ePEIm@d#vwgCnkOH';
$database    = 'ment_chatgpt';
$time_secs   = 660;
$connect	 = mysqli_connect($server, $username, $password,$database);

mysqli_query($connect, "SET NAMES 'utf8'");

date_default_timezone_set('asia/ho_chi_minh');
?>