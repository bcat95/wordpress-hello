<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/conn.php";

if (isset($_SERVER['REDIRECT_URL'])) $postslug = str_replace('/api/vn/mail/','',strtolower($_SERVER['REDIRECT_URL']));
else $postslug = "";

$te=explode('/',$postslug);

if (sizeof($te) == 1){
	if ($te[0] == 'open'){

		if (isset($_GET['mkey'])) $mkey = $_GET['mkey']; else $mkey = "";
		if (isset($_GET['mid'])) $mid = $_GET['mid']; else $mid = 0;
		if (isset($_GET['uid'])) $uid = $_GET['uid']; else $uid = "";

		mail_log_open($mid,$mkey,$uid);
		exit();
	}
}

function mail_log_open($id,$mail_key,$us_id=''){
	global $connect;

	$WHERE = " 1 AND ";

	if ($id) $WHERE .= " id = '".addslashes($id)."' AND";
	if ($mail_key != "") $WHERE .= " mail_key = '".addslashes($mail_key)."' AND ";
	if ($us_id != "") $WHERE .= " us_id = '".addslashes($us_id)."' AND ";
	$time_update = time();

	$query = "UPDATE mentor_mail_log SET status = 2, time_update = '".$time_update."' WHERE $WHERE status = 1 LIMIT 1";
	// echo $query;
	if ($connect->query($query) === TRUE) {
		echo 'true';
	} else {
		$query = "UPDATE mentor_mail_log SET status = 2, time_update = '".$time_update."' WHERE mail_key = '".addslashes($mail_key)."' status = 1 LIMIT 1";
		if ($connect->query($query) === TRUE) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
}
?>