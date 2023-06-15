<?php

// /usr/bin/php -q /home/mentor.chatgptvietnam.org/public_html/cron/customer_credits_packs.php > /dev/null 2>&1

$server		 = 'localhost';
$username    = 'ment_chatgpt';
$password    = 'ePEIm@d#vwgCnkOH';
$database    = 'ment_chatgpt';
$time_secs   = 660;
$connect	 = mysqli_connect($server, $username, $password,$database);
mysqli_query($connect, "SET NAMES 'utf8'");

$query = "SELECT * FROM customer_credits_packs WHERE id_credit_pack = 1 AND status = 'awaiting_payment' LIMIT 5";
$result = mysqli_query($connect,$query);
if ($result){
	$rows = [];
    while ($row=mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    if ($rows && sizeof($rows) > 0) {
    	foreach ($rows as $key => $row) {

			$PHPSESSID = '0q412tp5pe4v9cl0bnh47ua5hm';
			if (rand(0,1)) $PHPSESSID = '4a7kle5tnkdm5b0ef1fuuv9hji';
			if (rand(0,1)) $PHPSESSID = 'q7reuc7adfdfc0bt6g67sj8tc7';

    		$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://mentor.chatgptvietnam.org/admin/modules/sales/action.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => 'id_order='.$row['id_order'].'&action=aprove_payment',
			CURLOPT_HTTPHEADER => array(
				'cookie: PHPSESSID='.$PHPSESSID.';',
				'Content-Type: application/x-www-form-urlencoded'
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			echo $response;
    	}
    }
}

?>